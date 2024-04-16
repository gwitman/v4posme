<?php
//posme:2023-02-27
namespace App\Libraries;

use App\Models\Core\Bd_Model;
use App\Models\Core\Branch_Model;
use App\Models\Core\Catalog_Item_convertion_Model;
use App\Models\Core\Catalog_Item_Model;
use App\Models\Core\Catalog_Model;
use App\Models\Core\Company_Component_flavor_Model;
use App\Models\Core\Company_Component_Model;
use App\Models\Core\Company_Data_View_Model;
use App\Models\Core\Company_Default_Data_View_Model;
use App\Models\Core\Company_Model;
use App\Models\Core\Company_Parameter_Model;
use App\Models\Core\Company_Subelement_audit_Model;
use App\Models\Core\Component_Audit_detail_Model;
use App\Models\Core\Component_Audit_Model;
use App\Models\Core\Component_Autorization_Model;

use App\Models\Core\Component_Model;
use App\Models\Core\Counter_Model;
use App\Models\Core\Currency_Model;
use App\Models\Core\Data_View_Model;
use App\Models\Core\Element_Model;
use App\Models\Core\Exchangerate_Model;
use App\Models\Core\Log_Model;
use App\Models\Core\Membership_Model;
use App\Models\Core\Menu_Element_Model;
use App\Models\Core\Parameter_Model;
use App\Models\Core\Role_Autorization_Model;
use App\Models\Core\Role_Model;
use App\Models\Core\Sub_Element_Model;
use App\Models\Core\Transaction_Concept_Model;
use App\Models\Core\Transaction_Model;
use App\Models\Core\User_Model;
use App\Models\Core\User_Permission_Model;
use App\Models\Core\Workflow_Model;
use App\Models\Core\Workflow_Stage_Model;
use App\Models\Core\Workflow_Stage_Relation_Model;



use App\Models\Accounting_Balance_Model;
use App\Models\Account_Level_Model;
use App\Models\Account_Model;
use App\Models\Account_Type_Model;
use App\Models\Biblia_Model;
use App\Models\Center_Cost_Model;
use App\Models\Company_Component_Concept_Model;
use App\Models\Company_Currency_Model;
use App\Models\Company_Log_Model;
use App\Models\Component_Cycle_Model;
use App\Models\Component_Period_Model;
use App\Models\Credit_Line_Model;
use App\Models\Customer_Consultas_Sin_Riesgo_Model;
use App\Models\Customer_Credit_Amortization_Model;
use App\Models\Customer_Credit_Document_Endity_Related_Model;
use App\Models\Customer_Credit_Document_Model;
use App\Models\Customer_Credit_Line_Model;
use App\Models\Customer_Credit_Model;
use App\Models\Customer_Model;
use App\Models\Employee_Calendar_Pay_detail_Model;
use App\Models\Employee_Calendar_Pay_Model;
use App\Models\Employee_Model;
use App\Models\Entity_Account_Model;
use App\Models\Entity_Email_Model;
use App\Models\Entity_Model;
use App\Models\Entity_Phone_Model;
use App\Models\Error_Model;
use App\Models\Fixed_Assent_Model;
use App\Models\Itemcategory_Model;
use App\Models\Itemwarehouse_Model;
use App\Models\Item_Data_Sheet_Detail_Model;
use App\Models\Item_Data_Sheet_Model;
use App\Models\Item_Model;
use App\Models\Item_Warehouse_Expired_Model;
use App\Models\Journal_Entry_Detail_Model;
use App\Models\Journal_Entry_Model;
use App\Models\Legal_Model;
use App\Models\List_Price_Model;
use App\Models\Natural_Model;
use App\Models\Notification_Model;
use App\Models\Price_Model;
use App\Models\Provideritem_Model;
use App\Models\Provider_Model;
use App\Models\Relationship_Model;
use App\Models\Remember_Model;
use App\Models\Tag_Model;
use App\Models\Transaction_Causal_Model;

use App\Models\Transaction_Master_Concept_Model;
use App\Models\Transaction_Master_Detail_Credit_Model;
use App\Models\Transaction_Master_Detail_Model;
use App\Models\Transaction_Master_Info_Model;
use App\Models\Transaction_Master_Model;

