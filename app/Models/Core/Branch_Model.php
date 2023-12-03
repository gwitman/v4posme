<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;



class Branch_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function delete_app_posme($companyID,$branchID){
		$db 		= db_connect();
		$builder	= $db->table("tb_branch");
		
		$data 				= array();
		$data["isActive"] 	= 0;
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);		
		
		return $builder->update($data);
   }
   function update_app_posme($companyID,$branchID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_branch");
		
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);		
		
		return $builder->update($data);
   }
   function insert_app_posme($companyID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_branch");
		
		$result 	= $builder->insert($data);
		return $db->insertID();		
   }
   
   function get_rowByPK($companyID,$branchID){
		$db 		= db_connect();  		
		$builder 	= $db->table('tb_branch');
		
		
		
		$builder->where('companyID', $companyID);
		$builder->where('branchID', $branchID);
		$builder->where('isActive', 1);
		$recordSet = $builder->get()->getRow();
		return $recordSet;
   }
   function getByCompany($companyID){
		$db 		= db_connect();		
		$builder 	= $db->table('tb_branch');
		
		$builder->where('companyID', $companyID);		
		$builder->where('isActive', 1);
		$recordSet = $builder->get()->getResult();
		return $recordSet;
		
   }
}
?>