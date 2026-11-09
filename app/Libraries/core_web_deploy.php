<?php
//posme:2026-11-09
namespace App\Libraries;

use App\Models\Core\Deploy_Target_Model;
use App\Libraries\core_web_deploy\core_deploy_rules;
use App\Libraries\core_web_deploy\core_deploy_crypto;
use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_agent_client;
use App\Libraries\core_web_deploy\core_deploy_transport;
use App\Libraries\core_web_deploy\core_deploy_schema;
use App\Libraries\core_web_deploy\core_deploy_data;
use App\Libraries\core_web_deploy\core_deploy_sql_splitter;
use App\Libraries\core_web_deploy\core_deploy_cleanup;

/**
 * Orquestador de la consola de despliegue remoto (core_merge::deploy).
 *
 * - Catálogo de compañías a partir de public/resource/file_sql/clientes/*.sql
 *   (cabecera /*BD: nombre_bd:etiqueta*\/ y /*SERVIDOR: ...*\/).
 * - Perfiles de conexión por compañía en tb_deploy_target, con credenciales cifradas.
 * - Estado y log de cada despliegue en WRITEPATH/posme_deploy/deploys/<deployID>/.
 * - Acceso al destino por agente (HTTPS firmado) o por MySQL directo.
 */
class core_web_deploy
{
    private $crypto;
    private $model;

    function __construct()
    {
        $this->crypto = new core_deploy_crypto();
        $this->model  = new Deploy_Target_Model();
    }

    // ------------------------------------------------------------ compañías

    /**
     * Lee la cabecera /*BD:*\/ y /*SERVIDOR:*\/ de un script de cliente.
     *
     * @param string $content Primeros bytes del archivo.
     * @return array ['dbName', 'label', 'server']
     */
    static function parseHeader($content)
    {
        $header = ['dbName' => '', 'label' => '', 'server' => ''];
        if (preg_match('/\/\*\s*BD:\s*([^*]*?)\s*\*\//', $content, $m)) {
            $parts            = explode(':', $m[1], 2);
            $header['dbName'] = trim($parts[0]);
            $header['label']  = isset($parts[1]) ? trim($parts[1]) : '';
        }
        if (preg_match('/\/\*\s*SERVIDOR[^:*]*:\s*([^*]*?)\s*\*\//', $content, $m)) {
            $header['server'] = trim($m[1]);
        }
        return $header;
    }

    /** Carpeta de scripts de cliente. */
    private function clientDir()
    {
        return FCPATH . core_deploy_rules::SQL_CLIENT_DIR;
    }

    /**
     * Lista las compañías disponibles (un .sql por compañía).
     * Excluye 001_default.sql y los *-custom-php.sql (notas, no SQL).
     *
     * @return array Lista de ['key', 'file', 'dbName', 'label', 'server', 'valid']
     */
    function getCompanies()
    {
        $companies = [];
        foreach (glob($this->clientDir() . '*.sql') ?: [] as $file) {
            $name = basename($file);
            if ($name === core_deploy_rules::SQL_DEFAULT_FILE || substr($name, -15) === '-custom-php.sql') {
                continue;
            }
            $handle = fopen($file, 'rb');
            $head   = fread($handle, 2048);
            fclose($handle);
            $header      = self::parseHeader($head);
            $key         = substr($name, 0, -4);
            $companies[] = [
                'key'    => $key,
                'file'   => $name,
                'dbName' => $header['dbName'],
                'label'  => $header['label'],
                'server' => $header['server'],
                'valid'  => $header['dbName'] !== '' && preg_match('/^[A-Za-z0-9_\-]+$/', $key) === 1,
            ];
        }
        usort($companies, function ($a, $b) {
            return strcasecmp($a['key'], $b['key']);
        });
        return $companies;
    }

    /**
     * Obtiene una compañía por su clave validando que exista.
     *
     * @param string $key Nombre del archivo sin .sql.
     * @return array
     */
    function getCompany($key)
    {
        foreach ($this->getCompanies() as $company) {
            if ($company['key'] === $key) {
                if (!$company['valid']) {
                    throw new \Exception('El script de la compañía no tiene una cabecera /*BD: ... */ válida.');
                }
                return $company;
            }
        }
        throw new \Exception('La compañía seleccionada no existe en file_sql/clientes.');
    }

