<?php
//posme:2023-02-27
namespace App\Controllers;

use Exception;
class app_inventory_inputunpost extends _BaseController {
	
    
	function viewPrinterDirectCompra58mm()
	{
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";			
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["useMobile"]						= $dataSession["user"]->useMobile;
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58Compra($dataView);
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
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}	
		
	}
	function viewRegisterFormato80mm(){
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
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);	
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);			
			//Get Documento				
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objProvider"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objLegal"]					= $datView["objProvider"] != NULL ? $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID) : NULL;
			$datView["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$datView["objTM"]->targetWarehouseID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= null;
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= null;
			$datView["objNatural"]					= null;
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_inputunpost","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:auto",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'3',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>1
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:left;width:50px",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:right;width:auto",
			    "colspan_row_data"		=>'2',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;width:90px",
			    "colspan"=>'1',
			    "prefix"=>$datView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;width:90px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>$datView["objCurrency"]->simbol,
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT.', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
			        $detail_->itemName,  
			        sprintf("%.2f",round($detail_->quantity,2)), 
			        sprintf("%.2f",round($detail_->amount,2))
			        
			    );
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterInputUnpost(
			    "COMPRA",
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
				$datView["objStage"][0]->display /*estado*/
			);
			$this->dompdf->loadHTML($html);
			
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);
				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download compra</a>
				"; 				

			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true ]);
			}
			
			
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
			
		    return $resultView;		}
	}
	function viewRegisterFormatoA4(){
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
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);	
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);			
			//Get Documento				
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objProvider"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objLegal"]					= $datView["objProvider"] != NULL ? $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID) : NULL;
			$datView["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$datView["objTM"]->targetWarehouseID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= null;
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= null;
			$datView["objNatural"]					= null;
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_inputunpost","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:auto",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'3',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>1
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:left;width:50px",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:right;width:auto",
			    "colspan_row_data"		=>'2',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;width:90px",
			    "colspan"=>'1',
			    "prefix"=>$datView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;width:90px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>$datView["objCurrency"]->simbol,
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT.', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
			        $detail_->itemName,  
			        sprintf("%.2f",round($detail_->quantity,2)), 
			        sprintf("%.2f",round($detail_->amount,2))
			        
			    );
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterInputUnpost(
			    "COMPRA",
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
				$datView["objStage"][0]->display /*estado*/
			);
			$this->dompdf->loadHTML($html);
			
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);
				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download compra</a>
				"; 				

			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true ]);
			}
			
			
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
			
		    return $resultView;		}
	}
	
	function viewRegisterFormatoA4Globalpro(){
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
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			$employeeID					= $dataSession["user"]->employeeID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);	
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);			
			//Get Documento				
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objProvider"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objLegal"]					= $datView["objProvider"] != NULL ? $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID) : NULL;
			$datView["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$datView["objTM"]->targetWarehouseID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_inputunpost","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$employeeID);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$employeeID);
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterInputUnpostGlobalPro(
			    "COMPRA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
				$datView["objTMD"],
			    $objParameterTelefono,
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				""
			);
			$this->dompdf->loadHTML($html);
			
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);
				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download compra</a>
				"; 				

			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true ]);
			}
			
			
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
			
		    return $resultView;		}
	}
	
	function viewRegisterFormatoA4GlobalproOnlyQuantity(){
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
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			$employeeID					= $dataSession["user"]->employeeID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);	
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);			
			//Get Documento				
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objProvider"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objLegal"]					= $datView["objProvider"] != NULL ? $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID) : NULL;
			$datView["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,$datView["objTM"]->targetWarehouseID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_inputunpost","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$employeeID);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$employeeID);
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterInputUnpostGlobalProOnlyQuantity(
			    "COMPRA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
				$datView["objTMD"],
			    $objParameterTelefono,
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				""
			);
			$this->dompdf->loadHTML($html);
			
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);
				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_56/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download compra</a>
				"; 				

			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true ]);
			}
			
			
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
			
		    return $resultView;		}
	}
	
	function searchTransactionMaster(){
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
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			$transactionNumber 	= /*inicio get post*/ $this->request->getPost("transactionNumber");
			
			
			if(!$transactionNumber){
					throw new \Exception(NOT_PARAMETER);			
			} 			
			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID,$transactionNumber);	
			
			if(!$objTM)
			throw new \Exception("NO SE ENCONTRO EL DOCUMENTO");	
			
			
			
			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
			));//--finjson
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}
	function delete(){
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"delete",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
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
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
					throw new \Exception(NOT_PARAMETER);								 
			} 
			
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
			$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
		
	}
	
	function insertElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_INSERT);	
			}
			
			
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			$companyID 								= $dataSession["user"]->companyID;
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_inputunpost",0);			
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);			
			$objListTypePreice						= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;			
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_inputunpost",0);
			$objTM["transactionCausalID"] 			= /*inicio get post*/ $this->request->getPost("txtCausalID");
			$objTM["entityID"]						= /*inicio get post*/ $this->request->getPost("txtProviderID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponent->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtDescription");//--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");//--fin peticion get o post
			$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterIDOrdenCompra");//--fin peticion get o post
			$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseID");//--fin peticion get o post
			$objTM["tax1"]							= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtIva"));
			$objTM["tax2"]							= 0;
			$objTM["tax3"]							= 0;
			$objTM["tax4"]							= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$objTM["subAmount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtSubTotal"));
			$objTM["discount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtDiscount"));
			$objTM["amount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtTotal"));
			$objTM["isActive"]						= 1;
			$objTM["isTemplate"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtIsTemplate")) ? "0" : /*inicio get post*/ $this->request->getPost("txtIsTemplate") ;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$path_ = PATH_FILE_OF_APP."/company_".$companyID."/component_56/component_item_".$transactionMasterID;						
			if(!file_exists ($path_)){
				mkdir($path_, 0755);
				chmod($path_, 0755);
			}
			
			
			//Generar un archivo template de ejemplo
			date_default_timezone_set(APP_TIMEZONE);
			$objParameterCharacterSplite	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
			$characterSplie					= $objParameterCharacterSplite->value; 			
			$date 				= date("Y_m_d_H_i_s");			
			$pathTemplate 		= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$transactionMasterID;
			$pathTemplate 		= $pathTemplate.'/ejemplo_'.$date.'.csv';
			$fppathTemplate 	= fopen($pathTemplate, 'w');
			$fieldTemplate 		= ["Codigo","Nombre","Cantidad","Costo","Precio","Lote","Vencimiento"];
			fputcsv($fppathTemplate, $fieldTemplate,$characterSplie);
			fclose($fppathTemplate);
					
					
			
			
			//Recorrer la lista del detalle del documento
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");	
			$arrayListCost	 							= /*inicio get post*/ $this->request->getPost("txtDetailCost");			
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");			
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");					
			$arrayPrice 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice");			
			$arrayPrice2 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice2");			
			$arrayPrice3 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice3");			
			$arrayReference4TransactionMasterDetail 	= /*inicio get post*/ $this->request->getPost("txtReference4TransactionMasterDetail");
			
			
			if(!empty($arrayListItemID))
			{
				foreach($arrayListItemID as $key => $value)
				{	
					$itemID 								= $value;
					$objItem 								= $this->Item_Model->get_rowByPK($objTM["companyID"],$value);
					$quantity 								= helper_StringToNumber(ltrim(rtrim($arrayListQuantity[$key])));
					$cost 									= helper_StringToNumber(ltrim(rtrim($arrayListCost[$key])));
					$barCodeExtende 						= $arrayReference4TransactionMasterDetail[$key];
					
					$lote 									= $arrayListLote[$key];
					$vencimiento							= $arrayListVencimiento[$key];
					$unitaryPrice 							= $arrayPrice[$key];
					$unitaryPrice2 							= $arrayPrice2[$key];
					$unitaryPrice3 							= $arrayPrice3[$key];
					
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					$objTMD["quantity"] 					= $quantity;
					$objTMD["unitaryCost"]					= $cost;
					$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];
					
					$objTMD["unitaryAmount"]				= ltrim(rtrim($unitaryPrice));
					$objTMD["amount"] 						= 0;										
					$objTMD["discount"]						= 0;
					$objTMD["unitaryPrice"]					= ltrim(rtrim($unitaryPrice));
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["lote"]							= $lote;
					$objTMD["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
					$objTMD["reference3"]					= ltrim(rtrim($unitaryPrice2))."|".ltrim(rtrim($unitaryPrice3));
					$objTMD["reference4"]					= str_replace(",,",",", str_replace(PHP_EOL,",",  ltrim(rtrim($barCodeExtende)) ));
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
			else
			{
				$transactionMasterIDThemplate = $objTM["reference4"];
				if($transactionMasterIDThemplate > 0 )
				{
						$objTransactionMasterDetailTemplate 	= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterIDThemplate);
						if($objTransactionMasterDetailTemplate)
						{
						   foreach($objTransactionMasterDetailTemplate as  $key => $objTmdTemplate)
						   {
							    
								$objTMD["companyID"] 					= $objTM["companyID"];
								$objTMD["transactionID"] 				= $objTM["transactionID"];
								$objTMD["transactionMasterID"] 			= $transactionMasterID;
								$objTMD["componentID"]					= $objComponentItem->componentID;
								$objTMD["componentItemID"] 				= $objTmdTemplate->componentItemID;
								$objTMD["quantity"] 					= $objTmdTemplate->quantity;
								$objTMD["unitaryCost"]					= $objTmdTemplate->unitaryCost;
								$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];
								
								$objTMD["unitaryAmount"]				= $objTmdTemplate->unitaryAmount;
								$objTMD["amount"] 						= 0;										
								$objTMD["discount"]						= 0;
								$objTMD["unitaryPrice"]					= $objTmdTemplate->unitaryPrice;
								$objTMD["promotionID"] 					= 0;
								
								$objTMD["lote"]							= $objTmdTemplate->lote;
								$objTMD["expirationDate"]				= $objTmdTemplate->expirationDate;
								$objTMD["reference3"]					= $objTmdTemplate->reference3;	
								$objTMD["reference4"]					= "";//la referencia 4 cuando biene de una plantilla, se se escribe por que no hay que extender ningun codigo de barra
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
				}
			}
			
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();			
				$errorCode 		= $db->error()["code"];
				$errorMessage 	= $db->error()["message"];								
				$this->core_web_notification->set_message(true,$errorMessage );
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/add');	
			}
			
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

	function insertElementMobile($dataSession, $objItems){
		$validate = false;
		foreach($objItems as $key => $value)
		{	
			$cantidadFinal = $value->cantidadEntradas-$value->cantidadSalidas;
			$validate = $cantidadFinal>0;
			if($validate){
				break;
			}
		}
		if($validate){
			try{
				$companyID 						= $dataSession["user"]->companyID;
				$branchID 						= $dataSession["user"]->branchID;
				$roleID 						= $dataSession["role"]->roleID;
				$objListComanyParameter			= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
		
				//Obtener el Componente de Transacciones Other Input to Inventory
				$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
				if(is_null($objComponent))
					throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
				
				$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
				if(is_null($objComponentItem))
					throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
				
				$objParameterWarehouseDefault		= $this->core_web_parameter->getParameter("INVENTORY_ITEM_WAREHOUSE_DEFAULT",$companyID);
				$warehouseDefault 					= $objParameterWarehouseDefault->value;
				//buscar el id de la bodega por number
				$warehouseID 						= $this->Warehouse_Model->getByCode($companyID, $warehouseDefault)->warehouseID;
				$objListWarehouseTipoDespacho		= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$dataSession["user"]->userID);
				if(!$objListWarehouseTipoDespacho)
				{
					log_message("error","El usuario no tiene una bodega tipo despacho configurada");
					throw new \Exception("El usuario no tiene una bodega tipo despacho configurada");
				}
				
				$warehouseID						= $objListWarehouseTipoDespacho[0]->warehouseID;
				$objParameterProviderDefault		= $this->core_web_parameter->getParameter("CXP_PROVIDER_DEFAULT",$companyID);
				$objParameterProviderDefault 		= $objParameterProviderDefault->value;
				$providerDefault	 				= $this->Provider_Model->get_rowByProviderNumber($companyID,$objParameterProviderDefault);
				
				$transactionID 							= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_inputunpost",0);
				$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($companyID,$transactionID);
				$objTM["companyID"] 					= $companyID;
				$objTM["transactionID"] 				= $transactionID;			
				$objTM["branchID"]						= $branchID;			
				$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_inputunpost",0);
				$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($objTM["companyID"],$objTM["transactionID"]);
				$objTM["entityID"]						= $providerDefault->entityID;
				$objTM["transactionOn"]					= date('Y-m-d H:i:s');
				$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
				$objTM["componentID"] 					= $objComponent->componentID;
				$objTM["note"] 							= "";
				$objTM["sign"] 							= $objT->signInventory;
				$objTM["currencyID"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_CURRENCY_ID_DEFAULT")->value;
				$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
				$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
				$objTM["reference1"] 					= "";
				$objTM["reference2"] 					= "";
				$objTM["reference3"] 					= "";
				$objTM["reference4"] 					= "";
				$objTM["statusID"] 						= $this->core_web_workflow->getWorkflowStageApplyFirst("tb_transaction_master_inputunpost","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
				$objTM["amount"] 						= 0;
				$objTM["isApplied"] 					= 0;
				$objTM["journalEntryID"] 				= 0;
				$objTM["classID"] 						= NULL;
				$objTM["areaID"] 						= NULL;
				$objTM["sourceWarehouseID"]				= NULL;
				$objTM["targetWarehouseID"]				= $warehouseID;
				$objTM["tax1"]							= 0;
				$objTM["tax2"]							= 0;
				$objTM["tax3"]							= 0;
				$objTM["tax4"]							= 0;
				$objTM["subAmount"]						= 0;
				$objTM["discount"]						= 0;
				$objTM["amount"]						= 0;
				$objTM["isActive"]						= 1;
				$objTM["isTemplate"] 					= 0;
				$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
				
				$db=db_connect();
				$db->transStart();
				$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
		
				//Crear la Carpeta para almacenar los Archivos del Documento
				$path_ = PATH_FILE_OF_APP."/company_".$companyID."/component_56/component_item_".$transactionMasterID;						
				if(!file_exists ($path_)){
					mkdir($path_, 0755);
					chmod($path_, 0755);
				}
		
		
				if(count($objItems)>0){
					$amount=0;
					$subAmount=0;
					foreach($objItems as $key => $value)
					{	
						$cantidadFinal = $value->cantidadEntradas-$value->cantidadSalidas;
						if($cantidadFinal<=0){//entrada - salidas > 0
							continue;
						}
		
						$objItem 								= $this->Item_Model->get_rowByCodeBarra($companyID, $value->barCode); //buscar por codigo de barra
						$itemID 								= $objItem->itemID;
						$quantity 								= $cantidadFinal;
						$cost 									= $objItem->cost;
						$barCodeExtende 						= "";
						
						$lote 									= "";
						$vencimiento							= "";
						$unitaryPrice 							= $value->precioPublico;
						$unitaryPrice2 							= 0;
						$unitaryPrice3 							= 0;
						
						$objTMD["companyID"] 					= $companyID;
						$objTMD["transactionID"] 				= $transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentItem->componentID;
						$objTMD["componentItemID"] 				= $itemID;
						$objTMD["promotionID"] 					= 0;
						$objTMD["reference4"]					= str_replace(",,",",", str_replace(PHP_EOL,",",  ltrim(rtrim($barCodeExtende)) ));
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
		
						//Obtener lista de precio
						$objParameterPriceDefault				= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
						$listPriceID 							= $objParameterPriceDefault->value;
						$objItemInactive						= $this->Item_Model->get_rowByPKAndInactive($companyID,$itemID);
						
						//Actualizar tipo de precio 1 ---> 154 ---->PUBLICO
						if($unitaryPrice > 0){
							
							$typePriceID					= 154;
							$dataUpdatePrice["price"] 		= $unitaryPrice;
							$dataUpdatePrice["percentage"] 	= 
															$objItem->cost == 0 ? 
																($unitaryPrice / 100) : 
																(((100 * $unitaryPrice) / $objItem->cost) - 100);
							$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
						}
									
						$objTMD["discount"]						= 0;
						$objTMD["quantity"] 					= $quantity;
						$objTMD["unitaryCost"]					= $cost;
						$objTMD["unitaryPrice"]					= $unitaryPrice;
						$objTMD["amount"] 						= $cost * $quantity;
						$objTMD["reference3"]					= $unitaryPrice2."|".$unitaryPrice3;
						$objTMD["reference4"]					= str_replace(",,",",", str_replace(PHP_EOL,",",  ltrim(rtrim($barCodeExtende)) ));
						$objTMD["unitaryAmount"]				= $unitaryPrice;
						$objTMD["cost"] 						= $quantity * $cost;
						$objTMD["lote"]							= $lote;
						$objTMD["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
						$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
						$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
		
						$amount 								+= $objTMD["cost"];
						$subAmount								= $amount;
					}
		
					$objTM["amount"]	=$amount;
					$objTM["subAmount"]	=$subAmount;
					$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
		
					//Aplicar el Documento?
					if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTM["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) ){
						//Ingresar en Kardex.
						$this->core_web_inventory->calculateKardexNewInput($companyID,$transactionID,$transactionMasterID);			
					
						//Crear Conceptos.
						$this->core_web_concept->inputunpost($companyID,$transactionID,$transactionMasterID);
					}
		
					if($db->transStatus() !== false){
						$db->transCommit();}
					else{
						$db->transRollback();
					}
				}
			}
			catch(\Exception $ex){
				return $this->response->setJSON(array(
					'error' => true,
					'message' => 'Linea: ' . $ex->getLine() . " - Error:" . $ex->getMessage()
				));//--finjson
			}	
		}		
	}

	function updateElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_EDIT);	
			}
						 		
			
			//Obtener el Componente de Transacciones de Solicitud General
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$transactionNumber						= /*inicio get post*/ $this->request->getPost("txtTransactionNumber");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			//Obtener lista de precio
			$objParameterPriceDefault					= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 								= $objParameterPriceDefault->value;
			$objTipePrice 								= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			//Actualizar Maestro
			$objTMNew["transactionCausalID"] 		= /*inicio get post*/ $this->request->getPost("txtCausalID");
			$objTMNew["transactionOn"]				= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTMNew["statusIDChangeOn"]			= date("Y-m-d H:m:s");			
			$objTMNew["note"] 						= /*inicio get post*/ $this->request->getPost("txtDescription");//--fin peticion get o post			
			$objTMNew["entityID"]					= /*inicio get post*/ $this->request->getPost("txtProviderID");
			$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
			$objTMNew["reference1"] 				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objTMNew["reference2"] 				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objTMNew["reference3"] 				= /*inicio get post*/ $this->request->getPost("txtReference3");//--fin peticion get o post
			$objTMNew["reference4"] 				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterIDOrdenCompra");//--fin peticion get o post
			$objTMNew["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
			$objTMNew["currencyID2"]				= $this->core_web_currency->getTarget($companyID,$objTMNew["currencyID"]);
			$objTMNew["exchangeRate"]				= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTMNew["currencyID2"],$objTMNew["currencyID"]);
			$objTMNew["sourceWarehouseID"]			= NULL;
			$objTMNew["targetWarehouseID"]			= /*inicio get post*/ $this->request->getPost("txtWarehouseID");//--fin peticion get o post
			$objTMNew["tax1"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtIva"));
			$objTMNew["tax2"]						= 0;
			$objTMNew["tax3"]						= 0;
			$objTMNew["tax4"]						= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$objTMNew["subAmount"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtSubTotal"));
			$objTMNew["discount"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtDiscount"));
			$objTMNew["amount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtTotal"));
			$objTMNew["isTemplate"] 				= is_null(/*inicio get post*/ $this->request->getPost("txtIsTemplate")) ? "0" : /*inicio get post*/ $this->request->getPost("txtIsTemplate");
			
			/*****************************
			///Las transacciones no se usan en esta pantalla con el objetivo de qeu se puedan importar muchos item
			///
			///
			*****************************/
			//$db=db_connect();
			//$db->transStart();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			
			//Actualizar Detalle
			$listTMD_ID 								= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");			
			$arrayListCost	 							= /*inicio get post*/ $this->request->getPost("txtDetailCost");			
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");			
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");			
			$arrayPrice 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice");	
			$arrayPrice2 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice2");			
			$arrayPrice3 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice3");						
			$arrayReference4TransactionMasterDetail 	= /*inicio get post*/ $this->request->getPost("txtReference4TransactionMasterDetail");
			$archivoCSV 								= /*inicio get post*/ $this->request->getPost("txtFileImport");			
			
			
			
			
			if($archivoCSV != ".csv")
			{
				$this->updateElementDetailByFile(
					$dataSession,
					$branchID,
					$roleID,
					$objTMNew,
					$companyID,
					$transactionID,
					$transactionMasterID,
					$listTMD_ID,
					$arrayListItemID,
					$arrayListQuantity,
					$arrayListCost,
					$arrayListLote,
					$arrayListVencimiento,
					$arrayPrice,
					$arrayPrice2,
					$arrayPrice3,
					$arrayReference4TransactionMasterDetail,
					$archivoCSV
				);
				
			}
			else
			{
				$this->updateElementDetailByPost(
					$dataSession,
					$branchID,
					$roleID,
					$objTMNew,
					$companyID,
					$transactionID,
					$transactionMasterID,
					$listTMD_ID,
					$arrayListItemID,
					$arrayListQuantity,
					$arrayListCost,
					$arrayListLote,
					$arrayListVencimiento,
					$arrayPrice,
					$arrayPrice2,
					$arrayPrice3,
					$arrayReference4TransactionMasterDetail,
					$archivoCSV
				);
			}
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] ){
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewInput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->inputunpost($companyID,$transactionID,$transactionMasterID);

				//Si es al credito crear tabla de amortizacion
				$amountTotal 			= $objTMNew["amount"] - $objTMNew["discount"];
				$causalIDTypeCredit 	= explode(",", $parameterCausalTypeCredit->value);
				$exisCausalInCredit		= null;
				$exisCausalInCredit		= array_search($objTMNew["transactionCausalID"] ,$causalIDTypeCredit);

				//Validar si el causal existe y si es de tipo credito
				if(($exisCausalInCredit|| $exisCausalInCredit === 0)/*CREDITO*/ && $objTMNew["tax4"] != 0 /*NO INGRESADA*/&& !empty($objTMNew["tax4"]) /*VACIO*/)
				{
					//Crear documento del modulo
					$objCustomerCreditLine 								= $this->Customer_Credit_Line_Model->get_rowByPK($objTMNew["tax4"]);
					$objCustomerCreditDocument["companyID"] 			= $companyID;
					$objCustomerCreditDocument["entityID"] 				= $objCustomerCreditLine->entityID;
					$objCustomerCreditDocument["customerCreditLineID"] 	= $objCustomerCreditLine->customerCreditLineID;
					$objCustomerCreditDocument["documentNumber"] 		= $transactionNumber;
					$objCustomerCreditDocument["dateOn"] 				= $objTMNew["transactionOn"];
					$objCustomerCreditDocument["exchangeRate"] 			= $objTMNew["exchangeRate"];
					$objCustomerCreditDocument["interes"] 				= $objCustomerCreditLine->interestYear;
					$objCustomerCreditDocument["term"] 					= $objCustomerCreditLine->term;
					$objCustomerCreditDocument["amount"] 				= $amountTotal; 
					$objCustomerCreditDocument["balance"] 				= $amountTotal;
					$objCatalogItemDayExclude 							= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->dayExcluded);
					$objCustomerCreditDocument["currencyID"] 			= $objTMNew["currencyID"];					
					$objCustomerCreditDocument["statusID"] 				= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_document","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
					$objCustomerCreditDocument["reference1"] 			= $objTMNew["reference1"];
					$objCustomerCreditDocument["reference2"] 			= $objTMNew["reference2"];
					$objCustomerCreditDocument["reference3"] 			= $objTMNew["reference3"];
					$objCustomerCreditDocument["isActive"] 				= 1;
					$objCustomerCreditDocument["providerIDCredit"] 		= $objTMNew["entityID"];
					$objCustomerCreditDocument["periodPay"]				= $objCustomerCreditLine->periodPay;
					$objCustomerCreditDocument["typeAmortization"] 		= $objCustomerCreditLine->typeAmortization;					
					$customerCreditDocumentID 							= $this->Customer_Credit_Document_Model->insert_app_posme($objCustomerCreditDocument);

					$periodPay 								= $this->Catalog_Item_Model->get_rowByCatalogItemID( $objCustomerCreditLine->periodPay );
					$objCatalogItem_DiasNoCobrables 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES",$companyID);
					$objCatalogItem_DiasFeriados365 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_365",$companyID);
					$objCatalogItem_DiasFeriados366 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_366",$companyID);

					//Crear tabla de amortizacion
					$this->financial_amort->amort(
						$objCustomerCreditDocument["amount"], 				/*monto*/
						$objCustomerCreditDocument["interes"],				/*interes anual*/
						$objCustomerCreditDocument["term"],				/*numero de pagos*/	
						$periodPay->sequence,							/*frecuencia de pago en dia*/
						$objTMNew["transactionOn"], 						/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization, /*tipo de amortizacion*/
						$objCatalogItem_DiasNoCobrables,
						$objCatalogItem_DiasFeriados365,
						$objCatalogItem_DiasFeriados366,
						$objCatalogItemDayExclude,
						$dataSession["company"]->flavorID
					);

					$tableAmortization = $this->financial_amort->getTable();
					if($tableAmortization["detail"])
					{
						foreach($tableAmortization["detail"] as $key => $itemAmortization){
							$objCustomerAmoritizacion["customerCreditDocumentID"]	= $customerCreditDocumentID;
							$objCustomerAmoritizacion["balanceStart"]				= $itemAmortization["saldoInicial"];
							$objCustomerAmoritizacion["dateApply"]					= $itemAmortization["date"];
							$objCustomerAmoritizacion["interest"]					= $itemAmortization["interes"];
							$objCustomerAmoritizacion["capital"]					= $itemAmortization["principal"];
							$objCustomerAmoritizacion["share"]						= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["balanceEnd"]					= $itemAmortization["saldo"];
							$objCustomerAmoritizacion["remaining"]					= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["dayDelay"]					= 0;
							$objCustomerAmoritizacion["note"]						= '';
							$objCustomerAmoritizacion["statusID"]					= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_amoritization","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
							$objCustomerAmoritizacion["isActive"]					= 1;
							$objCustomerAmortizationID 								= $this->Customer_Credit_Amortization_Model->insert_app_posme($objCustomerAmoritizacion);
						}
					}

				}
			}
			
			
			/*****************************
			///Las transacciones no se usan en esta pantalla con el objetivo de qeu se puedan importar muchos item
			///
			///
			*****************************/			
			//if($db->transStatus() !== false){
			//	$db->transCommit();						
			//	$this->core_web_notification->set_message(false,SUCCESS);
			//	$this->response->redirect(base_url()."/".'app_inventory_inputunpost/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			//}
			//else{
			//	$db->transRollback();						
			//	$this->core_web_notification->set_message(true,$this->db->_error_message());
			//	$this->response->redirect(base_url()."/".'app_inventory_inputunpost/add');	
			//}
			$this->core_web_notification->set_message(false,SUCCESS);
			$this->response->redirect(base_url()."/".'app_inventory_inputunpost/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			
			
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
			
		    echo $resultView;
		}			
	}
	
	
	function updateElementDetailByPost(
		$dataSession,
		$branchID,
		$roleID,
		$objTMNew,
		$companyID,
		$transactionID,
		$transactionMasterID,
		$listTMD_ID,
		$arrayListItemID,
		$arrayListQuantity,
		$arrayListCost,
		$arrayListLote,
		$arrayListVencimiento,
		$arrayPrice,
		$arrayPrice2,
		$arrayPrice3,
		$arrayReference4TransactionMasterDetail,
		$archivoCSV
	)
	{
		$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTMD_ID);
		$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
		$listPriceID 				= $objParameterPriceDefault->value;
		
		$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
		if(!$objComponentItem)
		throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
	
		if(!empty($arrayListItemID)){
			foreach($arrayListItemID as $key => $value){
				$transactionMasterDetailID				= $listTMD_ID[$key];	
				$objItem 								= $this->Item_Model->get_rowByPK($companyID,$value);
				$objItemInactive						= $this->Item_Model->get_rowByPKAndInactive($companyID,$value);
				
				$itemID 								= $value;
				$quantity 								= helper_StringToNumber(ltrim(rtrim($arrayListQuantity[$key])));
				$cost 									= helper_StringToNumber(ltrim(rtrim($arrayListCost[$key])));
				$lote 									= $arrayListLote[$key];
				$vencimiento							= $arrayListVencimiento[$key];
				$unitaryPrice 							= ltrim(rtrim($arrayPrice[$key]));
				$unitaryPrice2 							= helper_RequestGetValue(ltrim(rtrim($arrayPrice2[$key])),0);
				$unitaryPrice3 							= helper_RequestGetValue(ltrim(rtrim($arrayPrice3[$key])),0);
				$barCodeExtende 						= $arrayReference4TransactionMasterDetail[$key];
				 
				if(!$objItem && $objItemInactive)
				{
					throw new \Exception("Revisar el producto :" .$objItemInactive->itemNumber. " bar code (".$objItemInactive->barCode.") revisar configuracion no se encuentra en sistema...");
				}
				
				//Actualizar Codigo de barra
				if($barCodeExtende != "")
				{
					if(strpos($objItem->barCode,$barCodeExtende) === false)
					{
						$dataNewItem 			= null;
						$dataNewItem["barCode"] = $objItem->barCode.",". str_replace(",,",",", str_replace(PHP_EOL,",",  ltrim(rtrim($barCodeExtende)) )) 	;
						$this->Item_Model->update_app_posme($objItem->companyID,$objItem->itemID,$dataNewItem);
					}
				}
				
				//Actualizar tipo de precio 1 ---> 154 ---->PUBLICO
				if($unitaryPrice > 0){
					
					$typePriceID					= 154;
					$dataUpdatePrice["price"] 		= $unitaryPrice;
					$dataUpdatePrice["percentage"] 	= 
													$objItem->cost == 0 ? 
														($unitaryPrice / 100) : 
														(((100 * $unitaryPrice) / $objItem->cost) - 100);
					$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
				}
				
				//Actualizar tipo de precio 2 ---> 155 ---->POR MAYOR
				if($unitaryPrice2 > 0){
					$typePriceID					= 155;
					$dataUpdatePrice["price"] 		= $unitaryPrice2;
					$dataUpdatePrice["percentage"] 	= 
													$objItem->cost == 0 ? 
														($unitaryPrice2 / 100) : 
														(((100 * $unitaryPrice2) / $objItem->cost) - 100);
					$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
				}
				
				//Actualizar tipo de precio 3 ---> 156 ---->CREDITO
				if($unitaryPrice3 > 0 ){							
					$typePriceID					= 156;
					$dataUpdatePrice["price"] 		= $unitaryPrice3;
					$dataUpdatePrice["percentage"] 	= 
													$objItem->cost == 0 ? 
														($unitaryPrice3 / 100) : 
														(((100 * $unitaryPrice3) / $objItem->cost) - 100);
					$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
				}
			
				//Ingrear al provedor si no existe. 
				$objProviderItemModel = $this->Provideritem_Model->getByPK($companyID,$itemID,$objTMNew["entityID"]);						
				if(!$objProviderItemModel){
					$objPIMNew["companyID"]	= $companyID;
					$objPIMNew["branchID"]	= $branchID;
					$objPIMNew["entityID"]	= $objTMNew["entityID"];
					$objPIMNew["itemID"]	= $itemID;
					$this->Provideritem_Model->insert_app_posme($objPIMNew);
				}
				
				
				//Nuevo Detalle
				if($transactionMasterDetailID == 0){						
					$objTMD 								= array();
					$objTMD["companyID"] 					= $companyID;
					$objTMD["transactionID"] 				= $transactionID;
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;//itemID
					$objTMD["quantity"] 					= $quantity;
					$objTMD["unitaryCost"]					= $cost;
					$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];
					
					$objTMD["unitaryAmount"]				= $unitaryPrice;
					$objTMD["amount"] 						= $objTMD["unitaryCost"] * $objTMD["quantity"];
					$objTMD["discount"]						= 0;
					$objTMD["unitaryPrice"]					= $unitaryPrice;
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["lote"]							= $lote;
					$objTMD["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
					$objTMD["reference3"]					= $unitaryPrice2."|".$unitaryPrice3;
					$objTMD["reference4"]					= str_replace(",,",",", str_replace(PHP_EOL,",",  ltrim(rtrim($barCodeExtende)) ));
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;							
					$objTMD["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];;						
					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					
				}
				//Editar Detalle
				else{
					$objTMD 									= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID);						
					$objTMDNew["quantity"] 						= $quantity;
					$objTMDNew["unitaryCost"]					= $cost;
					$objTMDNew["unitaryPrice"]					= $unitaryPrice;
					$objTMDNew["amount"] 						= $cost * $quantity;
					$objTMDNew["reference3"]					= $unitaryPrice2."|".$unitaryPrice3;
					$objTMDNew["reference4"]					= str_replace(",,",",", str_replace(PHP_EOL,",",  ltrim(rtrim($barCodeExtende)) ));
					
					
					$objTMDNew["unitaryAmount"]					= $unitaryPrice;
					$objTMDNew["cost"] 							= $objTMDNew["quantity"] * $objTMDNew["unitaryCost"];
					$objTMDNew["lote"]							= $lote;
					$objTMDNew["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
					$objTMDNew["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
					$objTMDNew["inventoryWarehouseTargetID"]	= $objTMNew["targetWarehouseID"];
					$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMDNew);						
				}
				
				
			}
		}
		
	}
	
	function updateElementDetailByFile(
		$dataSession,
		$branchID,
		$roleID,
		$objTMNew,
		$companyID,
		$transactionID,
		$transactionMasterID,
		$listTMD_ID,
		$arrayListItemID,
		$arrayListQuantity,
		$arrayListCost,
		$arrayListLote,
		$arrayListVencimiento,
		$arrayPrice,
		$arrayPrice2,
		$arrayPrice3,
		$arrayReference4TransactionMasterDetail,
		$archivoCSV
	)
	{
		$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);
				
		//Leer archivo
		$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
		if(!$objComponent)
		throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
	
		$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
		if(!$objComponentItem)
		throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
		
		$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$transactionMasterID;			
		$path 	= $path.'/'.$archivoCSV;
		
		if (!file_exists($path))
		throw new \Exception("NO EXISTE EL ARCHIVO PARA IMPORTAR LOS PRECIOS");
		
		$objParameter				= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
		$characterSplie 			= $objParameter->value;
		$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
		$listPriceID 				= $objParameterPriceDefault->value;
		
		$this->csvreader->separator = $characterSplie;
		$table 			= $this->csvreader->parse_file($path); 
		$fila 			= 0;
		if($table){
			
			//if (count($table) > 7000){	
			//	throw new \Exception("Archivo con mas de 200 registros");
			//}
			
			if (count($table) >= 1){	

				
				if(!array_key_exists("Codigo",$table[0])){
					throw new \Exception("Columna 'Codigo' no existe en el archivo .csv");
				}
				if(!array_key_exists("Nombre",$table[0])){
					throw new \Exception("Columna 'Nombre' no existe en el archivo .csv");
				}
				if(!array_key_exists("Cantidad",$table[0])){
					throw new \Exception("Columna 'Cantidad' no existe en el archivo .csv");
				}
				if(!array_key_exists("Costo",$table[0])){
					throw new \Exception("Columna 'Costo' no existe en el archivo .csv");
				}
				if(!array_key_exists("Precio",$table[0])){
					throw new \Exception("Columna 'Costo' no existe en el archivo .csv");
				}
				if(!array_key_exists("Lote",$table[0])){
					throw new \Exception("Columna 'Lote' no existe en el archivo .csv");
				}
				if(!array_key_exists("Vencimiento",$table[0])){
					throw new \Exception("Columna 'Vencimiento' no existe en el archivo .csv");
				}
			}
			
			
			foreach ($table as $row) 
			{	
				$fila++;
				
				if(count($row) == 0 )
					continue;
				
				$codigo 		= $row["Codigo"];
				$description 	= $row["Nombre"];
				$cantidad 		= ltrim(rtrim($row["Cantidad"]));
				$costo 			= ltrim(rtrim($row["Costo"]));			
				$lote 			= $row["Lote"];
				$vencimiento	= $row["Vencimiento"];
				$precio			= ltrim(rtrim($row["Precio"]));
				$objItem		= $this->Item_Model->get_rowByCode($companyID,$codigo);	
				
				if(!$objItem) {
					$objItem		= $this->Item_Model->get_rowByCodeBarra($companyID,$codigo);		
					
				}				
				
				
				//Agregar productos nuevos
				if(!$objItem) 
				{
					$controllerApi 				= new app_inventory_item();
					$controllerApi->initController($this->request, $this->response, $this->logger);									
					$objItemNewApi 					= [
								'txtCallback' 				=> 'fnCollback',
								'txtComando' 				=> 'false',
								'txtInventoryCategoryID'	=> $this->Itemcategory_Model->getByCompany($companyID)[0]->inventoryCategoryID,
								'txtName'					=> $description,
								'txtFamilyID'				=> $this->core_web_catalog->getCatalogAllItem("tb_item","familyID",$companyID)[0]->catalogItemID,
								'txtBarCode'				=> $codigo,
								'txtDescription'			=> $description,
								'txtUnitMeasureID'			=> $this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID)[0]->catalogItemID,
								'txtDisplayID'				=> $this->core_web_catalog->getCatalogAllItem("tb_item","displayID",$companyID)[0]->catalogItemID,
								'txtCapacity'				=> 1,
								'txtDisplayUnitMeasureID'	=> $this->core_web_catalog->getCatalogAllItem("tb_item","displayUnitMeasureID",$companyID)[0]->catalogItemID,
								'txtDefaultWarehouseID'		=> $objTMNew["targetWarehouseID"],
								'txtQuantityMax'			=> 1000,
								'txtQuantityMin'			=> 0,
								'txtReference1'				=> '-',
								'txtReference2'				=> '-',
								'txtReference3'				=> '-',
								'txtStatusID'				=> $this->core_web_workflow->getWorkflowInitStage("tb_item","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID,
								'txtIsPerishable'			=> 0,
								'txtIsServices'				=> 0,
								'txtIsInvoiceQuantityZero'	=> true,
								'txtIsInvoice'				=> true,
								'txtFactorBox'				=> 1,
								'txtFactorProgram'			=> 1,
								'txtCurrencyID'				=> $objTMNew["currencyID"],
								'txtQuantity'				=> 0,
								'txtCost'					=> 0,
								
								'txtDetailWarehouseID'		=> [$objTMNew["targetWarehouseID"]],
								'txtDetailQuantityMax'		=> [1000],
								'txtDetailQuantityMin'		=> [0],
								
								'txtDetailSkuCatalogItemID'	=> [$this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID)[0]->catalogItemID],
								'txtDetailSkuValue'			=> [1],
								
								'txtDetailTypePriceValue'	=> [0,0,0,0,0],
								'txtDetailTypeComisionValue'=> [0,0,0,0,0],
								'txtDetailTypePriceID'		=> [
																	$this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID)[0]->catalogItemID,
																	$this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID)[1]->catalogItemID,
																	$this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID)[2]->catalogItemID,
																	$this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID)[3]->catalogItemID,
																	$this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID)[4]->catalogItemID
															   ],
								'txtDetailListPriceID'		=> [
																	$this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID)->value,
																	$this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID)->value,
																	$this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID)->value,
																	$this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID)->value,
																	$this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID)->value
															   ],
								'txtRealStateEmail'			=> "",
								'txtRealStatePhone'			=> "",
								'txtRealStateLinkYoutube'	=> "",
								'txtRealStateLinkPaginaWeb'	=> "",
								'txtRealStateLinkPhontos'	=> "",
								'txtRealStateLinkGoogleMaps'=> "",
								'txtRealStateLinkOther'				=> "",
								'txtRealStateStyleKitchen'			=> "",
								'txtRealStateReferenceUbicacion'	=> "",
								'txtRealStateReferenceCondominio'	=> "",
								'txtRealStateReferenceZone'			=> "",
								'txtRealStateGerenciaExclusive'		=> "",
								
					];
					
					
					$objItemNewApiID	= $controllerApi->save("apinew", $objItemNewApi, $dataSession);							
					$objItem			= $this->Item_Model->get_rowByPK($companyID,$objItemNewApiID);
					if(!$objItem)
					{
						throw new \Exception("El siguiente producto no existe en inventario: ". $codigo."");
					}
					
				}
					
				
				if(!$objItem)
					continue;
				
				$transactionMasterDetailID				= 0;					
				$itemID 								= $objItem->itemID;
				$quantity 								= helper_StringToNumber(ltrim(rtrim($cantidad)));
				$cost 									= helper_StringToNumber(ltrim(rtrim($costo)));	
				
				//Ingrear al provedor si no existe. 
				$objProviderItemModel = $this->Provideritem_Model->getByPK($companyID,$itemID,$objTMNew["entityID"]);						
				if(!$objProviderItemModel){
					
					$objPIMNew["companyID"]	= $companyID;
					$objPIMNew["branchID"]	= $branchID;
					$objPIMNew["entityID"]	= $objTMNew["entityID"];
					$objPIMNew["itemID"]	= $itemID;
					$this->Provideritem_Model->insert_app_posme($objPIMNew);
				}
				
				//Actualizar tipo de precio 1 ---> 154 ---->PUBLICO
				if($precio > 0){
					
					$typePriceID					= 154;
					$dataUpdatePrice["price"] 		= $precio;
					$dataUpdatePrice["percentage"] 	= 
													$objItem->cost == 0 ? 
														($precio / 100) : 
														(((100 * $precio) / $objItem->cost) - 100);
					$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
				}
				
				
				
				//Nuevo Detalle
				if($transactionMasterDetailID == 0 ){						
					$objTMD 								= array();
					$objTMD["companyID"] 					= $companyID;
					$objTMD["transactionID"] 				= $transactionID;
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;//itemID
					$objTMD["quantity"] 					= $quantity;
					$objTMD["unitaryCost"]					= $cost;
					$objTMD["cost"] 						= $objTMD["quantity"] * $objTMD["unitaryCost"];
					$objTMD["unitaryAmount"]				= $precio;
					$objTMD["amount"] 						= $cost * $precio;
					$objTMD["discount"]						= 0;
					$objTMD["unitaryPrice"]					= $precio;
					$objTMD["promotionID"] 					= 0;
					$objTMD["lote"]							= $lote;
					$objTMD["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
					$objTMD["reference3"]					= '0|0';
					$objTMD["reference4"]					= '';//si se carga mediatne un excel no exsite el reference4, por que los valores de exencion de codigo, se deben de modificar, en la pantalla propiamente
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
					
				}
			}
		}
				
	}
	
	function save($mode=""){
			
			$mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
	
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtProviderID","Proveedor","required");
			$this->validation->setRule("txtStatusID","Estado","required");
			$this->validation->setRule("txtTransactionOn","Fecha","required");
				
			//Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/add');
				exit;
			} 
			//Guardar o Editar Registro						
			if($mode == "new"){
				$this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				$this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/add');
				exit;
			}
	}
	function edit(){
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_EDIT);	
			}	
			
		
			
			//Obtener parametros
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;		
			$userID 				= $dataSession["user"]->userID;
			if((!$transactionID || !$transactionMasterID))
			{ 
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/add');	
			} 		
			
			
			$objListTypePreice	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			//Obtener el componente de Item
			$objComponentItem		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el componente de Proveedor
			$objComponentProvider		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponentProvider)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			//Obtener el componente de Entrada sin postear
			$objComponentInputSinPost		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponentInputSinPost)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			//Obtener el componente de Orden de Compra
			$objComponentOrdenCompra		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_purchaseorden");
			if(!$objComponentOrdenCompra)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_purchaseorden' NO EXISTE...");
			 
			//Obtener el componente de linea de credito
			$objComponentCreditLine		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_line");
			if(!$objComponentCreditLine)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_line' NO EXISTE...");
			
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$datView["objListPrice"]			= $objListPrice;			
			$datView["objListTypePreice"]		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			//Obtener el Registro	
			$datView["useMobile"]					= $dataSession["user"]->useMobile;
			$datView["objComponentItem"]	 		= $objComponentItem;
			$datView["objComponentProvider"]	 	= $objComponentProvider;
			$datView["objComponentInputSinPost"]	= $objComponentInputSinPost;
			$datView["objComponentOrdenCompra"]		= $objComponentOrdenCompra;
			$datView["objComponentCreditLine"]		= $objComponentCreditLine;
			$datView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
			$datView["objTM"]	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]					= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_inputunpost","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTM"]->transactionOn 	= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTMOrdenCompra"]		= $this->Transaction_Master_Model->get_rowByTransactionMasterID($companyID,helper_RequestGetValue($datView["objTM"]->reference4,"0") );
			
			$datView["objProvider"]				= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);			
			$datView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID);
			$datView["objLegalDefault"]			= $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID);
			$datView["objListCurrency"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$datView["objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE"]	= $this->core_web_parameter->getParameterValue("CORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE",$companyID);
			$datView["objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY"]	= $this->core_web_parameter->getParameterValue("INVENTORY_URL_PRINTER_INPUTUNPOST_ONLY_QUANTITY",$companyID);
			$datView["objParameterINVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES"]	= $this->core_web_parameter->getParameterValue("INVENTORY_URL_PRINTER_INPUTUNPOST_SHOW_OPCIONES",$companyID);
			
			$objParameterUrlPrinter					= $this->core_web_parameter->getParameter("INVENTORY_URL_PRINTER_INPUTUNPOST",$companyID);
			$objParameterUrlPrinter 				= $objParameterUrlPrinter->value;
			$datView["objParameterUrlPrinter"]		= $objParameterUrlPrinter;
			
			$objParameterMasive					= $this->core_web_parameter->getParameter("ITEM_PRINTER_BARCODE_MASIVE",$this->session->get('user')->companyID);
			$objParameterMasive					= $objParameterMasive->value;	
			$datView["objParameterMasive"]		= $objParameterMasive;
			$datView["objListCausal"]			= $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
			$datView["objListCreditLine"]		= $this->Customer_Credit_Line_Model->get_rowByBranchID($companyID, $branchID);			
			
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$objParameterCantidadItemPoup				= $objParameterCantidadItemPoup->value;
			$datView["objParameterCantidadItemPoup"] 	= $objParameterCantidadItemPoup;
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_inputunpost/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_inputunpost/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_inputunpost/edit_script',$datView);//--finview
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
			
		    echo $resultView;		
		}
	}
	function add(){
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
			
			}	
			
			
							
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_inputunpost","statusID",$companyID,$branchID,$roleID);
			$dataView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
			
			//Obtener el componente de Item
			$objComponentItem		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el componente de Proveedor
			$objComponentProvider		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponentProvider)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			//Obtener el componente de Entrada sin postear
			$objComponentInputSinPost		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponentInputSinPost)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			//Obtener el componente de Orden de Compra
			$objComponentOrdenCompra		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_purchaseorden");
			if(!$objComponentOrdenCompra)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_purchaseorden' NO EXISTE...");

			//Obtener el componente de linea de credito
			$objComponentCreditLine		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_line");
			if(!$objComponentCreditLine)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_line' NO EXISTE...");
			
			$objParameterWarehouseDefault		= $this->core_web_parameter->getParameter("INVENTORY_ITEM_WAREHOUSE_DEFAULT",$companyID);
			$warehouseDefault 					= $objParameterWarehouseDefault->value;
			$dataView["warehouseDefault"]		= $warehouseDefault;
			$objParameterProviderDefault		= $this->core_web_parameter->getParameter("CXP_PROVIDER_DEFAULT",$companyID);
			$objParameterProviderDefault 		= $objParameterProviderDefault->value;
			$dataView["providerDefault"]	 		= $this->Provider_Model->get_rowByProviderNumber($companyID,$objParameterProviderDefault);
			$dataView["providerNaturalDefault"]	 	= $this->Natural_Model->get_rowByPK($companyID,$dataView["providerDefault"]->branchID,$dataView["providerDefault"]->entityID);
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_inputunpost",0);
			//Obtener el catalogo de tipos de precios
			$dataView["objListTypePreice"]			= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			$objListPrice 							= $this->List_Price_Model->getListPriceToApply($companyID);
			$dataView["objListPrice"]				= $objListPrice;
			$dataView["useMobile"]					= $dataSession["user"]->useMobile;
			$dataView["objComponentItem"] 			= $objComponentItem;
			$dataView["objComponentProvider"] 		= $objComponentProvider;
			$dataView["objComponentCreditLine"]		= $objComponentCreditLine;
			$dataView["objComponentInputSinPost"]	= $objComponentInputSinPost;
			$dataView["objComponentOrdenCompra"]	= $objComponentOrdenCompra;
			$dataView["objListCurrency"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objParameterCORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE"]	= $this->core_web_parameter->getParameterValue("CORE_VIEW_CUSTOM_SCROLL_IN_DETATAIL_PURSHASE",$companyID);
			$dataView["company"]					= $dataSession["company"];
			$dataView["objListCausal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
			
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$objParameterCantidadItemPoup				= $objParameterCantidadItemPoup->value;
			$dataView["objParameterCantidadItemPoup"] 	= $objParameterCantidadItemPoup;
			
			//Renderizar Resultado 
			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				= $this->core_web_notification->get_message();
			$dataSession["head"]				= /*--inicio view*/ view('app_inventory_inputunpost/news_head',$dataView);//--finview
			$dataSession["body"]				= /*--inicio view*/ view('app_inventory_inputunpost/news_body',$dataView);//--finview
			$dataSession["script"]				= /*--inicio view*/ view('app_inventory_inputunpost/news_script',$dataView);//--finview
			$dataSession["footer"]				= "";
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
			//Obtener el componente Para mostrar la lista
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
			//Vista por defecto 
			if($dataViewID == null){				
				//$targetComponentID			= 0;	
				//$parameter["{companyID}"]		= $this->session->get('user')->companyID;
				//$dataViewData					= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				//$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
				
				
				
				
				$targetComponentID			= $this->session->get('company')->flavorID;				
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;				
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				
				
				if(!$dataViewData){
					
					$targetComponentID			= 0;	
					$parameter["{companyID}"]	= $this->session->get('user')->companyID;					
					$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);				
				}
				
				
				if($dataSession["user"]->useMobile == 1)
				{					
					//$dataViewRender			= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFildMobile($dataViewData,'ListView',"fnTableSelectedRow");
				}
				else
				{
					//$dataViewRender			= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFild($dataViewData,'ListView',"fnTableSelectedRow");
				}
				
				
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
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_inputunpost/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_inputunpost/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_inputunpost/list_script');//--finview
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
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;		}
	}	
	function add_masinformacion($fnCallback="",$itemID="",$transactionMasterDetailID="",$positionID="",$lote="",$vencimiento="",$precio1="",$precio2="",$txtReference4TransactionMasterDetail = "" ){
		
			$fnCallback = helper_SegmentsByIndex($this->uri->getSegments(),1,$fnCallback);	
			$itemID = helper_SegmentsByIndex($this->uri->getSegments(),2,$itemID);	
			$transactionMasterDetailID = helper_SegmentsByIndex($this->uri->getSegments(),3,$transactionMasterDetailID);	
			$positionID = helper_SegmentsByIndex($this->uri->getSegments(),4,$positionID);	
			$lote = helper_SegmentsByIndex($this->uri->getSegments(),5,$lote);	
			$vencimiento = helper_SegmentsByIndex($this->uri->getSegments(),6,$vencimiento);	
			$precio1 = helper_SegmentsByIndex($this->uri->getSegments(),7,$precio1);	
			$precio2 = helper_SegmentsByIndex($this->uri->getSegments(),8,$precio2);	
			$txtReference4TransactionMasterDetail = helper_SegmentsByIndex($this->uri->getSegments(),9,$txtReference4TransactionMasterDetail);	
		
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
			
			$data["itemID"] 								= $itemID;
			$data["transactionMasterDetailID"] 				= $transactionMasterDetailID;
			$data["positionID"] 							= $positionID;
			$data["fnCallback"] 							= $fnCallback;
			$data["lote"] 									= $lote;
			$data["vencimiento"] 							= $vencimiento;
			$data["precio1"] 								= $precio1;
			$data["precio2"] 								= $precio2;
			$data["txtReference4TransactionMasterDetail"]	= $txtReference4TransactionMasterDetail;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_inventory_inputunpost/popup_masinformacion_item_head',$data);//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_inventory_inputunpost/popup_masinformacion_item_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_inventory_inputunpost/popup_masinformacion_item_script',$data);//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	
}
?>