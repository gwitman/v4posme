<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Tag_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($tagID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_tag");
		
		$builder->where("tagID",$tagID);
		return $builder->update($data);
		
   }
   function delete_app_posme($tagID){
		$db 	= db_connect();
		$builder	= $db->table("tb_tag");		
  		$data["isActive"] = 0;
		
		$builder->where("tagID",$tagID);
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_tag");
		
		$result		= $builder->insert($data);
		return $result;
   }
   function get_rows(){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select tagID,name,description,sendEmail,sendNotificationApp,sendSMS,isActive");
		$sql = $sql.sprintf(" from tb_tag n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($tagID){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select tagID,name,description,sendEmail,sendNotificationApp,sendSMS,isActive");
		$sql = $sql.sprintf(" from tb_tag n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.tagID = $tagID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByName($name){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select tagID,name,description,sendEmail,sendNotificationApp,sendSMS,isActive");
		$sql = $sql.sprintf(" from tb_tag n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.name = '$name' ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>