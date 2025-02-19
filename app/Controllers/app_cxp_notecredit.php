<?php

namespace App\Controllers;

class app_cxp_notecredit extends _BaseController
{
	function add()
	{

		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}



			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;

			$objComponentProvider					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if (!$objComponentProvider)
				throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");

			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$customerEntityID 					= /*inicio get get*/ $this->request->getGet("entityID"); //--fin peticion get o post
			$objCustomer						= null;
			$objNatural							= null;

			if (isset($customerEntityID)) {
				$objCustomer						= $this->Customer_Model->get_rowByEntity($companyID, $customerEntityID);
				$objNatural							= $this->Natural_Model->get_rowByPK($companyID, $objCustomer->branchID, $customerEntityID);
			}

			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_share", 0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);

			//Tipo de Factura			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["useMobile"]				= $dataSession["user"]->useMobile;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID, date("Y-m-d"), 1, $targetCurrency->currencyID, $objCurrency->currencyID);

			$objParameterExchangePurchase		= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE", $companyID);
			$dataView["exchangeRatePurchase"]	= $this->core_web_currency->getRatio($companyID, date("Y-m-d"), 1, $targetCurrency->currencyID, $objCurrency->currencyID) - $objParameterExchangePurchase->value;
			$objParameterExchangeSales			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE", $companyID);
			$dataView["exchangeRateSale"]		= $this->core_web_currency->getRatio($companyID, date("Y-m-d"), 1, $targetCurrency->currencyID, $objCurrency->currencyID) + $objParameterExchangeSales->value;

