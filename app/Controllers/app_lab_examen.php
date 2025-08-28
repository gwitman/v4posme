<?php
//posme:2023-03-18
namespace App\Controllers;
class app_lab_examen extends _BaseController 
{
    
    function index($dataViewID = null) 
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
                        
                        $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                        if ($resultPermission 	== PERMISSION_NONE)
                            throw new \Exception(NOT_ACCESS_FUNCTION);
                            
                }
                
                //Obtener el componente Para mostrar la lista de AccountType
                $objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_examen_lab");
                if(!$objComponent)
                    throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_examen_lab' NO EXISTE...");
                    
                  
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
                    $dataSession["notification"]						= $this->core_web_error->get_error($dataSession["user"]->userID);
                    $dataSession["message"]								= $this->core_web_notification->get_message();
                    $dataSession["head"]								= /*--inicio view*/ view('app_lab_examen/list_head');//--finview
                    $dataSession["footer"]								= /*--inicio view*/ view('app_lab_examen/list_footer');//--finview
                    $dataSession["body"]								= $dataViewRender;
                    $dataSession["script"]								= /*--inicio view*/ view('app_lab_examen/list_script');//--finview
                    $dataSession["script"]			                    = $dataSession["script"].$this->core_web_javascript->createVar("componentID",$objComponent->componentID);
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
            
            $this->email->setFrom(EMAIL_APP);
            $this->email->setTo(EMAIL_APP_COPY);
            $this->email->setSubject("Error");
            $this->email->setMessage($resultView);
            
            $resultSend01 = $this->email->send();
            $resultSend02 = $this->email->printDebugger();
            
            
            return $resultView;
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
            
            //Obtener parametro de session
            $companyID				= $dataSession["company"]->companyID;
            $branchID 				= $dataSession["user"]->branchID;
            $roleID 				= $dataSession["role"]->roleID;
            $userID					= $dataSession["user"]->userID;
            
			//Obtener parametro
            $transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
            $transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri            
            $transactionMasterID	= helper_RequestGetValue($transactionMasterID,0);
            $transactionID			= helper_RequestGetValue($transactionID,0);
            $comandoID				= helper_RequestGetValue($this->request->getPost('txtComando'),"view");
			$workflowStateInit		= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_examen_lab","statusID",$companyID,$branchID,$roleID);
			$workflowStateInit		= $workflowStateInit[0];
			$statusOld				= helper_RequestGetValue($this->request->getPost('txtStatusOld'),0);			
			$statusNew				= helper_RequestGetValue(/*inicio get post*/ $this->request->getPost("txtStatusID"),$workflowStateInit->workflowStageID);
			
			$statusNewEditableParcial  	
									= 	
									$this->core_web_workflow->validateWorkflowStage(
										"tb_transaction_master_examen_lab","statusID",
										$statusNew,COMMAND_EDITABLE,
										$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID
									);
			$statusNewAplicable 	= 	
									$this->core_web_workflow->validateWorkflowStage(
										"tb_transaction_master_examen_lab","statusID",
										$statusNew,COMMAND_APLICABLE,
										$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID
									);
			$statusNewAplicable 	= 	
									$this->core_web_workflow->validateWorkflowStage(
										"tb_transaction_master_examen_lab","statusID",
										$statusNew,COMMAND_ELIMINABLE,
										$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID
									);
			$statusNewEditableTotal = 	
									$this->core_web_workflow->validateWorkflowStage(
										"tb_transaction_master_examen_lab","statusID",
										$statusNew,COMMAND_EDITABLE_TOTAL,
										$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID
									);
									
									
									
					
				
			$transactionMasterID = 
				helper_RequestGetValue($this->request->getPost('txtTransactionMasterID'),0) == 0 ? 
				$transactionMasterID
				:
				helper_RequestGetValue($this->request->getPost('txtTransactionMasterID'),0) ;
				
			$transactionID = 
				helper_RequestGetValue($this->request->getPost('txtTransactionID'),0) == 0 ? 
				$transactionID
				:
				helper_RequestGetValue($this->request->getPost('txtTransactionID'),0) ;
				
			
			//Obtener componentes
            $objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if(!$objComponentCustomer)
            throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
            
            
            $objComponentTransactionExamenLab	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_examen_lab");
            if(!$objComponentTransactionExamenLab)
            throw new \Exception("EL COMPONENTE 'tb_transaction_master_examen_lab' NO EXISTE...");
		
			$objComponentCatalog	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_catalog");
            if(!$objComponentCatalog)
            throw new \Exception("EL COMPONENTE 'tb_catalog' NO EXISTE...");
		
			$objComponentPublicCatalog	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if(!$objComponentPublicCatalog)
            throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
            
              
			//Obtener parametros   
            $customerDefault	= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);            
			
			//Obtener transacciones
			$objT 			= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$objTMOld		= null;
			$objTM			= null;
			
			//Insertar Valores Transaction Master
			if($transactionMasterID ==  0 && $comandoID == "save"){
				$objTM["companyID"] 					= $dataSession["user"]->companyID;
				$objTM["transactionID"] 				= $transactionID;			
				$objTM["branchID"]						= $dataSession["user"]->branchID;			
				$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_examen_lab",0);
				$objTM["transactionCausalID"] 			= $this->core_web_transaction->getDefaultCausalID($objTM["companyID"],$objTM["transactionID"]);
				$objTM["entityID"]						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
				$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");				
				$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
				$objTM["componentID"] 					= $objComponentTransactionExamenLab->componentID;
				$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post
				$objTM["sign"] 							= $objT->signInventory;
				$objTM["currencyID"]					= $this->core_web_currency->getCurrencyDefault($dataSession["user"]->companyID)->currencyID;
				$objTM["currencyID2"]					= $this->core_web_currency->getCurrencyExternal($dataSession["user"]->companyID)->currencyID; //$objTM["currencyID"];
				$objTM["exchangeRate"]					= 1;//$this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID"],$objTM["currencyID2"]);
				$objTM["descriptionReference"]			= "reference1=tipo de muestra, reference2=remitente,reference3= catalogItemID= catalog de examen,reference4=hora,priority=edad,areaid=sexo "; 
				$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtMuestra");//--fin peticion get o post
				$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtRemitente");//--fin peticion get o post
				$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtExamenID");//--fin peticion get o post
				$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("txtHoraHidden");//--fin peticion get o post
				$objTM["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
				$objTM["amount"] 						= 0;
				$objTM["isApplied"] 					= 0;
				$objTM["journalEntryID"] 				= 0;
				$objTM["classID"] 						= NULL;
				$objTM["priorityID"]					= /*inicio get post*/ $this->request->getPost("txtEdadID");//--fin peticion get o post
				$objTM["areaID"] 						= /*inicio get post*/ $this->request->getPost("txtSexoID");//--fin peticion get o post
				$objTM["sourceWarehouseID"]				= NULL;
				$objTM["targetWarehouseID"]				= NULL;
				$objTM["tax1"]							= 0;
				$objTM["tax2"]							= 0;
				$objTM["tax3"]							= 0;
				$objTM["tax4"]							= 0;
				$objTM["subAmount"]						= 0;
				$objTM["discount"]						= 0;
				$objTM["amount"]						= 0;
				$objTM["isActive"]						= 1;
				$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);	
				$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
				
				
			}
			else if($transactionMasterID >  0 && $comandoID == "save"){
				$objTMOld 						= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$objTM["entityID"]				= /*inicio get post*/ $this->request->getPost("txtCustomerID");
				$objTM["transactionOn"]			= /*inicio get post*/ $this->request->getPost("txtDate");		
				$objTM["note"] 					= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post	
				$objTM["reference1"] 			= /*inicio get post*/ $this->request->getPost("txtMuestra");//--fin peticion get o post
				$objTM["reference2"] 			= /*inicio get post*/ $this->request->getPost("txtRemitente");//--fin peticion get o post				
				$objTM["reference3"] 			= /*inicio get post*/ $this->request->getPost("txtExamenID");//--fin peticion get o post		
				$objTM["reference4"] 			= /*inicio get post*/ $this->request->getPost("txtHoraHidden");//--fin peticion get o post
				$objTM["priorityID"]			= /*inicio get post*/ $this->request->getPost("txtEdadID");//--fin peticion get o post
				$objTM["areaID"] 				= /*inicio get post*/ $this->request->getPost("txtSexoID");//--fin peticion get o post				
				$objTM["statusID"] 				= /*inicio get post*/ $this->request->getPost("txtStatusID");
				$objTM["statusIDChangeOn"]		= date("Y-m-d H:m:s");
				
				//El Estado solo permite editar el workflow
				if($statusNewEditableParcial)
				{
					$objTM								= array();
					$objTM["statusID"] 					= $statusNew;					
				}
				
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);				
			}
			
			//Insertar Valores TransactionMasterDetail
			if ( $comandoID == "save" && $statusNewEditableParcial == false ){
				$objTxtTransactionMasterDetail		= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDetailID");//--fin peticion get o post
				$objTxtCatalogItemID				= /*inicio get post*/ $this->request->getPost("txtPublicCatalogDetailID");//--fin peticion get o post
				$objTxtValue						= /*inicio get post*/ $this->request->getPost("txtValue");//--fin peticion get o post
				$objTxtTransactionMasterDetail			= helper_RequestGetValue($objTxtTransactionMasterDetail,array());
				$objTxtCatalogItemID					= helper_RequestGetValue($objTxtCatalogItemID,array());
				$objTxtValue							= helper_RequestGetValue($objTxtValue,array());
				
				 
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);
				$ingrearDetalle = true;
				
				
				foreach($objTxtCatalogItemID as $index => $value)
				{						
					$catalogItemID 							= $value;
					$catalogValue 							= $objTxtValue[$index];
					$objPublicCatalogModel 					= $this->Public_Catalog_Detail_Model->asObject()->find($catalogItemID);
					$objExamen 								= $this->Public_Catalog_Detail_Model->asObject()->find($objTM["reference3"]);
					$objSexo 								= $this->Public_Catalog_Detail_Model->asObject()->find($objTM["areaID"]);
					$objEdad 								= $this->Public_Catalog_Detail_Model->asObject()->find($objTM["priorityID"]);
					
					
					if(
						$objPublicCatalogModel->parentName  == $objExamen->name && 
						$objPublicCatalogModel->reference2  == $objSexo->name && 
						$objPublicCatalogModel->reference3  == $objEdad->name 
					)
					{
						$objTMD["companyID"] 					= $companyID;
						$objTMD["transactionID"] 				= $transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentCatalog->componentID;
						$objTMD["componentItemID"] 				= $catalogItemID;
						$objTMD["quantity"] 					= 0;
						$objTMD["unitaryCost"]					= 0;
						$objTMD["cost"] 						= 0;
						
						$objTMD["unitaryAmount"]				= 0;
						$objTMD["amount"] 						= 0;										
						$objTMD["discount"]						= 0;
						$objTMD["unitaryPrice"]					= 0;
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["lote"]							= "";
						$objTMD["expirationDate"]				= "";
						$objTMD["reference3"]					= $catalogValue;
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;					
						$objTMD["inventoryWarehouseSourceID"]	= NULL;
						$objTMD["inventoryWarehouseTargetID"]	= NULL;
							
						$this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					}
				}
				
				
			}
			
			
			//Obtener variales 
            $dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
            $dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentPublicCatalog->componentID);           
            
            
            if($dataView["objTransactionMaster"]){
                $dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
                $dataView["objTransactionMaster"]->transactionOn2 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn2),"Y-m-d");
            }
            
            
            $dataView["objComponentCustomer"]	= $objComponentCustomer;
            $dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);
            
			
            //Obtener estados
            if($dataView["objTransactionMaster"]){
				
                $dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_examen_lab","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
                $dataView["objListWorkflowStageAll"]	= $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_examen_lab","statusID",$companyID,$branchID,$roleID);
            }
            else{
                $dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_examen_lab","statusID",$companyID,$branchID,$roleID);
                $dataView["objListWorkflowStageAll"]	= $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_examen_lab","statusID",$companyID,$branchID,$roleID);
			}
            
            
            //Obtener cliente por defecto
            if($dataView["objTransactionMaster"]){
                $dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
            }
            else{
                $dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByCode($companyID,$customerDefault->value);
            }
            
            if(!$dataView["objCustomerDefault"])
            throw new \Exception("NO EXISTE EL CLIENTE POR DEFECTO");
            
            $dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
            $dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
            
            //Al detalle de productos escapar nombres
            if($dataView["objTransactionMasterDetail"])
            foreach($dataView["objTransactionMasterDetail"] as $key => $value)
            {
                $dataView["objTransactionMasterDetail"][$key]->name 	= htmlentities($value->name,ENT_QUOTES);
                $dataView["objTransactionMasterDetailCredit"]			= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($value->transactionMasterDetailID);
            }
            
            //Obtener valores por defecto de catalogo	
			$objPublicCatalogEdades 	= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_examen_lab.edades")->where("isActive",1)->find();
			if(!$objPublicCatalogEdades)
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE EDADES tb_transaction_master_examen_lab.edades");
			}
			
			$objPublicCatalogSexo 	= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_examen_lab.sexo")->where("isActive",1)->find();
			if(!$objPublicCatalogSexo)
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE SEXO tb_transaction_master_examen_lab.sexo");
			}
			
			$objPublicCatalogMuestra 	= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_examen_lab.muestra")->where("isActive",1)->find();
			if(!$objPublicCatalogMuestra)
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE MUESTRA tb_transaction_master_examen_lab.muestra");
			}
			
			
			$objPublicCatalogExamen 	= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_examen_lab.examen")->where("isActive",1)->find();
			if(!$objPublicCatalogExamen)
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE EDAMEN tb_transaction_master_examen_lab.examen");
			}
			
			$objPublicCatalogIndicadores 	= $this->Public_Catalog_Model->asObject()->where("systemName","tb_transaction_master_examen_lab.indicadores")->where("isActive",1)->find();
			if(!$objPublicCatalogIndicadores)
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE INDICADORES tb_transaction_master_examen_lab.indicadores");
			}
			
			
			$dataView["objListSexo"]				= $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID",$objPublicCatalogSexo[0]->publicCatalogID)->where( "isActive",1)->findAll();
			$dataView["objListEdad"]				= $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID",$objPublicCatalogEdades[0]->publicCatalogID)->where( "isActive",1)->findAll();
            $dataView["objListExamenes"]			= $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID",$objPublicCatalogExamen[0]->publicCatalogID)->where( "isActive",1)->findAll();
			$dataView["objListMuestra"]				= $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID",$objPublicCatalogMuestra[0]->publicCatalogID)->where( "isActive",1)->findAll();
			$dataView["objListIndicadores"]			= $this->Public_Catalog_Detail_Model->asObject()->where("publicCatalogID",$objPublicCatalogIndicadores[0]->publicCatalogID)->where( "isActive",1)->orderBy('sequence', 'ASC')->orderBy('parentCatalogDetailID', 'ASC')->findAll();
			
			//Obtener muestra por defecto
			$muestraID 		= 0;			
			$muestraIDName 	= 0;	
			if($transactionMasterID == 0){
				$muestraID 		= $dataView["objListMuestra"][0]->publicCatalogDetailID;
				$muestraIDName 	= $dataView["objListMuestra"][0]->name;
			}
			$dataView["muestraID"]		= $muestraID;			
			$dataView["muestraIDName"]	= $muestraIDName;			
			
			
			//Obtener sexo por defecto 
			$sexoID 	= 0;
			$sexoIDName	= 0;
			if($transactionMasterID == 0){
				$sexoID 		= $dataView["objListSexo"][0]->publicCatalogDetailID;
				$sexoIDName 	= $dataView["objListSexo"][0]->name;
			}
			$dataView["sexoID"]		= $sexoID;			
			$dataView["sexoIDName"]	= $sexoIDName;	
			
			//Obtener edad por defecto 
			$edadID 		= 0;			
			$edadIDName 	= 0;
			if($transactionMasterID == 0){
				$edadID 		= $dataView["objListEdad"][0]->publicCatalogDetailID;
				$edadIDName 	= $dataView["objListEdad"][0]->name;
			}
			$dataView["edadID"]		= $edadID;
			$dataView["edadIDName"]	= $edadIDName;
			
			//Obtener examen por defecto
			$examenId 				= 0;			
			$examenIdName			= 0;			
			if($transactionMasterID == 0){
				$examenId 		= $dataView["objListExamenes"][0]->publicCatalogDetailID;
				$examenIdName 	= $dataView["objListExamenes"][0]->name;
			}
			$dataView["examenId"]					= $examenId;
			$dataView["examenIdName"]				= $examenIdName;
			
			
			
			$muestraID 	= helper_RequestGetValueObjet($dataView["objTransactionMaster"],"reference1",$muestraID);
			$examenId 	= helper_RequestGetValueObjet($dataView["objTransactionMaster"],"reference3",$examenId);
			$edadID 	= helper_RequestGetValueObjet($dataView["objTransactionMaster"],"priorityID",$edadID);
			$sexoID 	= helper_RequestGetValueObjet($dataView["objTransactionMaster"],"areaID",$sexoID);
			$examenIdName 	= $this->Public_Catalog_Detail_Model->asObject()->find($examenId)->name;
			$sexoIDName 	= $this->Public_Catalog_Detail_Model->asObject()->find($sexoID)->name;
			$edadIDName 	= $this->Public_Catalog_Detail_Model->asObject()->find($edadID)->name;
			$dataView["examenIdName"]			= $examenIdName;
			$dataView["sexoIDName"]				= $sexoIDName;
			$dataView["edadIDName"]				= $edadIDName;
			
			$dataView["objListExamenesParametros"] 	= 
			array_filter(
				$dataView["objListIndicadores"], 
				function($obj) use ($edadID,$sexoID,$examenId,$edadIDName,$sexoIDName,$examenIdName)
				{  
					$resultx1 = ($obj->reference3 == $edadIDName);	
					$resultx2 = ($obj->reference2 == $sexoIDName);										
					$resultx3 = ($obj->parentName == $examenIdName);															
					
				
					
					if($resultx1 == false)
					{
						return false;
					}
					
					if($resultx2 == false)
					{
						return false;
					}
					
					if($resultx3 == false)
					{
						return false;
					}

					return true;
					
				}
			);
			
			
            
            //Renderizar Resultado
            $dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"]			= $this->core_web_notification->get_message();
            $dataSession["head"]			= /*--inicio view*/ view('app_lab_examen/edit_head',$dataView);//--finview
            $dataSession["body"]			= /*--inicio view*/ view('app_lab_examen/edit_body',$dataView);//--finview
            $dataSession["script"]			= /*--inicio view*/ view('app_lab_examen/edit_script',$dataView);//--finview
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
			
			
			//Validar si el estado permite eliminar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_examen_lab","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
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
			
			//Load Modelos
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			  
			
			//Nuevo Registro
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
	
	//facturacion estandar, horizontal tamaña a4
	function viewRegisterFormatoPaginaNormalA4LabGenerico(){
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
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);			
			
			$objComponentPublicCatalog	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if(!$objComponentPublicCatalog)
            throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
		
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentPublicCatalog->componentID);
			
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_examen_lab","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			$datView["objMuestra"]					= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->reference1);
			$datView["objTipoExamen"]				= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->reference3);
			$datView["objEdad"]						= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->priorityID);
			$datView["objSexo"]						= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->areaID);
			$sello		 							= getBahavioLargeDB($objCompany->type, "app_lab_examen", "sello", "");
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterExamenLab(
			    "EXAMEN",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"], 
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],	
				$datView["objTMD"],
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objMuestra"],
				$datView["objTipoExamen"],
				$datView["objEdad"],
				$datView["objSexo"],
				$sello
			);
			
			
			$this->dompdf->loadHTML($html);
			
			
			//1cm = 29.34666puntos
			//a4: 210 ancho x 297
			//a4: 21cm x 29.7cm
			//a4: 595.28puntos x 841.59puntos
			
			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->setPaper(array(0,0,234.76,6000));
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => !$objParameterShowLinkDownload ]);
			
			//descargar
			//$this->dompdf->stream();
			
			
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
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	//facturacion estandar, horizontal tamaña a4
	function viewRegisterFormatoPaginaNormalA4LabGenericoV1(){
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
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);			
			
			$objComponentPublicCatalog	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_public_catalog");
            if(!$objComponentPublicCatalog)
            throw new \Exception("EL COMPONENTE 'tb_public_catalog' NO EXISTE...");
		
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndComponent($companyID,$transactionID,$transactionMasterID,$objComponentPublicCatalog->componentID);
			
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_examen_lab","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			$datView["objMuestra"]					= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->reference1);
			$datView["objTipoExamen"]				= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->reference3);
			$datView["objEdad"]						= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->priorityID);
			$datView["objSexo"]						= $this->Public_Catalog_Detail_Model->asObject()->find($datView["objTM"]->areaID);
			
			
			
			
		    
			
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterExamenLabV1(
			    "EXAMEN",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"], 
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],	
				$datView["objTMD"],
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objMuestra"],
				$datView["objTipoExamen"],
				$datView["objEdad"],
				$datView["objSexo"]
			);
			
			
			$this->dompdf->loadHTML($html);
			
			//1cm = 29.34666puntos
			//a4: 210 ancho x 297
			//a4: 21cm x 29.7cm
			//a4: 595.28puntos x 841.59puntos
			
			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->setPaper(array(0,0,234.76,6000));
			
			$this->dompdf->render();
			
			$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
			$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			
			//visualizar
			$this->dompdf->stream("file.pdf", ['Attachment' => !$objParameterShowLinkDownload ]);
			
			//descargar
			//$this->dompdf->stream();
			
			
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
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
		
	
}
?>