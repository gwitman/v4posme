<?php
//posme:2023-02-27
namespace App\Controllers;

class app_inventory_otherinput extends _BaseController
{
	function viewRegister()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession				= $this->session->get();

			//PERMISO SOBRE LA FUNCION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);


				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);
			}


			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri				
			$companyID 					= $dataSession["user"]->companyID;
			$branchID 					= $dataSession["user"]->branchID;
			$roleID 					= $dataSession["role"]->roleID;


			//Get Component
			$objComponent				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameterLogo			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
			//Get Company
			$objCompany 				= $this->Company_Model->get_rowByPK($companyID);


			//Get Documento				
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID, $transactionID, $transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn), "Y-m-d");
			$datView["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID, $datView["objTM"]->targetWarehouseID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_otherinput", "statusID", $datView["objTM"]->statusID, $companyID, $branchID, $roleID);

			if ($dataSession["company"]->flavorID == 728 /*pasteleria balladares*/) {
				$html = helper_reporte80mmInventoryInputPasteleriaBalladares(
					"ENTRADA DE INVENTARIO",
					$objCompany,
					$objParameterLogo,
					$datView["objTM"],
					$datView["objStage"][0]->display, /*estado*/
					$datView["objTMD"],
					$datView["objWarehouse"]
				);
			} else {
				$html = helper_reporteA4TransactionMasterOutherInputGlobalPro(
					"ENTRADA DE INVENTARIO",
					$objCompany,
					$objParameterLogo,
					$datView["objTM"],
					$datView["objStage"][0]->display, /*estado*/
					$datView["objTMD"],
					$datView["objWarehouse"]
				);
			}

			//echo $html;
			$this->dompdf->loadHTML($html);

			//1cm = 29.34666puntos
			//a4: 210 ancho x 297
			//a4: 21cm x 29.7cm
			//a4: 595.28puntos x 841.59puntos

			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->setPaper(array(0,0,234.76,6000));

			$this->dompdf->render();

			$objParameterShowLinkDownload		= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD", $companyID);
			$objParameterShowLinkDownload		= $objParameterShowLinkDownload->value;
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW", $companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;

			$fileNamePut 						= "transferencia_" . $transactionMasterID . "_" . date("dmYhis") . ".pdf";
			$fileNamePdf 						= $datView["objTM"]->transactionNumber . ".pdf";

			//$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
			//	
			//file_put_contents(
			//	$path,
			//	$this->dompdf->output()					
			//);									
			//chmod($path, 644);

			if ($objParameterShowLinkDownload == "true") {
				echo "<a 
					href='" . base_url() . "/resource/file_company/company_" . $companyID . "/component_48/component_item_" . $transactionMasterID . "/" .
					$fileNamePut . "'>download factura</a>
				";
			} else {
				//visualizar				
				$this->dompdf->stream($fileNamePdf, ['Attachment' => $objParameterShowDownloadPreview]);
			}

			//descargar
			//$this->dompdf->stream();

		} catch (\Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}
	function searchTransactionMaster()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession				= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited 				= false;
				$permited 				= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ACCESS_FUNCTION);
			}

			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////

			$transactionNumber 	= /*inicio get post*/ $this->request->getPost("transactionNumber");

			if (!$transactionNumber) {
				throw new \Exception(NOT_PARAMETER);
			}
			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID, $transactionNumber);

			if (!$objTM)
				throw new \Exception("NO SE ENCONTRO EL DOCUMENTO");



			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
			)); //--finjson

		} catch (\Exception $ex) {

			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine() . " " . $ex->getMessage()
			)); //--finjson
		}
	}
	function delete()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_DELETE);
			}

			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////						

			//Obtener Parametros
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");
			$transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");

			if ((!$companyID && !$transactionID && !$transactionMasterID)) {
				throw new \Exception(NOT_PARAMETER);
			}


			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
				throw new \Exception(NOT_DELETE);


			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_otherinput", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new \Exception(NOT_WORKFLOW_DELETE);

			if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $objTM->transactionOn))
				throw new \Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");


			//Eliminar el Registro
			$this->Transaction_Master_Model->delete_app_posme($companyID, $transactionID, $transactionMasterID);
			$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID, $transactionID, $transactionMasterID);

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			)); //--finjson

		} catch (\Exception $ex) {

			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine() . " " . $ex->getMessage()
			)); //--finjson
		}
	}
	function insertElement($dataSession)
	{
		try {
			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited 				= false;
				$permited 				= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}

			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_otherinput");
			if (!$objComponent)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_otherinput' NO EXISTE...");

			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem)
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			if ($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtTransactionOn")))
				throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");


			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_otherinput", 0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID, $transactionID);

			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID, $dataSession["user"]->branchID, "tb_transaction_master_otherinput", 0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($objTM["companyID"], $objTM["transactionID"]);
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponent->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtDescription"); //--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
			$objTM["currencyID2"]					= $objTM["currencyID"]; //$this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= 1; //$this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
			$objTM["reference1"] 					= "";
			$objTM["reference2"] 					= "";
			$objTM["reference3"] 					= "";
			$objTM["reference4"] 					= "";
			$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["amount"] 						= /*inicio get post*/ $this->request->getPost("txtTotal");
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseSourceID"); //--fin peticion get o post
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

			$db = db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);

			//Crear la Carpeta para almacenar los Archivos del Documento			
			if (!file_exists(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $transactionMasterID)) {
				mkdir(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $transactionMasterID, 0700, true);
			}
			//Recorrer la lista del detalle del documento
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");
			$arrayListCost	 							= /*inicio get post*/ $this->request->getPost("txtDetailCost");

			if (!empty($arrayListItemID)) {
				foreach ($arrayListItemID as $key => $value) {
					$itemID 		= $value;
					$quantity 		= helper_StringToNumber($arrayListQuantity[$key]);
					$cost 			= helper_StringToNumber($arrayListCost[$key]);
					$lote 			= $arrayListLote[$key];
					$vencimiento	= $arrayListVencimiento[$key];

					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					$objTMD["quantity"] 					= $quantity;
					$objTMD["unitaryCost"]					= $cost;
					$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];

					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= 0;
					$objTMD["discount"]						= 0;
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["promotionID"] 					= 0;

					$objTMD["lote"]							= $lote;
					$objTMD["expirationDate"]				= $vencimiento == "" ? NULL :  $vencimiento;
					$objTMD["reference3"]					= '';
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];

					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
				}
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_inventory_otherinput/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_inventory_otherinput/add');
			}
		} catch (\Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}
	function updateElement($dataSession)
	{
		try {
			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited 				= false;
				$permited 				= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);
			}

			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_otherinput");
			if (!$objComponent)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_otherinput' NO EXISTE...");

			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem)
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			$oldStatusID 							= $objTM->statusID;

			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
				throw new \Exception(NOT_EDIT);

			//Validar si el estado permite editar
			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_otherinput", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new \Exception(NOT_WORKFLOW_EDIT);

			if ($this->core_web_accounting->cycleIsCloseByDate($companyID, $objTM->transactionOn))
				throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");

			//Actualizar Maestro
			$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtDescription"); //--fin peticion get o post
			$objTMNew["exchangeRate"]					= 1; //$this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$objTM->currencyID,$objTM->currencyID2);
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["targetWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseSourceID"); //--fin peticion get o post

			$objTMNew["amount"] 						= /*inicio get post*/ $this->request->getPost("txtTotal");
			$db = db_connect();
			$db->transStart();
			//El Estado solo permite editar el workflow
			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_otherinput", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			} else {
				$this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);
			}

			//Actualizar Detalle
			$listTMD_ID 								= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");
			$arrayListCost	 							= /*inicio get post*/ $this->request->getPost("txtDetailCost");
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");

			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID, $transactionID, $transactionMasterID, $listTMD_ID);

			if (!empty($arrayListItemID)) {
				foreach ($arrayListItemID as $key => $value) {
					$transactionMasterDetailID					= $listTMD_ID[$key];
					$lote 										= $arrayListLote[$key];
					$vencimiento								= $arrayListVencimiento[$key];

					//Nuevo Detalle
					if ($transactionMasterDetailID == 0) {
						$objTMD 								= array();
						$objTMD["companyID"] 					= $companyID;
						$objTMD["transactionID"] 				= $transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentItem->componentID;
						$objTMD["componentItemID"] 				= $value; //itemID
						$objTMD["quantity"] 					= helper_StringToNumber($arrayListQuantity[$key]); //cantidad
						$objTMD["unitaryCost"]					= helper_StringToNumber($arrayListCost[$key]); //costo
						$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];

						$objTMD["unitaryAmount"]				= 0;
						$objTMD["amount"] 						= 0;
						$objTMD["discount"]						= 0;
						$objTMD["unitaryPrice"]					= 0;
						$objTMD["promotionID"] 					= 0;

						//
						//
						$objTMD["lote"]							= $lote;
						$objTMD["expirationDate"]				= $vencimiento == "" ? NULL :  $vencimiento;
						$objTMD["reference3"]					= '';
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["inventoryWarehouseSourceID"]	= NULL;
						$objTMD["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];
						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					}
					//Editar Detalle
					else 
					{
						$objTMD 									= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID, $transactionMasterDetailID);
						$objTMDNew["quantity"] 						= helper_StringToNumber($arrayListQuantity[$key]); //cantidad
						$objTMDNew["unitaryCost"]					= helper_StringToNumber($arrayListCost[$key]); //costo
						$objTMDNew["cost"] 							= $objTMDNew["quantity"] * $objTMDNew["unitaryCost"];
						$objTMDNew["lote"]							= $lote;
						$objTMDNew["expirationDate"]				= $vencimiento == "" ? NULL :  $vencimiento;

						$objTMDNew["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $transactionMasterDetailID, $objTMDNew);
					}
				}
			}

			//Aplicar el Documento?
			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_otherinput", "statusID", $objTMNew["statusID"], COMMAND_APLICABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"]) {
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewInput($companyID, $transactionID, $transactionMasterID);

				//Crear Conceptos.
				$this->core_web_concept->otherinput($companyID, $transactionID, $transactionMasterID);
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_inventory_otherinput/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_inventory_otherinput/add');
			}
		} catch (\Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}
	function save($mode = "")
	{
		$mode 				= helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);

		//AUTENTICADO
		if (!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();

		//Validar Formulario						
		$this->validation->setRule("txtWarehouseSourceID", "Bodega", "required");
		$this->validation->setRule("txtStatusID", "Estado", "required");
		$this->validation->setRule("txtTransactionOn", "Fecha", "required");

		//Validar Formulario
		if (!$this->validation->withRequest($this->request)->run()) {
			$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
			$this->core_web_notification->set_message(true, $stringValidation);
			$this->response->redirect(base_url() . "/" . 'app_inventory_otherinput/add');
			exit;
		}
		//Guardar o Editar Registro						
		if ($mode == "new") {
			$this->insertElement($dataSession);
		} else if ($mode == "edit") {
			$this->updateElement($dataSession);
		} else {
			$stringValidation = "El modo de operacion no es correcto (new|edit)";
			$this->core_web_notification->set_message(true, $stringValidation);
			$this->response->redirect(base_url() . "/" . 'app_inventory_otherinput/add');
			exit;
		}
	}
	function edit()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);
			}

			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////

			//Obtener parametros
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "companyID"); //--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionID"); //--finuri
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "transactionMasterID"); //--finuri
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;
			$userID 				= $dataSession["user"]->userID;
			if ((!$transactionID || !$transactionMasterID)) {
				$this->response->redirect(base_url() . "/" . 'app_inventory_otherinput/add');
			}

			//Obtener el Registro			
			$datView["objTM"]	 				= $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			$datView["objTMD"]					= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID, $transactionID, $transactionMasterID);
			$datView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID, $userID);
			$datView["objUser"]					= $dataSession["user"];
			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_otherinput", "statusID", $datView["objTM"]->statusID, $companyID, $branchID, $roleID);
			$datView["objTM"]->transactionOn 	= date_format(date_create($datView["objTM"]->transactionOn), "Y-m-d");
			$objListComanyParameter				= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVOICE_CANTIDAD_ITEM");
			$datView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;

			$objComponentItem					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem)
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$datView["componentItemID"] 		= $objComponentItem->componentID;

			//Renderizar Resultado
			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				=  $this->core_web_notification->get_message();
			$dataSession["head"]				= /*--inicio view*/ view('app_inventory_otherinput/edit_head', $datView); //--finview
			$dataSession["body"]				= /*--inicio view*/ view('app_inventory_otherinput/edit_body', $datView); //--finview
			$dataSession["script"]				= /*--inicio view*/ view('app_inventory_otherinput/edit_script', $datView); //--finview
			$dataSession["footer"]				= "";
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r

		} catch (\Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}

	function add()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession				= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited 				= false;
				$permited 				= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}

			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			$dataView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID, $userID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_otherinput", "statusID", $companyID, $branchID, $roleID);

			//Obtener el componente de Item
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponent)
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			$objParameterWarehouseDefault		= $this->core_web_parameter->getParameter("INVENTORY_ITEM_WAREHOUSE_DEFAULT", $companyID);
			$warehouseDefault 					= $objParameterWarehouseDefault->value;
			$dataView["warehouseDefault"]		= $warehouseDefault;
			$dataView["componentItemID"] 		= $objComponent->componentID;
			$objListComanyParameter				= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;

			//Renderizar Resultado
			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				= $this->core_web_notification->get_message();
			$dataSession["head"]				= /*--inicio view*/ view('app_inventory_otherinput/news_head', $dataView); //--finview
			$dataSession["body"]				= /*--inicio view*/ view('app_inventory_otherinput/news_body', $dataView); //--finview
			$dataSession["script"]				= /*--inicio view*/ view('app_inventory_otherinput/news_script', $dataView); //--finview
			$dataSession["footer"]				= "";
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r

		} catch (\Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}
	function index($dataViewID = null)
	{
		try 
		{
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession				= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {

				$permited 				= false;
				$permited 				= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ACCESS_FUNCTION);
			}
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_otherinput");
			if (!$objComponent)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_otherinput' NO EXISTE...");

			//Vista por defecto 
			if ($dataViewID == null) {
				$targetComponentID			= 0;
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
			}
			//Otra vista
			else {
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
			}

			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_otherinput/list_head'); //--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_otherinput/list_footer'); //--finview
			$dataSession["body"]			= $dataViewRender;
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_otherinput/list_script'); //--finview
			$dataSession["script"]			= $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r	
		} catch (\Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}

	function add_masinformacion($fnCallback = "", $itemID = "", $transactionMasterDetailID = "", $positionID = "", $lote = "", $vencimiento = "")
	{
		$fnCallback 				= helper_SegmentsByIndex($this->uri->getSegments(), 1, $fnCallback);
		$itemID 					= helper_SegmentsByIndex($this->uri->getSegments(), 2, $itemID);
		$transactionMasterDetailID	= helper_SegmentsByIndex($this->uri->getSegments(), 3, $transactionMasterDetailID);
		$positionID 				= helper_SegmentsByIndex($this->uri->getSegments(), 4, $positionID);
		$lote 						= helper_SegmentsByIndex($this->uri->getSegments(), 5, $lote);
		$vencimiento 				= helper_SegmentsByIndex($this->uri->getSegments(), 6, $vencimiento);

		//AUTENTICACION
		if (!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession				= $this->session->get();

		//PERMISO SOBRE LA FUNCION
		if (APP_NEED_AUTHENTICATION == true) {
			$permited 				= false;
			$permited 				= $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

			if (!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);

			$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
			if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);
		}

		$data["itemID"] 					= $itemID;
		$data["transactionMasterDetailID"] 	= $transactionMasterDetailID;
		$data["positionID"] 				= $positionID;
		$data["fnCallback"] 				= $fnCallback;
		$data["lote"] 						= $lote;
		$data["vencimiento"] 				= $vencimiento;

		//Renderizar Resultado
		$dataSession["message"]		= "";
		$dataSession["head"]		= /*--inicio view*/ view('app_inventory_otherinput/popup_masinformacion_item_head', $data); //--finview
		$dataSession["body"]		= /*--inicio view*/ view('app_inventory_otherinput/popup_masinformacion_item_body', $data); //--finview
		$dataSession["script"]		= /*--inicio view*/ view('app_inventory_otherinput/popup_masinformacion_item_script', $data); //--finview
		return view("core_masterpage/default_popup", $dataSession); //--finview-r
	}
}
