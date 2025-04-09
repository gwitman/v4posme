<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxp_expenses extends _BaseController {
	
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
				$this->response->redirect(base_url()."/".'app_cxp_expenses/add');	
			} 		
			
			
			//Componente de facturacion
			$objComponentTransactionShare	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_accounting_expenses");
			if(!$objComponentTransactionShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_accounting_expenses' NO EXISTE...");

			$objComponentProvider			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");			
			if(!$objComponentProvider)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");

			$objComponentAmortization		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_amoritization");			
			if(!$objComponentAmortization)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_amoritization' NO EXISTE...");

			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			//Tipo de Factura			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);									
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");	
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);


			$objProvider						= null;
			$objLegal							= null;
			
			if($dataView["objTransactionMaster"]->entityID)
			{
				$objProvider						= $this->Provider_Model->get_rowByEntity($companyID, $dataView["objTransactionMaster"]->entityID);
				$objLegal							= $this->Legal_Model->get_rowByPK($companyID, $branchID, $dataView["objTransactionMaster"]->entityID);			
			}

			$dataView["objTransactionMaster"]->tax4 	= $dataView["objTransactionMaster"]->tax4 ? $dataView["objTransactionMaster"]->tax4 : 0;
			$objAmortization							= $this->Customer_Credit_Amortization_Model->get_rowByPk($dataView["objTransactionMaster"]->tax4);
			$objDocument 								= $objAmortization ? $this->Customer_Credit_Document_Model->get_rowByPk($objAmortization->customerCreditDocumentID) : NULL;

			$dataView["company"]					= $dataSession["company"];
			$dataView["objListCurrency"]			= $objListCurrency;
			$dataView["companyID"]					= $dataSession["user"]->companyID;
			$dataView["userID"]						= $dataSession["user"]->userID;
			$dataView["userName"]					= $dataSession["user"]->nickname;
			$dataView["roleID"]						= $dataSession["role"]->roleID;
			$dataView["roleName"]					= $dataSession["role"]->name;
			$dataView["branchID"]					= $dataSession["branch"]->branchID;
			$dataView["branchName"]					= $dataSession["branch"]->name;
			$dataView["exchangeRate"]				= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objComponentShare"]			= $objComponentTransactionShare;					
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_accounting_expenses","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objListBranch"]				= $this->Branch_Model->getByCompany($companyID);
			$dataView["objComponentProvider"]		= $objComponentProvider;
			$dataView["objProvider"]				= $objProvider;
			$dataView["objLegal"]					= $objLegal;
			$dataView["objAmortization"]			= $objAmortization;
			$dataView["objDocument"]				= $objDocument;
			$dataView["objComponentAmortization"]	= $objComponentAmortization;
			$dataView["objComponentItem"]			= $objComponentItem;

			$objPublicCatalogTipoGastos 						= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_accounting_expenses.tipos_gastos")->where("isActive",1)->find();			
			$objPublicCatalogCategoriaGastos 					= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_accounting_expenses.categoria_gastos")->where("isActive",1)->find();
			$dataView["objListCatalogoTipoGastos"]				= $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID",$objPublicCatalogTipoGastos[0]->publicCatalogID)->where( "isActive",1)->findAll();
            $dataView["objListCatalogItemClasificacion"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_accounting_expenses","classID",$companyID);
			
			$dataView["objTipoGasto"]							= $this->Public_Catalog_Detail_Model->
																	asObject()->
																	where("publicCatalogDetailID",$dataView["objTransactionMaster"]->priorityID)->
																	findAll();
																	
			$dataView["objListCatalogoCategoriaGastos"]			= $this->Public_Catalog_Detail_Model->
																	asObject()->
																	where("publicCatalogID",$objPublicCatalogCategoriaGastos[0]->publicCatalogID)->
																	where("isActive",1)->
																	where("parentName",$dataView["objTipoGasto"][0]->name)->
																	findAll();
																	
																	
			$objParameterUrlPrinter 					= $this->core_web_parameter->getParameter("CXP_URL_PRINTER_GASTO",$companyID);
			$objParameterUrlPrinter 					= $objParameterUrlPrinter->value;
			$dataView["objParameterUrlPrinter"]	 		= $objParameterUrlPrinter;
			
			$objParameterUrlServerFile 					= $this->core_web_parameter->getParameter("CORE_FILE_SERVER",$companyID);
			$objParameterUrlServerFile					= $objParameterUrlServerFile->value;
			$dataView["objParameterUrlServerFile"]		= $objParameterUrlServerFile;
			
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_expenses/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_expenses/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_expenses/edit_script',$dataView);//--finview
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
			$companyID 								= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 							= /*inicio get post*/ $this->request->getPost("transactionID");				
			$transactionMasterID 					= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
			$objListCompanyParameter				= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$workflowStageAmortizationRegister 		= $this->core_web_parameter->getParameterFiltered($objListCompanyParameter,"SHARE_AMORTIZATION_STATUS_REGISTER")->value;	
			$workflowStageDocumentRegister 			= $this->core_web_parameter->getParameterFiltered($objListCompanyParameter,"SHARE_DOCUMENT_CREDIT_STATUS_REGISTER")->value;	
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);			

			if((!$companyID && !$transactionID && !$transactionMasterID)){
				throw new \Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);	
			$objTMD					= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID)[0];

			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");
				
			$db=db_connect();
			$db->transStart();

			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$objTM->statusID,COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			{
				//Eliminar el Registro			
				$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);	
			}
			else if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			{
				if(empty($objTM->tax4) || $objTM->tax4 == 0)
				{
					throw new \Exception("ESTE REGISTRO NO TIENE UNA DEUDA ASIGNADA");
				}

				if(!$dataSession["role"]->isAdmin)
				{
					throw new \Exception(NOT_WORKFLOW_DELETE);
				}

				//Obtener datos
				$objTMDR	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterID($transactionMasterID);
				$objTMSOld	= $this->Transaction_Master_Model->get_rowByTransactionID_And_EntityID($companyID,$transactionID,$objTM->entityID);
				$objTMDC	= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($objTMD->transactionMasterDetailID);
				
				//Validar gasto a eliminar
				if($objTMSOld)
				{
					if($objTMSOld[0]->transactionMasterID != $transactionMasterID)
					{
						throw new \Exception("SOLO PUEDE ELIMINAR EL ULTIMO GASTO DEL CLIENTE...");
					}
				}

				//Recorrare las cuotas a regresar
				foreach($objTMDR as $key => $amRef)
				{
					//Actualizarion Amortizacion
					$objCCA 				= $this->Customer_Credit_Amortization_Model->get_rowByPK($amRef->componentItemID);
					$amorNew["remaining"]	= $objCCA->remaining + $amRef->quantity;
					$amorNew["statusID"] 	= $workflowStageAmortizationRegister;
					$amorNew["dayDelay"] 	= 0;
					$this->Customer_Credit_Amortization_Model->update_app_posme($amRef->componentItemID,$amorNew);
				}	

				//Actualizar Documento
				$objCC					= $this->Customer_Credit_Document_Model->get_rowByPK($objCCA->customerCreditDocumentID);					
				$cdcNew["statusID"]		= $workflowStageDocumentRegister;
				$cdcNew["balance"]		= $objCC->balance + $objTMDC->capital;
				$this->Customer_Credit_Document_Model->update_app_posme($objCCA->customerCreditDocumentID,$cdcNew);

				//Obtener Linea de Credito
				$objCustomerCreditLine   = $this->Customer_Credit_Line_Model->get_rowByPK($objCC->customerCreditLineID);
					
				//Actualizar Linea de Credito
				$montoTotalCordobaCredit = $objTM->currencyID == 1 /*cordoba*/ ? $objTMDC->capital : round(($objTMDC->capital * $objTM->exchangeRate),2) ;
				$montoTotalDolaresCredit = $objTM->currencyID == 2 /*dolares*/ ? $objTMDC->capital : round(($objTMDC->capital / $objTM->exchangeRate),2) ;
				
				//aumentar el balance de linea
				if($objCustomerCreditLine->currencyID == $objCurrencyCordoba->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $montoTotalCordobaCredit;
				else
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $montoTotalDolaresCredit;
					
				$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);

				//Eliminar el Registro			
				$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);	
			}
			
			
			if($db->transStatus() !== false)
			{
				$db->transCommit();		
				return $this->response->setJSON(array(
					'error'   => false,
					'message' => SUCCESS
				));//--finjson
				
			}
			else
			{	
				throw new \Exception($this->db->_error_message());
			}
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_accounting_expenses");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_accounting_expenses' NO EXISTE...");
			
			$objCustomerCreditDocument			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objCustomerCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;			
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$transactionMasterDetailID				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDetailID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;

			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			$typeAmortizationAmericanoID			= $this->core_web_parameter->getParameter("CXC_AMERICANO",$companyID)->value;
			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro	
			$objTMNew["entityID"]						= /*inicio get post*/ $this->request->getPost("txtProviderID");
			$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["branchID"]						= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
			$objTMNew["currencyID"] 					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post			
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM->currencyID2,$objTMNew["currencyID"]);
			$objTMNew["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");
			$objTMNew["priorityID"] 					= /*inicio get post*/ $this->request->getPost("txtPriorityID");
			$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference1");
			$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference2");
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference3");			
			$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference4");			
			
			//$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post
			//$objTMNew["descriptionReference"] 		= "reference1:input,reference2:input,reference3:Gestor de Cobro,reference4:Linea de credito del Cliente";
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"] 						= /*inicio get post*/ $this->request->getPost("txtDetailAmount");
            $objTMNew["tax1"] 						    = helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtTransactionMasterTax1'));
            $objTMNew["tax2"] 						    = helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtTransactionMasterTax2'));
			$objTMNew["tax4"] 						    = helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtAmortizationID'));
			$objTMNew['classID']                        =  $this->request->getPost('txtClassID');

			$objTMDNew["companyID"]						= $companyID;
			$objTMDNew["transactionID"]					= $transactionID;
			$objTMDNew["transactionMasterID"]			= $transactionMasterID; 
			$objTMDNew["componentID"]					= $objCustomerCreditDocument->componentID;
			$objTMDNew["componentItemID"]				= /*inicio get post*/ $this->request->getPost("txtCustomerCreditDocumentID");
			$objTMDNew["amount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtTransactionMasterTax2'));
			$objTMDNew["reference3"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtAmortizationID'));
			$objTMDNew["reference1"]					= /*inicio get post*/ $this->request->getPost("txtDocumentNumber"); 
			$objTMDNew["isActive"]						= 1;

			$db=db_connect();
			$db->transStart();
			
			//El Estado solo permite editar el workflow			
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){			
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMDNew);				
			}
	

			// Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] )
			{	
				//Recorrer Factura para Actualizar Balances
				$objListTMD = $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
				$objTMD		= $objListTMD[0];

				if($objTMD && !empty($objTMD->reference3))
				{
					//documento inicial
					$objCustomerCreditDocumentInicial			= $this->Customer_Credit_Document_Model->get_rowByPK($objTMD->componentItemID);					
					
					//aplicar
					$this->core_web_amortization->applyCuote($companyID,$objTMD->componentItemID,$objTMD->amount,$objTMD->reference3,$objTMD->transactionMasterDetailID);
					
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
					
					
					$objTMFactura 								= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$objTMD->reference1);/*invoiceNumber*/
					$objCustomerCreditLine 						= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCreditDocument->customerCreditLineID); /*customerCreditLineID*/
					$montoAbono									= $objTMDC["capital"];
					$montoAbonoDolares							= $objTMFactura->currencyID == 2 ? $objTMDC["capital"] :  /*cordoba a dolares*/ ($objTMDC["capital"] * round($objTMFactura->exchangeRate,4));
					$montoAbonoCordobas							= $objTMFactura->currencyID == 1 ? $objTMDC["capital"] :  /*dolares a cordoba*/ ($objTMDC["capital"] * round($objTMFactura->exchangeRate,4));
					
					// actualizar saldo de la linea
					// linea dolares y factura dolares					
					// linea cordoba y factura cordoba
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
				
				// Crear Conceptos.
				$this->core_web_concept->share($companyID,$transactionID,$transactionMasterID);
			}
			
			if($db->transStatus() !== false)
			{
				
				$db->transCommit();	
				$this->core_web_notification->set_message(false,SUCCESS);				
				$this->response->redirect(base_url()."/".'app_cxp_expenses/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
				
			}
			else{
				$db->transRollback();	
				$this->core_web_notification->set_message(true,$this->$db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxp_expenses/add');	
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_accounting_expenses");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_accounting_expenses' NO EXISTE...");
			
			$objCustomerCreditDocument			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_document");
			if(!$objCustomerCreditDocument)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_document' NO EXISTE...");
			
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_accounting_expenses",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_accounting_expenses",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);			
			$objTM["entityID"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtProviderID"));
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShare->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post
			$objTM["currencyID2"]					= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference1");
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference2");
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference3");
			$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference4");
			$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["amount"] 						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtDetailAmount'));
			$objTM["tax1"] 						    = helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtTransactionMasterTax1'));
			$objTM["tax2"] 						    = helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtTransactionMasterTax2'));
			$objTM["tax4"] 						    = helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtAmortizationID'));
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= $this->request->getPost('txtClassID');
			$objTM["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");
			$objTM["priorityID"] 					= /*inicio get post*/ $this->request->getPost("txtPriorityID");
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);

			$objTMD["companyID"]					= $companyID;
			$objTMD["transactionID"]				= $objTM["transactionID"];
			$objTMD["transactionMasterID"]			= $transactionMasterID; 
			$objTMD["componentID"]					= $objCustomerCreditDocument->componentID;
			$objTMD["componentItemID"]				= /*inicio get post*/ $this->request->getPost("txtCustomerCreditDocumentID");
			$objTMD["amount"]						= $objTM["tax2"];
			$objTMD["reference3"]					= /*inicio get post*/ $this->request->getPost("txtAmortizationID");
			$objTMD["reference1"]					= /*inicio get post*/ $this->request->getPost("txtDocumentNumber"); 
			$objTMD["isActive"]						= 1;
			$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
			
			
			$objParameterUrlServerFile 					= $this->core_web_parameter->getParameter("CORE_FILE_SERVER",$companyID);
			$objParameterUrlServerFile 					= $objParameterUrlServerFile->value;
			$dataView["objParameterUrlServerFile"]	 	= $objParameterUrlServerFile;
			
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			if ($dataView["objParameterUrlServerFile"] == "")
			{
				$pathDocument = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$transactionMasterID;
				if(!file_exists ($pathDocument))
				{
					mkdir( $pathDocument,0700,true);
				}
			}
			else 
			{
				//Crear carpeta en servidor remoto
				$ch 				= curl_init();
				$urlCreateFolder 	= $dataView["objParameterUrlServerFile"]."/core_elfinder/createFolder/companyID/".$companyID."/componentID/".$objTM["componentID"]."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID;
				
				
				// set URL and other appropriate options
				curl_setopt($ch, CURLOPT_URL, $urlCreateFolder);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				
				// grab URL and pass it to the browser
				curl_exec($ch);
				
				// close cURL resource, and free up system resources
				curl_close($ch);
			}
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxp_expenses/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->$db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxp_expenses/add');	
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
				$this->response->redirect(base_url()."/".'app_cxp_expenses/add');
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
				$this->response->redirect(base_url()."/".'app_cxp_expenses/add');
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
			
			$objComponentProvider			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");			
			if(!$objComponentProvider)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");

			$objComponentAmortization		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_credit_amoritization");			
			if(!$objComponentAmortization)
			throw new \Exception("EL COMPONENTE 'tb_customer_credit_amoritization' NO EXISTE...");
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
			$providerDefault					= $this->core_web_parameter->getParameter("CXP_PROVIDER_DEFAULT",$companyID);
			$providerDefault 					= $this->Provider_Model->get_rowByProviderNumber($companyID,$providerDefault->value);

			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_accounting_expenses",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			//Tipo de Factura
			$dataView["company"]				= $dataSession["company"];
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objCurrency"]			= $objCurrency;
			$objParameterExchangePurchase		= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID);
			$dataView["exchangeRatePurchase"]	= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) - $objParameterExchangePurchase->value;			
			$objParameterExchangeSales			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID);
			$dataView["exchangeRateSale"]		= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) + $objParameterExchangeSales->value;		
			$dataView["objListBranch"]			= $this->Branch_Model->getByCompany($companyID);
			$dataView["objComponentProvider"]	= $objComponentProvider;
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_accounting_expenses","statusID",$companyID,$branchID,$roleID);
			$dataView["objComponentItem"]		= $objComponentItem;

			$dataView["objComponentAmortization"]	= $objComponentAmortization;
			$objPublicCatalogTipoGastos 			= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_accounting_expenses.tipos_gastos")->where("isActive",1)->find();
			if(!$objPublicCatalogTipoGastos)
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE TIPOS DE GASTOS tb_transaction_master_accounting_expenses.tipos_gastos");
			}
			
			$objPublicCatalogCategoriaGastos 		= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_accounting_expenses.categoria_gastos")->where("isActive",1)->find();
			if(!$objPublicCatalogCategoriaGastos)
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE CATEGORIA DE GASTOS tb_transaction_master_accounting_expenses.categoria_gastos");
			}
            $dataView["objListCatalogItemClasificacion"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_accounting_expenses","classID",$companyID);

			$objListComanyParameter								= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]			= $objParameterCantidadItemPoup->value;

            $dataView["objListCatalogoTipoGastos"]				= $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID",$objPublicCatalogTipoGastos[0]->publicCatalogID)->where( "isActive",1)->findAll();
			$dataView["objListCatalogoCategoriaGastos"]			= $this->Public_Catalog_Detail_Model->
																	asObject()->
																	where("publicCatalogID",$objPublicCatalogCategoriaGastos[0]->publicCatalogID)->
																	where("isActive",1)->
																	where("parentName",$dataView["objListCatalogoTipoGastos"][0]->name)->
																	findAll();
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_expenses/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_expenses/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_expenses/news_script',$dataView);//--finview
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
		
			//Librerias
			
			
			
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_accounting_expenses");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_accounting_expenses' NO EXISTE...");
			
			
			//Vista por defecto PC
			if($dataViewID == null and  !$this->request->getUserAgent()->isMobile() ){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}		
			//Vista por defecto MOBILE
			else if( $this->request->getUserAgent()->isMobile() ){
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$objComponent->componentID,"DEFAULT_MOBILE_LISTA_INGRESO_A_CAJA",CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			//Vista Por Id
			else 
			{
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_expenses/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_cxp_expenses/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_expenses/list_script');//--finview
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
			
		    echo $resultView;		
		}
	}	

	function viewPrinterFormatoA4(){
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
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterGastosGlobalPro(
			    "GASTOS",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],				
			    $objParameterTelefono,				
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/
			);
			
			$this->dompdf->loadHTML($html);
			
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_98/component_item_".$transactionMasterID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);
				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_98/component_item_".$transactionMasterID."/".
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
	
	//factura en imppresora de ticket de 80mm
	function viewRegisterFormatoPaginaTicket(){
		//Factura en Impresora Termica 
		//O impresora de ticket, con ancho de 3.2 pulgadas
		//O equivalente a 8 centimetro
		//Formato de papel rollo.
		
		
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
			
			//Cargar Libreria
					
			
			
			//Get Component
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter		= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterPhone	= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 			= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,is_null($datView["objTM"]->entityID) == true ? 0 : $datView["objTM"]->entityID );
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,is_null($datView["objTM"]->entityID) == true ? 0 : $datView["objTM"]->entityID );
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			
			
			
			//Set Nombre del Reporte
			$reportName		= "DOC_INVOICE";
			$facturaTipo   = $datView["objTipo"]->name;
			$facturaEstado = $datView["objStage"][0]->display;
			
			
			
			
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
			$row = array("MONTO", '', $datView["objCurrency"]->simbol.sprintf('%.2f', $datView["objTM"]->amount ) );
			array_push($detalle,$row);
			
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterInputOutPutCash(
			    "INGRESO DE CAJA",
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
			
		    return $resultView;		}
	}
	//factura en imppresora de ticket de 80mm
	function viewRegisterFormatoPaginaTicketTermica(){
		//Factura en Impresora Termica 
		//O impresora de ticket, con ancho de 3.2 pulgadas
		//O equivalente a 8 centimetro
		//Formato de papel rollo.
		
		
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter		= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterPhone	= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 			= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_accounting_expenses","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			
			
			
			
			
			$facturaTipo   = $datView["objTipo"]->name;
			$facturaEstado = $datView["objStage"][0]->display;
			
			
			
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
	
	//factura en imppresora de ticket de 80mm
	function viewPrinterDirect80mmShareRustikGrill(){
		//Factura en Impresora Termica 
		//O impresora de ticket, con ancho de 3.2 pulgadas
		//O equivalente a 8 centimetro
		//Formato de papel rollo.
		
		
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter		= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterPhone	= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 			= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTMDD"]						= $this->Transaction_Master_Denomination_Model->get_rowByTransactionMaster($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUsuario"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_outputcash","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);			
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			$datView["objParameterLogo"]			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$datView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			
			$facturaTipo   = $datView["objTipo"]->name;
			$facturaEstado = $datView["objStage"][0]->display;
			
			
			
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmReportCashInputRustik($datView);
			
			
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
	
}
?>