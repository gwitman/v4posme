<?php
function getBehavioFarmaLey(){
    $farmaLey = array(
	
		/*Farma Ley*/
		strtolower('farma_ley_core_web_menu_O. SALIDAS')			 			=> "AJUSTE SALIDA",
		strtolower('farma_ley_core_web_menu_O. ENTRADAS')			 			=> "AJUSTE ENTRADA",
		strtolower('farma_ley_app_inventory_inputunpost_validarDateExpired')	=> "true",
		strtolower('farma_ley_app_inventory_item_chkPerecedero')				=> "checked",
		strtolower('farma_ley_app_inventory_item_divTxtCapacidad')				=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtSKUCompras')				=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtSKUProduccion')			=> "hidden",
		strtolower('farma_ley_app_inventory_item_divPanelMoneda')				=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtBodega')					=> "hidden",
		strtolower('farma_ley_app_inventory_item_divTxtPresentacionUM')			=> "hidden",
		strtolower('farma_ley_app_inventory_item_selectedFamilyDefault')		=> "false",
		strtolower('farma_ley_app_inventory_item_selectedUM')					=> "false",
		strtolower('farma_ley_app_inventory_item_Marca')						=> "Uso",
		strtolower('farma_ley_app_inventory_item_divTraslate')					=> "
		<script>
			$(document).ready(function(){
				$('#panelDivMarca').appendTo('#dropdown');
			});
		</script>
		",
		
		
		strtolower('farma_ley_app_inventory_item_selectedDisplayUM')			=> "false",		
		strtolower('farma_ley_app_invoice_billing_divTxtCausalID')				=> "hidden",		
		strtolower('farma_ley_app_invoice_billing_divTxtCausalIDScript')		=> "",		
		strtolower('farma_ley_app_invoice_billing_jsPostUpdateInvoiceView')		=> "			
			if(objTransactionMaster.statusID == '66' /*registrada*/)
			{
				$('#btnPrinter').addClass('hidden');
			}
			
		",
		strtolower('farma_ley_app_invoice_billing_divTraslateElement') 			=> "
		<script>
			$(document).ready(function(){						
				$('#divTxtElementoDisponibleParaMove2').removeClass('hidden');
				$('#divVendedor').appendTo('#divTxtElementoDisponibleParaMove2');
			});
		</script>",
		strtolower('farma_ley_app_invoice_billing_scriptValidateCustomer') 		=> "
				if($('#txtEmployeeID').val() == '614')
				{
					Toast.fire({
						icon: 	'warning',
						title: 	'Seleccionar vendedor'
					});
					result = false;
				}
		",
		strtolower('farma_ley_app_cxc_customer_divTxtNombres')					=> "hidden",		
		strtolower('farma_ley_app_cxc_customer_divTxtApellidos')				=> "hidden",		
		strtolower('farma_ley_app_cxc_customer_divTxtNombreComercial')			=> "hidden",		
		strtolower('farma_ley_app_cxc_customer_divRigthHome')					=> "hidden",		
		strtolower('farma_ley_app_cxc_customer_divScriptValideFunctionPre')		=> "
			
			var txtLegalName_ = $('#txtLegalName').val();
			$('#txtFirstName').val(txtLegalName_);
			$('#txtLastName').val(txtLegalName_);
			$('#txtCommercialName').val(txtLegalName_);
		",		
		
		strtolower('farma_ley_app_inventory_item_scriptValidate')				=> "
			
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
