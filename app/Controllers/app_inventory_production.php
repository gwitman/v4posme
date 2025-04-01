<?php

namespace App\Controllers;

use Exception;

class app_inventory_production extends _BaseController
{
	function index($dataViewID = null)
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession        			= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {

				$permited 					= false;
				$permited 					= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission        	= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission     	== PERMISSION_NONE)
					throw new Exception(NOT_ACCESS_FUNCTION);
			}

			$objComponent        			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_production_orden");
			if (!$objComponent)
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_production_orden' NO EXISTE...");


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
				$dataViewData                   = $this->core_web_view->getViewByName($this->session->get('user'), $objComponent->componentID, "LISTA DE ORDENES DE PRODUCCION", CALLERID_LIST, $resultPermission, $parameter);
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
			$dataSession["head"]                = /*--inicio view*/ view('app_inventory_production/list_head'); //--finview
			$dataSession["footer"]              = /*--inicio view*/ view('app_inventory_production/list_footer'); //--finview
			$dataSession["body"]                = $dataViewRender;
			$dataSession["script"]              = /*--inicio view*/ view('app_inventory_production/list_script'); //--finview
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

			echo $resultView;
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
			$this->validation->setRule("txtDate", "Fecha", "required");
			$this->validation->setRule("txtTransactionTotalAmount", "Monto Total", "required");
			$this->validation->setRule("txtItemInputID", "Insumos", "required");
			$this->validation->setRule("txtItemOutputID","Productos Resultantes", "required");

			//Validar Formulario
			if (!$this->validation->withRequest($this->request)->run()) {
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true, $stringValidation);
				$this->response->redirect(base_url() . "/" . 'app_inventory_production/add');
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
				$this->response->redirect(base_url() . "/" . 'app_inventory_production/add');
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
			$dataSession        			= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited 					= false;
				$permited 					= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission        	= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission     	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}

			$companyID      = $dataSession["user"]->companyID;
			$branchID       = $dataSession["user"]->branchID;
			$roleID         = $dataSession["role"]->roleID;

			$objComponentProductionOrder        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_production_orden");
			if (!$objComponentProductionOrder) {
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_production_orden' NO EXISTE...");
			}

			$objComponentWarehouse    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_warehouse");
			if (!$objComponentWarehouse) {
				throw new Exception("EL COMPONENTE 'tb_warehouse' NO EXISTE...");
			}

			$objComponentItem    				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem) {
				throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			}

			$objComponentEmployee    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentEmployee) {
				throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			}

			$dataView["company"]                                = $dataSession["company"];
			$dataView["objComponentWarehouse"]                 	= $objComponentWarehouse;
			$dataView["objComponentItem"]                 	    = $objComponentItem;
			$dataView["objComponentEmployee"]                 	= $objComponentEmployee;
			$dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_production_orden", "statusID", $companyID, $branchID, $roleID);
			$dataView["objListWarehouse"] 						= $this->Warehouse_Model->getByCompany($companyID);
			$dataView["objListCurrency"]						= $this->Company_Currency_Model->getByCompany($companyID);
            $dataView["objCurrency"]                            = $this->core_web_currency->getCurrencyDefault($companyID);
			$objListComanyParameter								= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]			= $objParameterCantidadItemPoup->value;

			//Renderizar Resultado 
			$dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]             = $this->core_web_notification->get_message();
			$dataSession["head"]                = /*--inicio view*/ view('app_inventory_production/news_head', $dataView); //--finview
			$dataSession["body"]                = /*--inicio view*/ view('app_inventory_production/news_body', $dataView); //--finview
			$dataSession["script"]              = /*--inicio view*/ view('app_inventory_production/news_script', $dataView); //--finview
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

			echo $resultView;
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

			$objComponentProductionOrder        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_production_orden");
			if (!$objComponentProductionOrder) {
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_production_orden' NO EXISTE...");
			}

			$objComponentWarehouse    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_warehouse");
			if (!$objComponentWarehouse) {
				throw new Exception("EL COMPONENTE 'tb_warehouse' NO EXISTE...");
			}

			$objComponentItem    				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem) {
				throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			}

			$objComponentEmployee    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentEmployee) {
				throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			}

			$companyID                              = $dataSession["user"]->companyID;
			$branchID                               = $dataSession["user"]->branchID;

			$objTM["companyID"]                     = $companyID;
			$objTM["transactionNumber"]             = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_transaction_master_production_orden", 0);
			$objTM["transactionID"]                 = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_production_orden", 0);
			$objTM["branchID"]                      = $branchID;
			$objTM["transactionCausalID"]           = $this->core_web_transaction->getDefaultCausalID($companyID, $objTM["transactionID"]);
			$objTM["entityID"]                      = /*inicio get post*/ $this->request->getPost("txtRequestEmployeeID");
			$objTM["transactionOn"]                 = /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["componentID"]                   = $objComponentProductionOrder->componentID;
			$objTM["reference1"]                    = /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTM["reference2"]                    = /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTM["reference3"]                    = /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTM["note"]                          = /*inicio get post*/ $this->request->getPost("txtNote");
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
			$objTM["statusID"]                      = /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["amount"]						= /*inicio get post*/ $this->request->getPost("txtTransactionTotalAmount");
			$objTM["entityIDSecondary"]             = /*inicio get post*/ $this->request->getPost("txtSenderEmployeeID");
			$objTM["isActive"]                      = 1;
			$this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

			$db = db_connect();
			$db->transStart();
			$transactionMasterID                    = $this->Transaction_Master_Model->insert_app_posme($objTM);

			//Lista de Insumos
			$objListItemInputID						= /*inicio get post*/ $this->request->getPost("txtItemInputID");
			$objListItemInputQuantity				= /*inicio get post*/ $this->request->getPost("txtItemInputQuantity");
			$objListItemInputUnitaryCost			= /*inicio get post*/ $this->request->getPost("txtItemInputUnitaryCost");
			$objListItemInputTotalCost				= /*inicio get post*/ $this->request->getPost("txtItemInputTotalCost");
			$objListItemInputWarehouseSource		= /*inicio get post*/ $this->request->getPost("txtItemInputWarehouseSourceID");
			$objListItemInputProductDestination		= /*inicio get post*/ $this->request->getPost("txtItemInputProductDestinationID");

			//Lista de productos resultantes
			$objListItemOutputID					= /*inicio get post*/ $this->request->getPost("txtItemOutputID");
			$objListItemOutputQuantity				= /*inicio get post*/ $this->request->getPost("txtItemOutputQuantity");
			$objListItemOutputUnitaryCost			= /*inicio get post*/ $this->request->getPost("txtItemOutputUnitaryCost");
			$objListItemOutputTotalCost				= /*inicio get post*/ $this->request->getPost("txtItemOutputTotalCost");
			$objListItemOutputWarehouseTargetID		= /*inicio get post*/ $this->request->getPost("txtItemOutputWarehouseTargetID");


			if (!empty($objListItemInputID)) {
				foreach ($objListItemInputID as $key => $value) {
					$objTMDInput["companyID"]					= $companyID;
					$objTMDInput["transactionID"]				= $objTM["transactionID"];
					$objTMDInput["transactionMasterID"]			= $transactionMasterID;
					$objTMDInput["componentID"]					= $objComponentProductionOrder->componentID;
					$objTMDInput["componentItemID"]				= $value;
					$objTMDInput["quantity"]					= $objListItemInputQuantity[$key];
					$objTMDInput["unitaryCost"]					= $objListItemInputUnitaryCost[$key];
					$objTMDInput["amount"]						= $objListItemInputTotalCost[$key];
					$objTMDInput["inventoryWarehouseSourceID"]	= $objListItemInputWarehouseSource[$key];
					$objTMDInput["skuCatalogItemID"]			= $objListItemInputProductDestination[$key];
					$objTMDInput["isActive"]					= 1;
					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMDInput);
				}
			}

			if (!empty($objListItemOutputID)) {
				foreach ($objListItemOutputID as $key => $value) {
					$objTMDOutput["companyID"]					= $companyID;
					$objTMDOutput["transactionID"]				= $objTM["transactionID"];
					$objTMDOutput["transactionMasterID"]		= $transactionMasterID;
					$objTMDOutput["componentID"]				= $objComponentProductionOrder->componentID;
					$objTMDOutput["componentItemID"]			= $value;
					$objTMDOutput["quantity"]					= $objListItemOutputQuantity[$key];
					$objTMDOutput["unitaryCost"]				= $objListItemOutputUnitaryCost[$key];
					$objTMDOutput["amount"]						= $objListItemOutputTotalCost[$key];
					$objTMDOutput["inventoryWarehouseTargetID"]	= $objListItemOutputWarehouseTargetID[$key];
					$objTMDOutput["isActive"]					= 1;
					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMDOutput);
				}
			}

			//Crear la Carpeta para almacenar los Archivos del Cliente			
			if (!file_exists(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentProductionOrder->componentID . "/component_item_" . $transactionMasterID)) {
				mkdir(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentProductionOrder->componentID . "/component_item_" . $transactionMasterID, 0700, true);
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_inventory_production/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->$db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_inventory_production/add');
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

	function edit()
	{
		try {
			if (!$this->core_web_authentication->isAuthenticated()) {
				throw new Exception(USER_NOT_AUTENTICATED);
			}

			$dataSession    = $this->session->get();

			if (APP_NEED_AUTHENTICATION == true) {
				$permited 	= false;
				$permited 	= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}

			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri
			$branchID				= $dataSession["user"]->branchID;
			$roleID					= $dataSession["role"]->roleID;

			$objComponentProductionOrder        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_production_orden");
			if (!$objComponentProductionOrder) {
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_production_orden' NO EXISTE...");
			}

			$objComponentWarehouse    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_warehouse");
			if (!$objComponentWarehouse) {
				throw new Exception("EL COMPONENTE 'tb_warehouse' NO EXISTE...");
			}

			$objComponentItem    				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem) {
				throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			}

			$objComponentEmployee    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentEmployee) {
				throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			}

			$dataView["objTM"]                  				= $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
			$dataView["objTMD"]									= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentProductionOrder->componentID);
			$dataView["company"]                                = $dataSession["company"];
			$dataView["objComponentWarehouse"]                 	= $objComponentWarehouse;
			$dataView["objComponentItem"]                 	    = $objComponentItem;
			$dataView["objComponentProductionOrder"]			= $objComponentProductionOrder;
			$dataView["objComponentEmployee"]                 	= $objComponentEmployee;
			$dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_production_orden", "statusID", $companyID, $branchID, $roleID);
			$dataView["objRequestEmployee"]						= $dataView["objTM"]->entityID 			? $this->Employee_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->entityID) 			: "";
			$dataView["objSenderEmployee"]                   	= $dataView["objTM"]->entityIDSecondary ? $this->Employee_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->entityIDSecondary) 	: "";
			$dataView["objRequestEmployeeNatural"]				= $dataView["objTM"]->entityID 			? $this->Natural_Model->get_rowByPk($companyID, $branchID, $dataView["objTM"]->entityID) 			: "";
			$dataView["objSenderEmployeeNatural"]				= $dataView["objTM"]->entityIDSecondary ? $this->Natural_Model->get_rowByPk($companyID, $branchID, $dataView["objTM"]->entityIDSecondary) 	: "";
			$dataView["objListWarehouse"]						= $this->Warehouse_Model->getByCompany($companyID);
			$dataView["objListCurrency"]						= $this->Company_Currency_Model->getByCompany($companyID);
			$objListComanyParameter								= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]			= $objParameterCantidadItemPoup->value;
			
			//RENDERIZAR RESULTADO
			$dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]         = $this->core_web_notification->get_message();
			$dataSession["head"]            = view('app_inventory_production/edit_head', $dataView);
			$dataSession["body"]            = view('app_inventory_production/edit_body', $dataView);
			$dataSession["script"]          = view('app_inventory_production/edit_script', $dataView);
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



			$objComponentProductionOrder        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_production_orden");
			if (!$objComponentProductionOrder) {
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_production_orden' NO EXISTE...");
			}

			$objComponentWarehouse    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_warehouse");
			if (!$objComponentWarehouse) {
				throw new Exception("EL COMPONENTE 'tb_warehouse' NO EXISTE...");
			}

			$objComponentItem   				 = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem) {
				throw new Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			}

			$objComponentEmployee    			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentEmployee) {
				throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			}


			$companyID                              = $dataSession["user"]->companyID;
			$branchID                               = $dataSession["user"]->branchID;
			$roleID                                 = $dataSession["role"]->roleID;
			$userID									= $dataSession["user"]->userID;
			$authorization							= $resultPermission;

			$transactionID                          = /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID                    = /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");

			$objTM                                  = $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
			$objTMNew["entityID"]					= /*inicio get post*/ $this->request->getPost("txtRequestEmployeeID");
			$objTMNew["transactionOn"]				= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["reference1"]					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTMNew["reference2"]					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTMNew["reference3"]					= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTMNew["note"]						= /*inicio get post*/ $this->request->getPost("txtNote");
			$objTMNew["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
			$objTMNew["statusID"]					= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"]						= /*inicio get post*/ $this->request->getPost("txtTransactionTotalAmount");
			$objTMNew["entityIDSecondary"]			= /*inicio get post*/ $this->request->getPost("txtSenderEmployeeID");
			$objTMNew["isActive"]                   = 1;

			//Validar si el estado permite editar
			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_production_orden", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new Exception(NOT_WORKFLOW_EDIT);

			$db = db_connect();
			$db->transStart();

			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_production_orden", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->request->getPost("txtStatusID");
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			} else {
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			}

			$objListTransactionMasterDetailID		= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDetailID");

			//Lista de Insumos
			$objListItemInputID						= /*inicio get post*/ $this->request->getPost("txtItemInputID");
			$objListItemInputQuantity				= /*inicio get post*/ $this->request->getPost("txtItemInputQuantity");
			$objListItemInputUnitaryCost			= /*inicio get post*/ $this->request->getPost("txtItemInputUnitaryCost");
			$objListItemInputTotalCost				= /*inicio get post*/ $this->request->getPost("txtItemInputTotalCost");
			$objListItemInputWarehouseSource		= /*inicio get post*/ $this->request->getPost("txtItemInputWarehouseSourceID");
			$objListItemInputProductDestination		= /*inicio get post*/ $this->request->getPost("txtItemInputProductDestinationID");

			//Lista de productos resultantes
			$objListItemOutputID					= /*inicio get post*/ $this->request->getPost("txtItemOutputID");
			$objListItemOutputQuantity				= /*inicio get post*/ $this->request->getPost("txtItemOutputQuantity");
			$objListItemOutputUnitaryCost			= /*inicio get post*/ $this->request->getPost("txtItemOutputUnitaryCost");
			$objListItemOutputTotalCost				= /*inicio get post*/ $this->request->getPost("txtItemOutputTotalCost");
			$objListItemOutputWarehouseTargetID		= /*inicio get post*/ $this->request->getPost("txtItemOutputWarehouseTargetID");

			if ($objListTransactionMasterDetailID == null) {
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID, $transactionID, $transactionMasterID);
			} else {
				$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID, $transactionID, $transactionMasterID, $objListTransactionMasterDetailID);
			}

			if (!empty($objListTransactionMasterDetailID)) 
			{
				$itemInputListLength 	= count($objListItemInputID);
				$itemOutputListLength 	= count($objListItemOutputID);

				$itemInputListCounter	= 0;
				$itemOutputListCounter 	= 0;

				foreach ($objListTransactionMasterDetailID as $key => $transactionMasterDetailID) 
				{	
					$objTMDNew 			= [];
					if (empty($transactionMasterDetailID)) 
					{
						if ($itemInputListCounter < $itemInputListLength && !empty($objListItemInputWarehouseSource[$itemInputListCounter])) 
						{
							$objTMDNew["companyID"]						= $companyID;
							$objTMDNew["transactionID"]					= $objTM->transactionID;
							$objTMDNew["transactionMasterID"]			= $transactionMasterID;
							$objTMDNew["componentID"]					= $objComponentProductionOrder->componentID;
							$objTMDNew["componentItemID"]				= $objListItemInputID[$itemInputListCounter];
							$objTMDNew["quantity"]						= $objListItemInputQuantity[$itemInputListCounter];
							$objTMDNew["unitaryCost"]					= $objListItemInputUnitaryCost[$itemInputListCounter];
							$objTMDNew["amount"]						= $objListItemInputTotalCost[$itemInputListCounter];
							$objTMDNew["inventoryWarehouseSourceID"]	= $objListItemInputWarehouseSource[$itemInputListCounter];
							$objTMDNew["skuCatalogItemID"]				= $objListItemInputProductDestination[$itemInputListCounter];
							$objTMDNew["isActive"]						= 1;
							$itemInputListCounter++;
						} 
						else if ($itemOutputListCounter < $itemOutputListLength && !empty($objListItemOutputWarehouseTargetID[$itemOutputListCounter])) 
						{
							$objTMDNew["companyID"]						= $companyID;
							$objTMDNew["transactionID"]					= $objTM->transactionID;
							$objTMDNew["transactionMasterID"]			= $transactionMasterID;
							$objTMDNew["componentID"]					= $objComponentProductionOrder->componentID;
							$objTMDNew["componentItemID"]				= $objListItemOutputID[$itemOutputListCounter];
							$objTMDNew["quantity"]						= $objListItemOutputQuantity[$itemOutputListCounter];
							$objTMDNew["unitaryCost"]					= $objListItemOutputUnitaryCost[$itemOutputListCounter];
							$objTMDNew["amount"]						= $objListItemOutputTotalCost[$itemOutputListCounter];
							$objTMDNew["inventoryWarehouseTargetID"]	= $objListItemOutputWarehouseTargetID[$itemOutputListCounter];
							$objTMDNew["isActive"]						= 1;
							$itemOutputListCounter++;
						}

						if (!empty($objTMDNew)) {
							$this->Transaction_Master_Detail_Model->insert_app_posme($objTMDNew);
						}
					} 
					else 
					{
						if ($itemInputListCounter < $itemInputListLength && !empty($objListItemInputWarehouseSource[$itemInputListCounter])) 
						{
							$objTMDNew["componentItemID"]				= $objListItemInputID[$itemInputListCounter];
							$objTMDNew["quantity"]						= $objListItemInputQuantity[$itemInputListCounter];
							$objTMDNew["unitaryCost"]					= $objListItemInputUnitaryCost[$itemInputListCounter];
							$objTMDNew["amount"]						= $objListItemInputTotalCost[$itemInputListCounter];
							$objTMDNew["inventoryWarehouseSourceID"]	= $objListItemInputWarehouseSource[$itemInputListCounter];
							$objTMDNew["skuCatalogItemID"]				= $objListItemInputProductDestination[$itemInputListCounter];
							$itemInputListCounter++;
						} 
						else if ($itemOutputListCounter < $itemOutputListLength && !empty($objListItemOutputWarehouseTargetID[$itemOutputListCounter])) 
						{
							$objTMDNew["componentItemID"]				= $objListItemOutputID[$itemOutputListCounter];
							$objTMDNew["quantity"]						= $objListItemOutputQuantity[$itemOutputListCounter];
							$objTMDNew["unitaryCost"]					= $objListItemOutputUnitaryCost[$itemOutputListCounter];
							$objTMDNew["amount"]						= $objListItemOutputTotalCost[$itemOutputListCounter];
							$objTMDNew["inventoryWarehouseTargetID"]	= $objListItemOutputWarehouseTargetID[$itemOutputListCounter];
							$itemOutputListCounter++;
						}

						if (!empty($objTMDNew)) {
							$this->Transaction_Master_Detail_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $transactionMasterDetailID, $objTMDNew);
						}
					}
				}
			}

			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_production_orden", "statusID", $objTMNew["statusID"], COMMAND_APLICABLE, $companyID, $branchID, $roleID)) 
			{
				$transactionID					= $objTM->transactionID;
				$objListWarehouseSourceID		= array_unique($objListItemInputWarehouseSource);
				$objListWarehouseTargetID		= array_unique($objListItemOutputWarehouseTargetID);

				foreach($objListWarehouseSourceID as $WarehouseSourceID)
				{
					$query			= "CALL pr_inventory_create_transaction_otheroutput_by_production(".$companyID.",".$branchID.",".$userID.",".$transactionID.",".$transactionMasterID.",".$WarehouseSourceID.");";
					$objData		= $this->Bd_Model->executeRender($query,NULL);
				}
				
				foreach($objListWarehouseTargetID as $WarehouseTargetID)
				{
					$query			= "CALL pr_inventory_create_transaction_otherinput_by_production(".$companyID.",".$branchID.",".$userID.",".$transactionID.",".$transactionMasterID.",".$WarehouseTargetID.");";
					$objData		= $this->Bd_Model->executeRender($query,NULL);
				}

				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_inventory_production/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->$db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_inventory_production/add');
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
			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_production_orden", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
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

			$objTM 				= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID, $transactionNumber);

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
			$dataSession					= $this->session->get();

			//PERMISO SOBRE LA FUNCION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited 					= false;
				$permited 					= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);


				$resultPermission			= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 		== PERMISSION_NONE)
					throw new Exception(NOT_ALL_EDIT);
			}

			$objComponentProductionOrder	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_production_orden");
			if (!$objComponentProductionOrder) {
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_production_orden' NO EXISTE...");
			}

			$transactionID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri			
			$transactionMasterID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri				
			$companyID 						= $dataSession["user"]->companyID;
			$branchID 						= $dataSession["user"]->branchID;
			$roleID 						= $dataSession["role"]->roleID;

			$objCompany 			    	= $this->Company_Model->get_rowByPK($companyID);
			$objParameterTelefono	    	= $this->core_web_parameter->getParameter("CORE_PHONE", $companyID);
			$objParameterLogo	        	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
			$objParameterRuc 				= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER", $companyID)->value;

			$objTM							= $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
			$objTMD							= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentProductionOrder->componentID);
			$objTM->transactionOn			= date_format(date_create($objTM->transactionOn), "Y-m-d");
			$objWorkflowStage				= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($objTM->statusID)[0]->name;
			$objCurrency					= $this->Currency_Model->get_rowByPK($objTM->currencyID);

			$objRequestEmployee				= $objTM->entityID 			? $this->Employee_Model->get_rowByPK($companyID, $branchID, $objTM->entityID) 			: "";
			$objSenderEmployee				= $objTM->entityIDSecondary ? $this->Employee_Model->get_rowByPK($companyID, $branchID, $objTM->entityIDSecondary) 	: "";

			$objRequestEmployeeNatural		= $objTM->entityID 			? $this->Natural_Model->get_rowByPk($companyID, $branchID, $objTM->entityID) 			: "";
			$objSenderEmployeeNatural		= $objTM->entityIDSecondary ? $this->Natural_Model->get_rowByPk($companyID, $branchID, $objTM->entityIDSecondary) 	: "";

			$objRequestEmployeeName			= $objRequestEmployee ? ($objRequestEmployee->employeNumber . " | " . $objRequestEmployeeNatural->firstName . " " . $objRequestEmployeeNatural->lastName) 	: "";
			$objSenderEmployeeName			= $objSenderEmployee  ? ($objSenderEmployee->employeNumber 	. " | " . $objSenderEmployeeNatural->firstName 	. " " . $objSenderEmployeeNatural->lastName) 	: "";
			

			//Generar Reporte
			$html = helper_reporteA4ProductionOrder(
				"",
				$objCompany,
				$objParameterTelefono,
				$objParameterLogo,
				$objParameterRuc,
				$objTM,
				$objWorkflowStage,
				$objTMD,
				$objCurrency,
				$objRequestEmployeeName,
				$objSenderEmployeeName,
			);

			// echo $html;

			$this->dompdf->loadHTML($html);

			$this->dompdf->render();

			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;

			if ($objParameterShowLinkDownload == "true") {
				$fileNamePut = "factura_" . $transactionMasterID . "_" . date("dmYhis") . ".pdf";
				$path        = "./resource/file_company/company_" . $companyID . "/component_112/component_item_" . $transactionMasterID . "/" . $fileNamePut;

				file_put_contents(
					$path,
					$this->dompdf->output()
				);

				chmod($path, 644);

				echo "<a 
					href='" . base_url() . "/resource/file_company/company_" . $companyID . "/component_112/component_item_" . $transactionMasterID . "/" .
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

			echo $resultView;
		}
	}
}
