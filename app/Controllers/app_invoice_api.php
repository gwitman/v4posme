<?php
//posme:2023-02-27
namespace App\Controllers;
class app_invoice_api extends _BaseController {
	
    
	
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
			echo $ex->getMessage();
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
			
			//Obtener Resultados.
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'objCustomer'	  				=> $objCustomer,
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