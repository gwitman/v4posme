<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_warehouse extends _BaseController {
	
    
	function edit(){ 
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
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_EDIT);			
			}	
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			 			
			 	
			
			
			//Redireccionar datos
			
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$warehouseID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$warehouseID))
			{ 
				$this->response->redirect(base_url()."/".'app_inventory_warehouse/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objWarehouse"]			= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);
			$datView["objListBranch"]			= $this->Branch_Model->getByCompany($companyID);
			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_warehouse","statusID",$datView["objWarehouse"]->statusID,$companyID,$branchID,$roleID);
			$datView["objListTypeWarehouse"]	= $this->core_web_catalog->getCatalogAllItem("tb_warehouse","typeWarehouse",$companyID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_warehouse/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_warehouse/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_warehouse/edit_script',$datView);//--finview
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
			$warehouseID 		= /*inicio get post*/ $this->request->getPost("warehouseID");				
			
			if((!$companyID && !$warehouseID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL COMPROBANTE
			$obj 			= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			//VALIDAR PRODUCTOS EN LAS BODEGAS
			$count 				= $this->Itemwarehouse_Model->warehouseIsEmpty($companyID,$warehouseID);
			if($count > 0)
			throw new \Exception("la bodega no puede ser eliminada, hay productos con existencias mayor que 0");	
			
			//Eliminar el Registro
			$this->Warehouse_Model->delete_app_posme($companyID,$warehouseID);
			
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
	function save($method = NULL){
		 $method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
		 try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtNumber","Codigo","required|min_length[5]|max_length[5]");    
			$this->validation->setRule("txtName","Nombre","required");
			$this->validation->setRule("txtBranchID","Sucursal","required");
			 	
			
			//Validar Formulario
			if($this->validation->withRequest($this->request)->run() != true){
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_inventory_warehouse/add');	
				throw new \Exception("DETENER EJECUCION");
						
			}
			
			//Nuevo Registro
			if( $method == "new"){
					
					//PERMISO SOBRE LA FUNCION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
					
					}					
					
					$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
					$db=db_connect();
					$db->transStart();
					//Crear Cuenta
					$obj["companyID"]			= $dataSession["user"]->companyID;
					$obj["branchID"] 			= /*inicio get post*/ $this->request->getPost("txtBranchID");
					$obj["number"] 				= /*inicio get post*/ $this->request->getPost("txtNumber");
					$obj["emailResponsability"]	= /*inicio get post*/ $this->request->getPost("txtEmailResponsability");
					$obj["name"] 				= /*inicio get post*/ $this->request->getPost("txtName");				 
					$obj["address"] 			= /*inicio get post*/ $this->request->getPost("txtAddress");				 
					$obj["isActive"] 			= true;
					$obj["statusID"] 			= /*inicio get post*/ $this->request->getPost("txtStatusID");
					$obj["typeWarehouse"] 		= /*inicio get post*/ $this->request->getPost("txtTypeWarehouse");
					$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
					
					//Validar Codigo de Bodega
					$objWarehouse				= $this->Warehouse_Model->getByCode($obj["companyID"],$obj["number"]);
					if($objWarehouse)
					{
						$this->core_web_notification->set_message(true,"Ya hay una bodega existente con ese codigo");
						$this->response->redirect(base_url()."/".'app_inventory_warehouse/add');	
						exit;
					}
					
					//Ingresar
					$warehouseID				= $this->Warehouse_Model->insert_app_posme($obj);
					$companyID 					= $obj["companyID"];
					
					
					//Completar Transaccion
					if($db->transStatus() !== false){
						$db->transCommit();						
						$this->core_web_notification->set_message(false,SUCCESS);
						$this->response->redirect(base_url()."/".'app_inventory_warehouse/edit/companyID/'.$companyID."/warehouseID/".$warehouseID);						
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
						$this->response->redirect(base_url()."/".'app_inventory_warehouse/add');	
					}
					 
			} 
			//Editar Registro
			else {
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
					
					//PERMISO SOBRE EL REGISTRO
					$companyID 			= $dataSession["user"]->companyID;
					$warehouseID 		= /*inicio get post*/ $this->request->getPost("txtWarehouseID");
					$branchID 			= /*inicio get post*/ $this->request->getPost("txtBranchID");
					$oldWarehouse 		= $this->Warehouse_Model->get_rowByPK($companyID,$warehouseID);
					if ($resultPermission 	== PERMISSION_ME && ($oldWarehouse->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
					//Actualizar Bodega
					$db=db_connect();
					$db->transStart();					
					$obj["number"] 				= /*inicio get post*/ $this->request->getPost("txtNumber");
					$obj["address"] 			= /*inicio get post*/ $this->request->getPost("txtAddress");
					$obj["name"] 				= /*inicio get post*/ $this->request->getPost("txtName");
					$obj["emailResponsability"]	= /*inicio get post*/ $this->request->getPost("txtEmailResponsability");
					$obj["statusID"] 			= /*inicio get post*/ $this->request->getPost("txtStatusID");
					$obj["typeWarehouse"] 		= /*inicio get post*/ $this->request->getPost("txtTypeWarehouse");
					$obj["branchID"] 			= /*inicio get post*/ $this->request->getPost("txtBranchIDDescription");
					
					//Validar Codigo de Bodega
					$objWarehouse		= $this->Warehouse_Model->getByCode($companyID,$obj["number"]);
					if($objWarehouse)
					{
						if($objWarehouse->warehouseID != $oldWarehouse->warehouseID){
							$this->core_web_notification->set_message(true,"Ya hay una bodega existente con ese codigo");
							$this->response->redirect(base_url()."/".'app_inventory_warehouse/edit/companyID/'.$companyID."/warehouseID/".$warehouseID);
							exit;
						}
					}
					
					//Actualizar Bodega
					$result 			= $this->Warehouse_Model->update_app_posme($companyID,$branchID,$warehouseID,$obj);
				
					if($db->transStatus() !== false){
						$db->transCommit();
						$this->core_web_notification->set_message(false,SUCCESS);
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
					}
					$this->response->redirect(base_url()."/".'app_inventory_warehouse/edit/companyID/'.$companyID."/warehouseID/".$warehouseID);
					
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
	function add(){ 
	
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
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ALL_INSERT);			
			
			}	
			
			//Cargar Libreria
			 
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$objData["objListBranch"] 			= $this->Branch_Model->getByCompany($companyID);
			$objData["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_warehouse","statusID",$companyID,$branchID,$roleID);
			$objData["objListTypeWarehouse"]	= $this->core_web_catalog->getCatalogAllItem("tb_warehouse","typeWarehouse",$companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_warehouse/news_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_warehouse/news_body',$objData);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_warehouse/news_script');//--finview
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
		
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}				
			
			//Obtener el componente Para mostrar la lista de Bodegas
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_warehouse");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_warehouse' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_warehouse/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_warehouse/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_warehouse/list_script');//--finview
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