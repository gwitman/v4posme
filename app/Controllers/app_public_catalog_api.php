<?php
//posme:2023-02-27
namespace App\Controllers;
class app_public_catalog_api extends _BaseController {
	
    
	
	
	
	function getPublicCatalogDetail()
	{
		try{  
		
		
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
			
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$publicCatalogDetailID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"publicCatalogDetailID");//--finuri
			
			//Obtener el catalogo detaile
			$objPCD					= $this->Public_Catalog_Detail_Model->
											asObject()->																		
											where("publicCatalogDetailID",$publicCatalogDetailID)->
											find();
			$objPCDResult 			= $this->Public_Catalog_Detail_Model->asObject()->
											where("isActive",1)->											
											where("parentName",$objPCD[0]->name)->
											find();
		
			
			//Obtener Resultados.			
			return $this->response->setJSON(array(
				'error'   			=> false,
				'message' 			=> SUCCESS,			
				'objGridView'	 	=> $objPCDResult
			));//--finjson			
			
		}
		catch(\Exception $ex){
			echo $ex->getMessage();
		}
	}
	
		
	
}
?>