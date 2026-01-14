<?php
//posme:2023-02-27
namespace App\Controllers;
use Config\Services;

class app_cxc_conversation extends _BaseController {
	
       
	function index($dataViewID = null)
	{
		try{ 
		
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			
			$dataViewID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $dataViewID);
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_notification_conversation");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_notification_conversation' NO EXISTE...");
			
			//Vista por defecto 
			$targetComponentID			= $this->session->get('company')->flavorID;
			if($dataViewID == null){
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;				
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				
				
				if(!$dataViewData){
					$targetComponentID			= 0;	
					$parameter["{companyID}"]	= $this->session->get('user')->companyID;					
					$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);				
				}
			}
			
			//Tipo de plantilla
			$masterPage 	= $dataSession["role"]->typeApp.'_masterpage';
			$viewType		= $dataSession["role"]->typeApp == "default" ? "" : $dataSession["role"]->typeApp.'_';
			 
			//Renderizar Resultado
			$dataView["company"]					= $dataSession["company"];
			$dataView["companyPageSetting"]			= $dataSession["companyPageSetting"];
			$dataView["title"]						= "Conversaciones";
			$dataView["data"]						= $dataViewData["view_data"];
			$dataSession["head"]					= /*--inicio view*/ view('app_cxc_customer/'.$viewType.'list_head',$dataView);//--finview
			$dataSession["footer"]					= /*--inicio view*/ view('app_cxc_customer/'.$viewType.'list_footer',$dataView);//--finview
			$dataSession["script"]					= /*--inicio view*/ view('app_cxc_customer/'.$viewType.'list_script',$dataView);//--finview
			$dataSession["body"]					= /*--inicio view*/ view('app_cxc_customer/'.$viewType.'list_body',$dataView);//--finview
						
			return view("core_masterpage/".$masterPage,$dataSession);//--finview-r	
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