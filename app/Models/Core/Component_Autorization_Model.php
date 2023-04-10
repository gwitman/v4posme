<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;


class Component_Autorization_Model extends Model  {
    function __construct(){		
      parent::__construct();
    }
    function get_rowByCompanyID($companyID){
		$db 	= db_connect();   
		$sql = "";
		$sql = sprintf("select ca.companyID,ca.componentAutorizationID,ca.name");
		$sql = $sql.sprintf(" from tb_component_autorization ca ");
		$sql = $sql.sprintf(" where ca.companyID = $companyID");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }   
}
?>