<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_currency extends _BaseController {
	
   
  
	function process_view_report(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"currency_core_function",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
			}	 
			
									
			$startOn		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri	
			$companyID 		= $dataSession["user"]->companyID;			
			
			//Cargar Libreria
			
				
									
		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Datos
			$objData		= $this->Exchangerate_Model->getByCompanyAndDate($companyID,$startOn,$endOn);								
			//Set Columnas
			$objColumn 		= array('date'=>'Fecha','nameSource' => 'Moneda Local','ratio'=>'Equivale A','nameTarget' => 'Moneda Extranjera');
			//Set Nombre del Reporte
			$reportName		= "TASA DE CAMBIO";
			//Set Info File
			
			if(isset($objData))
			$objDataResult["objDetail"]					= $objData;
			else
			$objDataResult["objDetail"]					= NULL;
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objLogo"] 					= $objParameter;	
			$objDataResult["objParameterTamanoLetra"] 	= "small";	
			$objDataResult["objParameterAltoDeLaFila"] 	= "small";				
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "process_view_report" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			
			
			return view("app_account_currency/process_view_report/view_a_disemp",$objDataResult);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
	function process_file_update_exchange_rate()
	{
		try{ 
		
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"currency_core_function",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);								
			
			}	
			
		
			$companyID 		= $dataSession["user"]->companyID;	
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company_currency");
			
			$ratio 		= $this->request->getPost("value");
			$startOn 	= $this->request->getPost("startOn");
			$endOn 		= $this->request->getPost("endOn");
			
			date_default_timezone_set(APP_TIMEZONE); 
			
			$currencyIDTargetCordoba			= $this->core_web_currency->getCurrencyDefault($companyID);
			$currencyIDSourceDolar				= $this->core_web_currency->getCurrencyExternal($companyID);
			$startOn	= \DateTime::createFromFormat('Y-m-d', $startOn);
			$endOn		= \DateTime::createFromFormat('Y-m-d', $endOn);
			
			// Mientras $fecha1 sea menor que $fecha2
			while ($startOn <= $endOn) {
				
				//Buscar si existe La Tasa de Cambio
				$date 			 = $startOn->format('Y-m-d');
				
				
				//Procesar de De Cordoba a Dolar
				$objExchangeRate = $this->Exchangerate_Model->get_rowByPK($companyID,$date,$currencyIDSourceDolar->currencyID,$currencyIDTargetCordoba->currencyID);
				if($objExchangeRate){					
					
					$data["ratio"] = $ratio;
					$this->Exchangerate_Model->update_app_posme($companyID,$date,$currencyIDSourceDolar->currencyID,$currencyIDTargetCordoba->currencyID,$data);
					
				}
				else{					
					$data["companyID"] 			= $companyID;
					$data["date"] 				= $date;
					$data["currencyID"] 		= $currencyIDSourceDolar->currencyID;
					$data["targetCurrencyID"] 	= $currencyIDTargetCordoba->currencyID;
					$data["ratio"] 				= $ratio;					
					$this->Exchangerate_Model->insert_app_posme($data);
				}




				//Procesar de De Dolar A Cordoba
				$objExchangeRate = $this->Exchangerate_Model->get_rowByPK($companyID,$date,$currencyIDTargetCordoba->currencyID,$currencyIDSourceDolar->currencyID);
				if($objExchangeRate){					
					
					$data["ratio"] = 1/$ratio;
					$this->Exchangerate_Model->update_app_posme($companyID,$date,$currencyIDTargetCordoba->currencyID,$currencyIDSourceDolar->currencyID,$data);
					
				}
				else{					
					$data["companyID"] 			= $companyID;
					$data["date"] 				= $date;
					$data["currencyID"] 		= $currencyIDTargetCordoba->currencyID;
					$data["targetCurrencyID"] 	= $currencyIDSourceDolar->currencyID;
					$data["ratio"] 				= 1/$ratio;					
					$this->Exchangerate_Model->insert_app_posme($data);
				}

				// Incrementa la fecha en un día
				$startOn->modify('+1 day');
			}

			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			$this->core_web_notification->set_message(false,SUCCESS);
		
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}	
	}
	
	function process_file(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"currency_core_function",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);								
			
			}	
			
						
									
			
			 			
		
			$companyID 		= $dataSession["user"]->companyID;	
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company_currency");
			$filePath 		= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_0/";
			$fileName 		= $filePath./*inicio get post*/ $this->request->getPost("fileName");
			date_default_timezone_set(APP_TIMEZONE); 
	
			//Existe el Archivo
			$fila = 0;
			if (!file_exists($fileName))
			throw new \Exception("NO EXISTE EL ARCHIVO MENCIONADO");
			
			//Leer el Archivo			
			$objParameterSplit	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
			$characterSplie 	= $objParameterSplit->value;
				
			$this->csvreader->separator = $characterSplie;				
			$csvTable 		= $this->csvreader->parse_file($fileName); 
			$lineNumber		= 0;
			
			
			foreach ($csvTable as $csvRow) 
			{	
				$lineNumber++;
				if(
					!isset($csvRow["FECHA"])  || empty($csvRow["FECHA"]) || is_null($csvRow["FECHA"]) || 
					!isset($csvRow["MONEDA_LOCAL"])  || empty($csvRow["MONEDA_LOCAL"]) || is_null($csvRow["MONEDA_LOCAL"]) || 
					!isset($csvRow["TASA_DE_CAMBIO"])   || empty($csvRow["TASA_DE_CAMBIO"]) || is_null($csvRow["TASA_DE_CAMBIO"]) || 
					!isset($csvRow["MONEDA_EXTRANJERA"])   || empty($csvRow["MONEDA_EXTRANJERA"]) || is_null($csvRow["MONEDA_EXTRANJERA"]) || 
					!isset($csvRow["TIPO"])  || empty($csvRow["TIPO"]) || is_null($csvRow["TIPO"]) 
				)
				throw new \Exception("REVISAR LINEA ".$lineNumber);
				
				
				$date 				= date($csvRow["FECHA"]);
				$nameSource 		= $csvRow["MONEDA_LOCAL"];
				$objCurrencySource 	= $this->Currency_Model->get_rowName($nameSource);
				$value 				= $csvRow["TASA_DE_CAMBIO"];
				$nameTarget 		= $csvRow["MONEDA_EXTRANJERA"];
				$objCurrencyTarget 	= $this->Currency_Model->get_rowName($nameTarget);
				$type 				= $csvRow["TIPO"];
				
				
				
				//Validar Fecha					
				try{
					$date = date_format(date_create($date),"Y-m-d");					
				}
				catch(\Exception $e){
					throw new \Exception("FECHA TIENE FORMATO (YYYY-MM-DD) INCORRECTO REVISAR LINEA ".$lineNumber);
				}
				
				
				if($date == "0000-00-00")
				throw new \Exception("FECHA TIENE FORMATO (YYYY-MM-DD) INCORRECTO REVISAR LINEA ".$lineNumber);
				 
				//Validar Mas Campos
				if(empty($value) || $value == 0)
				throw new \Exception("TASA DE CAMBIO NO PUEDE SER 0 REVISAR LINEA ".$lineNumber);						
				if(!$objCurrencySource)
				throw new \Exception("MONEDA LOCAL NO EXISTE REVISAR LINEA ".$lineNumber);
				if(!$objCurrencyTarget)
				throw new \Exception("MONEDA EXTRANJERA NO EXISTE REVISAR LINEA  ".$lineNumber);						
				
				
				$ratio				= $value;
				$currencyIDSource	= $objCurrencySource->currencyID;
				$currencyIDTarget	= $objCurrencyTarget->currencyID;
				
				
				//Buscar si existe La Tasa de Cambio
				
				$objExchangeRate = $this->Exchangerate_Model->get_rowByPK($companyID,$date,$currencyIDSource,$currencyIDTarget);
				
				
				
				
				//Actualizar
				if($objExchangeRate){					
					
					$data["ratio"] = $ratio;
					$this->Exchangerate_Model->update_app_posme($companyID,$date,$currencyIDSource,$currencyIDTarget,$data);
					
				}
				//Insertar
				else{
					
					$data["companyID"] 			= $companyID;
					$data["date"] 				= $date;
					$data["currencyID"] 		= $currencyIDSource;
					$data["targetCurrencyID"] 	= $currencyIDTarget;
					$data["ratio"] 				= $ratio;					
					$this->Exchangerate_Model->insert_app_posme($data);
				}
				
			}
			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			$this->core_web_notification->set_message(false,SUCCESS);
		
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}	
	}
	
	function process(){
		try{ 
			//AUTENTICADO		
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"currency_core_function",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
					
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
			
			}	
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_currency/process_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_currency/process_body');//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_currency/process_script');//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}	
	}
	
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
									
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$currencyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"currencyID");//--finuri	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$currencyID))
			{ 
				$this->response->redirect(base_url()."/".'app_accounting_currency/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objCompanyCurrency"]	= $this->Company_Currency_Model->get_rowByPK($companyID,$currencyID);			
			$datView["objListCurrency"] 	= $this->Currency_Model->getList();
			
			
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_currency/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_currency/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_currency/edit_script',$datView);//--finview
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
			$companyID 		= /*inicio get post*/ $this->request->getPost("companyID");
			$currencyID 	= /*inicio get post*/ $this->request->getPost("currencyID");				
			
			if((!$companyID && !$currencyID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 			
		
			//Eliminar el Registro
			$this->Company_Currency_Model->delete_app_posme($companyID,$currencyID);
					
			
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
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////						
			
			//Validar Formulario						
			$this->validation->setRule("txtCurrencyID","Currency","required");    
			$this->validation->setRule("txtSimb","Simb","required|max_length[5]|min_length[1]");
			
			 
			//Nuevo Registro			
			$continue	= true;
			if( $method == "new" && $this->validation->withRequest($this->request)->run() == true ){
					
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
						$obj["companyID"]			= $dataSession["user"]->companyID;
						$obj["currencyID"] 			= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
						$obj["simb"] 				= /*inicio get post*/ $this->request->getPost("txtSimb");
						
						
						$result						= $this->Company_Currency_Model->insert_app_posme($obj);
						$companyID 					= $obj["companyID"];
						$currencyID 				= $obj["currencyID"];
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_accounting_currency/edit/companyID/'.$companyID."/currencyID/".$currencyID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_accounting_currency/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_accounting_currency/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->validation->withRequest($this->request)->run() == true) {
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
						$companyID 			= /*inicio get post*/ $this->request->getPost("txtCompanyID");
						$currencyID 		= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
						$obj["simb"] 		= /*inicio get post*/ $this->request->getPost("txtSimb");					
						$result 			= $this->Company_Currency_Model->update_app_posme($companyID,$currencyID,$obj);						
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_accounting_currency/edit/companyID/'.$companyID."/currencyID/".$currencyID);
					}					
					else{
						$this->response->redirect(base_url()."/".'app_accounting_currency/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_currency/add');	
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
			
			  
			$dataView["objListCurrency"] = $this->Currency_Model->getList();
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_currency/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_currency/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_currency/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company_currency");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'CompanyCurrency' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_account_currency/list_head',$dataView);//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_account_currency/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_account_currency/list_script');//--finview
			$dataSession["script"] 			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>