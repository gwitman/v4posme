<?php
//posme:2023-02-27
namespace App\Controllers;
class app_box_report extends _BaseController {
	
   
	function index($dataViewID = null){	
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			
				$parentMenuElementID 	= $this->core_web_permission->getElementID(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			}	
			
			//Obtener la Lista de Reportes
			$dataMenu["menuRenderBodyReport"] 	
									= $this->core_web_menu->render_menu_body_report($dataSession["menuBodyReport"],$parentMenuElementID);
									
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_report/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_report/view_body',$dataMenu);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_report/view_script');//--finview
			$dataSession["footer"]			= "";			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function share(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);	
	
			}	
			
			$authorization		= $resultPermission;
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			//
			//obtener permiso de fecha de reporte				
			$filterArray 		= array_filter($dataSession["menuHiddenPopup"], function($val){  return (strpos($val->display, 'ES_PERMITIDO_MOSTRAR_INFO_DE_') !== false) && (strpos($val->display, '_DAY_IN_app_box_report_share') !== false) ;});
			$filteredArray 		= [];
			$filterIndex 		= 0;			
			foreach($filterArray as $key => $value ){ $filteredArray[$filterIndex] = $value;  $filterIndex++; }
			if(count($filteredArray) > 0 && $dataSession["role"]->isAdmin == 0 ){
				$filteredArray = str_replace("ES_PERMITIDO_MOSTRAR_INFO_DE_","",$filteredArray[0]->display);
				$filteredArray = str_replace("_DAY_IN_app_box_report_share","",$filteredArray);
				$filteredArray = intval($filteredArray);
			}
			else{
				$filteredArray = -1;
			}
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$conceptoFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"conceptoFilter");//--finuri	
			$categoryItem		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"categoryItem");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$startOn			= $startOn != "" ? $startOn: 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");
			$endOn				= $endOn != "" ? $endOn : 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");			
			$endOn				= $endOn." 23:59:59";	
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri
			
			//calcular las fechas iniciales del reporte
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			if($filteredArray != -1){
				$startOn_Temporal = $endOn_;				
				date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
				
				if($startOn_ <  $startOn_Temporal){
					$startOn = $startOn_Temporal->format('Y-m-d');
				}
			}
			
			
			
			
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Obtener lista de conceptos.
				$dataView["objTipoMovementInputCash"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_inputcash","areaID",$companyID);
				$dataView["objTipoMovementOutputCash"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_outputcash","areaID",$companyID);
				$dataView["objListCategoryItem"]			= $this->Itemcategory_Model->getByCompany($companyID);
				
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/share/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/share/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/share/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{						
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter]
				);			
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem]
				);	

				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem]
				);					
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
			
				if(isset($objDataSales))
				$objDataResult["objSales"]					= $objDataSales;
				else
				$objDataResult["objSales"]					= NULL;
			
				
				if(isset($objDataSalesCredito))
				$objDataResult["objSalesCredito"]			= $objDataSalesCredito;
				else
				$objDataResult["objSalesCredito"]			= NULL;
			
				if(isset($objDataCash))				
				$objDataResult["objCash"]					= $objDataCash;
				else
				$objDataResult["objCash"]					= NULL;
			
				if(isset($objDataCashOut))				
				$objDataResult["objCashOut"]					= $objDataCashOut;
				else
				$objDataResult["objCashOut"]					= NULL;
			
				
				
				
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				//
				return view("app_box_report/share/view_a_disemp",$objDataResult);//--finview-r
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function daily_town(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);	
	
			}	
			
			$authorization		= $resultPermission;
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			//
			//obtener permiso de fecha de reporte				
			$filterArray 		= array_filter($dataSession["menuHiddenPopup"], function($val){  return (strpos($val->display, 'ES_PERMITIDO_MOSTRAR_INFO_DE_') !== false) && (strpos($val->display, '_DAY_IN_app_box_report_share') !== false) ;});
			$filteredArray 		= [];
			$filterIndex 		= 0;		
			$filteredArray		= 0;
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$conceptoFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"conceptoFilter");//--finuri	
			$categoryItem		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"categoryItem");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$startOn			= $startOn != "" ? $startOn: 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");
			$endOn				= $endOn != "" ? $endOn : 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");			
			$endOn				= $endOn." 23:59:59";	
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri
			
			//calcular las fechas iniciales del reporte
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			
			$startOn_Temporal = $endOn_;				
			date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
			
			if($startOn_ <  $startOn_Temporal)
			{
				$startOn = $startOn_Temporal->format('Y-m-d');
			}
			
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Obtener lista de conceptos.
				$dataView["objTipoMovementInputCash"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_inputcash","areaID",$companyID);
				$dataView["objTipoMovementOutputCash"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_outputcash","areaID",$companyID);
				$dataView["objListCategoryItem"]			= $this->Itemcategory_Model->getByCompany($companyID);
				
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/daily_town/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/daily_town/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/daily_town/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{						
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter]
				);			
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem]
				);	

				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem]
				);					
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
			
				if(isset($objDataSales))
				$objDataResult["objSales"]					= $objDataSales;
				else
				$objDataResult["objSales"]					= NULL;
			
				
				if(isset($objDataSalesCredito))
				$objDataResult["objSalesCredito"]			= $objDataSalesCredito;
				else
				$objDataResult["objSalesCredito"]			= NULL;
			
				if(isset($objDataCash))				
				$objDataResult["objCash"]					= $objDataCash;
				else
				$objDataResult["objCash"]					= NULL;
			
				if(isset($objDataCashOut))				
				$objDataResult["objCashOut"]					= $objDataCashOut;
				else
				$objDataResult["objCashOut"]					= NULL;
			
				
				
				
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				//
				return view("app_box_report/daily_town/view_a_disemp",$objDataResult);//--finview-r
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function share_summary(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);	
	
			}	
			
			$authorization		= $resultPermission;
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$hourOn				= 1;
			$hourEnd			= 23;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			//
			//obtener permiso de fecha de reporte				
			$filterArray 		= array_filter($dataSession["menuHiddenPopup"], function($val){  return (strpos($val->display, 'ES_PERMITIDO_MOSTRAR_INFO_DE_') !== false) && (strpos($val->display, '_DAY_IN_app_box_report_share') !== false) ;});
			$filteredArray 		= [];
			$filterIndex 		= 0;			
			foreach($filterArray as $key => $value ){ $filteredArray[$filterIndex] = $value;  $filterIndex++; }
			if(count($filteredArray) > 0 && $dataSession["role"]->isAdmin == 0 ){
				$filteredArray = str_replace("ES_PERMITIDO_MOSTRAR_INFO_DE_","",$filteredArray[0]->display);
				$filteredArray = str_replace("_DAY_IN_app_box_report_share","",$filteredArray);
				$filteredArray = intval($filteredArray);
			}
			else{
				$filteredArray = -1;
			}
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri			
			$hourOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourStart");//--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourEnd");//--finuri
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri			
			
			
			$startOn			= $startOn." ".$hourOn.":00:00";	
			$endOn				= $endOn." ".$hourEnd.":59:59";				
						
			//calcular las fechas iniciales del reporte
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			if($filteredArray != -1){
				$startOn_Temporal = $endOn_;
				date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
				
				if($startOn_ <  $startOn_Temporal){
					$startOn = $startOn_Temporal->format('Y-m-d H:i:s');
				}
			}
			
			
			
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/share_summary/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/share_summary/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/share_summary/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{						
			
				$obUserModel		= $this->User_Model->get_rowByPK($companyID,$branchID,$userIDFilter);
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter]
				);
				
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1"]
				);	

				
				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1"]
				);	
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1"]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1"]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
			
				if(isset($objDataSales))
				$objDataResult["objSales"]					= $objDataSales;
				else
				$objDataResult["objSales"]					= NULL;
			
				if(isset($objDataSalesCredito))
				$objDataResult["objSalesCredito"]			= $objDataSalesCredito;
				else
				$objDataResult["objSalesCredito"]			= NULL;
			
				if(isset($objDataCash))				
				$objDataResult["objCash"]					= $objDataCash;
				else
				$objDataResult["objCash"]					= NULL;
			
				if(isset($objDataCashOut))				
				$objDataResult["objCashOut"]					= $objDataCashOut;
				else
				$objDataResult["objCashOut"]					= NULL;
			
				
				
				
				
				
				$objDataResult["obUserModel"] 				= $obUserModel;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				$html = view("app_box_report/share_summary/view_a_disemp",$objDataResult);//--finview-r				
				$this->dompdf->loadHTML($html);
			
				//1cm = 29.34666puntos
				//a4: 210 ancho x 297
				//a4: 21cm x 29.7cm
				//a4: 595.28puntos x 841.59puntos
				
				//$this->dompdf->setPaper('A4','portrait');
				//$this->dompdf->setPaper(array(0,0,234.76,6000));
				
				$this->dompdf->render();
				
				//visualizar
				
				$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
				$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
				$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
				$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
				$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
				
				$fileNamePut = "caja_0_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_0/".$fileNamePut;
					
				file_put_contents($path,$this->dompdf->output());
				chmod($path, 644);
				
				if($objParameterShowLinkDownload == "true")
				{	
					echo "<a href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_0/".$fileNamePut."'>download caja</a>";					
				}
				else{			
					//visualizar
					$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
				}
				
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
	function share_summary_80mm_direct(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);	
	
			}	
			
			$authorization		= $resultPermission;
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$hourOn				= 1;
			$hourEnd			= 23;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			//
			//obtener permiso de fecha de reporte				
			$filterArray 		= array_filter($dataSession["menuHiddenPopup"], function($val){  return (strpos($val->display, 'ES_PERMITIDO_MOSTRAR_INFO_DE_') !== false) && (strpos($val->display, '_DAY_IN_app_box_report_share') !== false) ;});
			$filteredArray 		= [];
			$filterIndex 		= 0;			
			foreach($filterArray as $key => $value ){ $filteredArray[$filterIndex] = $value;  $filterIndex++; }
			if(count($filteredArray) > 0 && $dataSession["role"]->isAdmin == 0 ){
				$filteredArray = str_replace("ES_PERMITIDO_MOSTRAR_INFO_DE_","",$filteredArray[0]->display);
				$filteredArray = str_replace("_DAY_IN_app_box_report_share","",$filteredArray);
				$filteredArray = intval($filteredArray);
			}
			else{
				$filteredArray = -1;
			}
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri			
			$hourOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourStart");//--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourEnd");//--finuri
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri			
			
			
			$startOn			= $startOn." ".$hourOn.":00:00";	
			$endOn				= $endOn." ".$hourEnd.":59:59";				
						
			//calcular las fechas iniciales del reporte
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			if($filteredArray != -1){
				$startOn_Temporal = $endOn_;
				date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
				
				if($startOn_ <  $startOn_Temporal){
					$startOn = $startOn_Temporal->format('Y-m-d H:i:s');
				}
			}
			
			
			
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_direct/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_direct/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_direct/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{						
			
				$obUserModel		= $this->User_Model->get_rowByPK($companyID,$branchID,$userIDFilter);
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_box_get_report_closed(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter]
				);
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
			
				
				$objDataResult["obUserModel"] 				= $obUserModel;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				$objDataResult["objParameterLogo"]			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$objDataResult["objUsuario"] 				= $dataSession["user"];
		
				
				$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
				$objParameterPrinterName = $objParameterPrinterName->value;
				$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
				$this->core_web_printer_direct->executePrinter80mmReportCashClosedDirect($objDataResult);
				
				
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function share_summary_80mm_general(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);	
	
			}	
			
			$authorization		= $resultPermission;								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$hourOn				= 1;
			$hourEnd			= 23;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			//
			//obtener permiso de fecha de reporte				
			$filterArray 		= array_filter($dataSession["menuHiddenPopup"], function($val){  return (strpos($val->display, 'ES_PERMITIDO_MOSTRAR_INFO_DE_') !== false) && (strpos($val->display, '_DAY_IN_app_box_report_share') !== false) ;});
			$filteredArray 		= [];
			$filterIndex 		= 0;			
			foreach($filterArray as $key => $value ){ $filteredArray[$filterIndex] = $value;  $filterIndex++; }
			if(count($filteredArray) > 0 && $dataSession["role"]->isAdmin == 0 ){
				$filteredArray = str_replace("ES_PERMITIDO_MOSTRAR_INFO_DE_","",$filteredArray[0]->display);
				$filteredArray = str_replace("_DAY_IN_app_box_report_share","",$filteredArray);
				$filteredArray = intval($filteredArray);
			}
			else{
				$filteredArray = -1;
			}
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri			
			$hourOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourStart");//--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourEnd");//--finuri
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri			
			
			
			$startOn			= $startOn." ".$hourOn.":00:00";	
			$endOn				= $endOn." ".$hourEnd.":59:59";				
						
			//calcular las fechas iniciales del reporte
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			if($filteredArray != -1){
				$startOn_Temporal = $endOn_;
				date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
				
				if($startOn_ <  $startOn_Temporal){
					$startOn = $startOn_Temporal->format('Y-m-d H:i:s');
				}
			}
			
			
			
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_general/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_general/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_general/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
	
	
	function share_summary_58mm(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);	
	
			}	
			
			$authorization		= $resultPermission;
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			//
			//obtener permiso de fecha de reporte				
			$filterArray 		= array_filter($dataSession["menuHiddenPopup"], function($val){  return (strpos($val->display, 'ES_PERMITIDO_MOSTRAR_INFO_DE_') !== false) && (strpos($val->display, '_DAY_IN_app_box_report_share') !== false) ;});
			$filteredArray 		= [];
			$filterIndex 		= 0;			
			foreach($filterArray as $key => $value ){ $filteredArray[$filterIndex] = $value;  $filterIndex++; }
			if(count($filteredArray) > 0 && $dataSession["role"]->isAdmin == 0 ){
				$filteredArray = str_replace("ES_PERMITIDO_MOSTRAR_INFO_DE_","",$filteredArray[0]->display);
				$filteredArray = str_replace("_DAY_IN_app_box_report_share","",$filteredArray);
				$filteredArray = intval($filteredArray);
			}
			else{
				$filteredArray = -1;
			}
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$startOn 			= $startOn != "" ? $startOn: \DateTime::createFromFormat('Y-m-d',date("Y-m-d"))->format("Y-m-d");  			
			$endOn 				= $endOn  != ""? $endOn: \DateTime::createFromFormat('Y-m-d',date("Y-m-d"))->format("Y-m-d");  		
			$endOn				= $endOn." 23:59:59";	
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri
			//calcular las fechas iniciales del reporte
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			if($filteredArray != -1){
				$startOn_Temporal = $endOn_;
				date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
				
				if($startOn_ <  $startOn_Temporal){
					$startOn = $startOn_Temporal->format('Y-m-d');
				}
			}
			
			
			
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/share_summary/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/share_summary/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/share_summary/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{						
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter]
				);
				
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1"]
				);		

				
				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1"]
				);	
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1"]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1"]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
			
				if(isset($objDataSales))
				$objDataResult["objSales"]					= $objDataSales;
				else
				$objDataResult["objSales"]					= NULL;
			
				
				if(isset($objDataSalesCredito))
				$objDataResult["objSalesCredito"]			= $objDataSalesCredito;
				else
				$objDataResult["objSalesCredito"]			= NULL;
			
			
				if(isset($objDataCash))				
				$objDataResult["objCash"]					= $objDataCash;
				else
				$objDataResult["objCash"]					= NULL;
			
				if(isset($objDataCashOut))				
				$objDataResult["objCashOut"]					= $objDataCashOut;
				else
				$objDataResult["objCashOut"]					= NULL;
			
				
				
				
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				//
				$html = view("app_box_report/share_summary/view_a_disemp",$objDataResult);//--finview-r
				
				$this->dompdf->loadHTML($html);
			
				//1cm = 29.34666puntos
				//a4: 210 ancho x 297
				//a4: 21cm x 29.7cm
				//a4: 595.28puntos x 841.59puntos
				
				//$this->dompdf->setPaper('A4','portrait');
				//$this->dompdf->setPaper(array(0,0,234.76,6000));
				
				$this->dompdf->render();
				
				//visualizar
				
				$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
				$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
				$this->dompdf->stream("file.pdf", ['Attachment' => !$objParameterShowLinkDownload]);
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
}
?>