<?php
//posme:2023-02-27
namespace App\Controllers;
use App\Libraries\core_mysql_dump;



class core_acount extends _BaseController {
	
	
	//Vista de Login
	function index(){
		
		
		//Si esta autenticado, solo redirigir
		//----------------------------------------------
        if($this->core_web_authentication->isAuthenticated())
		{
			$dataSession	= $this->session->get();
			if (array_key_exists("lastUrl", $dataSession)) 
			{				
				if($dataSession["lastUrl"] != "")
				{
					if($dataSession["company"]->type == "emanuel")
					{
						$this->response->redirect($dataSession["lastUrl"]);
					}
					if($dataSession["company"]->type == "magiBarbershop")
					{
						$this->response->redirect(base_url()."/app_invoice_billing/add/codigoMesero/none");
					}
					else
					{
					
						////Obtener Datos 				
						$parameterCantidadTransacciones 	= $this->core_web_parameter->getParameter("CORE_QUANTITY_TRANSACCION",$dataSession["user"]->companyID);
						$parameterCantidadTransacciones 	= $parameterCantidadTransacciones->value;
						
						$parameterBalance = $this->core_web_parameter->getParameter("CORE_CUST_PRICE_BALANCE",$dataSession["user"]->companyID);
						$parameterBalance = $parameterBalance->value;
						
						$parameterCORE_PROPIETARY_ADDRESS 			= $this->core_web_parameter->getParameter("CORE_PROPIETARY_ADDRESS",$dataSession["user"]->companyID);
						$parameterCORE_PROPIETARY_ADDRESS 			= $parameterCORE_PROPIETARY_ADDRESS->value;
						$parameterCORE_PROPIETARY_PHONE 			= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$dataSession["user"]->companyID);
						$parameterCORE_PROPIETARY_PHONE 			= $parameterCORE_PROPIETARY_PHONE->value;
						$parameterCORE_PHONE 						= $this->core_web_parameter->getParameter("CORE_PHONE",$dataSession["user"]->companyID);
						$parameterCORE_PHONE 						= $parameterCORE_PHONE->value;
						$parameterCORE_COMPANY_IDENTIFIER 			= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$dataSession["user"]->companyID);
						$parameterCORE_COMPANY_IDENTIFIER 			= $parameterCORE_COMPANY_IDENTIFIER->value;
						$parameterCORE_PROPIETARY_EMAIL 			= $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL",$dataSession["user"]->companyID);
						$parameterCORE_PROPIETARY_EMAIL 			= $parameterCORE_PROPIETARY_EMAIL->value;
						$parameterCORE_PROPIETARY_NAME 				= $this->core_web_parameter->getParameter("CORE_PROPIETARY_NAME",$dataSession["user"]->companyID);
						$parameterCORE_PROPIETARY_NAME 				= $parameterCORE_PROPIETARY_NAME->value;
						
						//Validar Fecha de Expiracion
						$objCompany 										= $dataSession["company"];
						$nickname											= $dataSession["user"]->nickname;
						//Set Variables			
						$params_["nickname"]								= $nickname;
						$params_["objCompany"]								= $objCompany;
						$params_["parameterBalance"]						= $parameterBalance;
						$params_["parameterCantidadDeTransacciones"]		= $parameterCantidadTransacciones;
						$params_["mensaje"]									= "Su balance de uso es:".$parameterBalance.", Cantidad de Transacciones: ".$parameterCantidadTransacciones;
						$params_["mensaje"]									= $params_["mensaje"]." </br>".
								" Parametro Direccion: ".$parameterCORE_PROPIETARY_ADDRESS."</br>".
								" Parametro Telefono: ".$parameterCORE_PROPIETARY_PHONE."</br>".
								" Parametro Telefono 2: ".$parameterCORE_PHONE."</br>".
								" Parametro Ruc: ".$parameterCORE_COMPANY_IDENTIFIER."</br>".
								" Parametro Email: ".$parameterCORE_PROPIETARY_EMAIL."</br>".
								" Parametro Propietario: ".$parameterCORE_PROPIETARY_NAME."</br>";
				
						$this->response->redirect($dataSession["lastUrl"]);
					}
				}
			}
		}
			
			
			
			
		//Mostrar pantalla de login
		//----------------------------------------------	
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
		$parameterLabelSistem 		= $this->core_web_parameter->getParameter("CORE_LABEL_SISTEMA_SUPLANTATION",APP_COMPANY);
		$parameterLabelSistem 		= $parameterLabelSistem->value;
		$boll_ 						= $this->core_web_parameter->getParameter("CORE_TRAKING_GPS",APP_COMPANY);
		$boll_ 						= $boll_->value;
		$objCompany					= $this->Company_Model->get_rowByPK(APP_COMPANY);
		
		
		//C:\xampp\teamds2\nsSystem\v4posme\system\Database\MySQLi\Connection\execute
		//execute
		//echo "<h1 style='color: wheat;' >uso de la variable ENVIRONMENT:".print_r(ENVIRONMENT ,true)."</h1>";		
		//Renderizar		
		$data["message"]	= "";
		$data["parameterPrice"]			= $parameterPrice;
		$data["parameterTipoPlan"]		= $parameterTipoPlan;
		$data["parameterLabelSistem"]	= $parameterLabelSistem;
		$data["boll_"]					= $boll_;
		$data["objCompany"]				= $objCompany;

		//Recordarme: solo el usuario viaja a la vista, la contraseña queda en la cookie cifrada
		$objRemember					= $this->core_web_authentication->rememberLoginGet();
		$data["rememberNickname"]		= $objRemember ? $objRemember["nickname"] : "";
		$data["rememberActive"]			= $objRemember ? true : false;
		return view('core_acount/login',$data);//--finview-r
	}
	
	function logout(){
		try
		{																
			
			$dataSession					= $this->session->get();	
			$type							= $dataSession["company"]->type;
			$companyID 						= APP_COMPANY;
			
			
			$objComponentSeguridad			= $this->core_web_tools->getComponentIDBy_ComponentName("0-SEGURIDAD");
			if(!$objComponentSeguridad)
			throw new \Exception("EL COMPONENTE '0-SEGURIDAD' NO EXISTE...");
			
			
			//wgonzalez-desable--$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentSeguridad->componentID."/component_item_0";	
			//wgonzalez-desable--if (!file_exists($documentoPath)) 
			//wgonzalez-desable--{
			//wgonzalez-desable--	// Crear el directorio con permisos 0755 (y recursivo por si faltan subdirectorios)
			//wgonzalez-desable--	if (!mkdir($documentoPath, 0755, true)) 
			//wgonzalez-desable--	{
			//wgonzalez-desable--		throw new \Exception("❌ No se pudo crear el directorio: $documentoPath");
			//wgonzalez-desable--	}
			//wgonzalez-desable--}
			//wgonzalez-desable--
			//wgonzalez-desable--chmod($documentoPath, 0755);
			//wgonzalez-desable--
			//wgonzalez-desable--$core_mysql_dump	= new core_mysql_dump("mysql:host=".DB_SERVER.";dbname=".DB_BDNAME,DB_USER,DB_PASSWORD);
			//wgonzalez-desable--$sqlBacku           = $documentoPath."/".DB_BDNAME."_DB_".date("Ymd_H_i_s").".sql";
			//wgonzalez-desable--$core_mysql_dump->start($sqlBacku);
			
			
			//wgonzalez-desable--//Mandar respaldo
			//wgonzalez-desable--if($type=="globalpro")
			//wgonzalez-desable--{				
			//wgonzalez-desable--	$ch 				= curl_init();								
			//wgonzalez-desable--	$objParameterUrlServerFile 					= $this->core_web_parameter->getParameter("CORE_FILE_SERVER",$companyID);
			//wgonzalez-desable--	$objParameterUrlServerFile 					= $objParameterUrlServerFile->value;				
			//wgonzalez-desable--	$urlCreateFolder 							= $objParameterUrlServerFile."/core_elfinder/createFolder/companyID/".$companyID."/componentID/".$objComponentSeguridad->componentID."/transactionID/0/transactionMasterID/0";
			//wgonzalez-desable--	$filePathDetination							= $sqlBacku;			
			//wgonzalez-desable--	curl_setopt($ch, CURLOPT_URL, $urlCreateFolder);
			//wgonzalez-desable--	curl_setopt($ch, CURLOPT_HEADER, 0);
			//wgonzalez-desable--	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			//wgonzalez-desable--	curl_setopt($ch, CURLOPT_POSTFIELDS, array('txtFileDocument' => curl_file_create($filePathDetination) ));
			//wgonzalez-desable--	curl_exec($ch);				
			//wgonzalez-desable--	curl_close($ch);
			//wgonzalez-desable--	
			//wgonzalez-desable--}
			
			
			
			//Guardar Log
			$this->Log_Session_Model->delete_app_posme("userID",$dataSession["user"]->userID);
			$this->core_web_authentication->destroyLogin();
			$this->response->redirect(base_url());
			
						
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
	
	function login(){
		
		
		
		
		try{ 
			
			
			if(!isset($_POST["txtNickname"]) || !isset($_POST["txtPassword"]))
			throw new \Exception(NOT_VALID_USER);
		
			
			
			
			$nickname 				= $_POST["txtNickname"];
			$password				= $_POST["txtPassword"];
			$pagoCantidadDeMeses	= isset($_POST["txtNickname"]) ?  $_POST["txtPagarCantidadDe"] : 0;
			$pagoCantidadDeMeses	= $pagoCantidadDeMeses != null  ?  $pagoCantidadDeMeses : 0;
			$pagoCantidadDeMeses	= $pagoCantidadDeMeses != ""  ?  $pagoCantidadDeMeses : 0;

			//Recordarme: si el formulario usa la contraseña guardada, se toma de la cookie cifrada
			if($this->request->getPost("txtRememberUse") == "1")
			{
				$objRemember = $this->core_web_authentication->rememberLoginGet();
				if($objRemember && $objRemember["nickname"] == $nickname)
				$password = $objRemember["password"];
			}

			$objUser	= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname,$password);

			//Recordarme: guardar o borrar segun el checkbox
			if($this->request->getPost("remember") != null)
			$this->core_web_authentication->rememberLoginSave($nickname,$password);
			else
			$this->core_web_authentication->rememberLoginClear();

			//Autorizacion de ingreso (CORE_AUTHENTICATE_BY_EMAIL): la sesion NO se crea hasta que el administrador autorice
			$destinatariosAutorizacion	= $this->loginAuthorizationRecipients($objUser);
			if(count($destinatariosAutorizacion) > 0)
			return $this->loginAuthorizationRequest($objUser,$destinatariosAutorizacion);

			$data 				= $this->core_web_authentication->createLogin($objUser);
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
			
			
			$parameterCORE_PROPIETARY_ADDRESS 			= $this->core_web_parameter->getParameter("CORE_PROPIETARY_ADDRESS",$objUser["user"]->companyID);
			$parameterCORE_PROPIETARY_ADDRESS 			= $parameterCORE_PROPIETARY_ADDRESS->value;
			$parameterCORE_PROPIETARY_PHONE 			= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$objUser["user"]->companyID);
			$parameterCORE_PROPIETARY_PHONE 			= $parameterCORE_PROPIETARY_PHONE->value;
			$parameterCORE_PHONE 						= $this->core_web_parameter->getParameter("CORE_PHONE",$objUser["user"]->companyID);
			$parameterCORE_PHONE 						= $parameterCORE_PHONE->value;
			$parameterCORE_COMPANY_IDENTIFIER 			= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$objUser["user"]->companyID);
			$parameterCORE_COMPANY_IDENTIFIER 			= $parameterCORE_COMPANY_IDENTIFIER->value;
			$parameterCORE_PROPIETARY_EMAIL 			= $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL",$objUser["user"]->companyID);
			$parameterCORE_PROPIETARY_EMAIL 			= $parameterCORE_PROPIETARY_EMAIL->value;
			$parameterCORE_PROPIETARY_NAME 				= $this->core_web_parameter->getParameter("CORE_PROPIETARY_NAME",$objUser["user"]->companyID);
			$parameterCORE_PROPIETARY_NAME 				= $parameterCORE_PROPIETARY_NAME->value;

			//Procesar Pago
			if($pagoCantidadMonto > 0 )
			{
				return $this->response->redirect(base_url()."/"."core_acount/payment/pagoCantidadDeMeses/".$pagoCantidadDeMeses);
			}
			//Validar Fecha de Expiracion
			$objCompany 	= $this->Company_Model->get_rowByPK($objUser["user"]->companyID);
			
			//Set Variables			
			$params_["nickname"]								= $nickname;
			$params_["objCompany"]								= $objCompany;
			$params_["parameterBalance"]						= $parameterBalance;
			$params_["parameterCantidadDeTransacciones"]		= $parameterCantidadTransacciones;
			$params_["mensaje"]									= "Su balance de uso es:".$parameterBalance.", Cantidad de Transacciones: ".$parameterCantidadTransacciones;
			$params_["mensaje"]									= $params_["mensaje"]." </br>".
					" Parametro Direccion: ".$parameterCORE_PROPIETARY_ADDRESS."</br>".
					" Parametro Telefono: ".$parameterCORE_PROPIETARY_PHONE."</br>".
					" Parametro Telefono 2: ".$parameterCORE_PHONE."</br>".
					" Parametro Ruc: ".$parameterCORE_COMPANY_IDENTIFIER."</br>".
					" Parametro Email: ".$parameterCORE_PROPIETARY_EMAIL."</br>".
					" Parametro Propietario: ".$parameterCORE_PROPIETARY_NAME."</br>";
					
					
			set_cookie("userID",$dataSession['user']->userID,86400,"localhost");
			set_cookie("nickname",$dataSession['user']->nickname,86400,"localhost");
			set_cookie("email",$dataSession['user']->email,86400,"localhost");

			//Pantalla de inicio: la última visitada por el usuario o la del rol
			$urlInicio	= $this->core_web_authentication->lastScreenGet($objUser["user"]);
			$urlInicio	= $urlInicio ? $urlInicio : base_url()."/".$objUser["role"]->urlDefault."/index";

			if($objCompany->type == "emanuel")
			{
				$this->response->redirect($urlInicio);
			}
			else
			{
				//Guardar Log		
				$this->Log_Session_Model->delete_app_posme("userID",$dataSession["user"]->userID);					
				$objLogSessionModel["session_id"] 		= $dataSession["sessionID"];
				$objLogSessionModel["ip_address"] 		= $this->request->getIPAddress();
				$objLogSessionModel["user_agent"] 		= $this->request->getUserAgent()->getPlatform();
				$objLogSessionModel["last_activity"] 	= \DateTime::createFromFormat("Y-m-d H:i:s",helper_getDateTime())->format("YmdHis");
				$objLogSessionModel["user_data"] 		= $objUser["user"]->nickname." create session";
				$objLogSessionModel["userID"]			= $dataSession["user"]->userID;				
				$this->Log_Session_Model->insert($objLogSessionModel);
				$this->response->redirect($urlInicio);
			}
			
		
		}
		catch(\Exception $e){				
			
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
			$parameterLabelSistem 		= $this->core_web_parameter->getParameter("CORE_LABEL_SISTEMA_SUPLANTATION",APP_COMPANY);
			$parameterLabelSistem 		= $parameterLabelSistem->value;	
			$objCompany					= $this->Company_Model->get_rowByPK(APP_COMPANY);
			$boll_ 						= $this->core_web_parameter->getParameter("CORE_TRAKING_GPS",APP_COMPANY);
			$boll_ 						= $boll_->value;

			//Renderizar					
			$data_login["message"]				= $e->getMessage();
			$data_login["parameterPrice"]		= $parameterPrice;
			$data_login["parameterTipoPlan"]	= $parameterTipoPlan;
			$data_login["parameterLabelSistem"]	= $parameterLabelSistem;
			$data_login["objCompany"]			= $objCompany;
			$data_login["boll_"]				= $boll_;

			//Recordarme: si fallo usando la contraseña guardada (p. ej. la cambiaron), se borra
			$objRemember						= $this->core_web_authentication->rememberLoginGet();
			if($objRemember && $this->request->getPost("txtRememberUse") == "1")
			{
				$this->core_web_authentication->rememberLoginClear();
				$objRemember = null;
			}
			$data_login["rememberNickname"]		= $objRemember ? $objRemember["nickname"] : "";
			$data_login["rememberActive"]		= $objRemember ? true : false;
			return view('core_acount/login',$data_login);//--finview-r
			
		}
		
	}
	
	function loginMobile()
	{	
		
		try
		{ 
			
			$nickname	= /*inicio get post*/ $this->request->getPost("txtNickname");			
			$password	= /*inicio get post*/ $this->request->getPost("txtPassword");	
			$objUser	= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname,$password);
			
			
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
			
			//Validar Fecha de Expiracion
			$objCompany 	= $this->Company_Model->get_rowByPK($objUser["user"]->companyID);
			
			
			//Enviar mensaje				
			$params_["nickname"]								= $nickname;
			$params_["objCompany"]								= $objCompany;
			$params_["parameterBalance"]						= $parameterBalance;
			$params_["parameterCantidadDeTransacciones"]		= $parameterCantidadTransacciones;
			$params_["mensaje"]									= "Su balance de uso es:".$parameterBalance.", Cantidad de Transacciones:".$parameterCantidadTransacciones;
			
			
			//Guardar Log			
			$objLogSessionModel["session_id"] 		= session_id();
			$objLogSessionModel["ip_address"] 		= $this->request->getIPAddress();
			$objLogSessionModel["user_agent"] 		= $this->request->getUserAgent()->getPlatform();
			$objLogSessionModel["last_activity"] 	= \DateTime::createFromFormat("Y-m-d H:i:s",helper_getDateTime())->format("YmdHis");
			$objLogSessionModel["user_data"] 		= $objUser["user"]->nickname." create session";
			
			
			$objLogSessionModel2 					= $this->Log_Session_Model->asObject()->where("session_id",$objLogSessionModel["session_id"])->find();
			if(!$objLogSessionModel2)			
			$this->Log_Session_Model->insert($objLogSessionModel);			
			else 			
			$this->Log_Session_Model->upsert($objLogSessionModel);
		
			return $this->response->setJSON(array(
				'error'   	=> false,
				'message' 	=> SUCCESS,			
				'objUser'  	=> $objUser["user"]
			));//--finjson			
		
			
		
		}
		catch(\Exception $ex)
		{							
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));
			
		}
		
	}
	
	/**
	 * Recuperar contraseña desde el modal del login: busca el usuario por correo
	 * y le envía sus datos de acceso. Responde JSON {error, message}.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function rememberpassword(){

		try{
			$email = trim((string)$this->request->getPost("txtEmail"));
			if($email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL))
			throw new \Exception("Escribe un correo válido, por ejemplo usuario@empresa.com");

			try{
				$objUser = $this->core_web_authentication->get_UserBy_Email($email);
			}
			catch(\Exception $ex){
				if(strpos($ex->getMessage(),"EMAIL INCORRECTO") === 0)
				throw new \Exception("No encontramos un usuario activo con ese correo.");
				throw $ex;
			}

			$parameterLabelSistem 		= $this->core_web_parameter->getParameter("CORE_LABEL_SISTEMA_SUPLANTATION",APP_COMPANY);
			$parameterLabelSistem 		= $parameterLabelSistem->value;

			$dataEmail["nickname"]		= $objUser["user"]->nickname;
			$dataEmail["password"]		= $objUser["user"]->password;
			$dataEmail["email"]			= $objUser["user"]->email;
			$dataEmail["companyName"]	= $objUser["company"]->name;
			$dataEmail["labelSistema"]	= $parameterLabelSistem;
			$dataEmail["urlLogin"]		= base_url();
			$dataEmail["fecha"]			= \DateTime::createFromFormat("Y-m-d H:i:s",helper_getDateTime())->format("d/m/Y h:i A");

			//Envio inmediato: el usuario espera la respuesta en el modal
			$mensaje = /*--inicio view*/ view('core_template/email_remember_password',$dataEmail);//--finview
			if(!$this->core_web_email->sendNow($objUser["user"]->companyID,$objUser["user"]->email,REMEMBER_PASSWORD,$mensaje,array("from" => EMAIL_APP, "fromName" => HELLOW)))
			throw new \Exception("No se pudo enviar el correo. Intenta de nuevo en unos minutos.");

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => MESSAGE_EMAL
			));//--finjson
		}
		catch(\Exception $e){
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $e->getMessage()
			));//--finjson
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
	
	
	function payment(){
		
		
		//Funcion que genera el pago 
		//Mandar a ejecutar el pago en pagadito
		//POS					= APLICACIO DE POSME
		//NUMMBER				= FLAVOR DEL CLIENTE
		//FECHA					= FECHA DEL PAGO 
		//NUMERO DE FACTURA 	= POS__NUMBER__FECHA
		//EL RETORNO DE LA APLICACION SE HACE EN LA SIGUIENTE URL 
		//core_user/payment_user_back
		
		
		try
		{
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession			= $this->session->get();
			$companyID				= $dataSession["user"]->companyID;

			//Obtener Datos
			$pagoCantidadDeMeses	= (int) /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"pagoCantidadDeMeses");//--finuri
			$parameterPrice			= (float) $this->core_web_parameter->getParameterValue("CORE_CUST_PRICE",$companyID);
			if($pagoCantidadDeMeses <= 0)
			throw new \Exception("Indique una cantidad de meses válida para pagar la licencia.");

			//Pagadito multiplica cantidad x precio: el precio unitario es el de un mes (CORE_CUST_PRICE)
			$flavorID 	= !empty($dataSession["company"]->flavorID) ? $dataSession["company"]->flavorID : $companyID;
			$ern 		= "POS__".$flavorID."__".date("YmdHis");
			$payment 	= $this->core_web_payment->getPaymentUrl(
				$companyID,
				$ern,
				"Licencia posMe ".$pagoCantidadDeMeses." mes(es)",
				$pagoCantidadDeMeses,
				$parameterPrice,
				base_url()
			);

			log_message("info","[core_acount::payment] companyID: ".$companyID." | ern: ".$ern." | meses: ".$pagoCantidadDeMeses);
			return redirect()->to($payment["url"]);
		}
		catch(\Throwable $ex)
		{
			//core_user/payment es publica y muestra la notificacion; es la misma pantalla de retorno de los pagos POS
			log_message("error","[core_acount::payment] ".$ex->getLine()." ".$ex->getMessage());
			$this->core_web_notification->set_message(true,$ex->getMessage());
			return redirect()->to(base_url("core_user/payment"));
		}
	}
	
	/********************************************/
	/* AUTORIZACION DE INGRESO POR EL ADMINISTRADOR (CORE_AUTHENTICATE_BY_EMAIL = true)
	/* 1. login: valida la clave, guarda la solicitud en tb_log_session (token con hash) y envia el enlace.
	/* 2. reviewLoginAuthorization / resolveLoginAuthorization: el administrador autoriza o rechaza (sin sesion).
	/* 3. getLoginAuthorizationStatus: la pantalla de espera consulta; al autorizarse crea la sesion.
	/********************************************/

	/**
	 * Destinatarios que deben autorizar el ingreso del usuario. Devuelve arreglo vacio si no se
	 * requiere autorizacion: CORE_AUTHENTICATE_BY_EMAIL distinto de "true", usuario superadmin o
	 * el usuario es uno de los autorizadores.
	 * Destinatarios: CORE_LOGIN_AUTHORIZATION_EMAIL (separados por coma) o, si esta vacio,
	 * los usuarios con rol administrador de la compañia.
	 *
	 * @param array $objUser Datos de get_UserBy_PasswordAndNickname.
	 * @return array Correos destinatarios.
	 */
	private function loginAuthorizationRecipients($objUser)
	{
		$companyID	= $objUser["user"]->companyID;
		$nickname	= strtolower(trim((string)$objUser["user"]->nickname));

		try{
			$habilitado = (string)$this->core_web_parameter->getParameterValue("CORE_AUTHENTICATE_BY_EMAIL",$companyID);
		}
		catch(\Exception $ex){
			$habilitado = "false";
		}
		if(strtolower(trim($habilitado)) != "true" || $nickname == "superadmin")
		return array();

		try{
			$correos = (string)$this->core_web_parameter->getParameterValue("CORE_LOGIN_AUTHORIZATION_EMAIL",$companyID);
		}
		catch(\Exception $ex){
			$correos = "";
		}

		$destinatarios	= $this->core_web_email->normalizeRecipients($correos);
		$adminUserIDs	= array();
		if(count($destinatarios) == 0)
		{
			$correosAdmin	= array();
			$objListAdmin	= $this->User_Model->get_rowByRoleAdminCompany($companyID);
			foreach(($objListAdmin ? $objListAdmin : array()) as $objAdmin)
			{
				$adminUserIDs[]	= (int)$objAdmin->userID;
				$correosAdmin[]	= $objAdmin->email;
			}
			$destinatarios	= $this->core_web_email->normalizeRecipients($correosAdmin);
		}

		//El usuario que intenta entrar es uno de los autorizadores: entra sin autorizacion
		$emailUsuario	= strtolower(trim((string)$objUser["user"]->email));
		if(in_array((int)$objUser["user"]->userID,$adminUserIDs) || ($emailUsuario != "" && in_array($emailUsuario,array_map("strtolower",$destinatarios))))
		return array();

		if(count($destinatarios) == 0)
		throw new \Exception("El ingreso requiere autorización del administrador, pero no hay correos de autorización configurados. Contacte al administrador.");

		return $destinatarios;
	}

	/**
	 * Registra la solicitud de autorizacion (tb_log_session, estado pending, solo el hash del token),
	 * envia el enlace a los administradores (sendNow y, si falla, cola de correo) y muestra la
	 * pantalla de espera. No crea la sesion del usuario.
	 *
	 * @param array $objUser       Datos de get_UserBy_PasswordAndNickname.
	 * @param array $destinatarios Correos de los autorizadores.
	 * @return string HTML de la pantalla de espera.
	 */
	private function loginAuthorizationRequest($objUser,$destinatarios)
	{
		$companyID	= (int)$objUser["user"]->companyID;
		$userID		= (int)$objUser["user"]->userID;
		$nickname	= (string)$objUser["user"]->nickname;
		$objCompany	= $objUser["company"];

		try{
			$minutos = (int)$this->core_web_parameter->getParameterValue("CORE_LOGIN_AUTHORIZATION_MINUTES",$companyID);
		}
		catch(\Exception $ex){
			$minutos = 15;
		}
		$minutos	= $minutos > 0 ? $minutos : 15;

		$now		= helper_getDateTime();
		$objNow		= \DateTime::createFromFormat("Y-m-d H:i:s",$now);
		$expiresOn	= (clone $objNow)->modify("+".$minutos." minutes")->format("Y-m-d H:i:s");
		$token		= bin2hex(random_bytes(32));
		$tokenHash	= hash("sha256",$token);
		$sessionID	= session_create_id();
		$ip			= $this->request->getIPAddress();
		$agent		= $this->request->getUserAgent();
		$platform	= (string)$agent->getPlatform();
		$browser	= trim((string)$agent->getBrowser()." ".(string)$agent->getVersion());

		//Si el navegador tenia otra sesion abierta, deja de estar autenticado mientras espera
		if($this->session->get("user"))
		$this->session->remove("user");

		//Solicitud pendiente: solo se guarda el hash del token
		$objLogSessionModel["session_id"] 				= $sessionID;
		$objLogSessionModel["userID"]					= $userID;
		$objLogSessionModel["companyID"]				= $companyID;
		$objLogSessionModel["ip_address"] 				= $ip;
		$objLogSessionModel["user_agent"] 				= $platform;
		$objLogSessionModel["last_activity"] 			= $objNow->format("YmdHis");
		$objLogSessionModel["user_data"] 				= $nickname." request_authorization session";
		$objLogSessionModel["authorizationStatus"]		= "pending";
		$objLogSessionModel["authorizationTokenHash"]	= $tokenHash;
		$objLogSessionModel["authorizationExpiresOn"]	= $expiresOn;
		$this->Log_Session_Model->insert($objLogSessionModel);

		//Correo al administrador
		$url					= base_url()."/core_acount/reviewLoginAuthorization/token/".$token;
		$params_["nickname"]	= $nickname;
		$params_["objCompany"]	= $objCompany;
		$params_["mensaje"]		=
			"El usuario <b>".htmlspecialchars($nickname)."</b> está intentando ingresar al sistema y requiere su autorización.<br/><br/>".
			"Usuario: ".htmlspecialchars($nickname)."<br/>".
			"Compañía: ".htmlspecialchars((string)$objCompany->name)."<br/>".
			"IP: ".htmlspecialchars($ip)."<br/>".
			"Plataforma: ".htmlspecialchars(trim($platform." ".$browser))."<br/>".
			"Hora: ".$objNow->format("Y-m-d H:i")."<br/>".
			"Vence: ".\DateTime::createFromFormat("Y-m-d H:i:s",$expiresOn)->format("Y-m-d H:i")."<br/><br/>".
			"👉 <a href='".htmlspecialchars($url,ENT_QUOTES)."'>Revisar y autorizar el ingreso</a><br/><br/>".
			"Si el enlace no abre, copie esta dirección en el navegador: ".htmlspecialchars($url);
		$subject				= "Autorizar ingreso: ".$objCompany->name." - ".$nickname;
		$body  					= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview

		$enviado				= $this->core_web_email->sendNow($companyID,$destinatarios,$subject,$body);
		if(!$enviado && $this->core_web_email->isQueueEnabled($companyID))
		$enviado				= $this->core_web_email->queueEmail($companyID,$destinatarios,$subject,$body,array(),"core_acount::login");

		if(!$enviado)
		{
			$this->Log_Session_Model->delete_app_posme("session_id",$sessionID);
			throw new \Exception("No se pudo notificar al administrador para autorizar el ingreso. Intente de nuevo en unos minutos.");
		}

		//La solicitud vive en la sesion del navegador (sin token en claro)
		$this->session->set("pendingLogin",array(
			"userID"		=> $userID,
			"companyID"		=> $companyID,
			"nickname"		=> $nickname,
			"sessionID"		=> $sessionID,
			"tokenHash"		=> $tokenHash,
			"expiresOn"		=> $expiresOn
		));

		$script = /*--inicio view*/ view('core_template/core_acount_script_autorizacion');//--finview
		$script = str_replace("[[URL_AUTORIZATION_STATUS]]",base_url()."/core_acount/getLoginAuthorizationStatus", $script);
		$script = str_replace("[[URL_AUTORIZATION_CANCEL]]",base_url()."/core_acount/cancelLoginAuthorization", $script);
		$script = str_replace("[[URL_LOGIN]]",base_url(), $script);

		return helper_notificationPage(
			"Esperando autorización",
			$script,
			"Se notificó al administrador. Esta pantalla continuará sola cuando autorice el ingreso (vence a las ".\DateTime::createFromFormat("Y-m-d H:i:s",$expiresOn)->format("h:i A").").",
			"",
			"",
			true
		);
	}

	/**
	 * Datos de la vista core_acount/review_authorization para un token.
	 *
	 * @param string $token Token en claro recibido en el enlace o en el formulario.
	 * @return array array("data" => datos de la vista, "objLog" => fila de tb_log_session o null)
	 */
	private function loginAuthorizationViewData($token)
	{
		$token				= strtolower(trim((string)$token));
		$data["valid"]		= false;
		$data["resolved"]	= false;
		$data["message"]	= "El enlace de autorización no es válido.";
		$data["token"]		= "";
		$data["nickname"]	= "";
		$data["companyName"]= "";
		$data["ip"]			= "";
		$data["userAgent"]	= "";
		$data["createdOn"]	= "";
		$data["expiresOn"]	= "";
		$data["urlResolve"]	= base_url()."/core_acount/resolveLoginAuthorization";

		if(preg_match('/^[a-f0-9]{64}$/',$token) !== 1)
		return array("data" => $data, "objLog" => null);

		$objLog				= $this->Log_Session_Model->get_rowByAuthorizationTokenHash(hash("sha256",$token));
		if(!$objLog)
		return array("data" => $data, "objLog" => null);

		$objUserLog			= $this->User_Model->get_rowByUserID((int)$objLog->userID);
		$objCompanyLog		= $this->Company_Model->get_rowByPK((int)$objLog->companyID);
		$objCreatedOn		= \DateTime::createFromFormat("YmdHis",(string)$objLog->last_activity);
		$objExpiresOn		= \DateTime::createFromFormat("Y-m-d H:i:s",(string)$objLog->authorizationExpiresOn);

		$data["token"]		= $token;
		$data["nickname"]	= $objUserLog ? $objUserLog->nickname : "";
		$data["companyName"]= $objCompanyLog ? $objCompanyLog->name : "";
		$data["ip"]			= (string)$objLog->ip_address;
		$data["userAgent"]	= (string)$objLog->user_agent;
		$data["createdOn"]	= $objCreatedOn ? $objCreatedOn->format("Y-m-d H:i") : "";
		$data["expiresOn"]	= $objExpiresOn ? $objExpiresOn->format("Y-m-d H:i") : "";

		$status				= (string)$objLog->authorizationStatus;
		if($status == "authorized")
		$data["message"]	= "Este ingreso ya fue autorizado.";
		else if($status == "rejected")
		$data["message"]	= "Este ingreso ya fue rechazado.";
		else if($status != "pending")
		$data["message"]	= "La solicitud de autorización no es válida.";
		else if((string)$objLog->authorizationExpiresOn < helper_getDateTime())
		$data["message"]	= "La solicitud de autorización venció. El usuario debe intentar ingresar de nuevo.";
		else
		{
			$data["valid"]	= true;
			$data["message"]= "";
		}

		return array("data" => $data, "objLog" => $objLog);
	}

	/**
	 * Pagina publica que abre el administrador desde el correo: muestra los datos del intento de
	 * ingreso y los botones Autorizar / Rechazar (la decision se envia por POST).
	 * Segmento: token.
	 *
	 * @return string Vista core_acount/review_authorization.
	 */
	function reviewLoginAuthorization()
	{
		try
		{
			$token		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"token"); //--finuri
			$result		= $this->loginAuthorizationViewData($token);
			return view('core_acount/review_authorization',$result["data"]);//--finview-r
		}
		catch(\Exception $ex)
		{
			log_message("error","[core_acount::reviewLoginAuthorization] ".$ex->getLine()." ".$ex->getMessage());
			return view('core_acount/review_authorization',array("valid" => false, "resolved" => false, "message" => "No se pudo revisar la solicitud. Intente de nuevo."));//--finview-r
		}
	}

	/**
	 * Autoriza o rechaza la solicitud de ingreso (solo POST: token + action approve|reject).
	 * La actualizacion es atomica: solo aplica si sigue pendiente y no ha vencido.
	 *
	 * @return string Vista core_acount/review_authorization con resolved = true y el mensaje.
	 */
	function resolveLoginAuthorization()
	{
		try
		{
			//Nunca se autoriza con GET (ej. vista previa de enlaces del correo)
			if(strtolower($this->request->getMethod()) != "post")
			return view('core_acount/review_authorization',array("valid" => false, "resolved" => false, "message" => "La decisión debe enviarse desde el formulario de autorización. Abra de nuevo el enlace del correo."));//--finview-r

			$token		= (string)$this->request->getPost("token");
			$action		= $this->request->getPost("action");

			//El formulario puede enviar action repetido con el mismo valor
			$actions	= array_values(array_unique(array_map(function($v){ return strtolower(trim((string)$v)); }, is_array($action) ? $action : array($action))));
			$action		= count($actions) == 1 ? $actions[0] : "";

			$result		= $this->loginAuthorizationViewData($token);
			$data		= $result["data"];
			$objLog		= $result["objLog"];

			if(!$objLog || !$data["valid"])
			return view('core_acount/review_authorization',$data);//--finview-r

			if($action != "approve" && $action != "reject")
			{
				$data["message"]	= "Seleccione Autorizar o Rechazar.";
				return view('core_acount/review_authorization',$data);//--finview-r
			}

			$status		= $action == "approve" ? "authorized" : "rejected";
			$objUserBy	= $this->session->get("user");
			$userIDBy	= $objUserBy ? (int)$objUserBy->userID : 0;
			$nickname	= $data["nickname"];
			$affected	= $this->Log_Session_Model->update_app_posme_authorization(
				hash("sha256",strtolower(trim($token))),
				$status,
				$userIDBy,
				$this->request->getIPAddress(),
				helper_getDateTime(),
				$nickname." ".($status == "authorized" ? "authorized" : "rejected")." session"
			);

			$data["valid"]		= false;
			$data["resolved"]	= true;
			if($affected > 0)
			$data["message"]	= $status == "authorized" ? "Ingreso autorizado. El usuario ".$nickname." entrará en unos segundos." : "Ingreso rechazado. El usuario ".$nickname." no podrá entrar con esta solicitud.";
			else
			{
				//Otro administrador ya respondio o vencio mientras se revisaba
				$data["resolved"]	= false;
				$data["message"]	= "La solicitud ya fue resuelta o venció.";
			}

			log_message("info","[core_acount::resolveLoginAuthorization] usuario: ".$nickname." | estado: ".$status." | aplicado: ".$affected);
			return view('core_acount/review_authorization',$data);//--finview-r
		}
		catch(\Exception $ex)
		{
			log_message("error","[core_acount::resolveLoginAuthorization] ".$ex->getLine()." ".$ex->getMessage());
			return view('core_acount/review_authorization',array("valid" => false, "resolved" => false, "message" => "No se pudo registrar la decisión. Intente de nuevo."));//--finview-r
		}
	}

	/**
	 * Consulta de la pantalla de espera (siempre JSON). Lee pendingLogin de la sesion;
	 * al estar autorizada crea la sesion del usuario y devuelve la URL de inicio.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON {status: pending|authorized|rejected|expired|none|error, message, redirect}
	 */
	function getLoginAuthorizationStatus()
	{
		try
		{
			$pending	= $this->session->get("pendingLogin");
			if(!is_array($pending) || empty($pending["tokenHash"]))
			return $this->response->setJSON(array("status" => "none", "message" => "No hay una solicitud de ingreso pendiente.", "redirect" => base_url()));

			$objLog		= $this->Log_Session_Model->get_rowByAuthorizationTokenHash($pending["tokenHash"]);
			if(!$objLog || (int)$objLog->userID != (int)$pending["userID"])
			{
				$this->session->remove("pendingLogin");
				return $this->response->setJSON(array("status" => "none", "message" => "La solicitud de ingreso ya no existe.", "redirect" => base_url()));
			}

			$status		= (string)$objLog->authorizationStatus;
			if($status == "rejected")
			{
				$this->session->remove("pendingLogin");
				return $this->response->setJSON(array("status" => "rejected", "message" => "El administrador rechazó el ingreso.", "redirect" => base_url()));
			}

			if($status == "pending")
			{
				if((string)$objLog->authorizationExpiresOn < helper_getDateTime())
				{
					$this->session->remove("pendingLogin");
					return $this->response->setJSON(array("status" => "expired", "message" => "La solicitud de autorización venció. Intente ingresar de nuevo.", "redirect" => base_url()));
				}
				return $this->response->setJSON(array("status" => "pending", "message" => "Esperando autorización del administrador.", "redirect" => ""));
			}

			if($status != "authorized")
			{
				$this->session->remove("pendingLogin");
				return $this->response->setJSON(array("status" => "none", "message" => "La solicitud de ingreso no es válida.", "redirect" => base_url()));
			}

			//Autorizado: se crea la sesion con el mismo sessionID registrado en la bitacora
			$objUser	= $this->core_web_authentication->get_UserBy_UserIDForLogin($pending["companyID"],$pending["userID"]);
			$this->session->remove("pendingLogin");
			$this->core_web_authentication->createLogin($objUser);
			$this->session->set("sessionID",$pending["sessionID"]);
			$dataSession	= $this->session->get();

			set_cookie("userID",$dataSession['user']->userID,86400,"localhost");
			set_cookie("nickname",$dataSession['user']->nickname,86400,"localhost");
			set_cookie("email",$dataSession['user']->email,86400,"localhost");

			//Bitacora: una sola sesion por usuario (igual que el login normal)
			$this->Log_Session_Model->where("userID",$dataSession["user"]->userID)->where("session_id !=",$pending["sessionID"])->delete();
			$objLogSessionModel["last_activity"] 	= \DateTime::createFromFormat("Y-m-d H:i:s",helper_getDateTime())->format("YmdHis");
			$objLogSessionModel["user_data"] 		= $objUser["user"]->nickname." create session";
			$this->Log_Session_Model->update_app_posme($pending["sessionID"],$objLogSessionModel);

			//Pantalla de inicio: la última visitada por el usuario o la del rol
			$urlInicio	= $this->core_web_authentication->lastScreenGet($objUser["user"]);
			$urlInicio	= $urlInicio ? $urlInicio : base_url()."/".$objUser["role"]->urlDefault."/index";

			return $this->response->setJSON(array("status" => "authorized", "message" => "Ingreso autorizado.", "redirect" => $urlInicio));
		}
		catch(\Throwable $ex)
		{
			log_message("error","[core_acount::getLoginAuthorizationStatus] ".$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON(array("status" => "error", "message" => "No se pudo consultar la autorización.", "redirect" => ""));
		}
	}

	/**
	 * Cancela la solicitud de ingreso pendiente del navegador (siempre JSON).
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON {status: "cancelled", redirect} o {status: "error", message}
	 */
	function cancelLoginAuthorization()
	{
		try
		{
			$pending	= $this->session->get("pendingLogin");
			if(is_array($pending) && !empty($pending["sessionID"]))
			{
				$objLog	= $this->Log_Session_Model->get_rowByAuthorizationTokenHash((string)$pending["tokenHash"]);
				//Solo se borra la solicitud si sigue pendiente (no se toca una sesion ya creada)
				if($objLog && (string)$objLog->authorizationStatus == "pending")
				$this->Log_Session_Model->delete_app_posme("session_id",$pending["sessionID"]);
			}
			$this->session->remove("pendingLogin");

			return $this->response->setJSON(array("status" => "cancelled", "redirect" => base_url()));
		}
		catch(\Throwable $ex)
		{
			log_message("error","[core_acount::cancelLoginAuthorization] ".$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON(array("status" => "error", "message" => "No se pudo cancelar la solicitud. Intente de nuevo."));
		}
	}
}
