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
		strtolower('farma_ley_app_inventory_item_divTxtBodega')					=> "",
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
		
		
		strtolower('farma_ley_app_inventory_item_selectedDisplayUM')									=> "false",		
		strtolower('farma_ley_app_invoice_billing_divTxtCausalID')										=> "",		
		strtolower('farma_ley_app_invoice_billing_divTxtCausalIDScript')								=> "hidden",		
		strtolower('farma_ley_app_invoice_billing_txtTraductionMesa')									=> "Dr.",	
		strtolower('farma_ley_app_invoice_billing_despuesDeRecalcularTotalRecibidoIgualCero')			=> "true",	
		
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
				$('#divMesa').appendTo('#divTxtElementoDisponibleParaMove2');
				
				//Seleccionar Dr
				$('#txtMesaID').on('change', function(){
					let value 	= $('#txtMesaID').find(':selected').data('ratio');
					value 		= fnFormatNumber(value);
					$('#txtPorcentajeDescuento').val(value);
				});
				
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
				
				
				//Validar que haya al menos una forma de pago
				var _recpeitAmout = $('#txtReceiptAmount').val();
				if (_recpeitAmout == '')
					_recpeitAmout = 0 ;
				_recpeitAmout	  = parseFloat(_recpeitAmout);
				
				
				var _receptPoint  = $('#txtReceiptAmountPoint').val();
				if (_receptPoint == '')
					_receptPoint = 0 ;
				_receptPoint	  = parseFloat(_receptPoint);
					
				
				if( 
					_recpeitAmout == 0 && 
					_receptPoint == 0 && 
					invoiceTypeCredit == false 
				)
				{
					result = false;
					Toast.fire({
						icon: 'warning',
						title: 'Escribir la forma de pago'
					});
				}
				
		",
		strtolower('farma_ley_app_cxc_customer_divTxtNombres')					=> "hidden",		
		strtolower('farma_ley_app_cxc_customer_divTxtApellidos')				=> "hidden",		
		strtolower('farma_ley_app_cxc_customer_divTxtNombreComercial')			=> "hidden",		
		strtolower('farma_ley_app_cxc_customer_divRigthHome')					=> "hidden",	
		strtolower('farma_ley_app_cxc_customer_divScriptCustom') 				=> "
			<script>
			$(document).ready(function(){ 

				//Pasar la direccion al panel principal panel 1 segunda columna
				var panel1_column2_new =  $('<div class=\"col-lg-6\" id=\"panel1_column2_new\" ></div>');                    
				$('#home').children().first().append(panel1_column2_new);   
				$('#txtAddress').parent().parent().appendTo('#panel1_column2_new');

			
				$('#txtIdentification').on('input', function () {
					  const inputVal 	= $(this).val();
					  var cumple 		= false;
					  if (
						inputVal.length === 14 &&
						/^\d{13}[^\d]$/.test(inputVal)
					  ) {
						cumple = true;
					  } else {
						cumple = false;
					  }
					  
					  if(cumple == false)
						  return;
					  
					  
					  

					  const dia 			= inputVal.substring(3, 5);
					  const mes 			= inputVal.substring(5, 7);
					  const anioCorto 		= inputVal.substring(7, 9);
					  const anioCompleto 	= 1900 + parseInt(anioCorto, 10);
					  var stringYear		=  anioCompleto+'-'+mes+'-'+dia;
					  $('#txtBirthDate').val(stringYear);  
					  
				});
			}); 
			</script> ",

			
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
