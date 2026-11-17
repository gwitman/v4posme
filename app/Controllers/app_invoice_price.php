<?php
//posme:2023-02-27
namespace App\Controllers;
class app_invoice_price extends _BaseController {
	
       
	function viewRegister(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
							
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_EDIT);		
			}	 
			
			
			$listPriceID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"listPriceID");//--finuri		
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			$datView["objListPrice"]				= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);
			$datView["objListPriceDetail"]			= $this->Price_Model->get_rowByAll($companyID,$listPriceID);
			
			
			
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}
	}
	function edit(){ 
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_EDIT);			
			
			}	
			
			//Librerias		
			//
			////////////////////////////////////////
			 
			
			//Redireccionar datos
			
						
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$listPriceID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"listPriceID");//--finuri		
			$branchID		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			
			if((!$companyID || !$branchID || !$listPriceID))
			{ 
				$this->response->redirect(base_url()."/".'app_invoice_price/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objListPrice"]	 		= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);						
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			//Obtener Informacion
			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_list_price","statusID",$datView["objListPrice"]->statusID,$companyID,$branchID,$roleID);			
			$datView["useMobile"]						= $dataSession["user"]->useMobile;
			$datView["objComponent"] 					= $objComponent;			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_invoice_price/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_invoice_price/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_invoice_price/edit_script',$datView);//--finview
			$dataSession["footer"]			= "";				
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}	
	}
	function delete(){
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"delete",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_DELETE);			
			
			}	
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			
			//Nuevo Registro
			$companyID 			= /*inicio get post*/ $this->request->getPost("companyID");
			$listPriceID		= /*inicio get post*/ $this->request->getPost("listPriceID");				
			
			
			if((!$companyID && !$listPriceID )){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL lista de precio
			$objListPrice	= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);	
			
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objListPrice->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$objListPrice->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->List_Price_Model->delete_app_posme($companyID,$listPriceID);
					
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
		
	}
	function updateElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_EDIT);	
			}
			
				
				
			
			 			
			
			//Obtener el Componente de la lista de precio
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;			
			$listPriceID 							= /*inicio get post*/ $this->request->getPost("txtListPriceID");
			
			$objListPrice							= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);
			$oldStatusID 							= $objListPrice->statusID;
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objListPrice->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$objListPrice->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objListPrice					= array();
				$objListPrice["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->List_Price_Model->update_app_posme($companyID,$listPriceID,$objListPrice);
			}
			else{
				
				$objListPrice 							= NULL;
				$objListPrice["startOn"]				= /*inicio get post*/ $this->request->getPost("txtStartOn");//--fin peticion get o post
				$objListPrice["endOn"]					= /*inicio get post*/ $this->request->getPost("txtEndOn");//--fin peticion get o post
				$objListPrice["name"]					= /*inicio get post*/ $this->request->getPost("txtName");//--fin peticion get o post
				$objListPrice["description"]			= /*inicio get post*/ $this->request->getPost("txtDescription");//--fin peticion get o post
				$objListPrice["statusID"]				= /*inicio get post*/ $this->request->getPost("txtStatusID");//--fin peticion get o post
				$objListPrice["isActive"]				= 1;			
				$this->List_Price_Model->update_app_posme($companyID,$listPriceID,$objListPrice);
				
				
				//La importacion y generacion del archivo de precios se hace por AJAX (generateFile / processFile)
			}
			
			//Confirmar Entidad
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_invoice_price/edit/companyID/'.$companyID."/listPriceID/".$listPriceID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_invoice_price/add');	
			}
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    echo $resultView;
		}		
	}
	function insertElement($dataSession){
		try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_INSERT);	
			}
			
					
			
			
			//Obtener el Componente de Lista de Precio
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;						
			$objListPrice["companyID"]				= $companyID;
			$objListPrice["startOn"]				= /*inicio get post*/ $this->request->getPost("txtStartOn");//--fin peticion get o post
			$objListPrice["endOn"]					= /*inicio get post*/ $this->request->getPost("txtEndOn");//--fin peticion get o post
			$objListPrice["name"]					= /*inicio get post*/ $this->request->getPost("txtName");//--fin peticion get o post
			$objListPrice["description"]			= /*inicio get post*/ $this->request->getPost("txtDescription");//--fin peticion get o post
			$objListPrice["statusID"]				= /*inicio get post*/ $this->request->getPost("txtStatusID");//--fin peticion get o post
			$objListPrice["isActive"]				= 1;
			$this->core_web_auditoria->setAuditCreated($objListPrice,$dataSession,$this->request);
			
			$db=db_connect();
			$db->transStart();
			$listPriceID = $this->List_Price_Model->insert_app_posme($objListPrice);
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$listPriceID;
			mkdir($path, 0700,true);
			$path 	= $path.'/list_price_default.csv';
			$fp 	= fopen($path, 'w');
			
			//Crear el archivo de precios
			$objListItem 	= $this->Item_Model->get_rowByCompany($companyID);
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			$field 		 	= ["Codigo","Descripcion","Costo"];
			foreach($objTipePrice as $price){
				array_push($field,"".$price->catalogItemID."-".strtoupper($price->display)."-%");
				array_push($field,"".$price->catalogItemID."-".strtoupper($price->display)."-abs");
			}
			
			fputcsv($fp, $field);
			foreach($objListItem as $item){
				$rowfield = [];
				array_push($rowfield,$item->itemNumber);
				array_push($rowfield,$item->name);
				array_push($rowfield,$item->cost);
			
				foreach($objTipePrice as $price){
					array_push($rowfield,0);
					array_push($rowfield,0);
				}
				
				fputcsv($fp, $rowfield);
			}			
			fclose($fp);
			
			
			//Commit Operaciones
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_invoice_price/edit/companyID/'.$companyID."/listPriceID/".$listPriceID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_invoice_price/add');	
			}
			
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}	
	}
	/**
	 * Valida autenticacion, permiso de edicion y (opcional) el estado de la lista de precio.
	 * @param  mixed $listPriceID      ID de la lista de precio
	 * @param  bool  $validarWorkflow  true = exige que el estado permita editar los precios
	 * @return array companyID, dataSession, objListPrice y path (carpeta de archivos de la lista)
	 */
	private function priceValidarAcceso($listPriceID,$validarWorkflow)
	{
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();
		$resultPermission	= null;

		//PERMISO SOBRE LA FUNCION
		if(APP_NEED_AUTHENTICATION == true){
			$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			if(!$permited)
			throw new \Exception(NOT_ACCESS_CONTROL);

			$resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			if ($resultPermission == PERMISSION_NONE)
			throw new \Exception(NOT_ALL_EDIT);
		}

		$companyID		= $dataSession["user"]->companyID;
		$branchID		= $dataSession["user"]->branchID;
		$roleID			= $dataSession["role"]->roleID;
		$listPriceID	= (int)$listPriceID;
		if(!$listPriceID)
		throw new \Exception(NOT_PARAMETER);

		$objListPrice	= $this->List_Price_Model->get_rowByPK($companyID,$listPriceID);
		if(!$objListPrice)
		throw new \Exception("LA LISTA DE PRECIO NO EXISTE");

		$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
		if(!$objComponent)
		throw new \Exception("EL COMPONENTE 'tb_list_price' NO EXISTE...");

		//Mismas reglas que updateElement: los precios solo se cambian si el estado permite edicion total
		if($validarWorkflow){
			if ($resultPermission !== null && $resultPermission == PERMISSION_ME && ($objListPrice->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);

			if(!$this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$objListPrice->statusID,COMMAND_EDITABLE_TOTAL,$companyID,$branchID,$roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);

			if($this->core_web_workflow->validateWorkflowStage("tb_list_price","statusID",$objListPrice->statusID,COMMAND_EDITABLE,$companyID,$branchID,$roleID))
			throw new \Exception("EL ESTADO DE LA LISTA SOLO PERMITE CAMBIAR EL ESTADO, NO LOS PRECIOS");
		}

		return array(
			"companyID"		=> $companyID,
			"listPriceID"	=> $listPriceID,
			"dataSession"	=> $dataSession,
			"objListPrice"	=> $objListPrice,
			"path"			=> PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$listPriceID
		);
	}

	/**
	 * Limpia el nombre de archivo recibido (evita rutas) y exige extension .csv
	 * @param  string $fileName
	 * @return string
	 */
	private function priceNombreArchivo($fileName)
	{
		$fileName = basename(str_replace("\\","/",trim((string)$fileName)));
		if($fileName == "" || strtolower(pathinfo($fileName,PATHINFO_EXTENSION)) != "csv")
		throw new \Exception("SELECCIONE UN ARCHIVO CON EXTENSION <b>.csv</b>");

		return $fileName;
	}

	/**
	 * Convierte un valor del archivo a numero. Quita espacios y separador de miles (coma).
	 * @param  mixed $valor
	 * @return float|null|false  null = celda vacia, false = no numerico
	 */
	private function priceNumero($valor)
	{
		$valor = str_replace(array(" ",",","\xC2\xA0"),"",trim((string)$valor));
		if($valor === "")
		return null;

		if(!is_numeric($valor))
		return false;

		return (float)$valor;
	}

	/**
	 * Porcentaje de ganancia sobre el costo a partir del precio.
	 * Con costo 0 se conserva la regla historica del sistema (precio / 100).
	 * @param  float $precio
	 * @param  float $costo
	 * @return float
	 */
	private function pricePorcentaje($precio,$costo)
	{
		return $costo == 0 ? ($precio / 100) : (((100 * $precio) / $costo) - 100);
	}

	/**
	 * Busca en el encabezado del archivo las columnas de un tipo de precio:
	 * "<typePriceID>-<NOMBRE>-abs" (valor), "<typePriceID>-<NOMBRE>-%" (porcentaje) y "<typePriceID>-COMISION%-abs".
	 * Se busca por el ID para no depender de que el nombre del tipo de precio no haya cambiado.
	 * @param  array $encabezado
	 * @param  int   $typePriceID
	 * @return array abs, porc, comision (nombre de columna o null)
	 */
	private function priceColumnas($encabezado,$typePriceID)
	{
		$result		= array("abs" => null, "porc" => null, "comision" => null);
		$prefijo	= $typePriceID."-";
		foreach($encabezado as $col){
			if(strpos($col,$prefijo) !== 0)
			continue;

			$resto = strtolower(substr($col,strlen($prefijo)));
			if($resto == "comision%-abs")
			$result["comision"]	= $col;
			else if(substr($resto,-4) == "-abs")
			$result["abs"]		= $col;
			else if(substr($resto,-2) == "-%")
			$result["porc"]		= $col;
		}
		return $result;
	}

	/**
	 * Lee el CSV de precios. Detecta el separador (parametro CORE_CSV_SPLIT, coma o punto y coma)
	 * y no limita el largo de la linea (listas con muchos tipos de precio).
	 * @param  string $file
	 * @param  string $separador  Separador configurado en la empresa
	 * @return array  encabezado y filas (linea + datos por nombre de columna)
	 */
	private function priceLeerCsv($file,$separador)
	{
		$fp = fopen($file,"r");
		if(!$fp)
		throw new \Exception("NO SE PUDO ABRIR EL ARCHIVO");

		//Detectar separador con la primera linea (Excel puede guardar con ; aunque la empresa use ,)
		$primera	= (string)fgets($fp);
		$candidatos	= array_unique(array($separador == "" ? "," : $separador, ",", ";"));
		$mejor		= $separador == "" ? "," : $separador;
		$maximo		= -1;
		foreach($candidatos as $c){
			if(substr_count($primera,$c) > $maximo){
				$maximo	= substr_count($primera,$c);
				$mejor	= $c;
			}
		}
		rewind($fp);

		$encabezado	= null;
		$filas		= array();
		$linea		= 0;
		while(($datos = fgetcsv($fp,0,$mejor)) !== false){
			$linea++;
			if($encabezado === null){
				$encabezado = array();
				foreach($datos as $col)
				$encabezado[] = trim(str_replace("\xEF\xBB\xBF","",(string)$col));
				continue;
			}

			//Lineas vacias
			if(count($datos) == 1 && trim((string)$datos[0]) == "")
			continue;

			$item = array();
			foreach($encabezado as $i => $col)
			$item[$col] = isset($datos[$i]) ? $datos[$i] : "";

			$filas[] = array("linea" => $linea, "datos" => $item);
		}
		fclose($fp);

		if(!$encabezado || !in_array("Codigo",$encabezado))
		throw new \Exception("EL ARCHIVO NO TIENE LA COLUMNA <b>Codigo</b>. Genere el archivo desde esta pantalla y vuelva a intentarlo.");

		return array("encabezado" => $encabezado, "filas" => $filas);
	}

	/**
	 * Genera el archivo CSV con los precios actuales de la lista y devuelve la URL para descargarlo.
	 * Columnas por tipo de precio: valor (-abs), porcentaje (-%) y comision.
	 */
	function generateFile(){
		try{
			$acceso		= $this->priceValidarAcceso($this->request->getPost("listPriceID"),false);
			$companyID	= $acceso["companyID"];
			$listPriceID= $acceso["listPriceID"];
			$path		= $acceso["path"];

			//Crear la carpeta de archivos de la lista
			if (!file_exists($path))
			{
				mkdir($path, 0755, true);
				chmod($path, 0755);
			}

			date_default_timezone_set(APP_TIMEZONE);
			$fileName		= "list_price_".$listPriceID."_".date("Y_m_d_H_i_s").".csv";
			$fp				= fopen($path."/".$fileName,"w");
			if(!$fp)
			throw new \Exception("NO SE PUDO CREAR EL ARCHIVO EN EL SERVIDOR");

			$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
			$separador		= $objParameter->value;
			$objListItem	= $this->Item_Model->get_rowByCompany($companyID);
			$objTipePrice	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);

			//Precios actuales indexados por producto y tipo de precio (una sola consulta)
			$mapPrice		= array();
			$objPrices		= $this->Price_Model->get_rowByAll($companyID,$listPriceID);
			if($objPrices)
			foreach($objPrices as $p)
			$mapPrice[$p->itemID."-".$p->typePriceID] = $p;

			//BOM para que Excel respete tildes y eñes
			fwrite($fp,"\xEF\xBB\xBF");

			$field = array("Codigo","Descripcion","Costo");
			if($objTipePrice)
			foreach($objTipePrice as $tipo){
				array_push($field,$tipo->catalogItemID."-".strtoupper($tipo->display)."-abs");
				array_push($field,$tipo->catalogItemID."-".strtoupper($tipo->display)."-%");
				array_push($field,$tipo->catalogItemID."-".strtoupper("COMISION%")."-abs");
			}
			fputcsv($fp,$field,$separador);

			if($objListItem)
			foreach($objListItem as $item){
				$rowfield = array($item->itemNumber,$item->name,round((float)$item->cost,2));
				if($objTipePrice)
				foreach($objTipePrice as $tipo){
					$objPrice = isset($mapPrice[$item->itemID."-".$tipo->catalogItemID]) ? $mapPrice[$item->itemID."-".$tipo->catalogItemID] : null;
					array_push($rowfield,$objPrice ? round((float)$objPrice->price,2) : 0);
					array_push($rowfield,$objPrice ? round((float)$objPrice->percentage,4) : 0);
					array_push($rowfield,$objPrice ? round((float)$objPrice->percentageCommision,2) : 0);
				}
				fputcsv($fp,$rowfield,$separador);
			}
			fclose($fp);

			return $this->response->setJSON(array(
				'error'			=> false,
				'message'		=> SUCCESS,
				'fileName'		=> $fileName,
				'urlDownload'	=> base_url()."/app_invoice_price/downloadFile/listPriceID/".$listPriceID."?fileName=".rawurlencode($fileName)
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
	 * Descarga un archivo de la carpeta de la lista de precio (GET: listPriceID en la URL, fileName en query).
	 */
	function downloadFile(){
		try{
			$listPriceID	= helper_SegmentsValue($this->uri->getSegments(),"listPriceID");
			$acceso			= $this->priceValidarAcceso($listPriceID,false);
			$fileName		= $this->priceNombreArchivo($this->request->getGet("fileName"));
			$file			= $acceso["path"]."/".$fileName;

			if(!is_file($file))
			throw new \Exception("EL ARCHIVO <b>".esc($fileName)."</b> NO EXISTE");

			return $this->response->download($file,null)->setFileName($fileName);
		}
		catch(\Exception $ex){
			$dataSession		= $this->session->get();
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/app_invoice_price/index";
		    $data["urlBack"]   = base_url()."/app_invoice_price/index";
		    return view("core_template/email_error_general",$data);
		}
	}

	/**
	 * Lista los archivos .csv de la carpeta de la lista de precio (mas reciente primero).
	 */
	function listFile(){
		try{
			$acceso		= $this->priceValidarAcceso($this->request->getPost("listPriceID"),false);
			$path		= $acceso["path"];
			$archivos	= array();

			if(is_dir($path))
			foreach(scandir($path) as $nombre){
				$file = $path."/".$nombre;
				if(!is_file($file) || strtolower(pathinfo($nombre,PATHINFO_EXTENSION)) != "csv")
				continue;

				$archivos[] = array(
					"name"		=> $nombre,
					"time"		=> filemtime($file),
					"date"		=> date("Y-m-d H:i:s",filemtime($file)),
					"size"		=> filesize($file),
					"urlDownload"	=> base_url()."/app_invoice_price/downloadFile/listPriceID/".$acceso["listPriceID"]."?fileName=".rawurlencode($nombre)
				);
			}

			usort($archivos,function($a,$b){ return $b["time"] - $a["time"]; });

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'data'    => $archivos
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
	 * Procesa el archivo de precios seleccionado.
	 * Por cada producto y tipo de precio:
	 *  - si cambio el valor (-abs) se guarda el valor y se calcula el %;
	 *  - si no cambio el valor pero si el % (-%), se guarda el % y se calcula el valor (costo + %);
	 *  - la comision se guarda tal cual.
	 * Solo se escriben los precios que cambiaron. Devuelve el resumen y el detalle de cambios.
	 */
	function processFile(){
		try{
			$acceso			= $this->priceValidarAcceso($this->request->getPost("listPriceID"),true);
			$companyID		= $acceso["companyID"];
			$listPriceID	= $acceso["listPriceID"];
			$fileName		= $this->priceNombreArchivo($this->request->getPost("fileName"));
			$file			= $acceso["path"]."/".$fileName;

			if(!is_file($file))
			throw new \Exception("EL ARCHIVO <b>".esc($fileName)."</b> NO EXISTE, refresque la lista de archivos");

			set_time_limit(0);
			$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
			$csv			= $this->priceLeerCsv($file,$objParameter->value);
			$objTipePrice	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			if(!$objTipePrice)
			throw new \Exception("NO HAY TIPOS DE PRECIO CONFIGURADOS");

			//Columnas de cada tipo de precio
			$columnas		= array();
			$hayColumnas	= false;
			foreach($objTipePrice as $tipo){
				$columnas[$tipo->catalogItemID] = $this->priceColumnas($csv["encabezado"],$tipo->catalogItemID);
				if($columnas[$tipo->catalogItemID]["abs"] !== null || $columnas[$tipo->catalogItemID]["porc"] !== null)
				$hayColumnas = true;
			}
			if(!$hayColumnas)
			throw new \Exception("EL ARCHIVO NO TIENE COLUMNAS DE PRECIO. Genere el archivo desde esta pantalla y vuelva a intentarlo.");

			//Productos por codigo y precios actuales (una consulta cada uno)
			$mapItem		= array();
			$objListItem	= $this->Item_Model->get_rowByCompany($companyID);
			if($objListItem)
			foreach($objListItem as $item)
			$mapItem[trim($item->itemNumber)] = $item;

			$mapPrice		= array();
			$objPrices		= $this->Price_Model->get_rowByAll($companyID,$listPriceID);
			if($objPrices)
			foreach($objPrices as $p)
			$mapPrice[$p->itemID."-".$p->typePriceID] = $p;

			$filasLeidas			= 0;
			$productosActualizados	= 0;
			$preciosCambiados		= 0;
			$productosSinCambio		= 0;
			$detalle				= array();
			$errores				= array();

			$db = db_connect();
			$db->transStart();

			foreach($csv["filas"] as $fila){
				$codigo = trim(isset($fila["datos"]["Codigo"]) ? (string)$fila["datos"]["Codigo"] : "");
				if($codigo == "")
				continue;

				$filasLeidas++;
				if(!isset($mapItem[$codigo])){
					$errores[] = array("linea" => $fila["linea"], "codigo" => $codigo, "nombre" => isset($fila["datos"]["Descripcion"]) ? $fila["datos"]["Descripcion"] : "", "mensaje" => "EL PRODUCTO NO EXISTE O ESTA INACTIVO");
					continue;
				}

				$objItem		= $mapItem[$codigo];
				$costo			= (float)$objItem->cost;
				$operaciones	= array();
				$errorProducto	= "";

				//1. Calcular todos los tipos de precio del producto (si uno falla no se guarda ninguno)
				foreach($objTipePrice as $tipo){
					$cols		= $columnas[$tipo->catalogItemID];
					$old		= isset($mapPrice[$objItem->itemID."-".$tipo->catalogItemID]) ? $mapPrice[$objItem->itemID."-".$tipo->catalogItemID] : null;
					$oldPrecio	= $old ? (float)$old->price : 0;
					$oldPorc	= $old ? (float)$old->percentage : 0;
					$oldCom		= $old ? (float)$old->percentageCommision : 0;

					$valAbs		= $cols["abs"]		!== null ? $this->priceNumero($fila["datos"][$cols["abs"]])		: null;
					$valPorc	= $cols["porc"]		!== null ? $this->priceNumero($fila["datos"][$cols["porc"]])		: null;
					$valCom		= $cols["comision"]	!== null ? $this->priceNumero($fila["datos"][$cols["comision"]])	: null;

					if($valAbs === false || $valPorc === false || $valCom === false){
						$errorProducto = "VALORES NO NUMERICOS EN ".strtoupper($tipo->display);
						break;
					}

					$newPrecio	= $oldPrecio;
					$newPorc	= $oldPorc;
					$newCom		= $valCom === null ? $oldCom : $valCom;
					$origen		= "";

					//El valor manda: si cambio el precio se recalcula el %
					if($valAbs !== null && round($valAbs,2) != round($oldPrecio,2)){
						$newPrecio	= round($valAbs,2);
						$newPorc	= $this->pricePorcentaje($newPrecio,$costo);
						$origen		= "Valor";
					}
					//Si no cambio el precio pero si el %, se calcula el precio sobre el costo
					else if($valPorc !== null && round($valPorc,4) != round($oldPorc,4)){
						if($costo == 0){
							$errorProducto = "NO SE PUEDE CALCULAR EL PRECIO ".strtoupper($tipo->display)." POR % PORQUE EL COSTO ES 0, escriba el precio en valor";
							break;
						}
						$newPorc	= $valPorc;
						$newPrecio	= round($costo * (100 + $valPorc) / 100,2);
						$origen		= "%";
					}

					if($newPrecio < 0){
						$errorProducto = "EL PRECIO ".strtoupper($tipo->display)." NO PUEDE SER NEGATIVO";
						break;
					}

					$cambioCom = round($newCom,4) != round($oldCom,4);
					if($origen == "" && $cambioCom)
					$origen = "Comision";

					//Sin cambios y el registro ya existe: no se toca
					if($origen == "" && $old)
					continue;

					$operaciones[] = array(
						"old"		=> $old,
						"tipo"		=> $tipo,
						"origen"	=> $origen,
						"data"		=> array(
							"price"					=> $newPrecio,
							"percentage"			=> $newPorc,
							"percentageCommision"	=> $newCom
						),
						"oldPrecio"	=> $oldPrecio,
						"oldPorc"	=> $oldPorc,
						"oldCom"	=> $oldCom
					);
				}

				if($errorProducto != ""){
					$errores[] = array("linea" => $fila["linea"], "codigo" => $codigo, "nombre" => $objItem->name, "mensaje" => $errorProducto);
					continue;
				}

				//2. Guardar
				$cambiosProducto = 0;
				foreach($operaciones as $op){
					if($op["old"]){
						$this->Price_Model->update_app_posme($companyID,$listPriceID,$objItem->itemID,$op["tipo"]->catalogItemID,$op["data"]);
					}
					else{
						$dataPrice					= $op["data"];
						$dataPrice["companyID"]		= $companyID;
						$dataPrice["listPriceID"]	= $listPriceID;
						$dataPrice["itemID"]		= $objItem->itemID;
						$dataPrice["typePriceID"]	= $op["tipo"]->catalogItemID;
						$this->Price_Model->insert_app_posme($dataPrice);
					}

					//Registro nuevo sin valores: se crea para completar la lista pero no es un cambio de precio
					if($op["origen"] == "")
					continue;

					$cambiosProducto++;
					$detalle[] = array(
						"codigo"		=> $objItem->itemNumber,
						"nombre"		=> $objItem->name,
						"tipo"			=> $op["tipo"]->display,
						"costo"			=> $costo,
						"precioAnterior"=> $op["oldPrecio"],
						"precioNuevo"	=> $op["data"]["price"],
						"porcAnterior"	=> $op["oldPorc"],
						"porcNuevo"		=> $op["data"]["percentage"],
						"comAnterior"	=> $op["oldCom"],
						"comNueva"		=> $op["data"]["percentageCommision"],
						"origen"		=> $op["origen"]
					);
				}

				if($cambiosProducto > 0){
					$productosActualizados++;
					$preciosCambiados += $cambiosProducto;
				}
				else{
					$productosSinCambio++;
				}
			}

			$db->transComplete();
			if($db->transStatus() === false)
			throw new \Exception("NO SE PUDIERON GUARDAR LOS PRECIOS, no se aplico ningun cambio");

			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'data'    => array(
					"archivo"				=> $fileName,
					"filasLeidas"			=> $filasLeidas,
					"productosActualizados"	=> $productosActualizados,
					"preciosCambiados"		=> $preciosCambiados,
					"productosSinCambio"	=> $productosSinCambio,
					"productosConError"		=> count($errores),
					"detalle"				=> $detalle,
					"errores"				=> $errores
				)
			));//--finjson
		}
		catch(\Exception $ex){
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}
	function save($mode=""){
			$mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
		
			//Guardar o Editar Registro						
			if($mode == "new"){
				$this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				$this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_invoice_price/add');
				exit;
			}
			
	}
	function add(){ 
	
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
			
			}	
			 
			
			$dataView							= null;
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;			
			$dataView["useMobile"]				= $dataSession["user"]->useMobile;
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_list_price","statusID",$companyID,$branchID,$roleID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_invoice_price/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_invoice_price/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_invoice_price/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}	
			
    }
	function index($dataViewID = null){	
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
			//Obtener el componente Para mostrar la lista de Precios
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_list_price");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_list_price' NO EXISTE...");
			
			
			$esMovil = $dataSession["user"]->useMobile == 1 || $this->request->getUserAgent()->isMobile();
			$dataViewID = filter_var(helper_SegmentsByIndex($this->uri->getSegments(), 1, $dataViewID), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE); // Entero o null: el valor llega a SQL en Company_Data_View_Model

			//Vista por defecto
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $esMovil ? $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow") : $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $esMovil ? $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow") : $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_invoice_price/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_invoice_price/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_invoice_price/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}
	}
	
}
?>