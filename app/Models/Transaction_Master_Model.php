<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Master_Model extends Model  {
   function __construct(){
	
      parent::__construct();
   }
   function delete_app_posme($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");
		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);
		
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$transactionID,$transactionMasterID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");    
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.descriptionReference, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive, ws.name as workflowStageName,tm.priorityID, tm.transactionOn2 , tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary , tm.dayExcluded  ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" inner join  tb_workflow_stage ws on tm.statusID = ws.workflowStageID");
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByTransactionMasterID($companyID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");   
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID, tm.transactionOn2, tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary, tm.dayExcluded ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByTransactionID_And_EntityID($companyID,$transactionID,$entityID)
   {
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");   
		$sql = "";
		$sql = sprintf(
			"select 
				tm.companyID, 
				tm.transactionID, tm.transactionMasterID, tm.branchID, 
				tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, 
				tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign,
				tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, 
				tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, 
				tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, 
				tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID, tm.transactionOn2, 
				tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary, 
				tm.dayExcluded 
			");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where tm.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.entityID = $entityID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		$sql = $sql.sprintf(" order by  tm.transactionMasterID desc limit 10 ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByTransactionNumber($companyID,$transactionNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");    
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID , tm.transactionOn2 , tm.isTemplate, tm.periodPay,tm.nextVisit,tm.numberPhone,tm.printerQuantity,tm.entityIDSecondary , tm.dayExcluded ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where tm.transactionNumber = '$transactionNumber' ");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByNotification($companyID)
   {
	   $db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");    
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID , tm.transactionOn2 , tm.isTemplate, tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary , tm.dayExcluded  ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where");
		$sql = $sql.sprintf(" 		tm.companyID = $companyID and ");
		$sql = $sql.sprintf(" 		tm.isActive= 1 and ");		
		$sql = $sql.sprintf(" 		tm.nextVisit is not null and ");	
		$sql = $sql.sprintf(" 		tm.nextVisit != '0000-00-00' and ");	
		$sql = $sql.sprintf(" 		tm.notificationID is null  ");	

		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowInStatusRegister($companyID,$transactionMasterID)
   {
	   	$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");    
   
		
	
		$sql = "";
		$sql = sprintf("
			select 
				tm.transactionNumber,
				ws.`name` as nameStatus
			from 
				tb_transaction_master tm 
				inner join tb_workflow_stage ws on 
					tm.statusID = ws.workflowStageID 
			where 
				tm.isActive = 1 and 
				tm.companyID = $companyID and 
				ws.editableTotal = 1 and 
				tm.transactionMasterID != $transactionMasterID 
		");
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByNumberExoneration($companyID,$exonerationNumber)
   {
	   	$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master");    
   
		
	
		$sql = "";
		$sql = sprintf("
			select 
				t.transactionNumber,
				tr.reference1 as  exonerationNumber 
			from 
				tb_transaction_master t 
				inner join tb_workflow_stage ws on 
					ws.workflowStageID = t.statusID 
				inner join tb_transaction_master_references tr on 
					tr.transactionMasterID = t.transactionMasterID 
			where 
				t.transactionID = 19 /*factura*/ and 
				t.isActive = 1 and 
				ws.aplicable = 1 and 
				tr.reference1 != '' and 
				tr.reference1 = '$exonerationNumber' 
		");
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }

	function get_ZonasByCatalogItemID($catalogItemId):array{
		$db 		= db_connect();
		$builder	= $db->table("tb_catalog_item tci")
						->distinct()
						->select("tci.catalogID,
							tci.catalogItemID,
							tci.name,
							tci.display,
							tci.flavorID,
							tci.description,
							tci.sequence,
							tci.parentCatalogID,
							tci.parentCatalogItemID,
							tci.ratio, 
							IF(
								(
								SELECT 
									MIN(ttmi.transactionMasterID)
								FROM 
									tb_transaction_master ttm  
									INNER JOIN tb_transaction_master_info ttmi
										ON ttm.transactionMasterID = ttmi.transactionMasterID
									INNER JOIN tb_workflow_stage tws
										ON ttm.statusID = tws.workflowStageID
										AND tws.isInit = 1
								WHERE 
									tci.catalogItemID = ttmi.zoneID
									AND ttm.transactionID = 19
									AND ttm.isActive = 1
									AND ttm.transactionOn >= NOW() - INTERVAL 1 DAY
								) IS NULL,tci.reference1, REPLACE(tci.reference1, '.', '_bussy.')
							) AS reference1,
							tci.reference2,
							tci.reference3,
							tci.reference4")	
		->whereIn("tci.catalogItemID",$catalogItemId);
		return $builder->get()->getResultObject();
	}

	function get_MesasByCatalogItemID($catalogItemId):array{
		$db 		= db_connect();
		$builder	= $db->table("tb_catalog_item tci")
						->select(" tci.catalogID,
								tci.catalogItemID,
								tci.name,
								tci.display,
								tci.flavorID,
								tci.description,
								tci.sequence,
								tci.parentCatalogID,
								tci.parentCatalogItemID,
								tci.ratio, 
								
								IF(
									(								
									SELECT 
										MIN(ttmi.transactionMasterID)
									FROM 
										tb_transaction_master ttm  
										INNER JOIN tb_transaction_master_info ttmi
											ON ttm.transactionMasterID = ttmi.transactionMasterID
										INNER JOIN tb_workflow_stage tws
											ON ttm.statusID = tws.workflowStageID
											AND tws.isInit = 1
									WHERE 
										tci.catalogItemID = ttmi.mesaID
										AND ttm.transactionID = 19
										AND ttm.isActive = 1
										AND ttm.transactionOn >= NOW() - INTERVAL 1 DAY
									) IS NULL,tci.reference1, 
									REPLACE(tci.reference1, '.', '_bussy.')
								) AS reference1,
								
								IFNULL(
									(
										SELECT 
											MIN(ttmi.transactionMasterID)
										FROM 
											tb_transaction_master ttm  
											INNER JOIN tb_transaction_master_info ttmi
												ON ttm.transactionMasterID = ttmi.transactionMasterID
											INNER JOIN tb_workflow_stage tws
												ON ttm.statusID = tws.workflowStageID
												AND tws.isInit = 1
											WHERE 
												tci.catalogItemID = ttmi.mesaID
												AND ttm.transactionID = 19
												AND ttm.isActive = 1
												AND ttm.transactionOn >= NOW() - INTERVAL 1 DAY
									),
									0
								) AS reference2,
								tci.reference3,
								tci.reference4")
						->distinct()
						->whereIn("tci.catalogItemID",$catalogItemId);
						
		return $builder->get()->getResultObject();
	}
}
?>