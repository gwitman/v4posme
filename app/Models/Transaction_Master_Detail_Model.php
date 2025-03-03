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
   
   function get_rowByTransactionMasterDetailID($transactionMasterDetailID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");    
		
		$sql = "";
		$sql = sprintf("
				select 
					td.companyID, td.transactionID, td.transactionMasterID, 
					td.transactionMasterDetailID, td.componentID, td.componentItemID, 
					td.promotionID, td.amount, td.cost, td.quantity, td.discount, 
					td.unitaryAmount, td.unitaryCost, td.unitaryPrice, 
					td.reference1, td.reference2, td.reference3,td.reference4,
					td.reference5,td.reference6,td.reference7, td.catalogStatusID, 
					td.inventoryStatusID, td.isActive, td.quantityStock, 
					td.quantiryStockInTraffic, td.quantityStockUnaswared, 
					td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, 
					td.inventoryWarehouseTargetID,
					td.descriptionReference,td.exchangeRateReference,td.lote , 
					td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku,
					td.skuFormatoDescription,td.itemNameLog,td.amountCommision, 
					td.itemNameDescriptionLog 
					");
		$sql = $sql.sprintf(" from tb_transaction_master_detail td " );		
		$sql = $sql.sprintf(" where ");		
		$sql = $sql.sprintf(" td.transactionMasterDetailID = $transactionMasterDetailID");		
		$sql = $sql.sprintf(" and td.isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$componentID=33){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");    
   
		if($componentID == 33 /*33 component:tb_item*/){
	
			$sql = "";
			$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,i.itemNumber,i.name as itemName,ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference,td.lote , td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku,td.skuFormatoDescription,td.itemNameLog,td.amountCommision, td.itemNameDescriptionLog ");
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
			$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference,td.lote,td.skuFormatoDescription,td.itemNameLog,td.amountCommision, td.itemNameDescriptionLog ");
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
			$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference,td.lote,td.skuFormatoDescription,td.itemNameLog,td.amountCommision, td.itemNameDescriptionLog ");
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
		$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,i.itemNumber,i.barCode,i.name as itemName,ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference,td.lote,td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku,td.skuFormatoDescription,td.itemNameLog,td.amountCommision,  td.itemNameDescriptionLog ");
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
		$sql = sprintf("select 
					w.companyID,w.branchID,w.warehouseID,w.itemID,w.quantity,w.cost,w.quantityMax,
					w.quantityMin,td.descriptionReference,td.exchangeRateReference,td.expirationDate,td.lote ,
					td.typePriceID,td.skuCatalogItemID,td.skuQuantity,td.skuQuantityBySku,
					td.skuFormatoDescription,
					REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(replace(td.itemNameLog,'\"',''), '\r\n', ''), '\n\r', ''),'\n', ''),'\t','') , '?', '')  as itemNameLog,
					td.amountCommision,
					REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(replace(td.itemNameDescriptionLog,'\"',''), '\r\n', ''), '\n\r', ''),'\n', ''),'\t','') , '?', '')   as itemNameDescriptionLog
						
				");
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
								i.description,
								i.display,
								i.reference1,
								td.skuFormatoDescription,
								td.itemNameLog,
								td.amountCommision,
								td.itemNameDescriptionLog
							");
			$sql = $sql.sprintf(" from tb_transaction_master tm");
			$sql = $sql.sprintf(" inner join  tb_transaction_master_detail td on tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
			$sql = $sql.sprintf(" inner join  tb_catalog_item i on  td.componentItemID = i.catalogItemID");					
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
			$sql = $sql.sprintf(" and td.isActive= 1");		
			
			
		}
		if($componentID == 92 /*public catalogo*/)
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
								i.description,
								i.display,
								i.reference1,
								i.reference2,								
								i.reference4,
								
								td.skuFormatoDescription,
								td.itemNameLog,
								td.amountCommision,
								td.itemNameDescriptionLog
							");
			$sql = $sql.sprintf(" from tb_transaction_master tm");
			$sql = $sql.sprintf(" inner join  tb_transaction_master_detail td on tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
			$sql = $sql.sprintf(" inner join  tb_public_catalog_detail i on  td.componentItemID = i.publicCatalogDetailID");					
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
			$sql = $sql.sprintf(" and td.isActive= 1");		
			
			
		}
		
		if($componentID == 100 /*tb_file*/)
		{
			$sql = sprintf("select 
								tm.companyID,
								tm.transactionID,
								tm.transactionMasterID,
								
								td.transactionMasterDetailID,
								td.componentID,
								td.componentItemID,								
								td.reference1,
								td.reference2,
								td.reference3,
								
								tcom.name as tipoFile
								
							");
			$sql = $sql.sprintf(" from tb_transaction_master tm");
			$sql = $sql.sprintf(" inner join  tb_transaction_master_detail td on tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
			$sql = $sql.sprintf(" inner join  tb_catalog_item tcom on tcom.catalogItemID = td.componentItemID ");
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
			$sql = $sql.sprintf(" and td.isActive= 1");		
			
			
		}

		if($componentID == 104 /*tb_comments*/)
		{
			$sql = sprintf("select 
								td.companyID,
								td.transactionID,
								td.transactionMasterID,
								td.transactionMasterDetailID,
								td.componentID,
								td.expirationDate,
								td.reference1,
								td.catalogStatusID
							");
			$sql = $sql.sprintf(" from tb_transaction_master_detail td");
			$sql = $sql.sprintf(" where td.companyID = $companyID");
			$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
			$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");
			$sql = $sql.sprintf(" and td.componentID = $componentID");
			$sql = $sql.sprintf(" and td.isActive= 1");		
			$sql = $sql.sprintf(" order by td.transactionMasterDetailID desc");			
		}

		if($componentID == 88 /* Consulta Medica*/)
		{
			$sql      = sprintf("select 
									td.transactionMasterDetailID,
									td.itemNameLog,
                                    td.skuQuantity,
                                    td.skuQuantityBySku,
                                    td.typePriceID,
									td.amount");
			$sql = $sql.sprintf(" from tb_transaction_master_detail td");
			$sql = $sql.sprintf(" where td.transactionMasterID = $transactionMasterID");
		}
		
		if($componentID == 98 /* Orden de taller : tb_transaction_master_workshop_taller*/)
		{
			$sql      = sprintf("select 
									td.reference1,
									ci.name									
								");
			$sql = $sql.sprintf(" from tb_transaction_master_detail td");
			$sql = $sql.sprintf(" inner join tb_catalog_item ci on ci.catalogItemID = td.catalogStatusID ");			
			$sql = $sql.sprintf(" where td.transactionMasterID = $transactionMasterID and td.isActive = 1 ");
		}

		if($componentID == 119 || $componentID == 121)
		{
			/*Entrada de Activo Fijo: tb_transaction_master_fixedassent_input - 119*/
			/*Salida de Activo Fijo:  tb_transaction_master_fixedassent_output- 121*/
			$sql = sprintf("SELECT 
									tmd.transactionMasterDetailID as tmdID,
									fa.fixedAssentID AS fixedAssetID,
									fa.fixedAssentCode AS fixedAssetCode,
									fa.`name` AS fixedAssetName,
									tmd.amount AS fixedAssetDuration,
									tmd.componentItemID");
			$sql = $sql.sprintf(" FROM tb_transaction_master_detail tmd");
			$sql = $sql.sprintf(" INNER JOIN tb_fixed_assent fa ON tmd.componentItemID = fa.fixedAssentID");
			$sql = $sql.sprintf(" WHERE tmd.companyID = $companyID");
			$sql = $sql.sprintf(" AND tmd.transactionID = $transactionID");
			$sql = $sql.sprintf(" AND tmd.transactionMasterID = $transactionMasterID");
			$sql = $sql.sprintf(" AND tmd.componentID = $componentID");
			$sql = $sql.sprintf(" AND tmd.isActive =  1");
		}
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowByTransaction($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("select 
						td.companyID, td.transactionID, td.transactionMasterID, 
						td.transactionMasterDetailID, td.componentID, td.componentItemID,
						td.promotionID, td.amount, td.cost, td.quantity, td.discount,
						td.unitaryAmount, td.unitaryCost, td.unitaryPrice,td.tax1,td.tax2,td.tax3,td.tax4,
						td.reference1, td.reference2, td.reference3,td.reference4,
						td.reference5,td.reference6,td.reference7,
						td.catalogStatusID, td.inventoryStatusID, td.isActive,
						td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, 
						td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, 
						td.inventoryWarehouseTargetID,i.itemNumber,
						case 
							when LOCATE(',',i.barCode) > 1 then 
								CONCAT(SUBSTRING(i.barCode,1,LOCATE(',',i.barCode)+1),'...') 
							else 
								i.barCode 
						end  as barCode,
						i.name as itemName,
						ci.name as unitMeasureName,td.descriptionReference,td.exchangeRateReference,
						td.lote,td.typePriceID,td.skuCatalogItemID,td.skuQuantity,
						td.skuQuantityBySku,td.skuFormatoDescription,
						REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(replace(td.itemNameLog,'\"',''), '\r\n', ''), '\n\r', ''),'\n', ''),'\t','') , '?', '')  as itemNameLog,
						td.amountCommision,
						REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(replace(td.itemNameDescriptionLog,'\"',''), '\r\n', ''), '\n\r', ''),'\n', ''),'\t','') , '?', '')   as itemNameDescriptionLog
						
						");
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
		$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference,td.lote,td.skuFormatoDescription,td.itemNameLog,td.amountCommision,td.itemNameDescriptionLog ");
		$sql = $sql.sprintf(" from tb_transaction_master_detail td");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
		$sql = $sql.sprintf(" and td.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }

   function get_rowByTransactionToAdvanceOfPayroll($companyID,$transactionID,$transactionMasterID){
	$db 	= db_connect();
	$builder	= $db->table("tb_transaction_master_detail");
	$sql = "";
	$sql = sprintf("select td.companyID, td.transactionID, td.transactionMasterID, td.transactionMasterDetailID, td.componentID, td.componentItemID, td.promotionID, td.amount, td.cost, td.quantity, td.discount, td.unitaryAmount, td.unitaryCost, td.unitaryPrice, td.reference1, td.reference2, td.reference3,td.reference4,td.reference5,td.reference6,td.reference7, td.catalogStatusID, td.inventoryStatusID, td.isActive, td.quantityStock, td.quantiryStockInTraffic, td.quantityStockUnaswared, td.remaingStock, td.expirationDate, td.inventoryWarehouseSourceID, td.inventoryWarehouseTargetID,td.descriptionReference,td.exchangeRateReference,td.lote,td.skuFormatoDescription,td.itemNameLog,td.amountCommision,td.itemNameDescriptionLog ");
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

   function deleteWhereIDNotInComponent($companyID,$transactionID,$transactionMasterID,$componentID,$listTMD_ID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
		$data["isActive"] = 0;


		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);
		$builder->where("componentID",$componentID);
		$builder->whereNotIn("transactionMasterDetailID",$listTMD_ID);	
		return $builder->update($data);
	}
	
	function get_rowByEntityIDAndCreditPending($companyID,$customerNumber)
	{
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
				select 
					c.customerNumber,
					n.firstName,
					tm.createdOn,
					i.`name` as itemName,
					tmd.quantity ,
					tmd.unitaryPrice ,
					ifnull(tmd.tax1,0) as tax1,
					ifnull(tmd.tax2,0) as tax2,
					tmd.amount 
				from 
					tb_customer c 
					inner join tb_naturales n on 
						c.entityID = n.entityID 
					inner join tb_customer_credit_document ccd on 
						ccd.entityID = c.entityID 
					inner join tb_transaction_master tm on 
						tm.transactionNumber = ccd.documentNumber 
					inner join tb_transaction_master_detail tmd on 
						tmd.transactionMasterID = tm.transactionMasterID 
					inner join tb_item i on 
						i.itemID = tmd.componentItemID 
				where 
					c.customerNumber = '$customerNumber' and 
					tm.isActive = 1 and 
					tmd.isActive = 1 and 
					ccd.isActive = 1 and 
					ccd.statusID in (77 /*registrado*/) and 
					c.companyID = $companyID 
						");
	
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
   function get_rowByShareID($companyID,$transactionMasterID)
	{
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
				select 
					tm.transactionNumber,
					cus.customerNumber,
					nat.firstName,		
					fac.transactionNumber as FacturaCancelada,
					fac.createdOn,
					i.`name` as itemName,
					ul.quantity ,
					ul.unitaryPrice ,
					ifnull(ul.tax1,0) as tax1,
					ifnull(ul.tax2,0) as tax2,
					ul.amount 
				from 
					tb_transaction_master tm 
					inner join tb_workflow_stage ws on 
						ws.workflowStageID = tm.statusID 
					inner join tb_transaction_master_detail tmd on 
						tmd.transactionMasterID = tm.transactionMasterID 
					inner join tb_customer_credit_document ccd on 
						ccd.documentNumber = tmd.reference1 
					inner join tb_workflow_stage wss on 
						wss.workflowStageID = ccd.statusID 
					inner join tb_transaction_master fac on 
						fac.transactionNumber = ccd.documentNumber 
					inner join tb_transaction_master_detail ul on 
						ul.transactionMasterID = fac.transactionMasterID 
					inner join tb_item i on 
						i.itemID = ul.componentItemID 
					inner join tb_naturales nat on 
						nat.entityID = ccd.entityID 
					inner join tb_customer cus on 	
						nat.entityID = cus.entityID 
				where 
					tm.isActive = 1 and 
					tm.transactionID = 23 and 
					tm.statusID in ( 80 /*aplicado*/ )  and 
					ccd.statusID in ( 82 /*cancelado*/ )   and 
					tm.transactionMasterID = $transactionMasterID 	 ;  
				");
	
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
    function GlobalPro_get_rowBySalesByEmployeerMonthOnly_Sales($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			 select	
				ifnull(nat.firstName,'ND') as firtsName,
				sum(
					case 
						when t.transactionID = 19 then 
							td.unitaryPrice * td.quantity	
						else 
							td.unitaryPrice * td.quantity	 * -1
					end 
				) as monto 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					t.statusID = ws.workflowStageID
				left join tb_naturales nat on 
					nat.entityID = t.entityIDSecondary 
				inner join tb_transaction_master_detail td on 
					td.transactionMasterID = t.transactionMasterID
				inner join tb_item i on 
					i.itemID = td.componentItemID
			where 
				t.transactionID in (19,20) and   
				t.isActive = 1  and 
				t.companyID = 2  and 
				td.isActive = 1 and 
				t.transactionOn between '$dateFirst' and '$dateLast' and 
				i.name NOT LIKE '%s' and 
				(
					(
						t.transactionID = 19 and 
						t.statusID in ( 67 /*aplicada*/,68 /*anulada*/  )
					)
					or 
					(
						t.transactionID = 20 
					)				
				)
			group by  
				nat.firstName
		","%repara%");
	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   
   
   function GlobalPro_get_rowBySalesByEmployeerMonthOnly_Tenico($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			 select	
				REPLACE(ifnull(i.name,'ND'),'Reparacion de Laptop','') as firtsName,
				sum(
					case 
						when t.transactionID = 19 then 
							td.unitaryPrice * td.quantity	
						else
							td.unitaryPrice * td.quantity * -1 
					end 
				) as monto 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					t.statusID = ws.workflowStageID
				left join tb_naturales nat on 
					nat.entityID = t.entityIDSecondary 
				inner join tb_transaction_master_detail td on 
					td.transactionMasterID = t.transactionMasterID
				inner join tb_item i on 
					i.itemID = td.componentItemID
			where 
				t.transactionID in (19,20) and   
				t.isActive = 1  and 
				t.companyID = 2  and 
				t.transactionOn between '$dateFirst' and '$dateLast' and 
				i.name LIKE  '%s' and 
				(
					(
						t.transactionID = 19 and 
						t.statusID in ( 67 /*aplicada*/,68 /*anulada*/  )
					)
					or 
					(
						t.transactionID = 20 
					)				
				)
			group by  
				i.name
		","%repara%");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   
   
   function GlobalPro_get_MonthOnly_Sales($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
		 select	
				month(t.transactionOn) as firtsName,
				sum(
					case 
						when t.transactionID = 19 then 
							t.subAmount
						else 
							(t.subAmount * -1)
					end 
				) as monto 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					t.statusID = ws.workflowStageID
				left join tb_naturales nat on 
					nat.entityID = t.entityIDSecondary 
			where 
				t.transactionID in (19,20) and   
				t.isActive = 1   
				and 
				(
					(
						t.transactionID = 19 and 
						t.statusID in ( 67 /*aplicada*/,68 /*anulada*/  )
					)
					or 
					(
						t.transactionID = 20 
					)
				) and 
				t.companyID = 2  and 
				t.transactionOn between '$dateFirst' and '$dateLast' 
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   
   function GlobalPro_get_MonthOnly_SalesByBranch($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
		 select	
				b.name as firtsName,
				sum(
					case 
						when t.transactionID = 19 then 
							t.subAmount
						else 
							(t.subAmount * -1)
					end 
				) as monto 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					t.statusID = ws.workflowStageID
				left join tb_naturales nat on 
					nat.entityID = t.entityIDSecondary 
				inner join tb_user us on 
					us.userID = t.createdBy 
				inner join tb_branch b on 
					b.branchID = us.locationID 
			where 
				t.transactionID in (19,20) and   
				t.isActive = 1   
				and 
				(
					(
						t.transactionID = 19 and 
						t.statusID in ( 67 /*aplicada*/,68 /*anulada*/  )
					)
					or 
					(
						t.transactionID = 20 
					)
				) and 
				t.companyID = 2  and 
				t.transactionOn between '$dateFirst' and '$dateLast' 
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   
   function GlobalPro_get_Day_Sales($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			 select	
				day(t.transactionOn) as firtsName,
				sum(
					case
						when t.transactionID = 19 then 
							t.subAmount 
						else 
							(t.subAmount * -1)
					end
				) as monto 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					t.statusID = ws.workflowStageID
				left join tb_naturales nat on 
					nat.entityID = t.entityIDSecondary 
			where 
				t.transactionID in (19,20) and   
				t.isActive = 1  and 				
				t.companyID = 2  and 
				t.transactionOn between '$dateFirst' and '$dateLast' and 
				(
					(
						t.transactionID = 19 and 
						t.statusID in ( 67 /*aplicada*/,68 /*anulada*/  )
					)
					or 
					(
						t.transactionID = 20 
					)				
				)
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   
   function GlobalPro_get_Notification_LaptopMenorA14400_7Meses()
   {
	   $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = "select   
				
				tx.phoneNumber as Destino,
				
				CONCAT(
				tx.transactionNumber,
				'-',
				tx.transactionMasterDetailID,
				'-sendWhatsappGlobalProLaptopMenorA14400Frecuency7Meses'
				) as CodigoMensaje,

			
				CONCAT(
				'¡Hola ', tx.firstName  ,' [simbol-cono] [simbol-enter][simbol-enter] !Esperamos que hayas disfrutado de tu laptop! [simbol-carita-estrellada]   Queremos recordarte que la garantía de 7 meses está por terminar, es por eso que te ofrecemos un 50% de DESCUENTO en mantenimiento. Para que tu equipo siga funcionando de maravilla,  ¡Aprovecha esta oferta y asegura un rendimiento óptimo!  Saludos,SERVICIO TECNICO GLOBAL PRO!'
				) as Mensaje 

			from 
					(
						select 
							tmd.transactionMasterDetailID,
							tm.transactionNumber,
							nat.firstName,
							REPLACE(cus.identification, '[^a-zA-Z0-9]', '') as identification,
							REPLACE(cus.phoneNumber, '[^a-zA-Z0-9]', '') as phoneNumber,
							tm.createdOn,
							tmd.itemNameLog,
							tmd.itemNameDescriptionLog
						from 
							tb_transaction_master tm 
							inner join tb_transaction_master_detail tmd on 
								tm.transactionMasterID = tmd.transactionMasterID 
							inner join tb_item i on 
								tmd.componentItemID = i.itemID 
							inner join tb_item_category cat on 
								i.inventoryCategoryID = cat.inventoryCategoryID 
							inner join tb_naturales nat on 
								nat.entityID = tm.entityID 
							inner join tb_customer cus on 
								nat.entityID = cus.entityID 
						where 
							tm.transactionID = 19 and 
							tm.isActive = 1 and 
							tmd.isActive = 1 and 
							cat.`name` = 'Laptops' and 
							tmd.unitaryAmount <= 14400  and 
							LENGTH(REPLACE(cus.identification, '[^a-zA-Z0-9]', '')) = 14 and 
							LENGTH(REPLACE(cus.phoneNumber, '[^a-zA-Z0-9]', '')) = 8 and 
							tm.createdOn <= DATE_SUB(CURDATE(), INTERVAL 210 DAY) and 
							tm.createdOn >= DATE_SUB(CURDATE(), INTERVAL 209 DAY) 
							
					) tx
			";
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function GlobalPro_get_Notification_LaptopMayoresA14400_11Meses()
   {
	   $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = "select   
				
				tx.phoneNumber as Destino,
				
				CONCAT(
				tx.transactionNumber,
				'-',
				tx.transactionMasterDetailID,
				'-sendWhatsappGlobalProLaptopMayoresA14400Frecuency11Meses'
				) as CodigoMensaje,

			
				CONCAT(
				'¡Hola ', tx.firstName  ,' [simbol-cono] [simbol-enter][simbol-enter] !Esperamos que hayas disfrutado de tu laptop! [simbol-carita-estrellada]   Queremos recordarte que la garantía de 12 meses está por terminar, es por eso que te ofrecemos un 50% de DESCUENTO en mantenimiento. Para que tu equipo siga funcionando de maravilla,  ¡Aprovecha esta oferta y asegura un rendimiento óptimo!  Saludos,SERVICIO TECNICO GLOBAL PRO!'
				) as Mensaje 

			from 
					(
						select 
							tmd.transactionMasterDetailID,
							tm.transactionNumber,
							nat.firstName,
							REPLACE(cus.identification, '[^a-zA-Z0-9]', '') as identification,
							REPLACE(cus.phoneNumber, '[^a-zA-Z0-9]', '') as phoneNumber,
							tm.createdOn,
							tmd.itemNameLog ,
							tmd.itemNameDescriptionLog
						from 
							tb_transaction_master tm 
							inner join tb_transaction_master_detail tmd on 
								tm.transactionMasterID = tmd.transactionMasterID 
							inner join tb_item i on 
								tmd.componentItemID = i.itemID 
							inner join tb_item_category cat on 
								i.inventoryCategoryID = cat.inventoryCategoryID 
							inner join tb_naturales nat on 
								nat.entityID = tm.entityID 
							inner join tb_customer cus on 
								nat.entityID = cus.entityID 
						where 
							tm.transactionID = 19 and 
							tm.isActive = 1 and 
							tmd.isActive = 1 and 
							cat.`name` = 'Laptops' and 
							tmd.unitaryAmount > 14400  and 
							LENGTH(REPLACE(cus.identification, '[^a-zA-Z0-9]', '')) = 14 and 
							LENGTH(REPLACE(cus.phoneNumber, '[^a-zA-Z0-9]', '')) = 8 and 
							tm.createdOn <= DATE_SUB(CURDATE(), INTERVAL 229 DAY) and 
							tm.createdOn >= DATE_SUB(CURDATE(), INTERVAL 228 DAY) 
							
					) tx
			limit 1 
			";
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   

   function GlobalPro_get_Notification_CumpleAnnos()
   {
	   $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = "
			select  
				tx.phoneNumber as Destino,
				'sendWhatsappGlobalProCumpleAnnos' as CodigoMensaje,
				tx.Comparacion , 
				
				CONCAT(
				'¡Felicidades ', tx.firstName  ,'![simbol-carita-estrellada][simbol-cono][simbol-enter]Espero tengas un excelente día!.[simbol-enter][simbol-enter]-Equipo Global Pro [simbol-enter]Las mejores computadoras del país!' 
				) as Mensaje  
				
			from 
					(
						select 							
							nat.firstName,
							REPLACE(cus.identification, '[^a-zA-Z0-9]', '') as identification,
							REPLACE(cus.phoneNumber, '[^a-zA-Z0-9]', '') as phoneNumber 		,
							
							
							
							DATE(DATE_ADD(NOW(), INTERVAL -6 HOUR)) AS Fecha,							
							STR_TO_DATE(
									CONCAT(
											SUBSTRING(cus.identification, 4, 2), 
											'-', 
											SUBSTRING(cus.identification, 6, 2), 
											'-', 
											SUBSTRING(cus.identification, 8, 2) + 1900
									),
									'%d-%m-%Y'
							) AS FechaCus,
							-- Comparación de días y meses
							CASE 
									WHEN MONTH(DATE(DATE_ADD(NOW(), INTERVAL -6 HOUR))) = MONTH(
											STR_TO_DATE(
													CONCAT(
															SUBSTRING(cus.identification, 4, 2), 
															'-', 
															SUBSTRING(cus.identification, 6, 2), 
															'-', 
															SUBSTRING(cus.identification, 8, 2) + 1900
													),
													'%d-%m-%Y'
											)
									) 
									AND DAY(DATE(DATE_ADD(NOW(), INTERVAL -6 HOUR))) = DAY(
											STR_TO_DATE(
													CONCAT(
															SUBSTRING(cus.identification, 4, 2), 
															'-', 
															SUBSTRING(cus.identification, 6, 2), 
															'-', 
															SUBSTRING(cus.identification, 8, 2) + 1900
													),
													'%d-%m-%Y'
											)
									)
									THEN 'Iguales'
									ELSE 'Diferentes'
							END AS Comparacion 
						from 
							tb_naturales nat 
							inner join tb_customer cus on 
								nat.entityID = cus.entityID 
						where 											
							LENGTH(REPLACE(cus.identification, '[^a-zA-Z0-9]', '')) = 14 and 
							LENGTH(REPLACE(cus.phoneNumber, '[^a-zA-Z0-9]', '')) = 8  
					) tx
			where 
				tx.Comparacion = 'Iguales'
			";
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   
   function RealState_get_ClienteFuenteDeContacto($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			SELECT 
				u.`name` as Indicador,
				count(*) as Cantidad
			from 
				tb_customer c 
				left join tb_catalog_item u on 
					c.formContactID = u.catalogItemID 
			where 
				c.isActive =  1 and 
				c.createdOn  between '$dateFirst' and '$dateLast'  				
			group by 
				u.name 
			order by 
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_ClientesInteres($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				ci.`name` as Indicador,
				count(*) as Cantidad
			from 
				tb_customer  x 
				inner join tb_catalog_item ci on 
					x.categoryID = ci.catalogItemID 
			where 
				x.createdOn between '$dateFirst' and '$dateLast'  
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_ClientesTipoPropiedad($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				ci.`name` as Indicador,
				count(*) as Cantidad
			from 
				tb_customer  x 
				inner join tb_catalog_item ci on 
					x.clasificationID = ci.catalogItemID 
			where 
				x.createdOn between '$dateFirst' and '$dateLast'  
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_ClientesPorAgentes($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			 select 
				u.firstName as Indicador,
				count(*) as Cantidad
			from 
				tb_customer  x 
				inner join tb_naturales  u on 
					x.entityContactID = u.entityID  
			where 
				x.createdOn between '$dateFirst' and '$dateLast'  
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_ClientesClasificacionPorAgentes($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				ws.`name` as Indicador,
				nat.firstName as Agente,
				count(*) as Cantidad
			from 
				tb_customer  x 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = x.statusID 
				inner join tb_naturales nat on 
					nat.entityID = x.entityContactID 
			where 
				x.createdOn between '$dateFirst' and '$dateLast'  
			group by  
				1,2
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_ClientesCerrados($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			 select 
				ws.`name` as Indicador,
				count(*) as Cantidad
			from 
				tb_customer  x 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = x.statusID 
			where 
				x.createdOn between '$dateFirst' and '$dateLast'  
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_AgenteEfectividad($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				u.firstName as Indicador,
				count(*) as Cantidad
			from 
				tb_customer  x 
				inner join tb_naturales  u on 
					x.entityContactID = u.entityID  
			where 
				x.createdOn between '$dateFirst' and '$dateLast'  
			group by  
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_PropiedadesPorAgentes($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			 select 
				nat.firstName as Indicador,
				count(*) as Cantidad 
			from 
				tb_item  i 
				inner join tb_naturales nat on 
					nat.entityID = i.realStateEmployerAgentID 
			where 
				i.createdOn between '$dateFirst' and '$dateLast'  
			group by 
				nat.firstName 
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_PropiedadesPorAgentesMetas($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				ifnull(nat.firstName,'ND') as Indicador,
				count(*) as Cantidad 
			from 
				tb_item i 
				left join tb_naturales nat on 
					nat.entityID = i.realStateEmployerAgentID 	
			where 	
				i.isActive = 1 and 
				i.createdOn between '$dateFirst' and '$dateLast' 
			group by 
				nat.firstName 
			order by 
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_PropiedadesRendimientoAnualVentas($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				ifnull(nat.firstName,'ND') as Indicador,
				count(*) as Cantidad 
			from 
				tb_item i 
				left join tb_naturales nat on 
					nat.entityID = i.realStateEmployerAgentID 	
			where 	
				i.isActive = 1 and 
				i.createdOn between '$dateFirst' and '$dateLast' 
			group by 
				nat.firstName 
			order by 
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function RealState_get_PropiedadesRendimientoAnualEnlistamiento($companyID,$dateFirst,$dateLast)
   {
	   
	    $db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				ifnull(nat.firstName,'ND') as Indicador,
				count(*) as Cantidad 
			from 
				tb_item i 
				left join tb_naturales nat on 
					nat.entityID = i.realStateEmployerAgentID 	
			where 	
				i.isActive = 1 and 
				i.createdOn between '$dateFirst' and '$dateLast' 
			group by 
				nat.firstName 
			order by 
				1
		");
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }
   function GlamCust_get_Citas($companyID)
   {
	   
	    $db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				tat.firstName,
				tat.transactionNumber,
				tat.SiguienteVisita
			from 
				(
					select 
						case 
							when ci.referenceClientName != '' then 
								ci.referenceClientName
							else 
								nat.firstName
						end  as firstName ,
						c.transactionNumber ,
						DATE_ADD(c.nextVisit , INTERVAL zone.`sequence`  MINUTE) as SiguienteVisita 
					from 
						tb_transaction_master c 
						inner join tb_transaction_master_info ci on 
							c.transactionMasterID = ci.transactionMasterID 
						inner join tb_catalog_item zone on 
							zone.catalogItemID = ci.zoneID 
						inner join tb_workflow_stage ws on 
							c.statusID = ws.workflowStageID 
						inner join tb_naturales nat on 
							c.entityID = nat.entityID 
					where 
						c.isActive = 1 and 
						c.companyID = 2 and 
						c.transactionID = 19  and 
						ws.isInit = 1 and 
						CAST(zone.`name`  AS UNSIGNED ) > 1   and 
						c.nextVisit is not null 
				)  tat 
			where 
				tat.SiguienteVisita < DATE_ADD( 
					DATE_ADD(
						NOW() , 
						INTERVAL ".APP_HOUR_DIFERENCE_MYSQL_EMBEDDED."
					), 
					INTERVAL 2 HOUR
				) AND 
				tat.SiguienteVisita > DATE_ADD(
						NOW() , 
						INTERVAL ".APP_HOUR_DIFERENCE_MYSQL_EMBEDDED."
				)
						
		");
	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   } 
   function GlamCust_get_Citas_2DayBefore($companyID)
   {
	   
	    $db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				tat.firstName,
				tat.transactionNumber,
				tat.SiguienteVisita
			from 
				(
					select 
						case 
							when ci.referenceClientName != '' then 
								ci.referenceClientName
							else 
								nat.firstName
						end  as firstName ,
						c.transactionNumber ,
						DATE_ADD(c.nextVisit , INTERVAL zone.`sequence`  MINUTE) as SiguienteVisita 
					from 
						tb_transaction_master c 
						inner join tb_transaction_master_info ci on 
							c.transactionMasterID = ci.transactionMasterID 
						inner join tb_catalog_item zone on 
							zone.catalogItemID = ci.zoneID 
						inner join tb_workflow_stage ws on 
							c.statusID = ws.workflowStageID 
						inner join tb_naturales nat on 
							c.entityID = nat.entityID 
					where 
						c.isActive = 1 and 
						c.companyID = 2 and 
						c.transactionID = 19  and 
						ws.isInit = 1 and 
						CAST(zone.`name`  AS UNSIGNED ) > 1   and 
						c.nextVisit is not null 
				)  tat 
			where 
				tat.SiguienteVisita < DATE_ADD( 
					DATE_ADD(
						NOW() , 
						INTERVAL ".APP_HOUR_DIFERENCE_MYSQL_EMBEDDED."
					), 
					INTERVAL 48 HOUR
				) AND 
				tat.SiguienteVisita > DATE_ADD(
						NOW() , 
						INTERVAL ".APP_HOUR_DIFERENCE_MYSQL_EMBEDDED."
				)
						
		");
	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   }

	
   function AudioElPipe_get_Citas($companyID)
   {
	   
	    $db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");
	   		
		$sql = "";
		$sql = sprintf("
			select 
				tat.firstName,
				tat.transactionNumber,
				tat.SiguienteVisita,
				tat.Notas,
				tat.transactionMasterID
			from 
				(
					

					select 
						case 
							when ci.referenceClientName != '' then 
								ci.referenceClientName
							else 
								nat.firstName
						end  as firstName ,
						c.transactionNumber ,
						c.nextVisit as SiguienteVisita,
						c.note as Notas,
						c.transactionMasterID
					from 
						tb_transaction_master c 
						inner join tb_transaction_master_info ci on 
							c.transactionMasterID = ci.transactionMasterID 
						inner join tb_catalog_item zone on 
							zone.catalogItemID = ci.zoneID 
						inner join tb_workflow_stage ws on 
							c.statusID = ws.workflowStageID 
						inner join tb_naturales nat on 
							c.entityID = nat.entityID 
					where 
						c.isActive = 1 and 
						c.companyID = 2 and 
						c.transactionID = 19  and   
						ws.isInit = 1 and    					
						c.nextVisit is not null 
						
	
				)  tat 
			where
				tat.SiguienteVisita BETWEEN  
				/*utc zona 0 a las media noche */
				/*utc zona 0 a las media noche */
				/*
				'2025-01-24 22:00:00' and 
				'2025-01-25 02:00:00' ;
				*/
				
				/*utc zona 0 a las media noche */
				/*utc zona 0 a las media noche */
				
				DATE_ADD( 
					DATE_ADD(
						NOW() , 
						INTERVAL -6 hour 
					), 
					INTERVAL -2 HOUR
				) 
				AND 				
				DATE_ADD( 
					DATE_ADD(
						NOW() , 
						INTERVAL -6 hour 
					), 
					INTERVAL 2 HOUR
				) 
				 
						
		");
	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
			
   } 

   function CompuMatt_get_MonthOnly_Sales($companyID,$dateFirst,$dateLast)
   {

	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");

		$sql = "";
		$sql = sprintf("
		 select	
				month(t.transactionOn) as firtsName,
				sum(t.amount) as monto 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					t.statusID = ws.workflowStageID 
			where 
				t.transactionID = 40 /*Ordenes de Taller*/ and   
				t.isActive = 1 and 
				t.companyID = 2  
				and t.transactionOn between '$dateFirst' and '$dateLast' 
			group by  
				1
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }

   function CompuMatt_get_Day_Sales($companyID,$dateFirst,$dateLast)
   {

	    $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");

		$sql = "";
		$sql = sprintf("
			 select	
				day(t.transactionOn) as firtsName,
				sum(t.amount) as monto 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					t.statusID = ws.workflowStageID
			where 
				t.transactionID = 40 /*Ordenes de Taller*/ and   
				t.isActive = 1  and 				
				t.companyID = 2  and 
				t.transactionOn between '$dateFirst' and '$dateLast' 
			group by  
				1
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }

   function CompuMatt_get_Amount_by_Status($companyID,$dateFirst,$dateLast)
   {
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");

		$sql = "";
		$sql = sprintf("
			 SELECT 
					c.name AS firstName,
					COUNT(t.areaID) AS amount
				FROM 
					tb_transaction_master t
				INNER JOIN 
					tb_workflow_stage ws ON t.statusID = ws.workflowStageID
				INNER JOIN 
					tb_catalog_item c ON t.areaID = c.catalogItemID
				WHERE 
					t.transactionID = 40 /*Ordenes de Taller*/ AND 
					t.isActive = 1 
					AND t.companyID = 2
				GROUP BY 
					c.name
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }

   function CompuMatt_get_Orders_by_Employee($companyID,$dateFirst,$dateLast)
   {
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail");

		$sql = "";
		$sql = sprintf("
				SELECT 
					nat.firstName AS employee,	
					c.name AS firstName, 
					COUNT(t.areaID) AS amount
				FROM 
					tb_transaction_master t
					INNER JOIN 
						tb_workflow_stage ws ON t.statusID = ws.workflowStageID
					INNER JOIN 
						tb_catalog_item c ON t.areaID = c.catalogItemID
					INNER JOIN tb_naturales nat on
						nat.entityID = t.entityIDSecondary
				WHERE 
					t.transactionID = 40 /*Ordenes de Taller*/ AND 
					t.isActive = 1 AND t.companyID = 2 AND
					t.transactionOn between '$dateFirst' and '$dateLast'
				GROUP BY 
					nat.firstName,c.name  
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }

  




   function Ebenezer_Get_MonthOnly_CashSale($companyID,$dateFirst,$dateLast)
   {

	    $db 	= db_connect();

		$sql = "";
		$sql = sprintf("
		  	SELECT 
		  		MONTH(tm.transactionOn) AS mes, 
				SUM(tm.amount) AS monto
			FROM 
				tb_transaction_master tm 
				INNER JOIN tb_transaction_causal tc ON 
					tm.transactionID = tc.transactionID
			WHERE 
				tc.transactionID = 19
				AND 
				tc.name = 'CONTADO'
				AND 
				tm.isActive = 1
				AND
				tm.transactionOn between '$dateFirst' and '$dateLast'
			GROUP BY 
				mes
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }

   function Ebenezer_Get_MonthOnly_Inscriptions($companyID,$dateFirst,$dateLast)
   {

	    $db 	= db_connect();

		$sql = "";
		$sql = sprintf("
		 	SELECT 
		 		DAY(tm.transactionOn) AS dia,
				SUM(tm.amount) AS ingreso 
			FROM 
				tb_transaction_master tm
			WHERE 
				tm.transactionID = 23
				AND 
				tm.transactionOn between '$dateFirst' and '$dateLast'
				AND
				tm.isActive = 1
			GROUP BY 
				dia;
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }

   function Ebenezer_Get_Students_By_City()
   {
		$db 	= db_connect();

		$sql = "";
		$sql = sprintf("
				SELECT 
					ci.name AS municipio, 
					COUNT(c.cityID) AS cantidad 
				FROM 
					tb_customer c
					INNER JOIN tb_catalog_item ci ON 
						c.cityID = ci.catalogItemID
				WHERE 
					c.isActive = 1
				GROUP BY
					ci.name
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }

   function Ebenezer_Get_Students_By_Sex()
   {
		$db 	= db_connect();

		$sql = "";
		$sql = sprintf("
				SELECT 
					ci.name AS sexo, 
					COUNT(c.sexoID) AS cantidad 
				FROM 
					tb_customer c 
					INNER JOIN tb_catalog_item ci ON 
						c.sexoID = ci.catalogItemID
				WHERE
					c.isActive = 1
				GROUP BY
					ci.name
		");

		//Ejecutar Consulta
		return $db->query($sql)->getResult();	
   }
   





   function FinancieraCorea_Desembolsos_Mensuales($companyID,$dateFirst,$dateLast)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
		  	SELECT 
				MONTH(c.createdOn) as Mes,
				SUM(c.amount) as Desembolso 
			FROM 
				tb_transaction_master c 
			WHERE 
				c.transactionID = 19 /*factura*/ 
				AND 
				c.isActive = 1 
				AND 
				c.transactionCausalID in (22 /*credito*/,24 /*credito*/) 
				AND 
				c.statusID in (67 /*aplicada*/) 
				AND  
				c.createdOn between '$dateFirst' and '$dateLast'
			group by 
				1
		");

		return $db->query($sql)->getResult();
   }

   function FinancieraCorea_Pagos_Mensuales($companyID,$dateFirst,$dateLast)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				MONTH(c.createdOn) AS Mes, 
				SUM(c.amount) AS Pagos
			FROM 
				tb_transaction_master c
			WHERE 
				c.transactionID = 23 /*abonos*/ 
				AND 
				c.isActive = 1 
				AND 
				c.statusID = 80 /*aplicadao*/ 
				AND 
				c.createdOn BETWEEN '$dateFirst' AND '$dateLast'
			GROUP BY 
				1 ;
		");

		return $db->query($sql)->getResult();
   }

   function FinancieraCorea_Interes_Mensual($companyID,$dateFirst,$dateLast)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				MONTH(c.createdOn) AS Mes,
				SUM(tdc.interest) as Interes 
			FROM 
				tb_transaction_master c 
				INNER JOIN tb_transaction_master_detail td ON 
					td.transactionMasterID = c.transactionMasterID 
				INNER JOIN tb_transaction_master_detail_credit tdc ON 
					tdc.transactionMasterDetailID = td.transactionMasterDetailID 
			WHERE 
				c.transactionID = 23 /*abonos*/ 
				AND 
				c.isActive = 1 
				AND 
				td.isActive = 1 
				AND 
				c.statusID = 80 /*aplicadao*/ 
				AND 
				c.createdOn between '$dateFirst' and '$dateLast'  
			GROUP BY 
				1 ;
		");

		return $db->query($sql)->getResult();
	}

	function FinancieraCorea_Capital_Mensual($companyID,$dateFirst,$dateLast)
   {
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				MONTH(c.createdOn) AS Mes,
				SUM(tdc.capital) as Capital 
			FROM 
				tb_transaction_master c 
				INNER JOIN tb_transaction_master_detail td ON 
					td.transactionMasterID = c.transactionMasterID 
				INNER JOIN tb_transaction_master_detail_credit tdc ON 
					tdc.transactionMasterDetailID = td.transactionMasterDetailID 
			WHERE 
				c.transactionID = 23 /*abonos*/ 
				AND 
				c.isActive = 1 
				AND 
				td.isActive = 1 
				AND 
				c.statusID = 80 /*aplicadao*/ 
				AND 
				c.createdOn between '$dateFirst' and '$dateLast'  
			GROUP BY 
				1 ;
		");

		return $db->query($sql)->getResult();
   	}





	function Default_Ventas_De_Contado_Mes_Actual($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
		  	SELECT 
				DAY(c.createdOn) as Dia,
				SUM(c.amount) as Venta 
			FROM 
				tb_transaction_master c 
			WHERE 
				c.transactionID = 19 /*factura*/ 
				AND 
				c.isActive = 1 
				AND 
				c.transactionCausalID in (21 /*Contado*/,23 /*Contado*/) 
				AND 
				c.statusID in (67 /*aplicada*/) 
				AND 
				c.createdOn between '$dateFirst' and '$dateLast'
			group by 
				1
		");

		return $db->query($sql)->getResult();
	}

	function Default_Ventas_De_Contado_Mensuales($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
		  	SELECT 
				u.nickname as Mes,
				SUM(c.amount) as Venta 
			FROM 
				tb_transaction_master c 
				inner join tb_user u on 
					c.createdBy = u.userID 
			WHERE 
				c.transactionID = 19 /*factura*/ 
				AND 
				c.isActive = 1 
				AND 
				c.transactionCausalID in (21 /*Contado*/,23 /*Contado*/) 
				AND 
				c.statusID in (67 /*aplicada*/) 
				AND  
				c.createdOn between '$dateFirst' and '$dateLast'
			group by 
				1
		");

		return $db->query($sql)->getResult();
	}

	function Default_Ventas_De_Credito_Mes_Actual($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
		  	SELECT 
				MONTH(c.createdOn) as Mes,
				SUM(c.amount) as Venta 
			FROM 
				tb_transaction_master c 
			WHERE 
				c.transactionID = 19 /*factura*/ 
				AND 
				c.isActive = 1 
				AND 
				c.transactionCausalID in (21 /*Contado*/,23 /*Contado*/) 
				AND 
				c.statusID in (67 /*aplicada*/) 
				AND  
				c.createdOn between '$dateFirst' and '$dateLast'
			group by 
				1
		");

		return $db->query($sql)->getResult();
	}

	function Default_Pagos_Mensuales($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
		  	SELECT 
				MONTH(c.createdOn) AS Mes, 
				SUM(c.amount) AS Pagos
			FROM 
				tb_transaction_master c
			WHERE 
				c.transactionID = 23 /*abonos*/ 
				AND 
				c.isActive = 1 
				AND 
				c.statusID = 80 /*aplicadao*/ 
				AND 
				c.createdOn BETWEEN '$dateFirst' AND '$dateLast'
			GROUP BY 
				1 ;
		");

		return $db->query($sql)->getResult();
	}





	function FunerariaBlandon_Servicios_Contratados($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT
				MONTH(c.createdOn ) AS Mes,
				SUM(c.amount) AS Total
			FROM 
				tb_transaction_master c 
			WHERE 
				c.isActive = 1 
				AND 
				c.transactionID = 19 /*abonos*/ 
				AND 
				c.statusID IN (67 /*aplicada*/ )  
				AND 
				c.transactionCausalID IN (22 /*credito*/ , 24 /*credito*/ ) 
				AND 
				c.createdOn BETWEEN '$dateFirst' AND '$dateLast'
			GROUP BY 
				1;
		");

		return $db->query($sql)->getResult();
	}

	function FunerariaBlandon_Pagos_Mensuales_Realizados($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				MONTH(c.createdOn ) AS Mes,
				SUM(c.amount) AS Total
			FROM 
				tb_transaction_master c 
			WHERE 
				c.isActive = 1 
				AND 
				c.transactionID = 23 /*abonos*/ 
				AND 
				c.statusID IN (80 /*aplicada*/ )  
				AND 
				c.createdOn BETWEEN '$dateFirst' AND '$dateLast'
			GROUP BY 
				1;
		");

		return $db->query($sql)->getResult();
	}

	function FunerariaBlandon_Cartera_De_Cobros($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				MONTH(u.dateApply) AS Mes,
				SUM(u.remaining ) AS Total 
			FROM 
				tb_customer_credit_document  c
				INNER JOIN tb_customer_credit_amoritization u ON 
					c.customerCreditDocumentID = u.customerCreditDocumentID 
			WHERE 
				c.isActive = 1 
				AND 	
				c.statusID IN (77 /*registrado*/ ) 
				AND 
				u.dateApply BETWEEN '$dateFirst' AND '$dateLast' 
			GROUP BY 
			1;
		");

		return $db->query($sql)->getResult();
	}

	function FunerariaBlandon_Facturacion_Contado($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				DAY(c.createdOn ) AS Dia,
				SUM(c.amount) AS Total
			FROM 
				tb_transaction_master c 
			WHERE 
				c.isActive = 1 
				AND 
				c.transactionID = 19 /*facturacion*/ 
				AND 
				c.statusID IN (67 /*aplicada*/ )  
				AND
				c.transactionCausalID IN (21 /*contado*/ , 23 /*contado*/ ) 
				AND 
				c.createdOn BETWEEN '$dateFirst' AND '$dateLast'
			GROUP BY 
				1;
		");

		return $db->query($sql)->getResult();
	}
	




	function PowerHouseGym_Ventas_De_Contado_Mes_Actual($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
		  	SELECT 
				DAY(c.createdOn) as Dia,
				SUM(c.amount) as Total 
			FROM 
				tb_transaction_master c 
			WHERE 
				c.transactionID = 19 /*factura*/ 
				AND 
				c.isActive = 1 
				AND 
				c.transactionCausalID in (21 /*Contado*/,23 /*Contado*/) 
				AND 
				c.statusID in (67 /*aplicada*/) 
				AND 
				c.createdOn between '$dateFirst' and '$dateLast'
			group by 
				1
		");

		return $db->query($sql)->getResult();
	}

	function PowerHouseGym_Ingresos_Por_Membresias($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT
				DAY(c.createdOn ) AS Dia,
				SUM(c.amount) AS Total
			FROM 
				tb_transaction_master c 
			WHERE 
				c.isActive = 1 
				AND 
				c.transactionID = 19 /*abonos*/ 
				AND 
				c.statusID IN (67 /*aplicada*/ )  
				AND 
				c.transactionCausalID IN (22 /*credito*/ , 24 /*credito*/ ) 
				AND 
				c.createdOn BETWEEN '$dateFirst' AND '$dateLast'
			GROUP BY 
				1;
		");

		return $db->query($sql)->getResult();
	}

	function PowerHouseGym_Proyeccion_De_Membresias($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				MONTH(u.dateApply) AS Mes,
				SUM(u.remaining ) AS Total 
			FROM 
				tb_customer_credit_document  c
				INNER JOIN tb_customer_credit_amoritization u ON 
					c.customerCreditDocumentID = u.customerCreditDocumentID 
			WHERE 
				c.isActive = 1 
				AND 	
				c.statusID IN (77 /*registrado*/ ) 
				AND 
				u.dateApply BETWEEN '$dateFirst' AND '$dateLast' 
			GROUP BY 
			1;
		");

		return $db->query($sql)->getResult();
	}

	function PowerHouseGym_Conteo_De_Membresias($companyID,$dateFirst,$dateLast)
	{
		$db = db_connect();

		$sql = "";
		$sql = sprintf("
			SELECT 
				MONTH(c.createdOn) as Mes,
				COUNT(c.transactionMasterID) as Total 
			FROM 
				tb_transaction_master c 
			WHERE 
				c.transactionID = 19 /*factura*/ 
				AND 
				c.isActive = 1 
				AND 
				c.transactionCausalID in (22 /*credito*/,24 /*credito*/) 
				AND 
				c.statusID in (67 /*aplicada*/) 
				AND 
				c.createdOn between '$dateFirst' and '$dateLast'
			group by 
				1
		");

		return $db->query($sql)->getResult();
	}
}
?>