<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_datasheet extends _BaseController {
	
       
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
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			
			
			
			//Redireccionar datos
			
			$companyID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$itemDataSheetID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemDataSheetID");//--finuri
			$branchID 			= $dataSession["user"]->branchID;
			$roleID 			= $dataSession["role"]->roleID;			
			if((!$companyID || !$itemDataSheetID))
			{ 
				$this->response->redirect(base_url()."/".'app_inventory_datasheet/add');	
			} 		
			
			//Obtener el componente de Item
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener Informacion
			$dataView["componentItemID"] 			= $objComponent->componentID;
			$dataView["objItemDataSheet"]	 		= $this->Item_Data_Sheet_Model->get_rowByPK($itemDataSheetID);
			$dataView["objItemDataSheetDetail"]	 	= $this->Item_Data_Sheet_Detail_Model->get_rowByItemDataSheet($itemDataSheetID);
			$dataView["objItem"]					= $this->Item_Model->get_rowByPK($companyID,$dataView["objItemDataSheet"]->itemID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_item_data_sheet","statusID",$dataView["objItemDataSheet"]->statusID,$companyID,$branchID,$roleID);			
			//
			////Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_datasheet/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_datasheet/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_datasheet/edit_script',$dataView);//--finview
			$dataSession["footer"]			= "";				
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			$itemID 			= /*inicio get post*/ $this->request->getPost("itemID");				
			
			if((!$companyID && !$itemID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL ITEM
			$obj 			= $this->Item_Model->get_rowByPK($companyID,$itemID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$obj->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			//VALIDAR CANTIDAD
			if($obj->quantity > 0)
			throw new \Exception("EL REGISTRO NO PUEDE SER ELIMINADO, SU CANTIDAD ES MAYOR QUE  0");			
			//Eliminar el Registro
			$this->Item_Model->delete_app_posme($companyID,$itemID);
					
			
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
	function searchItem(){
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
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			
			//Nuevo Registro
			$itemNumber 			= /*inicio get post*/ $this->request->getPost("itemNumber");
			
			
			if(!$itemNumber){
					throw new \Exception(NOT_PARAMETER);			
			} 			
			$obj 	= $this->Item_Model->get_rowByCode($dataSession["user"]->companyID,$itemNumber);	
			
			if(!$obj)
			throw new \Exception("NO SE ENCONTRO EL REGISTRO");	
			
			
			
			return $this->response->setJSON(array(
				'error'   			=> false,
				'message' 			=> SUCCESS,
				'companyID' 		=> $obj->companyID,
				'itemID'			=> $obj->itemID
			));//--finjson
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}
    function save($method = NULL){
		 $method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			 
			 
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			
			
			
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$objComponentItemDataSheet				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_data_sheet");
			if(!$objComponentItemDataSheet)
			throw new \Exception("EL COMPONENTE 'tb_item_data_sheet' NO EXISTE...");
			$companyID 	= $dataSession["user"]->companyID;
			//Nuevo Registro	
			if( $method == "new"  ){
					
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
					
					$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
					//Ingresar Cuenta					
					$db=db_connect();
					$db->transStart();					
					$objItemDataSeet["itemID"]				= /*inicio get post*/ $this->request->getPost("txtItemID");
					$objItemDataSeet["version"]				= /*inicio get post*/ $this->request->getPost("txtVersion");
					$objItemDataSeet["statusID"]			= /*inicio get post*/ $this->request->getPost("txtStatusID");
					$objItemDataSeet["name"]				= /*inicio get post*/ $this->request->getPost("txtName");
					$objItemDataSeet["description"]			= /*inicio get post*/ $this->request->getPost("txtDescription");
					$objItemDataSeet["isActive"]			= 1;
					$this->core_web_auditoria->setAuditCreated($objItemDataSeet,$dataSession,$this->request);
					$itemDataSheetID						= $this->Item_Data_Sheet_Model->insert_app_posme($objItemDataSeet);
					
					//Crear la Carpeta para almacenar los Archivos del Item
					$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItemDataSheet->componentID."/component_item_".$itemDataSheetID;	
					if (!file_exists($documentoPath))
					{
						mkdir($documentoPath, 0755);
						chmod($documentoPath, 0755);
					}
					
					if($db->transStatus() !== false){
						$db->transCommit();						
						$this->core_web_notification->set_message(false,SUCCESS);
						$this->response->redirect(base_url()."/".'app_inventory_datasheet/edit/companyID/'.$companyID."/itemDataSheetID/".$itemDataSheetID);						
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$db->_error_message());
						$this->response->redirect(base_url()."/".'app_inventory_datasheet/add');	
					}
					 
			} 
			//Editar Registro
			else {
					
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
					 
					//PERMISO SOBRE EL REGISTRO
					$itemDataSheetID		= /*inicio get post*/ $this->request->getPost("txtItemDataSheetID");
					$objOldItemDataSheet	= $this->Item_Data_Sheet_Model->get_rowByPK($itemDataSheetID);
					if ($resultPermission 	== PERMISSION_ME && ($objOldItemDataSheet->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
					//PERMISO PUEDE EDITAR EL REGISTRO
					if(!$this->core_web_workflow->validateWorkflowStage("tb_item_data_sheet","statusID",$objOldItemDataSheet->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new \Exception(NOT_WORKFLOW_EDIT);					
					
					$db=db_connect();
					$db->transStart();						
					if(!$this->core_web_workflow->validateWorkflowStage("tb_item_data_sheet","statusID",$objOldItemDataSheet->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					{
						//Actualizar Cuenta						
						$objNewItemDataSeet["itemID"]				= /*inicio get post*/ $this->request->getPost("txtItemID");
						$objNewItemDataSeet["version"]				= /*inicio get post*/ $this->request->getPost("txtVersion");
						$objNewItemDataSeet["statusID"]				= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$objNewItemDataSeet["name"]					= /*inicio get post*/ $this->request->getPost("txtName");
						$objNewItemDataSeet["description"]			= /*inicio get post*/ $this->request->getPost("txtDescription");
						$objNewItemDataSeet["isActive"]				= 1;
						
						//Actualizar Objeto
						$row_affected 	= $this->Item_Data_Sheet_Model->update_app_posme($itemDataSheetID,$objNewItemDataSeet);
						$messageTmp						= "";
						//Actualizar Detalle
						$arrayItemID				= /*inicio get post*/ $this->request->getPost("txtDetailItemID");
						$arrayItemDataSheetDetailID	= /*inicio get post*/ $this->request->getPost("txtDetailItemDataSheetDetailID");
						$arrayQuantity				= /*inicio get post*/ $this->request->getPost("txtDetailQuantity");
						
						
						
						
						//Eliminar los registros que no estan
						$this->Item_Data_Sheet_Detail_Model->deleteWhereIDNotIn($itemDataSheetID,$arrayItemDataSheetDetailID);
						if (!empty($arrayItemID)) {
							
							foreach ($arrayItemID as $key => $value) {
								$itemID 								= $value;
								$dataSheetDetailID						= $arrayItemDataSheetDetailID[$key];
								$quantity								= $arrayQuantity[$key];
								$objItemDataSheetDetail					= $this->Item_Data_Sheet_Detail_Model->get_rowByPKItemID($itemDataSheetID,$itemID);
								
								
								if ($dataSheetDetailID == 0 && !$objItemDataSheetDetail) {
									$dataNewItemDataSheetDetail = [];
									$dataNewItemDataSheetDetail["itemDataSheetID"] 	= $itemDataSheetID;
									$dataNewItemDataSheetDetail["itemID"] 			= $itemID;
									$dataNewItemDataSheetDetail["quantity"] 		= $quantity;
									$dataNewItemDataSheetDetail["relatedItemID"] 	= 0;
									$dataNewItemDataSheetDetail["isActive"] 		= true;
									
									$reId = $this->Item_Data_Sheet_Detail_Model->insert_app_posme($dataNewItemDataSheetDetail);
								}
								if ($dataSheetDetailID == 0 && $objItemDataSheetDetail) {
									$dataNewItemDataSheetDetail = [];
									$dataNewItemDataSheetDetail["quantity"] 		= $quantity;
									$dataNewItemDataSheetDetail["relatedItemID"] 	= 0;
									$dataNewItemDataSheetDetail["isActive"] 		= true;									
									$reId = $this->Item_Data_Sheet_Detail_Model->update_app_posme($objItemDataSheetDetail->itemDataSheetDetailID,$dataNewItemDataSheetDetail);
								}
								else{
									$dataNewItemDataSheetDetail = [];
									$dataNewItemDataSheetDetail["itemID"] 	= $itemID;
									$dataNewItemDataSheetDetail["quantity"] = $quantity;
									$dataNewItemDataSheetDetail["isActive"] = true;
									$reId = $this->Item_Data_Sheet_Detail_Model->update_app_posme($dataSheetDetailID,$dataNewItemDataSheetDetail);
								}
							}
						}
					}
					else{
						$objNewItemDataSheet["statusID"] 	= /*inicio get post*/ $this->request->getPost("txtStatusID");							
						$row_affected 						= $this->Item_Data_Sheet_Model->update_app_posme($itemDataSheetID,$objNewItemDataSheet);
						$messageTmp							= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
					}
					
					
					if($db->transStatus() !== false){
						$db->transCommit();
						$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$db->error()["message"]);
					}
					
					$this->response->redirect(base_url()."/".'app_inventory_datasheet/edit/companyID/'.$companyID."/itemDataSheetID/".$itemDataSheetID);
					
			} 
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
						
			//Obtener el componente de Item
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_item_data_sheet","statusID",$companyID,$branchID,$roleID);
			$dataView["componentItemID"] 		= $objComponent->componentID;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_datasheet/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_datasheet/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_datasheet/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}	
			
    }
	
	function index($dataViewID = null)
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
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_data_sheet");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item_data_sheet' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_datasheet/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_datasheet/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_datasheet/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>