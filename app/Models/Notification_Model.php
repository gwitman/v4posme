<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Notification_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function update_app_posme($notificationID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");
		
		$builder->where("notificationID",$notificationID);
		return $builder->update($data);
		
   }
   function delete_app_posme($notificationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_notification");		
  		$data["isActive"] = 0;
		
		$builder->where("notificationID",$notificationID);
		return $builder->update($data);
		
   } 
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_notification");
		$result		= $builder->insert($data);
		return $result;
   }
   function get_rowByPK($notificationID){
		$db 	= db_connect();
		$builder	= $db->table("tb_notification");    
		$sql = "";
		$sql = sprintf("select notificationID,errorID,from,to,subject,message,summary,title,tagID,createdOn,sendOn,isActive");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");		
		$sql = $sql.sprintf(" and n.notificationID = $notificationID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rows($top){
		$db 	= db_connect();
		$builder	= $db->table("tb_notification");    
		$sql = "";
		$sql = sprintf("select notificationID,errorID,from,to,subject,message,summary,title,tagID,createdOn,sendOn,isActive");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and n.sendOn is null");
		$sql = $sql.sprintf(" limit 0,$top ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowsByToMessage($to,$message){
		$db 	= db_connect();
		    
		$sql = "";
		$sql = sprintf("select notificationID,errorID,from,to,subject,message,summary,title,tagID,createdOn,sendOn,isActive");
		$sql = $sql.sprintf(" from tb_notification n");
		$sql = $sql.sprintf(" where n.isActive= 1");
		$sql = $sql.sprintf(" and n.to = '$to' ");
		$sql = $sql.sprintf(" and n.message = '$message' ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();	
   }
   
}
?>