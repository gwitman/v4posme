<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Master_Detail_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
		
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);	
		$builder->where("transactionMasterDetailID",$transactionMasterDetailID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$componentID=33){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");    
   
		if($componentID == 33 /*33 component:tb_item*/){
	
			$sql = "";
			$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,i.itemNumber,i.name as itemName,ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference,td.lote , td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku ");
			$sql = $sql.sprintf(" from tb_transaction_master_detail td");
			$sql = $sql.sprintf(" inner join  tb_item i on td.companyID = i.companyID and td.componentItemID = i.itemID");
			$sql = $sql.sprintf(" inner join  tb_catalog_item ci on i.unitMeasureID = ci.catalogItemID");
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
			$sql = $sql.sprintf(" and td.transactionMasterDetailID = $transactionMasterDetailID");		
			$sql = $sql.sprintf(" and td.isActive= 1");	
		}
		else if($componentID == 64 /*64 component:tb_transaction_master_share*/){
	
			$sql = "";
			$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference,td.lote");
			$sql = $sql.sprintf(" from tb_transaction_master_detail td");
			$sql = $sql.sprintf(" inner join  tb_customer_credit_document i on td.companyID = i.companyID and td.componentItemID = i.customerCreditDocumentID");
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
			$sql = $sql.sprintf(" and td.transactionMasterDetailID = $transactionMasterDetailID");		
			$sql = $sql.sprintf(" and td.isActive= 1");			
		}	
		else {
			$sql = "";
			$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference,td.lote");
			$sql = $sql.sprintf(" from tb_transaction_master_detail td");		
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
			$sql = $sql.sprintf(" and td.transactionMasterDetailID = $transactionMasterDetailID");		
			$sql = $sql.sprintf(" and td.isActive= 1");	
		}
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByTransactionAndItems($companyID,$transactionID,$transactionMasterID,$listTMD_ID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");    
   
		
	
		$sql = "";
		$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,i.itemNumber,i.name as itemName,ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference,td.lote,td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku");
		$sql = $sql.sprintf(" from tb_transaction_master_detail td");
		$sql = $sql.sprintf(" inner join  tb_item i on td.companyID = i.companyID and td.componentItemID = i.itemID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci on i.unitMeasureID = ci.catalogItemID");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");				
		$sql = $sql.sprintf(" and td.isActive= 1");	
		$sql = $sql.sprintf(" and i.itemID in (".$listTMD_ID.") ");	
	
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	    $sql = "";
		$sql = sprintf("select w.companyID,w.branchID,w.warehouseID,w.itemID,w.quantity,w.cost,w.quantityMax,w.quantityMin,td.descriptionReference,td.exchangeRateReference,td.expirationDate,td.lote , td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku");
	    $sql = $sql.sprintf(" from tb_transaction_master tm");
		$sql = $sql.sprintf(" inner join  tb_transaction_master_detail td on tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
		$sql = $sql.sprintf(" inner join  tb_item i on td.companyID = i.companyID and td.componentItemID = i.itemID");		
		$sql = $sql.sprintf(" inner join  tb_item_warehouse w on w.warehouseID = tm.sourceWarehouseID and w.itemID = i.itemID");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
		$sql = $sql.sprintf(" and td.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$componentID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	    $sql = "";
		
		if($componentID == 3 /*catalogo*/)
		{
			$sql = sprintf("select 
								tm.companyID,
								tm.transactionID,
								tm.transactionMasterID,
								td.transactionMasterDetailID,
								td.componentID,
								td.componentItemID,								
								td.reference3,
								i.name,
								i.description
							");
			$sql = $sql.sprintf(" from tb_transaction_master tm");
			$sql = $sql.sprintf(" inner join  tb_transaction_master_detail td on tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
			$sql = $sql.sprintf(" inner join  tb_catalog_item i on  td.componentItemID = i.catalogItemID");					
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
			$sql = $sql.sprintf(" and td.isActive= 1");		
			
			
		}
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByTransaction($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice,td.tax1,td.tax2,td.tax3,td.tax4, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,i.itemNumber,i.name as itemName,ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference,td.lote,td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku");
		$sql = $sql.sprintf(" from tb_transaction_master_detail td");
		$sql = $sql.sprintf(" inner join  tb_item i on td.companyID = i.companyID and td.componentItemID = i.itemID");
		$sql = $sql.sprintf(" inner join  tb_catalog_item ci on i.unitMeasureID = ci.catalogItemID");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
		$sql = $sql.sprintf(" and td.isActive= 1");		
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
		$sql = "";
		$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference,td.lote");
		$sql = $sql.sprintf(" from tb_transaction_master_detail td");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
		$sql = $sql.sprintf(" and td.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function deleteWhereTM($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
		
		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);
		return $builder->update($data);
		
   }
   function deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTMD_ID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
		$data["isActive"] = 0;
		
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);			
		$builder->whereNotIn("transactionMasterDetailID",$listTMD_ID);	
		return $builder->update($data);
		
   }
}
?>