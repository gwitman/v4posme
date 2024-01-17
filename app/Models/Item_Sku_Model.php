<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Item_Sku_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function delete_app_posme($itemID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_sku");		
		
		$builder->where("itemID",$itemID);	
		$builder->delete();
		
   }
   function update_app_posme($itemID,$catalogItemID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_sku");
		
		
		$builder->where("itemID",$itemID);	
		$builder->where("catalogItemID",$catalogItemID);				
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_sku");
		$result 	= $builder->insert($data);
				
   }
   

   function get_rowByItemID($itemID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_sku");
		$sql = "";
		$sql = sprintf("select i.skuID, i.itemID,i.catalogItemID,i.value,w.display ");
		$sql = $sql.sprintf(" from tb_item_sku i");
		$sql = $sql.sprintf(" inner join  tb_catalog_item w on i.catalogItemID = w.catalogItemID");		
		$sql = $sql.sprintf(" where i.itemID = $itemID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByPK($itemID,$catalogItemID){
		$db 		= db_connect();
		$builder	= $db->table("tb_item_sku");
		
		$sql = "";
		$sql = sprintf("select i.skuID, i.itemID,i.catalogItemID,i.value,w.display");
		$sql = $sql.sprintf(" from tb_item_sku i");
		$sql = $sql.sprintf(" inner join  tb_catalog_item w on i.catalogItemID = w.catalogItemID");		
		$sql = $sql.sprintf(" where i.itemID = $itemID");		
		$sql = $sql.sprintf(" and i.catalogItemID = $catalogItemID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_rowByTransactionMasterID($companyID,$transactionMasterID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_item_sku");
		$sql = "";
		$sql = sprintf("select i.skuID, i.itemID,i.catalogItemID,i.value as Valor ,w.display as Sku ");
		$sql = $sql.sprintf(" from tb_transaction_master tm ");
		$sql = $sql.sprintf(" inner join tb_transaction_master_detail tmd on tm.transactionMasterID = tmd.transactionMasterID ");
		$sql = $sql.sprintf(" inner join  tb_item_sku i on i.itemID = tmd.componentItemID ");
		$sql = $sql.sprintf(" inner join  tb_catalog_item w on i.catalogItemID = w.catalogItemID");		
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
  
}
?>