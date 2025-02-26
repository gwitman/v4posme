<?php
function getBehavioEmanuelPizza(): array
{
    return array(
		strtolower('pizzaemanuel_core_web_language_workflowstage_billing_REGISTRADA')			 						=> "GUARDAR MESA",
		strtolower('pizzaemanuel_core_web_language_workflowstage_billing_APLICADA')				 						=> "PAGAR",
		strtolower('pizzaemanuel_core_web_language_workflowstage_billing_REGISTRAR')				 					=> "GUARDAR MESA",
		strtolower('pizzaemanuel_core_web_language_workflowstage_billing_APLICAR')				 						=> "PAGAR",		
		strtolower('pizzaemanuel_core_web_language_workflowstage_billing_ANULADA')				 						=> "ANULADA",		
		strtolower('pizzaemanuel_app_invoice_billing_lablBotunConfiguracion')											=> "CONFIGURACION",
		strtolower('pizzaemanuel_app_invoice_billing_lablBotunVerDetalle')												=> "PRODUCTO",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtMoneda') 													=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCambio') 													=> "hidden",				
		strtolower('pizzaemanuel_app_invoice_billing_divPestanaCredito') 												=> "hidden",		
		strtolower('pizzaemanuel_app_invoice_billing_divPestanaMas')	 												=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divPestanaReferencias')											=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divHiddenEmployer')												=> "hidden",		
		strtolower('pizzaemanuel_app_invoice_billing_lblReferencia')													=> "Vendedor",	
		strtolower('pizzaemanuel_app_invoice_billing_txtScanerBarCode')	 												=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_panelResumenFacturaTool')	 										=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_panelResumenFactura')	 											=> "hidden",		
		strtolower('pizzaemanuel_app_invoice_billing_rowOptionPaymentExtras')	 										=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_panelLabelSumaryAlternativo')	 									=> "",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCedulaBeneficiario')	 										=> "Dirección",
		strtolower('pizzaemanuel_app_invoice_billing_divTraslateElement') 												=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divZone').appendTo('#divInformacionLeft');								
				$('#divMesa').appendTo('#divInformacionLeft');
				$('#divTrasuctionPhone').appendTo('#divInformacionRight');				
				$('#divTipoFactura').appendTo('#divInformacionLeft');
				$('#divReferencia').appendTo('#divInformacionRight');	
				$('#btnAceptarDialogCocinaV2').removeClass('hidden');
				
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
		
    );
}
