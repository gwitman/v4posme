<?php
//posme:2023-02-27
namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use CodeIgniter\Database\Exceptions\DatabaseException;

class app_invoice_billing extends _BaseController {

    function edit($companyID, $transactionID, $transactionMasterID, $codigoMesero): ResponseInterface
    {
        $response = [
            'success' 	=> false,
            'message' 	=> '',
            'data' 		=> []
        ];
        try
        {
            //AUTENTICADO
            if(!$this->core_web_authentication->isAuthenticated())
                throw new \Exception(USER_NOT_AUTENTICATED);
            $dataSession		= $this->session->get();

            //PERMISO SOBRE LA FUNCTION  aa
            if(APP_NEED_AUTHENTICATION == true){
                $permited = false;
                $permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

                if(!$permited)
                    throw new \Exception(NOT_ACCESS_CONTROL);

                $resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
                if ($resultPermission 	== PERMISSION_NONE)
                    throw new \Exception(NOT_ALL_EDIT);

            }

            $branchID 				= $dataSession["user"]->branchID;
            $roleID 				= $dataSession["role"]->roleID;
            $userID					= $dataSession["user"]->userID;



            if((!$companyID || !$transactionID  || !$transactionMasterID))
            {
                $this->response->redirect(base_url()."/".'app_invoice_billing/add');
            }

            //Obtener el componente de Item
            $objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
            if(!$objComponentCustomer)
                throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");

            //Componente de facturacion
            $objComponentTransactionBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
            if(!$objComponentTransactionBilling)
                throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");

            //Obtener el componente de Item
            $objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
            if(!$objComponentItem)
                throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");



            $objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
            $targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);
            $objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
            $objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);


            if(!$objListPrice)
                throw new \Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");

            /** ZONAS O SALONES */
            $objPublicCatalogIdZonas					= 0;
            $objPubliCatalogZonasConfig 				= $this->Public_Catalog_Model->asObject()
                ->where("systemName","tb_transaction_master_billing.zone_x_meseros")
                ->where("isActive",1)
                ->where("flavorID",$dataSession["company"]->flavorID)
                ->find();

            if($codigoMesero != "none" && !$objPubliCatalogZonasConfig )
            {
                throw new \Exception("CONFIGURAR EL CATALOGO DE ZONAS tb_transaction_master_billing.zone_x_meseros");
            }

            $objPublicCatalogIdZonas					= $codigoMesero == "none" ? 0 : $objPubliCatalogZonasConfig[0]->publicCatalogID;
            $objPubliCatalogDetailZonasConfiguradas		= $this->Public_Catalog_Detail_Model->asObject()
                ->where("publicCatalogID",$objPublicCatalogIdZonas)
                ->where( "isActive",1)
                ->where( "name",$codigoMesero)
                ->findAll();
            /** MESAS O UBICACIONES DE ZONAS */
            $objPublicCatalogId							= 0;
            $objPubliCatalogMesasConfig 				= $this->Public_Catalog_Model->asObject()
                ->where("systemName","tb_transaction_master_billing.mesas_x_meseros")
                ->where("isActive",1)
                ->where("flavorID",$dataSession["company"]->flavorID)
                ->find();

            if($codigoMesero != "none" && !$objPubliCatalogMesasConfig )
            {
                throw new \Exception("CONFIGURAR EL CATALOGO DE MESAS tb_transaction_master_billing.mesas_x_meseros");
            }

            $objPublicCatalogId							= $codigoMesero == "none" ? 0 : $objPubliCatalogMesasConfig[0]->publicCatalogID;
            $objPubliCatalogDetailMesasConfiguradas		= $this->Public_Catalog_Detail_Model->asObject()
                ->where("publicCatalogID",$objPublicCatalogId)
                ->where( "isActive",1)
                ->where( "name",$codigoMesero)
                ->findAll();


            $objListComanyParameter					= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
            $customerDefault						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_CLIENTDEFAULT");
            $urlPrinterDocument						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_URL_PRINTER");
            $urlPrinterDocumentOpcion2				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_URL_PRINTER_OPCION2");

            $dataView["codigoMesero"]					= $codigoMesero;
            $objParameterInvoiceTypeEmployer			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_TYPE_EMPLOYEER");
            $objParameterInvoiceTypeEmployer			= $objParameterInvoiceTypeEmployer->value;

            $parameterValue 							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION");
            $dataView["objParameterInvoiceButtomPrinterFidLocalPaymentAndAmortization"] = $parameterValue->value;
            $objParameterRestaurant 									= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_IS_RESTAURANT")->value;

            $objParameterDirect 										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT")->value;
            $objParameterInvoiceBillingQuantityZero						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_QUANTITY_ZERO");
            $dataView["objParameterInvoiceBillingQuantityZero"]			= $objParameterInvoiceBillingQuantityZero->value;

            $objParameterInvoiceBillingPrinterDirect					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT");
            $dataView["objParameterInvoiceBillingPrinterDirect"]		= $objParameterInvoiceBillingPrinterDirect->value;
            $objParameterInvoiceBillingPrinterDirectUrl					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_URL");
            $dataView["objParameterInvoiceBillingPrinterDirectUrl"]		= $objParameterInvoiceBillingPrinterDirectUrl->value;

            $objParameterShowComandoDeCocina							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SHOW_COMMAND_FOOT");
            $dataView["objParameterShowComandoDeCocina"]				= $objParameterShowComandoDeCocina->value;
            $urlPrinterDocumentCocina									= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_URL_PRINTER_COCINA");
            $dataView["urlPrinterDocumentCocina"]						= $urlPrinterDocumentCocina->value;
            $urlPrinterDocumentCocinaDirect								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_URL_PRINTER_COCINA_DIRECT");
            $dataView["urlPrinterDocumentCocinaDirect"]					= $urlPrinterDocumentCocinaDirect->value;
            $objParameterImprimirPorCadaFactura							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PRINT_BY_INVOICE");
            $dataView["objParameterImprimirPorCadaFactura"]				= $objParameterImprimirPorCadaFactura->value;
            $objParameterRegresarAListaDespuesDeGuardar					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SAVE_AFTER_TO_LIST");
            $dataView["objParameterRegresarAListaDespuesDeGuardar"]		= $objParameterRegresarAListaDespuesDeGuardar->value;
            $objParameterScanerProducto									= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_SHOW_POPUP_FIND_PRODUCTO_NOT_SCANER");
            $objParameterScanerProducto									= $objParameterScanerProducto->value;
            $dataView["objParameterScanerProducto"] 					= $objParameterScanerProducto;
            $objParameterUrlServidorDeImpresion							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_SERVER_PATH");
            $objParameterUrlServidorDeImpresion							= $objParameterUrlServidorDeImpresion->value;
            $dataView["objParameterUrlServidorDeImpresion"] 			= $objParameterUrlServidorDeImpresion;

			$objParameterTipoWarehouseDespacho							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_TYPE_WAREHOUSE_DESPACHO");
			$objParameterTipoWarehouseDespacho							= $objParameterTipoWarehouseDespacho->value;
			$dataView['objParameterTipoWarehouseDespacho']				= $objParameterTipoWarehouseDespacho;
            $objParameterCantidadItemPoup								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
            $objParameterCantidadItemPoup								= $objParameterCantidadItemPoup->value;
            $dataView["objParameterCantidadItemPoup"] 					= $objParameterCantidadItemPoup;
            $objParameterHidenFiledItemNumber							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_HIDEN_ITEMNUMBER_IN_POPUP");
            $objParameterHidenFiledItemNumber							= $objParameterHidenFiledItemNumber->value;
            $dataView["objParameterHidenFiledItemNumber"] 				= $objParameterHidenFiledItemNumber;
            $objParameterEsResrarante									= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_IS_RESTAURANT");
            $objParameterEsResrarante									= $objParameterEsResrarante->value;
            $dataView["objParameterEsResrarante"] 						= $objParameterEsResrarante;
            $objParameterAmortizationDuranteFactura						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE");
            $objParameterAmortizationDuranteFactura						= $objParameterAmortizationDuranteFactura->value;
            $dataView["objParameterAmortizationDuranteFactura"] 		= $objParameterAmortizationDuranteFactura;
            $objParameterTypePreiceDefault								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_DEFAULT_TYPE_PRICE");
            $objParameterTypePreiceDefault								= $objParameterTypePreiceDefault->value;

            $objParameterAlturaDelModalDeSeleccionProducto					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_ALTO_MODAL_DE_SELECCION_DE_PRODUCTO_AL_FACTURAR");
            $objParameterAlturaDelModalDeSeleccionProducto					= $objParameterAlturaDelModalDeSeleccionProducto->value;
            $dataView["objParameterAlturaDelModalDeSeleccionProducto"] 		= $objParameterAlturaDelModalDeSeleccionProducto;

            $objParameterScrollDelModalDeSeleccionProducto					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_SCROLL_DE_MODAL_EN_SELECCION_DE_PRODUTO_AL_FACTURAR");
            $objParameterScrollDelModalDeSeleccionProducto					= $objParameterScrollDelModalDeSeleccionProducto->value;
            $dataView["objParameterScrollDelModalDeSeleccionProducto"] 		= $objParameterScrollDelModalDeSeleccionProducto;

            $objParameterMostrarImagenEnSeleccion							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SHOW_IMAGE_IN_DETAIL_SELECTION");
            $objParameterMostrarImagenEnSeleccion							= $objParameterMostrarImagenEnSeleccion->value;
            $dataView["objParameterMostrarImagenEnSeleccion"] 				= $objParameterMostrarImagenEnSeleccion;

            $objParameterPantallaParaFacturar								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PANTALLA_FACTURACION");
            $objParameterPantallaParaFacturar								= $objParameterPantallaParaFacturar->value;
            $dataView["objParameterPantallaParaFacturar"] 					= $objParameterPantallaParaFacturar;


