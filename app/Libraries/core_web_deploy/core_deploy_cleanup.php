<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Limpieza de servidor: libera espacio e inodos en las instalaciones posMe de un
 * hosting. Reemplaza de forma controlada a core_merge::merge_of_posme_merge_to_posme_initialize.
 *
 * El agente de una instalación recorre su carpeta padre y trata como instalación
 * posMe toda carpeta vecina real (no enlace) que tenga app/Config/Routes.php,
 * writable/ y public/index.php. Solo borra dentro de las rutas de cada categoría,
 * validadas con realpath, y nunca borra los archivos de protección ni la
 * configuración del agente.
 */
class core_deploy_cleanup
{
    /** Segundos máximos de borrado por petición; el resto continúa en la siguiente. */
    const TIME_BUDGET = 120;

    /** Categorías disponibles: clave => [etiqueta, descripción]. */
    public static $CATEGORIES = [
        'logs'         => ['Logs', 'writable/logs'],
        'cache'        => ['Caché', 'writable/cache'],
        'debugbar'     => ['Debugbar', 'writable/debugbar'],
        'uploads'      => ['Uploads', 'writable/uploads'],
        'session'      => ['Sesiones', 'writable/session (todas: los usuarios deberán volver a iniciar sesión)'],
        'agent'        => ['Despliegues del agente', 'writable/posme_agent/deploys (respaldos, staging y log; ya no se podrá revertir)'],
        'file_company' => ['Archivos de compañía', 'public/resource/file_company/company_*/component_* (imágenes, adjuntos, reportes y respaldos .sql)'],
    ];

    /** Nombres que nunca se borran en ninguna carpeta. */
    public static $KEEP_NAMES = ['index.html', '.htaccess', 'agent_config.php'];

    /** Carpetas vecinas que nunca se consideran instalaciones. */
    public static $IGNORED_SIBLINGS = ['tutoriales'];

    /**
     * Valida y normaliza la lista de categorías pedida.
     *
     * @param mixed $categories Lista recibida.
     * @return array Categorías válidas, en el orden de $CATEGORIES.
     */
    static function categories($categories)
    {
        $categories = is_array($categories) ? $categories : [];
        return array_values(array_filter(array_keys(self::$CATEGORIES), function ($key) use ($categories) {
            return in_array($key, $categories, true);
        }));
    }

    /**
     * Indica si una carpeta es una instalación posMe.
     *
     * @param string $path Ruta absoluta.
     * @return bool
     */
    static function isInstallation($path)
    {
        return is_dir($path) && !is_link($path)
            && is_file($path . '/app/Config/Routes.php')
            && is_dir($path . '/writable')
            && is_file($path . '/public/index.php');
    }

    /**
     * Instalaciones posMe de la carpeta padre de $selfRoot (incluida la propia).
     *
     * @param string $selfRoot Raíz de la instalación del agente (ROOTPATH).
     * @return array Lista de ['name', 'path', 'self'].
     */
    static function installations($selfRoot)
    {
        $self   = realpath($selfRoot);
        $parent = $self ? dirname($self) : false;
        if (!$self || !$parent || !is_dir($parent)) {
            throw new \Exception('No se pudo leer la carpeta de instalaciones.');
        }
        $result = [];
        foreach (scandir($parent) as $name) {
            if ($name === '.' || $name === '..' || in_array($name, self::$IGNORED_SIBLINGS, true)) {
                continue;
            }
            $path = $parent . DIRECTORY_SEPARATOR . $name;
            if (!self::isInstallation($path)) {
                continue;
            }
            $real = realpath($path);
            if ($real === false || dirname($real) !== $parent) {
                continue;
            }
            $result[] = ['name' => $name, 'path' => $real, 'self' => $real === $self];
        }
        return $result;
    }

    /**
     * Busca una instalación por nombre entre las vecinas válidas.
     *
     * @param string $selfRoot Raíz del agente.
     * @param string $name     Nombre de la carpeta.
     * @return array ['name', 'path', 'self']
     */
    static function findInstallation($selfRoot, $name)
    {
        foreach (self::installations($selfRoot) as $item) {
            if ($item['name'] === (string) $name) {
                return $item;
            }
        }
        throw new \Exception('La instalación "' . $name . '" no existe o no es una instalación posMe.');
    }

    /**
     * Indica si la instalación tiene un despliegue en curso (candado vigente).
     *
     * @param string $installPath Raíz de la instalación.
     * @return bool
     */
    static function isLocked($installPath)
    {
        $lock = core_deploy_files::readJson($installPath . '/writable/' . core_deploy_rules::AGENT_DIR . '/lock.json');
        return is_array($lock) && isset($lock['time']) && (time() - (int) $lock['time']) <= core_deploy_rules::LOCK_TTL;
    }

