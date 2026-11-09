<?php
//posme:2026-11-09
namespace App\Controllers;

use App\Libraries\core_web_deploy\core_deploy_rules;
use App\Libraries\core_web_deploy\core_deploy_crypto;
use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_package;
use App\Libraries\core_web_deploy\core_deploy_schema;
use App\Libraries\core_web_deploy\core_deploy_data;
use App\Libraries\core_web_deploy\core_deploy_cleanup;
use App\Libraries\core_web_deploy\core_deploy_company_files;

/**
 * Agente de despliegue remoto de posMe.
 *
 * Vive en cada instalación del cliente y recibe órdenes firmadas (HMAC-SHA256)
 * desde la consola core_merge::deploy. Ejecuta todo localmente en el servidor:
 * archivos, estructura y datos, con la conexión a BD que la instalación ya tiene
 * (las credenciales de MySQL nunca viajan).
 *
 * Solo responde si existe WRITEPATH/posme_agent/agent_config.php con
 * 'enabled' => true y un secreto de 64 caracteres. Ese archivo no forma parte
 * de ningún paquete, así que nunca se sobrescribe.
 */
class core_deploy_agent extends _BaseController
{
    private $agentDir;
    private $config;
    private $payload;
    private $deployID;

    /** Verifica que el servidor tenga respuesta del agente. */
    function ping()            { return $this->handle('ping', false); }
    /** Toma el candado de despliegue. */
    function begin()           { return $this->handle('begin', false); }
    /** Libera el candado. */
    function finish()          { return $this->handle('finish', true); }
    /** Recibe un bloque de archivo. */
    function upload_chunk()    { return $this->handle('upload_chunk', true); }
    /** Une los bloques y verifica el hash. */
    function upload_commit()   { return $this->handle('upload_commit', true); }
    /** Extrae el paquete a staging. */
    function extract()         { return $this->handle('extract', true); }
    /** Copia los archivos autorizados con respaldo. */
    function apply_files()     { return $this->handle('apply_files', true); }
    /** Revierte los archivos de un despliegue. */
    function rollback_files()  { return $this->handle('rollback_files', true); }
    /** Compara archivos instalados contra el manifiesto. */
    function verify_files()    { return $this->handle('verify_files', false); }
    /** Devuelve la estructura de la BD. */
    function schema_snapshot() { return $this->handle('schema_snapshot', false); }
    /** Ejecuta sentencias DDL aprobadas. */
    function exec_sql()        { return $this->handle('exec_sql', true); }
    /** Devuelve hashes por fila de las tablas pedidas. */
    function data_inspect()    { return $this->handle('data_inspect', false); }
    /** Aplica el paquete de datos + scripts en una transacción. */
    function data_apply()      { return $this->handle('data_apply', true); }
    /** Restaura el respaldo de datos de un despliegue. */
    function data_restore()    { return $this->handle('data_restore', true); }
    /** Devuelve el log de un despliegue. */
    function log()             { return $this->handle('log', false); }
    /** Limpieza: lista las instalaciones vecinas y lo que se liberaría (no borra). */
    function cleanup_scan()    { return $this->handle('cleanup_scan', false); }
    /** Limpieza: borra las categorías pedidas de una instalación (por tramos de tiempo). */
    function cleanup_apply()   { return $this->handle('cleanup_apply', false); }
    /** Configuración: lista los archivos de primer nivel de app/Config. */
    function config_list()     { return $this->handle('config_list', false); }
    /** Configuración: devuelve el contenido de un archivo de app/Config. */
    function config_read()     { return $this->handle('config_read', false); }
    /** Configuración: reemplaza un archivo de app/Config con respaldo previo. */
    function config_write()    { return $this->handle('config_write', false); }
    /** Configuración: restaura un archivo de app/Config desde un respaldo. */
    function config_restore()  { return $this->handle('config_restore', false); }
    /** Logos: reemplaza un logo de la lista fija con respaldo previo. */
    function logo_write()      { return $this->handle('logo_write', false); }

