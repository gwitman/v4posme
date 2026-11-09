<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Utilidades de archivos del despliegue: carpetas protegidas, escritura atómica,
 * JSON comprimido y limpieza de respaldos antiguos.
 */
class core_deploy_files
{
    /**
     * Crea la carpeta (recursiva) y la protege contra acceso web con .htaccess e index.html.
     *
     * @param string $dir Ruta absoluta.
     * @return string La misma ruta.
     */
    static function ensureDir($dir)
    {
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \Exception('No se pudo crear el directorio: ' . self::shortPath($dir));
        }
        if (!is_file($dir . '/.htaccess')) {
            @file_put_contents($dir . '/.htaccess', "<IfModule authz_core_module>\n    Require all denied\n</IfModule>\n<IfModule !authz_core_module>\n    Deny from all\n</IfModule>\n");
        }
        if (!is_file($dir . '/index.html')) {
            @file_put_contents($dir . '/index.html', '');
        }
        return $dir;
    }

    /**
     * Escribe un archivo de forma atómica (temporal + rename) creando la carpeta.
     *
     * @param string $path    Ruta destino.
     * @param string $content Contenido.
     * @return void
     */
    static function writeAtomic($path, $content)
    {
        $dir = dirname($path);
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \Exception('No se pudo crear el directorio: ' . self::shortPath($dir));
        }
        $tmp = $path . '.tmp_' . bin2hex(random_bytes(4));
        if (file_put_contents($tmp, $content, LOCK_EX) === false) {
            throw new \Exception('No se pudo escribir: ' . self::shortPath($path));
        }
        if (!@rename($tmp, $path)) {
            // En Windows rename no reemplaza archivos abiertos: intentar copiar
            if (!@copy($tmp, $path)) {
                @unlink($tmp);
                throw new \Exception('No se pudo reemplazar: ' . self::shortPath($path));
            }
            @unlink($tmp);
        }
    }

    /**
     * Guarda un arreglo como JSON (opcionalmente comprimido con gzip).
     *
     * @return void
     */
    static function writeJson($path, $data, $gzip = false)
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
        if ($json === false) {
            throw new \Exception('No se pudo codificar JSON: ' . json_last_error_msg());
        }
        self::writeAtomic($path, $gzip ? gzencode($json, 6) : $json);
    }

    /**
     * Lee un JSON (detecta gzip por la firma 1F 8B).
     *
     * @return array|null
     */
    static function readJson($path)
    {
        if (!is_file($path)) {
            return null;
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            return null;
        }
        if (strncmp($raw, "\x1f\x8b", 2) === 0) {
            $raw = gzdecode($raw);
            if ($raw === false) {
                throw new \Exception('Archivo comprimido dañado: ' . basename($path));
            }
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Elimina una carpeta completa.
     *
     * @return bool
     */
    static function removeDir($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir) || is_link($dir)) {
            return @unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (!self::removeDir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return @rmdir($dir);
    }

    /**
     * Conserva solo las N carpetas más recientes dentro de $parent.
     *
     * @param string $parent Carpeta que contiene una subcarpeta por despliegue.
     * @param int    $keep   Cantidad a conservar.
     * @param string $except Subcarpeta que nunca se borra (despliegue en curso).
     * @return void
     */
    static function pruneDirs($parent, $keep, $except = '')
    {
        if (!is_dir($parent)) {
            return;
        }
        $dirs = [];
        foreach (scandir($parent) as $item) {
            if ($item === '.' || $item === '..' || $item === $except || !is_dir($parent . '/' . $item)) {
                continue;
            }
            $dirs[$item] = filemtime($parent . '/' . $item);
        }
        arsort($dirs);
        foreach (array_slice(array_keys($dirs), $keep) as $old) {
            self::removeDir($parent . '/' . $old);
        }
    }

    /**
     * Valida un identificador de despliegue (evita rutas maliciosas).
     *
     * @return string
     */
    static function assertDeployID($deployID)
    {
        if (!is_string($deployID) || preg_match('/^[0-9]{14}_[a-f0-9]{8}$/', $deployID) !== 1) {
            throw new \Exception('Identificador de despliegue inválido.');
        }
        return $deployID;
    }

    /**
     * Genera un identificador de despliegue "AAAAMMDDhhmmss_xxxxxxxx".
     *
     * @return string
     */
    static function newDeployID()
    {
        $stamp = preg_replace('/[^0-9]/', '', helper_getDateTime());
        return substr(str_pad($stamp, 14, '0'), 0, 14) . '_' . bin2hex(random_bytes(4));
    }

    /**
     * Ruta corta para mensajes (no expone la ruta absoluta del servidor).
     *
     * @return string
     */
    static function shortPath($path)
    {
        $path = str_replace('\\', '/', (string) $path);
        foreach (['ROOTPATH', 'WRITEPATH'] as $constant) {
            if (defined($constant)) {
                $base = str_replace('\\', '/', constant($constant));
                if ($base !== '' && strpos($path, $base) === 0) {
                    return ($constant === 'WRITEPATH' ? 'writable/' : '') . ltrim(substr($path, strlen($base)), '/');
                }
            }
        }
        return basename($path);
    }
}
