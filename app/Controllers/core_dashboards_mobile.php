<?php
//posme:2023-02-27
namespace App\Controllers;
use Config\Services;

class core_dashboards_mobile extends _BaseController {

	function index()
	{
		try {

			// AUTENTICACIÓN VIA PARÁMETROS (sin sesión requerida)
			$userName = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "userName"); //--finuri
			$password = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "password"); //--finuri

			if (!$userName || !$password)
				throw new \Exception("Parámetros userName y password son requeridos.");

            
			// Login como lo hace app_mobile_api/setDataUpload
            
			$objUser        = $this->core_web_authentication->get_UserBy_PasswordAndNickname($userName, $password);      
            $data 		    = $this->core_web_authentication->createLogin($objUser);
			$dataSession	= $this->session->get();

			$companyID  = $objUser["user"]->companyID;
			$branchID   = $objUser["user"]->branchID;
			$userID     = $objUser["user"]->userID;			
			$objCompany = $objUser["company"];
            

			// Tipo de plantilla
			$masterPage = 'snagit_masterpage';
			$viewType   = 'snagit_';

			// Renderizar vistas
			$dataView["company"]    = $objCompany;
			$dataView["title"]      = "Dashboard Móvil";
			$dataView["userName"]   = $userName;
			$dataView["password"]   = $password;
			$dataView["companyID"]  = $companyID;
			$dataView["branchID"]   = $branchID;
			$dataView["userID"]     = $userID;

			$dataSession["title"]   = "Dashboard Móvil";
			$dataSession["head"]    = /*--inicio view*/ view('core_dashboards_mobile/' . $viewType . 'index_head', $dataView);//--finview
			$dataSession["footer"]  = /*--inicio view*/ view('core_dashboards_mobile/' . $viewType . 'index_footer', $dataView);//--finview
			$dataSession["script"]  = /*--inicio view*/ view('core_dashboards_mobile/' . $viewType . 'index_script', $dataView);//--finview
			$dataSession["body"]    = /*--inicio view*/ view('core_dashboards_mobile/' . $viewType . 'index_body', $dataView)
									. /*--inicio view*/ view('core_dashboards_mobile/' . $viewType . 'index_pwa_install', $dataView);//--finview

			return view("core_masterpage/" . $masterPage, $dataSession);//--finview-r

		} catch (\Exception $ex) {
			$data["exception"] = $ex;
			$data["urlLogin"]   = base_url();
			$resultView         = view("core_template/email_error_general", $data);
			return $resultView;
		}
	}

	/**
	 * Manifest dinámico para PWA (usa variables PHP para rutas)
	 */
	function manifest()
	{
		$this->response->setContentType('application/json');
		return view('core_masterpage/snagit_manifest_json');
	}

	/**
	 * API para obtener datos del reporte de caja
	 */
	function getReportData()
	{
		try {

			$userName       = /*inicio get post*/ $this->request->getPost("userName");
			$password       = /*inicio get post*/ $this->request->getPost("password");
			$startOn        = /*inicio get post*/ $this->request->getPost("startOn");
			$endOn          = /*inicio get post*/ $this->request->getPost("endOn");
			$customerName   = /*inicio get post*/ $this->request->getPost("customerName");
            $itemName       = /*inicio get post*/ $this->request->getPost("itemName");
            $transactionID  = /*inicio get post*/ $this->request->getPost("transactionID");

			if (!$userName || !$password)
				throw new \Exception("Parámetros userName y password son requeridos.");

			// Login
			$objUser	    = $this->session->get();
			$companyID      = $objUser["user"]->companyID;
			$userID         = $objUser["user"]->userID;
			$objCompany     = $objUser["company"];

			// Valores por defecto
			$startOn        = $startOn ? $startOn . " 00:00:00" : date("Y-m-d") . " 00:00:00";
			$endOn          = $endOn ? $endOn . " 23:59:59" : date("Y-m-d") . " 23:59:59";
			$customerName   = $customerName ? $customerName : '';
            $itemName       = $itemName ? $itemName : '';
            $transactionID  = $transactionID ? $transactionID : '19';
			$tocken         = '';
			$authorization  = "1";

			// Llamar procedimiento almacenado		
            if($transactionID == 19 /* FACTURAS */)
            {
                $objData    = $this->Transaction_Master_Model->getRowAll_DashboardMobile_Facturas(
                        $transactionID,
                        $startOn,
                        $endOn,
                        $customerName,
                        $itemName
                );
            }
			else if($transactionID == "productSalesAmount" /* PRODUCTOS VENDIDOS MONTOS */)
            {
                $objData    = $this->Transaction_Master_Model->getRowAll_DashboardMobile_FacturasProductosAmount(
                        $transactionID,
                        $startOn,
                        $endOn,
                        $customerName,
                        $itemName
                );
            }
			else if($transactionID == "productSalesQuantity" /* PRODUCTOS VENDIDOS CANTIDAD */)
            {
                $objData    = $this->Transaction_Master_Model->getRowAll_DashboardMobile_FacturasProductosQuantity(
                        $transactionID,
                        $startOn,
                        $endOn,
                        $customerName,
                        $itemName
                );
            }
			else if($transactionID == "productInventoryQuantity" /* PRODUCTOS EN CANTIDAD  0 */)
            {
                $objData    = $this->Item_Model->getRowAll_DashboardMobile_InventoryQuantity(
                        $transactionID,
                        $startOn,
                        $endOn,
                        $customerName,
                        $itemName
                );
            }
            else if($transactionID == 23 /* ABONOS */)
            {
                $objData    = $this->Transaction_Master_Model->getRowAll_DashboardMobile_Abonos(
                        $transactionID,
                        $startOn,
                        $endOn,
                        $customerName
                );
            }
            else
            {
                $objData = [];
            }


			return $this->response->setJSON(array(
				'success'   => true,
				'data'      => $objData,
				'company'   => $objCompany->name,
				'startOn'   => $startOn,
				'endOn'     => $endOn
			));//--finjson

		} catch (\Exception $ex) {
			return $this->response->setJSON(array(
				'success' => false,
				'message' => 'Linea: ' . $ex->getLine() . " - Error:" . $ex->getMessage()
			));//--finjson
		}
	}
}
