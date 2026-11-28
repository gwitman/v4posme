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
						
						//Agregar Lista de Caja
						$objListBox					= /*inicio get post*/ $this->request->getPost("txtDetailCashBoxID");
						$this->Cash_Box_User_Model->deleteByUser($companyID,$userID);
						if($objListBox)
						foreach($objListBox as $key => $value){
							$objCashBoxUser 				= NULL;		
							$objCashBoxUser["companyID"] 	= $companyID;
							$objCashBoxUser["branchID"] 	= $branchID;
							$objCashBoxUser["userID"] 		= $userID;
							$objCashBoxUser["cashBoxID"]	= $value;
							$objCashBoxUser["typeID"] 		= 0;		
							$objCashBoxUser["isPrimary"] 	= 0;	
							$objCashBoxUser["isActive"] 	= 1;	
							$this->Cash_Box_User_Model->insert($objCashBoxUser);
							
						}
						
						//Agregar la caja por defecto
						$cashBoxID = helper_RequestGetValue($this->request->getPost("txtCashBoxID"),0);
						if($cashBoxID > 0)
						{
							$objCashBoxUser 				= NULL;					
							$objCashBoxUser["companyID"] 	= $companyID;
							$objCashBoxUser["branchID"] 	= $branchID;
							$objCashBoxUser["userID"] 		= $userID;
							$objCashBoxUser["cashBoxID"]	= $cashBoxID;
							$objCashBoxUser["typeID"] 		= 0;							
							$objCashBoxUser["isPrimary"] 	= 1;	
							$objCashBoxUser["isActive"] 	= 1;								
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
						
						//Agregar Lista de Caja
						$objListBox					= /*inicio get post*/ $this->request->getPost("txtDetailCashBoxID");
						$this->Cash_Box_User_Model->deleteByUser($companyID,$userID);
						if($objListBox)
						foreach($objListBox as $key => $value){
							$objCashBoxUser 				= NULL;		
							$objCashBoxUser["companyID"] 	= $companyID;
							$objCashBoxUser["branchID"] 	= $branchID;
							$objCashBoxUser["userID"] 		= $userID;
							$objCashBoxUser["cashBoxID"]	= $value;
							$objCashBoxUser["typeID"] 		= 0;		
							$objCashBoxUser["isPrimary"] 	= 0;	
							$objCashBoxUser["isActive"] 	= 1;	
							$this->Cash_Box_User_Model->insert($objCashBoxUser);
							
						}
						
						//Modificar Caja por defecto
						$cashBoxID = helper_RequestGetValue($this->request->getPost("txtCashBoxID"),0);
						if($cashBoxID > 0)
						{
							$objCashBoxUser 				= NULL;					
							$objCashBoxUser					= $this->Cash_Box_User_Model->asArray()
								->where("userID",$userID)
								->where("cashBoxID",$cashBoxID)
								->where("isActive",1)
								->findAll();
							
							if( $objCashBoxUser )
							{								
								$objCashBoxUser[0]["cashBoxID"]		= $cashBoxID;	
								$objCashBoxUser[0]["isPrimary"] 	= 1;	
								$this->Cash_Box_User_Model->update($objCashBoxUser[0]["cashBoxUserID"],$objCashBoxUser[0]);
							}
							else 
							{
								$objCashBoxUser["companyID"] 	= $companyID;
								$objCashBoxUser["branchID"] 	= $branchID;
								$objCashBoxUser["userID"] 		= $userID;
								$objCashBoxUser["cashBoxID"]	= $cashBoxID;
								$objCashBoxUser["typeID"] 		= 0;		
								$objCashBoxUser["isPrimary"] 	= 1;	
								$objCashBoxUser["isActive"] 	= 1;	
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

			//Tab Parametros: solo isAdmin o isAdminCompany y solo sobre la compania en sesion
			$datView["canEditParameter"]				= $this->core_web_report_builder->isAdminCompany($dataSession) && intval($companyID) == intval($dataSession["user"]->companyID);
			//Tab Compania: mismas reglas que Parametros (name, address y abreviature de tb_company)
			$datView["objCompanyEdit"]					= $datView["canEditParameter"] ? $this->Company_Model->get_rowByPK(intval($dataSession["user"]->companyID)) : null;


			//Lista de cajas
			$datView["objListCash"]	 				= $this->Cash_Box_Model->asObject()
				->where("companyID",$this->session->get('user')->companyID)
				->where("isActive",1)
				->findAll();
			$datView["objListCashUser"]	 			= $this->Cash_Box_User_Model->asObject()
						->where("companyID",$this->session->get('user')->companyID)
						->where("userID",$userID )
						->where("isActive",1 )
						->findAll();
			$datView["cashBoxID"]					= $datView["objListCashUser"] ? $datView["objListCashUser"][0]->cashBoxID : 0;
			$datView["objListBox"]	 				= $this->Cash_Box_User_Model->get_rowByUserID($companyID,$userID);
				
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
			$datView["objListCash"]	 = $datView["objListCash"]	 = $this->Cash_Box_Model->asObject()
				->where("companyID",$this->session->get('user')->companyID)
				->where("isActive",1)
				->findAll();
			
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
				//En telefono la lista se dibuja como en abonos (app_box_share)
				if( $this->request->getUserAgent()->isMobile() )
				$dataViewRender				= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
				else
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				//En telefono la lista se dibuja como en abonos (app_box_share)
				if( $this->request->getUserAgent()->isMobile() )
				$dataViewRender				= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
				else
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
	function add_box(){
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
			$objListBox				= $this->Cash_Box_Model->get_All($dataSession["user"]->companyID);
			$data["objListBox"] 	= $objListBox;
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('core_user/popup_box_add_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('core_user/popup_box_add_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('core_user/popup_box_add_script');//--finview
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
	
	function payment_dfrutas(){
	
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
			
			$dataSession["head"]			= /*--inicio view*/ view('core_user/payment_dfrutas_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('core_user/payment_dfrutas_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_user/payment_dfrutas_script',$datView);//--finview
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
		//PAGO POSME CALENDAR	= AGE__EMAIL__FECHA
		
		//Orignes de pago 2:	core_account/payment
		//PAGO POSME 			= POS__FLAVORID__FECHA 
		
		//Orignes de pago 3:	posme.net/ (woocommerce posme)
		//PAGO WOOMCOMERCE		= NUMBER
		
		//Orignes de pago 4:	app_invoice_api/getLinkPaymentPagadito
		//PAGO DE FACTURA DE CLIENTES Y PARA CLIENTES	= FCCLI_FLAVORID_FECHA

		//Orignes de pago 5:	app_cxc_advertising/paymentCreate
		//PAGO DE CAMPAÑA WHATSAPP	= CAM__COMPANYID__TRANSACTIONMASTERID (tb_transaction_master_pagadito)
		//							  CAM__ORIGEN__COMPANYID__TRANSACTIONMASTERID cuando la instalacion tiene paymentOriginCode;
		//							  si ORIGEN es otra instalacion se reenvia a la URL de la lista blanca paymentOrigins

		
		
		
		
		
		
		
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
		$urlResultadoFacturacion 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_URL_RESULT",APP_COMPANY);
		$urlResultadoFacturacion 	= $urlResultadoFacturacion->value;
				
		
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

		//Pago de campaña con origen (CAM__ORIGEN__COMPANYID__TRANSACTIONMASTERID): Pagadito solo regresa a esta instalacion,
		//si el origen es otra instalacion (otra carpeta u otro servidor) se reenvia a su payment_user_back segun la lista blanca.
		//reenviado=1 garantiza un solo salto: quien recibe un reenvio procesa localmente y nunca vuelve a reenviar.
		$facturaParteOrigen			= "";
		if($facturaParteApp == "CAM" && count($facturaParte) >= 4)
		{
			$facturaParteOrigen			= $facturaParte[1];
			$facturaParteClient			= $facturaParte[2];
			$facturaParteConsercutivo	= $facturaParte[3];
		}
		$pagoReenviado				= $this->request->getGet("reenviado") == "1";
		if($facturaParteOrigen != "" && !$pagoReenviado && strcasecmp($facturaParteOrigen,$this->core_web_payment->getOriginCode()) != 0)
		{
			$urlOrigen = $this->core_web_payment->getOriginUrl($facturaParteOrigen);
			if($urlOrigen != "" && strcasecmp($urlOrigen,rtrim(base_url(),"/")) != 0)
			{
				$urlReenvio = $urlOrigen."/core_user/payment_user_back?".http_build_query(array("parametro1" => $tocken,"parametro2" => $factura,"reenviado" => "1"));
				log_message("info","[core_user::payment_user_back] pago ".$factura." reenviado a ".$urlOrigen);
				$this->response->redirect($urlReenvio);
				return;
			}
			//Origen no autorizado: se procesa aqui (no encontrara la campaña) y se recupera con Verificar pago en el origen
			if($urlOrigen == "")
			log_message("error","[core_user::payment_user_back] origen ".$facturaParteOrigen." no esta en la lista blanca paymentOrigins, pago ".$factura);
		}

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
			$urlRedirect = base_url()."/".$urlResultadoFacturacion;
		}
		//pago de campaña whatsapp
		else if($facturaParteApp == "CAM")
		{
			$urlRedirect = base_url()."/app_cxc_advertising/index";
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
					//Pago de campaña WhatsApp: se actualiza tb_transaction_master_pagadito antes de armar el mensaje
					//campaignResult: ok | token (no coincide, no se actualiza) | notfound (no existe o error)
					$campaignResult 	= "";
					$campaignNap 		= $Pagadito->get_rs_reference();
					if($facturaParteApp == "CAM")
					{
						$campaignResult = "notfound";
						try
						{
							$campaignCompanyID 		= (int)$facturaParteClient;
							$campaignMasterID 		= (int)$facturaParteConsercutivo;
							$campaignStatus 		= $Pagadito->get_rs_status();
							$campaignTransactionID 	= $this->core_web_payment->getTransactionPagaditoID($campaignCompanyID);
							$objCampaign 			= $this->Transaction_Master_Model->get_rowByPK($campaignCompanyID,$campaignTransactionID,$campaignMasterID);

							if(!$objCampaign)
							{
								$campaignResult = "notfound";
							}
							else if((string)$objCampaign->reference1 !== (string)$tocken)
							{
								$campaignResult = "token";
								log_message("error","[core_user::payment_user_back] token no coincide para ".$factura." NAP ".$campaignNap);
							}
							else
							{
								$campaignResult = "ok";
								if(in_array($campaignStatus,array("COMPLETED","VERIFYING","REVOKED","FAILED")))
								{
									$dataCampaign 					= array();
									$dataCampaign["reference4"] 	= $campaignStatus;
									$dataCampaign["reference2"] 	= $campaignNap;

									//Estado aplicable del workflow: requiere la sesion del usuario (rol); si no hay, manda reference4
									$sessionCallback = $this->session->get();
									if($campaignStatus == "COMPLETED" && !empty($sessionCallback["user"]) && !empty($sessionCallback["role"]) && $sessionCallback["user"]->companyID == $campaignCompanyID)
									{
										try
										{
											$campaignStatusID = $this->core_web_payment->getWorkflowStageID($campaignCompanyID,$sessionCallback["user"]->branchID,$sessionCallback["role"]->roleID,true);
											if($campaignStatusID > 0)
											{
												$dataCampaign["statusID"] 			= $campaignStatusID;
												$dataCampaign["statusIDChangeOn"] 	= helper_getDateTime();
											}
										}
										catch(\Throwable $exStage)
										{
											log_message("error","[core_user::payment_user_back] estado aplicable ".$exStage->getMessage());
										}
									}

									$this->Transaction_Master_Model->update_app_posme($campaignCompanyID,$campaignTransactionID,$campaignMasterID,$dataCampaign);
								}
							}
						}
						catch(\Throwable $exCampaign)
						{
							$campaignResult = "notfound";
							log_message("error","[core_user::payment_user_back] campaña ".$factura." ".$exCampaign->getMessage());
						}
						log_message("info","[core_user::payment_user_back] campaña ".$factura." estado ".$Pagadito->get_rs_status()." resultado ".$campaignResult." NAP ".$campaignNap);
					}

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
							$msgError 		= false;
							$msgSecundario 	= "";

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
								
								$msgSecundario = '
									<style>
										table {
										  border-collapse: collapse;
										  width: 400px;
										  margin: 20px auto;
										  font-family: \'Segoe UI\', sans-serif;
										  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
										  border-radius: 10px;
										  overflow: hidden;
										}

										th {
										  background: linear-gradient(90deg, #4CAF50, #81C784);
										  color: white;
										  font-size: 20px;
										  padding: 15px;
										  text-align: center;
										}

										td {
										  padding: 12px 20px;
										  border-bottom: 1px solid #f0f0f0;
										}

										tr:nth-child(even) td {
										  background-color: #f9f9f9;
										}

										tr:last-child td {
										  border-bottom: none;
										}

										td:first-child {
										  font-weight: bold;
										  color: #333;
										}
									</style>
									  
									<table>
										  <tr>
											<th colspan="2">📋 Gracias por comprar.</th>
										  </tr>
										  <tr>
											<td>🔑 NAP</td>
											<td>'.$Pagadito->get_rs_reference().'</td>
										  </tr>
										  <tr>
											<td>📌 Valor</td>
											<td>'.$Pagadito->get_rs_date_trans().'</td>
										  </tr>
									</table>
								';

							}

							//transaccion completa de licencia posMe (no extiende la licencia: solo informa)
							if($facturaParteApp == "POS")
							{
								$msgSecundario = ''.
								'Gracias por comprar en posMe.</br> '.
								'NAP: ' .$Pagadito->get_rs_reference(). "</br>" .
								'Fecha Respuesta:' .$Pagadito->get_rs_date_trans();
								log_message("info","[core_user::payment_user_back] pago licencia ".$factura." NAP ".$Pagadito->get_rs_reference());
							}

							//transaccion completa de campaña whatsapp
							if($facturaParteApp == "CAM")
							{
								if($campaignResult == "ok")
								{
									$msgSecundario 	= "Pago de campaña aprobado. NAP: ".$campaignNap.". Ya puede enviar la campaña.";
								}
								else if($campaignResult == "token")
								{
									$msgError 		= true;
									$msgSecundario 	= "El pago (NAP ".$campaignNap.") no corresponde a la campaña registrada; no se aplicó. Comuníquese con soporte.";
								}
								else
								{
									$msgError 		= true;
									$msgSecundario 	= "El pago fue aprobado (NAP ".$campaignNap.") pero no se encontró la campaña en esta instalación; use Verificar pago desde la pantalla de campañas.";
								}
							}

							$this->core_web_notification->set_message($msgError,$msgSecundario);
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
			$this->core_web_whatsap->sendWhatsapp(
				APP_COMPANY,
				$this->request->getPost("txtTelefono"),
				$url,
				"evolution"
			);


			//Url
			$core_web_google 		= new core_web_google();
			$urlGoogle 			 	= $core_web_google->getRequestPermission_Posme($dataUpdateUser["foto"]);
			$url 					= $this->core_web_parameter->getParameter("POSME_CALENDAR_URL_CITA",APP_COMPANY)->value."?txtBusiness=".$dataUpdateUser["foto"];
			$urlImagen 		 		= base_url()."/resource/file_company/company_".APP_COMPANY."/component_8/component_item_".$userID."/qrcode.png";
			
			//Guardar Codigo QR 			
			$this->core_web_qr->generate($url,$documentoPath."/qrcode.png","M","10");	
			
		
			//Enviar mensaje de link de acceso
			$this->core_web_whatsap->sendWhatsapp(
				APP_COMPANY,
				$this->request->getPost("txtTelefono"),
				$url,
				"evolution"
			);

			//Enviar Permiso
			$this->core_web_whatsap->sendWhatsapp(
				APP_COMPANY,
				$this->request->getPost("txtTelefono"),
				$urlGoogle,
				"evolution"
			);


			//Enviar mensaje de imagen de qr para el acceso
			$this->core_web_whatsap->sendWhatsapp(
				APP_COMPANY,
				$this->request->getPost("txtTelefono"),
				"comparte y agenda",
				"evolution",
				['type' => 'image', 'url' => $urlImagen, 'fileName' => 'qrcode.png', 'fileExt' => 'png']
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

	/**
	 * Devuelve en JSON los parametros editables (tb_parameter.isEdited = 1) de la compania en sesion.
	 * Solo para roles isAdmin o isAdminCompany. Si llega companyID (POST o URI) debe ser el de la sesion.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON {error, message, data:[...]}
	 */
	function getParameterCompany(){
		try{
			$dataSession		= $this->parameterCompany_session("edit");
			$companyID			= $dataSession["user"]->companyID;

			//companyID del cliente: solo se compara, nunca se usa para consultar
			$companyIDClient	= /*inicio get post*/ $this->request->getPost("companyID");
			if(!$companyIDClient)
			$companyIDClient	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			if($companyIDClient && intval($companyIDClient) != intval($companyID))
			throw new \Exception(NOT_ACCESS_CONTROL);

			$objList			= $this->Company_Parameter_Model->get_rowByCompanyIDEditable($companyID);

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'data'    => $objList
			));//--finjson
		}
		catch(\Exception $ex){
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}

	/**
	 * Recibe JSON crudo {companyID, items:[{companyParameterID, customValue}]} y actualiza solo customValue
	 * de los parametros editables de la compania en sesion. Valida todo antes de guardar (todo o nada).
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON {error, message, updated, errors:[...]}
	 */
	function saveParameterCompany(){
		try{
			$dataSession		= $this->parameterCompany_session("edit");
			$companyID			= $dataSession["user"]->companyID;
			$body				= $this->request->getJSON(true);

			if(!is_array($body) || !isset($body["items"]) || !is_array($body["items"]))
			throw new \Exception(NOT_PARAMETER);

			if(isset($body["companyID"]) && $body["companyID"] !== "" && intval($body["companyID"]) != intval($companyID))
			throw new \Exception(NOT_ACCESS_CONTROL);

			//Indexar los parametros editables de la compania: solo estos se pueden modificar
			$objListEditable	= $this->Company_Parameter_Model->get_rowByCompanyIDEditable($companyID);
			$mapEditable		= array();
			foreach($objListEditable as $row)
			{
				$mapEditable[intval($row->companyParameterID)] = $row;
			}

			//Validar todo primero; si hay algun error no se guarda nada
			$errors				= array();
			$toSave				= array();
			foreach($body["items"] as $item)
			{
				if(!is_array($item))
				{
					$errors[] = array("companyParameterID" => 0, "message" => "ELEMENTO INVALIDO");
					continue;
				}

				$id				= isset($item["companyParameterID"]) ? intval($item["companyParameterID"]) : 0;
				$custom			= isset($item["customValue"]) && !is_array($item["customValue"]) ? trim((string)$item["customValue"]) : "";

				if(!isset($mapEditable[$id]))
				{
					$errors[] = array("companyParameterID" => $id, "message" => "PARAMETRO NO EDITABLE");
					continue;
				}

				$msg			= $this->validateParameterCustomValue($mapEditable[$id],$custom);
				if($msg !== "")
				{
					$errors[] = array("companyParameterID" => $id, "display" => $mapEditable[$id]->display, "message" => $msg);
					continue;
				}

				if((string)$mapEditable[$id]->customValue !== $custom)
				$toSave[$id] = $custom;
			}

			if(count($errors) > 0)
			{
				return $this->response->setJSON(array(
					'error'   => true,
					'message' => "VALORES INVALIDOS",
					'errors'  => $errors
				));//--finjson
			}

			$db = db_connect();
			$db->transStart();
			foreach($toSave as $id => $custom)
			{
				$this->Company_Parameter_Model->update_customValue_app_posme($companyID,$id,$custom);
			}
			$db->transComplete();

			if($db->transStatus() === false)
			throw new \Exception("ERROR AL GUARDAR LOS PARAMETROS");

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'updated' => count($toSave),
				'errors'  => array()
			));//--finjson
		}
		catch(\Exception $ex){
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}

	/**
	 * Recibe JSON crudo {companyID, name, address, abreviature} y actualiza esos tres campos de tb_company
	 * de la compania en sesion. Solo roles isAdmin o isAdminCompany. Nunca modifica type ni flavorID.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON {error, message, data:{name, address, abreviature}}
	 */
	function saveCompany(){
		try{
			$dataSession		= $this->parameterCompany_session("edit");
			$companyID			= $dataSession["user"]->companyID;
			$body				= $this->request->getJSON(true);

			if(!is_array($body))
			throw new \Exception(NOT_PARAMETER);

			//companyID del cliente: solo se compara, nunca se usa para actualizar
			if(isset($body["companyID"]) && $body["companyID"] !== "" && intval($body["companyID"]) != intval($companyID))
			throw new \Exception(NOT_ACCESS_CONTROL);

			//Campo => [etiqueta, longitud maxima de la columna, obligatorio]
			$fields				= array(
				"name"        => array("NOMBRE",250,true),
				"address"     => array("DIRECCION",550,false),
				"abreviature" => array("ABREVIATURA",255,false)
			);
			$data				= array();
			foreach($fields as $field => $rule)
			{
				$value			= isset($body[$field]) && !is_array($body[$field]) ? trim((string)$body[$field]) : "";
				if($rule[2] && $value === "")
				throw new \Exception("EL CAMPO ".$rule[0]." ES OBLIGATORIO");
				if(!mb_check_encoding($value,"UTF-8") || mb_strlen($value,"UTF-8") > $rule[1])
				throw new \Exception("EL CAMPO ".$rule[0]." ADMITE MAXIMO ".$rule[1]." CARACTERES");
				//Las columnas de tb_company son latin1: no admiten emojis ni caracteres fuera de ese juego
				if($value !== "" && @iconv("UTF-8","ISO-8859-1",$value) === false)
				throw new \Exception("EL CAMPO ".$rule[0]." TIENE CARACTERES NO SOPORTADOS");
				$data[$field]	= $value;
			}

			$objCompany			= $this->Company_Model->get_rowByPK(intval($companyID));
			if(!$objCompany)
			throw new \Exception("LA EMPRESA NO EXISTE");

			$this->Company_Model->update_generalData_app_posme($companyID,$data);

			//Refrescar la compania en sesion para que el cambio se vea sin volver a iniciar sesion
			if(isset($dataSession["company"]) && is_object($dataSession["company"]))
			{
				$objSession					= $dataSession["company"];
				$objSession->name			= $data["name"];
				$objSession->address		= $data["address"];
				$objSession->abreviature	= $data["abreviature"];
				$this->session->set("company",$objSession);
			}

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'data'    => $data
			));//--finjson
		}
		catch(\Exception $ex){
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}

	/**
	 * Devuelve en JSON la configuracion de pantallas (table = "normal": tb_company_page_setting) o las plantillas
	 * (table = "large": tb_company_page_setting_large) del tipo de la compania en sesion (keyi = tb_company.type).
	 * Con mode = "missing" devuelve los registros 'demo' cuyo controller + method + element no existen en la compania.
	 * Solo para roles isAdmin o isAdminCompany.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON {error, message, companyType, mode, data:[...]}
	 */
	function getPageSetting(){
		try{
			$dataSession		= $this->parameterCompany_session("edit");
			$companyID			= $dataSession["user"]->companyID;
			$body				= $this->pageSetting_body();

			//companyID del cliente: solo se compara, nunca se usa para consultar
			if(isset($body["companyID"]) && $body["companyID"] !== "" && intval($body["companyID"]) != intval($companyID))
			throw new \Exception(NOT_ACCESS_CONTROL);

			$table				= isset($body["table"]) ? (string)$body["table"] : "";
			$mode				= isset($body["mode"]) && $body["mode"] === "missing" ? "missing" : "company";
			$model				= $this->pageSetting_model($table);
			$companyType		= $this->pageSetting_companyType($companyID);

			if($mode == "missing")
			$objList			= $companyType == "demo" ? array() : $model->get_rowsEditorMissingFromDemo($companyType);
			else
			$objList			= $model->get_rowsEditorByKey($companyType);

			return $this->pageSetting_json(array(
				'error'       => false,
				'message'     => SUCCESS,
				'companyType' => $companyType,
				'mode'        => $mode,
				'data'        => $objList
			));
		}
		catch(\Exception $ex){
			return $this->pageSetting_json(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));
		}
	}

	/**
	 * Guarda UN registro (una tarjeta) de configuracion de pantallas o de plantillas. Recibe JSON crudo
	 * {companyID, table, id, isNew, valuei}. Si isNew = false actualiza solo valuei del registro de la compania;
	 * si isNew = true, id es el registro 'demo' de origen: se inserta una copia con keyi = tipo de la compania
	 * (o se actualiza si ya existe ese controller + method + element). Nunca modifica registros 'demo'
	 * salvo que la compania sea de tipo 'demo'.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON {error, message, id, keyi, inserted}
	 */
	function savePageSetting(){
		try{
			$dataSession		= $this->parameterCompany_session("edit");
			$companyID			= $dataSession["user"]->companyID;
			$body				= $this->request->getJSON(true);

			if(!is_array($body) || !isset($body["id"]) || !array_key_exists("valuei",$body) || is_array($body["valuei"]))
			throw new \Exception(NOT_PARAMETER);

			if(isset($body["companyID"]) && $body["companyID"] !== "" && intval($body["companyID"]) != intval($companyID))
			throw new \Exception(NOT_ACCESS_CONTROL);

			$table				= isset($body["table"]) ? (string)$body["table"] : "";
			$model				= $this->pageSetting_model($table);
			$companyType		= $this->pageSetting_companyType($companyID);
			$id					= intval($body["id"]);
			$isNew				= !empty($body["isNew"]);
			$valuei				= (string)$body["valuei"];	//sin trim: en plantillas los espacios y saltos importan

			$msg				= $this->validatePageSettingValuei($table,$valuei);
			if($msg !== "")
			throw new \Exception($msg);

			$row				= $model->get_rowByPk($id);
			if(!$row)
			throw new \Exception("EL REGISTRO NO EXISTE");

			//Validar pertenencia antes de abrir la transaccion
			//existente: debe ser del tipo de la compania; nuevo: el origen debe ser 'demo'
			if(!$isNew && (string)$row->keyi !== $companyType)
			throw new \Exception(NOT_ACCESS_CONTROL);
			if($isNew && (string)$row->keyi !== "demo")
			throw new \Exception(NOT_ACCESS_CONTROL);

			$inserted			= false;
			$db					= db_connect();
			$db->transStart();
			if(!$isNew)
			{
				$model->updateValueiEditor_app_posme($id,$companyType,$valuei);
				$savedID		= $id;
			}
			else
			{
				//Si ya existe para la compania (doble clic, otra pestana) se actualiza en vez de duplicar
				$exist			= $model->get_rowByKeyControllerMethodElement($companyType,$row->controller,$row->method,$row->element);
				if($exist)
				{
					$savedID	= intval($exist->{$table == "large" ? "customPageLargeID" : "customPageID"});
					$model->updateValueiEditor_app_posme($savedID,$companyType,$valuei);
				}
				else
				{
					$savedID	= $model->insertEditor_app_posme(array(
						"namei"      => $row->namei,
						"keyi"       => $companyType,
						"controller" => $row->controller,
						"method"     => $row->method,
						"type"       => $row->type,
						"flavorID"   => $row->flavorID,
						"element"    => $row->element,
						"valuei"     => $valuei,
						"isActive"   => 1
					));
					$inserted	= true;
				}
			}
			$db->transComplete();

			if($db->transStatus() === false)
			throw new \Exception("ERROR AL GUARDAR EL REGISTRO");

			return $this->pageSetting_json(array(
				'error'    => false,
				'message'  => SUCCESS,
				'id'       => intval($savedID),
				'keyi'     => $companyType,
				'inserted' => $inserted
			));
		}
		catch(\Exception $ex){
			return $this->pageSetting_json(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));
		}
	}

	/**
	 * Lee el cuerpo de la peticion: JSON crudo o, si no viene JSON, los campos POST.
	 *
	 * @return array Datos recibidos.
	 */
	protected function pageSetting_body(){
		$body = null;
		try{
			$body = $this->request->getJSON(true);
		}
		catch(\Exception $ex){
			$body = null;
		}
		if(!is_array($body))
		$body = (array)$this->request->getPost();
		return $body;
	}

	/**
	 * Modelo segun la tabla pedida: "normal" (tb_company_page_setting) o "large" (tb_company_page_setting_large).
	 *
	 * @param string $table "normal" o "large".
	 * @return object Modelo.
	 */
	protected function pageSetting_model($table){
		if($table === "normal")
		return $this->Company_Page_Setting_Model;
		if($table === "large")
		return new \App\Models\Company_Page_Setting_Large_Model();
		throw new \Exception(NOT_PARAMETER);
	}

	/**
	 * Tipo de la compania (tb_company.type), que es el keyi de las tablas de configuracion.
	 *
	 * @param int $companyID Compania en sesion.
	 * @return string Tipo de la compania.
	 */
	protected function pageSetting_companyType($companyID){
		$objCompany		= $this->Company_Model->get_rowByPK(intval($companyID));
		$companyType	= $objCompany ? trim((string)$objCompany->type) : "";
		if($companyType === "")
		throw new \Exception("LA EMPRESA NO TIENE TIPO CONFIGURADO (tb_company.type)");
		return $companyType;
	}

	/**
	 * Respuesta JSON tolerante a bytes no UTF-8 (las plantillas blob pueden traerlos y json_encode fallaria).
	 *
	 * @param array $data Respuesta.
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	protected function pageSetting_json($data){
		$json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
		if($json === false)
		$json = json_encode(array('error' => true, 'message' => 'ERROR AL CODIFICAR LA RESPUESTA'));
		return $this->response->setContentType('application/json')->setBody($json);
	}

	/**
	 * Valida valuei: siempre texto. En "normal" (varchar 4000) maximo 4000 caracteres y sin caracteres de 4 bytes
	 * (servidores MariaDB 5.5 en utf8). En "large" (blob) solo se exige UTF-8 valido.
	 *
	 * @param string $table  "normal" o "large".
	 * @param string $valuei Valor propuesto.
	 * @return string "" si es valido, o el mensaje de error.
	 */
	protected function validatePageSettingValuei($table,$valuei){
		if(!mb_check_encoding($valuei,"UTF-8"))
		return "CARACTERES NO SOPORTADOS";

		if($table === "normal")
		{
			if(mb_strlen($valuei,"UTF-8") > 4000)
			return "MAXIMO 4000 CARACTERES";
			if(preg_match('/[\x{10000}-\x{10FFFF}]/u',$valuei))
			return "CARACTERES NO SOPORTADOS (EMOJIS)";
		}
		return "";
	}

	/**
	 * Autentica, valida permisos (urlPermited / urlPermissionCmd) y que el rol en sesion sea isAdmin o isAdminCompany.
	 * Se usa en los endpoints AJAX del tab Parametros.
	 *
	 * @param string $cmd Comando de permiso a validar ("edit").
	 * @return array Datos de la sesion.
	 */
	protected function parameterCompany_session($cmd){
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();

		if(APP_NEED_AUTHENTICATION == true){
			$permited = false;
			$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			if(!$permited)
			throw new \Exception(NOT_ACCESS_CONTROL);

			$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),$cmd,URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			if ($resultPermission 	== PERMISSION_NONE)
			throw new \Exception(NOT_ALL_EDIT);
		}

		//Regla de negocio: los parametros afectan a toda la compania, solo administradores
		if(!$this->core_web_report_builder->isAdminCompany($dataSession))
		throw new \Exception(NOT_ACCESS_CONTROL);

		return $dataSession;
	}

	/**
	 * Valida el customValue de un parametro segun su tipo (tb_parameter.type): bool, int, decimal, text,
	 * datetime o combobox (opciones separadas por coma en tb_parameter.source). Si type esta vacio o no es
	 * reconocido, el tipo se detecta a partir de value. Vacio siempre es valido. Maximo 300 caracteres.
	 *
	 * @param object $row    Fila de get_rowByCompanyIDEditable (type, source, value...).
	 * @param string $custom Valor propuesto para customValue (ya con trim).
	 * @return string "" si es valido, o el mensaje de error.
	 */
	protected function validateParameterCustomValue($row,$custom){
		if($custom === "")
		return "";

		if(mb_strlen($custom,"UTF-8") > 300)
		return "MAXIMO 300 CARACTERES";

		//La columna es utf8 (3 bytes): no admite caracteres de 4 bytes como emojis
		if(preg_match('/[\x{10000}-\x{10FFFF}]/u',$custom) || !mb_check_encoding($custom,"UTF-8"))
		return "CARACTERES NO SOPORTADOS";

		$type			= strtolower(trim((string)(isset($row->type) ? $row->type : "")));
		$typeValid		= array("bool","int","decimal","text","datetime","combobox");

		//Sin tipo configurado: detectar por value
		if(!in_array($type,$typeValid,true))
		{
			$value		= trim((string)$row->value);
			if(in_array(strtolower($value),array("true","false"),true))
			$type		= "bool";
			else if(preg_match('/^-?\d+$/',$value))
			$type		= "int";
			else if(preg_match('/^-?\d+\.\d+$/',$value))
			$type		= "decimal";
			else if(preg_match('/^\d{4}-\d{2}-\d{2}$/',$value))
			$type		= "datetime";
			else
			$type		= "text";
		}

		switch($type)
		{
			case "bool":
				return in_array(strtolower($custom),array("true","false"),true) ? "" : "DEBE SER true O false";
			case "int":
				return preg_match('/^-?\d+$/',$custom) ? "" : "DEBE SER ENTERO";
			case "decimal":
				return preg_match('/^-?\d+(\.\d+)?$/',$custom) ? "" : "DEBE SER DECIMAL";
			case "datetime":
				$date	= \DateTime::createFromFormat('!Y-m-d',$custom);
				return ($date && $date->format('Y-m-d') === $custom) ? "" : "DEBE SER FECHA yyyy-mm-dd";
			case "combobox":
				$options	= array();
				foreach(explode(",",(string)(isset($row->source) ? $row->source : "")) as $option)
				{
					$option	= trim($option);
					if($option !== "")
					$options[] = $option;
				}
				return in_array($custom,$options,true) ? "" : "DEBE SER UNA DE LAS OPCIONES: ".implode(", ",$options);
			default:
				return "";
		}
	}

}
?>