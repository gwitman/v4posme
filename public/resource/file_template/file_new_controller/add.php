
try{
    //AUTENTICACION
    if(!$this->core_web_authentication->isAuthenticated())
        throw new \Exception(USER_NOT_AUTENTICATED);
    $dataSession		= $this->session->get();

    //PERMISO SOBRE LA FUNCION
    if(APP_NEED_AUTHENTICATION == true){
        $permited = false;
        $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

        if(!$permited)
            throw new \Exception(NOT_ACCESS_CONTROL);

        $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
        if ($resultPermission 	== PERMISSION_NONE)
            throw new \Exception(NOT_ALL_INSERT);

    }

    $companyID = $dataSession["user"]->companyID;
    $branchID  = $dataSession["user"]->branchID;
    $roleID    = $dataSession["role"]->roleID;
    $userID    = $dataSession["user"]->userID;

    $transactionID   = $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "nombre_componente", 0);
    $objCurrency     = $this->core_web_currency->getCurrencyDefault($companyID);
    $targetCurrency  = $this->core_web_currency->getCurrencyExternal($companyID);
    $objListCurrency = $this->Company_Currency_Model->getByCompany($companyID);

    //Obtener el Componente
    $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("nombre_componente");
    if (!$objComponent)
    throw new \Exception("EL COMPONENTE 'nombre_componente' NO EXISTE...");

    //Insertar Contenido
    $dataView                       =  [];

    //Renderizar Resultado
    $dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
    $dataSession["message"]			= $this->core_web_notification->get_message();
    $dataSession["head"]			= /*--inicio view*/ view('nombre_controlador/news_head', $dataView);//--finview
    $dataSession["body"]			= /*--inicio view*/ view('nombre_controlador/news_body', $dataView);//--finview
    $dataSession["script"]			= /*--inicio view*/ view('nombre_controlador/news_script', $dataView);//--finview
    $dataSession["footer"]			= "";
    return view("core_masterpage/default_masterpage",$dataSession);//--finview-r

}
catch(\Exception $ex){
    if (empty($dataSession)) {
        return redirect()->to(base_url("core_acount/login"));
    }

    $data["session"]   = $dataSession;
    $data["exception"] = $ex;
    $data["urlLogin"]  = base_url();
    $data["urlIndex"]  = base_url()."/". str_replace("app\\\\controllers\\\\","",strtolower( get_class($this)))."/"."index";
    $data["urlBack"]   = base_url()."/". str_replace("app\\\\controllers\\\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
    $resultView        = view("core_template/email_error_general",$data);

    return $resultView;
}