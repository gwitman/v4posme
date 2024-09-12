<?php
//posme:2023-02-27
namespace App\Controllers;
use App\Models\Item_Model;
use CodeIgniter\Session\Session;
use Config\Services;

class app_mobile_api extends _BaseController
{

    function setDataUpload()
    {
        try {

            $nickname 					= /*inicio get post*/ $this->request->getPost("txtNickname");
            $password 					= /*inicio get post*/ $this->request->getPost("txtPassword");
            $objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);
            $companyID 					= $objUser["user"]->companyID;
            Services::session()->set("user", $objUser["user"]);
            $objCompany 				= $objUser["company"];
            $objItemsJson 				= $this->request->getPost("txtData");
            $data 						= json_decode($objItemsJson, false);
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
			
			// APLICAR VALIDACIONES
			// 001 validar employer del usuario
			$employee		= $this->Employee_Model->get_rowByEntityID($companyID,$dataSession["user"]->employeeID );
			if(!$employee)
			{
				throw new \Exception("El usuario no tiene un colaborador asignado");
			}
			
			// 002 validar que no ayan dos item con el mismo barCode
			$itemBarCodeDuplicate = $this->Item_Model->getItemBarCodeDuplicate($companyID);
			if($itemBarCodeDuplicate)
			{
				throw new \Exception("El item -".$itemBarCodeDuplicate->barCode."- tiene codigo de barra duplicado");
			}
			
			// 003 validar que no ayan dos customer del mismo identificacion
			$customerIdentificationDuplicate = $this->Customer_Model->getIdentificationDuplicate($companyID);
			if($customerIdentificationDuplicate)
			{
				throw new \Exception("El cliente -".$customerIdentificationDuplicate->identification."- tiene codigo de barra duplicado");
			}

            // INICIO DE CARGA DE ITEMS
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
                    }
                    else
                    {
                        $method = "new_customer_mobile";
                    }
                    $controller->save($method, $va, $dataSession);
                }
            }


            //INICIO DE CARGA DE CUSTOMERS
            if (count($customers) > 0) {
                $controller = new app_cxc_customer();
                $controller->initController($this->request, $this->response, $this->logger);
                foreach ($customers as $cus)
                {
                    $companyID=$cus->companyID;
                    $branchID=$cus->branchID;
                    $entityID=$cus->entityID;
                    //si entityid es null o 0, es nuevo, sino un update
                    $objCustomer= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
                    if (is_null($objCustomer)){
                        $controller->insertElementMobile($dataSession,$cus);
                    }else{
                        $objCustomer=json_decode(json_encode($objCustomer));
                        $objCustomer->firstName = $cus->firstName;
                        $objCustomer->lastName=$cus->lastName;
                        $controller->updateElementMobile($dataSession, $objCustomer);
                    }
                }
            }

            // SINCRONIZACION DE COMPRAS
            if (count($items) > 0) {
                $inventoryController  =new app_inventory_inputunpost();
                $inventoryController->initController($this->request, $this->response, $this->logger);
                $inventoryController->insertElementMobile($dataSession, $items);
            }
            
            //las entradas - salidas < 0
            // SINCRONIZACION DE SALIDAS
            if(count($items)>0){
                $inventoryOutController = new app_inventory_otheroutput();
                $inventoryOutController->initController($this->request, $this->response, $this->logger);
                $inventoryOutController->insertElementMobile($dataSession, $items);     
            }

            //SINCRONIZACION FACTURAS
            if(count($transactionMasters)>0){
                $billingController = new app_invoice_billing();
                $billingController->initController($this->request, $this->response, $this->logger);
                $typeTransaction = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_billing",0);
                $facturas = array_filter($transactionMasters, function($tm) use ($typeTransaction) {
                    return $tm->TransactionId == $typeTransaction;
                });
                foreach($facturas as $objTm){
                    // Filtrar los objetos por TransactionMasterId
                    $transactionMasterId=$objTm->TransactionMasterId;
                    $resultado = array_filter($transactionMasterDetails, function($tm) use ($transactionMasterId) {
                        return $tm->TransactionMasterId == $transactionMasterId;
                    });
                    $billingController->insertElementMobil($dataSession,$objTm, $resultado);
                }
            }

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

            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS
            ));//--finjson

        } catch (\Exception $ex) {
            return $this->response->setJSON(array(
                'error' => true,
                'message' => 'Linea: ' . $ex->getLine() . " - Error:" . $ex->getMessage()
            ));//--finjson

        }

    }

    function getDataDownload()
    {
        try {

            $nickname 	= /*inicio get post*/ $this->request->getPost("txtNickname");
            $password 	= /*inicio get post*/ $this->request->getPost("txtPassword");
            $objUser 	= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);
            $companyID 	= $objUser["user"]->companyID;
            $userID 	= $objUser["user"]->userID;
            $objCompany = $objUser["company"];

            //Obtener listado de productos
            $objWarehouse 	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID, $userID);
            $objWarehouseID = array_map(fn($warehouseItem) => $warehouseItem->warehouseID, $objWarehouse);
            $objListItem 	= $this->Item_Model->get_rowByCompanyIDToMobile($objWarehouseID);

            //Obtener lista de clients
            $objListCustomer = $this->Customer_Model->get_rowByCompanyIDToMobile($companyID);

            //Obtener lisa de paramtros
            $objListParameter = $this->Company_Parameter_Model->get_rowByCompanyID($companyID);

            //Obtener documentos pendientes
            $objListDocumentCredit 	= $this->Customer_Credit_Document_Model->get_rowByBalancePendingByCompanyToMobile($companyID);

            //Obtener lista de amortizaciones
            $objListAmortization 	= $this->Customer_Credit_Amortization_Model->get_rowShareLateByCompanyToMobile($companyID);


            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS,
                'ObjCompany' => $objCompany,
                'ListItem' => $objListItem,
                'ListCustomer' => $objListCustomer,
                'ListParameter' => $objListParameter,
                'ListDocumentCredit' => $objListDocumentCredit,
                'ListDocumentCreditAmortization' => $objListAmortization
            ));//--finjson

        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson

        }

    }
}

?>