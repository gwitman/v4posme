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
				
						$subject 	= "Inicio de session: ".$objCompany->name." ".$nickname;
						$body  		= /*--inicio view*/ view('core_template/email_notificacion',$params_);
						
				
						$this->email->setFrom(EMAIL_APP);
						$this->email->setTo(EMAIL_APP_COPY);
						$this->email->setSubject($subject);			
						$this->email->setMessage($body); 
						
						$resultSend01 = $this->email->send();
						$resultSend02 = $this->email->printDebugger();
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
				$this->response->redirect(base_url()."/"."core_acount/payment/pagoCantidadDeMeses/".$pagoCantidadDeMeses);
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
			
	
			
			
			$subject 	= "Inicio de session: ".$objCompany->name." ".$nickname;
			$body  		= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview			
			if($objCompany->type == "emanuel")
			{
				$this->response->redirect(base_url()."/".$objUser["role"]->urlDefault."/index");
			}
			else 
			{
				$this->email->setFrom(EMAIL_APP);
				$this->email->setTo(EMAIL_APP_COPY);
				$this->email->setSubject($subject);			
				$this->email->setMessage($body); 
				
				$resultSend01 = $this->email->send();
				$resultSend02 = $this->email->printDebugger();
				
				
				//Guardar Log		
				$this->Log_Session_Model->delete_app_posme("userID",$dataSession["user"]->userID);	
				
				$objLogSessionModel["session_id"] 		= $dataSession["sessionID"];
				$objLogSessionModel["ip_address"] 		= $this->request->getIPAddress();
				$objLogSessionModel["user_agent"] 		= $this->request->getUserAgent()->getPlatform();
				$objLogSessionModel["last_activity"] 	= \DateTime::createFromFormat("Y-m-d H:i:s",helper_getDateTime())->format("YmdHis");
				$objLogSessionModel["user_data"] 		= $objUser["user"]->nickname." create session";
				$objLogSessionModel["userID"]			= $dataSession["user"]->userID;				
				$this->Log_Session_Model->insert($objLogSessionModel);	
				$this->response->redirect(base_url()."/".$objUser["role"]->urlDefault."/index");
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
			
			
			$subject 	= "Inicio de session: ".$objCompany->name." ".$nickname;
			$body  		= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview			
			
			$this->email->setFrom(EMAIL_APP);
			$this->email->setTo(EMAIL_APP_COPY);
			$this->email->setSubject($subject);			
			$this->email->setMessage($body); 
			
			$resultSend01 = $this->email->send();
			$resultSend02 = $this->email->printDebugger();
			
			
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
