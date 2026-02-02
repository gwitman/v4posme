<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxc_api extends _BaseController {
		
	function getCustomerBalance()
	{
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
	function getSimulateAmortization()
	{
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
	
	function getConversationConversation_ByEmployer()
	{
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
			
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_notification_conversation");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_notification_conversation' NO EXISTE...");
			
			//Obtener  los datos del cliente
			$data 		= $this->request->getJSON(true);
			// Extraer entityID
			$tipoLista 	= $data['entityID'] ?? null;

			// Validación básica
			if (!$tipoLista) {
				return $this->response->setJSON([
					'success' => false,
					'message' => 'tipoLista no recibido',
					'data' 	  => []
				]);
			}
			
			
			//Vista por defecto 
			if($tipoLista == 'activas')
			{
				$targetComponentID			= $this->session->get('company')->flavorID;			
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;	
				$parameter["{userID}"]		= $dataSession["user"]->userID;
				$parameter["{tipoLista}"]	= $tipoLista;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				
				
				if(!$dataViewData){
					$targetComponentID			= 0;	
					$parameter["{userID}"]		= $dataSession["user"]->userID;
					$parameter["{companyID}"]	= $this->session->get('user')->companyID;
					$parameter["{tipoLista}"]	= $tipoLista;
					$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);				
				}
			}
			if($tipoLista != 'activas')
			{
				$targetComponentID			= $this->session->get('company')->flavorID;			
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;	
				$parameter["{userID}"]		= $dataSession["user"]->userID;
				$parameter["{tipoLista}"]	= $tipoLista;
				$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$objComponent->componentID,$tipoLista,CALLERID_LIST,$resultPermission,$parameter);
				
				
				if(!$dataViewData){
					$targetComponentID			= 0;	
					$parameter["{userID}"]		= $dataSession["user"]->userID;
					$parameter["{companyID}"]	= $this->session->get('user')->companyID;
					$parameter["{tipoLista}"]	= $tipoLista;
					$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$objComponent->componentID,$tipoLista,CALLERID_LIST,$resultPermission,$parameter);
				}
			}
			
		
			
			
			$data	= $dataViewData["view_data"];			
			return $this->response->setJSON([
				'success' => true,
				'message' => 'entityID recibido',
				'data'	  => $data
			]);
						
			
		}
		catch(\Exception $ex){
			return $this->response->setJSON([
				'success' => false,
				'message' => $ex->getMessage(),
				'line'    => $ex->getLine(),
				'file'    => $ex->getFile(),
				'code'    => $ex->getCode(),
				'data'    => []
			]);
		}
	}
	function getConversationConversation_Report()
	{
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
			
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_notification_conversation");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_notification_conversation' NO EXISTE...");
			
			//Obtener  los datos del cliente
			$data 		= $this->request->getJSON(true);
			// Extraer entityID
			$txtStartOn 			= $data['txtStartOn'] ?? null;
			$txtFinishOn 			= $data['txtFinishOn'] ?? null;
			$txtEntityIDEmployer 	= $data['txtEntityIDEmployer'] ?? null;
			$txtInboxID 			= $data['txtInboxID'] ?? null;
			$txtWorkflowStatusID	= $data['txtWorkflowStatusID'] ?? null;

			//Obtener datos
			$data = $this->
			Customer_Conversation_Model->
			getBy_StartOn_EndOn_EmployerID_InboxID_StatusID(
				$txtStartOn,$txtFinishOn,$txtEntityIDEmployer,0,$txtWorkflowStatusID
			);
			
			$totalRegistros = 0;
			$sinContestar   = 0;
			$conContestar	= 0;
			$noLeidas 		= 0;
			if($data)
			{
				$totalRegistros = count($data);
				$noLeidas 		= count(array_filter($data, function ($m) {
					return $m->messgeConterNotRead > 0;
				}));
				$sinContestar 	= count(array_filter($data, function ($m) {
					return $m->dayNotContacted > 0;
				}));
				$conContestar 	= count(array_filter($data, function ($m) {
					return $m->dayNotContacted = 0;
				}));

				
			}
			return $this->response->setJSON([
				'success' 			=> true,
				'message' 			=> 'txtEntityIDEmployer recibido',
				'data'	  			=> $data,
				'count'				=> $totalRegistros,
				'noLeidas'			=> $noLeidas,
				'sinContestar'		=> $sinContestar,
				'conContestar'		=> $conContestar
			]);
						
			
		}
		catch(\Exception $ex){
			return $this->response->setJSON([
				'success' => false,
				'message' => $ex->getMessage(),
				'line'    => $ex->getLine(),
				'file'    => $ex->getFile(),
				'code'    => $ex->getCode(),
				'data'    => []
			]);
		}
	}
	function getConversationCustomer_ByCustomer()
	{
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
			
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_notification_conversation");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_notification_conversation' NO EXISTE...");
			
			
			//Obtener  los datos del cliente
			//Obtener  JSON enviado por fetch
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
			
			//Obtener los datos del cliente		
			$companyID 					= $dataSession["user"]->companyID;			
			$branchID					= $dataSession["user"]->branchID;			
			$objCustomer 				= $this->Customer_Model->get_rowByEntity($companyID,$entityID);
			$objNatural 				= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$objListEmployerAll			= $this->Employee_Model->get_rowByCompanyID($companyID);
			$objListEmployerAsigned 	= $this->Company_Component_Relation_Model->get_ConversationEmployerBy_entityIDCustomer($entityID);			
			$objListEmployer 			= [];

			foreach ($objListEmployerAll as $employer) {
				// 👉 condición de ejemplo
				$resultValidation = $this->Employee_Model->get_validatePermissionBy_EmployerID_PuedeAtenderWhatsappInCRM($employer->entityID);
				if($resultValidation)
				{
					$objListEmployer[] = $employer;
				}
			}

			
			return $this->response->setJSON([
				'success' 				 => true,
				'message' 				 => 'entityID no recibido',
				'objCustomer' 	  		 => $objCustomer,
				'objNatural'			 => $objNatural,
				'objListEmployer'		 => $objListEmployer,
				'objListEmployerAsigned' => $objListEmployerAsigned
			]);
			
		}
		catch(\Exception $ex){
			return $this->response->setJSON([
				'success' => false,
				'message' => $ex->getMessage(),
				'line'    => $ex->getLine(),
				'file'    => $ex->getFile(),
				'code'    => $ex->getCode(),
				'data'    => []
			]);
		}
	}
	function getConversationNotification_ByCustomer()
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
		
		$objListNotification 					= $this->Notification_Model->get_rowByEntityIDCustomer($entityID);			
		$objCustomerConversation				= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($entityID);
		$objConversation 						= array();
		$objConversation["messgeConterNotRead"] = 0 ;
		$this->Customer_Conversation_Model->update_app_posme_ByCustomer($entityID,$objConversation);
		
		$objNotification["isRead"] 	= 1;
		$this->Notification_Model->update_app_posme_notification_byCustomerID($entityID,$objNotification);
		
		return $this->response->setJSON([
			'success' => true,
			'message' => 'entityID recibido',
			'data'	  => $objListNotification
		]);
	}
	function setConversationNotification_ByCustomer()
	{	
		
		// Obtener JSON enviado por fetch
		log_message("error","setConversationNotification_ByCustomer");
		$file 		= $this->request->getFile('image');
		$data 		= array();
		if(!$file)
		{
			$data 		= $this->request->getJSON(true);
		}
		else
		{
			$data["entityID"] 				= $this->request->getPost('entityID') ?? '';
			$data["txtTab3CustomerMessage"] = $this->request->getPost('txtTab3CustomerMessage') ?? '';
			$data["txtTab3CustomerPhone"] 	= $this->request->getPost('txtTab3CustomerPhone') ?? '';
		}
		
		// Extraer entityID
		$entityID 	= $data['entityID'] ?? null;
		$message	= $data['txtTab3CustomerMessage'] ?? null;
		$phone		= $data['txtTab3CustomerPhone'] ?? null;

		// Validación básica
		if ($entityID === null || $entityID === '' || !is_numeric($entityID)) {
			
			$dataResult = [			
				'success' => false,
				'message' => 'entityID no recibido',
				'data' 	  => []
			];
			
			log_message("error",print_r($dataResult,true));			
			return $this->response->setJSON($dataResult);
			
		}
		
		log_message("error","setConversationNotification_ByCustomer >> procesar auteenticacion");
		//AUTENTICADO		
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();
	
	
		//Obtener el Componente de Transacciones Other Input to Inventory
		$objComponentCustomerConversation	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_conversation");
		if(!$objComponentCustomerConversation)
		throw new \Exception("EL COMPONENTE 'tb_customer_conversation' NO EXISTE...");
		
	
		//Obtener el colaborador
		log_message("error","setConversationNotification_ByCustomer >> obtener colaboradores");
		$entityIDEmployer 		= $dataSession["user"]->employeeID;		
		$companyID				= $dataSession["user"]->companyID;		
		$branchID				= $dataSession["user"]->branchID;
		$typeCompany			= $dataSession["company"]->type;
		if(!$entityIDEmployer)
		{
			$dataResult = [			
				'success' 	=> false,
				'message' 	=> 'Usuario no tiene un colaborador asignado',
				'data' 	  	=> [],
				'entityID' 	=> $entityID
			];
			
			log_message("error",print_r($dataResult,true));			
			return $this->response->setJSON($dataResult);
			
		}
		
		//Obtener el cliente	
		log_message("error","setConversationNotification_ByCustomer >> obtener clientes");		
		$phone					= getNumberPhone($phone);
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($phone);
		
		if(!$objCustomer)
		{
			log_message("error","setConversationNotification_ByCustomer >> cliente no encontrado");		
			//crear el cliente
			$entityIDCustomer 	= $this->core_web_conversation->createCustomer($dataSession,$phone,$phone,$this->request);
			
			//crear una conversacion al cliente
			$conversationIsNew	= true;
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$entityIDCustomer);		
			
			//asociar el colaborador a la converascion
			$objListEntityIDEmployer 	= array();
			$objListEntityIDEmployer[] 	= $entityIDEmployer; 
			$this->core_web_conversation->createEmployerInConversation($dataSession,$conversationID,$objListEntityIDEmployer);
			
		}
		else
		{
			log_message("error","setConversationNotification_ByCustomer >> cliente si encontrado");		
		}
	
	
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($phone);
		$entityID				= $objCustomer[0]->entityID;
		
		
		//Obtener el tag
		$objTag		 = $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		log_message("error","setConversationNotification_ByCustomer >> obtener el colaborador");		
		//Obtener al colaborador
		$objEmployer 		= $this->Employee_Model->get_rowByEntityID($companyID,$entityIDEmployer);
		if(!$objEmployer)
		{
			$dataResult = [			
				'success' 	=> false,
				'message' 	=> 'Colaborador no encontrado',
				'data' 	  	=> [],
				'entityID' 	=> $entityID
			];
			
			log_message("error",print_r($dataResult,true));			
			return $this->response->setJSON($dataResult);
		}
		
		//Obtener el telefono del colaborador
		log_message("error","setConversationNotification_ByCustomer >> obtener telefono del colaborador");		
		$objEmployerPhone = $this->Entity_Phone_Model->get_rowByPrimaryEntity($companyID,$branchID,$entityIDEmployer);
		if(!$objEmployerPhone)
		{
			$dataResult = [
				'success' 	=> false,
				'message' 	=> 'Colaborador no tiene telefono primario',
				'data' 	 	=> [],
				'entityID' 	=> $entityID
			];
			
			log_message("error",print_r($dataResult,true));			
			return $this->response->setJSON($dataResult);
		}
		
		
		//Actulizar Conversacion, cliente existe pero no tiene conversacion
		log_message("error","setConversationNotification_ByCustomer >> obtener conversacion");		
		$objCustomerConversation				= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		if(!$objCustomerConversation)
		{
			//crear una conversacion al cliente
			log_message("error",print_r("crear conversacion",true));
			$conversationIsNew	= true;			
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$entityID);				
			if($conversationID == 0)
			{
				$dataResult = [
					'success' 	=> false,
					'message' 	=> 'No fue posible crear la conversacion',
					'data' 	 	=> [],
					'entityID' 	=> $entityID
				];
				
				log_message("error",print_r($dataResult,true));
				return $this->response->setJSON($dataResult);
			}
			
			//asociar el colaborador a la converascion
			$objListEntityIDEmployer 	= array();
			$objListEntityIDEmployer[] 	= $entityIDEmployer; 
			$this->core_web_conversation->createEmployerInConversation($dataSession,$conversationID,$objListEntityIDEmployer);
			$objCustomerConversation	= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		}
		
		log_message("error","setConversationNotification_ByCustomer >> actualiaz conversacion");		
		$objConversation 						= array();
		$objConversation["messgeConterNotRead"] = 0;
		$objConversation["lastMessage"] 		= $message ;
		$objConversation["lastActivityOn"] 		= helper_getDateTime() ;
		$objConversation["messageSendOn"] 		= helper_getDateTime() ;
		$this->Customer_Conversation_Model->update_app_posme($objCustomerConversation[0]->conversationID,$objConversation);
		
		
		//Actualizar la ultima interaccion del colaborador en la conversacion
		log_message("error","setConversationNotification_ByCustomer >> actualiaz interaccion del colaborador");		
		$objCCR 					= array();
		$objCCR["lastActivityOn"] 	= helper_getDateTime();
		$this->Company_Component_Relation_Model->update_app_posme_byConversationID_AndEntityIDEmployer(
			$objCustomerConversation[0]->conversationID,
			$entityIDEmployer,
			$objCCR
		);
		
		//PROCESAR IMAGEN
		log_message("error","setConversationNotification_ByCustomer >> procesar archivo");		
		if($file)
		{
			
			//Validar si es un archivo valido
			if (!$file->isValid()) {
				$dataResult = [			
					'success' => false,
					'message' => 'Archivo inválido',
					'data' 	  => []
				];
				
				log_message("error",print_r($dataResult,true));			
				return $this->response->setJSON($dataResult);
			}
			if (!in_array($file->getMimeType(), ['image/png','image/jpeg','image/jpg','image/webp'])) {				
				$dataResult = [			
					'success' => false,
					'message' => 'Formato no permitido',
					'data' 	  => []
				];
				
				log_message("error",print_r($dataResult,true));			
				return $this->response->setJSON($dataResult);				
			}
			// Generar nombre único
			$newName 		= $file->getRandomName();
			$urlPath 		= "/company_2/component_".$objComponentCustomerConversation->componentID."/component_item_".$objCustomerConversation[0]->conversationID;
			$documentoPath 	= PATH_FILE_OF_APP.$urlPath;
			$urlPublic 		= base_url()."/".$documentoPath."/".$newName;
			
			log_message("error",print_r($urlPublic,true));
			log_message("error",print_r($documentoPath."/".$newName,true));
			
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755,true);
				chmod($documentoPath, 0755);
			}
			
			// Mover archivo
			$file->move($documentoPath, $newName, true);
		}
		
		log_message("error","setConversationNotification_ByCustomer >> guardar notificacion");		
		$objNotification 							= array();		
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= $objEmployer->firstName;
		$objNotification["to"] 						= $objCustomer[0]->firstName;
		$objNotification["subject"] 				= $file ? $urlPublic : 'no use';
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= "no use";
		$objNotification["title"] 					= $file ? 'image' : 'text';
		$objNotification["tagID"] 					= $objTag->tagID;
		$objNotification["createdOn"] 				= helper_getDateTime();
		$objNotification["isActive"] 				= 1;
		$objNotification["phoneFrom"] 				= $objEmployerPhone[0]->number;
		$objNotification["phoneTo"] 				= $objCustomer[0]->phoneNumber;
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
		$objNotification["entityIDSource"] 			= $entityID;
		$objNotification["entityIDTarget"] 			= $entityIDEmployer;
		$notificationID 							= $this->Notification_Model->insert_app_posme($objNotification);
	
		//Enviar mensaje usando wapi
		log_message("error","setConversationNotification_ByCustomer >> obtener firma");		
		$message	= $this->core_web_conversation->getMessageSignature($companyID,$typeCompany,$objEmployer->firstName,$message);
		
		
		//Enviar mensaje
		/////////////////////////////////////
		/////////////////////////////////////
		//wg-if (!$file)
		//wg-{
		//wg-	$this->core_web_whatsap->sendMessageBy_VanageApiText_PosMe(
		//wg-		$companyID, 
		//wg-		$message, 
		//wg-		clearNumero($objCustomer[0]->phoneNumber),
		//wg-		'text',
		//wg-		false 			
		//wg-	);
		//wg-}
		//wg-else
		//wg-{
		//wg-	$this->core_web_whatsap->sendMessageBy_VanageApiImage_PosMe(
		//wg-		$companyID, 
		//wg-		$message, 
		//wg-		clearNumero($objCustomer[0]->phoneNumber),
		//wg-		'image',
		//wg-		$urlPublic
		//wg-	);
		//wg-}
		
		$result = null;
		if (!$file)
		{
			log_message("error","setConversationNotification_ByCustomer >> enviar mensaje de texto a: ".clearNumero($objCustomer[0]->phoneNumber));		
			$result = $this->core_web_whatsap->sendMessageGeneric(
				$typeCompany,
				$companyID, 
				$message, 
				clearNumero($objCustomer[0]->phoneNumber)	
			);
		}
		else
		{
			log_message("error","setConversationNotification_ByCustomer >> enviar mensaje de imagen");		
			$result = $this->core_web_whatsap->sendMessageTypeImagGeneric(
				$typeCompany,
				$companyID, 
				$urlPublic, 
				$message,
				clearNumero($objCustomer[0]->phoneNumber)
			);
		}
		
		log_message("error","setConversationNotification_ByCustomer >> proceso finalizado");		
		if($result["status"] == "error") 
		{
			return $this->response->setJSON([
				'success' 	=> false,
				'message' 	=> 'el mensaje se guardo bien, pero no fue posible enviar el mensaje por whatsapp',
				'entityID' 	=> $entityID
			]);
		}
		else
		{
			return $this->response->setJSON([
				'success' 	=> true,
				'message' 	=> 'entityID recibido',
				'entityID' 	=> $entityID
			]);
		}
		
	}
	function setConversationCustomer()
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
		
		//AUTENTICADO
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();
		
		
		//Actualiar Cliente
		$companyID					= $dataSession["user"]->companyID;		
		$branchID					= $dataSession["user"]->branchID;
		$objCustomer 				= array();
		$objCustomer["phoneNumber"] = $data['txtTab2CustomerPhone'] ?? '';		
		$result 					= $this->Customer_Model->update_app_posme($companyID,$branchID,$entityID,$objCustomer);
		
		//Actualizar Natural
		$objNatural 				= array();
		$objNatural["firstName"] 	= $data['txtTab2CustomerName'] ?? '';
		$result 					= $this->Natural_Model->update_app_posme($companyID,$branchID,$entityID,$objNatural);
		
		
		
		$objComponentCustomerConversation		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_conversation");
		if(!$objComponentCustomerConversation)
			throw new \Exception("EL COMPONENTE 'tb_customer_conversation' NO EXISTE...");
		
		//Obtener la conversacion activa
		$objCustomerConversation  = $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($entityID);
		if(!$objCustomerConversation)
		{
			//Obtener la conversacion		
			$conversationIsNew	= true;
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$entityID);
		
		}
		$objCustomerConversation  = $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($entityID);
		
		//Eliminar los agentes de la conversacion activa
		$this->Company_Component_Relation_Model->delete_app_posme_byComponentIDSource_AndComponentItemSourceID(
			$objComponentCustomerConversation->componentID,
			$objCustomerConversation[0]->conversationID
		);
		
		
		//wg-//Asociar los nuevos agentes a la conversacon activa
		$objListEntityIDEmployer = $data["txtTab2ListEmployerAsigned"];
		$this->core_web_conversation->createEmployerInConversation($dataSession,$objCustomerConversation[0]->conversationID,$objListEntityIDEmployer);
		
		//Finalizar la Conversacion
		$workflowStageID 	= $data['txtTab2WorkflowStageID'] ?? '';
		$objWorkflowStage 	= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($workflowStageID);
		if($objWorkflowStage[0]->aplicable == 1)
		{
			$objConversationNew 					= array();
			$objConversationNew["statusID"] 		= $workflowStageID;
			$objConversationNew["lastActivityOn"] 	= helper_getDateTime();
			$this->Customer_Conversation_Model->update_app_posme($objCustomerConversation[0]->conversationID,$objConversationNew);
		}
		
		//Resutado
		return $this->response->setJSON([
			'success' => true,
			'message' => 'entityID recibido'
		]);
	}
	function setConversationConversation_Tools()
	{
		// Obtener JSON enviado por fetch
		$data 								= $this->request->getJSON(true);
		// Extraer entityID
		$txtMarcarTodosComoLeido 			= $data['txtMarcarTodosComoLeido'] ?? null;
		$txtCerrarTodasLasConversaciones 	= $data['txtCerrarTodasLasConversaciones'] ?? null;

		
		//AUTENTICADO
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();
		
		
		//Actualiar Cliente
		$companyID					= $dataSession["user"]->companyID;		
		$branchID					= $dataSession["user"]->branchID;
		$userID						= $dataSession["user"]->userID;
		$roleID						= $dataSession["role"]->roleID;
		$entityIDEmployer			= $dataSession["user"]->employeeID;
		
		//Obtener la lista de conversaciones del usuario
		if($txtMarcarTodosComoLeido == true)
		{
			$objListConversation 		= $this->
						Customer_Conversation_Model->
						getByEntityEntityIDEmployer_StatusNameRegister($entityIDEmployer);
			
			
			if($objListConversation)
			{
				foreach($objListConversation as $conversation)
				{
					$data 						 = array();
					$data["messgeConterNotRead"] = 0;
					$this->Customer_Conversation_Model->update_app_posme($conversation->conversationID,$data);
				}
			}
		}
		
		if($txtCerrarTodasLasConversaciones == true)
		{
			
			$objListConversation 		= $this->
						Customer_Conversation_Model->
						getByAll_StatusNameRegister();
			
			if($objListConversation)
			{
				
				foreach($objListConversation as $conversation)
				{
					
					$data 						 = array();
					$data["statusID"] 			 = $this->core_web_workflow->getWorkflowStageByStageInit(
							"tb_customer_conversation","statusID",
							$conversation->statusID,
							$companyID,
							$branchID,
							$roleID
					)[0]->workflowStageID;
					$this->Customer_Conversation_Model->update_app_posme($conversation->conversationID,$data);
				}
			}
		}
		
		//Resutado
		return $this->response->setJSON([
			'success' => true,
			'message' => 'entityID recibido'
		]);
	}
	public function WebHookInsertAndUpdateElementLiveConnectWebHook()
	{
		// JSON crudo (string completo)
		log_message("error","WebHookInsertAndUpdateElementLiveConnectWebHook");
		$rawJson = $this->request->getBody();

		// Ejemplo: guardarlo en log o BD
		//{"chat":{"IPs":"186.77.207.249","browser":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36","contacto":{"default_target":0,"default_target_id":0,"destacado":0,"etiquetas":{"0":0},"extra1":"","extra2":"","id":"YSZ0A13040785761QQPI0","isbot":0,"nombre":"Nuevo Visitante","pais":"-"},"destacado":0,"etiquetas":{"0":0},"fecha":1768149772,"fecha_ini":1768149773,"id":"ADKAY50362847409VZHU0","id_canal":60644,"id_grupo":33674,"id_usuario":59221,"isbot":1,"presence":{"status":"away","timestamp":1768149776},"traceID":"Root=1-6963d30c-308dc5150b41c394527f5d79","usuarios":{"59221":{"assign":true,"avatar":"","bot_data":{"id":59221,"id_flowbot":16986,"id_usuario":59221,"name":"flowbot"},"fecha":1768149773,"id":59221,"isbot":1,"noleidos":5,"nombre":"*BOT* Flowbot: Bot Atencion Virtual"}}},"inputs":{"id":"YSZ0A13040785761QQPI0","id_contacto":null,"avatar":null,"nombre":"Nuevo Visitante","apellidos":null,"email":null,"correo":null,"celular":null,"genero":null,"pais":"-","ciudad":null,"direccion":null,"ubicacion":null,"tipo_documento":null,"num_documento":null,"fecha_cumple":null,"dinamicos":null,"habeasdata":null,"destacado":0,"extra1":"","extra2":"","valor":null,"etiquetas":[0],"default_target":0,"default_target_id":0,"isbot":0,"mensaje_inicial":"Hola!"}}
		//wg-$rawJson = '
		//wg-		{
		//wg-			"chat":
		//wg-			{
		//wg-				"IPs":"186.77.207.249",
		//wg-				"browser":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36",
		//wg-				"contacto":
		//wg-					{
		//wg-						"default_target":0,
		//wg-						"default_target_id":0,
		//wg-						"destacado":0,
		//wg-						"etiquetas":{"0":0},
		//wg-						"extra1":"","extra2":"",
		//wg-						"id":"YSZ0A13040785761QQPI0",
		//wg-						"isbot":0,
		//wg-						"nombre":"Nuevo Visitante","pais":"-"
		//wg-					},
		//wg-				"destacado":0,
		//wg-				"etiquetas":{"0":0},
		//wg-				"fecha":1768149772,"fecha_ini":1768149773,"id":"ADKAY50362847409VZHU0",
		//wg-				"id_canal":60644,"id_grupo":33674,"id_usuario":59221,"isbot":1,
		//wg-				"presence":{"status":"away","timestamp":1768149776},
		//wg-				"traceID":"Root=1-6963d30c-308dc5150b41c394527f5d79",
		//wg-				"usuarios":{
		//wg-						"59221":{
		//wg-								"assign":true,"avatar":"",
		//wg-								"bot_data":{"id":59221,"id_flowbot":16986,"id_usuario":59221,"name":"flowbot"},
		//wg-								"fecha":1768149773,"id":59221,"isbot":1,"noleidos":5,
		//wg-								"nombre":"*BOT* Flowbot: Bot Atencion Virtual"
		//wg-						}
		//wg-				}
		//wg-			},
		//wg-			"inputs":{
		//wg-					"id":"YSZ0A13040785761QQPI0",
		//wg-					"id_contacto":null,"avatar":null,"nombre":"Nuevo Visitante",
		//wg-					"apellidos":null,"email":null,"correo":null,"celular":null,
		//wg-					"genero":null,"pais":"-","ciudad":null,"direccion":null,
		//wg-					"ubicacion":null,"tipo_documento":null,"num_documento":null,
		//wg-					"fecha_cumple":null,"dinamicos":null,"habeasdata":null,
		//wg-					"destacado":0,"extra1":"","extra2":"","valor":null,"etiquetas":[0],
		//wg-					"default_target":0,"default_target_id":0,"isbot":0,"mensaje_inicial":"Hola!"
		//wg-			}
		//wg-		}'; 
		log_message('error', 'Webhook RAW JSON: ' . $rawJson);	
		$data    = json_decode($rawJson, true);
		log_message('error', 'Webhook RAW JSON: ' . print_r($data,true));	
		
		echo "Request:";
		echo print_r($data,true);

		if (!$data) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'JSON inválido'
			])->setStatusCode(400);
		}

		// Ejemplo de lectura segura
		/* ===============================
		   CHAT
		================================ */
		$chat                 = $data['chat'] ?? [];
		$chat_ip              = $chat['IPs'] ?? '';
		$chat_browser         = $chat['browser'] ?? '';
		$chat_fecha           = $chat['fecha'] ?? null;
		$chat_fecha_ini       = $chat['fecha_ini'] ?? null;
		$chat_id              = $chat['id'] ?? '';
		$chat_id_canal        = $chat['id_canal'] ?? null;
		$chat_id_grupo        = $chat['id_grupo'] ?? null;
		$chat_id_usuario      = $chat['id_usuario'] ?? null;
		$chat_isbot           = $chat['isbot'] ?? 0;
		$chat_trace_id        = $chat['traceID'] ?? '';
		$chat_destacado       = $chat['destacado'] ?? 0;

		/* ===============================
		   CONTACTO (dentro de chat)
		================================ */
		$contacto             = $chat['contacto'] ?? [];
		$contacto_id          = $contacto['id'] ?? '';
		$contacto_nombre      = $contacto['nombre'] ?? '';
		$contacto_pais        = $contacto['pais'] ?? '';
		$contacto_isbot       = $contacto['isbot'] ?? 0;
		$contacto_destacado   = $contacto['destacado'] ?? 0;
		$contacto_extra1      = $contacto['extra1'] ?? '';
		$contacto_extra2      = $contacto['extra2'] ?? '';
		$contacto_default_tg  = $contacto['default_target'] ?? 0;
		$contacto_default_tg_id = $contacto['default_target_id'] ?? 0;
		$contacto_etiquetas   = $contacto['etiquetas'] ?? [];

		/* ===============================
		   PRESENCE
		================================ */
		$presence             = $chat['presence'] ?? [];
		$presence_status      = $presence['status'] ?? '';
		$presence_timestamp   = $presence['timestamp'] ?? null;

		/* ===============================
		   USUARIOS
		================================ */
		$usuarios             = $chat['usuarios'] ?? [];
		$usuarios_data = [];
		foreach ($usuarios as $usuario_id => $usuario) {

			$usuarios_data[$usuario_id] = [
				'id'          => $usuario['id'] ?? null,
				'nombre'      => $usuario['nombre'] ?? '',
				'isbot'       => $usuario['isbot'] ?? 0,
				'noleidos'    => $usuario['noleidos'] ?? 0,
				'assign'      => $usuario['assign'] ?? false,
				'avatar'      => $usuario['avatar'] ?? '',
				'fecha'       => $usuario['fecha'] ?? null,

				// Bot data
				'bot_id'      => $usuario['bot_data']['id'] ?? null,
				'flowbot_id'  => $usuario['bot_data']['id_flowbot'] ?? null,
				'bot_nombre'  => $usuario['bot_data']['name'] ?? '',
			];
		}

		/* ===============================
		   INPUTS (visitante)
		================================ */
		$inputs                = $data['inputs'] ?? [];
		$input_id              = $inputs['id'] ?? '';
		$input_id_contacto     = $inputs['id_contacto'] ?? null;
		$input_nombre          = $inputs['nombre'] ?? '';
		$input_apellidos       = $inputs['apellidos'] ?? '';
		$input_email           = $inputs['email'] ?? '';
		$input_correo          = $inputs['correo'] ?? '';
		$input_celular         = $inputs['celular'] ?? '';
		$input_genero          = $inputs['genero'] ?? '';
		$input_pais            = $inputs['pais'] ?? '';
		$input_ciudad          = $inputs['ciudad'] ?? '';
		$input_direccion       = $inputs['direccion'] ?? '';
		$input_ubicacion       = $inputs['ubicacion'] ?? '';
		$input_tipo_doc        = $inputs['tipo_documento'] ?? '';
		$input_num_doc         = $inputs['num_documento'] ?? '';
		$input_fecha_cumple    = $inputs['fecha_cumple'] ?? '';
		$input_dinamicos       = $inputs['dinamicos'] ?? null;
		$input_habeasdata      = $inputs['habeasdata'] ?? null;
		$input_destacado       = $inputs['destacado'] ?? 0;
		$input_extra1          = $inputs['extra1'] ?? '';
		$input_extra2          = $inputs['extra2'] ?? '';
		$input_valor           = $inputs['valor'] ?? null;
		$input_etiquetas       = $inputs['etiquetas'] ?? [];
		$input_default_tg      = $inputs['default_target'] ?? 0;
		$input_default_tg_id   = $inputs['default_target_id'] ?? 0;
		$input_isbot           = $inputs['isbot'] ?? 0;
		$input_mensaje_inicial = $inputs['mensaje_inicial'] ?? '';


		//Datos Minimo
		//Nombre  (NOMBRE)
		//Telefono (88888888)
		//Id de la propiedad de interes   (NUMERO)
		//Id de encutra 24  (NUMERO)
		//Estilo de propiedad (CASA,TERRENO,COMERCIAL)
		//Interes del cliente (RENTA,VENTA)
		//Nombre del agente

		// Guardar en BD, procesar lógica, etc...
		log_message('error', 'Nombre: '.$contacto_nombre);
		log_message('error', 'Webhook RAW JSON: Success');
		
		echo "Response:";
		return $this->response->setJSON([
			'success' => true
		]);
		
		try
		{
			//Ingresar al cliente
			$dataSession 				= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
			$objComponentCustomer		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
				throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			$objDataSet								= null;
			$companyID 								= $dataSession["user"]->companyID;
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;
			$objEntity["branchID"]					= $dataSession["user"]->branchID;
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession,$this->request);
			
			date_default_timezone_set(APP_TIMEZONE);
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= 0;
			$exchangeRateTotal 						= 0;
			$exchangeRateAmount 					= 0;
			$db=db_connect();
			$db->transStart();		
			
			$entityID 							= $this->Entity_Model->insert_app_posme($objEntity);
			$objDataSet["entityID"] 			= $entityID;
			$objDataSet["customerCreditLineID"]	= 0;
			$objListComanyParameter			 	= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);

			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= $contacto_nombre;
			$objNatural["lastName"]		= $contacto_nombre;
			$objNatural["address"]		= "";
			$objNatural["statusID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_STATUS_CIVIL_ID_DEFAULT")->value;
			$objNatural["profesionID"]	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PROFESION_ID_DEFAULT")->value;
			$result 					= $this->Natural_Model->insert_app_posme($objNatural);

			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= $contacto_nombre;
			$objLegal["legalName"]		= $contacto_nombre;
			$objLegal["address"]		= "";
			$result 					= $this->Legal_Model->insert_app_posme($objLegal);
			
			$paisDefault 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_PAIS_DEFAULT")->value;
			$departamentoDefault 		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DEPARTAMENTO_DEFAULT")->value;
			$municipioDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_MUNICIPIO_DEFAULT")->value;
			$plazoDefault 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_PLAZO_DEFAULT")->value;
			$typeAmortizationDefault 	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_TYPE_AMORTIZATION")->value;
			$frecuencyDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_FRECUENCIA_PAY_DEFAULT")->value;
			$creditLineDefault 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_CREDIT_LINE_DEFAULT")->value;
			$validarCedula 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_VALIDAR_CEDULA_REPETIDA")->value;
			$interesDefault				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_INTERES_DEFAULT")->value;
			$dayExcludedDefault 		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_DAY_EXCLUDED_IN_CREDIT")->value;

			$paisID 					= $paisDefault;
			$departamentoId				= $departamentoDefault;
			$municipioId				= $municipioDefault;

			$objCustomer["companyID"]			= $objEntity["companyID"];
			$objCustomer["branchID"]			= $objEntity["branchID"];
			$objCustomer["entityID"]			= $entityID;
			$objCustomer["customerNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer",0);
			$objDataSet["customerNumber"] 		= $objCustomer["customerNumber"];
			$objCustomer["identificationType"]	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_IDENTIFICATION_TYPE_DEFAULT")->value;
			$objCustomer["identification"]		= $input_celular;

			//validar que se permita la omision de la cedula
			if(strcmp($validarCedula,"true") == 0)
			{
				//Validar que ya existe el cliente
				$objCustomerOld					= $this->Customer_Model->get_rowByIdentification($companyID,$objCustomer["identification"]);
				if($objCustomerOld)
				{
					throw new \Exception("Error identificacion del cliente ya existe.");
				}
			}
			
			$objCustomer["countryID"]			= $paisID;
			$objCustomer["stateID"]				= $departamentoId;
			$objCustomer["cityID"]				= $municipioId;
			$objCustomer["location"]			= $input_ubicacion;
			$objCustomer["address"]				= "";
			$objCustomer["currencyID"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_CURRENCY_ID_DEFAULT")->value;
			$objCustomer["clasificationID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CLASIFICATION_ID_DEFAULT")->value;
			$objCustomer["categoryID"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CATEGORY_ID_DEFAULT")->value;
			$objCustomer["subCategoryID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_SUBCATEGORY_ID_DEFAULT")->value;
			$objCustomer["customerTypeID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_ID_DEFAULT")->value;
			$objCustomer["birthDate"]			= date("Y-m-d");
			$objCustomer["dateContract"]		= date("Y-m-d");
			$objCustomer["statusID"]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;; //lo mismo statusid de producto solo cambiar nombre de la tabla
			$objCustomer["typePay"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_TYPE_PAY_ID_DEFAULT")->value;
			$objCustomer["payConditionID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_PAY_CONDITION_ID_DEFAULT")->value;
			$objCustomer["sexoID"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_CUSTOMER_SEX_ID_DEFAULT")->value;
			$objCustomer["reference1"]			= "";
			$objCustomer["reference2"]			= "";
			$objCustomer["reference3"]			= "";
			$objCustomer["reference4"]			= "";
			$objCustomer["reference5"]			= "";
			$objCustomer["balancePoint"]		= 0;
			$objCustomer["phoneNumber"]			= $input_celular;
			$objCustomer["typeFirm"]			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_TYPE_FIRM_ID_DEFAULT")->value;
			$objCustomer["budget"]				= 0;
			$objCustomer["isActive"]			= true;
			$objCustomer["entityContactID"]		= 0;
			$objCustomer["formContactID"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_FORM_CONTACT_ID_DEFAULT")->value;
			$objCustomer["allowWhatsappPromotions"]		= 0;
			$objCustomer["allowWhatsappCollection"]		= 0;
			
			$this->core_web_auditoria->setAuditCreated($objCustomer,$dataSession,$this->request);
			$result 							= $this->Customer_Model->insert_app_posme($objCustomer);
			
			//Ingresar registro en el lector biometrico
			$validateBiometric = $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_USE_BIOMETRIC");
			if(strcmp(strtolower($validateBiometric->value), "true") == 0)
			{	
				$dataUser["id"]							= $entityID;
				$dataUser["name"]						= "buscar en otra base";
				$dataUser["email"]						= "buscar en otra base";
				$dataUser["email_verified_at"]			= "0000-00-00 00:00:00";
				$dataUser["password"]					= "buscar en otra base";
				$dataUser["remember_token"]				= "buscar en otra base";
				$dataUser["created_at"]					= "0000-00-00 00:00:00";
				$dataUser["updated_at"]					= "0000-00-00 00:00:00";
				$dataUser["image"]						= "";
				$resultUser 							= $this->Biometric_User_Model->delete_app_posme($dataUser["id"]);
				$resultUser 							= $this->Biometric_User_Model->insert_app_posme($dataUser);
			}

			//Ingresar Cuenta
			$objEntityAccount["companyID"]			= $objEntity["companyID"];
			$objEntityAccount["componentID"]		= $objComponentCustomer->componentID;
			$objEntityAccount["componentItemID"]	= $entityID;
			$objEntityAccount["name"]				= "";
			$objEntityAccount["description"]		= "";
			$objEntityAccount["accountTypeID"]		= "0";
			$objEntityAccount["currencyID"]			= "0";
			$objEntityAccount["classID"]			= "0";
			$objEntityAccount["balance"]			= "0";
			$objEntityAccount["creditLimit"]		= "0";
			$objEntityAccount["maxCredit"]			= "0";
			$objEntityAccount["debitLimit"]			= "0";
			$objEntityAccount["maxDebit"]			= "0";
			$objEntityAccount["statusID"]			= "0";

			$objEntityAccount["accountID"]			= "0";
			$objEntityAccount["statusID"]			= "0";
			$objEntityAccount["isActive"]			= 1;
			$this->core_web_auditoria->setAuditCreated($objEntityAccount,$dataSession,$this->request);
			$this->Entity_Account_Model->insert_app_posme($objEntityAccount);

			
			//Ingresar Customer Credit
			$objCustomerCredit["companyID"] 		= $objEntity["companyID"];
			$objCustomerCredit["branchID"] 			= $objEntity["branchID"];
			$objCustomerCredit["entityID"] 			= $entityID;
			$objCustomerCredit["limitCreditDol"] 	= 900000;
			$objCustomerCredit["balanceDol"] 		= $objCustomerCredit["limitCreditDol"];
			$objCustomerCredit["incomeDol"] 		= 5000;
			$this->Customer_Credit_Model->insert_app_posme($objCustomerCredit);

			//Lineas de Creditos
			$arrayListCustomerCreditLineID	= array();
			$arrayListCreditLineID			= array();
			$arrayListCreditCurrencyID		= array();
			$arrayListCreditStatusID		= array();
			$arrayListCreditInterestYear	= array();
			$arrayListCreditInterestPay		= array();
			$arrayListCreditTotalPay		= array();
			$arrayListCreditTotalDefeated	= array();
			$arrayListCreditDateOpen		= array();
			$arrayListCreditPeriodPay		= array();
			$arrayListCreditDateLastPay		= array();
			$arrayListCreditTerm			= array();
			$arrayListCreditNote			= array();
			$arrayListCreditLine			= array();
			$arrayListCreditNumber			= array();
			$arrayListCreditLimit			= array();
			$arrayListCreditBalance			= array();
			$arrayListCreditStatus			= array();
			$arrayListTypeAmortization		= array();
			$arrayListDayExcluded			= array();
			$limitCreditLine 				= 0;




			if(empty($arrayListCustomerCreditLineID))
			{
				$arrayListCustomerCreditLineID[0]	= 1;
				$arrayListCreditLineID[0] 			= $creditLineDefault;
				$arrayListCreditCurrencyID[0]		= $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;
				$arrayListCreditLimit[0]			= 300000;
				$arrayListCreditInterestYear[0]		= $interesDefault;
				$arrayListCreditInterestPay[0]		= 0;
				$arrayListCreditTotalPay[0]			= 0;
				$arrayListCreditTotalDefeated[0]	= 0;
				$arrayListCreditPeriodPay[0]		= $frecuencyDefault;
				$arrayListCreditTerm[0]				= $plazoDefault;
				$arrayListCreditNote[0]				= "-";
				$arrayListTypeAmortization[0]		= $typeAmortizationDefault;
				$arrayListDayExcluded[0]			= $dayExcludedDefault;				
				$arrayListCreditStatusID[0]			= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;

			}
			
			

			if(!empty($arrayListCustomerCreditLineID))
			{
				foreach($arrayListCustomerCreditLineID as $key => $value)
				{
					$objCustomerCreditLine["companyID"]		= $objEntity["companyID"];
					$objCustomerCreditLine["branchID"]		= $objEntity["branchID"];
					$objCustomerCreditLine["entityID"]		= $entityID;
					$objCustomerCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objCustomerCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objCustomerCreditLine["currencyID"]	= $arrayListCreditCurrencyID[$key];
					$objCustomerCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objCustomerCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objCustomerCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objCustomerCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objCustomerCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objCustomerCreditLine["dateOpen"]		= date("Y-m-d");
					$objCustomerCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objCustomerCreditLine["dateLastPay"]	= date("Y-m-d");
					$objCustomerCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objCustomerCreditLine["note"]			= $arrayListCreditNote[$key];
					$objCustomerCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objCustomerCreditLine["isActive"]		= 1;
					$objCustomerCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
					$objCustomerCreditLine["dayExcluded"]		= $arrayListDayExcluded[$key];
					$limitCreditLine 							= $limitCreditLine + $objCustomerCreditLine["limitCredit"];
					$exchangeRate 								= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCustomerCreditLine["currencyID"],$objCurrencyDolares->currencyID);//cordobas a dolares, o dolares a dolares.
					$exchangeRateAmount							= $objCustomerCreditLine["limitCredit"];
					$customerCreditLineID 						= $this->Customer_Credit_Line_Model->insert_app_posme($objCustomerCreditLine);
					$objDataSet["customerCreditLineID"]			= $customerCreditLineID;




					//sumar los limites en dolares
					if($exchangeRate == 1)
						$exchangeRateTotal = $exchangeRateTotal + $exchangeRateAmount;
					//sumar los limite en cordoba
					else
						$exchangeRateTotal = $exchangeRateTotal + ($exchangeRateAmount / $exchangeRate);



				}
			}


			//Validar Limite de Credito
			if($exchangeRateTotal > $objCustomerCredit["limitCreditDol"])
				throw new \Exception("LINEAS DE CREDITOS MAL CONFIGURADAS LÍMITE EXCEDIDO");
			
			
			//Asociar el cliente al colaborador
			$objUserAdmin					=  $this->User_Model->get_rowByRoleAdmin($dataSession["user"]->companyID);			
			if($objUserAdmin)
			{
				$objListEmployerID 		= array_map(function($i) { return $i->employeeID; }, $objUserAdmin);
				$objListEmployerID[] 	= $dataSession["user"]->employeeID;
				$objListEmployerID 		= array_unique($objListEmployerID);
				
				foreach ($objListEmployerID as $employerIDT)
				{
					$dataRelationShip				= NULL;
					$dataRelationShip["employeeID"]	= $employerIDT;
					$dataRelationShip["customerID"]	= $entityID;
					$dataRelationShip["isActive"]	= 1;
					$dataRelationShip["startOn"]	= date("Y-m-d");
					$dataRelationShip["endOn"]		= date("Y-m-d");
					$this->Relationship_Model->insert_app_posme($dataRelationShip);					
				}
			}
			
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			$pathfile = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentCustomer->componentID."/component_item_".$entityID;

			if (!file_exists($pathfile))
			{
				mkdir($pathfile, 0700,true);
			}


			
			if($db->transStatus() !== false)
			{
				log_message('error', 'Webhook RAW JSON: Success');
				$db->transCommit();
				return $this->response->setJSON([
					'success' => true
				]);
				
			}
			else{
				log_message('error', 'Webhook RAW JSON: Error');
				$db->transRollback();
				return $this->response->setJSON([
					'success' => true,
					'message' => "",   		// mensaje del error
					'line'    => "0",      	// línea donde ocurrió
					'file'    => ""       	// archivo (opcional pero útil)
				]);
				
			}

		}
		catch(\Exception $ex)
		{
			return $this->response->setJSON([
				'success' => false,
				'message' => $ex->getMessage(),   // mensaje del error
				'line'    => $ex->getLine(),      // línea donde ocurrió
				'file'    => $ex->getFile()       // archivo (opcional pero útil)
			])->setStatusCode(400);
		}
		
	}
	public function WebHookInsertAndUpdateElementLiveConnectReceiptWhatsappWebHook()
	{
		// JSON crudo (string completo)
		log_message("error","WebHookInsertAndUpdateElementLiveConnectReceiptWhatsappWebHook");
		$rawJson = $this->request->getBody();
		$rawJson = '{"type":"chatAdd","payload":{"id_conversacion":"LCWAP|361|50587125827C","id_canal":361,"mensaje_inicial":"Testing prueba","ingreso":1768256052,"contacto":{"id":26108813,"nombre":"posMe"},"usuario_autoasignado":{"id":59177,"nombre":"Santa Lucia Real Estate"}}}';
		log_message('error', 'Webhook RAW JSON: ' . $rawJson);	
		$data    = json_decode($rawJson, true);
		log_message('error', 'Webhook RAW JSON: ' . print_r($data,true));	
		
		echo "Request:";
		echo print_r($data,true);

		if (!$data) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'JSON inválido'
			])->setStatusCode(400);
		}
	
	
		/* ===============================
		   TYPE
		================================ */
		$type 		= $data['type'] ?? '';

		/* ===============================
		   PAYLOAD
		================================ */
		$payload 	= $data['payload'] ?? [];

		/* ===============================
		   DATOS DEL CHAT
		================================ */
		$id_conversacion  = $payload['id_conversacion']  ?? '';
		$id_canal         = $payload['id_canal']         ?? 0;
		$mensaje_inicial  = $payload['mensaje_inicial']  ?? '';
		$ingreso          = $payload['ingreso']          ?? 0;

		/* ===============================
		   CONTACTO
		================================ */
		$contacto 			= $payload['contacto'] ?? [];
		$contacto_id     	= $contacto['id']     ?? 0;
		$contacto_nombre 	= $contacto['nombre'] ?? '';


		/* ===============================
		   USUARIO AUTOASIGNADO
		================================ */
		$usuario_autoasignado 	= $payload['usuario_autoasignado'] ?? [];
		$usuario_id     		= $usuario_autoasignado['id']     ?? 0;
		$usuario_nombre 		= $usuario_autoasignado['nombre'] ?? '';


		/* ===============================
		   CAST DE SEGURIDAD
		================================ */
		$id_canal      = (int)$id_canal;
		$ingreso       = (int)$ingreso;
		$contacto_id   = (int)$contacto_id;
		$usuario_id    = (int)$usuario_id;

		
		//2024-07-22
        //api token: https://api.liveconnect.chat/prod/account/token
        $Parameter_Model 			= new Parameter_Model();
        $Company_Parameter_Model 	= new Company_Parameter_Model();

        $objPWhatsapPrivatekey		= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
        $objPWhatsapPrivatekeyId	= $objPWhatsapPrivatekey->parameterID;
        $objPWhatsapPrivatekey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $objPWhatsapCkey		= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
        $objPWhatsapCkeyId		= $objPWhatsapCkey->parameterID;
        $objPWhatsapCkey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapCkeyId);

        $objPWhatsapUrlTokenMessage			= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
        $objPWhatsapUrlTokenMessageId 		= $objPWhatsapUrlTokenMessage->parameterID;
        $objCP_WhatsapUrlTokenMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlTokenMessageId);

        $objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
        $objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
        $objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);

        //id canal
        $objPWhatsapIdCanal		= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION_PARAMETERF1");
        $objPWhatsapPrivatekeyId	= $objPWhatsapIdCanal->parameterID;
        $objPWhatsapIdCanal		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $objCP_WhatsapUrlTokenMessage->value,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'cKey' => $objPWhatsapCkey->value,
                'privateKey' => $objPWhatsapPrivatekey->value
            ]),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json, application/xml",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        }
        //return $response;
        $response_data 	= json_decode($response, true);

        if($response_data['status'] ==1)
        {
            $token = $response_data['PageGearToken'];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.liveconnect.chat/prod/direct/wa/sendFile",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'id_canal' => $objPWhatsapIdCanal->value,
                    'numero'=>$phoneDestino,                    
					"url"=> $urlImage,
					"nombre"=> $nameImage,
				    "extension"=> $extImage
  
                ]),
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "PageGearToken: ".$token
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                $response_data 	= json_decode($response, true);
                return $response_data['status_message'];
            }
        }
        return "";
		
		return $this->response->setJSON([
			'success' => true,
			'message'    => 'JSON valido'
		]);
		
	}
	public function WebHookReceiptMessage_Whatsapp_Wati()
	{
		// JSON crudo (string completo)
		log_message("error","WebHookReceiptMessage_Whatsapp_Wati");
		$rawJson = $this->request->getBody();
		$rawJson = '{"type":"chatAdd","payload":{"id_conversacion":"LCWAP|361|50587125827C","id_canal":361,"mensaje_inicial":"Testing prueba","ingreso":1768256052,"contacto":{"id":26108813,"nombre":"posMe"},"usuario_autoasignado":{"id":59177,"nombre":"Santa Lucia Real Estate"}}}';
		log_message('error', 'Webhook RAW JSON: ' . $rawJson);	
		$data    = json_decode($rawJson, true);
		log_message('error', 'Webhook RAW JSON: ' . print_r($data,true));	
		
		echo "Request:";
		echo print_r($data,true);

		if (!$data) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'JSON inválido'
			])->setStatusCode(400);
		}
	
	
		/* ===============================
		   TYPE
		================================ */
		$type 		= $data['type'] ?? '';

		/* ===============================
		   PAYLOAD
		================================ */
		$payload 	= $data['payload'] ?? [];

		/* ===============================
		   DATOS DEL CHAT
		================================ */
		$id_conversacion  = $payload['id_conversacion']  ?? '';
		$id_canal         = $payload['id_canal']         ?? 0;
		$mensaje_inicial  = $payload['mensaje_inicial']  ?? '';
		$ingreso          = $payload['ingreso']          ?? 0;

		/* ===============================
		   CONTACTO
		================================ */
		$contacto 			= $payload['contacto'] ?? [];
		$contacto_id     	= $contacto['id']     ?? 0;
		$contacto_nombre 	= $contacto['nombre'] ?? '';


		/* ===============================
		   USUARIO AUTOASIGNADO
		================================ */
		$usuario_autoasignado 	= $payload['usuario_autoasignado'] ?? [];
		$usuario_id     		= $usuario_autoasignado['id']     ?? 0;
		$usuario_nombre 		= $usuario_autoasignado['nombre'] ?? '';


		/* ===============================
		   CAST DE SEGURIDAD
		================================ */
		$id_canal      = (int)$id_canal;
		$ingreso       = (int)$ingreso;
		$contacto_id   = (int)$contacto_id;
		$usuario_id    = (int)$usuario_id;

		
		//2024-07-22
        //api token: https://api.liveconnect.chat/prod/account/token
        $Parameter_Model 			= new Parameter_Model();
        $Company_Parameter_Model 	= new Company_Parameter_Model();

        $objPWhatsapPrivatekey		= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
        $objPWhatsapPrivatekeyId	= $objPWhatsapPrivatekey->parameterID;
        $objPWhatsapPrivatekey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $objPWhatsapCkey		= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
        $objPWhatsapCkeyId		= $objPWhatsapCkey->parameterID;
        $objPWhatsapCkey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapCkeyId);

        $objPWhatsapUrlTokenMessage			= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
        $objPWhatsapUrlTokenMessageId 		= $objPWhatsapUrlTokenMessage->parameterID;
        $objCP_WhatsapUrlTokenMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlTokenMessageId);

        $objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
        $objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
        $objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);

        //id canal
        $objPWhatsapIdCanal		= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION_PARAMETERF1");
        $objPWhatsapPrivatekeyId	= $objPWhatsapIdCanal->parameterID;
        $objPWhatsapIdCanal		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $objCP_WhatsapUrlTokenMessage->value,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'cKey' => $objPWhatsapCkey->value,
                'privateKey' => $objPWhatsapPrivatekey->value
            ]),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json, application/xml",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        }
        //return $response;
        $response_data 	= json_decode($response, true);

        if($response_data['status'] ==1)
        {
            $token = $response_data['PageGearToken'];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.liveconnect.chat/prod/direct/wa/sendFile",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'id_canal' => $objPWhatsapIdCanal->value,
                    'numero'=>$phoneDestino,                    
					"url"=> $urlImage,
					"nombre"=> $nameImage,
				    "extension"=> $extImage
  
                ]),
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "PageGearToken: ".$token
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                $response_data 	= json_decode($response, true);
                return $response_data['status_message'];
            }
        }
        return "";
		
		return $this->response->setJSON([
			'success' => true,
			'message'    => 'JSON valido'
		]);
		
	}
	public function WebHookReceiptMessage_Whatsapp_EvolutionApi_posMe()
	{
		// JSON crudo (string completo)
		log_message('error', 'Webhook RAW JSON: ' ."WebHookReceiptMessage_Whatsapp_EvolutionApi_posMe" );	
		$input	 = $this->request->getJSON(true); // true = array
		$rawJson = $this->request->getBody();
		$rawJson = '{"type":"chatAdd","payload":{"id_conversacion":"LCWAP|361|50587125827C","id_canal":361,"mensaje_inicial":"Testing prueba","ingreso":1768256052,"contacto":{"id":26108813,"nombre":"posMe"},"usuario_autoasignado":{"id":59177,"nombre":"Santa Lucia Real Estate"}}}';
		log_message('error', 'Webhook RAW JSON: ' . $rawJson);	
		$data    = json_decode($rawJson, true);
		log_message('error', 'Webhook RAW JSON: data:' . print_r($data,true));	
		
		
		// Captura el POST JSON de Vonage
		log_message("error","input:".print_r($input,true));
        if (!$input) {
			
			$result = [
				'success' => false,
				'message' => 'JSON inválido input Vonage'
			];
			log_message("error",print_r($result,true));
			return $this->response->setJSON($result)->setStatusCode(200);
        }

        // Extraer datos básicos
        $from    = $input['from'] ?? '';
        $to      = $input['to'] ?? '';
        $message = $input['text'] ?? '';
        $msgId   = $input['messageId'] ?? '';
        $status  = $input['status'] ?? '';
		
		
		if (!$data) 
		{
			$result = [
				'success' => false,
				'message' => 'JSON inválido'
			];
			
			log_message("error",print_r($result,true));
			return $this->response->setJSON($result)->setStatusCode(200);
		}
	
		
		
		
		//wg-evolution-api-if(($data['event'] ?? null) !== 'messages.upsert') {
		//wg-evolution-api-	return $this->response->setJSON([
		//wg-evolution-api-		'success' => false,
		//wg-evolution-api-		'message' => 'JSON inválido evento no receipt'
		//wg-evolution-api-	])->setStatusCode(400);
		//wg-evolution-api-}
		//wg-evolution-api-	
		//wg-evolution-api-	
		//wg-evolution-api-$messageData 						= $data['data'];
		//wg-evolution-api-$from 								= $messageData['key']['remoteJid'] ?? null;
		//wg-evolution-api-$data["customerFirstName"] 			= str_replace('@s.whatsapp.net', '', $from);
		//wg-evolution-api-$data["customerPhoneNumber"]		= $data["customerFirstName"];
		//wg-evolution-api-$data["customerMessage"] 			= $messageData['message']['conversation'] 
		//wg-evolution-api-	?? $messageData['message']['extendedTextMessage']['text']
		//wg-evolution-api-	?? '';

		$data["customerPhoneNumber"] = "887646645";
		$data["customerFirstName"]	 = "witmaj gonzalez";
		$data["customerMessage"]	 = "hola que tal";
		
		$customerPhoneNumber 	= clearNumero($data["customerPhoneNumber"]);
		$customerFirstName		= $data["customerFirstName"];
		$message				= $data["customerMessage"];
		$dataSession 			= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID				= $dataSession["user"]->companyID;
		$branchID				= $dataSession["user"]->branchID;
		$roleID 				= $dataSession["role"]->roleID;
		$userID 				= $dataSession["user"]->userID;
		//Obtener al cliente
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
		{
			$this->core_web_conversation->createCustomer($dataSession,$customerPhoneNumber,$customerFirstName,$this->request);
			
		}
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
			throw new \Exception ("Cliente no encontrado");
		
		//Obtener la conversacion
		$conversationIsNew			= false;
		$conversationID				= 0;
		$objCustomerConversation	= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		if(!$objCustomerConversation)
		{
			$conversationIsNew	= true;
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$objCustomer[0]->entityID);
		}
		$objCustomerConversation				= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		$objConversation 						= array();
		$objConversation["messgeConterNotRead"] = 1 ;
		$this->Customer_Conversation_Model->update_app_posme($objCustomerConversation[0]->conversationID,$objConversation);
		
		
		//Ingresar el mensaje a la conversacion activa		
		$objTag		 								= $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		$objNotification 							= array();		
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= $objCustomer[0]->firstName;
		$objNotification["to"] 						= '';
		$objNotification["subject"] 				= "no use";
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= "no use";
		$objNotification["title"] 					= "no use";
		$objNotification["tagID"] 					= $objTag->tagID;
		$objNotification["createdOn"] 				= helper_getDateTime();
		$objNotification["isActive"] 				= 1;
		$objNotification["phoneFrom"] 				= $objCustomer[0]->phoneNumber;
		$objNotification["phoneTo"] 				= '';
		$objNotification["programDate"] 			= helper_getDate();
		$objNotification["programHour"] 			= '00:00';
		$objNotification["sendOn"] 					= NULL;
		$objNotification["sendEmailOn"] 			= NULL;
		$objNotification["sendWhatsappOn"] 			= NULL;
		$objNotification["addedCalendarGoogle"] 	= 0;
		$objNotification["quantityOcupation"] 		= 0;
		$objNotification["quantityDisponible"] 		= 0;
		$objNotification["googleCalendarEventID"] 	= NULL;
		$objNotification["isRead"] 					= 0;
		$objNotification["entityIDSource"] 			= $objCustomer[0]->entityID;
		$objNotification["entityIDTarget"] 			= 0;
		$notificationID 							= $this->Notification_Model->insert_app_posme($objNotification);

		//Obtener la lista de agentes a afiliar
		$objListEntityIDEmployer 					= $this->core_web_conversation->getAllEmployer($companyID,$dataSession["company"]->type,$customerPhoneNumber,$message,$conversationIsNew );				
		$this->core_web_conversation->createEmployerInConversation($dataSession,$objCustomerConversation[0]->conversationID,$objListEntityIDEmployer);
		
		//Resultado
		$result = [
			'success' 			=> true,
			'message'   		=> 'JSON valido',
			'entityID'			=> $objCustomer[0]->entityID,
			'converationID'		=> $objCustomerConversation[0]->conversationID,
			'notificationID'	=> $notificationID
		];	
		log_message("error",print_r($result,true));
		return $this->response->setJSON($result);
		
	}
	public function WebHookReceiptMessage_Whatsapp_VonageApi_posMe()
	{
		// JSON crudo (string completo)
		log_message('error', 'Webhook RAW JSON: ' ."WebHookReceiptMessage_Whatsapp_VonageApi_posMe" );	
		$input	 	= $this->request->getJSON(true); // true = array
		//$rawJson 	= '{"to":"14157386102","from":"50584766457","channel":"whatsapp","message_uuid":"6c1d4fcf-7f80-46dd-9209-f7e34ef4fe9c","timestamp":"2026-01-18T15:02:25Z","message_type":"text","text":"Join debit mouth","context_status":"none","profile":{"name":"www witman"}}';
		//log_message('error', 'Webhook RAW JSON: ' . $rawJson);	
		
		
		// Captura el POST JSON de Vonage
		log_message("error","input:".print_r($input,true));
        if (!$input) {
			$result = [
				'success' => false,
				'message' => 'JSON inválido input Vonage'
			];
			log_message("error",print_r($result,true));
			return $this->response->setJSON($result)->setStatusCode(200);
        }

        // Extraer datos básicos
        //$from    = $input['from'] ?? '';
        //$to      = $input['to'] ?? '';
        //$message = $input['text'] ?? '';
        //$msgId   = $input['message_uuid'] ?? '';
        //$status  = $input['message_type'] ?? '';
		log_message("error","input: init process message");
		$data["customerPhoneNumber"] 	= $input['from'] ?? '';
		$data["customerFirstName"]	 	= $input['from'] ?? '';
		$data["customerMessage"]	 	= $input['text'] ?? '';
		$data["customerMessageType"]	= $input['message_type'] ?? '';
		$data["customerMessageUrl"]		= "";
		$data["customerMessageFile"]	= "";
		if($data["customerMessageType"] == "image")
		{
			$data["customerMessage"]	 	= $input['image']['caption'] ?? '';
			$data["customerMessageUrl"]		= $input['image']['url'] ?? '';
			$data["customerMessageFile"]	= $input['image']['name'] ?? '';
		}
		
		
		
		//$data["customerPhoneNumber"] 	= "887646645";
		//$data["customerFirstName"]	= "witmaj gonzalez";
		//$data["customerMessage"]	 	= "hola que tal";
		
		$customerPhoneNumber 	= getNumberPhone($data["customerPhoneNumber"]);
		$customerFirstName		= getNumberPhone($data["customerFirstName"]);
		$message				= $data["customerMessage"];
		$messageUrl				= $data["customerMessageUrl"];
		$messageFile			= $data["customerMessageFile"];
		$messageType			= $data["customerMessageType"];
		$dataSession 			= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID				= $dataSession["user"]->companyID;
		$branchID				= $dataSession["user"]->branchID;
		$roleID 				= $dataSession["role"]->roleID;
		$userID 				= $dataSession["user"]->userID;
		//Obtener al cliente
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
		{
			$this->core_web_conversation->createCustomer($dataSession,$customerPhoneNumber,$customerFirstName,$this->request);
			
		}
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
			throw new \Exception ("Cliente no encontrado");
		
		//Obtener la conversacion
		$conversationIsNew			= false;
		$conversationID				= 0;
		$objCustomerConversation	= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		if(!$objCustomerConversation)
		{
			$conversationIsNew	= true;
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$objCustomer[0]->entityID);
		}
		$objCustomerConversation				= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		$objConversation 						= array();
		$objConversation["messgeConterNotRead"] = 1 ;
		$this->Customer_Conversation_Model->update_app_posme($objCustomerConversation[0]->conversationID,$objConversation);
		
		
		//Ingresar el mensaje a la conversacion activa		
		$objTag		 								= $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		$objNotification 							= array();		
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= $objCustomer[0]->firstName;
		$objNotification["to"] 						= '';
		$objNotification["subject"] 				= $messageUrl;
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= $messageFile;
		$objNotification["title"] 					= $messageType;
		$objNotification["tagID"] 					= $objTag->tagID;
		$objNotification["createdOn"] 				= helper_getDateTime();
		$objNotification["isActive"] 				= 1;
		$objNotification["phoneFrom"] 				= $objCustomer[0]->phoneNumber;
		$objNotification["phoneTo"] 				= '';
		$objNotification["programDate"] 			= helper_getDate();
		$objNotification["programHour"] 			= '00:00';
		$objNotification["sendOn"] 					= NULL;
		$objNotification["sendEmailOn"] 			= NULL;
		$objNotification["sendWhatsappOn"] 			= NULL;
		$objNotification["addedCalendarGoogle"] 	= 0;
		$objNotification["quantityOcupation"] 		= 0;
		$objNotification["quantityDisponible"] 		= 0;
		$objNotification["googleCalendarEventID"] 	= NULL;
		$objNotification["isRead"] 					= 0;
		$objNotification["entityIDSource"] 			= 0;
		$objNotification["entityIDTarget"] 			= $objCustomer[0]->entityID;
		$notificationID 							= $this->Notification_Model->insert_app_posme($objNotification);

		//Obtener la lista de agentes a afiliar
		$objListEntityIDEmployer 					= $this->core_web_conversation->getAllEmployer($companyID,$dataSession["company"]->type,$customerPhoneNumber,$message,$conversationIsNew );				
		$this->core_web_conversation->createEmployerInConversation($dataSession,$objCustomerConversation[0]->conversationID,$objListEntityIDEmployer);
		
		//Resultado
		$result = [
			'success' 			=> true,
			'message'   		=> 'JSON valido',
			'entityID'			=> $objCustomer[0]->entityID,
			'converationID'		=> $objCustomerConversation[0]->conversationID,
			'notificationID'	=> $notificationID
		];	
		log_message("error",print_r($result,true));
		return $this->response->setJSON($result);
		
	}
	public function WebHookReceiptMessage_Whatsapp_Ultramsg_posMe()
	{
		
		// JSON crudo (string completo)
		$host 		= $this->request->getServer('HTTP_HOST');		
		log_message('error', 'Webhook RAW JSON: ' ."WebHookReceiptMessage_Whatsapp_Ultramsg_posMe" );	
		$input	 	= $this->request->getJSON(true); // true = array
		
		//Solo se permiten mensajes recibidos
		if($input["event_type"] != "message_received"  )
			return;
		
		
		// Captura el POST JSON de Vonage
		log_message("error","input:".print_r($input,true));
        if (!$input) {
			$result = [
				'success' => false,
				'message' => 'JSON inválido input Vonage'
			];
			log_message("error",print_r($result,true));
			return $this->response->setJSON($result)->setStatusCode(200);
        }

        // Extraer datos básicos       
		log_message("error","input: init process message");
		$data["customerPhoneNumber"] 	= $input["data"]['from'] ?? '';
		$data["customerFirstName"]	 	= $input["data"]['pushname'] ?? '';
		$data["customerMessage"]	 	= $input["data"]['body'] ?? '';
		$data["customerMessageType"]	= $input["data"]['type'] ?? '';
		$data["customerMessageUrl"]		= "";
		$data["customerMessageFile"]	= "";
		
		if($data["customerMessageType"] == "chat")
			$data["customerMessageType"] = "text";
		
		if($data["customerMessageType"] == "image")
			$data["customerMessageType"] = "image";
		
		if($data["customerMessageType"] == "ptt")
			$data["customerMessageType"] = "audio";
		
		
		
		if($data["customerMessageType"] == "image")
		{
			$data["customerMessage"]	 	= $input['data']['body'] ?? '';
			$data["customerMessageUrl"]		= $input['data']['media'] ?? '';
			$data["customerMessageFile"]	= $input['data']['media'] ?? '';
		}
		
		if($data["customerMessageType"] == "audio")
		{
			$data["customerMessage"]	 	= $input['data']['body'] ?? '';
			$data["customerMessageUrl"]		= $input['data']['media'] ?? '';
			$data["customerMessageFile"]	= $input['data']['media'] ?? '';
		}
		
		
		
		
		//$data["customerPhoneNumber"] 	= "887646645";
		//$data["customerFirstName"]	= "witmaj gonzalez";
		//$data["customerMessage"]	 	= "hola que tal";
		
		$customerPhoneNumber 	= getNumberPhone($data["customerPhoneNumber"]);
		$customerFirstName		= "new_".$data["customerFirstName"];
		$message				= $data["customerMessage"];
		$messageUrl				= $data["customerMessageUrl"];
		$messageFile			= $data["customerMessageFile"];
		$messageType			= $data["customerMessageType"];
		$dataSession 			= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID				= $dataSession["user"]->companyID;
		$branchID				= $dataSession["user"]->branchID;
		$roleID 				= $dataSession["role"]->roleID;
		$userID 				= $dataSession["user"]->userID;
		
		//Validar si el webhook esta activo
		if (strpos(strtolower($data["customerMessage"]), 'test') !== false) {
			 // 2. Limpiar espacios inicio y fin
			$texto 	= strtolower(trim($data["customerMessage"]));

			// 3. Quitar TODOS los espacios del string
			$texto 	= str_replace(' ', '', $texto);
			$texto 	= str_replace('-', '', $texto);

			// 4. Split por :
			$partes = explode(':', $texto);

			// 5. Obtener valores
			$mensaje 	= $partes[0] ?? '';
			$email  	= $partes[1] ?? '';
			$phone  	= $partes[2] ?? '';
			
			
			//Enviar email
			log_message("error","input: mandar email de tester");
			$objCompany 			= $dataSession["company"];
			$params_["nickname"]	= $dataSession["user"]->nickname;
			$params_["objCompany"]	= $objCompany;				
			$params_["mensaje"]		= "Webhook SUCCESS: ".$objCompany->name;
			$subject 				= "Test de Webhook SUCCESS ".$objCompany->name;
			$body  					= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview			
			$this->email->setFrom(EMAIL_APP);
			$this->email->setTo( $email );
			$this->email->setSubject($subject);			
			$this->email->setMessage($body); 			
			$resultSend01 = $this->email->send();
			$resultSend02 = $this->email->printDebugger();	

			//Enviar whatsapp
			log_message("error","input: mandar whatsapp de tester");
			$result = $this->core_web_whatsap->sendMessageGeneric(
				$objCompany->type,
				$objCompany->companyID, 
				"Test de whatsapp:".$objCompany->name, 
				clearNumero($phone)	
			);
			log_message("error","input: fin del proceso");
			return;
		}
		
		//Obtener al cliente
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
		{
			$this->core_web_conversation->createCustomer($dataSession,$customerPhoneNumber,$customerFirstName,$this->request);
			
		}
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
			throw new \Exception ("Cliente no encontrado");
		
		//Obtener la conversacion
		$conversationIsNew			= false;
		$conversationID				= 0;
		$objCustomerConversation	= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		if(!$objCustomerConversation)
		{
			$conversationIsNew	= true;
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$objCustomer[0]->entityID);
		}
		$objCustomerConversation				= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		$lastActivityOnOld						= $objCustomerConversation[0]->lastActivityOn;
		$lastActivityOnNew						= helper_getDateTime() ;
		$objConversation 						= array();
		$objConversation["messgeConterNotRead"] = 1 ;
		$objConversation["lastMessage"] 		= $message ;
		$objConversation["lastActivityOn"] 		= $lastActivityOnNew;
		$objConversation["messageReceiptOn"] 	= $lastActivityOnNew;		
		$this->Customer_Conversation_Model->update_app_posme($objCustomerConversation[0]->conversationID,$objConversation);
		
			
		//Ingresar el mensaje a la conversacion activa		
		$objTag		 								= $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		$objNotification 							= array();		
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= $objCustomer[0]->firstName;
		$objNotification["to"] 						= '';
		$objNotification["subject"] 				= $messageUrl;
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= $messageFile;
		$objNotification["title"] 					= $messageType;
		$objNotification["tagID"] 					= $objTag->tagID;
		$objNotification["createdOn"] 				= helper_getDateTime();
		$objNotification["isActive"] 				= 1;
		$objNotification["phoneFrom"] 				= $objCustomer[0]->phoneNumber;
		$objNotification["phoneTo"] 				= '';
		$objNotification["programDate"] 			= helper_getDate();
		$objNotification["programHour"] 			= '00:00';
		$objNotification["sendOn"] 					= NULL;
		$objNotification["sendEmailOn"] 			= NULL;
		$objNotification["sendWhatsappOn"] 			= NULL;
		$objNotification["addedCalendarGoogle"] 	= 0;
		$objNotification["quantityOcupation"] 		= 0;
		$objNotification["quantityDisponible"] 		= 0;
		$objNotification["googleCalendarEventID"] 	= NULL;
		$objNotification["isRead"] 					= 0;
		$objNotification["entityIDSource"] 			= 0;
		$objNotification["entityIDTarget"] 			= $objCustomer[0]->entityID;
		$notificationID 							= $this->Notification_Model->insert_app_posme($objNotification);

		//Obtener la lista de agentes a afiliar
		$objListEntityIDEmployer 					= $this->core_web_conversation->getAllEmployer($companyID,$dataSession["company"]->type,$customerPhoneNumber,$message,$conversationIsNew );				
		$this->core_web_conversation->createEmployerInConversation($dataSession,$objCustomerConversation[0]->conversationID,$objListEntityIDEmployer);
		
		//////////////////////////////////////////////
		//Notificar a los agentes afiliados
		//////////////////////////////////////////////
		$diferenceDate 							= helper_CompareDateTime($lastActivityOnOld,$lastActivityOnNew);
		log_message("error","Diferencia en fecha entre la conversacion actual y la anterior");
		log_message("error",print_r($lastActivityOnOld,true));
		log_message("error",print_r($lastActivityOnNew,true));
		log_message("error",print_r($diferenceDate,true));		
		//Han pasado almenos 5 minutos desde el utlimo mensaje
		if($diferenceDate["comparador"] == "-1" && ((int)$diferenceDate["minutos"]) >= 5 )
		{			
			log_message("error","Enviar mensaje colaboradores asignados");
			$urlSend		= base_url()."/app_cxc_conversation/edit/entityID/".$objCustomer[0]->entityID;
			$whatsappLink 	= urlencode($urlSend);
			$short 			= file_get_contents("https://is.gd/create.php?format=simple&url=$whatsappLink");
		
			$this->core_web_conversation->notificationEmployerInConversation(
				$dataSession["company"]->companyID,
				$dataSession["user"]->branchID,
				$dataSession["company"]->type,
				$objCustomerConversation[0]->conversationID,
			"📩 *Cliente:".$objCustomer[0]->firstName."* ha enviado un mensaje 

👉 Por favor, respóndelo en el siguiente enlace:
🌐 ".$short
			);		
		}
		
		
		//Resultado
		$result = [
			'success' 			=> true,
			'message'   		=> 'JSON valido',
			'entityID'			=> $objCustomer[0]->entityID,
			'converationID'		=> $objCustomerConversation[0]->conversationID,
			'notificationID'	=> $notificationID
		];	
		log_message("error",print_r($result,true));
		return $this->response->setJSON($result);
		
	}
	public function WebHookReceiptMessage_Whatsapp_AppzApiIo_posMe()
	{
		
		return;
		log_message('error', 'Webhook RAW JSON: ' ."WebHookReceiptMessage_Whatsapp_AppzApiIo_posMe" );			
		// 1️⃣ Leer el body crudo (FORMA CORRECTA PARA WEBHOOKS)
		$raw 	= file_get_contents('php://input');
		
		// 3️⃣ Intentar decodificar JSON
		$input 	= json_decode($raw, true);
		log_message('error', 'Webhook INPUT : ' . print_r($input,true) );	
		
		
        if (!$input) {
			$result = [
				'success' => false,
				'message' => 'JSON inválido input Vonage'
			];
			log_message("error",print_r($result,true));
			return $this->response->setJSON($result)->setStatusCode(200);
        }

		//Solo se permiten mensajes recibidos
		if($input["type"] != "ReceivedCallback" )
			return;
		
		
		
        // Extraer datos básicos   
		$data = array();
		if(array_key_exists('imagen', $input))
		{
			$data["customerMessageType"] = "image";
		}
		if(array_key_exists('text', $input))
		{
			$data["customerMessageType"] = "text";
		}
		if(array_key_exists('audio', $input))
		{
			$data["customerMessageType"] = "audio";
		}
		
		log_message("error","input: init process message");
		$data["customerPhoneNumber"] 	= $input['phone'] ?? '';
		$data["customerFirstName"]	 	= $input['senderName'] ?? '';
		$data["customerMessage"]	 	= "";		
		$data["customerMessageUrl"]		= "";
		$data["customerMessageFile"]	= "";	
		if($data["customerMessageType"] == "text")
		{
			$data["customerMessage"]	 	= $input['text']['message'] ?? '';
		}
		
		if($data["customerMessageType"] == "image")
		{
			$data["customerMessage"]	 	= $input['image']['caption'] ?? '';
			$data["customerMessageUrl"]		= $input['image']['imageUrl'] ?? '';
			$data["customerMessageFile"]	= $input['image']['imageUrl'] ?? '';
		}
		
		if($data["customerMessageType"] == "audio")
		{
			$data["customerMessage"]	 	= $input['audio']['caption'] ?? '';
			$data["customerMessageUrl"]		= $input['audio']['imageUrl'] ?? '';
			$data["customerMessageFile"]	= $input['audio']['imageUrl'] ?? '';
		}
		
		
		
		$customerPhoneNumber 	= getNumberPhone($data["customerPhoneNumber"]);
		$customerFirstName		= "new_".$data["customerFirstName"];
		$message				= $data["customerMessage"];
		$messageUrl				= $data["customerMessageUrl"];
		$messageFile			= $data["customerMessageFile"];
		$messageType			= $data["customerMessageType"];
		$dataSession 			= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID				= $dataSession["user"]->companyID;
		$branchID				= $dataSession["user"]->branchID;
		$roleID 				= $dataSession["role"]->roleID;
		$userID 				= $dataSession["user"]->userID;
		//Obtener al cliente
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
		{
			$this->core_web_conversation->createCustomer($dataSession,$customerPhoneNumber,$customerFirstName,$this->request);
			
		}
		$objCustomer			= $this->Customer_Model->get_rowByPhoneNumber($customerPhoneNumber);
		if(!$objCustomer)
			throw new \Exception ("Cliente no encontrado");
		
		//Obtener la conversacion
		$conversationIsNew			= false;
		$conversationID				= 0;
		$objCustomerConversation	= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		if(!$objCustomerConversation)
		{
			$conversationIsNew	= true;
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$objCustomer[0]->entityID);
		}
		$objCustomerConversation				= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		$lastActivityOnOld						= $objCustomerConversation[0]->lastActivityOn;
		$lastActivityOnNew						= helper_getDateTime() ;
		$objConversation 						= array();
		$objConversation["messgeConterNotRead"] = 1 ;
		$objConversation["lastMessage"] 		= $message ;
		$objConversation["lastActivityOn"] 		= $lastActivityOnNew;
		$this->Customer_Conversation_Model->update_app_posme($objCustomerConversation[0]->conversationID,$objConversation);
		
			
		//Ingresar el mensaje a la conversacion activa		
		$objTag		 								= $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		$objNotification 							= array();		
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= $objCustomer[0]->firstName;
		$objNotification["to"] 						= '';
		$objNotification["subject"] 				= $messageUrl;
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= $messageFile;
		$objNotification["title"] 					= $messageType;
		$objNotification["tagID"] 					= $objTag->tagID;
		$objNotification["createdOn"] 				= helper_getDateTime();
		$objNotification["isActive"] 				= 1;
		$objNotification["phoneFrom"] 				= $objCustomer[0]->phoneNumber;
		$objNotification["phoneTo"] 				= '';
		$objNotification["programDate"] 			= helper_getDate();
		$objNotification["programHour"] 			= '00:00';
		$objNotification["sendOn"] 					= NULL;
		$objNotification["sendEmailOn"] 			= NULL;
		$objNotification["sendWhatsappOn"] 			= NULL;
		$objNotification["addedCalendarGoogle"] 	= 0;
		$objNotification["quantityOcupation"] 		= 0;
		$objNotification["quantityDisponible"] 		= 0;
		$objNotification["googleCalendarEventID"] 	= NULL;
		$objNotification["isRead"] 					= 0;
		$objNotification["entityIDSource"] 			= 0;
		$objNotification["entityIDTarget"] 			= $objCustomer[0]->entityID;
		$notificationID 							= $this->Notification_Model->insert_app_posme($objNotification);

		//Obtener la lista de agentes a afiliar
		$objListEntityIDEmployer 					= $this->core_web_conversation->getAllEmployer($companyID,$dataSession["company"]->type,$customerPhoneNumber,$message,$conversationIsNew );				
		$this->core_web_conversation->createEmployerInConversation($dataSession,$objCustomerConversation[0]->conversationID,$objListEntityIDEmployer);
		
		//////////////////////////////////////////////
		//Notificar a los agentes afiliados
		//////////////////////////////////////////////
		$diferenceDate 							= helper_CompareDateTime($lastActivityOnOld,$lastActivityOnNew);
		log_message("error","Diferencia en fecha entre la conversacion actual y la anterior");
		log_message("error",print_r($lastActivityOnOld,true));
		log_message("error",print_r($lastActivityOnNew,true));
		log_message("error",print_r($diferenceDate,true));		
		//Han pasado almenos 5 minutos desde el utlimo mensaje
		if($diferenceDate["comparador"] == -1 && $diferenceDate["minutos"] > 5 )
		{			
			$urlSend		= base_url()."/app_cxc_conversation/edit/entityID/".$objCustomer[0]->entityID;
			$whatsappLink 	= urlencode($urlSend);
			$short 			= file_get_contents("https://is.gd/create.php?format=simple&url=$whatsappLink");
		
			$this->core_web_conversation->notificationEmployerInConversation(
			$dataSession["company"]->companyID,
			$dataSession["user"]->branchID,
			$dataSession["company"]->type,
			$objCustomerConversation[0]->conversationID,
			"📩 *Cliente:".$objCustomer[0]->firstName."* ha enviado un mensaje 

👉 Por favor, respóndelo en el siguiente enlace:
🌐 ".$short
			);		
		}
		
		
		//Resultado
		$result = [
			'success' 			=> true,
			'message'   		=> 'JSON valido',
			'entityID'			=> $objCustomer[0]->entityID,
			'converationID'		=> $objCustomerConversation[0]->conversationID,
			'notificationID'	=> $notificationID
		];	
		log_message("error",print_r($result,true));
		return $this->response->setJSON($result);
		
	}
}
?>