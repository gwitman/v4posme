<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Transaction_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }  
   function get_rowByPK($companyID,$name){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,transactionID,name,description,workflowID,accountID,isCountable,reference1,reference2,reference3,generateTransactionNumber,decimalPlaces,isActive,classID,journalTypeID");
		$sql = $sql.sprintf(" from tb_transaction");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and name = '$name' ");
		$sql = $sql.sprintf(" and isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getCounterTransactionMaster($companyID,$transactionID,$statusID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_transaction tb");
		$sql = $sql.sprintf(" inner join  tb_transaction_master tm on tb.transactionID = tm.transactionID ");
		$sql = $sql.sprintf(" where tm.isActive = 1");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tm.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and tm.statusID = $statusID");		
   		return $db->query($sql)->getRow()->counter;
   }
   
   function getCountInput($companyID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_transaction tb");
		$sql = $sql.sprintf(" inner join  tb_transaction_master tm on tb.transactionID = tm.transactionID ");
		$sql = $sql.sprintf(" where tm.isActive = 1");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tb.signInventory = 1");
		
   		return $db->query($sql)->getRow()->counter;
   }
   function getCountOutput($companyID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_transaction tb");
		$sql = $sql.sprintf(" inner join  tb_transaction_master tm on tb.transactionID = tm.transactionID ");
		$sql = $sql.sprintf(" where tm.isActive = 1");
		$sql = $sql.sprintf(" and tm.companyID = $companyID");
		$sql = $sql.sprintf(" and tb.signInventory = -1");
		
   		return $db->query($sql)->getRow()->counter;
   }
   
   function getByCompanyAndTransaction($companyID,$transactionID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select companyID, transactionID, name, description, workflowID, isCountable, reference1, reference2, reference3, generateTransactionNumber, decimalPlaces, journalTypeID, signInventory, isActive");
		$sql = $sql.sprintf(" from tb_transaction");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and transactionID = $transactionID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getTransactionContabilizable($companyID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID, transactionID, name, description, workflowID, isCountable, reference1, reference2, reference3, generateTransactionNumber, decimalPlaces, journalTypeID, signInventory, isActive");
		$sql = $sql.sprintf(" from tb_transaction");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and isCountable= 1");		
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function update_app_posme($companyID,$transactionID,$data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);		
		
		return $builder->update($data);
   }
   
}
?>