<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Cash_Box_Session_Model extends Model  
{
    protected $table      = 'tb_cash_box_session';
    protected $primaryKey = 'cashBoxSessionID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['cashBoxSessionID', 'companyID','branchID','cashBoxID','startOn','endOn','statusID','isActive','userID','transactionMasterID'];

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
	
   
}
?>