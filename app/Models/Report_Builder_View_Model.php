<?php
//posme:2026-11-06
namespace App\Models;
use CodeIgniter\Model;

/**
 * Catalogo de vistas curadas del Constructor de Reportes.
 * Tablas: tb_report_builder_view, tb_report_builder_view_field, tb_dataview.
 *
 * El catalogo es global (no lleva companyID). Solo se exponen las vistas de tb_dataview
 * con callerID = 3 (REPORT_BUILDER). El permiso por requiredAddress lo valida el controlador.
 */
class Report_Builder_View_Model extends Model
{
    protected $table      = 'tb_report_builder_view';
    protected $primaryKey = 'reportBuilderViewID';

    /**
     * Lista las vistas activas del catalogo.
     *
     * @return array Filas (objetos) con dataViewID, label, description, requiredAddress y componentID, ordenadas por label.
     */
    function get_rowsActive()
    {
        $db  = db_connect();
        $sql = "
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
            ORDER BY
                v.label ASC
        ";

        return $db->query($sql)->getResult();
    }

    /**
     * Obtiene una vista activa del catalogo.
     *
     * @param int $dataViewID Vista a leer.
     * @return object|null Fila con dataViewID, label, description, requiredAddress y componentID, o null si no esta en el catalogo activo.
     */
    function get_rowByDataViewID($dataViewID)
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
        ", $dataViewID);

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
