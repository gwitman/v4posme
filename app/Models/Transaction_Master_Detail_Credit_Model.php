<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Master_Detail_Credit_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   
   function insert_app_posme($data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_credit");
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($transactionMasterDetailID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_credit");
		
		$builder->where("transactionMasterDetailID",$transactionMasterDetailID);	
		return $builder->update($data);
		
   }
   function delete_app_posme($transactionMasterDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_credit");
		
		$builder->where("transactionMasterDetailID",$transactionMasterDetailID);	
		$builder->delete();
		
   }
   function get_rowByPK($transactionMasterDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_credit");
		$sql = "";
		$sql = sprintf("select td.transactionMasterDetailCreditID,td.transactionMasterDetailID,td.capital,td.interest,td.dayDalay,td.interestMora,td.currencyID,td.exchangeRate,td.reference1,td.reference2,td.reference3,td.reference4,td.reference5,td.reference6,td.reference7,td.reference8,td.reference9");
		$sql = $sql.sprintf(" from tb_transaction_master_detail_credit td");
		$sql = $sql.sprintf(" where td.transactionMasterDetailID = $transactionMasterDetailID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function deleteWhereIDNotIn( $transactionMasterID,$listTMD_ID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_credit");
		
		$builder->where("transactionMasterID",$transactionMasterID);
		$builder->whereNotIn("transactionMasterDetailID",$listTMD_ID);
		
		$builder->delete();
		
   }
   
   
}
?>