<?php
function getBehavio($type_company,$key_controller,$key_element,$default_value)
{
	$divs = array(		
		strtolower('default_core_web_menu_O. SALIDAS')			 												=> "O. SALIDAS",
		strtolower('default_core_web_menu_O. ENTRADAS')			 												=> "O. ENTRADAS",
		strtolower('default_core_web_menu_PRODUCTO')			 												=> "PRODUCTO",
		strtolower('default_app_cxp_expenses_Referencia 1')														=> "Referencia 1",
		strtolower('default_app_cxp_expenses_Referencia 2')														=> "Referencia 2",
		strtolower('default_app_cxp_expenses_Referencia 3')														=> "Referencia 3",				
		strtolower('default_default_masterpage_backgroundImage')			 									=> "",
		strtolower('default_core_account_idtest')																=> "hidden",
		strtolower('default_app_invoice_billing_panelResumenFactura')											=> "",
		strtolower('default_app_invoice_billing_panelResumenFacturaTool')										=> "hidden",
		strtolower('default_app_invoice_billing_bodyListInvoice')			 									=> "",		
		strtolower('default_app_invoice_billing_divTxtZone')		 											=> "",
		strtolower('default_app_box_share_stylePage')			 												=> "",
		strtolower('default_app_box_share_labelReference1')			 											=> "Referencia1",
		strtolower('default_app_box_share_divResumenAbono')			 											=> "",
		strtolower('default_app_box_share_divStart')			 												=> "",
		strtolower('default_app_box_share_divFecha')			 												=> "",
		strtolower('default_app_box_share_divAplicado')			 												=> "",
		strtolower('default_app_box_share_divCambio')			 												=> "",
		strtolower('default_app_box_share_comboStyle')			 												=> "select2",
		strtolower('default_app_box_share_new_script_validate_reference1')										=> "",
		strtolower('default_app_box_share_javscriptVariable_varShareMountDefaultOfAmortization')				=> "true",
		strtolower('default_app_box_share_javscriptVariable_varPrinterOnlyFormat')								=> "false",
		strtolower('default_app_box_share_TableColumnDocumento')												=> "",		
		strtolower('default_app_box_share_divCustomerControlBuscar')											=> "",
		strtolower('default_app_box_share_divCustomerControlSelected')											=> "hidden",
		strtolower('default_app_box_share_divCobrador')															=> "",
		strtolower('default_app_box_share_divMoneda')															=> "",		
		strtolower('default_app_box_share_btnVerMovimientos')													=> "hidden",
		strtolower('default_app_inventory_transferoutput_parameterValidarEnvioDestino')							=> "false",
		strtolower('default_app_inventory_transferoutput_labelReference1')										=> "Referencia",		
		strtolower('default_app_invoice_billing_divTxtCedula2') 												=> "",		
		strtolower('default_app_invoice_billing_divTraslateElement') 											=> "",		
		strtolower('default_core_dashboards_divPanelCuadroMembresia')											=> "",
		strtolower('default_core_dashboards_divPanelBiblico')													=> "",
		strtolower('default_core_dashboards_divPanelSoporteTenico')												=> "",
		strtolower('default_core_dashboards_divPanelFormaPago')													=> "",
		strtolower('default_core_dashboards_divPanelInfoPago')													=> "",
		strtolower('default_core_dashboards_divPanelUsuario')													=> "",	
		strtolower('default_app_invoice_billing_divTxtCambio') 													=> "",
		strtolower('default_app_invoice_billing_divTxtMoneda') 			 										=> "",		
		strtolower('default_app_invoice_billing_divTxtCliente2') 			 									=> "",		
		strtolower('default_app_inventory_inputunpost_new_script_validate_reference1')							=> "",
		strtolower('default_app_purchase_pedidos_divDllEstado')													=> "",
		strtolower('default_web_tools_report_helper_Edad') 														=> "Edad", 		
		strtolower('default_web_tools_report_helper_Sexo') 														=> "Sexo", 				
					
		strtolower('default_app_cxc_customer_divTxtNombres') 													=> "",
		strtolower('default_app_cxc_customer_divTxtApellidos') 			 										=> "",		
		strtolower('default_app_cxc_customer_divTxtNombreComercial') 			 								=> "",
		strtolower('default_app_cxc_customer_divTxtEstado') 													=> "",
		strtolower('default_app_cxc_customer_divTxtClasificacion') 												=> "",
		strtolower('default_app_cxc_customer_divTxtTipo') 			 											=> "",		
		strtolower('default_app_cxc_customer_divTxtSubCategoria') 			 									=> "",
		strtolower('default_app_cxc_customer_divTxtEstadoCivil') 			 									=> "",
		strtolower('default_app_cxc_customer_divTxtProfesionUFicio') 			 								=> "",
		strtolower('default_app_cxc_customer_divScriptCustom') 													=> "",	
		strtolower('default_app_cxc_customer_divTxtCategoria') 													=> "",		
		strtolower('default_app_cxc_customer_Clasificacion')													=> "Clasificacion",
		strtolower('default_app_cxc_customer_Categoria')														=> "Categoria",
		strtolower('default_app_cxc_customer_Referencia1')														=> "Referencia 1",
		strtolower('default_app_cxc_customer_Referencia2')														=> "Referencia 2",
		strtolower('default_app_cxc_customer_Referencia3')														=> "Referencia 3",
		strtolower('default_app_cxc_customer_Referencia4')														=> "Referencia 4",
		strtolower('default_app_cxc_customer_Referencia5')														=> "Referencia 5",
					
		strtolower('default_app_inventory_item_label_price_PUBLICO')			 								=> "PUBLICO",
		strtolower('default_app_inventory_item_label_price_POR MAYOR')			 								=> "POR MAYOR",
		strtolower('default_app_inventory_item_label_price_CREDITO')			 								=> "CREDITO",
		strtolower('default_app_inventory_item_label_price_CREDITO POR MAYOR')			 						=> "CREDITO POR MAYOR",
		strtolower('default_app_inventory_item_label_price_ESPECIAL')			 								=> "ESPECIAL",						
		strtolower('default_app_inventory_item_Conceptos')			 											=> "Conceptos",
		strtolower('default_app_inventory_item_labelBarCode')													=> "Barra",
		strtolower('default_app_inventory_item_divTxtPresentacionUM') 											=> "",
		strtolower('default_app_inventory_item_divTxtPresentacion') 			 								=> "",
		strtolower('default_app_inventory_item_divTxtUM') 			 											=> "",
		strtolower('default_app_inventory_item_divTxtCapacidad') 			 									=> "",
		strtolower('default_app_inventory_item_divTxtCantidadMinima') 											=> "",
		strtolower('default_app_inventory_item_divTxtCantidadMaxima') 											=> "",
		strtolower('default_app_inventory_item_divTxtSKUCompras') 			 									=> "",
		strtolower('default_app_inventory_item_divTxtSKUProduccion') 											=> "",
		strtolower('default_app_inventory_item_divTxtEstado') 			 										=> "",
		strtolower('default_app_inventory_item_divTxtFamilia') 			 										=> "",
		strtolower('default_app_inventory_item_divTxtBarCode') 													=> "",
		strtolower('default_app_inventory_item_divTxtPerecedero') 			 									=> "",	
		strtolower('default_app_inventory_item_divTraslateElementTablePrecio') 									=> "",				
		strtolower('default_app_inventory_item_Descripcion')			 										=> "Descripcion",
		strtolower('default_app_inventory_item_*Familia')														=> "*Familia",
		strtolower('default_app_inventory_item_*Presentacion')													=> "*Presentacion",
		strtolower('default_app_inventory_item_Perecedero')														=> "Perecedero",
		strtolower('default_app_inventory_item_*UM. Presentacion')												=> "*UM. Presentacion",
		strtolower('default_app_inventory_item_*SKU Compras')													=> "* SKU Compras",
		strtolower('default_app_inventory_item_*SKU Produccion')												=> "* SKU Produccion",
		strtolower('default_app_inventory_item_*Cantidad Minima')												=> "* Cantidad Minima",
		strtolower('default_app_inventory_item_*Cantidad Maxima')												=> "* Cantidad Maxima",
		strtolower('default_app_inventory_item_Servicio')														=> "Servicio",		
		strtolower('default_app_inventory_item_*Categoria')														=> "*Categoria",
		strtolower('default_app_inventory_item_*Capacidad')														=> "*Capacidad",		
		strtolower('default_app_inventory_item_Marca')															=> "Marca",
		strtolower('default_app_inventory_item_Modelo')															=> "Modelo",
		strtolower('default_app_inventory_item_Serie ó MAI')													=> "Serie o MAI",		
		strtolower('default_app_inventory_item_fieldInmobiliaria')												=> "",
		strtolower('default_app_purchase_pedidos_divScriptCustom')												=> "",		
		strtolower('default_app_invoice_billing_txtTraductionVendedor') 		 								=> "Vendedor",
		strtolower('default_app_invoice_billing_txtTraductionMesa') 		 									=> "Mesa",
		/*Baru*/
		strtolower('baruh_core_web_printer_direct_executePrinter80mmFooter')									=> "\nNo se aceptan devoluciones.\nRevisar antes de salir",
		
		/*Ebenezer*/
		strtolower('ebenezer_core_web_menu_CXC')			 													=> "ALUMNOS",
		strtolower('ebenezer_core_web_menu_CLIENTES')			 												=> "REG. ALUMNO",
		strtolower('ebenezer_core_web_menu_ABONO')			 													=> "PAGO",
		strtolower('ebenezer_core_web_menu_PRODUCTO')			 												=> "PRO./SER.",
		strtolower('ebenezer_app_cxc_customer_labelTitlePageList')												=> "ALUMNOS",
		strtolower('ebenezer_app_cxc_customer_labelTitlePageNew')												=> "ALUMNO",
		strtolower('ebenezer_app_cxc_customer_labelTitlePageEdit')												=> "ALUMNO",		
		strtolower('ebenezer_app_box_share_labelTitlePageList')													=> "PAGOS",
		strtolower('ebenezer_app_box_share_labelTitlePageNew')													=> "PAGO",
		strtolower('ebenezer_app_box_share_labelTitlePageEdit')													=> "PAGO",
		strtolower('ebenezer_app_box_share_lblCliente')															=> "Alumno",
		strtolower('ebenezer_app_cxc_customer_divTxtPresupuesto')												=> "hidden",
		strtolower('ebenezer_app_cxc_customer_divTxtSubCategoria')												=> "hidden",
		strtolower('ebenezer_app_cxc_customer_divTxtCategoria')													=> "hidden",	
		strtolower('ebenezer_app_cxc_customer_divTxtTipo')														=> "hidden",
		strtolower('ebenezer_app_cxc_customer_divTxtFullNameCommercial')										=> "Tutor",
		strtolower('ebenezer_app_cxc_customer_lblTxtIdentification')											=> "Codigo de alumno",
		strtolower('ebenezer_app_cxc_customer_divTxtHuella')													=> "hidden",
		strtolower('ebenezer_app_cxc_customer_divTxtBuro')														=> "hidden",
		strtolower('ebenezer_app_invoice_billing_labelTitlePageList')											=> "Recibos",
		strtolower('ebenezer_app_invoice_billing_labelTitlePageNew')											=> "Recibos",
		strtolower('ebenezer_app_invoice_billing_labelTitlePageEdit')											=> "Recibos",
		strtolower('ebenezer_app_invoice_billing_divTxtClienteBeneficiario')									=> "Alumno",
		strtolower('ebenezer_app_invoice_billing_divTxtClienteBeneficiarioPrincipal')							=> "Alumno",
		
		
		
		
		
		strtolower('ebenezer_app_invoice_billing_scriptValidateCustomer')										=> "
		
			if($('#txtNote').val() == 'sin comentarios.' )
			{
				fnShowNotification('Escribir comentario','error',timerNotification);
				result = false;
				fnWaitClose();
			}
		
		",

		

		/*Farmacia LM*/
		strtolower('farma_lm_app_invoice_billing_divOpcionViewA4') 													=> "",
		strtolower('farma_lm_web_tools_report_helper_reporteA4TransactionMasterInvoiceA4Opcion1AutorizationDgi')	=> "",
		
		/*Mini Ferreteria Shalom*/		
		strtolower('mini_ferreteria_shalom_web_tools_report_helper_reporte80mmTransactionMaster_Devolucion')		=> "	
			</br>
			<tr>
			  <td colspan='3' style='text-align:center'>
				No le digas a Dios cuáles tu problema , mejor dile al problema quien es tu Dios ( pon a Dios sobre todas tus cosas )
			  </td>
			</tr>
			</br>
			<tr>
			  <td colspan='3' style='text-align:center'>
				No se aceptan devoluciones.                
			  </td>
			</tr>
			
		",
		
		/*Ferreteria Mateo*/			
		strtolower('ferreteria_mateo_app_invoice_billing_bodyListInvoice')	 									=> "height: 550px; overflow: scroll;",
		strtolower('ferreteria_mateo_app_box_share_stylePage')	 												=> "/*posMe stylePage*/ #content .row{ margin-bottom:0px !important; } .email-bar{ margin-bottom:0px !important; } .form-group{ margin-bottom:0px !important; } .si_ferreteria_mateo{ display:none !important; } ",
					
		/*Clinica larreynaga*/			
		strtolower('clinicalarreynaga_web_tools_report_helper_Edad') 											=> "Genero", 		
		strtolower('clinicalarreynaga_web_tools_report_helper_Sexo') 											=> "Sexo", 		
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtNombres') 											=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtApellidos') 			 							=> "hidden",		
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtNombreComercial') 			 						=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtEstado') 											=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtClasificacion') 									=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtTipo') 			 								=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtCategoria') 										=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtSubCategoria') 			 						=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtEstadoCivil') 			 							=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtProfesionUFicio') 			 						=> "hidden",		
        strtolower('clinicalarreynaga_app_cxc_customer_divScriptCustom')                                         => "<script> $(document).ready(function(){ $(document).on('focusout','#txtLegalName',function(){ var varLegalName     = $('#txtLegalName').val(); $('#txtFirstName').val(  varLegalName  ); $('#txtLastName').val(  varLegalName  ); $('#txtCommercialName').val(  varLegalName  ); $('#txtIdentification').val(  'ND'  ); });});</script>",
		
		/*Exceso*/			
		strtolower('exceso_app_inventory_item_labelBarCode')													=> "Barra / IMAI", 
		strtolower('exceso_app_inventory_item_divTxtCapacidad') 			 									=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtCantidadMinima') 											=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtCantidadMaxima') 											=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtSKUCompras') 			 									=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtSKUProduccion') 											=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtEstado') 			 										=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtFamilia') 			 										=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtUM') 			 											=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtPresentacion') 			 									=> "hidden", 
		strtolower('exceso_app_inventory_item_divTxtPresentacionUM') 											=> "hidden", 
		
		
		
		
		/*KHADASH*/		
		//strtolower('khadash_app_box_share_divResumenAbono')			 										=> "hidden", 
		//strtolower('khadash_app_box_share_divStart')			 												=> "hidden", 
		//strtolower('khadash_app_box_share_divFecha')			 												=> "hidden", 
		//strtolower('khadash_app_box_share_divAplicado')			 											=> "hidden", 
		//strtolower('khadash_app_box_share_divCambio')			 												=> "hidden", 
		//strtolower('khadash_app_box_share_comboStyle')			 											=> "", 
		//strtolower('khadash_app_box_share_javscriptVariable_varShareMountDefaultOfAmortization')				=> "false", 
		//strtolower('khadash_app_box_share_TableColumnDocumento')												=> "hidden", 
		//strtolower('khadash_app_box_share_btnVerMovimientos')													=> "hidden", 
		//strtolower('khadash_app_box_share_javscriptVariable_varPrinterOnlyFormat')							=> "true", 
		//strtolower('khadash_app_box_share_divCustomerControlBuscar')											=> "", 
		//strtolower('khadash_app_box_share_divCustomerControlSelected')										=> "hidden", 
		//strtolower('khadash_app_box_share_divCobrador')														=> "hidden", 
		//strtolower('khadash_app_box_share_divMoneda')															=> "hidden", 
		strtolower('khadash_core_web_menu_SIMULADOR')			 				=> "CALCULADORA",
		strtolower('khadash_core_web_menu_FACTURACION')			 				=> "PRESTAMOS",
		strtolower('khadash_core_web_menu_FACTURAR')			 				=> "PRESTAR",
		strtolower('khadash_core_web_menu_VENTAS')			 					=> "DESEMBOLSO",
		strtolower('khadash_core_web_menu_DETALLE DE VENTAS')			 		=> "DETALLE DE DESEMBOLSOS",
		strtolower('khadash_core_web_menu_RESUMEN DE VENTAS')			 		=> "RESUMEN DE DESEMBOLSOS",
		strtolower('khadash_core_web_menu_COBRANZA')			 				=> "CONF. RUTAS",
		strtolower('khadash_core_web_menu_RRHH')			 					=> "RRHH RUTAS",
		strtolower('khadash_core_web_menu_COLABORADORES')			 			=> "COLAB. RUTA.",
		strtolower('khadash_app_collection_manager_lblTitleList')			 	=> "CLIENTES POR RUTAS",
		strtolower('khadash_app_collection_manager_lblTitleAdd')			 	=> "AGREGAR CLIENTE A LA RUTA",
		strtolower('khadash_app_collection_manager_lblColaborador')			 	=> "Ruta",
		strtolower('khadash_app_rrhh_employee_lblList')			 				=> "LISTA DE RUTAS Y/O COLABORADOR",
		strtolower('khadash_app_rrhh_employee_lblNew')			 				=> "NUEVA RUTA Y/O COLABORADOR",
		strtolower('khadash_app_rrhh_employee_lblEdit')			 				=> "EDITAR RUTA Y/O COLABORADOR",
		strtolower('khadash_app_cxc_customer_divTxtCategoria')			 		=> "hidden",
		strtolower('khadash_app_cxc_customer_divTxtFullNameCommercial')			=> "Categoria",
		strtolower('khadash_app_cxc_customer_lblTxtPhoneTemp')					=> "Prefesion u Oficio",
		strtolower('khadash_app_cxc_customer_divTxtProfesionUFicio')			=> "hidden",
		
		
		strtolower('khadash_app_cxc_customer_showBtnIrBuro')			 		=> "",
		strtolower('khadash_app_cxc_customer_showBtnIrSimulador')			 	=> "",
		strtolower('khadash_app_cxc_customer_showBtnIrInvoice')			 		=> "",		
		strtolower('khadash_app_cxc_customer_showBtnIrShare')			 		=> "",				
		strtolower('khadash_app_cxc_customer_showBtnGroupAcciones')	 			=> "",				
		strtolower('khadash_app_cxc_record_showBtnIrCustomerOfRecord') 			=> "",
		strtolower('khadash_app_cxc_simulation_showBtnIrCustomerOfSimulator') 	=> "",
		strtolower('khadash_app_box_share_showBtnIrCustomerOfShare') 			=> "",
		strtolower('khadash_app_cxc_customer_divScriptValideFunction') 	 		=> "
		
		if( $('#txtIdentification').val()  == ''){
			fnShowNotification('Escribir cedula','error',timerNotification);
			result = false;
		}
		if( $('#txtIdentification').val()  == '0'){
			fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
		}
		
		const regex = /^\d{3}-\d{6}-\d{4}[A-Za-z]$/;
		if(!regex.test(   $('#txtIdentification').val()   )){
            fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
        } 
		",
		
		/*Credi Flash*/				
		strtolower('credi_flash_core_web_menu_SIMULADOR')			 				=> "CALCULADORA",
		strtolower('credi_flash_core_web_menu_FACTURACION')			 				=> "PRESTAMOS",
		strtolower('credi_flash_core_web_menu_FACTURAR')			 				=> "PRESTAR",
		strtolower('credi_flash_core_web_menu_VENTAS')			 					=> "DESEMBOLSO",
		strtolower('credi_flash_core_web_menu_DETALLE DE VENTAS')			 		=> "DETALLE DE DESEMBOLSOS",
		strtolower('credi_flash_core_web_menu_RESUMEN DE VENTAS')			 		=> "RESUMEN DE DESEMBOLSOS",
		strtolower('credi_flash_core_web_menu_COBRANZA')			 				=> "CONF. RUTAS",
		strtolower('credi_flash_core_web_menu_RRHH')			 					=> "RRHH RUTAS",
		strtolower('credi_flash_core_web_menu_COLABORADORES')			 			=> "COLAB. RUTA.",
		strtolower('credi_flash_app_collection_manager_lblTitleList')			 	=> "CLIENTES POR RUTAS",
		strtolower('credi_flash_app_collection_manager_lblTitleAdd')			 	=> "AGREGAR CLIENTE A LA RUTA",
		strtolower('credi_flash_app_collection_manager_lblColaborador')			 	=> "Ruta",
		strtolower('credi_flash_app_rrhh_employee_lblList')			 				=> "LISTA DE RUTAS Y/O COLABORADOR",
		strtolower('credi_flash_app_rrhh_employee_lblNew')			 				=> "NUEVA RUTA Y/O COLABORADOR",
		strtolower('credi_flash_app_rrhh_employee_lblEdit')			 				=> "EDITAR RUTA Y/O COLABORADOR",
		strtolower('credi_flash_app_cxc_customer_divTxtCategoria')			 		=> "hidden",
		strtolower('credi_flash_app_cxc_customer_divTxtFullNameCommercial')			=> "Categoria",
		strtolower('credi_flash_app_cxc_customer_lblTxtPhoneTemp')					=> "Prefesion u Oficio",
		strtolower('credi_flash_app_cxc_customer_divTxtProfesionUFicio')			=> "hidden",
		strtolower('credi_flash_app_cxc_customer_showBtnIrBuro')			 		=> "",
		strtolower('credi_flash_app_cxc_customer_showBtnIrSimulador')			 	=> "",
		strtolower('credi_flash_app_cxc_customer_showBtnIrInvoice')			 		=> "",		
		strtolower('credi_flash_app_cxc_customer_showBtnIrShare')			 		=> "",				
		strtolower('credi_flash_app_cxc_customer_showBtnGroupAcciones')	 			=> "hidden",				
		strtolower('credi_flash_app_cxc_customer_showBtnViewLeads')	 				=> "hidden",				
		strtolower('credi_flash_app_cxc_customer_showBtnPrinter')	 				=> "hidden",				
		strtolower('credi_flash_app_cxc_customer_showBtnDelete')	 				=> "hidden",				
		strtolower('credi_flash_app_cxc_record_showBtnIrCustomerOfRecord') 			=> "",
		strtolower('credi_flash_app_cxc_simulation_showBtnIrCustomerOfSimulator') 	=> "",
		strtolower('credi_flash_app_box_share_showBtnIrCustomerOfShare') 			=> "hidden",
		strtolower('credi_flash_app_box_share_btnVerMovimientos') 					=> "hidden",
		strtolower('credi_flash_app_invoice_billing_panelSummaryInvoice') 			=> "hidden",
		strtolower('credi_flash_app_invoice_billing_panelResumenFactura') 			=> "hidden",
		strtolower('credi_flash_app_invoice_billing_txtScanerBarCode') 				=> "hidden",
		strtolower('credi_flash_app_invoice_billing_divTxtCambio') 					=> "hidden",
		strtolower('credi_flash_app_invoice_billing_divTxtCliente2') 				=> "hidden",
		strtolower('credi_flash_app_invoice_billing_divTxtCedula2') 				=> "hidden",		
		strtolower('credi_flash_app_invoice_billing_divPanelPagosIncome') 			=> "hidden",
		strtolower('credi_flash_app_invoice_billing_autoCloseSelectItem') 			=> "true",
		strtolower('credi_flash_app_invoice_billing_divPanelBtnMasMobileD') 		=> "hidden",		
		strtolower('credi_flash_app_invoice_billing_rowOptionPaymentExtrasTarjeta') => "hidden",
		strtolower('credi_flash_app_invoice_billing_rowOptionPaymentExtras') 		=> "hidden",
		
		
		
		
		strtolower('credi_flash_app_cxc_customer_divScriptValideFunction') 	 		=> "
		
		if( $('#txtIdentification').val()  == ''){
			fnShowNotification('Escribir cedula','error',timerNotification);
			result = false;
		}
		if( $('#txtIdentification').val()  == '0'){
			fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
		}
		
		const regex = /^\d{3}-\d{6}-\d{4}[A-Za-z]$/;
		if(!regex.test(   $('#txtIdentification').val()   )){
            fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
        } 
		",
		
	
		
		/*El patio*/
		strtolower('patio_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divMesa').appendTo('#divInformacionLeft');
			});
		</script>",		
		
		/*Santa lucia ral state*/	
		strtolower('luciaralstate_core_web_menu_PRODUCTO')			 											=> "INMOBILIARIO",		
		strtolower('luciaralstate_default_masterpage_backgroundImage')		 									=> "style='background-image: url(".  base_url()."/resource/img/logos/fondo_globalpro.jpg"   .");'",		
		strtolower('luciaralstate_app_cxc_customer_Clasificacion')												=> "Estilo de propiedad",
		strtolower('luciaralstate_app_cxc_customer_Categoria')													=> "Interes",
		strtolower('luciaralstate_app_cxc_customer_Referencia1')												=> "Id encuentra 24",
		strtolower('luciaralstate_app_cxc_customer_Referencia2')												=> "Mensaje",
		strtolower('luciaralstate_app_cxc_customer_Referencia3')												=> "Comentario 1",
		strtolower('luciaralstate_app_cxc_customer_Referencia4')												=> "Comentario 2",
		strtolower('luciaralstate_app_cxc_customer_Referencia5')												=> "Ubicacion",
		strtolower('luciaralstate_app_cxc_customer_txtDomicilio')												=> "Ubicacion de interes",
		
		strtolower('luciaralstate_app_inventory_item_*Familia')													=> "Tipo de propiedad",
		strtolower('luciaralstate_app_inventory_item_*Presentacion')											=> "Proposito",
		strtolower('luciaralstate_app_inventory_item_Perecedero')												=> "Amueblado",
		strtolower('luciaralstate_app_inventory_item_*UM. Presentacion')										=> "Disponible",
		strtolower('luciaralstate_app_inventory_item_*SKU Compras')												=> "Baños",
		strtolower('luciaralstate_app_inventory_item_*SKU Produccion')											=> "Habitaciones",
		strtolower('luciaralstate_app_inventory_item_*Cantidad Minima')											=> "Niveles",
		strtolower('luciaralstate_app_inventory_item_*Cantidad Maxima')											=> "Horas antes de visita",
		strtolower('luciaralstate_app_inventory_item_Servicio')													=> "Disponible",				
		strtolower('luciaralstate_app_inventory_item_fieldInmobiliariaPais')									=> "hidden",		
		strtolower('luciaralstate_app_inventory_item_fieldInmobiliariaDepartamento')							=> "hidden",		
		strtolower('luciaralstate_app_inventory_item_fieldInmobiliariaMunicipio')								=> "hidden",
		strtolower('luciaralstate_app_inventory_item_*Categoria')												=> "Diseño de propiedad",
		strtolower('luciaralstate_app_inventory_item_*Capacidad')												=> "Aires",		
		strtolower('luciaralstate_app_inventory_item_Marca')													=> "Area de contruccion M2",
		strtolower('luciaralstate_app_inventory_item_Modelo')													=> "Area de terreno VR2",
		strtolower('luciaralstate_app_inventory_item_Serie ó MAI')												=> "ID encuentra 24",		
		strtolower('luciaralstate_app_inventory_item_labelBarCode')												=> "ID página web",
		strtolower('luciaralstate_app_inventory_item_Descripcion')			 									=> "Direccion",
		strtolower('luciaralstate_app_inventory_item_label_price_PUBLICO')			 							=> "PRECIO DE VENTA",
		strtolower('luciaralstate_app_inventory_item_label_price_POR MAYOR')			 						=> "PRECIO DE RENTA",
		strtolower('luciaralstate_app_inventory_item_label_price_CREDITO')			 							=> "----",
		strtolower('luciaralstate_app_inventory_item_label_price_CREDITO POR MAYOR')			 				=> "----",
		strtolower('luciaralstate_app_inventory_item_label_price_ESPECIAL')			 							=> "----",		
		strtolower('luciaralstate_app_inventory_item_btnDelete')			 									=> "hidden",	
		strtolower('luciaralstate_app_cxc_customer_divTxtNombres')												=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtApellidos')											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtNombreComercial')										=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtTypeIdentification')									=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtIdentification')										=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtSubCategoria')											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtEstadoCivil')											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtProfesionUFicio')										=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtTypeFirmID')											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divPestanaCXC') 	 											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divPestanaCXCLineas') 	 									=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtFechaContacto') 	 									=> "",
		strtolower('luciaralstate_app_cxc_customer_divTxtFechaNacimiento') 	 									=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divPestanaTelefono') 	 									=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtBuro') 	 											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtHuella') 	 											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divPestanaMas') 	 											=> "hidden",		
		strtolower('luciaralstate_app_cxc_customer_divTxtPais') 	 											=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtDepartamento') 	 									=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtMunicipio') 	 										=> "hidden",
		strtolower('luciaralstate_app_cxc_customer_divTxtFormContact') 	 										=> "",
        strtolower('luciaralstate_app_inventory_item_divTxtBirthday') 	 									=> "",
        strtolower('luciaralstate_app_inventory_item_divBirthday') 	 									    => "
		    $('#txtDateLastUse').datepicker({
		        format: 'mm-dd'
		    });
		",
		strtolower('luciaralstate_app_inventory_item_divTraslate') 												=> "
		<script>
			$(document).ready(function(){				 
				$('#txtRealStateStyleKitchen').parent().parent().appendTo('#divTraslateQuantityMax');  
				$('#txtQuantityMax').parent().parent().appendTo('#divTraslateElemento1');  
			});
		</script>",		
		
		strtolower('luciaralstate_app_inventory_item_scriptValidate') 											=> "		
		//Validar que el campo sea solo numero
		var regexOnlyNumber = /^[0-9]+$/;		
		if (!regexOnlyNumber.test($('#txtBarCode').val())) 
		{
			fnShowNotification('El campo (ID página web) solo puede contener numeros ','error',timerNotification);
			result = false;
		}
		
		//Validar que el campo sea solo numero
		var regexOnlyNumber = /^[0-9]+$/;		
		if (!regexOnlyNumber.test($('#txtRealStatePhone').val())) 
		{
			fnShowNotification('El campo Telefono solo puede contener numeros ','error',timerNotification);
			result = false;
		}
		
		//Validar que el campo sea solo numero		
		var regexOnlyNumber = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
		if (!regexOnlyNumber.test($('#txtRealStateEmail').val())) 
		{
			fnShowNotification('El campo Email no es valido','error',timerNotification);
			result = false;
		}
		
		
		//Validar que sea un enlace correcto
		var regexOnlyNumber = /^(ftp|http|https):\/\/[^ \"]+$/;
		if (!regexOnlyNumber.test($('#txtRealStateLinkPaginaWeb').val())) 
		{
			fnShowNotification('El campo (Página web) debe ser un enlace válido.','error',timerNotification);
			result = false;
		}
		
		
		//Validar el precio de renta o precio de venta la suma no pueden dar 0
		var totalPrecio = 0;
		for(var il = 0 ; il < 2 ; il++)
		{
			var rowx = $($($('#body_detail_precio tr')[il]).find('td')[1]).find('input')[0];
			rowx = $(rowx).val();
			totalPrecio = totalPrecio + parseInt(rowx);
			
		}
		
		if(totalPrecio <= 1)
		{
			fnShowNotification('Precio y renta no pude ser menor a 0','error',timerNotification);
			result = false;
		}
		
		//Obtener precio de venta 
		var precioVenta = 0;
		for(var il = 0 ; il < 1 ; il++)
		{
			var rowx = $($($('#body_detail_precio tr')[il]).find('td')[1]).find('input')[0];
			rowx = $(rowx).val();
			precioVenta = precioVenta + parseInt(rowx);
			
		}
		
		
		//Obtener precio de renta 
		var precioRenta = 0;
		for(var il = 1 ; il < 2 ; il++)
		{
			var rowx = $($($('#body_detail_precio tr')[il]).find('td')[1]).find('input')[0];
			rowx = $(rowx).val();
			precioRenta = precioRenta + parseInt(rowx);
			
		}
		
		if(precioRenta == precioVenta)
		{
			fnShowNotification('Precio y Renta no pueden ser iguales','error',timerNotification);
			result = false;
		}
		
		
		",		
		
		strtolower('luciaralstate_app_cxc_customer_divScriptCustom') 											=> "
		<script>
		$(document).ready(function(){ 
			$('#txtIdentification').val('0');				
			
			//$('#divTxtLeadsSubTipo').insertAfter('#divTxtCategoryE');
			//$('#lblLeadSubTipoLeads').addClass('col-lg-4');
			//$('#lblLeadSubTipoLeads').addClass('control-label');																															
			//$('#s2id_txtLeadSubTipo').wrap('<div class=\"col-lg-8\"></div>');
			
			
			$(document).on('focusout','#txtLegalName',function(){ 									 
				var varLegalName 	= $('#txtLegalName').val(); 
				$('#txtFirstName').val(varLegalName  ); 
				$('#txtLastName').val(varLegalName  ); 
				$('#txtCommercialName').val(varLegalName); 	 
			}); 
			
			
		}); 
		</script> 
		",
		strtolower('luciaralstate_app_cxc_customer_divScriptValideFunction') 	 								=> "
		
		if( $('#txtPhoneNumber').val()  == ''){
			fnShowNotification('Escribir Telefono','error',timerNotification);
			result = false;
		}
		
		if(!/^\d+$/.test(   $('#txtPhoneNumber').val()   )){
            fnShowNotification('Escribir Telefono solo puede tener números','error',timerNotification);
			result = false;
        } 
		
		if( $('#txtReference1').val()  == ''){
			fnShowNotification('Escribir ID','error',timerNotification);
			result = false;
		}
		
		if(!/^\d+$/.test(   $('#txtReference1').val()   )){
            fnShowNotification('Escribir ID Encuentra 24 solo puede tener números','error',timerNotification);
			result = false;
        } 
		
		
		",	
		strtolower('luciaralstate_app_cxc_customer_divScriptReady') 	 										=> "		
		$('#txtLegalName').on('blur', function() 
		{
			
			var regex = /^[A-Za-z]+$/;
			var regex = regex.test($(this).val());
	
			if (!regex) {
				$(this).focus(); // Volver a enfocar el campo de entrada
				$(this).trigger('input'); // Disparar el evento de entrada para validar la entrada
			}
		});

		$('#txtPhoneNumber').on('blur', function() 
		{
			
			var regex = /^[\d()+\-]+$/;
			var regex = regex.test($(this).val());
	
			if (!regex) {
				$(this).focus(); // Volver a enfocar el campo de entrada
				$(this).trigger('input'); // Disparar el evento de entrada para validar la entrada
				fnShowNotification('Escribir Telefono solo puede tener números','error',5000);
			}
		});
		
		$('#txtReference1').on('blur', function() 
		{
			
			var regex = /^[0-9]+$/;
			var regex = regex.test($(this).val());
	
			if (!regex) {
				$(this).focus(); // Volver a enfocar el campo de entrada
				$(this).trigger('input'); // Disparar el evento de entrada para validar la entrada
				fnShowNotification('Escribir ID Encuentra 24 solo puede tener números','error',5000);
			}
		});",
		
        
		strtolower('luciaralstate_app_inventory_item_divTxtEstado') 			 								=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divTxtUM') 			 									=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divTxtPresentacionUM')  									=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divTxtBodega') 		 									=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divTxtCantidad') 	 										=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divTxtCosto') 		 										=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divTxtCantidadZero')  										=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divTxtFacturable') 	 									=> "hidden",
		strtolower('luciaralstate_app_inventory_item_menuBodegaPestana') 	 									=> "hidden",
		strtolower('luciaralstate_app_inventory_item_menuBodegaPestana') 	 									=> "hidden",
		strtolower('luciaralstate_app_inventory_item_divControlCreatedOn') 	 									=> "",
		strtolower('luciaralstate_app_inventory_item_divControlModifiedOn') 	 								=> "",	
		strtolower('luciaralstate_app_inventory_item_fieldExclusiveGerencia') 	 								=> "",	
		
				
		/*El blue moon*/
		strtolower('bluemoon_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divMesa').appendTo('#divInformacionLeft');
				$('#divZone').appendTo('#divInformacionRight');				
			});
		</script>",
		
		//Daleska
		strtolower('daleska_app_box_share_showBtnPrinterInvoiceCancel')											=> "",
		
		//Veterinaria la Bendicion
		strtolower('veterinaria_bendicion_app_inventory_item_Marca')											=> "Vencimiento",
		strtolower('veterinaria_bendicion_app_inventory_item_fieldInmobiliaria')								=> "hidden",
		strtolower('veterinaria_bendicion_app_inventory_item_fieldEquiposModelo')								=> "hidden",
		strtolower('veterinaria_bendicion_app_inventory_item_fieldEquiposSerie')								=> "hidden",		
		strtolower('veterinaria_bendicion_app_inventory_item_divTraslate') 										=> "
		<script>
			$(document).ready(function(){				 
				$('#txtReference1').parent().parent().appendTo('#divTraslateElemento2');  
			});
		</script>",	
		strtolower('veterinaria_bendicion_app_inventory_item_scriptValidate') 									=> "		
		//Validar fecha de vencimiento
		var regexOnlyNumber =  /^\d{4}-\d{2}-\d{2}$/;
		if (!regexOnlyNumber.test($('#txtReference1').val())) 
		{
			fnShowNotification('Fecha de vencimiento debe tener el formato YYYY-MM-DD','error',timerNotification);
			result = false;
		}
		
		",	
		
		/*Galmcuts*/
		strtolower('galmcuts_app_invoice_billing_txtTraductionVendedor') 		 									=> "Barvero",
		strtolower('galmcuts_app_invoice_billing_txtTraductionMesa') 		 										=> "Sala",
		strtolower('galmcuts_app_invoice_billing_divLabelZone') 		 											=> "Hora",
		strtolower('galmcuts_app_invoice_billing_divMesa') 		 													=> "hidden",
		strtolower('galmcuts_app_invoice_billing_divTraslateElement') 												=> "
		<script>
			$(document).ready(function(){	
				debugger;
				var tivReferencia 	   = $('#divReferencia').html();
				var tivSiguienteVisita = $('#divSiguienteVisita').html();
				
                $('#divReferencia').html(tivSiguienteVisita);
                $('#divSiguienteVisita').html(tivReferencia);
				
				if( 
					$('#txtUserID').val() == '494' || 
					$('#txtUserID').val() == '495' || 
					$('#txtUserID').val() == '496' 
				)
				{
					$($('.btnAcept')[0]).addClass('hidden');
				}
				
			});
		</script>",
		
		/*Titanes*/
		strtolower('titanes_core_web_menu_CXP')			 															=> "PROVEEDORES",
		strtolower('titanes_app_inventory_item_Serie ó MAI')	 													=> "Rubro",
		strtolower('titanes_app_invoice_billing_rowOptionPaymentExtras')	 										=> "hidden",
		strtolower('titanes_app_invoice_billing_rowOptionPaymentExtrasTarjeta')	 									=> "hidden",
		strtolower('titanes_app_invoice_billing_rowOptionAmountReceiptExtranjera')	 								=> "hidden",
		strtolower('titanes_app_invoice_billing_txtTraductionMesa')	 												=> "Forma de pago",
		strtolower('titanes_app_invoice_billing_txtTraductionPhone')	 											=> "Banco",		
		strtolower('titanes_app_invoice_billing_divTraslateElement') 												=> "
		<script>
			$(document).ready(function(){
				$('#divMesa').appendTo('#divTransactionPhoneBefore');
			});
		</script>",
		
		
		//Funeraria Blandon
		strtolower('fn_blandon_core_web_menu_FACTURACION')			 												=> "CONTRATOS",
		strtolower('fn_blandon_core_web_menu_FACTURAR')			 													=> "CONTRATO",
		strtolower('fn_blandon_core_web_menu_ABONO')			 													=> "RECIBO",
		strtolower('fn_blandon_app_invoice_billing_divLabelZone') 		 											=> "Parentesco",
		strtolower('fn_blandon_app_invoice_billing_txtTraductionPhone')	 											=> "Tel. Bene.",
		strtolower('fn_blandon_app_invoice_billing_divTxtClienteBeneficiario')	 									=> "Bene. Nombre",
		strtolower('fn_blandon_app_invoice_billing_divTxtCedulaBeneficiario')	 									=> "Bene. Cedula",		
		strtolower('fn_blandon_app_invoice_billing_labelTitlePageList')	 											=> "CONTRATOS",
		strtolower('fn_blandon_app_invoice_billing_labelTitlePageEdit')	 											=> "Contrato",
		strtolower('fn_blandon_app_invoice_billing_labelTitlePageNew')	 											=> "Contrato",		
		strtolower('fn_blandon_app_box_share_labelTitlePageList')	 												=> "RECIBOS",
		strtolower('fn_blandon_app_box_share_labelTitlePageEdit')	 												=> "Recibo",
		strtolower('fn_blandon_app_box_share_labelTitlePageNew')	 												=> "Recibo",		
		strtolower('fn_blandon_app_invoice_billing_divHiddenReference')	 											=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divMesa')	 													=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divNextVisitHidden')	 											=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divBodegaHidden')	 											=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divTxtCambio')	 												=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divPrecio')	 													=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divDesembolsoEfectivo')	 										=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divReportSinRiesgo')												=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divProviderCredit')												=> "hidden",
		strtolower('fn_blandon_app_invoice_billing_divApplied')														=> "hidden",
		
		
		strtolower('fn_blandon_app_invoice_billing_txtTermReference')	 											=> "Plazo",
		strtolower('fn_blandon_app_invoice_billing_txtTraductionExpenseLabel')										=> "Interes",
		strtolower('fn_blandon_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				
				if( $('#txtTransactionMasterID').val() == undefined )
				$('#txtNote').val('');
			
				$('#divBeneficiario').appendTo('#divInformacionLeftReference');
				$('#divCedula').appendTo('#divInformacionLeftReference');
				$('#divZone').appendTo('#divInformacionLeftReference');
				$('#divTrasuctionPhone').appendTo('#divInformacionLeftReference');
				$('#divFixedExpenses').appendTo('#divInformacionRightReference');
				$('#divNote').appendTo('#divInformacionLeftZone');
				
			});
		</script>
		",		
		strtolower('fn_blandon_app_invoice_billing_scriptValidateInCredit')											=> "
		if($('#txtReferenceClientIdentifier').val() == '')
		{
				fnShowNotification('Cedula del beneficiario','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		if($('#txtReferenceClientName').val() == '')
		{
				fnShowNotification('Nombre del beneficiario','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		if($('#txtNumberPhone').val() == '')
		{
				fnShowNotification('Telefono del beneficiario','error',timerNotification);
				result = false;
				fnWaitClose();
		}		
		if($('#txtReference2').val() == '1')
		{
				fnShowNotification('Plazo del credito','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		if($('#txtEmployeeID').val() == '614')
		{
				fnShowNotification('Vendedor','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		
		",
		
		//HipperAll
		strtolower('hiperall_core_web_menu_FACTURACION')			 												=> "CONTRATOS",
		strtolower('hiperall_core_web_menu_FACTURAR')			 													=> "CONTRATO",
		strtolower('hiperall_core_web_menu_ABONO')			 														=> "RECIBO",
		strtolower('hiperall_core_web_menu_ADM TALLER')			 													=> "ADM TAREAS",
		strtolower('hiperall_core_web_menu_TALLER')			 														=> "TAREAS",
		
		strtolower('hiperall_app_purchase_taller_labelTitlePageList')	 											=> "LISTA DE TAREAS",
		strtolower('hiperall_app_purchase_taller_labelTitlePageEdit')	 											=> "EDITAR TAREA",
		strtolower('hiperall_app_purchase_taller_labelTitlePageNew')	 											=> "AGREGAR TAREA",
		strtolower('hiperall_app_purchase_taller_classTxtSucursal')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_divTxtApplied')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_divTxtChange')	 													=> "hidden",
		strtolower('hiperall_app_purchase_taller_divLabelStatus')	 												=> "Etapa",
		strtolower('hiperall_app_purchase_taller_divLabelStatusMachine')	 										=> "Prioridad",
		strtolower('hiperall_app_purchase_taller_classTxtFactura')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_labelTecnico')	 													=> "Asignar a",
		strtolower('hiperall_app_purchase_taller_labelArticulo')	 												=> "Producto",
		strtolower('hiperall_app_purchase_taller_labelMarca')	 													=> "Categoria",
		strtolower('hiperall_app_purchase_taller_classDescOtros')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_classModelo')	 													=> "hidden",
		strtolower('hiperall_app_purchase_taller_classSerie')	 													=> "hidden",
		strtolower('hiperall_app_purchase_taller_classAccesorios')	 												=> "",
		strtolower('hiperall_app_purchase_taller_divTxtCurrency')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_classProblema')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_classPassword')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_classSolucion')	 												=> "hidden",
		strtolower('hiperall_app_purchase_taller_classAmount')	 													=> "hidden",
		strtolower('hiperall_app_purchase_taller_classLabel')	 													=> "hidden",
		strtolower('hiperall_app_purchase_taller_labelNota')	 													=> "Titulo",
		strtolower('hiperall_app_purchase_taller_divTxtStatus')	 													=> "hidden",
		strtolower('hiperall_app_purchase_taller_labelAccesorios')	 												=> "Etapa",
		strtolower('hiperall_app_purchase_taller_classBtnTaller')	 												=> "hidden",		
		strtolower('hiperall_app_invoice_billing_labelTitlePageList')	 											=> "CONTRATOS",
		strtolower('hiperall_app_invoice_billing_labelTitlePageEdit')	 											=> "Contrato",
		strtolower('hiperall_app_invoice_billing_labelTitlePageNew')	 											=> "Contrato",		
		strtolower('hiperall_app_invoice_billing_txtTermReference')	 												=> "Plazo",
		strtolower('hiperall_app_invoice_billing_txtTraductionExpenseLabel')										=> "Interes",		
		strtolower('hiperall_app_box_share_labelTitlePageList')	 													=> "RECIBOS",
		strtolower('hiperall_app_box_share_labelTitlePageEdit')	 													=> "Recibo",
		strtolower('hiperall_app_box_share_labelTitlePageNew')	 													=> "Recibo",
		strtolower('hiperall_app_cxc_customer_labelAgente')															=> "Vendedor",
		strtolower('hiperall_app_cxc_customer_Clasificacion')														=> "Lenguaje",
		strtolower('hiperall_app_cxc_customer_lblTxtNombre')														=> "Empresa",
		strtolower('hiperall_app_cxc_customer_lblTxtApellidos')														=> "Clave",
		strtolower('hiperall_app_cxc_customer_lblTxtFullName')														=> "Contacto",
		strtolower('hiperall_app_cxc_customer_lblTxtIdentification')												=> "Cedula",
		strtolower('hiperall_app_cxc_customer_lblTxtPhoneTemp')														=> "Licencia",
		strtolower('hiperall_app_cxc_customer_divTxtFullNameCommercial')											=> "Codigo postal",
		strtolower('hiperall_app_cxc_customer_divTxtFechaNacimiento')												=> "hidden",
		strtolower('hiperall_app_cxc_customer_divClassSex')															=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtTypeIdentification')											=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtTipo')															=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtSubCategoria')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtEstadoCivil')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtProfesionUFicio')												=> "hidden",
		strtolower('hiperall_app_cxc_customer_divPestanaCXC')														=> "hidden",
		strtolower('hiperall_app_cxc_customer_divPestanaCXCLineas')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_divPestanaArchivos')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_classDivReference1')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_classDivReference2')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_classDivReference3')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_classDivReference4')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_classDivReference5')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtTypeFirmID')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtPresupuesto')													=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtBuro')															=> "hidden",
		strtolower('hiperall_app_cxc_customer_divTxtHuella')														=> "hidden",
		
		
		
		/*Pizza Laus*/		
		strtolower('pizza_laus_core_web_language_workflowstage_billing_REGISTRADA')			 						=> "GUARDAR MESA",
		strtolower('pizza_laus_core_web_language_workflowstage_billing_APLICADA')				 					=> "PAGAR",
		strtolower('pizza_laus_core_web_language_workflowstage_billing_REGISTRAR')				 					=> "GUARDAR MESA",
		strtolower('pizza_laus_core_web_language_workflowstage_billing_APLICAR')				 					=> "PAGAR",		
		strtolower('pizza_laus_core_web_language_workflowstage_billing_ANULADA')				 					=> "ANULADA",		
		strtolower('pizza_laus_app_invoice_billing_lablBotunConfiguracion')											=> "CONFIGURACION",
		strtolower('pizza_laus_app_invoice_billing_lablBotunVerDetalle')											=> "PRODUCTO",
		strtolower('pizza_laus_app_invoice_billing_divTxtMoneda') 													=> "hidden",
		strtolower('pizza_laus_app_invoice_billing_divTxtCambio') 													=> "hidden",				
		strtolower('pizza_laus_app_invoice_billing_divPestanaCredito') 												=> "hidden",		
		strtolower('pizza_laus_app_invoice_billing_divPestanaMas')	 												=> "hidden",
		strtolower('pizza_laus_app_invoice_billing_divPestanaReferencias')											=> "hidden",
		strtolower('pizza_laus_app_invoice_billing_divHiddenEmployer')												=> "hidden",		
		strtolower('pizza_laus_app_invoice_billing_lblReferencia')													=> "Vendedor",	
		strtolower('pizza_laus_app_invoice_billing_txtScanerBarCode')	 											=> "hidden",
		strtolower('pizza_laus_app_invoice_billing_panelResumenFacturaTool')	 									=> "hidden",
		strtolower('pizza_laus_app_invoice_billing_panelResumenFactura')	 										=> "hidden",		
		strtolower('pizza_laus_app_invoice_billing_rowOptionPaymentExtras')	 										=> "hidden",
		strtolower('pizza_laus_app_invoice_billing_panelLabelSumaryAlternativo')	 								=> "",
		strtolower('pizza_laus_app_invoice_billing_divTxtCedulaBeneficiario')	 									=> "Dirección",
		strtolower('pizza_laus_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divZone').appendTo('#divInformacionLeft');								
				$('#divMesa').appendTo('#divInformacionLeft');
				$('#divTrasuctionPhone').appendTo('#divInformacionRight');				
				$('#divTipoFactura').appendTo('#divInformacionLeft');
				$('#divReferencia').appendTo('#divInformacionRight');	
				
				//Mover opciones de pago
				$('#divPaymentOption').appendTo('#siderbar_content_right');	
				$('#divPaymentOption').removeClass('col-lg-5');
				$('#divPaymentOption').addClass('col-lg-6');
				
				//Mover panel donde se muestra los totales de la factura
				$('#divPanelShowSummaryNumber').appendTo('#siderbar_content_right');	
				$('#divPanelShowSummaryNumber').removeClass('col-lg-5');
				$('#divPanelShowSummaryNumber').addClass('col-lg-6');
				
				
				$('#heading').remove();
				$('<br/>').appendTo('#divPanelPaymentSideBar');	
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().appendTo('#divPanelPaymentSideBar');	
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().removeClass('col-lg-2');
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().addClass('col-lg-12');
				$('#panelContainterDetailInvoice').appendTo('#siderbar_content_right_factura');	
				$('#btnBar').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnFooter').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnDeleteItem').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnNewItem').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnPrinter').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnNew').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnDelete').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnOptionPago').parent().appendTo('#rowBotoneraFacturaFila4');
				$('#btnVeDetalleFactura').parent().appendTo('#rowBotoneraFacturaFila4');				
				$('#saltoDeLineaFila3').remove();
				$('#saltoDeLineaFila2').remove();
				$('#saltoDeLineaFila0').remove();
				$('#saltoDeLineaFila6').remove();
				$('#saltoDeLineaFila7').remove();
				$('#rowBotoneraFacturaFila1').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila2').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila3').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila4').appendTo('#panelComandoAlternativa2');				
				$('#btnVeDetalleFactura').removeClass('btn-primary');
				$('#btnVeDetalleFactura').addClass('btn-success');				
				$('#btnGroupdProducto').removeClass('btn-success');
				$('#btnGroupdProducto').addClass('btn-primary');				
				$('#btnOptionPago').removeClass('btn-primary');
				$('#btnOptionPago').addClass('btn-warning');				
				$('#labelTotalAlternativo').appendTo('#divPanelFacturaSideBarComandos');
				$('#labelTitleDetalle').remove();
				$('#mySidebarFactura').css('padding-top','0px');
				
				
				
				
			});
		</script>",
		
		/*Bar Exit*/		
		strtolower('bar_exit_core_web_language_workflowstage_billing_REGISTRADA')			 					=> "GUARDAR MESA",
		strtolower('bar_exit_core_web_language_workflowstage_billing_APLICADA')				 					=> "PAGAR",
		strtolower('bar_exit_core_web_language_workflowstage_billing_REGISTRAR')				 				=> "GUARDAR MESA",
		strtolower('bar_exit_core_web_language_workflowstage_billing_APLICAR')				 					=> "PAGAR",		
		strtolower('bar_exit_core_web_language_workflowstage_billing_ANULADA')				 					=> "ANULADA",		
		strtolower('bar_exit_app_invoice_billing_lablBotunConfiguracion')										=> "CONFIGURACION",
		strtolower('bar_exit_app_invoice_billing_lablBotunVerDetalle')											=> "PRODUCTO",
		strtolower('bar_exit_app_invoice_billing_divTxtMoneda') 												=> "hidden",
		strtolower('bar_exit_app_invoice_billing_divTxtCambio') 												=> "hidden",				
		strtolower('bar_exit_app_invoice_billing_divPestanaCredito') 											=> "hidden",		
		strtolower('bar_exit_app_invoice_billing_divPestanaMas')	 											=> "hidden",
		strtolower('bar_exit_app_invoice_billing_divPestanaReferencias')										=> "hidden",
		strtolower('bar_exit_app_invoice_billing_divHiddenEmployer')											=> "hidden",		
		strtolower('bar_exit_app_invoice_billing_lblReferencia')												=> "Vendedor",	
		strtolower('bar_exit_app_invoice_billing_txtScanerBarCode')	 											=> "hidden",
		strtolower('bar_exit_app_invoice_billing_panelResumenFacturaTool')	 									=> "hidden",
		strtolower('bar_exit_app_invoice_billing_panelResumenFactura')	 										=> "hidden",		
		strtolower('bar_exit_app_invoice_billing_rowOptionPaymentExtras')	 									=> "hidden",
		strtolower('bar_exit_app_invoice_billing_panelLabelSumaryAlternativo')	 								=> "",
		strtolower('bar_exit_app_invoice_billing_divTxtCedulaBeneficiario')	 									=> "Dirección",
		strtolower('bar_exit_app_invoice_billing_divApplyExoneracion')	 										=> "",
		strtolower('bar_exit_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divZone').appendTo('#divInformacionLeft');								
				$('#divMesa').appendTo('#divInformacionLeft');
				$('#divTrasuctionPhone').appendTo('#divInformacionRight');				
				$('#divTipoFactura').appendTo('#divInformacionLeft');
				$('#divReferencia').appendTo('#divInformacionRight');	
				$('#divPanelExoneracion').appendTo('#divInformacionRight');
				
				
				//Mover opciones de pago
				$('#divPaymentOption').appendTo('#siderbar_content_right');	
				$('#divPaymentOption').removeClass('col-lg-5');
				$('#divPaymentOption').addClass('col-lg-6');
				
				//Mover panel donde se muestra los totales de la factura
				$('#divPanelShowSummaryNumber').appendTo('#siderbar_content_right');	
				$('#divPanelShowSummaryNumber').removeClass('col-lg-5');
				$('#divPanelShowSummaryNumber').addClass('col-lg-6');
				
				
				$('#heading').remove();
				$('<br/>').appendTo('#divPanelPaymentSideBar');	
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().appendTo('#divPanelPaymentSideBar');	
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().removeClass('col-lg-2');
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().addClass('col-lg-12');
				$('#panelContainterDetailInvoice').appendTo('#siderbar_content_right_factura');	
				$('#btnBar').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnFooter').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnDeleteItem').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnNewItem').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnPrinter').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnNew').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnDelete').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnOptionPago').parent().appendTo('#rowBotoneraFacturaFila4');
				$('#btnVeDetalleFactura').parent().appendTo('#rowBotoneraFacturaFila4');				
				$('#saltoDeLineaFila3').remove();
				$('#saltoDeLineaFila2').remove();
				$('#saltoDeLineaFila0').remove();
				$('#saltoDeLineaFila6').remove();
				$('#saltoDeLineaFila7').remove();
				$('#rowBotoneraFacturaFila1').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila2').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila3').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila4').appendTo('#panelComandoAlternativa2');				
				$('#btnVeDetalleFactura').removeClass('btn-primary');
				$('#btnVeDetalleFactura').addClass('btn-success');				
				$('#btnGroupdProducto').removeClass('btn-success');
				$('#btnGroupdProducto').addClass('btn-primary');				
				$('#btnOptionPago').removeClass('btn-primary');
				$('#btnOptionPago').addClass('btn-warning');				
				$('#labelTotalAlternativo').appendTo('#divPanelFacturaSideBarComandos');
				$('#labelTitleDetalle').remove();
				$('#mySidebarFactura').css('padding-top','0px');
				
				
				
				
			});
		</script>",
		
		
		/*Financiera Erick Corea*/		
		strtolower('corea_core_web_menu_SIMULADOR')			 					=> "CALCULADORA",
		strtolower('corea_core_web_menu_FACTURACION')			 				=> "PRESTAMOS",
		strtolower('corea_core_web_menu_FACTURAR')			 					=> "PRESTAR",
		strtolower('corea_core_web_menu_VENTAS')			 					=> "DESEMBOLSO",
		strtolower('corea_core_web_menu_DETALLE DE VENTAS')			 			=> "DETALLE DE DESEMBOLSOS",
		strtolower('corea_core_web_menu_RESUMEN DE VENTAS')			 			=> "RESUMEN DE DESEMBOLSOS",
		strtolower('corea_core_web_menu_COBRANZA')			 					=> "CONF. RUTAS",
		strtolower('corea_core_web_menu_RRHH')			 						=> "RRHH RUTAS",
		strtolower('corea_core_web_menu_COLABORADORES')			 				=> "COLAB. RUTA.",
		strtolower('corea_app_collection_manager_lblTitleList')			 		=> "CLIENTES POR RUTAS",
		strtolower('corea_app_collection_manager_lblTitleAdd')			 		=> "AGREGAR CLIENTE A LA RUTA",
		strtolower('corea_app_collection_manager_lblColaborador')			 	=> "Ruta",
		strtolower('corea_app_rrhh_employee_lblList')			 				=> "LISTA DE RUTAS Y/O COLABORADOR",
		strtolower('corea_app_rrhh_employee_lblNew')			 				=> "NUEVA RUTA Y/O COLABORADOR",
		strtolower('corea_app_rrhh_employee_lblEdit')			 				=> "EDITAR RUTA Y/O COLABORADOR",
		strtolower('corea_app_cxc_customer_divTxtCategoria')			 		=> "hidden",
		strtolower('corea_app_cxc_customer_divTxtFullNameCommercial')			=> "Categoria",
		strtolower('corea_app_cxc_customer_lblTxtPhoneTemp')					=> "Prefesion u Oficio",
		strtolower('corea_app_cxc_customer_divTxtProfesionUFicio')				=> "hidden",
		
		
		strtolower('corea_app_cxc_customer_showBtnIrBuro')			 			=> "",
		strtolower('corea_app_cxc_customer_showBtnIrSimulador')			 		=> "",
		strtolower('corea_app_cxc_customer_showBtnIrInvoice')			 		=> "",		
		strtolower('corea_app_cxc_customer_showBtnIrShare')			 			=> "",				
		strtolower('corea_app_cxc_customer_showBtnGroupAcciones')	 			=> "",				
		strtolower('corea_app_cxc_record_showBtnIrCustomerOfRecord') 			=> "",
		strtolower('corea_app_cxc_simulation_showBtnIrCustomerOfSimulator') 	=> "",
		strtolower('corea_app_box_share_showBtnIrCustomerOfShare') 				=> "",
		strtolower('corea_app_cxc_customer_divScriptValideFunction') 	 		=> "
		
		if( $('#txtIdentification').val()  == ''){
			fnShowNotification('Escribir cedula','error',timerNotification);
			result = false;
		}
		if( $('#txtIdentification').val()  == '0'){
			fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
		}
		
		const regex = /^\d{3}-\d{6}-\d{4}[A-Za-z]$/;
		if(!regex.test(   $('#txtIdentification').val()   )){
            fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
        } 
		
		
		",
		
		/*Gym Power House*/
		strtolower('gym_power_house_app_inventory_item_lblBanoServicio')			 										=> "Membresia",
		
		/*Cafe hotel Retorno*/		
		strtolower('cafe_hotel_retorno_core_web_language_workflowstage_billing_REGISTRADA')			 						=> "GUARDAR MESA",
		strtolower('cafe_hotel_retorno_core_web_language_workflowstage_billing_APLICADA')				 					=> "PAGAR",
		strtolower('cafe_hotel_retorno_core_web_language_workflowstage_billing_REGISTRAR')				 					=> "GUARDAR MESA",
		strtolower('cafe_hotel_retorno_core_web_language_workflowstage_billing_APLICAR')				 					=> "PAGAR",		
		strtolower('cafe_hotel_retorno_core_web_language_workflowstage_billing_ANULADA')				 					=> "ANULADA",		
		strtolower('cafe_hotel_retorno_app_invoice_billing_lablBotunConfiguracion')											=> "CONFIGURACION",
		strtolower('cafe_hotel_retorno_app_invoice_billing_lablBotunVerDetalle')											=> "PRODUCTO",		
		strtolower('cafe_hotel_retorno_app_invoice_billing_divTxtCambio') 													=> "hidden",				
		strtolower('cafe_hotel_retorno_app_invoice_billing_divPestanaCredito') 												=> "hidden",		
		strtolower('cafe_hotel_retorno_app_invoice_billing_divPestanaMas')	 												=> "hidden",
		strtolower('cafe_hotel_retorno_app_invoice_billing_divPestanaReferencias')											=> "hidden",
		strtolower('cafe_hotel_retorno_app_invoice_billing_divHiddenEmployer')												=> "hidden",		
		strtolower('cafe_hotel_retorno_app_invoice_billing_lblReferencia')													=> "Vendedor",	
		strtolower('cafe_hotel_retorno_app_invoice_billing_txtScanerBarCode')	 											=> "hidden",
		strtolower('cafe_hotel_retorno_app_invoice_billing_panelResumenFacturaTool')	 									=> "hidden",
		strtolower('cafe_hotel_retorno_app_invoice_billing_panelResumenFactura')	 										=> "hidden",		
		strtolower('cafe_hotel_retorno_app_invoice_billing_rowOptionPaymentExtras')	 										=> "hidden",
		strtolower('cafe_hotel_retorno_app_invoice_billing_panelLabelSumaryAlternativo')	 								=> "",
		strtolower('cafe_hotel_retorno_app_invoice_billing_divTxtCedulaBeneficiario')	 									=> "Dirección",
		strtolower('cafe_hotel_retorno_app_invoice_billing_divTxtMoneda')	 												=> "",
		
		
		strtolower('cafe_hotel_retorno_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divZone').appendTo('#divInformacionLeft');								
				$('#divMesa').appendTo('#divInformacionLeft');
				$('#divTrasuctionPhone').appendTo('#divInformacionRight');				
				$('#divTipoFactura').appendTo('#divInformacionLeft');
				$('#divReferencia').appendTo('#divInformacionRight');	
				
				//Mover opciones de pago
				$('#divPaymentOption').appendTo('#siderbar_content_right');	
				$('#divPaymentOption').removeClass('col-lg-5');
				$('#divPaymentOption').addClass('col-lg-6');
				
				//Mover panel donde se muestra los totales de la factura
				$('#divPanelShowSummaryNumber').appendTo('#siderbar_content_right');	
				$('#divPanelShowSummaryNumber').removeClass('col-lg-5');
				$('#divPanelShowSummaryNumber').addClass('col-lg-6');
				
				
				$('#heading').remove();
				$('<br/>').appendTo('#divPanelPaymentSideBar');	
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().appendTo('#divPanelPaymentSideBar');	
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().removeClass('col-lg-2');
				$('.btnAcept[data-valueworkflow=\"67\"]').parent().addClass('col-lg-12');
				$('#panelContainterDetailInvoice').appendTo('#siderbar_content_right_factura');	
				$('#btnBar').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnFooter').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnDeleteItem').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnNewItem').parent().appendTo('#divPanelFacturaSideBarComandos');
				$('#btnPrinter').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnNew').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnDelete').parent().appendTo('#rowBotoneraFacturaFila1');
				$('#btnOptionPago').parent().appendTo('#rowBotoneraFacturaFila4');
				$('#btnVeDetalleFactura').parent().appendTo('#rowBotoneraFacturaFila4');				
				$('#saltoDeLineaFila3').remove();
				$('#saltoDeLineaFila2').remove();
				$('#saltoDeLineaFila0').remove();
				$('#saltoDeLineaFila6').remove();
				$('#saltoDeLineaFila7').remove();
				$('#rowBotoneraFacturaFila1').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila2').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila3').appendTo('#panelComandoAlternativa2');				
				$('#rowBotoneraFacturaFila4').appendTo('#panelComandoAlternativa2');				
				$('#btnVeDetalleFactura').removeClass('btn-primary');
				$('#btnVeDetalleFactura').addClass('btn-success');				
				$('#btnGroupdProducto').removeClass('btn-success');
				$('#btnGroupdProducto').addClass('btn-primary');				
				$('#btnOptionPago').removeClass('btn-primary');
				$('#btnOptionPago').addClass('btn-warning');				
				$('#labelTotalAlternativo').appendTo('#divPanelFacturaSideBarComandos');
				$('#labelTitleDetalle').remove();
				$('#mySidebarFactura').css('padding-top','0px');
				
				
				
				
			});
		</script>",
		
		/*Compu MATT*/						
		strtolower('compu_matt_app_invoice_billing_lblRptInvoiceAddress') 											=> "Frente a la entrada principal de Iglesia la Merced",
		strtolower('compu_matt_app_invoice_billing_lblRptInvoiceRuc') 												=> "Edificio Principal RUC: J0310000066388",
		strtolower('compu_matt_app_invoice_billing_lblRptInvoiceOpcion2') 											=> "Compra Todo en COMPU MATT",
		strtolower('compu_matt_app_invoice_billing_lblRptInvoiceOpcion1') 											=> "",
		strtolower('compu_matt_app_invoice_billing_lblRptInvoicePhone') 											=> "Teléfono: 2311 0234.",		
		strtolower('compu_matt_app_purchase_taller_lblReportEntradaRptDireccion') 									=> "Frente a la entrada principal de Iglesia la Merced",
		strtolower('compu_matt_app_purchase_taller_lblReportEntradaRptPhone') 										=> "Servicio Tecnico: 2311 0234",		
		strtolower('compu_matt_app_purchase_taller_lblReportEntradaRptRed') 										=> "Siguenos! Compu MATT",
		strtolower('compu_matt_app_purchase_taller_lblReportEntradaRptCondicion1') 									=> "",
		strtolower('compu_matt_app_purchase_taller_lblReportEntradaRptCondicion2') 									=> "
			CLAUSULAS
			</br>
			<ul>
				<li>Favor presentar esta esquela para retirar su equipo.</li>
				<li>COMPU MATT No se responsabiliza por la pérdida de información. en medios extraibles tales como: disco duro (internos o externos), DVD, Pendriver, cita backup , al momento de realizar cualquier servicio.</li>
				<li>Nuestra GARANTIA no cubre fallas de software, La garantía por mano de obra es de 7 dias.</li>
				<li>El valor del diagnóstico es de C$ 450 y es acreditable al monto total del mantenimiento realializado.</li>
				<li>Para hacer valida su garantía, presentar SIEMPRE su factura Original de compra.</li>
				<li>Despues de 30 dias de notificado el diagnóstico. no nos hacemos responsables de su equipo.</li>
				<li>Los equipos deben estar siempre conectados a tomas corriente polarizados, con protectores de energia, supresores de picos y UPS de lo contrario perderea su garantía</li>
				<li>Para cualquier información, llamar al 2311-0234, Correo electronico: soporte@compumattsa.com. WhatsApp :  8249-3098</li>
			</ul>
		",
		strtolower('compu_matt_app_purchase_pedidos_scriptValidateInSave') 											=> "
			applyValidationNote = false;
		",
		
		/*posme*/
		strtolower('posme_core_web_menu_CONS. MED')			 												=> "VISITAS",
		strtolower('posme_app_med_query_labelTitlePageList')			 									=> "VISITAS",
		strtolower('posme_app_med_query_divPanelEdad')			 											=> "hidden",
		strtolower('posme_app_med_query_divPanelAltura')			 										=> "hidden",
		strtolower('posme_app_med_query_divPanelPeso')			 											=> "hidden",
		strtolower('posme_app_med_query_divPanelIMC')			 											=> "hidden",
		strtolower('posme_app_med_query_divPanelResultado')			 										=> "hidden",
		strtolower('posme_app_med_query_divPanelEvaluacion')			 									=> "hidden",
		strtolower('posme_app_med_query_divPanelRecomendacion')			 									=> "hidden",
		strtolower('posme_app_med_query_divPanelDiagnostico')			 									=> "hidden",
		
		
	);

	$colirio                = getBehavioColirio();
    $divs                   = array_merge($divs, $colirio);
	$tenampa                = getBehavioTenampa();
    $divs                   = array_merge($divs, $tenampa);
	$emanuel                = getBehavioEmanuel();
    $divs                   = array_merge($divs, $emanuel);
    $carlosLuis             = getBehavioCarlosLuis();
    $divs                   = array_merge($divs, $carlosLuis);
	$creditAguil            = getBehavioCreditAguil();
	$divs                   = array_merge($divs, $creditAguil);
    $autoLavadoMaximum      = getBehavioAutoLavadoMaximum();
    $divs                   = array_merge($divs, $autoLavadoMaximum);
	$emanuelPizza		    = getBehavioEmanuelPizza();
    $divs                   = array_merge($divs, $emanuelPizza);
	$pasteleriaChic   		= getBehavioChic();
    $divs                   = array_merge($divs, $pasteleriaChic);
	$globalPro		   		= getBehavioGlobalpro();
    $divs                   = array_merge($divs, $globalPro);
	$balladaresPasteleria	= getBehavioPasteleriaBalladares();
	$divs                   = array_merge($divs, $balladaresPasteleria);
	$balladaresTisey		= getBehavioAguaTisey();
	$divs                   = array_merge($divs, $balladaresTisey);
	$majo					= getBehavioMajo();
	$divs                   = array_merge($divs, $majo);
	$cristoRey				= getBehavioCristoRey();
	$divs                   = array_merge($divs, $cristoRey);
	$farmaLey				= getBehavioFarmaLey();
	$divs                   = array_merge($divs, $farmaLey);
	$blooMoon				= getBehavioBlooMoon();
	$divs                   = array_merge($divs, $blooMoon);

	//Comanda traducir es para los menu
	//comportamiento del controlador
	//si el key no existe regresa valor vacio
	if($key_controller == "core_web_language")
	{
		//lenguaje		
		$key = strtolower($type_company)."_".strtolower($key_controller)."_".strtolower($key_element);
		
		if(!array_key_exists( $key, $divs) )
		{
			//si el key no existe regrear el elemento
			return $key_element;
		}
		else 
		{
			//si el key existe , retornar valor
			return $divs[$key];
		}
	}
	else if ( $key_controller == "core_web_language_workflowstage" )
	{
		//lenguaje		
		$key = strtolower($type_company)."_".strtolower($key_controller)."_".strtolower($key_element);
		
		if(!array_key_exists( $key, $divs) )
		{
			//si el key no existe regrear el elemento
			return $default_value;
		}
		else 
		{
			//si el key existe , retornar valor
			return $divs[$key];
		}
	}
	//Menu
	//Si el key no existe regrea el mismo valor
	else if($key_controller == "core_web_menu")
	{
		//lenguaje		
		$key = strtolower($type_company)."_".strtolower($key_controller)."_".strtolower($key_element);
		if(!array_key_exists( $key, $divs) )
		{
			//si el key no existe regrear el elemento
			return $key_element;
		}
		else 
		{
			//si el key existe , retornar valor
			return $divs[$key];
		}
	}
	else 
	{		
		$key = strtolower($type_company)."_".strtolower($key_controller)."_".strtolower($key_element);
		if(!array_key_exists( $key, $divs) )
		{
			
			//si el key no existe, buscar el key para la empresa por defecto
			$key = strtolower("default")."_".strtolower($key_controller)."_".strtolower($key_element);
			if(!array_key_exists( $key, $divs) )
			{	
				return $default_value;
			}
			else 
			{
				return $divs[$key];
			}
			
		}
		else 
		{
			//si el key exite buscar el valor del key
			return $divs[$key];
		}
		
	}
		
}

?>