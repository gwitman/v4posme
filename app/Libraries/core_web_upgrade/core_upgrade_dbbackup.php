<?php
//posme:2026-11-13
namespace App\Libraries\core_web_upgrade;

use App\Libraries\core_web_deploy\core_deploy_files;

/**
 * Respaldo y restauración manual de la base de datos (pestaña "Respaldo de base
 * de datos" de core_upgrade/byPop3).
 *
 * Reutiliza core_upgrade_dbdump (respaldo por bloques reanudable, verificación
 * con sha256 y restauración en streaming) con su propia carpeta:
 *   WRITEPATH/posme_upgrade/db_backups/<backupID>/   respaldos manuales y subidos.
 *   WRITEPATH/posme_upgrade/backups/<upgradeID>/     respaldos de la actualización (solo lectura aquí).
 *   WRITEPATH/posme_upgrade/db_uploads/              subidas por bloques y zip temporales de descarga.
 *   WRITEPATH/posme_upgrade/db_restore.json          restauración en curso.
 *
 * La restauración toma el candado de la instalación y activa el modo
 * mantenimiento; si falla a mitad, ambos se conservan para reintentar o cancelar.
 */
class core_upgrade_dbbackup
{
    const CONFIRM_WORD     = 'RESTAURAR';
    const UPLOAD_CHUNK     = 1048576;      // 1 MB por bloque de subida
    const MAX_UPLOAD_BYTES = 2147483647;   // 2 GB por archivo .zip
    const ORIGIN_MANUAL    = 'manual';
    const ORIGIN_UPGRADE   = 'upgrade';

    // ------------------------------------------------------------ rutas

    /** Carpeta de los respaldos manuales y subidos. */
    static function manualDir()
    {
        return WRITEPATH . core_upgrade_rules::CLIENT_DIR . '/db_backups';
    }

    /** Carpeta de subidas y zip temporales. */
    static function uploadsDir()
    {
        return WRITEPATH . core_upgrade_rules::CLIENT_DIR . '/db_uploads';
    }

    /** Archivo de la restauración en curso. */
    static function restoreFile()
    {
        return WRITEPATH . core_upgrade_rules::CLIENT_DIR . '/db_restore.json';
    }

    /**
     * Carpeta base según el origen del respaldo.
     *
     * @param string $origin manual | upgrade.
     * @return string
     */
    static function baseDir($origin)
    {
        if ($origin === self::ORIGIN_MANUAL) {
            return self::manualDir();
        }
        if ($origin === self::ORIGIN_UPGRADE) {
            return core_upgrade_dbdump::backupsDir();
        }
        throw new \Exception('Origen de respaldo inválido.');
    }

    // ------------------------------------------------------------ consulta

    /**
     * Resumen de un respaldo para la pantalla.
     *
     * @param string $origin   manual | upgrade.
     * @param string $backupID Identificador.
     * @param array  $manifest Manifiesto.
     * @return array
     */
    private static function summary($origin, $backupID, array $manifest)
    {
        return [
            'backupID'  => $backupID,
            'origin'    => $origin,
            'source'    => isset($manifest['source']) ? $manifest['source'] : ($origin === self::ORIGIN_UPGRADE ? 'upgrade' : 'manual'),
            'createdOn' => isset($manifest['createdOn']) ? $manifest['createdOn'] : '',
            'dbName'    => isset($manifest['dbName']) ? $manifest['dbName'] : '',
            'by'        => isset($manifest['by']) ? $manifest['by'] : '',
            'note'      => isset($manifest['note']) ? $manifest['note'] : '',
            'fileName'  => isset($manifest['uploadedName']) ? $manifest['uploadedName'] : '',
            'tables'    => isset($manifest['tables']) && is_array($manifest['tables']) ? count($manifest['tables']) : 0,
            'rows'      => isset($manifest['rows']) ? (int) $manifest['rows'] : 0,
            'bytes'     => isset($manifest['bytes']) ? (int) $manifest['bytes'] : 0,
            'complete'  => !empty($manifest['complete']),
            'verified'  => !empty($manifest['verified']),
        ];
    }

