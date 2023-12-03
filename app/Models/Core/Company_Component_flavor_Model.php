<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Company_Component_Flavor_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }    
   function get_rowByCompanyAndComponentAndComponentItemID($companyID,$componentID,$componentItemID){
		$db = db_connect();
		
		$sql = "";
		$sql = sprintf("select e.companyID,e.componentID,e.componentItemID,e.flavorID");
		$sql = $sql.sprintf(" from tb_company_component_flavor e");
		$sql = $sql.sprintf(" where e.companyID = $companyID");
		$sql = $sql.sprintf(" and e.componentID = $componentID");		
		$sql = $sql.sprintf(" and e.componentItemID = $componentItemID");		
		
		//Ejecutar Consulta
		 return $db->query($sql)->getRow();
		
   }
}
?>