    /**
     * Contenido de un script de parámetros.
     *
     * @param string $file Nombre del archivo.
     * @return string
     */
    function readScript($file)
    {
        $path = $this->clientDir() . basename($file);
        if (!is_file($path)) {
            throw new \Exception('No existe el script: ' . basename($file));
        }
        return (string) file_get_contents($path);
    }

    /**
     * Revisa un script: cantidad de sentencias, escrituras sin WHERE y DDL.
     *
     * @return array
     */
    function analyzeScript($name, $sql)
    {
        $statements = core_deploy_sql_splitter::split($sql);
        $unbounded  = [];
        $ddl        = [];
        foreach ($statements as $statement) {
            if (core_deploy_sql_splitter::isUnboundedWrite($statement['sql'])) {
                $unbounded[] = ['line' => $statement['line'], 'sql' => substr(preg_replace('/\s+/', ' ', $statement['sql']), 0, 160)];
            }
            if (core_deploy_sql_splitter::isDdl($statement['sql'])) {
                $ddl[] = ['line' => $statement['line'], 'sql' => substr(preg_replace('/\s+/', ' ', $statement['sql']), 0, 160)];
            }
        }
        return ['name' => $name, 'statements' => count($statements), 'unbounded' => $unbounded, 'ddl' => $ddl];
    }

    /**
     * Tablas de datos de los perfiles Navicat (fuente de verdad).
     * Perfil 01 → modo completo; perfil 03 (tb_counter) → solo insertar.
     *
     * @return array [tabla => ['mode' => ..., 'profile' => '01'|'03']]
     */
    function getProfileTables()
    {
        $tables = [];
        $base   = FCPATH . core_deploy_rules::NAVICAT_DIR;
        foreach ([core_deploy_rules::NAVICAT_PROFILE_CONFIG => '01', core_deploy_rules::NAVICAT_PROFILE_COUNTER => '03'] as $file => $profile) {
            $json = is_file($base . $file) ? json_decode((string) file_get_contents($base . $file), true) : null;
            if (!is_array($json) || !isset($json['Tables'])) {
                throw new \Exception('No se pudo leer el perfil Navicat: ' . $file);
            }
            foreach ($json['Tables'] as $item) {
                $name = isset($item['Source']['Name']) ? $item['Source']['Name'] : '';
                if (!core_deploy_rules::isSyncableTable($name) || isset($tables[$name])) {
                    continue;
                }
                $mode          = in_array($name, core_deploy_rules::$INSERT_ONLY_TABLES, true) ? core_deploy_rules::MODE_INSERT : core_deploy_rules::MODE_FULL;
                $tables[$name] = ['mode' => $mode, 'profile' => $profile];
            }
        }
        ksort($tables);
        return $tables;
    }

    /**
     * Valida y completa los modos elegidos por el usuario.
     *
     * @param array $requested [tabla => modo]
     * @return array [tabla => modo] solo tablas de los perfiles.
     */
    function resolveTableModes(array $requested)
    {
        $modes = [];
        foreach ($this->getProfileTables() as $table => $info) {
            $mode = isset($requested[$table]) ? $requested[$table] : $info['mode'];
            if (!in_array($mode, core_deploy_rules::dataModes(), true)) {
                throw new \Exception('Modo inválido para ' . $table);
            }
            $modes[$table] = $mode;
        }
        return $modes;
    }

    // ------------------------------------------------------------ perfiles

    /**
     * Perfil de la compañía sin credenciales (para la vista).
     *
     * @return array
     */
    function getProfilePublic($companyID, $companyKey)
    {
        $row = $this->model->get_rowByCompanyFile($companyID, $companyKey);
        if (!$row) {
            return ['exists' => false, 'transportProtocol' => 'sftp', 'dbMode' => 'agent', 'tableModes' => new \stdClass()];
        }
        return [
            'exists'            => true,
            'deployTargetID'    => (int) $row->deployTargetID,
            'agentUrl'          => $row->agentUrl,
            'hasAgentSecret'    => !empty($row->agentSecret),
            'transportProtocol' => $row->transportProtocol ?: 'sftp',
            'ftpHost'           => $row->ftpHost,
            'ftpPort'           => (int) $row->ftpPort,
            'ftpUser'           => $row->ftpUser,
            'hasFtpPassword'    => !empty($row->ftpPassword),
            'ftpRootDir'        => $row->ftpRootDir,
            'ftpAppDir'         => $row->ftpAppDir,
            'dbMode'            => $row->dbMode ?: 'agent',
            'dbHost'            => $row->dbHost,
            'dbPort'            => (int) $row->dbPort,
            'dbName'            => $row->dbName,
            'dbUser'            => $row->dbUser,
            'hasDbPassword'     => !empty($row->dbPassword),
            'tableModes'        => $row->tableModes ? json_decode($row->tableModes, true) : new \stdClass(),
            'lastDeployID'      => $row->lastDeployID,
            'lastDeployOn'      => $row->lastDeployOn,
            'lastDeployStatus'  => $row->lastDeployStatus,
        ];
    }

