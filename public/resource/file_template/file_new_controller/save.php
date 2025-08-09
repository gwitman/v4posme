// @signature save($method = "")
$method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
try{
    //AUTENTICACION
    if(!$this->core_web_authentication->isAuthenticated())
        throw new \Exception(USER_NOT_AUTENTICATED);
    $dataSession = $this->session->get();

    //Validar Formulario
    $this->validation->setRule("txtStatusID", "Estado", "required");
    $this->validation->setRule("txtDate", "Fecha", "required");

    //Validar Formulario
    if (! $this->validation->withRequest($this->request)->run()) {
        $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
        $this->core_web_notification->set_message(true, $stringValidation);
        $this->response->redirect(base_url() . "/" . 'nombre_controlador/add');
        exit;
    }

    //Validar Formulario


    //Nuevo Registro
    if( $method == "new"){
        return $this->insertElement($dataSession);
    }
    //Editar Registro
    else if( $method == "edit") {
        return $this->updateElement($dataSession);
    }else{
        //no existe el metodo
        return redirect(base_url() . "/" . 'nombre_controlador/add');
    }

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