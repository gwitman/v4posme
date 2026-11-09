<?php
//posme:2026-11-09
namespace App\Libraries\core_web_deploy;

/**
 * Separa un script SQL en sentencias individuales.
 *
 * Necesario porque mysqli::multi_query se detiene en el primer error sin decir
 * en qué sentencia falló y no entiende DELIMITER. Respeta comillas simples,
 * dobles y backticks; comentarios '-- ', '#', '/* *\/' (los descarta) y
 * '/*! ... *\/' (los conserva, MySQL los ejecuta).
 */
class core_deploy_sql_splitter
{
    /**
     * Divide el script en sentencias.
     *
     * @param string $sql Script completo.
     * @return array Lista de ['sql' => sentencia sin delimitador, 'line' => línea donde inicia].
     */
    static function split($sql)
    {
        $sql        = str_replace(["\r\n", "\r"], "\n", (string) $sql);
        $length     = strlen($sql);
        $delimiter  = ';';
        $statements = [];
        $buffer     = '';
        $line       = 1;
        $startLine  = 1;
        $i          = 0;

        while ($i < $length) {
            $char = $sql[$i];
            $next = $i + 1 < $length ? $sql[$i + 1] : '';

            // DELIMITER al inicio de línea (comando del cliente mysql)
            if (($i === 0 || $sql[$i - 1] === "\n") && trim($buffer) === '' && preg_match('/^DELIMITER[ \t]+(\S+)[ \t]*(\n|$)/i', substr($sql, $i, 64), $match)) {
                $delimiter = $match[1];
                $i        += strlen($match[0]);
                $line++;
                $buffer    = '';
                continue;
            }

            // Comentario de línea: "-- " o "--" al final de línea, y "#"
            if (($char === '-' && $next === '-' && ($i + 2 >= $length || ctype_space($sql[$i + 2]))) || $char === '#') {
                $end = strpos($sql, "\n", $i);
                $i   = $end === false ? $length : $end;
                continue;
            }

            // Comentario de bloque (se conserva solo si es ejecutable /*! ... */)
            if ($char === '/' && $next === '*') {
                $end        = strpos($sql, '*/', $i + 2);
                $end        = $end === false ? $length : $end + 2;
                $comment    = substr($sql, $i, $end - $i);
                $line      += substr_count($comment, "\n");
                if (isset($comment[2]) && $comment[2] === '!') {
                    if (trim($buffer) === '') {
                        $startLine = $line;
                    }
                    $buffer .= $comment;
                } else {
                    $buffer .= ' ';
                }
                $i = $end;
                continue;
            }

            // Literales entre comillas o backticks
            if ($char === "'" || $char === '"' || $char === '`') {
                if (trim($buffer) === '') {
                    $startLine = $line;
                }
                $quote = $char;
                $j     = $i + 1;
                while ($j < $length) {
                    if ($sql[$j] === '\\' && $quote !== '`') {
                        $j += 2;
                        continue;
                    }
                    if ($sql[$j] === $quote) {
                        if ($j + 1 < $length && $sql[$j + 1] === $quote) {
                            $j += 2;
                            continue;
                        }
                        break;
                    }
                    $j++;
                }
                $literal = substr($sql, $i, $j - $i + 1);
                $line   += substr_count($literal, "\n");
                $buffer .= $literal;
                $i       = $j + 1;
                continue;
            }

            // Delimitador
            if (substr($sql, $i, strlen($delimiter)) === $delimiter) {
                self::push($statements, $buffer, $startLine);
                $buffer = '';
                $i     += strlen($delimiter);
                continue;
            }

            if ($char === "\n") {
                $line++;
            } elseif (trim($buffer) === '' && !ctype_space($char)) {
                $startLine = $line;
            }

            $buffer .= $char;
            $i++;
        }

        self::push($statements, $buffer, $startLine);
        return $statements;
    }

    /**
     * Agrega la sentencia a la lista si no está vacía.
     *
     * @param array  $statements Lista acumulada (por referencia).
     * @param string $buffer     Texto de la sentencia.
     * @param int    $line       Línea de inicio.
     * @return void
     */
    private static function push(array &$statements, $buffer, $line)
    {
        $text = trim($buffer);
        if ($text !== '') {
            $statements[] = ['sql' => $text, 'line' => $line];
        }
    }

    /**
     * Tipo de sentencia (primera palabra en mayúsculas).
     *
     * @param string $sql Sentencia.
     * @return string
     */
    static function type($sql)
    {
        if (preg_match('/^\s*(\/\*!\d*\s*)?([A-Za-z]+)/', $sql, $match)) {
            return strtoupper($match[2]);
        }
        return '';
    }

    /**
     * Indica si la sentencia es un UPDATE o DELETE sin WHERE (afecta toda la tabla).
     *
     * @param string $sql Sentencia.
     * @return bool
     */
    static function isUnboundedWrite($sql)
    {
        $type = self::type($sql);
        if ($type !== 'UPDATE' && $type !== 'DELETE') {
            return false;
        }
        $plain = preg_replace("/'(?:[^'\\\\]|\\\\.|'')*'/s", "''", $sql);
        return preg_match('/\bWHERE\b/i', $plain) !== 1;
    }

    /**
     * Indica si la sentencia es DDL (provoca COMMIT implícito en MySQL).
     *
     * @param string $sql Sentencia.
     * @return bool
     */
    static function isDdl($sql)
    {
        return in_array(self::type($sql), ['CREATE', 'ALTER', 'DROP', 'TRUNCATE', 'RENAME', 'GRANT', 'REVOKE', 'LOCK', 'UNLOCK'], true);
    }
}
