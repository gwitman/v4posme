<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Causal_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function getCausalByBranch($companyID,$transactionID,$branchID){
		$db 	= db_connect();
		
	    $sql = "";
		$sql = sprintf("select tc.companyID, tc.transactionID, tc.transactionCausalID, tc.branchID, tc.name, tc.warehouseSourceID, tc.warehouseTargetID, tc.isDefault, tc.isActive");
		$sql = $sql.sprintf(" from tb_transaction_causal tc");		
		$sql = $sql.sprintf(" where tc.companyID = $companyID");		
		$sql = $sql.sprintf(" and tc.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and tc.branchID = $branchID");		
		$sql = $sql.sprintf(" and tc.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getCausalDefaultID($companyID,$transactionID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select tc.companyID, tc.transactionID, tc.transactionCausalID, tc.branchID, tc.name, tc.warehouseSourceID, tc.warehouseTargetID, tc.isDefault, tc.isActive");
		$sql = $sql.sprintf(" from tb_transaction_causal tc");
		$sql = $sql.sprintf(" where tc.companyID = $companyID");		
		$sql = $sql.sprintf(" and tc.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and tc.isActive= 1");		
		$sql = $sql.sprintf(" and tc.isDefault= 1");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function getByCompanyAndTransaction($companyID,$transactionID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select tc.companyID, tc.transactionID, tc.transactionCausalID, tc.branchID, tc.name, tc.warehouseSourceID, tc.warehouseTargetID, tc.isDefault, tc.isActive,b.name as branch,w.name as warehouseSourceDescription, w2.name as warehouseTargetDescription");
		$sql = $sql.sprintf(" from tb_transaction_causal tc");
		$sql = $sql.sprintf(" inner join  tb_branch b on tc.branchID = b.branchID");
		$sql = $sql.sprintf(" left join  tb_warehouse w on tc.warehouseSourceID = w.warehouseID");
		$sql = $sql.sprintf(" left join  tb_warehouse w2 on tc.warehouseTargetID = w2.warehouseID");
		$sql = $sql.sprintf(" where tc.companyID = $companyID");		
		$sql = $sql.sprintf(" and tc.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and tc.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByCompanyAndTransactionAndCausal($companyID,$transactionID,$causalID){
		$db 	= db_connect();
		
		
		$sql = "";
		$sql = sprintf("select companyID, transactionID, transactionCausalID, branchID, name, warehouseSourceID, warehouseTargetID, isDefault, isActive");
		$sql = $sql.sprintf(" from tb_transaction_causal tc");		
		$sql = $sql.sprintf(" where tc.companyID = $companyID");		
		$sql = $sql.sprintf(" and tc.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and tc.transactionCausalID = $causalID");		
		$sql = $sql.sprintf(" and tc.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function delete_app_posme($companyID,$transactionID,$listCausal){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_causal");
		
		$data["isActive"] = 0;
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->whereNotIn("transactionCausalID",$listCausal);	
		return $builder->update($data);
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_causal");	
		
		$result = $builder->insert($data);
		return $db->insertID();		
		
   }
   function update_app_posme($companyID,$transactionID,$causalID,$data){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_causal");		
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionCausalID",$causalID);	
		return $builder->update($data);
		
   }
   function countCausalDefault($companyID,$transactionID){
		$db 		= db_connect();
		
		
		$sql = "";
		$sql = $sql.sprintf(" select count(*) as counter ");
		$sql = $sql.sprintf(" from tb_transaction_causal");
		$sql = $sql.sprintf(" where isActive = 1");
		$sql = $sql.sprintf(" and isDefault = 1");
		$sql = $sql.sprintf(" and companyID = $companyID");
		$sql = $sql.sprintf(" and transactionID = $transactionID");
		
   		return $db->query($sql)->getRow()->counter;
   }
}
?>