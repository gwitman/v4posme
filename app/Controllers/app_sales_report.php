<?php
//posme:2023-02-27
namespace App\Controllers;
class app_sales_report extends _BaseController {
	
   
	function index($dataViewID = null){	
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			
				$parentMenuElementID 	= $this->core_web_permission->getElementID(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			}	
			
			//Obtener la Lista de Reportes
			$dataMenu["menuRenderBodyReport"] 	
									= $this->core_web_menu->render_menu_body_report($dataSession["company"],$dataSession["menuBodyReport"],$parentMenuElementID);
									
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_sales_report/view_head');//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_sales_report/view_body',$dataMenu);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_sales_report/view_script');//--finview
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
	function sales_detail(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$inventoryCategoryID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"inventoryCategoryID");//--finuri
			$warehouseID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_detail/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_detail/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_detail/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_detail(?,?,?,?,?,?,?);";		
				log_message("error","CALL pr_sales_get_report_sales_detail(2,'',2,'".$startOn."','".$endOn."',".$inventoryCategoryID.",'".$warehouseID."'); 001");				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$inventoryCategoryID,$warehouseID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				log_message("error","CALL pr_sales_get_report_sales_detail(2,'',2,'".$startOn."','".$endOn."',".$inventoryCategoryID.",'".$warehouseID."'); 002");
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("error","CALL pr_sales_get_report_sales_detail(2,'',2,'".$startOn."','".$endOn."',".$inventoryCategoryID.",'".$warehouseID."'); 003");
				
				if($dataSession["company"]->flavorID == 728)
				return view("app_sales_report/sales_detail/view_a_disemp_pasteleria_lizzette",$objDataResult);//--finview-r
				else 
				return view("app_sales_report/sales_detail/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	function sales_detail_traking_subshop(){
		try{ 
		
			
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= APP_COMPANY;
			$branchID			= APP_BRANCH;
			$userID				= APP_USERADMIN;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$inventoryCategoryID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"inventoryCategoryID");//--finuri
			$warehouseID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"keyWarehouse");//--finuri
			
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_detail_traking_subshop/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_detail_traking_subshop/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_detail_traking_subshop/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				$objWarehouse 	= $this->Warehouse_Model->getByEmailContainsString($companyID,$warehouseID);
				