    /**
     * Guarda el perfil. Los campos de contraseña vacíos conservan el valor anterior.
     *
     * @param int    $companyID  Compañía de la sesión.
     * @param int    $userID     Usuario.
     * @param string $companyKey Archivo de compañía.
     * @param array  $input      Datos del formulario.
     * @return int deployTargetID
     */
    function saveProfile($companyID, $userID, $companyKey, array $input)
    {
        $this->getCompany($companyKey);
        $protocol = isset($input['transportProtocol']) ? strtolower($input['transportProtocol']) : 'sftp';
        if (!in_array($protocol, ['sftp', 'ftps', 'ftp', 'none'], true)) {
            throw new \Exception('Protocolo inválido.');
        }
        $dbMode = isset($input['dbMode']) && $input['dbMode'] === 'direct' ? 'direct' : 'agent';
        $agentUrl = trim(isset($input['agentUrl']) ? $input['agentUrl'] : '');
        if ($agentUrl !== '' && !filter_var($agentUrl, FILTER_VALIDATE_URL)) {
            throw new \Exception('La URL del agente no es válida.');
        }

        $data = [
            'agentUrl'          => rtrim($agentUrl, '/'),
            'transportProtocol' => $protocol,
            'ftpHost'           => trim(isset($input['ftpHost']) ? $input['ftpHost'] : ''),
            'ftpPort'           => (int) (isset($input['ftpPort']) ? $input['ftpPort'] : 0),
            'ftpUser'           => trim(isset($input['ftpUser']) ? $input['ftpUser'] : ''),
            'ftpRootDir'        => trim(isset($input['ftpRootDir']) ? $input['ftpRootDir'] : ''),
            'ftpAppDir'         => trim(isset($input['ftpAppDir']) ? $input['ftpAppDir'] : ''),
            'dbMode'            => $dbMode,
            'dbHost'            => trim(isset($input['dbHost']) ? $input['dbHost'] : ''),
            'dbPort'            => (int) (isset($input['dbPort']) ? $input['dbPort'] : 3306),
            'dbName'            => trim(isset($input['dbName']) ? $input['dbName'] : ''),
            'dbUser'            => trim(isset($input['dbUser']) ? $input['dbUser'] : ''),
            'modifiedOn'        => helper_getDateTime(),
            'modifiedBy'        => $userID,
        ];
        if (isset($input['tableModes']) && is_array($input['tableModes'])) {
            $data['tableModes'] = json_encode($this->resolveTableModes($input['tableModes']));
        }
        foreach (['ftpPassword', 'dbPassword', 'agentSecret'] as $secretField) {
            if (isset($input[$secretField]) && $input[$secretField] !== '') {
                if ($secretField === 'agentSecret' && !preg_match('/^[a-f0-9]{64}$/', $input[$secretField])) {
                    throw new \Exception('El secreto del agente debe tener 64 caracteres hexadecimales.');
                }
                $data[$secretField] = $this->crypto->encrypt($input[$secretField]);
            }
        }

        $row = $this->model->get_rowByCompanyFile($companyID, $companyKey);
        if ($row) {
            $this->model->update_app_posme($companyID, $row->deployTargetID, $data);
            return (int) $row->deployTargetID;
        }
        $data['companyID']   = $companyID;
        $data['companyFile'] = $companyKey;
        $data['createdOn']   = helper_getDateTime();
        $data['createdBy']   = $userID;
        $data['isActive']    = 1;
        return (int) $this->model->insert_app_posme($data);
    }

