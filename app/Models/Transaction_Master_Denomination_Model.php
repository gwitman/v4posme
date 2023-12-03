<?php 
//posme:2023-02-27
namespace App\Models; 
use CodeIgniter\Model;

class Transaction_Master_Denomination_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function delete_app_posme($transactionMasterID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_denomination");
		
		$builder->where("transactionMasterID",$transactionMasterID);
		$builder->delete();
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_denomination");
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($transactionMasterDenominationID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_denomination");
		
		$builder->where("transactionMasterDenominationID",$transactionMasterDenominationID);
		return $builder->update($data);
		
   }
   function get_rowByTransactionMaster($companyID,$transactionID,$transactionMasterID)
   {
	    $db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_denomination");    
		
		$sql = "";
		$sql = sprintf("
				select 
					i.companyID,
					i.catalogItemID,
					i.currencyID,
					i.quantity,
					i.reference1,
					i.reference2,
					i.isActive,
					i.transactionID,
					i.transactionMasterDenominationID,
					i.transactionMasterID,
					i.componentID,
					i.exchangeRate,
					i.ratio,
					ci.name as denominationName
				from 
					tb_transaction_master_denomination i 
					inner join tb_catalog_item ci on 
						ci.catalogItemID = i.catalogItemID 
				where 
					i.isActive = 1 and 
					i.companyID = $companyID and 
					i.transactionID = $transactionID and 
					i.transactionMasterID = $transactionMasterID
				order by 
					ci.sequence
   
		");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByPK($transactionMasterDenominationID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_denomination");    
		
		
		
		$sql = "";
		$sql = sprintf("
				select 
					i.companyID,
					i.catalogItemID,
					i.currencyID,
					i.quantity,
					i.reference1,
					i.reference2,
					i.isActive,
					i.transactionID,
					i.transactionMasterDenominationID,
					i.transactionMasterID,
					i.componentID,
					i.exchangeRate,
					i.ratio,
					ci.name as denominationName
				from 
					tb_transaction_master_denomination i 
					inner join tb_catalog_item ci on 
						ci.catalogItemID = i.catalogItemID 
				where 
					i.isActive = 1 and 
					i.transactionMasterDenominationID = $transactionMasterDenominationID 
				order by 
					ci.sequence
   
		");
		
	
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>