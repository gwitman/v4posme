<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Profile_Detail_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function getByCompanyAndTransactionAndCausal($companyID,$transactionID,$causalID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select tc.companyID, tc.transactionID, tc.transactionCausalID, tc.profileDetailID, tc.conceptID, tc.accountID, tc.classID, tc.sign,tp.name as conceptDescription,a.accountNumber as accountDescription,cc.number as centerCostDescription");
		$sql = $sql.sprintf(" from tb_transaction_profile_detail tc");
		$sql = $sql.sprintf(" inner join  tb_transaction_concept tp on tc.conceptID = tp.conceptID and tc.transactionID = tp.transactionID ");
		$sql = $sql.sprintf(" inner join  tb_account a on tc.accountID = a.accountID and tc.companyID = a.companyID");
		$sql = $sql.sprintf(" left join  tb_center_cost cc on tc.classID = cc.classID and tc.companyID = cc.companyID");
		$sql = $sql.sprintf(" where tc.companyID = $companyID");
		$sql = $sql.sprintf(" and tc.transactionID = $transactionID");
		$sql = $sql.sprintf(" and tc.transactionCausalID = $causalID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   function getByCompanyAndTransactionAndCausalAndProfileDetailID($companyID,$transactionID,$causalID,$profileDetailID){
		$db 	= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyID, transactionID, transactionCausalID, profileDetailID, conceptID, accountID, classID, sign");
		$sql = $sql.sprintf(" from tb_transaction_profile_detail tc");		
		$sql = $sql.sprintf(" where tc.companyID = $companyID");		
		$sql = $sql.sprintf(" and tc.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and tc.transactionCausalID = $causalID");	
		$sql = $sql.sprintf(" and tc.profileDetailID = $profileDetailID");	
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function delete_app_posme($companyID,$transactionID,$causalID,$profileDetailID){
		$db 	= db_connect();
		$builder	= $db->table("tb_transaction_profile_detail");
		
		$builder->where("companyID",$companyID);
		$builder->where("transactionID",$transactionID);	
		$builder->where("transactionCausalID",$causalID);	
		$builder->where("profileDetailID",$profileDetailID);	
		$builder->delete("");
		
   }
   function insert_app_posme($data){
		$db 		= db_connect();
		$builder	= $db->table("tb_transaction_profile_detail");
   		$result 	= $builder->insert($data);
		return $db->insertID();		
		
   }
}
?>