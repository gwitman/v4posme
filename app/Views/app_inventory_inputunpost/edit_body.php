<div class="pv2 pinv pinv-compra pinv-inputunpost pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-last"></i></div>
			<div>
				<p class="pv2-header-title">Compra #<span class="invoice-num"><?php echo $objTM->transactionNumber;?></span> <span class="pv2-chip<?php if($objTM->isApplied == 1) echo " is-applied"; ?>"><?php echo ($objTM->isApplied == 1) ? "Aplicado" : "Edición"; ?></span></p>
				<p class="pv2-header-sub">Revisa o modifica la compra al proveedor</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_inputunpost/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
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
		<input type="hidden" name="txtTransactionNumber" value="<?php echo $objTM->transactionNumber ?>">

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
						<li><a id="btnClickArchivo"  href="#dropdown-file" data-toggle="tab">Archivos</a></li>
					</ul>
					<!--tab menu-->

					<!--tab content-->
					<div class="tab-content">
						<!--tab content general-->
						<div class="tab-pane fade in active" id="home">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-4 form-group">
									<label class="pv2-label" for="txtTransactionOn">Fecha</label>
									<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
										<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo $objTM->transactionOn;?>">
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
												if($ws->workflowStageID == $objTM->statusID)
												echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
												else
												echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
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
												if($ws->warehouseID == $objTM->targetWarehouseID)
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
												if($currency->currencyID == $objTM->currencyID )
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
												if($ws->transactionCausalID == $objTM->transactionCausalID)
												{
													echo "<option value='".$ws->transactionCausalID."' selected >".$ws->name."</option>";
												}else{
													echo "<option value='".$ws->transactionCausalID."'  >".$ws->name."</option>";
												}
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
									<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="<?php echo $objTM->reference1; ?>">
								</div>
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference2"><?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "lblReference2", "Referencia3",$objListCompanyPageSetting); ?></label>
									<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="<?php echo $objTM->reference2; ?>">
								</div>
								<div class="col-xs-12 col-sm-4 form-group">
									<label class="pv2-label" for="txtReference3"><?php echo getBahavioSession($company->type, "app_inventory_inputunpost", "lblReference3", "Referencia3",$objListCompanyPageSetting); ?></label>
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
				<div class="pv2-card pinv-card-prov">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Proveedor</p>
					<div class="row">
						<div class="col-xs-12 col-sm-6 form-group">
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
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtCreditLineDescription">Linea de credito</label>
							<div class="input-group pinv-search">
								<input type="hidden" id="txtCreditLineID" name="txtCreditLineID" value="<?php echo $objTM->tax4; ?>">
								<input class="form-control" readonly id="txtCreditLineDescription" type="txtCreditLineDescription" value="<?php
									if($objListCreditLine){
										foreach($objListCreditLine as $cl){
											if($cl->customerCreditLineID == $objTM->tax4){
												echo $cl->accountNumber;
											}
										}
									}
								?>">
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
								<input type="hidden" id="txtTransactionMasterIDOrdenCompra" name="txtTransactionMasterIDOrdenCompra" value="<?php echo $objTM->reference4; ?>">
								<input class="form-control" readonly id="txtTransactionNumberOrdenCompra" type="txtTransactionNumberOrdenCompra" value="<?php echo $objTMOrdenCompra != NULL ? $objTMOrdenCompra->transactionNumber : "";  ?>">
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
								<input type="checkbox"   name="txtIsTemplate" id="txtIsTemplate" value="1" <?php echo ($objTM->isTemplate == 1) ? "checked":""; ?>  > Guardar como plantilla
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
						<input type="text" id="txtSubTotal" name="txtSubTotal" readonly class="form-control" value="<?php echo number_format(floor($objTM->subAmount * 100) / 100,2,".",","); ?>" style="text-align:right"/>
					</div>
					<div class="pinv-summary-row">
						<label for="txtDiscount">Descuento</label>
						<input type="text" id="txtDiscount" name="txtDiscount"  class="form-control" value="<?php echo number_format(floor($objTM->discount * 100) / 100,2,".",",");  ?>" style="text-align:right"/>
					</div>
					<div class="pinv-summary-row">
						<label for="txtIva">IVA</label>
						<input type="text" id="txtIva" name="txtIva"  class="form-control" readonly="readonly" value="<?php echo number_format(floor($objTM->tax1 * 100) / 100,2,".",",");  ?>" style="text-align:right"/>
					</div>
					<div class="pinv-summary-row <?php  echo $pantallaCompra_hiddenSummaryISC; ?>">
						<label for="txtIsc">ISC</label>
						<input type="text" id="txtIsc" name="txtIsc"  class="form-control" readonly="readonly" value="<?php echo number_format(floor($objTM->tax2 * 100) / 100,2,".",",");  ?>" style="text-align:right"/>
					</div>
					<div class="pv2-total">
						<label class="pv2-label" for="txtTotal">Total <span class="pinv-moneda-simbolo"></span></label>
						<div class="pinv-total-valor"><span class="pinv-moneda-simbolo"></span><input type="text" id="txtTotal" name="txtTotal" readonly value="<?php echo number_format(floor($objTM->amount * 100) / 100,2,".",",");  ?>" style="text-align:right"/></div>
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
					<span class="pinv-import">
						<a href="#" class="btn btn-default btn-sm" id="btnImportDetailTransaction" ><i class="icon16 i-upload"></i> Importar</a>
						<!-- archivos .csv de la pestana Archivos, del mas nuevo al mas antiguo (se llena por AJAX: listarArchivosImportacion) -->
						<select class="form-control" name="txtFileImport" id="txtFileImport" autocomplete="off" title="Archivo .csv subido en Archivos">
							<option value=".csv">Seleccione archivo .csv</option>
						</select>
						<a href="#" class="btn btn-default btn-sm" id="btnImportRefrescarArchivos" title="Actualizar lista de archivos"><i class="icon16 i-loop-4"></i></a>
						<!-- importacion por lotes: 1 = el detalle ya se importo; modo de codigos repetidos: omitir | nuevo -->
						<input type="hidden" name="txtFileImportProcesado" id="txtFileImportProcesado" value="0">
						<input type="hidden" name="txtImportModoRepetidos" id="txtImportModoRepetidos" value="">
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
				Esta compra no tiene productos. Toca <b>Agregar producto</b> para empezar.
			</div>
		</div>
		<!-- detalle del movimiento-->

	</div>
	</form>
	<!-- formulario -->

	<div id="modalDialogOpenPrimter" title="Formato de Impresion" class="dialog">
		<p>Seleccione el formato que desea imprimir la factura</p>
	</div>

	<!-- Importacion CSV: aviso de codigos repetidos -->
	<div id="modalDialogImportRepetidos" title="Productos con codigos repetidos" class="dialog">
		<div class="pimp">
			<div class="pimp-head pimp-head-warn">
				<div class="pimp-icon">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.9L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
				</div>
				<div class="pimp-head-text">
					<p class="pimp-title">Codigos repetidos en el archivo</p>
					<p class="pimp-sub" id="lblImportRepetidosResumen"></p>
				</div>
			</div>
			<div class="pimp-body">
				<div id="divImportRepetidosLista" class="pimp-lista"></div>
				<div class="pimp-opciones">
					<div class="pimp-opcion"><b>Omitir repetidos</b>Se importa solo la primera fila de cada codigo.</div>
					<div class="pimp-opcion"><b>Generar codigo nuevo</b>Las filas repetidas se crean como productos nuevos con codigo automatico.</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Importacion CSV: progreso por lotes -->
	<div id="modalDialogImportProgreso" title="Importando archivo" class="dialog">
		<div class="pimp" id="divImportProgreso">
			<div class="pimp-head">
				<div class="pimp-icon">
					<svg class="pimp-ico-subir" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
					<svg class="pimp-ico-ok" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
				</div>
				<div class="pimp-head-text">
					<p class="pimp-title" id="lblImportTitulo">Importando productos</p>
					<p class="pimp-sub" id="lblImportArchivo"></p>
				</div>
				<div class="pimp-pct" id="lblImportPorcentaje">0%</div>
			</div>
			<div class="pimp-body">
				<div class="pimp-bar"><div id="barImportProgreso" class="pimp-bar-fill" style="width:0%"></div></div>
				<p class="pimp-status" id="lblImportProgreso">Preparando...</p>
				<div class="pimp-stats">
					<div class="pimp-stat pimp-stat-ok"><span id="lblImportInsertadas">0</span><small>Lineas</small></div>
					<div class="pimp-stat pimp-stat-new"><span id="lblImportNuevos">0</span><small>Productos nuevos</small></div>
					<div class="pimp-stat pimp-stat-skip"><span id="lblImportOmitidas">0</span><small>Omitidas</small></div>
				</div>
				<div class="pimp-note">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
					No cierre ni recargue la pagina hasta que termine.
				</div>
			</div>
		</div>
	</div>

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Compra #<?php echo $objTM->transactionNumber;?></p>
			<p class="pv2-mbar-total">0</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
