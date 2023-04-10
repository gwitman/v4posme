<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Company_Component_Model extends Model  {
   function __construct(){
		
      parent::__construct();
   }  
   function get_rowByPK($companyID,$componentID){
		$db 	= db_connect(); 
		
		$sql = "";
		$sql = sprintf("select companyID,componentID");
		$sql = $sql.sprintf(" from tb_company_component");
		$sql = $sql.sprintf(" where companyID = $companyID");
		$sql = $sql.sprintf(" and componentID = $componentID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getRow();
   }
}
?>