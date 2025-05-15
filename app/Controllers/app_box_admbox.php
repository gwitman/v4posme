<?php
//posme:2025-05-04
namespace App\Controllers;

class app_box_admbox extends _BaseController
{
    public function add()
    {
        try {
            //AUTENTICACION
            if (! $this->core_web_authentication->isAuthenticated()) {
                throw new \Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (! $permited) {
                    throw new \Exception(NOT_ACCESS_CONTROL);
                }

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) {
                    throw new \Exception(NOT_ALL_INSERT);
                }

            }

            //Obtener el componente Para mostrar la lista de AccountType
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_cash_box");
            if (! $objComponent) {
                throw new \Exception("00409 EL COMPONENTE 'tb_cash_box' NO EXISTE...");
            }

            $companyID = $dataSession['user']->companyID;
            $branchID  = $dataSession['user']->branchID;
            $roleID    = $dataSession['role']->roleID;

            $data["objListWorkflowStage"] = $this->core_web_workflow->getWorkflowInitStage("tb_cash_box", "statusID", $companyID, $branchID, $roleID);

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/view('app_box_admbox/news_head', $data);   //--finview
            $dataSession["body"]         = /*--inicio view*/view('app_box_admbox/news_body', $data);   //--finview
            $dataSession["script"]       = /*--inicio view*/view('app_box_admbox/news_script', $data); //--finview
            $dataSession["footer"]       = "";
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

    public function edit($companyID, $boxID)
    {
        try {
            //AUTENTICACION
            if (! $this->core_web_authentication->isAuthenticated()) {
                throw new \Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (! $permited) {
                    throw new \Exception(NOT_ACCESS_CONTROL);
                }

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) {
                    throw new \Exception(NOT_ALL_INSERT);
                }

            }

            //Obtener el componente Para mostrar la lista de AccountType
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_cash_box");
            if (! $objComponent) {
                throw new \Exception("00409 EL COMPONENTE 'tb_cash_box' NO EXISTE...");
            }
            $branchID   = $dataSession['user']->branchID;
            $roleID     = $dataSession['role']->roleID;
            $objCashBox = $this->Cash_Box_Model->get_rowByPK($companyID, $boxID);
            if (is_null($objCashBox)) {
                $this->core_web_notification->set_message(true, 'No existe la caja especificada');
                $this->response->redirect(base_url() . "/" . 'app_box_admbox/index');
                return;
            }

            $data['objCashBox']           = $objCashBox;
            $data["objListWorkflowStage"] = $this->core_web_workflow->getWorkflowStageByStageInit("tb_cash_box", "statusID", $objCashBox->statusID, $companyID, $branchID, $roleID);

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/view('app_box_admbox/edit_head', $data);   //--finview
            $dataSession["body"]         = /*--inicio view*/view('app_box_admbox/edit_body', $data);   //--finview
            $dataSession["script"]       = /*--inicio view*/view('app_box_admbox/edit_script', $data); //--finview
            $dataSession["footer"]       = "";
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

    public function delete()
    {
        try {
            //AUTENTICADO
            if (! $this->core_web_authentication->isAuthenticated()) {
                throw new \Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (! $permited) {
                    throw new \Exception(NOT_ACCESS_CONTROL);
                }

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) {
                    throw new \Exception(NOT_ALL_DELETE);
                }

            }

            $companyID = /*inicio get post*/$this->request->getPost("companyID");
            $boxID     = /*inicio get post*/$this->request->getPost("txtBoxID");

            if ((! $companyID && ! $boxID)) {
                throw new \Exception(NOT_PARAMETER);
            }

            $objCashBox = $this->Cash_Box_Model->get_rowByPK($companyID, $boxID);
            if (is_null($objCashBox)) {
                $this->core_web_notification->set_message(true, 'No existe la caja especificada');
                $this->response->redirect(base_url() . "/" . 'app_box_admbox/index');
                return;
            }

            //Eliminar el Registro
            $this->Cash_Box_Model->delete_app_posme($companyID, $boxID);

            return $this->response->setJSON([
                'error'   => false,
                'message' => SUCCESS,
            ]); //--finjson
        } catch (\Exception $ex) {
            return $this->response->setJSON([
                'error'   => true,
                'message' => $ex->getLine() . " " . $ex->getMessage(),
            ]); //--finjson
            $this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
        }
    }

    public function insertElement($dataSession)
    {
        //PERMISO SOBRE LA FUNCION
        if (APP_NEED_AUTHENTICATION == true) {
            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

            if (! $permited) {
                throw new \Exception(NOT_ACCESS_CONTROL);
            }

            $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            if ($resultPermission == PERMISSION_NONE) {
                throw new \Exception(NOT_ACCESS_FUNCTION);
            }

        }

        $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "add");

        $db = db_connect();
        $db->transStart();
        $companyID             = $dataSession['user']->companyID;
        $branchID              = $dataSession['user']->branchID;
        $objBox['companyID']   = $companyID;
        $objBox['branchID']    = $branchID;
        $objBox['cashBoxCode'] = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_cash_box", 0);
        $objBox['name']        = /*inicio get post*/$this->request->getPost("txtNameBox");
        $objBox['description'] = /*inicio get post*/$this->request->getPost("txtDescriptionBox");
        $objBox['statusID']    = /*inicio get post*/$this->request->getPost("txtStatusID");
        $objBox['isActive']    = 1;

        $boxId = $this->Cash_Box_Model->insert_app_posme($objBox);

        if ($db->transStatus() !== false) {
            $db->transCommit();
            $this->core_web_notification->set_message(false, SUCCESS);
            $this->response->redirect(base_url() . "/" . 'app_box_admbox/edit/' . $companyID . "/" . $boxId);
        } else {
            $db->transRollback();
            $this->core_web_notification->set_message(true, $this->db->_error_message());
            $this->response->redirect(base_url() . "/" . 'app_box_admbox/add');
        }
    }

