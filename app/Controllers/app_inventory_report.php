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
									= $this->core_web_menu->render_menu_body_report($dataSession["menuBodyReport"],$parentMenuElementID);
									
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
			exit($ex->getMessage());
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
			exit($ex->getMessage());
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
			exit($ex->getMessage());
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
				
				return view("app_inventory_report/master_kardex/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$query			= "CALL pr_inventory_get_report_list_item(?,?,?);";
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
			
			if($objCompany->type == "default")
				return view("app_inventory_report/list_item/view_a_disemp",$objDataResult);//--finview-r
			else 
				return view("app_inventory_report/list_item/view_a_disemp_".$objCompany->type,$objDataResult);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
				
				return view("app_inventory_report/list_item_by_warehouse/view_a_disemp",$objDataResult);//--finview-r
			}
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			exit($ex->getMessage());
		}
	}
}
?>