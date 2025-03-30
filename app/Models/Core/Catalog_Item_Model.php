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
		$sql = sprintf("select e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID,e.ratio,e.reference1,e.reference2,e.reference3,e.reference4 ");
		$sql = $sql.sprintf(" from tb_catalog_item e");
		$sql = $sql.sprintf(" where e.catalogID = $catalogID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID ");	
		$sql = $sql.sprintf(" order by e.sequence ");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   
   function get_rowByCatalogIDAndReference1($catalogID, $reference1,$flavorID)
   {
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID,e.ratio,e.reference1,e.reference2,e.reference3,e.reference4 ");
		$sql = $sql.sprintf(" from tb_catalog_item e");
		$sql = $sql.sprintf(" where e.catalogID = $catalogID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID ");	
		$sql = $sql.sprintf(" and e.reference1 = '$reference1' ");	
		$sql = $sql.sprintf(" order by e.sequence ");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }

   function get_rowByCatalogIDAndFlavorID_Parent($catalogID,$flavorID,$parentCatalogItemID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID,e.ratio,e.reference1,e.reference2,e.reference3,e.reference4 ");
		$sql = $sql.sprintf(" from tb_catalog_item e");
		$sql = $sql.sprintf(" where e.catalogID = $catalogID");	
		$sql = $sql.sprintf(" and e.flavorID = $flavorID");	
		$sql = $sql.sprintf(" and e.parentCatalogItemID = $parentCatalogItemID");
		$sql = $sql.sprintf(" order by e.sequence ");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByCatalogItemID($catalogItemID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select e.catalogID,e.catalogItemID,e.name,e.display,e.flavorID,e.description,e.sequence,e.parentCatalogID,e.parentCatalogItemID,e.ratio,e.reference1,e.reference2,e.reference3,e.reference4 ");
		$sql = $sql.sprintf(" from tb_catalog_item e");
		$sql = $sql.sprintf(" where e.catalogItemID = $catalogItemID");	
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
   
}
?>