    /**
     * Respaldos guardados (manuales, subidos y de la actualización), más reciente primero.
     *
     * @return array ['backups' => [], 'freeBytes' => int, 'restore' => array|null, 'busy' => string]
     */
    static function listAll()
    {
        $list = [];
        foreach ([self::ORIGIN_MANUAL, self::ORIGIN_UPGRADE] as $origin) {
            foreach (glob(self::baseDir($origin) . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
                $backupID = basename($dir);
                if (preg_match('/^[0-9]{14}_[a-f0-9]{8}$/', $backupID) !== 1) {
                    continue;
                }
                $manifest = core_deploy_files::readJson($dir . '/manifest.json');
                if (!$manifest) {
                    continue;
                }
                $list[] = self::summary($origin, $backupID, $manifest);
            }
        }
        usort($list, function ($a, $b) {
            return strcmp($b['backupID'], $a['backupID']);
        });
        $free = @disk_free_space(WRITEPATH);
        $lock = core_upgrade_lock::current();
        return [
            'backups'   => $list,
            'freeBytes' => $free === false ? null : (int) $free,
            'restore'   => self::restoreState(),
            'busy'      => $lock ? (string) $lock['deployID'] : '',
        ];
    }

    // ------------------------------------------------------------ generar respaldo

    /** Exige que no haya despliegue, actualización ni restauración en curso. */
    private static function assertIdle()
    {
        $lock = core_upgrade_lock::current();
        if ($lock) {
            throw new \Exception('Hay una actualización, despliegue o restauración en curso (' . $lock['deployID'] . '). Espere a que termine.');
        }
    }

    /**
     * Progreso del respaldo para la pantalla.
     *
     * @param array $manifest Manifiesto.
     * @return array
     */
    private static function progress(array $manifest)
    {
        return [
            'backupID'   => $manifest['backupID'],
            'done'       => !empty($manifest['complete']),
            'unitsDone'  => (int) $manifest['unitsDone'],
            'unitsTotal' => (int) $manifest['unitsTotal'],
            'tables'     => min((int) $manifest['cursor']['table'], count($manifest['order'])),
            'tablesAll'  => count($manifest['order']),
            'rows'       => (int) $manifest['rows'],
            'bytes'      => (int) $manifest['bytes'],
        ];
    }

    /**
     * Inicia un respaldo manual: estructura, rutinas y plan de páginas.
     *
     * @param object $db       Conexión CI4.
     * @param string $userName Usuario que lo genera.
     * @param string $note     Descripción opcional.
     * @return array Progreso.
     */
    static function createBegin($db, $userName, $note)
    {
        self::assertIdle();
        $backupID = core_deploy_files::newDeployID();
        core_deploy_files::ensureDir(self::manualDir());
        $manifest           = core_upgrade_dbdump::begin($db, $backupID, self::manualDir());
        $manifest['source'] = 'manual';
        $manifest['by']     = (string) $userName;
        $manifest['note']   = mb_substr(trim((string) $note), 0, 120);
        core_deploy_files::writeJson(self::manualDir() . '/' . $backupID . '/manifest.json', $manifest);
        return self::progress($manifest);
    }

    /**
     * Continúa el respaldo manual hasta agotar el presupuesto de tiempo.
     *
     * @param object $db       Conexión CI4.
     * @param string $backupID Identificador.
     * @param float  $deadline microtime(true) límite.
     * @return array Progreso.
     */
    static function createStep($db, $backupID, $deadline)
    {
        return self::progress(core_upgrade_dbdump::step($db, $backupID, $deadline, self::manualDir()));
    }

    /**
     * Verifica el respaldo manual recién generado.
     *
     * @param object $db       Conexión CI4.
     * @param string $backupID Identificador.
     * @return array Resultado de core_upgrade_dbdump::verify.
     */
    static function createVerify($db, $backupID)
    {
        return core_upgrade_dbdump::verify($db, $backupID, self::manualDir());
    }

    /**
     * Elimina un respaldo manual o subido (los de la actualización se depuran solos).
     *
     * @param string $backupID Identificador.
     * @return void
     */
    static function delete($backupID)
    {
        $dir   = core_upgrade_dbdump::dir($backupID, self::manualDir());
        $state = self::restoreState();
        if ($state && $state['origin'] === self::ORIGIN_MANUAL && $state['backupID'] === $backupID) {
            throw new \Exception('El respaldo se está usando en una restauración pendiente; termínela o cancélela primero.');
        }
        if (!is_dir($dir)) {
            throw new \Exception('No existe el respaldo ' . $backupID . '.');
        }
        if (!core_deploy_files::removeDir($dir)) {
            throw new \Exception('No se pudo eliminar por completo el respaldo ' . $backupID . '.');
        }
    }

    // ------------------------------------------------------------ descarga

    /**
     * Arma un .zip temporal del respaldo para descargarlo (las partes ya vienen en gzip).
     *
     * @param string $origin   manual | upgrade.
     * @param string $backupID Identificador.
     * @return array ['path' => string, 'name' => string]
     */
    static function zipFile($origin, $backupID)
    {
        if (!class_exists('\ZipArchive')) {
            throw new \Exception('PHP no tiene la extensión zip habilitada.');
        }
        $baseDir  = self::baseDir($origin);
        $dir      = core_upgrade_dbdump::dir($backupID, $baseDir);
        $manifest = core_upgrade_dbdump::manifest($backupID, $baseDir);
        if (empty($manifest['complete'])) {
            throw new \Exception('El respaldo no está completo; no se puede descargar.');
        }
        $tmpDir = core_deploy_files::ensureDir(self::uploadsDir());
        // Limpieza de zip temporales que quedaron de descargas anteriores (más de 1 h)
        foreach (glob($tmpDir . '/download_*.zip') ?: [] as $old) {
            if (time() - (int) @filemtime($old) > 3600) {
                @unlink($old);
            }
        }
        $path = $tmpDir . '/download_' . bin2hex(random_bytes(6)) . '.zip';
        $zip  = new \ZipArchive();
        if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('No se pudo crear el archivo .zip de descarga.');
        }
        // Solo se empaqueta lo que describe el manifiesto
        $files = [$manifest['schema']['file'], $manifest['routines']['file']];
        foreach ($manifest['tables'] as $info) {
            foreach ($info['parts'] as $part) {
                $files[] = $part['file'];
            }
        }
        $zip->addFile($dir . '/manifest.json', 'manifest.json');
        foreach ($files as $file) {
            if (!is_file($dir . '/' . $file)) {
                $zip->close();
                @unlink($path);
                throw new \Exception('Falta la parte ' . $file . ' del respaldo.');
            }
            $zip->addFile($dir . '/' . $file, $file);
            if (method_exists($zip, 'setCompressionName')) {
                $zip->setCompressionName($file, \ZipArchive::CM_STORE);
            }
        }
        if (!$zip->close()) {
            @unlink($path);
            throw new \Exception('No se pudo cerrar el archivo .zip de descarga.');
        }
        $dbName = preg_replace('/[^A-Za-z0-9_\-]/', '', isset($manifest['dbName']) ? (string) $manifest['dbName'] : '');
        return ['path' => $path, 'name' => 'posme_bd_' . ($dbName !== '' ? $dbName . '_' : '') . $backupID . '.zip'];
    }

