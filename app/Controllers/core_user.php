<?php
//posme:2023-02-27
namespace App\Controllers;
class core_user extends _BaseController {
	
    
	
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
			
			$companyID 	= /*inicio get post*/ $this->request->getPost("companyID");
			$branchID 	= /*inicio get post*/ $this->request->getPost("branchID");
			$userID		= /*inicio get post*/ $this->request->getPost("userID");	
			
			if((!$companyID && !$branchID   && !$userID)){
					throw new \Exception(NOT_PARAMETER);
					 
			} 
			
			$obj 			= $this->User_Model->get_rowByPK($companyID,$branchID,$userID);			
			$obj->isActive	= false;			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			$obj			= (array)$obj;	
			$result 		= $this->User_Model->update_app_posme($companyID,$branchID,$userID,$obj);
					
			
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
			
			
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			  
			  
			
			
			//Validar Formulario						
			$this->validation->setRule("txtNickname","Nickname ","required");    
			$this->validation->setRule("txtPassword","Password ","required");    
			$this->validation->setRule("txtEmail","Email","required");
			$this->validation->setRule("txtRoleID","Rol","required");
						
			
			 
			//Nuevo Registro
			$companyID 	= $dataSession["user"]->companyID;
			$branchID 	= $dataSession["user"]->branchID;
			$userID		= /*inicio get post*/ $this->request->getPost("userID");	
			$continue	= true;
			
