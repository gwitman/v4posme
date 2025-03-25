<?php

function getBehavioPasteleriaBalladares(): array
{
    //company type: maximum
    return array(
		strtolower('pasteleria_balladares_app_cxc_customer_divScriptReady')         		=> "$('#txtIdentification').val('".date("Y_m_d_h_i")."');"
    );
}

?>
