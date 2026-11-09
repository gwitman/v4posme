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
						
			

			//Solo un rol en sesion administrador o administrador de compania puede cambiar isAdminCompany
			$canEditAdminCompany	= $this->canEditAdminCompany($dataSession);
			$isAdminCompanyPost		= (/*inicio get post*/ $this->request->getPost("txtIsAdminCompany") == "1") ? 1 : 0;

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

					//Permisos enviados en JSON, filtrados a los elementos que la pantalla puede editar
					$listElementIDEditable	= $this->getListElementIDEditable($dataSession["user"]->companyID);
					$objPermissionPost		= $this->getPermissionFromPost($listElementIDEditable);
					$listPermission			= $objPermissionPost["elements"];

					$db=db_connect();
					$db->transStart();
					//Crear Rol
					$obj["companyID"]		= $dataSession["user"]->companyID;
					$obj["branchID"] 		= $dataSession["user"]->branchID;
					$obj["name"] 			= /*inicio get post*/ $this->request->getPost("txtName");
					$obj["description"] 	= /*inicio get post*/ $this->request->getPost("txtDescription");
					$obj["urlDefault"] 		= /*inicio get post*/ $this->request->getPost("txtUrlDefault");
					$obj["isAdmin"] 		= false;
					$obj["isAdminCompany"] 	= $canEditAdminCompany ? $isAdminCompanyPost : 0;
					$obj["isActive"] 		= true;
					$obj["createdBy"] 		= $dataSession["user"]->userID;
					$obj["typeApp"] 		= /*inicio get post*/ $this->request->getPost("txtTypeApp");
					$roleID 				= $this->Role_Model->insert_app_posme($obj);
					$companyID 				= $obj["companyID"];
					$branchID 				= $obj["branchID"];

					//Eliminar Elementos
					$this->User_Permission_Model->delete_ByRole($companyID,$branchID,$roleID);

					//Insert Elementos
					foreach($listPermission as $elementID => $nivel){
							$objUserPermission["companyID"]		= $companyID;
							$objUserPermission["branchID"]		= $branchID;
							$objUserPermission["roleID"]		= $roleID;
							$objUserPermission["elementID"]		= $elementID;
							$objUserPermission["selected"]		= $nivel["selected"];
							$objUserPermission["inserted"]		= $nivel["inserted"];
							$objUserPermission["deleted"]		= $nivel["deleted"];
							$objUserPermission["edited"]		= $nivel["edited"];
							$this->User_Permission_Model->insert_app_posme($objUserPermission);
					}
					
					//Insertar Autorizaciones
					foreach($objPermissionPost["autorizations"] as $value){
						$data["componentAutorizationID"] 	= $value;
						$data["companyID"] 					= $companyID;
						$data["roleID"] 					= $roleID;
						$data["branchID"] 					= $branchID;
						$this->Role_Autorization_Model->insert_app_posme($data);
					}

					//transComplete hace commit o rollback segun el estado de la transaccion
					$db->transComplete();
					if($db->transStatus() !== false){
						$this->core_web_notification->set_message(false,SUCCESS);
						$this->response->redirect(base_url()."/".'core_role/edit/companyID/'.$companyID."/branchID/".$branchID."/roleID/".$roleID);
					}
					else{
						$this->core_web_notification->set_message(true,$db->error()["message"]);
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
					//El rol debe ser de la empresa de la sesion
					if ($companyID != $dataSession["user"]->companyID)
					throw new \Exception(NOT_EDIT);

					$objOld = $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
					if (!$objOld)
					throw new \Exception(NOT_EDIT);

					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);

					//Permisos enviados en JSON, filtrados a los elementos que la pantalla puede editar (C2)
					$listElementIDEditable	= $this->getListElementIDEditable($dataSession["user"]->companyID);
					$objPermissionPost		= $this->getPermissionFromPost($listElementIDEditable);
					$listPermission			= $objPermissionPost["elements"];

					$db=db_connect();
					$db->transStart();
					//Actualizar Rol
					$obj["name"] 			= /*inicio get post*/ $this->request->getPost("txtName");
					$obj["description"] 	= /*inicio get post*/ $this->request->getPost("txtDescription");
					$obj["urlDefault"] 		= /*inicio get post*/ $this->request->getPost("txtUrlDefault");
					$obj["typeApp"] 		= /*inicio get post*/ $this->request->getPost("txtTypeApp");
					$obj["isAdmin"] 		= $objOld->isAdmin;	//conservar: antes se forzaba false y el rol perdia el administrador
					$obj["isAdminCompany"] 	= $canEditAdminCompany ? $isAdminCompanyPost : (isset($objOld->isAdminCompany) ? intval($objOld->isAdminCompany) : 0);
					$obj["isActive"] 		= true;
					$result 				= $this->Role_Model->update_app_posme($companyID,$branchID,$roleID,$obj);

					//Eliminar Elementos: solo los que la pantalla puede editar; los demas permisos del rol quedan intactos
					if(!empty($listElementIDEditable))
					$this->User_Permission_Model->delete_ByRoleAndElements($companyID,$branchID,$roleID,array_keys($listElementIDEditable));

					//Insert Elementos
					foreach($listPermission as $elementID => $nivel){
							$objUserPermission["companyID"]		= $companyID;
							$objUserPermission["branchID"]		= $branchID;
							$objUserPermission["roleID"]		= $roleID;
							$objUserPermission["elementID"]		= $elementID;
							$objUserPermission["selected"]		= $nivel["selected"];
							$objUserPermission["inserted"]		= $nivel["inserted"];
							$objUserPermission["deleted"]		= $nivel["deleted"];
							$objUserPermission["edited"]		= $nivel["edited"];
							$this->User_Permission_Model->insert_app_posme($objUserPermission);
					}
					
					//Limpiar tablas
					$this->Role_Autorization_Model->deleteByRole($companyID,$branchID,$roleID);
					
					
					//Insertar Autorizaciones
					foreach($objPermissionPost["autorizations"] as $value){
						$data["componentAutorizationID"] 	= $value;
						$data["companyID"] 					= $companyID;
						$data["roleID"] 					= $roleID;
						$data["branchID"] 					= $branchID;
						$this->Role_Autorization_Model->insert_app_posme($data);
					}

					//transComplete hace commit o rollback segun el estado de la transaccion
					$db->transComplete();
					if($db->transStatus() !== false){
						$this->core_web_notification->set_message(false,SUCCESS);
					}
					else{
						$this->core_web_notification->set_message(true,$db->error()["message"]);
					}
					$this->response->redirect(base_url()."/".'core_role/edit/companyID/'.$companyID."/branchID/".$branchID."/roleID/".$roleID);
			}
			else{
				$this->core_web_notification->set_message(true,$this->validation->getErrors());
				//Si se estaba editando un rol se vuelve a su pantalla de edicion
				if($companyID && $branchID && $roleID)
				$this->response->redirect(base_url()."/".'core_role/edit/companyID/'.$companyID."/branchID/".$branchID."/roleID/".$roleID);
				else
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
				return redirect()->to(base_url()."/".'core_role/add');
			}


			//Obtener el Registro
			$datView["objRole"]	 					= $this->Role_Model->get_rowByPK($companyID,$branchID,$roleID);
			//Obtener los Permisos
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			//Matriz de permisos: todas las pantallas y todas las autorizaciones de la empresa
			$datView["listMenuElement"]				= $this->Menu_Element_Model->get_rowByCompanyIDForRole($dataSession["user"]->companyID);
			$datView["listComponentAutorizationAll"]= $this->Component_Autorization_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$datView["isOwnRole"]					= ($dataSession["role"]->roleID == $roleID);
			$datView["canEditAdminCompany"]			= $this->canEditAdminCompany($dataSession);

			
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
			 
			//Matriz de permisos: todas las pantallas y todas las autorizaciones de la empresa
			$datView["listMenuElement"]				= $this->Menu_Element_Model->get_rowByCompanyIDForRole($dataSession["user"]->companyID);
			$datView["listComponentAutorizationAll"]= $this->Component_Autorization_Model->get_rowByCompanyID($dataSession["user"]->companyID);
			$datView["canEditAdminCompany"]			= $this->canEditAdminCompany($dataSession);

			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('core_role/news_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_role/news_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_role/news_script',$datView);//--finview
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
			}
			//Otra vista
			else{
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter);
			}

			//En telefono la lista se dibuja como en abonos (app_box_share)
			if( $this->request->getUserAgent()->isMobile() )
			$dataViewRender				= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
			else
			$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			 
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
			$listMenuElement			= $this->Menu_Element_Model->get_rowByCompanyID($dataSession["user"]->companyID,$dataSession["role"]->typeApp);
			$data["listMenuElement"] 	= $listMenuElement;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('core_role/popup_add_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('core_role/popup_add_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('core_role/popup_add_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r

	}

	/**
	 * Indica si el usuario en sesion puede marcar o desmarcar el campo isAdminCompany de un rol.
	 * Solo puede hacerlo si su rol en sesion es administrador (isAdmin = 1) o administrador de compania (isAdminCompany = 1).
	 *
	 * @param array $dataSession Datos de la sesion actual (debe traer "role").
	 * @return bool true si puede editar isAdminCompany.
	 */
	protected function canEditAdminCompany($dataSession)
	{
		return (
			$dataSession["role"]->isAdmin == 1 ||
			(isset($dataSession["role"]->isAdminCompany) && $dataSession["role"]->isAdminCompany == 1)
		);
	}

	/**
	 * Obtiene los elementID que la pantalla de roles puede editar para la empresa.
	 * Usa la misma fuente que alimenta la matriz de permisos en add/edit (Menu_Element_Model->get_rowByCompanyIDForRole),
	 * de modo que el guardado solo borra e inserta permisos de elementos visibles en la pantalla.
	 *
	 * @param int $companyID Empresa de la sesion.
	 * @return array Arreglo asociativo elementID => true.
	 */
	protected function getListElementIDEditable($companyID)
	{
		$listElementIDEditable	= array();
		$listMenuElement		= $this->Menu_Element_Model->get_rowByCompanyIDForRole($companyID);
		if($listMenuElement)
		foreach($listMenuElement as $objMenuElement){
			$elementID = intval($objMenuElement->elementID);
			if($elementID > 0)
			$listElementIDEditable[$elementID] = true;
		}
		return $listElementIDEditable;
	}

	/**
	 * Lee y valida los permisos enviados en el POST "txtPermissionJson" (un solo campo para no superar max_input_vars).
	 * Formato esperado: {"elements":[{"elementID":12,"selected":0,"inserted":-1,"deleted":-1,"edited":2},...],"autorizations":[5,9,...]}
	 * Reglas: un solo registro por elementID (se conserva el primero), solo elementos de la lista editable,
	 * niveles validos -1,0,1,2 (cualquier otro valor o un nivel ausente se guarda como PERMISSION_NONE),
	 * autorizaciones enteras positivas y sin repetir.
	 *
	 * @param array $listElementIDEditable Arreglo elementID => true con los elementos que la pantalla puede editar.
	 * @return array ["elements" => [elementID => ["selected","inserted","deleted","edited"]], "autorizations" => [componentAutorizationID,...]]
	 * @throws \Exception Si el JSON no es valido o no trae la lista "elements"; en ese caso no se guarda nada.
	 */
	protected function getPermissionFromPost($listElementIDEditable)
	{
		$txtPermissionJson	= /*inicio get post*/ $this->request->getPost("txtPermissionJson");
		$objPermission		= is_string($txtPermissionJson) ? json_decode($txtPermissionJson,true) : null;
		if(!is_array($objPermission) || !array_key_exists("elements",$objPermission) || !is_array($objPermission["elements"]))
		throw new \Exception("Los permisos enviados no son validos (txtPermissionJson). No se guardo ningun cambio, recargue la pantalla e intente de nuevo.");

		$listNivelValido	= array(PERMISSION_NONE,PERMISSION_ALL,PERMISSION_BRANCH,PERMISSION_ME);
		$listPermission		= array();
		foreach($objPermission["elements"] as $objElement){
			if(!is_array($objElement) || !isset($objElement["elementID"]))
			continue;

			$elementID = intval($objElement["elementID"]);
			if($elementID <= 0 || array_key_exists($elementID,$listPermission) || !array_key_exists($elementID,$listElementIDEditable))
			continue;

			$nivel = array();
			foreach(array("selected","inserted","deleted","edited") as $flag){
				//Las constantes PERMISSION_* son string ('-1','0','1','2'): normalizar a string para que la comparacion estricta funcione
				$valueFlag		= (isset($objElement[$flag]) && is_numeric($objElement[$flag])) ? strval(intval($objElement[$flag])) : PERMISSION_NONE;
				$nivel[$flag]	= in_array($valueFlag,$listNivelValido,true) ? $valueFlag : PERMISSION_NONE;
			}
			$listPermission[$elementID] = $nivel;
		}

		$listAutorization = array();
		if(isset($objPermission["autorizations"]) && is_array($objPermission["autorizations"]))
		foreach($objPermission["autorizations"] as $value){
			$componentAutorizationID = is_numeric($value) ? intval($value) : 0;
			if($componentAutorizationID > 0 && !in_array($componentAutorizationID,$listAutorization,true))
			$listAutorization[] = $componentAutorizationID;
		}

		return array(
			"elements"		=> $listPermission,
			"autorizations"	=> $listAutorization
		);
	}

}
?>