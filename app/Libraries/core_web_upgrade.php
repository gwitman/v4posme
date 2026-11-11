<?php
//posme:2026-11-10
namespace App\Libraries;

use App\Libraries\core_web_deploy\core_deploy_crypto;
use App\Libraries\core_web_deploy\core_deploy_data;
use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_rules;
use App\Libraries\core_web_deploy\core_deploy_schema;
use App\Libraries\core_web_deploy\core_deploy_sql_splitter;
use App\Libraries\core_web_upgrade\core_upgrade_auth;
use App\Libraries\core_web_upgrade\core_upgrade_client;
use App\Libraries\core_web_upgrade\core_upgrade_dbdump;
use App\Libraries\core_web_upgrade\core_upgrade_files;
use App\Libraries\core_web_upgrade\core_upgrade_lock;
use App\Libraries\core_web_upgrade\core_upgrade_package;
use App\Libraries\core_web_upgrade\core_upgrade_rules;

/**
 * Fachada de la actualización inversa (core_upgrade/byPop3).
 *
 * La instalación del cliente se actualiza desde el servidor central
 * (core_upgrade_server) por etapas cortas que la pantalla encadena:
 *   0 begin · 1 connect · 2 verify_local · 3 backup/backup_verify ·
 *   4 download/extract · 5 compare · 6 apply_schema/apply_data/apply_files ·
 *   7 verify · 8 finish · 9 rollback
 *
 * Archivos de trabajo (WRITEPATH/posme_upgrade):
 *   upgrade_config.php      URL de origen, instalación y secreto cifrado (upgrade.key).
 *   state.json              Versión instalada por la última actualización exitosa.
 *   runs/<upgradeID>/       state.json, log.txt, paquetes, staging, planes y respaldos de archivos.
 *   backups/<upgradeID>/    Respaldo completo de la BD (core_upgrade_dbdump).
 *
 * El constructor no hace E/S: se instancia en cada petición desde _BaseController.
 */
class core_web_upgrade
{
    /** Etapa de cada acción de upgrade_step. */
    public static $STAGES = [
        'begin'         => 0,
        'connect'       => 1,
        'verify_local'  => 2,
        'backup'        => 3,
        'backup_verify' => 3,
        'download'      => 4,
        'extract'       => 4,
        'compare'       => 5,
        'apply_schema'  => 6,
        'apply_data'    => 6,
        'apply_files'   => 6,
        'verify'        => 7,
        'finish'        => 8,
        'rollback'      => 9,
    ];

    /**
     * Modos de datos que el cliente fuerza aunque el servidor diga otra cosa:
     * la compañía nunca se toca, los parámetros y contadores solo se insertan.
     */
    public static $FORCED_TABLE_MODES = [
        'tb_company'           => core_deploy_rules::MODE_SKIP,
        'tb_company_parameter' => core_deploy_rules::MODE_INSERT,
        'tb_counter'           => core_deploy_rules::MODE_INSERT,
    ];

    private $stepLog   = [];
    private $secrets   = [];
    private $errorInfo = ['stage' => null, 'action' => '', 'detail' => null];

    /** Constructor liviano (sin E/S). */
    function __construct()
    {
    }

    // ------------------------------------------------------------ rutas de trabajo

    /** Carpeta del cliente. */
    function clientDir()
    {
        return WRITEPATH . core_upgrade_rules::CLIENT_DIR;
    }

    /** Archivo de configuración del cliente. */
    function configFile()
    {
        return $this->clientDir() . '/upgrade_config.php';
    }

    /** Archivo con la versión instalada. */
    private function localStateFile()
    {
        return $this->clientDir() . '/state.json';
    }

    /** Carpeta de corridas. */
    private function runsDir()
    {
        return $this->clientDir() . '/runs';
    }

    /**
     * Carpeta de una corrida (valida el identificador).
     *
     * @param string $upgradeID Identificador.
     * @return string
     */
    private function runDir($upgradeID)
    {
        return $this->runsDir() . '/' . core_deploy_files::assertDeployID($upgradeID);
    }

    /** Nombre corto de un zip para carpetas (syncApp.zip → syncApp). */
    private function zipKey($zip)
    {
        return preg_replace('/[^A-Za-z0-9_]/', '', basename((string) $zip, '.zip'));
    }

    /** Carpeta staging de un zip. */
    private function stagingDir($upgradeID, $zip)
    {
        return $this->runDir($upgradeID) . '/staging/' . $this->zipKey($zip);
    }

    /** Carpeta de respaldo de archivos de un zip. */
    private function filesBackupDir($upgradeID, $zip)
    {
        return $this->runDir($upgradeID) . '/files_backup/' . $this->zipKey($zip);
    }

    // ------------------------------------------------------------ configuración

    /** Cifrador con la llave propia de la actualización. */
    private function crypto()
    {
        return new core_deploy_crypto($this->clientDir() . '/upgrade.key');
    }

