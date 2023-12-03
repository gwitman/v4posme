<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxp_provider extends _BaseController {
	
    
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
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			$companyID_ 							= /*inicio get post*/ $this->request->getPost("txtCompanyID");
			$branchID_								= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$entityID_								= /*inicio get post*/ $this->request->getPost("txtEntityID");
			
			$objProvider							= $this->Provider_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
			$oldStatusID 							= $objProvider->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objProvider->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$objProvider->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objProvider["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->Provider_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objProvider);
			}
			else{
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
				$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
				$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Natural_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objNatural);
				$objLegal["isActive"]		= true;
				$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
				$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
				$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Legal_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objLegal);
				
				$objProvider 							= NULL;
				$objProvider["numberIdentification"]	= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
				$objProvider["identificationTypeID"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
				$objProvider["providerType"]			= /*inicio get post*/ $this->request->getPost('txtProviderTypeID');//--fin peticion get o post
				$objProvider["providerCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
				$objProvider["providerClasificationID"]	= /*inicio get post*/ $this->request->getPost("txtClasificationID");//--fin peticion get o post
				$objProvider["reference1"]				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
				$objProvider["reference2"]				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
				$objProvider["payConditionID"]			= /*inicio get post*/ $this->request->getPost("txtTypePayID");//--fin peticion get o post
				$objProvider["isLocal"]					= /*inicio get post*/ $this->request->getPost('txtIsLocal');//--fin peticion get o post
				$objProvider["countryID"]				= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
				$objProvider["stateID"]					= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
				$objProvider["cityID"]					= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
				$objProvider["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$objProvider["currencyID"]				= /*inicio get post*/ $this->request->getPost('txtCurrencyID');//--fin peticion get o post
				$objProvider["statusID"]				= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$objProvider["deleveryDay"]				= /*inicio get post*/ $this->request->getPost('txtDayDelevery');//--fin peticion get o post
				$objProvider["deleveryDayReal"]			= /*inicio get post*/ $this->request->getPost('txtDayDeleveryReal');//--fin peticion get o post
				$objProvider["distancia"]				= /*inicio get post*/ $this->request->getPost("txtDistancia");//--fin peticion get o post
				$objProvider["isActive"]				= true;
				$this->Provider_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objProvider);
			
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
			
			//Confirmar Entidad
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxp_provider/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');	
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
			
				
			
			
			
				
			
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;			
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;			
			$objEntity["branchID"]					= $dataSession["user"]->branchID;			
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession,$this->request);
			
			$db=db_connect();
			$db->transStart();
			$entityID = $this->Entity_Model->insert_app_posme($objEntity);
			
			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
			$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
			$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$result 					= $this->Natural_Model->insert_app_posme($objNatural);
			
			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
			$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
			$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$result 					= $this->Legal_Model->insert_app_posme($objLegal);
			
			$objProvider["companyID"]				= $objEntity["companyID"];
			$objProvider["branchID"]				= $objEntity["branchID"];
			$objProvider["entityID"]				= $entityID;
			$objProvider["providerNumber"]			= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_provider",0);
			$objProvider["numberIdentification"]	= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
			$objProvider["identificationTypeID"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
			$objProvider["providerType"]			= /*inicio get post*/ $this->request->getPost('txtProviderTypeID');//--fin peticion get o post
			$objProvider["providerCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
			$objProvider["providerClasificationID"]	= /*inicio get post*/ $this->request->getPost("txtClasificationID");//--fin peticion get o post
			$objProvider["reference1"]				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objProvider["reference2"]				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objProvider["payConditionID"]			= /*inicio get post*/ $this->request->getPost("txtTypePayID");//--fin peticion get o post
			$objProvider["isLocal"]					= /*inicio get post*/ $this->request->getPost('txtIsLocal');//--fin peticion get o post
			$objProvider["countryID"]				= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
			$objProvider["stateID"]					= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
			$objProvider["cityID"]					= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
			$objProvider["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$objProvider["currencyID"]				= /*inicio get post*/ $this->request->getPost('txtCurrencyID');//--fin peticion get o post
			$objProvider["statusID"]				= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
			$objProvider["deleveryDay"]				= /*inicio get post*/ $this->request->getPost('txtDayDelevery');//--fin peticion get o post
			$objProvider["deleveryDayReal"]			= /*inicio get post*/ $this->request->getPost('txtDayDeleveryReal');//--fin peticion get o post
			$objProvider["distancia"]				= /*inicio get post*/ $this->request->getPost("txtDistancia");//--fin peticion get o post
			$objProvider["isActive"]				= true;
			$this->core_web_auditoria->setAuditCreated($objProvider,$dataSession,$this->request);
			$result 							= $this->Provider_Model->insert_app_posme($objProvider);
			
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
			
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID, 0700);
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxp_provider/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');	
			}
			
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
			$companyID 			= /*inicio get post*/ $this->request->getPost("companyID");
			$branchID 			= /*inicio get post*/ $this->request->getPost("branchID");				
			$entityID 			= /*inicio get post*/ $this->request->getPost("entityID");				
			
			if((!$companyID && !$branchID && !$entityID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL PROVEEDOR
			$objProvider 		= $this->Provider_Model->get_rowByPK($companyID,$branchID,$entityID);	
			
			if ($entityID == APP_PROVIDER)
			{
				throw new \Exception("No es posible eliminar el proveedor, edite el nombre");
			}
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objProvider->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$objProvider->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Provider_Model->delete_app_posme($companyID,$branchID,$entityID);
					
			
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
	function save($mode=""){
			$mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtCountryID","Pais","required");
			$this->validation->setRule("txtStateID","Departamento","required");
			$this->validation->setRule("txtCityID","Municipio","required");
			$this->validation->setRule("txtIdentification","Identificacion","required");
				
				
			//Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');
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
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');
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
			
			//Librerias		
			//
			////////////////////////////////////////
			 
			 			
			
			
						
			
			
			
			
			//Redireccionar datos
			
						
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$branchID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri	
			$entityID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"entityID");//--finuri	
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$branchID || !$entityID))
			{ 
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objLegal"]	 			= $this->Legal_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objProvider"]	 			= $this->Provider_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objEntityListEmail"]		= $this->Entity_Email_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objEntityListPhone"]		= $this->Entity_Phone_Model->get_rowByEntity($companyID,$branchID,$entityID);
			
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
			//Obtener Informacion
			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_provider","statusID",$datView["objProvider"]->statusID,$companyID,$branchIDUser,$roleID);
			$datView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);			
			$datView["objComponent"] 					= $objComponent;
			$datView["objCurrency"]						= $this->core_web_currency->getCurrencyDefault($companyID);			
			$datView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_provider","countryID",$companyID);
			$datView["objListState"]					= $this->core_web_catalog->getCatalogAllItem_Parent("tb_provider","stateID",$companyID,$datView["objProvider"]->countryID);
			$datView["objListCity"]						= $this->core_web_catalog->getCatalogAllItem_Parent("tb_provider","cityID",$companyID,$datView["objProvider"]->stateID);			
			$datView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_provider","identificationTypeID",$companyID);						
			$datView["objListProviderTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerType",$companyID);
			$datView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerCategoryID",$companyID);
			$datView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerClasificationID",$companyID);
			$datView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","payConditionID",$companyID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_provider/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_provider/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_provider/edit_script',$datView);//--finview
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
			 
						
			$dataView							= null;
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_provider","statusID",$companyID,$branchID,$roleID);
			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_provider","identificationTypeID",$companyID);
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_provider","countryID",$companyID);
			
			$dataView["objListProviderTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerType",$companyID);			
			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerCategoryID",$companyID);			
			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerClasificationID",$companyID);			
			$dataView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","payConditionID",$companyID);
			
			$dataView["objListCurrency"]				= $this->Company_Currency_Model->getByCompany($companyID);
			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);			
			$dataView["objCurrency"]					= $objCurrency;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_provider/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_provider/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_provider/news_script',$dataView);//--finview
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_provider/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_cxp_provider/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_provider/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
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
			$dataSession["head"]		= /*--inicio view*/ view('app_cxp_provider/popup_addemail_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxp_provider/popup_addemail_body');//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxp_provider/popup_addemail_script');//--finview
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
			$dataSession["head"]		= /*--inicio view*/ view('app_cxp_provider/popup_addphone_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxp_provider/popup_addphone_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxp_provider/popup_addphone_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	
}
?>