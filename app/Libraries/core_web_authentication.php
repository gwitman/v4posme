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


class core_web_authentication {
   
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
   
   function get_UserBy_PasswordAndNickname($nickname,$password){
		$User_Model = new User_Model();
		$Role_Model = new Role_Model();
		$Membership_Model = new Membership_Model();
		$Company_Model = new Company_Model();
		$Branch_Model = new Branch_Model();
		$core_web_menu = new core_web_menu();
		$core_web_permission = new core_web_permission();
		$core_web_parameter = new core_web_parameter();
		
		
		
		
		
		$objUser	= $User_Model->get_rowByNiknamePassword($nickname,$password);
		if(!$objUser)
		throw new \Exception('PASSWORD O NICKNAME INCORRECTO ...');
		
		$objCompany		= $Company_Model->get_rowByPK($objUser->companyID);
		$objBranch		= $Branch_Model->get_rowByPK($objUser->companyID,$objUser->branchID);
		$objMembership	= $Membership_Model->get_rowByCompanyIDBranchIDUserID($objUser->companyID,$objUser->branchID,$objUser->userID);
		
		if(!$objMembership)
		throw new \Exception('EL USUARIO NO TIENE ASIGNADO UN ROL...');
		
		$objRole					= $Role_Model->get_rowByPK($objUser->companyID,$objUser->branchID,$objMembership->roleID);
		
		
		$objElementAuthorized		= $core_web_menu->get_menu_top($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);		
		$objElementNotAuthorized	= $core_web_menu->get_menu_left($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$menuBodyReport				= $core_web_menu->get_menu_body_report($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$menuHiddenPopup 			= $core_web_menu->get_menu_hidden_popup($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$mensajeLogin 				= $core_web_permission->getLicenseMessage(2);
		
		if(!$objCompany)
		throw new \Exception('LA EMPREA NO FUE ENCONTRADA ...');
		
		if(!$objBranch)
		throw new \Exception('LA SUCURSAL NO FUE ENCONTRADA ...');
		
		if(!$objRole)
		throw new \Exception('EL ROL DEL USUARIO NO FUE ENCONTRADO...');
		
		
		$parameterLabelSistem = $core_web_parameter->getParameter("CORE_LABEL_SISTEMA_SUPLANTATION",$objMembership->companyID);
		$parameterLabelSistem = $parameterLabelSistem->value;	
		
		
		$data["company"]				= $objCompany;
		$data["parameterLabelSistem"]	= $parameterLabelSistem;
		$data["mensajeLogin"]			= $mensajeLogin;
		$data["branch"]					= $objBranch;
		$data["role"]					= $objRole;
		$data["user"]					= $objUser;
		$data["menuTop"]				= $objElementAuthorized;
		$data["menuLeft"]				= $objElementNotAuthorized;
		$data["menuBodyTop"]			= null;
		$data["menuBodyReport"]			= $menuBodyReport;
		$data["menuHiddenPopup"]		= $menuHiddenPopup;
		
		
		return $data;
   }
   function get_UserBy_Email($email){
		$User_Model = new User_Model();
		$Role_Model = new Role_Model();
		$Membership_Model = new Membership_Model();
		$Company_Model = new Company_Model();
		$Branch_Model = new Branch_Model();
		$core_web_menu = new core_web_menu();
		
		$objUser	= $User_Model->get_rowByEmail($email);
		if(!$objUser)
		throw new \Exception('EMAIL INCORRECTO ...');
		
		$objCompany		= $Company_Model->get_rowByPK($objUser->companyID);
		$objBranch		= $Branch_Model->get_rowByPK($objUser->companyID,$objUser->branchID);
		$objMembership	= $Membership_Model->get_rowByCompanyIDBranchIDUserID($objUser->companyID,$objUser->branchID,$objUser->userID);
		
		if(!$objMembership)
		throw new \Exception('EL USUARIO NO TIENE ASIGNADO UN ROL...');
		
		$objRole					= $Role_Model->get_rowByPK($objUser->companyID,$objUser->branchID,$objMembership->roleID);
		$objElementAuthorized		= $core_web_menu->get_menu_top($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$objElementNotAuthorized	= $core_web_menu->get_menu_left($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$objElementBodyReport 		= $core_web_menu->get_menu_body_report($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		$menuHiddenPopup 			= $core_web_menu->get_menu_hidden_popup($objMembership->companyID,$objMembership->branchID,$objMembership->roleID,$objMembership->userID);
		
		if(!$objCompany)
		throw new \Exception('LA EMPREA NO FUE ENCONTRADA ...');
		
		if(!$objBranch)
		throw new \Exception('LA SUCURSAL NO FUE ENCONTRADA ...');
		
		if(!$objRole)
		throw new \Exception('EL ROL DEL USUARIO NO FUE ENCONTRADO...');
		
		$data["company"]			= $objCompany;
		$data["branch"]				= $objBranch;
		$data["role"]				= $objRole;
		$data["user"]				= $objUser;
		$data["menuTop"]			= $objElementAuthorized;
		$data["menuLeft"]			= $objElementNotAuthorized;
		$data["menuBodyTop"]		= null;
		$data["menuBodyReport"] 	= $objElementBodyReport;
		$data["menuHiddenPopup"]	= $menuHiddenPopup;
		return $data;
		
   }
   function createLogin($data){		
		$session 		= \Config\Services::session();
		$core_web_menu 	= new core_web_menu();	
		
		
		$session->set($data);		
		$data			 				= $session->get();
		
		
		$userdata["menuRenderTop"] 		= $core_web_menu->render_menu_top($data["menuTop"]);			
		$userdata["menuRenderLeft"] 	= $core_web_menu->render_menu_left($data["company"],$data["menuLeft"]);
		$session->set($userdata);
		
		
				
   } 
   function destroyLogin(){
		$session 		= \Config\Services::session();
		$session->destroy(); 
   }
   function isAuthenticated(){
		
		$session 		= \Config\Services::session();
		if(!APP_NEED_AUTHENTICATION)
		return true;
		
		
		if($session->get('user'))
		return true;
		
		return false;			
   }
}
?>