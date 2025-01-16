<?php
function getBehavioColirio(){
    $colirio = array(
        strtolower('colirio_core_web_menu_CXC')			 													=> "ALUMNOS",
        strtolower('colirio_core_web_menu_CLIENTES')			 												=> "REG. ALUMNO",
        strtolower('colirio_core_web_menu_ABONO')			 													=> "PAGO",
        strtolower('colirio_core_web_menu_PRODUCTO')			 												=> "PRO./SER.",
        strtolower('colirio_app_cxc_customer_labelTitlePageList')												=> "ALUMNOS",
        strtolower('colirio_app_cxc_customer_labelTitlePageNew')												=> "ALUMNO",
        strtolower('colirio_app_cxc_customer_labelTitlePageEdit')												=> "ALUMNO",
        strtolower('colirio_app_box_share_labelTitlePageList')												=> "PAGOS",
        strtolower('colirio_app_box_share_labelTitlePageNew')													=> "PAGO",
        strtolower('colirio_app_box_share_labelTitlePageEdit')												=> "PAGO",
        strtolower('colirio_app_box_share_lblCliente')														=> "Alumno",
        strtolower('colirio_app_cxc_customer_divTxtPresupuesto')												=> "hidden",
        strtolower('colirio_app_cxc_customer_divTxtSubCategoria')												=> "hidden",
        strtolower('colirio_app_cxc_customer_divTxtCategoria')												=> "hidden",
        strtolower('colirio_app_cxc_customer_divTxtTipo')														=> "hidden",
        strtolower('colirio_app_cxc_customer_divTxtFullNameCommercial')										=> "Tutor",
        strtolower('colirio_app_cxc_customer_lblTxtIdentification')											=> "Codigo de alumno",
        strtolower('colirio_app_cxc_customer_divTxtHuella')													=> "hidden",
        strtolower('colirio_app_cxc_customer_divTxtBuro')														=> "hidden",
        strtolower('colirio_app_invoice_billing_labelTitlePageList')											=> "Recibos",
        strtolower('colirio_app_invoice_billing_labelTitlePageNew')											=> "Recibos",
        strtolower('colirio_app_invoice_billing_labelTitlePageEdit')											=> "Recibos",
        strtolower('colirio_app_invoice_billing_divTxtClienteBeneficiario')									=> "Alumno",
        strtolower('colirio_app_invoice_billing_divTxtClienteBeneficiarioPrincipal')							=> "Alumno",
        strtolower('colirio_app_invoice_billing_scriptValidateCustomer')										=> "
		
			if($('#txtNote').val() == 'sin comentarios.' )
			{
				fnShowNotification('Escribir comentario','error',timerNotification);
				result = false;
				fnWaitClose();
			}
		
		",
    );
    return $colirio;
}
