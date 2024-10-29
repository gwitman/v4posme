<?php
//posme:2023-02-27
namespace App\Controllers;
class app_purchase_report extends _BaseController {
	
   
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
			$dataSession["head"]			= /*--inicio view*/ view('app_purchase_report/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_purchase_report/view_body',$dataMenu);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_purchase_report/view_script');//--finview
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
	
	
	function purchase_detail(){
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
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri				
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri				
			$providerID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"providerID");//--finuri				
				
				
			if(!($viewReport && $startOn && $endOn  )){
				
				//Obtener Lita de proveedores
				$data["objListProvider"] 	= $this->Provider_Model->get_rowByCompany($companyID);
				
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_purchase_report/purchase_detail/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_purchase_report/purchase_detail/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_purchase_report/purchase_detail/view_script');//--finview
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
				$query			= "CALL pr_purchase_get_report_purchase_detail(?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$providerID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_purchase_report/purchase_detail/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	function purchase_taller()
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
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$employerListID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"employerIDFilter");//--finuri			
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			
			//Cargar Libreria
			$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
			$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
			$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
			$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
			
				
			if( !($viewReport) )
			{
				//Obtener lista de usuarios
				$objListaEmployer 				= $this->Employee_Model->get_rowByCompanyID($dataSession["user"]->companyID);
				$dataView["objListaEmployer"] 	= $objListaEmployer;
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_purchase_report/purchase_taller/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_purchase_report/purchase_taller/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_purchase_report/purchase_taller/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
				
			}
			else
			{
				
				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_purchase_get_report_purchase_taller(?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$employerListID, $startOn,$endOn." 23:59:59" ]
				);			
				
				if(isset($objData))
				{
					$objDataResult["objDetail"]					= $objData;					
					$objDataResult["objListItem"] 				= array_unique(array_map(function($elem){
																	return $elem["Estado"];
																}, $objDataResult["objDetail"] ));
					
					
				
				}
				else
				{
					$objDataResult["objDetail"]					= NULL;
					$objDataResult["objListItem"] 				= NULL;
				}
			
			
				//Obtener lista de colaboradores
				$objDataResult["objListEmployeer"]			= $this->Employee_Model->get_rowByCompanyID($dataSession["user"]->companyID);
				
				
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objParameterTamanoLetra"] 	= $objParameterTamanoLetra;	
				$objDataResult["objParameterAltoDeLaFila"] 	= $objParameterAltoDeLaFila;	
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;				
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_purchase_get_report_purchase_taller" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				$objDataResult["employerListID"]			= $employerListID;
				$objDataResult["typeCompay"]				= $objCompany->type;
				$objDataResult["flavorIDCompay"]			= $objCompany->flavorID;
				
				return view("app_purchase_report/purchase_taller/view_a_disemp",$objDataResult);//--finview-r
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