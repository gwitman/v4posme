<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Entity_Phone_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function delete_app_posme($companyID,$branchID,$entityID,$entityPhoneID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_phone");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		$builder->where("entityPhoneID",$entityPhoneID);	
		$builder->delete();
		
   }
   function deleteByEntity($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_phone");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);			
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_phone");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$branchID,$entityID,$entityPhoneID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_phone");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		$builder->where("entityPhoneID",$entityPhoneID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$branchID,$entityID,$entityPhoneID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_phone");    
		$sql = "";
		$sql = sprintf("select tm.companyID,tm.branchID,tm.entityID,tm.entityPhoneID,tm.typeID,ci.name as typeIDDescription,tm.number,tm.isPrimary");
		$sql = $sql.sprintf(" from tb_entity_phone tm");		
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci on tm.typeID = ci.catalogItemID");
		$sql = $sql.sprintf(" where tm.entityPhoneID = $entityPhoneID");
		$sql = $sql.sprintf(" and tm.entityID = $entityID");
		$sql = $sql.sprintf(" and tm.branchID = $branchID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEntity($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_phone");    
		$sql = "";
		$sql = sprintf("select tm.companyID,tm.branchID,tm.entityID,tm.entityPhoneID,tm.typeID,ci.name as typeIDDescription,tm.number,tm.isPrimary");
		$sql = $sql.sprintf(" from tb_entity_phone tm");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci on tm.typeID = ci.catalogItemID");
		$sql = $sql.sprintf(" where tm.entityID = $entityID");
		$sql = $sql.sprintf(" and tm.branchID = $branchID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>