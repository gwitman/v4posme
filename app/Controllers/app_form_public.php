<?php
//posme:2023-02-27
namespace App\Controllers;

use Exception;
use PhpParser\Node\Stmt\TryCatch;

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
		
		
			
			$datView=array();
			$datView['result']=$this->request->getGet('valor');
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

	function convierten_detalle_servicio_get() {
		$id = $this->request->getGet('txtTransactionMasterReferenceID');
		try{
			$result = $this->Transaction_Master_References_Model->get_rowByPK($id);
			if(!is_null($result)){
				return $this->response->setJSON(array(
					'error' => false,
					'message' => SUCCESS,
					"result"=>$result
				));//--finjson
			}else{
				return $this->response->setJSON(array(
					'error' => true,
					'message' => ERROR,
					"result"=>"ND"
				));//--finjson
			}
		}catch(\Exception $ex){
			$this->core_web_notification->set_message(true,$ex->getMessage());
			return $this->response->setJSON(array(
				'error' => true,
				'message' => $ex->getMessage(),
				"result"=>"ND"
			));//--finjson
		}	
	}

	function save($mode){
		try{ 
			//Guardar o Editar Registro		
			if($mode == "new"){
				$this->insertElement();
			}
		}
		catch(\Exception $ex){
			$this->core_web_notification->set_message(true,$ex->getMessage());
		}	
	}

	function insertElement(){
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
			
			$objTMR = array();
			$objTMR['reference1'] = $this->request->getPost('txtTransactionMasterReference1');
			$objTMR['reference2'] = $this->request->getPost('txtTransactionMasterReference2');
			$objTMR['reference3'] = $this->request->getPost('txtTransactionMasterReference3');
			$objTMR['refernece4'] = $this->request->getPost('txtTransactionMasterReference4');
			$objTMR['refernece5'] = $this->request->getPost('txtTransactionMasterReference5');
			$objTMR['reference6'] = $this->request->getPost('txtTransactionMasterReference6');
			$objTMR['reference7'] = $this->request->getPost('txtTransactionMasterReference7');
			$objTMR['reference8'] = $this->request->getPost('txtTransactionMasterReference8');
			$objTMR['referecne9'] = $this->request->getPost('txtTransactionMasterReference9');
			$objTMR['reference10'] = $this->request->getPost('txtTransactionMasterReference10');
			$objTMR['reference11'] = $this->request->getPost('txtTransactionMasterReference11');
			$objTMR['reference12'] = $this->request->getPost('txtTransactionMasterReference12');
			$objTMR['reference13'] = $this->request->getPost('txtTransactionMasterReference13');
			$objTMR['reference14'] = $this->request->getPost('txtTransactionMasterReference14');
			$objTMR['reference15'] = $this->request->getPost('txtTransactionMasterReference15');
			$objTMR['reference16'] = $this->request->getPost('txtTransactionMasterReference16');
			$objTMR['reference17'] = $this->request->getPost('txtTransactionMasterReference17');
			$objTMR['reference18'] = $this->request->getPost('txtTransactionMasterReference18');
			$objTMR['createdOn']  = date('Y-m-d H:i:s');
			$objTMR['isActive']  = 1;		
			
			$db=db_connect();
			$db->transStart();

			if(!is_null($objTMR['reference1'])){
				$result = $this->Transaction_Master_References_Model->insert_app_posme($objTMR);				
			}else{
				$result=0;
			}
			
			if($db->transStatus() !== false){				
				$db->transCommit();
                $this->response->redirect(base_url()."/".'app_form_public/convierten_detalle_servicio?valor='.$result);
			}
			else{
				$db->transRollback();
				$this->response->redirect(base_url()."/".'app_form_public/convierten_detalle_servicio?valor='.$result);
			}
			
		}
		catch(\Exception $ex){
			echo $ex->getMessage().' linea '.$ex->getLine();
			return 0;			
		}	
	}
	
}
?>