<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Menu_Element_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   function get_rowByCompanyIDyElementID($companyID,$elementIDArray){
		$db 	= db_connect(); 	
				
		$sql = "";
		$sql = sprintf("select x.companyID,x.elementID,x.menuElementID,x.parentMenuElementID,
		x.display,x.address,x.orden,x.icon,x.template,x.nivel,x.iconWindowForm,x.formRedirectWindowForm,x.typeUrlRedirect");
		$sql = $sql.sprintf(" from tb_menu_element x");		
		$sql = $sql.sprintf(" inner join  tb_element e on e.elementID = x.elementID");
		$sql = $sql.sprintf(" inner join  tb_component_element ce on e.elementID = ce.elementID");
		$sql = $sql.sprintf(" inner join  tb_company_component cco on ce.componentID = cco.componentID");
		$sql = $sql.sprintf(" where x.companyID = $companyID");		
		$sql = $sql.sprintf(" and x.isActive = true");
		$sql = $sql.sprintf(" and cco.companyID = $companyID");

		if( $elementIDArray ){
		        $sql = $sql.sprintf(" and x.elementID in ( -1 ");
			foreach($elementIDArray as $elementID){
				$sql = $sql.sprintf(" , $elementID  ");
			}
		        $sql = $sql.sprintf(" ) ");
		}

		$sql = $sql.sprintf(" order by x.orden asc");
		
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
		
   }
   function get_rowByCompanyID($companyID){
		$db 	= db_connect(); 	
		$sql = "";
		$sql = sprintf("select tb_menu_element.companyID,tb_menu_element.elementID,tb_menu_element.menuElementID,
		tb_menu_element.parentMenuElementID,tb_menu_element.display,tb_menu_element.address,tb_menu_element.orden,
		tb_menu_element.icon,tb_menu_element.template,tb_menu_element.nivel,tb_menu_element.iconWindowForm,
		tb_menu_element.formRedirectWindowForm,tb_menu_element.typeUrlRedirect");
		$sql = $sql.sprintf(" from tb_menu_element");
		$sql = $sql.sprintf(" inner join  tb_element on tb_menu_element.elementID = tb_element.elementID");
		$sql = $sql.sprintf(" where tb_menu_element.companyID = ".$companyID." ");
		$sql = $sql.sprintf(" and tb_menu_element.isActive = 1 ");		
		$sql = $sql.sprintf(" order by tb_menu_element.orden asc");   
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
}
?>