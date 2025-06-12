<?php
function getBehavioBlooMoon(){
    $bloomoon = array(
		strtolower('bluemoon_core_web_language_workflowstage_billing_REGISTRADA')			 					=> "GUARDAR MESA",
		strtolower('bluemoon_core_web_language_workflowstage_billing_APLICADA')				 					=> "PAGAR",
		strtolower('bluemoon_core_web_language_workflowstage_billing_REGISTRAR')				 				=> "GUARDAR MESA",
		strtolower('bluemoon_core_web_language_workflowstage_billing_APLICAR')				 					=> "PAGAR",		
		strtolower('bluemoon_core_web_language_workflowstage_billing_ANULADA')				 					=> "ANULADA",		
		strtolower('bluemoon_app_invoice_billing_lablBotunConfiguracion')										=> "CONFIGURACION",
		strtolower('bluemoon_app_invoice_billing_lablBotunVerDetalle')											=> "PRODUCTO",
		strtolower('bluemoon_app_invoice_billing_divTxtMoneda') 												=> "hidden",
		strtolower('bluemoon_app_invoice_billing_divTxtCambio') 												=> "hidden",				
		strtolower('bluemoon_app_invoice_billing_divPestanaCredito') 											=> "hidden",		
		strtolower('bluemoon_app_invoice_billing_divPestanaMas')	 											=> "hidden",
		strtolower('bluemoon_app_invoice_billing_divPestanaReferencias')										=> "hidden",
		strtolower('bluemoon_app_invoice_billing_divHiddenEmployer')											=> "hidden",		
		strtolower('bluemoon_app_invoice_billing_lblReferencia')												=> "Vendedor",	
		strtolower('bluemoon_app_invoice_billing_txtScanerBarCode')	 											=> "hidden",
		strtolower('bluemoon_app_invoice_billing_panelResumenFacturaTool')	 									=> "hidden",
		strtolower('bluemoon_app_invoice_billing_panelResumenFactura')	 										=> "hidden",		
		strtolower('bluemoon_app_invoice_billing_rowOptionPaymentExtras')	 									=> "hidden",
		strtolower('bluemoon_app_invoice_billing_panelLabelSumaryAlternativo')	 								=> "",
		strtolower('bluemoon_app_invoice_billing_divTxtCedulaBeneficiario')	 									=> "Dirección",
		strtolower('bluemoon_app_invoice_billing_divApplyExoneracion')	 										=> "",
		strtolower('bluemoon_app_invoice_billing_divTraslateElement') 											=> "
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
				debugger;
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
				
				//Pasar el boton de guardar mensa en nuevo a procesar pago
				if(loadEdicion == false)
				{
					$('#registrarFacturaNueva').children().appendTo($('#btnRollbackPayment').parent());
				}
				else 
				{
					//Pasar el boton pagar en modo edcion, al panel de proceas pago
					$('#workflowLink').find('[data-valueworkflow=\"67\"]').appendTo($('#btnRollbackPayment').parent());
					
					//Una ves guardado eliminar el boton guardar mensa del panel princial
					$('#workflowLink').find('[data-valueworkflow=\"66\"]').remove();
				}
				
			});
		</script>",
		strtolower('bluemoon_app_invoice_billing_jsPostUpdateInvoiceView') 									=> "
		if(loadEdicion == false)
		{
			$('#registrarFacturaNueva').children().appendTo($('#btnRollbackPayment').parent());
			$('#btnSaveInvoice').attr('data-valueworkflow', '66');
		}
		else 
		{
			//Pasar el boton pagar en modo edcion, al panel de proceas pago
			$('#btnRollbackPayment').parent().find('[data-valueworkflow=\"67\"]').remove();
			$('#workflowLink').find('[data-valueworkflow=\"67\"]').appendTo($('#btnRollbackPayment').parent());
			
			//Una ves guardado eliminar el boton guardar mensa del panel princial
			$('#workflowLink').find('[data-valueworkflow=\"66\"]').remove();
			
			//Eliminar  es espacio vacio
			$('#workflowLink').empty();
			
			
			//Agregar el atributo workflow en el boton SaveInvoice
			$('#btnSaveInvoice').attr('data-valueworkflow', '66');
		}
		"	
		
		
    );
	
	
    return $bloomoon;
}
