<?php
//posme:2023-02-27
namespace App\Controllers;
use App\Libraries\core_mysql_dump;


class core_acount extends _BaseController {
	
	
	//Vista de Login
	function index(){
		
		//Obtener Datos 			
		$parameterSendBox = $this->core_web_parameter->getParameter("CORE_PAYMENT_SENDBOX",APP_COMPANY);		
		$parameterSendBox = $parameterSendBox->value;		
		$parameterSendBoxUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_USUARIO",APP_COMPANY);
		$parameterSendBoxUsuario = $parameterSendBoxUsuario->value;
		$parameterSendBoxClave = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_CLAVE",APP_COMPANY);
		$parameterSendBoxClave = $parameterSendBoxClave->value;
		$parameterProduccionUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_USUARIO",APP_COMPANY);
		$parameterProduccionUsuario = $parameterProduccionUsuario->value;
		$parameterProduccionClave = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_CLAVE",APP_COMPANY);
		$parameterProduccionClave = $parameterProduccionClave->value;
		$parameterPrice= $this->core_web_parameter->getParameter("CORE_CUST_PRICE",APP_COMPANY);
		$parameterPrice = $parameterPrice->value;		
		$parameterTipoPlan = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",APP_COMPANY);
		$parameterTipoPlan = $parameterTipoPlan->value;		
		$parameterLabelSistem = $this->core_web_parameter->getParameter("CORE_LABEL_SISTEMA_SUPLANTATION",APP_COMPANY);
		$parameterLabelSistem = $parameterLabelSistem->value;		
		
		
		
		//C:\xampp\teamds2\nsSystem\v4posme\system\Database\MySQLi
		//execute
		//echo "<h1 style='color: wheat;' >uso de la variable ENVIRONMENT:".print_r(ENVIRONMENT ,true)."</h1>";		
		//Renderizar		
		$data["message"]	= "";		
		$data["parameterPrice"]		= $parameterPrice;
		$data["parameterTipoPlan"]	= $parameterTipoPlan;
		$data["parameterLabelSistem"]	= $parameterLabelSistem;
		return view('core_acount/login',$data);//--finview-r
		
	
			
	}		
	
	function logout(){
		try{																
			
			
			$dataSession					= $this->session->get();	
			$companyID 						= APP_COMPANY;
			$this->core_web_authentication->destroyLogin();			
			
			
			
			$objComponentSeguridad			= $this->core_web_tools->getComponentIDBy_ComponentName("0-SEGURIDAD");
			if(!$objComponentSeguridad)
			throw new \Exception("EL COMPONENTE '0-SEGURIDAD' NO EXISTE...");
			
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentSeguridad->componentID."/component_item_0";						
			chmod($documentoPath, 0755);
			
			$core_mysql_dump	= new core_mysql_dump("mysql:host=".DB_SERVER.";dbname=".DB_BDNAME,DB_USER,DB_PASSWORD);
			$core_mysql_dump->start($documentoPath."/".DB_BDNAME."_DB_".date("Ymd_H_i_s").".sql");
			
			$this->response->redirect(base_url());
			
						
		}
		catch(\Exception $e){				
			
			show_error($e->getMessage() ,500 );
		}
	}
	
