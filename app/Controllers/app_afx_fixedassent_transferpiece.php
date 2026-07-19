<?php
//posme:2025-07-19
namespace App\Controllers;

use Exception;

class app_afx_fixedassent_transferpiece extends _BaseController
{
    function index($dataViewID = null)
    {
        log_message('info', '[app_afx_fixedassent_transferpiece::index] Inicio de ejecución. dataViewID: ' . ($dataViewID ?? 'null'));
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();
            log_message('info', '[app_afx_fixedassent_transferpiece::index] Usuario autenticado. userID: ' . $dataSession["user"]->userID);

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new Exception(NOT_ACCESS_FUNCTION);

                log_message('info', '[app_afx_fixedassent_transferpiece::index] Permisos validados. resultPermission: ' . $resultPermission);
            }

            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferpiece");
            if (!$objComponent)
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_transferpiece' NO EXISTE...");

            log_message('info', '[app_afx_fixedassent_transferpiece::index] Componente obtenido. componentID: ' . $objComponent->componentID);

            //Vista por defecto PC
            if ($dataViewID == null and !$this->request->getUserAgent()->isMobile()) {
                log_message('info', '[app_afx_fixedassent_transferpiece::index] Renderizando vista PC por defecto');
                $targetComponentID          = 0;
                $parameter["{companyID}"]   = $this->session->get('user')->companyID;
                $dataViewData               = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
                $dataViewRender             = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }
            //Vista por defecto MOBILE
            else if ($this->request->getUserAgent()->isMobile()) {
                log_message('info', '[app_afx_fixedassent_transferpiece::index] Renderizando vista MOBILE');
                $parameter["{companyID}"]   = $this->session->get('user')->companyID;
                $dataViewData               = $this->core_web_view->getViewByName($this->session->get('user'), $objComponent->componentID, "LISTA DE TRANSFERENCIA DE PIEZAS", CALLERID_LIST, $resultPermission, $parameter);
                $dataViewRender             = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }
            //Vista Por Id
            else {
                log_message('info', '[app_afx_fixedassent_transferpiece::index] Renderizando vista por ID: ' . $dataViewID);
                $parameter["{companyID}"]   = $this->session->get('user')->companyID;
                $dataViewData               = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
                $dataViewRender             = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }

