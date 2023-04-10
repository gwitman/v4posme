<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Account_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
  function delete_app_posme($companyID,$accountID){
		$db 		= db_connect();
		$builder	= $db->table("tb_account");		
  		$data["isActive"] = 0;
		
		
		$builder->where("companyID",$companyID);
		$builder->where("accountID",$accountID);	
		return $builder->update($data);
		
   } 
   function update_app_posme($companyID,$accountID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_account");	
		
		$builder->where("companyID",$companyID);
		$builder->where("accountID",$accountID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_account");	
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
		 
   }
   function get_isParent($companyID,$accountID){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = $sql.sprintf(" select count(*) as counter  ");
		$sql = $sql.sprintf(" from tb_account");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		$sql = $sql.sprintf(" and parentAccountID = $accountID");
		
   		return $db->query($sql)->getRow()->counter;
   }
   function get_countAccount($companyID){
		$db 		= db_connect();
		
		$sql = "";
		
		$sql = $sql.sprintf(" select count(*) as counter  ");
		$sql = $sql.sprintf(" from tb_account");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		
   		return $db->query($sql)->getRow()->counter;
		
   }
   function getByAccountNumber($accountNumber,$companyID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID,accountID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$sql = $sql.sprintf(" from tb_account");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and accountNumber = '$accountNumber' ");
		$sql = $sql.sprintf(" and isActive = 1");		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByPK($companyID,$accountID){
		$db 		= db_connect();
		    
		$sql = "";
		$sql = sprintf("select companyID,accountID,accountTypeID,accountLevelID,parentAccountID,classID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$sql = $sql.sprintf(" from tb_account");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and accountID = $accountID");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
	function getByCompany($companyID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID,accountID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$sql = $sql.sprintf(" from tb_account");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive = 1");		
		$sql = $sql.sprintf(" order by  accountNumber asc");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	function getByCompanyOperative($companyID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID,accountID,accountTypeID,accountLevelID,parentAccountID,accountNumber,name,description,isOperative,statusID,currencyID,createdBy,createdOn,createdIn,createdAt,isActive");
		$sql = $sql.sprintf(" from tb_account");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isActive = 1");				
		$sql = $sql.sprintf(" and isOperative = 1");		
		$sql = $sql.sprintf(" order by  accountNumber asc");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	
}
?>