			if(!$userID && $this->validation->withRequest($this->request)->run() == true ){
					
					
					
					//Validar si tiene permiso para ver la Pagina WEB					
					if(APP_NEED_AUTHENTICATION == true){
							$permited = false;
							$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
							
							if(!$permited)
							throw new \Exception(NOT_ACCESS_CONTROL);
							
							$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
							if ($resultPermission 	== PERMISSION_NONE)
							throw new \Exception(NOT_ACCESS_FUNCTION);
					}	 
					
					
					//validar nickname
					$objUserTmp = $this->User_Model->get_rowByExistNickname(/*inicio get post*/ $this->request->getPost("txtNickname"));
					if($objUserTmp && $continue )
					{						
						$continue = false;
						$this->core_web_notification->set_message(true,NICKNAME_DUPLI);
					}   
					
					//validar email
					$objUserTmp = $this->User_Model->get_rowByEmail(/*inicio get post*/ $this->request->getPost("txtEmail"));
					if($objUserTmp && $continue )
					{ 	
						$continue = false;
						$this->core_web_notification->set_message(true,EMAIL_DUPLI);				
					} 					
					
					
					
					$this->core_web_permission->getValueLicense($companyID,get_class($this)."/"."index");
					$continue = true;
					
					//Ingresar usuario
					if($continue){
						$db=db_connect();
						$db->transStart();
						//Crear Usuario
						$obj["companyID"]		= $dataSession["user"]->companyID;					
						$obj["branchID"] 		= $dataSession["user"]->branchID;					
						
						$obj["nickname"] 			= /*inicio get post*/ $this->request->getPost("txtNickname");
						$obj["password"] 			= /*inicio get post*/ $this->request->getPost("txtPassword");
						$obj["email"] 				= /*inicio get post*/ $this->request->getPost("txtEmail");					
						$obj["createdOn"]			= date("Y-m-d H:i:s");					
						$obj["createdBy"]			= $dataSession["user"]->userID;
						$obj["isActive"] 			= true;		
						$obj["employeeID"] 			= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
						$userID		 				= $this->User_Model->insert_app_posme($obj);					
						
						
						$companyID 					= $obj["companyID"];
						$branchID 					= $obj["branchID"];			 
						$roleID 					= /*inicio get post*/ $this->request->getPost("txtRoleID");
						
						//Eliminar Membership
						$result 					= $this->Membership_Model->delete_app_posme($companyID,$branchID,$userID);
						 
						//Nuevo Membership
						$objMembership["companyID"] = $companyID;
						$objMembership["branchID"] 	= $branchID;
						$objMembership["userID"] 	= $userID;
						$objMembership["roleID"] 	= $roleID;
						$result 					= $this->Membership_Model->insert_app_posme($objMembership);
						
						//Agrebar Bodegas
						$objListWarehouse 			= /*inicio get post*/ $this->request->getPost("txtDetailWarehouseID");
						$this->Userwarehouse_Model->deleteByUser($companyID,$userID);
						if($objListWarehouse)
						foreach($objListWarehouse as $key => $value){
							$objWarehouse["companyID"] 		= $companyID;
							$objWarehouse["branchID"] 		= $branchID;
							$objWarehouse["userID"] 		= $userID;
							$objWarehouse["warehouseID"] 	= $value;
							$this->Userwarehouse_Model->insert_app_posme($objWarehouse);
						}
						
						//Agregar Notificaciones
						$objListTag					= /*inicio get post*/ $this->request->getPost("txtDetailTagID");
						$this->User_Tag_Model->deleteByUser($userID);
						if($objListTag)
						foreach($objListTag as $key => $value){
							$objTag["companyID"] 		= $companyID;
							$objTag["branchID"] 		= $branchID;
							$objTag["userID"] 			= $userID;
							$objTag["tagID"] 			= $value;
							$this->User_Tag_Model->insert_app_posme($objTag);
						}
						
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'core_user/edit/companyID/'.$companyID."/branchID/".$branchID."/userID/".$userID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'core_user/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'core_user/add');	
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
					$objOld = $this->User_Model->get_rowByPK($companyID,$branchID,$userID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
			
					//validar nickname
					$objUserTmp = $this->User_Model->get_rowByExistNickname(/*inicio get post*/ $this->request->getPost("txtNickname"));
					if($objUserTmp && ($objUserTmp->userID != $userID)  && $continue )
					{						
						$continue = false;
						$this->core_web_notification->set_message(true,NICKNAME_DUPLI);								
					}   
					
					//validar email
					$objUserTmp = $this->User_Model->get_rowByEmail(/*inicio get post*/ $this->request->getPost("txtEmail"));
					if($objUserTmp && ($objUserTmp->userID != $userID)  && $continue )
					{ 	
						$continue = false;
						$this->core_web_notification->set_message(true,EMAIL_DUPLI);				
					} 
						
					if($continue){
						$db=db_connect();
						$db->transStart();
						
						//Actualizar Rol
						$obj["nickname"] 			= /*inicio get post*/ $this->request->getPost("txtNickname");
						$obj["password"] 			= /*inicio get post*/ $this->request->getPost("txtPassword");
						$obj["email"] 				= /*inicio get post*/ $this->request->getPost("txtEmail");
						$obj["employeeID"] 			= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
						$result 					= $this->User_Model->update_app_posme($companyID,$branchID,$userID,$obj);
						$roleID 					= /*inicio get post*/ $this->request->getPost("txtRoleID");
						
						//Eliminar Membership
						$result 					= $this->Membership_Model->delete_app_posme($companyID,$branchID,$userID);
						 
						//Nuevo Membership
						$objMembership["companyID"] = $companyID;
						$objMembership["branchID"] 	= $branchID;
						$objMembership["userID"] 	= $userID;
						$objMembership["roleID"] 	= $roleID;
						$result 					= $this->Membership_Model->insert_app_posme($objMembership);
						
						
						//Agrebar Bodegas
						$objListWarehouse 			= /*inicio get post*/ $this->request->getPost("txtDetailWarehouseID");
						$this->Userwarehouse_Model->deleteByUser($companyID,$userID);
						if($objListWarehouse)
						foreach($objListWarehouse as $key => $value){
							$objWarehouse["companyID"] 		= $companyID;
							$objWarehouse["branchID"] 		= $branchID;
							$objWarehouse["userID"] 		= $userID;
							$objWarehouse["warehouseID"] 	= $value;
							$this->Userwarehouse_Model->insert_app_posme($objWarehouse);
						}
						
						//Agregar Notificaciones
						$objListTag					= /*inicio get post*/ $this->request->getPost("txtDetailTagID");
						$this->User_Tag_Model->deleteByUser($userID);
						if($objListTag)
						foreach($objListTag as $key => $value){
							$objTag["companyID"] 		= $companyID;
							$objTag["branchID"] 		= $branchID;
							$objTag["userID"] 			= $userID;
							$objTag["tagID"] 			= $value;
							$this->User_Tag_Model->insert_app_posme($objTag);
						}
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'core_user/edit/companyID/'.$companyID."/branchID/".$branchID."/userID/".$userID);
					}					
					else{
						$this->response->redirect(base_url()."/".'core_user/add');	
					}
			}  
			else{				
				$stringValidation = str_replace("\n","",$this->validation->getErrors());				
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'core_user/add');	
			} 
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			
			//Load Model
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			 			
						
			
			
			
			
			
				
			
			
			
			//Redireccionar datos
			
