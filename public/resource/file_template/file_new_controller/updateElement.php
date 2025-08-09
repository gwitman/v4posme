// @signature updateElement($dataSession)
try {
    //PERMISO SOBRE LA FUNCTION
    if (APP_NEED_AUTHENTICATION == true) {
        $permited = false;
        $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

        if (!$permited) throw new \Exception(NOT_ACCESS_CONTROL);

        $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
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

    $branchID            = $dataSession["user"]->branchID;
    $roleID              = $dataSession["role"]->roleID;
    $companyID           = $dataSession["user"]->companyID;
    $userID              = $dataSession["user"]->userID;
    $transactionID       = /*inicio get post*/$this->request->getPost("txtTransactionID");
    $transactionMasterID = /*inicio get post*/$this->request->getPost("txtTransactionMasterID");
    $objTM               = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
    $oldStatusID         = $objTM->statusID;

    //Validar Edicion por el Usuario
    if ($resultPermission == PERMISSION_ME && ($objTM->createdBy != $userID)) {
        throw new \Exception(NOT_EDIT);
    }

    //Validar si el estado permite editar
    if (! $this->core_web_workflow->validateWorkflowStage("nombre_componente", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $companyID, $branchID, $roleID)) {
        throw new \Exception(NOT_WORKFLOW_EDIT);
    }

    if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $objTM->transactionOn)) {
        throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
    }
    //Actualizar Maestro
    //$objTMNew["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
    $objTMNew["transactionOn"]    = /*inicio get post*/$this->request->getPost("txtDate");
    $objTMNew["statusIDChangeOn"] = date("Y-m-d H:m:s");
    $objTMNew["branchID"]         = $this->request->getPost("txtBranchID");
    $objTMNew["note"]             = /*inicio get post*/$this->request->getPost("txtNote");       //--fin peticion get o post
    $objTMNew["currencyID"]       = /*inicio get post*/$this->request->getPost("txtCurrencyID"); //--fin peticion get o post
    $objTMNew["exchangeRate"]     = $this->core_web_currency->getRatio($dataSession["user"]->companyID, date("Y-m-d"), 1, $objTM->currencyID2, $objTMNew["currencyID"]);
    $objTMNew["classID"]          = /*inicio get post*/$this->request->getPost("txtBoxID");
    $objTMNew["areaID"]           = /*inicio get post*/$this->request->getPost("txtAreaID");
    $objTMNew["priorityID"]       = /*inicio get post*/$this->request->getPost("txtPriorityID");
    $objTMNew["reference1"]       = /*inicio get post*/$this->request->getPost("txtDetailReference1");
    $objTMNew["reference2"]       = /*inicio get post*/$this->request->getPost("txtDetailReference2");
    $objTMNew["reference3"]       = /*inicio get post*/$this->request->getPost("txtDetailReference3");
    //$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtEmployeeID");//--fin peticion get o post
    //$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post
    //$objTMNew["descriptionReference"] 		= "reference1:input,reference2:input,reference3:Gestor de Cobro,reference4:Linea de credito del Cliente";
    $objTMNew["statusID"] = /*inicio get post*/$this->request->getPost("txtStatusID");
    $objTMNew["amount"]   = 0;

    $db = db_connect();
    $db->transStart();

    //El Estado solo permite editar el workflow
    if ($this->core_web_workflow->validateWorkflowStage("nombre_componente", "statusID", $objTM->statusID, COMMAND_EDITABLE, $companyID, $branchID, $roleID)) {
    $objTMNew             = [];
    $objTMNew["statusID"] = /*inicio get post*/$this->request->getPost("txtStatusID");
        $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
    } else {
        $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
    }

    //insertar contenido
    if ($db->transStatus() !== false) {
        $db->transCommit();
        $this->core_web_notification->set_message(false, SUCCESS);
        $this->response->redirect(base_url() . "/" . 'nombre_controlador/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
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