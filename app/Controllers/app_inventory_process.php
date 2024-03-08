<?php
//posme:2023-02-27
namespace App\Controllers;
class app_inventory_process extends _BaseController {
   
	
	
	
	
	
	function index($dataViewID = null){	
	try{ 
			$dataSession		= $this->session->get();
			
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LA FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
			
			}	
			
			
			
			
			$objCompanyParameter 				= $this->core_web_parameter->getParameter("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$dataV["objListAccountingPeriod"]	= $this->Component_Period_Model->get_rowByNotClosed($dataSession["user"]->companyID,$objCompanyParameter->value);
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_inventory_process/view_head',$dataV);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_inventory_process/view_body',$dataV);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_inventory_process/view_script',$dataV);//--finview
			$dataSession["footer"]			= "";			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}
	}	
	
}
?>