<?php
//posme:2026-11-10
namespace App\Libraries\core_web_upgrade;

use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_package;

/**
 * Aplicación, reversión y verificación de los archivos de un paquete.
 *
 * Cada archivo reemplazado se respalda antes de escribirse. El orden reduce el
 * riesgo de dejar la instalación a medias: nuevos → Libraries/Models/Helpers →
 * Views → Controllers → archivos críticos (_BaseController, el propio
 * actualizador, Routes.php). En vendor, vendor/composer/* y vendor/autoload.php
 * van al final. Al terminar se limpia opcache y se hace un health check HTTP a
 * la propia instalación; si falla, se revierte el paquete en la misma petición.
 */
class core_upgrade_files
{
    /**
     * Peso de orden de una ruta (menor = antes).
     *
     * @param string $path   Ruta relativa a ROOTPATH.
     * @param bool   $exists Si el destino ya existe.
     * @return int
     */
    static function orderWeight($path, $exists)
    {
        $lower = strtolower($path);
        $critical = [
            'app/controllers/_basecontroller.php'     => 91,
            'app/controllers/core_upgrade.php'        => 92,
            'app/controllers/core_upgrade_server.php' => 92,
            'app/libraries/core_web_upgrade.php'      => 93,
            'app/config/routes.php'                   => 99,
            'vendor/autoload.php'                     => 99,
        ];
        if (isset($critical[$lower])) {
            return $critical[$lower];
        }
        if (strpos($lower, 'app/libraries/core_web_upgrade/') === 0) {
            return 93;
        }
        if (strpos($lower, 'vendor/composer/') === 0) {
            return 95;
        }
        if (!$exists) {
            return 0;
        }
        if (strpos($lower, 'app/libraries/') === 0 || strpos($lower, 'app/models/') === 0 || strpos($lower, 'app/helpers/') === 0) {
            return 10;
        }
        if (strpos($lower, 'app/views/') === 0) {
            return 20;
        }
        if (strpos($lower, 'app/controllers/') === 0) {
            return 30;
        }
        return 15;
    }

    /**
     * Compara staging contra lo instalado.
     *
     * @param array $files [destino => ['sha256','size']]
     * @return array ['update' => [...], 'create' => [...], 'unchanged' => int]
     */
    static function compare(array $files)
    {
        $result = ['update' => [], 'create' => [], 'unchanged' => 0];
        foreach ($files as $path => $info) {
            $target = ROOTPATH . $path;
            if (!is_file($target)) {
                $result['create'][] = $path;
            } elseif (hash_file('sha256', $target) !== $info['sha256']) {
                $result['update'][] = $path;
            } else {
                $result['unchanged']++;
            }
        }
        return $result;
    }

    /**
     * Respaldar y copiar los archivos de un paquete en orden seguro.
     *
     * @param array  $files      [destino => ['sha256','size']] de la extracción.
     * @param string $stagingDir Carpeta staging del paquete.
     * @param string $backupDir  Carpeta de respaldo de este paquete.
     * @param string $mode       Modo (reclasifica cada ruta antes de escribir).
     * @param string $zip        Nombre del zip.
     * @return array ['updated' => [...], 'created' => [...], 'unchanged' => int, 'errors' => [...]]
     */
    static function apply(array $files, $stagingDir, $backupDir, $mode, $zip)
    {
        $packages = core_upgrade_rules::packages($mode);
        $prefix   = $packages[$zip]['target'];
        $list     = [];
        foreach ($files as $path => $info) {
            $list[] = ['path' => $path, 'info' => $info, 'weight' => self::orderWeight($path, is_file(ROOTPATH . $path))];
        }
        usort($list, function ($a, $b) {
            return $a['weight'] === $b['weight'] ? strcmp($a['path'], $b['path']) : ($a['weight'] < $b['weight'] ? -1 : 1);
        });

        $results = ['updated' => [], 'created' => [], 'unchanged' => 0, 'errors' => []];
        foreach ($list as $item) {
            $path = $item['path'];
            // Defensa en profundidad: se vuelve a clasificar con la regla vigente
            $relative = strpos($path, $prefix) === 0 ? substr($path, strlen($prefix)) : null;
            $class    = $relative === null ? null : core_upgrade_rules::classify($relative, $mode, $zip);
            if (!$class || $class['category'] !== 'update' || $class['target'] !== $path) {
                $results['errors'][] = ['path' => $path, 'error' => 'No autorizado por las reglas locales'];
                continue;
            }
            $source = $stagingDir . '/' . $path;
            $target = ROOTPATH . $path;
            try {
                if (is_link($target)) {
                    throw new \Exception('El destino es un enlace simbólico');
                }
                if (!is_file($source) || hash_file('sha256', $source) !== $item['info']['sha256']) {
                    throw new \Exception('El archivo de staging no coincide con la extracción');
                }
                if (is_file($target)) {
                    if (hash_file('sha256', $target) === $item['info']['sha256']) {
                        $results['unchanged']++;
                        continue;
                    }
                    $backup = $backupDir . '/' . $path;
                    if (!is_dir(dirname($backup)) && !@mkdir(dirname($backup), 0755, true) && !is_dir(dirname($backup))) {
                        throw new \Exception('No se pudo crear la carpeta de respaldo');
                    }
                    if (!@copy($target, $backup)) {
                        throw new \Exception('No se pudo respaldar');
                    }
                    // Se registra antes de escribir: si la escritura queda a medias, el rollback lo restaura
                    $results['updated'][] = $path;
                    core_deploy_files::writeAtomic($target, file_get_contents($source));
                } else {
                    $results['created'][] = $path;
                    core_deploy_files::writeAtomic($target, file_get_contents($source));
                }
                if (function_exists('opcache_invalidate')) {
                    @opcache_invalidate($target, true);
                }
            } catch (\Throwable $ex) {
                $results['errors'][] = ['path' => $path, 'error' => $ex->getMessage()];
            }
        }
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
        return $results;
    }

