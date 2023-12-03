<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Log_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }  
   function get_rowByPK($companyID,$branchID,$loginID,$token){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select logID,companyID,branchID,loginID,token,procedureName,code,description,createdOn");
		$sql = $sql.sprintf(" from tb_log");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID");
		$sql = $sql.sprintf(" and loginID = $loginID");
		$sql = $sql.sprintf(" and token = '$token' ");
		$sql = $sql.sprintf(" order by createdOn desc");
		$sql = $sql.sprintf(" limit 0,1 ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rowByNameParameterOutput($companyID,$branchID,$loginID,$token,$nameParameterOutput){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select logID,companyID,branchID,loginID,token,procedureName,code,description,createdOn");
		$sql = $sql.sprintf(" from tb_log");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and branchID = $branchID");
		$sql = $sql.sprintf(" and loginID = $loginID");
		$sql = $sql.sprintf(" and token = '$token' ");
		$sql = $sql.sprintf(" and procedureName = '$nameParameterOutput' ");
		$sql = $sql.sprintf(" order by createdOn desc");
		$sql = $sql.sprintf(" limit 0,1 ");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
	}
}
?>