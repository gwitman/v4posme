<?php
function getBehavioTenampa(){
    $tenampa = array(
		strtolower('tenampa_app_invoice_billing_divTxtCambio') 													=> "hidden",	
        strtolower('tenampa_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divMesa').appendTo('#divInformacionRight');
				$('#divZone').appendTo('#divInformacionLeft');		
				$('#btnAceptarDialogCocinaV2').removeClass('hidden');		


			});
		</script>",	
    );
    return $tenampa;
}