    /**
     * Genera y guarda un secreto nuevo para el agente.
     *
     * @return string Secreto (se muestra una sola vez).
     */
    function generateAgentSecret($companyID, $userID, $companyKey)
    {
        $secret = core_deploy_crypto::randomSecret();
        $this->saveProfile($companyID, $userID, $companyKey, array_merge($this->getProfilePublicForSave($companyID, $companyKey), ['agentSecret' => $secret]));
        return $secret;
    }

    /** Datos actuales del perfil en el formato de saveProfile (sin secretos). */
    private function getProfilePublicForSave($companyID, $companyKey)
    {
        $profile = $this->getProfilePublic($companyID, $companyKey);
        unset($profile['tableModes']);
        return $profile;
    }

    /**
     * Perfil completo con credenciales descifradas (uso interno, nunca a la vista).
     *
     * @return object
     */
    function getProfileSecret($companyID, $companyKey)
    {
        $row = $this->model->get_rowByCompanyFile($companyID, $companyKey);
        if (!$row) {
            throw new \Exception('Primero guarde la conexión remota de esta compañía.');
        }
        $row->agentSecretPlain = $this->crypto->decrypt($row->agentSecret);
        $row->ftpPasswordPlain = $this->crypto->decrypt($row->ftpPassword);
        $row->dbPasswordPlain  = $this->crypto->decrypt($row->dbPassword);
        return $row;
    }

    /** Valores secretos del perfil (para ocultarlos en logs). */
    function secretsOf($profile)
    {
        return array_filter([$profile->agentSecretPlain, $profile->ftpPasswordPlain, $profile->dbPasswordPlain]);
    }

    /**
     * Marca el resultado del último despliegue en el perfil.
     *
     * @return void
     */
    function markLastDeploy($companyID, $profile, $deployID, $status)
    {
        $this->model->update_app_posme($companyID, $profile->deployTargetID, [
            'lastDeployID'     => $deployID,
            'lastDeployOn'     => helper_getDateTime(),
            'lastDeployStatus' => substr($status, 0, 20),
        ]);
    }

    // ------------------------------------------------------------ conexiones

    /** Cliente del agente. */
    function agent($profile)
    {
        if (empty($profile->agentUrl)) {
            throw new \Exception('Falta la URL del agente.');
        }
        return new core_deploy_agent_client($profile->agentUrl, $profile->agentSecretPlain);
    }

    /** Transporte FTP/SFTP. */
    function transport($profile)
    {
        if (empty($profile->transportProtocol) || $profile->transportProtocol === 'none' || empty($profile->ftpHost)) {
            throw new \Exception('No hay conexión FTP/SFTP configurada.');
        }
        return new core_deploy_transport($profile->transportProtocol, $profile->ftpHost, $profile->ftpPort, $profile->ftpUser, $profile->ftpPasswordPlain);
    }

    /** Ruta remota de la aplicación (raíz + directorio). */
    function remoteAppDir($profile)
    {
        return core_deploy_transport::joinPath($profile->ftpRootDir, $profile->ftpAppDir);
    }

    /**
     * Conexión MySQL directa al destino (modo "direct").
     *
     * @return object Conexión CI4.
     */
    function directDb($profile)
    {
        if (empty($profile->dbHost) || empty($profile->dbName) || empty($profile->dbUser)) {
            throw new \Exception('Faltan datos de la conexión MySQL directa.');
        }
        $default = config('Database')->default;
        $db      = \Config\Database::connect([
            'DSN'      => '',
            'hostname' => $profile->dbHost,
            'username' => $profile->dbUser,
            'password' => (string) $profile->dbPasswordPlain,
            'database' => $profile->dbName,
            'DBDriver' => 'MySQLi',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => true,
            'charset'  => isset($default['charset']) ? $default['charset'] : 'utf8',
            'DBCollat' => isset($default['DBCollat']) ? $default['DBCollat'] : 'utf8_general_ci',
            'port'     => (int) $profile->dbPort ?: 3306,
        ], false);
        $db->initialize();
        return $db;
    }

    /** Indica si las operaciones de BD van por MySQL directo. */
    function isDirect($profile)
    {
        return isset($profile->dbMode) && $profile->dbMode === 'direct';
    }

    // ------------------------------------------------------------ despliegues

