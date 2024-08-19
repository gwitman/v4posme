<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Catalog_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }    
   function get_rowByCatalogID($catalogID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.name,e.description,e.isActive,e.orden,e.publicCatalogSystemName");
		$sql = $sql.sprintf(" from tb_catalog e");
		$sql = $sql.sprintf(" where e.catalogID = $catalogID");	
		$sql = $sql.sprintf(" and e.isActive= 1");	
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
   function get_rowByName($name){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.name,e.description,e.isActive,e.orden,e.publicCatalogSystemName");
		$sql = $sql.sprintf(" from tb_catalog e");
		$sql = $sql.sprintf(" where e.name = '$name' ");	
		$sql = $sql.sprintf(" and e.isActive= 1");	
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
   
}
?>