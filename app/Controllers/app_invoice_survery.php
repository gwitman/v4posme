<?php
//posme:2023-02-27
namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use CodeIgniter\Database\Exceptions\DatabaseException;

class app_invoice_survery extends _BaseController {


	
	function index(){	
		try{
			
			//Ininicializar Cache
			//$cache 				= Services::cache();
			//$this->cachePage( TIME_CACHE_APP );	
			
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_survery");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_survery' NO EXISTE...");
			
			$key				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"key");//--finuri			
			$dataview["key"]	= $key;
			
			$companyID					= APP_COMPANY;
			$objListPrice 				= $this->List_Price_Model->getListPriceToApply($companyID);
			$dataview["objListItem"]	= $this->Item_Model->get_rowByItemReference1($objListPrice->listPriceID,$key);
			
			//Obtener todos los prodcutos con el key
			$dataSession["head"]								= /*--inicio view*/ view('app_invoice_survery/index_head',$dataview);//--finview
			$dataSession["footer"]								= /*--inicio view*/ view('app_invoice_survery/index_footer',$dataview);//--finview
			$dataSession["body"]								= /*--inicio view*/ view('app_invoice_survery/index_body',$dataview);//--finview
			$dataSession["script"]								= /*--inicio view*/ view('app_invoice_survery/index_script',$dataview);//--finview
			return view("app_invoice_survery/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex)
		{
			
		    $data["session"]   = null;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);    
		    $resultView        = view("core_template/email_error_general",$data);
		    return $resultView;
			
		}
	}	
	
	function insertElement(){
	
		try
		{
			
			//Obtener el key;
			$key	= $this->request->getPost("key");
			
			
			//Obtener datos
			$name			= $this->request->getPost("name");
			$phone			= $this->request->getPost("phone");
			$direccion 		= $this->request->getPost("address");
			$listItem		= $this->request->getPost("itemID");
			$listQuantity	= $this->request->getPost("quantity");
			$listPrice		= $this->request->getPost("price");
			
			
			//Buscar el colaborador
			$objColaborador	= $this->Employee_Model->get_rowByItemReference1($key);
			
			//Buscar el cliente
			$objCustomer	= $this->Customer_Model->get_rowByItemReference1($phone,$key);
			
			
			//Obtener el login
			$objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
			$objCompany 				= $objUser["company"];
			$dataSession				= null;
			$dataSession['user'] 		= $objUser["user"];
            $dataSession['company'] 	= $objCompany;
            $dataSession['role'] 		= $objUser["role"];
			$companyID 					= APP_COMPANY;
			$branchID 					= APP_BRANCH;
			$roleID 					= $dataSession['role']->roleID;
			
			//Ingresar al cliente
			if(!$objCustomer)
			{
				$cus					= new \stdClass();
				$cus->companyID 		= APP_COMPANY;
                $cus->branchID 			= APP_BRANCH;
                $cus->entityID 			= 1; 
				$cus->location 			= $direccion;
				$cus->phoneNumber 		= $phone; 
				$cus->phone		 		= $phone; 
				$cus->firstName 		= $name; 
				$cus->lastName 			= $name; 
				$cus->identification 	= $phone;
				$cus->reference1 		= $key;
				$cus->statusID 			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;				
				$controller 			= new app_cxc_customer();
                $controller->initController($this->request, $this->response, $this->logger);				
				$controller->insertElementMobile($dataSession,$cus);
			}
			
			
			
			//Ingresar el transacion master
			$objComponentShare = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_survery");
            if (! $objComponentShare) {
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_survery' NO EXISTE...");
            }
			
			$objComponentItem = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if (! $objComponentItem) {
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
            }
			
			
			$objCustomer	= $this->Customer_Model->get_rowByItemReference1($phone,$key);
			$transactionID 	= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID, "tb_transaction_master_survery", 0);
			$objT          	= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID, $transactionID);
			
			$objTM["companyID"]           = $companyID;
            $objTM["transactionID"]       = $transactionID;
            $objTM["branchID"]            = $branchID;
            $objTM["transactionNumber"]   = $this->core_web_counter->goNextNumber($dataSession["user"]->companyID, $dataSession["user"]->branchID, "tb_transaction_master_survery", 0);
            $objTM["transactionCausalID"] = $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID, $transactionID);
            $objTM["entityID"] 			  = $objCustomer[0]->entityID;
            $objTM["transactionOn"]     = date("Y-m-d H:m:s");
            $objTM["statusIDChangeOn"]  = date("Y-m-d H:m:s");
            $objTM["componentID"]       = $objComponentShare->componentID;
            $objTM["note"]              = "";
            $objTM["sign"]              = $objT->signInventory;
            $objTM["currencyID"]        = $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;	
            $objTM["currencyID2"]       = $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID;
            $objTM["exchangeRate"]      = $this->core_web_currency->getRatio($dataSession["user"]->companyID, date("Y-m-d"), 1, $objTM["currencyID2"], $objTM["currencyID"]);
            $objTM["reference1"]        = "";
            $objTM["reference2"]        = "";
            $objTM["reference3"]        = "";
            $objTM["reference4"]        = '';
            $objTM["statusID"]          = /*inicio get post*/$this->request->getPost("txtStatusID");
            $objTM["amount"]            = 0;
            $objTM["isApplied"]         = 0;
            $objTM["journalEntryID"]    = 0;
            $objTM["classID"]           = 0;
            $objTM["areaID"]            = 0;
            $objTM["priorityID"]        = 0;
            $objTM["sourceWarehouseID"] = null;
            $objTM["targetWarehouseID"] = null;
            $objTM["isActive"]          = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            $db = db_connect();
            $db->transException(true)->transStart();
            $transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			
			//Crear la Carpeta para almacenar los Archivos del Documento
            $pathDocument = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponentShare->componentID . "/component_item_" . $transactionMasterID;
            if (! file_exists($pathDocument)) {
                mkdir($pathDocument, 0700, true);
            }
			
			
			//Ingresar detalle
			$total = 0;
			if (!$listItem) {

	
				foreach($listItem  as $key => $value)
				{
					
					$itemID				 = $listItem[$key];
					$quantity			 = $listQuantity[$key];
					$price			 	 = $listPrice[$key];
					
					if($quantity <= 0)
						continue;
					
					$objTMD                        = null;
					$objTMD["companyID"]           = $objTM["companyID"];
					$objTMD["transactionID"]       = $objTM["transactionID"];
					$objTMD["transactionMasterID"] = $transactionMasterID;
					$objTMD["componentID"]         = $objComponentItem->componentID;
					$objTMD["componentItemID"]     = $itemID;
					$objTMD["quantity"]            = $quantity;
					$objTMD["unitaryCost"]         = 0;
					$objTMD["cost"]                = 0;

					$objTMD["unitaryPrice"]  = $price;
					$objTMD["unitaryAmount"] = 0;
					$objTMD["amount"]        = $price * $quantity;
					$objTMD["discount"]      = 0;
					$objTMD["promotionID"]   = 0;

					$objTMD["reference1"]                 = 0;
					$objTMD["reference2"]                 = 0;
					$objTMD["reference3"]                 = 0;
					$objTMD["catalogStatusID"]            = 0;
					$objTMD["inventoryStatusID"]          = 0;
					$objTMD["isActive"]                   = 1;
					$objTMD["quantityStock"]              = 0;
					$objTMD["quantiryStockInTraffic"]     = 0;
					$objTMD["quantityStockUnaswared"]     = 0;
					$objTMD["remaingStock"]               = 0;
					$objTMD["expirationDate"]             = null;
					$objTMD["inventoryWarehouseSourceID"] = null;
					$objTMD["inventoryWarehouseTargetID"] = null;
					$total 								  = $total + ($price * $quantity);
					$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					
					
				}
            }
			
			
			$objTMNew["amount"] = $total;
            $this->Transaction_Master_Model->update_app_posme($companyID, $transactionID, $transactionMasterID, $objTMNew);

			
			
			if ($db->transStatus() !== false) {
				
				
				//Mandar mensjes por whatapp al cliente
				$mensajeCliente		= "";
				$this->core_web_whatsap->sendMessageByLiveconnect($companyID, replaceSimbol($mensajeCliente), clearNumero($objCustomer[0]->phoneNumber));
				
				//Mandar mensaje por whatapp al propietario
				$mensajeColaborador	= "";
				$this->core_web_whatsap->sendMessageByLiveconnect($companyID, replaceSimbol($mensajeColaborador), clearNumero($objColaborador[0]->phoneNumber));
				
				
                $db->transCommit();
				$dataSession["error"] 				= false;
				$dataSession["transactionNumber"] 	= $objTM["transactionNumber"];				
				$dataSession["message"] 			= "Su pedido ya esta enproceso, le mantendremos informado.";
				return view("app_invoice_survery/result_body",$dataSession);//--finview-r	
                
            } else {
                $db->transRollback();
				$dataSession["error"] 				= true;		
				$dataSession["transactionNumber"] 	= "404";							
				$dataSession["message"] 			= "No fue posible procesar su orden.";
				return view("app_invoice_survery/result_body",$dataSession);//--finview-r	
            }
			
			
			
			
		}
		catch(\Exception $ex)
		{	
			
			$data["session"]   = null;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    echo $resultView;
		}		
		
	}

    

	
	function searchTransactionMaster(){
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
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			
			//Nuevo Registro
			$transactionNumber 	= /*inicio get post*/ $this->request->getPost("transactionNumber");
			
			
			if(!$transactionNumber){
					throw new \Exception(NOT_PARAMETER);			
			} 			
			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID,$transactionNumber);	
			
			if(!$objTM)
			throw new \Exception("NO SE ENCONTRO EL DOCUMENTO");	
			
			
			
			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
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