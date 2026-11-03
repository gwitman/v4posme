<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-calendar-2"></i></div>
			<div>
				<p class="pv2-header-title">Periodo contable <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Registra el periodo y divide sus fechas en ciclos</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_period/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-period" name="form-new-rol" role="form" autocomplete="off">

		<!-- periodo -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-2"></i></span> Periodo</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4 form-group">
					<label class="pv2-label" for="txtNumber">Código de periodo</label>
					<input class="form-control" type="text" name="txtNumber" readonly="true" placeholder="YYYY" id="txtNumber" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control" type="text" name="txtName" id="txtName" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 form-group">
					<label class="pv2-label" for="txtStatusID">Estado</label>
					<select name="txtStatusID" id="txtStatusID" class="select2 select2statusIDPeriod">
							<option></option>
							<?php
							if($objListComponentPeriodStatus)
							foreach($objListComponentPeriodStatus as $i){
								echo "<option value='".$i->workflowStageID."'>".$i->name."</option>";
							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 form-group">
					<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
					<div class="input-group date" data-date-format="yyyy-mm-dd">
						<input size="16" class="form-control" type="text" name="txtStartOn" id="txtStartOn" autocomplete="off">
						<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 form-group">
					<label class="pv2-label" for="txtEndOn">Fecha final</label>
					<div class="input-group date" data-date-format="yyyy-mm-dd">
						<input size="16" class="form-control" type="text" name="txtEndOn" id="txtEndOn" autocomplete="off">
						<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
					</div>
				</div>
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtDescription">Descripción</label>
					<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"></textarea>
				</div>
			</div>
		</div>
		<!-- /periodo -->

		<!-- ciclos del periodo -->
		<div class="pv2-card" id="cardCycles">
			<div class="pv2-detail-head">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Ciclos del periodo <span class="pv2-count">0</span></p>
				<div class="pv2-actions">
					<a href="#" class="btn btn-default btn-sm" id="btnDeleteCycle" ><i class="icon16 i-remove"></i> Quitar marcados</a>
					<a href="#" class="btn btn-primary btn-sm" id="btnNewCycle" ><i class="icon16 i-plus"></i> Agregar ciclo</a>
				</div>
			</div>
			<div class="pv2-table-wrap">
				<table id="ListComponentCycle" class="pv2-table">
					<thead>
						<tr>
							<th class="pv2-col-check"></th>
							<th>Inicio</th>
							<th>Fin</th>
							<th>Estado</th>
							<th>Código de ciclo</th>
						</tr>
					</thead>
					<tbody id="tbody_detail">
					</tbody>
				</table>
			</div>
			<div class="pv2-empty">
				<i class="icon20 i-calendar-2"></i>
				Sin ciclos. Toca <b>Agregar ciclo</b> para dividir el periodo.
			</div>
		</div>
		<!-- /ciclos del periodo -->

	</form>
	<!-- /formulario -->

	<!-- plantilla de fila de ciclo (fuera del formulario; los 5 campos van en la misma fila y orden: el servidor los empareja por indice) -->
	<script type="text/template" id="tmpl_row_cycle">
		<tr class="row_cycle">
			<td class="pv2-col-check">
				<input type="checkbox" class="txtCycleIsActive" name="txtCycleIsActive[]" value="1" >
				<input type="hidden" name="txtComponentCycleID[]" />
			</td>
			<td>
				<div class="pv2-cycle-field">
					<div class="input-group date" data-date-format="yyyy-mm-dd">
						<input size="16" class="form-control txtCycleStartOn accountingCycleStart" type="text" name="txtCycleStartOn[]" autocomplete="off" >
						<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
					</div>
				</div>
			</td>
			<td>
				<div class="pv2-cycle-field">
					<div class="input-group date" data-date-format="yyyy-mm-dd">
						<input size="16" class="form-control txtCycleEndOn accountingCycleEnd" type="text" name="txtCycleEndOn[]" autocomplete="off" >
						<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
					</div>
				</div>
			</td>
			<td>
				<div class="pv2-cycle-field">
					<select name="txtCycleStatusID[]" id="txtCycleWorkflowStageID" class="select2 select2statusID">
							<option></option>
							<?php
							if($objListComponentCycleStatus)
							foreach($objListComponentCycleStatus as $i){
								echo "<option value='".$i->workflowStageID."'>".$i->name."</option>";
							}
							?>
					</select>
				</div>
			</td>
			<td>
				<div class="pv2-cycle-field">
					<input class="form-control" type="text" name="txtCycleNumber[]" readonly="true" value="" autocomplete="off">
				</div>
			</td>
		</tr>
	</script>

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Ciclos del periodo</p>
			<p class="pv2-mbar-total">0 ciclos</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
