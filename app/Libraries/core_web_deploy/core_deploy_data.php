<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Sincronización de datos de las tablas de configuración.
 *
 * Reemplaza el proceso manual del perfil Navicat "01 sincronizar todas las tablas
 * de configuracion" (+ perfil 03 de solo inserción para tb_counter) seguido de
 * los scripts 001_default y del cliente.
 *
 * Se usa igual en el agente (conexión local del servidor) y en el modo MySQL
 * directo (conexión remota desde la consola).
 *
 * Modos por tabla (core_deploy_rules::MODE_*):
 *   full   = insertar + actualizar + eliminar (comportamiento del perfil 01)
 *   upsert = insertar + actualizar
 *   insert = solo insertar las filas cuya llave primaria no existe (perfil 03)
 */
class core_deploy_data
{
    /**
     * Columnas y llave primaria de una tabla.
     *
     * @param object $db    Conexión CI4.
     * @param string $table Tabla.
     * @return array|null ['columns' => [...], 'pk' => [...]] o null si no existe.
     */
    static function tableInfo($db, $table)
    {
        if (preg_match('/^[A-Za-z0-9_]+$/', $table) !== 1) {
            throw new \Exception('Nombre de tabla inválido: ' . $table);
        }
        $exists = $db->query('SELECT COUNT(*) AS c FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND TABLE_TYPE = \'BASE TABLE\'', [$table])->getRow();
        if (!$exists || (int) $exists->c === 0) {
            return null;
        }
        $columns = [];
        foreach ($db->query('SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION', [$table])->getResult() as $row) {
            $columns[] = $row->COLUMN_NAME;
        }
        $pk = [];
        foreach ($db->query('SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = \'PRIMARY\' ORDER BY ORDINAL_POSITION', [$table])->getResult() as $row) {
            $pk[] = $row->COLUMN_NAME;
        }
        return ['columns' => $columns, 'pk' => $pk];
    }

    /**
     * Clave de fila a partir de la llave primaria.
     *
     * @return string
     */
    static function rowKey(array $row, array $pk)
    {
        $values = [];
        foreach ($pk as $column) {
            $values[] = isset($row[$column]) ? (string) $row[$column] : null;
        }
        return json_encode($values);
    }

    /**
     * Hash de una fila sobre una lista de columnas (mismo cálculo en ambos lados).
     *
     * @return string
     */
    static function rowHash(array $row, array $columns)
    {
        $values = [];
        foreach ($columns as $column) {
            $values[] = array_key_exists($column, $row) && $row[$column] !== null ? (string) $row[$column] : null;
        }
        return md5(json_encode($values));
    }

    /**
     * Lee todas las filas de una tabla.
     *
     * @return array
     */
    static function rows($db, $table)
    {
        return $db->query('SELECT * FROM `' . $table . '`')->getResultArray();
    }

    /**
     * Inspecciona las tablas en el destino: existencia, columnas, PK y hash por fila.
     *
     * @param object $db     Conexión destino.
     * @param array  $tables [tabla => columnas del origen]
     * @return array [tabla => ['exists', 'columns', 'pk', 'common', 'hashes' => [clave => hash]]]
     */
    static function inspect($db, array $tables)
    {
        $result = [];
        foreach ($tables as $table => $sourceColumns) {
            if (!core_deploy_rules::isSyncableTable($table)) {
                throw new \Exception('Tabla no permitida para sincronizar: ' . $table);
            }
            $info = self::tableInfo($db, $table);
            if ($info === null) {
                $result[$table] = ['exists' => false];
                continue;
            }
            $common = array_values(array_intersect((array) $sourceColumns, $info['columns']));
            $hashes = [];
            if (count($info['pk'])) {
                foreach (self::rows($db, $table) as $row) {
                    $hashes[self::rowKey($row, $info['pk'])] = self::rowHash($row, $common);
                }
            }
            $result[$table] = [
                'exists'  => true,
                'columns' => $info['columns'],
                'pk'      => $info['pk'],
                'common'  => $common,
                'hashes'  => $hashes,
            ];
        }
        return $result;
    }

