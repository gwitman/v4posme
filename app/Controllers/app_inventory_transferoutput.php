<?php
//posme:2023-02-27
namespace App\Controllers;

class app_inventory_transferoutput extends _BaseController
{
	function viewRegister()
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameterLogo	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);
			//Get Company
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID, $transactionID, $transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn), "Y-m-d");
			$datView["objWarehouse"] 				= $this->Warehouse_Model->get_rowByPK($datView["objTM"]->companyID, $datView["objTM"]->sourceWarehouseID);
			$datView["objWarehouseTarget"]			= $this->Warehouse_Model->get_rowByPK($datView["objTM"]->companyID, $datView["objTM"]->targetWarehouseID);
			$datView["objNaturalSource"]			= $datView["objTM"] != NULL ? $this->Natural_Model->get_rowByPK($companyID, $datView["objTM"]->branchID, $datView["objTM"]->entityID) : NULL;
			$datView["objNaturalTarget"]			= $datView["objTM"] != NULL ? $this->Natural_Model->get_rowByPK($companyID, $datView["objTM"]->branchID, $datView["objTM"]->entityIDSecondary) : NULL;
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_transferoutput", "statusID", $datView["objTM"]->statusID, $companyID, $branchID, $roleID);



			$objParameterTelefono								= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$datViewArray["phoneNumber"]						= $objParameterTelefono->value;
			$datViewArray["address"]							= $objCompany->address;
			$datViewArray["transactionNumber"]					= $datView["objTM"]->transactionNumber;
			$objParameterRuc	    							= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datViewArray["ruc"]								= $objParameterRuc->value;
			$datViewArray["transactionOn"] 						= $datView["objTM"]->transactionOn;
			$datViewArray["warehouseSourceName"]				= $datView["objWarehouse"]->name;
			$datViewArray["warehouseTargetName"]				= $datView["objWarehouseTarget"]->name;
			$datViewArray["employerSourceName"]					= $datView["objNaturalSource"] ? $datView["objNaturalSource"]->firstName 	: "";
			$datViewArray["employerSourceLastName"]				= $datView["objNaturalSource"] ? $datView["objNaturalSource"]->lastName		: "";
			$datViewArray["employerTargetName"]					= $datView["objNaturalTarget"] ? $datView["objNaturalTarget"]->firstName	: "";
			$datViewArray["employerTargetLastName"]				= $datView["objNaturalTarget"] ? $datView["objNaturalTarget"]->lastName		: "";
			$datViewArray["transactionMasterDetail"] 			= array();
			
			foreach($datView["objTMD"] as $detail_)
			{
				$row = array(
					"itemNumber"			=>$detail_->itemNumber,
					"itemName"				=>$detail_->itemName,
					"itemNameQuantity"		=>sprintf("%01.2f",round($detail_->quantity,2)),
					"itemNamePrice"			=>sprintf("%01.2f",round(0,2)),
					"itemNameAmount"		=>sprintf("%01.2f",round($detail_->amount,2))	
				);
				array_push($datViewArray["transactionMasterDetail"],$row);		
			}
			
			
			//Obtener imagen de logo
			$path    						= PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;    
			$type    						= pathinfo($path, PATHINFO_EXTENSION);
			$data    						= file_get_contents($path);
			$base64  						= 'data:image/' . $type . ';base64,' . base64_encode($data);
			$datViewArray["imageBase64"]	= $base64;
			
			$htmlTemplateCompany					= getBahavioDB($objCompany->type,"app_inventory_transferoutput","templateTransferOutput","");
			$htmlTemplateDemo 						= getBahavioDB("demo","app_inventory_transferoutput","templateTransferOutput","");
			
			
			
			if($htmlTemplateCompany == "" || !htmlTemplateCompany)
				$htmlTemplateCompany = $htmlTemplateDemo;
			
			
			
			//Parse plantilla 
			$parser = \Config\Services::parser();			
			$html 	= $parser->setData($datViewArray)->renderString($htmlTemplateCompany);
			$this->dompdf->loadHTML($html);
			
			//$html = helper_reporteA4TransactionMasterTransferOutputGlobalPro(
			//	"SALIDA POR TRANSFERENCIA",
			//	$objCompany,
			//	$objParameterLogo,
			//	$datView["objTM"],
			//	$datView["objStage"][0]->display, /*estado*/
			//	$datView["objTMD"],
			//	$datView["objNaturalSource"],
			//	$datView["objNaturalTarget"],
			//	$datView["objWarehouse"],
			//	$datView["objWarehouseTarget"]
			//);
			//echo $html;
			//return;
			//$this->dompdf->loadHTML($html);


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

			$fileNamePut = "transferencia_" . $transactionMasterID . "_" . date("dmYhis") . ".pdf";
			$fileNamePdf = $datView["objTM"]->transactionNumber . ".pdf";

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
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

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
			$objTM 				= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID, $transactionNumber);

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


			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferoutput", "statusID", $objTM->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
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
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);
			}


			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID, get_class($this) . "/" . "index");
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferoutput");
			if (!$objComponent)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_transferoutput' NO EXISTE...");

			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem)
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			if ($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtTransactionOn")))
				throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");


			//Crear la transaccion  de salida por transferencia
			//
			/////////////////////////////////////////////////////
			/////////////////////////////////////////////////////
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_transferoutput", 0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID, $transactionID);
			$objListComanyParameter					= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCausalOfProduccion			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVENTORY_TRANSACTION_CAUSAL_PRODUCTION_ITEM")->value;


			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID, $dataSession["user"]->branchID, "tb_transaction_master_transferoutput", 0);
			$objTM["transactionCausalID"] 			= $this->request->getPost("txtTransactionCausalID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponent->componentID;
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
			$objTM["entityIDSecondary"] 			= /*inicio get post*/ $this->request->getPost("txtEmployeeIDTarget");
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtDescription"); //--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
			$objTM["currencyID2"]					= $objTM["currencyID"]; //$this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= 1; //$this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
			$objTM["reference1"] 					= "";
			$objTM["reference2"] 					= "";
			$objTM["reference3"] 					= "";
			$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTM["isTemplate"] 					= /*inicio get post*/ $this->request->getPost("txtIsTemplate") == "true" ? 1 : 0 ;
			
			
			$workflowStageOutput 					= 0;
			$workflowStageInput 					= 0;
			$inventoryTransferAutoApply				= "";
			$inventoryTransferAutoApply				= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"], "INVENTORY_TRANSFEROUTPUT_AUTO_APPLY_TRANSFERINPUT")->value;

			//Obtener los estados de cada transccion
			if ($inventoryTransferAutoApply == "true") {
				$workflowStageOutput = $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"], "INVENTORY_TRANSFEROUTPUT_WORKFLOW_APPLY")->value;
				$workflowStageInput  = $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"], "INVENTORY_TRANSFERINPUT_WORKFLOW_APPLY")->value;
			} else {
				$workflowStageOutput 				= /*inicio get post*/ $this->request->getPost("txtStatusID");
				$objTMInputWorkflowStageInit 		= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_transferinput", "statusID", $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID);
				$workflowStageInput 				= $objTMInputWorkflowStageInit[0]->workflowStageID;
				$objTMInput["statusID"]				= $workflowStageInput;
			}

			$objTM["statusID"] 						= $workflowStageOutput;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseSourceID"); //--fin peticion get o post
			$objTM["targetWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseTargetID"); //--fin peticion get o post
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);



			$db = db_connect();
			$db->transStart();
			$objWarehouseTarget  = $this->Warehouse_Model->get_rowByPK($companyID, $objTM["targetWarehouseID"]);
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);

			//Crear la carpeta de salida por transferencia			
			if (!file_exists(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $transactionMasterID)) {
				mkdir(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $transactionMasterID, 0700, true);
			}
			//Crear detalle de salida por transferencia
			$arrayListItemID 								= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
			$arrayListQuantity	 							= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");
			$arrayListLote	 								= /*inicio get post*/ $this->request->getPost("txtDetailLote");
			$arrayListVencimiento							= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");
			$txtIsTrnasferAll 								= $this->request->getPost("txtIsTrnasferAll");
			$txtTransactionNumberTemplate					= $this->request->getPost("txtTransactionNumberTemplate");
			
			//Obtener el detalle de las cantidades en base a una plantilla
			if(
				empty($arrayListItemID)
				&& 
				$txtTransactionNumberTemplate != ""
			)
			{
				$arrayListItemID 								= [];
				$arrayListQuantity	 							= [];
				$arrayListLote	 								= [];
				$arrayListVencimiento							= [];				
				$objListItemWarehouseProcess					= $this->Transaction_Master_Detail_Model->get_rowByTransactionTo_TransactionNumberAnd_WarehouseSourceAnd_WarehuseTareget($objTM["companyID"],$txtTransactionNumberTemplate,$objTM["sourceWarehouseID"], $objTM["targetWarehouseID"] );
				if($objListItemWarehouseProcess)
				{
					foreach($objListItemWarehouseProcess as $itemWare)
					{
						$arrayListItemID[]			= $itemWare->itemID;
						$arrayListQuantity[]		= $itemWare->quantity;
						$arrayListLote[]			= "";
						$arrayListVencimiento[]		= "";
					}
				}
			}
			
			
			//Obtene el detalle de las cantidades en bodegas
			//Para aplicar la salida
			if(
				empty($arrayListItemID)
				&& 
				$txtIsTrnasferAll == "true"
			)
			{
				$arrayListItemID 								= [];
				$arrayListQuantity	 							= [];
				$arrayListLote	 								= [];
				$arrayListVencimiento							= [];				
				$objListItemWarehouseProcess					= $this->Itemwarehouse_Model->getByWarehouseSourceAndTarget($objTM["companyID"],$objTM["sourceWarehouseID"], $objTM["targetWarehouseID"] );
				if($objListItemWarehouseProcess)
				{
					foreach($objListItemWarehouseProcess as $itemWare)
					{
						$arrayListItemID[]			= $itemWare->itemID;
						$arrayListQuantity[]		= $itemWare->quantity;
						$arrayListLote[]			= "";
						$arrayListVencimiento[]		= "";
					}
				}
			}
			
			//Obtener el detalle de los productos agregados en pantalla
			if (!empty($arrayListItemID)) {
				foreach ($arrayListItemID as $key => $value) {
					$objItem 								= $this->Item_Model->get_rowByPK($objTM["companyID"], $value);
					$objItemWarehouse						= $this->Itemwarehouse_Model->getByPK($objTM["companyID"], $value, $objTM["sourceWarehouseID"]);
					$isServices								= is_null($objItem->isServices) ? false : $objItem->isServices;
					$lote 									= $arrayListLote[$key];
					$vencimiento							= $arrayListVencimiento[$key];

					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $value; //itemID
					$objTMD["quantity"] 					= helper_StringToNumber($arrayListQuantity[$key]); //cantidad
					$objTMD["unitaryCost"]					= $objItem->cost;
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

					if ($objItemWarehouse->quantity < $objTMD["quantity"] && $isServices == false)
						throw new \Exception("No hay suficiente existencia del producto en la bodega origen");
				}
			}


			//Enviar reporte de salida por transferencia
			//Si la transccion es aplicable
			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferoutput", "statusID", $objTM["statusID"], COMMAND_APLICABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {

				//Enviar reporte de salida por transferencia
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO", $companyID);


				$query					= "CALL pr_transaction_master_detail(?,?,?);";
				$objDataResultProcedure	= $this->Bd_Model->executeRender(
					$query,
					[APP_COMPANY, $objTM["transactionID"], $transactionMasterID]
				);

				if (isset($objDataResultProcedure))
					$objDataReport["objDetail"]					= $objDataResultProcedure;
				else
					$objDataReport["objDetail"]					= NULL;


				$objDataReport["objCompany"] 					= $objCompany;
				$objDataReport["objLogo"] 						= $objParameter;
				$objDataReport["startOn"] 						= date('Y-m-d H:i:s');
				$objDataReport["endOn"] 						= date('Y-m-d H:i:s');
				$objDataReport["objFirma"] 						= "{companyID:" .  ",branchID:" .  ",userID:" . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_transaction_master_detail" . ",ip:" . $this->request->getIPAddress() . ",sessionID:" . ",agenteID:" . $this->request->getUserAgent()->getAgentString() . ",lastActivity:" .  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}";
				$objDataReport["objFirmaEncription"] 			= md5($objDataReport["objFirma"]);

				$html 	= /*--inicio view*/ view('app_inventory_transferoutput/view_a_disemp', $objDataReport);
				$email 	= $objWarehouseTarget->emailResponsability;

				//Mandar email de salida por transferencia		
				$this->email->setFrom(EMAIL_APP);
				$this->email->setTo($email);
				$this->email->setSubject("TRANSFERENCIA DE SALIDA ELABORADA:");
				$this->email->setMessage($html);
				$resultSend = $this->email->send();


				//Crear la transaccion  de entrada por transferencia
				//
				/////////////////////////////////////////////////////
				/////////////////////////////////////////////////////
				$transactionIDInput 				= $this->core_web_parameter->getParameter("INVENTORY_TRANSFEROUTPUT_RELATION_TRANSFERINPUT", $companyID)->value;
				$objTInput							= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID, $transactionIDInput);
				$objTMInput["companyID"]			= $companyID;
				$objTMInput["transactionID"]		= $transactionIDInput;
				$objTMInput["branchID"]				= $dataSession["user"]->branchID;
				$objTMInput["transactionNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID, $dataSession["user"]->branchID, "tb_transaction_master_transferinput", 0);
				$objTMInput["transactionCausalID"]	= $this->core_web_transaction->getDefaultCausalID($objTMInput["companyID"], $objTMInput["transactionID"]);
				$objTMInput["transactionOn"]		= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
				$objTMInput["statusIDChangeOn"]		= date("Y-m-d H:m:s");
				$objTMInput["componentID"]			= $objComponent->componentID;
				$objTMInput["note"] 				= /*inicio get post*/ $this->request->getPost("txtDescription"); //--fin peticion get o post
				$objTMInput["sign"] 				= $objTInput->signInventory;
				$objTMInput["currencyID"]			= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
				$objTMInput["currencyID2"]			= $objTMInput["currencyID"]; //$this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
				$objTMInput["exchangeRate"]			= 1; //$this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
				$objTMInput["reference1"] 			= $transactionID;
				$objTMInput["reference2"] 			= $transactionMasterID;
				$objTMInput["reference3"] 			= $objTM["transactionNumber"];
				$objTMInput["reference4"] 			= "";
				$objTMInput["statusID"] 			= $workflowStageInput;
				$objTMInput["amount"] 				= 0;
				$objTMInput["isApplied"] 			= 0;
				$objTMInput["journalEntryID"] 		= 0;
				$objTMInput["classID"] 				= NULL;
				$objTMInput["areaID"] 				= NULL;
				$objTMInput["sourceWarehouseID"]	= /*inicio get post*/ $this->request->getPost("txtWarehouseSourceID"); //--fin peticion get o post
				$objTMInput["targetWarehouseID"]	= /*inicio get post*/ $this->request->getPost("txtWarehouseTargetID"); //--fin peticion get o post
				$objTMInput["isActive"]				= 1;
				$this->core_web_auditoria->setAuditCreated($objTMInput, $dataSession, $this->request);
				$transactionMasterIDInput 			= $this->Transaction_Master_Model->insert_app_posme($objTMInput);

				//Recorrer detalle de entrada por transferencia
				$arrayListItemID 									= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
				$arrayListQuantity	 								= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");
				$arrayListLote	 									= /*inicio get post*/ $this->request->getPost("txtDetailLote");
				$arrayListVencimiento								= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");


				//Obtener el detalle de las cantidades en base a una plantilla
				if(
					empty($arrayListItemID)
					&& 
					$txtTransactionNumberTemplate != ""
				)
				{
					$arrayListItemID 								= [];
					$arrayListQuantity	 							= [];
					$arrayListLote	 								= [];
					$arrayListVencimiento							= [];				
					$objListItemWarehouseProcess					= $this->Transaction_Master_Detail_Model->get_rowByTransactionTo_TransactionNumberAnd_WarehouseSourceAnd_WarehuseTareget($objTM["companyID"],$txtTransactionNumberTemplate,$objTM["sourceWarehouseID"], $objTM["targetWarehouseID"] );
					if($objListItemWarehouseProcess)
					{
						foreach($objListItemWarehouseProcess as $itemWare)
						{
							$arrayListItemID[]			= $itemWare->itemID;
							$arrayListQuantity[]		= $itemWare->quantity;
							$arrayListLote[]			= "";
							$arrayListVencimiento[]		= "";
						}
					}
				}
				
				//Obtene el detalle de las cantidades en bodegas
				//Para aplicar la entrada
				if(
					empty($arrayListItemID)
					&& 
					$txtIsTrnasferAll == "true"
				)
				{
					$arrayListItemID 								= [];
					$arrayListQuantity	 							= [];
					$arrayListLote	 								= [];
					$arrayListVencimiento							= [];
					$objListItemWarehouseProcess					= $this->Itemwarehouse_Model->getByWarehouseSourceAndTarget($objTM["companyID"],$objTM["sourceWarehouseID"], $objTM["targetWarehouseID"] );
					if($objListItemWarehouseProcess)
					{
						foreach($objListItemWarehouseProcess as $itemWare)
						{
							$arrayListItemID[]			= $itemWare->itemID;
							$arrayListQuantity[]		= $itemWare->quantity;
							$arrayListLote[]			= "";
							$arrayListVencimiento[]		= "";
						}
					}
				}
				
				if (!empty($arrayListItemID)) {
					foreach ($arrayListItemID as $key => $value) {
						$objItem 									= $this->Item_Model->get_rowByPK($objTMInput["companyID"], $value);
						$objItemWarehouse							= $this->Itemwarehouse_Model->getByPK($objTMInput["companyID"], $value, $objTMInput["sourceWarehouseID"]);
						$lote 										= $arrayListLote[$key];
						$vencimiento								= $arrayListVencimiento[$key];

						$objTMDInput["companyID"] 					= $objTMInput["companyID"];
						$objTMDInput["transactionID"] 				= $objTMInput["transactionID"];
						$objTMDInput["transactionMasterID"] 		= $transactionMasterIDInput;
						$objTMDInput["componentID"]					= $objComponentItem->componentID;
						$objTMDInput["componentItemID"] 			= $value; //itemID
						$objTMDInput["quantity"] 					= helper_StringToNumber($arrayListQuantity[$key]); //cantidad
						$objTMDInput["unitaryCost"]					= $objItem->cost;
						$objTMDInput["cost"] 						= $objTMDInput["quantity"] * $objTMDInput["unitaryCost"];

						$objTMDInput["unitaryAmount"]				= 0;
						$objTMDInput["amount"] 						= 0;
						$objTMDInput["discount"]					= 0;
						$objTMDInput["unitaryPrice"]				= 0;
						$objTMDInput["promotionID"] 				= 0;

						$objTMDInput["reference1"]					= '';
						$objTMDInput["reference2"]					= '';
						$objTMDInput["reference3"]					= '';
						$objTMDInput["catalogStatusID"]				= 0;
						$objTMDInput["inventoryStatusID"]			= 0;
						$objTMDInput["isActive"]					= 1;
						$objTMDInput["quantityStock"]				= 0;
						$objTMDInput["quantiryStockInTraffic"]		= 0;
						$objTMDInput["quantityStockUnaswared"]		= 0;
						$objTMDInput["remaingStock"]				= 0;
						$objTMDInput["lote"]						= $lote;
						$objTMDInput["expirationDate"]				= $vencimiento == "" ? NULL :  $vencimiento;

						$objTMDInput["inventoryWarehouseSourceID"]	= $objTMInput["sourceWarehouseID"];
						$objTMDInput["inventoryWarehouseTargetID"]	= $objTMInput["targetWarehouseID"];

						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMDInput);
					}
				}


				//Actualizar la transaccion  de salida por transferencia
				//
				/////////////////////////////////////////////////////
				/////////////////////////////////////////////////////
				$objTMUpdate["reference1"]	= $objTMInput["transactionID"];
				$objTMUpdate["reference2"]	= $transactionMasterIDInput;
				$objTMUpdate["reference3"]	= $objTMInput["transactionNumber"];
				$this->Transaction_Master_Model->update_app_posme($objTM["companyID"], $objTM["transactionID"], $transactionMasterID, $objTMUpdate);
			}

			//Aplicar Salida por transferencia
			//
			/////////////////////////////////////////////////////
			/////////////////////////////////////////////////////
			//Aplicar el Documento de Salida
			//Ingresar en Kardex. de salida si el causal no es produccion de mercaderia
			if (
				$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferoutput", "statusID", $objTM["statusID"], COMMAND_APLICABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)
				&&
				$objTM["transactionCausalID"] != $objParameterCausalOfProduccion
				&&
				$inventoryTransferAutoApply == "true"
			) {

				$this->core_web_inventory->calculateKardexNewOutput($objTM["companyID"], $objTM["transactionID"], $transactionMasterID);

				//Crear Conceptos.
				//$this->core_web_concept->otherinput($companyID,$transactionID,$transactionMasterID);

			}

			//Aplicar Entrada por transferencia
			//
			/////////////////////////////////////////////////////
			/////////////////////////////////////////////////////
			//Aplicar el Documento de Entrada
			//Ingresar en Kardex. de Entrada si el causal no es produccion de mercaderi			
			if (
				$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferinput", "statusID", $objTMInput["statusID"], COMMAND_APLICABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)
				&&
				$inventoryTransferAutoApply == "true"
			) {
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewInput($objTMInput["companyID"], $objTMInput["transactionID"], $transactionMasterIDInput);

				//Crear Conceptos.
				//$this->core_web_concept->otheroutput($companyID,$transactionID,$transactionMasterID);
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_inventory_transferoutput/edit/companyID/' . $companyID . "/transactionID/" . $objTM["transactionID"] . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_inventory_transferoutput/add');
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
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);
			}


			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferoutput");
			if (!$objComponent)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_transferoutput' NO EXISTE...");


			$objComponentInput						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferinput");
			if (!$objComponentInput)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_transferoinput' NO EXISTE...");


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
			if (!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferoutput", "statusID", $objTM->statusID, COMMAND_EDITABLE_TOTAL, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
				throw new \Exception(NOT_WORKFLOW_EDIT);


			//Actualizar Maestro
			$objTMNew["transactionOn"]				= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTMNew["statusIDChangeOn"]			= date("Y-m-d H:m:s");
			$objTMNew["note"] 						= /*inicio get post*/ $this->request->getPost("txtDescription"); //--fin peticion get o post
			$objTMNew["exchangeRate"]				= 1; //$this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$objTM->currencyID,$objTM->currencyID2);
			$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["sourceWarehouseID"]			= /*inicio get post*/ $this->request->getPost("txtWarehouseSourceID"); //--fin peticion get o post
			$objTMNew["targetWarehouseID"]			= /*inicio get post*/ $this->request->getPost("txtWarehouseTargetID"); //--fin peticion get o post
			$objTMNew["isTemplate"] 				= /*inicio get post*/ $this->request->getPost("txtIsTemplate") == "true" ? 1 : 0 ;
			
			$db = db_connect();
			$db->transStart();
			//El Estado solo permite editar el workflow
			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferoutput", "statusID", $objTM->statusID, COMMAND_EDITABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID)) {
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
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");

			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID, $transactionID, $transactionMasterID, $listTMD_ID);

			if (!empty($arrayListItemID)) {
				foreach ($arrayListItemID as $key => $value) {
					$transactionMasterDetailID					= $listTMD_ID[$key];
					$lote 										= $arrayListLote[$key];
					$vencimiento								= $arrayListVencimiento[$key];
					$objItem 									= $this->Item_Model->get_rowByPK($companyID, $value);
					$objItemWarehouse							= $this->Itemwarehouse_Model->getByPK($companyID, $value, $objTMNew["sourceWarehouseID"]);

					//Validar Stock de Inventario
					if ($objItemWarehouse->quantity < helper_StringToNumber($arrayListQuantity[$key]))
						throw new \Exception("La cantidad de '" . $objItem->itemNumber . " " . $objItem->name . "' es mayor que la disponible en bodega");


					//Nuevo Detalle
					if ($transactionMasterDetailID == 0) {
						$objTMD 								= array();
						$objTMD["companyID"] 					= $companyID;
						$objTMD["transactionID"] 				= $transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentItem->componentID;
						$objTMD["componentItemID"] 				= $value; //itemID
						$objTMD["quantity"] 					= helper_StringToNumber($arrayListQuantity[$key]); //cantidad
						$objTMD["unitaryCost"]					= $objItem->cost;
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
						$objTMD["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
						$objTMD["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];
						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);

						if ($objItemWarehouse->quantity < $objTMD["quantity"])
							throw new \Exception("NO HAY SUFICIENTE EXISTENCIAS DEL PRODUCTO");
					}
					//Editar Detalle
					else {
						$objTMD 									= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID, $transactionMasterDetailID);
						$objTMDNew["quantity"] 						= helper_StringToNumber($arrayListQuantity[$key]); //cantidad
						$objTMDNew["unitaryCost"]					= $objItem->cost;
						$objTMDNew["cost"] 							= $objTMDNew["quantity"] * $objTMDNew["unitaryCost"];
						$objTMDNew["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
						$objTMDNew["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];
						$objTMDNew["lote"]							= $lote;
						$objTMDNew["expirationDate"]				= $vencimiento == "" ? NULL :  $vencimiento;
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $transactionMasterDetailID, $objTMDNew);

						if ($objItemWarehouse->quantity < $objTMDNew["quantity"])
							throw new \Exception("NO HAY SUFICIENTE EXISTENCIAS DEL PRODUCTO");
					}
				}
			}

			//Crear la transaccion de salida
			if ($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_transferoutput", "statusID", $objTMNew["statusID"], COMMAND_APLICABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"]) {

				//Crear la transaccion  de entrada por transferencia
				//
				/////////////////////////////////////////////////////
				/////////////////////////////////////////////////////
				$transactionIDInput 				= $this->core_web_parameter->getParameter("INVENTORY_TRANSFEROUTPUT_RELATION_TRANSFERINPUT", $companyID)->value;
				$objTInput							= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID, $transactionIDInput);
				$objTMInput["companyID"]			= $companyID;
				$objTMInput["transactionID"]		= $transactionIDInput;
				$objTMInput["branchID"]				= $dataSession["user"]->branchID;
				$objTMInput["transactionNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID, $dataSession["user"]->branchID, "tb_transaction_master_transferinput", 0);
				$objTMInput["transactionCausalID"]	= $this->core_web_transaction->getDefaultCausalID($objTMInput["companyID"], $objTMInput["transactionID"]);
				$objTMInput["transactionOn"]		= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
				$objTMInput["statusIDChangeOn"]		= date("Y-m-d H:m:s");
				$objTMInput["componentID"]			= $objComponentInput->componentID;
				$objTMInput["note"] 				= /*inicio get post*/ $this->request->getPost("txtDescription"); //--fin peticion get o post
				$objTMInput["sign"] 				= $objTInput->signInventory;
				$objTMInput["currencyID"]			= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
				$objTMInput["currencyID2"]			= $objTMInput["currencyID"]; //$this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
				$objTMInput["exchangeRate"]			= 1; //$this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
				$objTMInput["reference1"] 			= $transactionID;
				$objTMInput["reference2"] 			= $transactionMasterID;
				$objTMInput["reference3"] 			= $objTM->transactionNumber;
				$objTMInput["reference4"] 			= "";


				$workflowStageInput 				= 0;
				$objTMInputWorkflowStageInit 		= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_transferinput", "statusID", $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID);
				$workflowStageInput 				= $objTMInputWorkflowStageInit[0]->workflowStageID;
				$objTMInput["statusID"] 			= $workflowStageInput;
				$objTMInput["amount"] 				= 0;
				$objTMInput["isApplied"] 			= 0;
				$objTMInput["journalEntryID"] 		= 0;
				$objTMInput["classID"] 				= NULL;
				$objTMInput["areaID"] 				= NULL;
				$objTMInput["sourceWarehouseID"]	= /*inicio get post*/ $this->request->getPost("txtWarehouseSourceID"); //--fin peticion get o post
				$objTMInput["targetWarehouseID"]	= /*inicio get post*/ $this->request->getPost("txtWarehouseTargetID"); //--fin peticion get o post
				$objTMInput["isActive"]				= 1;
				$this->core_web_auditoria->setAuditCreated($objTMInput, $dataSession, $this->request);
				$transactionMasterIDInput 			= $this->Transaction_Master_Model->insert_app_posme($objTMInput);

				//Recorrer detalle de entrada por transferencia
				$arrayListItemID 									= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
				$arrayListQuantity	 								= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");
				$arrayListLote	 									= /*inicio get post*/ $this->request->getPost("txtDetailLote");
				$arrayListVencimiento								= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");

				if (!empty($arrayListItemID)) {
					foreach ($arrayListItemID as $key => $value) {
						$objItem 									= $this->Item_Model->get_rowByPK($objTMInput["companyID"], $value);
						$objItemWarehouse							= $this->Itemwarehouse_Model->getByPK($objTMInput["companyID"], $value, $objTMInput["sourceWarehouseID"]);
						$lote 										= $arrayListLote[$key];
						$vencimiento								= $arrayListVencimiento[$key];

						$objTMDInput["companyID"] 					= $objTMInput["companyID"];
						$objTMDInput["transactionID"] 				= $objTMInput["transactionID"];
						$objTMDInput["transactionMasterID"] 		= $transactionMasterIDInput;
						$objTMDInput["componentID"]					= $objComponentItem->componentID;
						$objTMDInput["componentItemID"] 			= $value; //itemID
						$objTMDInput["quantity"] 					= helper_StringToNumber($arrayListQuantity[$key]); //cantidad
						$objTMDInput["unitaryCost"]					= $objItem->cost;
						$objTMDInput["cost"] 						= $objTMDInput["quantity"] * $objTMDInput["unitaryCost"];

						$objTMDInput["unitaryAmount"]				= 0;
						$objTMDInput["amount"] 						= 0;
						$objTMDInput["discount"]					= 0;
						$objTMDInput["unitaryPrice"]				= 0;
						$objTMDInput["promotionID"] 				= 0;

						$objTMDInput["reference1"]					= '';
						$objTMDInput["reference2"]					= '';
						$objTMDInput["reference3"]					= '';
						$objTMDInput["catalogStatusID"]				= 0;
						$objTMDInput["inventoryStatusID"]			= 0;
						$objTMDInput["isActive"]					= 1;
						$objTMDInput["quantityStock"]				= 0;
						$objTMDInput["quantiryStockInTraffic"]		= 0;
						$objTMDInput["quantityStockUnaswared"]		= 0;
						$objTMDInput["remaingStock"]				= 0;
						$objTMDInput["lote"]						= $lote;
						$objTMDInput["expirationDate"]				= $vencimiento == "" ? NULL :  $vencimiento;

						$objTMDInput["inventoryWarehouseSourceID"]	= $objTMInput["sourceWarehouseID"];
						$objTMDInput["inventoryWarehouseTargetID"]	= $objTMInput["targetWarehouseID"];

						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMDInput);
					}
				}


				//Actualizar la transaccion  de salida por transferencia
				//
				/////////////////////////////////////////////////////
				/////////////////////////////////////////////////////
				$objTMUpdate["reference1"]	= $objTMInput["transactionID"];
				$objTMUpdate["reference2"]	= $transactionMasterIDInput;
				$objTMUpdate["reference3"]	= $objTMInput["transactionNumber"];
				$this->Transaction_Master_Model->update_app_posme($objTM->companyID, $objTM->transactionID, $transactionMasterID, $objTMUpdate);
			}


			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
				$this->response->redirect(base_url() . "/" . 'app_inventory_transferoutput/edit/companyID/' . $companyID . "/transactionID/" . $transactionID . "/transactionMasterID/" . $transactionMasterID);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $this->db->_error_message());
				$this->response->redirect(base_url() . "/" . 'app_inventory_transferoutput/add');
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
		$mode = helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);

		//AUTENTICADO
		if (!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();

		//Validar Formulario						
		$this->validation->setRule("txtWarehouseSourceID", "Bodega Origen", "required");
		$this->validation->setRule("txtWarehouseTargetID", "Bodega Destino", "required");
		$this->validation->setRule("txtStatusID", "Estado", "required");
		$this->validation->setRule("txtTransactionOn", "Fecha", "required");

		//Validar Formulario
		if (!$this->validation->withRequest($this->request)->run()) {
			$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
			$this->core_web_notification->set_message(true, $stringValidation);
			$this->response->redirect(base_url() . "/" . 'app_inventory_transferouput/add');
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
			$this->response->redirect(base_url() . "/" . 'app_inventory_transferouput/add');
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
				$this->response->redirect(base_url() . "/" . 'app_inventory_transferoutput/add');
			}

			//Obtener el Registro			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID, $transactionID, $transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID, $transactionID, $transactionMasterID);
			$datView["objListWarehouse"]			= $this->Userwarehouse_Model->getRowByUserID($companyID, $userID);
			$datView["objListWarehouseAll"]			= $this->Warehouse_Model->getByCompany($companyID);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($datView["objTM"]->companyID, $datView["objTM"]->createdAt, $datView["objTM"]->createdBy);
			$datView["objEmployee"]					= $this->Employee_Model->get_rowByEntityID($companyID, $datView["objUser"]->employeeID);
			$datView["objEntity"]					= $this->Entity_Model->get_rowByEntity($companyID, $datView["objUser"]->employeeID);
			$datView["objNatural"]					= $datView["objEmployee"] != NULL ? $this->Natural_Model->get_rowByPK($companyID, $datView["objEmployee"]->branchID, $datView["objEmployee"]->entityID) : NULL;
			$datView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_transferoutput", "statusID", $datView["objTM"]->statusID, $companyID, $branchID, $roleID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn), "Y-m-d");
			$datView["userID"]						= $datView["objUser"]->userID;
			$datView["objListTransactionCausal"] 	= $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);

			//Obtener Empleado Envia
			$datView["objEmployeeNaturalSource"]	=  $this->Natural_Model->get_rowByPK($datView["objTM"]->companyID, $datView["objTM"]->branchID, $datView["objTM"]->entityID);


			//Obtener Empleado Recibe
			$datView["objEmployeeNaturalTarget"]	= $this->Natural_Model->get_rowByPK($datView["objTM"]->companyID, $datView["objTM"]->branchID, $datView["objTM"]->entityIDSecondary);


			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$objComponentItem)
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$datView["componentItemID"] 			= $objComponentItem->componentID;


			$objComponentEmployee					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentEmployee)
				throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

			$objComponentEntity						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");
			if (!$objComponentEntity)
				throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");



			$datView["objComponentEmployee"]  			= $objComponentEmployee;
			$datView["objComponentEntity"]  			= $objComponentEntity;
			$datView["company"]							= $dataSession["company"];
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVOICE_CANTIDAD_ITEM");
			$datView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;

			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_transferoutput/edit_head', $datView); //--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_transferoutput/edit_body', $datView); //--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_transferoutput/edit_script', $datView); //--finview
			$dataSession["footer"]			= "";
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
			$dataSession		= $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

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
			$dataView["objListWarehouseAll"]	= $this->Warehouse_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_transferoutput", "statusID", $companyID, $branchID, $roleID);

			//Obtener el componente de Item
			$componentTranItem					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (!$componentTranItem)
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el componente de Item
			$componentTransfer					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferoutput");
			if (!$componentTransfer)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_transferoutput' NO EXISTE...");


			$objComponentEmployee				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if (!$objComponentEmployee)
				throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");
			if (!$objComponentEntity)
				throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");


			
			$transactionID 								= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_transferoutput", 0);
			$dataView["componentTranItemID"] 			= $componentTranItem->componentID;
			$dataView["userID"] 						= $userID;
			$dataView["objUser"]	 					= $this->User_Model->get_rowByPK($companyID, $branchID, $userID);
			$dataView["objEntity"]						= $this->Entity_Model->get_rowByEntity($companyID, $dataView["objUser"]->employeeID);
			$dataView["objListTransactionCausal"]		= $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
			$dataView["objComponentEmployee"]  			= $objComponentEmployee;
			$dataView["objComponentEntity"]  			= $objComponentEntity;
			$dataView["company"]						= $dataSession["company"];
			$dataView["componentTransfer"]				= $componentTransfer;			
			
			
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;
			//Renderizar Resultado 
			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				= $this->core_web_notification->get_message();
			$dataSession["head"]				= /*--inicio view*/ view('app_inventory_transferoutput/news_head', $dataView); //--finview
			$dataSession["body"]				= /*--inicio view*/ view('app_inventory_transferoutput/news_body', $dataView); //--finview
			$dataSession["script"]				= /*--inicio view*/ view('app_inventory_transferoutput/news_script', $dataView); //--finview
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

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ACCESS_FUNCTION);
			}
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_transferoutput");
			if (!$objComponent)
				throw new \Exception("EL COMPONENTE 'tb_transaction_master_transferoutput' NO EXISTE...");

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
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_transferoutput/list_head'); //--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_transferoutput/list_footer'); //--finview
			$dataSession["body"]			= $dataViewRender;
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_transferoutput/list_script'); //--finview
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

			return $resultView;
		}
	}
	function add_masinformacion($fnCallback = "", $itemID = "", $transactionMasterDetailID = "", $positionID = "", $lote = "", $vencimiento = "")
	{

		$fnCallback 				= helper_SegmentsByIndex($this->uri->getSegments(), 1, $fnCallback);
		$itemID 					= helper_SegmentsByIndex($this->uri->getSegments(), 2, $itemID);
		$transactionMasterDetailID 	= helper_SegmentsByIndex($this->uri->getSegments(), 3, $transactionMasterDetailID);
		$positionID 				= helper_SegmentsByIndex($this->uri->getSegments(), 4, $positionID);
		$lote 						= helper_SegmentsByIndex($this->uri->getSegments(), 5, $lote);
		$vencimiento 				= helper_SegmentsByIndex($this->uri->getSegments(), 6, $vencimiento);

		//AUTENTICACION
		if (!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();

		//PERMISO SOBRE LA FUNCION
		if (APP_NEED_AUTHENTICATION == true) {
			$permited = false;
			$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

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
