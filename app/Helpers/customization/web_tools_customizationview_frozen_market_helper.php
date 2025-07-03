<?php
function getBehavioFrozenMarket(){
    $frozenMarket = array(
		strtolower('frozen_market_app_invoice_billing_scriptValidateTotalesZero')			=> "validateTotalesZero = false;",
    );
    return $frozenMarket;
}
