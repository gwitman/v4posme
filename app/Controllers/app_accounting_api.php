<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_api extends _BaseController {
	

	function getCycle(){
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
			$companyID 				= $dataSession["user"]->companyID;
			$componentPeriodID 		= /*inicio get post*/ $this->request->getPost("componentPeriodID");		
			if((!$companyID) || (!$componentPeriodID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			
						
			$cycles					= $this->Component_Cycle_Model->getByComponentPeriodID($componentPeriodID);
			if($cycles)
			foreach($cycles as $key => $value){
				$value->startOnFormat 	= helper_DateToSpanish($value->startOnFormat,"Y-F");
				$value->endOnFormat 	= helper_DateToSpanish($value->endOnFormat,"Y-F");
				$cycles[$key] = $value;
			}
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'cycles'  => $cycles
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	function getCycleNotClosed(){
		try{ 
			
			$dataSession = $this->session->get();
			
			
			
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated()){
				
				throw new \Exception(USER_NOT_AUTENTICATED);
			}
			
			$dataSession		= $this->session->get();
			
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$componentPeriodID 		= /*inicio get post*/ $this->request->getPost("componentPeriodID");		
			if((!$companyID) || (!$componentPeriodID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			
			
			
			$objCompanyParameter 	= $this->core_web_parameter->getParameter("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$cycles					= $this->Component_Cycle_Model->get_rowByNotClosed($dataSession["user"]->companyID,$componentPeriodID,$objCompanyParameter->value);
			if($cycles)
			foreach($cycles as $key => $value){
				$value->startOnFormat 	= helper_DateToSpanish($value->startOnFormat,"Y-F");
				$value->endOnFormat 	= helper_DateToSpanish($value->endOnFormat,"Y-F");
				$cycles[$key] = $value;
			}
			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'cycles'  => $cycles
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
			
			
			
			
			
						
			
			$numberUser		= $this->User_Model->getCount($companyID);					
			$numberPeriod	= $this->Component_Period_Model->get_countPeriod($companyID);
			$numberAccount	= $this->Account_Model->get_countAccount($companyID);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'numberuser'	  	=> $numberUser,
				'numberaccount'	  	=> $numberAccount,
				'numberperiod'	  	=> $numberPeriod
			));			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	function getHistoryBalanceByAccount(){
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
			$companyID 		= /*inicio get post*/ $this->request->getPost("companyID");
			$accountID 		= /*inicio get post*/ $this->request->getPost("accountID");				
			
			if((!$companyID && !$accountID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			
			$query			= "CALL pr_accounting_get_history_balance_by_account (?,?);";			
			$resultQuery	= $this->Bd_Model->executeRender(
				$query,
				[$companyID,$accountID]
			);		
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'data'	  => $resultQuery
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