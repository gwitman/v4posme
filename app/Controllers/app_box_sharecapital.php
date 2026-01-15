<?php
//posme:2023-02-27
namespace App\Controllers;
class app_box_sharecapital extends _BaseController {
    
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
				$this->response->redirect(base_url()."/".'app_box_sharecapital/add');	
			} 		
			
			
			//Obtener el componente de Item
			$objComponentCustomer					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Componente de facturacion
			$objComponentTransactionShareCapital	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponentTransactionShareCapital)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
		
			//Componente de facturacion
			$objComponentCustomerCreditDocument		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCustomerCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");

			$objComponentItem						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			
			$urlPrinterDocument					= $this->core_web_parameter->getParameter("BOX_SHARECAPITAL_URL_PRINTER",$companyID);
			//Tipo de Factura
			$dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objComponentCustomerCreditDocument"]		= $objComponentCustomerCreditDocument;
			$dataView["objComponentTransactionShareCapital"]	= $objComponentTransactionShareCapital;
			$dataView["objComponentCustomer"]					= $objComponentCustomer;
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_share_capital","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objComponentItem"]		= $objComponentItem;

			$objListComanyParameter								= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]			= $objParameterCantidadItemPoup->value;

			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_sharecapital/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_sharecapital/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_sharecapital/edit_script',$dataView);//--finview
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
				
				
			//Si el documento esta aplicado crear el contra documento
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			$objComponentShareCapital			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponentShareCapital)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentCreditDocument			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			
			$objComponentCustomer				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			$typeAmortizationAmericanoID			= $this->core_web_parameter->getParameter("CXC_AMERICANO",$companyID)->value;
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			
			
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
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro
			$objTMNew["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM->currencyID2,$objTM->currencyID);
			$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"] 						= 0;
			
			//Ingresar Informacion Adicional
			$objTMInfoNew["companyID"]					= $objTM->companyID;
			$objTMInfoNew["transactionID"]				= $objTM->transactionID;
			$objTMInfoNew["transactionMasterID"]		= $transactionMasterID;
			$objTMInfoNew["zoneID"]						= 0;
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			
			$db=db_connect();
			$db->transStart();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				$this->Transaction_Master_Info_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
			}
			
			
			//Actualizar Detalle
			$arrayListCustomerCreditDocumentID			= /*inicio get post*/ $this->request->getPost("txtDetailCustomerCreditDocumentID");
			$arrayListShare	 							= /*inicio get post*/ $this->request->getPost("txtDetailShare");
			$arrayListTransactionDetailID				= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");
			$amount 									= 0;
			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$arrayListTransactionDetailID);
			
			
			if(!empty($arrayListTransactionDetailID)){
				foreach($arrayListTransactionDetailID as $key => $value){			
					$customerCreditDocumentID				= $arrayListCustomerCreditDocumentID[$key];
					$share									= helper_StringToNumber($arrayListShare[$key]);
					$transactionDetailID					= $arrayListTransactionDetailID[$key];
					$objCustomerCreditDocument				= $this->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
					$objCustomerCreditLine					= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID); /*customerCreditLineID*/
					
					//Nuevo Detalle
					if($transactionDetailID == 0){						
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $objTM->companyID;
						$objTMD["transactionID"] 				= $objTM->transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentCreditDocument->componentID;
						$objTMD["componentItemID"] 				= $customerCreditDocumentID;
						$objTMD["quantity"] 					= 0;
						$objTMD["unitaryCost"]					= 0;
						$objTMD["cost"] 						= 0;
						
						$objTMD["unitaryPrice"]					= 0;
						$objTMD["unitaryAmount"]				= 0;
						$objTMD["amount"] 						= $share;
						$objTMD["discount"]						= 0;					
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["reference1"]					= $objCustomerCreditDocument->documentNumber;
						
						//Obtener balance anterior
						if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
						$objTMD["reference2"]					= $objCustomerCreditDocument->balance;
						else
						$objTMD["reference2"]					= $objCustomerCreditDocument->balanceNew;					
					
					
						$objTMD["reference3"]					= NULL;
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["expirationDate"]				= NULL;
						$objTMD["inventoryWarehouseSourceID"]	= $objTM->sourceWarehouseID;
						$objTMD["inventoryWarehouseTargetID"]	= $objTM->targetWarehouseID;						
						$amount 								= $amount + $objTMD["amount"];						
						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					}					
					//Editar Detalle
					else{
						
						$objTMD 								= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionDetailID);						
						$objTMDNew 								= null;
					
						$objTMDNew["amount"] 					= $share;
						$objTMDNew["reference1"]				= $objCustomerCreditDocument->documentNumber;
						
						//Obtener balance anterior
						if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
						$objTMDNew["reference2"]					= $objCustomerCreditDocument->balance;
						else
						$objTMDNew["reference2"]					= $objCustomerCreditDocument->balanceNew;					
										
						$objTMDNew["reference3"]				= NULL;
						$objTMDNew["exchangeRateReference"]		= $objCustomerCreditDocument->exchangeRate;
						$objTMDNew["descriptionReference"]		= '{componentID:"Componente de los documentos de credito",componentItemID:"Id del documento de credito",reference1:"Numero del desembolso",refernece2:"balance anterior",reference4:"balance nuevo",exchangeRateReference:"Tasa de cambio del desembolso"}';
						$amount 								= $amount + $objTMDNew["amount"];
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objTMDNew);
					}
					
					
				}
			}			
			
			//Actualizar Transaccion			
			$objTMNew["amount"] = $amount;			
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
						
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share_capital","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] ){
				
				
				//Recorrer Facturas
				$objListTMD = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
				if($objListTMD)
				foreach($objListTMD as $key => $objTMD){
					
					//documento inicial
					$objCustomerCreditDocumentInicial			= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);					
					
					//aplicar
					$this->core_web_amortization->shareCapital($companyID,$objTMD->componentItemID,$objTMD->amount);
					
					//documento final
					$objCustomerCreditDocument					= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);					
					
					//Actualizar detlle de credito
					$objTMDC["transactionMasterID"]				= $objTMD->transactionMasterID;
					$objTMDC["transactionMasterDetailID"]		= $objTMD->transactionMasterDetailID;
					$objTMDC["capital"]							= ($objCustomerCreditDocumentInicial->balance - $objCustomerCreditDocument->balance);
					$objTMDC["interest"]						= $objTMD->amount - $objTMDC["capital"];
					$objTMDC["dayDalay"]						= 0;
					$objTMDC["interestMora"]					= 0;
					$objTMDC["currencyID"]						= $objTM->currencyID;
					$objTMDC["exchangeRate"]					= $objTMNew["exchangeRate"];
					$objTMDC["reference1"]						= NULL;
					$objTMDC["reference2"]						= NULL;
					$objTMDC["reference3"]						= NULL;
					$objTMDC["reference4"]						= NULL;
					$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
					
					
					$objCustomer								= $this->Customer_Model->get_rowByEntity($companyID,$objTMNew["entityID"]);					
					$objTMFactura 								= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$objCustomerCreditDocument->documentNumber);/*invoiceNumber*/
					$objCustomerCreditLine 						= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID); /*customerCreditLineID*/
					$objCustomerCredit							= $this->Customer_Credit_Model->get_rowByPK($companyID,$objCustomer->branchID,$objCustomer->entityID);
					$montoAbono									= $objTMDC["capital"];
					$montoAbonoDolares							= $objTMFactura->exchangeRate > 1 ? /*cordoba a dolares*/ ($objTMDC["capital"] * round(1/round($objTMFactura->exchangeRate,4),4)) : $objTMDC["capital"];
					$montoAbonoCordobas							= $objTMFactura->exchangeRate < 1 ? /*dolares a cordoba*/ ($objTMDC["capital"] / round($objTMFactura->exchangeRate,4)) : $objTMDC["capital"];
					
					
					//actualizar saldo general del cliente
					$objCustomerCreditNew["balanceDol"]			= $objCustomerCredit->balanceDol + $montoAbonoDolares;
					$this->Customer_Credit_Model->update_app_posme($companyID,$objCustomer->branchID,$objCustomer->entityID,$objCustomerCreditNew);
					
						
					//actualizar saldo de la linea
					//linea dolares y factura dolares					
					//linea cordoba y factura cordoba
					if($objCustomerCreditLine->currencyID == $objTMFactura->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance + $montoAbono;
					
					//linea en dolares factura en cordoba
					if($objCustomerCreditLine->currencyID == $objCurrencyDolares->currencyID && $objTMFactura->currencyID != $objCurrencyDolares->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance + $montoAbonoDolares;
						
					//linea en cordoba factura en dolares
					if($objCustomerCreditLine->currencyID != $objCurrencyDolares->currencyID && $objTMFactura->currencyID == $objCurrencyDolares->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance + $montoAbonoCordobas;
					
					
					//actualizar linea
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
					
					
					//actualizar saldo del recibo
					$objTMDNew									= NULL;
					if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
					$objTMDNew["reference4"]					= $objCustomerCreditDocument->balance;
					else
					$objTMDNew["reference4"]					= $objCustomerCreditDocument->balanceNew;
				
					//actualizar saldo del recibo
					$this->Transaction_Master_Detail_Model->update_app_posme($objTMD->companyID,$objTMD->transactionID,$objTMD->transactionMasterID,$objTMD->transactionMasterDetailID,$objTMDNew);
					
					
				}
				
				//Crear Conceptos.
				$this->core_web_concept->shareCapital($companyID,$transactionID,$transactionMasterID);
				
			}
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_box_sharecapital/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_box_sharecapital/add');	
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
			
		    echo $resultView;
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
			
				
			
			
			
			
				
				
			
			
			
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShareCapital			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponentShareCapital)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentCreditDocument			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			
			$objComponentCustomer				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_share_capital",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_share_capital",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShareCapital->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
			$objTM["currencyID2"]					= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTM["reference4"] 					= '';
			$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["amount"] 						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtTotal'));
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$pathDocument = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShareCapital->componentID."/component_item_".$transactionMasterID;			
			if(!file_exists ($pathDocument))
			{
				mkdir( $pathDocument,0700, true);
			}
			
			
			//Ingresar Informacion Adicional
			$objTMInfo["companyID"]					= $objTM["companyID"];
			$objTMInfo["transactionID"]				= $objTM["transactionID"];
			$objTMInfo["transactionMasterID"]		= $transactionMasterID;
			$objTMInfo["zoneID"]					= 0;
			$objTMInfo["routeID"]					= 0;
			$objTMInfo["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfo["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfo["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			$this->Transaction_Master_Info_Model->insert_app_posme($objTMInfo);
			
			//Recorrer la lista del detalle del documento
			$arrayListCustomerCreditDocumentID			= /*inicio get post*/ $this->request->getPost("txtDetailCustomerCreditDocumentID");
			$arrayListShare	 							= /*inicio get post*/ $this->request->getPost("txtDetailShare");			
			$arrayListTransactionDetailID				= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");
			$amount 									= 0;
			
			if(!empty($arrayListCustomerCreditDocumentID)){
				foreach($arrayListCustomerCreditDocumentID as $key => $value){
					$customerCreditDocumentID				= $value;
					$share									= helper_StringToNumber($arrayListShare[$key]);
					$transactionDetailID					= $arrayListTransactionDetailID[$key];
					$objCustomerCreditDocument				= $this->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);
					
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentCreditDocument->componentID;
					$objTMD["componentItemID"] 				= $customerCreditDocumentID;
					$objTMD["quantity"] 					= 0;
					$objTMD["unitaryCost"]					= 0;
					$objTMD["cost"] 						= 0;
					
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= $share;
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $objCustomerCreditDocument->documentNumber;
					$objTMD["reference2"]					= NULL;
					$objTMD["reference3"]					= NULL;
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
					$amount 								= $amount + $objTMD["amount"];
					
					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
				}
			}
			
			//Actualizar Transaccion
			$objTM["amount"] = $amount;
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_box_sharecapital/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_box_sharecapital/add');	
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
			
		    echo $resultView;
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
			$this->validation->setRule("txtStatusID","Estado","required");
			$this->validation->setRule("txtDate","Fecha","required");
			
			 //Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_box_sharecapital/add');
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
				$this->response->redirect(base_url()."/".'app_box_sharecapital/add');
				exit;
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
			
			//Obtener el componente de Item
			$objComponentCustomer				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentCustomerCreditDocument	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCustomerCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");

			$objComponentItem					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_share_capital",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			
			//Tipo de Factura
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			
			$dataView["objComponentCustomer"]				= $objComponentCustomer;
			$dataView["objComponentCustomerCreditDocument"]	= $objComponentCustomerCreditDocument;
			$dataView["objCaudal"]							= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]				= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_share_capital","statusID",$companyID,$branchID,$roleID);
			$dataView["objComponentItem"]					= $objComponentItem;

			$objListComanyParameter							= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]		= $objParameterCantidadItemPoup->value;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_sharecapital/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_sharecapital/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_sharecapital/news_script',$dataView);//--finview
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share_capital");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_share_capital' NO EXISTE...");
			
			
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
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_sharecapital/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_box_sharecapital/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_box_sharecapital/list_script');//--finview
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
	
	function viewRegisterFormatoPaginaNormal(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);		
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objCurrency"]                 = $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share_capital","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			//Configurar Detalle			
			$confiDetalle = array();
			$row = array(
				"style"		=>"text-align:left;width:50%",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:50%",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalle,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:10%",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:left;width:10%",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalle,$row);
			
			$row = array(
				"style"		=>"text-align:right",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalle,$row);
			
			
			//Inicializar Detalle
			/*Calculo de saldos generales*/
			$detalle = array();
			
			foreach($datView["objTMD"] as $detail_){
				$row = array("MONTO", '', $datView["objCurrency"]->simbol.sprintf('%.2f',round($detail_->amount,2)));
				array_push($detalle,$row);
			}
			
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterShareCapital(
			    "ABONO AL CAPITAL",
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
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => !$objParameterShowLinkDownload]);
			exit;
			//descargar
			//$this->dompdf->stream();
			
			
			
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
	function viewRegisterFormatoPaginaTicket(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			
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
	function viewRegisterFidLocal(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			
			
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
	function viewRegisterVariedadesCarlosLuis(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			
			
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
	function viewRegisterInstitutoLaVid(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			
			
			
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
	
	
}
?>