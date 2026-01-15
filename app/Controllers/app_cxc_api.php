<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxc_api extends _BaseController {
		
	function getCustomerBalance(){
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
			
			//Redireccionar datos
			
			$customerID		= /*inicio get post*/ $this->request->getPost("customerID");			
			$currencyID		= /*inicio get post*/ $this->request->getPost("currencyID");
			$data 			= $this->Customer_Credit_Document_Model->get_rowByEntityApplied($companyID,$customerID,$currencyID);
			
			return $this->response->setJSON(array(
				'error'   	=> false,
				'message' 	=> SUCCESS,			
				'array'	  	=> $data
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	function getSimulateAmortization(){
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
			
			//Redireccionar datos
			$plantID					= /*inicio get post*/ $this->request->getPost("plantID");			
			$frecuencyID				= /*inicio get post*/ $this->request->getPost("frecuencyID");			
			$numberPay					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("numberPay"));
			$interestYear				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("interestYear"));	
			$amount						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("amount"));
			$dayExcluded				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("dayExcluded"));
			
			
			$branchID 					= $dataSession["user"]->branchID;
			$roleID 					= $dataSession["role"]->roleID;	
			$companyID					= $dataSession["user"]->companyID;
			$creditMultiplicador		= $this->core_web_parameter->getParameter("CREDIT_INTERES_MULTIPLO",$companyID)->value;
			$objCatalogItemFrecuencia 	= $this->Catalog_Item_Model->get_rowByCatalogItemID($frecuencyID);//obtener el catalogo de la frecuencia de pago;			
			$objCatalogItemDayExclude 	= $this->Catalog_Item_Model->get_rowByCatalogItemID($dayExcluded);//obtener el catalogo de la frecuencia de pago;			
			
			$interestYear				= $interestYear * $objCatalogItemFrecuencia->ratio;
			$objCatalogItem_DiasNoCobrables 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES",$companyID);
			$objCatalogItem_DiasFeriados365 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_365",$companyID);
			$objCatalogItem_DiasFeriados366 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_366",$companyID);
						
			//Obtener catalogo
			$periodPay 				= $this->Catalog_Item_Model->get_rowByCatalogItemID($frecuencyID);
			
			
			//Crear tabla de amortizacion
			$this->financial_amort->amort(
				$amount, 									/*monto*/
				$interestYear,								/*interes anual*/
				$numberPay,									/*numero de pagos*/	
				$periodPay->sequence,						/*frecuencia de pago en dia*/
				date("Y-m-d"),								/*fecha del credito*/	
				$plantID 									/*tipo de amortizacion*/,
				$objCatalogItem_DiasNoCobrables,
				$objCatalogItem_DiasFeriados365,
				$objCatalogItem_DiasFeriados366,
				$objCatalogItemDayExclude,
				$dataSession["company"]->flavorID
			);			
			
			$tableAmortization = $this->financial_amort->getTable();
			
			
			return $this->response->setJSON(array(
				'error'   	=> false,
				'message' 	=> SUCCESS,			
				'array'	  	=> $tableAmortization
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	function getConversationByCustomer()
	{
		// Obtener JSON enviado por fetch
		$data 		= $this->request->getJSON(true);
		// Extraer entityID
		$entityID 	= $data['entityID'] ?? null;

		// Validación básica
		if (!$entityID) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'entityID no recibido',
				'data' 	  => []
			]);
		}
		
		$objListNotification = $this->Notification_Model->get_rowByEntityIDCustomer($entityID);
		return $this->response->setJSON([
			'success' => true,
			'message' => 'entityID recibido',
			'data'	  => $objListNotification
		]);
	}
	
	function setConversationByCustomer()
	{
		// Obtener JSON enviado por fetch
		$data 		= $this->request->getJSON(true);
		// Extraer entityID
		$entityID 	= $data['entityID'] ?? null;
		$message	= $data['message'] ?? null;

		// Validación básica
		if (!$entityID) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'entityID no recibido',
				'data' 	  => []
			]);
		}
		
		//AUTENTICADO
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();
	
	
		//Obtener el colaborador
		$entityIDEmployer 		= $dataSession["user"]->employeeID;		
		$companyID				= $dataSession["user"]->companyID;		
		$branchID				= $dataSession["user"]->branchID;
		if(!$entityIDEmployer)
		{
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Usuario no tiene un colaborador asignado',
				'data' 	  => []
			]);
		}
		
		//Obtener el tag
		$objTag		 = $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		
		//Obtener al cliente
		$objCustomer = $this->Customer_Model->get_rowByEntity($companyID,$entityID);
		
		//Obtener al colaborador
		$objEmployer 		= $this->Employee_Model->get_rowByEntityID($companyID,$entityIDEmployer);
		if(!$objEmployer)
		{
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Colaborador no encontrado',
				'data' 	  => []
			]);
		}
		
		//Obtener el telefono del colaborador
		$objEmployerPhone = $this->Entity_Phone_Model->get_rowByPrimaryEntity($companyID,$branchID,$entityIDEmployer);
		if(!$objEmployerPhone)
		{
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Colaborador no tiene telefono primario',
				'data' 	  => []
			]);
		}
		
		$objNotification 							= array();		
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= $objEmployer->firstName;
		$objNotification["to"] 						= $objCustomer->firstName;
		$objNotification["subject"] 				= "no use";
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= "no use";
		$objNotification["title"] 					= "no use";
		$objNotification["tagID"] 					= $objTag->tagID;
		$objNotification["createdOn"] 				= helper_getDateTime();
		$objNotification["isActive"] 				= 1;
		$objNotification["phoneFrom"] 				= $objEmployerPhone[0]->number;
		$objNotification["phoneTo"] 				= $objCustomer->phoneNumber;
		$objNotification["programDate"] 			= helper_getDate();
		$objNotification["programHour"] 			= '00:00';
		$objNotification["sendOn"] 					= NULL;
		$objNotification["sendEmailOn"] 			= NULL;
		$objNotification["sendWhatsappOn"] 			= NULL;
		$objNotification["addedCalendarGoogle"] 	= 0;
		$objNotification["quantityOcupation"] 		= 0;
		$objNotification["quantityDisponible"] 		= 0;
		$objNotification["googleCalendarEventID"] 	= NULL;
		$objNotification["isRead"] 					= 1;
		$objNotification["entityIDSource"] 			= $entityIDEmployer;
		$objNotification["entityIDTarget"] 			= $entityID;
		$notificationID 							= $this->Notification_Model->insert_app_posme($objNotification);
	
		
		return $this->response->setJSON([
			'success' => true,
			'message' => 'entityID recibido'
		]);
		
	}
	function setCustomer()
	{
		// Obtener JSON enviado por fetch
		$data 		= $this->request->getJSON(true);
		// Extraer entityID
		$entityID 	= $data['entityID'] ?? null;
		$message	= $data['message'] ?? null;

		// Validación básica
		if (!$entityID) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'entityID no recibido',
				'data' 	  => []
			]);
		}
		
		//AUTENTICADO
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();
		
		
		//Actualiar Cliente
		$companyID					= $dataSession["user"]->companyID;		
		$branchID					= $dataSession["user"]->branchID;
		$objCustomer 				= array();
		$objCustomer["phoneNumber"] = $data['txtCustomerPhone'] ?? '';		
		$result 					= $this->Customer_Model->update_app_posme($companyID,$branchID,$entityID,$objCustomer);
		
		//Actualizar Natural
		$objNatural 				= array();
		$objNatural["firstName"] 	= $data['txtCustomerName'] ?? '';
		$result 					= $this->Natural_Model->update_app_posme($companyID,$branchID,$entityID,$objNatural);
		
		return $this->response->setJSON([
			'success' => true,
			'message' => 'entityID recibido'
		]);
	}
	
}
?>