				$warehouseID	= $objWarehouse->warehouseID;
				//Obtener el tipo de Comprobante
				$companyID 		= $companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_detail(?,?,?,?,?,?,?);";						
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$inventoryCategoryID,$warehouseID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $companyID . ",branchID:" . $branchID . ",userID:" . $userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				return view("app_sales_report/sales_detail_traking_subshop/view_a_disemp",$objDataResult);//--finview-r
				
			}
		}
		catch(\Exception $ex){
			
			$data["session"]   = null;
		    $data["exception"] = $ex;
		    $data["urlLogin"]  = base_url();
		    $data["urlIndex"]  = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   = base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        = view("core_template/email_error_general",$data);
			
		    echo $resultView;
		}
	}
	
	function sales_detail_commission(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$inventoryCategoryID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"inventoryCategoryID");//--finuri
			$warehouseID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri
			$userIDFilter			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"userIDFilter");//--finuri
			$texto					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"texto");//--finuri
			$txtEmployerID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"txtEmployerID");//--finuri
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$objListaUsuarios 						= $this->User_Model->get_All($dataSession["user"]->companyID);
				$dataSession["objListaUsuarios"] 		= $objListaUsuarios;		
				$dataSession["objListEmployer"]			= $this->Employee_Model->get_rowByCompanyID($dataSession["user"]->companyID);
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_detail_commission/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_detail_commission/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_detail_commission/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_detail_commission(?,?,?,?,?,?,?,?,?,?);";		
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$inventoryCategoryID,$warehouseID,$userIDFilter,$texto,$txtEmployerID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["texto"] 					= $texto;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				log_message("error","CALL pr_sales_get_report_sales_detail(2,'',2,'".$startOn."','".$endOn."',".$inventoryCategoryID.",'".$warehouseID."'); 003");
				
				
				if($dataSession["company"]->type == "majo")
				{
					$html = helper_reporte80mmSalesCommission($objDataResult);
					
					//echo $html;
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
					$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
					$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
					$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
					
					$fileNamePut 	= "commisiones_".date("dmYhis").".pdf";
					$path        	= "./resource/file_company/company_".$companyID."/component_1/component_item_0/".$fileNamePut;
					$patdir         = "./resource/file_company/company_".$companyID."/component_1/component_item_0";	
					
					if (!file_exists($patdir))
					{
						mkdir($patdir, 0755);
						chmod($patdir, 0755);
					}
					
					
					file_put_contents(
						$path,
						$this->dompdf->output()					
					);						
					
					chmod($path, 644);					
					if($objParameterShowLinkDownload == "true")
					{			
						echo "<a 
							href='".base_url()."/resource/file_company/company_".$companyID."/component_1/component_item_0/".
							$fileNamePut."'>download factura</a>
						"; 				
					
					}
					else{			
						//visualizar				
						$this->dompdf->stream($fileNamePut, ['Attachment' => $objParameterShowDownloadPreview ]);
					}
					
					
					
					//descargar
					//$this->dompdf->stream();
					
					
				}
				else
				{					
					return view("app_sales_report/sales_detail_commission/view_a_disemp",$objDataResult);//--finview-r
				}
				
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
	
	function sales_summary_by_client()
    {
        try{

            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

            //PERMISOS SOBRE LAS FUNCIONES
            if(APP_NEED_AUTHENTICATION == true){

                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);
            }


            $viewReport			= false;
            $startOn			= false;
            $endOn				= false;
            $companyID			= $dataSession["user"]->companyID;
            $branchID			= $dataSession["user"]->branchID;
            $userID				= $dataSession["user"]->userID;
            $tocken				= '';

            $viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
            $startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
            $endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri


            if(!($viewReport && $startOn && $endOn  )){

                //Renderizar Resultado
                $dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
                $dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
                $dataSession["message"]					= $this->core_web_notification->get_message();
                $dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_summary_by_client/view_head');//--finview
                $dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_summary_by_client/view_body',$dataSession);//--finview
                $dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_summary_by_client/view_script');//--finview
                $dataSession["footer"]					= "";
                return view("core_masterpage/default_report",$dataSession);//--finview-r
            }
            else{

                //Obtener el tipo de Comprobante
                $companyID 		= $dataSession["user"]->companyID;
                //Get Component
                $objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
                //Get Logo
                $objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
                //Get Company
                $objCompany 	= $this->Company_Model->get_rowByPK($companyID);
                //Get Datos
                $query			= "CALL pr_sales_get_report_sales_by_client(?,?,?,?,?,?,?);";
                $objData		= $this->Bd_Model->executeRender(
                    $query,
                    [$companyID,$tocken,$userID,$startOn,$endOn,0,0]
                );


                if(isset($objData)){
                    $objDataResult["objDetail"]				= $objData;
                }
                else{
                    $objDataResult["objDetail"]				= $objData;
                }

                $objDataResult["objCompany"] 				= $objCompany;
                $objDataResult["objStartOn"] 				= $startOn;
                $objDataResult["objEndOn"] 					= $endOn;
                $objDataResult["objLogo"] 					= $objParameter;
                $objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
                $objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);

                return view("app_sales_report/sales_summary_by_client/view_a_disemp",$objDataResult);//--finview-r

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
	
	function sales_commision(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_commision/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_commision/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_commision/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_comisssion_summary(?,?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,0,0]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_sales_report/sales_commision/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	function company_utitlity(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$txtClassID 			= /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "txtClassID");//--finuri
			$txtBranchID 			= /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "txtBranchID");//--finuri
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]					= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["objListWarehouse"]					= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["objListBranch"]						= $this->Branch_Model->getByCompany($companyID);
				$dataSession["objListCatalogItemClasificacion"] 	= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_accounting_expenses", "classID", $companyID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/company_utitlity/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/company_utitlity/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/company_utitlity/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener sucursal  
				$objBranch						= $this->Branch_Model->get_rowByPK($companyID,$txtBranchID);
				$objDataResult["branchName"]	= $txtBranchID == 0 ? "TODAS" : $objBranch->name;
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_utility_summary(?,?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$txtClassID,$txtBranchID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_sales_report/company_utitlity/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	
	function sales_detail_format_chart(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri				
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri				
				
				
			$objFechaInicial 	= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  				
			$objFechaFinal 		= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));  				
			$startOn			= !$startOn ? $objFechaInicial->format("Y-m-d"): $startOn;
			$endOn				= !$endOn ? $objFechaFinal->format("Y-m-d"): $endOn;
			$objFechaInicial 	= \DateTime::createFromFormat('Y-m-d',$startOn);  				
			$objFechaFinal 	= \DateTime::createFromFormat('Y-m-d',$endOn);  				
			
			
			//Obtener el tipo de Comprobante
			$companyID 		= $dataSession["user"]->companyID;
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
			//Get Datos
			$query			= "CALL pr_sales_get_report_sales_detail(?,?,?,?,?,?,?);";				
			$objData		= $this->Bd_Model->executeRender(
				$query,
				[$companyID,$tocken,$userID,$startOn,$endOn,0,0]
			);
			
			
			if(isset($objData)){
				$objDataResult["objDetail"]				= $objData;
			}
			else{
				$objDataResult["objDetail"]				= $objData;
			}
			
			$objDataResult["objCompany"] 				= $objCompany;
			$objDataResult["objStartOn"] 				= $startOn;
			$objDataResult["objEndOn"] 					= $endOn;
			$objDataResult["objLogo"] 					= $objParameter;
			$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
			$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
			
			//Renderizar Resultado 
			$objDataResult["message"]		= $this->core_web_notification->get_message();
			$objDataResult["head"]			= /*--inicio view*/ view('app_sales_report/sales_detail_format_chart/view_head',$objDataResult);//--finview
			$objDataResult["body"]			= /*--inicio view*/ view('app_sales_report/sales_detail_format_chart/view_body',$objDataResult);//--finview
			$objDataResult["script"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_chart/view_script',$objDataResult);//--finview
			$objDataResult["footer"]		= "";			
			return view("core_masterpage/default_report",$objDataResult);//--finview-r	
			
		
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
	
	function sales_summary(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri				
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri				
			$tax1				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"tax1");//--finuri				
			$warehouseID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri				
			$entityID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"customerID");//--finuri				
				
			
			//Obtener el componente de Item
            $objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if(!$objComponentCustomer)
                throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if(!$objComponentItem)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$objListComanyParameter							= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
            $objParameterCantidadItemPoup					= $objParameterCantidadItemPoup->value;
            $dataSession["objParameterCantidadItemPoup"] 	= $objParameterCantidadItemPoup;
			
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["objComponentCustomer"]	= $objComponentCustomer;
				$dataSession["objComponentItem"]		= $objComponentItem;
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_summary/view_head',$dataSession);//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_summary/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_summary/view_script',$dataSession);//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_summary(?,?,?,?,?,?,?,?,?,?,?);";
				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn." 23:59:59",0,"-1",$tax1,0,$warehouseID,$entityID] 
				);			
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_sales_report/sales_summary/view_a_disemp",$objDataResult);//--finview-r
				
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
	function sales_detail_format_80ml(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri				
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri				
			$hourOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourStart");//--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourEnd");//--finuri
				
			
			$startOn			= $startOn." ".$hourOn."";	
			$endOn				= $endOn." ".$hourEnd."";		
				
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml/view_body');//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_day(?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);				
				$html =	view("app_sales_report/sales_detail_format_80ml/view_a_disemp",$objDataResult);//--finview-r
							
				
				$this->dompdf->loadHTML($html);
				
				
			
				//1cm = 29.34666puntos
				//a4: 210 ancho x 297
				//a4: 21cm x 29.7cm
				//a4: 595.28puntos x 841.59puntos
				
				//$this->dompdf->setPaper('A4','portrait');
				//$this->dompdf->setPaper(array(0,0,234.76,6000));
				
				$this->dompdf->render();
				
				//visualizar
				$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
				$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			
				
				if($objParameterShowLinkDownload == "true")
				{
					$fileNamePut = "venta_0_".date("dmYhis").".pdf";
					$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_0/".$fileNamePut;
						
					file_put_contents(
						$path,
						$this->dompdf->output()					
					);								
					
					chmod($path, 644);
					
					echo "<a 
						href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_0/".
						$fileNamePut."'>download venta</a>
					"; 		

					

				}
				else{			
					//visualizar
					$this->dompdf->stream("file.pdf ", ['Attachment' => !$objParameterShowLinkDownload ]);
				}
				
				
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
	
	function sales_detail_format_80ml_direct(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri				
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri				
			$hourOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourStart");//--finuri
			$hourEnd			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"hourEnd");//--finuri
				
			
			$startOn			= $startOn." ".$hourOn."";	
			$endOn				= $endOn." ".$hourEnd."";		
				
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml_direct/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml_direct/view_body');//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml_direct/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_day(?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);								
				$objDataResult["objParameterLogo"]			= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$objDataResult["objUsuario"] 				= $dataSession["user"];
				
				$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
				$objParameterPrinterName = $objParameterPrinterName->value;
				$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
				$this->core_web_printer_direct->executePrinter80mmReportVentaDirect($objDataResult);
				
				
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
	
	
	
	function sales_detail_format_58ml(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
			
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri	
			$startOn			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri				
			$endOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri				
			
				
				
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["message"]		= $this->core_web_notification->get_message();
				$dataSession["head"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml/view_head');//--finview
				$dataSession["body"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml/view_body');//--finview
				$dataSession["script"]		= /*--inicio view*/ view('app_sales_report/sales_detail_format_80ml/view_script');//--finview
				$dataSession["footer"]		= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_day(?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn." 00:00:00",$endOn." 23:59:59"]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);				
				$html =	view("app_sales_report/sales_detail_format_80ml/view_a_disemp",$objDataResult);//--finview-r
							
				
				$this->dompdf->loadHTML($html);
			
				//1cm = 29.34666puntos
				//a4: 210 ancho x 297
				//a4: 21cm x 29.7cm
				//a4: 595.28puntos x 841.59puntos
				
				//$this->dompdf->setPaper('A4','portrait');
				//$this->dompdf->setPaper(array(0,0,234.76,6000));
				
				$this->dompdf->render();
				
				//visualizar
				$objParameterShowLinkDownload	= $this->core_web_parameter->getParameter("CORE_SHOW_LINK_DOWNOAD",$companyID);
				$objParameterShowLinkDownload	= $objParameterShowLinkDownload->value;
			
			
			
				if($objParameterShowLinkDownload == "true")
				{
					$fileNamePut = "reporte_0_".date("dmYhis").".pdf";
					$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_0/".$fileNamePut;
						
					file_put_contents(
						$path,
						$this->dompdf->output()					
					);								
					
					chmod($path, 644);
					
					echo "<a 
						href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_0/".
						$fileNamePut."'>download reporte</a>
					"; 				

				}
				else{			
					//visualizar
					$this->dompdf->stream("file.pdf ", ['Attachment' => !$objParameterShowLinkDownload ]);
				}
				
				
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
	
	
	function sales_detail_item_sales_globalpro(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$inventoryCategoryID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"inventoryCategoryID");//--finuri
				
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_detail_item_sales_globalpro/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_detail_item_sales_globalpro/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_detail_item_sales_globalpro/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_venta_de_producto(?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$inventoryCategoryID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_sales_report/sales_detail_item_sales_globalpro/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	function restock(){
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$inventoryCategoryID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"inventoryCategoryID");//--finuri
				
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/restock/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/restock/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/restock/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_venta_de_producto(?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$inventoryCategoryID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_sales_report/restock/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	
	function sales_detail_traking_globalpro()
	{
		try{ 
		
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
		
			//PERMISOS SOBRE LAS FUNCIONES
			if(APP_NEED_AUTHENTICATION == true){				
				
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				
				if(!$permited)
				throw new \Exception(NOT_ACCESS_CONTROL);
				
				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"index",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
				throw new \Exception(NOT_ACCESS_FUNCTION);			
			}	
			
								
			$viewReport			= false;
			$startOn			= false;
			$endOn				= false;
			$companyID			= $dataSession["user"]->companyID;
			$branchID			= $dataSession["user"]->branchID;
			$userID				= $dataSession["user"]->userID;
			$tocken				= '';
			
			$viewReport				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"viewReport");//--finuri
			$startOn				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"startOn");//--finuri
			$endOn					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"endOn");//--finuri
			$inventoryCategoryID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"inventoryCategoryID");//--finuri
			$warehouseID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"warehouseID");//--finuri
			
			if(!($viewReport && $startOn && $endOn  )){
				
				//Renderizar Resultado 
				$dataSession["objListCategoryItem"]		= $this->Itemcategory_Model->getByCompany($companyID);
				$dataSession["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserID($companyID,$userID);
				$dataSession["message"]					= $this->core_web_notification->get_message();
				$dataSession["head"]					= /*--inicio view*/ view('app_sales_report/sales_detail_traking_globalpro/view_head');//--finview
				$dataSession["body"]					= /*--inicio view*/ view('app_sales_report/sales_detail_traking_globalpro/view_body',$dataSession);//--finview
				$dataSession["script"]					= /*--inicio view*/ view('app_sales_report/sales_detail_traking_globalpro/view_script');//--finview
				$dataSession["footer"]					= "";			
				return view("core_masterpage/default_report",$dataSession);//--finview-r	
			}
			else{				
				
				//Obtener el tipo de Comprobante
				$companyID 		= $dataSession["user"]->companyID;
				//Get Component
				$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				//Get Logo
				$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				//Get Company
				$objCompany 	= $this->Company_Model->get_rowByPK($companyID);
				//Get Datos
				$query			= "CALL pr_sales_get_report_sales_detail(?,?,?,?,?,?,?);";				
				$objData		= $this->Bd_Model->executeRender(
					$query,
					[$companyID,$tocken,$userID,$startOn,$endOn,$inventoryCategoryID,$warehouseID]
				);
				
				
				if(isset($objData)){
					$objDataResult["objDetail"]				= $objData;
				}
				else{
					$objDataResult["objDetail"]				= $objData;
				}
				
				$objDataResult["objCompany"] 				= $objCompany;
				$objDataResult["objStartOn"] 				= $startOn;
				$objDataResult["objEndOn"] 					= $endOn;
				$objDataResult["objLogo"] 					= $objParameter;
				$objDataResult["objFirma"] 					= "{companyID:" . $dataSession["user"]->companyID . ",branchID:" . $dataSession["user"]->branchID . ",userID:" . $dataSession["user"]->userID . ",fechaID:" . date('Y-m-d H:i:s') . ",reportID:" . "pr_sales_get_report_sales_detail" . ",ip:". $this->request->getIPAddress() . ",sessionID:" . session_id() .",agenteID:". $this->request->getUserAgent()->getAgentString() .",lastActivity:".  /*inicio last_activity */ "activity" /*fin last_activity*/ . "}"  ;
				$objDataResult["objFirmaEncription"] 		= md5 ($objDataResult["objFirma"]);
				
				return view("app_sales_report/sales_detail_traking_globalpro/view_a_disemp",$objDataResult);//--finview-r
				
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
	
	function sales_detail_out_of_range()
	{
		
	}
	
	
	
}
?>