    /**
     * Flujo común: configuración → HTTPS → bloqueo por fallos → firma → candado → acción.
     *
     * @param string $action      Acción solicitada.
     * @param bool   $requireLock Exige que el candado pertenezca a este despliegue.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    private function handle($action, $requireLock)
    {
        try {
            @set_time_limit(300);
            $this->agentDir = WRITEPATH . core_deploy_rules::AGENT_DIR;

            if (strtoupper($this->request->getMethod()) !== 'POST') {
                return $this->deny(405, 'Método no permitido.');
            }

            $configFile = $this->agentDir . '/agent_config.php';
            $config     = is_file($configFile) ? include $configFile : null;
            if (!is_array($config) || empty($config['enabled']) || !isset($config['secret']) || strlen($config['secret']) < 64) {
                return $this->deny(404, 'Agente no habilitado.');
            }
            $this->config = $config;

            $host    = strtolower((string) $this->request->getServer('HTTP_HOST'));
            $isLocal = preg_match('/^(localhost|127\.0\.0\.1|\[::1\])(:\d+)?$/', $host) === 1;
            if (!$this->request->isSecure() && !$isLocal && empty($config['allowHttp'])) {
                return $this->deny(403, 'El agente solo acepta HTTPS.');
            }

            if ($this->isBlocked()) {
                return $this->deny(429, 'Demasiados intentos fallidos; espere unos minutos.');
            }

            $body      = (string) $this->request->getBody();
            $timestamp = (string) $this->request->getHeaderLine('X-Posme-Timestamp');
            $nonce     = (string) $this->request->getHeaderLine('X-Posme-Nonce');
            $signature = (string) $this->request->getHeaderLine('X-Posme-Signature');

            if (!ctype_digit($timestamp) || abs(time() - (int) $timestamp) > core_deploy_rules::SIGNATURE_WINDOW
                || preg_match('/^[a-f0-9]{32}$/', $nonce) !== 1
                || !core_deploy_crypto::verify($config['secret'], $action, $timestamp, $nonce, $body, $signature)) {
                $this->registerFailure();
                return $this->deny(401, 'Firma inválida o vencida.');
            }
            if (!$this->useNonce($nonce)) {
                $this->registerFailure();
                return $this->deny(401, 'Petición repetida.');
            }

            $payload       = json_decode($body, true);
            $this->payload = is_array($payload) ? $payload : [];
            $this->deployID = isset($this->payload['deployID']) ? core_deploy_files::assertDeployID($this->payload['deployID']) : null;

            if ($requireLock) {
                $lock = core_deploy_files::readJson($this->agentDir . '/lock.json');
                if (!$lock || $lock['deployID'] !== $this->deployID || (time() - $lock['time']) > core_deploy_rules::LOCK_TTL) {
                    throw new \Exception('El despliegue no tiene el candado activo; inicie de nuevo.');
                }
                core_deploy_files::writeJson($this->agentDir . '/lock.json', ['deployID' => $this->deployID, 'time' => time(), 'since' => $lock['since']]);
            }

            $method = 'action_' . $action;
            $data   = $this->$method();
            $this->log_line($action, 'OK');

            return $this->response->setJSON(['error' => false, 'message' => SUCCESS, 'data' => $data]);
        } catch (\Throwable $ex) {
            $this->log_line($action, 'ERROR ' . $ex->getMessage());
            return $this->response->setJSON(['error' => true, 'message' => $ex->getMessage()]);
        }
    }

    /** Respuesta de rechazo sin detalles internos. */
    private function deny($status, $message)
    {
        return $this->response->setStatusCode($status)->setJSON(['error' => true, 'message' => $message]);
    }

    /** Carpeta del despliegue actual. */
    private function deployDir($sub = '')
    {
        if ($this->deployID === null) {
            throw new \Exception('Falta deployID.');
        }
        $dir = core_deploy_files::ensureDir($this->agentDir . '/deploys/' . $this->deployID);
        return $sub === '' ? $dir : core_deploy_files::ensureDir($dir . '/' . $sub);
    }

    /** Línea de log del despliegue (sin secretos). */
    private function log_line($action, $message)
    {
        try {
            $line = '[' . helper_getDateTime() . '] ' . $action . ' ' . core_deploy_crypto::mask($message, [isset($this->config['secret']) ? $this->config['secret'] : '']) . "\n";
            $file = $this->deployID ? $this->agentDir . '/deploys/' . $this->deployID . '/log.txt' : $this->agentDir . '/agent.log';
            if (is_dir(dirname($file))) {
                file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
            }
        } catch (\Throwable $ex) {
            // El log nunca debe interrumpir la operación
        }
    }

    /** Registra un fallo de autenticación. */
    private function registerFailure()
    {
        core_deploy_files::ensureDir($this->agentDir);
        $file     = $this->agentDir . '/failures.json';
        $data     = core_deploy_files::readJson($file) ?: ['since' => time(), 'count' => 0];
        if (time() - $data['since'] > core_deploy_rules::AUTH_FAILURE_TTL) {
            $data = ['since' => time(), 'count' => 0];
        }
        $data['count']++;
        core_deploy_files::writeJson($file, $data);
    }

    /** Indica si el agente está bloqueado por fallos. */
    private function isBlocked()
    {
        $data = core_deploy_files::readJson($this->agentDir . '/failures.json');
        return $data && (time() - $data['since']) <= core_deploy_rules::AUTH_FAILURE_TTL && $data['count'] >= core_deploy_rules::MAX_AUTH_FAILURES;
    }

