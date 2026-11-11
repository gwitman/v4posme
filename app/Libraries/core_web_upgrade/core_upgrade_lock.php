<?php
//posme:2026-11-10
namespace App\Libraries\core_web_upgrade;

use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_rules;

/**
 * Candado y modo mantenimiento de la actualización.
 *
 * - Candado: WRITEPATH/posme_agent/lock.json con el mismo formato
 *   {deployID, time, since} y la misma exclusión (lock.guard + flock) que usa
 *   core_deploy_agent, para que un despliegue remoto y una actualización nunca
 *   corran a la vez en la misma instalación.
 * - Mantenimiento: WRITEPATH/posme_upgrade/maintenance.json
 *   {upgradeID, since, by, refreshed}; caduca 2 h después del último refresco.
 *   Las lecturas son livianas porque _BaseController las hace en cada petición.
 */
class core_upgrade_lock
{
    /** Carpeta del candado compartido. */
    static function lockDir()
    {
        return WRITEPATH . core_upgrade_rules::LOCK_DIR;
    }

    /** Archivo de mantenimiento. */
    static function maintenanceFile()
    {
        return WRITEPATH . core_upgrade_rules::CLIENT_DIR . '/maintenance.json';
    }

    /**
     * Candado vigente o null.
     *
     * @return array|null
     */
    static function current()
    {
        $lock = core_deploy_files::readJson(self::lockDir() . '/lock.json');
        if (!$lock || !isset($lock['time']) || (time() - (int) $lock['time']) > core_deploy_rules::LOCK_TTL) {
            return null;
        }
        return $lock;
    }