    /** Carpeta local de un despliegue. */
    function deployDir($deployID)
    {
        core_deploy_files::assertDeployID($deployID);
        return core_deploy_files::ensureDir(WRITEPATH . core_deploy_rules::CONSOLE_DIR . '/deploys/' . $deployID);
    }

    /** Lee el estado de un despliegue. */
    function readState($deployID)
    {
        $state = core_deploy_files::readJson($this->deployDir($deployID) . '/state.json');
        if (!$state) {
            throw new \Exception('El despliegue no existe o expiró; prepare el paquete de nuevo.');
        }
        return $state;
    }

    /** Guarda el estado de un despliegue. */
    function writeState($deployID, array $state)
    {
        core_deploy_files::writeJson($this->deployDir($deployID) . '/state.json', $state);
    }

    /**
     * Crea un despliegue nuevo para la compañía.
     *
     * @return array Estado inicial.
     */
    function createDeploy($companyKey, $userName)
    {
        $deployID = core_deploy_files::newDeployID();
        $state    = [
            'deployID'   => $deployID,
            'companyKey' => $companyKey,
            'createdOn'  => helper_getDateTime(),
            'createdBy'  => $userName,
            'package'    => null,
            'plan'       => null,
            'data'       => null,
            'steps'      => [],
        ];
        $this->writeState($deployID, $state);
        core_deploy_files::pruneDirs(WRITEPATH . core_deploy_rules::CONSOLE_DIR . '/deploys', 30, $deployID);
        return $state;
    }

    /**
     * Agrega una línea al log local del despliegue (ocultando secretos).
     *
     * @return void
     */
    function log($deployID, $step, $message, array $secrets = [])
    {
        $line = '[' . helper_getDateTime() . '] [' . $step . '] ' . core_deploy_crypto::mask((string) $message, $secrets) . "\n";
        file_put_contents($this->deployDir($deployID) . '/log.txt', $line, FILE_APPEND | LOCK_EX);
    }

    /** Texto completo del log local. */
    function readLog($deployID)
    {
        $file = $this->deployDir($deployID) . '/log.txt';
        return is_file($file) ? (string) file_get_contents($file) : '';
    }

    // ---------------------------------------------------------------- limpieza

    /**
     * Pide al agente la lista de instalaciones de su hosting y lo que ocupa cada categoría.
     *
     * @param object $profile    Perfil con credenciales.
     * @param array  $categories Categorías a medir.
     * @return array ['parent', 'self', 'installations']
     */
    function cleanupScan($profile, array $categories)
    {
        $result = $this->agent($profile)->call('cleanup_scan', ['categories' => core_deploy_cleanup::categories($categories)]);
        if (!isset($result['installations']) || !is_array($result['installations'])) {
            throw new \Exception('El agente no devolvió las instalaciones; actualice el agente en ese servidor.');
        }
        return $result;
    }

    /**
     * Ordena al agente limpiar una instalación (un tramo de tiempo; repetir mientras complete = false).
     *
     * @param object $profile      Perfil con credenciales.
     * @param string $installation Nombre de la carpeta de la instalación.
     * @param array  $categories   Categorías a borrar.
     * @return array ['installation', 'complete', 'categories']
     */
    function cleanupApply($profile, $installation, array $categories)
    {
        $result = $this->agent($profile)->call('cleanup_apply', ['installation' => (string) $installation, 'categories' => core_deploy_cleanup::categories($categories)]);
        if (!isset($result['complete'], $result['categories'])) {
            throw new \Exception('Respuesta de limpieza inválida del agente.');
        }
        return $result;
    }

    /**
     * Agrega una línea al log local de limpiezas (writable/posme_deploy/cleanup.log).
     *
     * @return void
     */
    function cleanupLog($message, array $secrets = [])
    {
        $dir  = core_deploy_files::ensureDir(WRITEPATH . core_deploy_rules::CONSOLE_DIR);
        $line = '[' . helper_getDateTime() . '] ' . core_deploy_crypto::mask((string) $message, $secrets) . "\n";
        file_put_contents($dir . '/cleanup.log', $line, FILE_APPEND | LOCK_EX);
    }

