<?php
namespace App\Controllers;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;

class app_tools_endorsements extends _BaseController {

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
                $this->response->redirect(base_url()."/".'app_tools_endorsements/add');
            }

            //Obtener el componente de tarea
            $objComponentTask = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_endorsements");
            if (!$objComponentTask)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_endorsements' NO EXISTE...");

            $objParameterUrlPrinter					            = $this->core_web_parameter->getParameter("ENDORSEMENTS_URL_PRINTER",$companyID);
            $objParameterUrlPrinter 				            = $objParameterUrlPrinter->value;
            $objCatalog                                      	= $this->Catalog_Model->get_rowByName("Tipo de Endoso");
            $dataView["objTransactionMaster"]				    = $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $dataView["objTransactionMaster"]->transactionOn    = date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
            $dataView["objTransactionMasterReference"]          = $this->Transaction_Master_References_Model->get_rowByTransactionMaster($transactionMasterID);
            $dataView["catalogID"]		                        = $objCatalog->catalogID;
		
			$dataView["objCatalogItem"]							= $this->Catalog_Item_Model->get_rowByCatalogIDAndReference1($objCatalog->catalogID, $dataView["objTransactionMasterReference"]->reference6 ,$dataSession["company"]->flavorID );			
			if(!$dataView["objCatalogItem"])
			$dataView["objCatalogItem"]							= $this->Catalog_Item_Model->get_rowByCatalogIDAndReference1($objCatalog->catalogID, $dataView["objTransactionMasterReference"]->reference6 ,0 );			
		
            $dataView["objListWorkflowStage"]	                = $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_endorsements","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
            $dataView["objComponent"]                           = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction");;
            $dataView["tipoTransaccion"]                        = $this->Transaction_Model->getRowByCompanyId($companyID);
            $dataView["objParameterUrlPrinter"]		            = $objParameterUrlPrinter;

            //Renderizar Resultado
            $dataSession["notification"]= $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] 	= $this->core_web_notification->get_message();
            $dataSession["head"] 		= /*--inicio view*/ view('app_tools_endorsements/edit_head', $dataView);//--finview
            $dataSession["body"] 		= /*--inicio view*/ view('app_tools_endorsements/edit_body', $dataView);//--finview
            $dataSession["script"] 		= /*--inicio view*/ view('app_tools_endorsements/edit_script', $dataView);//--finview
            $dataSession["footer"] 		= "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        }catch (Exception $ex){
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

    function delete(): ResponseInterface
    {
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

            if( !$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_endorsements","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
                throw new \Exception(NOT_WORKFLOW_DELETE);

            $objTMRef = $this->Transaction_Master_References_Model->get_rowByTransactionMaster($transactionMasterID);
            //Eliminar el Registro
            $this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
            $this->Transaction_Master_References_Model->delete_app_posme($objTMRef->transactionMasterReferenceID);

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

    function updateElement($dataSession){
        try{
            //PERMISO SOBRE LA FUNCTION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            $objComponentShareCapital			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_endorsements");
            if(!$objComponentShareCapital)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_endorsements' NO EXISTE...");

            $companyID 				= $dataSession["user"]->companyID;
            $userID 				= $dataSession["user"]->userID;
            $transactionID 			= /*inicio get post*/ $this->request->getPost("txtTransactionIDEndoso");
            $transactionMasterID	= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
            $objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $objTMRef 				= $this->Transaction_Master_References_Model->get_rowByTransactionMaster($transactionMasterID);
            $oldStatusID 			= $objTM->statusID;

            //Validar Edicion por el Usuario
            if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $userID))
                throw new \Exception(NOT_EDIT);

            //Validar si el estado permite editar
            if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_endorsements","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
                throw new \Exception(NOT_WORKFLOW_EDIT);

            if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
                throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");

            //buscamos en el catalogo detail con el id, el campo a editar, y lo guardamos
            $catalogItemID			                = /*inicio get post*/ $this->request->getPost("txtValorModificar");
            $findCatalogoDetail                     = $this->Catalog_Item_Model->get_rowByCatalogItemID($catalogItemID);
            $objTMNew["transactionOn"]				= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTMNew['reference1']                 = /*inicio get post*/ $this->request->getPost("txtTransactionID");
            $objTMNew["statusIDChangeOn"]			= date("Y-m-d H:m:s");
            $objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");

            $db=db_connect();
            $db->transException(true)->transStart();

            //El Estado solo permite editar el workflow
            if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_endorsements","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
                $objTMNew								= array();
                $objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
                $this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
            }
            else{
                $this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
            }

            $valorAnterior          = /*inicio get post*/$this->request->getPost("txtSelectedTextValorAnterior");
            $valorNuevo             = /*inicio get post*/$this->request->getPost("txtSelectedTextValorNuevo");
            if (empty($valorAnterior)) {
                $valorAnterior = /*inicio get post*/$this->request->getPost("txtValorAnterior");
            }
            if (empty($valorNuevo)) {
                $valorNuevo = /*inicio get post*/$this->request->getPost("txtValorNuevo");
            }

            $objTMRefNew['transactionReferenceNumber']   = /*inicio get post*/ $this->request->getPost("txtTransactionNumber");;
            $objTMRefNew['reference1']                   = /*inicio get post*/ $this->request->getPost("txtTransactionMasterIDEndoso");
            $objTMRefNew['reference2']                   = $catalogItemID;
            $objTMRefNew['reference3']                   = $findCatalogoDetail->name;
            $objTMRefNew['refernece4']                   = $findCatalogoDetail->display;
            $objTMRefNew['refernece5']                   = $findCatalogoDetail->description;
            $objTMRefNew['reference6']                   = $findCatalogoDetail->reference1;
            $objTMRefNew['reference7']                   = $findCatalogoDetail->reference2;
            $objTMRefNew['reference8']                   = $findCatalogoDetail->reference3;
            $objTMRefNew['referecne9']                   = /*inicio get post*/$this->request->getPost("txtValorAnterior");
            $objTMRefNew['reference10']                  = /*inicio get post*/$this->request->getPost("txtValorNuevo");
            $objTMRefNew['reference11']                  = $valorAnterior;
            $objTMRefNew['reference12']                  = $valorNuevo;

            $this->Transaction_Master_References_Model->update_app_posme($objTMRef->transactionMasterReferenceID,$objTMRefNew);

            //Aplicar el Documento?
            if(
                $this->core_web_workflow->validateWorkflowStage
                (
                    "tb_transaction_master_endorsements",
                    "statusID",
                    $objTMNew["statusID"],
                    COMMAND_APLICABLE,
                    $dataSession["user"]->companyID,
                    $dataSession["user"]->branchID,
                    $dataSession["role"]->roleID
                ) &&
                $oldStatusID != $objTMNew["statusID"]
            ){
                $cadena             	= $objTMRefNew['refernece4'];                
                $table              	= explode('.', $cadena)[0];
                $campo              	= explode('.', $cadena)[1];
				$valueNuevo				= $objTMRefNew['reference10'];
                $primaryKey         	= helper_ObtenerClavePrimaria($db, $table);
				$transactionNumberFor		= $objTMRefNew['transactionReferenceNumber'];
				$transactionMasterIDFor		= $objTMRefNew['reference1'];
				$typeDocumentFor			= $objTMRefNew['reference6'];
				
                if ($table == "tb_transaction_master") 
				{
                    $query = "UPDATE ".$table." SET ".$campo." = ? WHERE transactionMasterID = ?;";
                    $db->query($query,[$valueNuevo, $transactionMasterIDFor ]);
                }
				else if ($table == "tb_transaction_master_info") 
				{
                    $query = "UPDATE ".$table." SET ".$campo." = ? WHERE transactionMasterID = ?;";
                    $db->query($query,[$valueNuevo, $transactionMasterIDFor ]);
                }
				else
					throw new \Exception("Configurar el tipo de endoso en codigo app_tools_endorsement.updateElement ");
				
				
            }

            if($db->transStatus() !== false)
            {
                $db->transCommit();
                $this->core_web_notification->set_message(false,SUCCESS);
                $this->response->redirect(base_url()."/".'app_tools_endorsements/edit/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID);
            }
            else{
                $errorCode 		= $db->error()["code"];
                $errorMessage 	= 'Codigo: '.$errorCode . '; Mensaje '.$db->error()["message"];
                $this->core_web_notification->set_message(true,$errorMessage);
                $this->response->redirect(base_url()."/".'app_tools_endorsements/add');
            }
        }catch (DatabaseException $e) {
            echo $e->getMessage();
        }
        catch (Exception $ex){
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

    function insertElement($dataSession){
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
            $objComponentEndoso			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_endorsements");
            if(!$objComponentEndoso)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_endorsements' NO EXISTE...");

            //Obtener transaccion
            $companyID 								= $dataSession["user"]->companyID;
            $branchID                               = $dataSession["user"]->branchID;
            $transactionID 							= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_endorsements",0);
            $objT 									= $this->Transaction_Model->getByCompanyAndTransaction($companyID,$transactionID);

            //buscamos en el catalogo detail con el id, el campo a editar, y lo guardamos
            $catalogItemID			                = /*inicio get post*/ $this->request->getPost("txtValorModificar");
            $findCatalogoDetail                     = $this->Catalog_Item_Model->get_rowByCatalogItemID($catalogItemID);

            $objTM["companyID"] 					= $companyID;
            $objTM["transactionID"] 				= $transactionID;
            $objTM["branchID"]						= $branchID;
            $objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($companyID, $branchID,"tb_transaction_master_endorsements",0);
            $objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
            $objTM["statusIDChangeOn"]				= date("c");
            $objTM["componentID"] 					= $objComponentEndoso->componentID;
            $objTM["sign"] 							= $objT->signInventory;
            $objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
            $objTM["amount"] 						= 0;
            $objTM["isApplied"] 					= 0;
            $objTM["journalEntryID"] 				= 0;
			$objTM['reference1']                    = /*inicio get post*/ $this->request->getPost("txtTransactionID");
            $objTM["classID"] 						= NULL;
            $objTM["sourceWarehouseID"]				= NULL;
            $objTM["targetWarehouseID"]				= NULL;
            $objTM["isActive"]						= 1;
            $objTM["note"] 							= "";

            $this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);

            $db = db_connect();
            $db->transException(true)->transStart();

            $transactionMasterID    = $this->Transaction_Master_Model->insert_app_posme($objTM);
            $valorAnterior          = /*inicio get post*/$this->request->getPost("txtSelectedTextValorAnterior");
            $valorNuevo             = /*inicio get post*/$this->request->getPost("txtSelectedTextValorNuevo");
			
            if (empty($valorAnterior)) {
                $valorAnterior = /*inicio get post*/$this->request->getPost("txtValorAnterior");
            }
            if (empty($valorNuevo)) {
                $valorNuevo = /*inicio get post*/$this->request->getPost("txtValorNuevo");
            }
			
            $objTMRef['transactionMasterID']           = $transactionMasterID;
            $objTMRef['createdOn']                     = date('c');
            $objTMRef['isActive']                      = 1;
            $objTMRef['transactionReferenceNumber']    = /*inicio get post*/ $this->request->getPost("txtTransactionNumber");;
            $objTMRef['reference1']                    = /*inicio get post*/ $this->request->getPost("txtTransactionMasterIDEndoso");
            $objTMRef['reference2']                    = $catalogItemID;
            $objTMRef['reference3']                    = $findCatalogoDetail->name;
            $objTMRef['refernece4']                    = $findCatalogoDetail->display;
            $objTMRef['refernece5']                    = $findCatalogoDetail->description;
            $objTMRef['reference6']                    = $findCatalogoDetail->reference1;
            $objTMRef['reference7']                    = $findCatalogoDetail->reference2;
            $objTMRef['reference8']                    = $findCatalogoDetail->reference3;
            $objTMRef['referecne9']                    = /*inicio get post*/$this->request->getPost("txtValorAnterior");
            $objTMRef['reference10']                   = /*inicio get post*/$this->request->getPost("txtValorNuevo");
            $objTMRef['reference11']                   = $valorAnterior;
            $objTMRef['reference12']                   = $valorNuevo;

            $this->Transaction_Master_References_Model->insert_app_posme($objTMRef);

            //Crear la Carpeta para almacenar los Archivos del Documento
            $documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentEndoso->componentID."/component_item_".$transactionMasterID;
            if (!file_exists($documentoPath))
            {
                mkdir($documentoPath, 0755, true);
                chmod($documentoPath, 0755);
            }


            if($db->transStatus() !== false){
                $db->transCommit();
                $this->core_web_notification->set_message(false,SUCCESS);
                $this->response->redirect(base_url()."/".'app_tools_endorsements/edit/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID);
            }
            else{
                $db->transRollback();
                $errorCode 		= $db->error()["code"];
                $errorMessage 	= 'Codigo: '.$errorCode . '; Mensaje '.$db->error()["message"];
                $this->core_web_notification->set_message(true, 'Rollback: '.$errorMessage);
                $this->response->redirect(base_url()."/".'app_tools_endorsements/add');
            }
        }catch (DatabaseException $e) {
            echo $e->getMessage();
        }
        catch (Exception $ex){
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

    function save($mode = ""){
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
                $this->response->redirect(base_url() . "/" . 'app_tools_endorsements/add');
                exit;
            }

            //Guardar o Editar Registro
            if ($mode == "new") {
                return $this->insertElement($dataSession);
            } else if ($mode == "edit") {
                return $this->updateElement($dataSession);
            } else {
                $stringValidation = "El modo de operacion no es correcto (new|edit)";
                $this->core_web_notification->set_message(true, $stringValidation);
                $this->response->redirect(base_url() . "/" . 'app_tools_endorsements/add');
                exit;
            }
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function add(){
        try{
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
            $objCatalog 	= $this->Catalog_Model->get_rowByName("Tipo de Endoso");
            $companyID      = $dataSession["user"]->companyID;
            $branchID       = $dataSession["user"]->branchID;
            $roleID         = $dataSession["role"]->roleID;
            $userID         = $dataSession["user"]->userID;
            $transactionID  = $this->core_web_transaction->getTransactionID($companyID, "tb_transaction_master_endorsements", 0);
            $objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction");
            $transacciones  = $this->Transaction_Model->getRowByCompanyId($companyID);

            $dataView["tipoTransaccion"]        = $transacciones;
            $dataView["objComponent"]           = $objComponent;
            $dataView["catalogID"]        		= $objCatalog->catalogID;
            $dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_endorsements","statusID",$companyID,$branchID,$roleID);

            //Renderizar Resultado
            $dataSession["notification"]= $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] 	= $this->core_web_notification->get_message();
            $dataSession["head"] 		= /*--inicio view*/ view('app_tools_endorsements/news_head', $dataView);//--finview
            $dataSession["body"] 		= /*--inicio view*/ view('app_tools_endorsements/news_body', $dataView);//--finview
            $dataSession["script"] 		= /*--inicio view*/ view('app_tools_endorsements/news_script', $dataView);//--finview
            $dataSession["footer"] 		= "";
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r

        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function index($dataViewID = null){
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

            //Obtener el componente para mostrar la lista de endosos
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_endorsements");
            if (!$objComponent)
                throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_endorsements' NO EXISTE...");


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
            $dataSession["head"] 			= /*--inicio view*/ view('app_tools_endorsements/list_head', $dataView);//--finview
            $dataSession["footer"] 			= /*--inicio view*/ view('app_tools_endorsements/list_footer', $dataView);//--finview
            $dataSession["body"] 			= $dataViewRender;
            $dataSession["script"] 			= /*--inicio view*/ view('app_tools_endorsements/list_script', $dataView);//--finview
            $dataSession["script"] 			= $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);
            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Exception $ex) {
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }

    function searchTransactionMaster(){
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

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);

            }

            $transactionNumber 	= /*inicio get post*/ $this->request->getPost("transactionNumber");

            if(!$transactionNumber){
                throw new \Exception(NOT_PARAMETER);
            }
            $objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID,$transactionNumber);

            if(!$objTM)
                throw new \Exception("NO SE ENCONTRO EL DOCUMENTO");



            return $this->response->setJSON(array(
                'error'   				=> false,
                'message' 				=> SUCCESS,
                'companyID' 			=> $objTM->companyID,
                'transactionID'			=> $objTM->transactionID,
                'transactionMasterID'	=> $objTM->transactionMasterID
            ));//--finjson

        }
        catch(\Exception $ex){

            return $this->response->setJSON(array(
                'error'   => true,
                'message' => $ex->getLine()." ".$ex->getMessage()
            ));//--finjson
        }
    }

	function getTransactionMasterOld($transactionNumber,$getValue)
	{
		
		try{
            //Validar Authentication
            if(!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();
            $companyID          = $dataSession["user"]->companyID;
			
			$db					= db_connect();
			$cadena             = $getValue;			
			$table              = explode('.', $cadena)[0];
			$campo              = explode('.', $cadena)[1];
			$typeDocument		= $transactionNumber;
			
			if (
				str_starts_with($typeDocument, "FAC")  &&
				$table	== "tb_transaction_master"
			) 
			{
				$query 	= " SELECT 
								".$campo." as Value 
							FROM  
								".$table."  
							WHERE 
								transactionNumber = ?;
						  ";
				$data 	= $db->query($query,[ $transactionNumber ])->getResult();
			}
			else if (
				str_starts_with($typeDocument, "FAC")  &&
				$table	== "tb_transaction_master_info"
			) 
			{
				$query 	= " SELECT 
								".$campo." as Value 
							FROM  
								".$table."  
							WHERE 
								transactionMasterID = (SELECT uu.transactionMasterID FROM tb_transaction_master uu where uu.transactionNumber = ?);
						  ";
				$data 	= $db->query($query,[ $transactionNumber ])->getResult();
			}
			else if (
				str_starts_with($typeDocument, "COMPRA")  &&
				$table	== "tb_transaction_master"
			) 
			{
				$query 	= " SELECT 
								".$campo." as Value 
							FROM  
								".$table."  
							WHERE 
								transactionMasterID = (SELECT uu.transactionMasterID FROM tb_transaction_master uu where uu.transactionNumber = ?);
						  ";
				$data 	= $db->query($query,[ $transactionNumber ])->getResult();
			}
			else 
				throw new \Exception("Configurar el tipo de endoso en codigo app_tools_endorsement.getTransactionMasterOld ");

			
            //Obtener Resultados.
            return $this->response->setJSON(array(
                'error'   			=> false,
                'message' 			=> SUCCESS,
                'data'	 	        => $data
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
    function getTransactionMaster($transactionNumber)
    {
        try{
            //Validar Authentication
            if(!$this->core_web_authentication->isAuthenticated())
                throw new Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();
            $companyID          = $dataSession["user"]->companyID;
			
		
			
			
			
            $data				= $this->Transaction_Master_Model->get_rowByTransactionNumber($companyID,$transactionNumber);

            //Obtener Resultados.
            return $this->response->setJSON(array(
                'error'   			=> false,
                'message' 			=> SUCCESS,
                'data'	 	        => $data
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

    function viewRegisterFormatoPaginaTicket($transactionID, $transactionMasterID)
    {
        try{
            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

            //PERMISO SOBRE LA FUNCION
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);


                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);
            }

            $companyID 					= $dataSession["user"]->companyID;
            $branchID 					= $dataSession["user"]->branchID;
            $roleID 					= $dataSession["role"]->roleID;

            //Get Component
            $objComponent		    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
            $objParameter		    = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
            $objParameterPhone	    = $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
            $objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
            $objCompany 		    = $this->Company_Model->get_rowByPK($companyID);

            //Get Documento
            $dataView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            if (!isset($dataView["objTM"])){
                throw new Exception("No existe la transacción o esta eliminada");
            }
            $dataView["objTMRef"]					= $this->Transaction_Master_References_Model->get_rowByTransactionMaster($transactionMasterID);
            $dataView["objTM"]->transactionOn 		= date_format(date_create($dataView["objTM"]->transactionOn),"Y-m-d");
            $dataView["objUser"] 					= $this->User_Model->get_rowByPK($dataView["objTM"]->companyID,$dataView["objTM"]->createdAt,$dataView["objTM"]->createdBy);
            $dataView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $dataView["objBranch"]					= $this->Branch_Model->get_rowByPK($dataView["objTM"]->companyID,$dataView["objTM"]->branchID);
            $dataView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_endorsements","statusID",$dataView["objTM"]->statusID,$companyID,$branchID,$roleID);

            //Generar Reporte
            $html = helper_reporte80mmTransactionMasterEndorsements(
                "ENDOSO",
                $objCompany,
                $objParameter,
                $dataView["objTM"],
                $dataView["objTMRef"],
                $objParameterTelefono,
                $dataView["objStage"][0]->display,
                $dataSession["user"]->nickname,
                ""
            );
            // Configurar Dompdf
            $options 	= new Options();
            $options->set('isHtml5ParserEnabled', true);

            $dompdf 	= new Dompdf($options);
            $dompdf->loadHTML($html);
            $dompdf->render();

            $objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
            $objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;

            //visualizar
            $dompdf->stream($dataView["objTMRef"]->transactionReferenceNumber.".pdf", ['Attachment' => $objParameterShowLinkDownload]);
        }catch (Exception $ex){
            if (empty($dataSession)) {
                return redirect()->to(base_url("core_acount/login"));
            }

            $data["session"]    = $dataSession;
            $data["exception"]  = $ex;
            $data["urlLogin"]   = base_url();
            $data["urlIndex"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
            $data["urlBack"]    = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView         = view("core_template/email_error_general", $data);

            return $resultView;
        }
    }
}
?>