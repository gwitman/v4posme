<?php
//posme:2023-02-27
namespace App\Controllers;
class core_testunitary extends _BaseController {
	//Constructor ...
    
    
	//INDEX
	////////////////////////////
	function index(){ 
		try{
			
			//Validar Authentication
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception('00018 EL USUARIO NO SE HA AUTENTICADO...');
			$dataSession		= $this->session->get();
			
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}	
		
	}
}
?>