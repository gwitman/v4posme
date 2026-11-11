<?php
//posme:2026-11-10
namespace App\Libraries\core_web_upgrade;

/**
 * Reglas de la actualización inversa de posMe (core_upgrade/byPop3).
 *
 * La instalación del cliente descarga del servidor central (core_upgrade_server)
 * los paquetes syncApp/syncVendor/syncPublic/syncConfig, la estructura de la BD,
 * los datos de configuración y los scripts de parámetros, y los aplica localmente.
 *
 * Esta clase es la única fuente de reglas: modos, paquetes, destinos, lista dura
 * de rutas que nunca se sobrescriben y límites anti zip-bomb.
 */
class core_upgrade_rules
{
    const PROTOCOL           = 1;
    const VERSION            = '1.0.0';
    const DEFAULT_SOURCE_URL = 'https://posme.net/v4posme/public/public';

    // Carpetas de trabajo (dentro de WRITEPATH)
    const CLIENT_DIR         = 'posme_upgrade';
    const SERVER_DIR         = 'posme_upgrade_server';
    const LOCK_DIR           = 'posme_agent';     // candado compartido con core_deploy_agent

    // Transferencia y tiempos
    const CHUNK_SIZE         = 1048576;           // 1 MB por bloque (viaja en base64)
    const STEP_BUDGET        = 20;                // segundos de trabajo por petición de bloque
    const TIME_LIMIT         = 900;               // set_time_limit de cada petición
    const MAINTENANCE_TTL    = 7200;              // 2 h desde el último refresco
    const KEEP_RUNS          = 5;                 // corridas que se conservan
    const KEEP_BACKUPS       = 2;                 // respaldos completos de BD que se conservan
    const CONFIRM_WORD       = 'ACTUALIZAR';

    // Límites propios de los paquetes (vendor tiene muchos archivos)
    const MAX_ZIP_BYTES      = 524288000;         // 500 MB por ZIP descargado
    const MAX_ENTRIES        = 80000;
    const MAX_TOTAL_BYTES    = 1073741824;        // 1 GB descomprimido por ZIP
    const MAX_FILE_BYTES     = 104857600;         // 100 MB por archivo
    const MAX_RATIO          = 200;               // razón de compresión máxima

    // Respaldo completo de la BD
    const DUMP_PAGE_ROWS     = 2000;              // filas por lectura paginada
    const DUMP_INSERT_ROWS   = 200;               // filas por sentencia INSERT

    /**
     * Paquetes por modo: zip => destino, carpeta raíz que se quita y rutas protegidas.
     * Las rutas protegidas se suman a la lista dura global.
     */
    public static $MODES = [
        'local' => [
            // En instalación local (PC) Constants.php y App.php SÍ se actualizan si vienen en el zip
            'syncApp.zip'    => ['target' => 'app/',        'root' => 'app',    'protected' => []],
            'syncVendor.zip' => ['target' => 'vendor/',     'root' => 'vendor', 'protected' => []],
            'syncPublic.zip' => ['target' => 'public/',     'root' => 'public', 'protected' => ['public/resource/img/logos/', 'public/resource/file_company/', 'public/.htaccess', 'public/index.php']],
        ],
        'cloud' => [
            'syncVendor.zip' => ['target' => 'vendor/',     'root' => 'vendor', 'protected' => []],
            // En la nube Constants.php y App.php son propios de cada instancia: nunca se sobrescriben
            'syncConfig.zip' => ['target' => 'app/Config/', 'root' => 'Config', 'protected' => ['app/Config/Constants.php', 'app/Config/App.php']],
        ],
    ];

    /** Lista dura global: siempre gana (archivo exacto o carpeta terminada en "/"). */
    public static $HARD_DENY = [
        '.env',
        'env',
        'writable/',
        'system/',
        'public/resource/img/logos/',
        'public/resource/file_company/',
        'public/.htaccess',
        'public/index.php',
    ];

    /** Contenido de trabajo que nunca se copia. */
    public static $IGNORED_PATTERNS = [
        '#(^|/)\.git(/|$)#',
        '#(^|/)\.idea(/|$)#',
        '#(^|/)\.DS_Store$#',
        '#(^|/)Thumbs\.db$#i',
        '#(^|/)__MACOSX(/|$)#',
    ];

    /**
     * Nombres de todos los paquetes conocidos (unión de los modos).
     *
     * @return array
     */
    static function packageNames()
    {
        $names = [];
        foreach (self::$MODES as $packages) {
            foreach (array_keys($packages) as $name) {
                $names[$name] = true;
            }
        }
        return array_keys($names);
    }