use App\Models\Transaction_Profile_Detail_Model;
use App\Models\Userwarehouse_Model;
use App\Models\User_Tag_Model;
use App\Models\Warehouse_Model;


class core_web_menu {
   
   /**********************Variables Estaticas********************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
	private $CI; 
	
	
   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){		
        
   }
   function get_menu_top($companyID=null,$branchID=null,$roleID=null,$userID=null){
		
		//Cargar Modelos		
		$Component_Model = new Component_Model();
		$Company_Component_Model = new Company_Component_Model();
		$Element_Model = new Element_Model();
		$Menu_Element_Model = new Menu_Element_Model();
		$User_Permission_Model = new User_Permission_Model();
		$Role_Model = new Role_Model();
		
		
		//Obtener el rol del usuario
		$objRole 	= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new \Exception('NO EXISTE EL ROL DEL USUARIO');
		
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementSeguridad =	$Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_TOP);
		if(!$listElementSeguridad)
		return null;
			
		
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDSeguridad;
		$listElementIDPermitied;		
		foreach($listElementSeguridad AS $i){ 
			$listElementIDSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDSeguridad, $tmp);
		}
		

		
		//Obtener la lista de menu_element del componente de  seguridad...
		if($objRole->isAdmin && $listElementIDSeguridad)	{	
			$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDSeguridad);			
		}
		else if ($listElementIDPermitied){
			$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		}
		
		if(!$listMenuElement)
		return null; 	
	
	   
		
		//Resultado 
		return $listMenuElement;
		
    }
   function get_menu_left($companyID=null,$branchID=null,$roleID=null,$userID=null){
		
		//Cargar Modelos		
		$Component_Model = new Component_Model();
		$Company_Component_Model = new Company_Component_Model();
		$Element_Model = new Element_Model();
		$Menu_Element_Model = new Menu_Element_Model();
		$User_Permission_Model = new User_Permission_Model();
		$Role_Model = new Role_Model();
		
		
		//Obtener el rol del usuario
		$objRole 	= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new \Exception('NO EXISTE EL ROL DEL USUARIO');
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementNotSeguridad =	$Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_LEFT);
		if(!$listElementNotSeguridad)
		return null;
			
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDNotSeguridad;
		$listElementIDPermitied;		
		foreach($listElementNotSeguridad AS $i){ 
			$listElementIDNotSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDNotSeguridad, $tmp);
		}
		
		
		//Obtener la lista de menu_element del componente de  seguridad...
		if($objRole->isAdmin && $listElementIDNotSeguridad)		
		$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDNotSeguridad);
		else if ($listElementIDPermitied)
		$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		
		if(!$listMenuElement)
		return null; 	
	
	
		//Resultado  
		return $listMenuElement;
		
    }
    function get_menu_body_report($companyID=null,$branchID=null,$roleID=null,$userID=null){
		//Cargar Modelos		
		$Component_Model = new Component_Model();
		$Company_Component_Model = new Company_Component_Model();
		$Element_Model = new Element_Model();
		$Menu_Element_Model = new Menu_Element_Model();
		$User_Permission_Model = new User_Permission_Model();
		$Role_Model = new Role_Model();
		
		
		//Obtener el rol del usuario
		$objRole 	= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new \Exception('NO EXISTE EL ROL DEL USUARIO');
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementNotSeguridad =	$Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_BODY);
		if(!$listElementNotSeguridad)
		return null;
			
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDNotSeguridad;
		$listElementIDPermitied;		
		foreach($listElementNotSeguridad AS $i){ 
			$listElementIDNotSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDNotSeguridad, $tmp);
		}
		
		$listMenuElement	= null;
		//Obtener la lista de menu_element del componente de  seguridad...
		if($objRole->isAdmin && $listElementIDNotSeguridad)		
		$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDNotSeguridad);
		else if ($listElementIDPermitied)
		$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		
		if(!$listMenuElement)
		return null; 	
	
	
		//Resultado  
		return $listMenuElement;
		
	}
	function get_menu_hidden_popup($companyID=null,$branchID=null,$roleID=null,$userID=null){
		//Cargar Modelos		
		$Component_Model = new Component_Model();
		$Company_Component_Model = new Company_Component_Model();
		$Element_Model = new Element_Model();
		$Menu_Element_Model = new Menu_Element_Model();
		$User_Permission_Model = new User_Permission_Model();
		$Role_Model = new Role_Model();
		
		
		//Obtener el rol del usuario
		$objRole 	= $Role_Model->get_rowByPK($companyID,$branchID,$roleID);
		if(!$objRole)		
		throw new \Exception('NO EXISTE EL ROL DEL USUARIO');
		
		//Obtener la lista de elementos tipo pagina, que pertenescan al componente de seguridad
		$listElementNotSeguridad =	$Element_Model->get_rowByTypeAndLayout(ELEMENT_TYPE_PAGE,MENU_HIDDEN_POPUP);
		if(!$listElementNotSeguridad)
		return null;
			
		//Obtener la lista del elementos de tipo pagina a la cual el usuario tiene permiso , segun el rol del usuario
		$listElementPermitido = $User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
		
		//Obtener los id de los Elementos
		$listElementIDNotSeguridad;
		$listElementIDPermitied;		
		foreach($listElementNotSeguridad AS $i){ 
			$listElementIDNotSeguridad[] = $i->elementID; 
		}
		
		if($listElementPermitido){
			$tmp;
			foreach($listElementPermitido AS $i){$tmp[] = $i->elementID;}
			$listElementIDPermitied = array_intersect($listElementIDNotSeguridad, $tmp);
		}
		
		
		//Obtener la lista de menu_element del componente de  seguridad...
		$listMenuElement = NULL;
		if($objRole->isAdmin && $listElementIDNotSeguridad)		
		$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDNotSeguridad);
		else if ($listElementIDPermitied)
		$listMenuElement = $Menu_Element_Model->get_rowByCompanyIDyElementID($companyID,$listElementIDPermitied);
		
		if(!$listMenuElement)
		return null; 	
	
	
		//Resultado  
		return $listMenuElement;
		
	}
	function render_menu_top($data)
    {
		$html		= "";
		$html		= self::render_item_top($data,null);
		return $html;
    }
    function render_item_top($data,$parent){
		$html	= "";	
		$x		= "";	
		foreach($data AS $obj){
			if($obj->parentMenuElementID == $parent){				
				$x 					= self::render_item_top($data,$obj->menuElementID);		
				$data_["icon"]		= $obj->icon;
				$data_["address"]	= base_url()."/". str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$obj->address);
				$data_["display"]	= $obj->display;
				$data_["target"]	= $obj->typeUrlRedirect;
				$data_["submenu"]	= $x;								
				$template			= view("core_template/".$obj->template,$data_);		
				$html				= $html . $template;
			}		
		}		 
		return $html;  
    }
    function render_menu_left($company,$data)
    {
		$html		= "";		
		$html		= self::render_item_left($company,$data,null);
		return $html;
    }
    function render_item_left($company,$data,$parent)
	{
		$html	= "";	
		$x		= "";	
		foreach($data AS $obj){
			if($obj->parentMenuElementID == $parent){				
				$x 					= self::render_item_left($company,$data,$obj->menuElementID);		
				$data_["icon"]		= $obj->icon;				
				$data_["address"]	= base_url()."/". str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$obj->address);				
				$data_["display"]	= getBehavio(strtoupper($company->type),"core_web_menu",$obj->display,"");
				$data_["submenu"]	= $x;								
				$template			= view("core_template/".$obj->template,$data_);								
				$html				= $html . $template;
			}		
		}		 
		return $html;  
    }
	function render_menu_body_report($data,$elementID){
		$html		= "";		
		$html		= self::render_item_body_report($data,$elementID);
		return $html;
	}
	function render_item_body_report($data,$parent){
		$html	= "";	
		$x		= "";	
		if(!$data)
		return;
		
		foreach($data AS $obj){
			if($obj->parentMenuElementID == $parent){				
				$x 					= self::render_item_body_report($data,$obj->menuElementID);		
				$data_["icon"]		= $obj->icon;
				$data_["address"]	= base_url()."/". str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$obj->address);
				$data_["display"]	= $obj->display;
				$data_["submenu"]	= $x;								
				$template			= view("core_template/".$obj->template,$data_);								
				$html				= $html . $template;
			}		
		}		 
		return $html;  
    }
}
?>