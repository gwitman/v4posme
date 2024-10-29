<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxp_report extends _BaseController
{

    function index($dataViewID = null)
    {
        try {

            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISOS SOBRE LAS FUNCIONES
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);

                $parentMenuElementID = $this->core_web_permission->getElementID(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            }

            //Obtener la Lista de Reportes
            $dataMenu["menuRenderBodyReport"]
                = $this->core_web_menu->render_menu_body_report($dataSession["company"],$dataSession["menuBodyReport"], $parentMenuElementID);

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] = $this->core_web_notification->get_message();
            $dataSession["head"] = /*--inicio view*/ view('app_cxp_report/view_head');//--finview
            $dataSession["body"] = /*--inicio view*/view('app_cxp_report/view_body', $dataMenu);//--finview
            $dataSession["script"] = /*--inicio view*/ view('app_cxp_report/view_script');//--finview
            $dataSession["footer"] = "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;        }
    }

    function expenses()
    {
        try {

            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISOS SOBRE LAS FUNCIONES
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);
            }


            $viewReport = false;
            $startOn = false;
            $endOn = false;
            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $userID = $dataSession["user"]->userID;
            $tocken = '';

            $viewReport = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "viewReport");//--finuri
            $startOn = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "startOn");//--finuri
            $endOn = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "endOn");//--finuri
            $txtTiposID = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "txtTiposID");//--finuri
            $txtCategoriaID = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "txtCategoriaID");//--finuri
            $txtClassID = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "txtClassID");//--finuri


            if (!($viewReport && $startOn && $endOn)) {

                //Renderizar Resultado
                $objPublicCatalogTipoGastos = $this->Public_Catalog_Model->asObject()->where("systemName", "tb_transaction_master_accounting_expenses.tipos_gastos")->where("isActive", 1)->find();
                $objPublicCatalogCategoriaGastos = $this->Public_Catalog_Model->asObject()->where("systemName", "tb_transaction_master_accounting_expenses.categoria_gastos")->where("isActive", 1)->find();
                $dataSession["objListCatalogoTipoGastos"] = $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID", $objPublicCatalogTipoGastos[0]->publicCatalogID)->where("isActive", 1)->findAll();
                $dataSession["objListCatalogoCategoriaGastos"] = $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID", $objPublicCatalogCategoriaGastos[0]->publicCatalogID)->where("isActive", 1)->findAll();
                $dataSession["objListCatalogItemClasificacion"] = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_accounting_expenses", "classID", $companyID);


                $dataSession["message"] = $this->core_web_notification->get_message();
                $dataSession["head"] = /*--inicio view*/view('app_cxp_report/expenses/view_head');//--finview
                $dataSession["body"] = /*--inicio view*/view('app_cxp_report/expenses/view_body', $dataSession);//--finview
                $dataSession["script"] = /*--inicio view*/ view('app_cxp_report/expenses/view_script');//--finview
                $dataSession["footer"] = "";
                return view("core_masterpage/default_report", $dataSession);//--finview-r
            } else {

                //Obtener el tipo de Comprobante
                $companyID = $dataSession["user"]->companyID;
                //Get Component
                $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
                //Get Logo
                $objParameter = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
                //Get Company
                $objCompany = $this->Company_Model->get_rowByPK($companyID);
                //Get Datos
                $query = "CALL pr_cxp_get_report_expenses_detail(?,?,?,?,?,?,?,?);";
                $objData = $this->Bd_Model->executeRender(
                    $query, [$companyID, $tocken, $userID, $startOn, $endOn, $txtTiposID, $txtCategoriaID,$txtClassID]
                );


                if (isset($objData)) {
                    $objDataResult["objDetail"] = $objData;
                } else {
                    $objDataResult["objDetail"] = $objData;
                }

                $objDataResult["objCompany"] = $objCompany;
                $objDataResult["objStartOn"] = $startOn;
                $objDataResult["objEndOn"] = $endOn;
                $objDataResult["objLogo"] = $objParameter;
                $objDataResult["objFirma"] = "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxp_get_report_expenses_detail" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . session_id() . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */
                    "activity" /*fin last_activity*/ . "}";
                $objDataResult["objFirmaEncription"] = md5($objDataResult["objFirma"]);

                return view("app_cxp_report/expenses/view_a_disemp", $objDataResult);//--finview-r

            }
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;        }
    }

    function expenses_summary()
    {
        try {

            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISOS SOBRE LAS FUNCIONES
            if (APP_NEED_AUTHENTICATION == true) {

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);
            }


            $viewReport = false;
            $startOn = false;
            $endOn = false;
            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $userID = $dataSession["user"]->userID;
            $tocken = '';

            $viewReport = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "viewReport");//--finuri
            $startOn = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "startOn");//--finuri
            $endOn = /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "endOn");//--finuri


            if (!($viewReport && $startOn && $endOn)) {

                //Renderizar Resultado
                $objPublicCatalogTipoGastos = $this->Public_Catalog_Model->asObject()->where("systemName", "tb_transaction_master_accounting_expenses.tipos_gastos")->where("isActive", 1)->find();
                $objPublicCatalogCategoriaGastos = $this->Public_Catalog_Model->asObject()->where("systemName", "tb_transaction_master_accounting_expenses.categoria_gastos")->where("isActive", 1)->find();
                $dataSession["objListCatalogoTipoGastos"] = $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID", $objPublicCatalogTipoGastos[0]->publicCatalogID)->where("isActive", 1)->findAll();
                $dataSession["objListCatalogoCategoriaGastos"] = $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID", $objPublicCatalogCategoriaGastos[0]->publicCatalogID)->where("isActive", 1)->findAll();


                $dataSession["message"] = $this->core_web_notification->get_message();
                $dataSession["head"] = /*--inicio view*/view('app_cxp_report/expenses_summary/view_head');//--finview
                $dataSession["body"] = /*--inicio view*/view('app_cxp_report/expenses_summary/view_body', $dataSession);//--finview
                $dataSession["script"] = /*--inicio view*/view('app_cxp_report/expenses_summary/view_script');//--finview
                $dataSession["footer"] = "";
                return view("core_masterpage/default_report", $dataSession);//--finview-r
            } else {

                //Obtener el tipo de Comprobante
                $companyID = $dataSession["user"]->companyID;
                //Get Component
                $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
                //Get Logo
                $objParameter = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
                //Get Company
                $objCompany = $this->Company_Model->get_rowByPK($companyID);
                //Get Datos
                $query = "CALL pr_cxp_get_report_expenses_summary(?,?,?,?,?,?,?,?);";
                $objData = $this->Bd_Model->executeRender(
                    $query,
                    [$companyID, $tocken, $userID, $startOn, $endOn, 0, 0,0]
                );


                if (isset($objData)) {
                    $objDataResult["objDetail"] = $objData;
                } else {
                    $objDataResult["objDetail"] = $objData;
                }

                $objDataResult["objCompany"] = $objCompany;
                $objDataResult["objStartOn"] = $startOn;
                $objDataResult["objEndOn"] = $endOn;
                $objDataResult["objLogo"] = $objParameter;
                $objDataResult["objFirma"] = "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_cxp_get_report_expenses_detail" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . session_id() . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */
                    "activity" /*fin last_activity*/ . "}";
                $objDataResult["objFirmaEncription"] = md5($objDataResult["objFirma"]);

                return view("app_cxp_report/expenses_summary/view_a_disemp", $objDataResult);//--finview-r

            }
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;        }
    }


}

?>