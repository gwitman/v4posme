<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Reglas del despliegue remoto de posMe.
 *
 * Es la única fuente de reglas que comparten la consola local (core_merge::deploy)
 * y el agente remoto (core_deploy_agent). Vive en app/Libraries (y no en app/Config)
 * porque app/Config es propio de cada cliente y nunca se despliega.
 *
 * La lista blanca de archivos es la que ya aplicaba core_merge::submitprocesapp:
 * app/Config/Routes.php + app/{Controllers,Models,Libraries,Helpers,Views}.
 */
class core_deploy_rules
{
    const VERSION            = '1.1.0';
    // Versión mínima del agente que tiene las acciones de configuración y logos
    const VERSION_COMPANY_FILES = '1.1.0';

    // Directorios de trabajo (dentro de WRITEPATH)
    const AGENT_DIR          = 'posme_agent';
    const CONSOLE_DIR        = 'posme_deploy';

    // Paquete
    const MANIFEST_NAME      = 'posme_manifest.json';
    const CHUNK_SIZE         = 1048576;          // 1 MB por bloque (viaja en base64)
    const MAX_ENTRIES        = 20000;
    const MAX_TOTAL_BYTES    = 314572800;        // 300 MB descomprimido
    const MAX_FILE_BYTES     = 52428800;         // 50 MB por archivo
    const MAX_RATIO          = 200;              // razón de compresión máxima (zip bomb)
    const MAX_UPLOAD_BYTES   = 104857600;        // 100 MB por ZIP recibido

    // Seguridad del agente
    const SIGNATURE_WINDOW   = 300;              // segundos de tolerancia del timestamp
    const MAX_AUTH_FAILURES  = 20;               // fallos permitidos por ventana
    const AUTH_FAILURE_TTL   = 600;              // ventana de bloqueo (segundos)
    const LOCK_TTL           = 7200;             // un candado abandonado caduca en 2 h
    const KEEP_DEPLOYS       = 5;                // respaldos que se conservan

    // Modos de sincronización de datos
    const MODE_FULL          = 'full';           // insertar + actualizar + eliminar
    const MODE_UPSERT        = 'upsert';         // insertar + actualizar
    const MODE_INSERT        = 'insert';         // solo insertar lo que no existe
    const MODE_SKIP          = 'skip';           // no sincronizar

    // Perfiles Navicat que definen las tablas (fuente de verdad, no se duplica la lista)
    const NAVICAT_DIR             = 'resource/file_navicate_perfil_sincronization/';
    const NAVICAT_PROFILE_CONFIG  = '01 sincronizar todas las tablas de configuracion.ndsymysql';
    const NAVICAT_PROFILE_COUNTER = '03 sincronizar contador.ndsymysql';

    // Scripts de parámetros
    const SQL_CLIENT_DIR     = 'resource/file_sql/clientes/';
    const SQL_DEFAULT_FILE   = '001_default.sql';

    // Configuración y logos de cada compañía (pestaña "Configuración y logos")
    const CONFIG_DIR            = 'app/Config/';
    const CONFIG_MAX_BYTES      = 524288;        // 512 KB por archivo de configuración
    const FILES_BACKUP_DIR      = 'files_backup'; // dentro de WRITEPATH/posme_agent
    const FILES_KEEP_BACKUPS    = 20;            // respaldos de archivos que se conservan
    const CONFIG_CONFIRM_NO_BACKUP = 'GUARDAR SIN RESPALDO';
    const LOGO_DIR              = 'resource/img/logos/'; // relativo a FCPATH
    const LOGO_MAX_BYTES        = 1048576;       // 1 MB por logo (recibido y convertido)
    const LOGO_MAX_PIXELS       = 4000;          // ancho y alto máximos
    const LOGO_TICKET_MAX_WIDTH = 576;           // ancho útil de una impresora de 80 mm

    /** Archivos de app/Config que se listan pero no se editan desde la consola. */
    public static $CONFIG_READONLY = [
        'Routes.php',
    ];

