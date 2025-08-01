<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_report extends _BaseController {
	
   
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
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_report/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_report/view_body',$dataMenu);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_report/view_script');//--finview
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
	function movement_by_warehouse(){
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
			$warehouseID		= false;			
			$itemID 			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri	
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri				
			$itemID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri
				
				
			if(!($viewReport && $startOn && $endOn && $warehouseID && $itemID )){
				
				$data["objListWarehouse"]	= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);				
				$data["objListItem"]		= $this->Item_Model->get_rowByCompany($companyID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/movement_by_warehouse/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/movement_by_warehouse/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/movement_by_warehouse/view_script');//--finview
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
				$query			= "CALL pr_inventory_get_report_auxiliar_mov_by_warehouse(?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$warehouseID,$startOn,$endOn,$itemID]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"]					= $startOn;
				$objDataResult["endOn"]						= $endOn;
				$objDataResult["objItem"] 					= $this->Item_Model->get_rowByPK($companyID,$itemID);
				$objDataResult["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_inventory_report/movement_by_warehouse/view_a_disemp",$objDataResult);//--finview-r
				
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
	function movement(){
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
			$itemID 			= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri	
			$itemID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri					
			
				
				
			if(!($viewReport && $startOn && $endOn && $itemID )){
				
				$data["objListItem"]		= $this->Item_Model->get_rowByCompany($companyID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/movement/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/movement/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/movement/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				$objListWarehouse 	= $this->Itemwarehouse_Model->get_rowByItemID($companyID,$itemID);
				if($objListWarehouse)
				{
					foreach($objListWarehouse as $objWarehouse)
					{
						$query			= "CALL pr_zerror_reparar_kardex(?,?);";
						$objData		= $this->Bd_Model->executeRender(
							$query,
							[$itemID,$objWarehouse->warehouseID]
						);
					}
				}				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_inventory_get_report_auxiliar_mov_by_allwarehouse(?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$startOn,$endOn,$itemID]
				);			
				
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"]					= $startOn;
				$objDataResult["endOn"]						= $endOn;
				$objDataResult["objItem"] 					= $this->Item_Model->get_rowByPK($companyID,$itemID);				
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_inventory_report/movement/view_a_disemp",$objDataResult);//--finview-r
				
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
	function master_kardex(){
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
			$warehouseID		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri	
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri
			$horaInicial		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"horaInicial");//--finuri
			$horaFinal			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"horaFinal");//--finuri
			
				
				
			if(!($viewReport && $startOn && $endOn  )){
				$data["objListWarehouse"]	= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/master_kardex/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/master_kardex/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/master_kardex/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				$startOn 		= $startOn." ".$horaInicial;
				$endOn 			= $endOn." ".$horaFinal;
				
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_inventory_get_report_master_kardex(?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$warehouseID,$startOn,$endOn]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
				$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
				$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
				$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
				$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
			
				$objDataResult["objParameterTamanoLetra"] 				= $objParameterTamanoLetra;
				$objDataResult["objParameterAltoDeLaFila"] 				= $objParameterAltoDeLaFila;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"]					= $startOn;
				$objDataResult["endOn"]						= $endOn;
				$objDataResult["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				$objPermissionNotMostrarCosto 					= array_filter($dataSession["menuHiddenPopup"], function($val){  return $val->display == 'NO_ES_PERMITIDO_MOSTRAR_COSTOS'; });
				$objPermissionNotMostrarCosto 					= count($objPermissionNotMostrarCosto) > 0 ? "true" : "false";				
				$objPermissionNotMostrarCosto					= $dataSession["role"]->isAdmin ? "false" : $objPermissionNotMostrarCosto;				
				$objDataResult["objPermissionNotMostrarCosto"] 	= $objPermissionNotMostrarCosto;
				
				if($objCompany->type == "casetafreddy")
				return view("app_inventory_report/master_kardex/view_a_disemp_caseta_freddy",$objDataResult);//--finview-r
				else
				return view("app_inventory_report/master_kardex/view_a_disemp",$objDataResult);//--finview-r
				
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
	function list_item(){
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
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri				
			$categoryID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"categoryID");//--finuri				
			$tocken				= '';
			 
			
			//Cargar Libreria
			if($warehouseID == "")
			{
				$data["objListWarehouse"]	= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);			
				$data["objListCategory"]	= $this->Itemcategory_Model->getByCompany($companyID);			
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/list_item/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/list_item/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/list_item/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else
			{
				$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
				$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
				$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
				$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
				
					
					
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_inventory_get_report_list_item(?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$warehouseID,$categoryID]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objParameterTamanoLetra"] 	= $objParameterTamanoLetra;	
				$objDataResult["objParameterAltoDeLaFila"] 	= $objParameterAltoDeLaFila;				
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);

				$objPermissionNotMostrarCosto 					= array_filter($dataSession["menuHiddenPopup"], function($val){  return $val->display == 'NO_ES_PERMITIDO_MOSTRAR_COSTOS'; });
				$objPermissionNotMostrarCosto 					= count($objPermissionNotMostrarCosto) > 0 ? "true" : "false";				
				$objPermissionNotMostrarCosto					= $dataSession["role"]->isAdmin ? "false" : $objPermissionNotMostrarCosto;				
				$objDataResult["objPermissionNotMostrarCosto"] 	= $objPermissionNotMostrarCosto;
				
				
				//Revisar si existe la vista			
				if($objCompany->type == "globalpro")
				{
					return view("app_inventory_report/list_item/view_a_disemp_globalpro",$objDataResult);//--finview-r
				}
				else if($objCompany->type == "agencia_freddy")
				{
					return view("app_inventory_report/list_item/view_a_disemp_agencia_freddy",$objDataResult);//--finview-r
				}
				else if($objCompany->type == "ainaracloset")
				{
					return view("app_inventory_report/list_item/view_a_disemp_ainaracloset",$objDataResult);//--finview-r
				}
				else if($objCompany->type == "agro_el_labrador")
				{
					return view("app_inventory_report/list_item/view_a_disemp_agro_el_labrador",$objDataResult);//--finview-r
				}
				else if($objCompany->type == "galmcuts")
				{
					return view("app_inventory_report/list_item/view_a_disemp_galmcuts",$objDataResult);//--finview-r
				}
				else if($objCompany->type == "farma_ley")
				{
					return view("app_inventory_report/list_item/view_a_disemp_farma_ley",$objDataResult);//--finview-r
				}
				else if ($objCompany->type == "elektro")
				{
					return view("app_inventory_report/list_item/view_a_disemp_elektro",$objDataResult);//--finview-r
				}	
				else if ($objCompany->type == "frozen_market")
				{
					return view("app_inventory_report/list_item/view_a_disemp_frozen_market",$objDataResult);//--finview-r
				}
				else if ($objCompany->type == "moto_partes")
				{
					return view("app_inventory_report/list_item/view_a_disemp_moto_partes",$objDataResult);//--finview-r
				}
				else
				{
					return view("app_inventory_report/list_item/view_a_disemp",$objDataResult);//--finview-r
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
	
	function list_item_out_exist(){
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
			$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
			$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
			$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
			$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
			
				
				
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_inventory_get_report_list_item_out_exists(?,?,?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID]
			);			
			
			if(isset($objData))
			$objDataResult["objDetail"]					= $objData;
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objParameterTamanoLetra"] 	= $objParameterTamanoLetra;	
			$objDataResult["objParameterAltoDeLaFila"] 	= $objParameterAltoDeLaFila;				
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			
			
			
			if( /*$objCompany->type == "default" */ true )
				return view("app_inventory_report/list_item_out_exist/view_a_disemp",$objDataResult);//--finview-r
			else 
				return view("app_inventory_report/list_item_out_exist/view_a_disemp_".$objCompany->type,$objDataResult);//--finview-r
			
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
	function list_item_width_exist(){
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
			$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
			$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
			$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
			$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
			
				
				
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_inventory_get_report_list_item_width_exists(?,?,?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID]
			);			
			
			if(isset($objData))
			$objDataResult["objDetail"]					= $objData;
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objParameterTamanoLetra"] 	= $objParameterTamanoLetra;	
			$objDataResult["objParameterAltoDeLaFila"] 	= $objParameterAltoDeLaFila;				
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			
			
			
			if( /*$objCompany->type == "default" */ true )
				return view("app_inventory_report/list_item_width_exist/view_a_disemp",$objDataResult);//--finview-r
			else 
				return view("app_inventory_report/list_item_width_exist/view_a_disemp_".$objCompany->type,$objDataResult);//--finview-r
			
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
	function list_item_by_warehouse(){
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
			$warehouseListID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseIDFilter");//--finuri			
			
			//Cargar Libreria
			$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
			$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
			$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
			$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
			
				
			if( !($viewReport) )
			{
				//Obtener lista de usuarios
				$objListaWarehouse 				= $this->Warehouse_Model->getByCompany($dataSession["user"]->companyID);
				$dataView["objListaWarehouse"] 	= $objListaWarehouse;
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/list_item_by_warehouse/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/list_item_by_warehouse/view_body',$dataView);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/list_item_by_warehouse/view_script');//--finview
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
				$query			= "CALL pr_inventory_get_report_list_item_by_warehouse(?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$warehouseListID]
				);			
				
				if(isset($objData))
				{
					$objDataResult["objDetail"]					= $objData;					
					$objDataResult["objListItem"] 				= array_unique(array_map(function($elem){
																	return $elem["itemNumber"];
																}, $objDataResult["objDetail"] ));
					
					
				
				}
				else
				{
					$objDataResult["objDetail"]					= NULL;
					$objDataResult["objListItem"] 				= NULL;
				}
			
			
				//Obtener lista de bodegas
				$objDataResult["objListWarehouse"]			= $this->Warehouse_Model->getByCompany($companyID);
				
				

				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objParameterTamanoLetra"] 	= $objParameterTamanoLetra;	
				$objDataResult["objParameterAltoDeLaFila"] 	= $objParameterAltoDeLaFila;				
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item_by_warehouse" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				$objDataResult["warehouseListID"]			= $warehouseListID;
				$objDataResult["typeCompay"]				= $objCompany->type;
				$objDataResult["flavorIDCompay"]			= $objCompany->flavorID;
				
				if($objCompany->type == "chic")
				return view("app_inventory_report/list_item_by_warehouse/view_a_disemp_chic",$objDataResult);//--finview-r
				else 
				return view("app_inventory_report/list_item_by_warehouse/view_a_disemp",$objDataResult);//--finview-r
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
	function list_item_expired(){
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
			$query			= "CALL pr_inventory_get_eport_list_item_expired(?,?,?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID]
			);			
			
			
			if(isset($objData))
			$objDataResult["objDetail"]					= $objData;
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			
			return view("app_inventory_report/list_item_expired/view_a_disemp",$objDataResult);//--finview-r
			
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
	
	function list_item_real_estate(){
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
			$showActivos			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"showActivos");//--finuri
			$warehouseID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri
			$typeDate				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"typeDate");//--finuri
			$namePropietario		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"namePropietario");//--finuri
			$numberEncuentra24		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"numberEncuentra24");//--finuri
			$namePropietario		= $namePropietario == "numberEncuentra24" ? "" : $namePropietario;
			$numberEncuentra24		= $numberEncuentra24 == "numberEncuentra24" ? "" : $numberEncuentra24;
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_inventory_report/list_item_real_estate/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_inventory_report/list_item_real_estate/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_inventory_report/list_item_real_estate/view_script');//--finview
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
				$query			= "
									select 
										x.`itemID`,
										x.`createdOn`
										,x.`Codigo`
										,x.`Nombre`
										,x.`Pagina Web`
										,x.`Amueblado`
										,x.`Aires`
										,x.`Niveles`
										,x.`Hora de visita`
										,x.`Baños`
										,x.`Habitaciones`
										,x.`Diseño de propiedad`
										,x.`Tipo de casa`
										,x.`Proposito`
										,x.`Moneda`
										,x.`Fecha de enlistamiento`
										,x.`Fecha de actualizacion`
										,round(x.`Precio Venta`,2) as `Precio Venta`
										,round(x.`Precio Renta`,2) as `Precio Renta`
										,x.`Disponible`
										,x.`Area de contruccion M2`
										,x.`Area de terreno V2`
										,x.`ID Encuentra 24`
										,x.`Baño de servicio`
										,x.`Baño de visita`
										,x.`Cuarto de servicio`
										,x.`Walk in closet`
										,x.`Piscina privada`
										,x.`Area club con piscina`
										,x.`Acepta mascota`
										,x.`Corretaje`
										,x.`Plan de referido`
										,x.`Link Youtube`
										,x.`Pagina Web Link`
										,x.`Foto`
										,x.`Google`
										,x.`Otros Link`
										,x.`Estilo de cocina`
										,x.`Agente`
										,x.`Zona`
										,x.`Condominio`
										,x.`Ubicacion`
										,x.`Exclusividad de agente`
										,x.`Telefono`
										
										
										
										/*
										,x.`Pais`
										,x.`Estado`
										,x.`Ciudad`
										*/
										 
									from 
										vw_inventory_list_item_real_estate x 
									where 
										(
											'".$namePropietario."' != 'none' and  											
											x.`Nombre` like '%".$namePropietario."%'  
										) or
										(
											
											'".$numberEncuentra24."' != 'none' and 
											x.`ID Encuentra 24` like '%".$numberEncuentra24."%'  
										) or 
										(
											[[TIPO_FILTRO_FECHA]] 
											and
											(
											  '".$namePropietario."' = 'none' and  
											  '".$numberEncuentra24."' = 'none' 
											)											
										) 
										AND 
										x.isActive = ".$showActivos."   
										
								";
								
				if($typeDate == "actualizacion")
				{
					$query	= str_replace("[[TIPO_FILTRO_FECHA]]", " x.`Fecha de actualizacion` BETWEEN ? and ? ", $query);
				}
				if($typeDate == "enlistamiento")
				{
					$query	= str_replace("[[TIPO_FILTRO_FECHA]]", " x.`Fecha de enlistamiento` BETWEEN ? and ? ", $query);
				}
				
								
				
				log_message("error",$query);
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$startOn,$endOn]
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
				
				return view("app_inventory_report/list_item_real_estate/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function printer_stiker_sin_precio($listItem="")
	{
		
		$listItem = helper_SegmentsByIndex($this->uri->getSegments(),1,$listItem);	
		
		
		try{
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			$listItem					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"listItem");//--finuri
			$listItem 					= urldecode($listItem);		
			
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Cargar Libreria		
			$listItem			= explode("|",$listItem);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);
			
			$objTypePriceValue					= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE",$companyID);
			$objTypePriceValue					= $objTypePriceValue->value;
			
			$objTypeListPriceValue				= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$objTypeListPriceValue				= $objTypeListPriceValue->value;
			
			
			//Actualizar lso codigos de barra
			$objListaItemPrinter = array();			
			$itemID 			 = 0;			
			foreach($listItem as $itemWitCantidad)
			{
				
				$itemWitCantidadTmp	= explode("-",$itemWitCantidad);				
				$itemID 	= $itemWitCantidadTmp[0];
				$cantidad 	= $itemWitCantidadTmp[1];			
				//Obtener Lista de Productos	
				$objItem 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
					
				if($objItem == null)
				{}		
				else{	
					for($i = 0; $i < $cantidad ; $i++){
						$objItemTempory 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
						$objItemTempory->itemPrice 	= $this->Price_Model->get_rowByPK($companyID, $objTypeListPriceValue, $itemID, $objTypePriceValue /*Precio publico*/)->price;
						array_push($objListaItemPrinter,$objItemTempory);
					}
				}
			}
					
					
				
		
			
			//Generar Reporte
			$dataView["objListaItem"] 	= $objListaItemPrinter;
			$html = helper_reporteItemStickerSinPrecio(
			    "STICKER",
			    $objCompany,
				$dataView
			);
			
			$this->dompdf->loadHTML($html);
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "sticker_".$itemID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_33/component_item_".$itemID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_33/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download compra</a>
				"; 				
			
			}
			else{			
				//visualizar
				$this->dompdf->stream("file".date("dmYhis").".pdf ", ['Attachment' =>  true ]);
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
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function printer_stiker_con_precio($listItem="")
	{
		
		$listItem = helper_SegmentsByIndex($this->uri->getSegments(),1,$listItem);	
		
		
		try{
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			$listItem					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"listItem");//--finuri
			$listItem 					= urldecode($listItem);		
			
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Cargar Libreria		
			$listItem			= explode("|",$listItem);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);
			
			$objTypePriceValue					= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE",$companyID);
			$objTypePriceValue					= $objTypePriceValue->value;
			
			$objTypeListPriceValue				= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$objTypeListPriceValue				= $objTypeListPriceValue->value;
			
			
			//Actualizar lso codigos de barra
			$objListaItemPrinter = array();			
			$itemID 			 = 0;			
			foreach($listItem as $itemWitCantidad)
			{
				
				$itemWitCantidadTmp	= explode("-",$itemWitCantidad);				
				$itemID 	= $itemWitCantidadTmp[0];
				$cantidad 	= $itemWitCantidadTmp[1];			
				//Obtener Lista de Productos	
				$objItem 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
					
				if($objItem == null)
				{}		
				else{	
					for($i = 0; $i < $cantidad ; $i++){
						$objItemTempory 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
						$objItemTempory->itemPrice 	= $this->Price_Model->get_rowByPK($companyID, $objTypeListPriceValue, $itemID, $objTypePriceValue /*Precio publico*/)->price;
						array_push($objListaItemPrinter,$objItemTempory);
					}
				}
			}
					
					
				
		
			
			//Generar Reporte
			$dataView["objListaItem"] 	= $objListaItemPrinter;
			$html = helper_reporteItemStickerConPrecio(
			    "STICKER",
			    $objCompany,
				$dataView
			);
			
			$this->dompdf->loadHTML($html);
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "sticker_".$itemID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_33/component_item_".$itemID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_33/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download compra</a>
				"; 				
			
			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true ]);
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
	function purchase(){
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
			$warehouseID		= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri	
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri
			$horaInicial		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"horaInicial");//--finuri
			$horaFinal			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"horaFinal");//--finuri
			$entityIDProviderID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"providerID");//--finuri
			
				
				
			if(!($viewReport && $startOn && $endOn  )){
				$data["objListWarehouse"]	= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$data["objListProvider"]	= $this->Provider_Model->get_rowByCompany($companyID);
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/purchase/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/purchase/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/purchase/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				$startOn 		= $startOn." ".$horaInicial;
				$endOn 			= $endOn." ".$horaFinal;
				
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_inventory_get_report_purchase(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$warehouseID,$startOn,$endOn,$entityIDProviderID]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
			
				
			
				$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
				$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
				$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
				$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
			
				$objDataResult["objParameterTamanoLetra"] 				= $objParameterTamanoLetra;
				$objDataResult["objParameterAltoDeLaFila"] 				= $objParameterAltoDeLaFila;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["startOn"]					= $startOn;
				$objDataResult["endOn"]						= $endOn;
				$objDataResult["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				$objPermissionNotMostrarCosto 					= array_filter($dataSession["menuHiddenPopup"], function($val){  return $val->display == 'NO_ES_PERMITIDO_MOSTRAR_COSTOS'; });
				$objPermissionNotMostrarCosto 					= count($objPermissionNotMostrarCosto) > 0 ? "true" : "false";				
				$objPermissionNotMostrarCosto					= $dataSession["role"]->isAdmin ? "false" : $objPermissionNotMostrarCosto;				
				$objDataResult["objPermissionNotMostrarCosto"] 	= $objPermissionNotMostrarCosto;
				
				
				if(count($objDataResult["objDetail"]) <= 1500 )
				{
					return view("app_inventory_report/purchase/view_a_disemp",$objDataResult);//--finview-r
				}
				else
				{
					$objParameterDeliminterCsv	 = $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
					$objParameterDeliminterCsv	 = $objParameterDeliminterCsv->value;
					
					//Descargar Datos
					//file name 
					$filename = 'purchase_'.date('Ymd').'.csv'; 
					header("Content-Description: File Transfer"); 
					header("Content-Disposition: attachment; filename=$filename"); 
					header("Content-Type: application/csv; ");
					
		   
					// file creation 
					$file 	= fopen('php://output', 'w');
					$header = array(
						"Compra"			,
						"Fecha"				,
						"Moneda"			,
						"Proveedor"			,
						"Bodega"			,
						"Producto"			,
						"Descripcion"		,
						"Cantidad"			,
						"Costo Unitario"	,
						"Costo Total"
					); 
					
					//Agregar cabecera
					fputcsv($file, $header,$objParameterDeliminterCsv);
					
					//Agregar fila
					foreach ($objDataResult["objDetail"] as $key=>$line){
							$row 		= array();
							$row[0]		= $line["transactionNumber"];
							$row[1]		= $line["createdOn"];
							$row[2]		= $line["currencyName"];
							$row[3]		= $line["providerName"];
							$row[4]		= $line["warehouseName"];
							$row[5]		= "'".$line["itemNumber"];
							$row[6]		= $line["itemName"];
							$row[7]		= $line["quantity"];
							$row[8]		= $line["unitaryCost"];
							$row[9]		= $line["cost"];
							fputcsv($file,$row,$objParameterDeliminterCsv); 
					}
					
					//retonar
					fclose($file); 
					exit; 
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
	
	function list_item_inventory(){
		
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
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri				
			$categoryID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"categoryID");//--finuri				
			$tocken				= '';
			 
			
			//Cargar Libreria
			if($warehouseID == "")
			{
				$data["objListWarehouse"]	= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);			
				$data["objListCategory"]	= $this->Itemcategory_Model->getByCompany($companyID);			
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/list_item_inventory/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/list_item_inventory/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/list_item_inventory/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else
			{
				$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
				$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
				$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
				$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
				
					
					
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_inventory_get_report_list_item(?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$warehouseID,$categoryID]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]					= $objData;
				else
				$objDataResult["objDetail"]					= NULL;
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objParameterTamanoLetra"] 	= $objParameterTamanoLetra;	
				$objDataResult["objParameterAltoDeLaFila"] 	= $objParameterAltoDeLaFila;				
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);

				$objPermissionNotMostrarCosto 					= array_filter($dataSession["menuHiddenPopup"], function($val){  return $val->display == 'NO_ES_PERMITIDO_MOSTRAR_COSTOS'; });
				$objPermissionNotMostrarCosto 					= count($objPermissionNotMostrarCosto) > 0 ? "true" : "false";				
				$objPermissionNotMostrarCosto					= $dataSession["role"]->isAdmin ? "false" : $objPermissionNotMostrarCosto;				
				$objDataResult["objPermissionNotMostrarCosto"] 	= $objPermissionNotMostrarCosto;
								
				//Revisar si existe la vista			
				return view("app_inventory_report/list_item_inventory/view_a_disemp",$objDataResult);//--finview-r	
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
	function list_info_item(){
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
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri				
			$categoryID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"categoryID");//--finuri			

			$tocken				= '';
			 			
			//Cargar Libreria
			if($warehouseID == "")
			{
				$data["objListWarehouse"]	= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);			
				$data["objListCategory"]	= $this->Itemcategory_Model->getByCompany($companyID);			
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_inventory_report/list_info_item/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_inventory_report/list_info_item/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_inventory_report/list_info_item/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
				
			}
			else
			{
				$objParameterTamanoLetra	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_SIZE_LATTER",$companyID);
				$objParameterTamanoLetra	= $objParameterTamanoLetra->value;	
				$objParameterAltoDeLaFila	= $this->core_web_parameter->getParameter("CORE_VIEW_CUSTOM_REPORT_IN_LIST_ITEM_ALTO_FILA",$companyID);
				$objParameterAltoDeLaFila	= $objParameterAltoDeLaFila->value;				
														
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_inventory_get_report_list_item(?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$userID,$tocken,$companyID,$warehouseID,$categoryID]
				);			
																
				
				if(isset($objData))
					$objDataResult["objDetail"]					= $objData;
				else
					$objDataResult["objDetail"]					= NULL;
					$objDataResult["objCompany"] 				= $objCompany;
					$objDataResult["objLogo"] 					= $objParameter;
					$objDataResult["objParameterTamanoLetra"] 	= $objParameterTamanoLetra;	
					$objDataResult["objParameterAltoDeLaFila"] 	= $objParameterAltoDeLaFila;				
					$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_inventory_get_report_list_item" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
					$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);

					$objPermissionNotMostrarCosto 					= array_filter($dataSession["menuHiddenPopup"], function($val){  return $val->display == 'NO_ES_PERMITIDO_MOSTRAR_COSTOS'; });
					$objPermissionNotMostrarCosto 					= count($objPermissionNotMostrarCosto) > 0 ? "true" : "false";				
					$objPermissionNotMostrarCosto					= $dataSession["role"]->isAdmin ? "false" : $objPermissionNotMostrarCosto;				
					$objDataResult["objPermissionNotMostrarCosto"] 	= $objPermissionNotMostrarCosto;

					return view("app_inventory_report/list_info_item/view_a_disemp",$objDataResult);//--finview-r									
					
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


	function download_report_info_producto(){
		try{ 					
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}									
						
			$companyID			= $dataSession["user"]->companyID;			
			$userID				= $dataSession["user"]->userID;
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri				
			$categoryID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"categoryID");//--finuri					
			$tocken				= "";
						
			$query			= "CALL pr_inventory_get_report_list_item(?,?,?,?,?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$userID,$tocken,$companyID,$warehouseID,$categoryID]
			);	

			if(isset($objData)){
						
				$resultados2	= array();
				foreach($objData as $key => $value){
					$resultados2[$key]	= array_values($value);
				}

				$objParameterDeliminterCsv	 = $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				$objParameterDeliminterCsv	 = $objParameterDeliminterCsv->value;	

				//Descargar Datos
				//file name 
				$filename = 'user_'.date('Ymd').'.csv'; 
				header("Content-Description: File Transfer"); 
				header("Content-Disposition: attachment; filename=$filename"); 
				header("Content-Type: application/csv; ");
						
				// file creation 
				$file 	= fopen('php://output', 'w');
						
				if($dataSession["company"]->type == "farma_ley" ){
					//si es farma ley se personaliza sus campos indicados.
					$header = [
					1 => "barcode",	
					0 => "sku",
					2 => "itemname",
					7 => "price",
					5 => "quantity",							
					15 => "vendors",							
					14 => "active",						
					];
				}
				else
				{
					$header = [
					0 => "itemNumber",
					1 => "barCode",
					3 => "categoryName",
					2 => "itemName",
					9 => "price2",
					8 => "price",
					13 => "familyName", 
					12 => "unidadMedidaName"
					];
				}	
						
				//fputcsv($file, $header,separator: $objParameterDeliminterCsv);
				fputcsv($file, array_values($header), $objParameterDeliminterCsv);

				foreach ($resultados2 as $line) {
					$row = [];								
					foreach (array_keys($header) as $colIndex) {
        				$value = isset($line[$colIndex]) ? $line[$colIndex] : '';
        				// Formatear campos específicos
        				if ($colIndex == 5) { // quantity            				
							$value = (int)$value;
        				}
						if ($colIndex == 7) { // price            				
							$value = number_format($value,2,'.','');
        				}
        				if ($colIndex == 15) { // vendors            				
							$value = ''.(string)$value.'';							        				
						}
        				$row[] = $value;
    				}
					fputcsv($file, $row, $objParameterDeliminterCsv);
				}											
				fclose($file); 																	
				exit;																						 
			}			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
			
	}



}
?>