    /**
     * Valida el modo.
     *
     * @param string $mode local | cloud
     * @return string
     */
    static function assertMode($mode)
    {
        if (!is_string($mode) || !isset(self::$MODES[$mode])) {
            throw new \Exception('Modo de actualización inválido.');
        }
        return $mode;
    }

    /**
     * Paquetes de un modo.
     *
     * @param string $mode Modo.
     * @return array [zip => ['target','root','protected']]
     */
    static function packages($mode)
    {
        return self::$MODES[self::assertMode($mode)];
    }

    /**
     * Indica si una ruta (relativa a la raíz) coincide con una lista de archivos/carpetas.
     *
     * @param string $path Ruta relativa a ROOTPATH con "/".
     * @param array  $list Archivos exactos o carpetas terminadas en "/".
     * @return bool
     */
    static function matchesList($path, array $list)
    {
        $lower = strtolower($path);
        foreach ($list as $item) {
            $item = strtolower($item);
            if (substr($item, -1) === '/') {
                if (strpos($lower . '/', $item) === 0) {
                    return true;
                }
            } elseif ($lower === $item) {
                return true;
            }
        }
        return false;
    }

    /**
     * Clasifica una entrada de un paquete.
     *
     * @param string $path Ruta relativa al destino del zip (ya sin prefijo raíz).
     * @param string $mode Modo.
     * @param string $zip  Nombre del zip.
     * @return array ['category' => update|protected|ignored|rejected, 'reason' => string, 'target' => ruta relativa a ROOTPATH]
     */
    static function classify($path, $mode, $zip)
    {
        $packages = self::packages($mode);
        if (!isset($packages[$zip])) {
            return ['category' => 'rejected', 'reason' => 'Paquete no permitido en el modo ' . $mode, 'target' => ''];
        }
        $safe = \App\Libraries\core_web_deploy\core_deploy_package::safeRelativePath($path);
        if ($safe === null || $safe !== $path) {
            return ['category' => 'rejected', 'reason' => 'Ruta peligrosa', 'target' => ''];
        }
        // Windows resuelve alias que saltarían la lista protegida por texto:
        // nombres cortos 8.3 (CONSTA~1.PHP), punto/espacio final (Constants.php.) y flujos NTFS (x::$DATA)
        foreach (explode('/', $safe) as $segment) {
            if (preg_match('/~\d/', $segment) || preg_match('/[. ]$/', $segment) || strpos($segment, ':') !== false) {
                return ['category' => 'rejected', 'reason' => 'Nombre no permitido en Windows (alias de otro archivo)', 'target' => ''];
            }
        }
        $target = $packages[$zip]['target'] . $safe;
        foreach (self::$IGNORED_PATTERNS as $pattern) {
            if (preg_match($pattern, $target)) {
                return ['category' => 'ignored', 'reason' => 'Archivo de trabajo o de control de versiones', 'target' => $target];
            }
        }
        if (self::matchesList($target, self::$HARD_DENY)) {
            return ['category' => 'protected', 'reason' => 'Lista dura: nunca se sobrescribe', 'target' => $target];
        }
        if (self::matchesList($target, $packages[$zip]['protected'])) {
            return ['category' => 'protected', 'reason' => 'Propio de cada instalación', 'target' => $target];
        }
        return ['category' => 'update', 'reason' => 'Paquete ' . $zip, 'target' => $target];
    }

    /**
     * Modo sugerido según el host: localhost, 127.0.0.1, ::1 o nombre del equipo → local.
     *
     * @param string|null $host Host de la petición (con o sin puerto).
     * @return string local | cloud
     */
    static function detectMode($host = null)
    {
        $host = strtolower(trim((string) $host));
        $host = preg_replace('/^\[([^\]]+)\](:\d+)?$/', '$1', $host);
        // El puerto solo se quita si queda un único ":" (en IPv6 sin corchetes, como ::1, no hay puerto)
        if (substr_count($host, ':') === 1) {
            $host = preg_replace('/:\d+$/', '', $host);
        }
        if (in_array($host, ['localhost', '127.0.0.1', '::1', ''], true)) {
            return 'local';
        }
        $machine = strtolower((string) @gethostname());
        if ($machine !== '' && ($host === $machine || strpos($machine, $host . '.') === 0)) {
            return 'local';
        }
        return 'cloud';
    }

    /**
     * Ajusta una sentencia DDL de MySQL 8 para MariaDB (collation 0900 e índices invisibles).
     *
     * @param string $sql Sentencia.
     * @return string
     */
    static function adaptSqlForMaria($sql)
    {
        $sql = preg_replace('/utf8mb4_0900_[a-z0-9_]+/i', 'utf8mb4_general_ci', (string) $sql);
        $sql = preg_replace('/\s*\/\*!80023\s+INVISIBLE\s*\*\//i', '', $sql);
        return $sql;
    }
}
