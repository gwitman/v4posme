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
		strtolower('farma_ley_app_inventory_item_label_price_PUBLICO')			=> "General",
		strtolower('farma_ley_app_inventory_item_label_price_POR MAYOR')		=> "Minsa",
		strtolower('farma_ley_app_inventory_item_divTraslate')					=> "
		<script>
			$(document).ready(function(){
				$('#panelDivMarca').appendTo('#dropdown');
				
				/*ocultar campo si el usurio es facturadora*/
				
				if( $('#txtRolNameSession').val() == 'Despachador' )
				{
					//Ocultar Costo
					$('#txtCost').parent().parent().addClass('hidden');
					//Ocutlar precio 4
					$('input.txtDetailTypePriceID[value=\'478\']').parent().parent().addClass('hidden');
					//Ocutlar precio 3
					$('input.txtDetailTypePriceID[value=\'477\']').parent().parent().addClass('hidden');
					//Ocutlar precio 1
					$('input.txtDetailTypePriceID[value=\'156\']').parent().parent().addClass('hidden');
					//Ocutlar precio 1
					$('input.txtDetailTypePriceID[value=\'154\']').parent().parent().addClass('hidden');
					
					
					//Ocultar la comision
					$('#tblPrecios thead th:nth-child(3)').hide();
					$('#tblPrecios tbody td:nth-child(3)').hide();
					
					//Acttivar el only
					$('#txtName').prop('readonly', true);
					
				}
				
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
