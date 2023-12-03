<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;



class Company_Default_Data_View_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function get_rowBy_CCCT($companyID,$componentID,$callerID,$targetComponentID){
		$db 	= db_connect();    
		$sql = "";
		$sql = sprintf("select companyID,componentID,callerID,dataViewID,targetComponentID");
		$sql = $sql.sprintf(" from tb_company_default_dataview");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and componentID = $componentID");
		$sql = $sql.sprintf(" and callerID = $callerID");
		$sql = $sql.sprintf(" and targetComponentID = $targetComponentID");		
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>