    /** Registra un nonce; false si ya se usó. Limpia los vencidos. */
    private function useNonce($nonce)
    {
        $dir = core_deploy_files::ensureDir($this->agentDir . '/nonces');
        if (mt_rand(1, 20) === 1) {
            foreach (glob($dir . '/*.n') ?: [] as $file) {
                if (time() - filemtime($file) > core_deploy_rules::SIGNATURE_WINDOW * 2) {
                    @unlink($file);
                }
            }
        }
        $handle = @fopen($dir . '/' . $nonce . '.n', 'x');
        if ($handle === false) {
            return false;
        }
        fclose($handle);
        return true;
    }

    /** Nombre de la BD configurada en la instalación. */
    private function dbName()
    {
        return defined('DB_BDNAME') ? DB_BDNAME : (string) db_connect()->getDatabase();
    }

    // ------------------------------------------------------------------ acciones

    /** Información del servidor para validar el destino. */
    private function action_ping()
    {
        $db       = db_connect();
        $writable = [];
        foreach (array_merge(core_deploy_rules::$ALLOWED_DIRS, ['writable/']) as $dir) {
            $writable[rtrim($dir, '/')] = is_dir(ROOTPATH . $dir) && is_writable(ROOTPATH . $dir);
        }
        $writable['app/Config/Routes.php'] = is_writable(ROOTPATH . 'app/Config/Routes.php');
        $lock  = core_deploy_files::readJson($this->agentDir . '/lock.json');
        $state = core_deploy_files::readJson($this->agentDir . '/state.json') ?: [];

        return [
            'agentVersion'     => core_deploy_rules::VERSION,
            'php'              => PHP_VERSION,
            'extensions'       => ['zip' => class_exists('ZipArchive'), 'mysqli' => extension_loaded('mysqli'), 'openssl' => extension_loaded('openssl'), 'zlib' => function_exists('gzencode')],
            'dbName'           => $this->dbName(),
            'dbVersion'        => $db->query('SELECT VERSION() AS v')->getRow()->v,
            'sqlMode'          => $db->query('SELECT @@SESSION.sql_mode AS m')->getRow()->m,
            'diskFree'         => @disk_free_space(ROOTPATH) ?: null,
            'writable'         => $writable,
            'maxExecutionTime' => (int) ini_get('max_execution_time'),
            'postMaxSize'      => ini_get('post_max_size'),
            'memoryLimit'      => ini_get('memory_limit'),
            'locked'           => $lock && (time() - $lock['time']) <= core_deploy_rules::LOCK_TTL ? $lock['deployID'] : null,
            'lastDeploy'       => isset($state['lastDeploy']) ? $state['lastDeploy'] : null,
            'features'         => ['config', 'logos'],
        ];
    }

