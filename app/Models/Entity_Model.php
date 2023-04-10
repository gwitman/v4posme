<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Entity_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($companyID,$branchID,$entityID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity");
		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity");		 
 		
		$builder->where("companyID",$companyID);
		$builder->where("branchID",$branchID);	
		$builder->where("entityID",$entityID);
		$builder->delete();
		
   } 
   function insert_app_posme($data){
		$db 	        = db_connect();
		$builder	    = $db->table("tb_entity");
		
		$result			= $builder->insert($data);
		$autoIncrement	= $db->insertID(); 	
		return $autoIncrement;
		
   }
   function get_rowByPK($companyID,$branchID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity");    
		$sql = "";
		$sql = sprintf("select i.companyID,i.branchID,i.entityID,i.createdAt,i.createdOn,i.createdBy");
		$sql = $sql.sprintf(" from tb_entity i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.branchID = $branchID");
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
      function get_rowByEntity($companyID,$entityID){
		$db 	= db_connect();
		$builder	= $db->table("tb_entity");    
		$sql = "";
		$sql = sprintf("select i.companyID,i.branchID,i.entityID,i.createdAt,i.createdOn,i.createdBy");
		$sql = $sql.sprintf(" from tb_entity i");		
		$sql = $sql.sprintf(" where i.companyID = $companyID");		
		$sql = $sql.sprintf(" and i.entityID = $entityID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>