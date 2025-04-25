<?php
//posme:2023-02-27
namespace App\Controllers;
class app_cxp_provider extends _BaseController {
	
    
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
			
				
			
			
			
				
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			
			$companyID_ 							= /*inicio get post*/ $this->request->getPost("txtCompanyID");
			$branchID_								= /*inicio get post*/ $this->request->getPost("txtBranchID");
			$entityID_								= /*inicio get post*/ $this->request->getPost("txtEntityID");
			
			$objProvider							= $this->Provider_Model->get_rowByPK($companyID_,$branchID_,$entityID_);
			$oldStatusID 							= $objProvider->statusID;

			//Moneda Dolares
			date_default_timezone_set(APP_TIMEZONE); 
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objProvider->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$objProvider->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			
			
			$db=db_connect();
			$db->transStart();			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$oldStatusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){				
				$objProvider["statusID"] 		= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$this->Provider_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objProvider);
			}
			else{
				$objNatural["isActive"]		= true;
				$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
				$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
				$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Natural_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objNatural);
				$objLegal["isActive"]		= true;
				$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
				$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
				$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$this->Legal_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objLegal);
				
				$objProvider 							= NULL;
				$objProvider["numberIdentification"]	= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
				$objProvider["identificationTypeID"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
				$objProvider["providerType"]			= /*inicio get post*/ $this->request->getPost('txtProviderTypeID');//--fin peticion get o post
				$objProvider["providerCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
				$objProvider["providerClasificationID"]	= /*inicio get post*/ $this->request->getPost("txtClasificationID");//--fin peticion get o post
				$objProvider["reference1"]				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
				$objProvider["reference2"]				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
				$objProvider["payConditionID"]			= /*inicio get post*/ $this->request->getPost("txtTypePayID");//--fin peticion get o post
				$objProvider["isLocal"]					= /*inicio get post*/ $this->request->getPost('txtIsLocal');//--fin peticion get o post
				$objProvider["countryID"]				= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
				$objProvider["stateID"]					= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
				$objProvider["cityID"]					= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
				$objProvider["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
				$objProvider["currencyID"]				= /*inicio get post*/ $this->request->getPost('txtCurrencyID');//--fin peticion get o post
				$objProvider["statusID"]				= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
				$objProvider["deleveryDay"]				= /*inicio get post*/ $this->request->getPost('txtDayDelevery');//--fin peticion get o post
				$objProvider["deleveryDayReal"]			= /*inicio get post*/ $this->request->getPost('txtDayDeleveryReal');//--fin peticion get o post
				$objProvider["distancia"]				= /*inicio get post*/ $this->request->getPost("txtDistancia");//--fin peticion get o post
				$objProvider["isActive"]				= true;
				$this->Provider_Model->update_app_posme($companyID_,$branchID_,$entityID_,$objProvider);
			
			}
			
			
			//Email
			$this->Entity_Email_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityEmail 				= /*inicio get post*/ $this->request->getPost("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= /*inicio get post*/ $this->request->getPost("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $companyID_;
				$objEntityEmail["branchID"]		= $branchID_;
				$objEntityEmail["entityID"]		= $entityID_;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key] == 1 ? true : false; 
				$this->Entity_Email_Model->insert_app_posme($objEntityEmail);
			}
			
			//Phone
			$this->Entity_Phone_Model->deleteByEntity($companyID_,$branchID_,$entityID_);
			$arrayListEntityPhoneTypeID			= /*inicio get post*/ $this->request->getPost("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $companyID_;
				$objEntityPhone["branchID"]		= $branchID_;
				$objEntityPhone["entityID"]		= $entityID_;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert_app_posme($objEntityPhone);
			}	
			
			//Lineas de Creditos
			$arrayListProviderCreditLineID	= /*inicio get post*/ $this->request->getPost("txtProviderCreditLineID");
			$arrayListCreditLineID			= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$arrayListCreditCurrencyID		= /*inicio get post*/ $this->request->getPost("txtCreditCurrencyID");
			$arrayListCreditStatusID		= /*inicio get post*/ $this->request->getPost("txtCreditStatusID");
			$arrayListCreditInterestYear	= /*inicio get post*/ $this->request->getPost("txtCreditInterestYear");
			$arrayListCreditInterestPay		= /*inicio get post*/ $this->request->getPost("txtCreditInterestPay");
			$arrayListCreditTotalPay		= /*inicio get post*/ $this->request->getPost("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= /*inicio get post*/ $this->request->getPost("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= /*inicio get post*/ $this->request->getPost("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= /*inicio get post*/ $this->request->getPost("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= /*inicio get post*/ $this->request->getPost("txtCreditDateLastPay");
			$arrayListCreditTerm			= /*inicio get post*/ $this->request->getPost("txtCreditTerm");
			$arrayListCreditNote			= /*inicio get post*/ $this->request->getPost("txtCreditNote");
			$arrayListCreditLine			= /*inicio get post*/ $this->request->getPost("txtLine");
			$arrayListCreditNumber			= /*inicio get post*/ $this->request->getPost("txtLineNumber");
			$arrayListCreditLimit			= /*inicio get post*/ $this->request->getPost("txtLineLimit");
			$arrayListCreditBalance			= /*inicio get post*/ $this->request->getPost("txtLineBalance");
			$arrayListCreditStatus			= /*inicio get post*/ $this->request->getPost("txtLineStatus");
			$arrayListTypeAmortization		= /*inicio get post*/ $this->request->getPost("txtTypeAmortization");
			$arrayListDayExcluded			= /*inicio get post*/ $this->request->getPost("txtDayExcluded");
			
			$limitCreditLine 				= 0;
			if($arrayListProviderCreditLineID)
			{
				//Limpiar Lineas de Creditos
				$this->Customer_Credit_Line_Model->deleteWhereIDNotIn($companyID_,$branchID_,$entityID_,$arrayListProviderCreditLineID);
				
			}
			
			if(!empty($arrayListProviderCreditLineID))
			foreach($arrayListProviderCreditLineID as $key => $value){
			
				$providerCreditLineID 						= $value;
				if($providerCreditLineID == 0 ){
					$objProviderCreditLine					= NULL;
					$objProviderCreditLine["companyID"]		= $companyID_;
					$objProviderCreditLine["branchID"]		= $branchID_;
					$objProviderCreditLine["entityID"]		= $entityID_;
					$objProviderCreditLine["creditLineID"]	= $arrayListCreditLineID[$key];
					$objProviderCreditLine["accountNumber"]	= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objProviderCreditLine["currencyID"]	= helper_StringToNumber($arrayListCreditCurrencyID[$key]);
					$objProviderCreditLine["limitCredit"]	= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objProviderCreditLine["balance"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objProviderCreditLine["interestYear"]	= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objProviderCreditLine["interestPay"]	= $arrayListCreditInterestPay[$key];
					$objProviderCreditLine["totalPay"]		= $arrayListCreditTotalPay[$key];
					$objProviderCreditLine["totalDefeated"]	= $arrayListCreditTotalDefeated[$key];
					$objProviderCreditLine["dateOpen"]		= date("Y-m-d");
					$objProviderCreditLine["periodPay"]		= $arrayListCreditPeriodPay[$key];
					$objProviderCreditLine["dateLastPay"]	= date("Y-m-d");
					$objProviderCreditLine["term"]			= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objProviderCreditLine["note"]			= $arrayListCreditNote[$key];
					$objProviderCreditLine["statusID"]		= $arrayListCreditStatusID[$key];
					$objProviderCreditLine["isActive"]		= 1;
					$objProviderCreditLine["typeAmortization"]		= $arrayListTypeAmortization[$key];
					$objProviderCreditLine["dayExcluded"]			= $arrayListDayExcluded[$key];					
					$limitCreditLine 								= $limitCreditLine + $objProviderCreditLine["limitCredit"];
					$this->Customer_Credit_Line_Model->insert_app_posme($objProviderCreditLine);
					
					if($objProviderCreditLine["balance"] > $objProviderCreditLine["limitCredit"])
					throw new \Exception("BALANCE NO PUEDE SER MAYOR QUE EL LIMITE EN LA LINEA");
				}
				else{					
					$objProviderCreditLine 							= $this->Customer_Credit_Line_Model->get_rowByPK($providerCreditLineID);
					$objProviderCreditLineNew						= NULL;
					$objProviderCreditLineNew["creditLineID"]		= $arrayListCreditLineID[$key];
					$objProviderCreditLineNew["currencyID"]			= helper_StringToNumber($arrayListCreditCurrencyID[$key]);
					$objProviderCreditLineNew["limitCredit"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objProviderCreditLineNew["interestYear"]		= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objProviderCreditLineNew["balance"] 			= $objProviderCreditLineNew["limitCredit"] - ($objProviderCreditLine->limitCredit - $objProviderCreditLine->balance);
					$objProviderCreditLineNew["periodPay"]			= $arrayListCreditPeriodPay[$key];
					$objProviderCreditLineNew["term"]				= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objProviderCreditLineNew["note"]				= $arrayListCreditNote[$key];
					$objProviderCreditLineNew["statusID"]			= $arrayListCreditStatusID[$key];
					$objProviderCreditLineNew["typeAmortization"]	= $arrayListTypeAmortization[$key];
					$objProviderCreditLineNew["dayExcluded"]		= $arrayListDayExcluded[$key];
					$limitCreditLine 								= $limitCreditLine + $objProviderCreditLineNew["limitCredit"];
					
					//Si el balance es mayor que el limite igual el balance al limite
					if($objProviderCreditLineNew["balance"] > $objProviderCreditLineNew["limitCredit"])
					$objProviderCreditLineNew["balance"] = $objProviderCreditLineNew["limitCredit"];
					
					//actualizar
					$this->Customer_Credit_Line_Model->update_app_posme($providerCreditLineID,$objProviderCreditLineNew);
				}
			}
			
			//Confirmar Entidad
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxp_provider/edit/companyID/'.$companyID_."/branchID/".$branchID_."/entityID/".$entityID_);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');	
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
			
				
			
			
			
				
			
			
			
			//Obtener el Componente de Transacciones Other Input to Inventory
			$objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			//Obtener transaccion
			$companyID 								= $dataSession["user"]->companyID;			
			$objEntity["companyID"] 				= $dataSession["user"]->companyID;			
			$objEntity["branchID"]					= $dataSession["user"]->branchID;	
			$branchID 								= $dataSession["user"]->branchID;		
			$roleID 								= $dataSession["role"]->roleID;
			$this->core_web_auditoria->setAuditCreated($objEntity,$dataSession,$this->request);

			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");

			$db=db_connect();
			$db->transStart();
			$entityID = $this->Entity_Model->insert_app_posme($objEntity);
			
			$objNatural["companyID"]	= $objEntity["companyID"];
			$objNatural["branchID"] 	= $objEntity["branchID"];
			$objNatural["entityID"]		= $entityID;
			$objNatural["isActive"]		= true;
			$objNatural["firstName"]	= /*inicio get post*/ $this->request->getPost("txtFirstName");//--fin peticion get o post
			$objNatural["lastName"]		= /*inicio get post*/ $this->request->getPost("txtLastName");//--fin peticion get o post
			$objNatural["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$result 					= $this->Natural_Model->insert_app_posme($objNatural);
			
			$objLegal["companyID"]		= $objEntity["companyID"];
			$objLegal["branchID"]		= $objEntity["branchID"];
			$objLegal["entityID"]		= $entityID;
			$objLegal["isActive"]		= true;
			$objLegal["comercialName"]	= /*inicio get post*/ $this->request->getPost("txtCommercialName");//--fin peticion get o post
			$objLegal["legalName"]		= /*inicio get post*/ $this->request->getPost("txtLegalName");//--fin peticion get o post
			$objLegal["address"]		= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$result 					= $this->Legal_Model->insert_app_posme($objLegal);
			
			$objProvider["companyID"]				= $objEntity["companyID"];
			$objProvider["branchID"]				= $objEntity["branchID"];
			$objProvider["entityID"]				= $entityID;
			$objProvider["providerNumber"]			= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_provider",0);
			$objProvider["numberIdentification"]	= /*inicio get post*/ $this->request->getPost('txtIdentification');//--fin peticion get o post
			$objProvider["identificationTypeID"]	= /*inicio get post*/ $this->request->getPost('txtIdentificationTypeID');//--fin peticion get o post
			$objProvider["providerType"]			= /*inicio get post*/ $this->request->getPost('txtProviderTypeID');//--fin peticion get o post
			$objProvider["providerCategoryID"]		= /*inicio get post*/ $this->request->getPost('txtCategoryID');//--fin peticion get o post
			$objProvider["providerClasificationID"]	= /*inicio get post*/ $this->request->getPost("txtClasificationID");//--fin peticion get o post
			$objProvider["reference1"]				= /*inicio get post*/ $this->request->getPost("txtReference1");//--fin peticion get o post
			$objProvider["reference2"]				= /*inicio get post*/ $this->request->getPost("txtReference2");//--fin peticion get o post
			$objProvider["payConditionID"]			= /*inicio get post*/ $this->request->getPost("txtTypePayID");//--fin peticion get o post
			$objProvider["isLocal"]					= /*inicio get post*/ $this->request->getPost('txtIsLocal');//--fin peticion get o post
			$objProvider["countryID"]				= /*inicio get post*/ $this->request->getPost('txtCountryID');//--fin peticion get o post
			$objProvider["stateID"]					= /*inicio get post*/ $this->request->getPost('txtStateID');//--fin peticion get o post
			$objProvider["cityID"]					= /*inicio get post*/ $this->request->getPost("txtCityID");//--fin peticion get o post
			$objProvider["address"]					= /*inicio get post*/ $this->request->getPost("txtAddress");//--fin peticion get o post
			$objProvider["currencyID"]				= /*inicio get post*/ $this->request->getPost('txtCurrencyID');//--fin peticion get o post
			$objProvider["statusID"]				= /*inicio get post*/ $this->request->getPost('txtStatusID');//--fin peticion get o post
			$objProvider["deleveryDay"]				= /*inicio get post*/ $this->request->getPost('txtDayDelevery');//--fin peticion get o post
			$objProvider["deleveryDayReal"]			= /*inicio get post*/ $this->request->getPost('txtDayDeleveryReal');//--fin peticion get o post
			$objProvider["distancia"]				= /*inicio get post*/ $this->request->getPost("txtDistancia");//--fin peticion get o post
			$objProvider["isActive"]				= true;
			$this->core_web_auditoria->setAuditCreated($objProvider,$dataSession,$this->request);
			$result 							= $this->Provider_Model->insert_app_posme($objProvider);
			
			//Email
			$arrayListEntityEmail 				= /*inicio get post*/ $this->request->getPost("txtEntityEmail");
			$arrayListEntityEmailIsPrimary		= /*inicio get post*/ $this->request->getPost("txtEmailIsPrimary");			
			if(!empty($arrayListEntityEmail))
			foreach($arrayListEntityEmail as $key => $value){
				$objEntityEmail["companyID"]	= $objEntity["companyID"];
				$objEntityEmail["branchID"]		= $objEntity["branchID"];
				$objEntityEmail["entityID"]		= $entityID;
				$objEntityEmail["email"]		= $value;
				$objEntityEmail["isPrimary"]	= $arrayListEntityEmailIsPrimary[$key];
				$this->Entity_Email_Model->insert_app_posme($objEntityEmail);
			}
			
			//Phone
			$arrayListEntityPhoneTypeID			= /*inicio get post*/ $this->request->getPost("txtEntityPhoneTypeID");
			$arrayListEntityPhoneNumber 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneNumber");
			$arrayListEntityPhoneIsPrimary 		= /*inicio get post*/ $this->request->getPost("txtEntityPhoneIsPrimary");			
			if(!empty($arrayListEntityPhoneTypeID))
			foreach($arrayListEntityPhoneTypeID as $key => $value){
				$objEntityPhone["companyID"]	= $objEntity["companyID"];
				$objEntityPhone["branchID"]		= $objEntity["branchID"];
				$objEntityPhone["entityID"]		= $entityID;
				$objEntityPhone["typeID"]		= $value;
				$objEntityPhone["number"]		= $arrayListEntityPhoneNumber[$key];
				$objEntityPhone["isPrimary"]	= $arrayListEntityPhoneIsPrimary[$key];
				$this->Entity_Phone_Model->insert_app_posme($objEntityPhone);
			}

			//Ingresar Customer Credit
			$objProviderCredit["companyID"] 		= $objEntity["companyID"];
			$objProviderCredit["branchID"] 			= $objEntity["branchID"];
			$objProviderCredit["entityID"] 			= $entityID;
			$this->Customer_Credit_Model->insert_app_posme($objProviderCredit);
			
			$objListComanyParameter			= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
            $creditLineDefault 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "CXC_CREDIT_LINE_DEFAULT")->value;
			$interesDefault					= $this->core_web_parameter->getParameterValue("CXC_INTERES_DEFAULT",$companyID);
			$frecuencyDefault 				= $this->core_web_parameter->getParameterValue("CXC_FRECUENCIA_PAY_DEFAULT",$companyID);
			$plazoDefault 					= $this->core_web_parameter->getParameterValue("CXC_PLAZO_DEFAULT",$companyID);
			$typeAmortizationDefault 		= $this->core_web_parameter->getParameterValue("CXC_TYPE_AMORTIZATION",$companyID);
			$dayExcludedDefault 			= $this->core_web_parameter->getParameterValue("CXC_DAY_EXCLUDED_IN_CREDIT",$companyID);			

			//Lineas de Creditos
			$arrayListProviderCreditLineID	= /*inicio get post*/ $this->request->getPost("txtProviderCreditLineID");
			$arrayListCreditLineID			= /*inicio get post*/ $this->request->getPost("txtCreditLineID");
			$arrayListCreditCurrencyID		= /*inicio get post*/ $this->request->getPost("txtCreditCurrencyID");
			$arrayListCreditStatusID		= /*inicio get post*/ $this->request->getPost("txtCreditStatusID");
			$arrayListCreditInterestYear	= /*inicio get post*/ $this->request->getPost("txtCreditInterestYear");
			$arrayListCreditInterestPay		= /*inicio get post*/ $this->request->getPost("txtCreditInterestPay");
			$arrayListCreditTotalPay		= /*inicio get post*/ $this->request->getPost("txtCreditTotalPay");
			$arrayListCreditTotalDefeated	= /*inicio get post*/ $this->request->getPost("txtCreditTotalDefeated");
			$arrayListCreditDateOpen		= /*inicio get post*/ $this->request->getPost("txtCreditDateOpen");
			$arrayListCreditPeriodPay		= /*inicio get post*/ $this->request->getPost("txtCreditPeriodPay");
			$arrayListCreditDateLastPay		= /*inicio get post*/ $this->request->getPost("txtCreditDateLastPay");
			$arrayListCreditTerm			= /*inicio get post*/ $this->request->getPost("txtCreditTerm");
			$arrayListCreditNote			= /*inicio get post*/ $this->request->getPost("txtCreditNote");
			$arrayListCreditLine			= /*inicio get post*/ $this->request->getPost("txtLine");
			$arrayListCreditNumber			= /*inicio get post*/ $this->request->getPost("txtLineNumber");
			$arrayListCreditLimit			= /*inicio get post*/ $this->request->getPost("txtLineLimit");
			$arrayListCreditBalance			= /*inicio get post*/ $this->request->getPost("txtLineBalance");
			$arrayListCreditStatus			= /*inicio get post*/ $this->request->getPost("txtLineStatus");
			$arrayListTypeAmortization		= /*inicio get post*/ $this->request->getPost("txtTypeAmortization");			
			$arrayListDayExcluded			= /*inicio get post*/ $this->request->getPost("txtDayExcluded");			
			$limitCreditLine 				= 0;
			
			
			if(empty($arrayListProviderCreditLineID))
			{
				 $arrayListProviderCreditLineID[0]	= 1;
				 $arrayListCreditLineID[0] 			= $creditLineDefault;
				 $arrayListCreditCurrencyID[0]		= $this->core_web_currency->getCurrencyDefault($companyID)->currencyID;
				 $arrayListCreditLimit[0]			= 300000;
				 $arrayListCreditInterestYear[0]	= $interesDefault;
				 $arrayListCreditInterestPay[0]		= 0;
				 $arrayListCreditTotalPay[0]		= 0;
				 $arrayListCreditTotalDefeated[0]	= 0;
				 $arrayListCreditPeriodPay[0]		= $frecuencyDefault;
				 $arrayListCreditTerm[0]			= $plazoDefault;
				 $arrayListCreditNote[0]			= "-";
				 $arrayListTypeAmortization[0]		= $typeAmortizationDefault;
				 $arrayListDayExcluded[0]			= $dayExcludedDefault;
				 $arrayListCreditStatusID[0]		= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
				 
			}
			
			if(!empty($arrayListProviderCreditLineID))
			{
				foreach($arrayListProviderCreditLineID as $key => $value)
				{
					$objProviderCreditLine["companyID"]			= $objEntity["companyID"];
					$objProviderCreditLine["branchID"]			= $objEntity["branchID"];
					$objProviderCreditLine["entityID"]			= $entityID;
					$objProviderCreditLine["creditLineID"]		= $arrayListCreditLineID[$key];
					$objProviderCreditLine["accountNumber"]		= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_customer_credit_line",0);
					$objProviderCreditLine["currencyID"]		= $arrayListCreditCurrencyID[$key];
					$objProviderCreditLine["limitCredit"]		= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objProviderCreditLine["balance"]			= helper_StringToNumber($arrayListCreditLimit[$key]);
					$objProviderCreditLine["interestYear"]		= helper_StringToNumber($arrayListCreditInterestYear[$key]);
					$objProviderCreditLine["interestPay"]		= $arrayListCreditInterestPay[$key];
					$objProviderCreditLine["totalPay"]			= $arrayListCreditTotalPay[$key];
					$objProviderCreditLine["totalDefeated"]		= $arrayListCreditTotalDefeated[$key];
					$objProviderCreditLine["dateOpen"]			= date("Y-m-d");
					$objProviderCreditLine["periodPay"]			= $arrayListCreditPeriodPay[$key];
					$objProviderCreditLine["dateLastPay"]		= date("Y-m-d");
					$objProviderCreditLine["term"]				= helper_StringToNumber($arrayListCreditTerm[$key]);
					$objProviderCreditLine["note"]				= $arrayListCreditNote[$key];
					$objProviderCreditLine["statusID"]			= $arrayListCreditStatusID[$key];
					$objProviderCreditLine["isActive"]			= 1;
					$objProviderCreditLine["typeAmortization"]	= $arrayListTypeAmortization[$key];
					$objProviderCreditLine["dayExcluded"]		= $arrayListDayExcluded[$key];
					$limitCreditLine 							= $limitCreditLine + $objProviderCreditLine["limitCredit"];
					$this->Customer_Credit_Line_Model->insert_app_posme($objProviderCreditLine);
					
				}
			}
			
			//Crear la Carpeta para almacenar los Archivos del Cliente
			mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$entityID, 0700);
			
			if($db->transStatus() !== false){
				$db->transCommit();						
				$this->core_web_notification->set_message(false,SUCCESS);
				$this->response->redirect(base_url()."/".'app_cxp_provider/edit/companyID/'.$companyID."/branchID/".$objEntity["branchID"]."/entityID/".$entityID);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$this->db->_error_message());
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');	
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
			$branchID 			= /*inicio get post*/ $this->request->getPost("branchID");				
			$entityID 			= /*inicio get post*/ $this->request->getPost("entityID");				
			
			if((!$companyID && !$branchID && !$entityID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 
			
			//OBTENER EL PROVEEDOR
			$objProvider 		= $this->Provider_Model->get_rowByPK($companyID,$branchID,$entityID);	
			
			if ($entityID == APP_PROVIDER)
			{
				throw new \Exception("No es posible eliminar el proveedor, edite el nombre");
			}
			
			//PERMISO SOBRE EL REGISTRO
			if ($resultPermission 	== PERMISSION_ME && ($objProvider->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			
			//PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
			if(!$this->core_web_workflow->validateWorkflowStage("tb_provider","statusID",$objProvider->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
			
			//Eliminar el Registro
			$this->Provider_Model->delete_app_posme($companyID,$branchID,$entityID);
					
			
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
	function save($mode=""){
			$mode = helper_SegmentsByIndex($this->uri->getSegments(),1,$mode);	
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("txtCountryID","Pais","required");
			$this->validation->setRule("txtStateID","Departamento","required");
			$this->validation->setRule("txtCityID","Municipio","required");
			$this->validation->setRule("txtIdentification","Identificacion","required");
				
				
			//Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');
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
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');
				exit;
			}
			
	}
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
			 
			 			
			
			
						
			
			
			
			
			//Redireccionar datos
			
						
			$companyID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$branchID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"branchID");//--finuri	
			$entityID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"entityID");//--finuri	
			$branchIDUser	= $dataSession["user"]->branchID;
			$roleID 		= $dataSession["role"]->roleID;			
			if((!$companyID || !$branchID || !$entityID))
			{ 
				$this->response->redirect(base_url()."/".'app_cxp_provider/add');	
			} 		
			
			
			//Obtener el Registro			
			$datView["objEntity"]	 			= $this->Entity_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objNatural"]	 			= $this->Natural_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objLegal"]	 			= $this->Legal_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objProvider"]	 			= $this->Provider_Model->get_rowByPK($companyID,$branchID,$entityID);
			$datView["objEntityListEmail"]		= $this->Entity_Email_Model->get_rowByEntity($companyID,$branchID,$entityID);
			$datView["objEntityListPhone"]		= $this->Entity_Phone_Model->get_rowByEntity($companyID,$branchID,$entityID);
			
			
			$objComponent						= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
			//Obtener Informacion
			$datView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_provider","statusID",$datView["objProvider"]->statusID,$companyID,$branchIDUser,$roleID);
			$datView["objListCurrency"]					= $this->Company_Currency_Model->getByCompany($companyID);			
			$datView["objComponent"] 					= $objComponent;
			$datView["objCurrency"]						= $this->core_web_currency->getCurrencyDefault($companyID);			
			$datView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_provider","countryID",$companyID);
			$datView["objListState"]					= $this->core_web_catalog->getCatalogAllItem_Parent("tb_provider","stateID",$companyID,$datView["objProvider"]->countryID);
			$datView["objListCity"]						= $this->core_web_catalog->getCatalogAllItem_Parent("tb_provider","cityID",$companyID,$datView["objProvider"]->stateID);			
			$datView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_provider","identificationTypeID",$companyID);						
			$datView["objListProviderTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerType",$companyID);
			$datView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerCategoryID",$companyID);
			$datView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerClasificationID",$companyID);
			$datView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","payConditionID",$companyID);
			$datView["objCustomerCreditLine"]			= $this->Customer_Credit_Line_Model->get_rowByEntity($companyID,$branchID,$entityID);

			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			=  $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_provider/edit_head',$datView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_provider/edit_body',$datView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_provider/edit_script',$datView);//--finview
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
			
		    return $resultView;		}	
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
			
			//Obtener Tasa de Cambio			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			
			$dataView["objListWorkflowStage"]			= $this->core_web_workflow->getWorkflowInitStage("tb_provider","statusID",$companyID,$branchID,$roleID);
			$dataView["objListIdentificationType"]		= $this->core_web_catalog->getCatalogAllItem("tb_provider","identificationTypeID",$companyID);
			$dataView["objListCountry"]					= $this->core_web_catalog->getCatalogAllItem("tb_provider","countryID",$companyID);
			
			$dataView["objListProviderTypeID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerType",$companyID);			
			$dataView["objListCategoryID"]				= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerCategoryID",$companyID);			
			$dataView["objListClasificationID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","providerClasificationID",$companyID);			
			$dataView["objListPayConditionID"]			= $this->core_web_catalog->getCatalogAllItem("tb_provider","payConditionID",$companyID);
			
			$dataView["objListCurrency"]				= $this->Company_Currency_Model->getByCompany($companyID);
			$objCurrency								= $this->core_web_currency->getCurrencyDefault($companyID);			
			$dataView["objCurrency"]					= $objCurrency;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_provider/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_cxp_provider/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_provider/news_script',$dataView);//--finview
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
			
		    return $resultView;		}	
			
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
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_provider' NO EXISTE...");
			
			
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
			$dataSession["head"]			= /*--inicio view*/ view('app_cxp_provider/list_head');//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_cxp_provider/list_footer');//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_cxp_provider/list_script');//--finview
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
			
		    return $resultView;		}
	}
	function add_email(){
			
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
			
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxp_provider/popup_addemail_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxp_provider/popup_addemail_body');//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxp_provider/popup_addemail_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	function add_phone(){
			
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
			
			$companyID 						= $dataSession["user"]->companyID;
			$data["objListPhoneTypeID"]		= $this->core_web_catalog->getCatalogAllItem("tb_entity_phone","typeID",$companyID);
			
			//Renderizar Resultado
			$dataSession["message"]		= "";
			$dataSession["head"]		= /*--inicio view*/ view('app_cxp_provider/popup_addphone_head');//--finview
			$dataSession["body"]		= /*--inicio view*/ view('app_cxp_provider/popup_addphone_body',$data);//--finview
			$dataSession["script"]		= /*--inicio view*/ view('app_cxp_provider/popup_addphone_script');//--finview
			return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
	
	function add_credit_line(){
			
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
		
		
		
		
		$companyID 								= $dataSession["user"]->companyID;
		$branchID 								= $dataSession["user"]->branchID;
		$roleID 								= $dataSession["role"]->roleID;
		$dataView["objListLine"]				= $this->Credit_Line_Model->get_rowByCompany($companyID);
		$dataView["objCurrencyList"]			= $this->Company_Currency_Model->getByCompany($companyID);
		$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
		$dataView["objListPay"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
		$dataView["objListTypeAmortization"]	= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
		$dataView["objListDayExcluded"]			= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","dayExcluded",$companyID);
		
		$objParameterCurrenyDefault				= $this->core_web_parameter->getParameter("ACCOUNTING_CURRENCY_NAME_FUNCTION",$companyID);			
		$objParameterCurrenyDefault 			= $objParameterCurrenyDefault->value;
		$dataView["objParameterCurrenyDefault"] = $objParameterCurrenyDefault;
		
		
		$objParameterCXC_DAY_EXCLUDED_IN_CREDIT				= $this->core_web_parameter->getParameter("CXC_DAY_EXCLUDED_IN_CREDIT",$companyID);			
		$objParameterCXC_DAY_EXCLUDED_IN_CREDIT 			= $objParameterCXC_DAY_EXCLUDED_IN_CREDIT->value;
		$dataView["objParameterCXC_DAY_EXCLUDED_IN_CREDIT"] = $objParameterCXC_DAY_EXCLUDED_IN_CREDIT;
		
		
		$objParameterAmortizationDefault	= $this->core_web_parameter->getParameter("CXC_TYPE_AMORTIZATION",$companyID);			
		$objParameterAmortizationDefault 	= $objParameterAmortizationDefault->value;
		$dataView["objParameterAmortizationDefault"] = $objParameterAmortizationDefault;
		
		
		$objParameterInteresDefault						= $this->core_web_parameter->getParameter("CXC_INTERES_DEFAULT",$companyID);			
		$objParameterInteresDefault 					= $objParameterInteresDefault->value;
		$dataView["objParameterInteresDefault"] 		= $objParameterInteresDefault;
		
		$objParameterPayDefault						= $this->core_web_parameter->getParameter("CXC_FRECUENCIA_PAY_DEFAULT",$companyID);			
		$objParameterPayDefault 					= $objParameterPayDefault->value;
		$dataView["objParameterPayDefault"] 		= $objParameterPayDefault;
		$dataView["objParameterCXC_PLAZO_DEFAULT"]	= $this->core_web_parameter->getParameterValue("CXC_PLAZO_DEFAULT",$companyID);			
		
		//Renderizar Resultado
		$dataSession["message"]		= "";
		$dataSession["head"]		= /*--inicio view*/ view('app_cxp_provider/popup_addcreditline_head',$dataView);//--finview
		$dataSession["body"]		= /*--inicio view*/ view('app_cxp_provider/popup_addcreditline_body',$dataView);//--finview
		$dataSession["script"]		= /*--inicio view*/ view('app_cxp_provider/popup_addcreditline_script',$dataView);//--finview
		return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}

	function edit_credit_line(){
			
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
		
		
		$customerCreditLineID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"customerCreditLineID");//--finuri
		$companyID 							= $dataSession["user"]->companyID;
		$branchID 							= $dataSession["user"]->branchID;
		$roleID 							= $dataSession["role"]->roleID;
		
		
		$dataView["objListLine"]			= $this->Credit_Line_Model->get_rowByCompany($companyID);
		$dataView["objCurrencyList"]		= $this->Company_Currency_Model->getByCompany($companyID);
		$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_line","statusID",$companyID,$branchID,$roleID);
		$dataView["objListPay"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
		$dataView["objCustomerCreditLine"] 	= $this->Customer_Credit_Line_Model->get_rowByPK($customerCreditLineID);
		$dataView["objListTypeAmortization"]= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","typeAmortization",$companyID);
		$dataView["objListDayExcluded"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","dayExcluded",$companyID);
		
		//Renderizar Resultado
		$dataSession["message"]		= "";
		$dataSession["head"]		= /*--inicio view*/ view('app_cxp_provider/popup_editcreditline_head',$dataView);//--finview
		$dataSession["body"]		= /*--inicio view*/ view('app_cxp_provider/popup_editcreditline_body',$dataView);//--finview
		$dataSession["script"]		= /*--inicio view*/ view('app_cxp_provider/popup_editcreditline_script',$dataView);//--finview
		return view("core_masterpage/default_popup",$dataSession);//--finview-r
	}
}
?>