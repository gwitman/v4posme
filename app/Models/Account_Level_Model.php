<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Account_Level_Model extends Model  {
   function __construct(){
      parent::__construct();
   } 
  function delete_app_posme($companyID,$accountLevelID){
		$db 		= db_connect();	
		$builder	= $db->table("tb_account_level");
		
  		$data["isActive"] = 0;
		$builder->where("companyID",$companyID);
		$builder->where("accountLevelID",$accountLevelID);	
		return $builder->update($data);
		
   } 
   function update_app_posme($companyID,$accountLevelID,$data){
		$db 		= db_connect();	
		$builder	= $db->table("tb_account_level");
		
		$builder->where("companyID",$companyID);
		$builder->where("accountLevelID",$accountLevelID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_account_level");	
		
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
		 
   }
   function get_countInAccount($companyID,$accountLevelID){
		$db 		= db_connect();
		$builder	= $db->table("tb_account");
	    
		$builder->where("isActive",1);
		$builder->where("companyID",$companyID);
		$builder->where("accountLevelID",$accountLevelID);				
   		return $builder->countAllResults();
   }
   function getByCompany($companyID){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select companyID,accountLevelID,name,description,lengthTotal,split,lengthGroup,createdBy,createdAt,createdOn,createdIn,isActive,isOperative");
		$sql = $sql.sprintf(" from tb_account_level");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive = 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$accountLevelID){
		$db 		= db_connect();
		
		 
		$sql = "";
		$sql = sprintf("select companyID,accountLevelID,name,description,lengthTotal,split,lengthGroup,createdBy,createdAt,createdOn,createdIn,isActive,isOperative");
		$sql = $sql.sprintf(" from tb_account_level");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and accountLevelID = $accountLevelID");
		$sql = $sql.sprintf(" and isActive = 1");		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
  
}
?>