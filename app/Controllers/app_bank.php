<?php
//posme:2023-02-27
namespace App\Controllers;

class app_bank extends _BaseController
{


	function edit()
	{
		try {
			//AUTENTICACION			
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();


			//PERMISO SOBRE LA FUNCION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);
			}

			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////

			//Cargar Libreria
			$objComponentBank        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank");
			if (!$objComponentBank)
				throw new \Exception("EL COMPONENTE 'tb_bank' NO EXISTE...");

			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentCustomer)
				throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");


			//Redireccionar datos

			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
			$bankID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "bankID"); //--finuri	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;
			if ((!$companyID || !$bankID)) {
				$this->response->redirect(base_url() . "/" . 'app_bank/add');
			}


			//Obtener el Registro			
			$dataView["objBank"]								= $this->Bank_Model->get_rowByPK($companyID, $bankID);

			$managerId 											= $dataView["objBank"]->managerID ?? 0;
			//Renderizar Resultado
			$dataView["objCustomer"]            				= $this->Employee_Model->get_rowByPK($companyID, $branchID, $managerId);
			$dataView["objListLegal"]							= $this->Legal_Model->get_rowByPk($companyID, $branchID, $managerId);
			$dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowAllStage("tb_bank", "statusID", $companyID, $branchID, $roleID);
			$dataView["objListCurrency"]                        = $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCurrencyDefault"]                 = $this->core_web_currency->getCurrencyDefault($companyID);
			$dataView["objComponentCustomer"]                   = $objComponentCustomer;
			$dataSession["notification"]						= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]								= $this->core_web_notification->get_message();
			$dataSession["head"]								= /*--inicio view*/ view('app_bank/edit_head', $dataView); //--finview
			$dataSession["body"]								= /*--inicio view*/ view('app_bank/edit_body', $dataView); //--finview
			$dataSession["script"]								= /*--inicio view*/ view('app_bank/edit_script', $dataView); //--finview
			$dataSession["footer"]								= "";
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
	function delete()
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

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_DELETE);
			}

			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////



			//Nuevo Registro
			$companyID 			= /*inicio get post*/ $this->request->getPost("companyID");
			$bankID	 			= /*inicio get post*/ $this->request->getPost("bankID");


			if ((!$companyID && !$bankID)) {
				throw new \Exception(NOT_PARAMETER);
			}

			//OBTENER EL COMPROBANTE
			$obj 			= $this->Bank_Model->get_rowByPK($companyID, $bankID);
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
				throw new \Exception(NOT_DELETE);

			$statusID                               = $obj->statusID;

			if ($statusID) {
				if (!$this->core_web_workflow->validateWorkflowStage("tb_bank", "statusID", $statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
					throw new \Exception(NOT_WORKFLOW_DELETE);
			}

			//Eliminar el Registro
			$this->Bank_Model->delete_app_posme($companyID, $bankID);

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			)); //--finjson


		} catch (\Exception $ex) {

			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine() . " " . $ex->getMessage()
			)); //--finjson
			$this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
		}
	}
	function save($method = NULL)
	{
		$method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
		try {
			//AUTENTICACION
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//Validar Formulario						
			$this->validation->setRule("txtName", "Nombre", "required");

			//Validar Formulario
			if ($this->validation->withRequest($this->request)->run() != true) {
				$stringValidation = str_replace("\n", "", $this->validation->getErrors());
				$this->core_web_notification->set_message(true, $stringValidation);
				$this->response->redirect(base_url() . "/" . 'app_bank/add');
				exit;
			}

			//Nuevo Registro
			if ($method == "new") {
				//PERMISO SOBRE LA FUNCION
				$this->insertElement($dataSession);
			}
			//Editar Registro
			else {
				//PERMISO SOBRE LA FUNCION
				$this->updateElement($dataSession);
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
	function add()
	{

		try {
			//AUTENTICACION
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}

			//Cargar Libreria
			$objComponentBank        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank");

			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;

			$objComponentCustomer    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentCustomer)
				throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

			//Renderizar Resultado 
			$dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowInitStage("tb_bank", "statusID", $companyID, $branchID, $roleID);
			$dataView["objListCurrency"]                        = $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCurrencyDefault"]                 = $this->core_web_currency->getCurrencyDefault($companyID);
			$dataSession["notification"]						= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataView["objComponentCustomer"]                   = $objComponentCustomer;
			$dataSession["message"]								= $this->core_web_notification->get_message();
			$dataSession["head"]								= /*--inicio view*/ view('app_bank/news_head', $dataView); //--finview
			$dataSession["body"]								= /*--inicio view*/ view('app_bank/news_body', $dataView); //--finview
			$dataSession["script"]								= /*--inicio view*/ view('app_bank/news_script', $dataView); //--finview
			$dataSession["footer"]								= "";

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
	function index($dataViewID = null)
	{
		try {

			//AUTENTICACION
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ACCESS_FUNCTION);
			}

			//Obtener el componente Para mostrar la lista de Bodegas
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank");
			if (!$objComponent)
				throw new \Exception("00409 EL COMPONENTE 'tb_bank' NO EXISTE...");


			//Vista por defecto 
			if ($dataViewID == null) {
				$targetComponentID			= 0;
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
			}
			//Otra vista
			else {
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
			}

			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_bank/list_head'); //--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_bank/list_footer'); //--finview
			$dataSession["body"]			= $dataViewRender;
			$dataSession["script"]			= /*--inicio view*/ view('app_bank/list_script'); //--finview
			$dataSession["script"]			= $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
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
			//PERMISO SOBRE LA FUNCION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}

			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");
			$db = db_connect();
			$db->transStart();
			//Crear Cuenta
			$obj["companyID"]			= $dataSession["user"]->companyID;
			$obj["name"] 				= /*inicio get post*/ $this->request->getPost("txtName");
			$obj["isActive"] 			= true;
			$obj["accountID"] 			= 0;
			$obj["balance"] 			= 0;
			$obj["managerID"] 			= $this->request->getPost('txtCustomerEntityID');
			$obj["currencyID"] 			= $this->request->getPost('txtCurrencyID');
			$obj["cardNumber"] 			= $this->request->getPost('txtTarjeta');
			$obj["dateExpired"] 		= $this->request->getPost('txtDate');
			$obj["reference1"] 			= $this->request->getPost('txtReference1');
			$obj["reference2"] 			= $this->request->getPost('txtReference2');
			$obj["statusID"] 			= $this->request->getPost('txtStatusID');
			$obj["note"] 				= $this->request->getPost('txtComment');
			$obj["urlBank"] 			= $this->request->getPost('txtURL');
			$obj["password"]			= $this->request->getPost('txtPassword');
			$obj["pin"]					= $this->request->getPost('txtPin');
			$obj["user"]				= $this->request->getPost('txtUsuario');
			$obj["comisionPos"]		    = $this->request->getPost('txtComisionPos');
			$obj["comisionSave"]		= $this->request->getPost('txtComisionSave');


			//Ingresar
			$companyID 				= $obj["companyID"];
			$bankID					= $this->Bank_Model->insert_app_posme($companyID, $obj);


			//Completar Transaccion
			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_bank/edit/companyID/' . $companyID . "/bankID/" . $bankID);
			} else {
				$db->transRollback();
				$errorCode 		= "001";   //$db->error()["code"];
				$errorMessage 	= "error de bd";	//$db->error()["message"];						
				$this->core_web_notification->set_message(true, $errorMessage);
				$this->response->redirect(base_url() . "/" . 'app_bank/add');
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
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);
			}

			//PERMISO SOBRE EL REGISTRO
			$companyID 			= $dataSession["user"]->companyID;
			$bankID 			= /*inicio get post*/ $this->request->getPost("txtBankID");

			$objTM                                  = $this->Bank_Model->get_rowByPK($companyID, $bankID);
			$statusID                               = $objTM->statusID ?? $this->request->getPost('txtStatusID');

			$obj["companyID"]			= $companyID;
			$obj["name"] 				= /*inicio get post*/ $this->request->getPost("txtName");
			$obj["isActive"] 			= true;
			$obj["accountID"] 			= 0;
			$obj["balance"] 			= 0;
			$obj["managerID"] 			= $this->request->getPost('txtCustomerEntityID');
			$obj["currencyID"] 			= $this->request->getPost('txtCurrencyID');
			$obj["cardNumber"] 			= $this->request->getPost('txtTarjeta');
			$obj["dateExpired"] 		= $this->request->getPost('txtDate');
			$obj["reference1"] 			= $this->request->getPost('txtReference1');
			$obj["reference2"] 			= $this->request->getPost('txtReference2');
			$obj["statusID"] 			= $this->request->getPost('txtStatusID');
			$obj["note"] 				= $this->request->getPost('txtComment');
			$obj["urlBank"] 			= $this->request->getPost('txtURL');
			$obj["password"]			= $this->request->getPost('txtPassword');
			$obj["pin"]					= $this->request->getPost('txtPin');
			$obj["user"]				= $this->request->getPost('txtUsuario');
			$obj["comisionPos"]		    = $this->request->getPost('txtComisionPos');
			$obj["comisionSave"]		= $this->request->getPost('txtComisionSave');
			
			//Validar si el estado permite editar
			if (!$this->core_web_workflow->validateWorkflowStage("tb_bank", "statusID", $statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new \Exception(NOT_WORKFLOW_EDIT);

			//Actualizar Bodega
			$db = db_connect();
			$db->transStart();
			//Actualizar Bodega
			$result 			= $this->Bank_Model->update_app_posme($companyID, $bankID, $obj);

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $db->error()["message"]);
			}

			$this->response->redirect(base_url() . "/" . 'app_bank/edit/companyID/' . $companyID . "/bankID/" . $bankID);
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
