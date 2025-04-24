<?php
function getBehavioFarmaLey(){
    $farmaLey = array(
	
		/*Farma Ley*/
		strtolower('farma_ley_app_inventory_item_chkPerecedero')		=> "checked",
		strtolower('farma_ley_app_inventory_item_divTxtCapacidad')		=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtSKUCompras')		=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtSKUProduccion')	=> "hidden",
		strtolower('farma_ley_app_inventory_item_divPanelMoneda')		=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtBodega')			=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtPresentacionUM')	=> "hidden",
		strtolower('farma_ley_app_inventory_item_selectedFamilyDefault')=> "false",
		strtolower('farma_ley_app_inventory_item_selectedUM')			=> "false",
		strtolower('farma_ley_app_inventory_item_selectedDisplayUM')	=> "false",
		strtolower('farma_ley_app_inventory_item_scriptValidate')		=> "
			
			if($('#txtFamilyID').val() == ''){
				fnShowNotification('Seleccione la familia','error',timerNotification);
				result = false;
			}
			
			if($('#txtDisplayID').val() == ''){
				fnShowNotification('Seleccione la presentación','error',timerNotification);
				result = false;
			}
			
			
		",
		
		
		
		
		
			
    );
    return $farmaLey;
}
