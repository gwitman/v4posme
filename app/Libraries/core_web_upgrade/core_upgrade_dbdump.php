<?php
//posme:2026-11-10
namespace App\Libraries\core_web_upgrade;

use App\Libraries\core_web_deploy\core_deploy_files;
use App\Libraries\core_web_deploy\core_deploy_schema;
use App\Libraries\core_web_deploy\core_deploy_sql_splitter;
use App\Libraries\core_web_deploy\core_deploy_data;

/**
 * Respaldo completo de la BD por bloques reanudables y su restauración.
 *
 * Carpeta: WRITEPATH/posme_upgrade/backups/<backupID>/
 *   000_schema.sql.gz    DROP TABLE IF EXISTS + CREATE TABLE de cada tabla.
 *   data/<n>_<parte>.sql.gz  INSERT de una página de filas (paginado por llave primaria).
 *   999_routines.sql.gz  funciones, vistas, procedimientos y triggers con DELIMITER ;; y sin DEFINER.
 *   manifest.json        COUNT(*) por tabla al iniciar, filas y sha256 de cada parte, cursor.
 *
 * Cada fila va en su propia línea (real_escape_string no deja saltos de línea
 * crudos), lo que permite contar filas y restaurar en streaming con gzgets.
 */
class core_upgrade_dbdump
{
    /** Carpeta de respaldos. */
    static function backupsDir()
    {
        return WRITEPATH . core_upgrade_rules::CLIENT_DIR . '/backups';
    }

    /**
     * Carpeta de un respaldo (valida el identificador).
     *
     * @param string      $backupID Identificador.
     * @param string|null $baseDir  Carpeta contenedora (null = respaldos de la actualización).
     * @return string
     */
    static function dir($backupID, $baseDir = null)
    {
        core_deploy_files::assertDeployID($backupID);
        return ($baseDir === null ? self::backupsDir() : $baseDir) . '/' . $backupID;
    }

    /** Conexión mysqli inicializada. */
    private static function mysqli($db)
    {
        if (!($db->connID instanceof \mysqli)) {
            $db->initialize();
        }
        if (!($db->connID instanceof \mysqli)) {
            throw new \Exception('No se pudo abrir la conexión MySQL para el respaldo.');
        }
        return $db->connID;
    }

    /** Escribe un archivo .gz y devuelve [sha256, bytes]. */
    private static function writeGz($path, $content)
    {
        core_deploy_files::writeAtomic($path, gzencode($content, 6));
        return ['sha256' => hash_file('sha256', $path), 'bytes' => filesize($path)];
    }

    /** Escapa un identificador con backticks. */
    private static function quoteName($name)
    {
        return '`' . str_replace('`', '``', $name) . '`';
    }

    /**
     * Inicia un respaldo: estructura, rutinas y plan de páginas por tabla.
     *
     * @param object $db       Conexión CI4.
     * @param string $backupID Identificador (mismo formato que deployID).
     * @param string|null $baseDir Carpeta contenedora (null = respaldos de la actualización).
     * @return array Manifiesto.
     */
    static function begin($db, $backupID, $baseDir = null)
    {
        $dir = self::dir($backupID, $baseDir);
        core_deploy_files::removeDir($dir);
        core_deploy_files::ensureDir($dir . '/data');
        // Solo se depuran los respaldos de la actualización (los manuales los borra el usuario)
        if ($baseDir === null) {
            core_deploy_files::pruneDirs(self::backupsDir(), core_upgrade_rules::KEEP_BACKUPS - 1, $backupID);
        }

        $snapshot = core_deploy_schema::snapshot($db);
        if (count($snapshot['errors'])) {
            throw new \Exception('No se pudo leer toda la estructura para el respaldo: ' . implode(' | ', array_slice($snapshot['errors'], 0, 5)));
        }

        // Estructura de tablas
        $schema = "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        foreach ($snapshot['tables'] as $table => $create) {
            $schema .= 'DROP TABLE IF EXISTS ' . self::quoteName($table) . ";\n" . $create . ";\n\n";
        }

