<?php
//posme:2023-02-27
namespace App\Controllers;
class core_role extends _BaseController {
	
    
	
	function delete(){
		try{ 
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
			$companyID 	= /*inicio get post*/ $this->request->getPost("companyID");
			$branchID 	= /*inicio get post*/ $this->request->getPost("branchID");
			$roleID		= /*inicio get post*/ $this->request->getPost("roleID");	
			
			if((!$companyID && !$branchID   && !$roleID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			$obj 			= $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);			
			$obj->isActive	= false;
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			$obj			= (array)$obj;			
			$result 		= $this->Role_Model->update_app_posme($companyID,$branchID,$roleID,$obj);
					
			
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
	function save(){
		 try{ 
			//AUTENTICACION			
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			  
			
					
			
			//Validar Formulario			
			//Datos Requerido para que el Usuario pueda ser Seleccionado
			$this->validation->setRule("txtName","Nombre ","required");    
			$this->validation->setRule("txtUrlDefault","Url Default ","required");    
			$this->validation->setRule("txtDescription","Descripcion ","required");    			
						
			
			 
			//Nuevo Registro
			$companyID 	= /*inicio get post*/ $this->request->getPost("companyID");
			$branchID 	= /*inicio get post*/ $this->request->getPost("branchID");
			$roleID		= /*inicio get post*/ $this->request->getPost("roleID");		
			if((!$companyID && !$branchID   && !$roleID) && $this->validation->withRequest($this->request)->run() == true ){
					
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
							$permited = false;
							$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
							
							if(!$permited)
							throw new \Exception(NOT_ACCESS_CONTROL);
							
							$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
							if ($resultPermission 	== PERMISSION_NONE)
							throw new \Exception(NOT_ACCESS_FUNCTION);			
						
					}	 					
					
					$db=db_connect();
			$db->transStart();
					//Crear Rol
					$obj["companyID"]		= $dataSession["user"]->companyID;					
					$obj["branchID"] 		= $dataSession["user"]->branchID;					
					$obj["name"] 			= /*inicio get post*/ $this->request->getPost("txtName");
					$obj["description"] 	= /*inicio get post*/ $this->request->getPost("txtDescription");
					$obj["urlDefault"] 		= /*inicio get post*/ $this->request->getPost("txtUrlDefault");
					$obj["isAdmin"] 		= false;
					$obj["isActive"] 		= true;		
					$obj["createdBy"] 		= $dataSession["user"]->userID;
					$roleID 				= $this->Role_Model->insert_app_posme($obj);
					$companyID 				= $obj["companyID"];
					$branchID 				= $obj["branchID"];
					 
					//Eliminar Elementos
					$this->User_Permission_Model->delete_ByRole($companyID,$branchID,$roleID);
					
					//Insert Elementos 
					$elementIDArray			= /*inicio get post*/ $this->request->getPost("txtElementID");
					$txtSelectedIDArray		= /*inicio get post*/ $this->request->getPost("txtSelectedID");
					$txtInsertedIDArray		= /*inicio get post*/ $this->request->getPost("txtInsertedID");
					$txtDeletedIDArray		= /*inicio get post*/ $this->request->getPost("txtDeletedID");
					$txtEditedIDArray		= /*inicio get post*/ $this->request->getPost("txtEditedID");
					if($elementIDArray)
					foreach($elementIDArray as $key => $value){
							$objUserPermission["companyID"]		= $companyID;
							$objUserPermission["branchID"]		= $branchID;
							$objUserPermission["roleID"]		= $roleID;
							$objUserPermission["elementID"]		= $value;							
							$objUserPermission["selected"]		= $txtSelectedIDArray[$value];
							$objUserPermission["inserted"]		= $txtInsertedIDArray[$value];
							$objUserPermission["deleted"]		= $txtDeletedIDArray[$value];
							$objUserPermission["edited"]		= $txtEditedIDArray[$value];
							$this->User_Permission_Model->insert_app_posme($objUserPermission);
					} 
					
					//Insertar Autorizaciones
					$listsComponentAutorizationID = /*inicio get post*/ $this->request->getPost("txtComponentAutorizationID");
					if($listsComponentAutorizationID)
					foreach($listsComponentAutorizationID as $key => $value){
						$data["componentAutorizationID"] 	= $value;
						$data["companyID"] 					= $companyID;
						$data["roleID"] 					= $roleID;
						$data["branchID"] 					= $branchID;
						$this->Role_Autorization_Model->insert_app_posme($data);
					} 
					 
					if($db->transStatus() !== false){
						$db->transCommit();						
						$this->core_web_notification->set_message(false,SUCCESS);
						$this->response->redirect(base_url()."/".'core_role/edit/companyID/'.$companyID."/branchID/".$branchID."/roleID/".$roleID);						
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
						$this->response->redirect(base_url()."/".'core_role/add');	
					}				
					 
			} 
			//Editar Registro
			else if( $this->validation->withRequest($this->request)->run() == true) {
					
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
										
					//PERMISO SOBRE EL REGISTRO
					$objOld = $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
			
					$db=db_connect();
			$db->transStart();
					//Actualizar Rol
					$obj["name"] 			= /*inicio get post*/ $this->request->getPost("txtName");
					$obj["description"] 	= /*inicio get post*/ $this->request->getPost("txtDescription");
					$obj["urlDefault"] 		= /*inicio get post*/ $this->request->getPost("txtUrlDefault");
					$obj["isAdmin"] 		= false;
					$obj["isActive"] 		= true;
					$result 				= $this->Role_Model->update_app_posme($companyID,$branchID,$roleID,$obj);
					
					//Eliminar Elementos
					$this->User_Permission_Model->delete_ByRole($companyID,$branchID,$roleID);
					
					//Insert Elementos  
					$elementIDArray			= /*inicio get post*/ $this->request->getPost("txtElementID");
					$txtSelectedIDArray		= /*inicio get post*/ $this->request->getPost("txtSelectedID");
					$txtInsertedIDArray		= /*inicio get post*/ $this->request->getPost("txtInsertedID");
					$txtDeletedIDArray		= /*inicio get post*/ $this->request->getPost("txtDeletedID");
					$txtEditedIDArray		= /*inicio get post*/ $this->request->getPost("txtEditedID");
					if($elementIDArray)
					foreach($elementIDArray as $key => $value){
							$objUserPermission["companyID"]		= $companyID;
							$objUserPermission["branchID"]		= $branchID;
							$objUserPermission["roleID"]		= $roleID;
							$objUserPermission["elementID"]		= $value;							
							$objUserPermission["selected"]		= $txtSelectedIDArray[$value];
							$objUserPermission["inserted"]		= $txtInsertedIDArray[$value];
							$objUserPermission["deleted"]		= $txtDeletedIDArray[$value];
							$objUserPermission["edited"]		= $txtEditedIDArray[$value];
							$this->User_Permission_Model->insert_app_posme($objUserPermission);
					}
					
					//Limpiar tablas
					$this->Role_Autorization_Model->deleteByRole($companyID,$branchID,$roleID);
					
					
					//Insertar Autorizaciones
					$listsComponentAutorizationID = /*inicio get post*/ $this->request->getPost("txtComponentAutorizationID");
					if($listsComponentAutorizationID)
					foreach($listsComponentAutorizationID as $key => $value){
						$data["componentAutorizationID"] 	= $value;
						$data["companyID"] 					= $companyID;
						$data["roleID"] 					= $roleID;
						$data["branchID"] 					= $branchID;
						$this->Role_Autorization_Model->insert_app_posme($data);
					} 
					
					
					if($db->transStatus() !== false){
						$db->transCommit();
						$this->core_web_notification->set_message(false,SUCCESS);
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
					}
					$this->response->redirect(base_url()."/".'core_role/edit/companyID/'.$companyID."/branchID/".$branchID."/roleID/".$roleID);
			}  
			else{
				$this->core_web_notification->set_message(true,$this->validation->getErrors());
				$this->response->redirect(base_url()."/".'core_role/add');	
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
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			 			
			
			
			
			//Redireccionar datos
			
			$companyID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$branchID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri
			$roleID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"roleID");//--finuri
			if((!$companyID || !$branchID ||  !$roleID))
			{ 
				$this->response->redirect(base_url()."/".'core_role/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objRole"]	 					= $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
			//Obtener los Permisos
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('core_role/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_role/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_role/edit_script',$datView);//--finview
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
    function add(){
	
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LAS FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			 
			}	  
			 
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('core_role/news_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_role/news_body');//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_role/news_script');//--finview
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
			
			
			//Obtener el componente Para mostrar la lista de Roles
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_role");
			if(!$objComponent)
			throw new \Exception("00384 EL COMPONENTE 'tb_role' NO EXISTE ...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('core_role/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('core_role/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('core_role/list_script');//--finview
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
	//POPUP
	function add_subelement_autorization(){
			//Cargar Libreria
			
			
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
			
			//Obtener la lista de elementos			
			$listComponentAutorization			= $this->Component_Autorization_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$data["listComponentAutorization"] 	= $listComponentAutorization;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('core_role/popup_add_autorization_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('core_role/popup_add_autorization_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('core_role/popup_add_autorization_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_subelement(){
			//Cargar Libreria
			
			
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
			
			//Obtener la lista de elementos			
			$listMenuElement			= $this->Menu_Element_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$data["listMenuElement"] 	= $listMenuElement;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('core_role/popup_add_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('core_role/popup_add_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('core_role/popup_add_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r			
			
	}
	
} 
?>