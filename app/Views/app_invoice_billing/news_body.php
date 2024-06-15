<div 
	class="isloading-overlay"
	id="divLoandingCustom"
	style="position:fixed; left:0; top:0; z-index: 10000; background: rgba(0,0,0,0.9); width: 100%; height: 1090px;"	
>
	<span class="isloading-wrapper  isloading-show  isloading-overlay">espere un momento ...  
		<i class="icon-refresh icon-spin">
		</i>
	</span>
</div>

<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<!--
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
			</div>
		</div> 
		-->
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>
<!-- End .row-fluid  -->

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
					
			<!-- titulo de comprobante-->
			<div class="panel-heading">
					<div class="icon"><i class="icon20 i-file"></i></div> 
					<h4>FACTURA:#<span class="invoice-num">00000000</span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form" >
			<div class="panel-body printArea"> 
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active">
						<a href="#home" data-toggle="tab">Informacion</a>
					</li>
					<li class="elementMovilOculto">
						<a href="#profile" data-toggle="tab">Referencias.</a>
					</li>
					
					<li>
						<a href="#credit" data-toggle="tab">Info de Credito.</a>
					</li>
					<li class="dropdown elementMovilOculto">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
							<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
						 </ul>
					</li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
						<div class="col-lg-6" id="divInformacionLeft" >
								
								<input type="hidden" id="txtCodigoMesero" name="txtCodigoMesero" value="<?php echo $codigoMesero;  ?>">
								<div class="form-group">
									<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
							
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divTxtCambio",""); ?> ">
										<label class="col-lg-4 control-label" for="normal">Cambio</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
										</div>
								</div>
								
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="normal">Descripcion</label>
									<div class="col-lg-8">										
										<input class="form-control"   type="text" name="txtNote" id="txtNote" value="sin comentarios.">
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divTxtMoneda",""); ?> " id="divMoneda" >
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($listCurrency)
												foreach($listCurrency as $currency){
													if( $currency->name == $objParameterACCOUNTING_CURRENCY_NAME_IN_BILLING  )
													echo "<option value='".$currency->currencyID."' selected >".$currency->name."</option>";
													else
													echo "<option value='".$currency->currencyID."'  >".$currency->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
							
						</div>
						<div class="col-lg-6" id="divInformacionRight" >
						
								<div class="form-group">
									<label class="col-lg-4 control-label" for="buttons">Cliente</label>
									<div class="col-lg-8">
										<div class="input-group">
											<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomerDefault->entityID;  ?>">
											<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
											
											<span class="input-group-btn">
												<button class="btn btn-danger" type="button" id="btnClearCustomer">
													<i aria-hidden="true" class="i-undo-2"></i>
													clear
												</button>
											</span>
											<span class="input-group-btn">
												<button class="btn btn-primary" type="button" id="btnSearchCustomer">
													<i aria-hidden="true" class="i-search-5"></i>
													buscar
												</button>
											</span>
											<!--
											<span class="input-group-btn">
												<button class="btn btn-success" type="button" id="btnSearchCustomerNew">
													<i aria-hidden="true" class="i-plus"></i>
													nuevo
												</button>
											</span>
											-->
											
										</div>
									</div>
								</div>
								
								
							
								
								
								
								<div class="form-group  <?php echo getBehavio($company->type,"app_invoice_billing","divTxtCliente2",""); ?>  ">
										<label class="col-lg-4 control-label" for="normal">Cliente</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="">
										</div>
								</div>
								
								<div id="divTxtElementoDisponibleParaMover1" class="hidden" >
									
								</div>
								
								<div class="form-group   <?php echo getBehavio($company->type,"app_invoice_billing","divTxtCedula2",""); ?> "  id="divCedula"  >
										<label class="col-lg-4 control-label" for="normal">Cedula</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="">
										</div>
								</div>
								
								<div class="form-group" id="divTipoFactura" >
									<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
									<div class="col-lg-8">
										<select name="txtCausalID" id="txtCausalID" class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objCaudal)
												foreach($objCaudal as $causal){
													if($count == 0 )
													echo "<option value='".$causal->transactionCausalID."' selected >".$causal->name."</option>";
													else
													echo "<option value='".$causal->transactionCausalID."'  >".$causal->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>


								<div class="form-group hidden" id="divLineaCredit">
									<label class="col-lg-4 control-label" for="selectFilter">Línea de Crédito</label>
									<div class="col-lg-8">
										<select name="txtCustomerCreditLineID" id="txtCustomerCreditLineID" class="<?php echo ($useMobile == "1" ? "" : "select2");  ?>" > 
										</select>
									</div>
								</div>
								
							
								<div id="divTxtElementoDisponibleParaMove2" class="hidden" >
									abc
								</div>
								
								
						</div>
						</div>
						
					</div>
					<div class="tab-pane fade" id="profile">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group <?php echo getBehavio($company->type,"app_invoice_billing","divTxtZone",""); ?>" id="divZone"  >
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_invoice_billing","divLabelZone","Zona"); ?></label>
									<div class="col-lg-8">
										<select name="txtZoneID" id="txtZoneID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListZone)
												foreach($objListZone as $z){
													if($count == 0 )
													echo "<option value='".$z->catalogItemID."' selected >".$z->display."</option>";
													else
													echo "<option value='".$z->catalogItemID."'  >".$z->display."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
									
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Precio</label>
									<div class="col-lg-8">
										<select name="txtTypePriceID" id="txtTypePriceID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListTypePrice)
												foreach($objListTypePrice as $price){
													if($price->catalogItemID == $objParameterTypePreiceDefault )
													echo "<option value='".$price->catalogItemID."' selected >".$price->display."</option>";
													else
													echo "<option value='".$price->catalogItemID."'  >".$price->display."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group" id="divBodega" >
									<label class="col-lg-4 control-label" for="selectFilter">Bodega</label>
									<div class="col-lg-8">
										<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListWarehouse)
												foreach($objListWarehouse as $ware){
													if($ware->typeWarehouse == $objParameterTipoWarehouseDespacho)
													echo "<option value='".$ware->warehouseID."' selected >".$ware->name."</option>";
													else
													echo "<option value='".$ware->warehouseID."'  >".$ware->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
			
							</div>
							
							<div class="col-lg-6">
								
							
								
								<div class="form-group" id="divReferencia">
										<label class="col-lg-4 control-label" for="normal">Referencia</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="<?php echo ($objEmployeeNatural ? $objEmployeeNatural->firstName : "N/D"); ?>">
										</div>
								</div>
								
								<div class="form-group" id="divVendedor">
										<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_invoice_billing","txtTraductionVendedor","Vendedor"); ?></label>
										<div class="col-lg-8">
											<select name="txtEmployeeID" id="txtEmployeeID" class="select2">
													<option></option>																
													<?php
													$count					= 0;
													$employerDefault 		= "true"; //$objParameterINVOICE_BILLING_EMPLOYEE_DEFAULT;
													
													if($objListEmployee)
													foreach($objListEmployee as $employee){
														if($count == 0 && $employerDefault == "true")
															echo "<option value='".$employee->entityID."' selected >".$employee->firstName."</option>";
														else
															echo "<option value='".$employee->entityID."'  >".$employee->firstName."</option>";
														$count++;
													}
													?>
											</select>
										</div>
								</div>

								
								<div class="form-group"  >
										<label class="col-lg-4 control-label" for="normal">Telefono</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtNumberPhone" id="txtNumberPhone" value="">												
										</div>
								</div>	
								
							
								
								<div class="form-group  <?php echo getBehavio($company->type,"app_invoice_billing","divMesa",""); ?>" id="divMesa" >
									<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_invoice_billing","txtTraductionMesa","Mesa"); ?></label>
									<div class="col-lg-8">
										<select name="txtMesaID" id="txtMesaID" class="select2">
												<option></option>																
												<?php
												$count = 0;
												if($objListMesa)
												foreach($objListMesa as $ware){
													if($count == 0)
													echo "<option value='".$ware->catalogItemID."' selected >".$ware->name."</option>";
													else
													echo "<option value='".$ware->catalogItemID."'  >".$ware->name."</option>";
													$count++;
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group" id="divSiguienteVisita">
									<label class="col-lg-4 control-label" for="datepicker">Siguiente Visita</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtNextVisit" id="txtNextVisit" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
					
					<div class="tab-pane fade" id="credit">
						<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Proveedor de Credito</label>
											<div class="col-lg-8">
												<!--
												<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
												-->
												<select name="txtReference1" id="txtReference1" class="select2">
														<option value="0"></option>		
														<?php
														$index = -1;
														if($listProvider)
														foreach($listProvider as $ws){
																$index = $index + 1;																
																if($index == 0)
																echo "<option value='".$ws->entityID."' selected >".$ws->firstName." ".$ws->lastName."</option>";
																else 
																echo "<option value='".$ws->entityID."' >".$ws->firstName." ".$ws->lastName."</option>";	
														}
														?>
												</select>
											</div>
									</div>
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Aplicado</label>
											<div class="col-lg-8">
												<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" >
											</div>
									</div>
								
									<div class="form-group ">
											<label class="col-lg-4 control-label" for="normal">% De Gasto.</label>
											<div class="col-lg-8">
												<input class="form-control"   type="text" name="txtFixedExpenses" id="txtFixedExpenses" value="0">
												<!--
												<a href="#" class="btn btn-primary  gap-right10" data-toggle="popover" data-placement="bottom" 
												data-content="Ejemplo: Del Interese de cada cuota, se multiplica por este % para saber de cuanto es la comision para FID-Local, este numero debe ser #0 o mayor que #1" title="" data-original-title="% de Gastos Fijo:">Ayuda:</a>
												-->
												
											</div>
									</div>
									
								
									
									<div class="form-group hide">
											<label class="col-lg-4 control-label" for="normal">Primer Linea del Protocolo.</label>
											<div class="col-lg-8">
												<input class="form-control"   type="text" name="txtLayFirstLineProtocolo" id="txtLayFirstLineProtocolo" value="">
												
												<a href="#" class="btn btn-primary  gap-right10" data-toggle="popover" data-placement="bottom" 
												data-content="Ejemplo:  5" title="" 
												data-original-title="Tenor:">Ayuda:</a>
												
												
											</div>
									</div>
									
									
								</div>
								<div class="col-lg-6">
								
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Primer Pago</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDateFirst" id="txtDateFirst" value="" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Plazo ó Referencia2</label>
											<div class="col-lg-8">
												<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo  $objParameterCXC_PLAZO_DEFAULT; ?>">												
											</div>
									</div>	
									
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Frecuencia</label>
											<div class="col-lg-8">
												<!--
												<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
												-->
												<select name="txtPeriodPay" id="txtPeriodPay" class="select2">
														<option value="0"></option>		
														<?php
														$index = -1;
														if($objListPay)
														foreach($objListPay as $ws){
																$index = $index + 1;																
																if($ws->catalogItemID == $objParameterCXC_FRECUENCIA_PAY_DEFAULT )
																	echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																else 
																	echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";	
														}
														?>
												</select>
											</div>
									</div>
									
									
								</div>
						</div>
						
						<div class="row">
							<div class="col-lg-6">
							
							
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"></label>
										<div class="col-lg-8">
											 <label class="label-change-switch" id="txtLabelIsDesembolsoEfectivo">Es un desembolso en efectivo?</label>
											 <br/>
											 <div class="switch" data-on="success" data-off="warning">
												<input class="toggle"controls-row type="checkbox" checked id="txtCheckDeEfectivo" />
											</div>																
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal"></label>
										<div class="col-lg-8">
											
											<label class="label-change-switch" id="txtLabelIsReportSinRiesgo">Reportar a SinRiesgo</label>
											<br/>
											
											<div class="switch" data-on="success" data-off="warning">												
												<input class="toggle"controls-row type="checkbox" checked id="txtCheckReportSinRiesgo" name="txtCheckReportSinRiesgo" value="1"  />
											</div>																
										</div>
										
								</div>
								
								
								
							</div>
							<div class="col-lg-6">
							
								
								
							</div>
						</div>
					</div>
					
					
					<div class="tab-pane fade" id="dropdown">
						<div class="form-group">
								<label class="col-lg-2 control-label" for="normal">Procedimiento</label>
								<div class="col-lg-10">
									<textarea class="form-control" type="text"  name="txtTMIReference1" id="txtTMIReference1" ></textarea>
								</div>
						</div>						
					</div>
					<div class="tab-pane fade" id="dropdown-file">
						
					</div>
				</div>    

		
				<br/>
				
				<div class="row">
					<div class="col-lg-12">
						<h3>Detalle:</h3>
						<table id="tb_transaction_master_detail" class="table table-bordered"  >
							<thead>
							  <tr>
								<th></th>
								<th></th>
								<th></th>
								<th>Codigo</th>
								<th>Descripcion</th>
								<th>U/M</th>
								<th>Cantidad</th>													
								<th>Precio</th>
								<th>Total</th>
								<th></th>
								<th>skuQuantityBySku</th>
								<th>unitaryPriceInvidual</th>
								<th>Accion</th>
								<th>skuFormatoDescription</th>
								<th>Precio2</th>
								<th>Precio3</th>
							  </tr>
							</thead>
							<tbody id="body_tb_transaction_master_detail">
							</tbody>
						</table>
						
					</div><!-- End .col-lg-12  --> 
				</div><!-- End .row-fluid  -->
				<?php
				$countWorkflow 		= 0;
				$valueWorkflowFirst = 0;
				if($objListWorkflowStage)
				foreach($objListWorkflowStage as $ws){
					$countWorkflow++;
					if($countWorkflow == 1)
						$valueWorkflowFirst = $ws->workflowStageID;
				}
				?>
				
				<input class="form-control"  type="hidden"  name="txtStatusID" id="txtStatusID" value="<?php echo $valueWorkflowFirst; ?>" >
				
				<br/>
				
				<?php
				if($useMobile != "1")
				{
					?>
						
						<div class="row">
							<div class="col col-lg-2">
								<a href="#" class="btn btn-flat btn-info   btn-block hidden btn-comando-factura" id="btnNewItem" ><i class="icon16 i-print"></i> AGREGAR PRO</a>
							</div>
							<div class="col col-lg-2">
								<a href="#" class="btn btn-flat btn-danger  btn-block hidden btn-comando-factura" id="btnDeleteItem" ><i class="icon16 i-print"></i> ELIMINAR PRO</a>					
							</div>
							<div class="col col-lg-2">
								<div class="btn-group  btn-block">
									<button type="button" class="btn btn-flat btn-success dropdown-toggle  btn-block hidden btn-comando-factura" data-toggle="dropdown"><i class="icon16 i-print"></i> PRODUCTO <span class="caret"></span></button>
									<ul class="dropdown-menu">
											<li><a href="#" id="btnNewItemCatalog" >NUEVO PRODUCTO</a></li>						
											<li><a href="#" id="btnRefreshDataCatalogo" >ACTUALIZAR CATALOGO</a></li>											
									</ul>
								</div>
							</div>
							<div class="col col-lg-2">
								<div class="btn-group btn-block  hidden btn-comando-factura ">
									<button  type="button" class="btn btn-flat btn-inverse dropdown-toggle btn-block" data-toggle="dropdown"><i class="icon16 i-pencil"></i> SELECCION <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="#" id="btnBack"  >REGRESAR</a></li>
										<li><a href="#" id="btnSelectInvoice"  >SELECCIONAR</a></li>
									</ul>
								</div>
							</div>
							
						</div>
						
						<br/>
						
						<div class="row">
						
							<div class="col col-lg-2">
								<a href="<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>" class="btn btn-flat btn-info btn-block hidden btn-comando-factura" id="btnNew"><i class="icon16 i-checkmark-4"></i> NUEVA FAC</a>
							</div>
							
							<div class="col col-lg-2">
									
									<?php 
									if ($objParameterInvoiceAutoApply == "true"){
										?>
										<a href="#" class="btn btn-warning  btn-block hidden btn-comando-factura" id="btnAcept"><i class="icon16 i-checkmark-4"></i> 
										APLICAR
										</a>
										<?php
									}
									else{
										?>
										<a href="#" class="btn btn-warning  btn-block hidden btn-comando-factura" id="btnAcept"><i class="icon16 i-checkmark-4"></i> 
										REGISTRAR
										</a>
										<?php 
									}
									?>
								
							</div>
						</div>

						

					<?php
				}
				else{
					?>
					
					<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-info dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-pencil"></i> PRO <span class="caret"></span></button>
									<ul class="dropdown-menu">
											<li><a href="#" id="btnNewItem" >AGREGAR PRO</a></li>
											<li><a href="#" id="btnDeleteItem" >ELIMINAR PRO</a></li>											
									</ul>
								</div>
							</div>
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-danger dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-print"></i> FAC <span class="caret"></span></button>
									<ul class="dropdown-menu">											
											<li><a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" >REGRESAR</a></li>
											<li><a href="<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/<?php echo $codigoMesero; ?>" id="btnNew">NUEVA FAC</a></li>
											<li><a href="#" id="btnSelectInvoice"  > SELECCIONAR</a></li>
									</ul>
								</div>
							</div>
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-primary dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-cloud"></i> SALV <span class="caret"></span></button>
									<ul class="dropdown-menu">
											<?php 
												if ($objParameterInvoiceAutoApply == "true"){
													?>
													<li>
													<a href="#" id="btnAcept">
													APLICAR
													</a>
													</li>
													<?php
												}
												else{
													?>
													<li class="badge-info">
													<a href="#" id="btnAcept">
													REGISTRAR
													</a>
													</li>
													<?php 
												}
											?>
									</ul>
								</div>
							</div>
					</div>
					<br/>					
					<div class="row">
							<div class="col col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-success dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon32 i-search"></i> MAS <span class="caret"></span></button>
									<ul class="dropdown-menu">											
											<li><a href="#" id="btnNewItemCatalog" >NUEVO PRODUCTO</a></li>						
											<li><a href="#" id="btnRefreshDataCatalogo" >ACTUALIZAR CATALOGO</a></li>
									</ul>
								</div>
							</div>							
					</div>
					<?php
				}
				?>
				
				
				
				<br/>
				<br/>
				<input class="form-control"  type="text"  name="txtScanerCodigo" id="txtScanerCodigo" value="" >
				

				<div class="row">
					
					<div class="col-lg-3 <?php echo getBehavio($company->type,"app_invoice_billing","panelResumenFacturaTool",""); ?>   " id="panelResumenFacturaTool" >
						<div class="page-header">
							<h3>Tool Calcular Monto sin Iva</h3>
						</div>
						<table class="<?php echo $useMobile == "1" ? "" : "table table-bordered "  ?>"  >
							<tbody>
								<tr>
									<th style="width:200px;text-align:left;" >01) MONTO</th>
									<td >
										<input type="text" id="txtToolMontoConIva" name="txtToolMontoConIva"  class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">02) </th>
									<td >
										<a href="#" class="btn btn-warning  btn-block" id="txtToolCalcular"><i class="icon16 i-checkmark-4"></i> CALCULAR</a>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">03) MONTO SIN IVA</th>
									<td >
										<input type="text" id="txtToolMontoSinIva" name="txtToolMontoSinIva"  class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					
					
					<div class="col-lg-3 col-sm-12 <?php echo getBehavio($company->type,"app_invoice_billing","panelResumenFactura",""); ?>   " id="panelResumenFactura" >
						<div class="page-header">
							<h3 id="labelRef" >Ref.</h4>
						</div>
						<ul class="list-unstyled">
							<li><h3>CC: <span class="red-smooth">*</span></h3></li>
							<li><i class="icon16 i-arrow-right-3"></i>Resumen de la factura</li>
							<li><i class="icon16 i-arrow-right-3"></i>Scaner: Control + k = Nuevo</li>
							<li><i class="icon16 i-arrow-right-3"></i>Scaner: Control + i = Abrir caja</li>
							<li><i class="icon16 i-arrow-right-3"></i>Ingreso Dolares: Control + b = Subir</li>
							
						</ul>

					</div>
					
					<div class="col-lg-5 col-sm-12">
						<div class="page-header">
							<h3>Pago</h3>
						</div>
						<table class="<?php echo $useMobile == "1" ? "" : "table table-bordered  "  ?>" id="table-resumen" >
							<tbody>
							
							
								
								<tr>
									<th style="text-align:left" >01) CAMBIO</th>
									<td >
										<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>								
								<tr>
									<th style="text-align:left">02) MON.</th>
									<td >
										<input type="text" id="txtReceiptAmount" name="txtReceiptAmount"  class="col-lg-12 txt-numeric" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									
								</tr>
								
								<tr>
									<th style="text-align:left" >03) MON. EXT.</th>
									<td >
										<input type="text" id="txtReceiptAmountDol" name="txtReceiptAmountDol"  class="col-lg-12 txt-numeric" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									
								</tr>
								
								
								<tr>
									<th style="text-align:left">04) Tarjeta. Nac.</th>
									<td style="">
										<input type="text" id="txtReceiptAmountTarjeta" name="txtReceiptAmountTarjeta"   class="col-lg-12 txt-numeric" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td style="">
										<select name="txtReceiptAmountTarjeta_BankID" id="txtReceiptAmountTarjeta_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($count == 0 )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>
									</td>
									<td style="">
										<input type="text" id="txtReceiptAmountTarjeta_Reference" name="txtReceiptAmountTarjeta_Reference"   class="col-lg-12" value="" />
									</td>
								</tr>
								<tr>
									<th style="text-align:left">05) Tarjeta. Ext.</th>
									<td>
										<input type="text" id="txtReceiptAmountTarjetaDol" name="txtReceiptAmountTarjetaDol"   class="col-lg-12 txt-numeric" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td>
										<select name="txtReceiptAmountTarjetaDol_BankID" id="txtReceiptAmountTarjetaDol_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($count == 0 )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>
									</td>
									<td>
										<input type="text" id="txtReceiptAmountTarjetaDol_Reference" name="txtReceiptAmountTarjetaDol_Reference"   class="col-lg-12" value="" />
									</td>
								</tr>
								
								<tr>
									<th style="text-align:left">06) TRANS. Nac.</th>
									<td >
										<input type="text" id="txtReceiptAmountBank" name="txtReceiptAmountBank"  class="col-lg-12 txt-numeric" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td>
										<select name="txtReceiptAmountBank_BankID" id="txtReceiptAmountBank_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($count == 0 )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>
									</td>
									<td>
										<input type="text" id="txtReceiptAmountBank_Reference" name="txtReceiptAmountBank_Reference"   class="col-lg-12" value="" />
									</td>
								</tr>
								<tr>
									<th style="text-align:left">07) TRANS. Ext.</th>
									<td >
										<input type="text" id="txtReceiptAmountBankDol" name="txtReceiptAmountBankDol"  class="col-lg-12 txt-numeric" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
									<td>										
										<select name="txtReceiptAmountBankDol_BankID" id="txtReceiptAmountBankDol_BankID"  class="<?php echo ($useMobile == "1" ? "" : "select2"); ?>">
												<?php
												$count = 0;
												if($objListBank)
												foreach($objListBank as $bank){
													if($count == 0 )
													echo "<option value='".$bank->bankID."' selected >".$bank->name."</option>";
													else
													echo "<option value='".$bank->bankID."'  >".$bank->name."</option>";
													$count++;
												}
												?>
										</select>										
									</td>
									<td>
										<input type="text" id="txtReceiptAmountBankDol_Reference" name="txtReceiptAmountBankDol_Reference"   class="col-lg-12" value="" />
									</td>
								</tr>
								
								<tr>
									<th style="text-align:left" >08) Pt</th>
									<td >
										<input type="text" id="txtReceiptAmountPoint" name="txtReceiptAmountPoint"  class="col-lg-12 txt-numeric" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>								
								</tr>
								
								
							</tbody>
						</table>
					</div>
					
					<div class="col-lg-4 col-sm-12 ">
						<div class="page-header">
							<h3>Resumen</h3>
						</div>
						<table class="<?php echo $useMobile == "1" ? "" : "table table-bordered "  ?>" id="table-resumen-pago" >
							<tbody>
								<tr>
									<th style="text-align:left;" >01) SUB TOTAL</th>
									<td >
										<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">02) IVA</th>
									<td >
										<input type="text" id="txtIva" name="txtIva" readonly class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th style="text-align:left">03) TOTAL</th>
									<td >
										<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12" value="" style="text-align:<?php $useMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
				</div><!-- End .row-fluid  -->                                       
			</div>
			</form>
			<!-- /body -->
			
			
			<div id="modalDialogBackList" title="Regresar a la lista" class="dialog">
				<p>Seguro que desea regresa a la lista</p>
			</div>
			
			<div id="modalDialogOpenPrimter" title="Impresion" class="dialog">
				<p>Desea imprmir la factura en formato 80mm</p>
			</div>
			
			
		</div>
	</div>
</div>

<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<!--
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
			</div>
		</div> 
		-->
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>
<!-- End .row-fluid  -->



<?php echo getBehavio($company->type,"app_invoice_billing","divTraslateElement",""); ?>  
 