        // Rutinas, vistas y triggers en orden de dependencia, sin DEFINER (diff contra una BD vacía)
        $empty    = ['schema' => '', 'tables' => [], 'views' => [], 'functions' => [], 'procedures' => [], 'triggers' => []];
        $plan     = core_deploy_schema::diff($snapshot, $empty);
        $routines = "DELIMITER ;;\n";
        foreach ($plan['steps'] as $step) {
            if ($step['grupo'] === 'tabla') {
                continue;
            }
            foreach ($step['sql'] as $sql) {
                $routines .= $sql . "\n;;\n";
            }
        }
        $routines .= "DELIMITER ;\n";

        $tables = [];
        $units  = 2;
        foreach (array_keys($snapshot['tables']) as $table) {
            $info  = core_deploy_data::tableInfo($db, $table);
            if ($info === null) {
                continue;
            }
            $count = (int) $db->query('SELECT COUNT(*) AS c FROM ' . self::quoteName($table))->getRow()->c;
            $pages = max(1, (int) ceil($count / core_upgrade_rules::DUMP_PAGE_ROWS));
            $units += $pages;
            $tables[$table] = ['count' => $count, 'pk' => $info['pk'], 'columns' => $info['columns'], 'rows' => 0, 'pages' => $pages, 'parts' => []];
        }

