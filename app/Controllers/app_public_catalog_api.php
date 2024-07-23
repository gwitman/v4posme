<?php
//posme:2023-02-27
namespace App\Controllers;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class app_public_catalog_api extends _BaseController {
	
    
	
	
	
	function getPublicCatalogDetail()
	{
		try{  
		
		
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
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
		catch(Exception $ex){
            return $this->response->setJSON(array(
                'error'   			=> true,
                'message' 			=> $ex->getMessage(),
                'objGridView'	 	=> []
            ));//--finjson
		}
	}

    /**
     * <p>API para filtrar detalle del catalogo publico del cliente por nombre</p>
     * @version 1.0.0.0
     * @return ResponseInterface
     */
    function getPublicCatalogDetailFilter()
    {
        try{
            $catalogName	= $this->request->getPost('catalogName');
            $filter1	= $this->request->getPost('filter1',FILTER_SANITIZE_STRING);
            $filter2	= $this->request->getPost('filter2',FILTER_SANITIZE_STRING);
            $filter3	= $this->request->getPost('filter3',FILTER_SANITIZE_STRING);
            $filter4	= $this->request->getPost('filter4',FILTER_SANITIZE_STRING);

            $objPC					= $this->Public_Catalog_Model->
                                    asObject()->
                                    where("name",$catalogName)->
                                    find();
            if(count($objPC)==0){
                return $this->response->setJSON(array(
                    'error'    => false,
                    'message' => SUCCESS,
                    'result' => [0=>'NO APLICA']
                ));
            }

            $objPCDResult 			= $this->Public_Catalog_Detail_Model->asObject()->
                                    where("publicCatalogID",$objPC[0]->publicCatalogID);

            if (isset($filter1)){
                $objPCDResult= $objPCDResult->where("reference1",$filter1);
            }
            if (isset($filter2)){
                $objPCDResult=$objPCDResult->where("reference2",$filter2);
            }
            if (isset($filter3)){
                $objPCDResult= $objPCDResult->where("reference3",$filter3);
            }
            if (isset($filter4)){
                $objPCDResult= $objPCDResult->where("reference4",$filter4);
            }
            $objPCDResult=$objPCDResult->find();

            if (count($objPCDResult)==0){
                return $this->response->setJSON(array(
                    'error'    => false,
                    'message' => SUCCESS,
                    'result' => [0=>'NO APLICA']
                ));
            }else{
                return $this->response->setJSON(array(
                    'error'   			=> false,
                    'message' 			=> SUCCESS,
                    'result'	 	=> $objPCDResult
                ));
            }
        }
        catch(Exception $ex){
            return $this->response->setJSON(array(
                'error'   			=> true,
                'message' 			=> $ex->getMessage(),
                'result'	 	=> []
            ));
        }
    }
	
}
?>