<?php
//posme:2023-02-27
namespace App\Controllers;

class core_acount_logsession extends _BaseController {

	//Pagina de consulta: Log de sesiones + estadistica de transacciones por usuario
	function index()
	{
		try{

			//1. AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//2. PERMISOS
			if(APP_NEED_AUTHENTICATION == true){
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission	= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeftSnagit"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);
			}

			//3. DATOS
			$companyID			= $dataSession["user"]->companyID;
			$objListUser		= $this->User_Model->get_All($companyID);

			//4. RENDERIZAR (mismo masterpage que core_dashboards: tema Sneat)
			$dataView["company"]				= $dataSession["company"];
			$dataView["companyPageSetting"]		= $dataSession["companyPageSetting"];
			$dataView["title"]					= "Log de Sesiones";
			$dataView["objListUser"]			= $objListUser;
			$dataView["userIDDefault"]			= (is_array($objListUser) && count($objListUser) > 0) ? $objListUser[0]->userID : 0;

			$dataSession["notification"]		= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]				= "";
			$dataSession["head"]				= /*--inicio view*/ view('core_acount_logsession/index_head',$dataView);//--finview
			$dataSession["footer"]				= "";
			$dataSession["script"]				= /*--inicio view*/ view('core_acount_logsession/index_script',$dataView);//--finview
			$dataSession["body"]				= /*--inicio view*/ view('core_acount_logsession/index_body',$dataView);//--finview

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

			return view("core_template/email_error_general",$data);
		}
	}

	//API JSON: ultimas 5 sesiones + estadistica de transacciones del usuario filtrado
	function getData()
	{
		try{

			//1. AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();

			//2. DATOS
			$companyID			= $dataSession["user"]->companyID;
			$userID				= $this->request->getPost("userID");
			$userID				= ($userID === null || $userID === "") ? 0 : (int)$userID;

			if($userID <= 0)
			throw new \Exception("Debe seleccionar un usuario valido.");

			//3. LOGICA
			//Ultimas 5 sesiones del usuario
			$objLogSession		= $this->Log_Session_Model->get_lastRowsByUserID($userID,5);
			//Estadistica de transacciones por tipo (createdBy vs userID)
			$objStatistic		= $this->Transaction_Master_Model->get_statisticByCreatedBy($companyID,$userID);
			//Total de transacciones del usuario
			$counterTransaction	= $this->Transaction_Master_Model->get_countByCreatedBy($companyID,$userID);

			//4. RESPUESTA
			return $this->response->setJSON(array(
				'error'				=> false,
				'success'			=> true,
				'message'			=> SUCCESS,
				'counterTransaction'=> $counterTransaction,
				'statistic'			=> $objStatistic,
				'data'				=> $objLogSession
			));//--finjson

		}
		catch(\Exception $ex){
			return $this->response->setJSON(array(
				'error'		=> true,
				'success'	=> false,
				'message'	=> $ex->getLine()." ".$ex->getMessage()
			));
		}
	}
}
?>
