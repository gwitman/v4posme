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


			log_message("error","[SET_DATA_UPLOAD] ===== INICIO setDataUpload =====");
			//$nickname 						= "adminweb";
            //$password 						= "abeadm";
			$nickname 					= /*inicio get post*/ $this->request->getPost("txtNickname");
            $password 					= /*inicio get post*/ $this->request->getPost("txtPassword");
			log_message("error","[SET_DATA_UPLOAD] Autenticando usuario -> nickname: ".$nickname);
			
            $objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);
			$objListCustomerMap			= [];
            $companyID 					= $objUser["user"]->companyID;
            Services::session()->set("user", $objUser["user"]);
            $objCompany 				= $objUser["company"];
			log_message("error","[SET_DATA_UPLOAD] Usuario autenticado -> userID: ".$objUser["user"]->userID." | employeeID: ".$objUser["user"]->employeeID." | companyID: ".$companyID." | companyType: ".$objCompany->type);
			
            
			//Validar permiso si se limpiara el inventario
			$permited 			= false;
            $permited 			= $this->core_web_permission->urlPermited("core_inventory", "clear_item_on_upload_data", URL_SUFFIX, $objUser["menuTop"], $objUser["menuLeft"], $objUser["menuBodyReport"], $objUser["menuBodyTop"], $objUser["menuHiddenPopup"]);
			$limpiarInventory 	= "false";
			
			if ($permited) {
				$limpiarInventory 	= "true";
			}
			log_message("error","[SET_DATA_UPLOAD] Permiso limpiar inventario -> limpiarInventory: ".$limpiarInventory);
			
			
			//$objItemsJson 			= '{"ObjCustomers":[],"ObjItems":[],"ObjTransactionMaster":[{"TransactionId":19,"TypePaymentId":3,"TransactionMasterId":7,"TransactionNumber":"FAC-0011","EntityId":13,"TransactionOn":"2026-03-19T09:28:32.237701","TransactionOn2":"0001-01-01T00:00:00","NextVisit":"2026-03-19T00:00:00","Plazo":1,"FixedExpenses":0.0,"PeriodPay":190,"EntitySecondaryId":"1123","SubAmount":220.0,"Discount":22.0,"Taxi1":0.0,"Amount":220.0,"CustomerCreditLineId":359,"TransactionCausalId":21,"ExchangeRate":0.0,"CurrencyId":1,"Comment":"Jfjd","Reference1":"","Reference2":"","Reference3":"","CustomerIdentification":"000-000000-0000A","ReferenceClientName":"","MesaID":0,"MesaName":"Seleccione","StatusID":67,"Reference4":null,"CuotasPendientes":0}],"ObjTransactionMasterDetail":[{"TransactionMasterDetailId":13,"TransactionMasterId":7,"Componentid":33,"ComponentItemId":29144,"Quantity":10.0,"UnitaryCost":0.0,"UnitaryPrice":20.0,"SubAmount":200.0,"Discount":20.0,"Tax1":0.0,"Amount":200.0,"ItemBarCode":"777700001111","Reference1":"","Reference2":"","PorcentajeDescuento":0.0,"MontoDescuento":0.0,"ReferenciaProducto":"pez flaco"},{"TransactionMasterDetailId":14,"TransactionMasterId":7,"Componentid":33,"ComponentItemId":29142,"Quantity":4.0,"UnitaryCost":0.0,"UnitaryPrice":5.0,"SubAmount":20.0,"Discount":2.0,"Tax1":0.0,"Amount":20.0,"ItemBarCode":"777700001109","Reference1":"","Reference2":"","PorcentajeDescuento":0.0,"MontoDescuento":0.0,"ReferenciaProducto":"pes gordo"}]}';
            //$data 					= json_decode($objItemsJson, false);
			$objItemsJson 				= $this->request->getPost("txtData");			
            $data 						= json_decode($objItemsJson, false);


			log_message("error","[SET_DATA_UPLOAD] JSON recibido (txtData): ".$objItemsJson);
            if(!isset($data)) {
				log_message("error","[SET_DATA_UPLOAD] Sin datos a ingresar, finalizando.");
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
			log_message("error","[SET_DATA_UPLOAD] Resumen de datos -> items: ".count($items)." | customers: ".count($customers)." | transactionMasters: ".count($transactionMasters)." | transactionMasterDetails: ".count($transactionMasterDetails));
			
			// APLICAR VALIDACIONES
			// 001 validar employer del usuario
			log_message("error","[SET_DATA_UPLOAD] Validando colaborador asignado -> employeeID: ".$dataSession["user"]->employeeID);
			$employee		= $this->Employee_Model->get_rowByEntityID($companyID,$dataSession["user"]->employeeID );
			if(!$employee)
			{
				log_message("error","[SET_DATA_UPLOAD] ERROR: El usuario no tiene un colaborador asignado -> employeeID: ".$dataSession["user"]->employeeID);
				throw new \Exception("El usuario no tiene un colaborador asignado");
			}
			
			// 002 validar bodega despacho del usuario
			$objListWarehouseTipoDespacho	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$objUser["user"]->userID);
			if(!$objListWarehouseTipoDespacho)
			{
				log_message("error","[SET_DATA_UPLOAD] ERROR: El usuario no tiene bodega tipo despacho -> userID: ".$objUser["user"]->userID);
				throw new \Exception("El usuario no tiene una bodega tipo despacho configurada");
			}
			log_message("error","[SET_DATA_UPLOAD] Validaciones OK -> bodegas despacho: ".count($objListWarehouseTipoDespacho));
			
			
			
            // INICIO DE CARGA DE ITEMS
			log_message("error","[SET_DATA_UPLOAD] --- INICIO carga de ITEMS (total: ".count($items).") ---");
            if (count($items) > 0) {
				
                $controller 				= new app_inventory_item();
                $controller->initController($this->request, $this->response, $this->logger);
                foreach ($items as $va)
                {
					
                    $objOldItem = $this->Item_Model->get_rowByCodeBarra($companyID, $va->barCode);
                    if (!is_null($objOldItem))
                    {					
                        $method = "edit_customer_mobile";
                        $va->itemID= $objOldItem->itemID;
						log_message("error","[SET_DATA_UPLOAD] Item EXISTENTE -> barCode: ".$va->barCode." | itemID: ".$va->itemID." | metodo: ".$method);
                    }
                    else
                    {					
                        $method = "new_customer_mobile";
						log_message("error","[SET_DATA_UPLOAD] Item NUEVO -> barCode: ".$va->barCode." | metodo: ".$method);
                    }
					
                    $controller->save($method, $va, $dataSession);
					
                }
            }
			log_message("error","[SET_DATA_UPLOAD] --- FIN carga de ITEMS ---");

            //INICIO DE CARGA DE CUSTOMERS
			$idexCount = 0;
			log_message("error","[SET_DATA_UPLOAD] --- INICIO carga de CUSTOMERS (total: ".count($customers).") ---");
            if (count($customers) > 0) 
			{
				
                $controller = new app_cxc_customer();
                $controller->initController($this->request, $this->response, $this->logger);
                foreach ($customers as $cus)
                {
                    $companyID	= $cus->companyID;
                    $branchID	= $cus->branchID;
                    $entityID	= $cus->entityID;
					$location   = $cus->location;
					$phone		= $cus->phone;
					log_message("error","[SET_DATA_UPLOAD] Procesando customer [".$idexCount."] -> entityID: ".$entityID." | identification: ".$cus->identification." | nombre: ".$cus->firstName." ".$cus->lastName);
                    //si entityid es null o 0, es nuevo, sino un update
                    $objCustomer				= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
					$objCustomerByIdentifier 	= $this->Customer_Model->get_rowByIdentification($companyID,$cus->identification);
					$objCustomer				= !is_null($objCustomer) ? $objCustomer : false;
					$objCustomer				= $objCustomer ? $objCustomer : $objCustomerByIdentifier;
					
					
                    if (
						!$objCustomer
					)
					{
						log_message("error","[SET_DATA_UPLOAD] Customer NUEVO, insertando -> identification: ".$cus->identification);
                        $objDataSet 									= $controller->insertElementMobile($dataSession,$cus);
						$entityIDOld 									= $customers[$idexCount]->entityID;
						$customerCreditLineIDOld 						= $customers[$idexCount]->customerCreditLineID;
						log_message("error","[SET_DATA_UPLOAD] Resultado insertElementMobile: ".print_r($objDataSet,true));
						
						//Validar si se ingreso bien el customer
						if (!is_array($objDataSet)) 
						{
							log_message("error","[SET_DATA_UPLOAD] ERROR: insertElementMobile no retorno array -> respuesta: ".print_r($objDataSet,true));
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
						log_message("error","[SET_DATA_UPLOAD] Customer insertado -> entityIDOld: ".$entityIDOld." => entityIDNew: ".$customers[$idexCount]->entityID." | customerNumber: ".$customers[$idexCount]->customerNumber." | customerCreditLineID: ".$customers[$idexCount]->customerCreditLineID);
						$objCustomerMaps = (object)[
								'entityIDOld' 				=> $entityIDOld,
								'customerCreditLineIDOld' 	=> $customerCreditLineIDOld,
								'entityID' 					=> $customers[$idexCount]->entityID,
								'customerCreditLineID' 		=> $customers[$idexCount]->customerCreditLineID 
						];
						$objListCustomerMap[] 							= $objCustomerMaps;
                    }
					else
					{
                        $objCustomer			=json_decode(json_encode($objCustomer));
						$objCustomerCreditLine 	=$this->Customer_Credit_Line_Model->get_rowByEntity($objCustomer->companyID,$objCustomer->branchID,$objCustomer->entityID);
						log_message("error","[SET_DATA_UPLOAD] Customer EXISTENTE, actualizando -> entityID: ".$objCustomer->entityID." | identification: ".$cus->identification);
                        $objCustomer->firstName 	= $cus->firstName;
                        $objCustomer->lastName		= $cus->lastName;
						$objCustomer->location		= $cus->location;
						$objCustomer->phoneNumber	= $cus->phone;
                        $controller->updateElementMobile($dataSession, $objCustomer);
						
						$entityIDOld 						= $customers[$idexCount]->entityID;
						$customerCreditLineIDOld 			= $customers[$idexCount]->customerCreditLineID;
						log_message("error","[SET_DATA_UPLOAD] Customer actualizado -> entityID: ".$objCustomer->entityID." | customerCreditLineID: ".$objCustomerCreditLine[0]->customerCreditLineID);
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
			log_message("error","[SET_DATA_UPLOAD] --- FIN carga de CUSTOMERS (mapa clientes: ".count($objListCustomerMap).") ---");
			
			
			
            // SINCRONIZACION DE COMPRAS / ENTRADAS
			// Las entradas ahora vienen como transacciones (tb_transaction_master_inputunpost) con su detalle
            if(count($transactionMasters)>0)
			{
                $inventoryController  = new app_inventory_inputunpost();
                $inventoryController->initController($this->request, $this->response, $this->logger);
                $typeTransactionEntrada = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_inputunpost",0);
                $entradas = array_filter($transactionMasters, function($tm) use ($typeTransactionEntrada) { return $tm->TransactionId == $typeTransactionEntrada; });
				log_message("error","[SET_DATA_UPLOAD] --- INICIO sincronizacion COMPRAS/ENTRADAS (typeTransaction: ".$typeTransactionEntrada." | total entradas: ".count($entradas).") ---");
                foreach($entradas as $objTm)
				{
                    $transactionMasterId 	= $objTm->TransactionMasterId;
                    $detalleEntrada 		= array_filter($transactionMasterDetails, function($tmd) use ($transactionMasterId) { return $tmd->TransactionMasterId == $transactionMasterId; });
					log_message("error","[SET_DATA_UPLOAD] Procesando entrada -> transactionNumber: ".$objTm->TransactionNumber." | transactionMasterID: ".$transactionMasterId." | detalles: ".count($detalleEntrada));
                    $inventoryController->insertElementMobile($dataSession, $objTm, $detalleEntrada);
                }
            }
            log_message("error","[SET_DATA_UPLOAD] --- FIN sincronizacion COMPRAS/ENTRADAS ---");

            // SINCRONIZACION DE SALIDAS
			// Las salidas ahora vienen como transacciones (tb_transaction_master_otheroutput) con su detalle
            if(count($transactionMasters)>0){
                $inventoryOutController = new app_inventory_otheroutput();
                $inventoryOutController->initController($this->request, $this->response, $this->logger);
                $typeTransactionSalida = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_otheroutput",0);
                $salidas = array_filter($transactionMasters, function($tm) use ($typeTransactionSalida) { return $tm->TransactionId == $typeTransactionSalida; });
				log_message("error","[SET_DATA_UPLOAD] --- INICIO sincronizacion SALIDAS (typeTransaction: ".$typeTransactionSalida." | total salidas: ".count($salidas).") ---");
                foreach($salidas as $objTm)
				{
                    $transactionMasterId 	= $objTm->TransactionMasterId;
                    $detalleSalida 			= array_filter($transactionMasterDetails, function($tmd) use ($transactionMasterId) { return $tmd->TransactionMasterId == $transactionMasterId; });
					log_message("error","[SET_DATA_UPLOAD] Procesando salida -> transactionNumber: ".$objTm->TransactionNumber." | transactionMasterID: ".$transactionMasterId." | detalles: ".count($detalleSalida));
                    $inventoryOutController->insertElementMobile($dataSession, $objTm, $detalleSalida);
                }
            }
			log_message("error","[SET_DATA_UPLOAD] --- FIN sincronizacion SALIDAS ---");
            //SINCRONIZACION FACTURAS
			$idexCount = 0;
            if(count($transactionMasters)>0)
			{
                $billingController 	= new app_invoice_billing();
                $billingController->initController($this->request, $this->response, $this->logger);
                $typeTransaction 	= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_billing",0);
                $facturas 			= array_filter($transactionMasters, function($tm) use ($typeTransaction) { return $tm->TransactionId == $typeTransaction; });
				log_message("error","[SET_DATA_UPLOAD] --- INICIO sincronizacion FACTURAS (typeTransaction: ".$typeTransaction." | total facturas: ".count($facturas).") ---");
                foreach($facturas as $objTm)
				{
					
                    // Filtrar los objetos por TransactionMasterId
                    $transactionMasterId=$objTm->TransactionMasterId;
					$entityID 			=$objTm->EntityId;
					log_message("error","[SET_DATA_UPLOAD] Procesando factura -> transactionNumber: ".$objTm->TransactionNumber." | transactionMasterID: ".$transactionMasterId." | entityID(original): ".$entityID);
					
					//buscar el entityID si es un entityID Nuevo					
					$objCustomerFilt 	= array_filter($objListCustomerMap, function($e) use ($entityID) { return $e->entityIDOld == $entityID; });
					
					
					if($objCustomerFilt)
					{
						
						log_message("error","[SET_DATA_UPLOAD] Buscando remapeo de entityID en mapa clientes -> entityID(original): ".$entityID);
						
						$objCustomerFilt 				= is_array($objCustomerFilt) ? reset($objCustomerFilt) : $$objCustomerFilt; 
						$objTm->entityID 				= $objCustomerFilt->entityID;
						$objTm->customerCreditLineID 	= $objCustomerFilt->customerCreditLineID;

						$objTm->EntityId 				= $objCustomerFilt->entityID;
						$objTm->CustomerCreditLineId 	= $objCustomerFilt->customerCreditLineID;
						log_message("error","[SET_DATA_UPLOAD] EntityID remapeado desde mapa clientes -> entityIDOld: ".$entityID." => entityIDNew: ".$objTm->EntityId." | customerCreditLineID: ".$objTm->CustomerCreditLineId);
					}
					else
					{
						log_message("error","[SET_DATA_UPLOAD] EntityID no esta en el mapa, buscando en modelo -> entityID: ".$objTm->EntityId);
						//no se encontro en el mapa, buscar el customer por el entityID en el modelo
						$objCustomerModel 	= $this->Customer_Model->get_rowByEntity($companyID, $objTm->EntityId);
						if($objCustomerModel)
						{
							$objCustomerCreditLineModel 	= $this->Customer_Credit_Line_Model->get_rowByEntity($objCustomerModel->companyID, $objCustomerModel->branchID, $objCustomerModel->entityID);
							$objTm->entityID 				= $objCustomerModel->entityID;
							$objTm->customerCreditLineID 	= (is_array($objCustomerCreditLineModel) && count($objCustomerCreditLineModel) > 0) ? $objCustomerCreditLineModel[0]->customerCreditLineID : null;

							$objTm->EntityId 				= $objTm->entityID;
							$objTm->CustomerCreditLineId 	= $objTm->customerCreditLineID;
							log_message("error","[SET_DATA_UPLOAD] EntityID resuelto desde modelo -> entityID: ".$objTm->EntityId." | customerCreditLineID: ".$objTm->CustomerCreditLineId);
						}
						else
						{
							log_message("error","[SET_DATA_UPLOAD] ADVERTENCIA: No se encontro customer para entityID: ".$objTm->EntityId);
						}
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
					
					log_message("error","[SET_DATA_UPLOAD] Insertando factura -> transactionNumber: ".$objTm->TransactionNumber." | numeroOriginal: ".$transactionMasterNumberOriginal." | detalles: ".count($resultado)." | monto: ".$objTm->Amount);
                    $billingController->insertElementMobil($dataSession,$transactionMasterNumberOriginal,$objTm, $resultado);
					log_message("error","[SET_DATA_UPLOAD] Factura procesada -> transactionNumber: ".$objTm->TransactionNumber);
					$idexCount++;
                }
            }
			log_message("error","[SET_DATA_UPLOAD] --- FIN sincronizacion FACTURAS (procesadas: ".$idexCount.") ---");
			
			
            //SINCRONIZACION ABONOS
            if(count($transactionMasters)>0){
                $shareController = new app_box_share();
                $shareController->initController($this->request, $this->response, $this->logger);
                $typeTransaction = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_share",0);
                $abonos = array_filter($transactionMasters, function($tm) use ($typeTransaction) {
                    return $tm->TransactionId == $typeTransaction;
                });
				log_message("error","[SET_DATA_UPLOAD] --- INICIO sincronizacion ABONOS (typeTransaction: ".$typeTransaction." | total abonos: ".count($abonos).") ---");
                foreach($abonos as $objTm){
					log_message("error","[SET_DATA_UPLOAD] Procesando abono -> transactionNumber: ".$objTm->TransactionNumber." | entityID: ".$objTm->EntityId." | monto: ".$objTm->Amount);
                    $shareController->insertElementMobil($dataSession,$objTm);
                }
            }
			log_message("error","[SET_DATA_UPLOAD] --- FIN sincronizacion ABONOS ---");

			//SINCRONIZACION GASTOS
			if(count($transactionMasters)>0)
			{
				$expensesController = new app_cxp_expenses();
				$expensesController->initController($this->request, $this->response, $this->logger);
				$typeTransaction 	= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_accounting_expenses",0);
				$gastos 			= array_filter($transactionMasters, function($tm) use ($typeTransaction) {
					return $tm->TransactionId == $typeTransaction;
				});
				log_message("error","[SET_DATA_UPLOAD] --- INICIO sincronizacion GASTOS (typeTransaction: ".$typeTransaction." | total gastos: ".count($gastos).") ---");
				foreach($gastos as $objTm)
				{
					log_message("error","[SET_DATA_UPLOAD] Procesando gasto -> transactionNumber: ".$objTm->TransactionNumber." | entityID: ".$objTm->EntityId." | monto: ".$objTm->Amount);
					$expensesController->insertElementMobile($dataSession,$objTm);
					log_message("error","[SET_DATA_UPLOAD] Gasto procesado -> transactionNumber: ".$objTm->TransactionNumber);
				}
			}
			log_message("error","[SET_DATA_UPLOAD] --- FIN sincronizacion GASTOS ---");

			//SINCRONIZAR VISITAS O CONSULTAS MEDICAS
			if(count($transactionMasters)>0){
                $medQueryController = new app_med_query();
                $medQueryController->initController($this->request, $this->response, $this->logger);
                $typeTransaction = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_med_asistencia",0);
                $medQuery = array_filter($transactionMasters, function($tm) use ($typeTransaction) {
                    return $tm->TransactionId == $typeTransaction;
                });
				log_message("error","[SET_DATA_UPLOAD] --- INICIO sincronizacion VISITAS/CONSULTAS MEDICAS (typeTransaction: ".$typeTransaction." | total: ".count($medQuery).") ---");
                foreach($medQuery as $objTm){
					log_message("error","[SET_DATA_UPLOAD] Procesando consulta medica -> transactionNumber: ".$objTm->TransactionNumber." | entityID: ".$objTm->EntityId);
                    $medQueryController->insertElementMobil($dataSession,$objTm);
                }
            }
			log_message("error","[SET_DATA_UPLOAD] --- FIN sincronizacion VISITAS/CONSULTAS MEDICAS ---");
			
			
			
            // DEJAR EN 0  LA CANTIDAD DE PRODCUTOS EN BODEGAS
			// SEGUN CONFIGURACION
			log_message("error","[SET_DATA_UPLOAD] --- INICIO limpieza de inventario (limpiarInventory: ".$limpiarInventory.") ---");
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
						
						log_message("error","[SET_DATA_UPLOAD] Limpieza inventario -> items SALIDA a procesar: ".count($itemsProccessSalidas)." | items ENTRADA a procesar: ".count($itemsProccessEntradas));
						if($itemsProccessSalidas)
						{
							if(count($itemsProccessSalidas) > 0)
							{
								$inventoryOutController = new app_inventory_otheroutput();
								$inventoryOutController->initController($this->request, $this->response, $this->logger);
								$inventoryOutController->insertElementMobileByItems($dataSession, $itemsProccessSalidas);     
							}
						}
						if($itemsProccessEntradas)
						{
							if(count($itemsProccessEntradas) > 0)
							{
								$inventoryController  =new app_inventory_inputunpost();
								$inventoryController->initController($this->request, $this->response, $this->logger);
								$inventoryController->insertElementMobileByItems($dataSession, $itemsProccessEntradas);
								
							}
						}
					}
				}
			}
			log_message("error","[SET_DATA_UPLOAD] --- FIN limpieza de inventario ---");
			log_message("error","[SET_DATA_UPLOAD] ===== FIN setDataUpload OK =====");
			
			
            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS
            ));//--finjson

        } catch (\Exception $ex) {
			
			log_message("error","[SET_DATA_UPLOAD] ===== ERROR setDataUpload -> Linea: ".$ex->getLine()." | Mensaje: ".$ex->getMessage()." =====");
			log_message("error","[SET_DATA_UPLOAD] Traza: ".$ex->getTraceAsString());
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

			//Aplicar un ordenamiento a los productos
			if($objCompany->type == "farmaciaMils")
			{
				usort($objListItem, fn($a, $b) => $a->itemID <=> $b->itemID);
			}


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
	
	/**
     * Sube / reemplaza la imagen de un producto.
     * Entrada (multipart/form-data): txtNickname, txtPassword, txtItemID, txtImage (archivo)
     * Salida (JSON): { "error": bool, "message": string }
     *
     * La imagen se almacena usando la misma ruta de trabajo que app_inventory_item:
     * PATH_FILE_OF_APP/company_{companyID}/component_{componentID}/component_item_{itemID}/image.jpg
     */
    public function setDataUploadImageItem()
    {
        try {

            log_message("error", print_r("[SET_IMAGE] 0001 - INICIO setDataUploadImageItem", true));

            $companyID = /*inicio get post*/ $this->request->getPost('txtCompanyID');
            $companyID = empty($companyID) ? APP_COMPANY : $companyID;
            $itemID    = (int) /*inicio get post*/ $this->request->getPost('txtItemID');

            log_message("error", print_r("[SET_IMAGE] 0002 - companyID: " . $companyID . " - itemID: " . $itemID, true));

            if ($itemID <= 0) {
                throw new \Exception("itemID invalido");
            }

            log_message("error", print_r("[SET_IMAGE] 0003 - itemID valido", true));

            //Obtener el componente tb_item
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if (! $objComponent) {
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
            }

            log_message("error", print_r("[SET_IMAGE] 0004 - componentID: " . $objComponent->componentID, true));

            //Validar el archivo recibido
            $file = $this->request->getFile('txtImage');
            if ($file === null || ! $file->isValid()) {
                throw new \Exception("Archivo no valido");
            }

            log_message("error", print_r("[SET_IMAGE] 0005 - archivo recibido valido", true));

            //Validar que sea imagen
            $mime = $file->getMimeType();
            log_message("error", print_r("[SET_IMAGE] 0006 - mime: " . $mime, true));
            if (strpos($mime, 'image/') !== 0) {
                throw new \Exception("El archivo no es una imagen");
            }

            //Construir la carpeta del item (misma ruta que app_inventory_item)
            $pathFileFolder = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $itemID;
            log_message("error", print_r("[SET_IMAGE] 0007 - pathFileFolder: " . $pathFileFolder, true));
            if (! is_dir($pathFileFolder)) {
                mkdir($pathFileFolder, 0700, true);
                log_message("error", print_r("[SET_IMAGE] 0008 - carpeta creada", true));
            }

            //Se guarda siempre como default_imagen_android.jpg, sobrescribiendo la anterior
            $destino = $pathFileFolder . "/default_imagen_android.jpg";
            log_message("error", print_r("[SET_IMAGE] 0009 - destino: " . $destino, true));
            if (file_exists($destino)) {
                unlink($destino);
                log_message("error", print_r("[SET_IMAGE] 0010 - imagen anterior eliminada", true));
            }

            $file->move($pathFileFolder, "default_imagen_android.jpg", true);
            log_message("error", print_r("[SET_IMAGE] 0011 - imagen guardada correctamente", true));

            return $this->response->setJSON(array(
                'error'   => false,
                'message' => SUCCESS
            ));//--finjson

        } catch (\Exception $ex) {
            log_message("error", print_r("[SET_IMAGE] ERROR - Linea: " . $ex->getLine() . " - " . $ex->getMessage(), true));
            return $this->response->setJSON(array(
                'error'   => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson
        }
    }

    /**
     * Obtiene la imagen de un producto.
     * Entrada (get/post): txtNickname, txtPassword, txtItemID
     * Salida: binario JPEG, o JSON con error si no existe.
     *
     * Usa la misma ruta de trabajo que app_inventory_item:
     * PATH_FILE_OF_APP/company_{companyID}/component_{componentID}/component_item_{itemID}/image.jpg
     */
    public function getDataUploadImageItem()
    {
        try {
			$companyID = $this->request->getPostGet('txtCompanyID');
			$companyID = empty($companyID) ? APP_COMPANY : $companyID;
			$itemID    = (int) $this->request->getPostGet('txtItemID');

			if ($itemID <= 0) {
				throw new \Exception("itemID invalido");
			}

			$objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if (! $objComponent) {
				throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			}

			$ruta = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $itemID . "/default_imagen_android.jpg";

			if (! file_exists($ruta)) {
				throw new \Exception("Imagen no encontrada");
			}

			$contenido = file_get_contents($ruta);

			// IMPORTANTE: descartar CUALQUIER salida previa (espacios, BOM, warnings, logs)
			// para que el binario empiece exactamente en FF D8 FF.
			while (ob_get_level() > 0) {
				ob_end_clean();
			}

			return $this->response
				->setHeader('Content-Type', 'image/jpeg')
				->setHeader('Content-Length', (string) strlen($contenido))
				->setHeader('Cache-Control', 'no-store')
				->setBody($contenido);
		} catch (\Exception $ex) {
			log_message("error", print_r("[GET_IMAGE] ERROR - Linea: " . $ex->getLine() . " - " . $ex->getMessage(), true));
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine() . " " . $ex->getMessage(),
			)); //--finjson
		}
    }
	
}

?>