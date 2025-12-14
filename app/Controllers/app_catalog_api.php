<?php
//posme:2023-02-27
namespace App\Controllers;
class app_catalog_api extends _BaseController {
	
     
	function getCatalogItemByParentCatalogItemID()
	{
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$catalogItemID 			= /*inicio get post*/ $this->request->getPost("catalogItemID");		
			$tableName 				= /*inicio get post*/ $this->request->getPost("tableName");		
			$fieldName 				= /*inicio get post*/ $this->request->getPost("fieldName");		
			if((!$companyID) || (!$catalogItemID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			//Obtener todos los departamentos del pais
			$catalogItems = $this->core_web_catalog->getCatalogAllItem_Parent($tableName,$fieldName,$companyID,$catalogItemID);
			
			
			return $this->response->setJSON(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItems
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	
	function getCatalogItemByState(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$catalogItemID 			= /*inicio get post*/ $this->request->getPost("catalogItemID");		
			if((!$companyID) || (!$catalogItemID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			//Obtener todos los departamentos del pais
			$catalogItems = $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","stateID",$companyID,$catalogItemID);
			
			
			return $this->response->setJSON(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItems
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	
	function getCatalogItemByCity(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$catalogItemID 			= /*inicio get post*/ $this->request->getPost("catalogItemID");		
			if((!$companyID) || (!$catalogItemID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			//Obtener todos los departamentos del pais
			$catalogItems = $this->core_web_catalog->getCatalogAllItem_Parent("tb_customer","cityID",$companyID,$catalogItemID);
			
			
			return $this->response->setJSON(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItems
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}

    function getCatalogByName($name)
    {
        try{
            //Validar Authentication
            if(!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();
            $companyID          = $dataSession["user"]->companyID;
            $objPCD				= $this->core_web_catalog->getCatalogAllItemByNameCatalogo($name, $companyID);

            //Obtener Resultados.
            return $this->response->setJSON(array(
                'error'   			=> false,
                'message' 			=> SUCCESS,
                'data'	 	        => $objPCD
            ));//--finjson

        }
        catch(Exception $ex){
            return $this->response->setJSON(array(
                'error'   			=> true,
                'message' 			=> $ex->getMessage(),
                'data'	 	        => []
            ));//--finjson
        }
    }

	function getCatalogItemByEndosos()
    {
        try{
            //Validar Authentication
            if(!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

            $catalogID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"catalogID");//--finuri
            $reference1	        = /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"reference1");//--finuri
            $objPCD				= $this->Catalog_Item_Model->get_rowByCatalogIDAndReference1($catalogID, $reference1,$dataSession["company"]->flavorID);
			
			if(!$objPCD)
			$objPCD				= $this->Catalog_Item_Model->get_rowByCatalogIDAndReference1($catalogID, $reference1,0);

            //Obtener Resultados.
            return $this->response->setJSON(array(
                'error'   			=> false,
                'message' 			=> SUCCESS,
                'data'	 	        => $objPCD
            ));//--finjson

        }
        catch(Exception $ex){
            return $this->response->setJSON(array(
                'error'   			=> true,
                'message' 			=> $ex->getMessage(),
                'data'	 	        => []
            ));//--finjson
        }
    }
	
	function getCatalogItemMesasByZone()
	{
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$catalogItemID 			= /*inicio get post*/ $this->request->getPost("catalogItemZoneID");		
			if((!$companyID) || (!$catalogItemID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			//Obtener todos los departamentos del pais
			$catalogItems 			= $this->core_web_catalog->getCatalogAllItem_Parent("tb_transaction_master_info_billing","mesaID",$companyID,$catalogItemID);			
			$catalogItemResponse 	= [];
			if($catalogItems)
			{
				foreach($catalogItems as $mesa)
				{
					//obtener la factura en estado registrada de estamesa			
					$minInvoice = $this->Transaction_Master_Model->get_MesasByCatalogItemID([-1,$mesa->catalogItemID]);
					$arrayMesa						= [];
					$arrayMesa["catalogItemID"]		= $mesa->catalogItemID;
					$arrayMesa["col"]				= $mesa->reference2;
					$arrayMesa["fila"]				= $mesa->reference1;
					$arrayMesa["display"]			= $mesa->display;
					if($minInvoice)
					{
						$arrayMesa["estado"]					= "ocupada";
						$arrayMesa["transactionMasterID"]		= $minInvoice[0]->reference2; /*id de la factura*/
						
					}
					else
					{
						$arrayMesa["estado"]					= "libre";
						$arrayMesa["transactionMasterID"]		= 0;
					}
					
					$catalogItemResponse[] = $arrayMesa;
					
				}
			}
			
			return $this->response->setJSON(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItemResponse
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
}
?>