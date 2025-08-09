
//Factura en Impresora Termica
//O impresora de ticket, con ancho de 3.2 pulgadas
//O equivalente a 8 centimetro
//Formato de papel rollo.

try {

    //AUTENTICADO
    if (! $this->core_web_authentication->isAuthenticated()) {
        throw new \Exception(USER_NOT_AUTENTICATED);
    }

    $dataSession = $this->session->get();

    //PERMISO SOBRE LA FUNCION
    if (APP_NEED_AUTHENTICATION == true) {
        $permited = false;
        $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

        if (! $permited) {
            throw new \Exception(NOT_ACCESS_CONTROL);
        }

        $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
        if ($resultPermission == PERMISSION_NONE) {
            throw new \Exception(NOT_ALL_EDIT);
        }

    }

    $transactionID       = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "transactionID");       //--finuri
    $transactionMasterID = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri
    $companyID           = $dataSession["user"]->companyID;
    $branchID            = $dataSession["user"]->branchID;
    $roleID              = $dataSession["role"]->roleID;

    //Cargar Libreria

    //Get Component
    $objComponent         = $this->core_web_tools->getComponentIDBy_ComponentName("nombre_componente");
    $objParameter         = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
    $objParameterPhone    = $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE", $companyID);
    $objParameterTelefono = $this->core_web_parameter->getParameter("CORE_PHONE", $companyID);
    $objCompany           = $this->Company_Model->get_rowByPK($companyID);
    $spacing              = 0.5;

    //Get Documento
    $datView["objTM"]                = $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
    $datView["objTMI"]               = $this->Transaction_Master_Info_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
    $datView["objTMD"]               = $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID, $transactionID, $transactionMasterID);
    $datView["objTM"]->transactionOn = date_format(date_create($datView["objTM"]->transactionOn), "Y-m-d");
    $datView["objUser"]              = $this->User_Model->get_rowByPK($datView["objTM"]->companyID, $datView["objTM"]->createdAt, $datView["objTM"]->createdBy);
    $datView["Identifier"]           = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER", $companyID);
    $datView["objBranch"]            = $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID, $datView["objTM"]->branchID);
    $datView["objStage"]             = $this->core_web_workflow->getWorkflowStage("tb_transaction_master_inputcash", "statusID", $datView["objTM"]->statusID, $companyID, $branchID, $roleID);
    $datView["objTipo"]              = $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID, $datView["objTM"]->transactionID, $datView["objTM"]->transactionCausalID);
    $datView["objCustumer"]          = $this->Customer_Model->get_rowByEntity($companyID, is_null($datView["objTM"]->entityID) == true ? 0 : $datView["objTM"]->entityID);
    $datView["objNatural"]           = $this->Natural_Model->get_rowByPK($companyID, $datView["objTM"]->createdAt, is_null($datView["objTM"]->entityID) == true ? 0 : $datView["objTM"]->entityID);
    $datView["objCurrency"]          = $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
    $datView["tipoCambio"]           = round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE", $companyID)->value, 2);
    $prefixCurrency                  = $datView["objCurrency"]->simbol . " ";

    //Set Nombre del Reporte
    $reportName    = "DOC_INVOICE";
    $facturaTipo   = $datView["objTipo"]->name;
    $facturaEstado = $datView["objStage"][0]->display;

    //Configurar Detalle
    $confiDetalle = [];

    $row = [
        "style"               => "text-align:left;width:50%",
        "colspan"             => '1',
        "prefix"              => '',

        "style_row_data"      => "text-align:left;width:50%",
        "colspan_row_data"    => '1',
        "prefix_row_data"     => '',
        "nueva_fila_row_data" => 0,
    ];
    array_push($confiDetalle, $row);

    $row = [
        "style"               => "text-align:left;width:10%",
        "colspan"             => '1',
        "prefix"              => '',

        "style_row_data"      => "text-align:left;width:10%",
        "colspan_row_data"    => '1',
        "prefix_row_data"     => '',
        "nueva_fila_row_data" => 0,
    ];
    array_push($confiDetalle, $row);

    $row = [
        "style"               => "text-align:right",
        "colspan"             => '1',
        "prefix"              => $datView["objCurrency"]->simbol,

        "style_row_data"      => "text-align:right",
        "colspan_row_data"    => '1',
        "prefix_row_data"     => $datView["objCurrency"]->simbol,
        "nueva_fila_row_data" => 0,
    ];
    array_push($confiDetalle, $row);

    //Inicializar Detalle
    /*Calculo de saldos generales*/
    $detalle = [];
    $row     = ["MONTO", '', $datView["objCurrency"]->simbol . sprintf('%.2f', $datView["objTM"]->amount)];
    array_push($detalle, $row);

    //Generar Reporte
    $html = helper_reporte80mmTransactionMasterInputOutPutCash(
        "",
        $objCompany,
        $objParameter,
        $datView["objTM"],
        $datView["objNatural"],
        $datView["objCustumer"],
        $datView["tipoCambio"],
        $datView["objCurrency"],
        $datView["objTMI"],
        $confiDetalle,
        $detalle,
        $objParameterTelefono,
        $datView["objStage"][0]->display,
        "",
        ""
    );
    $this->dompdf->loadHTML($html);

    //1cm = 29.34666puntos
    //a4: 210 ancho x 297
    //a4: 21cm x 29.7cm
    //a4: 595.28puntos x 841.59puntos

    //$this->dompdf->setPaper('A4','portrait');
    //$this->dompdf->setPaper(array(0,0,234.76,6000));

    $this->dompdf->render();

    $objParameterShowLinkDownload = $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID);
    $objParameterShowLinkDownload = $objParameterShowLinkDownload->value;

    //visualizar
    $this->dompdf->stream("file.pdf", ['Attachment' => ! $objParameterShowLinkDownload]);

    //descargar
    //$this->dompdf->stream();

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