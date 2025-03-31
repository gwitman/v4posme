<?php
function getBehavioGlobalpro(){
    $globalpro = array(
	
		/*GlobalPro*/
		strtolower('globalpro_core_web_menu_O. SALIDAS')			 											=> "AJUSTE SALIDA",
		strtolower('globalpro_core_web_menu_O. ENTRADAS')			 											=> "AJUSTE ENTRADA",
		strtolower('globalpro_core_web_menu_CARTERA DE CREDITO')			 									=> "CLIENTES POR COBRAR",
		strtolower('globalpro_app_inventory_item_label_price_PUBLICO')			 								=> "PRECIO OFERTA",
		strtolower('globalpro_app_inventory_item_label_price_POR MAYOR')										=> "REGULAR",
		strtolower('globalpro_app_inventory_item_label_price_CREDITO')			 								=> "POR MAYOR",
		strtolower('globalpro_app_inventory_item_label_price_CREDITO POR MAYOR')								=> "LIQUIDACION",
		strtolower('globalpro_app_inventory_item_label_price_ESPECIAL')			 								=> "ESPECIAL",		
		strtolower('globalpro_app_cxp_expenses_Referencia 1')													=> "Descripcion",
		strtolower('globalpro_app_cxp_expenses_Referencia 2')													=> "Factura",
		strtolower('globalpro_app_cxp_expenses_Referencia 3')													=> "Proveedor",
		strtolower('globalpro_app_cxp_expenses_Referencia 4')													=> "RUC",		
		strtolower('globalpro_app_cxp_expenses_scriptReady')													=> "		
			$('#divPanelCurrency').appendTo('#panelGeneralRigth');
			$('#divPanelMonto').appendTo('#panelGeneralRigth');
			$('#divPanelIVA').appendTo('#panelGeneralRigth');			
			$('#divPanelTotal').appendTo('#panelGeneralRigth');
		",
		
		
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
		strtolower('globalpro_app_inventory_item_menuBodegaReferencias') 	 									=> "hidden",
		strtolower('globalpro_app_inventory_item_btnDropDownPrinter') 	 										=> "",
		
		
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
		strtolower('globalpro_app_inventory_transferoutput_parameterDefaultQuantity')							=> "0",
		strtolower('globalpro_app_purchase_pedidos_divDllEstado')												=> "hidden",
		strtolower('globalpro_app_purchase_pedidos_divScriptCustom')											=> "
			<script>
			$(document).ready(function(){ 		
				var nickname 	= $('#header > nav > a > span' ).text().replace('(', '');
				nickname 		= nickname.replace('(', '');
				nickname 		= nickname.replace(')', '');
				nickname 		= nickname.replace(':', '');
				nickname 		= nickname.replace(' ', '');
				nickname 		= nickname.replace('usuario', '');
				
				if( !(nickname  == 'gabriel' || nickname  == 'superadmin')  )
				{
					$('#txtTMInfoDetailReference2').parent().parent().addClass('hidden');
				}
				
			}); 
			</script>
		",
		strtolower('globalpro_app_cxc_customer_divScriptCustom') 												=> "
			<script>
			$(document).ready(function(){ 		
				$('#txtSexoID').val(''); 
				$('#txtSexoID').trigger('change'); 							 
				$(document).on('focusout','#txtLegalName',function(){ 									
					var varLegalName 	= $('#txtLegalName').val(); 
					$('#txtFirstName').val(  varLegalName  ); 
					$('#txtLastName').val(  varLegalName  ); 
					$('#txtCommercialName').val(  varLegalName  ); 	 
				}); 
			}); 
			</script> ",
		strtolower('globalpro_app_inventory_item_divTraslateElementTablePrecio') 								=> "<script>$(document).ready(function(){       $('#btnPrice').parent().remove(); $('#tblPrecios').appendTo('#divContainerRowPersonalization');  });</script>",
		strtolower('globalpro_app_invoice_billing_divTraslateElement') 											=> "<script>$(document).ready(function(){		$('#divVendedor').appendTo('#divInformacionLeft');$('#divBodega').appendTo('#divInformacionLeft');});</script>",		
		strtolower('globalpro_app_box_share_new_script_validate_reference1')									=> "/*Validar Atiende*/ if($('#txtReference1').val() == ''){fnShowNotification('Escriba quien le atiende','error',timerNotification);result = false;}",		
		strtolower('globalpro_app_inventory_inputunpost_new_script_validate_reference1')						=> "/*Validar Referecia 1*/ if($('#txtReference1').val() == ''){fnShowNotification('Escriba Referencia 1 es obligatoria','error',timerNotification);result = false;}",
		strtolower('globalpro_app_purchase_taller_divTxtApplied')												=> "hidden",
		strtolower('globalpro_app_purchase_taller_divTxtChange')												=> "hidden",
		strtolower('globalpro_app_purchase_taller_divTxtStatus')												=> "hidden",
		strtolower('globalpro_app_purchase_taller_divTxtCurrency')												=> "hidden",
		strtolower('globalpro_app_purchase_taller_attrSelectedSucursal')										=> "",
		strtolower('globalpro_app_purchase_taller_scriptValidateForm')											=> "	


		if( $('#txtAreaID').val() == 799 /*entregado*/ )
		{
			if($('#txtDetailAmount').val() == '0'){
				fnShowNotification('El monto no puede ser 0','error',timerNotification);
				result = false;
			}					

			if($('#txtNote').val() == ''){
				fnShowNotification('Seleccionar factura','error',timerNotification);
				result = false;
			}
		}

		
		if( $('#txtRouteID').val() == 794 /*otros*/ && $('#txtReferenceClientName').val() == ''  )
		{
			fnShowNotification('Describir descripción de (otros)','error',timerNotification);
			result = false;
		}
		
		
		if( $('#txtBranchID').val() == '' )
		{
			fnShowNotification('Seleccionar sucursal','error',timerNotification);
			result = false;
		}
		
		",
		strtolower('globalpro_app_purchase_garantia_divPanelAplicado') 												=> "hidden",
		strtolower('globalpro_app_purchase_garantia_divPanelCambio') 												=> "hidden",
		strtolower('globalpro_app_purchase_garantia_divPanelEstado') 												=> "hidden",
		strtolower('globalpro_app_purchase_garantia_divPanelMoneda') 												=> "hidden",
		strtolower('globalpro_app_purchase_garantia_divPanelMonto') 												=> "hidden",
		strtolower('globalpro_app_cxp_expenses_lblAplicado') 														=> "hidden",
		strtolower('globalpro_app_cxp_expenses_lblCambio') 															=> "hidden",		
		strtolower('globalpro_app_box_report_dailyTownUserDiv') 													=> "hidden",
		strtolower('globalpro_app_box_report_dailyTownConceptIngresosDiv') 											=> "hidden",
		strtolower('globalpro_app_box_report_dailyTownConceptEgresosDiv') 											=> "hidden",
		strtolower('globalpro_app_box_report_dailyTownCategoryItemDiv') 											=> "hidden",
		strtolower('globalpro_app_box_report_dailyTownNoneConceptDiv') 												=> "hidden",
		strtolower('globalpro_app_box_report_dailyTownShowAmountDiv') 												=> "hidden",
		strtolower('globalpro_app_invoice_billing_scriptValidateTotalesZero')										=> "validateTotalesZero = false;",
		
		
			
    );
    return $globalpro;
}
