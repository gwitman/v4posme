<?php
//posme:2023-02-27
//Pantalla para ver el listado de notificaciones
//Pantalla para ver el listado de notificaiones en sistema
namespace App\Controllers;
class core_notification extends _BaseController {
	
    
	function save($errorID = null){
		$errorID = helper_SegmentsByIndex($this->uri->getSegments(),1,$errorID);	
		 try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			
			
			$errorID	= /*inicio get post*/ $this->request->getPost("errorID");
			if(!$errorID ){					
				throw new \Exception("NO ES POSIBLE MARCAR COMO LEIDO");	 
				return;
			} 
			
			$objError				= $this->Error_Model->get_rowByPK($errorID);
			$data["isRead"]			= 1;
			$data["readOn"]			= date_format(date_create(),"Y-m-d H:i:s");
			$this->Error_Model->update_app_posme($errorID,$data);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => "success"
			));//--finjson
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}		
			
	}
	
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
			$data["objListErrorOblicaciones"]				= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("LLENAR NOTI DE OBLIGACION")->tagID);
			$data["objListErrorPagos"]						= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("LLENAR NOTI CUOTA A TRASADA")->tagID);
			$data["objListErrorCumple"]						= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("LLENAR NOTI FELIZ CUMPLE")->tagID);
			$data["objListErrorInventarioMinimo"]			= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("LLENAR NOTI INVENTARIO MINIMO")->tagID);
			$data["objListErrorTC"]							= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("LLENAR NOTI TIPO DE CAMBIO")->tagID);
			$data["objListErrorProximaVisita"]				= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("LLENAR NOTI PROXIMA VISITA")->tagID);
			$data["objListErrorFechaDeVencimiento"]			= $this->Error_Model->get_rowByUserAllAndTagID($dataSession["user"]->userID,$this->Tag_Model->get_rowByName("LLENAR NOTI DE PRODUCTOS VENCIDOS")->tagID);
			$dataViewRender									= /*--inicio view*/ view('core_notification/list_body',$data);//--finview
			
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('core_notification/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('core_notification/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('core_notification/list_script');//--finview
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