    /** Toma el candado del despliegue (uno a la vez por instalación). */
    private function action_begin()
    {
        if ($this->deployID === null) {
            throw new \Exception('Falta deployID.');
        }
        core_deploy_files::ensureDir($this->agentDir);
        $lockFile = $this->agentDir . '/lock.json';
        $handle   = fopen($this->agentDir . '/lock.guard', 'c');
        if (!$handle || !flock($handle, LOCK_EX)) {
            throw new \Exception('No se pudo obtener el candado del servidor.');
        }
        try {
            $lock = core_deploy_files::readJson($lockFile);
            if ($lock && $lock['deployID'] !== $this->deployID && (time() - $lock['time']) <= core_deploy_rules::LOCK_TTL) {
                throw new \Exception('Ya hay otro despliegue en curso (' . $lock['deployID'] . ').');
            }
            core_deploy_files::writeJson($lockFile, ['deployID' => $this->deployID, 'time' => time(), 'since' => helper_getDateTime()]);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
        $this->deployDir();
        core_deploy_files::pruneDirs($this->agentDir . '/deploys', core_deploy_rules::KEEP_DEPLOYS, $this->deployID);
        return ['locked' => $this->deployID];
    }

    /** Libera el candado y elimina archivos temporales grandes. */
    private function action_finish()
    {
        @unlink($this->agentDir . '/lock.json');
        core_deploy_files::removeDir($this->deployDir() . '/incoming');
        core_deploy_files::removeDir($this->deployDir() . '/staging');
        return ['released' => true];
    }

    /** Tipo de carga permitido. */
    private function kind()
    {
        $kind = isset($this->payload['kind']) ? $this->payload['kind'] : '';
        if (!in_array($kind, ['package', 'data'], true)) {
            throw new \Exception('Tipo de carga inválido.');
        }
        return $kind;
    }

    /** Guarda un bloque verificando su hash. */
    private function action_upload_chunk()
    {
        $kind    = $this->kind();
        $index   = (int) $this->payload['index'];
        $total   = (int) $this->payload['total'];
        $content = base64_decode((string) $this->payload['content'], true);
        if ($content === false || $index < 0 || $total < 1 || $index >= $total || $total > ceil(core_deploy_rules::MAX_UPLOAD_BYTES / core_deploy_rules::CHUNK_SIZE)) {
            throw new \Exception('Bloque inválido.');
        }
        if (!hash_equals((string) $this->payload['sha256'], hash('sha256', $content))) {
            throw new \Exception('El bloque ' . $index . ' llegó dañado (hash distinto).');
        }
        file_put_contents($this->deployDir('incoming') . '/' . $kind . '.' . $index . '.part', $content, LOCK_EX);
        return ['index' => $index];
    }

    /** Une los bloques y verifica el SHA-256 total. */
    private function action_upload_commit()
    {
        $kind  = $this->kind();
        $total = (int) $this->payload['total'];
        $dir   = $this->deployDir('incoming');
        $file  = $dir . '/' . $kind . '.bin';
        $out   = fopen($file, 'wb');
        for ($i = 0; $i < $total; $i++) {
            $part = $dir . '/' . $kind . '.' . $i . '.part';
            if (!is_file($part)) {
                fclose($out);
                throw new \Exception('Falta el bloque ' . $i . '.');
            }
            fwrite($out, file_get_contents($part));
        }
        fclose($out);
        for ($i = 0; $i < $total; $i++) {
            @unlink($dir . '/' . $kind . '.' . $i . '.part');
        }
        if (filesize($file) !== (int) $this->payload['size'] || !hash_equals((string) $this->payload['sha256'], hash_file('sha256', $file))) {
            @unlink($file);
            throw new \Exception('El archivo recibido no coincide con el original (hash o tamaño).');
        }
        return ['sha256' => hash_file('sha256', $file), 'size' => filesize($file)];
    }

    /** Extrae y verifica el paquete en staging. */
    private function action_extract()
    {
        $zip    = $this->deployDir('incoming') . '/package.bin';
        $result = core_deploy_package::extract($zip, $this->deployDir() . '/staging');
        core_deploy_files::writeJson($this->deployDir() . '/manifest.json', $result['manifest']);
        if (count($result['errors'])) {
            throw new \Exception('El paquete no pasó la verificación del servidor: ' . implode(' | ', array_slice($result['errors'], 0, 10)));
        }
        return ['extracted' => $result['extracted'], 'files' => count($result['manifest']['files'])];
    }

    /** Respalda y copia los archivos autorizados. */
    private function action_apply_files()
    {
        $manifest = core_deploy_files::readJson($this->deployDir() . '/manifest.json');
        $staging  = $this->deployDir() . '/staging';
        if (!$manifest || !is_dir($staging)) {
            throw new \Exception('Primero debe extraerse el paquete.');
        }
        $backupDir = $this->deployDir('backup_files');
        $results   = ['updated' => [], 'created' => [], 'unchanged' => 0, 'errors' => []];

        foreach ($manifest['files'] as $path => $info) {
            if (core_deploy_rules::classify($path)['category'] !== 'update') {
                $results['errors'][] = ['path' => $path, 'error' => 'No autorizado por el servidor'];
                continue;
            }
            $source = $staging . '/' . $path;
            $target = ROOTPATH . $path;
            try {
                if (is_file($target)) {
                    if (hash_file('sha256', $target) === $info['sha256']) {
                        $results['unchanged']++;
                        continue;
                    }
                    $backup = $backupDir . '/' . $path;
                    if (!is_dir(dirname($backup))) {
                        mkdir(dirname($backup), 0755, true);
                    }
                    if (!copy($target, $backup)) {
                        throw new \Exception('No se pudo respaldar');
                    }
                    core_deploy_files::writeAtomic($target, file_get_contents($source));
                    $results['updated'][] = $path;
                } else {
                    core_deploy_files::writeAtomic($target, file_get_contents($source));
                    $results['created'][] = $path;
                }
            } catch (\Throwable $ex) {
                $results['errors'][] = ['path' => $path, 'error' => $ex->getMessage()];
            }
        }

        core_deploy_files::writeJson($this->deployDir() . '/files_result.json', $results);
        $state               = core_deploy_files::readJson($this->agentDir . '/state.json') ?: [];
        $state['lastDeploy'] = ['deployID' => $this->deployID, 'on' => helper_getDateTime(), 'meta' => isset($manifest['meta']) ? $manifest['meta'] : [], 'files' => count($manifest['files']), 'errors' => count($results['errors'])];
        core_deploy_files::writeJson($this->agentDir . '/state.json', $state);

        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
        return [
            'updated'   => count($results['updated']),
            'created'   => count($results['created']),
            'unchanged' => $results['unchanged'],
            'errors'    => $results['errors'],
            'list'      => ['updated' => array_slice($results['updated'], 0, 500), 'created' => array_slice($results['created'], 0, 500)],
        ];
    }

    /** Revierte los archivos: restaura los respaldados y elimina los creados. */
    private function action_rollback_files()
    {
        $results = core_deploy_files::readJson($this->deployDir() . '/files_result.json');
        if (!$results) {
            throw new \Exception('Este despliegue no tiene archivos aplicados.');
        }
        $backupDir = $this->deployDir() . '/backup_files';
        $restored  = 0;
        $removed   = 0;
        $errors    = [];
        foreach ($results['updated'] as $path) {
            if (core_deploy_package::safeRelativePath($path) !== $path || core_deploy_rules::classify($path)['category'] !== 'update') {
                continue;
            }
            try {
                core_deploy_files::writeAtomic(ROOTPATH . $path, file_get_contents($backupDir . '/' . $path));
                $restored++;
            } catch (\Throwable $ex) {
                $errors[] = $path . ': ' . $ex->getMessage();
            }
        }
        foreach ($results['created'] as $path) {
            if (core_deploy_package::safeRelativePath($path) === $path && core_deploy_rules::classify($path)['category'] === 'update' && @unlink(ROOTPATH . $path)) {
                $removed++;
            }
        }
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
        return ['restored' => $restored, 'removed' => $removed, 'errors' => $errors];
    }

    /** Verifica los archivos instalados contra el manifiesto. */
    private function action_verify_files()
    {
        $manifest = core_deploy_files::readJson($this->deployDir() . '/manifest.json');
        if (!$manifest) {
            throw new \Exception('No hay manifiesto para este despliegue.');
        }
        $mismatch = [];
        foreach ($manifest['files'] as $path => $info) {
            $target = ROOTPATH . $path;
            if (!is_file($target) || hash_file('sha256', $target) !== $info['sha256']) {
                $mismatch[] = $path;
            }
        }
        return ['checked' => count($manifest['files']), 'mismatch' => array_slice($mismatch, 0, 200), 'mismatchCount' => count($mismatch)];
    }

    /** Fotografía de la estructura de la BD local del servidor. */
    private function action_schema_snapshot()
    {
        return core_deploy_schema::snapshot(db_connect());
    }

    /** Ejecuta DDL aprobado; la primera vez respalda la estructura. */
    private function action_exec_sql()
    {
        $statements = isset($this->payload['statements']) && is_array($this->payload['statements']) ? $this->payload['statements'] : [];
        $db         = db_connect();
        $backup     = $this->deployDir() . '/schema_before.json.gz';
        if (!is_file($backup)) {
            core_deploy_files::writeJson($backup, core_deploy_schema::snapshot($db), true);
        }
        foreach ($statements as $item) {
            if (!isset($item['sql']) || !is_string($item['sql'])) {
                throw new \Exception('Sentencia inválida.');
            }
        }
        return core_deploy_schema::run($db, $statements);
    }

    /** Hashes por fila de las tablas pedidas. */
    private function action_data_inspect()
    {
        $tables = isset($this->payload['tables']) && is_array($this->payload['tables']) ? $this->payload['tables'] : [];
        return core_deploy_data::inspect(db_connect(), $tables);
    }

    /** Aplica el paquete de datos (subido como "data") con respaldo previo. */
    private function action_data_apply()
    {
        $payload = core_deploy_files::readJson($this->deployDir('incoming') . '/data.bin');
        if (!$payload || !isset($payload['tables'], $payload['scripts'])) {
            throw new \Exception('No se recibió el paquete de datos.');
        }
        if (isset($payload['dbName']) && $payload['dbName'] !== $this->dbName()) {
            throw new \Exception('El paquete de datos es para otra base de datos.');
        }
        foreach (array_keys($payload['tables']) as $table) {
            if (!core_deploy_rules::isSyncableTable($table)) {
                throw new \Exception('Tabla no permitida: ' . $table);
            }
        }
        $db   = db_connect();
        $rows = core_deploy_data::backup($db, array_keys($payload['tables']), $this->deployDir() . '/backup_data.json.gz');
        $result = core_deploy_data::apply($db, $payload['tables'], $payload['scripts']);
        $result['backupRows'] = $rows;
        return $result;
    }

    /** Restaura el respaldo de datos de este despliegue. */
    private function action_data_restore()
    {
        return ['restored' => core_deploy_data::restore(db_connect(), $this->deployDir() . '/backup_data.json.gz')];
    }

    /** Últimas líneas del log del despliegue. */
    private function action_log()
    {
        $file = $this->deployDir() . '/log.txt';
        $text = is_file($file) ? file_get_contents($file) : '';
        return ['log' => substr($text, -20000)];
    }

    /**
     * Limpieza: instalaciones posMe de la carpeta padre y lo que ocuparía cada categoría.
     * Payload: categories (lista).
     *
     * @return array ['parent', 'self', 'installations' => [['name', 'self', 'locked', 'stats']]]
     */
    private function action_cleanup_scan()
    {
        $categories = core_deploy_cleanup::categories(isset($this->payload['categories']) ? $this->payload['categories'] : []);
        $deadline   = microtime(true) + core_deploy_cleanup::TIME_BUDGET;
        $root       = realpath(ROOTPATH);
        $result     = ['parent' => basename(dirname($root)), 'self' => basename($root), 'installations' => []];
        foreach (core_deploy_cleanup::installations(ROOTPATH) as $item) {
            $result['installations'][] = [
                'name'   => $item['name'],
                'self'   => $item['self'],
                'locked' => core_deploy_cleanup::isLocked($item['path']),
                'stats'  => core_deploy_cleanup::scan($item['path'], $categories, $deadline),
            ];
        }
        return $result;
    }

    /**
     * Limpieza: borra el contenido de las categorías en una instalación vecina (o la propia).
     * Payload: installation (nombre de carpeta), categories (lista).
     * Si se agota el tiempo devuelve complete = false y la consola vuelve a llamar.
     *
     * @return array ['installation', 'complete', 'categories']
     */
    private function action_cleanup_apply()
    {
        $categories = core_deploy_cleanup::categories(isset($this->payload['categories']) ? $this->payload['categories'] : []);
        if (!$categories) {
            throw new \Exception('No se indicaron categorías de limpieza.');
        }
        $item = core_deploy_cleanup::findInstallation(ROOTPATH, isset($this->payload['installation']) ? $this->payload['installation'] : '');
        if (core_deploy_cleanup::isLocked($item['path'])) {
            throw new \Exception('La instalación ' . $item['name'] . ' tiene un despliegue en curso; no se limpia.');
        }
        $result = core_deploy_cleanup::apply($item['path'], $categories, microtime(true) + core_deploy_cleanup::TIME_BUDGET);
        $result['installation'] = $item['name'];
        $this->log_line('cleanup_apply', $item['name'] . ' ' . json_encode($result['categories']));
        return $result;
    }

    // ------------------------------------------------------- configuración y logos

    /** Rechaza la operación si hay un despliegue en curso (candado vigente). */
    private function assertNoDeployLock()
    {
        $lock = core_deploy_files::readJson($this->agentDir . '/lock.json');
        if ($lock && isset($lock['time']) && (time() - $lock['time']) <= core_deploy_rules::LOCK_TTL) {
            throw new \Exception('Hay un despliegue en curso en este servidor (' . (isset($lock['deployID']) ? $lock['deployID'] : '') . '); intente cuando termine.');
        }
    }

    /** Carpeta real app/Config de la instalación. */
    private function configDir()
    {
        $dir = realpath(ROOTPATH . core_deploy_rules::CONFIG_DIR);
        if ($dir === false || !is_dir($dir)) {
            throw new \Exception('No se encontró la carpeta app/Config.');
        }
        return $dir;
    }

    /**
     * Ruta segura de un archivo de app/Config: nombre válido, existente, sin enlaces
     * simbólicos y resuelto dentro de la propia carpeta.
     *
     * @param string $name Nombre del archivo.
     * @return string Ruta absoluta.
     */
    private function configPath($name)
    {
        if (!core_deploy_rules::isConfigName($name)) {
            throw new \Exception('Nombre de archivo de configuración inválido.');
        }
        $dir  = $this->configDir();
        $path = $dir . DIRECTORY_SEPARATOR . $name;
        if (!is_file($path) || is_link($path)) {
            throw new \Exception('No existe el archivo app/Config/' . $name . '.');
        }
        $real = realpath($path);
        if ($real === false || !core_deploy_company_files::samePath(dirname($real), $dir)) {
            throw new \Exception('Ruta no permitida: app/Config/' . $name . '.');
        }
        return $real;
    }

    /**
     * Crea una carpeta de respaldo nueva y conserva solo las más recientes.
     *
     * @return array ['id', 'dir']
     */
    private function newFilesBackup()
    {
        $parent = core_deploy_files::ensureDir($this->agentDir . '/' . core_deploy_rules::FILES_BACKUP_DIR);
        $id     = core_deploy_company_files::newBackupID();
        $dir    = core_deploy_files::ensureDir($parent . '/' . $id);
        core_deploy_files::pruneDirs($parent, core_deploy_rules::FILES_KEEP_BACKUPS, $id);
        return ['id' => $id, 'dir' => $dir];
    }

    /** Copia un archivo existente a la carpeta de respaldo conservando su ruta relativa. */
    private function backupFile($source, $backupDir, $relative)
    {
        $target = $backupDir . '/' . $relative;
        if (!is_dir(dirname($target)) && !@mkdir(dirname($target), 0755, true) && !is_dir(dirname($target))) {
            throw new \Exception('No se pudo crear la carpeta de respaldo.');
        }
        if (!@copy($source, $target)) {
            throw new \Exception('No se pudo respaldar ' . $relative . '.');
        }
    }

    /** Invalida la caché de opcode de un archivo recién escrito. */
    private function invalidateOpcache($path)
    {
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($path, true);
        }
    }