    /**
     * Revierte un paquete: restaura los respaldados y elimina los creados.
     *
     * @param array  $results   Resultado de apply().
     * @param string $backupDir Carpeta de respaldo del paquete.
     * @return array ['restored' => int, 'removed' => int, 'errors' => [...]]
     */
    static function rollback(array $results, $backupDir)
    {
        $restored = 0;
        $removed  = 0;
        $errors   = [];
        foreach (array_reverse(isset($results['updated']) ? $results['updated'] : []) as $path) {
            if (core_deploy_package::safeRelativePath($path) !== $path || core_upgrade_rules::matchesList($path, core_upgrade_rules::$HARD_DENY)) {
                continue;
            }
            $backup = $backupDir . '/' . $path;
            try {
                if (!is_file($backup)) {
                    throw new \Exception('No existe el respaldo');
                }
                core_deploy_files::writeAtomic(ROOTPATH . $path, file_get_contents($backup));
                if (function_exists('opcache_invalidate')) {
                    @opcache_invalidate(ROOTPATH . $path, true);
                }
                $restored++;
            } catch (\Throwable $ex) {
                $errors[] = ['path' => $path, 'error' => $ex->getMessage()];
            }
        }
        foreach (isset($results['created']) ? $results['created'] : [] as $path) {
            if (core_deploy_package::safeRelativePath($path) !== $path || core_upgrade_rules::matchesList($path, core_upgrade_rules::$HARD_DENY)) {
                continue;
            }
            if (is_file(ROOTPATH . $path) && @unlink(ROOTPATH . $path)) {
                $removed++;
            }
        }
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
        return ['restored' => $restored, 'removed' => $removed, 'errors' => $errors];
    }

    /**
     * Verifica lo instalado contra la extracción.
     *
     * @param array $files [destino => ['sha256','size']]
     * @return array ['checked' => int, 'mismatch' => [...]]
     */
    static function verify(array $files)
    {
        $mismatch = [];
        foreach ($files as $path => $info) {
            $target = ROOTPATH . $path;
            if (!is_file($target) || hash_file('sha256', $target) !== $info['sha256']) {
                $mismatch[] = $path;
            }
        }
        return ['checked' => count($files), 'mismatch' => $mismatch];
    }

    /**
     * Health check HTTP a la propia instalación (core_upgrade/upgrade_health).
     *
     * @return array ['ok' => bool, 'message' => string]
     */
    static function health()
    {
        if (!function_exists('curl_init')) {
            return ['ok' => false, 'message' => 'La extensión curl no está disponible para el health check.'];
        }
        $url  = rtrim(base_url(), '/') . '/core_upgrade/upgrade_health?t=' . time();
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            // Es la propia instalación: puede tener un certificado local no reconocido
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $body   = curl_exec($curl);
        $errno  = curl_errno($curl);
        $error  = curl_error($curl);
        $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($errno) {
            return ['ok' => false, 'message' => 'Sin respuesta de la instalación: ' . $error];
        }
        $json = json_decode((string) $body, true);
        $data = is_array($json) && isset($json['data']) && is_array($json['data']) ? $json['data'] : $json;
        if ($status !== 200 || !is_array($data) || !isset($data['ok']) || $data['ok'] !== true) {
            return ['ok' => false, 'message' => 'La instalación no respondió correctamente (HTTP ' . $status . ').'];
        }
        return ['ok' => true, 'message' => 'HTTP 200, BD disponible, protocolo ' . (isset($data['protocol']) ? (int) $data['protocol'] : 0) . '.'];
    }
}
