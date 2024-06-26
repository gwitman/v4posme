<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_account extends _BaseController {   
	

	public function isValidAccountNumber($accountNumber="",$companyID="",$accountLevelID=""){ 
		//false numero incorrecto
		//true numero correcto		
		
		$objAccountLevel = $this->Account_Level_Model->get_rowByPK($companyID,$accountLevelID);
				
		
		//Validar Longitud Total
		if($objAccountLevel->lengthTotal != strlen($accountNumber) )
		return false;		
			
		
		
		//Validar Longitud de Grupo
		if($objAccountLevel->split)
		{		
			$partNumber = explode($objAccountLevel->split,$accountNumber);
			$count 		= count($partNumber) -1;
			
			
			if($objAccountLevel->lengthGroup != strlen($partNumber[$count]))
			return false;
		}
		
		return true;
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
			$accountID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"accountID");//--finuri
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$accountID))
			{ 
				$this->response->redirect(base_url()."/".'app_accounting_account/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objAccount"]	 				= $this->Account_Model->get_rowByPK($companyID,$accountID);
			$datView["objParentAccount"]			= null;
			
			if( $datView["objAccount"]->parentAccountID != null )
			$datView["objParentAccount"]			= $this->Account_Model->get_rowByPK($companyID,$datView["objAccount"]->parentAccountID);
		
			$datView["objListAccountLevel"]	 		= $this->Account_Level_Model->getByCompany($companyID);
			$datView["objListAccountType"]	 		= $this->Account_Type_Model->getByCompany($companyID);
			$datView["objListCompanyCurrency"]	 	= $this->Company_Currency_Model->getByCompany($companyID);
			
			$datView["objListCenterCost"]			= $this->Center_Cost_Model->getByCompany($dataSession["user"]->companyID);
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account/edit_script',$datView);//--finview
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
			$accountID 			= /*inicio get post*/ $this->request->getPost("accountID");				
			$branchID			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			
			if((!$companyID && !$accountID)){
					throw new \Exception(NOT_PARAMETER);		
			} 
			
			$obj = $this->Account_Model->get_rowByPK($companyID,$accountID);	
			
			//Validar permiso de usuario
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			//Validar si la cuenta es hoja
			$resultTemp = $this->Account_Model->get_isParent($companyID,$accountID);
			if($resultTemp > 0 ){
			throw new \Exception("ELIMINAR PRIMERAMENTE LAS CUENTAS DE MAS BAJO NIVEL");
			}
			
			//Validar Saldo
			$app					 = "DELETE_ACCOUNT";
			
			
			
			$query					  = "CALL pr_accounting_checkaccount_to_delete(?,?,?,?,?,@resultProcessMessage,@resultProcessCode);";			
			$resultProcess			= $this->Bd_Model->executeRender(
				$query,[$companyID,$branchID,$loginID,$accountID,$app]
			);	
			
			
			$query					 = "SELECT @resultProcessMessage as message,@resultProcessCode as codigo;";
			$resultProcess			 = $this->Bd_Model->executeRender($query,null);				
			$resultProcess			 = $resultProcess[0];
			
			
			if($resultProcess["codigo"] == 0)
			throw new \Exception($resultProcess["message"]);
			
			//Eliminar el Registro
			$this->Account_Model->delete_app_posme($companyID,$accountID);
					
			
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
			$this->validation->setRule("txtAccountNumber","Codigo","required");    
			$this->validation->setRule("txtAccountLevelID","Clase","required");
			$this->validation->setRule("txtAccountTypeID","Tipo","required");
			$this->validation->setRule("txtName","Nombre","required");
			$this->validation->setRule("txtCurrencyID","Moneda","required");
			
			 
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
						throw new \Exception(NOT_ACCESS_FUNCTION);			
					
					}	
					
					$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");					
					//Ingresar Cuenta
					if($continue){
						$db=db_connect();
						$db->transStart();
						//Buscar Padre
						$objParentAccount = NULL;
						if(/*inicio get post*/ $this->request->getPost("txtAccountNumberParent")){
							$objParentAccount		= $this->Account_Model->getByAccountNumber(/*inicio get post*/ $this->request->getPost("txtAccountNumberParent"),$dataSession["user"]->companyID);
						}
						
						//Buscar si Existe Cuenta
						$objAccount					= $this->Account_Model->getByAccountNumber(/*inicio get post*/ $this->request->getPost("txtAccountNumber"),$dataSession["user"]->companyID);
						if($objAccount){
							$continue 				= false;
							throw new \Exception("EL CODIGO DE LA CUENTA YA ESTA RESERVADO...");
						}
						//Validar Codigo							
						if(!$this->isValidAccountNumber( $this->request->getPost("txtAccountNumber") ,$dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtAccountLevelID"))){
							$continue 				= false;
							throw new \Exception("EL CODIGO DE LA CUENTA TIENE UN FORMATO INCORRECTO");
						}
						//Validar si la cuenta puede ser operativa
						$objAccountLevel 			= $this->Account_Level_Model->get_rowByPK($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtAccountLevelID"));
						$txtIsOperative				= $this->request->getPost("txtIsOperative");	
						$txtIsOperative				= empty($txtIsOperative) ? 0 : 1;
						if( $txtIsOperative !=  $objAccountLevel->isOperative){
							$continue 				= false;
							throw new \Exception("OPERATIVIDAD DE LA CUENTA NO ES VALIDA");
						}
						
						
						//Crear Cuenta
						$obj["companyID"]			= $dataSession["user"]->companyID;
						$obj["accountTypeID"] 		= /*inicio get post*/ $this->request->getPost("txtAccountTypeID");
						$obj["accountLevelID"] 		= /*inicio get post*/ $this->request->getPost("txtAccountLevelID");
						$obj["parentAccountID"] 	= $objParentAccount ? $objParentAccount->accountID : NULL;
						
						$obj["classID"] 			= /*inicio get post*/ $this->request->getPost("txtClassID") ? /*inicio get post*/ $this->request->getPost("txtClassID") : NULL;
						$obj["accountNumber"] 		= /*inicio get post*/ $this->request->getPost("txtAccountNumber");	
						$obj["name"] 				= /*inicio get post*/ $this->request->getPost("txtName");					 
						$obj["description"] 		= /*inicio get post*/ $this->request->getPost("txtDescription");
						$obj["isOperative"] 		= $txtIsOperative;
						$obj["statusID"] 			= 0;
						$obj["currencyID"] 			= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
						$obj["isActive"] 			= true;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
						
						$accountID				= $this->Account_Model->insert_app_posme($obj);
						$companyID 				= $obj["companyID"];
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_accounting_account/edit/companyID/'.$companyID."/accountID/".$accountID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_accounting_account/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_accounting_account/add');	
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
					$companyID					= $dataSession["user"]->companyID;
					$accountID 					= /*inicio get post*/ $this->request->getPost("txtAccountID");
					$objOld = $this->Account_Model->get_rowByPK($companyID,$accountID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
					
			
					if($continue){
						$db=db_connect();
						$db->transStart();					
						
						//Buscar Padre
						$objParentAccount 	= NULL;
						$accountID 			= /*inicio get post*/ $this->request->getPost("txtAccountID");
						if(/*inicio get post*/ $this->request->getPost("txtAccountNumberParent")){
							$objParentAccount		= $this->Account_Model->getByAccountNumber(/*inicio get post*/ $this->request->getPost("txtAccountNumberParent"),$dataSession["user"]->companyID);
						}
						
						//Buscar si Existe Cuenta
						$objAccount					= $this->Account_Model->getByAccountNumber(/*inicio get post*/ $this->request->getPost("txtAccountNumber"),$dataSession["user"]->companyID);
						if($objAccount){
							if($objAccount->accountID != $accountID){
								$continue 				= false;
								throw new \Exception("EL CODIGO DE LA CUENTA YA ESTA RESERVADO...");
							}
						}
						//Validar Codigo	
						if(!$this->isValidAccountNumber(/*inicio get post*/ $this->request->getPost("txtAccountNumber"),$dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtAccountLevelID"))){
							$continue 				= false;
							throw new \Exception("EL CODIGO DE LA CUENTA TIENE UN FORMATO INCORRECTO");
						}						
				
						//Validar si la cuenta puede ser operativa
						$objAccountLevel 			= $this->Account_Level_Model->get_rowByPK($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtAccountLevelID"));
						$txtIsOperative				= $this->request->getPost("txtIsOperative") ;
						$txtIsOperative				= empty($txtIsOperative) ? 0 : 1;
						if( $txtIsOperative !=  $objAccountLevel->isOperative)
						{
							$continue 				= false;
							throw new \Exception("OPERATIVIDAD DE LA CUENTA NO ES VALIDA");
						}
						
						
						//Crear Cuenta
						$companyID					= $dataSession["user"]->companyID;
						$accountID 					= /*inicio get post*/ $this->request->getPost("txtAccountID");
						$obj["accountTypeID"] 		= /*inicio get post*/ $this->request->getPost("txtAccountTypeID");
						$obj["accountLevelID"] 		= /*inicio get post*/ $this->request->getPost("txtAccountLevelID");
						$obj["parentAccountID"] 	= $objParentAccount ? $objParentAccount->accountID : NULL;
						
						$obj["classID"] 			= /*inicio get post*/ $this->request->getPost("txtClassID") ? /*inicio get post*/ $this->request->getPost("txtClassID") : NULL;
						$obj["accountNumber"] 		= /*inicio get post*/ $this->request->getPost("txtAccountNumber");	
						$obj["name"] 				= /*inicio get post*/ $this->request->getPost("txtName");					 
						$obj["description"] 		= /*inicio get post*/ $this->request->getPost("txtDescription");
						$obj["isOperative"] 		= $txtIsOperative;
						$obj["currencyID"] 			= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
						$obj["isActive"] 			= true;					
						$result 					= $this->Account_Model->update_app_posme($companyID,$accountID,$obj);
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_accounting_account/edit/companyID/'.$companyID."/accountID/".$accountID);
						
					}					
					else{
						$this->response->redirect(base_url()."/".'app_accounting_account/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_account/add');	
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
			 
			 
			 
			 
			
			
			
			$data["objListAccountLevel"] 	= $this->Account_Level_Model->getByCompany($dataSession["user"]->companyID);
			$data["objListAccountType"] 	= $this->Account_Type_Model->getByCompany($dataSession["user"]->companyID);
			$data["objListCompanyCurrency"] = $this->Company_Currency_Model->getByCompany($dataSession["user"]->companyID);
			
			$data["objListCenterCost"]		= $this->Center_Cost_Model->getByCompany($dataSession["user"]->companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account/news_head',$data);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account/news_body',$data);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account/news_script',$data);//--finview
			$dataSession["footer"]			= "";
			return  view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_account' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_account/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_account/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_account/list_script');//--finview
			$dataSession["script"] 			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>