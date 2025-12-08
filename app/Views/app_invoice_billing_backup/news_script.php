<!-- ./ page heading -->
<script>
	var baseUrl = '<?php echo base_url(); ?>';
	var objConfigInit = {
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
			'txtUserID': '<?= $userID; ?>',
			'txtCompanyID' : '<?php echo $companyID ?>',
			'txtTransactionID' : '<?php echo $transactionID ?>',
			'txtTransactionMasterID' : '0',
			'txtCodigoMesero' : '<?= $codigoMesero;  ?>',
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
			'txtTMIReference1' : '',
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
			'txtLayFirstLineProtocolo' : '' ,
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
			'txtReceiptAmountTarjeta_Reference' : '',			
			'txtReceiptAmountTarjetaDol' : 0.00,
			'txtReceiptAmountTarjetaDol_Reference' : '',			
			'txtReceiptAmountBank' : 0.00,
			'txtReceiptAmountBank_Reference' : '',
			'txtReceiptAmountBankDol' : 0.00,
			'txtReceiptAmountBankDol_Reference' : '',
			'txtReceiptAmountPoint' : 0.00,
			'txtReceiptAmountTarjeta_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
			'txtReceiptAmountTarjetaDol_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
			'txtReceiptAmountBank_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
			'txtReceiptAmountBankDol_BankID' : '<?php
					$count = 0;
					if($objListBank)
					foreach($objListBank as $ws){
						if ($count == 0)
							echo $ws->bankID;								
						$count++;
					}
				?>', 
		},
		labels:{
			'txtTM_transactionNumber': 'PRF00000000'
		}
		
	};
					
					
	Ext.onReady(function () {

		miVentanaPrincipal = Ext.create('Ext.container.Viewport', {
			layout: 'fit',
			id: 'panelPrincipal',
			listeners:{
				afterrender: function(form) {
					// Configuración dinámica al "load" del contenedor
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
													id: 'txtCustomerCreditLineID'
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
															checked: false
														},
														{
															xtype: 'radiofield',
															boxLabel: 'No',
															name: 'txtCheckApplyExoneracion',
															inputValue: '0',
															checked: true
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
											title: 'Detalle de Productos',
											margin: '0 10 0 0',
											store: {
												fields: ['producto', 'cantidad', 'precio'],
												data: 	[]
											},
											
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
											
											columns: [
												{ text: 'Codigo', dataIndex: 'producto',  width: 100  },
												{ text: 'Producto', dataIndex: 'producto',  width: 100  },
												{ text: 'U/M', dataIndex: 'producto',  width: 250 },
												{ text: 'Cantidad', dataIndex: 'cantidad', width: 100 },
												{ text: 'Precio', dataIndex: 'precio', width: 100 },
												{ text: 'Sub total', dataIndex: 'precio', width: 100 },
												{ text: 'Accion', dataIndex: 'precio', width: 100 }
											],											
											selModel: 'rowmodel'  // permite seleccionar filas para eliminar
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
												{ fieldLabel: '% Descuento', value: 0 , name: 'txtPorcentajeDescuento' , id:'txtPorcentajeDescuento', },
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
			/*witman test*/
			title: 'Listado de Clientes',
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
		
		
	
		function fnBtnNuevaFactura() 
		{
			fnFillFactura(miVentanaPrincipal,null);
			//Ext.Msg.alert('Nueva', 'Factura guardada');
		}
		function fnBtnGuardarFactura()
		{
			Ext.Msg.alert('Guardar', 'Factura guardada');
		}
		function fnBtnEliminarFactura()
		{
			Ext.Msg.alert('Eliminar', 'Factura guardada');
		}
		function fnBtnActualizarPantalla()
		{
			
		}
		function fnBtnActualizarCatalogo()
		{
			
		}
		function fnBtnSeleccionarFactura()
		{
			miVentanaSeleccionFactura.show();
		}
		function fnBtnNuevoProducto()
		{
			
		}
		function fnBtnRegresar()
		{
			
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
		
		function fnBtnSeleccionProducto(btn) {
			
			var grid 		= miVentanaSeleccionProducto.down('grid');
			var seleccion 	= grid.getSelection();

			if (seleccion.length === 0) 
			{
				Ext.Msg.alert('Aviso', 'Debe seleccionar al menos un producto.');
				return;
			}
			
			debugger;
			var grid = btn.up('grid');  
			seleccion.forEach(function(record) 
			{	
				//miVentanaPrincipal.down('#txtCustomerID').setValue(  record.data.entityID  );
				//miVentanaPrincipal.down('#txtCustomerDescription').setValue(  record.data.Codigo + " " + record.data.Nombre );			
				debugger;
				
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
			
			
		}
		function fnBtnCancelarSeleccionCliente(btn) {
			btn.up('window').close();
		}
		
		function fnChangeTypePreiceID(combo, newValue, oldValue, eOpts) {
			//Ext.Msg.alert('Cambio detectado', 'Valor anterior: ' + oldValue + ', Nuevo valor: ' + newValue);
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



		function fnBtnEnterScaner (field, e) {
			if (e.getKey() === Ext.EventObject.ENTER) {
				var codigo = field.getValue();
				var grid = field.up('grid');

				// Ejemplo: agregar producto automáticamente al store
				grid.getStore().add({
					codigo: codigo,
					producto: 'Producto escaneado',
					um: 'UND',
					cantidad: 1,
					precio: 0,
					subtotal: 0
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
		
		if(formPanel.id == 'panelPrincipal')
		{
			
			
			
			
			var campoNombre = miVentanaPrincipal.down('#txtTM_transactionNumber');
			if(obj == null)
			{
				campoNombre.setText( objConfigInit.labels.txtTM_transactionNumber );
			}
			else
			{
				campoNombre.setText( objConfigInit.labels.txtTM_transactionNumber );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtUserID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtUserID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtUserID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtCompanyID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCompanyID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCompanyID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtTransactionID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtTransactionMasterID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionMasterID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTransactionMasterID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtCodigoMesero');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCodigoMesero );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCodigoMesero );
			}
			
			
			var campoNombre = miVentanaPrincipal.down('#txtDate');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDate );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDate );
			}
			
			
			var campoNombre = miVentanaPrincipal.down('#txtExchangeRate');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtExchangeRate );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtExchangeRate );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtNote');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtNote );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtNote );
			}
			
			
			var campoNombre = miVentanaPrincipal.down('#txtCurrencyID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCurrencyID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCurrencyID );
			}
			
			
			var campoNombre = miVentanaPrincipal.down('#txtCustomerID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtCustomerDescription');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerDescription );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCustomerDescription );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtReferenceClientName');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientName );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientName );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtReferenceClientIdentifier');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientIdentifier );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReferenceClientIdentifier );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtCausalID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtCausalID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtCausalID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtZoneID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtZoneID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtZoneID );
			}
			
			
			var campoNombre = miVentanaPrincipal.down('#txtTypePriceID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTypePriceID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTypePriceID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtWarehouseID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtWarehouseID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtWarehouseID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtReference3');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReference3 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReference3 );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtEmployeeID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtEmployeeID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtEmployeeID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtNumberPhone');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtNumberPhone );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtNumberPhone );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtMesaID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtMesaID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtMesaID );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtNextVisit');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtNextVisit );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtNextVisit );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtDateFirst');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDateFirst );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDateFirst );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtReference2');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReference2 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReference2 );
			}
			
			
			
			var campoNombre = miVentanaPrincipal.down('#txtTMIReference1');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTMIReference1 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTMIReference1 );
			}
			
			
			var campoNombre = miVentanaPrincipal.down('#txtPeriodPay');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtPeriodPay );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtPeriodPay );
			}
			
			
			var campoNombre = miVentanaPrincipal.down('#txtReference1');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReference1 );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReference1 );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtDayExcluded');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDayExcluded );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDayExcluded );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtFixedExpenses');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtFixedExpenses );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtFixedExpenses );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtLayFirstLineProtocolo');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtLayFirstLineProtocolo );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtLayFirstLineProtocolo );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtSubTotal');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtSubTotal );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtSubTotal );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtIva');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtIva );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtIva );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtPorcentajeDescuento');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtPorcentajeDescuento );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtPorcentajeDescuento );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtDescuento');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtDescuento );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtDescuento );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtServices');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtServices );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtServices );
			}
			
			var campoNombre = miVentanaPrincipal.down('#txtTotal');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtTotal );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtTotal );
			}
			
			
			var campoNombre = miVentanaDePago.down('#txtChangeAmount');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtChangeAmount );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtChangeAmount );
			}
			
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmount');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmount );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmount );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountDol');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountDol );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountDol );
			}
			
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjeta');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjetaDol');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol );
			}
			
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjetaDol_Reference');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_Reference );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_Reference );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBank');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBank_Reference');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_Reference );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_Reference );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBankDol');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBankDol_Reference');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_Reference );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_Reference );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountPoint');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountPoint );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountPoint );
			}
			
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjeta_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjeta_BankID );
			}
			
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountTarjetaDol_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountTarjetaDol_BankID );
			}
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBank_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBank_BankID );
			}
			
			
			var campoNombre = miVentanaDePago.down('#txtReceiptAmountBankDol_BankID');
			if(obj == null)
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_BankID );
			}
			else
			{
				campoNombre.setValue( objConfigInit.values.txtReceiptAmountBankDol_BankID );
			}
			
			
			
		}
		
	}
	
</script>
