<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxp_report extends _BaseController {
	
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
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_report/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_report/view_body',$dataMenu);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_report/view_script');//--finview
			$dataSession["footer"]			= "";			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}

	function expenses(){
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
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$txtTiposID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"txtTiposID");//--finuri
			$txtCategoriaID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"txtCategoriaID");//--finuri			

			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCatalogoTipoGastos"]				= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_accounting_expenses","priorityID",$companyID);//--Tipo de Gastos			
				$dataSession["objListCatalogoCategoriaGastos"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_accounting_expenses","areaID",$companyID);//--Categoria de Gastos


				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_cxp_report/expenses/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_cxp_report/expenses/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_cxp_report/expenses/view_script');//--finview
				$dataSession["footer"]					= "";			
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
				$query			= "CALL pr_cxp_get_report_expenses_detail(?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn]
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
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxp_get_report_expenses_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_cxp_report/expenses/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}

	function expenses_summary(){
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
				
				
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_cxp_report/expenses_summary/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_cxp_report/expenses_summary/view_body');//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_cxp_report/expenses_summary/view_script');//--finview
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
				$query			= "CALL pr_cxp_get_report_expenses_summary_pivot(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,0,"-1"]
				);			

				
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_cxp_report/expenses_summary/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
	function expenses_chart(){
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
				
				
			$objFechaInicial 	= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  				
			$objFechaFinal 		= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  				
			$startOn			= !$startOn ? $objFechaInicial->format("Y-m-d"): $startOn;
			$endOn				= !$endOn ? $objFechaFinal->format("Y-m-d"): $endOn;
			$objFechaInicial 	= \DateTime::createFromFormat('Y-m-d',$startOn);  				
			$objFechaFinal 	= \DateTime::createFromFormat('Y-m-d',$endOn);  				
			
			
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_cxp_get_report_expenses_summary_pivot(?,?,?,?,?);";				
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$companyID,$tocken,$userID,$startOn,$endOn]
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
			
			//Renderizar Resultado 
			$objDataResult["message"]		= $this->core_web_notification->get_message();
			$objDataResult["head"]			= /*--inicio view*/ view('app_cxp_report/expenses_chart/view_head',$objDataResult);//--finview
			$objDataResult["body"]			= /*--inicio view*/ view('app_cxp_report/expenses_chart/view_body',$objDataResult);//--finview
			$objDataResult["script"]		= /*--inicio view*/ view('app_cxp_report/expenses_chart/view_script',$objDataResult);//--finview
			$objDataResult["footer"]		= "";			
			return view("core_masterpage/default_report",$objDataResult);//--finview-r	
			
		
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
}
?>