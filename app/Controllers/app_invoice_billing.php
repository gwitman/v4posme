<?php
//posme:2023-02-27
namespace App\Controllers;
class app_invoice_billing extends _BaseController {
	
    
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
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			//Redireccionar datos	
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
				
			if((!$companyID || !$transactionID  || !$transactionMasterID))
			{ 
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');	
			} 		
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Componente de facturacion
			$objComponentTransactionBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentTransactionBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$customerDefault					= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			$urlPrinterDocument					= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER",$companyID);
			
			if(!$objListPrice)
			throw new \Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");
			
			//Obtener parametros para mostrar botones de impresion
			//$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_BAUCHER_GENERAL",$companyID);
			//$dataView["objParameterInvoiceButtomPrinterBoucherGeneral"] = $parameterValue->value;
			
			//$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_PREPRINTER",$companyID);
			//$dataView["objParameterInvoiceButtomPrinterPrePrinter"] = $parameterValue->value;
			//$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT",$companyID);
			//$dataView["objParameterInvoiceButtomPrinterFidLocalPayment"] = $parameterValue->value;
			$parameterValue = $this->core_web_parameter->getParameter("INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION",$companyID);
			$dataView["objParameterInvoiceButtomPrinterFidLocalPaymentAndAmortization"] = $parameterValue->value;
			
			
			$objParameterInvoiceBillingQuantityZero					= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$dataView["objParameterInvoiceBillingQuantityZero"]		= $objParameterInvoiceBillingQuantityZero->value;
			$objParameterInvoiceBillingPrinterDirect				= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirect"]	= $objParameterInvoiceBillingPrinterDirect->value;
			$objParameterInvoiceBillingPrinterDirectUrl					= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_URL",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirectUrl"]		= $objParameterInvoiceBillingPrinterDirectUrl->value;
			
			$objParameterShowComandoDeCocina							= $this->core_web_parameter->getParameter("INVOICE_BILLING_SHOW_COMMAND_FOOT",$companyID);
			$dataView["objParameterShowComandoDeCocina"]				= $objParameterShowComandoDeCocina->value;			
			$urlPrinterDocumentCocina									= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER_COCINA",$companyID);
			$dataView["urlPrinterDocumentCocina"]						= $urlPrinterDocumentCocina->value;
			$urlPrinterDocumentCocinaDirect								= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER_COCINA_DIRECT",$companyID);
			$dataView["urlPrinterDocumentCocinaDirect"]					= $urlPrinterDocumentCocinaDirect->value;
			$objParameterImprimirPorCadaFactura							= $this->core_web_parameter->getParameter("INVOICE_PRINT_BY_INVOICE",$companyID);
			$dataView["objParameterImprimirPorCadaFactura"]				= $objParameterImprimirPorCadaFactura->value;
			
			
			
			//Tipo de Factura
			$dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objTransactionMaster"]->transactionOn2 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn2),"Y-m-d");
			$dataView["objTransactionMasterDetailCredit"]		= null;	
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
						
			$dataView["objListPrice"]			= $objListPrice;
			$dataView["objComponentBilling"]	= $objComponentTransactionBilling;
			$dataView["objComponentItem"]		= $objComponentItem;
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["warehouseID"]			= $dataView["objCaudal"][0]->warehouseSourceID;
			$dataView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_billing","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objListTypePrice"]		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["objListZone"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);
			$dataView["listCurrency"]			= $objListCurrency;
			$dataView["listProvider"]			= $this->Provider_Model->get_rowByCompany($companyID);
			$dataView["objListaPermisos"]		= $dataSession["menuHiddenPopup"];
			
			
			
			if(!$dataView["objCustomerDefault"])
			throw new \Exception("NO EXISTE EL CLIENTE POR DEFECTO");
			
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			
			//Procesar Datos
			if($dataView["objTransactionMasterDetail"])
			foreach($dataView["objTransactionMasterDetail"] as $key => $value)
			{
				$dataView["objTransactionMasterDetail"][$key]->itemName = htmlentities($value->itemName,ENT_QUOTES);
				$dataView["objTransactionMasterDetailCredit"]			= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($value->transactionMasterDetailID);
			}
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_invoice_billing/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_invoice_billing/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_invoice_billing/edit_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			
			
			return view("core_masterpage/default_popup",$dataSession);//--finview-r	
			
		}
		catch(\Exception $ex){
		    
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
	
	function editv2(){ 
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
			
			
			
			//Redireccionar datos
				
			
			
			$companyID				= $dataSession["company"]->companyID;
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
			
			$transactionMasterID	= $transactionMasterID === "" ? 0 : $transactionMasterID;
			$transactionID			= $transactionID === "" ? 0 : $transactionID;
			
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
				
			
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Componente de facturacion
			$objComponentTransactionBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentTransactionBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
		
			//Obtener el componente de Item
			$objComponentItemCategory	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_category");
			if(!$objComponentItemCategory)
			throw new \Exception("EL COMPONENTE 'tb_item_category' NO EXISTE...");
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$customerDefault					= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			$urlPrinterDocument					= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER",$companyID);
			
			if(!$objListPrice)
			throw new \Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");
		
			
			
		
			
			$objParameterInvoiceBillingQuantityZero					= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$dataView["objParameterInvoiceBillingQuantityZero"]		= $objParameterInvoiceBillingQuantityZero->value;
			$objParameterInvoiceBillingPrinterDirect				= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirect"]	= $objParameterInvoiceBillingPrinterDirect->value;
			$objParameterInvoiceBillingPrinterDirectUrl					= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_URL",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirectUrl"]		= $objParameterInvoiceBillingPrinterDirectUrl->value;
			$objParameterInvoiceBillingPrinterDirectCocinaUrl					= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER_COCINA_DIRECT",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirectCocinaUrl"]		= $objParameterInvoiceBillingPrinterDirectCocinaUrl->value;
			
			//Tipo de Factura
			$dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			//Formato de fecha
			if($dataView["objTransactionMaster"]){
				$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
				$dataView["objTransactionMaster"]->transactionOn2 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn2),"Y-m-d");
			}
			
			$agent 												= $this->request->getUserAgent();			
			$dataView["isMobile"]								= helper_RequestGetValue($agent->isMobile(),"0");
			$dataView["widthPanelComando"]						= $dataView["isMobile"] == "0" ? "280" : "450";
			$dataView["widthPanelTeclado"]						= $dataView["isMobile"] == "0" ? "325" : "350";
			$dataView["widthPanelNueva"]						= $dataView["isMobile"] == "0" ? "280" : "210";
			$dataView["widthPanelCategoria"]					= $dataView["isMobile"] == "0" ? "350" : "420";
			$dataView["widthPanelCategoriaAndProductoPhone"]	= $dataView["isMobile"] == "0" ? "350" : "380";
			
				


			$dataView["objTransactionMasterDetailCredit"]		= null;	
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
						
			$dataView["objListPrice"]				= $objListPrice;
			$dataView["objComponentBilling"]		= $objComponentTransactionBilling;
			$dataView["objComponentItem"]			= $objComponentItem;
			$dataView["objComponentItemCategory"]	= $objComponentItemCategory;
			
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["warehouseID"]			= $dataView["objCaudal"][0]->warehouseSourceID;
			$dataView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
			
			//Obtener estados
			if($dataView["objTransactionMaster"]){
				$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_billing","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
				$dataView["objListWorkflowStageAll"]	= $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);				
			}
			else{				
				$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);				
				$dataView["objListWorkflowStageAll"]	= $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);				
			}
			
			
			
			//Obtener cliente por defecto
			if($dataView["objTransactionMaster"]){
				$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			}
			else{
				$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByCode($companyID,$customerDefault->value);
			}
			
			
			
			$dataView["objListTypePrice"]		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["objListZone"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);
			$dataView["listCurrency"]			= $objListCurrency;
			$dataView["listProvider"]			= $this->Provider_Model->get_rowByCompany($companyID);
			$dataView["objListaPermisos"]		= $dataSession["menuHiddenPopup"];
			
			
			if(!$dataView["objCustomerDefault"])
			throw new \Exception("NO EXISTE EL CLIENTE POR DEFECTO");
			
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			
			//Al detalle de productos escapar nombres
			if($dataView["objTransactionMasterDetail"])
			foreach($dataView["objTransactionMasterDetail"] as $key => $value)
			{
				$dataView["objTransactionMasterDetail"][$key]->itemName = htmlentities($value->itemName,ENT_QUOTES);
				$dataView["objTransactionMasterDetailCredit"]			= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($value->transactionMasterDetailID);
			}
			
			//Renderizar Resultado 			
			//$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			//$dataSession["message"]		= $this->core_web_notification->get_message();
			//$dataSession["head"]			= /*--inicio view*/ view('app_invoice_billing/edit_head',$dataView);//--finview
			//$dataSession["body"]			= /*--inicio view*/ view('app_invoice_billing/edit_body',$dataView);//--finview
			//$dataSession["script"]		= /*--inicio view*/ view('app_invoice_billing/editv2_script',$dataView);//--finview
			//$dataSession["footer"]		= "";
			$dataView["script"]				= /*--inicio view*/ view('app_invoice_billing/editv2_script',$dataView);//--finview
			
			
			return view("app_invoice_billing/editv2",$dataView);//--finview-r
			
		}
		catch(\Exception $ex){
		    
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
			
				
				
				
				
				
			
			
			
			
			//Nuevo Registro
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");				
			$transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
			
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
					throw new \Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");
				
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE); 					
				
			//Si el documento esta aplicado crear el contra documento
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			{
				$transactionIDRevert = $this->core_web_parameter->getParameter("INVOICE_TRANSACTION_REVERSION_TO_BILLING",$companyID);
				$transactionIDRevert = $transactionIDRevert->value;
				$result = $this->core_web_transaction->createInverseDocumentByTransaccion($companyID,$transactionID,$transactionMasterID,$transactionIDRevert,0);
				
				//Si la factura es de credito
				$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
				$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
				$exisCausalInCredit						= null;
				$exisCausalInCredit						= array_search($objTM->transactionCausalID ,$causalIDTypeCredit);
				
				if($exisCausalInCredit || $exisCausalInCredit === 0){
				
					//Valores de tasa de cambio
					date_default_timezone_set(APP_TIMEZONE); 
					$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
					$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
					$dateOn 								= date("Y-m-d");
					$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
					$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
						
					//cancelar el documento de credito
					$objCustomerCredotDocument					= $this->Customer_Credit_Document_Model->get_rowByDocument($objTM->companyID,$objTM->entityID,$objTM->transactionNumber);
					$objCustomerCredotDocumentNew["statusID"]	= $this->core_web_parameter->getParameter("SHARE_DOCUMENT_ANULADO",$companyID)->value;
					$this->Customer_Credit_Document_Model->update_app_posme($objCustomerCredotDocument->customerCreditDocumentID,$objCustomerCredotDocumentNew);
					
					$amountDol									= $objCustomerCredotDocument->amount / $exchangeRate;
					$amountCor									= $objCustomerCredotDocument->amount;
					
					//aumentar el blance de la linea
					$objCustomerCreditLine						= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCredotDocument->customerCreditLineID);
					$objCustomerCreditLineNew["balance"]		= $objCustomerCreditLine->balance + ($objCustomerCreditLine->currencyID == $objCurrencyDolares->currencyID ? $amountDol : $amountCor);
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCredotDocument->customerCreditLineID,$objCustomerCreditLineNew);
					
					//aumentar el balance de credito
					$objCustomer								= $this->Customer_Model->get_rowByEntity($objTM->companyID,$objTM->entityID);
					$objCustomerCredit							= $this->Customer_Credit_Model->get_rowByPK($objTM->companyID,$objCustomer->branchID,$objTM->entityID);
					$objCustomerCreditNew["balanceDol"]			= $objCustomerCredit->balanceDol + $amountDol;
					$this->Customer_Credit_Model->update_app_posme($objTM->companyID,$objCustomer->branchID,$objTM->entityID,$objCustomerCreditNew);
										
				}
				
				
			}
			else 
			{	
				//Eliminar el Registro			
				$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);			
			}
			
			
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
			
				
					
										
			
			 
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			$objParameterImprimirPorCadaFactura			= $this->core_web_parameter->getParameter("INVOICE_PRINT_BY_INVOICE",$companyID);
			$objParameterImprimirPorCadaFactura			= $objParameterImprimirPorCadaFactura->value;
			
			//Actualizar Maestro
			$typePriceID 								= /*inicio get post*/ $this->request->getPost("txtTypePriceID");
			$objListPrice 								= $this->List_Price_Model->getListPriceToApply($companyID);
			$objTMNew["transactionCausalID"] 			= /*inicio get post*/ $this->request->getPost("txtCausalID");
			$objTMNew["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["transactionOn2"]					= /*inicio get post*/ $this->request->getPost("txtDateFirst");//Fecha del Primer Pago, de las facturas al credito
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post			
			$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTMNew["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTMNew["reference4"] 					= is_null( $this->request->getPost("txtCustomerCreditLineID") ) ? "0" : /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"] 						= 0;
			$objTMNew["currencyID"]						= /*inicio get post*/ $this->request->getPost("txtCurrencyID"); 
			$objTMNew["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTMNew["currencyID"]);
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTMNew["currencyID2"],$objTMNew["currencyID"]);
			$objTMNew["sourceWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseID");
			
			
			//Ingresar Informacion Adicional
			$objTMInfoNew["companyID"]					= $objTM->companyID;
			$objTMInfoNew["transactionID"]				= $objTM->transactionID;
			$objTMInfoNew["transactionMasterID"]		= $transactionMasterID;
			$objTMInfoNew["zoneID"]						= /*inicio get post*/ $this->request->getPost("txtZoneID");
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			$objTMInfoNew["receiptAmountDol"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountDol"));
			
			$db=db_connect();
			$db->transStart();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				$this->Transaction_Master_Info_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
			}
			
			
			
			//Leer archivo
			$path 		= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			$path 		= $path.'/procesar.csv';
			$pathNew 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			$pathNew 	= $pathNew.'/procesado.csv';
					
			
			
			if (file_exists($path))
			{
				//Actualizar Detalle
				$listTransactionDetalID 					= array();
				$arrayListItemID 							= array();
				$arrayListQuantity	 						= array();
				$arrayListPrice		 						= array();
				$arrayListSubTotal	 						= array();
				$arrayListIva		 						= array();
				$arrayListLote	 							= array();
				$arrayListVencimiento						= array();
				$arrayListSku								= array();
				
				$objParameterDeliminterCsv	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				$characterSplie = $objParameterDeliminterCsv->value;
				
				//Obtener los registro del archivo
				$this->csvreader->separator = $characterSplie;
				$table 			= $this->csvreader->parse_file($path); 
				
				
				rename($path,$pathNew);
				$fila 			= 0;
				if($table)
				foreach ($table as $row) 
				{	
					$fila++;
					$codigo 		= $row["Codigo"];
					$description 	= $row["Nombre"];
					$cantidad 		= $row["Cantidad"];
					$precio 		= $row["Precio"];											
					$objItem		= $this->Item_Model->get_rowByCode($companyID,$codigo);
					
					array_push($listTransactionDetalID, 0);
					array_push($arrayListItemID, $objItem->itemID);
					array_push($arrayListQuantity, $cantidad);
					array_push($arrayListPrice, $precio);
					//$arrayListSubTotal		= SUB TOTAL ES UN SOLO NUMERO
					//$arrayListIva		 		= IVA ES UN SOLO NUMERO POR QUE ES EL TOTAL
					array_push($arrayListLote, '');
					array_push($arrayListVencimiento, '');
					array_push($arrayListSku,0);
					
				}
			}
			else{
				//Actualizar Detalle
				$listTransactionDetalID 					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDetailID");
				$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtItemID");
				$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtQuantity");
				$arrayListPrice		 						= /*inicio get post*/ $this->request->getPost("txtPrice");
				$arrayListSubTotal	 						= /*inicio get post*/ $this->request->getPost("txtSubTotal");
				$arrayListIva		 						= /*inicio get post*/ $this->request->getPost("txtIva");
				$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");			
				$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");	
				$arrayListSku								= /*inicio get post*/ $this->request->getPost("txtSku");
				
			}
						
			
				
			
			//Ingresar la configuracion de precios			
			$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 	= $objParameterPriceDefault->value;
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			
			$objParameterUpdatePrice	= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 			= $objParameterUpdatePrice->value;
			
							
							
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$subAmountTotal									= 0;			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTransactionDetalID);
			$this->Transaction_Master_Detail_Credit_Model->deleteWhereIDNotIn($transactionMasterID,$listTransactionDetalID);
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){			
					$itemID 								= $value;
					$lote 									= is_null($arrayListLote) ? "": $arrayListLote[$key];
					$vencimiento							= is_null($arrayListVencimiento) ? "" : $arrayListVencimiento[$key];
					$warehouseID 							= $objTMNew["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);
					$objItemWarehouse 						= $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);					
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");
					$skuCatalogItemID						= $arrayListSku[$key];
					$objItemSku								= $this->Item_Sku_Model->getByPK($itemID,$skuCatalogItemID);
					
					//$price 									= $objItem->cost * ( 1 + ($objPrice->percentage/100));
					//$price 									= $arrayListPrice[$key];					
					$price 									= $arrayListPrice[$key] / ($objItemSku->value) ;
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );					
					$unitaryAmount 							= $price * (1 + $ivaPercentage);					
					$tax1 									= $price * $ivaPercentage;
					$transactionMasterDetailID				= $listTransactionDetalID[$key];
					
					
					//Validar Cantidades
					$messageException = "La cantidad de '".$objItem->itemNumber. " " .$objItem->name."' es mayor que la disponible en bodega";
					$messageException = $messageException.", en bodega existen ".$objItemWarehouse->quantity." y esta solicitando : ".$quantity;
					if(
						$objItemWarehouse->quantity < $quantity  
						&& 
						$objItem->isInvoiceQuantityZero == 0
						&&
						$objParameterInvoiceBillingQuantityZero == "false"
					)					
					throw new \Exception($messageException);
										
					//Nuevo Detalle
					if($transactionMasterDetailID == 0){	
						
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $objTM->companyID;
						$objTMD["transactionID"] 				= $objTM->transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentItem->componentID;
						$objTMD["componentItemID"] 				= $itemID;
						
						$objTMD["quantity"] 					= $quantity * $objItemSku->value;	//cantidad
						$objTMD["skuQuantity"] 					= $quantity;						//cantidad
						$objTMD["skuQuantityBySku"]				= $objItemSku->value;				//cantidad
					
						
						$objTMD["unitaryCost"]					= $objItem->cost;					//costo
						$objTMD["cost"] 						= $objTMD["quantity"]  * $objItem->cost;		//costo por unidad
						
						$objTMD["unitaryPrice"]					= $price;							//precio de lista
						$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto
						$objTMD["tax1"]							= $tax1;							//impuesto de lista
						$objTMD["amount"] 						= $objTMD["quantity"] * $unitaryAmount;		//precio de lista con inpuesto por cantidad
						$objTMD["discount"]						= 0;					
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["reference1"]					= $lote;
						$objTMD["reference2"]					= $vencimiento;
						$objTMD["reference3"]					= '0';
						
						
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["expirationDate"]				= NULL;
						$objTMD["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
						$objTMD["inventoryWarehouseTargetID"]	= $objTM->targetWarehouseID;
						$objTMD["skuCatalogItemID"] 			= $skuCatalogItemID;
						
						$tax1Total								= $tax1Total + $tax1;
						$subAmountTotal							= $subAmountTotal + ($quantity * $price);
						$amountTotal							= $amountTotal + $objTMD["amount"];
						$transactionMasterDetailID_				= $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
						$objTMDC								= NULL;
						$objTMDC["transactionMasterID"]			= $transactionMasterID;
						$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID_;
						$objTMDC["reference1"]					= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
						$objTMDC["reference2"]					= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgo");
						$objTMDC["reference3"]					= /*inicio get post*/ $this->request->getPost("txtLayFirstLineProtocolo");
						$objTMDC["reference4"]					= "";
						$objTMDC["reference5"]					= "";
						$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos fijos para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";						
						$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
						
						//Actualizar el Precio
						if($objUpdatePrice)
						{							
							$typePriceID					= $typePriceID;
							$dataUpdatePrice["price"] 		= $price;
							$dataUpdatePrice["percentage"] 	= 
															$objItem->cost == 0 ? 
																($price / 100) : 
																(((100 * $price) / $objItem->cost) - 100);																		
							
							$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
							
						}
						
						
					}					
					//Editar Detalle
					else{
						
						$objTMDC  								= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($transactionMasterDetailID);
						$objTMDC								= NULL;
						
						$objTMDNew 								= null;
						
						$objTMDNew["quantity"] 					= $quantity * $objItemSku->value;	//cantidad
						$objTMDNew["skuQuantity"] 				= $quantity;						//cantidad
						$objTMDNew["skuQuantityBySku"]			= $objItemSku->value;				//cantidad
					
						
						$objTMDNew["unitaryCost"]				= $objItem->cost;				//costo
						$objTMDNew["cost"] 						= $objTMDNew["quantity"]  * $objItem->cost;	//costo por cantidad
						
						$objTMDNew["unitaryPrice"]				= $price;						//precio de lista
						$objTMDNew["unitaryAmount"]				= $unitaryAmount;				//precio de lista con inpuesto
						$objTMDNew["tax1"]						= $tax1;						//impuesto de lista
						$objTMDNew["amount"] 					= $objTMDNew["quantity"]  * $unitaryAmount;	//precio de lista con inpuesto por cantidad
						
						$objTMDNew["reference1"]				= $lote;
						$objTMDNew["reference2"]				= $vencimiento;
						$objTMDNew["reference3"]				= '0';
						$objTMDNew["inventoryWarehouseSourceID"]= $objTMNew["sourceWarehouseID"];
						$objTMDNew["skuCatalogItemID"] 			= $skuCatalogItemID;
						
						
						$tax1Total								= $tax1Total + $tax1;
						$subAmountTotal							= $subAmountTotal + ($quantity * $price);
						$amountTotal							= $amountTotal + $objTMDNew["amount"];						
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMDNew);	
						
						$objTMDC["reference1"]					= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
						$objTMDC["reference2"]					= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgo");
						$objTMDC["reference3"]					= /*inicio get post*/ $this->request->getPost("txtLayFirstLineProtocolo");
						$objTMDC["reference4"]					= "";
						$objTMDC["reference5"]					= "";
						$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos Fijos para las Facturas de Credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";
						$this->Transaction_Master_Detail_Credit_Model->update_app_posme($transactionMasterDetailID,$objTMDC);
						
						//Actualizar el Precio
						if($objUpdatePrice)
						{
							
							$typePriceID					= $typePriceID;
							$dataUpdatePrice["price"] 		= $price;
							$dataUpdatePrice["percentage"] 	= 
															$objItem->cost == 0 ? 
																($price / 100) : 
																(((100 * $price) / $objItem->cost) - 100);
							
							$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
							
						}
						
					}
					
					
				}
			}			
			
			//Actualizar Transaccion			
			$objTMNew["amount"] 	= $amountTotal;
			$objTMNew["tax1"] 		= $tax1Total;
			$objTMNew["subAmount"] 	= $subAmountTotal;
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			
			
			//Aplicar el Documento?
			if( 
				$this->core_web_workflow->validateWorkflowStage
				(
					"tb_transaction_master_billing",
					"statusID",
					$objTMNew["statusID"],
					COMMAND_APLICABLE,
					$dataSession["user"]->companyID,
					$dataSession["user"]->branchID,
					$dataSession["role"]->roleID
				) &&  
				$oldStatusID != $objTMNew["statusID"] 
			){
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
				//Si es al credito crear tabla de amortizacion
				$causalIDTypeCredit 	= explode(",", $parameterCausalTypeCredit->value);
				$exisCausalInCredit		= null;
				$exisCausalInCredit		= array_search($objTMNew["transactionCausalID"] ,$causalIDTypeCredit);
				
				//si la factura es de credito
				if($exisCausalInCredit || $exisCausalInCredit === 0){
					
					
					//Crear documento del modulo
					$objCustomerCreditLine 								= $this->Customer_Credit_Line_Model->get_rowByPK($objTMNew["reference4"]);
					$objCustomerCreditDocument["companyID"] 			= $companyID;
					$objCustomerCreditDocument["entityID"] 				= $objCustomerCreditLine->entityID;
					$objCustomerCreditDocument["customerCreditLineID"] 	= $objCustomerCreditLine->customerCreditLineID;
					$objCustomerCreditDocument["documentNumber"] 		= $objTM->transactionNumber;
					$objCustomerCreditDocument["dateOn"] 				= $objTMNew["transactionOn"];
					$objCustomerCreditDocument["exchangeRate"] 			= $objTMNew["exchangeRate"];
					$objCustomerCreditDocument["term"] 					= $objCustomerCreditLine->term;
					$objCustomerCreditDocument["interes"] 				= $objCustomerCreditLine->interestYear;
					$objCustomerCreditDocument["amount"] 				= $amountTotal;
					$objCustomerCreditDocument["currencyID"] 			= $objTMNew["currencyID"];					
					$objCustomerCreditDocument["statusID"] 				= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_document","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
					$objCustomerCreditDocument["reference1"] 			= $objTMNew["note"];
					$objCustomerCreditDocument["reference2"] 			= "";
					$objCustomerCreditDocument["reference3"] 			= "";
					$objCustomerCreditDocument["isActive"] 				= 1;
					
					$objCustomerCreditDocument["providerIDCredit"] 		= $objTMNew["reference1"];
					$objCustomerCreditDocument["periodPay"]				= $objCustomerCreditLine->periodPay;
					$objCustomerCreditDocument["typeAmortization"] 		= $objCustomerCreditLine->typeAmortization;
					$objCustomerCreditDocument["balance"] 				= $amountTotal;
					$objCustomerCreditDocument["reportSinRiesgo"] 	 	= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgo");
					$customerCreditDocumentID 							= $this->Customer_Credit_Document_Model->insert_app_posme($objCustomerCreditDocument);
					$periodPay 											= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);
					
					//Crear tabla de amortizacion
					$this->financial_amort->amort(
						$objCustomerCreditDocument["amount"], 		/*monto*/
						$objCustomerCreditDocument["interes"],		/*interes anual*/
						$objCustomerCreditDocument["term"],			/*numero de pagos*/	
						$periodPay->sequence,						/*frecuencia de pago en dia*/
						$objTMNew["transactionOn2"], 				/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 	/*tipo de amortizacion*/
					);
					
					$tableAmortization = $this->financial_amort->getTable();
					if($tableAmortization["detail"])
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
					
					//Crear las personas relacionadas a la factura
					$objEntityRelated								= array();
					$objEntityRelated["customerCreditDocumentID"]	= $customerCreditDocumentID;
					$objEntityRelated["entityID"]					= $objCustomerCreditLine->entityID;
					$objEntityRelated["type"]						= $this->core_web_parameter->getParameter("CXC_PROPIETARIO_DEL_CREDITO",$companyID)->value;
					$objEntityRelated["typeCredit"]					= 401; /*comercial*/
					$objEntityRelated["statusCredit"]				= 429; /*activo*/
					$objEntityRelated["typeGarantia"]				= 444; /*pagare*/
					$objEntityRelated["typeRecuperation"]			= 450; /*recuperacion normal */
					$objEntityRelated["ratioDesembolso"]			= 1;
					$objEntityRelated["ratioBalance"]				= 1;
					$objEntityRelated["ratioBalanceExpired"]		= 1;
					$objEntityRelated["ratioShare"]					= 1;
					$objEntityRelated["isActive"]					= 1;
					$this->core_web_auditoria->setAuditCreated($objEntityRelated,$dataSession,$this->request);			
					$ccEntityID 		= $this->Customer_Credit_Document_Endity_Related_Model->insert_app_posme($objEntityRelated);
					
					//Calculo del Total en Dolares
					$amountTotalDolares	= $objTMNew["exchangeRate"] > 1 ? 
								/*factura en cordoba*/ ($amountTotal * round($objTMNew["exchangeRate"],4)) : 
								/*factura en dolares*/ ($amountTotal * 1 );
					
					
					//disminuir el balance de general					
					$objCustomerCredit 					= $this->Customer_Credit_Model->get_rowByPK($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID);
					$objCustomerCreditNew["balanceDol"]	= $objCustomerCredit->balanceDol - $amountTotalDolares;
					$this->Customer_Credit_Model->update_app_posme($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID,$objCustomerCreditNew);
					
					//disminuir el balance de linea
					if($objCustomerCreditLine->currencyID == $objCurrencyCordoba->currencyID)
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotal;
					else
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotalDolares;
						
					
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
					
				}
				
			}
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_invoice_billing/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');	
			}
			
		}
		catch(\Exception $ex){
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
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$objTransactionCausal 					= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,/*inicio get post*/ $this->request->getPost("txtCausalID"));
			
			
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
			
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			
			//obtener el primer estado  de la factura o el estado inicial.
			$objListWorkflowStage					= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			//Saber si se va autoaplicar
			$objParameterInvoiceAutoApply			= $this->core_web_parameter->getParameter("INVOICE_AUTOAPPLY_CASH",$companyID);
			$objParameterInvoiceAutoApply			= $objParameterInvoiceAutoApply->value;
			$objParaemterStatusCanceled				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CANCEL",$companyID);
			$objParaemterStatusCanceled				= $objParaemterStatusCanceled->value;
			$objParameterUrlPrinterDirect			= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_URL",$companyID);
			$objParameterUrlPrinterDirect			= $objParameterUrlPrinterDirect->value;
			$objParameterImprimirPorCadaFactura		= $this->core_web_parameter->getParameter("INVOICE_PRINT_BY_INVOICE",$companyID);
			$objParameterImprimirPorCadaFactura		= $objParameterImprimirPorCadaFactura->value;
			
			
			
			
			//Saber si es al credito
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);			
			$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit						= null;
			$exisCausalInCredit						= array_search(/*inicio get post*/ $this->request->getPost("txtCausalID"),$causalIDTypeCredit);
			if($exisCausalInCredit || $exisCausalInCredit === 0){
				$exisCausalInCredit = "true";
			}
			//Si esta configurado como auto aplicado
			//y es al credito. cambiar el estado por el estado inicial, que es registrada
			$statusID = "";
			if($objParameterInvoiceAutoApply == "true" && $exisCausalInCredit == "true" ){				
				$statusID = $objListWorkflowStage[0]->workflowStageID;
			}
			//si la factura es al contado, y esta como auto aplicada cambiar el estado
			else if ($objParameterInvoiceAutoApply == "true" && $exisCausalInCredit != "true" ){
				$statusID  = $objParaemterStatusCanceled;
			}
			//De lo contrario respetar el estado que venga en pantalla
			else {
				$statusID = /*inicio get post*/ $this->request->getPost("txtStatusID");
			}
			
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_billing",0);
			$objTM["transactionCausalID"] 			= /*inicio get post*/ $this->request->getPost("txtCausalID");
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["transactionOn2"]				= /*inicio get post*/ $this->request->getPost("txtDateFirst");//Fecha del Primer Pago, de las facturas al credito
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentBilling->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID"); 
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTM["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTM["reference4"] 					= is_null($this->request->getPost("txtCustomerCreditLineID")) ? "0" : /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post*/
			$objTM["statusID"] 						= $statusID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseID");
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();			
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			mkdir($documentoPath, 0700);
			
			//Ingresar Informacion Adicional
			$objTMInfo["companyID"]					= $objTM["companyID"];
			$objTMInfo["transactionID"]				= $objTM["transactionID"];
			$objTMInfo["transactionMasterID"]		= $transactionMasterID;
			$objTMInfo["zoneID"]					= /*inicio get post*/ $this->request->getPost("txtZoneID");
			$objTMInfo["routeID"]					= 0;
			$objTMInfo["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfo["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfo["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			$objTMInfo["receiptAmountDol"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountDol"));
			$this->Transaction_Master_Info_Model->insert_app_posme($objTMInfo);
			
			//Recorrer la lista del detalle del documento
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtItemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtQuantity");	
			$arrayListPrice		 						= /*inicio get post*/ $this->request->getPost("txtPrice");
			$arrayListSubTotal	 						= /*inicio get post*/ $this->request->getPost("txtSubTotal");
			$arrayListIva		 						= /*inicio get post*/ $this->request->getPost("txtIva");
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");			
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");			
			$arrayListSku								= /*inicio get post*/ $this->request->getPost("txtSku");
			
			//Ingresar la configuracion de precios		
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$subAmountTotal									= 0;
			
			
			//Tipo de precio seleccionado por el usuario,
			//Actualmente no se esta usando
			$typePriceID 							= /*inicio get post*/ $this->request->getPost("txtTypePriceID");
			$objListPrice 							= $this->List_Price_Model->getListPriceToApply($companyID);
			//obtener la lista de precio por defecto
			$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 	= $objParameterPriceDefault->value;
			//obener los tipos de precio de la lista de precio por defecto
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			//Parametro para validar si se cambian los precios en la facturacion
			$objParameterUpdatePrice	= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 			= $objParameterUpdatePrice->value;
			
			
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){
					
					$itemID 								= $value;
					$lote 									= is_null($arrayListLote)? "" : $arrayListLote[$key];
					$vencimiento							= is_null($arrayListVencimiento) ? "" : $arrayListVencimiento[$key];
					$warehouseID 							= $objTM["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);					
					$objItemWarehouse 						= $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");
					$skuCatalogItemID						= $arrayListSku[$key];
					$objItemSku								= $this->Item_Sku_Model->getByPK($itemID,$skuCatalogItemID);
					
					//$price								= $objItem->cost * ( 1 + ($objPrice->percentage/100));
					$price 									= $arrayListPrice[$key] / ($objItemSku->value) ;
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );
					$unitaryAmount 							= $price * (1 + $ivaPercentage);
					$tax1 									= $price * $ivaPercentage;
					
					
					if(
						$objItemWarehouse->quantity < $quantity 
						&& 
						$objItem->isInvoiceQuantityZero == 0
						&&						
						$objParameterInvoiceBillingQuantityZero == "false"
					)
					throw new \Exception("La cantidad de '"+$objItem->itemNumber+ " " +$objItem->name+"' es mayor que la disponible en bodega");
					
					
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					
					$objTMD["quantity"] 					= $quantity * $objItemSku->value;	//cantidad
					$objTMD["skuQuantity"] 					= $quantity;						//cantidad
					$objTMD["skuQuantityBySku"]				= $objItemSku->value;				//cantidad
					
					$objTMD["unitaryCost"]					= $objItem->cost;					//costo
					$objTMD["cost"] 						= $objTMD["quantity"]  * $objItem->cost;		//cantidad por costo
					
					$objTMD["unitaryPrice"]					= $price;							//precio de lista
					$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto					
					$objTMD["amount"] 						= $objTMD["quantity"] * $unitaryAmount;		//precio de lista con inpuesto por cantidad
					$objTMD["tax1"]							= $tax1;							//impuesto de lista
					
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $lote;
					$objTMD["reference2"]					= $vencimiento;
					$objTMD["reference3"]					= '0';
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
					$objTMD["skuCatalogItemID"] 			= $skuCatalogItemID;
					
					
					$tax1Total								= $tax1Total + $tax1;
					$subAmountTotal							= $subAmountTotal + ($quantity * $price);
					$amountTotal							= $amountTotal + $objTMD["amount"];
					
					$transactionMasterDetailID_				= $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					
					$objTMDC								= NULL;
					$objTMDC["transactionMasterID"]			= $transactionMasterID;
					$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID_;
					$objTMDC["reference1"]					= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
					$objTMDC["reference2"]					= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgo");
					$objTMDC["reference3"]					= /*inicio get post*/ $this->request->getPost("txtLayFirstLineProtocolo");
					$objTMDC["reference4"]					= "";
					$objTMDC["reference5"]					= "";
					$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos Fijo para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";
					$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
					
					//Actualizar tipo de precio
					if($objUpdatePrice)
					{ 
						
						$typePriceID					= $typePriceID;																				
						$dataUpdatePrice["price"] 		= $price;
						$dataUpdatePrice["percentage"] 	= 
														$objItem->cost == 0 ? 
															($price / 100) : 
															(((100 * $price) / $objItem->cost) - 100);
															
						
						$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
								
						
					}
					
					
				}
			}
			
			//Actualizar Transaccion
			$objTM["amount"] 	= $amountTotal;
			$objTM["tax1"] 		= $tax1Total;
			$objTM["subAmount"] = $subAmountTotal;			
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
			
			//Aplicar el Documento?
			//Las factuas de credito no se auto aplican auque este el parametro, por que hay que crer el documento
			//y esto debe ser revisado cuidadosamente
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
			}
			
			
			
			if( $db->transStatus() !== false && $objParameterInvoiceAutoApply == "false"  ){
				$db->transCommit();
				$this->core_web_notification->set_message(false,SUCCESS);				
				$this->response->redirect(base_url()."/".'app_invoice_billing/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			if(  $db->transStatus() !== false && $objParameterInvoiceAutoApply == "true"  ){
				$db->transCommit();
				
				//si es auto aplicadao mandar a imprimir
				if($objParameterInvoiceAutoApply == "true" && $objParameterImprimirPorCadaFactura == "true" ){
				
					// create a new cURL resource
					$ch 		= curl_init();
					$urlPrinter = base_url()."/".$objParameterUrlPrinterDirect."/companyID/".$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID;
					log_message("error",$urlPrinter);
					
					// set URL and other appropriate options
					curl_setopt($ch, CURLOPT_URL, $urlPrinter);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					
					// grab URL and pass it to the browser
					curl_exec($ch);
					
					// close cURL resource, and free up system resources
					curl_close($ch);
				}
				
				
				$this->core_web_notification->set_message(false,SUCCESS);				
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');	
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');	
			}
			
			
		}
		catch(\Exception $ex){
		    
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
	function saveApi(){
		 try{ 
			//Autenticado
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("statusID","Estado","required");
			$this->validation->setRule("transactionOn","Fecha de transaccion","required");
			$this->validation->setRule("createdOn","Fecha de creacion","required");
			$this->validation->setRule("transactionMasterID","Id de transaccion","required");
			$this->validation->setRule("transactionCausalID","Id del causal","required");
			
			
			
			//Permiso de agregar factura
			if(/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0){
				if(APP_NEED_AUTHENTICATION == true){
					$permited = false;
					$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					
					if(!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);
					
					$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);	
				}
			}
			//Permiso de editar factura
			else{
				if(APP_NEED_AUTHENTICATION == true){
					$permited = false;
					$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					
					if(!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);
					
					$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);	
				}
			}
			
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
			
			//Obtener componentes
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);			
			$objTransaction							= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$objTransactionCausal 					= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,/*inicio get post*/ $this->request->getPost("transactionCausalID"));
			
			
			//Obtener tipo de cambio			
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
			//Validar ciclo contable
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("transactionOn")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Validar licencia
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			
			//Obtener parametros
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			
			
			$objParameterInvoiceAutoApply				= $this->core_web_parameter->getParameter("INVOICE_AUTOAPPLY_CASH",$companyID);
			$objParameterInvoiceAutoApply				= $objParameterInvoiceAutoApply->value;
			
			$objParameterPriceDefault				= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 							= $objParameterPriceDefault->value;
						
			$objParameterUpdatePrice				= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 						= $objParameterUpdatePrice->value;
			
			
			//Ver si es factura de credito
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);			
			$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit						= null;
			$exisCausalInCredit						= array_search(/*inicio get post*/ $this->request->getPost("transactionCausalID"),$causalIDTypeCredit);
			$esFacturaDeCredito						= false;
			if($exisCausalInCredit || $exisCausalInCredit === 0){
				$exisCausalInCredit = "true";
				$esFacturaDeCredito = true;
			}
			
			//Obter estado de factura
			$objListWorkflowStage					= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			
			
			//Obtener el estado de la factura
			//Si la configuracion es auto - aplicada
			//pero es una factura de credito - pasar el estado al inicial
			$statusID = "";
			if($objParameterInvoiceAutoApply == "true" && $exisCausalInCredit == "true" ){				
				$statusID = $objListWorkflowStage[0]->workflowStageID;
			}
			//De lo contrario respetar el estado que venga en pantalla
			else {
				$statusID = /*inicio get post*/ $this->request->getPost("statusID");
			}
			
			
			
			//Obtener tipos de precio
			$typePriceID 								= /*inicio get post*/ $this->request->getPost("typePriceID");
			$objListPrice 								= $this->List_Price_Model->getListPriceToApply($companyID);
			
			//Obtener el catalogo de tipos de precios
			$objTipePrice 		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			//Inicia los valores de la factura	
			$transactionNumber 	= 
				/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0 ? 
				$this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_billing",0) :
				/*inicio get post*/ $this->request->getPost("transactionNumber");
				
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $transactionNumber;
			$objTM["transactionCausalID"] 			= /*inicio get post*/ $this->request->getPost("transactionCausalID");
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("entityID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("transactionOn");
			$objTM["transactionOn2"]				= /*inicio get post*/ $this->request->getPost("transactionOn");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentBilling->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("note");//--fin peticion get o post
			$objTM["sign"] 							= $objTransaction->signInventory;
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("currencyID"); 
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("reference1");
			$objTM["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("reference2");
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("reference3");
			$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("reference4");//--fin peticion get o post
			$objTM["statusID"] 						= $statusID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= /*inicio get post*/ $this->request->getPost("sourceWarehouseID");
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);	
			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = 0;
			
			//Insertar Factura
			if(/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0){
				$transactionMasterID 						= $this->Transaction_Master_Model->insert_app_posme($objTM);				
				
				//Crear la Carpeta para almacenar los Archivos del Documento
				mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID, 0700);
			}
			else{
				$transactionMasterID 		= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
				$objTransactionMasterOld  	= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				
				//Validar Edicion por el Usuario
				if ($resultPermission 	== PERMISSION_ME && (  /*inicio get post*/ $this->request->getPost("createdBy") != $userID))
				throw new \Exception(NOT_EDIT);
			
				//Validar si el estado permite editar
				if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTransactionMasterOld->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
				throw new \Exception(NOT_WORKFLOW_EDIT);	
			
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
				
			}
			
			
			
			//Insertar Transaction Master Info
			$objTMInfoNew["companyID"]					= $objTM["companyID"];
			$objTMInfoNew["transactionID"]				= $objTM["transactionID"];			
			$objTMInfoNew["zoneID"]						= /*inicio get post*/ $this->request->getPost("zoneID");
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("referenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("referenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("receiptAmount"));
			$objTMInfoNew["receiptAmountDol"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("receiptAmountDol"));			
			$objTMInfoNew["transactionMasterID"]  		= $transactionMasterID;
			
			if(/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0){
				$this->Transaction_Master_Info_Model->insert_app_posme($objTMInfoNew);
			}
			else{
				
				$this->Transaction_Master_Info_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
			}
			//Actualizar Detalle			
			$listTransactionDetalID 					= /*inicio get post*/ $this->request->getPost("transactionMasterDetailID");
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("itemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("quantity");
			$arrayListPrice		 						= /*inicio get post*/ $this->request->getPost("price");
			$arrayListSubTotal	 						= /*inicio get post*/ $this->request->getPost("subtotal");
			$arrayListIva		 						= /*inicio get post*/ $this->request->getPost("iva");
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("lote");			
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("vencimiento");	
			$arrayListSku								= /*inicio get post*/ $this->request->getPost("sku");
			
			
			
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$subAmountTotal									= 0;			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTransactionDetalID);
			$this->Transaction_Master_Detail_Credit_Model->deleteWhereIDNotIn($transactionMasterID,$listTransactionDetalID);
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){		
				
					//Obtener Variables
					$itemID 								= $value;
					$lote 									= $arrayListLote[$key];
					$vencimiento							= $arrayListVencimiento[$key];
					$warehouseID 							= $objTM["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);
					$objItemWarehouse 						= $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);					
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);					
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");									
					
					$skuCatalogItemID						= $arrayListSku[$key];					
					$objItemSku								= $this->Item_Sku_Model->getByPK($itemID,$skuCatalogItemID);
					
					$price 									= $arrayListPrice[$key] / ($quantity * $objItemSku->value) ;
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );					
					$unitaryAmount 							= $price * (1 + $ivaPercentage);					
					$tax1 									= $price * $ivaPercentage;
					$transactionMasterDetailID				= $listTransactionDetalID[$key];
					$nuevoRegistro							= true;
					
					
					//Validar Cantidades
					$messageException = "La cantidad de '".$objItem->itemNumber. " " .$objItem->name."' es mayor que la disponible en bodega";
					$messageException = $messageException.", en bodega existen ".$objItemWarehouse->quantity." y esta solicitando : ".$quantity;
					if(
						$objItemWarehouse->quantity < $quantity  
						&& 
						$objItem->isInvoiceQuantityZero == 0
					)					
					throw new \Exception($messageException);
						
					//Transacation Master Detalle
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					
					$objTMD["quantity"] 					= $quantity * $objItemSku->value;	//cantidad
					$objTMD["skuQuantity"] 					= $quantity;						//cantidad
					$objTMD["skuQuantityBySku"]				= $objItemSku->value;				//cantidad
					
					
					$objTMD["unitaryCost"]					= $objItem->cost;							//costo
					$objTMD["cost"] 						= $objTMD["quantity"] * $objItem->cost;		//costo por unidad
					
					$objTMD["unitaryPrice"]					= $price;							//precio de lista
					$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto					
					$objTMD["amount"] 						= $objTMD["quantity"]* $unitaryAmount;		//precio de lista con inpuesto por cantidad
					
					$objTMD["tax1"]							= $tax1;							//impuesto de lista
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $lote;
					$objTMD["reference2"]					= $vencimiento;
					$objTMD["reference3"]					= '0';
					
					
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
					$objTMD["skuCatalogItemID"] 			= $skuCatalogItemID;
					
					
				
					
					if($transactionMasterDetailID == 0){	
						$nuevoRegistro 				= true;						
						$transactionMasterDetailID 	= $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					}
					else{			
						$nuevoRegistro 	= false;
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMD);							
						
					}
					
					
					
					//Precio
					if($objUpdatePrice)
					{							
						$typePriceID					= $typePriceID;
						$dataUpdatePrice["price"] 		= $price;
						$dataUpdatePrice["percentage"] 	= 
														$objItem->cost == 0 ? 
															($price / 100) : 
															(((100 * $price) / $objItem->cost) - 100);																		
						
						$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
						
					}
					
					
					//Documento
					$objTMDC								= NULL;
					$objTMDC["transactionMasterID"]			= $transactionMasterID;
					$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID;
					$objTMDC["reference1"]					= 0;
					$objTMDC["reference2"]					= 0;
					$objTMDC["reference3"]					= 0;
					$objTMDC["reference4"]					= "";
					$objTMDC["reference5"]					= "";
					$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos fijos para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";						
					
				
					
					if($nuevoRegistro == true){	
						$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
					}
					else{			
						$this->Transaction_Master_Detail_Credit_Model->update_app_posme($transactionMasterDetailID,$objTMDC);
						
					}
					
					//Sumarizar Variable Totales
					$tax1Total								= $tax1Total + $tax1;
					$subAmountTotal							= $subAmountTotal + ($quantity * $price);
					$amountTotal							= $amountTotal + $objTMD["amount"];
					
					
					
				}
			}
			
			//Actualizar Transaction Master despues del detalle			
			$objTM["amount"] 	= $amountTotal;
			$objTM["tax1"] 		= $tax1Total;
			$objTM["subAmount"] = $subAmountTotal;			
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
			
			//Aplicar el Documento
			//Las factuas de credito no se auto aplican auque este el parametro, por que hay que crer el documento
			//y esto debe ser revisado cuidadosamente
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
				//Si es al credito crear tabla de amortizacion				
				//si la factura es de credito
				if($esFacturaDeCredito == true){
					
					
					//Crear documento del modulo
					$objCustomerCreditLine 								= $this->Customer_Credit_Line_Model->get_rowByPK($objTM["reference4"]);
					$objCustomerCreditDocument["companyID"] 			= $companyID;
					$objCustomerCreditDocument["entityID"] 				= $objCustomerCreditLine->entityID;
					$objCustomerCreditDocument["customerCreditLineID"] 	= $objCustomerCreditLine->customerCreditLineID;
					$objCustomerCreditDocument["documentNumber"] 		= $objTM["transactionNumber"];
					$objCustomerCreditDocument["dateOn"] 				= $objTM["transactionOn"];
					$objCustomerCreditDocument["exchangeRate"] 			= $objTM["exchangeRate"];
					$objCustomerCreditDocument["term"] 					= $objCustomerCreditLine->term;
					$objCustomerCreditDocument["interes"] 				= $objCustomerCreditLine->interestYear;
					$objCustomerCreditDocument["amount"] 				= $amountTotal;
					$objCustomerCreditDocument["currencyID"] 			= $objTM["currencyID"];					
					$objCustomerCreditDocument["statusID"] 				= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_document","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
					$objCustomerCreditDocument["reference1"] 			= $objTM["note"];
					$objCustomerCreditDocument["reference2"] 			= "";
					$objCustomerCreditDocument["reference3"] 			= "";
					$objCustomerCreditDocument["isActive"] 				= 1;
					
					$objCustomerCreditDocument["providerIDCredit"] 		= $objTM["reference1"];
					$objCustomerCreditDocument["periodPay"]				= $objCustomerCreditLine->periodPay;
					$objCustomerCreditDocument["typeAmortization"] 		= $objCustomerCreditLine->typeAmortization;
					$objCustomerCreditDocument["balance"] 				= $amountTotal;
					$objCustomerCreditDocument["reportSinRiesgo"] 	 	= false;
					$customerCreditDocumentID 							= $this->Customer_Credit_Document_Model->insert_app_posme($objCustomerCreditDocument);
					$periodPay 											= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);
					
					//Crear tabla de amortizacion
					$this->financial_amort->amort(
						$objCustomerCreditDocument["amount"], 		/*monto*/
						$objCustomerCreditDocument["interes"],		/*interes anual*/
						$objCustomerCreditDocument["term"],			/*numero de pagos*/	
						$periodPay->sequence,						/*frecuencia de pago en dia*/
						$objTM["transactionOn2"], 					/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 	/*tipo de amortizacion*/
					);
					
					$tableAmortization = $this->financial_amort->getTable();
					if($tableAmortization["detail"])
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
					
					//Crear las personas relacionadas a la factura
					$objEntityRelated								= array();
					$objEntityRelated["customerCreditDocumentID"]	= $customerCreditDocumentID;
					$objEntityRelated["entityID"]					= $objCustomerCreditLine->entityID;
					$objEntityRelated["type"]						= $this->core_web_parameter->getParameter("CXC_PROPIETARIO_DEL_CREDITO",$companyID)->value;
					$objEntityRelated["typeCredit"]					= 401; /*comercial*/
					$objEntityRelated["statusCredit"]				= 429; /*activo*/
					$objEntityRelated["typeGarantia"]				= 444; /*pagare*/
					$objEntityRelated["typeRecuperation"]			= 450; /*recuperacion normal */
					$objEntityRelated["ratioDesembolso"]			= 1;
					$objEntityRelated["ratioBalance"]				= 1;
					$objEntityRelated["ratioBalanceExpired"]		= 1;
					$objEntityRelated["ratioShare"]					= 1;
					$objEntityRelated["isActive"]					= 1;
					$this->core_web_auditoria->setAuditCreated($objEntityRelated,$dataSession,$this->request);			
					$ccEntityID 	= $this->Customer_Credit_Document_Endity_Related_Model->insert_app_posme($objEntityRelated);
					
					//Calculo del Total en Dolares
					$amountTotalDolares	= $objTM["exchangeRate"] > 1 ? 
								/*factura en cordoba*/ ($amountTotal * round($objTM["exchangeRate"],4)) : 
								/*factura en dolares*/ ($amountTotal * 1 );
					
					
					//disminuir el balance de general					
					$objCustomerCredit 					= $this->Customer_Credit_Model->get_rowByPK($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID);
					$objCustomerCreditNew["balanceDol"]	= $objCustomerCredit->balanceDol - $amountTotalDolares;
					$this->Customer_Credit_Model->update_app_posme($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID,$objCustomerCreditNew);
					
					//disminuir el balance de linea
					if($objCustomerCreditLine->currencyID == $objCurrencyCordoba->currencyID)
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotal;
					else
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotalDolares;
						
					//actualizar balance de la linea de credito del cliente
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
					
				}
				
				
			}
			
			if($db->transStatus() !== false){
				$db->transCommit();										
			}
			else{
				$db->transRollback();						
				throw new \Exception($this->db->_error_message());
				
			}
			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => $transactionNumber,
				'transactionMasterID' => $transactionMasterID,
				'transactionNumber' => $transactionNumber,
				'companyID' => $companyID,
				'transactionID' => $transactionID
			));//--finjson	
			
		}
		catch(\Exception $ex){
			
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson	
		}		
	}
	function save($mode=""){		
		 $mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
		 
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Validar Formulario									
			

			
			//reglas			
			$this->validation->setRule("txtStatusID","Estado","required|min_length[1]");
			$this->validation->setRule("txtDate","Fecha","required");
			
			//echo print_r($this->validation->withRequest($this->request)->run(),true);
			//echo print_r($this->validation->getErrors(),true);
			//echo print_r($this->validation->getError("txtStatusID"),true);
			 //Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){				
				
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());				
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');
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
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');
				exit;
			}
			
			
		}
		catch(\Exception $ex){
		    
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
	
	function add(){ 
	
		try{ 
			
			
			//$this->cachePage(60);			
			
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
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$customerDefault					= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			if(!$objListPrice)
			throw new \Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");
		
			$objParameterInvoiceAutoApply			= $this->core_web_parameter->getParameter("INVOICE_AUTOAPPLY_CASH",$companyID);
			$objParameterInvoiceAutoApply			= $objParameterInvoiceAutoApply->value;
			$objParameterTypePreiceDefault			= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE",$companyID);
			$objParameterTypePreiceDefault			= $objParameterTypePreiceDefault->value;
			$objParameterTipoWarehouseDespacho		= $this->core_web_parameter->getParameter("INVOICE_TYPE_WAREHOUSE_DESPACHO",$companyID);
			$objParameterTipoWarehouseDespacho		= $objParameterTipoWarehouseDespacho->value;
			$objParameterImprimirPorCadaFactura		= $this->core_web_parameter->getParameter("INVOICE_PRINT_BY_INVOICE",$companyID);
			$objParameterImprimirPorCadaFactura		= $objParameterImprimirPorCadaFactura->value;
			
			
			//Obtener la lista de estados
			if($objParameterInvoiceAutoApply == "true"){
				$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageApplyFirst("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			}
			else{
				$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			}
			
			
			
			//Tipo de Factura
			$dataView["companyID"]							= $dataSession["user"]->companyID;
			$dataView["userID"]								= $dataSession["user"]->userID;
			$dataView["userName"]							= $dataSession["user"]->nickname;
			$dataView["roleID"]								= $dataSession["role"]->roleID;
			$dataView["roleName"]							= $dataSession["role"]->name;
			$dataView["branchID"]							= $dataSession["branch"]->branchID;
			$dataView["branchName"]							= $dataSession["branch"]->name;
			$dataView["exchangeRate"]						= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["listCurrency"]						= $objListCurrency;
			$dataView["objListPrice"]						= $objListPrice;
			$dataView["objComponentItem"]					= $objComponentItem;
			$dataView["objComponentCustomer"]				= $objComponentCustomer;
			$dataView["objCaudal"]							= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["warehouseID"]						= $dataView["objCaudal"][0]->warehouseSourceID;
			$dataView["objListWarehouse"]					= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);			
			$dataView["objCustomerDefault"]					= $this->Customer_Model->get_rowByCode($companyID,$customerDefault->value);
			$dataView["objListTypePrice"]					= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["objListZone"]						= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);			
			$dataView["listProvider"]						= $this->Provider_Model->get_rowByCompany($companyID);
			$dataView["objListaPermisos"]					= $dataSession["menuHiddenPopup"];
			$dataView["objParameterTypePreiceDefault"] 		= $objParameterTypePreiceDefault;
			$dataView["objParameterTipoWarehouseDespacho"] 	= $objParameterTipoWarehouseDespacho;
			$dataView["objParameterInvoiceAutoApply"] 		= $objParameterInvoiceAutoApply;
			$dataView["objParameterImprimirPorCadaFactura"] = $objParameterImprimirPorCadaFactura;
			
			
			$objParameterInvoiceBillingQuantityZero					= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$dataView["objParameterInvoiceBillingQuantityZero"]		= $objParameterInvoiceBillingQuantityZero->value;
			
						
			if(!$dataView["objCustomerDefault"])
			throw new \Exception("NO EXISTE EL CLIENTE POR DEFECTO");
			
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_invoice_billing/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_invoice_billing/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_invoice_billing/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			//return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			return view("core_masterpage/default_popup",$dataSession);//--finview-r	
			
		}
		catch(\Exception $ex){
		    
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
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			//$this->dompdf->loadHTML("<h1>hola mundo</h1>");
			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->render();
			//$this->dompdf->stream();
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			 
			$objParameterPantallaParaFacturar		= $this->core_web_parameter->getParameter("INVOICE_PANTALLA_FACTURACION",$this->session->get('user')->companyID);
			$objParameterPantallaParaFacturar		= $objParameterPantallaParaFacturar->value;
			
			
			
			//Renderizar Resultado
			$dataViewJava["objParameterPantallaParaFacturar"]	= $objParameterPantallaParaFacturar;
			$dataSession["notification"]						= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]								= $this->core_web_notification->get_message();
			$dataSession["head"]								= /*--inicio view*/ view('app_invoice_billing/list_head');//--finview
			$dataSession["footer"]								= /*--inicio view*/ view('app_invoice_billing/list_footer');//--finview
			$dataSession["body"]								= $dataViewRender; 
			$dataSession["script"]								= /*--inicio view*/ view('app_invoice_billing/list_script',$dataViewJava);//--finview
			$dataSession["script"]			                    = $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
		    
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
			  
			
			//Nuevo Registro
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
	
	
	function viewPrinterOpen(){
		try{			
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",APP_COMPANY);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinterOpen();
			
		}
		catch(\Exception $ex){
		    
			
		    //$data["session"]   = $dataSession;
		    //$data["exception"] = $ex;
		    //$data["urlLogin"]  = base_url();
		    //$data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    //$data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    //$resultView        = view("core_template/email_error_general",$data);
		    //return $resultView;
			
			exit($ex->getMessage());
		}	
	}
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mm(){
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
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mm($dataView);
			
		}
		catch(\Exception $ex){
		    
			
		    //$data["session"]   = $dataSession;
		    //$data["exception"] = $ex;
		    //$data["urlLogin"]  = base_url();
		    //$data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    //$data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    //$resultView        = view("core_template/email_error_general",$data);
		    //return $resultView;
			
			exit($ex->getMessage());
		}	
	}
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura58mm(){
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
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58mm($dataView);
		}
		catch(\Exception $ex){
		    
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura58mmChicharronesCarasenos(){
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
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58ChicharronesCarasenos($dataView);
		}
		catch(\Exception $ex){
		    
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectCocina80mm(){
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
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			
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
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCommandaCocina($dataView);
		}
		catch(\Exception $ex){
		    
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectCocina58mm(){
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
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			
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
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58mmCommandaCocina($dataView);
		}
		catch(\Exception $ex){
		    
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
	
	//facturacion estandar, horizontal tamaña a4
	function viewRegisterFormatoPaginaNormal80mm(){
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
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
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
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
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
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
					$detail_->itemName,  
					sprintf("%01.2f",round($detail_->quantity,2)), 
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
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
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
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
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => false]);
			
			//descargar
			//$this->dompdf->stream();
			
			
		}
		catch(\Exception $ex){
		    
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
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1(){
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
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
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
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
					$detail_->itemName,  
					sprintf("%01.2f",round($detail_->quantity,2)), 
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
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
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
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
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => false]);
			
			//descargar
			//$this->dompdf->stream();
			
			
		}
		catch(\Exception $ex){
		    
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
	//facturacion estandar, horizontal tamaña a4
	function viewRegisterFormatoPaginaNormal58mm(){
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
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			//Configurar Detalle
			$confiDetalleHeader = array();
			$row = array(
			    "style"		=>"text-align:left;width:auto",
			    "colspan"	=>'1',
			    "prefix"	=>'',
			    
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'3',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
			    "style"		=>"text-align:left;width:50px",
			    "colspan"	=>'1',
			    "prefix"	=>'',
			    
			    "style_row_data"		=>"text-align:right;width:auto",
			    "colspan_row_data"		=>'2',
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
			    "prefix_row_data"		=>$datView["objCurrency"]->simbol,
			    "nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", '', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
			        $detail_->itemName,  
			        "cant:".round($detail_->quantity,2), 
			        round($detail_->amount,2));
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte58mmTransactionMaster(
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
			    $objParameterTelefono
			    );
			$this->dompdf->loadHTML($html);
			
			//1cm = 29.34666puntos
			//a4: 210 ancho x 297
			//a4: 21cm x 29.7cm
			//a4: 595.28puntos x 841.59puntos
			
			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->setPaper(array(0,0,234.76,6000));
			
			$this->dompdf->render();
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => false]);
			
			//descargar
			//$this->dompdf->stream();
			
			
		}
		catch(\Exception $ex){
		    
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
	
	//facturacion estandar, horizontal tamaña a4
	function viewRegisterFormatoPaginaCocina80mm(){
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
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
		
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);					
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
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
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["objNatural"]						= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustumer"]->branchID,$dataView["objCustumer"]->entityID);
			$dataView["tipoCambio"]						= round($dataView["objTransactionMaster"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:auto",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:left;width:50px",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:50px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;width:70px",
			    "colspan"=>'1',
			    "prefix"=>$dataView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;width:70px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>"",
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
		    
		    $detalle = array();		    
		    $row = array("Elaborar", '', "");
		    array_push($detalle,$row);
		    
		    
			foreach($dataView["objTransactionMasterDetail"] as $detail_){
			    $row = array(
					$detail_->itemName,  
					1, /*round($detail_->quantity,2),*/ 
					"" /*round($detail_->amount,2)*/
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmCocina(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $dataView["objTransactionMaster"],
			    $dataView["objNatural"],
			    $dataView["objCustumer"],
			    $dataView["tipoCambio"],
			    $dataView["objCurrency"],
			    $dataView["objTransactionMasterInfo"],
			    $confiDetalle,
			    $detalle,
			    $objParameterTelefono,
				"",
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
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => false]);
			
			//descargar
			//$this->dompdf->stream();
			
			
		}
		catch(\Exception $ex){
		    
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
	
	//facturacion estandar, horizontal tamaña a4
	function viewRegisterFormatoPaginaCocina58mm(){
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
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
		
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);					
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
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
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["objNatural"]						= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustumer"]->branchID,$dataView["objCustumer"]->entityID);
			$dataView["tipoCambio"]						= round($dataView["objTransactionMaster"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:auto",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:left;width:50px",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:50px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;width:70px",
			    "colspan"=>'1',
			    "prefix"=>$dataView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;width:70px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>"",
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
		    
		    $detalle = array();		    
		    $row = array("Elaborar", '', "");
		    array_push($detalle,$row);
		    
		    
			foreach($dataView["objTransactionMasterDetail"] as $detail_){
			    $row = array(
					$detail_->itemName,  
					1, /*round($detail_->quantity,2),*/ 
					"" /*round($detail_->amount,2)*/
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte58mmCocina(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $dataView["objTransactionMaster"],
			    $dataView["objNatural"],
			    $dataView["objCustumer"],
			    $dataView["tipoCambio"],
			    $dataView["objCurrency"],
			    $dataView["objTransactionMasterInfo"],
			    $confiDetalle,
			    $detalle,
			    $objParameterTelefono,
				"",
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
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => false]);
			
			//descargar
			//$this->dompdf->stream();
			
			
		}
		catch(\Exception $ex){
		    
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
	
}
?>