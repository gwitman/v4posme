<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_dashboards extends _BaseController {
	//Constructor ...
    
    
	//INDEX
	//////////////////////////// abc
	function index(){ 
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
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
				
			}		
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= "";
			$dataSession["head"]			= "";
			$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_accounting',$dataSession);//--finview
			$dataSession["script"]			= ""; 
			$dataSession["footer"]			= ""; 
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
		}
		catch(\Exception $ex){
			show_error($ex->getMessage() ,500 );
		}
	}
}
?>