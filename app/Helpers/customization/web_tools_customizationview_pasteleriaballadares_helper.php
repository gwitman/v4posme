<?php

function getBehavioPasteleriaBalladares(): array
{
    
    return array(
		strtolower('pasteleria_balladares_app_cxc_customer_divScriptReady')         		=> "$('#txtIdentification').val('".date("Y_m_d_h_i")."');",
		strtolower('pasteleria_balladares_app_invoice_billing_txtTraductionMesa')         	=> "Entregado",
		strtolower('pasteleria_balladares_app_invoice_billing_divLabelZone')         		=> "Hora",
		strtolower('pasteleria_balladares_app_invoice_billing_divPrecio')         			=> "hidden",
		strtolower('pasteleria_balladares_app_invoice_billing_divBodegaHidden')         	=> "hidden",
		strtolower('pasteleria_balladares_app_invoice_billing_divMesa')			         	=> "hidden",
		strtolower('pasteleria_balladares_app_cxc_customer_divTxtNombreLegal')				=> "hidden",
		strtolower('pasteleria_balladares_app_cxc_customer_divTxtNombreComercial')			=> "hidden",
		strtolower('pasteleria_balladares_app_invoice_billing_divHiddenEmployer')			=> "hidden",
		strtolower('pasteleria_balladares_app_invoice_billing_txtTraductionPhone')			=> "Facturador",
		strtolower('pasteleria_balladares_app_invoice_billing_lblReferencia')	         	=> "Entregado",		
		strtolower('pasteleria_balladares_app_invoice_billing_divTraslateElement')			=> "
			<script>
				$(document).ready(function(){
					if( $('#txtTransactionMasterID').val() == '' )
					{
						$('#txtReference3').val('NO'); 
					}
				}); 
			</script> 
		",
		strtolower('pasteleria_balladares_app_invoice_billing_jsClearForm')					=> "			
			$('#txtReference3').val('NO');
		",
		strtolower('pasteleria_balladares_app_cxc_customer_divScriptValideFunctionPre')		=> "
				$('#txtLegalName').val($('#txtFirstName').val());
				$('#txtCommercialName').val($('#txtLastName').val());
		",
		
    );
}

?>