    /**
     * Lista los .php de primer nivel de app/Config (Boot/ y subcarpetas excluidas).
     *
     * @return array ['critical' => [nombres], 'files' => [['name','size','mtime','sha256','readonly','critical']]]
     */
    private function action_config_list()
    {
        $this->assertNoDeployLock();
        $dir   = $this->configDir();
        $files = [];
        foreach (scandir($dir) ?: [] as $name) {
            $path = $dir . DIRECTORY_SEPARATOR . $name;
            if (!core_deploy_rules::isConfigName($name) || !is_file($path) || is_link($path)) {
                continue;
            }
            $real = realpath($path);
            if ($real === false || !core_deploy_company_files::samePath(dirname($real), $dir)) {
                continue;
            }
            $files[] = [
                'name'     => $name,
                'size'     => filesize($real),
                'mtime'    => date('Y-m-d H:i:s', filemtime($real)),
                'sha256'   => hash_file('sha256', $real),
                'readonly' => core_deploy_rules::isConfigReadonly($name),
                'critical' => core_deploy_rules::isConfigCritical($name),
            ];
        }
        usort($files, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        return ['critical' => core_deploy_rules::$CONFIG_CRITICAL, 'files' => $files];
    }

    /**
     * Contenido de un archivo de app/Config (en base64: puede no ser UTF-8).
     * Payload: name.
     *
     * @return array ['name', 'content64', 'sha256', 'size', 'mtime', 'readonly']
     */
    private function action_config_read()
    {
        $this->assertNoDeployLock();
        $name = isset($this->payload['name']) ? (string) $this->payload['name'] : '';
        $path = $this->configPath($name);
        if (filesize($path) > core_deploy_rules::CONFIG_MAX_BYTES) {
            throw new \Exception('El archivo supera 512 KB; no se edita desde la consola.');
        }
        $content = (string) file_get_contents($path);
        return [
            'name'      => $name,
            'content64' => base64_encode($content),
            'sha256'    => hash('sha256', $content),
            'size'      => strlen($content),
            'mtime'     => date('Y-m-d H:i:s', filemtime($path)),
            'readonly'  => core_deploy_rules::isConfigReadonly($name),
        ];
    }

    /**
     * Reemplaza un archivo de app/Config: revisa sintaxis, exige el SHA-256 que la consola
     * leyó (nadie lo cambió entretanto), respalda y escribe de forma atómica.
     * Payload: name, content64, expectedSha.
     *
     * @return array ['name', 'sha256', 'previousSha', 'backupID', 'backup']
     */
    private function action_config_write()
    {
        $this->assertNoDeployLock();
        $name = isset($this->payload['name']) ? (string) $this->payload['name'] : '';
        if (core_deploy_rules::isConfigReadonly($name)) {
            throw new \Exception('app/Config/' . $name . ' es de solo lectura en la consola.');
        }
        $path    = $this->configPath($name);
        $content = base64_decode(isset($this->payload['content64']) ? (string) $this->payload['content64'] : '', true);
        if ($content === false || $content === '') {
            throw new \Exception('Contenido inválido.');
        }
        if (strlen($content) > core_deploy_rules::CONFIG_MAX_BYTES) {
            throw new \Exception('El contenido supera 512 KB.');
        }
        $lint = core_deploy_package::lintPhp($content);
        if ($lint !== null) {
            // Solo la línea: el detalle del parser puede citar valores del archivo y este mensaje se registra en el log
            $line = preg_match('/línea\s+(\d+)/u', $lint, $m) ? $m[1] : '?';
            throw new \Exception('Error de sintaxis: línea ' . $line . ' (revisado en el servidor).');
        }
        $previous = (string) file_get_contents($path);
        $expected = isset($this->payload['expectedSha']) ? (string) $this->payload['expectedSha'] : '';
        if (!hash_equals(hash('sha256', $previous), $expected)) {
            throw new \Exception('El archivo cambió en el servidor; vuelva a abrirlo.');
        }

        $relative = core_deploy_rules::CONFIG_DIR . $name;
        $backup   = $this->newFilesBackup();
        $this->backupFile($path, $backup['dir'], $relative);
        core_deploy_files::writeAtomic($path, $content);
        $this->invalidateOpcache($path);

        return [
            'name'        => $name,
            'sha256'      => hash('sha256', $content),
            'previousSha' => hash('sha256', $previous),
            'backupID'    => $backup['id'],
            'backup'      => 'writable/' . core_deploy_rules::AGENT_DIR . '/' . core_deploy_rules::FILES_BACKUP_DIR . '/' . $backup['id'] . '/' . $relative,
        ];
    }

    /**
     * Restaura un archivo de app/Config desde un respaldo de config_write.
     * Payload: name, backupID.
     *
     * @return array ['name', 'sha256']
     */
    private function action_config_restore()
    {
        $this->assertNoDeployLock();
        $name     = isset($this->payload['name']) ? (string) $this->payload['name'] : '';
        $backupID = isset($this->payload['backupID']) ? (string) $this->payload['backupID'] : '';
        if (!core_deploy_company_files::isBackupID($backupID)) {
            throw new \Exception('Identificador de respaldo inválido.');
        }
        $path   = $this->configPath($name);
        $source = $this->agentDir . '/' . core_deploy_rules::FILES_BACKUP_DIR . '/' . $backupID . '/' . core_deploy_rules::CONFIG_DIR . $name;
        if (!is_file($source)) {
            throw new \Exception('No existe el respaldo de app/Config/' . $name . '.');
        }
        $content = (string) file_get_contents($source);
        core_deploy_files::writeAtomic($path, $content);
        $this->invalidateOpcache($path);
        return ['name' => $name, 'sha256' => hash('sha256', $content)];
    }

    /**
     * Reemplaza un logo de la lista fija en public/resource/img/logos con respaldo previo.
     * Payload: name, content64, sha256.
     *
     * @return array ['name', 'size', 'width', 'height', 'sha256', 'backup', 'pathMismatch']
     */
    private function action_logo_write()
    {
        $this->assertNoDeployLock();
        $name    = isset($this->payload['name']) ? (string) $this->payload['name'] : '';
        $content = base64_decode(isset($this->payload['content64']) ? (string) $this->payload['content64'] : '', true);
        if ($content === false) {
            throw new \Exception('Contenido inválido.');
        }
        if (!hash_equals(isset($this->payload['sha256']) ? (string) $this->payload['sha256'] : '', hash('sha256', $content))) {
            throw new \Exception('La imagen llegó dañada (hash distinto).');
        }
        $size = core_deploy_company_files::checkLogoBinary($content, $name);

        // Sin ensureDir: esa carpeta es pública y no debe recibir el .htaccess de bloqueo
        $dir = FCPATH . core_deploy_rules::LOGO_DIR;
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \Exception('No se pudo crear la carpeta ' . core_deploy_rules::LOGO_DIR . '.');
        }
        $target = $dir . $name;
        if (is_link($target)) {
            throw new \Exception('Ruta no permitida: ' . $name . '.');
        }
        $backupPath = null;
        if (is_file($target)) {
            $backup     = $this->newFilesBackup();
            $relative   = 'public/' . core_deploy_rules::LOGO_DIR . $name;
            $this->backupFile($target, $backup['dir'], $relative);
            $backupPath = 'writable/' . core_deploy_rules::AGENT_DIR . '/' . core_deploy_rules::FILES_BACKUP_DIR . '/' . $backup['id'] . '/' . $relative;
        }
        core_deploy_files::writeAtomic($target, $content);

        // Los tickets leen el logo desde PATH_FILE_OF_APP_ROOT: avisar si no es esta instalación
        $pathMismatch = false;
        if (defined('PATH_FILE_OF_APP_ROOT')) {
            $pathMismatch = !core_deploy_company_files::samePath(realpath(constant('PATH_FILE_OF_APP_ROOT')), realpath(FCPATH . 'resource'));
        }
        return [
            'name'         => $name,
            'size'         => strlen($content),
            'width'        => $size['width'],
            'height'       => $size['height'],
            'sha256'       => hash('sha256', $content),
            'backup'       => $backupPath,
            'pathMismatch' => $pathMismatch,
        ];
    }
}
