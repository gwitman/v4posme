<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_report extends _BaseController {
	
   
	function auxiliar_mov_x_tipo_de_comprobantes(){
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
			$journalTypeID		= false;	
			$excludeSystem		= 0;
			$stringContainer	= "";
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$journalTypeID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"journalTypeID");//--finuri				
			$excludeSystem		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"excludeSystem");//--finuri
			$stringContainer	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"stringContainer");//--finuri
			$classID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"classID");//--finuri
			
			
			if(!($viewReport && $startOn && $endOn && $journalTypeID )){
				
				
				$data["objListJournalType"]	= $this->core_web_catalog->getCatalogAllItem("tb_journal_entry","journalTypeID",$companyID);
				$data["objListCentroCosto"]	= $this->Center_Cost_Model->getByCompany($companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_body',$data);//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				//Cargar Libreria
					
				
				
				
				
				//Obtener el tipo de Comprobante
				$objCatalogItem = $this->Catalog_Item_Model->get_rowByCatalogItemID($journalTypeID);
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_accounting_get_report_auxiliar_mov_tipo_comprobantes(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$journalTypeID,$startOn,$endOn,$excludeSystem,$stringContainer,$classID]
				);			
				
				if(isset($objData))
				$objDataResult["objDetail"]			= $objData;
				else
				$objDataResult["objDetail"]			= $objData;
				
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["startOn"]			= $startOn;
				$objDataResult["endOn"]				= $endOn;
				$objDataResult["objTipo"]			= $objCatalogItem <> NULL ? $objCatalogItem->name : 'TODOS';
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_mov_x_tipo_de_comprobantes" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/auxiliar_mov_x_tipo_de_comprobantes/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function catalogo_de_cuentas(){
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
			
			$companyID		= $dataSession["user"]->companyID;
			$branchID		= $dataSession["user"]->branchID;
			$userID			= $dataSession["user"]->userID;			
			
			//Cargar Libreria
			
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);							
			//Get Datos
			$query			= "CALL pr_accounting_get_report_catalogo_de_cuenta(?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$companyID]
			);	
			
			$objDataResult["objDetail"] 				= $objData;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "catalogo_de_cuentas" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			
			return view("app_account_report/catalogo_de_cuenta/view_a_disemp",$objDataResult);//--finview-r		
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function auxiliar_de_cuenta(){
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
			
									
			$viewReport		= false;
			$periodID		= false;
			$cycleStartID	= false;
			$cycleEndID		= false;
			$accountID		= false;
			$companyID		= $dataSession["user"]->companyID;
			$branchID		= $dataSession["user"]->branchID;
			$userID			= $dataSession["user"]->userID;
			$tocken			= '';
			
			
			$viewReport		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$periodID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"periodID");//--finuri
			$cycleStartID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"cycleStartID");//--finuri
			$cycleEndID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"cycleEndID");//--finuri
			$accountID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"accountID");//--finuri
			
			
			if(!($viewReport && $periodID && $cycleStartID && $cycleEndID && $accountID)){
				
				
				$data["objListAccountingPeriod"] 
										= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				$data["objListAccount"] 
										= $this->Account_Model->getByCompanyOperative($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= /*--inicio view*/ view('app_account_report/auxiliar_de_cuenta/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_account_report/auxiliar_de_cuenta/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_account_report/auxiliar_de_cuenta/view_script');//--finview
				$dataSession["footer"]	= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				//Cargar Libreria
					
				
				
				
				//Obtener Ciclo Inicial y final
				$objCycleStart 	= $this->Component_Cycle_Model->get_rowByPK($periodID,$cycleStartID);
				$objCycleEnd 	= $this->Component_Cycle_Model->get_rowByPK($periodID,$cycleEndID);
				
				
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);	
				//Get Datos
				$query			= "CALL pr_accounting_get_report_auxiliar_account(?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRenderMultipleNative(
					$query,
					[$companyID,$periodID,$cycleStartID,$cycleEndID,$accountID]
				);				
				
				if(isset($objData[0])){
					$objDataResult["name"] 				= $objData[0][0]["name"];
					$objDataResult["accountNumber"] 	= $objData[0][0]["accountNumber"];
					$objDataResult["naturaleza"] 		= $objData[0][0]["naturaleza"];
					$objDataResult["money"] 			= $objData[0][0]["money"];
					$objDataResult["description"] 		= $objData[0][0]["description"];
				}
				if(isset($objData[1])){
					$objDataResult["objBalanceStart"] 	= $objData[1][0]["balanceStart"];
				}
				else
					$objDataResult["objBalanceStart"] 	= $objData;
					
				if(isset($objData[2])){
					$objDataResult["objMovement"] 		= $objData[2];
				}
				else 
					$objDataResult["objMovement"] 		= NULL;
					
				if(isset($objData[3])){
					$objDataResult["objBalanceEnd"] 	= $objData[3][0]["balanceEnd"];
				}
				else 
					$objDataResult["objBalanceEnd"] 	= $objData;
				
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objCycleStart"]		= $objCycleStart;
				$objDataResult["objCycleEnd"]		= $objCycleEnd;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "auxiliar_de_cuenta" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/auxiliar_de_cuenta/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function balance_general(){
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
			if(count($this->uri->getSegments()) > 2){
				$viewReport		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
				$periodID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"periodID");//--finuri
				$cycleID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"cycleID");//--finuri
			} 
			
			if(!($viewReport && $periodID && $cycleID)){
				
				$data["objListAccountingPeriod"] 
										= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= /*--inicio view*/ view('app_account_report/balance_general/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_account_report/balance_general/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_account_report/balance_general/view_script');//--finview
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
				$query			= "CALL pr_accounting_get_report_balance_general(?,?,?,?,?,?);";								
				$objData		= $this->Bd_Model->executeRenderMultipleNative(
					$query,
					[$companyID,$branchID,$userID,$tocken,$periodID,$cycleID]
				);	
				
				
				
				$objDataResult["objDetailActivo"]			= $objData[0];
				$objDataResult["objDetailPasivo"]			= $objData[1];
				$objDataResult["objDetailCapital"]			= $objData[2];
				$objDataResult["objTotalActivo"]			= $objData[3];
				$objDataResult["objTotalPasivoCapital"]		= $objData[4];
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balance_general" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/balance_general/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function razon_financial(){
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
				$dataSession["head"]	= /*--inicio view*/ view('app_account_report/razon_financial/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_account_report/razon_financial/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_account_report/razon_financial/view_script');//--finview
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
				$query			= "CALL pr_accounting_get_report_razon_financial(?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRenderMultipleNative(
					$query,
					[$companyID,$branchID,$userID,$tocken,$periodID,$cycleID]
				);	
				
				$objDataResult["objCycle"]					= $objCycle;			
				$objDataResult["objDetailRazon"]			= $objData[0];			
				$objDataResult["objDetailIndicadores"]		= $objData[1];
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "razon_financiera" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/razon_financial/view_a_disemp",$objDataResult);//--finview-r
				
			}
		
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function estado_de_resultado(){
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
			$classID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"classID");//--finuri
			 
			
			if(!($viewReport && $periodID && $cycleID)){
				
				
				$data["objListAccountingPeriod"] 	= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				$data["objListCentroCosto"]			= $this->Center_Cost_Model->getByCompany($companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= /*--inicio view*/ view('app_account_report/estado_de_resultado/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_account_report/estado_de_resultado/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_account_report/estado_de_resultado/view_script');//--finview
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
				
				$objCenterCost	= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);
				//Get Datos
				$query			= "CALL pr_accounting_get_report_estado_resultado(?,?,?,?,?,?,?);";
				$objData		= $this->Bd_Model->executeRenderMultipleNative(
					$query,
					[$companyID,$branchID,$userID,$tocken,$periodID,$cycleID,$classID]
				);	
				
				$objDataResult["objDetailIngresos"]			= isset($objData[0]) ? $objData[0] : NULL;
				$objDataResult["objDetailCostos"]			= isset($objData[1]) ? $objData[1] : NULL;
				$objDataResult["objDetailGastos"]			= isset($objData[2]) ? $objData[2] : NULL;;
				$objDataResult["objUtilityNeta"]			= $objData[3][0]["valor"];
				$objDataResult["objUtilityMensual"]			= $objData[4][0]["valor"];
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;
				
				$objDataResult["objCenterCost"]		= $objCenterCost;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "estado_de_resultado" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/estado_de_resultado/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function cash_flow(){
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
				$dataSession["head"]	= /*--inicio view*/ view('app_account_report/cash_flow/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_account_report/cash_flow/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_account_report/cash_flow/view_script');//--finview
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
				$query			= "CALL pr_accounting_get_report_cash_flow(?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$cycleID,$periodID]
				);	
				
				$objDataResult["objDetail"]			= $objData;				
				$objDataResult["objCompany"] 		= $objCompany;
				$objDataResult["objLogo"] 			= $objParameter;
				$objDataResult["objPeriod"]			= $objPeriod;
				$objDataResult["objCycle"]			= $objCycle;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "estado_de_resultado" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/cash_flow/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function presupuestory(){
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
			
									
			$viewReport		= false;
			$periodID		= false;
			$cycleID		= false;
			
			$viewReport		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$periodID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"periodID");//--finuri
			$cycleID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"cycleID");//--finuri	
			 
			
			if(!($viewReport && $periodID && $cycleID)){
				
				$data["objListAccountingPeriod"] 
										= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= /*--inicio view*/ view('app_account_report/presupuestory/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_account_report/presupuestory/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_account_report/presupuestory/view_script');//--finview
				$dataSession["footer"]	= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{
				//Cargar Libreria
				///////////////////////////
				///////////////////////////
					
				
				
				
				
						
				//Obtener datos
				////////////////////////////
				////////////////////////////
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
				$query			= "CALL pr_accounting_get_report_presupuestory(?,?,?);";
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$periodID,$cycleID]
				);
				
				$objDataResult["objDetail"] 	= $objData;
				$objDataResult["objPeriod"] 	= $objPeriod;
				$objDataResult["objCycle"] 		= $objCycle;
				$objDataResult["objCompany"] 	= $objCompany;
				$objDataResult["objLogo"] 		= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balanza_comprobacion" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/presupuestory/view_a_disemp",$objDataResult);//--finview-r
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function balanza_comprobacion(){
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
			
									
			$viewReport		= false;
			$periodID		= false;
			$cycleID		= false;
			
			$viewReport		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$periodID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"periodID");//--finuri
			$cycleID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"cycleID");//--finuri					
			$classID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"classID");//--finuri

			
			if(!($viewReport && $periodID && $cycleID)){
				
				
				$data["objListAccountingPeriod"] 	= $this->Component_Period_Model->get_rowByCompany($dataSession["user"]->companyID);
				$data["objListCentroCosto"]			= $this->Center_Cost_Model->getByCompany($dataSession["user"]->companyID);
				
				//Renderizar Resultado 
				$dataSession["message"]	= $this->core_web_notification->get_message();
				$dataSession["head"]	= /*--inicio view*/ view('app_account_report/balanza_de_comprobacion/view_head');//--finview
				$dataSession["body"]	= /*--inicio view*/ view('app_account_report/balanza_de_comprobacion/view_body',$data);//--finview
				$dataSession["script"]	= /*--inicio view*/ view('app_account_report/balanza_de_comprobacion/view_script');//--finview
				$dataSession["footer"]	= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{
				//Cargar Libreria
				///////////////////////////
				///////////////////////////
					
				
				
				
				
						
				//Obtener datos
				////////////////////////////
				////////////////////////////
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
				
				$query			= "CALL pr_accounting_get_report_balanza_de_comprobacion(?,?,?,?);";
				$objData		= $this->Bd_Model->executeRenderMultipleNative(
					$query,
					[$companyID,$periodID,$cycleID,$classID]
				);	
				
				$objDataResult["objSumary"] 	= isset($objData[1][0]) ? $objData[1][0] : array();
				$objDataResult["objDetail"] 	= isset($objData[0]) ? $objData[0] : array();
				$objDataResult["objPeriod"] 	= $objPeriod;
				$objDataResult["objCycle"] 		= $objCycle;
				$objDataResult["objCompany"] 	= $objCompany;
				$objDataResult["objLogo"] 		= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "balanza_comprobacion" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_account_report/balanza_de_comprobacion/view_a_disemp",$objDataResult);//--finview-r
			}
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
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
			$dataSession["head"]			= /*--inicio view*/ view('app_account_report/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_report/view_body',$dataMenu);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_report/view_script');//--finview
			$dataSession["footer"]			= "";			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>