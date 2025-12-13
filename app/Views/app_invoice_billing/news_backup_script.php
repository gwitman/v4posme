<!-- ./ page heading -->
<script>
	var baseUrl 											= '<?php echo base_url(); ?>';
	let objCausalTypeCredit 								= JSON.parse('<?php echo json_encode($objCausalTypeCredit); ?>');
	let objListCustomerCreditLine 							= JSON.parse('<?php echo json_encode($objListCustomerCreditLine); ?>');	
	let objCurrencyCordoba 									= JSON.parse('<?php echo json_encode($objCurrencyCordoba); ?>');
	var varParameterINVOICE_BILLING_VALIDATE_EXONERATION 	= '<?php echo $objParameterINVOICE_BILLING_VALIDATE_EXONERATION; ?>';	
	var varUrlPrinter										= '<?php echo $urlPrinterDocument; ?>';
	var varParameterAmortizationDuranteFactura				= <?php echo $objParameterAmortizationDuranteFactura; ?>;
	
	var varStatusInvoiceAplicado			= 67; //Estado Aplicada
    var varStatusInvoiceAnular				= 68; //Anular
	var varStatusInvoiceRegistrado			= 66; //Registrado
	var isLoading 							= true;
	
	var objConfigInit 		= {
		texts: {
			'txtNombre': 'Nombre completo',
			'chkActivo': '¿Está activo?'
		},
		comboStores: {
			'txtCurrencyID': [
				<?php
					$count = 0;
					if($listCurrency)
					foreach($listCurrency as $currency)
					{
						echo "{ name: '".$currency->name."', id: '".$currency->currencyID."' },";
						$count++;
					}
				?>				
			],
			'txtCausalID' : [
				<?php
					$count = 0;
					if($objCaudal)
					foreach($objCaudal as $causal)
					{
						echo "{ name: '".$causal->name."', id: '".$causal->transactionCausalID."' },";
						$count++;
					}
				?>		
			],
			'txtZoneID' : [
				<?php
					$count = 0;
					if($objListZone)
					foreach($objListZone as $z){												
						echo "{ name: '".$z->display."', id: '".$z->catalogItemID."' },";
						$count++;
					}
				?>
			],
			'txtTypePriceID' : [
				<?php
					$count = 0;
					if($objListTypePrice)
					foreach($objListTypePrice as $z){												
						echo "{ name: '".$z->display."', id: '".$z->catalogItemID."' },";
						$count++;
					}
				?>
			],
			'txtWarehouseID' : [
				<?php
					$count = 0;
					if($objListWarehouse)
					foreach($objListWarehouse as $ware){												
						echo "{ name: '".$ware->name."', id: '".$ware->warehouseID."' },";
						$count++;
					}
				?>
			],
			'txtEmployeeID' : [
				<?php
					$count					= 0;
					$employerDefault 		= "true"; //$objParameterINVOICE_BILLING_EMPLOYEE_DEFAULT;					
					if($objListEmployee)
					foreach($objListEmployee as $employee){						
						echo "{ name: '".$employee->firstName."', id: '".$employee->entityID."' },";
						$count++;
					}
				?>
			],
			'txtMesaID' : [
				<?php
					$count = 0;
					if($objListMesa)
					foreach($objListMesa as $ware){
						echo "{ name: '".$ware->display."', id: '".$ware->catalogItemID."' },";
						$count++;
					}
				?>
			],
			'txtPeriodPay' : [
				<?php
					$count = 0;
					if($objListPay)
					foreach($objListPay as $ws){
						echo "{ name: '".$ws->display."', id: '".$ws->catalogItemID."' },";								
					}
				?>
			],
			'txtReference1' : [
				<?php
					$count = 0;
					if($listProvider)
					foreach($listProvider as $ws){
						echo "{ name: '".$ws->firstName."', id: '".$ws->entityID."' },";								
					}
				?>
			], 
			'txtDayExcluded' : [
				<?php
					$count = 0;
					if($objListDayExcluded)
					foreach($objListDayExcluded as $ws){
						echo "{ name: '".$ws->name."', id: '".$ws->catalogItemID."' },";								
					}
				?>
			], 
			'txtReceiptAmountTarjeta_BankID' : [
				<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						echo "{ name: '".$ws->name."', id: '".$ws->bankID."' },";								
					}
				?>
			], 
			'txtReceiptAmountTarjetaDol_BankID' : [
				<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						echo "{ name: '".$ws->name."', id: '".$ws->bankID."' },";								
					}
				?>
			], 
			'txtReceiptAmountBank_BankID' : [
				<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						echo "{ name: '".$ws->name."', id: '".$ws->bankID."' },";								
					}
				?>
			], 
			'txtReceiptAmountBankDol_BankID' : [
				<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						echo "{ name: '".$ws->name."', id: '".$ws->bankID."' },";								
					}
				?>
			], 
		},
		hidden: {
			'txtCustomerCreditLineID': true
		},
		disabled: {
			'txtNombre': false,
			'cmbClientes': false
		},
		readOnly: {
			'txtNombre': false
		},
		values:
		{
			'txtTM_transactionNumber' : 'PRF00000000',
			'txtUserID': '<?= $userID; ?>',
			'txtCompanyID' : '<?php echo $companyID ?>',
			'txtTransactionID' : '<?php echo $transactionID ?>',
			'txtTransactionMasterID' : '0',
			'txtCodigoMesero' : '<?= $codigoMesero;  ?>',			
			'txtStatusID' : varStatusInvoiceRegistrado,
					
			'txtStatusIDOld' : 0,			
			'txtDate': fnFormatDateYYYY_MM_DD(new Date()),
			'txtExchangeRate': '<?php echo $exchangeRate; ?>',	
			'txtNote' : 'sin comentarios',
			'txtCurrencyID': '<?php
					$count = 0;
					if($listCurrency)
					foreach($listCurrency as $currency)
					{
						if( $currency->name == $objParameterACCOUNTING_CURRENCY_NAME_IN_BILLING  )
						echo $currency->currencyID;
					}
				?>',
			'txtCustomerID' : '<?php echo $objCustomerDefault->entityID;  ?>',
			'txtCustomerDescription' : '<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>',
			'txtReferenceClientName' : '',
			'txtReferenceClientIdentifier' : '' ,
			'txtCausalID' : '<?php
					$count = 0;
					if($objCaudal)
					foreach($objCaudal as $causal)
					{
						if($count == 0)
						echo $causal->transactionCausalID;
						$count++;
					}
				?>',
			'txtCustomerCreditLineID' : 0,
			'txtZoneID' : '<?php
					$count = 0;
					if($objListZone)
					foreach($objListZone as $z){	
						if($count == 0)
						echo $z->catalogItemID;
						$count++;
					}
				?>',
			'txtTypePriceID' : '<?php
					$count = 0;
					if($objListTypePrice)
					foreach($objListTypePrice as $price){		
						if($price->catalogItemID == $objParameterTypePreiceDefault )				
							echo $price->catalogItemID;
						$count++;
					}
				?>',
			'txtWarehouseID' : '<?php
					$count 	= 0;
					$find 	= false;
					if($objListWarehouse)
					foreach($objListWarehouse as $ware){	
						if($ware->typeWarehouse == $objParameterTipoWarehouseDespacho && $find == false )
						{
							$find = true;
							echo $ware->warehouseID;
						}
						$count++;
					}
				?>',
			'txtReference3' : '' ,
			'txtEmployeeID' : '<?php
					$count					= 0;
					$employerDefault 		= "true"; //$objParameterINVOICE_BILLING_EMPLOYEE_DEFAULT;					
					if($objListEmployee)
					foreach($objListEmployee as $employee){	
						if($count == 0 && $employerDefault == "true")				
							echo $employee->entityID;
						
						$count++;
					}
				?>',
			'txtNumberPhone' : '',
			'txtMesaID' : '<?php
					$count = 0;
					if($objListMesa)
					foreach($objListMesa as $ware){
						if($count == 0)
						echo $ware->catalogItemID;
						$count++;
					}
				?>',
			'txtNextVisit' : '',
			'txtDateFirst' : fnFormatDateYYYY_MM_DD(new Date()),
			'txtReference2' : '<?php echo  $objParameterCXC_PLAZO_DEFAULT; ?>',			
			'txtPeriodPay' : '<?php
					$count = 0;
					if($objListPay)
					foreach($objListPay as $ws){
						if($ws->catalogItemID == $objParameterCXC_FRECUENCIA_PAY_DEFAULT )
							echo $ws->catalogItemID;
					}
				?>',
			'txtReference1' : '<?php
					$count = 0;
					if($listProvider)
					foreach($listProvider as $ws){
						if($count == 0)
							echo $ws->entityID;								
						$count++;
					}
				?>', 
			'txtDayExcluded' : '<?php
					$count = 0;
					if($objListDayExcluded)
					foreach($objListDayExcluded as $ws){
						if($ws->catalogItemID == $objParameterCXC_DAY_EXCLUDED_IN_CREDIT)
							echo $ws->catalogItemID;
					}
				?>',
			'txtFixedExpenses' : 0,
			'txtCheckApplyExoneracion': 0,			
			'txtLayFirstLineProtocolo' : '' ,
			'txtCheckDeEfectivo' : 0,
			'txtCheckReportSinRiesgoValue' : 0,
			'txtTMIReference1' : '',
			
			'txtSubTotal' : 0.00,
			'txtIva' : 0.00,
			'txtPorcentajeDescuento' : 0.00,
			'txtDescuento' : 0.00,
			'txtServices' : 0.00,
			'txtTotal' : 0.00,
			
			'txtChangeAmount' : 0.00,
			'txtReceiptAmount' : 0.00,
			'txtReceiptAmountDol' : 0.00,	
			
			'txtReceiptAmountTarjeta' : 0.00,
			'txtReceiptAmountTarjeta_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
			'txtReceiptAmountTarjeta_Reference' : '',

			
			
			'txtReceiptAmountTarjetaDol' : 0.00,
			'txtReceiptAmountTarjetaDol_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
			'txtReceiptAmountTarjetaDol_Reference' : '',

			
			'txtReceiptAmountBank' : 0.00,
			'txtReceiptAmountBank_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
			'txtReceiptAmountBank_Reference' : '',
			
			
			'txtReceiptAmountBankDol' : 0.00,
			'txtReceiptAmountBankDol_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
			'txtReceiptAmountBankDol_Reference' : '',
			'txtReceiptAmountPoint' : 0.00,
			'txtTransactionMasterDetail': [] 
		},
		labels:{
			'txtTM_transactionNumber': 'PRF00000000'
		}
		
	};
					
		
	var objConfigMappingConfig = 
	{
		txtTransactionMasterID: "transactionMasterID",
		catalogItemID: 			"txtTMD_txtCatalogItemIDSku",
		name: 					"txtTMD_skuFormatoDescription",
		price: 					"txtTMD_txtPrice",
		value: 					"txtTMD_txtRatioSku",
		iva: 					"txtTMD_txtIva",
		quantity: 				"txtTMD_txtQuantity",
		subtotal: 				"txtTMD_txtSubTotal"
	};
	
	
	
	
	//-var producto = {
	//-	itemID: 25,
	//-	catalogItemID: 99,
	//-	name: "Coca Cola 600ml",
	//-	price: 20,
	//-	value: 6,
	//-	iva: 3,
	//-	quantity: 2,
	//-	subtotal: 40
	//-};
	//-
	//-//pasa un objeto de un tipo a otro, segun la configuraicon
	//-var detalle = fnMapObjectToNew(producto, objConfigMappingConfig);
	//-console.info("origen");
	//-console.log(producto);
	//-console.info("destino");
	//-console.log(detalle);
	//-
	//-//pasa la configuracion de una a la configuracion inversa
	//-var mappingInverse = fnMapInvertMapping(objConfigMappingConfig);
	//-console.info("origen");
	//-console.log(objConfigMappingConfig);
	//-console.info("destino");
	//-console.log(mappingInverse);	
	//-
	//-//pasa un objeto de un tipo a otro, segun la configuraicon
	//-var originalBack = fnMapObjectBack(detalle, mappingInverse);
	//-console.info("origen");
	//-console.log(detalle);
	//-console.info("destino");
	//-console.log(originalBack);


	Ext.onReady(function () {

		miVentanaEsperando = Ext.create('Ext.window.Window', {
					title: 'Procesando... .. .',
					id: 'miVentanaEsperando',
					itemId:'miVentanaEsperando',
					closable: false,
					modal: true,
					closeAction: 'hide',
					width: 300,
					height: 100,
					bodyPadding: 10,
					html: '<div style="text-align:center;"><b>Espere un momento...</b></div>'
				}); 
			
		miVentanaEsperando.show();
		
		miVentanaDePago = Ext.create('Ext.window.Window', {
					title: 'Opciones de pago',
					id: 'miVentanaDePago',
					itemId:'miVentanaDePago',
					width: 700,
					height: 350,
					modal: true,
					closeAction: 'hide',
					hidden: true,
					// Crear ventana modal														
					layout: 'vbox',
					bodyPadding: 10,			
					listeners:{
						afterrender: function(form) {
							// Configuración dinámica al "load" del contenedor
							fnConfiguracionLoad(form, objConfigInit );
						}
					},
					items: [

						// ✔ COLUMNA 1
						{
							width: 650,
							items: [	
								{
									xtype: 'numberfield',
									fieldLabel: 'Cambio',
									labelWidth: 200,
									width: 300,
									minValue: 0,
									decimalPrecision: 4,
									step: 0.0001,
									hideTrigger: true,
									keyNavEnabled: false,
									mouseWheelEnabled: false,
									readOnly: true,
									name: 'txtChangeAmount',
									id:'txtChangeAmount',
									itemId: 'txtChangeAmount',  // Mejor usar itemId
									style: 'font-weight:bold; border-width: 2px;',  // resalta el cuadro
									listeners: {
										change: fnChange_ChangeAmount
									}
									
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Monto Recibido',
									labelWidth: 200,
									width: 300,
									minValue: 0,
									decimalPrecision: 4,
									step: 0.0001,
									hideTrigger: true,
									keyNavEnabled: false,
									mouseWheelEnabled: false,
									name: 'txtReceiptAmount',
									id:'txtReceiptAmount',
									listeners: {
										change: fnChange_ReceiptAmount
									}
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Monto Recibido Extranjero',
									labelWidth: 200,
									width: 300,
									minValue: 0,
									decimalPrecision: 4,
									step: 0.0001,
									hideTrigger: true,
									keyNavEnabled: false,
									mouseWheelEnabled: false,
									name: 'txtReceiptAmountDol',
									id:'txtReceiptAmountDol',
									listeners: {
										change: fnChange_ReceiptAmount
									}
								},	
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Tarjeta nacional:',
									labelWidth: 200,
									layout: 'hbox',
									items: [
										{
											xtype: 'numberfield',
											flex: 1,
											width:95,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountTarjeta',
											id:'txtReceiptAmountTarjeta',
											listeners: {
												change: fnChange_ReceiptAmount
											}
										},
										{
											xtype: 'combobox',
											store: ['A','B','C'],
											store: Ext.create('Ext.data.Store', {
												fields: ['id', 'name'] 
											}),
											displayField: 'name',
											valueField: 'id',
											queryMode: 'local',
											editable: false,
											width: 150,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountTarjeta_BankID',
											id:'txtReceiptAmountTarjeta_BankID',
											listeners: {
												change: fnChange_ReceiptAmountTarjeta_BankID
											}
										},
										{
											xtype: 'textfield',
											emptyText: 'Referencia',
											width: 100,
											flex: 1,
											name: 'txtReceiptAmountTarjeta_Reference',
											id:'txtReceiptAmountTarjeta_Reference',
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Tarjeta extranjera:',
									labelWidth: 200,
									layout: 'hbox',
									items: [
										{
											xtype: 'numberfield',
											flex: 1,
											width:95,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountTarjetaDol',
											id:'txtReceiptAmountTarjetaDol',
											listeners: {
												change: fnChange_ReceiptAmount
											}
										},
										{
											xtype: 'combobox',
											store: ['A','B','C'],
											store: Ext.create('Ext.data.Store', {
												fields: ['id', 'name'] 
											}),
											displayField: 'name',
											valueField: 'id',
											queryMode: 'local',
											editable: false,
											width: 150,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountTarjetaDol_BankID',
											id:'txtReceiptAmountTarjetaDol_BankID',
											listeners: {
												change: fnChange_ReceiptAmountTarjeta_BankID
											}
										},
										{
											xtype: 'textfield',
											emptyText: 'Referencia',
											width: 100,
											flex: 1,
											name: 'txtReceiptAmountTarjetaDol_Reference',
											id:'txtReceiptAmountTarjetaDol_Reference',
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Transferencia nacional:',
									labelWidth: 200,
									layout: 'hbox',
									items: [
										{
											xtype: 'numberfield',
											flex: 1,
											width:95,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountBank',
											id:'txtReceiptAmountBank',
											listeners: {
												change: fnChange_ReceiptAmount
											}
										},
										{
											xtype: 'combobox',
											store: ['A','B','C'],
											store: Ext.create('Ext.data.Store', {
												fields: ['id', 'name'] 
											}),
											displayField: 'name',
											valueField: 'id',
											queryMode: 'local',
											editable: false,
											width: 150,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountBank_BankID',
											id:'txtReceiptAmountBank_BankID',
											listeners: {
												change: fnChange_ReceiptAmountTarjeta_BankID
											}
										},
										{
											xtype: 'textfield',
											emptyText: 'Referencia',
											width: 100,
											flex: 1,
											name: 'txtReceiptAmountBank_Reference',
											id:'txtReceiptAmountBank_Reference',
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Transferencia extranjera:',
									labelWidth: 200,
									layout: 'hbox',
									items: [
										{
											xtype: 'numberfield',
											flex: 1,
											width:95,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountBankDol',
											id:'txtReceiptAmountBankDol',
											listeners: {
												change: fnChange_ReceiptAmount
											}
										},
										{
											xtype: 'combobox',
											store: ['A','B','C'],
											store: Ext.create('Ext.data.Store', {
												fields: ['id', 'name'] 
											}),
											displayField: 'name',
											valueField: 'id',
											queryMode: 'local',
											editable: false,
											width: 150,
											margin: '0 5 0 0',
											name: 'txtReceiptAmountBankDol_BankID',
											id:'txtReceiptAmountBankDol_BankID',
											listeners: {
												change: fnChange_ReceiptAmountTarjeta_BankID
											}
										},
										{
											xtype: 'textfield',
											emptyText: 'Referencia',
											width: 100,
											flex: 1,
											name: 'txtReceiptAmountBankDol_Reference',
											id:'txtReceiptAmountBankDol_Reference',
										}
									]
								},
								{
									xtype: 'numberfield',
									fieldLabel: 'Puntos',
									labelWidth: 200,
									width: 300,
									minValue: 0,
									decimalPrecision: 4,
									step: 0.0001,
									hideTrigger: true,
									keyNavEnabled: false,
									mouseWheelEnabled: false,
									name: 'txtReceiptAmountPoint',
									id:'txtReceiptAmountPoint',
									listeners: {
										change: fnChange_ReceiptAmount
									}
								}
								
							]
						}
					],

					buttons: [
						{
							text: 'Confirmar',
							id: 'btnConfirmarPago',
							handler: fnBtnConfirmarPago
						},
						{
							text: 'Cancelar',
							id: 'btnCancelarPago',
							handler: fnBtnCancelarPago
						}
					]
					
																
				});
				
		miVentanaImpresion = Ext.create('Ext.window.Window', {
					title: 'Seleccione Formato de Impresión',
					id: 'miVentanaImpresion',
					itemId:'miVentanaImpresion',
					width: 350,
					height: 350,
					modal: true,
					closeAction: 'hide',
					layout: 'vbox',
					bodyPadding: 20,
					hidden: true,

					items: [
						{
							xtype: 'label',
							text: 'Elija su formato de impresión',
							style: 'font-size:16px; font-weight:bold; color:green; margin-bottom:20px;'
						},
						{
							xtype: 'container',
							layout: {
								type: 'vbox',
								pack: 'start',
								align: 'stretch'
							},
							defaults: {
								xtype: 'button',
								margin: '5 0'
							},
							items: [
								{
									text: 'Preview printer',
									id: 'btnImpresion1',
									handler: fnBtnImpresion1
								},
								{
									text: 'Impresión 2',
									id: 'btnImpresion2',
									hidden: true,
									handler: fnBtnImpresion2
								},
								{
									text: 'Impresión 3',
									id: 'btnImpresion3',
									hidden: true,
									handler: fnBtnImpresion3
								},
								{
									text: 'Impresión 4',
									id: 'btnImpresion4',
									hidden: true,
									handler: fnBtnImpresion4
								}
							]
						}
					],

					buttons: [				
						{
							text: 'Cancelar',
							iconCls: 'x-fa fa-times',
							id: 'btnCancelarImpresion',					
							handler: fnBtnCancelarImpresion
						}
					]
				});

		miVentanaSeleccionCliente = Ext.create('Ext.window.Window', {			
					title: 'Listado de Clientes',
					id: 'miVentanaSeleccionCliente',
					itemId:'miVentanaSeleccionCliente',
					width: 700,
					height: 400,
					modal: true,
					layout: 'fit',
					closeAction: 'hide',
					items: [<?php echo $objCompanyDataView_BuscarClientes["view_config"]->jsonConfiguration; ?> ],
					bbar: [
						'->',
						{
							text: 'Aceptar',
							iconCls: 'x-fa fa-check',
							id: 'btnSeleccionCliente',
							handler: fnBtnSeleccionCliente
						},
						{
							text: 'Cancelar',
							iconCls: 'x-fa fa-times',
							id: 'btnCancelarSeleccionCliente',
							handler: fnBtnCancelarSeleccionCliente
						}
					]
				});
				
		miVentanaSeleccionProducto = Ext.create('Ext.window.Window', {
					title: 'Listado de Productos',
					id: 'miVentanaSeleccionProducto',
					itemId:'miVentanaSeleccionProducto',
					width: 700,
					height: 400,
					modal: true,
					layout: 'fit',
					closeAction: 'hide',
					items: [<?php echo $objCompanyDataView_BuscarProductos["view_config"]->jsonConfiguration; ?> ],
					bbar: [
						'->',
						{
							text: 'Aceptar',
							iconCls: 'x-fa fa-check',
							id: 'btnSeleccionProducto',
							handler: fnBtnSeleccionProducto
						},
						{
							text: 'Cancelar',
							iconCls: 'x-fa fa-times',
							id: 'btnCancelarSeleccionProducto',
							handler: fnBtnCancelarSeleccionProducto
						}
					]
				});

		miVentanaSeleccionFactura = Ext.create('Ext.window.Window', {
					title: 'Listado de Facturas',
					id: 'miVentanaSeleccionFactura',
					itemId:'miVentanaSeleccionFactura',
					width: 900,
					height: 400,
					modal: true,
					layout: 'fit',
					closeAction: 'hide',
					items: [<?php echo $objCompanyDataView_BuscarFacturas["view_config"]->jsonConfiguration; ?> ],
					bbar: [
						'->',
						{
							text: 'Aceptar',
							iconCls: 'x-fa fa-check',
							id: 'btnSeleccionFactura',
							handler: fnBtnSeleccionFactura
						},
						{
							text: 'Cancelar',
							iconCls: 'x-fa fa-times',
							id: 'btnCancelarSeleccionFactura',
							handler: fnBtnCancelarSeleccionFactura
						}
					]
				});
				
		
		miVentanaInformacionAdicional = Ext.create('Ext.window.Window', {
					title: 'Informacion adicional',
					id: 'miVentanaInformacionAdicional',
					itemId:'miVentanaInformacionAdicional',
					width: 350,
					height: 250,
					modal: true,
					closeAction: 'hide',
					hidden: true,
					// Crear ventana modal														
					layout: 'vbox',
					bodyPadding: 10,			
					listeners:{
						afterrender: function(form) {
							// Configuración dinámica al "load" del contenedor
							fnConfiguracionLoad(form, objConfigInit );
						}
					},
					items: [

						// ✔ COLUMNA 1
						{
							width: 350,
							items: [	
								{
									xtype: 'hiddenfield',
									name: 'ventanaInformacionAdicional_indexTransctionMasterDetail',
									id:'ventanaInformacionAdicional_indexTransctionMasterDetail',
									itemId:'ventanaInformacionAdicional_indexTransctionMasterDetail',
									value: '12345'
								},
								{
									xtype: 'combobox',
									fieldLabel: 'Precios',
									labelWidth: 100,
									width: 300,									
									store: Ext.create('Ext.data.Store', {
										fields: ['id', 'name'] 
									}),
									displayField: 'name',
									valueField: 'id',
									queryMode: 'local',
									editable: false,
									name: 'txtSelectPrecio',
									id:'txtSelectPrecio',
									itemId:'txtSelectPrecio',
								},
								{
									xtype: 'combobox',
									fieldLabel: 'Vendedor',
									labelWidth: 100,
									width: 300,									
									store: Ext.create('Ext.data.Store', {
										fields: ['id', 'name'] 
									}),
									displayField: 'name',
									valueField: 'id',
									queryMode: 'local',
									editable: false,
									name: 'txtSelectVendedor',
									id:'txtSelectVendedor',
									itemId:'txtSelectVendedor',
								},
								{
									xtype: 'textfield',       // tipo texto
									fieldLabel: 'Serie', // etiqueta
									labelWidth: 100,          // ancho de la etiqueta
									width: 300,               // ancho total del campo
									name: 'txtSerieProducto',     // nombre del campo para enviar al servidor
									id: 'txtSerieProducto',       // id único
									itemId:'txtSerieProducto',
								},
								{
									xtype: 'textfield',       // tipo texto
									fieldLabel: 'Referencia', // etiqueta
									labelWidth: 100,          // ancho de la etiqueta
									width: 300,               // ancho total del campo
									name: 'txtReferenciaProducto',     // nombre del campo para enviar al servidor
									id: 'txtReferenciaProducto',       // id único
									itemId:'txtReferenciaProducto',
								}
								
							]
						}
					],

					buttons: [
						{
							text: 'Aceptar',
							id: 'btnConfirmarInformacionAdicional',
							handler: fnBtnConfirmarInformacionAdicional
						},
						{
							text: 'Cancelar',
							id: 'btnCancelarInformacionAdicional',
							handler: fnBtnCancelarInformacionAdicional
						}
					]
					
			});


		miVentanaPrincipal = Ext.create('Ext.container.Viewport', {
			layout: 'fit',
			id: 'miVentanaPrincipal',
			itemId: 'miVentanaPrincipal',
			listeners:{
				afterrender: function(form) {
					// Configuración dinámica al "load" del contenedor
					indexDBCreate(false);
					fnConfiguracionLoad(form, objConfigInit );	
					
					//mandar a cargar la factura cuando es en modo edicion
					var transactionMasterID 	= '<?php echo $transactionMasterID ?>'
					var codigoMesero 			= '<?php echo $codigoMesero ?>';
		
					
					if(transactionMasterID > 0){
						fnLoadInvoiceExistente(transactionMasterID,codigoMesero);
					}
					else
					{
						isLoading = false;
						miVentanaEsperando.hide();
					}
					
				}
			},
			items: [
				{
					xtype: 'panel',
					title: 'Facturación',
					layout: 'border',

					tbar: [
						{
							text: 'Nueva factura',
							iconCls: 'x-btn-save',
							cls: 'btn-nueva',
							id: 'btnNuevaFactura',
							handler: fnBtnNuevaFactura
							
						},
						{
							text: 'Guardar factura',
							iconCls: 'x-fa fa-save',
							cls: 'btn-guardar',
							id: 'btnGuardarFactura',
							handler: fnBtnGuardarFactura
						},
						{
							text: 'Eliminar factura',
							iconCls: 'x-fa fa-trash',
							cls: 'btn-eliminar',
							id: 'btnEliminarFactura',
							handler: fnBtnEliminarFactura
						},
						{
							text: 'Imprimir factura',
							iconCls: 'x-fa fa-trash',
							cls: 'btn-eliminar',
							id: 'btnImprimirFactura',
							handler: fnBtnImprimirFactura
						},
						
						'->',  // 🔥 ENVÍA TODO LO QUE SIGUE A LA DERECHA
						{
							xtype: 'label',
							text: 'FAC00123', // aquí puedes poner dinámicamente el número
							name: 'txtTM_transactionNumber',
							id:'txtTM_transactionNumber',
							style: 'color:green; font-weight:bold; margin-right:20px;'
						},
						{
							xtype: 'button',
							text: 'Herramienta',
							iconCls: 'x-fa fa-refresh',
							cls: 'btn-actualizar',

							menu: [   // 🔽 DROPDOWN BUTTON
								{
									text: 'Actualizar',
									iconCls: 'x-fa fa-database',
									id: 'btnActualizarPantalla',
									handler: fnBtnActualizarPantalla
								},
								{
									text: 'Actualizar catalogo',
									iconCls: 'x-fa fa-database',
									id: 'btnActualizarCatalogo',
									handler: fnBtnActualizarCatalogo
								},
								{
									text: 'Crear bd local',
									iconCls: 'x-fa fa-database',
									id: 'btnCrearBDLocal',
									handler: fnBtnCrearBDLocal
								},
								{
									text: 'Seleccionar factura',
									iconCls: 'x-fa fa-database',
									id: 'btnSeleccionarFactura',
									handler: fnBtnSeleccionarFactura
								},
								{
									text: 'Nuevo producto',
									iconCls: 'x-fa fa-database',
									id: 'btnNuevoProducto',
									handler: fnBtnNuevoProducto
								},
								'-',
								{
									text: 'Regresar',
									iconCls: 'x-fa fa-retweet',
									id: 'btnRegresar',
									handler: fnBtnRegresar
								}
							]
						}
						
						
						
					],

					items: [
						{
							xtype: 'tabpanel',
							region: 'center',
							activeTab: 4,  // 🔥 Tab que estará activo por defecto (0 = primer tab)

							items: [

								// TAB 1 - Datos Generales
								{
									title: 'Datos de factura',
									bodyPadding: 15,
									layout: 'hbox',   // 🔥 Dos columnas horizontales
									defaults: {
										xtype: 'container',
										layout: 'vbox',
										margin: '0 20 0 0'  // separación entre columnas
									},
									items: [

										// ✔ COLUMNA 1
										{
											width: 350,
											items: [
												{
													xtype: 'hiddenfield',
													name: 'txtUserID',
													id:'txtUserID',
													value: '12345'
												},
												{
													xtype: 'hiddenfield',
													name: 'txtCompanyID',
													id:'txtCompanyID',
													value: '12345'
												},
												{
													xtype: 'hiddenfield',
													name: 'txtTransactionID',
													id:'txtTransactionID',
													value: '12345'
												},
												{
													xtype: 'hiddenfield',
													name: 'txtTransactionMasterID',
													id:'txtTransactionMasterID',
													value: '12345'
												},
												{
													xtype: 'hiddenfield',
													name: 'txtCodigoMesero',
													id:'txtCodigoMesero',
													value: '12345'
												},
												{
													xtype: 'hiddenfield',
													name: 'txtStatusID',
													id:'txtStatusID',
													value: '12345'
												},
												{
													xtype: 'hiddenfield',
													name: 'txtStatusIDOld',
													id:'txtStatusIDOld',
													value: '12345'
												},
												{
													xtype: 'datefield',
													fieldLabel: 'Fecha',
													labelWidth: 100,
													width: 300,
													format: 'Y-m-d',
													name: 'txtDate',
													id:'txtDate',
												},
												{
													xtype: 'numberfield',
													fieldLabel: 'Tipo de Cambio',
													labelWidth: 100,
													width: 300,
													minValue: 0,
													decimalPrecision: 4,
													step: 0.0001,
													hideTrigger: true,
													keyNavEnabled: false,
													mouseWheelEnabled: false,
													name: 'txtExchangeRate',
													id:'txtExchangeRate',
												},												
												{
													xtype: 'textfield',
													fieldLabel: 'Comentario',
													labelWidth: 100,
													width: 300,
													name: 'txtNote',
													id:'txtNote',
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Moneda',
													labelWidth: 100,
													width: 300,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtCurrencyID',
													id:'txtCurrencyID',
													listeners: {
														change: fnChange_CurrencyID_CreditLineID_WarehouseID
													}
												}
											]
										},

										// ✔ COLUMNA 2
										{
											width: 480,
											items: [
												{
													xtype: 'fieldcontainer',
													fieldLabel: 'Cliente',
													labelWidth: 100,
													layout: 'hbox',
													width: 450,

													items: [
														{
															xtype: 'hiddenfield',
															name: 'txtCustomerID',
															value: '12345',
															id:'txtCustomerID',
														},
														{
															xtype: 'textfield',															
															readOnly: true,
															width: 195,
															emptyText: 'Seleccione un cliente...',
															name: 'txtCustomerDescription',
															id:'txtCustomerDescription',
														},
														{
															xtype: 'button',
															text: 'Limpiar',
															iconCls: 'x-fa fa-eraser',
															cls:'btn-limpiar',
															margin: '0 5',
															id: 'btnLimpiarCliente',
															handler: fnBtnLimpiarCliente
														},
														{
															xtype: 'button',
															text: 'Buscar',
															iconCls: 'x-fa fa-search',
															id: 'btnBuscarCliente',															
															handler: fnBtnBuscarCliente
														}
													]
												},
												{
													xtype: 'textfield',
													fieldLabel: 'Cliente',
													labelWidth: 100,
													width: 300,
													name: 'txtReferenceClientName',
													id:'txtReferenceClientName',
												},
												{
													xtype: 'textfield',
													fieldLabel: 'Cedula',
													labelWidth: 100,
													width: 300,
													name: 'txtReferenceClientIdentifier',
													id:'txtReferenceClientIdentifier',
												},	
												{
													xtype: 'combobox',
													fieldLabel: 'Tipo',
													labelWidth: 100,
													width: 300,
													store: ['Contado', 'Credito'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name:'txtCausalID',
													id:'txtCausalID',
													listeners: {
														change: fnChange_CausalID
													}
												},	
												{
													xtype: 'combobox',
													fieldLabel: 'Linea de credito',
													labelWidth: 100,
													width: 300,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtCustomerCreditLineID',
													id: 'txtCustomerCreditLineID',
													listeners: {
														change: fnChange_CurrencyID_CreditLineID_WarehouseID
													}
												}
												
											]
										}

									]

								},
								
								// TAB 2 - Referencias
								{
									title: 'Referencias',
									bodyPadding: 15,
									layout: 'hbox',   // 🔥 Dos columnas horizontales
									defaults: {
										xtype: 'container',
										layout: 'vbox',
										margin: '0 20 0 0'  // separación entre columnas
									},
									items: [

										// ✔ COLUMNA 1
										{
											width: 350,
											items: [
												{
													xtype: 'combobox',
													fieldLabel: 'Zona',
													labelWidth: 100,
													width: 300,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtZoneID',
													id:'txtZoneID',
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Precio',
													labelWidth: 100,
													width: 300,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtTypePriceID',
													id:'txtTypePriceID',
													listeners: {
														change: fnChangeTypePreiceID
													}
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Bodega',
													labelWidth: 100,
													width: 300,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtWarehouseID',
													id:'txtWarehouseID',
													listeners: {
														change: fnChange_CurrencyID_CreditLineID_WarehouseID
													}
												}
											]
										},

										// ✔ COLUMNA 2
										{
											width: 480,
											items: [
												{
													xtype: 'textfield',
													fieldLabel: 'Referencia',
													labelWidth: 100,
													width: 300,
													name: 'txtReference3',
													id:'txtReference3',
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Vendedor',
													labelWidth: 100,
													width: 300,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtEmployeeID',
													id:'txtEmployeeID',
												},
												{
													xtype: 'textfield',
													fieldLabel: 'Telefono',
													labelWidth: 100,
													width: 300,
													name: 'txtNumberPhone',
													id:'txtNumberPhone',
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Mesa',
													labelWidth: 100,
													width: 300,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtMesaID',
													id:'txtMesaID',
												},
												{
													xtype: 'datefield',
													format: 'Y-m-d',
													fieldLabel: 'Siguiente visita',
													labelWidth: 100,
													width: 300,
													name: 'txtNextVisit',
													id:'txtNextVisit',
												}
												
											]
										}

									]
								},
								
								// TAB 3 - Informacion de credito
								{
									title: 'Informacion de credito',
									bodyPadding: 15,
									layout: 'hbox',   // 🔥 Dos columnas horizontales
									defaults: {
										xtype: 'container',
										layout: 'vbox',
										margin: '0 20 0 0'  // separación entre columnas
									},
									items: [

										// ✔ COLUMNA 1
										{
											width: 500,
											items: [
												{
													xtype: 'datefield',
													format: 'Y-m-d',
													fieldLabel: 'Primer pago',
													labelWidth: 200,
													width: 400,
													name: 'txtDateFirst',
													id:'txtDateFirst',
												},
												{
													xtype: 'numberfield',
													fieldLabel: 'Plazo',
													labelWidth: 200,
													width: 400,
													minValue: 0,
													decimalPrecision: 4,
													step: 0.0001,
													hideTrigger: true,
													keyNavEnabled: false,
													mouseWheelEnabled: false,
													name: 'txtReference2',
													id:'txtReference2',
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Frecuencia',
													labelWidth: 200,
													width: 400,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name : 'txtPeriodPay',
													id:'txtPeriodPay',
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Proveedor de credito',
													labelWidth: 200,
													width: 400,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtReference1',
													id:'txtReference1',
												},
												{
													xtype: 'combobox',
													fieldLabel: 'Dias excluidos',
													labelWidth: 200,
													width: 400,
													store: ['USD', 'NIO', 'CRC', 'EUR'],
													store: Ext.create('Ext.data.Store', {
														fields: ['id', 'name'] 
													}),
													displayField: 'name',
													valueField: 'id',
													queryMode: 'local',
													editable: false,
													name: 'txtDayExcluded',
													id:'txtDayExcluded',
												}
											]
										},

										// ✔ COLUMNA 2
										{
											width: 450,
											items: [
												{
													xtype: 'numberfield',
													fieldLabel: '% de Interes',
													labelWidth: 200,
													width: 400,
													minValue: 0,
													decimalPrecision: 4,
													step: 0.0001,
													hideTrigger: true,
													keyNavEnabled: false,
													mouseWheelEnabled: false,
													name: 'txtFixedExpenses',
													id:'txtFixedExpenses',
												},												
												{
													xtype: 'fieldcontainer',
													fieldLabel: 'Aplica exoneracion',
													labelWidth: 300,
													layout: 'hbox',
													defaults: {
														margin: '0 10 0 0'
													},
													items: [
														{
															xtype: 'radiofield',
															boxLabel: 'Si',
															name: 'txtCheckApplyExoneracion',
															inputValue: '1',
															checked: false,
															listeners: {
																change: fnChange_ApplyExoneration
															}														
														},
														{
															xtype: 'radiofield',
															boxLabel: 'No',
															name: 'txtCheckApplyExoneracion',
															inputValue: '0',
															checked: true,
															listeners: {
																change: fnChange_ApplyExoneration
															}
														}
													]
												},
												{
													xtype: 'textfield',
													fieldLabel: 'Codigo de exoneracion:',
													labelWidth: 200,
													width: 400,
													name: 'txtLayFirstLineProtocolo',
													id:'txtLayFirstLineProtocolo',
													listeners: {
														change: fnChange_FirstLineProtocolo
													}
												},
												{
													xtype: 'fieldcontainer',
													fieldLabel: 'Desembolso en efectivo',
													labelWidth: 300,
													layout: 'hbox',
													defaults: {
														margin: '0 10 0 0'
													},
													items: [
														{
															xtype: 'radiofield',
															boxLabel: 'Si',
															name: 'txtCheckDeEfectivo',
															inputValue: '1',
															checked: false
														},
														{
															xtype: 'radiofield',
															boxLabel: 'No',
															name: 'txtCheckDeEfectivo',
															inputValue: '0',
															checked: true
														}
													]
												},
												{
													xtype: 'fieldcontainer',
													fieldLabel: 'Reportar a sin riesgo',
													labelWidth: 300,
													layout: 'hbox',
													defaults: {
														margin: '0 10 0 0'
													},
													items: [
														{
															xtype: 'radiofield',
															boxLabel: 'Si',
															name: 'txtCheckReportSinRiesgoValue',
															inputValue: '1',
															checked: false
														},
														{
															xtype: 'radiofield',
															boxLabel: 'No',
															name: 'txtCheckReportSinRiesgoValue',
															inputValue: '0',
															checked: true
														}
													]
												},
											]
										}

									]
								},
								
								// TAB 4 - Notas
								{
									title: 'Notas',
									bodyPadding: 15,
									layout: 'hbox',   // 🔥 Dos columnas horizontales
									defaults: {
										xtype: 'container',
										layout: 'vbox',
										margin: '0 20 0 0'  // separación entre columnas
									},
									items: [

										// ✔ COLUMNA 1
										{
											width: 500,
											items: [
												{
													xtype: 'textarea',
													fieldLabel: 'Nota',
													labelWidth: 100,
													width: 500,
													height: 120,          // altura para varias líneas
													grow: true,           // opcional: se ajusta automáticamente al contenido
													growMax: 300,         // altura máxima si grow=true
													emptyText: 'Ingrese aquí la nota o comentario...',
													allowBlank: true  ,    // si es opcional ,
													name : 'txtTMIReference1',
													id:'txtTMIReference1',
												}
											]
										},

										

									]
								},


								// TAB 5 - Detalle + Resumen
								{
									title: 'Detalle',
									layout: 'hbox',
									padding: 10,
									items: [

										// Columna izquierda - Tabla
										{
											xtype: 'grid',
											flex: 2,
											itemId: 'gridDetailTransactionMaster',
											title: 'Detalle de Productos',
											margin: '0 10 0 0',		
											selModel: 'rowmodel', // permite seleccionar filas para eliminar	
											maxHeight: 40 * 10,       // altura aproximada para 15 filas
											scrollable: true,     // permite scroll interno al grid
											bodyBorder: true,
											frame: true,
	
											tbar: [
												{
													text: 'Agregar producto',
													iconCls: 'x-fa fa-plus',
													handler: fnBtnNuevoProductoDetail
												},
												{
													text: 'Eliminar producto',
													iconCls: 'x-fa fa-trash',
													handler: fnBtnEliminarProductoDetail 
												},
												
												'->', // 🔥 empuja el siguiente campo a la derecha
												{
													xtype: 'textfield',
													fieldLabel: 'Escanear',
													labelAlign: 'right',
													width: 350,
													id:'txtScanerCodigo',
													name:'txtScanerCodigo',
													itemId:'txtScanerCodigo',
													enableKeyEvents: true,
													listeners: {
														afterrender: function(field) {
															// 🔥 Le da foco automáticamente al campo
															field.focus(false, 200); // 200ms delay opcional
														},
														keypress: fnBtnEnterScaner 
													}
												}
												
											],
											store: {
												fields: [
													// 0
													'txtTMD_checked',

													// 1
													'txtTMD_txtTransactionMasterDetailID',

													// 2
													'txtTMD_txtItemID',

													// 3
													'txtTMD_txtTransactionDetailItemNumber',

													// 4
													'txtTMD_txtTransactionDetailName',

													// 5
													'txtTMD_txtSku',

													// 6
													'txtTMD_txtQuantity',

													// 7
													'txtTMD_txtPrice',

													// 8
													'txtTMD_txtSubTotal',

													// 9
													'txtTMD_txtIva',

													// 10
													'txtTMD_skuQuantityBySku',

													// 11
													'txtTMD_unitaryPriceInvidual',

													// 12 (no tiene dataIndex: botón +)

													// 13 (no tiene dataIndex: botón -)

													// 14 (no tiene dataIndex: botón info)

													// 15
													'txtTMD_skuFormatoDescription',

													// 16
													'txtTMD_txtItemPrecio2',

													// 17
													'txtTMD_txtItemPrecio3',

													// 18
													'txtTMD_txtTransactionDetailNameDescription',

													// 19
													'txtTMD_txtTaxServices',

													// 20
													'txtTMD_txtDetailLote',

													// 21
													'txtTMD_txtInfoVendedor',

													// 22
													'txtTMD_txtInfoSerie',

													// 23
													'txtTMD_txtInfoReferencia',

													// 24
													'txtTMD_txtItemPrecio1',

													// 25
													'txtTMD_txtCatalogItemIDSku',

													// 26
													'txtTMD_txtRatioSku',

													// 27
													'txtTMD_txtDiscountByItem',

													// 28
													'txtTMD_txtCommisionByBankByItem'
												],
												data: 	[]
											},
											columns: [
											
												// ==========================
												// 0 CHECKBOX
												// ==========================
												{
													xtype: 'checkcolumn',
													text: '',
													dataIndex: 'txtTMD_checked',
													width: 50,
													stopSelection: false
												},

												
												// ==========================
												// 1 transactionMasterDetailID (hidden)
												// ==========================
												{
													text: 'txtTransactionMasterDetailID',
													dataIndex: 'txtTMD_txtTransactionMasterDetailID',
													width: 120,
													hidden: true
												},
												
												
												// ==========================
												// 2 itemID (hidden)
												// ==========================
												{
													text: 'txtItemID',
													dataIndex: 'txtTMD_txtItemID',
													width: 120,
													hidden: true
												},

												
												// ==========================
												// 3 itemNumber
												// ==========================
												{
													text: 'Código',
													dataIndex: 'txtTMD_txtTransactionDetailItemNumber',
													width: 120,
													align: 'left',
													tdCls: 'columna-pastel'
												},
												
												// ==========================
												// 4 itemNumber
												// ==========================
												{
													text: 'Descripcion',
													dataIndex: 'txtTMD_txtTransactionDetailName',													
													flex: 1,   // ocupa todo el espacio disponible proporcionalmente
													align: 'left'
												},	
												// ==========================
												// 5 U/M
												// ==========================																								
												{
													text: 'U/M',
													dataIndex: 'txtTMD_txtSku',
													width: 150,
													xtype: 'widgetcolumn',
													widget: {
														xtype: 'combo',														
														store: Ext.create('Ext.data.Store', {
															fields: ['id', 'name'] ,
															data: [ ] 
														}),
														displayField: 'name',
														valueField: 'id',
														queryMode: 'local',
														editable: false,    // no permite escribir, solo seleccionar
														forceSelection: true,
														listeners: {
															select: function(combo, record) {
																
																if (isLoading) return; // Detener evento
																
																// Actualizar el store del grid con el valor seleccionado
																var gridRecord = combo.getWidgetRecord();
																gridRecord.set('txtTMD_txtSku', combo.getValue());
															}
														}
													}
												},
												// ==========================
												// 6 Cantidad
												// ==========================												
												{
													text: 'Cantidad',
													dataIndex: 'txtTMD_txtQuantity',
													width: 120,
													align: 'right',
													xtype: 'widgetcolumn',
													widget: {
														xtype: 'numberfield',
														minValue: 0.01,   // valores mayores a 0
														allowBlank: false,
														decimalPrecision: 2,  // dos decimales
														hideTrigger: true,    // oculta los botones de subir/bajar
														enableKeyEvents: true,
														listeners: {
															change: function(field, newValue) {
																
																if (isLoading) return; // Detener evento
																
																// Aquí podés manejar el cambio y actualizar el store
																var record = field.getWidgetRecord();
																record.set('txtTMD_txtQuantity', newValue);
																
																
																var grid 		= field.up('grid');          // obtiene el grid
																var store 		= grid.getStore();          // obtiene el store
																var rowIndex 	= store.indexOf(record); // índice del record en el store

																fnRecalculateDetail(true,"", rowIndex);
															}
														}
													}
												},
												// ==========================
												// 7 Precio
												// ==========================										
												{
													text: 'Precio',
													dataIndex: 'txtTMD_txtPrice',
													width: 120,
													align: 'right',
													xtype: 'widgetcolumn',
													widget: {
														xtype: 'numberfield',
														minValue: 0.01,   // valores mayores a 0
														allowBlank: false,
														decimalPrecision: 2,  // dos decimales
														hideTrigger: true,    // oculta los botones de subir/bajar
														enableKeyEvents: true,
														listeners: {
															change: function(field, newValue) {
																
																if (isLoading) return; // Detener evento
																
																// Aquí podés manejar el cambio y actualizar el store
																var record = field.getWidgetRecord();
																
																if (record) {  // se asegura que record NO sea undefined ni null
																	record.set('txtTMD_txtPrice', newValue);
																	
																			
																	
																	var grid 		= field.up('grid');          // obtiene el grid
																	var store 		= grid.getStore();          // obtiene el store
																	var rowIndex 	= store.indexOf(record); // índice del record en el store

																	fnRecalculateDetail(true,"", rowIndex);
																	
																}
																
															}
														}
													}
												},
												
												// ==========================
												// 8 Total
												// ==========================	
												{
													text: 'Total',
													dataIndex: 'txtTMD_txtSubTotal',
													width: 120,
													align: 'right',
												},												
												// ==========================
												// 9 Iva
												// ==========================
												{
													text: 'txtIva',
													dataIndex: 'txtTMD_txtIva',
													width: 120,
													hidden: true
												},
												// ==========================
												// 10 Cantidad por SKU  Cantidad en unidades por cada sku es decir 1 paquete = 25 unidades
												// ==========================
												{
													text: 'skuQuantityBySku',
													dataIndex: 'txtTMD_skuQuantityBySku',
													width: 120,
													hidden: true
												},
												// ==========================
												// 11 Precio unitario individual
												// ==========================
												{
													text: 'unitaryPriceInvidual',
													dataIndex: 'txtTMD_unitaryPriceInvidual',
													width: 120,
													hidden: true
												},
												// ==========================
												// 12 Acciones
												// ==========================													
												// Botón +
												{
													text: '+',
													xtype: 'widgetcolumn',
													width: 50,
													align: 'center',
													widget: {
														xtype: 'button',
														scale: 'small',
														text: '+',
														tooltip: 'Incrementar',
														handler: function(btn) {
															var record = btn.getWidgetRecord();
															var current = record.get('txtTMD_txtQuantity');
															record.set('txtTMD_txtQuantity', current + 1);
														}
													}
												},

												// Botón -
												{
													text: '-',
													xtype: 'widgetcolumn',
													width: 50,
													align: 'center',
													widget: {
														xtype: 'button',
														scale: 'small',
														text: '-',
														tooltip: 'Disminuir',
														handler: function(btn) {
															var record = btn.getWidgetRecord();
															var current = record.get('txtTMD_txtQuantity');
															if (current > 0) {
																record.set('txtTMD_txtQuantity', current - 1);
															}
														}
													}
												},

												// Botón info
												{
													text: 'Info',
													xtype: 'widgetcolumn',
													width: 50,
													align: 'center',
													widget: {
														xtype: 'button',
														scale: 'small',
														text: 'i',
														tooltip: 'Información',
														handler: function(btn) {
															
															var record 									= btn.getWidgetRecord();
															miVentanaInformacionAdicional.currentRecord = record;
	
															//Cargar Id
															miVentanaInformacionAdicional.down("#ventanaInformacionAdicional_indexTransctionMasterDetail").setValue(record.get('txtTMD_txtTransactionMasterDetailID'));															
																														
															//Cargar Lista de precios
															let storeData = [];
															if(record.get('txtTMD_txtPrice') > 0 )
															{
																storeData.push({id: record.get('txtTMD_txtPrice'), name: record.get('txtTMD_txtPrice') });	
															}
															storeData.push({id: record.get('txtTMD_txtItemPrecio1'), name: record.get('txtTMD_txtItemPrecio1') });
															storeData.push({id: record.get('txtTMD_txtItemPrecio2'), name: record.get('txtTMD_txtItemPrecio2') });
															storeData.push({id: record.get('txtTMD_txtItemPrecio3'), name: record.get('txtTMD_txtItemPrecio3') });															
															miVentanaInformacionAdicional.down("#txtSelectPrecio").getStore().removeAll();
															miVentanaInformacionAdicional.down("#txtSelectPrecio").getStore().loadData(storeData);
															miVentanaInformacionAdicional.down("#txtSelectPrecio").setValue(  record.get('txtTMD_txtPrice')  );
															
															//Obtener lista de vendedores
															let storeDataV2 	= [];
															storeDataV2 		= objConfigInit.comboStores.txtEmployeeID;
															miVentanaInformacionAdicional.down("#txtSelectVendedor").getStore().removeAll();
															miVentanaInformacionAdicional.down("#txtSelectVendedor").getStore().loadData(storeDataV2);
															miVentanaInformacionAdicional.down("#txtSelectVendedor").setValue(record.get('txtTMD_txtInfoVendedor'));
															
															//Cargar Serie
															miVentanaInformacionAdicional.down("#txtSerieProducto").setValue(record.get('txtTMD_txtInfoSerie'));															
															
															//Cargar Referencia
															miVentanaInformacionAdicional.down("#txtReferenciaProducto").setValue(record.get('txtTMD_txtInfoReferencia'));																														
															miVentanaInformacionAdicional.show();
														}
													}
												},
												// ==========================
												// 13 Descripcion de la sku
												// ==========================
												{
													text: 'skuFormatoDescription',
													dataIndex: 'txtTMD_skuFormatoDescription',
													width: 120,
													hidden: true
												},
												// ==========================
												// 14 Precio 2
												// ==========================
												{
													text: 'txtItemPrecio2',
													dataIndex: 'txtTMD_txtItemPrecio2',
													width: 120,
													hidden: true
												},
												// ==========================
												// 15 Precio 3
												// ==========================
												{
													text: 'txtItemPrecio3',
													dataIndex: 'txtTMD_txtItemPrecio3',
													width: 120,
													hidden: true
												},
												// ==========================
												// 16 Nombre descripcion
												// ==========================
												{
													text: 'txtTransactionDetailNameDescription',
													dataIndex: 'txtTMD_txtTransactionDetailNameDescription',
													width: 120,
													hidden: true
												},
												// ==========================
												// 17 Impuesto por servicio
												// ==========================
												{
													text: 'txtTaxServices',
													dataIndex: 'txtTMD_txtTaxServices',
													width: 120,
													hidden: true
												},
												// ==========================
												// 18 Peso o Lote
												// ==========================
												{
													text: 'txtDetailLote',
													dataIndex: 'txtTMD_txtDetailLote',
													width: 120,
													hidden: true
												},
												// ==========================
												// 19 Vendedor 
												// ==========================
												{
													text: 'txtInfoVendedor',
													dataIndex: 'txtTMD_txtInfoVendedor',
													width: 120,
													hidden: true
												},
												// ==========================
												// 20 Serie
												// ==========================
												{
													text: 'txtInfoSerie',
													dataIndex: 'txtTMD_txtInfoSerie',
													width: 120,
													hidden: true
												},
												// ==========================
												// 21 Referencia
												// ==========================
												{
													text: 'txtInfoReferencia',
													dataIndex: 'txtTMD_txtInfoReferencia',
													width: 120,
													hidden: true
												},
												// ==========================
												// 22 Precio 1
												// ==========================
												{
													text: 'txtItemPrecio1',
													dataIndex: 'txtTMD_txtItemPrecio1',
													width: 120,
													hidden: true
												},
												// ==========================
												// 23 catalogItemID sku
												// ==========================
												{
													text: 'txtCatalogItemIDSku',
													dataIndex: 'txtTMD_txtCatalogItemIDSku',
													width: 120,
													hidden: true
												},
												// ==========================
												// 24 ratio sku
												// ==========================
												{
													text: 'txtRatioSku',
													dataIndex: 'txtTMD_txtRatioSku',
													width: 120,
													hidden: true
												},
												// ==========================
												// 25 descuento
												// ==========================
												{
													text: 'txtDiscountByItem',
													dataIndex: 'txtTMD_txtDiscountByItem',
													width: 120,
													hidden: true
												},
												// ==========================
												// 26 Comision por banco
												// ==========================
												{
													text: 'txtCommisionByBankByItem',
													dataIndex: 'txtTMD_txtCommisionByBankByItem',
													width: 120,
													hidden: true
												},
												
											]										
											
										},

										// Columna derecha - Resumen
										{
											xtype: 'panel',
											title: 'Resumen',
											flex: 1,
											bodyPadding: 15,
											layout: 'vbox',
											defaults: { xtype: 'numberfield', labelWidth: 100, width: 250 },

											items: [
												{ fieldLabel: 'Subtotal', value: 0, readOnly: true , name: 'txtSubTotal' , id:'txtSubTotal',},
												{ fieldLabel: 'Impuesto', value: 0, readOnly: true , name: 'txtIva' ,id:'txtIva', },
												{ 
													fieldLabel: '% Descuento', 
													value: 0 , 
													name: 'txtPorcentajeDescuento' , 
													id:'txtPorcentajeDescuento', 
													listeners: {
														change: fnChange_PorcentageDescuento
													}
												},
												{ fieldLabel: 'Descuento', value: 0 , name: 'txtDescuento' , id:'txtDescuento', },
												{ fieldLabel: '% Servicio', value: 0 , name: 'txtServices' , id:'txtServices',},
												{ fieldLabel: 'Total', value: 0, readOnly: true , name: 'txtTotal' , id:'txtTotal', },
												{
													xtype: 'button',
													text: 'Pagar',
													margin: '20 0 0 0',
													id: 'btnPagar',
													handler: fnBtnPagar
												}
											]
										}
									]
								},

								
							]
						}
					]
				}
			]
		});

	
		Ext.getDoc().on('keydown', function(e) {
		
			// F12 para imprimir factura
			if (e.getKey() === Ext.event.Event.F12) {
				e.preventDefault(); // evita la acción por defecto del navegador
				fnBtnImpresion1();
			}

			// F2 para ir a un input scanear
			if (e.getKey() === Ext.event.Event.F2) {
				e.preventDefault();
				var input = Ext.getCmp('txtScanerCodigo'); // el id de tu campo
				if (input) {
					input.focus(false, 200);
				}
			}
			
			// F3 para guardar
			if (e.getKey() === Ext.event.Event.F3) {
				e.preventDefault();
				fnEnviarFactura("guardar");
			}
			
			// F4 para aplicar
			if (e.getKey() === Ext.event.Event.F4) {
				e.preventDefault();
				fnEnviarFactura("aplicar");
			}
			
			// F5 seleccion de productos
			if (e.getKey() === Ext.event.Event.F5) {
				e.preventDefault();
				miVentanaSeleccionProducto.show();
			}
			
			// F6 abrir opcion de pago
			if (e.getKey() === Ext.event.Event.F6) {
				e.preventDefault();
				
				miVentanaDePago.show();	
				var input = Ext.getCmp('txtReceiptAmount'); // el id de tu campo
				if (input) {
					input.focus(false, 200);
				}
			}
			
			// F1 para ayuda
			if (e.getKey() === Ext.event.Event.F1) {
				e.preventDefault(); // evita la acción por defecto del navegador
				win = Ext.create('Ext.window.Window', {
					id: 'winAyudaAtajos',
					title: '📘 Manual de Accesos Rápidos',
					width: 550,
					height: 420,
					modal: true,
					layout: 'fit',
					resizable: false,
					bodyPadding: 15,
					closeAction: 'hide',

					items: [{
						xtype: 'component',
						autoScroll: true,
						html: `
							<div style="font-family: Arial; font-size:14px;">
								<h2 style="color:#1a73e8; margin-top:0;">
									⌨ Accesos Rápidos del Sistema
								</h2>

								<p style="margin-bottom:15px;">
									Utiliza las siguientes combinaciones de teclas para trabajar
									de forma más rápida y eficiente:
								</p>

								<table style="width:100%; border-collapse:collapse;">
									<tr style="background:#f5f5f5;">
										<th style="padding:8px; border:1px solid #ddd;">Tecla</th>
										<th style="padding:8px; border:1px solid #ddd;">Acción</th>
									</tr>

									<tr>
										<td style="padding:8px; border:1px solid #ddd; font-weight:bold;">F1</td>
										<td style="padding:8px; border:1px solid #ddd;">Abrir este manual de ayuda</td>
									</tr>

									<tr>
										<td style="padding:8px; border:1px solid #ddd; font-weight:bold;">F2</td>
										<td style="padding:8px; border:1px solid #ddd;">Activar escáner</td>
									</tr>

									<tr>
										<td style="padding:8px; border:1px solid #ddd; font-weight:bold;">F3</td>
										<td style="padding:8px; border:1px solid #ddd;">Guardar información</td>
									</tr>

									<tr>
										<td style="padding:8px; border:1px solid #ddd; font-weight:bold;">F4</td>
										<td style="padding:8px; border:1px solid #ddd;">Aplicar cambios</td>
									</tr>
									
									<tr>
										<td style="padding:8px; border:1px solid #ddd; font-weight:bold;">F5</td>
										<td style="padding:8px; border:1px solid #ddd;">Abrir seleccion de producto</td>
									</tr>
									
									<tr>
										<td style="padding:8px; border:1px solid #ddd; font-weight:bold;">F6</td>
										<td style="padding:8px; border:1px solid #ddd;">Abrir opciones de pago</td>
									</tr>

									<tr>
										<td style="padding:8px; border:1px solid #ddd; font-weight:bold;">F12</td>
										<td style="padding:8px; border:1px solid #ddd;">Imprimir factura</td>
									</tr>
								</table>

								<p style="margin-top:15px; color:#666;">
									💡 Consejo: Memorizar estos atajos aumenta considerablemente
									la velocidad de operación del sistema.
								</p>
							</div>
						`
					}],

					buttons: [{
						text: 'Cerrar',
						iconCls: 'x-fa fa-times',
						handler: function () {
							win.hide();
						}
					}]
				}).show();
			}
			
		});
		
		function fnBtnNuevaFactura() 
		{
			objListCustomerCreditLine 	= JSON.parse('<?php echo json_encode($objListCustomerCreditLine); ?>');
			var miVentanaPrincipal_ 	= Ext.getCmp('miVentanaPrincipal');
			var miVentanaDePago_		= Ext.getCmp('miVentanaDePago');			
			fnConfiguracionLoad(miVentanaPrincipal_, objConfigInit );
			fnConfiguracionLoad(miVentanaDePago_, objConfigInit );
		}
		
		
		function fnEnviarFactura(accion)
		{
			miVentanaEsperando.show();			
			var esValida = fnValidateFormAndSubmit(accion);
			if(!esValida)
			{
				miVentanaEsperando.hide();
				return;
			}
			
			//Actualizar El estado de la factura
			if(accion == "aplicar")
			{
				var miVentanaPrincipal_ 					= Ext.getCmp('miVentanaPrincipal');
				miVentanaPrincipal_.down('#txtStatusID').setValue(varStatusInvoiceAplicado);
			}
			
			
			// 2️⃣ Serializar datos (igual a jQuery serialize)
			var miVentanaPrincipal_ 					= Ext.getCmp('miVentanaPrincipal');
			var miVentanaDePago_						= Ext.getCmp('miVentanaDePago');
			var grid 									= miVentanaPrincipal_.down('#gridDetailTransactionMaster'); // encuentra el grid		
			var store 									= grid.getStore();
			var formData								= {};
			
			formData.txtTransactionNumber						= miVentanaPrincipal_.down("#txtTM_transactionNumber").text;
			formData.txtUserID									= miVentanaPrincipal_.down("#txtUserID").getValue(); 
			formData.txtCompanyID								= miVentanaPrincipal_.down("#txtCompanyID").getValue(); 
			formData.txtTransactionID							= miVentanaPrincipal_.down("#txtTransactionID").getValue(); 
			formData.txtTransactionMasterID						= miVentanaPrincipal_.down("#txtTransactionMasterID").getValue(); 
			formData.txtCodigoMesero							= miVentanaPrincipal_.down("#txtCodigoMesero").getValue(); 
			formData.txtStatusID								= miVentanaPrincipal_.down("#txtStatusID").getValue(); 
			formData.txtStatusIDOld								= miVentanaPrincipal_.down("#txtStatusIDOld").getValue(); 
			formData.txtDate									= miVentanaPrincipal_.down("#txtDate").getValue(); 
			formData.txtExchangeRate							= miVentanaPrincipal_.down("#txtExchangeRate").getValue(); 
			formData.txtNote									= miVentanaPrincipal_.down("#txtNote").getValue(); 
			formData.txtCurrencyID								= miVentanaPrincipal_.down("#txtCurrencyID").getValue(); 
			formData.txtCustomerID								= miVentanaPrincipal_.down("#txtCustomerID").getValue(); 
			formData.txtCustomerDescription						= miVentanaPrincipal_.down("#txtCustomerDescription").getValue(); 
			formData.txtReferenceClientName						= miVentanaPrincipal_.down("#txtReferenceClientName").getValue(); 
			formData.txtReferenceClientIdentifier				= miVentanaPrincipal_.down("#txtReferenceClientIdentifier").getValue(); 
			formData.txtCausalID								= miVentanaPrincipal_.down("#txtCausalID").getValue(); 
			formData.txtCustomerCreditLineID					= miVentanaPrincipal_.down("#txtCustomerCreditLineID").getValue(); 
			formData.txtZoneID									= miVentanaPrincipal_.down("#txtZoneID").getValue(); 
			formData.txtTypePriceID								= miVentanaPrincipal_.down("#txtTypePriceID").getValue(); 
			formData.txtWarehouseID								= miVentanaPrincipal_.down("#txtWarehouseID").getValue(); 
			formData.txtReference3								= miVentanaPrincipal_.down("#txtReference3").getValue(); 
			formData.txtEmployeeID								= miVentanaPrincipal_.down("#txtEmployeeID").getValue(); 
			formData.txtNumberPhone								= miVentanaPrincipal_.down("#txtNumberPhone").getValue(); 
			formData.txtMesaID									= miVentanaPrincipal_.down("#txtMesaID").getValue(); 
			formData.txtNextVisit								= miVentanaPrincipal_.down("#txtNextVisit").getValue(); 
			formData.txtDateFirst								= miVentanaPrincipal_.down("#txtDateFirst").getValue(); 
			formData.txtReference2								= miVentanaPrincipal_.down("#txtReference2").getValue(); 			
			formData.txtPeriodPay								= miVentanaPrincipal_.down("#txtPeriodPay").getValue(); 
			formData.txtReference1								= miVentanaPrincipal_.down("#txtReference1").getValue(); 
			formData.txtDayExcluded								= miVentanaPrincipal_.down("#txtDayExcluded").getValue(); 
			formData.txtFixedExpenses							= miVentanaPrincipal_.down("#txtFixedExpenses").getValue(); 
			formData.txtCheckApplyExoneracion					= getValueRadio("miVentanaPrincipal","txtCheckApplyExoneracion");
			formData.txtLayFirstLineProtocolo					= miVentanaPrincipal_.down("#txtLayFirstLineProtocolo").getValue(); 
			formData.txtCheckDeEfectivo							= getValueRadio("miVentanaPrincipal","txtCheckDeEfectivo");
			formData.txtCheckReportSinRiesgoValue				= getValueRadio("miVentanaPrincipal","txtCheckReportSinRiesgoValue"); 			
			formData.txtTMIReference1							= miVentanaPrincipal_.down("#txtTMIReference1").getValue();
			formData.txtSubTotal								= miVentanaPrincipal_.down("#txtSubTotal").getValue(); 
			formData.txtIva										= miVentanaPrincipal_.down("#txtIva").getValue(); 
			formData.txtPorcentajeDescuento						= miVentanaPrincipal_.down("#txtPorcentajeDescuento").getValue(); 
			formData.txtDescuento								= miVentanaPrincipal_.down("#txtDescuento").getValue(); 
			formData.txtServices								= miVentanaPrincipal_.down("#txtServices").getValue(); 
			formData.txtTotal									= miVentanaPrincipal_.down("#txtTotal").getValue(); 
			
			formData.txtChangeAmount							= miVentanaDePago_.down("#txtChangeAmount").getValue(); 
			formData.txtReceiptAmount							= miVentanaDePago_.down("#txtReceiptAmount").getValue(); 
			formData.txtReceiptAmountDol						= miVentanaDePago_.down("#txtReceiptAmountDol").getValue(); 
			formData.txtReceiptAmountTarjeta					= miVentanaDePago_.down("#txtReceiptAmountTarjeta").getValue(); 
			formData.txtReceiptAmountTarjeta_BankID				= miVentanaDePago_.down("#txtReceiptAmountTarjeta_BankID").getValue(); 			
			formData.txtReceiptAmountTarjeta_Reference			= miVentanaDePago_.down("#txtReceiptAmountTarjeta_Reference").getValue(); 
			formData.txtReceiptAmountTarjetaDol					= miVentanaDePago_.down("#txtReceiptAmountTarjetaDol").getValue(); 
			formData.txtReceiptAmountTarjetaDol_BankID			= miVentanaDePago_.down("#txtReceiptAmountTarjetaDol_BankID").getValue(); 
			formData.txtReceiptAmountTarjetaDol_Reference		= miVentanaDePago_.down("#txtReceiptAmountTarjetaDol_Reference").getValue(); 
			formData.txtReceiptAmountBank						= miVentanaDePago_.down("#txtReceiptAmountBank").getValue(); 
			formData.txtReceiptAmountBank_BankID				= miVentanaDePago_.down("#txtReceiptAmountBank_BankID").getValue(); 
			formData.txtReceiptAmountBank_Reference				= miVentanaDePago_.down("#txtReceiptAmountBank_Reference").getValue(); 
			formData.txtReceiptAmountBankDol					= miVentanaDePago_.down("#txtReceiptAmountBankDol").getValue(); 
			formData.txtReceiptAmountBankDol_BankID				= miVentanaDePago_.down("#txtReceiptAmountBankDol_BankID").getValue(); 
			formData.txtReceiptAmountBankDol_Reference			= miVentanaDePago_.down("#txtReceiptAmountBankDol_Reference").getValue(); 
			formData.txtReceiptAmountPoint						= miVentanaDePago_.down("#txtReceiptAmountPoint").getValue(); 
			
			var txtTransactionMasterDetailID 				= [];
			var txtItemID 									= [];
			var txtTransactionDetailName					= [];
			var txtSku										= [];
			var txtQuantity									= [];
			var txtPrice									= [];
			var txtSubTotal									= [];
			var txtIva										= [];
			var skuQuantityBySku							= [];
			var unitaryPriceInvidual						= [];
			var skuFormatoDescription						= [];
			var txtItemPrecio2								= [];
			var txtItemPrecio3								= [];
			var txtTransactionDetailNameDescription			= [];
			var txtTaxServices								= [];
			var txtDetailLote								= [];
			var txtInfoVendedor								= [];
			var txtInfoSerie								= [];
			var txtInfoReferencia							= [];
			var txtItemPrecio1								= [];
			var txtCatalogItemIDSku							= [];
			var txtRatioSku									= [];
			var txtDiscountByItem							= [];
			var txtCommisionByBankByItem					= [];
			
			
			store.each(function (record) 
			{	
				
				txtTransactionMasterDetailID.push(record.get('txtTMD_txtTransactionMasterDetailID'));
				txtItemID.push(record.get('txtTMD_txtItemID'));
				txtTransactionDetailName.push(record.get('txtTMD_txtTransactionDetailName'));
				txtSku.push(record.get('txtTMD_txtSku'));
				txtQuantity.push(record.get('txtTMD_txtQuantity'));
				txtPrice.push(record.get('txtTMD_txtPrice'));
				txtSubTotal.push(record.get('txtTMD_txtSubTotal'));
				txtIva.push(record.get('txtTMD_txtIva'));
				skuQuantityBySku.push(record.get('txtTMD_skuQuantityBySku'));
				unitaryPriceInvidual.push(record.get('txtTMD_unitaryPriceInvidual'));
				skuFormatoDescription.push(record.get('txtTMD_skuFormatoDescription'));
				txtItemPrecio2.push(record.get('txtTMD_txtItemPrecio2'));
				txtItemPrecio3.push(record.get('txtTMD_txtItemPrecio3'));
				txtTransactionDetailNameDescription.push(record.get('txtTMD_txtTransactionDetailNameDescription'));
				txtTaxServices.push(record.get('txtTMD_txtTaxServices'));
				txtDetailLote.push(record.get('txtTMD_txtDetailLote'));
				txtInfoVendedor.push(record.get('txtTMD_txtInfoVendedor'));
				txtInfoSerie.push(record.get('txtTMD_txtInfoSerie'));
				txtInfoReferencia.push(record.get('txtTMD_txtInfoReferencia'));
				txtItemPrecio1.push(record.get('txtTMD_txtItemPrecio1'));
				txtCatalogItemIDSku.push(record.get('txtTMD_txtCatalogItemIDSku'));
				txtRatioSku.push(record.get('txtTMD_txtRatioSku'));
				txtDiscountByItem.push(record.get('txtTMD_txtDiscountByItem'));
				txtCommisionByBankByItem.push(record.get('txtTMD_txtCommisionByBankByItem'));
				
			});
			
			formData["txtTransactionMasterDetailID[]"] 			= txtTransactionMasterDetailID;
			formData["txtItemID[]"] 							= txtItemID;
			formData["txtTransactionDetailName[]"] 				= txtTransactionDetailName;
			formData["txtSku[]"] 								= txtSku;
			formData["txtQuantity[]"] 							= txtQuantity;
			formData["txtPrice[]"] 								= txtPrice;
			formData["txtSubTotal[]"] 							= txtSubTotal;
			formData["txtIva[]"] 								= txtIva;
			formData["skuQuantityBySku[]"] 						= skuQuantityBySku;
			formData["unitaryPriceInvidual[]"] 					= unitaryPriceInvidual;
			formData["skuFormatoDescription[]"] 				= skuFormatoDescription;
			formData["txtItemPrecio2[]"] 						= txtItemPrecio2;
			formData["txtItemPrecio3[]"] 						= txtItemPrecio3;
			formData["txtTransactionDetailNameDescription[]"] 	= txtTransactionDetailNameDescription;
			formData["txtTaxServices[]"] 						= txtTaxServices;
			formData["txtDetailLote[]"] 						= txtDetailLote;
			formData["txtInfoVendedor[]"] 						= txtInfoVendedor;
			formData["txtInfoSerie[]"] 							= txtInfoSerie;
			formData["txtInfoReferencia[]"] 					= txtInfoReferencia;
			formData["txtItemPrecio1[]"] 						= txtItemPrecio1;
			formData["txtCatalogItemIDSku[]"] 					= txtCatalogItemIDSku;
			formData["txtRatioSku[]"] 							= txtRatioSku;
			formData["txtDiscountByItem[]"] 					= txtDiscountByItem;
			formData["txtCommisionByBankByItem[]"] 				= txtCommisionByBankByItem;
			
			var typeSave	= "new";
			if(miVentanaPrincipal_.down("#txtTransactionMasterID").getValue() > 0 )
				typeSave	= "edit";
			
			Ext.Ajax.request({
				url		: "<?php echo base_url(); ?>/app_invoice_billing/save/"+typeSave,
				method	: 'POST',           // o 'POST'
				async	: true,  			// true, no bloquea el hilo
				params	: formData,
				success: function(response, opts) 
				{
					console.log('Datos recibidos fnBtnGuardarFactura:', datos);
					miVentanaEsperando.hide();
					var datos = Ext.decode(response.responseText); // parse JSON
					
					 // Restaurar botón
					if(datos.success) 
					{
						fnUpdateInvoiceView(datos);
						Ext.Msg.show({
							title: 		'Operación realizada',
							message: 	"<span style='color:green;font-weight:bold;'>Operacion exitosa</span>",
							icon: 		Ext.Msg.INFO,
							buttons: 	Ext.Msg.OK
						});
					} 
					else 
					{
						Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>"+datos.error.message + "</span>" );	
					}
					
				},
				failure: function(response, opts) {
					miVentanaEsperando.hide();
					Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
					console.log('Server-side failure with status code ' + response.status);
				}
			});
			
			
		}
		
		function fnBtnGuardarFactura()
		{	
			fnEnviarFactura("guardar");
		}
		function fnBtnEliminarFactura()
		{
			miVentanaEsperando.show();
			Ext.Ajax.request({
				url		: "<?php echo base_url(); ?>/app_invoice_billing/delete",
				method	: 'POST',            // o 'POST'
				async: true,  				// bloquea el hilo
				params	: {                 // parámetros opcionales
					companyID 			: Ext.getCmp('miVentanaPrincipal').down('#txtCompanyID').getValue(),
					transactionID 		: Ext.getCmp('miVentanaPrincipal').down('#txtTransactionID').getValue(),
					transactionMasterID : Ext.getCmp('miVentanaPrincipal').down('#txtTransactionMasterID').getValue(),
				},
				success: function(response, opts) {
					
					// response.responseText contiene la respuesta en texto
					var datos = Ext.decode(response.responseText); // parse JSON
					console.log('Datos recibidos fnBtnEliminarFactura:', datos);
					miVentanaEsperando.hide();
					
					objListCustomerCreditLine 	= JSON.parse('<?php echo json_encode($objListCustomerCreditLine); ?>');
					var miVentanaPrincipal_ 	= Ext.getCmp('miVentanaPrincipal');
					var miVentanaDePago_		= Ext.getCmp('miVentanaDePago');			
					fnConfiguracionLoad(miVentanaPrincipal_, objConfigInit );
					fnConfiguracionLoad(miVentanaDePago_, objConfigInit );
					
					if(datos.error){
						
						Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>"+datos.message+"</span>" );						
					}
					else{
						Ext.Msg.show({
							title: 'Operación realizada',
							message: '<span style="color:green;font-weight:bold;">'+datos.message+ '</span>',
							icon: Ext.Msg.INFO,
							buttons: Ext.Msg.OK
						});
					}
				},
				failure: function(response, opts) {
					miVentanaEsperando.hide();
					Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
					console.log('Server-side failure with status code ' + response.status);
				}
			});
			
			
		}
		function fnBtnImprimirFactura()
		{
			miVentanaImpresion.show();
		}
		function fnBtnActualizarPantalla()
		{
			miVentanaEsperando.show();
			window.location.reload();
		}
		function fnBtnCrearBDLocal()
		{
			indexDBCreate(false);
		}
		function fnBtnActualizarCatalogo()
		{
			miVentanaEsperando.show();
			indexDBCreate(true);
		}
		function fnBtnSeleccionarFactura()
		{
			miVentanaSeleccionFactura.show();
		}
		function fnBtnNuevoProducto()
		{
			var url_request = "<?php echo base_url(); ?>/app_inventory_item/add";
			window.open(url_request, '_blank');
		}
		function fnBtnRegresar()
		{
			
			var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
			if(viewport){
				var grid = viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
				grid.getStore().loadData([]);
				
				if(grid.getStore().getCount() < 1 )
				{
					miVentanaEsperando.show();
					window.location.href = '<?php echo base_url(); ?>/app_invoice_billing/index';
				}
				else{
					Ext.Msg.confirm(
						'Confirmación',
						'¿Desea recargar la página?',
						function (btn) {
							if (btn === 'yes') {

								// Mostrar tu ventana
								miVentanaEsperando.show();

								// Redirigir / recargar
								window.location.href = '<?php echo base_url(); ?>/app_invoice_billing/index';

							} else {
								// No hacer nada si presiona No
								// Puedes poner un console.log si quieres
								console.log('Operación cancelada por el usuario');
							}
						}
					);
				}
			
			}
	
	
			
		}
		function fnBtnLimpiarCliente (btn) {
			btn.up('fieldcontainer').down('#txtCustomerID').setValue('');
			btn.up('fieldcontainer').down('#txtCustomerDescription').setValue('');
		}
		function fnBtnBuscarCliente()
		{
			miVentanaSeleccionCliente.show();
		}
		function fnBtnPagar(btn)
		{
			var panel = btn.up('panel');
			// Tomar valores de ejemplo
			var total = 50;			
			miVentanaDePago.show();													
		}
		function fnBtnConfirmarPago(winBtn) {			
			winBtn.up('window').close();
			fnEnviarFactura("aplicar");
		}
		function fnBtnCancelarPago(winBtn) {
			winBtn.up('window').close();
		}
		function fnBtnCancelarImpresion () {
			miVentanaImpresion.hide()
		}
		function fnBtnImpresion1()
		{
			
			miVentanaImpresion.hide();
			var miVentanaPrincipal_ 	= Ext.getCmp('miVentanaPrincipal');
			var ahora 					= new Date();
			var año 					= ahora.getFullYear();
			var mes 					= String(ahora.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
			var dia 					= String(ahora.getDate()).padStart(2, '0');
			var hora 					= String(ahora.getHours()).padStart(2, '0');
			var minuto 					= String(ahora.getMinutes()).padStart(2, '0');
			var segundo 				= String(ahora.getSeconds()).padStart(2, '0');
			var fechaHora 				= `${año}${mes}${dia}${hora}${minuto}${segundo}`;	
			var transactionMasterID		= miVentanaPrincipal_.down("#txtTransactionMasterID").getValue();
			var url						= "<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+transactionMasterID+"/"+fechaHora;
			window.open(url, '_blank');
		
		
		}
		function fnBtnImpresion2()
		{
			miVentanaImpresion.hide();
			var miVentanaPrincipal_ 	= Ext.getCmp('miVentanaPrincipal');
			var ahora 					= new Date();
			var año 					= ahora.getFullYear();
			var mes 					= String(ahora.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
			var dia 					= String(ahora.getDate()).padStart(2, '0');
			var hora 					= String(ahora.getHours()).padStart(2, '0');
			var minuto 					= String(ahora.getMinutes()).padStart(2, '0');
			var segundo 				= String(ahora.getSeconds()).padStart(2, '0');
			var fechaHora 				= `${año}${mes}${dia}${hora}${minuto}${segundo}`;	
			var transactionMasterID		= miVentanaPrincipal_.down("#txtTransactionMasterID").getValue();
			var url						= "<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+transactionMasterID+"/"+fechaHora;
			window.open(url, '_blank');
		}
		function fnBtnImpresion3()
		{
			miVentanaImpresion.hide();
			var miVentanaPrincipal_ 	= Ext.getCmp('miVentanaPrincipal');
			var ahora 					= new Date();
			var año 					= ahora.getFullYear();
			var mes 					= String(ahora.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
			var dia 					= String(ahora.getDate()).padStart(2, '0');
			var hora 					= String(ahora.getHours()).padStart(2, '0');
			var minuto 					= String(ahora.getMinutes()).padStart(2, '0');
			var segundo 				= String(ahora.getSeconds()).padStart(2, '0');
			var fechaHora 				= `${año}${mes}${dia}${hora}${minuto}${segundo}`;	
			var transactionMasterID		= miVentanaPrincipal_.down("#txtTransactionMasterID").getValue();
			var url						= "<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+transactionMasterID+"/"+fechaHora;
			window.open(url, '_blank');
		}
		function fnBtnImpresion4()
		{
			miVentanaImpresion.hide();
			var miVentanaPrincipal_ 	= Ext.getCmp('miVentanaPrincipal');
			var ahora 					= new Date();
			var año 					= ahora.getFullYear();
			var mes 					= String(ahora.getMonth() + 1).padStart(2, '0'); // Mes empieza en 0
			var dia 					= String(ahora.getDate()).padStart(2, '0');
			var hora 					= String(ahora.getHours()).padStart(2, '0');
			var minuto 					= String(ahora.getMinutes()).padStart(2, '0');
			var segundo 				= String(ahora.getSeconds()).padStart(2, '0');
			var fechaHora 				= `${año}${mes}${dia}${hora}${minuto}${segundo}`;	
			var transactionMasterID		= miVentanaPrincipal_.down("#txtTransactionMasterID").getValue();
			var url						= "<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/2/transactionID/19/transactionMasterID/"+transactionMasterID+"/"+fechaHora;
			window.open(url, '_blank');
		}
		function fnBtnSeleccionFactura() {
			
			
			var grid 		= miVentanaSeleccionFactura.down('grid');
			var seleccion 	= grid.getSelection();

			if (seleccion.length === 0) 
			{
				Ext.Msg.alert('Aviso', '<span style="color:red;font-weight:bold;">Debe seleccionar al menos una factura.</span>');
				return;
			}
			
			miVentanaEsperando.show();
			miVentanaSeleccionFactura.hide();
			fnLoadInvoiceExistente(seleccion[0].data.transactionMasterID,"none");
			
		}
		function fnBtnCancelarSeleccionFactura(btn) {
			btn.up('window').close();
		}
		function fnBtnConfirmarInformacionAdicional()
		{
			
			var win    = this.up('window');
			var record = win.currentRecord;

			if (!record) {
				Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se encontró el registro</span>');
				return;
			}

			// Actualizar valores desde la ventana
			record.set({
				txtTMD_txtPrice: 			win.down("#txtSelectPrecio").getValue(),
				txtTMD_txtInfoVendedor: 	win.down("#txtSelectVendedor").getValue(),
				txtTMD_txtInfoSerie: 		win.down("#txtSerieProducto").getValue(),
				txtTMD_txtInfoReferencia: 	win.down("#txtReferenciaProducto").getValue()
			});

			// (Opcional) confirmar cambios
			record.commit();
			win.close();
			
		}
		function fnBtnCancelarInformacionAdicional(btn){
			btn.up('window').close();
		}
		function fnBtnSeleccionProducto(btn) {
			
			var grid 		= miVentanaSeleccionProducto.down('grid');
			var seleccion 	= grid.getSelection();

			if (seleccion.length === 0) 
			{
				Ext.Msg.alert('Aviso', '<span style="color:red;font-weight:bold;">Debe seleccionar al menos un producto.</span>');
				return;
			}
			
			
			var grid 	= miVentanaPrincipal.down('#gridDetailTransactionMaster'); // buscar por itemId
			var store 	= grid.getStore();

			seleccion.forEach(function(record) 
			{	
				var itemID = record.data.itemID;
				indexDBGetLocalProductoByItemID(itemID, function(resultado) 
				{
					
					console.log(resultado);

					// resultado.productos → lista filtrada por Barra (contiene)
					// resultado.conceptos → lista exacta por componentItemID
					// resultado.skus → lista exacta por itemID
					var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
					if(!viewport){
						return;
					}

					var currencyID 		= viewport.down("#txtCurrencyID").getValue();
					var warehouseID		= viewport.down("#txtWarehouseID").getValue();
					if(resultado.productos.length > 0)
					{
						resultado.productos = Ext.Array.filter(resultado.productos, function (obj) { 
							return (
								obj.currencyID === currencyID && 
								obj.warehouseID === warehouseID
							);
						});
						
						
						if(resultado.productos.length > 0 )
						resultado.productos  = resultado.productos[0];
							
						var sumar				= true;
						//Logica de precio
						if(viewport.down("#txtTypePriceID").getValue() == "154" /*precio1*/)
							resultado.productos.Precio = resultado.productos.Precio;
						else if(viewport.down("#txtTypePriceID").getValue() == "155" /*precio2*/)
							resultado.productos.Precio = resultado.productos.Precio2;
						else /*precio3*/
							resultado.productos.Precio = resultado.productos.Precio3;
							
						
						onCompleteNewItem(resultado.productos,sumar,resultado.conceptos,resultado.skus);
						
					}
					
				});
				
				
			});
			
			miVentanaSeleccionProducto.hide();
			
			
		}
		function fnBtnCancelarSeleccionProducto(btn) {
			btn.up('window').close();
		}
		function fnBtnSeleccionCliente() {
			var grid 		= miVentanaSeleccionCliente.down('grid');
			var seleccion 	= grid.getSelection();

			if (seleccion.length === 0) 
			{
				Ext.Msg.alert('Aviso', '<span style="color:red;font-weight:bold;">Debe seleccionar al menos un cliente.</span>');
				return;
			}
			
			seleccion.forEach(function(record) 
			{	
				miVentanaPrincipal.down('#txtCustomerID').setValue(  record.data.entityID  );
				miVentanaPrincipal.down('#txtCustomerDescription').setValue(  record.data.Codigo + " " + record.data.Nombre );			
			});
			
			miVentanaSeleccionCliente.hide();
			miVentanaEsperando.show();
			fnClearData();
			fnGetCustomerClient();			
			
			
			
		}
		function fnBtnCancelarSeleccionCliente(btn) {
			btn.up('window').close();
		}
		
		
		function fnChange_ChangeAmount(field, newValue) {
			if (newValue < 0) {
				field.setFieldStyle('color:red; font-weight:bold; border:2px solid red;');
			}
			else if (newValue > 0) {
				field.setFieldStyle('color:green; font-weight:bold; border:2px solid green;');
			}
			else {
				field.setFieldStyle('color:black; font-weight:bold; border:2px solid black;');
			}
		}
		
		function fnChange_ReceiptAmount(field, newValue, oldValue) {
			
			
			if (isLoading) return; // Detener evento
			console.log('Valor anterior:', oldValue);
			console.log('Nuevo valor:', newValue);
			
			fnCalculateAmountPay();
		
		}
		
		function fnChange_ReceiptAmountTarjeta_BankID (field, newValue, oldValue) {
			
			if (isLoading) return; // Detener evento
			
			console.log('Valor anterior:', oldValue);
			console.log('Nuevo valor:', newValue);
			// Aquí puedes agregar la lógica que necesites
			// Por ejemplo, recalcular totales según el descuento
			let value = 0;
			fnRecalcularMontoComision(value);
		
		}
		
		function fnChange_PorcentageDescuento (field, newValue, oldValue) 
		{
			
			if (isLoading) return; // Detener evento
			console.log('Valor anterior:', oldValue);
			console.log('Nuevo valor:', newValue);
			
			// Aquí puedes agregar la lógica que necesites
			// Por ejemplo, recalcular totales según el descuento
			
			if (window.miVentanaEsperando && miVentanaEsperando.show) {
				miVentanaEsperando.show();
			}

			fnRecalculateDetail(true,"");
			
			if (window.miVentanaEsperando ) {
				miVentanaEsperando.hide();
			}

	
			 
		}
		
		function fnChange_FirstLineProtocolo (field, newValue, oldValue) 
		{
			if (isLoading) return; // Detener evento
			
			var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
			if(!viewport){
				return;
			}
			
			var urlExoneration="<?php echo base_url(); ?>/app_invoice_api/getNumberExoneration/value/"+ viewport.down("#txtLayFirstLineProtocolo").getValue(); 
			if(varParameterINVOICE_BILLING_VALIDATE_EXONERATION == "true")
			{
				miVentanaEsperando.show();
				Ext.Ajax.request({
					url		: urlExoneration,
					method	: 'GET',             // o 'POST'
					async: false,  // bloquea el hilo
					params	: {                  // parámetros opcionales
						filtro: 'ABC'
					},
					success: function(response, opts) {
						
						// response.responseText contiene la respuesta en texto
						var datos = Ext.decode(response.responseText); // parse JSON
						console.log('Datos recibidos fnChange_FirstLineProtocolo:', datos);
						
						
						//La exoneracion ya existe no exonerar
						var timerNotification 	= 15000;
						if(datos.objTransactionMaster.length > 0 )
						{
							viewport.down('#txtCheckApplyExoneracion').setValue(false);							
							Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">El numero de exoneracion ya existe!!</span>');
						}
						//La exoneracoin no existe si se puede exonerar
						else
						{
							viewport.down('#txtCheckApplyExoneracion').setValue(true);
							Ext.toast({
								html: 'Exoneración aplicada!!',
								title: 'Éxito',
								width: 300,
								align: 't',      // arriba
								bodyStyle: 'background-color: #dff0d8; color: #3c763d;', // verde estilo éxito
								closable: true,
								slideInDuration: 300,
								minWidth: 200
							});
						}

						var grid 	= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
						var store 	= grid.getStore();	
						var length  = store.getCount();
						store.each(function (record) {						
							fnGetConcept(record.get('txtTMD_txtItemID'),"ALL");
						});
						
						
					},
					failure: function(response, opts) {
						Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
						console.log('Server-side failure with status code ' + response.status);
					}
				});
				
				
				
			}
		}
		
		function fnChange_ApplyExoneration (field, newValue) {
			
			if (isLoading) return; // Detener evento
			
			if (newValue === true) {
				console.log("Seleccionado: SI (1)");
			}
			
			

			var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
			if(!viewport){
				return;
			}
			
			var grid 	= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 	= grid.getStore();	
			var length  = store.getCount();
			
			
			store.each(function (record) {
				fnGetConcept(record.get('txtTMD_txtItemID'),"ALL");
			});
			
			//Validar variable
			if (window.miVentanaEsperando && miVentanaEsperando.show) {
				
			}
			else
				return;
			
			miVentanaEsperando.show();
			setTimeout(() => { miVentanaEsperando.hide(); }, (length * 1000) * 0.2 );
			
			
		}
		
		function fnChange_CurrencyID_CreditLineID_WarehouseID(combo, newValue, oldValue, eOpts) {
			
			if (isLoading) return; // Detener evento
			fnClearData();
			fnLockPayment();
		}
		function fnChange_CausalID(combo, newValue, oldValue, eOpts) {
			
			if (isLoading) return; // Detener evento
			fnClearData();
			fnLockPayment();
			fnRenderLineaCreditoDiv();
		}
		function fnChangeTypePreiceID(combo, newValue, oldValue, eOpts) {
			
			if (isLoading) return; // Detener evento
			fnActualizarPrecio();
		}
		
		
		function fnBtnEliminarProductoDetail(btn) {
			var grid = btn.up('grid');
			var selection = grid.getSelectionModel().getSelection();
			if (selection.length > 0) {
				grid.getStore().remove(selection);
			} else {
				Ext.Msg.alert('Atención', '<span style="color:red;font-weight:bold;">Seleccione al menos un producto para eliminar.</span>');
			}
		}


		function fnBtnNuevoProductoDetail(btn)
		{
			miVentanaSeleccionProducto.show();
		}



		function fnBtnEnterScaner (field, e) 
		{
			if (e.getKey() === Ext.EventObject.ENTER) 
			{
				var codigo 			= field.getValue();
				var grid 			= field.up('grid');
				var codigoABuscar 	= codigo;

				indexDBGetLocalProductoByBarra(codigoABuscar, function(resultado) {
					console.log(resultado);

					// resultado.productos → lista filtrada por Barra (contiene)
					// resultado.conceptos → lista exacta por componentItemID
					// resultado.skus → lista exacta por itemID
					var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
					if(!viewport){
						return;
					}

					var currencyID 		= viewport.down("#txtCurrencyID").getValue();
					var warehouseID		= viewport.down("#txtWarehouseID").getValue();
					if(resultado.productos.length > 0)
					{
						resultado.productos = Ext.Array.filter(resultado.productos, function (obj) { 
							return (
								obj.currencyID === currencyID && 
								obj.warehouseID === warehouseID
							);
						});
						
						
						if(resultado.productos.length > 0 )
						resultado.productos  = resultado.productos[0];
							
						var sumar				= true;
						//Logica de precio
						if(viewport.down("#txtTypePriceID").getValue() == "154" /*precio1*/)
							resultado.productos.Precio = resultado.productos.Precio;
						else if(viewport.down("#txtTypePriceID").getValue() == "155" /*precio2*/)
							resultado.productos.Precio = resultado.productos.Precio2;
						else /*precio3*/
							resultado.productos.Precio = resultado.productos.Precio3;
							
						
						onCompleteNewItem(resultado.productos,sumar,resultado.conceptos,resultado.skus);
						
					}
					
				});


				
						
						

					

				
				field.setValue(''); // limpia el campo
			}
		}

		
		
		function fnConfiguracionLoad(contenedor, config) 
		{	
			// Recorremos todos los componentes del contenedor
			contenedor.query('*').forEach(function(comp) 
			{
				
				// 1️⃣ Cambiar texto
				if (config.texts && config.texts[comp.getId()]) {
					if (comp.setFieldLabel) {
						comp.setFieldLabel(config.texts[comp.getId()]);
					} else if (comp.setBoxLabel) {
						comp.setBoxLabel(config.texts[comp.getId()]);
					}
				}

				// 2️⃣ Limpiar y cargar datos en ComboBox
				if (comp.xtype === 'combobox' && config.comboStores && config.comboStores[comp.getId()]) {					
					let storeData = config.comboStores[comp.getId()];

					if (comp.getStore()) {
						// Limpiar store antes de cargar
						comp.getStore().removeAll();

						// Cargar los nuevos datos
						comp.getStore().loadData(storeData);
						
						// Opcional: resetear el valor seleccionado
						comp.setValue(null);
					}
				}

				// 3️⃣ Cambiar visibilidad
				if (config.hidden && config.hidden[comp.getId()] !== undefined) {					
					comp.setHidden(config.hidden[comp.getId()]);
				}

				// 4️⃣ Cambiar habilitado/solo lectura
				if (config.disabled && config.disabled[comp.getId()] !== undefined) {
					comp.setDisabled(config.disabled[comp.getId()]);
				}

				if (config.readOnly && config.readOnly[comp.getId()] !== undefined && comp.setReadOnly) {
					comp.setReadOnly(config.readOnly[comp.getId()]);
				}
				
				// 5️⃣ Asignar valores a los campos
				if (config.values && config.values[comp.getId()] !== undefined) {
					let valor = config.values[comp.getId()];
					
					// Dependiendo del tipo de control, usamos setValue()
					if (comp.setValue) {
						comp.setValue(valor);
					}
				}
				
				// 5️⃣ Asignar etiqueta
				if (config.labels && config.labels[comp.getId()] !== undefined) {
					let valor = config.labels[comp.getId()];						
					comp.setText(valor);
				}


			});
		}
		
	});
	
	function fnFillFactura(formPanel, obj) 
	{
		
		
		//txtTM_transactionNumber
		var miVentanaPrincipal_ 	= Ext.getCmp('miVentanaPrincipal');
		var miVentanaDePago_		= Ext.getCmp('miVentanaDePago');
		var campoNombre 			= miVentanaPrincipal_.down('#txtTM_transactionNumber');
		if(obj == null)
		{
			campoNombre.setText( objConfigInit.labels.txtTM_transactionNumber );
		}
		else
		{
			campoNombre.setText( obj.txtTM_transactionNumber );
		}
		
		//txtUserID
		var campoNombre = miVentanaPrincipal_.down('#txtUserID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtUserID );
		}
		else
		{
			campoNombre.setValue( obj.txtUserID );
		}
		
		//txtCompanyID
		var campoNombre = miVentanaPrincipal_.down('#txtCompanyID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtCompanyID );
		}
		else
		{
			campoNombre.setValue( obj.txtCompanyID );
		}
		
		//txtTransactionID
		var campoNombre = miVentanaPrincipal_.down('#txtTransactionID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtTransactionID );
		}
		else
		{
			campoNombre.setValue( obj.txtTransactionID );
		}
		
		//txtTransactionMasterID
		var campoNombre = miVentanaPrincipal_.down('#txtTransactionMasterID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtTransactionMasterID );
		}
		else
		{
			campoNombre.setValue( obj.txtTransactionMasterID );
		}
		
		//txtCodigoMesero
		var campoNombre = miVentanaPrincipal_.down('#txtCodigoMesero');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtCodigoMesero );
		}
		else
		{
			campoNombre.setValue( obj.txtCodigoMesero );
		}
		
		//txtStatusID
		var campoNombre = miVentanaPrincipal_.down('#txtStatusID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtStatusID );
		}
		else
		{
			campoNombre.setValue( obj.txtStatusID );
		}
		
		//txtStatusIDOld
		var campoNombre = miVentanaPrincipal_.down('#txtStatusIDOld');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtStatusIDOld );
		}
		else
		{
			campoNombre.setValue( obj.txtStatusIDOld );
		}
		
		//txtDate
		var campoNombre = miVentanaPrincipal_.down('#txtDate');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtDate );
		}
		else
		{
			campoNombre.setValue( obj.txtDate );
		}
		
		//txtExchangeRate
		var campoNombre = miVentanaPrincipal_.down('#txtExchangeRate');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtExchangeRate );
		}
		else
		{
			campoNombre.setValue( obj.txtExchangeRate );
		}
		
		//txtNote
		var campoNombre = miVentanaPrincipal_.down('#txtNote');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtNote );
		}
		else
		{
			campoNombre.setValue( obj.txtNote );
		}
		
		//txtCurrencyID
		var campoNombre = miVentanaPrincipal_.down('#txtCurrencyID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtCurrencyID );
		}
		else
		{
			campoNombre.setValue( obj.txtCurrencyID );
		}
		
		//txtCustomerID
		var campoNombre = miVentanaPrincipal_.down('#txtCustomerID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtCustomerID );
		}
		else
		{
			campoNombre.setValue( obj.txtCustomerID );
		}
		
		//txtCustomerDescription
		var campoNombre = miVentanaPrincipal_.down('#txtCustomerDescription');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtCustomerDescription );
		}
		else
		{
			campoNombre.setValue( obj.txtCustomerDescription );
		}
		
		//txtReferenceClientName
		var campoNombre = miVentanaPrincipal_.down('#txtReferenceClientName');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReferenceClientName );
		}
		else
		{
			campoNombre.setValue( obj.txtReferenceClientName );
		}
		
		//txtReferenceClientIdentifier
		var campoNombre = miVentanaPrincipal_.down('#txtReferenceClientIdentifier');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReferenceClientIdentifier );
		}
		else
		{
			campoNombre.setValue( obj.txtReferenceClientIdentifier );
		}
		
		//txtCausalID
		var campoNombre = miVentanaPrincipal_.down('#txtCausalID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtCausalID );
		}
		else
		{
			campoNombre.setValue( obj.txtCausalID );
		}
		
		//txtCustomerCreditLineID
		var campoNombre = miVentanaPrincipal_.down('#txtCustomerCreditLineID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtCustomerCreditLineID );
		}
		else
		{
			campoNombre.setValue( obj.txtCustomerCreditLineID );
		}
		
		//txtZoneID
		var campoNombre = miVentanaPrincipal_.down('#txtZoneID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtZoneID );
		}
		else
		{
			campoNombre.setValue( obj.txtZoneID );
		}
		
		//txtTypePriceID
		var campoNombre = miVentanaPrincipal_.down('#txtTypePriceID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtTypePriceID );
		}
		else
		{
			campoNombre.setValue( obj.txtTypePriceID );
		}
		
		//txtWarehouseID
		var campoNombre = miVentanaPrincipal_.down('#txtWarehouseID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtWarehouseID );
		}
		else
		{
			campoNombre.setValue( obj.txtWarehouseID );
		}
		
		//txtReference3
		var campoNombre = miVentanaPrincipal_.down('#txtReference3');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReference3 );
		}
		else
		{
			campoNombre.setValue( obj.txtReference3 );
		}
		
		//txtEmployeeID
		var campoNombre = miVentanaPrincipal_.down('#txtEmployeeID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtEmployeeID );
		}
		else
		{
			campoNombre.setValue( obj.txtEmployeeID );
		}
		
		//txtNumberPhone
		var campoNombre = miVentanaPrincipal_.down('#txtNumberPhone');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtNumberPhone );
		}
		else
		{
			campoNombre.setValue( obj.txtNumberPhone );
		}
		
		//txtMesaID
		var campoNombre = miVentanaPrincipal_.down('#txtMesaID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtMesaID );
		}
		else
		{
			campoNombre.setValue( obj.txtMesaID );
		}
		
		//txtNextVisit
		var campoNombre = miVentanaPrincipal_.down('#txtNextVisit');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtNextVisit );
		}
		else
		{
			campoNombre.setValue( obj.txtNextVisit );
		}
		
		//txtDateFirst
		var campoNombre = miVentanaPrincipal_.down('#txtDateFirst');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtDateFirst );
		}
		else
		{
			campoNombre.setValue( obj.txtDateFirst );
		}
		
		//txtReference2
		var campoNombre = miVentanaPrincipal_.down('#txtReference2');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReference2 );
		}
		else
		{
			campoNombre.setValue( obj.txtReference2 );
		}
		
		
		//txtPeriodPay
		var campoNombre = miVentanaPrincipal_.down('#txtPeriodPay');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtPeriodPay );
		}
		else
		{
			campoNombre.setValue( obj.txtPeriodPay );
		}
		
		//txtReference1
		var campoNombre = miVentanaPrincipal_.down('#txtReference1');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReference1 );
		}
		else
		{
			campoNombre.setValue( obj.txtReference1 );
		}
		
		//txtDayExcluded
		var campoNombre = miVentanaPrincipal_.down('#txtDayExcluded');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtDayExcluded );
		}
		else
		{
			campoNombre.setValue( obj.txtDayExcluded );
		}
		
		//txtFixedExpenses
		var campoNombre = miVentanaPrincipal_.down('#txtFixedExpenses');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtFixedExpenses );
		}
		else
		{
			campoNombre.setValue( obj.txtFixedExpenses );
		}
		
		//txtCheckApplyExoneracion
		var campoNombre = miVentanaPrincipal_.down('#txtCheckApplyExoneracion');
		if(obj == null)
		{
			setValueRadio("miVentanaPrincipal","txtCheckApplyExoneracion",objConfigInit.values.txtCheckApplyExoneracion );
		}
		else
		{
			setValueRadio("miVentanaPrincipal","txtCheckApplyExoneracion",obj.txtCheckApplyExoneracion);
		}
		
		//txtLayFirstLineProtocolo
		var campoNombre = miVentanaPrincipal_.down('#txtLayFirstLineProtocolo');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtLayFirstLineProtocolo );
		}
		else
		{
			campoNombre.setValue( obj.txtLayFirstLineProtocolo );
		}
		
		//txtCheckDeEfectivo
		var campoNombre = miVentanaPrincipal_.down('#txtCheckDeEfectivo');
		if(obj == null)
		{
			setValueRadio("miVentanaPrincipal","txtCheckDeEfectivo",objConfigInit.values.txtCheckDeEfectivo );
		}
		else
		{
			setValueRadio("miVentanaPrincipal","txtCheckDeEfectivo",obj.txtCheckDeEfectivo );
		}
		
		//txtCheckReportSinRiesgoValue
		var campoNombre = miVentanaPrincipal_.down('#txtCheckReportSinRiesgoValue');
		if(obj == null)
		{
			setValueRadio("miVentanaPrincipal","txtCheckReportSinRiesgoValue",objConfigInit.values.txtCheckReportSinRiesgoValue );
		}
		else
		{
			setValueRadio("miVentanaPrincipal","txtCheckReportSinRiesgoValue",obj.txtCheckReportSinRiesgoValue );
		}
		
		
		//txtTMIReference1
		var campoNombre = miVentanaPrincipal_.down('#txtTMIReference1');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtTMIReference1 );
		}
		else
		{
			campoNombre.setValue( obj.txtTMIReference1 );
		}
		
		//txtSubTotal
		var campoNombre = miVentanaPrincipal_.down('#txtSubTotal');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtSubTotal );
		}
		else
		{
			campoNombre.setValue( obj.txtSubTotal );
		}
		
		//txtIva
		var campoNombre = miVentanaPrincipal_.down('#txtIva');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtIva );
		}
		else
		{
			campoNombre.setValue( obj.txtIva );
		}
		
		//txtPorcentajeDescuento
		var campoNombre = miVentanaPrincipal_.down('#txtPorcentajeDescuento');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtPorcentajeDescuento );
		}
		else
		{
			campoNombre.setValue( obj.txtPorcentajeDescuento );
		}
		
		//txtDescuento
		var campoNombre = miVentanaPrincipal_.down('#txtDescuento');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtDescuento );
		}
		else
		{
			campoNombre.setValue( obj.txtDescuento );
		}
		
		//txtServices
		var campoNombre = miVentanaPrincipal_.down('#txtServices');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtServices );
		}
		else
		{
			campoNombre.setValue( obj.txtServices );
		}
		
		//txtTotal
		var campoNombre = miVentanaPrincipal_.down('#txtTotal');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtTotal );
		}
		else
		{
			campoNombre.setValue( obj.txtTotal );
		}
		
		//txtChangeAmount
		var campoNombre = miVentanaDePago_.down('#txtChangeAmount');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtChangeAmount );
		}
		else
		{
			campoNombre.setValue( obj.txtChangeAmount );
		}
		
		//txtReceiptAmount
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmount');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmount );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmount );
		}
		
		//txtReceiptAmountDol
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountDol');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountDol );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountDol );
		}
		
		//txtReceiptAmountTarjeta
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountTarjeta');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountTarjeta );
		}
		
		//txtReceiptAmountTarjeta_BankID
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountTarjeta_BankID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_BankID );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountTarjeta_BankID );
		}
		
		//txtReceiptAmountTarjeta_Reference
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountTarjeta_Reference');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_Reference );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountTarjeta_Reference );
		}
		
		
		//txtReceiptAmountTarjetaDol
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountTarjetaDol');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountTarjetaDol );
		}
		
		//txtReceiptAmountTarjetaDol_BankID
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountTarjetaDol_BankID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_BankID );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountTarjetaDol_BankID );
		}
		
		//txtReceiptAmountTarjetaDol_Reference
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountTarjetaDol_Reference');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_Reference );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountTarjetaDol_Reference );
		}
		
		//txtReceiptAmountBank
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountBank');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountBank );
		}
		
		//txtReceiptAmountBank_BankID
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountBank_BankID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_BankID );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountBank_BankID );
		}
		
		
		//txtReceiptAmountBank_Reference
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountBank_Reference');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_Reference );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountBank_Reference );
		}
		
		//txtReceiptAmountBankDol
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountBankDol');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountBankDol );
		}
		
		//txtReceiptAmountBankDol_BankID
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountBankDol_BankID');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_BankID );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountBankDol_BankID );
		}
		
		//txtReceiptAmountBankDol_Reference
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountBankDol_Reference');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_Reference );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountBankDol_Reference );
		}
		
		//txtReceiptAmountPoint
		var campoNombre = miVentanaDePago_.down('#txtReceiptAmountPoint');
		if(obj == null)
		{
			campoNombre.setValue( objConfigInit.values.txtReceiptAmountPoint );
		}
		else
		{
			campoNombre.setValue( obj.txtReceiptAmountPoint );
		}
		
		//Limpiar detalle		
		var grid = miVentanaPrincipal_.down('#gridDetailTransactionMaster'); // encuentra el grid		
		if(obj == null)
		{
			grid.getStore().loadData([]);
		}
		else 
		{
			grid.getStore().loadData(obj.txtTransactionMasterDetail); 
			
			
			// Buscar columna widgetColumn
			var comboColumn = null;
			Ext.Array.each(grid.columns, function (col) {
				if (col.dataIndex === 'txtTMD_txtSku') {
					comboColumn = col;
					return false;
				}
			});

			if (!comboColumn) {
				console.error('No se encontró la columna txtTMD_txtSku');
				return;
			}


			// Aplicar store y valor a CADA FILA del grid
			grid.getStore().each(function (record) {
				
				// Crear store para el combo
				var comboStoreData 	= [{ id: record.get("txtTMD_txtCatalogItemIDSku") , name : record.get("txtTMD_txtSku") }];
				var storeForCombo 	= Ext.create('Ext.data.Store', {
					fields: ['id', 'name'],
					data: comboStoreData
				});
				
				var widget = comboColumn.getWidget(record);
				if (widget) {
					widget.bindStore(storeForCombo);
					if (comboStoreData.length > 0) {
						widget.setValue(comboStoreData[0].id);
						record.set('txtTMD_txtSku', comboStoreData[0].id);
					}
				}
				
			});

			
			

			var viewport = Ext.getCmp('miVentanaPrincipal');
			if (!viewport) return;

			var gridDetail = viewport.down('#gridDetailTransactionMaster');
			var storeDetail = gridDetail.getStore();
			
			
			//recalcular detalle
			fnRecalculateDetail(false,"");
			
		}
	
			
	}
	
	function indexDBCreate(obtenerRegistroDelServer) 
	{
		var indexDB 	= window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
		const request 	= indexDB.open('MyDatabasePosMe', 2);

		request.onsuccess = (e) =>
		{

			// Se crea la conexion
			db 				   = request.result;
			console.info('fnDatabaseInicializada');
			if(obtenerRegistroDelServer)
			{
				indexDBObtenerProductos();
			}


		};

		request.onupgradeneeded  = (e) => {
			console.info('Database table created');

			const db = request.result;
			//...

			const objectStoreX001  = db.createObjectStore('objListaProductosX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreX001.createIndex("Barra", "Barra", { unique: false });
			objectStoreX001.createIndex("Cantidad", "Cantidad", { unique: false });
			objectStoreX001.createIndex("Codigo", "Codigo", { unique: false });
			objectStoreX001.createIndex("Descripcion", "Descripcion", { unique: false });
			objectStoreX001.createIndex("Medida", "Medida", { unique: false });
			objectStoreX001.createIndex("Precio", "Precio", { unique: false });
			objectStoreX001.createIndex("cost", "cost", { unique: false });
			objectStoreX001.createIndex("currencyID", "currencyID", { unique: false });
			objectStoreX001.createIndex("warehouseID", "warehouseID", { unique: false });
			objectStoreX001.createIndex("isInvoice", "isInvoice", { unique: false });
			objectStoreX001.createIndex("isInvoiceQuantityZero", "isInvoiceQuantityZero", { unique: false });
			objectStoreX001.createIndex("itemID", "itemID", { unique: false });
			objectStoreX001.createIndex("iwCost", "iwCost", { unique: false });
			objectStoreX001.createIndex("iwQuantityMax", "iwQuantityMax", { unique: false });
			objectStoreX001.createIndex("iwQuantityMin", "iwQuantityMin", { unique: false });
			objectStoreX001.createIndex("quantity", "quantity", { unique: false });
			objectStoreX001.createIndex("quantityMax", "quantityMax", { unique: false });
			objectStoreX001.createIndex("quantityMin", "quantityMin", { unique: false });
			objectStoreX001.createIndex("unitMeasureID", "unitMeasureID", { unique: false });
			objectStoreX001.createIndex("Precio2", "Precio2", { unique: false });
			objectStoreX001.createIndex("Precio3", "Precio3", { unique: false });

			const objectStoreSkuX001  = db.createObjectStore('objListaProductosSkuX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreSkuX001.createIndex("itemID", "itemID", { unique: false });
			objectStoreSkuX001.createIndex("catalogItemID", "catalogItemID", { unique: false });
			objectStoreSkuX001.createIndex("value", "value", { unique: false });
			objectStoreSkuX001.createIndex("name", "name", { unique: false });
			objectStoreSkuX001.createIndex("price", "precio", { unique: false });
			objectStoreSkuX001.createIndex("predeterminado", "predeterminado", { unique: false });

			const objectStoreConceptX001  = db.createObjectStore('objListaProductosConceptosX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreConceptX001.createIndex("companyComponentConceptID", "companyComponentConceptID", { unique: false });
			objectStoreConceptX001.createIndex("companyID", "companyID", { unique: false });
			objectStoreConceptX001.createIndex("componentID", "componentID", { unique: false });
			objectStoreConceptX001.createIndex("componentItemID", "componentItemID", { unique: false });
			objectStoreConceptX001.createIndex("isActive", "isActive", { unique: false });
			objectStoreConceptX001.createIndex("valueIn", "valueIn", { unique: false });
			objectStoreConceptX001.createIndex("valueOut", "valueOut", { unique: false });



			const objectStoreCustomerCreditLineX001  = db.createObjectStore('objListaCustomerCreditLineX001' , { keyPath : 'id',autoIncrement: true } );
			objectStoreCustomerCreditLineX001.createIndex("accountNumber","accountNumber", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("balance","balance", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("branchID","branchID", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("companyID","companyID", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("creditLineID","creditLineID", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("currencyID","currencyID", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("currencyName","currencyName", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("customerCreditLineID","customerCreditLineID", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("dateLastPay","dateLastPay", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("dateOpen","dateOpen", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("entityID","entityID", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("interestPay","interestPay", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("interestYear","interestYear", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("isActive","isActive", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("limitCredit","limitCredit", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("line","line", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("note","note", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("periodPay","periodPay", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("periodPayLabel","periodPayLabel", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("statusID","statusID", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("statusName","statusName", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("term","term", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("totalDefeated","totalDefeated", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("totalPay","totalPay", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("typeAmortization","typeAmortization", { unique: false });
			objectStoreCustomerCreditLineX001.createIndex("typeAmortizationLabel","typeAmortizationLabel", { unique: false });

		};
		//...
	}
	
	function indexDBRemoveTable(varTable){
		const request 		= db.transaction(varTable, 'readwrite')
							  .objectStore(varTable)
							  .clear();

		request.onsuccess 	= ()=> {
			console.info("success");
		}

		request.onerror = (err)=> {
			console.log('error');
		}
	}
	
	function indexDBAddTable(varTable,varDatos){
		const transaction = db.transaction(varTable, 'readwrite');



		const objectStore = transaction.objectStore(varTable);

		// Se agrega un nuevo estudiante
		for ( var intx = 0 ; intx < varDatos.length ; intx++)
		{
		objectStore.add(varDatos[intx]);
        }

		transaction.oncomplete = function(event) {
			//...
		};

		transaction.onerror = function(event) {
		  //...
		};

		//request.onsuccess = ()=> {
		//	console.log('success');
		//}
		//
		//request.onerror = (err)=> {
		//	console.log('error');
		//}
	}
	
	//obtener informacion de los productos
	function indexDBObtenerProductos(){

		/*TIPO PRECIO 1 -- 154 -- PUBLICO */		
		Ext.Ajax.request({
			url		: "<?php echo base_url(); ?>/app_invoice_api/getViewApi/<?php echo $objComponentItem->componentID; ?>/onCompleteNewItem/SELECCIONAR_ITEM_BILLING_BACKGROUND/"+ encodeURI('{"warehouseID"|"'+ miVentanaPrincipal.down("#txtWarehouseID").getValue() +'"{}"listPriceID"|"<?php echo $objListPrice->listPriceID; ?>"{}"typePriceID"|"'+154+'"}'),  
			method	: 'GET',             // o 'POST'
			params	: {                  // parámetros opcionales
				filtro: 'ABC'
			},
			success: function(response, opts) {
				
				
				// response.responseText contiene la respuesta en texto
				var datos = Ext.decode(response.responseText); // parse JSON
				console.log('Datos recibidos indexDBObtenerProductos:', datos);
				
				
				console.info("fnFillListaProductos success data");
				var objListaProductos 			= datos.objGridView;


				indexDBRemoveTable("objListaProductosX001");
				indexDBAddTable("objListaProductosX001",objListaProductos);
				
				
				Ext.Ajax.request({
					url		: "<?php echo base_url(); ?>/app_inventory_api/getSkuAllProduct",
					method	: 'GET',             // o 'POST'
					async: false,  // bloquea el hilo
					params	: {                  // parámetros opcionales
						filtro: 'ABC'
					},
					success: function(response, opts) {
						
						// response.responseText contiene la respuesta en texto
						var datos = Ext.decode(response.responseText); // parse JSON
						console.log('Datos recibidos:', datos);
						
						
						console.info("fnFillListaItemSku success data");
						indexDBRemoveTable("objListaProductosSkuX001");
						indexDBAddTable("objListaProductosSkuX001",datos.objListItemSku);	
						
						
						
					},
					failure: function(response, opts) {
						Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
						console.log('Server-side failure with status code ' + response.status);
					}
				});
				
				
				Ext.Ajax.request({
					url		: "<?php echo base_url(); ?>/core_concept_api/getConceptAllProduct",
					method	: 'GET',             // o 'POST'
					async: false,  // bloquea el hilo
					params	: {                  // parámetros opcionales
						filtro: 'ABC'
					},
					success: function(response, opts) {
						
						// response.responseText contiene la respuesta en texto
						var datos = Ext.decode(response.responseText); // parse JSON
						console.log('Datos recibidos:', datos);
						
						console.info("fnFillListaItemConcept success data");
						indexDBRemoveTable("objListaProductosConceptosX001");
						indexDBAddTable("objListaProductosConceptosX001",datos.objGridView);
					},
					failure: function(response, opts) {
						Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
						console.log('Server-side failure with status code ' + response.status);
					}
				});

					
				// Cerrar ventana modal
				miVentanaEsperando.close();
				Ext.Msg.alert('Éxito', '<span style="color:green;font-weight:bold;">Datos actualizados correctamente</span>');
			
				
				
			},
			failure: function(response, opts) {
				Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
				console.log('Server-side failure with status code ' + response.status);
			}
		});



	}
	
	function indexDBGetLocalProductoByBarra(valorBuscar, callbackFinal) {
    
		// 1️⃣ Buscar en objListaProductosX001 por contiene
		const store1 = db.transaction("objListaProductosX001", "readonly")
						 .objectStore("objListaProductosX001")
						 .index("Barra");

		store1.getAll().onsuccess = function(e) {

			const all = e.target.result;
			const buscar = String(valorBuscar).toLowerCase();

			// Filtro LIKE %valor%
			const productos = all.filter(item => {
				const campo = String(item["Barra"] || "").toLowerCase();
				return campo.includes(buscar);
			});

			// Si no hay nada → terminar
			if (productos.length === 0) {
				callbackFinal({
					productos: [],
					conceptos: [],
					skus: []
				});
				return;
			}

			// EXTRAER itemID para las búsquedas siguientes
			const listaItemID = productos.map(x => x.itemID);

			//---------------------------------------------------------
			// 2️⃣ Buscar en objListaProductosConceptosX001 EXACTO
			//---------------------------------------------------------
			const store2 = db.transaction("objListaProductosConceptosX001", "readonly")
							 .objectStore("objListaProductosConceptosX001")
							 .index("componentItemID");

			store2.getAll().onsuccess = function(e2) {

				const allConceptos = e2.target.result;

				const conceptos = allConceptos.filter(c =>
					listaItemID.includes(c.componentItemID)
				);

				//---------------------------------------------------------
				// 3️⃣ Buscar en objListaProductosSkuX001 EXACTO
				//---------------------------------------------------------
				const store3 = db.transaction("objListaProductosSkuX001", "readonly")
								 .objectStore("objListaProductosSkuX001")
								 .index("itemID");

				store3.getAll().onsuccess = function(e3) {

					const allSkus = e3.target.result;

					const skus = allSkus.filter(s =>
						listaItemID.includes(s.itemID)
					);

					//---------------------------------------------------------
					// 4️⃣ DEVOLVER RESULTADO FINAL
					//---------------------------------------------------------
					callbackFinal({
						productos: productos,
						conceptos: conceptos,
						skus: skus
					});
				};
			};
		};
	}
	
	function indexDBGetLocalProductoByItemID(valorBuscar, callbackFinal) 
	{

		// Convertir valor a número si aplica (según tu BD)
		const valorExacto = String(valorBuscar).trim();

		//---------------------------------------------------------
		// 1️⃣ Buscar PRODUCTOS por itemID (EQUAL)
		//---------------------------------------------------------

		const store1 = db.transaction("objListaProductosX001", "readonly")
						 .objectStore("objListaProductosX001")
						 .index("itemID");

		store1.getAll().onsuccess = function (e) {

			const all = e.target.result;

			// FILTRO POR IGUAL (EQUAL)
			const productos = all.filter(item =>
				String(item["itemID"]) === valorExacto
			);

			// Si no hay nada → terminar
			if (productos.length === 0) {
				callbackFinal({
					productos: [],
					conceptos: [],
					skus: []
				});
				return;
			}

			// EXTRAER itemID para las búsquedas siguientes
			const listaItemID = productos.map(x => x.itemID);

			//---------------------------------------------------------
			// 2️⃣ Buscar CONCEPTOS por componentItemID (EQUAL)
			//---------------------------------------------------------

			const store2 = db.transaction("objListaProductosConceptosX001", "readonly")
							 .objectStore("objListaProductosConceptosX001")
							 .index("componentItemID");

			store2.getAll().onsuccess = function (e2) {

				const allConceptos = e2.target.result;

				const conceptos = allConceptos.filter(c =>
					listaItemID.includes(c.componentItemID)
				);

				//---------------------------------------------------------
				// 3️⃣ Buscar SKUS por itemID (EQUAL)
				//---------------------------------------------------------

				const store3 = db.transaction("objListaProductosSkuX001", "readonly")
								 .objectStore("objListaProductosSkuX001")
								 .index("itemID");

				store3.getAll().onsuccess = function (e3) {

					const allSkus = e3.target.result;

					const skus = allSkus.filter(s =>
						listaItemID.includes(s.itemID)
					);

					//---------------------------------------------------------
					// 4️⃣ DEVOLVER RESULTADO FINAL
					//---------------------------------------------------------

					callbackFinal({
						productos: productos,
						conceptos: conceptos,
						skus: skus
					});
				};
			};
		};
	}



	function indexDBGetLocalConceptos(valorBuscar, callbackFinal) {
		const store = db.transaction("objListaProductosConceptosX001", "readonly")
						.objectStore("objListaProductosConceptosX001")
						.index("componentItemID");

		store.getAll().onsuccess = function(e) {

			const all = e.target.result;

			// BÚSQUEDA EXACTA
			const conceptos = all.filter(item => 
				String(item["componentItemID"]) === String(valorBuscar)
			);

			callbackFinal(conceptos);
		};
	}

	
	function fnClearData()
	{
		console.info("fnClearData");
		
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(viewport){
			var grid = viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			grid.getStore().loadData([]);
	
	
			viewport.down("#txtSubTotal").setValue("0.00");
			viewport.down("#txtDescuento").setValue("0.00");
			viewport.down("#txtPorcentajeDescuento").setValue("0.00");
			viewport.down("#txtIva").setValue("0.00");
			viewport.down("#txtServices").setValue("0.00");
			viewport.down("#txtTotal").setValue("0.00");
		}
		
		var viewport_miVentanaDePago = Ext.getCmp('miVentanaDePago'); // accede al viewport		
		if (viewport_miVentanaDePago) {
			viewport_miVentanaDePago.down("#txtReceiptAmount").setValue("0.00");
			viewport_miVentanaDePago.down("#txtReceiptAmountDol").setValue("0.00");
			viewport_miVentanaDePago.down("#txtChangeAmount").setValue("0.00");
			viewport_miVentanaDePago.down("#txtReceiptAmountBank").setValue("0.00");
			viewport_miVentanaDePago.down("#txtReceiptAmountPoint").setValue("0.00");
			viewport_miVentanaDePago.down("#txtReceiptAmountTarjeta").setValue("0.00");
			viewport_miVentanaDePago.down("#txtReceiptAmountTarjetaDol").setValue("0.00");
			viewport_miVentanaDePago.down("#txtReceiptAmountBankDol").setValue("0.00");
		}
		
		console.info("fnClearData success");
		
	}
	
	
	function fnLockPayment()
	{	
		if(isLoading) return ;
		
		console.info("fnLockPayment");
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(viewport){
			var invoiceTypeCredit 							= false;
			var causalSelect 								= viewport.down("#txtCausalID").getValue();
			
			var causalCredit 								= objCausalTypeCredit.value.split(",");
			var lockPayment									= <?php echo getBahavioDB($company->type, "app_invoice_billing", "lockPayment", "false"); ?>;
			//Obtener si la factura es al credito
			for(var i=0;i<causalCredit.length;i++)
			{
				if(causalCredit[i] === causalSelect)
				{
					invoiceTypeCredit 						= true;
				}
			}
			
			if(invoiceTypeCredit && lockPayment)
			{				
				var viewport_miVentanaDePago = Ext.getCmp('miVentanaDePago'); // accede al viewport		
				if (viewport_miVentanaDePago) {
					viewport_miVentanaDePago.down("#txtReceiptAmount").setReadOnly(true);
					viewport_miVentanaDePago.down("#txtReceiptAmountDol").setReadOnly(true);
					viewport_miVentanaDePago.down("#txtChangeAmount").setReadOnly(true);
					viewport_miVentanaDePago.down("#txtReceiptAmountBank").setReadOnly(true);
					viewport_miVentanaDePago.down("#txtReceiptAmountPoint").setReadOnly(true);
					viewport_miVentanaDePago.down("#txtReceiptAmountTarjeta").setReadOnly(true);
					viewport_miVentanaDePago.down("#txtReceiptAmountTarjetaDol").setReadOnly(true);
					viewport_miVentanaDePago.down("#txtReceiptAmountBankDol").setReadOnly(true);
				}
				
			}
			if(!invoiceTypeCredit && lockPayment)
			{
				var viewport_miVentanaDePago = Ext.getCmp('miVentanaDePago'); // accede al viewport		
				if (viewport_miVentanaDePago) {
					viewport_miVentanaDePago.down("#txtReceiptAmount").setReadOnly(false);
					viewport_miVentanaDePago.down("#txtReceiptAmountDol").setReadOnly(false);
					viewport_miVentanaDePago.down("#txtChangeAmount").setReadOnly(false);
					viewport_miVentanaDePago.down("#txtReceiptAmountBank").setReadOnly(false);
					viewport_miVentanaDePago.down("#txtReceiptAmountPoint").setReadOnly(false);
					viewport_miVentanaDePago.down("#txtReceiptAmountTarjeta").setReadOnly(false);
					viewport_miVentanaDePago.down("#txtReceiptAmountTarjetaDol").setReadOnly(false);
					viewport_miVentanaDePago.down("#txtReceiptAmountBankDol").setReadOnly(false);
				}
			}
			
		
		}
		
		console.info("fnLockPayment fin");
	}
	
	function fnGetCustomerClient(){		
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(viewport){
			var entityID = viewport.down("#txtCustomerID").getValue();
			Ext.Ajax.request({
				url		: "<?php echo base_url(); ?>/app_invoice_api/getLineByCustomer",
				method	: 'POST',             // o 'POST'
				params	: {                  // parámetros opcionales
					entityID: entityID
				},
				success: function(response, opts) {
					
					var data = Ext.decode(response.responseText); // parse JSON
					console.log('Datos recibidos:', data);
					fnRenderLineaCredit(data.objListCustomerCreditLine,data.objCausalTypeCredit);				
					
				},
				failure: function(response, opts) {
					Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
					console.log('Server-side failure with status code ' + response.status);
				}
			});
		}
	}
	
	function fnRenderLineaCredit(listCustomerCreditLine,causalTypeCredit)
	{
		objListCustomerCreditLine 	= listCustomerCreditLine;
		objCausalTypeCredit 		= causalTypeCredit;
		
		
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(viewport){
			
		}
		
		var viewport_miVentanaDePago = Ext.getCmp('miVentanaDePago'); // accede al viewport		
		if (viewport_miVentanaDePago) {
		}
		
		
		//Llenar las lineas de credito
		var field = viewport.down("#txtCustomerCreditLineID");
		field.clearValue();
		field.getStore().removeAll();
		
		if(objListCustomerCreditLine != null)
		{
			var elementItem = [];
			for(var i = 0; i< objListCustomerCreditLine.length;i++)
			{
				elementItem.push({name: objListCustomerCreditLine[i].accountNumber + " " +objListCustomerCreditLine[i].line , id: objListCustomerCreditLine[i].customerCreditLineID });
			}
			
			field.getStore().loadData(elementItem);
		}
		
		//Si tienes linea de credito activar el tipo contado y credito
		var fieldTipoFactura		= viewport.down("#txtCausalID");
		var listArrayCausalCredit 	= objCausalTypeCredit.value.split(",");
		var store 					= fieldTipoFactura.getStore();
		
		store.each(function(record){		
			var causalID = record.get('id'); 
			if (listArrayCausalCredit.indexOf(causalID) !== -1) {
				if (objListCustomerCreditLine.length > 0) {
					record.set('disabled', false);
				} else {
					record.set('disabled', true);
				}
			} else {
				record.set('disabled', false);
			}
		});
		
		fieldTipoFactura.setDisabled(false); 
		fieldTipoFactura.bindStore(store);
		
			
		
		fnLockPayment();
		miVentanaEsperando.hide();
		
	}
	function fnRenderLineaCreditoDiv()
	{
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(viewport){
			
		}
		
		var viewport_miVentanaDePago = Ext.getCmp('miVentanaDePago'); // accede al viewport		
		if (viewport_miVentanaDePago) {
		}
		
		
		//Si es de credito que la factura no supere la linea de credito
		var causalSelect 				= viewport.down("#txtCausalID").getValue();
		var customerCreditLineID 		= viewport.down("#txtCustomerCreditLineID").getValue();
		var causalCredit 				= objCausalTypeCredit.value.split(",");
		var invoiceTypeCredit 			= false;

		//Obtener si la factura es al credito
		for(var i=0;i<causalCredit.length;i++){
			if(causalCredit[i] == causalSelect){
				invoiceTypeCredit = true;
			}
		}

		if(invoiceTypeCredit ){			
			var field = viewport.down("#txtCustomerCreditLineID");
			field.setVisible(true);
		}
		else{
			var field = viewport.down("#txtCustomerCreditLineID");
			field.setVisible(false);
		}
		
	}
	function fnActualizarPrecio()
	{
		console.info("fnActualizarPrecio");		
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(viewport){
			
		}
		
		var varStatusIDOld = viewport.down("#txtStatusIDOld").getValue();
		if(varStatusIDOld ==  varStatusInvoiceAplicado)
				return;
			
		if(varStatusIDOld ==  varStatusInvoiceRegistrado)
				return;
			
		var typePriceID 	= viewport.down("#txtTypePriceID").getValue();
		var grid 			= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
		var store 			= grid.getStore();
		
		store.each(function (record) {
			
			var precio		= 0;
			if(typePriceID == "154" /*precio1*/)
				precio = record.get('price1');
			else if(typePriceID == "155" /*precio2*/)
				precio = record.get('price2');
			else /*precio3*/
				precio = record.get('price3');
				
			record.set('price',  precio);
		});
		
		console.info("fnActualizarPrecio fin");
		fnRecalculateDetail(false,"");
		
	}
	
	function fnRecalculateDetail(clearRecibo,tipo_calculate, index=-1)
	{
		
		
		console.info("fnRecalculateDetail");
		
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		var viewport_miVentanaDePago = Ext.getCmp('miVentanaDePago'); // accede al viewport		
		if (!viewport_miVentanaDePago)
			return;
		
		
		var porcentajeDescuento 						= viewport.down('#txtPorcentajeDescuento').getValue() || 0;
		var totalGeneral 								= 0;
		var despuesDeRecalcularTotalRecibidoIgualCero	= <?php echo getBehavio($company->type, "app_invoice_billing", "despuesDeRecalcularTotalRecibidoIgualCero", "false"); ?>;
		var invoiceTypeCredit 							= false;

        if(index == -1 && tipo_calculate != "sumarizar")
		{
			var cantidad 				= 0;
			var iva 					= 0;
			var taxServices				= 0;
			var precio					= 0;
			var subtotal 				= 0;
			var total 					= 0;
			var cantidadGeneral 		= 0;
			var ivaGeneral 				= 0;
			var serviceGeneral			= 0;
			var precioGeneral			= 0;
			var subtotalGeneral 		= 0;			
			let skuRatio				= 0;
			var priceTemporal			= 0;
			var cantidadTemporal		= 0;
			var descuento				= 0;
			
			
			var grid 			= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 			= grid.getStore();
			store.each(function (record) {
				
				
				cantidad 	= record.get('txtTMD_txtQuantity');
				precio 		= record.get('txtTMD_txtPrice');
				iva 		= record.get('txtTMD_txtIva');
				taxServices	= record.get('txtTMD_txtTaxServices');
				skuRatio    = record.get('txtTMD_txtRatioSku');

				subtotal    = precio * cantidad;
				iva 		= (precio * cantidad) * iva;
				taxServices = (precio * cantidad) * taxServices;
				total 		= iva + taxServices + subtotal ;
				descuento	= subtotal * (porcentajeDescuento / 100);


				cantidadGeneral 	= cantidadGeneral + cantidad;
				precioGeneral 		= precioGeneral + precio;
				ivaGeneral 			= ivaGeneral + iva;
				serviceGeneral		= serviceGeneral + taxServices;
				subtotalGeneral 	= subtotalGeneral + subtotal;
				totalGeneral 		= totalGeneral + total;
				let skuQuantityBySku= cantidad * skuRatio;
				
				
				record.set('txtTMD_skuQuantityBySku',  skuQuantityBySku);
				record.set('txtTMD_txtSubTotal',  subtotal);
				record.set('txtTMD_txtDiscountByItem',  descuento);
				
			});
			
			
			
			descuento 		= subtotalGeneral * (porcentajeDescuento / 100);
			totalGeneral 	= subtotalGeneral + serviceGeneral + ivaGeneral - descuento;

			viewport.down("#txtSubTotal").setValue(subtotalGeneral);
			viewport.down("#txtDescuento").setValue(descuento);
			viewport.down("#txtIva").setValue(ivaGeneral);
			viewport.down("#txtServices").setValue(serviceGeneral);
			viewport.down("#txtTotal").setValue(totalGeneral);

			//Si es de credito que la factura no supere la linea de credito
			var causalSelect 				= viewport.down("#txtCausalID").getValue();
			var customerCreditLineID 		= viewport.down("#txtCustomerCreditLineID").getValue();
			var objCustomerCreditLine 		= Ext.Array.filter(objListCustomerCreditLine, function (obj) { return obj.customerCreditLineID === customerCreditLineID;});
			var causalCredit 				= objCausalTypeCredit.value.split(",");

			//Obtener si la factura es al credito
			for(var i=0;i<causalCredit.length;i++){
				if(causalCredit[i] === causalSelect){
					invoiceTypeCredit = true;
				}
			}
		}

		if(index > -1 && tipo_calculate != "sumarizar")
		{
			
			var grid 						= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 						= grid.getStore();		
			
			var cantidadTemporal 	 		=  store.getAt(index).get("txtTMD_txtQuantity");
			var priceTemporal  		 		=  store.getAt(index).get("txtTMD_txtPrice");
			store.getAt(index).set("txtTMD_txtQuantity",cantidadTemporal);
			store.getAt(index).set("txtTMD_txtPrice",priceTemporal);
			
			
			
			var cantidad 				= store.getAt(index).get("txtTMD_txtQuantity");
			var skuRatio    			= store.getAt(index).get("txtTMD_txtRatioSku"); 
			let skuQuantityBySku 		= cantidad * skuRatio;
			store.getAt(index).set("txtTMD_skuQuantityBySku",skuQuantityBySku);
			
			
			var precio 					= store.getAt(index).get("txtTMD_txtPrice"); 
			var subtotal    			= precio * cantidad;
			store.getAt(index).set("txtTMD_txtSubTotal",subtotal);
			
			var descuento				= subtotal * (porcentajeDescuento / 100);
			store.getAt(index).set("txtTMD_txtDiscountByItem",descuento);
			
			
			var grid 						= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 						= grid.getStore();	
			var subtotalGeneral 			= 0;
			var descuento		 			= 0;	
			var ivaGeneral		 			= 0;
			var serviceGeneral		 		= 0;	
			totalGeneral				    = 0;
			
			store.each(function(record){
				subtotalGeneral += parseFloat(record.get('txtTMD_txtSubTotal')) || 0;
			});
			store.each(function(record){
				descuento += parseFloat(record.get('txtTMD_txtDiscountByItem')) || 0;
			});
			store.each(function(record){
				ivaGeneral += parseFloat(record.get('txtTMD_txtIva')) || 0;
			});
			store.each(function(record){
				serviceGeneral += parseFloat(record.get('txtTMD_txtTaxServices')) || 0;
			});
			
			totalGeneral				    = subtotalGeneral + ivaGeneral + serviceGeneral - descuento;			
			viewport.down("#txtSubTotal").setValue(subtotalGeneral);
			viewport.down("#txtDescuento").setValue(descuento);
			viewport.down("#txtIva").setValue(ivaGeneral);
			viewport.down("#txtServices").setValue(serviceGeneral);
			viewport.down("#txtTotal").setValue(totalGeneral);
			
			
			var causalSelect 				= viewport.down("#txtCausalID").getValue();
			var causalCredit 				= objCausalTypeCredit.value.split(",");
			//Obtener si la factura es al credito
			for(var i=0;i<causalCredit.length;i++){
				if(causalCredit[i] === causalSelect){
					invoiceTypeCredit = true;
				}
			}
			
		}
		
		if(tipo_calculate == "sumarizar")
		{
			var grid 						= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 						= grid.getStore();			
				
			var subtotalGeneral 			= 0;
			var descuento		 			= 0;	
			var ivaGeneral		 			= 0;
			var serviceGeneral		 		= 0;	
			totalGeneral				    = 0;			
			
			store.each(function(record){
				subtotalGeneral += parseFloat(record.get('txtTMD_txtSubTotal')) || 0;
			});

			store.each(function(record){
				descuento += parseFloat(record.get('txtTMD_txtDiscountByItem')) || 0;
			});

			store.each(function(record){
				ivaGeneral += parseFloat(record.get('txtTMD_txtIva')) || 0;
			});

			store.each(function(record){
				serviceGeneral += parseFloat(record.get('txtTMD_txtTaxServices')) || 0;
			});

			totalGeneral				    = subtotalGeneral + ivaGeneral + serviceGeneral - descuento;			
			viewport.down('#txtSubTotal').setValue(subtotalGeneral);
			viewport.down('#txtDescuento').setValue(descuento);
			viewport.down('#txtIva').setValue(ivaGeneral);
			viewport.down('#txtServices').setValue(serviceGeneral);
			viewport.down('#txtTotal').setValue(totalGeneral);
			
		}

		
		if(invoiceTypeCredit === true){
			viewport_miVentanaDePago.down("#txtReceiptAmount").setValue("0.00");
		}
		else if(despuesDeRecalcularTotalRecibidoIgualCero == true)
		{
			viewport_miVentanaDePago.down("#txtReceiptAmount").setValue("0.00");
		}
		else{			
			viewport_miVentanaDePago.down("#txtReceiptAmount").setValue(totalGeneral);
		}
		
		if (clearRecibo)
		{
				viewport_miVentanaDePago.down("#txtReceiptAmountDol").setValue("0.00");
				viewport_miVentanaDePago.down("#txtChangeAmount").setValue("0.00");
				viewport_miVentanaDePago.down("#txtReceiptAmountBank").setValue("0");
				viewport_miVentanaDePago.down("#txtReceiptAmountPoint").setValue("0");
				viewport_miVentanaDePago.down("#txtReceiptAmountTarjeta").setValue("0");
				viewport_miVentanaDePago.down("#txtReceiptAmountTarjetaDol").setValue("0");
				viewport_miVentanaDePago.down("#txtReceiptAmountBankDol").setValue("0");
		}
		console.info("fnRecalculateDetail fin ");
	}
	
	
	
	function fnRecalcularMontoComision(monto) 
	{
		
		if(isLoading) return ;
		console.info("fnRecalcularMontoComision");
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
		var store 		= grid.getStore();		
		if (isNaN(monto))
		{
			monto = 0;
		}

		if(monto == 0)
			return;
		
		store.each(function (record) {
			let oldPrice = record.get("txtTMD_txtSubTotal");
			let newPrice = oldPrice * ( monto / 100 );
			record.set("txtTMD_txtCommisionByBankByItem",newPrice);
		});
		
		
		
		console.info("fnRecalcularMontoComision Fin");
		
	}
	
	
	function fnCalculateAmountPay()
	{
		
		console.info("fnRecalcularMontoComision");
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
		var store 		= grid.getStore();			
		

		var viewport_miVentanaDePago = Ext.getCmp('miVentanaDePago'); // accede al viewport		
		if (!viewport_miVentanaDePago)
			return;
		
		
		
        let resultTotal     = 0.0;
        let currencyId      = viewport.down("#txtCurrencyID").getValue();
        let ingresoCordoba 	= viewport_miVentanaDePago.down("#txtReceiptAmount").getValue();
        let bancoCordoba 	= viewport_miVentanaDePago.down("#txtReceiptAmountBank").getValue();
        let puntoCordoba 	= viewport_miVentanaDePago.down("#txtReceiptAmountPoint").getValue();
        let tarjetaCordoba 	= viewport_miVentanaDePago.down("#txtReceiptAmountTarjeta").getValue();
        let tarejtaDolares 	= viewport_miVentanaDePago.down("#txtReceiptAmountTarjetaDol").getValue();
        let bancoDolares 	= viewport_miVentanaDePago.down("#txtReceiptAmountBankDol").getValue();
        let ingresoDol 	    = viewport_miVentanaDePago.down("#txtReceiptAmountDol").getValue();
        let tipoCambio 	    = viewport.down("#txtExchangeRate").getValue();
        let total 		    = viewport.down("#txtTotal").getValue();
        if( currencyId === "1" /*Cordoba*/ )
		{
            resultTotal =  
				(
					parseFloat(ingresoCordoba) +  
					parseFloat(bancoCordoba) + 
					parseFloat(puntoCordoba) + 
					parseFloat(tarjetaCordoba) + 
					( 
						parseFloat(bancoDolares) / 
						parseFloat(tipoCambio) 
					) + 
					( 
						parseFloat(tarejtaDolares) / 
						parseFloat(tipoCambio) 
					)  + 
					(
						parseFloat(ingresoDol)  * 
						((1 / parseFloat(tipoCambio)).toFixed(2) * 1)
					)
				) - parseFloat(total);
		}else if( currencyId === "2" /*dolares*/ )
		{
			resultTotal =  
				(
					parseFloat(ingresoCordoba) +  
					parseFloat(bancoCordoba) + 
					parseFloat(puntoCordoba) + 
					parseFloat(tarjetaCordoba) + 
					( 
						parseFloat(bancoDolares) * 
						parseFloat(tipoCambio) 
					) + 
					( 
						parseFloat(tarejtaDolares) * 
						parseFloat(tipoCambio) 
					)  + 
					(
						parseFloat(ingresoDol) * 
						parseFloat(tipoCambio)
					)
				) - parseFloat(total);
		}

        resultTotal = resultTotal;
        viewport_miVentanaDePago.down("#txtChangeAmount").setValue(resultTotal);
    }
	
	

	function onCompleteNewItem(filterResult,suma,conceptos,skus){
		console.info("CALL onCompleteNewItem");
		
		
		
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		
		
		var record = {			
			txtTMD_checked: false,
			txtTMD_txtTransactionMasterDetailID: 0,
			txtTMD_txtItemID: filterResult.itemID,
			txtTMD_txtTransactionDetailItemNumber: filterResult.Codigo,
			txtTMD_txtTransactionDetailName: filterResult.Nombre,
			txtTMD_txtSku: filterResult.Medida,
			txtTMD_txtQuantity: 1,
			txtTMD_txtPrice: filterResult.Precio,
			txtTMD_txtSubTotal: filterResult.Precio ,
			txtTMD_txtIva: 0,
			txtTMD_skuQuantityBySku: 0,
			txtTMD_unitaryPriceInvidual: 0,
			txtTMD_skuFormatoDescription: filterResult.Medida,
			txtTMD_txtItemPrecio2: filterResult.Precio2,
			txtTMD_txtItemPrecio3: filterResult.Precio3,
			txtTMD_txtTransactionDetailNameDescription: "",
			txtTMD_txtTaxServices: 0,
			txtTMD_txtDetailLote: 0,
			txtTMD_txtInfoVendedor: 0,
			txtTMD_txtInfoSerie: "",
			txtTMD_txtInfoReferencia: "",
			txtTMD_txtItemPrecio1: filterResult.Precio,
			txtTMD_txtCatalogItemIDSku: filterResult.unitMeasureID,
			txtTMD_txtRatioSku: 1, //?
			txtTMD_txtDiscountByItem: 0,
			txtTMD_txtCommisionByBankByItem: 0
		};
		
		
		
		let itemID 			= filterResult.itemID;
		let allData 		= skus;
		let priceDefault	= record.txtTMD_txtPrice;
		
		
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
		var store 		= grid.getStore();	
	
		var comboStoreData  = [];
		var resultado 		=  Ext.Array.filter(allData, function (producto) {
			return producto.itemID == record.txtTMD_txtItemID;
		});
		
		
		if (resultado.length > 0) 
		{
			Ext.Array.each(resultado, function (producto) {
				let selected = parseInt(producto.predeterminado, 10);
				if(selected === 1){
					console.info(producto);
					record.txtTMD_txtCatalogItemIDSku				=producto.catalogItemID;
					record.txtTMD_txtSku							=producto.catalogItemID;							
					record.txtTMD_skuFormatoDescription				=producto.name;
					record.txtTMD_txtPrice 							=producto.price;
					record.txtTMD_txtRatioSku 						=producto.value;
				}
				
				comboStoreData.push({
					id: 	producto.catalogItemID,
					name: 	producto.name
				});
			});
		}
		
		
		if (parseInt(record.txtTMD_txtPrice) == 0)
		{
			record.txtTMD_txtPrice = priceDefault;
		}
		
		
		var newRecord 				= store.insert(0, record)[0];
		//var newRecord 			= store.add(record)[0];
		
		//Buscar la columna combobox
		var comboColumn = null;
		Ext.Array.each(grid.columns, function(col) {
			if (col.dataIndex === 'txtTMD_txtSku') {
				comboColumn = col;
				return false; // rompe el loop
			}
		});
		if (!comboColumn) {
			console.error('No se encontró la columna txtTMD_txtSku');
			return;
		}

		// Crear un store de Ext JS
		var storeForCombo = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data: comboStoreData
		});

		
		//Agreagar el store al combobox
		Ext.defer(function() 
		{
			var comboWidget = comboColumn.getWidget(newRecord);
			if (comboWidget) {				
				comboWidget.bindStore(storeForCombo); // vincula el store
				

				if (comboStoreData.length > 0) {
					comboWidget.setValue(comboStoreData[0].id );
					newRecord.set('txtTMD_txtSku', comboStoreData[0].id );
				}
			}
			
			
			console.info("fnGetConcept");
			var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
			if(!viewport){
				return;
			}
			
			
			var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 		= grid.getStore();			
			var records		= store.queryBy(function(record){ return record.get('txtTMD_txtItemID') == filterResult.itemID; }).items;
			fnGetConcept( filterResult.itemID, "ALL");
			
			
			
		}, 50); // pequeño delay para que el widget exista
		
		

		viewport.down("#txtScanerCodigo").focus(false, 200);
		console.info("fin onCompleteNewItem");
	}
	
	
	//el objeto pasado como primer parametro , se retorna un objeto, nuevo, basado en la configuracion
	//que se pasa como segundo parametro
	function fnMapObjectToNew(objOrigen, mappingJson) {
		var objDestino = {};

		Ext.Object.each(mappingJson, function(origenKey, destinoKey) {
			if (objOrigen.hasOwnProperty(origenKey)) {
				objDestino[destinoKey] = objOrigen[origenKey];
			}
		});

		return objDestino;
	}
	
	function fnMapObjectBack(objDestino, mappingJsonInverse) {
		var objOrigen = {};

		Ext.Object.each(mappingJsonInverse, function(destKey, origenKey) {
			if (objDestino.hasOwnProperty(destKey)) {
				objOrigen[origenKey] = objDestino[destKey];
			}
		});

		return objOrigen;
	}
	
	function fnMapInvertMapping(mappingJson) {
		var inverted = {};

		Ext.Object.each(mappingJson, function(key, value) {
			inverted[value] = key;
		});

		return inverted;
	}
	
	// Obtener el valor seleccionado
	function getValueRadio(formulario,field) {
		
		var ventana = Ext.getCmp(formulario);
	
		// Obtener todos los radios con el mismo name
		var radios = ventana.query('radiofield[name=' + field + ']');

		// Buscar el que está seleccionado
		for (var i = 0; i < radios.length; i++) {
			if (radios[i].getValue() === true) {
				return radios[i].inputValue;
			}
		}

		return null; // Ninguno seleccionado
		
	}

	// Setear el valor del radio
	function setValueRadio(formulario, field, valor) {
		var ventana = Ext.getCmp(formulario);
		if (!ventana) return;

		// Obtener todos los radios con el mismo name
		var radios = ventana.query('radiofield[name=' + field + ']');

		// Recorrer y setear valor
		for (var i = 0; i < radios.length; i++) {
			if (radios[i].inputValue == valor) {
				radios[i].setValue(true);  // Selecciona el radio
			} else {
				radios[i].setValue(false); // Desmarca los demás
			}
		}
	}
	
	function fnGetConcept(conceptItemID,nameConcept)
	{
		
		console.info("fnGetConcept");
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		
		
		var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
		var store 		= grid.getStore();			
		var records		= store.queryBy(function(record){ return record.get('txtTMD_txtItemID') == conceptItemID; }).items;

		
		//Obtener el concepto de la base de datos del navegador y calcular nuevamente
		indexDBGetLocalConceptos(conceptItemID,function (e){
			var objConcepto = e;
			var exoneracion = getValueRadio("miVentanaPrincipal","txtCheckApplyExoneracion");
			 
			
			///si exoneracion es igual a 0 o no esta exonerado, 
			//calcular impuestos				
			if(exoneracion == "0" )
			{
				objConcepto1 	= Ext.Array.filter(objConcepto, function(obj){ return obj.name === "IVA"; });
				if( objConcepto1.length > 0 )
				{
					records[0].set("txtTMD_txtIva",objConcepto1[0].valueOut);
				}
				objConcepto2 	= Ext.Array.filter(objConcepto, function(obj){ return obj.name === "TAX_SERVICES"; });
				if( objConcepto2.length > 0 )
				{
					records[0].set("txtTMD_txtTaxServices",objConcepto2[0].valueOut);
				}
			}
			else
			{
				
				records[0].set("txtTMD_txtIva",0);
				records[0].set("txtTMD_txtTaxServices",0);
			}
			
			
			fnRecalculateDetail(true,"", store.indexOf(records[0]));
			console.info("fnGetConcept fin");
		});
		
	}
	function fnLoadInvoiceExistente(transactionMasterID,codigoMesero)
	{
		var urlRequest =  baseUrl + '/app_invoice_billing/edit/2/19/' + transactionMasterID + '/' +codigoMesero;
		Ext.Ajax.request({
			url		: urlRequest,
			method	: 'GET',             // o 'POST'
			async	: true,				 // true no bloquea el hilo
			params	: {                  // parámetros opcionales
				filtro: 'ABC'
			},
			success: function(response, opts) {
				
				// response.responseText contiene la respuesta en texto
				var datos = Ext.decode(response.responseText); // parse JSON
				console.log('fnLoadInvoiceExistente');
				fnUpdateInvoiceView(datos);
				isLoading	   = false;
				
				if(window.miVentanaEsperando)
					if(miVentanaEsperando.show)
						miVentanaEsperando.hide();
			},
			failure: function(response, opts) {
				
				if(window.miVentanaEsperando)
					if(miVentanaEsperando.show)
						miVentanaEsperando.hide();
					
				Ext.Msg.alert('Error', '<span style="color:red;font-weight:bold;">No se pudieron cargar los datos</span>');
				console.log('Server-side failure with status code ' + response.status);
			}
		});
		
		
		
	}
	
	
	function fnUpdateInvoiceView(datos)
	{
		
		if(datos.success == false)
		{
			return;
		}
		
		
		var objFormulario 							= {};
		objListCustomerCreditLine					= datos.data.objListCustomerCreditLine;
		objFormulario.txtTM_transactionNumber		= datos.data.objTransactionMaster.transactionNumber;
		objFormulario.txtUserID						= datos.data.objTransactionMaster.createdBy;
		objFormulario.txtCompanyID					= datos.data.objTransactionMaster.companyID;
		objFormulario.txtTransactionID				= datos.data.objTransactionMaster.transactionID;
		objFormulario.txtTransactionMasterID		= datos.data.objTransactionMaster.transactionMasterID;
		objFormulario.txtCodigoMesero				= datos.data.codigoMesero;
		objFormulario.txtStatusID					= datos.data.objTransactionMaster.statusID;
		objFormulario.txtStatusIDOld				= datos.data.objTransactionMaster.statusID;
		objFormulario.txtDate						= datos.data.objTransactionMaster.transactionOn;
		objFormulario.txtExchangeRate				= datos.data.objTransactionMaster.exchangeRate;
		objFormulario.txtNote						= datos.data.objTransactionMaster.note;
		objFormulario.txtCurrencyID					= datos.data.objTransactionMaster.currencyID;
		objFormulario.txtCustomerID					= datos.data.objTransactionMaster.entityID;
		objFormulario.txtCustomerDescription		= datos.data.objCustomerDefault.customerNumber + " / " + datos.data.objNaturalDefault.firstName; 
		objFormulario.txtReferenceClientName		= datos.data.objTransactionMasterInfo.referenceClientName;
		objFormulario.txtReferenceClientIdentifier	= datos.data.objTransactionMasterInfo.referenceClientIdentifier;
		objFormulario.txtCausalID					= datos.data.objTransactionMaster.transactionCausalID;
		objFormulario.txtCustomerCreditLineID		= datos.data.objTransactionMaster.reference4; 
		objFormulario.txtZoneID						= datos.data.objTransactionMasterInfo.zoneID; 		
		if(datos.data.objTransactionMasterDetail.length > 0)
			objFormulario.txtTypePriceID				= datos.data.objTransactionMasterDetail[0].typePriceID; 
		else
			objFormulario.txtTypePriceID				= 0;		
		objFormulario.txtWarehouseID						= datos.data.objTransactionMaster.sourceWarehouseID; 
		objFormulario.txtReference3							= datos.data.objTransactionMaster.reference3; 
		objFormulario.txtEmployeeID							= datos.data.objTransactionMaster.entityIDSecondary; 
		objFormulario.txtNumberPhone						= datos.data.objTransactionMaster.numberPhone; 
		objFormulario.txtMesaID								= datos.data.objTransactionMasterInfo.mesaID; 
		objFormulario.txtNextVisit							= datos.data.objTransactionMaster.nextVisit; 
		objFormulario.txtDateFirst							= datos.data.objTransactionMaster.transactionOn2; 
		objFormulario.txtReference2							= datos.data.objTransactionMaster.reference2; 		
		objFormulario.txtPeriodPay							= datos.data.objTransactionMaster.periodPay; 
		objFormulario.txtReference1							= datos.data.objTransactionMaster.reference1; 
		objFormulario.txtDayExcluded						= datos.data.objTransactionMaster.dayExcluded; 
		objFormulario.txtFixedExpenses						= datos.data.objTransactionMasterDetailCredit.reference1; 
		objFormulario.txtCheckApplyExoneracion				= datos.data.objTransactionMasterReferences.reference2; 
		objFormulario.txtLayFirstLineProtocolo				= datos.data.objTransactionMasterReferences.reference1; 
		objFormulario.txtCheckDeEfectivo					= 0;
		objFormulario.txtCheckReportSinRiesgoValue			= datos.data.objTransactionMasterDetailCredit.reference2; 		
		objFormulario.txtTMIReference1						= datos.data.objTransactionMasterInfo.reference1;
		
		objFormulario.txtSubTotal							= 0;
		objFormulario.txtIva								= 0;
		objFormulario.txtPorcentajeDescuento				= datos.data.objTransactionMaster.tax4; 
		objFormulario.txtDescuento							= datos.data.objTransactionMaster.discount; 
		objFormulario.txtServices							= 0;
		objFormulario.txtTotal								= 0;
		
		objFormulario.txtChangeAmount						= datos.data.objTransactionMasterInfo.changeAmount; 
		objFormulario.txtReceiptAmount						= datos.data.objTransactionMasterInfo.receiptAmount; 		
		objFormulario.txtReceiptAmountDol					= datos.data.objTransactionMasterInfo.receiptAmountDol; 
		objFormulario.txtReceiptAmountTarjeta				= datos.data.objTransactionMasterInfo.receiptAmountCard; 
		objFormulario.txtReceiptAmountTarjeta_BankID		= datos.data.objTransactionMasterInfo.receiptAmountCardBankID; 
		objFormulario.txtReceiptAmountTarjeta_Reference		= datos.data.objTransactionMasterInfo.receiptAmountCardBankReference; 
		objFormulario.txtReceiptAmountTarjetaDol			= datos.data.objTransactionMasterInfo.receiptAmountCardDol; 
		objFormulario.txtReceiptAmountTarjetaDol_BankID		= datos.data.objTransactionMasterInfo.receiptAmountCardDolBankID; 
		objFormulario.txtReceiptAmountTarjetaDol_Reference	= datos.data.objTransactionMasterInfo.receiptAmountCardDolBankReference; 
		objFormulario.txtReceiptAmountBank					= datos.data.objTransactionMasterInfo.receiptAmountBank; 		
		objFormulario.txtReceiptAmountBank_BankID			= datos.data.objTransactionMasterInfo.receiptAmountBankID; 
		objFormulario.txtReceiptAmountBank_Reference		= datos.data.objTransactionMasterInfo.receiptAmountBankReference; 
		objFormulario.txtReceiptAmountBankDol				= datos.data.objTransactionMasterInfo.receiptAmountBankDol; 
		objFormulario.txtReceiptAmountBankDol_BankID		= datos.data.objTransactionMasterInfo.receiptAmountBankDolID; 
		objFormulario.txtReceiptAmountBankDol_Reference		= datos.data.objTransactionMasterInfo.receiptAmountBankDolReference; 
		objFormulario.txtReceiptAmountPoint					= datos.data.objTransactionMasterInfo.receiptAmountPoint; 
		
		
		
		//cargar detalle
		objFormulario.txtTransactionMasterDetail			= [];
		var typePriceID 									=  154; /*publico*/
		var varDetailReferences								= datos.data.objTransactionMasterDetailReferences;
		var varDetailConcept 								= datos.data.objTransactionMasterDetailConcept;
		var objTransactionMasterItemPrice 					= datos.data.objTransactionMasterItemPrice;
		if(datos.data.objTransactionMasterDetail.length > 0 )
		{
			for(var i = 0 ; i < datos.data.objTransactionMasterDetail.length ; i++)
			{
				
				//master detail reference
				var row 			   = datos.data.objTransactionMasterDetail[i];
				typePriceID			   = row.typePriceID;
                let objDetailReference = Ext.Array.filter(varDetailReferences, function (obj) { return obj.transactionMasterDetailID === row.transactionMasterDetailID;});
                //Obtener Iva
                var tmp_ 				= Ext.Array.filter(varDetailConcept, function (obj) {  return obj.componentItemID === row.componentItemID && obj.name === "IVA";}); 				
                var iva_ 				= (tmp_.length <= 0 ? 0 : parseFloat(tmp_[0].valueOut));
                
				//obtener el precio  2
				var resultado = Ext.Array.filter(objTransactionMasterItemPrice, function (obj) {
					return obj.itemID === row.componentItemID &&
						   obj.typePriceID === "155";
				});
				var Precio2 		= resultado.length > 0 ? resultado[0].Precio : null;


				//obtener el precio  3
				var resultado = Ext.Array.filter(objTransactionMasterItemPrice, function (obj) {
					return obj.itemID === row.componentItemID &&
						   obj.typePriceID === "156";
				});
				var Precio3 		= resultado.length > 0 ? resultado[0].Precio : null;
				
                
                var tax2					= row.tax2;
				let skuFormatoDescription   = row.skuFormatoDescription;
				let skuQuantityBySku		= row.skuQuantityBySku;
				let skuCatalogItemID		= row.skuCatalogItemID;
				let skuQuantity				= row.skuQuantity;

                var taxServices 	= 0;

                //Validar impuesto IVA
                if (  isNaN(row.tax1 / row.unitaryPrice)  )
                {
                    iva_  = 0 ;
                }
                else
                {
                    iva_  = row.tax1 / row.unitaryPrice ;
                }

                //Validar servicio TAX_SERVICES
                if (  isNaN(row.tax2 / row.unitaryPrice)  )
                {
                    taxServices  = 0 ;
                }
                else
                {
                    taxServices  = row.tax2 / row.unitaryPrice ;
                }
                let infoSales       = '';
                let infoSerie       = '';
                let infoReferencia  = '';
                let infoPrecio1     = 0;
                let infoPrecio2     = 0;
                let infoPrecio3     = 0;

                if (objDetailReference && objDetailReference.length > 0) {
                    infoSales       = objDetailReference[0].sales;
                    infoSerie       = objDetailReference[0].reference1;
                    infoReferencia  = objDetailReference[0].reference2;
                    infoPrecio1     = objDetailReference[0].precio1;
                    infoPrecio2     = objDetailReference[0].precio2;
                    infoPrecio3     = objDetailReference[0].precio3;
                }


				var itemNumber 			= "";
				var mostrarCodigoBarra 	= '<?php echo getBehavio($company->type, 'app_invoice_billing', 'javaScriptShowCodeBarra', 'false') ?>';
				if(mostrarCodigoBarra == "false")
				{
					itemNumber 			= row.itemNumber;
				}
				else
				{
					itemNumber 			= row.barCode + " " + row.itemNumber;
				}

				var record 	= {			
					txtTMD_checked: false,
					txtTMD_txtTransactionMasterDetailID: row.transactionMasterDetailID,
					txtTMD_txtItemID: row.componentItemID,
					txtTMD_txtTransactionDetailItemNumber: itemNumber,
					txtTMD_txtTransactionDetailName: row.itemNameLog,
					txtTMD_txtSku: skuFormatoDescription,
					txtTMD_txtQuantity: row.quantity,
					txtTMD_txtPrice: row.unitaryPrice,
					txtTMD_txtSubTotal: row.unitaryPrice * row.quantity ,
					txtTMD_txtIva: iva_,
					txtTMD_skuQuantityBySku: skuQuantityBySku,
					txtTMD_unitaryPriceInvidual: row.unitaryPrice,
					txtTMD_skuFormatoDescription: skuFormatoDescription,
					txtTMD_txtItemPrecio2: infoPrecio2,
					txtTMD_txtItemPrecio3: infoPrecio3,
					txtTMD_txtTransactionDetailNameDescription: row.itemNameDescriptionLog,
					txtTMD_txtTaxServices:taxServices,
					txtTMD_txtDetailLote: row.reference1,
					txtTMD_txtInfoVendedor: infoSales,
					txtTMD_txtInfoSerie: infoSerie,
					txtTMD_txtInfoReferencia: infoReferencia,
					txtTMD_txtItemPrecio1: infoPrecio1,
					txtTMD_txtCatalogItemIDSku: skuCatalogItemID,
					txtTMD_txtRatioSku: skuQuantity,
					txtTMD_txtDiscountByItem: row.discount,
					txtTMD_txtCommisionByBankByItem: row.tax3 
				};
				
				
				objFormulario.txtTransactionMasterDetail.push(record);
			}
		}
		
		//cargar lso datos en pantalla		
		fnFillFactura("miVentanaPrincipal", objFormulario );
		
	}
	
	function fnValidateFormAndSubmit(accion)
	{
		
		let result 				= true;		
		var miVentanaPrincipal_ = Ext.getCmp('miVentanaPrincipal');
		var miVentanaDePago_	= Ext.getCmp('miVentanaDePago');
		var grid 				= miVentanaPrincipal_.down('#gridDetailTransactionMaster'); // encuentra el grid		
		var store 				= grid.getStore();
			
			
		let switchDesembolso	= getValueRadio("miVentanaPrincipal","txtCheckDeEfectivo");
		if(switchDesembolso === "0")
			switchDesembolso = false;
		else
			switchDesembolso = true;
		
		
		//Validar Factura status
		if(
			miVentanaPrincipal_.down("#txtStatusID").getValue() == varStatusInvoiceRegistrado && 
			miVentanaPrincipal_.down("#txtStatusIDOld").getValue() == "0" && 
			accion == "aplicar"
		)
		{
			Ext.Msg.alert(
				'Error',
				'<span style="color:red;font-weight:bold;">Debe registrar la factura primeramente</span>'
			);

			result = false;
		}
		
		
		//Validar bodega de despacho
		if(miVentanaPrincipal_.down("#txtWarehouseID").getValue() == ""){			
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Seleccionar bodega de despacho</span>" );	
			result = false;
		}


		//Validar Fecha
		if(miVentanaPrincipal_.down("#txtDate").getValue() == ""){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Establecer fecha al documento</span>" );	
			result = false;
		}

		//Validar Cliente
		if(miVentanaPrincipal_.down("#txtCustomerID").getValue() == ""){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Seleccionar el cliente</span>" );	
			result = false;
		}
		//Validar Proveedor de Credito
		if(miVentanaPrincipal_.down("#txtReference1").getValue() == "0" && switchDesembolso){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Seleccionar el proveedor de credito</span>" );	
			result = false;
		}
		//Validar Zona
		if(miVentanaPrincipal_.down("#txtZoneID").getValue() == "" && switchDesembolso){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Seleccionar la zona de la factura</span>" );	
			result = false;
		}
		//Validar Vendedor
		if(miVentanaPrincipal_.down("#txtEmployeeID").getValue() == "" && switchDesembolso){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Seleccionar el vendedor de la factura</span>" );	
			result = false;
		}

		//Validar monto descuento en rango de 0 a 100
		let porcentajeDescuento = parseFloat(miVentanaPrincipal_.down('#txtPorcentajeDescuento').getValue()) || 0;
		if (porcentajeDescuento < 0 || porcentajeDescuento > 100) {
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>El porcentaje de descuento no es valido</span>" );	
			result = false;
		}

		//Validar Estado de la factura
		if(miVentanaPrincipal_.down("#txtStatusIDOld").getValue() ==  varStatusInvoiceAplicado){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Crear una nueva factura, por que la actual esta aplicada, no puede ser modificada</span>" );	
			result = false;
		}

		//Validar estado anulado
		if(miVentanaPrincipal_.down("#txtStatusID").getValue() ==  varStatusInvoiceAnular){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>No puede pasar a estado anulada</span>" );	
			result = false;
		}

		//Validar Detalle
		//
		///////////////////////////////////////////////		
		var cantidadTotalesEnZero 	= store.queryBy(function(record) { return record.get('txtTMD_txtSubTotal') == 0; }).getCount();
		var validateTotalesZero 	= true;
		<?php echo getBehavio($company->type, "app_invoice_billing", "scriptValidateTotalesZero", ""); ?>

		if(validateTotalesZero == true)
		{
			if(cantidadTotalesEnZero > 0){
				Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>No puede haber totales en 0</span>" );	
				result = false;
			}
		}


		var cantidadTotalesEnZero = store.queryBy(function(record) { return record.get('txtTMD_txtQuantity') == 0; }).getCount();
		if(cantidadTotalesEnZero > 0){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>No puede haber cantidades en 0</span>" );	
			result = false;
		}

		
		if( /*varAutoAPlicar == "true" && */ store.getCount() == 0){
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>La factura no puede estar vacia</span>" );	
			result = false;
		}

		var listItemIDToValid 	= "-1";
		var listQntity 			= "-1"
		store.each(function (record) {
			listItemIDToValid 	= listItemIDToValid+ ","+record.get('txtTMD_txtItemID');
			listQntity 			= listQntity+ ","+record.get('txtTMD_txtQuantity');
			
		});
		
		//Si es de credito que la factura no supere la linea de credito		
		var causalSelect 				= miVentanaPrincipal_.down("#txtCausalID").getValue();
		var causalCredit 				= objCausalTypeCredit.value.split(",");
		var invoiceTypeCredit 			= false;


		//Obtener si la factura es al credito
		for(var i=0;i<causalCredit.length;i++){
			if(causalCredit[i] == causalSelect){
				invoiceTypeCredit = true;
			}
		}
		
		
		//Validar si es de credito debe de seleccionar una linea de credito
		var customerCreditLineID 		= miVentanaPrincipal_.down("#txtCustomerCreditLineID").getValue();
		if(invoiceTypeCredit && customerCreditLineID == null)
		{
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Debe seleccionar una linea de credito</span>" );	
			result = false;
		}
			
		
		var objCustomerCreditLine 		= objListCustomerCreditLine.filter(function(obj){ return obj.customerCreditLineID == customerCreditLineID; }); 
		if(varParameterAmortizationDuranteFactura && miVentanaPrincipal_.down("#txtReference2").getValue() == "" && invoiceTypeCredit ){			
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Seleccionar el plazo</span>" );	
			result = false;
		}

		//No puede haber cambio, si la factura es de credito
		if(invoiceTypeCredit && miVentanaDePago_.down("#txtChangeAmount").getValue() > 0 )
		{
			Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>No puede haber cambio si la factura es de credito</span>" );	
			result = false;
		}

		
		<?php echo getBehavio($company->type, "app_invoice_billing", "scriptValidateCustomer", ""); ?>


		//Validaciones si la factura es al credito.
		if(invoiceTypeCredit){

			<?php echo getBehavio($company->type, "app_invoice_billing", "scriptValidateInCredit", ""); ?>

			//Validar Fecha del Primer Pago si es de Credito
			if(miVentanaPrincipal_.down("#txtDateFirst").getValue() == "" && switchDesembolso){
				Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Seleccionar la fecha del primer pago</span>" );	
				result = false;
			}


			//Validar Notas
			if(miVentanaPrincipal_.down("#txtNote").getValue() == "" && switchDesembolso){
				Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Asignarle una nota al documento</span>" );	
				result = false;
			}

			//Validar Escritura Publica
			if(miVentanaPrincipal_.down("#txtFixedExpenses").getValue() == "" && switchDesembolso){
				Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>Ingresar el porcentaje de gasto fijo por desembolso</span>" );	
				result = false;
			}



			var montoTotalInvoice 	= miVentanaPrincipal_.down("#txtTotal").getValue();
			var balanceCredit 		= 0;

			if(objCurrencyCordoba.currencyID == objCustomerCreditLine[0].currencyID)
				balanceCredit =  objCustomerCreditLine[0].balance;
			else{
				balanceCredit = (
									objCustomerCreditLine[0].balance *
									objCustomerCreditLine[0].objExchangeRate
								);
			}

			
			//Validar Limite
			if(parseFloat(balanceCredit) < parseFloat(montoTotalInvoice) &&  parseFloat(balanceCredit) != 0 ){
				result = false;
				Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>La factura no puede ser facturada al credito, balance del cliente" + balanceCredit + " </span>"  );	
			}


		}
		else{
			//Validar Pago
			if( parseFloat( miVentanaDePago_.down("#txtChangeAmount").getValue() )  < 0 ){
				result = false;
				Ext.Msg.alert('Error',"<span style='color:red;font-weight:bold;'>El cambio de la factura no puede ser menor a 0</span>" );	
			}
		}


		
		if(result == false)
		{
			miVentanaEsperando.hide();
		}
		
		return result;

	}
	
	function fnFormatDateYYYY_MM_DD(date) {
		var y = date.getFullYear();
		var m = String(date.getMonth() + 1).padStart(2, '0');
		var d = String(date.getDate()).padStart(2, '0');
		return y + '-' + m + '-' + d;
	}


		
</script>
