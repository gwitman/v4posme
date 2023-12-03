<?php
//posme:2023-02-27
namespace App\Controllers;
class app_catalog_api extends _BaseController {
	
     
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
}
?>