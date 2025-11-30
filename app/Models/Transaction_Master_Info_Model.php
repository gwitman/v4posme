<?php 
//posme:2023-02-27
namespace App\Models; 
use CodeIgniter\Model;

class Transaction_Master_Info_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function delete_app_posme($companyID,$transactionID,$transactionMasterID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_info");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);	
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_info");
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$transactionID,$transactionMasterID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_info");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionMasterID",$transactionMasterID);
		return $builder->update($data);
		
   }
   function get_rowByPK($companyID,$transactionID,$transactionMasterID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_info");    
		
		$sql = "";
		$sql = sprintf("
		select 
				transactionMasterInfoID,				
				companyID,
				transactionID,
				transactionMasterID,
				zoneID,routeID,mesaID,
				referenceClientName,
				referenceClientIdentifier,
				
				changeAmount,
				receiptAmountPoint,
				
				
				receiptAmount,
				receiptAmountDol,
				
				receiptAmountBank,
				receiptAmountBankID,
				receiptAmountBankReference,
				
				receiptAmountBankDol,
				receiptAmountBankDolID,
				receiptAmountBankDolReference,
				
				receiptAmountCard,
				receiptAmountCardBankID,
				receiptAmountCardBankReference,
				
				receiptAmountCardDol,
				receiptAmountCardBankDolID,
				receiptAmountCardBankDolReference,
				
				tm.reference1,
				tm.reference2,
				ci.name as zonaName,
				ci2.name as mesaName
		");
		$sql = $sql.sprintf(" from tb_transaction_master_info tm");		
		$sql = $sql.sprintf(" left join tb_catalog_item ci on  ci.catalogItemID = tm.zoneID ");		
		$sql = $sql.sprintf(" left join tb_catalog_item ci2 on  ci2.catalogItemID = tm.mesaID  ");		
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   function get_rowByTransactionNumberAndCreatedBy($companyID,$transactionNumber,$createdBy)
   {
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_info");    
		$sql = "";
		$sql = sprintf("
			select 
				tm.companyID,
				tm.transactionID,
				tm.transactionMasterID,
				tm.transactionNumber,
				tmi.reference2 
			from 
				tb_transaction_master tm 
				inner join tb_transaction_master_info tmi on 
					tm.transactionMasterID = tmi.transactionMasterID 
			where 
				tm.isActive = 1 and 
				tm.companyID = $companyID and 
				tm.createdBy = $createdBy and 
				tmi.reference2 = '$transactionNumber'
				
		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
}
?>