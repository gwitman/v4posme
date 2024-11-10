<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class ItemWarehouse_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function deleteWhereIDNotIn($companyID,$itemID,$listWarehouseID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		
		$builder->where("companyID",$companyID);
		$builder->where("itemID",$itemID);	
		$builder->whereNotIn("warehouseID",$listWarehouseID);	
		$builder->where("quantity",0);
		$builder->delete();
		
   }
   function update_app_posme($companyID,$itemID,$warehouseID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		
		$builder->where("companyID",$companyID);
		$builder->where("itemID",$itemID);	
		$builder->where("warehouseID",$warehouseID);				
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		$result = $builder->insert($data);
				
   }
   function getByWarehouse($companyID,$warehouseID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		$sql = "";
		$sql = sprintf("select i.itemNumber as CODIGO,i.name AS PRODUCTO,ci.display as UM,i.itemID,w.quantity,i.cost");
		$sql = $sql.sprintf(" from tb_item i");
		$sql = $sql.sprintf(" inner join  tb_item_warehouse w on i.itemID = w.itemID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item  ci on i.unitMeasureID = ci.catalogItemID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and w.warehouseID = $warehouseID");		
		$sql = $sql.sprintf(" and i.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowLowMinimus($companyID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		$sql = "";
		$sql = sprintf("select i.itemNumber,i.name as itemName,iw.quantity,iw.quantityMin,w.number as warehouseNumber,w.name as warehouseName");
		$sql = $sql.sprintf(" from tb_item_warehouse iw");
		$sql = $sql.sprintf(" inner join  tb_warehouse w on iw.warehouseID = w.warehouseID");
		$sql = $sql.sprintf(" inner join  tb_item i on i.itemID = iw.itemID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.isActive= 1");
		$sql = $sql.sprintf(" and w.isActive= 1");
		$sql = $sql.sprintf(" and iw.quantity < iw.quantityMin ");

		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByItemID($companyID,$itemID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.warehouseID, i.itemID, i.quantity, i.quantityMax, i.quantityMin,w.name as warehouseName");
		$sql = $sql.sprintf(" from tb_item_warehouse i");
		$sql = $sql.sprintf(" inner join  tb_warehouse w on i.warehouseID = w.warehouseID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByPK($companyID,$itemID,$warehouseID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		
		$sql = "";
		$sql = sprintf("select i.companyID, i.branchID, i.warehouseID, i.itemID, i.quantity, i.quantityMax, i.quantityMin,w.name as warehouseName");
		$sql = $sql.sprintf(" from tb_item_warehouse i");
		$sql = $sql.sprintf(" inner join  tb_warehouse w on i.warehouseID = w.warehouseID");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		$sql = $sql.sprintf(" and i.warehouseID = $warehouseID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function warehouseIsEmpty($companyID,$warehouseID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse");
		
		$sql = "";
		$sql = sprintf("select count(*) as counter");
		$sql = $sql.sprintf(" from tb_item_warehouse");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" where warehouseID = $warehouseID");
		$sql = $sql.sprintf(" where quantity > 0");		
   		return $db->query($sql)->getRow()->counter;
   }
  
}
?>