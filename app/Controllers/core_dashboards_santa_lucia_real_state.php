<?php
//posme:2023-02-27
namespace App\Controllers;
class core_dashboards_santa_lucia_real_state extends _BaseController {
	
    
	//INDEX
	////////////////////////////
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
			
			
			
			$firstDateYear					= helper_PrimerDiaDelMes();
			$lastDateYear					= helper_UltimoDiaDelMes();
			$firstDate						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"txtDateStart");//--finuri
			$lastDate						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"txtDateFinish");//--finuri			
			$firstDate						= $firstDate == "" ? helper_PrimerDiaDelMes() : $firstDate ;
			$lastDate						= $lastDate == "" ? explode(" ",helper_UltimoDiaDelMes())[0] : $lastDate ;			
			
			
			$dataSession["roleName"]												= $dataSession["role"]->name;
			$dataSession["firstDate"]												= $firstDate;
			$dataSession["lastDate"]												= $lastDate;
			$dataSession["RealState_get_ClienteFuenteDeContacto"]					= $this->Transaction_Master_Detail_Model->RealState_get_ClienteFuenteDeContacto($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_ClientesInteres"]							= $this->Transaction_Master_Detail_Model->RealState_get_ClientesInteres($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_ClientesTipoPropiedad"]						= $this->Transaction_Master_Detail_Model->RealState_get_ClientesTipoPropiedad($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_ClientesPorAgentes"]						= $this->Transaction_Master_Detail_Model->RealState_get_ClientesPorAgentes($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_ClientesClasificacionPorAgentes"]			= $this->Transaction_Master_Detail_Model->RealState_get_ClientesClasificacionPorAgentes($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_ClientesCerrados"]							= $this->Transaction_Master_Detail_Model->RealState_get_ClientesCerrados($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_AgenteEfectividad"]							= $this->Transaction_Master_Detail_Model->RealState_get_AgenteEfectividad($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_PropiedadesPorAgentes"]						= $this->Transaction_Master_Detail_Model->RealState_get_PropiedadesPorAgentes($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_PropiedadesPorAgentesMetas"]				= $this->Transaction_Master_Detail_Model->RealState_get_PropiedadesPorAgentesMetas($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_PropiedadesRendimientoAnualVentas"]			= $this->Transaction_Master_Detail_Model->RealState_get_PropiedadesRendimientoAnualVentas($dataSession["user"]->companyID,$firstDate,$lastDate);
			$dataSession["RealState_get_PropiedadesRendimientoAnualEnlistamiento"]	= $this->Transaction_Master_Detail_Model->RealState_get_PropiedadesRendimientoAnualEnlistamiento($dataSession["user"]->companyID,$firstDate,$lastDate);
			
			
			
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= "";
			$dataSession["head"]			= "";
			$dataSession["body"]			= /*--inicio view*/ view('core_dasboard/dashboards_default_santa_lucia_real_state',$dataSession);//--finview
			$dataSession["script"]			= ""; 
			$dataSession["footer"]			= ""; 			
			
			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r	
		}
		catch(\Exception $ex){
			$data["session"]   = $dataSession;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
		    echo $resultView;
		}
	}
}
?>