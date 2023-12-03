<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Relationship_Model extends Model  {
   function __construct(){		
      parent::__construct();
   } 
  function delete_app_posme($employeeID,$customerID){
		$db 	= db_connect();
		$builder	= $db->table("tb_relationship");	
		
		$builder->where("employeeID",$employeeID);
		$builder->where("customerID",$customerID);	
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 			= db_connect();
		$builder		= $db->table("tb_relationship");	
		
		$result			= $builder->insert($data);
		$autoIncrement	= $db->insertID(); 		
		
   }
}
?>