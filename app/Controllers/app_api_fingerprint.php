<?php
//posme:2023-02-27
namespace App\Controllers;
use App\Libraries\core_mysql_dump;

class app_api_fingerprint extends _BaseController {
	
	//https://www.youtube.com/watch?v=I-xhKVcYr4g
	//http://localhost/BiometricPhp-master/api/
	//http://localhost/posmev4/app_api_fingerprint/
	//llRnk81687411555823
	
	function web_ssejs(){
		
		
		$tockenPc 		= "llRnk81687411555823";
		$finger_name	= "Pulgar_Derecho";
		$resultado 		= $this->Temp_Fingerprint_Model->get_ssejs($tockenPc);
		
		
		header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
		header("HTTP/1.1 200 OK");
		echo 'data: ' . json_encode($resultado) . "\n\n";
		flush();
		
		 if (!empty($resultado->image)) 
		 {
			
			$data["image"]  		= null;
			$resultado 				= $this->Temp_Fingerprint_Model->update_app_posme($tockenPc,$data);            
            //$resultado->id 			= $tockenPc;
            //$resultado->name 			= null;
            //$resultado->image			= null;
            //$resultado->user_id 		= null;
            //$resultado->option 		= null;
        }
		
		
		
		
	}
	
	//Leer huella para guardar
	function web_active_sensor_enroll()
	{
		
		$customerID	 	= /*inicio get post*/ $this->request->getPost("customerID");	
		$tockenPc 		= "llRnk81687411555823";
		$finger_name	= "Pulgar_Derecho";
		
		$this->Temp_Fingerprint_Model->delete_app_posme($tockenPc);
		
		
		$dataNew["id"] 				= strtotime("now") ;
		$dataNew["finger_name"] 	= $finger_name ;
		$dataNew["token_pc"]		= $tockenPc;
		$dataNew["text"]			= "El sensor de huella dactilar esta activado";
		$dataNew["option"]			= "enroll";
		$dataNew["user_id"]			= $customerID;
		$this->Temp_Fingerprint_Model->insert_app_posme($dataNew);
		
	}	
	
	//Leer  huella para validar
	function web_active_sensor_read(){
		
		
		$tockenPc 		= "llRnk81687411555823";
		$finger_name	= "Pulgar_Derecho";
		$this->Temp_Fingerprint_Model->delete_app_posme($tockenPc);
		
		
		$dataNew["id"] 				= strtotime("now") ;
		$dataNew["option"]			= "read";
		$dataNew["token_pc"]		= $tockenPc;
		$dataNew["created_at"] 		= date("Y-m-d H:i:s");
		$this->Temp_Fingerprint_Model->insert_app_posme($dataNew);
		
		$executeProgramFingerPint	= $this->core_web_parameter->getParameter("OPEN_FINGERPRINT_EXECUTE",APP_COMPANY);
		$executeProgramFingerPint	= $executeProgramFingerPint->value;
		if($executeProgramFingerPint == "false")
		return;
		
		$executeProgramFingerPintPath	= $this->core_web_parameter->getParameter("OPEN_FINGERPRINT_EXECUTE_PATH",APP_COMPANY);
		$executeProgramFingerPintPath	= $executeProgramFingerPintPath->value;
		return;
		
		//wgonzalez--// Nombre del programa (sin la ruta) para buscarlo en los procesos en ejecución
		//wgonzalez--$programName = "tu_programa.exe";
		//wgonzalez--
		//wgonzalez--// Comprobar si el programa ya está abierto
		//wgonzalez--exec("tasklist /FI \"IMAGENAME eq $programName\"", $output);
		//wgonzalez--
		//wgonzalez--$isRunning = false;
		//wgonzalez--foreach ($output as $line) {
		//wgonzalez--	if (strpos($line, $programName) !== false) {
		//wgonzalez--		$isRunning = true;
		//wgonzalez--		break;
		//wgonzalez--	}
		//wgonzalez--}
		//wgonzalez--
		//wgonzalez--if ($isRunning) {
		//wgonzalez--	// Si el programa está abierto, cerrarlo
		//wgonzalez--	exec("taskkill /F /IM $programName", $killOutput, $killResult);
		//wgonzalez--	if ($killResult === 0) {
		//wgonzalez--		echo "El programa estaba abierto y se cerró con éxito.";
		//wgonzalez--	} else {
		//wgonzalez--		echo "Hubo un error al intentar cerrar el programa.";
		//wgonzalez--	}
		//wgonzalez--} else {
		//wgonzalez--	echo "El programa no está en ejecución.";
		//wgonzalez--}


		//// Ejecutar el archivo .exe				
		//$batFilePath  = 'C:\\xampp\\teamds2\\nsSystem\\v4posme\\public\\resource\\file_job\\job_execute_finger_print_exe.bat';		
		//$batFilePath  = 'C:\Program Files\FileZilla FTP Client\filezilla.exe';
		//
		//
		//
		//exec("powershell -command \"Start-Process '$batFilePath'\"");
		//$descriptorspec = [
		//	0 => ["pipe", "r"],
		//	1 => ["pipe", "w"],
		//	2 => ["pipe", "w"]
		//];
		//$process = proc_open($batFilePath, $descriptorspec, $pipes);
		//if (is_resource($process)) {
		//	fclose($pipes[0]);
		//	fclose($pipes[1]);
		//	fclose($pipes[2]);
		//	proc_close($process);
		//}
		
		//exec("cmd /c start \"".$batFilePath."\"");
		//shell_exec("start \"\" \"$batFilePath\"");
		//pclose(popen("start \"\" \"$batFilePath\"", "r"));		
		//exec("start \"\" \"$batFilePath\" > NUL 2>&1 ", $output, $return_var);
		//exec('start /B "'.$executeProgramFingerPintPath.'" 2>&1', $output, $return_var);
		//exec('start "'.$executeProgramFingerPintPath.'" 2>&1', $output, $return_var);
		
		
		// Mostrar salida y error
		//if ($return_var === 0) {
		//	log_message("error","¡Programa ejecutado con éxito!");
		//} else {
		//	log_message("error","Hubo un error al ejecutar el programa: ");
		//	log_message("error",print_r($output,true)); // Mostrar detalles del error
		//}

		
	}
	function web_get_finger(){
		
		
		$customerID	 		= /*inicio get post*/ $this->request->getPost("customerID");	
		$objFinger 			= $this->Fingerprints_Model->getByUserIDAndNotifie($customerID);
		$data["notified"] 	= 0;
		$this->Fingerprints_Model->update_app_posme($objFinger->id,$data);
		return $this->response->setJSON($objFinger);//--finjson	
		
		
	}
	
