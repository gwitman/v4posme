<?php
//posme:2023-02-27
namespace App\Controllers;


class app_form_public extends _BaseController {
	
	function convierten_detalle_servicio()
	{
	
		try{ 
			
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");			
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentEntity					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_entity");			
			if(!$objComponentEntity)
			throw new \Exception("EL COMPONENTE 'tb_entity' NO EXISTE...");
		
		
			
			
			//Obtener los Roles			
			$datView["objEmployee"]  = $objComponent;
			$datView["objEntity"]  	 = $objComponentEntity;
			$datView["message"]		 = $this->core_web_notification->get_message_alert();
			
			//Renderizar Resultado 			
			$dataSession["head"]			= /*--inicio view*/ view('app_form_public/convierten_detalle_servicio_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_form_public/convierten_detalle_servicio_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_form_public/convierten_detalle_servicio_script',$datView);//--finview
			$dataSession["footer"]			= "";
			return view("core_masterpage/default_masterpage_public",$dataSession);//--finview-r
			
		}
		catch(\Exception $ex){
			exit($ex->getMessage());
		}	
			
    }
	
}
?>