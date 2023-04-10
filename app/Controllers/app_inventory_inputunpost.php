<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_inputunpost extends _BaseController {
	
    
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
			    $objParameterTelefono
			    );
			$this->dompdf->loadHTML($html);
			
			
			$this->dompdf->render();
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => false]);
			
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
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_inputunpost",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objListTypePreice						= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;			
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_inputunpost",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($objTM["companyID"],$objTM["transactionID"]);
			$objTM["entityID"]						= /*inicio get post*/ $this->request->getPost("txtProviderID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponent->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtDescription");//--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
			$objTM["currencyID2"]					= $objTM["currencyID"];//$this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
			$objTM["exchangeRate"]					= 1;//$this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
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
			$objTM["tax4"]							= 0;
			$objTM["subAmount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtSubTotal"));
			$objTM["discount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtDiscount"));
			$objTM["amount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtTotal"));
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$path_ = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$transactionMasterID;
			mkdir($path_, 0700);
			
			//Recorrer la lista del detalle del documento
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");	
			$arrayListCost	 							= /*inicio get post*/ $this->request->getPost("txtDetailCost");			
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");			
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");					
			$arrayPrice 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice");			
			$arrayPrice2 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice2");			
			$arrayPrice3 								= /*inicio get post*/ $this->request->getPost("txtDetailPrice3");			
			
			
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){	
					$itemID 								= $value;
					$objItem 								= $this->Item_Model->get_rowByPK($objTM["companyID"],$value);
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					$cost 									= helper_StringToNumber($arrayListCost[$key]);
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
					
					$objTMD["unitaryAmount"]				= $unitaryPrice;
					$objTMD["amount"] 						= 0;										
					$objTMD["discount"]						= 0;
					$objTMD["unitaryPrice"]					= $unitaryPrice;
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["lote"]							= $lote;
					$objTMD["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
					$objTMD["reference3"]					= $unitaryPrice2."|".$unitaryPrice3;
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
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/add');	
			}
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			
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
			$objTMNew["transactionOn"]				= /*inicio get post*/ $this->request->getPost("txtTransactionOn");
			$objTMNew["statusIDChangeOn"]			= date("Y-m-d H:m:s");			
			$objTMNew["note"] 						= /*inicio get post*/ $this->request->getPost("txtDescription");//--fin peticion get o post
			$objTMNew["exchangeRate"]				= 1;//$this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$objTM->currencyID,$objTM->currencyID2);
			$objTMNew["entityID"]					= /*inicio get post*/ $this->request->getPost("txtProviderID");
			$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
			$objTMNew["reference1"] 				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objTMNew["reference2"] 				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objTMNew["reference3"] 				= /*inicio get post*/ $this->request->getPost("txtReference3");//--fin peticion get o post
			$objTMNew["reference4"] 				= /*inicio get post*/ $this->request->getPost("txtTransactionMasterIDOrdenCompra");//--fin peticion get o post
			$objTMNew["sourceWarehouseID"]			= NULL;
			$objTMNew["targetWarehouseID"]			= /*inicio get post*/ $this->request->getPost("txtWarehouseID");//--fin peticion get o post
			$objTMNew["tax1"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtIva"));
			$objTMNew["tax2"]						= 0;
			$objTMNew["tax3"]						= 0;
			$objTMNew["tax4"]						= 0;
			$objTMNew["subAmount"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtSubTotal"));
			$objTMNew["discount"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtDiscount"));
			$objTMNew["amount"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtTotal"));
			
			$db=db_connect();
			$db->transStart();
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
			$archivoCSV 								= /*inicio get post*/ $this->request->getPost("txtFileImport");			
			
			
			
			if($archivoCSV != ".csv"){
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);
				
				//Leer archivo
				$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$transactionMasterID;			
				$path 	= $path.'/'.$archivoCSV;
				
				if (!file_exists($path))
				throw new \Exception("NO EXISTE EL ARCHIVO PARA IMPORTAR LOS PRECIOS");
				
				$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				$characterSplie = $objParameter->value;
				
				$this->csvreader->separator = $characterSplie;
				$table 			= $this->csvreader->parse_file($path); 
				$fila 			= 0;
				if($table){
					
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
						$codigo 		= $row["Codigo"];
						$description 	= $row["Nombre"];
						$cantidad 		= $row["Cantidad"];
						$costo 			= $row["Costo"];			
						$lote 			= $row["Lote"];
						$vencimiento	= $row["Vencimiento"];
						$precio			= $row["Precio"];
						$objItem		= $this->Item_Model->get_rowByCode($companyID,$codigo);	
						
						if(!$objItem) {
						$objItem		= $this->Item_Model->get_rowByCodeBarra($companyID,$codigo);		
						}				
						
						
						if(!$objItem) {
							throw new \Exception("El siguiente producto no existe en inventario: ". $codigo);
						}
							
						$transactionMasterDetailID				= 0;					
						$itemID 								= $objItem->itemID;
						$quantity 								= helper_StringToNumber($cantidad);
						$cost 									= helper_StringToNumber($costo);	
						
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
							$objTMD["amount"] 						= 0;
							$objTMD["discount"]						= 0;
							$objTMD["unitaryPrice"]					= $precio;
							$objTMD["promotionID"] 					= 0;
							$objTMD["lote"]							= $lote;
							$objTMD["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
							$objTMD["reference3"]					= '0|0';
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
			else{
				
				$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTMD_ID);
				if(!empty($arrayListItemID)){
					foreach($arrayListItemID as $key => $value){
						$transactionMasterDetailID				= $listTMD_ID[$key];	
						$objItem 								= $this->Item_Model->get_rowByPK($objTM->companyID,$value);
						$itemID 								= $value;
						$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
						$cost 									= helper_StringToNumber($arrayListCost[$key]);
						$lote 									= $arrayListLote[$key];
						$vencimiento							= $arrayListVencimiento[$key];
						$unitaryPrice 							= $arrayPrice[$key];
						$unitaryPrice2 							= helper_RequestGetValue($arrayPrice2[$key],0);
						$unitaryPrice3 							= helper_RequestGetValue($arrayPrice3[$key],0);
						
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
							$objTMD["amount"] 						= 0;										
							$objTMD["discount"]						= 0;
							$objTMD["unitaryPrice"]					= $unitaryPrice;
							$objTMD["promotionID"] 					= 0;
							
							$objTMD["lote"]							= $lote;
							$objTMD["expirationDate"]				= $vencimiento == "" ? NULL:  $vencimiento;
							$objTMD["reference3"]					= $unitaryPrice2."|".$unitaryPrice3;
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
							$objTMDNew["reference3"]					= $unitaryPrice2."|".$unitaryPrice3;
							
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
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_inputunpost","statusID",$objTMNew["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $oldStatusID != $objTMNew["statusID"] ){
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewInput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->inputunpost($companyID,$transactionID,$transactionMasterID);
			}
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_inventory_inputunpost/add');	
			}
		}
		catch(\Exception $ex){
				
				$data["session"]   = $dataSession;
				$data["exception"] = $ex;
				$data["urlLogin"]  = base_url();
				$data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
				$data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);				
				echo view("core_template/email_error_general",$data);
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
			
			
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$datView["objListPrice"]			= $objListPrice;			
			$datView["objListTypePreice"]		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			//Obtener el Registro	
			$datView["objComponentItem"]	 		= $objComponentItem;
			$datView["objComponentProvider"]	 	= $objComponentProvider;
			$datView["objComponentInputSinPost"]	= $objComponentInputSinPost;
			$datView["objComponentOrdenCompra"]		= $objComponentOrdenCompra;
			$datView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
			$datView["objTM"]	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]					= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_inputunpost","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTM"]->transactionOn 	= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objTMOrdenCompra"]		= $this->Transaction_Master_Model->get_rowByTransactionMasterID($companyID,helper_RequestGetValue($datView["objTM"]->reference4,"0") );
			
			$datView["objProvider"]				= $this->Provider_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);			
			$datView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID);
			$datView["objLegalDefault"]			= $this->Legal_Model->get_rowByPK($companyID,$datView["objProvider"]->branchID,$datView["objProvider"]->entityID);
			
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
			
			$objParameterWarehouseDefault		= $this->core_web_parameter->getParameter("INVENTORY_ITEM_WAREHOUSE_DEFAULT",$companyID);
			$warehouseDefault 					= $objParameterWarehouseDefault->value;
			$dataView["warehouseDefault"]		= $warehouseDefault;
			$objParameterProviderDefault		= $this->core_web_parameter->getParameter("CXP_PROVIDER_DEFAULT",$companyID);
			$objParameterProviderDefault 		= $objParameterProviderDefault->value;
			$dataView["providerDefault"]	 		= $this->Provider_Model->get_rowByProviderNumber($companyID,$objParameterProviderDefault);
			$dataView["providerNaturalDefault"]	 	= $this->Natural_Model->get_rowByPK($companyID,$dataView["providerDefault"]->branchID,$dataView["providerDefault"]->entityID);
			
			//Obtener el catalogo de tipos de precios
			$dataView["objListTypePreice"]			= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$dataView["objListPrice"]			= $objListPrice;
			$dataView["objComponentItem"] 			= $objComponentItem;
			$dataView["objComponentProvider"] 		= $objComponentProvider;
			$dataView["objComponentInputSinPost"]	= $objComponentInputSinPost;
			$dataView["objComponentOrdenCompra"]	= $objComponentOrdenCompra;
			
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
			//Obtener el componente Para mostrar la lista
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_inputunpost");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_inputunpost' NO EXISTE...");
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_inputunpost/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_inputunpost/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_inputunpost/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	function add_masinformacion($fnCallback="",$itemID="",$transactionMasterDetailID="",$positionID="",$lote="",$vencimiento="",$precio1="",$precio2=""){
		
			$fnCallback = helper_SegmentsByIndex($this->uri->getSegments(),1,$fnCallback);	
			$itemID = helper_SegmentsByIndex($this->uri->getSegments(),2,$itemID);	
			$transactionMasterDetailID = helper_SegmentsByIndex($this->uri->getSegments(),3,$transactionMasterDetailID);	
			$positionID = helper_SegmentsByIndex($this->uri->getSegments(),4,$positionID);	
			$lote = helper_SegmentsByIndex($this->uri->getSegments(),5,$lote);	
			$vencimiento = helper_SegmentsByIndex($this->uri->getSegments(),6,$vencimiento);	
			$precio1 = helper_SegmentsByIndex($this->uri->getSegments(),7,$precio1);	
			$precio2 = helper_SegmentsByIndex($this->uri->getSegments(),8,$precio2);	
		
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
			
			$data["itemID"] 					= $itemID;
			$data["transactionMasterDetailID"] 	= $transactionMasterDetailID;
			$data["positionID"] 				= $positionID;
			$data["fnCallback"] 				= $fnCallback;
			$data["lote"] 						= $lote;
			$data["vencimiento"] 				= $vencimiento;
			$data["precio1"] 					= $precio1;
			$data["precio2"] 					= $precio2;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_inventory_inputunpost/popup_masinformacion_item_head',$data);//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_inventory_inputunpost/popup_masinformacion_item_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_inventory_inputunpost/popup_masinformacion_item_script',$data);//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	
}
?>