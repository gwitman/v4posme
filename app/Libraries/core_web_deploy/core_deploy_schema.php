<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Lectura, normalización y comparación de la estructura MySQL/MariaDB.
 *
 * - Tablas, columnas, índices y restricciones: se comparan a partir de
 *   SHOW CREATE TABLE (cada línea de columna/índice es la definición exacta que
 *   se usa para generar ADD/MODIFY), normalizada para que las diferencias
 *   cosméticas entre MySQL 8 y MariaDB no generen cambios falsos.
 * - Vistas, funciones, procedimientos y triggers: SHOW CREATE sin DEFINER.
 *
 * Política: nunca se eliminan tablas ni columnas; lo que sobra en el destino se
 * reporta en "extras". Vistas, rutinas y triggers se recrean (sin pérdida de datos).
 */
class core_deploy_schema
{
    /**
     * Toma una fotografía de la estructura de la BD conectada.
     *
     * @param object $db Conexión CI4 (BaseConnection).
     * @return array
     */
    static function snapshot($db)
    {
        $schema  = self::scalar($db, 'SELECT DATABASE()');
        $version = self::scalar($db, 'SELECT VERSION()');
        $result  = [
            'schema'     => $schema,
            'version'    => $version,
            'isMaria'    => stripos($version, 'mariadb') !== false,
            'tables'     => [],
            'views'      => [],
            'functions'  => [],
            'procedures' => [],
            'triggers'   => [],
            'errors'     => [],
        ];

        foreach ($db->query('SHOW FULL TABLES')->getResultArray() as $row) {
            $values = array_values($row);
            $name   = $values[0];
            $type   = strtoupper($values[1]);
            try {
                if ($type === 'VIEW') {
                    $create = self::showCreate($db, 'SHOW CREATE VIEW `' . $name . '`', 1);
                    $result['views'][$name] = $create;
                } else {
                    $create = self::showCreate($db, 'SHOW CREATE TABLE `' . $name . '`', 1);
                    $result['tables'][$name] = $create;
                }
            } catch (\Throwable $ex) {
                $result['errors'][] = $name . ': ' . $ex->getMessage();
            }
        }

        $routines = $db->query('SELECT ROUTINE_NAME, ROUTINE_TYPE FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE()')->getResultArray();
        foreach ($routines as $row) {
            $kind = strtoupper($row['ROUTINE_TYPE']) === 'FUNCTION' ? 'FUNCTION' : 'PROCEDURE';
            try {
                $create = self::showCreate($db, 'SHOW CREATE ' . $kind . ' `' . $row['ROUTINE_NAME'] . '`', 2);
                if ($create === null || $create === '') {
                    $result['errors'][] = $row['ROUTINE_NAME'] . ': sin privilegios para leer la definición';
                    continue;
                }
                $result[$kind === 'FUNCTION' ? 'functions' : 'procedures'][$row['ROUTINE_NAME']] = $create;
            } catch (\Throwable $ex) {
                $result['errors'][] = $row['ROUTINE_NAME'] . ': ' . $ex->getMessage();
            }
        }

        foreach ($db->query('SHOW TRIGGERS')->getResultArray() as $row) {
            try {
                $result['triggers'][$row['Trigger']] = self::showCreate($db, 'SHOW CREATE TRIGGER `' . $row['Trigger'] . '`', 2);
            } catch (\Throwable $ex) {
                $result['errors'][] = $row['Trigger'] . ': ' . $ex->getMessage();
            }
        }

        ksort($result['tables']);
        ksort($result['views']);
        ksort($result['functions']);
        ksort($result['procedures']);
        ksort($result['triggers']);
        return $result;
    }

    /** Primera columna de la primera fila. */
    private static function scalar($db, $sql)
    {
        $row = $db->query($sql)->getRowArray();
        return $row ? (string) array_values($row)[0] : '';
    }

    /** Valor de la columna $index de un SHOW CREATE. */
    private static function showCreate($db, $sql, $index)
    {
        $row = $db->query($sql)->getRowArray();
        if (!$row) {
            return null;
        }
        $values = array_values($row);
        return isset($values[$index]) ? (string) $values[$index] : null;
    }

