<!-- ./ page heading -->
<script>
	var baseUrl 											= '<?php echo base_url(); ?>';
	let objCausalTypeCredit 								= JSON.parse('<?php echo json_encode($objCausalTypeCredit); ?>');
	let objListCustomerCreditLine 							= JSON.parse('<?php echo json_encode($objListCustomerCreditLine); ?>');	
	let objCurrencyCordoba 									= JSON.parse('<?php echo json_encode($objCurrencyCordoba); ?>');
	var varParameterINVOICE_BILLING_VALIDATE_EXONERATION 	= '<?php echo $objParameterINVOICE_BILLING_VALIDATE_EXONERATION; ?>';
	
	var varStatusInvoiceAplicado			= 67; //Estado Aplicada
    var varStatusInvoiceAnular				= 68; //Anular
	var varStatusInvoiceRegistrado			= 66; //Registrado
	
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
			'txtStatusID' : '<?php
					$count 				= 0;
					$valueWorkflowFirst = 0;
					if($objListWorkflowStage){
						foreach($objListWorkflowStage as $ws){
							if($count == 0)
								$valueWorkflowFirst = $ws->workflowStageID;
							$count++;
						}
					}
					?>',
					
			'txtStatusIDOld' : 0,			
			'txtDate': Ext.Date.format(new Date(), 'Y-m-d'),	
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
			'txtDateFirst' : Ext.Date.format(new Date(), 'Y-m-d'),
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
		},
		labels:{
			'txtTM_transactionNumber': 'PRF00000000'
		}
		
	};
					
		
	var objConfigMappingConfig = 
	{
		itemID: 				"txtTMD_txtItemID",
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

		miVentanaPrincipal = Ext.create('Ext.container.Viewport', {
			layout: 'fit',
			id: 'miVentanaPrincipal',
			itemId: 'miVentanaPrincipal',
			listeners:{
				afterrender: function(form) {
					// Configuración dinámica al "load" del contenedor
					indexDBCreate(false);
					fnConfiguracionLoad(form, objConfigInit );					
					
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
											selModel: 'rowmodel', // permite seleccionar filas para eliminar
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
														store: ['Producto A', 'Producto B', 'Producto C'], // o un store más complejo
														editable: false,    // no permite escribir, solo seleccionar
														forceSelection: true,
														listeners: {
															select: function(combo, record) {
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
																// Aquí podés manejar el cambio y actualizar el store
																var record = field.getWidgetRecord();
																record.set('txtTMD_txtQuantity', newValue);
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
																// Aquí podés manejar el cambio y actualizar el store
																var record = field.getWidgetRecord();
																record.set('txtTMD_txtPrice', newValue);
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
															var record = btn.getWidgetRecord();
															Ext.Msg.alert('Info', 'Producto: ' + record.get('nombre') + '\nCantidad: ' + record.get('txtTMD_txtQuantity'));
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
							name: 'txtChangeAmount',
							id:'txtChangeAmount',
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
							text: 'Impresión 1',
							id: 'btnImpresion1',
							handler: fnBtnImpresion1
						},
						{
							text: 'Impresión 2',
							id: 'btnImpresion2',
							handler: fnBtnImpresion2
						},
						{
							text: 'Impresión 3',
							id: 'btnImpresion3',
							handler: fnBtnImpresion3
						},
						{
							text: 'Impresión 4',
							id: 'btnImpresion4',
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
		
		miVentanaEsperando = Ext.create('Ext.window.Window', {
			title: 'Procesando',
			id: 'miVentanaEsperando',
			itemId:'miVentanaEsperando',
			closable: false,
			modal: true,
			closeAction: 'hide',
			width: 300,
			height: 100,
			bodyPadding: 10,
			html: '<div style="text-align:center;"><b>Esperando...</b></div>'
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
							value: '12345'
						},
						{
							xtype: 'combobox',
							fieldLabel: 'Precios',
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
							name: 'txtSelectPrecio',
							id:'txtSelectPrecio',
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
							name: 'txtSelectVendedor',
							id:'txtSelectVendedor',
						},
						{
							xtype: 'textfield',       // tipo texto
							fieldLabel: 'Serie', // etiqueta
							labelWidth: 100,          // ancho de la etiqueta
							width: 300,               // ancho total del campo
							name: 'txtSerieProducto',     // nombre del campo para enviar al servidor
							id: 'txtSerieProducto',       // id único
						},
						{
							xtype: 'textfield',       // tipo texto
							fieldLabel: 'Referencia', // etiqueta
							labelWidth: 100,          // ancho de la etiqueta
							width: 300,               // ancho total del campo
							name: 'txtReferenciaProducto',     // nombre del campo para enviar al servidor
							id: 'txtReferenciaProducto',       // id único
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



		function fnBtnNuevaFactura() 
		{
			fnFillFactura(miVentanaPrincipal,null);
		}
		function fnBtnGuardarFactura()
		{
			Ext.Msg.alert('Guardar', 'Factura guardada');
		}
		function fnBtnEliminarFactura()
		{
			Ext.Msg.alert('Eliminar', 'Factura guardada');
		}
		function fnBtnImprimirFactura()
		{
			
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
				
				if(grid.getStore().count() < 1 )
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
			Ext.Msg.alert('Pago', 'Pago registrado correctamente.');
			winBtn.up('window').close();
		}
		function fnBtnCancelarPago(winBtn) {
			winBtn.up('window').close();
		}
		function fnBtnCancelarImpresion () {
			ventanaImpresion.hide();
		}
		function fnBtnImpresion1()
		{
			
		}
		function fnBtnImpresion2()
		{
			
		}
		function fnBtnImpresion3()
		{
			
		}
		function fnBtnImpresion4()
		{
			
		}
		function fnBtnSeleccionFactura() {
			
			var grid 		= miVentanaSeleccionFactura.down('grid');
			var seleccion 	= grid.getSelection();

			if (seleccion.length === 0) 
			{
				Ext.Msg.alert('Aviso', 'Debe seleccionar al menos una factura.');
				return;
			}
			
			//seleccion.forEach(function(record) 
			//{	
			//	miVentanaPrincipal.down('#txtCustomerID').setValue(  record.data.entityID  );
			//	miVentanaPrincipal.down('#txtCustomerDescription').setValue(  record.data.Codigo + " " + record.data.Nombre );			
			//});
			
			miVentanaSeleccionFactura.hide();
			
			
		}
		function fnBtnCancelarSeleccionFactura(btn) {
			btn.up('window').close();
		}
		function fnBtnConfirmarInformacionAdicional()
		{
			miVentanaInformacionAdicional.hide();
		}
		function fnBtnCancelarInformacionAdicional(btn){
			btn.up('window').close();
		}
		function fnBtnSeleccionProducto(btn) {
			
			var grid 		= miVentanaSeleccionProducto.down('grid');
			var seleccion 	= grid.getSelection();

			if (seleccion.length === 0) 
			{
				Ext.Msg.alert('Aviso', 'Debe seleccionar al menos un producto.');
				return;
			}
			
			
			var grid 	= miVentanaPrincipal.down('#gridDetailTransactionMaster'); // buscar por itemId
			var store 	= grid.getStore();

			seleccion.forEach(function(record) 
			{	
				//miVentanaPrincipal.down('#txtCustomerID').setValue(  record.data.entityID  );
				//miVentanaPrincipal.down('#txtCustomerDescription').setValue(  record.data.Codigo + " " + record.data.Nombre );			
			
				// Ejemplo: agregar fila vacía
				grid.getStore().add({
					codigo: '',
					producto: '',
					um: '',
					cantidad: 1,
					precio: 0,
					subtotal: 0
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
				Ext.Msg.alert('Aviso', 'Debe seleccionar al menos un cliente.');
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
		
		function fnChange_ReceiptAmount(field, newValue, oldValue) {
			console.log('Valor anterior:', oldValue);
			console.log('Nuevo valor:', newValue);
			fnCalculateAmountPay();
		
		}
		
		function fnChange_ReceiptAmountTarjeta_BankID (field, newValue, oldValue) {
			console.log('Valor anterior:', oldValue);
			console.log('Nuevo valor:', newValue);

			// Aquí puedes agregar la lógica que necesites
			// Por ejemplo, recalcular totales según el descuento
			let value = 0;
			fnRecalcularMontoComision(value);
		
		}
		
		function fnChange_PorcentageDescuento (field, newValue, oldValue) 
		{
			console.log('Valor anterior:', oldValue);
			console.log('Nuevo valor:', newValue);

			// Aquí puedes agregar la lógica que necesites
			// Por ejemplo, recalcular totales según el descuento
			
			miVentanaEsperando.show();
			fnRecalculateDetail(true,"");
			miVentanaEsperando.hide();
			 
		}
		
		function fnChange_FirstLineProtocolo (field, newValue, oldValue) 
		{
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
						console.log('Datos recibidos:', datos);
						
						
						//La exoneracion ya existe no exonerar
						var timerNotification 	= 15000;
						if(datos.objTransactionMaster.length > 0 )
						{
							viewport.down('#txtCheckApplyExoneracion').setValue(false);							
							Ext.Msg.alert('Error', 'El numero de exoneracion ya existe!!');
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
						var length  = grid.count();
						store.each(function (record) {
							fnGetConcept(record.get('itemID'),"ALL");
						});
						
						
					},
					failure: function(response, opts) {
						Ext.Msg.alert('Error', 'No se pudieron cargar los datos');
						console.log('Server-side failure with status code ' + response.status);
					}
				});
				
				
				
			}
		}
		
		function fnChange_ApplyExoneration (field, newValue) {
			if (newValue === true) {
				console.log("Seleccionado: SI (1)");
			}
			
			

			var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
			if(!viewport){
				return;
			}
			
			var grid 	= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 	= grid.getStore();	
			var length  = grid.count();
			store.each(function (record) {
				fnGetConcept(record.get('itemID'),"ALL");
			});
			
			miVentanaEsperando.show();
			setTimeout(() => { miVentanaEsperando.hide(); }, (length * 1000) * 0.2 );
			
			
		}
		
		function fnChange_CurrencyID_CreditLineID_WarehouseID(combo, newValue, oldValue, eOpts) {
			
			fnClearData();
			fnLockPayment();
		}
		function fnChange_CausalID(combo, newValue, oldValue, eOpts) {
			
			fnClearData();
			fnLockPayment();
			fnRenderLineaCreditoDiv();
		}
		function fnChangeTypePreiceID(combo, newValue, oldValue, eOpts) {
			fnActualizarPrecio();
		}
		
		
		function fnBtnEliminarProductoDetail(btn) {
			var grid = btn.up('grid');
			var selection = grid.getSelectionModel().getSelection();
			if (selection.length > 0) {
				grid.getStore().remove(selection);
			} else {
				Ext.Msg.alert('Atención', 'Seleccione al menos un producto para eliminar.');
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

				
				//buscar el producto y agregar por codigo de barra
				indexDBGetInformationLocal(
					"objListaProductosX001",
					"all",
					0,
					"all",
					{"codigoABuscar":codigoABuscar},
					function(e){

						
						var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
						if(!viewport){
							return;
						}

						//buscar el producto y agregar
						var codigoABuscar 	= e.codigoABuscar.toUpperCase();
						let data			= e.all;
						let encontrado		= false;
						let index			= -1;
						for(let i = 0 ; i < data.length ; i++)
						{

							if(encontrado == true)
							{
								i--;
								index = i;
								break;
							}

							
							//buscar por codigo de sistema
							var currencyTemp	= data[i].currencyID;
							var currencyID 		= viewport.down("#txtCurrencyID").getValue();

							var warehouseIDTemp		= data[i].warehouseID;
							var warehouseID			= viewport.down("#txtWarehouseID").getValue();

							if(
								currencyID == currencyTemp &&
								fnDeleteCerosIzquierdos(codigoABuscar) == fnDeleteCerosIzquierdos(data[i].Codigo.replace("BITT","").replace("ITT","").toUpperCase())  &&
								warehouseID == warehouseIDTemp
							)
							{

								encontrado 		= true;
								break;
							}

							
							//buscar por codigo de barra
							var listCodigTmp 	= data[i].Barra.split(",");
							currencyTemp		= data[i].currencyID;
							currencyID 			= viewport.down("#txtCurrencyID").getValue();
							encontrado			= false;

							if(encontrado == false )
							{
								for(let ii = 0 ; ii < listCodigTmp.length; ii++)
								{
									if(
										fnDeleteCerosIzquierdos(listCodigTmp[ii].toUpperCase()) == fnDeleteCerosIzquierdos(codigoABuscar) &&
										currencyID == currencyTemp  &&
										warehouseID == warehouseIDTemp
									)
									{
										index       = i;
										encontrado 	= true;
										break;
									}
								}
							}
						}

						
						if(encontrado == true)
						{
							var sumar				= true;
							var filterResult 		= data[index];
						
							//Logica de precio
							if(viewport.down("#txtTypePriceID").getValue() == "154" /*precio1*/)
								filterResult.Precio = filterResult.Precio;
							else if(viewport.down("#txtTypePriceID").getValue() == "155" /*precio2*/)
								filterResult.Precio = filterResult.Precio2;
							else /*precio3*/
								filterResult.Precio = filterResult.Precio3;
								

							
							//Agregar el Item a la Fila
							onCompleteNewItem(filterResult,sumar);
						}

					}

				);
				
				
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
		
		if(formPanel.id == 'miVentanaPrincipal')
		{			
	
			//txtTM_transactionNumber
			var campoNombre = miVentanaPrincipal.down('#txtTM_transactionNumber');
			if(obj == null)
			{
				campoNombre.setText( objConfigInit.labels.txtTM_transactionNumber );
			}
			else
			{
				campoNombre.setText( objConfigInit.labels.txtTM_transactionNumber );
			}
			
			//txtUserID
			var campoNombre = miVentanaPrincipal.down('#txtUserID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtUserID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtUserID );
			}
			
			//txtCompanyID
			var campoNombre = miVentanaPrincipal.down('#txtCompanyID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCompanyID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCompanyID );
			}
			
			//txtTransactionID
			var campoNombre = miVentanaPrincipal.down('#txtTransactionID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionID );
			}
			
			//txtTransactionMasterID
			var campoNombre = miVentanaPrincipal.down('#txtTransactionMasterID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionMasterID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionMasterID );
			}
			
			//txtCodigoMesero
			var campoNombre = miVentanaPrincipal.down('#txtCodigoMesero');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCodigoMesero );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCodigoMesero );
			}
			
			//txtStatusID
			var campoNombre = miVentanaPrincipal.down('#txtStatusID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtStatusID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtStatusID );
			}
			
			//txtStatusIDOld
			var campoNombre = miVentanaPrincipal.down('#txtStatusIDOld');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtStatusIDOld );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtStatusIDOld );
			}
			
			//txtDate
			var campoNombre = miVentanaPrincipal.down('#txtDate');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDate );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDate );
			}
			
			//txtExchangeRate
			var campoNombre = miVentanaPrincipal.down('#txtExchangeRate');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtExchangeRate );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtExchangeRate );
			}
			
			//txtNote
			var campoNombre = miVentanaPrincipal.down('#txtNote');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtNote );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtNote );
			}
			
			//txtCurrencyID
			var campoNombre = miVentanaPrincipal.down('#txtCurrencyID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCurrencyID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCurrencyID );
			}
			
			//txtCustomerID
			var campoNombre = miVentanaPrincipal.down('#txtCustomerID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerID );
			}
			
			//txtCustomerDescription
			var campoNombre = miVentanaPrincipal.down('#txtCustomerDescription');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerDescription );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerDescription );
			}
			
			//txtReferenceClientName
			var campoNombre = miVentanaPrincipal.down('#txtReferenceClientName');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientName );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientName );
			}
			
			//txtReferenceClientIdentifier
			var campoNombre = miVentanaPrincipal.down('#txtReferenceClientIdentifier');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientIdentifier );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientIdentifier );
			}
			
			//txtCausalID
			var campoNombre = miVentanaPrincipal.down('#txtCausalID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCausalID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCausalID );
			}
			
			//txtCustomerCreditLineID
			var campoNombre = miVentanaPrincipal.down('#txtCustomerCreditLineID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerCreditLineID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerCreditLineID );
			}
			
			//txtZoneID
			var campoNombre = miVentanaPrincipal.down('#txtZoneID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtZoneID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtZoneID );
			}
			
			//txtTypePriceID
			var campoNombre = miVentanaPrincipal.down('#txtTypePriceID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTypePriceID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTypePriceID );
			}
			
			//txtWarehouseID
			var campoNombre = miVentanaPrincipal.down('#txtWarehouseID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtWarehouseID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtWarehouseID );
			}
			
			//txtReference3
			var campoNombre = miVentanaPrincipal.down('#txtReference3');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReference3 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReference3 );
			}
			
			//txtEmployeeID
			var campoNombre = miVentanaPrincipal.down('#txtEmployeeID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtEmployeeID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtEmployeeID );
			}
			
			//txtNumberPhone
			var campoNombre = miVentanaPrincipal.down('#txtNumberPhone');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtNumberPhone );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtNumberPhone );
			}
			
			//txtMesaID
			var campoNombre = miVentanaPrincipal.down('#txtMesaID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtMesaID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtMesaID );
			}
			
			//txtNextVisit
			var campoNombre = miVentanaPrincipal.down('#txtNextVisit');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtNextVisit );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtNextVisit );
			}
			
			//txtDateFirst
			var campoNombre = miVentanaPrincipal.down('#txtDateFirst');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDateFirst );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDateFirst );
			}
			
			//txtReference2
			var campoNombre = miVentanaPrincipal.down('#txtReference2');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReference2 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReference2 );
			}
			
			
			//txtPeriodPay
			var campoNombre = miVentanaPrincipal.down('#txtPeriodPay');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtPeriodPay );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtPeriodPay );
			}
			
			//txtReference1
			var campoNombre = miVentanaPrincipal.down('#txtReference1');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReference1 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReference1 );
			}
			
			//txtDayExcluded
			var campoNombre = miVentanaPrincipal.down('#txtDayExcluded');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDayExcluded );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDayExcluded );
			}
			
			//txtFixedExpenses
			var campoNombre = miVentanaPrincipal.down('#txtFixedExpenses');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtFixedExpenses );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtFixedExpenses );
			}
			
			//txtCheckApplyExoneracion
			var campoNombre = miVentanaPrincipal.down('#txtCheckApplyExoneracion');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCheckApplyExoneracion );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCheckApplyExoneracion );
			}
			
			//txtLayFirstLineProtocolo
			var campoNombre = miVentanaPrincipal.down('#txtLayFirstLineProtocolo');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtLayFirstLineProtocolo );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtLayFirstLineProtocolo );
			}
			
			//txtCheckDeEfectivo
			var campoNombre = miVentanaPrincipal.down('#txtCheckDeEfectivo');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCheckDeEfectivo );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCheckDeEfectivo );
			}
			
			//txtCheckReportSinRiesgoValue
			var campoNombre = miVentanaPrincipal.down('#txtCheckReportSinRiesgoValue');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCheckReportSinRiesgoValue );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCheckReportSinRiesgoValue );
			}
			
			
			//txtTMIReference1
			var campoNombre = miVentanaPrincipal.down('#txtTMIReference1');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTMIReference1 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTMIReference1 );
			}
			
			//txtSubTotal
			var campoNombre = miVentanaPrincipal.down('#txtSubTotal');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtSubTotal );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtSubTotal );
			}
			
			//txtIva
			var campoNombre = miVentanaPrincipal.down('#txtIva');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtIva );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtIva );
			}
			
			//txtPorcentajeDescuento
			var campoNombre = miVentanaPrincipal.down('#txtPorcentajeDescuento');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtPorcentajeDescuento );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtPorcentajeDescuento );
			}
			
			//txtDescuento
			var campoNombre = miVentanaPrincipal.down('#txtDescuento');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDescuento );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDescuento );
			}
			
			//txtServices
			var campoNombre = miVentanaPrincipal.down('#txtServices');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtServices );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtServices );
			}
			
			//txtTotal
			var campoNombre = miVentanaPrincipal.down('#txtTotal');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTotal );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTotal );
			}
			
			//txtChangeAmount
			var campoNombre = miVentanaDePago.down('#txtChangeAmount');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtChangeAmount );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtChangeAmount );
			}
			
			//txtReceiptAmount
			var campoNombre = miVentanaDePago.down('#txtReceiptAmount');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmount );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmount );
			}
			
			//txtReceiptAmountDol
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountDol');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountDol );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountDol );
			}
			
			//txtReceiptAmountTarjeta
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjeta');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta );
			}
			
			//txtReceiptAmountTarjeta_BankID
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjeta_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_BankID );
			}
			
			//txtReceiptAmountTarjeta_Reference
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjeta_Reference');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_Reference );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_Reference );
			}
			
			
			//txtReceiptAmountTarjetaDol
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjetaDol');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol );
			}
			
			//txtReceiptAmountTarjetaDol_BankID
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjetaDol_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_BankID );
			}
			
			//txtReceiptAmountTarjetaDol_Reference
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjetaDol_Reference');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_Reference );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_Reference );
			}
			
			//txtReceiptAmountBank
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBank');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank );
			}
			
			//txtReceiptAmountBank_BankID
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBank_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_BankID );
			}
			
			
			//txtReceiptAmountBank_Reference
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBank_Reference');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_Reference );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_Reference );
			}
			
			//txtReceiptAmountBankDol
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBankDol');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol );
			}
			
			//txtReceiptAmountBankDol_BankID
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBankDol_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_BankID );
			}
			
			//txtReceiptAmountBankDol_Reference
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBankDol_Reference');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_Reference );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_Reference );
			}
			
			//txtReceiptAmountPoint
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountPoint');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountPoint );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountPoint );
			}
			
			//Limpiar detalle
			var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
			if(viewport)
			{
				var grid = viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
				grid.getStore().loadData([]);
			}
			
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
			console.info('Database success');
			if(obtenerRegistroDelServer)
			{
				indexDBObtenerProductos();
			}


		};

		request.onupgradeneeded  = (e) => {
			console.info('Database created');

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
				console.log('Datos recibidos:', datos);
				
				
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
						Ext.Msg.alert('Error', 'No se pudieron cargar los datos');
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
						Ext.Msg.alert('Error', 'No se pudieron cargar los datos');
						console.log('Server-side failure with status code ' + response.status);
					}
				});

					
				// Cerrar ventana modal
				miVentanaEsperando.close();
				Ext.Msg.alert('Éxito', 'Datos actualizados correctamente');
			
				
				
			},
			failure: function(response, opts) {
				Ext.Msg.alert('Error', 'No se pudieron cargar los datos');
				console.log('Server-side failure with status code ' + response.status);
			}
		});



	}
	
	function indexDBGetInformationLocal(varTable, varColumn, varValue, valueComando, varDataExt, varFunction) {
        const requestStore = db.transaction(varTable, 'readwrite').objectStore(varTable);

        let request;
        let varIndex;

        if (varColumn === "all") {
            request = requestStore.getAll();
        } else {
            varIndex 	= requestStore.index(varColumn);
            request 	= varIndex.getAll(varValue);
        }

        request.onsuccess = function() {
            try {
                if (valueComando !== "none") {
                    varDataExt[valueComando] = request.result;
                    varFunction(varDataExt, varDataExt);
                } else {
                    varFunction(request.result, varDataExt);
                }
            } catch (ex) {
                console.error("Error in onsuccess:", ex);
            }
        }

        request.onerror = function(err) {
            console.error("Error in request:", err);
            if (err.target && err.target.error) {
                console.error("Error message:", err.target.error.message);
            }
        }
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
					Ext.Msg.alert('Error', 'No se pudieron cargar los datos');
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
				
				cantidad 	= record.get('quantity');
				precio 		= record.get('unitaryPrice');
				iva 		= record.get('iva');
				taxServices	= record.get('taxServices');
				skuRatio    = record.get('ratioSku');

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
				
				
				record.set('skuQuantityBySku',  skuQuantityBySku);
				record.set('total',  subtotal);
				record.set('discount',  descuento);
				
			});
			
			
			
			descuento 		= subtotalGeneral * (porcentajeDescuento / 100);
			totalGeneral 	= subtotalGeneral + serviceGeneral + ivaGeneral - descuento;

			viewport.down("#txtSubTotal").setValue(subtotalGeneral);
			viewport.down("#txtDescuento").setValue(descuento);
			viewport.down("#txtIva").setValue(ivaGeneral);
			viewport.down("#txtServices").setValue(serviceGeneral);
			viewport.down("#txtTotal").setValue(totalGeneral);

			//Si es de credito que la factura no supere la linea de credito
			var causalSelect 				= viewport.down("#txtCausalID").val();
			var customerCreditLineID 		= viewport.down("#txtCustomerCreditLineID").val();
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
			
			var cantidadTemporal 	 		=  store[index].get("quantity");
			var priceTemporal  		 		=  store[index].get("txtprice");
			store[index].set("quantity",cantidadTemporal);
			store[index].set("price",priceTemporal);
			
			
			
			var cantidad 				= store[index].get("quantity");
			var skuRatio    			= store[index].get("ratioSku"); 
			let skuQuantityBySku 		= cantidad * skuRatio;
			store[index].set("skuQuantityBySku",skuQuantityBySku);
			
			
			var precio 					= store[index].get("price"); 
			var subtotal    			= precio * cantidad;
			store[index].set("total",subtotal);
			
			var descuento				= subtotal * (porcentajeDescuento / 100);
			store[index].set("discount",descuento);
			
			
			var grid 						= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
			var store 						= grid.getStore();	
			var subtotalGeneral 			= 0;
			var descuento		 			= 0;	
			var ivaGeneral		 			= 0;
			var serviceGeneral		 		= 0;	
			totalGeneral				    = 0;
			
			store.each(function(record){
				subtotalGeneral += parseFloat(record.get('total')) || 0;
			});
			store.each(function(record){
				descuento += parseFloat(record.get('discount')) || 0;
			});
			store.each(function(record){
				ivaGeneral += parseFloat(record.get('iva')) || 0;
			});
			store.each(function(record){
				serviceGeneral += parseFloat(record.get('taxServies')) || 0;
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
				subtotalGeneral += parseFloat(record.get('total')) || 0;
			});

			store.each(function(record){
				descuento += parseFloat(record.get('discount')) || 0;
			});

			store.each(function(record){
				ivaGeneral += parseFloat(record.get('iva')) || 0;
			});

			store.each(function(record){
				serviceGeneral += parseFloat(record.get('taxServies')) || 0;
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
	
	function fnGetConcept(conceptItemID,nameConcept){

		console.info("fnGetConcept");
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
		var store 		= grid.getStore();			
		var records		= store.queryBy(function(record){ return record.get('itemID') > conceptItemID; }).items;

		//Obtener el concepto de la base de datos del navegador y calcular nuevamente
		obtenerDataDBProductoArray(
			"objListaProductosConceptosX001",
			"componentItemID",conceptItemID,"none",{},
			function(e){
				var objConcepto = e;
				var exoneracion = viewport.down("#txtCheckApplyExoneracionValue").getValue();

				if(exoneracion == false )
				{
					objConcepto1 	= Ext.Array.filter(objConcepto, function(obj){ return obj.name === "IVA"; });
					if( objConcepto1.length > 0 )
					{
						records[0].set("iva",iva);
					}
					objConcepto2 	= Ext.Array.filter(objConcepto, function(obj){ return obj.name === "TAX_SERVICES"; });
					if( objConcepto2.length > 0 )
					{
						records[0].set("taxServices",taxServices);
					}
				}
				else
				{
					
					records[0].set("iva",iva);
					records[0].set("taxServices",taxServices);
				}
				fnRecalculateDetail(true,"", store.indexOf(records[0]));
				console.info("fnGetConcept fin");
				
			}
		);
		
		
	}
	
	function fnRecalcularMontoComision(monto) {
		
		
		console.info("fnRecalcularMontoComision");
		var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
		if(!viewport){
			return;
		}
		var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
		var store 		= grid.getStore();			
		var records		= store.queryBy(function(record){ return record.get('itemID') > conceptItemID; }).items;

		
		if (isNaN(monto))
		{
			monto = 0;
		}

		if(monto == 0)
			return;
		
		store.each(function (record) {
			let oldPrice = record.get("total");
			let newPrice = oldPrice * ( monto / 100 );
			record.set("comisionPosBanck",newPrice);
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
		var records		= store.queryBy(function(record){ return record.get('itemID') > conceptItemID; }).items;

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

        resultTotal = fnFormatNumber(resultTotal,2);
        viewport_miVentanaDePago.down("#txtChangeAmount").setValue(resultTotal);
    }
	
	function fnDeleteCerosIzquierdos(texto){

		var array 						= texto.split("");
		var newTexto 				 	= "";
		var encontradoPrimerElemento 	= false;

		for(var i = 0 ; i<array.length;i++){
			if(array[i] != "0" && encontradoPrimerElemento == false)
			{
				newTexto = newTexto + array[i];
				encontradoPrimerElemento = true;
			}
			else{
				if(encontradoPrimerElemento == true){
					newTexto = newTexto + array[i];
				}
			}
		}

		return newTexto;
	}

	function onCompleteNewItem(filterResult,suma){
		console.info("CALL onCompleteNewItem");
		console.info(filterResult);
		
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
			txtTMD_txtSku: filterResult.unitMeasureID,
			txtTMD_txtQuantity: 1,
			txtTMD_txtPrice: filterResult.Precio,
			txtTMD_txtSubTotal: filterResult.Precio ,
			txtTMD_txtIva: 0,
			txtTMD_skuQuantityBySku: 0,
			txtTMD_unitaryPriceInvidual: filterResult.Precio,
			txtTMD_skuFormatoDescription: filterResult.Medida,
			txtTMD_txtItemPrecio2: filterResult.Precio2,
			txtTMD_txtItemPrecio3: filterResult.Precio3,
			txtTMD_txtTransactionDetailNameDescription: filterResult.Nombre,
			txtTMD_txtTaxServices: 0,
			txtTMD_txtDetailLote: "",
			txtTMD_txtInfoVendedor: "",
			txtTMD_txtInfoSerie: "",
			txtTMD_txtInfoReferencia: "",
			txtTMD_txtItemPrecio1: filterResult.Precio,
			txtTMD_txtCatalogItemIDSku: null,
			txtTMD_txtRatioSku: 1,
			txtTMD_txtDiscountByItem: 0,
			txtTMD_txtCommisionByBankByItem: 0
		};
		
		
		
		
		indexDBObtenerProductos
		(
			"objListaProductosSkuX001",
			"all",
			0,
			'all',
			{'itemID': record.txtTMD_txtItemID},
			function(e){
				debugger;
				console.info("objListaProductosSkuX001");
				let itemID 			= e['itemID'];
				let allData 		= e['all'];
				let priceDefault	= record.txtTMD_txtPrice;
				
				debugger;
				var viewport = Ext.getCmp('miVentanaPrincipal'); // accede al viewport
				if(!viewport){
					return;
				}
				var grid 		= viewport.down('#gridDetailTransactionMaster'); // encuentra el grid
				var store 		= grid.getStore();	
			
				debugger;
				var resultado 		=  Ext.Array.filter(allData, function (producto) {
					return producto.itemID == record.txtTMD_txtItemID;
				});
				
				if (resultado.length > 0) {
					Ext.Array.each(resultado, function (producto) {
						let selected = parseInt(producto.predeterminado, 10);
						if(selected === 1){
							console.info(producto);
							record.txtTMD_txtCatalogItemIDSku				=producto.catalogItemID;
							record.txtTMD_skuFormatoDescription				=producto.name;
							record.txtTMD_txtPrice 							=producto.price;
							record.txtTMD_txtRatioSku 						=producto.value;
						}
					});
				}
				
				
				if (parseInt(record.txtTMD_txtPrice) == 0)
				{
					record.txtTMD_txtPrice = priceDefault;
				}
				
				
				store.add(record);
				debugger;
				fnGetConcept(record.txtTMD_txtItemID,"ALL");
				debugger;
				
			}
		);
		

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
	
</script>
