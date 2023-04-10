<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Catalog_Item_Model extends Model  {
   function __construct(){     
      parent::__construct();
   }    
   function get_rowByCatalogIDAndFlavorID($catalogID,$flavorID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID");
		$sql = $sql.sprintf(" from tb_catalog_item e");
		$sql = $sql.sprintf(" where e.catalogID = $catalogID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByCatalogIDAndFlavorID_Parent($catalogID,$flavorID,$parentCatalogItemID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID");
		$sql = $sql.sprintf(" from tb_catalog_item e");
		$sql = $sql.sprintf(" where e.catalogID = $catalogID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID");	
		$sql = $sql.sprintf(" and e.parentCatalogItemID = $parentCatalogItemID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByCatalogItemID($catalogItemID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID");
		$sql = $sql.sprintf(" from tb_catalog_item e");
		$sql = $sql.sprintf(" where e.catalogItemID = $catalogItemID");	
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
}
?>