    /**
     * Candado local por compañía (evita dos despliegues simultáneos al mismo cliente).
     *
     * @return void
     */
    function lockCompany($companyKey, $deployID)
    {
        $dir  = core_deploy_files::ensureDir(WRITEPATH . core_deploy_rules::CONSOLE_DIR . '/locks');
        $file = $dir . '/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $companyKey) . '.json';
        $lock = core_deploy_files::readJson($file);
        if ($lock && $lock['deployID'] !== $deployID && (time() - $lock['time']) <= core_deploy_rules::LOCK_TTL) {
            throw new \Exception('Ya hay un despliegue en curso para esta compañía (' . $lock['deployID'] . ').');
        }
        core_deploy_files::writeJson($file, ['deployID' => $deployID, 'time' => time()]);
    }

    /** Libera el candado local. */
    function unlockCompany($companyKey, $deployID)
    {
        $file = WRITEPATH . core_deploy_rules::CONSOLE_DIR . '/locks/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $companyKey) . '.json';
        $lock = core_deploy_files::readJson($file);
        if ($lock && $lock['deployID'] === $deployID) {
            @unlink($file);
        }
    }

    // ------------------------------------------------------------ estructura y datos

    /**
     * Snapshot de la BD destino (agente o directo).
     *
     * @return array
     */
    function remoteSnapshot($profile)
    {
        if ($this->isDirect($profile)) {
            return core_deploy_schema::snapshot($this->directDb($profile));
        }
        return $this->agent($profile)->call('schema_snapshot');
    }

    /**
     * Ejecuta sentencias DDL en el destino.
     *
     * @return array ['ok', 'results']
     */
    function remoteExec($profile, $deployID, array $statements)
    {
        if ($this->isDirect($profile)) {
            $db     = $this->directDb($profile);
            $backup = $this->deployDir($deployID) . '/schema_before.json.gz';
            if (!is_file($backup)) {
                core_deploy_files::writeJson($backup, core_deploy_schema::snapshot($db), true);
            }
            return core_deploy_schema::run($db, $statements);
        }
        return $this->agent($profile)->call('exec_sql', ['deployID' => $deployID, 'statements' => $statements]);
    }

    /**
     * Prepara el paquete de datos: compara filas locales con el destino y guarda
     * los cambios + scripts en data.json.gz del despliegue.
     *
     * @param object $profile    Perfil con credenciales.
     * @param string $deployID   Despliegue.
     * @param array  $company    Compañía (getCompany).
     * @param array  $tableModes [tabla => modo]
     * @param bool   $runScripts Ejecutar 001_default y el script del cliente.
     * @return array Resumen para la vista.
     */
    function prepareData($profile, $deployID, array $company, array $tableModes, $runScripts)
    {
        $local   = db_connect();
        $request = [];
        foreach ($tableModes as $table => $mode) {
            if ($mode === core_deploy_rules::MODE_SKIP) {
                continue;
            }
            $info = core_deploy_data::tableInfo($local, $table);
            if ($info === null) {
                throw new \Exception('La tabla ' . $table . ' no existe en la base local.');
            }
            $request[$table] = $info['columns'];
        }

        $remote  = $this->isDirect($profile)
            ? core_deploy_data::inspect($this->directDb($profile), $request)
            : $this->agent($profile)->call('data_inspect', ['tables' => $request]);

        $tables  = [];
        $summary = [];
        foreach ($request as $table => $columns) {
            $mode = $tableModes[$table];
            if (!isset($remote[$table]) || empty($remote[$table]['exists'])) {
                $summary[$table] = ['mode' => $mode, 'counts' => null, 'warnings' => ['La tabla no existe en el destino (sincronice la estructura primero).']];
                continue;
            }
            $change          = core_deploy_data::changes(core_deploy_data::rows($local, $table), $remote[$table], $mode, $table);
            $summary[$table] = ['mode' => $mode, 'counts' => $change['counts'], 'warnings' => $change['warnings']];
            if ($change['counts']['insert'] + $change['counts']['update'] + $change['counts']['delete'] > 0) {
                unset($change['counts'], $change['warnings']);
                $tables[$table] = $change;
            }
        }

        $scripts  = [];
        $analysis = [];
        if ($runScripts) {
            $default    = $this->readScript(core_deploy_rules::SQL_DEFAULT_FILE);
            $client     = $this->readScript($company['file']);
            $scripts[]  = ['name' => core_deploy_rules::SQL_DEFAULT_FILE, 'sql' => $default];
            $scripts[]  = ['name' => $company['file'], 'sql' => $client];
            $analysis[] = $this->analyzeScript(core_deploy_rules::SQL_DEFAULT_FILE, $default);
            $analysis[] = $this->analyzeScript($company['file'], $client);
        }

        $file = $this->deployDir($deployID) . '/data.json.gz';
        core_deploy_files::writeJson($file, ['dbName' => $company['dbName'], 'tables' => $tables, 'scripts' => $scripts], true);

        return ['tables' => $summary, 'scripts' => $analysis, 'size' => filesize($file), 'sha256' => hash_file('sha256', $file)];
    }

