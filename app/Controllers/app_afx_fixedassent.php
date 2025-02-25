<?php
//posme:2023-02-27
namespace App\Controllers;
class app_afx_fixedassent extends _BaseController {
	
    
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
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			
			
			$companyID_ 							= /*inicio get post*/ $this->request->getPost("txtCompanyID");
			$branchID_								= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$fixedAssentID_							= /*inicio get post*/ $this->request->getPost("txtFixedAssentID");
			
			$objFA									= $this->Fixed_Assent_Model->get_rowByPK($companyID_,$branchID_,$fixedAssentID_);
			$oldStatusID 							= $objFA->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objFA->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_fixed_assent","statusID",$objFA->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_fixed_assent","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				
				$objFA 					= NULL;		
				$objFA["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->Fixed_Assent_Model->update_app_posme($companyID_,$branchID_,$fixedAssentID_,$objFA);
			}
			else{
				
				$objFA 								= NULL;		
				$objFA["branchID"]					= /*inicio get post*/ $this->request->getPost("txtBranchID");
				$objFA["name"]						= /*inicio get post*/ $this->request->getPost('txtName');//--fin peticion get o post
				$objFA["description"]				= /*inicio get post*/ $this->request->getPost('txtDescription');//--fin peticion get o post
				$objFA["modelNumber"]				= /*inicio get post*/ $this->request->getPost('txtModelNumber');//--fin peticion get o post
				$objFA["marca"]						= /*inicio get post*/ $this->request->getPost('txtMarca');//--fin peticion get o post
				$objFA["colorID"]					= /*inicio get post*/ $this->request->getPost('txtColorID');//--fin peticion get o post
				$objFA["chasisNumber"]				= /*inicio get post*/ $this->request->getPost('txtChasisNumber');//--fin peticion get o post
				$objFA["reference1"]				= /*inicio get post*/ $this->request->getPost('txtReference1');//--fin peticion get o post
				$objFA["reference2"]				= /*inicio get post*/ $this->request->getPost('txtReference2');//--fin peticion get o post
				$objFA["year"]						= /*inicio get post*/ $this->request->getPost('txtYear');//--fin peticion get o post
				$objFA["asignedEmployeeID"]			= /*inicio get post*/ $this->request->getPost('txtAsignedEmployeeID');//--fin peticion get o post
				$objFA["categoryID"]				= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
				$objFA["typeID"]					= /*inicio get post*/ $this->request->getPost('txtTypeID');//--fin peticion get o post
				$objFA["typeDepresiationID"]		= /*inicio get post*/ $this->request->getPost('txtTypeDepresiationID');//--fin peticion get o post
				$objFA["yearOfUtility"]				= /*inicio get post*/ $this->request->getPost('txtYearUtility');//--fin peticion get o post
				$objFA["currencyID"]				= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
				$objFA["priceStart"]				= /*inicio get post*/ $this->request->getPost('txtPriceStart');//--fin peticion get o post
				$objFA["isForaneo"]					= /*inicio get post*/ $this->request->getPost('txtIsForaneo');//--fin peticion get o post
				$objFA["statusID"]					= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$objFA["countryID"]					= /*inicio get post*/ $this->request->getPost("txtCountryID");
				$objFA["cityID"]					= /*inicio get post*/ $this->request->getPost("txtStateID");
				$objFA["municipalityID"]			= /*inicio get post*/ $this->request->getPost("txtCityID");
				$objFA["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");
				$objFA["areaID"]					= /*inicio get post*/ $this->request->getPost("txtAreaID");
				$objFA["projectID"]					= /*inicio get post*/ $this->request->getPost("txtProyectID");
				$objFA["duration"]					= /*inicio get post*/ $this->request->getPost("txtDuration");
				$objFA["typeFixedAssentID"]			= /*inicio get post*/ $this->request->getPost("txtTypeAssentID");
				$objFA["startOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
				$objFA["ratio"]						= /*inicio get post*/ $this->request->getPost("txtRatio");
				$objFA["settlementAmount"]			= /*inicio get post*/ $this->request->getPost("txtSettlementAmount");
				$objFA["currentAmount"]				= /*inicio get post*/ $this->request->getPost("txtCurrentAmount");
				$this->Fixed_Assent_Model->update_app_posme($companyID_,$branchID_,$fixedAssentID_,$objFA);
			
			}
			
			//Confirmar Entidad
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_afx_fixedassent/edit/companyID/'.$companyID_."/branchID/".$branchID_."/fixedAssentID/".$fixedAssentID_);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_afx_fixedassent/add');	
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
			$fixedAssentID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"fixedAssentID");//--finuri	
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$branchID || !$fixedAssentID))
			{ 
				$this->response->redirect(base_url()."/".'app_afx_fixedassent/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objFA"]	 				= $this->Fixed_Assent_Model->get_rowByPK($companyID,$branchID,$fixedAssentID);			
			$datView["objAsignedEmployee"] 		= $this->Employee_Model->get_rowByEntityID($companyID,$datView["objFA"]->asignedEmployeeID); 
			$datView["objAsignedNatural"]		= $datView["objAsignedEmployee"] == null ? $datView["objAsignedEmployee"] : $this->Natural_Model->get_rowByPK($companyID,$datView["objAsignedEmployee"]->branchID,$datView["objAsignedEmployee"]->entityID);
			
			
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			$objComponentFA						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponentFA)
			throw new \Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			$objComponentCatalog					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponentCatalog)
			throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");

			//Obtener Informacion
			$datView["objComponentCatalog"]			= $objComponentCatalog;
			$datView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_fixed_assent","statusID",$datView["objFA"]->statusID,$companyID,$branchIDUser,$roleID);
			$datView["componentEmployeeID"] 		= $objComponent->componentID;
			$datView["objComponentFA"] 				= $objComponentFA;
			$datView["objListColor"]				= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","colorID",$companyID);			
			$datView["objListCategory"]				= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","categoryID",$companyID);						
			$datView["objListType"]					= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeID",$companyID);					
			$datView["objListTypeDepresiation"]		= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeDepresiationID",$companyID);
			$datView["objListBranch"]				= $this->Branch_Model->getByCompany($companyID);
			$datView["objListTypeFixedAssent"]		= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeFixedAssentID",$companyID);
			$datView["objListCountry"]				= $this->core_web_catalog->getCatalogAllItem("tb_employee","countryID",$companyID);
			$datView["objListState"]				= $this->core_web_catalog->getCatalogAllItem_Parent("tb_employee","stateID",$companyID,$datView["objFA"]->countryID);			
			$datView["objListMunicipality"]			= $this->core_web_catalog->getCatalogAllItem_Parent("tb_employee","cityID",$companyID,$datView["objFA"]->cityID);
			$datView["objListCurrency"]             = $this->Company_Currency_Model->getByCompany($companyID);
			$datView["objArea"]						= $this->Public_Catalog_Detail_Model->get_rowByPk($datView["objFA"]->areaID);
			$datView["objProject"]					= $this->Public_Catalog_Detail_Model->get_rowByPk($datView["objFA"]->projectID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_afx_fixedassent/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_afx_fixedassent/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_afx_fixedassent/edit_script',$datView);//--finview
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
			};
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			
			//Obtener transaccion
			$companyID 							= $dataSession["user"]->companyID;			
			$objFA["companyID"] 				= $dataSession["user"]->companyID;			
			$objFA["branchID"]					= $this->request->getPost("txtBranchID");
			$objFA["fixedAssentCode"]			= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_fixed_assent",0);
			$objFA["name"]						= /*inicio get post*/ $this->request->getPost('txtName');//--fin peticion get o post
			$objFA["description"]				= /*inicio get post*/ $this->request->getPost('txtDescription');//--fin peticion get o post
			$objFA["modelNumber"]				= /*inicio get post*/ $this->request->getPost('txtModelNumber');//--fin peticion get o post
			$objFA["marca"]						= /*inicio get post*/ $this->request->getPost('txtMarca');//--fin peticion get o post
			$objFA["colorID"]					= /*inicio get post*/ $this->request->getPost('txtColorID');//--fin peticion get o post
			$objFA["chasisNumber"]				= /*inicio get post*/ $this->request->getPost('txtChasisNumber');//--fin peticion get o post
			$objFA["reference1"]				= /*inicio get post*/ $this->request->getPost('txtReference1');//--fin peticion get o post
			$objFA["reference2"]				= /*inicio get post*/ $this->request->getPost('txtReference2');//--fin peticion get o post
			$objFA["year"]						= /*inicio get post*/ $this->request->getPost('txtYear');//--fin peticion get o post
			$objFA["asignedEmployeeID"]			= /*inicio get post*/ $this->request->getPost('txtAsignedEmployeeID');//--fin peticion get o post
			$objFA["categoryID"]				= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
			$objFA["typeID"]					= /*inicio get post*/ $this->request->getPost('txtTypeID');//--fin peticion get o post
			$objFA["typeDepresiationID"]		= /*inicio get post*/ $this->request->getPost('txtTypeDepresiationID');//--fin peticion get o post
			$objFA["yearOfUtility"]				= /*inicio get post*/ $this->request->getPost('txtYearUtility');//--fin peticion get o post
			$objFA["currencyID"]				= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
			$objFA["priceStart"]				= /*inicio get post*/ $this->request->getPost('txtPriceStart');//--fin peticion get o post
			$objFA["isForaneo"]					= /*inicio get post*/ $this->request->getPost('txtIsForaneo');//--fin peticion get o post			
			$objFA["statusID"]					= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
			$objFA["countryID"]					= /*inicio get post*/ $this->request->getPost("txtCountryID");
			$objFA["cityID"]					= /*inicio get post*/ $this->request->getPost("txtStateID");
			$objFA["municipalityID"]			= /*inicio get post*/ $this->request->getPost("txtCityID");
			$objFA["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");
			$objFA["areaID"]					= /*inicio get post*/ $this->request->getPost("txtAreaID");
			$objFA["projectID"]					= /*inicio get post*/ $this->request->getPost("txtProyectID");
			$objFA["duration"]					= /*inicio get post*/ $this->request->getPost("txtDuration");
			$objFA["typeFixedAssentID"]			= /*inicio get post*/ $this->request->getPost("txtTypeAssentID");
			$objFA["startOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objFA["ratio"]						= /*inicio get post*/ $this->request->getPost("txtRatio");
			$objFA["settlementAmount"]			= /*inicio get post*/ $this->request->getPost("txtSettlementAmount");
			$objFA["currentAmount"]				= /*inicio get post*/ $this->request->getPost("txtCurrentAmount");
			$objFA["isActive"]					= 1;
			$this->core_web_auditoria->setAuditCreated($objFA,$dataSession,$this->request);
			
			$db=db_connect();
			$db->transStart();
			$fixedAssentID = $this->Fixed_Assent_Model->insert_app_posme($objFA);
			
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			if(!file_exists(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$fixedAssentID))
			{
				mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$fixedAssentID, 0700, true);
			}

			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_afx_fixedassent/edit/companyID/'.$companyID."/branchID/".$objFA["branchID"]."/fixedAssentID/".$fixedAssentID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$db->error());
				$this->response->redirect(base_url()."/".'app_afx_fixedassent/add');	
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
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$branchID 				= /*inicio get post*/ $this->request->getPost("branchID");				
			$fixedAssentID 			= /*inicio get post*/ $this->request->getPost("fixedAssentID");				
			
			if((!$companyID && !$branchID && !$fixedAssentID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL EMPLEADO
			$objFixedAssent 		= $this->Fixed_Assent_Model->get_rowByPK($companyID,$branchID,$fixedAssentID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objFixedAssent->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_fixed_assent","statusID",$objFixedAssent->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Fixed_Assent_Model->delete_app_posme($companyID,$branchID,$fixedAssentID);
					
			
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
			$this->validation->setRule("txtName","Nombre","required");
			$this->validation->setRule("txtCategoryID","Categoria","required");
			$this->validation->setRule("txtTypeID","Tipo","required");
				
				
			//Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_afx_fixedassent/add');
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
				$this->response->redirect(base_url()."/".'app_afx_fixedassent/add');
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
			
			$dataView							= [];			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			$objComponentCatalog					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponentCatalog)
			throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
			
			$dataView["componentEmployeeID"] 			= $objComponent->componentID;			
			$dataView["objComponentCatalog"]			= $objComponentCatalog;
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_fixed_assent","statusID",$companyID,$branchID,$roleID);
			$dataView["objListColor"]					= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","colorID",$companyID);			
			$dataView["objListCategory"]				= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","categoryID",$companyID);						
			$dataView["objListType"]					= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeID",$companyID);					
			$dataView["objListTypeDepresiation"]		= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeDepresiationID",$companyID);
			$dataView["objListCurrency"]                = $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_employee","countryID",$companyID);
			$dataView["objListBranch"]					= $this->Branch_Model->getByCompany($companyID);
			$dataView["objListTypeFixedAssent"]			= $this->core_web_catalog->getCatalogAllItem("tb_fixed_assent","typeFixedAssentID",$companyID);

			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_afx_fixedassent/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_afx_fixedassent/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_afx_fixedassent/news_script',$dataView);//--finview
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_fixed_assent");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_fixed_assent' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_afx_fixedassent/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_afx_fixedassent/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_afx_fixedassent/list_script');//--finview
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
	
}
?>