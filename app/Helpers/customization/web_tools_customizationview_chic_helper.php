<?php
function getBehavioChic(){
    $chic = array(
		strtolower('chicextensiones_app_inventory_item_label_price_PUBLICO')			 	=> "INFLADO",
		strtolower('chicextensiones_app_inventory_item_label_price_POR MAYOR')			 	=> "STANDAR",
		strtolower('chicextensiones_app_inventory_item_label_price_CREDITO')			 	=> "ESPECIAL",
		strtolower('chicextensiones_app_inventory_item_label_price_CREDITO POR MAYOR')		=> "",
		strtolower('chicextensiones_app_inventory_item_label_price_ESPECIAL')			 	=> "",						
		strtolower('chicextensiones_app_invoice_billing_lablBotunCocina')			 		=> "COMANDA",			
		strtolower('chicextensiones_app_invoice_billing_divTxtCedula2') 	 	=> "hidden", 
		strtolower('chicextensiones_app_invoice_billing_divMesa') 	 			=> "hidden", 		
        strtolower('chicextensiones_app_invoice_billing_divTraslateElement')    => "
			<script>
				$(document).ready(function(){ 
					/*quitar el atributo de oculto*/  
					$('#divTxtElementoDisponibleParaMover1').removeClass('hidden'); 
					/*pasar divZone pasar a divTxtElementoDisponibleParaMover1*/ 
					$('#divZone').appendTo('#divTxtElementoDisponibleParaMover1');    
				}); 
			</script> 
		",         

		
			
    );
    return $chic;
}
