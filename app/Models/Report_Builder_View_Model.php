<?php
//posme:2026-11-06
namespace App\Models;
use CodeIgniter\Model;

/**
 * Catalogo de vistas curadas del Constructor de Reportes.
 * Tablas: tb_report_builder_view, tb_report_builder_view_field, tb_dataview.
 *
 * El catalogo es global (no lleva companyID), pero solo se exponen las vistas de tb_dataview con
 * callerID = 3 (REPORT_BUILDER) que la empresa tenga en tb_company_dataview con su flavorID exacto.
 * El permiso por requiredAddress lo valida el controlador.
 */
class Report_Builder_View_Model extends Model
{
    protected $table      = 'tb_report_builder_view';
    protected $primaryKey = 'reportBuilderViewID';

    /**
     * Lista las vistas activas del catalogo disponibles para la empresa y su flavor.
     *
     * @param int $companyID Empresa de la sesion.
     * @param int $flavorID  Flavor de la empresa.
     * @return array Filas (objetos) con dataViewID, label, description, requiredAddress y componentID, ordenadas por label.
     */
    function get_rowsActive($companyID, $flavorID)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                v.dataViewID,
                v.label,
                v.description,
                v.requiredAddress,
                d.componentID
            FROM tb_report_builder_view AS v
                INNER JOIN tb_dataview AS d ON d.dataViewID = v.dataViewID
            WHERE d.callerID = 3
                AND d.isActive = 1
                AND v.isActive = 1
                AND EXISTS (
                    SELECT 1
                    FROM tb_company_dataview AS c
                    WHERE c.companyID = %d
                        AND c.flavorID = %d
                        AND c.dataViewID = d.dataViewID
                        AND c.callerID = d.callerID
                        AND c.componentID = d.componentID
                        AND c.isActive = 1
                )
            ORDER BY
                v.label ASC
        ", $companyID, $flavorID);

        return $db->query($sql)->getResult();
    }

    /**
     * Obtiene una vista activa del catalogo disponible para la empresa y su flavor.
     *
     * @param int $dataViewID Vista a leer.
     * @param int $companyID  Empresa de la sesion.
     * @param int $flavorID   Flavor de la empresa.
     * @return object|null Fila con dataViewID, label, description, requiredAddress y componentID, o null si no esta disponible.
     */
    function get_rowByDataViewID($dataViewID, $companyID, $flavorID)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                v.dataViewID,
                v.label,
                v.description,
                v.requiredAddress,
                d.componentID
            FROM tb_report_builder_view AS v
                INNER JOIN tb_dataview AS d ON d.dataViewID = v.dataViewID
            WHERE v.dataViewID = %d
                AND d.callerID = 3
                AND d.isActive = 1
                AND v.isActive = 1
                AND EXISTS (
                    SELECT 1
                    FROM tb_company_dataview AS c
                    WHERE c.companyID = %d
                        AND c.flavorID = %d
                        AND c.dataViewID = d.dataViewID
                        AND c.callerID = d.callerID
                        AND c.componentID = d.componentID
                        AND c.isActive = 1
                )
        ", $dataViewID, $companyID, $flavorID);

        return $db->query($sql)->getRow();
    }

    /**
     * Lista los campos activos de una vista, incluidos los de costo (isCost = 1).
     * Quitar los campos de costo cuando no procedan le corresponde a quien llama.
     *
     * @param int $dataViewID Vista a consultar.
     * @return array Filas (objetos) ordenadas por orden.
     */
    function get_fieldsByDataViewID($dataViewID)
    {
        $db  = db_connect();
        $sql = sprintf("
            SELECT
                f.reportBuilderViewFieldID,
                f.dataViewID,
                f.fieldName,
                f.label,
                f.dataType,
                f.alignDefault,
                f.isCost,
                f.orden
            FROM tb_report_builder_view_field AS f
            WHERE f.dataViewID = %d
                AND f.isActive = 1
            ORDER BY
                f.orden ASC,
                f.reportBuilderViewFieldID ASC
        ", $dataViewID);

        return $db->query($sql)->getResult();
    }
}
