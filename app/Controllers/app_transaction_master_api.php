<?php
//posme:2023-02-27
namespace App\Controllers;
class app_transaction_master_api extends _BaseController {
	
	function updateOrden_TransactionMaster_Task()
    {
        $mode = helper_SegmentsValue($this->uri->getSegments(), "save");
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited("app_rrhh_task","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd("app_rrhh_task","edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);

            }
			

            //Guardar o Editar Registro
            if ($mode == "edit") 
			{
			
				//Obtener el Componente de Tarea
				$objComponentTask			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
				if(!$objComponentTask)
					throw new \Exception("EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");

				$objComponentComments			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_comments");
				if(is_null($objComponentComments))
					throw new \Exception("EL COMPONENTE 'tb_comments' NO EXISTE...");

				$companyID 								= $dataSession["user"]->companyID;
				$userID 								= $dataSession["user"]->userID;
				$transactionID 							= $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_task", 0);				
				$objListTransactionMasterID				= /*inicio get post*/ explode(",", $this->request->getPost("txtTransactionMasterIDs"));
				$objListReference3						= /*inicio get post*/ explode(",", $this->request->getPost("txtReference3s"));
				

				
				$db=db_connect();
				$db->transStart();
				foreach($objListTransactionMasterID as $key => $value)
				{
					$transactionMasterID	= $objListTransactionMasterID[$key];
					$objTMNew["reference3"] = $objListReference3[$key];
					$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				}
				
				if($db->transStatus() !== false)
				{
					$db->transCommit();
					return $this->response->setJSON(array(
						'error'   => false,
						'message' => SUCCESS,
						'transactionMasterID'=>$transactionMasterID
					));//--finjson
					
				}
				else{
					$errorCode 		= $db->error()["code"];
					$errorMessage 	= 'Codigo: '.$errorCode . '; Mensaje '.$db->error()["message"];					
					return $this->response->setJSON(array(
						'error'   => true,
						'message' => $errorMessage 
					));//--finjson
				}
				
				
            } 
			else 
			{
                $this->core_web_notification->set_message(true,"El modo de operacion no es correcto (new|edit)");
				return $this->response->setJSON(array(
					'error'   => true,
					'message' => "El modo de operacion no es correcto (new|edit)"
				));//--finjson
            }


        } catch (\Exception $ex) {
           $this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
        }
    }
	
	function update_TransactionMaster_Task()
    {
        $mode = helper_SegmentsValue($this->uri->getSegments(), "save");
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited("app_rrhh_task","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd("app_rrhh_task","edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);

            }
			

            //Guardar o Editar Registro
            if ($mode == "edit") 
			{
			
				//Obtener el Componente de Tarea
				$objComponentTask			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
				if(!$objComponentTask)
					throw new \Exception("EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");

				$objComponentComments			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_comments");
				if(is_null($objComponentComments))
					throw new \Exception("EL COMPONENTE 'tb_comments' NO EXISTE...");

				$companyID 								= $dataSession["user"]->companyID;
				$userID 								= $dataSession["user"]->userID;
				$transactionID 							= $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_task", 0);				
				$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
				$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);

				//Validar Edicion por el Usuario
				if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $userID))
					throw new \Exception(NOT_EDIT);

				//Validar si el estado permite editar
				if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_task","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new \Exception(NOT_WORKFLOW_EDIT);

				//Actualizar Maestro
				$objTMNew["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
				$objTMNew["nextVisit"]					    = /*inicio get post*/ $this->request->getPost("txtNextVisit");
				$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
				$objTMNew["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");
				$objTMNew["priorityID"] 					= /*inicio get post*/ $this->request->getPost("txtPriorityID");
				$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
				$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
				$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
				$objTMNew["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtReference4");
				$objTMNew["descriptionReference"] 		    = /*inicio get post*/ $this->request->getPost("txtDescripcion");
				$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
				$objTMNew["entityID"]						= /*inicio get post*/ $this->request->getPost("txtAsignadoID");
				$objTMNew["entityIDSecondary"]				= /*inicio get post*/ $this->request->getPost("txtEmisorID");
				$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");

				$db=db_connect();
				$db->transStart();

				//El Estado solo permite editar el workflow
				if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_task","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
					$objTMNew								= array();
					$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
					$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				}
				else{
					$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				}

				//Actualizar Comentarios sobre el trabajo a realizar
				$comentariosTallerID		= $this->request->getPost("commentsDetailID");
				if(!is_null($comentariosTallerID)){
					$comentariosTaller 	= $this->request->getPost("txtComentarioTallerArray");
					$comentariosCatalogID= $this->request->getPost('txtCommentsIDArray');
					$contar = count($comentariosTaller)-1;
					$this->Transaction_Master_Detail_Model->deleteWhereIDNotInComponent($companyID,$transactionID,$transactionMasterID,$objComponentComments->componentID,$comentariosTallerID);
					for($i=$contar; $i>=0; $i--){
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $companyID;
						$objTMD["transactionID"] 				= $transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentComments->componentID;
						$objTMD['catalogStatusID']				= $comentariosCatalogID[$i];
						$objTMD["reference1"]					= $comentariosTaller[$i];
						if($comentariosTallerID[$i]==0){
							$objTMD['expirationDate']			= date('Y-m-d H:i:s');
							$objTMD["isActive"]					= 1;
							$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
						}else{
							$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$comentariosTallerID[$i],$objTMD);
						}
					}
				}

				if($db->transStatus() !== false)
				{
					$db->transCommit();
					return $this->response->setJSON(array(
						'error'   => false,
						'message' => SUCCESS,
						'transactionMasterID'=>$transactionMasterID
					));//--finjson
					
				}
				else{
					$errorCode 		= $db->error()["code"];
					$errorMessage 	= 'Codigo: '.$errorCode . '; Mensaje '.$db->error()["message"];					
					return $this->response->setJSON(array(
						'error'   => true,
						'message' => $errorMessage 
					));//--finjson
				}
				
				
            } 
			else 
			{
                $this->core_web_notification->set_message(true,"El modo de operacion no es correcto (new|edit)");
				return $this->response->setJSON(array(
					'error'   => true,
					'message' => "El modo de operacion no es correcto (new|edit)"
				));//--finjson
            }


        } catch (\Exception $ex) {
           $this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
        }
    }
	
	function create_TransactionMaster_Task()
    {
        $mode = helper_SegmentsValue($this->uri->getSegments(), "save");
		
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited("app_rrhh_task","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd("app_rrhh_task","add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);

            }
			
			
            //Guardar o Editar Registro
            if ($mode == "new") 
			{
                $this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");

				//Obtener el Componente de Tarea
				$objComponentTask			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
				if(!$objComponentTask)
					throw new \Exception("EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");

				$objComponentComments			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_comments");
				if(is_null($objComponentComments))
					throw new \Exception("EL COMPONENTE 'tb_comments' NO EXISTE...");

				if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
					throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO".$this->request->getPost("txtDate"));

				//Obtener transaccion
				$companyID 								= $dataSession["user"]->companyID;
				$branchID                               = $dataSession["user"]->branchID;
				$transactionID 							= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_task",0);
				$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($companyID,$transactionID);

				$objTM["companyID"] 					= $companyID;
				$objTM["transactionID"] 				= $transactionID;
				$objTM["branchID"]						= $branchID;
				$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($companyID, $branchID,"tb_transaction_master_task",0);
				$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($companyID,$transactionID);
				$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
				$objTM["nextVisit"]					    = /*inicio get post*/ $this->request->getPost("txtNextVisit");
				$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
				$objTM["componentID"] 					= $objComponentTask->componentID;
				$objTM["sign"] 							= $objT->signInventory;
				$objTM["currencyID"]					= null;
				$objTM["currencyID2"]					= null;
				$objTM["exchangeRate"]					= null;
				$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
				$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
				$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
				$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtReference4");
				$objTM["descriptionReference"] 			= /*inicio get post*/ $this->request->getPost("txtDescripcion");
				$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
				$objTM["amount"] 						= 0;
				$objTM["isApplied"] 					= 0;
				$objTM["journalEntryID"] 				= 0;
				$objTM["classID"] 						= NULL;
				$objTM["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtAreaID");
				$objTM["priorityID"] 					= /*inicio get post*/ $this->request->getPost("txtPriorityID");
				$objTM["sourceWarehouseID"]				= NULL;
				$objTM["targetWarehouseID"]				= NULL;
				$objTM["isActive"]						= 1;
				$objTM["entityID"]						= /*inicio get post*/ $this->request->getPost("txtAsignadoID");
				$objTM["entityIDSecondary"]				= /*inicio get post*/ $this->request->getPost("txtEmisorID");
				$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");


				$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);

				$db=db_connect();
				$db->transStart();

				$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);

				//Crear la Carpeta para almacenar los Archivos del Documento
				$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentTask->componentID."/component_item_".$transactionMasterID;
				if (!file_exists($documentoPath))
				{
					mkdir($documentoPath, 0755);
					chmod($documentoPath, 0755);
				}

				//Comentarios sobre la tarea a realizar
				$comentariosTarea					= $this->request->getPost("txtComentarioTareaArray");
				if(!is_null($comentariosTarea)){
					$comentariosCatalogID= $this->request->getPost('txtCommentsIDArray');
					$contar = count($comentariosTarea)-1;
					for($i=$contar; $i>=0; $i--){
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $objTM["companyID"];
						$objTMD["transactionID"] 				= $objTM["transactionID"];
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentComments->componentID;
						$objTMD['catalogStatusID']				= $comentariosCatalogID[$i];
						$objTMD["reference1"]					= $comentariosTarea[$i];
						$objTMD['expirationDate']				= date('Y-m-d H:i:s');
						$objTMD["isActive"]						= 1;
						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					}
				}

				//insertar notificacion en tabla error log
				$tagName		= "LLENAR NOTI DE OBLIGACION";
				$objTag			= $this->Tag_Model->get_rowByName($tagName);

				$userAsignado           = $this->User_Model->get_rowByEmployeeID($companyID, $objTM["entityID"]);
				if ($userAsignado) {
					$data					= null;
					$data["tagID"]			= $objTag->tagID;
					$data["notificated"]	= "notificar obligacion";
					$data["message"]		= "Tarea asiganda: ".$objTM["reference4"];
					$data["isActive"]		= 1;
					$data["userID"]			= $userAsignado->userID;
					$data["createdOn"]		= date_format(date_create(), "Y-m-d H:i:s");
					$this->Error_Model->insert_app_posme($data);
				}

				if($db->transStatus() !== false){
					$db->transCommit();
					return $this->response->setJSON(array(
						'error'   => false,
						'message' => SUCCESS,
						'transactionMasterID'=>$transactionMasterID
					));//--finjson
				}
				else{
					$db->transRollback();
					$errorCode 		= $db->error()["code"];
					$errorMessage 	= 'Codigo: '.$errorCode . '; Mensaje '.$db->error()["message"];					
					return $this->response->setJSON(array(
						'error'   => true,
						'message' => $errorMessage 
					));//--finjson
				}
				
            }  
			else 
			{
				$this->core_web_notification->set_message(true,"El modo de operacion no es correcto (new|edit)");
				return $this->response->setJSON(array(
					'error'   => true,
					'message' => "El modo de operacion no es correcto (new|edit)"
				));//--finjson
            }


        } 
		catch (\Exception $ex) 
		{
           $this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
        }
    }
	
	function delete_TransactionMaster_Task()
	{
		try{
            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited("app_rrhh_task","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd("app_rrhh_task","add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);

            }

			
            $companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
            $transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");
            $transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");
			$transactionNumber	 	= /*inicio get post*/ $this->request->getPost("transactionNumber");
			$transactionNumber		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionNumber");//--finuri
			$companyID 				= $dataSession["user"]->companyID;

            if((!$transactionNumber)){
                throw new \Exception(NOT_PARAMETER);
            }

			$objTM					= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$transactionNumber);
            $objTM	 				= $this->Transaction_Master_Model->get_rowByPK($objTM->companyID,$objTM->transactionID,$objTM->transactionMasterID);
            if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
                throw new \Exception(NOT_DELETE);

            //Si el documento esta aplicado crear el contra documento
            if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_task","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
                throw new \Exception(NOT_WORKFLOW_DELETE);

            //Eliminar el Registro
            $this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
            $this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);

            return $this->response->setJSON(array(
                'error'   => false,
                'message' => SUCCESS
            ));//--finjson
        }
		catch(\Exception $ex){
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
		
	}
	
	function getAll_TransactionMaster_Task()
    {
        try{
            //Validar Authentication
            if(!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

			$objListTask		= $this->Transaction_Master_Model->get_RowAll_TransactionMaster_Task();
            
            //Obtener Resultados.
            return $this->response->setJSON(array(
                'error'   			=> false,
                'message' 			=> SUCCESS,
                'data'	 	        => $objListTask
            ));//--finjson

        }
        catch(Exception $ex){
            return $this->response->setJSON(array(
                'error'   			=> true,
                'message' 			=> $ex->getMessage(),
                'data'	 	        => []
            ));//--finjson
        }
    }
}
?>