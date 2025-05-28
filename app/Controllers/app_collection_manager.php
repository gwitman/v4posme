<?php
//posme:2023-02-27
namespace App\Controllers;

use CodeIgniter\Exceptions\AlertError;
use CodeIgniter\HTTP\RedirectResponse;
class app_collection_manager extends _BaseController {
		
    function edit() 
	{				
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
			
			//Cargar Librerias
			$objComponentEmployee		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new \Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentCustomer		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("00409 EL COMPONENTE 'tb_customer' NO EXISTE...");

			$objComponentItem			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			$relationshipID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "relationshipID"); //--finuri
			$companyID 		    = $dataSession["user"]->companyID;
			$branchID 			= $dataSession["user"]->branchID;
			$roleID 			= $dataSession["role"]->roleID;

			if ((!$relationshipID )) {
				$this->response->redirect(base_url() . "/" . 'app_collection_manager/add');
			}

			//Obtener el Registro						
			$dataView["objRelationship"]		= $this->Relationship_Model->get_rowByPKID($relationshipID);
			$dataView["objCustomerAfter"]		= null;
			
			if($dataView["objRelationship"]->customerIDAfter)
			{
				$dataView["objCustomerAfter"]	= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objRelationship"]->customerIDAfter);
			}
			
			$dataView["objListEmployee"]		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			$dataView["objListCustomer"]	    = $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			$dataView["company"]				= $dataSession["company"];	
			$dataView["objComponentItem"]	    = $objComponentItem;

			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$dataView["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;	

			//Renderizar Resultado
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_collection_manager/edit_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_collection_manager/edit_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_collection_manager/edit_script',$dataView);//--finview
			$dataSession["footer"]			= "";			

			return view("core_masterpage/default_masterpage",$dataSession);//--finview-r			

		} catch (\Exception $ex) {
			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			return $resultView;
		}
	}	
	function delete()
	{
		try
		{ 
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
			$relationshipID			= /*inicio get post*/ $this->request->getPost("relationshipID");			
			if((!$relationshipID)){
					throw new \Exception(NOT_PARAMETER);			
					 
			} 			
			
			//Eliminar el Registro
			$this->Relationship_Model->delete_app_posme($relationshipID);
			
			return $this->response->setJSON(array(
				'error'   => false,
				'message' => SUCCESS
			));//--finjson
			
			
		}
		catch(\Exception $ex)
		{
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
		}		
			
	}
	function save($method = NULL)
	{
		 $method = helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
		 try{ 
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
	
			 	
			//Nuevo Registro
			if( $method == "new"){
				$this->insertElement($dataSession);										 
			} 
			//Editar Registro
			else {
				//PERMISO SOBRE LA FUNCION
				$this->updateElement($dataSession);
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
	function add(): RedirectResponse|string
	{ 				
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
			//Cargar Librerias									
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
					
			$objComponentEmployee		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
			if(!$objComponentEmployee)
			throw new \Exception("00409 EL COMPONENTE 'tb_employee' NO EXISTE...");
		
			$objComponentCustomer		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("00409 EL COMPONENTE 'tb_customer' NO EXISTE...");

			$objComponentItem			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
		
			$objData["componentEmployeeID"]	= $objComponentEmployee->componentID;
			$objData["componentCustomerID"]	= $objComponentCustomer->componentID;
			$objData["company"]				= $dataSession["company"];		
			$objData["objComponentItem"]	= $objComponentItem;

			$objListComanyParameter						= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$objParameterCantidadItemPoup				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$objData["objParameterCantidadItemPoup"]	= $objParameterCantidadItemPoup->value;	
			
			//Renderizar Resultado			
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_collection_manager/news_head',$objData);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_collection_manager/news_body',$objData);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_collection_manager/news_script',$objData);//--finview
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
			
		    echo $resultView;
		}	
			
    }
	function index($dataViewID = null)
	{	
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
			
			//Obtener el componente Para mostrar la lista gestores
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_relationship");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_relationship' NO EXISTE...");
			
			
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
			$dataView["company"]			= $dataSession["company"];
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_collection_manager/list_head',$dataView);//--finview
			$dataSession["footer"]			= /*--inicio view*/ view('app_collection_manager/list_footer',$dataView);//--finview
			$dataSession["body"]			= $dataViewRender; 
			$dataSession["script"]			= /*--inicio view*/ view('app_collection_manager/list_script',$dataView);//--finview
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
			
		    echo $resultView;
		}
	}	

	function insertElement($dataSession)
	{
		try {
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
			
			$db=db_connect();
			$db->transStart();
			//Crear Cuenta
			$obj["employeeID"]			= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
			$obj["customerID"] 			= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$obj["customerIDAfter"]		= /*inicio get post*/ $this->request->getPost("txtCustomerIDAfter");
			$obj["orderNo"] 			= /*inicio get post*/ $this->request->getPost("txtOrderNo");
			$obj["reference1"]          = $this->request->getPost("txtReference1");
			$obj["isActive"] 			= true;
			$obj["startOn"] 			= date("Y-m-d");
			$obj["endOn"] 				= date("Y-m-d");
			
			//Obtener el orden correcto
			$objAfter 			= $this->Relationship_Model->get_rowByPKAndReference1($obj["employeeID"], $obj["customerIDAfter"],$obj["reference1"]);
			if($objAfter)
			{
				$obj["orderNo"] = is_null($objAfter->orderNo) ? 0 : $objAfter->orderNo + 1 ;
			}
			//Ingresar
			$relationShipID		= $this->Relationship_Model->insert_app_posme($obj);
			
			$db->transCommit();						
			$this->core_web_notification->set_message(false,SUCCESS);
			$this->response->redirect(base_url()."/".'app_collection_manager/index');	
		} catch (\Exception $ex) {

			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
		
	}
	function updateElement($dataSession)
	{
		try {
			if (APP_NEED_AUTHENTICATION == true) {
				$permited = false;
				$permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

				if (!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);

				$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
				if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);
			}

			//PERMISO SOBRE EL REGISTRO
			$companyID 			= $dataSession["user"]->companyID;
			$relationshipID    	= $this->request->getPost("txtRelationshipID");
			$employeeID         = $this->request->getPost("txtEmployeeID");
			$customerID         = $this->request->getPost("txtCustomerID");
			$reference1         = $this->request->getPost("txtReference1");

			
			$obj["employeeID"] 			= $this->request->getPost("txtEmployeeID");			
			$obj["customerID"] 			= $this->request->getPost('txtCustomerID');
			$obj["customerIDAfter"]		= $this->request->getPost("txtCustomerIDAfter");
			$obj["orderNo"] 			= $this->request->getPost('txtOrderNo');
			$obj["reference1"]          = $this->request->getPost("txtReference1");
			
			//Calcular el orden
			
			
			$db = db_connect();
			$db->transStart();

			//Obtener el orden correcto
			$objAfter 	= $this->Relationship_Model->get_rowByPKAndReference1($obj["employeeID"], $obj["customerIDAfter"],$obj["reference1"]);			
			if($objAfter)
			{
				$obj["orderNo"] = is_null($objAfter->orderNo) ? 0 : $objAfter->orderNo + 1 ;
			}
			$result 	= $this->Relationship_Model->update_app_posme($relationshipID, $obj);
			
			if ($db->transStatus() !== false) {
				$db->transCommit();
				$this->core_web_notification->set_message(false, SUCCESS);
			} else {
				$db->transRollback();
				$this->core_web_notification->set_message(true, $db->error()["message"]);
			}

			$this->response->redirect(base_url() . "/" . 'app_collection_manager/edit/relationshipID/' . $relationshipID);
			
		} catch (\Exception $ex) {

			if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}

			$data["session"]   = $dataSession;
			$data["exception"] = $ex;
			$data["urlLogin"]  = base_url();
			$data["urlIndex"]  = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . "index";
			$data["urlBack"]   = base_url() . "/" . str_replace("app\\controllers\\", "", strtolower(get_class($this))) . "/" . helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
			$resultView        = view("core_template/email_error_general", $data);

			echo $resultView;
		}
	}
}
?>