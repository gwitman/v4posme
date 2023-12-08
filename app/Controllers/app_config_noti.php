<?php
//posme:2023-02-27
namespace App\Controllers;
use App\Libraries\core_web_google;

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
			$datView["objListTag"]					= $this->Tag_Model->get_rows();
			
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
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////	
			  			
			//Validar Formulario						
			$this->validation->setRule("txtTitulo","title","required");    
			
			 
			//Nuevo Registro			
			$continue	= true;
			if( $method == "newpublic" && $this->validation->withRequest($this->request)->run() == true )
			{
				
				
				$txtBusiness  = $this->request->getVar("txtBusiness");
				$txtBusiness  = $txtBusiness ? $txtBusiness : "default";
				try
				{
					//Validar Campos obligatorios
					if(!$this->request->getPost("txtDescription"))
					throw new \Exception("Descripcion es obligatorio.");
				
					if(!$this->request->getPost("txtNombre"))
					throw new \Exception("Nombre es obligatorio.");
				
					if(!$this->request->getPost("txtTelefono"))
					throw new \Exception("Telefono es obligatorio.");
				
					if(!$this->request->getPost("txtDate"))
					throw new \Exception("Fecha es obligatorio.");
				
					if(!$this->request->getPost("txtHora"))
					throw new \Exception("Hora es obligatorio.");
				
					if(!$this->request->getPost("txtBusiness"))
					throw new \Exception("Negocio es obligatorio.");
					
				
				
					//Validar hora disponible para el negocio en especifico
					//Valier  por negocio
					$datetime		 			= $this->request->getPost("txtDate")." ".$this->request->getPost("txtHora").":00";
					$objNotification 			= $this->Notification_Model->get_rowsWhatsappPrimerEmployeerOcupado($datetime,$txtBusiness);					
					if($objNotification)
					{	
												
						for($i = 0 ; $i < 43200 ; $i++)
						{
							
							$datetime 					= \DateTime::createFromFormat('Y-m-d H:i:s',$datetime);										
							$datetime					= date_add($datetime,date_interval_create_from_date_string('1 minutes'));								
							$datetime  					= date_format($datetime,"Y-m-d H:i:s");
							$objNotificationSugerida 	= $this->Notification_Model->get_rowsWhatsappPrimerEmployeerOcupado($datetime,$txtBusiness);		
							if(!$objNotificationSugerida)
							break;
						}
						
						throw new \Exception("No disponible en este horario </br> Hasta ".$datetime);	
					}
					
					
					
					//Buscar tag
					$objTag			= $this->Tag_Model->get_rowByName("PROXIMA VISITA");
					
					//Obtener el usuario 
					$userID				= /*inicio get post*/ $this->request->getPost("txtUserID");
					$usuario 			= $this->User_Model->get_rowByPK(APP_COMPANY,APP_BRANCH,$userID);
					
					
					//Ingresar error
					$data				= null;
					$data["notificated"]= "visita agendada";
					$data["tagID"]		= $objTag->tagID;
					$data["message"]	= /*inicio get post*/ $this->request->getPost("txtDescription");
					$data["isActive"]	= 1;
					$data["userID"]		= APP_USERADMIN;
					$data["createdOn"]	= date_format(date_create(),"Y-m-d H:i:s");					
					$errorID			= $this->Error_Model->insert_app_posme($data);
					
									
					//ingrear notificacion
					$data						= null;
					$data["errorID"]			= $errorID;
					$data["from"]				= /*inicio get post*/ $this->request->getPost("txtEmail");
					$data["phoneFrom"]			= /*inicio get post*/ $this->request->getPost("txtTelefono");
					$data["message"]			= /*inicio get post*/ $this->request->getPost("txtDescription");				
					$data["title"]				= /*inicio get post*/ $this->request->getPost("txtNombre");
					$data["programHour"]		= /*inicio get post*/ $this->request->getPost("txtHora");
					$data["programDate"]		= /*inicio get post*/ $this->request->getPost("txtDate");
					$data["to"]						= $usuario->email;
					$data["phoneTo"]				= $usuario->phone;
					$data["subject"]				= "visita agendada";
					$data["summary"]				= $txtBusiness;
					$data["tagID"]					= $objTag->tagID;
					$data["createdOn"]				= date_format(date_create(),"Y-m-d H:i:s");
					$data["isActive"]				= 1;
					$data["addedCalendarGoogle"] 	= 0;
					$this->Notification_Model->insert_app_posme($data);
					
					
					
					//enviar al propietario del calendario, la url para autoriza, el evento					
					$core_web_google = new core_web_google();
					$url 			 = $core_web_google->getRequestPermission_Posme($txtBusiness);
					
					
					$this->core_web_whatsap->sendMessageUltramsg(
						APP_COMPANY, 
						"".$data["title"]." agenda una cita con usted , pulse el siguiente link para agregar al calendario.",
						$usuario->phone
					);
					
					$this->core_web_whatsap->sendMessageUltramsg(
						APP_COMPANY, 
						$url,
						$usuario->phone
					);
					
					
					$this->core_web_notification->set_message(false,"Cita agendada: P- ".$errorID);
					$this->response->redirect(base_url()."/".'app_config_noti/eventgoogleadd?txtBusiness='.$txtBusiness);	
				}
				catch(\Exception $ex){
					$this->core_web_notification->set_message(true,"Cita error : ".$ex->getMessage());
					$this->response->redirect(base_url()."/".'app_config_noti/eventgoogleadd?txtBusiness='.$txtBusiness);	
				}	
				
			}
			else if( $method == "new" && $this->validation->withRequest($this->request)->run() == true ){
					
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
					
					//Ingresar Currency
					if($continue){
						$db=db_connect();
						$db->transStart();
						//Crear Cuenta
						$obj["companyID"]					= $dataSession["user"]->companyID;
						$obj["title"] 						= /*inicio get post*/ $this->request->getPost("txtTitulo");
						$obj["period"] 						= helper_RequestGetValue( /*inicio get post*/ $this->request->getPost("txtPeriodID"), 0 );
						$obj["day"] 						= /*inicio get post*/ $this->request->getPost("txtDias");
						$obj["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$obj["isTemporal"] 					= helper_RequestGetValue( /*inicio get post*/ $this->request->getPost("txtIsTemporal"), 0) ;
						$obj["tagID"] 						= /*inicio get post*/ $this->request->getPost("txtTagID");
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
							$errorCode 		= $db->error()["code"];
							$errorMessage 	= $db->error()["message"];							
							$this->core_web_notification->set_message(true,$errorMessage );
							$this->response->redirect(base_url()."/".'app_config_noti/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_config_noti/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->validation->withRequest($this->request)->run() == true) {
				
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
						$obj["tagID"] 						= /*inicio get post*/ $this->request->getPost("txtTagID");
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
			$dataView["objListTag"]				= $this->Tag_Model->get_rows();
			
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
	
	function eventgoogleadd()
	{
		
			//Obtener el componente Para mostrar la lista de CompanyCurrency
			$objComponent					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_remember");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_remember' NO EXISTE...");
			$dataView["component"]			= $objComponent;
			
			$business							= /*inicio get post*/ $this->request->getVar("txtBusiness");
			$business							= $business ? $business : "default";
			
			
			$dataView["business"] 				= $business;
			$dataView["objListPeriod"]			= $this->core_web_catalog->getCatalogAllItem("tb_remember","period",APP_COMPANY);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_remember","statusID",APP_COMPANY,APP_BRANCH,APP_ROL_SUPERADMIN);
			$dataView["message"]				= $this->core_web_notification->get_message_alert();
			$dataView["objListUser"]			= $this->User_Model->get_rowByFoto(/*inicio get post*/ $business );
			$dataView["objItemUser"]			= $dataView["objListUser"][0];
			
			
			$dataSession["head"]				= /*--inicio view*/ view('app_config_noti/public_news_head',$dataView);//--finview
			$dataSession["body"]				= /*--inicio view*/ view('app_config_noti/public_news_body',$dataView);//--finview
			$dataSession["script"]				= /*--inicio view*/ view('app_config_noti/public_news_script',$dataView);//--finview
			$dataSession["footer"]				= "";
			return view("core_masterpage/default_masterpage_public",$dataSession);//--finview-r
		
	}
	
	function eventgooglecalendaradded()
	{
		
		//ya medio los permisos
		$code 		= $this->request->getGet("code");		
		$state 		= $this->request->getGet("state");	
		$business	= $state;
		if($code)
		{
			
			$core_web_google = new core_web_google();
			$tocket			 = $core_web_google->getRequestToket_Posme($code);
			
			//Obtener el tag que se agregara al calendario
			$objTag			= $this->Tag_Model->get_rowByName("PROXIMA VISITA");
			$objListNoti	= $this->Notification_Model->get_rowsToAddedGoogleCalendar($objTag->tagID,$business);
			if($objListNoti)
			{
				foreach($objListNoti as $notiElement)
				{
					$business 							 = $notiElement->summary;
					$dataUpdate["addedCalendarGoogle"]   = 1;
					$this->Notification_Model->update_app_posme($notiElement->notificationID,$dataUpdate);
					
					$resultEventID	 = $core_web_google->setEvent_Posme(
						$tocket,
						$notiElement->title,
						$notiElement->message,
						$notiElement->programDate,
						$notiElement->programHour.":00",
						$notiElement->programHour.":00"
					);
			
					
				}
			}
			
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