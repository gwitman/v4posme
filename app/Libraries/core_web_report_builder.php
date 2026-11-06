<?php
//posme:2026-11-06
namespace App\Libraries;

/**
 * Generador de consultas del Constructor de Reportes (Builder Report).
 *
 * Reglas de seguridad:
 *  - El navegador nunca envia SQL. Solo envia una configuracion que se valida contra el catalogo
 *    de campos (tb_report_builder_view_field); en el SQL se usa el nombre del catalogo, nunca el
 *    texto recibido.
 *  - La conexion es la normal de db_connect(): la garantia de solo lectura la da este generador.
 *  - Todo lo que no se reconoce se rechaza (falla cerrado).
 *
 * La validacion y la composicion son puras (sin BD ni sesion) para poder probarlas sin BD.
 * El unico metodo que toca la BD es execute(), y recibe la conexion como parametro.
 */
class core_web_report_builder
{
    /** Topes de la configuracion. */
    const MAX_COLUMNS = 40;
    const MAX_ORDER_BY = 10;
    const MAX_FILTERS = 30;
    const MAX_GROUP_BY = 20;
    const MAX_LABEL_LENGTH = 150;
    const MAX_FILTER_TEXT_LENGTH = 500;

    /** Topes duros de los parametros de empresa: ningun parametro los puede superar. */
    const HARD_MAX_ROWS_SCREEN = 20000;
    const HARD_MAX_PAGE_SIZE = 500;
    const HARD_MAX_ROWS_EXPORT = 200000;
    const HARD_MAX_TIMEOUT_SECONDS = 300;

    /** Valores por defecto si el parametro de empresa es invalido. */
    const DEFAULT_ROWS_SCREEN = 5000;
    const DEFAULT_PAGE_SIZE = 100;
    const DEFAULT_ROWS_EXPORT = 50000;
    const DEFAULT_TIMEOUT_SECONDS = 30;

    /** Filas por lote al exportar. */
    const EXPORT_BATCH_SIZE = 2000;

    /** Maximo de caracteres de una celda de Excel (ver WorksheetManager de OpenSpout). */
    const MAX_EXCEL_CELL_LENGTH = 32767;

    /** Formatos de celda de Excel. */
    const EXCEL_FORMAT_NUMBER = '#,##0.00';
    const EXCEL_FORMAT_COUNT = '#,##0';
    const EXCEL_FORMAT_DATE = 'yyyy-mm-dd';
    const EXCEL_FORMAT_DATETIME = 'yyyy-mm-dd hh:mm:ss';

    /** Operadores permitidos segun el tipo de CATALOGO del campo. */
    private static $OPERATORS = [
        'text'   => ['eq', 'contains', 'starts'],
        'number' => ['eq', 'gt', 'lt', 'between'],
        'date'   => ['eq', 'between', 'from', 'to'],
    ];

    /** Funciones de agregacion; COUNT aplica a cualquier tipo y las demas solo a number. */
    private static $FUNCTIONS = ['SUM', 'COUNT', 'AVG', 'MIN', 'MAX'];

    /**
     * Palabras prohibidas en el SQL de una vista. Parte de core_web_ai_tools.php:1098-1106; se dejan
     * fuera las que son nombres de columna habituales (user, version, database) porque aqui el SQL
     * lo escribe el administrador del catalogo y no un usuario final.
     */
    private static $FORBIDDEN_WORDS = [
        'insert', 'update', 'delete', 'replace', 'drop', 'alter', 'create', 'truncate', 'rename',
        'grant', 'revoke', 'call', 'exec', 'execute', 'prepare', 'deallocate', 'set', 'into',
        'load', 'load_file', 'outfile', 'dumpfile', 'sleep', 'benchmark', 'get_lock', 'release_lock',
        'is_free_lock', 'lock', 'unlock', 'handler', 'procedure', 'analyse', 'kill', 'shutdown', 'flush',
        'information_schema', 'performance_schema', 'mysql', 'sys',
    ];

    public function __construct()
    {
    }

    /* =================================================================================
       SQL BASE DE LA VISTA
    ================================================================================= */

    /**
     * Valida el SQL de la vista y sustituye {companyID} y {filterPermission}.
     *
     * @param string $sqlScript  sqlScript de tb_company_dataview.
     * @param int    $companyID  Empresa de la sesion.
     * @param int    $userID     Usuario de la sesion.
     * @param int    $branchID   Sucursal de la sesion.
     * @param mixed  $permission Valor de urlPermissionCmd; cualquier valor desconocido lanza excepcion.
     * @return string SQL listo para envolverse en buildSelect().
     * @throws \Exception Si la vista no cumple el contrato o el permiso no es valido.
     */
    function resolveBaseSql($sqlScript, $companyID, $userID, $branchID, $permission)
    {
        $this->validateBaseSql($sqlScript);

        // Se busca en el texto enmascarado: un {filterPermission} dentro de un comentario o literal
        // no filtraria nada y dejaria la vista sin restriccion. Lo mismo dentro de un identificador
        // con comilla invertida, que solo cambiaria el nombre de una columna.
        $masked = $this->maskSql($sqlScript, true);
        if (strpos($masked, '{companyID}') === false || strpos($masked, '{filterPermission}') === false) {
            throw new \Exception('La vista de reporte debe incluir {companyID} y {filterPermission}.');
        }

        $companyID = (int) $companyID;
        if ($companyID <= 0) {
            throw new \Exception('La empresa de la sesion no es valida.');
        }

        $filter = $this->buildPermissionFilter($permission, $userID, $branchID);
        $sql    = str_replace(['{companyID}', '{filterPermission}'], [(string) $companyID, $filter], $sqlScript);

        if (preg_match('/\{\w+\}/', $sql)) {
            throw new \Exception('La vista de reporte tiene marcadores sin resolver.');
        }

        return $sql;
    }

    /**
     * Traduce el permiso del usuario al filtro de la vista. Falla cerrado.
     *
     * @param mixed $permission Valor de urlPermissionCmd.
     * @param int   $userID     Usuario de la sesion.
     * @param int   $branchID   Sucursal de la sesion.
     * @return string Filtro SQL (vacio si ve todo).
     * @throws \Exception Si el permiso no es uno de los conocidos.
     */
    private function buildPermissionFilter($permission, $userID, $branchID)
    {
        $value = (is_int($permission) || is_string($permission)) ? (string) $permission : null;

        if ($value === PERMISSION_ALL) {
            return '';
        }
        if ($value === PERMISSION_BRANCH) {
            return ' AND x.createdAt = ' . (int) $branchID;
        }
        if ($value === PERMISSION_ME) {
            return ' AND x.createdBy = ' . (int) $userID;
        }

        throw new \Exception(NOT_ACCESS_FUNCTION);
    }

