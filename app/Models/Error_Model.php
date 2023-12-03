<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Error_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($errorID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");
		
		$builder->where("errorID",$errorID);
		return $builder->update($data);
		
   }
   function updateTagID($tagID,$companyID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");	
		
		$builder->where("tagID",$tagID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($errorID){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");	
		
  		$data["isActive"] = 0;
		$builder->where("errorID",$errorID);
		return $builder->update($data);
		
   } 
   
   function deleteByTagID($tagID,$companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");	
		
		$builder->where("tagID",$tagID);
		$builder->delete();
		
   } 
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_error");
		$result		= $builder->insert($data);
		return 		$db->insertID();	
   }
   function get_rowByPK($errorID){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");    
		$sql = "";
		$sql = sprintf("select errorID,tagID,notificated,message,isActive,isRead,createdOn,readOn");
		$sql = $sql.sprintf(" from tb_error n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.errorID = $errorID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByUser($userID){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");    
		$sql = "";
		$sql = sprintf("select n.errorID,n.tagID,n.notificated,n.message,n.isActive,n.isRead,n.createdOn,n.readOn,n.userID");
		$sql = $sql.sprintf(" from tb_error n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.userID = $userID");
		$sql = $sql.sprintf(" and n.isRead is null");
		$sql = $sql.sprintf(" limit 0,5");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }
   function get_rowByUserCount($userID){
		$db 		= db_connect();
		$builder	= $db->table("tb_error");    
		
		$sql = "";
		$sql = sprintf("select count(*) as counter");
		$sql = $sql.sprintf(" from tb_error n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.userID = $userID");
		$sql = $sql.sprintf(" and n.isRead is null ");				

		//contar filas
		return $db->query($sql)->getRow()->counter;
		
   }
   function get_rowByUserAllAndTagID($userID,$tagID){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");    
		$sql = "";
		$sql = sprintf("select n.errorID,n.tagID,n.notificated,n.message,n.isActive,n.isRead,n.createdOn,n.readOn,n.userID");
		$sql = $sql.sprintf(" from tb_error n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.userID = $userID");
		$sql = $sql.sprintf(" and n.isRead is null");
		$sql = $sql.sprintf(" and n.tagID = $tagID");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }
   function get_rowByUserAll($userID){
		$db 	= db_connect();
		$builder	= $db->table("tb_error");    
		$sql = "";
		$sql = sprintf("select n.errorID,n.tagID,n.notificated,n.message,n.isActive,n.isRead,n.createdOn,n.readOn,n.userID");
		$sql = $sql.sprintf(" from tb_error n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.userID = $userID");
		$sql = $sql.sprintf(" and n.isRead is null");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }
   function get_rowByMessageUser($userID,$message){
		$db 		= db_connect();
		$builder	= $db->table("tb_error");
		$sql = "";
		$sql = sprintf("select errorID,tagID,notificated,message,isActive,isRead,createdOn,readOn,n.userID");
		$sql = $sql.sprintf(" from tb_error n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		
		if ($userID == 0)
		$sql = $sql.sprintf(" and n.userID is null");
		else		
		$sql = $sql.sprintf(" and n.userID = $userID");
	
		$sql = $sql.sprintf(" and n.message = ". $db->escape($message) );
		$sql = $sql.sprintf(" limit 0,1 ");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
}
?>