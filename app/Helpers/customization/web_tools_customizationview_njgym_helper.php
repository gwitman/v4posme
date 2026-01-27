<?php
function getBehavioNJGYM(): array
{
    return array(
		//NJ Gym        		
		strtolower('gymJalapa_app_cxc_customer_divTxtNombreComercial')		 			=> "hidden",
        strtolower('gymJalapa_app_cxc_customer_divTxtCategoria')			 			=> "hidden",
        strtolower('gymJalapa_app_cxc_customer_divTxtFullNameCommercial')				=> "Categoria",
        strtolower('gymJalapa_app_cxc_customer_lblTxtPhoneTemp')						=> "Prefesion u Oficio",
        strtolower('gymJalapa_app_cxc_customer_divTxtProfesionUFicio')					=> "hidden",	
		strtolower('gymJalapa_app_cxc_customer_divTxtNombreLegal') 						=> "hidden",
		strtolower('gymJalapa_app_cxc_customer_divScriptValideFunctionPre') 			=> "		
			var firstName 	= $('#txtFirstName').val();
			var lastName 	= $('#txtLastName').val();
			$('#txtLegalName').val(firstName + ' ' + lastName );
			$('#txtCommercialName').val('ND');
			
		",
        strtolower('gymJalapa_app_cxc_customer_showBtnIrBuro')			 				=> "hidden",
        strtolower('gymJalapa_app_cxc_customer_showBtnIrSimulador')			 			=> "hidden",
        strtolower('gymJalapa_app_cxc_customer_showBtnIrInvoice')			 			=> "hidden",
        strtolower('gymJalapa_app_cxc_customer_showBtnIrShare')			 				=> "hidden",
        strtolower('gymJalapa_app_cxc_customer_showBtnGroupAcciones')	 				=> "hidden",        
        strtolower('gymJalapa_app_cxc_customer_divScriptValideFunction') 	 			=> "		
		if( $('#txtIdentification').val()  == ''){
			fnShowNotification('Escribir cedula','error',timerNotification);
			result = false;
		}
		if( $('#txtIdentification').val()  == '0'){
			fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
		}
		",

    );
}
