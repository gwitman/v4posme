<?php
//posme:2023-02-27
namespace App\Controllers;
class app_purchase_printertaller extends _BaseController {

	function index($dataViewID = null){
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

						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);

			}

			$companyID 			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$roleID 			= $dataSession["role"]->roleID;

			//Componente de taller
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_workshop_taller");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_workshop_taller' NO EXISTE...");

			//TransactionID de las ordenes de taller
			$transactionID		= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_workshop_taller",0);

			//Componentes usados por los examinadores (colaborador / proyecto)
			//Mismos examinadores que el formulario app_afx_fixedassent/add
			$objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

			$objComponentCatalog	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponentCatalog)
			throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");

			//Parametros con las urls de impresion de las ordenes de taller
			$objParameterUrlPrinterInput	= $this->core_web_parameter->getParameter("WORKSHOW_URL_PRINTER_TALLER",$companyID);
			$objParameterUrlPrinterOutput	= $this->core_web_parameter->getParameter("WORKSHOW_URL_PRINTER_TALLER_OUTPUT",$companyID);

			//Datos para la vista
			$dataView["company"]					= $dataSession["company"];
			$dataView["companyID"]					= $companyID;
			$dataView["branchID"]					= $branchID;
			$dataView["roleID"]						= $roleID;
			$dataView["transactionID"]				= $transactionID;
			$dataView["objComponent"]				= $objComponent;
			$dataView["componentEmployeeID"]		= $objComponentEmployee->componentID;
			$dataView["objComponentCatalog"]		= $objComponentCatalog;
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_workshop_taller","statusID",$companyID,$branchID,$roleID);
			$dataView["objParameterUrlPrinterInput"]	= $objParameterUrlPrinterInput ? $objParameterUrlPrinterInput->value : "app_purchase_taller/viewPrinterFormatoA4";
			$dataView["objParameterUrlPrinterOutput"]	= $objParameterUrlPrinterOutput ? $objParameterUrlPrinterOutput->value : "app_purchase_taller/viewPrinterFormatoA4Output";

			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_purchase_printertaller/index_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_purchase_printertaller/index_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_purchase_printertaller/index_script',$dataView);//--finview
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
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);

		    return $resultView;
		}
	}

	function search(){
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

						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);
			}

			$companyID 			= $dataSession["user"]->companyID;
			$transactionID		= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_workshop_taller",0);

			//Filtros recibidos
			$startOn			= /*inicio get post*/ $this->request->getPost("txtStartOn");
			$endOn				= /*inicio get post*/ $this->request->getPost("txtEndOn");
			$employerEntityID	= /*inicio get post*/ $this->request->getPost("txtEmployerID");
			$fixedAssentID		= /*inicio get post*/ $this->request->getPost("txtFixedAssetID");
			$statusID			= /*inicio get post*/ $this->request->getPost("txtStatusID");

			$objList = $this->Transaction_Master_Model->getWorkshopOrdersFiltered(
				$companyID,
				$transactionID,
				$startOn,
				$endOn,
				$employerEntityID,
				$fixedAssentID,
				$statusID
			);

			//Dashboard: totales y agrupaciones
			$total				= 0;
			$dashboardByEmployer= array();
			$dashboardByStatus	= array();
			$dashboardByEstado	= array();

			if($objList){
				$total = count($objList);
				foreach($objList as $row){
					$keyEmp = ($row->employerName != "") ? $row->employerName : "SIN ASIGNAR";
					$keySta = ($row->workflowStageName != "") ? $row->workflowStageName : "SIN ESTADO";
					$keyEqu = ($row->estadoEquipoName != "") ? $row->estadoEquipoName : "SIN DEFINIR";

					$dashboardByEmployer[$keyEmp]	= isset($dashboardByEmployer[$keyEmp]) ? $dashboardByEmployer[$keyEmp] + 1 : 1;
					$dashboardByStatus[$keySta]		= isset($dashboardByStatus[$keySta]) ? $dashboardByStatus[$keySta] + 1 : 1;
					$dashboardByEstado[$keyEqu]		= isset($dashboardByEstado[$keyEqu]) ? $dashboardByEstado[$keyEqu] + 1 : 1;
				}
			}

			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'total'					=> $total,
				'data'					=> $objList ? $objList : array(),
				'dashboardByEmployer'	=> $dashboardByEmployer,
				'dashboardByStatus'		=> $dashboardByStatus,
				'dashboardByEstado'		=> $dashboardByEstado
			));//--finjson

		}
		catch(\Exception $ex){
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}

	//Imprime en un solo PDF todas las ordenes de ENTRADA del filtro, una por hoja
	function viewPrinterAllInput(){
		return $this->printAll("input");
	}

	//Imprime en un solo PDF todas las ordenes de SALIDA del filtro, una por hoja
	function viewPrinterAllOutput(){
		return $this->printAll("output");
	}

	//Construye un unico PDF concatenando el reporte de cada orden con salto de pagina
	private function printAll($mode){
		try{
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);

						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_EDIT);
			}

			$companyID 			= $dataSession["user"]->companyID;
			$transactionID		= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_workshop_taller",0);

			//Mismos filtros que la pantalla / grid
			$startOn			= /*inicio get post*/ $this->request->getPost("txtStartOn") ?: $this->request->getGet("txtStartOn");
			$endOn				= /*inicio get post*/ $this->request->getPost("txtEndOn") ?: $this->request->getGet("txtEndOn");
			$employerEntityID	= /*inicio get post*/ $this->request->getPost("txtEmployerID") ?: $this->request->getGet("txtEmployerID");
			$fixedAssentID		= /*inicio get post*/ $this->request->getPost("txtFixedAssetID") ?: $this->request->getGet("txtFixedAssetID");
			$statusID			= /*inicio get post*/ $this->request->getPost("txtStatusID") ?: $this->request->getGet("txtStatusID");

			//Validar intervalo de fecha obligatorio
			if(trim($startOn) == "" || trim($endOn) == "")
			throw new \Exception("DEBE SELECCIONAR UN INTERVALO DE FECHA (FECHA INICIAL Y FECHA FINAL) ANTES DE GENERAR EL PDF");

			$objList = $this->Transaction_Master_Model->getWorkshopOrdersFiltered(
				$companyID,
				$transactionID,
				$startOn,
				$endOn,
				$employerEntityID,
				$fixedAssentID,
				$statusID
			);

			if(!$objList || count($objList) == 0)
			throw new \Exception("NO HAY ORDENES PARA IMPRIMIR CON LOS FILTROS APLICADOS");

			$htmlFinal	= "";
			$total		= count($objList);
			$indice		= 0;
			foreach($objList as $orden){
				$indice++;
				$htmlOrden = $this->buildOrdenHtml($dataSession,$companyID,$orden->transactionID,$orden->transactionMasterID,$mode);
				//Cada orden ocupa una sola hoja: salto de pagina DESPUES de cada orden,
				//excepto la ultima para no generar una hoja en blanco al final.
				$pageBreak = ($indice < $total) ? "page-break-after: always;" : "";
				$htmlFinal .= "<div style='".$pageBreak."'>".$htmlOrden."</div>";
			}

			$this->dompdf->loadHTML($htmlFinal);
			$this->dompdf->render();

			$nombre = ($mode == "output") ? "ordenes_salida_taller" : "ordenes_entrada_taller";
			$this->dompdf->stream($nombre.".pdf", ['Attachment' => false]);
			exit;

		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);

		    return $resultView;
		}
	}

	//Genera el HTML del reporte de una orden de taller (entrada u salida) reutilizando los helpers del taller
	private function buildOrdenHtml($dataSession,$companyID,$transactionID,$transactionMasterID,$mode){
		$branchID 					= $dataSession["user"]->branchID;
		$roleID 					= $dataSession["role"]->roleID;

		$objComponentTaller			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_workshop_taller");
		if(!$objComponentTaller)
		throw new \Exception("EL COMPONENTE 'tb_transaction_master_workshop_taller' NO EXISTE...");

		$objParameter				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
		$objCompany 				= $this->Company_Model->get_rowByPK($companyID);
		$objParameterTelefono		= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);

		$datView["objTM"]	 		= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
		$datView["objTMI"]			= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
		$datView["objTM"]->transactionOn = date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
		$datView["objTC"]			= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
		$datView["objTMD"]			= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentTaller->componentID);
		$datView["objCurrency"]		= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
		$datView["tipoCambio"]		= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
		$datView["objStage"]		= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_workshop_taller","statusID",$datView["objTM"]->statusID,$companyID,APP_BRANCH,APP_ROL_SUPERADMIN);
		$datView["objUser"] 		= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
		$datView["objBranch"]		= $this->Branch_Model->get_rowByPK($companyID,$datView["objUser"]->locationID);

		$objComponentFile			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_file");
		if(!$objComponentFile)
		throw new \Exception("EL COMPONENTE 'tb_file' NO EXISTE...");

		$objCurrency				= $this->core_web_currency->getCurrencyDefault($companyID);
		$targetCurrency				= $this->core_web_currency->getCurrencyExternal($companyID);
		$objListCurrency			= $this->Company_Currency_Model->getByCompany($companyID);

		$dataView["objTransactionMaster"]				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
		$dataView["objTransactionMaster"]->transactionOn = date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
		$dataView["objTransactionMasterInfo"]			= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
		$dataView["objTransactionMasterDetailDocument"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentFile->componentID);

		//Cliente
		$dataView["objCustomer"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
		$dataView["objCustomerNatural"]	= $this->Natural_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);
		$dataView["objCustomerLegal"]	= $this->Legal_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);

		//Colaborador
		$dataView["objEmployer"]		= $this->Employee_Model->get_rowByEntityID($companyID,$dataView["objTransactionMaster"]->entityIDSecondary);
		$dataView["objEmployerNatural"]	= $this->Natural_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
		$dataView["objEmployerLegal"]	= $this->Legal_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);

		//Factura
		$dataView["objBilling"]			= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$dataView["objTransactionMaster"]->note);

		//Activo Fijo (periodPay)
		$dataView["objFixedAsset"]		= $dataView["objTransactionMaster"]->periodPay ? $this->Fixed_Assent_Model->get_rowByPK($companyID,$branchID,$dataView["objTransactionMaster"]->periodPay) : false;

		//Ubicacion del cliente
		$dataView["objCustomerCountry"]	= ($dataView["objCustomer"] && $dataView["objCustomer"]->countryID) ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objCustomer"]->countryID) : false;
		$dataView["objCustomerState"]	= ($dataView["objCustomer"] && $dataView["objCustomer"]->stateID)   ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objCustomer"]->stateID)   : false;
		$dataView["objCustomerCity"]	= ($dataView["objCustomer"] && $dataView["objCustomer"]->cityID)    ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objCustomer"]->cityID)    : false;

		//Ubicacion del activo fijo
		$dataView["objFixedAssetCountry"]		= ($dataView["objFixedAsset"] && $dataView["objFixedAsset"]->countryID)      ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objFixedAsset"]->countryID)      : false;
		$dataView["objFixedAssetCity"]			= ($dataView["objFixedAsset"] && $dataView["objFixedAsset"]->cityID)         ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objFixedAsset"]->cityID)         : false;
		$dataView["objFixedAssetMunicipality"]	= ($dataView["objFixedAsset"] && $dataView["objFixedAsset"]->municipalityID) ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objFixedAsset"]->municipalityID) : false;

		//Catalogos del activo fijo
		$dataView["objFixedAssetVoltaje"]		= ($dataView["objFixedAsset"] && $dataView["objFixedAsset"]->colorID)    ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objFixedAsset"]->colorID)    : false;
		$dataView["objFixedAssetEficiencia"]	= ($dataView["objFixedAsset"] && $dataView["objFixedAsset"]->categoryID) ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objFixedAsset"]->categoryID) : false;
		$dataView["objFixedAssetCategoria"]		= ($dataView["objFixedAsset"] && $dataView["objFixedAsset"]->typeID)     ? $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objFixedAsset"]->typeID)     : false;

		$dataView["objListCurrency"]		= $objListCurrency;
		$dataView["companyID"]				= $dataSession["user"]->companyID;
		$dataView["userID"]					= $dataSession["user"]->userID;
		$dataView["userName"]				= $dataSession["user"]->nickname;
		$dataView["roleID"]					= $dataSession["role"]->roleID;
		$dataView["roleName"]				= $dataSession["role"]->name;
		$dataView["branchID"]				= $dataSession["branch"]->branchID;
		$dataView["branchName"]				= $dataSession["branch"]->name;
		$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);
		$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_workshop_taller","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
		$dataView["objListEstadosEquipo"]	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_taller","areaID",$companyID);
		$dataView["objListAccesorios"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_taller","priorityID",$companyID);
		$dataView["objItemAccesorios"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_taller","priorityID",$companyID,$dataView["objTransactionMaster"]->priorityID);
		$dataView["objListMarca"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_taller","zoneID",$companyID);
		$dataView["objItemMarca"]			= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_taller","zoneID",$companyID,$dataView["objTransactionMasterInfo"]->zoneID);
		$dataView["objListArticulos"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_taller","routeID",$companyID);
		$dataView["objItemArticulo"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_taller","routeID",$companyID,$dataView["objTransactionMasterInfo"]->routeID);
		$dataView["objListArchivos"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_taller","mesaID",$companyID);
		$dataView["objItemEstadoEquipo"]	= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_taller","areaID",$companyID,$dataView["objTransactionMaster"]->areaID);
		$dataView["objTMD"]					= $datView["objTMD"];

		$html = "";
		if($mode == "output"){
			if($dataSession["company"]->type == "frioTec"){
				$html = helper_reporteA4mmTransactionMasterTallerOutputFrioTec(
					"TALLER",$objCompany,$objParameter,$datView["objTM"],$datView["tipoCambio"],$datView["objCurrency"],$datView["objTMI"],$objParameterTelefono,$dataView,$datView["objStage"][0]->display,$datView["objTC"]->name,$datView
				);
			}else{
				$html = helper_reporteA4mmTransactionMasterTallerOutputGlobalPro(
					"TALLER",$objCompany,$objParameter,$datView["objTM"],$datView["tipoCambio"],$datView["objCurrency"],$datView["objTMI"],$objParameterTelefono,$dataView,$datView["objStage"][0]->display,$datView["objTC"]->name,$datView
				);
			}
		}else{
			if($dataSession["company"]->type == "compu_matt"){
				$html = helper_reporteA4mmTransactionMasterTallerCompuMatt(
					"TALLER",$objCompany,$objParameter,$datView["objTM"],$datView["tipoCambio"],$datView["objCurrency"],$datView["objTMI"],$objParameterTelefono,$dataView,$datView["objStage"][0]->display,$datView["objTC"]->name
				);
			}else if($dataSession["company"]->type == "frioTec"){
				$html = helper_reporteA4mmTransactionMasterTallerFrioTec(
					"TALLER",$objCompany,$objParameter,$datView["objTM"],$datView["tipoCambio"],$datView["objCurrency"],$datView["objTMI"],$objParameterTelefono,$dataView,$datView["objStage"][0]->display,$datView["objTC"]->name,$datView
				);
			}else{
				$html = helper_reporteA4mmTransactionMasterTallerGlobalPro(
					"TALLER",$objCompany,$objParameter,$datView["objTM"],$datView["tipoCambio"],$datView["objCurrency"],$datView["objTMI"],$objParameterTelefono,$dataView,$datView["objStage"][0]->display,$datView["objTC"]->name,$datView
				);
			}
		}

		return $html;
	}
}