    /**
     * Lee upgrade_config.php (sin caché de opcache).
     *
     * @return array
     */
    private function readConfigFile()
    {
        $file = $this->configFile();
        if (!is_file($file)) {
            return [];
        }
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($file, true);
        }
        $config = include $file;
        return is_array($config) ? $config : [];
    }

    /**
     * Secreto en claro de la configuración ('' si no hay o no se puede descifrar).
     *
     * @param array $config Configuración.
     * @return string
     */
    private function secretOf(array $config)
    {
        if (empty($config['secret'])) {
            return '';
        }
        try {
            $plain = (string) $this->crypto()->decrypt((string) $config['secret']);
        } catch (\Throwable $ex) {
            return '';
        }
        if ($plain !== '') {
            $this->secrets[] = $plain;
        }
        return $plain;
    }

    /**
     * Configuración visible (la clave solo enmascarada).
     *
     * @return array ['configured','sourceUrl','installationID','secretMasked']
     */
    function configRead()
    {
        $config = $this->readConfigFile();
        $secret = $this->secretOf($config);
        $url    = isset($config['sourceUrl']) ? (string) $config['sourceUrl'] : '';
        $id     = isset($config['installationID']) ? (string) $config['installationID'] : '';
        return [
            'configured'     => $url !== '' && $id !== '' && strlen($secret) >= 64,
            'sourceUrl'      => $url,
            'installationID' => $id,
            'secretMasked'   => $secret !== '' ? '********' . substr($secret, -4) : '',
        ];
    }

    /**
     * Guarda la conexión. Si la clave llega vacía se conserva la guardada.
     *
     * @param string $sourceUrl      URL del servidor central.
     * @param string $installationID Identificador de la instalación.
     * @param string $secret         Clave nueva o '' para conservar la actual.
     * @return array Configuración visible.
     */
    function configSave($sourceUrl, $installationID, $secret)
    {
        $config = $this->readConfigFile();
        $url    = rtrim(trim((string) $sourceUrl), '/');
        $parts  = parse_url($url);
        if (!$parts || !isset($parts['scheme'], $parts['host']) || !in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            throw new \Exception('La URL del servidor de origen es inválida.');
        }
        $allowHttp = !empty($config['allowHttp']);
        if (strtolower($parts['scheme']) !== 'https' && !$allowHttp) {
            throw new \Exception('La URL del servidor de origen debe usar HTTPS.');
        }
        $installationID = trim((string) $installationID);
        if (preg_match('/^[A-Za-z0-9_\-]{3,64}$/', $installationID) !== 1) {
            throw new \Exception('El ID de instalación debe tener de 3 a 64 letras, números, guion o guion bajo.');
        }

        $secret = trim((string) $secret);
        if ($secret === '') {
            // Clave vacía: se conserva la guardada
            if ($this->secretOf($config) === '') {
                throw new \Exception('Escriba la clave de la instalación.');
            }
            $encrypted = (string) $config['secret'];
        } else {
            if (strlen($secret) < 64 || preg_match('/^[\x21-\x7e]+$/', $secret) !== 1) {
                throw new \Exception('La clave debe tener al menos 64 caracteres, sin espacios.');
            }
            $this->secrets[] = $secret;
            $encrypted       = $this->crypto()->encrypt($secret);
        }

        $new = [
            'sourceUrl'      => $url,
            'installationID' => $installationID,
            'secret'         => $encrypted,
            'allowHttp'      => $allowHttp,
            'allowLoopback'  => !empty($config['allowLoopback']),
            'updatedOn'      => helper_getDateTime(),
        ];
        $refusal = $this->clientRefusal($new);
        if ($refusal !== null) {
            throw new \Exception($refusal);
        }

        $file = $this->configFile();
        core_deploy_files::ensureDir(dirname($file));
        core_deploy_files::writeAtomic($file, "<?php\n//posme:upgrade_config generado por core_upgrade (propio de esta instalación)\nreturn " . var_export($new, true) . ";\n");
        @chmod($file, 0600);
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($file, true);
        }
        return $this->configRead();
    }

    /**
     * Motivo por el que esta instalación no puede actualizarse desde la URL (o null).
     *
     * @param array $config Configuración.
     * @return string|null
     */
    private function clientRefusal(array $config)
    {
        if (core_upgrade_auth::isServerEnabled()) {
            return 'Esta instalación es un servidor de actualizaciones (server_config habilitado); no puede actualizarse a sí misma.';
        }
        $parts = parse_url(isset($config['sourceUrl']) ? (string) $config['sourceUrl'] : '');
        if (!$parts || !isset($parts['host'])) {
            return null;
        }
        $host = strtolower(trim($parts['host'], '[]'));
        if (empty($config['allowLoopback']) && ($host === 'localhost' || $host === '::1' || preg_match('/^127\./', $host) === 1)) {
            return 'La URL de origen apunta a esta misma máquina; use la URL del servidor central.';
        }
        $own     = parse_url(rtrim(base_url(), '/'));
        $ownHost = isset($own['host']) ? strtolower(trim($own['host'], '[]')) : '';
        $ownPath = rtrim(isset($own['path']) ? strtolower($own['path']) : '', '/');
        $srcPath = rtrim(isset($parts['path']) ? strtolower($parts['path']) : '', '/');
        if ($ownHost !== '' && $host === $ownHost && $srcPath === $ownPath) {
            return 'La URL de origen es esta misma instalación.';
        }
        return null;
    }

    /**
     * Cliente firmado hacia el servidor central.
     *
     * @return core_upgrade_client
     */
    private function makeClient()
    {
        $config  = $this->readConfigFile();
        $refusal = $this->clientRefusal($config);
        if ($refusal !== null) {
            throw new \Exception($refusal);
        }
        $secret = $this->secretOf($config);
        if (empty($config['sourceUrl']) || empty($config['installationID']) || $secret === '') {
            throw new \Exception('Configure primero la conexión con el servidor de actualizaciones.');
        }
        return new core_upgrade_client($config['sourceUrl'], $config['installationID'], $secret, !empty($config['allowHttp']));
    }

    /**
     * Oculta los secretos conocidos de un texto.
     *
     * @param string $text Texto.
     * @return string
     */
    function mask($text)
    {
        if (!count($this->secrets)) {
            $this->secretOf($this->readConfigFile());
        }
        return core_deploy_crypto::mask((string) $text, $this->secrets);
    }

    // ------------------------------------------------------------ estado local

    /** Versión instalada por la última actualización exitosa. */
    private function localState()
    {
        $state = core_deploy_files::readJson($this->localStateFile());
        return is_array($state) ? $state : [];
    }

    /**
     * Datos de la instalación local.
     *
     * @param string $host Host de la petición.
     * @return array
     */
    private function localInfo($host)
    {
        $state = $this->localState();
        $row   = db_connect()->query('SELECT DATABASE() AS d, VERSION() AS v')->getRow();
        return [
            'version'      => isset($state['version']) ? (string) $state['version'] : '',
            'publishedOn'  => isset($state['publishedOn']) ? (string) $state['publishedOn'] : '',
            'upgradedOn'   => isset($state['upgradedOn']) ? (string) $state['upgradedOn'] : '',
            'php'          => PHP_VERSION,
            'dbName'       => $row ? (string) $row->d : '',
            'dbVersion'    => $row ? (string) $row->v : '',
            'modeDetected' => core_upgrade_rules::detectMode($host),
        ];
    }

    /** Tamaño aproximado de la BD (datos + índices) en bytes. */
    private function dbBytes()
    {
        $row = db_connect()->query('SELECT COALESCE(SUM(DATA_LENGTH + INDEX_LENGTH), 0) AS b FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()')->getRow();
        return $row ? (int) $row->b : 0;
    }

    /** Elemento de comprobación. */
    private function checkItem($label, $ok, $detail, $group)
    {
        return ['label' => $label, 'ok' => (bool) $ok, 'detail' => (string) $detail, 'group' => $group];
    }

    /**
     * Comprobaciones de la instalación local.
     *
     * @param string $mode     Modo.
     * @param int    $required Bytes libres requeridos.
     * @return array
     */
    private function localChecks($mode, $required)
    {
        $checks = [];
        foreach (['zip', 'curl', 'openssl', 'mysqli', 'zlib'] as $extension) {
            $checks[] = $this->checkItem('Extensión PHP ' . $extension, extension_loaded($extension), extension_loaded($extension) ? '' : 'No está habilitada', 'local');
        }
        $dirs = [WRITEPATH . core_upgrade_rules::CLIENT_DIR . '/'];
        foreach (core_upgrade_rules::packages($mode) as $package) {
            $dirs[] = ROOTPATH . $package['target'];
        }
        foreach ($dirs as $dir) {
            $ok = false;
            try {
                core_deploy_files::ensureDir($dir);
                $probe = rtrim($dir, '/') . '/.posme_upgrade_probe';
                $ok    = @file_put_contents($probe, 'x') !== false;
                @unlink($probe);
            } catch (\Throwable $ex) {
                $ok = false;
            }
            $checks[] = $this->checkItem('Permiso de escritura en ' . core_deploy_files::shortPath($dir), $ok, $ok ? '' : 'PHP no puede escribir en la carpeta', 'local');
        }
        $free = @disk_free_space(WRITEPATH);
        if ($free === false) {
            $checks[] = $this->checkItem('Espacio libre en disco', true, 'No se pudo medir; se continúa', 'local');
        } else {
            $checks[] = $this->checkItem('Espacio libre en disco', $free >= $required, round($free / 1048576, 2) . ' MB libres, se requieren ' . round($required / 1048576, 2) . ' MB', 'local');
        }
        try {
            db_connect()->query('SELECT 1');
            $checks[] = $this->checkItem('Conexión a la base de datos', true, '', 'local');
        } catch (\Throwable $ex) {
            $checks[] = $this->checkItem('Conexión a la base de datos', false, 'Sin conexión', 'local');
        }
        return $checks;
    }

    /**
     * Normaliza el manifiesto del servidor para un modo.
     *
     * @param array  $manifest Respuesta de manifest.
     * @param string $mode     Modo.
     * @return array ['version','publishedOn','phpMin','protocol','packages' => [...], 'byName' => [...], 'missing' => [...]]
     */
    private function remoteFromManifest(array $manifest, $mode)
    {
        $byName = [];
        foreach (isset($manifest['packages']) && is_array($manifest['packages']) ? $manifest['packages'] : [] as $item) {
            if (!is_array($item) || !isset($item['name']) || !in_array($item['name'], core_upgrade_rules::packageNames(), true)) {
                continue;
            }
            $byName[$item['name']] = [
                'name'   => (string) $item['name'],
                'size'   => isset($item['size']) ? (int) $item['size'] : 0,
                'sha256' => isset($item['sha256']) ? strtolower((string) $item['sha256']) : '',
                'mtime'  => isset($item['mtime']) ? (string) $item['mtime'] : '',
            ];
        }
        $missing  = [];
        $packages = [];
        foreach (array_keys(core_upgrade_rules::packages($mode)) as $name) {
            if (isset($byName[$name])) {
                $packages[] = $byName[$name];
            } else {
                $missing[] = $name;
            }
        }
        return [
            'version'     => isset($manifest['version']) ? (string) $manifest['version'] : '',
            'publishedOn' => isset($manifest['publishedOn']) ? (string) $manifest['publishedOn'] : '',
            'phpMin'      => isset($manifest['phpMin']) ? (string) $manifest['phpMin'] : '',
            'protocol'    => isset($manifest['protocol']) ? (int) $manifest['protocol'] : 0,
            'packages'    => $packages,
            'byName'      => $byName,
            'missing'     => $missing,
        ];
    }

    // ------------------------------------------------------------ consulta

    /**
     * Consulta previa: versiones, componentes, comprobaciones y si se puede iniciar.
     *
     * @param string      $host Host de la petición.
     * @param string|null $mode Modo pedido por la pantalla (o null = detectado).
     * @return array
     */
    function check($host, $mode = null)
    {
        $local    = $this->localInfo($host);
        $mode     = is_string($mode) && isset(core_upgrade_rules::$MODES[$mode]) ? $mode : $local['modeDetected'];
        $packages = core_upgrade_rules::packages($mode);
        $checks   = [];
        $warnings = [];
        $tables   = [];
        $remote   = ['reachable' => false, 'version' => '', 'publishedOn' => '', 'phpMin' => '', 'packages' => [], 'missing' => array_keys($packages)];

        $config = $this->configRead();
        $checks[] = $this->checkItem('Conexión con el servidor configurada', $config['configured'], $config['configured'] ? $config['sourceUrl'] : 'Falta URL, ID de instalación o clave', 'remote');

        if ($config['configured']) {
            try {
                $client   = $this->makeClient();
                $start    = microtime(true);
                $manifest = $client->call('manifest', ['mode' => $mode]);
                $info     = $this->remoteFromManifest($manifest, $mode);
                $remote   = [
                    'reachable'   => true,
                    'version'     => $info['version'],
                    'publishedOn' => $info['publishedOn'],
                    'phpMin'      => $info['phpMin'],
                    'packages'    => $info['packages'],
                    'missing'     => $info['missing'],
                    'latencyMs'   => (int) round((microtime(true) - $start) * 1000),
                ];
                foreach (isset($manifest['tables']) && is_array($manifest['tables']) ? $manifest['tables'] : [] as $item) {
                    if (is_array($item) && isset($item['table'], $item['mode']) && core_deploy_rules::isSyncableTable($item['table'])) {
                        $tableMode = isset(self::$FORCED_TABLE_MODES[$item['table']]) ? self::$FORCED_TABLE_MODES[$item['table']] : (string) $item['mode'];
                        $tables[]  = ['table' => $item['table'], 'mode' => $tableMode];
                    }
                }
                $checks[] = $this->checkItem('Servidor de actualizaciones disponible', true, 'Versión ' . ($info['version'] ?: 'sin versión') . ' · ' . $remote['latencyMs'] . ' ms', 'remote');
                $checks[] = $this->checkItem('Protocolo compatible', $info['protocol'] === core_upgrade_rules::PROTOCOL, 'Servidor ' . $info['protocol'] . ', local ' . core_upgrade_rules::PROTOCOL, 'remote');
                foreach (array_keys($packages) as $name) {
                    $present  = isset($info['byName'][$name]);
                    $size     = $present ? $info['byName'][$name]['size'] : 0;
                    $okSize   = $present && $size > 0 && $size <= core_upgrade_rules::MAX_ZIP_BYTES;
                    $checks[] = $this->checkItem('Paquete ' . $name . ' en el servidor', $okSize, $present ? round($size / 1048576, 2) . ' MB' . ($okSize ? '' : ' (tamaño no permitido)') : 'No está publicado', 'remote');
                }
                if ($info['phpMin'] !== '') {
                    $okPhp    = version_compare(PHP_VERSION, $info['phpMin'], '>=');
                    $checks[] = $this->checkItem('Versión de PHP', $okPhp, 'Local ' . PHP_VERSION . ', mínimo ' . $info['phpMin'], 'local');
                }
            } catch (\Throwable $ex) {
                $warnings[] = $this->mask($ex->getMessage());
                $checks[]   = $this->checkItem('Servidor de actualizaciones disponible', false, $this->mask($ex->getMessage()), 'remote');
            }
        }

        $required = $this->dbBytes();
        foreach ($remote['packages'] as $package) {
            $required += 4 * (int) $package['size'];
        }
        $checks = array_merge($checks, $this->localChecks($mode, $required));

        $lock = core_upgrade_lock::current();
        if ($lock) {
            $checks[] = $this->checkItem('Sin otro despliegue o actualización en curso', false, 'Candado activo desde ' . (isset($lock['since']) ? $lock['since'] : '-'), 'local');
        }
        $maintenance = core_upgrade_lock::maintenanceState();

        $allOk = true;
        foreach ($checks as $item) {
            $allOk = $allOk && $item['ok'];
        }
        $upToDate = $remote['reachable'] && $remote['version'] !== '' && $remote['version'] === $local['version'];

        $files = [];
        foreach ($packages as $zip => $package) {
            $files[] = ['zip' => $zip, 'target' => $package['target'], 'protected' => $package['protected']];
        }

        return [
            'local'       => $local,
            'remote'      => $remote,
            'mode'        => $mode,
            'upToDate'    => $upToDate,
            'components'  => ['files' => $files, 'schema' => true, 'data' => ['tables' => $tables]],
            'checks'      => $checks,
            'warnings'    => $warnings,
            'maintenance' => ['active' => $maintenance['active'], 'since' => $maintenance['since'], 'by' => $maintenance['by']],
            'canRun'      => $allOk && $remote['reachable'] && !count($remote['missing']) && !$maintenance['active'],
        ];
    }

    /**
     * Estado de la actualización en curso (si hay).
     *
     * @return array ['running','upgradeID','stage','action','mode','startedOn','maintenance']
     */
    function status()
    {
        $lock = core_upgrade_lock::current();
        $run  = null;
        if ($lock && isset($lock['deployID']) && preg_match('/^[0-9]{14}_[a-f0-9]{8}$/', (string) $lock['deployID']) === 1) {
            $run = core_deploy_files::readJson($this->runDir($lock['deployID']) . '/state.json');
        }
        $maintenance = core_upgrade_lock::maintenanceState();
        return [
            'running'     => $run !== null && in_array($run['status'], ['running', 'rolling_back'], true),
            'upgradeID'   => $run ? $run['upgradeID'] : null,
            'stage'       => $run ? (int) $run['stage'] : null,
            'action'      => $run ? (string) $run['action'] : '',
            'mode'        => $run ? (string) $run['mode'] : '',
            'startedOn'   => $run ? (string) $run['startedOn'] : '',
            'maintenance' => ['active' => $maintenance['active'], 'since' => $maintenance['since'], 'by' => $maintenance['by']],
        ];
    }

    /**
     * Bitácora de una corrida (o de la última si no se indica).
     *
     * @param string $upgradeID Identificador o ''.
     * @return array ['upgradeID','lines']
     */
    function logLines($upgradeID = '')
    {
        if ($upgradeID === '' || $upgradeID === null) {
            $ids = [];
            foreach (glob($this->runsDir() . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
                if (preg_match('/^[0-9]{14}_[a-f0-9]{8}$/', basename($dir)) === 1) {
                    $ids[] = basename($dir);
                }
            }
            rsort($ids);
            if (!count($ids)) {
                return ['upgradeID' => null, 'lines' => []];
            }
            $upgradeID = $ids[0];
        }
        $file  = $this->runDir($upgradeID) . '/log.txt';
        $lines = is_file($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        return ['upgradeID' => $upgradeID, 'lines' => array_slice($lines ?: [], -2000)];
    }

    /**
     * Health check de la propia instalación.
     *
     * @return array ['ok','protocol']
     */
    function health()
    {
        db_connect()->query('SELECT 1');
        return ['ok' => true, 'protocol' => core_upgrade_rules::PROTOCOL];
    }

    // ------------------------------------------------------------ bitácora y salida

    /** Agrega una línea a la bitácora de la corrida y a la respuesta. */
    private function log($upgradeID, $text)
    {
        $text            = $this->mask($text);
        $this->stepLog[] = $text;
        if ($upgradeID) {
            $dir = $this->runDir($upgradeID);
            if (is_dir($dir)) {
                @file_put_contents($dir . '/log.txt', '[' . helper_getDateTime() . '] ' . $text . "\n", FILE_APPEND | LOCK_EX);
            }
        }
    }

    /** Respuesta común de upgrade_step. */
    private function out($action, $done, $index, $total, array $detail)
    {
        return [
            'stage'    => self::$STAGES[$action],
            'action'   => $action,
            'done'     => (bool) $done,
            'index'    => (int) $index,
            'total'    => (int) $total,
            'progress' => null,
            'detail'   => $detail,
            'log'      => $this->stepLog,
        ];
    }

    /** Lanza un error de etapa con su detalle. */
    private function fail($message, $detail = null)
    {
        $this->errorInfo['detail'] = $detail;
        throw new \Exception($message);
    }

    /**
     * Datos de error de la última etapa (para data del JSON de error).
     *
     * @return array ['stage','action','done','detail','log']
     */
    function errorData()
    {
        return [
            'stage'  => $this->errorInfo['stage'],
            'action' => $this->errorInfo['action'],
            'done'   => false,
            'detail' => $this->errorInfo['detail'],
            'log'    => $this->stepLog,
        ];
    }

    /** Lee el estado de una corrida. */
    private function loadRun($upgradeID)
    {
        $run = core_deploy_files::readJson($this->runDir($upgradeID) . '/state.json');
        if (!$run) {
            throw new \Exception('No existe la actualización ' . $upgradeID . '.');
        }
        return $run;
    }

    /** Guarda el estado de una corrida. */
    private function saveRun(array $run)
    {
        $run['updatedOn'] = helper_getDateTime();
        core_deploy_files::writeJson($this->runDir($run['upgradeID']) . '/state.json', $run);
    }

    /** Exige una marca de etapa previa. */
    private function requireFlag(array $run, $flag, $message)
    {
        if (empty($run[$flag])) {
            throw new \Exception($message);
        }
    }

    /** Lee un JSON de trabajo de la corrida (obligatorio). */
    private function readRunJson($upgradeID, $name)
    {
        $data = core_deploy_files::readJson($this->runDir($upgradeID) . '/' . $name);
        if (!is_array($data)) {
            throw new \Exception('Falta el archivo de trabajo ' . $name . '; inicie de nuevo.');
        }
        return $data;
    }

    // ------------------------------------------------------------ etapas

    /**
     * Ejecuta una acción de upgrade_step.
     *
     * @param array  $params   upgradeID, action, index, ids, mode, force, confirm, status, includeDb.
     * @param string $userName Usuario de la sesión.
     * @param string $host     Host de la petición.
     * @return array {stage, action, done, index, total, progress, detail, log}
     */
    function step(array $params, $userName, $host)
    {
        $action          = isset($params['action']) ? (string) $params['action'] : '';
        $this->stepLog   = [];
        $this->errorInfo = ['stage' => isset(self::$STAGES[$action]) ? self::$STAGES[$action] : null, 'action' => $action, 'detail' => null];
        if (!isset(self::$STAGES[$action])) {
            throw new \Exception('Acción de actualización inválida.');
        }
        @set_time_limit(core_upgrade_rules::TIME_LIMIT);
        @ignore_user_abort(true);
        $deadline = microtime(true) + core_upgrade_rules::STEP_BUDGET;
        $index    = isset($params['index']) && is_numeric($params['index']) ? max(0, (int) $params['index']) : 0;

        if ($action === 'begin') {
            return $this->stepBegin($params, $userName, $host);
        }
        $upgradeID = core_deploy_files::assertDeployID(isset($params['upgradeID']) ? (string) $params['upgradeID'] : '');
        if ($action === 'finish') {
            return $this->stepFinish($upgradeID, $params);
        }

        $run = $this->loadRun($upgradeID);
        try {
            if ($action === 'rollback') {
                $result = $this->stepRollback($run, $params, $index, $deadline);
                $this->saveRun($run);
                return $result;
            }
            if ($run['status'] !== 'running') {
                throw new \Exception('La actualización ' . $upgradeID . ' ya terminó; inicie una nueva.');
            }
            core_upgrade_lock::refresh($upgradeID);
            core_upgrade_lock::maintenanceRefresh($upgradeID);
            $run['stage']  = self::$STAGES[$action];
            $run['action'] = $action;

            switch ($action) {
                case 'connect':       $result = $this->stepConnect($run); break;
                case 'verify_local':  $result = $this->stepVerifyLocal($run); break;
                case 'backup':        $result = $this->stepBackup($run, $index, $deadline); break;
                case 'backup_verify': $result = $this->stepBackupVerify($run); break;
                case 'download':      $result = $this->stepDownload($run, $index, $deadline); break;
                case 'extract':       $result = $this->stepExtract($run); break;
                case 'compare':       $result = $this->stepCompare($run); break;
                case 'apply_schema':  $result = $this->stepApplySchema($run, $params); break;
                case 'apply_data':    $result = $this->stepApplyData($run); break;
                case 'apply_files':   $result = $this->stepApplyFiles($run, $index); break;
                case 'verify':        $result = $this->stepVerify($run); break;
                default:              throw new \Exception('Acción de actualización inválida.');
            }
            $this->saveRun($run);
            return $result;
        } catch (\Throwable $ex) {
            $message          = $this->mask($ex->getMessage());
            $run['lastError'] = $action . ': ' . $message;
            $this->log($upgradeID, 'ERROR en ' . $action . ': ' . $message);
            try {
                $this->saveRun($run);
            } catch (\Throwable $ignored) {
                // El error original es el que importa
            }
            throw new \Exception($message);
        }
    }

    /** 0. Inicio: confirmación, candado y modo mantenimiento. */
    private function stepBegin(array $params, $userName, $host)
    {
        $confirm = isset($params['confirm']) ? (string) $params['confirm'] : '';
        if (!hash_equals(core_upgrade_rules::CONFIRM_WORD, $confirm)) {
            throw new \Exception('Escriba ' . core_upgrade_rules::CONFIRM_WORD . ' (en mayúsculas, sin espacios) para confirmar.');
        }
        $mode = core_upgrade_rules::assertMode(isset($params['mode']) ? (string) $params['mode'] : '');
        // Valida configuración y rechazos (propio host, servidor habilitado) antes de bloquear
        $this->makeClient();

        $upgradeID = core_deploy_files::newDeployID();
        core_upgrade_lock::acquire($upgradeID);
        try {
            core_upgrade_lock::maintenanceOn($upgradeID, $userName);
            core_deploy_files::ensureDir($this->runDir($upgradeID));
            $run = [
                'upgradeID'     => $upgradeID,
                'mode'          => $mode,
                'force'         => isset($params['force']) && in_array((string) $params['force'], ['1', 'true'], true),
                'by'            => (string) $userName,
                'host'          => (string) $host,
                'startedOn'     => helper_getDateTime(),
                'startedTs'     => microtime(true),
                'status'        => 'running',
                'stage'         => 0,
                'action'        => 'begin',
                'clientVersion' => core_upgrade_rules::VERSION,
                'previousState' => $this->localState(),
            ];
            $this->saveRun($run);
            core_deploy_files::pruneDirs($this->runsDir(), core_upgrade_rules::KEEP_RUNS - 1, $upgradeID);
            $this->log($upgradeID, 'Actualización ' . $upgradeID . ' iniciada por ' . $userName . ' en modo ' . $mode . ($run['force'] ? ' (forzada)' : '') . '.');
        } catch (\Throwable $ex) {
            core_upgrade_lock::maintenanceOff($upgradeID);
            core_upgrade_lock::release($upgradeID);
            throw $ex;
        }
        $out              = $this->out('begin', true, 0, 1, ['upgradeID' => $upgradeID, 'mode' => $mode]);
        $out['upgradeID'] = $upgradeID;
        return $out;
    }

    /** 1. Conexión: ping, manifiesto, paquetes del modo y plan de descarga. */
    private function stepConnect(array &$run)
    {
        $client  = $this->makeClient();
        $start   = microtime(true);
        $ping    = $client->call('ping');
        $latency = (int) round((microtime(true) - $start) * 1000);
        if (!isset($ping['protocol']) || (int) $ping['protocol'] !== core_upgrade_rules::PROTOCOL) {
            throw new \Exception('El servidor usa otro protocolo de actualización (' . (isset($ping['protocol']) ? (int) $ping['protocol'] : 0) . ').');
        }
        $remote = $this->remoteFromManifest($client->call('manifest', ['mode' => $run['mode']]), $run['mode']);
        $detail = ['remoteVersion' => $remote['version'], 'latencyMs' => $latency, 'packages' => $remote['packages'], 'missing' => $remote['missing']];
        if (count($remote['missing'])) {
            $this->fail('Falta ' . implode(', ', $remote['missing']) . ' en el servidor.', $detail);
        }
        foreach ($remote['packages'] as $package) {
            if ($package['size'] <= 0 || $package['size'] > core_upgrade_rules::MAX_ZIP_BYTES || preg_match('/^[a-f0-9]{64}$/', $package['sha256']) !== 1) {
                $this->fail('El paquete ' . $package['name'] . ' del servidor tiene un tamaño o hash no permitido.', $detail);
            }
        }
        if ($remote['phpMin'] !== '' && version_compare(PHP_VERSION, $remote['phpMin'], '<')) {
            $this->fail('La versión ' . $remote['version'] . ' requiere PHP ' . $remote['phpMin'] . ' (local ' . PHP_VERSION . ').', $detail);
        }
        $local = $this->localState();
        if (empty($run['force']) && $remote['version'] !== '' && isset($local['version']) && $remote['version'] === $local['version']) {
            $this->fail('La instalación ya está en la versión ' . $remote['version'] . '. Marque "Forzar" para aplicarla de nuevo.', $detail);
        }

        $packages = [];
        $chunks   = 0;
        foreach ($remote['packages'] as $package) {
            $package['chunks']           = max(1, (int) ceil($package['size'] / core_upgrade_rules::CHUNK_SIZE));
            $packages[$package['name']] = $package;
            $chunks                     += $package['chunks'];
        }
        $run['remote']   = ['version' => $remote['version'], 'publishedOn' => $remote['publishedOn'], 'phpMin' => $remote['phpMin'], 'packages' => $packages];
        $run['download'] = ['unit' => 0, 'total' => $chunks, 'bytes' => 0];
        $this->log($run['upgradeID'], 'Servidor versión ' . ($remote['version'] ?: 'sin versión') . ' · ' . $latency . ' ms · ' . count($packages) . ' paquetes.');
        return $this->out('connect', true, 0, 1, $detail);
    }

    /** 2. Instalación local: extensiones, permisos, disco y BD. */
    private function stepVerifyLocal(array &$run)
    {
        $this->requireFlag($run, 'remote', 'Conéctese primero con el servidor.');
        $required = $this->dbBytes();
        foreach ($run['remote']['packages'] as $package) {
            $required += 4 * (int) $package['size'];
        }
        $checks = $this->localChecks($run['mode'], $required);
        $free   = @disk_free_space(WRITEPATH);
        $detail = ['checks' => $checks, 'diskFreeBytes' => $free === false ? 0 : (int) $free];
        $bad    = [];
        foreach ($checks as $item) {
            if (!$item['ok']) {
                $bad[] = $item['label'] . ($item['detail'] !== '' ? ' (' . $item['detail'] . ')' : '');
            }
        }
        if (count($bad)) {
            $this->fail('Comprobaciones locales con error: ' . implode('; ', $bad), $detail);
        }
        $run['verifiedLocal'] = true;
        $this->log($run['upgradeID'], 'Instalación local verificada (' . count($checks) . ' comprobaciones).');
        return $this->out('verify_local', true, 0, 1, $detail);
    }

    /** 3. Respaldo completo de la BD por bloques (index 0 = inicia de cero). */
    private function stepBackup(array &$run, $index, $deadline)
    {
        $this->requireFlag($run, 'verifiedLocal', 'Verifique primero la instalación local.');
        $db = db_connect();
        if ($index === 0 || empty($run['backupStarted'])) {
            core_upgrade_dbdump::begin($db, $run['upgradeID']);
            $run['backupStarted']  = true;
            $run['backupVerified'] = false;
            $this->log($run['upgradeID'], 'Respaldo de la base de datos iniciado.');
        }
        $manifest = core_upgrade_dbdump::step($db, $run['upgradeID'], $deadline);
        $parts    = 2;
        foreach ($manifest['tables'] as $info) {
            $parts += count($info['parts']);
        }
        $done = !empty($manifest['complete']);
        if ($done) {
            $this->log($run['upgradeID'], 'Respaldo terminado: ' . count($manifest['tables']) . ' tablas, ' . (int) $manifest['rows'] . ' filas.');
        }
        return $this->out('backup', $done, max(0, (int) $manifest['unitsDone'] - 1), (int) $manifest['unitsTotal'], [
            'tables' => min((int) $manifest['cursor']['table'], count($manifest['order'])),
            'rows'   => (int) $manifest['rows'],
            'bytes'  => (int) $manifest['bytes'],
            'part'   => $parts,
        ]);
    }

    /** 3. Verificación del respaldo (hash, lectura gzip y conteo de filas). */
    private function stepBackupVerify(array &$run)
    {
        $this->requireFlag($run, 'backupStarted', 'No hay respaldo de la base de datos.');
        $result = core_upgrade_dbdump::verify(db_connect(), $run['upgradeID']);
        $detail = ['parts' => $result['parts'], 'tables' => $result['tables'], 'rows' => $result['rows'], 'bytes' => $result['bytes'], 'ok' => $result['ok'], 'note' => $result['note']];
        if (!$result['ok']) {
            $this->fail('El respaldo no pasó la verificación: ' . $result['note'], $detail);
        }
        $run['backupVerified'] = true;
        $this->log($run['upgradeID'], 'Respaldo verificado: ' . $result['parts'] . ' partes.' . ($result['note'] !== '' ? ' Nota: ' . $result['note'] : ''));
        return $this->out('backup_verify', true, 0, 1, $detail);
    }

    /** Lista plana de bloques a descargar (índice global sobre todos los zip). */
    private function downloadUnits(array $run)
    {
        $units = [];
        foreach ($run['remote']['packages'] as $name => $package) {
            for ($i = 0; $i < $package['chunks']; $i++) {
                $units[] = ['zip' => $name, 'chunk' => $i, 'chunks' => $package['chunks']];
            }
        }
        return $units;
    }

    /** 4. Descarga por bloques de 1 MB con hash por bloque y por paquete. */
    private function stepDownload(array &$run, $index, $deadline)
    {
        $this->requireFlag($run, 'backupVerified', 'El respaldo de la base de datos no está verificado.');
        $client = $this->makeClient();
        $dir    = $this->runDir($run['upgradeID']) . '/packages';
        $units  = $this->downloadUnits($run);
        if ($index === 0) {
            core_deploy_files::removeDir($dir);
            $run['download'] = ['unit' => 0, 'total' => count($units), 'bytes' => 0];
        }
        core_deploy_files::ensureDir($dir);

        $last = null;
        while ($run['download']['unit'] < count($units)) {
            $unit   = $units[$run['download']['unit']];
            $result = $client->downloadChunk($unit['zip'], $unit['chunk'], $dir . '/' . $unit['zip'] . '.' . $unit['chunk'] . '.part');
            $run['download']['bytes'] += $result['bytes'];
            $run['download']['unit']++;
            $last = $unit;
            if ($unit['chunk'] === $unit['chunks'] - 1) {
                $this->assemble($run, $unit['zip'], $unit['chunks'], $dir);
            }
            $this->saveRun($run);
            if (microtime(true) >= $deadline) {
                break;
            }
        }
        if ($last === null && count($units)) {
            $last = $units[max(0, min(count($units) - 1, $run['download']['unit'] - 1))];
        }
        $totalBytes = 0;
        foreach ($run['remote']['packages'] as $package) {
            $totalBytes += (int) $package['size'];
        }
        $done = $run['download']['unit'] >= count($units);
        return $this->out('download', $done, max(0, $run['download']['unit'] - 1), count($units), [
            'zip'        => $last ? $last['zip'] : '',
            'bytes'      => (int) $run['download']['bytes'],
            'totalBytes' => $totalBytes,
            'chunk'      => $last ? $last['chunk'] + 1 : 0,
            'chunks'     => $last ? $last['chunks'] : 0,
        ]);
    }

    /** Une los bloques de un zip y verifica tamaño y sha256 contra el manifiesto. */
    private function assemble(array $run, $zip, $chunks, $dir)
    {
        $target = $dir . '/' . $zip;
        $temp   = $target . '.tmp';
        $out    = fopen($temp, 'wb');
        if ($out === false) {
            throw new \Exception('No se pudo crear ' . $zip . ' en la carpeta de trabajo.');
        }
        try {
            for ($i = 0; $i < $chunks; $i++) {
                $in = @fopen($dir . '/' . $zip . '.' . $i . '.part', 'rb');
                if ($in === false) {
                    throw new \Exception('Falta el bloque ' . $i . ' de ' . $zip . '.');
                }
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
        } finally {
            fclose($out);
        }
        $info = $run['remote']['packages'][$zip];
        if (filesize($temp) !== (int) $info['size'] || !hash_equals($info['sha256'], hash_file('sha256', $temp))) {
            @unlink($temp);
            throw new \Exception('El paquete ' . $zip . ' descargado no coincide con el hash publicado por el servidor.');
        }
        if (is_file($target)) {
            @unlink($target);
        }
        if (!@rename($temp, $target)) {
            throw new \Exception('No se pudo guardar ' . $zip . '.');
        }
        for ($i = 0; $i < $chunks; $i++) {
            @unlink($dir . '/' . $zip . '.' . $i . '.part');
        }
        $this->log($run['upgradeID'], $zip . ' descargado y verificado (' . round($info['size'] / 1048576, 2) . ' MB).');
    }

    /** Resultado de la extracción de un zip. */
    private function extractResult($upgradeID, $zip)
    {
        return $this->readRunJson($upgradeID, 'extract_' . $this->zipKey($zip) . '.json.gz');
    }

    /** 4. Extracción segura a staging y lint de PHP (falla si hay errores de sintaxis). */
    private function stepExtract(array &$run)
    {
        if (empty($run['download']) || $run['download']['unit'] < $run['download']['total']) {
            throw new \Exception('La descarga no está completa.');
        }
        $zips = [];
        $lint = [];
        foreach (array_keys(core_upgrade_rules::packages($run['mode'])) as $zip) {
            $path = $this->runDir($run['upgradeID']) . '/packages/' . $zip;
            if (!is_file($path) || !hash_equals($run['remote']['packages'][$zip]['sha256'], hash_file('sha256', $path))) {
                throw new \Exception('El paquete ' . $zip . ' no está descargado o cambió.');
            }
            $result = core_upgrade_package::extract($path, $this->stagingDir($run['upgradeID'], $zip), $run['mode'], $zip);
            core_deploy_files::writeJson($this->runDir($run['upgradeID']) . '/extract_' . $this->zipKey($zip) . '.json.gz', $result, true);
            $zips[] = [
                'zip'        => $zip,
                'files'      => count($result['files']),
                'protected'  => array_slice($result['protected'], 0, 500),
                'rejected'   => array_slice($result['rejected'], 0, 500),
                'ignored'    => $result['ignored'],
                'lintErrors' => array_slice($result['lintErrors'], 0, 200),
            ];
            foreach ($result['lintErrors'] as $error) {
                $lint[] = $error['path'] . ' (' . $error['error'] . ')';
            }
            $this->log($run['upgradeID'], $zip . ': ' . count($result['files']) . ' archivos, ' . count($result['protected']) . ' protegidos, ' . count($result['rejected']) . ' rechazados, ' . count($result['lintErrors']) . ' con error PHP.');
        }
        if (count($lint)) {
            $this->fail(count($lint) . ' archivos PHP con errores de sintaxis en los paquetes descargados: ' . implode('; ', array_slice($lint, 0, 3)), ['zips' => $zips]);
        }
        $run['extracted'] = true;
        return $this->out('extract', true, 0, 1, ['zips' => $zips]);
    }

    /**
     * Ajusta un snapshot del servidor para MariaDB (collation 0900 e índices invisibles).
     *
     * @param array $snapshot Snapshot del servidor.
     * @param array $local    Snapshot local.
     * @return array
     */
    private function adaptSnapshot(array $snapshot, array $local)
    {
        if (empty($local['isMaria'])) {
            return $snapshot;
        }
        foreach (['tables', 'views', 'functions', 'procedures', 'triggers'] as $key) {
            foreach (isset($snapshot[$key]) && is_array($snapshot[$key]) ? $snapshot[$key] : [] as $name => $sql) {
                $snapshot[$key][$name] = core_upgrade_rules::adaptSqlForMaria($sql);
            }
        }
        return $snapshot;
    }

    /**
     * Plan de estructura: diff(servidor, local) con los nombres que usa la pantalla.
     *
     * @param array $server Snapshot del servidor.
     * @param array $local  Snapshot local.
     * @return array ['steps','extras','notes','summary','hash']
     */
    private function schemaPlan(array $server, array $local)
    {
        foreach (['tables', 'views', 'functions', 'procedures', 'triggers'] as $key) {
            if (!isset($server[$key]) || !is_array($server[$key])) {
                throw new \Exception('La estructura enviada por el servidor está incompleta (' . $key . ').');
            }
        }
        if (!isset($server['schema'])) {
            $server['schema'] = '';
        }
        $diff  = core_deploy_schema::diff($this->adaptSnapshot($server, $local), $local);
        $steps = [];
        foreach ($diff['steps'] as $step) {
            $steps[] = [
                'id'     => $step['id'],
                'group'  => $step['grupo'],
                'action' => $step['accion'],
                'object' => $step['objeto'],
                'risk'   => $step['riesgo'],
                'reason' => $step['motivo'],
                'before' => $step['antes'],
                'after'  => $step['despues'],
                'sql'    => array_values($step['sql']),
            ];
        }
        return ['steps' => $steps, 'extras' => $diff['extras'], 'notes' => $diff['notes'], 'summary' => $diff['summary'], 'hash' => $diff['hash']];
    }

    /**
     * Plan de datos: filas del servidor contra el estado local (pull invertido).
     *
     * @param object $db     Conexión local.
     * @param array  $config Respuesta de config_data.
     * @return array ['tables' => [t => [mode, insert, update, delete, warnings]], 'changes' => [t => changes()], 'warnings' => [...]]
     */
    private function dataPlan($db, array $config)
    {
        if (!isset($config['tables']) || !is_array($config['tables'])) {
            throw new \Exception('El servidor no envió los datos de configuración.');
        }

        // Nunca se mezclan compañías: el companyID de tb_company debe ser el mismo
        $serverIDs = array_map('intval', isset($config['companyIDs']) && is_array($config['companyIDs']) ? $config['companyIDs'] : []);
        $localIDs  = [];
        foreach ($db->query('SELECT companyID FROM tb_company')->getResultArray() as $row) {
            $localIDs[] = (int) $row['companyID'];
        }
        sort($serverIDs);
        sort($localIDs);
        if ($serverIDs !== $localIDs) {
            throw new \Exception('El companyID de tb_company del servidor (' . implode(',', $serverIDs) . ') es distinto al local (' . implode(',', $localIDs) . '); se aborta para no mezclar compañías.');
        }

        $columns  = [];
        $modes    = [];
        $warnings = [];
        foreach ($config['tables'] as $table => $info) {
            if (!core_deploy_rules::isSyncableTable($table) || !is_array($info)) {
                $warnings[] = 'Tabla rechazada por las reglas locales: ' . $table;
                continue;
            }
            $mode = isset(self::$FORCED_TABLE_MODES[$table]) ? self::$FORCED_TABLE_MODES[$table] : (isset($info['mode']) ? (string) $info['mode'] : '');
            if (!in_array($mode, core_deploy_rules::dataModes(), true)) {
                $warnings[] = 'Modo inválido para ' . $table . '; se omite.';
                continue;
            }
            $modes[$table] = $mode;
            if ($mode !== core_deploy_rules::MODE_SKIP) {
                $columns[$table] = isset($info['columns']) && is_array($info['columns']) ? $info['columns'] : [];
            }
        }

        $inspect = core_deploy_data::inspect($db, $columns);
        $tables  = [];
        $changes = [];
        foreach ($modes as $table => $mode) {
            $rows = isset($config['tables'][$table]['rows']) && is_array($config['tables'][$table]['rows']) ? $config['tables'][$table]['rows'] : [];
            if ($mode === core_deploy_rules::MODE_SKIP) {
                $tables[$table] = ['mode' => $mode, 'insert' => 0, 'update' => 0, 'delete' => 0, 'warnings' => []];
                continue;
            }
            if (empty($inspect[$table]['exists'])) {
                $tables[$table] = ['mode' => $mode, 'insert' => count($rows), 'update' => 0, 'delete' => 0, 'warnings' => ['La tabla aún no existe localmente; se llena después de crear la estructura.']];
                continue;
            }
            $change          = core_deploy_data::changes($rows, $inspect[$table], $mode, $table);
            $changes[$table] = $change;
            $tables[$table]  = ['mode' => $mode, 'insert' => $change['counts']['insert'], 'update' => $change['counts']['update'], 'delete' => $change['counts']['delete'], 'warnings' => $change['warnings']];
        }
        return ['tables' => $tables, 'changes' => $changes, 'warnings' => $warnings];
    }

    /**
     * Valida los scripts de parámetros que envía el servidor.
     *
     * @param array $response Respuesta de client_script.
     * @return array [['name','sql']]
     */
    private function validateScripts(array $response)
    {
        $scripts = [];
        foreach (isset($response['scripts']) && is_array($response['scripts']) ? $response['scripts'] : [] as $script) {
            if (!is_array($script) || !isset($script['name'], $script['sql']) || preg_match('/^[A-Za-z0-9_\-\.]{1,120}\.sql$/', (string) $script['name']) !== 1) {
                throw new \Exception('El servidor envió un script de parámetros inválido.');
            }
            foreach (core_deploy_sql_splitter::split((string) $script['sql']) as $statement) {
                if (core_deploy_sql_splitter::isDdl($statement['sql'])) {
                    throw new \Exception('El script ' . $script['name'] . ' contiene DDL en la línea ' . $statement['line'] . '; no se puede ejecutar en la transacción de datos.');
                }
            }
            $scripts[] = ['name' => (string) $script['name'], 'sql' => (string) $script['sql']];
        }
        return $scripts;
    }

    /** 5. Comparación: archivos, estructura, datos y scripts (no modifica nada). */
    private function stepCompare(array &$run)
    {
        $this->requireFlag($run, 'extracted', 'Los paquetes no están extraídos.');
        $client = $this->makeClient();
        $db     = db_connect();
        $dir    = $this->runDir($run['upgradeID']);

        // Archivos
        $files = [];
        foreach (array_keys(core_upgrade_rules::packages($run['mode'])) as $zip) {
            $extract     = $this->extractResult($run['upgradeID'], $zip);
            $compare     = core_upgrade_files::compare($extract['files']);
            $files[$zip] = ['update' => $compare['update'], 'create' => $compare['create'], 'unchanged' => $compare['unchanged'], 'protected' => $extract['protected']];
        }

        // Estructura
        $server = $client->call('schema_snapshot');
        $local  = core_deploy_schema::snapshot($db);
        if (count($local['errors'])) {
            throw new \Exception('No se pudo leer toda la estructura local: ' . implode(' | ', array_slice($local['errors'], 0, 5)));
        }
        $plan = $this->schemaPlan($server, $local);
        if (!empty($server['errors'])) {
            $plan['notes'][] = 'El servidor no pudo leer parte de su estructura: ' . implode(' | ', array_slice((array) $server['errors'], 0, 5));
        }
        core_deploy_files::writeJson($dir . '/server_snapshot.json.gz', $server, true);
        core_deploy_files::writeJson($dir . '/local_snapshot.json.gz', $local, true);
        core_deploy_files::writeJson($dir . '/schema_plan.json.gz', $plan, true);

        // Datos de configuración
        $config = $client->call('config_data');
        $data   = $this->dataPlan($db, $config);
        core_deploy_files::writeJson($dir . '/config_data.json.gz', $config, true);

        // Scripts de parámetros (001_default.sql + el de la compañía registrada en el servidor)
        $scripts = $this->validateScripts($client->call('client_script'));
        core_deploy_files::writeJson($dir . '/scripts.json.gz', ['scripts' => $scripts], true);

        $hash = hash('sha256', json_encode([$plan['hash'], $data['tables'], array_column($scripts, 'name')]));
        $run['compared']       = true;
        $run['schemaHash']     = $plan['hash'];
        $run['schemaVerified'] = false;
        $run['schemaApplied']  = [];
        $run['dataApplied']    = false;
        $run['filesApplied']   = [];
        $run['planHash']       = $hash;

        foreach ($data['warnings'] as $warning) {
            $this->log($run['upgradeID'], 'Aviso: ' . $warning);
        }
        foreach ($plan['notes'] as $note) {
            $this->log($run['upgradeID'], 'Nota: ' . $note);
        }
        $this->log($run['upgradeID'], 'Plan: ' . count($plan['steps']) . ' cambios de estructura, ' . count($data['tables']) . ' tablas de datos, ' . count($scripts) . ' scripts.');

        return $this->out('compare', true, 0, 1, [
            'files'  => $files,
            'schema' => ['steps' => $plan['steps'], 'extras' => $plan['extras'], 'summary' => $plan['summary'], 'notes' => $plan['notes']],
            'data'   => ['tables' => $data['tables'], 'scripts' => array_column($scripts, 'name')],
            'hash'   => $hash,
        ]);
    }

    /** Recrea la rutina local cuando falla el CREATE de la versión del servidor (tras el DROP). */
    private function restoreLocalRoutine($db, array $run, array $step)
    {
        $keys = ['funcion' => 'functions', 'procedimiento' => 'procedures', 'trigger' => 'triggers'];
        $key  = $keys[$step['group']];
        $local = core_deploy_files::readJson($this->runDir($run['upgradeID']) . '/local_snapshot.json.gz');
        if (!$local || !isset($local[$key][$step['object']])) {
            return ' (no existía localmente; no hay nada que recrear)';
        }
        $result = core_deploy_schema::run($db, [['id' => 'restore', 'sql' => core_deploy_schema::stripDefiner($local[$key][$step['object']])]]);
        return $result['ok'] ? ' (se recreó la versión local)' : ' (no se pudo recrear la versión local: ' . $result['results'][0]['error'] . ')';
    }

    /** 6. Estructura: aplica solo los ids recibidos, paso a paso; se detiene en el primer error. */
    private function stepApplySchema(array &$run, array $params)
    {
        $this->requireFlag($run, 'compared', 'Falta la comparación.');
        $ids = json_decode(isset($params['ids']) ? (string) $params['ids'] : '[]', true);
        if (!is_array($ids)) {
            throw new \Exception('Lista de cambios de estructura inválida.');
        }
        $db   = db_connect();
        $plan = $this->readRunJson($run['upgradeID'], 'schema_plan.json.gz');

        // Antes del primer cambio se confirma que la estructura local sigue igual a la comparada
        if (empty($run['schemaVerified'])) {
            $server = $this->readRunJson($run['upgradeID'], 'server_snapshot.json.gz');
            $now    = $this->schemaPlan($server, core_deploy_schema::snapshot($db));
            if (!hash_equals((string) $run['schemaHash'], $now['hash'])) {
                throw new \Exception('La estructura local cambió desde la comparación; cancele y vuelva a iniciar la actualización.');
            }
            $run['schemaVerified'] = true;
        }

        $byId = [];
        foreach ($plan['steps'] as $step) {
            $byId[$step['id']] = $step;
        }
        $results = [];
        foreach ($ids as $id) {
            $id = (string) $id;
            if (!isset($byId[$id])) {
                $results[] = ['id' => $id, 'ok' => false, 'ms' => 0, 'error' => 'Cambio desconocido'];
                $this->fail('El cambio de estructura ' . $id . ' no está en el plan.', ['results' => $results]);
            }
            if (in_array($id, $run['schemaApplied'], true)) {
                $results[] = ['id' => $id, 'ok' => true, 'ms' => 0, 'error' => null];
                continue;
            }
            $step       = $byId[$id];
            $statements = [];
            foreach ($step['sql'] as $i => $sql) {
                $statements[] = ['id' => $id . '#' . $i, 'sql' => $sql];
            }
            $start  = microtime(true);
            $result = core_deploy_schema::run($db, $statements);
            $ms     = (int) round((microtime(true) - $start) * 1000);
            if (!$result['ok']) {
                $last  = end($result['results']);
                $error = (string) $last['error'];
                // Rutina: el DROP pasó y falló el CREATE → se recrea la versión local
                if (isset($step['group']) && in_array($step['group'], ['funcion', 'procedimiento', 'trigger'], true) && count($result['results']) > 1) {
                    $error .= $this->restoreLocalRoutine($db, $run, $step);
                }
                $results[] = ['id' => $id, 'ok' => false, 'ms' => $ms, 'error' => $error];
                $this->log($run['upgradeID'], 'ERROR estructura ' . $step['object'] . ': ' . $error);
                $this->fail('falló el cambio de estructura ' . $step['object'] . ': ' . $error, ['results' => $results]);
            }
            $run['schemaApplied'][] = $id;
            $results[]              = ['id' => $id, 'ok' => true, 'ms' => $ms, 'error' => null];
            $this->log($run['upgradeID'], 'OK estructura ' . $step['group'] . ' ' . $step['object'] . ' (' . $ms . ' ms)');
        }
        return $this->out('apply_schema', true, 0, 1, ['results' => $results]);
    }

    /** 6. Datos de configuración y scripts en una transacción, con respaldo previo de las tablas. */
    private function stepApplyData(array &$run)
    {
        $this->requireFlag($run, 'compared', 'Falta la comparación.');
        $db      = db_connect();
        $config  = $this->readRunJson($run['upgradeID'], 'config_data.json.gz');
        $scripts = $this->readRunJson($run['upgradeID'], 'scripts.json.gz');
        $scripts = isset($scripts['scripts']) && is_array($scripts['scripts']) ? $scripts['scripts'] : [];

        // Se recalcula con la estructura ya aplicada (tablas y columnas nuevas)
        $plan = $this->dataPlan($db, $config);
        foreach ($plan['tables'] as $table => $info) {
            if ($info['mode'] !== core_deploy_rules::MODE_SKIP && !isset($plan['changes'][$table])) {
                $this->log($run['upgradeID'], 'Aviso: ' . $table . ' no existe localmente; se omite.');
            }
        }
        $backupRows        = core_deploy_data::backup($db, array_keys($plan['changes']), $this->runDir($run['upgradeID']) . '/backup_config_data.json.gz');
        $run['dataBackup'] = true;
        $this->saveRun($run);
        $this->log($run['upgradeID'], 'Respaldo de datos de configuración: ' . $backupRows . ' filas.');

        $report = core_deploy_data::apply($db, $plan['changes'], $scripts);
        $detail = ['ok' => $report['ok'], 'tables' => $report['tables'], 'scripts' => $report['scripts'], 'error' => $report['error']];
        if (!$report['ok']) {
            $this->fail('datos revertidos (ROLLBACK): ' . $report['error'], $detail);
        }
        $run['dataApplied'] = true;
        $this->log($run['upgradeID'], 'Datos de configuración aplicados en ' . count($report['tables']) . ' tablas y ' . count($report['scripts']) . ' scripts.');
        return $this->out('apply_data', true, 0, 1, $detail);
    }

    /** Carga en memoria las clases del actualizador antes de sobrescribir sus archivos. */
    private function preloadClasses()
    {
        $classes = [
            core_upgrade_rules::class, core_upgrade_files::class, core_upgrade_lock::class, core_upgrade_client::class,
            core_upgrade_auth::class, core_upgrade_package::class, core_upgrade_dbdump::class,
            core_deploy_files::class, core_deploy_crypto::class, core_deploy_data::class, core_deploy_rules::class,
            core_deploy_schema::class, core_deploy_sql_splitter::class,
            'App\Libraries\core_web_deploy\core_deploy_package',
        ];
        foreach ($classes as $class) {
            class_exists($class, true);
        }
    }

    /** 6. Archivos: un zip por petición (index = zip), health check y reversión del zip si falla. */
    private function stepApplyFiles(array &$run, $index)
    {
        $this->requireFlag($run, 'dataApplied', 'Aplique primero los datos de configuración.');
        $zips  = array_keys(core_upgrade_rules::packages($run['mode']));
        $total = count($zips);
        if ($index >= $total) {
            return $this->out('apply_files', true, $total - 1, $total, ['zip' => '', 'updated' => 0, 'created' => 0, 'unchanged' => 0, 'errors' => [], 'health' => ['ok' => true, 'message' => '']]);
        }
        $this->preloadClasses();
        $zip     = $zips[$index];
        $key     = $this->zipKey($zip);
        $extract = $this->extractResult($run['upgradeID'], $zip);
        $backup  = $this->filesBackupDir($run['upgradeID'], $zip);

        if (!empty($run['filesApplied'][$zip])) {
            $saved = $this->readRunJson($run['upgradeID'], 'files_' . $key . '.json.gz');
            return $this->out('apply_files', $index >= $total - 1, $index, $total, ['zip' => $zip, 'updated' => count($saved['updated']), 'created' => count($saved['created']), 'unchanged' => $saved['unchanged'], 'errors' => [], 'health' => ['ok' => true, 'message' => 'Ya aplicado']]);
        }

        $results = core_upgrade_files::apply($extract['files'], $this->stagingDir($run['upgradeID'], $zip), $backup, $run['mode'], $zip);
        // Se guarda antes del health check: la reversión completa lo necesita
        core_deploy_files::writeJson($this->runDir($run['upgradeID']) . '/files_' . $key . '.json.gz', $results, true);
        $run['filesTouched'][$zip] = true;
        $this->saveRun($run);

        $detail = [
            'zip'       => $zip,
            'updated'   => count($results['updated']),
            'created'   => count($results['created']),
            'unchanged' => $results['unchanged'],
            'errors'    => array_slice($results['errors'], 0, 200),
            'health'    => null,
        ];
        if (count($results['errors'])) {
            $reverted = core_upgrade_files::rollback($results, $backup);
            $this->log($run['upgradeID'], $zip . ': ' . count($results['errors']) . ' errores; paquete revertido (' . $reverted['restored'] . ' restaurados, ' . $reverted['removed'] . ' eliminados).');
            $this->fail(count($results['errors']) . ' errores al actualizar ' . $zip . '; se revirtió el paquete. Primero: ' . $results['errors'][0]['path'] . ' (' . $results['errors'][0]['error'] . ')', $detail);
        }
        $health           = core_upgrade_files::health();
        $detail['health'] = $health;
        if (!$health['ok']) {
            $reverted = core_upgrade_files::rollback($results, $backup);
            $this->log($run['upgradeID'], $zip . ': health check falló; paquete revertido (' . $reverted['restored'] . ' restaurados, ' . $reverted['removed'] . ' eliminados).');
            $this->fail('posMe no responde después de actualizar ' . $zip . ' (se revirtió el paquete): ' . $health['message'], $detail);
        }
        $run['filesApplied'][$zip] = true;
        $this->log($run['upgradeID'], $zip . ': ' . $detail['updated'] . ' actualizados, ' . $detail['created'] . ' nuevos, ' . $detail['unchanged'] . ' sin cambios. ' . $health['message']);
        return $this->out('apply_files', $index >= $total - 1, $index, $total, $detail);
    }

    /** 7. Verificación: hash de lo instalado, estructura pendiente y datos aplicados. */
    private function stepVerify(array &$run)
    {
        $checked  = 0;
        $mismatch = [];
        foreach (array_keys(core_upgrade_rules::packages($run['mode'])) as $zip) {
            if (empty($run['filesApplied'][$zip])) {
                throw new \Exception('El paquete ' . $zip . ' no se aplicó.');
            }
            $result   = core_upgrade_files::verify($this->extractResult($run['upgradeID'], $zip)['files']);
            $checked += $result['checked'];
            $mismatch = array_merge($mismatch, $result['mismatch']);
        }
        $server  = $this->readRunJson($run['upgradeID'], 'server_snapshot.json.gz');
        $pending = count($this->schemaPlan($server, core_deploy_schema::snapshot(db_connect()))['steps']);
        $detail  = [
            'files'  => ['checked' => $checked, 'mismatch' => array_slice($mismatch, 0, 500)],
            'schema' => ['pending' => $pending],
            'data'   => ['ok' => !empty($run['dataApplied'])],
        ];
        if (count($mismatch)) {
            $this->fail(count($mismatch) . ' archivos no coinciden con lo descargado.', $detail);
        }
        if (empty($run['dataApplied'])) {
            $this->fail('Los datos de configuración no se aplicaron.', $detail);
        }
        $run['verified'] = true;
        $this->log($run['upgradeID'], 'Verificación: ' . $checked . ' archivos OK, ' . $pending . ' cambios de estructura pendientes.');
        return $this->out('verify', true, 0, 1, $detail);
    }

    /** 8. Cierre: suelta candado y mantenimiento en cualquier estado; con ok registra la versión. */
    private function stepFinish($upgradeID, array $params)
    {
        $status     = isset($params['status']) && $params['status'] === 'ok' ? 'ok' : 'error';
        $run        = core_deploy_files::readJson($this->runDir($upgradeID) . '/state.json');
        $newVersion = '';

        if ($status === 'ok') {
            if (!$run || $run['status'] !== 'running' || empty($run['verified'])) {
                throw new \Exception('No se puede cerrar como completada: faltan etapas por terminar.');
            }
            $newVersion = (string) $run['remote']['version'];
            core_deploy_files::ensureDir($this->clientDir());
            core_deploy_files::writeJson($this->localStateFile(), [
                'version'     => $newVersion,
                'publishedOn' => (string) $run['remote']['publishedOn'],
                'upgradedOn'  => helper_getDateTime(),
                'upgradeID'   => $upgradeID,
                'mode'        => $run['mode'],
                'by'          => $run['by'],
            ]);
        }

        core_upgrade_lock::maintenanceOff($upgradeID);
        core_upgrade_lock::release($upgradeID);

        $duration = 0;
        if ($run) {
            if (in_array($run['status'], ['running', 'rolling_back'], true)) {
                $run['status'] = $status === 'ok' ? 'ok' : 'error';
            }
            $duration           = (int) round((microtime(true) - (float) $run['startedTs']) * 1000);
            $run['finishedOn']  = helper_getDateTime();
            $run['durationMs']  = $duration;
            $run['stage']       = 8;
            $run['action']      = 'finish';
            $this->saveRun($run);
            // Los paquetes y el staging ya no se necesitan (la reversión usa files_backup)
            core_deploy_files::removeDir($this->runDir($upgradeID) . '/packages');
            core_deploy_files::removeDir($this->runDir($upgradeID) . '/staging');
            $this->log($upgradeID, $status === 'ok' ? 'Actualización completada: versión ' . $newVersion . '.' : 'Proceso cerrado con error o cancelado. Mantenimiento desactivado.');
        }
        return $this->out('finish', true, 0, 1, ['status' => $status, 'newVersion' => $newVersion, 'durationMs' => $duration]);
    }

    /** 9. Reversión por bloques: archivos → datos de configuración → BD completa (opcional). */
    private function stepRollback(array &$run, array $params, $index, $deadline)
    {
        $upgradeID = $run['upgradeID'];
        $db        = db_connect();
        if ($index === 0 || empty($run['rollback'])) {
            core_upgrade_lock::acquire($upgradeID);
            core_upgrade_lock::maintenanceOn($upgradeID, isset($run['by']) ? $run['by'] : '');
            $zips = [];
            foreach (isset($run['filesTouched']) && is_array($run['filesTouched']) ? $run['filesTouched'] : [] as $zip => $touched) {
                if ($touched) {
                    $zips[] = $zip;
                }
            }
            $includeDb = isset($params['includeDb']) && in_array((string) $params['includeDb'], ['1', 'true'], true);
            if ($includeDb && empty($run['backupVerified'])) {
                throw new \Exception('No hay un respaldo verificado de la base de datos para esta actualización.');
            }
            $run['rollback'] = ['phase' => 'files', 'zips' => array_reverse($zips), 'zipIndex' => 0, 'includeDb' => $includeDb, 'unitsDone' => 0, 'dbStarted' => false, 'dbTotal' => 0, 'dbDone' => 0, 'startedOn' => helper_getDateTime()];
            $run['status']   = 'rolling_back';
            $this->log($upgradeID, 'Reversión iniciada' . ($includeDb ? ' (incluye la base de datos completa)' : '') . '.');
        } else {
            core_upgrade_lock::refresh($upgradeID);
            core_upgrade_lock::maintenanceRefresh($upgradeID);
        }

        $rb       = $run['rollback'];
        $phase    = $rb['phase'];
        $restored = 0;
        if ($phase === 'files') {
            if ($rb['zipIndex'] < count($rb['zips'])) {
                $zip     = $rb['zips'][$rb['zipIndex']];
                $results = core_deploy_files::readJson($this->runDir($upgradeID) . '/files_' . $this->zipKey($zip) . '.json.gz');
                if ($results) {
                    $result = core_upgrade_files::rollback($results, $this->filesBackupDir($upgradeID, $zip));
                    if (count($result['errors'])) {
                        $run['rollback'] = $rb;
                        $this->fail('No se pudieron restaurar ' . count($result['errors']) . ' archivos de ' . $zip . '. Primero: ' . $result['errors'][0]['path'] . ' (' . $result['errors'][0]['error'] . ')', ['phase' => 'files', 'restored' => $result['restored'], 'done' => false, 'errors' => array_slice($result['errors'], 0, 200)]);
                    }
                    $restored = $result['restored'] + $result['removed'];
                    $this->log($upgradeID, 'Reversión de ' . $zip . ': ' . $result['restored'] . ' restaurados, ' . $result['removed'] . ' eliminados.');
                }
                $rb['zipIndex']++;
                $rb['unitsDone']++;
            }
            if ($rb['zipIndex'] >= count($rb['zips'])) {
                $rb['phase'] = 'config_data';
            }
        } elseif ($phase === 'config_data') {
            $file = $this->runDir($upgradeID) . '/backup_config_data.json.gz';
            if (!empty($run['dataBackup']) && is_file($file)) {
                $restored = array_sum(core_deploy_data::restore($db, $file));
                $this->log($upgradeID, 'Datos de configuración restaurados: ' . $restored . ' filas.');
            }
            $rb['unitsDone']++;
            $rb['phase'] = $rb['includeDb'] ? 'db' : 'done';
        } elseif ($phase === 'db') {
            $result          = core_upgrade_dbdump::restoreStep($db, $upgradeID, empty($rb['dbStarted']), $deadline);
            $rb['dbStarted'] = true;
            $restored        = $result['restored'] - $rb['dbDone'];
            $rb['dbDone']    = $result['restored'];
            $rb['dbTotal']   = $result['total'];
            if ($result['done']) {
                $rb['phase'] = 'done';
                $this->log($upgradeID, 'Base de datos restaurada desde el respaldo ' . $upgradeID . '.');
            }
        }

        $done            = $rb['phase'] === 'done';
        $run['rollback'] = $rb;
        if ($done) {
            // Se restaura la versión registrada antes de la actualización
            $previous = isset($run['previousState']) && is_array($run['previousState']) ? $run['previousState'] : [];
            if (count($previous)) {
                core_deploy_files::writeJson($this->localStateFile(), $previous);
            } elseif (is_file($this->localStateFile())) {
                $current = $this->localState();
                if (isset($current['upgradeID']) && $current['upgradeID'] === $upgradeID) {
                    @unlink($this->localStateFile());
                }
            }
            $run['status'] = 'rolledback';
            core_upgrade_lock::maintenanceOff($upgradeID);
            core_upgrade_lock::release($upgradeID);
            $this->log($upgradeID, 'Reversión completada. Mantenimiento desactivado.');
        }
        $total     = count($rb['zips']) + 1 + ($rb['includeDb'] ? max(1, (int) $rb['dbTotal']) : 0);
        $unitsDone = $rb['unitsDone'] + ($rb['includeDb'] ? (int) $rb['dbDone'] : 0);
        return $this->out('rollback', $done, $done ? $total - 1 : max(0, $unitsDone - 1), $total, ['phase' => $phase, 'restored' => $restored, 'done' => $done]);
    }
}
