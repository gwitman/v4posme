<?php
//posme:2023-02-27
namespace App\Controllers;
class core_dashboards_globalpro extends _BaseController {
	
    
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
			
			
			
			$firstDateYear					= helper_PrimerDiaDelMes();
			$lastDateYear					= helper_UltimoDiaDelMes();
			
			if(
				$dataSession["role"]->name == "GLOBALPRO@ADMINISTRADOR" ||
				$dataSession["role"]->name == "SUPE ADMIN"
			)
			{
				$firstDateYear					= helper_PrimerDiaDelYear();
				$lastDateYear					= helper_UltimoDiaDelYear();
			}
			
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
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= "";
			$dataSession["head"]			= "";
			$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_globalpro',$dataSession);//--finview
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
}
?>