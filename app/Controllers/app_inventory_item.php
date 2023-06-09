<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_item extends _BaseController {
	
       
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
									
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$itemID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri
			$callback		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"callback");//--finuri
			$callback		= $callback === "" ?  "false" : $callback;
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$itemID))
			{ 
				$this->response->redirect(base_url()."/".'app_inventory_item/add');	
			} 		
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponentProvider							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponentProvider)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			$objParameterTypePreiceDefault			= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE",$companyID);
			$objParameterTypePreiceDefault			= $objParameterTypePreiceDefault->value;
			$objParameterListPreiceDefault			= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$objParameterListPreiceDefault			= $objParameterListPreiceDefault->value;
			//Obtener Informacion
			$dataView["objComponent"] 				= $objComponent;
			$dataView["componentProviderID"]		= $objComponentProvider->componentID;
			$dataView["objListConcept"]				= $this->Company_Component_Concept_Model->get_rowByComponentItemID($companyID,$objComponent->componentID,$itemID);
			$dataView["objItem"]	 				= $this->Item_Model->get_rowByPK($companyID,$itemID);
			$dataView["objItemSku"]	 				= $this->Item_Sku_Model->get_rowByItemID($itemID);
			$dataView["objItemWarehouse"]			= $this->Itemwarehouse_Model->get_rowByItemID($companyID,$itemID);			
			$dataView["objListWarehouse"]			= $this->Warehouse_Model->getByCompany($companyID);
			$dataView["objListProvider"]			= $this->Provideritem_Model->get_rowByItemID($companyID,$itemID);
			$dataView["objListInventoryCategory"]	= $this->Itemcategory_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_item","statusID",$dataView["objItem"]->statusID,$companyID,$branchID,$roleID);			
			$dataView["objListFamily"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","familyID",$companyID);
			$dataView["objListUnitMeasure"]			= $this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID);
			$dataView["objListDisplay"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","displayID",$companyID);
			$dataView["objListDisplayUnitMeasure"]	= $this->core_web_catalog->getCatalogAllItem("tb_item","displayUnitMeasureID",$companyID);
			$dataView["objListTypePreice"]			= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			$dataView["objParameterTypePreiceDefault"]			= $objParameterTypePreiceDefault;
			$dataView["objParameterListPreiceDefault"]			= $objParameterListPreiceDefault;
			$dataView["callback"]					= $callback;
			$dataView["objListPriceItem"]			= $this->Price_Model->get_rowByItemID($companyID,$dataView["objParameterListPreiceDefault"],$itemID);
					
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_item/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_item/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_item/edit_script',$dataView);//--finview
			$dataSession["footer"]			= "";				
			if($callback == "false")
				return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			else
				return view("core_masterpage/default_popup",$dataSession);//--finview-r	
			
			
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
			
			//Nuevo Registro	
			if( $method == "new"  )
			{
					
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
					$callback  								= /*inicio get post*/ $this->request->getPost("txtCallback"); 
					$objItem["companyID"]					= $dataSession["user"]->companyID;
					$objItem["branchID"] 					= $dataSession["user"]->branchID;					
					$objItem["inventoryCategoryID"] 		= /*inicio get post*/ $this->request->getPost("txtInventoryCategoryID");
					$objItem["familyID"] 					= /*inicio get post*/ $this->request->getPost("txtFamilyID");
					$objItem["itemNumber"] 					= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_item",0);
					$objItem["barCode"] 					= /*inicio get post*/ $this->request->getPost("txtBarCode") == "" ? "B".$objItem["itemNumber"].""  : /*inicio get post*/ $this->request->getPost("txtBarCode");
					$objItem["name"] 						= /*inicio get post*/ $this->request->getPost("txtName");
					$objItem["description"] 				= /*inicio get post*/ $this->request->getPost("txtDescription");
					$objItem["unitMeasureID"] 				= /*inicio get post*/ $this->request->getPost("txtUnitMeasureID");
					$objItem["displayID"] 					= /*inicio get post*/ $this->request->getPost("txtDisplayID");
					$objItem["capacity"] 					= /*inicio get post*/ $this->request->getPost("txtCapacity");
					$objItem["displayUnitMeasureID"] 		= /*inicio get post*/ $this->request->getPost("txtDisplayUnitMeasureID");
					$objItem["defaultWarehouseID"] 			= /*inicio get post*/ $this->request->getPost("txtDefaultWarehouseID");
					$objItem["quantity"] 					= 0;
					$objItem["quantityMax"] 				= /*inicio get post*/ $this->request->getPost("txtQuantityMax");
					$objItem["quantityMin"] 				= /*inicio get post*/ $this->request->getPost("txtQuantityMin");
					$objItem["cost"] 						= 0;
					$objItem["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
					$objItem["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
					$objItem["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
					$objItem["isPerishable"] 				= /*inicio get post*/ $this->request->getPost("txtIsPerishable");
					$objItem["isServices"] 					= /*inicio get post*/ $this->request->getPost("txtIsServices");
					$objItem["isInvoiceQuantityZero"] 		= /*inicio get post*/ $this->request->getPost("txtIsInvoiceQuantityZero");
					$objItem["factorBox"] 					= /*inicio get post*/ $this->request->getPost("txtFactorBox");
					$objItem["factorProgram"] 				= /*inicio get post*/ $this->request->getPost("txtFactorProgram");
					$objItem["isActive"] 					= 1;
					$this->core_web_auditoria->setAuditCreated($objItem,$dataSession,$this->request);
					
					$itemID								= $this->Item_Model->insert_app_posme($objItem);
					$companyID 							= $objItem["companyID"];
					//Crear la Carpeta para almacenar los Archivos del Item
					mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID, 0700);
					
					
					//Guardar el Detalle de las Bodegas
					$objListWarehouseID					= /*inicio get post*/ $this->request->getPost("txtDetailWarehouseID");
					$objListWarehouseQuantityMax		= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMax");
					$objListWarehouseQuantityMain		= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMin");
					
				
					if($objListWarehouseID)
					foreach($objListWarehouseID as $key => $value){
						$objItemWarehouse["companyID"] 			= $companyID;
						$objItemWarehouse["branchID"] 			= $objItem["branchID"];
						$objItemWarehouse["warehouseID"] 		= $value;
						$objItemWarehouse["itemID"] 			= $itemID;
						$objItemWarehouse["quantity"] 			= 0;
						$objItemWarehouse["quantityMax"] 		= $objListWarehouseQuantityMax[$key];
						$objItemWarehouse["quantityMin"] 		= $objListWarehouseQuantityMain[$key];
						$this->Itemwarehouse_Model->insert_app_posme($objItemWarehouse);
					}
					
					//Guardar Detalle de sku
					$objListCatalogItemSKU					= /*inicio get post*/ $this->request->getPost("txtDetailSkuCatalogItemID");
					$objListCatalogItemSKUValue				= /*inicio get post*/ $this->request->getPost("txtDetailSkuValue");
					if($objListCatalogItemSKU)
					foreach($objListCatalogItemSKU as $key => $value){
						$objSku["itemID"] 			= $itemID;
						$objSku["catalogItemID"] 	= $value;
						$objSku["value"] 			= $objListCatalogItemSKUValue[$key];
						$this->Item_Sku_Model->insert_app_posme($objSku);
					}
					
					
					//Guardar proveedor por defecto
					$objParameterProviderDefault	= $this->core_web_parameter->getParameter("INVENTORY_ITEM_PROVIDER_DEFAULT",$companyID);
					$objParameterProviderDefault 	= $objParameterProviderDefault->value;
					$objTmpProvider					= [];
					$objTmpProvider["companyID"]	= $companyID;
					$objTmpProvider["branchID"]		= $objItem["branchID"];
					$objTmpProvider["itemID"]		= $itemID;
					$objTmpProvider["entityID"]		= $objParameterProviderDefault;
					$this->Provideritem_Model->insert_app_posme($objTmpProvider);
						
						
					//Ingresar la configuracion de precios
					//por defecto con 0% de utilidad
					$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
					$listPriceID 	= $objParameterPriceDefault->value;
					$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
						
					foreach($objTipePrice as $price)
					{				
					
							
							
							
							$typePriceID			= 0;							
							$objItemTmp				= $this->Item_Model->get_rowByCode($companyID,
								$objItem["itemNumber"]);
								
							
								
							$typePriceID			= $price->catalogItemID;
							
							//Insert register to price
							$dataPrice["companyID"] 	= $companyID;
							$dataPrice["listPriceID"] 	= $listPriceID;
							$dataPrice["itemID"] 		= $objItemTmp->itemID;
							$dataPrice["typePriceID"] 	= $typePriceID;							
							$dataPrice["price"] 		= 0;
							$dataPrice["percentage"] 	= 0;
									
							$this->Price_Model->insert_app_posme($dataPrice);
					}
					
					//Generar la Imagen del Codigo de Barra
					$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID.
					"/component_".$objComponent->componentID."/component_item_".$itemID."/barcode.jpg";
					
					
					
					$this->core_web_barcode->generate( $pathFileCodeBarra, $objItem["barCode"], "80", "horizontal", "code128", false, 1 );					
					
					//Fin				
					if($db->transStatus() !== false){
						$db->transCommit();						
						$this->core_web_notification->set_message(false,SUCCESS);
						$this->response->redirect(base_url()."/".'app_inventory_item/edit/companyID/'.$companyID."/itemID/".$itemID."/callback/".$callback);						
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
						$this->response->redirect(base_url()."/".'app_inventory_item/add');	
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
					$companyID 			= $dataSession["user"]->companyID;
					$itemID				= /*inicio get post*/ $this->request->getPost("txtItemID");
					$objOldItem 		= $this->Item_Model->get_rowByPK($companyID,$itemID);
					if ($resultPermission 	== PERMISSION_ME && ($objOldItem->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
					//PERMISO PUEDE EDITAR EL REGISTRO
					if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$objOldItem->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new \Exception(NOT_WORKFLOW_EDIT);					
					
					
					
					//Crear la Carpeta para almacenar los Archivos del Item
					$directoryItem = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID;
					if(!file_exists($directoryItem))
					mkdir( $directoryItem,0700);
					
					$db=db_connect();
					$db->transStart();	
					$callback  	= /*inicio get post*/ $this->request->getPost("txtCallback"); 									
					if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$objOldItem->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					{
						//Actualizar Cuenta								
						$objNewItem["inventoryCategoryID"] 			= /*inicio get post*/ $this->request->getPost("txtInventoryCategoryID");
						$objNewItem["familyID"] 					= /*inicio get post*/ $this->request->getPost("txtFamilyID");												
						$objNewItem["barCode"] 						= /*inicio get post*/ $this->request->getPost("txtBarCode") == "" ? "B".$objOldItem->itemNumber  : /*inicio get post*/ $this->request->getPost("txtBarCode");
						$objNewItem["name"] 						= /*inicio get post*/ $this->request->getPost("txtName");
						$objNewItem["description"] 					= /*inicio get post*/ $this->request->getPost("txtDescription");
						$objNewItem["unitMeasureID"] 				= /*inicio get post*/ $this->request->getPost("txtUnitMeasureID");
						$objNewItem["displayID"] 					= /*inicio get post*/ $this->request->getPost("txtDisplayID");
						$objNewItem["capacity"] 					= /*inicio get post*/ $this->request->getPost("txtCapacity");
						$objNewItem["displayUnitMeasureID"] 		= /*inicio get post*/ $this->request->getPost("txtDisplayUnitMeasureID");
						$objNewItem["defaultWarehouseID"] 			= /*inicio get post*/ $this->request->getPost("txtDefaultWarehouseID");						
						$objNewItem["quantityMax"] 					= /*inicio get post*/ $this->request->getPost("txtQuantityMax");
						$objNewItem["quantityMin"] 					= /*inicio get post*/ $this->request->getPost("txtQuantityMin");						
						$objNewItem["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
						$objNewItem["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
						$objNewItem["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$objNewItem["isPerishable"] 				= /*inicio get post*/ $this->request->getPost("txtIsPerishable");
						$objNewItem["isServices"] 					= /*inicio get post*/ $this->request->getPost("txtIsServices");
						$objNewItem["isInvoiceQuantityZero"] 		= /*inicio get post*/ $this->request->getPost("txtIsInvoiceQuantityZero");
						$objNewItem["factorBox"] 					= /*inicio get post*/ $this->request->getPost("txtFactorBox");
						$objNewItem["factorProgram"] 				= /*inicio get post*/ $this->request->getPost("txtFactorProgram");
						//Actualizar Objeto
						$row_affected 	= $this->Item_Model->update_app_posme($companyID,$itemID,$objNewItem);
			
						//Guardar el detalle de Conceptos
						$this->Company_Component_Concept_Model->deleteWhereComponentItemID($companyID,$objComponent->componentID,$itemID);
						$objListConcept						= /*inicio get post*/ $this->request->getPost("txtDetailConceptName");
						if($objListConcept)
						foreach($objListConcept as $key => $value){
							$objTmpConcept						= [];
							$objTmpConcept["companyID"]			= $companyID;
							$objTmpConcept["componentID"]		= $objComponent->componentID;
							$objTmpConcept["componentItemID"]	= $itemID;
							$objTmpConcept["name"]				= $value;
							$objTmpConcept["valueIn"]			= /*inicio get post*/ $this->request->getPost("txtDetailConceptValueIn")[$key];
							$objTmpConcept["valueOut"]			= /*inicio get post*/ $this->request->getPost("txtDetailConceptValueOut")[$key];
							$this->Company_Component_Concept_Model->insert_app_posme($objTmpConcept);
						}
						
						//Guardar el detalle de Proveedores
						$this->Provideritem_Model->deleteWhereItemID($companyID,$itemID);
						$objListProviderID					= /*inicio get post*/ $this->request->getPost("txtProviderEntityID");
						if($objListProviderID)
						foreach($objListProviderID as $key => $value){
							$objTmpProvider					= [];
							$objTmpProvider["companyID"]	= $objOldItem->companyID;
							$objTmpProvider["branchID"]		= $objOldItem->branchID;
							$objTmpProvider["itemID"]		= $itemID;
							$objTmpProvider["entityID"]		= $value;
							$this->Provideritem_Model->insert_app_posme($objTmpProvider);
						}
						
						
						//Guardar Detalle de sku
						$this->Item_Sku_Model->delete_app_posme($itemID);
						$objListCatalogItemSKU					= /*inicio get post*/ $this->request->getPost("txtDetailSkuCatalogItemID");
						$objListCatalogItemSKUValue				= /*inicio get post*/ $this->request->getPost("txtDetailSkuValue");
						if($objListCatalogItemSKU)
						foreach($objListCatalogItemSKU as $key => $value){
							$objSku["itemID"] 			= $itemID;
							$objSku["catalogItemID"] 	= $value;
							$objSku["value"] 			= $objListCatalogItemSKUValue[$key];
							$this->Item_Sku_Model->insert_app_posme($objSku);
						}
						
						//Guardar el Detalle las Bodegas
						$objListDetailWarehouseID			= /*inicio get post*/ $this->request->getPost("txtDetailWarehouseID");
						$objListDetailWarehouseQuantityMax	= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMax");
						$objListDetailWarehouseQuantityMin	= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMin");
					
						//Eliminar las Bodegas que no estan
						$this->Itemwarehouse_Model->deleteWhereIDNotIn($companyID,$itemID,$objListDetailWarehouseID);
						
						if($objListDetailWarehouseID)
						foreach($objListDetailWarehouseID as $key => $value){
							$objWarehouseDetail["quantityMax"] 			= $objListDetailWarehouseQuantityMax[$key];
							$objWarehouseDetail["quantityMin"] 			= $objListDetailWarehouseQuantityMin[$key];
							$warehouseID 								= $objListDetailWarehouseID[$key];
							$objOldItemWarehouse 						= $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);
							if($objOldItemWarehouse){
								$this->Itemwarehouse_Model->update_app_posme($companyID,$itemID,$warehouseID,$objWarehouseDetail);
							}
							else{								
								$objWarehouseDetail["companyID"] 	= $companyID;
								$objWarehouseDetail["warehouseID"] 	= $warehouseID;
								$objWarehouseDetail["itemID"] 		= $itemID;
								$objWarehouseDetail["quantity"] 	= 0;
								$objWarehouseDetail["branchID"] 	= $dataSession["user"]->branchID;
								$this->Itemwarehouse_Model->insert_app_posme($objWarehouseDetail);
							}
						}
						
						
						//Ingresar la configuracion de precios
						//por defecto con 0% de utilidad
						$arrayListPrecioValue 	= /*inicio get post*/ $this->request->getPost("txtDetailTypePriceValue");
						$arrayTypePrecioId 		= /*inicio get post*/ $this->request->getPost("txtDetailTypePriceID");
						$arrayListPrecioID 		= /*inicio get post*/ $this->request->getPost("txtDetailListPriceID");
						$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
						$listPriceID 	= $objParameterPriceDefault->value;
						$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
						
						foreach($arrayTypePrecioId as $key => $price)
						{				
								
								$typePriceID				= 0;	
								$typePriceID				= $arrayTypePrecioId[$key];
								$listPriceID				= $arrayListPrecioID[$key];
								$priceValue					= $arrayListPrecioValue[$key];
								
								
								//Insert register to price
								$dataPrice["companyID"] 	= $companyID;
								$dataPrice["listPriceID"] 	= $listPriceID;
								$dataPrice["itemID"] 		= $itemID;
								$dataPrice["typePriceID"] 	= $typePriceID;
								$dataPrice["price"] 		= $priceValue;
								$dataPrice["percentage"] 	= 0;
										
								$objPrice = $this->Price_Model->get_rowByPK($companyID,$listPriceID,$itemID,$typePriceID);								
								if($objPrice == null )
								{
									$this->Price_Model->insert_app_posme($dataPrice);
								}
								else{
									$this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataPrice);
								}
						}
						
						$messageTmp						= "";
					}
					else{
						$objNewItem["statusID"] 		= /*inicio get post*/ $this->request->getPost("txtStatusID");							
						$row_affected 					= $this->Item_Model->update_app_posme($companyID,$itemID,$objNewItem);
						$messageTmp						= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
					}
					
					
					//Generar la Imagen del Codigo de Barra
					$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID."/barcode.jpg";
					
					
					
					$this->core_web_barcode->generate( $pathFileCodeBarra, $objNewItem["barCode"], "40", "horizontal", "code128", false, 3 );
					
					
					
					if($db->transStatus() !== false){
						$db->transCommit();
						$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
					}
					else{
						$db->transRollback();						
						$this->core_web_notification->set_message(true,$this->db->_error_message());
					}
					$this->response->redirect(base_url()."/".'app_inventory_item/edit/companyID/'.$companyID."/itemID/".$itemID."/callback/".$callback);
					
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
			$callback							= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"callback");//--finuri
			$callback							= $callback === "" ? "false" : $callback;
			
			$objParameterWarehouseDefault	= $this->core_web_parameter->getParameter("INVENTORY_ITEM_WAREHOUSE_DEFAULT",$companyID);
			$warehouseDefault 				= $objParameterWarehouseDefault->value;
			
			$objParameterTypePreiceDefault			= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE",$companyID);
			$objParameterTypePreiceDefault			= $objParameterTypePreiceDefault->value;
			$objParameterListPreiceDefault			= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$objParameterListPreiceDefault			= $objParameterListPreiceDefault->value;
			$dataView["objListWarehouse"]			= $this->Warehouse_Model->getByCompany($companyID);
			$dataView["objListInventoryCategory"]	= $this->Itemcategory_Model->getByCompany($companyID);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_item","statusID",$companyID,$branchID,$roleID);
			$dataView["objListFamily"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","familyID",$companyID);
			$dataView["objListUnitMeasure"]			= $this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID);
			$dataView["objListDisplay"]				= $this->core_web_catalog->getCatalogAllItem("tb_item","displayID",$companyID);
			$dataView["objListDisplayUnitMeasure"]	= $this->core_web_catalog->getCatalogAllItem("tb_item","displayUnitMeasureID",$companyID);
			$dataView["objListTypePreice"]			= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["warehouseDefault"]			= $warehouseDefault;
			
			$dataView["objParameterTypePreiceDefault"]			= $objParameterTypePreiceDefault;
			$dataView["objParameterListPreiceDefault"]			= $objParameterListPreiceDefault;
			$dataView["callback"]								= $callback;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_item/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_item/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_item/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
			if($callback == "false")
				return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			else
				return view("core_masterpage/default_popup",$dataSession);//--finview-r	
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
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
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_item/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_item/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_item/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	function popup_add_concept(){
			
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
		
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_inventory_item/popup_addconcept_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_inventory_item/popup_addconcept_body');//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_inventory_item/popup_addconcept_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r			
			
	}
	
	
	function popup_add_renderimg($companyID="",$componentID="",$itemID=""){
		$companyID = helper_SegmentsByIndex($this->uri->getSegments(),1,$companyID);	
		$componentID = helper_SegmentsByIndex($this->uri->getSegments(),2,$componentID);	
		$itemID = helper_SegmentsByIndex($this->uri->getSegments(),3,$itemID);	
		
		
			
		//Extraer el codigo de barra			
		$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentID."/component_item_".$itemID."/barcode.jpg";
		
		
		$type = 'image/jpg';
		header('Content-Type:'.$type);
		header('Content-Length: ' . filesize($pathFileCodeBarra));
		readfile($pathFileCodeBarra);
		exit;
	}
	
}
?>