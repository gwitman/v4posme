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
		$sql = sprintf("select companyID,transactionID,transactionMasterID,zoneID,routeID,referenceClientName,referenceClientIdentifier,receiptAmount,receiptAmountDol,reference1,reference2,changeAmount");
		$sql = $sql.sprintf(" from tb_transaction_master_info tm");		
		$sql = $sql.sprintf(" where tm.transactionMasterID = $transactionMasterID");
		$sql = $sql.sprintf(" and tm.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>