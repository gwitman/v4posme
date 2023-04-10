<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Transaction_Concept_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function get_rowByPK($companyID,$transactionID,$name){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,transactionID,conceptID,name,orden,sign,visible,isActive");
		$sql = $sql.sprintf(" from tb_transaction_concept");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and transactionID = $transactionID");
		$sql = $sql.sprintf(" and name = '$name' ");
		$sql = $sql.sprintf(" and isActive= 1");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   
    function getByCompanyAndTransaction($companyID,$transactionID){
		$db 	= db_connect();
		$sql 	= "";
		$sql = sprintf("select companyID, transactionID, conceptID, name, orden, sign, visible, isActive");
		$sql = $sql.sprintf(" from tb_transaction_concept");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and transactionID = $transactionID");		
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
}
?>