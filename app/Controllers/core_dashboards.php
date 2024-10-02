<?php
//posme:2023-02-27
namespace App\Controllers;
class core_dashboards extends _BaseController {
	
    
	//INDEX
	////////////////////////////
	function index(){ 
		try{ 
		
			 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
				
			}		
								
			//Validar Parametro de maximo de usuario.
			$objCompany									= $dataSession["company"];
			$companyID 									= $dataSession["company"]->companyID;
			$objParameterMAX_USER 						= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_MAX_USER",$companyID);
			$objParameterMAX_USER 						= $objParameterMAX_USER->value;
			$dataSession["objParameterMAX_USER"] 		= $objParameterMAX_USER;
			$parameterFechaExpiration 					= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
			$parameterFechaExpiration 					= $parameterFechaExpiration->value;
			$parameterFechaExpiration 					= \DateTime::createFromFormat('Y-m-d',$parameterFechaExpiration)->format("Y-m-d");			
			$dataSession["parameterFechaExpiration"] 	= $parameterFechaExpiration;
			$objParameterISleep							= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_SLEEP",$companyID);
			$objParameterISleep							= $objParameterISleep->value;
			$dataSession["objParameterISleep"] 			= $objParameterISleep;
			$objParameterTipoPlan						= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",$companyID);
			$objParameterTipoPlan						= $objParameterTipoPlan->value;
			$dataSession["objParameterTipoPlan"] 		= $objParameterTipoPlan;
			$objParameterExpiredLicense					= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$companyID);
			$objParameterExpiredLicense					= $objParameterExpiredLicense->value;
			$objParameterExpiredLicense 				= \DateTime::createFromFormat('Y-m-d',$objParameterExpiredLicense)->format("Y-m-d");	
			$dataSession["objParameterExpiredLicense"] 	= $objParameterExpiredLicense;
			$objParameterCreditos						= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE",$companyID);
			$objParameterCreditosID						= $objParameterCreditos->parameterID;
			$objParameterCreditos						= $objParameterCreditos->value;
			$dataSession["objParameterCreditos"] 		= $objParameterCreditos;
			$objParameterVersion						= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_VERSION",$companyID);			
			$objParameterVersion						= $objParameterVersion->value;
			$dataSession["objParameterVersion"] 		= $objParameterVersion;
			$objParameterPrice							= $this->core_web_parameter->getParameter("CORE_CUST_PRICE",$companyID);			
			$objParameterPrice							= $objParameterPrice->value;
			$dataSession["objParameterPrice"] 			= $objParameterPrice;
			$objParameterPriceByInvoice					= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BY_INVOICE",$companyID);
			$objParameterPriceByInvoice					= $objParameterPriceByInvoice->value;
			$dataSession["objParameterPriceByInvoice"] 	= $objParameterPriceByInvoice;
			
			
			$objCurrency									= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency									= $this->core_web_currency->getCurrencyExternal($companyID);
			$dataSession["objExchangeRateDolarACordoba"] 	= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataSession["objExchangeRateCordobaDolar"] 	= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$objCurrency->currencyID,$targetCurrency->currencyID);			
			
			
			
			$diaDelAnnio 								= date("z");
			$diaDelAnnio 								= rand(1, 360);
			$objVersiculo 								= $this->Biblia_Model->get_rowByDay($companyID, $diaDelAnnio);
			if ($objVersiculo == null) {
				$objVersiculo = $this->Biblia_Model->get_rowByDay($companyID, 1);
			}
			
			//Renderizar Resultado
			$dataSession["objVersiculo"] 	= $objVersiculo;
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= "";
			$dataSession["head"]			= "";
			
			
			if($objCompany->type == "fn_blandon")
			{
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_fun_blandon',$dataSession);//--finview
			}
			if($objCompany->type == "corea")
			{
				$dataSession					= $this->getIndexCorea($dataSession);
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_corea',$dataSession);//--finview
			}
			else
			{				
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default',$dataSession);//--finview
			}
			
			$dataSession["script"]			= ""; 
			$dataSession["footer"]			= ""; 	
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			//show_error($ex->getMessage() ,500 );
		}
	}
	
	function getIndexCorea($dataSession)
	{
		$firstDateYear					= helper_PrimerDiaDelMes();
		$lastDateYear					= helper_UltimoDiaDelMes();
		$firstDate						= helper_PrimerDiaDelMes();
		$lastDate						= helper_UltimoDiaDelMes();			
		$objListVentas					= $this->Transaction_Master_Detail_Model->GlobalPro_get_rowBySalesByEmployeerMonthOnly_Sales($dataSession["user"]->companyID,$firstDate,$lastDate);
		$objListTecnico					= $this->Transaction_Master_Detail_Model->GlobalPro_get_rowBySalesByEmployeerMonthOnly_Tenico($dataSession["user"]->companyID,$firstDate,$lastDate);
		
		//Obtener las Ventas Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListVentaMensual = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			
			$objListVentaMensualTemporal = $this->Transaction_Master_Detail_Model->GlobalPro_get_MonthOnly_Sales($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListVentaMensualTemporal)
			{
				array_push($objListVentaMensual, $objListVentaMensualTemporal[0]);

			}
			$objFirstYearDate->modify('+1 month');
		}
		
		
		
		//Obtener las Ventas Diarias
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);						
		$objLastDate 		= \DateTime::createFromFormat('Y-m-d H:i:s', $lastDate);
		$objLastDate->setTime(0, 0, 0);
		$objNowDate 		= \DateTime::createFromFormat('Y-m-d H:i:s', helper_getDate());
		$objNowDate->setTime(0, 0, 0);
		$objListVentaDiaria = array();
		while($objFirstDate <= $objNowDate)
		{				
			$objListVentaDiariaTemporal = $this->Transaction_Master_Detail_Model->GlobalPro_get_Day_Sales($dataSession["user"]->companyID, $objFirstDate->format("Y-m-d"),$objFirstDate->format("Y-m-d") );
			if($objListVentaDiariaTemporal)
			{
				array_push($objListVentaDiaria, $objListVentaDiariaTemporal[0]);

			}
			$objFirstDate->modify('+1 day');
		}
		
		//Renderizar Resultado			
		$dataSession["objListVentas"]		= $objListVentas;
		$dataSession["objListTecnico"]		= $objListTecnico;
		$dataSession["objListVentaMensual"]	= $objListVentaMensual;
		$dataSession["objListVentaDiaria"]	= $objListVentaDiaria;
		return $dataSession;
	}
}
?>