<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class User_Tag_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function deleteByUser($userID){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_tag");
		
		$builder->where("userID",$userID);
		$builder->delete();
   }
   function delete_app_posme($tagID,$userID){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_tag");
		
		$builder->where("tagID",$tagID);
		$builder->where("userID",$userID);
		$builder->delete("tb_user_tag");
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_tag");
		$result	= $builder->insert($data);
		return $result;
   }
   function get_rowByUser($userID){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_tag");
		$sql = "";
		$sql = sprintf("select ut.tagID,ut.userID,ut.companyID,ut.branchID,u.userID,u.email,t.name,t.description,t.sendEmail,t.sendNotificationApp,t.sendSMS,t.isActive");
		$sql = $sql.sprintf(" from tb_user_tag ut");
		$sql = $sql.sprintf(" inner join  tb_user u on ut.userID = u.userID");
		$sql = $sql.sprintf(" inner join  tb_tag t on ut.tagID = t.tagID");
		$sql = $sql.sprintf(" where u.userID = $userID");	
		$sql = $sql.sprintf(" and t.isActive= 1");	
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByPK($tagID){
		$db 		= db_connect();
		$builder	= $db->table("tb_user_tag");    
		$sql = "";
		$sql = sprintf("select ut.tagID,ut.userID,ut.companyID,ut.branchID,u.userID,u.email");
		$sql = $sql.sprintf(" from tb_user_tag ut");
		$sql = $sql.sprintf(" inner join  tb_user u on ut.userID = u.userID");
		$sql = $sql.sprintf(" inner join  tb_tag t on ut.tagID = t.tagID");
		$sql = $sql.sprintf(" where ut.tagID = $tagID");		
		$sql = $sql.sprintf(" and t.isActive= 1 and u.isActive = 1 ");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>