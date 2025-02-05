<?php
function getBehavioCreditAguil(): array
{
    return array(
		//Credit Aguil
		strtolower('creditaguil_core_web_menu_FACTURACION')			 												=> "CONTRATOS",
		strtolower('creditaguil_core_web_menu_FACTURAR')			 													=> "CONTRATO",
		strtolower('creditaguil_core_web_menu_ABONO')			 													=> "RECIBO",
		strtolower('creditaguil_app_invoice_billing_divLabelZone') 		 											=> "Parentesco",
		strtolower('creditaguil_app_invoice_billing_txtTraductionPhone')	 											=> "Tel. Bene.",
		strtolower('creditaguil_app_invoice_billing_divTxtClienteBeneficiario')	 									=> "Bene. Nombre",
		strtolower('creditaguil_app_invoice_billing_divTxtCedulaBeneficiario')	 									=> "Bene. Cedula",		
		strtolower('creditaguil_app_invoice_billing_labelTitlePageList')	 											=> "CONTRATOS",
		strtolower('creditaguil_app_invoice_billing_labelTitlePageEdit')	 											=> "Contrato",
		strtolower('creditaguil_app_invoice_billing_labelTitlePageNew')	 											=> "Contrato",		
		strtolower('creditaguil_app_box_share_labelTitlePageList')	 												=> "RECIBOS",
		strtolower('creditaguil_app_box_share_labelTitlePageEdit')	 												=> "Recibo",
		strtolower('creditaguil_app_box_share_labelTitlePageNew')	 												=> "Recibo",		
		strtolower('creditaguil_app_invoice_billing_divHiddenReference')	 											=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divMesa')	 													=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divNextVisitHidden')	 											=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divBodegaHidden')	 											=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divTxtCambio')	 												=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divPrecio')	 													=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divDesembolsoEfectivo')	 										=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divReportSinRiesgo')												=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divProviderCredit')												=> "hidden",
		strtolower('creditaguil_app_invoice_billing_divApplied')														=> "hidden",
		
		
		strtolower('creditaguil_app_invoice_billing_txtTermReference')	 											=> "Plazo",
		strtolower('creditaguil_app_invoice_billing_txtTraductionExpenseLabel')										=> "Interes",
		strtolower('creditaguil_app_invoice_billing_divTraslateElement') 											=> "
		<script>
			$(document).ready(function(){		
				
				if( $('#txtTransactionMasterID').val() == undefined )
				$('#txtNote').val('');
			
				$('#divBeneficiario').appendTo('#divInformacionLeftReference');
				$('#divCedula').appendTo('#divInformacionLeftReference');
				$('#divZone').appendTo('#divInformacionLeftReference');
				$('#divTrasuctionPhone').appendTo('#divInformacionLeftReference');
				$('#divFixedExpenses').appendTo('#divInformacionRightReference');
				$('#divNote').appendTo('#divInformacionLeftZone');
				
			});
		</script>
		",		
		strtolower('creditaguil_app_invoice_billing_scriptValidateInCredit')											=> "
		if($('#txtReferenceClientIdentifier').val() == '')
		{
				fnShowNotification('Cedula del beneficiario','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		if($('#txtReferenceClientName').val() == '')
		{
				fnShowNotification('Nombre del beneficiario','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		if($('#txtNumberPhone').val() == '')
		{
				fnShowNotification('Telefono del beneficiario','error',timerNotification);
				result = false;
				fnWaitClose();
		}		
		if($('#txtReference2').val() == '1')
		{
				fnShowNotification('Plazo del credito','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		if($('#txtEmployeeID').val() == '614')
		{
				fnShowNotification('Vendedor','error',timerNotification);
				result = false;
				fnWaitClose();
		}
		
		",
    );
}
