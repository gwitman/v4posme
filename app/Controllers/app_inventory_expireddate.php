<?php

namespace App\Controllers;

use Exception;

class app_inventory_expireddate extends _BaseController
{
	function index($dataViewID = null)
	{
		try {
			//AUTENTICADO
			if (!$this->core_web_authentication->isAuthenticated())
				throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession        = $this->session->get();

			//PERMISO SOBRE LA FUNCTION
			if (APP_NEED_AUTHENTICATION == true) {

				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission           = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission       == PERMISSION_NONE)
					throw new Exception(NOT_ACCESS_FUNCTION);
			}

			$objComponent        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_expireddate");
			if (!$objComponent) {
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_expireddate' NO EXISTE...");
			}

			$companyID                          = $dataSession["user"]->companyID;
			$userID                             = $dataSession["user"]->userID;

			$objListWarehouses 					= $this->Warehouse_Model->getByCompany($companyID);
			
			foreach ($objListWarehouses as $warehouse) 
			{
				if (!file_exists(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $warehouse->warehouseID)) {
					mkdir(PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $objComponent->componentID . "/component_item_" . $warehouse->warehouseID, 0700, true);
				}
			}

			$dataView["objListWarehouse"]       = $this->Userwarehouse_Model->getRowByUserID($companyID, $userID);
			$dataView["objComponent"]			= $objComponent;

			//Renderizar Resultado
			$dataSession["notification"]        = $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]             = $this->core_web_notification->get_message();
			$dataSession["head"]                = /*--inicio view*/ view('app_inventory_expireddate/news_head', $dataView); //--finview
			$dataSession["body"]                = /*--inicio view*/ view('app_inventory_expireddate/news_body', $dataView); //--finview
			$dataSession["script"]              = /*--inicio view*/ view('app_inventory_expireddate/news_script', $dataView); //--finview
			$dataSession["footer"]			    = "";
			return view("core_masterpage/default_masterpage", $dataSession); //--finview-r	
		} catch (Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}

	function download()
	{
		try {
			if (!$this->core_web_authentication->isAuthenticated()) {
				throw new Exception(USER_NOT_AUTENTICATED);
			}

			$dataSession    = $this->session->get();

			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}

			$warehouseID			= $this->request->getPost("warehouseID");
			$branchID 				= $dataSession["user"]->branchID;
			$companyID 				= $dataSession["user"]->companyID;

			$objComponent			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_expireddate");
			if (!$objComponent)
			{
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_expireddate' NO EXISTE...");
			}

			if ($warehouseID == "" || !$warehouseID)
			{
				throw new Exception("NO SE ENCONTRO LA BODEGA");
			}

			$objListExpiredItems		= $this->Item_Warehouse_Expired_Model->get_expiredItemByWarehouseID($companyID, $warehouseID);
			if(count($objListExpiredItems) == 0)
			{
				throw new Exception("NO SE ENCONTRARON REGISTROS EXPIRADOS EN LA BODEGA");
			}
			//GENERAR EXCEL
			$objParameterDeliminterCsv	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
			$objParameterDeliminterCsv	= $objParameterDeliminterCsv->value;

			$fileName					= 'expired_items.csv';
			$path        				= "./resource/file_company/company_{$companyID}/component_{$objComponent->componentID}/component_item_{$warehouseID}/{$fileName}";
			$header						= array_keys(get_object_vars($objListExpiredItems[0]));
			array_pop($header); // Eliminar la columna de itemID

			if(($file = fopen($path,'w')) == false)
			{
				throw new Exception("NO SE PUDO ABRIR EL ARCHIVO {$fileName}");
			}

			fputcsv($file, $header, $objParameterDeliminterCsv);
			if ($objListExpiredItems) {

				foreach ($objListExpiredItems as $row) 
				{
					$rowData = array_values(get_object_vars($row));

					array_pop($rowData); // Eliminar la columna de itemID
					
					$rowData = array_combine($header, $rowData);
					
					fputcsv($file, $rowData, $objParameterDeliminterCsv);
				}
			}

			fclose($file);
			//FIN DE GENERACION DEL EXCEL

			return $this->response->setJSON([
				'error'		=> false,
				'message'	=> SUCCESS,
				'link' 		=> base_url("/resource/file_company/company_{$companyID}/component_{$objComponent->componentID}/component_item_{$warehouseID}/{$fileName}/")
			]);

		} catch (Exception $ex) {
			return $this->response->setJSON(array(
				'error'   		=> true,
				'message' 		=> $ex->getLine() . " " . $ex->getMessage(),
			)); //--finjson
		}
	}

	function process()
	{
		try {
			if (!$this->core_web_authentication->isAuthenticated()) {
				throw new Exception(USER_NOT_AUTENTICATED);
			}

			$dataSession    = $this->session->get();

			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new Exception(NOT_ALL_INSERT);
			}

			$warehouseID			= $this->request->getPost("warehouseID");
			$companyID 				= $dataSession["user"]->companyID;

			$objComponent			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_expireddate");
			if (!$objComponent)
			{
				throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_expireddate' NO EXISTE...");
			}

			if ($warehouseID == "" || !$warehouseID)
			{
				throw new Exception("NO SE ENCONTRO LA BODEGA");
			}

			$objListExpiredItems		= $this->Item_Warehouse_Expired_Model->get_expiredItemByWarehouseID($companyID, $warehouseID);
			$objListExpiredItemsFromCSV	= [];
			
			if(count($objListExpiredItems) == 0)
			{
				throw new Exception("NO SE ENCONTRARON REGISTROS EXPIRADOS EN LA BODEGA");
			}

			//GENERAR EXCEL
			$objParameterDeliminterCsv	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
			$objParameterDeliminterCsv	= $objParameterDeliminterCsv->value;

			$fileName					= 'expired_items.csv';
			$path        				= "./resource/file_company/company_{$companyID}/component_{$objComponent->componentID}/component_item_{$warehouseID}/{$fileName}";
			$header						= array_keys(get_object_vars($objListExpiredItems[0]));
			array_pop($header); // Eliminar la columna de itemID
			
			if(!file_exists($path))
			{
				throw new Exception("NO SE ENCONTRO EL ARCHIVO {$fileName}");
			}

			if(($file = fopen($path,'r')) == false)
			{
				throw new Exception("NO SE PUDO ABRIR EL ARCHIVO {$fileName}");
			}

			//1- LEER REGISTROS DEL EXCEL
			$csvRowCounter = 0;

			while (($line = fgets($file)) !== false)
			{
				$line 		= trim($line);													// Limpiar comillas dobles extras y salto de línea
				$line 		= preg_replace('/^"|"\s*$/', '', $line);	// quitar comillas al inicio y final
				$line 		= str_replace('""', '"', $line); 				// reemplazar dobles comillas por una

				$row		= explode($objParameterDeliminterCsv, $line);
				$rowLength 	= count($row);

				if ($rowLength != count($header)) 
				{
					throw new Exception("EL ARCHIVO {$fileName} NO TIENE EL FORMATO CORRECTO");
				}

				$rowData 	= array_combine($header, $row);

				if ($csvRowCounter != 0) {
					array_push($objListExpiredItemsFromCSV, $rowData);
				}

				$csvRowCounter++;
			}

			fclose($file);
			//---FIN DE LECTURA DEL EXCEL

			if(empty($objListExpiredItemsFromCSV))
			{
				throw new Exception("NO SE ENCONTRARON REGISTROS EN EL ARCHIVO {$fileName}");
			}

			//Crear array indexado por campo Sistema
			$csvItemsIndexed 	= [];
			foreach ($objListExpiredItemsFromCSV as $csvItem) 
			{
				$key					= trim($csvItem["Sistema"], '"'); 
				array_push($csvItemsIndexed, $csvItem);
			}

			//2- OBTENER LOS itemID DE LOS REGISTROS PROVENIENTES DEL EXCEL
			$counter 				= 0;
			$itemIDList				= [];
			$objListItemsToInsert 	= [];
			$keyBackup				= 0;	
			$csvIndexedItemsLength	= count($csvItemsIndexed);

			foreach ($objListExpiredItems as $expiredItem) 
			{
				$key = $expiredItem->Sistema;

				if($keyBackup == $key)
				{
					continue;
				}

				$keyBackup = $key;

				for($i = 0; $i < $csvIndexedItemsLength; $i++)
				{
					if ($key == $csvItemsIndexed[$i]["Sistema"]) 
					{
						$csvItem 						= $csvItemsIndexed[$i];
						$itemToInsert 					= [];
						$itemToInsert["warehouseID"] 	= $expiredItem->Codigo_Bodega;
						$itemToInsert["itemID"]      	= $expiredItem->itemID;
						$itemToInsert["companyID"]   	= $companyID;
						$itemToInsert["lote"]        	= "";
						$itemToInsert["quantity"]    	= $csvItem["Cantidad"];
						$itemToInsert["dateExpired"] 	= date("Y-m-d", strtotime(trim($csvItem["Expiracion"], '"')));
				
						array_push($objListItemsToInsert, $itemToInsert);
					}
				}

				$itemIDList[$counter] = $expiredItem->itemID;
				$counter++;			
			}
			

			$db = db_connect();
			$db->transStart();

			//3- ELIMINAR LOS REGISTROS QUE ESTAN EN EL CSV
			$this->Item_Warehouse_Expired_Model->delete_byItemIDInList($companyID, $warehouseID, $itemIDList);

			//4- INSERTAR NUEVAMENTE LOS REGISTROS DEL CSV PERO YA ACTUALIZADOS
			foreach($objListItemsToInsert as $item)
			{
				$this->Item_Warehouse_Expired_Model->insert_app_posme($item);
			}

			if ($db->transStatus() !== false) {
				$db->transCommit();
			
			} else {
				$db->transRollback();
				throw new Exception("ERROR AL ACTUALIZAR EL ARCHIVO {$fileName}");
			}

			return $this->response->setJSON([
				'error'		=> false,
				'message'	=> SUCCESS,
				'link' 		=> base_url("/resource/file_company/company_{$companyID}/component_128/{$fileName}")
			]);

		} catch (Exception $ex) {
			return $this->response->setJSON(array(
				'error'   		=> true,
				'message' 		=> $ex->getLine() . " " . $ex->getMessage(),
				'warehouseID'	=> $warehouseID
			)); //--finjson
		}
	}
}