            //Tipo de Factura
            $agent 												= $this->request->getUserAgent();
            $dataView["isMobile"]								= $dataSession["user"]->useMobile;
            $dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
            $dataView["urlPrinterDocumentOpcion2"]				= $urlPrinterDocumentOpcion2->value;
            $dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $dataView["objTransactionMasterReferences"]			= $this->Transaction_Master_References_Model->get_rowByTransactionMaster($transactionMasterID);
            $dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
            $dataView["objTransactionMasterDetailReferences"] 	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID);
            $dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
            $dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);

            $dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
            $dataView["objTransactionMaster"]->transactionOn2 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn2),"Y-m-d");
            $dataView["objTransactionMasterDetailCredit"]		= null;
            $dataView["companyID"]				= $dataSession["user"]->companyID;
            $dataView["userID"]					= $dataSession["user"]->userID;
            $dataView["userName"]				= $dataSession["user"]->nickname;
            $dataView["roleID"]					= $dataSession["role"]->roleID;
            $dataView["roleName"]				= $dataSession["role"]->name;
            $dataView["isAdmin"]				= $dataSession["role"]->isAdmin;
            $dataView["branchID"]				= $dataSession["branch"]->branchID;
            $dataView["branchName"]				= $dataSession["branch"]->name;
            $dataView["useMobile"]				= $dataSession["user"]->useMobile;
            $dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);
            $dataView["objCurrency"]			= $objCurrency;
            $dataView["company"]				= $dataSession["company"];
            $dataView["objListEmployee"]		= $this->Employee_Model->get_rowByBranchIDAndType($companyID,$branchID,  $objParameterInvoiceTypeEmployer );
            $dataView["objListBank"]			= $this->Bank_Model->getByCompany($companyID);
            $dataView["objListPrice"]			= $objListPrice;
            $dataView["objComponentBilling"]			= $objComponentTransactionBilling;
            $dataView["objComponentTransactionBilling"]	= $objComponentTransactionBilling;
            $dataView["objComponentItem"]		= $objComponentItem;
            $dataView["objComponentCustomer"]	= $objComponentCustomer;
            $dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);
            $dataView["warehouseID"]			= $dataView["objCaudal"][0]->warehouseSourceID;
            $dataView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$userID);
            $dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_billing","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
            $objListWorkflowStageNames          = [];
            foreach($dataView["objListWorkflowStage"] as $ws){
                $objListWorkflowStageNames[] = getBehavio($dataSession['company']->type,"core_web_language_workflowstage","billing_".$ws->name, $ws->name);
            }
            $dataView["objListWorkflowStageNames"] = $objListWorkflowStageNames;
            $dataView["objCustomerDefault"]		    = $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
            $dataView["objListTypePrice"]		    = $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
            $dataView["objListZone"]			    = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);
            $dataView["objListMesa"]			    = $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","mesaID",$companyID);
            $dataView['transactionID']			    = $transactionID;
            $dataView['objParameterRestaurant']	    = $objParameterRestaurant;

            if($objParameterRestaurant=='true'){
                $catalogItemIdZonas							= array_column($dataView["objListZone"],'catalogItemID');
                $dataView['objListZone']					= $this->Transaction_Master_Model->get_ZonasByCatalogItemID($catalogItemIdZonas);
                $catalogItemIdMesas							= array_column($dataView["objListMesa"],'catalogItemID');
                $dataView['objListMesa']					= $this->Transaction_Master_Model->get_MesasByCatalogItemID($catalogItemIdMesas);
            }
            /** FILTRAR LAS ZONAS O SALONES DONDEL MESERO TIENE PERMISO */
            $listZonasByMesero = array_map(function($item) {
                return $item->display;
            }, $objPubliCatalogDetailZonasConfiguradas);


            $listZonaFiltradas = array_filter($dataView["objListZone"] , function($item) use ($listZonasByMesero) {
                return in_array($item->name, $listZonasByMesero);
            });

            $dataView["objListZone"] = $codigoMesero == "none" ? $dataView["objListZone"]  : $listZonaFiltradas;
            if(!$dataView["objListZone"])
                throw new \Exception("NO ES POSIBLE CONTINUAR, CONFIGURAR CATALOGO ZONAS");

            //Filtrar la lista de mesas que el mesero tiene permiso
            $listMesasByMesero = array_map(function($item) {
                return $item->display;
            }, $objPubliCatalogDetailMesasConfiguradas);


            $listMesaFiltradas = array_filter($dataView["objListMesa"] , function($item) use ($listMesasByMesero) {
                return in_array($item->name, $listMesasByMesero);
            });

            $dataView["objListMesa"] = $codigoMesero == "none" ? $dataView["objListMesa"]  : $listMesaFiltradas;
            if(!$dataView["objListMesa"])
                throw new \Exception("NO ES POSIBLE CONTINUAR, CONFIGURAR CATALOGO MESAS");

            $mesaID = $dataView["objTransactionMasterInfo"]->mesaID;

            $listMesaFiltradas = array_filter($dataView["objListMesa"] , function($item) use ($mesaID) {
                return $item->catalogItemID == $mesaID;
            });
            if(!$listMesaFiltradas)
                throw new \Exception("NO TIENE ACCESO A LA MESA SELECCIONADA");


            $dataView["objListPay"]				= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
            $dataView["objListDayExcluded"]		= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","dayExcluded",$companyID);
            $dataView["listCurrency"]			= $objListCurrency;
            $dataView["listProvider"]			= $this->Provider_Model->get_rowByCompany($companyID);
            $dataView["objListaPermisos"]		= $dataSession["menuHiddenPopup"];
            $dataView["useMobile"]																	= $dataSession["user"]->useMobile;
            $dataView["objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE"] 						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE")->value;
            $dataView["objParameterINVOICE_OPEN_CASH_PASSWORD"] 									= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_OPEN_CASH_PASSWORD")->value;
            $dataView["objParameterCustomPopupFacturacion"]											= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_VIEW_CUSTOM_PANTALLA_DE_FACTURACION_POPUP_SELECCION_PRODUCTO_FORMA_MOSTRAR")->value;
            $dataView["objParameterTipoPrinterDonwload"]											= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DOWNLOAD")->value;
            $dataView["objParameterPrinterDirectAndPreview"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_PREVIEW_AND_DIRECT")->value;

            $dataView["objParameterINVOICE_BILLING_APPLY_TYPE_PRICE_ON_DAY_POR_MAYOR"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_APPLY_TYPE_PRICE_ON_DAY_POR_MAYOR")->value;
            $dataView["objParameterINVOICE_BILLING_SHOW_COMMAND_BAR"]								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SHOW_COMMAND_BAR")->value;
            $dataView["objParameterINVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_BAR"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_BAR")->value;
            $dataView["objParameterINVOICE_BILLING_PRINTER_DIRECT_URL_BAR"]							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_URL_BAR")->value;
            $dataView["objParameterINVOICE_BILLING_PRINTER_URL_BAR"]								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_URL_BAR")->value;
            $dataView["objParameterINVOICE_BILLING_SELECTITEM"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SELECTITEM")->value;
            //$dataView["objListParameterJavaScript"]													= $this->core_web_parameter->getParameterAllToJavaScript($companyID);
            $dataView["objParameterCXC_DAY_EXCLUDED_IN_CREDIT"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_DAY_EXCLUDED_IN_CREDIT")->value;
            $dataView["objParameterINVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE")->value;
            $dataView["objParameterINVOICE_BILLING_VALIDATE_EXONERATION"]							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_VALIDATE_EXONERATION")->value;
            $dataView["objParameterINVOICE_SHOW_FIELD_PESO"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_SHOW_FIELD_PESO")->value;

            if(!$dataView["objCustomerDefault"])
                throw new \Exception("NO EXISTE EL CLIENTE POR DEFECTO");

            $dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
            $dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);

            //Procesar Datos
            if($dataView["objTransactionMasterDetail"])
                foreach($dataView["objTransactionMasterDetail"] as $key => $value)
                {
                    $dataView["objTransactionMasterDetail"][$key]->itemName = htmlentities($value->itemName,ENT_QUOTES);
                    $dataView["objTransactionMasterDetailCredit"]			= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($value->transactionMasterDetailID);
                }



            //Obtener la linea de credito del cliente por defecto
            $objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
            $objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
            $parameterCausalTypeCredit 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_CREDIT");
            $objCustomerCreditAmoritizationAll		= $this->Customer_Credit_Amortization_Model->get_rowByCustomerID( $dataView["objTransactionMaster"]->entityID );
            $objListCustomerCreditLine 				= $this->Customer_Credit_Line_Model->get_rowByEntityBalanceMayorCero($companyID,$dataSession["user"]->branchID,$dataView["objTransactionMaster"]->entityID);
			
			

            $dataView["objListCustomerCreditLine"]	  		=  $objListCustomerCreditLine;
            $dataView["objCausalTypeCredit"]				=  $parameterCausalTypeCredit;
            $dataView["objCurrencyDolares"] 				=  $objCurrencyDolares;
            $dataView["objCurrencyCordoba"] 				=  $objCurrencyCordoba;
            $dataView["objCustomerCreditAmoritizationAll"] 	=  $objCustomerCreditAmoritizationAll;

            //Obtener los datos de precio, sku y conceptos de la transaccoin
            $dataView["objTransactionMasterItemPrice"]			= $this->Price_Model->get_rowByTransactionMasterID($companyID,$objListPrice->listPriceID, $dataView["objTransactionMaster"]->transactionMasterID );
            $dataView["objTransactionMasterItemConcepto"]		= $this->Company_Component_Concept_Model->get_rowByTransactionMasterID($companyID,$objComponentItem->componentID, $dataView["objTransactionMaster"]->transactionMasterID );
            $dataView["objTransactionMasterItemSku"]			= $this->Item_Sku_Model->get_rowByTransactionMasterID($companyID, $dataView["objTransactionMaster"]->transactionMasterID );
            $dataView["objTransactionMasterItem"]				= $this->Item_Model->get_rowByTransactionMasterID( $dataView["objTransactionMaster"]->transactionMasterID  );

            //Obtener la categoria de prodcutos y la lista de prodcutos si es restaurante
            $dataView["objListInventoryCategoryRestaurant"]	 = NULL;
            $dataView["objListInventoryItemsRestaurant"]	 = NULL;
            if($objParameterEsResrarante == "true")
            {
                $parameterView["{companyID}"]			= $this->session->get('user')->companyID;
                $parameterView["{currencyID}"] 			= $objCurrency->currencyID;
                $parameterView["{warehouseID}"]			= $dataView["warehouseID"];
                $parameterView["{listPriceID}"]			= $objListPrice->listPriceID;
                $parameterView["{useMobile}"]			= $this->session->get('user')->useMobile;
                $parameterView["{fnCallback}"] 			= "";
                $parameterView["{typePriceID}"] 		= $objParameterTypePreiceDefault;
                $parameterView["{iDisplayStartDB}"]		= "0";
                $parameterView["{iDisplayLength}"]		= $objParameterCantidadItemPoup;
                $parameterView["{sSearchDB}"]			= "";
                $parameterView["{isWindowForm}"]		= "0";




                $dataView["objListInventoryCategoryRestaurant"]  =  $this->Itemcategory_Model->getByCompany($companyID);
                $dataView["objListInventoryItemsRestaurant"]	 = 	$this->core_web_view->getViewByName(
                    $this->session->get('user'),
                    $objComponentItem->componentID,
                    "SELECCIONAR_ITEM_BILLING_POPUP_INVOICE_RESTAURANT",
                    CALLERID_SEARCH,
                    null,
                    $parameterView
                )["view_data"];


            }
			$dataView["objParameterTypePreiceDefault"] 		= $objParameterTypePreiceDefault;

            //Datos para imprimir la factura
            //------------------------------------------
            if($objParameterDirect  == "true")
            {
                $dataPostPrinter["objTransactionMaster"]					= $dataView["objTransactionMaster"];
                $dataPostPrinter["objTransactionMasterInfo"]				= $dataView["objTransactionMasterInfo"];
                $dataPostPrinter["objTransactionMasterDetail"]				= $dataView["objTransactionMasterDetail"];
                $dataPostPrinter["objTransactionMasterDetailReferences"]	= $dataView["objTransactionMasterDetailReferences"];
                $dataPostPrinter["objTransactionMasterDetailWarehouse"]		= $dataView["objTransactionMasterDetailWarehouse"];
                $dataPostPrinter["objTransactionMasterDetailConcept"]		= $dataView["objTransactionMasterDetailConcept"];
                $dataPostPrinter["objComponentCompany"]				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
                $dataPostPrinter["objParameterLogo"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_COMPANY_LOGO");
                $dataPostPrinter["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_PROPIETARY_PHONE");
                $dataPostPrinter["objCompany"] 						= $this->Company_Model->get_rowByPK($companyID);
                $dataPostPrinter["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataPostPrinter["objTransactionMaster"]->createdAt,$dataPostPrinter["objTransactionMaster"]->createdBy);
                $dataPostPrinter["Identifier"]						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_COMPANY_IDENTIFIER");
                $dataPostPrinter["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataPostPrinter["objTransactionMaster"]->branchID);
                $dataPostPrinter["objTipo"]							= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataPostPrinter["objTransactionMaster"]->transactionID,$dataPostPrinter["objTransactionMaster"]->transactionCausalID);
                $dataPostPrinter["objCustumer"]						= $this->Customer_Model->get_rowByEntity($companyID,$dataPostPrinter["objTransactionMaster"]->entityID);
                $dataPostPrinter["objCurrency"]						= $this->Currency_Model->get_rowByPK($dataPostPrinter["objTransactionMaster"]->currencyID);
                $dataPostPrinter["prefixCurrency"]					= $dataPostPrinter["objCurrency"]->simbol." ";
                $dataPostPrinter["cedulaCliente"] 					= $dataPostPrinter["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataPostPrinter["objCustumer"]->customerNumber :  $dataPostPrinter["objTransactionMasterInfo"]->referenceClientIdentifier;
                $dataPostPrinter["nombreCliente"] 					= $dataPostPrinter["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataPostPrinter["objCustumer"]->firstName : $dataPostPrinter["objTransactionMasterInfo"]->referenceClientName ;
                $dataPostPrinter["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataPostPrinter["objTransactionMaster"]->statusID);
                $dataPostPrinter["objMesa"]							= $this->Catalog_Item_Model->get_rowByCatalogItemID($dataPostPrinter["objTransactionMasterInfo"]->mesaID);
                $serializedDataPostPrinter 							= serialize($dataPostPrinter);
                $serializedDataPostPrinter 							= base64_encode($serializedDataPostPrinter);
                $dataView["dataPrinterLocal"]						= $serializedDataPostPrinter;

            }
            else
            {
                $dataView["dataPrinterLocal"]						= "";
            }

            //Variable para validar si es un mesero
            $esMesero 					        = false;
            $eliminarProductos 			    	= false;
            $esMesero 					        = $this->core_web_permission->urlPermited("es_mesero","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
            $eliminarProductos 		        	= $this->core_web_permission->urlPermited("no_permitir_eliminar_productos_de_factura","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

            $esMesero					        = !$esMesero ? "0" : $esMesero;
            $esMesero					        = $dataSession["role"]->isAdmin ? "0" : $esMesero;
            $eliminarProductos                 	= !$eliminarProductos ? "0" : $eliminarProductos;
            $eliminarProductos					= $dataSession["role"]->isAdmin ? "0" : $eliminarProductos;
            $dataView["esMesero"]	            = $esMesero;
            $dataView["eliminarProducto"]	    = $eliminarProductos;

            // Procesar fechas
            if(isset($dataView['objTransactionMaster'])) {
                $dataView['objTransactionMaster']->transactionOn = date_format(date_create($dataView['objTransactionMaster']->transactionOn),"Y-m-d");
                $dataView['objTransactionMaster']->transactionOn2 = date_format(date_create($dataView['objTransactionMaster']->transactionOn2),"Y-m-d");
            }

            // Procesar detalles de la transacción
            if(isset($dataView['objTransactionMasterDetail'])) {
                foreach($dataView['objTransactionMasterDetail'] as $key => $value) {
                    $dataView['objTransactionMasterDetail'][$key]->itemName = htmlentities($value->itemName,ENT_QUOTES);
                    $dataView['objTransactionMasterDetailCredit'] = $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($value->transactionMasterDetailID);
                }
            }

            // Configurar respuesta exitosa
            $response['success']    = true;
            $response['data']       = $dataView;

        } catch(\Exception $ex) {
            $response['message'] = $ex->getMessage();
            log_message('error', $ex->getMessage());
        }

        return $this->response->setJSON($response);
    }

	function editv2(){ 
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
			
			
			
			//Redireccionar datos
			$companyID				= $dataSession["company"]->companyID;
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
			
			$transactionMasterID	= $transactionMasterID === "" ? 0 : $transactionMasterID;
			$transactionID			= $transactionID === "" ? 0 : $transactionID;
			
			$branchID 				= $dataSession["user"]->branchID;
			$roleID 				= $dataSession["role"]->roleID;			
			$userID					= $dataSession["user"]->userID;
			
				
			
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Componente de facturacion
			$objComponentTransactionBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentTransactionBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
		
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
		
			//Obtener el componente de Item
			$objComponentItemCategory	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item_category");
			if(!$objComponentItemCategory)
			throw new \Exception("EL COMPONENTE 'tb_item_category' NO EXISTE...");
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);			
			$customerDefault					= $this->core_web_parameter->getParameter("INVOICE_BILLING_CLIENTDEFAULT",$companyID);
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			$urlPrinterDocument					= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER",$companyID);
			
			if(!$objListPrice)
			throw new \Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");
		
			
			
		
			
			$objParameterInvoiceBillingQuantityZero					= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$dataView["objParameterInvoiceBillingQuantityZero"]		= $objParameterInvoiceBillingQuantityZero->value;
			$objParameterInvoiceBillingPrinterDirect				= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirect"]	= $objParameterInvoiceBillingPrinterDirect->value;
			$objParameterInvoiceBillingPrinterDirectUrl					= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_URL",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirectUrl"]		= $objParameterInvoiceBillingPrinterDirectUrl->value;
			$objParameterInvoiceBillingPrinterDirectCocinaUrl					= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER_COCINA_DIRECT",$companyID);
			$dataView["objParameterInvoiceBillingPrinterDirectCocinaUrl"]		= $objParameterInvoiceBillingPrinterDirectCocinaUrl->value;
			
			//Tipo de Factura
			$dataView["urlPrinterDocument"]						= $urlPrinterDocument->value;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			//Formato de fecha
			if($dataView["objTransactionMaster"]){
				$dataView["objTransactionMaster"]->transactionOn 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn),"Y-m-d");
				$dataView["objTransactionMaster"]->transactionOn2 	= date_format(date_create($dataView["objTransactionMaster"]->transactionOn2),"Y-m-d");
			}
			
			$agent 												= $this->request->getUserAgent();			
			$dataView["isMobile"]								= $dataSession["user"]->useMobile;
			$dataView["widthPanelComando"]						= $dataView["isMobile"] == "0" ? "280" : "450";
			$dataView["widthPanelTeclado"]						= $dataView["isMobile"] == "0" ? "325" : "350";
			$dataView["widthPanelNueva"]						= $dataView["isMobile"] == "0" ? "280" : "210";
			$dataView["widthPanelCategoria"]					= $dataView["isMobile"] == "0" ? "350" : "420";
			$dataView["widthPanelCategoriaAndProductoPhone"]	= $dataView["isMobile"] == "0" ? "350" : "380";
			
				


			$dataView["objTransactionMasterDetailCredit"]		= null;	
			$dataView["companyID"]				= $dataSession["user"]->companyID;
			$dataView["userID"]					= $dataSession["user"]->userID;
			$dataView["userName"]				= $dataSession["user"]->nickname;
			$dataView["roleID"]					= $dataSession["role"]->roleID;
			$dataView["roleName"]				= $dataSession["role"]->name;
			$dataView["branchID"]				= $dataSession["branch"]->branchID;
			$dataView["branchName"]				= $dataSession["branch"]->name;
			$dataView["exchangeRate"]			= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
						
			$dataView["objListPrice"]				= $objListPrice;
			$dataView["objComponentBilling"]		= $objComponentTransactionBilling;
			$dataView["objComponentItem"]			= $objComponentItem;
			$dataView["objComponentItemCategory"]	= $objComponentItemCategory;
			
			$dataView["objComponentCustomer"]	= $objComponentCustomer;
			$dataView["objCaudal"]				= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["warehouseID"]			= $dataView["objCaudal"][0]->warehouseSourceID;
			$dataView["objListWarehouse"]		= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$userID);
			
			//Obtener estados
			if($dataView["objTransactionMaster"]){
				$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowStageByStageInit("tb_transaction_master_billing","statusID",$dataView["objTransactionMaster"]->statusID,$companyID,$branchID,$roleID);
				$dataView["objListWorkflowStageAll"]	= $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);				
			}
			else{				
				$dataView["objListWorkflowStage"]		= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);				
				$dataView["objListWorkflowStageAll"]	= $this->core_web_workflow->getWorkflowAllStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);				
			}
			
			
			
			//Obtener cliente por defecto
			if($dataView["objTransactionMaster"]){
				$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			}
			else{
				$dataView["objCustomerDefault"]		= $this->Customer_Model->get_rowByCode($companyID,$customerDefault->value);
			}
			
			
			
			$dataView["objListTypePrice"]		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["objListZone"]			= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);
			$dataView["listCurrency"]			= $objListCurrency;
			$dataView["listProvider"]			= $this->Provider_Model->get_rowByCompany($companyID);
			$dataView["objListaPermisos"]		= $dataSession["menuHiddenPopup"];
			
			
			if(!$dataView["objCustomerDefault"])
			throw new \Exception("NO EXISTE EL CLIENTE POR DEFECTO");
			
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			
			//Al detalle de productos escapar nombres
			if($dataView["objTransactionMasterDetail"])
			foreach($dataView["objTransactionMasterDetail"] as $key => $value)
			{
				$dataView["objTransactionMasterDetail"][$key]->itemName = htmlentities($value->itemName,ENT_QUOTES);
				$dataView["objTransactionMasterDetailCredit"]			= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($value->transactionMasterDetailID);
			}
			
			//Renderizar Resultado 			
			//$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			//$dataSession["message"]		= $this->core_web_notification->get_message();
			//$dataSession["head"]			= /*--inicio view*/ view('app_invoice_billing/edit_head',$dataView);//--finview
			//$dataSession["body"]			= /*--inicio view*/ view('app_invoice_billing/edit_body',$dataView);//--finview
			//$dataSession["script"]		= /*--inicio view*/ view('app_invoice_billing/editv2_script',$dataView);//--finview
			//$dataSession["footer"]		= "";
			$dataView["script"]				= /*--inicio view*/ view('app_invoice_billing/editv2_script',$dataView);//--finview
			
			
			return view("app_invoice_billing/editv2",$dataView);//--finview-r
			
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
			
			$objTM	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);			
			$objCustomerCredotDocument	= $this->Customer_Credit_Document_Model->get_rowByDocument($objTM->companyID,$objTM->entityID,$objTM->transactionNumber);
			
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $dataSession["user"]->userID))
			throw new \Exception(NOT_DELETE);
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE SE ELIMINADO, EL CICLO CONTABLE ESTA CERRADO");
				
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_ELIMINABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_DELETE);
		
			//Validar si la factura es de credito y esta aplicada y tiene abono			
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit						= null;
			$exisCausalInCredit						= array_search($objTM->transactionCausalID ,$causalIDTypeCredit);				
			if( 
				$this->core_web_workflow->validateWorkflowStage
				(
					"tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_APLICABLE,
					$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID
				)
				and 
				(
					$exisCausalInCredit || $exisCausalInCredit === 0
				)
				and 
				(
					$objCustomerCredotDocument->amount != $objCustomerCredotDocument->balance
				)
				and 
				(
					$objCustomerCredotDocument->balance > 1
				)
			)
			{
				throw new \Exception("Factura con abonos y balance mayor que 1");
			}
			
				
			//Si el documento esta aplicado crear el contra documento
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			{
				
				//Actualizar fecha en la transacciones oroginal
				$dataNewTM 									= array();
				$dataNewTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$dataNewTM);
				
				$transactionIDRevert = $this->core_web_parameter->getParameter("INVOICE_TRANSACTION_REVERSION_TO_BILLING",$companyID);
				$transactionIDRevert = $transactionIDRevert->value;
				$result = $this->core_web_transaction->createInverseDocumentByTransaccion($companyID,$transactionID,$transactionMasterID,$transactionIDRevert,0);
				
				
				if($exisCausalInCredit || $exisCausalInCredit === 0)
				{
				
					//Valores de tasa de cambio
					date_default_timezone_set(APP_TIMEZONE); 
					$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
					$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
					$dateOn 								= date("Y-m-d");
					$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
					$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
						
					//cancelar el documento de credito					
					$objCustomerCredotDocumentNew["statusID"]	= $this->core_web_parameter->getParameter("SHARE_DOCUMENT_ANULADO",$companyID)->value;
					$this->Customer_Credit_Document_Model->update_app_posme($objCustomerCredotDocument->customerCreditDocumentID,$objCustomerCredotDocumentNew);
					
					$amountDol									= $objCustomerCredotDocument->balance / $exchangeRate;
					$amountCor									= $objCustomerCredotDocument->balance;
					
					//aumentar el blance de la linea
					$objCustomerCreditLine						= $this->Customer_Credit_Line_Model->get_rowByPK($objCustomerCredotDocument->customerCreditLineID);
					$objCustomerCreditLineNew["balance"]		= $objCustomerCreditLine->balance + ($objCustomerCreditLine->currencyID == $objCurrencyDolares->currencyID ? $amountDol : $amountCor);
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCredotDocument->customerCreditLineID,$objCustomerCreditLineNew);
					
					//aumentar el balance de credito
					$objCustomer								= $this->Customer_Model->get_rowByEntity($objTM->companyID,$objTM->entityID);
					$objCustomerCredit							= $this->Customer_Credit_Model->get_rowByPK($objTM->companyID,$objCustomer->branchID,$objTM->entityID);
					$objCustomerCreditNew["balanceDol"]			= $objCustomerCredit->balanceDol + $amountDol;
					$this->Customer_Credit_Model->update_app_posme($objTM->companyID,$objCustomer->branchID,$objTM->entityID,$objCustomerCreditNew);
					
					return $this->response->setJSON(array(
							'error'   => false,
							'message' => SUCCESS." Factura anulada"
					));//--finjson				
				
				}
				
				return $this->response->setJSON(array(
							'error'   => false,
							'message' => SUCCESS." Factura anulada"
				));//--finjson				
				
				
			}
			else 
			{	
				//Eliminar el Registro			
				$this->Transaction_Master_Model->delete_app_posme($companyID,$transactionID,$transactionMasterID);
				$this->Transaction_Master_Detail_Model->deleteWhereTM($companyID,$transactionID,$transactionMasterID);	

				return $this->response->setJSON(array(
							'error'   => false,
							'message' => SUCCESS." Factura anulada"
				));//--finjson				
				
			}
			
			
		}
		catch(\Exception $ex){
			$this->core_web_notification->set_message(true,$ex->getLine()." ".$ex->getMessage());
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
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
			
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$companyID 								= $dataSession["user"]->companyID;
			$userID 								= $dataSession["user"]->userID;
			$transactionID 							= /*inicio get post*/ $this->request->getPost("txtTransactionID");
			$transactionMasterID					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterID");
			$objTM	 								= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$oldStatusID 							= $objTM->statusID;
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);
			
			
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
			
			//Validar Edicion por el Usuario
			if ($resultPermission 	== PERMISSION_ME && ($objTM->createdBy != $userID))
			throw new \Exception(NOT_EDIT);
			
			//Validar si el estado permite editar
			if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
			throw new \Exception(NOT_WORKFLOW_EDIT);					
			
			if($this->core_web_accounting->cycleIsCloseByDate($companyID,$objTM->transactionOn))
			throw new \Exception("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO");
			
			
			$objParameterInvoiceUpdateNameInTransactionOnly		= $this->core_web_parameter->getParameter("INVOICE_UPDATENAME_IN_TRANSACTION_ONLY",$companyID);
			$objParameterInvoiceUpdateNameInTransactionOnly		= $objParameterInvoiceUpdateNameInTransactionOnly->value;
			
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			$objParameterImprimirPorCadaFactura			= $this->core_web_parameter->getParameter("INVOICE_PRINT_BY_INVOICE",$companyID);
			$objParameterImprimirPorCadaFactura			= $objParameterImprimirPorCadaFactura->value;
			$objParameterRegrearANuevo					= $this->core_web_parameter->getParameter("INVOICE_BILLING_SAVE_AFTER_TO_ADD",$companyID);
			$objParameterRegrearANuevo					= $objParameterRegrearANuevo->value;			
			$objParameterUpdateDateAplication			= $this->core_web_parameter->getParameter("INVOICE_BILLING_UPDATE_DATE_APPLYCATION_IN_MOMENT_APLICATION",$companyID);
			$objParameterUpdateDateAplication			= $objParameterUpdateDateAplication->value;
			$objParameterEsRestaurant 					= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"INVOICE_BILLING_IS_RESTAURANT")->value;
			$objParameterINVOICE_BILLING_TRAKING_BAR	= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"INVOICE_BILLING_TRAKING_BAR")->value;
			
			//Actualizar Maestro
			$codigoMesero								= /*inicio get post*/ $this->request->getPost("txtCodigoMesero");
			$typePriceID 								= /*inicio get post*/ $this->request->getPost("txtTypePriceID");
            $varDescuento		 					    = /*inicio get post*/ $this->request->getPost("txtDescuento");
            $varPorcentajeDescuento		 		        = /*inicio get post*/ $this->request->getPost("txtPorcentajeDescuento");
			$objListPrice 								= $this->List_Price_Model->getListPriceToApply($companyID);
			$objTMNew["transactionCausalID"] 			= /*inicio get post*/ $this->request->getPost("txtCausalID");
			$objTMNew["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTMNew["transactionOn"]					= $objParameterUpdateDateAplication == "true" ? date("Y-m-d")  : $this->request->getPost("txtDate");
			$objTMNew["transactionOn2"]					= /*inicio get post*/ $this->request->getPost("txtDateFirst");//Fecha del Primer Pago, de las facturas al credito
			$objTMNew["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTMNew["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post			
			$objTMNew["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTMNew["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTMNew["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTMNew["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTMNew["reference4"] 					= is_null( $this->request->getPost("txtCustomerCreditLineID") ) ? "0" : /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post
			$objTMNew["statusID"] 						= /*inicio get post*/ $this->request->getPost("txtStatusID");
			$objTMNew["amount"] 						= 0;
			$objTMNew["currencyID"]						= /*inicio get post*/ $this->request->getPost("txtCurrencyID"); 
			$objTMNew["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTMNew["currencyID"]);
			$objTMNew["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTMNew["currencyID2"],$objTMNew["currencyID"]);
			$objTMNew["sourceWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseID");
			$objTMNew["periodPay"]						= /*inicio get post*/ $this->request->getPost("txtPeriodPay");
			$objTMNew["nextVisit"]						= /*inicio get post*/ $this->request->getPost("txtNextVisit");
			$objTMNew["numberPhone"]					= /*inicio get post*/ $this->request->getPost("txtNumberPhone");
			$objTMNew["entityIDSecondary"]				= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
			$objTMNew["dayExcluded"]					= /*inicio get post*/ $this->request->getPost("txtDayExcluded");

			//Ingresar Informacion Adicional
			$objTMInfoNew["companyID"]					= $objTM->companyID;
			$objTMInfoNew["transactionID"]				= $objTM->transactionID;
			$objTMInfoNew["transactionMasterID"]		= $transactionMasterID;
			$objTMInfoNew["zoneID"]						= /*inicio get post*/ $this->request->getPost("txtZoneID");
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["mesaID"]						= /*inicio get post*/ $this->request->getPost("txtMesaID");
			$objTMInfoNew["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			$objTMInfoNew["receiptAmountDol"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountDol"));
			$objTMInfoNew["receiptAmountPoint"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountPoint"));
			$objTMInfoNew["receiptAmountBank"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBank"));
			$objTMInfoNew["receiptAmountBankDol"]		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBankDol"));
			$objTMInfoNew["receiptAmountCardDol"]		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjetaDol"));
			$objTMInfoNew["receiptAmountCard"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjeta"));
			$objTMInfoNew["changeAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtChangeAmount"));
			
			$objTMInfoNew["receiptAmountBankReference"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBank_Reference"));
			$objTMInfoNew["receiptAmountBankDolReference"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBankDol_Reference"));
			$objTMInfoNew["receiptAmountCardBankReference"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjeta_Reference"));
			$objTMInfoNew["receiptAmountCardBankDolReference"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjetaDol_Reference"));
			
			$objTMInfoNew["receiptAmountBankID"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBank_BankID"));
			$objTMInfoNew["receiptAmountBankDolID"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBankDol_BankID"));
			$objTMInfoNew["receiptAmountCardBankID"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjeta_BankID"));
			$objTMInfoNew["receiptAmountCardBankDolID"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjetaDol_BankID"));
			$objTMInfoNew["reference1"]								= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtTMIReference1"));
			$objTMInfoNew["reference2"]								= "not_used";
			
			
			//Ingresar TransactionMaster Reference			
			$objTMReferenceNew["reference1"]				= /*inicio get post*/ $this->request->getPost("txtLayFirstLineProtocolo");
			$objTMReferenceNew["reference2"]				= is_null( /*inicio get post*/ $this->request->getPost("txtCheckApplyExoneracionValue")) ? "0" : /*inicio get post*/ $this->request->getPost("txtCheckApplyExoneracionValue") ;
			
			
			$db=db_connect();
			$db->transException(true)->transStart();
			
			//El Estado solo permite editar el workflow
			if($this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM->statusID,COMMAND_EDITABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				$objTMNew								= array();
				$objTMNew["statusID"] 					= /*inicio get post*/ $this->request->getPost("txtStatusID");						
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			}
			else{
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
				$this->Transaction_Master_Info_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
				$this->Transaction_Master_References_Model->update_app_posme_by_transactionMasterID($transactionMasterID,$objTMReferenceNew);
			}
			
			
			
			//Leer archivo
			$path 		= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			$path 		= $path.'/procesar.csv';
			$pathNew 	= PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			$pathNew 	= $pathNew.'/procesado.csv';
					
			
			
			if (file_exists($path))
			{
				//Actualizar Detalle
				$listTransactionDetalID 					= array();
				$arrayListItemID 							= array();
				$arrayListItemName							= array();
				$arrayListItemNameDescription				= array();
				$arrayListQuantity	 						= array();
				$arrayListPrice		 						= array();
				$arrayListSubTotal	 						= array();
				$arrayListIva		 						= array();
				$arrayListTaxServices						= array();
				$arrayListLote	 							= array();
				$arrayListVencimiento						= array();
				$arrayListIdSku								= array();
				$arrayListSkuDescription					= array();
				$arrayListSkuRatio							= array();
				$arrayListSkuQuantity						= array();
				$arrayListSkuFormatoDescription				= array();
				$arrayListSku								= array();
				$arrayListSkuFormatoDescription 			= array();
				$arrayListInfoSales			                = array();
				$arrayListInfoSerie 			            = array();
                $arrayListInfoReferencia 			        = array();
				$arrayListDiscount		 			        = array();
                $arrayListInfoPrecio1				        = array();
                $arrayListInfoPrecio2				        = array();
                $arrayListInfoPrecio3				        = array();
				$arrayListCommisionBank						= array();

				$objParameterDeliminterCsv	= $this->core_web_parameter->getParameter("CORE_CSV_SPLIT",$companyID);
				$characterSplie = $objParameterDeliminterCsv->value;
				
				//Obtener los registro del archivo
				$this->csvreader->separator = $characterSplie;
				$table 			= $this->csvreader->parse_file($path); 
				
				
				rename($path,$pathNew);
				$fila 			= 0;
				if($table)
				foreach ($table as $row) 
				{	
					$fila++;
					$codigo 		= $row["Codigo"];
					$description 	= $row["Nombre"];
					$cantidad 		= $row["Cantidad"];
					$precio 		= $row["Precio"];											
					$objItem		= $this->Item_Model->get_rowByCode($companyID,$codigo);
					
					array_push($listTransactionDetalID, 0);
					array_push($arrayListItemID, $objItem->itemID);
					array_push($arrayListItemName, $objItem->name);
					array_push($arrayListItemNameDescription, $objItem->name);					
					array_push($arrayListQuantity, $cantidad);
					array_push($arrayListPrice, $precio);
					//$arrayListSubTotal		= SUB TOTAL ES UN SOLO NUMERO
					//$arrayListIva		 		= IVA ES UN SOLO NUMERO POR QUE ES EL TOTAL
					array_push($arrayListLote, '');
					array_push($arrayListVencimiento, '');

					array_push($arrayListSku,0);
					array_push($arrayListIdSku,0);
					array_push($arrayListSkuDescription,0);
					array_push($arrayListSkuRatio,0);
					array_push($arrayListSkuQuantity,0);
					array_push($arrayListDiscount,0);
					array_push($arrayListCommisionBank,0);
				
					array_push($arrayListSkuFormatoDescription,'');
					array_push($arrayListInfoSales,'');
					array_push($arrayListInfoSerie,'');
					array_push($arrayListInfoReferencia,'');
					array_push($arrayListInfoPrecio1,'');
					array_push($arrayListInfoPrecio2,'');
					array_push($arrayListInfoPrecio3,'');

				}
			}
			else{
				//Actualizar Detalle
				$listTransactionDetalID 					= /*inicio get post*/ $this->request->getPost("txtTransactionMasterDetailID");
				$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtItemID");
				$arrayListItemName 							= /*inicio get post*/ $this->request->getPost("txtTransactionDetailName");
				$arrayListItemNameDescription				= /*inicio get post*/ $this->request->getPost("txtTransactionDetailNameDescription");
				$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtQuantity");
				$arrayListPrice		 						= /*inicio get post*/ $this->request->getPost("txtPrice");
				$arrayListSubTotal	 						= /*inicio get post*/ $this->request->getPost("txtSubTotal");
				$arrayListIva		 						= /*inicio get post*/ $this->request->getPost("txtIva");
				$arrayListTaxServices 						= /*inicio get post*/ $this->request->getPost("txtTaxServices");
				$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");			
				$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");
				$arrayListIdSku								= /*inicio get post*/ $this->request->getPost("txtCatalogItemIDSku");
				$arrayListSkuDescription					= /*inicio get post*/ $this->request->getPost("txtSku");
				$arrayListSkuRatio							= /*inicio get post*/ $this->request->getPost("txtRatioSku");
				$arrayListDiscount							= /*inicio get post*/ $this->request->getPost("txtDiscountByItem");				
				$arrayListCommisionBank						= /*inicio get post*/ $this->request->getPost("txtCommisionByBankByItem");		
				$arrayListSkuQuantity						= /*inicio get post*/ $this->request->getPost("skuQuantityBySku");
				$arrayListSkuFormatoDescription				= /*inicio get post*/ $this->request->getPost("skuFormatoDescription");
                $arrayListInfoSales				            = /*inicio get post*/ $this->request->getPost("txtInfoVendedor");
                $arrayListInfoSerie				            = /*inicio get post*/ $this->request->getPost("txtInfoSerie");
                $arrayListInfoReferencia				    = /*inicio get post*/ $this->request->getPost("txtInfoReferencia");
                $arrayListInfoPrecio1				        = /*inicio get post*/ $this->request->getPost("txtItemPrecio1");
                $arrayListInfoPrecio2				        = /*inicio get post*/ $this->request->getPost("txtItemPrecio2");
                $arrayListInfoPrecio3				        = /*inicio get post*/ $this->request->getPost("txtItemPrecio3");
				
			}
			
			//Ingresar la configuracion de precios			
			$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 	= $objParameterPriceDefault->value;
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			
			$objParameterUpdatePrice	= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 			= $objParameterUpdatePrice->value;
			
			$objParameterAmortizationDuranteFactura	= $this->core_web_parameter->getParameter("INVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE",$companyID);
			$objParameterAmortizationDuranteFactura = $objParameterAmortizationDuranteFactura->value;
			
							
			
			
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$tax2Total										= 0;
			$subAmountTotal									= 0;			
			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTransactionDetalID);
			$this->Transaction_Master_Detail_Credit_Model->deleteWhereIDNotIn($transactionMasterID,$listTransactionDetalID);
			
			if( $objParameterINVOICE_BILLING_TRAKING_BAR == "true")
			$this->Transaction_Master_Detail_References_Model->deleteWhereIDNotIn($listTransactionDetalID);
			
			
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){			
					$itemID 								= $value;
					$lote 									= is_null($arrayListLote) ? "": $arrayListLote[$key];
					$vencimiento							= is_null($arrayListVencimiento) ? "" : $arrayListVencimiento[$key];
					$warehouseID 							= $objTMNew["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);
					$objItemWarehouse 						= $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);					
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					$unitaryCost							= $objItem->cost;
					
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");
					$objCompanyComponentConceptTaxServices	= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"TAX_SERVICES");
					$itemNameDetail							= str_replace('"',"",str_replace("'","",$arrayListItemName[$key]));
					$itemNameDetailDecription				= str_replace('"',"",str_replace("'","",$arrayListItemNameDescription[$key]));
					
					$skuCatalogItemID						= $arrayListIdSku[$key];
					$skuQuantityBySku						= $arrayListSkuQuantity[$key];
					$skuRatio								= $arrayListSkuRatio[$key];
					$discount								= $arrayListDiscount[$key];
					$tax3									= $arrayListCommisionBank[$key];
					
					$skuFormatoDescription					= $arrayListSkuFormatoDescription[$key];
					$objItemSku								= $this->Item_Sku_Model->getByPK($itemID,$skuCatalogItemID);
					$price 									= $arrayListPrice[$key];
					
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );	
					$taxServicesPorcentage					= ($objCompanyComponentConceptTaxServices != null ? $objCompanyComponentConceptTaxServices->valueOut : 0 );						
					$ivaPercentage 							= $objTMReferenceNew["reference2"] == "0" ? $ivaPercentage : 0;
					$taxServicesPorcentage 					= $objTMReferenceNew["reference2"] == "0" ? $taxServicesPorcentage : 0;
					
					$unitaryAmount 							= $price * (1 + $ivaPercentage);					
					$tax1 									= $price * $ivaPercentage;
					$tax2									= $price * $taxServicesPorcentage;
					$transactionMasterDetailID				= $listTransactionDetalID[$key];
					$comisionPorcentage						= 0;
					$comisionPorcentage						= $this->core_web_transaction_master_detail->getPorcentageComision($companyID,$listPriceID,$itemID,$price);
					$unitaryCost							= $this->core_web_transaction_master_detail->getCostCustomer($companyID,$itemID,$unitaryCost,$price);

                    //información del producto
                    $infoSales				                = $arrayListInfoSales[$key];
                    $infoSerie				                = $arrayListInfoSerie[$key];
                    $infoReferencia				            = $arrayListInfoReferencia[$key];
                    $infoPrecio1				            = $arrayListInfoPrecio1[$key];
                    $infoPrecio2				            = $arrayListInfoPrecio2[$key];
                    $infoPrecio3				            = $arrayListInfoPrecio3[$key];

					//Actualisar nombre 		
					if( $objParameterInvoiceUpdateNameInTransactionOnly  == "false")
					{
						
						$objItemNew 			= array();
						$objItemNew["name"] 	= rtrim(ltrim($itemNameDetail));
						$this->Item_Model->update_app_posme($companyID,$itemID,$objItemNew);
						
						if( strpos($itemNameDetail ,"NC.") > 0 )
						{
							$objItemNew 			= array();
							$objItemNew["name"] 	= rtrim(ltrim(explode("NC.",$itemNameDetail)[0]));
							$objItemNew["barcode"] 	= $objItem->barCode.",". rtrim(ltrim(explode("NC.",	$itemNameDetail)[1]	));
							$itemNameDetail			= $objItemNew["name"];
							$this->Item_Model->update_app_posme($companyID,$itemID,$objItemNew);
						}
							
						
						if( strpos($itemNameDetail ,"CC.") > 0 )
						{
							$objItemNew 			= array();
							$objItemNew["name"] 	= rtrim(ltrim(explode("CC.",$itemNameDetail)[0]));
							$objItemNew["barcode"] 	= rtrim(ltrim(explode("CC.",$itemNameDetail)[1]));
							$itemNameDetail			= $objItemNew["name"];
							$this->Item_Model->update_app_posme($companyID,$itemID,$objItemNew);						
						}
						

					}
					
					
					
					
					
					//Validar Cantidades
					$messageException = "La cantidad de '".$objItem->itemNumber. " " .$objItem->name."' es mayor que la disponible en bodega";
					$messageException = $messageException.", en bodega existen ".$objItemWarehouse->quantity." y esta solicitando : ".$quantity;
					if(
						$objItemWarehouse->quantity < $quantity  
						&& 
						$objItem->isInvoiceQuantityZero == 0
						&&
						$objParameterInvoiceBillingQuantityZero == "false"
					)					
					throw new \Exception($messageException);
								

								
					//Nuevo Detalle
					if($transactionMasterDetailID == 0){	
						
						$objTMD 								= NULL;
						$objTMD["companyID"] 					= $objTM->companyID;
						$objTMD["transactionID"] 				= $objTM->transactionID;
						$objTMD["transactionMasterID"] 			= $transactionMasterID;
						$objTMD["componentID"]					= $objComponentItem->componentID;
						$objTMD["componentItemID"] 				= $itemID;
						
						$objTMD["quantity"] 					= $quantity;	//cantidad
						$objTMD["skuQuantity"] 					= $skuRatio;						//cantidad
						$objTMD["skuQuantityBySku"]				= $skuQuantityBySku;				//cantidad
					
						
						$objTMD["unitaryCost"]					= $unitaryCost;								//costo
						$objTMD["cost"] 						= $objTMD["quantity"]  * $unitaryCost;		//costo por unidad
						
						$objTMD["unitaryPrice"]					= $price;												//precio de lista
						$objTMD["unitaryAmount"]				= $unitaryAmount;										//precio de lista con inpuesto
						$objTMD["tax1"]							= $tax1;												//impuesto de lista
						$objTMD["tax2"]							= $tax2;												//impuesto de servicio
						$objTMD["tax3"]							= $tax3;
						$objTMD["amount"] 						= $objTMD["quantity"] * ($unitaryAmount + $tax2);		//precio de lista con inpuesto por cantidad
						$objTMD["discount"]						= $discount;					
						$objTMD["promotionID"] 					= 0;
						
						$objTMD["reference1"]					= $lote;
						$objTMD["reference2"]					= $vencimiento;
						$objTMD["reference3"]					= '0';
						$objTMD["itemNameLog"] 					= $itemNameDetail;
						$objTMD["itemNameDescriptionLog"] 		= $itemNameDetailDecription;
						
						
						$objTMD["catalogStatusID"]				= 0;
						$objTMD["inventoryStatusID"]			= 0;
						$objTMD["isActive"]						= 1;
						$objTMD["quantityStock"]				= 0;
						$objTMD["quantiryStockInTraffic"]		= 0;
						$objTMD["quantityStockUnaswared"]		= 0;
						$objTMD["remaingStock"]					= 0;
						$objTMD["expirationDate"]				= NULL;
						$objTMD["inventoryWarehouseSourceID"]	= $objTMNew["sourceWarehouseID"];
						$objTMD["inventoryWarehouseTargetID"]	= $objTM->targetWarehouseID;
						$objTMD["skuCatalogItemID"] 			= $skuCatalogItemID;
						$objTMD["skuFormatoDescription"] 		= $skuFormatoDescription;
						$objTMD["amountCommision"] 				= $price * $comisionPorcentage * $quantity ;
						
						
						$tax1Total								= $tax1Total + ($tax1 * $quantity);
						$tax2Total								= $tax2Total + ($tax2 * $quantity);
						$subAmountTotal							= $subAmountTotal + ($quantity * $price);
						$amountTotal							= $amountTotal + $objTMD["amount"];
						$transactionMasterDetailID_				= $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
						$objTMDC								= NULL;
						$objTMDC["transactionMasterID"]			= $transactionMasterID;
						$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID_;
						$objTMDC["reference1"]					= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
						$objTMDC["reference2"]					= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgoValue");
						$objTMDC["reference3"]					= "";
						$objTMDC["reference4"]					= "";
						$objTMDC["reference5"]					= "";
						$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos fijos para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";						
						$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
						
						//Actualizar el Precio
						if($objUpdatePrice == "true" )
						{							
							$typePriceID					= $typePriceID;
							$dataUpdatePrice["price"] 		= $price;
							$dataUpdatePrice["percentage"] 	= 
															$unitaryCost == 0 ? 
																($price / 100) : 
																(((100 * $price) / $unitaryCost ) - 100);																		
							
							$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
							
						}

                        $objTMDRNew["isActive"] 					= 1;
                        $objTMDRNew["createdOn"] 					= date("Y-m-d H:m:s");
                        $objTMDRNew["quantity"] 					= $objTMD["quantity"];
                        $objTMDRNew["componentID"] 					= $objTMD["componentID"];
                        $objTMDRNew["componentItemID"]				= $objTMD["componentItemID"];
                        $objTMDRNew["transactionMasterDetailID"]	= $transactionMasterDetailID_;
                        $objTMDRNew["sales"]	                    = $infoSales;
                        $objTMDRNew["reference1"]	                = $infoSerie;
                        $objTMDRNew["reference2"]	                = $infoReferencia;
                        $objTMDRNew["precio1"]	                    = $infoPrecio1;
                        $objTMDRNew["precio2"]	                    = $infoPrecio2;
                        $objTMDRNew["precio3"]	                    = $infoPrecio3;
                        $this->Transaction_Master_Detail_References_Model->insert_app_posme($objTMDRNew);
						
					}					
					//Editar Detalle
					else{
						
						$objTMDC  								= $this->Transaction_Master_Detail_Credit_Model->get_rowByPK($transactionMasterDetailID);
						$objTMDC								= NULL;
						
						$objTMDNew 								= null;
						
						$objTMDNew["quantity"] 					= $quantity;	//cantidad
						$objTMDNew["skuQuantity"] 				= $skuRatio;						//cantidad
						$objTMDNew["skuQuantityBySku"]			= $skuQuantityBySku;				//cantidad
					
						
						$objTMDNew["unitaryCost"]				= $unitaryCost;								//costo
						$objTMDNew["cost"] 						= $objTMDNew["quantity"]  * $unitaryCost;	//costo por cantidad
						
						$objTMDNew["discount"]					= $discount;
						$objTMDNew["unitaryPrice"]				= $price;						//precio de lista
						$objTMDNew["unitaryAmount"]				= $unitaryAmount;				//precio de lista con inpuesto
						$objTMDNew["tax1"]						= $tax1;						//impuesto de lista
						$objTMDNew["tax2"]						= $tax2;						//impuesto de servicio
						$objTMDNew["tax3"]						= $tax3;
						$objTMDNew["amount"] 					= $objTMDNew["quantity"]  * ($unitaryAmount + $tax2);	//precio de lista con inpuesto por cantidad
						
						$objTMDNew["reference1"]				= $lote;
						$objTMDNew["reference2"]				= $vencimiento;
						$objTMDNew["reference3"]				= '0';
						$objTMDNew["itemNameLog"] 				= $itemNameDetail;
						$objTMDNew["itemNameDescriptionLog"] 	= $itemNameDetailDecription;
						$objTMDNew["inventoryWarehouseSourceID"]= $objTMNew["sourceWarehouseID"];
						$objTMDNew["skuCatalogItemID"] 			= $skuCatalogItemID;
						$objTMDNew["skuFormatoDescription"] 	= $skuFormatoDescription;						
						$objTMDNew["amountCommision"] 			= $price * $comisionPorcentage * $quantity;
						
						$tax1Total								= $tax1Total + ($tax1 * $quantity);
						$tax2Total								= $tax2Total + ($tax2 * $quantity);
						$subAmountTotal							= $subAmountTotal + ($quantity * $price);
						$amountTotal							= $amountTotal + $objTMDNew["amount"];		
						$objTMDOld								= $this->Transaction_Master_Detail_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objComponentItem->componentID);						
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMDNew);	
						
						$objTMDC["reference1"]					= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
						$objTMDC["reference2"]					= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgoValue");
						$objTMDC["reference3"]					= "";
						$objTMDC["reference4"]					= "";
						$objTMDC["reference5"]					= "";
						$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos Fijos para las Facturas de Credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";
						$this->Transaction_Master_Detail_Credit_Model->update_app_posme($transactionMasterDetailID,$objTMDC);
						
						//Actualizar el Precio
						if($objUpdatePrice == "true" )
						{
							
							$typePriceID					= $typePriceID;
							$dataUpdatePrice["price"] 		= $price;
							$dataUpdatePrice["percentage"] 	= 
															$unitaryCost == 0 ? 
																($price / 100) : 
																(((100 * $price) / $unitaryCost ) - 100);
							
							$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
							
						}

                        /*
						$quantityRestaranteTraking                  = $objTMDNew["quantity"];
                        if ( 
							$objParameterINVOICE_BILLING_TRAKING_BAR == "true" && 
							$objTMDNew["quantity"] > $objTMDOld->quantity 
						)
                        {
                            $quantityRestaranteTraking 				= $objTMDNew["quantity"] - $objTMDOld->quantity;
                        }else{
                            $quantityRestaranteTraking              = $objTMDNew["quantity"];
                        }
						*/
                        $objTMDROld                                 = $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterDetailID($transactionMasterDetailID);
                        $objTMDRNew["isActive"] 					= 1;
                        $objTMDRNew["createdOn"] 					= date("Y-m-d H:m:s");
                        $objTMDRNew["quantity"] 					= $objTMDNew["quantity"];;
                        $objTMDRNew["componentID"] 					= $objComponentItem->componentID;
                        $objTMDRNew["componentItemID"]				= $itemID;
                        $objTMDRNew["transactionMasterDetailID"]	= $transactionMasterDetailID;
                        $objTMDRNew["sales"]	                    = $infoSales;
                        $objTMDRNew["reference1"]	                = $infoSerie;
                        $objTMDRNew["reference2"]	                = $infoReferencia;
                        $objTMDRNew["precio1"]	                    = $infoPrecio1;
                        $objTMDRNew["precio2"]	                    = $infoPrecio2;
                        $objTMDRNew["precio3"]	                    = $infoPrecio3;
                        if($objTMDROld){
                            $this->Transaction_Master_Detail_References_Model->update_byTransactionMasterDetailID_app_posme($transactionMasterDetailID, $objTMDRNew);
                        }else{
                            $this->Transaction_Master_Detail_References_Model->insert_app_posme($objTMDRNew);
                        }

					}
					
				}
			}			
			
			//Actualizar Transaccion
			$amountTotal 			= $amountTotal-$varDescuento;
            $objTMNew["subAmount"]  = $subAmountTotal;
            $objTMNew["tax1"] 		= $tax1Total;
			$objTMNew["tax2"] 		= $tax2Total;
            $objTMNew["tax4"] 		= $varPorcentajeDescuento;
            $objTMNew["discount"]   = $varDescuento;
            $objTMNew["amount"] 	= $amountTotal;

			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew);
			
			
			
			
			//Aplicar el Documento?
			if( 
				$this->core_web_workflow->validateWorkflowStage
				(
					"tb_transaction_master_billing",
					"statusID",
					$objTMNew["statusID"],
					COMMAND_APLICABLE,
					$dataSession["user"]->companyID,
					$dataSession["user"]->branchID,
					$dataSession["role"]->roleID
				) &&  
				$oldStatusID != $objTMNew["statusID"] 
			){
				
				//Actualizar el numero de factura
				$ratioPont					= $this->core_web_parameter->getParameter("INVOICE_RATIO_OF_POINT_BY_BILLING",$companyID);
				$ratioPont 					= $ratioPont->value;
				$sendWhatappByPoint			= $this->core_web_parameter->getParameter("INVOICE_SEND_WHATAPP_BY_POINT",$companyID);
				$sendWhatappByPoint 		= $sendWhatappByPoint->value;
				$sendWhatappTemplate		= $this->core_web_parameter->getParameter("INVOICE_SEND_WHATAPP_BY_POINT_TEMPLATE",$companyID);
				$sendWhatappTemplate 		= $sendWhatappTemplate->value;
				
				
			
				$objTMNew003["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->locationID,"tb_transaction_master_billing",0);
				$objTMNew003["createdOn"]						= date("Y-m-d H:m:s");
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew003);
				
				//Guardar los puntos iniciales
				$objCustomer 						= $this->Customer_Model->get_rowByEntity($companyID, $objTMNew["entityID"] );				
				$objTMReferenceNew					= null;
				$objTMReferenceNew["reference3"]	= $objCustomer->balancePoint;
				$this->Transaction_Master_References_Model->update_app_posme_by_transactionMasterID($transactionMasterID,$objTMReferenceNew);
				
				//Acumular punto del cliente.
				if($objTMNew["currencyID"]  == $objCurrencyCordoba->currencyID )
				{
					$objCustomer 					= $this->Customer_Model->get_rowByEntity($companyID, $objTMNew["entityID"] );
					$objNatural						= $this->Natural_Model->get_rowByPK($companyID,$dataSession["user"]->branchID,$objTMNew["entityID"]);					
										
					//Reglas unicamente para FarmaLey con respecto al calculo de los Puntos.(maz)					
					if($dataSession["company"]->type == "farma_ley" )
					{
						if($varDescuento == 0)
						{							
							$objCustomerNew["balancePoint"]	= $objCustomer->balancePoint + (($amountTotal - $objTMInfoNew["receiptAmountPoint"]) * $ratioPont);
						}
						else 
						{
							$objCustomerNew["balancePoint"]	= $objCustomer->balancePoint;
						}							
					}
					else 
					{
						$objCustomerNew["balancePoint"]	= $objCustomer->balancePoint + (($amountTotal - $objTMInfoNew["receiptAmountPoint"]) * $ratioPont);
					}
					$this->Customer_Model->update_app_posme($objCustomer->companyID,$objCustomer->branchID,$objCustomer->entityID,$objCustomerNew);
					
					
				}
				
				
				//Es pago con punto restar puntos
				if($objTMInfoNew["receiptAmountPoint"] > 0 && $objTMNew["currencyID"]  ==  $objCurrencyCordoba->currencyID )
				{
					$objCustomer 					= $this->Customer_Model->get_rowByEntity($companyID, $objTMNew["entityID"] );					
					//Validar si existe los suficiente puntos para pagar
					if( $objCustomer->balancePoint <  $objTMInfoNew["receiptAmountPoint"])
					{
						throw new \Exception("Su balance en punto es : PT ". number_format( $objCustomer->balancePoint,2) ." , no se puede aplicar la factura." );
					}
					$objCustomerNew["balancePoint"]	= $objCustomer->balancePoint - $objTMInfoNew["receiptAmountPoint"];
					$this->Customer_Model->update_app_posme($objCustomer->companyID,$objCustomer->branchID,$objCustomer->entityID,$objCustomerNew);
				}
				
				
				//Obtener los puntos finales
				$objCustomer 						= $this->Customer_Model->get_rowByEntity($companyID, $objTMNew["entityID"] );
				$objTMReferenceNew					= null;
				$objTMReferenceNew["refernece4"]	= $objCustomer->balancePoint;
				$this->Transaction_Master_References_Model->update_app_posme_by_transactionMasterID($transactionMasterID,$objTMReferenceNew);
				
				
				//Enviar whatapp de puntos
				if($sendWhatappByPoint == "true" && $dataSession["company"]->type == "farma_ley" )
				{	
					$amountPoint			= $objCustomer->balancePoint / $ratioPont;
					$amountPoint			= number_format($amountPoint , 2);
					$phoneDestino 			= $objCustomer->phoneNumber;
					$phoneDestino 			= clearNumero($phoneDestino);
					$sendWhatappTemplate 	= str_replace("{firstName}", $objNatural->firstName, $sendWhatappTemplate);
					$sendWhatappTemplate 	= str_replace("{amount}",  " ".$amountPoint." pt ", $sendWhatappTemplate);
					$sendWhatappTemplate 	= replaceSimbol($sendWhatappTemplate);
					$this->core_web_whatsap->sendMessageByWaapi($companyID, $sendWhatappTemplate, $phoneDestino);
				}
				
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
				//Si es al credito crear tabla de amortizacion
				$causalIDTypeCredit 	= explode(",", $parameterCausalTypeCredit->value);
				$exisCausalInCredit		= null;
				$exisCausalInCredit		= array_search($objTMNew["transactionCausalID"] ,$causalIDTypeCredit);
				
				//si la factura es de credito
				if($exisCausalInCredit || $exisCausalInCredit === 0){
					
					
					//Crear documento del modulo
					$objCustomerCreditLine 								= $this->Customer_Credit_Line_Model->get_rowByPK($objTMNew["reference4"]);
					$objCustomerCreditDocument["companyID"] 			= $companyID;
					$objCustomerCreditDocument["entityID"] 				= $objCustomerCreditLine->entityID;
					$objCustomerCreditDocument["customerCreditLineID"] 	= $objCustomerCreditLine->customerCreditLineID;
					$objCustomerCreditDocument["documentNumber"] 		= $objTMNew003["transactionNumber"];
					$objCustomerCreditDocument["dateOn"] 				= $objTMNew["transactionOn"];
					$objCustomerCreditDocument["exchangeRate"] 			= $objTMNew["exchangeRate"];
					$objCustomerCreditDocument["interes"] 				= $objCustomerCreditLine->interestYear;
					
					$objCustomerCreditDocument["term"] 					= $objCustomerCreditLine->term;
					$objCustomerCreditDocument["amount"] 				= $amountTotal; 
					$objCustomerCreditDocument["balance"] 				= $amountTotal;
					$objCatalogItemDayExclude 							= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->dayExcluded);
				
					if($objParameterAmortizationDuranteFactura == "true" &&  $objTMNew["currencyID"] == 1 /*cordoba*/)
					{
						
						
						$objCustomerCreditDocument["term"] 					= $objTMNew["reference2"];
						$objCustomerCreditDocument["interes"] 				= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
						$objCustomerCreditDocument["amount"] 				= 	$amountTotal - 
																				$objTMInfoNew["receiptAmountPoint"] - 
																				
																				$objTMInfoNew["receiptAmount"] - 
																				$objTMInfoNew["receiptAmountBank"] - 																				
																				$objTMInfoNew["receiptAmountCard"] - 
																				
																				round(($objTMInfoNew["receiptAmountBankDol"] / $objTMNew["exchangeRate"]),2) - 
																				round(($objTMInfoNew["receiptAmountCardDol"] / $objTMNew["exchangeRate"]),2) - 																			
																				round(($objTMInfoNew["receiptAmountDol"] / $objTMNew["exchangeRate"]),2)  ;
																				
						$objCustomerCreditDocument["balance"] 				= $objCustomerCreditDocument["amount"];						
					}
					
					if($objParameterAmortizationDuranteFactura == "true" &&  $objTMNew["currencyID"] == 2 /*dolares*/)
					{
						$objCustomerCreditDocument["term"] 					= $objTMNew["reference2"];
						$objCustomerCreditDocument["interes"] 				= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
						$objCustomerCreditDocument["amount"] 				= 	$amountTotal - 
																				$objTMInfoNew["receiptAmountPoint"] - 
																				
																				$objTMInfoNew["receiptAmount"] - 
																				$objTMInfoNew["receiptAmountBank"] - 																				
																				$objTMInfoNew["receiptAmountCard"] - 
																				
																				round(($objTMInfoNew["receiptAmountBankDol"] / $objTMNew["exchangeRate"]),2) - 
																				round(($objTMInfoNew["receiptAmountCardDol"] / $objTMNew["exchangeRate"]),2) - 																			
																				round(($objTMInfoNew["receiptAmountDol"] / $objTMNew["exchangeRate"]),2)  ;
																				
						$objCustomerCreditDocument["balance"] 				= $objCustomerCreditDocument["amount"];
					}
					
					
					$objCustomerCreditDocument["currencyID"] 			= $objTMNew["currencyID"];					
					$objCustomerCreditDocument["statusID"] 				= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_document","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
					$objCustomerCreditDocument["reference1"] 			= $objTMNew["note"];
					$objCustomerCreditDocument["reference2"] 			= "";
					$objCustomerCreditDocument["reference3"] 			= "";
					$objCustomerCreditDocument["isActive"] 				= 1;
					
					$objCustomerCreditDocument["providerIDCredit"] 		= $objTMNew["reference1"];					
					$objCustomerCreditDocument["periodPay"]				= $objCustomerCreditLine->periodPay;
					
					if($objParameterAmortizationDuranteFactura == "true")
					{
						$objCustomerCreditDocument["periodPay"]			= $objTMNew["periodPay"];
						$objCatalogItemDayExclude 						= $this->Catalog_Item_Model->get_rowByCatalogItemID($objTMNew["dayExcluded"]);
					}
					
					$objCustomerCreditDocument["typeAmortization"] 		= $objCustomerCreditLine->typeAmortization;					
					$objCustomerCreditDocument["reportSinRiesgo"] 	 	= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgoValue");
					$customerCreditDocumentID 							= $this->Customer_Credit_Document_Model->insert_app_posme($objCustomerCreditDocument);
					$periodPay 											= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);
					
					if($objParameterAmortizationDuranteFactura == "true")
					{
						$periodPay 										= $this->Catalog_Item_Model->get_rowByCatalogItemID( $objTMNew["periodPay"] );
					}
					
					
					$objCatalogItem_DiasNoCobrables 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES",$companyID);
					$objCatalogItem_DiasFeriados365 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_365",$companyID);
					$objCatalogItem_DiasFeriados366 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_366",$companyID);
						
					
					
					//Crear tabla de amortizacion
					$this->financial_amort->amort(
						$objCustomerCreditDocument["amount"], 		/*monto*/
						$objCustomerCreditDocument["interes"],		/*interes anual*/
						$objCustomerCreditDocument["term"],			/*numero de pagos*/	
						$periodPay->sequence,						/*frecuencia de pago en dia*/
						$objTMNew["transactionOn2"], 				/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 	/*tipo de amortizacion*/,
						$objCatalogItem_DiasNoCobrables,
						$objCatalogItem_DiasFeriados365,
						$objCatalogItem_DiasFeriados366,
						$objCatalogItemDayExclude,
						$dataSession["company"]->flavorID
					);
					
					$tableAmortization = $this->financial_amort->getTable();
					if($tableAmortization["detail"])
					{
						$sequence = 0;
						foreach($tableAmortization["detail"] as $key => $itemAmortization){
							$sequence 												= $sequence + 1;
							$objCustomerAmoritizacion["customerCreditDocumentID"]	= $customerCreditDocumentID;
							$objCustomerAmoritizacion["balanceStart"]				= $itemAmortization["saldoInicial"];
							$objCustomerAmoritizacion["dateApply"]					= $itemAmortization["date"];
							$objCustomerAmoritizacion["interest"]					= $itemAmortization["interes"];
							$objCustomerAmoritizacion["capital"]					= $itemAmortization["principal"];
							$objCustomerAmoritizacion["share"]						= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["balanceEnd"]					= $itemAmortization["saldo"];
							$objCustomerAmoritizacion["remaining"]					= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["dayDelay"]					= 0;
							$objCustomerAmoritizacion["note"]						= '';
							$objCustomerAmoritizacion["statusID"]					= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_amoritization","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
							$objCustomerAmoritizacion["isActive"]					= 1;
							$objCustomerAmoritizacion["sequence"]					= $sequence;
							$objCustomerAmortizationID 								= $this->Customer_Credit_Amortization_Model->insert_app_posme($objCustomerAmoritizacion);
						}
					}
					
					//Crear las personas relacionadas a la factura
					$objEntityRelated								= array();
					$objEntityRelated["customerCreditDocumentID"]	= $customerCreditDocumentID;
					$objEntityRelated["entityID"]					= $objCustomerCreditLine->entityID;
					$objEntityRelated["type"]						= $this->core_web_parameter->getParameter("CXC_PROPIETARIO_DEL_CREDITO",$companyID)->value;
					$objEntityRelated["typeCredit"]					= 401; /*comercial*/
					$objEntityRelated["statusCredit"]				= 429; /*activo*/
					$objEntityRelated["typeGarantia"]				= 444; /*pagare*/
					$objEntityRelated["typeRecuperation"]			= 450; /*recuperacion normal */
					$objEntityRelated["ratioDesembolso"]			= 1;
					$objEntityRelated["ratioBalance"]				= 1;
					$objEntityRelated["ratioBalanceExpired"]		= 1;
					$objEntityRelated["ratioShare"]					= 1;
					$objEntityRelated["isActive"]					= 1;
					$this->core_web_auditoria->setAuditCreated($objEntityRelated,$dataSession,$this->request);			
					$ccEntityID 		= $this->Customer_Credit_Document_Endity_Related_Model->insert_app_posme($objEntityRelated);
					
					
					
					$montoTotalCordobaCredit = $objTMNew["currencyID"] == 1 /*dolares*/ ? $objCustomerCreditDocument["amount"] : round(($objCustomerCreditDocument["amount"] * $objTMNew["exchangeRate"]),2) ;
					$montoTotalDolaresCredit = $objTMNew["currencyID"] == 2 /*dolares*/ ? $objCustomerCreditDocument["amount"] : round(($objCustomerCreditDocument["amount"] / $objTMNew["exchangeRate"]),2) ;
					
					
					//disminuir el balance de general	
					$objCustomerCredit 					= $this->Customer_Credit_Model->get_rowByPK($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID);
					$objCustomerCreditNew["balanceDol"]	= $objCustomerCredit->balanceDol - $montoTotalDolaresCredit;
					$this->Customer_Credit_Model->update_app_posme($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID,$objCustomerCreditNew);
					
					
					
					//disminuir el balance de linea
					if($objCustomerCreditLine->currencyID == $objCurrencyCordoba->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $montoTotalCordobaCredit;
					else
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $montoTotalDolaresCredit;
						
					
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
					
				}
				
			}
			
			
			if($db->transStatus() !== false)
			{
				$db->transCommit();					
			    $url = '';
				$this->core_web_notification->set_message(false,SUCCESS);				
				if($objParameterRegrearANuevo == "true")
					$url = (base_url()."/".'app_invoice_billing/add/transactionMasterIDToPrinter/'.$transactionMasterID."/codigoMesero/".$codigoMesero);
				else
					$url = (base_url()."/".'app_invoice_billing/edit/transactionMasterIDToPrinter/'.$transactionMasterID.'/companyID/'.$companyID."/transactionID/".$transactionID."/transactionMasterID/".$transactionMasterID."/codigoMesero/".$codigoMesero);

                $response = [
                    'success' 	=> true,
                    'message' 	=> SUCCESS,
                    'redirect' 	=> $url
                ];

                return $this->edit($companyID, $transactionID, $transactionMasterID, $codigoMesero);
			}
			else{
				$db->transRollback();						
				$this->core_web_notification->set_message(true,$db->error());				
                $response = [
                    'success' 	=> true,
                    'message' 	=> SUCCESS,
                    'redirect' 	=> (base_url()."/".'app_invoice_billing/add/transactionMasterIDToPrinter/0'."/codigoMesero/".$codigoMesero)
                ];
                return $this->response->setJSON($response);
			}
			
		}
        catch (DatabaseException $e) {
			$db->transRollback();
            $response = [
                'success' 	=> false,
                'error' 	=> [
								'code' 		=> $e->getLine(),
								'message' 	=> $e->getMessage()
                ],
                'data' 		=> [
                    'codigoMesero' => ''
                ]
            ];
            return $this->response->setJSON($response);
        }
        catch(\Exception $ex)
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

            $this->email->setFrom(EMAIL_APP);
            $this->email->setTo(EMAIL_APP_COPY);
            $this->email->setSubject("Error");
            $this->email->setMessage($resultView);

            $resultSend01 = $this->email->send();
            $resultSend02 = $this->email->printDebugger();


            $response = [
                'success' 	=> false,
                'error' 	=> [
								'code' 		=> $ex->getLine(),
								'message' 	=> $ex->getMessage()
                ],
                'data' 		=> [
                    'codigoMesero' => $codigoMesero
                ]
            ];
            return $this->response->setJSON($response);

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
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
			
			
			$companyID 							= $dataSession["user"]->companyID;			
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			$objCompany							= $dataSession["company"];
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);
			$companyID 								= $dataSession["user"]->companyID;
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$objTransactionCausal 					= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,/*inicio get post*/ $this->request->getPost("txtCausalID"));
			
			
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
		
			
			
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("txtDate")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			
			
			
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			
			//obtener el primer estado  de la factura o el estado inicial.
			$objListWorkflowStage					= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			//Saber si se va autoaplicar
			$objParameterInvoiceAutoApply			= $this->core_web_parameter->getParameter("INVOICE_AUTOAPPLY_CASH",$companyID);
			$objParameterInvoiceAutoApply			= $objParameterInvoiceAutoApply->value;
			$objParaemterStatusCanceled				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CANCEL",$companyID);
			$objParaemterStatusCanceled				= $objParaemterStatusCanceled->value;
			$objParameterUrlPrinterDirect			= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_URL",$companyID);
			$objParameterUrlPrinterDirect			= $objParameterUrlPrinterDirect->value;
			$objParameterImprimirPorCadaFactura		= $this->core_web_parameter->getParameter("INVOICE_PRINT_BY_INVOICE",$companyID);
			$objParameterImprimirPorCadaFactura		= $objParameterImprimirPorCadaFactura->value;
			$objParameterSendEmailInInsert			= $this->core_web_parameter->getParameter("INVOICE_SEND_EMAIL_IN_INSERT",$companyID);
			$objParameterSendEmailInInsert			= $objParameterSendEmailInInsert->value;
			$objParameterEsRestaurant 				= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"INVOICE_BILLING_IS_RESTAURANT")->value;
			$objParameterINVOICE_BILLING_TRAKING_BAR= $this->core_web_parameter->getParameterFiltered($dataSession["companyParameter"],"INVOICE_BILLING_TRAKING_BAR")->value;
			
			
			
			
			//Saber si es al credito
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);			
			$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit						= null;
			$exisCausalInCredit						= array_search(/*inicio get post*/ $this->request->getPost("txtCausalID"),$causalIDTypeCredit);
			if($exisCausalInCredit || $exisCausalInCredit === 0){
				$exisCausalInCredit = "true";
			}
			//Si esta configurado como auto aplicado
			//y es al credito. cambiar el estado por el estado inicial, que es registrada
			$statusID = "";
			if($objParameterInvoiceAutoApply == "true" && $exisCausalInCredit == "true" ){				
				$statusID = $objListWorkflowStage[0]->workflowStageID;
			}
			//si la factura es al contado, y esta como auto aplicada cambiar el estado
			else if ($objParameterInvoiceAutoApply == "true" && $exisCausalInCredit != "true" ){
				$statusID  = $objParaemterStatusCanceled;
			}
			//De lo contrario respetar el estado que venga en pantalla
			else {
				$statusID = /*inicio get post*/ $this->request->getPost("txtStatusID");
			}
			
			
			$codigoMesero							= /*inicio get post*/ $this->request->getPost("txtCodigoMesero");
            $varDescuento		 					= /*inicio get post*/ $this->request->getPost("txtDescuento");
            $varPorcentajeDescuento		 		    = /*inicio get post*/ $this->request->getPost("txtPorcentajeDescuento");
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;			
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_proforma",0);
			$objTM["transactionCausalID"] 			= /*inicio get post*/ $this->request->getPost("txtCausalID");
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("txtCustomerID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("txtDate");
			$objTM["transactionOn2"]				= /*inicio get post*/ $this->request->getPost("txtDateFirst");//Fecha del Primer Pago, de las facturas al credito
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentBilling->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("txtNote");//--fin peticion get o post			
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("txtCurrencyID"); 
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("txtReference1");
			$objTM["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("txtReference2");
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("txtReference3");
			$objTM["reference4"] 					= is_null($this->request->getPost("txtCustomerCreditLineID")) ? "0" : /*inicio get post*/ $this->request->getPost("txtCustomerCreditLineID");//--fin peticion get o post*/
			$objTM["statusID"] 						= $statusID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= /*inicio get post*/ $this->request->getPost("txtWarehouseID");
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$objTM["periodPay"]						= /*inicio get post*/ $this->request->getPost("txtPeriodPay");
			$objTM["nextVisit"]						= /*inicio get post*/ $this->request->getPost("txtNextVisit");
			$objTM["numberPhone"]					= /*inicio get post*/ $this->request->getPost("txtNumberPhone");
			$objTM["entityIDSecondary"]				= /*inicio get post*/ $this->request->getPost("txtEmployeeID");
			$objTM["dayExcluded"]					= /*inicio get post*/ $this->request->getPost("txtDayExcluded");
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);			
			
			
			$db=db_connect();
			$db->transException(true)->transStart();	

			$objParameterInvoiceUpdateNameInTransactionOnly		= $this->core_web_parameter->getParameter("INVOICE_UPDATENAME_IN_TRANSACTION_ONLY",$companyID);
			$objParameterInvoiceUpdateNameInTransactionOnly		= $objParameterInvoiceUpdateNameInTransactionOnly->value;			
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);
			
			
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			
			
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			//Ingresar Informacion Adicional
			$objTMInfo["companyID"]					= $objTM["companyID"];
			$objTMInfo["transactionID"]				= $objTM["transactionID"];
			$objTMInfo["transactionMasterID"]		= $transactionMasterID;
			$objTMInfo["zoneID"]					= /*inicio get post*/ $this->request->getPost("txtZoneID");
			$objTMInfo["mesaID"]					= /*inicio get post*/ $this->request->getPost("txtMesaID");
			$objTMInfo["routeID"]					= 0;
			$objTMInfo["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("txtReferenceClientName");
			$objTMInfo["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("txtReferenceClientIdentifier");
			$objTMInfo["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmount"));
			$objTMInfo["receiptAmountDol"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountDol"));
			$objTMInfo["receiptAmountPoint"]		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountPoint"));
			$objTMInfo["receiptAmountBank"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBank"));
			$objTMInfo["receiptAmountBankDol"]		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBankDol"));
			$objTMInfo["receiptAmountCardDol"]		= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjetaDol"));
			$objTMInfo["receiptAmountCard"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjeta"));
			$objTMInfo["changeAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtChangeAmount"));
			
			$objTMInfo["receiptAmountBankReference"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBank_Reference"));
			$objTMInfo["receiptAmountBankDolReference"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBankDol_Reference"));
			$objTMInfo["receiptAmountCardBankReference"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjeta_Reference"));
			$objTMInfo["receiptAmountCardBankDolReference"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjetaDol_Reference"));			
			
			$objTMInfo["receiptAmountBankID"]						= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBank_BankID"));
			$objTMInfo["receiptAmountBankDolID"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountBankDol_BankID"));
			$objTMInfo["receiptAmountCardBankID"]					= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjeta_BankID"));
			$objTMInfo["receiptAmountCardBankDolID"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtReceiptAmountTarjetaDol_BankID"));			
			$objTMInfo["reference1"]								= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("txtTMIReference1"));
			$objTMInfo["reference2"]								= "not_used";
			
			
			$this->Transaction_Master_Info_Model->insert_app_posme($objTMInfo);
			//Ingresar TransactionMaster Reference
			$objTMReference["transactionMasterID"]		= $transactionMasterID;
			$objTMReference["reference1"]				= /*inicio get post*/ $this->request->getPost("txtLayFirstLineProtocolo");
			$objTMReference["reference2"]				= is_null( /*inicio get post*/ $this->request->getPost("txtCheckApplyExoneracionValue")) ? "0" : /*inicio get post*/ $this->request->getPost("txtCheckApplyExoneracionValue") ;
			$objTMReference["createdOn"]				= helper_getDateTime();
			$objTMReference["isActive"]					= 1;
			$this->Transaction_Master_References_Model->insert_app_posme($objTMReference);
			
			
			//Recorrer la lista del detalle del documento
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("txtItemID");
			$arrayListItemName 							= /*inicio get post*/ $this->request->getPost("txtTransactionDetailName");
			$arrayListItemNameDescription				= /*inicio get post*/ $this->request->getPost("txtTransactionDetailNameDescription");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("txtQuantity");	
			$arrayListPrice		 						= /*inicio get post*/ $this->request->getPost("txtPrice");
			$arrayListSubTotal	 						= /*inicio get post*/ $this->request->getPost("txtSubTotal");
			$arrayListIva		 						= /*inicio get post*/ $this->request->getPost("txtIva");
			$arrayListTaxServices 						= /*inicio get post*/ $this->request->getPost("txtTaxServices");
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("txtDetailLote");
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("txtDetailVencimiento");			
			$arrayListIdSku								= /*inicio get post*/ $this->request->getPost("txtCatalogItemIDSku");
			$arrayListSkuDescription					= /*inicio get post*/ $this->request->getPost("txtSku");
			$arrayListSkuRatio							= /*inicio get post*/ $this->request->getPost("txtRatioSku");
			$arrayListDiscount							= /*inicio get post*/ $this->request->getPost("txtDiscountByItem");			
			$arrayListCommisionBank						= /*inicio get post*/ $this->request->getPost("txtCommisionByBankByItem");		
			$arrayListSkuQuantity						= /*inicio get post*/ $this->request->getPost("skuQuantityBySku");
			$arrayListSkuFormatoDescription				= /*inicio get post*/ $this->request->getPost("skuFormatoDescription");
			$arrayListInfoSales				            = /*inicio get post*/ $this->request->getPost("txtInfoVendedor");
			$arrayListInfoSerie				            = /*inicio get post*/ $this->request->getPost("txtInfoSerie");
			$arrayListInfoReferencia				    = /*inicio get post*/ $this->request->getPost("txtInfoReferencia");
			$arrayListInfoPrecio1				        = /*inicio get post*/ $this->request->getPost("txtItemPrecio1");
			$arrayListInfoPrecio2				        = /*inicio get post*/ $this->request->getPost("txtItemPrecio2");
			$arrayListInfoPrecio3				        = /*inicio get post*/ $this->request->getPost("txtItemPrecio3");

			//Ingresar la configuracion de precios		
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$tax2Total										= 0;
			$subAmountTotal									= 0;
			
			
			//Tipo de precio seleccionado por el usuario,
			//Actualmente no se esta usando
			$typePriceID 							= /*inicio get post*/ $this->request->getPost("txtTypePriceID");
			$objListPrice 							= $this->List_Price_Model->getListPriceToApply($companyID);
			//obtener la lista de precio por defecto
			$objParameterPriceDefault	= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 	= $objParameterPriceDefault->value;
			//obener los tipos de precio de la lista de precio por defecto
			$objTipePrice 	= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			//Parametro para validar si se cambian los precios en la facturacion
			$objParameterUpdatePrice	= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 			= $objParameterUpdatePrice->value;
			
			
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){
					
					$itemID 								= $value;
					$lote 									= is_null($arrayListLote)? "" : $arrayListLote[$key];
					$vencimiento							= is_null($arrayListVencimiento) ? "" : $arrayListVencimiento[$key];
					$warehouseID 							= $objTM["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);					
					$objItemWarehouse 						= $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");
					$objCompanyComponentConceptTaxServices	= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"TAX_SERVICES");
					$skuCatalogItemID						= $arrayListIdSku[$key];
					$skuFormatoDescription					= $arrayListSkuDescription[$key];
					$skuRatio								= $arrayListSkuRatio[$key];
					$discount								= $arrayListDiscount[$key];
					$tax3									= $arrayListCommisionBank[$key];
					$skuQuantityBySku						= $arrayListSkuQuantity[$key];

					$itemNameDetail							= str_replace("'","",$arrayListItemName[$key]);
					$itemNameDetailDescription				= str_replace("'","",$arrayListItemNameDescription[$key]);
					$objItemSku								= $this->Item_Sku_Model->getByPK($itemID,$skuCatalogItemID);
					
					//$price								= $objItem->cost * ( 1 + ($objPrice->percentage/100));
					$price 									= $arrayListPrice[$key];
					
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );
					$taxServicesPercentage					= ($objCompanyComponentConceptTaxServices != null ? $objCompanyComponentConceptTaxServices->valueOut : 0 );
					$ivaPercentage 							= $objTMReference["reference2"] == "0" ? $ivaPercentage : 0;
					$taxServicesPercentage 					= $objTMReference["reference2"] == "0" ? $taxServicesPercentage : 0;
					
					
					$unitaryAmount 							= $price * (1 + $ivaPercentage);
					$tax1 									= $price * $ivaPercentage;
					$tax2									= $price * $taxServicesPercentage;

                    $infoSales				                = $arrayListInfoSales[$key];
                    $infoSerie				                = $arrayListInfoSerie[$key];
                    $infoReferencia				            = $arrayListInfoReferencia[$key];
                    $infoPrecio1				            = $arrayListInfoPrecio1[$key];
                    $infoPrecio2				            = $arrayListInfoPrecio2[$key];
                    $infoPrecio3				            = $arrayListInfoPrecio3[$key];

					//Actualisar nombre 
					if( $objParameterInvoiceUpdateNameInTransactionOnly == "false")
					{
						$objItemNew 		= array();
						$objItemNew["name"] = $itemNameDetail;
						$this->Item_Model->update_app_posme($companyID,$itemID,$objItemNew);
					}
					
					//Validar Cantidades
					if(
						$objItemWarehouse->quantity < $quantity 
						&& 
						$objItem->isInvoiceQuantityZero == 0
						&&						
						$objParameterInvoiceBillingQuantityZero == "false"
					)
					throw new \Exception("La cantidad de '".$objItem->itemNumber. " ".$objItem->name."' es mayor que la disponible en bodega");
					
					
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					
					$objTMD["quantity"] 					= $quantity;						//cantidad
					$objTMD["skuQuantity"] 					= $skuRatio;						//ratio del sqku
					$objTMD["skuQuantityBySku"]				= $skuQuantityBySku;				//cantidad del sku * quantity
					
					$objTMD["unitaryCost"]					= $objItem->cost;					//costo
					$objTMD["cost"] 						= $objTMD["quantity"]  * $objItem->cost;		//cantidad por costo
					
					$objTMD["unitaryPrice"]					= $price;							//precio de lista
					$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto					
					$objTMD["amount"] 						= $objTMD["quantity"] * ($unitaryAmount + $tax2);		//precio de lista con inpuesto por cantidad
					$objTMD["tax1"]							= $tax1;							//impuesto de lista
					$objTMD["tax2"]							= $tax2;							//impuesto de servicio
					$objTMD["tax3"]							= $tax3;		
					
					$objTMD["discount"]						= $discount;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $lote;
					$objTMD["reference2"]					= $vencimiento;
					$objTMD["reference3"]					= '0';
					$objTMD["itemNameLog"] 					= $itemNameDetail;
					$objTMD["itemNameDescriptionLog"] 		= $itemNameDetailDescription;
					
					
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
					$objTMD["skuCatalogItemID"] 			= $skuCatalogItemID;
					$objTMD["skuFormatoDescription"] 		= $skuFormatoDescription;
					
					
					
					$tax1Total								= $tax1Total + $tax1;
					$tax2Total								= $tax2Total + $tax2;
					$subAmountTotal							= $subAmountTotal + ($quantity * $price);
					$amountTotal							= $amountTotal + $objTMD["amount"];
					
					$transactionMasterDetailID_				= $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					
					$objTMDC								= NULL;
					$objTMDC["transactionMasterID"]			= $transactionMasterID;
					$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID_;
					$objTMDC["reference1"]					= /*inicio get post*/ $this->request->getPost("txtFixedExpenses");
					$objTMDC["reference2"]					= /*inicio get post*/ $this->request->getPost("txtCheckReportSinRiesgoValue");
					$objTMDC["reference3"]					= "";
					$objTMDC["reference4"]					= "";
					$objTMDC["reference5"]					= "";
					$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos Fijo para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";
					$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
					
					//Actualizar tipo de precio
					if($objUpdatePrice == "true")
					{ 
						
						$typePriceID					= $typePriceID;																				
						$dataUpdatePrice["price"] 		= $price;
						$dataUpdatePrice["percentage"] 	= 
														$objItem->cost == 0 ? 
															($price / 100) : 
															(((100 * $price) / $objItem->cost) - 100);
															
						
						$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);
								
						
					}

                    $objTMDRNew["isActive"] 					= 1;
                    $objTMDRNew["createdOn"] 					= date("Y-m-d H:m:s");
                    $objTMDRNew["quantity"] 					= $objTMD["quantity"];
                    $objTMDRNew["componentID"] 					= $objTMD["componentID"];
                    $objTMDRNew["componentItemID"]				= $objTMD["componentItemID"];
                    $objTMDRNew["transactionMasterDetailID"]	= $transactionMasterDetailID_;
                    $objTMDRNew["sales"]	                    = $infoSales;
                    $objTMDRNew["reference1"]	                = $infoSerie;
                    $objTMDRNew["reference2"]	                = $infoReferencia;
                    $objTMDRNew["precio1"]	                    = $infoPrecio1;
                    $objTMDRNew["precio2"]	                    = $infoPrecio2;
                    $objTMDRNew["precio3"]	                    = $infoPrecio3;
                    $this->Transaction_Master_Detail_References_Model->insert_app_posme($objTMDRNew);
					
				}
			}
			$amountTotal = $amountTotal-$varDescuento;
			//Actualizar Transaccion
            $objTM["subAmount"] = $subAmountTotal;
            $objTM["tax1"] 		= $tax1Total;
			$objTM["tax2"] 		= $tax2Total;
            $objTM["tax4"] 		= $varPorcentajeDescuento;
            $objTM["discount"]  = $varDescuento;
			$objTM["amount"] 	= $amountTotal;

			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
			
			//Aplicar el Documento?
			//Las factuas de credito no se auto aplican auque este el parametro, por que hay que crer el documento
			//y esto debe ser revisado cuidadosamente
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
				
				//Actualizar el numero de factura
				$objTMNew003["transactionNumber"]				= $this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_billing",0);
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMNew003);
				
			}
			
			//Enviar Email
			if($objParameterSendEmailInInsert == "true" && $objTM["nextVisit"]  != "" )
			{
					$emailProperty 		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL",$companyID);
					$emailProperty 		= $emailProperty->value;
					$nextVisit			= \DateTime::createFromFormat('Y-m-d', $objTM["nextVisit"] )->format("Y-m-d");
		
					
					$params_["objCompany"]  = $objCompany;
					$params_["firstName"]  	= $objTMInfo["referenceClientName"];
					$params_["hour"]  		= $nextVisit;
					$params_["mensaje"]  	= "Cita de: ".$objTMInfo["referenceClientName"]." programada para : ".$nextVisit;
					$subject 				= "Cita de: ".$objTMInfo["referenceClientName"];
					$body  					= /*--inicio view*/ view('core_template/email_notificacion',$params_);//--finview
					
					$this->email->setFrom(EMAIL_APP);
					$this->email->setTo( $emailProperty );
					$this->email->setSubject($subject);			
					$this->email->setMessage($body); 
					$resultSend01 = $this->email->send();
					
			}
			
			
			//No auto aplicar
			if( $db->transStatus() !== false && $objParameterInvoiceAutoApply == "false"  )
			{
				$db->transCommit();
    
				$response = [
					'success' 	=> true,
					'message' 	=> SUCCESS,
					'redirect' 	=> base_url()."/".'app_invoice_billing/edit/transactionMasterIDToPrinter/0/companyID/'.$companyID."/transactionID/".$objTM["transactionID"]."/transactionMasterID/".$transactionMasterID."/codigoMesero/".$codigoMesero
				];
				
				return $this->edit($companyID, $objTM["transactionID"], $transactionMasterID, $codigoMesero);
			}			
			//Si auto aplicar
			else if( $db->transStatus() !== false && $objParameterInvoiceAutoApply == "true"  ){
				
				$db->transCommit();

				$response = [
					'success' 	=> true,
					'message' 	=> SUCCESS,
					'redirect' 	=> base_url()."/".'app_invoice_billing/add/transactionMasterIDToPrinter/'.$transactionMasterID."/codigoMesero/".$codigoMesero
				];
				
				return $this->response->setJSON($response);
			}
			//Error 
			else
			{				
				$db->transRollback();						
				$errorCode 		= $db->error()["code"];
				$errorMessage 	= $db->error()["message"];

				$response = [
					'success' 	=> false,
					'error' 	=> [
							'code' 		=> $errorCode,
							'message'	=> $errorMessage
					],
					'data' 		=> [
						'codigoMesero' => $codigoMesero
					]
				];
				return $this->response->setJSON($response);	
			}		
		}
		catch (DatabaseException $e) {
            $response = [
				'success' 	=> false,
				'error' 	=> [
									'code' 			=> $e->getLine(),
									'message' 		=> $e->getMessage()
				],
				'data' 		=> [
									'codigoMesero' => $codigoMesero
				]
			];
			return $this->response->setJSON($response);	
        }
		catch(\Exception $ex)
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
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    $response = [
                'success' 	=> false,
                'error' 	=> [
                    'code' 		=> $ex->getLine(),
                    'message' 	=> $ex->getMessage()
                ],
                'data' 		=> [
                    'codigoMesero' => $codigoMesero
                ]
            ];
            return $this->response->setJSON($response);
        }
	}

	function insertElementMobil($dataSession,$transactionMaster, $transactionMasterDetails){
	
		try
		{
			
			//Obtener el Componente de Transacciones Facturacion
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");


			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");

			$companyID 								= $dataSession["user"]->companyID;			
			$branchID 								= $dataSession["user"]->branchID;
			$roleID 								= $dataSession["role"]->roleID;
			$objListComanyParameter					= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_billing",0);
			$companyID 								= $dataSession["user"]->companyID;			
			$objT 									= $this->Transaction_Model->getByCompanyAndTransaction($companyID,$transactionID);		
			$employee								= $this->Employee_Model->get_rowByEntityID($companyID,$dataSession["user"]->employeeID );
			
					
			//Valores de tasa de cambio
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");

			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVOICE_BILLING_QUANTITY_ZERO");
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;

			//Saber si se va autoaplicar
			$objParameterInvoiceAutoApply			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_AUTOAPPLY_CASH");
			$objParameterInvoiceAutoApply			= $objParameterInvoiceAutoApply->value;
			$objParaemterStatusCanceled				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_CANCEL");
			$objParaemterStatusCanceled				= $objParaemterStatusCanceled->value;
			$objParameterUrlPrinterDirect			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_URL");
			$objParameterUrlPrinterDirect			= $objParameterUrlPrinterDirect->value;
			$objParameterImprimirPorCadaFactura		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PRINT_BY_INVOICE");
			$objParameterImprimirPorCadaFactura		= $objParameterImprimirPorCadaFactura->value;
			$objParameterSendEmailInInsert			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_SEND_EMAIL_IN_INSERT");
			$objParameterSendEmailInInsert			= $objParameterSendEmailInInsert->value;
			$objParameterAmortizationDuranteFactura	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE");
			$objParameterAmortizationDuranteFactura = $objParameterAmortizationDuranteFactura->value;
			$objParameterUnitDefault				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, 'INVENTORY_UNITMEASURE_ID_DEFAULT')->value;
			$objParameterCXC_DAY_EXCLUDED_IN_CREDIT	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, 'CXC_DAY_EXCLUDED_IN_CREDIT')->value;
			$objParameterEsRestaurant 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVOICE_BILLING_IS_RESTAURANT")->value;
			$objParameterINVOICE_BILLING_TRAKING_BAR= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVOICE_BILLING_TRAKING_BAR")->value;

			//Saber si es al credito
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_CREDIT");			
			$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit						= null;

			$exisCausalInCredit						= array_search($transactionMaster->TransactionCausalId,$causalIDTypeCredit);
			if($exisCausalInCredit || $exisCausalInCredit === 0){
				$exisCausalInCredit = "true";
			}
			$objParameterProviderDefault		= $this->core_web_parameter->getParameter("CXP_PROVIDER_DEFAULT",$companyID);
			$objParameterProviderDefault 		= $objParameterProviderDefault->value;
			$providerDefault	 				= $this->Provider_Model->get_rowByProviderNumber($companyID,$objParameterProviderDefault);
			//Si esta configurado como auto aplicado
			//y es al credito. cambiar el estado por el estado inicial, que es registrada			
			$statusID 								= $this->core_web_workflow->getWorkflowStageApplyFirst("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);			
			$customer 								= $this->Customer_Model->get_rowByIdentification($companyID, $transactionMaster->CustomerIdentification);
			$objParameterWarehouseDefault			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter, "INVENTORY_ITEM_WAREHOUSE_DEFAULT");			
			$warehouseDefault 						= $objParameterWarehouseDefault->value;
			$objListWarehouseTipoDespacho			= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$dataSession["user"]->userID);
			if(!$objListWarehouseTipoDespacho)
			{
				log_message("error","El usuario no tiene una bodega tipo despacho configurada");
				throw new \Exception("El usuario no tiene una bodega tipo despacho configurada");
			}
				
			$warehouseID 							= $this->Warehouse_Model->getByCode($companyID, $warehouseDefault)->warehouseID;
			$warehouseID							= $objListWarehouseTipoDespacho[0]->warehouseID;
			$objTM["companyID"] 					= $companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $branchID;			
			$objTM["transactionNumber"]				= $this->core_web_counter->goNextNumber($companyID,$branchID,"tb_transaction_master_billing",0);
			$objTM["transactionCausalID"] 			= $transactionMaster->TransactionCausalId;
			$objTM["entityID"] 						= $customer->entityID;
			$objTM["transactionOn"]					= $transactionMaster->TransactionOn;
			$objTM["transactionOn2"]				= $transactionMaster->NextVisit;
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:i:s");
			$objTM["componentID"] 					= $objComponentBilling->componentID;
			$objTM["note"] 							= $transactionMaster->Comment;	
			$objTM["sign"] 							= $objT->signInventory;
			$objTM["currencyID"]					= $transactionMaster->CurrencyId; 
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= $providerDefault->entityID;
			$objTM["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTM["reference2"] 					= $transactionMaster->Plazo; 
			$objTM["reference3"] 					= $this->User_Model->get_UserByUserIDAndCompanyID($companyID, $dataSession["user"]->userID)->firstName;
			$objTM["reference4"] 					= is_null($transactionMaster->CustomerCreditLineId) ? "0" : $transactionMaster->CustomerCreditLineId;
			$objTM["statusID"] 						= $statusID[0]->workflowStageID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= $warehouseID;
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$objTM["tax2"]							= 0;
			$objTM["tax4"]							= 0;
			$objTM["discount"]						= 0;
			$objTM["periodPay"]						= $transactionMaster->PeriodPay;
			$objTM["nextVisit"]						= $transactionMaster->NextVisit;
			$objTM["numberPhone"]					= "";
			$objTM["entityIDSecondary"]				= $employee->entityID;
			$objTM["dayExcluded"]					= $objParameterCXC_DAY_EXCLUDED_IN_CREDIT;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);	
			


			$db=db_connect();
			$db->transStart();	

			$objParameterInvoiceUpdateNameInTransactionOnly		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_UPDATENAME_IN_TRANSACTION_ONLY");
			$objParameterInvoiceUpdateNameInTransactionOnly		= $objParameterInvoiceUpdateNameInTransactionOnly->value;			
			$transactionMasterID = $this->Transaction_Master_Model->insert_app_posme($objTM);


			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID;			

			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755);
				chmod($documentoPath, 0755);
			}

			//Ingresar Informacion Adicional
			$objTMInfo["companyID"]					= $companyID;
			$objTMInfo["transactionID"]				= $transactionID;
			$objTMInfo["transactionMasterID"]		= $transactionMasterID;
			$objTMInfo["zoneID"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_ZONEID_DEFAULT")->value; //INVOICE_BILLING_ZONEID_DEFAULT
			$objTMInfo["mesaID"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_MESAID_DEFAULT")->value; //INVOICE_BILLING_MESAID_DEFAULT
			$objTMInfo["routeID"]					= 0;
			$objTMInfo["referenceClientName"]		= "";
			$objTMInfo["referenceClientIdentifier"]	= $customer->identification;
			$amountDolares							= 0;
			$amountCordobas							= 0;
			if($transactionMaster->CurrencyId==$objCurrencyCordoba->currencyID){ //cordobas
				$amountCordobas = $transactionMaster->Amount;
			}else if($transactionMaster->CurrencyId==$objCurrencyDolares->currencyID){ //dolares
				$amountDolares = $transactionMaster->Amount;
			}
			$objTMInfo["receiptAmount"]				= $exisCausalInCredit == "true" ? 0 : $amountCordobas;
			$objTMInfo["receiptAmountDol"]			= $exisCausalInCredit == "true" ? 0 : $amountDolares; 
			$objTMInfo["receiptAmountPoint"]		= 0;
			$objTMInfo["receiptAmountBank"]			= 0;
			$objTMInfo["receiptAmountBankDol"]		= 0;
			$objTMInfo["receiptAmountCardDol"]		= 0;
			$objTMInfo["receiptAmountCard"]			= 0;
			$objTMInfo["changeAmount"]				= 0;

			$objTMInfo["receiptAmountBankReference"]				= 0;
			$objTMInfo["receiptAmountBankDolReference"]				= 0;
			$objTMInfo["receiptAmountCardBankReference"]			= 0;
			$objTMInfo["receiptAmountCardBankDolReference"]			= 0;

			$objTMInfo["receiptAmountBankID"]						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_BANKID_DEFAULT")->value; //INVOICE_BILLING_BANKID_DEFAULT
			$objTMInfo["receiptAmountBankDolID"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_BANKID_DEFAULT")->value; //INVOICE_BILLING_BANKID_DEFAULT
			$objTMInfo["receiptAmountCardBankID"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_BANKID_DEFAULT")->value; //INVOICE_BILLING_BANKID_DEFAULT
			$objTMInfo["receiptAmountCardBankDolID"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_BANKID_DEFAULT")->value; //INVOICE_BILLING_BANKID_DEFAULT
			$objTMInfo["reference1"]								= 0;
			$objTMInfo["reference2"]								= $transactionMaster->TransactionNumber;


			$this->Transaction_Master_Info_Model->insert_app_posme($objTMInfo);
			
			
			//Ingresar TransactionMaster Reference
			$objTMReference["transactionMasterID"]		= $transactionMasterID;
			$objTMReference["reference1"]				= "";  //numero de exoneracion
			$objTMReference["reference2"]				= "0"; //aplica exoneracion
			$objTMReference["createdOn"]				= helper_getDateTime();
			$objTMReference["isActive"]					= 1;
			$this->Transaction_Master_References_Model->insert_app_posme($objTMReference);

			//Ingresar la configuracion de precios		
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$tax2Total										= 0;
			$subAmountTotal									= 0;

			//obtener la lista de precio por defecto
			$objParameterPriceDefault	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_DEFAULT_PRICELIST");
			$listPriceID 	= $objParameterPriceDefault->value;
			if(count($transactionMasterDetails)>0){
				foreach($transactionMasterDetails as $value){
					$objItem 								= $this->Item_Model->get_rowByCodeBarra($companyID, $value->ItemBarCode);
					if(!isset($objItem)){
						continue;
					}
					$itemID 								= $objItem->itemID;
					$lote 									= "";
					$vencimiento							= "";
					$warehouseID 							= $objTM["sourceWarehouseID"];				
					$quantity 								= $value->Quantity;
					$itemNameDetail							= $objItem->name;
					$itemNameDetailDescription				= $objItem->description;
					$price 									= $value->UnitaryPrice;
					$comisionPorcentage						= 0;
					$comisionPorcentage						= $this->core_web_transaction_master_detail->getPorcentageComision($companyID,$listPriceID,$itemID,$price);
					$ivaPercentage							= 0;
					$taxServicesPercentage					= 0;
					$unitaryAmount 							= $price * (1 + $ivaPercentage);
					$tax1 									= $price * $ivaPercentage;
					$tax2									= $price * $taxServicesPercentage;

					$objTMD 								= array();
					$objTMD["companyID"] 					= $companyID;
					$objTMD["transactionID"] 				= $transactionID;
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					$objTMD["quantity"] 					= $quantity;	//cantidad
					$objTMD["skuQuantity"] 					= $quantity;						//cantidad
					$objTMD["skuQuantityBySku"]				= 1;				//cantidad

					$objTMD["unitaryCost"]					= $objItem->cost;					//costo
					$objTMD["cost"] 						= $quantity  * $objItem->cost;		//cantidad por costo

					$objTMD["unitaryPrice"]					= $price;							//precio de lista
					$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto					
					$objTMD["amount"] 						= $quantity * $unitaryAmount;		//precio de lista con inpuesto por cantidad
					$objTMD["tax1"]							= $tax1;							//impuesto de lista
					$objTMD["tax2"]							= $tax2; 							//impusto de servicio

					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;

					$objTMD["reference1"]					= $lote;
					$objTMD["reference2"]					= $vencimiento;
					$objTMD["reference3"]					= '0';
					$objTMD["itemNameLog"] 					= $itemNameDetail;
					$objTMD["itemNameDescriptionLog"] 		= $itemNameDetailDescription;


					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
					$objTMD["skuCatalogItemID"] 			= $objParameterUnitDefault;
					$objTMD["skuFormatoDescription"] 		= 'UNIDAD';
					$objTMD["amountCommision"] 				= $price * $comisionPorcentage * $quantity ;

					$tax1Total								= $tax1Total + $tax1;
					$tax2Total								= $tax2Total + $tax2;
					$subAmountTotal							= $subAmountTotal + ($quantity * $price);
					$amountTotal							= $amountTotal + $objTMD["amount"];

					$transactionMasterDetailID_				= $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);

					$objTMDC								= NULL;
					$objTMDC["transactionMasterID"]			= $transactionMasterID;
					$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID_;
					$objTMDC["reference1"]					= $transactionMaster->FixedExpenses;
					$objTMDC["reference2"]					= "1";
					$objTMDC["reference3"]					= "0";
					$objTMDC["reference4"]					= "";
					$objTMDC["reference5"]					= "";
					$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos Fijo para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";
					$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
					
					
					//Ingresar la lista de productos de RESTAURANTE
					if ( $objParameterINVOICE_BILLING_TRAKING_BAR == "true")
					{
						$objTMDRNew["isActive"] 					= 1;
						$objTMDRNew["createdOn"] 					= date("Y-m-d H:m:s");
						$objTMDRNew["quantity"] 					= $objTMD["quantity"];
						$objTMDRNew["componentID"] 					= $objTMD["componentID"];
						$objTMDRNew["componentItemID"]				= $objTMD["componentItemID"];
						$objTMDRNew["transactionMasterDetailID"]	= $transactionMasterDetailID_;
						$this->Transaction_Master_Detail_References_Model->insert_app_posme($objTMDRNew);
					}
					
				}
			}

			//Actualizar Transaccion
			$objTM["amount"] 	= $amountTotal;
			$objTM["tax1"] 		= $tax1Total;
			$objTM["tax2"] 		= $tax2Total;
			$objTM["subAmount"] = $subAmountTotal;			
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);

			//Ingresar la configuracion de precios			
			$objParameterPriceDefault	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_DEFAULT_PRICELIST");
			$listPriceID 	= $objParameterPriceDefault->value;


			//Aplicar el Documento?
			if( 
				$this->core_web_workflow->validateWorkflowStage
				(
					"tb_transaction_master_billing",
					"statusID",
					$objTM["statusID"],
					COMMAND_APLICABLE,
					$companyID,
					$branchID,
					$roleID
				) 
			){



				//Acumular punto del cliente.
				if($objTMInfo["receiptAmountPoint"] <= 0 && $objTM["currencyID"]  == $objCurrencyCordoba->currencyID )
				{
					$objCustomer 					= $this->Customer_Model->get_rowByEntity($companyID, $objTM["entityID"] );
					$objCustomerNew["balancePoint"]	= $objCustomer->balancePoint + $amountTotal;
					$this->Customer_Model->update_app_posme($objCustomer->companyID,$objCustomer->branchID,$objCustomer->entityID,$objCustomerNew);
				}
				//Es pago con punto restar puntos
				if($objTMInfo["receiptAmountPoint"] > 0 && $objTM["currencyID"]  ==  $objCurrencyCordoba->currencyID )
				{
					$objCustomer 					= $this->Customer_Model->get_rowByEntity($companyID, $objTM["entityID"] );
					$objCustomerNew["balancePoint"]	= $objCustomer->balancePoint - $objTMInfo["receiptAmountPoint"];
					$this->Customer_Model->update_app_posme($objCustomer->companyID,$objCustomer->branchID,$objCustomer->entityID,$objCustomerNew);
				}


				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			

				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);

				//Si es al credito crear tabla de amortizacion
				$causalIDTypeCredit 	= explode(",", $parameterCausalTypeCredit->value);
				$exisCausalInCredit		= null;
				$exisCausalInCredit		= array_search($objTM["transactionCausalID"] ,$causalIDTypeCredit);

				//si la factura es de credito
				if($exisCausalInCredit || $exisCausalInCredit === 0){


					//Crear documento del modulo
					$objCustomerCreditLine 								= $this->Customer_Credit_Line_Model->get_rowByPK($transactionMaster->CustomerCreditLineId);
					$objCustomerCreditDocument["companyID"] 			= $companyID;
					$objCustomerCreditDocument["entityID"] 				= $objCustomerCreditLine->entityID;
					$objCustomerCreditDocument["customerCreditLineID"] 	= $objCustomerCreditLine->customerCreditLineID;
					$objCustomerCreditDocument["documentNumber"] 		= $objTM["transactionNumber"];
					$objCustomerCreditDocument["dateOn"] 				= $objTM["transactionOn"];
					$objCustomerCreditDocument["exchangeRate"] 			= $objTM["exchangeRate"];
					$objCustomerCreditDocument["interes"] 				= $objCustomerCreditLine->interestYear;

					$objCustomerCreditDocument["term"] 					= $objCustomerCreditLine->term;
					$objCustomerCreditDocument["amount"] 				= $amountTotal; 
					$objCustomerCreditDocument["balance"] 				= $amountTotal;
					$objCatalogItemDayExclude 							= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->dayExcluded);

					if($objParameterAmortizationDuranteFactura == "true" &&  $objTM["currencyID"] == 1 /*cordoba*/)
					{
						
						
						$objCustomerCreditDocument["term"] 					= $objTM["reference2"];
						$objCustomerCreditDocument["interes"] 				= $transactionMaster->FixedExpenses;
						$objCustomerCreditDocument["amount"] 				= 	$amountTotal - 
																				$objTMInfo["receiptAmountPoint"] - 
																				
																				$objTMInfo["receiptAmount"] - 
																				$objTMInfo["receiptAmountBank"] - 																				
																				$objTMInfo["receiptAmountCard"] - 
																				
																				round(($objTMInfo["receiptAmountBankDol"] * $objTM["exchangeRate"]),2) - 
																				round(($objTMInfo["receiptAmountCardDol"] * $objTM["exchangeRate"]),2) - 																			
																				round(($objTMInfo["receiptAmountDol"] * $objTM["exchangeRate"]),2)  ;
																				
						$objCustomerCreditDocument["balance"] 				= $objCustomerCreditDocument["amount"];
					}
					
					if($objParameterAmortizationDuranteFactura == "true" &&  $objTM["currencyID"] == 2 /*dolares*/)
					{
						$objCustomerCreditDocument["term"] 					= $objTM["reference2"];
						$objCustomerCreditDocument["interes"] 				= $transactionMaster->FixedExpenses;
						$objCustomerCreditDocument["amount"] 				= 	$amountTotal - 
																				$objTMInfo["receiptAmountPoint"] - 
																				
																				$objTMInfo["receiptAmount"] - 
																				$objTMInfo["receiptAmountBank"] - 																				
																				$objTMInfo["receiptAmountCard"] - 
																				
																				round(($objTMInfo["receiptAmountBankDol"] / $objTM["exchangeRate"]),2) - 
																				round(($objTMInfo["receiptAmountCardDol"] / $objTM["exchangeRate"]),2) - 																			
																				round(($objTMInfo["receiptAmountDol"] / $objTM["exchangeRate"]),2)  ;
																				
						$objCustomerCreditDocument["balance"] 				= $objCustomerCreditDocument["amount"];
					}
					
					
					$objCustomerCreditDocument["currencyID"] 			= $objTM["currencyID"];					
					$objCustomerCreditDocument["statusID"] 				= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_document","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
					$objCustomerCreditDocument["reference1"] 			= $objTM["note"];
					$objCustomerCreditDocument["reference2"] 			= "";
					$objCustomerCreditDocument["reference3"] 			= "";
					$objCustomerCreditDocument["isActive"] 				= 1;

					$objCustomerCreditDocument["providerIDCredit"] 		= $objTM["reference1"];
					$objCustomerCreditDocument["periodPay"]				= $objCustomerCreditLine->periodPay;

					if($objParameterAmortizationDuranteFactura == "true")
					{
						$objCustomerCreditDocument["periodPay"]			= $objTM["periodPay"];
						$objCatalogItemDayExclude 						= $this->Catalog_Item_Model->get_rowByCatalogItemID($objTM["dayExcluded"]);
					}
					
					
					$objCustomerCreditDocument["typeAmortization"] 		= $objCustomerCreditLine->typeAmortization;					
					$objCustomerCreditDocument["reportSinRiesgo"] 	 	= 1;
					$customerCreditDocumentID 							= $this->Customer_Credit_Document_Model->insert_app_posme($objCustomerCreditDocument);
					$periodPay 											= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);

					if($objParameterAmortizationDuranteFactura == "true")
					{
						$periodPay 										= $this->Catalog_Item_Model->get_rowByCatalogItemID( $objTM["periodPay"] );
					}
					
					
					$objCatalogItem_DiasNoCobrables 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES",$companyID);
					$objCatalogItem_DiasFeriados365 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_365",$companyID);
					$objCatalogItem_DiasFeriados366 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_366",$companyID);
					

					//Crear tabla de amortizacion
					$this->financial_amort->amort(
						$objCustomerCreditDocument["amount"], 		/*monto*/
						$objCustomerCreditDocument["interes"],		/*interes anual*/
						$objCustomerCreditDocument["term"],			/*numero de pagos*/	
						$periodPay->sequence,						/*frecuencia de pago en dia*/
						$objTM["transactionOn2"], 				/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 	/*tipo de amortizacion*/,
						$objCatalogItem_DiasNoCobrables,
						$objCatalogItem_DiasFeriados365,
						$objCatalogItem_DiasFeriados366,
						$objCatalogItemDayExclude,
						$dataSession["company"]->flavorID
					);

					$tableAmortization = $this->financial_amort->getTable();
					if($tableAmortization["detail"])
					{
						$sequence = 0;
						foreach($tableAmortization["detail"] as $key => $itemAmortization){
							$sequence 												= $sequence + 1;
							$objCustomerAmoritizacion["customerCreditDocumentID"]	= $customerCreditDocumentID;
							$objCustomerAmoritizacion["balanceStart"]				= $itemAmortization["saldoInicial"];
							$objCustomerAmoritizacion["dateApply"]					= $itemAmortization["date"];
							$objCustomerAmoritizacion["interest"]					= $itemAmortization["interes"];
							$objCustomerAmoritizacion["capital"]					= $itemAmortization["principal"];
							$objCustomerAmoritizacion["share"]						= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["balanceEnd"]					= $itemAmortization["saldo"];
							$objCustomerAmoritizacion["remaining"]					= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["dayDelay"]					= 0;
							$objCustomerAmoritizacion["note"]						= '';
							$objCustomerAmoritizacion["statusID"]					= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_amoritization","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
							$objCustomerAmoritizacion["isActive"]					= 1;
							$objCustomerAmoritizacion["sequence"]					= $sequence;
							$objCustomerAmortizationID 								= $this->Customer_Credit_Amortization_Model->insert_app_posme($objCustomerAmoritizacion);
						}
					}

					//Crear las personas relacionadas a la factura
					$objEntityRelated								= array();
					$objEntityRelated["customerCreditDocumentID"]	= $customerCreditDocumentID;
					$objEntityRelated["entityID"]					= $objCustomerCreditLine->entityID;
					$objEntityRelated["type"]						= $this->core_web_parameter->getParameter("CXC_PROPIETARIO_DEL_CREDITO",$companyID)->value;
					$objEntityRelated["typeCredit"]					= 401; /*comercial*/
					$objEntityRelated["statusCredit"]				= 429; /*activo*/
					$objEntityRelated["typeGarantia"]				= 444; /*pagare*/
					$objEntityRelated["typeRecuperation"]			= 450; /*recuperacion normal */
					$objEntityRelated["ratioDesembolso"]			= 1;
					$objEntityRelated["ratioBalance"]				= 1;
					$objEntityRelated["ratioBalanceExpired"]		= 1;
					$objEntityRelated["ratioShare"]					= 1;
					$objEntityRelated["isActive"]					= 1;
					$this->core_web_auditoria->setAuditCreated($objEntityRelated,$dataSession,$this->request);			
					$ccEntityID 		= $this->Customer_Credit_Document_Endity_Related_Model->insert_app_posme($objEntityRelated);



					$montoTotalCordobaCredit = $objTM["currencyID"] == $objCurrencyCordoba->currencyID ? $objCustomerCreditDocument["amount"] : round(($objCustomerCreditDocument["amount"] * $objTM["exchangeRate"]),2) ;
					$montoTotalDolaresCredit = $objTM["currencyID"] == $objCurrencyDolares->currencyID ? $objCustomerCreditDocument["amount"] : round(($objCustomerCreditDocument["amount"] / $objTM["exchangeRate"]),2) ;
				

					//disminuir el balance de general	
					$objCustomerCredit 					= $this->Customer_Credit_Model->get_rowByPK($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID);
					$objCustomerCreditNew["balanceDol"]	= $objCustomerCredit->balanceDol - $montoTotalDolaresCredit;
					$this->Customer_Credit_Model->update_app_posme($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID,$objCustomerCreditNew);



					//disminuir el balance de linea
					if($objCustomerCreditLine->currencyID == $objCurrencyCordoba->currencyID)
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $montoTotalCordobaCredit;
					else
					$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $montoTotalDolaresCredit;								
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
				}

			}

			//No auto aplicar
			if( $db->transStatus() !== false)
			{
				$db->transCommit();
			}
			else
			{
				$db->transRollback();
				throw new \Exception(ERROR);
			}	
		}
		catch(\Exception $ex)
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

	function saveApi(){
		 try{ 
			//Autenticado
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			//Validar Formulario						
			$this->validation->setRule("statusID","Estado","required");
			$this->validation->setRule("transactionOn","Fecha de transaccion","required");
			$this->validation->setRule("createdOn","Fecha de creacion","required");
			$this->validation->setRule("transactionMasterID","Id de transaccion","required");
			$this->validation->setRule("transactionCausalID","Id del causal","required");
			
			
			
			//Permiso de agregar factura
			if(/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0){
				if(APP_NEED_AUTHENTICATION == true){
					$permited = false;
					$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					
					if(!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);
					
					$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"add",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_INSERT);	
				}
			}
			//Permiso de editar factura
			else{
				if(APP_NEED_AUTHENTICATION == true){
					$permited = false;
					$permited = $this->core_web_permission->urlPermited(get_class($this),"index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					
					if(!$permited)
					throw new \Exception(NOT_ACCESS_CONTROL);
					
					$resultPermission		= $this->core_web_permission->urlPermissionCmd(get_class($this),"edit",URL_SUFFIX,$dataSession,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
					if ($resultPermission 	== PERMISSION_NONE)
					throw new \Exception(NOT_ALL_EDIT);	
				}
			}
			
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			
			
			//Obtener componentes
			$objComponentBilling			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			
			$objComponentItem				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			//Obtener transaccion
			$transactionID 							= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);			
			$objTransaction							= $this->Transaction_Model->getByCompanyAndTransaction($dataSession["user"]->companyID,$transactionID);
			$objTransactionCausal 					= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,/*inicio get post*/ $this->request->getPost("transactionCausalID"));
			
			
			//Obtener tipo de cambio			
			date_default_timezone_set(APP_TIMEZONE); 
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);
			$dateOn 								= date("Y-m-d");
			$dateOn 								= date_format(date_create($dateOn),"Y-m-d");
			$exchangeRate 							= $this->core_web_currency->getRatio($companyID,$dateOn,1,$objCurrencyDolares->currencyID,$objCurrencyCordoba->currencyID);
			
			//Validar ciclo contable
			if($this->core_web_accounting->cycleIsCloseByDate($dataSession["user"]->companyID,/*inicio get post*/ $this->request->getPost("transactionOn")))
			throw new \Exception("EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO");
			
			//Validar licencia
			$this->core_web_permission->getValueLicense($dataSession["user"]->companyID,get_class($this)."/"."index");
			
			//Obtener parametros
			$objParameterInvoiceBillingQuantityZero		= $this->core_web_parameter->getParameter("INVOICE_BILLING_QUANTITY_ZERO",$companyID);
			$objParameterInvoiceBillingQuantityZero		= $objParameterInvoiceBillingQuantityZero->value;
			
			
			$objParameterInvoiceAutoApply				= $this->core_web_parameter->getParameter("INVOICE_AUTOAPPLY_CASH",$companyID);
			$objParameterInvoiceAutoApply				= $objParameterInvoiceAutoApply->value;
			
			$objParameterPriceDefault				= $this->core_web_parameter->getParameter("INVOICE_DEFAULT_PRICELIST",$companyID);
			$listPriceID 							= $objParameterPriceDefault->value;
						
			$objParameterUpdatePrice				= $this->core_web_parameter->getParameter("INVOICE_UPDATEPRICE_ONLINE",$companyID);
			$objUpdatePrice 						= $objParameterUpdatePrice->value;
			
			
			//Ver si es factura de credito
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameter("INVOICE_BILLING_CREDIT",$companyID);			
			$causalIDTypeCredit 					= explode(",", $parameterCausalTypeCredit->value);
			$exisCausalInCredit						= null;
			$exisCausalInCredit						= array_search(/*inicio get post*/ $this->request->getPost("transactionCausalID"),$causalIDTypeCredit);
			$esFacturaDeCredito						= false;
			if($exisCausalInCredit || $exisCausalInCredit === 0){
				$exisCausalInCredit = "true";
				$esFacturaDeCredito = true;
			}
			
			//Obter estado de factura
			$objListWorkflowStage					= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			
			
			//Obtener el estado de la factura
			//Si la configuracion es auto - aplicada
			//pero es una factura de credito - pasar el estado al inicial
			$statusID = "";
			if($objParameterInvoiceAutoApply == "true" && $exisCausalInCredit == "true" ){				
				$statusID = $objListWorkflowStage[0]->workflowStageID;
			}
			//De lo contrario respetar el estado que venga en pantalla
			else {
				$statusID = /*inicio get post*/ $this->request->getPost("statusID");
			}
			
			
			
			//Obtener tipos de precio
			$typePriceID 								= /*inicio get post*/ $this->request->getPost("typePriceID");
			$objListPrice 								= $this->List_Price_Model->getListPriceToApply($companyID);
			
			//Obtener el catalogo de tipos de precios
			$objTipePrice 		= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			
			//Inicia los valores de la factura	
			$transactionNumber 	= 
				/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0 ? 
				$this->core_web_counter->goNextNumber($dataSession["user"]->companyID,$dataSession["user"]->branchID,"tb_transaction_master_billing",0) :
				/*inicio get post*/ $this->request->getPost("transactionNumber");
				
			
			$objTM["companyID"] 					= $dataSession["user"]->companyID;
			$objTM["transactionID"] 				= $transactionID;			
			$objTM["branchID"]						= $dataSession["user"]->branchID;
			$objTM["transactionNumber"]				= $transactionNumber;
			$objTM["transactionCausalID"] 			= /*inicio get post*/ $this->request->getPost("transactionCausalID");
			$objTM["entityID"] 						= /*inicio get post*/ $this->request->getPost("entityID");
			$objTM["transactionOn"]					= /*inicio get post*/ $this->request->getPost("transactionOn");
			$objTM["transactionOn2"]				= /*inicio get post*/ $this->request->getPost("transactionOn");
			$objTM["statusIDChangeOn"]				= date("Y-m-d H:m:s");
			$objTM["componentID"] 					= $objComponentBilling->componentID;
			$objTM["note"] 							= /*inicio get post*/ $this->request->getPost("note");//--fin peticion get o post
			$objTM["sign"] 							= $objTransaction->signInventory;
			$objTM["currencyID"]					= /*inicio get post*/ $this->request->getPost("currencyID"); 
			$objTM["currencyID2"]					= $this->core_web_currency->getTarget($companyID,$objTM["currencyID"]);
			$objTM["exchangeRate"]					= $this->core_web_currency->getRatio($dataSession["user"]->companyID,date("Y-m-d"),1,$objTM["currencyID2"],$objTM["currencyID"]);
			$objTM["reference1"] 					= /*inicio get post*/ $this->request->getPost("reference1");
			$objTM["descriptionReference"] 			= "reference1:entityID del proveedor de credito para las facturas al credito,reference4: customerCreditLineID linea de credito del cliente";
			$objTM["reference2"] 					= /*inicio get post*/ $this->request->getPost("reference2");
			$objTM["reference3"] 					= /*inicio get post*/ $this->request->getPost("reference3");
			$objTM["reference4"] 					= /*inicio get post*/ $this->request->getPost("reference4");//--fin peticion get o post
			$objTM["statusID"] 						= $statusID;
			$objTM["amount"] 						= 0;
			$objTM["isApplied"] 					= 0;
			$objTM["journalEntryID"] 				= 0;
			$objTM["classID"] 						= NULL;
			$objTM["areaID"] 						= NULL;
			$objTM["sourceWarehouseID"]				= /*inicio get post*/ $this->request->getPost("sourceWarehouseID");
			$objTM["targetWarehouseID"]				= NULL;
			$objTM["isActive"]						= 1;
			$this->core_web_auditoria->setAuditCreated($objTM,$dataSession,$this->request);	
			
			
			
			$db=db_connect();
			$db->transStart();
			$transactionMasterID = 0;
			
			//Insertar Factura
			if(/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0){
				$transactionMasterID 						= $this->Transaction_Master_Model->insert_app_posme($objTM);				
				
				//Crear la Carpeta para almacenar los Archivos del Documento
				mkdir(PATH_FILE_OF_APP."/company_".$companyID."/component_".$objComponentBilling->componentID."/component_item_".$transactionMasterID, 0700);
			}
			else{
				$transactionMasterID 		= /*inicio get post*/ $this->request->getPost("transactionMasterID");				
				$objTransactionMasterOld  	= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				
				//Validar Edicion por el Usuario
				if ($resultPermission 	== PERMISSION_ME && (  /*inicio get post*/ $this->request->getPost("createdBy") != $userID))
				throw new \Exception(NOT_EDIT);
			
				//Validar si el estado permite editar
				if(!$this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTransactionMasterOld->statusID,COMMAND_EDITABLE_TOTAL,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID))
				throw new \Exception(NOT_WORKFLOW_EDIT);	
			
				$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
				
			}
			
			
			
			//Insertar Transaction Master Info
			$objTMInfoNew["companyID"]					= $objTM["companyID"];
			$objTMInfoNew["transactionID"]				= $objTM["transactionID"];			
			$objTMInfoNew["zoneID"]						= /*inicio get post*/ $this->request->getPost("zoneID");
			$objTMInfoNew["routeID"]					= 0;
			$objTMInfoNew["referenceClientName"]		= /*inicio get post*/ $this->request->getPost("referenceClientName");
			$objTMInfoNew["referenceClientIdentifier"]	= /*inicio get post*/ $this->request->getPost("referenceClientIdentifier");
			$objTMInfoNew["receiptAmount"]				= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("receiptAmount"));
			$objTMInfoNew["receiptAmountDol"]			= helper_StringToNumber(/*inicio get post*/ $this->request->getPost("receiptAmountDol"));			
			$objTMInfoNew["transactionMasterID"]  		= $transactionMasterID;
			
			if(/*inicio get post*/ $this->request->getPost("transactionMasterID") == 0){
				$this->Transaction_Master_Info_Model->insert_app_posme($objTMInfoNew);
			}
			else{
				
				$this->Transaction_Master_Info_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTMInfoNew);
			}
			//Actualizar Detalle			
			$listTransactionDetalID 					= /*inicio get post*/ $this->request->getPost("transactionMasterDetailID");
			$arrayListItemID 							= /*inicio get post*/ $this->request->getPost("itemID");
			$arrayListQuantity	 						= /*inicio get post*/ $this->request->getPost("quantity");
			$arrayListPrice		 						= /*inicio get post*/ $this->request->getPost("price");
			$arrayListSubTotal	 						= /*inicio get post*/ $this->request->getPost("subtotal");
			$arrayListIva		 						= /*inicio get post*/ $this->request->getPost("iva");
			$arrayListLote	 							= /*inicio get post*/ $this->request->getPost("lote");			
			$arrayListVencimiento						= /*inicio get post*/ $this->request->getPost("vencimiento");	
			$arrayListSku								= /*inicio get post*/ $this->request->getPost("sku");
			
			
			
			$amountTotal 									= 0;
			$tax1Total 										= 0;
			$subAmountTotal									= 0;			$this->Transaction_Master_Detail_Model->deleteWhereIDNotIn($companyID,$transactionID,$transactionMasterID,$listTransactionDetalID);
			$this->Transaction_Master_Detail_Credit_Model->deleteWhereIDNotIn($transactionMasterID,$listTransactionDetalID);
			if(!empty($arrayListItemID)){
				foreach($arrayListItemID as $key => $value){		
				
					//Obtener Variables
					$itemID 								= $value;
					$lote 									= $arrayListLote[$key];
					$vencimiento							= $arrayListVencimiento[$key];
					$warehouseID 							= $objTM["sourceWarehouseID"];
					$objItem 								= $this->Item_Model->get_rowByPK($companyID,$itemID);
					$objItemWarehouse 						= $this->Itemwarehouse_Model->getByPK($companyID,$itemID,$warehouseID);					
					$quantity 								= helper_StringToNumber($arrayListQuantity[$key]);					
					$objPrice 								= $this->Price_Model->get_rowByPK($companyID,$objListPrice->listPriceID,$itemID,$typePriceID);
					$objCompanyComponentConcept 			= $this->Company_Component_Concept_Model->get_rowByPK($companyID,$objComponentItem->componentID,$itemID,"IVA");									
					
					$skuCatalogItemID						= $arrayListSku[$key];					
					$objItemSku								= $this->Item_Sku_Model->getByPK($itemID,$skuCatalogItemID);
					
					$price 									= $arrayListPrice[$key] / ($quantity * $objItemSku->value) ;
					$ivaPercentage							= ($objCompanyComponentConcept != null ? $objCompanyComponentConcept->valueOut : 0 );					
					$unitaryAmount 							= $price * (1 + $ivaPercentage);					
					$tax1 									= $price * $ivaPercentage;
					$transactionMasterDetailID				= $listTransactionDetalID[$key];
					$nuevoRegistro							= true;
					
					
					//Validar Cantidades
					$messageException = "La cantidad de '".$objItem->itemNumber. " " .$objItem->name."' es mayor que la disponible en bodega";
					$messageException = $messageException.", en bodega existen ".$objItemWarehouse->quantity." y esta solicitando : ".$quantity;
					if(
						$objItemWarehouse->quantity < $quantity  
						&& 
						$objItem->isInvoiceQuantityZero == 0
					)					
					throw new \Exception($messageException);
						
					//Transacation Master Detalle
					$objTMD 								= NULL;
					$objTMD["companyID"] 					= $objTM["companyID"];
					$objTMD["transactionID"] 				= $objTM["transactionID"];
					$objTMD["transactionMasterID"] 			= $transactionMasterID;
					$objTMD["componentID"]					= $objComponentItem->componentID;
					$objTMD["componentItemID"] 				= $itemID;
					
					$objTMD["quantity"] 					= $quantity * $objItemSku->value;	//cantidad
					$objTMD["skuQuantity"] 					= $quantity;						//cantidad
					$objTMD["skuQuantityBySku"]				= $objItemSku->value;				//cantidad
					
					
					$objTMD["unitaryCost"]					= $objItem->cost;							//costo
					$objTMD["cost"] 						= $objTMD["quantity"] * $objItem->cost;		//costo por unidad
					
					$objTMD["unitaryPrice"]					= $price;							//precio de lista
					$objTMD["unitaryAmount"]				= $unitaryAmount;					//precio de lista con inpuesto					
					$objTMD["amount"] 						= $objTMD["quantity"]* $unitaryAmount;		//precio de lista con inpuesto por cantidad
					
					$objTMD["tax1"]							= $tax1;							//impuesto de lista
					$objTMD["discount"]						= 0;					
					$objTMD["promotionID"] 					= 0;
					
					$objTMD["reference1"]					= $lote;
					$objTMD["reference2"]					= $vencimiento;
					$objTMD["reference3"]					= '0';
					
					
					$objTMD["catalogStatusID"]				= 0;
					$objTMD["inventoryStatusID"]			= 0;
					$objTMD["isActive"]						= 1;
					$objTMD["quantityStock"]				= 0;
					$objTMD["quantiryStockInTraffic"]		= 0;
					$objTMD["quantityStockUnaswared"]		= 0;
					$objTMD["remaingStock"]					= 0;
					$objTMD["expirationDate"]				= NULL;
					$objTMD["inventoryWarehouseSourceID"]	= $objTM["sourceWarehouseID"];
					$objTMD["inventoryWarehouseTargetID"]	= $objTM["targetWarehouseID"];
					$objTMD["skuCatalogItemID"] 			= $skuCatalogItemID;
					
					
				
					
					if($transactionMasterDetailID == 0){	
						$nuevoRegistro 				= true;						
						$transactionMasterDetailID 	= $this->Transaction_Master_Detail_Model->insert_app_posme($objTMD);
					}
					else{			
						$nuevoRegistro 	= false;
						$this->Transaction_Master_Detail_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$transactionMasterDetailID,$objTMD);							
						
					}
					
					
					
					//Precio
					if($objUpdatePrice == "true" )
					{							
						$typePriceID					= $typePriceID;
						$dataUpdatePrice["price"] 		= $price;
						$dataUpdatePrice["percentage"] 	= 
														$objItem->cost == 0 ? 
															($price / 100) : 
															(((100 * $price) / $objItem->cost) - 100);																		
						
						$objPrice = $this->Price_Model->update_app_posme($companyID,$listPriceID,$itemID,$typePriceID,$dataUpdatePrice);									
						
					}
					
					
					//Documento
					$objTMDC								= NULL;
					$objTMDC["transactionMasterID"]			= $transactionMasterID;
					$objTMDC["transactionMasterDetailID"]	= $transactionMasterDetailID;
					$objTMDC["reference1"]					= 0;
					$objTMDC["reference2"]					= 0;
					$objTMDC["reference3"]					= 0;
					$objTMDC["reference4"]					= "";
					$objTMDC["reference5"]					= "";
					$objTMDC["reference9"]					= "reference1: Porcentaje de Gastos fijos para las facturas de credito,reference2: Escritura Publica,reference3: Primer Linea del Protocolo";						
					
				
					
					if($nuevoRegistro == true){	
						$this->Transaction_Master_Detail_Credit_Model->insert_app_posme($objTMDC);
					}
					else{			
						$this->Transaction_Master_Detail_Credit_Model->update_app_posme($transactionMasterDetailID,$objTMDC);
						
					}
					
					//Sumarizar Variable Totales
					$tax1Total								= $tax1Total + $tax1;
					$subAmountTotal							= $subAmountTotal + ($quantity * $price);
					$amountTotal							= $amountTotal + $objTMD["amount"];
					
					
					
				}
			}
			
			//Actualizar Transaction Master despues del detalle			
			$objTM["amount"] 	= $amountTotal;
			$objTM["tax1"] 		= $tax1Total;
			$objTM["subAmount"] = $subAmountTotal;			
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$objTM);
			
			//Aplicar el Documento
			//Las factuas de credito no se auto aplican auque este el parametro, por que hay que crer el documento
			//y esto debe ser revisado cuidadosamente
			if( $this->core_web_workflow->validateWorkflowStage("tb_transaction_master_billing","statusID",$objTM["statusID"],COMMAND_APLICABLE,$dataSession["user"]->companyID,$dataSession["user"]->branchID,$dataSession["role"]->roleID)){
				
				//Ingresar en Kardex.
				$this->core_web_inventory->calculateKardexNewOutput($companyID,$transactionID,$transactionMasterID);			
			
				//Crear Conceptos.
				$this->core_web_concept->billing($companyID,$transactionID,$transactionMasterID);
				
				//Si es al credito crear tabla de amortizacion				
				//si la factura es de credito
				if($esFacturaDeCredito == true){
					
					
					//Crear documento del modulo
					$objCustomerCreditLine 								= $this->Customer_Credit_Line_Model->get_rowByPK($objTM["reference4"]);
					$objCustomerCreditDocument["companyID"] 			= $companyID;
					$objCustomerCreditDocument["entityID"] 				= $objCustomerCreditLine->entityID;
					$objCustomerCreditDocument["customerCreditLineID"] 	= $objCustomerCreditLine->customerCreditLineID;
					$objCustomerCreditDocument["documentNumber"] 		= $objTM["transactionNumber"];
					$objCustomerCreditDocument["dateOn"] 				= $objTM["transactionOn"];
					$objCustomerCreditDocument["exchangeRate"] 			= $objTM["exchangeRate"];
					$objCustomerCreditDocument["term"] 					= $objCustomerCreditLine->term;
					$objCustomerCreditDocument["interes"] 				= $objCustomerCreditLine->interestYear;
					$objCustomerCreditDocument["amount"] 				= $amountTotal;
					$objCustomerCreditDocument["currencyID"] 			= $objTM["currencyID"];					
					$objCustomerCreditDocument["statusID"] 				= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_document","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
					$objCustomerCreditDocument["reference1"] 			= $objTM["note"];
					$objCustomerCreditDocument["reference2"] 			= "";
					$objCustomerCreditDocument["reference3"] 			= "";
					$objCustomerCreditDocument["isActive"] 				= 1;
					
					$objCustomerCreditDocument["providerIDCredit"] 		= $objTM["reference1"];
					$objCustomerCreditDocument["periodPay"]				= $objCustomerCreditLine->periodPay;
					$objCustomerCreditDocument["typeAmortization"] 		= $objCustomerCreditLine->typeAmortization;
					$objCustomerCreditDocument["balance"] 				= $amountTotal;
					$objCustomerCreditDocument["reportSinRiesgo"] 	 	= false;
					$customerCreditDocumentID 							= $this->Customer_Credit_Document_Model->insert_app_posme($objCustomerCreditDocument);
					$periodPay 											= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->periodPay);
					
					
					
					$objCatalogItem_DiasNoCobrables 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES",$companyID);
					$objCatalogItem_DiasFeriados365 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_365",$companyID);
					$objCatalogItem_DiasFeriados366 		= $this->core_web_catalog->getCatalogAllItemByNameCatalogo("CXC_NO_COBRABLES_FERIADOS_366",$companyID);
					$objCatalogItemDayExclude 				= $this->Catalog_Item_Model->get_rowByCatalogItemID($objCustomerCreditLine->dayExcluded);
						
					//Crear tabla de amortizacion
					$this->financial_amort->amort(
						$objCustomerCreditDocument["amount"], 		/*monto*/
						$objCustomerCreditDocument["interes"],		/*interes anual*/
						$objCustomerCreditDocument["term"],			/*numero de pagos*/	
						$periodPay->sequence,						/*frecuencia de pago en dia*/
						$objTM["transactionOn2"], 					/*fecha del credito*/	
						$objCustomerCreditLine->typeAmortization 	/*tipo de amortizacion*/,
						$objCatalogItem_DiasNoCobrables,
						$objCatalogItem_DiasFeriados365,
						$objCatalogItem_DiasFeriados366,
						$objCatalogItemDayExclude,
						$dataSession["company"]->flavorID
					);
					
					$tableAmortization = $this->financial_amort->getTable();
					if($tableAmortization["detail"])
					{
						$sequence = 0;
						foreach($tableAmortization["detail"] as $key => $itemAmortization){
							$sequence 												= $sequence + 1;
							$objCustomerAmoritizacion["customerCreditDocumentID"]	= $customerCreditDocumentID;
							$objCustomerAmoritizacion["balanceStart"]				= $itemAmortization["saldoInicial"];
							$objCustomerAmoritizacion["dateApply"]					= $itemAmortization["date"];
							$objCustomerAmoritizacion["interest"]					= $itemAmortization["interes"];
							$objCustomerAmoritizacion["capital"]					= $itemAmortization["principal"];
							$objCustomerAmoritizacion["share"]						= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["balanceEnd"]					= $itemAmortization["saldo"];
							$objCustomerAmoritizacion["remaining"]					= $itemAmortization["cuota"];
							$objCustomerAmoritizacion["dayDelay"]					= 0;
							$objCustomerAmoritizacion["note"]						= '';
							$objCustomerAmoritizacion["statusID"]					= $this->core_web_workflow->getWorkflowInitStage("tb_customer_credit_amoritization","statusID",$companyID,$branchID,$roleID)[0]->workflowStageID;
							$objCustomerAmoritizacion["isActive"]					= 1;
							$objCustomerAmoritizacion["sequence"]					= $sequence;
							$objCustomerAmortizationID 								= $this->Customer_Credit_Amortization_Model->insert_app_posme($objCustomerAmoritizacion);
						}
					}
					
					//Crear las personas relacionadas a la factura
					$objEntityRelated								= array();
					$objEntityRelated["customerCreditDocumentID"]	= $customerCreditDocumentID;
					$objEntityRelated["entityID"]					= $objCustomerCreditLine->entityID;
					$objEntityRelated["type"]						= $this->core_web_parameter->getParameter("CXC_PROPIETARIO_DEL_CREDITO",$companyID)->value;
					$objEntityRelated["typeCredit"]					= 401; /*comercial*/
					$objEntityRelated["statusCredit"]				= 429; /*activo*/
					$objEntityRelated["typeGarantia"]				= 444; /*pagare*/
					$objEntityRelated["typeRecuperation"]			= 450; /*recuperacion normal */
					$objEntityRelated["ratioDesembolso"]			= 1;
					$objEntityRelated["ratioBalance"]				= 1;
					$objEntityRelated["ratioBalanceExpired"]		= 1;
					$objEntityRelated["ratioShare"]					= 1;
					$objEntityRelated["isActive"]					= 1;
					$this->core_web_auditoria->setAuditCreated($objEntityRelated,$dataSession,$this->request);			
					$ccEntityID 	= $this->Customer_Credit_Document_Endity_Related_Model->insert_app_posme($objEntityRelated);
					
					//Calculo del Total en Dolares
					$amountTotalDolares	= $objTM["exchangeRate"] > 1 ? 
								/*factura en cordoba*/ ($amountTotal * round($objTM["exchangeRate"],4)) : 
								/*factura en dolares*/ ($amountTotal * 1 );
					
					
					//disminuir el balance de general					
					$objCustomerCredit 					= $this->Customer_Credit_Model->get_rowByPK($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID);
					$objCustomerCreditNew["balanceDol"]	= $objCustomerCredit->balanceDol - $amountTotalDolares;
					$this->Customer_Credit_Model->update_app_posme($objCustomerCreditLine->companyID,$objCustomerCreditLine->branchID,$objCustomerCreditLine->entityID,$objCustomerCreditNew);
					
					//disminuir el balance de linea
					if($objCustomerCreditLine->currencyID == $objCurrencyCordoba->currencyID)
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotal;
					else
						$objCustomerCreditLineNew["balance"]	= $objCustomerCreditLine->balance - $amountTotalDolares;
						
					//actualizar balance de la linea de credito del cliente
					$this->Customer_Credit_Line_Model->update_app_posme($objCustomerCreditLine->customerCreditLineID,$objCustomerCreditLineNew);
					
				}
				
				
			}
			
			if($db->transStatus() !== false){
				$db->transCommit();										
			}
			else{
				$db->transRollback();						
				throw new \Exception($this->db->_error_message());
				
			}
			
			
			
			return $this->response->setJSON(array(
				'error'   				=> false,
				'message' 				=> $transactionNumber,
				'transactionMasterID' 	=> $transactionMasterID,
				'transactionNumber' 	=> $transactionNumber,
				'companyID' 			=> $companyID,
				'transactionID' 		=> $transactionID
			));//--finjson	
			
		}
		catch(\Exception $ex){
			
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson	
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
			
			
			
			//reglas			
			$this->validation->setRule("txtStatusID","Estado","required|min_length[1]");
			$this->validation->setRule("txtDate","Fecha","required");
			
			//echo print_r($this->validation->withRequest($this->request)->run(),true);
			//echo print_r($this->validation->getErrors(),true);
			//echo print_r($this->validation->getError("txtStatusID"),true);
			
			 //Validar Formulario
			if(!$this->validation->withRequest($this->request)->run()){				
				
				$stringValidation = $this->core_web_tools->formatMessageError($this->validation->getErrors());	
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');
				exit;
			} 
			
			//Guardar o Editar Registro						
			if($mode == "new"){
				return $this->insertElement($dataSession);
			}
			else if ($mode == "edit"){
				return $this->updateElement($dataSession);
			}
			else{
				$stringValidation = "El modo de operacion no es correcto (new|edit)";
				$this->core_web_notification->set_message(true,$stringValidation);
				$this->response->redirect(base_url()."/".'app_invoice_billing/add');
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
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();


            $response = [
                'success' 	=> false,
                'error' 	=> [
                    'code' 		=> $ex->getLine(),
                    'message' 	=> $ex->getMessage()
                ],
                'data' 		=> [
                    'codigoMesero' => ''
                ]
            ];
            return $this->response->setJSON($response);
		}		
			
	}

    public function setSessionData(): ResponseInterface
    {
        $session = session();

        $session->set([
            'companyID' => $this->request->getPost('companyID'),
            'transactionID' => $this->request->getPost('transactionID'),
            'transactionMasterID' => $this->request->getPost('transactionMasterID'),
            'codigoMesero' => $this->request->getPost('codigoMesero'),
            'edicion' => true
        ]);

        return $this->response->setJSON(['status' => 'ok']);
    }

	function add(){ 
	
		try{ 
			
			
			//$this->cachePage( TIME_CACHE_APP  );			
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
			
			
			
			$companyID 							= $dataSession["user"]->companyID;
			$branchID 							= $dataSession["user"]->branchID;
			$roleID 							= $dataSession["role"]->roleID;
			$userID								= $dataSession["user"]->userID;
			$transactionMasterIDToPrinter		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterIDToPrinter");//--finuri
            $codigoMesero                       = array_key_exists('codigoMesero', $dataSession) ?  $dataSession['codigoMesero'] : helper_SegmentsValue($this->uri->getSegments(),"codigoMesero");
            $transactionID                      = array_key_exists('transactionID',$dataSession) ? $dataSession['transactionID'] : 19 /*FACTURA*/;
            $transactionMasterID                = array_key_exists('transactionMasterID', $dataSession) ? $dataSession['transactionMasterID'] : 0;
			
			//Obtener el componente de Item
			$objComponentCustomer	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_customer");
			if(!$objComponentCustomer)
			throw new \Exception("EL COMPONENTE 'tb_customer' NO EXISTE...");
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
		
			$objComponentTransactionBilling	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponentTransactionBilling)
			throw new \Exception("EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			$transactionIDNueva 					= $this->core_web_transaction->getTransactionID($dataSession["user"]->companyID,"tb_transaction_master_billing",0);			
			
			$objCurrency						= $this->core_web_currency->getCurrencyDefault($companyID);
			$targetCurrency						= $this->core_web_currency->getCurrencyExternal($companyID);						
			$objListPrice 						= $this->List_Price_Model->getListPriceToApply($companyID);
			$objListCurrency					= $this->Company_Currency_Model->getByCompany($companyID);
			
			
			if(!$objListPrice)
			throw new \Exception("NO EXISTE UNA LISTA DE PRECIO PARA SER APLICADA");
		
			
			$objListComanyParameter					= $this->Company_Parameter_Model->get_rowByCompanyID($companyID);
			$customerDefault						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_CLIENTDEFAULT");
			$objParameterInvoiceTypeEmployer		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_TYPE_EMPLOYEER");
			$objParameterInvoiceTypeEmployer		= $objParameterInvoiceTypeEmployer->value;			
			$objParameterInvoiceAutoApply			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_AUTOAPPLY_CASH");
			$objParameterInvoiceAutoApply			= $objParameterInvoiceAutoApply->value;
			$objParameterTypePreiceDefault			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_DEFAULT_TYPE_PRICE");
			$objParameterTypePreiceDefault			= $objParameterTypePreiceDefault->value;
			$objParameterTipoWarehouseDespacho		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_TYPE_WAREHOUSE_DESPACHO");
			$objParameterTipoWarehouseDespacho		= $objParameterTipoWarehouseDespacho->value;
			$objParameterImprimirPorCadaFactura		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PRINT_BY_INVOICE");
			$objParameterImprimirPorCadaFactura		= $objParameterImprimirPorCadaFactura->value;
			$objParameterScanerProducto				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_SHOW_POPUP_FIND_PRODUCTO_NOT_SCANER");
			$objParameterScanerProducto				= $objParameterScanerProducto->value;
			$objParameterCantidadItemPoup			= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_CANTIDAD_ITEM");
			$objParameterCantidadItemPoup			= $objParameterCantidadItemPoup->value;
			$objParameterHidenFiledItemNumber		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_HIDEN_ITEMNUMBER_IN_POPUP");
			$objParameterHidenFiledItemNumber		= $objParameterHidenFiledItemNumber->value;			
			$objParameterAmortizationDuranteFactura	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE");
			$objParameterAmortizationDuranteFactura	= $objParameterAmortizationDuranteFactura->value;
			$objParameterDirect 					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT")->value;
			$objParameterRestaurant 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_IS_RESTAURANT")->value;
			
			$objParameterAlturaDelModalDeSeleccionProducto	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_ALTO_MODAL_DE_SELECCION_DE_PRODUCTO_AL_FACTURAR");
			$objParameterAlturaDelModalDeSeleccionProducto	= $objParameterAlturaDelModalDeSeleccionProducto->value;			
			
			$objParameterScrollDelModalDeSeleccionProducto	= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_SCROLL_DE_MODAL_EN_SELECCION_DE_PRODUTO_AL_FACTURAR");
			$objParameterScrollDelModalDeSeleccionProducto	= $objParameterScrollDelModalDeSeleccionProducto->value;			
			
			
			//Obtener la lista de estados
			if($objParameterInvoiceAutoApply == "true"){
				$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowStageApplyFirst("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			}
			else{
				$dataView["objListWorkflowStage"]	= $this->core_web_workflow->getWorkflowInitStage("tb_transaction_master_billing","statusID",$companyID,$branchID,$roleID);
			}
			
			/** ZONAS O SALONES */
			$objPublicCatalogIdZonas					= 0;
			$objPubliCatalogZonasConfig 				= $this->Public_Catalog_Model->asObject()
															->where("systemName","tb_transaction_master_billing.zone_x_meseros")
															->where("isActive",1)
															->where("flavorID",$dataSession["company"]->flavorID)
															->find();
			
			if($codigoMesero != "none" && !$objPubliCatalogZonasConfig )
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE ZONAS tb_transaction_master_billing.zone_x_meseros");
			}
			
			$objPublicCatalogIdZonas					= $codigoMesero == "none" ? 0 : $objPubliCatalogZonasConfig[0]->publicCatalogID;
			$objPubliCatalogDetailZonasConfiguradas		= $this->Public_Catalog_Detail_Model->asObject()
																->where("publicCatalogID",$objPublicCatalogIdZonas)
																->where( "isActive",1)	
																->where( "name",$codigoMesero)
																->findAll();

			/** MESAS O UBICACIONES DE ZONAS */							
			$objPublicCatalogId							= 0;
			$objPubliCatalogMesasConfig 				= $this->Public_Catalog_Model->asObject()
																	->where("systemName","tb_transaction_master_billing.mesas_x_meseros")
																	->where("isActive",1)
																	->where("flavorID",$dataSession["company"]->flavorID)
																	->find();
			
			if($codigoMesero != "none" && !$objPubliCatalogMesasConfig )
			{
				throw new \Exception("CONFIGURAR EL CATALOGO DE MESAS tb_transaction_master_billing.mesas_x_meseros");
			}
			
			$objPublicCatalogId							= $codigoMesero == "none" ? 0 : $objPubliCatalogMesasConfig[0]->publicCatalogID;
			$objPubliCatalogDetailMesasConfiguradas		= $this->Public_Catalog_Detail_Model->asObject()
																->where("publicCatalogID",$objPublicCatalogId)
																->where( "isActive",1)	
																->where( "name",$codigoMesero)
																->findAll();
			
			
			//Tipo de Factura
			$agent 											= $this->request->getUserAgent();						
			$dataView["isMobile"]							= $dataSession["user"]->useMobile;
			$dataView["objComponentTransactionBilling"]		= $objComponentTransactionBilling;
			$dataView["companyID"]							= $dataSession["user"]->companyID;
			$dataView["isAdmin"]							= $dataSession["role"]->isAdmin;
			$dataView["userID"]								= $dataSession["user"]->userID;
			$dataView["userName"]							= $dataSession["user"]->nickname;
			$dataView["useMobile"]							= $dataSession["user"]->useMobile;
			$dataView["roleID"]								= $dataSession["role"]->roleID;
			$dataView["roleName"]							= $dataSession["role"]->name;
			$dataView["branchID"]							= $dataSession["branch"]->branchID;
			$dataView["branchName"]							= $dataSession["branch"]->name;
			$dataView["company"]							= $dataSession["company"];
			$dataView["exchangeRate"]						= $this->core_web_currency->getRatio($companyID,date("Y-m-d"),1,$targetCurrency->currencyID,$objCurrency->currencyID);			
			$dataView["listCurrency"]						= $objListCurrency;
			$dataView["objCurrency"]						= $objCurrency;
			$dataView["objListPrice"]						= $objListPrice;
			$dataView["objListEmployee"]					= $this->Employee_Model->get_rowByBranchIDAndType($companyID,$branchID, $objParameterInvoiceTypeEmployer );
			$dataView["objListBank"]						= $this->Bank_Model->getByCompany($companyID);
			$dataView["objComponentItem"]					= $objComponentItem;
			$dataView["objComponentCustomer"]				= $objComponentCustomer;
			$dataView["objCaudal"]							= $this->Transaction_Causal_Model->getCausalByBranch($companyID,$transactionID,$branchID);			
			$dataView["warehouseID"]						= $dataView["objCaudal"][0]->warehouseSourceID;
			$dataView["objListWarehouse"]					= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$userID);			
			$dataView["objCustomerDefault"]					= $this->Customer_Model->get_rowByCode($companyID,$customerDefault->value);
			$dataView["objListTypePrice"]					= $this->core_web_catalog->getCatalogAllItem("tb_price","typePriceID",$companyID);
			$dataView["objListZone"]						= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","zoneID",$companyID);
			$dataView["objListMesa"]						= $this->core_web_catalog->getCatalogAllItem("tb_transaction_master_info_billing","mesaID",$companyID);
			$dataView['transactionID']						= $transactionID;
			$dataView['transactionIDNueva']					= $transactionIDNueva;
			$dataView['transactionMasterID']				= empty($transactionMasterID) ? 0 : $transactionMasterID;
			$dataView['objParameterRestaurant']				= $objParameterRestaurant;
			if($objParameterRestaurant=='true'){
				$catalogItemIdZonas							= array_column($dataView["objListZone"],'catalogItemID');
				$dataView['objListZone']					= $this->Transaction_Master_Model->get_ZonasByCatalogItemID($catalogItemIdZonas);
				$catalogItemIdMesas							= array_column($dataView["objListMesa"],'catalogItemID');
				$dataView['objListMesa']					= $this->Transaction_Master_Model->get_MesasByCatalogItemID($catalogItemIdMesas);
			}
			
			/** FILTRAR LAS ZONAS O SALONES DONDEL MESERO TIENE PERMISO */
			$listZonasByMesero = array_map(function($item) {
				return $item->display;
			}, $objPubliCatalogDetailZonasConfiguradas);

			
			$listZonaFiltradas = array_filter($dataView["objListZone"] , function($item) use ($listZonasByMesero) {
				return in_array($item->name, $listZonasByMesero);
			});

			$dataView["objListZone"] = $codigoMesero == "none" ? $dataView["objListZone"]  : $listZonaFiltradas;
			if(!$dataView["objListZone"])
			throw new \Exception("NO ES POSIBLE CONTINUAR, CONFIGURAR CATALOGO ZONAS");	

			//Filtrar la lista de mesas que el mesero tiene permiso
			$listMesasByMesero = array_map(function($item) {
				return $item->display;
			}, $objPubliCatalogDetailMesasConfiguradas);

			
			$listMesaFiltradas = array_filter($dataView["objListMesa"] , function($item) use ($listMesasByMesero) {
				return in_array($item->name, $listMesasByMesero);
			});

			$dataView["objListMesa"] = $codigoMesero == "none" ? $dataView["objListMesa"]  : $listMesaFiltradas;
			if(!$dataView["objListMesa"])
			throw new \Exception("NO ES POSIBLE CONTINUAR CONFIGURAR CATALOGO MESAS");
			
			
			$dataView["objParameterINVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE"] 						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_OPEN_CASH_WHEN_PRINTER_INVOICE")->value;
			$dataView["codigoMesero"]						= $codigoMesero;
			$dataView["objListPay"]							= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","periodPay",$companyID);
			$dataView["objListDayExcluded"]					= $this->core_web_catalog->getCatalogAllItem("tb_customer_credit_line","dayExcluded",$companyID);
			$dataView["listProvider"]						= $this->Provider_Model->get_rowByCompany($companyID);
			$dataView["objListaPermisos"]					= $dataSession["menuHiddenPopup"];
			$dataView["objParameterTypePreiceDefault"] 		= $objParameterTypePreiceDefault;
			$dataView["objParameterTipoWarehouseDespacho"] 	= $objParameterTipoWarehouseDespacho;
			$dataView["objParameterInvoiceAutoApply"] 		= $objParameterInvoiceAutoApply;
			$dataView["objParameterImprimirPorCadaFactura"] = $objParameterImprimirPorCadaFactura;
			$dataView["objParameterScanerProducto"] 		= $objParameterScanerProducto;
			$dataView["objParameterCantidadItemPoup"] 			= $objParameterCantidadItemPoup;
			$dataView["objParameterHidenFiledItemNumber"] 		= $objParameterHidenFiledItemNumber;
			$dataView["objParameterAmortizationDuranteFactura"] = $objParameterAmortizationDuranteFactura;
			$dataView["objParameterAlturaDelModalDeSeleccionProducto"] 	= $objParameterAlturaDelModalDeSeleccionProducto;
			$dataView["objParameterScrollDelModalDeSeleccionProducto"] 	= $objParameterScrollDelModalDeSeleccionProducto;
			$dataView["objParameterCXC_PLAZO_DEFAULT"]												= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_PLAZO_DEFAULT")->value;
			$dataView["objParameterCXC_DAY_EXCLUDED_IN_CREDIT"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_DAY_EXCLUDED_IN_CREDIT")->value;
			$dataView["objParameterINVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PARAMTER_AMORITZATION_DURAN_INVOICE")->value;
			$dataView["objParameterCXC_FRECUENCIA_PAY_DEFAULT"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CXC_FRECUENCIA_PAY_DEFAULT")->value;
			$dataView["objParameterCustomPopupFacturacion"]											= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_VIEW_CUSTOM_PANTALLA_DE_FACTURACION_POPUP_SELECCION_PRODUCTO_FORMA_MOSTRAR")->value;
			$dataView["objParameterINVOICE_BILLING_APPLY_TYPE_PRICE_ON_DAY_POR_MAYOR"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_APPLY_TYPE_PRICE_ON_DAY_POR_MAYOR")->value;
			$dataView["objParameterINVOICE_BILLING_SHOW_COMMAND_BAR"]								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SHOW_COMMAND_BAR")->value;
			$dataView["objParameterINVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_BAR"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_BAR")->value;
			$dataView["objParameterINVOICE_BILLING_PRINTER_DIRECT_URL_BAR"]							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_URL_BAR")->value;
			$dataView["objParameterobjParameterINVOICE_BILLING_PRINTER_URL_BAR"]					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_URL_BAR")->value;					
			$dataView["objParameterINVOICE_BILLING_EMPLOYEE_DEFAULT"]								= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_EMPLOYEE_DEFAULT")->value;
			$dataView["objParameterINVOICE_BILLING_SELECTITEM"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SELECTITEM")->value;
			$dataView["objParameterACCOUNTING_CURRENCY_NAME_IN_BILLING"]							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"ACCOUNTING_CURRENCY_NAME_IN_BILLING")->value;
			$dataView["objParameterINVOICE_BILLING_VALIDATE_EXONERATION"]							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_VALIDATE_EXONERATION")->value;
			$dataView["objParameterINVOICE_SHOW_FIELD_PESO"]										= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_SHOW_FIELD_PESO")->value;
			$dataView["objListParameterJavaScript"]													= $this->core_web_parameter->getParameterAllToJavaScript($companyID);			
			$dataView["urlPrinterDocument"]															= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_URL_PRINTER")->value;
			
			
			$objParameterUrlServidorDeImpresion							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_SERVER_PATH");
			$objParameterUrlServidorDeImpresion							= $objParameterUrlServidorDeImpresion->value;
			$dataView["objParameterUrlServidorDeImpresion"] 			= $objParameterUrlServidorDeImpresion;
			
			$objParameterImprimirPorCadaFactura							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PRINT_BY_INVOICE");
			$dataView["objParameterImprimirPorCadaFactura"]				= $objParameterImprimirPorCadaFactura->value;
			
			$objParameterInvoiceBillingPrinterDirect					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT");
			$dataView["objParameterInvoiceBillingPrinterDirect"]		= $objParameterInvoiceBillingPrinterDirect->value;
			$objParameterInvoiceBillingPrinterDirectUrl					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_PRINTER_DIRECT_URL");
			$dataView["objParameterInvoiceBillingPrinterDirectUrl"]		= $objParameterInvoiceBillingPrinterDirectUrl->value;
			
			
			$objParameterInvoiceBillingQuantityZero						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_QUANTITY_ZERO");
			$dataView["objParameterInvoiceBillingQuantityZero"]			= $objParameterInvoiceBillingQuantityZero->value;
			$objParameterRegresarAListaDespuesDeGuardar					= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SAVE_AFTER_TO_LIST");
			$dataView["objParameterRegresarAListaDespuesDeGuardar"]		= $objParameterRegresarAListaDespuesDeGuardar->value;
			
			$objParameterMostrarImagenEnSeleccion						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_SHOW_IMAGE_IN_DETAIL_SELECTION");
			$objParameterMostrarImagenEnSeleccion						= $objParameterMostrarImagenEnSeleccion->value;	
			$dataView["objParameterMostrarImagenEnSeleccion"] 			= $objParameterMostrarImagenEnSeleccion;
			
			$objParameterPantallaParaFacturar							= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_PANTALLA_FACTURACION");
			$objParameterPantallaParaFacturar							= $objParameterPantallaParaFacturar->value;
			$dataView["objParameterPantallaParaFacturar"] 				= $objParameterPantallaParaFacturar;
			
			$objParameterEsResrarante									= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_IS_RESTAURANT");
			$objParameterEsResrarante									= $objParameterEsResrarante->value;
			$dataView["objParameterEsResrarante"] 						= $objParameterEsResrarante;
			
						
			if(!$dataView["objCustomerDefault"])
			throw new \Exception("NO EXISTE EL CLIENTE POR DEFECTO");
			
			$dataView["objNaturalDefault"]		= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objLegalDefault"]		= $this->Legal_Model->get_rowByPK($companyID,$dataView["objCustomerDefault"]->branchID,$dataView["objCustomerDefault"]->entityID);
			$dataView["objEmployeeNatural"]		= $this->Natural_Model->get_rowByPK($companyID,$dataSession["user"]->branchID,$dataSession["user"]->employeeID);
			
			//Obtener la categoria de prodcutos y la lista de prodcutos si es restaurante
			$dataView["objListInventoryCategoryRestaurant"]	 = NULL;
			$dataView["objListInventoryItemsRestaurant"]	 = NULL;
			if($objParameterEsResrarante == "true")
			{
				$parameterView["{companyID}"]			= $this->session->get('user')->companyID;
				$parameterView["{currencyID}"] 			= $objCurrency->currencyID;
				$parameterView["{warehouseID}"]			= $dataView["warehouseID"];
				$parameterView["{listPriceID}"]			= $objListPrice->listPriceID;				
				$parameterView["{useMobile}"]			= $this->session->get('user')->useMobile;
				$parameterView["{fnCallback}"] 			= "";
				$parameterView["{typePriceID}"] 		= $objParameterTypePreiceDefault;				
				$parameterView["{iDisplayStartDB}"]		= "0";
				$parameterView["{iDisplayLength}"]		= $objParameterCantidadItemPoup;
				$parameterView["{sSearchDB}"]			= "";
				$parameterView["{isWindowForm}"]		= "0";
				
				
			
			
				$dataView["objListInventoryCategoryRestaurant"]  =  $this->Itemcategory_Model->getByCompany($companyID);
				$dataView["objListInventoryItemsRestaurant"]	 = 	$this->core_web_view->getViewByName(
						$this->session->get('user'),
						$objComponentItem->componentID,
						"SELECCIONAR_ITEM_BILLING_POPUP_INVOICE_RESTAURANT",
						CALLERID_SEARCH,
						null,
						$parameterView
				)["view_data"];
				
										
			}
			
			
			//Obtener la linea de credito del cliente por defecto
			$objCurrencyDolares						= $this->core_web_currency->getCurrencyExternal($companyID);
			$objCurrencyCordoba						= $this->core_web_currency->getCurrencyDefault($companyID);			
			$parameterCausalTypeCredit 				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"INVOICE_BILLING_CREDIT");			
			$objCustomerCreditAmoritizationAll		= $this->Customer_Credit_Amortization_Model->get_rowByCustomerID($dataView["objCustomerDefault"]->entityID);
			$objListCustomerCreditLine 				= $this->Customer_Credit_Line_Model->get_rowByEntityBalanceMayorCero($companyID,$dataSession["user"]->branchID,$dataView["objCustomerDefault"]->entityID);			
			
			
			$dataView["objListCustomerCreditLine"]	  		=  $objListCustomerCreditLine;				
			$dataView["objCausalTypeCredit"]				=  $parameterCausalTypeCredit;
			$dataView["objCurrencyDolares"] 				=  $objCurrencyDolares;
			$dataView["objCurrencyCordoba"] 				=  $objCurrencyCordoba;
			$dataView["objCustomerCreditAmoritizationAll"] 	=  $objCustomerCreditAmoritizationAll;
			
			//Obtener los datos de impresion				
			if($transactionMasterIDToPrinter > 0 && $objParameterDirect  == "true")
			{	
				$dataPostPrinter["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterIDToPrinter);
				$dataPostPrinter["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterIDToPrinter);
				$dataPostPrinter["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterIDToPrinter);
				$dataPostPrinter["objTransactionMasterDetailReferences"]	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterIDToPrinter,$objComponentItem->componentID);
				$dataPostPrinter["objTransactionMasterDetailWarehouse"]		= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterIDToPrinter);
				$dataPostPrinter["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterIDToPrinter,$objComponentItem->componentID);
				$dataPostPrinter["objComponentCompany"]				= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataPostPrinter["objParameterLogo"]				= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_COMPANY_LOGO");
				$dataPostPrinter["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_PROPIETARY_PHONE");
				$dataPostPrinter["objCompany"] 						= $this->Company_Model->get_rowByPK($companyID);			
				$dataPostPrinter["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataPostPrinter["objTransactionMaster"]->createdAt,$dataPostPrinter["objTransactionMaster"]->createdBy);
				$dataPostPrinter["Identifier"]						= $this->core_web_parameter->getParameterFiltered($objListComanyParameter,"CORE_COMPANY_IDENTIFIER");
				$dataPostPrinter["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataPostPrinter["objTransactionMaster"]->branchID);
				$dataPostPrinter["objTipo"]							= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataPostPrinter["objTransactionMaster"]->transactionID,$dataPostPrinter["objTransactionMaster"]->transactionCausalID);
				$dataPostPrinter["objMesa"]							= $this->Catalog_Item_Model->get_rowByCatalogItemID($dataPostPrinter["objTransactionMasterInfo"]->mesaID);
				$dataPostPrinter["objCustumer"]						= $this->Customer_Model->get_rowByEntity($companyID,$dataPostPrinter["objTransactionMaster"]->entityID);
				$dataPostPrinter["objCurrency"]						= $this->Currency_Model->get_rowByPK($dataPostPrinter["objTransactionMaster"]->currencyID);
				$dataPostPrinter["prefixCurrency"]					= $dataPostPrinter["objCurrency"]->simbol." ";
				$dataPostPrinter["cedulaCliente"] 					= $dataPostPrinter["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataPostPrinter["objCustumer"]->customerNumber :  $dataPostPrinter["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataPostPrinter["nombreCliente"] 					= $dataPostPrinter["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataPostPrinter["objCustumer"]->firstName : $dataPostPrinter["objTransactionMasterInfo"]->referenceClientName ;
				$dataPostPrinter["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataPostPrinter["objTransactionMaster"]->statusID);
				$serializedDataPostPrinter 							= serialize($dataPostPrinter);
				$serializedDataPostPrinter 							= base64_encode($serializedDataPostPrinter);
				$dataView["dataPrinterLocal"]						= $serializedDataPostPrinter;
				$dataView["dataPrinterLocalTransactionMasterID"]	= $dataPostPrinter["objTransactionMaster"]->transactionMasterID;
				$dataView["dataPrinterLocalTransactionID"]			= $dataPostPrinter["objTransactionMaster"]->transactionID;
				$dataView["dataPrinterLocalCompanyID"]				= $dataPostPrinter["objTransactionMaster"]->companyID;
				$dataView["transactionMasterIDToPrinter"] 			= $transactionMasterIDToPrinter;
			
			}
			else 
			{
				$dataView["transactionMasterIDToPrinter"] 			= $transactionMasterIDToPrinter;
				$dataView["dataPrinterLocal"]						= "";
				$dataView["dataPrinterLocalTransactionMasterID"]	= 0;
				$dataView["dataPrinterLocalTransactionID"]			= 0;
				$dataView["dataPrinterLocalCompanyID"]				= 0;
			}
			

            //Variable para validar si es un mesero
            $esMesero 					= false;
            $eliminarProductos 			= false;
            $esMesero 					= $this->core_web_permission->urlPermited("es_mesero","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
            $eliminarProductos 		    = $this->core_web_permission->urlPermited("no_permitir_eliminar_productos_de_factura","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

            $esMesero					        = !$esMesero ? "0" : $esMesero;
            $esMesero					        = $dataSession["role"]->isAdmin ? "0" : $esMesero;
            $eliminarProductos                  = !$eliminarProductos ? "0" : $eliminarProductos;
            $eliminarProductos					= $dataSession["role"]->isAdmin ? "0" : $eliminarProductos;
            $dataView["esMesero"]	            = $esMesero;
            $dataView["eliminarProducto"]	    = $eliminarProductos;
			
			//Renderizar Resultado 
			$dataSession["notification"]	= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]			= $this->core_web_notification->get_message();
			$dataSession["head"]			= /*--inicio view*/ view('app_invoice_billing/news_head',$dataView);//--finview
			$dataSession["body"]			= /*--inicio view*/ view('app_invoice_billing/news_body',$dataView);//--finview
			$dataSession["script"]			= /*--inicio view*/ view('app_invoice_billing/news_script',$dataView);//--finview
			$dataSession["footer"]			= "";
            session()->remove(['transactionMasterID', 'viaBoton']);
			//return view("core_masterpage/default_masterpage",$dataSession);//--finview-r
			return view("core_masterpage/default_popup",$dataSession);//--finview-r	
			
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
	function index($dataViewID = null,$fecha = null){	
		try{
			//AUTENTICADO
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession			= $this->session->get();
			
			
			$dataSession["lastUrl"] = base_url()."/"."app_invoice_billing/index";
			$this->session->set($dataSession);
			
			
		 
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
			$cache 				= Services::cache();
			$objComponent		= $this->core_web_tools->getComponentIDBy_ComponentName("tb_transaction_master_billing");
			if(!$objComponent)
			throw new \Exception("00409 EL COMPONENTE 'tb_transaction_master_billing' NO EXISTE...");
			
			//$this->dompdf->loadHTML("<h1>hola mundo</h1>");
			//$this->dompdf->setPaper('A4','portrait');
			//$this->dompdf->render();
			//$this->dompdf->stream();
			$objHoy 	= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));
			$objFecha 	= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));
			$fecha		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"fecha");//--finuri
			$fecha		= !$fecha ? $objFecha->format("Y-m-d"): $fecha;
			$objFecha 	= \DateTime::createFromFormat('Y-m-d',$fecha);  				
			
			if( $objFecha <  $objHoy )
			{
				$this->cachePage( TIME_CACHE_APP );	
			}
			
			$objParameterMeseroScreenIndividual		= $this->core_web_parameter->getParameter("INVOICE_BILLING_MESERO_SCREEN_INDIVIDUAL",$this->session->get('user')->companyID);
			$objParameterMeseroScreenIndividual		= $objParameterMeseroScreenIndividual->value;
			
			
			$objParameterShowPreview		= $this->core_web_parameter->getParameter("INVOICE_SHOW_PREVIEW_INLIST",$this->session->get('user')->companyID);
			$objParameterShowPreview		= $objParameterShowPreview->value;
			$dataViewID 					= helper_SegmentsValue($this->uri->getSegments(), "dataViewID");
			
			
			$dataViewIDCache			= $cache->get('app_invoice_billing_dataviewid_index');
			if($dataViewIDCache && $dataViewID == null )
				$dataViewID = $dataViewIDCache;

            $targetComponentID			= $this->session->get('company')->flavorID;
			//Vista por defecto 
			if($dataViewID == null || $dataViewID == "null" ){
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$parameter["{userID}"]		= $this->session->get('user')->userID;
				$parameter["{fecha}"]		= $fecha;					
				$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);							
				if(!$dataViewData){
					
					$targetComponentID			= 0;	
					$parameter["{companyID}"]	= $this->session->get('user')->companyID;
					$parameter["{fecha}"]		= $fecha;
					$parameter["{userID}"]		= $this->session->get('user')->userID;
					$dataViewData				= $this->core_web_view->getViewDefault($this->session->get('user'),$objComponent->componentID,CALLERID_LIST,$targetComponentID,$resultPermission,$parameter);				
				}
				
				$cache->save('app_invoice_billing_dataviewid_index', $dataViewData["view_config"]->dataViewID, TIME_CACHE_APP);
				if($dataSession["user"]->useMobile == "1")
				{					
					//$dataViewRender			= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFildMobile($dataViewData,'ListView',"fnTableSelectedRow");
				}
				else
				{
					//$dataViewRender			= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
					$dataViewRender				= $this->core_web_view->renderGreedWithHtmlInFild($dataViewData,'ListView',"fnTableSelectedRow");
				}
			}
			//Otra vista
			else
			{
				
				$cache->save('app_invoice_billing_dataviewid_index', $dataViewID, TIME_CACHE_APP);				
				$parameter["{companyID}"]	= $this->session->get('user')->companyID;
				$parameter["{userID}"]		= $this->session->get('user')->userID;
				$parameter["{fecha}"]		= $fecha;
				$dataViewData				= $this->core_web_view->getViewBy_DataViewID($this->session->get('user'),$objComponent->componentID,$dataViewID,CALLERID_LIST,$resultPermission,$parameter, $targetComponentID);

				if($dataSession["user"]->useMobile == "1")
				{
					$dataViewRender				= $this->core_web_view->renderGreedMobile($dataViewData,'ListView',"fnTableSelectedRow");
				}
				else 
				{
					$dataViewRender				= $this->core_web_view->renderGreed($dataViewData,'ListView',"fnTableSelectedRow");
				}
			} 
			 
			 //Factura prerender en la lista principal
			$objParameterPantallaParaFacturar		= $this->core_web_parameter->getParameter("INVOICE_PANTALLA_FACTURACION",$this->session->get('user')->companyID);
			$objParameterPantallaParaFacturar		= $objParameterPantallaParaFacturar->value;
			$urlPrinterDocument						= $this->core_web_parameter->getParameter("INVOICE_URL_PRINTER",$this->session->get('user')->companyID);
			$urlPrinterDocumentDirect				= $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_URL",$this->session->get('user')->companyID);
			$iframePreviewReport					= "";
			
			if($objParameterShowPreview == "true" &&  $dataViewData)
			{			
				foreach($dataViewData["view_data"] as $key => $value)
				{					
				    $pathScaner 					= "./resource/file_company/"."company_2/component_48/component_item_".$value["transactionMasterID"];					
					$value["exiteFileInFolder"] 	= false;
					$value["fileName"] 				= false;
					$value["urlPrinterDocument"] 		= $urlPrinterDocument->value;
					$value["urlPrinterDocumentDirect"] 	= $urlPrinterDocumentDirect->value;
					
					if(file_exists($pathScaner))
					{
						$value["arrayFileInFolder"] = scandir ($pathScaner, SCANDIR_SORT_DESCENDING );						
						if($value["Estado"] == "APLICADA" && $value["arrayFileInFolder"] )
						{
							if(count($value["arrayFileInFolder"]) > 2)
							{
								$value["exiteFileInFolder"]	 	= true;						
								$value["fileName"]				= $value["arrayFileInFolder"][0];
							}						
						}					
					}					
					
					$iframePreviewReport = $iframePreviewReport.view('core_view_fragmentos/app_invoice_billing_index_iframe',$value);
				}
			}
			
			//Variable para validar si es un mesero
			$esMesero 					= false;
            $eliminarProductos 			= false;
			$esMesero 					= $this->core_web_permission->urlPermited("es_mesero","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);
			$eliminarProductos 		    = $this->core_web_permission->urlPermited("no_permitir_eliminar_productos_de_factura","index",URL_SUFFIX,$dataSession["menuTop"],$dataSession["menuLeft"],$dataSession["menuBodyReport"],$dataSession["menuBodyTop"],$dataSession["menuHiddenPopup"]);

			$esMesero					        				= !$esMesero ? "0" : $esMesero;
			$esMesero					        				= $dataSession["role"]->isAdmin ? "0" : $esMesero;
            $eliminarProductos                  				= !$eliminarProductos ? "0" : $eliminarProductos;
            $eliminarProductos									= $dataSession["role"]->isAdmin ? "0" : $eliminarProductos;
			$dataViewJava["esMesero"]	        				= $esMesero;
			$dataViewJava["eliminarProductos"]					= $eliminarProductos;
			$dataViewJava["objParameterMeseroScreenIndividual"] = $objParameterMeseroScreenIndividual;
			$dataViewJava["objPasswordMesero"] 					= $dataSession["user"]->password;

			//Renderizar Resultado
			$dataViewJava["objParameterPantallaParaFacturar"]	= $objParameterPantallaParaFacturar;
			$dataViewJava["objParameterShowPreview"]			= $objParameterShowPreview;
			$dataViewJava["useMobile"]							= $dataSession["user"]->useMobile;
			
			$dataViewHeader["company"]							= $dataSession["company"];
			$dataViewHeader["objFecha"] 						= $objFecha;
			$dataViewHeader["objParameterShowPreview"] 			= $objParameterShowPreview;
            $dataViewHeader["useMobile"]						= $dataSession["user"]->useMobile;
			
			$dataViewFooter["objFecha"] 						= $objFecha;
			$dataViewFooter["objParameterShowPreview"] 			= $objParameterShowPreview;
			$dataViewFooter["iframePreviewReport"]				= $iframePreviewReport;
			
			
			$dataSession["useMobile"]							= $dataSession["user"]->useMobile;
			$dataSession["notification"]						= $this->core_web_error->get_error($dataSession["user"]->userID);
			$dataSession["message"]								= $this->core_web_notification->get_message();
			$dataSession["head"]								= /*--inicio view*/ view('app_invoice_billing/list_head',$dataViewHeader);//--finview
			$dataSession["footer"]								= /*--inicio view*/ view('app_invoice_billing/list_footer',$dataViewFooter);//--finview
			$dataSession["body"]								= $dataViewRender; 			
			$dataSession["script"]								= /*--inicio view*/ view('app_invoice_billing/list_script',$dataViewJava);//--finview
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
	
	
	function viewPrinterOpen(){
		try{			
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",APP_COMPANY);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinterOpen();
			
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
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mm(){
		try{
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$fromServer				= /*inicio get post*/ $this->request->getPost("fromServer");
			
			if($fromServer == "")
			{
				
				$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailReferences"]	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID);
				$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
				
				
				$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
				$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
				$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
				$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
				$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
				$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
				$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
				$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
				$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
				$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
				$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			}
			else 
			{
				// Decodificar la cadena Base64
				$serializedData = base64_decode($fromServer);
			
				// Deserializar la cadena a un array
				$serializedData = unserialize($serializedData);			
			
				$dataView	= $serializedData;
			}
			
			
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mm($dataView);
			log_message("error","impresion elaborada");
			
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmBarExit(){
		try{
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$fromServer				= /*inicio get post*/ $this->request->getPost("fromServer");
			
			if($fromServer == "")
			{
				
				$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailReferences"]	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID);
				$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
				
				
				$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
				$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
				$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
				$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
				$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
				$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
				$dataView["objMesa"]						= $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objTransactionMasterInfo"]->mesaID);
				$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
				$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
				$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
				$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
				$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			}
			else 
			{
				// Decodificar la cadena Base64
				$serializedData = base64_decode($fromServer);
			
				// Deserializar la cadena a un array
				$serializedData = unserialize($serializedData);			
			
				$dataView	= $serializedData;
			}
			
			
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			
			if (str_starts_with($dataView["objTransactionMaster"]->transactionNumber, "PRF")) 
			{
				$this->core_web_printer_direct->executePrinter80mmBarExitCuenta($dataView);
			} 
			else 
			{				
				$this->core_web_printer_direct->executePrinter80mmBarExitOrignal($dataView);
				$this->core_web_printer_direct->executePrinter80mmBarExitCopia($dataView);				
			}


			log_message("error","impresion elaborada");
			
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
	
	function viewPrinterDirectFactura80mmDistribuidoraRD(){
		try{
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$fromServer				= /*inicio get post*/ $this->request->getPost("fromServer");
			
			if($fromServer == "")
			{
				
				$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailReferences"]	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID);
				$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
				
				
				$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
				$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
				$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
				$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
				$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
				$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
				$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
				$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
				$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
				$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
				$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			}
			else 
			{
				// Decodificar la cadena Base64
				$serializedData = base64_decode($fromServer);
			
				// Deserializar la cadena a un array
				$serializedData = unserialize($serializedData);			
			
				$dataView	= $serializedData;
			}
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmDistribuidoraRD($dataView);
			log_message("error","impresion elaborada");
			
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
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmBarMilekin(){
		try{
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$fromServer				= /*inicio get post*/ $this->request->getPost("fromServer");
			
			if($fromServer == "")
			{
				
				$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
				
				
				$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
				$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
				$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
				$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
				$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
				$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
				$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
				$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
				$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
				$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
				$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			}
			else 
			{
				// Decodificar la cadena Base64
				$serializedData = base64_decode($fromServer);
			
				// Deserializar la cadena a un array
				$serializedData = unserialize($serializedData);			
			
				$dataView	= $serializedData;
			}
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmBarMilekin($dataView);
			log_message("error","impresion elaborada");
			
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
	
	
	
	function viewPrinterDirectFactura80mmPizzaLaus(){
		try{
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$fromServer				= /*inicio get post*/ $this->request->getPost("fromServer");
			
			if($fromServer == "")
			{
				
				$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailReferences"]	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID);
				$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
				
				
				$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
				$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
				$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
				$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
				$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
				$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
				$dataView["objMesa"]						= $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objTransactionMasterInfo"]->mesaID);
				$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
				$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
				$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
				$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
				$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			}
			else 
			{
				// Decodificar la cadena Base64
				$serializedData = base64_decode($fromServer);
			
				// Deserializar la cadena a un array
				$serializedData = unserialize($serializedData);			
			
				$dataView		= $serializedData;
			}
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmPizzaLaus($dataView);
			log_message("error","impresion elaborada");
			
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
	
	function viewPrinterDirectFactura80mmCafeRetorno(){
		try{
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$fromServer				= /*inicio get post*/ $this->request->getPost("fromServer");
			
			if($fromServer == "")
			{
				
				$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailReferences"]	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID);
				$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
				
				
				$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
				$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
				$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
				$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
				$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
				$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
				$dataView["objMesa"]						= $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objTransactionMasterInfo"]->mesaID);
				$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
				$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
				$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
				$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
				$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			}
			else 
			{
				// Decodificar la cadena Base64
				$serializedData = base64_decode($fromServer);
			
				// Deserializar la cadena a un array
				$serializedData = unserialize($serializedData);			
			
				$dataView	= $serializedData;
			}
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCafeRetorno($dataView);
			log_message("error","impresion elaborada");
			
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
	
	function viewPrinterDirectBar80mmCafeRetorno(){
		try{
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
			
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$fromServer				= /*inicio get post*/ $this->request->getPost("fromServer");
			
			if($fromServer == "")
			{
				
				$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailReferences"]	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID);
				$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
				$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
				
				
				$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
				$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
				$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
				$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
				$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
				$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
				$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
				$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
				$dataView["objMesa"]						= $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objTransactionMasterInfo"]->mesaID);
				$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
				$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
				$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
				$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
				$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
				$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			}
			else 
			{
				// Decodificar la cadena Base64
				$serializedData = base64_decode($fromServer);
			
				// Deserializar la cadena a un array
				$serializedData = unserialize($serializedData);			
			
				$dataView	= $serializedData;
			}
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCafeRetorno($dataView);
			log_message("error","impresion elaborada");
			
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
	
	
	
	function viewPrinterDirectFactura80mmBlueMoon(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objEmployerNaturales"]			= $this->Natural_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID,$dataView["objTransactionMaster"]->entityIDSecondary);
			$dataView["objMesa"]						= $this->Catalog_Item_Model->get_rowByCatalogItemID($dataView["objTransactionMasterInfo"]->mesaID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmBlueMoon($dataView);
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmBlueMoon($dataView);
			
			
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
	
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmPuraVida(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmPuraVida($dataView);
			
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
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmRustikChillGrill(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmRustikChillGrill($dataView);
			
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmComidaChinaMijo(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			$dataNew 					= NULL;
			$dataNew["printerQuantity"] = $dataView["objTransactionMaster"]->printerQuantity + 1;
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$dataNew);
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmComidaChinaMijoFactura($dataView);
			
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmComidaAudioElPipe(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			$dataNew 					= NULL;
			$dataNew["printerQuantity"] = $dataView["objTransactionMaster"]->printerQuantity + 1;
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$dataNew);
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmComidaAudioElPipe($dataView);
			
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
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmYahwetFart(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			$dataNew 					= NULL;
			$dataNew["printerQuantity"] = $dataView["objTransactionMaster"]->printerQuantity + 1;
			$this->Transaction_Master_Model->update_app_posme($companyID,$transactionID,$transactionMasterID,$dataNew);
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmYahwetFart($dataView);
			
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
	
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura80mmFerreteriaDouglas(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmFerreteriaDouglas($dataView);
			
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura58mm(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			
			
			$objParameterInvoiceUpdateNameInTransactionOnly				 = $this->core_web_parameter->getParameter("INVOICE_UPDATENAME_IN_TRANSACTION_ONLY",$companyID);
			$objParameterInvoiceUpdateNameInTransactionOnly				 = $objParameterInvoiceUpdateNameInTransactionOnly->value;	
			$dataView["objParameterInvoiceUpdateNameInTransactionOnly"]  = $objParameterInvoiceUpdateNameInTransactionOnly;
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58mm($dataView);
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura58mmChicharronesCarasenos(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58ChicharronesCarasenos($dataView);
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectFactura58mmLaTenera(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58LaTenera($dataView);
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectBar80mmRustikChillGrill(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			$comment				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterComment");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail2"]			= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= array();
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			$dataView["objComentario"]					= $comment;
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_BAR",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			//Filtrar productos			
			$itemID = explode(",",$itemID);			
			foreach($dataView["objTransactionMasterDetail2"] as $tmd)
			{
				foreach($itemID as $itemIDx)
				{
					if ($itemIDx == $tmd->componentItemID)
					{
						array_push($dataView["objTransactionMasterDetail"],$tmd);
					}
				}				
			}	
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCommandaCocina($dataView);
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectBar80mmPizzaLaus(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			$comment				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterComment");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail2"]			= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= array();
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			$dataView["objComentario"]					= $comment;
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_BAR",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			//Filtrar productos			
			$itemID = explode(",",$itemID);			
			foreach($dataView["objTransactionMasterDetail2"] as $tmd)
			{
				foreach($itemID as $itemIDx)
				{
					if ($itemIDx == $tmd->componentItemID)
					{
						array_push($dataView["objTransactionMasterDetail"],$tmd);
					}
				}				
			}	
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCommandaBarPizzaLaus($dataView);
		}
		catch(\Exception $ex){
		    if (empty($dataSession)) {
				return redirect()->to(base_url("core_acount/login"));
			}
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectCocina80mmRustikChillGrill(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			$comment				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterComment");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail2"]			= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= array();
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			$dataView["objComentario"]					= $comment;
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			//Filtrar productos			
			$itemID = explode(",",$itemID);			
			foreach($dataView["objTransactionMasterDetail2"] as $tmd)
			{
				foreach($itemID as $itemIDx)
				{
					if ($itemIDx == $tmd->componentItemID)
					{
						array_push($dataView["objTransactionMasterDetail"],$tmd);
					}
				}				
			}	
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCommandaCocina($dataView);
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
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectCocina80mmPizzaLaus(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			$comment				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterComment");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail2"]			= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= array();
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			$dataView["objComentario"]					= $comment;
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			//Filtrar productos			
			$itemID = explode(",",$itemID);			
			foreach($dataView["objTransactionMasterDetail2"] as $tmd)
			{
				foreach($itemID as $itemIDx)
				{
					if ($itemIDx == $tmd->componentItemID)
					{
						array_push($dataView["objTransactionMasterDetail"],$tmd);
					}
				}				
			}	
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCommandaCocinaPizzaLaus($dataView);
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
	
	
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectCocina80mm(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			$comment				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterComment");//--finuri	
			
			$dataView["objComentario"]							= $comment;
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail2"]			= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= array();
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			//Filtrar productos			
			$itemID = explode(",",$itemID);			
			foreach($dataView["objTransactionMasterDetail2"] as $tmd)
			{
				foreach($itemID as $itemIDx)
				{
					if ($itemIDx == $tmd->componentItemID)
					{
						array_push($dataView["objTransactionMasterDetail"],$tmd);
					}
				}				
			}	
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter80mmCommandaCocina($dataView);
			
			
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
	//facturacino imprimir directamente en impresora, formato de ticket
	function viewPrinterDirectCocina58mm(){
		try{
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			
			$this->core_web_printer_direct->configurationPrinter($objParameterPrinterName);
			$this->core_web_printer_direct->executePrinter58mmCommandaCocina($dataView);
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
	function viewRegisterFormatoPaginaNormal80mm(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription),  
					sprintf("%01.2f",round($detail_->quantity,2)), 
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				""
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
	function viewRegisterFormatoPaginaNormal80mmCharLot(){
		
		
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterCharLot(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
		
		
	}
	
	
	function viewRegisterFormatoPaginaNormal80mmEbenezer(){
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
			$objParameterRuc 		= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID)->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterInvoiceEbenezer(
			    "FACTURA",
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
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				$objParameterRuc 	
			);
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
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$fileNamePdf = "FAC_".$datView["objTM"]->transactionNumber."_".str_replace(" ","_", $datView["objNatural"]->firstName).".pdf";
			
			$path        	= "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
			$patdir         = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID;	
			
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
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream($fileNamePdf, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
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
	function viewRegisterFormatoPaginaNormal80mmGlobalPro(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($companyID,$datView["objUser"]->locationID);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterInvoiceGlobalPro(
			    "FACTURA",
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
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				"",
				$datView
			);
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
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$fileNamePdf = "FAC_".$datView["objTM"]->transactionNumber."_".str_replace(" ","_", $datView["objNatural"]->firstName).".pdf";
			
			$path        	= "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
			$patdir         = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID;	
			
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
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream($fileNamePdf, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
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
	
	function viewRegisterFormatoPaginaNormalA4Titan(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterInvoiceTitan(
			    "FACTURA",
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
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				""
			);
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
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$fileNamePdf = "FAC_".$datView["objTM"]->transactionNumber."_".str_replace(" ","_", $datView["objNatural"]->firstName).".pdf";
			
			$path        	= "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
			$patdir         = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID;	
			
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
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream($fileNamePdf, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
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
	function viewRegisterFormatoPaginaNormal80mmLaptopStore(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterInvoiceLaptopStore(
			    "FACTURA",
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
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				""
			);
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
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$fileNamePdf = "FAC_".$datView["objTM"]->transactionNumber."_".str_replace(" ","_", $datView["objNatural"]->firstName).".pdf";
			
			$path        	= "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
			$patdir         = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID;	
			
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
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream($fileNamePdf, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
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
	function viewRegisterFormatoPaginaNormal80mmBpn(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterInvoiceBpn(
			    "FACTURA",
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
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				""
			);
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
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$fileNamePdf = "FAC_".$datView["objTM"]->transactionNumber."_".str_replace(" ","_", $datView["objNatural"]->firstName).".pdf";
			
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream($fileNamePdf, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
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
	
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar		
				$timestamp 	= date("YmdHis") . "0"; // Resultado: 202505261134000
				$filename 	= "posme_" . $timestamp . ".pdf";							
				$this->dompdf->stream($filename, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1ElektroClima(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					number_format($detail_->quantity,2,".",","),
					number_format($detail_->unitaryPrice,2,".",","),
					number_format($detail_->amount,2,".",",")			
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterElektroClima(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar	
				$timestamp 	= date("YmdHis") . "0"; // Resultado: 202505261134000
				$filename 	= "posme_" . $timestamp . ".pdf";					
				$this->dompdf->stream($filename, ['Attachment' => false ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1Divas(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterDivas(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1Caracenos(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterCaracenos(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1FarmaLey(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);			
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    $discountTotal = 0;
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					"_________________________________"."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
				
				//
				$objTMDReference 	= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterDetailID($detail_->transactionMasterDetailID);
				$precio 			= $detail_->unitaryPrice;
				$minsa	 			= $objTMDReference[0]->precio2;
				$descuento			= $minsa - $precio;
				$precio 			= sprintf("%01.2f",round($precio,2));
				$minsa	 			= sprintf("%01.2f",round($minsa,2));
				$descuento			= sprintf("%01.2f",round($descuento,2));
				
				if($descuento > 0)
				{
					$discountTotal = $discountTotal + ($descuento * $detail_->quantity);
				}
				
				$row = array(
					"C$:".$minsa."(Desc:C$".$descuento.")T:C$".$precio."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					$precio,
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
				
				
				
			}
			
			$datView["objTM"]->discount = $discountTotal;
			if($discountTotal > 0 )
			{
				$datView["objTM"]->subAmount = $datView["objTM"]->subAmount + $discountTotal;
			}
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterFarmaLey(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/,
				$datView
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1LaCeiba(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterLaCeiba(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1LicoreriaCentral(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterLicoreriaCentral(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	function viewRegisterFormatoPaginaNormal80mmOpcion1PasteleriaLizzette(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			$objOrderDeliveryTime					= $this->Catalog_Item_Model->get_rowByCatalogItemID($datView["objTMI"]->zoneID);
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterPasteleriaLizzette(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
				$objOrderDeliveryTime,
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1RepuestoCristoRey(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    // Ordenar el array por el campo barCode (ascendente)
			usort($datView["objTMD"], function($a, $b) {
				return strcmp($a->barCode, $b->barCode);
			});

			foreach($datView["objTMD"] as $detail_){
				$row = array(
					strtoupper($detail_->barCode). " ". strtoupper($detail_->categoryName)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterRepuestoCristoRey(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
				
			//Crear la Carpeta para almacenar los Archivos del Documento
			$documentoPath = PATH_FILE_OF_APP."/company_".$companyID."/component_48/component_item_".$transactionMasterID;						
			if (!file_exists($documentoPath))
			{
				mkdir($documentoPath, 0755, true);
				chmod($documentoPath, 0755);
			}
			
			
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}

    function viewRegisterFormatoPaginaNormal80mmOpcion1Emanuel(){
        try{


            $transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
            $transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
            $companyID 					= APP_COMPANY;
			$dataSession				= $this->session->get();


            //Get Component
            $objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
            $objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
            $objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
            $objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $objParameterRuc        = $objParameterRuc->value;
            $objCompany 			= $this->Company_Model->get_rowByPK($companyID);
            $spacing 				= 0.5;

            //Get Documento
            $datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
            $datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
            $datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
            $datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
            $datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
            $datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
            $datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
            $datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
            $datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
            $datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
            $datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
            $datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
            $datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objZone"]						= $this->core_web_catalog->getCatalogItem("tb_transaction_master_info_billing","zoneID",$companyID,$datView["objTMI"]->zoneID);
			$datView["objMesa"]						= $this->core_web_catalog->getCatalogItem("tb_transaction_master_info_billing","mesaID",$companyID,$datView["objTMI"]->mesaID);			
            $prefixCurrency 						= $datView["objCurrency"]->simbol." ";



            //Configurar Detalle Header
            $confiDetalleHeader = array();
            $row = array(
                "style"		=>"text-align:left;width:auto",
                "colspan"	=>'1',
                "prefix"	=>'',


                "style_row_data"		=>"text-align:left;width:auto",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>'',
                "nueva_fila_row_data"	=>1
            );
            array_push($confiDetalleHeader,$row);

            $row = array(
                "style"		=>"text-align:left;width:50px",
                "colspan"	=>'1',
                "prefix"	=>'',

                "style_row_data"		=>"text-align:right;width:auto",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>'',
                "nueva_fila_row_data"	=>0
            );
            array_push($confiDetalleHeader,$row);


            $row = array(
                "style"		=>"text-align:right;width:90px",
                "colspan"	=>'1',
                "prefix"	=>$datView["objCurrency"]->simbol,

                "style_row_data"		=>"text-align:right;width:90px",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>"",
                "nueva_fila_row_data"	=>0
            );
            array_push($confiDetalleHeader,$row);



            $detalle = array();
            $row = array("CANT", 'PREC', "TOTAL");
            array_push($detalle,$row);


            foreach($datView["objTMD"] as $detail_){
                $row = array(
                    $detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",
                    "none",
                    "none"
                );
                array_push($detalle,$row);

                $row = array(
                    sprintf("%01.2f",round($detail_->quantity,2)),
                    sprintf("%01.2f",round($detail_->unitaryPrice,2)),
                    sprintf("%01.2f",round($detail_->amount,2))
                );
                array_push($detalle,$row);
            }

            if ($datView["objStage"][0]->display == "APLICADA"){
                $html = helper_reporte80mmTransactionMasterEmanuel(
                    "FACTURA",
                    $objCompany,
                    $objParameter,
                    $datView["objTM"],
                    $datView["objNatural"],
                    $datView["objCustumer"],
                    $datView["tipoCambio"],
                    $datView["objCurrency"],
                    $datView["objTMI"],
                    $confiDetalleHeader,
                    $detalle,
                    $objParameterTelefono, /*telefono*/
                    $datView["objStage"][0]->display, /*estado*/
                    $datView["objTC"]->name /*causal*/,
                    $datView["objUser"]->nickname,
                    $objParameterRuc /*ruc*/ , 
					$datView 
                );
            }else{
                $html = helper_reporte80mmTransactionMasterRegistrada(
                    "FACTURA",
                    $objCompany,
                    $objParameter,
                    $datView["objTM"],
                    $datView["objNatural"],
                    $datView["objCustumer"],
                    $datView["tipoCambio"],
                    $datView["objCurrency"],
                    $datView["objTMI"],
                    $confiDetalleHeader,
                    $detalle,
                    $objParameterTelefono, /*telefono*/
                    $datView["objStage"][0]->display, /*estado*/
                    $datView["objTC"]->name /*causal*/,
                    $datView["objUser"]->nickname,
                    $objParameterRuc /*ruc*/ , 
					$datView
                );
            }

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

            $fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
            $path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;

            file_put_contents(
                $path,
                $this->dompdf->output()
            );

            chmod($path, 644);

            if($objParameterShowLinkDownload == "true")
            {
                echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
                    $fileNamePut."'>download factura</a>
				";

            }
            else{
				
       			if($dataSession["user"]->nickname=="adminweb")
					$this->dompdf->stream("posme_factura_".$transactionMasterID."_".date("dmYhis"), ['Attachment' => true ]);
				else
					$this->dompdf->stream("posme_factura_".$transactionMasterID."_".date("dmYhis"), ['Attachment' => true ]);

            }




        }
        catch(\Exception $ex){

            //$data["session"] = $dataSession;
            $data["session"] 	= null;
            $data["exception"] 	= $ex;
            $data["urlLogin"]  	= base_url();
            $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
            $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView        	= view("core_template/email_error_general",$data);

            $this->email->setFrom(EMAIL_APP);
            $this->email->setTo(EMAIL_APP_COPY);
            $this->email->setSubject("Error");
            $this->email->setMessage($resultView);

            $resultSend01 = $this->email->send();
            $resultSend02 = $this->email->printDebugger();


            return $resultView;
        }
    }
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1EmanuelPizza(){
        try{


            $transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
            $transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
            $companyID 					= APP_COMPANY;



            //Get Component
            $objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
            $objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
            $objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
            $objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $objParameterRuc        = $objParameterRuc->value;
            $objCompany 	= $this->Company_Model->get_rowByPK($companyID);
            $spacing 		= 0.5;

            //Get Documento
            $datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
            $datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
            $datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
            $datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
            $datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
            $datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
            $datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
            $datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
            $datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
            $datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
            $datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objLegal"]					= $this->Legal_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
            $datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
            $datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objZone"]						= $this->core_web_catalog->getCatalogItem("tb_transaction_master_info_billing","zoneID",$companyID,$datView["objTMI"]->zoneID);
			$datView["objMesa"]						= $this->core_web_catalog->getCatalogItem("tb_transaction_master_info_billing","mesaID",$companyID,$datView["objTMI"]->mesaID);			
            $prefixCurrency 						= $datView["objCurrency"]->simbol." ";



            //Configurar Detalle Header
            $confiDetalleHeader = array();
            $row = array(
                "style"		=>"text-align:left;width:auto",
                "colspan"	=>'1',
                "prefix"	=>'',


                "style_row_data"		=>"text-align:left;width:auto",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>'',
                "nueva_fila_row_data"	=>1
            );
            array_push($confiDetalleHeader,$row);

            $row = array(
                "style"		=>"text-align:left;width:50px",
                "colspan"	=>'1',
                "prefix"	=>'',

                "style_row_data"		=>"text-align:right;width:auto",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>'',
                "nueva_fila_row_data"	=>0
            );
            array_push($confiDetalleHeader,$row);


            $row = array(
                "style"		=>"text-align:right;width:90px",
                "colspan"	=>'1',
                "prefix"	=>$datView["objCurrency"]->simbol,

                "style_row_data"		=>"text-align:right;width:90px",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>"",
                "nueva_fila_row_data"	=>0
            );
            array_push($confiDetalleHeader,$row);



            $detalle = array();
            $row = array("CANT", 'PREC', "TOTAL");
            array_push($detalle,$row);


            foreach($datView["objTMD"] as $detail_){
                $row = array(
                    $detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",
                    "none",
                    "none"
                );
                array_push($detalle,$row);

                $row = array(
                    sprintf("%01.2f",round($detail_->quantity,2)),
                    sprintf("%01.2f",round($detail_->unitaryPrice,2)),
                    sprintf("%01.2f",round($detail_->amount,2))
                );
                array_push($detalle,$row);
            }

            if ($datView["objStage"][0]->display == "APLICADA"){
                $html = helper_reporte80mmTransactionMasterEmanuelPizza(
                    "FACTURA",
                    $objCompany,
                    $objParameter,
                    $datView["objTM"],
                    $datView["objNatural"],
                    $datView["objCustumer"],
                    $datView["tipoCambio"],
                    $datView["objCurrency"],
                    $datView["objTMI"],
                    $confiDetalleHeader,
                    $detalle,
                    $objParameterTelefono, /*telefono*/
                    $datView["objStage"][0]->display, /*estado*/
                    $datView["objTC"]->name /*causal*/,
                    $datView["objUser"]->nickname,
                    $objParameterRuc /*ruc*/ , 
					$datView 
                );
            }else{
                $html = helper_reporte80mmTransactionMasterRegistrada(
                    "FACTURA",
                    $objCompany,
                    $objParameter,
                    $datView["objTM"],
                    $datView["objNatural"],
                    $datView["objCustumer"],
                    $datView["tipoCambio"],
                    $datView["objCurrency"],
                    $datView["objTMI"],
                    $confiDetalleHeader,
                    $detalle,
                    $objParameterTelefono, /*telefono*/
                    $datView["objStage"][0]->display, /*estado*/
                    $datView["objTC"]->name /*causal*/,
                    $datView["objUser"]->nickname,
                    $objParameterRuc /*ruc*/ , 
					$datView
                );
            }

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

            $fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
            $path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;

            file_put_contents(
                $path,
                $this->dompdf->output()
            );

            chmod($path, 644);

            if($objParameterShowLinkDownload == "true")
            {
                echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
                    $fileNamePut."'>download factura</a>
				";

            }
            else{
                //visualizar
                $this->dompdf->stream("factura_".$transactionMasterID."_".date("dmYhis"), ['Attachment' => $objParameterShowDownloadPreview ]);
            }




        }
        catch(\Exception $ex){

            //$data["session"] = $dataSession;
            $data["session"] 	= null;
            $data["exception"] 	= $ex;
            $data["urlLogin"]  	= base_url();
            $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
            $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView        	= view("core_template/email_error_general",$data);

            $this->email->setFrom(EMAIL_APP);
            $this->email->setTo(EMAIL_APP_COPY);
            $this->email->setSubject("Error");
            $this->email->setMessage($resultView);

            $resultSend01 = $this->email->send();
            $resultSend02 = $this->email->printDebugger();


            return $resultView;
        }
    }
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1Tenampa(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterTenampa(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}

    function viewRegisterFormatoPaginaNormal80mmOpcion1Colirio(){
        try{


            $transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri
            $transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri
            $companyID 					= APP_COMPANY;



            //Get Component
            $objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
            $objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
            $objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
            $objParameterEmail	    = $this->core_web_parameter->getParameter("CORE_PROPIETARY_EMAIL",$companyID);
            $objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $objParameterRuc        = $objParameterRuc->value;
            $objCompany 	= $this->Company_Model->get_rowByPK($companyID);
            $spacing 		= 0.5;

            //Get Documento
            $datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
            $datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
            $datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
            $datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
            $datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
            $datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
            $datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
            $datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
            $datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
            $datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
            $datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
            $datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
            $datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
            $datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
            $datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
            $prefixCurrency 						= $datView["objCurrency"]->simbol." ";



            //Configurar Detalle Header
            $confiDetalleHeader = array();
            $row = array(
                "style"		=>"text-align:left;width:auto",
                "colspan"	=>'1',
                "prefix"	=>'',


                "style_row_data"		=>"text-align:left;width:auto",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>'',
                "nueva_fila_row_data"	=>1
            );
            array_push($confiDetalleHeader,$row);

            $row = array(
                "style"		=>"text-align:left;width:50px",
                "colspan"	=>'1',
                "prefix"	=>'',

                "style_row_data"		=>"text-align:right;width:auto",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>'',
                "nueva_fila_row_data"	=>0
            );
            array_push($confiDetalleHeader,$row);


            $row = array(
                "style"		=>"text-align:right;width:90px",
                "colspan"	=>'1',
                "prefix"	=>$datView["objCurrency"]->simbol,

                "style_row_data"		=>"text-align:right;width:90px",
                "colspan_row_data"		=>'1',
                "prefix_row_data"		=>"",
                "nueva_fila_row_data"	=>0
            );
            array_push($confiDetalleHeader,$row);



            $detalle = array();
            $row = array("CANT", 'PREC', "TOTAL");
            array_push($detalle,$row);


            foreach($datView["objTMD"] as $detail_){
                $row = array(
                    $detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",
                    "none",
                    "none"
                );
                array_push($detalle,$row);

                $row = array(
                    sprintf("%01.2f",round($detail_->quantity,2)),
                    sprintf("%01.2f",round($detail_->unitaryPrice,2)),
                    sprintf("%01.2f",round($detail_->amount,2))
                );
                array_push($detalle,$row);
            }


            //Generar Reporte
            $html = helper_reporte80mmTransactionMasterColirio(
                "Comprobante Ingresos",
                $objCompany,
                $objParameter,
                $datView["objTM"],
                $datView["objNatural"],
                $datView["objCustumer"],
                $datView["tipoCambio"],
                $datView["objCurrency"],
                $datView["objTMI"],
                $confiDetalleHeader,
                $detalle,
                $objParameterTelefono, /*telefono*/
                $objParameterEmail, /*email*/
                $datView["objStage"][0]->display, /*estado*/
                $datView["objTC"]->name /*causal*/,
                $datView["objUser"]->nickname,
                $objParameterRuc /*ruc*/
            );
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

            $fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
            $path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;

            file_put_contents(
                $path,
                $this->dompdf->output()
            );

            chmod($path, 644);

            if($objParameterShowLinkDownload == "true")
            {
                echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
                    $fileNamePut."'>download factura</a>
				";

            }
            else{
                //visualizar
                $this->dompdf->stream("file_".date('YmdHms'), ['Attachment' => $objParameterShowDownloadPreview ]);
            }




        }
        catch(\Exception $ex){

            //$data["session"] = $dataSession;
            $data["session"] 	= null;
            $data["exception"] 	= $ex;
            $data["urlLogin"]  	= base_url();
            $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
            $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
            $resultView        	= view("core_template/email_error_general",$data);

            $this->email->setFrom(EMAIL_APP);
            $this->email->setTo(EMAIL_APP_COPY);
            $this->email->setSubject("Error");
            $this->email->setMessage($resultView);

            $resultSend01 = $this->email->send();
            $resultSend02 = $this->email->printDebugger();


            return $resultView;
        }
    }

	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1PizzaLaus(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTMDR"]						= $this->Transaction_Master_Detail_References_Model->get_rowByTransactionMasterIDAndComponentID($transactionMasterID,$objComponentItem->componentID );
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMDR"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower("UNIDAD")."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1FarmaLM(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterLM(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1FarmaGael(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterFarmaGael(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1FarmaJireth(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterFarmaJireth(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1BivalyStore(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterBivalyStore(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1Miranda(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterMiranda(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar	
				$timestamp 	= date("YmdHis") . "0"; // Resultado: 202505261134000
				$filename 	= "posme_" . $timestamp . ".pdf";				
				$this->dompdf->stream($filename, ['Attachment' => true ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1Chic(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription)."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterBillingChic(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1GlamCuts(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>"",
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("CANT", 'PREC', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				$row = array(
					$detail_->itemName. " ". $detail_->skuFormatoDescription ."-comand-new-row",  				
					"none",
					"none"
				);
			    array_push($detalle,$row);
				
			    $row = array(					
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->unitaryPrice,2)),
					sprintf("%01.2f",round($detail_->amount,2))					
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterGlamCuts(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	
	function viewRegisterFormatoPaginaNormal80mmOpcion1AgroServicioElLabrador(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
					$detail_->itemNumber."</br>".$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription),  
					sprintf("%01.2f",round($detail_->quantity,2)), 					
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMaster(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1MarysCosmetic(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
					    
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterMarysCosmetic(
			    "FACTURA",
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
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
			);
			$this->dompdf->loadHTML($html);
			//echo $html;
			//return ;
			
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
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	
	
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1Axceso(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
				
				$barCode = "";
				if( strpos($detail_->barCode,",") === false )
				{
					$barCode = $detail_->barCode;
				}
				else
				{
					$barCode = $detail_->itemNumber;
				}
				
				
				
			    $row = array(
					$barCode." ".$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription),  
					sprintf("%01.2f",round($detail_->quantity,2)), 
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterAxceso(
			    "RECIBO",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
		    $this->email->setFrom(EMAIL_APP);
		    $this->email->setTo(EMAIL_APP_COPY);
		    $this->email->setSubject("Error");
		    $this->email->setMessage($resultView);
		    
		    $resultSend01 = $this->email->send();
		    $resultSend02 = $this->email->printDebugger();
		    
		    
		    return $resultView;
		}
	}
	//Facturacion
	//Reporte = 
	//	viewRegisterFormatoPaginaNormal80mm	+
	//	Field.Vendedor	+
	//  Field.Ruc
	function viewRegisterFormatoPaginaNormal80mmOpcion1PabloRosales(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription),  
					sprintf("%01.2f",round($detail_->quantity,2))."  * ".
					sprintf("%s",number_format($detail_->unitaryAmount,2,".","," )) , 
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterPabloRosales(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				

			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
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
	function viewRegisterFormatoPaginaNormal80mmCarlosLuis(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription),  
					sprintf("%01.2f",round($detail_->quantity,2)), 
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterInvoiceCarlosLuis(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				""
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
	function viewRegisterFormatoPaginaNormal80mmOpcion1Douglas(){
		try{ 
			
			
			$transactionID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri			
			$transactionMasterID		= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri				
			$companyID 					= APP_COMPANY;	
			
			
			
			//Get Component
			$objComponent	        = $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$objParameterRuc	    = $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$objParameterRuc        = $objParameterRuc->value;
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$datView["objTM"]->branchID,APP_ROL_SUPERADMIN);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objUser"]						= $this->User_Model->get_rowByPK($companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
		
			//Configurar Detalle Header
			$confiDetalleHeader = array();
			$row = array(
				"style"		=>"text-align:left;width:auto",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				
				"style_row_data"		=>"text-align:left;width:auto",
				"colspan_row_data"		=>'3',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:left;width:50px",
				"colspan"	=>'1',
				"prefix"	=>'',
				
				"style_row_data"		=>"text-align:right;width:auto",
				"colspan_row_data"		=>'2',
				"prefix_row_data"		=>'',
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
				"style"		=>"text-align:right;width:90px",
				"colspan"	=>'1',
				"prefix"	=>$datView["objCurrency"]->simbol,
				
				"style_row_data"		=>"text-align:right;width:90px",
				"colspan_row_data"		=>'1',
				"prefix_row_data"		=>$datView["objCurrency"]->simbol,
				"nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", 'CANT', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
					$detail_->itemName. " ". strtolower($detail_->skuFormatoDescription),  
					sprintf("%01.2f",round($detail_->quantity,2)), 
					sprintf("%01.2f",round($detail_->amount,2))
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterInvoiceDouglas(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono, /*telefono*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name /*causal*/,
				$datView["objUser"]->nickname,
			    $objParameterRuc /*ruc*/
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
			$objParameterShowDownloadPreview	= $this->core_web_parameter->getParameter("CORE_SHOW_DOWNLOAD_PREVIEW",$companyID);
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview->value;
			$objParameterShowDownloadPreview	= $objParameterShowDownloadPreview == "true" ? true : false;
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$path        = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
				
			file_put_contents(
				$path,
				$this->dompdf->output()					
			);						
			
			chmod($path, 644);
			
			if($objParameterShowLinkDownload == "true")
			{			
				echo "<a 
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				

			}
			else{			
				//visualizar				
				$this->dompdf->stream("file.pdf ", ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
			
		}
		catch(\Exception $ex){
		    
		    //$data["session"] = $dataSession;
			$data["session"] 	= null;
		    $data["exception"] 	= $ex;
		    $data["urlLogin"]  	= base_url();
		    $data["urlIndex"]  	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/"."index";
		    $data["urlBack"]   	= base_url()."/". str_replace("app\\controllers\\","",strtolower( get_class($this)))."/".helper_SegmentsByIndex($this->uri->getSegments(), 0, null);
		    $resultView        	= view("core_template/email_error_general",$data);
		    
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
	function viewRegisterFormatoPaginaNormalA4Opcion1(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTC"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["objNaturalEmployer"]			= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["objTelefonoEmployer"]			= $this->Entity_Phone_Model->get_rowByEntity($companyID,$datView["objCustumer"]->branchID,$datView["objTM"]->entityIDSecondary);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			
			//Generar Reporte
			$html = helper_reporteA4TransactionMasterInvoiceA4Opcion1(
			    "FACTURA",
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
				$datView["objNaturalEmployer"], /*vendedor*/
				$datView["objTelefonoEmployer"], /*telefono cliente*/
				$datView["objStage"][0]->display, /*estado*/
				$datView["objTC"]->name, /*causal*/
				"",
				"",
				$dataSession["companyParameter"]
			);
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
			
			$fileNamePut = "factura_".$transactionMasterID."_".date("dmYhis").".pdf";
			$fileNamePdf = "FAC_".$datView["objTM"]->transactionNumber."_".str_replace(" ","_", $datView["objNatural"]->firstName).".pdf";
			
			$path        	= "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".$fileNamePut;
			$patdir         = "./resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID;	
			
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
					href='".base_url()."/resource/file_company/company_".$companyID."/component_48/component_item_".$transactionMasterID."/".
					$fileNamePut."'>download factura</a>
				"; 				
			
			}
			else{			
				//visualizar				
				$this->dompdf->stream($fileNamePdf, ['Attachment' => $objParameterShowDownloadPreview ]);
			}
			
			
			
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
	
	function viewRegisterFormatoPaginaNormalA4FunBlandonReciboOficialCaja()
	{
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
			$saldos						= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"saldos");//--finuri	
			$companyID 					= $dataSession["user"]->companyID;		
			$branchID 					= $dataSession["user"]->branchID;		
			$roleID 					= $dataSession["role"]->roleID;		
			
			//Get Component
			$objComponent	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			//Get Logo
			$objParameter	= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			//Get Company
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			//Get Documento				
			
			//Get Documento
			//Obtener Datos
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransactionToShare($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$datView["objCurrency"]                 = $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			
			
			
			
			//Inicializar Detalle
			$saldoInicial = array_sum(array_column($datView["objTMD"], 'reference2'));
			$saldoFinal   = array_sum(array_column($datView["objTMD"], 'reference4'));
			$saldoAbonado = array_sum(array_column($datView["objTMD"], 'amount'));
			
			
			
			//Generar Reporte
			$html = helper_reporte80mmTransactionMasterFunBlandonReciboOficialCaja(
			    "ABONO",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],			    
			    $objParameterTelefono,
				$datView["objStage"][0]->display,
				"",
				""
			);
			
			//echo $html;
			//return;
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
			$nameFileDownload				= date("YmdHis").".pdf";
			
			//visualizar
			$this->response->setContentType('application/pdf');
			$objParameterShowLinkDownload 	= $objParameterShowLinkDownload == "false" ? true : false;
			$this->dompdf->stream($nameFileDownload	, ['Attachment' => $objParameterShowLinkDownload]);
			
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
	function viewRegisterFormatoPaginaNormal58mm(){
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
			$objCompany 	= $this->Company_Model->get_rowByPK($companyID);			
			$spacing 		= 0.5;
			
			//Get Documento					
			$datView["objTM"]	 					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMI"]						= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$datView["objTMD"]						= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$datView["objTM"]->transactionOn 		= date_format(date_create($datView["objTM"]->transactionOn),"Y-m-d");
			$datView["objUser"] 					= $this->User_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->createdAt,$datView["objTM"]->createdBy);
			$datView["Identifier"]					= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$datView["objBranch"]					= $this->Branch_Model->get_rowByPK($datView["objTM"]->companyID,$datView["objTM"]->branchID);
			$datView["objStage"]					= $this->core_web_workflow->getWorkflowStage("tb_transaction_master_billing","statusID",$datView["objTM"]->statusID,$companyID,$branchID,$roleID);
			$datView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$datView["objTM"]->transactionID,$datView["objTM"]->transactionCausalID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objCurrency"]					= $this->Currency_Model->get_rowByPK($datView["objTM"]->currencyID);
			$datView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$datView["objTM"]->entityID);
			$datView["objNatural"]					= $this->Natural_Model->get_rowByPK($companyID,$datView["objCustumer"]->branchID,$datView["objCustumer"]->entityID);
			$datView["tipoCambio"]					= round($datView["objTM"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			$prefixCurrency 						= $datView["objCurrency"]->simbol." "; 
			
			
			//Configurar Detalle
			$confiDetalleHeader = array();
			$row = array(
			    "style"		=>"text-align:left;width:auto",
			    "colspan"	=>'1',
			    "prefix"	=>'',
			    
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'3',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>1
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
			    "style"		=>"text-align:left;width:50px",
			    "colspan"	=>'1',
			    "prefix"	=>'',
			    
			    "style_row_data"		=>"text-align:right;width:auto",
			    "colspan_row_data"		=>'2',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			$row = array(
			    "style"		=>"text-align:right;width:90px",
			    "colspan"	=>'1',
			    "prefix"	=>$datView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;width:90px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>$datView["objCurrency"]->simbol,
			    "nueva_fila_row_data"	=>0
			);
			array_push($confiDetalleHeader,$row);
			
			
			
			
		    
		    $detalle = array();		    
		    $row = array("PRODUCTO", '', "TOTAL");
		    array_push($detalle,$row);
		    
		    
			foreach($datView["objTMD"] as $detail_){
			    $row = array(
			        $detail_->itemName,  
			        "cant:".round($detail_->quantity,2), 
			        round($detail_->amount,2));
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte58mmTransactionMaster(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $datView["objTM"],
			    $datView["objNatural"],
			    $datView["objCustumer"],
			    $datView["tipoCambio"],
			    $datView["objCurrency"],
			    $datView["objTMI"],
			    $confiDetalleHeader,
			    $detalle,
			    $objParameterTelefono
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
	function viewRegisterFormatoPaginaCocina80mm(){
		try{ 
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
		
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);					
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["objNatural"]						= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustumer"]->branchID,$dataView["objCustumer"]->entityID);
			$dataView["tipoCambio"]						= round($dataView["objTransactionMaster"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:auto",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:left;width:50px",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:50px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;width:70px",
			    "colspan"=>'1',
			    "prefix"=>$dataView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;width:70px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>"",
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
		    
		    $detalle = array();		    
		    $row = array("Elaborar", '', "");
		    array_push($detalle,$row);
		    
		    
			foreach($dataView["objTransactionMasterDetail"] as $detail_){
			    $row = array(
					$detail_->itemName,  
					1, /*round($detail_->quantity,2),*/ 
					"" /*round($detail_->amount,2)*/
				);
			    array_push($detalle,$row);
			}
			
			
			//Generar Reporte
			$html = helper_reporte80mmCocina(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $dataView["objTransactionMaster"],
			    $dataView["objNatural"],
			    $dataView["objCustumer"],
			    $dataView["tipoCambio"],
			    $dataView["objCurrency"],
			    $dataView["objTransactionMasterInfo"],
			    $confiDetalle,
			    $detalle,
			    $objParameterTelefono,
				"",
				"",
				""
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
	function viewRegisterFormatoPaginaCocina58mm(){
		try{ 
			
			//Librerias		
			//
			////////////////////////////////////////
			////////////////////////////////////////
			////////////////////////////////////////
			
			
			
			//Obtener el componente de Item
			$objComponentItem	= $this->core_web_tools->getComponentIDBy_ComponentName("tb_item");
			if(!$objComponentItem)
			throw new \Exception("EL COMPONENTE 'tb_item' NO EXISTE...");
			$dataSession		= $this->session->get();
									
			$companyID				= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"companyID");//--finuri
			$transactionID			= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionID");//--finuri	
			$transactionMasterID	= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"transactionMasterID");//--finuri	
			$itemID					= /*--ini uri*/ helper_SegmentsValue($this->uri->getSegments(),"itemID");//--finuri	
			$itemIDArray			= explode(",",$itemID);
			$itemIDArray			= array_map('intval', $itemIDArray);
			
			$objCompany 			= $this->Company_Model->get_rowByPK($companyID);					
			$objParameter	        = $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$objParameterTelefono	= $this->core_web_parameter->getParameter("CORE_PHONE",$companyID);
			$dataView["objTransactionMaster"]					= $this->Transaction_Master_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterInfo"]				= $this->Transaction_Master_Info_Model->get_rowByPK($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetail"]				= $this->Transaction_Master_Detail_Model->get_rowByTransaction($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailWarehouse"]	= $this->Transaction_Master_Detail_Model->get_rowByTransactionAndWarehouse($companyID,$transactionID,$transactionMasterID);
			$dataView["objTransactionMasterDetailConcept"]		= $this->Transaction_Master_Concept_Model->get_rowByTransactionMasterConcept($companyID,$transactionID,$transactionMasterID,$objComponentItem->componentID);
			
			
			$dataView["objComponentCompany"]			= $this->core_web_tools->getComponentIDBy_ComponentName("tb_company");
			$dataView["objParameterLogo"]				= $this->core_web_parameter->getParameter("CORE_COMPANY_LOGO",$companyID);
			$dataView["objParameterPhoneProperty"]		= $this->core_web_parameter->getParameter("CORE_PROPIETARY_PHONE",$companyID);
			$dataView["objCompany"] 					= $this->Company_Model->get_rowByPK($companyID);			
			$dataView["objUser"] 						= $this->User_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->createdAt,$dataView["objTransactionMaster"]->createdBy);
			$dataView["Identifier"]						= $this->core_web_parameter->getParameter("CORE_COMPANY_IDENTIFIER",$companyID);
			$dataView["objBranch"]						= $this->Branch_Model->get_rowByPK($companyID,$dataView["objTransactionMaster"]->branchID);
			$dataView["objTipo"]						= $this->Transaction_Causal_Model->getByCompanyAndTransactionAndCausal($companyID,$dataView["objTransactionMaster"]->transactionID,$dataView["objTransactionMaster"]->transactionCausalID);
			$dataView["objCustumer"]					= $this->Customer_Model->get_rowByEntity($companyID,$dataView["objTransactionMaster"]->entityID);
			$dataView["objCurrency"]					= $this->Currency_Model->get_rowByPK($dataView["objTransactionMaster"]->currencyID);
			$dataView["prefixCurrency"]					= $dataView["objCurrency"]->simbol." ";
			$dataView["cedulaCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientIdentifier == "" ? $dataView["objCustumer"]->customerNumber :  $dataView["objTransactionMasterInfo"]->referenceClientIdentifier;
			$dataView["nombreCliente"] 					= $dataView["objTransactionMasterInfo"]->referenceClientName  == "" ? $dataView["objCustumer"]->firstName : $dataView["objTransactionMasterInfo"]->referenceClientName ;
			$dataView["objStage"]						= $this->Workflow_Stage_Model->get_rowByWorkflowStageIDOnly($dataView["objTransactionMaster"]->statusID);
			$dataView["objNatural"]						= $this->Natural_Model->get_rowByPK($companyID,$dataView["objCustumer"]->branchID,$dataView["objCustumer"]->entityID);
			$dataView["tipoCambio"]						= round($dataView["objTransactionMaster"]->exchangeRate + $this->core_web_parameter->getParameter("ACCOUNTING_EXCHANGE_SALE",$companyID)->value,2);			
			$dataView["objZone"]						= $this->core_web_catalog->getCatalogItem("tb_transaction_master_info_billing","zoneID",$companyID,$dataView["objTransactionMasterInfo"]->zoneID);
			$dataView["objMesa"]						= $this->core_web_catalog->getCatalogItem("tb_transaction_master_info_billing","mesaID",$companyID,$dataView["objTransactionMasterInfo"]->mesaID);
			
			
			//obtener nombre de impresora por defecto
			$objParameterPrinterName = $this->core_web_parameter->getParameter("INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT_COCINA",$companyID);
			$objParameterPrinterName = $objParameterPrinterName->value;
								
			
			
			//Configurar Detalle
			$confiDetalle = array();
			$row = array(
			    "style"=>"text-align:left;width:auto",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:auto",
			    "colspan_row_data"		=>'3',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>1
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:left;width:50px",
			    "colspan"=>'1',
			    "prefix"=>'',
			    
			    "style_row_data"		=>"text-align:left;width:50px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>'',
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			$row = array(
			    "style"=>"text-align:right;width:5px",
			    "colspan"=>'1',
			    "prefix"=>$dataView["objCurrency"]->simbol,
			    
			    "style_row_data"		=>"text-align:right;width:5px",
			    "colspan_row_data"		=>'1',
			    "prefix_row_data"		=>"",
			    "nueva_fila_row_data"	=>0
			    
			);
			array_push($confiDetalle,$row);
			
		    
		    $detalle = array();		    
		    $row = array("Elaborar", '', "");
		    array_push($detalle,$row);
		    
		    
			
			foreach($dataView["objTransactionMasterDetail"] as $detail_){
				
				if(in_array($detail_->componentItemID, $itemIDArray)   )
				{
					$row = array(
						$detail_->itemName,  
						1, /*round($detail_->quantity,2),*/ 
						"" /*round($detail_->amount,2)*/
					);
					array_push($detalle,$row);
				}
			}
			
			
			//Generar Reporte
			$html = helper_reporte58mmCocina(
			    "FACTURA",
			    $objCompany,
			    $objParameter,
			    $dataView["objTransactionMaster"],
			    $dataView["objNatural"],
			    $dataView["objCustumer"],
			    $dataView["tipoCambio"],
			    $dataView["objCurrency"],
			    $dataView["objTransactionMasterInfo"],
			    $confiDetalle,
			    $detalle,
				$dataView, 
			    $objParameterTelefono,
				"",
				"",
				"",
				""				
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