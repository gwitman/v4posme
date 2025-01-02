<?php
//posme:2023-02-27
namespace App\Controllers;
class app_planilla_employee_pay extends _BaseController {
	
    
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
			
			//Cargar Librerias
			
			
			
			
			
			//Obtener el componente de Item
			$objComponentCalendarPay	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new \Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
		
			$objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			
			//Redireccionar datos
			
			$calendarID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"calendarID");//--finuri
			$companyID 				= $dataSession["user"]->companyID;
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
			if((!$calendarID))
			{ 
				$this->response->redirect(base_url()."/".'app_planilla_employee_pay/add');	
			} 		
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			
			
			$dataView["objComponentCalendarPay"]	= $objComponentCalendarPay;
			$dataView["objComponentEmployee"]		= $objComponentEmployee;
			$dataView["objCalendarPay"]				= $this->Employee_Calendar_Pay_Model->get_rowByPK($calendarID);
			$dataView["objCalendarPayDetail"]		= $this->Employee_Calendar_Pay_detail_Model->get_rowByCalendarID($calendarID);
			$dataView["objListCycle"]				= $this->Component_Cycle_Model->get_rowByCycleID($dataView["objCalendarPay"]->accountingCycleID);
			$dataView["objListType"]				= $this->core_web_catalog->getCatalogAllItem("tb_employee_calendar_pay","typeID",$dataView["companyID"]);
			$dataView["objListCurrency"]			= $this->Company_Currency_Model->getByCompany($dataView["companyID"]);
			$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_employee_calendar_pay","statusID",$dataView["objCalendarPay"]->statusID,$companyID,$branchID,$roleID);
			$dataView["objListCatalogWeeks"]		= $this->core_web_catalog->getCatalogAllItem("tb_employee_calendar_pay","weekID",$dataView["companyID"]);

			//Obtener los ID de los empleados registrados en la planilla
			$objListEmployeeIDs = array_map(function($row)
				{
					return $row->employeeID;
				},$dataView["objCalendarPayDetail"]
			);

			//Verificar que hayan datos para no lanzar excepcion en la consulta
			if($objListEmployeeIDs)
			{
				$dataView["objListEmployee"] 		= $this->Employee_Model->get_rowByEmployeeIDList($companyID, $branchID, $objListEmployeeIDs);
			}
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_planilla_employee_pay/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_planilla_employee_pay/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_planilla_employee_pay/edit_script',$dataView);//--finview
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
			$companyID 				= /*inicio get post*/ $this->request->getPost("companyID");
			$transactionID 			= /*inicio get post*/ $this->request->getPost("transactionID");				
			$transactionMasterID 	= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
			
			
			if((!$companyID && !$transactionID && !$transactionMasterID)){
					throw new \Exception(NOT_PARAMETER);								 
			} 
			
			$objTM	 				= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ELIMINARSE, EL CICLO CONTABLE ESTA CERRADO");
				
				
			//Si el documento esta aplicado crear el contra documento
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_share","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
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
			
			
						
			
			
			
			
			//Obtener el Componente
			$objComponentCalendarPay			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new \Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
			
			//Obtener el Ciclo
			$objCycle 							= $this->Component_Cycle_Model->get_rowByCycleID(/*inicio get post*/ $this->request->getPost("txtCycleID"));
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$objCycle->startOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			
			//Obtener transaccion
			$calendarID								= /*inicio get post*/ $this->request->getPost("txtCalendarPayID");
			$objCalendarPay							= $this->Employee_Calendar_Pay_Model->get_rowByPK($calendarID);
			$objEC["companyID"] 					= $dataSession["user"]->companyID;
			$objEC["accountingCycleID"] 			= /*inicio get post*/ $this->request->getPost("txtCycleID");	
			$objEC["name"] 							= /*inicio get post*/ $this->request->getPost("txtNombre");
			$objEC["typeID"] 						= /*inicio get post*/ $this->request->getPost("txtTypeID");
			$objEC["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
			$objEC["statusID"]						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objEC["description"] 					= /*inicio get post*/ $this->request->getPost("txtNote");
			
			//Obtener el Ciclo
			$objCycle 								= $this->Component_Cycle_Model->get_rowByCycleID(/*inicio get post*/ $this->request->getPost("txtCycleID"));			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objCalendarPay->createdBy != $userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_employee_calendar_pay","statusID",$objCalendarPay->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objCycle->startOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_employee_calendar_pay","statusID",$objCalendarPay->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objEC								= array();
				$objEC["description"] 					= /*inicio get post*/ $this->request->getPost("txtNote");
				$objEC["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Employee_Calendar_Pay_Model->update_app_posme($calendarID,$objEC);
			}
			else{
				$this->Employee_Calendar_Pay_Model->update_app_posme($calendarID,$objEC);
			}
			
			
			//Recorrer la lista del detalle del documento
			$arrayListCalendarDetailID	= /*inicio get post*/ $this->request->getPost("txtCalendarDetailID");
			$arrayListEmployeeID		= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
			$arrayListSalario			= /*inicio get post*/ $this->request->getPost("txtSalario");
			$arrayListComision			= /*inicio get post*/ $this->request->getPost("txtComision");
			$arrayListAdelantos	 		= /*inicio get post*/ $this->request->getPost("txtAdelantos");			
			$arrayListNeto				= /*inicio get post*/ $this->request->getPost("txtNeto");

			$arrayListBono					= /*inicio get post*/ $this->request->getPost("txtBonoQuincenal");
			$arrayListDeduccionPrestamo		= /*inicio get post*/ $this->request->getPost("txtDeduccionesPrestamo");
			$arrayListDeduccionLlegadaTarde	= /*inicio get post*/ $this->request->getPost("txtDeduccionesLlegadaTarde");
			$arrayListINSS					= /*inicio get post*/ $this->request->getPost("txtINSS");
			$arrayListINSSPatronal			= /*inicio get post*/ $this->request->getPost("txtINSSPatronal");
			$arrayListIR					= /*inicio get post*/ $this->request->getPost("txtIR");
			$arrayListAhorro				= /*inicio get post*/ $this->request->getPost("txtAhorro");
			
			//Eliminar Para Crear Nuevamente.
			if(empty($arrayListCalendarDetailID))
			{
				//Por si se eliminan todos los registros de la planilla
				$this->Employee_Calendar_Pay_detail_Model->deleteWhereCalendarID($calendarID);
			}
			else
			{
				$this->Employee_Calendar_Pay_detail_Model->deleteWhereIDNotIn($calendarID,$arrayListCalendarDetailID);
			}
			
			if(!empty($arrayListCalendarDetailID)){
				foreach($arrayListCalendarDetailID as $key => $value){
					$calendarPayDetailID		= $value;
					$EmployeeID					= $arrayListEmployeeID[$key];
					$Salario					= $arrayListSalario[$key];
					$Comision					= $arrayListComision[$key];
					$Adelantos					= $arrayListAdelantos[$key];
					$Neto 						= $arrayListNeto[$key];
					
					$Bono						= $arrayListBono[$key];
					$DeduccionPrestamo			= $arrayListDeduccionPrestamo[$key];
					$DeduccionLlegadaTarde		= $arrayListDeduccionLlegadaTarde[$key];
					$INSS						= $arrayListINSS[$key];
					$INSSPatronal 				= $arrayListINSSPatronal[$key];
					$IR							= $arrayListIR[$key];
					$Ahorro						= $arrayListAhorro[$key];
					
					//Nuevo Detalle
					if($calendarPayDetailID == 0){	
						$objECD 									= NULL;
						$objECD["calendarID"] 						= $calendarID;
						$objECD["employeeID"] 						= $EmployeeID;
						$objECD["plus_salary"] 						= $Salario;
						$objECD["plus_commission"]					= $Comision;
						$objECD["plus_bonus"]						= $Bono;
						$objECD["minus_adelantos"]					= $Adelantos;
						$objECD["minus_deduction_for_loans"]		= $DeduccionPrestamo;
						$objECD["minus_deduction_for_late_arrival"]	= $DeduccionLlegadaTarde;
						$objECD["minus_inss"]						= $INSS;
						$objECD["inss_patronal"]					= $INSSPatronal;
						$objECD["minus_ir"]							= $IR;
						$objECD["saving"]							= $Ahorro;
						$objECD["equal_neto"] 						= $Neto;
						$objECD["isActive"]							= 1;
						
						$this->Employee_Calendar_Pay_detail_Model->insert_app_posme($objECD);
					}					
					//Editar Detalle
					else{						
						$objECD 									= NULL;
						$objECD["calendarID"] 						= $calendarID;
						$objECD["employeeID"] 						= $EmployeeID;
						$objECD["plus_salary"] 						= $Salario;
						$objECD["plus_commission"]					= $Comision;
						$objECD["plus_bonus"]						= $Bono;
						$objECD["minus_adelantos"]					= $Adelantos;
						$objECD["minus_deduction_for_loans"]		= $DeduccionPrestamo;
						$objECD["minus_deduction_for_late_arrival"]	= $DeduccionLlegadaTarde;
						$objECD["minus_inss"]						= $INSS;
						$objECD["inss_patronal"]					= $INSSPatronal;
						$objECD["minus_ir"]							= $IR;
						$objECD["saving"]							= $Ahorro;
						$objECD["equal_neto"] 						= $Neto;
						$objECD["isActive"]							= 1;
												
						$this->Employee_Calendar_Pay_detail_Model->update_app_posme($calendarPayDetailID,$objECD);					
					}
					
					
				}
			}			
			
			//Aplicar el Documento?
			if( $this->core_web_workflow->validateWorkflowStage("tb_employee_calendar_pay","statusID",$objEC["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID) &&  $objCalendarPay->statusID != $objEC["statusID"] ){
				
				//Crear Transaccion Nueva
				$query			= "CALL pr_planilla_create_transaction (?,?);";	
				$resultQuery	= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$calendarID]
				);
				
				
			}
			
			
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_planilla_employee_pay/edit/calendarID/'.$calendarID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_planilla_employee_pay/add');	
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
			
		    echo $resultView;
		}
		
	}
	function insertElement($dataSession){
		try{
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
			//Obtener el Componente
			$objComponentCalendarPay			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new \Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
			
			//Obtener el Ciclo
			$objCycle 							= $this->Component_Cycle_Model->get_rowByCycleID(/*inicio get post*/ $this->request->getPost("txtCycleID"));
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,$objCycle->startOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Obtener transaccion
			$objEC["companyID"] 					= $dataSession["user"]->companyID;
			$objEC["accountingCycleID"] 			= /*inicio get post*/ $this->request->getPost("txtCycleID");			
			$objEC["number"]						= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_employee_calendar_pay",0);
			$objEC["name"] 							= /*inicio get post*/ $this->request->getPost("txtNombre");
			$objEC["typeID"] 						= /*inicio get post*/ $this->request->getPost("txtTypeID");
			$objEC["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");
			$objEC["weekID"]						= /*inicio get post*/ $this->request->getPost("txtWeekID");	
			$objEC["statusID"]						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objEC["description"] 					= /*inicio get post*/ $this->request->getPost("txtNote");
			$objEC["isActive"] 						= 1;
			$this->core_web_auditoria->setAuditCreated($objEC,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transStart();
			$calendarID = $this->Employee_Calendar_Pay_Model->insert_app_posme($objEC);
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			mkdir(PATH_FILE_OF_APP."/company_".$objEC["companyID"]."/component_".$objComponentCalendarPay->componentID."/component_item_".$calendarID, 0700);
			
			//Recorrer la lista del detalle del documento
			$arrayListCalendarDetailID	= /*inicio get post*/ $this->request->getPost("txtCalendarDetailID");
			$arrayListEmployeeID		= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
			$arrayListSalario			= /*inicio get post*/ $this->request->getPost("txtSalario");
			$arrayListComision			= /*inicio get post*/ $this->request->getPost("txtComision");
			$arrayListAdelantos	 		= /*inicio get post*/ $this->request->getPost("txtAdelantos");			
			$arrayListNeto				= /*inicio get post*/ $this->request->getPost("txtNeto");
			
			$arrayListBono					= /*inicio get post*/ $this->request->getPost("txtBonoQuincenal");
			$arrayListDeduccionPrestamo		= /*inicio get post*/ $this->request->getPost("txtDeduccionesPrestamo");
			$arrayListDeduccionLlegadaTarde	= /*inicio get post*/ $this->request->getPost("txtDeduccionesLlegadaTarde");
			$arrayListINSS					= /*inicio get post*/ $this->request->getPost("txtINSS");
			$arrayListINSSPatronal			= /*inicio get post*/ $this->request->getPost("txtINSSPatronal");
			$arrayListIR					= /*inicio get post*/ $this->request->getPost("txtIR");
			$arrayListAhorro				= /*inicio get post*/ $this->request->getPost("txtAhorro");

			if(!empty($arrayListCalendarDetailID)){
				foreach($arrayListCalendarDetailID as $key => $value){
					$calendarPayDetailID		= $value;
					$EmployeeID					= $arrayListEmployeeID[$key];
					$Salario					= $arrayListSalario[$key];
					$Comision					= $arrayListComision[$key];
					$Adelantos					= $arrayListAdelantos[$key];
					$Neto 						= $arrayListNeto[$key];
					
					$Bono						= $arrayListBono[$key];
					$DeduccionPrestamo			= $arrayListDeduccionPrestamo[$key];
					$DeduccionLlegadaTarde		= $arrayListDeduccionLlegadaTarde[$key];
					$INSS						= $arrayListINSS[$key];
					$INSSPatronal 				= $arrayListINSSPatronal[$key];
					$IR							= $arrayListIR[$key];
					$Ahorro						= $arrayListAhorro[$key];
					
					$objECD 									= NULL;
					$objECD["calendarID"] 						= $calendarID;
					$objECD["employeeID"] 						= $EmployeeID;
					$objECD["plus_salary"] 						= $Salario;
					$objECD["plus_commission"]					= $Comision;
					$objECD["plus_bonus"]						= $Bono;
					$objECD["minus_adelantos"]					= $Adelantos;
					$objECD["minus_deduction_for_loans"]		= $DeduccionPrestamo;
					$objECD["minus_deduction_for_late_arrival"]	= $DeduccionLlegadaTarde;
					$objECD["minus_inss"]						= $INSS;
					$objECD["inss_patronal"]					= $INSSPatronal;
					$objECD["minus_ir"]							= $IR;
					$objECD["saving"]							= $Ahorro;
					$objECD["equal_neto"] 						= $Neto;
					$objECD["isActive"]							= 1;
					
					$this->Employee_Calendar_Pay_detail_Model->insert_app_posme($objECD);
				}
			}
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_planilla_employee_pay/edit/calendarID/'.$calendarID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_planilla_employee_pay/add');	
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

			echo $resultView;
		}	
	}
	function save($mode=""){
		 $mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
		 try{ 
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtCycleID","Ciclo","required");
			
			 //Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_planilla_employee_pay/add');
				exit;
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
				$this->response->redirect(base_url()."/".'app_planilla_employee_pay/add');
				exit;
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
			
			//Cargar Librerias
			
			
			
			//Obtener el componente de Item
			$objComponentCalendarPay	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponentCalendarPay)
			throw new \Exception("EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
		
			$objComponentEmployee	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");
			
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			
			
			$dataView["objComponentCalendarPay"]= $objComponentCalendarPay;
			$dataView["objComponentEmployee"]	= $objComponentEmployee;
			//$dataView["objListCycle"]			= $this->Component_Cycle_Model->get_rowByCompanyIDFecha($dataView["companyID"],date("Y-m-d"));
			//Obtener el Parametro Estado Cerrado de los Ciclos Contables.
			$objCompanyParameter 				= $this->core_web_parameter->getParameter("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",$dataSession["user"]->companyID);
			$dataView["objListCycle"]			= $this->Component_Cycle_Model->get_rowByCompanyID_TopCycleOpenAscAndOpen($dataView["companyID"],100,$objCompanyParameter->value);
			$dataView["objListType"]			= $this->core_web_catalog->getCatalogAllItem("tb_employee_calendar_pay","typeID",$dataView["companyID"]);
			$dataView["objListCurrency"]		= $this->Company_Currency_Model->getByCompany($dataView["companyID"]);
			$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_employee_calendar_pay","statusID",$dataView["companyID"],$dataView["branchID"],$dataView["roleID"]);
			$dataView["objListCatalogWeeks"]	= $this->core_web_catalog->getCatalogAllItem("tb_employee_calendar_pay","weekID",$dataView["companyID"]);
		
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_planilla_employee_pay/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_planilla_employee_pay/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_planilla_employee_pay/news_script',$dataView);//--finview
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
		
			//Librerias
			
			
			
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee_calendar_pay");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_employee_calendar_pay' NO EXISTE...");
			
			
			//Vista por defecto PC
			if($dataViewID == null and  !$this->request->getUserAgent()->isMobile() ){				
				$targetComponentID			= 0;	
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}		
			//Vista por defecto MOBILE
			else if( $this->request->getUserAgent()->isMobile() ){
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewByName($this->session->get('user'),$objComponent->componentID,"DEFAULT_MOBILE_LISTA_ABONOS",CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			} 
			//Vista Por Id
			else 
			{
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter); 			
				$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
			}
			 
			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_planilla_employee_pay/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_planilla_employee_pay/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_planilla_employee_pay/list_script');//--finview
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