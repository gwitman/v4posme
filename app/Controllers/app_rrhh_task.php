<?php

namespace App\Controllers;

class app_rrhh_task extends _BaseController
{
    function edit(){
        try {
            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);

            $dataSession		= $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION){
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            $companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
            $transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
            $transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
            $branchID 				= $dataSession["user"]->branchID;
            $roleID 				= $dataSession["role"]->roleID;

            if((!$companyID || !$transactionID  || !$transactionMasterID))
            {
                $this->response->redirect(base_url()."/".'app_purchase_taller/add');
            }

            //Obtener el componente de tarea
            $objComponentTask = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
            if (!$objComponentTask)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");

            //Obtener el componente de colaborador
            $objComponentEmployer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployer)
                throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

            //Obtener el componente de comentarios a tareas
            $objComponentComments	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_comments");
            if(is_null($objComponentComments))
                throw new \Exception("EL COMPONENTE 'tb_comments' NO EXISTE...");

            $dataView["objTransactionMaster"]				    = $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $dataView["objTransactionMaster"]->transactionOn    = date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
            $dataView["objTransactionMaster"]->nextVisit        = date_format(date_create($dataView["objTransactionMaster"]->nextVisit),"Y-m-d");
            $dataView["objComponentEmployer"]                   = $objComponentEmployer;
            $dataView["objComponentTask"]                       = $objComponentTask;
            $dataView["objCaudal"]                              = $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
            $dataView["objListWorkflowStage"]                   = $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_task", "statusID", $dataView["objTransactionMaster"]->statusID, $companyID, $branchID, $roleID);
            $dataView["objListPrioridad"]                       = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_task", "priorityID", $companyID);
            $dataView["objListCategoria"]                       = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_task", "areaID", $companyID);
            $dataView["objListComments"]                        = $this->core_web_catalog->getCatalogAllItem("tb_comments", "catalogStatusID", $companyID);
            $dataView["objDetalleComentariosTarea"]		        = $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID, $transactionID, $transactionMasterID, $objComponentComments->componentID);

            //Obtener emisor
            $dataView["objEmisor"]				= $this->Employee_Model->get_rowByEntityID($companyID,$dataView["objTransactionMaster"]->entityIDSecondary);
            $dataView["objEmisorNatural"]		= $this->Natural_Model->get_rowByPK($dataView["objEmisor"]->companyID, $dataView["objEmisor"]->branchID, $dataView["objEmisor"]->entityID);
            $dataView["objEmisorLegal"]			= $this->Legal_Model->get_rowByPK($dataView["objEmisor"]->companyID, $dataView["objEmisor"]->branchID, $dataView["objEmisor"]->entityID);

            //Obtener asignado
            $dataView["objAsignado"]			= $this->Employee_Model->get_rowByEntityID($companyID,$dataView["objTransactionMaster"]->entityID);
            $dataView["objAsignadoNatural"]		= $this->Natural_Model->get_rowByPK($dataView["objAsignado"]->companyID, $dataView["objAsignado"]->branchID, $dataView["objAsignado"]->entityID);
            $dataView["objAsignadoLegal"]		= $this->Legal_Model->get_rowByPK($dataView["objAsignado"]->companyID, $dataView["objAsignado"]->branchID, $dataView["objAsignado"]->entityID);

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] = $this->core_web_notification->get_message();
            $dataSession["head"] = /*--inicio view*/ view('app_rrhh_task/edit_head', $dataView);//--finview
            $dataSession["body"] = /*--inicio view*/ view('app_rrhh_task/edit_body', $dataView);//--finview
            $dataSession["script"] = /*--inicio view*/ view('app_rrhh_task/edit_script', $dataView);//--finview
            $dataSession["footer"] = "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r

        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function delete(){
        try{
            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"delete",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);

            }

            $companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
            $transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");
            $transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");


            if((!$companyID && !$transactionID && !$transactionMasterID)){
                throw new \Exception(NOT_PARAMETER);
            }

            $objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
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

    function updateElement($dataSession)
    {
        try {
            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            //Obtener el Componente de Tarea
            $objComponentTask			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
            if(!$objComponentTask)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");

            $objComponentComments			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_comments");
            if(is_null($objComponentComments))
                throw new \Exception("EL COMPONENTE 'tb_comments' NO EXISTE...");

            $companyID 								= $dataSession["user"]->companyID;
            $userID 								= $dataSession["user"]->userID;
            $transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
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
                $this->core_web_notification->set_message(false,SUCCESS);
                $this->response->redirect(base_url()."/".'app_rrhh_task/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
            }
            else{
                $errorCode 		= $db->error()["code"];
                $errorMessage 	= 'Codigo: '.$errorCode . '; Mensaje '.$db->error()["message"];
                $this->core_web_notification->set_message(true,$errorMessage);
                $this->response->redirect(base_url()."/".'app_rrhh_task/add');
            }
        }catch (\Exception $ex){
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]   = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"]  = base_url();
            $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
            $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView        = view("core_template/email_error_general",$data);

            echo $resultView;
        }
    }

    function insertElement($dataSession)
    {
        try {
            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION){
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_INSERT);
            }

            $this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");

            //Obtener el Componente de Tarea
            $objComponentTask			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
            if(!$objComponentTask)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");

            $objComponentComments			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_comments");
            if(is_null($objComponentComments))
                throw new \Exception("EL COMPONENTE 'tb_comments' NO EXISTE...");

            if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
                throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");

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
                $this->core_web_notification->set_message(false,SUCCESS);
                $this->response->redirect(base_url()."/".'app_rrhh_task/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
            }
            else{
                $db->transRollback();
                $errorCode 		= $db->error()["code"];
                $errorMessage 	= 'Codigo: '.$errorCode . '; Mensaje '.$db->error()["message"];
                $this->core_web_notification->set_message(true,$errorMessage);
                $this->response->redirect(base_url()."/".'app_rrhh_task/add');
            }
        }catch(\Exception $ex){
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]   = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"]  = base_url();
            $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
            $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView        = view("core_template/email_error_general",$data);

            echo $resultView;
        }
    }

    function save($mode = "")
    {
        $mode = helper_SegmentsByIndex($this->uri->getSegments(), 1, $mode);
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //Validar Formulario
            $this->validation->setRule("txtStatusID", "Estado", "required");
            $this->validation->setRule("txtDate", "Fecha", "required");

            //Validar Formulario
            if (!$this->validation->withRequest($this->request)->run()) {
                $stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_rrhh_task/add');
                exit;
            }

            //Guardar o Editar Registro
            if ($mode == "new") {
                $this->insertElement($dataSession);
            } else if ($mode == "edit") {
                $this->updateElement($dataSession);
            } else {
                $stringValidation = "El modo de operacion no es correcto (new|edit)";
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_rrhh_task/add');
                exit;
            }


        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function add()
    {

        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "add", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_INSERT);

            }

            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $roleID = $dataSession["role"]->roleID;
            $userID = $dataSession["user"]->userID;
            $transactionID = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_task", 0);

            //Obtener el componente
            $objComponentTask = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
            if (!$objComponentTask)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");

            $objComponentEmployer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployer)
                throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

            $dataView["objComponentEmployer"] = $objComponentEmployer;
            $dataView["objComponentTask"] 	= $objComponentTask;
            $dataView["companyID"] 			= $companyID;
            $dataView["userID"] 			= $userID;
            $dataView["userName"] 			= $dataSession["user"]->nickname;
            $dataView["roleID"] 			= $roleID;
            $dataView["roleName"] 			= $dataSession["role"]->name;
            $dataView["branchID"] 			= $branchID;
            $dataView["branchName"] 		= $dataSession["branch"]->name;
            $dataView["company"] 			= $dataSession["company"];
            $dataView["objCaudal"] 			= $this->Transaction_Causal_Model->getCausalByBranch($companyID, $transactionID, $branchID);
            $dataView["objListWorkflowStage"] = $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_task", "statusID", $companyID, $branchID, $roleID);
            $dataView["objListPrioridad"] 	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_task", "priorityID", $companyID);
            $dataView["objListCategoria"] 	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_task", "areaID", $companyID);
            $dataView["objListComments"] 	= $this->core_web_catalog->getCatalogAllItem("tb_comments", "catalogStatusID", $companyID);

            //Renderizar Resultado
            $dataSession["notification"]= $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] 	= $this->core_web_notification->get_message();
            $dataSession["head"] 		= /*--inicio view*/ view('app_rrhh_task/news_head', $dataView);//--finview
            $dataSession["body"] 		= /*--inicio view*/ view('app_rrhh_task/news_body', $dataView);//--finview
            $dataSession["script"] 		= /*--inicio view*/ view('app_rrhh_task/news_script', $dataView);//--finview
            $dataSession["footer"] 		= "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r

        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function index($dataViewID = null)
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION) {
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);

            }

            //Obtener el componente para mostrar la lista de tareas
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_task");
            if (!$objComponent)
                throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_task' NO EXISTE...");


            //Vista por defecto
            if ($dataViewID == null) {
                $targetComponentID 			= 0;
                $parameter["{companyID}"] 	= $this->session->get('user')->companyID;
                $dataViewData 				= $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
                $dataViewRender 			= $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            } //Otra vista
            else {
                $parameter["{companyID}"] 	= $this->session->get('user')->companyID;
                $dataViewData 				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
                $dataViewRender 			= $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
            }

            //Renderizar Resultado
            $dataView["company"] 			= $dataSession["company"];
            $dataSession["notification"] 	= $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] 		= $this->core_web_notification->get_message();
            $dataSession["head"] 			= /*--inicio view*/ view('app_rrhh_task/list_head', $dataView);//--finview
            $dataSession["footer"] 			= /*--inicio view*/ view('app_rrhh_task/list_footer', $dataView);//--finview
            $dataSession["body"] 			= $dataViewRender;
            $dataSession["script"] 			= /*--inicio view*/ view('app_rrhh_task/list_script', $dataView);//--finview
            $dataSession["script"] 			= $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"] = $dataSession;
            $data["exception"] = $ex;
            $data["urlLogin"] = base_url();
            $data["urlIndex"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"] = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function searchTransactionMaster()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession = $this->session->get();

            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);

            }

            //Load Modelos
            //
            ////////////////////////////////////////
            ////////////////////////////////////////
            ////////////////////////////////////////


            //Nuevo Registro
            $transactionNumber = /*inicio get post*/
                $this->request->getPost("transactionNumber");


            if (!$transactionNumber) {
                throw new \Exception(NOT_PARAMETER);
            }
            $objTM = $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession['user']->companyID, $transactionNumber);

            if (!$objTM)
                throw new \Exception("NO SE ENCONTRO EL DOCUMENTO");


            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS,
                'companyID' => $objTM->companyID,
                'transactionID' => $objTM->transactionID,
                'transactionMasterID' => $objTM->transactionMasterID
            ));//--finjson

        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson
        }
    }
}
?>