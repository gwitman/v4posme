<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxc_process extends _BaseController {
	
   
		
	function uploadDataSinRiesgo(){
		try{ 
		
			
			//AUTENTICADO 
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
			}	
			
			
			
			
			$companyID			= $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$loginID			= $dataSession["user"]->userID;
			$tocken				= "";
			
			//Obtener La Data, de la vista.
			$query			    = "CALL pr_cxc_get_report_upload_buro(?,?,?);";
			//$resultados1x 	= $this->Customer_Consultas_Sin_Riesgo_Model->get_rowByCompany($companyID);
			
			$resultados1	= $this->Bd_Model->executeRender(
				$query,
				[$loginID,$tocken,$companyID]
			);
			$resultados1	= $resultados1;
			
			$resultados2	= array();
			foreach($resultados1 as $key => $value){
				$resultados2[$key]	= array_values($value);
			}
			 
			
			//https://www.sinriesgos.com.ni/ServiceFacade/servicios.asmx?wsdl
			//https://www.sinriesgos.com.ni/ServiceFacade/servicios.asmx?wsdl
			$objParameter 			= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_UPLOAD",$companyID);//""			
			$objParameterCodigo		= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_UPLOAD_CODIGO",$companyID);//"b77a5c561934e089"	
			$objParameterPassword 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_PASSWORD",$companyID);//flc-wgonzalez
			$objParameterUsuario 	= $this->core_web_parameter->getParameter("CORE_CXC_WSDL_SIN_RIESGO_USUARIO",$companyID);//180389Gonzalez.
			$objParameterDeliminterCsv	 = $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
			$objParameterDeliminterCsv	 = $objParameterDeliminterCsv->value;
			$logDB["code"]				 = 0;
			
			
			
			//Mandar Sin Riesgo vis Servicios Web
			/*
			$client 				= new \SoapClient($objParameter->value);
			$params 				= array(
				"Codigo"					=> $objParameterCodigo->value,
				"Usuario" 					=> $objParameterUsuario->value,
				"Contraseña" 				=> $objParameterPassword->value,
				"lstCreditos"				=> $resultados2 
			);			
			
			$resultado = $client->ActualizarCreditosOL( $params );
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => $resultado->ActualizarCreditosOLResult,
				'result'  => 0
			));//--finjson
			*/
			
			//Descargar Datos
			//file name 
			$filename = 'user_'.date('Ymd').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");
			
   
			// file creation 
			$file 	= fopen('php://output', 'w');
			$header = array(
				"tipo_ent", /*"ENTIDAD*/  /**/
				"num_corre", /*"FECHA DE REPORTE*/  /**/
				"fec_rep", /*"IDENTIFICACION*/  /**/
				"depart", /*"TIPO DE PERSONA*/  /*Completar con apoyo de Tabla ASCII*/
				"cedula", /*"NACIONALIDAD*/  /**/
				"Nombre", /*"SEXO*/  /*Tabla ASCII*/
				"tip_cre", /*"FECHA DE NACIMIENTO*/  /**/
				"fec_desem", /*"ESTADO CIVIL*/  /*Tabla ASCII*/
				"tip_obli", /*"DIRECCION DOMICILIO*/  /**/
				"mont_auto", /*"DEPARTAMENTO DOMICILIO*/  /*Tabla ASCII*/
				"plazo", /*"MUNICIPIO DOMICILIO*/  /*Tabla ASCII*/
				"frec_pago", /*"DIRECCION TRABAJO*/  /**/
				"Saldo", /*"DEPARTAMENTO TRABAJO*/  /*Tabla ASCII*/
				"estado", /*"MUNICIPIO TRABAJO*/  /*Tabla ASCII*/
				"mont_venci", /*"TELEFONO DOMICILIAR*/  /**/
				"antig_mora", /*"TELEFONO TRABAJO*/  /**/
				"tip_gara", /*"CELULAR*/  /**/
				"for_recup", /*"CORREO ELECTRONICO*/  /**/
				"NumCredito", /*"OCUPACION*/  /**/
				"MontoCuota", /*"ACTIVIDAD ECONOMICA*/  /**/
				"", /*"SECTOR*/  /**/

			); 
			
			fputcsv($file, $header,$objParameterDeliminterCsv);
			foreach ($resultados2 as $key=>$line){ 
					fputcsv($file,$line,$objParameterDeliminterCsv); 
			}
			fclose($file); 
			exit; 
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	 	
			
	}
				
	function index($dataViewID = null){	
	try{ 
		
	         $dataViewID = helper_SegmentsByIndex($this->uri->getSegments(), 1, $dataViewID);
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
			
			
			
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxc_process/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxc_process/view_body');//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxc_process/view_script');//--finview
			$dataSession["footer"]			= "";			
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