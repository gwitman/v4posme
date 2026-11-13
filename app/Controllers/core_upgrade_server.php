<?php
//posme:2026-11-10
namespace App\Controllers;

use App\Libraries\core_web_deploy\core_deploy_data;
use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_rules;
use App\Libraries\core_web_deploy\core_deploy_schema;
use App\Libraries\core_web_upgrade\core_upgrade_auth;
use App\Libraries\core_web_upgrade\core_upgrade_rules;

/**
 * Servidor central de la actualización inversa (lo consume core_upgrade/byPop3).
 *
 * Solo responde si existe WRITEPATH/posme_upgrade_server/server_config.php con
 * 'enabled' => true (ver core_upgrade_auth). Los paquetes se suben a mano a
 * WRITEPATH/posme_upgrade_server/packages/ (syncApp.zip, syncVendor.zip,
 * syncPublic.zip, syncConfig.zip y version.txt: línea 1 versión, línea 2 fecha).
 *
 * Todas las peticiones son POST firmadas (HMAC por instalación, nonce de un uso,
 * HTTPS). Toda respuesta posterior a la autenticación sale firmada con el secreto
 * de la instalación (X-Posme-Response-Timestamp / X-Posme-Response-Signature).
 * Las credenciales de la BD nunca viajan: solo estructura y filas de configuración.
 */
class core_upgrade_server extends _BaseController
{
    private $auth;
    private $client;

    /** Prueba de conexión. */
    function ping()            { return $this->handle('ping'); }
    /** Versión publicada y paquetes disponibles. */
    function manifest()        { return $this->handle('manifest'); }
    /** Bloque de 1 MB de un paquete (base64 + sha256). */
    function package_chunk()   { return $this->handle('package_chunk'); }
    /** Estructura de la BD del servidor. */
    function schema_snapshot() { return $this->handle('schema_snapshot'); }
    /** Filas de las tablas de configuración (perfiles Navicat). */
    function config_data()     { return $this->handle('config_data'); }
    /** Scripts de parámetros: 001_default.sql + el de la compañía registrada. */
    function client_script()   { return $this->handle('client_script'); }

    /**
     * Autentica, ejecuta la acción y firma la respuesta.
     *
     * @param string $action Acción.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    private function handle($action)
    {
        $this->auth = new core_upgrade_auth();
        try {
            $this->client = $this->auth->authenticate($this->request, $action);
        } catch (\Throwable $ex) {
            // Rechazo previo a la autenticación: no hay secreto con qué firmar
            $code = in_array((int) $ex->getCode(), [401, 403, 404, 405, 429], true) ? (int) $ex->getCode() : 400;
            return $this->response->setStatusCode($code)->setJSON(['error' => true, 'message' => $ex->getMessage()]);
        }

        @set_time_limit(core_upgrade_rules::TIME_LIMIT);
        try {
            $method = 'action_' . $action;
            $data   = $this->$method($this->client['payload']);
            $this->log_line($action, 'OK');
            return $this->signed(200, ['error' => false, 'message' => SUCCESS, 'data' => $data], $action);
        } catch (\Throwable $ex) {
            $this->log_line($action, 'ERROR ' . $ex->getMessage());
            return $this->signed(400, ['error' => true, 'message' => $ex->getMessage()], $action);
        }
    }

    /**
     * Respuesta JSON firmada con el secreto de la instalación.
     *
     * @param int    $status HTTP.
     * @param array  $json   Cuerpo.
     * @param string $action Acción firmada.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    private function signed($status, array $json, $action)
    {
        $body = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($body === false) {
            $status = 500;
            $body   = json_encode(['error' => true, 'message' => 'No se pudo codificar la respuesta: ' . json_last_error_msg()]);
        }
        // Descarta salida suelta (espacios tras el cierre PHP en otros archivos): CI la antepone al cuerpo y rompe la firma
        if (ob_get_level() > 0) {
            @ob_clean();
        }
        $timestamp = (string) time();
        $signature = core_upgrade_auth::signResponse($this->client['secret'], $this->client['installationID'], $action, $timestamp, $this->client['nonce'], $body);
        return $this->response
            ->setStatusCode($status)
            ->setContentType('application/json')
            ->setHeader('Cache-Control', 'no-store')
            ->setHeader('X-Posme-Response-Timestamp', $timestamp)
            ->setHeader('X-Posme-Response-Signature', $signature)
            ->setBody($body);
    }

    /** Bitácora diaria del servidor (instalación y acción; nunca secretos). */
    private function log_line($action, $message)
    {
        try {
            $dir = core_deploy_files::ensureDir($this->auth->dir() . '/logs');
            $ip  = (string) $this->request->getIPAddress();
            @file_put_contents($dir . '/' . date('Ymd') . '.log', '[' . helper_getDateTime() . '] ' . $ip . ' ' . $this->client['installationID'] . ' ' . $action . ' ' . substr(str_replace(["\r", "\n"], ' ', $message), 0, 300) . "\n", FILE_APPEND | LOCK_EX);
        } catch (\Throwable $ex) {
            // La bitácora nunca detiene la respuesta
        }
    }

