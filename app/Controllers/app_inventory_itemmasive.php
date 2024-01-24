<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_itemmasive extends _BaseController {
	
       
	
	
	function index($dataViewID = null){	
	try{ 
		
			$dataSession		= $this->session->get();
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			
			
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_masive");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item_masive' NO EXISTE...");
			
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFild($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFild($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			$objParameterMasive	= $this->core_web_parameter->getParameter("ITEM_PRINTER_BARCODE_MASIVE",$this->session->get('user')->companyID);
			$objParameterMasive	= $objParameterMasive->value;	
			
			//Renderizar Resultado
			$dataView["objParameterMasive"]	= $objParameterMasive;			
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_itemmasive/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_inventory_itemmasive/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_itemmasive/list_script',$dataView);//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
	function popup_add_prinercode($listItem=""){
		$listItem = helper_SegmentsByIndex($this->uri->getSegments(),1,$listItem);	
			
			
		//Papel para codigo de barra, de medida
		//2 pulgada x 1pulgada
		
		
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
			
			
			$listItem					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"listItem");//--finuri
			$listItem 					= urldecode($listItem);			
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Cargar Libreria			
			 						
			 		
			
			$listItem	= explode("|",$listItem);
			
			
			
			//Get Component
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");						
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);						
			
			//Componetne de Item
			$objComponentItem			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
				
			
			//Actualizar lso codigos de barra
			$objListaItemPrinter = array();			
			foreach($listItem as $itemWitCantidad)
			{
				$itemWitCantidadTmp	= explode("-",$itemWitCantidad);
				$itemID 	= $itemWitCantidadTmp[0];
				$cantidad 	= $itemWitCantidadTmp[1];			
				//Obtener Lista de Productos		
					
					
				
				$objItem 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
					
				if($objItem == null)
				{}		
				else{	
					$objItem->barCode	= $objItem->barCode == "" ? "B".$objItem->itemNumber  : $objItem->barCode;
					$objNewItem["barCode"] = $objItem->barCode;
					$row_affected 	= $this->Item_Model->update_app_posme($companyID,$objItem->itemID,$objNewItem);
					
					$directory=  PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItem->componentID."/component_item_".$objItem->itemID;
					$pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItem->componentID."/component_item_".$objItem->itemID."/barcode.jpg";
					
					if(!file_exists($directory))
					mkdir($directory, 0700);
					
					$this->core_web_barcode->generate( $pathFileCodeBarra, $objItem->barCode, "40", "horizontal", "code128", false, 3 );
					for($i = 0; $i < $cantidad ; $i++){
						$objItemTempory = $this->Item_Model->get_rowByPK($companyID,$itemID);
						array_push($objListaItemPrinter,$objItemTempory);
					}
				}
			}
					
					
				
			$data["objComponentItem"] = $objComponentItem;
			$data["objComponent"] = $objComponent;
			$data["objListaItem"] = $objListaItemPrinter;
			return view("app_inventory_itemmasive/printer_barcode",$data);//--finview-r			
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function printer_barcode_58ml_direct($listItem="")
	{
		
		$listItem = helper_SegmentsByIndex($this->uri->getSegments(),1,$listItem);	
		
		
		//try{
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			$listItem					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"listItem");//--finuri
			$listItem 					= urldecode($listItem);			
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Cargar Libreria		
			$listItem	= explode("|",$listItem);
			
			//Get Component
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");						
			$objCompany 		= $this->Company_Model->get_rowByPK($companyID);						
			
			//Componetne de Item
			$objComponentItem			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
				
			
			
			$objTypePriceValue					= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE",$companyID);
			$objTypePriceValue					= $objTypePriceValue->value;
			
			$objTypeListPriceValue				= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$objTypeListPriceValue				= $objTypeListPriceValue->value;
			
			
			//Actualizar lso codigos de barra
			$objListaItemPrinter = array();			
			foreach($listItem as $itemWitCantidad)
			{
				$itemWitCantidadTmp	= explode("-",$itemWitCantidad);
				$itemID 	= $itemWitCantidadTmp[0];
				$cantidad 	= $itemWitCantidadTmp[1];			
				//Obtener Lista de Productos		
					
					
				
				$objItem 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
					
				if($objItem == null)
				{}		
				else{	
					$objItem->barCode	= $objItem->barCode == "" ? "B".$objItem->itemNumber  : $objItem->barCode;
					$objNewItem["barCode"] = $objItem->barCode;
					$row_affected 	= $this->Item_Model->update_app_posme($companyID,$objItem->itemID,$objNewItem);
					
					$directory				=  PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItem->componentID."/component_item_".$objItem->itemID;
					$pathFileCodeBarra 		= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItem->componentID."/component_item_".$objItem->itemID."/barcode.jpg";
					$pathFileCodeBarraPng 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentItem->componentID."/component_item_".$objItem->itemID."/barcode.png";
					
					if(!file_exists($directory))
					mkdir($directory, 0700);
					
					//$this->core_web_barcode->generate( $pathFileCodeBarra, $objItem->barCode, "40", "horizontal", "code128", false, 3 );
					$this->core_web_barcode->generate( $pathFileCodeBarra, $objItem->barCode, "40", "horizontal", "code128", false, 2 );
					if(!file_exists($pathFileCodeBarraPng))
					{
						if (!copy($pathFileCodeBarra, $pathFileCodeBarraPng)) {
							echo "Error al copiar $fichero...\n";
						}
					}
					
					for($i = 0; $i < $cantidad ; $i++){
						$objItemTempory 			= $this->Item_Model->get_rowByPK($companyID,$itemID);
						$objItemTempory->itemPrice 	= $this->Price_Model->get_rowByPK($companyID, $objTypeListPriceValue, $itemID, $objTypePriceValue /*Precio publico*/)->price;
						array_push($objListaItemPrinter,$objItemTempory);
					}
				}
			}
					
					
				
			$dataView["objComponentItem"] 			= $objComponentItem;
			$dataView["objComponent"] 				= $objComponent;
			$dataView["objListaItem"] 				= $objListaItemPrinter;
			$dataView["objListItemID"]				= array_column($objListaItemPrinter,"itemID");
			$dataView["objListItemIDDistinct"]		= array_unique($dataView["objListItemID"]);
			
			
			
			//obtener nombre de impresora por defecto
			if($dataView["objListaItem"])
			{
					
				foreach($dataView["objListItemIDDistinct"] as $itemID )
				{
					
					$listItemFilter = array_filter($dataView["objListaItem"] , function($k) use ($itemID)
					{										
										return $k->itemID == $itemID;
					});
					
					$objParameterPrinterName = $this->core_web_parameter->getParameter("INVENTORY_BARCODE_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
					$objParameterPrinterName = $objParameterPrinterName->value;					
					$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
					$this->core_web_printer_direct->executePrinter58mmBarCodeList($dataView["objComponentItem"],$listItemFilter);
				}
			}
			
			
			
		//}
		//catch(\Exception $ex)
		//{			
			//exit($ex->getMessage());
		//}	
	}
	//facturacino imprimir directamente en impresora, formato de ticket
	function printer_barcode_58ml_direct_localhost($listItem="")
	{
		
		$listItem 	= helper_SegmentsByIndex($this->uri->getSegments(),1,$listItem);	
		$listItem	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"listItem");//--finuri
		$listItem 	= urldecode($listItem);		
		
		
		//try{
			
		
			
			//Cargar Libreria	
			$companyID 			= 2;
			$componentItemID 	= 33;			
			$listItem			= explode("|",$listItem);
			
			
			
			//Actualizar lso codigos de barra
			$objListaItemPrinter = array();			
			foreach($listItem as $itemWitCantidad)
			{
				
				$itemWitCantidadTmp	= explode("-",$itemWitCantidad);				
				$itemID 		= $itemWitCantidadTmp[0];
				$cantidad 		= $itemWitCantidadTmp[1];			
				$itemNumber		= $itemWitCantidadTmp[2];			
				$name			= $itemWitCantidadTmp[3];			
				$itemBarCode	= $itemWitCantidadTmp[4];			
				$itemPrice		= $itemWitCantidadTmp[5];			
				
				//Obtener Lista de Productos		
				$directory				=  PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentItemID."/component_item_".$itemID;
				$pathFileCodeBarra 		= PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentItemID."/component_item_".$itemID."/barcode.jpg";
				$pathFileCodeBarraPng 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$componentItemID."/component_item_".$itemID."/barcode.png";
					
				if(!file_exists($directory))
				mkdir($directory, 0700);
					
				//$this->core_web_barcode->generate( $pathFileCodeBarra, $itemBarCode, "40", "horizontal", "code128", false, 3 );
				$this->core_web_barcode->generate( $pathFileCodeBarra, $itemBarCode, "40", "horizontal", "code128", false, 2 );
				if(!file_exists($pathFileCodeBarraPng))
				{
					if (!copy($pathFileCodeBarra, $pathFileCodeBarraPng)) {
						echo "Error al copiar $fichero...\n";
					}
				}
					
				for($i = 0; $i < $cantidad ; $i++){	
					$objItemTempory["itemID"] 		= $itemID;
					$objItemTempory["cantidad"] 	= 1;
					$objItemTempory["itemNumber"] 	= $itemNumber;
					$objItemTempory["name"] 		= $name;
					$objItemTempory["itemBarCode"] 	= $itemBarCode;
					$objItemTempory["itemPrice"] 	= $itemPrice;
					array_push($objListaItemPrinter,$objItemTempory);
				}
				
			}
					
					
				
			$dataView["objComponentItem"] 			= $componentItemID;			
			$dataView["objListaItem"] 				= $objListaItemPrinter;
			$dataView["objListItemID"]				= array_column($objListaItemPrinter,"itemID");
			$dataView["objListItemIDDistinct"]		= array_unique($dataView["objListItemID"]);
			
			
			//obtener nombre de impresora por defecto
			if($dataView["objListaItem"])
			{
					
				foreach($dataView["objListItemIDDistinct"] as $itemID )
				{
					
					$listItemFilter = array_filter($dataView["objListaItem"] , function($k) use ($itemID)
					{										
										return $k["itemID"] == $itemID;
					});
					
					
					
					$objParameterPrinterName = $this->core_web_parameter->getParameter("INVENTORY_BARCODE_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
					$objParameterPrinterName = $objParameterPrinterName->value;					
					$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
					$this->core_web_printer_direct->executePrinter58mmBarCodeListLocalHost($dataView["objComponentItem"],$listItemFilter);
				}
			}
			
			
			
		//}
		//catch(\Exception $ex)
		//{			
			//exit($ex->getMessage());
		//}	
	}
	
}
?>