            //Renderizar Resultado
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = view('app_afx_fixedassent_transferpiece/list_head');
            $dataSession["footer"]          = view('app_afx_fixedassent_transferpiece/list_footer');
            $dataSession["body"]            = $dataViewRender;
            $dataSession["script"]          = view('app_afx_fixedassent_transferpiece/list_script');
            $dataSession["script"]          = $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
            log_message('info', '[app_afx_fixedassent_transferpiece::index] Vista renderizada exitosamente');
            return view("core_masterpage/default_masterpage", $dataSession);
        } catch (Exception $ex) 
        {
            log_message('error', '[app_afx_fixedassent_transferpiece::index] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);
            return $resultView;
        }
    }

    function save($mode = "")
    {
        $mode = helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);
        log_message('info', '[app_afx_fixedassent_transferpiece::save] Inicio de ejecución. mode: ' . $mode);

        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();
            log_message('info', '[app_afx_fixedassent_transferpiece::save] Usuario autenticado. userID: ' . $dataSession["user"]->userID);

            //Validar Formulario
            $this->validation->setRule("txtDate", "Fecha", "required");
            $this->validation->setRule("txtStatusID", "Estado", "required");

            //Validar Formulario
            if (!$this->validation->withRequest($this->request)->run()) {
                $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
                log_message('warning', '[app_afx_fixedassent_transferpiece::save] Validación fallida: ' . $stringValidation);
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_transferpiece/add');
                exit;
            }

            //Guardar o Editar Registro
            if ($mode == "new") {
                log_message('info', '[app_afx_fixedassent_transferpiece::save] Ejecutando modo INSERT');
                $this->insertElement($dataSession);
            } else if ($mode == "edit") {
                log_message('info', '[app_afx_fixedassent_transferpiece::save] Ejecutando modo UPDATE');
                $this->updateElement($dataSession);
            } else {
                $stringValidation = "El modo de operacion no es correcto (new|edit)";
                log_message('error', '[app_afx_fixedassent_transferpiece::save] Modo inválido: ' . $mode);
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_transferpiece/add');
                exit;
            }
        } catch (Exception $ex) {
            log_message('error', '[app_afx_fixedassent_transferpiece::save] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function add()
    {
        log_message('info', '[app_afx_fixedassent_transferpiece::add] Inicio de ejecución');
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();
            log_message('info', '[app_afx_fixedassent_transferpiece::add] Usuario autenticado. userID: ' . $dataSession["user"]->userID);

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_INSERT);

                log_message('info', '[app_afx_fixedassent_transferpiece::add] Permisos validados. resultPermission: ' . $resultPermission);
            }

            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $roleID = $dataSession["role"]->roleID;

            $objComponentTransferpiece = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferpiece");
            if (!$objComponentTransferpiece)
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_transferpiece' NO EXISTE...");

            $objComponentFixedAsset = $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
            if (!$objComponentFixedAsset)
                throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");

            $objComponentPublicCatalog = $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if (!$objComponentPublicCatalog)
                throw new Exception("00409 EL COMPONENTE 'tb_public_catalog' NO EXISTE...");

            $transactionID = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_transferpiece", 0);
            log_message('info', '[app_afx_fixedassent_transferpiece::add] Componentes cargados. transactionID: ' . $transactionID . ', companyID: ' . $companyID . ', branchID: ' . $branchID);

            $dataView["company"] = $dataSession["company"];
            $dataView["objComponentTransferpiece"] = $objComponentTransferpiece;
            $dataView["objComponentFixedAsset"] = $objComponentFixedAsset;
            $dataView["objComponentPublicCatalog"] = $objComponentPublicCatalog;
            $dataView["objListWorkflowStage"] = $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_transferpiece", "statusID", $companyID, $branchID, $roleID);
            $dataView["objCausal"] = $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] = $this->core_web_notification->get_message();
            $dataSession["head"] = view('app_afx_fixedassent_transferpiece/news_head', $dataView);
            $dataSession["body"] = view('app_afx_fixedassent_transferpiece/news_body', $dataView);
            $dataSession["script"] = view('app_afx_fixedassent_transferpiece/news_script', $dataView);
            $dataSession["footer"] = "";
            log_message('info', '[app_afx_fixedassent_transferpiece::add] Vista de agregar renderizada exitosamente');
            return view("core_masterpage/default_masterpage", $dataSession);

        } catch (Exception $ex) {
            log_message('error', '[app_afx_fixedassent_transferpiece::add] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function edit()
    {
        log_message('info', '[app_afx_fixedassent_transferpiece::edit] Inicio de ejecución');
        try {
            if (!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);

            $dataSession = $this->session->get();
            log_message('info', '[app_afx_fixedassent_transferpiece::edit] Usuario autenticado. userID: ' . $dataSession["user"]->userID);

            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_INSERT);

                log_message('info', '[app_afx_fixedassent_transferpiece::edit] Permisos validados. resultPermission: ' . $resultPermission);
            }

            $companyID = helper_SegmentsValue($this->uri->getSegments(), "companyID");
            $transactionID = helper_SegmentsValue($this->uri->getSegments(), "transactionID");
            $transactionMasterID = helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID");
            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $roleID = $dataSession["role"]->roleID;

            log_message('info', '[app_afx_fixedassent_transferpiece::edit] Parámetros: companyID=' . $companyID . ', transactionID=' . $transactionID . ', transactionMasterID=' . $transactionMasterID);

            $objComponentTransferpiece = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferpiece");
            if (!$objComponentTransferpiece)
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_transferpiece' NO EXISTE...");

            $objComponentFixedAsset = $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
            if (!$objComponentFixedAsset)
                throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");

            $objComponentPublicCatalog = $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if (!$objComponentPublicCatalog)
                throw new Exception("00409 EL COMPONENTE 'tb_public_catalog' NO EXISTE...");

            if ((!$companyID) || (!$transactionID) || (!$transactionMasterID)) {
                log_message('warning', '[app_afx_fixedassent_transferpiece::edit] Parámetros incompletos, redirigiendo a add');
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_transferpiece/add');
            }

            $dataView["company"] = $dataSession["company"];
            $dataView["objTM"] = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            $dataView["objComponentTransferpiece"] = $objComponentTransferpiece;
            $dataView["objComponentFixedAsset"] = $objComponentFixedAsset;
            $dataView["objComponentPublicCatalog"] = $objComponentPublicCatalog;
            $dataView["objListWorkflowStage"] = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_transferpiece", "statusID", $companyID, $branchID, $roleID);
            $dataView["objCausal"] = $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
            $dataView["objTMD"] = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentTransferpiece->componentID);

            log_message('info', '[app_afx_fixedassent_transferpiece::edit] Transaction Master cargado. statusID: ' . ($dataView["objTM"]->statusID ?? 'null'));

            // Obtener info del activo origen
            $dataView["objSourceAsset"] = null;
            if ($dataView["objTM"]->sourceWarehouseID) {
                $dataView["objSourceAsset"] = $this->Fixed_Assent_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->sourceWarehouseID);
                log_message('info', '[app_afx_fixedassent_transferpiece::edit] Activo origen cargado. sourceWarehouseID: ' . $dataView["objTM"]->sourceWarehouseID);
            }

            // Obtener info del activo destino
            $dataView["objTargetAsset"] = null;
            if ($dataView["objTM"]->targetWarehouseID) {
                $dataView["objTargetAsset"] = $this->Fixed_Assent_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->targetWarehouseID);
                log_message('info', '[app_afx_fixedassent_transferpiece::edit] Activo destino cargado. targetWarehouseID: ' . $dataView["objTM"]->targetWarehouseID);
            }

            //RENDERIZAR RESULTADO
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] = $this->core_web_notification->get_message();
            $dataSession["head"] = view('app_afx_fixedassent_transferpiece/edit_head', $dataView);
            $dataSession["body"] = view('app_afx_fixedassent_transferpiece/edit_body', $dataView);
            $dataSession["script"] = view('app_afx_fixedassent_transferpiece/edit_script', $dataView);
            $dataSession["footer"] = "";
            log_message('info', '[app_afx_fixedassent_transferpiece::edit] Vista de edición renderizada exitosamente');
            return view('core_masterpage/default_masterpage', $dataSession);
        } catch (Exception $ex) {
            log_message('error', '[app_afx_fixedassent_transferpiece::edit] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function insertElement($dataSession)
    {
        log_message('info', '[app_afx_fixedassent_transferpiece::insertElement] Inicio de ejecución');
        try {
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_INSERT);

                log_message('info', '[app_afx_fixedassent_transferpiece::insertElement] Permisos validados');
            }

            $objComponentTransferpiece = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferpiece");
            if (!$objComponentTransferpiece)
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_transferpiece' NO EXISTE...");

            $objComponentFixedAsset = $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
            if (!$objComponentFixedAsset)
                throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");

            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $roleID = $dataSession["role"]->roleID;

            $objTM["companyID"] = $companyID;
            $objTM["transactionNumber"] = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_transaction_master_transferpiece", 0);
            $objTM["transactionID"] = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_transferpiece", 0);
            $objTM["branchID"] = $branchID;
            $objTM["transactionOn"] = $this->request->getPost("txtDate");
            $objTM["componentID"] = $objComponentTransferpiece->componentID;
            $objTM["note"] = $this->request->getPost("txtComment");
            $objTM["reference1"] = $this->request->getPost("txtReference1");
            $objTM["reference2"] = $this->request->getPost("txtReference2");
            $objTM["statusID"] = $this->request->getPost("txtStatusID");
            $objTM["sourceWarehouseID"] = $this->request->getPost("txtSourceFixedAssetID");
            $objTM["targetWarehouseID"] = $this->request->getPost("txtTargetFixedAssetID");
            $objTM["isActive"] = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            log_message('info', '[app_afx_fixedassent_transferpiece::insertElement] Datos TM preparados. transactionNumber: ' . $objTM["transactionNumber"] . ', transactionID: ' . $objTM["transactionID"] . ', statusID: ' . $objTM["statusID"]);

            $db = db_connect();
            $db->transStart();
            $transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
            log_message('info', '[app_afx_fixedassent_transferpiece::insertElement] Transaction Master insertado. transactionMasterID: ' . $transactionMasterID);

            // Guardar detalle de piezas
            $arrayPieceName = $this->request->getPost("txtPieceName");
            $arrayPieceQuantity = $this->request->getPost("txtPieceQuantity");
            $arrayPieceAction = $this->request->getPost("txtPieceAction");

            if (!empty($arrayPieceName)) {
                log_message('info', '[app_afx_fixedassent_transferpiece::insertElement] Insertando detalle de piezas. Total: ' . count($arrayPieceName));
                foreach ($arrayPieceName as $key => $value) {
                    $objTMD = [];
                    $objTMD["companyID"] = $companyID;
                    $objTMD["transactionID"] = $objTM["transactionID"];
                    $objTMD["transactionMasterID"] = $transactionMasterID;
                    $objTMD["componentID"] = $objComponentTransferpiece->componentID;
                    $objTMD["componentItemID"] = 0;
                    $objTMD["quantity"] = $arrayPieceQuantity[$key];
                    $objTMD["reference1"] = $arrayPieceAction[$key];
                    $objTMD["reference2"] = $arrayPieceName[$key];
                    $objTMD["isActive"] = 1;
                    $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
                }
                log_message('info', '[app_afx_fixedassent_transferpiece::insertElement] Detalle de piezas insertado correctamente');
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                log_message('info', '[app_afx_fixedassent_transferpiece::insertElement] Transacción COMMIT exitoso. Redirigiendo a edit');
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_transferpiece/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
            } else {
                $db->transRollback();
                log_message('error', '[app_afx_fixedassent_transferpiece::insertElement] Transacción ROLLBACK. Error al guardar');
                $this->core_web_notification->set_message(true, "Error al guardar la transaccion");
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_transferpiece/add');
            }
        } catch (Exception $ex) {
            log_message('error', '[app_afx_fixedassent_transferpiece::insertElement] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function updateElement($dataSession)
    {
        log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Inicio de ejecución');
        try {
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_EDIT);

                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Permisos validados');
            }

            $objComponentTransferpiece = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferpiece");
            if (!$objComponentTransferpiece)
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_transferpiece' NO EXISTE...");

            $objComponentFixedAsset = $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
            if (!$objComponentFixedAsset)
                throw new Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");

            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $roleID = $dataSession["role"]->roleID;

            $transactionID = $this->request->getPost("txtTransactionID");
            $transactionMasterID = $this->request->getPost("txtTransactionMasterID");

            log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Parámetros: companyID=' . $companyID . ', transactionID=' . $transactionID . ', transactionMasterID=' . $transactionMasterID);

            $objTM = $this->Transaction_Master_Model->get_rowByPk($companyID, $transactionID, $transactionMasterID);

            //Validar si el estado permite editar
            if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferpiece", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new Exception(NOT_WORKFLOW_EDIT);

            log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Workflow validado. statusID actual: ' . $objTM->statusID);

            $db = db_connect();
            $db->transStart();

            if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferpiece", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Modo edición parcial (solo statusID)');
                $objTMNew = array();
                $objTMNew["statusID"] = $this->request->getPost("txtStatusID");
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
            } else {
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Modo edición completa');
                $objTMNew["transactionOn"] = $this->request->getPost("txtDate");
                $objTMNew["note"] = $this->request->getPost("txtComment");
                $objTMNew["reference1"] = $this->request->getPost("txtReference1");
                $objTMNew["reference2"] = $this->request->getPost("txtReference2");
                $objTMNew["statusID"] = $this->request->getPost("txtStatusID");
                $objTMNew["sourceWarehouseID"] = $this->request->getPost("txtSourceFixedAssetID");
                $objTMNew["targetWarehouseID"] = $this->request->getPost("txtTargetFixedAssetID");
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
            }

            // Manejar detalle existente y nuevo
            $arrayCurrentPieceID = $this->request->getPost("txtCurrentPieceID");
            $arrayCurrentPieceName = $this->request->getPost("txtCurrentPieceName");
            $arrayCurrentPieceQuantity = $this->request->getPost("txtCurrentPieceQuantity");
            $arrayCurrentPieceAction = $this->request->getPost("txtCurrentPieceAction");

            $arrayNewPieceName = $this->request->getPost("txtNewPieceName");
            $arrayNewPieceQuantity = $this->request->getPost("txtNewPieceQuantity");
            $arrayNewPieceAction = $this->request->getPost("txtNewPieceAction");

            // Eliminar los detalles existentes si la lista esta completamente vacia
            if ($arrayCurrentPieceID == null && $arrayNewPieceName != null) {
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Eliminando todos los detalles existentes');
                $this->Transaction_Master_Detail_Model->deleteWhereTM($companyID, $transactionID, $transactionMasterID);
            } else if ($arrayCurrentPieceID != null) {
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Eliminando detalles que no están en la lista actual');
                // Eliminar los que no estan en la lista
                $this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID, $transactionID, $transactionMasterID, $arrayCurrentPieceID);
            }

            // Actualizar los existentes
            if (!empty($arrayCurrentPieceID)) {
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Actualizando detalles existentes. Total: ' . count($arrayCurrentPieceID));
                foreach ($arrayCurrentPieceID as $key => $value) {
                    $objTMDUpdate = [];
                    $objTMDUpdate["quantity"] = $arrayCurrentPieceQuantity[$key];
                    $objTMDUpdate["reference1"] = $arrayCurrentPieceAction[$key];
                    $objTMDUpdate["reference2"] = $arrayCurrentPieceName[$key];
                    $this->Transaction_Master_Detail_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $value, $objTMDUpdate);
                }
            }

            // Insertar los nuevos
            if (!empty($arrayNewPieceName)) {
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Insertando nuevos detalles. Total: ' . count($arrayNewPieceName));
                foreach ($arrayNewPieceName as $key => $value) {
                    $objTMD = [];
                    $objTMD["companyID"] = $companyID;
                    $objTMD["transactionID"] = $transactionID;
                    $objTMD["transactionMasterID"] = $transactionMasterID;
                    $objTMD["componentID"] = $objComponentTransferpiece->componentID;
                    $objTMD["componentItemID"] = 0;
                    $objTMD["quantity"] = $arrayNewPieceQuantity[$key];
                    $objTMD["reference1"] = $arrayNewPieceAction[$key];
                    $objTMD["reference2"] = $value;
                    $objTMD["isActive"] = 1;
                    $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
                }
            }

            // Lógica de aplicación cuando el workflow lo permite
            $statusID = $this->request->getPost("txtStatusID");
            if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferpiece", "statusID", $statusID, COMMAND_APLICABLE, $companyID, $branchID, $roleID)) {
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Workflow APLICABLE. Ejecutando lógica de transferencia de piezas');

                $sourceFixedAssetID = $this->request->getPost("txtSourceFixedAssetID");
                $targetFixedAssetID = $this->request->getPost("txtTargetFixedAssetID");

                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] sourceFixedAssetID: ' . ($sourceFixedAssetID ?? 'null') . ', targetFixedAssetID: ' . ($targetFixedAssetID ?? 'null'));

                // Obtener todos los detalles actualizados
                $objListDetail = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentTransferpiece->componentID);

                if ($objListDetail) {
                    log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Procesando ' . count($objListDetail) . ' detalles para aplicación');
                    foreach ($objListDetail as $detail) {
                        $pieceAction = $detail->reference1;
                        $pieceName = $detail->reference2;
                        $pieceQuantity = $detail->quantity;

                        if ($pieceAction == "baja" && $sourceFixedAssetID) {
                            log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Acción BAJA para pieza: ' . $pieceName . ' del activo origen: ' . $sourceFixedAssetID);
                            // Dar de baja la pieza del activo origen
                            $objProperty = $this->Component_Property_Model->get_rowByItemID($sourceFixedAssetID);
                            if ($objProperty) {
                                $this->Component_Property_Model->delete_app_posme($objProperty->propertyItemID);
                                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Pieza dada de baja. propertyItemID: ' . $objProperty->propertyItemID);
                            }
                        } else if ($pieceAction == "nueva" && $targetFixedAssetID) {
                            log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Acción NUEVA para pieza: ' . $pieceName . ' en activo destino: ' . $targetFixedAssetID);
                            // Crear nueva pieza en activo destino
                            $dataProp = [];
                            $dataProp["componentID"] = $objComponentFixedAsset->componentID;
                            $dataProp["componentItemID"] = $targetFixedAssetID;
                            $dataProp["propertyID"] = 0;
                            $dataProp["name"] = $pieceName;
                            $dataProp["descripcion"] = "";
                            $dataProp["value"] = $pieceQuantity;
                            $dataProp["type"] = "transferpiece";
                            $dataProp["isActive"] = 1;
                            $this->Component_Property_Model->insert_app_posme($dataProp);
                            log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Nueva pieza creada en activo destino');
                        } else if ($pieceAction == "transferencia" && $sourceFixedAssetID && $targetFixedAssetID) {
                            log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Acción TRANSFERENCIA para pieza: ' . $pieceName . ' de origen: ' . $sourceFixedAssetID . ' a destino: ' . $targetFixedAssetID);
                            // Baja del origen
                            $objProperty = $this->Component_Property_Model->get_rowByItemID($sourceFixedAssetID);
                            if ($objProperty) {
                                $this->Component_Property_Model->delete_app_posme($objProperty->propertyItemID);
                                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Pieza eliminada del origen. propertyItemID: ' . $objProperty->propertyItemID);
                            }
                            // Alta en destino
                            $dataProp = [];
                            $dataProp["componentID"] = $objComponentFixedAsset->componentID;
                            $dataProp["componentItemID"] = $targetFixedAssetID;
                            $dataProp["propertyID"] = 0;
                            $dataProp["name"] = $pieceName;
                            $dataProp["descripcion"] = "";
                            $dataProp["value"] = $pieceQuantity;
                            $dataProp["type"] = "transferpiece";
                            $dataProp["isActive"] = 1;
                            $this->Component_Property_Model->insert_app_posme($dataProp);
                            log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Pieza creada en activo destino');
                        }
                    }
                }
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                log_message('info', '[app_afx_fixedassent_transferpiece::updateElement] Transacción COMMIT exitoso');
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_transferpiece/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
            } else {
                $db->transRollback();
                log_message('error', '[app_afx_fixedassent_transferpiece::updateElement] Transacción ROLLBACK. Error al actualizar');
                $this->core_web_notification->set_message(true, "Error al actualizar la transaccion");
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_transferpiece/add');
            }
        } catch (Exception $ex) {
            log_message('error', '[app_afx_fixedassent_transferpiece::updateElement] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            echo $resultView;
        }
    }

    function delete()
    {
        log_message('info', '[app_afx_fixedassent_transferpiece::delete] Inicio de ejecución');
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();
            log_message('info', '[app_afx_fixedassent_transferpiece::delete] Usuario autenticado. userID: ' . $dataSession["user"]->userID);

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_DELETE);

                log_message('info', '[app_afx_fixedassent_transferpiece::delete] Permisos validados. resultPermission: ' . $resultPermission);
            }

            $companyID = $this->request->getPost("companyID");
            $transactionID = $this->request->getPost("transactionID");
            $transactionMasterID = $this->request->getPost("transactionMasterID");

            log_message('info', '[app_afx_fixedassent_transferpiece::delete] Parámetros: companyID=' . $companyID . ', transactionID=' . $transactionID . ', transactionMasterID=' . $transactionMasterID);

            if ((!$companyID && !$transactionID && !$transactionMasterID)) {
                throw new Exception(NOT_PARAMETER);
            }

            $objTM = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            if ($resultPermission == PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
                throw new Exception(NOT_DELETE);

            //Si el documento esta aplicado validar si se puede eliminar
            if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferpiece", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new Exception(NOT_WORKFLOW_DELETE);

            //Eliminar el Registro
            $this->Transaction_Master_Model->delete_app_posme($companyID, $transactionID, $transactionMasterID);
            log_message('info', '[app_afx_fixedassent_transferpiece::delete] Registro eliminado exitosamente. transactionMasterID: ' . $transactionMasterID);

            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS
            ));
        } catch (Exception $ex) {
            log_message('error', '[app_afx_fixedassent_transferpiece::delete] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));
        }
    }

    function searchTransactionMaster()
    {
        log_message('info', '[app_afx_fixedassent_transferpiece::searchTransactionMaster] Inicio de ejecución');
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();
            log_message('info', '[app_afx_fixedassent_transferpiece::searchTransactionMaster] Usuario autenticado. userID: ' . $dataSession["user"]->userID);

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new Exception(NOT_ACCESS_FUNCTION);
            }

            $transactionNumber = $this->request->getPost("transactionNumber");
            if (!$transactionNumber) {
                throw new Exception(NOT_PARAMETER);
            }

            log_message('info', '[app_afx_fixedassent_transferpiece::searchTransactionMaster] Buscando transactionNumber: ' . $transactionNumber);

            $objTM = $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID, $transactionNumber);

            if (!$objTM)
                throw new Exception("NO SE ENCONTRO EL DOCUMENTO");

            log_message('info', '[app_afx_fixedassent_transferpiece::searchTransactionMaster] Documento encontrado. transactionMasterID: ' . $objTM->transactionMasterID);

            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS,
                'companyID' => $objTM->companyID,
                'transactionID' => $objTM->transactionID,
                'transactionMasterID' => $objTM->transactionMasterID
            ));

        } catch (Exception $ex) {
            log_message('error', '[app_afx_fixedassent_transferpiece::searchTransactionMaster] Exception: ' . $ex->getMessage() . ' en linea ' . $ex->getLine());
            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));
        }
    }
}
