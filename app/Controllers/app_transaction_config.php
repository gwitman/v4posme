<?php
//posme:2023-02-27
namespace App\Controllers;
class app_transaction_config extends _BaseController {
	
    
	function apiDeleteProfileDetail(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");		
			$transactionCausalID 	= /*inicio get post*/ $this->request->getPost("transactionCausalID");
			$profileDetailID 		= /*inicio get post*/ $this->request->getPost("profileDetailID");
			
			if((!$companyID) || (!$transactionID) || (!$transactionCausalID) || (!$profileDetailID)) {
					throw new \Exception(NOT_PARAMETER);	
			} 
			
				
			$row				= $this->Transaction_Profile_Detail_Model->delete_app_posme($companyID,$transactionID,$transactionCausalID,$profileDetailID);
			
			
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
		}
	}
	function apiInsertProfileDetail(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");		
			$transactionCausalID 	= /*inicio get post*/ $this->request->getPost("transactionCausalID");
			$centerCostID 			= /*inicio get post*/ $this->request->getPost("centerCostID");
			$centerCostDescription 	= /*inicio get post*/ $this->request->getPost("centerCostDescription");
			$accountID 				= /*inicio get post*/ $this->request->getPost("accountID");
			$accountDescription 	= /*inicio get post*/ $this->request->getPost("accountDescription");
			$sign 					= /*inicio get post*/ $this->request->getPost("sign");
			$conceptID 				= /*inicio get post*/ $this->request->getPost("conceptID");
			$conceptDescription 	= /*inicio get post*/ $this->request->getPost("conceptDescription");
			if((!$companyID) || (!$transactionID) || (!$transactionCausalID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
						
				
			$objTPDNew["companyID"] 			= $companyID;
			$objTPDNew["transactionID"] 		= $transactionID;
			$objTPDNew["transactionCausalID"] 	= $transactionCausalID;
			$objTPDNew["conceptID"] 			= $conceptID;
			$objTPDNew["accountID"] 			= $accountID;
			$objTPDNew["classID"] 				= $centerCostID;
			$objTPDNew["sign"] 					= $sign;
			$profileDetailID					= $this->Transaction_Profile_Detail_Model->insert_app_posme($objTPDNew);
			$objTPD 							= $objTPDNew;
			$objTPD["profileDetailID"]			= $profileDetailID;
			$objTPD["centerCostDescription"] 	= /*inicio get post*/ $this->request->getPost("centerCostDescription");
			$objTPD["accountDescription"] 		= /*inicio get post*/ $this->request->getPost("accountDescription");
			$objTPD["conceptDescription"] 		= /*inicio get post*/ $this->request->getPost("conceptDescription");
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'objProfileDetail'   => $objTPD
			));//--finjson			
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	function apiGetInforCausal(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL." ".get_class($this));		
			}			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");		
			$transactionCausalID 	= /*inicio get post*/ $this->request->getPost("transactionCausalID");		
			if((!$companyID) || (!$transactionID) || (!$transactionCausalID)){
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			
			
			
			
			$objTC 		= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$transactionCausalID);
			$objTPD		= $this->Transaction_Profile_Detail_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$transactionCausalID);
			
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS,
				'objListTransactionProfileDetail'   => $objTPD,
				'objTransactionCausal'  			=> $objTC
			));//--finjson			
			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	function save(){
		try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();			
			
			//Validar Formulario						
			$this->validation->setRule("txtName","Nombre","required");    
			$this->validation->setRule("txtCompanyID","Compañia ID","required");    
			$this->validation->setRule("txtTransactionID","Transacion ID","required");    
			$companyID 		= /*inicio get post*/ $this->request->getPost("txtCompanyID");
			$transactionID 	= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			//Error Formulario no valido
			if($this->validation->withRequest($this->request)->run() != true ){
				$strTemp = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$strTemp);
				$this->response->redirect(base_url()."/".'app_transaction_config/edit/companyID/'.$companyID.'/transactionID/'.$transactionID);	
				exit;
			}
			
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
			
			 			
			
			
			//Iniciar Transacciones
			$db=db_connect();
			$db->transStart();	


			$objTransaction["journalTypeID"] 	= /*inicio get post*/ $this->request->getPost("txtJournalTypeID");
			$objTransaction["reference1"] 		= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTransaction["reference2"] 		= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTransaction["reference3"] 		= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTransaction["isCountable"] 		= /*inicio get post*/ $this->request->getPost("txtIsContabilize");
			$objTransaction["description"] 		= /*inicio get post*/ $this->request->getPost("txtDescription");
			$objListCausalID					= /*inicio get post*/ $this->request->getPost("txtCausalID");
			$result 							= $this->Transaction_Model->update_app_posme($companyID,$transactionID,$objTransaction);
					
			//Eliminar todas los Causales que no estan en el detalle del Cliente
			$result								= $this->Transaction_Causal_Model->delete_app_posme($companyID,$transactionID,$objListCausalID);
			//Insert o Update Causal
			if($objListCausalID)
			foreach($objListCausalID as $idex => $value){
				$causalID 											= $value;							
				$objNewTransactionCausal["branchID"]				= /*inicio get post*/ $this->request->getPost("txtCausalBranchID")[$idex];
				$objNewTransactionCausal["name"]					= /*inicio get post*/ $this->request->getPost("txtCausalName")[$idex];
				$objNewTransactionCausal["isDefault"]				= /*inicio get post*/ $this->request->getPost("txtCausalIsDefault")[$idex] == "true" ? 1 : 0 ;
				$objNewTransactionCausal["warehouseSourceID"]		= /*inicio get post*/ $this->request->getPost("txtCausalWarehouseSourceID")[$idex] == "" ? "NULL" : /*inicio get post*/ $this->request->getPost("txtCausalWarehouseSourceID")[$idex] ;
				$objNewTransactionCausal["warehouseTargetID"]		= /*inicio get post*/ $this->request->getPost("txtCausalWarehouseTargetID")[$idex] == "" ? "NULL" : /*inicio get post*/ $this->request->getPost("txtCausalWarehouseTargetID")[$idex];
				$objOldTransactionCausal 	= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$causalID);
				
				//Insert Causal
				if(!$objOldTransactionCausal){
					$objNewTransactionCausal["companyID"] 		= $companyID;
					$objNewTransactionCausal["transactionID"] 	= $transactionID;
					$objNewTransactionCausal["isActive"] 		= 1;					
					$result = $this->Transaction_Causal_Model->insert_app_posme($objNewTransactionCausal);
				}
				//Update Causal
				else{
					$result = $this->Transaction_Causal_Model->update_app_posme($companyID,$transactionID,$causalID,$objNewTransactionCausal);
				}
			}
			//Validar que hay un causal por defecto
			$countCausalDefault = $this->Transaction_Causal_Model->countCausalDefault($companyID,$transactionID);
			if($countCausalDefault == 0)
			throw new \Exception("Siempre el documento tiene que tener un Causal Principal.");
			
			
						
			//Redireccionar
			if ( $db->transStatus() === false)
			{
				$db->transRollback();	
				$this->core_web_notification->set_message(true,$this->db->_error_message());
			}
			
			if ( $db->transStatus() !== false )
			{
				$db->transCommit();
				$this->core_web_notification->set_message(false,SUCCESS);
			}
			
			$this->response->redirect(base_url()."/".'app_transaction_config/edit/companyID/'.$companyID.'/transactionID/'.$transactionID);	
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
	function add_causal(){
			
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//PERMISO SOBRE LA FUNCION
			if(APP_NEED_AUTHENTICATION == true){
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}
			
			//Cargar Librerias
			 	
			 	
			$D["objListWarehouse"]		= $this->Warehouse_Model->getByCompany($dataSession["user"]->companyID);
			$D["objListBranch"]			= $this->Branch_Model->getByCompany($dataSession["user"]->companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_transaction_config/popup_addcausal_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_transaction_config/popup_addcausal_body',$D);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_transaction_config/popup_addcausal_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r			
			
	}
	function edit(){ 
		 try{ 
			//AUTENTICACION			
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
			
			//Redireccionar datos
									
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$branchID 		= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;
			if((!$companyID || !$transactionID))
			{ 
				$this->response->redirect(base_url()."/".'app_transaction_config/index');	
			} 	
			
			
			 	
			 	
			 	
			 	
			 	
			 	
			
			
			$D["objListTransactionConcept"]		= $this->Transaction_Concept_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$D["objListAccount"]				= $this->Account_Model->getByCompanyOperative($dataSession["user"]->companyID);
			$D["objListCenterCost"]				= $this->Center_Cost_Model->getByCompany($dataSession["user"]->companyID);
			$D["objListJournalType"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction","journalTypeID",$dataSession["user"]->companyID);
			$D["objTransaction"]				= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$D["objWorkflow"]					= $this->Workflow_Model->get_rowByWorkflowID($D["objTransaction"]->workflowID);
			$D["objListTransactionCausal"]		= $this->Transaction_Causal_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_transaction_config/edit_head',$D);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_transaction_config/edit_body',$D);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_transaction_config/edit_script',$D);//--finview
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
		
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISO SOBRE LA FUNCION
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_transaction_config/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_transaction_config/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_transaction_config/list_script');//--finview
			$dataSession["script"]			= $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);   
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
	
}
?>