    /**
     * Toma el candado para la actualización (uno a la vez por instalación).
     *
     * @param string $upgradeID Identificador con el formato de deployID.
     * @return void
     */
    static function acquire($upgradeID)
    {
        core_deploy_files::assertDeployID($upgradeID);
        $dir    = core_deploy_files::ensureDir(self::lockDir());
        $handle = fopen($dir . '/lock.guard', 'c');
        if (!$handle || !flock($handle, LOCK_EX)) {
            throw new \Exception('No se pudo obtener el candado de la instalación.');
        }
        try {
            $lock = core_deploy_files::readJson($dir . '/lock.json');
            if ($lock && isset($lock['deployID'], $lock['time']) && $lock['deployID'] !== $upgradeID && (time() - (int) $lock['time']) <= core_deploy_rules::LOCK_TTL) {
                throw new \Exception('Ya hay otro despliegue o actualización en curso (' . $lock['deployID'] . ').');
            }
            core_deploy_files::writeJson($dir . '/lock.json', ['deployID' => $upgradeID, 'time' => time(), 'since' => helper_getDateTime()]);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    /**
     * Exige que el candado pertenezca a la actualización y lo refresca.
     *
     * @param string $upgradeID Identificador.
     * @return void
     */
    static function refresh($upgradeID)
    {
        $dir    = self::lockDir();
        $handle = @fopen(core_deploy_files::ensureDir($dir) . '/lock.guard', 'c');
        if (!$handle || !flock($handle, LOCK_EX)) {
            throw new \Exception('No se pudo obtener el candado de la instalación.');
        }
        try {
            $lock = core_deploy_files::readJson($dir . '/lock.json');
            if (!$lock || !isset($lock['deployID'], $lock['time']) || $lock['deployID'] !== $upgradeID || (time() - (int) $lock['time']) > core_deploy_rules::LOCK_TTL) {
                throw new \Exception('La actualización no tiene el candado activo; inicie de nuevo.');
            }
            core_deploy_files::writeJson($dir . '/lock.json', ['deployID' => $upgradeID, 'time' => time(), 'since' => isset($lock['since']) ? $lock['since'] : helper_getDateTime()]);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    /**
     * Suelta el candado si pertenece a la actualización.
     *
     * @param string $upgradeID Identificador.
     * @return bool
     */
    static function release($upgradeID)
    {
        $file = self::lockDir() . '/lock.json';
        $lock = core_deploy_files::readJson($file);
        if ($lock && isset($lock['deployID']) && $lock['deployID'] === $upgradeID) {
            return @unlink($file);
        }
        return false;
    }

    // ------------------------------------------------------------ mantenimiento

    /**
     * Activa el modo mantenimiento.
     *
     * @param string $upgradeID Identificador.
     * @param string $by        Usuario que lo activa.
     * @return void
     */
    static function maintenanceOn($upgradeID, $by)
    {
        core_deploy_files::ensureDir(dirname(self::maintenanceFile()));
        core_deploy_files::writeJson(self::maintenanceFile(), [
            'upgradeID' => $upgradeID,
            'since'     => helper_getDateTime(),
            'by'        => (string) $by,
            'refreshed' => time(),
        ]);
    }

    /**
     * Refresca el mantenimiento de la actualización en curso.
     *
     * @param string $upgradeID Identificador.
     * @return void
     */
    static function maintenanceRefresh($upgradeID)
    {
        $data = core_deploy_files::readJson(self::maintenanceFile());
        if ($data && isset($data['upgradeID']) && $data['upgradeID'] === $upgradeID) {
            $data['refreshed'] = time();
            core_deploy_files::writeJson(self::maintenanceFile(), $data);
        }
    }

    /**
     * Desactiva el mantenimiento (solo si es de esta actualización, salvo $force).
     *
     * @param string $upgradeID Identificador.
     * @param bool   $force     Desactivar aunque sea de otra corrida.
     * @return void
     */
    static function maintenanceOff($upgradeID, $force = false)
    {
        $data = core_deploy_files::readJson(self::maintenanceFile());
        if ($data && ($force || (isset($data['upgradeID']) && $data['upgradeID'] === $upgradeID))) {
            @unlink(self::maintenanceFile());
        }
    }

    /**
     * Estado del mantenimiento sin dependencias (se llama en cada petición).
     *
     * @return array ['active' => bool, 'since' => string|null, 'by' => string|null, 'upgradeID' => string|null]
     */
    static function maintenanceState()
    {
        $off  = ['active' => false, 'since' => null, 'by' => null, 'upgradeID' => null];
        $file = self::maintenanceFile();
        if (!is_file($file)) {
            return $off;
        }
        $data = json_decode((string) @file_get_contents($file), true);
        if (!is_array($data) || !isset($data['refreshed']) || (time() - (int) $data['refreshed']) > core_upgrade_rules::MAINTENANCE_TTL) {
            return $off;
        }
        return [
            'active'    => true,
            'since'     => isset($data['since']) ? $data['since'] : null,
            'by'        => isset($data['by']) ? $data['by'] : null,
            'upgradeID' => isset($data['upgradeID']) ? $data['upgradeID'] : null,
        ];
    }

    /**
     * Indica si el controlador debe responder 503 por mantenimiento.
     *
     * @param string $controllerClass Clase completa del controlador.
     * @return bool
     */
    static function blocks($controllerClass)
    {
        $name = strtolower(substr(strrchr('\\' . $controllerClass, '\\'), 1));
        if (in_array($name, ['core_upgrade', 'core_upgrade_server', 'core_acount'], true)) {
            return false;
        }
        $state = self::maintenanceState();
        return $state['active'];
    }

    /**
     * Prepara la respuesta 503 (JSON si la petición es AJAX, HTML simple si no).
     *
     * @param object $request  Petición CI4.
     * @param object $response Respuesta CI4.
     * @return object Respuesta lista para send().
     */
    static function maintenanceResponse($request, $response)
    {
        $message = 'El sistema se está actualizando. Intente de nuevo en unos minutos.';
        $isAjax  = method_exists($request, 'isAJAX') && $request->isAJAX();
        $accept  = method_exists($request, 'getHeaderLine') ? strtolower((string) $request->getHeaderLine('Accept')) : '';
        $response->setStatusCode(503);
        $response->setHeader('Retry-After', '300');
        if ($isAjax || strpos($accept, 'application/json') !== false) {
            $response->setContentType('application/json');
            $response->setBody(json_encode(['error' => true, 'message' => $message, 'maintenance' => true], JSON_UNESCAPED_UNICODE));
        } else {
            $response->setContentType('text/html');
            $response->setBody('<!DOCTYPE html><html><head><meta charset="utf-8"><title>posMe en mantenimiento</title></head>'
                . '<body style="font-family:sans-serif;background:#f5f6f8;"><div style="max-width:520px;margin:80px auto;padding:24px;background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.15);">'
                . '<h2 style="margin-top:0;">posMe en mantenimiento</h2><p>' . htmlspecialchars($message) . '</p></div></body></html>');
        }
        return $response;
    }
}
