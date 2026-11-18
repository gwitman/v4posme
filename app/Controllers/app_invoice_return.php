<?php
//posme:2026-11-17
namespace App\Controllers;

/**
 * Devolucion de productos facturados.
 * El cliente devuelve uno o varios productos de una factura aplicada.
 * Al aplicar se generan, en una sola transaccion de BD, una Nota de Credito aplicada
 * (saldo a favor del cliente, en la moneda de la factura) y una Otra Entrada
 * (productos devueltos) en la bodega de la factura.
 * Documentos viejos (sustitucion) pueden tener reference4 apuntando a una Otra Salida.
 */
class app_invoice_return extends _BaseController
{
    /** Componente / transaccion del documento de devolucion */
    private $componentName          = "tb_transaction_master_replacement";

    /** Componente / transaccion de la factura */
    private $componentNameBilling   = "tb_transaction_master_billing";

    /** Longitud maxima de tb_transaction_master.note */
    private $noteMaxLength          = 3500;

    /**
     * Lista de documentos de devolucion (data view por defecto del componente).
     * @param int|null $dataViewID Vista alterna
     */
    function index($dataViewID = null)
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            $resultPermission = 0;
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);
            }

            //Obtener el componente para mostrar la lista
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName($this->componentName);
            if (!$objComponent)
                throw new \Exception("EL COMPONENTE '" . $this->componentName . "' NO EXISTE...");

            $esMovil = $dataSession["user"]->useMobile == 1 || $this->request->getUserAgent()->isMobile();

            $parameter["{companyID}"] = $this->session->get('user')->companyID;
            if ($dataViewID == null) {
                //Vista por defecto
                $targetComponentID = 0;
                $dataViewData      = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
            } else {
                //Otra vista
                $dataViewData      = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
            }
            $dataViewRender = $esMovil ? $this->core_web_view->renderGreedMobile($dataViewData, 'ListView', "fnTableSelectedRow") : $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/ view('app_invoice_return/list_head'); //--finview
            $dataSession["footer"]       = /*--inicio view*/ view('app_invoice_return/list_footer'); //--finview
            $dataSession["body"]         = $dataViewRender;
            $dataSession["script"]       = /*--inicio view*/ view('app_invoice_return/list_script'); //--finview
            $dataSession["script"]       = $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
            return view("core_masterpage/default_masterpage", $dataSession); //--finview-r
        } catch (\Exception $ex) {
            return $this->renderErrorView(isset($dataSession) ? $dataSession : null, $ex);
        }
    }

    /**
     * Pantalla de nuevo documento de devolucion.
     */
    function add()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_INSERT);
            }

            $companyID = $dataSession["user"]->companyID;
            $branchID  = $dataSession["user"]->branchID;
            $roleID    = $dataSession["role"]->roleID;

            $dataView                           = $this->getCommonViewData($companyID);
            $dataView["useMobile"]              = $dataSession["user"]->useMobile;
            $dataView["objListWorkflowStage"]   = $this->core_web_workflow->getWorkflowInitStage($this->componentName, "statusID", $companyID, $branchID, $roleID);
            $dataView["objTMD"]                 = array();
            $dataView["objInvoice"]             = null;
            $dataView["objCustomer"]            = null;
            $dataView["isApplied"]              = false;
            $dataView["objOtherInput"]          = null;
            $dataView["objNoteCredit"]          = null;
            $dataView["objOtherOutputHistoric"] = null;

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/ view('app_invoice_return/news_head', $dataView); //--finview
            $dataSession["body"]         = /*--inicio view*/ view('app_invoice_return/news_body', $dataView); //--finview
            $dataSession["script"]       = /*--inicio view*/ view('app_invoice_return/news_script', $dataView); //--finview
            $dataSession["footer"]       = "";
            return view("core_masterpage/default_masterpage", $dataSession); //--finview-r
        } catch (\Exception $ex) {
            return $this->renderErrorView(isset($dataSession) ? $dataSession : null, $ex);
        }
    }

    /**
     * Pantalla de edicion / consulta de un documento de devolucion.
     */
    function edit()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            //La compania siempre es la de la sesion
            $companyID           = $dataSession["user"]->companyID;
            $transactionID       = (int) /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri
            $transactionMasterID = (int) /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri
            $branchID            = $dataSession["user"]->branchID;
            $roleID              = $dataSession["role"]->roleID;

            if (!$transactionID || !$transactionMasterID)
                return $this->response->redirect(base_url() . "/" . 'app_invoice_return/add');

            $objTM = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            if (!$objTM)
                throw new \Exception("EL DOCUMENTO DE DEVOLUCION NO EXISTE O FUE ELIMINADO");
            $objTM->transactionOn = date_format(date_create($objTM->transactionOn), "Y-m-d");

            //Factura y lineas disponibles (excluyendo este documento)
            $objInvoice  = $this->getInvoiceByTransactionMasterID($companyID, $objTM->reference1);
            $objCustomer = $this->getCustomerOrEmpty($companyID, $objTM->entityID);
            $lines       = $objInvoice ? $this->getInvoiceLines($companyID, $objInvoice, $transactionID, $transactionMasterID) : array();

            $dataView                           = $this->getCommonViewData($companyID);
            $dataView["useMobile"]              = $dataSession["user"]->useMobile;
            $dataView["objTM"]                  = $objTM;
            $dataView["objTMD"]                 = $this->getDetailForView($companyID, $transactionID, $transactionMasterID, $lines);
            $dataView["objInvoice"]             = $objInvoice ? $this->formatInvoiceForView($companyID, $objInvoice, $objCustomer) : null;
            $dataView["objCustomer"]            = $objCustomer;
            $dataView["objListWorkflowStage"]   = $this->core_web_workflow->getWorkflowStageByStageInit($this->componentName, "statusID", $objTM->statusID, $companyID, $branchID, $roleID);
            $dataView["isApplied"]              = ((int) $objTM->isApplied) == 1;
            $dataView["objOtherInput"]          = $this->getLinkedTransaction($companyID, $objTM->reference3);
            $dataView["objNoteCredit"]          = null;
            $dataView["objOtherOutputHistoric"] = null;

            //reference4: NC (devolucion) o, en documentos viejos de sustitucion, una Otra Salida
            $objLinked4 = $this->getLinkedTransaction($companyID, $objTM->reference4);
            if ($objLinked4) {
                if ((int) $objLinked4->transactionID == (int) $this->core_web_note_credit->getTransactionIDNoteCredit($companyID))
                    $dataView["objNoteCredit"]          = $objLinked4;
                else
                    $dataView["objOtherOutputHistoric"] = $objLinked4;
            }

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]      = $this->core_web_notification->get_message();
            $dataSession["head"]         = /*--inicio view*/ view('app_invoice_return/edit_head', $dataView); //--finview
            $dataSession["body"]         = /*--inicio view*/ view('app_invoice_return/edit_body', $dataView); //--finview
            $dataSession["script"]       = /*--inicio view*/ view('app_invoice_return/edit_script', $dataView); //--finview
            $dataSession["footer"]       = "";
            return view("core_masterpage/default_masterpage", $dataSession); //--finview-r
        } catch (\Exception $ex) {
            return $this->renderErrorView(isset($dataSession) ? $dataSession : null, $ex);
        }
    }

    /**
     * Guarda el documento (new | edit).
     * @param string $mode new | edit
     */
    function save($mode = "")
    {
        $mode = helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);

        //AUTENTICADO
        if (!$this->core_web_authentication->isAuthenticated())
            return redirect()->to(base_url("core_acount/login"));
        $dataSession = $this->session->get();

        $urlBack = base_url() . "/" . 'app_invoice_return/add';
        if ($mode == "edit") {
            $urlBack = $this->getUrlEdit(
                $dataSession["user"]->companyID,
                (int) $this->request->getPost("txtTransactionID"),
                (int) $this->request->getPost("txtTransactionMasterID")
            );
        }

        //Validar Formulario
        $this->validation->setRule("txtStatusID", "Estado", "required");
        $this->validation->setRule("txtTransactionOn", "Fecha", "required");
        $this->validation->setRule("txtInvoiceNumber", "Factura", "required");
        if (!$this->validation->withRequest($this->request)->run()) {
            $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
            $this->core_web_notification->set_message(true, $stringValidation);
            return $this->response->redirect($urlBack);
        }

        if ($mode == "new")
            return $this->insertElement($dataSession);
        else if ($mode == "edit")
            return $this->updateElement($dataSession);

        $this->core_web_notification->set_message(true, "El modo de operacion no es correcto (new|edit)");
        return $this->response->redirect(base_url() . "/" . 'app_invoice_return/add');
    }

    /**
     * Elimina (soft delete) un documento de devolucion no aplicado. Responde JSON.
     */
    function delete()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            $resultPermission = 0;
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);
            }

            //La compania siempre es la de la sesion
            $companyID           = $dataSession["user"]->companyID;
            $transactionID       = (int) /*inicio get post*/ $this->request->getPost("transactionID");
            $transactionMasterID = (int) /*inicio get post*/ $this->request->getPost("transactionMasterID");
            if (!$transactionID || !$transactionMasterID)
                throw new \Exception(NOT_PARAMETER);

            $objTM = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            if (!$objTM)
                throw new \Exception("EL DOCUMENTO DE DEVOLUCION NO EXISTE O YA FUE ELIMINADO");

            if ($resultPermission == PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
                throw new \Exception(NOT_DELETE);

            //Con documentos generados (Otra Entrada / Nota de Credito) no se puede eliminar
            if (((int) $objTM->isApplied) == 1 || trim((string) $objTM->reference3) != "" || trim((string) $objTM->reference4) != "")
                throw new \Exception("LA DEVOLUCION YA GENERO SUS DOCUMENTOS (OTRA ENTRADA / NOTA DE CREDITO), NO PUEDE ELIMINARSE");

            if (!$this->core_web_workflow->validateWorkflowStage($this->componentName, "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new \Exception(NOT_WORKFLOW_DELETE);

            if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $objTM->transactionOn))
                throw new \Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");

            //Eliminar el Registro
            $this->Transaction_Master_Model->delete_app_posme($companyID, $transactionID, $transactionMasterID);
            $this->Transaction_Master_Detail_Model->deleteWhereTM($companyID, $transactionID, $transactionMasterID);

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

    /**
     * Devuelve la factura (validada, con su moneda) y sus lineas con la cantidad disponible
     * para devolver y el precio neto de devolucion por unidad.
     * POST: invoiceNumber, excludeTransactionMasterID (opcional), transactionOn (opcional, Y-m-d).
     * Responde JSON {error, message, maxDays, invoice, detail}.
     */
    function getInvoiceDetailAvailable()
    {
        $maxDays = 0;
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);
            }

            $companyID                  = $dataSession["user"]->companyID;
            $maxDays                    = $this->getParameterMaxDays($companyID);
            $invoiceNumber              = trim((string) $this->getRequestValue("invoiceNumber"));
            $excludeTransactionMasterID = (int) $this->getRequestValue("excludeTransactionMasterID");
            $transactionOn              = trim((string) $this->getRequestValue("transactionOn"));
            $transactionOn              = $transactionOn == "" ? helper_getDate() : $this->normalizeDate($transactionOn);

            if ($invoiceNumber == "")
                throw new \Exception("DEBE INDICAR EL NUMERO DE FACTURA");

            $transactionIDReplacement = $this->getTransactionIDReplacement($companyID);
            $objInvoice               = $this->getInvoiceValidated($companyID, $invoiceNumber, 0, $transactionOn, $maxDays);
            $lines                    = $this->getInvoiceLines($companyID, $objInvoice, $transactionIDReplacement, $excludeTransactionMasterID);
            $objCustomer              = $this->getCustomerOrEmpty($companyID, $objInvoice->entityID);

            $detail = array();
            foreach ($lines as $line) {
                $detail[] = array(
                    "transactionMasterDetailID" => $line->transactionMasterDetailID,
                    "itemID"                    => $line->itemID,
                    "itemNumber"                => $line->itemNumber,
                    "itemName"                  => $line->itemName,
                    "unitMeasureName"           => $line->unitMeasureName,
                    "quantityInvoiced"          => $line->quantityInvoiced,
                    "quantityReplaced"          => $line->quantityReplaced,
                    "quantityReturned"          => $line->quantityReplaced,
                    "quantityAvailable"         => $line->quantityAvailable,
                    "unitaryCost"               => $line->unitaryCost,
                    "unitaryPrice"              => $line->unitaryPrice,
                    "amountAvailable"           => $line->amountAvailable
                );
            }

            return $this->response->setJSON(array(
                'error'   => false,
                'message' => SUCCESS,
                'maxDays' => $maxDays,
                'invoice' => $this->formatInvoiceForView($companyID, $objInvoice, $objCustomer, $lines),
                'detail'  => $detail
            )); //--finjson
        } catch (\Exception $ex) {
            return $this->response->setJSON(array(
                'error'   => true,
                'message' => $ex->getMessage(),
                'maxDays' => $maxDays,
                'invoice' => null,
                'detail'  => array()
            )); //--finjson
        }
    }

    /**
     * Imprime el documento con la plantilla de BD
     * (tb_company_page_setting_large: app_invoice_return / templateReplacement).
     */
    function viewRegister()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            $companyID           = $dataSession["user"]->companyID;
            $transactionID       = (int) /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri
            $transactionMasterID = (int) /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri

            $objTM = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            if (!$objTM)
                throw new \Exception("EL DOCUMENTO DE DEVOLUCION NO EXISTE O FUE ELIMINADO");
            $objTM->transactionOn = date_format(date_create($objTM->transactionOn), "Y-m-d");

            $objCompany       = $this->Company_Model->get_rowByPK($companyID);
            $objParameterLogo = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
            $objParameterTelefono = null;
            try {
                $objParameterTelefono = $this->core_web_parameter->getParameter("CORE_PHONE", $companyID);
            } catch (\Exception $exPhone) {
                $objParameterTelefono = null;
            }

            //Factura
            $objInvoice = $this->Transaction_Master_Model->get_rowByTransactionMasterID($companyID, (int) $objTM->reference1);
            if ($objInvoice) {
                $objInvoice->transactionOn = date_format(date_create($objInvoice->transactionOn), "Y-m-d");
            } else {
                $objInvoice                    = new \stdClass();
                $objInvoice->transactionNumber = $objTM->reference2;
                $objInvoice->transactionOn     = "";
            }

            $objCustomer = $this->getCustomerOrEmpty($companyID, $objTM->entityID);

            //Detalle
            $objDetail    = array();
            $amountDetail = 0; //Monto de la NC que se generara: suma de cantidad x precio neto de cada linea
            $rows         = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID, $transactionID, $transactionMasterID);
            foreach ($rows as $row) {
                $amountDetail = round($amountDetail + (float) $row->amount, 2);
                $objItem      = $this->Item_Model->get_rowByPKAndInactive($companyID, (int) $row->componentItemID);
                $objSustituto = empty($row->reference2) ? null : $this->Item_Model->get_rowByPKAndInactive($companyID, (int) $row->reference2);

                $obj                      = new \stdClass();
                $obj->itemNumber          = $objItem ? $objItem->itemNumber : "";
                $obj->itemName            = $objItem ? $objItem->name : $row->itemNameLog;
                $obj->quantity            = $row->quantity;
                $obj->itemNumberSustituto = $objSustituto ? $objSustituto->itemNumber : $row->reference3;
                $obj->itemNameSustituto   = $objSustituto ? $objSustituto->name : $row->itemNameDescriptionLog;
                $objDetail[]              = $obj;
            }

            //reference4: NC (devolucion) u Otra Salida (documentos viejos de sustitucion)
            $objOtherInput  = $this->getLinkedTransaction($companyID, $objTM->reference3);
            $objOtherOutput = $this->getLinkedTransaction($companyID, $objTM->reference4);
            $objNoteCredit  = null;
            if ($objOtherOutput && (int) $objOtherOutput->transactionID == (int) $this->core_web_note_credit->getTransactionIDNoteCredit($companyID)) {
                $objNoteCredit  = $objOtherOutput;
                $objOtherOutput = null;
            }

            //Plantilla de la compania, si no existe la de demo
            $htmlTemplate = getBahavioLargeDB($objCompany->type, "app_invoice_return", "templateReplacement", "");
            if (trim((string) $htmlTemplate) == "")
                $htmlTemplate = getBahavioLargeDB("demo", "app_invoice_return", "templateReplacement", "");
            if (trim((string) $htmlTemplate) == "")
                throw new \Exception("NO EXISTE LA PLANTILLA DE IMPRESION 'templateReplacement' DEL CONTROLADOR 'app_invoice_return' EN tb_company_page_setting_large (NI PARA EL TIPO DE COMPANIA NI PARA 'demo')");

            $data = array(
                'titulo'               => 'DEVOLUCION DE PRODUCTO',
                'objCompany'           => $objCompany,
                'objParameterLogo'     => $objParameterLogo,
                'objParameterTelefono' => $objParameterTelefono,
                'objTM'                => $objTM,
                'statusName'           => $objTM->workflowStageName,
                'objCustomer'          => $objCustomer,
                'objInvoice'           => $objInvoice,
                'objDetail'            => $objDetail,
                'otherInputNumber'     => $objOtherInput ? $objOtherInput->transactionNumber : "",
                'otherOutputNumber'    => $objOtherOutput ? $objOtherOutput->transactionNumber : "",
                'noteCreditNumber'     => $objNoteCredit ? $objNoteCredit->transactionNumber : "",
                //NC ya generada: su monto; si la devolucion aun no se aplica: el monto que se generara
                'noteCreditAmount'     => $objNoteCredit ? round((float) $objNoteCredit->amount, 2) : $amountDetail,
                'userNickName'         => $dataSession["user"]->nickname
            );

            $html = helper_RenderStringAsView($htmlTemplate, $data);
            $this->dompdf->loadHTML($html);
            $this->dompdf->render();

            $objParameterShowLinkDownload = $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID)->value;
            $this->dompdf->stream("devolucion_" . $objTM->transactionNumber . ".pdf", ['Attachment' => !$objParameterShowLinkDownload]);
            exit;
        } catch (\Exception $ex) {
            return $this->renderErrorView(isset($dataSession) ? $dataSession : null, $ex);
        }
    }

    /**************************************************************************/
    /* Metodos privados                                                       */
    /**************************************************************************/

    /**
     * Inserta un documento nuevo (cabecera + detalle) y, si el estado elegido es
     * aplicable, aplica la devolucion (NC + Otra Entrada).
     * @param array $dataSession
     */
    private function insertElement($dataSession)
    {
        $companyID = $dataSession["user"]->companyID;
        $urlBack   = base_url() . "/" . 'app_invoice_return/add';
        try {
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_INSERT);
            }
            $this->core_web_permission->getValueLicense($companyID, get_class($this) . "/" . "index");

            $branchID = $dataSession["user"]->branchID;
            $roleID   = $dataSession["role"]->roleID;

            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName($this->componentName);
            if (!$objComponent)
                throw new \Exception("EL COMPONENTE '" . $this->componentName . "' NO EXISTE...");

            $objComponentItem = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if (!$objComponentItem)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

            $transactionID = $this->getTransactionIDReplacement($companyID);
            $objT          = $this->Transaction_Model->getByCompanyAndTransaction($companyID, $transactionID);
            $transactionOn = $this->normalizeDate($this->request->getPost("txtTransactionOn"));
            $statusID      = (int) $this->request->getPost("txtStatusID");
            $note          = (string) $this->request->getPost("txtNote");

            if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $transactionOn))
                throw new \Exception("EL DOCUMENTO NO PUEDE INGRESARSE, EL CICLO CONTABLE ESTA CERRADO");

            //Factura, lineas y detalle validados
            $maxDays     = $this->getParameterMaxDays($companyID);
            $objInvoice  = $this->getInvoiceValidated($companyID, $this->request->getPost("txtInvoiceNumber"), (int) $this->request->getPost("txtInvoiceTransactionMasterID"), $transactionOn, $maxDays);
            $lines       = $this->getInvoiceLines($companyID, $objInvoice, $transactionID, 0);
            $warehouseID = $this->getInvoiceWarehouseID($objInvoice, $lines);
            $detail      = $this->validateDetail($companyID, $lines, $this->readDetailFromPost());

            //Si el estado elegido es aplicable se guarda con el estado inicial; applyReturn pone el aplicado al final
            $applyNow      = $this->core_web_workflow->validateWorkflowStage($this->componentName, "statusID", $statusID, COMMAND_APLICABLE, $companyID, $branchID, $roleID);
            $statusIDSaved = $statusID;
            if ($applyNow) {
                $objInitStage = $this->core_web_workflow->getWorkflowInitStage($this->componentName, "statusID", $companyID, $branchID, $roleID);
                if (empty($objInitStage))
                    throw new \Exception("NO EXISTE UN ESTADO INICIAL PARA '" . $this->componentName . "'");
                $statusIDSaved = $objInitStage[0]->workflowStageID;
            }

            $objTM                          = array();
            $objTM["companyID"]             = $companyID;
            $objTM["transactionID"]         = $transactionID;
            $objTM["branchID"]              = $branchID;
            $objTM["transactionNumber"]     = $this->core_web_counter->goNextNumber($companyID, $branchID, $this->componentName, 0);
            $objTM["transactionCausalID"]   = $this->core_web_transaction->getDefaultCausalID($companyID, $transactionID);
            $objTM["entityID"]              = $objInvoice->entityID;
            $objTM["transactionOn"]         = $transactionOn;
            $objTM["statusIDChangeOn"]      = helper_getDateTime();
            $objTM["componentID"]           = $objComponent->componentID;
            $objTM["note"]                  = mb_substr($note, 0, $this->noteMaxLength);
            $objTM["sign"]                  = 0;
            //Moneda de la factura: la NC se genera en esa moneda
            $objTM["currencyID"]            = $objInvoice->currencyID;
            $objTM["currencyID2"]           = $objTM["currencyID"];
            $objTM["exchangeRate"]          = $objInvoice->exchangeRate;
            $objTM["reference1"]            = $objInvoice->transactionMasterID;
            $objTM["reference2"]            = $objInvoice->transactionNumber;
            $objTM["reference3"]            = "";
            $objTM["reference4"]            = "";
            $objTM["statusID"]              = $statusIDSaved;
            $objTM["amount"]                = 0;
            $objTM["isApplied"]             = 0;
            $objTM["journalEntryID"]        = 0;
            $objTM["classID"]               = NULL;
            $objTM["areaID"]                = NULL;
            $objTM["sourceWarehouseID"]     = $warehouseID;
            $objTM["targetWarehouseID"]     = $warehouseID;
            $objTM["isActive"]              = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            //Cabecera + detalle en una transaccion de BD
            $db = db_connect();
            $db->transStart();
            try {
                $transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);

                foreach ($detail as $row) {
                    $this->Transaction_Master_Detail_Model->insert_app_posme(
                        $this->buildDetailRow($companyID, $transactionID, $transactionMasterID, $objComponentItem->componentID, $warehouseID, $row)
                    );
                }
                $db->transComplete();
            } catch (\Exception $exDb) {
                $db->transRollback();
                throw $exDb;
            }
            if ($db->transStatus() === false)
                throw new \Exception("NO FUE POSIBLE GUARDAR EL DOCUMENTO DE DEVOLUCION");

            //Carpeta de archivos del documento
            $path_ = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $transactionMasterID;
            if (!file_exists($path_))
                mkdir($path_, 0755, true);

            $urlEdit = $this->getUrlEdit($companyID, $transactionID, $transactionMasterID);

            //Aplicar en su propia transaccion (todo o nada); si falla el documento queda guardado sin aplicar
            if ($applyNow) {
                try {
                    $this->applyReturn($dataSession, $transactionID, $transactionMasterID, $statusID);
                } catch (\Exception $exApply) {
                    $this->core_web_notification->set_message(true, "EL DOCUMENTO SE GUARDO PERO NO SE PUDO APLICAR: " . $exApply->getMessage());
                    return $this->response->redirect($urlEdit);
                }
            }

            $this->core_web_notification->set_message(false, SUCCESS);
            return $this->response->redirect($urlEdit);
        } catch (\Exception $ex) {
            $this->core_web_notification->set_message(true, $ex->getMessage());
            return $this->response->redirect($urlBack);
        }
    }

    /**
     * Actualiza un documento no aplicado y, si el estado elegido es aplicable,
     * aplica la devolucion (NC + Otra Entrada) en una sola transaccion.
     * @param array $dataSession
     */
    private function updateElement($dataSession)
    {
        $companyID           = $dataSession["user"]->companyID;
        $transactionID       = (int) $this->request->getPost("txtTransactionID");
        $transactionMasterID = (int) $this->request->getPost("txtTransactionMasterID");
        $urlEdit             = $this->getUrlEdit($companyID, $transactionID, $transactionMasterID);
        try {
            //PERMISO SOBRE LA FUNCTION
            $resultPermission = 0;
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            $branchID = $dataSession["user"]->branchID;
            $roleID   = $dataSession["role"]->roleID;

            if (!$transactionID || !$transactionMasterID)
                throw new \Exception(NOT_PARAMETER);

            $objComponentItem = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if (!$objComponentItem)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

            $objTM = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            if (!$objTM)
                throw new \Exception("EL DOCUMENTO DE DEVOLUCION NO EXISTE O FUE ELIMINADO");

            if ($resultPermission == PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
                throw new \Exception(NOT_EDIT);

            if (((int) $objTM->isApplied) == 1)
                throw new \Exception("EL DOCUMENTO YA ESTA APLICADO, NO PUEDE EDITARSE");

            $statusID = (int) $this->request->getPost("txtStatusID");
            $applyNow = $this->core_web_workflow->validateWorkflowStage($this->componentName, "statusID", $statusID, COMMAND_APLICABLE, $companyID, $branchID, $roleID);

            //Documentos viejos de sustitucion con movimientos generados y sin aplicar: no se tocan
            if (trim((string) $objTM->reference3) != "" || trim((string) $objTM->reference4) != "")
                throw new \Exception("EL DOCUMENTO YA TIENE DOCUMENTOS GENERADOS (OTRA ENTRADA / NOTA DE CREDITO / OTRA SALIDA), NO PUEDE EDITARSE");

            if (!$this->core_web_workflow->validateWorkflowStage($this->componentName, "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $companyID, $branchID, $roleID))
                throw new \Exception(NOT_WORKFLOW_EDIT);

            $transactionOn = $this->normalizeDate($this->request->getPost("txtTransactionOn"));
            if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $objTM->transactionOn))
                throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARSE, EL CICLO CONTABLE ESTA CERRADO");
            if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $transactionOn))
                throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARSE, EL CICLO CONTABLE DE LA NUEVA FECHA ESTA CERRADO");

            //Factura, lineas y detalle validados (excluyendo este documento)
            $maxDays     = $this->getParameterMaxDays($companyID);
            $objInvoice  = $this->getInvoiceValidated($companyID, $this->request->getPost("txtInvoiceNumber"), (int) $this->request->getPost("txtInvoiceTransactionMasterID"), $transactionOn, $maxDays);
            $lines       = $this->getInvoiceLines($companyID, $objInvoice, $transactionID, $transactionMasterID);
            $warehouseID = $this->getInvoiceWarehouseID($objInvoice, $lines);
            $detail      = $this->validateDetail($companyID, $lines, $this->readDetailFromPost());

            $objTMNew                       = array();
            $objTMNew["transactionOn"]      = $transactionOn;
            $objTMNew["statusIDChangeOn"]   = helper_getDateTime();
            $objTMNew["note"]               = mb_substr((string) $this->request->getPost("txtNote"), 0, $this->noteMaxLength);
            $objTMNew["entityID"]           = $objInvoice->entityID;
            $objTMNew["reference1"]         = $objInvoice->transactionMasterID;
            $objTMNew["reference2"]         = $objInvoice->transactionNumber;
            $objTMNew["sourceWarehouseID"]  = $warehouseID;
            $objTMNew["targetWarehouseID"]  = $warehouseID;
            $objTMNew["currencyID"]         = $objInvoice->currencyID;
            $objTMNew["currencyID2"]        = $objInvoice->currencyID;
            $objTMNew["exchangeRate"]       = $objInvoice->exchangeRate;
            //Si el estado es aplicable, applyReturn lo asigna al terminar
            if (!$applyNow)
                $objTMNew["statusID"]       = $statusID;

            //Ids de detalle que se conservan
            $listTMD_ID = array();
            foreach ($detail as $row) {
                if (!empty($row["transactionMasterDetailID"]))
                    $listTMD_ID[] = (int) $row["transactionMasterDetailID"];
            }

            $db = db_connect();
            $db->transStart();
            try {
                $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);

                if (empty($listTMD_ID))
                    $this->Transaction_Master_Detail_Model->deleteWhereTM($companyID, $transactionID, $transactionMasterID);
                else
                    $this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID, $transactionID, $transactionMasterID, $listTMD_ID);

                foreach ($detail as $row) {
                    $objTMD = $this->buildDetailRow($companyID, $transactionID, $transactionMasterID, $objComponentItem->componentID, $warehouseID, $row);
                    if (empty($row["transactionMasterDetailID"])) {
                        $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
                    } else {
                        //La linea debe pertenecer a este documento (componentID 0 = consulta sin joins)
                        $objTMDOld = $this->Transaction_Master_Detail_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID, (int) $row["transactionMasterDetailID"], 0);
                        if (!$objTMDOld)
                            throw new \Exception("LA LINEA " . $row["transactionMasterDetailID"] . " NO PERTENECE A ESTE DOCUMENTO");

                        unset($objTMD["companyID"], $objTMD["transactionID"], $objTMD["transactionMasterID"]);
                        $this->Transaction_Master_Detail_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, (int) $row["transactionMasterDetailID"], $objTMD);
                    }
                }
                $db->transComplete();
            } catch (\Exception $exDb) {
                $db->transRollback();
                throw $exDb;
            }
            if ($db->transStatus() === false)
                throw new \Exception("NO FUE POSIBLE ACTUALIZAR EL DOCUMENTO DE DEVOLUCION");

            //Aplicar en su propia transaccion (todo o nada); si falla el documento queda guardado sin aplicar
            if ($applyNow) {
                try {
                    $this->applyReturn($dataSession, $transactionID, $transactionMasterID, $statusID);
                } catch (\Exception $exApply) {
                    $this->core_web_notification->set_message(true, "EL DOCUMENTO SE GUARDO PERO NO SE PUDO APLICAR: " . $exApply->getMessage());
                    return $this->response->redirect($urlEdit);
                }
            }

            $this->core_web_notification->set_message(false, SUCCESS);
            return $this->response->redirect($urlEdit);
        } catch (\Exception $ex) {
            $this->core_web_notification->set_message(true, $ex->getMessage());
            return $this->response->redirect($urlEdit);
        }
    }

    /**
     * Aplica la devolucion en UNA transaccion de BD (todo o nada):
     * revalida factura y cantidades, crea la Nota de Credito aplicada (suma al saldo del cliente),
     * crea la Otra Entrada con los productos devueltos y marca el documento aplicado
     * (reference3 = Otra Entrada, reference4 = NC, amount = monto de la NC).
     * Si algo falla se revierte todo y se relanza la excepcion.
     * @param array $dataSession
     * @param int   $transactionID
     * @param int   $transactionMasterID
     * @param int   $statusIDApplied Estado aplicable destino
     * @return void
     */
    private function applyReturn($dataSession, $transactionID, $transactionMasterID, $statusIDApplied)
    {
        $companyID = $dataSession["user"]->companyID;
        $branchID  = $dataSession["user"]->branchID;
        $roleID    = $dataSession["role"]->roleID;

        if (!$this->core_web_workflow->validateWorkflowStage($this->componentName, "statusID", $statusIDApplied, COMMAND_APLICABLE, $companyID, $branchID, $roleID))
            throw new \Exception("EL ESTADO SELECCIONADO NO ES APLICABLE");

        //Tipo de costo del inventario: con ULTIMO COSTO la entrada usa el costo actual del producto
        $typeCost = "";
        try {
            $typeCost = strtoupper(trim($this->core_web_parameter->getParameter("INVENTORY_TYPE_COST", $companyID)->value));
        } catch (\Exception $exCost) {
            $typeCost = "";
        }

        $db = db_connect();
        $db->transException(true)->transStart();
        try {
            //Bloquear el documento: evita aplicar dos veces en paralelo
            $objTMLock = $this->Transaction_Master_Model->get_rowByPKForUpdate($companyID, $transactionID, $transactionMasterID);
            if (!$objTMLock)
                throw new \Exception("EL DOCUMENTO DE DEVOLUCION NO EXISTE O FUE ELIMINADO");

            if (((int) $objTMLock->isApplied) == 1)
                throw new \Exception("EL DOCUMENTO DE DEVOLUCION YA ESTA APLICADO");

            if (trim((string) $objTMLock->reference3) != "" || trim((string) $objTMLock->reference4) != "")
                throw new \Exception("EL DOCUMENTO YA TIENE DOCUMENTOS GENERADOS (OTRA ENTRADA / NOTA DE CREDITO / OTRA SALIDA)");

            $objTM         = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
            $transactionOn = date_format(date_create($objTM->transactionOn), "Y-m-d");
            if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $transactionOn))
                throw new \Exception("EL DOCUMENTO NO PUEDE APLICARSE, EL CICLO CONTABLE ESTA CERRADO");

            //Revalidar factura y dias; bloquear la factura para que dos devoluciones no superen lo facturado
            $maxDays    = $this->getParameterMaxDays($companyID);
            $objInvoice = $this->getInvoiceValidated($companyID, null, (int) $objTM->reference1, $transactionOn, $maxDays);
            $this->Transaction_Master_Model->get_rowByPKForUpdate($companyID, $objInvoice->transactionID, $objInvoice->transactionMasterID);

            //Cantidades ya devueltas en otras devoluciones (excluye este documento) y detalle guardado
            $lines       = $this->getInvoiceLines($companyID, $objInvoice, $transactionID, $transactionMasterID);
            $warehouseID = $this->getInvoiceWarehouseID($objInvoice, $lines);

            $rows   = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID, $transactionID, $transactionMasterID);
            $posted = array();
            foreach ($rows as $row) {
                $posted[] = array(
                    "transactionMasterDetailID" => $row->transactionMasterDetailID,
                    "invoiceDetailID"           => $row->reference1,
                    "itemID"                    => $row->componentItemID,
                    "quantity"                  => $row->quantity
                );
            }
            $detail = $this->validateDetail($companyID, $lines, $posted);

            //Fila de bodega obligatoria para el producto devuelto; monto de la NC = cantidad x precio neto
            $amountNoteCredit = 0;
            foreach ($detail as $row) {
                $objIWReturned = $this->Itemwarehouse_Model->getByPK($companyID, (int) $row["itemID"], (int) $warehouseID);
                if (!$objIWReturned)
                    throw new \Exception("EL PRODUCTO DEVUELTO '" . $row["objItem"]->itemNumber . " " . $row["objItem"]->name . "' NO ESTA ASIGNADO A LA BODEGA DE LA FACTURA");

                //Redondeo por renglon, igual que el estimado de pantalla (retUpdateTotal)
                $amountNoteCredit = $amountNoteCredit + round($row["quantity"] * $row["line"]->unitaryPrice, 2);
            }
            $amountNoteCredit = round($amountNoteCredit, 2);
            if ($amountNoteCredit <= 0)
                throw new \Exception("EL MONTO DE LA DEVOLUCION ES 0; NO SE PUEDE GENERAR LA NOTA DE CREDITO");

            //1) Nota de Credito aplicada (moneda y tasa de la factura)
            $noteNC       = $this->buildNote($objTM, $objInvoice, $detail, "");
            $resultNC     = $this->core_web_note_credit->crearNcAplicada(
                $dataSession,
                $objInvoice->entityID,
                $objInvoice->currencyID,
                $objInvoice->exchangeRate,
                $amountNoteCredit,
                $transactionOn,
                $noteNC,
                $objInvoice->transactionMasterID,
                $objInvoice->transactionNumber,
                $transactionMasterID,
                $objTM->transactionNumber
            );

            //2) Otra Entrada con los productos devueltos
            $listInput = array();
            foreach ($detail as $row) {
                $listInput[] = array(
                    "itemID"      => $row["itemID"],
                    "quantity"    => $row["quantity"],
                    "unitaryCost" => $typeCost == "ULTIMO COSTO" ? $row["objItem"]->cost : $row["line"]->unitaryCost
                );
            }
            $noteInput   = $this->buildNote($objTM, $objInvoice, $detail, $resultNC["transactionNumber"]);
            $resultInput = $this->core_web_inventory->createOtherInputApplied($dataSession, $warehouseID, $transactionOn, $noteInput, $objTM->transactionNumber, $objInvoice->transactionNumber, $listInput);

            //3) Documento aplicado
            $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, array(
                "reference3"       => $resultInput["transactionMasterID"],
                "reference4"       => $resultNC["transactionMasterID"],
                "amount"           => $amountNoteCredit,
                "statusID"         => $statusIDApplied,
                "statusIDChangeOn" => helper_getDateTime(),
                "isApplied"        => 1
            ));

            $db->transCommit();
        } catch (\Throwable $exApply) {
            $db->transRollback();
            //DatabaseException de CI4 no extiende \Exception: se normaliza para los llamadores
            if ($exApply instanceof \Exception)
                throw $exApply;
            throw new \Exception($exApply->getMessage());
        }
    }

    /**
     * Busca y valida la factura: activa, de facturacion, en estado aplicable, no anulada
     * y dentro del rango de dias permitido respecto a $transactionOn.
     * @param int         $companyID
     * @param string|null $invoiceNumber              Numero de factura (prioritario)
     * @param int         $invoiceTransactionMasterID Id de la factura (si no hay numero, o para confirmar)
     * @param string      $transactionOn              Fecha de la devolucion (Y-m-d)
     * @param int         $maxDays
     * @return object Fila de Transaction_Master_Model::get_rowByPK de la factura
     */
    private function getInvoiceValidated($companyID, $invoiceNumber, $invoiceTransactionMasterID, $transactionOn, $maxDays)
    {
        $billingTransactionID = $this->getTransactionIDBilling($companyID);
        $invoiceNumber        = trim((string) $invoiceNumber);
        $objInvoice           = null;

        if ($invoiceNumber != "") {
            $objFound = $this->Transaction_Master_Model->get_rowByTransactionNumberAndTransactionID($companyID, $billingTransactionID, $invoiceNumber);
            if (!$objFound)
                throw new \Exception("LA FACTURA #" . $invoiceNumber . " NO EXISTE O ESTA ELIMINADA");
            if (!empty($invoiceTransactionMasterID) && (int) $objFound->transactionMasterID != (int) $invoiceTransactionMasterID)
                throw new \Exception("EL NUMERO DE FACTURA NO CORRESPONDE A LA FACTURA SELECCIONADA");
            $objInvoice = $this->Transaction_Master_Model->get_rowByPK($companyID, $billingTransactionID, (int) $objFound->transactionMasterID);
        } else if (!empty($invoiceTransactionMasterID)) {
            $objInvoice = $this->Transaction_Master_Model->get_rowByPK($companyID, $billingTransactionID, (int) $invoiceTransactionMasterID);
        } else {
            throw new \Exception("DEBE INDICAR LA FACTURA");
        }

        if (!$objInvoice)
            throw new \Exception("LA FACTURA NO EXISTE O ESTA ELIMINADA");

        //Excluir anuladas
        $statusAnulada = "";
        try {
            $statusAnulada = trim((string) $this->core_web_parameter->getParameter("INVOICE_BILLING_ANULADAS", $companyID)->value);
        } catch (\Exception $exAnulada) {
            $statusAnulada = "";
        }
        if ($statusAnulada != "" && (string) $objInvoice->statusID == $statusAnulada)
            throw new \Exception("LA FACTURA #" . $objInvoice->transactionNumber . " ESTA ANULADA");

        if (!$this->core_web_workflow->validateWorkflowStage($this->componentNameBilling, "statusID", $objInvoice->statusID, COMMAND_APLICABLE, $companyID, APP_BRANCH, APP_ROL_SUPERADMIN))
            throw new \Exception("LA FACTURA #" . $objInvoice->transactionNumber . " NO ESTA APLICADA (ESTADO: " . $objInvoice->workflowStageName . ")");

        //Dias transcurridos entre la factura y la devolucion
        $days = $this->getDaysBetween($objInvoice->transactionOn, $transactionOn);
        if ($days < 0)
            throw new \Exception("LA FECHA DE LA DEVOLUCION ES ANTERIOR A LA FECHA DE LA FACTURA #" . $objInvoice->transactionNumber);
        if ($days > $maxDays)
            throw new \Exception("LA FACTURA #" . $objInvoice->transactionNumber . " TIENE " . $days . " DIAS; EL MAXIMO PERMITIDO PARA DEVOLVER ES " . $maxDays . " DIAS");

        return $objInvoice;
    }

    /**
     * Lineas de producto de la factura con cantidad facturada, ya devuelta, disponible
     * y precio neto de devolucion por unidad (unitaryPrice, 2 decimales).
     * Precio neto: tmd.amount es bruto (cantidad x (precio + impuestos)) y el descuento va aparte.
     * - Si la suma de tmd.discount coincide con tm.discount: (tmd.amount - tmd.discount) / cantidad.
     * - Si no (descuento solo global en cabecera): tmd.amount / cantidad x (1 - tm.discount / SUM(tmd.amount)).
     * Usa get_rowByTransactionToShare (sin joins) para no perder lineas sin categoria/unidad.
     * @param int    $companyID
     * @param object $objInvoice
     * @param int    $transactionIDReplacement
     * @param int    $excludeTransactionMasterID Documento de devolucion a excluir (0 = ninguno)
     * @return array Objetos indexados por transactionMasterDetailID
     */
    private function getInvoiceLines($companyID, $objInvoice, $transactionIDReplacement, $excludeTransactionMasterID)
    {
        $objComponentItem = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
        if (!$objComponentItem)
            throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

        //Cantidades ya sustituidas en otros documentos
        $replaced     = array();
        $listReplaced = $this->Transaction_Master_Detail_Model->get_sumQuantityReplacedByInvoice($companyID, $transactionIDReplacement, $objInvoice->transactionMasterID, (int) $excludeTransactionMasterID);
        if (!empty($listReplaced)) {
            foreach ($listReplaced as $r) {
                $key            = (int) $r->invoiceTransactionMasterDetailID;
                $replaced[$key] = (isset($replaced[$key]) ? $replaced[$key] : 0) + (float) $r->quantityReplaced;
            }
        }

        $result      = array();
        $sumAmount   = 0;
        $sumDiscount = 0;
        $rows        = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID, $objInvoice->transactionID, $objInvoice->transactionMasterID);
        foreach ($rows as $row) {
            //Totales de todas las lineas (el descuento de cabecera se reparte sobre todas)
            $sumAmount   = $sumAmount + (float) $row->amount;
            $sumDiscount = $sumDiscount + (float) $row->discount;

            if ((int) $row->componentID != (int) $objComponentItem->componentID)
                continue;

            $objItem         = $this->Item_Model->get_rowByPKAndInactive($companyID, (int) $row->componentItemID);
            $unitMeasureName = "";
            if ($objItem && $objItem->unitMeasureID) {
                $objUnit         = $this->Catalog_Item_Model->get_rowByCatalogItemID((int) $objItem->unitMeasureID);
                $unitMeasureName = $objUnit ? $objUnit->name : "";
            }

            $key                             = (int) $row->transactionMasterDetailID;
            $quantityReplaced                = isset($replaced[$key]) ? $replaced[$key] : 0;
            $line                            = new \stdClass();
            $line->transactionMasterDetailID = $key;
            $line->itemID                    = (int) $row->componentItemID;
            $line->itemNumber                = $objItem ? $objItem->itemNumber : "";
            $line->itemName                  = $objItem ? $objItem->name : $row->itemNameLog;
            $line->unitMeasureName           = $unitMeasureName;
            $line->quantityInvoiced          = (float) $row->quantity;
            $line->quantityReplaced          = $quantityReplaced;
            $line->quantityAvailable         = max(0, round((float) $row->quantity - $quantityReplaced, 6));
            $line->unitaryCost               = (float) $row->unitaryCost;
            $line->inventoryWarehouseSourceID = $row->inventoryWarehouseSourceID;
            $line->lineAmount                = (float) $row->amount;
            $line->lineDiscount              = (float) $row->discount;
            $result[$key]                    = $line;
        }

        //Precio neto de devolucion por unidad
        $invoiceDiscount = (float) $objInvoice->discount;
        $useLineDiscount = abs($sumDiscount - $invoiceDiscount) < 0.01;
        foreach ($result as $line) {
            $net = 0;
            if ($line->quantityInvoiced > 0) {
                if ($useLineDiscount)
                    $net = ($line->lineAmount - $line->lineDiscount) / $line->quantityInvoiced;
                else if ($sumAmount > 0)
                    $net = ($line->lineAmount / $line->quantityInvoiced) * (1 - $invoiceDiscount / $sumAmount);
            }
            $line->unitaryPrice    = round(max(0, $net), 2);
            $line->amountAvailable = round($line->quantityAvailable * $line->unitaryPrice, 2);
        }
        return $result;
    }

    /**
     * Valida el detalle contra las lineas de la factura y devuelve el detalle enriquecido.
     * @param int   $companyID
     * @param array $lines   Resultado de getInvoiceLines
     * @param array $detail  Filas ['transactionMasterDetailID','invoiceDetailID','itemID','quantity']
     * @return array Filas con ademas 'line', 'objItem'
     */
    private function validateDetail($companyID, $lines, $detail)
    {
        if (empty($detail))
            throw new \Exception("DEBE AGREGAR AL MENOS UN PRODUCTO A DEVOLVER");

        $result        = array();
        $sumByLine     = array();
        foreach ($detail as $index => $row) {
            $lineNumber      = $index + 1;
            $invoiceDetailID = (int) $row["invoiceDetailID"];
            $itemID          = (int) $row["itemID"];
            $quantity        = $row["quantity"];

            if (!isset($lines[$invoiceDetailID]))
                throw new \Exception("LA LINEA " . $lineNumber . " NO PERTENECE A LA FACTURA");

            $line = $lines[$invoiceDetailID];
            if ($line->itemID != $itemID)
                throw new \Exception("LA LINEA " . $lineNumber . ": EL PRODUCTO NO COINCIDE CON EL DE LA FACTURA");

            if (!is_numeric($quantity) || (float) $quantity <= 0)
                throw new \Exception("LA LINEA " . $lineNumber . ": LA CANTIDAD DEBE SER MAYOR QUE 0");
            $quantity = (float) $quantity;

            $objItem =$this->Item_Model->get_rowByPKAndInactive($companyID, $itemID);
            if (!$objItem)
                throw new \Exception("LA LINEA " . $lineNumber . ": EL PRODUCTO DEVUELTO NO EXISTE");

            //Suma por linea de factura (puede repetirse en varias filas)
            $sumByLine[$invoiceDetailID] = (isset($sumByLine[$invoiceDetailID]) ? $sumByLine[$invoiceDetailID] : 0) + $quantity;
            if (round($sumByLine[$invoiceDetailID], 6) > round($line->quantityAvailable, 6))
                throw new \Exception("EL PRODUCTO '" . $line->itemNumber . " " . $line->itemName . "' SUPERA LA CANTIDAD DISPONIBLE PARA DEVOLVER (FACTURADO " . $line->quantityInvoiced . ", YA DEVUELTO " . $line->quantityReplaced . ", DISPONIBLE " . $line->quantityAvailable . ")");

            $row["quantity"] = $quantity;
            $row["line"]     = $line;
            $row["objItem"]  = $objItem;
            $result[]        = $row;
        }
        return $result;
    }

    /**
     * Lee los arreglos de detalle del POST.
     * @return array Filas ['transactionMasterDetailID','invoiceDetailID','itemID','quantity']
     */
    private function readDetailFromPost()
    {
        $listTMD_ID       = $this->request->getPost("txtDetailTransactionDetailID");
        $listInvoiceTMDID = $this->request->getPost("txtDetailInvoiceDetailID");
        $listItemID       = $this->request->getPost("txtDetailItemID");
        $listQuantity     = $this->request->getPost("txtDetailQuantity");

        $result = array();
        if (empty($listItemID) || !is_array($listItemID))
            return $result;

        foreach ($listItemID as $key => $itemID) {
            $result[] = array(
                "transactionMasterDetailID" => isset($listTMD_ID[$key]) ? (int) $listTMD_ID[$key] : 0,
                "invoiceDetailID"           => isset($listInvoiceTMDID[$key]) ? (int) $listInvoiceTMDID[$key] : 0,
                "itemID"                    => (int) $itemID,
                "quantity"               => helper_StringToNumber(isset($listQuantity[$key]) ? $listQuantity[$key] : "")
            );
        }
        return $result;
    }

    /**
     * Arma la fila de tb_transaction_master_detail del documento de devolucion
     * (unitaryPrice = precio neto de devolucion, amount = cantidad x precio; referencial,
     * el monto definitivo de la NC se recalcula al aplicar).
     * @return array
     */
    private function buildDetailRow($companyID, $transactionID, $transactionMasterID, $componentItemID, $warehouseID, $row)
    {
        $objTMD                                 = array();
        $objTMD["companyID"]                    = $companyID;
        $objTMD["transactionID"]                = $transactionID;
        $objTMD["transactionMasterID"]          = $transactionMasterID;
        $objTMD["componentID"]                  = $componentItemID;
        $objTMD["componentItemID"]              = $row["itemID"];
        $objTMD["quantity"]                     = $row["quantity"];
        $objTMD["unitaryCost"]                  = $row["line"]->unitaryCost;
        $objTMD["cost"]                         = $row["quantity"] * $row["line"]->unitaryCost;
        $objTMD["unitaryAmount"]                = $row["line"]->unitaryPrice;
        $objTMD["amount"]                       = round($row["quantity"] * $row["line"]->unitaryPrice, 2);
        $objTMD["discount"]                     = 0;
        $objTMD["unitaryPrice"]                 = $row["line"]->unitaryPrice;
        $objTMD["promotionID"]                  = 0;
        $objTMD["reference1"]                   = $row["invoiceDetailID"];
        $objTMD["reference2"]                   = "";
        $objTMD["reference3"]                   = "";
        $objTMD["itemNameLog"]                  = $row["objItem"]->name;
        $objTMD["itemNameDescriptionLog"]       = "";
        $objTMD["catalogStatusID"]              = 0;
        $objTMD["inventoryStatusID"]            = 0;
        $objTMD["isActive"]                     = 1;
        $objTMD["quantityStock"]                = 0;
        $objTMD["quantiryStockInTraffic"]       = 0;
        $objTMD["quantityStockUnaswared"]       = 0;
        $objTMD["remaingStock"]                 = 0;
        $objTMD["expirationDate"]               = NULL;
        $objTMD["inventoryWarehouseSourceID"]   = $warehouseID;
        $objTMD["inventoryWarehouseTargetID"]   = $warehouseID;
        return $objTMD;
    }

    /**
     * Detalle del documento para la vista de edicion, con nombres y cantidades.
     * @return array de objetos
     */
    private function getDetailForView($companyID, $transactionID, $transactionMasterID, $lines)
    {
        $result = array();
        $rows   = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID, $transactionID, $transactionMasterID);
        foreach ($rows as $row) {
            $objItem      = $this->Item_Model->get_rowByPKAndInactive($companyID, (int) $row->componentItemID);
            //Sustituto solo en documentos viejos de sustitucion (en devoluciones reference2 va vacio)
            $objSustituto = empty($row->reference2) ? null : $this->Item_Model->get_rowByPKAndInactive($companyID, (int) $row->reference2);
            $line         = isset($lines[(int) $row->reference1]) ? $lines[(int) $row->reference1] : null;

            $obj                             = new \stdClass();
            $obj->transactionMasterDetailID  = $row->transactionMasterDetailID;
            $obj->invoiceTransactionMasterDetailID = $row->reference1;
            $obj->itemID                     = $row->componentItemID;
            $obj->itemNumber                 = $objItem ? $objItem->itemNumber : "";
            $obj->itemName                   = $objItem ? $objItem->name : $row->itemNameLog;
            $obj->unitMeasureName            = $line ? $line->unitMeasureName : "";
            $obj->itemSustitutoID            = $row->reference2;
            $obj->itemNumberSustituto        = $objSustituto ? $objSustituto->itemNumber : $row->reference3;
            $obj->itemNameSustituto          = $objSustituto ? $objSustituto->name : $row->itemNameDescriptionLog;
            $obj->quantity                   = $row->quantity;
            $obj->unitaryCost                = $row->unitaryCost;
            $obj->quantityInvoiced           = $line ? $line->quantityInvoiced : 0;
            $obj->quantityReplaced           = $line ? $line->quantityReplaced : 0;
            $obj->quantityReturned           = $obj->quantityReplaced;
            $obj->quantityAvailable          = $line ? $line->quantityAvailable : 0;
            //Precio neto: el de la linea de factura; si no existe, el guardado en el detalle
            $obj->unitaryPrice               = $line ? $line->unitaryPrice : round((float) $row->unitaryPrice, 2);
            $obj->amount                     = round((float) $row->quantity * $obj->unitaryPrice, 2);
            $result[]                        = $obj;
        }
        return $result;
    }

    /**
     * Datos comunes de add/edit.
     * @return array
     */
    private function getCommonViewData($companyID)
    {
        $objComponentItem = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
        if (!$objComponentItem)
            throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

        //Igual que app_purchase_garantia: componente de facturas para el popup de busqueda
        $objComponentBilling = $this->core_web_tools->getComponentIDBy_ComponentName($this->componentNameBilling);
        if (!$objComponentBilling)
            throw new \Exception("EL COMPONENTE '" . $this->componentNameBilling . "' NO EXISTE...");

        $objParameterCantidadItemPoup = "";
        try {
            $objParameterCantidadItemPoup = $this->core_web_parameter->getParameter("INVOICE_CANTIDAD_ITEM", $companyID)->value;
        } catch (\Exception $ex) {
            $objParameterCantidadItemPoup = "";
        }

        $dataView                                   = array();
        $dataView["objComponentItem"]               = $objComponentItem;
        $dataView["objComponentBilling"]            = $objComponentBilling;
        $dataView["objParameterCantidadItemPoup"]   = $objParameterCantidadItemPoup;
        $dataView["maxDays"]                        = $this->getParameterMaxDays($companyID);
        $dataView["validateStock"]                  = $this->getParameterValidateStock($companyID);
        return $dataView;
    }

    /**
     * Datos de la factura para la vista / JSON.
     * @return array
     */
    private function formatInvoiceForView($companyID, $objInvoice, $objCustomer, $lines = null)
    {
        if ($lines === null)
            $lines = array();

        $warehouseID   = 0;
        $warehouseName = "";
        try {
            $warehouseID = $this->getInvoiceWarehouseID($objInvoice, $lines);
        } catch (\Exception $ex) {
            $warehouseID = 0;
        }
        if ($warehouseID) {
            $objWarehouse  = $this->Warehouse_Model->get_rowByPK($companyID, (int) $warehouseID);
            $warehouseName = $objWarehouse ? $objWarehouse->name : "";
        }

        //Moneda de la factura (la NC se genera en esa moneda)
        $objCurrency = empty($objInvoice->currencyID) ? null : $this->Currency_Model->get_rowByPK((int) $objInvoice->currencyID);

        return array(
            "currencyID"          => $objInvoice->currencyID,
            "currencyName"        => $objCurrency ? $objCurrency->name : "",
            "currencySymbol"      => $objCurrency ? $objCurrency->simbol : "",
            "transactionMasterID" => $objInvoice->transactionMasterID,
            "transactionNumber"   => $objInvoice->transactionNumber,
            "transactionOn"       => date_format(date_create($objInvoice->transactionOn), "Y-m-d"),
            "entityID"            => $objInvoice->entityID,
            "customerNumber"      => $objCustomer ? $objCustomer->customerNumber : "",
            "customerName"        => $objCustomer ? $objCustomer->firstName : "",
            "warehouseID"         => $warehouseID,
            "warehouseName"       => $warehouseName
        );
    }

    /**
     * Factura por id (sin validar estado), para mostrar en edit.
     * @return object|null
     */
    private function getInvoiceByTransactionMasterID($companyID, $invoiceTransactionMasterID)
    {
        if (empty($invoiceTransactionMasterID))
            return null;
        $billingTransactionID = $this->getTransactionIDBilling($companyID);
        return $this->Transaction_Master_Model->get_rowByPK($companyID, $billingTransactionID, (int) $invoiceTransactionMasterID);
    }

    /**
     * Bodega de la factura: cabecera (sourceWarehouseID) o, si falta, la de sus lineas.
     * @return int
     */
    private function getInvoiceWarehouseID($objInvoice, $lines)
    {
        if (!empty($objInvoice->sourceWarehouseID))
            return (int) $objInvoice->sourceWarehouseID;

        foreach ($lines as $line) {
            if (!empty($line->inventoryWarehouseSourceID))
                return (int) $line->inventoryWarehouseSourceID;
        }
        throw new \Exception("NO SE PUDO DETERMINAR LA BODEGA DE LA FACTURA #" . $objInvoice->transactionNumber);
    }

    /**
     * Cliente por entidad; si no existe devuelve un objeto vacio.
     * @return object
     */
    private function getCustomerOrEmpty($companyID, $entityID)
    {
        $objCustomer = empty($entityID) ? null : $this->Customer_Model->get_rowByEntity($companyID, (int) $entityID);
        if ($objCustomer)
            return $objCustomer;

        $objCustomer                 = new \stdClass();
        $objCustomer->entityID       = $entityID;
        $objCustomer->customerNumber = "";
        $objCustomer->firstName      = "";
        return $objCustomer;
    }

    /**
     * Transaccion generada (Otra Entrada / Otra Salida) por id.
     * @return object|null {companyID, transactionID, transactionMasterID, transactionNumber}
     */
    private function getLinkedTransaction($companyID, $transactionMasterID)
    {
        if (trim((string) $transactionMasterID) == "" || (int) $transactionMasterID == 0)
            return null;

        $objTM = $this->Transaction_Master_Model->get_rowByTransactionMasterID($companyID, (int) $transactionMasterID);
        if (!$objTM)
            return null;

        $obj                      = new \stdClass();
        $obj->companyID           = $objTM->companyID;
        $obj->transactionID       = $objTM->transactionID;
        $obj->transactionMasterID = $objTM->transactionMasterID;
        $obj->transactionNumber   = $objTM->transactionNumber;
        $obj->amount              = round((float) $objTM->amount, 2);
        return $obj;
    }

    /**
     * Comentario de la Nota de Credito / Otra Entrada:
     * "Devolución #<numero> de factura #<factura> [NC #<nc>]: <producto> x <cant>; ... <comentario>"
     * @param object $objTM                Documento de devolucion
     * @param object $objInvoice           Factura
     * @param array  $detail               Detalle validado
     * @param string $noteCreditNumber     Numero de la NC ("" si aun no existe)
     * @return string Recortado a la longitud de tb_transaction_master.note
     */
    private function buildNote($objTM, $objInvoice, $detail, $noteCreditNumber)
    {
        $parts = array();
        foreach ($detail as $row) {
            $parts[] = $row["objItem"]->itemNumber . " " . $row["objItem"]->name . " x " . ($row["quantity"] + 0);
        }
        $note = "Devolución #" . $objTM->transactionNumber . " de factura #" . $objInvoice->transactionNumber;
        if (trim((string) $noteCreditNumber) != "")
            $note = $note . " NC #" . $noteCreditNumber;
        $note = $note . ": " . implode("; ", $parts);
        if (trim((string) $objTM->note) != "")
            $note = $note . ". " . trim($objTM->note);

        return mb_substr($note, 0, $this->noteMaxLength);
    }

    /**
     * Dias entre dos fechas (fin - inicio), negativo si fin es anterior.
     * @return int
     */
    private function getDaysBetween($dateStart, $dateEnd)
    {
        $start = date_create(date_format(date_create($dateStart), "Y-m-d"));
        $end   = date_create(date_format(date_create($dateEnd), "Y-m-d"));
        $diff  = date_diff($start, $end);
        return $diff->invert ? -$diff->days : $diff->days;
    }

    /**
     * Normaliza una fecha a Y-m-d; lanza excepcion si no es valida.
     * @return string
     */
    private function normalizeDate($value)
    {
        $value = trim((string) $value);
        $date  = $value == "" ? false : date_create($value);
        if ($date === false)
            throw new \Exception("LA FECHA '" . $value . "' NO ES VALIDA");
        return date_format($date, "Y-m-d");
    }

    /**
     * Parametro INVOICE_REPLACEMENT_MAX_DAYS (defecto 5 si no existe).
     * @return int
     */
    private function getParameterMaxDays($companyID)
    {
        try {
            $value = trim((string) $this->core_web_parameter->getParameter("INVOICE_REPLACEMENT_MAX_DAYS", $companyID)->value);
            return is_numeric($value) && (int) $value >= 0 ? (int) $value : 5;
        } catch (\Exception $ex) {
            return 5;
        }
    }

    /**
     * Parametro INVOICE_REPLACEMENT_VALIDATE_STOCK ("true" | "false", defecto "true").
     * @return string
     */
    private function getParameterValidateStock($companyID)
    {
        try {
            $value = strtolower(trim((string) $this->core_web_parameter->getParameter("INVOICE_REPLACEMENT_VALIDATE_STOCK", $companyID)->value));
            return $value == "false" ? "false" : "true";
        } catch (\Exception $ex) {
            return "true";
        }
    }

    /**
     * transactionID de la devolucion.
     * @return int
     */
    private function getTransactionIDReplacement($companyID)
    {
        $transactionID = $this->core_web_transaction->getTransactionID($companyID, $this->componentName, 0);
        if (!$transactionID)
            throw new \Exception("LA TRANSACCION '" . $this->componentName . "' NO EXISTE...");
        return $transactionID;
    }

    /**
     * transactionID de facturacion.
     * @return int
     */
    private function getTransactionIDBilling($companyID)
    {
        $transactionID = $this->core_web_transaction->getTransactionID($companyID, $this->componentNameBilling, 0);
        if (!$transactionID)
            throw new \Exception("LA TRANSACCION '" . $this->componentNameBilling . "' NO EXISTE...");
        return $transactionID;
    }

    /**
     * Valor del request: POST de formulario o cuerpo JSON.
     * @return mixed
     */
    private function getRequestValue($name)
    {
        $value = $this->request->getPost($name);
        if ($value !== null)
            return $value;

        try {
            $json = $this->request->getJSON(true);
        } catch (\Exception $ex) {
            $json = null;
        }
        return is_array($json) && isset($json[$name]) ? $json[$name] : null;
    }

    /**
     * URL de edicion del documento.
     * @return string
     */
    private function getUrlEdit($companyID, $transactionID, $transactionMasterID)
    {
        return base_url() . "/" . 'app_invoice_return/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID;
    }

    /**
     * Pantalla de error general del sistema.
     * @return mixed
     */
    private function renderErrorView($dataSession, $ex)
    {
        if (empty($dataSession))
            return redirect()->to(base_url("core_acount/login"));

        $data["session"]   = $dataSession;
        $data["exception"] = $ex;
        $data["urlLogin"]  = base_url();
        $data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
        $data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
        return view("core_template/email_error_general", $data);
    }
}
