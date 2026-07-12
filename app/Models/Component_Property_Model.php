<?php 
//posme:2025-07-11
namespace App\Models;
use CodeIgniter\Model;

class Component_Property_Model extends Model  {
   function __construct(){
      parent::__construct();
   } 
   function get_rowByPK($propertyItemID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select propertyItemID,componentID,componentItemID,propertyID,name,descripcion,value,type,isActive");
		$sql = $sql.sprintf(" from tb_component_property");
		$sql = $sql.sprintf(" where propertyItemID = $propertyItemID");
		$sql = $sql.sprintf(" and isActive = 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByItemID($itemID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select propertyItemID,componentID,componentItemID,propertyID,name,descripcion,value,type,isActive");
		$sql = $sql.sprintf(" from tb_component_property");
		$sql = $sql.sprintf(" where componentItemID = $itemID");
		$sql = $sql.sprintf(" and isActive = 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByItemID_And_propertyID($itemID,$propertyID){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select propertyItemID,componentID,componentItemID,propertyID,name,descripcion,value,type,isActive");
		$sql = $sql.sprintf(" from tb_component_property");
		$sql = $sql.sprintf(" where componentItemID = $itemID");
		$sql = $sql.sprintf(" and isActive = 1");
        $sql = $sql.sprintf(" and propertyID = $propertyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_component_property");	
		
		$result 	= $builder->insert($data);
		return $db->insertID();		
   }
   function delete_app_posme($propertyItemID){
		$db 		= db_connect();	
		$builder	= $db->table("tb_component_property");
		
  		$data["isActive"] = 0;
		$builder->where("propertyItemID",$propertyItemID);
		return $builder->update($data);
   } 
   function update_app_posme($propertyItemID,$data){
		$db 		= db_connect();	
		$builder	= $db->table("tb_component_property");
		
		$builder->where("propertyItemID",$propertyItemID);
		return $builder->update($data);
   }
}
?>