<?php
//posme:2023-02-27
namespace App\Controllers;
class app_mobile_api extends _BaseController {
	
	function setDataUpload()
	{
		try
		{ 
			
			$nickname		= /*inicio get post*/ $this->request->getPost("txtNickname");			
			$password		= /*inicio get post*/ $this->request->getPost("txtPassword");	
			$objUser		= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname,$password);
			$companyID  	= $objUser["user"]->companyID;
			$userID		 	= $objUser["user"]->userID;
			$objCompany		= $objUser["company"];
			
			$objListCustomerSource					= /*inicio get post*/ $this->request->getPost("txtObjListCustomer");
			$objListItemsSource						= /*inicio get post*/ $this->request->getPost("txtObjListItems");
			$objListTransactionMasterSource			= /*inicio get post*/ $this->request->getPost("txtObjListTransactionMaster");
			$objListTransactionMasterDetialSource 	= /*inicio get post*/ $this->request->getPost("txtObjListTransactionMasterDetail");			
			
			return $this->response->setJSON(array(
				'error'   							=> false,
				'message' 							=> SUCCESS
			));//--finjson
			
		}
		catch(\Exception $ex)
		{
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			
		}		
			
	}
    function getDataDownload()
	{
		try
		{ 
			
			$nickname		= /*inicio get post*/ $this->request->getPost("txtNickname");			
			$password		= /*inicio get post*/ $this->request->getPost("txtPassword");	
			$objUser		= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname,$password);
			$companyID  	= $objUser["user"]->companyID;
			$userID		 	= $objUser["user"]->userID;
			$objCompany		= $objUser["company"];
			
			//Obtener listado de productos
			$objWarehouse 	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$userID);
			$objWarehouseID = array_map(fn($warehouseItem) => $warehouseItem->warehouseID, $objWarehouse);
			$objListItem 	= $this->Item_Model->get_rowByCompanyIDToMobile($objWarehouseID);
			
			//Obtener lista de clients 
			$objListCustomer = $this->Customer_Model->get_rowByCompanyIDToMobile($companyID);
			
			//Obtener lisa de paramtros
			$objListParameter = $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			
			//Obtener documentos pendientes
			$objListDocumentCredit = $this->Customer_Credit_Document_Model->get_rowByBalancePendingByCompanyToMobile($companyID);				
				
			//Obtener lista de amortizaciones
			$objListAmortization = $this->Customer_Credit_Amortization_Model->get_rowShareLateByCompanyToMobile($companyID);
			
			
			return $this->response->setJSON(array(
				'error'   							=> false,
				'message' 							=> SUCCESS,		
				'ObjCompany'						=> $objCompany,
				'ListItem'  						=> $objListItem,
				'ListCustomer'  					=> $objListCustomer,
				'ListParameter'  					=> $objListParameter,
				'ListDocumentCredit' 				=> $objListDocumentCredit,
				'ListDocumentCreditAmortization'	=> $objListAmortization
			));//--finjson
			
		}
		catch(\Exception $ex)
		{
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			
		}		
			
	}
}
?>