	function sse($tocken = "")
	{
		
		
		$tocken 		= helper_SegmentsByIndex($this->uri->getSegments(),1,$tocken);	
		$tockenPc 		= "llRnk81687411555823";
		$result 		= $this->Temp_Fingerprint_Model->get_ssejsByOptionIsNotNull($tockenPc);
		$array 			= array('option' => null, 'pc_serial' => $tockenPc);
		
		if ($result ) 
		{
			$array['option'] 	= $result->option;
			$array['pc_serial'] = $tockenPc;
		}
		
		$response = json_encode($array);                
		return $this->response->setJSON($response);//--finjson			
        
	}
	
	
	function save_finger()
	{
		
		$tockenPc 			= "llRnk81687411555823";
		$result 			= $this->Temp_Fingerprint_Model->get_ssejsByTocken($tockenPc);
		$data["option"]		= /*inicio get post*/ $this->request->getVar("option");	
		$this->Temp_Fingerprint_Model->update_app_posme_by_id($result->id,$data);
		
		
		//Crear el fringerPrint
		$data 					= array();
		$dedo 					= explode("_", $result->finger_name);
		$data["finger_name"] 	= $dedo[0] . " " . $dedo[1];
		$data["fingerprint"] 	= /*inicio get post*/ $this->request->getVar("fingerprint");
		$data["user_id"]		= $result->user_id;
		$data["notified"] 		= 0;
		$data["image"] 		= app_api_fingerprint::saveImage
		(
					/*inicio get post*/ $this->request->getVar("image"), 
					$result->user_id . $result->finger_name
		);		
		
		$resultSave  	= $this->Fingerprints_Model->insert_app_posme($data);					
		
	
		$response	 = json_encode(array("response" => "Ok"));
		return $this->response->setJSON($response);//--finjson	
	}
	
	function list_finger(){
		
		
		$from 		= /*inicio get post*/ $this->request->getVar("from");	
		$total 		= $this->Fingerprints_Model->getCount();
		$total  	= $total->total;
		$usuarios 	= $this->Fingerprints_Model->getPagination($from);
		
		//buscar nombre de usuarios en la otra base.
		if($usuarios)
		{
			if(count($usuarios) > 0)
			{
				for($i = 0 ; $i < count($usuarios) ; $i++)
				{
					$objNaturalesModel 	= $this->Natural_Model->get_rowByPK(APP_COMPANY,2,$usuarios[$i]->id);
					$usuarios[$i]->name = $objNaturalesModel->firstName;
				}
			}
		}		
		
		$array 		= array("usuarios" => $usuarios, "total" => $total);	
		$array 		= json_encode($array);
		return $this->response->setJSON($array);//--finjson	
		
	}
	
	function update_finger()
	{
		
		$tockenPc 			= "llRnk81687411555823";
		$result 			= $this->Temp_Fingerprint_Model->get_ssejsByTocken($tockenPc);
		$data["fingerprint"]		= null;
		$data["image"]				= /*inicio get post*/ $this->request->getVar("image");	
		$data["text"]				= /*inicio get post*/ $this->request->getVar("text");	
		$data["name"]				= /*inicio get post*/ $this->request->getVar("name");	
		$data["user_id"]			= ((int) /*inicio get post*/ $this->request->getVar("user_id") > 0) ? /*inicio get post*/ $this->request->getVar("user_id") : null;
		
		
		$this->Temp_Fingerprint_Model->update_app_posme_by_id($result->id,$data);
		$array = json_encode(array("response" => "Ok"));
		return $this->response->setJSON($array);//--finjson	
	}
	
	function sensor_close()
	{
		
		
		$tockenPc 			= "llRnk81687411555823";
		$result 			= $this->Temp_Fingerprint_Model->get_ssejsByTocken($tockenPc);		
		$data["option"]		= "close";	
		$this->Temp_Fingerprint_Model->update_app_posme_by_id($result->id,$data);
		
		
        $arrayResponse 	= array("code" => $result, "message" => "Ok");
        $array 			= json_encode($arrayResponse);
		return $this->response->setJSON($array);//--finjson	
	}
	
	function sincronizar(){
		
		
		$fingerID 	= $this->request->getVar("finger_id");
		$result 	= $this->Fingerprints_Model->getByFingerId($fingerID);	
        $array 		= json_encode($result);
		return $this->response->setJSON($array);//--finjson	
	}
	
	public static function saveImage($image, $user_id) {
        $image 		= base64_decode($image);
        $imageName 	= $user_id . ".png"; 
		
        $url 			= base_url()."/resource/file_company/company_2/component_36/".$imageName;
        $rutaArchivo 	= PATH_FILE_OF_APP."/company_2/component_36/".$imageName;
		
		
        file_put_contents($rutaArchivo, $image);
        return $url;
    }
	
}
