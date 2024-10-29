<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxc_record extends _BaseController {
	
       
	function index(){ 
		
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_EDIT);			
			
			}
		
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_consultas_sin_riesgo");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_customer_consultas_sin_riesgo' NO EXISTE...");
		
					
			$entityID		= $dataSession["user"]->userID;
			$companyID		= $dataSession["user"]->companyID;
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;					
			
			//Leer Parametros
			 
			date_default_timezone_set(APP_TIMEZONE); 
			
			
			$identificacion 	= /*inicio get get*/ $this->request->getGet("identificacion");//--fin peticion get o post
			$objCustomer		= $this->Customer_Model->get_rowByIdentification($companyID,$identificacion);
			$file_exists 		= /*inicio get get*/ $this->request->getGet("file_exists");//--fin peticion get o post
			$identificacion	= !$file_exists ? str_replace("-","",$identificacion) : substr($file_exists,0,14);
			$path_ 			= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/";
			$file			= $identificacion;
			$prefiex 		= date("_Y_m_d");
			$extencion		= ".txt";
			$archivo1 		= $path_.$file.$prefiex.$extencion;
			$archivo2 		= $path_.$file_exists;
		
			
			$objParameter 			= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO",$companyID);//"https://www.sinriesgos.com.ni/WS/WebService.asmx?WSDL"			
			$objParameterPassword 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_PASSWORD",$companyID);//flc-wgonzalez
			$objParameterUsuario 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_USUARIO",$companyID);//180389Gonzalez.
			
			
			$client 				= new \SoapClient($objParameter->value);			
			//$client 				= new \SOAPClient
			//						(
			//							$objParameter->value,
			//							array
			//							(
			//								'stream_context' => stream_context_create
			//								(
			//									array
			//									(
			//										'ssl' => array
			//										(
			//											'verify_peer' => false,
			//											'verify_peer_name' => false
			//										)
			//									)
			//								)
			//							)
		 	//						);
			//						
									

			$params 				= array(
				"Usuario" 					=> $objParameterUsuario->value,
				"Password" 					=> $objParameterPassword->value,
				"NumeroIdentificacion" 		=> $identificacion,
				"TipoConsulta" 				=> 1, 
				"Score"						=> false,
				"TipoPersona"				=> "F"
			);
			
			
		
			$objParameterCantidadDeConsultasActuales	= $this->core_web_parameter->getParameter("CXC_CURRENT_REQUEST_BURO",$companyID);
			$objParameterCantidadDeConsultasMaximas		= $this->core_web_parameter->getParameter("CXC_COUNT_MAX_REQUEST_BURO",$companyID);
			$dataPara["value"] 							= $objParameterCantidadDeConsultasActuales->value + 1;
			
			$resultado 		= "";
			$datView		= array();
			if(
				($identificacion && !$file_exists) ||  /*si viene la variable identificacion y no viene la variable file_exists: consultar */
				(!file_exists($archivo2) && ($file_exists)) /*si viene la varieble file_exists y no existe el archivo: consultar */ 
			)
			{				
				
				
				//consultar a sin riesgo
				if( $dataPara["value"] <= $objParameterCantidadDeConsultasMaximas->value )
				{
					$this->Company_Parameter_Model->update_app_posme($companyID,$objParameterCantidadDeConsultasActuales->parameterID,$dataPara);
					$objUltimoRegistro 				= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedulaLast($companyID,$identificacion);
					$requestID						= $objUltimoRegistro == null ? 0 : $objUltimoRegistro->requestID;
					$objUltimoRegistroMas6Dias 		= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowValidOld($requestID,6);
					$objFileDist					= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedula_FileName($companyID,$identificacion);
					$archivo_exists					= $objUltimoRegistroMas6Dias != null ? !file_exists($objUltimoRegistroMas6Dias->file): false;
					$resultado 						= $client->ObtenerRecordCrediticio($params);	
					
					//Guardar Archivo si, viene la variable file_exists, pero no existe el archivo
					if(!file_exists($archivo2) && ($file_exists)){ 
						$this->GuardarArchivo($archivo2,$resultado);
					}
					//Guardar Archivo si, viene la varieble identificacion y no viene la varieble file_exists
					if($identificacion && !$file_exists){					
						$this->GuardarArchivo($archivo1,$resultado);
						$archivo2 = $archivo1;
					}
					//Guardar la informacion del cliente
					if(
						($objUltimoRegistroMas6Dias  != NULL) || /*si la ultima consulta tiene mas de 6 dias */
						($objUltimoRegistro == NULL) /*si no existe registro en la tabla*/
					)
					{	
						$objCustomerConsultaSinRiesgo["companyID"] 				= $dataSession["user"]->companyID;			
						$objCustomerConsultaSinRiesgo["name"] 					= $resultado->ObtenerRecordCrediticioResult->Persona->NombreRazonSocial;			
						$objCustomerConsultaSinRiesgo["id"] 					= $resultado->ObtenerRecordCrediticioResult->Persona->NumeroDocumentoIdentidad;
						$objCustomerConsultaSinRiesgo["userID"] 				= $dataSession["user"]->userID;
						$objCustomerConsultaSinRiesgo["file"] 					= $archivo2;
						$this->core_web_auditoria->setAuditCreated($objCustomerConsultaSinRiesgo,$dataSession,$this->request);
						$requestID 	= $this->Customer_Consultas_Sin_Riesgo_Model->insert_app_posme($objCustomerConsultaSinRiesgo);					
					
					} 
					
					$requestID												= 0;
					$objCustomerConsultaSinRiesgo							= NULL;
					$objCustomerConsultaSinRiesgo["modifiedOn"] 			= date("Y-m-d");
					$objCustomerConsultaSinRiesgo["createdOn"] 				= date("Y-m-d");
					$objCustomerConsultaSinRiesgo["userID"] 				= $dataSession["user"]->userID;
					$objCustomerConsultaSinRiesgo["file"] 					= substr($archivo2,-29);
					$this->Customer_Consultas_Sin_Riesgo_Model->updateByCedula($companyID,$identificacion,$objCustomerConsultaSinRiesgo);
					
				}
				else{
					throw new \Exception("NO PUEDE SEGUIR CONSULTADO, CREDITOS AGOTADOS");
				}
					
				
				
			}
			
		
			if(   !(!$identificacion && !$file_exists)   ){
				$identificacion									= $identificacion ? str_replace("-","",$identificacion) : (substr($file_exists, 0, 14));				
				$objUltimoRegistro 								= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCedulaLast($companyID,$identificacion);
				$datos 											= $this->LeerDatos($archivo2);				
				$datView										= $this->FillDatos($datos);
				$datView["ObjConsulta"]							= $objUltimoRegistro;
				$datView["Persona"]->NumeroDocumentoIdentidad 	= substr_replace($datView["Persona"]->NumeroDocumentoIdentidad, "-", 3, 0);
				$datView["Persona"]->NumeroDocumentoIdentidad 	= substr_replace($datView["Persona"]->NumeroDocumentoIdentidad, "-", 10, 0);
			}
		
			
			//Renderizar Resultado
			$datView["company"]				= $dataSession["company"];
			$datView["objCustomer"]			= $objCustomer;
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxc_record/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxc_record/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxc_record/edit_script',$datView);//--finview
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
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}	
	}
	function GuardarArchivo($file,$data){
		//Serializar		
		$json_reaultado = json_encode($data);
		if(!file_exists($file))
		{
			//Crear Archivo
			$fp = fopen($file, 'w+');
			fclose($fp);
			//Guardar Datos.
			$current 	= file_get_contents($file);
			$current 	.= $json_reaultado;			
			file_put_contents($file, $current);
		}
	}
	function LeerDatos($file){
		$fp 	= fopen($file, "rb");
		$datos 	= fread($fp, filesize($file));
		fclose($fp);
		return $datos;
	}
	function FillDatos($resultado){
		$datView		= null;
		$resultado 		= json_decode($resultado);
		$resultado		= $resultado->ObtenerRecordCrediticioResult;
		
		//Obtener Datos Generales
		$datView["Persona"] = $resultado->Persona;
		/*
		$resultado->Persona->NumeroDocumentoIdentidad;
		$resultado->Persona->NombreRazonSocial;
		*/
		
		if (!empty((array)$resultado->DatosContacto)) {
			$empty = "";
			
			
			//Obtener Datos de Direcciones
			if(!empty((array)$resultado->DatosContacto->DireccionesContacto))
			if(!empty((array)$resultado->DatosContacto->DireccionesContacto->DireccionContacto))
			{
			    $datView["Direcciones"]	= $resultado->DatosContacto->DireccionesContacto->DireccionContacto;
			    $datView["Direcciones"] = is_array($datView["Direcciones"]) ? $datView["Direcciones"] : array($datView["Direcciones"]);
			}
			
			/*
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Direccion;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Departamento;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Municipio;
			$resultado->DatosContacto->DireccionesContacto->DireccionContacto[0]->Referencia;
			*/
			
			//Obtener Datos de Telefonos
			if(!empty((array)$resultado->DatosContacto->TelefonosContacto))
			if(!empty((array)$resultado->DatosContacto->TelefonosContacto->TelefonoContacto)){
			    $datView["Telefonos"]	= $resultado->DatosContacto->TelefonosContacto->TelefonoContacto;
			    $datView["Telefonos"] = is_array($datView["Telefonos"]) ? $datView["Telefonos"] : array($datView["Telefonos"]);
			}
			/*
			$resultado->DatosContacto->TelefonosContacto->TelefonoContacto[0]->Telefono;
			$resultado->DatosContacto->TelefonosContacto->TelefonoContacto[0]->Referencia;
			*/
		}
		
		//Datos de Creditos Vigente
		if(isset($resultado->CreditosVigentes))
		if (!empty((array)$resultado->CreditosVigentes)) 
		if (!empty((array)$resultado->CreditosVigentes->OperacionDeCredito)) {
		    $datView["CreditosVigentes"]	= $resultado->CreditosVigentes->OperacionDeCredito;		
		    $datView["CreditosVigentes"]    = is_array($datView["CreditosVigentes"]) ? $datView["CreditosVigentes"] : array($datView["CreditosVigentes"]);
		}
		/*
		$resultado->CreditosVigentes->OperacionDeCredito[0]->NumeroCredito;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Cuota;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FechaReporte;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Departamento;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoCredito;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FechaDesembolso;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->MontoAutorizado;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->PlazoMeses;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FormaDePago;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->SaldoDeuda;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->EstadoOP;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoObligacion;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->MontoVencido;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->AntiguedadMoraEnDias;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->TipoGarantia;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->FormaRecuperacion;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->Entidad;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->FechaReporte;
		$resultado->CreditosVigentes->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->AntiguedadMoraEnDias;
		*/
		
		//Datos de Creditos Cancelados	
		if(isset($resultado->CreditosCancelados))
		if (!empty((array)$resultado->CreditosCancelados)) 
		if (!empty((array)$resultado->CreditosCancelados->OperacionDeCredito)) {
		    $datView["CreditosCancelados"]	= $resultado->CreditosCancelados->OperacionDeCredito;
		    $datView["CreditosCancelados"]  = is_array($datView["CreditosCancelados"]) ? $datView["CreditosCancelados"] : array($datView["CreditosCancelados"]);
		}
		/*
		$resultado->CreditosCancelados->OperacionDeCredito[0]->NumeroCredito;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Cuota;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FechaReporte;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Departamento;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoCredito;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FechaDesembolso;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->MontoAutorizado;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->PlazoMeses;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FormaDePago; 
		$resultado->CreditosCancelados->OperacionDeCredito[0]->SaldoDeuda;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->EstadoOP;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoObligacion;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->MontoVencido;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->AntiguedadMoraEnDias;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->TipoGarantia;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->FormaRecuperacion;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->Entidad;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->FechaReporte;
		$resultado->CreditosCancelados->OperacionDeCredito[0]->HistorialMora->HistoriaMora[0]->AntiguedadMoraEnDias;
		*/
		
		//Tarjetas de Credito
		if(isset($resultado->TarjetasDeCredito))
		if(isset($resultado->TarjetasDeCredito))
		if (!empty((array)$resultado->TarjetasDeCredito->TarjetaDeCredito)) {
		    $datView["TarjetasDeCredito"]	= $resultado->TarjetasDeCredito->TarjetaDeCredito;
		    $datView["TarjetasDeCredito"]  = is_array($datView["TarjetasDeCredito"]) ? $datView["TarjetasDeCredito"] : array($datView["TarjetasDeCredito"]);
		}
		/*
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->NumeroTarjeta;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->Entidad;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaReporte;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaEmision;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->LimiteCredito;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->SaldoDeuda;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->CreditoDisponible;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->TipoTarjeta;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->TipoObligacion;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MontoVencido;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MoraEnDias;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FormaRecuperacion;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->FechaDesembolso;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->MontoAutorizado;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->PlazoMeses;
		$resultado->TarjetasDeCredito->TarjetaDeCredito[0]->SaldoDeudaExtraFinanciamiento;
		*/
		
		//Historia de Consulta
		if (!empty((array)$resultado->Consultas)) 
		if (!empty((array)$resultado->Consultas->HistoriaConsulta)){ 
		    $datView["Consultas"]	= $resultado->Consultas->HistoriaConsulta;
		    $datView["Consultas"]   = is_array($datView["Consultas"]) ? $datView["Consultas"] : array($datView["Consultas"]);
		}
	
	
		/*
		$resultado->Consultas->HistoriaConsulta[0]->Entidad;
		$resultado->Consultas->HistoriaConsulta[0]->FechaConsulta;
		$resultado->Consultas->HistoriaConsulta[0]->Cantidad;
		*/	
		return $datView;
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
			$companyID 			= /*inicio get post*/ $this->request->getPost("companyID");
			$branchID 			= /*inicio get post*/ $this->request->getPost("branchID");				
			$entityID 			= /*inicio get post*/ $this->request->getPost("entityID");				
			
			if((!$companyID && !$branchID && !$entityID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL CLIENTE
			$objCustomer 		= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Customer_Model->delete_app_posme($companyID,$branchID,$entityID);
					
			
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
	function updateElement($dataSession){
		try{
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
			
				
			
			
			
				
			
			
			
			
			
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			$companyID_ 							= /*inicio get post*/ $this->request->getPost("txtCompanyID");
			$branchID_								= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$entityID_								= /*inicio get post*/ $this->request->getPost("txtEntityID");
			
			$objCustomer							= $this->Customer_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
			$oldStatusID 							= $objCustomer->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objCustomer->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$objCustomer->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_customer","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objCustomer["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->Customer_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomer);
			}
			else{
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
				$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
				$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Natural_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objNatural);
				$objLegal["isActive"]		= true;
				$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
				$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
				$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Legal_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objLegal);
				
				$objCustomer 						= NULL;
				$objCustomer["identificationType"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
				$objCustomer["identification"]		= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
				$objCustomer["countryID"]			= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
				$objCustomer["stateID"]				= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
				$objCustomer["cityID"]				= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
				$objCustomer["location"]			= /*inicio get post*/ $this->request->getPost("txtLocation");//--fin peticion get o post
				$objCustomer["address"]				= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$objCustomer["currencyID"]			= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post
				$objCustomer["clasificationID"]		= /*inicio get post*/ $this->request->getPost('txtClasificationID');//--fin peticion get o post
				$objCustomer["categoryID"]			= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
				$objCustomer["subCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtSubCategoryID');//--fin peticion get o post
				$objCustomer["customerTypeID"]		= /*inicio get post*/ $this->request->getPost("txtCustomerTypeID");//--fin peticion get o post
				$objCustomer["birthDate"]			= /*inicio get post*/ $this->request->getPost("txtBirthDate");//--fin peticion get o post
				$objCustomer["statusID"]			= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$objCustomer["typePay"]				= /*inicio get post*/ $this->request->getPost('txtTypePayID');//--fin peticion get o post
				$objCustomer["payConditionID"]		= /*inicio get post*/ $this->request->getPost('txtPayConditionID');//--fin peticion get o post
				$objCustomer["sexoID"]				= /*inicio get post*/ $this->request->getPost('txtSexoID');//--fin peticion get o post
				$objCustomer["reference1"]			= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
				$objCustomer["reference2"]			= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
				$objCustomer["isActive"]			= true;
				$this->Customer_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomer);
				
				//Actualizar Customer Credit
				$objCustomerCredit 							= $this->Customer_Credit_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
				$objCustomerCreditNew["limitCreditDol"] 	= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtLimitCreditDol"));
				$objCustomerCreditNew["balanceDol"] 		= $objCustomerCreditNew["limitCreditDol"] - ($objCustomerCredit->limitCreditDol - $objCustomerCredit->balanceDol);
				$objCustomerCreditNew["incomeDol"] 			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtIncomeDol"));
				$this->Customer_Credit_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
				
				//actualizar cuenta
				$objListEntityAccount 					= $this->Entity_Account_Model->get_rowByEntity($companyID_,$objComponent->componentID,$entityID_);
				$objFirstEntityAccount					= $objListEntityAccount[0];
				$objEntityAccount["accountID"]			= /*inicio get post*/ $this->request->getPost("txtAccountID");//--fin peticion get o post
				$this->Entity_Account_Model->update_app_posme($objFirstEntityAccount->entityAccountID,$objEntityAccount);
			
			}
			
			
			//Email
			$this->Entity_Email_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityEmail 				= /*inicio get post*/ $this->request->getPost("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= /*inicio get post*/ $this->request->getPost("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $companyID_;
				$objEntityEmail["branchID"]		= $branchID_;
				$objEntityEmail["entityID"]		= $entityID_;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key] == 1 ? true : false;
				$this->Entity_Email_Model->insert_app_posme($objEntityEmail);
			}
			
			//Phone
			$this->Entity_Phone_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityPhoneTypeID			= /*inicio get post*/ $this->request->getPost("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $companyID_;
				$objEntityPhone["branchID"]		= $branchID_;
				$objEntityPhone["entityID"]		= $entityID_;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert_app_posme($objEntityPhone);
			}	
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");
			$arrayListCreditLineID			= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$arrayListCreditCurrencyID		= /*inicio get post*/ $this->request->getPost("txtCreditCurrencyID");
			$arrayListCreditStatusID		= /*inicio get post*/ $this->request->getPost("txtCreditStatusID");
			$arrayListCreditInterestYear	= /*inicio get post*/ $this->request->getPost("txtCreditInterestYear");
			$arrayListCreditInterestPay		= /*inicio get post*/ $this->request->getPost("txtCreditInterestPay");
			$arrayListCreditTotalPay		= /*inicio get post*/ $this->request->getPost("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= /*inicio get post*/ $this->request->getPost("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= /*inicio get post*/ $this->request->getPost("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= /*inicio get post*/ $this->request->getPost("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= /*inicio get post*/ $this->request->getPost("txtCreditDateLastPay");
			$arrayListCreditTerm			= /*inicio get post*/ $this->request->getPost("txtCreditTerm");
			$arrayListCreditNote			= /*inicio get post*/ $this->request->getPost("txtCreditNote");
			$arrayListCreditLine			= /*inicio get post*/ $this->request->getPost("txtLine");
			$arrayListCreditNumber			= /*inicio get post*/ $this->request->getPost("txtLineNumber");
			$arrayListCreditLimit			= /*inicio get post*/ $this->request->getPost("txtLineLimit");
			$arrayListCreditBalance			= /*inicio get post*/ $this->request->getPost("txtLineBalance");
			$arrayListCreditStatus			= /*inicio get post*/ $this->request->getPost("txtLineStatus");
			$arrayListTypeAmortization		= /*inicio get post*/ $this->request->getPost("txtTypeAmortization");
			$limitCreditLine 				= 0;
			//Limpiar Lineas de Creditos
			$this->Customer_Credit_Line_Model->deleteWhereIDNotIn($companyID_,$branchID_,$entityID_,$arrayListCustomerCreditLineID);
			
			if(!empty($arrayListCustomerCreditLineID))
			foreach($arrayListCustomerCreditLineID as $key => $value){
			
				$customerCreditLineID 						= $value;
				if($customerCreditLineID == 0 ){
					$objCustomerCreditLine					= NULL;
					$objCustomerCreditLine["companyID"]		= $companyID_;
					$objCustomerCreditLine["branchID"]		= $branchID_;
					$objCustomerCreditLine["entityID"]		= $entityID_;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= NULL;
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$limitCreditLine 								= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 									= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine["currencyID"]);
					$exchangeRateAmount								= $objCustomerCreditLine["limitCredit"];
					$this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
					
					if($objCustomerCreditLine["balance"] > $objCustomerCreditLine["limitCredit"])
					throw new \Exception("BALANCE NO PUEDE SER MAYOR QUE EL LIMITE EN LA LINEA");
				}
				else{					
					$objCustomerCreditLine 							= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
					$objCustomerCreditLineNew						= NULL;
					$objCustomerCreditLineNew["creditLineID"]		= $arrayListCreditLineID[$key];
					$objCustomerCreditLineNew["limitCredit"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLineNew["interestYear"]		= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLineNew["balance"] 			= $objCustomerCreditLineNew["limitCredit"] - ($objCustomerCreditLine->limitCredit - $objCustomerCreditLine->balance);
					$objCustomerCreditLineNew["periodPay"]			= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLineNew["term"]				= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLineNew["note"]				= $arrayListCreditNote[$key];
					$objCustomerCreditLineNew["statusID"]			= $arrayListCreditStatusID[$key];
					$objCustomerCreditLineNew["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$limitCreditLine 									= $limitCreditLine + $objCustomerCreditLineNew["limitCredit"];
					$exchangeRate 										= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine->currencyID);
					$exchangeRateAmount									= $objCustomerCreditLineNew["limitCredit"];
					
					//Si el balance es mayor que el limite igual el balance al limite
					if($objCustomerCreditLineNew["balance"] > $objCustomerCreditLineNew["limitCredit"])
					$objCustomerCreditLineNew["balance"] = $objCustomerCreditLineNew["limitCredit"];
					
					//actualizar
					$this->Customer_Credit_Line_Model->update_app_posme($customerCreditLineID,$objCustomerCreditLineNew);
					
					
			
				}
				
				//sumar los limites en dolares
				if($exchangeRate == 1)
					$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
				//sumar los limite en cordoba
				else
					$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
					
				
			}
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCreditNew["limitCreditDol"])
			throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Actualizar Balance
			if($objCustomerCreditNew["balanceDol"] > $objCustomerCreditNew["limitCreditDol"]){
				$objCustomerCreditNew["balanceDol"] = $objCustomerCreditNew["limitCreditDol"];
				$this->Customer_Credit_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objCustomerCreditNew);
			}
			
			//Confirmar Entidad
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxc_customer/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');	
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
	function insertElement($dataSession){
		try{
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
			
				
			
			
			
				
			
			
			
			
			
			
			
			
						
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;			
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;			
			$objEntity["branchID"]					= $dataSession["user"]->branchID;			
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession,$this->request);
			
			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			
			
			$db=db_connect();
			$db->transStart();
			$entityID = $this->Entity_Model->insert_app_posme($objEntity);
			
			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
			$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
			$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$result 					= $this->Natural_Model->insert_app_posme($objNatural);
			
			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
			$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
			$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$result 					= $this->Legal_Model->insert_app_posme($objLegal);
			
			$objCustomer["companyID"]			= $objEntity["companyID"];
			$objCustomer["branchID"]			= $objEntity["branchID"];
			$objCustomer["entityID"]			= $entityID;
			$objCustomer["customerNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objCustomer["identificationType"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
			$objCustomer["identification"]		= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
			$objCustomer["countryID"]			= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
			$objCustomer["stateID"]				= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
			$objCustomer["cityID"]				= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
			$objCustomer["location"]			= /*inicio get post*/ $this->request->getPost("txtLocation");//--fin peticion get o post
			$objCustomer["address"]				= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$objCustomer["currencyID"]			= /*inicio get post*/ $this->request->getPost("txtCurrencyID");//--fin peticion get o post
			$objCustomer["clasificationID"]		= /*inicio get post*/ $this->request->getPost('txtClasificationID');//--fin peticion get o post
			$objCustomer["categoryID"]			= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
			$objCustomer["subCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtSubCategoryID');//--fin peticion get o post
			$objCustomer["customerTypeID"]		= /*inicio get post*/ $this->request->getPost("txtCustomerTypeID");//--fin peticion get o post
			$objCustomer["birthDate"]			= /*inicio get post*/ $this->request->getPost("txtBirthDate");//--fin peticion get o post
			$objCustomer["statusID"]			= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
			$objCustomer["typePay"]				= /*inicio get post*/ $this->request->getPost('txtTypePayID');//--fin peticion get o post
			$objCustomer["payConditionID"]		= /*inicio get post*/ $this->request->getPost('txtPayConditionID');//--fin peticion get o post
			$objCustomer["sexoID"]				= /*inicio get post*/ $this->request->getPost('txtSexoID');//--fin peticion get o post
			$objCustomer["reference1"]			= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objCustomer["reference2"]			= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objCustomer["isActive"]			= true;
			$this->core_web_auditoria->setAuditCreated($objCustomer,$dataSession,$this->request);
			$result 							= $this->Customer_Model->insert_app_posme($objCustomer);
			
			//Ingresar Cuenta
			$objEntityAccount["companyID"]			= $objEntity["companyID"];
			$objEntityAccount["componentID"]		= $objComponent->componentID;
			$objEntityAccount["componentItemID"]	= $entityID;
			$objEntityAccount["name"]				= "";
			$objEntityAccount["description"]		= "";
			$objEntityAccount["accountTypeID"]		= "0";
			$objEntityAccount["currencyID"]			= "0";
			$objEntityAccount["classID"]			= "0";
			$objEntityAccount["balance"]			= "0";
			$objEntityAccount["creditLimit"]		= "0";
			$objEntityAccount["maxCredit"]			= "0";
			$objEntityAccount["debitLimit"]			= "0";
			$objEntityAccount["maxDebit"]			= "0";
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["accountID"]			= /*inicio get post*/ $this->request->getPost("txtAccountID");//--fin peticion get o post
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["isActive"]			= "1";
			$this->core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession,$this->request);
			$this->Entity_Account_Model->insert_app_posme($objEntityAccount);
			
			//Ingresar Customer Credit
			$objCustomerCredit["companyID"] 		= $objEntity["companyID"];
			$objCustomerCredit["branchID"] 			= $objEntity["branchID"];
			$objCustomerCredit["entityID"] 			= $entityID;
			$objCustomerCredit["limitCreditDol"] 	= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtLimitCreditDol"));
			$objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
			$objCustomerCredit["incomeDol"] 		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtIncomeDol"));
			$this->Customer_Credit_Model->insert_app_posme($objCustomerCredit);
			
			//Email
			$arrayListEntityEmail 				= /*inicio get post*/ $this->request->getPost("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= /*inicio get post*/ $this->request->getPost("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $objEntity["companyID"];
				$objEntityEmail["branchID"]		= $objEntity["branchID"];
				$objEntityEmail["entityID"]		= $entityID;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key];
				$this->Entity_Email_Model->insert_app_posme($objEntityEmail);
			}
			
			//Phone
			$arrayListEntityPhoneTypeID			= /*inicio get post*/ $this->request->getPost("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $objEntity["companyID"];
				$objEntityPhone["branchID"]		= $objEntity["branchID"];
				$objEntityPhone["entityID"]		= $entityID;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert_app_posme($objEntityPhone);
			}
			
			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");
			$arrayListCreditLineID			= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$arrayListCreditCurrencyID		= /*inicio get post*/ $this->request->getPost("txtCreditCurrencyID");
			$arrayListCreditStatusID		= /*inicio get post*/ $this->request->getPost("txtCreditStatusID");
			$arrayListCreditInterestYear	= /*inicio get post*/ $this->request->getPost("txtCreditInterestYear");
			$arrayListCreditInterestPay		= /*inicio get post*/ $this->request->getPost("txtCreditInterestPay");
			$arrayListCreditTotalPay		= /*inicio get post*/ $this->request->getPost("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= /*inicio get post*/ $this->request->getPost("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= /*inicio get post*/ $this->request->getPost("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= /*inicio get post*/ $this->request->getPost("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= /*inicio get post*/ $this->request->getPost("txtCreditDateLastPay");
			$arrayListCreditTerm			= /*inicio get post*/ $this->request->getPost("txtCreditTerm");
			$arrayListCreditNote			= /*inicio get post*/ $this->request->getPost("txtCreditNote");
			$arrayListCreditLine			= /*inicio get post*/ $this->request->getPost("txtLine");
			$arrayListCreditNumber			= /*inicio get post*/ $this->request->getPost("txtLineNumber");
			$arrayListCreditLimit			= /*inicio get post*/ $this->request->getPost("txtLineLimit");
			$arrayListCreditBalance			= /*inicio get post*/ $this->request->getPost("txtLineBalance");
			$arrayListCreditStatus			= /*inicio get post*/ $this->request->getPost("txtLineStatus");
			$arrayListTypeAmortization		= /*inicio get post*/ $this->request->getPost("txtTypeAmortization");
			
			$limitCreditLine 				= 0;
			if(!empty($arrayListCustomerCreditLineID))
			foreach($arrayListCustomerCreditLineID as $key => $value){
				$objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
				$objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
				$objCustomerCreditLine["entityID"]		= $entityID;
				$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
				$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
				$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
				$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
				$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
				$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
				$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
				$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
				$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
				$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
				$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
				$objCustomerCreditLine["dateLastPay"]	= NULL;
				$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
				$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
				$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
				$objCustomerCreditLine["isActive"]		= 1;
				$objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
				$limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
				$exchangeRate 								= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCustomerCreditLine["currencyID"]);
				$exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
				$this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
				
				//sumar los limites en dolares
				if($exchangeRate == 1)
					$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
				//sumar los limite en cordoba
				else
					$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);
				
				
			}
			
			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
			throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID, 0700);
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxc_customer/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');	
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
	function save($mode=""){
			$mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtCountryID","Pais","required");
			$this->validation->setRule("txtStateID","Departamento","required");
			$this->validation->setRule("txtCityID","Municipio","required");
			$this->validation->setRule("txtIdentification","Identificacion","required");
				
				
			//Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');
				exit;
			} 
			
			//Guardar o Editar Registro						
			if($mode == "new"){
				$this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				$this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_cxc_customer/add');
				exit;
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
			 
						
			$dataView							= null;
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			$objComponentAccount				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new \Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID);
			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer","identificationType",$companyID);
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","countryID",$companyID);
			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","clasificationID",$companyID);
			$dataView["objListCustomerTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","customerTypeID",$companyID);
			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer","categoryID",$companyID);
			$dataView["objListSubCategoryID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","subCategoryID",$companyID);
			$dataView["objListTypePay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","typePay",$companyID);
			$dataView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer","payConditionID",$companyID);
			$dataView["objListSexoID"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer","sexoID",$companyID);
			$dataView["objListCurrency"]				= $this->Company_Currency_Model->getByCompany($companyID);
			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);			
			$dataView["objCurrency"]					= $objCurrency;
			$dataView["objComponentAccount"]			= $objComponentAccount;
			
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);			
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxc_customer/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxc_customer/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxc_customer/news_script',$dataView);//--finview
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
	function edit_credit_line(){
			
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
			
			
			
			
			
			
			
			$customerCreditLineID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"customerCreditLineID");//--finuri
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			
			$dataView["objListLine"]			= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]		= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objCustomerCreditLine"] 	= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
			$dataView["objListTypeAmortization"]= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_editcreditline_head',$dataView);//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_editcreditline_body',$dataView);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_editcreditline_script',$dataView);//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_credit_line(){
			
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
			
			
			
			
			$companyID 								= $dataSession["user"]->companyID;
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$dataView["objListLine"]				= $this->Credit_Line_Model->get_rowByCompany($companyID);
			$dataView["objCurrencyList"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
			$dataView["objListPay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objListTypeAmortization"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
			
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_addcreditline_head',$dataView);//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_addcreditline_body',$dataView);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_addcreditline_script',$dataView);//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_email(){
			
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
			
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_addemail_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_addemail_body');//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_addemail_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_phone(){
			
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
			
			$companyID 						= $dataSession["user"]->companyID;
			$data["objListPhoneTypeID"]		= $this->core_web_catalog->getCatalogAllItem("tb_entity_phone","typeID",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxc_customer/popup_addphone_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxc_customer/popup_addphone_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxc_customer/popup_addphone_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	
}
?>