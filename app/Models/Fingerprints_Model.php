<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Fingerprints_Model extends Model  {
   function __construct(){	
      parent::__construct();
   } 
  function delete_app_posme($companyID){
		$db 		= db_connect("biometric");
		$builder	= $db->table("fingerprints");		
  		
		
		
		$builder->where("companyID",$companyID);
		return $builder->delete();
		
   } 
   function update_app_posme($fingerID,$data){
		$db 		= db_connect("biometric");
		$builder	= $db->table("fingerprints");	
		
		$builder->where("id",$fingerID);
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect("biometric");
		$builder	= $db->table("fingerprints");	
		$result 	= $builder->insert($data);
		return $db->insertID();	
		
		 
   }   
   
   function getCount()
   {
	    $db 		= db_connect("biometric");
		
		$sql = "";
		$sql = sprintf("select count(*) as total from 	fingerprints ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
		
   }
   
   function getPagination($from)
   {
	    $db  = db_connect("biometric");		
		$sql = "";
		$sql = sprintf("
				select  
					f.user_id as  id , f.fingerprint, 'buscar nombre en la otra base' as  name  
				from 
					fingerprints f  				
				");
				
		
		/*
		$sql = sprintf("
				select  
					f.user_id as  id , f.fingerprint, 'buscar nombre en la otra base' as  name  
				from 
					fingerprints f  
				limit  
					$from, 2500 
				");
		*/
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
   function getByFingerId($id){
	   
	    $db 		= db_connect("biometric");
		
		$sql = "";
		$sql = sprintf("select 	f.user_id as user_id,f.fingerprint , f.id as finger_id, 'buscar nombre en la otra base' as name  ");
		$sql = $sql.sprintf(" from fingerprints f ");
		$sql = $sql.sprintf(" where f.id = $id ");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByUserIDAndNotifie($userID)
   {
		$db 		= db_connect("biometric");
		
		$sql = "";
		$sql = sprintf("select 	id , finger_name,image,fingerprint,notified,user_id,created_at,updated_at ");
		$sql = $sql.sprintf(" from fingerprints ");
		$sql = $sql.sprintf(" where user_id = $userID and notified = 0 ");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByUserID($userID)
   {
		$db 		= db_connect("biometric");
		
		$sql = "";
		$sql = sprintf("select 	id , finger_name,image,fingerprint,notified,user_id,created_at,updated_at ");
		$sql = $sql.sprintf(" from fingerprints ");
		$sql = $sql.sprintf(" where user_id = $userID");		
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
	}
	
}
?>