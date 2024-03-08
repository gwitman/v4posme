<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_api extends _BaseController {
	
    function uploadDataEncuentra24(){
		try{ 
		
			
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
			
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$tocken				= "";
			
			$startOn			= helper_PrimerDiaDelMes();
			$endOn				= helper_UltimoDiaDelMes();
			
						
			//Get Datos
			$query			= "
								select 
									x.`itemID`,
									x.`createdOn`
									,x.`Codigo`
									,x.`Nombre`
									,x.`Pagina Web`
									,x.`Amueblado`
									,x.`Aires`
									,x.`Niveles`
									,x.`Hora de visita`
									,x.`Baños`
									,x.`Habitaciones`
									,x.`Diseño de propiedad`
									,x.`Tipo de casa`
									,x.`Proposito`
									,x.`Moneda`
									,x.`Fecha de enlistamiento`
									,x.`Fecha de actualizacion`
									,x.`Precio Venta`
									,x.`Precio Renta`
									,x.`Disponible`
									,x.`Area de contruccion M2`
									,x.`Area de terreno V2`
									,x.`ID Encuentra 24`
									,x.`Baño de servicio`
									,x.`Baño de visita`
									,x.`Cuarto de servicio`
									,x.`Walk in closet`
									,x.`Piscina privada`
									,x.`Area club con piscina`
									,x.`Acepta mascota`
									,x.`Corretaje`
									,x.`Plan de referido`
									,x.`Link Youtube`
									,x.`Pagina Web Link`
									,x.`Foto`
									,x.`Google`
									,x.`Otros Link`
									,x.`Estilo de cocina`
									,x.`Agente`
									,x.`Zona`
									,x.`Condominio`
									,x.`Ubicacion`
									,x.`Exclusividad de agente`
									,x.`Pais`
									,x.`Estado`
									,x.`Ciudad`
								from 
									vw_inventory_list_item_real_estate x 
								where 
									x.createdOn BETWEEN ? and ?
							";
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$startOn,$endOn]
			);
			
			
			//Crear XML
			$xmlContent =	'
			<?xml version="1.0" encoding="UTF-8"?>
			<root>
					<integert>4</integert>
			</root>';
				
			 // Crear el contenido XML
			$xmlContent =	htmlspecialchars(trim($xmlContent));
			echo $xmlContent;
			
			
			//-wgonzalez-2-// Configurar la respuesta HTTP con el tipo de contenido XML
			//-wgonzalez-2-$response = $this->response->setContentType('text/xml');
			//-wgonzalez-2-
			//-wgonzalez-2-// Enviar el contenido XML como respuesta
			//-wgonzalez-2-return $response->setBody($xmlContent);
	
			//-wgonzalez-$filename = 'user_'.date('Ymd').'.xml'; 
			//-wgonzalez-header("Content-Description: File Transfer"); 
			//-wgonzalez-header("Content-Disposition: attachment; filename=$filename"); 
			//-wgonzalez-header("Content-Type: text/xml; ");
			//-wgonzalez-echo $xmlContent;
			//-wgonzalez-exit; 
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
			
	}
	
	function generatedTransactionOutputByFormulate(){
		try{ 
		
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
				
			
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$componentPeriodID	= /*inicio get post*/ $this->request->getPost("componentPeriodID");
			$componentCycleID	= /*inicio get post*/ $this->request->getPost("componentCycleID");
			
			
						
			$query									= "CALL pr_inventory_create_transaction_output_by_formulated(?,?,?,?,?,@resultMayorization);";
			$resultMayorizate						= $this->Bd_Model->executeRender(
				$query,[$companyID,$branchID,$loginID,$componentPeriodID,$componentCycleID]
			);	
			
			$query									= "SELECT @resultMayorization as codigo";
			$resultMayorizate						= $this->Bd_Model->executeRender($query,null);			
			
			
			$resultMayorizate						= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,'');
			$resultMayorizateTransactionID			= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionID');
			$resultMayorizateTransactionMasterIDID	= $this->Log_Model->get_rowByNameParameterOutput($companyID,$branchID,$loginID,'','pr_inventory_create_transaction_output_by_formulated_transactionMasterID');
			$resultMayorizateTransactionID 			=  $resultMayorizateTransactionID->description;
			$resultMayorizateTransactionMasterIDID	= $resultMayorizateTransactionMasterIDID->description;
			
			
			
			
			
			
			
			//Ingresar en Kardex.
			$this->core_web_inventory->calculateKardexNewOutput($companyID,$resultMayorizateTransactionID,$resultMayorizateTransactionMasterIDID);			
			
			//Crear Conceptos.
			$this->core_web_concept->otheroutput($companyID,$resultMayorizateTransactionID,$resultMayorizateTransactionMasterIDID);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "SUCCESS",
				'result'  => $resultMayorizate
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
			
	}
	
	function getInforDashBoards(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}
			
			
			//Obtener Parametros
			$companyID 		= $dataSession["user"]->companyID;
			if((!$companyID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			
			
						
			
			$numberItem		= $this->Item_Model->getCount($companyID);					
			$numberInput	= $this->Transaction_Model->getCountInput($companyID);
			$numberOutput	= $this->Transaction_Model->getCountOutput($companyID);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'numberitem'	  	=> $numberItem,
				'numberinput'	  	=> $numberInput,
				'numberoutput'	  	=> $numberOutput
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