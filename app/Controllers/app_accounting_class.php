<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_class extends _BaseController {
	

	
	public function isValidAccountNumber($accountNumber="",$companyID="",$accountLevelID=""){
		//false numero incorrecto
		//true 	numero correcto
		$objAccountLevel = $this->Account_Level_Model->get_rowByPK($companyID,$accountLevelID);
		
		//Validar Longitud Total
		if($objAccountLevel->lengthTotal != strlen($accountNumber) )
		return false;		
			
		//Validar Longitud de Grupo
		if($objAccountLevel->split){
			$partNumber = explode($objAccountLevel->split,$accountNumber);
			$count 		= count($partNumber) -1;
			if($objAccountLevel->lengthGroup != strlen($partNumber[$count]))
			return false;
		}
				
		return true;
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
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$classID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"classID");//--finuri
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$classID))
			{ 
				$this->response->redirect(base_url()."/".'app_accounting_class/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objClass"]	 				= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);
			$datView["objListAccountLevel"]	 		= $this->Account_Level_Model->getByCompany($companyID);
			
			if($datView["objClass"]->parentClassID != null)
			$datView["objParentClass"]				= $this->Center_Cost_Model->get_rowByPK($companyID,$datView["objClass"]->parentClassID);
			
			
			
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_class/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_class/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_class/edit_script',$datView);//--finview
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
			$classID 			= /*inicio get post*/ $this->request->getPost("classID");				
			
			if((!$companyID && !$classID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			$obj 			= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			//Eliminar el Registro
			$this->Center_Cost_Model->delete_app_posme($companyID,$classID);
					
			
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
			$this->validation->setRule("txtNumber","Number","required");
			$this->validation->setRule("txtAccountLevelID","Class","required");
			$this->validation->setRule("txtDescription","Name","required");
			
			 
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
						//Buscar Padre
						$objParentClass = NULL;
						if(/*inicio get post*/ $this->request->getPost("txtParentClassNumber")){
							$objParentClass		= $this->Center_Cost_Model->getByClassNumber(/*inicio get post*/ $this->request->getPost("txtParentClassNumber"),$dataSession["user"]->companyID);
						}
						
						//Buscar si Existe Cuenta
						$objClass					= $this->Center_Cost_Model->getByClassNumber(/*inicio get post*/ $this->request->getPost("txtNumber"),$dataSession["user"]->companyID);
						if($objClass){
							$continue 				= false;
							throw new \Exception("EL CODIGO DEL CENTRO DE COSTO YA ESTA RESERVADO...");
						}
						//Validar Codigo	
						if(!$this->isValidAccountNumber(/*inicio get post*/ $this->request->getPost("txtNumber"),$dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtAccountLevelID"))){
							$continue 				= false;
							throw new \Exception("EL CODIGO DEL CENTRO DE COSTO TIENE UN FORMATO INCORRECTO");
						}
						
						//Crear Centro de Costo
						$obj["companyID"]			= $dataSession["user"]->companyID;						
						$obj["accountLevelID"] 		= /*inicio get post*/ $this->request->getPost("txtAccountLevelID");
						$obj["parentClassID"] 		= $objParentClass ? $objParentClass->classID : NULL;
						$obj["number"] 				= /*inicio get post*/ $this->request->getPost("txtNumber");							
						$obj["description"] 		= /*inicio get post*/ $this->request->getPost("txtDescription");												
						$obj["isActive"] 			= true;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
						
						$classID				= $this->Center_Cost_Model->insert_app_posme($obj);
						$companyID 				= $obj["companyID"];
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_accounting_class/edit/companyID/'.$companyID."/classID/".$classID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_accounting_class/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_accounting_class/add');	
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
					$companyID			= $dataSession["user"]->companyID;
					$classID 			= /*inicio get post*/ $this->request->getPost("txtClassID");
					$objOld 			= $this->Center_Cost_Model->get_rowByPK($companyID,$classID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
			
					if($continue){
						$db=db_connect();
						$db->transStart();					
						
						//Buscar Padre
						$objParentClass 	= NULL;
						$classID 			= /*inicio get post*/ $this->request->getPost("txtClassID");
						if(/*inicio get post*/ $this->request->getPost("txtParentClassNumber")){
							$objParentClass		= $this->Center_Cost_Model->getByClassNumber(/*inicio get post*/ $this->request->getPost("txtParentClassNumber"),$dataSession["user"]->companyID);
						}
						
						//Buscar si Existe Cuenta
						$objClass					= $this->Center_Cost_Model->getByClassNumber(/*inicio get post*/ $this->request->getPost("txtNumber"),$dataSession["user"]->companyID);
						if($objClass){
							if($objClass->classID != $classID){
								$continue 				= false;
								throw new \Exception("EL CODIGO DEL CENTRO DE COSTO YA ESTA RESERVADO...");
							}
						}
						//Validar Codigo	
						if(!$this->isValidAccountNumber(/*inicio get post*/ $this->request->getPost("txtNumber"),$dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtAccountLevelID"))){
							$continue 				= false;
							throw new \Exception("EL CODIGO DEL CENTRO DE COSTO TIENE UN FORMATO INCORRECTO");
						}						
						
						//Crear Cuenta
						$companyID					= $dataSession["user"]->companyID;
						$classID 					= /*inicio get post*/ $this->request->getPost("txtClassID");						
						$obj["accountLevelID"] 		= /*inicio get post*/ $this->request->getPost("txtAccountLevelID");
						$obj["parentAccountID"] 	= $objParentClass ? $objParentClass->classID : NULL;						
						$obj["number"] 				= /*inicio get post*/ $this->request->getPost("txtNumber");					 
						$obj["description"] 		= /*inicio get post*/ $this->request->getPost("txtDescription");
						$result 					= $this->Center_Cost_Model->update_app_posme($companyID,$classID,$obj);						
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_accounting_class/edit/companyID/'.$companyID."/classID/".$classID);
						
					}					
					else{
						$this->response->redirect(base_url()."/".'app_accounting_class/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_class/add');	
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
			
			}
			 
			  			
			$data["objListAccountLevel"] 	= $this->Account_Level_Model->getByCompany($dataSession["user"]->companyID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_class/news_head',$data);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_class/news_body',$data);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_class/news_script',$data);//--finview
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
			
			//Obtener el componente Para mostrar la lista de CenterCost
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_center_cost");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_center_cost' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_account_class/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_account_class/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_account_class/list_script');//--finview
			$dataSession["script"] 			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
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