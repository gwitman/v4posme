<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Temp_Fingerprint_Model extends Model  {
  function __construct()
  {	
      parent::__construct();
  } 
  function delete_app_posme($tockenPc){
		$db 		= db_connect("biometric");
		$builder	= $db->table("temp_fingerprint");		
  		
		
		
		$builder->where("token_pc",$tockenPc);		
		return $builder->delete();
		
   } 
   function update_app_posme($tockenPc,$data){
		$db 		= db_connect("biometric");
		$builder	= $db->table("temp_fingerprint");	
		
		$builder->where("token_pc",$tockenPc);
		return $builder->update($data);
		
   }
   function update_app_posme_by_id($id,$data){
		$db 		= db_connect("biometric");
		$builder	= $db->table("temp_fingerprint");	
		
		$builder->where("id",$id);
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect("biometric");
		$builder	= $db->table("temp_fingerprint");	
		$result 	= $builder->insert($data);
		return true;	
		
		 
   }
   function get_ssejs($tokenPc)
   {
		$db 		= db_connect("biometric");	
		
		$sql = "";
		$sql = $sql.sprintf(" select id,token_pc,image,updated_at,user_id,`name`, `option`,finger_name  ");
		$sql = $sql.sprintf(" from temp_fingerprint ");
		$sql = $sql.sprintf(" where token_pc = '" . $tokenPc . "' ");
		$sql = $sql.sprintf(" and `option` = 'read'  ORDER BY updated_at DESC LIMIT 1");
		
   		return $db->query($sql)->getRow();
   }
   
    function get_ssejsByTocken($tokenPc)
   {
		$db 		= db_connect("biometric");	
		
		$sql = "";
		$sql = $sql.sprintf(" select id,token_pc,image,updated_at,user_id,`name`, `option`,finger_name  ");
		$sql = $sql.sprintf(" from temp_fingerprint ");
		$sql = $sql.sprintf(" where token_pc = '" . $tokenPc . "' ");
		$sql = $sql.sprintf("   ORDER BY updated_at DESC LIMIT 1");
		
   		return $db->query($sql)->getRow();
   }
   
   function get_ssejsByOptionIsNotNull($tokenPc)
   {
		$db 		= db_connect("biometric");	
		
		$sql = "";
		$sql = $sql.sprintf(" select id,token_pc,image,updated_at,user_id,`name`, `option`,created_at,finger_name  ");
		$sql = $sql.sprintf(" from temp_fingerprint ");
		$sql = $sql.sprintf(" where token_pc = '" . $tokenPc . "' ");
		$sql = $sql.sprintf(" and `option` is not null  ORDER BY id DESC LIMIT 1");
		
   		return $db->query($sql)->getRow();
   }
   
   
	
}
?>