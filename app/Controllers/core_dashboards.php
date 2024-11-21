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
			else if($objCompany->type == "corea")
			{
				$dataSession					= $this->getIndexCorea($dataSession);
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_corea',$dataSession);//--finview
			}
			else if($objCompany->type == "compu_matt")
			{
				$dataSession					= $this->getIndexCompuMatt($dataSession);
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_compumatt',$dataSession);//--finview
			}
			else if($objCompany->type == "ebenezer")
			{
				$dataSession					= $this->getIndexEbenezer($dataSession);
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_ebenezer',$dataSession);//--finview
			}
			else if($objCompany->type == "khadash")
			{
				$dataSession					= $this->getIndexCorea($dataSession);
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_khadash',$dataSession);//--finview
			}
			else
			{
				$dataSession 					= $this->getIndexDefault($dataSession);			
				$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default',$dataSession);//--finview
			}
			
			$dataSession["script"]			= ""; 
			$dataSession["footer"]			= ""; 	
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}
	}
	
	function getIndexCorea($dataSession)
	{

		$firstDateYear					= helper_PrimerDiaDelMes();
		$lastDateYear					= helper_UltimoDiaDelMes();
		$firstDate						= helper_PrimerDiaDelMes();
		$lastDate						= helper_UltimoDiaDelMes();	
		
		if(
			$dataSession["role"]->name == "COREA@ADMINISTRADOR" ||
			$dataSession["role"]->name == "SUPE ADMIN"
		)
		{
			$firstDateYear					= helper_PrimerDiaDelYear();
			$lastDateYear					= helper_UltimoDiaDelYear();
		}
			
		
		//Obtener Desembolsos Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListDesembolsoMensual = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			$objListDesembolsoMensualTemporal = $this->Transaction_Master_Detail_Model->FinancieraCorea_Desembolsos_Mensuales($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListDesembolsoMensualTemporal)
			{
				array_push($objListDesembolsoMensual, $objListDesembolsoMensualTemporal[0]);

			}
			$objFirstYearDate->modify('+1 month');
		}
		
		
		
		//Obtener Pagos Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListPagoMensual = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			$objListPagoMensualTemporal = $this->Transaction_Master_Detail_Model->FinancieraCorea_Pagos_Mensuales($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListPagoMensualTemporal)
			{
				array_push($objListPagoMensual, $objListPagoMensualTemporal[0]);
	
			}
			$objFirstYearDate->modify('+1 month');
		}

		//Obtener Intereses Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListInteresMensual = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			$objListInteresMensualTemporal = $this->Transaction_Master_Detail_Model->FinancieraCorea_Interes_Mensual($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListInteresMensualTemporal)
			{
				array_push($objListInteresMensual, $objListInteresMensualTemporal[0]);
	
			}
			$objFirstYearDate->modify('+1 month');
		}

		//Obtener Capital Mensual
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListCapitalMensual = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			$objListCapitalMensualTemporal = $this->Transaction_Master_Detail_Model->FinancieraCorea_Capital_Mensual($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListCapitalMensualTemporal)
			{
				array_push($objListCapitalMensual, $objListCapitalMensualTemporal[0]);
	
			}
			$objFirstYearDate->modify('+1 month');
		}
		
		//Renderizar Resultado			
		$dataSession["objListCapitalMensual"]		= $objListCapitalMensual;
		$dataSession["objListInteresMensual"]		= $objListInteresMensual;
		$dataSession["objListDesembolsoMensual"]	= $objListDesembolsoMensual;
		$dataSession["objListPagoMensual"]			= $objListPagoMensual;
		return $dataSession;
	}

	function getIndexCompuMatt($dataSession)
	{
		$firstDateYear					= helper_PrimerDiaDelMes();
		$lastDateYear					= helper_UltimoDiaDelMes();
		$firstDate						= helper_PrimerDiaDelMes();
		$lastDate						= helper_UltimoDiaDelMes();	
		
		if(
			$dataSession["role"]->name == "COMPU_MATT@ADMINISTRADOR" ||
			$dataSession["role"]->name == "SUPE ADMIN"
		)
		{
			$firstDateYear					= helper_PrimerDiaDelYear();
			$lastDateYear					= helper_UltimoDiaDelYear();
		}
		
		
		
		$objListEstados					= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_taller","areaID",$dataSession["user"]->companyID);
		$objListOrdenesPorEmpleado		= $this->Transaction_Master_Detail_Model->CompuMatt_get_Orders_by_Employee($dataSession["user"]->companyID,$firstDate,$lastDate);
		$objListEstado					= $this->Transaction_Master_Detail_Model->CompuMatt_get_Amount_by_Status($dataSession["user"]->companyID,$firstDate,$lastDate);
		
		//Obtener las Ventas Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 			= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListVentaMensual 	= array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			
			$objListVentaMensualTemporal = $this->Transaction_Master_Detail_Model->CompuMatt_get_MonthOnly_Sales($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
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
			$objListVentaDiariaTemporal = $this->Transaction_Master_Detail_Model->CompuMatt_get_Day_Sales($dataSession["user"]->companyID, $objFirstDate->format("Y-m-d"),$objFirstDate->format("Y-m-d") );
			if($objListVentaDiariaTemporal)
			{
				array_push($objListVentaDiaria, $objListVentaDiariaTemporal[0]);

			}
			$objFirstDate->modify('+1 day');
		}
		
		//Renderizar Resultado			
		$dataSession["objListOrdenesPorEmpleado"]		= $objListOrdenesPorEmpleado;
		$dataSession["objListEstado"]					= $objListEstado;
		$dataSession["objListVentaMensual"]				= $objListVentaMensual;
		$dataSession["objListVentaDiaria"]				= $objListVentaDiaria;
		$dataSession["objListEstados"]					= $objListEstados;
		return $dataSession;
	}
	
	function getIndexEbenezer($dataSession)
	{
		$firstDateYear					= helper_PrimerDiaDelMes();
		$lastDateYear					= helper_UltimoDiaDelMes();
		$firstDate						= helper_PrimerDiaDelMes();
		$lastDate						= helper_UltimoDiaDelMes();			

		if(
			$dataSession["role"]->name == "EBENEZER@ADMINISTRADOR" ||
			$dataSession["role"]->name == "SUPE ADMIN"
		)
		{
			$firstDateYear					= helper_PrimerDiaDelYear();
			$lastDateYear					= helper_UltimoDiaDelYear();
		}

		$objListStudentsByCity			= $this->Transaction_Master_Detail_Model->Ebenezer_Get_Students_By_City();
		$objListStudentsBySex			= $this->Transaction_Master_Detail_Model->Ebenezer_Get_Students_By_Sex();
		
		//Obtener las Ventas Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListMonthlyCashSales = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			
			$objListIngresosPorVentaContadoTemporal = $this->Transaction_Master_Detail_Model->Ebenezer_Get_MonthOnly_CashSale($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListIngresosPorVentaContadoTemporal)
			{
				array_push($objListMonthlyCashSales, $objListIngresosPorVentaContadoTemporal[0]);

			}
			$objFirstYearDate->modify('+1 month');
		}		
		
		
		// Obtener las Ventas Diarias
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);						
		$objLastDate 		= \DateTime::createFromFormat('Y-m-d H:i:s', $lastDate);
		$objLastDate->setTime(0, 0, 0);
		$objNowDate 		= \DateTime::createFromFormat('Y-m-d H:i:s', helper_getDate());
		$objNowDate->setTime(0, 0, 0);
		$objListInscriptionsEarnings = array();
		while($objFirstDate <= $objLastDate)
		{				
			$objListIngresosPorMatriculaTemporal = $this->Transaction_Master_Detail_Model->Ebenezer_Get_MonthOnly_Inscriptions($dataSession["user"]->companyID, $objFirstDate->format("Y-m-d"),$objFirstDate->format("Y-m-d") );
			if($objListIngresosPorMatriculaTemporal)
			{
				array_push($objListInscriptionsEarnings, $objListIngresosPorMatriculaTemporal[0]);
				
			}
			$objFirstDate->modify('+1 day');
		}
		
		//Renderizar Resultado			
		$dataSession["objListStudentsByCity"]		= $objListStudentsByCity;
		$dataSession["objListStudentsBySex"]		= $objListStudentsBySex;
		$dataSession["objListMonthlyCashSales"]		= $objListMonthlyCashSales;
		$dataSession["objListInscriptionsEarnings"]	= $objListInscriptionsEarnings;
		return $dataSession;
	}
	
	function getIndexDefault($dataSession)
	{

		$firstDateYear					= helper_PrimerDiaDelYear();
		$lastDateYear					= helper_UltimoDiaDelMes();
		$firstDate						= helper_PrimerDiaDelMes();
		$lastDate						= helper_UltimoDiaDelMes();	
			
		
		//Obtener las Ventas de Contado del Mes Actual
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);						
		$objLastDate 		= \DateTime::createFromFormat('Y-m-d H:i:s', $lastDate);
		$objLastDate->setTime(0, 0, 0);
		$objNowDate 		= \DateTime::createFromFormat('Y-m-d H:i:s', helper_getDate());
		$objNowDate->setTime(0, 0, 0);
		$objListVentasContadoMesActual = array();
		while($objFirstDate <= $objNowDate)
		{				
			$objListVentasContadoMesActualTemporal = $this->Transaction_Master_Detail_Model->Default_Ventas_De_Contado_Mes_Actual($dataSession["user"]->companyID, $objFirstDate->format("Y-m-d"),$objFirstDate->format("Y-m-d") );
			if($objListVentasContadoMesActualTemporal)
			{
				array_push($objListVentasContadoMesActual, $objListVentasContadoMesActualTemporal[0]);
			}
			$objFirstDate->modify('+1 day');
		}
		
		$objListVentasContadoMesActual = $this->Transaction_Master_Detail_Model->Default_Ventas_De_Contado_Mes_Actual($dataSession["user"]->companyID, $objFirstDate->format("Y-m-d"),$objFirstDate->format("Y-m-d") );

		//Obtener Ventas de Contado Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListVentaContadoMensuales = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			$objListVentaContadoMensualTemporal = $this->Transaction_Master_Detail_Model->Default_Ventas_De_Contado_Mensuales($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListVentaContadoMensualTemporal)
			{
				array_push($objListVentaContadoMensuales, $objListVentaContadoMensualTemporal[0]);
			}
			$objFirstYearDate->modify('+1 month');
		}
		

		//Obtener Ventas al Credito Mensuales
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objListVentasCreditoMensuales = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			$objListVentasCreditoMensualTemporal = $this->Transaction_Master_Detail_Model->Default_Ventas_De_Credito_Mes_Actual($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListVentasCreditoMensualTemporal)
			{
				array_push($objListVentasCreditoMensuales, $objListVentasCreditoMensualTemporal[0]);
			}
			$objFirstYearDate->modify('+1 month');
		}

		//Obtener Capital Mensual
		$objFirstYearDate 		= \DateTime::createFromFormat('Y-m-d', $firstDateYear);
		$objFirstYearDate->setTime(0, 0, 0);						
		$objFirstDate 		= \DateTime::createFromFormat('Y-m-d', $firstDate);
		$objFirstDate->setTime(0, 0, 0);		
		$objPagosMensuales = array();
		while($objFirstYearDate <= $objFirstDate)
		{				
			$objLastDayMont =  \DateTime::createFromFormat('Y-m-d', $objFirstYearDate->format("Y-m-d"));
			$objLastDayMont->modify('+1 month');
			$objLastDayMont->modify('-1 day');
			$objListCapitalMensualTemporal = $this->Transaction_Master_Detail_Model->Default_Pagos_Mensuales($dataSession["user"]->companyID, $objFirstYearDate->format("Y-m-d"),$objLastDayMont->format("Y-m-d") );
			if($objListCapitalMensualTemporal)
			{
				array_push($objPagosMensuales, $objListCapitalMensualTemporal[0]);
			}
			$objFirstYearDate->modify('+1 month');
		}

		
		//Renderizar Resultado			
		$dataSession["objPagosMensuales"]					= $objPagosMensuales;
		$dataSession["objListVentasCreditoMensuales"]		= $objListVentasCreditoMensuales;
		$dataSession["objListVentasContadoMesActual"]		= $objListVentasContadoMesActual;
		$dataSession["objListVentaContadoMensuales"]		= $objListVentaContadoMensuales;
		return $dataSession;
	}
}
?>