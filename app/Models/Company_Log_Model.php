<?php 
//posme:2023-02-27
namespace App\Models;
use CodeIgniter\Model;

class Company_Log_Model extends Model  {
   function __construct(){	
      parent::__construct();
   }
   function getInfo($companyID,$branchID,$loginID,$app){
		$db 		= db_connect();
		
		$sql = "";
		$sql = sprintf("select companyLogID,companyID,branchID,loginID,createdOn,sourceName,description");
		$sql = $sql.sprintf(" from tb_company_log");
		$sql = $sql.sprintf(" where companyID = $companyID");		
		$sql = $sql.sprintf(" and branchID = $branchID");		
		$sql = $sql.sprintf(" and loginID = $loginID");		
		$sql = $sql.sprintf(" and sourceName = '$app' ");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
  
}
?>