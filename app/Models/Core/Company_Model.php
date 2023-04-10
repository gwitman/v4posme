<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Company_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }  
   function get_rowByPK($companyID){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,name,createdOn,address");
		$sql = $sql.sprintf(" from tb_company");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
   function get_rows(){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,name,createdOn,address");
		$sql = $sql.sprintf(" from tb_company");
		$sql = $sql.sprintf(" where isActive= 1");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>