    /** Archivos de app/Config cuyo error deja caído el sistema completo. */
    public static $CONFIG_CRITICAL = [
        'Constants.php',
        'Database.php',
        'App.php',
        'Autoload.php',
        'Filters.php',
    ];

    /** Logos que se pueden reemplazar (el orden corresponde a los campos logo_1..logo_4). */
    public static $LOGO_FILES = [
        'logo-micro-finanza.png',
        'logo-micro-finanza.jpg',
        'direct-ticket-logo-micro-finanza.png',
        'direct-ticket-logo-micro-finanza.jpg',
    ];

    /** Archivos sueltos que sí se actualizan. */
    public static $ALLOWED_FILES = [
        'app/Config/Routes.php',
    ];

    /** Carpetas que se actualizan completas. */
    public static $ALLOWED_DIRS = [
        'app/Controllers/',
        'app/Models/',
        'app/Libraries/',
        'app/Helpers/',
        'app/Views/',
    ];

    /** Rutas propias de cada instalación: se conservan siempre. */
    public static $PRESERVED_PREFIXES = [
        'app/Config/',
        'app/Common.php',
        'app/Database/',
        'app/Filters/',
        'app/Language/',
        'app/ThirdParty/',
        'app/index.html',
        '.env',
        'env',
        'writable/',
        'vendor/',
        'system/',
        'public/',
        'tests/',
        'spark',
        'composer.json',
        'composer.lock',
        'phpunit.xml.dist',
    ];

    /** Contenido que se descarta aunque esté dentro de una carpeta autorizada. */
    public static $IGNORED_PATTERNS = [
        '#(^|/)\.git(/|$)#',
        '#(^|/)\.idea(/|$)#',
        '#(^|/)\.DS_Store$#',
        '#(^|/)Thumbs\.db$#i',
        '#^app/Libraries/core_web_qr/(cache|temp)/#',
    ];

    /** Extensiones permitidas dentro de las carpetas autorizadas ('' = sin extensión). */
    public static $ALLOWED_EXTENSIONS = [
        '', 'php', 'html', 'htm', 'js', 'css', 'json', 'txt', 'md', 'sql', 'xml', 'csv',
        'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot', 'otf',
        'map', 'z', 'types', 'afm', 'ufm', 'gitkeep', 'gitattributes', 'sh', 'bat',
    ];

    /** Tablas de solo inserción (perfil 03): sus valores vivos son del cliente. */
    public static $INSERT_ONLY_TABLES = [
        'tb_counter',
    ];

    /**
     * Valores forzados al insertar en tablas de solo inserción.
     * Regla heredada de core_merge::merge_of_posme_merge_to_poeme_data_onlyinsert:
     * un contador nuevo nace en cero.
     */
    public static $INSERT_OVERRIDES = [
        'tb_counter' => ['initialValue' => 0, 'currentValue' => 0],
    ];

    /**
     * Tablas transaccionales que el agente nunca acepta sincronizar,
     * aunque se lo pida una consola con la firma correcta.
     */
    public static $DATA_DENY_TABLES = [
        'tb_transaction_master', 'tb_transaction_master_detail', 'tb_transaction_master_info',
        'tb_transaction_master_detail_credit', 'tb_customer', 'tb_customer_credit',
        'tb_customer_credit_document', 'tb_customer_credit_amoritization', 'tb_customer_credit_amortization',
        'tb_item', 'tb_item_warehouse', 'tb_user', 'tb_role', 'tb_user_permission', 'tb_journal_entry',
        'tb_journal_entry_detail', 'tb_entity', 'tb_naturales', 'tb_legal', 'tb_employee', 'tb_provider',
        'tb_deploy_target',
        // Skills del agente de IA: cada instalación mantiene las suyas
        'tb_ai_skill',
    ];

