
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

        $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
        if ($resultPermission == PERMISSION_NONE) {
            throw new \Exception(NOT_ACCESS_FUNCTION);
        }

    }

    //Load Modelos
    //
    ////////////////////////////////////////
    ////////////////////////////////////////
    ////////////////////////////////////////

    //Nuevo Registro
    $transactionNumber = /*inicio get post*/$this->request->getPost("transactionNumber");

    if (! $transactionNumber) {
        throw new \Exception(NOT_PARAMETER);
    }
    $objTM = $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID, $transactionNumber);

    if (! $objTM) {
        throw new \Exception("NO SE ENCONTRO EL DOCUMENTO");
    }

    return $this->response->setJSON([
        'error'               => false,
        'message'             => SUCCESS,
        'companyID'           => $objTM->companyID,
        'transactionID'       => $objTM->transactionID,
        'transactionMasterID' => $objTM->transactionMasterID,
    ]); //--finjson

} catch (\Exception $ex) {

    return $this->response->setJSON([
        'error'   => true,
        'message' => $ex->getLine() . " " . $ex->getMessage(),
    ]); //--finjson
}