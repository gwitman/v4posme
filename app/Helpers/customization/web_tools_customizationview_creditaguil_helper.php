<?php
function getBehavioCreditAguil(): array
{
    return array(
		//Credit Aguil
        strtolower('creditaguil_core_web_menu_SIMULADOR')			 					=> "CALCULADORA",
        strtolower('creditaguil_core_web_menu_FACTURACION')			 				=> "PRESTAMOS",
        strtolower('creditaguil_core_web_menu_FACTURAR')			 					=> "PRESTAR",
        strtolower('creditaguil_core_web_menu_VENTAS')			 					=> "DESEMBOLSO",
        strtolower('creditaguil_core_web_menu_DETALLE DE VENTAS')			 			=> "DETALLE DE DESEMBOLSOS",
        strtolower('creditaguil_core_web_menu_RESUMEN DE VENTAS')			 			=> "RESUMEN DE DESEMBOLSOS",
        strtolower('creditaguil_core_web_menu_COBRANZA')			 					=> "CONF. RUTAS",
        strtolower('creditaguil_core_web_menu_RRHH')			 						=> "RRHH RUTAS",
        strtolower('creditaguil_core_web_menu_COLABORADORES')			 				=> "COLAB. RUTA.",
        strtolower('creditaguil_app_collection_manager_lblTitleList')			 		=> "CLIENTES POR RUTAS",
        strtolower('creditaguil_app_collection_manager_lblTitleAdd')			 		=> "AGREGAR CLIENTE A LA RUTA",
        strtolower('creditaguil_app_collection_manager_lblColaborador')			 	=> "Ruta",
        strtolower('creditaguil_app_rrhh_employee_lblList')			 				=> "LISTA DE RUTAS Y/O COLABORADOR",
        strtolower('creditaguil_app_rrhh_employee_lblNew')			 				=> "NUEVA RUTA Y/O COLABORADOR",
        strtolower('creditaguil_app_rrhh_employee_lblEdit')			 				=> "EDITAR RUTA Y/O COLABORADOR",
        strtolower('creditaguil_app_cxc_customer_divTxtCategoria')			 		=> "hidden",
        strtolower('creditaguil_app_cxc_customer_divTxtFullNameCommercial')			=> "Categoria",
        strtolower('creditaguil_app_cxc_customer_lblTxtPhoneTemp')					=> "Prefesion u Oficio",
        strtolower('creditaguil_app_cxc_customer_divTxtProfesionUFicio')				=> "hidden",


        strtolower('creditaguil_app_cxc_customer_showBtnIrBuro')			 			=> "",
        strtolower('creditaguil_app_cxc_customer_showBtnIrSimulador')			 		=> "",
        strtolower('creditaguil_app_cxc_customer_showBtnIrInvoice')			 		=> "",
        strtolower('creditaguil_app_cxc_customer_showBtnIrShare')			 			=> "",
        strtolower('creditaguil_app_cxc_customer_showBtnGroupAcciones')	 			=> "",
        strtolower('creditaguil_app_cxc_record_showBtnIrCustomerOfRecord') 			=> "",
        strtolower('creditaguil_app_cxc_simulation_showBtnIrCustomerOfSimulator') 	=> "",
        strtolower('creditaguil_app_box_share_showBtnIrCustomerOfShare') 				=> "",
        strtolower('creditaguil_app_cxc_customer_divScriptValideFunction') 	 		=> "
		
		if( $('#txtIdentification').val()  == ''){
			fnShowNotification('Escribir cedula','error',timerNotification);
			result = false;
		}
		if( $('#txtIdentification').val()  == '0'){
			fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
		}
		
		const regex = /^\d{3}-\d{6}-\d{4}[A-Za-z]$/;
		if(!regex.test(   $('#txtIdentification').val()   )){
            fnShowNotification('Escribir cedula con formato correcto','error',timerNotification);
			result = false;
        } 
		
		
		",

    );
}
