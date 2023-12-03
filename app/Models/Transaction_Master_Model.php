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
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive, ws.name as workflowStageName,tm.priorityID, tm.transactionOn2 , tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary ");
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
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID, tm.transactionOn2, tm.isTemplate,tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_rowByTransactionNumber($companyID,$transactionNumber){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master");    
		$sql = "";
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID , tm.transactionOn2 , tm.isTemplate, tm.periodPay,tm.nextVisit,tm.numberPhone,tm.printerQuantity,tm.entityIDSecondary  ");
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
		$sql = sprintf("select tm.companyID, tm.transactionID, tm.transactionMasterID, tm.branchID, tm.transactionNumber, tm.transactionCausalID,tm.entityID, tm.transactionOn, tm.statusIDChangeOn, tm.componentID,tm.tax1,tm.tax2,tm.tax3,tm.tax4,tm.discount,tm.subAmount, tm.note, tm.sign, tm.currencyID, tm.currencyID2, tm.exchangeRate, tm.reference1, tm.reference2, tm.reference3, tm.reference4, tm.statusID, tm.amount, tm.isApplied, tm.journalEntryID, tm.classID, tm.areaID, tm.sourceWarehouseID, tm.targetWarehouseID, tm.createdBy, tm.createdAt, tm.createdOn, tm.createdIn, tm.isActive ,tm.priorityID , tm.transactionOn2 , tm.isTemplate, tm.periodPay,tm.nextVisit,tm.numberPhone ,tm.printerQuantity,tm.entityIDSecondary  ");
		$sql = $sql.sprintf(" from tb_transaction_master tm");		
		$sql = $sql.sprintf(" where");
		$sql = $sql.sprintf(" 		tm.companyID = $companyID and ");
		$sql = $sql.sprintf(" 		tm.isActive= 1 and ");		
		$sql = $sql.sprintf(" 		tm.nextVisit is not null and ");	
		$sql = $sql.sprintf(" 		tm.nextVisit != '0000-00-00' and ");	
		$sql = $sql.sprintf(" 		tm.notificationID is null ");	

		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function get_rowInStatusRegister($companyID)
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
				ws.editableTotal = 1 ; 
		");
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>