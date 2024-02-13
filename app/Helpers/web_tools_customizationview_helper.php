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
		strtolower('default_app_box_share_btnVerMovimientos')													=> "",
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
	
	
		
		/*GlobalPro*/
		strtolower('globalpro_core_web_menu_O. SALIDAS')			 											=> "AJUSTE SALIDA",
		strtolower('globalpro_core_web_menu_O. ENTRADAS')			 											=> "AJUSTE ENTRADA",
		strtolower('globalpro_app_inventory_item_label_price_PUBLICO')			 								=> "PRECIO OFERTA",
		strtolower('globalpro_app_inventory_item_label_price_POR MAYOR')										=> "REGULAR",
		strtolower('globalpro_app_inventory_item_label_price_CREDITO')			 								=> "POR MAYOR",
		strtolower('globalpro_app_inventory_item_label_price_CREDITO POR MAYOR')								=> "LIQUIDACION",
		strtolower('globalpro_app_inventory_item_label_price_ESPECIAL')			 								=> "ESPECIAL",		
		strtolower('globalpro_app_cxp_expenses_Referencia 1')													=> "Descripcion",
		strtolower('globalpro_app_cxp_expenses_Referencia 2')													=> "Factura",
		strtolower('globalpro_app_cxp_expenses_Referencia 3')													=> "Proveedor",
		strtolower('globalpro_app_inventory_item_Conceptos')			 										=> "IVA",
		strtolower('globalpro_default_masterpage_backgroundImage')			 									=> "style='background-image: url(".  base_url()."/resource/img/logos/fondo_globalpro.jpg"   .");'",		
		strtolower('globalpro_core_dashboards_divPanelCuadroMembresia')											=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelBiblico')													=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelSoporteTenico')											=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelFormaPago')												=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelInfoPago')												=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelUsuario')													=> "hidden",
									
									
		strtolower('globalpro_app_invoice_billing_panelResumenFacturaTool')										=> "",
		strtolower('globalpro_app_invoice_billing_panelResumenFactura')											=> "hidden",
		strtolower('globalpro_app_invoice_billing_divTxtCambio') 												=> "hidden",
		strtolower('globalpro_app_invoice_billing_divTxtMoneda') 			 									=> "hidden",		
		strtolower('globalpro_app_invoice_billing_divTxtCliente2') 			 									=> "hidden",
		strtolower('globalpro_app_invoice_billing_divTxtCedula2') 												=> "hidden",
									
									
									
		strtolower('globalpro_app_inventory_item_divTxtBarCode') 												=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtPerecedero') 			 								=> "hidden",		
		strtolower('globalpro_app_inventory_item_divTxtCapacidad') 			 									=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtCantidadMinima') 										=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtCantidadMaxima') 										=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtSKUCompras') 			 								=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtSKUProduccion') 											=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtEstado') 			 									=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtFamilia') 			 									=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtUM') 			 										=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtPresentacion') 			 								=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtPresentacionUM') 										=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtUM') 			 										=> "hidden",	
		
		
		strtolower('globalpro_app_cxc_customer_divTxtNombres') 													=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtApellidos') 			 									=> "hidden",		
		strtolower('globalpro_app_cxc_customer_divTxtNombreComercial') 			 								=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtEstado') 													=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtClasificacion') 											=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtTipo') 			 										=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtCategoria') 												=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtSubCategoria') 			 								=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtEstadoCivil') 			 									=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtProfesionUFicio') 			 								=> "hidden",
		strtolower('globalpro_app_box_share_labelReference1')			 										=> "Atiende",		
		strtolower('globalpro_app_inventory_transferoutput_parameterValidarEnvioDestino')						=> "true",
		strtolower('globalpro_app_inventory_transferoutput_labelReference1')									=> "Orden / Cliente",
		strtolower('globalpro_app_purchase_pedidos_divDllEstado')												=> "hidden",
		strtolower('globalpro_app_cxc_customer_divScriptCustom') 												=> "<script>$(document).ready(function(){ 		$('#txtSexoID').val(''); $('#txtSexoID').trigger('change'); 							 $(document).on('focusout','#txtLegalName',function(){ 									 var varLegalName 	= $('#txtLegalName').val(); $('#txtFirstName').val(  varLegalName  ); $('#txtLastName').val(  varLegalName  ); $('#txtCommercialName').val(  varLegalName  ); 	 }); }); </script> ",
		strtolower('globalpro_app_inventory_item_divTraslateElementTablePrecio') 								=> "<script>$(document).ready(function(){       $('#btnPrice').parent().remove(); $('#tblPrecios').appendTo('#divContainerRowPersonalization');  });</script>",
		strtolower('globalpro_app_invoice_billing_divTraslateElement') 											=> "<script>$(document).ready(function(){		$('#divVendedor').appendTo('#divInformacionLeft');$('#divBodega').appendTo('#divInformacionLeft');});</script>",		
		strtolower('globalpro_app_box_share_new_script_validate_reference1')									=> "/*Validar Atiende*/ if($('#txtReference1').val() == ''){fnShowNotification('Escriba quien le atiende','error',timerNotification);result = false;}",		
		strtolower('globalpro_app_inventory_inputunpost_new_script_validate_reference1')						=> "/*Validar Referecia 1*/ if($('#txtReference1').val() == ''){fnShowNotification('Escriba Referencia 1 es obligatoria','error',timerNotification);result = false;}",
					
					
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
		strtolower('clinicalarreynaga_app_cxc_customer_divScriptCustom') 										=> "<script> $(document).ready(function(){ $(document).on('focusout','#txtLegalName',function(){ var varLegalName 	= $('#txtLegalName').val(); $('#txtFirstName').val(  varLegalName  ); $('#txtLastName').val(  varLegalName  ); $('#txtCommercialName').val(  varLegalName  ); $('#txtIdentification').val(  'ND'  ); });});</script>",
					
					
					
		/*Chec extensiones*/			
		strtolower('chicextensiones_app_invoice_billing_divTxtCedula2') 										=> "hidden", 		
		strtolower('chicextensiones_app_invoice_billing_divTraslateElement') 									=> "<script>$(document).ready(function(){ /*quitar el atributo de oculto*/  $('#divTxtElementoDisponibleParaMover1').removeClass('hidden'); /*pasar divZone pasar a divTxtElementoDisponibleParaMover1*/ $('#divZone').appendTo('#divTxtElementoDisponibleParaMover1');	}); </script> ", 		
					
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
		strtolower('khadash_app_box_share_divResumenAbono')			 											=> "hidden", 
		strtolower('khadash_app_box_share_divStart')			 												=> "hidden", 
		strtolower('khadash_app_box_share_divFecha')			 												=> "hidden", 
		strtolower('khadash_app_box_share_divAplicado')			 												=> "hidden", 
		strtolower('khadash_app_box_share_divCambio')			 												=> "hidden", 
		strtolower('khadash_app_box_share_comboStyle')			 												=> "", 
		strtolower('khadash_app_box_share_javscriptVariable_varShareMountDefaultOfAmortization')				=> "false", 
		strtolower('khadash_app_box_share_TableColumnDocumento')												=> "hidden", 
		strtolower('khadash_app_box_share_btnVerMovimientos')													=> "hidden", 
		strtolower('khadash_app_box_share_javscriptVariable_varPrinterOnlyFormat')								=> "true", 
		strtolower('khadash_app_box_share_divCustomerControlBuscar')											=> "", 
		strtolower('khadash_app_box_share_divCustomerControlSelected')											=> "hidden", 
		strtolower('khadash_app_box_share_divCobrador')															=> "hidden", 
		strtolower('khadash_app_box_share_divMoneda')															=> "hidden", 
				
				
		/*Santa lucia ral state*/	
		strtolower('luciaralstate_core_web_menu_PRODUCTO')			 											=> "INMOBILIARIO",		
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
		strtolower('luciaralstate_app_cxc_customer_divScriptCustom') 											=> "
																														<script>
																														$(document).ready(function(){ 
																															$('#txtIdentification').val('0');				
																															$(document).on('focusout','#txtLegalName',function(){ 									 
																																var varLegalName 	= $('#txtLegalName').val(); 
																																$('#txtFirstName').val(varLegalName  ); 
																																$('#txtLastName').val(varLegalName  ); 
																																$('#txtCommercialName').val(varLegalName); 	 
																															}); 
																														}); 
																														</script> 
																													",
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
		
	);
	
	
	//Comanda traducir es para los menu
	//comportamiento del controlador
	//si el key no existe regresa valor vacio
	if($key_controller != "core_web_menu")
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
	//Menu
	//Si el key no existe regrea el mismo valor
	else 
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
		
}

?>