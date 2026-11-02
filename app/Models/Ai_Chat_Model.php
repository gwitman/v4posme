<?php
//posme:2026-11-02
namespace App\Models;
use CodeIgniter\Model;

/**
 * Persistencia del asistente IA "Jordania":
 * conversaciones, mensajes, uso de tokens y auditoria de consultas.
 * Tablas: tb_ai_conversacion, tb_ai_mensaje, tb_ai_uso, tb_ai_auditoria_sql.
 */
class Ai_Chat_Model extends Model
{
    protected $table      = 'tb_ai_conversacion';
    protected $primaryKey = 'conversacionID';

    /**
     * Devuelve la conversacion activa del usuario o null si no tiene.
     *
     * @param int $companyID Empresa de la sesion.
     * @param int $userID    Usuario de la sesion.
     * @return object|null
     */
    function get_rowConversacionActiva($companyID, $userID)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                c.conversacionID,
                c.companyID,
                c.userID,
                c.createdOn,
                c.updatedOn
            FROM tb_ai_conversacion AS c
            WHERE c.companyID = %d
                AND c.userID = %d
                AND c.isActive = 1
            ORDER BY
                c.conversacionID DESC
            LIMIT 1
        ", $companyID, $userID);

        return $db->query($sql)->getRow();
    }

    /**
     * Crea una conversacion nueva.
     *
     * @param array $data companyID, userID, createdOn.
     * @return int conversacionID creado.
     */
    function insert_conversacion($data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_ai_conversacion");
        $builder->insert($data);
        return $db->insertID();
    }

    /**
     * Cierra (soft delete) todas las conversaciones activas del usuario.
     *
     * @param int $companyID Empresa de la sesion.
     * @param int $userID    Usuario de la sesion.
     * @return bool
     */
    function delete_conversacionesByUser($companyID, $userID)
    {
        $db      = db_connect();
        $builder = $db->table("tb_ai_conversacion");
        $builder->where("companyID", $companyID);
        $builder->where("userID", $userID);
        $builder->where("isActive", 1);
        return $builder->update(["isActive" => 0]);
    }

    /**
     * Marca la conversacion como actualizada.
     *
     * @param int    $conversacionID Conversacion.
     * @param string $fecha          Fecha y hora.
     * @return bool
     */
    function update_conversacionFecha($conversacionID, $fecha)
    {
        $db      = db_connect();
        $builder = $db->table("tb_ai_conversacion");
        $builder->where("conversacionID", $conversacionID);
        return $builder->update(["updatedOn" => $fecha]);
    }

    /**
     * Guarda un mensaje (pregunta del usuario o respuesta de Jordania).
     *
     * @param array $data conversacionID, companyID, userID, rol, contenidoJson, tokensIn, tokensOut, createdOn.
     * @return int mensajeID creado.
     */
    function insert_mensaje($data)
    {
        $db = db_connect();
        $db->query("SET NAMES utf8mb4");
        $builder = $db->table("tb_ai_mensaje");
        $builder->insert($data);
        return $db->insertID();
    }

    /**
     * Ultimos N mensajes de una conversacion en orden cronologico.
     * Valida companyID y userID para que nadie lea conversaciones ajenas.
     *
     * @param int $companyID      Empresa de la sesion.
     * @param int $userID         Usuario de la sesion.
     * @param int $conversacionID Conversacion.
     * @param int $limite         Cantidad maxima de mensajes.
     * @return array
     */
    function get_rowMensajes($companyID, $userID, $conversacionID, $limite)
    {
        $db = db_connect();
        $db->query("SET NAMES utf8mb4");
        $sql = sprintf("
            SELECT
                x.mensajeID,
                x.rol,
                x.contenidoJson,
                x.createdOn
            FROM (
                SELECT
                    m.mensajeID,
                    m.rol,
                    m.contenidoJson,
                    m.createdOn
                FROM tb_ai_mensaje AS m
                WHERE m.conversacionID = %d
                    AND m.companyID = %d
                    AND m.userID = %d
                ORDER BY
                    m.mensajeID DESC
                LIMIT %d
            ) AS x
            ORDER BY
                x.mensajeID ASC
        ", $conversacionID, $companyID, $userID, $limite);

        return $db->query($sql)->getResult();
    }

    /**
     * Cantidad de preguntas del usuario en los ultimos N segundos (limite de frecuencia).
     *
     * @param int    $companyID Empresa de la sesion.
     * @param int    $userID    Usuario de la sesion.
     * @param string $desde     Fecha y hora desde la cual contar.
     * @return int
     */
    function get_countPreguntasDesde($companyID, $userID, $desde)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                COUNT(*) AS total
            FROM tb_ai_mensaje AS m
            WHERE m.companyID = %d
                AND m.userID = %d
                AND m.rol = 'user'
                AND m.createdOn >= %s
        ", $companyID, $userID, $db->escape($desde));

        $row = $db->query($sql)->getRow();
        return $row ? (int) $row->total : 0;
    }

    /**
     * Suma los tokens usados en una llamada a la API (incluidas las del bucle de herramientas).
     *
     * @param int    $companyID Empresa de la sesion.
     * @param int    $userID    Usuario de la sesion.
     * @param string $periodo   YYYY-MM.
     * @param array  $usage     input_tokens, output_tokens, cache_read_input_tokens, cache_creation_input_tokens.
     * @param int    $solicitudes Preguntas a sumar (1 solo en la primera llamada de la pregunta).
     * @param string $fecha     Fecha y hora.
     * @return bool
     */
    function upsert_uso($companyID, $userID, $periodo, $usage, $solicitudes, $fecha)
    {
        $db  = db_connect();
        $sql = sprintf("
            INSERT INTO tb_ai_uso (companyID, userID, periodo, inputTokens, outputTokens, cacheReadTokens, cacheWriteTokens, solicitudes, updatedOn)
            VALUES (%d, %d, %s, %d, %d, %d, %d, %d, %s)
            ON DUPLICATE KEY UPDATE
                inputTokens      = inputTokens + VALUES(inputTokens),
                outputTokens     = outputTokens + VALUES(outputTokens),
                cacheReadTokens  = cacheReadTokens + VALUES(cacheReadTokens),
                cacheWriteTokens = cacheWriteTokens + VALUES(cacheWriteTokens),
                solicitudes      = solicitudes + VALUES(solicitudes),
                updatedOn        = VALUES(updatedOn)
        ",
            $companyID,
            $userID,
            $db->escape($periodo),
            (int) ($usage["input_tokens"] ?? 0),
            (int) ($usage["output_tokens"] ?? 0),
            (int) ($usage["cache_read_input_tokens"] ?? 0),
            (int) ($usage["cache_creation_input_tokens"] ?? 0),
            $solicitudes,
            $db->escape($fecha)
        );

        return $db->query($sql);
    }

    /**
     * Tokens consumidos en el periodo (empresa completa o un usuario).
     * Cuenta entrada + salida + escritura de cache; la lectura de cache se registra pero no descuenta cuota.
     *
     * @param int      $companyID Empresa de la sesion.
     * @param string   $periodo   YYYY-MM.
     * @param int|null $userID    Usuario o null para toda la empresa.
     * @return int
     */
    function get_tokensPeriodo($companyID, $periodo, $userID = null)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                IFNULL(SUM(u.inputTokens + u.outputTokens + u.cacheWriteTokens), 0) AS total
            FROM tb_ai_uso AS u
            WHERE u.companyID = %d
                AND u.periodo = %s
        ", $companyID, $db->escape($periodo));

        if ($userID !== null) {
            $sql = $sql.sprintf(" AND u.userID = %d ", $userID);
        }

        $row = $db->query($sql)->getRow();
        return $row ? (int) $row->total : 0;
    }

    /**
     * Consumo del periodo por usuario de la empresa (endpoint usage para administradores).
     *
     * @param int    $companyID Empresa de la sesion.
     * @param string $periodo   YYYY-MM.
     * @return array
     */
    function get_rowUsoPorUsuario($companyID, $periodo)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                u.userID,
                IFNULL(usr.nickname, '') AS usuario,
                u.periodo,
                u.inputTokens,
                u.outputTokens,
                u.cacheReadTokens,
                u.cacheWriteTokens,
                u.solicitudes,
                u.updatedOn
            FROM tb_ai_uso AS u
                LEFT JOIN tb_user AS usr ON usr.userID = u.userID
            WHERE u.companyID = %d
                AND u.periodo = %s
            ORDER BY
                (u.inputTokens + u.outputTokens) DESC
        ", $companyID, $db->escape($periodo));

        return $db->query($sql)->getResult();
    }

    /**
     * Registra una herramienta o consulta SQL ejecutada por la IA.
     *
     * @param array $data companyID, userID, herramienta, sqlParametros, filas, ms, error, createdOn.
     * @return int auditoriaID creado.
     */
    function insert_auditoria($data)
    {
        $db = db_connect();
        $db->query("SET NAMES utf8mb4");
        $builder = $db->table("tb_ai_auditoria_sql");
        $builder->insert($data);
        return $db->insertID();
    }
}
