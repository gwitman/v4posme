<?php

namespace App\Controllers;

use Config\Services;
use Exception;

class app_cxc_document extends _BaseController
{

    function index()
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

            //Obtener el componente
            $objComponent        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
            if (!$objComponent) {
                throw new Exception("00409 EL COMPONENTE ' tb_customer_credit_document' NO EXISTE...");
            }



            $objFecha       = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
            $fecha          = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "fecha"); //--finuri
            $fecha          = !$fecha ? $objFecha->format("Y-m-d") : $fecha;
            $objFecha       = \DateTime::createFromFormat('Y-m-d', $fecha);

            $dataViewID     = helper_SegmentsValue($this->uri->getSegments(), "dataViewID");


            //Vista por defecto 
            if ($dataViewID == null || $dataViewID == "null") {
                $targetComponentID           = $this->session->get('company')->flavorID;
                $parameter["{companyID}"]    = $this->session->get('user')->companyID;
                $parameter["{fecha}"]        = $fecha;
                $dataViewData                = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);


                if (!$dataViewData) {
                    $targetComponentID           = 0;
                    $parameter["{companyID}"]    = $this->session->get('user')->companyID;
                    $parameter["{fecha}"]        = $fecha;
                    $dataViewData                = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
                }

                if ($dataSession["user"]->useMobile == 1) {
                    $dataViewRender                = $this->core_web_view->renderGreedWithHtmlInFildMobile($dataViewData, 'ListView', "fnTableSelectedRow");
                } else {
                    $dataViewRender                = $this->core_web_view->renderGreedWithHtmlInFild($dataViewData, 'ListView', "fnTableSelectedRow");
                }
            }
            //Otra vista
            else {
                $parameter["{companyID}"]    = $this->session->get('user')->companyID;
                $parameter["{fecha}"]        = $fecha;
                $dataViewData                = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);

                if ($dataSession["user"]->useMobile == 1) {
                    $dataViewRender                = $this->core_web_view->renderGreedMobile($dataViewData, 'ListView', "fnTableSelectedRow");
                } else {
                    $dataViewRender                = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
                }
            }


            $dataViewHeader["company"]                          = $dataSession["company"];
            $dataViewHeader["objFecha"]                         = $objFecha;

            $dataSession["useMobile"]                           = $dataSession["user"]->useMobile;
            $dataSession["notification"]                        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]                             = $this->core_web_notification->get_message();
            $dataSession["head"]                                = /*--inicio view*/ view('app_cxc_document/list_head', $dataViewHeader); //--finview
            $dataSession["footer"]                              = "";
            $dataSession["body"]                                = $dataViewRender;
            $dataSession["script"]                              = /*--inicio view*/ view('app_cxc_document/list_script'); //--finview
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

                $resultPermission        = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission     == PERMISSION_NONE)
                    throw new Exception(NOT_ALL_EDIT);
            }



            $customerCreditDocumentID       = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "customerCreditDocumentID"); //--finuri
            $objCustomerCreditDocument      = $this->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
            $customerEntityID               = $objCustomerCreditDocument->entityID;
            $companyID                      = $dataSession["user"]->companyID;
            $branchID                       = $dataSession["user"]->branchID;
			$entityNumber 					= "";
            $entityIdentification           = "";
            $entityPhoneNumber              = "";
            $entityBalanceDol               = 0;
            $entityLimitCreditDol           = 0;

            //Obtener Entidad
            $objCustomer                    = $this->Customer_Model->get_rowByEntity($companyID, $customerEntityID);
            if(!is_null($objCustomer))
            {
                $entityNumber               = $objCustomer->customerNumber;
                $entityIdentification       = $objCustomer->identification;
                $entityPhoneNumber          = $objCustomer->phoneNumber; //Solo los objetos de tipo customer tienen numeros de telefono
            }

            if(is_null($objCustomer))
            {
				$objCustomer			    = $this->Employee_Model->get_rowByPK($companyID, $branchID, $customerEntityID);

                if($objCustomer != null)
                {   
                    $entityNumber           = $objCustomer->employeNumber;
                    $entityIdentification   = $objCustomer->numberIdentification;
                }
            }

            if(is_null($objCustomer))
            {
				$objCustomer                = $this->Provider_Model->get_rowByPK($companyID, $branchID, $customerEntityID);	

                if($objCustomer != null)
                {   
                    $entityNumber           = $objCustomer->providerNumber;
                    $entityIdentification   = $objCustomer->numberIdentification;
                }
            }

            $objCustomerCreditAmortization  = $this->Customer_Credit_Amortization_Model->get_rowByDocument($customerCreditDocumentID);
            $objCustomerEntityRelated       = $this->Customer_Credit_Document_Endity_Related_Model->get_rowByDocument($customerCreditDocumentID); 
            $roleID                         = $dataSession["role"]->roleID;
            $branchIDUser                   = $dataSession["user"]->branchID;
            
            if (!$companyID) {
                $this->response->redirect(base_url() . "/" . 'app_cxc_document/index');
            }

            
            
            $dataView["objCustomer"]                        = $objCustomer;
            $dataView["entityNumber"]                       = $entityNumber;
            $dataView["entityIdentification"]               = $entityIdentification;
            $dataView["entityPhoneNumber"]                  = $entityPhoneNumber;
            $branchID                                       = $dataView["objCustomer"]->branchID;
            $entityID                                       = $dataView["objCustomer"]->entityID;
            $dataView["company"]                            = $dataSession["company"];
            $dataView["objLegal"]                           = $this->Legal_Model->get_rowByPK($companyID, $branchID, $entityID);
            $dataView["useMobile"]                          = $dataSession["user"]->useMobile;
            $dataView["objNatural"]                         = $this->Natural_Model->get_rowByPK($companyID, $branchID, $entityID);
            $dataView["objListCustomer"]                    = $this->Customer_Model->get_rowByCompany($companyID);
            $dataView["objCustomerCredit"]                  = $this->Customer_Credit_Model->get_rowByPK($companyID, $branchID, $entityID);
            $dataView["objListWorkflowStage"]               = $this->core_web_workflow->getWorkflowAllStage("tb_customer_credit_document", "statusID", $companyID, $branchIDUser, $roleID);
            $dataView["objCustomerCreditLine"]              = $this->Customer_Credit_Line_Model->get_rowByEntity($companyID, $branchID, $entityID);
            $dataView["objCustomerEntityRelated"]           = $objCustomerEntityRelated;
            $dataView["objCustomerCreditDocument"]          = $objCustomerCreditDocument;
            $dataView["objCustomerCreditAmortization"]      = $objCustomerCreditAmortization;
            $dataView["objListWorkflowStageAmortization"]   = $this->core_web_workflow->getWorkflowAllStage("tb_customer_credit_amoritization", "statusID", $companyID, $branchIDUser, $roleID);
			$dataView["objListCurrency"]					= $this->Currency_Model->getList();
            $dataView["objCatalogLegalName"]                = $this->Legal_Model->get_rowByCompany($companyID, $branchID);  

			$dataView["objCatalogEntityType"]			    = $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","type",$companyID);
			$dataView["objCatalogEntityTypeCredit"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeCredit",$companyID);
			$dataView["objCatalogEntityStatusCredit"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","statusCredit",$companyID);
			$dataView["objCatalogEntityTypeGarantia"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeGarantia",$companyID);
			$dataView["objCatalogEntityTypeRecuperation"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeRecuperation",$companyID);

            if($dataView["objCustomerCredit"] != null)
            {
                $dataView["entityBalanceDol"]               = $dataView["objCustomerCredit"]->balanceDol;
                $dataView["entityLimitCreditDol"]           = $dataView["objCustomerCredit"]->limitCreditDol;
            }
            else
            {
                $dataView["entityBalanceDol"]               = $entityBalanceDol;
                $dataView["entityLimitCreditDol"]           = $entityLimitCreditDol;
            }
            
            

            //Renderizar Resultado
            $dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]             = $this->core_web_notification->get_message();

            $dataSession["head"]                = /*--inicio view*/ view('app_cxc_document/edit_head', $dataView); //--finview			
            $dataSession["body"]                = /*--inicio view*/ view('app_cxc_document/edit_body', $dataView); //--finview
            $dataSession["script"]              = /*--inicio view*/ view('app_cxc_document/edit_script', $dataView); //--finview
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

    function save($mode = "", $dataSession = null)
    {
        $mode = helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);
        //AUTENTICADO
        if (!$this->core_web_authentication->isAuthenticated())
            throw new \Exception(USER_NOT_AUTENTICATED);
        $dataSession        = $this->session->get();

        $this->validation->setRule("txtIdentification", "Identificacion", "required");


        //Validar Formulario
        if (!$this->validation->withRequest($this->request)->run()) {
            $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
            $this->core_web_notification->set_message(true, $stringValidation);
            $this->response->redirect(base_url() . "/" . 'app_cxc_document/index');
            exit;
        }

        //Guardar o Editar Registro						
        if ($mode == "new") {
        } else if ($mode == "edit") {
            $this->updateElement($dataSession);
        } else {
            $stringValidation = "El modo de operacion no es correcto (new|edit)";
            $this->core_web_notification->set_message(true, $stringValidation);
            $this->response->redirect(base_url() . "/" . 'app_cxc_document/index');
            exit;
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

            //Obtener el Componente de Transacciones Other Input to Inventory
            $objComponent                            = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if (!$objComponent)
                throw new Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");


            $companyID_                                 = /*inicio get post*/ $this->request->getPost("txtCompanyID");
            $branchID_                                  = /*inicio get post*/ $this->request->getPost("txtBranchID");
            $entityID_                                  = /*inicio get post*/ $this->request->getPost("txtEntityID");
            $objCustomer                                = $this->Customer_Model->get_rowByPK($companyID_, $branchID_, $entityID_);

            // Validar Edicion por el Usuario
            if ($resultPermission     == PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID)) {
                throw new Exception(NOT_EDIT);
            }


            $db = db_connect();
            $db->transStart();

            //Actualizar Customer Credit
            $objCustomerCreditNew["limitCreditDol"]         = helper_StringToNumber(/*inicio get post*/$this->request->getPost("txtLimitCreditDol"));
            $objCustomerCreditNew["balanceDol"]             = helper_StringToNumber(/*inicio get post*/$this->request->getPost("txtBalanceDol"));			
            $this->Customer_Credit_Model->update_app_posme($companyID_, $branchID_, $entityID_, $objCustomerCreditNew);


            //Lineas de Creditos
            $arrayListCustomerCreditLineID      = /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");
            $arrayListCreditLineID              = /*inicio get post*/ $this->request->getPost("txtCreditLineID");
            $arrayListCreditStatusID            = /*inicio get post*/ $this->request->getPost("txtCreditStatusID");
            $arrayListCreditLimit               = /*inicio get post*/ $this->request->getPost("txtLineLimit");
            $arrayListCreditBalance             = /*inicio get post*/ $this->request->getPost("txtLineBalance");

            if (!empty($arrayListCustomerCreditLineID))
                foreach ($arrayListCustomerCreditLineID as $key => $value) {

                    $customerCreditLineID                               = $value;

                    $objCustomerCreditLine                              = $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
                    $objCustomerCreditLineNew                           = NULL;
                    $objCustomerCreditLineNew["creditLineID"]           = $arrayListCreditLineID[$key];
                    $objCustomerCreditLineNew["limitCredit"]            = helper_StringToNumber($arrayListCreditLimit[$key]);
                    $objCustomerCreditLineNew["balance"]                = helper_StringToNumber($arrayListCreditBalance[$key]);
                    $objCustomerCreditLineNew["statusID"]               = $arrayListCreditStatusID[$key];

                    //Si el balance es mayor que el limite igual el balance al limite
                    if ($objCustomerCreditLineNew["balance"] > $objCustomerCreditLineNew["limitCredit"])
                        $objCustomerCreditLineNew["balance"] = $objCustomerCreditLineNew["limitCredit"];

                    //actualizar
                    $this->Customer_Credit_Line_Model->update_app_posme($customerCreditLineID, $objCustomerCreditLineNew);
                }


            //Actualizar Balance
            if ($objCustomerCreditNew["balanceDol"] > $objCustomerCreditNew["limitCreditDol"]) {
                $objCustomerCreditNew["balanceDol"] = $objCustomerCreditNew["limitCreditDol"];
                $this->Customer_Credit_Model->update_app_posme($companyID_, $branchID_, $entityID_, $objCustomerCreditNew);
            }

            //Actualizar Documento
            $customerCreditDocumentID                   = /*inicio get post*/ $this->request->getPost("txtCustomerCreditDocumentID");
            $customerCreditDocumentBalance              = /*inicio get post*/ $this->request->getPost("txtCreditDocumentBalance");
            $customerCreditDocumentStatusID             = /*inicio get post*/ $this->request->getPost("txtCreditDocumentStatusID");
			$customerCreditDocumentoDateOn              = /*inicio get post*/ $this->request->getPost("txtDocumentDate");
			
            $objCustomerCreditDocumentNew               = NULL;
            $objCustomerCreditDocumentNew["balance"]    = $customerCreditDocumentBalance;
            $objCustomerCreditDocumentNew["statusID"]   = $customerCreditDocumentStatusID;
			$objCustomerCreditDocumentNew["dateOn"]     = $customerCreditDocumentoDateOn;			
            $this->Customer_Credit_Document_Model->update_app_posme($customerCreditDocumentID, $objCustomerCreditDocumentNew);

            //Actualizar Entity Documento
            $arrayListEntityRelatedID                       = $this->request->getPost('txtEntityRelatedID');
            $customerCreditEntityDocumentID                 = $this->request->getPost('txtCustomerCreditEntityDocumentID');
            $arrayListDocumentEntityID                      = $this->request->getPost('txtDocumentEntityID');
            $arrayListDocumentEntityType                    = $this->request->getPost('txtDocumentEntityType'); 
            $arrayListDocumentEntityTypeCredit              = $this->request->getPost('txtDocumentEntityTypeCredit');
            $arrayListDocumentEntityStatusCredit            = $this->request->getPost('txtDocumentEntityStatusCredit');
            $arrayListDocumentEntityTypeGarantia            = $this->request->getPost('txtDocumentEntityTypeGarantia');
            $arrayListDocumentEntityTypeRecuperation        = $this->request->getPost('txtDocumentEntityTypeRecuperation');   
            $arrayListDocumentEntityRatioDesembolso         = $this->request->getPost('txtRatioDesembolso');
            $arrayListDocumentEntityRatioBalance            = $this->request->getPost('txtRatioBalance');
            $arrayListDocumentEntityRatioBalanceExpired     = $this->request->getPost('txtRatioBalanceExpired');
            $arrayListDocumentEntityRatioShare              = $this->request->getPost('txtRatioShare');

            //Limpiar Entidades del Documento
            if(empty($arrayListEntityRelatedID))
            {
                $this->Customer_Credit_Document_Endity_Related_Model->deleteWhereDocumentID($customerCreditDocumentID);
            }else
            {
                $this->Customer_Credit_Document_Endity_Related_Model->deleteWhereIDNotIn($arrayListEntityRelatedID, $customerCreditDocumentID, $entityID_);
            }

                        
            if(!empty($arrayListEntityRelatedID))
            {
                foreach($arrayListEntityRelatedID as $key => $value)
                {
                    $entityRelatedID                                    = $value;
                   
                    if($entityRelatedID == 0)
                    {
                        $objEntityRelated                               = NULL;
                        $objEntityRelated["customerCreditDocumentID"]   = $customerCreditDocumentID;
                        $objEntityRelated["entityID"]                   = $arrayListDocumentEntityID[$key];
                        $objEntityRelated["type"]                       = $arrayListDocumentEntityType[$key];
                        $objEntityRelated["typeCredit"]                 = $arrayListDocumentEntityTypeCredit[$key];
                        $objEntityRelated["statusCredit"]               = $arrayListDocumentEntityStatusCredit[$key];
                        $objEntityRelated["typeGarantia"]               = $arrayListDocumentEntityTypeGarantia[$key];
                        $objEntityRelated["typeRecuperation"]           = $arrayListDocumentEntityTypeRecuperation[$key];
                        $objEntityRelated["ratioDesembolso"]            = helper_StringToNumber($arrayListDocumentEntityRatioDesembolso[$key]);
                        $objEntityRelated["ratioBalance"]               = helper_StringToNumber($arrayListDocumentEntityRatioBalance[$key]);
                        $objEntityRelated["ratioBalanceExpired"]        = helper_StringToNumber($arrayListDocumentEntityRatioBalanceExpired[$key]);
                        $objEntityRelated["ratioShare"]                 = helper_StringToNumber($arrayListDocumentEntityRatioShare[$key]);
                        $this->core_web_auditoria->setAuditCreated($objEntityRelated,$dataSession,$this->request);
                        $objEntityRelated["isActive"]                   = 1;
                        
                        $this->Customer_Credit_Document_Endity_Related_Model->insert_app_posme($objEntityRelated);
                    }
                    else
                    {
                        $objEntityRelated                                   = $this->Customer_Credit_Document_Endity_Related_Model->get_rowByPK($entityRelatedID);
                        $objEntityRelatedNew                                = NULL;
                        $objEntityRelatedNew["entityID"]                    = $arrayListDocumentEntityID[$key];
                        $objEntityRelatedNew["type"]                        = $arrayListDocumentEntityType[$key];
                        $objEntityRelatedNew["typeCredit"]                  = $arrayListDocumentEntityTypeCredit[$key];
                        $objEntityRelatedNew["statusCredit"]                = $arrayListDocumentEntityStatusCredit[$key];
                        $objEntityRelatedNew["typeGarantia"]                = $arrayListDocumentEntityTypeGarantia[$key];
                        $objEntityRelatedNew["typeRecuperation"]            = $arrayListDocumentEntityTypeRecuperation[$key];
                        $objEntityRelatedNew["ratioDesembolso"]             = helper_StringToNumber($arrayListDocumentEntityRatioDesembolso[$key]);
                        $objEntityRelatedNew["ratioBalance"]                = helper_StringToNumber($arrayListDocumentEntityRatioBalance[$key]);
                        $objEntityRelatedNew["ratioBalanceExpired"]         = helper_StringToNumber($arrayListDocumentEntityRatioBalanceExpired[$key]);
                        $objEntityRelatedNew["ratioShare"]                  = helper_StringToNumber($arrayListDocumentEntityRatioShare[$key]);
                        $this->core_web_auditoria->setAuditCreated($objEntityRelatedNew,$dataSession,$this->request);
                        $objEntityRelatedNew["isActive"]                    = 1;
                        $this->Customer_Credit_Document_Endity_Related_Model->update_app_posme($entityRelatedID, $customerCreditDocumentID, $objEntityRelatedNew);

                    }
                }
            }
           
            //Actualizar Amortizacion
            $listCustomerCreditAmortizationID                   = /*inicio get post*/ $this->request->getPost("txtCustomerCreditAmortizationID");
            $customerCreditAmortizationRemaining                = /*inicio get post*/ $this->request->getPost("txtRemaining");
            $customerCreditAmortizationDayDelay                 = /*inicio get post*/ $this->request->getPost("txtDayDelay");
			$customerCreditAmortizationDateApply                = /*inicio get post*/ $this->request->getPost("txtDateApply");
            $customerCreditAmortizationNote                     = /*inicio get post*/ $this->request->getPost("txtNote");
            $customerCreditAmortizationStatus                   = /*inicio get post*/ $this->request->getPost("txtAmortizationStatusID");
            $customerCreditAmortizationShareCapital             = /*inicio get post*/ $this->request->getPost("txtShareCapital");


            if (!empty($listCustomerCreditAmortizationID)) {
                foreach ($listCustomerCreditAmortizationID as $key => $value) {
                    $customerCreditAmortizationID                       = $value;
                    $objCustomerCreditAmortizationNew                   = NULL;
                    $objCustomerCreditAmortizationNew["remaining"]      = helper_StringToNumber($customerCreditAmortizationRemaining[$key]);
                    $objCustomerCreditAmortizationNew["dayDelay"]       = helper_StringToNumber($customerCreditAmortizationDayDelay[$key]);
                    $objCustomerCreditAmortizationNew["note"]           = $customerCreditAmortizationNote[$key];
                    $objCustomerCreditAmortizationNew["statusID"]       = $customerCreditAmortizationStatus[$key];
                    $objCustomerCreditAmortizationNew["shareCapital"]   = helper_StringToNumber($customerCreditAmortizationShareCapital[$key]);
					$objCustomerCreditAmortizationNew["dateApply"]      = $customerCreditAmortizationDateApply[$key];
                    $this->Customer_Credit_Amortization_Model->update_app_posme($customerCreditAmortizationID, $objCustomerCreditAmortizationNew);
                }
            }

            if ($db->transStatus() !== false) {
                $db->transCommit();
                $this->core_web_notification->set_message(false, SUCCESS);
                $this->response->redirect(base_url() . "/" . 'app_cxc_document/edit/customerCreditDocumentID/' . $customerCreditDocumentID);
            } else {
                $db->transRollback();
                $this->core_web_notification->set_message(true, $this->db->_error_message());
                $this->response->redirect(base_url() . "/" . 'app_cxc_document/index');
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
    
    function add_document_entity()
    {
			
        //AUTENTICACION
        if(!$this->core_web_authentication->isAuthenticated())
        throw new Exception(USER_NOT_AUTENTICATED);
        $dataSession		= $this->session->get();
        
        //PERMISO SOBRE LA FUNCION
        if(APP_NEED_AUTHENTICATION == true){
            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
            
            if(!$permited)
            throw new Exception(NOT_ACCESS_CONTROL);
            
            $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
            if ($resultPermission 	== PERMISSION_NONE)
            throw new Exception(NOT_ACCESS_FUNCTION);			
        }
        
        $companyID 								            = $dataSession["user"]->companyID;
        $branchUserID                                       = $dataSession["user"]->branchID;

        $dataView["objCatalogLegalName"]                    = $this->Legal_Model->get_rowByCompany($companyID, $branchUserID);  

        $dataView["objListCustomer"]                        = $this->Customer_Model->get_rowByCompany($companyID);	
        $dataView["objListCatalogEntityType"]               = $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","type",$companyID);
        $dataView["objListCatalogEntityTypeCredit"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeCredit",$companyID);
        $dataView["objListCatalogEntityStatusCredit"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","statusCredit",$companyID);	
        $dataView["objListCatalogEntityTypeGarantia"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeGarantia",$companyID);
        $dataView["objListCatalogEntityTypeRecuperation"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeRecuperation",$companyID);

        //Renderizar Resultado
        $dataSession["message"]		= "";
        $dataSession["head"]		= /*--inicio view*/ view('app_cxc_document/popup_add_documententity_head',$dataView);//--finview
        $dataSession["body"]		= /*--inicio view*/ view('app_cxc_document/popup_add_documententity_body',$dataView);//--finview
        $dataSession["script"]		= /*--inicio view*/ view('app_cxc_document/popup_add_documententity_script',$dataView);//--finview
        return view("core_masterpage/default_popup",$dataSession);//--finview-r
    }
    
    function edit_document_entity()
    {
			
        //AUTENTICACION
        if(!$this->core_web_authentication->isAuthenticated())
        throw new Exception(USER_NOT_AUTENTICATED);
        $dataSession		= $this->session->get();
        
        //PERMISO SOBRE LA FUNCION
        if(APP_NEED_AUTHENTICATION == true){
            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
            
            if(!$permited)
            throw new Exception(NOT_ACCESS_CONTROL);
            
            $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
            if ($resultPermission 	== PERMISSION_NONE)
            throw new Exception(NOT_ACCESS_FUNCTION);			
        }
        
        $companyID 								            = $dataSession["user"]->companyID;
        $branchUserID                                       = $dataSession["user"]->branchID;
        $entityRelatedID                                    = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "ccEntityRelatedID"); //--finuri  

        $dataView["objEntityRelated"]                       = $this->Customer_Credit_Document_Endity_Related_Model->get_rowByPK($entityRelatedID);
        
        $dataView["objCatalogLegalName"]                    = $this->Legal_Model->get_rowByCompany($companyID, $branchUserID);  
        $dataView["objListCustomer"]                        = $this->Customer_Model->get_rowByCompany($companyID);	
        $dataView["objListCatalogEntityType"]               = $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","type",$companyID);
        $dataView["objListCatalogEntityTypeCredit"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeCredit",$companyID);
        $dataView["objListCatalogEntityStatusCredit"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","statusCredit",$companyID);	
        $dataView["objListCatalogEntityTypeGarantia"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeGarantia",$companyID);
        $dataView["objListCatalogEntityTypeRecuperation"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_document_entity_related","typeRecuperation",$companyID);

        //Renderizar Resultado
        $dataSession["message"]		= "";
        $dataSession["head"]		= /*--inicio view*/ view('app_cxc_document/popup_edit_documententity_head',$dataView);//--finview
        $dataSession["body"]		= /*--inicio view*/ view('app_cxc_document/popup_edit_documententity_body',$dataView);//--finview
        $dataSession["script"]		= /*--inicio view*/ view('app_cxc_document/popup_edit_documententity_script',$dataView);//--finview
        return view("core_masterpage/default_popup",$dataSession);//--finview-r
    }
}
