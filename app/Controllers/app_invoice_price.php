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
			$filePrice 								= /*inicio get post*/ $this->request->getPost("txtFilePrice");//--fin peticion get o post 
			
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
				$objListPrice["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->List_Price_Model->update_app_posme($companyID,$listPriceID);
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
				
				
				if($filePrice != ""){
					
					$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$listPriceID;			
					$path 	= $path.'/'.$filePrice;
					
					if (!file_exists($path))
					throw new \Exception("NO EXISTE EL ARCHIVO PARA IMPORTAR LOS PRECIOS, RECUERDE QUE EL ARCHIVO DEBE TENER LA EXTENSION  EJEMPLO <b>".$filePrice.".csv</b> ");
				
				
					
					$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
					$characterSplie = $objParameter->value;
					
					$this->Price_Model->delete_app_posme($companyID,$listPriceID);
					$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);					
					$fila 			= 0;
					$this->csvreader->separator = $characterSplie;
					$table 			= $this->csvreader->parse_file($path); 
					if($table)
					foreach ($table as $row) 
					{
						
						
						$fila++;
						$codigo 		= $row["Codigo"];
						$description 	= $row["Descripcion"];
						$typePriceID	= 0;
						$priceID 		= 0;						
						foreach($objTipePrice as $price){									
							$typePriceID			= 0;
							$priceID 				= 0;
							$objItem				= $this->Item_Model->get_rowByCode($companyID,$codigo);
							$typePriceID			= $price->catalogItemID;							
							$precieValueAbs			= $row["".$price->catalogItemID."-".strtoupper($price->display)."-abs"];
							$comisionValueAbs		= $row["".$price->catalogItemID."-".strtoupper("COMISION%")."-abs"];
							if($objItem->itemID == 596 ){
								
								
							}
							
							//Insert register to price
							$dataPrice["companyID"] 				= $companyID;
							$dataPrice["listPriceID"] 				= $listPriceID;
							$dataPrice["itemID"] 					= $objItem->itemID;
							$dataPrice["typePriceID"] 				= $typePriceID;							
							$dataPrice["price"] 					= $precieValueAbs;
							$dataPrice["percentageCommision"] 		= $comisionValueAbs;
							
							
							// Validar que price sea numérico
							if (!is_numeric($precieValueAbs)) 
							{								
								//$precieValueAbs 	= substr_count($precieValueAbs, '.') > 1 ? preg_replace('/\.(?=.*\.)/', ',', $precieValueAbs) : $precieValueAbs;
								//$precieValueAbs	= (float) str_replace(',', '', $precieValueAbs);
								throw new \Exception("EL PRODUCTO : ".$objItem->itemNumber." ".$objItem->name."  (bar codigo:".$objItem->barCode.") Las columnas de  valor no son numericas VALOR:". $precieValueAbs. " ó tiene un mal formato." );
							}

						
							// Convertir explícitamente a float
							$precieValueAbs 			= (float)$precieValueAbs;
							$cost           		 	= (float)$objItem->cost;
							$dataPrice["percentage"] 	= $objItem->cost == 0 ? 
															($precieValueAbs / 100) : 
															(((100 * $precieValueAbs) / $objItem->cost) - 100);
									
							$this->Price_Model->insert_app_posme($dataPrice);
						}
					}
				}
				
				//Generar Archivo
				$generateFile = /*inicio get post*/ $this->request->getPost("txtGenerateFile");
				if($generateFile){
					date_default_timezone_set(APP_TIMEZONE); 
					$date 	= date("Y_m_d_H_i_s");
					//Crear la Carpeta para almacenar los Archivos del Cliente
					$path 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$listPriceID;					
					if (!file_exists($path))
					{
						mkdir($path, 0755, true);
						chmod($path, 0755);
					}
					
					$path 	= $path.'/list_price_default_'.$date.'.csv';
					$fp 	= fopen($path, 'w');
					
					//Crear el archivo de precios
					$objListItem 	= $this->Item_Model->get_rowByCompany($companyID);
					$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
					$objParameter	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
					$characterSplie = $objParameter->value;
					$field 		 	= ["Codigo","Descripcion","Costo"];
					foreach($objTipePrice as $price){						
						array_push($field,"".$price->catalogItemID."-".strtoupper($price->display)."-abs");
						array_push($field,"".$price->catalogItemID."-".strtoupper("COMISION%")."-abs");
					}
					
					fputcsv($fp, $field,$characterSplie);
					foreach($objListItem as $item){
						$rowfield = [];
						array_push($rowfield,$item->itemNumber);
						array_push($rowfield,$item->name);
						array_push($rowfield,$item->cost);
					
						foreach($objTipePrice as $price){
							//obtener el precio
							$objPrice 	=	$this->Price_Model->get_rowByPK($companyID,$listPriceID,$item->itemID,$price->catalogItemID);
							$percentage	= 	($objPrice != null ? $objPrice->percentage : 0);
							$price		= 	($objPrice != null ? $objPrice->price : 0);							
							$comision	= 	($objPrice != null ? $objPrice->percentageCommision : 0);							
							array_push($rowfield,$price);
							array_push($rowfield,$comision);
						}
						fputcsv($fp, $rowfield,$characterSplie);
					}			
					fclose($fp);
				}			
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
			
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
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