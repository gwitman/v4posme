<?php
//posme:2026-11-12
namespace App\Models;
use CodeIgniter\Model;

/**
 * Skills (objetivos/intenciones) del agente de IA de WhatsApp por empresa.
 * Tabla tb_ai_skill: cada fila es un objetivo con su prompt, datos requeridos,
 * herramientas permitidas y la accion al completarse (derivar | atendida).
 */
class Ai_Skill_Model extends Model
{
    protected $table      = 'tb_ai_skill';
    protected $primaryKey = 'skillID';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Skills activas de la empresa ordenadas por orderNo.
     * @param int $companyID empresa
     * @return array
     */
    function get_rowByCompany($companyID)
    {
        $db  = db_connect();
        $sql = "";
        $sql = $sql.sprintf("SELECT ");
        $sql = $sql.sprintf("    s.skillID, ");
        $sql = $sql.sprintf("    s.companyID, ");
        $sql = $sql.sprintf("    s.code, ");
        $sql = $sql.sprintf("    s.name, ");
        $sql = $sql.sprintf("    s.description, ");
        $sql = $sql.sprintf("    s.prompt, ");
        $sql = $sql.sprintf("    s.requiredFields, ");
        $sql = $sql.sprintf("    s.tools, ");
        $sql = $sql.sprintf("    s.onComplete, ");
        $sql = $sql.sprintf("    s.entityIDEmployerHandoff, ");
        $sql = $sql.sprintf("    s.orderNo ");
        $sql = $sql.sprintf("FROM ");
        $sql = $sql.sprintf("    tb_ai_skill s ");
        $sql = $sql.sprintf("WHERE ");
        $sql = $sql.sprintf("    s.companyID = %d AND ", $companyID);
        $sql = $sql.sprintf("    s.isActive = 1 ");
        $sql = $sql.sprintf("ORDER BY ");
        $sql = $sql.sprintf("    s.orderNo, ");
        $sql = $sql.sprintf("    s.skillID");

        $db->query("SET NAMES utf8mb4");
        return $db->query($sql)->getResult();
    }

    /**
     * Una skill activa por su codigo.
     * @param int    $companyID empresa
     * @param string $code      codigo de la skill (general, saldo_cxc, soporte, prospecto...)
     * @return object|null
     */
    function get_rowByCode($companyID, $code)
    {
        $db  = db_connect();
        $sql = "";
        $sql = $sql.sprintf("SELECT * ");
        $sql = $sql.sprintf("FROM tb_ai_skill ");
        $sql = $sql.sprintf("WHERE companyID = %d ", $companyID);
        $sql = $sql.sprintf("AND code = %s ", $db->escape((string)$code));
        $sql = $sql.sprintf("AND isActive = 1 ");
        $sql = $sql.sprintf("LIMIT 1");

        $db->query("SET NAMES utf8mb4");
        return $db->query($sql)->getRow();
    }
}