    /**
     * Clasifica una ruta relativa (ya normalizada) del paquete.
     *
     * @param string $path Ruta relativa con separador '/', sin prefijo.
     * @return array ['category' => update|preserved|ignored|rejected, 'reason' => string]
     */
    public static function classify($path)
    {
        foreach (self::$IGNORED_PATTERNS as $pattern) {
            if (preg_match($pattern, $path)) {
                return ['category' => 'ignored', 'reason' => 'Archivo de trabajo o de control de versiones'];
            }
        }

        if (in_array($path, self::$ALLOWED_FILES, true)) {
            return ['category' => 'update', 'reason' => 'Archivo autorizado'];
        }

        foreach (self::$ALLOWED_DIRS as $dir) {
            if (strpos($path, $dir) === 0) {
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (!in_array($extension, self::$ALLOWED_EXTENSIONS, true)) {
                    return ['category' => 'rejected', 'reason' => 'Extensión no permitida: .' . $extension];
                }
                return ['category' => 'update', 'reason' => 'Carpeta autorizada ' . rtrim($dir, '/')];
            }
        }

        foreach (self::$PRESERVED_PREFIXES as $prefix) {
            if ($path === rtrim($prefix, '/') || strpos($path, $prefix) === 0) {
                return ['category' => 'preserved', 'reason' => 'Propio de cada instalación (' . rtrim($prefix, '/') . ')'];
            }
        }

        return ['category' => 'ignored', 'reason' => 'Fuera de la lista de actualización'];
    }

    /**
     * Indica si un nombre de tabla es válido para sincronizar datos.
     *
     * @param string $table Nombre de la tabla.
     * @return bool
     */
    public static function isSyncableTable($table)
    {
        return is_string($table)
            && preg_match('/^tb_[a-z0-9_]+$/', $table) === 1
            && !in_array($table, self::$DATA_DENY_TABLES, true);
    }

    /**
     * Modos de datos válidos.
     *
     * @return array
     */
    public static function dataModes()
    {
        return [self::MODE_FULL, self::MODE_UPSERT, self::MODE_INSERT, self::MODE_SKIP];
    }

    /**
     * Indica si un nombre es un archivo de configuración editable/listable:
     * solo primer nivel de app/Config, letras, números y guion bajo, extensión .php.
     *
     * @param string $name Nombre del archivo (sin ruta).
     * @return bool
     */
    public static function isConfigName($name)
    {
        return is_string($name) && preg_match('/^[A-Za-z0-9_]+\.php$/D', $name) === 1;
    }

    /**
     * Indica si un archivo de configuración es de solo lectura en la consola.
     *
     * @param string $name Nombre del archivo.
     * @return bool
     */
    public static function isConfigReadonly($name)
    {
        return in_array($name, self::$CONFIG_READONLY, true);
    }

    /**
     * Indica si un archivo de configuración es crítico (su error tumba el sistema).
     *
     * @param string $name Nombre del archivo.
     * @return bool
     */
    public static function isConfigCritical($name)
    {
        return in_array($name, self::$CONFIG_CRITICAL, true);
    }

    /**
     * Indica si un nombre pertenece a la lista fija de logos reemplazables.
     *
     * @param string $name Nombre del archivo.
     * @return bool
     */
    public static function isLogoName($name)
    {
        return is_string($name) && in_array($name, self::$LOGO_FILES, true);
    }

    /**
     * Formato de imagen esperado según la extensión del logo.
     *
     * @param string $name Nombre del logo.
     * @return string 'png' | 'jpg'
     */
    public static function logoFormat($name)
    {
        return strtolower(pathinfo((string) $name, PATHINFO_EXTENSION)) === 'png' ? 'png' : 'jpg';
    }

    /**
     * Indica si el logo es de ticket (direct-ticket-*), que se imprime en 80 mm.
     *
     * @param string $name Nombre del logo.
     * @return bool
     */
    public static function isTicketLogo($name)
    {
        return strpos((string) $name, 'direct-ticket-') === 0;
    }

    /**
     * Indica si la versión de un agente soporta configuración y logos.
     *
     * @param string $version  Versión reportada por ping.
     * @param array  $features Funciones reportadas por ping.
     * @param string $feature  Función requerida ('config' | 'logos').
     * @return bool
     */
    public static function agentSupports($version, $features, $feature)
    {
        return is_string($version)
            && version_compare($version, self::VERSION_COMPANY_FILES, '>=')
            && is_array($features)
            && in_array($feature, $features, true);
    }
}