    /**
     * Calcula los cambios de una tabla comparando filas locales con hashes remotos.
     *
     * @param array  $localRows Filas del origen.
     * @param array  $remote    Resultado de inspect() para la tabla.
     * @param string $mode      Modo de sincronización.
     * @param string $table     Nombre de la tabla (para las reglas de solo inserción).
     * @return array ['pk', 'columns', 'inserts', 'updates', 'deletes', 'counts', 'warnings']
     */
    static function changes(array $localRows, array $remote, $mode, $table)
    {
        $warnings = [];
        $pk       = $remote['pk'];
        $columns  = $remote['common'];
        $inserts  = [];
        $updates  = [];
        $deletes  = [];

        if (!count($pk)) {
            return ['pk' => [], 'columns' => $columns, 'inserts' => [], 'updates' => [], 'deletes' => [], 'counts' => ['insert' => 0, 'update' => 0, 'delete' => 0, 'same' => 0], 'warnings' => ['La tabla no tiene llave primaria en el destino; se omite.']];
        }
        foreach ($pk as $column) {
            if (!in_array($column, $columns, true)) {
                return ['pk' => $pk, 'columns' => $columns, 'inserts' => [], 'updates' => [], 'deletes' => [], 'counts' => ['insert' => 0, 'update' => 0, 'delete' => 0, 'same' => 0], 'warnings' => ['La llave primaria del destino no existe en el origen; se omite.']];
            }
        }
        $missing = array_diff(array_keys(isset($localRows[0]) ? $localRows[0] : []), $columns);
        if (count($missing)) {
            $warnings[] = 'Columnas que aún no existen en el destino (sincronice la estructura primero): ' . implode(', ', $missing);
        }

        $overrides = isset(core_deploy_rules::$INSERT_OVERRIDES[$table]) ? core_deploy_rules::$INSERT_OVERRIDES[$table] : [];
        $seen      = [];
        $same      = 0;

        foreach ($localRows as $row) {
            $key        = self::rowKey($row, $pk);
            $seen[$key] = true;
            $values     = [];
            foreach ($columns as $column) {
                $values[$column] = array_key_exists($column, $row) ? $row[$column] : null;
            }
            if (!isset($remote['hashes'][$key])) {
                if ($mode === core_deploy_rules::MODE_INSERT) {
                    foreach ($overrides as $column => $value) {
                        if (array_key_exists($column, $values)) {
                            $values[$column] = $value;
                        }
                    }
                }
                $inserts[] = $values;
            } elseif ($remote['hashes'][$key] !== self::rowHash($row, $columns)) {
                if ($mode === core_deploy_rules::MODE_FULL || $mode === core_deploy_rules::MODE_UPSERT) {
                    $updates[] = $values;
                } else {
                    $same++;
                }
            } else {
                $same++;
            }
        }

        if ($mode === core_deploy_rules::MODE_FULL) {
            foreach (array_keys($remote['hashes']) as $key) {
                if (!isset($seen[$key])) {
                    $deletes[] = json_decode($key, true);
                }
            }
        }

        return [
            'pk'       => $pk,
            'columns'  => $columns,
            'inserts'  => $inserts,
            'updates'  => $updates,
            'deletes'  => $deletes,
            'counts'   => ['insert' => count($inserts), 'update' => count($updates), 'delete' => count($deletes), 'same' => $same],
            'warnings' => $warnings,
        ];
    }

    /**
     * Respalda el contenido actual de las tablas (antes de modificarlas).
     *
     * @param object $db     Conexión destino.
     * @param array  $tables Tablas a respaldar.
     * @param string $file   Archivo JSON comprimido.
     * @return int Filas respaldadas.
     */
    static function backup($db, array $tables, $file)
    {
        $data  = ['createdOn' => helper_getDateTime(), 'tables' => []];
        $count = 0;
        foreach ($tables as $table) {
            $info = self::tableInfo($db, $table);
            if ($info === null) {
                continue;
            }
            $rows                   = self::rows($db, $table);
            $data['tables'][$table] = ['columns' => $info['columns'], 'rows' => $rows];
            $count                 += count($rows);
        }
        core_deploy_files::writeJson($file, $data, true);
        return $count;
    }