    /**
     * Aplica el paquete de datos ya preparado (modo directo; en modo agente se sube y se llama data_apply).
     *
     * @return array
     */
    function applyDataDirect($profile, $deployID)
    {
        $payload = core_deploy_files::readJson($this->deployDir($deployID) . '/data.json.gz');
        if (!$payload) {
            throw new \Exception('Primero analice los datos.');
        }
        $db     = $this->directDb($profile);
        $rows   = core_deploy_data::backup($db, array_keys($payload['tables']), $this->deployDir($deployID) . '/backup_data.json.gz');
        $result = core_deploy_data::apply($db, $payload['tables'], $payload['scripts']);
        $result['backupRows'] = $rows;
        return $result;
    }

    /** Restaura los datos en modo directo. */
    function restoreDataDirect($profile, $deployID)
    {
        return ['restored' => core_deploy_data::restore($this->directDb($profile), $this->deployDir($deployID) . '/backup_data.json.gz')];
    }

    // ------------------------------------------------------------ instalación del agente

    /**
     * Contenido de agent_config.php para el servidor remoto.
     *
     * @return string
     */
    static function agentConfigContent($secret)
    {
        return "<?php\n// Configuración del agente de despliegue de posMe. NO compartir.\nreturn " . var_export([
            'enabled'   => true,
            'secret'    => $secret,
            'allowHttp' => false,
        ], true) . ";\n";
    }

    /**
     * Instala o actualiza el agente por FTP/SFTP: agent_config.php + protección
     * de la carpeta y, opcionalmente, el código del agente (controlador, librerías y Routes.php).
     *
     * @param object $profile     Perfil con credenciales.
     * @param bool   $includeCode Subir también el código del agente.
     * @return array Archivos subidos.
     */
    function installAgent($profile, $includeCode)
    {
        if (empty($profile->agentSecretPlain)) {
            throw new \Exception('Primero genere el secreto del agente.');
        }
        $appDir    = $this->remoteAppDir($profile);
        $agentDir  = $appDir . '/writable/' . core_deploy_rules::AGENT_DIR;
        $transport = $this->transport($profile);
        $uploaded  = [];
        $transport->connect();
        try {
            if (!$transport->isDir($appDir . '/app') || !$transport->isDir($appDir . '/writable')) {
                throw new \Exception('El directorio remoto no parece una instalación de posMe (faltan app/ o writable/).');
            }
            $transport->putContents("<IfModule authz_core_module>\n    Require all denied\n</IfModule>\n<IfModule !authz_core_module>\n    Deny from all\n</IfModule>\n", $agentDir . '/.htaccess');
            $transport->putContents('', $agentDir . '/index.html');
            $transport->putContents(self::agentConfigContent($profile->agentSecretPlain), $agentDir . '/agent_config.php');
            $uploaded[] = 'writable/' . core_deploy_rules::AGENT_DIR . '/agent_config.php';

            if ($includeCode) {
                $files = ['app/Controllers/core_deploy_agent.php', 'app/Libraries/core_web_deploy.php', 'app/Models/Core/Deploy_Target_Model.php', 'app/Config/Routes.php'];
                foreach (glob(APPPATH . 'Libraries/core_web_deploy/*.php') ?: [] as $file) {
                    $files[] = 'app/Libraries/core_web_deploy/' . basename($file);
                }
                foreach ($files as $relative) {
                    $transport->putFile(ROOTPATH . $relative, $appDir . '/' . $relative);
                    $uploaded[] = $relative;
                }
            }
        } finally {
            $transport->close();
        }
        return $uploaded;
    }
}
