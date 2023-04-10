<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Component_Audit_Model extends Model  {
    function __construct(){		
      parent::__construct();
    }
    function insert_app_posme($data){	
		
		$db 		= db_connect();   
		$builder	= $db->table("tb_component_audit");
		
		$result			 	= $builder->insert($data);
		$id 				= $db->insertID(); 		
		return $id;
		
   }
}
?>