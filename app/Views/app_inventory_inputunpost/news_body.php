<div class="pv2 pinv pinv-compra pinv-inputunpost pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-last"></i></div>
			<div>
				<p class="pv2-header-title">Compra #<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Registra la entrada de productos comprados a un proveedor</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_inputunpost/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-transaction" name="form-new-transaction" role="form" autocomplete="off">
	<div class="printArea">

		<div class="row">
			<div class="col-xs-12 col-md-8">

				<!-- documento -->
				<div class="pv2-card pinv-card-doc">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Documento</p>

					<!--tab menu-->
					<ul id="myTab" class="nav nav-tabs">
						<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
						<li><a href="#profile" data-toggle="tab">Referencias</a></li>
						<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
						<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
					</ul>
					<!--tab menu-->

					<!--tab content-->
					<div class="tab-content">
						<!--tab content general-->
						<div class="tab-pane fade in active" id="home">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-4 form-group">
									<label class="pv2-label" for="txtTransactionOn">Fecha</label>
									<div id="datepicker" class="input-group date"  data-date-format="yyyy-mm-dd">
										<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" >
										<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 form-group">
									<label class="pv2-label" for="txtStatusID">Estado</label>
									<select name="txtStatusID" id="txtStatusID" class="<?php echo ($useMobile == "1" ? "form-control" : "select2"); ?>">
											<option></option>
											<?php
											if($objListWorkflowStage)
											foreach($objListWorkflowStage as $ws){

												echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";

											}
											?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 form-group">
									<label class="pv2-label" for="txtWarehouseID">Bodega</label>
									<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
											<option></option>
											<?php
											if($objListWarehouse)
											foreach($objListWarehouse as $ws){
												if($ws->number == $warehouseDefault)
													echo "<option value='".$ws->warehouseID."' selected>".$ws->name."</option>";
												else
													echo "<option value='".$ws->warehouseID."' >".$ws->name."</option>";

											}
											?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 form-group">
									<label class="pv2-label" for="txtCurrencyID">Moneda</label>
									<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
											<?php
											$count = 0;
											if($objListCurrency)
											foreach($objListCurrency as $currency){
												if($count == 0 )
												echo "<option value='".$currency->currencyID."' data-simbol='".htmlspecialchars((string)$currency->simbol,ENT_QUOTES)."' selected >".$currency->name."</option>";
												else
												echo "<option value='".$currency->currencyID."' data-simbol='".htmlspecialchars((string)$currency->simbol,ENT_QUOTES)."'  >".$currency->name."</option>";
												$count++;
											}
											?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-4 form-group">
									<label class="pv2-label" for="txtCausalID">Causal</label>
									<select name="txtCausalID" id="txtCausalID" class="select2">
										<option></option>
										<?php
										$count = 0;
										if($objListCausal)
											foreach($objListCausal as $ws){
												echo "<option value='".$ws->transactionCausalID."'  >".$ws->name."</option>";
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<!--tab content general-->
						<!--tab content referencias-->
						<div class="tab-pane fade" id="profile">
							<div class="row">
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference1"><?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "lblReference1", "Referencia1",$objListCompanyPageSetting); ?></label>
									<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">
								</div>
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference2"><?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "lblReference2", "Referencia2",$objListCompanyPageSetting); ?></label>
									<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">
								</div>
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference3"><?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "lblReference3", "Referencia3",$objListCompanyPageSetting); ?></label>
									<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="">
								</div>
							</div>
						</div>
						<!--tab content referencias-->
						<!--tab content description-->
						<div class="tab-pane fade" id="dropdown">
							<div class="form-group">
								<label class="pv2-label" for="txtDescription">Nota</label>
								<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="4"></textarea>
							</div>
						</div>
						<!--tab content description-->
						<!--tab content file-->
						<div class="tab-pane fade" id="dropdown-file">
							<p class="pv2-help">Podras adjuntar archivos despues de guardar.</p>
						</div>
						<!--tab content file-->
					</div>
					<!--tab content-->
				</div>
				<!-- /documento -->

				<!-- proveedor -->
				<div class="pv2-card pinv-card-prov">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Proveedor</p>
					<div class="row">
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtProviderDescription">Proveedor</label>
							<div class="input-group pinv-search">
								<input type="hidden" id="txtProviderID" name="txtProviderID" value="<?php echo $providerDefault->entityID; ?>">
								<input class="form-control" readonly id="txtProviderDescription" type="txtProviderDescription" value="<?php echo $providerDefault->providerNumber; ?> / <?php echo $providerNaturalDefault->firstName; ?>">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btnClearProvider" title="Quitar">
										<i aria-hidden="true" class="i-undo-2"></i><span class="pinv-btn-text">Quitar</span>
									</button>
								</span>
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="btnSearchProvider" title="Buscar">
										<i aria-hidden="true" class="i-search-5"></i><span class="pinv-btn-text">Buscar</span>
									</button>
								</span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtCreditLineDescription">Linea de credito</label>
							<div class="input-group pinv-search">
								<input type="hidden" id="txtCreditLineID" name="txtCreditLineID" value="">
								<input class="form-control" readonly id="txtCreditLineDescription" type="txtCreditLineDescription" value="">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btnClearCreditLine" title="Quitar">
										<i aria-hidden="true" class="i-undo-2"></i><span class="pinv-btn-text">Quitar</span>
									</button>
								</span>
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="btnSearchCreditLine" title="Buscar">
										<i aria-hidden="true" class="i-search-5"></i><span class="pinv-btn-text">Buscar</span>
									</button>
								</span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtTransactionNumberOrdenCompra">Seleccionar plantilla</label>
							<div class="input-group pinv-search">
								<input type="hidden" id="txtTransactionMasterIDOrdenCompra" name="txtTransactionMasterIDOrdenCompra" value="">
								<input class="form-control" readonly id="txtTransactionNumberOrdenCompra" type="txtTransactionNumberOrdenCompra" value="">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btnClearOrdenCompra" title="Quitar">
										<i aria-hidden="true" class="i-undo-2"></i><span class="pinv-btn-text">Quitar</span>
									</button>
								</span>
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="btnSearchOrdenCompra" title="Buscar">
										<i aria-hidden="true" class="i-search-5"></i><span class="pinv-btn-text">Buscar</span>
									</button>
								</span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label">&nbsp;</label>
							<label class="pinv-check" for="txtIsTemplate">
								<input type="checkbox"   name="txtIsTemplate" id="txtIsTemplate" value="1" > Guardar como plantilla
							</label>
						</div>
					</div>
				</div>
				<!-- /proveedor -->

			</div>
			<div class="col-xs-12 col-md-4">

				<!-- resumen -->
				<div class="pv2-card pv2-sticky pinv-summary">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calculate"></i></span> Resumen</p>
					<div class="pinv-summary-row">
						<label for="txtSubTotal">Sub total</label>
						<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="form-control" value="0.00" style="text-align:right"/>
					</div>
					<div class="pinv-summary-row">
						<label for="txtDiscount">Descuento</label>
						<input type="text" id="txtDiscount" name="txtDiscount"  class="form-control" value="0.00" style="text-align:right"/>
					</div>
					<div class="pinv-summary-row">
						<label for="txtIva">IVA</label>
						<input type="text" id="txtIva" name="txtIva"  class="form-control" value="0.00" style="text-align:right" readonly="readonly"/>
					</div>
					<div class="pinv-summary-row <?php  echo $pantallaCompra_hiddenSummaryISC; ?>">
						<label for="txtIsc">ISC</label>
						<input type="text" id="txtIsc" name="txtIsc"  class="form-control" value="0.00" style="text-align:right" readonly="readonly"/>
					</div>
					<div class="pv2-total">
						<label class="pv2-label" for="txtTotal">Total <span class="pinv-moneda-simbolo"></span></label>
						<div class="pinv-total-valor"><span class="pinv-moneda-simbolo"></span><input type="text" id="txtTotal" name="txtTotal" readonly value="0.00" style="text-align:right"/></div>
					</div>
				</div>
				<!-- /resumen -->

			</div>
		</div>

		<!-- detalle del movimiento-->
		<div class="pv2-card pinv-card-detalle">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Detalle <span class="pinv-count">0</span></p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnClearCache" ><i class="icon16 i-loop-4"></i> Limpiar cache</a>
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteDetailTransaction" ><i class="icon16 i-remove"></i> Quitar marcados</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewDetailTransaction" ><i class="icon16 i-plus"></i> Agregar producto</a>
					<!--<a href="#" class="btn btn-flat btn-success" id="btnNewItemCatalog" >Nuevo producto</a>-->
				</div>
			</div>
			<div class="pinv-table-wrap">
				<table id="tb_transaction_master_detail" class="table table-bordered">
					<thead>
					  <tr>
						<th></th>
						<th>itemID</th>
						<th>transactionDetailID</th>
						<th>Codigo</th>
						<th>Nombre</th>
						<th>U/M</th>
						<th>Cantidad</th>
						<th>Costo</th>
						<th>Costo Neto</th>
						<th>Precio</th>
						<th>Lote</th><!--10-->
						<th>Expiracion</th><!--11-->
						<th>Mas</th><!--12-->
						<th>Precio1</th><!--13-->
						<th>Precio2</th><!--14-->
						<th>Expandir BarCode</th><!--15-->
						<th>IVA</th><!--16-->
						<th>ISC</th><!--17-->
						<th>Sub Total</th><!--18-->
					  </tr>
					</thead>
					<tbody id="body_detail_transaction">
					</tbody>
				</table>
			</div>
			<div class="pinv-empty">
				<i class="icon20 i-file-plus"></i>
				Selecciona el proveedor y la bodega, luego toca <b>Agregar producto</b> para empezar.
			</div>
		</div>
		<!-- detalle del movimiento-->

	</div>
	</form>
	<!-- formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Total de la compra</p>
			<p class="pv2-mbar-total">0</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
