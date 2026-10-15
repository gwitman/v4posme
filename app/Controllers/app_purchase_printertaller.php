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
}
