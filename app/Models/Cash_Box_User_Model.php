<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Cash_Box_User_Model extends Model  
{
    protected $table      = 'tb_cash_box_user';
    protected $primaryKey = 'cashBoxUserID';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['cashBoxUserID', 'branchID','companyID','userID','cashBoxID','typeID','isPrimary','isActive'];

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
	
	public function deleteByUser($companyID,$userID)
	{	
		$db      = db_connect();
        $builder = $db->table("tb_cash_box_user");

        $data["isActive"] = 0;
        $builder->where("companyID", $companyID);
        $builder->where("userID", $userID);
        return $builder->update($data);
		
	}
	
	public function get_rowByUserID($companyID,$userID)
	{
		$db 	= db_connect();		    
		$sql = "";
		$sql = sprintf("
				select 
					c.companyID,
					c.branchID,
					c.cashBoxID,
					c.userID,
					c.typeID,
					c.cashBoxUserID,
					c.isPrimary,
					k.cashBoxCode,
					k.`name`,
					k.description,
					k.statusID,
					k.isActive 
				from 
					tb_cash_box_user c 
					inner join tb_cash_box k on 
						c.cashBoxID = k.cashBoxID 
				where 
					k.isActive = 1 and 
					c.isActive = 1 and 
					c.userID = ".$userID." and 
					k.companyID = ".$companyID." order by k.`name` ;  ");
						
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	
	public function get_rowByUserIDAndPrimary($companyID,$userID)
	{
		$db 	= db_connect();		    
		$sql = "";
		$sql = sprintf("
				select 
					c.companyID,
					c.branchID,
					c.cashBoxID,
					c.userID,
					c.typeID,
					c.cashBoxUserID,
					c.isPrimary,
					k.cashBoxCode,
					k.`name`,
					k.description,
					k.statusID,
					k.isActive 
				from 
					tb_cash_box_user c 
					inner join tb_cash_box k on 
						c.cashBoxID = k.cashBoxID 
				where 
					k.isActive = 1 and 
					c.isActive = 1 and 
					c.userID = ".$userID." and 
					c.isPrimary = 1 and 
					k.companyID = ".$companyID." order by k.`name` ;  ");
						
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	
   
}
?>