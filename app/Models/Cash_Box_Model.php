<?php
//posme:2023-02-27
namespace App\Models;

use CodeIgniter\Model;

class Cash_Box_Model extends Model
{
    protected $table      = 'tb_cash_box';
    protected $primaryKey = 'cashBoxID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields  = ['cashBoxID', 'companyID', 'branchID', 'cashBoxCode', 'name', 'description', 'statusID', 'isActive'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function insert_app_posme($data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_cash_box");
        $result  = $builder->insert($data);
        return $db->insertID();
    }

    public function delete_app_posme($companyID, $cashBoxID)
    {
        $db      = db_connect();
        $builder = $db->table("tb_cash_box");

        $data["isActive"] = 0;
        $builder->where("companyID", $companyID);
        $builder->where("cashBoxID", $cashBoxID);
        return $builder->update($data);
    }

    public function update_app_posme($companyID, $cashBoxID, $data)
    {
        $db      = db_connect();
        $builder = $db->table("tb_cash_box");

        $builder->where("companyID", $companyID);
        $builder->where("cashBoxID", $cashBoxID);
        return $builder->update($data);
    }

    public function get_rowByPK($companyID, $cashBoxID)
    {
        $db      = db_connect();
        $builder = $db->table("tb_cash_box");
        $filtros = ['companyID' => $companyID, 'cashBoxID' => $cashBoxID, 'isActive' => 1];
        $builder = $builder->where($filtros);
        return $builder->get()->getRow();
    }

    public function get_All($companyID)
    {
        $db      = db_connect();
        $builder = $db->table("tb_cash_box");
        $filtros = ['companyID' => $companyID, 'isActive' => 1];
        $builder = $builder->where($filtros);
        return $builder->get()->getResultObject();
    }
}
