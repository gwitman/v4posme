<?php 
//posme:2025-01-06
namespace App\Controllers;

use Exception;
class app_planilla_settlement extends _BaseController
{
    function index($dataViewID = null)
    {
        try
        {
            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
            {   
                throw new Exception(USER_NOT_AUTENTICATED);
            }
            
            $dataSession = $this->session->get();

            //PERSMISO SOBRE LA FUNCION
            if(APP_NEED_AUTHENTICATION == true)
            {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                
                if(!$permited)
                {
                    throw new Exception(NOT_ACCESS_CONTROL);
                }

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if($resultPermission == PERMISSION_NONE)
                {
                    throw new Exception(NOT_ACCESS_FUNCTION);
                }
            }

            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_settlement");
            if(!$objComponent)
            {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_settlement' NO EXISTE");
            }

           //Vista por defecto 
           if ($dataViewID == null || $dataViewID == "null") {
            $targetComponentID           = $this->session->get('company')->flavorID;
            $parameter["{companyID}"]    = $this->session->get('user')->companyID;
            $dataViewData                = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);


            if (!$dataViewData) {
                $targetComponentID           = 0;
                $parameter["{companyID}"]    = $this->session->get('user')->companyID;
                $dataViewData                = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
            }

            if ($dataSession["user"]->useMobile == 1) {
                $dataViewRender                = $this->core_web_view->renderGreedWithHtmlInFildMobile($dataViewData, 'ListView', "fnTableSelectedRow");
            } else {
                $dataViewRender                = $this->core_web_view->renderGreedWithHtmlInFild($dataViewData, 'ListView', "fnTableSelectedRow");
            }
        }
            //Otra vista
            else {
                $parameter["{companyID}"]    = $this->session->get('user')->companyID;
                $dataViewData                = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);

                if ($dataSession["user"]->useMobile == 1) {
                    $dataViewRender                = $this->core_web_view->renderGreedMobile($dataViewData, 'ListView', "fnTableSelectedRow");
                } else {
                    $dataViewRender                = $this->core_web_view->renderGreed($dataViewData, 'ListView', "fnTableSelectedRow");
                }
            }

