<?php
//posme:2023-02-27
namespace App\Controllers;
use App\Models\Item_Model;
use CodeIgniter\Session\Session;
use Config\Services;

class app_mobile_api extends _BaseController
{

	function setPositionGps()
	{
		$nickname 					= /*inicio get post*/ $this->request->getPost("txtNickname");
		$password 					= /*inicio get post*/ $this->request->getPost("txtPassword");
		$latituded					= /*inicio get post*/ $this->request->getPost("txtLatituded");
		$longituded 				= /*inicio get post*/ $this->request->getPost("txtLongituded");
		$reference1 				= /*inicio get post*/ $this->request->getPost("txtReference1");
		$companyName 				= /*inicio get post*/ $this->request->getPost("txtCompanyName");
		$objPosition				= null;
		
		try {
			
            $objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);			
			$objPosition["entityID"]	= $objUser["user"]->employeeID;
        } 
		catch (\Exception $ex) 
		{			
            $objPosition["entityID"]	= 0;
        }
				
		$objPosition["isActive"]	= 1;
		$objPosition["createdOn"]	= helper_getDateTime();
		$objPosition["latituded"]	= $latituded;
		$objPosition["longituded"]	= $longituded;
		$objPosition["reference1"]	= $reference1;
		$objPosition["userName"]	= $nickname;
		$objPosition["companyName"]	= $companyName;
		$positionID					= $this->Entity_Location_Model->insert_app_posme($objPosition);
		
