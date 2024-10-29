<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_type extends _BaseController {
	
    
    public function field_naturaleza_check($str ="" ){
    	if (!($str == 'D' || $str == "C"))
		{
			$this->form_validation->set_message('field_naturaleza_check', 'The %s Son permitidos los Valores C|D');
			return FALSE;
		}
    } 
	function edit(){ 
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
			
						
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$accountTypeID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"accountTypeID");//--finuri
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$accountTypeID))
			{ 
				$this->response->redirect(base_url()."/".'app_accounting_type/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objAccountType"]	 			= $this->Account_Type_Model->get_rowByPK($companyID,$accountTypeID);
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_type/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_type/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_type/edit_script',$datView);//--finview
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
			$companyID 		= /*inicio get post*/ $this->request->getPost("companyID");
			$accountTypeID 	= /*inicio get post*/ $this->request->getPost("accountTypeID");				
			
			if((!$companyID && !$accountTypeID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			$obj 			= $this->Account_Type_Model->get_rowByPK($companyID,$accountTypeID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			//VALIDAR SI EL REGISTRO NO ESTA SIENDO USADO EN UNA CUENTA DE LA EMPREA
			$resultTemp = $this->Account_Type_Model->get_countInAccount($companyID,$accountTypeID);
			if($resultTemp > 0 ){
			throw new \Exception("EL REGISTRO ESTA EN USO, NO PUEDE SER ELIMINADO");
			}
			
			//Eliminar el Registro
			$this->Account_Type_Model->delete_app_posme($companyID,$accountTypeID);
					
			
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
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			 			  
					
			
			//Validar Formulario						
			$this->validation->setRule("txtName","Nombre","required");    
			$this->validation->setRule("txtNaturaleza","Naturaleza","callback_field_naturaleza_check");
			
			 
			//Nuevo Registro			
			$continue	= true;
			if( $method == "new" && $this->validation->withRequest($this->request)->run() == true ){
					
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
					
					}					
					
					//Ingresar Cuenta
					if($continue){
						$db=db_connect();
			$db->transStart();
						//Crear Cuenta
						$obj["companyID"]			= $dataSession["user"]->companyID;
						$obj["name"] 				= /*inicio get post*/ $this->request->getPost("txtName");
						$obj["naturaleza"] 			= /*inicio get post*/ $this->request->getPost("txtNaturaleza");
						$obj["description"] 		= /*inicio get post*/ $this->request->getPost("txtDescription");				 
						$obj["isActive"] 			= true;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
						
						$accountTypeID				= $this->Account_Type_Model->insert_app_posme($obj);
						$companyID 					= $obj["companyID"];
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_accounting_type/edit/companyID/'.$companyID."/accountTypeID/".$accountTypeID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_accounting_type/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_accounting_type/add');	
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
					$companyID 			= /*inicio get post*/ $this->request->getPost("txtCompanyID");
					$accountTypeID 		= /*inicio get post*/ $this->request->getPost("txtAccountTypeID");
					$objOld = $this->Account_Type_Model->get_rowByPK($companyID,$accountTypeID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
			
					if($continue){
						$db=db_connect();
			$db->transStart();
						
						//Actualizar Rol
						$companyID 			= /*inicio get post*/ $this->request->getPost("txtCompanyID");
						$accountTypeID 		= /*inicio get post*/ $this->request->getPost("txtAccountTypeID");
						$obj["name"] 		= /*inicio get post*/ $this->request->getPost("txtName");
						$obj["description"] = /*inicio get post*/ $this->request->getPost("txtDescription");
						$obj["naturaleza"] 	= /*inicio get post*/ $this->request->getPost("txtNaturaleza");
						$result 			= $this->Account_Type_Model->update_app_posme($companyID,$accountTypeID,$obj);
					
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_accounting_type/edit/companyID/'.$companyID."/accountTypeID/".$accountTypeID);
					}					
					else{
						$this->response->redirect(base_url()."/".'app_accounting_type/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_type/add');	
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
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_INSERT);			
			
			}	
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_type/news_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_type/news_body');//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_type/news_script');//--finview
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
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account_type");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'AccountType' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_account_type/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_account_type/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_account_type/list_script');//--finview
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