<?php
//posme:2023-02-27
namespace App\Controllers;
class app_collection_report extends _BaseController {
	
   
	function index($dataViewID = null)
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
			
				$parentMenuElementID 	= $this->core_web_permission->getElementID(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			}	
			
			//Obtener la Lista de Reportes
			$dataMenu["menuRenderBodyReport"] 	
									= $this->core_web_menu->render_menu_body_report($dataSession["company"],$dataSession["menuBodyReport"],$parentMenuElementID);
									
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_collection_report/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_collection_report/view_body',$dataMenu);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_collection_report/view_script');//--finview
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
	function commission_provider(){
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
			
			$providerID			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri					
			$providerID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"providerID");//--finuri
			
			
			if(!($viewReport && $startOn && $endOn   )){
				
				$objFiltros						= NULL;
				$objFiltros["objListProvider"]	= $this->Provider_Model->get_rowByCompany($companyID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_collection_report/commission_provider/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_collection_report/commission_provider/view_body',$objFiltros);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_collection_report/commission_provider/view_script');//--finview
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
				$query			= "CALL pr_collection_get_report_commision_provider(?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$startOn,$endOn,$providerID]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_collection_report/commission_provider/view_a_disemp",$objDataResult);//--finview-r
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
	function document_credit(){
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
			$documentNumber		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$documentNumber		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"documentNumber");//--finuri						
			
				
			if(!($viewReport && $documentNumber)){
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_collection_report/document_credit/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_collection_report/document_credit/view_body');//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_collection_report/document_credit/view_script');//--finview
				$dataSession["footer"]		= "";			
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
				$query			= "CALL pr_collection_get_report_document_credit(?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$documentNumber]
				);			
				
				if(isset($objData)){
					$objDataResult["objDetail"]					= $objData;
				}
				else{
					$objDataResult["objDetail"]					= NULL;
				}
				$objDataResult["objUser"] 					= $dataSession["user"];
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objPropietaryName"] 		= $objPropietaryName;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_document_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_collection_report/document_credit/view_a_disemp",$objDataResult);//--finview-r
				
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
	function customer(){
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
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			 
			
			//Cargar Libreria
				
			
				
				
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_collection_get_report_customer(?,?,?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID]
			);
			
			if(isset($objData))
			$objDataResult["objDetail"]					= $objData;
			else
			$objDataResult["objDetail"]					= NULL;
		
			$objDataResult["objUser"]					= $dataSession["user"];
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			
			return view("app_collection_report/customer/view_a_disemp",$objDataResult);//--finview-r
			
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
	function commission(){
		try{ 
		
			//CARGAR HELPER
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
			
									
			$viewReport		= false;
			$periodID		= false;
			$cycleID		= false;
			$companyID		= $dataSession["user"]->companyID;
			$branchID		= $dataSession["user"]->branchID;
			$userID			= $dataSession["user"]->userID;
			$tocken			= '';
			
			$viewReport		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$periodID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"periodID");//--finuri
			$cycleID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"cycleID");//--finuri	
			 
			
			if(!($viewReport && $periodID && $cycleID)){
				
				$data["objListAccountingPeriod"] 
										= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= /*--inicio view*/ view('app_collection_report/commission/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_collection_report/commission/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_collection_report/commission/view_script');//--finview
				$dataSession["footer"]	= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				//Cargar Libreria
					
				
				
				
				
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
				$objCycle		= $this->Component_Cycle_Model->get_rowByPK($periodID,$cycleID);
				$objPeriod		= $this->Component_Period_Model->get_rowByPK($periodID);
				//Get Datos
				$query			= "CALL pr_collection_get_report_detalle_transaction(?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$branchID,$userID,$tocken,$periodID,$cycleID]
				);	
				$objDataResult["objDetail"]			= $objData;
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balance_general" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_collection_report/commission/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	function documents_credit(){
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
			
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
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
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_collection_report/documents_credit/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_collection_report/documents_credit/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_collection_report/documents_credit/view_script');//--finview
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
				$query			= "CALL pr_collection_get_report_documents_credit(?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn]
				);			
				

				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["fontSize"]					= "smaller";
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				return view("app_collection_report/documents_credit/view_a_disemp",$objDataResult);//--finview-r
				
				//wg-rallback-if(count($objDataResult["objDetail"]) <= 1500 )
				//wg-rallback-{
				//wg-rallback-	
				//wg-rallback-}
				//wg-rallback-else
				//wg-rallback-{
				//wg-rallback-	$objParameterDeliminterCsv	 = $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				//wg-rallback-	$objParameterDeliminterCsv	 = $objParameterDeliminterCsv->value;
				//wg-rallback-	
				//wg-rallback-	
				//wg-rallback-	// Encabezados
				//wg-rallback-	$headers = [
				//wg-rallback-		"Usuario",	
				//wg-rallback-		"Cod. Cliente",
				//wg-rallback-		"F.R. Cliente",	
				//wg-rallback-		"Cliente",
				//wg-rallback-		"Telefono",		
				//wg-rallback-		"Estado Civil",
				//wg-rallback-		"Categoria",
				//wg-rallback-		"Identificacion",
				//wg-rallback-		"Sexo",
				//wg-rallback-		"Direccion",
				//wg-rallback-		"Cod. Desembolso",
				//wg-rallback-		"Plazo",
				//wg-rallback-		"Interes",				
				//wg-rallback-		"F. Desembolso",
				//wg-rallback-		"F. Ultimo abono",
				//wg-rallback-		"Estado Cliente",	
				//wg-rallback-		"Estado Desembolso",
				//wg-rallback-		"Frecuencia de pago",
				//wg-rallback-		"Desembolso",
				//wg-rallback-		"Desembolso + Interes",
				//wg-rallback-		"Monto Pagado",
				//wg-rallback-		"Avance",
				//wg-rallback-		"Saldo"
				//wg-rallback-	];
				//wg-rallback-
				//wg-rallback-	// Mapping de las llaves del dataset
				//wg-rallback-	$mapping = [
				//wg-rallback-		"nickname",	
				//wg-rallback-		"customerNumber",
				//wg-rallback-		"customerCreatedOn",
				//wg-rallback-		"customerName",
				//wg-rallback-		"phoneNumber",	
				//wg-rallback-		"statusCivil",
				//wg-rallback-		"comercialName",
				//wg-rallback-		"identification",
				//wg-rallback-		"sexo",
				//wg-rallback-		"location",
				//wg-rallback-		"documentNumber",
				//wg-rallback-		"term",
				//wg-rallback-		"interes",	
				//wg-rallback-		"dateDocument",
				//wg-rallback-		"dateLastShareDocument",
				//wg-rallback-		"statusCustomer",
				//wg-rallback-		"statusName",
				//wg-rallback-		"periodPay",	
				//wg-rallback-		"amountDocument",
				//wg-rallback-		"deudaTotal",
				//wg-rallback-		"montoPagado",	
				//wg-rallback-		"avance",
				//wg-rallback-		"saldo"	
				//wg-rallback-	];
				//wg-rallback-
				//wg-rallback-	// Generar CSV
				//wg-rallback-	$csv = helper_generarCSV($headers, $mapping, $objDataResult["objDetail"], ",");
				//wg-rallback-
				//wg-rallback-	// Si quieres descargarlo:
				//wg-rallback-	header("Content-Type: text/csv");
				//wg-rallback-	header("Content-Disposition: attachment; filename=reporte.csv");
				//wg-rallback-	echo $csv;
				//wg-rallback-	exit;
				//wg-rallback-
				//wg-rallback-
				//wg-rallback-
				//wg-rallback-
				//wg-rallback-	//-wg-//Descargar Datos
				//wg-rallback-	//-wg-//file name 
				//wg-rallback-	//-wg-$filename = 'app_collection_report_documents_credit_'.date('Ymd').'.csv'; 
				//wg-rallback-	//-wg-header("Content-Description: File Transfer"); 
				//wg-rallback-	//-wg-header("Content-Disposition: attachment; filename=$filename"); 
				//wg-rallback-	//-wg-header("Content-Type: application/csv; ");
				//wg-rallback-	//-wg-
				//wg-rallback-	//-wg-
				//wg-rallback-	//-wg-// file creation 
				//wg-rallback-	//-wg-$file 	= fopen('php://output', 'w');
				//wg-rallback-	//-wg-$header = array(
				//wg-rallback-	//-wg-	"Compra"			,
				//wg-rallback-	//-wg-	"Fecha"				,
				//wg-rallback-	//-wg-	"Moneda"			,
				//wg-rallback-	//-wg-	"Proveedor"			,
				//wg-rallback-	//-wg-	"Bodega"			,
				//wg-rallback-	//-wg-	"Producto"			,
				//wg-rallback-	//-wg-	"Descripcion"		,
				//wg-rallback-	//-wg-	"Cantidad"			,
				//wg-rallback-	//-wg-	"Costo Unitario"	,
				//wg-rallback-	//-wg-	"Costo Total"
				//wg-rallback-	//-wg-); 
				//wg-rallback-	//-wg-
				//wg-rallback-	//-wg-//Agregar cabecera
				//wg-rallback-	//-wg-fputcsv($file, $header,$objParameterDeliminterCsv);
				//wg-rallback-	//-wg-
				//wg-rallback-	//-wg-//Agregar fila
				//wg-rallback-	//-wg-foreach ($objDataResult["objDetail"] as $key=>$line){
				//wg-rallback-	//-wg-		$row 		= array();
				//wg-rallback-	//-wg-		$row[0]		= $line["transactionNumber"];
				//wg-rallback-	//-wg-		$row[1]		= $line["createdOn"];
				//wg-rallback-	//-wg-		$row[2]		= $line["currencyName"];
				//wg-rallback-	//-wg-		$row[3]		= $line["providerName"];
				//wg-rallback-	//-wg-		$row[4]		= $line["warehouseName"];
				//wg-rallback-	//-wg-		$row[5]		= "'".$line["itemNumber"];
				//wg-rallback-	//-wg-		$row[6]		= $line["itemName"];
				//wg-rallback-	//-wg-		$row[7]		= $line["quantity"];
				//wg-rallback-	//-wg-		$row[8]		= $line["unitaryCost"];
				//wg-rallback-	//-wg-		$row[9]		= $line["cost"];
				//wg-rallback-	//-wg-		fputcsv($file,$row,$objParameterDeliminterCsv); 
				//wg-rallback-	//-wg-}
				//wg-rallback-	//-wg-
				//wg-rallback-	//-wg-//retonar
				//wg-rallback-	//-wg-fclose($file); 
				//wg-rallback-	//-wg-exit; 
				//wg-rallback-	
				//wg-rallback-}
				
			}
		}
		catch(\Exception $ex)
		{
			//if (empty($dataSession)) {
			//	return redirect()->to(base_url("core_acount/login"));
			//}
			//
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}
	}
	
	function sumary_credit(){
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
			
			
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
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
			
			//Cargar Libreria
			if(!($viewReport && $startOn && $endOn )){
				
				//Obtener lista de usuarios
				$objListaUsuarios 				= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataView["objListaUsuarios"] 	= $objListaUsuarios;
				
				
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_collection_report/sumary_credit/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_collection_report/sumary_credit/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_collection_report/sumary_credit/view_script');//--finview
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
				$query			= "CALL pr_collection_get_report_summary_credit(?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$endOn]
				);			
				

				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"] 					= $startOn;
				$objDataResult["endOn"] 					= $endOn;
				$objDataResult["fontSize"]					= "smaller";
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxc_get_report_customer_credit" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_collection_report/sumary_credit/view_a_disemp",$objDataResult);//--finview-r
				
				
			}
		}
		catch(\Exception $ex)
		{
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