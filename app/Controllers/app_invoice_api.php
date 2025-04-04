<?php
//posme:2023-02-27
namespace App\Controllers;
class app_invoice_api extends _BaseController {
	
    function getLastMovement(){	
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			$companyID 		= $dataSession["user"]->companyID;
			$query			= "CALL pr_inventory_last_item_movement(?);";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$companyID]
			);			
			
			if(isset($objData)){
				$objItemLastMovement			= $objData;
			}
			else{
				$objItemLastMovement			= NULL;
			}
			
				
			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'objItemLastMovement'   => $objItemLastMovement
			));//--finjson
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			
		}		
			
	 }
	 
	 function getNumberExoneration()
	 {
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			$companyID 				= $dataSession["user"]->companyID;
			$exonerationNumber		= helper_SegmentsValue($this->uri->getSegments(),"value");//--finuri				
			$objTransactionMaster 	= $this->Transaction_Master_Model->get_rowByNumberExoneration($companyID,$exonerationNumber);
			
				
			return $this->response->setJSON(array(
				'error'   					=> false,
				'message' 					=> SUCCESS,
				'objTransactionMaster'   	=> $objTransactionMaster
			));//--finjson
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			
		}	
	 }
	
	function getLinkPaymentPagadito($companyID=NULL,$transactionID= NULL,$transactionMasterID = NULL){
		try
		{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
		
			
			$mensaje				= "";
			$companyID 				= helper_SegmentsByIndex($this->uri->getSegments(),2,$companyID);
			$transactionID 			= helper_SegmentsByIndex($this->uri->getSegments(),4,$transactionID);
			$transactionMasterID 	= helper_SegmentsByIndex($this->uri->getSegments(),6,$transactionMasterID);
			
			
			//Eliminar el Registro			
			$objTM 	= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$objTMD = $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);			
		
		
			/**************************************/
			//Obtener Datos			
			$parameterSendBox 			= $this->core_web_parameter->getParameter("CORE_PAYMENT_SENDBOX",APP_COMPANY);
			$parameterSendBox 			= $parameterSendBox->value;
			$parameterSendBoxUsuario 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_USUARIO",APP_COMPANY);
			$parameterSendBoxUsuario 	= $parameterSendBoxUsuario->value;
			$parameterSendBoxClave 		= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRUEBA_CLAVE",APP_COMPANY);
			$parameterSendBoxClave 		= $parameterSendBoxClave->value;
			$parameterProduccionUsuario = $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_USUARIO_COMMERCECLIENT",APP_COMPANY);
			$parameterProduccionUsuario = $parameterProduccionUsuario->value;
			$parameterProduccionClave 	= $this->core_web_parameter->getParameter("CORE_PAYMENT_PRODUCCION_CLAVE_COMMERCECLIENTE",APP_COMPANY);
			$parameterProduccionClave 	= $parameterProduccionClave->value;
			
			
			
			$parameterTemporal1 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL001",APP_COMPANY);
			$parameterTemporal2 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL002",APP_COMPANY);
			$parameterTemporal3 		= $this->core_web_parameter->getParameter("CORE_TEMPORAL003",APP_COMPANY);
			$fechaNow  				  	= date("YmdHis");
			$sendBox 					= $parameterSendBox == "true"? true : false;
			
			$uidt 						= "";
			$wskt 						= "";
			$urlProducto 				= base_url();
			if($sendBox)
			{
				$uidt = $parameterSendBoxUsuario;
				$wskt = $parameterSendBoxClave;
			}
			else
			{
				$uidt = $parameterProduccionUsuario;
				$wskt = $parameterProduccionClave;
			}
			
			
		
			
			$company		= $dataSession["company"];
			$nombre 		= "invoice  ".$company->name;
			$numberFactura 	= "FCCLI__".$company->flavorID."__".$fechaNow;
			
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
				 
				
				if ($objTMD) 
				{
					foreach($objTMD as $itemD)
					{
						$Pagadito->add_detail($itemD->quantity,$itemD->itemNameLog, $itemD->unitaryAmount,$urlProducto);
					}
					
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
							$mensaje = $Pagadito->get_rs_code().": ".$Pagadito->get_rs_message();;					
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
						break;
				}
			}
			/**************************************/
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => $mensaje
			));//--finjson
			
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			
		}	
		
	}
	
	function getTransactionMaster(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
		
			
			//Nuevo Registro
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");				
			$transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
			
			
			//Eliminar el Registro			
			$objTM 	= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$objTMD = $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);			
		
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'objTM'   => $objTM,
				'objTMD'  => $objTMD
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
	
	function getItemCantidad($itemID = null,$warehouseID = null){
		$itemID 	 = helper_SegmentsByIndex($this->uri->getSegments(),1,$itemID);
		$warehouseID = helper_SegmentsByIndex($this->uri->getSegments(),2,$warehouseID);
		try{ 
			
			
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();			
			
			////PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}
			
			//Cargar Librerias			
			
			
			//Obtener Parametros			
			$companyID 		= $dataSession["user"]->companyID;
			if(!$companyID && !$itemID && !$warehouseID){
					throw new \Exception(NOT_PARAMETER);		
			} 
			
			
			
			$objItemWarehouse = $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);
			
			//Obtener Resultados.
			
			return $this->response->setJSON(array(
				'error'   			=> false,
				'message' 			=> SUCCESS,			
				'objItemWarehouse' 	=> $objItemWarehouse
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	
	function getValidExistencia($warehouseID = null,$itemID = null,$quantity = null){		
		$warehouseID 		 	= helper_SegmentsByIndex($this->uri->getSegments(),2,$warehouseID);
		$itemID 	 			= helper_SegmentsByIndex($this->uri->getSegments(),4,$itemID);
		$quantity 				= helper_SegmentsByIndex($this->uri->getSegments(),6,$quantity);
		try{ 
			
			
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();			
			
			////PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}
			
			
			
			//Obtener Parametros			
			$companyID 		= $dataSession["user"]->companyID;
			if(!$companyID && !$itemID && !$warehouseID){
					throw new \Exception(NOT_PARAMETER);		
			} 
			
			
			$itemID 									= explode(",",$itemID);
			$quantity 									= explode(",",$quantity);
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			$resultValidate 							= array();
			
			foreach($itemID as $key => $value){					
				$qty 				= $quantity[$key];				
				$objItem 			= $this->Item_Model->get_rowByPK($companyID,$value);					
				$objItemWarehouse 	= $this->Itemwarehouse_Model->getByPK($companyID,$value,$warehouseID);
				
				
				if($objItem){
					if(
						$objItemWarehouse->quantity < $qty 
						&& 
						$objItem->isInvoiceQuantityZero == 0
						&&						
						$objParameterInvoiceBillingQuantityZero == "false"
					){
						
						
						$itemArray = array();
						$itemArray["Codigo"]  				= $objItem->itemNumber;
						$itemArray["Nombre"]  				= $objItem->name;
						$itemArray["Mensaje"] 				= "Existencia agotada";
						$itemArray["QuantityInWarehouse"] 	= $objItemWarehouse->quantity;
						array_push($resultValidate,$itemArray);
					}
				}
				
				
				
			}
			
			
			//Obtener Resultados.			
			return $this->response->setJSON(array(
				'error'   			=> false,
				'message' 			=> SUCCESS,			
				'resultValidate' 	=> $resultValidate
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	
	function getViewApiJsonTable($componentid = "",$fnCallback = "",$viewname = "",$filter=""){
		try{  
		
			$componentid		= helper_SegmentsByIndex($this->uri->getSegments(),1,$componentid);
			$fnCallback			= helper_SegmentsByIndex($this->uri->getSegments(),2,$fnCallback);
			$viewname			= helper_SegmentsByIndex($this->uri->getSegments(),3,$viewname);			
			$filter				= helper_SegmentsByIndex($this->uri->getSegments(),4,$filter);
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
			
			$parameter["{companyID}"]	= $this->session->get('user')->companyID;
			$viewname 					= urldecode($viewname);
			
			$filter 					= urldecode($filter);
			
			$result 					= $this->core_web_tools->formatParameter($filter);			
			
			if($result)
			$parameter 					= array_merge($parameter,$result);
			
			
			
			$sEcho								= helper_RequestGetValue($this->request->getGetPost("sEcho"),1);
			$iDisplayStart						= helper_RequestGetValue($this->request->getGetPost("iDisplayStart"),0);
			$iDisplayLength						= helper_RequestGetValue($this->request->getGetPost("iDisplayLength"),10);
			$sSearch							= helper_RequestGetValue($this->request->getGetPost("sSearch"),"");
			$sWarehouseID							= helper_RequestGetValue($this->request->getGetPost("warehouseID"),"");
			$sTypePriceID							= helper_RequestGetValue($this->request->getGetPost("typePriceID"),"");
			$sCurrencyID							= helper_RequestGetValue($this->request->getGetPost("currencyID"),"");
			$parameter["{iDisplayStart}"]			= $iDisplayStart;
			$parameter["{iDisplayLength}"]			= $iDisplayLength;
			$parameter["{sSearch}"]					= $sSearch;
			$parameter["{warehouseID}"]				= $sWarehouseID;
			$parameter["{typePriceID}"]				= $sTypePriceID;
			$parameter["{currencyID}"]				= $sCurrencyID;
			
			
			
			$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname,CALLERID_SEARCH,null,$parameter); 				
			$dataViewDataTotal			= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname."_TOTAL",CALLERID_SEARCH,null,$parameter); 				
			$dataViewDataDiplay			= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname."_DISPLAY",CALLERID_SEARCH,null,$parameter); 				
			$dataViewDataTotal 			= $dataViewDataTotal["view_data"][0]["itemID"];
			$dataViewDataDiplay 		= $dataViewDataDiplay["view_data"][0]["itemID"];
			
			
			$objetoAnonimo 				= (object) [
				"sEcho" => $sEcho,
				"iTotalRecords" 		=> $dataViewDataTotal,
				"iTotalDisplayRecords" 	=> $dataViewDataDiplay,
				'aaData' => $dataViewData["view_data"]
			];
			
			//Obtener Resultados.			
			return $this->response->setJSON( $objetoAnonimo );//--finjson			
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
	
	function getViewApi($componentid = "",$fnCallback = "",$viewname = "",$filter=""){
		try{  
		
			$componentid		= helper_SegmentsByIndex($this->uri->getSegments(),1,$componentid);
			$fnCallback			= helper_SegmentsByIndex($this->uri->getSegments(),2,$fnCallback);
			$viewname			= helper_SegmentsByIndex($this->uri->getSegments(),3,$viewname);			
			$filter				= helper_SegmentsByIndex($this->uri->getSegments(),4,$filter);
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
			
			$parameter["{companyID}"]	= $this->session->get('user')->companyID;
			$viewname 					= urldecode($viewname);
			
			$filter 					= urldecode($filter);
			
			$result 					= $this->core_web_tools->formatParameter($filter);			
			
			if($result)
			$parameter 					= array_merge($parameter,$result);
			
			
			
			$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$componentid,$viewname,CALLERID_SEARCH,null,$parameter); 			
			
			//Obtener Resultados.			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'objGridView'	 => $dataViewData["view_data"]
			));//--finjson			
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
	
	function getLineByCustomerOnly(){
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
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
			//Cargar Librerias
			
			
			
			//Obtener Parametros
			$entityID 		= /*inicio get post*/ $this->request->getPost("entityID");//--fin peticion get o post
			$companyID 		= $dataSession["user"]->companyID;
			$branchID 		= $dataSession["user"]->branchID;
			if(!$companyID && !$entityID){
					throw new \Exception(NOT_PARAMETER);		
			} 
			
			//Obtener tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			
			
			//Obtener Lineas de Credito
			$objListCustomerCreditLine2 	= $this->Customer_Credit_Line_Model->get_rowByBranchID($companyID,$branchID);
			$objListCustomerCreditLine 		= null;
			$counter 						= 0;
			
			if($objListCustomerCreditLine2)
			{
				foreach($objListCustomerCreditLine2 as $key => $value){
					if($value->balance > 0)
					{
						$objListCustomerCreditLine[$counter] = $value;
						$counter++;
					}
				}
			}
			
			//Obtener Resultados.
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,							
				'objListCustomerCreditLine'	  	=> $objListCustomerCreditLine,
				'objExchangeRate'				=> $exchangeRate,
				'objCausalTypeCredit'			=> $parameterCausalTypeCredit,
				'objCurrencyDolares' 			=> $objCurrencyDolares,
				'objCurrencyCordoba' 			=> $objCurrencyCordoba
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
		
	}
	
	function getLineByCustomerAll(){
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
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
		
			
			//Obtener Parametros			
			$companyID 		= $dataSession["user"]->companyID;
			$branchID 		= $dataSession["user"]->branchID;
			
			//Obtener tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			
			//Obtener Lineas de Credito
			$objListCustomerCreditLine 				= $this->Customer_Credit_Line_Model->get_rowByBranchID($companyID,$branchID);
			
			
			//Obtener Resultados.			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,							
				'objListCustomerCreditLine'	  		=> $objListCustomerCreditLine,
				'objExchangeRate'					=> $exchangeRate,
				'objCausalTypeCredit'				=> $parameterCausalTypeCredit,
				'objCurrencyDolares' 				=> $objCurrencyDolares,
				'objCurrencyCordoba' 				=> $objCurrencyCordoba				
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
			
			
		}
	}
	
	function getLineByCustomer(){
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
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
			//Cargar Librerias
			
			
			
			//Obtener Parametros
			$entityID 		= /*inicio get post*/ $this->request->getPost("entityID");//--fin peticion get o post
			$companyID 		= $dataSession["user"]->companyID;
			if(!$companyID && !$entityID){
					throw new \Exception(NOT_PARAMETER);		
			} 
			
			//Obtener tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			//Obtener Cliente
			$objCustomer 					= $this->Customer_Model->get_rowByEntity($companyID,$entityID);
			$branchID 						= ($objCustomer != null ? $objCustomer->branchID : 0);
			//Obtener Lineas de Credito
			$objListCustomerCreditLine2 	= $this->Customer_Credit_Line_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$objListCustomerCreditLine 		= null;
			$counter 						= 0;
			
			if($objListCustomerCreditLine2)
			{
				foreach($objListCustomerCreditLine2 as $key => $value){
					if($value->balance > 0)
					{
						$objListCustomerCreditLine[$counter] = $value;
						$counter++;
					}
				}
			}
			
			//Obtener Tabla de Amortizacion del cliente
			$objCustomerCreditAmoritizationAll	= $this->Customer_Credit_Amortization_Model->get_rowByCustomerID($entityID);
			
			
			//Obtener Resultados.			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'objCustomer'	  					=> $objCustomer,
				'objListCustomerCreditLine'	  		=> $objListCustomerCreditLine,
				'objExchangeRate'					=> $exchangeRate,
				'objCausalTypeCredit'				=> $parameterCausalTypeCredit,
				'objCurrencyDolares' 				=> $objCurrencyDolares,
				'objCurrencyCordoba' 				=> $objCurrencyCordoba,
				'objCustomerCreditAmoritizationAll' => $objCustomerCreditAmoritizationAll,
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
			
			
		}
	}

	function getLineByEntity(){
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
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
			//Obtener Parametros
			$entityID 		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("entityID"));//--fin peticion get o post
			$companyID 		= $dataSession["user"]->companyID;
			$branchID 		= $dataSession["user"]->branchID;
			if(!$companyID && !$entityID){
				throw new \Exception(NOT_PARAMETER);		
			} 
			
			//Obtener tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			//Obtener ENTIDAD
			$objEntity 						= $this->Customer_Model->get_rowByPK($companyID, $branchID, $entityID);
			if(is_null($objEntity))
			{
				$objEntity						= $this->Employee_Model->get_rowByPK($companyID, $branchID, $entityID);
			}

			if(is_null($objEntity))
			{
				$objEntity						= $this->Provider_Model->get_rowByPK($companyID, $branchID, $entityID);	
			}
					
			//Obtener Lineas de Credito
			$objListEntityCreditLine2 		= $this->Customer_Credit_Line_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$objListEntityCreditLine 		= null;
			$counter 						= 0;
			
			if($objListEntityCreditLine2)
			{
				foreach($objListEntityCreditLine2 as $key => $value){
					if($value->balance > 0)
					{
						$objListEntityCreditLine[$counter] = $value;
						$counter++;
					}
				}
			}
						
			//Obtener Resultados.			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'objCustomer'	  					=> $objEntity,
				'objListCustomerCreditLine'	  		=> $objListEntityCreditLine,
				'objExchangeRate'					=> $exchangeRate,
				'objCausalTypeCredit'				=> $parameterCausalTypeCredit,
				'objCurrencyDolares' 				=> $objCurrencyDolares,
				'objCurrencyCordoba' 				=> $objCurrencyCordoba,
			));//--finjson			
			
		}
		catch(\Exception $ex){
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson				
		}
	}
	
	function getInforDashBoards(){
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
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}
			
			
			//Obtener Parametros
			$companyID 		= $dataSession["user"]->companyID;
			if((!$companyID)){
					throw new \Exception(NOT_PARAMETER);		
			} 
			
			$countInvoice		= $this->core_web_transaction->getCountTransactionBillingAnuladas($companyID);
			$countInvoiceCancel	= $this->core_web_transaction->getCountTransactionBillingCancel($companyID);
			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'countinvoice'	  			=> $countInvoice,
				'countinvoicecancel'	  	=> $countInvoiceCancel
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
}
?>