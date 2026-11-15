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
			$numberPay					= trim((string)/*inicio get post*/ $this->request->getPost("numberPay"));
			//Defensa: plazo con exactamente una coma y ningun punto (ej. "50,666") => la coma es el separador decimal
			if(substr_count($numberPay,",") == 1 && strpos($numberPay,".") === false)
				$numberPay				= str_replace(",",".",$numberPay);
			else
				$numberPay				= helper_StringToNumber($numberPay);

			if(!is_numeric($numberPay) || (float)$numberPay <= 0)
			throw new \Exception("EL NUMERO DE PAGOS DEBE SER MAYOR A 0");

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
				date_format(date_create(helper_getDate()),"Y-m-d"),	/*fecha del credito*/
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
			$data	= $this->addConversationAI_Status($data);
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

	/**
	 * Agrega aiStatus y aiIntent (agente de IA) a las filas de la bandeja, en una sola consulta.
	 * @param array $rows filas de la vista (arreglos u objetos con entityID)
	 * @return array filas con aiStatus / aiIntent (null si la conversacion no tiene IA)
	 */
	private function addConversationAI_Status($rows)
	{
		if(!is_array($rows) || count($rows) == 0)
			return $rows;

		$entityIDs = [];
		foreach($rows as $row)
		{
			$entityID = is_array($row) ? ($row["entityID"] ?? 0) : ($row->entityID ?? 0);
			if((int)$entityID > 0)
				$entityIDs[] = (int)$entityID;
		}
		if(count($entityIDs) == 0)
			return $rows;

		$mapAI = [];
		foreach($this->Customer_Conversation_Model->get_rowAIStatusByEntityIDs(array_values(array_unique($entityIDs))) as $objAI)
			$mapAI[(int)$objAI->entityIDSource] = $objAI;

		foreach($rows as $i => $row)
		{
			$entityID 	= (int)(is_array($row) ? ($row["entityID"] ?? 0) : ($row->entityID ?? 0));
			$aiStatus 	= isset($mapAI[$entityID]) ? $mapAI[$entityID]->aiStatus : null;
			$aiIntent 	= isset($mapAI[$entityID]) ? $mapAI[$entityID]->aiIntent : null;
			if(is_array($row))
			{
				$rows[$i]["aiStatus"] = $aiStatus;
				$rows[$i]["aiIntent"] = $aiIntent;
			}
			else
			{
				$rows[$i]->aiStatus = $aiStatus;
				$rows[$i]->aiIntent = $aiIntent;
			}
		}
		return $rows;
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

		//Estado del agente de IA de la conversacion abierta (tarjeta "Agente IA")
		$conversationAI = null;
		if ($page == 1) {
			$conversationAI = $this->getConversationAI_Info($entityID);
		}

		return $this->response->setJSON([
			'success' 			=> true,
			'message' 			=> 'entityID recibido',
			'data'	  			=> $objListNotification,
			'total'	  			=> $totalMessages,
			'page'	  			=> $page,
			'limit'	  			=> $limit,
			'conversationAI'	=> $conversationAI
		]);
	}

	/**
	 * Estado del agente de IA de la conversacion abierta de un cliente, para la pantalla.
	 * @param int $entityID cliente
	 * @return array|null null si la conversacion no tiene agente de IA (aiStatus NULL)
	 */
	private function getConversationAI_Info($entityID)
	{
		$objConversation = $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($entityID);
		if (!$objConversation || $objConversation[0]->aiStatus === null || $objConversation[0]->aiStatus === '') {
			return null;
		}

		$aiData = json_decode((string)$objConversation[0]->aiData, true);
		$aiData = is_array($aiData) ? $aiData : [];
		return [
			'conversationID' 	=> (int)$objConversation[0]->conversationID,
			'aiStatus' 			=> (string)$objConversation[0]->aiStatus,
			'aiIntent' 			=> (string)$objConversation[0]->aiIntent,
			'aiHandoffOn' 		=> $objConversation[0]->aiHandoffOn,
			'datos' 			=> is_array($aiData['datos'] ?? null) ? $aiData['datos'] : [],
			'adjuntos' 			=> is_array($aiData['adjuntos'] ?? null) ? $aiData['adjuntos'] : [],
			'verificado' 		=> !empty($aiData['verificado']),
			'motivo' 			=> (string)($aiData['motivo'] ?? ''),
			'resumen' 			=> (string)($aiData['resumen'] ?? ''),
			'turnos' 			=> (int)($aiData['turnos'] ?? 0)
		];
	}
	/**
	 * Envía un mensaje (texto o archivo) al cliente de la conversación.
	 * Envuelve el proceso para que cualquier excepción regrese un JSON con
	 * success = false y el mensaje, en lugar de la respuesta genérica de error.
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	function setConversationNotification_ByCustomer()
	{
		try
		{
			return $this->setConversationNotification_ByCustomer_Process();
		}
		catch(\Throwable $ex)
		{
			log_message("error","setConversationNotification_ByCustomer >> ".$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON([
				'success' 	=> false,
				'message' 	=> 'No se pudo enviar el mensaje: '.$ex->getMessage(),
				'data' 		=> []
			]);
		}
	}

	/**
	 * Proceso de envío del mensaje al cliente (ver setConversationNotification_ByCustomer).
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	private function setConversationNotification_ByCustomer_Process()
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
		//Agente de IA: si un humano contesta al cliente (no nota interna), la IA deja de responder
		$aiStatusActual 						= (string)($objCustomerConversation[0]->aiStatus ?? '');
		if(!$isInternalMessage && ($aiStatusActual == "activa" || $aiStatusActual == "derivada"))
		$objConversation["aiStatus"] 			= "humano";
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
		if(!is_array($result) || ($result["status"] ?? "") == "error")
		{
			return $this->response->setJSON([
				'success' 	=> false,
				'message' 	=> (is_array($result) && !empty($result["message"])) ? $result["message"] : 'No se pudo enviar el mensaje',
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
			//Agente de IA: cerrar en la etapa "ATENDIDA POR IA" marca la conversacion como atendida
			$aiStageAttendedID 						= (int)$this->aiAgentParameter("WHATSAPP_AI_AGENT_STAGE_ATTENDED_ID",$companyID,"0");
			if($aiStageAttendedID > 0 && (int)$workflowStageID == $aiStageAttendedID)
			$objConversationNew["aiStatus"] 		= "atendida";
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
				//La etapa "ATENDIDA POR IA" no se usa al cerrar todas
				$aiStageAttendedID = (int)$this->aiAgentParameter("WHATSAPP_AI_AGENT_STAGE_ATTENDED_ID",$companyID,"0");
				foreach($objListConversation as $conversation)
				{
					$objListStage 				 = $this->core_web_workflow->getWorkflowStageByStageInit(
							"tb_customer_conversation","statusID",
							$conversation->statusID,
							$companyID,
							$branchID,
							$roleID
					);
					$objStageClose 				 = $objListStage[0];
					foreach($objListStage as $objStage)
					{
						if((int)$objStage->workflowStageID != $aiStageAttendedID)
						{
							$objStageClose = $objStage;
							break;
						}
					}

					$data 						 = array();
					$data["statusID"] 			 = $objStageClose->workflowStageID;
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

	/**
	 * Cambia el estado del agente de IA de la conversacion abierta de un cliente.
	 * JSON: entityID, accion = tomar (la atiende un humano, la IA deja de contestar)
	 *                        | atendida (etapa "ATENDIDA POR IA"; el proximo mensaje abre conversacion nueva con IA)
	 * @return \CodeIgniter\HTTP\ResponseInterface JSON success, message, conversationAI
	 */
	function setConversationAI_Status()
	{
		try
		{
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//PERMISO: el de la pantalla de conversaciones
			if(APP_NEED_AUTHENTICATION == true)
			{
				$permited = $this->core_web_permission->urlPermited("app_cxc_conversation","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
			}

			//PARAMETROS
			$data 				= $this->request->getJSON(true);
			$entityID 			= (int)($data['entityID'] ?? 0);
			$accion 			= (string)($data['accion'] ?? '');
			$companyID 			= (int)$dataSession["user"]->companyID;
			if($entityID <= 0 || !in_array($accion,["tomar","atendida"]))
			throw new \Exception("Datos incompletos: entityID y accion (tomar | atendida)");

			$objConversation 	= $this->Customer_Conversation_Model->getByEntityIDCustomer_StatusNameRegister($entityID);
			if(!$objConversation)
			throw new \Exception("El cliente no tiene una conversación abierta");
			$conversationID 	= (int)$objConversation[0]->conversationID;
			$aiData 			= json_decode((string)$objConversation[0]->aiData,true);
			$aiData 			= is_array($aiData) ? $aiData : [];

			//LOGICA
			if($accion == "tomar")
			{
				$this->Customer_Conversation_Model->update_aiStatus($conversationID,"humano");
			}
			else
			{
				$stageID = (int)$this->aiAgentParameter("WHATSAPP_AI_AGENT_STAGE_ATTENDED_ID",$companyID,"0");
				if($stageID <= 0)
				throw new \Exception("Configure el parámetro WHATSAPP_AI_AGENT_STAGE_ATTENDED_ID");
				$this->core_web_conversation->markAttendedByAI($companyID,$conversationID,$aiData,(string)$objConversation[0]->aiIntent);
			}
			log_message("error","[setConversationAI_Status] conversationID=".$conversationID." accion=".$accion." userID=".$dataSession["user"]->userID);

			//RESPUESTA
			return $this->response->setJSON([
				'success' 			=> true,
				'error' 			=> false,
				'message' 			=> SUCCESS,
				'conversationAI' 	=> $this->getConversationAI_Info($entityID)
			]);
		}
		catch(\Exception $ex)
		{
			return $this->response->setJSON([
				'success' 	=> false,
				'error' 	=> true,
				'message' 	=> $ex->getLine()." ".$ex->getMessage()
			]);
		}
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
	
	/**
	 * Webhook de la API oficial de WhatsApp (Meta Cloud API). Reemplaza al webhook de
	 * Evolution para RECIBIR; el envio sigue siempre por Evolution API.
	 * GET : verificacion de Meta. Si hub_verify_token coincide con el parametro
	 *       WHATSAP_META_VERIFY_TOKEN se devuelve hub_challenge en texto plano; si no, 403.
	 *       PHP convierte "hub.mode" en "hub_mode" dentro de $_GET.
	 * POST: valida la firma X-Hub-Signature-256 (WHATSAP_META_APP_SECRET), responde 200 a
	 *       Meta de inmediato y procesa cada mensaje con la funcion generica:
	 *       - field=messages           : mensaje del cliente.
	 *       - field=smb_message_echoes : mensaje que salio del numero (coexistencia). Si lo
	 *                                    envio posMe (por Evolution) se descarta porque ya
	 *                                    esta registrado; si lo escribio el dueño desde el
	 *                                    celular se registra como mensaje del dueño.
	 *       - statuses                 : se ignoran.
	 *       Solo procesa si WHATSAPP_WEBHOOK_METACLOUD = true.
	 *
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	//https://posme.net/v4posme/posme/public/app_cxc_api/WebHookReceiptMessage_Whatsapp_MetaCloud_posMe
	public function WebHookReceiptMessage_Whatsapp_MetaCloud_posMe()
	{
		try
		{
			$companyID 	= APP_COMPANY;
			$method 	= strtolower($this->request->getMethod());

			if($method == "get")
			{
				$hubMode 		= (string)$this->request->getGet("hub_mode");
				$hubToken 		= (string)$this->request->getGet("hub_verify_token");
				$hubChallenge 	= (string)$this->request->getGet("hub_challenge");

				$verifyToken 	= $this->metaCloudParameter($companyID, "WHATSAP_META_VERIFY_TOKEN");

				if($hubMode == "subscribe" && $verifyToken != "" && hash_equals($verifyToken, $hubToken))
				{
					log_message('error', '[MetaCloud] Verificacion de webhook correcta');
					return $this->response->setStatusCode(200)->setContentType('text/plain')->setBody($hubChallenge);
				}

				log_message('error', '[MetaCloud] Verificacion de webhook RECHAZADA: mode=' . $hubMode . ', parametro configurado=' . ($verifyToken != "" ? 'SI' : 'NO'));
				return $this->response->setStatusCode(403)->setContentType('text/plain')->setBody('Forbidden');
			}

			//POST: la firma se calcula sobre el cuerpo crudo, antes de decodificar el JSON
			$rawBody 		= (string)$this->request->getBody();

			//Interruptor: mientras este apagado no se procesa nada (Meta igual recibe 200)
			$webhookEnabled = strtolower($this->metaCloudParameter($companyID, "WHATSAPP_WEBHOOK_METACLOUD"));
			if($webhookEnabled != "true")
			{
				log_message('error', '[MetaCloud] DESCARTADO: parametro WHATSAPP_WEBHOOK_METACLOUD no es true');
				return $this->response->setJSON(['success' => false, 'message' => 'Webhook desactivado'])->setStatusCode(200);
			}

			//Firma obligatoria: sin App Secret configurado se rechaza todo
			$appSecret 		= $this->metaCloudParameter($companyID, "WHATSAP_META_APP_SECRET");
			$signature 		= (string)$this->request->getHeaderLine("X-Hub-Signature-256");
			if(!$this->metaCloudValidSignature($rawBody, $signature, $appSecret))
			{
				log_message('error', '[MetaCloud] RECHAZADO: firma X-Hub-Signature-256 invalida o App Secret no configurado (secret configurado=' . ($appSecret != "" ? 'SI' : 'NO') . ', cabecera=' . ($signature != "" ? 'SI' : 'NO') . ')');
				return $this->response->setStatusCode(403)->setContentType('text/plain')->setBody('Forbidden');
			}

			$input 			= json_decode($rawBody, true);
			if(!is_array($input) || ($input["object"] ?? '') != "whatsapp_business_account")
			{
				log_message('error', '[MetaCloud] DESCARTADO: JSON invalido u object distinto de whatsapp_business_account');
				return $this->response->setJSON(['success' => false, 'message' => 'Evento ignorado'])->setStatusCode(200);
			}

			//Juntar los mensajes de todas las entry/changes (Meta puede agrupar varios)
			$phoneNumberIdCfg 	= $this->metaCloudParameter($companyID, "WHATSAP_META_PHONE_NUMBER_ID");
			$items 				= [];
			$countStatuses 		= 0;
			foreach(($input["entry"] ?? []) as $entry)
			{
				foreach(($entry["changes"] ?? []) as $change)
				{
					$field 		= (string)($change["field"] ?? '');
					$value 		= is_array($change["value"] ?? null) ? $change["value"] : [];
					$metadata 	= $value["metadata"] ?? [];

					//Una misma app de Meta puede atender varios numeros: solo el de esta instalacion
					$phoneNumberId = (string)($metadata["phone_number_id"] ?? '');
					if($phoneNumberIdCfg != "" && $phoneNumberId != $phoneNumberIdCfg)
					{
						log_message('error', '[MetaCloud] DESCARTADO: phone_number_id=' . $phoneNumberId . ' no corresponde a esta instalacion');
						continue;
					}

					$countStatuses += count($value["statuses"] ?? []);
					if($field == "messages")
					{
						foreach(($value["messages"] ?? []) as $msg)
						{
							$items[] = ["isEcho" => false, "msg" => $msg, "contacts" => ($value["contacts"] ?? []), "metadata" => $metadata];
						}
					}
					elseif($field == "smb_message_echoes")
					{
						foreach(($value["message_echoes"] ?? []) as $msg)
						{
							$items[] = ["isEcho" => true, "msg" => $msg, "contacts" => [], "metadata" => $metadata];
						}
					}
				}
			}

			log_message('error', '[MetaCloud] POST recibido: mensajes=' . count($items) . ', statuses=' . $countStatuses);

			//Evitar duplicados: Meta reintenta el mismo evento con el mismo wamid
			$itemsNew = [];
			foreach($items as $item)
			{
				$wamid = (string)($item["msg"]["id"] ?? '');
				if($wamid !== '' && !$this->evolutionDedupRegister($wamid))
				{
					log_message('error', '[MetaCloud] DESCARTADO: wamid=' . $wamid . ' ya procesado (reintento de Meta)');
					continue;
				}
				$itemsNew[] = $item;
			}
			if(empty($itemsNew))
			{
				return $this->response->setJSON(['success' => true])->setStatusCode(200);
			}

			//Responder 200 a Meta antes de procesar (descarga de archivos, IA, eco con espera)
			$this->aiAgentReleaseClient(['success' => true]);

			foreach($itemsNew as $item)
			{
				$wamid = (string)($item["msg"]["id"] ?? '');
				try
				{
					$this->metaCloudProcessItem($companyID, $item);
				}
				catch(\Throwable $ex)
				{
					log_message('error', '[MetaCloud] ERROR procesando wamid=' . $wamid . ': ' . $ex->getLine() . ' ' . $ex->getMessage());
					$this->evolutionDedupRemove($wamid);
				}
			}
			return $this->response->setJSON(['success' => true])->setStatusCode(200);
		}
		catch(\Throwable $ex)
		{
			log_message('error', '[MetaCloud] ERROR: ' . $ex->getLine() . ' ' . $ex->getMessage());
			return $this->response->setJSON(['success' => false])->setStatusCode(200);
		}
	}

	/**
	 * Lee un parametro de la empresa sin lanzar excepcion si no existe.
	 * @param int    $companyID empresa
	 * @param string $name      nombre del parametro
	 * @return string valor sin espacios, o "" si no existe
	 */
	private function metaCloudParameter($companyID, $name)
	{
		try
		{
			$objParameter = $this->core_web_parameter->getParameter($name, $companyID);
			return $objParameter ? trim((string)$objParameter->value) : "";
		}
		catch(\Throwable $ex)
		{
			log_message('error', '[MetaCloud] AVISO: parametro ' . $name . ' no configurado');
			return "";
		}
	}

	/**
	 * Valida la cabecera X-Hub-Signature-256 de Meta: "sha256=" + HMAC-SHA256 del cuerpo
	 * crudo con el App Secret de la app.
	 * @param string $rawBody   cuerpo crudo de la peticion
	 * @param string $signature valor de la cabecera X-Hub-Signature-256
	 * @param string $appSecret App Secret (WHATSAP_META_APP_SECRET)
	 * @return bool true si la firma es valida; false si falta el secret, la cabecera o no coincide
	 */
	private function metaCloudValidSignature($rawBody, $signature, $appSecret)
	{
		if($appSecret === "" || stripos($signature, "sha256=") !== 0)
		{
			return false;
		}
		$expected = hash_hmac("sha256", (string)$rawBody, $appSecret);
		return hash_equals($expected, strtolower(substr($signature, 7)));
	}

	/**
	 * Procesa un mensaje de Meta: descarta los ecos enviados por posMe, convierte el resto
	 * al formato generico y lo registra con WebHookReceiptMessage_Whatsapp_Generic_posMe.
	 * @param int   $companyID empresa
	 * @param array $item      ["isEcho", "msg", "contacts", "metadata"]
	 * @return void
	 */
	private function metaCloudProcessItem($companyID, $item)
	{
		$isEcho 		= !empty($item["isEcho"]);
		$msg 			= $item["msg"];
		$wamid 			= (string)($msg["id"] ?? '');
		$msgType 		= (string)($msg["type"] ?? '');

		//En un mensaje del cliente "from" es el cliente; en un eco "from" es el numero de la
		//empresa y "to" el cliente
		$ownerDigits 	= preg_replace('/\D/', '', (string)($item["metadata"]["display_phone_number"] ?? ''));
		if($isEcho)
		{
			$fromDigits 	= preg_replace('/\D/', '', (string)($msg["from"] ?? ''));
			$ownerDigits 	= $fromDigits != '' ? $fromDigits : $ownerDigits;
			$clientDigits 	= preg_replace('/\D/', '', (string)($msg["to"] ?? ''));
		}
		else
		{
			$clientDigits 	= preg_replace('/\D/', '', (string)($msg["from"] ?? ''));
		}

		//Solo chats individuales (igual que Evolution descartaba grupos)
		if(isset($msg["group_id"]))
		{
			log_message('error', '[MetaCloud] DESCARTADO: mensaje de grupo, wamid=' . $wamid);
			return;
		}
		if($clientDigits == '')
		{
			log_message('error', '[MetaCloud] DESCARTADO: wamid=' . $wamid . ' sin telefono del cliente');
			return;
		}

		//Guardar el texto del mensaje (tambien de los enviados por posMe) para mostrarlo
		//cuando el cliente lo cite: Meta solo envia el id del mensaje citado
		$this->metaCloudQuotedCacheSave($wamid, (string)($msg["text"]["body"] ?? ($msg[$msgType]["caption"] ?? ($msg["document"]["filename"] ?? ''))));
		if($isEcho && $clientDigits == $ownerDigits)
		{
			log_message('error', '[MetaCloud] DESCARTADO: eco al propio numero de la empresa, wamid=' . $wamid);
			return;
		}

		//Eco de un mensaje que posMe envio por Evolution: ya esta en la conversacion
		if($isEcho)
		{
			$echoText = (string)($msg["text"]["body"] ?? ($msg[$msgType]["caption"] ?? ''));
			if($this->metaCloudIsSentByPosMe($wamid, $clientDigits, $msgType, $echoText))
			{
				return;
			}
			log_message('error', '[MetaCloud] Eco escrito desde el celular del dueño, se registrara. wamid=' . $wamid);
		}

		$genericData = $this->metaCloudMapMessage($companyID, $msg, $item["contacts"] ?? [], $isEcho, $clientDigits, $ownerDigits);
		if($genericData === null)
		{
			log_message('error', '[MetaCloud] DESCARTADO: tipo no soportado=' . $msgType . ', wamid=' . $wamid);
			return;
		}

		log_message('error', '[MetaCloud] Mensaje mapeado: wamid=' . $wamid
			. ', from=' . $genericData["data"]["from"]
			. ', tipo=' . $genericData["data"]["type"]
			. ', eco=' . ($isEcho ? 'SI' : 'NO')
			. ', conArchivo=' . (!empty($genericData["data"]["media"]) ? 'SI' : 'NO'));

		$this->WebHookReceiptMessage_Whatsapp_Generic_posMe($genericData);
	}

	/**
	 * Indica si un eco de Meta (smb_message_echoes) corresponde a un mensaje que posMe envio
	 * por Evolution API. Primero busca el id original dentro del wamid (el wamid es base64 y
	 * suele contener el key.id del mensaje, ej. 3EB0...) en la lista de enviados por posMe;
	 * si no aparece, busca la huella del envio (telefono + tipo + texto). Se reintenta unos
	 * segundos porque el eco puede llegar antes de que posMe guarde la respuesta del envio.
	 * @param string $wamid        id del mensaje en Meta
	 * @param string $clientDigits telefono del cliente (destinatario)
	 * @param string $msgType      tipo de Meta (text, image, document, audio, video, sticker)
	 * @param string $text         texto o caption del mensaje
	 * @return bool true si lo envio posMe (se debe descartar)
	 */
	private function metaCloudIsSentByPosMe($wamid, $clientDigits, $msgType, $text)
	{
		//Candidatos a id original: secuencias alfanumericas largas dentro del wamid decodificado
		$candidates = [];
		$encoded 	= preg_replace('/^wamid\./', '', $wamid);
		$decoded 	= base64_decode(strtr($encoded, '-_', '+/'), false);
		if($decoded !== false && preg_match_all('/[A-Za-z0-9]{12,}/', $decoded, $matches))
		{
			foreach($matches[0] as $candidate)
			{
				if($candidate !== $clientDigits)
				{
					$candidates[] = $candidate;
				}
			}
		}

		for($intento = 0; $intento < 4; $intento++)
		{
			if($intento > 0)
			{
				usleep(1000000);
			}
			foreach($candidates as $candidate)
			{
				if($this->core_web_whatsap->evolutionIsSentByPosMe($candidate))
				{
					//Consumir tambien la huella para que no descarte un mensaje igual del dueño
					$this->core_web_whatsap->evolutionConsumeSentFingerprint($clientDigits, $msgType, $text);
					log_message('error', '[MetaCloud] DESCARTADO: eco enviado por posMe (coincide id ' . $candidate . '), wamid=' . $wamid);
					return true;
				}
			}
			if($this->core_web_whatsap->evolutionConsumeSentFingerprint($clientDigits, $msgType, $text))
			{
				log_message('error', '[MetaCloud] DESCARTADO: eco enviado por posMe (coincide huella), wamid=' . $wamid . ', idsDelWamid=' . implode(',', $candidates));
				return true;
			}
		}
		log_message('error', '[MetaCloud] Eco sin coincidencia con envios de posMe: wamid=' . $wamid . ', idsDelWamid=' . implode(',', $candidates));
		return false;
	}

	/**
	 * Guarda el texto de un mensaje por su wamid (ultimos 1000) en
	 * WRITEPATH/evolution_dedup/meta_quoted.json, para resolver las citas de Meta.
	 * @param string $wamid id del mensaje en Meta
	 * @param string $text  texto, caption o nombre del documento
	 * @return void
	 */
	private function metaCloudQuotedCacheSave($wamid, $text)
	{
		$text = trim((string)$text);
		if($wamid === '' || $text === '')
		{
			return;
		}
		try
		{
			$dir = WRITEPATH . 'evolution_dedup';
			if(!is_dir($dir))
			{
				@mkdir($dir, 0777, true);
			}
			$handle = @fopen($dir . '/meta_quoted.json', 'c+');
			if($handle === false)
			{
				return;
			}
			flock($handle, LOCK_EX);
			$items 			= json_decode((string)stream_get_contents($handle), true);
			$items 			= is_array($items) ? $items : [];
			$items[$wamid] 	= mb_substr($text, 0, 500);
			if(count($items) > 1000)
			{
				$items = array_slice($items, -1000, null, true);
			}
			ftruncate($handle, 0);
			rewind($handle);
			fwrite($handle, json_encode($items));
			fflush($handle);
			flock($handle, LOCK_UN);
			fclose($handle);
		}
		catch(\Throwable $ex)
		{
			log_message('error', '[MetaCloud] AVISO: no se pudo guardar el texto para citas: ' . $ex->getMessage());
		}
	}

	/**
	 * Devuelve el texto de un mensaje citado guardado por metaCloudQuotedCacheSave.
	 * @param string $wamid id del mensaje citado (context.id)
	 * @return string texto, o "" si no se conoce
	 */
	private function metaCloudQuotedCacheGet($wamid)
	{
		$file = WRITEPATH . 'evolution_dedup/meta_quoted.json';
		if($wamid === '' || !file_exists($file))
		{
			return '';
		}
		$handle = @fopen($file, 'r');
		if($handle === false)
		{
			return '';
		}
		flock($handle, LOCK_SH);
		$items = json_decode((string)stream_get_contents($handle), true);
		flock($handle, LOCK_UN);
		fclose($handle);
		return is_array($items) ? (string)($items[$wamid] ?? '') : '';
	}

	/**
	 * Convierte un mensaje de Meta Cloud API (mensaje del cliente o eco) al formato generico
	 * de WebHookReceiptMessage_Whatsapp_Generic_posMe, igual que lo hace el webhook de
	 * Evolution. Los archivos se descargan de Meta con getMediaBase64ByMetaCloud.
	 * @param int    $companyID    empresa
	 * @param array  $msg          mensaje de Meta (messages[] o message_echoes[])
	 * @param array  $contacts     value.contacts[] (nombre del cliente)
	 * @param bool   $isEcho       true si salio del numero de la empresa
	 * @param string $clientDigits telefono del cliente
	 * @param string $ownerDigits  telefono de la empresa
	 * @return array|null datos genericos, o null si el tipo no se soporta (reaction, etc.)
	 */
	private function metaCloudMapMessage($companyID, $msg, $contacts, $isEcho, $clientDigits, $ownerDigits)
	{
		$msgType 	= (string)($msg["type"] ?? '');
		$body 		= '';
		$type 		= 'chat';
		$mediaNode 	= null;
		$filename 	= '';

		if($msgType == "text")
		{
			$body = (string)($msg["text"]["body"] ?? '');
		}
		elseif($msgType == "button")
		{
			$body = (string)($msg["button"]["text"] ?? ($msg["button"]["payload"] ?? ''));
		}
		elseif($msgType == "interactive")
		{
			$body = (string)($msg["interactive"]["button_reply"]["title"] ?? ($msg["interactive"]["list_reply"]["title"] ?? ''));
		}
		elseif($msgType == "image" || $msgType == "sticker")
		{
			$mediaNode 	= $msg[$msgType] ?? [];
			$body 		= (string)($mediaNode["caption"] ?? '');
			$type 		= 'image';
		}
		elseif($msgType == "video")
		{
			$mediaNode 	= $msg["video"] ?? [];
			$body 		= (string)($mediaNode["caption"] ?? '');
			$type 		= 'video';
		}
		elseif($msgType == "audio")
		{
			//Notas de voz y audios: igual que Evolution se tratan como "ptt" (la generica lo pasa a audio)
			$mediaNode 	= $msg["audio"] ?? [];
			$type 		= 'ptt';
		}
		elseif($msgType == "document")
		{
			$mediaNode 	= $msg["document"] ?? [];
			$body 		= (string)($mediaNode["caption"] ?? '');
			$type 		= 'document';
			$filename 	= (string)($mediaNode["filename"] ?? '');
		}
		elseif($msgType == "location")
		{
			$location 	= $msg["location"] ?? [];
			$body 		= trim('Ubicacion: ' . ($location["name"] ?? '') . ' ' . ($location["address"] ?? '')) . ' https://maps.google.com/?q=' . ($location["latitude"] ?? '') . ',' . ($location["longitude"] ?? '');
		}
		else
		{
			//reaction, unsupported, contacts, system, order...: se descartan como en Evolution
			return null;
		}

		//Nombre del cliente: contacts[] del mismo wa_id (en un eco no se conoce)
		$pushname = '';
		if(!$isEcho)
		{
			foreach($contacts as $contact)
			{
				if(preg_replace('/\D/', '', (string)($contact["wa_id"] ?? '')) == $clientDigits)
				{
					$pushname = (string)($contact["profile"]["name"] ?? '');
					break;
				}
			}
			if($pushname == '' && isset($contacts[0]["profile"]["name"]))
			{
				$pushname = (string)$contacts[0]["profile"]["name"];
			}
		}

		$genericData 								= [];
		$genericData["event"]                       = "message";
		$genericData["data"]["from"]                = $clientDigits . '@c.us';
		$genericData["data"]["body"]                = $body;
		$genericData["data"]["type"]                = $type;
		$genericData["data"]["contact"]["pushname"] = $pushname;
		//Meta solo envia el id del mensaje citado: el texto se busca en la cache local
		$genericData["data"]["quotedMsg"]["body"]   = $this->metaCloudQuotedCacheGet((string)($msg["context"]["id"] ?? ''));
		$genericData["data"]["fromMe"]              = $isEcho;
		$genericData["data"]["owner"]["name"]       = '';
		$genericData["data"]["owner"]["phone"]      = $isEcho ? $ownerDigits : '';
		$genericData["data"]["provider"]            = "metacloud";
		$genericData["data"]["isSticker"]           = ($msgType == "sticker");

		//Archivo: se descarga de Meta por su id. Si el nombre no trae extension la generica
		//la toma del mimetype (image/png -> png, audio/ogg; codecs=opus -> ogg)
		$mediaId = (string)($mediaNode["id"] ?? '');
		if($mediaNode !== null && $mediaId !== '')
		{
			$media = $this->core_web_whatsap->getMediaBase64ByMetaCloud($companyID, $mediaId);
			if(!empty($media["base64"]))
			{
				$genericData["data"]["media"] = [
					"mimetype" => (string)($mediaNode["mime_type"] ?? $media["mimetype"]),
					"data"     => $media["base64"],
					"filename" => $filename
				];
			}
			else
			{
				log_message('error', '[MetaCloud] AVISO: no se pudo descargar el archivo media_id=' . $mediaId . ', tipo=' . $msgType . '. Se registra como texto.');
			}
		}
		return $genericData;
	}

	/**
	 * Registra el id de un mensaje de Evolution API como procesado (lectura y escritura
	 * bajo el mismo bloqueo para que dos webhooks simultaneos no pasen ambos).
	 * @param string $messageId data.key.id del mensaje
	 * @return bool true si es la primera vez que se ve el id, false si es duplicado
	 */
	private function evolutionDedupRegister($messageId)
	{
		$dedupDir  = WRITEPATH . 'evolution_dedup';
		$dedupFile = $dedupDir . '/processed_ids.json';
		if(!is_dir($dedupDir))
		{
			@mkdir($dedupDir, 0777, true);
		}

		$handle = @fopen($dedupFile, 'c+');
		if($handle === false)
		{
			//Si no se puede abrir el archivo de control se procesa el mensaje igual
			log_message('error', '[EvolutionApi] AVISO: no se pudo abrir ' . $dedupFile);
			return true;
		}

		$isNew = true;
		flock($handle, LOCK_EX);
		$decoded 		= json_decode((string)stream_get_contents($handle), true);
		$processedIds 	= is_array($decoded) ? $decoded : [];

		if(isset($processedIds[$messageId]))
		{
			$isNew = false;
		}
		else
		{
			//Conservar solo los ultimos 500 ids para no crecer indefinidamente
			$processedIds[$messageId] = time();
			if(count($processedIds) > 500)
			{
				asort($processedIds);
				$processedIds = array_slice($processedIds, -500, null, true);
			}
			ftruncate($handle, 0);
			rewind($handle);
			fwrite($handle, json_encode($processedIds));
			fflush($handle);
		}
		flock($handle, LOCK_UN);
		fclose($handle);
		return $isNew;
	}

	/**
	 * Quita un id del archivo de control de duplicados (cuando el mensaje fallo o se descarto
	 * despues de registrarlo), para que un reenvio de Evolution lo vuelva a procesar.
	 * @param string $messageId data.key.id del mensaje
	 * @return void
	 */
	private function evolutionDedupRemove($messageId)
	{
		$dedupFile = WRITEPATH . 'evolution_dedup/processed_ids.json';
		if($messageId === '' || !file_exists($dedupFile))
		{
			return;
		}

		$handle = @fopen($dedupFile, 'c+');
		if($handle === false)
		{
			return;
		}
		flock($handle, LOCK_EX);
		$decoded = json_decode((string)stream_get_contents($handle), true);
		if(is_array($decoded) && isset($decoded[$messageId]))
		{
			unset($decoded[$messageId]);
			ftruncate($handle, 0);
			rewind($handle);
			fwrite($handle, json_encode($decoded));
			fflush($handle);
		}
		flock($handle, LOCK_UN);
		fclose($handle);
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

		if($data["customerMessageType"] == "video")
		{
			$data["customerMessageType"] = "video";
			log_message('error', '[Generic] Tipo mensaje: video');
		}
		
		if($data["customerMessageType"] == "ptt")
		{
			$data["customerMessageType"] = "audio";
			log_message('error', '[Generic] Tipo mensaje convertido: ptt -> audio');
		}

		//fromMe: mensaje que el dueño envio desde su telefono al cliente (solo EvolutionApi)
		$isFromMe 				= !empty($inputData["data"]["fromMe"]);
		log_message('error', '[Generic]    fromMe (enviado desde el telefono del dueño): ' . ($isFromMe ? 'SI' : 'NO'));

		//Buscar al cliente antes de guardar recursos multimedia
		$customerPhoneNumber 	= $data["customerPhoneNumber"];
		//En un fromMe no se conoce el nombre del cliente: se usa su telefono
		$customerFirstName		= "posMeConnect_".($data["customerFirstName"] != "" ? $data["customerFirstName"] : $customerPhoneNumber);
		
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

		//Si llego un mensaje multimedia sin el archivo (el proveedor no lo entrego),
		//se registra como texto para no perder el mensaje del cliente
		if(
			in_array($data["customerMessageType"], ["image", "pdf", "audio", "video", "document"]) &&
			empty($inputData['data']['media']['data'])
		)
		{
			log_message('error', '[Generic] AVISO: mensaje tipo ' . $data["customerMessageType"] . ' sin media, se registra como texto');
			$data["customerMessage"] 		= trim($data["customerMessage"] . " [Archivo " . $data["customerMessageType"] . " no disponible]");
			$data["customerMessageType"] 	= "text";
		}

		//Guardar el recurso o imagen o video o pdf
		if(
			$data["customerMessageType"] == "image" || 
			$data["customerMessageType"] == "pdf"   || 
			$data["customerMessageType"] == "audio" ||
			$data["customerMessageType"] == "video" ||
			$data["customerMessageType"] == "document" 
		)
		{
			log_message('error', '[Generic] >> Procesando recurso multimedia, tipo: ' . $data["customerMessageType"]);
			//Guardar la imagen
			$mediaTipe 	= (string)($inputData['data']['media']['mimetype'] ?? '');
			$base64 	= (string)($inputData['data']['media']['data'] ?? '');
			$filename 	= (string)($inputData['data']['media']['filename'] ?? '');
			log_message('error', '[Generic]    mediaMimetype: ' . $mediaTipe);
			log_message('error', '[Generic]    mediaFilename: ' . $filename);
			log_message('error', '[Generic]    base64 length: ' . strlen($base64));

			//generar nombre: primero la extension del nombre original; si no tiene, la del mimetype
			//quitando parametros ("audio/ogg; codecs=opus" -> "ogg")
			$extension = strtolower(preg_replace('/[^A-Za-z0-9]/', '', pathinfo($filename, PATHINFO_EXTENSION)));
			if($extension == '' || strlen($extension) > 10)
			{
				$mimeBase 	= strtolower(trim(explode(';', $mediaTipe)[0]));
				$mimeMap 	= [
					'image/jpeg' 		=> 'jpg',
					'audio/mpeg' 		=> 'mp3',
					'audio/mp4' 		=> 'm4a',
					'video/quicktime' 	=> 'mov',
					'text/plain' 		=> 'txt',
					'application/msword' 			=> 'doc',
					'application/vnd.ms-excel' 		=> 'xls',
					'application/vnd.openxmlformats-officedocument.wordprocessingml.document' 	=> 'docx',
					'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' 		=> 'xlsx',
					'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx'
				];
				$extension 	= $mimeMap[$mimeBase] ?? preg_replace('/[^a-z0-9]/', '', explode('/', $mimeBase . '/')[1]);
				$extension 	= ($extension == '' || strlen($extension) > 10) ? 'bin' : $extension;
			}
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
			//(el telefono viene del webhook: se limpia para que no pueda salir de la carpeta con "../")
			$phoneCustomer = preg_replace('/[^A-Za-z0-9_\-]/', '', (string)$data["customerPhoneNumber"]);
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
			$url 							= base_url()."/resource/file_company/company_".APP_COMPANY."/component_".$objComponentCustomerConversation->componentID."/component_item_".$phoneCustomer."/".$filename;
			$data["customerMessage"]	 	= $inputData['data']['body'] ?? '';
			$data["customerMessageUrl"]		= $url;
			$data["customerMessageFile"]	= $url;
			log_message("error","[Generic]    URL publica: " . $url);
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

		//Mensaje enviado por el dueño desde su telefono: se registra como saliente
		//(lado del colaborador) y no se notifica a los agentes ni se recategoriza al cliente
		if($isFromMe)
		{
			return $this->webHookSaveOwnerMessage_Whatsapp_posMe(
				$dataSession,
				$objCustomer[0],
				$objCustomerConversation[0],
				$conversationIsNew,
				$message,
				$messageUrl,
				$messageFile,
				$messageType,
				(string)($inputData["data"]["owner"]["name"] ?? ''),
				(string)($inputData["data"]["owner"]["phone"] ?? '')
			);
		}

		//////////////////////////////////////////////
		//Agente de IA de WhatsApp (WHATSAPP_AI_AGENT_ENABLE)
		//Solo Evolution API o Meta Cloud y solo mensajes del cliente. Las conversaciones que ya
		//existian antes de habilitar el agente (aiStatus NULL) siguen el flujo humano.
		//////////////////////////////////////////////
		//metacloud: se recibe por Meta pero la IA responde igual por Evolution API
		$aiEnabled 		= in_array(($inputData["data"]["provider"] ?? ''), ["evolutionapi", "metacloud"]) && $this->aiAgentIsEnabled($companyID);
		$aiStatus 		= (string)($objCustomerConversation[0]->aiStatus ?? '');
		if($aiEnabled && $conversationIsNew)
		{
			$this->Customer_Conversation_Model->update_aiStatus($objCustomerConversation[0]->conversationID,"activa");
			$objCustomerConversation[0]->aiStatus 	= "activa";
			$aiStatus 								= "activa";
		}
		$aiActive 		= $aiEnabled && $aiStatus == "activa";
		log_message('error', '[Generic]    Agente IA: habilitado=' . ($aiEnabled ? 'SI' : 'NO') . ', aiStatus=' . ($aiStatus == '' ? 'NULL' : $aiStatus));

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
		if(!$objTag)
		throw new \Exception("EL TAG 'MENSAJE DE CONVERSACION' NO EXISTE...");
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

		//La IA atiende la conversacion: no se asigna ni se notifica a los agentes
		//(eso lo hace handoffToHuman cuando la IA deriva)
		if($aiActive)
		{
			log_message('error', '[Generic] >> Conversación atendida por el agente de IA, se omite la asignación a agentes');
			return $this->webHookAIAgent_Whatsapp_posMe(
				$dataSession,
				$objCustomer[0],
				$objCustomerConversation[0],
				$objTag->tagID,
				$notificationID,
				$message,
				$messageType,
				$messageUrl,
				!empty($inputData["data"]["isSticker"]),
				[
					'success' 			=> true,
					'message'   		=> 'JSON valido',
					'entityID'			=> $objCustomer[0]->entityID,
					'converationID'		=> $objCustomerConversation[0]->conversationID,
					'notificationID'	=> $notificationID
				]
			);
		}

		//////////////////////////////////////////////
		//Obtener la lista de agentes a afiliar
		//////////////////////////////////////////////
		log_message('error', '[Generic] >> Obteniendo lista de agentes (getAllEmployer)...');
		log_message('error', '[Generic]    params: companyID=' . $companyID . ', type=' . $dataSession["company"]->type . ', phone=' . $customerPhoneNumber . ', conversationIsNew=' . ($conversationIsNew ? 'SI' : 'NO'));
		$objListEntityIDEmployer 					= $this->core_web_conversation->getAllEmployer($companyID,$dataSession["company"]->type,$customerPhoneNumber,$message,$conversationIsNew );
		log_message('error', '[Generic]    Colaboradores a asignar: tipoAsignacion=' . ($objListEntityIDEmployer["typeAsigned"] ?? 'null')
			. ', cantidad=' . count($objListEntityIDEmployer["employerList"] ?? [])
			. ', entityIDs=[' . implode(', ', $objListEntityIDEmployer["employerList"] ?? []) . ']');
		
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
		log_message("error","[Generic]    Ultima actividad anterior=" . $lastActivityOnOld
			. ', actual=' . $lastActivityOnNew
			. ', segundos transcurridos=' . ($diferenceDate["segundos"] ?? 'null')
			. ', comparador=' . ($diferenceDate["comparador"] ?? 'null')
			. ', tipoEmpresa=' . $dataSession["company"]->type);
		
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
				log_message("error","[Generic] *** DECISION NOTIFICACION COLABORADORES: SI SE ENVIARA (luciaralstate) - conversationID=" . $objCustomerConversation[0]->conversationID . ", cliente=" . $objCustomer[0]->firstName . " (entityID=" . $objCustomer[0]->entityID . ") ***");
				$urlSend		= base_url()."/app_cxc_conversation/edit/entityID/".$objCustomer[0]->entityID;
				$whatsappLink 	= urlencode($urlSend);
				log_message('error', '[Generic]    urlSend: ' . $urlSend);
				//Acortador con timeout: si is.gd no responde se usa la url completa
				$short 			= @file_get_contents("https://is.gd/create.php?format=simple&url=$whatsappLink", false, stream_context_create(['http' => ['timeout' => 5]]));
				$short 			= ($short === false || trim($short) == '') ? $urlSend : trim($short);
				log_message('error', '[Generic]    shortUrl: ' . $short);
			
				$messagePreview = mb_strlen($message) > 80 ? mb_substr($message, 0, 80) . '...' : $message;
				$this->core_web_conversation->notificationEmployerInConversation(
					$dataSession["company"]->companyID,
					$dataSession["user"]->branchID,
					$dataSession["company"]->type,
					$objCustomerConversation[0]->conversationID,
					"📩 *Cliente:".$objCustomer[0]->firstName."* ('".$objCustomer[0]->entityID."') ha enviado un mensaje:\n💬 _\"".$messagePreview."\"_\n👉 Por favor, respóndelo en el siguiente enlace: 🌐 ".$short,
					$objCustomer[0]->entityID
				);
				log_message('error', '[Generic]    notificationEmployerInConversation ejecutado (ver detalle de destinatarios en [notificationEmployerInConversation])');
			}
			else
			{
				//Detallar exactamente que condicion impide la notificacion
				$motivos = [];
				if($diferenceDate["comparador"] != "-1")
					$motivos[] = "la fecha de la ultima actividad no es anterior a la actual (comparador=" . $diferenceDate["comparador"] . ")";
				if(((int)$diferenceDate["segundos"]) < 10)
					$motivos[] = "no han pasado 10 segundos desde el ultimo mensaje (segundos=" . $diferenceDate["segundos"] . ")";
				if($objCustomer[0]->allowWhatsappCollection != 0)
					$motivos[] = "el numero del cliente esta bloqueado (allowWhatsappCollection=" . $objCustomer[0]->allowWhatsappCollection . ")";
				log_message('error', '[Generic] *** DECISION NOTIFICACION COLABORADORES: NO SE ENVIARA (luciaralstate). Motivo: ' . implode('; ', $motivos) . ' ***');
			}
		}
		else 
		{
			log_message('error', '[Generic]    Tipo: ' . $dataSession["company"]->type . ', evaluando condiciones...');
			log_message('error', '[Generic]    comparador=' . $diferenceDate["comparador"] . ', segundos=' . $diferenceDate["segundos"]);
			//Han pasado almenos 10 segundos desde el utlimo mensaje
			if($diferenceDate["comparador"] == "-1" && ((int)$diferenceDate["segundos"]) >=  10 )
			{
				log_message("error","[Generic] *** DECISION NOTIFICACION COLABORADORES: SI SE ENVIARA - conversationID=" . $objCustomerConversation[0]->conversationID . ", cliente=" . $objCustomer[0]->firstName . " (entityID=" . $objCustomer[0]->entityID . ") ***");
				$urlSend		= base_url()."/app_cxc_conversation/edit/entityID/".$objCustomer[0]->entityID;
				log_message('error', '[Generic]    urlSend: ' . $urlSend);				
				$short 			= $urlSend;
				log_message('error', '[Generic]    shortUrl: ' . $short);

				$messagePreview = mb_strlen($message) > 80 ? mb_substr($message, 0, 80) . '...' : $message;
				$this->core_web_conversation->notificationEmployerInConversation(
					$dataSession["company"]->companyID,
					$dataSession["user"]->branchID,
					$dataSession["company"]->type,
					$objCustomerConversation[0]->conversationID,
					"📩 *Cliente:". $objCustomer[0]->firstName."* ('".$objCustomer[0]->entityID."') ha enviado un mensaje:\n💬 _\"".$messagePreview."\"_\n👉 Por favor, respóndelo en el siguiente enlace: 🌐 ".$short,
					$objCustomer[0]->entityID
				);
				log_message('error', '[Generic]    notificationEmployerInConversation ejecutado (ver detalle de destinatarios en [notificationEmployerInConversation])');
			}
			else
			{
				//Detallar exactamente que condicion impide la notificacion
				$motivos = [];
				if($diferenceDate["comparador"] != "-1")
					$motivos[] = "la fecha de la ultima actividad no es anterior a la actual (comparador=" . $diferenceDate["comparador"] . ")";
				if(((int)$diferenceDate["segundos"]) < 10)
					$motivos[] = "no han pasado 10 segundos desde el ultimo mensaje (segundos=" . $diferenceDate["segundos"] . ")";
				log_message('error', '[Generic] *** DECISION NOTIFICACION COLABORADORES: NO SE ENVIARA. Motivo: ' . implode('; ', $motivos) . ' ***');
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
		log_message("error","[Generic] RESULTADO: entityID=" . $result["entityID"] . ", conversationID=" . $result["converationID"] . ", notificationID=" . $result["notificationID"]);

		//Conversacion derivada que ningun humano ha tomado: la IA puede seguir contestando
		if(
			$aiEnabled &&
			$aiStatus == "derivada" &&
			$this->aiAgentParameter("WHATSAPP_AI_AGENT_CONTINUE_AFTER_HANDOFF",$companyID,"true") == "true"
		)
		{
			log_message('error', '[Generic] >> Conversación derivada sin tomar, la IA sigue contestando');
			return $this->webHookAIAgent_Whatsapp_posMe(
				$dataSession,
				$objCustomer[0],
				$objCustomerConversation[0],
				$objTag->tagID,
				$notificationID,
				$message,
				$messageType,
				$messageUrl,
				!empty($inputData["data"]["isSticker"]),
				$result
			);
		}
		return $this->response->setJSON($result);
	}

	/**
	 * Indica si el agente de IA de WhatsApp esta habilitado para la empresa.
	 * @param int $companyID empresa
	 * @return bool
	 */
	private function aiAgentIsEnabled($companyID)
	{
		return $this->aiAgentParameter("WHATSAPP_AI_AGENT_ENABLE",$companyID,"false") == "true";
	}

	/**
	 * Lee un parametro del agente de IA sin lanzar excepcion si no existe.
	 * @param string $name      nombre del parametro
	 * @param int    $companyID empresa
	 * @param string $default   valor si el parametro no existe o esta vacio
	 * @return string valor en minusculas y sin espacios a los lados
	 */
	private function aiAgentParameter($name,$companyID,$default)
	{
		try
		{
			$objParameter 	= $this->core_web_parameter->getParameter($name,$companyID);
			$value 			= $objParameter ? trim((string)$objParameter->value) : "";
			//Sin distinguir mayusculas ("TRUE" = "true"); el mensaje de traspaso conserva su texto
			if($value !== "" && $name != "WHATSAPP_AI_AGENT_HANDOFF_MESSAGE")
				$value 		= mb_strtolower($value, "UTF-8");
			return $value === "" ? $default : $value;
		}
		catch(\Throwable $ex)
		{
			return $default;
		}
	}

	/**
	 * Agente de IA del webhook de WhatsApp (decision 9A).
	 * Responde 200 de inmediato a Evolution y sigue trabajando en segundo plano:
	 * espera DEBOUNCE segundos (si llego un mensaje mas nuevo termina, ese otro proceso
	 * contestara), toma un candado por cliente, pregunta a la IA, envia la respuesta,
	 * la guarda en la conversacion y ejecuta la accion (derivar a humano | atendida por IA).
	 * @param array  $dataSession     sesion del usuario por defecto del webhook
	 * @param object $objCustomer     cliente
	 * @param object $objConversation conversacion abierta del cliente
	 * @param int    $tagID           tag "MENSAJE DE CONVERSACION"
	 * @param int    $notificationID  mensaje del cliente recien guardado
	 * @param string $message         texto del mensaje
	 * @param string $messageType     text | image | pdf | audio | video | document
	 * @param string $messageUrl      url publica del archivo (multimedia)
	 * @param bool   $isSticker       true si el mensaje es un sticker
	 * @param array  $result          respuesta JSON para el proveedor
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	private function webHookAIAgent_Whatsapp_posMe($dataSession,$objCustomer,$objConversation,$tagID,$notificationID,$message,$messageType,$messageUrl,$isSticker,$result)
	{
		$companyID 		= (int)$dataSession["user"]->companyID;
		$entityID 		= (int)$objCustomer->entityID;
		$conversationID = (int)$objConversation->conversationID;
		$textoLimpio 	= trim(html_entity_decode(strip_tags((string)$message),ENT_QUOTES,'UTF-8'));

		//Sticker sin texto: se guarda pero no se contesta
		if($isSticker && $textoLimpio == "")
		{
			log_message('error', '[AgenteIA] Sticker sin texto, no se contesta. conversationID=' . $conversationID);
			return $this->response->setJSON($result);
		}

		//Adjuntos y audios se registran en aiData antes de esperar,
		//para que no se pierdan si este proceso termina por el debounce
		if(in_array($messageType,["image","pdf","document","audio","video"]))
		{
			$this->aiAgentRegisterMedia($conversationID,$messageType,$messageUrl);
		}

		//1) Responder 200 de inmediato y seguir en segundo plano
		$this->aiAgentReleaseClient($result);

		$lockName 	= "posme_ai_" . $companyID . "_" . $entityID;
		$db 		= db_connect();
		$tieneLock 	= false;
		try
		{
			//2) Debounce: si el cliente sigue escribiendo, contesta el proceso del ultimo mensaje
			$debounce 	= max(0,min(30,(int)$this->aiAgentParameter("WHATSAPP_AI_AGENT_DEBOUNCE_SECONDS",$companyID,"3")));
			if($debounce > 0)
				sleep($debounce);
			$objLast 	= $this->Notification_Model->get_rowLastCustomerMessage($entityID,$tagID);
			if($objLast && (int)$objLast->notificationID > (int)$notificationID)
			{
				log_message('error', '[AgenteIA] Llego un mensaje mas nuevo (' . $objLast->notificationID . '), este proceso termina');
				return $this->response->setJSON($result);
			}

			//3) Candado por cliente: si otro proceso ya esta contestando, el revisara este mensaje
			$rowLock 	= $db->query("SELECT GET_LOCK(" . $db->escape($lockName) . ",0) AS l")->getRow();
			$tieneLock 	= $rowLock && (int)$rowLock->l == 1;
			if(!$tieneLock)
			{
				log_message('error', '[AgenteIA] Otro proceso tiene el candado ' . $lockName . ', este proceso termina');
				return $this->response->setJSON($result);
			}

			//4) Contestar; si mientras tanto llegaron mensajes sin responder, otra vuelta (maximo 2)
			for($vuelta = 1; $vuelta <= 2; $vuelta++)
			{
				$objLast 		= $this->Notification_Model->get_rowLastCustomerMessage($entityID,$tagID);
				$lastCustomerID = $objLast ? (int)$objLast->notificationID : 0;
				$seguir 		= $this->aiAgentProcessTurn($dataSession,$objCustomer,$conversationID,$tagID);
				if(!$seguir)
					break;

				$objLast 		= $this->Notification_Model->get_rowLastCustomerMessage($entityID,$tagID);
				if(!$objLast || (int)$objLast->notificationID <= $lastCustomerID)
					break;
				log_message('error', '[AgenteIA] Hay mensajes sin responder, vuelta ' . ($vuelta + 1));
			}
		}
		catch(\Throwable $ex)
		{
			log_message('error', '[AgenteIA] ERROR ' . $ex->getLine() . ' ' . $ex->getMessage());
		}
		finally
		{
			if($tieneLock)
				$db->query("SELECT RELEASE_LOCK(" . $db->escape($lockName) . ")");
		}

		return $this->response->setJSON($result);
	}

	/**
	 * Un turno del agente: pregunta a la IA, envia la respuesta y ejecuta la accion.
	 * @param array  $dataSession    sesion del usuario por defecto del webhook
	 * @param object $objCustomer    cliente
	 * @param int    $conversationID conversacion
	 * @param int    $tagID          tag "MENSAJE DE CONVERSACION"
	 * @return bool true si la IA sigue a cargo de la conversacion (puede haber otra vuelta)
	 */
	private function aiAgentProcessTurn($dataSession,$objCustomer,$conversationID,$tagID)
	{
		$companyID 		= (int)$dataSession["user"]->companyID;
		$companyType 	= $dataSession["company"]->type;
		$phone 			= getNumberPhone(clearNumero($objCustomer->phoneNumber));

		//Volver a leer la conversacion: un humano pudo tomarla mientras se esperaba
		$objConversation = $this->aiAgentReadConversation($conversationID);
		if(!$objConversation)
			return false;
		$aiStatus = (string)$objConversation->aiStatus;
		if(!$this->aiAgentCanAnswer($companyID,$aiStatus))
		{
			log_message('error', '[AgenteIA] aiStatus=' . $aiStatus . ', la IA no contesta');
			return false;
		}

		$aiDataActual 	= json_decode((string)$objConversation->aiData,true);
		$aiDataActual 	= is_array($aiDataActual) ? $aiDataActual : [];

		//Segundo audio/video: se pasa a un humano sin llamar a la IA
		if($aiStatus == "activa" && (int)($aiDataActual["audios"] ?? 0) >= 2)
		{
			$aiResult = [
				"ok" 		=> true,
				"accion" 	=> "derivar",
				"respuesta" => "",
				"motivo" 	=> "el cliente envía audios o videos",
				"resumen" 	=> "",
				"intent" 	=> (string)$objConversation->aiIntent,
				"aiData" 	=> $aiDataActual
			];
		}
		else
		{
			$aiResult = $this->core_web_ai->askWhatsapp($companyID,$objCustomer,$objConversation,$tagID);
		}

		//Falla de la IA (decision 5): flujo humano normal, sin aviso al cliente
		if(empty($aiResult["ok"]))
		{
			$this->aiAgentLogFailure($companyID,(string)($aiResult["error"] ?? ''));
			if($aiStatus == "activa")
			{
				$aiResult["accion"] 	= "";
				$aiResult["motivo"] 	= "la IA no pudo responder (" . (string)($aiResult["error"] ?? '') . ")";
				$aiResult["aiData"] 	= $this->aiAgentMergeData($conversationID,$aiResult["aiData"] ?? $aiDataActual);
				$this->core_web_conversation->handoffToHuman($dataSession,$objConversation,$objCustomer,$aiResult,(string)$objConversation->lastMessage);
			}
			return false;
		}

		$respuesta 	= trim((string)($aiResult["respuesta"] ?? ''));
		$accion 	= (string)($aiResult["accion"] ?? '');
		$aiData 	= $this->aiAgentMergeData($conversationID,$aiResult["aiData"] ?? $aiDataActual);
		$aiResult["aiData"] = $aiData;

		//Al derivar se envia el mensaje de traspaso del parametro (o el texto de la IA si esta vacio)
		if($accion == "derivar")
		{
			$handoffMessage = $this->aiAgentParameter("WHATSAPP_AI_AGENT_HANDOFF_MESSAGE",$companyID,"");
			if($handoffMessage != "")
				$respuesta = $handoffMessage;
		}

		//Volver a leer aiStatus justo antes de enviar
		$objConversation = $this->aiAgentReadConversation($conversationID);
		if(!$objConversation || !$this->aiAgentCanAnswer($companyID,(string)$objConversation->aiStatus))
		{
			log_message('error', '[AgenteIA] La conversacion cambio de estado durante la consulta, se descarta la respuesta');
			return false;
		}

		if($respuesta != "")
		{
			//Firmado con el nombre del colaborador de la IA, igual que los mensajes de los asesores (se guarda sin firma)
			$respuestaFirmada = $this->core_web_conversation->getMessageSignature($companyID,$companyType,$this->core_web_conversation->getSignatureNameEmployerAI($companyID),$respuesta);
			$resultSend = $this->core_web_whatsap->sendMessageGeneric($companyType,$companyID,$respuestaFirmada,$phone,true,"");
			if(is_array($resultSend) && isset($resultSend["success"]) && $resultSend["success"] === false)
				log_message('error', '[AgenteIA] No se pudo enviar la respuesta: ' . (string)($resultSend["error"] ?? ''));
			$this->core_web_conversation->insertAIMessage($companyID,$objCustomer,$respuesta,'mensaje ia');

			$objUpdate 							= array();
			$objUpdate["lastMessage"] 			= $respuesta;
			$objUpdate["lastActivityOn"] 		= helper_getDateTime();
			$objUpdate["messageSendOn"] 		= helper_getDateTime();
			$this->Customer_Conversation_Model->update_app_posme($conversationID,$objUpdate);
		}

		$intent = (string)($aiResult["intent"] ?? '');
		if($accion == "derivar")
		{
			//Solo una conversacion activa se deriva; una ya derivada sigue esperando al humano
			if((string)$objConversation->aiStatus == "activa")
				$this->core_web_conversation->handoffToHuman($dataSession,$objConversation,$objCustomer,$aiResult,(string)$objConversation->lastMessage);
			else
				$this->Customer_Conversation_Model->update_aiStatus($conversationID,(string)$objConversation->aiStatus,["aiIntent" => ($intent != "" ? $intent : NULL),"aiData" => json_encode($aiData,JSON_UNESCAPED_UNICODE)]);
			return false;
		}
		if($accion == "atendida")
		{
			$this->core_web_conversation->markAttendedByAI($companyID,$conversationID,$aiData,$intent);
			return false;
		}

		$this->Customer_Conversation_Model->update_aiStatus($conversationID,(string)$objConversation->aiStatus,["aiIntent" => ($intent != "" ? $intent : NULL),"aiData" => json_encode($aiData,JSON_UNESCAPED_UNICODE)]);
		return true;
	}

	/**
	 * Indica si la IA puede contestar con el estado actual de la conversacion.
	 * @param int    $companyID empresa
	 * @param string $aiStatus  activa | derivada | humano | atendida | ''
	 * @return bool
	 */
	private function aiAgentCanAnswer($companyID,$aiStatus)
	{
		if($aiStatus == "activa")
			return true;
		if($aiStatus == "derivada")
			return $this->aiAgentParameter("WHATSAPP_AI_AGENT_CONTINUE_AFTER_HANDOFF",$companyID,"true") == "true";
		return false;
	}

	/**
	 * Lee la conversacion abierta por su ID (con los campos de IA).
	 * @param int $conversationID conversacion
	 * @return object|null
	 */
	private function aiAgentReadConversation($conversationID)
	{
		$rows = $this->Customer_Conversation_Model->getByConversationID((int)$conversationID);
		return $rows ? $rows[0] : null;
	}

	/**
	 * Une el aiData calculado por la IA con lo guardado en BD mientras se consultaba
	 * (adjuntos y audios que registraron otros procesos del webhook).
	 * @param int   $conversationID conversacion
	 * @param array $aiData         aiData devuelto por la IA
	 * @return array aiData final
	 */
	private function aiAgentMergeData($conversationID,$aiData)
	{
		$aiData 	= is_array($aiData) ? $aiData : [];
		$objConv 	= $this->aiAgentReadConversation($conversationID);
		$aiDataBD 	= $objConv ? json_decode((string)$objConv->aiData,true) : [];
		$aiDataBD 	= is_array($aiDataBD) ? $aiDataBD : [];

		$aiData["adjuntos"] = array_values(array_unique(array_merge(
			is_array($aiData["adjuntos"] ?? null) ? $aiData["adjuntos"] : [],
			is_array($aiDataBD["adjuntos"] ?? null) ? $aiDataBD["adjuntos"] : []
		)));
		$aiData["audios"] 	= max((int)($aiData["audios"] ?? 0),(int)($aiDataBD["audios"] ?? 0));
		unset($aiData["accion"]);
		return $aiData;
	}

	/**
	 * Registra en aiData la url de una imagen/documento (adjuntos) o cuenta un audio/video.
	 * @param int    $conversationID conversacion
	 * @param string $messageType    tipo del mensaje
	 * @param string $messageUrl     url publica del archivo
	 * @return void
	 */
	private function aiAgentRegisterMedia($conversationID,$messageType,$messageUrl)
	{
		$objConv 	= $this->aiAgentReadConversation($conversationID);
		if(!$objConv)
			return;
		$aiData 	= json_decode((string)$objConv->aiData,true);
		$aiData 	= is_array($aiData) ? $aiData : [];

		if(in_array($messageType,["audio","video"]))
		{
			$aiData["audios"] = (int)($aiData["audios"] ?? 0) + 1;
		}
		else if((string)$messageUrl != "")
		{
			$adjuntos 			= is_array($aiData["adjuntos"] ?? null) ? $aiData["adjuntos"] : [];
			$adjuntos[] 		= (string)$messageUrl;
			$aiData["adjuntos"] = array_values(array_unique($adjuntos));
		}

		$this->Customer_Conversation_Model->update_aiStatus($conversationID,(string)$objConv->aiStatus,["aiData" => json_encode($aiData,JSON_UNESCAPED_UNICODE)]);
	}

	/**
	 * Envia la respuesta HTTP al proveedor y cierra la conexion, dejando que el
	 * script siga trabajando (mod_php / php-fpm).
	 * @param array $result respuesta JSON
	 * @return void
	 */
	private function aiAgentReleaseClient($result)
	{
		ignore_user_abort(true);
		@set_time_limit(120);
		if(session_status() == PHP_SESSION_ACTIVE)
			session_write_close();

		$body = json_encode($result);
		while(ob_get_level() > 0)
			ob_end_clean();

		if(!headers_sent())
		{
			http_response_code(200);
			header("Content-Type: application/json; charset=UTF-8");
			header("Content-Length: " . strlen($body));
			header("Connection: close");
		}
		echo $body;
		flush();
		if(function_exists("fastcgi_finish_request"))
			fastcgi_finish_request();

		//Buffer nuevo para que CodeIgniter termine normal; lo que imprima ya no llega al proveedor
		ob_start();
	}

	/**
	 * Registra en el log una falla del agente de IA como maximo una vez por hora por empresa.
	 * @param int    $companyID empresa
	 * @param string $error     codigo o texto del error
	 * @return void
	 */
	private function aiAgentLogFailure($companyID,$error)
	{
		$key = "posme_ai_fail_" . (int)$companyID;
		try
		{
			$cache = \Config\Services::cache();
			if($cache->get($key))
				return;
			$cache->save($key,1,3600);
		}
		catch(\Throwable $ex)
		{
		}
		log_message('error', '[AgenteIA] La IA no pudo responder (companyID=' . $companyID . '): ' . $error . '. Se pasa la conversación a un humano.');
	}

	/**
	 * Registra en la conversacion del cliente un mensaje que el dueño del telefono de la
	 * empresa envio directamente desde WhatsApp (fromMe que no salio de posMe). Se guarda
	 * como mensaje saliente (lado del colaborador), ya leido, sin notificar a los agentes.
	 * @param array  $dataSession             Sesion del usuario por defecto del webhook
	 * @param object $objCustomer             Cliente destinatario del mensaje
	 * @param object $objCustomerConversation Conversacion activa del cliente
	 * @param bool   $conversationIsNew       true si la conversacion se acaba de crear
	 * @param string $message                 Texto del mensaje (o caption del archivo)
	 * @param string $messageUrl              URL publica del archivo ("" si es texto)
	 * @param string $messageFile             URL publica del archivo ("" si es texto)
	 * @param string $messageType             text, image, video, audio, document
	 * @param string $ownerName               pushName del dueño
	 * @param string $ownerPhone              Telefono del dueño (solo digitos)
	 * @return \CodeIgniter\HTTP\ResponseInterface
	 */
	private function webHookSaveOwnerMessage_Whatsapp_posMe($dataSession, $objCustomer, $objCustomerConversation, $conversationIsNew, $message, $messageUrl, $messageFile, $messageType, $ownerName, $ownerPhone)
	{
		$conversationID = $objCustomerConversation->conversationID;
		log_message('error', '[Generic] >> Registrando mensaje del dueño (fromMe) en conversationID=' . $conversationID);

		//Conversacion nueva: se asignan colaboradores igual que con un mensaje entrante,
		//para que la conversacion le aparezca a alguien en app_cxc_conversation
		if($conversationIsNew)
		{
			$objListEntityIDEmployer = $this->core_web_conversation->getAllEmployer($dataSession["user"]->companyID, $dataSession["company"]->type, $objCustomer->phoneNumber, $message, $conversationIsNew);
			$this->core_web_conversation->createEmployerInConversation($dataSession, $conversationID, $objListEntityIDEmployer["employerList"] ?? [], $conversationIsNew);
			log_message('error', '[Generic]    Conversacion nueva, colaboradores asignados: [' . implode(', ', $objListEntityIDEmployer["employerList"] ?? []) . ']');
		}

		//Colaborador con el que se registra el mensaje (la pantalla pinta del lado del agente
		//los mensajes cuyo entityIDTarget es un colaborador): el del usuario por defecto del
		//webhook y, si no tiene, el primer colaborador asignado a la conversacion
		$entityIDEmployer = (int)($dataSession["user"]->employeeID ?? 0);
		if($entityIDEmployer > 0 && !$this->Employee_Model->get_rowByEntityID($dataSession["user"]->companyID, $entityIDEmployer))
		{
			$entityIDEmployer = 0;
		}
		if($entityIDEmployer == 0)
		{
			$objEmployerList  = $this->Company_Component_Relation_Model->get_ConversationEmployerBy_ConversationID($conversationID);
			$entityIDEmployer = $objEmployerList ? (int)$objEmployerList[0]->entityID : 0;
		}
		if($entityIDEmployer == 0)
		{
			log_message('error', '[Generic]    AVISO: no hay colaborador para registrar el mensaje del dueño; se vera del lado del cliente');
		}
		log_message('error', '[Generic]    entityIDEmployer para el mensaje del dueño: ' . $entityIDEmployer);

		//Actualizar la conversacion como mensaje enviado (no suma mensajes sin leer)
		$objConversation 					= array();
		$objConversation["lastMessage"] 	= $message;
		$objConversation["lastActivityOn"] 	= helper_getDateTime();
		$objConversation["messageSendOn"] 	= helper_getDateTime();
		$this->Customer_Conversation_Model->update_app_posme($conversationID, $objConversation);

		//Agente de IA: el dueño contesto desde su telefono, la conversacion la toma un humano
		$aiStatusOwner = (string)($objCustomerConversation->aiStatus ?? '');
		if($aiStatusOwner == "activa" || $aiStatusOwner == "derivada")
		{
			$this->Customer_Conversation_Model->update_aiStatus($conversationID, "humano");
			log_message('error', '[Generic]    Agente IA: aiStatus ' . $aiStatusOwner . ' -> humano (respondio el dueño)');
		}

		//Ultima interaccion del colaborador en la conversacion
		if($entityIDEmployer > 0)
		{
			$objCCR 					= array();
			$objCCR["lastActivityOn"] 	= helper_getDateTime();
			$this->Company_Component_Relation_Model->update_app_posme_byConversationID_AndEntityIDEmployer($conversationID, $entityIDEmployer, $objCCR);
		}

		//Guardar el mensaje con el mismo formato que setConversationNotification_ByCustomer
		$objTag = $this->Tag_Model->get_rowByName("MENSAJE DE CONVERSACION");
		if(!$objTag)
		throw new \Exception("EL TAG 'MENSAJE DE CONVERSACION' NO EXISTE...");

		$objNotification 							= array();
		$objNotification["errorID"] 				= 0;
		$objNotification["from"] 					= ($ownerName != '' ? $ownerName : 'Propietario');
		$objNotification["to"] 						= $objCustomer->firstName;
		$objNotification["subject"] 				= ($messageUrl != '' ? $messageUrl : 'no use');
		$objNotification["message"] 				= $message;
		$objNotification["summary"] 				= ($messageFile != '' ? $messageFile : 'no use');
		$objNotification["title"] 					= $messageType;
		$objNotification["tagID"] 					= $objTag->tagID;
		$objNotification["createdOn"] 				= helper_getDateTime();
		$objNotification["isActive"] 				= 1;
		$objNotification["phoneFrom"] 				= $ownerPhone;
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
		$objNotification["entityIDSource"] 			= $objCustomer->entityID;
		$objNotification["entityIDTarget"] 			= $entityIDEmployer;
		$notificationID 							= $this->Notification_Model->insert_app_posme($objNotification);

		log_message("error","[Generic] ====== FIN WebHookReceiptMessage_Whatsapp_Generic_posMe (mensaje del dueño) ======");
		log_message("error","[Generic] RESULTADO: entityID=" . $objCustomer->entityID . ", conversationID=" . $conversationID . ", notificationID=" . $notificationID);
		return $this->response->setJSON([
			'success' 			=> true,
			'message'   		=> 'Mensaje del dueño registrado',
			'entityID'			=> $objCustomer->entityID,
			'converationID'		=> $conversationID,
			'notificationID'	=> $notificationID
		]);
	}
}
?>
