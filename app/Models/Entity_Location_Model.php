<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Entity_Location_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
    function delete_app_posme($entityLocationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		$builder->where("entityLocationID",$entityLocationID);	
		$builder->delete();
		
   }
   function deleteByEntity($entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		
		$builder->where("entityID",$entityID);	
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($entityLocationID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");
		
		
		$builder->where("entityLocationID",$entityLocationID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($entityLocationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");    
		
		$sql = "";
		$sql = sprintf("select 
				c.entityLocationID,c.entityID,c.createdOn,
				c.isActive,c.longituded,c.latituded,c.reference1
			");
		$sql = $sql.sprintf(" from tb_entity_location c");		
		$sql = $sql.sprintf(" where c.entityLocationID = $entityLocationID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByEntity($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity_location");    
		
		$sql = "";
		$sql = sprintf("select 
				c.entityLocationID,c.entityID,c.createdOn,
				c.isActive,c.longituded,c.latituded,c.reference1
			");
		$sql = $sql.sprintf(" from tb_entity_location c");		
		$sql = $sql.sprintf(" where c.entityID = $entityID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_UsersLocation()
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				el.entityID AS Id, 
				el.reference1 AS Name, 
				el.latituded AS Latitude, 
				el.longituded AS Longitude
			FROM 
				tb_entity_location el
			WHERE 
				el.isActive = 1
				AND NOT EXISTS
				(
					SELECT 
						1
					FROM 
						tb_entity_location el2
					WHERE 
						el2.entityID = el.entityID
					AND 
						el2.isActive = 1
					AND 
						ABS(DATEDIFF(NOW(), el2.createdOn))	< ABS(DATEDIFF(NOW(), el.createdOn))
				)
			ORDER BY
				el.createdOn DESC;
		");

		return $db->query($sql)->getResult();
   }
   
}
?>