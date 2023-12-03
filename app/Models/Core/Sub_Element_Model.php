<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Sub_Element_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }    
   function get_rowByNameAndElementID($elementID,$name){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.elementID,e.subElementID,e.name,e.workflowID,e.catalogID");
		$sql = $sql.sprintf(" from tb_subelement e");
		$sql = $sql.sprintf(" where e.name = '$name' ");
		$sql = $sql.sprintf(" and e.elementID = $elementID");		
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
}
?>