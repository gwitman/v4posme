<?php
//posme:2023-02-27
namespace App\Controllers;
class app_public_catalog extends _BaseController {
	
    
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
			$publicCatalogID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"publicCatalogID");//--finuri			
			$companyID 				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
			if(!$publicCatalogID)
			{ 
				$this->response->redirect(base_url()."/".'app_public_catalog/add');	
			} 		
			
			
			//Obtener el componente de Item
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
		
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			
			$dataView["objPublicCatalog"]					= $this->Public_Catalog_Model->asObject()->find($publicCatalogID);
			$dataView["objPublicCatalogDetail"]				= $this->Public_Catalog_Detail_Model->asObject()
																	->where('publicCatalogID', $publicCatalogID )
																	->where('isActive', 1 )
																	->findAll();
																	
			$dataView["company"]							= $dataSession["company"];
			$dataView["objComponent"]						= $objComponent;						
			$dataView["objListWorkflowStage"]				= $this->core_web_workflow->getWorkflowStageByStageInit("tb_public_catalog","statusID",$dataView["objPublicCatalog"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_public_catalog/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_public_catalog/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_public_catalog/edit_script',$dataView);//--finview
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
			$publicCatalogID		= /*inicio get post*/ $this->request->getPost("publicCatalogID");
			
			
			if( !$publicCatalogID )
			{
					throw new \Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Public_Catalog_Model->asArray()->find($publicCatalogID);			
			$objTM["isActive"]		= 0;
			
			//Eliminar el Registro			
			$this->Public_Catalog_Model->update($publicCatalogID,$objTM);			
			
			
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
				throw new \Exception(NOT_ALL_INSERT);	
			}
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponent			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
			
			//Obtener transaccion			
			$publicCatalogID						= /*inicio get post*/ $this->request->getPost("txtPublicCatalogID");
			$objTM["name"] 							= /*inicio get post*/ $this->request->getPost("txtName");
			$objTM["systemName"] 					= /*inicio get post*/ $this->request->getPost("txtSystemName");
			$objTM["isActive"]						= 1;
			$objTM["statusID"]						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["orden"] 						= 1;
			$objTM["description"] 					= /*inicio get post*/ $this->request->getPost("txtName");	
			$objTM["flavorID"]						= $dataSession["company"]->flavorID;
			
			
			
			$db=db_connect();
			$db->transStart();
			$this->Public_Catalog_Model->update($publicCatalogID,$objTM);			

			//Recorrer la lista del detalle del documento
			$array_publicCatalogID			= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_publicCatalogID");
			$array_publicCatalogDetailID	= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_publicCatalogDetailID");
			$array_parentCatalogDetailID	= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_parentPublicCatalogDetailID");
			$array_name						= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Name");
			$array_display					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Display");			
			$array_description				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Description");			
			$array_reference1				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference1");			
			$array_reference2				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference2");			
			$array_reference3				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference3");			
			$array_reference4				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference4");			
			$array_parentName				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_ParentName");			
			$array_sequence					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Sequence");			
			$array_ratio					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Ratio");			
			$array_flavor					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Flavor");			
			
			//eliminar las que no estan
			$objList_PublicCatalogDetailCurrent		= $this->Public_Catalog_Detail_Model->getView($publicCatalogID);
			$objList_PublicCatalogDetailCurrent	    = array_column($objList_PublicCatalogDetailCurrent,"publicCatalogDetailID");			
			$objTMD 								= NULL;					
			$objTMD["isActive"] 					= 0;
			$this->Public_Catalog_Detail_Model->update($objList_PublicCatalogDetailCurrent,$objTMD);
			
			//ingresar y actualizar
			if(!empty($array_publicCatalogDetailID)){
				foreach($array_publicCatalogDetailID as $key => $value){					
					
					$objTMD 								= NULL;
					$objTMD["publicCatalogID"] 				= $publicCatalogID;
					$objTMD["publicCatalogDetailID"]		= $array_publicCatalogDetailID[$key];
					$objTMD["name"] 						= $array_name[$key];
					$objTMD["display"] 						= $array_display[$key];
					$objTMD["flavorID"]						= $array_flavor[$key];
					$objTMD["description"] 					= $array_description[$key];
					$objTMD["sequence"] 					= $array_sequence[$key];
					$objTMD["parentCatalogDetailID"]		= 0;
					$objTMD["ratio"] 						= $array_ratio[$key];
					$objTMD["isActive"] 					= 1;
					$objTMD["reference1"]					= $array_reference1[$key];
					$objTMD["reference2"]					= $array_reference2[$key];
					$objTMD["reference3"]					= $array_reference3[$key];
					$objTMD["reference4"]					= $array_reference4[$key];
					$objTMD["parentName"]					= $array_parentName[$key];
					
					$this->Public_Catalog_Detail_Model->save($objTMD);
				}
			}
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect( base_url()."/".'app_public_catalog/edit/publicCatalogID/'.$publicCatalogID  );
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->$db->_error_message());
				$this->response->redirect(base_url()."/".'app_public_catalog/add');	
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
			$objComponent			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
			
			//Obtener transaccion			
			$objTM["name"] 							= /*inicio get post*/ $this->request->getPost("txtName");
			$objTM["systemName"] 					= /*inicio get post*/ $this->request->getPost("txtSystemName");
			$objTM["isActive"]						= 1;
			$objTM["statusID"]						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTM["orden"] 						= 1;
			$objTM["description"] 					= /*inicio get post*/ $this->request->getPost("txtName");	
			$objTM["flavorID"] 						= $dataSession["company"]->flavorID;
			
			
			
			$db=db_connect();
			$db->transStart();
			$publicCatalogID = $this->Public_Catalog_Model->insert($objTM);			
			
			
			//Recorrer la lista del detalle del documento
			$array_publicCatalogID			= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_publicCatalogID");
			$array_publicCatalogDetailID	= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_publicCatalogDetailID");
			$array_parentCatalogDetailID	= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_parentPublicCatalogDetailID");
			$array_name						= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Name");
			$array_display					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Display");			
			$array_description				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Description");			
			$array_reference1				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference1");			
			$array_reference2				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference2");			
			$array_reference3				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference3");			
			$array_reference4				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Reference4");			
			$array_parentName				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_ParentName");						
			$array_sequence					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Sequence");			
			$array_ratio					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Ratio");			
			$array_flavor					= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetail_Flavor");			
			
			
			if(!empty($array_publicCatalogDetailID)){
				foreach($array_publicCatalogDetailID as $key => $value){
					
					
					$objTMD 								= NULL;					
					$objTMD["publicCatalogID"] 				= $publicCatalogID;					
					$objTMD["name"] 						= $array_name[$key];
					$objTMD["display"] 						= $array_display[$key];
					$objTMD["flavorID"]						= $array_flavor[$key];
					$objTMD["description"] 					= $array_description[$key];
					$objTMD["sequence"] 					= $array_sequence[$key];
					$objTMD["parentCatalogDetailID"]		= 0;
					$objTMD["ratio"] 						= $array_ratio[$key];
					$objTMD["isActive"] 					= 1;
					$objTMD["reference1"]					= $array_reference1[$key];
					$objTMD["reference2"]					= $array_reference2[$key];
					$objTMD["reference3"]					= $array_reference3[$key];
					$objTMD["reference4"]					= $array_reference4[$key];
					$objTMD["parentName"]					= $array_parentName[$key];
					
					
					$this->Public_Catalog_Detail_Model->insert($objTMD);
				}
			}
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect( base_url()."/".'app_public_catalog/edit/publicCatalogID/'.$publicCatalogID  );
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$db->_error_message());
				$this->response->redirect(base_url()."/".'app_public_catalog/add');	
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
			$this->validation->setRule("txtName","Nombre","required");
			
			 //Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_public_catalog/add');
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
				$this->response->redirect(base_url()."/".'app_public_catalog/add');
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
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
			
			
			
			//Tipo de Factura			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			
			
			$dataView["company"]							= $dataSession["company"];
			$dataView["objComponent"]						= $objComponent;						
			$dataView["objListWorkflowStage"]				= $this->core_web_workflow->getWorkflowInitStage("tb_public_catalog","statusID",$companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_public_catalog/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_public_catalog/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_public_catalog/news_script',$dataView);//--finview
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
			
			
			//Vista por defecto PC
			if($dataViewID == null and  !$this->request->getUserAgent()->isMobile() ){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$parameter["{flavorID}"]	= $this->session->get('company')->flavorID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}		
			//Vista por defecto MOBILE
			else if( $this->request->getUserAgent()->isMobile() ){
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$parameter["{flavorID}"]	= $this->session->get('company')->flavorID;
				$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$objComponent->componentID,"DEFAULT_MOBILE_LISTA_ABONOS",CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			//Vista Por Id
			else 
			{
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$parameter["{flavorID}"]	= $this->session->get('company')->flavorID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			
			//Renderizar Resultado
			$dataViewHeader["company"]		= $dataSession["company"];
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID,$dataViewHeader);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_public_catalog/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_public_catalog/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_public_catalog/list_script');//--finview
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
			$objTM 	= $this->Public_Catalog_Model->find($transactionNumber);	
			
			if(!$objTM)
			throw new \Exception("NO SE ENCONTRO EL CATALOGO");	
			
			
			
			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'publicCatalogID' 			=> $objTM->publicCatalogID
			));//--finjson
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}
}
?>