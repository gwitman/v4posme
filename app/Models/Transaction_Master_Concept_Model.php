<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Transaction_Master_Concept_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }
   function get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$componentID){
		$db 		= db_connect();
		
		
	    $sql = "";
		$sql = sprintf("select cc.companyID,cc.componentID,cc.componentItemID,cc.name,cc.valueIn,cc.valueOut");
	    $sql = $sql.sprintf(" from tb_transaction_master tm");
		$sql = $sql.sprintf(" inner join  tb_transaction_master_detail td on tm.companyID = td.companyID and tm.transactionID = td.transactionID and tm.transactionMasterID = td.transactionMasterID");
		$sql = $sql.sprintf(" inner join  tb_item i on td.companyID = i.companyID and td.componentItemID = i.itemID");		
		$sql = $sql.sprintf(" inner join  tb_company_component_concept cc on cc.componentItemID = i.itemID");
		$sql = $sql.sprintf(" where td.companyID = $companyID");
		$sql = $sql.sprintf(" and td.transactionID = $transactionID");		
		$sql = $sql.sprintf(" and td.transactionMasterID = $transactionMasterID");		
		$sql = $sql.sprintf(" and cc.componentID = $componentID");		
		$sql = $sql.sprintf(" and td.isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
  
}
?>