    public function updateElement($dataSession)
    {
        //PERMISO SOBRE LA FUNCION
        if (APP_NEED_AUTHENTICATION == true) {
            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

            if (! $permited) {
                throw new \Exception(NOT_ACCESS_CONTROL);
            }

            $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            if ($resultPermission == PERMISSION_NONE) {
                throw new \Exception(NOT_ACCESS_FUNCTION);
            }

        }

        $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "edit");

        $db = db_connect();
        $db->transStart();
        $branchID   = $dataSession['user']->branchID;
        $boxID      = /*inicio get post*/$this->request->getPost("txtBoxID");
        $companyID  = /*inicio get post*/$this->request->getPost("txtCompanyID");
        $objCashBox = $this->Cash_Box_Model->get_rowByPK($companyID, $boxID);
        if (is_null($objCashBox)) {
            $this->core_web_notification->set_message(true, 'No existe la caja especificada');
            $this->response->redirect(base_url() . "/" . 'app_box_admbox/index');
            return;
        }

        $objBox['cashBoxCode'] = /*inicio get post*/$this->request->getPost("txtCodeBox");
        $objBox['name']        = /*inicio get post*/$this->request->getPost("txtNameBox");
        $objBox['description'] = /*inicio get post*/$this->request->getPost("txtDescriptionBox");
        $objBox['statusID']    = /*inicio get post*/$this->request->getPost("txtStatusID");

        $this->Cash_Box_Model->update_app_posme($companyID, $boxID, $objBox);

        if ($db->transStatus() !== false) {
            $db->transCommit();
            $this->core_web_notification->set_message(false, SUCCESS);
            $this->response->redirect(base_url() . "/" . 'app_box_admbox/edit/' . $companyID . "/" . $boxID);
        } else {
            $db->transRollback();
            $this->core_web_notification->set_message(true, $this->db->_error_message());
            $this->response->redirect(base_url() . "/" . 'app_box_admbox/add');
        }
    }

    public function save($method = null)
    {
        try {

            //AUTENTICACION
            if (! $this->core_web_authentication->isAuthenticated()) {
                throw new \Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession = $this->session->get();

            //Validar Formulario
            $this->validation->setRule("txtNameBox", "Nombre", "required");
            $this->validation->setRule("txtDescriptionBox", "Descripcion", "required");

            if ($method == "new" && $this->validation->withRequest($this->request)->run() == true) {
                $this->insertElement($dataSession);
            } else if ($method == "edit" && $this->validation->withRequest($this->request)->run() == true) {
                $this->updateElement($dataSession);
            } else {
                $stringValidation = str_replace("\n", "", $this->validation->getErrors());
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_box_admbox/add');
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

    public function index($dataViewID = null)
    {
        try {

            //AUTENTICACION
            if (! $this->core_web_authentication->isAuthenticated()) {
                throw new \Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (! $permited) {
                    throw new \Exception(NOT_ACCESS_CONTROL);
                }

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE) {
                    throw new \Exception(NOT_ACCESS_FUNCTION);
                }

            }

            //Obtener el componente Para mostrar la lista de AccountType
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_cash_box");
            if (! $objComponent) {
                throw new \Exception("00409 EL COMPONENTE 'tb_cash_box' NO EXISTE...");
            }

            //Vista por defecto
            if ($dataViewID == null) {
                $targetComponentID        = 0;
                $parameter["{companyID}"] = $this->session->get('user')->companyID;
                $dataViewData             = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
                $dataViewRender           = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }
            //Otra vista
            else {
                $parameter["{companyID}"] = $this->session->get('user')->companyID;
                $dataViewData             = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
                $dataViewRender           = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/view('app_box_admbox/list_head');   //--finview
            $dataSession["footer"]       = /*--inicio view*/view('app_box_admbox/list_footer'); //--finview
            $dataSession["body"]         = $dataViewRender;
            $dataSession["script"]       = /*--inicio view*/view('app_box_admbox/list_script'); //--finview
            $dataSession["script"]       = $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);

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
}
