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
	
	function getConceptAllProduct(){
		try{  
		
		
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get(); 
		
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
		
			$viewname 					= "LIST_CONCEPT_ITEMS";
			$parameter["{companyID}"]	= $this->session->get('user')->companyID;
			$dataViewData				= $this->core_web_view->getViewByName(	
												$this->session->get('user'),
												$objComponent->componentID,
												$viewname,
												CALLERID_SEARCH,null,$parameter
										  );
			
			//Obtener Resultados.			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,			
				'objGridView'	 => $dataViewData["view_data"]
			));//--finjson			
			
		}
		catch(\Exception $ex){
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
			
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    return $resultView;
		}
	}
	
	
}
?>