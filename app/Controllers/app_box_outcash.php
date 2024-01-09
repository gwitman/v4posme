<?php
//posme:2023-02-27
namespace App\Controllers;
class app_box_outcash extends _BaseController {
	
    
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
				$this->response->redirect(base_url()."/".'app_box_outcash/add');	
			} 		
			
			
			
			//Componente de facturacion
			$objComponentTransactionShare	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_outputcash");
			if(!$objComponentTransactionShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_outputcash' NO EXISTE...");
		
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);						
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			//Tipo de Factura			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDenomination"]		= $this->Transaction_Master_Denomination_Model->get_rowByTransactionMaster($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["objComponentShare"]		= $objComponentTransactionShare;					
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_outputcash","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objTipoMovement"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_outputcash","areaID",$companyID);
			$dataView["objListDenomination"]	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_denomination","catalogItemID",$companyID);
			
			$objParameterUrlPrinter 				= $this->core_web_parameter->getParameter("BOX_OUTCASH_URL_PRINTER",$companyID);
			$objParameterUrlPrinter 				= $objParameterUrlPrinter->value;
			$dataView["objParameterUrlPrinter"]	 	= $objParameterUrlPrinter;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_outcash/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_outcash/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_outcash/edit_script',$dataView);//--finview
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
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_outputcash","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_outputcash");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_outputcash' NO EXISTE...");
			
			$objComponentDenomination			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_denomination");
			if(!$objComponentDenomination)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_denomination' NO EXISTE...");
			
			$continue								= true;
			$mensaje								= "";
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;			
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			$movementTypeCierreParameter			= $this->core_web_parameter->getParameterValue("BOX_MOVEMENT_TYPE_CIERRE",$companyID);
			$movementTypeCierreParameter			= strtoupper($movementTypeCierreParameter);
			
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
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_outputcash","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro
			//$objTMNew["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
			$objTMNew["currencyID"] 					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post			
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM->currencyID2,$objTMNew["currencyID"]);
			$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference1");
			$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference2");
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference3");			
			$objTMNew["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");			
			//$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtEmployeeID");//--fin peticion get o post
			//$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post
			//$objTMNew["descriptionReference"] 			= "reference1:input,reference2:input,reference3:Gestor de Cobro,reference4:Linea de credito del Cliente";
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"] 						= 0;
			$objCatalogItemTypeMovement					= $this->Catalog_Item_Model->get_rowByCatalogItemID($objTMNew["areaID"]);
			
			
			$db=db_connect();
			$db->transStart();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_outputcash","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);				
			}
			
			
			//Ingresar el detalle de moneda
			$objListDenominationID	 					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationID");			
			$objListDenominationCurrencyID	 			= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationCurrencyID");			
			$objListDenominationExchangeRate			= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationExchangeRate");			
			$objListDenominationQuantity				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationQuantity");	
			$objListDenominationCatalogItem				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationCatalogItemID");	
			$objListDenominationReference				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationReference");	
			
			$this->Transaction_Master_Denomination_Model->delete_app_posme($transactionMasterID);			
			if(!empty($objListDenominationID))		
			{	
				foreach($objListDenominationID as $key => $value)
				{
					$objTMDeno 									= array();
					$objTMDeno["companyID"]						= $companyID;
					$objTMDeno["transactionID"]					= $transactionID;
					$objTMDeno["transactionMasterID"]			= $transactionMasterID;
					$objTMDeno["isActive"]						= 1;
					$objTMDeno["componentID"]					= $objComponentDenomination->componentID;
					$objTMDeno["catalogItemID"]					= $objListDenominationCatalogItem[$key];
					$objTMDeno["currencyID"]					= $objListDenominationCurrencyID[$key];
					$objTMDeno["exchangeRate"]					= $objListDenominationExchangeRate[$key];
					$objTMDeno["quantity"]						= $objListDenominationQuantity[$key];
					$objTMDeno["reference1"]					= $objListDenominationReference[$key];
					
					$this->Transaction_Master_Denomination_Model->insert_app_posme($objTMDeno);
					
				}
			}
			
			
			//Actualizar Detalle
			$arrayListTransactionDetailID				= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");			
			$arrayListShare	 							= /*inicio get post*/ $this->request->getPost("txtDetailAmount");
			$amount 									= 0;
			$arrayListTransactionDetailID_				= array();
			$arrayListTransactionDetailID_[0]			= $arrayListTransactionDetailID;
			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$arrayListTransactionDetailID_); 
			
			//phpinfo();			
			if(!empty($arrayListTransactionDetailID)){				
				
				$amount									= $amount + helper_StringToNumber($arrayListShare);				
				$transactionDetailID					= $arrayListTransactionDetailID;
				
				//Nuevo Detalle
				if($transactionDetailID == 0){	
					
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentShare->componentID;
					$objTMD["componentItemID"] 				= 0;
					$objTMD["quantity"] 					= 0;
					$objTMD["unitaryCost"]					= 0;
					$objTMD["cost"] 						= 0;
					
					$objTMD["unitaryPrice"]					= 0;
					$objTMD["unitaryAmount"]				= 0;
					$objTMD["amount"] 						= $amount;
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;						
					$objTMD["reference1"]					= '';
					$objTMD["reference2"]					= '';
					$objTMD["reference3"]					= '';
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= NULL;
					$objTMD["inventoryWarehouseTargetID"]	= NULL;
					$amount 								= $amount + $objTMD["amount"];
					
					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
				}					
				//Editar Detalle
				else{	
					
					$objTMD 								= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objComponentShare->componentID);						
					$objTMDNew 								= null;						
					$objTMDNew["amount"] 					= $amount;
					$objTMDNew["reference1"]				= '';
					$objTMDNew["reference2"]				= '';
					$objTMDNew["reference3"]				= '';
					$objTMDNew["exchangeRateReference"]		= '0';
					$objTMDNew["descriptionReference"]		= '';
					//$amount 								= $amount + $objTMDNew["amount"];
					
					$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionDetailID,$objTMDNew);
				}
				
			}			
			
			
			
			//Actualizar Transaccion			
			$objTMNew["amount"] = $amount;			
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
						
			//Aplicar documento		
			if( 
				$this->core_web_workflow->validateWorkflowStage
				(
					"tb_transaction_master_outputcash",
					"statusID",
					$objTMNew["statusID"],
					COMMAND_APLICABLE,
					$dataSession["user"]->companyID,
					$dataSession["user"]->branchID,
					$dataSession["role"]->roleID
				) &&  
				$oldStatusID != $objTMNew["statusID"]  && 
				strtoupper($objCatalogItemTypeMovement->name)  ==   $movementTypeCierreParameter	
			)
			{
				
				//Validar registro de caja
				$objListTMRegister 	= $this->Transaction_Master_Model->get_rowInStatusRegister($companyID,$transactionMasterID);
				if($objListTMRegister)
				{
					$continue			= false;
					$mensaje			= "Caja no puede ser cerrada tiene movimientos registrados, o los anula o los aplica.";
				}
				
			}
			
			//Cerrar caja si el tipo es Cierre
			$typeOutputCash 		= $objTMNew["areaID"];
			$objCatalogItem 		= $this->Catalog_Item_Model->get_rowByCatalogItemID($typeOutputCash);
			$objWorkflowStageApply 	= $this->core_web_workflow->getWorkflowStageApplyFirst( "tb_cash_box_session","statusID",$companyID,$branchID,$roleID );
			$objWorkflowStageInit 	= $this->core_web_workflow->getWorkflowInitStage( "tb_cash_box_session","statusID",$companyID,$branchID,$roleID );
			$objListCashUser	 	= $this->Cash_Box_User_Model->asObject()->where("companyID",$this->session->get('user')->companyID)->where("userID",$userID )->findAll();
			$cashBoxID				= $objListCashUser ? $objListCashUser[0]->cashBoxID : 0;
			
			if( strtoupper($objCatalogItem->display) == strtoupper("Cierre") )
			{
				$objCashBoxSession	= $this->Cash_Box_Session_Model->asArray()->
										where("userID",$userID)->
										where("statusID",$objWorkflowStageInit[0]->workflowStageID)->
										findAll();		

				if($objCashBoxSession)
				{				
					$objCashBoxSession 					= $objCashBoxSession[0];
					$objCashBoxSession["statusID"] 		= $objWorkflowStageApply[0]->workflowStageID;					
					$objCashBoxSession["endOn"] 		= date("Y-m-d H:i:s");
					$this->Cash_Box_Session_Model->update($objCashBoxSession["cashBoxSessionID"],$objCashBoxSession);
				}
			
			}
			
			
			
			if($db->transStatus() !== false && $continue == true)
			{
				$db->transCommit();			
				$this->core_web_notification->set_message(false,SUCCESS);				
				$this->response->redirect(base_url()."/".'app_box_outcash/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
				
			}
			else
			{
				$db->transRollback();	
				$mensaje = empty($db->error()["message"]) ? $mensaje : $db->error()["message"];
				$this->core_web_notification->set_message(true,$mensaje);
				$this->response->redirect(base_url()."/".'app_box_outcash/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
				
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_outputcash");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_outputcash' NO EXISTE...");
		
		
			$objComponentDenomination			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_denomination");
			if(!$objComponentDenomination)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_denomination' NO EXISTE...");
			
		
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_outputcash",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_outputcash",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);
			//$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
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
			$objTM["reference4"] 					= '';
			$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["amount"] 						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost('txtTotal'));
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$pathDocument = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$transactionMasterID;
			if(!file_exists ($pathDocument))
			{
				mkdir( $pathDocument,0700);
			}
			
			
			//Ingresar el detalle de moneda
			$objListDenominationID	 					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationID");			
			$objListDenominationCurrencyID	 			= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationCurrencyID");			
			$objListDenominationExchangeRate			= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationExchangeRate");			
			$objListDenominationQuantity				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationQuantity");	
			$objListDenominationCatalogItem				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationCatalogItemID");	
			$objListDenominationReference				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDenominationReference");	
			
			if(!empty($objListDenominationID))		
			{	
				foreach($objListDenominationID as $key => $value)
				{
					$objTMDeno 									= array();
					$objTMDeno["companyID"]						= $companyID;
					$objTMDeno["transactionID"]					= $transactionID;
					$objTMDeno["transactionMasterID"]			= $transactionMasterID;
					$objTMDeno["isActive"]						= 1;
					$objTMDeno["componentID"]					= $objComponentDenomination->componentID;
					$objTMDeno["catalogItemID"]					= $objListDenominationCatalogItem[$key];
					$objTMDeno["currencyID"]					= $objListDenominationCurrencyID[$key];
					$objTMDeno["exchangeRate"]					= $objListDenominationExchangeRate[$key];
					$objTMDeno["quantity"]						= $objListDenominationQuantity[$key];
					$objTMDeno["reference1"]					= $objListDenominationReference[$key];
					$this->Transaction_Master_Denomination_Model->insert_app_posme($objTMDeno);
				}
			}
			
			//Recorrer la lista del detalle del documento			
			$arrayListShare	 							= /*inicio get post*/ $this->request->getPost("txtDetailAmount");			
			$arrayListTransactionDetailID				= /*inicio get post*/ $this->request->getPost("txtDetailTransactionDetailID");
			$amount 									= 0;
			
		
			if(!empty($arrayListShare)){			
				$amount									= helper_StringToNumber($arrayListShare);
				$transactionDetailID					= $arrayListTransactionDetailID;
				
				
				$objTMD 								= NULL;
				$objTMD["companyID"] 					= $objTM["companyID"];
				$objTMD["transactionID"] 				= $objTM["transactionID"];
				$objTMD["transactionMasterID"] 			= $transactionMasterID;
				$objTMD["componentID"]					= $objComponentShare->componentID;
				$objTMD["componentItemID"] 				= 0;
				$objTMD["quantity"] 					= 0;
				$objTMD["unitaryCost"]					= 0;
				$objTMD["cost"] 						= 0;
				
				$objTMD["unitaryPrice"]					= 0;
				$objTMD["unitaryAmount"]				= 0;
				$objTMD["amount"] 						= $amount;
				$objTMD["discount"]						= 0;					
				$objTMD["promotionID"] 					= 0;
				
				$objTMD["reference1"]					= 0;
				$objTMD["reference2"]					= 0;
				$objTMD["reference3"]					= 0;
				$objTMD["catalogStatusID"]				= 0;
				$objTMD["inventoryStatusID"]			= 0;
				$objTMD["isActive"]						= 1;
				$objTMD["quantityStock"]				= 0;
				$objTMD["quantiryStockInTraffic"]		= 0;
				$objTMD["quantityStockUnaswared"]		= 0;
				$objTMD["remaingStock"]					= 0;
				$objTMD["expirationDate"]				= NULL;
				$objTMD["inventoryWarehouseSourceID"]	= NULL;
				$objTMD["inventoryWarehouseTargetID"]	= NULL;					
				
				
				$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
				
			}
			
			//Actualizar Transaccion
			$objTM["amount"] = $amount;
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_box_outcash/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$db->_error_message());
				$this->response->redirect(base_url()."/".'app_box_outcash/add');	
			}
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
				$this->response->redirect(base_url()."/".'app_box_outcash/add');
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
				$this->response->redirect(base_url()."/".'app_box_outcash/add');
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
			
		
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_outputcash",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			//Tipo de Factura
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			
			$objParameterExchangePurchase		= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID);
			$dataView["exchangeRatePurchase"]	= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) - $objParameterExchangePurchase->value;			
			$objParameterExchangeSales			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID);
			$dataView["exchangeRateSale"]		= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) + $objParameterExchangeSales->value;		
			
	
			$dataView["objCurrency"]			= $objCurrency;
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_outputcash","statusID",$companyID,$branchID,$roleID);
			$dataView["objTipoMovement"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_outputcash","areaID",$companyID);
			$dataView["objListDenomination"]	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_denomination","catalogItemID",$companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_box_outcash/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_box_outcash/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_box_outcash/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_outputcash");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_outputcash' NO EXISTE...");
			
			
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
				$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$objComponent->componentID,"DEFAULT_MOBILE_LISTA_EGRESOS_A_CAJA",CALLERID_LIST,$resultPermission,$parameter); 			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_box_outcash/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_box_outcash/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_box_outcash/list_script');//--finview
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_outputcash","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID, is_null($datView["objTM"]->entityID) == true ? 0 : $datView["objTM"]->entityID );
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,is_null($datView["objTM"]->entityID) == true ? 0 : $datView["objTM"]->entityID );
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			
			
			
			
			$facturaTipo   = $datView["objTipo"]->name;
			$facturaEstado = $datView["objStage"][0]->display;
			
			
			
			
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
			    "SALIDA DE CAJA",
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
			exit($ex->getMessage());
		}
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_outputcash","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." ";
			
			
			$facturaTipo   = $datView["objTipo"]->name;
			$facturaEstado = $datView["objStage"][0]->display;
			
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
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
			$this->core_web_printer_direct->executePrinter80mmReportCashOutRustik($datView);
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
	//factura en imppresora de ticket de 80mm
	function viewPrinterDirect80mmComidaChinaMijo(){
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
			$this->core_web_printer_direct->executePrinter80mmReportCashOutComidaChinaMijo($datView);
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
}
?>