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
			
			

			$interestYear				= $interestYear * $objCatalogItemFrecuencia->ratio;

			//dias excluidos	select * from tb_catalog_item c where c.catalogID = 94 ;
			//					select * from tb_catalog c where c.`name` = 'CXC_NO_COBRABLES';
			//					select * from tb_catalog c where c.`name` = 'CXC_NO_COBRABLES_FERIADOS_365';
			//					select * from tb_catalog c where c.`name` = 'CXC_NO_COBRABLES_FERIADOS_366';
			
			$objCatalogItemDayExclude 	= $this->Catalog_Item_Model->get_rowByCatalogItemID($dayExcluded);//obtener el catalogo de la frecuencia de pago;			
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

			// ValidaciÃ³n bÃ¡sica
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
			$txtStartOn 						= $data['txtStartOn'] ?? null;
			$txtFinishOn 						= $data['txtFinishOn'] ?? null;
			$txtEntityIDEmployer 				= $data['txtEntityIDEmployer'] ?? null;
			$txtInboxID 						= $data['txtInboxID'] ?? null;
			$txtWorkflowStatusID				= $data['txtWorkflowStatusID'] ?? null;
			$txtSubCategoryID					= $data['txtSubCategoryID'] ?? null;
			$txtWorkflowStatusIDConversation	= $data['txtWorkflowStatusIDConversation'] ?? null;
			$txtStatusResponseID				= $data['txtStatusResponseID'] ?? null;
			$txtStatusReadID					= $data['txtStatusReadID'] ?? null;

			//Obtener datos
			$companyID 	= $dataSession["user"]->companyID;
			$branchID 	= $dataSession["user"]->branchID;
			$roleID 	= $dataSession["role"]->roleID;
			
			$data = $this->
			Customer_Conversation_Model->
			getBy_StartOn_EndOn_EmployerID_InboxID_StatusID(
				$txtStartOn,
				$txtFinishOn,
				$txtEntityIDEmployer,
				$txtWorkflowStatusIDConversation,
				$txtWorkflowStatusID,
				$txtSubCategoryID,
				$txtStatusResponseID,
				$txtStatusReadID
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
					return $m->dayNotContacted < 0;
				}));
				$conContestar 	= count(array_filter($data, function ($m) {
					return $m->dayNotContacted > 0;
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

			// ValidaciÃ³n bÃ¡sica
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
			$roleID 					= $dataSession["role"]->roleID;
			$objCustomer 				= $this->Customer_Model->get_rowByEntity($companyID,$entityID);
			$objNatural 				= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$objListEmployerAll			= $this->Employee_Model->get_rowByCompanyID($companyID);
			$objListEmployerAsigned 	= $this->Company_Component_Relation_Model->get_ConversationRegisterEmployerBy_entityIDCustomer($entityID);			
			$objListEmployer 			= [];

			foreach ($objListEmployerAll as $employer) {
				// ðŸ‘‰ condiciÃ³n de ejemplo
				$resultValidation = $this->Employee_Model->get_validatePermissionBy_EmployerID_PuedeAtenderWhatsappInCRM($employer->entityID);
				if($resultValidation)
				{
					$objListEmployer[] = $employer;
				}
			}

			// Obtener catÃ¡logos
			$objListCategoryID			= $this->core_web_catalog->getCatalogAllItem("tb_customer","categoryID",$companyID);
			$objListSubCategoryID		= $this->core_web_catalog->getCatalogAllItem("tb_customer","subCategoryID",$companyID);
			$objListWorkflowStage		= $this->core_web_workflow->getWorkflowInitStage("tb_customer","statusID",$companyID,$branchID,$roleID);
			
			return $this->response->setJSON([
				'success' 				 => true,
				'message' 				 => 'entityID no recibido',
				'objCustomer' 	  		 => $objCustomer,
				'objNatural'			 => $objNatural,
				'objListEmployer'		 => $objListEmployer,
				'objListEmployerAsigned' => $objListEmployerAsigned,
				'objListCategoryID'		 => $objListCategoryID,
				'objListSubCategoryID'	 => $objListSubCategoryID,
				'objListWorkflowStage'	 => $objListWorkflowStage
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
		$page 		= $data['page'] ?? 1;
		$limit 		= $data['limit'] ?? 50;

		// ValidaciÃ³n bÃ¡sica
		if (!$entityID) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'entityID no recibido',
				'data' 	  => [],
				'total'	  => 0
			]);
		}
		
		// Calcular offset para paginaciÃ³n
		$offset = ($page - 1) * $limit;
		
		// Obtener notificaciones con paginaciÃ³n
		$objListNotification = $this->Notification_Model->get_rowByEntityIDCustomer_Paginated($entityID, $limit, $offset);
		
		// Obtener total de mensajes (solo en la primera pÃ¡gina)
		$totalMessages = 0;
		if ($page == 1) {
			$totalMessages = $this->Notification_Model->get_countByEntityIDCustomer($entityID);
		}
		
		// Solo marcar como leÃ­do en la primera carga
		if ($page == 1) {
			$objCustomerConversation = $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($entityID);
			$objConversation = array();
			$objConversation["messgeConterNotRead"] = 0;
			$this->Customer_Conversation_Model->update_app_posme_ByCustomer($entityID, $objConversation);
			
			$objNotification["isRead"] = 1;
			$this->Notification_Model->update_app_posme_notification_byCustomerID($entityID, $objNotification);
		}
		
		return $this->response->setJSON([
			'success' => true,
			'message' => 'entityID recibido',
			'data'	  => $objListNotification,
			'total'	  => $totalMessages,
			'page'	  => $page,
			'limit'	  => $limit
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
			$data["txtTab3IsInternalMessage"] = $this->request->getPost('txtTab3IsInternalMessage') ?? '0';
		}
		
		// Extraer entityID
		$entityID 	= $data['entityID'] ?? null;
		$message	= $data['txtTab3CustomerMessage'] ?? null;
		$phone		= $data['txtTab3CustomerPhone'] ?? null;
		$isInternalMessage = ($data['txtTab3IsInternalMessage'] ?? '0') === '1';

		// ValidaciÃ³n bÃ¡sica
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
		$phone					= clearNumero($phone);
		$objCustomer			= $this->Customer_Model->get_rowBy_PhoneNumberAnd_Email_phoneFixed($phone);		
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
			$this->core_web_conversation->createEmployerInConversation($dataSession,$conversationID,$objListEntityIDEmployer,false);
			
		}
		else
		{
			log_message("error","setConversationNotification_ByCustomer >> cliente si encontrado");		
		}
	
	
		$objCustomer			= $this->Customer_Model->get_rowBy_PhoneNumberAnd_Email_phoneFixed($phone);
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
			$this->core_web_conversation->createEmployerInConversation($dataSession,$conversationID,$objListEntityIDEmployer,false);
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
		$fileMimeType 	= "";
		$fileClientName = "";
		if($file)
		{
			
			//Validar si es un archivo valido
			if (!$file->isValid()) {
				$dataResult = [			
					'success' => false,
					'message' => 'Archivo invÃ¡lido',
					'data' 	  => []
				];
				
				log_message("error",print_r($dataResult,true));			
				return $this->response->setJSON($dataResult);
			}

			log_message("error","setConversationNotification_ByCustomer >> formato del archivo ". $file->getMimeType() );	
			if (!in_array(
					$file->getMimeType(), [
					'image/png',
					'image/jpeg',
					'image/jpg',
					'image/webp',
					'application/pdf',
					'video/webm'
					]
				)
			) 
			{				
				$dataResult = [			
					'success' => false,
					'message' => 'Formato no permitido ' .$file->getMimeType()  ,
					'data' 	  => []
				];
				
				log_message("error",print_r($file->getMimeType(),true));			
				log_message("error",print_r($dataResult,true));			
				return $this->response->setJSON($dataResult);				
			}
			// Generar nombre Ãºnico
			$newName 		= $file->getRandomName();			
			$fileMimeType 	= $file->getMimeType();
			$fileClientName = $file->getClientName();	
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
			
			// Convertir WebM a OGG si es necesario
			if($fileMimeType == "video/webm")
			{
				log_message("error","setConversationNotification_ByCustomer >> convertir webm a ogg");
				$inputPath = $documentoPath."/".$newName;
				$resultado = helper_convertWebmToWhatsappAudio($inputPath, 'ogg');
				
				if($resultado['success'])
				{
					log_message("error","setConversationNotification_ByCustomer >> conversion exitosa");
					// Actualizar variables con el nuevo archivo
					$newName 		= basename($resultado['file']);
					$fileMimeType 	= 'audio/ogg';
					$fileClientName = str_replace('.webm', '.ogg', $fileClientName);
					$urlPublic 		= base_url()."/".$documentoPath."/".$newName;
					
					log_message("error","setConversationNotification_ByCustomer >> nuevo archivo: ".$urlPublic);
				}
				else
				{
					log_message("error","setConversationNotification_ByCustomer >> error en conversion: ".$resultado['message']);
					// Si falla la conversiÃ³n, continuar con el archivo original
				}
			}
		}
		
		log_message("error","setConversationNotification_ByCustomer >> guardar notificacion");		
		$objNotification 							= array();		
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= $objEmployer->firstName;
		$objNotification["to"] 						= $objCustomer[0]->firstName;		
		$objNotification["subject"]                 = $file ? $urlPublic : 'no use';
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= $isInternalMessage ? 'mensaje interno' : "no use";
		
		if(!$file)
		{
			$objNotification["title"] 				= 'text';
		}
		else 
		{
			if($fileMimeType == "application/pdf")
			$objNotification["title"] 				= 'pdf';
			else if($fileMimeType == "video/webm" || $fileMimeType == "audio/mpeg" || $fileMimeType == "audio/ogg") 
			$objNotification["title"] 				= 'audio';
			else 
			$objNotification["title"] 				= 'image';
		}
		
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
		
		
		//Enviar mensaje solo si NO es mensaje interno
		/////////////////////////////////////
		/////////////////////////////////////
		if (!$isInternalMessage) 
		{
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
				log_message("error","setConversationNotification_ByCustomer >> enviar mensaje de texto a: ".getNumberPhone(clearNumero($objCustomer[0]->phoneNumber)));
				$result = $this->core_web_whatsap->sendMessageGeneric(
					$typeCompany,
					$companyID, 
					$message, 
					getNumberPhone(clearNumero($objCustomer[0]->phoneNumber)),
					true,
					""				
				);
			}
			else
			{
				if($fileMimeType == "application/pdf")
				{
					log_message("error","setConversationNotification_ByCustomer >> enviar mensaje de pdf ".$fileClientName);						
					$result = $this->core_web_whatsap->sendMessageTypePdfGeneric(
						$typeCompany,
						$companyID, 
						$urlPublic, 
						$fileClientName,
						$message,
						getNumberPhone(clearNumero($objCustomer[0]->phoneNumber)),
						true,
						""
					);
				}
				else if($fileMimeType == "video/webm" || $fileMimeType == "audio/mpeg" || $fileMimeType == "audio/ogg")
				{
					log_message("error","setConversationNotification_ByCustomer >> enviar mensaje de audio / video ".$fileClientName);						
					$result = $this->core_web_whatsap->sendMessageTypeVideoAudioGeneric(
						$typeCompany,
						$companyID, 
						$urlPublic, 
						$fileClientName,
						$message,
						getNumberPhone(clearNumero($objCustomer[0]->phoneNumber)),
						true,
						""
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
						getNumberPhone(clearNumero($objCustomer[0]->phoneNumber)),
						true,
						""
					);
				}
			}
		}
		
		log_message("error","setConversationNotification_ByCustomer >> proceso finalizado");			
		// Si es mensaje interno, no se envÃ­a WhatsApp
		if ($isInternalMessage) {
			log_message("error","setConversationNotification_ByCustomer >> mensaje interno, no se enviÃ³ WhatsApp");
			return $this->response->setJSON([
				'success' 	=> true,
				'message' 	=> 'Mensaje interno guardado (no enviado por WhatsApp)',
				'entityID' 	=> $entityID
			]);
		}
		
		// Si no es mensaje interno, validar resultado del envÃ­o de WhatsApp		
		if($result["status"] == "error") 
		{
			return $this->response->setJSON([
				'success' 	=> false,
				'message' 	=> $result["message"],
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

		// ValidaciÃ³n bÃ¡sica
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
		
		//Validar antesa de actualizar al cliente
		//Obtener si exite otro cliente con el mismo numero de telefono
		$phoneNumberNew			= getNumberPhoneIsContact(clearNumero($data['txtTab2CustomerPhone']));
		$objCustomerOld			= $this->Customer_Model->get_rowBy_PhoneNumberAnd_Email_phoneFixed($phoneNumberNew);		
		$redirect				= false;
		if($objCustomerOld)
		{
			if($objCustomerOld[0]->entityID != $entityID)
			{
				$entityIDTemp 	= $entityID;
				$entityID 		= $objCustomerOld[0]->entityID;
				$redirect 		= true;
				//Obtener todas las conversaciones del cliente actual
				$objListConversation = $this->Customer_Conversation_Model->getByEntityIDCustomer_All($entityIDTemp);
				if($objListConversation)
				{
					
					//Actualiar las conversaciones con el cliente correcto
					$conversationIDs 			= array_column($objListConversation, 'conversationID');					
					$dataNew 					= array();
					$dataNew["entityIDSource"]	= $entityID;
					$dataNew["isActive"]		= 0;
					$dataNew["statusID"]		= 0;
					$this->Customer_Conversation_Model->update_app_posme_ByConversationIDs($conversationIDs,$dataNew);
					
					
					//Actualizar todas las notificaciones por la persona correcta
					$dataNew 					= array();
					$dataNew["entityIDTarget"]	= $entityID;
					$this->Notification_Model->update_app_posme_notification_byCustomerID($entityIDTemp,$dataNew);
				}
				
				//Agregar al nuevo identificador del cliente
				$objEmail = $this->Entity_Email_Model->get_rowByEmail($entityID,$phoneNumberNew);
				if(!$objEmail)
				{
					$dataNew 				=  array();
					$dataNew["companyID"] 	=  $dataSession["user"]->companyID;
					$dataNew["branchID"] 	=  $dataSession["user"]->branchID;
					$dataNew["entityID"] 	=  $entityID;
					$dataNew["isPrimary"] 	=  0;
					$dataNew["email"] 		=  $phoneNumberNew;
					$entityEmailID 	= $this->Entity_Email_Model->insert_app_posme($dataNew);
				}
				
				//Obtener todos los email del cliente anterior e insertarlos en el nuevo cliente
				$objListEmail = $this->Entity_Email_Model->get_rowByEntity($dataSession["user"]->companyID,$dataSession["user"]->branchID,$entityIDTemp);
				if($objListEmail)
				{
					foreach($objListEmail as $objEmailInsert)
					{
						$dataNew 				=  array();
						$dataNew["companyID"] 	=  $dataSession["user"]->companyID;
						$dataNew["branchID"] 	=  $dataSession["user"]->branchID;
						$dataNew["entityID"] 	=  $entityID;
						$dataNew["isPrimary"] 	=  0;
						$dataNew["email"] 		=  $objEmailInsert->email;
						$entityEmailID 			=  $this->Entity_Email_Model->insert_app_posme($dataNew);
					}
				}
				
				//Actualiar telefono
				$dataNew 				= array();
				$dataNew["phoneNumber"] = "";
				$this->Customer_Model->update_app_posme($dataSession["user"]->companyID,$dataSession["user"]->branchID,$entityIDTemp,$dataNew);
				
				//Eliminar telefonos
				$this->Entity_Phone_Model->deleteByEntity($dataSession["user"]->companyID,$dataSession["user"]->branchID,$entityIDTemp);
				
				//Eliminar email
				$this->Entity_Email_Model->deleteByEntity($dataSession["user"]->companyID,$dataSession["user"]->branchID,$entityIDTemp);
				
			}
		}
		
		
		//Actualiar Cliente
		$companyID					= $dataSession["user"]->companyID;		
		$branchID					= $dataSession["user"]->branchID;
		$objCustomer 				= array();
		$objCustomer["phoneNumber"] = $phoneNumberNew ?? '';
		$objCustomer["categoryID"]  = $data['txtTab2CategoryID'] ?? '';
		$objCustomer["statusID"]    = $data['txtTab2StatusID'] ?? '';
		$objCustomer["subCategoryID"] = $data['txtTab2SubCategoryID'] ?? '';
		$objCustomer["budget"]      = $data['txtTab2Budget'] ?? '';
		$objCustomer["location"]    = $data['txtTab2Location'] ?? '';
		$objCustomer["reference1"]  = $data['txtTab2Reference1'] ?? '';
		$objCustomer["allowWhatsappCollection"] = ($data['txtTab2AllowWhatsappCollection'] ?? false) ? 1 : 0;
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
		$this->core_web_conversation->createEmployerInConversation($dataSession,$objCustomerConversation[0]->conversationID,$objListEntityIDEmployer,false);
		
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
			'success' 	=> true,
			'message' 	=> 'entityID recibido',
			'redirect' 	=> $redirect,
			'entityID' 	=> $entityID
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
		
	}
	public function WebHookInsertAndUpdateElementLiveConnectReceiptWhatsappWebHook()
	{
		
	}
	public function WebHookReceiptMessage_Whatsapp_Wati()
	{
		
	}
	public function WebHookReceiptMessage_Whatsapp_EvolutionApi_posMe()
	{
		
		
	}
	public function WebHookReceiptMessage_Whatsapp_VonageApi_posMe()
	{
		
		
	}
	
	public function WebHookReceiptMessage_Whatsapp_AppzApiIo_posMe()
	{	
	}

	public function WebHookReceiptMessage_Whatsapp_Ultramsg_posMe()
	{	
		// JSON crudo (string completo)
		$host 		= $this->request->getServer('HTTP_HOST');		
		log_message('error', '[Ultramsg] ====== INICIO WebHookReceiptMessage_Whatsapp_Ultramsg_posMe ======');	
		$input	 	= $this->request->getJSON(true); // true = array		
		log_message("error","[Ultramsg] INPUT RAW: " . print_r($input,true));

		//Solo se permiten mensajes recibidos
		if(($input["event_type"] ?? '') != "message_received")
		{
			log_message('error', '[Ultramsg] DESCARTADO: event_type=' . ($input["event_type"] ?? 'null') . ' (no es message_received)');
			return;
		}

		//Autenticar sesion por defecto para obtener companyID
		$dataSession = $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID   = $dataSession["user"]->companyID;
		log_message('error', '[Ultramsg] companyID: ' . $companyID);

		//Validar parametro WebHookWapi2
		$paramWebHookWapi2 = $this->core_web_parameter->getParameter("WebHookWapi2", $companyID);
		if(!$paramWebHookWapi2 || strtolower($paramWebHookWapi2->value) != "true")
		{
			log_message('error', '[Ultramsg] DESCARTADO: Parametro WebHookWapi2 no es true. value=' . ($paramWebHookWapi2->value ?? 'null'));
			return;
		}
		log_message('error', '[Ultramsg] Parametro WebHookWapi2=true, continuando...');

		//Mapear datos de Ultramsg al formato generico
		$genericData = [];
		$genericData["event"]                        = "message";
		$genericData["data"]["from"]                 = $input["data"]["from"] ?? '';
		$genericData["data"]["body"]                 = $input["data"]["body"] ?? '';
		$genericData["data"]["type"]                 = $input["data"]["type"] ?? 'chat';
		$genericData["data"]["contact"]["pushname"]  = $input["data"]["pushname"] ?? '';
		$genericData["data"]["quotedMsg"]["body"]    = '';

		//Tipo de mensaje segun Ultramsg
		$type = $genericData["data"]["type"];
		if($type == "document" || $type == "image" || $type == "ptt" || $type == "audio")
		{
			//Ultramsg envia media como URL, hay que descargar y convertir a base64
			$mediaUrl = $input["data"]["media"] ?? '';
			log_message('error', '[Ultramsg] Media URL: ' . $mediaUrl);

			if($mediaUrl != '')
			{
				$mediaContent = @file_get_contents($mediaUrl);
				if($mediaContent !== false)
				{
					$base64Media = base64_encode($mediaContent);

					//Determinar mimetype y filename
					$mimetype = 'application/octet-stream';
					$filename = $input["data"]["filename"] ?? '';
					if($type == "image")
					{
						$mimetype = 'image/jpeg';
						$filename = $filename ?: 'image_' . uniqid() . '.jpg';
					}
					elseif($type == "document")
					{
						$mimetype = 'application/pdf';
						$filename = $filename ?: ($input["data"]["body"] ?? 'document_' . uniqid() . '.pdf');
					}
					elseif($type == "ptt" || $type == "audio")
					{
						$mimetype = 'audio/ogg';
						$filename = $filename ?: 'audio_' . uniqid() . '.ogg';
					}

					$genericData["data"]["media"] = [
						"mimetype" => $mimetype,
						"data"     => $base64Media,
						"filename" => $filename
					];
					log_message('error', '[Ultramsg] Media descargada y convertida a base64, mimetype=' . $mimetype . ', filename=' . $filename);
				}
				else
				{
					log_message('error', '[Ultramsg] ERROR: No se pudo descargar media desde URL: ' . $mediaUrl);
				}
			}
		}

		log_message('error', '[Ultramsg] genericData mapeado: ' . print_r($genericData, true));

		//Llamar a la funcion generica
		return $this->WebHookReceiptMessage_Whatsapp_Generic_posMe($genericData);
	}


	//https://posme.nl/v4posme/comercial_luciana/public/app_cxc_api/WebHookReceiptMessage_Whatsapp_Wapi2_posMe
	//example: webhook wapi2
	public function WebHookReceiptMessage_Whatsapp_Wapi2_posMe()
	{
		// JSON crudo (string completo)
		log_message('error', '[Wapi2] ====== INICIO WebHookReceiptMessage_Whatsapp_Wapi2_posMe ======');	
		$input	 	= $this->request->getJSON(true); // true = array
		log_message('error', '[Wapi2] INPUT RAW: ' . print_r($input, true));
		
		//Solo se permiten mensajes recibidos
		if($input["event"] != "message")
		{
			log_message('error', '[Wapi2] DESCARTADO: event=' . ($input["event"] ?? 'null') . ' (no es message)');
			return;
		}
		
		//No se permite mensaje de broadcast
		if(str_contains($input["data"]["from"], 'broadcast'))
		{
			log_message('error', '[Wapi2] DESCARTADO: from contiene broadcast -> ' . $input["data"]["from"]);
			return;
		}
		
		//No se permite mensaje de template
		if($input["data"]["type"] == "notification_template")
		{
			log_message('error', '[Wapi2] DESCARTADO: type=notification_template');
			return;
		}
		
		//No se permiten mensaje de cifrado de extremo a extremo
		if( $input["data"]["type"] == "e2e_notification" )
		{
			log_message('error', '[Wapi2] DESCARTADO: type=e2e_notification');
			return;
		}
		
		log_message('error', '[Wapi2] Evento aceptado: event=' . $input["event"] . ', type=' . ($input["data"]["type"] ?? 'null') . ', from=' . ($input["data"]["from"] ?? 'null'));
		
		if (!$input) {
			$result = [
				'success' => false,
				'message' => 'JSON inválido input'
			];
			log_message("error","[Wapi2] ERROR: input vacío o nulo");
			log_message("error",print_r($result,true));
			return $this->response->setJSON($result)->setStatusCode(200);
		}

		//Autenticar sesion por defecto para obtener companyID
		$dataSession = $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID   = $dataSession["user"]->companyID;
		log_message('error', '[Wapi2] companyID: ' . $companyID);

		//Validar parametro WebHookWapi2
		$paramWebHookWapi2 = $this->core_web_parameter->getParameter("WebHookWapi2", $companyID);
		if(!$paramWebHookWapi2 || strtolower($paramWebHookWapi2->value) != "true")
		{
			log_message('error', '[Wapi2] DESCARTADO: Parametro WebHookWapi2 no es true. value=' . ($paramWebHookWapi2->value ?? 'null'));
			return;
		}
		log_message('error', '[Wapi2] Parametro WebHookWapi2=true, continuando...');

		//El input ya viene en formato generico compatible, pasar directamente
		log_message('error', '[Wapi2] >> Llamando a WebHookReceiptMessage_Whatsapp_Generic_posMe con input directo');
		return $this->WebHookReceiptMessage_Whatsapp_Generic_posMe($input);
	}


	/**
	 * Funcion generica para procesar mensajes de WhatsApp
	 * Recibe un array asociativo con la estructura estandarizada:
	 * $inputData["event"] = "message"
	 * $inputData["data"]["from"] = "50587125827@c.us"
	 * $inputData["data"]["body"] = "texto del mensaje"
	 * $inputData["data"]["type"] = "chat|image|document|ptt"
	 * $inputData["data"]["contact"]["pushname"] = "Nombre"
	 * $inputData["data"]["quotedMsg"]["body"] = "mensaje referenciado"
	 * $inputData["data"]["media"]["mimetype"] = "image/jpeg"
	 * $inputData["data"]["media"]["data"] = "base64..."
	 * $inputData["data"]["media"]["filename"] = "archivo.pdf"
	 */
	public function WebHookReceiptMessage_Whatsapp_Generic_posMe($inputData)
	{
		log_message('error', '[Generic] ====== INICIO WebHookReceiptMessage_Whatsapp_Generic_posMe ======');
		log_message('error', '[Generic] INPUT DATA: ' . print_r($inputData, true));

		// Extraer datos básicos
		log_message("error","[Generic] >> Extrayendo datos del mensaje...");
		log_message('error', '[Generic] inputData[data][from]: ' . ($inputData["data"]['from'] ?? 'null'));
		log_message('error', '[Generic] inputData[data][body]: ' . ($inputData["data"]['body'] ?? 'null'));
		log_message('error', '[Generic] inputData[data][type]: ' . ($inputData["data"]['type'] ?? 'null'));
		log_message('error', '[Generic] inputData[data][contact][pushname]: ' . ($inputData["data"]['contact']['pushname'] ?? 'null'));

		$data["customerPhoneNumber"] 			= getNumberPhoneIsContact(clearNumero(($inputData["data"]['from'] ?? '')));
		$data["customerFirstName"]	 			= $inputData["data"]['contact']['pushname'] ?? '';
		$data["customerMessage"]	 			= $inputData["data"]['body'] ?? '';
		$data["customerMessage"]	 			= helper_convertirLinkAHtml($data["customerMessage"],"ver link");
		$data["customerMessageType"]			= $inputData["data"]['type'] ?? '';
		$data["customerMessageFile"]			= "";
		$data["customerMessageUrl"]				= "";
		$data["customerMessageReference"]		= $inputData["data"]['quotedMsg']["body"] ?? '';

		log_message('error', '[Generic] >> Datos extraídos:');
		log_message('error', '[Generic]    customerPhoneNumber: ' . $data["customerPhoneNumber"]);
		log_message('error', '[Generic]    customerFirstName: ' . $data["customerFirstName"]);
		log_message('error', '[Generic]    customerMessage: ' . $data["customerMessage"]);
		log_message('error', '[Generic]    customerMessageType: ' . $data["customerMessageType"]);
		log_message('error', '[Generic]    customerMessageReference: ' . $data["customerMessageReference"]);

		//Tipo de mensaje
		if($data["customerMessageType"] == "chat")
		{
			$data["customerMessageType"] = "text";
			log_message('error', '[Generic] Tipo mensaje convertido: chat -> text');
		}
		
		if($data["customerMessageType"] == "image")
		{
			$data["customerMessageType"] = "image";
			log_message('error', '[Generic] Tipo mensaje: image');
		}
		
		if($data["customerMessageType"] == "ptt")
		{
			$data["customerMessageType"] = "audio";
			log_message('error', '[Generic] Tipo mensaje convertido: ptt -> audio');
		}

		//Buscar al cliente antes de guardar recursos multimedia
		$customerPhoneNumber 	= $data["customerPhoneNumber"];
		$customerFirstName		= "posMeConnect_".$data["customerFirstName"];
		
		log_message('error', '[Generic] >> Autenticando sesión con usuario por defecto (pre-multimedia)...');
		$dataSession 			= $this->core_web_authentication->get_UserBy_PasswordAndNickname(APP_USERDEFAULT_VALUE, APP_PASSWORDEFAULT_VALUE);
		$companyID				= $dataSession["user"]->companyID;
		$branchID				= $dataSession["user"]->branchID;
		$roleID 				= $dataSession["role"]->roleID;
		$userID 				= $dataSession["user"]->userID;
		log_message('error', '[Generic]    companyID: ' . $companyID);
		log_message('error', '[Generic]    branchID: ' . $branchID);
		log_message('error', '[Generic]    roleID: ' . $roleID);
		log_message('error', '[Generic]    userID: ' . $userID);
		log_message('error', '[Generic]    customerPhoneNumber: ' . $customerPhoneNumber);
		log_message('error', '[Generic]    customerFirstName: ' . $customerFirstName);
		
		//Obtener al cliente antes de procesar multimedia
		log_message('error', '[Generic] >> Buscando cliente por telefono (pre-multimedia): ' . $customerPhoneNumber);
		$objCustomer			= $this->Customer_Model->get_rowBy_PhoneNumberAnd_Email_phoneFixed($customerPhoneNumber);
		if(!$objCustomer)
		{
			log_message('error', '[Generic]    Cliente NO encontrado, creando nuevo cliente...');
			$this->core_web_conversation->createCustomer($dataSession,$customerPhoneNumber,$customerFirstName,$this->request);
			log_message('error', '[Generic]    Cliente creado con phone: ' . $customerPhoneNumber . ', nombre: ' . $customerFirstName);
		}
		$objCustomer			= $this->Customer_Model->get_rowBy_PhoneNumberAnd_Email_phoneFixed($customerPhoneNumber);
		if(!$objCustomer)
		{
			log_message('error', '[Generic] ERROR CRITICO: Cliente no encontrado despues de crearlo, phone: ' . $customerPhoneNumber);
			throw new \Exception ("Cliente no encontrado");
		}
		log_message('error', '[Generic]    Cliente encontrado: entityID=' . $objCustomer[0]->entityID . ', firstName=' . $objCustomer[0]->firstName . ', phoneNumber=' . $objCustomer[0]->phoneNumber.', categoryID=' . $objCustomer[0]->categoryID.', subCategoryID=' . $objCustomer[0]->subCategoryID );

		//Verificar si la categoría del cliente es la de exclusión (CXC_CATEGORY_POSMECONNECT_MESSAGE_PERSON)
		$paramCategoryPosmeConnect = $this->core_web_parameter->getParameter("CXC_CATEGORY_POSMECONNECT_MESSAGE_PERSON", $companyID);
		if($paramCategoryPosmeConnect && $paramCategoryPosmeConnect->value)
		{
			if($objCustomer[0]->subCategoryID == $paramCategoryPosmeConnect->value)
			{
				log_message('error', '[Generic] DESCARTADO: Cliente con subCategoryID=' . $objCustomer[0]->subCategoryID . ' coincide con CXC_CATEGORY_POSMECONNECT_MESSAGE_PERSON=' . $paramCategoryPosmeConnect->value . '. No se registra.');
				return $this->response->setJSON([
					'success' => false,
					'message' => 'Cliente excluido por categoría CXC_CATEGORY_POSMECONNECT_MESSAGE_PERSON'
				])->setStatusCode(200);
			}
		}

		//Guardar el recurso o imagen o video o pdf
		if(
			$data["customerMessageType"] == "image" || 
			$data["customerMessageType"] == "pdf"   || 
			$data["customerMessageType"] == "audio" ||
			$data["customerMessageType"] == "document" 
		)
		{
			log_message('error', '[Generic] >> Procesando recurso multimedia, tipo: ' . $data["customerMessageType"]);
			//Guardar la imagen
			$mediaTipe 	= $inputData['data']['media']['mimetype'];
			$base64 	= $inputData['data']['media']['data'];
			$filename 	= $inputData['data']['media']['filename'];
			log_message('error', '[Generic]    mediaMimetype: ' . $mediaTipe);
			log_message('error', '[Generic]    mediaFilename: ' . $filename);
			log_message('error', '[Generic]    base64 length: ' . strlen($base64));
			
			//generar nombre
			$extension = explode('/', $mediaTipe)[1];
			$filename  = uniqid('file_') . '.' . $extension;
			log_message('error', '[Generic]    filename generado: ' . $filename);

			// Quitar encabezado Base64 si existe
			if (str_contains($base64, ',')) {
				$base64 = explode(',', $base64)[1];
			}

			// Decodificar
			$imageBinary = base64_decode($base64);
			log_message('error', '[Generic]    imageBinary size (bytes): ' . strlen($imageBinary));

			//Obtener el componente
			$objComponentCustomerConversation	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer_conversation");
			if(!$objComponentCustomerConversation)
			throw new \Exception("EL COMPONENTE 'tb_customer_conversation' NO EXISTE...");
			log_message('error', '[Generic]    componentID: ' . $objComponentCustomerConversation->componentID);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$phoneCustomer = $data["customerPhoneNumber"];
			$documentoPath = PATH_FILE_OF_APP."/company_".APP_COMPANY."/component_".$objComponentCustomerConversation->componentID."/component_item_".$phoneCustomer;
			log_message('error', '[Generic]    documentoPath: ' . $documentoPath);
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0777, true);
				log_message('error', '[Generic]    Carpeta creada: ' . $documentoPath);
			}
			
			// Guardar archivo
			file_put_contents($documentoPath ."/". $filename, $imageBinary);
			log_message('error', '[Generic]    Archivo guardado: ' . $documentoPath . '/' . $filename);
			
			//Obtener la url
			$url 							= base_url()."/resource/file_company/company_2/component_".$objComponentCustomerConversation->componentID."/component_item_".$phoneCustomer."/".$filename;
			$data["customerMessage"]	 	= $inputData['data']['body'] ?? '';
			$data["customerMessageUrl"]		= $url;
			$data["customerMessageFile"]	= $url;
			log_message("error","[Generic]    URL publica: " . $url);
			log_message("error","[Generic]    paquete procesado: ".print_r($data,true));
		}
		
		//Procesar la referencia al mensaje 
		if($data["customerMessageReference"] != "")
		{
			$data["customerMessage"] = $data["customerMessage"]." <div class='referencia'> ".$data["customerMessageReference"]." </div>";
			log_message('error', '[Generic] Mensaje con referencia agregada');
		}
		
		$message				= $data["customerMessage"];
		$messageUrl				= $data["customerMessageUrl"];
		$messageFile			= $data["customerMessageFile"];
		$messageType			= $data["customerMessageType"];
		
		log_message('error', '[Generic]    message: ' . $message);
		log_message('error', '[Generic]    messageType: ' . $messageType);
		
		//Obtener la conversacion
		log_message('error', '[Generic] >> Buscando conversación para entityID: ' . $objCustomer[0]->entityID);
		$conversationIsNew			= false;
		$conversationID				= 0;
		$lastActivityOnOld			= helper_getDateTime();
		$lastActivityOnNew			= helper_getDateTime();
		
		$objCustomerConversation	= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		if(!$objCustomerConversation)
		{
			$conversationIsNew	= true;
			log_message('error', '[Generic]    Conversación NO existe, creando nueva...');
			$conversationID 	= $this->core_web_conversation->createConversation($dataSession,$objCustomer[0]->entityID);
			$lastActivityOnOld	= '1900-01-01 00:00:00';
			$lastActivityOnNew	= helper_getDateTime();
			log_message('error', '[Generic]    Conversación creada: conversationID=' . $conversationID);
		}
		else
		{
			$lastActivityOnOld		= $objCustomerConversation[0]->lastActivityOn;
			$lastActivityOnNew		= helper_getDateTime();
			log_message('error', '[Generic]    Conversación existente: conversationID=' . $objCustomerConversation[0]->conversationID . ', lastActivityOn=' . $lastActivityOnOld);
		}
		$objCustomerConversation				= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($objCustomer[0]->entityID);
		log_message('error', '[Generic]    conversationIsNew=' . ($conversationIsNew ? 'SI' : 'NO') . ', conversationID=' . $objCustomerConversation[0]->conversationID);
		log_message('error', '[Generic]    lastActivityOnOld=' . $lastActivityOnOld . ', lastActivityOnNew=' . $lastActivityOnNew);
		
		$objConversation 						= array();
		$objConversation["messgeConterNotRead"] = 1 ;
		$objConversation["lastMessage"] 		= $message ;
		$objConversation["lastActivityOn"] 		= $lastActivityOnNew;
		$objConversation["messageReceiptOn"] 	= $lastActivityOnNew;
		log_message('error', '[Generic] >> Actualizando conversación ID=' . $objCustomerConversation[0]->conversationID);
		$this->Customer_Conversation_Model->update_app_posme($objCustomerConversation[0]->conversationID,$objConversation);
		
		//Ingresar el mensaje a la conversacion activa
		log_message('error', '[Generic] >> Insertando notificación en conversación...');
		$objTag		 								= $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		log_message('error', '[Generic]    tagID: ' . ($objTag ? $objTag->tagID : 'NULL'));
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
		log_message('error', '[Generic]    Notificación insertada: notificationID=' . $notificationID);

		//////////////////////////////////////////////
		//Obtener la lista de agentes a afiliar
		//////////////////////////////////////////////
		log_message('error', '[Generic] >> Obteniendo lista de agentes (getAllEmployer)...');
		log_message('error', '[Generic]    params: companyID=' . $companyID . ', type=' . $dataSession["company"]->type . ', phone=' . $customerPhoneNumber . ', conversationIsNew=' . ($conversationIsNew ? 'SI' : 'NO'));
		$objListEntityIDEmployer 					= $this->core_web_conversation->getAllEmployer($companyID,$dataSession["company"]->type,$customerPhoneNumber,$message,$conversationIsNew );
		log_message('error', '[Generic]    getAllEmployer result: ' . print_r($objListEntityIDEmployer, true));
		
		log_message('error', '[Generic] >> Creando empleadores en conversación (createEmployerInConversation)...');
		$this->core_web_conversation->createEmployerInConversation($dataSession,$objCustomerConversation[0]->conversationID,$objListEntityIDEmployer["employerList"],$conversationIsNew);
		log_message('error', '[Generic]    Empleadores asociados a conversationID=' . $objCustomerConversation[0]->conversationID);
		
		//Categorizar al cliente
		log_message('error', '[Generic] >> Categorizando cliente (categorizeCustomer)...');
		log_message('error', '[Generic]    params: type=' . $dataSession["company"]->type . ', conversationID=' . $objCustomerConversation[0]->conversationID . ', entityID=' . $objCustomer[0]->entityID . ', conversationIsNew=' . ($conversationIsNew ? 'SI' : 'NO') . ', typeAsigned=' . ($objListEntityIDEmployer["typeAsigned"] ?? 'null'));
		$this->core_web_conversation->categorizeCustomer(
			$dataSession,
			$dataSession["company"]->type,
			$objCustomerConversation[0]->conversationID,
			$objCustomer[0]->entityID,
			$message,
			$objListEntityIDEmployer["employerList"],
			$conversationIsNew,
			$objListEntityIDEmployer["typeAsigned"]
		);
		log_message('error', '[Generic]    categorizeCustomer ejecutado OK');
		
		//////////////////////////////////////////////
		//Notificar a los agentes afiliados
		//////////////////////////////////////////////
		log_message('error', '[Generic] >> Evaluando notificación a agentes...');
		$diferenceDate 							= helper_CompareDateTime($lastActivityOnOld,$lastActivityOnNew);
		log_message("error","[Generic]    lastActivityOnOld: " . $lastActivityOnOld);
		log_message("error","[Generic]    lastActivityOnNew: " . $lastActivityOnNew);
		log_message("error","[Generic]    diferenceDate: " . print_r($diferenceDate, true));
		log_message("error","[Generic]    companyType: " . $dataSession["company"]->type);
		
		if( $dataSession["company"]->type == "luciaralstate" )
		{
			log_message('error', '[Generic]    Tipo: luciaralstate, evaluando condiciones...');
			log_message('error', '[Generic]    comparador=' . $diferenceDate["comparador"] . ', segundos=' . $diferenceDate["segundos"] . ', allowWhatsappCollection=' . $objCustomer[0]->allowWhatsappCollection);
			//Han pasado almenos 10 segundos desde el utlimo mensaje
			//Y el numero no esta bloqueado
			if(
				$diferenceDate["comparador"] == "-1" && 
				((int)$diferenceDate["segundos"]) >=  10 &&  
				$objCustomer[0]->allowWhatsappCollection == 0
			)
			{			
				log_message("error","[Generic] >> Enviando notificación a colaboradores asignados (luciaralstate)...");
				$urlSend		= base_url()."/app_cxc_conversation/edit/entityID/".$objCustomer[0]->entityID;
				$whatsappLink 	= urlencode($urlSend);
				log_message('error', '[Generic]    urlSend: ' . $urlSend);
				$short 			= file_get_contents("https://is.gd/create.php?format=simple&url=$whatsappLink");
				log_message('error', '[Generic]    shortUrl: ' . $short);
			
				$messagePreview = mb_strlen($message) > 80 ? mb_substr($message, 0, 80) . '...' : $message;
				$this->core_web_conversation->notificationEmployerInConversation(
					$dataSession["company"]->companyID,
					$dataSession["user"]->branchID,
					$dataSession["company"]->type,
					$objCustomerConversation[0]->conversationID,
					"📩 *Cliente:".$objCustomer[0]->firstName."* ('".$objCustomer[0]->entityID."') ha enviado un mensaje:\n💬 _\"".$messagePreview."\"_\n👉 Por favor, respóndelo en el siguiente enlace: 🌐 ".$short
				);
				log_message('error', '[Generic]    notificationEmployerInConversation ejecutado OK');
			}
			else
			{
				log_message('error', '[Generic]    Condiciones NO cumplidas para notificar (luciaralstate)');
			}
		}
		else 
		{
			log_message('error', '[Generic]    Tipo: ' . $dataSession["company"]->type . ', evaluando condiciones...');
			log_message('error', '[Generic]    comparador=' . $diferenceDate["comparador"] . ', segundos=' . $diferenceDate["segundos"]);
			//Han pasado almenos 10 segundos desde el utlimo mensaje
			if($diferenceDate["comparador"] == "-1" && ((int)$diferenceDate["segundos"]) >=  10 )
			{			
				log_message("error","[Generic] >> Enviando notificación a colaboradores asignados...");
				$urlSend		= base_url()."/app_cxc_conversation/edit/entityID/".$objCustomer[0]->entityID;
				$whatsappLink 	= urlencode($urlSend);
				log_message('error', '[Generic]    urlSend: ' . $urlSend);				
				$short 			= $whatsappLink;
				log_message('error', '[Generic]    shortUrl: ' . $short);

				$messagePreview = mb_strlen($message) > 80 ? mb_substr($message, 0, 80) . '...' : $message;
				$this->core_web_conversation->notificationEmployerInConversation(
					$dataSession["company"]->companyID,
					$dataSession["user"]->branchID,
					$dataSession["company"]->type,
					$objCustomerConversation[0]->conversationID,
					"📩 *Cliente:". $objCustomer[0]->firstName."* ('".$objCustomer[0]->entityID."') ha enviado un mensaje:\n💬 _\"".$messagePreview."\"_\n👉 Por favor, respóndelo en el siguiente enlace: 🌐 ".$short
				);
				log_message('error', '[Generic]    notificationEmployerInConversation ejecutado OK');
			}
			else
			{
				log_message('error', '[Generic]    Condiciones NO cumplidas para notificar');
			}
		}
		
		//Resultado
		$result = [
			'success' 			=> true,
			'message'   		=> 'JSON valido',
			'entityID'			=> $objCustomer[0]->entityID,
			'converationID'		=> $objCustomerConversation[0]->conversationID,
			'notificationID'	=> $notificationID
		];	
		log_message("error","[Generic] ====== FIN WebHookReceiptMessage_Whatsapp_Generic_posMe ======");
		log_message("error","[Generic] RESULTADO: " . print_r($result,true));
		return $this->response->setJSON($result);
	}
}
?>
