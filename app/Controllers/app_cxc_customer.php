<?php
//posme:2023-02-27
namespace App\Controllers;
use Config\Services;

class app_cxc_customer extends _BaseController {
	
       
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
									
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$branchID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri	
			$entityID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"entityID");//--finuri	
			$callback		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"callback");//--finuri	
			
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;		
			$callback		= $callback === "" ?  "false" : $callback ;
			
			if((!$companyID || !$branchID || !$entityID))
			{ 
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objLegal"]	 			= $this->Legal_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objCustomer"]	 			= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objEntityListEmail"]		= $this->Entity_Email_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objEntityListPhone"]		= $this->Entity_Phone_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objCustomerCredit"]		= $this->Customer_Credit_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objCustomerCreditLine"]	= $this->Customer_Credit_Line_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objCustomerSinRiesgo"]	= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedula_FileName($companyID,str_replace("-","",$datView["objCustomer"]->identification));
			$datView["objPaymentMethod"]        = $this->Customer_Payment_Method_Model->get_rowByEntityID($entityID);
			$datView["callback"]				= $callback;
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			$objComponentAccount				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new \Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			$objComponentEmployer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployer)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			$objListEntityAccount 						= $this->Entity_Account_Model->get_rowByEntity($companyID,$objComponent->componentID,$entityID);
			$objFirstEntityAccount						= $objListEntityAccount[0];
			$objAccount 								= $this->Account_Model->get_rowByPK($companyID,$objFirstEntityAccount->accountID);
			
			//Obtener Informacion
			$datView["objComponentEmployer"]			= $objComponentEmployer;
			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_customer","statusID",$datView["objCustomer"]->statusID,$companyID,$branchIDUser,$roleID);
			$datView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);			
			$datView["objComponent"] 					= $objComponent;
			$datView["objCurrency"]						= $this->core_web_currency->getCurrencyDefault($companyID);
			$datView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer","identificationType",$companyID);
			$datView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","countryID",$companyID);
			$datView["objListState"]					= $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","stateID",$companyID,$datView["objCustomer"]->countryID);
			$datView["objListCity"]						= $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","cityID",$companyID,$datView["objCustomer"]->stateID);
			$datView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","clasificationID",$companyID);
			$datView["objListCustomerTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","customerTypeID",$companyID);
			$datView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer","categoryID",$companyID);
			$datView["objListSubCategoryID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","subCategoryID",$companyID);
			$datView["objListTypePay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","typePay",$companyID);
			$datView["objListFormContactID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","formContactID",$companyID);
			$datView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","payConditionID",$companyID);
			$datView["objListSexoID"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","sexoID",$companyID);
			$datView["objListTypeID"]			        = $this->core_web_catalog->getCatalogAllItem("tb_customer_payment_method","typeID",$companyID);
			$datView["objListEstadoCivilID"]			= $this->core_web_catalog->getCatalogAllItem("tb_naturales","statusID",$companyID);
			
			$datView["objListProfesionID"] 				= $this->core_web_catalog->getCatalogAllItem("tb_naturales","profesionID",$companyID);
			
			$datView["objListTypeFirmID"] 				= $this->core_web_catalog->getCatalogAllItem("tb_customer","typeFirm",$companyID);
			$datView["objComponentAccount"] 			= $objComponentAccount;
			$datView["objEntityAccount"] 				= $objFirstEntityAccount;
			$datView["objAccount"] 						= $objAccount;
			$datView["useMobile"]						= $dataSession["user"]->useMobile;			
			$datView["company"]							= $dataSession["company"];
			
			//Obtener colaborador
			$datView["objEmployer"]					= $this->Employee_Model->get_rowByEntityID($companyID,$datView["objCustomer"]->entityContactID);
			$entityEmployeerID						= helper_RequestGetValueObjet($datView["objEmployer"],"entityID",0);
			$datView["objEmployerNatural"]			= $this->Natural_Model->get_rowByPK($datView["objCustomer"]->companyID,$datView["objCustomer"]->branchID,$entityEmployeerID);
			$datView["objEmployerLegal"]			= $this->Legal_Model->get_rowByPK($datView["objCustomer"]->companyID,$datView["objCustomer"]->branchID,$entityEmployeerID);
			
			//Obtener Cliente que referencia
			$datView["objCustomerReferente"]				= null;
			$datView["objCustomerReferenteNatural"]			= null;
			$datView["objCustomerReferenteLegal"]			= null;
			$entityIDCustomerReference 						= $datView["objCustomer"]->entityReferenceID;
			if(!is_null($entityIDCustomerReference) && is_numeric($entityIDCustomerReference) && floatval($entityIDCustomerReference) > 0 )
			{
				
				$datView["objCustomerReferente"]			= $this->Customer_Model->get_rowByEntity($companyID,$datView["objCustomer"]->entityReferenceID);			
				$datView["objCustomerReferenteNatural"]		= $this->Natural_Model->get_rowByPK($datView["objCustomer"]->companyID,$datView["objCustomer"]->branchID,$datView["objCustomerReferente"]->entityID);
				$datView["objCustomerReferenteLegal"]		= $this->Legal_Model->get_rowByPK($datView["objCustomer"]->companyID,$datView["objCustomer"]->branchID,$datView["objCustomerReferente"]->entityID);
			}
			
			
			$datView["objListSituationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer_frecuency_actuations","situationID",$companyID);
			$datView["objListFrecuencyContactID"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_frecuency_actuations","frecuencyContactID",$companyID);
			$datView['objCustomerFrecuency']		= $this->Customer_Frecuency_Actuations_Model->get_rowByEntityID($entityID);
			//Obtener catalogos de tipos de leads
			$objPCatalogTypeLeads 	= $this->Public_Catalog_Model->asObject()->
										where("systemName","tb_customer.typeLeads")->
										where("flavorID",$dataSession["company"]->flavorID)->
										where("isActive",1)->
										first();
			$objPCItemTypeLeads		= $this->Public_Catalog_Detail_Model->asObject()->
										where("publicCatalogID",helper_RequestGetValueObjet($objPCatalogTypeLeads,"publicCatalogID",0))->
										where( "isActive",1)->
										findAll();
			$datView["objPCItemTypeLeads"]	
										= $objPCItemTypeLeads;
										
			//Obtener catalogos de sub tipos de leads
			$objPCatalogSubTypeLeads 	= $this->Public_Catalog_Model->asObject()->
										where("systemName","tb_customer.subTypeLeads")->
										where("flavorID",$dataSession["company"]->flavorID)->
										where("isActive",1)->
										first();
			$objPCItemSubTypeLeads		= $this->Public_Catalog_Detail_Model->asObject()->
										where("publicCatalogID",helper_RequestGetValueObjet($objPCatalogSubTypeLeads,"publicCatalogID",0))->
										where( "isActive",1)->
										findAll();
			$datView["objPCItemSubTypeLeads"]	
										= $objPCItemSubTypeLeads;
										
			//Obtener catalogos de categoria de leads
			$objPCatalogCategoryLeads 	= $this->Public_Catalog_Model->asObject()->
										where("systemName","tb_customer.categoryLeads")->
										where("flavorID",$dataSession["company"]->flavorID)->
										where("isActive",1)->
										first();
			$objPCItemCategoryLeads		= $this->Public_Catalog_Detail_Model->asObject()->
										where("publicCatalogID",helper_RequestGetValueObjet($objPCatalogCategoryLeads,"publicCatalogID",0))->
										where( "isActive",1)->
										findAll();
			$datView["objPCItemCategoryLeads"]	= $objPCItemCategoryLeads;
		
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameter("INVOICE_CANTIDAD_ITEM",$companyID);			
			$objParameterCantidadItemPoup 				= $objParameterCantidadItemPoup->value;
			$datView["objParameterCantidadItemPoup"] 	= $objParameterCantidadItemPoup;
			
			
			//Obtener el componente de Item
            $objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if(!$objComponentItem)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$datView["objComponentItem"] = $objComponentItem;
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			
			$dataSession["head"]			= /*--inicio view*/ view('app_cxc_customer/edit_head',$datView);//--finview			
			$dataSession["body"]			= /*--inicio view*/ view('app_cxc_customer/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxc_customer/edit_script',$datView);//--finview
			$dataSession["footer"]			= "";				
			
			
			if($callback == "false")
			{
				
				return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			}
			else
			{
				
				return view("core_masterpage/default_popup",$dataSession);//--finview-r
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
			$companyID 			= /*inicio get post*/ $this->request->getPost("companyID");
			$branchID 			= /*inicio get post*/ $this->request->getPost("branchID");				
			$entityID 			= /*inicio get post*/ $this->request->getPost("entityID");				
			
			if((!$companyID && !$branchID && !$entityID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			
			if ($entityID == APP_CUSTOMER01)
			{
				throw new \Exception("No es posible eliminar el cliente, edite el nombre");
			}
			
			
			if ($entityID == APP_CUSTOMER02)
			{
				throw new \Exception("No es posible eliminar el cliente, edite el nombre");
			}
			
			
			
			//OBTENER EL CLIENTE
			$objCustomer 		= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Customer_Model->delete_app_posme($companyID,$branchID,$entityID);
					
			
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
			
				
			
			
			
				
			
			
			
			
			
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			$companyID_ 							= /*inicio get post*/ $this->request->getPost("txtCompanyID");
			$branchID_								= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$entityID_								= /*inicio get post*/ $this->request->getPost("txtEntityID");
			$callback  								= /*inicio get post*/ $this->request->getPost("txtCallback"); 									
			$objCustomer							= $this->Customer_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
			$oldStatusID 							= $objCustomer->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objCustomer["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->Customer_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomer);
			}
			else{
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
				$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
				$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				
				$objNatural["statusID"]		= /*inicio get post*/ $this->request->getPost("txtCivilStatusID");//--fin peticion get o post
				
				$objNatural["profesionID"]	= /*inicio get post*/ $this->request->getPost("txtProfesionID");//--fin peticion get o post
				$this->Natural_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objNatural);
				$objLegal["isActive"]		= true;
				$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
				$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
				$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Legal_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objLegal);
				
				$findPaymentMethod            = $this->Customer_Payment_Method_Model->get_rowByEntityID($entityID_);
				$objPaymentMethod['name']     = /*inicio get post*/ $this->request->getPost("txtNombreTarjeta");//--fin peticion get o post;
				$objPaymentMethod['number']   = /*inicio get post*/ $this->request->getPost("txtNumeroTarjeta");//--fin peticion get o post;
				$objPaymentMethod['email']    = /*inicio get post*/ $this->request->getPost("txtEmailTarjeta");//--fin peticion get o post;
				$objPaymentMethod['expirationDate']=/*inicio get post*/ $this->request->getPost("txtVencimientoTarjeta");//--fin peticion get o post;
				$objPaymentMethod['cvc']      = /*inicio get post*/ $this->request->getPost("txtCodigoCvc");//--fin peticion get o post;
				$objPaymentMethod['typeID']   = /*inicio get post*/ $this->request->getPost("txtTipoTarjeta");//--fin peticion get o post;
				if(is_null($findPaymentMethod)){
					$objPaymentMethod['entityID']=$entityID_;
					$objPaymentMethod['statusID']=1;
					$objPaymentMethod['isActive']=true;
					$this->Customer_Payment_Method_Model->insert_app_posme($objPaymentMethod);
				}else{
					$this->Customer_Payment_Method_Model->update_app_posme($entityID_,$objPaymentMethod);
				}

				$objCustomer 						= NULL;
				$objCustomer["identificationType"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
				$objCustomer["identification"]		= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
				
				$validarCedula 				= $this->core_web_parameter->getParameterValue("CXC_VALIDAR_CEDULA_REPETIDA",$companyID);
				
				//validar que se permita la omision de la cedula
				if(strcmp($validarCedula,"true") == 0){
					//Validar que ya existe el cliente
					$objCustomerOld						= $this->Customer_Model->get_rowByIdentification($companyID,$objCustomer["identification"]);
					if($objCustomerOld)
					{
						if($objCustomerOld->entityID != $entityID_ )
						{
							throw new \Exception("Error identificacion del cliente ya existe.");
						}
					}
				}
				
				
				$customerFrecuencyActuations =/*inicio get post*/ $this->request->getPost("customerFrecuencyActuations");//--fin peticion get o post
				if(!is_null($customerFrecuencyActuations)){
					$this->Customer_Frecuency_Actuations_Model->deleteWhereIDNotIn($entityID_,$customerFrecuencyActuations);
					$nombreRecordatorios = /*inicio get post*/ $this->request->getPost("txtNombreRecordatorioArray");//--fin peticion get o post
					$situationes         = /*inicio get post*/ $this->request->getPost("txtSituationIDArray");//--fin peticion get o post
					$frecuenias 		 = /*inicio get post*/ $this->request->getPost("txtFrecuencyContactIDArray");//--fin peticion get o post
					$cant = count($customerFrecuencyActuations)-1;
					for($i=$cant; $i>=0;$i--){
						$objFrecuencyActuations['entityID'] = $entityID_;						
						$objFrecuencyActuations['name'] = $nombreRecordatorios[$i];
						$objFrecuencyActuations['situationID'] = $situationes[$i];
						$objFrecuencyActuations['frecuencyContactID'] = $frecuenias[$i];
						$objFrecuencyActuations['isActive'] = 1;
						$idFrecuencia = $customerFrecuencyActuations[$i];
						if($idFrecuencia==0){
							$objFrecuencyActuations['createdOn'] = date('Y-m-d H:i:s');
							$objFrecuencyActuations['isApply'] = 0;
							$this->Customer_Frecuency_Actuations_Model->insert_app_posme($objFrecuencyActuations);
						}else{
							$this->Customer_Frecuency_Actuations_Model->update_app_posme($entityID_,$idFrecuencia,$objFrecuencyActuations);
						}	
					}
				}

				$objCustomer["countryID"]			= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
				$objCustomer["stateID"]				= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
				$objCustomer["cityID"]				= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
				$objCustomer["location"]			= /*inicio get post*/ $this->request->getPost("txtLocation");//--fin peticion get o post
				$objCustomer["address"]				= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$objCustomer["currencyID"]			= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post
				$objCustomer["clasificationID"]		= /*inicio get post*/ $this->request->getPost('txtClasificationID');//--fin peticion get o post
				$objCustomer["categoryID"]			= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
				$objCustomer["subCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtSubCategoryID');//--fin peticion get o post
				$objCustomer["customerTypeID"]		= /*inicio get post*/ $this->request->getPost("txtCustomerTypeID");//--fin peticion get o post
				$objCustomer["birthDate"]			= /*inicio get post*/ $this->request->getPost("txtBirthDate");//--fin peticion get o post
				$objCustomer["dateContract"]		= /*inicio get post*/ $this->request->getPost("txtDateContract");//--fin peticion get o post
				$objCustomer["statusID"]			= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$objCustomer["typePay"]				= /*inicio get post*/ $this->request->getPost('txtTypePayID');//--fin peticion get o post
				$objCustomer["payConditionID"]		= /*inicio get post*/ $this->request->getPost('txtPayConditionID');//--fin peticion get o post
				$objCustomer["sexoID"]				= /*inicio get post*/ $this->request->getPost('txtSexoID');//--fin peticion get o post
				$objCustomer["reference1"]			= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
				$objCustomer["reference2"]			= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
				$objCustomer["reference3"]			= /*inicio get post*/ $this->request->getPost("txtReference3");//--fin peticion get o post
				$objCustomer["reference4"]			= /*inicio get post*/ $this->request->getPost("txtReference4");//--fin peticion get o post
				$objCustomer["reference5"]			= /*inicio get post*/ $this->request->getPost("txtReference5");//--fin peticion get o post
				$objCustomer["balancePoint"]		= /*inicio get post*/ $this->request->getPost("txtBalancePoint");//--fin peticion get o post
				$objCustomer["phoneNumber"]			= /*inicio get post*/ $this->request->getPost("txtPhoneNumber");//--fin peticion get o post
				$objCustomer["typeFirm"]			= /*inicio get post*/ $this->request->getPost("txtTypeFirmID");//--fin peticion get o post
				$objCustomer["budget"]				= /*inicio get post*/ $this->request->getPost("txtBudget");//--fin peticion get o post
				$objCustomer["isActive"]			= true;
				$objCustomer["entityContactID"]		= /*inicio get post*/ $this->request->getPost("txtEmployerID");
				$objCustomer["entityReferenceID"]	= /*inicio get post*/ $this->request->getPost("txtCustomerIDReference");
				$objCustomer["modifiedOn"]			= helper_getDateTime();
				$objCustomer["formContactID"]		= /*inicio get post*/ $this->request->getPost("txtFormContactID");//--fin peticion get o post				
				if(isset($_POST['txtAllowWhatsappPromotions']))
					$objCustomer["allowWhatsappPromotions"]	 	= $this->request->getPost("txtAllowWhatsappPromotions");
				else 
					$objCustomer["allowWhatsappPromotions"]		= 0 ;
						
				if(isset($_POST['txtAllowWhatsappCollection']))
					$objCustomer["allowWhatsappCollection"]	 	= $this->request->getPost("txtAllowWhatsappCollection");
				else 
					$objCustomer["allowWhatsappCollection"]		= 0 ;
				
				$this->Customer_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomer);
				
				//Actualizar Customer Credit
				$objCustomerCredit 							= $this->Customer_Credit_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
				$objCustomerCreditNew["limitCreditDol"] 	= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtLimitCreditDol"));
				$objCustomerCreditNew["balanceDol"] 		= $objCustomerCreditNew["limitCreditDol"] - ($objCustomerCredit->limitCreditDol - $objCustomerCredit->balanceDol);
				$objCustomerCreditNew["incomeDol"] 			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtIncomeDol"));
				$this->Customer_Credit_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
				
				//actualizar cuenta
				$objListEntityAccount 					= $this->Entity_Account_Model->get_rowByEntity($companyID_,$objComponent->componentID,$entityID_);
				$objFirstEntityAccount					= $objListEntityAccount[0];
				
				$objEntityAccount["accountID"]			= empty(/*inicio get post*/ $this->request->getPost("txtAccountID")) ? 0 : /*inicio get post*/ $this->request->getPost("txtAccountID");
				$this->Entity_Account_Model->update_app_posme($objFirstEntityAccount->entityAccountID,$objEntityAccount);
			
			}
			
			
			//Email
			$this->Entity_Email_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityEmail 				= /*inicio get post*/ $this->request->getPost("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= /*inicio get post*/ $this->request->getPost("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $companyID_;
				$objEntityEmail["branchID"]		= $branchID_;
				$objEntityEmail["entityID"]		= $entityID_;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key] == 1 ? true : false;
				$this->Entity_Email_Model->insert_app_posme($objEntityEmail);
			}
			
			//Phone
			$this->Entity_Phone_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityPhoneTypeID			= /*inicio get post*/ $this->request->getPost("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $companyID_;
				$objEntityPhone["branchID"]		= $branchID_;
				$objEntityPhone["entityID"]		= $entityID_;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert_app_posme($objEntityPhone);
			}	
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");
			$arrayListCreditLineID			= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$arrayListCreditCurrencyID		= /*inicio get post*/ $this->request->getPost("txtCreditCurrencyID");
			$arrayListCreditStatusID		= /*inicio get post*/ $this->request->getPost("txtCreditStatusID");
			$arrayListCreditInterestYear	= /*inicio get post*/ $this->request->getPost("txtCreditInterestYear");
			$arrayListCreditInterestPay		= /*inicio get post*/ $this->request->getPost("txtCreditInterestPay");
			$arrayListCreditTotalPay		= /*inicio get post*/ $this->request->getPost("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= /*inicio get post*/ $this->request->getPost("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= /*inicio get post*/ $this->request->getPost("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= /*inicio get post*/ $this->request->getPost("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= /*inicio get post*/ $this->request->getPost("txtCreditDateLastPay");
			$arrayListCreditTerm			= /*inicio get post*/ $this->request->getPost("txtCreditTerm");
			$arrayListCreditNote			= /*inicio get post*/ $this->request->getPost("txtCreditNote");
			$arrayListCreditLine			= /*inicio get post*/ $this->request->getPost("txtLine");
			$arrayListCreditNumber			= /*inicio get post*/ $this->request->getPost("txtLineNumber");
			$arrayListCreditLimit			= /*inicio get post*/ $this->request->getPost("txtLineLimit");
			$arrayListCreditBalance			= /*inicio get post*/ $this->request->getPost("txtLineBalance");
			$arrayListCreditStatus			= /*inicio get post*/ $this->request->getPost("txtLineStatus");
			$arrayListTypeAmortization		= /*inicio get post*/ $this->request->getPost("txtTypeAmortization");
			$arrayListDayExcluded			= /*inicio get post*/ $this->request->getPost("txtDayExcluded");
			
			$limitCreditLine 				= 0;
			//Limpiar Lineas de Creditos
			$this->Customer_Credit_Line_Model->deleteWhereIDNotIn($companyID_,$branchID_,$entityID_,$arrayListCustomerCreditLineID);
			
			if(!empty($arrayListCustomerCreditLineID))
			foreach($arrayListCustomerCreditLineID as $key => $value){
			
				$customerCreditLineID 						= $value;
				if($customerCreditLineID == 0 ){
					$objCustomerCreditLine					= NULL;
					$objCustomerCreditLine["companyID"]		= $companyID_;
					$objCustomerCreditLine["branchID"]		= $branchID_;
					$objCustomerCreditLine["entityID"]		= $entityID_;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$objCustomerCreditLine["dayExcluded"]			= $arrayListDayExcluded[$key];					
					$limitCreditLine 								= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 									= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCustomerCreditLine["currencyID"],$objCurrencyDolares->currencyID,);
					$exchangeRateAmount								= $objCustomerCreditLine["limitCredit"];
					$this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
					
					if($objCustomerCreditLine["balance"] > $objCustomerCreditLine["limitCredit"])
					throw new \Exception("BALANCE NO PUEDE SER MAYOR QUE EL LIMITE EN LA LINEA");
				}
				else{					
					$objCustomerCreditLine 							= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
					$objCustomerCreditLineNew						= NULL;
					$objCustomerCreditLineNew["creditLineID"]		= $arrayListCreditLineID[$key];
					$objCustomerCreditLineNew["limitCredit"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLineNew["interestYear"]		= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLineNew["balance"] 			= $objCustomerCreditLineNew["limitCredit"] - ($objCustomerCreditLine->limitCredit - $objCustomerCreditLine->balance);
					$objCustomerCreditLineNew["periodPay"]			= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLineNew["term"]				= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLineNew["note"]				= $arrayListCreditNote[$key];
					$objCustomerCreditLineNew["statusID"]			= $arrayListCreditStatusID[$key];
					$objCustomerCreditLineNew["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$objCustomerCreditLineNew["dayExcluded"]			= $arrayListDayExcluded[$key];
					$limitCreditLine 									= $limitCreditLine + $objCustomerCreditLineNew["limitCredit"];
					$exchangeRate 										= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCustomerCreditLine->currencyID,$objCurrencyDolares->currencyID);					
					$exchangeRateAmount									= $objCustomerCreditLineNew["limitCredit"];
					
					//Si el balance es mayor que el limite igual el balance al limite
					if($objCustomerCreditLineNew["balance"] > $objCustomerCreditLineNew["limitCredit"])
					$objCustomerCreditLineNew["balance"] = $objCustomerCreditLineNew["limitCredit"];
					
					//actualizar
					$this->Customer_Credit_Line_Model->update_app_posme($customerCreditLineID,$objCustomerCreditLineNew);
					
					
			
				}
				
				
				
				
				//sumar los limites en dolares
				if($exchangeRate == 1)
					$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
				//sumar los limite en cordoba
				else
					$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
					
				
			}
			
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCreditNew["limitCreditDol"])
			throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Actualizar Balance
			if($objCustomerCreditNew["balanceDol"] > $objCustomerCreditNew["limitCreditDol"]){
				$objCustomerCreditNew["balanceDol"] = $objCustomerCreditNew["limitCreditDol"];
				$this->Customer_Credit_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
			}
			
			//Confirmar Entidad
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxc_customer/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_."/callback/".$callback);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');	
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

	public function updateElementMobile($dataSession, $customer){
		try{
					
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)	throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			$branchID 								= $customer->branchID;
			$entityID 								= $customer->entityID;
			$companyID								= $customer->companyID;
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
										
			$objCustomer							= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
			$oldStatusID 							= $objCustomer->statusID;
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objCustomerNew 				= null;
				$objCustomerNew["statusID"]		=$customer->statusID;				
				$this->Customer_Model->update_app_posme($companyID,$branchID,$entityID,$objCustomerNew);
			}
			else{
				$objCustomerNew 						= null;
				$objCustomerNew["phoneNumber"] 			=$customer->phoneNumber;
				$objCustomerNew["location"] 			=$customer->location;
				$this->Customer_Model->update_app_posme($companyID,$branchID,$entityID,$objCustomerNew);
				
				
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= $customer->firstName;
				$objNatural["lastName"]		= $customer->lastName;						
				$this->Natural_Model->update_app_posme($companyID,$branchID,$entityID,$objNatural);

				$objLegal["isActive"]		= true;
				$objLegal["comercialName"]	= $customer->firstName;
				$objLegal["legalName"]		= $customer->lastName;
				$this->Legal_Model->update_app_posme($companyID,$branchID,$entityID,$objLegal);		
			}
			
			
			//Confirmar Entidad
			if($db->transStatus() !== false){
				$db->transCommit();
			}
			else{
				$db->transRollback();
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
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;			
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;			
			$objEntity["branchID"]					= $dataSession["user"]->branchID;	
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession,$this->request);
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			$db=db_connect();
			$db->transStart();
			
			
			$entityID = $this->Entity_Model->insert_app_posme($objEntity);
			$callback  					= /*inicio get post*/ $this->request->getPost("txtCallback"); 
			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
			$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
			$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$objNatural["statusID"]		= /*inicio get post*/ $this->request->getPost("txtCivilStatusID");//--fin peticion get o post
			$objNatural["profesionID"]	= /*inicio get post*/ $this->request->getPost("txtProfesionID");//--fin peticion get o post
			$result 					= $this->Natural_Model->insert_app_posme($objNatural);
			
			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
			$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
			$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$result 					= $this->Legal_Model->insert_app_posme($objLegal);

			$objPaymentMethod['entityID'] = $entityID;
			$objPaymentMethod['statusID'] = 1;
			$objPaymentMethod['isActive'] = true;
			$objPaymentMethod['name']     = /*inicio get post*/ $this->request->getPost("txtNombreTarjeta");//--fin peticion get o post;
			$objPaymentMethod['number']   = /*inicio get post*/ $this->request->getPost("txtNumeroTarjeta");//--fin peticion get o post;
			$objPaymentMethod['email']    = /*inicio get post*/ $this->request->getPost("txtEmailTarjeta");//--fin peticion get o post;
			$objPaymentMethod['expirationDate']=/*inicio get post*/ $this->request->getPost("txtVencimientoTarjeta");//--fin peticion get o post;
			$objPaymentMethod['cvc']      = /*inicio get post*/ $this->request->getPost("txtCodigoCvc");//--fin peticion get o post;
			$objPaymentMethod['typeID']   = /*inicio get post*/ $this->request->getPost("txtTipoTarjeta");//--fin peticion get o post;
			$result 					  = $this->Customer_Payment_Method_Model->insert_app_posme($objPaymentMethod);
			
			$nombreRecordatorios = /*inicio get post*/ $this->request->getPost("txtNombreRecordatorioArray");//--fin peticion get o post;
			$situationes         = /*inicio get post*/ $this->request->getPost("txtSituationIDArray");//--fin peticion get o post;
			$frecuenias 		 = /*inicio get post*/ $this->request->getPost("txtFrecuencyContactIDArray");//--fin peticion get o post;
			if(!is_null($nombreRecordatorios)){
				$cant = count($nombreRecordatorios)-1;
				for($i=$cant; $i>=0;$i--){
					$objFrecuencyActuations['entityID'] = $entityID;
					$objFrecuencyActuations['createdOn'] = date('Y-m-d H:i:s');
					$objFrecuencyActuations['name'] = $nombreRecordatorios[$i];
					$objFrecuencyActuations['situationID'] = $situationes[$i];
					$objFrecuencyActuations['frecuencyContactID'] = $frecuenias[$i];
					$objFrecuencyActuations['isActive'] = 1;
					$objFrecuencyActuations['isApply'] = 0;
					$this->Customer_Frecuency_Actuations_Model->insert_app_posme($objFrecuencyActuations);
				}
			}

			$paisDefault 				= $this->core_web_parameter->getParameterValue("CXC_PAIS_DEFAULT",$companyID);
			$departamentoDefault 		= $this->core_web_parameter->getParameterValue("CXC_DEPARTAMENTO_DEFAULT",$companyID);
			$municipioDefault 			= $this->core_web_parameter->getParameterValue("CXC_MUNICIPIO_DEFAULT",$companyID);
			$plazoDefault 				= $this->core_web_parameter->getParameterValue("CXC_PLAZO_DEFAULT",$companyID);
			$typeAmortizationDefault 	= $this->core_web_parameter->getParameterValue("CXC_TYPE_AMORTIZATION",$companyID);
			$dayExcludedDefault 		= $this->core_web_parameter->getParameterValue("CXC_DAY_EXCLUDED_IN_CREDIT",$companyID);			
			$frecuencyDefault 			= $this->core_web_parameter->getParameterValue("CXC_FRECUENCIA_PAY_DEFAULT",$companyID);
			$creditLineDefault 			= $this->core_web_parameter->getParameterValue("CXC_CREDIT_LINE_DEFAULT",$companyID);
			$validarCedula 				= $this->core_web_parameter->getParameterValue("CXC_VALIDAR_CEDULA_REPETIDA",$companyID);
			$interesDefault				= $this->core_web_parameter->getParameterValue("CXC_INTERES_DEFAULT",$companyID);
			
			
			$paisID = empty (/*inicio get post*/ $this->request->getPost('txtCountryID') /*//--fin peticion get o post*/ ) ?  $paisDefault : /*inicio get post*/ $this->request->getPost('txtCountryID');  /*//--fin peticion get o post*/
			$departamentoId= empty (/*inicio get post*/ $this->request->getPost('txtStateID') /*//--fin peticion get o post*/ ) ?  $departamentoDefault : /*inicio get post*/ $this->request->getPost('txtStateID');  /*//--fin peticion get o post*/
			$municipioId= empty (/*inicio get post*/ $this->request->getPost('txtCityID') /*//--fin peticion get o post*/ ) ?  $municipioDefault : /*inicio get post*/ $this->request->getPost('txtCityID');  /*//--fin peticion get o post*/
			
			
			$objCustomer["companyID"]			= $objEntity["companyID"];
			$objCustomer["branchID"]			= $objEntity["branchID"];
			$objCustomer["entityID"]			= $entityID;
			$objCustomer["customerNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objCustomer["identificationType"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
			$objCustomer["identification"]		= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
			

			//validar que se permita la omision de la cedula
			if(strcmp($validarCedula,"true") == 0)
			{
				//Validar que ya existe el cliente
				$objCustomerOld					= $this->Customer_Model->get_rowByIdentification($companyID,$objCustomer["identification"]);
				if($objCustomerOld)
				{
					throw new \Exception("Error identificacion del cliente ya existe.");
				}
			} 
				
			
			$objCustomer["countryID"]			= $paisID;
			$objCustomer["stateID"]				= $departamentoId;
			$objCustomer["cityID"]				= $municipioId;
			$objCustomer["location"]			= /*inicio get post*/ $this->request->getPost("txtLocation");//--fin peticion get o post
			$objCustomer["address"]				= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$objCustomer["currencyID"]			= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post
			$objCustomer["clasificationID"]		= /*inicio get post*/ $this->request->getPost('txtClasificationID');//--fin peticion get o post
			$objCustomer["categoryID"]			= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
			$objCustomer["subCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtSubCategoryID');//--fin peticion get o post
			$objCustomer["customerTypeID"]		= /*inicio get post*/ $this->request->getPost("txtCustomerTypeID");//--fin peticion get o post
			$objCustomer["birthDate"]			= /*inicio get post*/ $this->request->getPost("txtBirthDate");//--fin peticion get o post
			$objCustomer["dateContract"]		= /*inicio get post*/ $this->request->getPost("txtDateContract");//--fin peticion get o post
			$objCustomer["statusID"]			= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
			$objCustomer["typePay"]				= /*inicio get post*/ $this->request->getPost('txtTypePayID');//--fin peticion get o post
			$objCustomer["payConditionID"]		= /*inicio get post*/ $this->request->getPost('txtPayConditionID');//--fin peticion get o post
			$objCustomer["sexoID"]				= /*inicio get post*/ $this->request->getPost('txtSexoID');//--fin peticion get o post
			$objCustomer["reference1"]			= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objCustomer["reference2"]			= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objCustomer["reference3"]			= /*inicio get post*/ $this->request->getPost("txtReference3");//--fin peticion get o post
			$objCustomer["reference4"]			= /*inicio get post*/ $this->request->getPost("txtReference4");//--fin peticion get o post
			$objCustomer["reference5"]			= /*inicio get post*/ $this->request->getPost("txtReference5");//--fin peticion get o post
			$objCustomer["balancePoint"]		= /*inicio get post*/ $this->request->getPost("txtBalancePoint");//--fin peticion get o post
			$objCustomer["phoneNumber"]			= /*inicio get post*/ $this->request->getPost("txtPhoneNumber");//--fin peticion get o post
			$objCustomer["typeFirm"]			= /*inicio get post*/ $this->request->getPost("txtTypeFirmID");//--fin peticion get o post
			$objCustomer["budget"]				= /*inicio get post*/ $this->request->getPost("txtBudget");//--fin peticion get o post
			$objCustomer["isActive"]			= true;
			$objCustomer["entityContactID"]		= /*inicio get post*/ $this->request->getPost("txtEmployerID");
			$objCustomer["entityReferenceID"]	= /*inicio get post*/ $this->request->getPost("txtCustomerIDReference");
			if(isset($_POST['txtAllowWhatsappPromotions']))
				$objCustomer["allowWhatsappPromotions"]	 	= $this->request->getPost("txtAllowWhatsappPromotions");
			else 
				$objCustomer["allowWhatsappPromotions"]		= 0 ;
					
			if(isset($_POST['txtAllowWhatsappCollection']))
				$objCustomer["allowWhatsappCollection"]	 	= $this->request->getPost("txtAllowWhatsappCollection");
			else 
				$objCustomer["allowWhatsappCollection"]		= 0 ;
			
			$objCustomer["formContactID"]		= /*inicio get post*/ $this->request->getPost("txtFormContactID");//--fin peticion get o post
			$this->core_web_auditoria->setAuditCreated($objCustomer,$dataSession,$this->request);
			$result 							= $this->Customer_Model->insert_app_posme($objCustomer);
			
			$validateBiometric = $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"], "CXC_USE_BIOMETRIC");
			if(strcmp(strtolower($validateBiometric->value), "true") == 0)
			{	
				//Ingresar registro en el lector biometrico				
				$dataUser["id"]							= $entityID;	
				$dataUser["name"]						= "buscar en otra base";
				$dataUser["email"]						= "buscar en otra base";
				$dataUser["email_verified_at"]			= "0000-00-00 00:00:00";
				$dataUser["password"]					= "buscar en otra base";
				$dataUser["remember_token"]				= "buscar en otra base";
				$dataUser["created_at"]					= "0000-00-00 00:00:00";
				$dataUser["updated_at"]					= "0000-00-00 00:00:00";
				$dataUser["image"]						= "";
				$resultUser 							= $this->Biometric_User_Model->delete_app_posme($dataUser["id"]);
				$resultUser 							= $this->Biometric_User_Model->insert_app_posme($dataUser);
			}
			
			
			
			//Ingresar Cuenta
			$objEntityAccount["companyID"]			= $objEntity["companyID"];
			$objEntityAccount["componentID"]		= $objComponent->componentID;
			$objEntityAccount["componentItemID"]	= $entityID;
			$objEntityAccount["name"]				= "";
			$objEntityAccount["description"]		= "";
			$objEntityAccount["accountTypeID"]		= "0";
			$objEntityAccount["currencyID"]			= "0";
			$objEntityAccount["classID"]			= "0";
			$objEntityAccount["balance"]			= "0";
			$objEntityAccount["creditLimit"]		= "0";
			$objEntityAccount["maxCredit"]			= "0";
			$objEntityAccount["debitLimit"]			= "0";
			$objEntityAccount["maxDebit"]			= "0";
			$objEntityAccount["statusID"]			= "0";
			
			$objEntityAccount["accountID"]			= empty(/*inicio get post*/ $this->request->getPost("txtAccountID")) ? '0': /*inicio get post*/ $this->request->getPost("txtAccountID");
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["isActive"]			= 1;
			$this->core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession,$this->request);
			$this->Entity_Account_Model->insert_app_posme($objEntityAccount);
			
			
			//Asociar el cliente al colaborador
			$objUserAdmin					=  $this->User_Model->get_rowByRoleAdmin($dataSession["user"]->companyID);			
			if($objUserAdmin)
			{
				$objListEmployerID 		= array_map(function($i) { return $i->employeeID; }, $objUserAdmin);
				$objListEmployerID[] 	= $dataSession["user"]->employeeID;
				$objListEmployerID 		= array_unique($objListEmployerID);
				
				foreach ($objListEmployerID as $employerIDT)
				{
					$dataRelationShip				= NULL;
					$dataRelationShip["employeeID"]	= $employerIDT;
					$dataRelationShip["customerID"]	= $entityID;
					$dataRelationShip["isActive"]	= 1;
					$dataRelationShip["startOn"]	= date("Y-m-d");
					$dataRelationShip["endOn"]		= date("Y-m-d");
					$this->Relationship_Model->insert_app_posme($dataRelationShip);					
				}
			}
			
			//Ingresar Customer Credit
			$objCustomerCredit["companyID"] 		= $objEntity["companyID"];
			$objCustomerCredit["branchID"] 			= $objEntity["branchID"];
			$objCustomerCredit["entityID"] 			= $entityID;
			$objCustomerCredit["limitCreditDol"] 	= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtLimitCreditDol"));
			$objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
			$objCustomerCredit["incomeDol"] 		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtIncomeDol"));
			$this->Customer_Credit_Model->insert_app_posme($objCustomerCredit);
			
			//Email
			$arrayListEntityEmail 				= /*inicio get post*/ $this->request->getPost("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= /*inicio get post*/ $this->request->getPost("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $objEntity["companyID"];
				$objEntityEmail["branchID"]		= $objEntity["branchID"];
				$objEntityEmail["entityID"]		= $entityID;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key];
				$this->Entity_Email_Model->insert_app_posme($objEntityEmail);
			}
			
			//Phone
			$arrayListEntityPhoneTypeID			= /*inicio get post*/ $this->request->getPost("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $objEntity["companyID"];
				$objEntityPhone["branchID"]		= $objEntity["branchID"];
				$objEntityPhone["entityID"]		= $entityID;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert_app_posme($objEntityPhone);
			}
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");
			$arrayListCreditLineID			= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$arrayListCreditCurrencyID		= /*inicio get post*/ $this->request->getPost("txtCreditCurrencyID");
			$arrayListCreditStatusID		= /*inicio get post*/ $this->request->getPost("txtCreditStatusID");
			$arrayListCreditInterestYear	= /*inicio get post*/ $this->request->getPost("txtCreditInterestYear");
			$arrayListCreditInterestPay		= /*inicio get post*/ $this->request->getPost("txtCreditInterestPay");
			$arrayListCreditTotalPay		= /*inicio get post*/ $this->request->getPost("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= /*inicio get post*/ $this->request->getPost("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= /*inicio get post*/ $this->request->getPost("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= /*inicio get post*/ $this->request->getPost("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= /*inicio get post*/ $this->request->getPost("txtCreditDateLastPay");
			$arrayListCreditTerm			= /*inicio get post*/ $this->request->getPost("txtCreditTerm");
			$arrayListCreditNote			= /*inicio get post*/ $this->request->getPost("txtCreditNote");
			$arrayListCreditLine			= /*inicio get post*/ $this->request->getPost("txtLine");
			$arrayListCreditNumber			= /*inicio get post*/ $this->request->getPost("txtLineNumber");
			$arrayListCreditLimit			= /*inicio get post*/ $this->request->getPost("txtLineLimit");
			$arrayListCreditBalance			= /*inicio get post*/ $this->request->getPost("txtLineBalance");
			$arrayListCreditStatus			= /*inicio get post*/ $this->request->getPost("txtLineStatus");
			$arrayListTypeAmortization		= /*inicio get post*/ $this->request->getPost("txtTypeAmortization");			
			$arrayListDayExcluded			= /*inicio get post*/ $this->request->getPost("txtDayExcluded");			
			$limitCreditLine 				= 0;
			
			
			
			
			if(empty($arrayListCustomerCreditLineID))
			{
				 $arrayListCustomerCreditLineID[0]	= 1;
				 $arrayListCreditLineID[0] 			= $creditLineDefault;
				 $arrayListCreditCurrencyID[0]		= $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;
				 $arrayListCreditLimit[0]			= 300000;
				 $arrayListCreditInterestYear[0]	= $interesDefault;
				 $arrayListCreditInterestPay[0]		= 0;
				 $arrayListCreditTotalPay[0]		= 0;
				 $arrayListCreditTotalDefeated[0]	= 0;
				 $arrayListCreditPeriodPay[0]		= $frecuencyDefault;
				 $arrayListCreditTerm[0]			= $plazoDefault;
				 $arrayListCreditNote[0]			= "-";
				 $arrayListTypeAmortization[0]		= $typeAmortizationDefault;
				 $arrayListDayExcluded[0]			= $dayExcludedDefault;
				 $arrayListCreditStatusID[0]		= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
				 
			}
			
			if(!empty($arrayListCustomerCreditLineID))
			{
				foreach($arrayListCustomerCreditLineID as $key => $value)
				{
					$objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
					$objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
					$objCustomerCreditLine["entityID"]		= $entityID;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
					$objCustomerCreditLine["dayExcluded"]		= $arrayListDayExcluded[$key];
					$limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 								= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCustomerCreditLine["currencyID"],$objCurrencyDolares->currencyID);//cordobas a dolares, o dolares a dolares.
					$exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
					$this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
					
					
					
					
					//sumar los limites en dolares
					if($exchangeRate == 1)
						$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
					//sumar los limite en cordoba
					else
						$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
					
					
					
				}
			}
			
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
			throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			$pathfile = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID;			
			
			if (!file_exists($pathfile))
			{
				mkdir($pathfile, 0700,true);
			}
			
		
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				
				if($dataSession["company"]->type=="luciaralstate")
					$this->response->redirect(base_url()."/".'app_cxc_customer/index');			
				else 
					$this->response->redirect(base_url()."/".'app_cxc_customer/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID."/callback/".$callback);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');	
			}
			
		}
		catch(\Exception $ex)
		{
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
	public function insertElementMobile($dataSession,$customer)
    {
        try{

            //Obtener el Componente de Transacciones Other Input to Inventory
            $objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if(!$objComponent)
                throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");

            //Obtener transaccion
			$objDataSet								= null;
            $companyID 								= $dataSession["user"]->companyID;
            $objEntity["companyID"] 				= $dataSession["user"]->companyID;
            $objEntity["branchID"]					= $dataSession["user"]->branchID;
            $branchID 								= $dataSession["user"]->branchID;
            $roleID 								= $dataSession["role"]->roleID;
            $this->core_web_auditoria->setAuditCreated($objEntity,$dataSession,$this->request);

            //Moneda Dolares
            date_default_timezone_set(APP_TIMEZONE);
            $objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
            $dateOn 								= date("Y-m-d");
            $dateOn 								= date_format(date_create($dateOn),"Y-m-d");
            $exchangeRate 							= 0;
            $exchangeRateTotal 						= 0;
            $exchangeRateAmount 					= 0;

            $db=db_connect();
            $db->transStart();


            $entityID 							= $this->Entity_Model->insert_app_posme($objEntity);
			$objDataSet["entityID"] 			= $entityID;
			$objDataSet["customerCreditLineID"]	= 0;
			$objListComanyParameter			 	= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);

            $objNatural["companyID"]	= $objEntity["companyID"];
            $objNatural["branchID"] 	= $objEntity["branchID"];
            $objNatural["entityID"]		= $entityID;
            $objNatural["isActive"]		= true;
            $objNatural["firstName"]	= $customer->firstName;
            $objNatural["lastName"]		= $customer->lastName;
            $objNatural["address"]		= "";
            $objNatural["statusID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_STATUS_CIVIL_ID_DEFAULT")->value;
            $objNatural["profesionID"]	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PROFESION_ID_DEFAULT")->value;
            $result 					= $this->Natural_Model->insert_app_posme($objNatural);

            $objLegal["companyID"]		= $objEntity["companyID"];
            $objLegal["branchID"]		= $objEntity["branchID"];
            $objLegal["entityID"]		= $entityID;
            $objLegal["isActive"]		= true;
            $objLegal["comercialName"]	= $customer->firstName;
            $objLegal["legalName"]		= $customer->lastName;
            $objLegal["address"]		= "";
            $result 					= $this->Legal_Model->insert_app_posme($objLegal);

            $paisDefault 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_PAIS_DEFAULT")->value;
            $departamentoDefault 		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DEPARTAMENTO_DEFAULT")->value;
            $municipioDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_MUNICIPIO_DEFAULT")->value;
            $plazoDefault 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PLAZO_DEFAULT")->value;
            $typeAmortizationDefault 	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_TYPE_AMORTIZATION")->value;
            $frecuencyDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_FRECUENCIA_PAY_DEFAULT")->value;
            $creditLineDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_CREDIT_LINE_DEFAULT")->value;
            $validarCedula 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_VALIDAR_CEDULA_REPETIDA")->value;
            $interesDefault				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_INTERES_DEFAULT")->value;
			$dayExcludedDefault 		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DAY_EXCLUDED_IN_CREDIT")->value;

            $paisID = $paisDefault;
            $departamentoId= $departamentoDefault;
            $municipioId= $municipioDefault;


            $objCustomer["companyID"]			= $objEntity["companyID"];
            $objCustomer["branchID"]			= $objEntity["branchID"];
            $objCustomer["entityID"]			= $entityID;
            $objCustomer["customerNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objDataSet["customerNumber"] 		= $objCustomer["customerNumber"];
            $objCustomer["identificationType"]	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_IDENTIFICATION_TYPE_DEFAULT")->value;
            $objCustomer["identification"]		= $customer->identification;


            //validar que se permita la omision de la cedula
            if(strcmp($validarCedula,"true") == 0)
            {
                //Validar que ya existe el cliente
                $objCustomerOld					= $this->Customer_Model->get_rowByIdentification($companyID,$objCustomer["identification"]);
                if($objCustomerOld)
                {
                    throw new \Exception("Error identificacion del cliente ya existe.");
                }
            }


            $objCustomer["countryID"]			= $paisID;
            $objCustomer["stateID"]				= $departamentoId;
            $objCustomer["cityID"]				= $municipioId;
            $objCustomer["location"]			= $customer->location;
            $objCustomer["address"]				= "";
            $objCustomer["currencyID"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_CURRENCY_ID_DEFAULT")->value;
            $objCustomer["clasificationID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CLASIFICATION_ID_DEFAULT")->value;
            $objCustomer["categoryID"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CATEGORY_ID_DEFAULT")->value;
            $objCustomer["subCategoryID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_SUBCATEGORY_ID_DEFAULT")->value;
            $objCustomer["customerTypeID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_ID_DEFAULT")->value;
            $objCustomer["birthDate"]			= date("Y-m-d");
            $objCustomer["dateContract"]		= date("Y-m-d");
            $objCustomer["statusID"]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;; //lo mismo statusid de producto solo cambiar nombre de la tabla
            $objCustomer["typePay"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_PAY_ID_DEFAULT")->value;
            $objCustomer["payConditionID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_PAY_CONDITION_ID_DEFAULT")->value;
            $objCustomer["sexoID"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_SEX_ID_DEFAULT")->value;
            $objCustomer["reference1"]			= property_exists($customer, 'reference1') ?  $customer->reference1 : "";
            $objCustomer["reference2"]			= "";
            $objCustomer["reference3"]			= "";
            $objCustomer["reference4"]			= "";
            $objCustomer["reference5"]			= "";
            $objCustomer["balancePoint"]		= 0;
            $objCustomer["phoneNumber"]			= $customer->phone;
            $objCustomer["typeFirm"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_TYPE_FIRM_ID_DEFAULT")->value;
            $objCustomer["budget"]				= 0;
            $objCustomer["isActive"]			= true;
            $objCustomer["entityContactID"]		= 0;
            $objCustomer["formContactID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_FORM_CONTACT_ID_DEFAULT")->value;
			$objCustomer["allowWhatsappPromotions"]		= 0;
			$objCustomer["allowWhatsappCollection"]		= 0;
			
            $this->core_web_auditoria->setAuditCreated($objCustomer,$dataSession,$this->request);
            $result 							= $this->Customer_Model->insert_app_posme($objCustomer);

            //Ingresar registro en el lector biometrico
			$validateBiometric = $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_USE_BIOMETRIC");
			if(strcmp(strtolower($validateBiometric->value), "true") == 0)
			{	
				$dataUser["id"]							= $entityID;
				$dataUser["name"]						= "buscar en otra base";
				$dataUser["email"]						= "buscar en otra base";
				$dataUser["email_verified_at"]			= "0000-00-00 00:00:00";
				$dataUser["password"]					= "buscar en otra base";
				$dataUser["remember_token"]				= "buscar en otra base";
				$dataUser["created_at"]					= "0000-00-00 00:00:00";
				$dataUser["updated_at"]					= "0000-00-00 00:00:00";
				$dataUser["image"]						= "";
				$resultUser 							= $this->Biometric_User_Model->delete_app_posme($dataUser["id"]);
				$resultUser 							= $this->Biometric_User_Model->insert_app_posme($dataUser);
			}



            //Ingresar Cuenta
            $objEntityAccount["companyID"]			= $objEntity["companyID"];
            $objEntityAccount["componentID"]		= $objComponent->componentID;
            $objEntityAccount["componentItemID"]	= $entityID;
            $objEntityAccount["name"]				= "";
            $objEntityAccount["description"]		= "";
            $objEntityAccount["accountTypeID"]		= "0";
            $objEntityAccount["currencyID"]			= "0";
            $objEntityAccount["classID"]			= "0";
            $objEntityAccount["balance"]			= "0";
            $objEntityAccount["creditLimit"]		= "0";
            $objEntityAccount["maxCredit"]			= "0";
            $objEntityAccount["debitLimit"]			= "0";
            $objEntityAccount["maxDebit"]			= "0";
            $objEntityAccount["statusID"]			= "0";

            $objEntityAccount["accountID"]			= "0";
            $objEntityAccount["statusID"]			= "0";
            $objEntityAccount["isActive"]			= 1;
            $this->core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession,$this->request);
            $this->Entity_Account_Model->insert_app_posme($objEntityAccount);

            //Ingresar Customer Credit
            $objCustomerCredit["companyID"] 		= $objEntity["companyID"];
            $objCustomerCredit["branchID"] 			= $objEntity["branchID"];
            $objCustomerCredit["entityID"] 			= $entityID;
            $objCustomerCredit["limitCreditDol"] 	= 900000;
            $objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
            $objCustomerCredit["incomeDol"] 		= 5000;
            $this->Customer_Credit_Model->insert_app_posme($objCustomerCredit);

            //Lineas de Creditos
            $arrayListCustomerCreditLineID	= array();
            $arrayListCreditLineID			= array();
            $arrayListCreditCurrencyID		= array();
            $arrayListCreditStatusID		= array();
            $arrayListCreditInterestYear	= array();
            $arrayListCreditInterestPay		= array();
            $arrayListCreditTotalPay		= array();
            $arrayListCreditTotalDefeated	= array();
            $arrayListCreditDateOpen		= array();
            $arrayListCreditPeriodPay		= array();
            $arrayListCreditDateLastPay		= array();
            $arrayListCreditTerm			= array();
            $arrayListCreditNote			= array();
            $arrayListCreditLine			= array();
            $arrayListCreditNumber			= array();
            $arrayListCreditLimit			= array();
            $arrayListCreditBalance			= array();
            $arrayListCreditStatus			= array();
            $arrayListTypeAmortization		= array();
			$arrayListDayExcluded			= array();
            $limitCreditLine 				= 0;




            if(empty($arrayListCustomerCreditLineID))
            {
                $arrayListCustomerCreditLineID[0]	= 1;
                $arrayListCreditLineID[0] 			= $creditLineDefault;
                $arrayListCreditCurrencyID[0]		= $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;
                $arrayListCreditLimit[0]			= 300000;
                $arrayListCreditInterestYear[0]		= $interesDefault;
                $arrayListCreditInterestPay[0]		= 0;
                $arrayListCreditTotalPay[0]			= 0;
                $arrayListCreditTotalDefeated[0]	= 0;
                $arrayListCreditPeriodPay[0]		= $frecuencyDefault;
                $arrayListCreditTerm[0]				= $plazoDefault;
                $arrayListCreditNote[0]				= "-";
                $arrayListTypeAmortization[0]		= $typeAmortizationDefault;
				$arrayListDayExcluded[0]			= $dayExcludedDefault;				
                $arrayListCreditStatusID[0]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;

            }

            if(!empty($arrayListCustomerCreditLineID))
            {
                foreach($arrayListCustomerCreditLineID as $key => $value)
                {
                    $objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
                    $objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
                    $objCustomerCreditLine["entityID"]		= $entityID;
                    $objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
                    $objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
                    $objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
                    $objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
                    $objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
                    $objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
                    $objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
                    $objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
                    $objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
                    $objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
                    $objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
                    $objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
                    $objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
                    $objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
                    $objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
                    $objCustomerCreditLine["isActive"]		= 1;
                    $objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
					$objCustomerCreditLine["dayExcluded"]		= $arrayListDayExcluded[$key];
                    $limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
                    $exchangeRate 								= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCustomerCreditLine["currencyID"],$objCurrencyDolares->currencyID);//cordobas a dolares, o dolares a dolares.
                    $exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
                    $customerCreditLineID 						= $this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
					$objDataSet["customerCreditLineID"]			= $customerCreditLineID;




                    //sumar los limites en dolares
                    if($exchangeRate == 1)
                        $exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
                    //sumar los limite en cordoba
                    else
                        $exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);



                }
            }


            //Validar Limite de Credito
            if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
                throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			
			//Asociar el cliente al colaborador
			$objUserAdmin					=  $this->User_Model->get_rowByRoleAdmin($dataSession["user"]->companyID);			
			if($objUserAdmin)
			{
				$objListEmployerID 		= array_map(function($i) { return $i->employeeID; }, $objUserAdmin);
				$objListEmployerID[] 	= $dataSession["user"]->employeeID;
				$objListEmployerID 		= array_unique($objListEmployerID);
				
				foreach ($objListEmployerID as $employerIDT)
				{
					$dataRelationShip				= NULL;
					$dataRelationShip["employeeID"]	= $employerIDT;
					$dataRelationShip["customerID"]	= $entityID;
					$dataRelationShip["isActive"]	= 1;
					$dataRelationShip["startOn"]	= date("Y-m-d");
					$dataRelationShip["endOn"]		= date("Y-m-d");
					$this->Relationship_Model->insert_app_posme($dataRelationShip);					
				}
			}
			

            //Crear la Carpeta para almacenar los Archivos del Cliente
            $pathfile = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID;

            if (!file_exists($pathfile))
            {
                mkdir($pathfile, 0700,true);
            }



            if($db->transStatus() !== false)
			{
                $db->transCommit();
				return $objDataSet;
            }
            else{
                $db->transRollback();
				return $objDataSet;
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
	
	public function insertAndUpdateElementLiveConnectWebHook()
	{
		// JSON crudo (string completo)
		$rawJson = $this->request->getBody();

		// Ejemplo: guardarlo en log o BD
		//{"chat":{"IPs":"186.77.207.249","browser":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36","contacto":{"default_target":0,"default_target_id":0,"destacado":0,"etiquetas":{"0":0},"extra1":"","extra2":"","id":"YSZ0A13040785761QQPI0","isbot":0,"nombre":"Nuevo Visitante","pais":"-"},"destacado":0,"etiquetas":{"0":0},"fecha":1768149772,"fecha_ini":1768149773,"id":"ADKAY50362847409VZHU0","id_canal":60644,"id_grupo":33674,"id_usuario":59221,"isbot":1,"presence":{"status":"away","timestamp":1768149776},"traceID":"Root=1-6963d30c-308dc5150b41c394527f5d79","usuarios":{"59221":{"assign":true,"avatar":"","bot_data":{"id":59221,"id_flowbot":16986,"id_usuario":59221,"name":"flowbot"},"fecha":1768149773,"id":59221,"isbot":1,"noleidos":5,"nombre":"*BOT* Flowbot: Bot Atencion Virtual"}}},"inputs":{"id":"YSZ0A13040785761QQPI0","id_contacto":null,"avatar":null,"nombre":"Nuevo Visitante","apellidos":null,"email":null,"correo":null,"celular":null,"genero":null,"pais":"-","ciudad":null,"direccion":null,"ubicacion":null,"tipo_documento":null,"num_documento":null,"fecha_cumple":null,"dinamicos":null,"habeasdata":null,"destacado":0,"extra1":"","extra2":"","valor":null,"etiquetas":[0],"default_target":0,"default_target_id":0,"isbot":0,"mensaje_inicial":"Hola!"}}
		//wg-$rawJson = '
		//wg-		{
		//wg-			"chat":
		//wg-			{
		//wg-				"IPs":"186.77.207.249",
		//wg-				"browser":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36",
		//wg-				"contacto":
		//wg-					{
		//wg-						"default_target":0,
		//wg-						"default_target_id":0,
		//wg-						"destacado":0,
		//wg-						"etiquetas":{"0":0},
		//wg-						"extra1":"","extra2":"",
		//wg-						"id":"YSZ0A13040785761QQPI0",
		//wg-						"isbot":0,
		//wg-						"nombre":"Nuevo Visitante","pais":"-"
		//wg-					},
		//wg-				"destacado":0,
		//wg-				"etiquetas":{"0":0},
		//wg-				"fecha":1768149772,"fecha_ini":1768149773,"id":"ADKAY50362847409VZHU0",
		//wg-				"id_canal":60644,"id_grupo":33674,"id_usuario":59221,"isbot":1,
		//wg-				"presence":{"status":"away","timestamp":1768149776},
		//wg-				"traceID":"Root=1-6963d30c-308dc5150b41c394527f5d79",
		//wg-				"usuarios":{
		//wg-						"59221":{
		//wg-								"assign":true,"avatar":"",
		//wg-								"bot_data":{"id":59221,"id_flowbot":16986,"id_usuario":59221,"name":"flowbot"},
		//wg-								"fecha":1768149773,"id":59221,"isbot":1,"noleidos":5,
		//wg-								"nombre":"*BOT* Flowbot: Bot Atencion Virtual"
		//wg-						}
		//wg-				}
		//wg-			},
		//wg-			"inputs":{
		//wg-					"id":"YSZ0A13040785761QQPI0",
		//wg-					"id_contacto":null,"avatar":null,"nombre":"Nuevo Visitante",
		//wg-					"apellidos":null,"email":null,"correo":null,"celular":null,
		//wg-					"genero":null,"pais":"-","ciudad":null,"direccion":null,
		//wg-					"ubicacion":null,"tipo_documento":null,"num_documento":null,
		//wg-					"fecha_cumple":null,"dinamicos":null,"habeasdata":null,
		//wg-					"destacado":0,"extra1":"","extra2":"","valor":null,"etiquetas":[0],
		//wg-					"default_target":0,"default_target_id":0,"isbot":0,"mensaje_inicial":"Hola!"
		//wg-			}
		//wg-		}'; 
		log_message('error', 'Webhook RAW JSON: ' . $rawJson);	
		$data    = json_decode($rawJson, true);
		log_message('error', 'Webhook RAW JSON: ' . print_r($data,true));	
		
		echo "Request:";
		echo print_r($data,true);

		if (!$data) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'JSON inválido'
			])->setStatusCode(400);
		}

		// Ejemplo de lectura segura
		/* ===============================
		   CHAT
		================================ */
		$chat                 = $data['chat'] ?? [];
		$chat_ip              = $chat['IPs'] ?? '';
		$chat_browser         = $chat['browser'] ?? '';
		$chat_fecha           = $chat['fecha'] ?? null;
		$chat_fecha_ini       = $chat['fecha_ini'] ?? null;
		$chat_id              = $chat['id'] ?? '';
		$chat_id_canal        = $chat['id_canal'] ?? null;
		$chat_id_grupo        = $chat['id_grupo'] ?? null;
		$chat_id_usuario      = $chat['id_usuario'] ?? null;
		$chat_isbot           = $chat['isbot'] ?? 0;
		$chat_trace_id        = $chat['traceID'] ?? '';
		$chat_destacado       = $chat['destacado'] ?? 0;

		/* ===============================
		   CONTACTO (dentro de chat)
		================================ */
		$contacto             = $chat['contacto'] ?? [];
		$contacto_id          = $contacto['id'] ?? '';
		$contacto_nombre      = $contacto['nombre'] ?? '';
		$contacto_pais        = $contacto['pais'] ?? '';
		$contacto_isbot       = $contacto['isbot'] ?? 0;
		$contacto_destacado   = $contacto['destacado'] ?? 0;
		$contacto_extra1      = $contacto['extra1'] ?? '';
		$contacto_extra2      = $contacto['extra2'] ?? '';
		$contacto_default_tg  = $contacto['default_target'] ?? 0;
		$contacto_default_tg_id = $contacto['default_target_id'] ?? 0;
		$contacto_etiquetas   = $contacto['etiquetas'] ?? [];

		/* ===============================
		   PRESENCE
		================================ */
		$presence             = $chat['presence'] ?? [];
		$presence_status      = $presence['status'] ?? '';
		$presence_timestamp   = $presence['timestamp'] ?? null;

		/* ===============================
		   USUARIOS
		================================ */
		$usuarios             = $chat['usuarios'] ?? [];
		$usuarios_data = [];
		foreach ($usuarios as $usuario_id => $usuario) {

			$usuarios_data[$usuario_id] = [
				'id'          => $usuario['id'] ?? null,
				'nombre'      => $usuario['nombre'] ?? '',
				'isbot'       => $usuario['isbot'] ?? 0,
				'noleidos'    => $usuario['noleidos'] ?? 0,
				'assign'      => $usuario['assign'] ?? false,
				'avatar'      => $usuario['avatar'] ?? '',
				'fecha'       => $usuario['fecha'] ?? null,

				// Bot data
				'bot_id'      => $usuario['bot_data']['id'] ?? null,
				'flowbot_id'  => $usuario['bot_data']['id_flowbot'] ?? null,
				'bot_nombre'  => $usuario['bot_data']['name'] ?? '',
			];
		}

		/* ===============================
		   INPUTS (visitante)
		================================ */
		$inputs                = $data['inputs'] ?? [];
		$input_id              = $inputs['id'] ?? '';
		$input_id_contacto     = $inputs['id_contacto'] ?? null;
		$input_nombre          = $inputs['nombre'] ?? '';
		$input_apellidos       = $inputs['apellidos'] ?? '';
		$input_email           = $inputs['email'] ?? '';
		$input_correo          = $inputs['correo'] ?? '';
		$input_celular         = $inputs['celular'] ?? '';
		$input_genero          = $inputs['genero'] ?? '';
		$input_pais            = $inputs['pais'] ?? '';
		$input_ciudad          = $inputs['ciudad'] ?? '';
		$input_direccion       = $inputs['direccion'] ?? '';
		$input_ubicacion       = $inputs['ubicacion'] ?? '';
		$input_tipo_doc        = $inputs['tipo_documento'] ?? '';
		$input_num_doc         = $inputs['num_documento'] ?? '';
		$input_fecha_cumple    = $inputs['fecha_cumple'] ?? '';
		$input_dinamicos       = $inputs['dinamicos'] ?? null;
		$input_habeasdata      = $inputs['habeasdata'] ?? null;
		$input_destacado       = $inputs['destacado'] ?? 0;
		$input_extra1          = $inputs['extra1'] ?? '';
		$input_extra2          = $inputs['extra2'] ?? '';
		$input_valor           = $inputs['valor'] ?? null;
		$input_etiquetas       = $inputs['etiquetas'] ?? [];
		$input_default_tg      = $inputs['default_target'] ?? 0;
		$input_default_tg_id   = $inputs['default_target_id'] ?? 0;
		$input_isbot           = $inputs['isbot'] ?? 0;
		$input_mensaje_inicial = $inputs['mensaje_inicial'] ?? '';


		//Datos Minimo
		//Nombre  (NOMBRE)
		//Telefono (88888888)
		//Id de la propiedad de interes   (NUMERO)
		//Id de encutra 24  (NUMERO)
		//Estilo de propiedad (CASA,TERRENO,COMERCIAL)
		//Interes del cliente (RENTA,VENTA)
		//Nombre del agente

		// Guardar en BD, procesar lógica, etc...
		log_message('error', 'Nombre: '.$contacto_nombre);
		log_message('error', 'Webhook RAW JSON: Success');
		
		echo "Response:";
		return $this->response->setJSON([
			'success' => true
		]);
		
		try
		{
			//Ingresar al cliente
			$dataSession 				= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
			$objComponentCustomer		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
				throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			$objDataSet								= null;
			$companyID 								= $dataSession["user"]->companyID;
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;
			$objEntity["branchID"]					= $dataSession["user"]->branchID;
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession,$this->request);
			
			date_default_timezone_set(APP_TIMEZONE);
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			$db=db_connect();
			$db->transStart();		
			
			$entityID 							= $this->Entity_Model->insert_app_posme($objEntity);
			$objDataSet["entityID"] 			= $entityID;
			$objDataSet["customerCreditLineID"]	= 0;
			$objListComanyParameter			 	= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);

			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= $contacto_nombre;
			$objNatural["lastName"]		= $contacto_nombre;
			$objNatural["address"]		= "";
			$objNatural["statusID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_STATUS_CIVIL_ID_DEFAULT")->value;
			$objNatural["profesionID"]	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PROFESION_ID_DEFAULT")->value;
			$result 					= $this->Natural_Model->insert_app_posme($objNatural);

			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= $contacto_nombre;
			$objLegal["legalName"]		= $contacto_nombre;
			$objLegal["address"]		= "";
			$result 					= $this->Legal_Model->insert_app_posme($objLegal);
			
			$paisDefault 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_PAIS_DEFAULT")->value;
			$departamentoDefault 		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DEPARTAMENTO_DEFAULT")->value;
			$municipioDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_MUNICIPIO_DEFAULT")->value;
			$plazoDefault 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PLAZO_DEFAULT")->value;
			$typeAmortizationDefault 	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_TYPE_AMORTIZATION")->value;
			$frecuencyDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_FRECUENCIA_PAY_DEFAULT")->value;
			$creditLineDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_CREDIT_LINE_DEFAULT")->value;
			$validarCedula 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_VALIDAR_CEDULA_REPETIDA")->value;
			$interesDefault				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_INTERES_DEFAULT")->value;
			$dayExcludedDefault 		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DAY_EXCLUDED_IN_CREDIT")->value;

			$paisID 					= $paisDefault;
			$departamentoId				= $departamentoDefault;
			$municipioId				= $municipioDefault;

			$objCustomer["companyID"]			= $objEntity["companyID"];
			$objCustomer["branchID"]			= $objEntity["branchID"];
			$objCustomer["entityID"]			= $entityID;
			$objCustomer["customerNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objDataSet["customerNumber"] 		= $objCustomer["customerNumber"];
			$objCustomer["identificationType"]	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_IDENTIFICATION_TYPE_DEFAULT")->value;
			$objCustomer["identification"]		= $input_celular;

			//validar que se permita la omision de la cedula
			if(strcmp($validarCedula,"true") == 0)
			{
				//Validar que ya existe el cliente
				$objCustomerOld					= $this->Customer_Model->get_rowByIdentification($companyID,$objCustomer["identification"]);
				if($objCustomerOld)
				{
					throw new \Exception("Error identificacion del cliente ya existe.");
				}
			}
			
			$objCustomer["countryID"]			= $paisID;
			$objCustomer["stateID"]				= $departamentoId;
			$objCustomer["cityID"]				= $municipioId;
			$objCustomer["location"]			= $input_ubicacion;
			$objCustomer["address"]				= "";
			$objCustomer["currencyID"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_CURRENCY_ID_DEFAULT")->value;
			$objCustomer["clasificationID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CLASIFICATION_ID_DEFAULT")->value;
			$objCustomer["categoryID"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CATEGORY_ID_DEFAULT")->value;
			$objCustomer["subCategoryID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_SUBCATEGORY_ID_DEFAULT")->value;
			$objCustomer["customerTypeID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_ID_DEFAULT")->value;
			$objCustomer["birthDate"]			= date("Y-m-d");
			$objCustomer["dateContract"]		= date("Y-m-d");
			$objCustomer["statusID"]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;; //lo mismo statusid de producto solo cambiar nombre de la tabla
			$objCustomer["typePay"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_PAY_ID_DEFAULT")->value;
			$objCustomer["payConditionID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_PAY_CONDITION_ID_DEFAULT")->value;
			$objCustomer["sexoID"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_SEX_ID_DEFAULT")->value;
			$objCustomer["reference1"]			= "";
			$objCustomer["reference2"]			= "";
			$objCustomer["reference3"]			= "";
			$objCustomer["reference4"]			= "";
			$objCustomer["reference5"]			= "";
			$objCustomer["balancePoint"]		= 0;
			$objCustomer["phoneNumber"]			= $input_celular;
			$objCustomer["typeFirm"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_TYPE_FIRM_ID_DEFAULT")->value;
			$objCustomer["budget"]				= 0;
			$objCustomer["isActive"]			= true;
			$objCustomer["entityContactID"]		= 0;
			$objCustomer["formContactID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_FORM_CONTACT_ID_DEFAULT")->value;
			$objCustomer["allowWhatsappPromotions"]		= 0;
			$objCustomer["allowWhatsappCollection"]		= 0;
			
			$this->core_web_auditoria->setAuditCreated($objCustomer,$dataSession,$this->request);
			$result 							= $this->Customer_Model->insert_app_posme($objCustomer);
			
			//Ingresar registro en el lector biometrico
			$validateBiometric = $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_USE_BIOMETRIC");
			if(strcmp(strtolower($validateBiometric->value), "true") == 0)
			{	
				$dataUser["id"]							= $entityID;
				$dataUser["name"]						= "buscar en otra base";
				$dataUser["email"]						= "buscar en otra base";
				$dataUser["email_verified_at"]			= "0000-00-00 00:00:00";
				$dataUser["password"]					= "buscar en otra base";
				$dataUser["remember_token"]				= "buscar en otra base";
				$dataUser["created_at"]					= "0000-00-00 00:00:00";
				$dataUser["updated_at"]					= "0000-00-00 00:00:00";
				$dataUser["image"]						= "";
				$resultUser 							= $this->Biometric_User_Model->delete_app_posme($dataUser["id"]);
				$resultUser 							= $this->Biometric_User_Model->insert_app_posme($dataUser);
			}

			//Ingresar Cuenta
			$objEntityAccount["companyID"]			= $objEntity["companyID"];
			$objEntityAccount["componentID"]		= $objComponentCustomer->componentID;
			$objEntityAccount["componentItemID"]	= $entityID;
			$objEntityAccount["name"]				= "";
			$objEntityAccount["description"]		= "";
			$objEntityAccount["accountTypeID"]		= "0";
			$objEntityAccount["currencyID"]			= "0";
			$objEntityAccount["classID"]			= "0";
			$objEntityAccount["balance"]			= "0";
			$objEntityAccount["creditLimit"]		= "0";
			$objEntityAccount["maxCredit"]			= "0";
			$objEntityAccount["debitLimit"]			= "0";
			$objEntityAccount["maxDebit"]			= "0";
			$objEntityAccount["statusID"]			= "0";

			$objEntityAccount["accountID"]			= "0";
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["isActive"]			= 1;
			$this->core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession,$this->request);
			$this->Entity_Account_Model->insert_app_posme($objEntityAccount);

			
			//Ingresar Customer Credit
			$objCustomerCredit["companyID"] 		= $objEntity["companyID"];
			$objCustomerCredit["branchID"] 			= $objEntity["branchID"];
			$objCustomerCredit["entityID"] 			= $entityID;
			$objCustomerCredit["limitCreditDol"] 	= 900000;
			$objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
			$objCustomerCredit["incomeDol"] 		= 5000;
			$this->Customer_Credit_Model->insert_app_posme($objCustomerCredit);

			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= array();
			$arrayListCreditLineID			= array();
			$arrayListCreditCurrencyID		= array();
			$arrayListCreditStatusID		= array();
			$arrayListCreditInterestYear	= array();
			$arrayListCreditInterestPay		= array();
			$arrayListCreditTotalPay		= array();
			$arrayListCreditTotalDefeated	= array();
			$arrayListCreditDateOpen		= array();
			$arrayListCreditPeriodPay		= array();
			$arrayListCreditDateLastPay		= array();
			$arrayListCreditTerm			= array();
			$arrayListCreditNote			= array();
			$arrayListCreditLine			= array();
			$arrayListCreditNumber			= array();
			$arrayListCreditLimit			= array();
			$arrayListCreditBalance			= array();
			$arrayListCreditStatus			= array();
			$arrayListTypeAmortization		= array();
			$arrayListDayExcluded			= array();
			$limitCreditLine 				= 0;




			if(empty($arrayListCustomerCreditLineID))
			{
				$arrayListCustomerCreditLineID[0]	= 1;
				$arrayListCreditLineID[0] 			= $creditLineDefault;
				$arrayListCreditCurrencyID[0]		= $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;
				$arrayListCreditLimit[0]			= 300000;
				$arrayListCreditInterestYear[0]		= $interesDefault;
				$arrayListCreditInterestPay[0]		= 0;
				$arrayListCreditTotalPay[0]			= 0;
				$arrayListCreditTotalDefeated[0]	= 0;
				$arrayListCreditPeriodPay[0]		= $frecuencyDefault;
				$arrayListCreditTerm[0]				= $plazoDefault;
				$arrayListCreditNote[0]				= "-";
				$arrayListTypeAmortization[0]		= $typeAmortizationDefault;
				$arrayListDayExcluded[0]			= $dayExcludedDefault;				
				$arrayListCreditStatusID[0]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;

			}
			
			

			if(!empty($arrayListCustomerCreditLineID))
			{
				foreach($arrayListCustomerCreditLineID as $key => $value)
				{
					$objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
					$objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
					$objCustomerCreditLine["entityID"]		= $entityID;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
					$objCustomerCreditLine["dayExcluded"]		= $arrayListDayExcluded[$key];
					$limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 								= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCustomerCreditLine["currencyID"],$objCurrencyDolares->currencyID);//cordobas a dolares, o dolares a dolares.
					$exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
					$customerCreditLineID 						= $this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
					$objDataSet["customerCreditLineID"]			= $customerCreditLineID;




					//sumar los limites en dolares
					if($exchangeRate == 1)
						$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
					//sumar los limite en cordoba
					else
						$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);



				}
			}


			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
				throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			
			//Asociar el cliente al colaborador
			$objUserAdmin					=  $this->User_Model->get_rowByRoleAdmin($dataSession["user"]->companyID);			
			if($objUserAdmin)
			{
				$objListEmployerID 		= array_map(function($i) { return $i->employeeID; }, $objUserAdmin);
				$objListEmployerID[] 	= $dataSession["user"]->employeeID;
				$objListEmployerID 		= array_unique($objListEmployerID);
				
				foreach ($objListEmployerID as $employerIDT)
				{
					$dataRelationShip				= NULL;
					$dataRelationShip["employeeID"]	= $employerIDT;
					$dataRelationShip["customerID"]	= $entityID;
					$dataRelationShip["isActive"]	= 1;
					$dataRelationShip["startOn"]	= date("Y-m-d");
					$dataRelationShip["endOn"]		= date("Y-m-d");
					$this->Relationship_Model->insert_app_posme($dataRelationShip);					
				}
			}
			
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			$pathfile = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentCustomer->componentID."/component_item_".$entityID;

			if (!file_exists($pathfile))
			{
				mkdir($pathfile, 0700,true);
			}


			
			if($db->transStatus() !== false)
			{
				log_message('error', 'Webhook RAW JSON: Success');
				$db->transCommit();
				return $this->response->setJSON([
					'success' => true
				]);
				
			}
			else{
				log_message('error', 'Webhook RAW JSON: Error');
				$db->transRollback();
				return $this->response->setJSON([
					'success' => true,
					'message' => "",   		// mensaje del error
					'line'    => "0",      	// línea donde ocurrió
					'file'    => ""       	// archivo (opcional pero útil)
				]);
				
			}

		}
		catch(\Exception $ex)
		{
			return $this->response->setJSON([
				'success' => false,
				'message' => $ex->getMessage(),   // mensaje del error
				'line'    => $ex->getLine(),      // línea donde ocurrió
				'file'    => $ex->getFile()       // archivo (opcional pero útil)
			])->setStatusCode(400);
		}
		
	}

	function save($mode="",$dataSession=null){
			$mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			//$this->validation->setRule("txtCountryID","Pais","required");
			//$this->validation->setRule("txtStateID","Departamento","required");
			//$this->validation->setRule("txtCityID","Municipio","required");
			$this->validation->setRule("txtIdentification","Identificacion","required");
				
			
			//Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');
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
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');
				exit;
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
			 
						
			$dataView							= null;
			
			//Obtener Tasa de Cambio			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$callback 							= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"callback");//--finuri
			$callback							= $callback === "" ?  "false" : $callback;
			$comando							= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"comando");//--finuri
			$comando							= $comando === "" ? "false" : $comando;
			
			$objComponentAccount				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new \Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
		
			$objComponentEmployer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployer)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			
			$objParameterPais	= $this->core_web_parameter->getParameter("CXC_PAIS_DEFAULT",$companyID);			
			$objParameterPais 	= $objParameterPais->value;
			$dataView["objParameterPais"] = $objParameterPais;
			
			$objParameterDepartamento	= $this->core_web_parameter->getParameter("CXC_DEPARTAMENTO_DEFAULT",$companyID);			
			$objParameterDepartamento 	= $objParameterDepartamento->value;
			$dataView["objParameterDepartamento"] = $objParameterDepartamento;
			
			$objParameterMunicipio	= $this->core_web_parameter->getParameter("CXC_MUNICIPIO_DEFAULT",$companyID);			
			$objParameterMunicipio 	= $objParameterMunicipio->value;
			$dataView["objParameterMunicipio"] 			= $objParameterMunicipio;		
			$dataView["objComponentEmployer"]			= $objComponentEmployer;
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID);
			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer","identificationType",$companyID);
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","countryID",$companyID);
			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","clasificationID",$companyID);
			$dataView["objListCustomerTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","customerTypeID",$companyID);
			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer","categoryID",$companyID);
			$dataView["objListSubCategoryID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","subCategoryID",$companyID);
			$dataView["objListTypePay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","typePay",$companyID);
			$dataView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","payConditionID",$companyID);
			$dataView["objListSexoID"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","sexoID",$companyID);
			$dataView["objListFormContactID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","formContactID",$companyID);
			$dataView["objListEstadoCivilID"]			= $this->core_web_catalog->getCatalogAllItem("tb_naturales","statusID",$companyID);
			
			$dataView["objListProfesionID"] 			= $this->core_web_catalog->getCatalogAllItem("tb_naturales","profesionID",$companyID);
			
			$dataView["objListTypeFirmID"] 				= $this->core_web_catalog->getCatalogAllItem("tb_customer","typeFirm",$companyID);
			$dataView["objListCurrency"]				= $this->Company_Currency_Model->getByCompany($companyID);
			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);			
			$dataView["objCurrency"]					= $objCurrency;
			$dataView["objComponentAccount"]			= $objComponentAccount;
			$dataView["callback"]						= $callback;
			$dataView["comando"]						= $comando;
			$dataView["useMobile"]						= $dataSession["user"]->useMobile;		
			$dataView["company"]						= $dataSession["company"];
			
			
			$dataView["objListTypeID"]			        = $this->core_web_catalog->getCatalogAllItem("tb_customer_payment_method","typeID",$companyID);
			$dataView["objListSituationID"]			    = $this->core_web_catalog->getCatalogAllItem("tb_customer_frecuency_actuations","situationID",$companyID);
			$dataView["objListFrecuencyContactID"]	    = $this->core_web_catalog->getCatalogAllItem("tb_customer_frecuency_actuations","frecuencyContactID",$companyID);
			//Obtener catalogos de tipos de leads
			$objPCatalogTypeLeads 	= $this->Public_Catalog_Model->asObject()->
										where("systemName","tb_customer.typeLeads")->
										where("flavorID",$dataSession["company"]->flavorID)->
										where("isActive",1)->
										first();
			$objPCItemTypeLeads		= $this->Public_Catalog_Detail_Model->asObject()->
										where("publicCatalogID",helper_RequestGetValueObjet($objPCatalogTypeLeads,"publicCatalogID",0))->
										where( "isActive",1)->
										findAll();
			$dataView["objPCItemTypeLeads"]	
										= $objPCItemTypeLeads;
										
			//Obtener catalogos de sub tipos de leads
			$objPCatalogSubTypeLeads 	= $this->Public_Catalog_Model->asObject()->
										where("systemName","tb_customer.subTypeLeads")->
										where("flavorID",$dataSession["company"]->flavorID)->
										where("isActive",1)->
										first();
			$objPCItemSubTypeLeads		= $this->Public_Catalog_Detail_Model->asObject()->
										where("publicCatalogID",helper_RequestGetValueObjet($objPCatalogSubTypeLeads,"publicCatalogID",0))->
										where( "isActive",1)->
										findAll();
			$dataView["objPCItemSubTypeLeads"]	
										= $objPCItemSubTypeLeads;
										
			//Obtener catalogos de categoria de leads
			$objPCatalogCategoryLeads 	= $this->Public_Catalog_Model->asObject()->
										where("systemName","tb_customer.categoryLeads")->
										where("flavorID",$dataSession["company"]->flavorID)->
										where("isActive",1)->
										first();
			$objPCItemCategoryLeads		= $this->Public_Catalog_Detail_Model->asObject()->
										where("publicCatalogID",helper_RequestGetValueObjet($objPCatalogCategoryLeads,"publicCatalogID",0))->
										where( "isActive",1)->
										findAll();
			$dataView["objPCItemCategoryLeads"]	
										= $objPCItemCategoryLeads;
										
			
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameter("INVOICE_CANTIDAD_ITEM",$companyID);			
			$objParameterCantidadItemPoup 				= $objParameterCantidadItemPoup->value;
			$dataView["objParameterCantidadItemPoup"] 	= $objParameterCantidadItemPoup;
			
			//Obtener el componente de Item
            $objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if(!$objComponentItem)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataView["objComponentItem"] = $objComponentItem;
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);			
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxc_customer/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxc_customer/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxc_customer/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			
			
			if($callback == "false")
				return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			else
				return view("core_masterpage/default_popup",$dataSession);//--finview-r
			
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
			$cache 				= Services::cache();
			$dataViewID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $dataViewID);
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			$dataViewIDCache			= $cache->get('app_cxc_customer_dataviewid_index');
			if($dataViewIDCache && $dataViewID == null )
				$dataViewID = $dataViewIDCache;
			
			
			//Vista por defecto 
			$targetComponentID			= $this->session->get('company')->flavorID;
			if($dataViewID == null){
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;				
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				
				
				if(!$dataViewData){
					$targetComponentID			= 0;	
					$parameter["{companyID}"]	= $this->session->get('user')->companyID;					
					$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);				
				}
				
				
				$cache->save('app_cxc_customer_dataviewid_index', $dataViewData["view_config"]->dataViewID, TIME_CACHE_APP);
				if(  $this->request->getUserAgent()->isMobile()  )
				{					
					//$dataViewRender			= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFildMobile($dataViewData,'ListView',"fnTableSelectedRow");
					
				}
				else
				{
					//$dataViewRender			= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
				}
								
			}
			//Otra vista
			else{				
				$cache->save('app_cxc_customer_dataviewid_index', $dataViewID, TIME_CACHE_APP);
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter,$targetComponentID); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			//Renderizar Resultado
			$dataView["company"]					= $dataSession["company"];
			$dataView["companyPageSetting"]			= $dataSession["companyPageSetting"];
			$dataView["objParameterCORE_VIEW_CUSTOM_SCROLL_IN_LIST_CUSTOMER"]	
											= $this->core_web_parameter->getParameterValue("CORE_VIEW_CUSTOM_SCROLL_IN_LIST_CUSTOMER",$this->session->get('user')->companyID);
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxc_customer/list_head',$dataView);//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_cxc_customer/list_footer',$dataView);//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_cxc_customer/list_script',$dataView);//--finview
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
	function edit_credit_line(){
			
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
			
			
			
			
			
			
			
			$customerCreditLineID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"customerCreditLineID");//--finuri
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			
			$dataView["objListLine"]			= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]		= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objCustomerCreditLine"] 	= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
			$dataView["objListTypeAmortization"]= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
			$dataView["objListDayExcluded"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","dayExcluded",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_editcreditline_head',$dataView);//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_editcreditline_body',$dataView);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_editcreditline_script',$dataView);//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_credit_line(){
			
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
			
			
			
			
			$companyID 								= $dataSession["user"]->companyID;
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$dataView["objListLine"]				= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objListTypeAmortization"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
			$dataView["objListDayExcluded"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","dayExcluded",$companyID);
			
			$objParameterCurrenyDefault	= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$companyID);			
			$objParameterCurrenyDefault 	= $objParameterCurrenyDefault->value;
			$dataView["objParameterCurrenyDefault"] = $objParameterCurrenyDefault;
			
			
			$objParameterCXC_DAY_EXCLUDED_IN_CREDIT				= $this->core_web_parameter->getParameter("CXC_DAY_EXCLUDED_IN_CREDIT",$companyID);			
			$objParameterCXC_DAY_EXCLUDED_IN_CREDIT 			= $objParameterCXC_DAY_EXCLUDED_IN_CREDIT->value;
			$dataView["objParameterCXC_DAY_EXCLUDED_IN_CREDIT"] = $objParameterCXC_DAY_EXCLUDED_IN_CREDIT;
			
			
			$objParameterAmortizationDefault	= $this->core_web_parameter->getParameter("CXC_TYPE_AMORTIZATION",$companyID);			
			$objParameterAmortizationDefault 	= $objParameterAmortizationDefault->value;
			$dataView["objParameterAmortizationDefault"] = $objParameterAmortizationDefault;
			
			
			$objParameterInteresDefault						= $this->core_web_parameter->getParameter("CXC_INTERES_DEFAULT",$companyID);			
			$objParameterInteresDefault 					= $objParameterInteresDefault->value;
			$dataView["objParameterInteresDefault"] 		= $objParameterInteresDefault;
			
			$objParameterPayDefault						= $this->core_web_parameter->getParameter("CXC_FRECUENCIA_PAY_DEFAULT",$companyID);			
			$objParameterPayDefault 					= $objParameterPayDefault->value;
			$dataView["objParameterPayDefault"] 		= $objParameterPayDefault;
			$dataView["objParameterCXC_PLAZO_DEFAULT"]	= $this->core_web_parameter->getParameterValue("CXC_PLAZO_DEFAULT",$companyID);			
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_addcreditline_head',$dataView);//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_addcreditline_body',$dataView);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_addcreditline_script',$dataView);//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_email(){
			
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
			
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_addemail_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_addemail_body');//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_addemail_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_phone(){
			
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
			
			$companyID 						= $dataSession["user"]->companyID;
			$data["objListPhoneTypeID"]		= $this->core_web_catalog->getCatalogAllItem("tb_entity_phone","typeID",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_addphone_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_addphone_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_addphone_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}	
	function viewPrinterCarnet()
	{
		try
		{
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
			
			
			$entityID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"entityID");//--finuri									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri									
			$branchID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri									
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);			
			$objComponentShare		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
		
			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objLegal"]	 			= $this->Legal_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objCustomer"]	 			= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
			$dataViewParse["firstName"]			= $datView["objNatural"]->firstName;
			$dataViewParse["customerNumber"]	= $datView["objCustomer"]->customerNumber;
			$dataViewParse["identification"]	= $datView["objCustomer"]->identification;
			$dataViewParse["companyName"]		= $objCompany->name;
			$dataViewParse["companyAddress"]	= $objCompany->address;
			
			
			//Obtener imagen de logo			
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameterLogo       = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$path    = PATH_FILE_OF_APP_ROOT.'/img/logos/direct-ticket-'.$objParameterLogo->value;    
			$type    = pathinfo($path, PATHINFO_EXTENSION);
			$data    = file_get_contents($path);
			$base64  = 'data:image/' . $type . ';base64,' . base64_encode($data);
			$dataViewParse["imageBase64"]			= $base64;		
			
			
			$htmlTemplateCompany					= getBahavioLargeDB($objCompany->type,"app_cxc_customer","templateCarnet","");
			$htmlTemplateDemo 						= getBahavioLargeDB("demo","app_cxc_customer","templateCarnet","");
			if($htmlTemplateCompany == "")
				$htmlTemplateCompany = $htmlTemplateDemo;
		
			$parser = \Config\Services::parser();			
			$html 	= $parser->setData($dataViewParse)->renderString($htmlTemplateCompany);
			
			$this->dompdf->loadHTML($html);
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			
			$fileNamePut 			= "customer_carnet_".$entityID."_".date("dmYhis").".pdf";
			if (!file_exists(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$entityID))
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$entityID, 0777,true);						
			$path        			= "./resource/file_company/company_".$companyID."/component_".$objComponentShare->componentID."/component_item_".$entityID."/".$fileNamePut;
			
			file_put_contents(
				$path,
				$this->dompdf->output()
			);
			chmod($path, 644);
					
			//visualizar 
			$this->dompdf->stream("file.pdf ", ['Attachment' => !$objParameterShowLinkDownload ]);
			exit;
		}
		catch(\Exception $ex)
		{
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			$data 			   = array();
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}	
		
	}
	function viewPrinterDirectBalance58mm(){
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
							

			$companyID					= $dataSession["user"]->companyID;
			$customerNumber				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"customerNumber");//--finuri
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);						
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByCode($companyID,$customerNumber);
			$dataView["objNatural"]						= $this->Natural_Model->get_rowByPK($dataView["objCustumer"]->companyID,$dataView["objCustumer"]->branchID,$dataView["objCustumer"]->entityID);
			
			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency								= $this->core_web_currency->getCurrencyExternal($companyID);	
			
			$dataView["objBalanceNacional"]				= $this->Customer_Credit_Document_Model->get_rowByEntityApplied($companyID,$dataView["objCustumer"]->entityID,$objCurrency->currencyID);
			$dataView["objBalanceExtranjero"]			= $this->Customer_Credit_Document_Model->get_rowByEntityApplied($companyID,$dataView["objCustumer"]->entityID,$targetCurrency->currencyID);

			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58mmBalanceCustomer($dataView);
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

}
?>