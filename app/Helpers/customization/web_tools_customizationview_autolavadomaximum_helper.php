<?php

function getBehavioAutoLavadoMaximum(): array
{
    //company type: maximum
    return array(
		strtolower('maximum_app_invoice_billing_divPestanaReferencias')         		=> "hidden",
		strtolower('maximum_app_invoice_billing_divPestanaCredito')             		=> "hidden",
		strtolower('maximum_app_invoice_billing_divPestanaMas')                 		=> "hidden",
		strtolower('maximum_app_invoice_billing_divTxtCambio')                  		=> "hidden",
		strtolower('maximum_app_invoice_billing_divTxtMoneda')                 	 		=> "hidden",
		strtolower('maximum_app_invoice_billing_divTxtCliente')                 		=> "hidden",
		strtolower('maximum_app_invoice_billing_divTxtCausalID')                 		=> "hidden",
		strtolower('maximum_app_invoice_billing_divBtnPrecios')                 		=> "hidden",
		strtolower('maximum_app_invoice_billing_panelResumenFactura')                 	=> "hidden",
		strtolower('maximum_app_invoice_billing_rowOptionPaymentExtras')              	=> "hidden",
		strtolower('maximum_app_invoice_billing_rowOptionPaymentExtrasTarjeta')       	=> "",
		strtolower('maximum_app_invoice_billing_divHiddeValue')                 		=> "hidden",
		strtolower('maximum_app_invoice_billing_divTxtTotal')                 			=> "01)",
		strtolower('maximum_app_invoice_billing_divTxtCausalIDScript')          		=> "",
		strtolower('maximum_app_invoice_billing_scriptValidateCustomer')          		=> '
		if($("#txtReferenceClientName").val() == ""){
			fnShowNotification("Establecer numero de cliente","error",timerNotification);
			result = false;
			fnWaitClose();
		}
		',
    );
}

?>