		return $this->response->setJSON(array(
			'error' => false,
			'message' => SUCCESS
		));//--finjson
		
		
	}
	function getPositionGps()
	{		
		$txtCompanyName 				= /*inicio get post*/ $this->request->getPost("txtCompanyName");
		$txtUserName 					= /*inicio get post*/ $this->request->getPost("txtUserName");		
		$txtIndex						= /*inicio get post*/ $this->request->getPost("txtIndex");		
		
		
		$txtCompanyName 		= str_replace("X3A", ":", $txtCompanyName);
		$txtCompanyName 		= str_replace("X2F", "/", $txtCompanyName);
		$txtCompanyName 		= str_replace("X4Z", " ", $txtCompanyName);			
			
		$objListRegisteredLocations                 = $this->Entity_Location_Model->get_UsersLocationByCompanyAndUserLast($txtCompanyName,$txtUserName);
		
		return $this->response->setJSON(array(
			'error' 	=> false,
			'message' 	=> SUCCESS,
			'index' 	=> $txtIndex, 
			'data' 		=> $objListRegisteredLocations
		));//--finjson
	}
    function setDataUpload()
    {
        try {


			log_message("error",print_r("0001",true));
			log_message("error",print_r("datos cargados----->",true));
            $nickname 						= "adminweb";
            $password 						= "abeadm";
			//$nickname 					= /*inicio get post*/ $this->request->getPost("txtNickname");
            //$password 					= /*inicio get post*/ $this->request->getPost("txtPassword");
			log_message("error",print_r("usuario: ".$nickname,true));
			log_message("error",print_r("password: ".$password,true));
			
            $objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);
			$objListCustomerMap			= [];
            $companyID 					= $objUser["user"]->companyID;
            Services::session()->set("user", $objUser["user"]);
            $objCompany 				= $objUser["company"];
			
            
			//Validar permiso si se limpiara el inventario
			$permited 			= false;
            $permited 			= $this->core_web_permission->urlPermited("core_inventory", "clear_item_on_upload_data", URL_SUFFIX, $objUser["menuTop"], $objUser["menuLeft"], $objUser["menuBodyReport"], $objUser["menuBodyTop"], $objUser["menuHiddenPopup"]);
			$limpiarInventory 	= "false";
			
			if ($permited) {
				$limpiarInventory 	= "true";
			}
			
			
			$objItemsJson 			= '{"ObjCustomers":[],"ObjItems":[],"ObjTransactionMaster":[{"TransactionId":19,"TypePaymentId":3,"TransactionMasterId":7,"TransactionNumber":"FAC-0011","EntityId":13,"TransactionOn":"2026-03-19T09:28:32.237701","TransactionOn2":"0001-01-01T00:00:00","NextVisit":"2026-03-19T00:00:00","Plazo":1,"FixedExpenses":0.0,"PeriodPay":190,"EntitySecondaryId":"1123","SubAmount":220.0,"Discount":22.0,"Taxi1":0.0,"Amount":220.0,"CustomerCreditLineId":359,"TransactionCausalId":21,"ExchangeRate":0.0,"CurrencyId":1,"Comment":"Jfjd","Reference1":"","Reference2":"","Reference3":"","CustomerIdentification":"000-000000-0000A","ReferenceClientName":"","MesaID":0,"MesaName":"Seleccione","StatusID":67,"Reference4":null,"CuotasPendientes":0}],"ObjTransactionMasterDetail":[{"TransactionMasterDetailId":13,"TransactionMasterId":7,"Componentid":33,"ComponentItemId":29144,"Quantity":10.0,"UnitaryCost":0.0,"UnitaryPrice":20.0,"SubAmount":200.0,"Discount":20.0,"Tax1":0.0,"Amount":200.0,"ItemBarCode":"777700001111","Reference1":"","Reference2":"","PorcentajeDescuento":0.0,"MontoDescuento":0.0,"ReferenciaProducto":"pez flaco"},{"TransactionMasterDetailId":14,"TransactionMasterId":7,"Componentid":33,"ComponentItemId":29142,"Quantity":4.0,"UnitaryCost":0.0,"UnitaryPrice":5.0,"SubAmount":20.0,"Discount":2.0,"Tax1":0.0,"Amount":20.0,"ItemBarCode":"777700001109","Reference1":"","Reference2":"","PorcentajeDescuento":0.0,"MontoDescuento":0.0,"ReferenciaProducto":"pes gordo"}]}';
            $data 					= json_decode($objItemsJson, false);
			//$objItemsJson 				= $this->request->getPost("txtData");			
            //$data 						= json_decode($objItemsJson, false);


			log_message("error",print_r($objItemsJson,true));
			log_message("error",print_r("0002",true));
            if(!isset($data)) {
                return $this->response->setJSON(array(
                    'error' => false,
                    'message' => 'No hay datos a ingresar'
                ));//--finjson
            }
            $items 						= $data->ObjItems;
            $customers                  = $data->ObjCustomers;
            $transactionMasters         = $data->ObjTransactionMaster;
            $transactionMasterDetails   = $data->ObjTransactionMasterDetail;
            $dataSession['user'] 		= $objUser["user"];
            $dataSession['company'] 	= $objCompany;
            $dataSession['role'] 		= $objUser["role"];
            $this->core_web_permission->getValueLicense($companyID,get_class($this)."/"."index");
			log_message("error",print_r("0003",true));
			
			// APLICAR VALIDACIONES
			// 001 validar employer del usuario
			$employee		= $this->Employee_Model->get_rowByEntityID($companyID,$dataSession["user"]->employeeID );
			if(!$employee)
			{
				throw new \Exception("El usuario no tiene un colaborador asignado");
			}
			
			// 002 validar bodega despacho del usuario
			$objListWarehouseTipoDespacho	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$objUser["user"]->userID);
			if(!$objListWarehouseTipoDespacho)
			{
				throw new \Exception("El usuario no tiene una bodega tipo despacho configurada");
			}
			
			
			
			log_message("error",print_r("0004",true));
            // INICIO DE CARGA DE ITEMS
            if (count($items) > 0) {
				
                $controller 				= new app_inventory_item();
				log_message("error",print_r("0004.001",true));
                $controller->initController($this->request, $this->response, $this->logger);
                foreach ($items as $va)
                {
					
                    $objOldItem = $this->Item_Model->get_rowByCodeBarra($companyID, $va->barCode);
					log_message("error",print_r("0004.002",true));
                    if (!is_null($objOldItem))
                    {					
                        $method = "edit_customer_mobile";
                        $va->itemID= $objOldItem->itemID;
						log_message("error",print_r("0004.003",true));
                    }
                    else
                    {					
                        $method = "new_customer_mobile";
						log_message("error",print_r("0004.004",true));
                    }
					
                    $controller->save($method, $va, $dataSession);
					
                }
            }
			log_message("error",print_r("0005",true));

            //INICIO DE CARGA DE CUSTOMERS
			$idexCount = 0;
            if (count($customers) > 0) 
			{
				
                $controller = new app_cxc_customer();
                $controller->initController($this->request, $this->response, $this->logger);
				log_message("error",print_r("0005.001",true));
                foreach ($customers as $cus)
                {
                    $companyID	= $cus->companyID;
                    $branchID	= $cus->branchID;
                    $entityID	= $cus->entityID;
					$location   = $cus->location;
					$phone		= $cus->phone;
					log_message("error",print_r("0005.006",true));
                    //si entityid es null o 0, es nuevo, sino un update
                    $objCustomer				= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
					$objCustomerByIdentifier 	= $this->Customer_Model->get_rowByIdentification($companyID,$cus->identification);
					$objCustomer				= !is_null($objCustomer) ? $objCustomer : false;
					$objCustomer				= $objCustomer ? $objCustomer : $objCustomerByIdentifier;
					
					
                    if (
						!$objCustomer
					)
					{
						
                        $objDataSet 									= $controller->insertElementMobile($dataSession,$cus);
						$entityIDOld 									= $customers[$idexCount]->entityID;
						$customerCreditLineIDOld 						= $customers[$idexCount]->customerCreditLineID;
						log_message("error",print_r("0005.007",true));
						log_message("error",print_r($objDataSet,true));
						
						//Validar si se ingreso bien el customer
						if (!is_array($objDataSet)) 
						{
							if (preg_match('/Linea:.*?\.php/', $objDataSet, $coincidencias)) 
							{
								throw new \Exception($coincidencias[0]);								
							} 
							else 
							{
								throw new \Exception("Error al crear el cliente.");
							}
						} 

						
						$customers[$idexCount]->entityID 				= $objDataSet["entityID"];
						$customers[$idexCount]->customerNumber 			= $objDataSet["customerNumber"];
						$customers[$idexCount]->customerCreditLineID 	= $objDataSet["customerCreditLineID"];
						log_message("error",print_r("0005.008",true));
						$objCustomerMaps = (object)[
								'entityIDOld' 				=> $entityIDOld,
								'customerCreditLineIDOld' 	=> $customerCreditLineIDOld,
								'entityID' 					=> $customers[$idexCount]->entityID,
								'customerCreditLineID' 		=> $customers[$idexCount]->customerCreditLineID 
						];
						$objListCustomerMap[] 							= $objCustomerMaps;
						log_message("error",print_r("0005.009",true));
                    }
					else
					{
                        $objCustomer			=json_decode(json_encode($objCustomer));
						$objCustomerCreditLine 	=$this->Customer_Credit_Line_Model->get_rowByEntity($objCustomer->companyID,$objCustomer->branchID,$objCustomer->entityID);
						log_message("error",print_r("0005.010",true));
                        $objCustomer->firstName 	= $cus->firstName;
                        $objCustomer->lastName		= $cus->lastName;
						$objCustomer->location		= $cus->location;
						$objCustomer->phoneNumber	= $cus->phone;
                        $controller->updateElementMobile($dataSession, $objCustomer);
						log_message("error",print_r("0005.011",true));
						
						$entityIDOld 						= $customers[$idexCount]->entityID;
						$customerCreditLineIDOld 			= $customers[$idexCount]->customerCreditLineID;
						$objCustomerMaps = (object)[
								'entityIDOld' 				=> $entityIDOld,
								'customerCreditLineIDOld' 	=> $customerCreditLineIDOld,
								'entityID' 					=> $objCustomer->entityID,
								'customerCreditLineID' 		=> $objCustomerCreditLine[0]->customerCreditLineID 
						];
						$objListCustomerMap[] 							= $objCustomerMaps;
                    }
					
					$idexCount++;
				
                }
            }
			
			
			
			log_message("error",print_r("0006",true));
            // SINCRONIZACION DE COMPRAS
            if (count($items) > 0) {
                $inventoryController  =new app_inventory_inputunpost();
                $inventoryController->initController($this->request, $this->response, $this->logger);
                $inventoryController->insertElementMobile($dataSession, $items);
            }
            log_message("error",print_r("0007",true));
            //las entradas - salidas < 0
            // SINCRONIZACION DE SALIDAS
            if(count($items)>0){
                $inventoryOutController = new app_inventory_otheroutput();
                $inventoryOutController->initController($this->request, $this->response, $this->logger);
                $inventoryOutController->insertElementMobile($dataSession, $items);     
            }
			log_message("error",print_r("0008",true));
            //SINCRONIZACION FACTURAS
			$idexCount = 0;
            if(count($transactionMasters)>0)
			{
                $billingController 	= new app_invoice_billing();
                $billingController->initController($this->request, $this->response, $this->logger);
                $typeTransaction 	= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_billing",0);
                $facturas 			= array_filter($transactionMasters, function($tm) use ($typeTransaction) { return $tm->TransactionId == $typeTransaction; });
				log_message("error",print_r("0008.001",true));
                foreach($facturas as $objTm)
				{
					
                    // Filtrar los objetos por TransactionMasterId
                    $transactionMasterId=$objTm->TransactionMasterId;
					$entityID 			=$objTm->EntityId;
					log_message("error",print_r("0008.002",true));
					
					//buscar el entityID si es un entityID Nuevo					
					$objCustomerFilt 	= array_filter($objListCustomerMap, function($e) use ($entityID) { return $e->entityIDOld == $entityID; });
					
					
					log_message("error",print_r("0008.003",true));
					if($objCustomerFilt)
					{
						
						log_message("error",print_r("0008.004.0001",true));
						log_message("error",print_r($objTm,true));
						log_message("error",print_r($objCustomerFilt,true));
						log_message("error",print_r($entityID,true));
						
						$objCustomerFilt 				= is_array($objCustomerFilt) ? reset($objCustomerFilt) : $$objCustomerFilt; 
						$objTm->entityID 				= $objCustomerFilt->entityID;
						$objTm->customerCreditLineID 	= $objCustomerFilt->customerCreditLineID;
						log_message("error",print_r("0008.004.0002",true));
					}
						
					//buscar el detalle
                    $resultado = array_filter($transactionMasterDetails, function($tm) use ($transactionMasterId) { return $tm->TransactionMasterId == $transactionMasterId; });					
					
					//antes de ingrear el registro validar si ya existe y eliminarlo
					$objTmOld 							= $this->Transaction_Master_Info_Model->get_rowByTransactionNumberAndCreatedBy($dataSession["user"]->companyID,$objTm->TransactionNumber,$dataSession["user"]->userID);
					$transactionMasterNumberOriginal 	= "";
					if($objTmOld)
					{
						$transactionMasterNumberOriginal 	= $objTmOld->transactionNumber;
						$this->Transaction_Master_Model->delete_app_posme($objTmOld->companyID,$objTmOld->transactionID,$objTmOld->transactionMasterID);						
					}
					
                    $billingController->insertElementMobil($dataSession,$transactionMasterNumberOriginal,$objTm, $resultado);
					log_message("error",print_r("0008.005",true));
					$idexCount++;
                }
            }
			log_message("error",print_r("0009",true));
			
			
            //SINCRONIZACION ABONOS
            if(count($transactionMasters)>0){
                $shareController = new app_box_share();
                $shareController->initController($this->request, $this->response, $this->logger);
                $typeTransaction = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_share",0);
                $abonos = array_filter($transactionMasters, function($tm) use ($typeTransaction) {
                    return $tm->TransactionId == $typeTransaction;
                });
                foreach($abonos as $objTm){
                    $shareController->insertElementMobil($dataSession,$objTm);
                }
            }
			log_message("error",print_r("0010",true));

			//SINCRONIZAR VISITAS O CONSULTAS MEDICAS
			if(count($transactionMasters)>0){
                $medQueryController = new app_med_query();
                $medQueryController->initController($this->request, $this->response, $this->logger);
                $typeTransaction = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_med_asistencia",0);
                $medQuery = array_filter($transactionMasters, function($tm) use ($typeTransaction) {
                    return $tm->TransactionId == $typeTransaction;
                });
                foreach($medQuery as $objTm){
                    $medQueryController->insertElementMobil($dataSession,$objTm);
                }
            }
			
			log_message("error",print_r("0011",true));
			
			
			
            // DEJAR EN 0  LA CANTIDAD DE PRODCUTOS EN BODEGAS
			// SEGUN CONFIGURACION
			if($limpiarInventory == "true")
			{
				//Obtener los productos con existencia 
				$objWarehouse 	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID, $dataSession['user']->userID);			
				$objWarehouseID = array_map(fn($warehouseItem) => $warehouseItem->warehouseID, $objWarehouse);
				$objListItem 	= $this->Item_Model->get_rowByCompanyIDToMobile($objWarehouseID);
			
			
				if($objListItem)
				{
					if(count($objListItem) > 0)
					{
						$itemsProccessSalidas 	= array();
						$itemsProccessEntradas 	= array();
						foreach($objListItem as $ielement)
						{
							if($ielement->quantity > 0)
							{
								$objeto 		 			 = new \stdClass();
								$objeto->cantidadEntradas 	 = 0;
								$objeto->cantidadSalidas 	 = $ielement->quantity;
								$objeto->barCode 	 		 = $ielement->barCode;
								$objeto->itemID 	 		 = $ielement->itemID;
								$itemsProccessSalidas[] = $objeto;
							}
							if($ielement->quantity < 0)
							{
								$objeto 		 			 = new \stdClass();
								$objeto->cantidadEntradas 	 = ($ielement->quantity) * -1;
								$objeto->cantidadSalidas 	 = 0;
								$objeto->barCode 	 		 = $ielement->barCode;
								$objeto->itemID 	 		 = $ielement->itemID;
								$objeto->precioPublico		 = $ielement->PrecioPublico;
								$itemsProccessEntradas[] = $objeto;
							}
						}
						
						log_message("error",print_r($itemsProccessSalidas,true));
						if($itemsProccessSalidas)
						{
							if(count($itemsProccessSalidas) > 0)
							{
								$inventoryOutController = new app_inventory_otheroutput();
								$inventoryOutController->initController($this->request, $this->response, $this->logger);
								$inventoryOutController->insertElementMobile($dataSession, $itemsProccessSalidas);     
							}
						}
						log_message("error",print_r($itemsProccessEntradas,true));
						if($itemsProccessEntradas)
						{
							if(count($itemsProccessEntradas) > 0)
							{
								$inventoryController  =new app_inventory_inputunpost();
								$inventoryController->initController($this->request, $this->response, $this->logger);
								$inventoryController->insertElementMobile($dataSession, $itemsProccessEntradas);
								
							}
						}
					}
				}
			}
			log_message("error",print_r("0012",true));
			
			
            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS
            ));//--finjson

        } catch (\Exception $ex) {
			
			log_message("error",print_r($ex,true));
            return $this->response->setJSON(array(
                'error' => true,
                'message' => 'Linea: ' . $ex->getLine() . " - Error:" . $ex->getMessage()
            ));//--finjson

        }

    }

    function getDataDownload()
    {
        try {

            $nickname 	= /*inicio get post*/ $this->request->getPostGet("txtNickname");
            $password 	= /*inicio get post*/ $this->request->getPostGet("txtPassword");
            $objUser 	= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);
            $companyID 	= $objUser["user"]->companyID;
            $userID 	= $objUser["user"]->userID;
			$flavorID	= $objUser["company"]->flavorID;
            $objCompany = $objUser["company"];

			//Obtener listado de menu
			$objListMenuElement  = $this->Menu_Element_Model->get_rowByUserID(
				$companyID,
				$userID,
				$objUser["role"]->typeApp
			);
			
			
            //Obtener listado de productos
            $objWarehouse 	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID, $userID);			
            $objWarehouseID = array_map(fn($warehouseItem) => $warehouseItem->warehouseID, $objWarehouse);
            $objListItem 	= $this->Item_Model->get_rowByCompanyIDToMobile($objWarehouseID);

            //Obtener lista de clients
			$objListCustomer = $this->Customer_Model->get_rowByCompanyIDToMobile($companyID, $userID );
			
			
            //Obtener lisa de paramtros
            $objListParameter = $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			
			//Obtener lista de catalogos
			$ListCatalogItem  = $this->Catalog_Item_Model->get_rowByFlavorID($flavorID);

            
			if($objCompany->type == "tu_futuro")
			{
				//Obtener documentos pendientes	no importa la asignacion
				//No importa a quien esten asignados , 
				//Pero si deben estar asignados
				$objListDocumentCredit 	= $this->Customer_Credit_Document_Model->get_rowByBalancePendingByCompanyToMobileTuFuturo($companyID, $userID );
			}
			else if($objCompany->type == "posme")
			{
				//Obtener documentos pendientes	no importa la asignacion
				//Ni tampoco es necesario que esten asignados
				$objListDocumentCredit 	= $this->Customer_Credit_Document_Model->get_rowByBalancePendingByCompanyToMobilePosMe($companyID, $userID );
			}
			else
			{
				//Obtener solo los asignados
				$objListDocumentCredit 	= $this->Customer_Credit_Document_Model->get_rowByBalancePendingByCompanyToMobile($companyID, $userID );
			}
			
            //Obtener lista de amortizaciones
			if( $objCompany->type == "tu_futuro" )
			{
				//Obtiene todos la tabla de amortizacion
				//De cuntas con remanente
				//Filtrando los datos solo asociados al usuario
				$objListAmortization 	= $this->Customer_Credit_Amortization_Model->get_rowShareLateByCompanyToMobileTuFuturo($companyID, $userID );
			}
			else if($objCompany->type == "posme")
			{
				//Obtiene todos la tabla de amortizacion
				//De cuntas con remanente
				//Filtrando los datos solo asociados al usuario
				$objListAmortization 	= $this->Customer_Credit_Amortization_Model->get_rowShareLateByCompanyToMobilePosMe($companyID, $userID );
			}
			else
			{				
				//Obtiene todos la tabla de amortizacion
				//De cuntas con remanente
				//Filtrando los datos solo asociados al usuario
				$objListAmortization 	= $this->Customer_Credit_Amortization_Model->get_rowShareLateByCompanyToMobile($companyID, $userID );
			}
			
			//Obtener lista de transacciones arribas 
			$objListServerTransactionMaster = $this->Transaction_Master_Model->get_rowByCreatedBy_AndCurrentDate($companyID, $userID);
			
			
			/*Obtener lista de facturas registradas del usuario*/
			$objListTransactionMasterRegister = $this->Transaction_Master_Detail_Model->get_rowByUserToMobile($companyID, $userID);
 
            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS,
                'ObjCompany' => $objCompany,
				'ListMenuElement' => $objListMenuElement,
                'ListItem' => $objListItem,				
                'ListCustomer' => $objListCustomer,
                'ListParameter' => $objListParameter,
				'ListCatalogItem' => $ListCatalogItem,
                'ListDocumentCredit' => $objListDocumentCredit,
                'ListDocumentCreditAmortization' => $objListAmortization,
				'ListServerTransactionMaster' => $objListServerTransactionMaster ,
				'ListTransactionMasterRegister' => $objListTransactionMasterRegister
            ));//--finjson

        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson

        }

    }
	
	function getUserByCompany()
	{
		try{ 
			
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$companyName 			= /*inicio get post*/ $this->request->getPost("companyName");	
			if( !$companyID )
			{
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			
			//Lista de usuarios
			if($companyName != "0")
			$catalogItems = $this->Entity_Location_Model->get_UserByCompanyLast($companyName);
		
			if($companyName == "0")
			$catalogItems = $this->Entity_Location_Model->get_UserAll();
			
			
			return $this->response->setJSON(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItems
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