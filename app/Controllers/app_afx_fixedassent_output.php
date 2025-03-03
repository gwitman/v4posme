<?php
//posme:2025-03-03
namespace App\Controllers;

use Exception;

class app_afx_fixedassent_output extends _BaseController
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

            $objComponent        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_output");
            if (!$objComponent)
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_output' NO EXISTE...");


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
                $dataViewData                   = $this->core_web_view->getViewByName($this->session->get('user'), $objComponent->componentID, "LISTA DE SALIDAS DE ACTIVOS", CALLERID_LIST, $resultPermission, $parameter);
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
            $dataSession["head"]                = /*--inicio view*/ view('app_afx_fixedassent_output/list_head'); //--finview
            $dataSession["footer"]              = /*--inicio view*/ view('app_afx_fixedassent_output/list_footer'); //--finview
            $dataSession["body"]                = $dataViewRender;
            $dataSession["script"]              = /*--inicio view*/ view('app_afx_fixedassent_output/list_script'); //--finview
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
            $this->validation->setRule("txtDate", "Fecha", "required");
            $this->validation->setRule("txtEmployeeEntityID", "Empleado", "required");
            $this->validation->setRule("txtAreaID", "Area", "required");
            $this->validation->setRule("txtProyectID", "Proyecto", "required");

            //Validar Formulario
            if (!$this->validation->withRequest($this->request)->run()) {
                $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_output/add');
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
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_output/add');
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

            $objComponentFixedAsset        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
            if (!$objComponentFixedAsset) {
                throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
            }

            $objComponentPublicCatalog        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if (!$objComponentPublicCatalog) {
                throw new Exception("00409 EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
            }

            $objComponentFixedAssetOutput        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_output");
            if (!$objComponentFixedAssetOutput) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_output' NO EXISTE...");
            }

            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee) {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            $transactionID                                      = $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_fixedassent_output", 0);

            $dataView["company"]                                = $dataSession["company"];
            $dataView["objComponentFixedAsset"]                 = $objComponentFixedAsset;
            $dataView["objComponentPublicCatalog"]              = $objComponentPublicCatalog;
            $dataView["objComponentFixedAssetOutput"]           = $objComponentFixedAssetOutput;
            $dataView["objComponentEmployee"]                   = $objComponentEmployee;
            $dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_fixedassent_output", "statusID", $companyID, $branchID, $roleID);
            $dataView["objListEmployee"]                        = $this->Employee_Model->get_rowByBranchID($companyID, $branchID);
            $dataView["objCausal"]                              = $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);

            //Renderizar Resultado 
            $dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]             = $this->core_web_notification->get_message();
            $dataSession["head"]                = /*--inicio view*/ view('app_afx_fixedassent_output/news_head', $dataView); //--finview
            $dataSession["body"]                = /*--inicio view*/ view('app_afx_fixedassent_output/news_body', $dataView); //--finview
            $dataSession["script"]              = /*--inicio view*/ view('app_afx_fixedassent_output/news_script', $dataView); //--finview
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



            $objComponentFixedAssetOutput        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_output");
            if (!$objComponentFixedAssetOutput) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_output' NO EXISTE...");
            }

            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee) {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            $companyID                              = $dataSession["user"]->companyID;
            $branchID                               = $dataSession["user"]->branchID;

            $objTM["companyID"]                    = $companyID;
            $objTM["transactionNumber"]            = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_transaction_master_fixedassent_output", 0);
            $objTM["transactionID"]                = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_fixedassent_output", 0);
            $objTM["branchID"]                     = $branchID;
            $objTM["transactionCausalID"]          = /*inicio get post*/ $this->request->getPost("txtCausalID");
            $objTM["entityID"]                     = /*inicio get post*/ $this->request->getPost("txtEmployeeEntityID");
            $objTM["transactionOn"]                = /*inicio get post*/ $this->request->getPost("txtDate");
            $objTM["componentID"]                  = $objComponentFixedAssetOutput->componentID;
            $objTM["note"]                         = /*inicio get post*/ $this->request->getPost("txtComment");
            $objTM["reference1"]                   = /*inicio get post*/ $this->request->getPost("txtReference1");
            $objTM["reference2"]                   = /*inicio get post*/ $this->request->getPost("txtReference2");
            $objTM["reference3"]                   = /*inicio get post*/ $this->request->getPost("txtReference3");
            $objTM["statusID"]                     = /*inicio get post*/ $this->request->getPost("txtStatusID");
            $objTM["classID"]                      = /*inicio get post*/ $this->request->getPost("txtProyectID");
            $objTM["areaID"]                       = /*inicio get post*/ $this->request->getPost("txtAreaID");
            $objTM["isActive"]                     = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            $db = db_connect();
            $db->transStart();
            $transactionMasterID                    = $this->Transaction_Master_Model->insert_app_posme($objTM);

            $arrayListFixedAssetID                  = $this->request->getPost("txtFixedAssetID");
            $arrayListEstimatedDuration             = $this->request->getPost("txtEstimatedDuration");

            if (!empty($arrayListFixedAssetID)) {
                foreach ($arrayListFixedAssetID as $key => $value) {
                    $assetID                    = $value;
                    $assetEstimatedDuration     = $arrayListEstimatedDuration[$key];

                    $objTMD["companyID"]                    = $companyID;
                    $objTMD["transactionID"]                = $objTM["transactionID"];
                    $objTMD["transactionMasterID"]          = $transactionMasterID;
                    $objTMD["componentID"]                  = $objComponentFixedAssetOutput->componentID;
                    $objTMD["componentItemID"]              = $assetID;
                    $objTMD["amount"]                       = $assetEstimatedDuration;
                    $objTMD["isActive"]                     = 1;
                    $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
                }
            }

            if (!file_exists(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentFixedAssetOutput->componentID . "/component_item_" . $transactionMasterID)) {
                mkdir(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentFixedAssetOutput->componentID . "/component_item_" . $transactionMasterID, 0700, true);
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_output/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
            } else {
                $db->transRollback();
                $this->core_web_notification->set_message(true, $this->$db->_error_message());
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_output/add');
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

            $companyID                  = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
            $transactionID              = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri
            $transactionMasterID        = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri
            $branchID                   = $dataSession["user"]->branchID;
            $companyID                  = $dataSession["user"]->companyID;
            $branchID                   = $dataSession["user"]->branchID;
            $roleID                     = $dataSession["role"]->roleID;

            $objComponentFixedAsset             = $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
            if (!$objComponentFixedAsset) {
                throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
            }

            $objComponentPublicCatalog          = $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if (!$objComponentPublicCatalog) {
                throw new Exception("00409 EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
            }

            $objComponentFixedAssetOutput        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_output");
            if (!$objComponentFixedAssetOutput) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_output' NO EXISTE...");
            }

            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee) {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            if ((!$companyID) || (!$transactionID) || (!$transactionMasterID)) {
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_output/add');
            }


            $dataView["company"]                                = $dataSession["company"];
            $dataView["objTM"]                                  = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            $dataView["objEmployee"]                            = $this->Employee_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->entityID);
            $dataView["objComponentFixedAssetOutput"]           = $objComponentFixedAssetOutput;
            $dataView["objComponentFixedAsset"]                 = $objComponentFixedAsset;
            $dataView["objComponentPublicCatalog"]              = $objComponentPublicCatalog;
            $dataView["objComponentEmployee"]                   = $objComponentEmployee;
            $dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_fixedassent_output", "statusID", $companyID, $branchID, $roleID);
            $dataView["objListEmployee"]                        = $this->Employee_Model->get_rowByBranchID($companyID, $branchID);
            $dataView["objCausal"]                              = $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
            $dataView["objNatural"]                             = $this->Natural_Model->get_rowByPk($companyID, $branchID, $dataView["objTM"]->entityID);
            $dataView["objArea"]                                =  $dataView["objTM"]->areaID   ? $this->Public_Catalog_Detail_Model->get_rowByPk($dataView["objTM"]->areaID) : "";
            $dataView["objProject"]                             =  $dataView["objTM"]->classID  ? $this->Public_Catalog_Detail_Model->get_rowByPk($dataView["objTM"]->classID) : "";
            $dataView["objTMD"]                                 = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentFixedAssetOutput->componentID);

            //RENDERIZAR RESULTADO
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = view('app_afx_fixedassent_output/edit_head', $dataView);
            $dataSession["body"]            = view('app_afx_fixedassent_output/edit_body', $dataView);
            $dataSession["script"]          = view('app_afx_fixedassent_output/edit_script', $dataView);
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

            $objComponentFixedAsset             = $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
            if (!$objComponentFixedAsset) {
                throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
            }

            $objComponentFixedAssetInput        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_output");
            if (!$objComponentFixedAssetInput) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_output' NO EXISTE...");
            }

            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee) {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            $companyID                              = $dataSession["user"]->companyID;
            $branchID                               = $dataSession["user"]->branchID;
            $roleID                                 = $dataSession["role"]->roleID;

            $transactionID                          = $this->request->getPost("txtTransactionID");
            $transactionMasterID                    = $this->request->getPost("txtTransactionMasterID");

            $objTM                                  = $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
            $objTMNew["transactionCausalID"]        = /*inicio get post*/ $this->request->getPost("txtCausalID");
            $objTMNew["entityID"]                   = /*inicio get post*/ $this->request->getPost("txtEmployeeEntityID");
            $objTMNew["transactionOn"]              = /*inicio get post*/ $this->request->getPost("txtDate");
            $objTMNew["note"]                       = /*inicio get post*/ $this->request->getPost("txtComment");
            $objTMNew["reference1"]                 = /*inicio get post*/ $this->request->getPost("txtReference1");
            $objTMNew["reference2"]                 = /*inicio get post*/ $this->request->getPost("txtReference2");
            $objTMNew["reference3"]                 = /*inicio get post*/ $this->request->getPost("txtReference3");
            $objTMNew["statusID"]                   = /*inicio get post*/ $this->request->getPost("txtStatusID");
            $objTMNew["classID"]                    = /*inicio get post*/ $this->request->getPost("txtProyectID");
            $objTMNew["areaID"]                     = /*inicio get post*/ $this->request->getPost("txtAreaID");

            //Validar si el estado permite editar
            if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_output", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new Exception(NOT_WORKFLOW_EDIT);

            $db = db_connect();
            $db->transStart();


            if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_output", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
                $objTMNew                           = array();
                $objTMNew["statusID"]               = $this->request->getPost("txtStatusID");
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
            } else {
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
            }


            $arrayCurrentListFixedAssetID                   = $this->request->getPost("txtCurrentFixedAssetID");
            $arrayCurrentListEstimatedDuration              = $this->request->getPost("txtCurrentEstimatedDuration");

            $arrayNewListFixedAssetID                       = $this->request->getPost("txtNewFixedAssetID");
            $arrayNewListEstimatedDuration                  = $this->request->getPost("txtNewEstimatedDuration");

            //Eliminar los activos fijos existentes si la lista esta completamente vacia
            if ($arrayCurrentListFixedAssetID == null && $arrayNewListFixedAssetID != null) {
                $this->Transaction_Master_Detail_Model->deleteWhereTM($companyID, $transactionID, $transactionMasterID);
            } else {
                //Eliminar los activos fijos si la lista no esta completamente vacia
                $this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID, $transactionID, $transactionMasterID, $arrayCurrentListFixedAssetID);
            }

            //Actualizar los activos fijos ya existentes
            if (!empty($arrayCurrentListFixedAssetID)) {
                foreach ($arrayCurrentListFixedAssetID as $key => $value) {
                    $assetID                    = $value;
                    $objTMDNew["amount"]        = $arrayCurrentListEstimatedDuration[$key];
                    $this->Transaction_Master_Detail_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $assetID, $objTMDNew);
                }
            }

            //Ingresar los nuevos activos fijos
            if (!empty($arrayNewListFixedAssetID)) {
                foreach ($arrayNewListFixedAssetID as $key => $value) {
                    $objTMDNew                                  = [];
                    $objTMDNew["companyID"]                     = $companyID;
                    $objTMDNew["transactionID"]                 = $objTM->transactionID;
                    $objTMDNew["transactionMasterID"]           = $transactionMasterID;
                    $objTMDNew["componentID"]                   = $objComponentFixedAssetInput->componentID;
                    $objTMDNew["componentItemID"]               = $value;
                    $objTMDNew["amount"]                        = $arrayNewListEstimatedDuration[$key];
                    $objTMDNew["isActive"]                      = 1;
                    $this->Transaction_Master_Detail_Model->insert_app_posme($objTMDNew);
                }
            }


            if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_output", "statusID", $objTMNew["statusID"], COMMAND_APLICABLE, $companyID, $branchID, $roleID)) {

                $objListFixedAssets                             = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentFixedAssetInput->componentID);

                //Validar el estado del activo para ver si es permitido ser aplicado en la transaccion.
                foreach ($objListFixedAssets as $asset) {
                    $objAsset                                       = $this->Fixed_Assent_Model->get_rowByPK($companyID, $branchID, $asset->componentItemID);
                    $objWorkflowStage                               = $this->core_web_workflow->getWorkflowStageTargetBySource($transactionID, $companyID, $objTMNew["transactionCausalID"], "tb_fixed_assent", $objAsset->statusID, "tb_transaction_master_fixedassent_output");

                    if ($objWorkflowStage) {
                        throw new Exception("EL ESTADO ACTUAL DEL ACTIVO FIJO " . $objAsset->fixedAssentCode . " " . $objAsset->name . " NO PERMITE APLICAR LA ENTRADA");
                    }
                }

                //Asignar el estado a los activos fijos en funcion del causal y el estado origen de la transaccion.
                $objWorkflowStage                               = $this->core_web_workflow->getWorkflowStageTargetBySource($transactionID, $companyID, $objTMNew["transactionCausalID"], "tb_transaction_master_fixedassent_output", $objTMNew["statusID"], "tb_fixed_assent");
                $fixedAssetStatusID                             = $objWorkflowStage ? $objWorkflowStage[0]->workflowStageID : "";

                if (!empty($fixedAssetStatusID)) {
                    $objFANew                                       = [];
                    $objFANew["statusID"]                           = $fixedAssetStatusID;
                    $objFANew["projectID"]                          = $objTMNew["classID"];
                    $objFANew["areaID"]                             = $objTMNew["areaID"];
                    $objFANew["asignedEmployeeID"]                  = $objTMNew["entityID"];

                    foreach ($objListFixedAssets as $asset) {
                        $this->Fixed_Assent_Model->update_app_posme($companyID, $branchID, $asset->fixedAssetID, $objFANew);
                    }
                }
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_output/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
            } else {
                $db->transRollback();
                $this->core_web_notification->set_message(true, $this->$db->_error_message());
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_output/add');
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

                $resultPermission       = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission   == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_DELETE);
            }

            $companyID                  = /*inicio get post*/ $this->request->getPost("companyID");
            $transactionID              = /*inicio get post*/ $this->request->getPost("transactionID");
            $transactionMasterID        = /*inicio get post*/ $this->request->getPost("transactionMasterID");


            if ((!$companyID && !$transactionID && !$transactionMasterID)) {
                throw new Exception(NOT_PARAMETER);
            }

            $objTM                      = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            if ($resultPermission       == PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
                throw new Exception(NOT_DELETE);

            //Si el documento esta aplicado crear el contra documento			
            if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_output", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
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

            $transactionNumber     = /*inicio get post*/ $this->request->getPost("transactionNumber");

            if (!$transactionNumber) {
                throw new Exception(NOT_PARAMETER);
            }

            $objTM     = $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID, $transactionNumber);

            if (!$objTM)
                throw new Exception("NO SE ENCONTRO EL DOCUMENTO");

            return $this->response->setJSON(array(
                'error'                     => false,
                'message'                   => SUCCESS,
                'companyID'                 => $objTM->companyID,
                'transactionID'             => $objTM->transactionID,
                'transactionMasterID'       => $objTM->transactionMasterID
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
            $dataSession        = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);


                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_EDIT);
            }


            $transactionID                  = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri			
            $transactionMasterID            = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri				
            $companyID                      = $dataSession["user"]->companyID;
            $branchID                       = $dataSession["user"]->branchID;

            $objCompany                     = $this->Company_Model->get_rowByPK($companyID);
            $objParameterTelefono           = $this->core_web_parameter->getParameter("CORE_PHONE", $companyID);
            $objParameterLogo               = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
            $objParameterRuc                = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER", $companyID)->value;

            $objTM                          = $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);
            $objTMD                         = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objTM->componentID);
            $objEmployee                    = $this->Employee_Model->get_rowByPK($companyID, $branchID, $objTM->entityID);
            $objEmployeeNatural             = $this->Natural_Model->get_rowByPk($companyID, $branchID, $objTM->entityID);
            $objTM->transactionOn           = date_format(date_create($objTM->transactionOn), "Y-m-d");
            $objUser                        = $this->User_Model->get_rowByPK($companyID, $branchID, $dataSession["user"]->userID);

            $objArea                        = $this->Public_Catalog_Detail_Model->get_rowByPk($objTM->areaID);
            $objProject                     = $this->Public_Catalog_Detail_Model->get_rowByPk($objTM->classID);

            $objFAI["type"]                 = "SALIDA";
            $objFAI["transactionOn"]        = $objTM->transactionOn;
            $objFAI["status"]               = $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($objTM->statusID)[0]->name;
            $objFAI["causalName"]           = $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID, $transactionID, $objTM->transactionCausalID)->name;
            $objFAI["reference1"]           = $objTM->reference1;
            $objFAI["reference2"]           = $objTM->reference2;
            $objFAI["reference3"]           = $objTM->reference3;
            $objFAI["comment"]              = $objTM->note;
            $objFAI["entityType"]           = "Empleado";
            $objFAI["entityName"]           = $objEmployeeNatural->firstName . " " . $objEmployeeNatural->lastName;
            $objFAI["entityNumber"]         = $objEmployee->employeNumber;
            $objFAI["areaID"]               = $objArea->Id;
            $objFAI["areaName"]             = $objArea->Indicador;
            $objFAI["projectID"]            = $objProject->Id;
            $objFAI["projectName"]          = $objProject->Indicador;

            //Generar Reporte
            $html = helper_reporteA4FixedAssetTransaction(
                $objFAI,
                $objCompany,
                $objParameterLogo,
                $objTM,
                $objTMD,
                $objParameterTelefono,
                $objUser,
                $objParameterRuc,
            );

            // echo $html;

            $this->dompdf->loadHTML($html);

            $this->dompdf->render();

            $objParameterShowLinkDownload    = $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID);
            $objParameterShowLinkDownload    = $objParameterShowLinkDownload->value;

            if ($objParameterShowLinkDownload == "true") {
                $fileNamePut = "factura_" . $transactionMasterID . "_" . date("dmYhis") . ".pdf";
                $path        = "./resource/file_company/company_" . $companyID . "/component_119/component_item_" . $transactionMasterID . "/" . $fileNamePut;

                file_put_contents(
                    $path,
                    $this->dompdf->output()
                );

                chmod($path, 644);

                echo "<a 
					href='" . base_url() . "/resource/file_company/company_" . $companyID . "/component_119/component_item_" . $transactionMasterID . "/" .
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
