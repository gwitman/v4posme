<?php
//posme:2023-02-27
namespace App\Controllers;
class app_config_noti extends _BaseController {
	
   
	function edit(){ 
		 try{ 
			//AUTENTICADO		
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCITON
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
			
			$rememberID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"rememberID");//--finuri
			$companyID		= $dataSession["user"]->companyID;
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$rememberID))
			{ 
				$this->response->redirect(base_url()."/".'app_config_noti/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objRemember"]					= $this->Remember_Model->get_rowByPK($rememberID);			
			$datView["objListPeriod"]				= $this->core_web_catalog->getCatalogAllItem("tb_remember","period",$companyID);
			$datView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_remember","statusID",$datView["objRemember"]->statusID,$companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_config_noti/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_config_noti/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_config_noti/edit_script',$datView);//--finview
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
			$rememberID 		= /*inicio get post*/ $this->request->getPost("rememberID");				
			
			if((!$rememberID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 			
		
			//Eliminar el Registro
			$this->Remember_Model->delete_app_posme($rememberID);
					
			
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
	
	function save($method = NULL){
		 $method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////	
			  			
			//Validar Formulario						
			$this->validation->setRule("txtTitulo","title","required");    
			
			 
			//Nuevo Registro			
			$continue	= true;
			if( $method == "new" && $this->validation->withRequest($this->request)->run() == true ){
					
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
					
					//Ingresar Currency
					if($continue){
						$db=db_connect();
			$db->transStart();
						//Crear Cuenta
						$obj["companyID"]					= $dataSession["user"]->companyID;
						$obj["title"] 						= /*inicio get post*/ $this->request->getPost("txtTitulo");
						$obj["period"] 						= /*inicio get post*/ $this->request->getPost("txtPeriodID");
						$obj["day"] 						= /*inicio get post*/ $this->request->getPost("txtDias");
						$obj["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$obj["isTemporal"] 					= /*inicio get post*/ $this->request->getPost("txtIsTemporal");
						$obj["description"] 				= /*inicio get post*/ $this->request->getPost("txtDescripcion");
						$obj["lastNotificationOn"]			= date('Y-m-d');
						$obj["isActive"]					= 1;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
						$rememberID							= $this->Remember_Model->insert_app_posme($obj);
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_config_noti/edit/rememberID/'.$rememberID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_config_noti/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_config_noti/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->validation->withRequest($this->request)->run() == true) {
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
					 
					
					
					if($continue){
						$db=db_connect();
			$db->transStart();
						
						//Actualizar Rol
						$rememberID							= /*inicio get post*/ $this->request->getPost("txtRememberID");
						$obj["title"] 						= /*inicio get post*/ $this->request->getPost("txtTitulo");
						$obj["period"] 						= /*inicio get post*/ $this->request->getPost("txtPeriodID");
						$obj["day"] 						= /*inicio get post*/ $this->request->getPost("txtDias");
						$obj["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$obj["isTemporal"] 					= /*inicio get post*/ $this->request->getPost("txtIsTemporal");
						$obj["description"] 				= /*inicio get post*/ $this->request->getPost("txtDescripcion");
						$result 			= $this->Remember_Model->update_app_posme($rememberID,$obj);						
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_config_noti/edit/rememberID/'.$rememberID);
					}					
					else{
						$this->response->redirect(base_url()."/".'app_config_noti/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_config_noti/add');	
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
			
			//Obtener el componente Para mostrar la lista de CompanyCurrency
			$objComponent					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_remember");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_remember' NO EXISTE...");
			$dataView["component"]			= $objComponent;
			
			//Renderizar Resultado 
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			$dataView["objListPeriod"]			= $this->core_web_catalog->getCatalogAllItem("tb_remember","period",$companyID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_remember","statusID",$companyID,$branchID,$roleID);
			
			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				= $this->core_web_notification->get_message();
			$dataSession["head"]				= /*--inicio view*/ view('app_config_noti/news_head',$dataView);//--finview
			$dataSession["body"]				= /*--inicio view*/ view('app_config_noti/news_body',$dataView);//--finview
			$dataSession["script"]				= /*--inicio view*/ view('app_config_noti/news_script',$dataView);//--finview
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
			
			
			//Obtener el componente Para mostrar la lista de CompanyCurrency
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_remember");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_remember' NO EXISTE...");
			
			
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
			$dataView["componentID"]		= $objComponent->componentID;
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_config_noti/list_head',$dataView);//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_config_noti/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_config_noti/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>