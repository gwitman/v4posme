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
			<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
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
						<div class="col-lg-6">
							
								<div class="form-group">
									<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
									<div class="col-lg-8">
										<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
											<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" >
											<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
										</div>
									</div>
								</div>
								
							
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Cambio</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
										</div>
								</div>
								
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="normal">Descripcion</label>
									<div class="col-lg-8">										
										<input class="form-control"   type="text" name="txtNote" id="txtNote" value="">
									</div>
								</div>
							
						</div>
						<div class="col-lg-6">
						
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
								
								
							
								
								
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Cliente</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="">
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Cedula</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="">
										</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
									<div class="col-lg-8">
										<select name="txtCausalID" id="txtCausalID" class="select2">
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
										<select name="txtCustomerCreditLineID" id="txtCustomerCreditLineID" class="select2">
										</select>
									</div>
								</div>
								
							
						</div>
						</div>
						
					</div>
					<div class="tab-pane fade" id="profile">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Zona</label>
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
								
								<div class="form-group">
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
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Referencia2</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">												
										</div>
								</div>	
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Referencia3</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="">												
										</div>
								</div>											
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
												<?php
												$count = 0;
												if($listCurrency)
												foreach($listCurrency as $currency){
													if($count == 0 )
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
							
								<div class="form-group ">
										<label class="col-lg-4 control-label" for="normal">% De Gasto.</label>
										<div class="col-lg-8">
											<input class="form-control"   type="text" name="txtFixedExpenses" id="txtFixedExpenses" value="0">
											
											<a href="#" class="btn btn-primary  gap-right10" data-toggle="popover" data-placement="bottom" 
											data-content="Ejemplo: Del Interese de cada cuota, se multiplica por este % para saber de cuanto es la comision para FID-Local, este numero debe ser #0 o mayor que #1" title="" data-original-title="% de Gastos Fijo:">Ayuda:</a>
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Aplicado</label>
										<div class="col-lg-8">
											<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" >
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
						</div>
					</div>
					
					
					<div class="tab-pane fade" id="dropdown">
						
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
				if($isMobile != "1")
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
											<li><a href="#" id="btnViewDetailProductos" >VER DETALLES</a></li>
									</ul>
								</div>
							</div>
							<div class="col col-lg-2">
								<a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" class="btn btn-inverse  btn-block hidden btn-comando-factura" ><i class="icon16 i-rotate"></i> REGRESAR</a>
							</div>
						</div>
						
						<br/>
						
						<div class="row">
						
							<div class="col col-lg-2">
								<a href="<?php echo base_url(); ?>/app_invoice_billing/add" class="btn btn-flat btn-info btn-block hidden btn-comando-factura" id="btnNew"><i class="icon16 i-checkmark-4"></i> NUEVA FAC</a>
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
							<div class="col col-lg-2">
								<div class="btn-group  btn-block hidden btn-comando-factura">
									<button type="button" class="btn btn-flat btn-success dropdown-toggle  btn-block" data-toggle="dropdown"><i class="icon16 i-print"></i> COMANDOS <span class="caret"></span></button>
									<ul class="dropdown-menu">
											<li><a href="#" id="btnNewItem" >AGREGAR PRO</a></li>
											<li><a href="#" id="btnDeleteItem" >ELIMINAR PRO</a></li>
											<li><a href="#" id="btnNewItemCatalog" >NUEVO PRODUCTO</a></li>						
											<li><a href="#" id="btnRefreshDataCatalogo" >ACTUALIZAR CATALOGO</a></li>
											<li><a href="#" id="btnViewDetailProductos" >VER DETALLES</a></li>
											<li><a href="<?php echo base_url(); ?>/app_invoice_billing/index" id="btnBack" >REGRESAR</a></li>
											<li><a href="<?php echo base_url(); ?>/app_invoice_billing/add" id="btnNew">NUEVA FAC</a></li>
											
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
													<li>
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
					<?php
				}
				?>
				
				
				
				<br/>
				<br/>
				<input class="form-control"  type="text"  name="txtScanerCodigo" id="txtScanerCodigo" value="" >
				

				<div class="row">
					
					<div class="col-lg-4">
						<div class="page-header">
							<h3>Ref.</h4>
						</div>
						<ul class="list-unstyled">
							<li><h3>CC: <span class="red-smooth">*</span></h3></li>
							<li><i class="icon16 i-arrow-right-3"></i>Resumen de la factura</li>
							<li><i class="icon16 i-arrow-right-3"></i>Scaner: Control + k = Nuevo</li>
							<li><i class="icon16 i-arrow-right-3"></i>Scaner: Control + i = Abrir caja</li>
							<li><i class="icon16 i-arrow-right-3"></i>Ingreso Dolares: Control + b = Subir</li>
							
						</ul>

					</div>
					
					<div class="col-lg-4">
						<div class="page-header">
							<h3>Pago</h3>
						</div>
						<table class="<?php echo $isMobile == "1" ? "" : "table table-bordered  "  ?>" id="table-resumen" >
							<tbody>
								<tr>
									<th style="width:200px">INGRESO Cordoba</th>
									<td >
										<input type="text" id="txtReceiptAmount" name="txtReceiptAmount"  class="col-lg-12" value="" style="text-align:<?php $isMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								
								<tr>
									<th>INGRESO Dolares</th>
									<td >
										<input type="text" id="txtReceiptAmountDol" name="txtReceiptAmountDol"  class="col-lg-12" value="" style="text-align:<?php $isMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th>CAMBIO Cordoba</th>
									<td >
										<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly class="col-lg-12" value="" style="text-align:<?php $isMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="col-lg-4">
						<div class="page-header">
							<h3>Resumen</h3>
						</div>
						<table class="<?php echo $isMobile == "1" ? "" : "table table-bordered "  ?>" id="table-resumen-pago" >
							<tbody>
								<tr>
									<th style="width:200px" >SUB TOTAL</th>
									<td >
										<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="col-lg-12" value="" style="text-align:<?php $isMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th>IVA</th>
									<td >
										<input type="text" id="txtIva" name="txtIva" readonly class="col-lg-12" value="" style="text-align:<?php $isMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
								<tr>
									<th>TOTAL</th>
									<td >
										<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12" value="" style="text-align:<?php $isMobile != "1" ? "right" : "left"  ?>"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
				</div><!-- End .row-fluid  -->                                       
			</div>
			</form>
			<!-- /body -->
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



 

  <!-- Modal para facturar-->
  <div class="modal fade" id="mi_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog"  id="div-modal-dialog-lista-productos">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">LISTA DE PRODUCTOS</h4>
        </div>
		
        <div class="modal-body" >
         
			<table id="table_list_productos" style="width:100%" class="table table-bordered">
			
					<thead>
					  <tr>
						<th>itemID</th>
						<th>Codigo</th>						
						<th>Descripcion</th>
						<th>Unidad de Medida</th>
						<th>Cantidad</th>
						<th>Precio</th>
						<th>Barra</th>
					  </tr>
					</thead>
					<tbody id="table_list_productos_detail">
					</tbody>
					
			</table>
		 
			<br/>
		 
		 
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-default" data-dismiss="modal" >Aceptar</button>-->
		  <button type="button" class="btn btn-primary" id="btnAddProductoOnLine"  >Agregar</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Para ver cantidades-->
  <div class="modal fade" id="mi_modal_para_revisar" tabindex="-1" role="dialog" aria-labelledby="myModalLabelParaRevisar" aria-hidden="true">
    <div class="modal-dialog"  id="div-modal-dialog-lista-productos-para-revisar">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
          </button>
          <h4 class="modal-title" id="myModalLabelParaRevisar">REVISAR</h4>
        </div>
		
        <div class="modal-body" >
         
			<table id="table_list_productos_para_revisar" style="width:100%" class="table table-bordered">
			
					<thead>
					  <tr>
						<th>itemID</th>
						<th>Codigo</th>						
						<th>Descripcion</th>
						<th>Unidad de Medida</th>
						<th>Cantidad</th>
						<th>Precio</th>
						<th>Barra</th>
					  </tr>
					</thead>
					<tbody id="table_list_productos_detail_para_revisar">
					</tbody>
					
			</table>
		 
			<br/>
		 
		 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button>		  
        </div>
      </div>
    </div>
  </div>