<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Master_Detail_References_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   
   function delete_app_posme($transactionMasterDetailRefereceID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_references");		
  		$data["isActive"] = 0;		
		
		$builder->where("transactionMasterDetailRefereceID",$transactionMasterDetailRefereceID);	
		return $builder->update($data);
		
   } 
   function deleteWhereIDNotIn($listTMD_ID){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_references");
		$data["isActive"] = 0;
				
		$builder->whereNotIn("transactionMasterDetailID",$listTMD_ID);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_references");
		
		$result 	= $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($transactionMasterDetailRefereceID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_references");
		
			
		$builder->where("transactionMasterDetailRefereceID",$transactionMasterDetailRefereceID);	
		return $builder->update($data);
		
   }
   function get_rowByPK($transactionMasterDetailRefereceID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_references");    
   
		
	
			$sql = "";
			$sql = sprintf("	select 
									td.transactionMasterDetailRefereceID,
									td.transactionMasterDetailID,
									td.componentID,
									td.componentItemID,
									td.isActive,
									td.quantity ,
									td.createdOn 
				");
			$sql = $sql.sprintf("from tb_transaction_master_detail_references td");			
			$sql = $sql.sprintf("where ");
			$sql = $sql.sprintf(" 	td.transactionMasterDetailRefereceID = $transactionMasterDetailRefereceID ");		
			$sql = $sql.sprintf(" 	and td.isActive= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$componentID)
   {
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_master_detail_references");    
   
		
		if($componentID == 33 /*33 component:tb_item*/)
		{
			$sql = "";
			$sql = sprintf("	select 
									tdr.transactionMasterDetailRefereceID,
									tdr.transactionMasterDetailID,
									tdr.componentID,
									tdr.componentItemID,
									tdr.isActive,
									tdr.quantity ,
									tdr.createdOn,
									i.name 
				");
			$sql = $sql.sprintf("from tb_transaction_master_detail_references tdr ");			
			$sql = $sql.sprintf("inner join tb_transaction_master_detail td on td.transactionMasterDetailID = tdr.transactionMasterDetailID ");			
			$sql = $sql.sprintf("inner join tb_transaction_master tm on tm.transactionMasterID = td.transactionMasterID ");			
			$sql = $sql.sprintf("inner join tb_item i on tdr.componentItemID = i.itemID ");			
			$sql = $sql.sprintf("where ");
			$sql = $sql.sprintf(" 	tm.transactionMasterID = $transactionMasterID ");		
			$sql = $sql.sprintf(" 	and tdr.componentID = $componentID ");		
			$sql = $sql.sprintf(" 	and tdr.isActive= 1");	
		}
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
   
   
}
?>