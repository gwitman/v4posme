<?php 
//posme:2023-02-27
namespace App\Models\Core;
use CodeIgniter\Model;

class Menu_Element_Model extends Model  {
   function __construct(){		
      parent::__construct();
   }  
   
   //Util para sacar todas las funciones a al que tiene acceso los usuarios 
   //que usaran la pantalla web
   function get_rowByCompanyIDyElementID($companyID,$elementIDArray,$typeApp){
		$db 	= db_connect(); 	
				
		$sql = "";
		$sql = sprintf("
						select 
							x.companyID,x.elementID,x.menuElementID,x.parentMenuElementID,
							x.display,x.address,x.orden,x.icon,x.template,x.nivel,
							x.iconWindowForm,x.formRedirectWindowForm,x.typeUrlRedirect,x.description,x.styleCss,
							x.templateSnagit,
							x.typeApp
						from 
							tb_menu_element x
							inner join  tb_element e on e.elementID = x.elementID
							inner join  tb_component_element ce on e.elementID = ce.elementID
							inner join  tb_company_component cco on ce.componentID = cco.componentID
						where 
							x.companyID = $companyID
							and x.isActive = true
							and cco.companyID = $companyID
							and cco.companyID = $companyID 
							and ifnull(x.typeApp,'') = '".$typeApp."' 
						");

		
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
   
   //Sirver para mostrar el listado de menu en una ventana emergente
   //Que se usa en la pantalal de configuracion de roles para seleccionar a cual de ellos tiene acceso 
   //el usuario
   function get_rowByCompanyID($companyID,$typeApp){
		$db 	= db_connect(); 	
		$sql = "";
		$sql = sprintf("select tb_menu_element.companyID,tb_menu_element.elementID,tb_menu_element.menuElementID,
		tb_menu_element.parentMenuElementID,tb_menu_element.display,tb_menu_element.address,tb_menu_element.orden,
		tb_menu_element.icon,tb_menu_element.template,tb_menu_element.nivel,tb_menu_element.iconWindowForm,
		tb_menu_element.formRedirectWindowForm,tb_menu_element.typeUrlRedirect,tb_menu_element.description,tb_menu_element.styleCss,
		tb_menu_element.templateSnagit,tb_menu_element.typeApp");
		$sql = $sql.sprintf(" from tb_menu_element");
		$sql = $sql.sprintf(" inner join  tb_element on tb_menu_element.elementID = tb_element.elementID");
		$sql = $sql.sprintf(" where tb_menu_element.companyID = ".$companyID." ");
		$sql = $sql.sprintf(" and tb_menu_element.isActive = 1 ");				
		$sql = $sql.sprintf(" order by tb_menu_element.typeApp asc, tb_menu_element.orden asc");   
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }
   
   function get_rowByUserID($companyID,$userID,$typeApp){
	   $db 	= db_connect(); 	
		$sql = "";
		$sql = sprintf("
				select 
					me.menuElementID,
					me.display,
					me.typeApp,
					me.address,
					me.icon,
					me.typeMenuElementID,
					up.selected,
					up.inserted,
					up.deleted,
					up.edited 
				from 
					tb_element e  
					inner join tb_menu_element me on 
						me.elementID = e.elementID 
					inner join tb_user_permission up on 
						up.elementID = me.elementID 
					inner join tb_role r on 
						r.roleID = up.roleID 
					inner join tb_membership mem on 
						mem.roleID = r.roleID 
					inner join tb_user usr on 
						usr.userID = mem.userID 
				where 
					me.isActive = 1 and 
					me.typeMenuElementID in (5 /*let*/,8 /*popup*/ )  and 
					r.isActive = 1 and 
					usr.isActive = 1 and 
					usr.companyID = $companyID and 
					usr.userID = $userID 
					/*role , usuario */
				order by 
					me.orden 
					
					

		");
		
		//Ejecutar Consulta
		return $db->query($sql)->getResult();
   }

}
?>