<?php
//posme:2023-02-27
namespace App\Controllers;
class app_purchase_garantia extends _BaseController {
	
    
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
				$this->response->redirect(base_url()."/".'app_purchase_garantia/add');	
			} 		
			
			
		
		
		
			
			//Componente de facturacion
			$objComponentTransactionShare	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_workshop_garantias");
			if(!$objComponentTransactionShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_workshop_garantias' NO EXISTE...");
		
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
		
			$objComponentEmployer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployer)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
		
			$objComponentBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
		
			$objComponentItem		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
		
		
			//Tipo de Factura
			$dataView["objComponentBilling"]	= $objComponentBilling;
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			$dataView["objComponentEmployer"]	= $objComponentEmployer;		
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);									
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			
			$dataView["objComponentItem"]		= $objComponentItem;
			$dataView["objListBranch"]			= $this->Branch_Model->getByCompany($companyID);
			$dataView["objListCurrency"]		= $objListCurrency;
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
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_workshop_garantias","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);			
			$dataView["objListCatalogoArticulos"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","routeID",$companyID);//--Articulos
			$dataView["objListCatalogoSellos"]				= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","zoneID",$companyID);//Sello
			$dataView["objListCatalogoStatusGarantia"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","priorityID",$companyID);//Articulo
			$dataView["objListCatalogoAccesorio"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","areaID",$companyID);//Articulo
			
			
			//Obtener al cliente
			$dataView["objCustomer"]				= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCustomerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);
			$dataView["objCustomerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);

			//Obtener colaborador
			$dataView["objEmployer"]				= $this->Employee_Model->get_rowByEntityID($companyID,$dataView["objTransactionMaster"]->entityIDSecondary);
			$dataView["objEmployerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			$dataView["objEmployerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			
			//Obtener Factura
			$dataView["objBilling"]					= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$dataView["objTransactionMaster"]->note);
			
			
			$objParameterUrlPrinter 						= $this->core_web_parameter->getParameter("WORKSHOW_URL_PRINTER_GARANTIA",$companyID);
			$objParameterUrlPrinter 						= $objParameterUrlPrinter->value;
			$dataView["objParameterUrlPrinter"]	 			= $objParameterUrlPrinter;
			
			$objParameterUrlPrinter 						= $this->core_web_parameter->getParameter("WORKSHOW_URL_PRINTER_TALLER_GARANTIA_OUTPUT",$companyID);
			$objParameterUrlPrinter 						= $objParameterUrlPrinter->value;
			$dataView["objParameterUrlPrinterOutput"]	 	= $objParameterUrlPrinter;
			
			$objParameterUrlPrinter 						= $this->core_web_parameter->getParameter("WORKSHOW_URL_PRINTER_TALLER_GARANTIA_STIKER",$companyID);
			$objParameterUrlPrinter 						= $objParameterUrlPrinter->value;
			$dataView["objParameterUrlPrinterSticker"]	 	= $objParameterUrlPrinter;
			
			$objListComanyParameter							= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]		= $objParameterCantidadItemPoup->value;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_purchase_garantia/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_purchase_garantia/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_purchase_garantia/edit_script',$dataView);//--finview
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
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_workshop_garantias","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro			
			$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);			
			
			
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_workshop_garantias");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_workshop_garantias' NO EXISTE...");
			
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;			
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
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_workshop_garantias","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			//Actualizar Maestro			
			$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew["branchID"]						= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:i:s");			
			$objTMNew["currencyID"] 					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post			
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM->currencyID2,$objTMNew["currencyID"]);
			$objTMNew["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");
			$objTMNew["priorityID"] 					= /*inicio get post*/ $this->request->getPost("txtPriorityID");
			$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference1");
			$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference2");
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference3");			
			$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtDetailReference4");						
			//$objTMNew["descriptionReference"] 		= "reference1:input,reference2:input,reference3:Gestor de Cobro,reference4:Linea de credito del Cliente";
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"] 						= /*inicio get post*/ $this->request->getPost("txtDetailAmount");	
			$objTMNew["entityID"]						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTMNew["entityIDSecondary"]				= /*inicio get post*/ $this->request->getPost("txtEmployerID");
			$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");
			
			$db=db_connect();
			$db->transStart();
			
			//El Estado solo permite editar el workflow
			$actualizarTransaccionPermtida				= true;
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_workshop_garantias","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$actualizarTransaccionPermtida			= false;
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);				
			}
		
		
			//Actualizar Mas Informacion
			if($actualizarTransaccionPermtida == true)
			{
				$objTMI["routeID"]						= /*inicio get post*/ $this->request->getPost("txtRouteID");
				$objTMI["zoneID"]						= /*inicio get post*/ $this->request->getPost("txtZoneID");				
				$this->Transaction_Master_Info_Model-> update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMI);
				
			}
			
			//Obtener plantilla de whatsapp
			$warrning = false;
			if($this->core_web_whatsap->validSendMessage(APP_COMPANY))
			{
				
				$catalogItemEtapa 			= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","priorityID",$companyID,$objTMNew["priorityID"]);
				$catalogItemArticulo		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","routeID",$companyID,$objTMI["routeID"]);								
				$objPC_PlantillaWhatsapp 	= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_workshop_garantias.templates_whatsapp")->where("isActive",1)->where("flavorID",$dataSession["company"]->flavorID)->find();				
				$objPCD_PlantillaWhatsapp   = false;			
				if($objPC_PlantillaWhatsapp)
				{
					
					
					//Obtener la plantilla corresponeidnte a la etapa					
					$objPCD_PlantillaWhatsapp	= $this->Public_Catalog_Detail_Model->asObject()->
													where("publicCatalogID",$objPC_PlantillaWhatsapp[0]->publicCatalogID)->
													where( "isActive",1)->
													where( "name",$catalogItemEtapa->name)->
													findAll();					
					$themplate 					= "";
					if($objPCD_PlantillaWhatsapp)
					{
						
						$themplate 				= helper_RequestGetValueObjet($objPCD_PlantillaWhatsapp[0],"description","");
						if($themplate != "")
						{
							
							//Obtener al cliente
							$dataView["objCustomer"]				= $this->Customer_Model->get_rowByEntity($companyID,$objTMNew["entityID"]);
							$dataView["objCustomerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);
							$dataView["objCustomerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);

							//Obtener colaborador
							$dataView["objEmployer"]				= $this->Employee_Model->get_rowByEntityID($companyID,$objTMNew["entityIDSecondary"]);
							$dataView["objEmployerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
							$dataView["objEmployerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
							$dataView["objEmployerPhone"]			= $this->Entity_Phone_Model->get_rowByEntity( $dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID );
							$dataView["objEmployerPhoneNumber"]		= $dataView["objEmployerPhone"] ? $dataView["objEmployerPhone"][0]->number : "N/D";
							
							//Obtener Factura
							$dataView["objBilling"]					= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$objTMNew["note"]);							
							$dataView["objCatalogItemAreaID"] 		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_taller","areaID",$companyID,$objTMNew["areaID"]);
							
							$articleDesc	= strtoupper($catalogItemArticulo->name) == strtoupper("Otros") ? $objTMI["referenceClientName"] : $catalogItemArticulo->name; 
							$themplate 		= str_replace("{customer_name}",helper_RequestGetValueObjet($dataView["objCustomerNatural"],"firstName",""),$themplate);
							$themplate 		= str_replace("{employeer_name}",helper_RequestGetValueObjet($dataView["objEmployerNatural"],"firstName",""),$themplate);
							$themplate 		= str_replace("{employeer_phone}",$dataView["objEmployerPhoneNumber"],$themplate);
							$themplate 		= str_replace("{status_name}",helper_RequestGetValueObjet($dataView["objCatalogItemAreaID"],"name",""),$themplate);
							$themplate 		= str_replace("{transaction_number}",helper_RequestGetValueObjet($dataView["objBilling"],"transactionNumber",""),$themplate);
							$themplate		= str_replace("{amount}",$objTMNew["amount"],$themplate);
							$themplate 		= str_replace("{text}",$objTMNew["reference1"],$themplate);
							$themplate 		= str_replace("{article}",$articleDesc,$themplate);
							
							$numerDestino 	= clearNumero($dataView["objCustomer"]->phoneNumber); 
							$warrning = true;
							$this->core_web_notification->set_message(false,"A:".$numerDestino." - ".substr($themplate,0,55)." ...");
							
							
							
							//wg-if($objCompany->type=="globalpro_decline")
							//wg-{
							//wg-	$this->core_web_whatsap->sendMessageByWaapi(
							//wg-		APP_COMPANY, 
							//wg-		$themplate,
							//wg-		$numerDestino
							//wg-		/*"50587125827"*/
							//wg-	);
							//wg-}
							//wg-elseif ($objCompany->type =="globalpro")
							//wg-{
                            //wg-    $this->core_web_whatsap->sendMessageByLiveconnect(
                            //wg-        APP_COMPANY,
                            //wg-        $themplate,
                            //wg-        $numerDestino
							//wg-		/*"50587125827"*/
                            //wg-    );
                            //wg-}
							//wg-else
							//wg-{
							//wg-	$this->core_web_whatsap->sendMessageUltramsg(
							//wg-		APP_COMPANY, 
							//wg-		$themplate,
							//wg-		$numerDestino
							//wg-		/*"50587125827"*/
							//wg-	);
							//wg-}
							
						}
						else 
						{
							$warrning = true;
							$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, configurar mensaje en plantilla (tb_transaction_master_workshop_garantias.templates_whatsapp) campo Grupo");	
						}
					}
					else {
						$warrning = true;
						$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, configurar mensaje en plantilla (tb_transaction_master_workshop_garantias.templates_whatsapp)");	
					}
				}	
				else {
					$warrning = true;
					$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, configurar plantailla (tb_transaction_master_workshop_garantias.templates_whatsapp)");	
				}				
			}
			else {
				$warrning = true;
				$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, por falta de saldo.");
			}
			
			
			if($db->transStatus() !== false)
			{
				
				$db->transCommit();	
				
				if($warrning == false )
				$this->core_web_notification->set_message(false,SUCCESS);	
				$this->response->redirect(base_url()."/".'app_purchase_garantia/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
				
			}
			else{
				$db->transRollback();	
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_purchase_garantia/add');	
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
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_workshop_garantias");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_workshop_garantias' NO EXISTE...");
			
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_workshop_garantias",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_workshop_garantias",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);			
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:i:s");
			$objTM["componentID"] 					= $objComponentShare->componentID;			
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
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");
			$objTM["priorityID"] 					= /*inicio get post*/ $this->request->getPost("txtPriorityID");
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;			
			$objTM["entityID"]						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTM["entityIDSecondary"]				= /*inicio get post*/ $this->request->getPost("txtEmployerID");
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");
			
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			
			//Ingresar mas Informacion
			$objTMI["companyID"]					= $objTM["companyID"];
			$objTMI["transactionID"]				= $objTM["transactionID"];
			$objTMI["transactionMasterID"]			= $transactionMasterID ;			
			$objTMI["routeID"]						= /*inicio get post*/ $this->request->getPost("txtRouteID");
			$objTMI["zoneID"]						= /*inicio get post*/ $this->request->getPost("txtZoneID");
			$this->Transaction_Master_Info_Model->insert_app_posme($objTMI);
			
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$pathDocument = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$transactionMasterID;
			if(!file_exists ($pathDocument))
			{
				mkdir( $pathDocument,0700,true);
			}
			
			//Obtener plantilla de whatsapp
			$warrning = false;
			if($this->core_web_whatsap->validSendMessage(APP_COMPANY))
			{
				
				$catalogItemEtapa 			= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","priorityID",$companyID,$objTM["priorityID"]);
				$catalogItemArticulo		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","routeID",$companyID,$objTMI["routeID"]);								
				$objPC_PlantillaWhatsapp 	= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_workshop_garantias.templates_whatsapp")->where("isActive",1)->where("flavorID",$dataSession["company"]->flavorID)->find();				
				$objPCD_PlantillaWhatsapp   = false;			
				if($objPC_PlantillaWhatsapp)
				{
					
					
					//Obtener la plantilla corresponeidnte a la etapa					
					$objPCD_PlantillaWhatsapp	= $this->Public_Catalog_Detail_Model->asObject()->
													where("publicCatalogID",$objPC_PlantillaWhatsapp[0]->publicCatalogID)->
													where( "isActive",1)->
													where( "name",$catalogItemEtapa->name)->
													findAll();					
					$themplate 					= "";
					if($objPCD_PlantillaWhatsapp)
					{
						
						$themplate 				= helper_RequestGetValueObjet($objPCD_PlantillaWhatsapp[0],"description","");
						if($themplate != "")
						{
							
							//Obtener al cliente
							$dataView["objCustomer"]				= $this->Customer_Model->get_rowByEntity($companyID,$objTM["entityID"]);
							$dataView["objCustomerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);
							$dataView["objCustomerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);

							//Obtener colaborador
							$dataView["objEmployer"]				= $this->Employee_Model->get_rowByEntityID($companyID,$objTM["entityIDSecondary"]);
							$dataView["objEmployerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
							$dataView["objEmployerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
							$dataView["objEmployerPhone"]			= $this->Entity_Phone_Model->get_rowByEntity( $dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID );
							$dataView["objEmployerPhoneNumber"]		= $dataView["objEmployerPhone"] ? $dataView["objEmployerPhone"][0]->number : "N/D";
							
							//Obtener Factura
							$dataView["objBilling"]					= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$objTM["note"]);							
							$dataView["objCatalogItemAreaID"] 		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_taller","areaID",$companyID,$objTM["areaID"]);
							
							$articleDesc	= strtoupper($catalogItemArticulo->name) == strtoupper("Otros") ? $objTMI["referenceClientName"] : $catalogItemArticulo->name; 
							$themplate 		= str_replace("{customer_name}",helper_RequestGetValueObjet($dataView["objCustomerNatural"],"firstName",""),$themplate);
							$themplate 		= str_replace("{employeer_name}",helper_RequestGetValueObjet($dataView["objEmployerNatural"],"firstName",""),$themplate);
							$themplate 		= str_replace("{employeer_phone}",$dataView["objEmployerPhoneNumber"],$themplate);
							$themplate 		= str_replace("{status_name}",helper_RequestGetValueObjet($dataView["objCatalogItemAreaID"],"name",""),$themplate);
							$themplate 		= str_replace("{transaction_number}",helper_RequestGetValueObjet($dataView["objBilling"],"transactionNumber",""),$themplate);
							$themplate		= str_replace("{amount}",$objTM["amount"],$themplate);
							$themplate 		= str_replace("{text}",$objTM["reference1"],$themplate);
							$themplate 		= str_replace("{article}",$articleDesc,$themplate);
							
							$numerDestino 	= clearNumero($dataView["objCustomer"]->phoneNumber); 
							$warrning = true;
							$this->core_web_notification->set_message(false,"A:".$numerDestino." - ".substr($themplate,0,55)." ...");
							
							
							
							//wg-if($objCompany->type=="globalpro_decline")
							//wg-{
							//wg-	$this->core_web_whatsap->sendMessageByWaapi(
							//wg-		APP_COMPANY, 
							//wg-		$themplate,
							//wg-		$numerDestino
							//wg-		/*"50587125827"*/
							//wg-	);
							//wg-}
							//wg-elseif ($objCompany->type =="globalpro")
							//wg-{
                            //wg-    $this->core_web_whatsap->sendMessageByLiveconnect(
                            //wg-        APP_COMPANY,
                            //wg-        $themplate,
                            //wg-        $numerDestino
							//wg-		/*"50587125827"*/
                            //wg-    );
                            //wg-}
							//wg-else
							//wg-{
							//wg-	$this->core_web_whatsap->sendMessageUltramsg(
							//wg-		APP_COMPANY, 
							//wg-		$themplate,
							//wg-		$numerDestino
							//wg-		/*"50587125827"*/
							//wg-	);
							//wg-}
							
						}
						else 
						{
							$warrning = true;
							$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, configurar mensaje en plantilla (tb_transaction_master_workshop_garantias.templates_whatsapp) campo Grupo");	
						}
					}
					else {
						$warrning = true;
						$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, configurar mensaje en plantilla (tb_transaction_master_workshop_garantias.templates_whatsapp)");	
					}
				}	
				else {
					$warrning = true;
					$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, configurar plantailla (tb_transaction_master_workshop_garantias.templates_whatsapp)");	
				}				
			}
			else {
				$warrning = true;
				$this->core_web_notification->set_message(true,"Garantia guardado correctamente, WhatsApp no enviado, por falta de saldo.");
			}
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				if($warrning == false )
				$this->core_web_notification->set_message(false,SUCCESS);	
			
				$this->response->redirect(base_url()."/".'app_purchase_garantia/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_purchase_garantia/add');	
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
				$this->response->redirect(base_url()."/".'app_purchase_garantia/add');
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
				$this->response->redirect(base_url()."/".'app_purchase_garantia/add');
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
			
		    return $resultView;
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
			$transactionID 						= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_workshop_garantias",0);
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
		
			$objComponentEmployer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployer)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
		
			$objComponentBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
			$objComponentItem		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
		
			//Tipo de Factura
			$dataView["objListBranch"]			= $this->Branch_Model->getByCompany($companyID);
			$dataView["objComponentBilling"]	= $objComponentBilling;
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			$dataView["objComponentEmployer"]	= $objComponentEmployer;
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
			$dataView["objComponentItem"]		= $objComponentItem;

			$objParameterExchangePurchase		= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_PURCHASE",$companyID);
			$dataView["exchangeRatePurchase"]	= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) - $objParameterExchangePurchase->value;			
			$objParameterExchangeSales			= $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID);
			$dataView["exchangeRateSale"]		= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID) + $objParameterExchangeSales->value;		
		
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;
		
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_workshop_garantias","statusID",$companyID,$branchID,$roleID);
			$dataView["objListCatalogoArticulos"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","routeID",$companyID);//--Articulos
			$dataView["objListCatalogoSellos"]				= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","zoneID",$companyID);//Sello
			$dataView["objListCatalogoStatusGarantia"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","priorityID",$companyID);//Articulo
			$dataView["objListCatalogoAccesorio"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","areaID",$companyID);//Articulo
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_purchase_garantia/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_purchase_garantia/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_purchase_garantia/news_script',$dataView);//--finview
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_workshop_garantias");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_workshop_garantias' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_purchase_garantia/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_purchase_garantia/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_purchase_garantia/list_script');//--finview
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_workshop_garantias","statusID",$datView["objTM"]->statusID,$companyID,APP_BRANCH,APP_ROL_SUPERADMIN);
			
			
			

			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
		
			$objComponentEmployer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployer)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
			$objComponentFile	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_file");
			if(!$objComponentFile)
			throw new \Exception("EL COMPONENTE 'tb_file' NO EXISTE...");

		
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);									
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailDocument"]		= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentFile->componentID);
			$dataView["objTransactionMasterInvoice"]			= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$dataView["objTransactionMaster"]->note);
			//Obtener al cliente
			$dataView["objCustomer"]				= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCustomerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);
			$dataView["objCustomerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);

			//Obtener colaborador
			$dataView["objEmployer"]				= $this->Employee_Model->get_rowByEntityID($companyID,$dataView["objTransactionMaster"]->entityIDSecondary);
			$dataView["objEmployerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			$dataView["objEmployerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			
			//Obtener Factura
			$dataView["objBilling"]					= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$dataView["objTransactionMaster"]->note);
			
			
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);						
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_workshop_garantias","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objListEstadosEquipo"]	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","areaID",$companyID);
			$dataView["objItemEstadosEquipo"]	= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","areaID",$companyID, $dataView["objTransactionMaster"]->areaID );
			$dataView["objListAccesorios"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","priorityID",$companyID);
			$dataView["objItemAccesorios"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","priorityID",$companyID,$dataView["objTransactionMaster"]->priorityID);
			$dataView["objListMarca"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","zoneID",$companyID);
			$dataView["objItemMarca"]			= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","zoneID",$companyID,$dataView["objTransactionMasterInfo"]->zoneID);
			$dataView["objListArticulos"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","routeID",$companyID);
			$dataView["objItemArticulo"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","routeID",$companyID,$dataView["objTransactionMasterInfo"]->routeID);
			
			
			
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterGarantiasGlobalPro(
			    "GARANTIA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],				
			    $objParameterTelefono,				
				$dataView ,
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
			
		    return $resultView;
		}
	}
	
	function viewPrinterFormatoA4Output(){
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_workshop_garantias","statusID",$datView["objTM"]->statusID,$companyID,APP_BRANCH,APP_ROL_SUPERADMIN);
			
			
			

			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
		
			$objComponentEmployer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployer)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
			$objComponentFile	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_file");
			if(!$objComponentFile)
			throw new \Exception("EL COMPONENTE 'tb_file' NO EXISTE...");

		
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);									
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailDocument"]		= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentFile->componentID);
			
			//Obtener al cliente
			$dataView["objCustomer"]				= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCustomerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);
			$dataView["objCustomerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);

			//Obtener colaborador
			$dataView["objEmployer"]				= $this->Employee_Model->get_rowByEntityID($companyID,$dataView["objTransactionMaster"]->entityIDSecondary);
			$dataView["objEmployerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			$dataView["objEmployerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			
			//Obtener Factura
			$dataView["objBilling"]					= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$dataView["objTransactionMaster"]->note);
			
			
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);						
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_workshop_garantias","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objListEstadosEquipo"]	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","areaID",$companyID);
			$dataView["objListAccesorios"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","priorityID",$companyID);
			$dataView["objItemAccesorios"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","priorityID",$companyID,$dataView["objTransactionMaster"]->priorityID);
			$dataView["objListMarca"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","zoneID",$companyID);
			$dataView["objItemMarca"]			= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","zoneID",$companyID,$dataView["objTransactionMasterInfo"]->zoneID);
			$dataView["objListArticulos"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","routeID",$companyID);
			$dataView["objItemArticulo"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","routeID",$companyID,$dataView["objTransactionMasterInfo"]->routeID);
			
			
			
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterTallerOutputGarantiaGlobalPro(
			    "GARANTIA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],				
			    $objParameterTelefono,				
				$dataView ,
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
			
		    return $resultView;
		}
	}
	
	function viewPrinterFormatoA4Stiker(){
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
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_workshop_garantias","statusID",$datView["objTM"]->statusID,$companyID,APP_BRANCH,APP_ROL_SUPERADMIN);
			
			
			

			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
		
			$objComponentEmployer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployer)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
			$objComponentFile	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_file");
			if(!$objComponentFile)
			throw new \Exception("EL COMPONENTE 'tb_file' NO EXISTE...");

		
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);									
			$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailDocument"]		= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentFile->componentID);
			
			//Obtener al cliente
			$dataView["objCustomer"]				= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCustomerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);
			$dataView["objCustomerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objCustomer"]->companyID,$dataView["objCustomer"]->branchID,$dataView["objCustomer"]->entityID);

			//Obtener colaborador
			$dataView["objEmployer"]				= $this->Employee_Model->get_rowByEntityID($companyID,$dataView["objTransactionMaster"]->entityIDSecondary);
			$dataView["objEmployerNatural"]			= $this->Natural_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			$dataView["objEmployerLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objEmployer"]->companyID,$dataView["objEmployer"]->branchID,$dataView["objEmployer"]->entityID);
			
			//Obtener Factura
			$dataView["objBilling"]					= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$dataView["objTransactionMaster"]->note);
			
			
			$dataView["objListCurrency"]		= $objListCurrency;
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);						
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_workshop_garantias","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objListEstadosEquipo"]	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","areaID",$companyID);
			$dataView["objListAccesorios"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","priorityID",$companyID);
			$dataView["objItemAccesorios"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","priorityID",$companyID,$dataView["objTransactionMaster"]->priorityID);
			$dataView["objListMarca"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","zoneID",$companyID);
			$dataView["objItemMarca"]			= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","zoneID",$companyID,$dataView["objTransactionMasterInfo"]->zoneID);
			$dataView["objListArticulos"]		= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_workshop_garantias","routeID",$companyID);
			$dataView["objItemArticulo"]		= $this->core_web_catalog->getCatalogItem("tb_transaction_master_workshop_garantias","routeID",$companyID,$dataView["objTransactionMasterInfo"]->routeID);
			
			
			
			
			
			//Generar Reporte
			$html = helper_reporteA4mmTransactionMasterTallerStickerGlobalPro(
			    "GARANTIA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],				
			    $objParameterTelefono,				
				$dataView ,
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
			
		    return $resultView;
		}
	}
	
	
	
}
?>