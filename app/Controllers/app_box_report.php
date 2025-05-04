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
									= $this->core_web_menu->render_menu_body_report($dataSession["company"],$dataSession["menuBodyReport"],$parentMenuElementID);
									
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
			$hourStart			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourStart");//--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourEnd");//--finuri
			
			
			
			
			$startOn			= $startOn != "" ? $startOn: 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");
			$startOn 			= $startOn." ".$hourStart.":00:00";	
			$endOn				= $endOn != "" ? $endOn : 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");	
			$hourEnd			= $hourEnd ? $hourEnd : "23";
			$endOn				= $endOn." ".$hourEnd.":59:59";	
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri
						
			
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
				log_message("error",print_r($userID,true));
				log_message("error",print_r($tocken,true));
				log_message("error",print_r($companyID,true));
				log_message("error",print_r($authorization,true));
				log_message("error",print_r($startOn,true));
				log_message("error",print_r($endOn,true));
				log_message("error",print_r($userIDFilter,true));
				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,0]
				);			
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem,0,0,0]
				);	

				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem,0]
				);					
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter,0]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter,0]
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
				$objDataResult["fontSize"]					= "smaller";
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				//
				
				//flavor: Narvaez
				if( $objCompany->flavorID == 427 ) 
				{
					return view("app_box_report/share/view_a_disemp_teledollar_narvaez",$objDataResult);//--finview-r
				}
				//globalpro
				else if( $objCompany->flavorID == 306 ) 
				{
					$objDataResult["fontSize"]					= "12px";
					return view("app_box_report/share/view_a_disemp",$objDataResult);//--finview-r
				}
				//gym raptor 
				else if( $objCompany->flavorID == 260 ) 
				{
					return view("app_box_report/share/view_a_disemp_raptor",$objDataResult);//--finview-r
				}
				else 
				{
					return view("app_box_report/share/view_a_disemp",$objDataResult);//--finview-r
				}
				
			}
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
			$showSumaryAmount	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"showSumaryAmount");//--finuri				
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$startOn			= $startOn != "" ? $startOn: 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");
			$endOn				= $endOn != "" ? $endOn : 	\DateTime::createFromFormat('Y-m-d', date("Y-m-d") )->format("Y-m-d");			
			$endOn				= $endOn." 23:59:59";	
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri
			$branchIDFiltered	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri
			
			//calcular las fechas iniciales del reporte
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			
			$startOn_Temporal = $endOn_;				
			date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
			
			//if($startOn_ <  $startOn_Temporal)
			//{
			//	$startOn = $startOn_Temporal->format('Y-m-d');
			//}
			
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Obtener lista de conceptos.
				$dataView["objTipoMovementInputCash"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_inputcash","areaID",$companyID);
				$dataView["objTipoMovementOutputCash"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_outputcash","areaID",$companyID);
				$dataView["objListCategoryItem"]			= $this->Itemcategory_Model->getByCompany($companyID);
				$dataView["objListBranch"]					= $this->Branch_Model->getByCompany($companyID);
				$dataView["objCompany"]						= $dataSession["company"];
				
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
				$objListBranch	= $this->Branch_Model->getByCompany($companyID);
				$html 			= "";
				
				
				if($branchIDFiltered != 0)
				{
					if($objListBranch)
					{
						foreach($objListBranch as $objBranch)
						{
							if($objBranch->branchID == $branchIDFiltered  )
							{
								$reult  = $this->daily_town_operation(
									$showSumaryAmount,
									$userID,$tocken,$companyID,$authorization,
									$startOn,$endOn,$userIDFilter,$objBranch->branchID,$categoryItem,
									$conceptoFilter,
									$objCompany,
									$objParameter,								
									$dataSession,
									$objBranch->name 
								);
								
								$html = $html.$reult;
							}
						}
					}
				}
				if($branchIDFiltered == 0)
				{
					
					$reult  = $this->daily_town_operation(
						$showSumaryAmount,
						$userID,$tocken,$companyID,$authorization,
						$startOn,$endOn,$userIDFilter,0 /*branchID*/,$categoryItem,
						$conceptoFilter,
						$objCompany,
						$objParameter,								
						$dataSession,
						"TODAS LAS SUCURSALES" 
					);
					
					$html = $html.$reult;
							
				}
				
				 // Retornar el HTML como respuesta
				return $this->response->setBody($html)
                        ->setHeader('Content-Type', 'text/html');
				
				
				
			}
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
	
	function daily_town_operation(
		$showSumaryAmount,
		$userID,$tocken,$companyID,$authorization,
		$startOn,$endOn,$userIDFilter,$branchIDFiltered,$categoryItem,
		$conceptoFilter,
		$objCompany,
		$objParameter,
		$dataSession,
		$branchName
	)
	{
		
		
		//Mostrar reporte normal
		if ($showSumaryAmount == "0" )
		{
			//Get Datos
			$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$branchIDFiltered]
			);			
			//Get Datos de Facturacion				
			$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?);";
			$objDataSales	= $this->Bd_Model->executeRender(
				$query,
				[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem,0,$branchIDFiltered,0]
			);	

			$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
			$objDataSalesCredito	= $this->Bd_Model->executeRender(
				$query,
				[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,$categoryItem,$branchIDFiltered]
			);					
			
			//Get Datos de Entrada de Efectivo y Salida				
			$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?);";
			$objDataCash	= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter,$branchIDFiltered]
			);			
			
			$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
			$objDataCashOut	= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,$conceptoFilter,$branchIDFiltered]
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
		
		
			$objDataResult["branchName"] 				= $branchName;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["startOn"] 					= $startOn;
			$objDataResult["endOn"] 					= $endOn;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			//
			return view("app_box_report/daily_town/view_a_disemp",$objDataResult, ['return' => true]);//--finview-r
					
		}
		if ($showSumaryAmount == "1" )
		{
			$startOn_r 		= \DateTime::createFromFormat('Y-m-d',$startOn)->format("Y-m-d");
			$endOn_r		= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn)->format("Y-m-d H:i:s");					
			$startOn 		= \DateTime::createFromFormat('Y-m-d',$startOn)->format("Y-m-d");
			$endOn 			= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn)->format("Y-m-d");										
			$startOn 		= \DateTime::createFromFormat('Y-m-d',$startOn);
			$endOn 			= \DateTime::createFromFormat('Y-m-d',$endOn);
			$dataDetail 	= null;
			
			
			
			
			while($startOn <= $endOn)
			{
				
				$startOn_ 	= $startOn->format("Y-m-d");
				$endOn_ 	= $endOn->format("Y-m-d")." 23:59:59";
			
				
				
				//Get Datos
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn_,$endOn_,$userIDFilter,$branchIDFiltered]
				);			
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn_,$endOn_,$userIDFilter,$categoryItem,0,$branchIDFiltered,0]
				);	

				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn_,$endOn_,$userIDFilter,$categoryItem,$branchIDFiltered]
				);					
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn_,$endOn_,$userIDFilter,$conceptoFilter,$branchIDFiltered]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn_,$endOn_,$userIDFilter,$conceptoFilter,$branchIDFiltered]
				);			
				
				if(isset($objData))
				$dataDetail[$startOn_]["objDetail"]					= $objData;
				else
				$dataDetail[$startOn_]["objDetail"]					= NULL;
			
			
				if(isset($objDataSales))
				$dataDetail[$startOn_]["objSales"]					= $objDataSales;
				else
				$dataDetail[$startOn_]["objSales"]					= NULL;
			
				
				if(isset($objDataSalesCredito))
				$dataDetail[$startOn_]["objSalesCredito"]			= $objDataSalesCredito;
				else
				$dataDetail[$startOn_]["objSalesCredito"]			= NULL;
			
				if(isset($objDataCash))				
				$dataDetail[$startOn_]["objCash"]					= $objDataCash;
				else
				$dataDetail[$startOn_]["objCash"]					= NULL;
			
				if(isset($objDataCashOut))				
				$dataDetail[$startOn_]["objCashOut"]					= $objDataCashOut;
				else
				$dataDetail[$startOn_]["objCashOut"]					= NULL;
			
			
				date_add($startOn, date_interval_create_from_date_string('1 days'));						
			}
			
			
			$objDataResult["branchName"] 				= $branchName;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["startOn"] 					= $startOn_r;
			$objDataResult["endOn"] 					= $endOn_r;
			$objDataResult["dataDetail"] 				= $dataDetail;
			
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);					
			return view("app_box_report/daily_town/view_a_disemp_summary_amount",$objDataResult, ['return' => true]);//--finview-r
					
					
					
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
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,0]
				);
				
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1",0,0,0]
				);	

				
				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1",0]
				);	
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1",0]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1",0]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
			
				if(isset($objDataSales))
				{
				$objDataResult["objSales"]					= $objDataSales;
				}
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
				
				if($objCompany->type == "galmcuts")
				$html = view("app_box_report/share_summary/view_a_disemp_glamcuts",$objDataResult);//--finview-r				
				else 
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
				
				$pathFileFloder = "./resource/file_company/company_".$companyID."/component_48/component_item_0";
                if(!file_exists($pathFileFloder))
                    mkdir($pathFileFloder, 0700);				
				
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
	
	
	function share_summary_80mm_prestatario(){
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
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_prestatario/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_prestatario/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/share_summary_80mm_prestatario/view_script');//--finview
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
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,0]
				);
				
				//Get Datos de Facturacion de contado		
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1",0,0,0]
				);	

				//Get Datos de Facturas de credito
				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1",0]
				);	
				
				//Get Datos de Entrada de Efectivo
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1",0]
				);			
				
				//Get Datos de Salida de Efectivo
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1",0]
				);			
				
				
				if(isset($objData))
				$objDataResult["objAbonos"]					= $objData;
				else
				$objDataResult["objAbonos"]					= NULL;
			
			
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
				
				$html = view("app_box_report/share_summary_80mm_prestatario/view_a_disemp",$objDataResult);//--finview-r				
				
				
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
	
	function attendance(){
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
			
			
			
			//obtener el desplazamiento de la fecha de reporte			
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
			
			//obtener parametros
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri			
			$hourOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourStart");//--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourEnd");//--finuri
			$userIDFilter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri			
			$format				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"format");//--finuri			
			$size				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"size");//--finuri			
			
			
			//calcular las fechas iniciales del reporte y fecha final
			$startOn			= $startOn." ".$hourOn.":00:00";	
			$endOn				= $endOn." ".$hourEnd.":59:59";				
			$startOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$startOn);		
			$endOn_ 	= \DateTime::createFromFormat('Y-m-d H:i:s',$endOn);		
			if($filteredArray != -1){
				$startOn_Temporal = $endOn_;
				date_sub($startOn_Temporal, date_interval_create_from_date_string($filteredArray.' days'));
				
				if($startOn_ <  $startOn_Temporal){
					$startOn = $startOn_Temporal->format('Y-m-d H:i:s');
				}
			}
			
			
			
			
			//cargar pantalla de filtros
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_box_report/attendance/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_box_report/attendance/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_box_report/attendance/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			
			
			//procesar reporte			
			$obUserModel	= $this->User_Model->get_rowByPK($companyID, $branchID, $userIDFilter);
			$companyID 		= $companyID;
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);


			$query			= "CALL pr_box_get_report_attendance(?,?,?,?,?,?,?);";			
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID, $tocken, $companyID, $authorization, $startOn, $endOn, $userIDFilter]
			);

			if (isset($objData))
				$objDataResult["objDetail"]					= $objData;
			else
				$objDataResult["objDetail"]					= NULL;




			//parametros de reportes
			$params_["objCompany"]					= $objCompany;			
			$params_["startOn"]						= str_replace(" 00:00:00", "", $startOn);			
			$params_["endOn"]						= str_replace(" 00:00:00", "", $endOn);
			$params_["dateCurrent"]					= date("Y-m-d H:i:s");
			$params_["obUserModel"]					= $obUserModel;
			$params_["objDetail"]					= $objDataResult["objDetail"];
			$params_["objLogo"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
			$params_["message"]						= str_replace(" 00:00:00", "", $startOn) . " ASISTENCIA DE CAJA: " . $objCompany->name . " ";
			$params_["objFirma"] 					= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_box_get_report_attendance" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
			$params_["objFirmaEncription"] 			= md5($params_["objFirma"]);
			$subject								= $params_["message"];
			$html  									= /*--inicio view*/ view('app_box_report/attendance/view_a_disemp', $params_); //--finview


			//generar resultado html
			if (strtoupper($format) == strtoupper("HTML")) {

				echo $html;
				
			} 
			
			//generar resultado pdf
			if (strtoupper($format) == strtoupper("PDF")) 
			{			
				$this->dompdf->loadHTML($html);
				$this->dompdf->render();
				
				$objParameterShowLinkDownload		= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID);
				$objParameterShowLinkDownload		= $objParameterShowLinkDownload->value;
				$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW", $companyID);
				$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
				$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;

				$fileNamePut = "caja_0_" . date("dmYhis") . ".pdf";
				$path        = "./resource/file_company/company_" . $companyID . "/component_48/component_item_0/" . $fileNamePut;


				//Crear la Carpeta para almacenar los Archivos del Documento
				$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_0";
				if (!file_exists($documentoPath))
				{
					mkdir($documentoPath, 0755);
					chmod($documentoPath, 0755);
				}
			
			
				file_put_contents($path, $this->dompdf->output());
				chmod($path, 644);

				if ($objParameterShowLinkDownload == "true") {
					echo "<a href='" . base_url() . "/resource/file_company/company_" . $companyID . "/component_48/component_item_0/" . $fileNamePut . "'>download caja</a>";
				} 
				else 
				{
					//visualizar
					$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview]);
				}
			}
			
			
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
				$query			= "CALL pr_box_get_report_abonos(?,?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,0]
				);
				
				//Get Datos de Facturacion				
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?);";
				$objDataSales	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1",0,0,0]
				);		

				
				$query					= "CALL pr_sales_get_report_sales_summary_credit(?,?,?,?,?,?,?,?);";
				$objDataSalesCredito	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$userIDFilter,"-1",0]
				);	
				
				//Get Datos de Entrada de Efectivo y Salida				
				$query			= "CALL pr_box_get_report_input_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCash	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1",0]
				);			
				
				$query			= "CALL pr_box_get_report_output_cash(?,?,?,?,?,?,?,?,?);";
				$objDataCashOut	= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$authorization,$startOn,$endOn,$userIDFilter,"-1",0]
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
	function reconciliation_deposits()
	{
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
			
								
			$viewReport			= false;
			$employeeNumber		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$urilEmployeeNumber = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"employeeNumber");//--finuri					
			$employeeNumber		= $this->core_web_counter->getFillNumber($companyID,$branchID,"tb_employee",0,$urilEmployeeNumber);
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri		
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri		
				
			if(!($viewReport && $employeeNumber)){
				//Renderizar Resultado 
				$dataView["objListEmployer"]	= $this->Employee_Model->get_rowByCompanyID($companyID);
				$dataSession["message"]			= $this->core_web_notification->get_message();				
				$dataSession["head"]			= /*--inicio view*/ view('app_box_report/reconciliation_deposits/view_head',$dataView);//--finview
				$dataSession["body"]			= /*--inicio view*/ view('app_box_report/reconciliation_deposits/view_body',$dataView);//--finview
				$dataSession["script"]			= /*--inicio view*/ view('app_box_report/reconciliation_deposits/view_script',$dataView);//--finview
				$dataSession["footer"]			= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$objPropietaryName		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_NAME",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_box_get_report_reconciliation_deposit(?,?,?,?,?,?);";
				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$employeeNumber,$startOn,$endOn]
				);			
				
				if(isset($objData)){
					$objDataResult["objDetail"]					= $objData;
					$objDataResult["objFirstDetail"]			= $objData[0];
				}
				else{
					$objDataResult["objDetail"]					= NULL;
					$objDataResult["objFirstDetail"]			= NULL;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objPropietaryName"] 		= $objPropietaryName;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_document_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_box_report/reconciliation_deposits/view_a_disemp",$objDataResult);//--finview-r
				
			}
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