<?php

namespace App\Controllers;

use Exception;

class app_accounting_indicators extends _BaseController
{

    function edit()
    {
        try {
            //AUTENTICACION			
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession        = $this->session->get();


            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            $companyID              = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
            $indicatorID            = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "indicatorID"); //--finuri

            if ((!$companyID || !$indicatorID)) {
                $this->response->redirect(base_url() . "/" . 'app_accounting_indicators/add');
            }


            //Obtener el Registro			
            $datView["objIndicator"]            = $this->Indicator_Model->getByPK($companyID, $indicatorID);

            //Renderizar Resultado
            $dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]             = $this->core_web_notification->get_message();
            $dataSession["head"]                = /*--inicio view*/ view('app_accounting_indicators/edit_head', $datView); //--finview
            $dataSession["body"]                = /*--inicio view*/ view('app_accounting_indicators/edit_body', $datView); //--finview
            $dataSession["script"]              = /*--inicio view*/ view('app_accounting_indicators/edit_script', $datView); //--finview
            $dataSession["footer"]              = "";
            return view("core_masterpage/default_masterpage", $dataSession); //--finview-r

        } catch (\Exception $ex) {
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

    function updateElement($dataSession)
    {
        if (APP_NEED_AUTHENTICATION == true) {
            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

            if (!$permited)
                throw new \Exception(NOT_ACCESS_CONTROL);

            $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            if ($resultPermission     == PERMISSION_NONE)
                throw new \Exception(NOT_ALL_EDIT);
        }

        $db = db_connect();
        $db->transStart();
        $companyID                  = $dataSession["user"]->companyID;
        $indicatorID                = /*inicio get post*/ $this->request->getPost("txtIndicatorID");
        $obj["code"]                = /*inicio get post*/ $this->request->getPost("txtCode");
        $obj["label"]               = /*inicio get post*/ $this->request->getPost("txtLabel");
        $obj["description"]         = /*inicio get post*/ $this->request->getPost("txtDescription");
        $obj["order"]               = /*inicio get post*/ $this->request->getPost("txtOrder");
        $obj["script"]              = /*inicio get post*/ $this->request->getPost("txtScript");
        $obj["prefix"]              = /*inicio get post*/ $this->request->getPost("txtPrefix");
        $obj["posfix"]              = /*inicio get post*/ $this->request->getPost("txtPosfix");

        //Actualizar
        $result             = $this->Indicator_Model->update_app_posme($companyID, $indicatorID, $obj);

        if ($db->transStatus() !== false) {
            $db->transCommit();
            $this->core_web_notification->set_message(false, SUCCESS);
        } else {
            $db->transRollback();
            $this->core_web_notification->set_message(true, $this->db->_error_message());
        }
        $this->response->redirect(base_url() . "/" . 'app_accounting_indicators/edit/companyID/' . $companyID . "/indicatorID/" . $indicatorID);
    }

    function insertElement($dataSession)
    {
        if (APP_NEED_AUTHENTICATION == true) {
            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

            if (!$permited)
                throw new \Exception(NOT_ACCESS_CONTROL);

            $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            if ($resultPermission     == PERMISSION_NONE)
                throw new \Exception(NOT_ALL_INSERT);
        }

        $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");
        $db = db_connect();
        $db->transStart();

        //Crear Indicador
        $obj["companyID"]           = $dataSession["user"]->companyID;
        $obj["code"]                = /*inicio get post*/ $this->request->getPost("txtCode");
        $obj["name"]                = /*inicio get post*/ $this->request->getPost("txtName");
        $obj["label"]               = /*inicio get post*/ $this->request->getPost("txtLabel");
        $obj["description"]         = /*inicio get post*/ $this->request->getPost("txtDescription");
        $obj["order"]               = /*inicio get post*/ $this->request->getPost("txtOrder");
        $obj["script"]              = /*inicio get post*/ $this->request->getPost("txtScript");
        $obj["posfix"]              = /*inicio get post*/ $this->request->getPost("txtPosfix");
        $obj["prefix"]              = /*inicio get post*/ $this->request->getPost("txtPrefix");
        $obj["isActive"]            = true;

        $indicatorID                = $this->Indicator_Model->insert_app_posme($obj);
        $companyID                  = $obj["companyID"];
        //Ingresar

        //Completar Transaccion
        if ($db->transStatus() !== false) {
            $db->transCommit();
            $this->core_web_notification->set_message(false, SUCCESS);
            $this->response->redirect(base_url() . "/" . 'app_accounting_indicators/edit/companyID/' . $companyID . "/indicatorID/" . $indicatorID);
        } else {
            $db->transRollback();
            $this->core_web_notification->set_message(true, $this->db->_error_message());
            $this->response->redirect(base_url() . "/" . 'app_accounting_indicators/add');
        }
    }

    function save($method = NULL)
    {
        $method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
        try {
            //AUTENTICACION
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession        = $this->session->get();

            //Validar Formulario						
            $this->validation->setRule("txtName", "Nombre", "required");

            //Validar Formulario
            if ($this->validation->withRequest($this->request)->run() != true) {
                $stringValidation = str_replace("\n", "", $this->validation->getErrors());
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_accounting_indicators/add');
                exit;
            }

            //Nuevo Registro
            if ($method == "new") {
                $this->insertElement($dataSession);
            }
            //Editar Registro
            else if($method == "edit"){
                $this->updateElement($dataSession);
            }else
            {
                $stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_indicators/add');
				exit;
            }
        } catch (\Exception $ex) {
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

    function add()
    {

        try {
            //AUTENTICACION
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession        = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_INSERT);
            }

            //Renderizar Resultado 
            $dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]             = $this->core_web_notification->get_message();
            $dataSession["head"]                = /*--inicio view*/ view('app_accounting_indicators/news_head'); //--finview
            $dataSession["body"]                = /*--inicio view*/ view('app_accounting_indicators/news_body'); //--finview
            $dataSession["script"]              = /*--inicio view*/ view('app_accounting_indicators/news_script'); //--finview
            $dataSession["footer"]              = "";
            return view("core_masterpage/default_masterpage", $dataSession); //--finview-r

        } catch (\Exception $ex) {
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

    function index($dataViewID = null)
    {
        try {

            //AUTENTICACION
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession        = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);
            }

            //Obtener el componente para mostrar los indicadores
            $objComponent        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_indicator");
            if (!$objComponent)
                throw new \Exception("EL COMPONENTE 'tb_indicator' NO EXISTE...");


            //Vista por defecto 
            if ($dataViewID == null) {
                $targetComponentID              = 0;
                $parameter["{companyID}"]       = $this->session->get('user')->companyID;
                $dataViewData                   = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
                $dataViewRender                 = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }
            //Otra vista
            else {
                $parameter["{companyID}"]       = $this->session->get('user')->companyID;
                $dataViewData                   = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
                $dataViewRender                 = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }

            //Renderizar Resultado
            $dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]             = $this->core_web_notification->get_message();
            $dataSession["head"]                = /*--inicio view*/ view('app_accounting_indicators/list_head'); //--finview
            $dataSession["footer"]              = /*--inicio view*/ view('app_accounting_indicators/list_footer'); //--finview
            $dataSession["body"]                = $dataViewRender;
            $dataSession["script"]              = /*--inicio view*/ view('app_accounting_indicators/list_script'); //--finview
            $dataSession["script"]              = $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
            return view("core_masterpage/default_masterpage", $dataSession); //--finview-r	
        } catch (\Exception $ex) {
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

    function delete()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession        = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);
            }

            //Nuevo Registro
            $companyID              = /*inicio get post*/ $this->request->getPost("companyID");
            $indicatorID            = /*inicio get post*/ $this->request->getPost("indicatorID");

            if ((!$companyID && !$indicatorID)) {
                throw new \Exception(NOT_PARAMETER);
            }

            //Eliminar el Registro
            $this->Indicator_Model->delete_app_posme($companyID, $indicatorID);

            return $this->response->setJSON(array(
                'error'   => false,
                'message' => SUCCESS
            )); //--finjson

        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error'   => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            )); //--finjson
        }
    }
}
