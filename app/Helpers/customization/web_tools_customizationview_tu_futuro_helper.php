<?php
function getBehavioTuFuturo(): array
{
    return array(
		//TuFuturo
        strtolower('tu_futuro_core_web_menu_SIMULADOR')			 					=> "CALCULADORA",
        strtolower('tu_futuro_core_web_menu_FACTURACION')			 					=> "PRESTAMOS",
        strtolower('tu_futuro_core_web_menu_FACTURAR')			 					=> "PRESTAR",
        strtolower('tu_futuro_core_web_menu_VENTAS')			 						=> "DESEMBOLSO",
        strtolower('tu_futuro_core_web_menu_DETALLE DE VENTAS')			 			=> "DETALLE DE DESEMBOLSOS",
        strtolower('tu_futuro_core_web_menu_RESUMEN DE VENTAS')			 			=> "RESUMEN DE DESEMBOLSOS",
        strtolower('tu_futuro_core_web_menu_COBRANZA')			 					=> "CONF. RUTAS",
        strtolower('tu_futuro_core_web_menu_RRHH')			 						=> "RRHH RUTAS",
        strtolower('tu_futuro_core_web_menu_COLABORADORES')			 				=> "COLAB. RUTA.",
        strtolower('tu_futuro_app_collection_manager_lblTitleList')			 		=> "CLIENTES POR RUTAS",
        strtolower('tu_futuro_app_collection_manager_lblTitleAdd')			 		=> "AGREGAR CLIENTE A LA RUTA",
        strtolower('tu_futuro_app_collection_manager_lblColaborador')			 		=> "Ruta",
        strtolower('tu_futuro_app_rrhh_employee_lblList')			 					=> "LISTA DE RUTAS Y/O COLABORADOR",
        strtolower('tu_futuro_app_rrhh_employee_lblNew')			 					=> "NUEVA RUTA Y/O COLABORADOR",
        strtolower('tu_futuro_app_rrhh_employee_lblEdit')			 					=> "EDITAR RUTA Y/O COLABORADOR",
        strtolower('tu_futuro_app_cxc_customer_divTxtCategoria')			 			=> "hidden",
        strtolower('tu_futuro_app_cxc_customer_divTxtFullNameCommercial')				=> "Categoria",
        strtolower('tu_futuro_app_cxc_customer_lblTxtPhoneTemp')						=> "Prefesion u Oficio",
        strtolower('tu_futuro_app_cxc_customer_divTxtProfesionUFicio')				=> "hidden",	
		strtolower('tu_futuro_app_cxc_customer_divTxtNombreLegal') 					=> "hidden",
		strtolower('tu_futuro_app_cxc_customer_divScriptValideFunctionPre') 			=> "
		
			var firstName 	= $('#txtFirstName').val();
			var lastName 	= $('#txtLastName').val();
			$('#txtLegalName').val(firstName + ' ' + lastName );
			
		
		",
		
		strtolower('tu_futuro_app_invoice_billing_divTraslateElement')				=> "
		<script>
			$(document).ready(function(){       
				$('#divInformacionRightReference').addClass('hidden');
			});
		</script>
		",

		
		
        strtolower('tu_futuro_app_cxc_customer_showBtnIrBuro')			 			=> "",
        strtolower('tu_futuro_app_cxc_customer_showBtnIrSimulador')			 		=> "",
        strtolower('tu_futuro_app_cxc_customer_showBtnIrInvoice')			 			=> "",
        strtolower('tu_futuro_app_cxc_customer_showBtnIrShare')			 			=> "",
        strtolower('tu_futuro_app_cxc_customer_showBtnGroupAcciones')	 				=> "",
        strtolower('tu_futuro_app_cxc_record_showBtnIrCustomerOfRecord') 				=> "",
        strtolower('tu_futuro_app_cxc_simulation_showBtnIrCustomerOfSimulator') 		=> "",
        strtolower('tu_futuro_app_box_share_showBtnIrCustomerOfShare') 				=> "",
        strtolower('tu_futuro_app_cxc_customer_divScriptValideFunction') 	 			=> "
		
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
