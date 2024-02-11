<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxc_leads extends _BaseController {
	
    
    
	
	
	function insertElement($dataSession){
		try{
			
			
			
			//Validar Licencia
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentShare			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_customer_leads");
			if(!$objComponentShare)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_customer_leads' NO EXISTE...");
			
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_customer_leads",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_customer_leads",0);
			$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($dataSession["user"]->companyID,$transactionID);			
			$objTM["transactionOn"]					= date("Y-m-d H:m:s");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentShare->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtLeadComentario");//--fin peticion get o post
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= 0;
			$objTM["currencyID2"]					= 0;
			$objTM["exchangeRate"]					= 0;
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtLeadCategory");
			$objTM["reference2"] 					= '';
			$objTM["reference3"] 					= '';
			$objTM["reference4"] 					= '';
			
			$objTM["statusID"] 						= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_customer_leads","statusID",$companyID,$objTM["branchID"],$dataSession["role"]->roleID)[0]->workflowStageID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtLeadTipo");
			$objTM["priorityID"] 					= /*inicio get post*/ $this->request->getPost("txtLeadSubTipo");
			$objTM["sourceWarehouseID"]				= NULL;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$objTM["entityID"]						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTM["entityIDSecondary"]				= 0;	
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			
			if($db->transStatus() !== false)
			{
				$db->transCommit();						
				return $this->response->setJSON(array(
					'error'   => false,
					'message' => "SUCCESS",
					'result'  => "SUCCESS"
				));//--finjson
				
			}
			else
			{
				$db->transRollback();						
				$errorCode 		= $db->error()["code"];
				$errorMessage 	= $db->error()["message"];				
				throw new \Exception($errorMessage);
			}
			
			
		}
		catch(\Exception $ex)
		{
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}	
		
	}
	
	
	function save($mode="")
	{
		$mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
		 
		//AUTENTICADO
		if(!$this->core_web_authentication->isAuthenticated())
		throw new \Exception(USER_NOT_AUTENTICATED);
		$dataSession		= $this->session->get();
		
		//Guardar o Editar Registro						
		if($mode == "new"){
			return $this->insertElement($dataSession);
		}
		else if ($mode == "edit"){
			return $this->updateElement($dataSession);
		}
		
		
			
		
	}
	
	
	
}
?>