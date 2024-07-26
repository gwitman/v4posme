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
			$objCompany		= $this->Company_Model->get_rowByPK(APP_COMPANY);
            $catalogName	= $this->request->getGet('catalogName');
			$catalogValue	= $this->request->getGet('fieldValue');
			$fieldValueBD	= "";
			
			
            $filter1	= $this->request->getGet('filter1',FILTER_SANITIZE_STRING);
            $filter2	= $this->request->getGet('filter2',FILTER_SANITIZE_STRING);
            $filter3	= $this->request->getGet('filter3',FILTER_SANITIZE_STRING);
            $filter4	= $this->request->getGet('filter4',FILTER_SANITIZE_STRING);

			$objPC		= $this->Public_Catalog_Model->
						asObject()->
						where("systemName",$catalogName)->
						find();
									
            if(count($objPC)==0){
                return $this->response->setJSON(
                    [0=>'ND']
                );
            }
			
			//Obtener la priemra fila
			$objPCDResult 			= $this->Public_Catalog_Detail_Model->asObject()->	
									where("sequence",1)->
                                    where("publicCatalogID",$objPC[0]->publicCatalogID)->find();
									
			if($objPCDResult)
			{
				//Obtener el nombre del campo en la tabla en la base de dtos
				foreach($objPCDResult as $value)
				{
					// Obtener un array asociativo de todas las propiedades del objeto
					$propiedades = get_object_vars($value);

					// Recorrer todas las propiedades y verificar si alguna tiene el valor 'abc'
					foreach ($propiedades as $nombrePropiedad => $valorPropiedad) {
						if ($valorPropiedad === $catalogValue) {
							$fieldValueBD = $nombrePropiedad;
							break;
						}
					}
				}
				
				
				
				
				$objPCDResult 			= $this->Public_Catalog_Detail_Model->asObject()->	
										where("sequence !=",1)->
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
				$objPCDResult=$objPCDResult->distinct()->select(''.$fieldValueBD.' as  label ')->find();

				if (count($objPCDResult)==0){
					return $this->response->setJSON(                    
						[0=>'ND']
					);
				}else{
					return $this->response->setJSON(
						$objPCDResult
					);
				}
				
			}
			else 
			{
				return $this->response->setJSON(                    
                    [0=>'ND']
                );
			}
			exit;
									
							
					

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