    // ------------------------------------------------------------ subida

    /** Ruta del archivo parcial de una subida. */
    private static function uploadPath($uploadID)
    {
        core_deploy_files::assertDeployID($uploadID);
        return self::uploadsDir() . '/upload_' . $uploadID . '.part';
    }

    /**
     * Inicia una subida por bloques.
     *
     * @param int $size Tamaño total del archivo.
     * @return array ['uploadID' => string, 'chunk' => int]
     */
    static function uploadBegin($size)
    {
        $size = (int) $size;
        if ($size <= 0 || $size > self::MAX_UPLOAD_BYTES) {
            throw new \Exception('El archivo está vacío o supera el máximo de 2 GB.');
        }
        $free = @disk_free_space(WRITEPATH);
        // Se necesita espacio para el .zip y para extraerlo
        if ($free !== false && $free < $size * 2.2) {
            throw new \Exception('No hay espacio suficiente en disco para subir y extraer el respaldo.');
        }
        $tmpDir = core_deploy_files::ensureDir(self::uploadsDir());
        // Subidas abandonadas (más de 1 día)
        foreach (glob($tmpDir . '/upload_*.part') ?: [] as $old) {
            if (time() - (int) @filemtime($old) > 86400) {
                @unlink($old);
            }
        }
        $uploadID = core_deploy_files::newDeployID();
        if (file_put_contents(self::uploadPath($uploadID), '') === false) {
            throw new \Exception('No se pudo preparar la subida.');
        }
        return ['uploadID' => $uploadID, 'chunk' => self::UPLOAD_CHUNK];
    }

