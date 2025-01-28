<?php
function getBehavioEmanuel(): array
{
    return array(
		strtolower('emanuel_app_invoice_billing_divTxtCambio') 													=> "hidden",
		strtolower('emanuel_app_invoice_billing_divHiddeValue') 												=> "hidden",
		strtolower('emanuel_app_invoice_billing_rowOptionPaymentExtrasTarjeta') 								=> "hidden",
		strtolower('emanuel_app_invoice_billing_rowOptionPaymentExtras') 								        => "hidden",
		strtolower('emanuel_app_invoice_billing_panelResumenFactura') 								        	=> "hidden",
		strtolower('emanuel_app_invoice_billing_divPestanaCredito') 								            => "hidden",
		strtolower('emanuel_app_invoice_billing_divPestanaReferencias') 								        => "hidden",
		strtolower('emanuel_app_invoice_billing_divPestanaMas') 								                => "hidden",
		strtolower('emanuel_app_invoice_billing_divTxtFecha') 								                	=> "hidden",
		strtolower('emanuel_app_invoice_billing_divTxtMoneda') 								               		=> "hidden",
		strtolower('emanuel_app_invoice_billing_divTxtCliente') 							                    => "hidden",
		strtolower('emanuel_app_invoice_billing_divTxtCausalID') 							                    => "hidden",
		strtolower('emanuel_app_invoice_billing_txtScanerBarCode') 							                	=> "hidden",
		strtolower('emanuel_app_invoice_billing_btnNewItemCatalog') 							                => "hidden",
		strtolower('emanuel_app_invoice_billing_divTxtCausalIDScript') 							            	=> "",
		strtolower('emanuel_app_invoice_billing_divTxtZone') 							                        => "hidden",
		strtolower('emanuel_app_invoice_billing_divHiddenEmployer') 							                => "hidden",
		strtolower('emanuel_app_invoice_billing_divBtnPrecios') 							                    => "hidden",
		strtolower('emanuel_app_invoice_billing_divTxtMesa') 												    => "disabled",
		strtolower('emanuel_app_invoice_billing_divTxtTotal') 													=> "01)",
		strtolower('emanuel_app_invoice_billing_btnMenus') 												    	=> "disabled",
		strtolower('emanuel_app_invoice_billing_btnFooter') 												    => "$('#btnFooter').addClass('hidden');",
        strtolower('emanuel_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divMesa').appendTo('#divInformacionRight');
				$('#divZone').appendTo('#divInformacionLeft');		
				$('#btnAceptarDialogCocinaV2').removeClass('hidden');		
				$('#btnNewItemCatalog').addClass('hidden');		
				$('#btnLinkPayment').addClass('hidden');
				$('#btnFooter').addClass('hidden');
				
				
				

			});
		</script>",
    );
}
