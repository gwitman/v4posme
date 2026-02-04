<?php
//posme:2023-02-27
namespace App\Controllers;
use Config\Services;

class app_cxc_conversation extends _BaseController {
	
       
	function edit()
	{
		try{ 
		
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
		
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			
			
			
			//Tipo de plantilla
			$companyID		= $dataSession["user"]->companyID;
			$branchID		= $dataSession["user"]->branchID;
			$masterPage 	= 'snagit_masterpage';
			$viewType		= 'snagit_';
			$entityID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "entityID"); //--finuri
			//obtener la ultima session
			$dataSession["lastUrl"] = base_url()."/"."app_cxc_conversation/edit/entityID/".$entityID;
			$this->session->set($dataSession);
			
			//Renderizar Resultado
			$dataView["company"]					= $dataSession["company"];
			$dataView["companyPageSetting"]			= $dataSession["companyPageSetting"];
			$dataView["objParameterCONVERSATION_LIST_CONVERSATION_NOT_PHOTE"]				= $this->core_web_parameter->getParameter("CONVERSATION_LIST_CONVERSATION_NOT_PHOTE",$dataSession["company"]->companyID)->value;
			$dataView["objParameterCONVERSATION_LIST_CONVERSATION_NOT_BELL"]				= $this->core_web_parameter->getParameter("CONVERSATION_LIST_CONVERSATION_NOT_BELL",$dataSession["company"]->companyID)->value;			
			$dataView["title"]						= "Conversaciones";
			$dataView["entityID"]					= $entityID;
			$dataSession["head"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'edit_head',$dataView);//--finview
			$dataSession["footer"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'edit_footer',$dataView);//--finview
			$dataSession["script"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'edit_script',$dataView);//--finview
			$dataSession["body"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'edit_body',$dataView);//--finview
						
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
	function index($dataViewID = null)
	{
		try{ 
		
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//obtener la ultima session
			$dataSession["lastUrl"] = base_url()."/"."app_cxc_conversation/index";
			$this->session->set($dataSession);
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			
			
			$dataViewID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $dataViewID);
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_notification_conversation");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_notification_conversation' NO EXISTE...");
			
			//Obtener el tipo de permiso
			$permitedUrl 		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			if($permitedUrl == PERMISSION_ALL)
			{
				$dataView["permisoParaVerTodasLasActivas"]	= "true";
			}
			else
			{
				$dataView["permisoParaVerTodasLasActivas"]	= "false";
			}
			
			
			//Tipo de plantilla
			$masterPage 	= 'snagit_masterpage';
			$viewType		= 'snagit_';
			 
			//Renderizar Resultado
			$dataView["company"]					= $dataSession["company"];
			$dataView["companyPageSetting"]			= $dataSession["companyPageSetting"];
			$dataView["title"]						= "Conversaciones";			
			
			$dataView["objParameterCONVERSATION_LIST_CONVERSATION_NOT_PHOTE"]				= $this->core_web_parameter->getParameter("CONVERSATION_LIST_CONVERSATION_NOT_PHOTE",$dataSession["company"]->companyID)->value;
			$dataView["objParameterCONVERSATION_LIST_CONVERSATION_NOT_BELL"]				= $this->core_web_parameter->getParameter("CONVERSATION_LIST_CONVERSATION_NOT_BELL",$dataSession["company"]->companyID)->value;
			$dataSession["head"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'list_head',$dataView);//--finview
			$dataSession["footer"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'list_footer',$dataView);//--finview
			$dataSession["script"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'list_script',$dataView);//--finview
			$dataSession["body"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'list_body',$dataView);//--finview
						
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
	function report($dataViewID = null)
	{
		try{ 
		
			
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//obtener la ultima session
			$dataSession["lastUrl"] = base_url()."/"."app_cxc_conversation/report";
			$this->session->set($dataSession);
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){				
				
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuLeftSnagit"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			
			
			$dataViewID 		= helper_SegmentsByIndex($this->uri->getSegments(), 1, $dataViewID);
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_notification_conversation");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_notification_conversation' NO EXISTE...");
			
			//Obtener el tipo de permiso
			$permitedUrl 		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			if($permitedUrl == PERMISSION_ALL)
			{
				$dataView["permisoParaVerTodasLasActivas"]	= "true";
			}
			else
			{
				$dataView["permisoParaVerTodasLasActivas"]	= "false";
			}
			
			//Obtener la lista de colaboradores
			$objListEmployerAll 	= $this->Employee_Model->get_rowByCompanyID($dataSession["company"]->companyID);
			$objListEmployer		= array();
			foreach ($objListEmployerAll as $employer) {
				// 👉 condición de ejemplo
				$resultValidation = $this->Employee_Model->get_validatePermissionBy_EmployerID_PuedeAtenderWhatsappInCRM($employer->entityID);
				if($resultValidation)
				{
					$objListEmployer[] = $employer;
				}
			}
			
			
			
			//Tipo de plantilla
			$masterPage 		= 'snagit_masterpage';
			$viewType			= 'snagit_';
			 
			//Renderizar Resultado
			$dataView["company"]					= $dataSession["company"];
			$dataView["companyPageSetting"]			= $dataSession["companyPageSetting"];
			$dataView["title"]						= "Conversaciones";			
			$dataView["objListEmployer"]			= $objListEmployer;
			$dataView["objEmployerIDDefault"]		= $objListEmployer[0]->entityID;
			
			$dataView["objParameterCONVERSATION_LIST_CONVERSATION_NOT_PHOTE"]				= $this->core_web_parameter->getParameter("CONVERSATION_LIST_CONVERSATION_NOT_PHOTE",$dataSession["company"]->companyID)->value;
			$dataView["objParameterCONVERSATION_LIST_CONVERSATION_NOT_BELL"]				= $this->core_web_parameter->getParameter("CONVERSATION_LIST_CONVERSATION_NOT_BELL",$dataSession["company"]->companyID)->value;
			$dataSession["head"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'report_head',$dataView);//--finview
			$dataSession["footer"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'report_footer',$dataView);//--finview
			$dataSession["script"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'report_script',$dataView);//--finview
			$dataSession["body"]					= /*--inicio view*/ view('app_cxc_conversation/'.$viewType.'report_body',$dataView);//--finview
						
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