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
            $objItemsJson 				= /*inicio get post*/  $this->request->getPost("txtData");
            $data 						= json_decode($objItemsJson, true);
            $items 						= $data['ObjItems'];
            $customers                  = $data['ObjCustomers'];
            $dataSession['user'] 		= $objUser["user"];
            $dataSession['company'] 	= $objCompany;
            $dataSession['role'] 		= $objUser["role"];
            $this->core_web_permission->getValueLicense($companyID,get_class($this)."/"."index");
            // INICIO DE CARGA DE ITEMS
            if (count($items) > 0) {
                $controller 				= new app_inventory_item();
                $controller->initController($this->request, $this->response, $this->logger);
                foreach ($items as $va)
                {
                    $objOldItem = $this->Item_Model->get_rowByCodeBarra($companyID, $va['barCode']);
                    if (!is_null($objOldItem))
                    {
                        $method = "edit_customer_mobile";
                        $va['itemID']= $objOldItem->itemID;
                    }
                    else
                    {
                        $method = "new_customer_mobile";
                    }
                    $objOldItem=json_decode(json_encode($va));
                    $controller->save($method, $objOldItem, $dataSession);
                }
            }


            //INICIO DE CARGA DE CUSTOMERS
            if (count($customers) > 0) {
                $controller = new app_cxc_customer();
                $controller->initController($this->request, $this->response, $this->logger);
                foreach ($customers as $cus)
                {
                    $obj=json_decode(json_encode($cus));
                    $companyID=$obj->companyID;
                    $branchID=$obj->branchID;
                    $entityID=$obj->entityID;
                    //si entityid es null o 0, es nuevo, sino un update
                    $objCustomer= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
                    if (is_null($objCustomer)){
                        $controller->insertElementMobile($dataSession,$obj);
                    }else{
                        $objCustomer=json_decode(json_encode($objCustomer));
                        $objCustomer->firstName = $obj->firstName;
                        $objCustomer->lastName=$obj->lastName;
                        $controller->updateElementMobile($dataSession, $objCustomer);
                    }
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