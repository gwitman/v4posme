<div class="pv2 pinv pinv-compra pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-last"></i></div>
			<div>
				<p class="pv2-header-title">Ajuste de inventario #<span class="invoice-num"><?php echo $objTM->transactionNumber;?></span> <span class="pv2-chip<?php if($objTM->isApplied == 1) echo " is-applied"; ?>"><?php echo ($objTM->isApplied == 1) ? "Aplicado" : "Edición"; ?></span></p>
				<p class="pv2-header-sub">Revisa o modifica el ajuste de existencias</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_ajuste/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-transaction" name="form-new-transaction" role="form" autocomplete="off">
	<div class="printArea">

		<input type="hidden" name="txtCompanyID" value="<?php echo $objTM->companyID; ?>">
		<input type="hidden" name="txtTransactionID" value="<?php echo $objTM->transactionID; ?>">
		<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTM->transactionMasterID; ?>">

		<div class="row">
			<div class="col-xs-12 col-md-8">

				<!-- documento -->
				<div class="pv2-card">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Documento</p>

					<!--tab menu-->
					<ul id="myTab" class="nav nav-tabs">
						<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
						<li><a href="#profile" data-toggle="tab">Referencias</a></li>
						<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
						<li><a id="btnClickArchivo"  href="#dropdown-file" data-toggle="tab">Archivos</a></li>
					</ul>
					<!--tab menu-->

					<!--tab content-->
					<div class="tab-content">
						<!--tab content general-->
						<div class="tab-pane fade in active" id="home">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-3 form-group">
									<label class="pv2-label" for="txtTransactionOn">Fecha</label>
									<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
										<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo $objTM->transactionOn;?>">
										<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3 form-group">
									<label class="pv2-label" for="txtStatusID">Estado</label>
									<select name="txtStatusID" id="txtStatusID" class="select2">
											<option></option>
											<?php
											if($objListWorkflowStage)
											foreach($objListWorkflowStage as $ws){
												if($ws->workflowStageID == $objTM->statusID)
												echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
												else
												echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
											}
											?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3 form-group">
									<label class="pv2-label" for="txtWarehouseID">Bodega</label>
									<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
											<option></option>
											<?php
											if($objListWarehouse)
											foreach($objListWarehouse as $ws){
												if($ws->warehouseID == $objTM->targetWarehouseID)
													echo "<option value='".$ws->warehouseID."' selected>".$ws->name."</option>";
												else
													echo "<option value='".$ws->warehouseID."' >".$ws->name."</option>";
											}
											?>
									</select>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-3 form-group">
									<label class="pv2-label" for="txtCurrencyID">Moneda</label>
									<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
											<?php
											$count = 0;
											if($objListCurrency)
											foreach($objListCurrency as $currency){
												if($currency->currencyID == $objTM->currencyID )
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
						<!--tab content general-->
						<!--tab content referencias-->
						<div class="tab-pane fade" id="profile">
							<div class="row">
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference1">Referencia1</label>
									<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objTM->reference1; ?>">
								</div>
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference2">Referencia2</label>
									<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objTM->reference2; ?>">
								</div>
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference3">Referencia3</label>
									<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="<?php echo $objTM->reference3; ?>">
								</div>
							</div>
						</div>
						<!--tab content referencias-->
						<!--tab content description-->
						<div class="tab-pane fade" id="dropdown">
							<div class="form-group">
								<label class="pv2-label" for="txtDescription">Nota</label>
								<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="4"><?php echo $objTM->note; ?></textarea>
							</div>
						</div>
						<!--tab content description-->
						<!--tab content file-->
						<div class="tab-pane fade" id="dropdown-file">
							<p class="pv2-help">Los archivos del documento se abren en una ventana nueva.</p>
						</div>
						<!--tab content file-->
					</div>
					<!--tab content-->
				</div>
				<!-- /documento -->

				<!-- proveedor -->
				<div class="pv2-card">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Proveedor</p>
					<div class="row">
						<div class="col-xs-12 col-sm-8 form-group">
							<label class="pv2-label" for="txtProviderDescription">Proveedor</label>
							<div class="input-group pinv-search">
								<input type="hidden" id="txtProviderID" name="txtProviderID" value="<?php echo $objProvider->entityID; ?>">
								<input class="form-control" readonly id="txtProviderDescription" type="txtProviderDescription" value="<?php echo $objProvider->providerNumber."/".$objLegalDefault->comercialName ?>">
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
						<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="form-control" value="<?php echo floor($objTM->subAmount * 100) / 100; ?>" style="text-align:right"/>
					</div>
					<div class="pinv-summary-row">
						<label for="txtDiscount">Descuento</label>
						<input type="text" id="txtDiscount" name="txtDiscount"  class="form-control" value="<?php echo floor($objTM->discount * 100) / 100;  ?>" style="text-align:right"/>
					</div>
					<div class="pinv-summary-row">
						<label for="txtIva">IVA</label>
						<input type="text" id="txtIva" name="txtIva"  class="form-control" value="<?php echo floor($objTM->tax1 * 100) / 100;  ?>" style="text-align:right"/>
					</div>
					<div class="pv2-total">
						<label class="pv2-label" for="txtTotal">Total</label>
						<input type="text" id="txtTotal" name="txtTotal" readonly value="<?php echo floor($objTM->amount * 100) / 100;  ?>" style="text-align:right"/>
					</div>
				</div>
				<!-- /resumen -->

			</div>
		</div>

		<!-- detalle del movimiento-->
		<div class="pv2-card">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Detalle <span class="pinv-count">0</span></p>
				<div class="pv2-actions">
					<span class="pinv-import">
						<a href="#" class="btn btn-default btn-sm" id="btnImportDetailTransaction" ><i class="icon16 i-upload"></i> Importar</a>
						<input class="form-control"  type="text"  name="txtFileImport" id="txtFileImport" value=".csv" title="Extension del archivo a importar">
					</span>
					<a href="#" class="btn btn-default btn-sm" id="btnPrinterDetailTransaction" ><i class="icon16 i-print"></i> Imprimir</a>
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
						<th>Precio</th>
						<th>Lote</th><!--9-->
						<th>Expiracion</th><!--10-->
						<th>Mas</th><!--11-->
						<th>Precio1</th><!--12-->
						<th>Precio2</th><!--13-->
					  </tr>
					</thead>
					<tbody id="body_detail_transaction">
					</tbody>
				</table>
			</div>
			<div class="pinv-empty">
				<i class="icon20 i-file-plus"></i>
				Este ajuste no tiene productos. Toca <b>Agregar producto</b> para empezar.
			</div>
		</div>
		<!-- detalle del movimiento-->

	</div>
	</form>
	<!-- formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Ajuste #<?php echo $objTM->transactionNumber;?></p>
			<p class="pv2-mbar-total">0</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
