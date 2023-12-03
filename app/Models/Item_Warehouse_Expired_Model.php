<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Item_Warehouse_Expired_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function deleteByPk($companyID,$itemWarehouseExpiredID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");		
		
		$builder->where("itemWarehouseExpiredID",$itemWarehouseExpiredID);	
		$builder->where("companyID",$companyID);
		$builder->delete();
		
   }
   function update_app_posme($companyID,$itemWarehouseExpiredID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");
		
		$builder->where("companyID",$companyID);
		$builder->where("itemWarehouseExpiredID",$itemWarehouseExpiredID);
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");
		$result 	= $builder->insert($data);
				
   }
   function getBy_ItemIDAndWarehouse($companyID,$warehouseID,$itemID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");
		$sql = "";
		$sql = sprintf("select i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$sql = $sql.sprintf(" from tb_item_warehouse_expired i");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.warehouseID = $warehouseID");		
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function getBy_ItemIDAndWarehouseAndLote($companyID,$warehouseID,$itemID,$lote){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");
		$sql = "";
		$sql = sprintf("select i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$sql = $sql.sprintf(" from tb_item_warehouse_expired i");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.warehouseID = $warehouseID");		
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		$sql = $sql.sprintf(" and i.lote = '$lote' ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function getBy_ItemIDAndWarehouseAndLoteAndExpired($companyID,$warehouseID,$itemID,$lote,$expired){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");
		$sql = "";
		$sql = sprintf("select i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$sql = $sql.sprintf(" from tb_item_warehouse_expired i");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.warehouseID = $warehouseID");		
		$sql = $sql.sprintf(" and i.itemID = $itemID");		
		$sql = $sql.sprintf(" and i.lote = '$lote'");
		$sql = $sql.sprintf(" and i.dateExpired = '$expired' ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getByPK($companyID,$itemWarehouseExpiredID){
		$db 	= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");
		$sql = "";
		$sql = sprintf("select i.itemWarehouseExpiredID,i.warehouseID,i.itemID,i.companyID,i.quantity,i.lote,i.dateExpired");
		$sql = $sql.sprintf(" from tb_item_warehouse_expired i");
		$sql = $sql.sprintf(" where i.companyID = $companyID");
		$sql = $sql.sprintf(" and i.itemWarehouseExpiredID = $itemWarehouseExpiredID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   //vencimiento de productos 6 meses antes
   function getBy_ItemIDAproxVencimiento($companyID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_warehouse_expired");
		
		$sql 		= "
			select 
				i.itemNumber,
				i.`name` as itemName,
				w.warehouseID as warehouseNumber,
				w.`name` warehouseName,
				e.quantity,
				e.lote,
				e.dateExpired 
			from 
				tb_item i 
				inner join tb_item_warehouse iw on 
					i.itemID = iw.itemID 
				inner join tb_warehouse w on 
					iw.warehouseID = w.warehouseID 
				inner join tb_item_warehouse_expired e on 
					e.itemID = i.itemID and 
					e.warehouseID = w.warehouseID 
			where 
				i.companyID = $companyID and 
				date_add(now() , interval 6 month ) > e.dateExpired
		";
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
  
}
?>