    /** Carpeta de paquetes publicados. */
    private function packagesDir()
    {
        return $this->auth->dir() . '/packages';
    }

    // ------------------------------------------------------------ acciones

    /** Prueba de conexión. */
    private function action_ping(array $payload)
    {
        return [
            'ok'       => true,
            'protocol' => core_upgrade_rules::PROTOCOL,
            'server'   => core_upgrade_rules::VERSION,
            'time'     => helper_getDateTime(),
            'label'    => $this->client['label'],
        ];
    }

    /**
     * Manifiesto: versión publicada, paquetes con sha256 (caché por tamaño y fecha) y tablas.
     */
    private function action_manifest(array $payload)
    {
        $dir    = $this->packagesDir();
        $config = $this->auth->config();
        $cache  = core_deploy_files::readJson($this->auth->dir() . '/cache/manifest.json');
        $cache  = is_array($cache) ? $cache : [];
        $fresh  = [];

        $packages = [];
        foreach (core_upgrade_rules::packageNames() as $name) {
            $path = $dir . '/' . $name;
            if (!is_file($path)) {
                continue;
            }
            clearstatcache(true, $path);
            $size  = (int) filesize($path);
            $mtime = (int) filemtime($path);
            if (isset($cache[$name]) && (int) $cache[$name]['size'] === $size && (int) $cache[$name]['mtime'] === $mtime && preg_match('/^[a-f0-9]{64}$/', (string) $cache[$name]['sha256']) === 1) {
                $sha256 = $cache[$name]['sha256'];
            } else {
                $sha256 = hash_file('sha256', $path);
            }
            $fresh[$name] = ['size' => $size, 'mtime' => $mtime, 'sha256' => $sha256];
            $packages[]   = ['name' => $name, 'size' => $size, 'sha256' => $sha256, 'mtime' => date('Y-m-d H:i:s', $mtime)];
        }
        if ($fresh !== $cache) {
            core_deploy_files::ensureDir($this->auth->dir() . '/cache');
            core_deploy_files::writeJson($this->auth->dir() . '/cache/manifest.json', $fresh);
        }

        $version     = '';
        $publishedOn = '';
        $versionFile = $dir . '/version.txt';
        if (is_file($versionFile)) {
            $lines       = preg_split('/\r\n|\r|\n/', trim((string) file_get_contents($versionFile)));
            $version     = substr(trim(isset($lines[0]) ? $lines[0] : ''), 0, 60);
            $publishedOn = substr(trim(isset($lines[1]) ? $lines[1] : ''), 0, 60);
            if ($publishedOn === '') {
                $publishedOn = date('Y-m-d H:i:s', (int) filemtime($versionFile));
            }
        }

        $tables = [];
        foreach ($this->tableModes() as $table => $mode) {
            $tables[] = ['table' => $table, 'mode' => $mode];
        }

        return [
            'protocol'    => core_upgrade_rules::PROTOCOL,
            'version'     => $version,
            'publishedOn' => $publishedOn,
            'phpMin'      => isset($config['phpMin']) ? (string) $config['phpMin'] : '7.4',
            'packages'    => $packages,
            'tables'      => $tables,
        ];
    }

