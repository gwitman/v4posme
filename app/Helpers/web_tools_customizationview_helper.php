<?php
function getBehavio($type_company,$key_controller,$key_element)
{
	$divs = array(
		strtolower('default_comand_traducir_O. SALIDAS')			 								=> "O. SALIDAS",
		strtolower('default_comand_traducir_O. ENTRADAS')			 								=> "O. ENTRADAS",
		
		strtolower('default_default_masterpage_backgroundImage')			 						=> "",
		strtolower('default_core_account_idtest')													=> "hidden",
		strtolower('default_app_invoice_billing_panelResumenFactura')								=> "",
		strtolower('default_app_invoice_billing_panelResumenFacturaTool')							=> "hidden",
		strtolower('default_app_invoice_billing_bodyListInvoice')			 						=> "",
		strtolower('default_app_box_share_stylePage')			 									=> "",
		strtolower('default_app_box_share_labelReference1')			 								=> "Referencia1",
		strtolower('default_app_box_share_divResumenAbono')			 								=> "",
		strtolower('default_app_box_share_divStart')			 									=> "",
		strtolower('default_app_box_share_divFecha')			 									=> "",
		strtolower('default_app_box_share_divAplicado')			 									=> "",
		strtolower('default_app_box_share_divCambio')			 									=> "",
		strtolower('default_app_box_share_comboStyle')			 									=> "select2",
		strtolower('default_app_box_share_new_script_validate_reference1')							=> "",
		strtolower('default_app_box_share_javscriptVariable_varShareMountDefaultOfAmortization')	=> "true",
		strtolower('default_app_box_share_javscriptVariable_varPrinterOnlyFormat')					=> "false",
		strtolower('default_app_box_share_TableColumnDocumento')									=> "",		
		strtolower('default_app_box_share_divCustomerControlBuscar')								=> "",
		strtolower('default_app_box_share_divCustomerControlSelected')								=> "hidden",
		strtolower('default_app_box_share_divCobrador')												=> "",
		strtolower('default_app_box_share_divMoneda')												=> "",
		
		
		
		strtolower('default_app_box_share_btnVerMovimientos')										=> "",
		strtolower('default_app_inventory_transferoutput_parameterValidarEnvioDestino')				=> "false",
		strtolower('default_app_inventory_transferoutput_labelReference1')							=> "Referencia",
		strtolower('default_app_inventory_item_labelBarCode')										=> "Barra",
		
		
		
		
		/*Ferreteria Mateo*/
		strtolower('ferreteria_mateo_app_invoice_billing_bodyListInvoice')	 		=> "height: 550px; overflow: scroll;",
		strtolower('ferreteria_mateo_app_box_share_stylePage')	 					=> "
			/*posMe stylePage*/
			#content .row{
				margin-bottom:0px !important;
			}
			.email-bar{
			    margin-bottom:0px !important;
			}
			.form-group{
				margin-bottom:0px !important;
			}
			.si_ferreteria_mateo{
				display:none !important;
			}
		",
		
		/*GlobalPro*/
		strtolower('globalpro_comand_traducir_Conceptos')			 				=> "IVA",
		strtolower('globalpro_comand_traducir_PUBLICO')			 					=> "PRECIO OFERTA",
		strtolower('globalpro_comand_traducir_POR MAYOR')			 				=> "REGULAR",
		strtolower('globalpro_comand_traducir_CREDITO')			 					=> "POR MAYOR",
		strtolower('globalpro_comand_traducir_CREDITO POR MAYOR')			 		=> "LIQUIDACION",
		strtolower('globalpro_comand_traducir_ESPECIAL')			 				=> "ESPECIAL",
		
		
		strtolower('globalpro_default_masterpage_backgroundImage')			 		=> "style='background-image: url(".  base_url()."/resource/img/logos/fondo_globalpro.jpg"   .");'",
		
		strtolower('globalpro_core_dashboards_divPanelCuadroMembresia')				=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelBiblico')						=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelSoporteTenico')				=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelFormaPago')					=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelInfoPago')					=> "hidden",
		strtolower('globalpro_core_dashboards_divPanelUsuario')						=> "hidden",
		
		strtolower('globalpro_app_invoice_billing_panelResumenFacturaTool')			=> "",
		strtolower('globalpro_app_invoice_billing_panelResumenFactura')				=> "hidden",
		strtolower('globalpro_app_invoice_billing_divTxtCambio') 					=> "hidden",
		strtolower('globalpro_app_invoice_billing_divTxtMoneda') 			 		=> "hidden",		
		strtolower('globalpro_app_invoice_billing_divTxtCliente2') 			 		=> "hidden",
		strtolower('globalpro_app_invoice_billing_divTxtCedula2') 					=> "hidden",
		strtolower('globalpro_app_invoice_billing_divTraslateElement') 	=> "
				<script>
					$(document).ready(function(){							
							$('#divVendedor').appendTo('#divInformacionLeft');
							$('#divBodega').appendTo('#divInformacionLeft');
					});
				</script>
		",
		
		
		strtolower('globalpro_app_inventory_item_divTxtBarCode') 					=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtPerecedero') 			 	=> "hidden",		
		strtolower('globalpro_app_inventory_item_divTxtCapacidad') 			 		=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtCantidadMinima') 			=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtCantidadMaxima') 			=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtSKUCompras') 			 	=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtSKUProduccion') 				=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtEstado') 			 		=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtFamilia') 			 		=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtUM') 			 			=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtPresentacion') 			 	=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtPresentacionUM') 			=> "hidden",
		strtolower('globalpro_app_inventory_item_divTxtUM') 			 			=> "hidden",
		strtolower('globalpro_app_inventory_item_divTraslateElementTablePrecio') 	=> "
				<script>
					$(document).ready(function(){
							$('#btnPrice').parent().remove();
							$('#tblPrecios').appendTo('#divContainerRowPersonalization');
					});
				</script>
		",
		
		
		
		strtolower('globalpro_app_cxc_customer_divTxtNombres') 								=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtApellidos') 			 				=> "hidden",		
		strtolower('globalpro_app_cxc_customer_divTxtNombreComercial') 			 			=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtEstado') 								=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtClasificacion') 						=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtTipo') 			 					=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtCategoria') 							=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtSubCategoria') 			 			=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtEstadoCivil') 			 				=> "hidden",
		strtolower('globalpro_app_cxc_customer_divTxtProfesionUFicio') 			 			=> "hidden",
		strtolower('globalpro_app_cxc_customer_divScriptCustom') 							=> "
				<script>
					$(document).ready(function(){
							
							$('#txtSexoID').val('');
							$('#txtSexoID').trigger('change');
							
							$(document).on('focusout','#txtLegalName',function(){
									
									var varLegalName 	= $('#txtLegalName').val();
									$('#txtFirstName').val(  varLegalName  );
									$('#txtLastName').val(  varLegalName  );
									$('#txtCommercialName').val(  varLegalName  );
									
							});
							
					});
				</script>
		",
		
		strtolower('globalpro_app_box_share_labelReference1')			 				=> "Atiende",
		strtolower('globalpro_app_box_share_new_script_validate_reference1')			=> "
			//Validar Atiende
			if($('#txtReference1').val() == ''){
				fnShowNotification('Escriba quien le atiende','error',timerNotification);
				result = false;
			}
		",		
		strtolower('globalpro_app_inventory_inputunpost_new_script_validate_reference1')			=> "
			//Validar Referecia 1
			if($('#txtReference1').val() == ''){
				fnShowNotification('Escriba Referencia 1 es obligatoria','error',timerNotification);
				result = false;
			}
		",
		strtolower('globalpro_comand_traducir_O. SALIDAS')			 								=> "AJUSTE SALIDA",
		strtolower('globalpro_comand_traducir_O. ENTRADAS')			 								=> "AJUSTE ENTRADA",
		strtolower('globalpro_app_inventory_transferoutput_parameterValidarEnvioDestino')			=> "true",
		strtolower('globalpro_app_inventory_transferoutput_labelReference1')						=> "Orden / Cliente",
		
		
		strtolower('globalpro_comand_traducir_Referencia 1')										=> "Descripcion",
		strtolower('globalpro_comand_traducir_Referencia 2')										=> "Factura",
		strtolower('globalpro_comand_traducir_Referencia 3')										=> "Proveedor",
		
		/*Clinica larreynaga*/
		strtolower('clinicalarreynaga_comand_traducir_Edad') 										=> "Genero",		
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtNombres') 								=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtApellidos') 			 				=> "hidden",		
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtNombreComercial') 			 			=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtEstado') 								=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtClasificacion') 						=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtTipo') 			 					=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtCategoria') 							=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtSubCategoria') 			 			=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtEstadoCivil') 			 				=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divTxtProfesionUFicio') 			 			=> "hidden",
		strtolower('clinicalarreynaga_app_cxc_customer_divScriptCustom') 							=> "
				<script>
					$(document).ready(function(){
							
						
							$(document).on('focusout','#txtLegalName',function(){
									
									var varLegalName 	= $('#txtLegalName').val();
									$('#txtFirstName').val(  varLegalName  );
									$('#txtLastName').val(  varLegalName  );
									$('#txtCommercialName').val(  varLegalName  );
									$('#txtIdentification').val(  'ND'  );
									
							});
							
					});
				</script>
		",
		
		
		/*Chec extensiones*/
		strtolower('chicextensiones_app_invoice_billing_divTxtCedula2') 							=> "hidden",		
		strtolower('chicextensiones_app_invoice_billing_divTraslateElement') 						=> "
				<script>
					$(document).ready(function()
					{
							//quitar el atributo de oculto
							$('#divTxtElementoDisponibleParaMover1').removeClass('hidden');
							
							//pasar divZone pasar a divTxtElementoDisponibleParaMover1
							$('#divZone').appendTo('#divTxtElementoDisponibleParaMover1');
							
					});
				</script>
		",
		
		
		/*Exceso*/
		strtolower('exceso_app_inventory_item_labelBarCode')										=> "Barra / IMAI",
		strtolower('exceso_app_inventory_item_divTxtCapacidad') 			 						=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtCantidadMinima') 								=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtCantidadMaxima') 								=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtSKUCompras') 			 						=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtSKUProduccion') 								=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtEstado') 			 							=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtFamilia') 			 							=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtUM') 			 								=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtPresentacion') 			 						=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtPresentacionUM') 								=> "hidden",
		strtolower('exceso_app_inventory_item_divTxtUM') 			 								=> "hidden",
		
		
		/*GBT*/
		strtolower('gbt_app_box_share_divResumenAbono')			 									=> "hidden",
		strtolower('gbt_app_box_share_divStart')			 										=> "hidden",
		strtolower('gbt_app_box_share_divFecha')			 										=> "hidden",
		strtolower('gbt_app_box_share_divAplicado')			 										=> "hidden",
		strtolower('gbt_app_box_share_divCambio')			 										=> "hidden",
		strtolower('gbt_app_box_share_comboStyle')			 										=> "",
		strtolower('gbt_app_box_share_javscriptVariable_varShareMountDefaultOfAmortization')		=> "false",
		strtolower('gbt_app_box_share_TableColumnDocumento')										=> "hidden",
		strtolower('gbt_app_box_share_btnVerMovimientos')											=> "hidden",
		strtolower('gbt_app_box_share_javscriptVariable_varPrinterOnlyFormat')						=> "true",
		strtolower('gbt_app_box_share_divCustomerControlBuscar')									=> "hidden",
		strtolower('gbt_app_box_share_divCustomerControlSelected')									=> "",
		strtolower('gbt_app_box_share_divCobrador')													=> "hidden",
		strtolower('gbt_app_box_share_divMoneda')													=> "hidden",
		
		
		/*KHADASH*/
		strtolower('khadash_app_box_share_divResumenAbono')			 									=> "hidden",
		strtolower('khadash_app_box_share_divStart')			 										=> "hidden",
		strtolower('khadash_app_box_share_divFecha')			 										=> "hidden",
		strtolower('khadash_app_box_share_divAplicado')			 										=> "hidden",
		strtolower('khadash_app_box_share_divCambio')			 										=> "hidden",
		strtolower('khadash_app_box_share_comboStyle')			 										=> "",
		strtolower('khadash_app_box_share_javscriptVariable_varShareMountDefaultOfAmortization')		=> "false",
		strtolower('khadash_app_box_share_TableColumnDocumento')										=> "hidden",
		strtolower('khadash_app_box_share_btnVerMovimientos')											=> "hidden",
		strtolower('khadash_app_box_share_javscriptVariable_varPrinterOnlyFormat')						=> "true",
		strtolower('khadash_app_box_share_divCustomerControlBuscar')									=> "",
		strtolower('khadash_app_box_share_divCustomerControlSelected')									=> "hidden",
		strtolower('khadash_app_box_share_divCobrador')													=> "hidden",
		strtolower('khadash_app_box_share_divMoneda')													=> "hidden"
		
	);
	
	
	
	if($key_controller != "comand_traducir")
	{
		//comportamiento
		//buscar comportamiento de la empresa
		$key = strtolower($type_company)."_".strtolower($key_controller)."_".strtolower($key_element);
		if(!array_key_exists( $key, $divs) )
		{
			
			//buscar comportamiento de la empresa
			$key = strtolower("default")."_".strtolower($key_controller)."_".strtolower($key_element);
			if(!array_key_exists( $key, $divs) )
			{	
				return "";
			}
			else 
			{
				return $divs[$key];
			}
			
		}
		else 
		{
			return $divs[$key];
		}
		
	}
	else 
	{
		//lenguaje
		//buscar traduccion de la empresa
		$key = strtolower($type_company)."_".strtolower($key_controller)."_".strtolower($key_element);
		if(!array_key_exists( $key, $divs) )
		{
			return $key_element;
		}
		else 
		{
			return $divs[$key];
		}
	}
		
}
