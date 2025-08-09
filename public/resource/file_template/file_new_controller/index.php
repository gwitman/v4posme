// @signature index($dataViewID = null)
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

        $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
        if ($resultPermission 	== PERMISSION_NONE)
            throw new \Exception(NOT_ACCESS_FUNCTION);
    }

    //Obtener el componente
    $objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("nombre_componente");
    if(!$objComponent)
        throw new \Exception("00409 EL COMPONENTE 'nombre_componente' NO EXISTE...");


    //Vista por defecto
    if(isset($dataViewID)){
        $targetComponentID			= 0;
        $parameter["{companyID}"]	= $this->session->get('user')->companyID;
        $dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);
        $dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
    }
    //Otra vista
    else{
        $parameter["{companyID}"]	= $this->session->get('user')->companyID;
        $dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter);
        $dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
    }

    //Renderizar Resultado
    $dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
    $dataSession["message"]			= $this->core_web_notification->get_message();
    $dataSession["head"]			= /*--inicio view*/ view('nombre_controlador/list_head');//--finview
    $dataSession["footer"]			= /*--inicio view*/ view('nombre_controlador/list_footer');//--finview
    $dataSession["body"]			= $dataViewRender;
    $dataSession["script"]			= /*--inicio view*/ view('nombre_controlador/list_script');//--finview
    $dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);
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