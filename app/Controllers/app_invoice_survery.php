<?php
//posme:2023-02-27
namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use CodeIgniter\Database\Exceptions\DatabaseException;

class app_invoice_survery extends _BaseController {


	
	function index(){	
		try{
			
			//Ininicializar Cache
			//$cache 				= Services::cache();
			//$this->cachePage( TIME_CACHE_APP );	
			
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_survery");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_survery' NO EXISTE...");
			
			$key				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"key");//--finuri			
			$dataview["key"]	= $key;
			
			$companyID					= APP_COMPANY;
			$objListPrice 				= $this->List_Price_Model->getListPriceToApply($companyID);
			$dataview["objListItem"]	= $this->Item_Model->get_rowByItemReference1($objListPrice->listPriceID,$key);
			
			//Obtener todos los prodcutos con el key
			$dataSession["head"]								= /*--inicio view*/ view('app_invoice_survery/index_head',$dataview);//--finview
			$dataSession["footer"]								= /*--inicio view*/ view('app_invoice_survery/index_footer',$dataview);//--finview
			$dataSession["body"]								= /*--inicio view*/ view('app_invoice_survery/index_body',$dataview);//--finview
			$dataSession["script"]								= /*--inicio view*/ view('app_invoice_survery/index_script',$dataview);//--finview
			return view("app_invoice_survery/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex)
		{
			
		    $data["session"]   = null;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);    
		    $resultView        = view("core_template/email_error_general",$data);
		    return $resultView;
			
		}
	}	
	
	function insertElement(){
	
		try
		{
			
			//Obtener el key;
			$key					= $this->request->getPost("key");
			
			
			//Obtener datos
			$name			= $this->request->getPost("name");
			$phone			= $this->request->getPost("phone");
			$direccion 		= $this->request->getPost("address");
			$listItem		= $this->request->getPost("itemID");
			$listQuantity	= $this->request->getPost("quantity");
			$listPrice		= $this->request->getPost("price");
			
			
			//Buscar el colaborador
			$objColaborador	= $this->Employee_Model->get_rowByItemReference1($key);
			
			//Buscar el cliente
			$objCustomer	= $this->Customer_Model->get_rowByItemReference1($phone,$key);
			
			
			//Obtener el login
			$objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
			$objCompany 				= $objUser["company"];
			$dataSession				= null;
			$dataSession["key"]			= $key;
			$dataSession['user'] 		= $objUser["user"];
            $dataSession['company'] 	= $objCompany;
            $dataSession['role'] 		= $objUser["role"];
			$companyID 					= APP_COMPANY;
			$branchID 					= APP_BRANCH;
			$roleID 					= $dataSession['role']->roleID;
			
			//Ingresar al cliente
			if(!$objCustomer)
			{
				$cus					= new \stdClass();
				$cus->companyID 		= APP_COMPANY;
                $cus->branchID 			= APP_BRANCH;
                $cus->entityID 			= 1; 
				$cus->location 			= $direccion;
				$cus->phoneNumber 		= $phone; 
				$cus->phone		 		= $phone; 
				$cus->firstName 		= $name; 
				$cus->lastName 			= $name; 
				$cus->identification 	= $phone;
				$cus->reference1 		= $key;
				$cus->statusID 			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;				
				$controller 			= new app_cxc_customer();
                $controller->initController($this->request, $this->response, $this->logger);				
				$controller->insertElementMobile($dataSession,$cus);
			}
			
			
			
			//Ingresar el transacion master
			$objComponentShare = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_survery");
            if (! $objComponentShare) {
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_survery' NO EXISTE...");
            }
			
			$objComponentItem = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if (! $objComponentItem) {
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
            }
			
			
			$objCustomer	= $this->Customer_Model->get_rowByItemReference1($phone,$key);
			$transactionID 	= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_survery", 0);
			$objT          	= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID, $transactionID);
			
			$objTM["companyID"]           = $companyID;
            $objTM["transactionID"]       = $transactionID;
            $objTM["branchID"]            = $branchID;
            $objTM["transactionNumber"]   = $this->core_web_counter->goNextNumber($dataSession["user"]->companyID, $dataSession["user"]->branchID, "tb_transaction_master_survery", 0);
            $objTM["transactionCausalID"] = $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID, $transactionID);
            $objTM["entityID"] 			  = $objCustomer[0]->entityID;
            $objTM["transactionOn"]     = date("Y-m-d H:m:s");
            $objTM["statusIDChangeOn"]  = date("Y-m-d H:m:s");
            $objTM["componentID"]       = $objComponentShare->componentID;
            $objTM["note"]              = "";
            $objTM["sign"]              = $objT->signInventory;
            $objTM["currencyID"]        = $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;	
            $objTM["currencyID2"]       = $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
            $objTM["exchangeRate"]      = $this->core_web_currency->getRatio($dataSession["user"]->companyID, date("Y-m-d"), 1, $objTM["currencyID2"], $objTM["currencyID"]);
            $objTM["reference1"]        = "";
            $objTM["reference2"]        = "";
            $objTM["reference3"]        = "";
            $objTM["reference4"]        = '';
            $objTM["statusID"]          = $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_survery", "statusID", $companyID, $branchID, $roleID)[0]->workflowStageID;
            $objTM["amount"]            = 0;
            $objTM["isApplied"]         = 0;
            $objTM["journalEntryID"]    = 0;
            $objTM["classID"]           = 0;
            $objTM["areaID"]            = 0;
            $objTM["priorityID"]        = 0;
            $objTM["sourceWarehouseID"] = null;
            $objTM["targetWarehouseID"] = null;
            $objTM["isActive"]          = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            $db = db_connect();
            $db->transException(true)->transStart();
            $transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			
			//Crear la Carpeta para almacenar los Archivos del Documento
            $pathDocument = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentShare->componentID . "/component_item_" . $transactionMasterID;
            if (! file_exists($pathDocument)) {
                mkdir($pathDocument, 0700, true);
            }
			
			
			//Ingresar detalle
			$total = 0;
			if ($listItem) {

	
				foreach($listItem  as $key => $value)
				{
					
					$itemID				 = $listItem[$key];
					$quantity			 = $listQuantity[$key];
					$price			 	 = $listPrice[$key];
					
					if($quantity <= 0)
						continue;
					
					$objTMD                        = null;
					$objTMD["companyID"]           = $objTM["companyID"];
					$objTMD["transactionID"]       = $objTM["transactionID"];
					$objTMD["transactionMasterID"] = $transactionMasterID;
					$objTMD["componentID"]         = $objComponentItem->componentID;
					$objTMD["componentItemID"]     = $itemID;
					$objTMD["quantity"]            = $quantity;
					$objTMD["unitaryCost"]         = 0;
					$objTMD["cost"]                = 0;

					$objTMD["unitaryPrice"]  = $price;
					$objTMD["unitaryAmount"] = $price;
					$objTMD["amount"]        = $price * $quantity;
					$objTMD["discount"]      = 0;
					$objTMD["promotionID"]   = 0;

					$objTMD["reference1"]                 = 0;
					$objTMD["reference2"]                 = 0;
					$objTMD["reference3"]                 = 0;
					$objTMD["catalogStatusID"]            = 0;
					$objTMD["inventoryStatusID"]          = 0;
					$objTMD["isActive"]                   = 1;
					$objTMD["quantityStock"]              = 0;
					$objTMD["quantiryStockInTraffic"]     = 0;
					$objTMD["quantityStockUnaswared"]     = 0;
					$objTMD["remaingStock"]               = 0;
					$objTMD["expirationDate"]             = null;
					$objTMD["inventoryWarehouseSourceID"] = null;
					$objTMD["inventoryWarehouseTargetID"] = null;
					$total 								  = $total + ($price * $quantity);
					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					
					
				}
            }
			
			
			$objTMNew["amount"] = $total;
            $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);

			
			
			if ($db->transStatus() !== false) {
				
				
				////Mandar mensjes por whatapp al cliente
				//$mensajeCliente		= "Su pedido esta siendo procesado :  [[URL]]";
				//$this->core_web_whatsap->sendMessageByLiveconnect($companyID, replaceSimbol($mensajeCliente), clearNumero($objCustomer[0]->phoneNumber));
				//
				////Mandar mensaje por whatapp al propietario
				//$mensajeColaborador	= "Hay una nueva orden de trabajo:  [[URL]]";
				//$this->core_web_whatsap->sendMessageByLiveconnect($companyID, replaceSimbol($mensajeColaborador), clearNumero($objColaborador[0]->phoneNumber));
				
				
                $db->transCommit();
				$dataSession["error"] 				= false;
				$dataSession["transactionNumber"] 	= $objTM["transactionNumber"];				
				$dataSession["message"] 			= getBahavioDB($dataSession["key"], 'app_invoice_survery', 'confirmacion', "Su pedido ya esta enproceso, le mantendremos informado.");
				return view("app_invoice_survery/result_body",$dataSession);//--finview-r	
                
            } else {
                $db->transRollback();
				$dataSession["error"] 				= true;		
				$dataSession["transactionNumber"] 	= "404";							
				$dataSession["message"] 			= "No fue posible procesar su orden.";
				return view("app_invoice_survery/result_body",$dataSession);//--finview-r	
            }
			
			
			
			
		}
		catch(\Exception $ex)
		{	
			
			$data["session"]   = null;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    echo $resultView;
		}		
		
	}

    

	
	function viewRegisterFormatoPaginaNormal80mmOpcion1(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 				= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);						
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);			
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);			
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					"C$ ".sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterSurvery(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
			    $objParameterRuc /*ruc*/
			);
			
			$this->dompdf->loadHTML($html);
			
			
			//1cm = 29.34666puntos
			//a4: 210 ancho x 297
			//a4: 21cm x 29.7cm
			//a4: 595.28puntos x 841.59puntos
			
			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->setPaper(array(0,0,234.76,6000));
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload		= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload		= $objParameterShowLinkDownload->value;
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_131/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_131/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_131/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar		
				$timestamp 	= date("YmdHis") . "0"; // Resultado: 202505261134000
				$filename 	= "posme_" . $timestamp . ".pdf";							
				$this->dompdf->stream($filename, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
		
	
}
?>