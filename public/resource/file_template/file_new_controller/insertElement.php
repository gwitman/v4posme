// @signature insertElement($dataSession)
try {
    //PERMISO SOBRE LA FUNCTION
    if (APP_NEED_AUTHENTICATION == true) {
        $permited = false;
        $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

        if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

        $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
        if ($resultPermission == PERMISSION_NONE) throw new \Exception(NOT_ALL_INSERT);
    }

    $branchID 								= $dataSession["user"]->branchID;
    $roleID 								= $dataSession["role"]->roleID;
    $companyID 								= $dataSession["user"]->companyID;

    $this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");

    //Obtener el Componente de Transacciones Facturacion
    $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("nombre_componente");
    if (! $objComponent) {
        throw new \Exception("EL COMPONENTE 'nombre_componente' NO EXISTE...");
    }

    //Obtener transaccion
    $transactionID = $this->core_web_transaction->getTransactionID($companyID, "nombre_componente", 0);
    $objT          = $this->Transaction_Model->getByCompanyAndTransaction($companyID, $transactionID);

    $objTM["companyID"]           = $companyID;
    $objTM["transactionID"]       = $transactionID;
    $objTM["branchID"]            = $branchID;
    $objTM["transactionNumber"]   = $this->core_web_counter->goNextNumber($companyID, $branchID, "nombre_componente", 0);
    $objTM["transactionCausalID"] = $this->core_web_transaction->getDefaultCausalID($companyID, $transactionID);
    //$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
    $objTM["transactionOn"]     = /*inicio get post*/$this->request->getPost("txtDate");
    $objTM["statusIDChangeOn"]  = date("Y-m-d H:m:s");
    $objTM["componentID"]       = $objComponent->componentID;
    $objTM["note"]              = /*inicio get post*/$this->request->getPost("txtNote"); //--fin peticion get o post
    $objTM["sign"]              = $objT->signInventory;
    $objTM["currencyID"]        = /*inicio get post*/$this->request->getPost("txtCurrencyID"); //--fin peticion get o post
    $objTM["currencyID2"]       = $this->core_web_currency->getCurrencyExternal($companyID)->currencyID;
    $objTM["exchangeRate"]      = $this->core_web_currency->getRatio($companyID, date("Y-m-d"), 1, $objTM["currencyID2"], $objTM["currencyID"]);
    $objTM["reference1"]        = /*inicio get post*/$this->request->getPost("txtDetailReference1");
    $objTM["reference2"]        = /*inicio get post*/$this->request->getPost("txtDetailReference2");
    $objTM["reference3"]        = /*inicio get post*/$this->request->getPost("txtDetailReference3");
    $objTM["reference4"]        = '';
    $objTM["statusID"]          = /*inicio get post*/$this->request->getPost("txtStatusID");
    $objTM["amount"]            = helper_StringToNumber( /*inicio get post*/$this->request->getPost('txtTotal'));
    $objTM["isApplied"]         = 0;
    $objTM["journalEntryID"]    = 0;
    $objTM["classID"]           = /*inicio get post*/$this->request->getPost("txtBoxID");
    $objTM["areaID"]            = /*inicio get post*/$this->request->getPost("txtAreaID");
    $objTM["priorityID"]        = /*inicio get post*/$this->request->getPost("txtPriorityID");
    $objTM["sourceWarehouseID"] = null;
    $objTM["targetWarehouseID"] = null;
    $objTM["isActive"]          = 1;
    $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);
    //insertar contenido
    $db = db_connect();
    $db->transException(true)->transStart();

    $transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);

    //Crear la Carpeta para almacenar los Archivos del Documento
    $pathDocument = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $transactionMasterID;
    if (! file_exists($pathDocument)) {
        mkdir($pathDocument, 0700, true);
    }

    if ($db->transStatus() !== false) {
        $db->transCommit();
        $this->core_web_notification->set_message(false, SUCCESS);
        $this->response->redirect(base_url() . "/" . 'nombre_controlador/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
    } else {
        $db->transRollback();
    $this->core_web_notification->set_message(true, $this->db->_error_message());
        $this->response->redirect(base_url() . "/" . 'nombre_controlador/add');
    }
} catch (\Exception $ex) {
    if (empty($dataSession)) {
        return redirect()->to(base_url("core_acount/login"));
    }

    $data["session"]   = $dataSession;
    $data["exception"] = $ex;
    $data["urlLogin"]  = base_url();
    $data["urlIndex"]  = base_url() . "/" . str_replace("app\\\\controllers\\\\", "", strtolower(get_class($this))) . "/" . "index";
    $data["urlBack"]   = base_url() . "/" . str_replace("app\\\\controllers\\\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
    $resultView        = view("core_template/email_error_general", $data);

    return $resultView;
}