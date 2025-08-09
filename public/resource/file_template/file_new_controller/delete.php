
try{
    //AUTENTICADO
    if(!$this->core_web_authentication->isAuthenticated())
        throw new \Exception(USER_NOT_AUTENTICATED);
    $dataSession		= $this->session->get();

    //PERMISO SOBRE LA FUNCTION
    if(APP_NEED_AUTHENTICATION == true){
        $permited = false;
        $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

        if(!$permited)
            throw new \Exception(NOT_ACCESS_CONTROL);

        $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"delete",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
        if ($resultPermission 	== PERMISSION_NONE)
            throw new \Exception(NOT_ALL_DELETE);

    }

    //Load Modelos
    //
    ////////////////////////////////////////
    ////////////////////////////////////////
    ////////////////////////////////////////

    $companyID           = /*inicio get post*/$this->request->getPost("companyID");
    $transactionID       = /*inicio get post*/$this->request->getPost("transactionID");
    $transactionMasterID = /*inicio get post*/$this->request->getPost("transactionMasterID");

    if ((! $companyID && ! $transactionID && ! $transactionMasterID)) {
        throw new \Exception(NOT_PARAMETER);
    }

    $objTM = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
    if ($resultPermission == PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID)) {
        throw new \Exception(NOT_DELETE);
    }

    if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $objTM->transactionOn)) {
        throw new \Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");
    }

    //Si el documento esta aplicado crear el contra documento
    if ($this->core_web_workflow->validateWorkflowStage("nombre_componente", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
        throw new \Exception(NOT_WORKFLOW_DELETE);
    }

    //Eliminar el Registro
    $this->Transaction_Master_Model->delete_app_posme($companyID, $transactionID, $transactionMasterID);
    $this->Transaction_Master_Detail_Model->deleteWhereTM($companyID, $transactionID, $transactionMasterID);
    //Resultado
    return $this->response->setJSON(array(
        'error'   => false,
        'message' => SUCCESS
    ));//--finjson

}
catch(\Exception $ex){

    return $this->response->setJSON(array(
        'error'   => true,
        'message' => $ex->getLine()." ".$ex->getMessage()
    ));//--finjson
    $this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
}
