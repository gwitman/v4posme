<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_api extends _BaseController {
	
     
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