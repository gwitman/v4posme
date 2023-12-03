<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Entity_Account_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($entityAccountID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_account");
		
		$builder->where("entityAccountID",$entityAccountID);
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_account");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }   
   function delete_app_posme($entityAccountID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_account");		
  		$data["isActive"] = 0;
		
		$builder->where("entityAccountID",$entityAccountID);
		return $builder->update($data);
		
   } 
   function get_rowByEntity($companyID,$componentID,$componentItemID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select entityAccountID, companyID, componentID, componentItemID, name, description, accountTypeID, currencyID, classID, balance, creditLimit, maxCredit, debitLimit, maxDebit, statusID, accountID, createdBy, createdOn, createdIn, createdAt,isActive");
		$sql = $sql.sprintf(" from tb_entity_account i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.componentID = $componentID");
		$sql = $sql.sprintf(" and i.componentItemID = $componentItemID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($entityAccountID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select entityAccountID, companyID, componentID, componentItemID, name, description, accountTypeID, currencyID, classID, balance, creditLimit, maxCredit, debitLimit, maxDebit, statusID, accountID, createdBy, createdOn, createdIn, createdAt,isActive");
		$sql = $sql.sprintf(" from tb_entity_account i");		
		$sql = $sql.sprintf(" where i.entityAccountID = $entityAccountID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByAccountID($companyID,$componentID,$componentItemID,$accountID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_account");    
		$sql = "";
		$sql = sprintf("select entityAccountID, companyID, componentID, componentItemID, name, description, accountTypeID, currencyID, classID, balance, creditLimit, maxCredit, debitLimit, maxDebit, statusID, accountID, createdBy, createdOn, createdIn, createdAt,isActive");
		$sql = $sql.sprintf(" from tb_entity_account i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.componentID = $componentID");
		$sql = $sql.sprintf(" and i.componentItemID = $componentItemID");
		$sql = $sql.sprintf(" and i.accountID = $accountID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>