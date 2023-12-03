<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_parameters extends _BaseController {
	
   
	
	function save(){
		
		try{  
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);						
			}	
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			$companyID 						= $dataSession["user"]->companyID;
			$accountNumberUtility 			= /*inicio get post*/ $this->request->getPost("accountUtility");//ACCOUNTING_NUMBER_UTILITY_PERIOD	
			$accountNumberUtilityAcumulate 	= /*inicio get post*/ $this->request->getPost("accountUtilityAcumulate");//ACCOUNTING_NUMBER_UTILITY_ACUMULATE		
			$currencyDefaultName 			= /*inicio get post*/ $this->request->getPost("currencyDefault");//ACCOUNTING_CURRENCY_NAME_FUNCTION		
			$formulateUtility 				= /*inicio get post*/ $this->request->getPost("formulateUtility");//ACCOUNTING_FORMULATE_OF_UTILITY
			$currencyReportName				= /*inicio get post*/ $this->request->getPost("currencyReport");//ACCOUNTING_CURRENCY_NAME_REPORT
			$accountNumberCostos			= /*inicio get post*/ $this->request->getPost("accountCostos");//ACCOUNTING_NUMBER_COSTOS
			$accountNumberGastos			= /*inicio get post*/ $this->request->getPost("accountGastos");//ACCOUNTING_NUMBER_GASTOS
			$accountNumberIngreso			= /*inicio get post*/ $this->request->getPost("accountIngreso");//ACCOUNTING_NUMBER_INGRESO
			$accountNumberActivo			= /*inicio get post*/ $this->request->getPost("accountActivo");//ACCOUNTING_NUMBER_ACTIVO
			$accountNumberPasivo			= /*inicio get post*/ $this->request->getPost("accountPasivo");//ACCOUNTING_NUMBER_PASIVO
			$accountNumberCapital			= /*inicio get post*/ $this->request->getPost("accountCapital");//ACCOUNTING_NUMBER_CAPITAL
			$accountResult					= /*inicio get post*/ $this->request->getPost("accountResult");//ACCOUNTING_ACCOUNTTYPE_RESULT
			$exchangePurchase				= /*inicio get post*/ $this->request->getPost("exchangePurchase");//ACCOUNTING_EXCHANGE_PURCHASE
			$exchangeSales					= /*inicio get post*/ $this->request->getPost("exchangeSales");//ACCOUNTING_EXCHANGE_SALE			
			$razon001						= /*inicio get post*/ $this->request->getPost("razon001");//ACCOUNTING_RF_RAZON_CIRCULANTE
			$razon002						= /*inicio get post*/ $this->request->getPost("razon002");//ACCOUNTING_RF_ENDEUDAMIENTO
			$razon003						= /*inicio get post*/ $this->request->getPost("razon003");//ACCOUNTING_RF_UTILIDAD_MENSUAL
			$razon004						= /*inicio get post*/ $this->request->getPost("razon004");//ACCOUNTING_RF_UTILIDAD_ANUAL
			$razon005						= /*inicio get post*/ $this->request->getPost("razon005");//ACCOUNTING_RF_RENTABILIDAD_ANUAL
			$razon006						= /*inicio get post*/ $this->request->getPost("razon006");//ACCOUNTING_RF_RENTABILIDAD_MENSUAL
			
			$objAccountResult 					= $this->core_web_parameter->getParameter("ACCOUNTING_ACCOUNTTYPE_RESULT",$companyID );
			$objAccountNumberCostos 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_COSTOS",$companyID );
			$objAccountNumberGastos 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_GASTOS",$companyID );
			$objAccountNumberIngreso 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_INGRESO",$companyID );
			$objAccountNumberActivo 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_ACTIVO",$companyID );
			$objAccountNumberPasivo 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_PASIVO",$companyID );
			$objAccountNumberCapital 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_CAPITAL",$companyID );			
			$objAccountNumberUtility 			= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_PERIOD",$companyID );
			$objAccountNumberUtilityAcumulate 	= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_ACUMULATE",$companyID );
			$objCurrencyDefaultName 			= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$companyID );
			$objFormulateUtility 				= $this->core_web_parameter->getParameter("ACCOUNTING_FORMULATE_OF_UTILITY",$companyID );
			$objCurrencyReportName 				= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_REPORT",$companyID );
			$objExchangePurchase 				= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID );
			$objExchangeSales 					= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID );			
			$objRazon001 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RAZON_CIRCULANTE",$companyID );
			$objRazon002 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_ENDEUDAMIENTO",$companyID );
			$objRazon003 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_MENSUAL",$companyID );
			$objRazon004 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_ANUAL",$companyID );
			$objRazon005 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_ANUAL",$companyID );
			$objRazon006 						= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_MENSUAL",$companyID );
			
			$data["value"] = $exchangeSales;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objExchangeSales->parameterID,$data);
			$data["value"] = $exchangePurchase;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objExchangePurchase->parameterID,$data);
			$data["value"] = $accountResult;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountResult->parameterID,$data);
			$data["value"] = $accountNumberCostos;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberCostos->parameterID,$data);
			$data["value"] = $accountNumberGastos;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberGastos->parameterID,$data);
			$data["value"] = $accountNumberIngreso;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberIngreso->parameterID,$data);
			$data["value"] = $accountNumberActivo;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberActivo->parameterID,$data);
			$data["value"] = $accountNumberPasivo;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberPasivo->parameterID,$data);
			$data["value"] = $accountNumberCapital;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberCapital->parameterID,$data);			
			$data["value"] = $accountNumberUtility;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberUtility->parameterID,$data);
			$data["value"] = $accountNumberUtilityAcumulate;
			$this->Company_Parameter_Model->update_app_posme($companyID,$objAccountNumberUtilityAcumulate->parameterID,$data);
			$data["value"] = $currencyDefaultName;
			$this->Company_Parameter_Model->update_app_posme($companyID,$objCurrencyDefaultName->parameterID,$data);
			$data["value"] = $formulateUtility;
			$this->Company_Parameter_Model->update_app_posme($companyID,$objFormulateUtility->parameterID,$data);
			$data["value"] = $currencyReportName;
			$this->Company_Parameter_Model->update_app_posme($companyID,$objCurrencyReportName->parameterID,$data);
			$data["value"] = $razon001;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objRazon001->parameterID,$data);
			$data["value"] = $razon002;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objRazon002->parameterID,$data);
			$data["value"] = $razon003;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objRazon003->parameterID,$data);
			$data["value"] = $razon004;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objRazon004->parameterID,$data);
			$data["value"] = $razon005;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objRazon005->parameterID,$data);
			$data["value"] = $razon006;			
			$this->Company_Parameter_Model->update_app_posme($companyID,$objRazon006->parameterID,$data);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}		
			
	}	
	function index($dataViewID = null){	
	try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);			
		
			}	
			
			//Obtener Parametros Contables
			$objAccountCostos 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_COSTOS",$dataSession["user"]->companyID);
			$objAccountGastos 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_GASTOS",$dataSession["user"]->companyID);
			$objAccountIngreso 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_INGRESO",$dataSession["user"]->companyID);
			$objAccountActivo 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_ACTIVO",$dataSession["user"]->companyID);
			$objAccountPasivo 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_PASIVO",$dataSession["user"]->companyID);
			$objAccountCapital 				= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_CAPITAL",$dataSession["user"]->companyID);			
			$objAccountUtilityPeriod 		= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_PERIOD",$dataSession["user"]->companyID);
			$objAccountUtilityAcumulate 	= $this->core_web_parameter->getParameter("ACCOUNTING_NUMBER_UTILITY_ACUMULATE",$dataSession["user"]->companyID);
			$objCurrencyDefault 			= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$dataSession["user"]->companyID);
			$objFormulateUtility 			= $this->core_web_parameter->getParameter("ACCOUNTING_FORMULATE_OF_UTILITY",$dataSession["user"]->companyID);
			$objCurrencyReport 				= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_REPORT",$dataSession["user"]->companyID);
			$objAccountResult 				= $this->core_web_parameter->getParameter("ACCOUNTING_ACCOUNTTYPE_RESULT",$dataSession["user"]->companyID);
			$objExchangePurchase 			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$dataSession["user"]->companyID);
			$objExchangeSales 				= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$dataSession["user"]->companyID);
			
			$objRazon001 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RAZON_CIRCULANTE",$dataSession["user"]->companyID);
			$objRazon002 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_ENDEUDAMIENTO",$dataSession["user"]->companyID);
			$objRazon003 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_MENSUAL",$dataSession["user"]->companyID);
			$objRazon004 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_UTILIDAD_ANUAL",$dataSession["user"]->companyID);
			$objRazon005 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_ANUAL",$dataSession["user"]->companyID);
			$objRazon006 					= $this->core_web_parameter->getParameter("ACCOUNTING_RF_RENTABILIDAD_MENSUAL",$dataSession["user"]->companyID);
			
			$dataV["accountCostos"] 			= $objAccountCostos->value;
			$dataV["accountGastos"] 			= $objAccountGastos->value;
			$dataV["accountIngreso"] 			= $objAccountIngreso->value;
			$dataV["accountActivo"] 			= $objAccountActivo->value;
			$dataV["accountPasivo"] 			= $objAccountPasivo->value;
			$dataV["accountCapital"] 			= $objAccountCapital->value;			
			$dataV["accountUtilityPeriod"] 		= $objAccountUtilityPeriod->value;
			$dataV["accountUtilityAcumulate"] 	= $objAccountUtilityAcumulate->value;
			$dataV["currencyDefault"]			= $objCurrencyDefault->value;
			$dataV["formulateUtility"] 			= $objFormulateUtility->value;
			$dataV["currencyReport"] 			= $objCurrencyReport->value;
			$dataV["accountResult"] 			= $objAccountResult->value;
			$dataV["exchangePurchase"] 			= $objExchangePurchase->value;
			$dataV["exchangeSales"] 			= $objExchangeSales->value;
			$dataV["objRazon001"] 			= $objRazon001->value;
			$dataV["objRazon002"] 			= $objRazon002->value;
			$dataV["objRazon003"] 			= $objRazon003->value;
			$dataV["objRazon004"] 			= $objRazon004->value;
			$dataV["objRazon005"] 			= $objRazon005->value;
			$dataV["objRazon006"] 			= $objRazon006->value;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_parameters/view_head',$dataV);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_parameters/view_body',$dataV);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_parameters/view_script',$dataV);//--finview
			$dataSession["footer"]			= "";			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>