    /**
     * Separa un CREATE TABLE en columnas, índices, restricciones y opciones.
     *
     * @param string $create Salida de SHOW CREATE TABLE.
     * @return array ['columns' => [nombre => línea], 'order' => [...], 'keys' => [...], 'constraints' => [...], 'options' => string]
     */
    static function parseCreateTable($create)
    {
        $create = str_replace(["\r\n", "\r"], "\n", $create);
        $open   = strpos($create, "(\n");
        $close  = strrpos($create, "\n)");
        $parsed = ['columns' => [], 'order' => [], 'keys' => [], 'constraints' => [], 'options' => ''];
        if ($open === false || $close === false) {
            return $parsed;
        }
        $parsed['options'] = trim(substr($create, $close + 2));
        foreach (explode("\n", substr($create, $open + 2, $close - $open - 2)) as $line) {
            $line = rtrim(trim($line), ',');
            if ($line === '') {
                continue;
            }
            if (preg_match('/^`((?:[^`]|``)+)`\s/', $line, $m)) {
                $parsed['columns'][$m[1]] = $line;
                $parsed['order'][]        = $m[1];
            } elseif (preg_match('/^PRIMARY KEY/i', $line)) {
                $parsed['keys']['PRIMARY'] = $line;
            } elseif (preg_match('/^(?:UNIQUE |FULLTEXT |SPATIAL )?(?:KEY|INDEX)\s+`((?:[^`]|``)+)`/i', $line, $m)) {
                $parsed['keys'][$m[1]] = $line;
            } elseif (preg_match('/^CONSTRAINT\s+`((?:[^`]|``)+)`/i', $line, $m)) {
                $parsed['constraints'][$m[1]] = $line;
            } elseif (preg_match('/^(?:CHECK|FOREIGN KEY)/i', $line)) {
                $parsed['constraints']['__' . md5($line)] = $line;
            }
        }
        return $parsed;
    }

