<?php
//posme:2026-11-06
namespace App\Models;
use CodeIgniter\Model;

/**
 * Consultas recurrentes guardadas del Constructor de Reportes.
 * Tabla: tb_report_builder.
 *
 * Regla de negocio: cada consulta pertenece a un usuario de una empresa. Todos los
 * metodos filtran por companyID, userID e isActive = 1, de modo que una consulta
 * ajena o eliminada se comporta igual que una inexistente.
 */
class Report_Builder_Model extends Model
{
    protected $table      = 'tb_report_builder';
    protected $primaryKey = 'reportBuilderID';

    /**
     * Lista las consultas activas del usuario, sin configJson para no cargar la configuracion completa.
     *
     * @param int $companyID Empresa de la sesion.
     * @param int $userID    Usuario de la sesion.
     * @return array Filas (objetos) ordenadas por nombre.
     */
    function get_rowsByUser($companyID, $userID)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                r.reportBuilderID,
                r.companyID,
                r.userID,
                r.dataViewID,
                r.name,
                r.createdOn,
                r.modifiedOn
            FROM tb_report_builder AS r
            WHERE r.companyID = %d
                AND r.userID = %d
                AND r.isActive = 1
            ORDER BY
                r.name ASC
        ", $companyID, $userID);

        return $db->query($sql)->getResult();
    }

    /**
     * Obtiene una consulta activa del usuario, incluida su configuracion.
     *
     * @param int $companyID       Empresa de la sesion.
     * @param int $userID          Usuario de la sesion.
     * @param int $reportBuilderID Consulta a leer.
     * @return object|null Fila de la consulta o null si no existe o es ajena.
     */
    function get_rowByPK($companyID, $userID, $reportBuilderID)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                r.reportBuilderID,
                r.companyID,
                r.userID,
                r.dataViewID,
                r.name,
                r.configJson,
                r.createdBy,
                r.createdOn,
                r.createdIn,
                r.createdAt,
                r.modifiedOn,
                r.isActive
            FROM tb_report_builder AS r
            WHERE r.companyID = %d
                AND r.userID = %d
                AND r.reportBuilderID = %d
                AND r.isActive = 1
        ", $companyID, $userID, $reportBuilderID);

        return $db->query($sql)->getRow();
    }

    /**
     * Indica si el usuario ya tiene una consulta activa visible con ese nombre. Las consultas de vistas
     * no disponibles estan ocultas para el usuario, por eso no reservan el nombre.
     * La comparacion sigue la collation de la columna name.
     *
     * @param int    $companyID Empresa de la sesion.
     * @param int    $userID    Usuario de la sesion.
     * @param string $name      Nombre a comprobar.
     * @param int    $excludeID Consulta a excluir (la que se esta editando); 0 no excluye ninguna.
     * @param array  $viewIDs   Vistas disponibles para el usuario (enteros).
     * @return bool true si el nombre ya esta en uso.
     */
    function existsName($companyID, $userID, $name, $excludeID, array $viewIDs)
    {
        if (empty($viewIDs)) {
            return false;
        }

        $db  = db_connect();
        $sql = sprintf("
            SELECT
                COUNT(*) AS total
            FROM tb_report_builder AS r
            WHERE r.companyID = %d
                AND r.userID = %d
                AND r.reportBuilderID <> %d
                AND r.isActive = 1
                AND r.dataViewID IN ?
                AND r.name = ?
        ", $companyID, $userID, $excludeID);

        $row = $db->query($sql, [array_map('intval', $viewIDs), $name])->getRow();
        return $row ? ((int) $row->total) > 0 : false;
    }

    /**
     * Crea una consulta.
     *
     * @param array $data companyID, userID, dataViewID, name, configJson, createdBy, createdOn, createdIn, createdAt.
     * @return int reportBuilderID creado.
     */
    function insert_app_posme($data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder");
        $builder->insert($data);
        return $db->insertID();
    }

    /**
     * Actualiza una consulta activa del usuario.
     *
     * @param int   $companyID       Empresa de la sesion.
     * @param int   $userID          Usuario de la sesion.
     * @param int   $reportBuilderID Consulta a actualizar.
     * @param array $data            Campos a cambiar (name, dataViewID, configJson, modifiedOn).
     * @return bool Resultado de la sentencia; es true aunque ninguna fila coincida.
     */
    function update_app_posme($companyID, $userID, $reportBuilderID, $data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder");
        $builder->where("companyID", $companyID);
        $builder->where("userID", $userID);
        $builder->where("reportBuilderID", $reportBuilderID);
        $builder->where("isActive", 1);
        return $builder->update($data);
    }

    /**
     * Elimina una consulta activa del usuario (soft delete).
     *
     * @param int $companyID       Empresa de la sesion.
     * @param int $userID          Usuario de la sesion.
     * @param int $reportBuilderID Consulta a eliminar.
     * @return bool Resultado de la sentencia; es true aunque ninguna fila coincida.
     */
    function delete_app_posme($companyID, $userID, $reportBuilderID)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder");
        $builder->where("companyID", $companyID);
        $builder->where("userID", $userID);
        $builder->where("reportBuilderID", $reportBuilderID);
        $builder->where("isActive", 1);
        return $builder->update(["isActive" => 0]);
    }
}
