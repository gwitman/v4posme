<?php
//posme:2026-11-10
namespace App\Libraries\core_web_upgrade;

use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_package;

/**
 * Extracción segura de los paquetes de la actualización inversa.
 *
 * Por cada entrada: ruta segura (sin "..", absolutas ni caracteres raros), sin
 * enlaces simbólicos, límites de tamaño y razón de compresión (zip bomb), quita
 * la carpeta raíz única igual al destino y clasifica con core_upgrade_rules.
 * Se extrae entrada por entrada a staging (nunca extractTo completo).
 */
class core_upgrade_package
{
    /**
     * Prefijo raíz a quitar: solo si TODAS las entradas cuelgan de una carpeta única igual a $root.
     *
     * @param array  $paths Rutas seguras de archivos.
     * @param string $root  Carpeta raíz esperada (app, vendor, public, Config).
     * @return string Prefijo ("" o "<root>/").
     */
    static function rootPrefix(array $paths, $root)
    {
        $first = [];
        foreach ($paths as $path) {
            $segment          = strpos($path, '/') === false ? '' : substr($path, 0, strpos($path, '/'));
            $first[$segment]  = true;
            if (count($first) > 1) {
                return '';
            }
        }
        return count($first) === 1 && isset($first[$root]) ? $root . '/' : '';
    }

    /**
     * Extrae un paquete a staging con las reglas de la actualización.
     *
     * @param string $zipPath    ZIP descargado (hash ya verificado).
     * @param string $stagingDir Carpeta temporal (se recrea vacía).
     * @param string $mode       Modo.
     * @param string $zipName    Nombre del zip.
     * @return array ['files' => [destino => [sha256,size]], 'protected' => [...], 'rejected' => [...], 'ignored' => int, 'lintErrors' => [...]]
     */
    static function extract($zipPath, $stagingDir, $mode, $zipName)
    {
        $packages = core_upgrade_rules::packages($mode);
        if (!isset($packages[$zipName])) {
            throw new \Exception('Paquete no permitido en el modo ' . $mode . ': ' . $zipName);
        }
        $package = $packages[$zipName];
        $zip     = core_deploy_package::open($zipPath);
        if ($zip->numFiles > core_upgrade_rules::MAX_ENTRIES) {
            $count = $zip->numFiles;
            $zip->close();
            throw new \Exception($zipName . ' tiene demasiadas entradas (' . $count . ').');
        }

        // 1. Inventario y validación de cada entrada
        $entries  = [];
        $rejected = [];
        $total    = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if ($stat === false) {
                $rejected[] = ['path' => '#' . $i, 'reason' => 'Entrada ilegible'];
                continue;
            }
            $name = $stat['name'];
            if (substr($name, -1) === '/' || substr($name, -1) === '\\') {
                continue;
            }
            $safe = core_deploy_package::safeRelativePath($name);
            if ($safe === null) {
                $rejected[] = ['path' => substr($name, 0, 200), 'reason' => 'Ruta peligrosa'];
                continue;
            }
            if (core_deploy_package::isSymlink($zip, $i)) {
                $rejected[] = ['path' => $safe, 'reason' => 'Enlace simbólico'];
                continue;
            }
            if ($stat['size'] > core_upgrade_rules::MAX_FILE_BYTES) {
                $rejected[] = ['path' => $safe, 'reason' => 'Archivo demasiado grande'];
                continue;
            }
            if ($stat['size'] > 1048576 && $stat['comp_size'] > 0 && ($stat['size'] / $stat['comp_size']) > core_upgrade_rules::MAX_RATIO) {
                $zip->close();
                throw new \Exception($zipName . ': razón de compresión sospechosa (zip bomb) en ' . $safe . '.');
            }
            $total += (int) $stat['size'];
            if ($total > core_upgrade_rules::MAX_TOTAL_BYTES) {
                $zip->close();
                throw new \Exception($zipName . ': el contenido descomprimido supera el límite permitido.');
            }
            $entries[] = ['index' => $i, 'path' => $safe, 'size' => (int) $stat['size']];
        }

        $prefix = self::rootPrefix(array_column($entries, 'path'), $package['root']);

        // 2. Extracción a staging
        core_deploy_files::removeDir($stagingDir);
        core_deploy_files::ensureDir($stagingDir);
        $base       = str_replace('\\', '/', realpath($stagingDir));
        $files      = [];
        $protected  = [];
        $ignored    = 0;
        $lintErrors = [];
        $lint       = strpos($package['target'], 'vendor/') !== 0;

        foreach ($entries as $entry) {
            $relative = $prefix !== '' ? substr($entry['path'], strlen($prefix)) : $entry['path'];
            if ($relative === '' || $relative === false) {
                continue;
            }
            $class = core_upgrade_rules::classify($relative, $mode, $zipName);
            if ($class['category'] === 'protected') {
                $protected[] = $class['target'];
                continue;
            }
            if ($class['category'] === 'ignored') {
                $ignored++;
                continue;
            }
            if ($class['category'] !== 'update') {
                $rejected[] = ['path' => $relative, 'reason' => $class['reason']];
                continue;
            }
            $content = $zip->getFromIndex($entry['index']);
            if ($content === false || strlen($content) !== $entry['size']) {
                $rejected[] = ['path' => $relative, 'reason' => 'No se pudo leer del ZIP'];
                continue;
            }
            if ($lint && strtolower(pathinfo($relative, PATHINFO_EXTENSION)) === 'php') {
                $error = core_deploy_package::lintPhp($content);
                if ($error !== null) {
                    $lintErrors[] = ['path' => $class['target'], 'error' => $error];
                    continue;
                }
            }
            $destination = $base . '/' . $class['target'];
            $parent      = dirname($destination);
            if (!is_dir($parent) && !@mkdir($parent, 0755, true) && !is_dir($parent)) {
                $rejected[] = ['path' => $relative, 'reason' => 'No se pudo crear la carpeta en staging'];
                continue;
            }
            // La carpeta real debe quedar dentro de staging (Zip Slip)
            $realParent = realpath($parent);
            if ($realParent === false || strpos(str_replace('\\', '/', $realParent) . '/', $base . '/') !== 0) {
                $rejected[] = ['path' => $relative, 'reason' => 'Ruta fuera de staging'];
                continue;
            }
            if (file_put_contents($destination, $content) === false) {
                $rejected[] = ['path' => $relative, 'reason' => 'No se pudo escribir en staging'];
                continue;
            }
            $files[$class['target']] = ['sha256' => hash('sha256', $content), 'size' => strlen($content)];
        }
        $zip->close();
        ksort($files);

        return [
            'zip'        => $zipName,
            'prefix'     => $prefix,
            'files'      => $files,
            'protected'  => $protected,
            'rejected'   => $rejected,
            'ignored'    => $ignored,
            'lintErrors' => $lintErrors,
        ];
    }
}