			$companyID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$branchID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri
			$userID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userID");//--finuri
			if((!$companyID || !$branchID ||  !$userID))
			{ 
				
				$this->response->redirect(base_url()."/".'core_user/add');	
			} 		
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			
			if(!$objComponentEntity)
			throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");
		
			
			//Obtener el Registro			
			$datView["objUser"]	 				= $this->User_Model->get_rowByPK($companyID,$branchID,$userID);
			//Obtener los Roles
			$datView["objListRoles"]			= $this->Role_Model->get_rowByCompanyIDyBranchID($companyID,$branchID);
			//Obtener el Membership
			$datView["objMembership"]	 		= $this->Membership_Model->get_rowByCompanyIDBranchIDUserID($companyID,$branchID,$userID);
			//Obtener la lista de Bodegas
			$datView["objListWarehouse"] 		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
			//Obtener la lista de Notificaciones
			$datView["objListNotification"]		= $this->User_Tag_Model->get_rowByUser($userID);
			//Obtener el componente
			$datView["objComponentEmployee"]	= $objComponent;
			$datView["objComponentEntity"]		= $objComponentEntity;
			//Obtener Entidad
			$datView["objEntity"]				= $this->Entity_Model->get_rowByEntity($companyID,$datView["objUser"]->employeeID);
			//Obtener Empleado
			$datView["objCustomer"]				= $datView["objEntity"] == null ? null : $this->Customer_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);
			$datView["objEmployee"]				= $datView["objEntity"] == null ? null : $this->Employee_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);			
			
			$datView["objProvider"]				= $datView["objEntity"] == null ? null : $this->Provider_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);			
			$datView["entityNumber"]			= $datView["objCustomer"] != null ? $datView["objCustomer"]->customerNumber :
												  (
													$datView["objEmployee"] != null ? $datView["objEmployee"]->employeNumber : 
														(
															$datView["objProvider"] != null ? $datView["objProvider"]->providerNumber : NULL
														)
												  );
			
			//Obtener El Natural
			$datView["objNatural"]				= $datView["objEntity"] == null ? null : $this->Natural_Model->get_rowByPK($datView["objEntity"]->companyID,$datView["objEntity"]->branchID,$datView["objEntity"]->entityID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('core_user/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_user/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_user/edit_script',$datView);//--finview
			$dataSession["footer"]			= "";				
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
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
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			 
			}
			
			//Load Modelos
			 
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			
			if(!$objComponentEntity)
			throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");
		
		
			
			
			//Obtener los Roles
			$datView["objListRoles"] = $this->Role_Model->get_rowByCompanyIDyBranchID($this->session->get('user')->companyID,$this->session->get('user')->branchID);
			$datView["objEmployee"]  = $objComponent;
			$datView["objEntity"]  	 = $objComponentEntity;
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('core_user/news_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_user/news_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_user/news_script');//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}	
			
    }
	function index($dataViewID = null){ 
		$dataViewID = helper_SegmentsByIndex($this->uri->getSegments(),1,$dataViewID);	
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_user");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_user' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('core_user/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('core_user/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('core_user/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	function add_warehouse(){
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
			$objListWarehouse			= $this->Warehouse_Model->getByCompany($dataSession["user"]->companyID);
			$data["objListWarehouse"] 	= $objListWarehouse;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('core_user/popup_add_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('core_user/popup_add_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('core_user/popup_add_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r			
			
	}
	function add_tag(){
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
			$objListTag					= $this->Tag_Model->get_rows();
			$data["objListTag"] 		= $objListTag;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('core_user/popup_tag_add_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('core_user/popup_tag_add_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('core_user/popup_tag_add_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r			
			
	}
	
}
?>