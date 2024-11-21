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


class core_web_permission {
   
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
   
   function getElementID($controler,$method,$suffix,$dataMenuTop,$dataMenuLeft,$dataMenuBodyReport,$dataMenuBodyTop,$dataMenuHiddenPopup){
		//$url  = $controler."/".$method.$suffix;
		$url  		= str_replace("app\\controllers\\","",strtolower($controler))."/".$method.$suffix;
		$urlIndex  	= str_replace("app\\controllers\\","",strtolower($controler))."/"."index".$suffix;
		
		//dataMenuTop
		//dataMenuLeft
		//dataMenuBodyReport
		//dataMenuBodyTop
		//dataMenuHiddenPopup
		
		if(is_array($dataMenuHiddenPopup))
		foreach($dataMenuHiddenPopup AS $url_){	
			$urlCompare = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($urlCompare) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuBodyTop))
		foreach($dataMenuBodyTop AS $url_){	
			$urlCompare = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($urlCompare) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuBodyReport))
		foreach($dataMenuBodyReport AS $url_){	
			$urlCompare = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($urlCompare) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuTop))
		foreach($dataMenuTop AS $url_){	
			$urlCompare = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($urlCompare) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		if(is_array($dataMenuLeft))
		foreach($dataMenuLeft AS $url_){
			$urlCompare = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);			
			if(strtoupper ($urlCompare) == strtoupper ($url)){
				return $url_->menuElementID;
			}
		}
		
		return false;
   }
   function urlPermited($controler,$method,$suffix,$dataMenuTop,$dataMenuLeft,$dataMenuBodyReport,$dataMenuBodyTop,$dataMenuHiddenPopup){
		//$url  = $controler."/".$method.$suffix;		
		$url  		= str_replace("app\\controllers\\","",strtolower($controler))."/".$method.$suffix;
		$urlIndex  	= str_replace("app\\controllers\\","",strtolower($controler))."/"."index".$suffix;
				
		
		//dataMenuTop
		//dataMenuLeft
		//dataMenuBodyReport
		//dataMenuBodyTop
		//dataMenuHiddenPopup	
		
		
		if(is_array($dataMenuHiddenPopup))
		foreach($dataMenuHiddenPopup AS $url_){	
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);			
			if(strtoupper ($url_2) == strtoupper ($url)){
				return true;
			}
			else if(strtoupper ($url_2) == strtoupper ($urlIndex)){
				return true;
			}
		}
		
		if(is_array($dataMenuBodyTop))
		foreach($dataMenuBodyTop AS $url_){	
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			
			
			if(strtoupper ($url_2) == strtoupper ($url)){
				return true;
			}
			else if(strtoupper ($url_2) == strtoupper ($urlIndex)){
				return true;
			}
		}
		
		if(is_array($dataMenuBodyReport))
		foreach($dataMenuBodyReport AS $url_){	
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($url_2) == strtoupper ($url)){
				return true;
			}
			else if(strtoupper ($url_2) == strtoupper ($urlIndex)){
				return true;
			}
		}
		
		if(is_array($dataMenuLeft))
		foreach($dataMenuLeft AS $url_){				
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);			
			if(strtoupper ($url_2) == strtoupper ($url)){
				return true;
			}
			else if(strtoupper ($url_2) == strtoupper ($urlIndex)){
				return true;
			}
		}
		
	
		if(is_array($dataMenuTop))
		foreach($dataMenuTop AS $url_){	
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($url_2) == strtoupper ($url)){
				return true;
			}
			else if(strtoupper ($url_2) == strtoupper ($urlIndex)){
				return true;
			}
		}
		return false;
   }
   function urlPermissionCmd($controler,$method,$suffix,$session_,$dataMenuTop,$dataMenuLeft,$dataMenuBodyReport,$dataMenuBodyTop,$dataMenuHiddenPopup){
		$User_Permission_Model = new User_Permission_Model();
		//$url  	= $controler."/".$method.$suffix;
		//$url2  	= $controler."/"."index".$suffix;		
		$url  	= str_replace("app\\controllers\\","",strtolower($controler))."/".$method.$suffix;
		$url2  	= str_replace("app\\controllers\\","",strtolower($controler))."/"."index".$suffix;
		
		
		//dataMenuTop
		//dataMenuLeft
		//dataMenuBodyReport
		//dataMenuBodyTop
		//dataMenuHiddenPopup	
		
		
		//Craer Variables
		$elementID	= 0; 	
		if($session_["role"]->isAdmin)
		return PERMISSION_ALL;			
		
		//Obtener el elementoID
		if(is_array($dataMenuTop))
		foreach($dataMenuTop AS $url_){		
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($url_2) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}
			else if(strtoupper ($url_2) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuLeft))
		foreach($dataMenuLeft AS $url_){	
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($url_2) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}			
			else if(strtoupper ($url_2) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuBodyReport))
		foreach($dataMenuBodyReport AS $url_){	
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($url_2) == strtoupper ($url)){				
				$elementID = $url_->elementID;
				break;
			}			
			else if(strtoupper ($url_2) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuBodyTop))
		foreach($dataMenuBodyTop AS $url_){	
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($url_2) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}
			else if(strtoupper ($url_2) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		if(is_array($dataMenuHiddenPopup))
		foreach($dataMenuHiddenPopup AS $url_){		
			$url_2 = str_replace(URL_SUFFIX_OLD,URL_SUFFIX_NEW,$url_->address);
			if(strtoupper ($url_2) == strtoupper ($url)){
				$elementID = $url_->elementID;
				break;
			}
			else if(strtoupper ($url_2) == strtoupper ($url2)){
				$elementID = $url_->elementID;
				break;
			}
		}
		
		
		if($elementID == 0)
		return PERMISSION_NONE;
		
		
		//Obtener resultado...		
		$rowRolePermission				= $User_Permission_Model->get_rowByPK($session_["user"]->companyID,$session_["user"]->branchID,$session_["role"]->roleID,$elementID);
		if(!$rowRolePermission)
		return PERMISSION_NONE;
		
		
		if($method 				== "index"){
			return $rowRolePermission->selected;
		}	
		else if($method 		== "edit"){
			return $rowRolePermission->edited;
		}
		else if($method 		== "delete"){
			return $rowRolePermission->deleted;
		}
		else if($method 		== "add"){
			return $rowRolePermission->inserted;
		}
		
   }
   function getValueLicense($companyID,$url)
   {
		
		
		$core_web_parameter = new core_web_parameter();
		$User_Permission_Model = new User_Permission_Model();
		$User_Model = new User_Model();
		$Company_Parameter_Model = new Company_Parameter_Model();
		//Validar Parametro de maximo de usuario.
		$objParameterMAX_USER 		= $core_web_parameter->getParameter("CORE_CUST_PRICE_MAX_USER",$companyID);
		
		
		
		$parameterFechaExpiration 	= $core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
		$parameterFechaExpiration 	= $parameterFechaExpiration->value;
		$parameterFechaExpiration 	= \DateTime::createFromFormat('Y-m-d',$parameterFechaExpiration);			
		$objParameterISleep			= $core_web_parameter->getParameter("CORE_CUST_PRICE_SLEEP",$companyID);
		$objParameterISleep			= $objParameterISleep->value;
		$objParameterTipoPlan		= $core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",$companyID);
		$objParameterTipoPlan		= $objParameterTipoPlan->value;
		$objParameterExpiredLicense	= $core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
		$objParameterExpiredLicense	= $objParameterExpiredLicense->value;
		$objParameterExpiredLicense = \DateTime::createFromFormat('Y-m-d',$objParameterExpiredLicense);		
		$objParameterCreditos		= $core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE",$companyID);
		$objParameterCreditosID		= $objParameterCreditos->parameterID;
		$objParameterCreditos		= $objParameterCreditos->value;
		
		$objParameterPriceByInvoice		= $core_web_parameter->getParameter("CORE_CUST_PRICE_BY_INVOICE",$companyID);
		$objParameterPriceByInvoice		= $objParameterPriceByInvoice->value;

		
		
		//Validar cantidad maxima de usuario
		//-wg-if($objParameterMAX_USER->value > 0 ){			
		//-wg-	$count = $User_Model->getCount($companyID);		
		//-wg-	if(($count + 1) > $objParameterMAX_USER->value ){
		//-wg-		
		//-wg-		throw new \Exception('
		//-wg-		<p>A superado el numero maximo de usuario.</p>
		//-wg-		<p>telefono de contacto: 8712-5827 para activar licencia</p>
		//-wg-		<p>realizar el pago de la licencia  aqui &ograve; </p>
		//-wg-		<p>realizar la transferencia a la siguiente cuenta BAC Dolares: 366-577-484 </p>
		//-wg-		
		//-wg-		');
		//-wg-	}
		//-wg-}

		//Validar Fecha de expiracion de la licencia
		$fechaNow  = \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  						
		if( $fechaNow >  $parameterFechaExpiration ){
			
			throw new \Exception('
			<p>La licencia a expirado.</p>
			<p>telefono de contacto: 8712-5827 para activar licencia</p>
			<p>realizar el pago de la licencia  aqui &ograve; </p>
			<p>realizar la transferencia a la siguiente cuenta BAC Dolares: 366-577-484 </p>
			');
		}
		
		//Validar Saldo				
		if($objParameterTipoPlan  == "CONSUMIBLE")
		{
			if(($objParameterCreditos - $objParameterPriceByInvoice ) < 0){
				
				throw new \Exception('
				<p>No tiene suficiente creditos.</p>
				<p>telefono de contacto: 8712-5827 para activar licencia</p>
				<p>realizar el pago de la licencia  aqui &ograve; </p>
				<p>realizar la transferencia a la siguiente cuenta BAC Dolares: 366-577-484 </p>
				
				');
			}
			
		}
		
		$objParameterCreditos 		= $objParameterCreditos - $objParameterPriceByInvoice ;
		$dataNewParameter 			= array();
		$dataNewParameter["value"] 	= $objParameterCreditos;
		$Company_Parameter_Model->update_app_posme($companyID,$objParameterCreditosID,$dataNewParameter);
		
		/*Aumentar la cantidad de transacciones*/
		$parameterCantiadTransacciones 				= $core_web_parameter->getParameter("CORE_QUANTITY_TRANSACCION",$companyID);
		$parameterCantiadTransaccionesId 			= $parameterCantiadTransacciones->parameterID;
		$parameterCantiadTransacciones 				= $parameterCantiadTransacciones->value;
		$parameterCantiadTransaccionesNewValor 		= $parameterCantiadTransacciones + 1;
		$dataNewParameterCantidadTransacciones 				= array();
		$dataNewParameterCantidadTransacciones["value"] 	= $parameterCantiadTransaccionesNewValor;
		$Company_Parameter_Model->update_app_posme($companyID,$parameterCantiadTransaccionesId,$dataNewParameterCantidadTransacciones);
		
		

		//Dormir el sistema cuando el tipo de Licencia es PERMANENTE y la fecha actual es mayor a la fecha limite de licencia
		//En tal caso, dormir el sistema	
		$fechaNow  = \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  	
		if( $fechaNow >  $objParameterExpiredLicense && $objParameterTipoPlan != "MEMBRESIA" ){		
			$diff = $objParameterExpiredLicense->diff($fechaNow);
			$days = abs($diff->days);
			$days = $days + $objParameterISleep ;						
			
			if($days > 60)
			$days = 60;
			if($days > 0){
				
				sleep($days);
			}
		}		
	
	}
   function getLicenseMessage($companyID)
   {
		
		
		$core_web_parameter 		= new core_web_parameter();		
		$parameterFechaExpiration 	= $core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
		$parameterFechaExpiration 	= $parameterFechaExpiration->value;
		$parameterFechaExpiration 	= \DateTime::createFromFormat('Y-m-d',$parameterFechaExpiration);	
		
		
		$objParameterExpiredLicense	= $core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
		$objParameterExpiredLicense	= $objParameterExpiredLicense->value;
		$objParameterExpiredLicense = \DateTime::createFromFormat('Y-m-d',$objParameterExpiredLicense);	
		
		$objParameterTipoPlan		= $core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",$companyID);
		$objParameterTipoPlan		= $objParameterTipoPlan->value;
		
		$objParameterCreditos		= $core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE",$companyID);
		$objParameterCreditosID		= $objParameterCreditos->parameterID;
		$objParameterCreditos		= $objParameterCreditos->value;
		
		$objParameterPriceByInvoice		= $core_web_parameter->getParameter("CORE_CUST_PRICE_BY_INVOICE",$companyID);
		$objParameterPriceByInvoice		= $objParameterPriceByInvoice->value;
		
		
		//Validar Fecha de expiracion de la licencia
		$fechaNow  		= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  
		$fechaNow		= date_add($fechaNow, date_interval_create_from_date_string('7 day'));
		if( $fechaNow >  $parameterFechaExpiration )
		{
			
			return "<span class='badge badge-important'>LICENCIA EXPIRA EN 7 DIAS</span>";
		}
		
		//Validar Saldo				
		if($objParameterTipoPlan  == "CONSUMIBLE")
		{
			if( $objParameterCreditos  < ($objParameterPriceByInvoice *  30) )
			{				
				return "<span class='badge badge-important'>CREDITOS PRONTO VENCERAN</span>";
			}
			
		}
		
		return "";
			
	
    }
  
}
?>