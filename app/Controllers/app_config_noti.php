<?php
//posme:2023-02-27
//Pantalla para hacer el crud de notificaciones
//Pantalla de administracion de notificaciones
//Pantalla para el mantenimiento de notificaciones

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
					
				
				
					//wgonzalez-no aplicar-validacion-aun-//Validar hora disponible para el negocio en especifico
					//wgonzalez-no aplicar-validacion-aun-//Valier  por negocio
					//wgonzalez-no aplicar-validacion-aun-$datetime		 			= $this->request->getPost("txtDate")." ".$this->request->getPost("txtHora").":00";
					//wgonzalez-no aplicar-validacion-aun-$objNotification 			= $this->Notification_Model->get_rowsWhatsappPrimerEmployeerOcupado($datetime,$txtBusiness);					
					//wgonzalez-no aplicar-validacion-aun-if($objNotification)
					//wgonzalez-no aplicar-validacion-aun-{	
					//wgonzalez-no aplicar-validacion-aun-							
					//wgonzalez-no aplicar-validacion-aun-	for($i = 0 ; $i < 43200 ; $i++)
					//wgonzalez-no aplicar-validacion-aun-	{
					//wgonzalez-no aplicar-validacion-aun-		
					//wgonzalez-no aplicar-validacion-aun-		$datetime 					= \DateTime::createFromFormat('Y-m-d H:i:s',$datetime);										
					//wgonzalez-no aplicar-validacion-aun-		$datetime					= date_add($datetime,date_interval_create_from_date_string('1 minutes'));								
					//wgonzalez-no aplicar-validacion-aun-		$datetime  					= date_format($datetime,"Y-m-d H:i:s");
					//wgonzalez-no aplicar-validacion-aun-		$objNotificationSugerida 	= $this->Notification_Model->get_rowsWhatsappPrimerEmployeerOcupado($datetime,$txtBusiness);		
					//wgonzalez-no aplicar-validacion-aun-		if(!$objNotificationSugerida)
					//wgonzalez-no aplicar-validacion-aun-		break;
					//wgonzalez-no aplicar-validacion-aun-	}
					//wgonzalez-no aplicar-validacion-aun-	
					//wgonzalez-no aplicar-validacion-aun-	throw new \Exception("No disponible en este horario </br> Hasta ".$datetime);	
					//wgonzalez-no aplicar-validacion-aun-}
					
					
					
					//Buscar tag
					$objTag			= $this->Tag_Model->get_rowByName("LLENAR NOTI PROXIMA VISITA");
					
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
					$data["addedCalendarGoogle"] 	= 1;
					$data["quantityOcupation"] 		= 0;
					$data["quantityDisponible"] 	= 0;
					$notificationID 				= $this->Notification_Model->insert_app_posme($data);
					
					
					
					//Agregar evento al calendario
					$core_web_google = new core_web_google();
					$objUsuario		 = $this->User_Model->get_rowByFoto($txtBusiness);
					$resultEventID	 = $core_web_google->setEvent_Posme(
						$objUsuario[0]->token_google_calendar,
						$data["title"],
						$data["message"],
						$data["programDate"],
						$data["programHour"].":00",
						$data["programHour"].":00"
					);
								
					$dataNewNotification["googleCalendarEventID"]	= $resultEventID;
					$this->Notification_Model->update_app_posme($notificationID,$dataNewNotification);
					
					$this->core_web_whatsap->sendMessageUltramsg(
						APP_COMPANY, 
						"".$data["title"]." cita agregada.",
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
						$companyID 							= $dataSession["user"]->companyID;
						$obj["companyID"]					= $dataSession["user"]->companyID;
						$obj["title"] 						= /*inicio get post*/ $this->request->getPost("txtTitulo");
						$obj["period"] 						= helper_RequestGetValue( /*inicio get post*/ $this->request->getPost("txtPeriodID"), 0 );
						$obj["day"] 						= /*inicio get post*/ $this->request->getPost("txtDias");
						$obj["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$obj["isTemporal"] 					= helper_RequestGetValue( /*inicio get post*/ $this->request->getPost("txtIsTemporal"), 0) ;
						$obj["leerFile"] 					= helper_RequestGetValue( /*inicio get post*/ $this->request->getPost("txtLeerFile"), 0) ;
						$obj["tagID"] 						= /*inicio get post*/ $this->request->getPost("txtTagID");
						$obj["description"] 				= /*inicio get post*/ $this->request->getPost("txtDescripcion");
						$obj["lastNotificationOn"]			= date('Y-m-d');
						$obj["isActive"]					= 1;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
						$rememberID							= $this->Remember_Model->insert_app_posme($obj);
						
						
						//Crear la Carpeta para almacenar los Archivos del Documento
						$path_ = PATH_FILE_OF_APP."/company_".$companyID."/component_76/component_item_".$rememberID;						
						if(!file_exists ($path_)){
							mkdir($path_, 0755);
							chmod($path_, 0755);
						}
						
						
						//Generar un archivo template de ejemplo
						date_default_timezone_set(APP_TIMEZONE);						
						$objParameterCharacterSplite	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$dataSession["user"]->companyID);
						$characterSplie					= $objParameterCharacterSplite->value; 			
						$date 				= date("Y_m_d_H_i_s");			
						$pathTemplate 		= PATH_FILE_OF_APP."/company_".$companyID."/component_76/component_item_".$rememberID;
						$pathTemplate 		= $pathTemplate.'/send.csv';
						$fppathTemplate 	= fopen($pathTemplate, 'w');
						$fieldTemplate 		= ["Destino","Mensaje"];
						fputcsv($fppathTemplate, $fieldTemplate,$characterSplie);
						fclose($fppathTemplate);
						
						
						
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
						$companyID							= $dataSession["user"]->companyID;
						$rememberID							= /*inicio get post*/ $this->request->getPost("txtRememberID");
						$obj["title"] 						= /*inicio get post*/ $this->request->getPost("txtTitulo");
						$obj["period"] 						= /*inicio get post*/ $this->request->getPost("txtPeriodID");
						$obj["day"] 						= /*inicio get post*/ $this->request->getPost("txtDias");
						$obj["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$obj["tagID"] 						= /*inicio get post*/ $this->request->getPost("txtTagID");
						$obj["isTemporal"] 					= /*inicio get post*/ $this->request->getPost("txtIsTemporal");
						$obj["leerFile"] 					= helper_RequestGetValue( /*inicio get post*/ $this->request->getPost("txtLeerFile"), 0) ;
						$obj["description"] 				= /*inicio get post*/ $this->request->getPost("txtDescripcion");
						$result 			= $this->Remember_Model->update_app_posme($rememberID,$obj);						
						
						//Crear la Carpeta para almacenar los Archivos del Documento
						$path_ = PATH_FILE_OF_APP."/company_".$companyID."/component_76/component_item_".$rememberID;						
						if(!file_exists ($path_)){
							mkdir($path_, 0755,true);
							chmod($path_, 0755);
						}
						
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
	
	function eventgoogleadd()
	{
		
			//Obtener el componente Para mostrar la lista de CompanyCurrency
			$objComponent					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_remember");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_remember' NO EXISTE...");
			$dataView["component"]			= $objComponent;
			
			$business							= /*inicio get post*/ $this->request->getVar("txtBusiness");
			$business							= $business ? $business : "default";
			
			$dataView["title"] 					= "Agenda tu cita";
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
	
	
	
}
?>