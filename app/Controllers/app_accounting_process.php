<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_process extends _BaseController {
   
	function downloadTipoCambio(){
		
		try{ 
		
			/* 
			//AUTENTICADO 
			*/
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}					
			 
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;			
			$componentPeriodID	= /*inicio get post*/ $this->request->getPost("componentPeriodID");
			$componentCycleID	= /*inicio get post*/ $this->request->getPost("componentCycleID");			
			
			//Obtener el Periodo
			$Cycle			= $this->Component_Cycle_Model->get_rowByPK($componentPeriodID,$componentCycleID);
			$objParameter 	= $this->core_web_parameter->getParameter("CORE_ACCOUNTING_WSDL_DER",$companyID);
			
			//"https://servicios.bcn.gob.ni/Tc_Servicio/ServicioTC.asmx?WSDL"			
			//			
			//$xml = file_get_contents('https://servicios.bcn.gob.ni/Tc_Servicio/ServicioTC.asmx?WSDL');
			//
			
			$dateInicial 	= new \DateTime($Cycle->startOn);
			$dateFinal 		= new \DateTime($Cycle->endOn);			
			$client 		= new \SoapClient($objParameter->value,[
				"stream_context" => stream_context_create(
				   array( 
					  'ssl' => array(
						   'verify_peer'       => false,
						   'verify_peer_name'  => false,
					  )
				   )
				)
			]);
			
			
			
			
			
			
			$objCurrencySource	= $this->core_web_currency->getCurrencyDefault($companyID);
			$objCurrencyTarget	= $this->core_web_currency->getCurrencyExternal($companyID);		
			$currencyIDSource	= $objCurrencySource->currencyID;
			$currencyIDTarget	= $objCurrencyTarget->currencyID;
			
			
				
			while ($dateInicial <= $dateFinal){
				
				
				$params 				= array(
					"Ano"					=> $dateInicial->format("Y"),
					"Mes" 					=> $dateInicial->format("m"),
					"Dia" 					=> $dateInicial->format("d")
				);
				
				
				//Tipo de Cambio del Dia
				$resultado 		= $client->RecuperaTC_Dia( $params );			
				$excangeRate 	= $resultado->RecuperaTC_DiaResult;
				$objExchangeRate 	= $this->Exchangerate_Model->get_rowByPK($companyID,$dateInicial->format("Y-m-d"),$currencyIDSource,$currencyIDTarget);
				
				if($objExchangeRate)
				{										
					
					$data			= NULL;
					$data["ratio"] 	= $excangeRate;
					$this->Exchangerate_Model->update_app_posme($companyID,$dateInicial->format("Y-m-d"),$currencyIDSource,$currencyIDTarget,$data);
					
				}
				//Insertar
				else{					
					
					$data						= NULL;
					$data["companyID"] 			= $companyID;
					$data["date"] 				= $dateInicial->format("Y-m-d");
					$data["currencyID"] 		= $currencyIDSource;
					$data["targetCurrencyID"] 	= $currencyIDTarget;
					$data["ratio"] 				= $excangeRate;					
					$this->Exchangerate_Model->insert_app_posme($data);
				}
				
				
				//Siguiente Fecha
				$dateInicial = date_add($dateInicial, date_interval_create_from_date_string('1 days'));
			}
			
			$logDB["code"] = 0;
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	
			
	}
	function contabilizateDocument(){
		try{ 
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
			
			
			
			
			$companyID				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$loginID				= $dataSession["user"]->userID;
			$transactionID			= /*inicio get post*/ $this->request->getPost("transactionID");			
			$objListTransaction		= $this->Transaction_Model->getTransactionContabilizable($companyID);
			$app					= "CONTABILIZATE";
			$logDB					= NULL;
			
		
			if($transactionID == 0){
				foreach( $objListTransaction as $item)
				{
					$transactionID			= $item->transactionID;
					$query					= "CALL pr_accounting_transaction_to_journal(?,?,?,?,?,@resultTransaction);";
					$resultTransaction		= $this->Bd_Model->executeRender(
						$query,
						[$companyID,$branchID,$loginID,$transactionID,$app]
					);							
					$logDB					= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$app);
				}
			}					
			else {
				$query					= "CALL pr_accounting_transaction_to_journal(?,?,?,?,?,@resultTransaction);";
				$resultTransaction		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$branchID,$loginID,$transactionID,$app]
				);							
				$logDB					= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$app);
			}
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
		
	}
	function execNotification(){
		try{ 
			//AUTENTICADO 
			$dataSession			= $this->session->get();
			$companyID				= $this->core_web_authentication->isAuthenticated() ? $dataSession["user"]->companyID : 2;
			$branchID 				= $this->core_web_authentication->isAuthenticated() ? $dataSession["user"]->branchID : 2;
			$loginID				= $this->core_web_authentication->isAuthenticated() ? $dataSession["user"]->userID : 2;
			$notificationName		= /*inicio get post*/ $this->request->getVar("notificationName") == "" ? "TODAS" : /*inicio get post*/ $this->request->getVar("notificationName") ;	
			$logDB["code"]			= 0;
			
			
			
			/*TODAS*/
			$ex = curl_init();
			curl_setopt($ex, CURLOPT_URL, base_url()."/app_notification/".$notificationName);
			curl_setopt($ex, CURLOPT_HEADER, 0);
			$er = curl_exec($ex);
			$er = curl_close($ex);
												
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
			)); //--finjson
			
			
		}
		catch(\Exception $ex)
		{
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
		
	}
	function clearNotification(){
		try{ 
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
			
			
			
			
			
			$companyID				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$loginID				= $dataSession["user"]->userID;
			$tagID					= /*inicio get post*/ $this->request->getPost("tagID");			
			$logDB["code"]			= 0;
			$data 					= null;
			$data["isRead"]			= 1;
			$data["readOn"]			= date("Y-m-d");
			if($tagID == -1){
				$resultDB 				= $this->Error_Model->updateTagID(1,$companyID,$data);
				$resultDB 				= $this->Error_Model->updateTagID(2,$companyID,$data);
				$resultDB 				= $this->Error_Model->updateTagID(5,$companyID,$data);
				$resultDB 				= $this->Error_Model->deleteByTagID(5,$companyID);
				$resultDB 				= $this->Error_Model->updateTagID(6,$companyID,$data);
				$resultDB 				= $this->Error_Model->updateTagID(7,$companyID,$data);
			}
			else if($tagID <> 5 )
				$resultDB 				= $this->Error_Model->updateTagID($tagID,$companyID,$data);
			else
			{
				$resultDB 				= $this->Error_Model->updateTagID(5,$companyID,$data);
				$resultDB 				= $this->Error_Model->deleteByTagID(5,$companyID);
			} 
															
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $logDB
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
		
	}
	function mayorizateCycle(){
		try{ 
		
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
				
			
			//
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$componentPeriodID	= /*inicio get post*/ $this->request->getPost("componentPeriodID");
			$componentCycleID	= /*inicio get post*/ $this->request->getPost("componentCycleID");
			
			$query				= "
			CALL pr_accounting_mayorizate_cycle(?,?,?,?,?,@resultMayorization);
			";
			
			$resultMayorizate	= $this->Bd_Model->executeRender(
				$query,
				[$companyID,$branchID,$loginID,$componentPeriodID,$componentCycleID]
			);	
			
			$resultMayorizate	= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,'');
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $resultMayorizate
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
			
	}
	function closedCycle(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$createdIn			= $this->request->getIPAddress();
			$tocken				= ''; 
			$componentPeriodID	= /*inicio get post*/ $this->request->getPost("componentPeriodID");
			$componentCycleID	= /*inicio get post*/ $this->request->getPost("componentCycleID");
			
			
			$query				= "			
			CALL pr_accounting_closed_cycle(?,?,?,?,?,?,?,@resultCode,@resultMessage);			
			";			
			$resultClosed		= $this->Bd_Model->executeRender(
				$query,
				[$companyID,$branchID,$loginID,$createdIn,$tocken,$componentPeriodID,$componentCycleID]
			);	
			$resultClosed		= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$tocken);
			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $resultClosed
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}		
			
	}	
	function index($dataViewID = null){	
	try{ 
			$dataSession		= $this->session->get();
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LA FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
			
			}	
			
			
			
			
			$objCompanyParameter 				= $this->core_web_parameter->getParameter("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$dataV["objListAccountingPeriod"]	= $this->Component_Period_Model->get_rowByNotClosed($dataSession["user"]->companyID,$objCompanyParameter->value);
			$dataV["objListTransaction"]		= $this->Transaction_Model->getTransactionContabilizable($dataSession["user"]->companyID);
			$dataV["objListTag"]				= $this->Tag_Model->get_rows();
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_process/view_head',$dataV);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_process/view_body',$dataV);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_process/view_script',$dataV);//--finview
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
	
}
?>