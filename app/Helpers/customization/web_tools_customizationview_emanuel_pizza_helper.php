<?php
function getBehavioEmanuelPizza(): array
{
    return array(
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCambio') 												=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divHiddeValue') 												=> "",
		strtolower('pizzaemanuel_app_invoice_billing_rowOptionPaymentExtrasTarjeta') 								=> "",
		strtolower('pizzaemanuel_app_invoice_billing_rowOptionPaymentExtras') 								        => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_panelResumenFactura') 								        	=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divPestanaCredito') 								            => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divPestanaReferencias') 								        => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divPestanaMas') 								                => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtFecha') 								                	=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtMoneda') 								               	=> "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCliente') 							                    => "",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCliente2') 							                    => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCedula2') 							                    => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCausalID') 							                    => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_txtScanerBarCode') 							                => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_btnNewItemCatalog') 							                => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCausalIDScript') 							            => "",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtCedulaBeneficiario')						            	=> "RUC",
		
		
		
		strtolower('pizzaemanuel_app_invoice_billing_divTxtZone') 							                        => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divHiddenEmployer') 							                => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divBtnPrecios') 							                    => "hidden",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtMesa') 												    => "disabled",
		strtolower('pizzaemanuel_app_invoice_billing_divTxtTotal') 													=> "**",	
		strtolower('pizzaemanuel_app_invoice_billing_divLabelNumDescuston') 										=> "**",	
		
		strtolower('pizzaemanuel_app_invoice_billing_btnFooter') 												    => "",
        strtolower('pizzaemanuel_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				$('#divVendedor').appendTo('#divInformacionLeft');
				$('#divMesa').appendTo('#divInformacionRight');
				$('#divZone').appendTo('#divInformacionLeft');		
				$('#btnAceptarDialogCocinaV2').removeClass('hidden');		
				$('#btnNewItemCatalog').addClass('hidden');		
				$('#btnLinkPayment').addClass('hidden');
				$('#btnFooter').addClass('hidden');
                
                if(esMesero === '1')
				{
				    $('#divPaymentOption').addClass('hidden');
				    $('.btnAcept').addClass('hidden');
				    $('.btnAceptAplicar').removeClass('hidden');
					$('#txtServices').parent().parent().addClass('hidden');
					$('#txtDescuento').parent().parent().addClass('hidden');
					$('#txtPorcentajeDescuento').parent().parent().addClass('hidden');
					$('#txtIva').parent().parent().addClass('hidden');
					$('#txtSubTotal').parent().parent().addClass('hidden');
				}
				else
				{   
					$('#txtServices').parent().parent().addClass('hidden');
					$('#txtDescuento').parent().parent().addClass('hidden');					
					$('#txtIva').parent().parent().addClass('hidden');
					$('#txtSubTotal').parent().parent().addClass('hidden');					
				}
				

			});
		</script>",
		
		strtolower('pizzaemanuel_app_cxc_customer_divPestanaReferencias') 							                    => "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divPestanaTelefono') 							                    	=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divPestanaEmail') 							                    	=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divPestanaCXC') 							                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divPestanaCXCLineas') 							                    => "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divPestanaMas') 							                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divRigthHome') 							                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divTxtApellidos')							                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divTxtNombreComercial')					                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_lblTxtFullName')					                    				=> "Dirección",
		strtolower('pizzaemanuel_app_cxc_customer_divTxtTypeIdentification')				                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divTxtIdentification')					                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_showBtnViewLeads')						                    		=> "hidden",
		strtolower('pizzaemanuel_app_cxc_customer_divScriptValideFunctionPre')					                    	=> "
			if( $('#txtFirstName').val()   == ''){
				fnShowNotification('Escribir Primer Nombre','error',timerNotification);
				result = false;
			}
			if( $('#txtPhoneNumber').val()   == ''){
				fnShowNotification('Escribir Telefono','error',timerNotification);
				result = false;
			}
			
			$('#txtIdentification').val('nd');			
			return result;
		",
		
		
		
		
		
		
		
		
    );
}
