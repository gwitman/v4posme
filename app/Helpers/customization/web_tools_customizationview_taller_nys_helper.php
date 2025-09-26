<?php
function getBehavioTallerNYS(){
    $chic = array(
		strtolower('tallerNys_app_invoice_billing_divOpcionViewA4') 	 		=> "", 		
		strtolower('tallerNys_app_invoice_billing_brDivOpcionTabla') 	 		=> "display:none", 
		strtolower('tallerNys_app_invoice_billing_brDivOpcionAceptar') 	 		=> "display:none", 
		strtolower('tallerNys_app_invoice_billing_brDivOpcionPreviewA4') 	 	=> "display:none", 
		strtolower('tallerNys_app_invoice_billing_divOpcionViewA4White') 	 	=> "", 
		strtolower('tallerNys_app_invoice_billing_brDivOpcionViewA4White') 	 	=> "", 
		strtolower('tallerNys_app_invoice_billing_labelPrinterPreview') 	 	=> "80MM", 
		strtolower('tallerNys_app_invoice_billing_labelPrinterPreviewA4') 	 	=> "A4 Sin plantilla", 
		strtolower('tallerNys_app_invoice_billing_labelPrinterPreviewA4White') 	=> "A4 Con plantilla", 
		
    );
    return $chic;
}
