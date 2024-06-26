<?php
//posme:2023-02-27
namespace App\Controllers;
class app_box_share extends _BaseController {
	
    
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
			 
			
			//Redireccionar datos
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
			if((!$companyID || !$transactionID  || !$transactionMasterID))
			{ 
				$this->response->redirect(base_url()."/".'app_box_share/add');	
			} 		
			
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
		
			//Obtener el componente del recolector de cobro
			$objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			//Componente de facturacion
			$objComponentTransactionShare	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share");
			if(!$objComponentTransactionShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share' NO EXISTE...");
		
			//Componente de facturacion
			$objComponentCustomerCreditDocument	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCustomerCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentAmortization			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_amoritization");
			if(!$objComponentAmortization)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_amoritization' NO EXISTE...");
			
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			
			$urlPrinterDocument					= $this->core_web_parameter->getParameter("BOX_SHARE_URL_PRINTER",$companyID);
			
			//Tipo de Factura
			$dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objComponentCustomerCreditDocument"]		= $objComponentCustomerCreditDocument;
			
			$dataView["company"]				= $dataSession["company"];
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objComponentShare"]		= $objComponentTransactionShare;
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			$dataView["objComponentEmployee"]	= $objComponentEmployee;			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_share","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objWorkflowStage"]		= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objNaturalDefault"]					= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);			
			$dataView["objLegalDefault"]					= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objParameterShareInvoiceByInvoice"] 	= $this->core_web_parameter->getParameterValue("SHARE_INVOICE_BY_INVOICE",$companyID);
			$dataView["company"]							= $dataSession["company"];
			$dataView["objListCustomer"]					= $this->Customer_Model->get_rowByCompany($companyID);
			$dataView["useMobile"]							= $dataSession["user"]->useMobile;
			$dataView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCurrencyDefault"]				= $this->core_web_currency->getCurrencyDefault($companyID);
			
			$dataView["objEmployeeDefault"]				= $this->Employee_Model->get_rowByEntityID($companyID,helper_RequestGetValue($dataView["objTransactionMaster"]->reference3,0));
			$dataView["objEmployeeNaturalDefault"]		= null;
			if($dataView["objEmployeeDefault"])			
			$dataView["objEmployeeNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objEmployeeDefault"]->branchID,$dataView["objEmployeeDefault"]->entityID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_share/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_share/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_share/edit_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			if( ! $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share' NO EXISTE...");
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentAmortization			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_amoritization");
			if(!$objComponentAmortization)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_amoritization' NO EXISTE...");
			
			
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
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtEmployeeID");//--fin peticion get o post
			$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post
			$objTMNew["descriptionReference"] 			= "reference1:input,reference2:input,reference3:Gestor de Cobro,reference4:Linea de credito del Cliente";
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"] 						= 0;
			$objTMNew["currencyID"]						= /*inicio get post*/ $this->request->getPost("txtCurrencyID"); 
			$objTMNew["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTMNew["currencyID"]);
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTMNew["currencyID2"],$objTMNew["currencyID"]);
			
			//Ingresar Informacion Adicional
			$objTMInfoNew["companyID"]					= $objTM->companyID;
			$objTMInfoNew["transactionID"]				= $objTM->transactionID;
			$objTMInfoNew["transactionMasterID"]		= $transactionMasterID;
			$objTMInfoNew["zoneID"]						= 0;
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			$objTMInfoNew["changeAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtChangeAmount"));
			$objTMInfoNew["reference1"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtBalanceStart"));
			$objTMInfoNew["reference2"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtBalanceFinish"));
			
			$db=db_connect();
			$db->transStart();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				$this->Transaction_Master_Info_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
			}
			
			
			//Actualizar Detalle
			$arrayListCustomerCreditDocumentID			= /*inicio get post*/ $this->request->getPost("txtDetailCustomerCreditDocumentID");//documentoid
			$arrayListTransactionDetailID				= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");//transaccion
			$arrayListTransactionDetailDocument			= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailDocument");//documento numero
			$arrayListTransactionDetailFecha			= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailFecha");//fecha
			$arrayListCustomerCreditAmortizationID		= /*inicio get post*/ $this->request->getPost("txtDetailAmortizationID");//amorization
			$arrayListShare	 							= /*inicio get post*/ $this->request->getPost("txtDetailShare");//monto del abono
			$abonoTotal									= 0;
			$amount 									= 0;
			
			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$arrayListTransactionDetailID); 
						
			//Si este valor esta en true, se tiene que seleccionar una factura a la ves, par ir abonando una a una
			//Si este valor es false, se puede seleccionar una sola factura, y el sistema va aplicado automaticamente, segun el monto.
			//Si este valor es false, ingreas a la funcion 
			if( 
				$this->core_web_parameter->getParameterValue("SHARE_INVOICE_BY_INVOICE",$companyID)
				== "false"
			)
			{
				
				//Sumar abono total.
				if($arrayListShare)
				{
					foreach($arrayListShare as $value)
					{
						$abonoTotal	= helper_StringToNumber($value) + $abonoTotal;
					}
				}
				
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID); 
				$objListDocumentoAmortization	 			= null;
				if(!empty($arrayListTransactionDetailID))
				{	
					
					
					if(count($arrayListTransactionDetailID) > 0)
					{
						
						$customerCreditDocumentIDMin				= $arrayListCustomerCreditDocumentID[0];
						$arrayListCustomerCreditDocumentID			= array();
						$arrayListTransactionDetailID				= array();
						$arrayListTransactionDetailDocument			= array();
						$arrayListTransactionDetailFecha			= array();
						$arrayListCustomerCreditAmortizationID		= array();
						$arrayListShare	 							= array();								
						$objListDocumentoAmortization				= $this->Customer_Credit_Document_Model->get_rowByBalancePending($companyID,$objTMNew["entityID"],$customerCreditDocumentIDMin,$objTMNew["currencyID"]);
						$objListDocumentoAmortizationBalanceTotal	= 0;
						//Obtener el banace total pendietne
						foreach($objListDocumentoAmortization as $it )
						{
							$objListDocumentoAmortizationBalanceTotal = $objListDocumentoAmortizationBalanceTotal + $it->remaingin;
						}
						
						if($objListDocumentoAmortizationBalanceTotal < $abonoTotal)
						{							
							throw new \Exception("ABONO, no puede ser aplicado por que supera el monto del saldo");
						}
						
						
						foreach($objListDocumentoAmortization as $it )
						{
							if($abonoTotal > $it->remaingin)
							{
									//agregar y disminuir
									$abonoTotal  = $abonoTotal - $it->remaingin;
									array_push($arrayListCustomerCreditDocumentID,$it->customerCreditDocumentID);
									array_push($arrayListTransactionDetailID,0);
									array_push($arrayListTransactionDetailDocument,$it->documentNumber);
									array_push($arrayListTransactionDetailFecha,$it->dateApply);
									array_push($arrayListCustomerCreditAmortizationID,$it->creditAmortizationID);
									array_push($arrayListShare, $it->remaingin);
									
							}
							else
							{
									array_push($arrayListCustomerCreditDocumentID,$it->customerCreditDocumentID);
									array_push($arrayListTransactionDetailID,0);
									array_push($arrayListTransactionDetailDocument,$it->documentNumber);
									array_push($arrayListTransactionDetailFecha,$it->dateApply);
									array_push($arrayListCustomerCreditAmortizationID,$it->creditAmortizationID);
									array_push($arrayListShare,$abonoTotal);
									$abonoTotal = 0;
									break;
							}						
						}
						
					}
				}
			}
			
			
			
			
			//Obtener cuanto abona totalmente			
			if(!empty($arrayListTransactionDetailID))
			{				
				foreach($arrayListTransactionDetailID as $key => $value){			
					$customerCreditDocumentID				= $arrayListCustomerCreditDocumentID[$key];
					$share									= helper_StringToNumber($arrayListShare[$key]);
					$transactionDetailID					= $arrayListTransactionDetailID[$key];
					$reference1Documento					= $arrayListTransactionDetailDocument[$key];
					$reference2Fecha						= $arrayListTransactionDetailFecha[$key];
					$refernece3AmortizationID				= $arrayListCustomerCreditAmortizationID[$key];
					
					
					
					//Nuevo Detalle
					if($transactionDetailID == 0)
					{	
						$objCustomerCreditDocument				= $this->Customer_Credit_Document_Model->get_rowByPK($customerCreditDocumentID);						
						$objCustomerCreditLine					= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID); /*customerCreditLineID*/
						$objCustomerAmortization				= $this->Customer_Credit_Amortization_Model->get_rowByPK($refernece3AmortizationID);
						
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $objTM->companyID;
						$objTMD["transactionID"] 				= $objTM->transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentShare->componentID;
						$objTMD["componentItemID"] 				= $customerCreditDocumentID;
						$objTMD["quantity"] 					= 0;
						$objTMD["unitaryCost"]					= 0;
						$objTMD["cost"] 						= 0;
						
						$objTMD["unitaryPrice"]					= 0;
						$objTMD["unitaryAmount"]				= 0;
						$objTMD["amount"] 						= $share;
						$objTMD["discount"]						= 0;					
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["reference1"]					= $reference1Documento;
						
						//Obtener balance anterior
						if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
						$objTMD["reference2"]					= $objCustomerCreditDocument->balance;
						else
						$objTMD["reference2"]					= $objCustomerCreditDocument->balanceNew;					
						
						
						$objTMD["reference3"]					= $refernece3AmortizationID;
						$objTMD["reference5"]					= $objCustomerCreditDocument->dateOn;
						$objTMD["reference6"]					= $objCustomerCreditDocument->dateFinish;
						$objTMD["reference7"]					= $objCustomerAmortization->dateApply;
						
						//Verificar los dias de atraso
						$hoy									= (new \DateTime())->format("Y-m-d");
						$hoy									= \DateTime::createFromFormat('Y-m-d',$hoy);
						$cuotaDate								= \DateTime::createFromFormat('Y-m-d', $objCustomerAmortization->dateApply );
						$cuotaDif								= $hoy->diff($cuotaDate);
						$cuotaDif								= $cuotaDif->days;
						$objTMD["lote"]							= $cuotaDif;
						
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
					else
					{	
											
						$objTMD 								= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objComponentShare->componentID);												
						$objTMDNew 								= null;
						$objCustomerCreditDocument				= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);
						$objCustomerCreditLine					= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID); 
						$objCustomerAmortization				= $this->Customer_Credit_Amortization_Model->get_rowByPK($refernece3AmortizationID);
						
						$objTMDNew["amount"] 					= $share;
						$objTMDNew["reference1"]				= $reference1Documento;
						
						//Obtener balance anterior
						if($typeAmortizationAmericanoID == $objCustomerCreditLine->typeAmortization)
						$objTMDNew["reference2"]					= $objCustomerCreditDocument->balance;
						else
						$objTMDNew["reference2"]					= $objCustomerCreditDocument->balanceNew;					
						
						$objTMDNew["reference3"]				= $refernece3AmortizationID;
						$objTMDNew["reference5"]				= $objCustomerCreditDocument->dateOn;
						$objTMDNew["reference6"]				= $objCustomerCreditDocument->dateFinish;
						$objTMDNew["reference7"]				= $objCustomerAmortization->dateApply;
						
						//Verificar los dias de atraso
						$hoy									= (new \DateTime())->format("Y-m-d");
						$hoy									= \DateTime::createFromFormat('Y-m-d',$hoy);
						$cuotaDate								= \DateTime::createFromFormat('Y-m-d', $objCustomerAmortization->dateApply );
						$cuotaDif								= $hoy->diff($cuotaDate);
						$cuotaDif								= $cuotaDif->days;
						$objTMDNew["lote"]						= $cuotaDif;
						
						
						$objTMDNew["exchangeRateReference"]		= $objCustomerCreditDocument->exchangeRate;
						$objTMDNew["descriptionReference"]		= '{componentID:"Componente de transacciones de cuotas",componentItemID:"Id del documento de credito",reference1:"Numero del desembolso",refernece2:"balance anterior",refernece3:"Id de la amortizacion",reference4:"balance nuevo",exchangeRateReference:"Tasa de cambio del desembolso",referece5:"Fecha Inical de la deuda",reference6:"Fecha Final de la deuda",reference7:"dia que tocaba la cuota",lote:"Dias de atraso de la cuota"}';
						$amount 								= $amount + $objTMDNew["amount"];
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objTMDNew);
					}
					
					
				}
			}	
			
			
			
			//Actualizar Transaccion			
			$objTMNew["amount"] = $amount;			
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
						
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] ){
				
				
				//Recorrer Facturas para Actualizar Balances
				$objListTMD = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
				if($objListTMD)
				foreach($objListTMD as $key => $objTMD){
					
					//documento inicial
					$objCustomerCreditDocumentInicial			= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);					
					
					//aplicar
					$this->core_web_amortization->applyCuote($companyID,$objTMD->componentItemID,$objTMD->amount,$objTMD->reference3);
					
					//documento final
					$objCustomerCreditDocument					= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);					
					
					
					//capital
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
					$objTMFactura 								= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$objTMD->reference1);/*invoiceNumber*/
					$objCustomerCreditLine 						= $this->Customer_Credit_Line_Model->get_rowByPK($objTMFactura->reference4); /*customerCreditLineID*/
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
				$this->core_web_concept->share($companyID,$transactionID,$transactionMasterID);
				
				
			}
			
			if($db->transStatus() !== false)
			{
				
				$db->transCommit();						
				
				$this->core_web_notification->set_message(false,SUCCESS);
				
				$this->response->redirect(base_url()."/".'app_box_share/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
				
			}
			else
			{
				$db->transRollback();	
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_box_share/add');	
			}
			
			
		}
		catch(\Exception $ex){			
			exit($ex->getMessage());
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share' NO EXISTE...");
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentAmortization			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_amoritization");
			if(!$objComponentAmortization)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_amoritization' NO EXISTE...");
			
			
			$objComponentCustomer				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_share",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_share",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShare->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID"); 
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTM["reference3"] 					= '';
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
			$filterPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$transactionMasterID;
			if (!file_exists($filterPath))			
			mkdir($filterPath, 0777,true);
			
			//Ingresar Informacion Adicional
			$objTMInfo["companyID"]					= $objTM["companyID"];
			$objTMInfo["transactionID"]				= $objTM["transactionID"];
			$objTMInfo["transactionMasterID"]		= $transactionMasterID;
			$objTMInfo["zoneID"]					= 0;
			$objTMInfo["routeID"]					= 0;
			$objTMInfo["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfo["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfo["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			$objTMInfo["reference1"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtBalanceStart"));
			$objTMInfo["reference2"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtBalanceFinish"));
			$this->Transaction_Master_Info_Model->insert_app_posme($objTMInfo);
			
			//Recorrer la lista del detalle del documento
			$arrayListCustomerCreditDocumentID			= /*inicio get post*/ $this->request->getPost("txtDetailCustomerCreditDocumentID");
			$arrayListTransactionDetailDocument			= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailDocument");
			$arrayListTransactionDetailFecha			= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailFecha");
			$arrayListCustomerCreditAmortizationID		= /*inicio get post*/ $this->request->getPost("txtDetailAmortizationID");
			$arrayListShare	 							= /*inicio get post*/ $this->request->getPost("txtDetailShare");			
			$arrayListTransactionDetailID				= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");
			$arrayListBalanceStart						= /*inicio get post*/ $this->request->getPost("txtDetailBalanceStart");
			$amount 									= 0;
			
			if(!empty($arrayListCustomerCreditDocumentID)){
				foreach($arrayListCustomerCreditDocumentID as $key => $value){
					$customerCreditDocumentID				= $value;
					$share									= helper_StringToNumber($arrayListShare[$key]);
					$transactionDetailID					= $arrayListTransactionDetailID[$key];
					$reference1Documento					= $arrayListTransactionDetailDocument[$key];
					$reference2BalanceStart					= $arrayListBalanceStart[$key];
					$refernece3AmortizationID 				= $arrayListCustomerCreditAmortizationID[$key];
					
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentShare->componentID;
					$objTMD["componentItemID"] 				= $customerCreditDocumentID;
					$objTMD["quantity"] 					= 0;
					$objTMD["unitaryCost"]					= 0;
					$objTMD["cost"] 						= 0;
					
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= $share;
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $reference1Documento;
					$objTMD["reference2"]					= $reference2BalanceStart;
					$objTMD["reference3"]					= $refernece3AmortizationID;
					$objTMD["reference4"]					= 0;
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
				$this->response->redirect(base_url()."/".'app_box_share/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_box_share/add');	
			}
			
			
		}
		catch(\Exception $ex){
			
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
				$this->response->redirect(base_url()."/".'app_box_share/add');
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
				$this->response->redirect(base_url()."/".'app_box_share/add');
				exit;
			}
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentCustomerCreditDocument	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objComponentCustomerCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_share",0);
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
			$dataView["useMobile"]				= $dataSession["user"]->useMobile;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			
			$objParameterExchangePurchase		= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID);
			$dataView["exchangeRatePurchase"]	= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) - $objParameterExchangePurchase->value;			
			$objParameterExchangeSales			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID);
			$dataView["exchangeRateSale"]		= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) + $objParameterExchangeSales->value;		
			
			$dataView["company"]							= $dataSession["company"];
			$dataView["objComponentCustomer"]				= $objComponentCustomer;
			$dataView["objComponentCustomerCreditDocument"]	= $objComponentCustomerCreditDocument;
			$dataView["objCaudal"]							= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]				= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_share","statusID",$companyID,$branchID,$roleID);
			$dataView["company"]							= $dataSession["company"];
			$dataView["objListCustomer"]					= $this->Customer_Model->get_rowByCompany($companyID);
			$dataView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCurrencyDefault"]				= $this->core_web_currency->getCurrencyDefault($companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_share/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_share/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_share/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_share' NO EXISTE...");
			
			
			//Vista por defecto PC
			if($dataViewID == null )
			{				
				$targetComponentID			= $this->session->get('company')->flavorID;				
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;				
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				
				if(!$dataViewData){
					
					$targetComponentID			= 0;	
					$parameter["{companyID}"]	= $this->session->get('user')->companyID;					
					$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);
				}
				
				if(  $this->request->getUserAgent()->isMobile()  )
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
			//Vista Por Id
			else 
			{
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 							
				if(  $this->request->getUserAgent()->isMobile() )
				{
					$dataViewRender				= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
				}
				else 
				{
					$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
				}
				
			}
			
			//Renderizar Resultado
			$dataViewHeader["company"]		= $dataSession["company"];
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID,$dataViewHeader);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_share/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_box_share/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_box_share/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
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
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objProvider"]					= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objLegal"]					= $datView["objProvider"] != NULL ? $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID) : NULL;
			$datView["objWarehouse"]				= $this->Warehouse_Model->get_rowByPK($companyID,0);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$employeeID);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$employeeID);
			
			
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			log_message("error",print_r($datView["objTMD"] ,true));
			
			
			/*Calculo de saldos generales*/
			$saldoInicialGeneral = round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   = round($datView["objTMI"]->reference2,0);
			
			$saldoInicial 	= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 	= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			
			$datView["objPropertySaldoInicial"]	= $saldoInicial;
			$datView["objPropertySaldoFincal"] 	= $saldoFinal;
			$datView["objPropertySaldoAbonos"]  = array();
			$datView["objPropertyAbonosTotal"]  = $saldoAbonado;
				
			$detalleShare= array();
			if($saldos != "Basico"){
				$row = array("SALDO INICIAL", '', sprintf('%.2f', $saldoInicial) );
				array_push($datView["objPropertySaldoAbonos"],$row);
				
				foreach($datView["objTMD"] as $detail_)
				{
					$row = array("ABONO", '', sprintf('%.2f',round($detail_->amount,2)));
					array_push($datView["objPropertySaldoAbonos"],$row);	
				
				}
				
				$row = array("SALDO FINAL", '', sprintf('%.2f', $saldoFinal) );
				array_push($datView["objPropertySaldoAbonos"],$row);
			}
			if ($saldos == "Basico")
			{
				$row = array("SALDO INICIAL", '', sprintf('%.2f', $saldoInicial) );
				array_push($datView["objPropertySaldoAbonos"],$row);
				
				$row = array("ABONO", '', sprintf('%.2f', $saldoAbonado) );
				array_push($datView["objPropertySaldoAbonos"],$row);	
				
				$row = array("SALDO FINAL", '', sprintf('%.2f', $saldoFinal) );
				array_push($datView["objPropertySaldoAbonos"],$row);
				
			}
			
			
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterShareGlobalPro(
			    "ABONO DE CAJA",
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
				"",
				$datView["objPropertySaldoInicial"],
				$datView["objPropertySaldoFincal"],
				$datView["objPropertySaldoAbonos"],
				$datView["objPropertyAbonosTotal"]
			);
			
			
			$this->dompdf->loadHTML($html);
			
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_64/component_item_".$transactionMasterID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);
				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_64/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download compra</a>
				"; 				
			
			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true ]);
			}
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri		
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
									
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share' NO EXISTE...");
		
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			
			//Get Documento				
			//Obtener Datos
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
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:50%",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:50%",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			   
			);			
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:left;width:10%",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:10%",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;",
			    "colspan"=>'1',
			    "prefix"=>$datView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>$datView["objCurrency"]->simbol,
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			$detalle = array();
			$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol. sprintf("%.2f",$saldoInicial));
			array_push($detalle,$row);
			
			foreach($datView["objTMD"] as $detail_){
				$row = array("ABONO", '', sprintf("%.2f",round($detail_->amount,2)));
				array_push($detalle,$row);
			}
			
			$row = array("SALDO FINAL", '', sprintf("%.2f",$saldoFinal));
			array_push($detalle,$row);
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
			    "ABONO",
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
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => !$objParameterShowLinkDownload]);
			
			//descargar
			//$this->dompdf->stream();
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function viewRegisterPosMe(){
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri		
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
									
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share' NO EXISTE...");
		
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			
			//Get Documento				
			//Obtener Datos
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
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array("style"=>"text-align:left;width:40%","colspan"=>'1',"prefix"=>'');			
			array_push($confiDetalle,$row);
			$row = array("style"=>"text-align:left;width:30%","colspan"=>'1',"prefix"=>'');
			array_push($confiDetalle,$row);
			$row = array("style"=>"text-align:right;","colspan"=>'1',"prefix"=>$datView["objCurrency"]->simbol);
			array_push($confiDetalle,$row);
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			$detalle 	  = array();
			//$row = array("SALDO INICIAL", '', $saldoInicial);
			//array_push($detalle,$row);
			//
			//foreach($datView["objTMD"] as $detail_){
			//	$row = array("ABONO", '', round($detail_->amount,2));
			//	array_push($detalle,$row);
			//}
			//
			//$row = array("SALDO FINAL", '', $saldoFinal);
			//array_push($detalle,$row);
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterPosMe(
			    "RECIBO",
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
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => !$objParameterShowLinkDownload]);
			
			//descargar
			//$this->dompdf->stream();
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Configurar Detalle			
			$confiDetalle = array();
			if($saldos != "Basico"){
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
			}
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			/*Calculo de saldos generales*/
			$saldoInicialGeneral = round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   = round($datView["objTMI"]->reference2,0);
			
			$saldoInicial 	= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 	= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			
			$detalle = array();
			if($saldos != "Basico"){
				$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol. sprintf("%.2f", $saldoInicial));
				array_push($detalle,$row);
				
				foreach($datView["objTMD"] as $detail_){
					$row = array("ABONO", '', sprintf('%.2f',round($detail_->amount,2)));
					array_push($detalle,$row);
				}
				
				$row = array("SALDO FINAL", '', sprintf('%.2f', $saldoFinal) );
				array_push($detalle,$row);
			}
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
			    "ABONO",
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
			$nameFileDownload				= date("YmdHis").".pdf";
			
			//visualizar
			$this->response->setContentType('application/pdf');
			$objParameterShowLinkDownload 	= $objParameterShowLinkDownload == "false" ? true : false;
			$this->dompdf->stream($nameFileDownload	, ['Attachment' => $objParameterShowLinkDownload]);
			
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
	
	function viewRegisterFormatoPaginaTicketFunBlandon(){
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Configurar Detalle			
			$confiDetalle = array();
			if($saldos != "Basico"){
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
					"prefix_row_data"		=>"",
					"nueva_fila_row_data"	=>0
				);
				array_push($confiDetalle,$row);
			}
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			/*Calculo de saldos generales*/
			$saldoInicialGeneral = round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   = round($datView["objTMI"]->reference2,0);
			
			$saldoInicial 	= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 	= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			
			$detalle = array();
			if($saldos != "Basico"){
				
				
				
				$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol." ".sprintf("%.2f", $saldoInicial));
				array_push($detalle,$row);
				
				foreach($datView["objTMD"] as $detail_){
					
					$row = array("APERTURA", '', $detail_->reference5 );
					array_push($detalle,$row);
					
					$row = array("FINALIZACION", '', $detail_->reference6);
					array_push($detalle,$row);
					
					
					$row = array("MORA", '', ($detail_->lote > 0 ? "0" :  $detail_->lote) );
					array_push($detalle,$row);
					
					
					$row = array("ABONO", '', sprintf('%.2f',round($detail_->amount,2)));
					array_push($detalle,$row);
				}
				
				$row = array("SALDO FINAL", '', sprintf('%.2f', $saldoFinal) );
				array_push($detalle,$row);
			}
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterFunBlandon(
			    "ABONO",
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
			$nameFileDownload				= date("YmdHis").".pdf";
			
			//visualizar
			$this->response->setContentType('application/pdf');
			$objParameterShowLinkDownload 	= $objParameterShowLinkDownload == "false" ? true : false;
			$this->dompdf->stream($nameFileDownload	, ['Attachment' => $objParameterShowLinkDownload]);
			
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
	
	function viewRegisterFormatoPaginaTicketKhadash(){
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID,$datView["objTM"]->createdBy);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);			
			$datView["objCCD"] 						= $this->Customer_Credit_Document_Model->get_rowByDocument($datView["objTM"]->companyID,$datView["objTM"]->entityID,$datView["objTMD"][0]->reference1);						
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objCurrency"]                 = $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Configurar Detalle			
			$confiDetalle = array();
			if($saldos != "Basico"){
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
			}
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			/*Calculo de saldos generales*/
			$saldoInicialGeneral = round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   = round($datView["objTMI"]->reference2,0);
			
			$saldoInicial 	= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 	= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			
			$detalle = array();
			if($saldos != "Basico"){
				$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol." ". sprintf("%.2f", $saldoInicial));
				array_push($detalle,$row);
				
				foreach($datView["objTMD"] as $detail_){
					$row = array("ABONO", '', sprintf('%.2f',round($detail_->amount,2)));
					array_push($detalle,$row);
				}
				
				$row = array("SALDO PENDIENTE", '', sprintf('%.2f', $saldoFinal) );
				array_push($detalle,$row);
			}
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterAbonoKhadash(
			    "ABONO",
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
				$datView["objUser"],
				$datView["objCCD"],
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
			$nameFileDownload				= date("YmdHis").".pdf";
			
			//visualizar
			$this->response->setContentType('application/pdf');
			$objParameterShowLinkDownload 	= $objParameterShowLinkDownload == "false" ? true : false;
			$this->dompdf->stream($nameFileDownload	, ['Attachment' => $objParameterShowLinkDownload]);
			
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
	
	function viewRegisterFormatoPaginaTicket80mmCarlosLuis(){
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Configurar Detalle			
			$confiDetalle = array();
			if($saldos != "Basico"){
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
			}
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			/*Calculo de saldos generales*/
			$saldoInicialGeneral = round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   = round($datView["objTMI"]->reference2,0);
			
			$saldoInicial 	= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 	= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			
			$detalle = array();
			if($saldos != "Basico"){
				$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol. sprintf("%.2f", $saldoInicial));
				array_push($detalle,$row);
				
				foreach($datView["objTMD"] as $detail_){
					$row = array("ABONO", '', sprintf('%.2f',round($detail_->amount,2)));
					array_push($detalle,$row);
				}
				
				$row = array("SALDO FINAL", '', sprintf('%.2f', $saldoFinal) );
				array_push($detalle,$row);
			}
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterShareCarlosLuis(
			    "ABONO",
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
			$this->response->setContentType('application/pdf');
			$objParameterShowLinkDownload 	= $objParameterShowLinkDownload == "false" ? true : false;
			$this->dompdf->stream("file.pdf", ['Attachment' => $objParameterShowLinkDownload]);
			
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
	function viewRegisterFormatoPaginaTicketFBlandon(){
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objDocumentAmortization"]		= $this->Customer_Credit_Amortization_Model->get_rowBySummaryInformationCredit($datView["objTMD"][0]->reference1);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objEmployeer"]				= $this->Employee_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objUser"]->employeeID);
			$datView["objEmployeerNatural"]			= $this->Natural_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objUser"]->employeeID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objCurrency"]                 = $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Configurar Detalle			
			$confiDetalle = array();
			if($saldos != "Basico"){
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
			}
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			/*Calculo de saldos generales*/
			$saldoInicialGeneral = round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   = round($datView["objTMI"]->reference2,0);
			
			$saldoInicial 	= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 	= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			
			$detalle = array();
			if($saldos != "Basico"){
				$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol. sprintf("%.2f", $saldoInicial));
				array_push($detalle,$row);
				
				foreach($datView["objTMD"] as $detail_){
					$row = array("ABONO", '', sprintf('%.2f',round($detail_->amount,2)));
					array_push($detalle,$row);
				}
				
				$row = array("SALDO FINAL", '', sprintf('%.2f', $saldoFinal) );
				array_push($detalle,$row);
			}
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterFcBlandon(
			    "ABONO",
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
				$datView["objEmployeer"],
				$datView["objEmployeerNatural"],
				$datView["objDocumentAmortization"],
				$datView["objStage"][0]->display,
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
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			//visualizar
			$this->response->setContentType('application/pdf');
			$objParameterShowLinkDownload 	= $objParameterShowLinkDownload == "false" ? true : false;
			$this->dompdf->stream("file.pdf", ['Attachment' => $objParameterShowLinkDownload]);
			
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
	function viewRegisterFormatoPaginaTicket58mm(){
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Configurar Detalle			
			$confiDetalle = array();
			if($saldos != "Basico"){
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
			}
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			/*Calculo de saldos generales*/
			$saldoInicialGeneral = round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   = round($datView["objTMI"]->reference2,0);
			
			$saldoInicial 	= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 	= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			
			$detalle = array();
			if($saldos != "Basico"){
				$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol. sprintf("%.2f", $saldoInicial));
				array_push($detalle,$row);
				
				foreach($datView["objTMD"] as $detail_){
					$row = array("ABONO", '', sprintf('%.2f',round($detail_->amount,2)));
					array_push($detalle,$row);
				}
				
				$row = array("SALDO FINAL", '', sprintf('%.2f', $saldoFinal) );
				array_push($detalle,$row);
			}
			
			
			
			
			//Generar Reporte
			$html = helper_reporte58mmTransactionMaster(
			    "ABONO",
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
			$this->response->setContentType('application/pdf');
			$objParameterShowLinkDownload 	= $objParameterShowLinkDownload == "false" ? true : false;
			$this->dompdf->stream("file.pdf", ['Attachment' => $objParameterShowLinkDownload]);
			
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
	function viewRegisterFormatoPaginaTicket58mmDirect(){
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter					= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);			
			$objParameterTelefono			= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_share","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			/*Calculo de saldos generales*/
			$saldoInicialGeneral 		= round($datView["objTMI"]->reference1,0);
			$saldoFinalGeneral   		= round($datView["objTMI"]->reference2,0);
			$saldoInicial 				= $saldos == "Individuales"? $saldoInicial: $saldoInicialGeneral ;
			$saldoFinal 				= $saldos == "Individuales"? $saldoFinal: $saldoFinalGeneral ;
			$datView["saldoInicial"]	= $saldoInicial;
			$datView["saldoFinal"]		= $saldoFinal;
			$datView["saldoAbonado"]	= $saldoAbonado;
				
									
				
			
			$datView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);									
			$datView["prefixCurrency"]					= $datView["objCurrency"]->simbol." ";
			$datView["cedulaCliente"] 					= $datView["objCustumer"]->customerNumber;
			$datView["nombreCliente"] 					= $datView["objCustumer"]->firstName ;			
			
			
			$datView["objTransactionMaster"]			= $datView["objTM"];
			$datView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$datView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$datView["objCompany"] 						= $this->Company_Model->get_rowByPK($companyID);			
			
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
			
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58mmShareDirect($datView);
			
			
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri		
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
								
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_share");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_share' NO EXISTE...");
		
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
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
			
			
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:50%",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:50%",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			   
			);			
			array_push($confiDetalle,$row);
			$row = array(
			     "style"=>"text-align:left;width:10%",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:10%",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;",
			    "colspan"=>'1',
			    "prefix"=>$datView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>$datView["objCurrency"]->simbol,
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			$detalle = array();
			$row = array("SALDO INICIAL", '', $datView["objCurrency"]->simbol. sprintf("%.2f",$saldoInicial));
			array_push($detalle,$row);
			
			foreach($datView["objTMD"] as $detail_){
			    $row = array("ABONO", '', sprintf("%.2f",round($detail_->amount,2)));
			    array_push($detalle,$row);
			}
			
			$row = array("SALDO FINAL", '', sprintf("%.2f",$saldoFinal));
			array_push($detalle,$row);
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
			    "ABONO",
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
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => !$objParameterShowLinkDownload]);
			
			//descargar
			//$this->dompdf->stream();
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
	
	
}
?>