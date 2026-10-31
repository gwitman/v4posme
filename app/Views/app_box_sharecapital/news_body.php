<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">
<form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
			<div>
				<p class="pv2-header-title">Abono al capital #<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Registra el abono a capital de los documentos de credito del cliente</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_box_sharecapital/index" id="btnBack" class="btn btn-default"><i class="icon16 i-arrow-left"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- indicadores -->
	<div class="pv2-kpis">
		<div class="pv2-kpi k-purple">
			<div class="pv2-kpi-icon"><i class="icon16 i-file"></i></div>
			<div class="pv2-kpi-body"><span class="pv2-kpi-label">Documentos</span><span class="pv2-kpi-value" id="pv2KpiCount">0</span></div>
		</div>
		<div class="pv2-kpi k-info">
			<div class="pv2-kpi-icon"><i class="icon16 i-coin"></i></div>
			<div class="pv2-kpi-body"><span class="pv2-kpi-label">Saldo documentos</span><span class="pv2-kpi-value" id="pv2KpiBalance">0.00</span></div>
		</div>
		<div class="pv2-kpi k-warn">
			<div class="pv2-kpi-icon"><i class="icon16 i-temperature"></i></div>
			<div class="pv2-kpi-body"><span class="pv2-kpi-label">Tipo de cambio C$</span><span class="pv2-kpi-value"><?php echo sprintf("%01.2f",$exchangeRate); ?></span></div>
		</div>
		<div class="pv2-kpi k-ok k-wide">
			<div class="pv2-kpi-icon"><i class="icon16 i-checkmark-4"></i></div>
			<div class="pv2-kpi-body"><span class="pv2-kpi-label">Total del abono</span><span class="pv2-kpi-value" id="pv2KpiTotal">0.00</span></div>
		</div>
	</div>
	<!-- /indicadores -->

	<div class="row">
		<div class="col-md-8 col-lg-9">
			<div class="row">

				<!-- documento -->
				<div class="col-sm-6">
					<div class="pv2-card">
						<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Documento</p>
						<div class="row">
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="pv2-label" for="txtDate">Fecha</label>
								<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
									<input size="16" class="form-control" type="text" name="txtDate" id="txtDate" >
									<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 form-group">
								<label class="pv2-label" for="txtStatusID">Estado</label>
								<select name="txtStatusID" id="txtStatusID" class="select2">
										<option></option>
										<?php
										if($objListWorkflowStage)
										foreach($objListWorkflowStage as $ws){
											echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
										}
										?>
								</select>
							</div>
							<div class="col-xs-8 col-sm-6 form-group">
								<label class="pv2-label" for="txtExchangeRate">Tipo de cambio</label>
								<input class="form-control pv2-num" type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
							</div>
							<div class="col-xs-4 col-sm-6 form-group">
								<label class="pv2-label" for="txtIsApplied">Aplicado</label>
								<input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1" >
							</div>
						</div>
					</div>
				</div>
				<!-- /documento -->

				<!-- cliente -->
				<div class="col-sm-6">
					<div class="pv2-card">
						<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Cliente</p>
						<div class="form-group">
							<label class="pv2-label" for="txtCustomerDescription">Cliente</label>
							<div class="input-group">
								<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="">
								<input class="form-control" readonly id="txtCustomerDescription" type="text" value="" placeholder="Busca un cliente">
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="btnSearchCustomer" title="Buscar cliente">
										<i aria-hidden="true" class="i-search-5"></i> Buscar
									</button>
								</span>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btnClearCustomer" title="Limpiar cliente">
										<i aria-hidden="true" class="i-undo-2"></i>
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<!-- /cliente -->

			</div>

			<!-- detalle -->
			<div class="pv2-card">
				<div class="pv2-detail-head">
					<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Documentos a abonar <span class="pv2-count" id="pv2DetailCount">0</span></p>
					<div class="pv2-actions" style="width:auto">
						<a href="#" class="btn btn-default btn-sm hidden-xs" id="btnDeleteShare"><i class="icon16 i-remove"></i> Quitar marcados</a>
						<a href="#" class="btn btn-primary btn-sm" id="btnNewShare"><i class="icon16 i-plus"></i> Agregar documento</a>
					</div>
				</div>

				<div class="pv2-table-wrap" id="pv2DetailWrap" style="display:none">
					<table id="tb_transaction_master_detail" class="pv2-table">
						<thead>
							<tr>
								<th class="pv2-col-check"></th>
								<th>Documento</th>
								<th class="text-right">Saldo</th>
								<th class="text-right pv2-col-share">Abono</th>
								<th class="pv2-col-del"></th>
							</tr>
						</thead>
						<tbody id="body_tb_transaction_master_detail">
						</tbody>
					</table>
				</div>

				<div class="pv2-empty" id="pv2EmptyDetail">
					<i class="icon20 i-file-plus"></i>
					Selecciona el cliente y toca <b>Agregar documento</b> para empezar.
				</div>
			</div>
			<!-- /detalle -->

		</div>

		<!-- pago -->
		<div class="col-md-4 col-lg-3">
			<div class="pv2-card pv2-sticky">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Pago</p>
				<div class="form-group">
					<label class="pv2-label" for="txtReceiptAmount">Ingreso</label>
					<input type="text" id="txtReceiptAmount" name="txtReceiptAmount" class="form-control pv2-num txt-numeric" inputmode="decimal" value=""/>
				</div>
				<div class="pv2-change">
					<span>Cambio</span>
					<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly value=""/>
				</div>
				<div class="pv2-total">
					<label class="pv2-label" for="txtTotal">Total</label>
					<input type="text" id="txtTotal" name="txtTotal" readonly value=""/>
				</div>
			</div>
		</div>
		<!-- /pago -->
	</div>

	<!-- referencias y comentario -->
	<div class="row">
		<div class="col-md-8">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-link"></i></span> Referencias</p>
				<div class="row">
					<div class="col-sm-6 form-group">
						<label class="pv2-label" for="txtReferenceClientName">Persona ref.</label>
						<input class="form-control" type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="">
					</div>
					<div class="col-sm-6 form-group">
						<label class="pv2-label" for="txtReferenceClientIdentifier">ID persona ref.</label>
						<input class="form-control" type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="">
					</div>
					<div class="col-sm-4 form-group">
						<label class="pv2-label" for="txtReference1">Referencia 1</label>
						<input class="form-control" type="text" name="txtReference1" id="txtReference1" value="">
					</div>
					<div class="col-sm-4 form-group">
						<label class="pv2-label" for="txtReference2">Referencia 2</label>
						<input class="form-control" type="text" name="txtReference2" id="txtReference2" value="">
					</div>
					<div class="col-sm-4 form-group">
						<label class="pv2-label" for="txtReference3">Referencia 3</label>
						<input class="form-control" type="text" name="txtReference3" id="txtReference3" value="">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-bubble"></i></span> Comentario</p>
				<div class="form-group">
					<label class="pv2-label" for="txtNote">Descripcion</label>
					<textarea class="form-control" id="txtNote" name="txtNote" rows="4"></textarea>
				</div>
				<p class="pv2-help" style="margin:0">Podras adjuntar archivos despues de guardar.</p>
			</div>
		</div>
	</div>
	<!-- /referencias y comentario -->

</form>

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Total del abono &middot; cambio <span class="pv2-mbar-change" id="pv2MbarChange">0.00</span></p>
			<p class="pv2-mbar-total" id="pv2MbarTotal">0.00</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>


<script type="text/template" id="tmpl_row_document">
	<tr class="row_razon">
		<td class="pv2-col-check">
			<input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
			<input type="hidden" name="txtDetailCustomerCreditDocumentID[]" id="txtDetailCustomerCreditDocumentID" class="classDetailItem" />
			<input type="hidden" name="txtDetailTransactionDetailID[]" id="txtDetailTransactionDetailID" />
		</td>
		<td class="pv2-doc" data-label="Documento"><span id="txtDocument"></span></td>
		<td class="text-right" data-label="Saldo"><span id="txtBalanceShare"></span></td>
		<td class="pv2-col-share" data-label="Abono"><input class="form-control pv2-num txtDetailShare txt-numeric" type="text" inputmode="decimal" id="txtDetailShare" name="txtDetailShare[]" value="" /></td>
		<td class="pv2-col-del"><a href="#" class="btn btn-link pv2-btn-del btnShareRemoveRow" title="Quitar documento"><i class="icon16 i-remove"></i></a></td>
	</tr>
</script>
