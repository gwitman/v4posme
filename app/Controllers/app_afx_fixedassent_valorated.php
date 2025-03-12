<?php
//posme:2025-11-03
namespace App\Controllers;

use Exception;

class app_afx_fixedassent_valorated extends _BaseController
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

            $objComponent        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_valorated");
            if (!$objComponent)
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_valorated' NO EXISTE...");


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
                $dataViewData                   = $this->core_web_view->getViewByName($this->session->get('user'), $objComponent->componentID, "LISTA DE VALORACIONES DE ACTIVOS FIJOS", CALLERID_LIST, $resultPermission, $parameter);
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
            $dataSession["head"]                = /*--inicio view*/ view('app_afx_fixedassent_valorated/list_head'); //--finview
            $dataSession["footer"]              = /*--inicio view*/ view('app_afx_fixedassent_valorated/list_footer'); //--finview
            $dataSession["body"]                = $dataViewRender;
            $dataSession["script"]              = /*--inicio view*/ view('app_afx_fixedassent_valorated/list_script'); //--finview
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
            $this->validation->setRule("txtYearID","Año","required");
            $this->validation->setRule("txtMonthID","Mes","required");

            //Validar Formulario
            if (!$this->validation->withRequest($this->request)->run()) {
                $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_valorated/add');
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
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_valorated/add');
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

            $objComponentFixedAssetValorated        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_valorated");
            if (!$objComponentFixedAssetValorated) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_valorated' NO EXISTE...");
            }

            $transactionID                                      = $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_fixedassent_valorated", 0);

            $dataView["company"]                                = $dataSession["company"];
            $dataView["objComponentFixedAsset"]                 = $objComponentFixedAsset;
            $dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_fixedassent_valorated", "statusID", $companyID, $branchID, $roleID);
            $dataView["objCausal"]                              = $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
            $dataView["objListCurrency"]                        = $this->Company_Currency_Model->getByCompany($companyID);
            $dataView["objCurrency"]                            = $this->core_web_currency->getCurrencyDefault($companyID);
            $objCompanyParameter 				                = $this->core_web_parameter->getParameter("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$dataView["objListAccountingPeriod"]	            = $this->Component_Period_Model->get_rowByNotClosed($dataSession["user"]->companyID,$objCompanyParameter->value);

            //Renderizar Resultado 
            $dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]             = $this->core_web_notification->get_message();
            $dataSession["head"]                = /*--inicio view*/ view('app_afx_fixedassent_valorated/news_head', $dataView); //--finview
            $dataSession["body"]                = /*--inicio view*/ view('app_afx_fixedassent_valorated/news_body', $dataView); //--finview
            $dataSession["script"]              = /*--inicio view*/ view('app_afx_fixedassent_valorated/news_script', $dataView); //--finview
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

            $objComponentFixedAssetValorated        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_valorated");
            if (!$objComponentFixedAssetValorated) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_valorated' NO EXISTE...");
            }

            $objComponentEmployee    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployee) {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            $companyID                              = $dataSession["user"]->companyID;
            $branchID                               = $dataSession["user"]->branchID;
			$transactionID 							= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_fixedassent_valorated",0);

            $objTM["companyID"]                     = $companyID;
            $objTM["transactionNumber"]             = $this->core_web_counter->goNextNumber($companyID, $branchID, "tb_transaction_master_fixedassent_valorated", 0);
            $objTM["transactionID"]                 = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_fixedassent_valorated", 0);
            $objTM["branchID"]                      = $branchID;
			$objTM["transactionCausalID"]           = $this->core_web_transaction->getDefaultCausalID($companyID,$transactionID);		
            $objTM["transactionOn"]                 = /*inicio get post*/ $this->request->getPost("txtDate");
            $objTM["componentID"]                   = $objComponentFixedAssetValorated->componentID;
            $objTM["note"]                          = /*inicio get post*/ $this->request->getPost("txtComment");
            $objTM["reference1"]                    = /*inicio get post*/ $this->request->getPost("txtReference1");
            $objTM["reference2"]                    = /*inicio get post*/ $this->request->getPost("txtReference2");
            $objTM["reference3"]                    = /*inicio get post*/ $this->request->getPost("txtReference3");
            $objTM["currencyID"]                    = /*inicio get post*/ $this->request->getPost("txtCurrencyID");
            $objTM["amount"]                        = /*inicio get post*/ $this->request->getPost("txtTotalAmount");
            $objTM["statusID"]                      = /*inicio get post*/ $this->request->getPost("txtStatusID");
            $objTM["classID"]                       = /*inicio get post*/ $this->request->getPost("txtYearID");
            $objTM["areaID"]                        = /*inicio get post*/ $this->request->getPost("txtMonthID");
            $objTM["isActive"]                      = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            $db = db_connect();
            $db->transStart();
            $transactionMasterID                    = $this->Transaction_Master_Model->insert_app_posme($objTM);

            $arrayListFixedAssetID                  = $this->request->getPost("txtFixedAssetID");
            $arrayListFixedAssetRatio               = $this->request->getPost("txtFixedAssetRatio");
            $arrayListFixedAssetCurrentAmount       = $this->request->getPost("txtFixedAssetCurrentAmount");

            if (!empty($arrayListFixedAssetID)) {
                foreach ($arrayListFixedAssetID as $key => $value) {
                    $assetID                        = $value;
                    $assetRatio                     = $arrayListFixedAssetRatio[$key];
                    $currentAmount                  = $arrayListFixedAssetCurrentAmount[$key] ?: 0;

                    $objTMD["companyID"]            = $companyID;
                    $objTMD["transactionID"]        = $objTM["transactionID"];
                    $objTMD["transactionMasterID"]  = $transactionMasterID;
                    $objTMD["componentID"]          = $objComponentFixedAssetValorated->componentID;
                    $objTMD["componentItemID"]      = $assetID;
                    $objTMD["amount"]               = $assetRatio;
                    $objTMD["discount"]             = $currentAmount;
                    $objTMD["tax1"]                 = $assetRatio + $currentAmount;
                    $objTMD["isActive"]             = 1;
                    $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
                }
            }

            if (!file_exists(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentFixedAssetValorated->componentID . "/component_item_" . $transactionMasterID)) {
                mkdir(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentFixedAssetValorated->componentID . "/component_item_" . $transactionMasterID, 0700, true);
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_valorated/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
            } else {
                $db->transRollback();
                $this->core_web_notification->set_message(true, $this->$db->_error_message());
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_valorated/add');
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

            $objComponentFixedAssetValorated  = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_valorated");
            if (!$objComponentFixedAssetValorated) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_valorated' NO EXISTE...");
            }

            if ((!$companyID) || (!$transactionID) || (!$transactionMasterID)) {
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_valorated/add');
            }


            $dataView["company"]                                = $dataSession["company"];
            $dataView["objTM"]                                  = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            $dataView["objComponentFixedAsset"]                 = $objComponentFixedAsset;
            $dataView["objComponentFixedAssetValorated"]        = $objComponentFixedAssetValorated;
            $dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_fixedassent_valorated", "statusID", $companyID, $branchID, $roleID);
            $dataView["objCausal"]                              = $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
            $dataView["objTMD"]                                 = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentFixedAssetValorated->componentID);
            $dataView["objListCurrency"]                        = $this->Company_Currency_Model->getByCompany($companyID);
            $dataView["objCurrency"]                            = $this->core_web_currency->getCurrencyDefault($companyID);
            $objCompanyParameter 				                = $this->core_web_parameter->getParameter("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$dataView["objListAccountingPeriod"]	            = $this->Component_Period_Model->get_rowByNotClosed($dataSession["user"]->companyID,$objCompanyParameter->value);

            
			$objCompanyParameter 	= $this->core_web_parameter->getParameter("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$cycles					= $this->Component_Cycle_Model->get_rowByNotClosed($dataSession["user"]->companyID,$dataView["objTM"]->classID,$objCompanyParameter->value);
			if($cycles)
			foreach($cycles as $key => $value){
				$value->startOnFormat 	= helper_DateToSpanish($value->startOnFormat,"Y-F");
				$value->endOnFormat 	= helper_DateToSpanish($value->endOnFormat,"Y-F");
				$cycles[$key] = $value;
			}

            $dataView["objListAccountingCycle"]                 = $cycles;
			
            //RENDERIZAR RESULTADO
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = view('app_afx_fixedassent_valorated/edit_head', $dataView);
            $dataSession["body"]            = view('app_afx_fixedassent_valorated/edit_body', $dataView);
            $dataSession["script"]          = view('app_afx_fixedassent_valorated/edit_script', $dataView);
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

            $objComponentFixedAssetValorated  = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_fixedassent_valorated");
            if (!$objComponentFixedAssetValorated) {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_fixedassent_valorated' NO EXISTE...");
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
            $objTMNew["transactionOn"]              = /*inicio get post*/ $this->request->getPost("txtDate");
            $objTMNew["note"]                       = /*inicio get post*/ $this->request->getPost("txtComment");
            $objTMNew["reference1"]                 = /*inicio get post*/ $this->request->getPost("txtReference1");
            $objTMNew["reference2"]                 = /*inicio get post*/ $this->request->getPost("txtReference2");
            $objTMNew["reference3"]                 = /*inicio get post*/ $this->request->getPost("txtReference3");
            $objTMNew["currencyID"]                 = /*inicio get post*/ $this->request->getPost("txtCurrencyID");
            $objTMNew["amount"]                     = /*inicio get post*/ $this->request->getPost("txtTotalAmount");
            $objTMNew["statusID"]                   = /*inicio get post*/ $this->request->getPost("txtStatusID");
            $objTMNew["classID"]                    = /*inicio get post*/ $this->request->getPost("txtYearID");
            $objTMNew["areaID"]                     = /*inicio get post*/ $this->request->getPost("txtMonthID");

            //Validar si el estado permite editar
            if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_valorated", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new Exception(NOT_WORKFLOW_EDIT);

            $db = db_connect();
            $db->transStart();


            if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_valorated", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
                $objTMNew                           = array();
                $objTMNew["statusID"]               = $this->request->getPost("txtStatusID");
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
            } else {
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
            }

            $arrayCurrentListFixedAssetID                   = $this->request->getPost("txtCurrentFixedAssetID");
            $arrayNewListFixedAssetID                       = $this->request->getPost("txtNewFixedAssetID");
            $arrayListFixedAssetRatio                       = $this->request->getPost("txtFixedAssetRatio");
            $arrayListFixedAssetCurrentAmount               = $this->request->getPost("txtFixedAssetCurrentAmount");

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
                    $assetRatio                 = $arrayListFixedAssetRatio[$key];
                    $currentAmount              = $arrayListFixedAssetCurrentAmount[$key] ?: 0;

                    $objTMDNew["amount"]        = $assetRatio;
                    $objTMDNew["discount"]      = $currentAmount;
                    $objTMDNew["tax1"]          = $assetRatio + $currentAmount;
                    $this->Transaction_Master_Detail_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $assetID, $objTMDNew);
                }
            }

            //Ingresar los nuevos activos fijos
            if (!empty($arrayNewListFixedAssetID)) {
                $currentFixedAssetListLength    = $arrayCurrentListFixedAssetID ? count($arrayCurrentListFixedAssetID) : 0;
                $newFixedAssetListLength        = count($arrayNewListFixedAssetID); 
                $mixedFixedAssetListLength      = $currentFixedAssetListLength + $newFixedAssetListLength;
                $lapsCounter                    = 0;

                for($i = $currentFixedAssetListLength; $i < $mixedFixedAssetListLength; $i++)
                {
                    $assetID                            = $arrayNewListFixedAssetID[$lapsCounter];
                    $assetRatio                         = $arrayListFixedAssetRatio[$i];
                    $assetCurrentAmount                 = $arrayListFixedAssetCurrentAmount[$i] ?: 0;      
                    
                    $objTMDNew                          = [];
                    $objTMDNew["companyID"]             = $companyID;
                    $objTMDNew["transactionID"]         = $objTM->transactionID;
                    $objTMDNew["transactionMasterID"]   = $transactionMasterID;
                    $objTMDNew["componentID"]           = $objComponentFixedAssetValorated->componentID;
                    $objTMDNew["componentItemID"]       = $assetID;
                    $objTMDNew["amount"]                = $assetRatio;
                    $objTMDNew["discount"]              = $assetCurrentAmount;
                    $objTMDNew["tax1"]                  = $assetRatio + $assetCurrentAmount;
                    $objTMDNew["isActive"]              = 1;
                    $this->Transaction_Master_Detail_Model->insert_app_posme($objTMDNew);

                    $lapsCounter++;
                }
            }


            if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_valorated", "statusID", $objTMNew["statusID"], COMMAND_APLICABLE, $companyID, $branchID, $roleID)) {

                $objListTMD                             = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentFixedAssetValorated->componentID);
                
                foreach($objListTMD as $tmd)
                {   
                    $objFA                              = [];
                    $objFA["currentAmount"]             = $tmd->newCurrentAmount;
                    $this->Fixed_Assent_Model->update_app_posme($companyID, $branchID, $tmd->componentItemID, $objFA);
                }   
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_valorated/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
            } else {
                $db->transRollback();
                $this->core_web_notification->set_message(true, $this->$db->_error_message());
                $this->response->redirect(base_url() . "/" . 'app_afx_fixedassent_valorated/add');
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
            if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_fixedassent_valorated", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
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
            $objTM->transactionOn           = date_format(date_create($objTM->transactionOn), "Y-m-d");
            $objUser                        = $this->User_Model->get_rowByPK($companyID, $branchID, $dataSession["user"]->userID);

            $objYearPeriod                  = $this->Component_Period_Model->get_rowByPK($objTM->classID);
            $objMonthCycle                  = $this->Component_Cycle_Model->get_rowByPK($objTM->classID,$objTM->areaID);

            $objFAD["type"]                 = "VALORACION";
            $objFAD["transactionOn"]        = $objTM->transactionOn;
            $objFAD["status"]               = $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($objTM->statusID)[0]->name;
            $objFAD["causalName"]           = $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID, $transactionID, $objTM->transactionCausalID)->name;
            $objFAD["reference1"]           = $objTM->reference1;
            $objFAD["reference2"]           = $objTM->reference2;
            $objFAD["reference3"]           = $objTM->reference3;
            $objFAD["comment"]              = $objTM->note;
            $objFAD["yearPeriod"]           = helper_DateToSpanish($objYearPeriod->startOn, "Y");
            $objFAD["monthCycle"]           = helper_DateToSpanish($objMonthCycle->startOn,"F");;
            $objFAD["totalAmount"]          = $objTM->amount;

            $objCurrency                    = $this->Currency_Model->get_rowByPK($objTM->currencyID);
            
            //Generar Reporte
            $html = helper_reporteA4FixedAssetDepreciationAndValuation(
                $objFAD,
                $objCompany,
                $objParameterLogo,
                $objCurrency,
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
                $path        = "./resource/file_company/company_" . $companyID . "/component_123/component_item_" . $transactionMasterID . "/" . $fileNamePut;

                file_put_contents(
                    $path,
                    $this->dompdf->output()
                );

                chmod($path, 644);

                echo "<a 
					href='" . base_url() . "/resource/file_company/company_" . $companyID . "/component_123/component_item_" . $transactionMasterID . "/" .
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
