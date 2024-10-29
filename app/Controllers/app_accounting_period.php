<?php
//posme:2023-02-27
namespace App\Controllers;
class app_accounting_period extends _BaseController {
	
   
    function edit(){  
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
			
			//Set Datos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
				
			
			
			
			
			
			//Redireccionar datos
			
						
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$componentID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentID");//--finuri	
			$componentPeriodID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"componentPeriodID");//--finuri	
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			if((!$companyID || !$componentID || !$componentPeriodID))
			{ 
				$this->response->redirect(base_url()."/".'app_accounting_period/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objComponentPeriod"]	 		 = $this->Component_Period_Model->get_rowByPK($componentPeriodID);
			$datView["objComponentPeriod"]->startOn  = date_format(date_create($datView["objComponentPeriod"]->startOn),"Y-m-d");
			$datView["objComponentPeriod"]->endOn 	 = date_format(date_create($datView["objComponentPeriod"]->endOn),"Y-m-d");						
			$datView["objListComponentPeriodStatus"] = $this->core_web_workflow->getWorkflowStageByStageInit("tb_accounting_period","statusID",$datView["objComponentPeriod"]->statusID,$companyID,$branchID,$roleID);			
			$datView["objListComponentCycle"] 		 = $this->Component_Cycle_Model->getByComponentPeriodID($datView["objComponentPeriod"]->componentPeriodID);
			if($datView["objListComponentCycle"])
			foreach($datView["objListComponentCycle"] as &$i){
				$i->startOn = date_format(date_create($i->startOn),"Y-m-d");
				$i->endOn 	= date_format(date_create($i->endOn),"Y-m-d");
			}						
			$datView["objListComponentCycleStatus"] = $this->core_web_workflow->getWorkflowAllStage("tb_accounting_cycle","statusID",$companyID,$branchID,$roleID);
			
			
			//Obtener los Permisos Core
			$datView["objUserPermission"]			= $this->User_Permission_Model->get_rowByCompanyIDyBranchIDyRoleID($companyID,$branchID,$roleID);
			//Obtener las Autorization Core
			$datView["listComponentAutoriation"]	= $this->Role_Autorization_Model->get_rowByRoleAutorization($companyID,$branchID,$roleID);
			
			
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_period/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_period/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_period/edit_script',$datView);//--finview
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
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$componentID 			= /*inicio get post*/ $this->request->getPost("componentID");				
			$componentPeriodID		= /*inicio get post*/ $this->request->getPost("componentPeriodID");				
			
			if((!$companyID && !$componentID && !$componentPeriodID)){
					throw new \Exception(NOT_PARAMETER);								 
			} 
			
			$obj 					= $this->Component_Period_Model->get_rowByPK($componentPeriodID);	
			$objListCycle			= $this->Component_Cycle_Model->getByComponentPeriodID($componentPeriodID);	
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_accounting_period","statusID",$obj->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			//PERIODO CONTIENE COMPROBANTES
			$bo = $this->core_web_accounting->periodIsEmptyByID($companyID,$componentPeriodID);
			if(!$bo)
			throw new \Exception("El PERIODO CONTABLE CONTIENE COMPROBANTES VALIDOS");
			
			
			//Eliminar el Registro
			$db=db_connect();
			$db->transStart();
			$this->Component_Period_Model->delete_app_posme($companyID,$componentID,$componentPeriodID);
			foreach($objListCycle as $itemCycle){				
				$this->Component_Cycle_Model->delete_app_posme($itemCycle->componentCycleID);
			}
			
			if($db->transStatus() !== false){
				$db->transCommit();									
			}
			else{
				$db->transRollback();										
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
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Load Modelos			
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			 			  
			 
					
			
			//Validar Formulario						
			$this->validation->setRule("txtName","Name","required");    
			$this->validation->setRule("txtStatusID","Status","required");
			$this->validation->setRule("txtStartOn","startOn","required");
			$this->validation->setRule("txtEndOn","endOn","required");
			
			 
			//Nuevo Registro			
			$continue				= true;
			$objComponentAccounting = $this->core_web_tools->getComponentIDBy_ComponentName("0-CONTABILIDAD");
			
			if( $method == "new" && $this->validation->withRequest($this->request)->run() == true ){
					
					//PERMISO SOBRE LA FUNCION
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
					//Ingresar Periodo
					if($continue){
						$db=db_connect();
						$db->transStart();
						$obj["companyID"]		= $dataSession["user"]->companyID;
						$obj["componentID"]		= $objComponentAccounting->componentID;
						$obj["number"]			= date("YmdHis");
						$obj["name"]			= /*inicio get post*/ $this->request->getPost("txtName");
						$obj["description"]		= /*inicio get post*/ $this->request->getPost("txtDescription");
						$obj["startOn"]			= /*inicio get post*/ $this->request->getPost("txtStartOn");
						$obj["endOn"]			= /*inicio get post*/ $this->request->getPost("txtEndOn");
						$obj["statusID"]		= /*inicio get post*/ $this->request->getPost("txtStatusID");
						$obj["isActive"]		= 1;
						$this->core_web_auditoria->setAuditCreated($obj,$dataSession,$this->request);
						
						//Validar Periodo
						$result					= $this->Component_Period_Model->validateTime($obj["companyID"],$obj["componentID"],$obj["startOn"],$obj["endOn"]);
						if($result){
							throw new \Exception("000243 EL PERIODO NO PUEDE SER CREADO, POR QUE ESTARIA SOLAPADO CON OTRO PERIODO CONTABLE...");
						}
						//Ingresar Periodo
						$componentPeriodID 		= $this->Component_Period_Model->insert_app_posme($obj);
						
						
						//Ingresar los Ciclos
						$objListCycleStartOn 	= /*inicio get post*/ $this->request->getPost("txtCycleStartOn");
						$objListCycleEndOn 		= /*inicio get post*/ $this->request->getPost("txtCycleEndOn");
						$objListCycleStatusID 	= /*inicio get post*/ $this->request->getPost("txtCycleStatusID");
						$objListCycleNumber 	= /*inicio get post*/ $this->request->getPost("txtCycleNumber");
						if($objListCycleStartOn)
						foreach($objListCycleStartOn as $key => $value){
							$objCycle["componentPeriodID"]		= $componentPeriodID;
							$objCycle["companyID"]				= $obj["companyID"];
							$objCycle["componentID"] 			= $obj["componentID"];
							$objCycle["number"] 				= $obj["number"];
							$objCycle["name"]					= $obj["number"];
							$objCycle["description"] 			= $obj["description"];
							$objCycle["startOn"] 				= $objListCycleStartOn[$key];
							$objCycle["endOn"]					= $objListCycleEndOn[$key];
							$objCycle["statusID"]				= $objListCycleStatusID[$key];
							$objCycle["isActive"] 				= true;
							$this->core_web_auditoria->setAuditCreated($objCycle,$dataSession,$this->request);
							
							if(!$objCycle["statusID"])
							throw new \Exception("000243 TODOS LOS CICLOS DEBEN DE TENER UN ESTADO ESTABLECIDO");
							
							if(!$objCycle["startOn"])
							throw new \Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA INICIAL ESTABLECIDA");
							
							if(!$objCycle["endOn"])
							throw new \Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA FINAL ESTABLECIDA");
							
							$componentCycleID 					= $this->Component_Cycle_Model->insert_app_posme($objCycle);
						}
						
						if($db->transStatus() !== false){
							$db->transCommit();						
							$this->core_web_notification->set_message(false,SUCCESS);
							$this->response->redirect(base_url()."/".'app_accounting_period/edit/companyID/'.$obj["companyID"]."/componentID/".$obj["componentID"]."/componentPeriodID/".$componentPeriodID);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
							$this->response->redirect(base_url()."/".'app_accounting_period/add');	
						}
					}
					else{
						$this->response->redirect(base_url()."/".'app_accounting_period/add');	
					}
					
					 
			} 
			//Editar Registro
			else if( $this->validation->withRequest($this->request)->run() == true) {
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
					
								
					//PERMISO SOBRE EL REGISTRO
					$messageTmp					= '';
					$companyID					= $dataSession["user"]->companyID;
					$componentID				= $objComponentAccounting->componentID;
					$componentPeriodID 			= /*inicio get post*/ $this->request->getPost("txtComponentPeriodID");
					$objOld = $this->Component_Period_Model->get_rowByPK($componentPeriodID);
					if ($resultPermission 	== PERMISSION_ME && ($objOld->createdBy != $dataSession["user"]->userID))
					throw new \Exception(NOT_EDIT);
			
					//PERMISO PUEDE EDITAR EL REGISTRO
					if(!$this->core_web_workflow->validateWorkflowStage("tb_accounting_period","statusID",$objOld->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
					throw new \Exception(NOT_WORKFLOW_EDIT);
					
					if($continue){
						$db=db_connect();
						$db->transStart();					
						
						if(!$this->core_web_workflow->validateWorkflowStage("tb_accounting_period","statusID",$objOld->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
						{
							$companyID					= $dataSession["user"]->companyID;
							$componentID				= $objComponentAccounting->componentID;
							$componentPeriodID 			= /*inicio get post*/ $this->request->getPost("txtComponentPeriodID");
							$obj["number"]				= /*inicio get post*/ $this->request->getPost("txtNumber");
							$obj["name"]				= /*inicio get post*/ $this->request->getPost("txtName");
							$obj["description"]			= /*inicio get post*/ $this->request->getPost("txtDescription");
							$obj["startOn"]				= /*inicio get post*/ $this->request->getPost("txtStartOn");
							$obj["endOn"]				= /*inicio get post*/ $this->request->getPost("txtEndOn");
							$obj["statusID"]			= /*inicio get post*/ $this->request->getPost("txtStatusID");
							$obj["isActive"]			= 1;
							//Validar Periodo
							$result					= $this->Component_Period_Model->validateTime($companyID,$componentID,$obj["startOn"],$obj["endOn"]);
							
							if($result && $result[0]->componentPeriodID != $componentPeriodID ){
								throw new \Exception("000243 EL PERIODO NO PUEDE SER EDITADO, POR QUE ESTARIA SOLAPADO CON OTRO PERIODO CONTABLE...");
							}						
							
							//Actualizar Periodo
							$result 					= $this->Component_Period_Model->update_app_posme($companyID,$componentID,$componentPeriodID,$obj);
							
							
							//Ingresar los Ciclos
							$objListCycleID 		= /*inicio get post*/ $this->request->getPost("txtComponentCycleID"); 
							$objListCycleStartOn 	= /*inicio get post*/ $this->request->getPost("txtCycleStartOn");
							$objListCycleEndOn 		= /*inicio get post*/ $this->request->getPost("txtCycleEndOn");
							$objListCycleStatusID 	= /*inicio get post*/ $this->request->getPost("txtCycleStatusID");
							$objListCycleNumber 	= /*inicio get post*/ $this->request->getPost("txtCycleNumber");
							
							//Validar si puede eliminar  los ciclos que previamente fueron eliminados por el usuario
							$objListCycleToDelete 	= $this->Component_Cycle_Model->get_rowByCycleNotIn($companyID,$componentID,$componentPeriodID,$objListCycleID);
							if($objListCycleToDelete)
							foreach($objListCycleToDelete as $ik){
								$bo = $this->core_web_accounting->cycleIsEmptyByID($companyID,$ik->componentCycleID);
								if(!$bo)
								throw new \Exception("El CICLO CONTABLE CONTIENE COMPROBANTES VALIDOS ".$ik->startOn);
								
								$bo = $this->core_web_accounting->cycleIsCloseByID($companyID,$ik->componentCycleID);
								if($bo)
								throw new \Exception("El CICLO CONTABLE ESTA CERRADO ".$ik->startOn);
								
							}
							
												
							//Eliminar los Ciclos que fueron eliminados por el usuario
							$this->Component_Cycle_Model->deleteNotInArray($companyID,$componentID,$componentPeriodID,$objListCycleID);
							
							if($objListCycleStartOn)
							foreach($objListCycleStartOn as $key => $value){							
								$componentCycleID 					= $objListCycleID[$key];								
								$objCycle["startOn"] 				= $objListCycleStartOn[$key];
								$objCycle["endOn"]					= $objListCycleEndOn[$key];
								$objCycle["statusID"]				= $objListCycleStatusID[$key];							
								
								if(!$objCycle["statusID"])
								throw new \Exception("000243 TODOS LOS CICLOS DEBEN DE TENER UN ESTADO ESTABLECIDO");
								
								if(!$objCycle["startOn"])
								throw new \Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA INICIAL ESTABLECIDA");
								
								if(!$objCycle["endOn"])
								throw new \Exception("000243 TODOS LOS CICLOS DEBEN DE TENER FECHA FINAL ESTABLECIDA");
								
								if($componentCycleID){
									$objCycleTmp 		= $this->Component_Cycle_Model->get_rowByCycleID($componentCycleID);
									$objCycleTmpStartOn = date_format(date_create($objCycleTmp->startOn),"Y-m-d");
									$objCycleTmpEndOn 	= date_format(date_create($objCycleTmp->endOn),"Y-m-d");									
									//Validar si puede editar las fechas del ciclo
									if(($objCycleTmpStartOn != $objCycle["startOn"]) || ($objCycleTmpEndOn != $objCycle["endOn"])){
										$bo = $this->Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$objCycle["startOn"]);
										if($bo)
										throw new \Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO POR QUE SE SOLAPA CON OTRA ".$objCycleTmpStartOn);
										
										$bo = $this->Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$objCycle["endOn"]);
										if($bo)
										throw new \Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO POR QUE SE SOLAPA CON OTRA ".$objCycleTmpStartOn);
										
										$bo = $this->core_web_accounting->cycleIsCloseByDate($companyID,$objCycleTmpStartOn);
										if($bo)
										throw new \Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO EL CICLO ESTA CERRADO ".$objCycleTmpStartOn);
										
										$bo = $this->core_web_accounting->cycleIsCloseByDate($companyID,$objCycleTmpEndOn);
										if($bo)
										throw new \Exception("NO PUEDE CAMBIAR LAS FECHA DE ESTE CICLO EL CICLO ESTA CERRADO ".$objCycleTmpStartOn);
										
										
									}
									
									//Editar Ciclo
									$componentCycleID 					= $this->Component_Cycle_Model->update_app_posme($componentCycleID,$objCycle);
								}
								else{
									//Nuevo Ciclo
									$objCycle["componentPeriodID"]		= $componentPeriodID;
									$objCycle["companyID"]				= $companyID;
									$objCycle["componentID"] 			= $componentID;
									$objCycle["number"] 				= $obj["number"];
									$objCycle["name"]					= $obj["number"];
									$objCycle["description"] 			= $obj["description"];							
									$objCycle["isActive"] 				= true;
									$this->core_web_auditoria->setAuditCreated($objCycle,$dataSession,$this->request);							
									$componentCycleID 					= $this->Component_Cycle_Model->insert_app_posme($objCycle);
								}
							}
						}
						else{
							$obj["statusID"]			= /*inicio get post*/ $this->request->getPost("txtStatusID");		
							$companyID					= $dataSession["user"]->companyID;
							$componentID				= $objComponentAccounting->componentID;
							$componentPeriodID 			= /*inicio get post*/ $this->request->getPost("txtComponentPeriodID");							
							$result 					= $this->Component_Period_Model->update_app_posme($companyID,$componentID,$componentPeriodID,$obj);							
							$messageTmp					= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
						}
						
						if($db->transStatus() !== false){
							$db->transCommit();
							$this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
						}
						else{
							$db->transRollback();						
							$this->core_web_notification->set_message(true,$this->db->_error_message());
						}
						$this->response->redirect(base_url()."/".'app_accounting_period/edit/companyID/'.$companyID."/componentID/".$componentID."/componentPeriodID/".$componentPeriodID);
						
					}					
					else{
						$this->response->redirect(base_url()."/".'app_accounting_period/add');	
					}
			}  
			else{
				$stringValidation = str_replace("\n","",$this->validation->getErrors());								
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_accounting_period/add');	
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
						
						$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
						if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);			
			
			}	
			
			//Obtener la lista de Workflow
			$companyID 	= $dataSession["user"]->companyID;
			$branchID 	= $dataSession["user"]->branchID;
			$roleID 	= $dataSession["role"]->roleID;
			$data["objListComponentPeriodStatus"]  	= $this->core_web_workflow->getWorkflowInitStage("tb_accounting_period","statusID",$companyID,$branchID,$roleID);
			$data["objListComponentCycleStatus"]  	= $this->core_web_workflow->getWorkflowInitStage("tb_accounting_cycle","statusID",$companyID,$branchID,$roleID);
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_period/news_head',$data);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_account_period/news_body',$data);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_account_period/news_script',$data);//--finview
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("0-CONTABILIDAD");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE '0-CONTABILIDAD' NO EXISTE...");
			
			
			//Vista por defecto 
			if($dataViewID == null){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$parameter["{componentID}"]	= $objComponent->componentID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			//Otra vista
			else{									
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$parameter["{componentID}"]	= $objComponent->componentID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_account_period/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_account_period/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_account_period/list_script');//--finview
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