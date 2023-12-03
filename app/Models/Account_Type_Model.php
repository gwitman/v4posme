<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Account_Type_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
  function delete_app_posme($companyID,$accountTypeID){
		$db 		= db_connect();
		$builder	= $db->table("tb_account_type");		
  		$data["isActive"] = 0;
		$builder->where("companyID",$companyID);
		$builder->where("accountTypeID",$accountTypeID);	
		return $builder->update($data);
		
   } 
   function update_app_posme($companyID,$accountTypeID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_account_type");		
		$builder->where("companyID",$companyID);
		$builder->where("accountTypeID",$accountTypeID);	
		
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_account_type");	
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
		 
   }
   function get_countInAccount($companyID,$accountTypeID){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select count(*) as counter ");	
		$sql = $sql.sprintf(" from tb_account");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		$sql = $sql.sprintf(" and accountTypeID = $accountTypeID");
		
   		return $db->query($sql)->getRow()->counter;
   }
   function getByCompany($companyID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID,accountTypeID,name,naturaleza,description,createdBy,createdAt,createdOn,createdIn,isActive");	
		$sql = $sql.sprintf(" from tb_account_type");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive = 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($companyID,$accountTypeID){
		$db 		= db_connect();
		    
		
		$sql = "";
		$sql = sprintf("select companyID,accountTypeID,name,naturaleza,description,createdBy,createdAt,createdOn,createdIn,isActive");
		$sql = $sql.sprintf(" from tb_account_type");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and accountTypeID = $accountTypeID");
		$sql = $sql.sprintf(" and isActive = 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
  
}
?>