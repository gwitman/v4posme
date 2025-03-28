<?php
function getBehavioChic(){
    $chic = array(
		strtolower('chicextensiones_app_invoice_billing_divTxtCedula2') 	 	=> "hidden", 
		strtolower('chicextensiones_app_invoice_billing_divMesa') 	 			=> "hidden", 
		strtolower('chicextensiones_app_invoice_billing_divLabelZone') 	 		=> "Hora", 
		strtolower('chicextensiones_app_invoice_billing_divTraslateElement') 	=> "
			<script>
				$(document).ready(function(){ 
					/*quitar el atributo de oculto*/  
					$('#divTxtElementoDisponibleParaMover1').removeClass('hidden'); 
				}); 
			</script> ", 		
			
    );
    return $chic;
}
