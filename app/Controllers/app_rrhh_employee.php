<?php
//posme:2023-02-27
namespace App\Controllers;
class app_rrhh_employee extends _BaseController {
	
    
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
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			$companyID_ 							= /*inicio get post*/ $this->request->getPost("txtCompanyID");
			$branchID_								= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$entityID_								= /*inicio get post*/ $this->request->getPost("txtEntityID");
			
			$objEmployee							= $this->Employee_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
			$oldStatusID 							= $objEmployee->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objEmployee->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_employee","statusID",$objEmployee->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_employee","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objEmployee["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->Employee_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objEmployee);
			}
			else{
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
				$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
				$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Natural_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objNatural);
				$objEmployee 							= NULL;				
				$objEmployee["numberIdentification"]	= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
				$objEmployee["identificationTypeID"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
				$objEmployee["typeEmployeeID"]			= /*inicio get post*/ $this->request->getPost('txtTypeEmployeeID');//--fin peticion get o post
				$objEmployee["categoryID"]				= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
				$objEmployee["clasificationID"]			= /*inicio get post*/ $this->request->getPost("txtClasificationID");//--fin peticion get o post
				$objEmployee["reference1"]				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
				$objEmployee["reference2"]				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
				$objEmployee["socialSecurityNumber"]	= /*inicio get post*/ $this->request->getPost("txtSocialSecurityNumber");//--fin peticion get o post
				$objEmployee["hourCost"]				= /*inicio get post*/ $this->request->getPost('txtHourCost');//--fin peticion get o post
				$objEmployee["countryID"]				= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
				$objEmployee["stateID"]					= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
				$objEmployee["cityID"]					= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
				$objEmployee["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post			
				$objEmployee["statusID"]				= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$objEmployee["departamentID"]			= /*inicio get post*/ $this->request->getPost('txtDepartamentID');//--fin peticion get o post
				$objEmployee["areaID"]					= /*inicio get post*/ $this->request->getPost('txtAreaID');//--fin peticion get o post
				$objEmployee["parentEmployeeID"]		= /*inicio get post*/ $this->request->getPost("txtParentEmployeeID");//--fin peticion get o post
				$objEmployee["startOn"]					= /*inicio get post*/ $this->request->getPost("txtStartOn");//--fin peticion get o post
				$objEmployee["endOn"]					= /*inicio get post*/ $this->request->getPost("txtEndOn");//--fin peticion get o post
				$objEmployee["vacationBalanceDay"]		= /*inicio get post*/ $this->request->getPost("txtVacationBalanceDay");//--fin peticion get o post			
				$objEmployee["isActive"]				= true;
				$this->Employee_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objEmployee);
			
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
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key];
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
				$this->response->redirect(base_url()."/".'app_rrhh_employee/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);
			}
			else{
				$db->transRollback();	
				$errorCode 		= $db->error()["code"];
				$errorMessage 	= $db->error()["message"];				
				$this->core_web_notification->set_message(true,$errorCode." ".$errorMessage);
				$this->response->redirect(base_url()."/".'app_rrhh_employee/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);
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
				$this->response->redirect(base_url()."/".'app_rrhh_employee/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objEmployee"]	 			= $this->Employee_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objParentEmployee"] 		= $this->Employee_Model->get_rowByEntityID($companyID,$datView["objEmployee"]->parentEmployeeID); 
			$datView["objParentNatural"]		= $datView["objParentEmployee"] == null ? $datView["objParentEmployee"] : $this->Natural_Model->get_rowByPK($companyID,$datView["objParentEmployee"]->branchID,$datView["objParentEmployee"]->entityID);
			$datView["objEntityListEmail"]		= $this->Entity_Email_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objEntityListPhone"]		= $this->Entity_Phone_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["company"]					= $dataSession["company"];

			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			//Obtener Informacion
			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_employee","statusID",$datView["objEmployee"]->statusID,$companyID,$branchIDUser,$roleID);
			$datView["objComponent"] 					= $objComponent;
			$datView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","countryID",$companyID);
			$datView["objListState"]					= $this->core_web_catalog->getCatalogAllItem_Parent("tb_employee","stateID",$companyID,$datView["objEmployee"]->countryID);
			$datView["objListCity"]						= $this->core_web_catalog->getCatalogAllItem_Parent("tb_employee","cityID",$companyID,$datView["objEmployee"]->stateID);			
			$datView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_employee","identificationTypeID",$companyID);	
			
			
			$datView["objListDepartamentID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","departamentID",$companyID);					
			$datView["objListAreaID"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","areaID",$companyID);			
			$datView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","clasificationID",$companyID);			
			$datView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_employee","categoryID",$companyID);
			$datView["objListTypeEmployeeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","typeEmployeeID",$companyID);
			
			
			////Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_rrhh_employee/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_rrhh_employee/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_rrhh_employee/edit_script',$datView);//--finview
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
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
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
			
			$objEmployee["companyID"]				= $objEntity["companyID"];
			$objEmployee["branchID"]				= $objEntity["branchID"];
			$objEmployee["entityID"]				= $entityID;
			$objEmployee["employeNumber"]			= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_employee",0);
			$objEmployee["numberIdentification"]	= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
			$objEmployee["identificationTypeID"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
			$objEmployee["typeEmployeeID"]			= /*inicio get post*/ $this->request->getPost('txtTypeEmployeeID');//--fin peticion get o post
			$objEmployee["categoryID"]				= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
			$objEmployee["clasificationID"]			= /*inicio get post*/ $this->request->getPost("txtClasificationID");//--fin peticion get o post
			$objEmployee["reference1"]				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objEmployee["reference2"]				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objEmployee["socialSecurityNumber"]	= /*inicio get post*/ $this->request->getPost("txtSocialSecurityNumber");//--fin peticion get o post
			$objEmployee["hourCost"]				= /*inicio get post*/ $this->request->getPost('txtHourCost');//--fin peticion get o post
			$objEmployee["countryID"]				= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
			$objEmployee["stateID"]					= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
			$objEmployee["cityID"]					= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
			$objEmployee["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post			
			$objEmployee["statusID"]				= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
			$objEmployee["departamentID"]			= /*inicio get post*/ $this->request->getPost('txtDepartamentID');//--fin peticion get o post
			$objEmployee["areaID"]					= /*inicio get post*/ $this->request->getPost('txtAreaID');//--fin peticion get o post
			$objEmployee["parentEmployeeID"]		= /*inicio get post*/ $this->request->getPost("txtParentEmployeeID");//--fin peticion get o post
			$objEmployee["startOn"]					= /*inicio get post*/ $this->request->getPost("txtStartOn");//--fin peticion get o post
			$objEmployee["endOn"]					= /*inicio get post*/ $this->request->getPost("txtEndOn");//--fin peticion get o post
			$objEmployee["vacationBalanceDay"]		= /*inicio get post*/ $this->request->getPost("txtVacationBalanceDay");//--fin peticion get o post			
			$objEmployee["isActive"]				= true;
			$this->core_web_auditoria->setAuditCreated($objEmployee,$dataSession,$this->request);
			$result 							= $this->Employee_Model->insert_app_posme($objEmployee);
			
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
				$this->response->redirect(base_url()."/".'app_rrhh_employee/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_rrhh_employee/add');	
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
			
			//OBTENER EL EMPLEADO
			$objEmployee 		= $this->Employee_Model->get_rowByPK($companyID,$branchID,$entityID);	
			
			if ($entityID == APP_EMPLOYEE)
			{
				throw new \Exception("No es posible eliminar el colaborador, edite el nombre");
			}
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objEmployee->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_employee","statusID",$objEmployee->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Employee_Model->delete_app_posme($companyID,$branchID,$entityID);
					
			
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
				$this->response->redirect(base_url()."/".'app_rrhh_employee/add');
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
				$this->response->redirect(base_url()."/".'app_rrhh_employee/add');
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
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			$dataView["componentEmployeeID"] 			= $objComponent->componentID;			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_employee","statusID",$companyID,$branchID,$roleID);
			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_employee","identificationTypeID",$companyID);			
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","countryID",$companyID);						
			$dataView["objListDepartamentID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","departamentID",$companyID);					
			$dataView["objListAreaID"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","areaID",$companyID);			
			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","clasificationID",$companyID);			
			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_employee","categoryID",$companyID);
			$dataView["objListTypeEmployeeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee","typeEmployeeID",$companyID);
			$dataView["company"]						= $dataSession["company"];
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_rrhh_employee/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_rrhh_employee/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_rrhh_employee/news_script',$dataView);//--finview
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
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
			$dataView["company"]			= $dataSession["company"];
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_rrhh_employee/list_head',$dataView);//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_rrhh_employee/list_footer',$dataView);//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_rrhh_employee/list_script',$dataView);//--finview
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
			$dataSession["head"]		= /*--inicio view*/ view('app_rrhh_employee/popup_addemail_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_rrhh_employee/popup_addemail_body');//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_rrhh_employee/popup_addemail_script');//--finview
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
			$dataSession["head"]		= /*--inicio view*/ view('app_rrhh_employee/popup_addphone_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_rrhh_employee/popup_addphone_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_rrhh_employee/popup_addphone_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	
}
?>