    /**
     * Valida el contrato del SQL de una vista: un solo SELECT sin escritura ni LIMIT/ORDER BY exteriores.
     *
     * Binds: el generador siempre usa binds nominales, y CI4 los sustituye con strtr sobre TODO el SQL
     * (system/Database/Query.php::matchNamedBinds). Por eso solo rompe un texto con la forma exacta
     * ":rb_xxx:", incluso dentro de un literal. Un "?" o "10:30:00" dentro de un literal no se toca:
     * matchSimpleBinds solo corre con binds posicionales, que este generador no usa.
     *
     * @param string $sql SQL de la vista, con sus marcadores.
     * @return void
     * @throws \Exception Si no cumple el contrato.
     */
    function validateBaseSql($sql)
    {
        if (!is_string($sql) || trim($sql) === '') {
            throw new \Exception('La vista de reporte no tiene SQL.');
        }
        if (preg_match('/:rb_\w*:/i', $sql)) {
            throw new \Exception('La vista de reporte contiene un texto reservado para los parametros del generador.');
        }
        // Un caracter de control puede hacer que este validador y el servidor lean distinto dónde
        // termina un comentario o un literal. Solo se admiten tab, LF y CR.
        if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $sql)) {
            throw new \Exception('La vista de reporte contiene un caracter de control no permitido.');
        }

        $masked = $this->maskSql($sql);

        if (!preg_match('/^\s*SELECT\b/i', $masked)) {
            throw new \Exception('La vista de reporte debe empezar por SELECT.');
        }
        foreach ([';', '@', ':', '?', '\\'] as $token) {
            if (strpos($masked, $token) !== false) {
                throw new \Exception('La vista de reporte contiene un caracter no permitido: ' . $token);
            }
        }
        if (preg_match('/\b(' . implode('|', self::$FORBIDDEN_WORDS) . ')\b/i', $masked, $match)) {
            throw new \Exception('La vista de reporte contiene una palabra no permitida: ' . strtoupper($match[1]));
        }

        $levelZero = $this->extractLevelZero($masked);
        if (preg_match('/\bORDER\s+BY\b/i', $levelZero) || preg_match('/\bLIMIT\b/i', $levelZero)) {
            throw new \Exception('La vista de reporte no debe tener ORDER BY ni LIMIT exteriores.');
        }
    }

    /**
     * Sustituye por espacios el contenido de literales y comentarios, conservando la longitud.
     * El contenido de identificadores con comilla invertida NO se enmascara: una palabra prohibida
     * ahi tambien se rechaza (MySQL admite `sleep`(1)).
     *
     * @param string $sql               SQL original.
     * @param bool   $maskIdentifiers   true enmascara tambien esos identificadores (para buscar marcadores).
     * @return string SQL enmascarado.
     * @throws \Exception Si hay literales o comentarios sin cerrar, o comentarios ejecutables.
     */
    private function maskSql($sql, $maskIdentifiers = false)
    {
        $length = strlen($sql);
        $output = '';
        $i      = 0;

        while ($i < $length) {
            $char = $sql[$i];
            $next = ($i + 1 < $length) ? $sql[$i + 1] : '';

            // Literales: admite comilla doblada y barra invertida
            if ($char === "'" || $char === '"') {
                $end = $this->findLiteralEnd($sql, $i, $length, $char);
                $output .= $char . str_repeat(' ', $end - $i - 1) . $char;
                $i = $end + 1;
                continue;
            }

            // Identificador con comilla invertida: se copia tal cual para seguir revisandolo
            if ($char === '`') {
                $end = strpos($sql, '`', $i + 1);
                if ($end === false) {
                    throw new \Exception('La vista de reporte tiene un identificador sin cerrar.');
                }
                $output .= $maskIdentifiers ? '`' . str_repeat(' ', $end - $i - 1) . '`' : substr($sql, $i, $end - $i + 1);
                $i = $end + 1;
                continue;
            }

            // Comentario de bloque. Los /*! */, /*M! */ se ejecutan y /*+ */ altera la ejecucion
            if ($char === '/' && $next === '*') {
                $start = substr($sql, $i + 2, 2);
                if ($start !== '' && ($start[0] === '!' || $start[0] === '+' || strtoupper($start) === 'M!')) {
                    throw new \Exception('La vista de reporte contiene un comentario ejecutable.');
                }
                $end = strpos($sql, '*/', $i + 2);
                if ($end === false) {
                    throw new \Exception('La vista de reporte tiene un comentario sin cerrar.');
                }
                $output .= str_repeat(' ', $end + 2 - $i);
                $i = $end + 2;
                continue;
            }

            // Comentario de linea. "--" solo lo es si le sigue espacio o caracter de control (regla de MySQL)
            $isDashComment = ($char === '-' && $next === '-' && ($i + 2 >= $length || ord($sql[$i + 2]) <= 0x20 || ord($sql[$i + 2]) === 0x7F));
            if ($char === '#' || $isDashComment) {
                $size = strcspn($sql, "\n", $i);
                $output .= str_repeat(' ', $size);
                $i += $size;
                continue;
            }

            $output .= $char;
            $i++;
        }

        return $output;
    }

    /**
     * Ubica la comilla que cierra un literal.
     *
     * @param string $sql    SQL original.
     * @param int    $start  Posicion de la comilla de apertura.
     * @param int    $length Longitud del SQL.
     * @param string $quote  Comilla usada.
     * @return int Posicion de la comilla de cierre.
     * @throws \Exception Si el literal no se cierra.
     */
    private function findLiteralEnd($sql, $start, $length, $quote)
    {
        $j = $start + 1;
        while ($j < $length) {
            $char = $sql[$j];
            if ($char === '\\') {
                $j += 2;
                continue;
            }
            if ($char === $quote) {
                if ($j + 1 < $length && $sql[$j + 1] === $quote) {
                    $j += 2;
                    continue;
                }
                return $j;
            }
            $j++;
        }

        throw new \Exception('La vista de reporte tiene un literal sin cerrar.');
    }

    /**
     * Devuelve solo el texto fuera de parentesis.
     *
     * @param string $masked SQL enmascarado.
     * @return string Texto de nivel 0.
     * @throws \Exception Si los parentesis no estan balanceados.
     */
    private function extractLevelZero($masked)
    {
        $depth  = 0;
        $output = '';
        $length = strlen($masked);

        for ($i = 0; $i < $length; $i++) {
            $char = $masked[$i];
            if ($char === '(') {
                $depth++;
                $output .= ' ';
            } elseif ($char === ')') {
                $depth--;
                if ($depth < 0) {
                    throw new \Exception('La vista de reporte tiene parentesis sin balancear.');
                }
                $output .= ' ';
            } elseif ($depth === 0) {
                $output .= $char;
            }
        }

        if ($depth !== 0) {
            throw new \Exception('La vista de reporte tiene parentesis sin balancear.');
        }

        return $output;
    }

    /* =================================================================================
       CATALOGO
    ================================================================================= */

    /**
     * Quita los campos de costo si el usuario no puede verlos. Regla de core_web_ai_tools:
     * puedeVerCostos() (:136-150) decide quien, y quitarColumnasCosto() (:1189) marca por nombre
     * costo/utilidad. Aqui se suma ademas la marca isCost del catalogo.
     *
     * @param array $fields      Filas del catalogo (objetos o arreglos).
     * @param bool  $canSeeCosts Resultado de puedeVerCostos().
     * @return array Filas permitidas.
     */
    function stripCostFields(array $fields, $canSeeCosts)
    {
        if ($canSeeCosts === true) {
            return array_values($fields);
        }

        $result = [];
        foreach ($fields as $field) {
            $row  = (array) $field;
            $name = isset($row['fieldName']) && is_string($row['fieldName']) ? $row['fieldName'] : '';
            if (!empty($row['isCost']) || preg_match('/costo|utilidad/i', $name)) {
                continue;
            }
            $result[] = $field;
        }

        return $result;
    }

    /**
     * Columnas que la pantalla puede ofrecer (sin companyID).
     *
     * @param array $fields Filas del catalogo ya sin costos si no proceden.
     * @return array Lista de [name, label, type, align].
     */
    function getCatalogColumns(array $fields)
    {
        $result = [];
        foreach ($this->indexCatalog($fields) as $entry) {
            $result[] = [
                'name'  => $entry['name'],
                'label' => $entry['label'],
                'type'  => $entry['type'],
                'align' => $entry['align'],
            ];
        }
        return $result;
    }

    /**
     * Operadores por tipo.
     *
     * @return array tipo => operadores
     */
    function getOperators()
    {
        return self::$OPERATORS;
    }

    /**
     * Funciones de agregacion.
     *
     * @return array Lista de funciones.
     */
    function getFunctions()
    {
        return self::$FUNCTIONS;
    }

    /**
     * Comprueba si el usuario tiene el menu que exige la vista. Compara igual que urlPermited:
     * sin .aspx y sin distinguir mayusculas. Sin direccion configurada, no da acceso.
     *
     * @param mixed $requiredAddress requiredAddress de la vista.
     * @param array $addresses       Resultado de core_web_ai_tools::getAddressesSesion().
     * @return bool
     */
    function hasRequiredAddress($requiredAddress, array $addresses)
    {
        if (!is_string($requiredAddress) || trim($requiredAddress) === '') {
            return false;
        }

        return isset($addresses[strtoupper(str_replace('.aspx', '', trim($requiredAddress)))]);
    }

    /**
     * Indexa el catalogo por nombre exacto. companyID nunca es seleccionable, y las filas con
     * nombre o tipo invalido se ignoran para que no lleguen al SQL.
     *
     * @param array $fields Filas del catalogo (objetos o arreglos).
     * @return array nombre => [name, label, type, align]
     */
    private function indexCatalog(array $fields)
    {
        $catalog = [];
        foreach ($fields as $field) {
            $row  = (array) $field;
            $name = $row['fieldName'] ?? null;
            $type = $row['dataType'] ?? null;

            if (!is_string($name) || !$this->isIdentifier($name) || strtolower($name) === 'companyid') {
                continue;
            }
            if (!is_string($type) || !isset(self::$OPERATORS[$type])) {
                continue;
            }

            $label = isset($row['label']) && is_string($row['label']) && $row['label'] !== '' ? $row['label'] : $name;
            $align = $row['alignDefault'] ?? 'left';

            $catalog[$name] = [
                'name'  => $name,
                'label' => $label,
                'type'  => $type,
                'align' => ($align === 'right') ? 'right' : 'left',
            ];
        }
        return $catalog;
    }

    /**
     * Indica si el texto es un identificador simple.
     *
     * @param string $name Nombre a revisar.
     * @return bool
     */
    private function isIdentifier($name)
    {
        return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $name) === 1;
    }

    /* =================================================================================
       VALIDACION DE LA CONFIGURACION
    ================================================================================= */

    /**
     * Valida y normaliza la configuracion recibida.
     *
     * Los filtros normalizados incluyen dataType (tipo de catalogo), que buildSelect() necesita para
     * distinguir un "eq" de fecha de uno de texto. El valor recibido del cliente se ignora.
     *
     * @param array $config Configuracion del cliente.
     * @param array $fields Filas del catalogo ya sin costos si no proceden.
     * @param array $limits Opcional: maxColumns, maxOrderBy, maxFilters, maxGroupBy (no superan los topes de la clase)
     *                      y keepEmptyFilters = true para conservar los filtros sin valor. Una consulta recurrente
     *                      guarda sus filtros aunque esten vacios, porque el valor se indica al usarla (saveQuery y
     *                      loadQuery). Para generar y exportar no se usa: el vacio se omite y un between con un solo
     *                      extremo es error. buildSelect() rechaza un filtro sin valor.
     * @return array Configuracion normalizada.
     * @throws \Exception Si algo no es valido.
     */
    function validateConfig(array $config, array $fields, array $limits)
    {
        $catalog = $this->indexCatalog($fields);

        if (isset($config['version']) && $config['version'] !== 1 && $config['version'] !== '1') {
            throw new \Exception('La version de la configuracion no es soportada.');
        }

        $dataViewID = $config['dataViewID'] ?? null;
        if (!(is_int($dataViewID) || (is_string($dataViewID) && ctype_digit($dataViewID))) || (int) $dataViewID <= 0) {
            throw new \Exception('Debe indicar una vista valida.');
        }

        $columns = $this->validateColumns($config['columns'] ?? null, $catalog, $this->resolveLimit($limits, 'maxColumns', self::MAX_COLUMNS));
        $groupBy = $this->validateGroupBy($config['groupBy'] ?? null, $catalog, $this->resolveLimit($limits, 'maxGroupBy', self::MAX_GROUP_BY));
        $orderBy = $this->validateOrderBy($config['orderBy'] ?? null, $catalog, $this->resolveLimit($limits, 'maxOrderBy', self::MAX_ORDER_BY));
        $filters = $this->validateFilters($config['filters'] ?? null, $catalog, $this->resolveLimit($limits, 'maxFilters', self::MAX_FILTERS), ($limits['keepEmptyFilters'] ?? false) === true);

        $this->validateAggregation($columns, $groupBy, $orderBy);

        return [
            'version'    => 1,
            'dataViewID' => (int) $dataViewID,
            'columns'    => $columns,
            'groupBy'    => $groupBy,
            'orderBy'    => $orderBy,
            'filters'    => $filters,
        ];
    }

    /**
     * Quita de una consulta guardada los campos que ya no estan en el catalogo. No falla por eso:
     * lo informa en warnings. Si la consulta queda invalida tambien se informa, y el usuario la corrige.
     *
     * @param array $config Configuracion guardada.
     * @param array $fields Filas del catalogo ya sin costos si no proceden.
     * @return array [config, warnings]
     */
    function reconcileConfig(array $config, array $fields)
    {
        $catalog  = $this->indexCatalog($fields);
        $missing  = [];
        $warnings = [];

        $isKnown = function ($name) use ($catalog, &$missing) {
            if (is_string($name) && isset($catalog[$name])) {
                return true;
            }
            $missing[is_string($name) ? $name : '(sin nombre)'] = true;
            return false;
        };

        // Una seccion guardada con forma inesperada se trata como vacia: abrir la consulta no debe fallar
        foreach (['columns', 'groupBy', 'orderBy', 'filters'] as $section) {
            if (isset($config[$section]) && !is_array($config[$section])) {
                $config[$section] = [];
                $warnings[]       = 'La seccion ' . $section . ' de la consulta guardada no es valida y se descarto.';
            }
        }

        $columns = [];
        foreach ($this->toList($config['columns'] ?? null) as $column) {
            if (is_array($column) && $isKnown($column['name'] ?? null)) {
                $columns[] = $column;
            }
        }

        $groupBy = [];
        foreach ($this->toList($config['groupBy'] ?? null) as $name) {
            if ($isKnown($name)) {
                $groupBy[] = $name;
            }
        }

        $orderBy = [];
        foreach ($this->toList($config['orderBy'] ?? null) as $order) {
            if (is_array($order) && $isKnown($order['column'] ?? null)) {
                $orderBy[] = $order;
            }
        }

        $filters = [];
        foreach ($this->toList($config['filters'] ?? null) as $filter) {
            if (is_array($filter) && $isKnown($filter['column'] ?? null)) {
                $filters[] = $filter;
            }
        }

        foreach (array_keys($missing) as $name) {
            $warnings[] = 'El campo ' . $name . ' ya no esta disponible y se quito de la consulta.';
        }

        $config['columns'] = $columns;
        $config['groupBy'] = $groupBy;
        $config['orderBy'] = $orderBy;
        $config['filters'] = $filters;

        try {
            $config = $this->validateConfig($config, $fields, ['keepEmptyFilters' => true]);
        } catch (\Exception $ex) {
            $warnings[] = 'La consulta necesita ajustes: ' . $ex->getMessage();
        }

        return ['config' => $config, 'warnings' => $warnings];
    }

    /**
     * Resuelve un limite de la configuracion sin superar el tope de la clase.
     *
     * @param array  $limits Limites recibidos.
     * @param string $key    Clave.
     * @param int    $max    Tope.
     * @return int
     */
    private function resolveLimit(array $limits, $key, $max)
    {
        return $this->normalizeLimit($limits[$key] ?? null, $max, $max);
    }

    /**
     * Convierte a lista; null equivale a lista vacia.
     *
     * @param mixed $value Valor recibido.
     * @return array
     * @throws \Exception Si no es arreglo ni null.
     */
    private function toList($value)
    {
        if ($value === null) {
            return [];
        }
        if (!is_array($value)) {
            throw new \Exception('La configuracion del reporte tiene un formato invalido.');
        }
        return array_values($value);
    }

    /**
     * Lee una propiedad opcional que, si viene, debe ser texto.
     *
     * @param array  $entry    Elemento de la configuracion.
     * @param string $key      Propiedad.
     * @param string $default  Valor si no viene.
     * @return string
     * @throws \Exception Si no es texto.
     */
    private function optionalString(array $entry, $key, $default)
    {
        $value = $entry[$key] ?? null;
        if ($value === null) {
            return $default;
        }
        if (!is_string($value)) {
            throw new \Exception('La propiedad ' . $key . ' de la configuracion debe ser texto.');
        }
        return $value;
    }

    /**
     * Obtiene el campo de catalogo por coincidencia exacta.
     *
     * @param mixed $name    Nombre recibido.
     * @param array $catalog Catalogo indexado.
     * @return array Entrada del catalogo.
     * @throws \Exception Si no existe.
     */
    private function requireField($name, array $catalog)
    {
        if (!is_string($name) || !isset($catalog[$name])) {
            throw new \Exception('La configuracion usa un campo que no esta disponible.');
        }
        return $catalog[$name];
    }

    /**
     * Valida una funcion de agregacion contra el tipo de catalogo.
     *
     * @param array $entry Elemento con la propiedad fn.
     * @param array $field Entrada del catalogo.
     * @return string|null Funcion o null.
     * @throws \Exception Si la funcion no aplica.
     */
    private function validateFunction(array $entry, array $field)
    {
        $fn = $this->optionalString($entry, 'fn', '');
        if ($fn === '') {
            return null;
        }
        if (!in_array($fn, self::$FUNCTIONS, true)) {
            throw new \Exception('La funcion de agregacion no es valida.');
        }
        if ($fn !== 'COUNT' && $field['type'] !== 'number') {
            throw new \Exception('La funcion ' . $fn . ' solo aplica a campos numericos.');
        }
        return $fn;
    }

    /**
     * Valida las columnas a mostrar.
     *
     * @param mixed $value   Valor recibido.
     * @param array $catalog Catalogo indexado.
     * @param int   $max     Maximo de columnas.
     * @return array Columnas normalizadas.
     * @throws \Exception Si algo no es valido.
     */
    private function validateColumns($value, array $catalog, $max)
    {
        $list = $this->toList($value);
        if (count($list) < 1) {
            throw new \Exception('Debe seleccionar al menos una columna.');
        }
        if (count($list) > $max) {
            throw new \Exception('No puede seleccionar mas de ' . $max . ' columnas.');
        }

        $result = [];
        foreach ($list as $column) {
            if (!is_array($column)) {
                throw new \Exception('La configuracion del reporte tiene un formato invalido.');
            }
            $field = $this->requireField($column['name'] ?? null, $catalog);
            $fn    = $this->validateFunction($column, $field);

            // type y align solo afectan la presentacion. Con fn el resultado siempre es numero
            $type = $this->optionalString($column, 'type', $field['type']);
            if (!isset(self::$OPERATORS[$type])) {
                throw new \Exception('El tipo de la columna no es valido.');
            }
            $defaultAlign = ($fn !== null) ? 'right' : $field['align'];
            $align        = $this->optionalString($column, 'align', $defaultAlign);
            if ($align !== 'left' && $align !== 'right') {
                throw new \Exception('La alineacion de la columna no es valida.');
            }
            $label = trim($this->optionalString($column, 'label', ''));
            $label = ($label === '') ? $field['label'] : mb_substr($label, 0, self::MAX_LABEL_LENGTH, 'UTF-8');

            $result[] = [
                'name'  => $field['name'],
                'label' => $label,
                'type'  => ($fn !== null) ? 'number' : $type,
                'align' => $align,
                'fn'    => $fn,
            ];
        }
        return $result;
    }

    /**
     * Valida los campos de agrupacion.
     *
     * @param mixed $value   Valor recibido.
     * @param array $catalog Catalogo indexado.
     * @param int   $max     Maximo de campos.
     * @return array Nombres de catalogo sin repetir.
     * @throws \Exception Si algo no es valido.
     */
    private function validateGroupBy($value, array $catalog, $max)
    {
        $list = $this->toList($value);
        if (count($list) > $max) {
            throw new \Exception('No puede agrupar por mas de ' . $max . ' campos.');
        }

        $result = [];
        foreach ($list as $name) {
            $field = $this->requireField($name, $catalog);
            if (!in_array($field['name'], $result, true)) {
                $result[] = $field['name'];
            }
        }
        return $result;
    }

    /**
     * Valida el orden.
     *
     * @param mixed $value   Valor recibido.
     * @param array $catalog Catalogo indexado.
     * @param int   $max     Maximo de criterios.
     * @return array Criterios normalizados.
     * @throws \Exception Si algo no es valido.
     */
    private function validateOrderBy($value, array $catalog, $max)
    {
        $list = $this->toList($value);
        if (count($list) > $max) {
            throw new \Exception('No puede ordenar por mas de ' . $max . ' campos.');
        }

        $result = [];
        foreach ($list as $order) {
            if (!is_array($order)) {
                throw new \Exception('La configuracion del reporte tiene un formato invalido.');
            }
            $field = $this->requireField($order['column'] ?? null, $catalog);
            $dir   = strtoupper($this->optionalString($order, 'dir', 'ASC'));
            if ($dir !== 'ASC' && $dir !== 'DESC') {
                throw new \Exception('La direccion del orden no es valida.');
            }
            $result[] = [
                'column' => $field['name'],
                'fn'     => $this->validateFunction($order, $field),
                'dir'    => $dir,
            ];
        }
        return $result;
    }

    /**
     * Valida los filtros. El operador y el valor se comparan contra el tipo de CATALOGO.
     *
     * @param mixed $value   Valor recibido.
     * @param array $catalog Catalogo indexado.
     * @param int   $max     Maximo de filtros.
     * @param bool  $keepEmpty true conserva los filtros sin valor (consulta guardada); false los omite.
     * @return array Filtros normalizados.
     * @throws \Exception Si algo no es valido.
     */
    private function validateFilters($value, array $catalog, $max, $keepEmpty = false)
    {
        $list = $this->toList($value);
        if (count($list) > $max) {
            throw new \Exception('No puede usar mas de ' . $max . ' filtros.');
        }

        $result = [];
        foreach ($list as $filter) {
            if (!is_array($filter)) {
                throw new \Exception('La configuracion del reporte tiene un formato invalido.');
            }
            $field    = $this->requireField($filter['column'] ?? null, $catalog);
            $operator = $filter['operator'] ?? null;
            if (!is_string($operator) || !in_array($operator, self::$OPERATORS[$field['type']], true)) {
                throw new \Exception('El operador del filtro no es valido para el campo ' . $field['label'] . '.');
            }

            $value1  = $this->readFilterValue($filter['value'] ?? null);
            $value2  = $this->readFilterValue($filter['value2'] ?? null);
            $normal  = [
                'column'   => $field['name'],
                'operator' => $operator,
                'dataType' => $field['type'],
            ];

            if ($operator === 'between') {
                if ($value1 === null || $value2 === null) {
                    if ($keepEmpty) {
                        // Lo que si viene se valida igual; el extremo que falte se pide al usar la consulta
                        $normal['value']  = ($value1 === null) ? null : $this->normalizeFilterValue($value1, $field);
                        $normal['value2'] = ($value2 === null) ? null : $this->normalizeFilterValue($value2, $field);
                        $result[]         = $normal;
                        continue;
                    }
                    if ($value1 === null && $value2 === null) {
                        continue;
                    }
                    throw new \Exception('El filtro entre del campo ' . $field['label'] . ' necesita los dos extremos.');
                }
                $value1 = $this->normalizeFilterValue($value1, $field);
                $value2 = $this->normalizeFilterValue($value2, $field);
                $isInverted = ($field['type'] === 'number') ? ((float) $value1 > (float) $value2) : ($value1 > $value2);
                if ($isInverted) {
                    throw new \Exception('Los extremos del filtro del campo ' . $field['label'] . ' estan invertidos.');
                }
                $normal['value']  = $value1;
                $normal['value2'] = $value2;
                $result[]         = $normal;
                continue;
            }

            if ($value1 === null) {
                if ($keepEmpty) {
                    $normal['value'] = null;
                    $result[]        = $normal;
                }
                continue;
            }
            $normal['value'] = $this->normalizeFilterValue($value1, $field);
            $result[]        = $normal;
        }
        return $result;
    }

    /**
     * Lee el valor de un filtro como texto. null y '' se tratan como vacio; 0 es un valor valido.
     *
     * @param mixed $value Valor recibido.
     * @return string|null
     * @throws \Exception Si es arreglo, objeto o booleano.
     */
    private function readFilterValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_float($value) && !is_finite($value)) {
            throw new \Exception('El valor de un filtro no es valido.');
        }
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new \Exception('El valor de un filtro no es valido.');
        }
        return (string) $value;
    }

    /**
     * Normaliza el valor de un filtro segun el tipo de catalogo.
     *
     * @param string $value Valor en texto.
     * @param array  $field Entrada del catalogo.
     * @return string Valor listo para el bind.
     * @throws \Exception Si no corresponde al tipo.
     */
    private function normalizeFilterValue($value, array $field)
    {
        if ($field['type'] === 'number') {
            $number = trim($value);
            if (!is_numeric($number) || !is_finite((float) $number)) {
                throw new \Exception('El filtro del campo ' . $field['label'] . ' debe ser un numero sin comas.');
            }
            return $number;
        }

        if ($field['type'] === 'date') {
            return $this->normalizeDate($value, $field['label']);
        }

        if (mb_strlen($value, 'UTF-8') > self::MAX_FILTER_TEXT_LENGTH) {
            throw new \Exception('El filtro del campo ' . $field['label'] . ' es demasiado largo.');
        }
        return $value;
    }

    /**
     * Valida una fecha Y-m-d real. Se limita el año para que el dia siguiente siga siendo una
     * fecha valida de MySQL.
     *
     * @param string $value Fecha.
     * @param string $label Titulo del campo para el mensaje.
     * @return string Fecha validada.
     * @throws \Exception Si no es una fecha real.
     */
    private function normalizeDate($value, $label)
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/D', $value, $parts)
            && (int) $parts[1] >= 1000
            && (int) $parts[1] <= 9998
            && checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1])) {
            return $value;
        }

        throw new \Exception('El filtro del campo ' . $label . ' debe ser una fecha valida con formato AAAA-MM-DD.');
    }

    /**
     * Reglas de agrupacion: con groupBy o alguna funcion, toda columna sin funcion debe estar agrupada
     * y el orden debe coincidir con una columna seleccionada o con un campo agrupado. Un orden con
     * funcion debe coincidir siempre con una columna que tenga esa funcion.
     *
     * @param array $columns Columnas normalizadas.
     * @param array $groupBy Campos agrupados.
     * @param array $orderBy Orden normalizado.
     * @return void
     * @throws \Exception Si no se cumplen.
     */
    private function validateAggregation(array $columns, array $groupBy, array $orderBy)
    {
        $selected       = [];
        $hasAggregation = count($groupBy) > 0;

        foreach ($columns as $column) {
            $selected[$column['name'] . '|' . (string) $column['fn']] = true;
            if ($column['fn'] !== null) {
                $hasAggregation = true;
            }
        }

        if ($hasAggregation) {
            foreach ($columns as $column) {
                if ($column['fn'] === null && !in_array($column['name'], $groupBy, true)) {
                    throw new \Exception('La columna ' . $column['label'] . ' debe estar agrupada o tener una funcion.');
                }
            }
        }

        foreach ($orderBy as $order) {
            $isSelected = isset($selected[$order['column'] . '|' . (string) $order['fn']]);
            $isGrouped  = ($order['fn'] === null && in_array($order['column'], $groupBy, true));

            if ($order['fn'] !== null && !$isSelected) {
                throw new \Exception('El orden con funcion debe coincidir con una columna seleccionada.');
            }
            if ($hasAggregation && !$isSelected && !$isGrouped) {
                throw new \Exception('El orden debe coincidir con una columna seleccionada o con un campo agrupado.');
            }
        }
    }

    /* =================================================================================
       COMPOSICION DE SQL
    ================================================================================= */

    /**
     * Compone el SELECT paginable. La configuracion debe venir de validateConfig().
     *
     * @param string   $baseSql   SQL de resolveBaseSql().
     * @param array    $config    Configuracion normalizada.
     * @param int      $companyID Empresa de la sesion.
     * @param int|null $limit     Maximo de filas; null sin LIMIT.
     * @param int|null $offset    Filas a saltar; se ignora sin $limit.
     * @return array [sql, binds, columns]
     * @throws \Exception Si la configuracion no esta normalizada.
     */
    function buildSelect($baseSql, array $config, $companyID, $limit = null, $offset = null)
    {
        $query = $this->composeQuery($baseSql, $config, $companyID);
        $sql   = $query['sql'] . ' ORDER BY ' . implode(', ', $query['order']);

        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(0, (int) $limit);
            if ($offset !== null) {
                $sql .= ' OFFSET ' . max(0, (int) $offset);
            }
        }

        return ['sql' => $sql, 'binds' => $query['binds'], 'columns' => $query['columns']];
    }

    /**
     * Compone el conteo con tope: MySQL deja de contar al pasar $cap.
     *
     * @param string $baseSql   SQL de resolveBaseSql().
     * @param array  $config    Configuracion normalizada.
     * @param int    $companyID Empresa de la sesion.
     * @param int    $cap       Tope del conteo.
     * @return array [sql, binds]
     * @throws \Exception Si la configuracion no esta normalizada.
     */
    function buildCount($baseSql, array $config, $companyID, $cap)
    {
        $query = $this->composeQuery($baseSql, $config, $companyID);
        $limit = max(1, (int) $cap) + 1;

        return [
            'sql'   => 'SELECT COUNT(*) AS total FROM (SELECT 1 FROM (' . $query['sql'] . ') rb_q LIMIT ' . $limit . ') rb_c',
            'binds' => $query['binds'],
        ];
    }

    /**
     * Arma las partes comunes del SELECT.
     *
     * @param string $baseSql   SQL base.
     * @param array  $config    Configuracion normalizada.
     * @param int    $companyID Empresa de la sesion.
     * @return array [sql, binds, columns, order]
     * @throws \Exception Si la configuracion no esta normalizada.
     */
    private function composeQuery($baseSql, array $config, $companyID)
    {
        if (!is_string($baseSql) || trim($baseSql) === '') {
            throw new \Exception('La vista de reporte no tiene SQL.');
        }
        if ((int) $companyID <= 0) {
            throw new \Exception('La empresa de la sesion no es valida.');
        }

        $select  = [];
        $columns = [];
        foreach ($this->toList($config['columns'] ?? null) as $i => $column) {
            $key       = 'c' . $i;
            $fn        = $column['fn'] ?? null;
            $select[]  = $this->buildExpression($column['name'] ?? null, $fn) . ' AS ' . $key;
            $columns[] = [
                'key'   => $key,
                'name'  => $column['name'],
                'label' => $column['label'] ?? $column['name'],
                'type'  => $column['type'] ?? 'text',
                'align' => $column['align'] ?? 'left',
                'fn'    => $fn,
            ];
        }
        if (count($select) === 0) {
            throw new \Exception('Debe seleccionar al menos una columna.');
        }

        $binds = ['rb_c' => (int) $companyID];
        $where = [$this->quoteColumn('companyID') . ' = :rb_c:'];
        foreach ($this->toList($config['filters'] ?? null) as $filter) {
            $where[] = $this->buildFilterClause($filter, $binds);
        }

        // El SQL base va entre saltos de linea: si termina en un comentario "-- x", no se come el parentesis
        $sql = 'SELECT ' . implode(', ', $select)
            . " FROM (\n" . $baseSql . "\n) AS rb_base"
            . ' WHERE ' . implode(' AND ', $where);

        $groups = [];
        foreach ($this->toList($config['groupBy'] ?? null) as $name) {
            $groups[] = $this->quoteColumn($name);
        }
        if (count($groups) > 0) {
            $sql .= ' GROUP BY ' . implode(', ', $groups);
        }

        return [
            'sql'     => $sql,
            'binds'   => $binds,
            'columns' => $columns,
            'order'   => $this->buildOrder($this->toList($config['orderBy'] ?? null), $columns),
        ];
    }

    /**
     * Arma el ORDER BY con el orden pedido y, como desempate, todas las columnas seleccionadas por alias.
     * Asi la paginacion con LIMIT/OFFSET es estable.
     *
     * @param array $orderBy Orden normalizado.
     * @param array $columns Columnas con su alias.
     * @return array Partes del ORDER BY.
     * @throws \Exception Si el orden no esta normalizado.
     */
    private function buildOrder(array $orderBy, array $columns)
    {
        $parts = [];
        $used  = [];

        foreach ($orderBy as $order) {
            $name = $order['column'] ?? null;
            $fn   = $order['fn'] ?? null;
            $dir  = $order['dir'] ?? 'ASC';
            if ($dir !== 'ASC' && $dir !== 'DESC') {
                throw new \Exception('La direccion del orden no es valida.');
            }

            $alias = null;
            foreach ($columns as $column) {
                if ($column['name'] === $name && $column['fn'] === $fn) {
                    $alias = $column['key'];
                    break;
                }
            }

            if ($alias !== null) {
                $used[$alias] = true;
                $parts[]      = $alias . ' ' . $dir;
                continue;
            }
            if ($fn !== null) {
                throw new \Exception('El orden con funcion debe coincidir con una columna seleccionada.');
            }
            $parts[] = $this->quoteColumn($name) . ' ' . $dir;
        }

        foreach ($columns as $column) {
            if (!isset($used[$column['key']])) {
                $parts[] = $column['key'] . ' ASC';
            }
        }

        return $parts;
    }

    /**
     * Expresion de una columna, con funcion si la tiene.
     *
     * @param mixed       $name Nombre de catalogo.
     * @param string|null $fn   Funcion.
     * @return string
     * @throws \Exception Si la funcion no es valida.
     */
    private function buildExpression($name, $fn)
    {
        $column = $this->quoteColumn($name);
        if ($fn === null) {
            return $column;
        }
        if (!in_array($fn, self::$FUNCTIONS, true)) {
            throw new \Exception('La funcion de agregacion no es valida.');
        }
        return $fn . '(' . $column . ')';
    }

    /**
     * Columna calificada con el alias de la vista.
     *
     * @param mixed $name Nombre de catalogo.
     * @return string
     * @throws \Exception Si no es un identificador simple.
     */
    private function quoteColumn($name)
    {
        if (!is_string($name) || !$this->isIdentifier($name)) {
            throw new \Exception('La configuracion usa un campo que no esta disponible.');
        }
        return 'rb_base.`' . $name . '`';
    }

    /**
     * Arma la condicion de un filtro normalizado y agrega sus binds.
     *
     * @param array $filter Filtro de validateConfig().
     * @param array $binds  Binds acumulados (por referencia).
     * @return string Condicion SQL.
     * @throws \Exception Si el filtro no esta normalizado.
     */
    private function buildFilterClause(array $filter, array &$binds)
    {
        $column   = $this->quoteColumn($filter['column'] ?? null);
        $operator = $filter['operator'] ?? '';
        $dataType = $filter['dataType'] ?? '';
        $value    = $filter['value'] ?? null;
        $value2   = $filter['value2'] ?? null;

        if ($dataType === 'date') {
            // Se compara por rango para no perder las filas con hora dentro del dia
            switch ($operator) {
                case 'eq':
                    return $column . ' >= ' . $this->addBind($binds, $value) . ' AND ' . $column . ' < ' . $this->addBind($binds, $this->nextDay($value));
                case 'between':
                    return $column . ' >= ' . $this->addBind($binds, $value) . ' AND ' . $column . ' < ' . $this->addBind($binds, $this->nextDay($value2));
                case 'from':
                    return $column . ' >= ' . $this->addBind($binds, $value);
                case 'to':
                    return $column . ' < ' . $this->addBind($binds, $this->nextDay($value));
            }
        }

        if ($dataType === 'number') {
            switch ($operator) {
                case 'eq':
                    return $column . ' = ' . $this->addBind($binds, $value);
                case 'gt':
                    return $column . ' > ' . $this->addBind($binds, $value);
                case 'lt':
                    return $column . ' < ' . $this->addBind($binds, $value);
                case 'between':
                    return $column . ' >= ' . $this->addBind($binds, $value) . ' AND ' . $column . ' <= ' . $this->addBind($binds, $value2);
            }
        }

        if ($dataType === 'text') {
            switch ($operator) {
                case 'eq':
                    return $column . ' = ' . $this->addBind($binds, $value);
                case 'contains':
                    return $column . ' LIKE ' . $this->addBind($binds, '%' . $this->escapeLike($value) . '%') . " ESCAPE '!'";
                case 'starts':
                    return $column . ' LIKE ' . $this->addBind($binds, $this->escapeLike($value) . '%') . " ESCAPE '!'";
            }
        }

        throw new \Exception('El filtro no es valido.');
    }

    /**
     * Registra un valor como bind nominal.
     *
     * @param array $binds Binds acumulados (por referencia).
     * @param mixed $value Valor; debe ser texto.
     * @return string Marcador del bind.
     * @throws \Exception Si el valor no es texto.
     */
    private function addBind(array &$binds, $value)
    {
        if (!is_string($value)) {
            throw new \Exception('El valor de un filtro no es valido.');
        }
        // rb_c ya ocupa una posicion: el primer parametro de filtro es rb_p0
        $name         = 'rb_p' . (count($binds) - 1);
        $binds[$name] = $value;
        return ':' . $name . ':';
    }

    /**
     * Escapa los comodines de LIKE con el caracter de ESCAPE '!'.
     *
     * @param mixed $value Texto.
     * @return string
     * @throws \Exception Si no hay valor: un LIKE '%%' devolveria todas las filas.
     */
    private function escapeLike($value)
    {
        if (!is_string($value) || $value === '') {
            throw new \Exception('El valor de un filtro no es valido.');
        }
        return str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $value);
    }

    /**
     * Dia siguiente a una fecha ya validada, en UTC para no depender de la zona horaria.
     *
     * @param mixed $date Fecha Y-m-d.
     * @return string
     * @throws \Exception Si no es una fecha valida.
     */
    private function nextDay($date)
    {
        $value = $this->normalizeDate((string) $date, 'fecha');
        $day   = new \DateTimeImmutable($value . ' 00:00:00', new \DateTimeZone('UTC'));
        return $day->modify('+1 day')->format('Y-m-d');
    }

    /* =================================================================================
       LIMITES
    ================================================================================= */

    /**
     * Normaliza un limite. Invalido, 0, negativo o texto dan el default; nunca "sin limite".
     *
     * @param mixed $value   Valor del parametro.
     * @param int   $default Valor por defecto.
     * @param int   $hardMax Tope duro.
     * @return int
     */
    function normalizeLimit($value, $default, $hardMax)
    {
        $number = 0;
        if (is_int($value)) {
            $number = $value;
        } elseif (is_string($value) && ctype_digit(trim($value))) {
            $number = (int) trim($value);
        }

        if ($number <= 0) {
            $number = (int) $default;
        }

        return min($number, (int) $hardMax);
    }

    /**
     * Normaliza los cuatro parametros de empresa contra sus topes.
     *
     * @param mixed $maxRowsScreen  REPORT_BUILDER_MAX_ROWS_SCREEN.
     * @param mixed $pageSizeMax    REPORT_BUILDER_PAGE_SIZE_MAX.
     * @param mixed $maxRowsExport  REPORT_BUILDER_MAX_ROWS_EXPORT.
     * @param mixed $timeoutSeconds REPORT_BUILDER_TIMEOUT_SECONDS.
     * @return array [maxRowsScreen, pageSizeMax, maxRowsExport, timeoutSeconds]
     */
    function normalizeLimits($maxRowsScreen, $pageSizeMax, $maxRowsExport, $timeoutSeconds)
    {
        $rowsScreen = $this->normalizeLimit($maxRowsScreen, self::DEFAULT_ROWS_SCREEN, self::HARD_MAX_ROWS_SCREEN);
        $pageSize   = $this->normalizeLimit($pageSizeMax, self::DEFAULT_PAGE_SIZE, self::HARD_MAX_PAGE_SIZE);

        return [
            'maxRowsScreen'  => $rowsScreen,
            // Una pagina nunca puede ser mayor que las filas navegables
            'pageSizeMax'    => min($pageSize, $rowsScreen),
            'maxRowsExport'  => $this->normalizeLimit($maxRowsExport, self::DEFAULT_ROWS_EXPORT, self::HARD_MAX_ROWS_EXPORT),
            'timeoutSeconds' => $this->normalizeLimit($timeoutSeconds, self::DEFAULT_TIMEOUT_SECONDS, self::HARD_MAX_TIMEOUT_SECONDS),
        ];
    }

    /* =================================================================================
       EJECUCION
    ================================================================================= */

    /**
     * Ejecuta un SELECT con limite de tiempo. No usa Bd_Model::executeRender porque ese quita
     * ONLY_FULL_GROUP_BY de la sesion.
     *
     * CI4 trata "SET ..." como escritura (BaseConnection::isWriteType) y devuelve bool, asi que
     * SET STATEMENT ... FOR SELECT no entrega filas. Por eso se cambia la variable de sesion y se
     * restaura en finally: la conexion de db_connect() es compartida.
     *
     * @param object $db             Conexion.
     * @param string $sql            Sentencia; debe empezar por SELECT.
     * @param array  $binds          Binds nominales.
     * @param mixed  $timeoutSeconds Segundos maximos.
     * @return array Filas asociativas.
     * @throws \Exception Con mensaje para el usuario si se agota el tiempo.
     */
    function execute($db, $sql, $binds, $timeoutSeconds)
    {
        if (!is_string($sql) || !preg_match('/^\s*SELECT\b/i', $sql)) {
            throw new \Exception('Solo se permite ejecutar consultas SELECT.');
        }

        $seconds  = $this->normalizeLimit($timeoutSeconds, self::DEFAULT_TIMEOUT_SECONDS, self::HARD_MAX_TIMEOUT_SECONDS);
        $variable = $this->resolveTimeoutVariable((string) $db->getVersion(), $seconds);
        $previous = null;

        try {
            if ($variable !== null) {
                $row      = $db->query('SELECT @@SESSION.' . $variable['name'] . ' AS valor')->getRowArray();
                $previous = $row['valor'] ?? null;
                $db->query('SET SESSION ' . $variable['name'] . ' = ' . $variable['value']);
            }

            $result = $db->query($sql, $binds);
            if (!is_object($result)) {
                // Solo ocurre con DBDebug = false; con DBDebug = true el driver lanza DatabaseException
                $error = $db->error();
                if ($this->isTimeoutError($error['code'] ?? 0, $error['message'] ?? '')) {
                    throw new \Exception($this->getTimeoutMessage());
                }
                throw new \RuntimeException('Error de base de datos ' . ($error['code'] ?? 0) . ': ' . ($error['message'] ?? ''));
            }

            return $result->getResultArray();
        } catch (\Throwable $ex) {
            if ($this->isTimeoutError($ex->getCode(), $ex->getMessage())) {
                throw new \Exception($this->getTimeoutMessage());
            }
            throw $ex;
        } finally {
            if ($variable !== null && is_numeric($previous)) {
                try {
                    $db->query('SET SESSION ' . $variable['name'] . ' = ' . $previous);
                } catch (\Throwable $ex) {
                    log_message('error', 'core_web_report_builder: no se restauro ' . $variable['name'] . ': ' . $ex->getMessage());
                }
            }
        }
    }

    /**
     * Variable de sesion que limita el tiempo segun el servidor: MariaDB en segundos, MySQL 5.7.8+ en
     * milisegundos. Otras versiones no tienen limite por sentencia.
     *
     * @param string $version Version del servidor.
     * @param int    $seconds Segundos.
     * @return array|null [name, value]
     */
    function resolveTimeoutVariable($version, $seconds)
    {
        if (stripos($version, 'mariadb') !== false) {
            return ['name' => 'max_statement_time', 'value' => (int) $seconds];
        }
        if (version_compare($version, '5.7.8', '>=')) {
            return ['name' => 'max_execution_time', 'value' => ((int) $seconds) * 1000];
        }
        return null;
    }

    /**
     * Indica si un error corresponde a tiempo agotado (1969 MariaDB, 3024 MySQL).
     *
     * @param mixed  $code    Codigo.
     * @param string $message Mensaje.
     * @return bool
     */
    function isTimeoutError($code, $message)
    {
        if ((int) $code === 1969 || (int) $code === 3024) {
            return true;
        }
        return preg_match('/max_statement_time|maximum statement execution time/i', (string) $message) === 1;
    }

    /**
     * Mensaje para el usuario cuando se agota el tiempo.
     *
     * @return string
     */
    private function getTimeoutMessage()
    {
        return 'La consulta tardo demasiado y fue cancelada. Reduzca el rango de fechas o agregue filtros.';
    }

    /* =================================================================================
       EXPORTACION (celdas puras)
    ================================================================================= */

    /**
     * Describe una celda de Excel sin depender de OpenSpout.
     *
     * OpenSpout escribe como FORMULA todo texto que empieza por "=" (CellTypeHelper::isFormula) y sin
     * escapar el XML (WorksheetManager::getCellXML); por eso el texto se neutraliza aqui.
     *
     * @param mixed $value  Valor de la BD.
     * @param array $column Columna [type, align, fn].
     * @return array [value, format, align]; value puede ser null, int, float, string o \DateTimeImmutable.
     */
    function buildExcelCell($value, array $column)
    {
        $type   = $column['type'] ?? 'text';
        $fn     = $column['fn'] ?? null;
        $align  = (($column['align'] ?? 'left') === 'right') ? 'right' : 'left';
        $result = ['value' => null, 'format' => null, 'align' => $align];

        if ($value === null || $value === '' || is_array($value) || is_object($value)) {
            return $result;
        }

        if ($type === 'number' || $fn !== null) {
            $number = is_bool($value) ? (int) $value : $value;
            if (is_numeric($number) && is_finite((float) $number)) {
                $result['value']  = $number + 0;
                $result['format'] = ($fn === 'COUNT') ? self::EXCEL_FORMAT_COUNT : self::EXCEL_FORMAT_NUMBER;
                return $result;
            }
        }

        if ($type === 'date') {
            $date = $this->parseExcelDate((string) $value);
            if ($date['isZero']) {
                return $result;
            }
            if ($date['value'] !== null) {
                $result['value']  = $date['value'];
                $result['format'] = $date['hasTime'] ? self::EXCEL_FORMAT_DATETIME : self::EXCEL_FORMAT_DATE;
                return $result;
            }
        }

        $result['value'] = $this->neutralizeExcelText((string) $value);
        return $result;
    }

    /**
     * Celda de encabezado: el titulo lo escribe el usuario, asi que se trata como texto.
     *
     * @param string $label Titulo.
     * @param string $align Alineacion.
     * @return array [value, format, align]
     */
    function buildExcelHeaderCell($label, $align)
    {
        return $this->buildExcelCell((string) $label, ['type' => 'text', 'align' => $align]);
    }

    /**
     * Interpreta una fecha sin desfase: se arma en UTC con los mismos componentes del texto, y
     * DateHelper::toExcel solo lee esos componentes. Antes de 1900 queda como texto porque esa
     * fecha no existe en la numeracion de Excel.
     *
     * @param string $value Fecha YYYY-MM-DD[ HH:MM:SS].
     * @return array [value, hasTime, isZero]
     */
    private function parseExcelDate($value)
    {
        $result = ['value' => null, 'hasTime' => false, 'isZero' => false];

        if (strpos($value, '0000-00-00') === 0) {
            $result['isZero'] = true;
            return $result;
        }

        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2}):(\d{2})(?:\.\d+)?)?$/D', $value, $parts)) {
            return $result;
        }

        $year   = (int) $parts[1];
        $hour   = (int) ($parts[4] ?? 0);
        $minute = (int) ($parts[5] ?? 0);
        $second = (int) ($parts[6] ?? 0);

        if ($year < 1900 || !checkdate((int) $parts[2], (int) $parts[3], $year) || $hour > 23 || $minute > 59 || $second > 59) {
            return $result;
        }

        $text = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, (int) $parts[2], (int) $parts[3], $hour, $minute, $second);
        $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $text, new \DateTimeZone('UTC'));
        if ($date === false) {
            return $result;
        }

        $result['value']   = $date;
        $result['hasTime'] = ($hour + $minute + $second) > 0;
        return $result;
    }

    /**
     * Neutraliza formulas con apostrofo y recorta al maximo de Excel (OpenSpout lanza excepcion si se
     * supera). El apostrofo queda visible en la celda: Excel solo lo oculta si lo teclea el usuario.
     *
     * @param string $text Texto.
     * @return string
     */
    private function neutralizeExcelText($text)
    {
        if ($text !== '' && strpos("=+-@\t\r", $text[0]) !== false) {
            $text = "'" . $text;
        }
        return mb_substr($text, 0, self::MAX_EXCEL_CELL_LENGTH, 'UTF-8');
    }

    /**
     * Valida el nombre de una consulta guardada. La conexion es utf8 de 3 bytes, asi que se rechazan los
     * caracteres de 4 bytes (emojis) y el texto que no sea UTF-8 valido.
     *
     * @param mixed $name Nombre recibido.
     * @return string Nombre sin espacios en los extremos.
     * @throws \Exception Si no es valido.
     */
    function normalizeQueryName($name)
    {
        if (!is_string($name) || trim($name) === '') {
            throw new \Exception('Debe indicar el nombre de la consulta.');
        }

        $name = trim($name);
        if (preg_match('/[\x{10000}-\x{10FFFF}]/u', $name) !== 0) {
            throw new \Exception('El nombre de la consulta contiene caracteres no permitidos.');
        }
        if (mb_strlen($name, 'UTF-8') > self::MAX_LABEL_LENGTH) {
            throw new \Exception('El nombre de la consulta no debe pasar de ' . self::MAX_LABEL_LENGTH . ' caracteres.');
        }

        return $name;
    }

    /**
     * Nombre de hoja valido: maximo 31 caracteres, sin \ / ? * [ ] : y sin apostrofo en los extremos.
     *
     * @param mixed $name Nombre propuesto.
     * @return string
     */
    function sanitizeSheetName($name)
    {
        $text = str_replace(['\\', '/', '?', '*', '[', ']', ':'], ' ', is_string($name) ? $name : '');
        $text = trim(mb_substr(trim($text, " '"), 0, 31, 'UTF-8'), " '");
        return ($text === '') ? 'Reporte' : $text;
    }

    /**
     * Nombre de archivo seguro con extension .xlsx.
     *
     * @param mixed $name Nombre propuesto.
     * @return string
     */
    function sanitizeFileName($name)
    {
        $text = preg_replace('/[^A-Za-z0-9_\-]+/', '_', is_string($name) ? $name : '');
        $text = trim(substr((string) $text, 0, 80), '_-');
        return (($text === '') ? 'reporte' : $text) . '.xlsx';
    }
}