            //RENDERIZAR RESULTADO
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession['user']->companyID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = view("app_planilla_settlement/list_head");
            $dataSession["footer"]          = view("app_planilla_settlement/list_footer");
            $dataSession["body"]            = $dataViewRender;
            $dataSession["script"]          = view("app_planilla_settlement/list_script");
            $dataSession["script"]          = $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);
            return view("core_masterpage/default_masterpage", $dataSession);
        }
        catch(Exception $ex){
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

    function save($mode = "")
    {
        $mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);
        
        try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtEmployeeSettlementStatusID","Estado","required");
			
			//Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_planilla_settlement/add');
                return;			
            } 
			
			//Guardar o Editar Registro						
			if($mode == "new"){
				$this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				$this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_planilla_settlement/add');
				return;
			}
			
			
		}
		catch(Exception $ex){
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

    function add()
    {
        try
        {
            if(!$this->core_web_authentication->isAuthenticated())
            {
                throw new Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession    = $this->session->get();

            if(APP_NEED_AUTHENTICATION == true)
            {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                
                if(!$permited)
                throw new Exception(NOT_ACCESS_CONTROL);
                
                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                throw new Exception(NOT_ALL_INSERT);	
            }

            $objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
            {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            $dataView["objComponentEmployee"]       = $objComponentEmployee;
			$dataView["objListWorkflowStage"]	    = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_settlement","statusID",$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID);

            //RENDERIZAR RESULTADO
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = view('app_planilla_settlement/news_head',$dataView);
            $dataSession["body"]            = view('app_planilla_settlement/news_body',$dataView);
            $dataSession["script"]          = view('app_planilla_settlement/news_script', $dataView);
            $dataSession["footer"]          = "";
            return view('core_masterpage/default_masterpage', $dataSession);

        }catch(Exception $ex){
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

    function edit()
    {
        try
        {
            if(!$this->core_web_authentication->isAuthenticated())
            {
                throw new Exception(USER_NOT_AUTENTICATED);
            }

            $dataSession    = $this->session->get();

            if(APP_NEED_AUTHENTICATION == true)
            {
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                
                if(!$permited)
                throw new Exception(NOT_ACCESS_CONTROL);
                
                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                throw new Exception(NOT_ALL_INSERT);	
            }

            $companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
			$companyID 				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
            

            $objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
            {
                throw new Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
            }

            if((!$companyID) || (!$transactionID) || (!$transactionMasterID)  )
			{ 
				$this->response->redirect(base_url()."/".'app_planilla_adelantos/add');	
			} 	

            $dataView["objTM"]                  = $this->Transaction_Master_Model->get_rowByPk($companyID,$transactionID,$transactionMasterID);
			$dataView["objTMI"]					= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $dataView["objEmployee"]            = $this->Employee_Model->get_rowByPK($companyID, $branchID, $dataView["objTM"]->entityID);
            $dataView["objListNaturales"]       = $this->Natural_Model->get_rowByPk($companyID, $branchID, $dataView["objTM"]->entityID);
            $dataView["objCatalogItem"]         = $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objEmployee"]->areaID);
            $dataView["objComponentEmployee"]   = $objComponentEmployee;
			$dataView["objListWorkflowStage"]   = $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_settlement","statusID",$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID);

            //RENDERIZAR RESULTADO
            $dataSession["notification"]    = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]         = $this->core_web_notification->get_message();
            $dataSession["head"]            = view('app_planilla_settlement/edit_head',$dataView);
            $dataSession["body"]            = view('app_planilla_settlement/edit_body',$dataView);
            $dataSession["script"]          = view('app_planilla_settlement/edit_script', $dataView);
            $dataSession["footer"]          = "";
            return view('core_masterpage/default_masterpage', $dataSession);

        }catch(Exception $ex){
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
    
    function insertElement($dataSession)
    {
        try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_INSERT);	
			}
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			//Obtener el Componente
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_settlement");
            if(!$objComponent)
            {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_settlement' NO EXISTE");
            }

            $companyID                      = $dataSession["user"]->companyID;
            $branchID                       = $dataSession["user"]->branchID;

            //Recoger datos del registro
            $transactionID 					= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_settlement",0);
            $objTM["companyID"]             = $dataSession["user"]->companyID;
            $objTM["transactionNumber"]     = $this->core_web_counter->goNextNumber($dataSession["user"]->companyID, $dataSession["user"]->branchID,"tb_transaction_master_settlement",0);
            $objTM["transactionID"]         = $transactionID;
            $objTM["branchID"]              = $dataSession["user"]->branchID;
            $objTM["transactionCausalID"]   = $this->core_web_transaction->getDefaultCausalID($objTM["companyID"],$objTM["transactionID"]);;
            $objTM["entityID"]              = $this->request->getPost("txtEmployeeEntityID");
            $objTM["transactionOn"]         = $this->request->getPost("txtTransactionOn");
            $objTM["transactionOn2"]        = $this->request->getPost("txtEmployeeBeginDate");
            $objTM["statusIDChangeOn"]      = date("Y-m-d H:m:s");
            $objTM["componentID"]           = $objComponent->componentID;
            $objTM["reference1"]            = $this->request->getPost("txtEmployeeWorkYears");
            $objTM["tax1"]                  = $this->request->getPost("txtEmployeeSalary");
            $objTM["tax2"]                  = $this->request->getPost("txtEmployeeAccumulatedVacations");
            $objTM["tax3"]                  = $this->request->getPost("txtEmployeeProporcionalSalary");
            $objTM["discount"]              = $this->request->getPost("txtEmployeeAmountSaving");
            $objTM["reference2"]            = $this->request->getPost("txtEmployeeComission");
            $objTM["statusID"]              = $this->request->getPost("txtEmployeeSettlementStatusID");
            $objTM["amount"]                = $this->request->getPost("txtEmployeeSettlement");
            $objTM["nextVisit"]             = $this->request->getPost("txtEmployeeEndDate");
            $objTM["isActive"]              = 1;
            $this->core_web_auditoria->setAuditCreated($objTM, $dataSession, $this->request);

            $objEmployeeEntityID            = $this->request->getPost("txtEmployeeEntityID");
            $objEmployee["endOn"]           = $this->request->getPost("txtEmployeeEndDate");
			
			$db =db_connect();
			$db->transStart();

			$transactionMasterID            = $this->Transaction_Master_Model->insert_app_posme($objTM);
            $this->Employee_Model->update_app_posme($companyID, $branchID, $objEmployeeEntityID, $objEmployee);
            
            //
            $objTMI["companyID"]            = $dataSession["user"]->companyID;
            $objTMI["transactionID"]        = $transactionID;
            $objTMI["transactionMasterID"]  = $transactionMasterID;
            $objTMI["changeAmount"]         = $this->request->getPost("txtEmployeeBonus");
            $objTMI["receiptAmount"]        = $this->request->getPost("txtEmployeeAccumulatedVacationsPayment");
            $objTMI["receiptAmountPoint"]   = $this->request->getPost("txtEmployeePaymentMonthThirteen");
            $objTMI["reference1"]           = $this->request->getPost("txtEmployeeSeniorityPayment");
            $objTMI["receiptAmountBank"]    = $this->request->getPost("txtEmployeeINSS");
            $objTMI["receiptAmountCard"]    = $this->request->getPost("txtEmployeeIR");
            $objTMI["receiptAmountCardDol"] = $this->request->getPost("txtEmployeeIRVacation");
            $objTMI["receiptAmountDol"]     = $this->request->getPost("txtEmployeeLoans");
            $objTMI["receiptAmountBankDol"] = $this->request->getPost("txtEmployeeLateArrival");
            $objTMI["reference2"]           = $this->request->getPost("txtEmployeeAdvancedPayment");

            $this->Transaction_Master_Info_Model->insert_app_posme($objTMI);


			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_planilla_settlement/edit/companyID/'.$dataSession["user"]->companyID.'/transactionID/'.$transactionID.'/transactionMasterID/'.$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_planilla_settlement/add');	
			}
			
			
		}
		catch(Exception $ex){
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

    function updateElement($dataSession)
    {
        try{
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new Exception(NOT_ALL_INSERT);	
			}
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			//Obtener el Componente
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_settlement");
            if(!$objComponent)
            {
                throw new Exception("00409 EL COMPONENTE 'tb_transaction_master_settlement' NO EXISTE");
            }

            $companyID              = $dataSession["user"]->companyID;
            $branchID               = $dataSession["user"]->branchID;
			$transactionID			= $this->request->getPost("txtTransactionID");
			$transactionMasterID	= $this->request->getPost("txtTransactionMasterID");
			$objTransactionMaster   = $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $oldStatusID            = $objTransactionMaster->statusID;

            $objTMNew["entityID"]               = $this->request->getPost("txtEmployeeEntityID");
            $objTMNew["transactionOn"]          = $this->request->getPost("txtTransactionOn");
            $objTMNew["transactionOn2"]         = $this->request->getPost("txtEmployeeBeginDate");
            $objTMNew["statusIDChangeOn"]       = date("Y-m-d H:m:s");
            $objTMNew["reference1"]             = $this->request->getPost("txtEmployeeWorkYears");
            $objTMNew["tax1"]                   = $this->request->getPost("txtEmployeeSalary");
            $objTMNew["tax2"]                   = $this->request->getPost("txtEmployeeAccumulatedVacations");
            $objTMNew["tax3"]                   = $this->request->getPost("txtEmployeeProporcionalSalary");
            $objTMNew["discount"]               = $this->request->getPost("txtEmployeeAmountSaving");
            $objTMNew["reference2"]             = $this->request->getPost("txtEmployeeComission");
            $objTMNew["statusID"]               = $this->request->getPost("txtEmployeeSettlementStatusID");
            $objTMNew["amount"]                 = $this->request->getPost("txtEmployeeSettlement");
            $objTMNew["nextVisit"]              = $this->request->getPost("txtEmployeeEndDate");

            $objEmployeeEntityID                = $this->request->getPost("txtEmployeeEntityID");
            $objEmployee["endOn"]               = $this->request->getPost("txtEmployeeEndDate");    
            
            $objTMI["changeAmount"]             = $this->request->getPost("txtEmployeeBonus");
            $objTMI["receiptAmount"]            = $this->request->getPost("txtEmployeeAccumulatedVacationsPayment");
            $objTMI["receiptAmountPoint"]       = $this->request->getPost("txtEmployeePaymentMonthThirteen");
            $objTMI["reference1"]               = $this->request->getPost("txtEmployeeSeniorityPayment");
            $objTMI["receiptAmountBank"]        = $this->request->getPost("txtEmployeeINSS");
            $objTMI["receiptAmountCard"]        = $this->request->getPost("txtEmployeeIR");
            $objTMI["receiptAmountCardDol"]     = $this->request->getPost("txtEmployeeIRVacation");
            $objTMI["receiptAmountDol"]         = $this->request->getPost("txtEmployeeLoans");
            $objTMI["receiptAmountBankDol"]     = $this->request->getPost("txtEmployeeLateArrival");
            $objTMI["reference2"]               = $this->request->getPost("txtEmployeeAdvancedPayment");

            //Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_settlement","statusID",$objTransactionMaster->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_EDIT);	

            $db =db_connect();
			$db->transStart();
            
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_settlement","statusID",$objTransactionMaster->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= $this->request->getPost("txtEmployeeSettlementStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);	
                $this->Transaction_Master_Info_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMI);
			}
                        
            if(
                $this->core_web_workflow->validateWorkflowStage
                (
                    "tb_transaction_master_settlement",
                    "statusID",
                    $objTMNew["statusID"],
                    COMMAND_APLICABLE,
                    $dataSession["user"]->companyID,
                    $dataSession["user"]->branchID,
                    $dataSession["role"]->roleID
                ) && $oldStatusID != $objTMNew["statusID"]
            ){
                $this->Employee_Model->update_app_posme($companyID, $branchID, $objEmployeeEntityID, $objEmployee);
            }

			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_planilla_settlement/edit/companyID/'.$dataSession["user"]->companyID.'/transactionID/'.$transactionID.'/transactionMasterID/'.$transactionMasterID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_planilla_settlement/add');	
			}
			
			
		}
		catch(Exception $ex){
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

    function delete(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                
                if(!$permited)
                throw new Exception(NOT_ACCESS_CONTROL);
						
                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"delete",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                throw new Exception(NOT_ALL_DELETE);			
			
			}				
			
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");				
			$transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
			
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
                throw new Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new Exception(NOT_DELETE);
				
			//Si el documento esta aplicado crear el contra documento			
			if( !$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_settlement","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro			
			$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
		}
		catch(Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
			
	}


	function searchTransactionMaster(){
		try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCTION
			if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                
                if(!$permited)
                throw new Exception(NOT_ACCESS_CONTROL);
            
                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                throw new Exception(NOT_ACCESS_FUNCTION);			
			
			}	
			  
			$transactionNumber 	= /*inicio get post*/ $this->request->getPost("transactionNumber");
			
			if(!$transactionNumber){
                throw new Exception(NOT_PARAMETER);			
			} 	

			$objTM 	= $this->Transaction_Master_Model->get_rowByTransactionNumber($dataSession["user"]->companyID,$transactionNumber);	
			
			if(!$objTM)
			throw new Exception("NO SE ENCONTRO EL DOCUMENTO");	
			
			
			
			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> SUCCESS,
				'companyID' 			=> $objTM->companyID,
				'transactionID'			=> $objTM->transactionID,
				'transactionMasterID'	=> $objTM->transactionMasterID
			));//--finjson
			
		}
		catch(Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}
}
?>