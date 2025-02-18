<?php

namespace App\Controllers;

use Exception;

class app_cxc_notecredit extends _BaseController
{
	function index($dataViewID = null)
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession        = $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {

				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission     == PERMISSION_NONE)
					throw new Exception(NOT_ACCESS_FUNCTION);
			}

			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxc_notacredit");
			if (!$objComponent)
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_cxc_notacredit' NO EXISTE...");


			//Vista por defecto PC
			if ($dataViewID == null and  !$this->request->getUserAgent()->isMobile()) {
				$targetComponentID              = 0;
				$parameter["{companyID}"]       = $this->session->get('user')->companyID;
				$dataViewData                   = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
				$dataViewRender                 = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
			}
			//Vista por defecto MOBILE
			else if ($this->request->getUserAgent()->isMobile()) {
				$parameter["{companyID}"]       = $this->session->get('user')->companyID;
				$dataViewData                   = $this->core_web_view->getViewByName($this->session->get('user'), $objComponent->componentID, "LISTA DE NOTAS DE CREDITO DE CLIENTES", CALLERID_LIST, $resultPermission, $parameter);
				$dataViewRender                 = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
			}
			//Vista Por Id
			else {
				$parameter["{companyID}"]       = $this->session->get('user')->companyID;
				$dataViewData                   = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
				$dataViewRender                 = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
			}

			//Renderizar Resultado
			$dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]             = $this->core_web_notification->get_message();
			$dataSession["head"]                = /*--inicio view*/ view('app_cxc_notecredit/list_head'); //--finview
			$dataSession["footer"]              = /*--inicio view*/ view('app_cxc_notecredit/list_footer'); //--finview
			$dataSession["body"]                = $dataViewRender;
			$dataSession["script"]              = /*--inicio view*/ view('app_cxc_notecredit/list_script'); //--finview
			$dataSession["script"]              = $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r	
		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			return $resultView;
		}
	}

	function save($mode = "")
	{
		$mode = helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);

		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//Validar Formulario						
			$this->validation->setRule("txtCustomerEntityID", "Cliente", "required");
			$this->validation->setRule("txtAmount", "Monto", "required");
			$this->validation->setRule("txtDate", "Fecha", "required");

			//Validar Formulario
			if (!$this->validation->withRequest($this->request)->run()) {
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true, $stringValidation);
				$this->response->redirect(base_url() . "/" . 'app_cxc_notecredit/add');
				exit;
			}

			//Guardar o Editar Registro						
			if ($mode == "new") {
				$this->insertElement($dataSession);
			} else if ($mode == "edit") {
				$this->updateElement($dataSession);
			} else {
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true, $stringValidation);
				$this->response->redirect(base_url() . "/" . 'app_box_share/add');
				exit;
			}
		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			return $resultView;
		}
	}

	function add()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession        = $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission     == PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}

			$companyID      = $dataSession["user"]->companyID;
			$branchID       = $dataSession["user"]->branchID;
			$roleID         = $dataSession["role"]->roleID;

			$objComponentNoteCredit        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxc_notacredit");
			if (!$objComponentNoteCredit)
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_cxc_notacredit' NO EXISTE...");


			//Obtener el componente de Item
			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if (!$objComponentCustomer)
				throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");




			$objCurrency						                = $this->core_web_currency->getCurrencyDefault($companyID);
			$dataView["company"]                                = $dataSession["company"];
			$dataView["objComponentNoteCredit"]                 = $objComponentNoteCredit;
			$dataView["objComponentCustomer"]                   = $objComponentCustomer;
			$dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_cxc_notacredit", "statusID", $companyID, $branchID, $roleID);
			$dataView["objListCustomer"]                        = $this->Customer_Model->get_rowByCompany($companyID);
			$dataView["objListCurrency"]                        = $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCurrencyDefault"]                 = $this->core_web_currency->getCurrencyDefault($companyID);
			$dataView["objCurrency"]                            = $objCurrency;

			//Renderizar Resultado 
			$dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]             = $this->core_web_notification->get_message();
			$dataSession["head"]                = /*--inicio view*/ view('app_cxc_notecredit/news_head', $dataView); //--finview
			$dataSession["body"]                = /*--inicio view*/ view('app_cxc_notecredit/news_body', $dataView); //--finview
			$dataSession["script"]              = /*--inicio view*/ view('app_cxc_notecredit/news_script', $dataView); //--finview
			$dataSession["footer"]              = "";
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r

		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			return $resultView;
		}
	}

	function edit()
	{
		try {
			if (!$this->core_web_authentication->isAuthenticated()) {
				throw new Exception(USER_NOT_AUTENTICATED);
			}

			$dataSession    = $this->session->get();

			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}

			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri
			$branchID 				= $dataSession["user"]->branchID;


			$objComponentNoteCredit        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxc_notacredit");
			if (!$objComponentNoteCredit)
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_cxc_notacredit' NO EXISTE...");


			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if (!$objComponentCustomer)
				throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");



			if ((!$companyID) || (!$transactionID) || (!$transactionMasterID)) {
				$this->response->redirect(base_url() . "/" . 'app_cxc_notecredit/add');
			}

			$dataView["objTM"]                  = $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
			$dataView["objCustomer"]            = $this->Customer_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->entityID);
			$dataView["objListLegal"]			= $this->Legal_Model->get_rowByPk($companyID, $branchID, $dataView["objTM"]->entityID);
			$dataView["objComponentCustomer"]   = $objComponentCustomer;
			$dataView["objComponentNoteCredit"]	= $objComponentNoteCredit;
			$dataView["objListCurrency"]        = $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]   = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_cxc_notacredit", "statusID", $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID);

			//RENDERIZAR RESULTADO
			$dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]         = $this->core_web_notification->get_message();
			$dataSession["head"]            = view('app_cxc_notecredit/edit_head', $dataView);
			$dataSession["body"]            = view('app_cxc_notecredit/edit_body', $dataView);
			$dataSession["script"]          = view('app_cxc_notecredit/edit_script', $dataView);
			$dataSession["footer"]          = "";
			return view('core_masterpage/default_masterpage', $dataSession);
		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			return $resultView;
		}
	}

	function insertElement($dataSession)
	{
		try {
			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}



			$objComponentNoteCredit        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxc_notacredit");
			if (!$objComponentNoteCredit)
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_cxc_notacredit' NO EXISTE...");


			//Obtener el componente de Item
			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if (!$objComponentCustomer) {
				throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			}

			$companyID                              = $dataSession["user"]->companyID;
			$branchID                               = $dataSession["user"]->branchID;

			$entityID                               = $this->request->getPost("txtCustomerEntityID");
			$currencyID                             = $this->request->getPost("txtCurrencyID");
			$monto                                  = $this->request->getPost("txtAmount");

			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE);
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn), "Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID, $dateOn, 1, $objCurrencyDolares->currencyID, $objCurrencyCordoba->currencyID);

			$objTM["companyID"]                     = $companyID;
			$objTM["transactionNumber"]             = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_transaction_master_cxc_notacredit", 0);
			$objTM["transactionID"]                 = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_cxc_notacredit", 0);
			$objTM["branchID"]                      = $branchID;
			$objTM["transactionCausalID"]           = $this->core_web_transaction->getDefaultCausalID($companyID, $objTM["transactionID"]);
			$objTM["entityID"]                      = $entityID;
			$objTM["transactionOn"]                 = /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["componentID"]                   = $objComponentNoteCredit->componentID;
			$objTM["note"]                          = /*inicio get post*/ $this->request->getPost("txtComment");
			$objTM["currencyID"]                    = $currencyID;
			$objTM["exchangeRate"]                  = $exchangeRate;
			$objTM["reference1"]                    = /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTM["reference2"]                    = /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTM["reference3"]                    = /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTM["statusID"]                      = /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["amount"]                        = $monto;
			$objTM["discount"]						= 0;
			$objTM["subAmount"]						= 0;
			$objTM["isActive"]                      = 1;
			$this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

			$db = db_connect();
			$db->transStart();
			$transactionMasterID                    = $this->Transaction_Master_Model->insert_app_posme($objTM);

			//Crear la Carpeta para almacenar los Archivos del Cliente			
			if (!file_exists(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentNoteCredit->componentID . "/component_item_" . $transactionMasterID)) {
				mkdir(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentNoteCredit->componentID . "/component_item_" . $transactionMasterID, 0700, true);
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_cxc_notecredit/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->$db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_cxc_notecredit/add');
			}
		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			return $resultView;
		}
	}

	function updateElement($dataSession)
	{
		try {
			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}



			$objComponentNoteCredit        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxc_notacredit");
			if (!$objComponentNoteCredit)
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_cxc_notacredit' NO EXISTE...");


			//Obtener el componente de Item
			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if (!$objComponentCustomer) {
				throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			}

			$companyID                              = $dataSession["user"]->companyID;
			$branchID                               = $dataSession["user"]->branchID;
			$roleID                                 = $dataSession["role"]->roleID;

			$entityID                               = $this->request->getPost("txtCustomerEntityID");
			$currencyID                             = $this->request->getPost("txtCurrencyID");
			$monto                                  = $this->request->getPost("txtAmount");
			$transactionID                          = $this->request->getPost("txtTransactionID");
			$transactionMasterID                    = $this->request->getPost("txtTransactionMasterID");

			$objTM                                  = $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
			$objC								   	= $this->Customer_Model->get_rowByPK($companyID, $branchID, $entityID);

			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE);
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn), "Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID, $dateOn, 1, $objCurrencyDolares->currencyID, $objCurrencyCordoba->currencyID);

			$objTMNew["entityID"]                   = $entityID;
			$objTMNew["transactionOn"]              = /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["note"]                       = /*inicio get post*/ $this->request->getPost("txtComment");
			$objTMNew["currencyID"]                 = $currencyID;
			$objTMNew["exchangeRate"]               = $exchangeRate;
			$objTMNew["reference1"]                 = /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTMNew["reference2"]                 = /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTMNew["reference3"]                 = /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTMNew["statusID"]                   = /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"]                     = $monto;
			$objTMNew["discount"]					= 0;
			$objTMNew["subAmount"]					= 0;
			$objTMNew["isActive"]                   = 1;

			//Validar si el estado permite editar
			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_cxc_notacredit", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new Exception(NOT_WORKFLOW_EDIT);

			$db = db_connect();
			$db->transStart();

			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_cxc_notacredit", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->request->getPost("txtStatusID");
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			} else {
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			}


			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_cxc_notacredit", "statusID", $objTMNew["statusID"], COMMAND_APLICABLE, $companyID, $branchID, $roleID)) {

				if ($objTMNew["currencyID"] == 1/*CORDOBA*/) {
					$objTMNew["discount"]			= $objC->balanceCor;
					$objTMNew["subAmount"]			= $monto + $objC->balanceCor;
					$objCNew["balanceCor"]          = $objTMNew["subAmount"];
				} else /*DOLAR*/ {
					$objTMNew["discount"]			= $objC->balanceDol;
					$objTMNew["subAmount"]			= $monto + $objC->balanceDol;
					$objCNew["balanceDol"]			= $objTMNew["subAmount"];
				}

				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
				$this->Customer_Model->update_app_posme($companyID, $branchID, $entityID, $objCNew);
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_cxc_notecredit/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->$db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_cxc_notecredit/add');
			}
		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}

	function delete()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_DELETE);
			}

			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");
			$transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");


			if ((!$companyID && !$transactionID && !$transactionMasterID)) {
				throw new Exception(NOT_PARAMETER);
			}

			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
				throw new Exception(NOT_DELETE);

			//Si el documento esta aplicado crear el contra documento			
			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_cxc_notacredit", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new Exception(NOT_WORKFLOW_DELETE);

			//Eliminar el Registro			
			$this->Transaction_Master_Model->delete_app_posme($companyID, $transactionID, $transactionMasterID);

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			)); //--finjson
		} catch (Exception $ex) {

			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine() . " " . $ex->getMessage()
			)); //--finjson
			$this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
		}
	}

	function searchTransactionMaster()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ACCESS_FUNCTION);
			}

			$transactionNumber 	= /*inicio get post*/ $this->request->getPost("transactionNumber");

			if (!$transactionNumber) {
				throw new Exception(NOT_PARAMETER);
			}

			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID, $transactionNumber);

			if (!$objTM)
				throw new Exception("NO SE ENCONTRO EL DOCUMENTO");

			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
			)); //--finjson

		} catch (Exception $ex) {

			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine() . " " . $ex->getMessage()
			)); //--finjson
		}
	}

	function viewPrinterFormatoA4()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);


				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_EDIT);
			}


			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri				
			$companyID 					= $dataSession["user"]->companyID;
			$branchID 					= $dataSession["user"]->branchID;
			$roleID						= $dataSession["role"]->roleID;

			$objCompany 			    = $this->Company_Model->get_rowByPK($companyID);
			$objParameterTelefono	    = $this->core_web_parameter->getParameter("CORE_PHONE", $companyID);
			$objParameterLogo	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);

			$objTM						= $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
			$objCustomer                = $this->Customer_Model->get_rowByPK($companyID, $branchID, $objTM->entityID);
			$objCustomerNatural         = $this->Natural_Model->get_rowByPk($companyID, $branchID, $objTM->entityID);

			$objCurrency				= $this->Currency_Model->get_rowByPK($objTM->currencyID);
			$objParameterRuc 			= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER", $companyID)->value;
			$objTM->transactionOn		= date_format(date_create($objTM->transactionOn), "Y-m-d");
			$objUser					= $this->User_Model->get_rowByPK($companyID, $branchID, $dataSession["user"]->userID);
			$objNoteBalances			= array();
			$noteStatus					= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($objTM->statusID)[0]->name;

			$objNoteBalances["montoInicial"]	= $objTM->discount;
			$objNoteBalances["montoFinal"]		= $objTM->subAmount;

			//Generar Reporte
			$html = helper_reporteA4CreditAndDebitNote(
				"NOTA DE CREDITO",
				$objCompany,
				$objParameterLogo,
				$objTM,
				$objNoteBalances,
				$objCustomerNatural,
				$objCustomer,
				$objCurrency,
				$objParameterTelefono,
				$objUser,
				$objParameterRuc,
				$noteStatus
			);

			$this->dompdf->loadHTML($html);

			$this->dompdf->render();

			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;

			if ($objParameterShowLinkDownload == "true") {
				$fileNamePut = "factura_" . $transactionMasterID . "_" . date("dmYhis") . ".pdf";
				$path        = "./resource/file_company/company_" . $companyID . "/component_113/component_item_" . $transactionMasterID . "/" . $fileNamePut;

				file_put_contents(
					$path,
					$this->dompdf->output()
				);

				chmod($path, 644);

				echo "<a 
					href='" . base_url() . "/resource/file_company/company_" . $companyID . "/component_113/component_item_" . $transactionMasterID . "/" .
					$fileNamePut . "'>download compra</a>
				";
			} else {
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true]);
			}
		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			return $resultView;
		}
	}
}