        $manifest = [
            'backupID'   => $backupID,
            'createdOn'  => helper_getDateTime(),
            'dbName'     => $snapshot['schema'],
            'dbVersion'  => $snapshot['version'],
            'complete'   => false,
            'schema'     => ['file' => '000_schema.sql.gz'] + self::writeGz($dir . '/000_schema.sql.gz', $schema),
            'routines'   => ['file' => '999_routines.sql.gz'] + self::writeGz($dir . '/999_routines.sql.gz', $routines),
            'tables'     => $tables,
            'order'      => array_keys($tables),
            'cursor'     => ['table' => 0, 'last' => null, 'offset' => 0],
            'unitsTotal' => $units,
            'unitsDone'  => 2,
            'rows'       => 0,
            'bytes'      => 0,
        ];
        core_deploy_files::writeJson($dir . '/manifest.json', $manifest);
        return $manifest;
    }

    /**
     * Manifiesto de un respaldo.
     *
     * @param string $backupID Identificador.
     * @param string|null $baseDir Carpeta contenedora (null = respaldos de la actualización).
     * @return array
     */
    static function manifest($backupID, $baseDir = null)
    {
        $manifest = core_deploy_files::readJson(self::dir($backupID, $baseDir) . '/manifest.json');
        if (!$manifest) {
            throw new \Exception('No existe el respaldo ' . $backupID . '.');
        }
        return $manifest;
    }

    /**
     * Continúa el respaldo de datos hasta agotar el presupuesto de tiempo.
     *
     * @param object $db       Conexión CI4.
     * @param string $backupID Identificador.
     * @param float  $deadline microtime(true) límite.
     * @param string|null $baseDir Carpeta contenedora (null = respaldos de la actualización).
     * @return array Manifiesto actualizado (complete = true al terminar).
     */
    static function step($db, $backupID, $deadline, $baseDir = null)
    {
        $dir      = self::dir($backupID, $baseDir);
        $manifest = self::manifest($backupID, $baseDir);
        $mysqli   = self::mysqli($db);

        while (!$manifest['complete'] && microtime(true) < $deadline) {
            $cursor = $manifest['cursor'];
            if ($cursor['table'] >= count($manifest['order'])) {
                $manifest['complete'] = true;
                break;
            }
            $table = $manifest['order'][$cursor['table']];
            $info  = $manifest['tables'][$table];
            $pk    = $info['pk'];

            // Página siguiente: por llave primaria si existe, si no por desplazamiento
            $sql = 'SELECT * FROM ' . self::quoteName($table);
            if (count($pk) && $cursor['last'] !== null) {
                $left  = [];
                $right = [];
                foreach ($pk as $i => $column) {
                    $left[]  = self::quoteName($column);
                    $right[] = "'" . $mysqli->real_escape_string((string) $cursor['last'][$i]) . "'";
                }
                $sql .= count($pk) === 1
                    ? ' WHERE ' . $left[0] . ' > ' . $right[0]
                    : ' WHERE (' . implode(',', $left) . ') > (' . implode(',', $right) . ')';
            }
            if (count($pk)) {
                $sql .= ' ORDER BY ' . implode(',', array_map([__CLASS__, 'quoteName'], $pk)) . ' LIMIT ' . core_upgrade_rules::DUMP_PAGE_ROWS;
            } else {
                $sql .= ' LIMIT ' . core_upgrade_rules::DUMP_PAGE_ROWS . ' OFFSET ' . (int) $cursor['offset'];
            }
            $result = $mysqli->query($sql, MYSQLI_USE_RESULT);
            if ($result === false) {
                throw new \Exception('No se pudo leer ' . $table . ': ' . $mysqli->errno . ' ' . $mysqli->error);
            }

            $lines   = [];
            $last    = null;
            $columns = null;
            $count   = 0;
            while ($row = $result->fetch_assoc()) {
                if ($columns === null) {
                    $columns = array_keys($row);
                }
                $values = [];
                foreach ($row as $value) {
                    $values[] = $value === null ? 'NULL' : "'" . $mysqli->real_escape_string((string) $value) . "'";
                }
                $lines[] = '(' . implode(',', $values) . ')';
                if (count($pk)) {
                    $last = [];
                    foreach ($pk as $column) {
                        $last[] = $row[$column];
                    }
                }
                $count++;
            }
            $result->free();

            if ($count > 0) {
                $header = 'INSERT INTO ' . self::quoteName($table) . ' (' . implode(',', array_map([__CLASS__, 'quoteName'], $columns)) . ') VALUES';
                $text   = '';
                foreach (array_chunk($lines, core_upgrade_rules::DUMP_INSERT_ROWS) as $chunk) {
                    $text .= $header . "\n" . implode(",\n", $chunk) . ";\n";
                }
                $part = count($info['parts']);
                $file = 'data/' . str_pad((string) $cursor['table'], 4, '0', STR_PAD_LEFT) . '_' . str_pad((string) $part, 5, '0', STR_PAD_LEFT) . '.sql.gz';
                $gz   = self::writeGz($dir . '/' . $file, $text);
                $manifest['tables'][$table]['parts'][] = ['file' => $file, 'rows' => $count] + $gz;
                $manifest['tables'][$table]['rows']   += $count;
                $manifest['rows']                     += $count;
                $manifest['bytes']                    += $gz['bytes'];
                $manifest['lastPart']                  = $file;
            }
            $manifest['unitsDone'] = min($manifest['unitsTotal'], $manifest['unitsDone'] + 1);

            if ($count < core_upgrade_rules::DUMP_PAGE_ROWS) {
                $manifest['cursor'] = ['table' => $cursor['table'] + 1, 'last' => null, 'offset' => 0];
            } else {
                $manifest['cursor'] = ['table' => $cursor['table'], 'last' => $last, 'offset' => $cursor['offset'] + $count];
            }
            core_deploy_files::writeJson($dir . '/manifest.json', $manifest);
        }
        if ($manifest['cursor']['table'] >= count($manifest['order'])) {
            $manifest['complete']  = true;
            $manifest['unitsDone'] = $manifest['unitsTotal'];
            core_deploy_files::writeJson($dir . '/manifest.json', $manifest);
        }
        return $manifest;
    }

    /**
     * Verifica integridad (sha256 y lectura gzip) y conteos del respaldo.
     *
     * @param object $db       Conexión CI4 (para comparar con COUNT(*) actual).
     * @param string $backupID Identificador.
     * @param string|null $baseDir Carpeta contenedora (null = respaldos de la actualización).
     * @return array ['parts','tables','rows','bytes','ok','note']
     */
    static function verify($db, $backupID, $baseDir = null)
    {
        $dir      = self::dir($backupID, $baseDir);
        $manifest = self::manifest($backupID, $baseDir);
        if (empty($manifest['complete'])) {
            throw new \Exception('El respaldo no está completo.');
        }
        $errors = [];
        $notes  = [];
        $parts  = 0;
        foreach ([$manifest['schema'], $manifest['routines']] as $item) {
            $path = $dir . '/' . $item['file'];
            if (!is_file($path) || hash_file('sha256', $path) !== $item['sha256'] || @gzdecode((string) file_get_contents($path)) === false) {
                $errors[] = $item['file'] . ' dañado';
            }
            $parts++;
        }
        foreach ($manifest['tables'] as $table => $info) {
            $rows = 0;
            foreach ($info['parts'] as $part) {
                $parts++;
                $path = $dir . '/' . $part['file'];
                if (!is_file($path) || hash_file('sha256', $path) !== $part['sha256']) {
                    $errors[] = $part['file'] . ' (' . $table . ') con hash distinto';
                    continue;
                }
                $handle = gzopen($path, 'rb');
                if ($handle === false) {
                    $errors[] = $part['file'] . ' ilegible';
                    continue;
                }
                $found = 0;
                while (($line = gzgets($handle)) !== false) {
                    if (isset($line[0]) && $line[0] === '(') {
                        $found++;
                    }
                }
                gzclose($handle);
                if ($found !== (int) $part['rows']) {
                    $errors[] = $part['file'] . ' (' . $table . '): ' . $found . ' filas, se esperaban ' . $part['rows'];
                }
                $rows += $found;
            }
            if ($rows !== (int) $info['count']) {
                $notes[] = $table . ': respaldadas ' . $rows . ' filas, COUNT(*) al iniciar ' . $info['count'];
            }
        }
        $manifest['verified'] = count($errors) === 0;
        core_deploy_files::writeJson($dir . '/manifest.json', $manifest);
        return [
            'parts'  => $parts,
            'tables' => count($manifest['tables']),
            'rows'   => (int) $manifest['rows'],
            'bytes'  => (int) $manifest['bytes'],
            'ok'     => count($errors) === 0,
            'note'   => implode(' | ', array_slice(array_merge($errors, $notes), 0, 30)),
            'errors' => $errors,
        ];
    }

    /** Prepara la sesión MySQL para restaurar. */
    private static function prepareSession($mysqli)
    {
        $mysqli->query('SET FOREIGN_KEY_CHECKS = 0');
        $mysqli->query('SET UNIQUE_CHECKS = 0');
        $mysqli->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
    }

    /** Ejecuta un archivo pequeño (estructura o rutinas) dividido con core_deploy_sql_splitter. */
    private static function runSmallFile($mysqli, $path, $label)
    {
        $sql = @gzdecode((string) file_get_contents($path));
        if ($sql === false) {
            throw new \Exception('No se pudo leer ' . $label . ' del respaldo.');
        }
        $count = 0;
        foreach (core_deploy_sql_splitter::split($sql) as $statement) {
            if ($mysqli->query($statement['sql']) === false) {
                throw new \Exception($label . ', línea ' . $statement['line'] . ': ' . $mysqli->errno . ' ' . $mysqli->error);
            }
            $count++;
        }
        return $count;
    }

    /**
     * Restaura el respaldo por bloques: estructura → partes de datos → rutinas.
     *
     * @param object $db       Conexión CI4.
     * @param string $backupID Identificador.
     * @param bool   $restart  Reinicia la restauración desde el principio.
     * @param float  $deadline microtime(true) límite.
     * @param string|null $baseDir Carpeta contenedora (null = respaldos de la actualización).
     * @return array ['done' => bool, 'restored' => int, 'total' => int, 'phase' => string]
     */
    static function restoreStep($db, $backupID, $restart, $deadline, $baseDir = null)
    {
        $dir      = self::dir($backupID, $baseDir);
        $manifest = self::manifest($backupID, $baseDir);
        if (empty($manifest['complete'])) {
            throw new \Exception('El respaldo de BD no está completo; no se puede restaurar.');
        }
        $stateFile = $dir . '/restore.json';
        $state     = $restart ? null : core_deploy_files::readJson($stateFile);
        if (!$state) {
            $state = ['phase' => 'schema', 'table' => 0, 'part' => 0, 'restored' => 0];
        }
        $units = [];
        foreach ($manifest['order'] as $table) {
            $units[] = count($manifest['tables'][$table]['parts']);
        }
        $total  = array_sum($units) + 2;
        $mysqli = self::mysqli($db);
        self::prepareSession($mysqli);

        while ($state['phase'] !== 'done' && microtime(true) < $deadline) {
            if ($state['phase'] === 'schema') {
                self::runSmallFile($mysqli, $dir . '/' . $manifest['schema']['file'], 'estructura');
                $state['phase'] = 'data';
                $state['restored']++;
            } elseif ($state['phase'] === 'data') {
                if ($state['table'] >= count($manifest['order'])) {
                    $state['phase'] = 'routines';
                } else {
                    $table = $manifest['order'][$state['table']];
                    $parts = $manifest['tables'][$table]['parts'];
                    if ($state['part'] >= count($parts)) {
                        $state['table']++;
                        $state['part'] = 0;
                    } else {
                        $part = $parts[$state['part']];
                        $path = $dir . '/' . $part['file'];
                        if (!is_file($path) || hash_file('sha256', $path) !== $part['sha256']) {
                            throw new \Exception('La parte ' . $part['file'] . ' del respaldo está dañada.');
                        }
                        self::streamFile($mysqli, $path, $table);
                        $state['part']++;
                        $state['restored']++;
                    }
                }
            } elseif ($state['phase'] === 'routines') {
                self::runSmallFile($mysqli, $dir . '/' . $manifest['routines']['file'], 'rutinas');
                $state['phase'] = 'done';
                $state['restored']++;
            }
            core_deploy_files::writeJson($stateFile, $state);
        }
        $mysqli->query('SET FOREIGN_KEY_CHECKS = 1');
        $mysqli->query('SET UNIQUE_CHECKS = 1');
        return ['done' => $state['phase'] === 'done', 'restored' => (int) $state['restored'], 'total' => $total, 'phase' => $state['phase']];
    }

    /**
     * Ejecuta en streaming (gzgets) los INSERT de una parte de datos.
     *
     * @param \mysqli $mysqli Conexión.
     * @param string  $path   Archivo .sql.gz.
     * @param string  $table  Tabla (para mensajes).
     * @return int Sentencias ejecutadas.
     */
    private static function streamFile($mysqli, $path, $table)
    {
        $handle = gzopen($path, 'rb');
        if ($handle === false) {
            throw new \Exception('No se pudo abrir una parte del respaldo de ' . $table . '.');
        }
        $buffer = '';
        $count  = 0;
        try {
            while (($line = gzgets($handle)) !== false) {
                $buffer .= $line;
                // Cada fila ocupa una línea que termina en "," o ";" (terminador propio del respaldo)
                if (substr(rtrim($line, "\r\n"), -1) === ';' && isset($line[0]) && $line[0] === '(') {
                    $statement = rtrim(rtrim($buffer), ';');
                    if ($mysqli->query($statement) === false) {
                        throw new \Exception('Restauración de ' . $table . ': ' . $mysqli->errno . ' ' . $mysqli->error);
                    }
                    $buffer = '';
                    $count++;
                }
            }
        } finally {
            gzclose($handle);
        }
        if (trim($buffer) !== '') {
            throw new \Exception('Parte del respaldo de ' . $table . ' incompleta.');
        }
        return $count;
    }

    /**
     * Último respaldo completo y verificado (o null).
     *
     * @return string|null backupID
     */
    static function latestVerified()
    {
        $best = null;
        foreach (glob(self::backupsDir() . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
            $id = basename($dir);
            if (preg_match('/^[0-9]{14}_[a-f0-9]{8}$/', $id) !== 1) {
                continue;
            }
            $manifest = core_deploy_files::readJson($dir . '/manifest.json');
            if ($manifest && !empty($manifest['complete']) && !empty($manifest['verified']) && ($best === null || strcmp($id, $best) > 0)) {
                $best = $id;
            }
        }
        return $best;
    }
}
