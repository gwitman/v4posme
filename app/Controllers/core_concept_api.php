<?php
//posme:2023-02-27
namespace App\Controllers;
class core_concept_api extends _BaseController {
	
    
	
	
	function index($dataViewID = null){ 
		$dataViewID = helper_SegmentsByIndex($this->uri->getSegments(),1,$dataViewID);	
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
			
			
			$companyID 				= $dataSession["user"]->companyID;
			$componentID 			= /*inicio get post*/ $this->request->getPost("componentID");		
			$componentItemID		= /*inicio get post*/ $this->request->getPost("componentItemID");	
			$name					= /*inicio get post*/ $this->request->getPost("name");	
			
			if((!$companyID) || (!$componentID) || (!$componentItemID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			$data = $this->Company_Component_Concept_Model->get_rowByPK($companyID,$componentID,$componentItemID,$name);
			
			return $this->response->setJSON(array(
				'error'   	=> false,
				'message' 	=> SUCCESS,
				'data'  	=> $data
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