    /**
     * Restaura un respaldo hecho con backup() en una transacción.
     *
     * @return array Filas restauradas por tabla.
     */
    static function restore($db, $file)
    {
        $data = core_deploy_files::readJson($file);
        if (!$data || !isset($data['tables'])) {
            throw new \Exception('No existe respaldo de datos para este despliegue.');
        }
        $restored = [];
        $sqlMode  = self::relaxSqlMode($db);
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->transBegin();
        try {
            foreach ($data['tables'] as $table => $content) {
                if (!core_deploy_rules::isSyncableTable($table) && $table !== 'tb_counter') {
                    throw new \Exception('Tabla no permitida en el respaldo: ' . $table);
                }
                // Restauración explícita del respaldo: se vacía la tabla y se reinsertan sus filas
                $db->table($table)->where('1 = 1', null, false)->delete();
                foreach (array_chunk($content['rows'], 200) as $chunk) {
                    if (count($chunk)) {
                        $db->table($table)->insertBatch($chunk);
                    }
                }
                $restored[$table] = count($content['rows']);
            }
            $db->transCommit();
        } catch (\Throwable $ex) {
            $db->transRollback();
            $db->query('SET FOREIGN_KEY_CHECKS = 1');
            self::restoreSqlMode($db, $sqlMode);
            throw new \Exception('Restauración revertida: ' . $ex->getMessage());
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1');
        self::restoreSqlMode($db, $sqlMode);
        return $restored;
    }

    /**
     * Aplica los cambios de datos y luego los scripts, todo en una sola transacción.
     * Si cualquier sentencia falla se revierte todo (ROLLBACK).
     *
     * @param object $db      Conexión destino.
     * @param array  $tables  [tabla => resultado de changes()]
     * @param array  $scripts Lista de ['name' => ..., 'sql' => ...] en orden de ejecución.
     * @return array ['ok' => bool, 'tables' => [...], 'scripts' => [...], 'error' => string|null]
     */
    static function apply($db, array $tables, array $scripts)
    {
        // Los scripts no pueden tener DDL: provocaría COMMIT implícito y rompería la reversión
        $parsedScripts = [];
        foreach ($scripts as $script) {
            $statements = core_deploy_sql_splitter::split($script['sql']);
            foreach ($statements as $statement) {
                if (core_deploy_sql_splitter::isDdl($statement['sql'])) {
                    throw new \Exception('El script ' . $script['name'] . ' contiene DDL en la línea ' . $statement['line'] . '; no se puede ejecutar dentro de la transacción.');
                }
            }
            $parsedScripts[] = ['name' => $script['name'], 'statements' => $statements];
        }

        $report  = ['ok' => false, 'tables' => [], 'scripts' => [], 'error' => null];
        $sqlMode = self::relaxSqlMode($db);
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->transBegin();

        try {
            foreach ($tables as $table => $change) {
                if (!core_deploy_rules::isSyncableTable($table) && !in_array($table, core_deploy_rules::$INSERT_ONLY_TABLES, true)) {
                    throw new \Exception('Tabla no permitida para sincronizar: ' . $table);
                }
                $pk     = $change['pk'];
                $done   = ['insert' => 0, 'update' => 0, 'delete' => 0];

                foreach (array_chunk($change['deletes'], 200) as $chunk) {
                    $builder = $db->table($table);
                    if (count($pk) === 1) {
                        $builder->whereIn($pk[0], array_column($chunk, 0));
                    } else {
                        $builder->groupStart();
                        foreach ($chunk as $values) {
                            $builder->orGroupStart();
                            foreach ($pk as $i => $column) {
                                $builder->where($column, $values[$i]);
                            }
                            $builder->groupEnd();
                        }
                        $builder->groupEnd();
                    }
                    $builder->delete();
                    $done['delete'] += $db->affectedRows();
                }

                foreach (array_chunk($change['inserts'], 200) as $chunk) {
                    $db->table($table)->insertBatch($chunk);
                    $done['insert'] += count($chunk);
                }

                foreach ($change['updates'] as $row) {
                    $builder = $db->table($table);
                    foreach ($pk as $column) {
                        $builder->where($column, $row[$column]);
                    }
                    $set = $row;
                    foreach ($pk as $column) {
                        unset($set[$column]);
                    }
                    if (count($set)) {
                        $builder->update($set);
                        $done['update']++;
                    }
                }
                $report['tables'][$table] = $done;
            }

            foreach ($parsedScripts as $script) {
                $result = core_deploy_schema::run($db, $script['statements']);
                $rows   = array_sum(array_column($result['results'], 'rows'));
                $report['scripts'][] = ['name' => $script['name'], 'statements' => count($result['results']), 'rows' => $rows, 'ok' => $result['ok']];
                if (!$result['ok']) {
                    $last  = end($result['results']);
                    $index = count($result['results']) - 1;
                    throw new \Exception('Script ' . $script['name'] . ', sentencia #' . ($index + 1) . ' (línea ' . $script['statements'][$index]['line'] . '): ' . $last['error']);
                }
            }

            if ($db->transStatus() === false) {
                throw new \Exception('La transacción quedó en estado de error.');
            }
            $db->transCommit();
            $report['ok'] = true;
        } catch (\Throwable $ex) {
            $db->transRollback();
            $report['error'] = $ex->getMessage();
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1');
        self::restoreSqlMode($db, $sqlMode);
        return $report;
    }

    /**
     * Usa un sql_mode no estricto durante la sincronización (como la BD local de
     * desarrollo) para que MySQL 8 en modo estricto no rechace datos válidos en
     * origen. Devuelve el modo anterior para restaurarlo.
     *
     * @param object $db Conexión.
     * @return string sql_mode anterior.
     */
    static function relaxSqlMode($db)
    {
        $row = $db->query('SELECT @@SESSION.sql_mode AS m')->getRow();
        $db->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
        return $row ? (string) $row->m : '';
    }

    /**
     * Restaura el sql_mode de la sesión.
     *
     * @return void
     */
    static function restoreSqlMode($db, $mode)
    {
        $db->query('SET SESSION sql_mode = ?', [(string) $mode]);
    }
}