    /**
     * Agrega un bloque al archivo parcial. Si el bloque ya estaba (reintento) no se duplica.
     *
     * @param string $uploadID Identificador.
     * @param int    $offset   Posición del bloque.
     * @param string $tmpPath  Archivo temporal recibido por PHP.
     * @return array ['received' => int]
     */
    static function uploadChunk($uploadID, $offset, $tmpPath)
    {
        $path = self::uploadPath($uploadID);
        if (!is_file($path)) {
            throw new \Exception('La subida no existe o venció; empiece de nuevo.');
        }
        clearstatcache(true, $path);
        $current = (int) filesize($path);
        $offset  = (int) $offset;
        $length  = (int) filesize($tmpPath);
        if ($length <= 0 || $length > self::UPLOAD_CHUNK) {
            throw new \Exception('Bloque de subida inválido.');
        }
        if ($offset + $length <= $current) {
            return ['received' => $current];
        }
        if ($offset !== $current) {
            throw new \Exception('El bloque no corresponde a la posición esperada (' . $current . ').');
        }
        if ($current + $length > self::MAX_UPLOAD_BYTES) {
            throw new \Exception('El archivo supera el máximo de 2 GB.');
        }
        $data = file_get_contents($tmpPath);
        if ($data === false || file_put_contents($path, $data, FILE_APPEND | LOCK_EX) !== $length) {
            throw new \Exception('No se pudo guardar el bloque recibido.');
        }
        return ['received' => $current + $length];
    }

