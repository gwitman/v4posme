<?php

function getBehavioPasteleriaBalladares(): array
{
    //company type: maximum
    return array(
		strtolower('pasteleria_balladares_app_cxc_customer_divScriptReady')         		=> "$('#txtIdentification').val('".date("Y_m_d_h_i")."');",
		strtolower('pasteleria_balladares_app_invoice_billing_txtTraductionMesa')         	=> "Entregado",
		strtolower('pasteleria_balladares_app_invoice_billing_divLabelZone')         		=> "Hora",
		strtolower('pasteleria_balladares_app_invoice_billing_divPrecio')         			=> "hidden",
		strtolower('pasteleria_balladares_app_invoice_billing_divBodegaHidden')         	=> "hidden",
		strtolower('pasteleria_balladares_app_invoice_billing_divMesa')			         	=> "hidden",
    );
}

?>
