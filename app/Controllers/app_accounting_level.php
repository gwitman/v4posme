<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_level extends _BaseController {
	
       
	public function number_check($str=""){
		if (!is_numeric($str))
		{
			$this->form_validation->set_message('username_check', 'The %s field is not numeric');
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
			$accountLevelID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"accountLevelID");//--finuri
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$accountLevelID))
			{ 
				$this->response->redirect(base_url()."/".'app_accounting_level/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objAccountLevel"]	 			= $this->Account_Level_Model->get_rowByPK($companyID,$accountLevelID);
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_level/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_level/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_level/edit_script',$datView);//--finview
			$dataSession["footer"]			= "";				
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$companyID 			= /*inicio get post*/ $this->request->getPost("companyID");
			$accountLevelID 	= /*inicio get post*/ $this->request->getPost("accountLevelID");				
			
			if((!$companyID && !$accountLevelID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			$obj 			= $this->Account_Level_Model->get_rowByPK($companyID,$accountLevelID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			//VALIDAR SI EL REGISTRO NO ESTA SIENDO USADO EN UNA CUENTA DE LA EMPREA
			$resultTemp = $this->Account_Level_Model->get_countInAccount($companyID,$accountLevelID);
			if($resultTemp > 0 ){
			throw new \Exception("EL REGISTRO ESTA EN USO, NO PUEDE SER ELIMINADO");
			}
			
			//Eliminar el Registro
			$this->Account_Level_Model->delete_app_posme($companyID,$accountLevelID);
					
			
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
			$this->validation->setRule("txtLengthTotal","Longitud Total","required");
			$this->validation->setRule("txtLengthGroup","Longitud del Grupo","required");
			
			 
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
						$obj["split"] 				= /*inicio get post*/ $this->request->getPost("txtSplit");
						$obj["description"] 		= /*inicio get post*/ $this->request->getPost("txtDescription");
						$obj["lengthTotal"] 		= /*inicio get post*/ $this->request->getPost("txtLengthTotal");	
						$obj["lengthGroup"] 		= /*inicio get post*/ $this->request->getPost("txtLengthGroup");				

						$txtIsOperative				= $this->request->getPost("txtIsOperative");	
						$txtIsOperative				= empty($txtIsOperative) ? 0 : 1;
						
						$obj["isOperative"] 		= $txtIsOperative;
						$obj["isActive"] 			= true;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
						
						$accountLevelID				= $this->Account_Level_Model->insert_app_posme($obj);
						$companyID 					= $obj["companyID"];
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_accounting_level/edit/companyID/'.$companyID."/accountLevelID/".$accountLevelID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_accounting_level/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_accounting_level/add');	
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
					$accountLevelID		= /*inicio get post*/ $this->request->getPost("txtAccountLevelID");
					$objOld 			= $this->Account_Level_Model->get_rowByPK($companyID,$accountLevelID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
					
					
					if($continue){
						$db=db_connect();
						$db->transStart();
						
						//Actualizar Rol
						$companyID 			= /*inicio get post*/ $this->request->getPost("txtCompanyID");
						$accountLevelID		= /*inicio get post*/ $this->request->getPost("txtAccountLevelID");
						$obj["name"] 		= /*inicio get post*/ $this->request->getPost("txtName");
						$obj["description"] = /*inicio get post*/ $this->request->getPost("txtDescription");
						$obj["split"] 		= /*inicio get post*/ $this->request->getPost("txtSplit");
						$obj["lengthGroup"] = /*inicio get post*/ $this->request->getPost("txtLengthGroup");
						$obj["lengthTotal"] = /*inicio get post*/ $this->request->getPost("txtLengthTotal");
						
						$txtIsOperative				= $this->request->getPost("txtIsOperative");	
						$txtIsOperative				= empty($txtIsOperative) ? 0 : 1;
						
						
						$obj["isOperative"] = $txtIsOperative;
						$result 			= $this->Account_Level_Model->update_app_posme($companyID,$accountLevelID,$obj);
					
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_accounting_level/edit/companyID/'.$companyID."/accountLevelID/".$accountLevelID);
					}					
					else{
						$this->response->redirect(base_url()."/".'app_accounting_level/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_level/add');	
			} 
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$dataSession["head"]			= /*--inicio view*/ view('app_account_level/news_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_level/news_body');//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_level/news_script');//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account_level");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_account_level' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_account_level/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_account_level/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_account_level/list_script');//--finview
			$dataSession["script"] 			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>