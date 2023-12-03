<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Company_SubElement_Audit_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }    
   function listElementAudit($companyID,$elementID){
		$db 	= db_connect();
		$sql = "";
		$sql = sprintf("select x.companyID,x.elementID,x.subElementID,s.name");
		$sql = $sql.sprintf(" from tb_company_subelement_audit x ");
		$sql = $sql.sprintf(" inner join  tb_subelement s on x.subElementID = s.subElementID ");		
		$sql = $sql.sprintf(" where x.companyID = $companyID");	
		$sql = $sql.sprintf(" and x.elementID = $elementID");	
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
}
?>