    /**
     * Cancela una subida y borra el archivo parcial.
     *
     * @param string $uploadID Identificador.
     * @return void
     */
    static function uploadCancel($uploadID)
    {
        $path = self::uploadPath($uploadID);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /** Nombre de archivo permitido dentro del respaldo. */
    private static function allowedEntry($name)
    {
        return preg_match('#^(000_schema\.sql\.gz|999_routines\.sql\.gz|data/[0-9]{4}_[0-9]{5}\.sql\.gz)$#', (string) $name) === 1;
    }

    /**
     * Termina la subida: valida el .zip, lo extrae como respaldo nuevo y lo verifica.
     *
     * @param object $db       Conexión CI4.
     * @param string $uploadID Identificador de la subida.
     * @param int    $size     Tamaño total esperado.
     * @param string $fileName Nombre original del archivo.
     * @param string $userName Usuario que lo sube.
     * @return array Resumen del respaldo creado.
     */
    static function uploadFinish($db, $uploadID, $size, $fileName, $userName)
    {
        if (!class_exists('\ZipArchive')) {
            throw new \Exception('PHP no tiene la extensión zip habilitada.');
        }
        $path = self::uploadPath($uploadID);
        clearstatcache(true, $path);
        if (!is_file($path) || (int) filesize($path) !== (int) $size) {
            throw new \Exception('El archivo no llegó completo; vuelva a subirlo.');
        }
        $backupID = core_deploy_files::newDeployID();
        $dir      = core_upgrade_dbdump::dir($backupID, self::manualDir());
        $zip      = new \ZipArchive();
        $opened   = false;
        try {
            if ($zip->open($path) !== true) {
                throw new \Exception('El archivo no es un .zip válido.');
            }
            $opened = true;
            $raw = $zip->getFromName('manifest.json');
            $manifest = $raw === false ? null : json_decode($raw, true);
            if (!is_array($manifest) || !isset($manifest['schema']['file'], $manifest['routines']['file'], $manifest['tables'], $manifest['order']) || !is_array($manifest['tables']) || !is_array($manifest['order'])) {
                throw new \Exception('El .zip no es un respaldo de posMe (falta o está dañado manifest.json).');
            }
            if (empty($manifest['complete'])) {
                throw new \Exception('El respaldo del .zip está incompleto.');
            }
            // Todas las rutas del manifiesto deben tener el formato del respaldo (evita rutas maliciosas)
            $files = [$manifest['schema']['file'], $manifest['routines']['file']];
            foreach ($manifest['tables'] as $table => $info) {
                if (!is_array($info) || !isset($info['parts']) || !is_array($info['parts']) || !in_array($table, $manifest['order'], true)) {
                    throw new \Exception('El manifiesto del respaldo está dañado (' . $table . ').');
                }
                foreach ($info['parts'] as $part) {
                    $files[] = isset($part['file']) ? $part['file'] : '';
                }
            }
            foreach ($files as $file) {
                if (!self::allowedEntry($file) || $zip->locateName($file) === false) {
                    throw new \Exception('Falta o no es válida la parte "' . $file . '" en el .zip.');
                }
            }

            core_deploy_files::ensureDir($dir . '/data');
            foreach ($files as $file) {
                $stream = $zip->getStream($file);
                $out    = $stream ? fopen($dir . '/' . $file, 'wb') : false;
                if (!$stream || !$out) {
                    throw new \Exception('No se pudo extraer ' . $file . '.');
                }
                stream_copy_to_stream($stream, $out);
                fclose($stream);
                fclose($out);
            }
            $zip->close();
            $opened = false;

            $manifest['backupID']     = $backupID;
            $manifest['source']       = 'upload';
            $manifest['by']           = (string) $userName;
            $manifest['uploadedName'] = mb_substr(basename((string) $fileName), 0, 120);
            $manifest['uploadedOn']   = helper_getDateTime();
            $manifest['verified']     = false;
            core_deploy_files::writeJson($dir . '/manifest.json', $manifest);

            $result = core_upgrade_dbdump::verify($db, $backupID, self::manualDir());
            if (!$result['ok']) {
                throw new \Exception('El respaldo subido no pasó la verificación: ' . implode(' | ', array_slice($result['errors'], 0, 5)));
            }
        } catch (\Throwable $ex) {
            if ($opened) {
                $zip->close();
            }
            core_deploy_files::removeDir($dir);
            @unlink($path);
            throw $ex;
        }
        @unlink($path);
        return self::summary(self::ORIGIN_MANUAL, $backupID, core_upgrade_dbdump::manifest($backupID, self::manualDir()));
    }

    // ------------------------------------------------------------ restauración

    /**
     * Restauración en curso (o null).
     *
     * @return array|null
     */
    static function restoreState()
    {
        $state = core_deploy_files::readJson(self::restoreFile());
        return $state && isset($state['restoreID'], $state['origin'], $state['backupID']) ? $state : null;
    }

    /**
     * Inicia la restauración: confirma, toma el candado y activa mantenimiento.
     *
     * @param string $origin   manual | upgrade.
     * @param string $backupID Respaldo a restaurar.
     * @param string $confirm  Palabra de confirmación.
     * @param string $userName Usuario.
     * @return array ['restoreID' => string, 'backupID' => string]
     */
    static function restoreBegin($origin, $backupID, $confirm, $userName)
    {
        if (!hash_equals(self::CONFIRM_WORD, (string) $confirm)) {
            throw new \Exception('Escriba ' . self::CONFIRM_WORD . ' (en mayúsculas, sin espacios) para confirmar.');
        }
        if (self::restoreState()) {
            throw new \Exception('Ya hay una restauración pendiente; continúela o cancélela.');
        }
        $baseDir  = self::baseDir($origin);
        $manifest = core_upgrade_dbdump::manifest($backupID, $baseDir);
        if (empty($manifest['complete']) || empty($manifest['verified'])) {
            throw new \Exception('Solo se puede restaurar un respaldo completo y verificado.');
        }
        $restoreID = core_deploy_files::newDeployID();
        core_upgrade_lock::acquire($restoreID);
        try {
            core_upgrade_lock::maintenanceOn($restoreID, $userName);
            core_deploy_files::writeJson(self::restoreFile(), [
                'restoreID' => $restoreID,
                'origin'    => $origin,
                'backupID'  => $backupID,
                'by'        => (string) $userName,
                'startedOn' => helper_getDateTime(),
                'started'   => false,
                'lastError' => '',
            ]);
        } catch (\Throwable $ex) {
            core_upgrade_lock::maintenanceOff($restoreID);
            core_upgrade_lock::release($restoreID);
            throw $ex;
        }
        return ['restoreID' => $restoreID, 'backupID' => $backupID];
    }

    /**
     * Restaura por bloques: estructura → datos → rutinas.
     *
     * @param object $db        Conexión CI4.
     * @param string $restoreID Identificador de la restauración.
     * @param float  $deadline  microtime(true) límite.
     * @return array ['done','restored','total','phase']
     */
    static function restoreStep($db, $restoreID, $deadline)
    {
        $state = self::restoreState();
        if (!$state || $state['restoreID'] !== $restoreID) {
            throw new \Exception('No hay una restauración en curso con ese identificador.');
        }
        // Retoma el candado si venció (reintento tras una pausa larga)
        if (core_upgrade_lock::current()) {
            core_upgrade_lock::refresh($restoreID);
        } else {
            core_upgrade_lock::acquire($restoreID);
        }
        $maintenance = core_upgrade_lock::maintenanceState();
        if (!$maintenance['active']) {
            core_upgrade_lock::maintenanceOn($restoreID, $state['by']);
        } else {
            core_upgrade_lock::maintenanceRefresh($restoreID);
        }
        try {
            $result = core_upgrade_dbdump::restoreStep($db, $state['backupID'], empty($state['started']), $deadline, self::baseDir($state['origin']));
        } catch (\Throwable $ex) {
            $state['started']   = true;
            $state['lastError'] = $ex->getMessage();
            core_deploy_files::writeJson(self::restoreFile(), $state);
            throw $ex;
        }
        $state['started']   = true;
        $state['lastError'] = '';
        if ($result['done']) {
            @unlink(self::restoreFile());
            core_upgrade_lock::maintenanceOff($restoreID);
            core_upgrade_lock::release($restoreID);
        } else {
            core_deploy_files::writeJson(self::restoreFile(), $state);
        }
        return $result;
    }

    /**
     * Cancela una restauración pendiente: libera candado y mantenimiento.
     * La BD puede quedar a medio restaurar.
     *
     * @param string $restoreID Identificador.
     * @return void
     */
    static function restoreCancel($restoreID)
    {
        $state = self::restoreState();
        if (!$state || $state['restoreID'] !== $restoreID) {
            throw new \Exception('No hay una restauración en curso con ese identificador.');
        }
        @unlink(self::restoreFile());
        core_upgrade_lock::maintenanceOff($restoreID);
        core_upgrade_lock::release($restoreID);
    }
}
