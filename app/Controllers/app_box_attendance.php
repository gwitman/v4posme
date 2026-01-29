<?php
//posme:2023-02-27
namespace App\Controllers;
class app_box_attendance extends _BaseController {
	
    
    function edit(){ 
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_EDIT);			
			
			}	
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			//Redireccionar datos
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
			if((!$companyID || !$transactionID  || !$transactionMasterID))
			{ 
				$this->response->redirect(base_url()."/".'app_box_attendance/add');	
			} 		
			
			
			
			//Componente de facturacion
			$objComponentTransactionShare	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_attendance");
			if(!$objComponentTransactionShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_attendance' NO EXISTE...");
		
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);						
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			//Tipo de Factura			
			$customerDefault									= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);						
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");			
			$objParameterATTENDANCE_AUTO_PRINTER 				= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"ATTENDANCE_AUTO_PRINTER")->value;
			
			
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objComponentShare"]		= $objComponentTransactionShare;					
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_attendance","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			
			
			$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objListPrioridad"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_attendance","priorityID",$companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_attendance/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_attendance/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_attendance/edit_script',$dataView);//--finview
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
	function delete(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"delete",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_DELETE);			
			
			}	
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
				
			
			//Nuevo Registro
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");				
			$transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
			
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
					throw new \Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");
				
				
			//Si el documento esta aplicado crear el contra documento
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_attendance","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro			
			$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
			$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
			
	}
	function updateElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_EDIT);	
			}
			
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_attendance");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_attendance' NO EXISTE...");
			
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;			
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			
			
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_attendance","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro
			//$objTMNew["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");			
			$objTMNew["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference1");
			$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference2");
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference3");	
			$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference4");			
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");			
			$objTMNew["priorityID"]						= /*inicio get post*/ $this->request->getPost("txtPriorityID");
			
			$db=db_connect();
			$db->transStart();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_attendance","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);				
			}
						
			
			if($db->transStatus() !== false){				
				$db->transCommit();										
				$this->core_web_notification->set_message(false,SUCCESS);				
				$this->response->redirect(base_url()."/".'app_box_attendance/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);				
			}
			else{
				$db->transRollback();	
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_box_attendance/add');	
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
	function insertElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_INSERT);	
			}
			
			
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_attendance");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_attendance' NO EXISTE...");
			
		
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$objParameterATTENDANCE_AUTO_PRINTER 	= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"ATTENDANCE_AUTO_PRINTER")->value;
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_attendance",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_attendance",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShare->componentID;
			$objTM["note"] 							= "";
			$objTM["sign"] 							= 0;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;
			$objTM["currencyID2"]					= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);			
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference1");
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference2");
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference3");
			$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference4");
			$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["priorityID"]					= /*inicio get post*/ $this->request->getPost("txtPriorityID");
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$transactionMasterID, 0700,true);
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				
				
				if($objParameterATTENDANCE_AUTO_PRINTER == "true")
				{
					//Ejecutar Impresion automatica				
					$ch 		= curl_init();
					$urlPrinter = base_url()."/app_box_attendance/viewRegisterFormatoPaginaTicketDirect80mm/companyID/".$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID;
					log_message("error",$urlPrinter);
					
					// set URL and other appropriate options
					curl_setopt($ch, CURLOPT_URL, $urlPrinter);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					
					// grab URL and pass it to the browser
					curl_exec($ch);
					
					// close cURL resource, and free up system resources
					curl_close($ch);
					
					
					
					$this->response->redirect(base_url()."/".'app_box_attendance/add');	
				}
				if($objParameterATTENDANCE_AUTO_PRINTER == "false")
				{
					$this->response->redirect(base_url()."/".'app_box_attendance/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
				}
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_box_attendance/add');	
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
	function save($mode=""){
		 $mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtStatusID","Estado","required");
			$this->validation->setRule("txtDate","Fecha","required");
			
			 //Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_box_attendance/add');
				exit;
			} 
			
			//Guardar o Editar Registro						
			if($mode == "new"){
				$this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				$this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_box_attendance/add');
				exit;
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
	
	function add(){ 
	
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
			
			}	
			 
			
			
			
			
			
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
		
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_attendance",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			
			//Tipo de Factura
			$customerDefault					= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);			
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			
			
			$objParameterATTENDANCE_AUTO_PRINTER 				= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"ATTENDANCE_AUTO_PRINTER")->value;
			$dataView["objParameterATTENDANCE_AUTO_PRINTER"]	= $objParameterATTENDANCE_AUTO_PRINTER;			
			$objParameterExchangePurchase			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID);
			$dataView["exchangeRatePurchase"]		= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) - $objParameterExchangePurchase->value;			
			$objParameterExchangeSales				= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID);
			$dataView["exchangeRateSale"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) + $objParameterExchangeSales->value;		
			
	
			$dataView["objCurrency"]					= $objCurrency;
			$dataView["objCaudal"]						= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_attendance","statusID",$companyID,$branchID,$roleID);
			$dataView["objComponentCustomer"]			= $objComponentCustomer;
			$dataView["objCustomerDefault"]				= $this->Customer_Model->get_rowByCode($companyID,$customerDefault->value);
			$dataView["objNaturalDefault"]				= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]				= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objListPrioridad"]				= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_attendance","priorityID",$companyID);
			$dataView["objParameterAsistenciaManual"]	= $this->core_web_parameter->getParameter("BOX_ATTENDANCE_MANUALITY",$companyID)->value;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_attendance/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_attendance/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_attendance/news_script',$dataView);//--finview
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
	function index($dataViewID = null){	
		try{ 
		
			//Librerias
			
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_attendance");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_attendance' NO EXISTE...");
			
			
			//Vista por defecto PC
			if($dataViewID == null and  !$this->request->getUserAgent()->isMobile() ){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}		
			//Vista por defecto MOBILE
			else if( $this->request->getUserAgent()->isMobile() ){
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$objComponent->componentID,"DEFAULT_MOBILE_LISTA_EGRESOS_A_CAJA",CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			//Vista Por Id
			else 
			{
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_attendance/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_box_attendance/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_box_attendance/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
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
	function searchTransactionMaster(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			
			//Nuevo Registro
			$transactionNumber 	= /*inicio get post*/ $this->request->getPost("transactionNumber");
			
			
			if(!$transactionNumber){
					throw new \Exception(NOT_PARAMETER);			
			} 			
			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID,$transactionNumber);	
			
			if(!$objTM)
			throw new \Exception("NO SE ENCONTRO EL DOCUMENTO");	
			
			
			
			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
			));//--finjson
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}
	
	//factura en imppresora de ticket de 80mm
	function viewRegisterFormatoPaginaTicket80mm(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_attendance","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterAttendance(
			    "ASISTENCIA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
			);
			$this->dompdf->loadHTML($html);
			
			//1cm = 29.34666puntos
			//a4: 210 ancho x 297
			//a4: 21cm x 29.7cm
			//a4: 595.28puntos x 841.59puntos
			
			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->setPaper(array(0,0,234.76,6000));
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_84/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_84/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				

			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' => !$objParameterShowLinkDownload ]);
			}
			
			
			
			
		}
		catch(\Exception $ex)
		{
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	//factura en imppresora de ticket de 80mm
	function viewRegisterFormatoPaginaTicketDirect80mm(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]			= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";			
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);						
			$dataView["objNatural"]						= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustumer"]->branchID,$dataView["objCustumer"]->entityID);
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmAttendance($dataView);
			
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
	
	//factura en imppresora de ticket de 80mm
	function viewRegisterFormatoPaginaTicketDirect80mmNoImage(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]			= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";			
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);						
			$dataView["objNatural"]						= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustumer"]->branchID,$dataView["objCustumer"]->entityID);
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmAttendanceNoImage($dataView);
			
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
	
	function viewRegisterFormatoQR(){
		
		$entityIDCustomer		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"entityIDCustomer");//--finuri						
		$typeResult				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"typeResult");//--finuri						
		$companyID 				= APP_COMPANY;	
		$branchID 				= APP_BRANCH;	
		$objCustomer			= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityIDCustomer);
		$objCompany 			= $this->Company_Model->get_rowByPK($companyID);			
		$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
		$objParameterRuc        = $objParameterRuc->value;
		$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
		$dataSession			= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID 				= $dataSession["user"]->companyID;
		if(!$companyID && !$entityID){
				throw new \Exception(NOT_PARAMETER);		
		} 
		
		$objComponentShare		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_attendance");
		if(!$objComponentShare)
		throw new \Exception("EL COMPONENTE 'tb_transaction_master_attendance' NO EXISTE...");
	
		if(!$objCustomer)
		{
			//Obtener imagen de logo
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameterLogo       = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;    
			$type    = pathinfo($path, PATHINFO_EXTENSION);
			$data    = file_get_contents($path);
			$base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
			$dataViewParse["imageBase64"]			= $base64;		
			
			//Parse plantilla 
			$dataViewParse["companyName"]			= $objCompany->name;
			$dataViewParse["companyRuc"]			= $objParameterRuc;
			$dataViewParse["transactionNumber"]		= "ASI00000000";
			$dataViewParse["transactionOn"]			= "1900-01-01";
			$dataViewParse["userName"]				= $dataSession["user"]->nickname;
			$dataViewParse["phoneNumber"]			= $objParameterTelefono->value;
			$dataViewParse["address"]				= "Error";
			$dataViewParse["customerRuc"]			= "Error";
			$dataViewParse["customerName"]			= "Cliente no encontrado";
			$dataViewParse["statusName"]			= "Error";
			$dataViewParse["solvencyName"]			= "Error";
			$dataViewParse["nextPaymentDate"]		= "Error";
			$dataViewParse["dayNextPayment"]		= "Error";
			$dataViewParse["dateExpired"]			= "Error";
			$dataViewParse["tipoCss"]				= $dataViewParse["solvencyName"] == "SI" ? "success" : "danger";
			
			
			if($typeResult == "html") 
			{
				$htmlTemplateCompany					= getBahavioLargeDB($objCompany->type,"app_box_attendance","templateAttendaceHtmlPage","");
				$htmlTemplateDemo 						= getBahavioLargeDB("demo","app_box_attendance","templateAttendaceHtmlPage","");
				if($htmlTemplateCompany == "")
					$htmlTemplateCompany = $htmlTemplateDemo;
			
				$parser 				= \Config\Services::parser();							
				$dataBody["body"] 		= $parser->setData($dataViewParse)->renderString($htmlTemplateCompany);					
				$dataBody["head"] 		= "";
				$dataBody["footer"] 	= "";
				$dataBody["script"] 	= "";					
				$dataBody["title"] 		= "";	
				$htmlPage 				= view("core_masterpage/default_masterpage_public",$dataBody);//--finview-r
				echo $htmlPage;
				exit;
			}
			else
			{
				$htmlTemplateCompany					= getBahavioLargeDB($objCompany->type,"app_box_attendance","templateAttendace","");
				$htmlTemplateDemo 						= getBahavioLargeDB("demo","app_box_attendance","templateAttendace","");
				if($htmlTemplateCompany == "")
					$htmlTemplateCompany = $htmlTemplateDemo;
			
				$parser = \Config\Services::parser();			
				$html 	= $parser->setData($dataViewParse)->renderString($htmlTemplateCompany);
			}
			
			$this->dompdf->loadHTML($html);
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			$transactionMasterID 	= 0;
			$fileNamePut 			= "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			if (!file_exists(PATH_FILE_OF_APP."/company_".$companyID."/component_84/component_item_".$transactionMasterID))
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_84/component_item_".$transactionMasterID, 0777,true);						
			$path        			= "./resource/file_company/company_".$companyID."/component_84/component_item_".$transactionMasterID."/".$fileNamePut;
			
			file_put_contents(
				$path,
				$this->dompdf->output()
			);
			chmod($path, 644);
					
			//visualizar 
			$this->dompdf->stream("file.pdf ", ['Attachment' => !$objParameterShowLinkDownload ]);
			exit;
		}	
		
		
		try{ 
			
			//Obtener Parametros
			$entityID		= $objCustomer->entityID;
			//Obtener tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			//Obtener Cliente
			$objCustomer 					= $this->Customer_Model->get_rowByEntity($companyID,$entityID);
			$branchID 						= ($objCustomer != null ? $objCustomer->branchID : 0);
			
			//Obtener Lineas de Credito
			$objListCustomerCreditLine2 	= $this->Customer_Credit_Line_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$objListCustomerCreditLine 		= null;
			$counter 						= 0;
			
			if($objListCustomerCreditLine2)
			{
				foreach($objListCustomerCreditLine2 as $key => $value){
					if($value->balance > 0)
					{
						$objListCustomerCreditLine[$counter] = $value;
						$counter++;
					}
				}
			}
			
			//Obtener Tabla de Amortizacion del cliente
			$objCustomerCreditAmoritizationAll	= $this->Customer_Credit_Amortization_Model->get_rowByCustomerID($entityID);			
			if(!$objCustomerCreditAmoritizationAll)
			{
				//Obtener imagen de logo
				$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$objParameterLogo       = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;    
				$type    = pathinfo($path, PATHINFO_EXTENSION);
				$data    = file_get_contents($path);
				$base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
				$dataViewParse["imageBase64"]			= $base64;			
				
				
				//Parse plantilla 
				$dataViewParse["companyName"]			= $objCompany->name;
				$dataViewParse["companyRuc"]			= $objParameterRuc;
				$dataViewParse["transactionNumber"]		= "ASI00000000";
				$dataViewParse["transactionOn"]			= "1900-01-01";
				$dataViewParse["userName"]				= $dataSession["user"]->nickname;
				$dataViewParse["phoneNumber"]			= $objParameterTelefono->value;
				$dataViewParse["address"]				= "Error";
				$dataViewParse["customerRuc"]			= "Error";
				$dataViewParse["customerName"]			= "Cliente no tiene membresia";
				$dataViewParse["statusName"]			= "Error";
				$dataViewParse["solvencyName"]			= "Error";
				$dataViewParse["nextPaymentDate"]		= "Error";
				$dataViewParse["dayNextPayment"]		= "Error";
				$dataViewParse["dateExpired"]			= "Error";
				$dataViewParse["tipoCss"]				= $dataViewParse["solvencyName"] == "SI" ? "success" : "danger";
				
				
				if($typeResult == "html") 
				{
					$htmlTemplateCompany					= getBahavioLargeDB($objCompany->type,"app_box_attendance","templateAttendaceHtmlPage","");
					$htmlTemplateDemo 						= getBahavioLargeDB("demo","app_box_attendance","templateAttendaceHtmlPage","");
					if($htmlTemplateCompany == "")
						$htmlTemplateCompany = $htmlTemplateDemo;
				
					$parser 				= \Config\Services::parser();			
					$dataBody["body"] 		= $parser->setData($dataViewParse)->renderString($htmlTemplateCompany);					
					$dataBody["head"] 		= "";
					$dataBody["footer"] 	= "";
					$dataBody["script"] 	= "";					
					$dataBody["title"] 		= "";	
					$htmlPage 				= view("core_masterpage/default_masterpage_public",$dataBody);//--finview-r
					echo $htmlPage;
					exit;
				}
				else
				{
					$htmlTemplateCompany					= getBahavioLargeDB($objCompany->type,"app_box_attendance","templateAttendace","");
					$htmlTemplateDemo 						= getBahavioLargeDB("demo","app_box_attendance","templateAttendace","");
					if($htmlTemplateCompany == "")
						$htmlTemplateCompany = $htmlTemplateDemo;
				
					$parser = \Config\Services::parser();			
					$html 	= $parser->setData($dataViewParse)->renderString($htmlTemplateCompany);
				}
				
				$this->dompdf->loadHTML($html);
				$this->dompdf->render();
				
				$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
				$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
				
				$transactionMasterID 	= 0;
				$fileNamePut 			= "factura_".$transactionMasterID."_".date("dmYhis").".pdf";				
				if (!file_exists(PATH_FILE_OF_APP."/company_".$companyID."/component_84/component_item_".$transactionMasterID))
				mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_84/component_item_".$transactionMasterID, 0777,true);						
				$path        			= "./resource/file_company/company_".$companyID."/component_84/component_item_".$transactionMasterID."/".$fileNamePut;
				
				file_put_contents(
					$path,
					$this->dompdf->output()
				);
				chmod($path, 644);
						
				//visualizar 
				$this->dompdf->stream("file.pdf ", ['Attachment' => !$objParameterShowLinkDownload ]);
				exit;
			}
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson	
		}
		
		
		
		try{
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_attendance");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_attendance' NO EXISTE...");
			
		
			$transactionOn	= helper_getDateTime();
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$transactionOn ))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			
			$objWorkflowStage						= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_attendance","statusID",$companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID);
			$objPrioridad							= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_attendance","priorityID",$companyID);			
			
			//Obtener la Mora
			////////////////////////////////
			////////////////////////////////
			$CantidadMora = array_filter(
					$objCustomerCreditAmoritizationAll,
					function ($item) {
						return isset($item->stageDocumento) && $item->stageDocumento !== 'CANCELADO' && $item->Mora > 0;
					}
			);
			$CantidadMora = array_values($CantidadMora);			
			if(count($CantidadMora) == 0)
			{
				$CantidadMora = 0;
			}
			else 
			{
					$CantidadMora = array_map(function ($item) {
						return isset($item->Mora) ? (int)$item->Mora : 0;
					}, $CantidadMora);
		
					$CantidadMora = !empty($CantidadMora) ? max($CantidadMora) : 0;
			}
				
			if($CantidadMora > 0)
			{
				$txtDetailReference1	= "NO";	
				$txtDetailReference4	= 0;
			}
			if($CantidadMora <= 0)
			{							
				$txtDetailReference1 = "SI";
			}
			
			////Fecha de Vencimiento
			////////////////////////////////
			////////////////////////////////
			$FechaVencimiento 		= array_filter(
					$objCustomerCreditAmoritizationAll,
					function ($item) {
						return isset($item->stageDocumento) && $item->stageDocumento !== 'CANCELADO';
					}
			);			
			$FechaVencimiento 		= array_values($FechaVencimiento);			
			$FechaVencimientoMora	= array_map(function ($item) {
					return isset($item->Mora) ? (int)$item->Mora : 0;
				}, $FechaVencimiento);
			$FechaVencimientoMora 	= !empty($FechaVencimientoMora) ? min($FechaVencimientoMora) : 0;
				
			
			$FechaVencimiento 	= array_filter(
					$FechaVencimiento,
					function ($item) use ($FechaVencimientoMora)  {
						return isset($item->stageDocumento) && $item->stageDocumento !== 'CANCELADO' && $item->Mora == $FechaVencimientoMora ;
					}
			);			
			$FechaVencimiento 	= array_values($FechaVencimiento);			
			
			$FechaVencimiento		= $FechaVencimiento[0];
			$FechaVencimiento		= $FechaVencimiento->dateApply;
			$txtDetailReference3	= $FechaVencimiento;
			//Fecha del proximo pago
			////////////////////////////////
			////////////////////////////////
			$FechaProximoPago 		= array_filter(
					$objCustomerCreditAmoritizationAll,
					function ($item) {
						return isset($item->stageDocumento) && $item->stageDocumento !== 'CANCELADO';
					}
			);
			$FechaProximoPago 		= array_values($FechaProximoPago);			
			
			$FechaProximoPagoMora	= array_map(function ($item) {
					return isset($item->Mora) ? (int)$item->Mora : 0;
				}, $FechaProximoPago);
			$FechaProximoPagoMora 		= !empty($FechaProximoPagoMora) ? max($FechaProximoPagoMora) : 0;
			
			$FechaProximoPago 		= array_filter(
					$FechaProximoPago,
					function ($item) use ($FechaProximoPagoMora ) {
						return isset($item->stageDocumento) && $item->stageDocumento !== 'CANCELADO' && $item->Mora == $FechaProximoPagoMora;
					}
			);
			$FechaProximoPago 		= array_values($FechaProximoPago);			
			
			$FechaProximoPago		= $FechaProximoPago[0];
			$FechaProximoPago		= $FechaProximoPago->dateApply;
			$txtDetailReference2 	= $FechaProximoPago;
			
			//Dias del Proximo Pago
			if($FechaProximoPagoMora > 0 )
				$txtDetailReference4 = 0;						
			else 
				$txtDetailReference4 = ( $FechaProximoPagoMora * -1 );	
			
			
			//Obtener transaccion
			$objParameterATTENDANCE_AUTO_PRINTER 	= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"ATTENDANCE_AUTO_PRINTER")->value;
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_attendance",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_attendance",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);
			$objTM["entityID"] 						= $entityID;
			$objTM["transactionOn"]					= $transactionOn;
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShare->componentID;
			$objTM["note"] 							= "";
			$objTM["sign"] 							= 0;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;
			$objTM["currencyID2"]					= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);			
			$objTM["reference1"] 					= $txtDetailReference1;//Cliente con Mora: SI,NO
			$objTM["reference2"] 					= $txtDetailReference2;//Dias para el proximo pago: number
			$objTM["reference3"] 					= $txtDetailReference3;//Fecha de vencimiento
			$objTM["reference4"] 					= $txtDetailReference4;//Dias para el proximo pago
			$objTM["statusID"] 						= $objWorkflowStage[0]->workflowStageID;
			$objTM["priorityID"]					= $objPrioridad[0]->catalogItemID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			if (!file_exists(PATH_FILE_OF_APP."/company_".$companyID."/component_84/component_item_".$transactionMasterID))
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$transactionMasterID, 0700,true);						
			if($db->transStatus() !== false)
			{
				$db->transCommit();
			}
			else
			{
				$db->transRollback();
			}
			
			
		}
		catch(\Exception $ex)
		{
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
		    return $resultView;
		}	
		
		try
		{ 
			$companyID 				= APP_COMPANY;	
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 				= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_attendance","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			//Obtener imagen de logo
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameterLogo       = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;    
			$type    = pathinfo($path, PATHINFO_EXTENSION);
			$data    = file_get_contents($path);
			$base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
			$dataViewParse["imageBase64"]						= $base64;
			
			//Obtener imagen de logo marca de agua
			$path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-marca-'.$objParameterLogo->value;   
			if (file_exists($path)) {
				$type    					 		= pathinfo($path, PATHINFO_EXTENSION);
				$data    					 		= file_get_contents($path);
				$base64  					 		= 'data:image/' . $type . ';base64,' . base64_encode($data);
				$dataViewParse["imageBase64Marca"] 	= $base64;
			} 
			
	
			
			//Parse plantilla 
			$dataViewParse["companyName"]			= $objCompany->name;
			$dataViewParse["companyRuc"]			= $objParameterRuc;
			$dataViewParse["transactionNumber"]		= $datView["objTM"]->transactionNumber;
			$dataViewParse["transactionOn"]			= $datView["objTM"]->createdOn;
			$dataViewParse["userName"]				= $datView["objUser"]->nickname;
			$dataViewParse["phoneNumber"]			= $objParameterTelefono->value;
			$dataViewParse["address"]				= $objCompany->address;
			$dataViewParse["customerRuc"]			= $datView["objCustumer"]->customerNumber;
			$dataViewParse["customerName"]			= $datView["objNatural"]->firstName;
			$dataViewParse["statusName"]			= $datView["objStage"][0]->name;
			$dataViewParse["solvencyName"]			= $datView["objTM"]->reference1;
			$dataViewParse["nextPaymentDate"]		= $datView["objTM"]->reference2;
			$dataViewParse["dayNextPayment"]		= $datView["objTM"]->reference4;
			$dataViewParse["dateExpired"]			= $datView["objTM"]->reference3;
			$dataViewParse["tipoCss"]				= $dataViewParse["solvencyName"] == "SI" ? "success" : "danger";
			
			if($typeResult == "html") 
			{
				$htmlTemplateCompany					= getBahavioLargeDB($objCompany->type,"app_box_attendance","templateAttendaceHtmlPage","");
				$htmlTemplateDemo 						= getBahavioLargeDB("demo","app_box_attendance","templateAttendaceHtmlPage","");
				if($htmlTemplateCompany == "")
					$htmlTemplateCompany = $htmlTemplateDemo;
			
				$parser = \Config\Services::parser();			
				$dataBody["body"] 		= $parser->setData($dataViewParse)->renderString($htmlTemplateCompany);					
				$dataBody["head"] 		= "";
				$dataBody["footer"] 	= "";
				$dataBody["script"] 	= "";					
				$dataBody["title"] 		= "";	
				$htmlPage 				= view("core_masterpage/default_masterpage_public",$dataBody);//--finview-r
				echo $htmlPage;
				exit;
			}
			else
			{
				$htmlTemplateCompany					= getBahavioLargeDB($objCompany->type,"app_box_attendance","templateAttendace","");
				$htmlTemplateDemo 						= getBahavioLargeDB("demo","app_box_attendance","templateAttendace","");
				if($htmlTemplateCompany == "")
					$htmlTemplateCompany = $htmlTemplateDemo;
			
				$parser = \Config\Services::parser();			
				$html 	= $parser->setData($dataViewParse)->renderString($htmlTemplateCompany);
			}
			$this->dompdf->loadHTML($html);
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_84/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
					
			//visualizar
			$this->dompdf->stream("file.pdf ", ['Attachment' => !$objParameterShowLinkDownload ]);
			exit;
			
			
		}
		catch(\Exception $ex)
		{
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    return $resultView;
		}
		
	}
	
}
?>