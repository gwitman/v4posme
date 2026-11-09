<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Validación, empaquetado y extracción segura de paquetes ZIP de posMe.
 *
 * Flujo:
 *  1. inspect(): valida el ZIP subido (estructura, Zip Slip, symlinks, zip bomb)
 *     y clasifica cada archivo con core_deploy_rules::classify().
 *  2. build(): genera un ZIP limpio solo con los archivos autorizados, con rutas
 *     normalizadas "app/..." y un manifiesto con SHA-256 por archivo. Revisa la
 *     sintaxis de cada PHP antes de enviarlo.
 *  3. extract() (en el agente): vuelve a validar con sus propias reglas, extrae
 *     entrada por entrada (nunca extractTo completo) y verifica los hashes.
 */
class core_deploy_package
{
    /**
     * Normaliza una ruta de entrada del ZIP y la rechaza si es peligrosa.
     *
     * @param string $name Nombre de la entrada.
     * @return string|null Ruta relativa segura o null si es peligrosa.
     */
    static function safeRelativePath($name)
    {
        if (!is_string($name) || $name === '' || strpos($name, "\0") !== false) {
            return null;
        }
        $path = str_replace('\\', '/', $name);
        if ($path[0] === '/' || preg_match('#^[A-Za-z]:#', $path) || strpos($path, '://') !== false) {
            return null;
        }
        $parts = [];
        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }
            if ($segment === '..') {
                return null;
            }
            if (preg_match('/[\x00-\x1F<>:"|?*]/', $segment)) {
                return null;
            }
            $parts[] = $segment;
        }
        return count($parts) ? implode('/', $parts) : null;
    }

    /**
     * Indica si la entrada del ZIP es un enlace simbólico (atributos UNIX).
     *
     * @param \ZipArchive $zip   Archivo abierto.
     * @param int         $index Índice de la entrada.
     * @return bool
     */
    static function isSymlink(\ZipArchive $zip, $index)
    {
        $opsys = 0;
        $attr  = 0;
        if ($zip->getExternalAttributesIndex($index, $opsys, $attr) && $opsys === \ZipArchive::OPSYS_UNIX) {
            return (($attr >> 16) & 0170000) === 0120000;
        }
        return false;
    }

    /**
     * Abre un ZIP validando la firma y la consistencia.
     *
     * @param string $zipPath Ruta del archivo.
     * @return \ZipArchive
     */
    static function open($zipPath)
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception('La extensión zip de PHP no está disponible.');
        }
        if (!is_file($zipPath) || filesize($zipPath) < 22) {
            throw new \Exception('El archivo no existe o está vacío.');
        }
        $handle = fopen($zipPath, 'rb');
        $magic  = fread($handle, 4);
        fclose($handle);
        if ($magic !== "PK\x03\x04") {
            throw new \Exception('El archivo no es un ZIP válido.');
        }
        $zip    = new \ZipArchive();
        $result = $zip->open($zipPath, \ZipArchive::CHECKCONS);
        if ($result !== true) {
            throw new \Exception('El ZIP está dañado o es inconsistente (código ' . $result . ').');
        }
        return $zip;
    }

    /**
     * Inspecciona un ZIP y clasifica su contenido.
     *
     * @param string $zipPath Ruta del ZIP.
     * @return array Informe con errors, prefix, files, totals.
     */
    static function inspect($zipPath)
    {
        $zip     = self::open($zipPath);
        $errors  = [];
        $entries = [];
        $total   = 0;

        if ($zip->numFiles > core_deploy_rules::MAX_ENTRIES) {
            $zip->close();
            throw new \Exception('El ZIP tiene demasiadas entradas (' . $zip->numFiles . ').');
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if ($stat === false) {
                $errors[] = 'Entrada ilegible #' . $i;
                continue;
            }
            $name = $stat['name'];
            if (substr($name, -1) === '/' || substr($name, -1) === '\\') {
                continue; // directorio
            }
            $safe = self::safeRelativePath($name);
            if ($safe === null) {
                $errors[] = 'Ruta peligrosa rechazada: ' . substr($name, 0, 200);
                continue;
            }
            if (self::isSymlink($zip, $i)) {
                $errors[] = 'Enlace simbólico rechazado: ' . $safe;
                continue;
            }
            if ($stat['size'] > core_deploy_rules::MAX_FILE_BYTES) {
                $errors[] = 'Archivo demasiado grande: ' . $safe;
                continue;
            }
            if ($stat['size'] > 1048576 && $stat['comp_size'] > 0 && ($stat['size'] / $stat['comp_size']) > core_deploy_rules::MAX_RATIO) {
                $errors[] = 'Razón de compresión sospechosa (zip bomb): ' . $safe;
                continue;
            }
            $total += $stat['size'];
            $entries[] = ['index' => $i, 'path' => $safe, 'size' => (int) $stat['size']];
        }
        $zip->close();

        if ($total > core_deploy_rules::MAX_TOTAL_BYTES) {
            throw new \Exception('El contenido descomprimido supera el límite permitido.');
        }

        $prefix = self::detectPrefix(array_column($entries, 'path'));
        if ($prefix === null) {
            $errors[] = 'No se encontró la carpeta app/ (se espera app/Controllers, app/Views, etc.).';
        }

        $files  = [];
        $totals = ['update' => 0, 'preserved' => 0, 'ignored' => 0, 'rejected' => 0, 'bytes' => 0];
        foreach ($entries as $entry) {
            if ($prefix === null) {
                break;
            }
            if ($entry['path'] === core_deploy_rules::MANIFEST_NAME) {
                continue;
            }
            if ($prefix !== '' && strpos($entry['path'], $prefix) !== 0) {
                $files[] = $entry + ['relative' => $entry['path'], 'category' => 'ignored', 'reason' => 'Fuera de la carpeta raíz del paquete'];
                $totals['ignored']++;
                continue;
            }
            $relative = substr($entry['path'], strlen($prefix));
            $class    = core_deploy_rules::classify($relative);
            $files[]  = $entry + ['relative' => $relative, 'category' => $class['category'], 'reason' => $class['reason']];
            $totals[$class['category']]++;
            if ($class['category'] === 'update') {
                $totals['bytes'] += $entry['size'];
            }
        }

        return [
            'errors' => $errors,
            'prefix' => $prefix,
            'files'  => $files,
            'totals' => $totals,
            'sha256' => hash_file('sha256', $zipPath),
            'size'   => filesize($zipPath),
        ];
    }

    /**
     * Detecta la carpeta raíz del paquete ("" si app/ está en la raíz o "v4posme/").
     *
     * @param array $paths Rutas relativas.
     * @return string|null Prefijo o null si no hay carpeta app/.
     */
    static function detectPrefix(array $paths)
    {
        $prefixes = [];
        foreach ($paths as $path) {
            if (preg_match('#^((?:[^/]+/)?)app/(Controllers|Models|Libraries|Helpers|Views)/#', $path, $match)
                || preg_match('#^((?:[^/]+/)?)app/Config/Routes\.php$#', $path, $match)) {
                $prefixes[$match[1]] = true;
            }
        }
        if (count($prefixes) === 0) {
            return null;
        }
        if (count($prefixes) > 1) {
            // Preferir la raíz si existe; si hay varias carpetas, es ambiguo
            if (isset($prefixes[''])) {
                return '';
            }
            return null;
        }
        return array_keys($prefixes)[0];
    }

    /**
     * Revisa la sintaxis de un archivo PHP sin ejecutarlo.
     *
     * @param string $code Código fuente.
     * @return string|null Mensaje de error o null si es válido.
     */
    static function lintPhp($code)
    {
        try {
            token_get_all($code, TOKEN_PARSE);
            return null;
        } catch (\ParseError $ex) {
            return 'línea ' . $ex->getLine() . ': ' . $ex->getMessage();
        } catch (\Throwable $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Construye el paquete limpio con los archivos autorizados y su manifiesto.
     *
     * @param string $sourceZip Ruta del ZIP subido por el usuario.
     * @param string $targetZip Ruta del ZIP limpio a generar.
     * @param array  $report    Informe de inspect().
     * @param array  $meta      Datos extra para el manifiesto (deployID, usuario...).
     * @return array ['manifest' => array, 'lintErrors' => array, 'sha256' => string, 'size' => int]
     */
    static function build($sourceZip, $targetZip, array $report, array $meta = [])
    {
        if (count($report['errors'])) {
            throw new \Exception('El paquete tiene errores de validación; no se puede construir.');
        }

        $source = self::open($sourceZip);
        $target = new \ZipArchive();
        if ($target->open($targetZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            $source->close();
            throw new \Exception('No se pudo crear el paquete limpio.');
        }

        $manifest   = ['version' => core_deploy_rules::VERSION, 'meta' => $meta, 'createdOn' => helper_getDateTime(), 'sourceSha256' => $report['sha256'], 'files' => []];
        $lintErrors = [];

        foreach ($report['files'] as $file) {
            if ($file['category'] !== 'update') {
                continue;
            }
            $content = $source->getFromIndex($file['index']);
            if ($content === false) {
                $lintErrors[$file['relative']] = 'No se pudo leer del ZIP';
                continue;
            }
            if (strtolower(pathinfo($file['relative'], PATHINFO_EXTENSION)) === 'php') {
                $lint = self::lintPhp($content);
                if ($lint !== null) {
                    $lintErrors[$file['relative']] = $lint;
                }
            }
            $manifest['files'][$file['relative']] = ['sha256' => hash('sha256', $content), 'size' => strlen($content)];
            $target->addFromString($file['relative'], $content);
        }
        $source->close();

        ksort($manifest['files']);
        $target->addFromString(core_deploy_rules::MANIFEST_NAME, json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $target->close();

        return [
            'manifest'   => $manifest,
            'lintErrors' => $lintErrors,
            'sha256'     => hash_file('sha256', $targetZip),
            'size'       => filesize($targetZip),
        ];
    }

    /**
     * Extrae un paquete limpio a la carpeta staging verificando cada archivo.
     * Se ejecuta en el agente: vuelve a aplicar las reglas locales del servidor.
     *
     * @param string $zipPath    ZIP recibido.
     * @param string $stagingDir Carpeta temporal (se recrea vacía).
     * @return array ['manifest' => array, 'extracted' => int, 'errors' => array]
     */
    static function extract($zipPath, $stagingDir)
    {
        $zip     = self::open($zipPath);
        $rawJson = $zip->getFromName(core_deploy_rules::MANIFEST_NAME);
        if ($rawJson === false) {
            $zip->close();
            throw new \Exception('El paquete no contiene ' . core_deploy_rules::MANIFEST_NAME . '.');
        }
        $manifest = json_decode($rawJson, true);
        if (!is_array($manifest) || !isset($manifest['files']) || !is_array($manifest['files'])) {
            $zip->close();
            throw new \Exception('Manifiesto del paquete inválido.');
        }

        core_deploy_files::removeDir($stagingDir);
        core_deploy_files::ensureDir($stagingDir);
        $base   = realpath($stagingDir);
        $errors = [];
        $count  = 0;
        $seen   = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $name = $stat['name'];
            if (substr($name, -1) === '/' || $name === core_deploy_rules::MANIFEST_NAME) {
                continue;
            }
            $safe = self::safeRelativePath($name);
            if ($safe === null || self::isSymlink($zip, $i)) {
                $errors[] = 'Entrada rechazada: ' . substr($name, 0, 200);
                continue;
            }
            $class = core_deploy_rules::classify($safe);
            if ($class['category'] !== 'update') {
                $errors[] = 'No autorizado por el servidor: ' . $safe . ' (' . $class['reason'] . ')';
                continue;
            }
            if (!isset($manifest['files'][$safe])) {
                $errors[] = 'Archivo fuera del manifiesto: ' . $safe;
                continue;
            }
            if ($stat['size'] > core_deploy_rules::MAX_FILE_BYTES) {
                $errors[] = 'Archivo demasiado grande: ' . $safe;
                continue;
            }

            $content = $zip->getFromIndex($i);
            if ($content === false || hash('sha256', $content) !== $manifest['files'][$safe]['sha256']) {
                $errors[] = 'Hash distinto al manifiesto: ' . $safe;
                continue;
            }

            $destination = $base . '/' . $safe;
            $parent      = dirname($destination);
            if (!is_dir($parent) && !@mkdir($parent, 0755, true) && !is_dir($parent)) {
                $errors[] = 'No se pudo crear carpeta para: ' . $safe;
                continue;
            }
            // La carpeta real debe quedar dentro de staging (Zip Slip)
            $realParent = realpath($parent);
            if ($realParent === false || strpos(str_replace('\\', '/', $realParent) . '/', str_replace('\\', '/', $base) . '/') !== 0) {
                $errors[] = 'Ruta fuera del directorio autorizado: ' . $safe;
                continue;
            }
            if (file_put_contents($destination, $content) === false) {
                $errors[] = 'No se pudo escribir: ' . $safe;
                continue;
            }
            $seen[$safe] = true;
            $count++;
        }
        $zip->close();

        foreach (array_keys($manifest['files']) as $path) {
            if (!isset($seen[$path])) {
                $errors[] = 'Falta en el paquete: ' . $path;
            }
        }

        return ['manifest' => $manifest, 'extracted' => $count, 'errors' => $errors];
    }
}
