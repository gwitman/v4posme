<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;



class Bank_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function delete_app_posme($companyID,$bankID){
		$db 		= db_connect();
		$builder	= $db->table("tb_bank");
		
		$data 				= array();
		$data["isActive"] 	= 0;
		$builder->where("companyID",$companyID);
		$builder->where("bankID",$bankID);		
		
		return $builder->update($data);
   }
   function update_app_posme($companyID,$bankID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_bank");
		
		
		$builder->where("companyID",$companyID);
		$builder->where("bankID",$bankID);		
		
		return $builder->update($data);
   }
   function insert_app_posme($companyID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_bank");
		
		$result 	= $builder->insert($data);
		return $db->insertID();		
   }
   
   function get_rowByPK($companyID,$bankID){
		$db 		= db_connect();  		
		$builder 	= $db->table('tb_bank');
		
		
		
		$builder->where('companyID', $companyID);
		$builder->where('bankID', $bankID);
		$builder->where('isActive', 1);
		$recordSet = $builder->get()->getRow();
		return $recordSet;
   }
   function getByCompany($companyID){
		$db 		= db_connect();		
		$builder 	= $db->table('tb_bank');
		
		$builder->where('companyID', $companyID);		
		$builder->where('isActive', 1);
		$recordSet = $builder->get()->getResult();
		return $recordSet;
		
   }
}
?>