	function login(){
		
		
		
		
		try{ 
			
			if(!isset($_POST["txtNickname"]) || !isset($_POST["txtPassword"]))
			throw new \Exception(NOT_VALID_USER);
		
			
			
			
			$nickname 				= $_POST["txtNickname"];
			$password				= $_POST["txtPassword"];
			$pagoCantidadDeMeses	= isset($_POST["txtNickname"]) ?  $_POST["txtPagarCantidadDe"] : 0;
			$pagoCantidadDeMeses	= $pagoCantidadDeMeses != null  ?  $pagoCantidadDeMeses : 0;
			$pagoCantidadDeMeses	= $pagoCantidadDeMeses != ""  ?  $pagoCantidadDeMeses : 0;
			
			$objUser	= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname,$password);				
			$data 		= $this->core_web_authentication->createLogin($objUser);
			$dataSession		= $this->session->get();	
			$dataSession		= $this->session->get(); 
			
			
			//Obtener Datos 				
			$parameterCantidadTransacciones 	= $this->core_web_parameter->getParameter("CORE_QUANTITY_TRANSACCION",$objUser["user"]->companyID);
			$parameterCantidadTransacciones 	= $parameterCantidadTransacciones->value;
		
			$parameterBalance = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE",$objUser["user"]->companyID);
			$parameterBalance = $parameterBalance->value;
			$parameterSendBox = $this->core_web_parameter->getParameter("CORE_PAYMENT_SENDBOX",$objUser["user"]->companyID);
			$parameterSendBox = $parameterSendBox->value;
			$parameterSendBoxUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_USUARIO",$objUser["user"]->companyID);
			$parameterSendBoxUsuario = $parameterSendBoxUsuario->value;
			$parameterSendBoxClave = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_CLAVE",$objUser["user"]->companyID);
			$parameterSendBoxClave = $parameterSendBoxClave->value;
			$parameterProduccionUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_USUARIO",$objUser["user"]->companyID);
			$parameterProduccionUsuario = $parameterProduccionUsuario->value;
			$parameterProduccionClave = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_CLAVE",$objUser["user"]->companyID);
			$parameterProduccionClave = $parameterProduccionClave->value;
			$parameterPrice				= $this->core_web_parameter->getParameter("CORE_CUST_PRICE",$objUser["user"]->companyID);
			$parameterPrice 			= $parameterPrice->value;
			$parameterTipoPlan 			= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",$objUser["user"]->companyID);
			$parameterTipoPlan 			= $parameterTipoPlan->value;
			$pagoCantidadMonto		  	= $pagoCantidadDeMeses * $parameterPrice;
			
			//Procesar Pago
			if($pagoCantidadMonto > 0 )
			{
				$this->response->redirect(base_url()."/"."core_acount/payment/pagoCantidadDeMeses/".$pagoCantidadDeMeses);
			}
			//Validar Fecha de Expiracion
			$objCompany 	= $this->Company_Model->get_rowByPK($objUser["user"]->companyID);
			
			//Set Variables			
			$params_["nickname"]								= $nickname;
			$params_["objCompany"]								= $objCompany;
			$params_["parameterBalance"]						= $parameterBalance;
			$params_["parameterCantidadDeTransacciones"]		= $parameterCantidadTransacciones;
			$params_["mensaje"]									= "Su balance de uso es:".$parameterBalance.", Cantidad de Transacciones:".$parameterCantidadTransacciones;
			
			set_cookie("userID",$dataSession['user']->userID,86400,"localhost");
			set_cookie("nickname",$dataSession['user']->nickname,86400,"localhost");
			set_cookie("email",$dataSession['user']->email,86400,"localhost");			
			
	
			$subject 	= "Inicio de session: ".$objCompany->name." ".$nickname;
			$body  		= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview
			
			
			$this->email->setFrom(EMAIL_APP);
			$this->email->setTo(EMAIL_APP_COPY);
			$this->email->setSubject($subject);			
			$this->email->setMessage($body); 
			
			$resultSend01 = $this->email->send();
			$resultSend02 = $this->email->printDebugger();
						
			$this->response->redirect(base_url()."/".$objUser["role"]->urlDefault."/index");
			
			
		
		}
		catch(\Exception $e){				
			
			$parameterSendBox = $this->core_web_parameter->getParameter("CORE_PAYMENT_SENDBOX",APP_COMPANY);		
			$parameterSendBox = $parameterSendBox->value;			
			$parameterSendBoxUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_USUARIO",APP_COMPANY);
			$parameterSendBoxUsuario = $parameterSendBoxUsuario->value;
			$parameterSendBoxClave = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_CLAVE",APP_COMPANY);
			$parameterSendBoxClave = $parameterSendBoxClave->value;
			$parameterProduccionUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_USUARIO",APP_COMPANY);
			$parameterProduccionUsuario = $parameterProduccionUsuario->value;
			$parameterProduccionClave = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_CLAVE",APP_COMPANY);
			$parameterProduccionClave = $parameterProduccionClave->value;
			$parameterPrice= $this->core_web_parameter->getParameter("CORE_CUST_PRICE",APP_COMPANY);
			$parameterPrice = $parameterPrice->value;		
			$parameterTipoPlan = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",APP_COMPANY);
			$parameterTipoPlan = $parameterTipoPlan->value;		
			$parameterLabelSistem = $this->core_web_parameter->getParameter("CORE_LABEL_SISTEMA_SUPLANTATION",APP_COMPANY);
			$parameterLabelSistem = $parameterLabelSistem->value;	
		
			//Renderizar					
			$data_login["message"]				= $e->getMessage();
			$data_login["parameterPrice"]		= $parameterPrice;
			$data_login["parameterTipoPlan"]	= $parameterTipoPlan;
			$data_login["parameterLabelSistem"]	= $parameterLabelSistem;
			return view('core_acount/login',$data_login);//--finview-r
			
		}
		
	}
	
	function rememberpassword(){
		
		try{ 
			if(!isset($_POST["txtEmail"]))
			throw new \Exception(NOT_VALID_EMAIL);
			
			$email 						= $_POST["txtEmail"];
			$objUser					=$this->core_web_authentication->get_UserBy_Email($email);
			
			
			$this->email->setFrom(EMAIL_APP, HELLOW);
			$this->email->setTo($objUser["user"]->email);
			$this->email->setSubject(REMEMBER_PASSWORD);
			$mensaje = /*--inicio view*/ view('core_template/email_remember_password',$objUser["user"]);//--finview
			$this->email->setMessage($mensaje); 
			//$this->email->send();
			
			//Notificar
			$data_message["txtMessage"]	= MESSAGE_EMAL;
			$data_login["message"]		= "";
			$data_login["status"]		= "PASSWORD REENVIADA";
			return view('core_acount/login',$data_login);//--finview-r
		}
		catch(\Exception $e){
			$data_message["txtMessage"]	= $e->getMessage();
			$data_login["message"]		= $data_message["txtMessage"];
			return view('core_acount/login',$data_login);//--finview-r
		}
	}
	
	function edit(){
	 
		try{
			
			//Cargar Modelo
			 			
			
			
			
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
			 
			//Obtener Datos 
			$objUser["branchID"] 				= $dataSession["user"]->branchID;
			$objUser["companyID"] 				= $dataSession["user"]->companyID;
			$objUser["userID"] 					= $dataSession["user"]->userID;
			$objUser["nickname"] 				= $dataSession["user"]->nickname;
			$objUser["email"] 					= $dataSession["user"]->email;
			$objUser["password"] 				= $dataSession["user"]->password;						
			$objUser["employeeID"] 				= $dataSession["user"]->employeeID;			
			$continue							= true;  	    
			
			//Datos Requerido para que el Usuario pueda ser Seleccionado
			$this->validation->setRule("txtEmail","Email Required","required");    
			$this->validation->setRule("txtPassword","Password Required","required");    
			$this->validation->setRule("txtNickname","Nickname Required","required");    
			 
			//Actualizar  
			if ($this->validation->withRequest($this->request)->run() == true && $continue) { 
				$objUser["email"] 					= /*inicio get post*/ $this->request->getPost('txtEmail');
				$objUser["password"] 				= /*inicio get post*/ $this->request->getPost('txtPassword');
				$objUser["nickname"] 				= /*inicio get post*/ $this->request->getPost('txtNickname');
					 
				//validar nickname
				$objUserTmp = $this->User_Model->get_rowByExistNickname($objUser["nickname"]);
				if($objUserTmp &&  $objUserTmp->userID != $dataSession["user"]->userID && $continue )
				{
					$continue	= false;
					$this->core_web_notification->set_message(true,NICKNAME_DUPLI);
				}   
				
				//validar email
				$objUserTmp = $this->User_Model->get_rowByEmail($objUser["email"]);
				if($objUserTmp &&  $objUserTmp->userID != $dataSession["user"]->userID && $continue)
				{ 
					$continue	= false;
					$this->core_web_notification->set_message(true,EMAIL_DUPLI);				
				} 
			 
				//actualizar
				$isSuccess 						= $this->User_Model->update_app_posme($objUser["companyID"],$objUser["branchID"],$objUser["userID"],$objUser);				 				
				if($isSuccess){					
					$this->core_web_notification->set_message(false,SUCCESS);
				}
				else{ 
					$this->core_web_notification->set_message(true,ERROR);
				}  
				
			}
			
			//Obtener Entidad
			$objUser["objEntity"]				= $this->Entity_Model->get_rowByEntity($objUser["companyID"],$objUser["employeeID"]);
			//Obtener Empleado
			$objUser["objEmployee"]				= $objUser["objEntity"] == null ? null : $this->Employee_Model->get_rowByPK($objUser["objEntity"]->companyID,$objUser["objEntity"]->branchID,$objUser["objEntity"]->entityID);
			//Obtener El Natural
			$objUser["objEmployeeNatural"]		= $objUser["objEntity"] == null ? null : $this->Natural_Model->get_rowByPK($objUser["objEntity"]->companyID,$objUser["objEntity"]->branchID,$objUser["objEntity"]->entityID);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('core_acount/edit_head');//--finview			
			$dataSession["body"]			= /*--inicio view*/ view('core_acount/edit_body',$objUser);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('core_acount/edit_script');//--finview 
			$dataSession["footer"]			= "";   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
			
		}
		catch(\Exception $ex){
				show_error($ex->getMessage() ,500 );
		}
			 
	}
	
	
	function payment(){
		
		
		//Funcion que genera el pago 
		//Mandar a ejecutar el pago en pagadito
		//POS					= APLICACIO DE POSME
		//NUMMBER				= FLAVOR DEL CLIENTE
		//FECHA					= FECHA DEL PAGO 
		//NUMERO DE FACTURA 	= POS__NUMBER__FECHA
		//EL RETORNO DE LA APLICACION SE HACE EN LA SIGUIENTE URL 
		//core_user/payment_user_back
		
		
		//AUTENTICACION
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession			= $this->session->get();
								
		
		//Obtener Datos
		$pagoCantidadDeMeses		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"pagoCantidadDeMeses");//--finuri
		$parameterSendBox 			= $this->core_web_parameter->getParameter("CORE_PAYMENT_SENDBOX",$dataSession["user"]->companyID);
		$parameterSendBox 			= $parameterSendBox->value;
		$parameterSendBoxUsuario 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_USUARIO",$dataSession["user"]->companyID);
		$parameterSendBoxUsuario 	= $parameterSendBoxUsuario->value;
		$parameterSendBoxClave 		= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_CLAVE",$dataSession["user"]->companyID);
		$parameterSendBoxClave 		= $parameterSendBoxClave->value;
		$parameterProduccionUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_USUARIO",$dataSession["user"]->companyID);
		$parameterProduccionUsuario = $parameterProduccionUsuario->value;
		$parameterProduccionClave 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_CLAVE",$dataSession["user"]->companyID);
		$parameterProduccionClave 	= $parameterProduccionClave->value;
		$parameterPrice				= $this->core_web_parameter->getParameter("CORE_CUST_PRICE",$dataSession["user"]->companyID);
		$parameterPrice 			= $parameterPrice->value;
		$parameterTipoPlan 			= $this->core_web_parameter->getParameter("CORE_CUST_PRICE_TIPO_PLAN",$dataSession["user"]->companyID);
		$parameterTipoPlan 			= $parameterTipoPlan->value;
		$parameterTemporal1 = $this->core_web_parameter->getParameter("CORE_TEMPORAL001",$dataSession["user"]->companyID);
		$parameterTemporal2 = $this->core_web_parameter->getParameter("CORE_TEMPORAL002",$dataSession["user"]->companyID);
		$parameterTemporal3 = $this->core_web_parameter->getParameter("CORE_TEMPORAL003",$dataSession["user"]->companyID);
		
		
		$parameterFechaExpiration = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_LICENCES_EXPIRED",$dataSession["user"]->companyID);
		$parameterFechaExpiration = $parameterFechaExpiration->value;
		$parameterFechaExpiration = \DateTime::createFromFormat('Y-m-d',$parameterFechaExpiration);			
		$pagoCantidadMonto		  = $pagoCantidadDeMeses * $parameterPrice;
		$fechaNow  				  = \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));
		
		
		$uidt 			= "";
		$wskt 			= "";
		$urlProducto 	= "localhost/posme";
		$sendBox 		= $parameterSendBox == "true"? true : false;
		$cantidad 		= $pagoCantidadDeMeses;
		$precio  		= $pagoCantidadMonto;
		if($sendBox){
			$uidt = $parameterSendBoxUsuario;
			$wskt = $parameterSendBoxClave;
		}
		else
		{
			$uidt = $parameterProduccionUsuario;
			$wskt = $parameterProduccionClave;
		}
		
		
		
	}
	
	
}