    /** Bloque de un paquete. */
    private function action_package_chunk(array $payload)
    {
        $name = isset($payload['name']) ? (string) $payload['name'] : '';
        if (!in_array($name, core_upgrade_rules::packageNames(), true)) {
            throw new \Exception('Paquete inválido.');
        }
        $path = $this->packagesDir() . '/' . $name;
        if (!is_file($path)) {
            throw new \Exception('El paquete ' . $name . ' no está publicado.');
        }
        $total  = (int) filesize($path);
        $chunks = max(1, (int) ceil($total / core_upgrade_rules::CHUNK_SIZE));
        $index  = isset($payload['index']) && is_numeric($payload['index']) ? (int) $payload['index'] : -1;
        if ($index < 0 || $index >= $chunks) {
            throw new \Exception('Bloque fuera de rango.');
        }
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new \Exception('No se pudo leer el paquete ' . $name . '.');
        }
        fseek($handle, $index * core_upgrade_rules::CHUNK_SIZE);
        $content = (string) fread($handle, core_upgrade_rules::CHUNK_SIZE);
        fclose($handle);
        return [
            'name'    => $name,
            'index'   => $index,
            'chunks'  => $chunks,
            'total'   => $total,
            'content' => base64_encode($content),
            'sha256'  => hash('sha256', $content),
        ];
    }

    /** Estructura de la BD del servidor. */
    private function action_schema_snapshot(array $payload)
    {
        return core_deploy_schema::snapshot(db_connect());
    }

    /**
     * Tablas de configuración con el modo que aplica el cliente.
     *
     * @return array [tabla => modo]
     */
    private function tableModes()
    {
        $modes = [];
        foreach ($this->core_web_deploy->getProfileTables() as $table => $info) {
            $modes[$table] = $info['mode'];
        }
        // La compañía nunca viaja; parámetros y contadores solo se insertan
        $modes['tb_company']           = core_deploy_rules::MODE_SKIP;
        $modes['tb_company_parameter'] = core_deploy_rules::MODE_INSERT;
        if (isset($modes['tb_counter'])) {
            $modes['tb_counter'] = core_deploy_rules::MODE_INSERT;
        }
        ksort($modes);
        return $modes;
    }

    /** Filas de las tablas de configuración. */
    private function action_config_data(array $payload)
    {
        $db     = db_connect();
        $tables = [];
        foreach ($this->tableModes() as $table => $mode) {
            if (!core_deploy_rules::isSyncableTable($table)) {
                continue;
            }
            if ($mode === core_deploy_rules::MODE_SKIP) {
                $tables[$table] = ['mode' => $mode, 'columns' => [], 'pk' => [], 'rows' => []];
                continue;
            }
            $info = core_deploy_data::tableInfo($db, $table);
            if ($info === null) {
                continue;
            }
            $rows = core_deploy_data::rows($db, $table);
            // Los contadores viajan en cero: cada instalación conserva su numeración
            if (isset(core_deploy_rules::$INSERT_OVERRIDES[$table])) {
                foreach ($rows as $i => $row) {
                    foreach (core_deploy_rules::$INSERT_OVERRIDES[$table] as $column => $value) {
                        if (array_key_exists($column, $row)) {
                            $rows[$i][$column] = $value;
                        }
                    }
                }
            }
            $tables[$table] = ['mode' => $mode, 'columns' => $info['columns'], 'pk' => $info['pk'], 'rows' => $rows];
        }
        $companyIDs = [];
        foreach ($db->query('SELECT companyID FROM tb_company')->getResultArray() as $row) {
            $companyIDs[] = (int) $row['companyID'];
        }
        return ['tables' => $tables, 'companyIDs' => $companyIDs];
    }

    /** Scripts de parámetros de la instalación (el companyKey sale del registro, nunca de la petición). */
    private function action_client_script(array $payload)
    {
        $scripts  = [];
        $warnings = [];
        try {
            $scripts[] = ['name' => core_deploy_rules::SQL_DEFAULT_FILE, 'sql' => $this->core_web_deploy->readScript(core_deploy_rules::SQL_DEFAULT_FILE)];
        } catch (\Throwable $ex) {
            $warnings[] = 'No existe ' . core_deploy_rules::SQL_DEFAULT_FILE . ' en el servidor.';
        }
        $key = preg_replace('/\.sql$/i', '', trim((string) $this->client['companyKey']));
        if ($key !== '') {
            if (preg_match('/^[A-Za-z0-9_\-\.]{1,100}$/', $key) !== 1 || strpos($key, '..') !== false) {
                throw new \Exception('El companyKey registrado para la instalación es inválido.');
            }
            $file = $key . '.sql';
            if ($file !== core_deploy_rules::SQL_DEFAULT_FILE) {
                try {
                    $scripts[] = ['name' => $file, 'sql' => $this->core_web_deploy->readScript($file)];
                } catch (\Throwable $ex) {
                    $warnings[] = 'No existe el script ' . $file . ' en el servidor.';
                }
            }
        } else {
            $warnings[] = 'La instalación no tiene companyKey registrado; solo se envía ' . core_deploy_rules::SQL_DEFAULT_FILE . '.';
        }
        return ['scripts' => $scripts, 'warnings' => $warnings];
    }
}
