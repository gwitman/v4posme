<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Component_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function get_rowByName($name){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select componentID,name as componentName");
		$sql = $sql.sprintf(" from tb_component");
		$sql = $sql.sprintf(" where name = '$name' ");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }

    function get_findAll()
    {
        $db 	    = db_connect();
        $builder    = $db->table('tb_component');
        return $builder->get()->getResult();
    }

    function get_byPk($componentID)
    {
        $db 	    = db_connect();
        $builder    = $db->table('tb_component');
        return $builder->where(['componentID'=>$componentID])->get()->getRow();
    }
}
?>