    /**
     * Pasa a minúsculas todo lo que no esté entre comillas simples.
     *
     * @return string
     */
    static function lowerOutsideQuotes($text)
    {
        $parts = preg_split("/('(?:[^'\\\\]|\\\\.|'')*')/s", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($parts as $i => $part) {
            if ($i % 2 === 0) {
                $parts[$i] = strtolower($part);
            }
        }
        return implode('', $parts);
    }

    /**
     * Normaliza una definición de columna para comparar MySQL y MariaDB.
     *
     * @param string $line Línea de columna de SHOW CREATE TABLE.
     * @return string
     */
    static function normalizeColumn($line)
    {
        $text = self::lowerOutsideQuotes($line);
        // JSON de MariaDB (longtext + CHECK json_valid) equivale a json de MySQL
        if (preg_match('/check\s*\(\s*json_valid\(/', $text)) {
            $text = preg_replace('/^(`[^`]+`)\s+longtext\b/', '$1 json', $text);
            $text = preg_replace('/\s*check\s*\(\s*json_valid\(`[^`]+`\)\s*\)/', '', $text);
        }
        $text = preg_replace('/\s+character set \w+/', '', $text);
        $text = preg_replace('/\s+collate \w+/', '', $text);
        $text = preg_replace('/\b(tinyint|smallint|mediumint|int|integer|bigint)\(\d+\)/', '$1', $text);
        $text = preg_replace('/\bcurrent_timestamp\(\)/', 'current_timestamp', $text);
        $text = preg_replace("/\bdefault '(-?\d+(?:\.\d+)?)'/", 'default $1', $text);
        $text = str_replace('utf8mb3', 'utf8', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Normaliza una línea de índice o restricción.
     *
     * @return string
     */
    static function normalizeKey($line)
    {
        $text = self::lowerOutsideQuotes($line);
        $text = preg_replace('/\s+using (btree|hash)/', '', $text);
        $text = preg_replace('/\s*\/\*!\d+\s*(in)?visible\s*\*\//', '', $text);
        $text = preg_replace("/\s+comment ''/", '', $text);
        $text = preg_replace('/\s+on (delete|update) restrict/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Quita la cláusula DEFINER de un CREATE (en hosting compartido falla).
     *
     * @return string
     */
    static function stripDefiner($sql)
    {
        return preg_replace('/\s+DEFINER\s*=\s*(?:`[^`]*`|\'[^\']*\'|[^\s@]+)@(?:`[^`]*`|\'[^\']*\'|[^\s]+)/i', '', $sql, 1);
    }

    /**
     * Normaliza rutinas y triggers para comparar.
     *
     * @return string
     */
    static function normalizeRoutine($sql)
    {
        $text = self::stripDefiner(str_replace(["\r\n", "\r"], "\n", (string) $sql));
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Extrae de SHOW CREATE VIEW la seguridad y el cuerpo SELECT.
     *
     * @param string $create Salida de SHOW CREATE VIEW.
     * @param string $schema Esquema a quitar de los nombres calificados.
     * @return array ['security' => DEFINER|INVOKER, 'body' => string]
     */
    static function parseView($create, $schema)
    {
        $security = preg_match('/SQL SECURITY (DEFINER|INVOKER)/i', $create, $m) ? strtoupper($m[1]) : 'DEFINER';
        $body     = preg_match('/\bVIEW\s+`(?:[^`]|``)+`(?:\s*\([^)]*\))?\s+AS\s+(.*)$/is', $create, $m) ? $m[1] : $create;
        if ($schema !== '') {
            $body = str_replace('`' . $schema . '`.', '', $body);
        }
        return ['security' => $security, 'body' => trim($body)];
    }

    /**
     * Normaliza el cuerpo de una vista para comparar.
     *
     * @return string
     */
    static function normalizeViewBody($body)
    {
        $text = self::lowerOutsideQuotes($body);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Tipo base y longitud de una columna normalizada.
     *
     * @return array [tipo, longitud|null]
     */
    private static function columnType($normalized)
    {
        if (preg_match('/^`[^`]+`\s+([a-z]+)(?:\(([^)]*)\))?/', $normalized, $m)) {
            return [$m[1], isset($m[2]) ? $m[2] : null];
        }
        return ['', null];
    }

    /**
     * Indica si cambiar una columna de $old a $new puede perder datos o fallar.
     *
     * @return string|null Motivo del riesgo o null si es seguro.
     */
    static function columnRisk($oldNormalized, $newNormalized)
    {
        list($oldType, $oldLen) = self::columnType($oldNormalized);
        list($newType, $newLen) = self::columnType($newNormalized);

        $ints  = ['tinyint' => 1, 'smallint' => 2, 'mediumint' => 3, 'int' => 4, 'integer' => 4, 'bigint' => 5];
        $texts = ['char' => 1, 'varchar' => 2, 'tinytext' => 3, 'text' => 4, 'mediumtext' => 5, 'longtext' => 6, 'json' => 6];

        if ($oldType !== $newType) {
            if (isset($ints[$oldType], $ints[$newType]) && $ints[$newType] >= $ints[$oldType]) {
                // ampliación de entero: segura
            } elseif (isset($texts[$oldType], $texts[$newType]) && $texts[$newType] >= $texts[$oldType]) {
                // ampliación de texto: segura
            } else {
                return 'Cambia el tipo de ' . $oldType . ' a ' . $newType;
            }
        } elseif ($oldLen !== null && $newLen !== null && $oldLen !== $newLen) {
            $oldParts = array_map('intval', explode(',', $oldLen));
            $newParts = array_map('intval', explode(',', $newLen));
            foreach ($oldParts as $i => $value) {
                if (isset($newParts[$i]) && $newParts[$i] < $value) {
                    return 'Reduce el tamaño de ' . $oldType . '(' . $oldLen . ') a (' . $newLen . ')';
                }
            }
        }

        if (strpos($oldNormalized, 'not null') === false && strpos($newNormalized, 'not null') !== false) {
            return 'Pasa de NULL a NOT NULL (falla o convierte valores nulos)';
        }
        if (strpos($newNormalized, 'auto_increment') !== false && strpos($oldNormalized, 'auto_increment') === false) {
            return 'Agrega AUTO_INCREMENT';
        }
        return null;
    }

    /**
     * Compara la estructura origen (local) con la destino (remota) y genera el plan.
     *
     * @param array $source Snapshot local.
     * @param array $target Snapshot remoto.
     * @return array ['steps' => [...], 'extras' => [...], 'notes' => [...], 'hash' => string, 'summary' => [...]]
     */
    static function diff(array $source, array $target)
    {
        $steps  = [];
        $extras = [];
        $notes  = [];

        // 1. Tablas, columnas, índices y restricciones
        foreach ($source['tables'] as $table => $create) {
            if (!isset($target['tables'][$table])) {
                $sql     = preg_replace('/\s+AUTO_INCREMENT=\d+/i', '', $create);
                $sql     = preg_replace('/^CREATE TABLE\s+/i', 'CREATE TABLE IF NOT EXISTS ', $sql, 1);
                $steps[] = self::step('tabla', 'crear', $table, [$sql], null, 'Tabla nueva');
                continue;
            }

            $local  = self::parseCreateTable($create);
            $remote = self::parseCreateTable($target['tables'][$table]);

            // Columnas (en el orden local, con AFTER para respetar la posición)
            $previous = null;
            foreach ($local['order'] as $column) {
                $line = $local['columns'][$column];
                if (!isset($remote['columns'][$column])) {
                    $position = $previous === null ? ' FIRST' : ' AFTER `' . str_replace('`', '``', $previous) . '`';
                    $risk     = stripos($line, 'auto_increment') !== false ? 'Columna AUTO_INCREMENT nueva' : null;
                    $steps[]  = self::step('columna', 'agregar', $table . '.' . $column, ['ALTER TABLE `' . $table . '` ADD COLUMN ' . $line . $position], $risk, 'Columna nueva', null, $line);
                } else {
                    $old = self::normalizeColumn($remote['columns'][$column]);
                    $new = self::normalizeColumn($line);
                    if ($old !== $new) {
                        $steps[] = self::step('columna', 'modificar', $table . '.' . $column, ['ALTER TABLE `' . $table . '` MODIFY COLUMN ' . $line], self::columnRisk($old, $new), 'Definición distinta', $remote['columns'][$column], $line);
                    }
                }
                $previous = $column;
            }
            foreach ($remote['order'] as $column) {
                if (!isset($local['columns'][$column])) {
                    $extras[] = ['tipo' => 'columna', 'objeto' => $table . '.' . $column, 'nota' => 'Existe solo en el destino (no se elimina)'];
                }
            }

            // Índices
            foreach ($local['keys'] as $name => $line) {
                $quoted = '`' . str_replace('`', '``', $name) . '`';
                if (!isset($remote['keys'][$name])) {
                    $risk    = $name === 'PRIMARY' ? 'Agrega llave primaria (falla si hay duplicados)' : null;
                    $steps[] = self::step('indice', 'agregar', $table . '.' . $name, ['ALTER TABLE `' . $table . '` ADD ' . $line], $risk, stripos($line, 'UNIQUE') === 0 ? 'Índice único nuevo (falla si hay duplicados)' : 'Índice nuevo', null, $line);
                } elseif (self::normalizeKey($remote['keys'][$name]) !== self::normalizeKey($line)) {
                    $drop    = $name === 'PRIMARY' ? 'DROP PRIMARY KEY' : 'DROP INDEX ' . $quoted;
                    $risk    = $name === 'PRIMARY' ? 'Cambia la llave primaria' : null;
                    $steps[] = self::step('indice', 'modificar', $table . '.' . $name, ['ALTER TABLE `' . $table . '` ' . $drop . ', ADD ' . $line], $risk, 'Columnas del índice distintas', $remote['keys'][$name], $line);
                }
            }
            foreach ($remote['keys'] as $name => $line) {
                if (!isset($local['keys'][$name])) {
                    $extras[] = ['tipo' => 'indice', 'objeto' => $table . '.' . $name, 'nota' => 'Existe solo en el destino (no se elimina)'];
                }
            }

            // Restricciones (FK / CHECK)
            foreach ($local['constraints'] as $name => $line) {
                if (strpos($name, '__') === 0) {
                    continue;
                }
                $quoted = '`' . str_replace('`', '``', $name) . '`';
                $isFk   = stripos($line, 'FOREIGN KEY') !== false;
                if (!isset($remote['constraints'][$name])) {
                    $steps[] = self::step('restriccion', 'agregar', $table . '.' . $name, ['ALTER TABLE `' . $table . '` ADD ' . $line], $isFk ? 'Llave foránea nueva (falla si hay huérfanos)' : null, 'Restricción nueva', null, $line);
                } elseif (self::normalizeKey($remote['constraints'][$name]) !== self::normalizeKey($line)) {
                    $drop    = $isFk ? 'DROP FOREIGN KEY ' . $quoted : 'DROP CONSTRAINT ' . $quoted;
                    $steps[] = self::step('restriccion', 'modificar', $table . '.' . $name, ['ALTER TABLE `' . $table . '` ' . $drop, 'ALTER TABLE `' . $table . '` ADD ' . $line], $isFk ? 'Recrea una llave foránea' : null, 'Restricción distinta', $remote['constraints'][$name], $line);
                }
            }

            // Opciones de tabla: solo se informan
            $localEngine  = preg_match('/ENGINE=(\w+)/i', $local['options'], $m) ? strtolower($m[1]) : '';
            $remoteEngine = preg_match('/ENGINE=(\w+)/i', $remote['options'], $m) ? strtolower($m[1]) : '';
            if ($localEngine !== $remoteEngine) {
                $notes[] = $table . ': motor distinto (' . $remoteEngine . ' en destino, ' . $localEngine . ' en origen); no se cambia.';
            }
        }
        foreach ($target['tables'] as $table => $create) {
            if (!isset($source['tables'][$table])) {
                $extras[] = ['tipo' => 'tabla', 'objeto' => $table, 'nota' => 'Existe solo en el destino (no se elimina)'];
            }
        }

        // 2. Funciones (antes de las vistas, que pueden usarlas)
        self::diffRoutines($steps, $extras, $source, $target, 'functions', 'FUNCTION', 'funcion');

        // 3. Vistas en orden de dependencia
        $viewSteps = [];
        foreach ($source['views'] as $view => $create) {
            $local = self::parseView($create, $source['schema']);
            if (isset($target['views'][$view])) {
                $remote = self::parseView($target['views'][$view], $target['schema']);
                if (self::normalizeViewBody($remote['body']) === self::normalizeViewBody($local['body'])) {
                    continue;
                }
            }
            $sql               = 'CREATE OR REPLACE SQL SECURITY ' . $local['security'] . ' VIEW `' . $view . '` AS ' . $local['body'];
            $viewSteps[$view]  = self::step('vista', isset($target['views'][$view]) ? 'reemplazar' : 'crear', $view, [$sql], null, isset($target['views'][$view]) ? 'Definición distinta' : 'Vista nueva');
            $viewSteps[$view]['_body'] = $local['body'];
        }
        foreach (self::sortViews($viewSteps) as $step) {
            unset($step['_body']);
            $steps[] = $step;
        }
        foreach ($target['views'] as $view => $create) {
            if (!isset($source['views'][$view])) {
                $extras[] = ['tipo' => 'vista', 'objeto' => $view, 'nota' => 'Existe solo en el destino (no se elimina)'];
            }
        }

        // 4. Procedimientos y 5. triggers
        self::diffRoutines($steps, $extras, $source, $target, 'procedures', 'PROCEDURE', 'procedimiento');
        self::diffRoutines($steps, $extras, $source, $target, 'triggers', 'TRIGGER', 'trigger');

        $summary = [];
        foreach ($steps as $step) {
            $summary[$step['grupo']] = (isset($summary[$step['grupo']]) ? $summary[$step['grupo']] : 0) + 1;
        }

        return [
            'steps'   => $steps,
            'extras'  => $extras,
            'notes'   => $notes,
            'summary' => $summary,
            'hash'    => hash('sha256', json_encode(array_column($steps, 'sql'))),
        ];
    }

    /**
     * Compara funciones, procedimientos o triggers.
     *
     * @return void
     */
    private static function diffRoutines(array &$steps, array &$extras, array $source, array $target, $key, $keyword, $group)
    {
        foreach ($source[$key] as $name => $create) {
            if (isset($target[$key][$name]) && self::normalizeRoutine($target[$key][$name]) === self::normalizeRoutine($create)) {
                continue;
            }
            $sqlCreate = self::stripDefiner($create);
            $steps[]   = self::step(
                $group,
                isset($target[$key][$name]) ? 'reemplazar' : 'crear',
                $name,
                ['DROP ' . $keyword . ' IF EXISTS `' . $name . '`', $sqlCreate],
                null,
                isset($target[$key][$name]) ? 'Definición distinta' : ucfirst($group) . ' nuevo'
            );
        }
        foreach ($target[$key] as $name => $create) {
            if (!isset($source[$key][$name])) {
                $extras[] = ['tipo' => $group, 'objeto' => $name, 'nota' => 'Existe solo en el destino (no se elimina)'];
            }
        }
    }

    /**
     * Ordena las vistas para crear primero las que otras vistas usan.
     *
     * @param array $viewSteps [vista => paso con _body]
     * @return array Pasos ordenados.
     */
    private static function sortViews(array $viewSteps)
    {
        $names   = array_keys($viewSteps);
        $ordered = [];
        $visited = [];
        $visit   = function ($name, $depth) use (&$visit, &$ordered, &$visited, $viewSteps, $names) {
            if (isset($visited[$name]) || $depth > 50) {
                return;
            }
            $visited[$name] = true;
            foreach ($names as $other) {
                if ($other !== $name && strpos($viewSteps[$name]['_body'], '`' . $other . '`') !== false) {
                    $visit($other, $depth + 1);
                }
            }
            $ordered[] = $viewSteps[$name];
        };
        foreach ($names as $name) {
            $visit($name, 0);
        }
        return $ordered;
    }

    /**
     * Crea un paso del plan.
     *
     * @return array
     */
    private static function step($group, $action, $object, array $sql, $risk, $reason, $before = null, $after = null)
    {
        return [
            'id'     => substr(sha1($group . '|' . $object . '|' . implode(';', $sql)), 0, 12),
            'grupo'  => $group,
            'accion' => $action,
            'objeto' => $object,
            'sql'    => $sql,
            'riesgo' => $risk,
            'motivo' => $reason,
            'antes'  => $before,
            'despues'=> $after,
        ];
    }

    /**
     * Ejecuta una lista de sentencias en orden y se detiene en el primer error.
     *
     * @param object $db         Conexión CI4.
     * @param array  $statements Lista de ['id' => ..., 'sql' => ...].
     * @return array ['ok' => bool, 'results' => [...]]
     */
    static function run($db, array $statements)
    {
        // CodeIgniter conecta de forma diferida: si aún no hubo consultas, connID es false
        if (!($db->connID instanceof \mysqli)) {
            $db->initialize();
        }
        $mysqli = $db->connID;
        if (!($mysqli instanceof \mysqli)) {
            throw new \Exception('No se pudo abrir la conexión MySQL para aplicar la estructura.');
        }
        $results = [];
        foreach ($statements as $item) {
            $start = microtime(true);
            $error = null;
            $rows  = 0;
            try {
                $ok = $mysqli->query($item['sql']);
                if ($ok === false) {
                    $error = $mysqli->errno . ' ' . $mysqli->error;
                } else {
                    $rows = (int) $mysqli->affected_rows;
                    if ($ok instanceof \mysqli_result) {
                        $ok->free();
                    }
                }
            } catch (\Throwable $ex) {
                $error = $ex->getMessage();
            }
            $results[] = [
                'id'    => isset($item['id']) ? $item['id'] : null,
                'ok'    => $error === null,
                'error' => $error,
                'rows'  => $rows,
                'ms'    => (int) round((microtime(true) - $start) * 1000),
            ];
            if ($error !== null) {
                return ['ok' => false, 'results' => $results];
            }
        }
        return ['ok' => true, 'results' => $results];
    }
}
