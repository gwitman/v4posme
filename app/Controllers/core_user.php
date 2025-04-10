<?php
//posme:2023-02-27
namespace App\Controllers;
use CodeIgniter\Files\File;
use App\Libraries\core_web_google;

class core_user extends _BaseController {
	
    
	
	function delete(){
		try{   
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true)
			{
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
			
			if(!$userID && $this->validation->withRequest($this->request)->run() == true )
			{
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
					
					
					$objListComanyParameter		= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
					$maxUser					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_CUST_PRICE_MAX_USER")->value;
					$objListUser				= $this->User_Model->get_All($companyID);
					
					//Ingresar usuario
					if($continue){
						
						//Validar la cantidad de usuarios permitidos
						if($objListUser != null)
						{
							if( count($objListUser) >= $maxUser )
							{
								throw new \Exception("Limite de usuarios superados...");
							}						
						}
						
						
						$db=db_connect();
						$db->transStart();
						//Crear Usuario
						$obj["companyID"]			= $dataSession["user"]->companyID;					
						$obj["branchID"] 			= $dataSession["user"]->branchID;
						$obj["locationID"] 			= /*inicio get post*/ $this->request->getPost("txtBranchID");						
						$obj["nickname"] 			= /*inicio get post*/ $this->request->getPost("txtNickname");
						$obj["password"] 			= /*inicio get post*/ $this->request->getPost("txtPassword");
						$obj["email"] 				= /*inicio get post*/ $this->request->getPost("txtEmail");					
						$obj["useMobile"]			= /*inicio get post*/ $this->request->getPost("txtIsMobile") == null ? 0 : /*inicio get post*/ $this->request->getPost("txtIsMobile");
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
						
						//Agregar la caja
						$cashBoxID = helper_RequestGetValue($this->request->getPost("txtCashBoxID"),0);
						if($cashBoxID > 0)
						{
							$objCashBoxUser 				= NULL;					
							$objCashBoxUser["companyID"] 	= $companyID;
							$objCashBoxUser["branchID"] 	= $branchID;
							$objCashBoxUser["userID"] 		= $userID;
							$objCashBoxUser["cashBoxID"]	= $cashBoxID;
							$objCashBoxUser["typeID"] 		= 0;							
							
							$this->Cash_Box_User_Model->insert($objCashBoxUser);
							
						}
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'core_user/edit/companyID/'.$companyID."/branchID/".$branchID."/userID/".$userID);						
						}
						else{
							$db->transRollback();	
					
							$errorCode 		= $db->error()["code"];
							$errorMessage 	= $db->error()["message"];
					
							$this->core_web_notification->set_message(true,$errorMessage);
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
					$objOld 		= $this->User_Model->get_rowByUserID($userID);					
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
						$obj["locationID"] 			= $this->request->getPost("txtBranchID");
						$obj["password"] 			= /*inicio get post*/ $this->request->getPost("txtPassword");
						$obj["email"] 				= /*inicio get post*/ $this->request->getPost("txtEmail");
						$obj["useMobile"]			= /*inicio get post*/ $this->request->getPost("txtIsMobile");
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
						
						//Modificar Caja
						$cashBoxID = helper_RequestGetValue($this->request->getPost("txtCashBoxID"),0);
						if($cashBoxID > 0)
						{
							$objCashBoxUser 				= NULL;					
							$objCashBoxUser					= $this->Cash_Box_User_Model->asArray()->where("userID",$userID)->findAll();							
							if( $objCashBoxUser )
							{								
								$objCashBoxUser[0]["cashBoxID"]	= $cashBoxID;								
								$this->Cash_Box_User_Model->update($objCashBoxUser[0]["cashBoxUserID"],$objCashBoxUser[0]);
							}
							else 
							{
								$objCashBoxUser["companyID"] 	= $companyID;
								$objCashBoxUser["branchID"] 	= $branchID;
								$objCashBoxUser["userID"] 		= $userID;
								$objCashBoxUser["cashBoxID"]	= $cashBoxID;
								$objCashBoxUser["typeID"] 		= 0;							
								$this->Cash_Box_User_Model->insert($objCashBoxUser);
							}
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
			
			$companyID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$branchID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri
			$userID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userID");//--finuri
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
		
			$objComponentItem					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el Registro			
			$datView["objUser"]	 				= $this->User_Model->get_rowByPK($companyID,$branchID,$userID);
			$datView["objBranch"]				= $this->Branch_Model->getByCompany($companyID);
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
			
			$datView["objComponentItem"]				= $objComponentItem;
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$datView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;

			
			//Lista de cajas
			$datView["objListCash"]	 				= $this->Cash_Box_Model->asObject()->where("companyID",$this->session->get('user')->companyID)->findAll();
			$datView["objListCashUser"]	 			= $this->Cash_Box_User_Model->asObject()->where("companyID",$this->session->get('user')->companyID)->where("userID",$userID )->findAll();
			$datView["cashBoxID"]					= $datView["objListCashUser"] ? $datView["objListCashUser"][0]->cashBoxID : 0;
			
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
			$companyID 							= $dataSession["user"]->companyID;
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			
			if(!$objComponentEntity)
			throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");
		
		
			$objComponentItem					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
			//Obtener los Roles
			$datView["objListRoles"] 		= $this->Role_Model->get_rowByCompanyIDyBranchID($this->session->get('user')->companyID,$this->session->get('user')->branchID);
			$datView["objEmployee"]  		= $objComponent;
			$datView["objEntity"]  	 		= $objComponentEntity;
			$datView["objBranch"]	 		= $this->Branch_Model->getByCompany($this->session->get('user')->companyID);
			$datView["objComponentItem"]	= $objComponentItem;

			//Lista de cajas
			$datView["objListCash"]	 = $this->Cash_Box_Model->asObject()->where("companyID",$this->session->get('user')->companyID)->findAll();
			
			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$datView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;

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
		catch(\Exception $ex)
		{
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
	
	function payment(){
	
		try{ 
			
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			
			if(!$objComponentEntity)
			throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");
		
		
			
			
			//Obtener los Roles			
			$datView["title"] 		 = "Pago";
			$datView["objEmployee"]  = $objComponent;
			$datView["objEntity"]  	 = $objComponentEntity;
			$datView["message"]		 = $this->core_web_notification->get_message_alert();
			
			//Renderizar Resultado 			
			
			$dataSession["head"]			= /*--inicio view*/ view('core_user/payment_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_user/payment_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_user/payment_script',$datView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage_public",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = null;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}	
			
    }
	
	
	function payment_user()
	{
		
		
		//Funcion que genera el pago 
		//Mandar a ejecutar el pago en pagadito
		//AGE					= APLICACIO DE AGENDA
		//EMAI					= EMAIL DEL CLIENTE
		//FECHA					= FECHA DEL PAGO 
		//NUMERO DE FACTURA 	= AGE__EMAIL__FECHA
		//EL RETORNO DE LA APLICACION SE HACE EN LA SIGUIENTE URL 
		//core_user/payment_user_back
		
		if(!$this->request->getPost("txtEmail"))
		{
			$mensaje = "Correo electronico es obligatorio";
			$this->core_web_notification->set_message(true,$mensaje);
			$this->response->redirect(base_url()."/".'core_user/payment');			
		}
		
		$flexSwitchCheckDefault 	= is_null($this->request->getPost("flexSwitchCheckDefault")) ? "false" : $this->request->getPost("flexSwitchCheckDefault");
		$flexSwitchCheckDefault 	= $flexSwitchCheckDefault == "on" ? true : false;
		
		//Obtener Datos
		$pagoCantidadDeMeses		= 1;
		$parameterSendBox 			= $this->core_web_parameter->getParameter("CORE_PAYMENT_SENDBOX",APP_COMPANY);
		$parameterSendBox 			= $parameterSendBox->value;
		$parameterSendBoxUsuario 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_USUARIO",APP_COMPANY);
		$parameterSendBoxUsuario 	= $parameterSendBoxUsuario->value;
		$parameterSendBoxClave 		= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_CLAVE",APP_COMPANY);
		$parameterSendBoxClave 		= $parameterSendBoxClave->value;
		$parameterProduccionUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_USUARIO",APP_COMPANY);
		$parameterProduccionUsuario = $parameterProduccionUsuario->value;
		$parameterProduccionClave 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_CLAVE",APP_COMPANY);
		$parameterProduccionClave 	= $parameterProduccionClave->value;
		$parameterPrice				= $this->core_web_parameter->getParameter("CORE_CUST_PRICE",APP_COMPANY);
		$parameterPrice 			= $parameterPrice->value;
		$parameterTipoPlan 			= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",APP_COMPANY);
		$parameterTipoPlan 			= $parameterTipoPlan->value;
		
		$parameterTemporal1 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL001",APP_COMPANY);
		$parameterTemporal2 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL002",APP_COMPANY);
		$parameterTemporal3 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL003",APP_COMPANY);
		
		$email						= $this->request->getPost("txtEmail");
		$pagoCantidadMonto		    = $pagoCantidadDeMeses * $parameterPrice;
		$fechaNow  				  	= date("YmdHis");
		
		
		$uidt 						= "";
		$wskt 						= "";
		$urlProducto 				= "http://posme.net/product/posme_calendar/";
		$sendBox 					= $parameterSendBox == "true"? true : false;
		$cantidad 					= $pagoCantidadDeMeses;
		$precio  					= $pagoCantidadMonto;
		if($sendBox){
			$uidt = $parameterSendBoxUsuario;
			$wskt = $parameterSendBoxClave;
		}
		else{
			$uidt = $parameterProduccionUsuario;
			$wskt = $parameterProduccionClave;
		}
		
		$nombre 		= "licenciamiento ";
		$numberFactura 	= "AGE__".$email."__".$fechaNow;
		
		/*
		 * Lo primero es crear el objeto nusoap_client, al que se le pasa como
		 * parámetro la URL de Conexión definida en la constante WSPG
		 */
		 
		
		$Pagadito = $this->core_web_pagadito;			
		$Pagadito->Init($uidt,$wskt);	
		
		/*
		 * Si se está realizando pruebas, necesita conectarse con Pagadito SandBox. Para ello llamamos
		 * a la función mode_sandbox_on(). De lo contrario omitir la siguiente linea.
		 */
		if ($sendBox) {
			$Pagadito->mode_sandbox_on();			
		}
		
		
		/*
		 * Validamos la conexión llamando a la función connect(). Retorna
		 * true si la conexión es exitosa. De lo contrario retorna false
		 */
		if ($Pagadito->connect()) 
		{				
			/*
			 * Luego pasamos a agregar los detalles
			 */
			if ($cantidad > 0) {
				$Pagadito->add_detail($cantidad,$nombre, $precio,$urlProducto);
			}
			
			//Agregando campos personalizados de la transacción
			/*
			$Pagadito->set_custom_param("param1", "Valor de param1");
			$Pagadito->set_custom_param("param2", "Valor de param2");
			$Pagadito->set_custom_param("param3", "Valor de param3");
			$Pagadito->set_custom_param("param4", "Valor de param4");
			$Pagadito->set_custom_param("param5", "Valor de param5");
			*/
			//Habilita la recepción de pagos preautorizados para la orden de cobro.
			//$Pagadito->enable_pending_payments();
	
			/*
			 * Lo siguiente es ejecutar la transacción, enviandole el ern.
			 *
			 * A manera de ejemplo el ern es generado como un número
			 * aleatorio entre 1000 y 2000. Lo ideal es que sea una
			 * referencia almacenada por el Pagadito Comercio.
			 */
			//$ern = rand(1000, 2000);
			$ern = $numberFactura;
			if (!$Pagadito->exec_trans($ern)) {
				/*
				 * En caso de fallar la transacción, verificamos el error devuelto.
				 * Debido a que la API nos puede devolver diversos mensajes de
				 * respuesta, validamos el tipo de mensaje que nos devuelve.
				 */
				switch($Pagadito->get_rs_code())
				{
					case "PG2001":
						/*Incomplete data*/
					case "PG3002":
						/*Error*/
					case "PG3003":
						/*Unregistered transaction*/
					case "PG3004":
						/*Match error*/
					case "PG3005":
						/*Disabled connection*/
					default:
						$mensaje = $Pagadito->get_rs_code().": ".$Pagadito->get_rs_message();
						$this->core_web_notification->set_message(true,$mensaje);
						$this->response->redirect(base_url()."/".'core_user/payment');					
						break;
				}
			}
		}
		else 
		{
			
			
			/*
			 * En caso de fallar la conexión, verificamos el error devuelto.
			 * Debido a que la API nos puede devolver diversos mensajes de
			 * respuesta, validamos el tipo de mensaje que nos devuelve.
			 */
			switch($Pagadito->get_rs_code())
			{
				case "PG2001":
					/*Incomplete data*/
				case "PG3001":
					/*Problem connection*/
				case "PG3002":
					/*Error*/
				case "PG3003":
					/*Unregistered transaction*/
				case "PG3005":
					/*Disabled connection*/
				case "PG3006":
					/*Exceeded*/
				default:					
					$mensaje = $Pagadito->get_rs_code().": ".$Pagadito->get_rs_message();
					$this->core_web_notification->set_message(true,$mensaje);
					$this->response->redirect(base_url()."/".'core_user/payment');
					break;
			}
		}
		
	}
	
	function payment_user_back(){
		
		//Funcion que procesa el resultado del pago
		//Orignes de pago 1:	core_user/payment_user
		//Orignes de pago 2:	core_account/payment
		//Orignes de pago 3:	posme.net/ (woocommerce posme)
		//Orignes de pago 4:	app_invoice_api/getLinkPaymentPagadito
		
		//PAGO WOOMCOMERCE								= NUMBER
		//PAGO POSME CALENDAR							= AGE__EMAIL__FECHA
		//PAGO POSME 									= POS__FLAVORID__FECHA 
		//PAGO DE FACTURA DE CLIENTES Y PARA CLIENTES	= FCCLI_FLAVORID_FECHA
		
		
		
		//Obtener Datos
		$parameterSendBox 			= $this->core_web_parameter->getParameter("CORE_PAYMENT_SENDBOX",APP_COMPANY);
		$parameterSendBox 			= $parameterSendBox->value;
		$parameterSendBoxUsuario 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_USUARIO",APP_COMPANY);
		$parameterSendBoxUsuario 	= $parameterSendBoxUsuario->value;
		$parameterSendBoxClave 		= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_CLAVE",APP_COMPANY);
		$parameterSendBoxClave 		= $parameterSendBoxClave->value;
		$parameterProduccionUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_USUARIO",APP_COMPANY);
		$parameterProduccionUsuario = $parameterProduccionUsuario->value;
		$parameterProduccionClave 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_CLAVE",APP_COMPANY);
		$parameterProduccionClave 	= $parameterProduccionClave->value;
		$parameterPrice				= $this->core_web_parameter->getParameter("CORE_CUST_PRICE",APP_COMPANY);
		$parameterPrice 			= $parameterPrice->value;
		$parameterTipoPlan 			= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",APP_COMPANY);
		$parameterTipoPlan 			= $parameterTipoPlan->value;
		$parameterTemporal1 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL001",APP_COMPANY);
		$parameterTemporal2 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL002",APP_COMPANY);
		$parameterTemporal3 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL003",APP_COMPANY);
		
				
		
		$uidt 			= "";
		$wskt 			= "";
		$sendBox 		= $parameterSendBox == "true"? true : false;
		if($sendBox){
			$uidt = $parameterSendBoxUsuario;
			$wskt = $parameterSendBoxClave;
		}
		else{
			$uidt = $parameterProduccionUsuario;
			$wskt = $parameterProduccionClave;
		}
		
		$tocken 	= /*--ini uri*/ $this->request->getGet("parametro1");
		$factura	= /*--ini uri*/ $this->request->getGet("parametro2");
		
		
		$facturaParte				= explode("__",$factura);	
		$facturaParteApp			= "";
		$facturaParteClient			= "";
		$facturaParteConsercutivo	= "";
		
		if(count($facturaParte) >= 1)
		$facturaParteApp			= $facturaParte[0];
	
		if(count($facturaParte) >= 2)
		$facturaParteClient			= $facturaParte[1];
	
		if(count($facturaParte) >= 3)
		$facturaParteConsercutivo	= $facturaParte[2];
	
		$urlRedirect = "";
		//aplicacion de agenda
		if($facturaParteApp == "AGE")
		{
			$urlRedirect = base_url()."/".'core_user/payment';
		}
		//aplicacoin de posme
		else if($facturaParteApp == "POS")
		{
			$urlRedirect = base_url()."/".'core_user/payment';
		}
		else if($facturaParteApp == "FCCLI")
		{
			$urlRedirect = base_url()."/".'app_invoice_billing/add/codigoMesero/none';
		}
		//woocomerce
		else 
		{
			$urlRedirect = APP_URL_WOOCOMERCE .'?wc-api=WC_Gateway_Pagadito&token='.$tocken.'&order_id='.$factura;			
			$this->response->redirect($urlRedirect);
			return;		
		}
		
							
		if ($tocken != "" ) 
		{
			/*
			 * Lo primero es crear el objeto Pagadito, al que se le pasa como
			 * parámetros el UID y el WSK definidos en config.php
			 */
			$Pagadito = $this->core_web_pagadito;
			$Pagadito->Init($uidt, $wskt);
			/*
			 * Si se está realizando pruebas, necesita conectarse con Pagadito SandBox. Para ello llamamos
			 * a la función mode_sandbox_on(). De lo contrario omitir la siguiente linea.
			 */
			if ($sendBox) {
				$Pagadito->mode_sandbox_on();
			}
			/*
			 * Validamos la conexión llamando a la función connect(). Retorna
			 * true si la conexión es exitosa. De lo contrario retorna false
			 */
			if ($Pagadito->connect()) 
			{
				/*
				 * Solicitamos el estado de la transacción llamando a la función
				 * get_status(). Le pasamos como parámetro el token recibido vía
				 * GET en nuestra URL de retorno.
				 */
				if ($Pagadito->get_status( $tocken ) ) 
				{
					/*
					 * Luego validamos el estado de la transacción, consultando el
					 * estado devuelto por la API.
					 */
					switch($Pagadito->get_rs_status())
					{
						case "COMPLETED":
							/*
							 * Tratamiento para una transacción exitosa.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////	

							//transaccion completa de posmeCalendar
							if($facturaParteApp == "AGE")
							{
								//Obtener el usuario
								$objUser 					= $this->User_Model->get_rowByEmail($facturaParteClient);
								$dataUser["lastPayment"] 	= $facturaParteConsercutivo;
								$this->User_Model->update_app_posme($objUser->companyID,$objUser->branchID,$objUser->userID,$dataUser);
								
								$msgSecundario = ''.
								'Gracias por comprar en posMe.</br> '.
								'NAP: ' .$Pagadito->get_rs_reference(). "</br>" . 
								'Fecha Respuesta:' .$Pagadito->get_rs_date_trans();
								
							}
							if($facturaParteApp == "FCCLI")
							{
								
								$msgSecundario = ''.
								'Gracias por comprar en posMe.</br> '.
								'NAP: ' .$Pagadito->get_rs_reference(). "</br>" . 
								'Fecha Respuesta:' .$Pagadito->get_rs_date_trans();
								
							}
							
							
							
							
							$this->core_web_notification->set_message(false,$msgSecundario);
							$this->response->redirect($urlRedirect);
							break;
						
						case "REGISTERED":
							
							/*
							 * Tratamiento para una transacción aún en
							 * proceso.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal = "Atención";
							$msgSecundario = "La transacción fue cancelada.";
							$mensaje = $msgPrincipal ." ". $msgSecundario;
							$this->core_web_notification->set_message(true,$mensaje);
							$this->response->redirect($urlRedirect);	
							break;
						
						case "VERIFYING":
							
							/*
							 * La transacción ha sido procesada en Pagadito, pero ha quedado en verificación.
							 * En este punto el cobro xha quedado en validación administrativa.
							 * Posteriormente, la transacción puede marcarse como válida o denegada;
							 * por lo que se debe monitorear mediante esta función hasta que su estado cambie a COMPLETED o REVOKED.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal 	= "Atención";
							$msgSecundario 	= "Su pago esta en validaciones NAP(Numero de aprobacion pagadito ". 
											   $Pagadito->get_rs_reference().
											   " Fecha de respuesta ". 
											   $Pagadito->get_rs_date_trans() ;
											   
							$mensaje = $msgPrincipal ." ". $msgSecundario;
							$this->core_web_notification->set_message(true,$mensaje);
							$this->response->redirect($urlRedirect);
							break;
						
						case "REVOKED":
							
							/*
							 * La transacción en estado VERIFYING ha sido denegada por Pagadito.
							 * En este punto el cobro ya ha sido cancelado.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////							
							$msgPrincipal 	= "Atención";
							$msgSecundario 	= "La transaccion fue denegada";											   
							$mensaje 		= $msgPrincipal ." ". $msgSecundario;
							$this->core_web_notification->set_message(true,$mensaje);
							$this->response->redirect($urlRedirect);
							break;
						
						case "FAILED":
							/*
							 * Tratamiento para una transacción fallida.
							 */
						default:
							
							/*
							 * Por ser un ejemplo, se muestra un mensaje
							 * de error fijo.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal 	= "Atención";
							$msgSecundario 	= "La transaccion no fue realizada";											   
							$mensaje 		= $msgPrincipal ." ". $msgSecundario;
							$this->core_web_notification->set_message(true,$mensaje);
							$this->response->redirect($urlRedirect);	
							break;
					}
				} 
				else 
				{
					/*
					 * En caso de fallar la petición, verificamos el error devuelto.
					 * Debido a que la API nos puede devolver diversos mensajes de
					 * respuesta, validamos el tipo de mensaje que nos devuelve.
					 */
					switch($Pagadito->get_rs_code())
					{
						case "PG2001":
							/*Incomplete data*/
						case "PG3002":
							/*Error*/
						case "PG3003":
							/*Unregistered transaction*/
						default:
							/*
							 * Por ser un ejemplo, se muestra un mensaje
							 * de error fijo.
							 */ ///////////////////////////////////////////////////////////////////////////////////////////////////////
							$msgPrincipal 	= "Atención";
							$msgSecundario 	= "La transaccion no fue completada";											   
							$mensaje 		= $msgPrincipal ." ". $msgSecundario;
							$this->core_web_notification->set_message(true,$mensaje);
							$this->response->redirect($urlRedirect);								
							break;
					}
				}
			} 
			else 
			{
				/*
				 * En caso de fallar la conexión, verificamos el error devuelto.
				 * Debido a que la API nos puede devolver diversos mensajes de
				 * respuesta, validamos el tipo de mensaje que nos devuelve.
				 */
				switch($Pagadito->get_rs_code())
				{
					case "PG2001":
						/*Incomplete data*/
					case "PG3001":
						/*Problem connection*/
					case "PG3002":
						/*Error*/
					case "PG3003":
						/*Unregistered transaction*/
					case "PG3005":
						/*Disabled connection*/
					case "PG3006":
						/*Exceeded*/
					default:
						/*
						 * Aqui se muestra el código y mensaje de la respuesta del WSPG
						 */
						$msgPrincipal 	= "Respueta de Api Pagadito";
						$msgSecundario 	= $Pagadito->get_rs_code() . " ".$Pagadito->get_rs_message();					   
						$mensaje 		= $msgPrincipal ." ". $msgSecundario;
						$this->core_web_notification->set_message(true,$mensaje);
						$this->response->redirect($urlRedirect);								
						break;
				}
			}
			
			//$this->core_web_notification->set_message(true,"Usuario error : "."Transaccion no match.");
			//$this->response->redirect($urlRedirect);
			
		} 
		else {
			$this->core_web_notification->set_message(true,"Usuario error : "."No se recibieron los datos correctamente.  La transacción no fue completada.");
			$this->response->redirect($urlRedirect);
		}
		
		
	}
	
	function savepublicgooglereturn()
	{
		
		//ya medio los permisos
		$code 		= $this->request->getGet("code");		
		$state 		= $this->request->getGet("state");	
		$business	= $state;
		if($code)
		{
			
			$core_web_google = new core_web_google();
			$tocket			 = $core_web_google->getRequestToket_Posme($code);
			
			//Obtener el usuario
			$objUsuario		 					= $this->User_Model->get_rowByFoto($business);
			$dataUser["token_google_calendar"] 	= $tocket;
			$this->User_Model->update_app_posme($objUsuario[0]->companyID,$objUsuario[0]->branchID,$objUsuario[0]->userID,$dataUser);
			
		}
		
		
		//Obtener el componente Para mostrar la lista de CompanyCurrency
		$objComponent					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_remember");
		if(!$objComponent)
		throw new \Exception("00409 EL COMPONENTE 'tb_remember' NO EXISTE...");
		$dataView["component"]			= $objComponent;
			
		$dataView["business"]				= $business;
		$dataView["objListPeriod"]			= $this->core_web_catalog->getCatalogAllItem("tb_remember","period",APP_COMPANY);
		$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_remember","statusID",APP_COMPANY,APP_BRANCH,APP_ROL_SUPERADMIN);
		$dataView["message"]				= $this->core_web_notification->get_message_alert();
		$dataView["objListUser"]			= $this->User_Model->get_All(APP_COMPANY);
		
		$dataSession["head"]				= /*--inicio view*/ view('app_config_noti/eventgooglecalendaradded_news_head',$dataView);//--finview
		$dataSession["body"]				= /*--inicio view*/ view('app_config_noti/eventgooglecalendaradded_news_body',$dataView);//--finview
		$dataSession["script"]				= /*--inicio view*/ view('app_config_noti/eventgooglecalendaradded_news_script',$dataView);//--finview
		$dataSession["footer"]				= "";
		return view("core_masterpage/default_masterpage_public",$dataSession);//--finview-r
		
	}
	
	
	
	function savepublic(){
		 try{ 			
			
			
			//Validar Campos obligatorios
			if(!$this->request->getPost("txtNombre"))
			throw new \Exception("Nombre obligatorio.");
		
			if(!$this->request->getPost("txtComercio"))
			throw new \Exception("Comercio obligatorio.");
		
		
			if(!$this->request->getPost("txtEmail"))
			throw new \Exception("Email obligatorio.");
		
			if(!$this->request->getPost("txtTelefono"))
			throw new \Exception("Telefono obligatorio.");
		
			$img 			= $this->request->getFile('formFilePortada');	
			$dataUpdateUser = array();			
			$db				= db_connect();
			$db->transStart();
			
			
			$comando					= "new"; //update
			$objUserTmp 				= $this->User_Model->get_rowByEmail(/*inicio get post*/ $this->request->getPost("txtEmail"));
			if($objUserTmp)
			{
				$comando 					= "update";
				//Crear Usuario
				$obj["companyID"]			= APP_COMPANY;				
				$obj["branchID"] 			= APP_BRANCH;				
				
				
				$obj["nickname"] 			= /*inicio get post*/ $this->request->getPost("txtNombre");			
				$obj["email"] 				= $objUserTmp->email;
				$obj["phone"] 				= /*inicio get post*/ $this->request->getPost("txtTelefono");						
				$obj["comercio"]			= /*inicio get post*/ $this->request->getPost("txtComercio");			
				$obj["comercio"]			= str_replace(" ","",$obj["comercio"]);
				$obj["comercio"]			= str_replace(".","",$obj["comercio"]);
				$obj["comercio"]			= str_replace("'","",$obj["comercio"]);
				$obj["comercio"]			= str_replace("/","",$obj["comercio"]);
				$obj["comercio"]			= str_replace('"',"",$obj["comercio"]);			
				$obj["comercio"]			= str_replace('í',"i",$obj["comercio"]);
				$obj["comercio"]			= str_replace('ó',"o",$obj["comercio"]);
				$obj["comercio"]			= str_replace('ú',"u",$obj["comercio"]);
				$obj["comercio"]			= str_replace('ñ',"n",$obj["comercio"]);
				$obj["comercio"]			= str_replace('Ñ',"N",$obj["comercio"]);
				$obj["comercio"]			= str_replace('é',"e",$obj["comercio"]);
				$obj["comercio"]			= str_replace('á',"a",$obj["comercio"]);
				$obj["isActive"] 			= true;		
				$obj["employeeID"] 			= 0;				
				$userID						= $objUserTmp->userID;
				$this->User_Model->update_app_posme(APP_COMPANY,APP_BRANCH,$userID,$obj);
				
				
			}
			else 
			{
				
				if($img->getSizeByUnit() == 0 )
				{
					throw new \Exception("Imagen obligatoria.");
				}
				
				$comando 					= "new";
				//Crear Usuario
				$obj["companyID"]			= APP_COMPANY;				
				$obj["branchID"] 			= APP_BRANCH;				
				
				$obj["nickname"] 			= /*inicio get post*/ $this->request->getPost("txtNombre");			
				$obj["email"] 				= /*inicio get post*/ $this->request->getPost("txtEmail");
				$obj["phone"] 				= /*inicio get post*/ $this->request->getPost("txtTelefono");						
				$obj["comercio"]			= /*inicio get post*/ $this->request->getPost("txtComercio");			
				$obj["comercio"]			= str_replace(" ","",$obj["comercio"]);
				$obj["comercio"]			= str_replace(".","",$obj["comercio"]);
				$obj["comercio"]			= str_replace("'","",$obj["comercio"]);
				$obj["comercio"]			= str_replace("/","",$obj["comercio"]);
				$obj["comercio"]			= str_replace('"',"",$obj["comercio"]);			
				$obj["comercio"]			= str_replace('í',"i",$obj["comercio"]);
				$obj["comercio"]			= str_replace('ó',"o",$obj["comercio"]);
				$obj["comercio"]			= str_replace('ú',"u",$obj["comercio"]);
				$obj["comercio"]			= str_replace('ñ',"n",$obj["comercio"]);
				$obj["comercio"]			= str_replace('Ñ',"N",$obj["comercio"]);
				$obj["comercio"]			= str_replace('é',"e",$obj["comercio"]);
				$obj["comercio"]			= str_replace('á',"a",$obj["comercio"]);
				
				
				$obj["createdOn"]			= date("Y-m-d H:i:s");		
				$obj["password"] 			= /*inicio get post*/ 123;
				$obj["lastPayment"]			= helper_getDateMoreOneMonth();
				$obj["createdBy"]			= APP_USERADMIN;
				$obj["isActive"] 			= true;		
				$obj["employeeID"] 			= 0;
				
				$userID		 				= $this->User_Model->insert_app_posme($obj);
			}
			
			
			//Crear carpeta de usuario		
			$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_8/component_item_".$userID;
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755);
				chmod($documentoPath, 0755);
			}
			
			
			
			//Crear imagen de identificacion
			//Guardar el archivo en la carpeta writable			
			if($img->getSizeByUnit()  == 0 && $comando == "update")
			{
				$dataUpdateUser["foto"] = $objUserTmp->foto;
			}
			if($img->getSizeByUnit()  != 0 && $comando == "update" )
			{
				$filepath 	= $img->store();			
				$name 		= $img->getName();		
				$filePathSource 		=  PATH_FILE_OF_UPLOAD_WRITE."/".$filepath;			
				$filePathDetination 	=  $documentoPath."/".$name;
				copy($filePathSource,$filePathDetination);
				unlink($filePathSource);				
				
				
				//Actualizar las notifiaciones con la nueva etiqueta de foto
				$objDataNotification["summary"] 	= $name;
				$this->Notification_Model->update_app_posme_by_sumary($objUserTmp->foto,$objDataNotification);				
				
				$dataUpdateUser["foto"] 			= $name;
				$this->User_Model->update_app_posme(APP_COMPANY,APP_BRANCH,$userID,$dataUpdateUser);
				
					
			}
			if($img->getSizeByUnit()  != 0 && $comando == "new" )
			{
				$filepath 	= $img->store();			
				$name 		= $img->getName();		
				$filePathSource 		=  PATH_FILE_OF_UPLOAD_WRITE."/".$filepath;			
				$filePathDetination 	=  $documentoPath."/".$name;
				copy($filePathSource,$filePathDetination);
				unlink($filePathSource);				
				
				$dataUpdateUser["foto"] = $name;
				$this->User_Model->update_app_posme(APP_COMPANY,APP_BRANCH,$userID,$dataUpdateUser);
					
			}
			
			
			
			if($comando == "new")
			{
				//Asociar rol
				$companyID 					= $obj["companyID"];
				$branchID 					= $obj["branchID"];			 
				$roleID 					= $this->core_web_parameter->getParameter("POSME_CALENDAR_ROLE_DEFAULT",APP_COMPANY)->value;
				$result 					= $this->Membership_Model->delete_app_posme($companyID,$branchID,$userID);
				 
				//Nuevo Membership
				$objMembership["companyID"] = $companyID;
				$objMembership["branchID"] 	= $branchID;
				$objMembership["userID"] 	= $userID;
				$objMembership["roleID"] 	= $roleID;
				$result 					= $this->Membership_Model->insert_app_posme($objMembership);
				
				
				//Agregar Notificaciones
				$this->User_Tag_Model->deleteByUser($userID);
				$objTag["companyID"] 		= $companyID;
				$objTag["branchID"] 		= $branchID;
				$objTag["userID"] 			= $userID;
				$objTag["tagID"] 			= $this->core_web_parameter->getParameter("POSME_CALENDAR_TAG_DEFAULT",APP_COMPANY)->value;
				$this->User_Tag_Model->insert_app_posme($objTag);
			}
			
			
			//Enviar mensaje de bienvenida
			$url = "Bienvenido: ".$this->request->getPost("txtNombre")." el siguiente link usted debe compartir con sus clientes: ";
			$this->core_web_whatsap->sendMessageUltramsg(
				APP_COMPANY, 
				$url,
				$this->request->getPost("txtTelefono")
			);
			
			
			//Url
			$core_web_google 		= new core_web_google();
			$urlGoogle 			 	= $core_web_google->getRequestPermission_Posme($dataUpdateUser["foto"]);
			$url 					= $this->core_web_parameter->getParameter("POSME_CALENDAR_URL_CITA",APP_COMPANY)->value."?txtBusiness=".$dataUpdateUser["foto"];
			$urlImagen 		 		= base_url()."/resource/file_company/company_".APP_COMPANY."/component_8/component_item_".$userID."/qrcode.png";
			
			//Guardar Codigo QR 			
			$this->core_web_qr->generate($url,$documentoPath."/qrcode.png","M","10");	
			
		
			//Enviar mensaje de link de acceso
			$this->core_web_whatsap->sendMessageUltramsg(
				APP_COMPANY, 
				$url,
				$this->request->getPost("txtTelefono")
			);
			
			//Enviar Permiso
			$this->core_web_whatsap->sendMessageUltramsg(
				APP_COMPANY, 
				$urlGoogle,
				$this->request->getPost("txtTelefono")
			);
			
			
			//Enviar mensaje de imagen de qr para el acceso			
			$this->core_web_whatsap->sendMessageTypeImagUltramsg(
				APP_COMPANY, 
				$urlImagen,
				"comparte y agenda",
				$this->request->getPost("txtTelefono")
			);
			
			
			//Return
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,"Exito informacion enviada a correo electronico o whatsapp");
				$this->response->redirect(base_url()."/".'core_user/addpublic');
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'core_user/addpublic');	
			}
			
		}
		catch(\Exception $ex){
			$this->core_web_notification->set_message(true,"Usuario error : ".$ex->getMessage());
			$this->response->redirect(base_url()."/".'core_user/addpublic');	
		}		
			
	}
	function addpublic(){
	
		try{ 
			
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			
			if(!$objComponentEntity)
			throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");
		
		
			
			
			//Obtener los Roles			
			$datView["objEmployee"]  = $objComponent;
			$datView["objEntity"]  	 = $objComponentEntity;
			$datView["message"]		 = $this->core_web_notification->get_message_alert();
			
			//Renderizar Resultado 		
			$dataSession["title"]			= "Formulario de afiliación";
			$dataSession["head"]			= /*--inicio view*/ view('core_user/addpublic_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_user/addpublic_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_user/addpublic_script',$datView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage_public",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			
			if (empty($dataSession)) 
			{
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = null;
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