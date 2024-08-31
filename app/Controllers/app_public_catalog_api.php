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
			$keyFieldValue	= $this->request->getGet('keyFildValue');
			$fieldValueBD	= "";
			
			
            $filter1	= $this->request->getGet('filter1',FILTER_SANITIZE_STRING);
            $filter2	= $this->request->getGet('filter2',FILTER_SANITIZE_STRING);
            $filter3	= $this->request->getGet('filter3',FILTER_SANITIZE_STRING);
            $filter4	= $this->request->getGet('filter4',FILTER_SANITIZE_STRING);
			$filter5	= $this->request->getGet('filter5',FILTER_SANITIZE_STRING);
			$filter6	= $this->request->getGet('filter6',FILTER_SANITIZE_STRING);
			$filter7	= $this->request->getGet('filter7',FILTER_SANITIZE_STRING);
			$filter8	= $this->request->getGet('filter8',FILTER_SANITIZE_STRING);
			$filter9	= $this->request->getGet('filter9',FILTER_SANITIZE_STRING);
			$filter10	= $this->request->getGet('filter10',FILTER_SANITIZE_STRING);
			$filter11	= $this->request->getGet('filter11',FILTER_SANITIZE_STRING);
			$filter12	= $this->request->getGet('filter12',FILTER_SANITIZE_STRING);
			$filter13	= $this->request->getGet('filter13',FILTER_SANITIZE_STRING);
			$filter14	= $this->request->getGet('filter14',FILTER_SANITIZE_STRING);
			$filter15	= $this->request->getGet('filter15',FILTER_SANITIZE_STRING);
			$filter16	= $this->request->getGet('filter16',FILTER_SANITIZE_STRING);
			$filter17	= $this->request->getGet('filter17',FILTER_SANITIZE_STRING);
			$filter18	= $this->request->getGet('filter18',FILTER_SANITIZE_STRING);
			$filter19	= $this->request->getGet('filter19',FILTER_SANITIZE_STRING);
			$filter20	= $this->request->getGet('filter20',FILTER_SANITIZE_STRING);
			$filter21	= $this->request->getGet('filter21',FILTER_SANITIZE_STRING);
			$filter22	= $this->request->getGet('filter22',FILTER_SANITIZE_STRING);
			$filter23	= $this->request->getGet('filter23',FILTER_SANITIZE_STRING);
			$filter24	= $this->request->getGet('filter24',FILTER_SANITIZE_STRING);


			//Aplicacion de filtro antes de buscar en base de datos
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_RentaMobile" &&  $filter13 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_RentaFijo" &&  $filter11 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_RguMobile" &&  $filter11 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_RguFijos" &&  $filter3 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_PlanMobileAdicional1" &&  $filter12 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_GestionMovileAdicional1" &&  $filter11 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_LineasMobileAdicionales" &&  $filter6 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_TipoMigracion" &&  $filter9 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_OperadorDonante" &&  $filter8 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_TipoDeLinea" &&  $filter7 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_PlanMobile" &&  $filter6 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_GestionMobile" &&  $filter5 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_VelocidadInternet" &&  $filter4 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_Tecnologia" &&  $filter3 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_Servicio" &&  $filter2 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			if($keyFieldValue == "convierten_tb_transaction_master_campos_cascada_detalles_servicion_ServicioExistente" &&  $filter1 == "" )
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
                    [0=>'ND']
                );
			}
			
			
			
			$objPC		= $this->Public_Catalog_Model->
						asObject()->
						where("systemName",$catalogName)->
						find();
									
            if(count($objPC)==0){
                return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
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
				if (isset($filter5)){
					$objPCDResult= $objPCDResult->where("reference5",$filter5);
				}
				if (isset($filter6)){
					$objPCDResult= $objPCDResult->where("reference6",$filter6);
				}
				if (isset($filter7)){
					$objPCDResult= $objPCDResult->where("reference7",$filter7);
				}
				if (isset($filter8)){
					$objPCDResult= $objPCDResult->where("reference8",$filter8);
				}
				if (isset($filter9)){
					$objPCDResult= $objPCDResult->where("reference9",$filter9);
				}
				if (isset($filter10)){
					$objPCDResult= $objPCDResult->where("reference10",$filter10);
				}
				if (isset($filter11)){
					$objPCDResult= $objPCDResult->where("reference11",$filter11);
				}
				if (isset($filter12)){
					$objPCDResult= $objPCDResult->where("reference12",$filter12);
				}
				if (isset($filter13)){
					$objPCDResult= $objPCDResult->where("reference13",$filter13);
				}
				if (isset($filter14)){
					$objPCDResult= $objPCDResult->where("reference14",$filter14);
				}
				if (isset($filter15)){
					$objPCDResult= $objPCDResult->where("reference15",$filter15);
				}
				if (isset($filter16)){
					$objPCDResult= $objPCDResult->where("reference16",$filter16);
				}
				if (isset($filter17)){
					$objPCDResult= $objPCDResult->where("reference17",$filter17);
				}
				if (isset($filter18)){
					$objPCDResult= $objPCDResult->where("reference18",$filter18);
				}
				if (isset($filter19)){
					$objPCDResult= $objPCDResult->where("reference19",$filter19);
				}
				if (isset($filter20)){
					$objPCDResult= $objPCDResult->where("reference20",$filter20);
				}
				if (isset($filter21)){
					$objPCDResult= $objPCDResult->where("reference21",$filter21);
				}
				if (isset($filter22)){
					$objPCDResult= $objPCDResult->where("reference22",$filter22);
				}
				if (isset($filter23)){
					$objPCDResult= $objPCDResult->where("reference23",$filter23);
				}
				if (isset($filter24)){
					$objPCDResult= $objPCDResult->where("reference24",$filter24);
				}
				
				
				
				$objPCDResult	= $objPCDResult->distinct()->select(''.$fieldValueBD.' as  label ')->find();								
				
				
				
				//$db 	= \Config\Database::connect();				
				//$sql 	= $db->getLastQuery()->__toString();
				//log_message("error",print_r($sql,true));
				
				
				if (count($objPCDResult)==0){
					return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(                    
						[0=>'ND']
					);
				}else{
					return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(
						$objPCDResult
					);
				}
				
			}
			else 
			{
				return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(                    
                    [0=>'ND']
                );
			}
			
									
							
					

        }
        catch(Exception $ex){
            return $this->response->setHeader('Access-Control-Allow-Origin', '*')->setJSON(array(
                'error'   			=> true,
                'message' 			=> $ex->getMessage(),
                'result'	 	=> []
            ));
        }
    }
	
}
?>