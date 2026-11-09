<?php
//posme:2026-11-06
namespace App\Models;
use CodeIgniter\Model;

/**
 * Consultas recurrentes guardadas del Constructor de Reportes.
 * Tabla: tb_report_builder.
 *
 * Regla de negocio: cada consulta pertenece a un usuario de una empresa. Los metodos
 * originales filtran por companyID, userID e isActive = 1, de modo que una consulta
 * ajena o eliminada se comporta igual que una inexistente.
 * Consultas compartidas (isShared = 1): visibles para todos los usuarios de la empresa
 * y editables por el dueno o por un administrador de la empresa (metodos *Editable* / *Visible*).
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
                r.isShared,
                r.viewTypeCatalogItemID,
                r.dashboardCatalogItemID,
                r.orden,
                r.displayJson,
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
                r.isShared,
                r.viewTypeCatalogItemID,
                r.dashboardCatalogItemID,
                r.orden,
                r.displayJson,
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
     *                    Opcionales: isShared, viewTypeCatalogItemID, dashboardCatalogItemID, orden, displayJson.
     * @return int reportBuilderID creado.
     */
    function insert_app_posme($data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder");
        $builder->insert($this->normalizeDashboardFields($data));
        return $db->insertID();
    }

    /**
     * Actualiza una consulta activa del usuario.
     *
     * @param int   $companyID       Empresa de la sesion.
     * @param int   $userID          Usuario de la sesion.
     * @param int   $reportBuilderID Consulta a actualizar.
     * @param array $data            Campos a cambiar (name, dataViewID, configJson, modifiedOn).
     *                               Opcionales: isShared, viewTypeCatalogItemID, dashboardCatalogItemID, orden, displayJson.
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
        return $builder->update($this->normalizeDashboardFields($data));
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

    /**
     * Obtiene una consulta activa que el usuario puede editar: la propia o, si es
     * administrador de la empresa, cualquier consulta compartida (isShared = 1).
     *
     * @param int  $companyID       Empresa de la sesion.
     * @param int  $userID          Usuario de la sesion.
     * @param int  $reportBuilderID Consulta a leer.
     * @param bool $isAdminCompany  true si el rol del usuario es administrador de la empresa.
     * @return object|null Fila de la consulta o null si no existe o no es editable.
     */
    function get_rowEditableByPK($companyID, $userID, $reportBuilderID, $isAdminCompany)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder AS r");
        $builder->select($this->selectColumnsFull());
        $builder->where("r.companyID", (int) $companyID);
        $builder->where("r.reportBuilderID", (int) $reportBuilderID);
        $builder->where("r.isActive", 1);
        $this->applyEditableFilter($builder, "r.", $userID, $isAdminCompany);

        return $builder->get()->getRow();
    }

    /**
     * Obtiene una consulta activa que el usuario puede ver/ejecutar: la propia o
     * cualquier consulta compartida (isShared = 1) de la empresa.
     *
     * @param int $companyID       Empresa de la sesion.
     * @param int $userID          Usuario de la sesion.
     * @param int $reportBuilderID Consulta a leer.
     * @return object|null Fila de la consulta o null si no existe o no es visible.
     */
    function get_rowVisibleByPK($companyID, $userID, $reportBuilderID)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder AS r");
        $builder->select($this->selectColumnsFull());
        $builder->where("r.companyID", (int) $companyID);
        $builder->where("r.reportBuilderID", (int) $reportBuilderID);
        $builder->where("r.isActive", 1);
        $builder->groupStart();
        $builder->where("r.userID", (int) $userID);
        $builder->orWhere("r.isShared", 1);
        $builder->groupEnd();

        return $builder->get()->getRow();
    }

    /**
     * Lista para "Mis consultas": las propias del usuario y, si es administrador de la
     * empresa, tambien las compartidas de otros usuarios. Sin configJson ni displayJson.
     *
     * @param int  $companyID      Empresa de la sesion.
     * @param int  $userID         Usuario de la sesion.
     * @param bool $isAdminCompany true si el rol del usuario es administrador de la empresa.
     * @return array Filas (objetos) ordenadas por nombre, con isOwner (1/0) y ownerNickname.
     */
    function get_rowsEditableByUser($companyID, $userID, $isAdminCompany)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder AS r");
        $builder->select("
            r.reportBuilderID,
            r.companyID,
            r.userID,
            r.dataViewID,
            r.name,
            r.isShared,
            r.viewTypeCatalogItemID,
            r.dashboardCatalogItemID,
            r.orden,
            r.createdOn,
            r.modifiedOn,
            u.nickname AS ownerNickname
        ");
        // isOwner se calcula con el usuario de la sesion (entero casteado, sin escape del builder).
        $builder->select("CASE WHEN r.userID = " . ((int) $userID) . " THEN 1 ELSE 0 END AS isOwner", false);
        $builder->join("tb_user AS u", "u.userID = r.userID AND u.companyID = r.companyID", "left");
        $builder->where("r.companyID", (int) $companyID);
        $builder->where("r.isActive", 1);
        $this->applyEditableFilter($builder, "r.", $userID, $isAdminCompany);
        $builder->orderBy("r.name", "ASC");

        return $builder->get()->getResult();
    }

    /**
     * Lista las consultas activas de un dashboard visibles para el usuario
     * (propias o compartidas), incluida su configuracion para poder ejecutarlas.
     *
     * @param int $companyID              Empresa de la sesion.
     * @param int $userID                 Usuario de la sesion.
     * @param int $dashboardCatalogItemID Dashboard (catalogo) a cargar.
     * @return array Filas (objetos) ordenadas por orden y reportBuilderID.
     */
    function get_rowsByDashboard($companyID, $userID, $dashboardCatalogItemID)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder AS r");
        $builder->select($this->selectColumnsFull());
        $builder->where("r.companyID", (int) $companyID);
        $builder->where("r.dashboardCatalogItemID", (int) $dashboardCatalogItemID);
        $builder->where("r.isActive", 1);
        $builder->groupStart();
        $builder->where("r.userID", (int) $userID);
        $builder->orWhere("r.isShared", 1);
        $builder->groupEnd();
        $builder->orderBy("r.orden", "ASC");
        $builder->orderBy("r.reportBuilderID", "ASC");

        return $builder->get()->getResult();
    }

    /**
     * Actualiza una consulta activa editable por el usuario (propia, o compartida si es
     * administrador de la empresa). No cambia el dueno (userID) de la consulta.
     *
     * @param int   $companyID       Empresa de la sesion.
     * @param int   $userID          Usuario de la sesion.
     * @param int   $reportBuilderID Consulta a actualizar.
     * @param bool  $isAdminCompany  true si el rol del usuario es administrador de la empresa.
     * @param array $data            Campos a cambiar (mismos que update_app_posme).
     * @return bool Resultado de la sentencia; es true aunque ninguna fila coincida.
     */
    function update_app_posme_editable($companyID, $userID, $reportBuilderID, $isAdminCompany, $data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder");
        $builder->where("companyID", (int) $companyID);
        $builder->where("reportBuilderID", (int) $reportBuilderID);
        $builder->where("isActive", 1);
        $this->applyEditableFilter($builder, "", $userID, $isAdminCompany);
        return $builder->update($this->normalizeDashboardFields($data));
    }

    /**
     * Elimina (soft delete) una consulta activa editable por el usuario (propia, o
     * compartida si es administrador de la empresa).
     *
     * @param int  $companyID       Empresa de la sesion.
     * @param int  $userID          Usuario de la sesion.
     * @param int  $reportBuilderID Consulta a eliminar.
     * @param bool $isAdminCompany  true si el rol del usuario es administrador de la empresa.
     * @return bool Resultado de la sentencia; es true aunque ninguna fila coincida.
     */
    function delete_app_posme_editable($companyID, $userID, $reportBuilderID, $isAdminCompany)
    {
        $db      = db_connect();
        $builder = $db->table("tb_report_builder");
        $builder->where("companyID", (int) $companyID);
        $builder->where("reportBuilderID", (int) $reportBuilderID);
        $builder->where("isActive", 1);
        $this->applyEditableFilter($builder, "", $userID, $isAdminCompany);
        return $builder->update(["isActive" => 0]);
    }

    /**
     * Regla de edicion: (userID = usuario) OR (administrador de la empresa AND isShared = 1).
     *
     * @param object $builder        Query Builder sobre tb_report_builder.
     * @param string $prefix         Alias de la tabla con punto ("r.") o "" si no hay alias.
     * @param int    $userID         Usuario de la sesion.
     * @param bool   $isAdminCompany true si el rol del usuario es administrador de la empresa.
     * @return void
     */
    private function applyEditableFilter($builder, $prefix, $userID, $isAdminCompany)
    {
        if (!$isAdminCompany) {
            $builder->where($prefix . "userID", (int) $userID);
            return;
        }

        $builder->groupStart();
        $builder->where($prefix . "userID", (int) $userID);
        $builder->orWhere($prefix . "isShared", 1);
        $builder->groupEnd();
    }

    /**
     * Columnas completas de una consulta (igual que get_rowByPK).
     *
     * @return string Lista de columnas con alias r.
     */
    private function selectColumnsFull()
    {
        return "
            r.reportBuilderID,
            r.companyID,
            r.userID,
            r.dataViewID,
            r.name,
            r.configJson,
            r.isShared,
            r.viewTypeCatalogItemID,
            r.dashboardCatalogItemID,
            r.orden,
            r.displayJson,
            r.createdBy,
            r.createdOn,
            r.createdIn,
            r.createdAt,
            r.modifiedOn,
            r.isActive
        ";
    }

    /**
     * Normaliza los campos de compartir/dashboard cuando vienen en el arreglo de datos.
     * Los que no vienen no se agregan, para no alterar llamadas existentes.
     *
     * @param array $data Datos a insertar o actualizar.
     * @return array Datos con isShared (0/1), enteros casteados y displayJson null si viene vacio.
     */
    private function normalizeDashboardFields($data)
    {
        if (array_key_exists("isShared", $data)) {
            $data["isShared"] = empty($data["isShared"]) ? 0 : 1;
        }

        foreach (["viewTypeCatalogItemID", "dashboardCatalogItemID", "orden"] as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = (int) $data[$field];
            }
        }

        if (array_key_exists("displayJson", $data) && ($data["displayJson"] === "" || $data["displayJson"] === false)) {
            $data["displayJson"] = null;
        }

        return $data;
    }
}
