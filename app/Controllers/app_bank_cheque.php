<?php

namespace App\Controllers;

use Exception;

class app_bank_cheque extends _BaseController
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

            $objComponent        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank_cheque");
            if (!$objComponent)
                throw new Exception("00409 EL COMPONENTE 'tb_bank_cheque' NO EXISTE...");


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
                $dataViewData                   = $this->core_web_view->getViewByName($this->session->get('user'), $objComponent->componentID, "LISTA DE CHEQUES", CALLERID_LIST, $resultPermission, $parameter);
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
            $dataSession["head"]                = /*--inicio view*/ view('app_bank_cheque/list_head'); //--finview
            $dataSession["footer"]              = /*--inicio view*/ view('app_bank_cheque/list_footer'); //--finview
            $dataSession["body"]                = $dataViewRender;
            $dataSession["script"]              = /*--inicio view*/ view('app_bank_cheque/list_script'); //--finview
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
            $dataSession        = $this->session->get();

            //Validar Formulario						
            $this->validation->setRule("txtEmployeeEntityID", "Empleado", "required");
            $this->validation->setRule("txtInitialValue", "Valor Inicial", "required");
            $this->validation->setRule("txtFinalValue", "Valor Final", "required");

            //Validar Formulario
            if (!$this->validation->withRequest($this->request)->run()) {
                $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_bank_cheque/add');
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
                $this->response->redirect(base_url() . "/" . 'app_bank_cheque/add');
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

            $objComponentBank        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank");
            if (!$objComponentBank)
                throw new Exception("00409 EL COMPONENTE 'tb_bank' NO EXISTE...");


            //Obtener el componente de Item
            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee)
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");


            $objCurrency                                        = $this->core_web_currency->getCurrencyDefault($companyID);
            $dataView["company"]                                = $dataSession["company"];
            $dataView["objComponentBank"]                       = $objComponentBank;
            $dataView["objComponentEmployee"]                   = $objComponentEmployee;
            $dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowInitStage("tb_bank_cheque", "statusID", $companyID, $branchID, $roleID);
            $dataView["objListEmployee"]                        = $this->Employee_Model->get_rowByBranchID($companyID,$branchID);
            $dataView["objListCurrency"]                        = $this->Company_Currency_Model->getByCompany($companyID);
            $dataView["objListCurrencyDefault"]                 = $this->core_web_currency->getCurrencyDefault($companyID);
            $dataView["objCurrency"]                            = $objCurrency;

            //Renderizar Resultado 
            $dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]             = $this->core_web_notification->get_message();
            $dataSession["head"]                = /*--inicio view*/ view('app_bank_cheque/news_head', $dataView); //--finview
            $dataSession["body"]                = /*--inicio view*/ view('app_bank_cheque/news_body', $dataView); //--finview
            $dataSession["script"]              = /*--inicio view*/ view('app_bank_cheque/news_script', $dataView); //--finview
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

    function insertElement($dataSession)
    {
        try {
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



            $objComponentBank        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank_cheque");
            if (!$objComponentBank)
                throw new Exception("00409 EL COMPONENTE 'tb_bank_cheque' NO EXISTE...");


            //Obtener el componente de Item
            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee) {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            $companyID                              = $dataSession["user"]->companyID;
            $branchID                               = $dataSession["user"]->branchID;

            $objBC["chequeNumber"]                = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_bank_cheque", 0);
            $objBC["name"]                        = /*inicio get post*/ $this->request->getPost("txtName");
            $objBC["statusID"]                    = /*inicio get post*/ $this->request->getPost("txtStatusID");
            $objBC["bankID"]                      = /*inicio get post*/ $this->request->getPost("txtBankID");
            $objBC["currencyID"]                  = /*inicio get post*/ $this->request->getPost("txtCurrencyID");
            $objBC["valueInitial"]                = /*inicio get post*/ $this->request->getPost("txtInitialValue");
            $objBC["valueCurrent"]                = /*inicio get post*/ $this->request->getPost("txtCurrentValue");
            $objBC["valueFinal"]                  = /*inicio get post*/ $this->request->getPost("txtFinalValue");
            $objBC["serie"]                       = /*inicio get post*/ $this->request->getPost("txtSerie");
            $objBC["managerID"]                   = /*inicio get post*/ $this->request->getPost("txtEmployeeEntityID");
            $objBC["isActive"]                    = 1;

            $db = db_connect();
            $db->transStart();
            $chequeID                               = $this->Bank_Cheque_Model->insert_app_posme($objBC);

            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_bank_cheque/edit/chequeID/' . $chequeID);
            } else {
                $db->transRollback();
                $this->core_web_notification->set_message(true, $db->error());
                $this->response->redirect(base_url() . "/" . 'app_bank_cheque/add');
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

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_INSERT);
            }

            $chequeID                   = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "chequeID"); //--finuri
            $branchID                   = $dataSession["user"]->branchID;
            $companyID                  = $dataSession["user"]->companyID;

            $objComponentBank        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank");
            if (!$objComponentBank)
                throw new Exception("00409 EL COMPONENTE 'tb_bank' NO EXISTE...");


            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee)
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");



            if (!$chequeID) {
                $this->response->redirect(base_url() . "/" . 'app_bank_cheque/add');
            }

            $dataView["objBC"]                  = $this->Bank_Cheque_Model->get_rowByPK($chequeID);
            $dataView["objEmployee"]            = $this->Employee_Model->get_rowByPK($companyID,$branchID,$dataView["objBC"]->managerID);
            $dataView["objNatural"]             = $this->Natural_Model->get_rowByPk($companyID, $branchID, $dataView["objBC"]->managerID);
            $dataView["objComponentEmployee"]   = $objComponentEmployee;
            $dataView["objComponentBank"]       = $objComponentBank;
            $dataView["objListCurrency"]        = $this->Company_Currency_Model->getByCompany($companyID);
            $dataView["objListWorkflowStage"]   = $this->core_web_workflow->getWorkflowAllStage("tb_bank_cheque", "statusID", $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID);
            $dataView["objBank"]                = $this->Bank_Model->get_rowByPK($companyID,$dataView["objBC"]->bankID);

            //RENDERIZAR RESULTADO
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = view('app_bank_cheque/edit_head', $dataView);
            $dataSession["body"]            = view('app_bank_cheque/edit_body', $dataView);
            $dataSession["script"]          = view('app_bank_cheque/edit_script', $dataView);
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

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_EDIT);
            }



            $objComponentBankCheque        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_bank_cheque");
            if (!$objComponentBankCheque)
                throw new Exception("00409 EL COMPONENTE 'tb_bank_cheque' NO EXISTE...");


            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee) {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            $companyID                              = $dataSession["user"]->companyID;
            $branchID                               = $dataSession["user"]->branchID;
            $roleID                                 = $dataSession["role"]->roleID;

            $chequeID                               = $this->request->getPost("txtChequeID");
            $objBC                                  = $this->Bank_Cheque_Model->get_rowByPK($chequeID);

            $objBCNew["name"]                        = /*inicio get post*/ $this->request->getPost("txtName");
            $objBCNew["statusID"]                    = /*inicio get post*/ $this->request->getPost("txtStatusID");
            $objBCNew["bankID"]                      = /*inicio get post*/ $this->request->getPost("txtBankID");
            $objBCNew["currencyID"]                  = /*inicio get post*/ $this->request->getPost("txtCurrencyID");
            $objBCNew["valueInitial"]                = /*inicio get post*/ $this->request->getPost("txtInitialValue");
            $objBCNew["valueCurrent"]                = /*inicio get post*/ $this->request->getPost("txtCurrentValue");
            $objBCNew["valueFinal"]                  = /*inicio get post*/ $this->request->getPost("txtFinalValue");
            $objBCNew["serie"]                       = /*inicio get post*/ $this->request->getPost("txtSerie");
            $objBCNew["managerID"]                   = /*inicio get post*/ $this->request->getPost("txtEmployeeEntityID");

            //Validar si el estado permite editar
            if (!$this->core_web_workflow->validateWorkflowStage("tb_bank_cheque", "statusID", $objBC->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new Exception(NOT_WORKFLOW_EDIT);

            $db = db_connect();
            $db->transStart();

            if ($this->core_web_workflow->validateWorkflowStage("tb_bank_cheque", "statusID", $objBC->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
                $objBCNew                                = array();
                $objBCNew["statusID"]                     = $this->request->getPost("txtStatusID");
                $this->Bank_Cheque_Model->update_app_posme($chequeID, $objBCNew);
            } else {
                $this->Bank_Cheque_Model->update_app_posme($chequeID, $objBCNew);
            }


            if ($this->core_web_workflow->validateWorkflowStage("tb_bank_cheque", "statusID", $objBCNew["statusID"], COMMAND_APLICABLE, $companyID, $branchID, $roleID)) {
                $this->Bank_Cheque_Model->update_app_posme($chequeID, $objBCNew);
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_bank_cheque/edit/chequeID/' . $chequeID);
            } else {
                $db->transRollback();
                $this->core_web_notification->set_message(true, $db->error());
                $this->response->redirect(base_url() . "/" . 'app_bank_cheque/add');
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
            $dataSession        = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_DELETE);
            }

            $chequeID     = /*inicio get post*/ $this->request->getPost("chequeID");


            if (!$chequeID) {
                throw new Exception(NOT_PARAMETER);
            }

            $objBC                     = $this->Bank_Cheque_Model->get_rowByPK($chequeID);
            if ($resultPermission     == PERMISSION_ME)
                throw new Exception(NOT_DELETE);

            //Si el documento esta aplicado crear el contra documento			
            if (!$this->core_web_workflow->validateWorkflowStage("tb_bank_cheque", "statusID", $objBC->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new Exception(NOT_WORKFLOW_DELETE);

            //Eliminar el Registro			
            $this->Bank_Cheque_Model->delete_app_posme($chequeID);

            return $this->response->setJSON(array(
                'error'   => false,
                'message' => SUCCESS
            )); //--finjson
        } catch (Exception $ex) {

            // $this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
            return $this->response->setJSON(array(
                'error'   => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            )); //--finjson
        }
    }

    function searchBankCheque()
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

            $chequeNumber     = /*inicio get post*/ $this->request->getPost("chequeNumber");

            if (!$chequeNumber) {
                throw new Exception(NOT_PARAMETER);
            }

            $objBC     = $this->Bank_Cheque_Model->get_rowByChequeNumber($chequeNumber);

            if (!$objBC)
                throw new Exception("NO SE ENCONTRO EL DOCUMENTO");

            return $this->response->setJSON(array(
                'error'         => false,
                'message'       => SUCCESS,
                'chequeID'      => $objBC->chequeID
            )); //--finjson

        } catch (Exception $ex) {

            return $this->response->setJSON(array(
                'error'   => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            )); //--finjson
        }
    }
}
