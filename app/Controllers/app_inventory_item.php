<?php
//posme:2023-02-27
namespace App\Controllers;

use App\Libraries\core_web_auditoria;
use App\Libraries\core_web_authentication;
use App\Libraries\core_web_counter;
use App\Libraries\core_web_parameter;
use App\Libraries\core_web_permission;
use App\Libraries\core_web_tools;
use App\Models\Item_Model;
use CodeIgniter\Controller;
use Config\Services;
use DateTime;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class app_inventory_item extends _BaseController
{


    function edit()
    {
        try {
            //AUTENTICADO
            if (!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
			
			
            $dataSession = $this->session->get();
            //PERMISO SOBRE LA FUNCTION
            if (APP_NEED_AUTHENTICATION == true) 
			{
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

                if (!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "edit", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);

            }

            //Librerias
            //
            ////////////////////////////////////////
            ////////////////////////////////////////
            ////////////////////////////////////////

            //Redireccionar datos

            $companyID 	= /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "companyID");//--finuri
            $itemID 	= /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "itemID");//--finuri
            $callback 	= /*--ini uri*/helper_SegmentsValue($this->uri->getSegments(), "callback");//--finuri
            $callback 	= $callback === "" ? "false" : $callback;
            $comando 	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(), "comando");//--finuri
            $comando 	= $comando === "" ? "false" : $comando;
            $branchID 	= $dataSession["user"]->branchID;
            $roleID 	= $dataSession["role"]->roleID;
			
            if ((!$companyID || !$itemID)) 
			{
                $this->response->redirect(base_url() . "/" . 'app_inventory_item/add');
            }

            //Obtener el componente Para mostrar la lista de AccountType
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if (!$objComponent)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

            //Obtener el componente Para mostrar la lista de AccountType
            $objComponentProvider = $this->core_web_tools->getComponentIDBy_ComponentName("tb_provider");
            if (!$objComponentProvider)
                throw new \Exception("EL COMPONENTE 'tb_provider' NO EXISTE...");


            $objComponentEmployer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployer)
                throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");


            $objParameterTypePreiceDefault = $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE", $companyID);
            $objParameterTypePreiceDefault = $objParameterTypePreiceDefault->value;
            $objParameterListPreiceDefault = $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST", $companyID);
            $objParameterListPreiceDefault = $objParameterListPreiceDefault->value;
            $objParameterAll = $this->core_web_parameter->getParameterAll($companyID);

            //Activar inmueble
            if ($comando == "activate") 
			{
                $dataActivate["isActive"] = 1;
                $this->Item_Model->update_app_posme($companyID, $itemID, $dataActivate);
            }

            //Obtener Informacion
            $dataView["objComponentEmployer"] 			= $objComponentEmployer;
            $dataView["objComponent"] 					= $objComponent;
            $dataView["componentProviderID"] 			= $objComponentProvider->componentID;
            $dataView["objListConcept"] 				= $this->Company_Component_Concept_Model->get_rowByComponentItemID($companyID, $objComponent->componentID, $itemID);
            $dataView["objItem"] 						= $this->Item_Model->get_rowByPK($companyID, $itemID);
            $dataView["objItemSku"] 					= $this->Item_Sku_Model->get_rowByItemID($itemID);
            $dataView["objItemWarehouse"] 				= $this->Itemwarehouse_Model->get_rowByItemID($companyID, $itemID);
            $dataView["objListWarehouse"] 				= $this->Warehouse_Model->getByCompany($companyID);
            $dataView["objListProvider"] 				= $this->Provideritem_Model->get_rowByItemID($companyID, $itemID);
            $dataView["objListInventoryCategory"] 		= $this->Itemcategory_Model->getByCompany($companyID);
            $dataView["objListWorkflowStage"] 			= $this->core_web_workflow->getWorkflowStageByStageInit("tb_item", "statusID", $dataView["objItem"]->statusID, $companyID, $branchID, $roleID);
            $dataView["objListFamily"] 					= $this->core_web_catalog->getCatalogAllItem("tb_item", "familyID", $companyID);
            $dataView["objListUnitMeasure"] 			= $this->core_web_catalog->getCatalogAllItem("tb_item", "unitMeasureID", $companyID);
            $dataView["objListDisplay"] 				= $this->core_web_catalog->getCatalogAllItem("tb_item", "displayID", $companyID);
            $dataView["objListDisplayUnitMeasure"] 		= $this->core_web_catalog->getCatalogAllItem("tb_item", "displayUnitMeasureID", $companyID);
            $dataView["objListDisplayGerenciaExcl"] 	= $this->core_web_catalog->getCatalogAllItem("tb_item", "realStateGerenciaExclusive", $companyID);
            $dataView["objListTypePreice"] 				= $this->core_web_catalog->getCatalogAllItem("tb_price", "typePriceID", $companyID);
            $dataView["objListCurrency"] 				= $this->Company_Currency_Model->getByCompany($companyID);
            $dataView["company"] 						= $dataSession["company"];
            $dataView["useMobile"] 						= $dataSession["user"]->useMobile;
            $dataView["objParameterTypePreiceDefault"] 	= $objParameterTypePreiceDefault;
            $dataView["objParameterListPreiceDefault"] 	= $objParameterListPreiceDefault;
            $dataView["callback"] 						= $callback;
            $dataView["comando"] 						= $comando;
            $dataView["objListPriceItem"] 				= $this->Price_Model->get_rowByItemID($companyID, $dataView["objParameterListPreiceDefault"], $itemID);
            $dataView["objListPriceItemFirst"] 			= 0;

            //Obtener el primer precio del producto
            $counterIndex = 0;
            if ($dataView["objListPriceItem"])
                foreach ($dataView["objListPriceItem"] as $ws) 
				{
                    if ($counterIndex == 0)
                        $dataView["objListPriceItemFirst"] = $ws->price;
                    $counterIndex++;
                }


            $objParameterMasive 			= $this->core_web_parameter->getParameter("ITEM_PRINTER_BARCODE_MASIVE", $companyID);
            $objParameterMasive 			= $objParameterMasive->value;
            $dataView["objParameterMasive"] = $objParameterMasive;
			
			$objParameterMasiveSinPrecio 			 = $this->core_web_parameter->getParameter("ITEM_PRINTER_BARCODE_MASIVE_SIN_PRECIO", $companyID);
            $objParameterMasiveSinPrecio 			 = $objParameterMasiveSinPrecio->value;
            $dataView["objParameterMasiveSinPrecio"] = $objParameterMasiveSinPrecio;
			
			$objParameterMasiveConPrecio 			 = $this->core_web_parameter->getParameter("ITEM_PRINTER_BARCODE_MASIVE_CON_PRECIO", $companyID);
            $objParameterMasiveConPrecio 			 = $objParameterMasiveConPrecio->value;
            $dataView["objParameterMasiveConPrecio"] = $objParameterMasiveConPrecio;


            //Obtener colaborador
            $dataView["objEmployer"] 		= $this->Employee_Model->get_rowByEntityID($companyID, $dataView["objItem"]->realStateEmployerAgentID);
            $entityEmployeerID 				= helper_RequestGetValueObjet($dataView["objEmployer"], "entityID", 0);
            $dataView["objEmployerNatural"] = $this->Natural_Model->get_rowByPK($dataView["objItem"]->companyID, $dataView["objItem"]->branchID, $entityEmployeerID);
            $dataView["objEmployerLegal"] 	= $this->Legal_Model->get_rowByPK($dataView["objItem"]->companyID, $dataView["objItem"]->branchID, $entityEmployeerID);

            //direccion
            $dataView["objListCountry"] 	= $this->core_web_catalog->getCatalogAllItem("tb_item", "realStateCountryID", $companyID);
            $dataView["objListState"] 		= $this->core_web_catalog->getCatalogAllItem_Parent("tb_item", "realStateStateID", $companyID, $dataView["objItem"]->realStateCountryID);
            $dataView["objListCity"] 		= $this->core_web_catalog->getCatalogAllItem_Parent("tb_item", "realStateCityID", $companyID, $dataView["objItem"]->realStateStateID);


            //Renderizar Resultado
            $dataSession["notification"] 	= $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] 		= $this->core_web_notification->get_message();
            $dataSession["head"] 			= /*--inicio view*/view('app_inventory_item/edit_head', $dataView);//--finview
            $dataSession["body"] 			= /*--inicio view*/view('app_inventory_item/edit_body', $dataView);//--finview
            $dataSession["script"] 			= /*--inicio view*/view('app_inventory_item/edit_script', $dataView);//--finview
            $dataSession["footer"] 			= "";
			
            if ($callback == "false")
                return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
            else
                return view("core_masterpage/default_popup", $dataSession);//--finview-r


        } catch (\Exception $ex) 
		{
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

    function delete()
    {
        try {
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

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "delete", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_DELETE);

            }

            //Load Modelos
            //
            ////////////////////////////////////////
            ////////////////////////////////////////
            ////////////////////////////////////////


            //Nuevo Registro
            $companyID = /*inicio get post*/
                $this->request->getPost("companyID");
            $itemID = /*inicio get post*/
                $this->request->getPost("itemID");

            if ((!$companyID && !$itemID)) {
                throw new \Exception(NOT_PARAMETER);

            }

            //OBTENER EL ITEM
            $obj = $this->Item_Model->get_rowByPK($companyID, $itemID);
            //PERMISO SOBRE EL REGISTRO
            if ($resultPermission == PERMISSION_ME && ($obj->createdBy != $dataSession["user"]->userID))
                throw new \Exception(NOT_DELETE);
            //PERMISO PUEDE ELIMINAR EL REGISTRO SEGUN EL WORKFLOW
            if (!$this->core_web_workflow->validateWorkflowStage("tb_item", "statusID", $obj->statusID, COMMAND_ELIMINABLE, $dataSession["user"]->companyID, $dataSession["user"]->branchID, $dataSession["role"]->roleID))
                throw new \Exception(NOT_WORKFLOW_DELETE);


            //VALIDAR CANTIDAD
            if ($dataSession["company"]->type != "luciaralstate") {
                if ($obj->quantity > 0) {
                    throw new \Exception("EL REGISTRO NO PUEDE SER ELIMINADO, SU CANTIDAD ES MAYOR QUE  0");
                }
            }


            //Eliminar el Registro
            $this->Item_Model->delete_app_posme($companyID, $itemID);


            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS
            ));//--finjson


        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson
            $this->core_web_notification->set_message(true, $ex->getLine() . " " . $ex->getMessage());
        }

    }

    function searchItem()
    {
        try {
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

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);

            }

            //Load Modelos
            //
            ////////////////////////////////////////
            ////////////////////////////////////////
            ////////////////////////////////////////


            //Nuevo Registro
            $itemNumber = /*inicio get post*/
                $this->request->getPost("itemNumber");


            if (!$itemNumber) {
                throw new \Exception(NOT_PARAMETER);
            }
            $obj = $this->Item_Model->get_rowByCode($dataSession["user"]->companyID, $itemNumber);

            if (!$obj)
                throw new \Exception("NO SE ENCONTRO EL REGISTRO");


            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS,
                'companyID' => $obj->companyID,
                'itemID' => $obj->itemID
            ));//--finjson

        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson
        }
    }

    function save($method  , $item  , $dataSession )
	{
		
		
        if ($method == "edit" || $method == "new")
		{
            $method 	= helper_SegmentsByIndex($this->uri->getSegments(), 1, $method);
            $method02 	= $method;
        }

        try{

			
			
            //AUTENTICADO
            if($method == "new" || $method =="edit")
            {
                if(!$this->core_web_authentication->isAuthenticated())
                    throw new \Exception(USER_NOT_AUTENTICATED);
                $dataSession		= $this->session->get();
            }



            //Load Modelos
            //
            ////////////////////////////////////////
            ////////////////////////////////////////
            ////////////////////////////////////////


            $objComponent							= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if(!$objComponent)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			
            //Nuevo Registro
            if( $method == "new"  )
            {
               
				if(APP_NEED_AUTHENTICATION == true)
				{
					$permited = false;
					$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

					if(!$permited)
						throw new \Exception(NOT_ACCESS_CONTROL);

					$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					if ($resultPermission 	== PERMISSION_NONE)
						throw new \Exception(NOT_ALL_INSERT);

				}


                $companyID 	=  $dataSession["user"]->companyID;
                $branchID   =  $dataSession["user"]->branchID;
                $this->core_web_permission->getValueLicense($companyID,get_class($this)."/"."index");

                $paisDefault 				= $this->core_web_parameter->getParameterValue("CXC_PAIS_DEFAULT",$companyID);
                $departamentoDefault 		= $this->core_web_parameter->getParameterValue("CXC_DEPARTAMENTO_DEFAULT",$companyID);
                $municipioDefault 			= $this->core_web_parameter->getParameterValue("CXC_MUNICIPIO_DEFAULT",$companyID);
                $validateBarCode 			= $this->core_web_parameter->getParameterValue("INVENTORY_BAR_CODE_UNIQUE",$companyID);


                $paisID 			= empty (/*inicio get post*/ $this->request->getPost('txtCountryID') /*//--fin peticion get o post*/ ) ?  $paisDefault : /*inicio get post*/ $this->request->getPost('txtCountryID');  /*//--fin peticion get o post*/
                $departamentoId		= empty (/*inicio get post*/ $this->request->getPost('txtStateID') /*//--fin peticion get o post*/ ) ?  $departamentoDefault : /*inicio get post*/ $this->request->getPost('txtStateID');  /*//--fin peticion get o post*/
                $municipioId		= empty (/*inicio get post*/ $this->request->getPost('txtCityID') /*//--fin peticion get o post*/ ) ?  $municipioDefault : /*inicio get post*/ $this->request->getPost('txtCityID');  /*//--fin peticion get o post*/


                //Ingresar Cuenta
                $db=db_connect();
                $db->transStart();
                $objParameterAll						= $this->core_web_parameter->getParameterAll($companyID);
                $callback  								= /*inicio get post*/ $this->request->getPost("txtCallback");
                $comando  								= /*inicio get post*/ $this->request->getPost("txtComando");
                $objItem["companyID"]					= $companyID;
                $objItem["branchID"] 					= $branchID;
                $objItem["inventoryCategoryID"] 		= /*inicio get post*/ $this->request->getPost("txtInventoryCategoryID");
                $nameProducto							= /*inicio get post*/ rtrim(ltrim(str_replace("\\","",str_replace("'", "", $this->request->getPost("txtName") ))));
                $nameProducto 							= str_replace('"',"",$nameProducto);

                $cache = \Config\Services::cache();
                $cache->save('app_inventory_item_last_inventory_category', $objItem["inventoryCategoryID"], TIME_CACHE_APP);


                if($objParameterAll["INVENTORY_IN_NEW_ITEM_MAINTAIN_NAME"] == "true")
                {
                    $cache->save('app_inventory_item_last_inventory_name', $nameProducto, TIME_CACHE_APP);
                }
                else{
                    $cache->save('app_inventory_item_last_inventory_name', "", TIME_CACHE_APP);
                }

                $objItem["familyID"] 					= /*inicio get post*/ $this->request->getPost("txtFamilyID");
                $objItem["itemNumber"] 					= $this->core_web_counter->goNextNumber($companyID,$branchID,"tb_item",0);
                $objItem["barCode"] 					= /*inicio get post*/ $this->request->getPost("txtBarCode") == "" ? str_replace("ITT", "7777",$objItem["itemNumber"]."")  : /*inicio get post*/ $this->request->getPost("txtBarCode");
                $objItem["barCode"]						= str_replace(PHP_EOL,",",ltrim(rtrim($objItem["barCode"])));
                $objItem["barCode"]						= str_replace(",,",",",$objItem["barCode"]);
                $objItem["barCode"]						= str_replace(["\n\r", "\n", "\r"],"",$objItem["barCode"]);
                $objItemValidBarCode 					= $this->Item_Model->get_rowByCodeBarra($companyID , $objItem["barCode"]  );

                if(strtoupper($validateBarCode) == strtoupper("true"))
                {
                    if($objItemValidBarCode)
                    {
                        $this->core_web_notification->set_message(true,"Codigo de barra ya existe.");
                        $this->response->redirect(base_url()."/".'app_inventory_item/add');
                        return;
                    }

                    $objItemValidBarCode 					= $this->Item_Model->get_rowByCodeBarraSimilar($companyID , $objItem["barCode"]  );
                    if($objItemValidBarCode)
                    {

                        foreach($objItemValidBarCode as $objItemSimiliar)
                        {
                            $codeTemp = explode(",",$objItemSimiliar->barCode);

                            foreach($codeTemp as $arrayCode)
                            {
                                if($arrayCode == $objItem["barCode"] )
                                {
                                    $this->core_web_notification->set_message(true,"Codigo de barra ya existe.");
                                    $this->response->redirect(base_url()."/".'app_inventory_item/add');
                                    return;
                                }
                            }

                        }
                    }
                }


                $objItem["name"] 						= $nameProducto;
                $objItem["description"] 				= /*inicio get post*/ $this->request->getPost("txtDescription");
                $objItem["unitMeasureID"] 				= /*inicio get post*/ $this->request->getPost("txtUnitMeasureID");
                $objItem["displayID"] 					= /*inicio get post*/ $this->request->getPost("txtDisplayID");
                $objItem["capacity"] 					= /*inicio get post*/ $this->request->getPost("txtCapacity");
                $objItem["displayUnitMeasureID"] 		= /*inicio get post*/ $this->request->getPost("txtDisplayUnitMeasureID");
                $objItem["defaultWarehouseID"] 			= /*inicio get post*/ $this->request->getPost("txtDefaultWarehouseID");
                $objItem["quantity"] 					= 0;
                $objItem["quantityMax"] 				= /*inicio get post*/ $this->request->getPost("txtQuantityMax");
                $objItem["quantityMin"] 				= /*inicio get post*/ $this->request->getPost("txtQuantityMin");
                $objItem["cost"] 						= 0;
                $objItem["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
                $objItem["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
                $objItem["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
                $objItem["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
                $objItem["isPerishable"] 				= /*inicio get post*/ $this->request->getPost("txtIsPerishable");
                $objItem["isServices"] 					= /*inicio get post*/ $this->request->getPost("txtIsServices");
                $objItem["isInvoiceQuantityZero"] 		= is_null (/*inicio get post*/ $this->request->getPost("txtIsInvoiceQuantityZero") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtIsInvoiceQuantityZero") ;
                $objItem["isInvoice"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtIsInvoice") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtIsInvoice") ;
                $objItem["factorBox"] 					= /*inicio get post*/ $this->request->getPost("txtFactorBox");
                $objItem["factorProgram"] 				= /*inicio get post*/ $this->request->getPost("txtFactorProgram");
                $objItem["isActive"] 					= 1;
                $objItem["currencyID"] 					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");

                $objItem["realStateRoomBatchServices"] 				= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateRoomBatchServices") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateRoomBatchServices") ;
                $objItem["realStateRoomServices"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateRoomServices") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateRoomServices") ;
                $objItem["realStateWallInCloset"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateWallInCloset") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateWallInCloset") ;
                $objItem["realStatePiscinaPrivate"] 				= is_null (/*inicio get post*/ $this->request->getPost("txtRealStatePiscinaPrivate") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStatePiscinaPrivate") ;
                $objItem["realStateClubPiscina"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateClubPiscina") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateClubPiscina") ;
                $objItem["realStateAceptanMascota"] 				= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateAceptanMascota") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateAceptanMascota") ;
                $objItem["realStateRooBatchVisit"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateRooBatchVisit") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateRooBatchVisit") ;
                $objItem["realStateContractCorrentaje"] 			= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateContractCorrentaje") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateContractCorrentaje") ;
                $objItem["realStatePlanReference"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStatePlanReference") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStatePlanReference") ;
                $objItem["realStateLinkYoutube"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateLinkYoutube");
                $objItem["realStateLinkPaginaWeb"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateLinkPaginaWeb");
                $objItem["realStateLinkPhontos"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateLinkPhontos");
                $objItem["realStateLinkGoogleMaps"] 				= /*inicio get post*/ $this->request->getPost("txtRealStateLinkGoogleMaps");
                $objItem["realStateLinkOther"] 						= /*inicio get post*/ $this->request->getPost("txtRealStateLinkOther");
                $objItem["realStateStyleKitchen"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateStyleKitchen");

                $objItem["realStateReferenceUbicacion"] 				= /*inicio get post*/ $this->request->getPost("txtRealStateReferenceUbicacion");
                $objItem["realStateReferenceCondominio"] 				= /*inicio get post*/ $this->request->getPost("txtRealStateReferenceCondominio");
                $objItem["realStateReferenceZone"] 						= /*inicio get post*/ $this->request->getPost("txtRealStateReferenceZone");
                $objItem["realStateGerenciaExclusive"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateGerenciaExclusive");
                $objItem["realStateCountryID"]			= $paisID;
                $objItem["realStateStateID"]			= $departamentoId;
                $objItem["realStateCityID"]				= $municipioId;
                $objItem["modifiedOn"]					= helper_getDateTime();
                $objItem["realStateEmployerAgentID"]	= /*inicio get post*/ $this->request->getPost("txtEmployerID");
                $objItem["realStateStyleKitchen"] 		= /*inicio get post*/ $this->request->getPost("txtRealStateStyleKitchen");
                $objItem["realStateEmail"] 				= /*inicio get post*/ $this->request->getPost("txtRealStateEmail");
                $objItem["realStatePhone"] 				= /*inicio get post*/ $this->request->getPost("txtRealStatePhone");
				$objItem["quantityInvoice"] 			= 0;
				
				if(!$this->request->getPost("txtDateLastUse"))
				{
					$objItem["dateLastUse"] 				= date("Y-m-d");
				}
				else
				{
					$year                                   = date("Y");
					$dateLastUse                            = /*inicio get post*/ $this->request->getPost("txtDateLastUse");
					$fechaCompleta                          = "$year-$dateLastUse";
					$fecha                                  = new DateTime($fechaCompleta);
					$objItem["dateLastUse"] 				= $fecha->format("Y-m-d");
				}
				
                $this->core_web_auditoria->setAuditCreated($objItem,$dataSession,$this->request);
                
                $itemID								= $this->Item_Model->insert_app_posme($objItem);
                $companyID 							= $objItem["companyID"];
                //Crear la Carpeta para almacenar los Archivos del Item
                $pathFileFloder = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID;
                if(!file_exists($pathFileFloder))
                    mkdir($pathFileFloder, 0700);

                //Obtener la unidad del producto
                $dataView["objListUnitMeasure"]			= $this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID);
                $dataView["objUnitMeasure"]				= "";
                foreach($dataView["objListUnitMeasure"] as $key => $val)
                {
                    if($val->catalogItemID == $objItem["unitMeasureID"])
                    {
                        $dataView["objUnitMeasure"] = $val->name;
                    }
                }



                //Guardar el Detalle de las Bodegas
                $objListWarehouseID					= /*inicio get post*/ $this->request->getPost("txtDetailWarehouseID");
                $objListWarehouseQuantityMax		= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMax");
                $objListWarehouseQuantityMain		= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMin");


                if($objListWarehouseID)
                {
                    foreach($objListWarehouseID as $key => $value){
                        $objItemWarehouse["companyID"] 			= $companyID;
                        $objItemWarehouse["branchID"] 			= $objItem["branchID"];
                        $objItemWarehouse["warehouseID"] 		= $value;
                        $objItemWarehouse["itemID"] 			= $itemID;
                        $objItemWarehouse["quantity"] 			= 0;
                        $objItemWarehouse["quantityMax"] 		= $objListWarehouseQuantityMax[$key];
                        $objItemWarehouse["quantityMin"] 		= $objListWarehouseQuantityMain[$key];
                        $this->Itemwarehouse_Model->insert_app_posme($objItemWarehouse);
                    }
                }

                //Agregar las bodegas que no esten
                $objListWarehouse		= $this->Warehouse_Model->getByCompany($companyID);
                if($objListWarehouse)
                {
                    foreach($objListWarehouse as $ware)
                    {
                        $existWarehouse = $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$ware->warehouseID);
                        if($existWarehouse)
                            continue;

                        $objItemWarehouse						= null;
                        $objItemWarehouse["companyID"] 			= $companyID;
                        $objItemWarehouse["branchID"] 			= $objItem["branchID"];
                        $objItemWarehouse["warehouseID"] 		= $ware->warehouseID;
                        $objItemWarehouse["itemID"] 			= $itemID;
                        $objItemWarehouse["quantity"] 			= 0;
                        $objItemWarehouse["quantityMax"] 		= 1000;
                        $objItemWarehouse["quantityMin"] 		= 0;
                        $this->Itemwarehouse_Model->insert_app_posme($objItemWarehouse);
                    }
                }

                //Guardar Detalle de sku
                $objListCatalogItemSKU					= /*inicio get post*/ $this->request->getPost("txtDetailSkuCatalogItemID");
                $objListCatalogItemSKUValue				= /*inicio get post*/ $this->request->getPost("txtDetailSkuValue");
                if($objListCatalogItemSKU)
                    foreach($objListCatalogItemSKU as $key => $value){
                        $objSku["itemID"] 			= $itemID;
                        $objSku["catalogItemID"] 	= $value;
                        $objSku["value"] 			= $objListCatalogItemSKUValue[$key];
                        $this->Item_Sku_Model->insert_app_posme($objSku);
                    }


                $objSkuExist 				= $this->Item_Sku_Model->getByPK($itemID,$objItem["unitMeasureID"]);
                if(!$objSkuExist)
                {
                    $objSku["itemID"] 			= $itemID;
                    $objSku["catalogItemID"] 	= $objItem["unitMeasureID"];
                    $objSku["value"] 			= 1;
                    $this->Item_Sku_Model->insert_app_posme($objSku);
                }

                //Guardar proveedor por defecto
				$objParameterProviderDefault	= $this->core_web_parameter->getParameter("INVENTORY_ITEM_PROVIDER_DEFAULT",$companyID);
				$objParameterProviderDefault 	= $objParameterProviderDefault->value;
				$objListProvider				= $this->Provider_Model->get_rowByCompany($companyID);
				if($objListProvider)
                {
                    foreach($objListProvider as $pro)
                    {						
						$objTmpProvider					= [];
						$objTmpProvider["companyID"]	= $companyID;
						$objTmpProvider["branchID"]		= $objItem["branchID"];
						$objTmpProvider["itemID"]		= $itemID;
						$objTmpProvider["entityID"]		= $pro->entityID;
						$this->Provideritem_Model->insert_app_posme($objTmpProvider);
					}
				}
                


                //Ingresar la configuracion de precios
                //por defecto con 0% de utilidad
                $arrayListPrecioValue 		= /*inicio get post*/ $this->request->getPost("txtDetailTypePriceValue");
                $arrayListComisionValue 	= /*inicio get post*/ $this->request->getPost("txtDetailTypeComisionValue");
                $arrayTypePrecioId 			= /*inicio get post*/ $this->request->getPost("txtDetailTypePriceID");
                $arrayListPrecioID 			= /*inicio get post*/ $this->request->getPost("txtDetailListPriceID");
                $objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
                $listPriceID 				= $objParameterPriceDefault->value;
                $objTipePrice 				= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);

                foreach($arrayTypePrecioId as $key => $price)
                {

                    $typePriceID				= 0;
                    $typePriceID				= $arrayTypePrecioId[$key];
                    $listPriceID				= $arrayListPrecioID[$key];
                    $priceValue					= $arrayListPrecioValue[$key];
                    $comisionValue				= $arrayListComisionValue[$key];


                    //Insert register to price
                    $dataPrice["companyID"] 				= $companyID;
                    $dataPrice["listPriceID"] 				= $listPriceID;
                    $dataPrice["itemID"] 					= $itemID;
                    $dataPrice["typePriceID"] 				= $typePriceID;
                    $dataPrice["price"] 					= $priceValue;
                    $dataPrice["percentage"] 				= 0;
                    $dataPrice["percentageCommision"] 		= $comisionValue;

                    $this->Price_Model->insert_app_posme($dataPrice);


                }



                //Generar la Imagen del Codigo de Barra
                $pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID.
                    "/component_".$objComponent->componentID."/component_item_".$itemID."/barcode.jpg";

                if(	strpos($objItem["barCode"],",") > 0  ){
                }
                else{
                    $this->core_web_barcode->generate( $pathFileCodeBarra, $objItem["barCode"], "80", "horizontal", "code128", false, 1 );
                }


                //Fin
                if($db->transStatus() !== false && $comando == "false" ){
                    $db->transCommit();
                    $this->core_web_notification->set_message(false,SUCCESS);
                    $this->response->redirect(base_url()."/".'app_inventory_item/edit/companyID/'.$companyID."/itemID/".$itemID."/callback/".$callback."/comando/".$comando);
                }
                else if($db->transStatus() !== false && $comando == "pantalla_abierta_desde_la_compra" ){
                    $db->transCommit();
                    $this->core_web_notification->set_message(false,SUCCESS);

                    $cantidad 	= /*inicio get post*/ $this->request->getPost("txtQuantity");
                    $costo 		= /*inicio get post*/ $this->request->getPost("txtCost");
                    $precio     = $arrayListPrecioValue[0];


                    if(	strpos($objItem["barCode"],",") > 0  )
                    {

                        $barCodeParse = explode(",",$objItem["barCode"]);
                        $barCodeParse = $barCodeParse[0];
                        $cache->save(
                            'app_inventory_item_add_producto_al_detalle_compra',
                            "0|".$itemID."|".$costo."|".$precio."|0|0|".$objItem["itemNumber"]."|".$objItem["name"]."|". $dataView["objUnitMeasure"] ."|".$cantidad."|".  $barCodeParse  ."|add_cantidad",
                            TIME_CACHE_APP
                        );
                    }
                    else{
                        $cache->save(
                            'app_inventory_item_add_producto_al_detalle_compra',
                            "0|".$itemID."|".$costo."|".$precio."|0|0|".$objItem["itemNumber"]."|".$objItem["name"]."|". $dataView["objUnitMeasure"] ."|".$cantidad."|".$objItem["barCode"]."|add_cantidad",
                            TIME_CACHE_APP
                        );
                    }



                    $this->response->redirect(base_url()."/".'app_inventory_item/add/callback/'.$callback.'/comando/'.$comando);
                }
                else{
                    $db->transRollback();
                    $this->core_web_notification->set_message(true,$this->db->_error_message());
                    $this->response->redirect(base_url()."/".'app_inventory_item/add');
                }

            }            
            if($method == "apinew")
			{
				
                $companyID 	= APP_COMPANY ;
                $branchID   = APP_BRANCH ;
                $paisDefault 				= $this->core_web_parameter->getParameterValue("CXC_PAIS_DEFAULT",$companyID);
                $departamentoDefault 		= $this->core_web_parameter->getParameterValue("CXC_DEPARTAMENTO_DEFAULT",$companyID);
                $municipioDefault 			= $this->core_web_parameter->getParameterValue("CXC_MUNICIPIO_DEFAULT",$companyID);
                $validateBarCode 			= $this->core_web_parameter->getParameterValue("INVENTORY_BAR_CODE_UNIQUE",$companyID);


                $paisID 			= empty (/*inicio get post*/ $item['txtCountryID'] /*//--fin peticion get o post*/ ) ?  $paisDefault : /*inicio get post*/ $item['txtCountryID'];  /*//--fin peticion get o post*/
                $departamentoId		= empty (/*inicio get post*/ $item['txtStateID'] /*//--fin peticion get o post*/ ) ?  $departamentoDefault : /*inicio get post*/ $item['txtStateID'];  /*//--fin peticion get o post*/
                $municipioId		= empty (/*inicio get post*/ $item['txtCityID'] /*//--fin peticion get o post*/ ) ?  $municipioDefault : /*inicio get post*/ $item['txtCityID'];  /*//--fin peticion get o post*/


                //Ingresar Cuenta
                $db=db_connect();
                $db->transStart();
                $objParameterAll						= $this->core_web_parameter->getParameterAll($companyID);
                $callback  								= /*inicio get post*/ $item["txtCallback"];
                $comando  								= /*inicio get post*/ $item["txtComando"];
                $objItem["companyID"]					= $companyID;
                $objItem["branchID"] 					= $branchID;
                $objItem["inventoryCategoryID"] 		= /*inicio get post*/ $item["txtInventoryCategoryID"];
                $nameProducto							= /*inicio get post*/ rtrim(ltrim(str_replace("\\","",str_replace("'", "", $item["txtName"] ))));
                $nameProducto 							= str_replace('"',"",$nameProducto);

                $cache = \Config\Services::cache();
                $cache->save('app_inventory_item_last_inventory_category', $objItem["inventoryCategoryID"], TIME_CACHE_APP);


                if($objParameterAll["INVENTORY_IN_NEW_ITEM_MAINTAIN_NAME"] == "true")
                {
                    $cache->save('app_inventory_item_last_inventory_name', $nameProducto, TIME_CACHE_APP);
                }
                else{
                    $cache->save('app_inventory_item_last_inventory_name', "", TIME_CACHE_APP);
                }

                $objItem["familyID"] 					= /*inicio get post*/ $item["txtFamilyID"];
                $objItem["itemNumber"] 					= $this->core_web_counter->goNextNumber($companyID,$branchID,"tb_item",0);
                $objItem["barCode"] 					= /*inicio get post*/ $item["txtBarCode"] == "" ? str_replace("ITT", "7777", $objItem["itemNumber"]."")  : /*inicio get post*/ $item["txtBarCode"];
                $objItem["barCode"]						= str_replace(PHP_EOL,",",ltrim(rtrim($objItem["barCode"])));
                $objItem["barCode"]						= str_replace(",,",",",$objItem["barCode"]);
                $objItem["barCode"]						= str_replace(["\n\r", "\n", "\r"],"",$objItem["barCode"]);
                $objItemValidBarCode 					= $this->Item_Model->get_rowByCodeBarra($companyID , $objItem["barCode"]  );

                if(strtoupper($validateBarCode) == strtoupper("true"))
                {
                    if($objItemValidBarCode)
                    {
                        $this->core_web_notification->set_message(true,"Codigo de barra ya existe.");
                        $this->response->redirect(base_url()."/".'app_inventory_item/add');
                        return;
                    }

                    $objItemValidBarCode 					= $this->Item_Model->get_rowByCodeBarraSimilar($companyID , $objItem["barCode"]  );
                    if($objItemValidBarCode)
                    {

                        foreach($objItemValidBarCode as $objItemSimiliar)
                        {
                            $codeTemp = explode(",",$objItemSimiliar->barCode);

                            foreach($codeTemp as $arrayCode)
                            {
                                if($arrayCode == $objItem["barCode"] )
                                {
                                    $this->core_web_notification->set_message(true,"Codigo de barra ya existe.");
                                    $this->response->redirect(base_url()."/".'app_inventory_item/add');
                                    return;
                                }
                            }

                        }
                    }
                }


                $objItem["name"] 						= $nameProducto;
                $objItem["description"] 				= /*inicio get post*/ $item["txtDescription"];
                $objItem["unitMeasureID"] 				= /*inicio get post*/ $item["txtUnitMeasureID"];
                $objItem["displayID"] 					= /*inicio get post*/ $item["txtDisplayID"];
                $objItem["capacity"] 					= /*inicio get post*/ $item["txtCapacity"];
                $objItem["displayUnitMeasureID"] 		= /*inicio get post*/ $item["txtDisplayUnitMeasureID"];
                $objItem["defaultWarehouseID"] 			= /*inicio get post*/ $item["txtDefaultWarehouseID"];
                $objItem["quantity"] 					= 0;
                $objItem["quantityMax"] 				= /*inicio get post*/ $item["txtQuantityMax"];
                $objItem["quantityMin"] 				= /*inicio get post*/ $item["txtQuantityMin"];
                $objItem["cost"] 						= 0;
                $objItem["reference1"] 					= /*inicio get post*/ $item["txtReference1"];
                $objItem["reference2"] 					= /*inicio get post*/ $item["txtReference2"];
                $objItem["reference3"] 					= /*inicio get post*/ $item["txtReference3"];
                $objItem["statusID"] 					= /*inicio get post*/ $item["txtStatusID"];
                $objItem["isPerishable"] 				= /*inicio get post*/ $item["txtIsPerishable"];
                $objItem["isServices"] 					= /*inicio get post*/ $item["txtIsServices"];
                $objItem["isInvoiceQuantityZero"] 		= is_null (/*inicio get post*/ $item["txtIsInvoiceQuantityZero"] ) ? 0 : /*inicio get post*/ $item["txtIsInvoiceQuantityZero"] ;
                $objItem["isInvoice"] 					= is_null (/*inicio get post*/ $item["txtIsInvoice"] ) ? 0 : /*inicio get post*/ $item["txtIsInvoice"] ;
                $objItem["factorBox"] 					= /*inicio get post*/ $item["txtFactorBox"];
                $objItem["factorProgram"] 				= /*inicio get post*/ $item["txtFactorProgram"];
                $objItem["isActive"] 					= 1;
                $objItem["currencyID"] 					= /*inicio get post*/ $item["txtCurrencyID"];

                $objItem["realStateRoomBatchServices"] 				= !isset (/*inicio get post*/ $item["txtRealStateRoomBatchServices"] ) ? 0 : /*inicio get post*/ $item["txtRealStateRoomBatchServices"] ;
                $objItem["realStateRoomServices"] 					= !isset (/*inicio get post*/ $item["txtRealStateRoomServices"] ) ? 0 : /*inicio get post*/ $item["txtRealStateRoomServices"] ;
                $objItem["realStateWallInCloset"] 					= !isset (/*inicio get post*/ $item["txtRealStateWallInCloset"] ) ? 0 : /*inicio get post*/ $item["txtRealStateWallInCloset"] ;
                $objItem["realStatePiscinaPrivate"] 				= !isset (/*inicio get post*/ $item["txtRealStatePiscinaPrivate"] ) ? 0 : /*inicio get post*/ $item["txtRealStatePiscinaPrivate"] ;
                $objItem["realStateClubPiscina"] 					= !isset (/*inicio get post*/ $item["txtRealStateClubPiscina"] ) ? 0 : /*inicio get post*/ $item["txtRealStateClubPiscina"] ;
                $objItem["realStateAceptanMascota"] 				= !isset (/*inicio get post*/ $item["txtRealStateAceptanMascota"] ) ? 0 : /*inicio get post*/ $item["txtRealStateAceptanMascota"] ;
                $objItem["realStateRooBatchVisit"] 					= !isset (/*inicio get post*/ $item["txtRealStateRooBatchVisit"] ) ? 0 : /*inicio get post*/ $item["txtRealStateRooBatchVisit"] ;
                $objItem["realStateContractCorrentaje"] 			= !isset (/*inicio get post*/ $item["txtRealStateContractCorrentaje"] ) ? 0 : /*inicio get post*/ $item["txtRealStateContractCorrentaje"] ;
                $objItem["realStatePlanReference"] 					= !isset (/*inicio get post*/ $item["txtRealStatePlanReference"] ) ? 0 : /*inicio get post*/ $item["txtRealStatePlanReference"] ;
                $objItem["realStateLinkYoutube"] 					= /*inicio get post*/ $item["txtRealStateLinkYoutube"];
                $objItem["realStateLinkPaginaWeb"] 					= /*inicio get post*/ $item["txtRealStateLinkPaginaWeb"];
                $objItem["realStateLinkPhontos"] 					= /*inicio get post*/ $item["txtRealStateLinkPhontos"];
                $objItem["realStateLinkGoogleMaps"] 				= /*inicio get post*/ $item["txtRealStateLinkGoogleMaps"];
                $objItem["realStateLinkOther"] 						= /*inicio get post*/ $item["txtRealStateLinkOther"];
                $objItem["realStateStyleKitchen"] 					= /*inicio get post*/ $item["txtRealStateStyleKitchen"];
                $objItem["realStateReferenceUbicacion"] 				= /*inicio get post*/ $item["txtRealStateReferenceUbicacion"];
                $objItem["realStateReferenceCondominio"] 				= /*inicio get post*/ $item["txtRealStateReferenceCondominio"];
                $objItem["realStateReferenceZone"] 						= /*inicio get post*/ $item["txtRealStateReferenceZone"];
                $objItem["realStateGerenciaExclusive"] 					= /*inicio get post*/ $item["txtRealStateGerenciaExclusive"];
                $objItem["realStateCountryID"]			= $paisID;
                $objItem["realStateStateID"]			= $departamentoId;
                $objItem["realStateCityID"]				= $municipioId;
                $objItem["modifiedOn"]					= helper_getDateTime();
                $objItem["realStateEmployerAgentID"]	= 0;
                $objItem["realStateStyleKitchen"] 		= /*inicio get post*/ $item["txtRealStateStyleKitchen"];
                $objItem["realStateEmail"] 				= /*inicio get post*/ $item["txtRealStateEmail"];
                $objItem["realStatePhone"] 				= /*inicio get post*/ $item["txtRealStatePhone"];
				$objItem["quantityInvoice"] 			= 0;
                $objItem["dateLastUse"] 				= helper_getDateTime();
                $this->core_web_auditoria->setAuditCreatedAdmin($objItem,$this->request);
                
				
                $itemID								= $this->Item_Model->insert_app_posme($objItem);				
                $companyID 							= $objItem["companyID"];
                //Crear la Carpeta para almacenar los Archivos del Item
                $pathFileFloder = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID;
                if(!file_exists($pathFileFloder))
                    mkdir($pathFileFloder, 0700);

                //Obtener la unidad del producto
                $dataView["objListUnitMeasure"]			= $this->core_web_catalog->getCatalogAllItem("tb_item","unitMeasureID",$companyID);
                $dataView["objUnitMeasure"]				= "";
                foreach($dataView["objListUnitMeasure"] as $key => $val)
                {
                    if($val->catalogItemID == $objItem["unitMeasureID"])
                    {
                        $dataView["objUnitMeasure"] = $val->name;
                    }
                }

				

                //Guardar el Detalle de las Bodegas
                $objListWarehouseID					= /*inicio get post*/ $item["txtDetailWarehouseID"];
                $objListWarehouseQuantityMax		= /*inicio get post*/ $item["txtDetailQuantityMax"];
                $objListWarehouseQuantityMain		= /*inicio get post*/ $item["txtDetailQuantityMin"];


                if($objListWarehouseID)
                {
                    foreach($objListWarehouseID as $key => $value){
                        $objItemWarehouse["companyID"] 			= $companyID;
                        $objItemWarehouse["branchID"] 			= $objItem["branchID"];
                        $objItemWarehouse["warehouseID"] 		= $value;
                        $objItemWarehouse["itemID"] 			= $itemID;
                        $objItemWarehouse["quantity"] 			= 0;
                        $objItemWarehouse["quantityMax"] 		= $objListWarehouseQuantityMax[$key];
                        $objItemWarehouse["quantityMin"] 		= $objListWarehouseQuantityMain[$key];
                        $this->Itemwarehouse_Model->insert_app_posme($objItemWarehouse);
                    }
                }

                //Agregar las bodegas que no esten
                $objListWarehouse		= $this->Warehouse_Model->getByCompany($companyID);
                if($objListWarehouse)
                {
                    foreach($objListWarehouse as $ware)
                    {
                        $existWarehouse = $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$ware->warehouseID);
                        if($existWarehouse)
                            continue;

                        $objItemWarehouse						= null;
                        $objItemWarehouse["companyID"] 			= $companyID;
                        $objItemWarehouse["branchID"] 			= $objItem["branchID"];
                        $objItemWarehouse["warehouseID"] 		= $ware->warehouseID;
                        $objItemWarehouse["itemID"] 			= $itemID;
                        $objItemWarehouse["quantity"] 			= 0;
                        $objItemWarehouse["quantityMax"] 		= 1000;
                        $objItemWarehouse["quantityMin"] 		= 0;
                        $this->Itemwarehouse_Model->insert_app_posme($objItemWarehouse);
                    }
                }

                //Guardar Detalle de sku
                $objListCatalogItemSKU					= /*inicio get post*/ $item["txtDetailSkuCatalogItemID"];
                $objListCatalogItemSKUValue				= /*inicio get post*/ $item["txtDetailSkuValue"];
                if($objListCatalogItemSKU)
                    foreach($objListCatalogItemSKU as $key => $value){
                        $objSku["itemID"] 			= $itemID;
                        $objSku["catalogItemID"] 	= $value;
                        $objSku["value"] 			= $objListCatalogItemSKUValue[$key];
                        $this->Item_Sku_Model->insert_app_posme($objSku);
                    }


                $objSkuExist 				= $this->Item_Sku_Model->getByPK($itemID,$objItem["unitMeasureID"]);
                if(!$objSkuExist)
                {
                    $objSku["itemID"] 			= $itemID;
                    $objSku["catalogItemID"] 	= $objItem["unitMeasureID"];
                    $objSku["value"] 			= 1;
                    $this->Item_Sku_Model->insert_app_posme($objSku);
                }

                //Guardar proveedor por defecto
                $objParameterProviderDefault	= $this->core_web_parameter->getParameter("INVENTORY_ITEM_PROVIDER_DEFAULT",$companyID);
                $objParameterProviderDefault 	= $objParameterProviderDefault->value;
				$objListProvider				= $this->Provider_Model->get_rowByCompany($companyID);
				if($objListProvider)
                {
                    foreach($objListProvider as $pro)
                    {						
						$objTmpProvider					= [];
						$objTmpProvider["companyID"]	= $companyID;
						$objTmpProvider["branchID"]		= $objItem["branchID"];
						$objTmpProvider["itemID"]		= $itemID;
						$objTmpProvider["entityID"]		= $pro->entityID;
						$this->Provideritem_Model->insert_app_posme($objTmpProvider);
					}
				}
				
				
                //Ingresar la configuracion de precios
                //por defecto con 0% de utilidad
                $arrayListPrecioValue 		= /*inicio get post*/ $item["txtDetailTypePriceValue"];
                $arrayListComisionValue 	= /*inicio get post*/ $item["txtDetailTypeComisionValue"];
                $arrayTypePrecioId 			= /*inicio get post*/ $item["txtDetailTypePriceID"];
                $arrayListPrecioID 			= /*inicio get post*/ $item["txtDetailListPriceID"];
                $objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
                $listPriceID 				= $objParameterPriceDefault->value;
                $objTipePrice 				= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);

                foreach($arrayTypePrecioId as $key => $price)
                {

                    $typePriceID				= 0;
                    $typePriceID				= $arrayTypePrecioId[$key];
                    $listPriceID				= $arrayListPrecioID[$key];
                    $priceValue					= $arrayListPrecioValue[$key];
                    $comisionValue				= $arrayListComisionValue[$key];


                    //Insert register to price
                    $dataPrice["companyID"] 				= $companyID;
                    $dataPrice["listPriceID"] 				= $listPriceID;
                    $dataPrice["itemID"] 					= $itemID;
                    $dataPrice["typePriceID"] 				= $typePriceID;
                    $dataPrice["price"] 					= $priceValue;
                    $dataPrice["percentage"] 				= 0;
                    $dataPrice["percentageCommision"] 		= $comisionValue;

                    $this->Price_Model->insert_app_posme($dataPrice);


                }



                //Generar la Imagen del Codigo de Barra
                $pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID.
                    "/component_".$objComponent->componentID."/component_item_".$itemID."/barcode.jpg";

                if(	strpos($objItem["barCode"],",") > 0  )
				{
                }
                else
				{
                    $this->core_web_barcode->generate( $pathFileCodeBarra, $objItem["barCode"], "80", "horizontal", "code128", false, 1 );
                }
				
				if($db->transStatus() !== false){
                    $db->transCommit();                    
                }
                else{
                    $db->transRollback();                    
                }
				
				
				return $itemID;
			}
			//Editar Registro
            if($method =="edit") 
			{
				

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
                $companyID 			= $dataSession["user"]->companyID;
                $objParameterAll	= $this->core_web_parameter->getParameterAll($companyID);
                $itemID				= /*inicio get post*/ $this->request->getPost("txtItemID");
                $objOldItem 		= $this->Item_Model->get_rowByPK($companyID,$itemID);
                if ($resultPermission 	== PERMISSION_ME && ($objOldItem->createdBy != $dataSession["user"]->userID))
                    throw new \Exception(NOT_EDIT);

                //PERMISO PUEDE EDITAR EL REGISTRO
                if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$objOldItem->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
                    throw new \Exception(NOT_WORKFLOW_EDIT);



                //Crear la Carpeta para almacenar los Archivos del Item
                $directoryItem = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID;
                if(!file_exists($directoryItem))
                    mkdir( $directoryItem,0700);

                $paisDefault 				= $this->core_web_parameter->getParameterValue("CXC_PAIS_DEFAULT",$companyID);
                $departamentoDefault 		= $this->core_web_parameter->getParameterValue("CXC_DEPARTAMENTO_DEFAULT",$companyID);
                $municipioDefault 			= $this->core_web_parameter->getParameterValue("CXC_MUNICIPIO_DEFAULT",$companyID);

                $paisID 			= empty (/*inicio get post*/ $this->request->getPost('txtCountryID') /*//--fin peticion get o post*/ ) ?  $paisDefault : /*inicio get post*/ $this->request->getPost('txtCountryID');  /*//--fin peticion get o post*/
                $departamentoId		= empty (/*inicio get post*/ $this->request->getPost('txtStateID') /*//--fin peticion get o post*/ ) ?  $departamentoDefault : /*inicio get post*/ $this->request->getPost('txtStateID');  /*//--fin peticion get o post*/
                $municipioId		= empty (/*inicio get post*/ $this->request->getPost('txtCityID') /*//--fin peticion get o post*/ ) ?  $municipioDefault : /*inicio get post*/ $this->request->getPost('txtCityID');  /*//--fin peticion get o post*/



                $db=db_connect();
                $db->transStart();
                $callback  	= /*inicio get post*/ $this->request->getPost("txtCallback");
                $comando  	= /*inicio get post*/ $this->request->getPost("txtComando");
                if(!$this->core_web_workflow->validateWorkflowStage("tb_item","statusID",$objOldItem->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
                {
                    //Actualizar Cuenta
                    $objNewItem["inventoryCategoryID"] 			= /*inicio get post*/ $this->request->getPost("txtInventoryCategoryID");
                    $objNewItem["familyID"] 					= /*inicio get post*/ $this->request->getPost("txtFamilyID");
                    $objNewItem["barCode"] 						= /*inicio get post*/ $this->request->getPost("txtBarCode") == "" ? str_replace("ITT", "7777", $objOldItem->itemNumber)  : /*inicio get post*/ $this->request->getPost("txtBarCode");
                    $objNewItem["barCode"]						= str_replace(PHP_EOL,",",ltrim(rtrim($objNewItem["barCode"])));
                    $objNewItem["barCode"]						= str_replace(",,",",",$objNewItem["barCode"]);
                    $objNewItem["barCode"]						= str_replace(["\n\r", "\n", "\r"],"",$objNewItem["barCode"]);
                    $objNewItem["name"] 						= /*inicio get post*/ rtrim(ltrim(str_replace("\\","",str_replace("'", "", $this->request->getPost("txtName") ))));
                    $objNewItem["name"]							= str_replace('"',"",$objNewItem["name"]);
                    $objNewItem["description"] 					= /*inicio get post*/ $this->request->getPost("txtDescription");
                    $objNewItem["unitMeasureID"] 				= /*inicio get post*/ $this->request->getPost("txtUnitMeasureID");
                    $objNewItem["displayID"] 					= /*inicio get post*/ $this->request->getPost("txtDisplayID");
                    $objNewItem["capacity"] 					= /*inicio get post*/ $this->request->getPost("txtCapacity");
                    $objNewItem["displayUnitMeasureID"] 		= /*inicio get post*/ $this->request->getPost("txtDisplayUnitMeasureID");
                    $objNewItem["defaultWarehouseID"] 			= /*inicio get post*/ $this->request->getPost("txtDefaultWarehouseID");
                    $objNewItem["quantityMax"] 					= /*inicio get post*/ $this->request->getPost("txtQuantityMax");
                    $objNewItem["quantityMin"] 					= /*inicio get post*/ $this->request->getPost("txtQuantityMin");
                    $objNewItem["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
                    $objNewItem["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
                    $objNewItem["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
                    $objNewItem["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");
                    $objNewItem["isPerishable"] 				= /*inicio get post*/ $this->request->getPost("txtIsPerishable");
                    $objNewItem["isServices"] 					= /*inicio get post*/ $this->request->getPost("txtIsServices");
                    $objNewItem["isInvoiceQuantityZero"] 		= is_null ( /*inicio get post*/ $this->request->getPost("txtIsInvoiceQuantityZero") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtIsInvoiceQuantityZero") ;
                    $objNewItem["isInvoice"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtIsInvoice") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtIsInvoice") ;
                    $objNewItem["factorBox"] 					= /*inicio get post*/ $this->request->getPost("txtFactorBox");
                    $objNewItem["factorProgram"] 				= /*inicio get post*/ $this->request->getPost("txtFactorProgram");
                    $objNewItem["currencyID"] 					= /*inicio get post*/ $this->request->getPost("txtCurrencyID");


                    $objNewItem["realStateRoomBatchServices"] 				= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateRoomBatchServices") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateRoomBatchServices") ;
                    $objNewItem["realStateRoomServices"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateRoomServices") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateRoomServices") ;
                    $objNewItem["realStateWallInCloset"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateWallInCloset") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateWallInCloset") ;
                    $objNewItem["realStatePiscinaPrivate"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStatePiscinaPrivate") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStatePiscinaPrivate") ;
                    $objNewItem["realStateClubPiscina"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateClubPiscina") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateClubPiscina") ;
                    $objNewItem["realStateAceptanMascota"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateAceptanMascota") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateAceptanMascota") ;
                    $objNewItem["realStateRooBatchVisit"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateRooBatchVisit") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateRooBatchVisit") ;
                    $objNewItem["realStateContractCorrentaje"] 				= is_null (/*inicio get post*/ $this->request->getPost("txtRealStateContractCorrentaje") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStateContractCorrentaje") ;
                    $objNewItem["realStatePlanReference"] 					= is_null (/*inicio get post*/ $this->request->getPost("txtRealStatePlanReference") ) ? 0 : /*inicio get post*/ $this->request->getPost("txtRealStatePlanReference") ;
                    $objNewItem["realStateLinkYoutube"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateLinkYoutube");
                    $objNewItem["realStateLinkPaginaWeb"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateLinkPaginaWeb");
                    $objNewItem["realStateLinkPhontos"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateLinkPhontos");
                    $objNewItem["realStateLinkGoogleMaps"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateLinkGoogleMaps");
                    $objNewItem["realStateLinkOther"] 						= /*inicio get post*/ $this->request->getPost("txtRealStateLinkOther");
                    $objNewItem["realStateStyleKitchen"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateStyleKitchen");

                    $objNewItem["realStateReferenceUbicacion"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateReferenceUbicacion");
                    $objNewItem["realStateReferenceCondominio"] 				= /*inicio get post*/ $this->request->getPost("txtRealStateReferenceCondominio");
                    $objNewItem["realStateReferenceZone"] 						= /*inicio get post*/ $this->request->getPost("txtRealStateReferenceZone");
                    $objNewItem["realStateGerenciaExclusive"] 					= /*inicio get post*/ $this->request->getPost("txtRealStateGerenciaExclusive");
                    $objNewItem["realStateCountryID"]			= $paisID;
                    $objNewItem["realStateStateID"]				= $departamentoId;
                    $objNewItem["realStateCityID"]				= $municipioId;
                    $objNewItem["modifiedOn"]					= helper_getDateTime();
                    $objNewItem["realStateEmployerAgentID"]		= /*inicio get post*/ $this->request->getPost("txtEmployerID");
                    $objNewItem["realStateEmail"] 				= /*inicio get post*/ $this->request->getPost("txtRealStateEmail");
                    $objNewItem["realStatePhone"] 				= /*inicio get post*/ $this->request->getPost("txtRealStatePhone");
                    $year                                   = date("Y");
                    $dateLastUse                            = /*inicio get post*/ $this->request->getPost("txtDateLastUse");
                    $fechaCompleta                          = "$year-$dateLastUse";
                    $fecha                                  = new DateTime($fechaCompleta);
                    $objNewItem["dateLastUse"] 				= $fecha->format("Y-m-d");
                    //Actualizar Objeto
                    $row_affected 	= $this->Item_Model->update_app_posme($companyID,$itemID,$objNewItem);

                    //Guardar el detalle de Conceptos
                    $this->Company_Component_Concept_Model->deleteWhereComponentItemID($companyID,$objComponent->componentID,$itemID);
                    $objListConcept						= /*inicio get post*/ $this->request->getPost("txtDetailConceptName");
                    if($objListConcept)
                        foreach($objListConcept as $key => $value){
                            $objTmpConcept						= [];
                            $objTmpConcept["companyID"]			= $companyID;
                            $objTmpConcept["componentID"]		= $objComponent->componentID;
                            $objTmpConcept["componentItemID"]	= $itemID;
                            $objTmpConcept["name"]				= $value;
                            $objTmpConcept["valueIn"]			= /*inicio get post*/ $this->request->getPost("txtDetailConceptValueIn")[$key];
                            $objTmpConcept["valueOut"]			= /*inicio get post*/ $this->request->getPost("txtDetailConceptValueOut")[$key];
                            $this->Company_Component_Concept_Model->insert_app_posme($objTmpConcept);
                        }

                    //Guardar el detalle de Proveedores
                    $this->Provideritem_Model->deleteWhereItemID($companyID,$itemID);
                    $objListProviderID					= /*inicio get post*/ $this->request->getPost("txtProviderEntityID");
                    if($objListProviderID)
                        foreach($objListProviderID as $key => $value){
                            $objTmpProvider					= [];
                            $objTmpProvider["companyID"]	= $objOldItem->companyID;
                            $objTmpProvider["branchID"]		= $objOldItem->branchID;
                            $objTmpProvider["itemID"]		= $itemID;
                            $objTmpProvider["entityID"]		= $value;
                            $this->Provideritem_Model->insert_app_posme($objTmpProvider);
                        }


                    //Guardar Detalle de sku
                    $this->Item_Sku_Model->delete_app_posme($itemID);
                    $objListCatalogItemSKU					= /*inicio get post*/ $this->request->getPost("txtDetailSkuCatalogItemID");
                    $objListCatalogItemSKUValue				= /*inicio get post*/ $this->request->getPost("txtDetailSkuValue");
                    if($objListCatalogItemSKU)
                        foreach($objListCatalogItemSKU as $key => $value){
                            $objSku["itemID"] 			= $itemID;
                            $objSku["catalogItemID"] 	= $value;
                            $objSku["value"] 			= $objListCatalogItemSKUValue[$key];
                            $this->Item_Sku_Model->insert_app_posme($objSku);
                        }

                    $objSkuExist 				= $this->Item_Sku_Model->getByPK($itemID,$objNewItem["unitMeasureID"]);
                    if(!$objSkuExist)
                    {
                        $objSku["itemID"] 			= $itemID;
                        $objSku["catalogItemID"] 	= $objNewItem["unitMeasureID"];
                        $objSku["value"] 			= 1;
                        $this->Item_Sku_Model->insert_app_posme($objSku);
                    }

                    //Guardar el Detalle las Bodegas
                    $objListDetailWarehouseID			= /*inicio get post*/ $this->request->getPost("txtDetailWarehouseID");
                    $objListDetailWarehouseQuantityMax	= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMax");
                    $objListDetailWarehouseQuantityMin	= /*inicio get post*/ $this->request->getPost("txtDetailQuantityMin");

                    //Eliminar las Bodegas que no estan
                    $this->Itemwarehouse_Model->deleteWhereIDNotIn($companyID,$itemID,$objListDetailWarehouseID);

                    if($objListDetailWarehouseID) {
                        foreach ($objListDetailWarehouseID as $key => $value) {
                            $objWarehouseDetail["quantityMax"] = $objListDetailWarehouseQuantityMax[$key];
                            $objWarehouseDetail["quantityMin"] = $objListDetailWarehouseQuantityMin[$key];
                            $warehouseID = $objListDetailWarehouseID[$key];
                            $objOldItemWarehouse = $this->Itemwarehouse_Model->getByPK($companyID, $itemID, $warehouseID);
                            if ($objOldItemWarehouse) {
                                $this->Itemwarehouse_Model->update_app_posme($companyID, $itemID, $warehouseID, $objWarehouseDetail);
                            } else {
                                $objWarehouseDetail["companyID"] = $companyID;
                                $objWarehouseDetail["warehouseID"] = $warehouseID;
                                $objWarehouseDetail["itemID"] = $itemID;
                                $objWarehouseDetail["quantity"] = 0;
                                $objWarehouseDetail["branchID"] = $dataSession["user"]->branchID;
                                $this->Itemwarehouse_Model->insert_app_posme($objWarehouseDetail);
                            }
                        }
                    }


                    //Ingresar la configuracion de precios
                    //por defecto con 0% de utilidad
                    $arrayListPrecioValue 		= /*inicio get post*/ $this->request->getPost("txtDetailTypePriceValue");
                    $arrayListComisionValue 	= /*inicio get post*/ $this->request->getPost("txtDetailTypeComisionValue");
                    $arrayTypePrecioId 			= /*inicio get post*/ $this->request->getPost("txtDetailTypePriceID");
                    $arrayListPrecioID 			= /*inicio get post*/ $this->request->getPost("txtDetailListPriceID");
                    $objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
                    $listPriceID 				= $objParameterPriceDefault->value;
                    $objTipePrice 				= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);

                    foreach($arrayTypePrecioId as $key => $price)
                    {

                        $typePriceID				= 0;
                        $typePriceID				= $arrayTypePrecioId[$key];
                        $listPriceID				= $arrayListPrecioID[$key];
                        $priceValue					= $arrayListPrecioValue[$key];
                        $comisionValue				= $arrayListComisionValue[$key];

                        //Insert register to price
                        $dataPrice["companyID"] 				= $companyID;
                        $dataPrice["listPriceID"] 				= $listPriceID;
                        $dataPrice["itemID"] 					= $itemID;
                        $dataPrice["typePriceID"] 				= $typePriceID;
                        $dataPrice["price"] 					= $priceValue;
                        $dataPrice["percentage"] 				= 0;
                        $dataPrice["percentageCommision"] 		= $comisionValue;

                        $objPrice = $this->Price_Model->get_rowByPK($companyID,$listPriceID,$itemID,$typePriceID);
                        if($objPrice == null )
                        {
                            $this->Price_Model->insert_app_posme($dataPrice);
                        }
                        else{
                            $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataPrice);
                        }
                    }

                    $messageTmp						= "";
                }
                else{
                    $objNewItem["statusID"] 		= /*inicio get post*/ $this->request->getPost("txtStatusID");
                    $row_affected 					= $this->Item_Model->update_app_posme($companyID,$itemID,$objNewItem);
                    $messageTmp						= "EL REGISTRO FUE EDITADO PARCIALMENTE, POR LA CONFIGURACION DE SU ESTADO ACTUAL";
                }


                //Generar la Imagen del Codigo de Barra
                $pathFileCodeBarra = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID."/barcode.jpg";



                if(	strpos($objNewItem["barCode"],",") > 0  ){
                }
                else{
                    $this->core_web_barcode->generate( $pathFileCodeBarra, $objNewItem["barCode"], "40", "horizontal", "code128", false, 3 );
                }




                if($db->transStatus() !== false){
                    $db->transCommit();
                    $this->core_web_notification->set_message(false,SUCCESS." ".$messageTmp);
                }
                else{
                    $db->transRollback();
                    $this->core_web_notification->set_message(true,$this->db->_error_message());
                }

                $this->response->redirect(base_url()."/".'app_inventory_item/edit/companyID/'.$companyID."/itemID/".$itemID."/callback/".$callback."/comando/".$comando);

            }
			if ($method=="new_customer_mobile")
			{
                $companyID 	= $dataSession["user"]->companyID;
                $branchID   = $dataSession["user"]->branchID;
                $roleID      = $dataSession["role"]->roleID;
			
                $objListComanyParameter			 = $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
                $paisDefault				     = $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_PAIS_DEFAULT");
                $departamentoDefault             = $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_DEPARTAMENTO_DEFAULT");
                $municipioDefault                = $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_MUNICIPIO_DEFAULT");
                $validateBarCode                 = $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_BAR_CODE_UNIQUE");
			
                $paisID 			= $paisDefault->value;
                $departamentoId		= $departamentoDefault->value;
                $municipioId		= $municipioDefault->value;
			
                //Ingresar Cuenta
                $db=db_connect();
                $db->transStart();
			
                $objItem["companyID"]					= $companyID;
                $objItem["branchID"] 					= $branchID;
                $objItem["inventoryCategoryID"] 		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_CATEGORY_BY_DEFAULT_ID")->value;
                $nameProducto							= $item->name;
                $nameProducto 							= str_replace('"',"",$nameProducto);
			
                $objItem["familyID"] 					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_FAMILY_ID_DEFAULT")->value;
                $objItem["itemNumber"] 					= $this->core_web_counter->goNextNumber($companyID,$branchID,"tb_item",0);
                $objItem["barCode"] 					= $item->barCode;
                $objItem["barCode"]						= str_replace(PHP_EOL,",",ltrim(rtrim($objItem["barCode"])));
                $objItem["barCode"]						= str_replace(",,",",",$objItem["barCode"]);
                $objItem["barCode"]						= str_replace(["\n\r", "\n", "\r"],"",$objItem["barCode"]);
                $objItem["name"] 						= $nameProducto;
                $objItem["description"] 				= $nameProducto;
                $objItem["unitMeasureID"] 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_UNITMEASURE_ID_DEFAULT")->value;
                $objItem["displayID"] 					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_DISPLAY_ID_DEFAULT")->value;
                $objItem["capacity"] 					= 1;
                $objItem["displayUnitMeasureID"] 		= $objItem["unitMeasureID"];
                $objItem["defaultWarehouseID"] 			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_WAREHOUSE_ID_DEFAULT")->value;
                $objItem["quantity"] 					= $item->quantity;
                $objItem["quantityMax"] 				= 1000;
                $objItem["quantityMin"] 				= 0;
                $objItem["cost"] 						= 0;
                $objItem["reference1"] 					= "";
                $objItem["reference2"] 					= "";
                $objItem["reference3"] 					= "";				
                $objItem["statusID"]                    = $this->core_web_workflow->getWorkflowInitStage("tb_item","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;				
                $objItem["isPerishable"] 				= 0;
                $objItem["isServices"] 					= 0;
                $objItem["isInvoiceQuantityZero"] 		= 1;
                $objItem["isInvoice"] 					= 1;
                $objItem["factorBox"] 					= 1;
                $objItem["factorProgram"] 				= 1;
                $objItem["isActive"] 					= 1;				
                $objItem["currencyID"] 					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVENTORY_CURRENCY_ID_DEFAULT")->value;
			
                $objItem["realStateRoomBatchServices"] 				= "";
                $objItem["realStateRoomServices"] 					= "";
                $objItem["realStateWallInCloset"] 					= "";
                $objItem["realStatePiscinaPrivate"] 				= "";
                $objItem["realStateClubPiscina"] 					= "";
                $objItem["realStateAceptanMascota"] 				= "";
                $objItem["realStateRooBatchVisit"] 					= "";
                $objItem["realStateContractCorrentaje"] 			= "";
                $objItem["realStatePlanReference"] 					= "";
                $objItem["realStateLinkYoutube"] 					= "";
                $objItem["realStateLinkPaginaWeb"] 					= "";
                $objItem["realStateLinkPhontos"] 					= "";
                $objItem["realStateLinkGoogleMaps"] 				= "";
                $objItem["realStateLinkOther"] 						= "";
                $objItem["realStateStyleKitchen"] 					= "";				
                $objItem["realStateReferenceUbicacion"] 				= "";
                $objItem["realStateReferenceCondominio"] 				= "";
                $objItem["realStateReferenceZone"] 						= "";
                $objItem["realStateGerenciaExclusive"] 					= "";
                $objItem["realStateCountryID"]			= $paisID;
                $objItem["realStateStateID"]			= $departamentoId;
                $objItem["realStateCityID"]				= $municipioId;
                $objItem["modifiedOn"]					= helper_getDateTime();
                $objItem["realStateEmployerAgentID"]	= "";
                $objItem["realStateStyleKitchen"] 		= "";
                $objItem["realStateEmail"] 				= "";
                $objItem["realStatePhone"] 				= "";
				$objItem["quantityInvoice"] 			= 0;
				$objItem["dateLastUse"] 				= helper_getDateTime();
				
                $this->core_web_auditoria->setAuditCreated($objItem,$dataSession,$this->request);
			
                $itemID								= $this->Item_Model->insert_app_posme($objItem);
                $companyID 							= $objItem["companyID"];
                //Crear la Carpeta para almacenar los Archivos del Item
                $pathFileFloder = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponent->componentID."/component_item_".$itemID;
                if(!file_exists($pathFileFloder))
                    mkdir($pathFileFloder, 0700);
			
                //Agregar las bodegas que no esten
                $objListWarehouse		= $this->Warehouse_Model->getByCompany($companyID);
                if($objListWarehouse)
                {
                    foreach($objListWarehouse as $ware)
                    {
                        $existWarehouse = $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$ware->warehouseID);
                        if($existWarehouse)
                            continue;
			
                        $objItemWarehouse						= null;
                        $objItemWarehouse["companyID"] 			= $companyID;
                        $objItemWarehouse["branchID"] 			= $objItem["branchID"];
                        $objItemWarehouse["warehouseID"] 		= $ware->warehouseID;
                        $objItemWarehouse["itemID"] 			= $itemID;
                        $objItemWarehouse["quantity"] 			= 0;
                        $objItemWarehouse["quantityMax"] 		= 1000;
                        $objItemWarehouse["quantityMin"] 		= 0;
                        $this->Itemwarehouse_Model->insert_app_posme($objItemWarehouse);
                    }
                }
			
                //Guardar Detalle de sku
                $objSkuExist 				= $this->Item_Sku_Model->getByPK($itemID,$objItem["unitMeasureID"]);
                if(!$objSkuExist)
                {
                    $objSku["itemID"] 			= $itemID;
                    $objSku["catalogItemID"] 	= $objItem["unitMeasureID"];
                    $objSku["value"] 			= 1;
                    $this->Item_Sku_Model->insert_app_posme($objSku);
                }
			
                //Guardar proveedor por defecto
                $objParameterProviderDefault	= $this->core_web_parameter->getParameter("INVENTORY_ITEM_PROVIDER_DEFAULT",$companyID);
                $objParameterProviderDefault 	= $objParameterProviderDefault->value;
				$objListProvider				= $this->Provider_Model->get_rowByCompany($companyID);
				if($objListProvider)
                {
                    foreach($objListProvider as $pro)
                    {						
						$objTmpProvider					= [];
						$objTmpProvider["companyID"]	= $companyID;
						$objTmpProvider["branchID"]		= $objItem["branchID"];
						$objTmpProvider["itemID"]		= $itemID;
						$objTmpProvider["entityID"]		= $pro->entityID;
						$this->Provideritem_Model->insert_app_posme($objTmpProvider);
					}
				}
				
			
			
                //Ingresar la configuracion de precios
                //por defecto con 0% de utilidad
                $objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
                $listPriceID 				= $objParameterPriceDefault->value;
                $objTipePrice 				= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
                foreach($objTipePrice as $key => $price)
                {
                    $typePriceID				= $objTipePrice[$key];
                    $priceValue					= $item->precioPublico;
                    $comisionValue				= 0;
			
			
                    //Insert register to price
                    $dataPrice["companyID"] 				= $companyID;
                    $dataPrice["listPriceID"] 				= $listPriceID;
                    $dataPrice["itemID"] 					= $itemID;
                    $dataPrice["typePriceID"] 				= $typePriceID->catalogItemID;
                    $dataPrice["price"] 					= $priceValue;
                    $dataPrice["percentage"] 				= 0;
                    $dataPrice["percentageCommision"] 		= $comisionValue;
			
                    $this->Price_Model->insert_app_posme($dataPrice);
                }
			
                //Fin
                if($db->transStatus() !== false){
                    $db->transCommit();
                }
                else{
                    $db->transRollback();
                }
            }
			if($method=="edit_customer_mobile")
			{
                $companyID 	= $dataSession["user"]->companyID;
			
                $db=db_connect();
                $db->transStart();
			
                $objItem['name']=$item->name;
                $objItem['description']=$item->name;
                $itemID=$item->itemID;
			
                $result = $this->Item_Model->update_app_posme($companyID,$itemID,$objItem);
			
                //Ingresar la configuracion de precios
                //por defecto con 0% de utilidad
                $objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
                $listPriceID 				= $objParameterPriceDefault->value;
                $objTipePrice 				= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
                foreach($objTipePrice as $key => $price)
                {
                    $typePriceID				= $objTipePrice[$key];
                    $priceValue					= $item->precioPublico;
                    $comisionValue				= 0;
			
                    //Insert register to price
                    $dataPrice["companyID"] 				= $companyID;
                    $dataPrice["listPriceID"] 				= $listPriceID;
                    $dataPrice["itemID"] 					= $itemID;
                    $dataPrice["typePriceID"] 				= $typePriceID->catalogItemID;
                    $dataPrice["price"] 					= $priceValue;
                    $dataPrice["percentage"] 				= 0;
                    $dataPrice["percentageCommision"] 		= $comisionValue;
			
                    $objPrice = $this->Price_Model->get_rowByPK($companyID,$listPriceID,$itemID,$dataPrice["typePriceID"]);
                    if($objPrice == null )
                    {
                        $this->Price_Model->insert_app_posme($dataPrice);
                    }
                    else{
                        $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$dataPrice["typePriceID"],$dataPrice);
                    }
                }
			
                //Fin
                if($db->transStatus() !== false){
                    $db->transCommit();
                }
                else{
                    $db->transRollback();
                }
            }
        }
        catch(\Exception $ex)
		{
            
            if($method == "new" || $method == "edit")
            {
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
            if($method == "apinew")
            {
                log_message("error",print_r($ex,true));
            }

        }
        return true;
    }

    function add()
    {

        try {
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


            $companyID = $dataSession["user"]->companyID;
            $branchID = $dataSession["user"]->branchID;
            $roleID = $dataSession["role"]->roleID;
            $callback = /*--ini uri*/
                helper_SegmentsValue($this->uri->getSegments(), "callback");//--finuri
            $callback = $callback === "" ? "false" : $callback;
            $comando = /*--ini uri*/
                helper_SegmentsValue($this->uri->getSegments(), "comando");//--finuri
            $comando = $comando === "" ? "false" : $comando;

            $objParameterWarehouseDefault = $this->core_web_parameter->getParameter("INVENTORY_ITEM_WAREHOUSE_DEFAULT", $companyID);
            $warehouseDefault = $objParameterWarehouseDefault->value;

            $objParameterTypePreiceDefault = $this->core_web_parameter->getParameter("INVOICE_DEFAULT_TYPE_PRICE", $companyID);
            $objParameterTypePreiceDefault = $objParameterTypePreiceDefault->value;
            $objParameterListPreiceDefault = $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST", $companyID);
            $objParameterListPreiceDefault = $objParameterListPreiceDefault->value;
            $objParameterInvoiceBillingQuantityZero = $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO", $companyID);
            $objParameterInvoiceBillingQuantityZero = $objParameterInvoiceBillingQuantityZero->value;
            $objParameterAll = $this->core_web_parameter->getParameterAll($companyID);


            $objComponentEmployer = $this->core_web_tools->getComponentIDBy_ComponentName("tb_employee");
            if (!$objComponentEmployer)
                throw new \Exception("EL COMPONENTE 'tb_employee' NO EXISTE...");

            $dataView["objListCountry"] = $this->core_web_catalog->getCatalogAllItem("tb_item", "realStateCountryID", $companyID);
            $dataView["objComponentEmployer"] = $objComponentEmployer;
            $dataView["objListWarehouse"] = $this->Warehouse_Model->getByCompany($companyID);
            $dataView["objListInventoryCategory"] = $this->Itemcategory_Model->getByCompany($companyID);
            $dataView["objListWorkflowStage"] = $this->core_web_workflow->getWorkflowInitStage("tb_item", "statusID", $companyID, $branchID, $roleID);
            $dataView["objListFamily"] = $this->core_web_catalog->getCatalogAllItem("tb_item", "familyID", $companyID);
            $dataView["objListUnitMeasure"] = $this->core_web_catalog->getCatalogAllItem("tb_item", "unitMeasureID", $companyID);
            $dataView["objListDisplay"] = $this->core_web_catalog->getCatalogAllItem("tb_item", "displayID", $companyID);
            $dataView["objListDisplayUnitMeasure"] = $this->core_web_catalog->getCatalogAllItem("tb_item", "displayUnitMeasureID", $companyID);
            $dataView["objListDisplayGerenciaExcl"] = $this->core_web_catalog->getCatalogAllItem("tb_item", "realStateGerenciaExclusive", $companyID);
            $dataView["objListTypePreice"] = $this->core_web_catalog->getCatalogAllItem("tb_price", "typePriceID", $companyID);
            $dataView["objListCurrency"] = $this->Company_Currency_Model->getByCompany($companyID);
            $dataView["warehouseDefault"] = $warehouseDefault;
            $dataView["company"] = $dataSession["company"];

            $cache = Services::cache();
            $dataView["app_inventory_item_last_inventory_category"] = $cache->get('app_inventory_item_last_inventory_category');
            $dataView["app_inventory_item_last_inventory_name"] = $cache->get('app_inventory_item_last_inventory_name');
            $dataView["app_inventory_item_add_producto_al_detalle_compra"] = $cache->get('app_inventory_item_add_producto_al_detalle_compra');

            $dataView["objParameterTypePreiceDefault"] = $objParameterTypePreiceDefault;
            $dataView["objParameterListPreiceDefault"] = $objParameterListPreiceDefault;
            $dataView["objParameterInvoiceBillingQuantityZero"] = $objParameterInvoiceBillingQuantityZero;
            $dataView["callback"] = $callback;
            $dataView["comando"] = $comando;
            $dataView["objParameterAll"] = $objParameterAll;
            $dataView["useMobile"] = $dataSession["user"]->useMobile;

            //Renderizar Resultado
            $dataSession["notification"] = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] = $this->core_web_notification->get_message();
            $dataSession["head"] = /*--inicio view*/
                view('app_inventory_item/news_head', $dataView);//--finview
            $dataSession["body"] = /*--inicio view*/
                view('app_inventory_item/news_body', $dataView);//--finview
            $dataSession["script"] = /*--inicio view*/
                view('app_inventory_item/news_script', $dataView);//--finview
            $dataSession["footer"] = "";
            $cache->save('app_inventory_item_add_producto_al_detalle_compra', "", TIME_CACHE_APP);

            if ($callback == "false")
                return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
            else
                return view("core_masterpage/default_popup", $dataSession);//--finview-r

        } catch (\Exception $ex) {
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

    function index($dataViewID = null)
    {
        try {

            //crear token en facebook
            //https://developers.facebook.com/tools/explorer/?method=POST&path=109895114143555%2Ffeed%3Fmessage%3Dhola2&version=v19.0&locale=es_ES
            //publicaciones en pagina de facebook
            //https://developers.facebook.com/docs/pages-api/posts
            //Lanzar un Post en una pagina de facebook success
            //-wgonzalez-publicar-en-pagina-require_once APPPATH . 'ThirdParty/Facebook/autoload.php';
            //-wgonzalez-publicar-en-pagina-$fb = new Facebook([
            //-wgonzalez-publicar-en-pagina-	'app_id' => '1863115980872262',
            //-wgonzalez-publicar-en-pagina-	'app_secret' => '02678c823881c45d3f10ca3b8bcceaec',
            //-wgonzalez-publicar-en-pagina-	'default_graph_version' => 'v19.0',
            //-wgonzalez-publicar-en-pagina-]);
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-$accessToken = 'EAAaefn43GkYBO5KVA5ZBERSEsTacyIZCoukwred8zrEdtoXlSeZCV2LZChxlQbfyArnxwtIRyIn2ueovqnUqgzS8HdxWEwTZB4X6SFUimK9k5AaaqHedKKZCEICIQ4yDl5ScykZC66gZB1qya9ZC4hDNmyfabCt65gAg7JqRBiSPvbS2GU9OM6MP1DDWDxOkLqHcDywvta7OPNMoch9nsqvRuNTIZD';
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-try {
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-	// Obtener instancia del cliente de Graph API
            //-wgonzalez-publicar-en-pagina-	$response = $fb->post('/109895114143555/feed', [
            //-wgonzalez-publicar-en-pagina-		'message' => 'Título de tu producto tes tst',
            //-wgonzalez-publicar-en-pagina-		'link' => 'https://www.posme.net'
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-	], $accessToken);
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-	$graphNode = $response->getGraphNode();
            //-wgonzalez-publicar-en-pagina-
            //-wgonzalez-publicar-en-pagina-	echo 'Publicación exitosa en Marketplace: ' . $graphNode['id'];
            //-wgonzalez-publicar-en-pagina-} catch (FacebookResponseException $e) {
            //-wgonzalez-publicar-en-pagina-	echo 'Error de Graph: ' . $e->getMessage();
            //-wgonzalez-publicar-en-pagina-} catch (FacebookSDKException $e) {
            //-wgonzalez-publicar-en-pagina-	echo 'Error de SDK: ' . $e->getMessage();
            //-wgonzalez-publicar-en-pagina-}


            //Crear una cuenta de negocio
            //https://www.facebook.com/business/help/169396597334438?id=2042840805783715
            //pasos para crear una merket place
            //https://developers.facebook.com/docs/commerce-platform/setup/api-setup?locale=es_ES
            //api de productos
            //https://developers.facebook.com/docs/marketing-api/reference/product-catalog/product_sets/
            //crear token en facebook
            //https://developers.facebook.com/tools/explorer/?method=POST&path=109895114143555%2Ffeed%3Fmessage%3Dhola2&version=v19.0&locale=es_ES
            //Lanzar un post en la market place de facebook en proceso
            //-wgonzalez-require_once APPPATH . 'ThirdParty/Facebook/autoload.php';
            //-wgonzalez-$fb = new Facebook([
            //-wgonzalez-	'app_id' => '1863115980872262',
            //-wgonzalez-	'app_secret' => '02678c823881c45d3f10ca3b8bcceaec',
            //-wgonzalez-	'default_graph_version' => 'v19.0',
            //-wgonzalez-]);
            //-wgonzalez-
            //-wgonzalez-$accessToken = 'EAAaefn43GkYBO0D1I6BKZBRHnoxRV6fnIUEZClkcRIuEC8TlYhrNKjqihsMlzn3TMrYRfIujv1wo6XfsR858MEHAkAANwm1es7syPVjnEg9SiL8jni1gel5cdFfWbJQh23zr8bHHBDxO90Opcc5tSikyz8cPfwAXVr8YS97Gn75gWnepRYA8FTpPASqlF0vuKOdN2pZB7AQfwULEQZDZD';
            //-wgonzalez-
            //-wgonzalez-try {
            //-wgonzalez-
            //-wgonzalez-	//$helper = $fb->getRedirectLoginHelper();
            //-wgonzalez-	//$tocken = $helper->getAccessToken();
            //-wgonzalez-
            //-wgonzalez-    // call facebook and ask for name and picture
            //-wgonzalez-    //var_dump( (string)$tocken );
            //-wgonzalez-	//$facebookResponse 	= $fb->get( '/me?fields=first_name,last_name,picture' );
            //-wgonzalez-	//$facebookUser 		= $facebookResponse->getGraphUser();
            //-wgonzalez-	//https://baulphp.com/publicar-en-facebook-desde-sitio-web-utilizando-php/
            //-wgonzalez-
            //-wgonzalez-	 // Use handler to get access token info
            //-wgonzalez-	//$oAuth2Client = $fb->getOAuth2Client();
            //-wgonzalez-	//$accessToken = $oAuth2Client->debugToken($tocken);
            //-wgonzalez-
            //-wgonzalez-
            //-wgonzalez-	// Obtener instancia del cliente de Graph API
            //-wgonzalez-	// Verificar si ya existe un conjunto de productos con los mismos filtros
            //-wgonzalez-	// $existingSets = $fb->get('/746644540896409/product_sets',$accessToken);
            //-wgonzalez-	// foreach ($existingSets->getGraphEdge() as $existingSet) {
            //-wgonzalez-	// 	if ($existingSet['filter'] == '{ "product_type": { "contains": "electronics" } }') {
            //-wgonzalez-	// 	  // Si ya existe un conjunto de productos con los mismos filtros, muestra un mensaje o realiza alguna acción
            //-wgonzalez-	// 	  echo '¡Ya existe un conjunto de productos con los mismos filtros!';
            //-wgonzalez-	//
            //-wgonzalez-	// 	}
            //-wgonzalez-	// }
            //-wgonzalez-
            //-wgonzalez-
            //-wgonzalez-	// Array de datos de productos
            //-wgonzalez-	$products = [
            //-wgonzalez-		[
            //-wgonzalez-		  'name' => 'Producto 1',
            //-wgonzalez-		  'description' => 'Descripción del producto 1',
            //-wgonzalez-		  'availability' => 'in stock',
            //-wgonzalez-		  'condition' => 'new',
            //-wgonzalez-		  'price' => '10,99',
            //-wgonzalez-		  'currency' => 'USD',
            //-wgonzalez-		  'url' => 'https://example.com/product1',
            //-wgonzalez-		  'image_url' => 'https://example.com/product1.jpg'
            //-wgonzalez-		]/*,
            //-wgonzalez-		[
            //-wgonzalez-		  'name' => 'Producto 2',
            //-wgonzalez-		  'description' => 'Descripción del producto 2',
            //-wgonzalez-		  'availability' => 'in stock',
            //-wgonzalez-		  'condition' => 'new',
            //-wgonzalez-		  'price' => '19,99',
            //-wgonzalez-		  'currency' => 'USD',
            //-wgonzalez-		  'url' => 'https://example.com/product2',
            //-wgonzalez-		  'image_url' => 'https://example.com/product2.jpg'7636920466326070
            //-wgonzalez-		],
            //-wgonzalez-		[
            //-wgonzalez-		  'name' => 'Producto 3',
            //-wgonzalez-		  'description' => 'Descripción del producto 3',
            //-wgonzalez-		  'availability' => 'in stock',
            //-wgonzalez-		  'condition' => 'new',
            //-wgonzalez-		  'price' => '29,99',
            //-wgonzalez-		  'currency' => 'USD',
            //-wgonzalez-		  'url' => 'https://example.com/product3',
            //-wgonzalez-		  'image_url' => 'https://example.com/product3.jpg'
            //-wgonzalez-		]*/
            //-wgonzalez-	];
            //-wgonzalez-
            //-wgonzalez-	// Realiza una solicitud POST para insertar los productos en el catálogo
            //-wgonzalez-	$response = $fb->post('/378489238328938/products',
            //-wgonzalez-	//array (
            //-wgonzalez-	//  'name' => 'Test Set',
            //-wgonzalez-	//  'filter' => '{"product_type":{"contains":"shirt"}}',
            //-wgonzalez-	//)
            //-wgonzalez-
            //-wgonzalez-	array (
            //-wgonzalez-		  'retailer_id' => '125',
            //-wgonzalez-		  'name' => 'Producto 1',
            //-wgonzalez-		  'description' => 'Descripción del producto 1',
            //-wgonzalez-		  'availability' => 'in stock',
            //-wgonzalez-		  'condition' => 'new',
            //-wgonzalez-		  'price' => '1099',
            //-wgonzalez-		  'currency' => 'USD',
            //-wgonzalez-		  'url' => 'https://example.com/product1',
            //-wgonzalez-		  'image_url' => 'https://example.com/product1.jpg'
            //-wgonzalez-	)
            //-wgonzalez-
            //-wgonzalez-	/*$products*/
            //-wgonzalez-	,$accessToken);
            //-wgonzalez-
            //-wgonzalez-
            //-wgonzalez-
            //-wgonzalez-	//echo 'Publicación exitosa en Marketplace: ' . $graphNode['id'];
            //-wgonzalez-} catch (FacebookResponseException $e) {
            //-wgonzalez-	echo 'Error de Graph: ' . $e->getMessage();
            //-wgonzalez-} catch (FacebookSDKException $e) {
            //-wgonzalez-	echo 'Error de SDK: ' . $e->getMessage();
            //-wgonzalez-}


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

                $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
                if ($resultPermission == PERMISSION_NONE)
                    throw new \Exception(NOT_ACCESS_FUNCTION);

            }
            //Obtener el componente Para mostrar la lista de AccountType
            $objComponent = $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if (!$objComponent)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");


            //Vista por defecto
            if ($dataViewID == null) {
                $targetComponentID = $this->session->get('company')->flavorID;
                $parameter["{companyID}"] = $this->session->get('user')->companyID;
                $dataViewData = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);

                if (!$dataViewData) {
                    $targetComponentID = 0;
                    $parameter["{companyID}"] = $this->session->get('user')->companyID;
                    $dataViewData = $this->core_web_view->getViewDefault($this->session->get('user'), $objComponent->componentID, CALLERID_LIST, $targetComponentID, $resultPermission, $parameter);
                }


				if(  $this->request->getUserAgent()->isMobile()  )
				{					
					//$dataViewRender			= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFildMobile($dataViewData,'ListView',"fnTableSelectedRow");
				}
				else
				{
					//$dataViewRender			= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreedJson($dataViewData,'ListView',"fnTableSelectedRow");
				}
				

            } //Otra vista
            else {
                $parameter["{companyID}"] = $this->session->get('user')->companyID;
                $dataViewData = $this->core_web_view->getViewBy_DataViewID($this->session->get('user'), $objComponent->componentID, $dataViewID, CALLERID_LIST, $resultPermission, $parameter);
                $dataViewRender = $this->core_web_view->renderGreedJson($dataViewData, 'ListView', "fnTableSelectedRow");
            }

            //Renderizar Resultado
			$dataView["company"] 			        = $dataSession["company"];
            $dataSession["datatable_V2_2_2"] 	    = true;
			$dataSession["jquery_V1_12_4"] 	    	= true;
            $dataSession["notification"] 	        = $this->core_web_error->get_error($dataSession["user"]->userID);
            $dataSession["message"] 		        = $this->core_web_notification->get_message();
            $dataSession["head"] 			        = /*--inicio view*/  view('app_inventory_item/list_head',$dataView);//--finview
            $dataSession["footer"] 			        = /*--inicio view*/ view('app_inventory_item/list_footer');//--finview
            $dataSession["body"] 			        = $dataViewRender;
            $dataSession["script"] 			        = /*--inicio view*/ view('app_inventory_item/list_script');//--finview
            $dataSession["script"] 			        = $dataSession["script"] . $this->core_web_javascript->createVar("componentID", $objComponent->componentID);

            return view("core_masterpage/default_masterpage", $dataSession);//--finview-r
        } catch (\Exception $ex) {
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

    function popup_add_concept()
    {

        //AUTENTICACION
        if (!$this->core_web_authentication->isAuthenticated())
            throw new \Exception(USER_NOT_AUTENTICATED);
        $dataSession = $this->session->get();

        //PERMISO SOBRE LA FUNCION
        if (APP_NEED_AUTHENTICATION == true) {
            $permited = false;
            $permited = $this->core_web_permission->urlPermited(get_class($this), "index", URL_SUFFIX, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);

            if (!$permited)
                throw new \Exception(NOT_ACCESS_CONTROL);

            $resultPermission = $this->core_web_permission->urlPermissionCmd(get_class($this), "index", URL_SUFFIX, $dataSession, $dataSession["menuTop"], $dataSession["menuLeft"], $dataSession["menuBodyReport"], $dataSession["menuBodyTop"], $dataSession["menuHiddenPopup"]);
            if ($resultPermission == PERMISSION_NONE)
                throw new \Exception(NOT_ACCESS_FUNCTION);
        }

        //Renderizar Resultado
        $dataSession["message"] = "";
        $dataSession["head"] = /*--inicio view*/
            view('app_inventory_item/popup_addconcept_head');//--finview
        $dataSession["body"] = /*--inicio view*/
            view('app_inventory_item/popup_addconcept_body');//--finview
        $dataSession["script"] = /*--inicio view*/
            view('app_inventory_item/popup_addconcept_script');//--finview
        return view("core_masterpage/default_popup", $dataSession);//--finview-r

    }


    function popup_add_renderimg($companyID = "", $componentID = "", $itemID = "")
    {
        $companyID = helper_SegmentsByIndex($this->uri->getSegments(), 1, $companyID);
        $componentID = helper_SegmentsByIndex($this->uri->getSegments(), 2, $componentID);
        $itemID = helper_SegmentsByIndex($this->uri->getSegments(), 3, $itemID);


        //Extraer el codigo de barra
        $pathFileCodeBarra = PATH_FILE_OF_APP . "/company_" . $companyID . "/component_" . $componentID . "/component_item_" . $itemID . "/barcode.jpg";


        $type = 'image/jpg';
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($pathFileCodeBarra));
        readfile($pathFileCodeBarra);
        exit;
    }

}

?>