    /**
     * Carpetas cuyo contenido se vacía para una categoría (las carpetas se conservan).
     *
     * @param string $installPath Raíz de la instalación.
     * @param string $category    Clave de categoría.
     * @return array Rutas absolutas validadas dentro de la instalación.
     */
    static function targets($installPath, $category)
    {
        $dirs = [];
        switch ($category) {
            case 'logs':
            case 'cache':
            case 'debugbar':
            case 'uploads':
            case 'session':
                $dirs[] = $installPath . '/writable/' . $category;
                break;
            case 'agent':
                $dirs[] = $installPath . '/writable/' . core_deploy_rules::AGENT_DIR . '/deploys';
                break;
            case 'file_company':
                // Igual que la limpieza anterior: se vacía cada component_* de cada company_*
                $base = $installPath . '/public/resource/file_company';
                foreach (self::subdirs($base, 'company_') as $companyDir) {
                    foreach (self::subdirs($companyDir, 'component_') as $componentDir) {
                        $dirs[] = $componentDir;
                    }
                }
                break;
        }

        $root   = realpath($installPath);
        $result = [];
        foreach ($dirs as $dir) {
            $real = is_dir($dir) && !is_link($dir) ? realpath($dir) : false;
            if ($real !== false && $root !== false && strpos($real, $root . DIRECTORY_SEPARATOR) === 0) {
                $result[] = $real;
            }
        }
        return $result;
    }

    /**
     * Cuenta archivos y bytes que se borrarían por categoría (no borra nada).
     *
     * @param string $installPath Raíz de la instalación.
     * @param array  $categories  Categorías.
     * @param float  $deadline    microtime límite; al pasarlo el conteo queda parcial.
     * @return array clave => ['files', 'bytes', 'partial']
     */
    static function scan($installPath, array $categories, $deadline)
    {
        $result = [];
        foreach ($categories as $category) {
            $stats = ['files' => 0, 'bytes' => 0, 'partial' => false];
            foreach (self::targets($installPath, $category) as $dir) {
                if (!self::walk($dir, false, $stats, $deadline)) {
                    $stats['partial'] = true;
                    break;
                }
            }
            $result[$category] = $stats;
        }
        return $result;
    }

    /**
     * Borra el contenido de las categorías hasta agotar el tiempo de la petición.
     *
     * @param string $installPath Raíz de la instalación.
     * @param array  $categories  Categorías.
     * @param float  $deadline    microtime límite.
     * @return array ['complete' => bool, 'categories' => clave => ['files', 'bytes', 'errors']]
     */
    static function apply($installPath, array $categories, $deadline)
    {
        $result   = ['complete' => true, 'categories' => []];
        foreach ($categories as $category) {
            $stats = ['files' => 0, 'bytes' => 0, 'errors' => 0, 'partial' => false];
            foreach (self::targets($installPath, $category) as $dir) {
                if (!self::walk($dir, true, $stats, $deadline)) {
                    $result['complete'] = false;
                    break;
                }
            }
            $result['categories'][$category] = $stats;
            if (!$result['complete']) {
                break;
            }
        }

        // La limpieza anterior dejaba creada la carpeta de respaldos de la compañía
        if ($result['complete'] && in_array('file_company', $categories, true)) {
            foreach (self::subdirs($installPath . '/public/resource/file_company', 'company_') as $companyDir) {
                if (is_dir($companyDir . '/component_1') && !is_dir($companyDir . '/component_1/component_item_0')) {
                    @mkdir($companyDir . '/component_1/component_item_0', 0755, true);
                }
            }
        }
        return $result;
    }

    /**
     * Recorre el contenido de una carpeta sin seguir enlaces; cuenta o borra.
     * En el primer nivel conserva $KEEP_NAMES; las subcarpetas se eliminan completas.
     *
     * @param string $dir      Carpeta a vaciar.
     * @param bool   $delete   true borra; false solo cuenta.
     * @param array  $stats    Acumulador ['files', 'bytes', 'errors'].
     * @param float  $deadline microtime límite.
     * @param bool   $top      Primer nivel (aplica $KEEP_NAMES).
     * @return bool false si se agotó el tiempo.
     */
    private static function walk($dir, $delete, array &$stats, $deadline, $top = true)
    {
        $items = @scandir($dir);
        if ($items === false) {
            if (isset($stats['errors'])) {
                $stats['errors']++;
            }
            return true;
        }
        foreach ($items as $item) {
            if ($item === '.' || $item === '..' || ($top && in_array($item, self::$KEEP_NAMES, true))) {
                continue;
            }
            // La configuración del agente nunca se borra, esté donde esté
            if ($item === 'agent_config.php') {
                continue;
            }
            if (microtime(true) > $deadline) {
                return false;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_link($path) || !is_dir($path)) {
                $size = is_link($path) ? 0 : (int) @filesize($path);
                if ($delete) {
                    if (@unlink($path)) {
                        $stats['files']++;
                        $stats['bytes'] += $size;
                    } else {
                        $stats['errors']++;
                    }
                } else {
                    $stats['files']++;
                    $stats['bytes'] += $size;
                }
                continue;
            }
            if (!self::walk($path, $delete, $stats, $deadline, false)) {
                return false;
            }
            if ($delete && !@rmdir($path)) {
                $stats['errors']++;
            }
        }
        return true;
    }

    /**
     * Subcarpetas reales (no enlaces) cuyo nombre empieza con $prefix.
     *
     * @return array Rutas absolutas.
     */
    private static function subdirs($base, $prefix)
    {
        $result = [];
        if (!is_dir($base) || is_link($base)) {
            return $result;
        }
        foreach (scandir($base) as $item) {
            $path = $base . DIRECTORY_SEPARATOR . $item;
            if (strpos($item, $prefix) === 0 && is_dir($path) && !is_link($path)) {
                $result[] = $path;
            }
        }
        return $result;
    }
}
