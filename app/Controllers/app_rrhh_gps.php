<?php 

namespace App\Controllers;

class app_rrhh_gps extends _BaseController{

    function Index()
    {
        try
        {
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


            $objListRegisteredLocations                 = $this->Entity_Location_Model->get_UsersLocation();
            $dataSession["objListRegisteredLocations"]  = $objListRegisteredLocations;

            

			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= "";
            $dataSession["head"]            = /*--inicio view*/ view("app_rrhh_gps/list_head",$dataSession);//--finview
            $dataSession["body"]            = /*--inicio view*/ view("app_rrhh_gps/list_script",$dataSession);//--finview
            $dataSession["script"]          = ""; 
			$dataSession["footer"]          = ""; 
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
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