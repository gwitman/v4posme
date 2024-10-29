<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_journal extends _BaseController {
	
       
    function edit(){ 
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_EDIT);			
			
			}	
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			 
			 			
			
			
						
			
			
			
			
			//Redireccionar datos
			
						
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$journalEntryID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"journalEntryID");//--finuri	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$journalEntryID))
			{ 
				$this->response->redirect(base_url()."/".'app_accounting_journal/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objJournalEntry"]	 			= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
			$datView["objJournalEntryDetail"]		= $this->Journal_Entry_Detail_Model->get_rowByJournalEntryID($companyID,$journalEntryID);
			$datView["objNextJournal"]				= $this->Journal_Entry_Model->get_rowByPK_Next($companyID,$journalEntryID);
			
			$datView["objBackJournal"]			= $this->Journal_Entry_Model->get_rowByPK_Back($companyID,$journalEntryID);
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_journal_entry");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_journal_entry' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentAccount					= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponentAccount)
			throw new \Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			//Obtener Informacion
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$datView["objExchangeRate"]			= $this->core_web_currency->getRatio($companyID,$datView["objJournalEntry"]->journalDate,1,$targetCurrency->currencyID,$objCurrency->currencyID);
			$datView["objCurrency"]				= $objCurrency;
			$datView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_journal_entry","statusID",$datView["objJournalEntry"]->statusID,$companyID,$branchID,$roleID);
			$datView["objListJournalType"]		= $this->core_web_catalog->getCatalogAllItem("tb_journal_entry","journalTypeID",$companyID);
			$datView["objListCurrency"]			= $this->Company_Currency_Model->getByCompany($companyID);
			$datView["objListAccount"]			= $this->Account_Model->getByCompanyOperative($companyID);
			$datView["objListClass"]			= $this->Center_Cost_Model->getByCompany($companyID);			
			$datView["objComponent"] 			= $objComponent;
			$datView["componentAccountID"] 		= $objComponentAccount->componentID;
			
			
			
			
			//Formato
			$datView["objJournalEntry"]->journalDate 		= date_format(date_create($datView["objJournalEntry"]->journalDate),"Y-m-d");
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_journal/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_journal/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_journal/edit_script',$datView);//--finview
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
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			  
			
			
			//Nuevo Registro
			$companyID 			= /*inicio get post*/ $this->request->getPost("companyID");
			$journalEntryID 	= /*inicio get post*/ $this->request->getPost("journalEntryID");				
			
			if((!$companyID && !$journalEntryID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL COMPROBANTE
			$obj 			= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);	
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_journal_entry","statusID",$obj->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			//SI EL CICLO YA ESTA CERRADO EL COMPROBANTE NO PUEDE SER ELIMINADO			
			$objx = $this->core_web_accounting->cycleIsCloseByID($companyID,$obj->accountingCycleID);
			if($objx)
			throw new \Exception("EL CICLO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER EDITADO");
			//inicio de transaccion
			$db=db_connect();
			$db->transStart();
			if($obj->isModule == 1)
			{
				$objTM						= $this->Transaction_Master_Model->get_rowByPK($obj->companyID,$obj->transactionID,$obj->transactionMasterID);
				$objTM["journalEntryID"]	= 0;
				$this->Transaction_Master_Model->update_app_posme($obj->companyID,$obj->transactionID,$obj->transactionMasterID,$objTM);
			}
			//Eliminar el Registro
			$this->Journal_Entry_Model->delete_app_posme($companyID,$journalEntryID);			
			
			//fin de transaccion
			if($db->transStatus() !== false){
				$db->transCommit();						
			}
			else{
				$db->transRollback();
				throw new \Exception("Error al intentar anular el comprobante.");
			}
			
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
			
	}
	
	function save($method = NULL){
		$method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			 
			
		
			//Validar Formulario						
			$this->validation->setRule("txtDate","Date","required");
			$this->validation->setRule("txtEntryName","EntryName","required");
			$this->validation->setRule("txtStatusID","Status","required");
			$this->validation->setRule("txtJournalType","JournalType","required");
			$this->validation->setRule("txtCurrencyID","Currency","required");
			
			 	
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
				
				
			
			
			
			
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_journal_entry");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_journal_entry' NO EXISTE...");
			
			$objCurrency							= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID);
			$targetCurrency							= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID);
			
			
			//Nuevo Registro			
			$continue	= true;
			if($method == "new" && /*inicio get post*/ $this->request->getPost("txtTemplatedNumber") != "" )
			{
				
				$journalEntryIDTemplated 	= /*inicio get post*/ $this->request->getPost("txtTemplatedNumber");
				$journalEntryID				= 0;
				$companyID					= $dataSession["user"]->companyID;
				$branchID					= $dataSession["user"]->branchID;
				$loginID					= $dataSession["user"]->userID;
				$app						= "PR_SELECTED_TEMPLATED";
				
				
				//ejecutar procedimiento.
				
				$query 					= "CALL pr_accounting_templated_to_journal(?,?,?,?,?,@resultTransaction);";
				$resultTransaction		= $this->Bd_Model->executeRender(
					$query,[$companyID,$branchID,$loginID,$app,$journalEntryIDTemplated]
				);
			
				$query 					= "SELECT @resultTransaction as codigo;";
				$resultTransaction		= $this->Bd_Model->executeRender($query,null);
				
				$journalEntryID			= $resultTransaction[0]["codigo"];
				$logDB					= $this->Log_Model->get_rowByPK($companyID,$branchID,$loginID,$app);
				
				
				
				//redireccionar a editar.
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_accounting_journal/edit/companyID/'.$companyID."/journalEntryID/".$journalEntryID);						
				
			}
			else if( $method == "new" && $this->validation->withRequest($this->request)->run() == true ){
					
					//PERMISO SOBRE LA FUNCTION
					if(APP_NEED_AUTHENTICATION == true){
						$permited = false;
						$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						
						if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
					
					}	
					$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
					//Ingresar Cuenta
					if($continue){
						$db=db_connect();
						$db->transStart();
						//Obtener Ciclo
						$objCycle											= $this->Component_Cycle_Model->get_rowByCompanyIDFecha($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate"));
						if(!$objCycle)
						throw new \Exception("TODO COMPROBANTE DEBE DE PERTENECER A UN CICLO");
						
						//Obtener la tasa de Cambio
						$objCurrency						= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID);
						$targetCurrency						= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID);			
						$exchangeRate						= $this->core_web_currency->getRatio($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate"),1,$targetCurrency->currencyID,/*inicio get post*/ $this->request->getPost("txtCurrencyID"));			
						
						if(!$exchangeRate)
							throw new \Exception("NO EXISTE LA TASA DE CAMBIO PARA:"./*inicio get post*/ $this->request->getPost("txtDate"));
							
							
						//No puede agregar comprobantes en un ciclo cerrado						
						$objx = $this->core_web_accounting->cycleIsCloseByID($dataSession["user"]->companyID,$objCycle->componentCycleID);
						if($objx)
						throw new \Exception("EL CICLO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER AGREGADO");
						
			
						//Crear Cuenta
						$objJournalEntry["companyID"]						= $dataSession["user"]->companyID;
						$objJournalEntry["journalNumber"] 					= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_journal_entry",/*inicio get post*/ $this->request->getPost("txtJournalType"));
						$objJournalEntry["entryName"] 						= /*inicio get post*/ $this->request->getPost("txtEntryName");
						$objJournalEntry["journalDate"] 					= /*inicio get post*/ $this->request->getPost("txtDate");
						$objJournalEntry["tb_exchange_rate"] 				= $exchangeRate;
						$objJournalEntry["isActive"] 						= 1;
						$objJournalEntry["isApplied"] 						= 0;
						$objJournalEntry["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$objJournalEntry["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");
						$objJournalEntry["reference1"] 						= /*inicio get post*/ $this->request->getPost("txtReference1");
						$objJournalEntry["reference2"] 						= /*inicio get post*/ $this->request->getPost("txtReference2");
						$objJournalEntry["reference3"] 						= /*inicio get post*/ $this->request->getPost("txtReference3");
						$objJournalEntry["journalTypeID"] 					= /*inicio get post*/ $this->request->getPost("txtJournalType");
						$objJournalEntry["currencyID"] 						= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
						$objJournalEntry["accountingCycleID"] 				= $objCycle->componentCycleID;
						$this->core_web_auditoria->setAuditCreated($objJournalEntry,$dataSession,$this->request);
						
						$journalEntryID						= $this->Journal_Entry_Model->insert_app_posme($objJournalEntry);
						$companyID 							= $objJournalEntry["companyID"];
						//Crear la Carpeta para almacenar los Archivos del Comprobante
						mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$journalEntryID, 0700);
						
						
						//Guardar el Detalle
						$objListJournalEntryDetailAccount	= /*inicio get post*/ $this->request->getPost("txtAccountID");
						$objListJournalEntryDetailClass		= /*inicio get post*/ $this->request->getPost("txtClassID");
						$objListJournalEntryDetailDebit		= /*inicio get post*/ $this->request->getPost("txtDebit");
						$objListJournalEntryDetailCredit	= /*inicio get post*/ $this->request->getPost("txtCredit");
					
						if($objListJournalEntryDetailAccount)
						foreach($objListJournalEntryDetailAccount as $key => $value){
							$objJournalEntryDetailID["companyID"] 			= $companyID;
							$objJournalEntryDetailID["journalEntryID"] 		= $journalEntryID;
							$objJournalEntryDetailID["accountID"] 			= $value;
							$objJournalEntryDetailID["isActive"] 			= 1;
							$objJournalEntryDetailID["classID"] 			= $objListJournalEntryDetailClass[$key];
							$objJournalEntryDetailID["debit"] 				= helper_StringToNumber($objListJournalEntryDetailDebit[$key]);
							$objJournalEntryDetailID["credit"] 				= helper_StringToNumber($objListJournalEntryDetailCredit[$key]);
							$objJournalEntryDetailID["note"] 				= "";
							$objJournalEntryDetailID["isApplied"] 			= 0;
							$objJournalEntryDetailID["branchID"] 			= $dataSession["user"]->branchID;
							$objJournalEntryDetailID["tb_exchange_rate"] 	= $exchangeRate;
							
							$this->Journal_Entry_Detail_Model->insert_app_posme($objJournalEntryDetailID);
						}
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_accounting_journal/edit/companyID/'.$companyID."/journalEntryID/".$journalEntryID);						
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_accounting_journal/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_accounting_journal/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->validation->withRequest($this->request)->run() == true) {
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
					 
					//PERMISO SOBRE EL REGISTRO
					$messageTmp			= '';
					$companyID 			= $dataSession["user"]->companyID;
					$journalEntryID		= /*inicio get post*/ $this->request->getPost("txtJournalEntryID");
					$objOld = $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
					//PERMISO PUEDE EDITAR EL REGISTRO
					if(!$this->core_web_workflow->validateWorkflowStage("tb_journal_entry","statusID",$objOld->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new \Exception(NOT_WORKFLOW_EDIT);					
					
					//NO PUEDE EDITAR UN COMPROBANTE QUE PERTENECE A UN CICLO CERRADO
					$objx = $this->core_web_accounting->cycleIsCloseByID($companyID,$objOld->accountingCycleID);
					if($objx)
					throw new \Exception("EL CICLO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER EDITADO");
					
					
					if($continue){
						$db=db_connect();
						$db->transStart();
						
						if(!$this->core_web_workflow->validateWorkflowStage("tb_journal_entry","statusID",$objOld->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
						{
							$companyID 			= $dataSession["user"]->companyID;
							$journalEntryID		= /*inicio get post*/ $this->request->getPost("txtJournalEntryID");
							$objJournalEntryOld	= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
							
							
							//Obtener Ciclo
							$objCycle												= $this->Component_Cycle_Model->get_rowByCompanyIDFecha($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate"));
							if(!$objCycle)
							throw new \Exception("TODO COMPROBANTE DEBE DE PERTENECER A UN CICLO");
							
							//Obtener la tasa de Cambio
							$objCurrency						= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID);
							$targetCurrency						= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID);									
							$exchangeRate						= $this->core_web_currency->getRatio($companyID,/*inicio get post*/ $this->request->getPost("txtDate"),1,$targetCurrency->currencyID,/*inicio get post*/ $this->request->getPost("txtCurrencyID"));			
							
							if(!$exchangeRate)
							throw new \Exception("NO EXISTE LA TASA DE CAMBIO PARA:"./*inicio get post*/ $this->request->getPost("txtDate"));
							
							//validar si puede guardar el comprobante en la fecha celeccionado 
							$objx = $this->core_web_accounting->cycleIsCloseByID($companyID,$objCycle->componentCycleID);
							if($objx)
							throw new \Exception("EL CICLO DESTINO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER EDITADO");
					
							//Actualizar Cuenta
							$objJournalEntryNew["entryName"] 						= /*inicio get post*/ $this->request->getPost("txtEntryName");
							$objJournalEntryNew["journalDate"] 						= /*inicio get post*/ $this->request->getPost("txtDate");
							$objJournalEntryNew["tb_exchange_rate"]					= $exchangeRate;
							$objJournalEntryNew["isActive"] 						= 1;
							$objJournalEntryNew["isApplied"] 						= 0;
							$objJournalEntryNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
							$objJournalEntryNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");
							$objJournalEntryNew["reference1"] 						= /*inicio get post*/ $this->request->getPost("txtReference1");
							$objJournalEntryNew["reference2"] 						= /*inicio get post*/ $this->request->getPost("txtReference2");
							$objJournalEntryNew["reference3"] 						= /*inicio get post*/ $this->request->getPost("txtReference3");
							$objJournalEntryNew["journalTypeID"] 					= /*inicio get post*/ $this->request->getPost("txtJournalType");
							$objJournalEntryNew["currencyID"] 						= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
							
							$objJournalEntryNew["isTemplated"] 						= /*inicio get post*/ $this->request->getPost("txtIsTemplated");
							
							$objJournalEntryNew["titleTemplated"] 					= /*inicio get post*/ $this->request->getPost("txtTitleTemplated");
							$objJournalEntryNew["accountingCycleID"] 				= $objCycle->componentCycleID;
							//Actualizar Objeto
							$row_affected 	= $this->Journal_Entry_Model->update_app_posme($companyID,$journalEntryID,$objJournalEntryNew);
							
							
							
							//Guardar el Detalle
							$objListJournalEntryDetailID		= /*inicio get post*/ $this->request->getPost("txtJournalEntryDetailID");
							$objListJournalEntryDetailAccount	= /*inicio get post*/ $this->request->getPost("txtAccountID");
							$objListJournalEntryDetailClass		= /*inicio get post*/ $this->request->getPost("txtClassID");
							$objListJournalEntryDetailDebit		= /*inicio get post*/ $this->request->getPost("txtDebit");
							$objListJournalEntryDetailCredit	= /*inicio get post*/ $this->request->getPost("txtCredit");
						
							//Eliminar Los detalle que no estan
							$this->Journal_Entry_Detail_Model->deleteWhereIDNotIn($companyID,$journalEntryID,$objListJournalEntryDetailID);
							
							$debitTotal 	= 0;
							$creditTotal 	= 0;
							if($objListJournalEntryDetailAccount)
							foreach($objListJournalEntryDetailAccount as $key => $value){
								
								$objJournalEntryDetailID["accountID"] 			= $value;
								$objJournalEntryDetailID["isActive"] 			= 1;
								$objJournalEntryDetailID["classID"] 			= $objListJournalEntryDetailClass[$key];
								$objJournalEntryDetailID["debit"] 				= helper_StringToNumber($objListJournalEntryDetailDebit[$key]);
								$objJournalEntryDetailID["credit"] 				= helper_StringToNumber($objListJournalEntryDetailCredit[$key]);
								$objJournalEntryDetailID["note"] 				= "";
								$objJournalEntryDetailID["isApplied"] 			= 0;
								$objJournalEntryDetailID["branchID"] 			= $dataSession["user"]->branchID;
								$objJournalEntryDetailID["tb_exchange_rate"] 	= $exchangeRate;
								$journalEntryDetailID 							= $objListJournalEntryDetailID[$key];
								$debitTotal 									= $debitTotal + $objJournalEntryDetailID["debit"];
								$creditTotal 									= $creditTotal + $objJournalEntryDetailID["credit"];
								
								if($journalEntryDetailID)
									$this->Journal_Entry_Detail_Model->update_app_posme($companyID,$journalEntryID,$journalEntryDetailID,$objJournalEntryDetailID);
								else{
									$objJournalEntryDetailID["companyID"] 			= $companyID;
									$objJournalEntryDetailID["journalEntryID"] 		= $journalEntryID;
									$this->Journal_Entry_Detail_Model->insert_app_posme($objJournalEntryDetailID);
								}
							}
							
							//Actualizar Maestro 
							$objJournalEntryNew["debit"] 	= $debitTotal;
							$objJournalEntryNew["credit"] 	= $creditTotal;
							$row_affected 	= $this->Journal_Entry_Model->update_app_posme($companyID,$journalEntryID,$objJournalEntryNew);
						}
						else{
							$companyID 						= $dataSession["user"]->companyID;
							$journalEntryID					= /*inicio get post*/ $this->request->getPost("txtJournalEntryID");
							$objJournalEntryNew["statusID"] = /*inicio get post*/ $this->request->getPost("txtStatusID");							
							$row_affected 					= $this->Journal_Entry_Model->update_app_posme($companyID,$journalEntryID,$objJournalEntryNew);
							$messageTmp						= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
						}
						
						//AUDITORIA
						$objNew = $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
						$this->core_web_auditoria->setAudit("tb_journal_entry",$objOld,$objNew,$dataSession,$this->request);
						
						//CREAR LA CARPETA
						$pathFile = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$journalEntryID;
						if(!file_exists($pathFile))
						mkdir($pathFile, 0700);
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_accounting_journal/edit/companyID/'.$companyID."/journalEntryID/".$journalEntryID);
					}					
					else{
						$this->response->redirect(base_url()."/".'app_accounting_journal/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_journal/add');	
			} 
			
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
	
	function add(){ 
	
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
			
			}	
			 
			
			
			
			$dataView							= null;
			
			//Obtener el componente de Item
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_account");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_account' NO EXISTE...");
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$dataView["componentAccountID"] 	= $objComponent->componentID;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);
			$dataView["objCurrency"]			= $objCurrency;
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_journal_entry","statusID",$companyID,$branchID,$roleID);
			$dataView["objListJournalType"]		= $this->core_web_catalog->getCatalogAllItem("tb_journal_entry","journalTypeID",$companyID);
			$dataView["objListCurrency"]		= $this->Company_Currency_Model->getByCompany($companyID);
			$dataView["objListAccount"]			= $this->Account_Model->getByCompanyOperative($companyID);
			$dataView["objListClass"]			= $this->Center_Cost_Model->getByCompany($companyID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_journal/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_journal/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_journal/news_script',$dataView);//--finview
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
	function index($dataViewID = null){	
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
			//Obtener el componente Para mostrar la lista de AccountType
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_journal_entry");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_journal_entry' NO EXISTE...");
			
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_journal/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_account_journal/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_account_journal/list_script');//--finview
			$dataSession["script"] 			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);  
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
	function searchJournal(){
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
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			
			//Nuevo Registro
			$journalNumber 			= /*inicio get post*/ $this->request->getPost("journalNumber");
			
			
			if(!$journalNumber){
					throw new \Exception(NOT_PARAMETER);			
			} 			
			$obj 	= $this->Journal_Entry_Model->get_rowByCode($dataSession["user"]->companyID,$journalNumber);	
			
			if(!$obj)
			throw new \Exception("NO SE ENCONTRO EL COMPROBANTE");	
			
			
			
			return $this->response->setJSON(array(
				'error'   			=> false,
				'message' 			=> SUCCESS,
				'companyID' 		=> $obj->companyID,
				'journalEntryID'	=> $obj->journalEntryID
			));//--finjson
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
		}
	}
	function viewAudit(){
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
			
								
			$journalEntryID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"journalEntryID");//--finuri			
			$companyID 			= $dataSession["user"]->companyID;		
			$branchID 			= $dataSession["user"]->branchID;		
			$roleID 			= $dataSession["role"]->roleID;		
				
			
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Journal
			$datView["objJournalEntry"]	 					= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
			$dataView["objDataAudit"]						= $this->core_web_auditoria->getAuditDetail($companyID,$journalEntryID,"tb_journal_entry");
			
			
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
	function viewRegister(){
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
			
								
			$journalEntryID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"journalEntryID");//--finuri			
			$companyID 			= $dataSession["user"]->companyID;		
			$branchID 			= $dataSession["user"]->branchID;		
			$roleID 			= $dataSession["role"]->roleID;		
				
			
			//Get Component
			$objComponent			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);		
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);


			
			//Get Journal
			$datView["objJournalEntry"]	 					= $this->Journal_Entry_Model->get_rowByPK($companyID,$journalEntryID);
			$datView["objJournalEntryDetail"]				= $this->Journal_Entry_Detail_Model->get_rowByJournalEntryID($companyID,$journalEntryID);								
			$datView["objJournalEntry"]->journalDate 		= date_format(date_create($datView["objJournalEntry"]->journalDate),"Y-m-d");
			$datView["objStage"]							= $this->core_web_workflow->getWorkflowStage("tb_journal_entry","statusID",$datView["objJournalEntry"]->statusID,$companyID,APP_BRANCH,APP_ROL_SUPERADMIN);
			
			//Generar Reporte
			$html = helper_reporteA4mmJournalEntry(
			    "COMPROBANTE",
			    $objCompany,
			    $objParameter,
			    $datView,
			    $objParameterTelefono
			);
			$this->dompdf->loadHTML($html);
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			if($objParameterShowLinkDownload == "true")
			{
				$fileNamePut = "journal_".$journalEntryID."_".date("dmYhis").".pdf";
				$path        = "./resource/file_company/company_".$companyID."/component_16/component_item_".$journalEntryID."/".$fileNamePut;
				
				file_put_contents(
					$path , 
					$this->dompdf->output()
				);								
				
				chmod($path, 644);
				
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_16/component_item_".$journalEntryID."/".
					$fileNamePut."'>download comprobante</a>
				"; 				

			}
			else{			
				//visualizar
				$this->dompdf->stream("file.pdf ", ['Attachment' =>  true ]);
			}
			
			
			
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