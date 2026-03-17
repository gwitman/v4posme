<?php
function getBehavioFarmaByB(){
    $farmaByB = array(
	
		/*Farma ByB*/
		strtolower('farmaciaByB_core_web_menu_O. SALIDAS')			 											=> "AJUSTE SALIDA",
		strtolower('farmaciaByB_core_web_menu_O. ENTRADAS')			 											=> "AJUSTE ENTRADA",
		strtolower('farmaciaByB_core_web_menu_CARTERA DE CREDITO')			 									=> "CLIENTES POR COBRAR",
		strtolower('farmaciaByB_app_inventory_item_label_price_PUBLICO')			 								=> "PRECIO OFERTA",
		strtolower('farmaciaByB_app_inventory_item_label_price_POR MAYOR')										=> "REGULAR",
		strtolower('farmaciaByB_app_inventory_item_label_price_CREDITO')			 								=> "POR MAYOR",
		strtolower('farmaciaByB_app_inventory_item_label_price_CREDITO POR MAYOR')								=> "LIQUIDACION",
		strtolower('farmaciaByB_app_inventory_item_label_price_ESPECIAL')			 								=> "ESPECIAL",		
		strtolower('farmaciaByB_app_cxp_expenses_Referencia 1')													=> "Descripcion",
		strtolower('farmaciaByB_app_cxp_expenses_Referencia 2')													=> "Factura",
		strtolower('farmaciaByB_app_cxp_expenses_Referencia 3')													=> "Proveedor",
		strtolower('farmaciaByB_app_cxp_expenses_Referencia 4')													=> "RUC",		
		strtolower('farmaciaByB_app_cxp_expenses_scriptReady')													=> "		
			$('#divPanelCurrency').appendTo('#panelGeneralRigth');
			$('#divPanelMonto').appendTo('#panelGeneralRigth');
			$('#divPanelIVA').appendTo('#panelGeneralRigth');			
			$('#divPanelTotal').appendTo('#panelGeneralRigth');
		",
		
		
		strtolower('farmaciaByB_app_inventory_item_Conceptos')			 										=> "IVA",
		strtolower('farmaciaByB_default_masterpage_backgroundImage')			 									=> "style='background-image: url(".  base_url()."/resource/img/logos/fondo_farmaciaByB.jpg"   .");'",		
		strtolower('farmaciaByB_core_dashboards_divPanelCuadroMembresia')											=> "hidden",
		strtolower('farmaciaByB_core_dashboards_divPanelBiblico')													=> "hidden",
		strtolower('farmaciaByB_core_dashboards_divPanelSoporteTenico')											=> "hidden",
		strtolower('farmaciaByB_core_dashboards_divPanelFormaPago')												=> "hidden",
		strtolower('farmaciaByB_core_dashboards_divPanelInfoPago')												=> "hidden",
		strtolower('farmaciaByB_core_dashboards_divPanelUsuario')													=> "hidden",
									
									
		strtolower('farmaciaByB_app_invoice_billing_panelResumenFacturaTool')										=> "",
		strtolower('farmaciaByB_app_invoice_billing_panelResumenFactura')											=> "hidden",
		strtolower('farmaciaByB_app_invoice_billing_divTxtCambio') 												=> "hidden",
		strtolower('farmaciaByB_app_invoice_billing_divTxtMoneda') 			 									=> "hidden",		
		strtolower('farmaciaByB_app_invoice_billing_divTxtCliente2') 			 									=> "hidden",
		strtolower('farmaciaByB_app_invoice_billing_divTxtCedula2') 												=> "hidden",
									
									
									
		strtolower('farmaciaByB_app_inventory_item_divTxtBarCode') 												=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtPerecedero') 			 								=> "",		
		strtolower('farmaciaByB_app_inventory_item_divTxtCapacidad') 			 									=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtCantidadMinima') 										=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtCantidadMaxima') 										=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtSKUCompras') 			 								=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtSKUProduccion') 											=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtEstado') 			 									=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtFamilia') 			 									=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtUM') 			 										=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtPresentacion') 			 								=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtPresentacionUM') 										=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_divTxtUM') 			 										=> "hidden",	
		strtolower('farmaciaByB_app_inventory_item_menuBodegaReferencias') 	 									=> "hidden",
		strtolower('farmaciaByB_app_inventory_item_btnDropDownPrinter') 	 										=> "",
		
		
		strtolower('farmaciaByB_app_cxc_customer_divTxtNombres') 													=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtApellidos') 			 									=> "hidden",		
		strtolower('farmaciaByB_app_cxc_customer_divTxtNombreComercial') 			 								=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtEstado') 													=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtClasificacion') 											=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtTipo') 			 										=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtCategoria') 												=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtSubCategoria') 			 								=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtEstadoCivil') 			 									=> "hidden",
		strtolower('farmaciaByB_app_cxc_customer_divTxtProfesionUFicio') 			 								=> "hidden",
		strtolower('farmaciaByB_app_box_share_labelReference1')			 										=> "Atiende",		
		strtolower('farmaciaByB_app_inventory_transferoutput_parameterValidarEnvioDestino')						=> "true",
		strtolower('farmaciaByB_app_inventory_transferoutput_labelReference1')									=> "Orden / Cliente",
		strtolower('farmaciaByB_app_inventory_transferoutput_parameterDefaultQuantity')							=> "0",
		strtolower('farmaciaByB_app_purchase_pedidos_divDllEstado')												=> "hidden",
		strtolower('farmaciaByB_app_purchase_pedidos_divScriptCustom')											=> "
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
		strtolower('farmaciaByB_app_cxc_customer_divScriptCustom') 												=> "
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
		strtolower('farmaciaByB_app_inventory_item_divTraslateElementTablePrecio') 								=> "<script>$(document).ready(function(){       $('#btnPrice').parent().remove(); $('#tblPrecios').appendTo('#divContainerRowPersonalization');  });</script>",
		strtolower('farmaciaByB_app_invoice_billing_divTraslateElement') 											=> "<script>$(document).ready(function(){		$('#divVendedor').appendTo('#divInformacionLeft');$('#divBodega').appendTo('#divInformacionLeft');});</script>",		
		strtolower('farmaciaByB_app_box_share_new_script_validate_reference1')									=> "/*Validar Atiende*/ if($('#txtReference1').val() == ''){fnShowNotification('Escriba quien le atiende','error',timerNotification);result = false;}",		
		strtolower('farmaciaByB_app_inventory_inputunpost_new_script_validate_reference1')						=> "/*Validar Referecia 1*/ if($('#txtReference1').val() == ''){fnShowNotification('Escriba Referencia 1 es obligatoria','error',timerNotification);result = false;}",
		strtolower('farmaciaByB_app_purchase_taller_divTxtApplied')												=> "hidden",
		strtolower('farmaciaByB_app_purchase_taller_divTxtChange')												=> "hidden",
		strtolower('farmaciaByB_app_purchase_taller_divTxtStatus')												=> "hidden",
		strtolower('farmaciaByB_app_purchase_taller_divTxtCurrency')												=> "hidden",
		strtolower('farmaciaByB_app_purchase_taller_attrSelectedSucursal')										=> "",
		strtolower('farmaciaByB_app_purchase_taller_scriptValidateForm')											=> "	


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
		strtolower('farmaciaByB_app_purchase_garantia_divPanelAplicado') 												=> "hidden",
		strtolower('farmaciaByB_app_purchase_garantia_divPanelCambio') 												=> "hidden",
		strtolower('farmaciaByB_app_purchase_garantia_divPanelEstado') 												=> "hidden",
		strtolower('farmaciaByB_app_purchase_garantia_divPanelMoneda') 												=> "hidden",
		strtolower('farmaciaByB_app_purchase_garantia_divPanelMonto') 												=> "hidden",
		strtolower('farmaciaByB_app_cxp_expenses_lblAplicado') 														=> "hidden",
		strtolower('farmaciaByB_app_cxp_expenses_lblCambio') 															=> "hidden",		
		strtolower('farmaciaByB_app_box_report_dailyTownUserDiv') 													=> "hidden",
		strtolower('farmaciaByB_app_box_report_dailyTownConceptIngresosDiv') 											=> "hidden",
		strtolower('farmaciaByB_app_box_report_dailyTownConceptEgresosDiv') 											=> "hidden",
		strtolower('farmaciaByB_app_box_report_dailyTownCategoryItemDiv') 											=> "hidden",
		strtolower('farmaciaByB_app_box_report_dailyTownNoneConceptDiv') 												=> "hidden",
		strtolower('farmaciaByB_app_box_report_dailyTownShowAmountDiv') 												=> "hidden",
		strtolower('farmaciaByB_app_invoice_billing_scriptValidateTotalesZero')										=> "validateTotalesZero = false;",
		
		
			
    );
    return $farmaByB;
}
