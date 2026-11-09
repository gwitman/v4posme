<?php
//posme:2026-11-09
namespace App\Models\Core;
use CodeIgniter\Model;

/**
 * Perfiles de despliegue remoto por compañía (tabla tb_deploy_target).
 * Las contraseñas y el secreto del agente se guardan cifrados (core_deploy_crypto).
 */
class Deploy_Target_Model extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene un perfil por su llave.
     *
     * @param int $companyID      Compañía.
     * @param int $deployTargetID Identificador del perfil.
     * @return object|null
     */
    function get_rowByPK($companyID, $deployTargetID)
    {
        $db  = db_connect();
        $sql = "";
        $sql = $sql.sprintf("SELECT * ");
        $sql = $sql.sprintf("FROM tb_deploy_target ");
        $sql = $sql.sprintf("WHERE companyID = %d ", $companyID);
        $sql = $sql.sprintf("AND deployTargetID = %d ", $deployTargetID);
        $sql = $sql.sprintf("AND isActive = 1");
        return $db->query($sql)->getRow();
    }

    /**
     * Obtiene el perfil activo de un archivo de compañía.
     *
     * @param int    $companyID   Compañía de la sesión (consola).
     * @param string $companyFile Nombre del archivo en file_sql/clientes sin .sql.
     * @return object|null
     */
    function get_rowByCompanyFile($companyID, $companyFile)
    {
        $db      = db_connect();
        $builder = $db->table('tb_deploy_target');
        $builder->where('companyID', $companyID);
        $builder->where('companyFile', $companyFile);
        $builder->where('isActive', 1);
        return $builder->get()->getRow();
    }

    /**
     * Lista los perfiles activos de la compañía.
     *
     * @param int $companyID Compañía.
     * @return array
     */
    function get_rowByCompany($companyID)
    {
        $db      = db_connect();
        $builder = $db->table('tb_deploy_target');
        $builder->select('deployTargetID, companyFile, agentUrl, lastDeployID, lastDeployOn, lastDeployStatus');
        $builder->where('companyID', $companyID);
        $builder->where('isActive', 1);
        return $builder->get()->getResult();
    }

    /**
     * Inserta un perfil.
     *
     * @return int ID insertado.
     */
    function insert_app_posme($data)
    {
        $db      = db_connect();
        $builder = $db->table('tb_deploy_target');
        $builder->insert($data);
        return $db->insertID();
    }

    /**
     * Actualiza un perfil.
     *
     * @return bool
     */
    function update_app_posme($companyID, $deployTargetID, $data)
    {
        $db      = db_connect();
        $builder = $db->table('tb_deploy_target');
        $builder->where('companyID', $companyID);
        $builder->where('deployTargetID', $deployTargetID);
        return $builder->update($data);
    }

    /**
     * Baja lógica de un perfil.
     *
     * @return bool
     */
    function delete_app_posme($companyID, $deployTargetID)
    {
        return $this->update_app_posme($companyID, $deployTargetID, ['isActive' => 0]);
    }
}
