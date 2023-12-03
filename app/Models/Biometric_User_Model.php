<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;


class Biometric_User_Model extends Model  {
  function __construct(){	
      parent::__construct();
  } 
   
  function delete_app_posme($userID){
		$db 		= db_connect("biometric");
		$builder	= $db->table("users");		
  		
		
		
		$builder->where("id",$userID);
		return $builder->delete();
		
   } 
  
   function insert_app_posme($data){
		$db 		= db_connect("biometric");
		$builder	= $db->table("users");	
		$result 	= $builder->insert($data);
		return $db->insertID();	
		
		 
   }   
	
}
?>