			$dataView["company"]							= $dataSession["company"];
			$dataView["objComponentProvider"]				= $objComponentProvider;
			$dataView["objCaudal"]							= $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
			$dataView["objListWorkflowStage"]				= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_share", "statusID", $companyID, $branchID, $roleID);
			$dataView["objListCustomer"]					= $this->Customer_Model->get_rowByCompany($companyID);
			$dataView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCurrencyDefault"]				= $this->core_web_currency->getCurrencyDefault($companyID);
			$dataView["customerEntityID"]					= $customerEntityID;
			$dataView["objCustomer"]						= $objCustomer;
			$dataView["objNatural"]							= $objNatural;

			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_notecredit/news_head', $dataView); //--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_notecredit/news_body', $dataView); //--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_notecredit/news_script', $dataView); //--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r

		} catch (\Exception $ex) {
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
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//Validar Formulario						
			$this->validation->setRule("txtCustomerID", "Proveedor", "required");
			$this->validation->setRule("txtAmount", "Monto", "required");
			$this->validation->setRule("txtDate", "Fecha", "required");

			//Validar Formulario
			if (!$this->validation->withRequest($this->request)->run()) {
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true, $stringValidation);
				$this->response->redirect(base_url() . "/" . 'app_cxp_notecredit/add');
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
				$this->response->redirect(base_url() . "/" . 'app_cxc_notecredit/add');
				exit;
			}
		} catch (\Exception $ex) {
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
				throw new \Exception(USER_NOT_AUTENTICATED);
			}

			$dataSession    = $this->session->get();

			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}

			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri
			$branchID 				= $dataSession["user"]->branchID;
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;

			$objComponentNoteCredit        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxp_notacredito");
			if (!$objComponentNoteCredit)
				throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_cxp_notacredito' NO EXISTE...");


			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if (!$objComponentCustomer)
				throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");

			$objComponentProvider					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if (!$objComponentProvider)
				throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");

			if ((!$companyID) || (!$transactionID) || (!$transactionMasterID)) {
				$this->response->redirect(base_url() . "/" . 'app_cxp_notecredit/add');
			}

			$customerEntityID 					= /*inicio get get*/ $this->request->getGet("entityID"); //--fin peticion get o post
			$objCustomer						= null;
			$objNatural							= null;


			$dataView["company"]							= $dataSession["company"];
			$dataView["objTM"]                  = $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
			$dataView["objCustomer"]            = $this->Customer_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->entityID);
			$dataView["objListLegal"]			= $this->Legal_Model->get_rowByPk($companyID, $branchID, $dataView["objTM"]->entityID);
			$dataView["objComponentProvider"]				= $objComponentProvider;
			$dataView["objCaudal"]							= $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
			$dataView["objListWorkflowStage"]				= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_share", "statusID", $companyID, $branchID, $roleID);
			$dataView["objListCustomer"]					= $this->Customer_Model->get_rowByCompany($companyID);
			$dataView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCurrencyDefault"]				= $this->core_web_currency->getCurrencyDefault($companyID);
			$dataView["customerEntityID"]					= $customerEntityID;
			$dataView["objCustomer"]						= $objCustomer;
			$dataView["objNatural"]							= $objNatural;
			$dataView["objComponentCustomer"]   = $objComponentCustomer;
			$dataView["objComponentNoteCredit"]	= $objComponentNoteCredit;
			$dataView["objListCurrency"]        = $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]   = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_cxp_notacredito", "statusID", $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID);

			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_notecredit/edit_head', $dataView); //--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_notecredit/edit_body', $dataView); //--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_notecredit/edit_script', $dataView); //--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r
		} catch (\Exception $ex) {
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
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}



			$objComponentNoteCredit        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxp_notacredito");
			if (!$objComponentNoteCredit)
				throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_cxp_notacredito' NO EXISTE...");


			//Obtener el componente de Item
			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if (!$objComponentCustomer) {
				throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			}

			$companyID                              = $dataSession["user"]->companyID;
			$branchID                               = $dataSession["user"]->branchID;

			$entityID                               = $this->request->getPost("txtCustomerID");
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
			$objTM["transactionNumber"]             = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_transaction_master_cxp_notacredito", 0);
			$objTM["transactionID"]                 = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_cxp_notacredito", 0);
			$objTM["branchID"]                      = $branchID;
			$objTM["transactionCausalID"]           = $this->core_web_transaction->getDefaultCausalID($companyID, $objTM["transactionID"]);
			$objTM["entityID"]                      = $entityID;
			$objTM["transactionOn"]                 = /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["componentID"]                   = $objComponentNoteCredit->componentID;
			$objTM["note"]                          = /*inicio get post*/ $this->request->getPost("txtComment");
			$objTM["currencyID"]                    = $currencyID;
			$objTM["exchangeRate"]                  = $exchangeRate;
			$objTM["reference1"]                    = /*inicio get post*/ $this->request->getPost("txtRef1");
			$objTM["reference2"]                    = /*inicio get post*/ $this->request->getPost("txtRef2");
			$objTM["reference3"]                    = /*inicio get post*/ $this->request->getPost("txtRef3");
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
				$this->response->redirect(base_url() . "/" . 'app_cxp_notecredit/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->$db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_cxp_notecredit/add');
			}
		} catch (\Exception $ex) {
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
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}



			$objComponentNoteCredit        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_cxp_notacredito");
			if (!$objComponentNoteCredit)
				throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_cxp_notacredito' NO EXISTE...");


			//Obtener el componente de Item
			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if (!$objComponentCustomer) {
				throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			}

			$companyID                              = $dataSession["user"]->companyID;
			$branchID                               = $dataSession["user"]->branchID;
			$roleID                                 = $dataSession["role"]->roleID;

			$entityID                               = $this->request->getPost("txtCustomerID");
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
			$objTMNew["reference1"]                 = /*inicio get post*/ $this->request->getPost("txtRef1");
			$objTMNew["reference2"]                 = /*inicio get post*/ $this->request->getPost("txtRef2");
			$objTMNew["reference3"]                 = /*inicio get post*/ $this->request->getPost("txtRef3");
			$objTMNew["statusID"]                   = /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"]                     = $monto;
			$objTMNew["discount"]					= 0;
			$objTMNew["subAmount"]					= 0;
			$objTMNew["isActive"]                   = 1;

			//Validar si el estado permite editar
			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_cxp_notacredito", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new \Exception(NOT_WORKFLOW_EDIT);

			$db = db_connect();
			$db->transStart();

			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_cxp_notacredito", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->request->getPost("txtStatusID");
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			} else {
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			}


			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_cxp_notacredito", "statusID", $objTMNew["statusID"], COMMAND_APLICABLE, $companyID, $branchID, $roleID)) {

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
				$this->response->redirect(base_url() . "/" . 'app_cxp_notecredit/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->$db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_cxp_notecredit/add');
			}
		} catch (\Exception $ex) {
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
}
