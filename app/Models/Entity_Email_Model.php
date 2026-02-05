<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Entity_Email_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
    function delete_app_posme($companyID,$branchID,$entityID,$entityEmailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_email");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		$builder->where("entityEmailID",$entityEmailID);	
		$builder->delete();
		
   }
   function deleteByEntity($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_email");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_email");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$branchID,$entityID,$entityEmailID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_email");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		$builder->where("entityEmailID",$entityEmailID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$branchID,$entityID,$entityEmailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_email");    
		
		$sql = "";
		$sql = sprintf("select tm.companyID,tm.branchID,tm.entityID,tm.entityEmailID,tm.email,tm.isPrimary");
		$sql = $sql.sprintf(" from tb_entity_email tm");		
		$sql = $sql.sprintf(" where tm.entityEmailID = $entityEmailID");
		$sql = $sql.sprintf(" and tm.entityID = $entityID");
		$sql = $sql.sprintf(" and tm.branchID = $branchID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEntity($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_email");    
		$sql = "";
		$sql = sprintf("select tm.companyID,tm.branchID,tm.entityID,tm.entityEmailID,tm.email,tm.isPrimary");
		$sql = $sql.sprintf(" from tb_entity_email tm");				
		$sql = $sql.sprintf(" where tm.entityID = $entityID");
		$sql = $sql.sprintf(" and tm.branchID = $branchID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByCompany($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_email");    
		$sql = "";
		$sql = sprintf("select tm.companyID,tm.branchID,tm.entityID,tm.entityEmailID,tm.email,tm.isPrimary");
		$sql = $sql.sprintf(" from tb_entity_email tm");				
		$sql = $sql.sprintf(" where ");
		$sql = $sql.sprintf(" ");
		$sql = $sql.sprintf(" 	tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByEmail($entityID,$email)
   {
	    $db 	= db_connect();
		$builder	= $db->table("tb_entity_email");    
		$sql = "";
		$sql = sprintf("select tm.companyID,tm.branchID,tm.entityID,tm.entityEmailID,tm.email,tm.isPrimary");
		$sql = $sql.sprintf(" from tb_entity_email tm");				
		$sql = $sql.sprintf(" where ");
		$sql = $sql.sprintf(" ");
		$sql = $sql.sprintf(" 	tm.entityID = $entityID and ");
		$sql = $sql.sprintf(" 	REGEXP_REPLACE(tm.email, '[\s\+\(\)]', '') = REGEXP_REPLACE('$email', '[\s\+\(\)]', '') ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
}
?>