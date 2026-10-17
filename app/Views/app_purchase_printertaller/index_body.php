<style>
	.ptaller-wrapper .filter-card{
		border:0;
		border-radius:10px;
		box-shadow:0 2px 10px rgba(0,0,0,.08);
		background:#fff;
		margin-bottom:20px;
	}
	.ptaller-wrapper .filter-card .panel-heading{
		background:linear-gradient(135deg,#4e73df 0%,#224abe 100%);
		color:#fff;
		border-top-left-radius:10px;
		border-top-right-radius:10px;
	}
	.ptaller-wrapper .filter-card .panel-heading h4{ color:#fff; }
	.ptaller-wrapper .dash-card{
		border-radius:10px;
		color:#fff;
		padding:18px 20px;
		margin-bottom:20px;
		box-shadow:0 2px 10px rgba(0,0,0,.12);
	}
	.ptaller-wrapper .dash-card .dash-number{ font-size:34px; font-weight:700; line-height:1; }
	.ptaller-wrapper .dash-card .dash-label{ font-size:13px; text-transform:uppercase; letter-spacing:.5px; opacity:.9; }
	.ptaller-wrapper .dash-card .dash-icon{ font-size:40px; opacity:.35; float:right; }
	.ptaller-wrapper .bg-blue  { background:linear-gradient(135deg,#4e73df,#224abe); }
	.ptaller-wrapper .bg-green { background:linear-gradient(135deg,#1cc88a,#13855c); }
	.ptaller-wrapper .bg-orange{ background:linear-gradient(135deg,#f6c23e,#dda20a); }
	.ptaller-wrapper .bg-red   { background:linear-gradient(135deg,#e74a3b,#be2617); }
	.ptaller-wrapper .mini-list{ list-style:none; margin:0; padding:0; max-height:160px; overflow:auto; }
	.ptaller-wrapper .mini-list li{ display:flex; justify-content:space-between; padding:6px 10px; border-bottom:1px solid #f0f0f0; font-size:13px; }
	.ptaller-wrapper .mini-list li .badge{ background:#4e73df; }
	.ptaller-wrapper .grid-card{ border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,.08); }
	.ptaller-wrapper table#tableOrdenes thead th{ background:#f8f9fc; }
	.ptaller-wrapper .badge-estado{ background:#36b9cc; }

	/* Asegurar que el calendario del datepicker se vea solido y por encima del panel */
	.datepicker.dropdown-menu{
		background:#fff !important;
		border:1px solid #d9d9d9 !important;
		border-radius:6px;
		box-shadow:0 6px 18px rgba(0,0,0,.15) !important;
		z-index:9999 !important;
		padding:6px;
	}
	.datepicker table{ background:#fff; width:100%; }
	.datepicker td, .datepicker th{ background:#fff; border-radius:4px; color:#333; text-align:center; }
	/* Encabezado: nombre del mes + año y flechas de navegacion */
	.datepicker thead tr:first-child th{
		background:#4e73df !important;
		color:#fff !important;
		font-weight:600;
		padding:8px 6px;
	}
	.datepicker thead tr:first-child th:hover{ background:#3b5fd0 !important; }
	.datepicker .datepicker-switch{ font-size:14px; color:#fff !important; }
	.datepicker .prev, .datepicker .next{ color:#fff !important; font-size:16px; }
	/* Fila de nombres de dias (Lu, Ma, ...) */
	.datepicker thead tr .dow{ background:#f8f9fc !important; color:#777 !important; font-weight:600; font-size:11px; }
	.datepicker td.day:hover, .datepicker th:hover{ background:#eef1fb; }
	.datepicker td.active, .datepicker td.active:hover,
	.datepicker td.active.day, .datepicker span.active{ background:#4e73df !important; color:#fff !important; }
	.datepicker td.today, .datepicker td.today:hover{ background:#f6c23e !important; color:#fff !important; }
	.datepicker td.old, .datepicker td.new{ color:#bbb !important; }
	.datepicker td span{ border-radius:4px; }
	.datepicker td span:hover{ background:#eef1fb; }
</style>

<div class="ptaller-wrapper">

	<input type="hidden" id="txtCompanyID" value="<?php echo $companyID; ?>">
	<input type="hidden" id="txtTransactionID" value="<?php echo $transactionID; ?>">
	<input type="hidden" id="urlPrinterInput"  value="<?php echo $objParameterUrlPrinterInput; ?>">
	<input type="hidden" id="urlPrinterOutput" value="<?php echo $objParameterUrlPrinterOutput; ?>">

	<!-- Panel de Filtros -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default filter-card">
				<div class="panel-heading">
					<div class="icon"><i class="icon20 i-equalizer"></i></div>
					<h4>Filtros de busqueda</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<!-- Rango de fechas -->
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label class="control-label">Desde</label>
								<div id="datepickerStart" class="input-group date" data-date-format="yyyy-mm-dd">
									<input class="form-control" type="text" name="txtStartOn" id="txtStartOn">
									<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label class="control-label">Hasta</label>
								<div id="datepickerEnd" class="input-group date" data-date-format="yyyy-mm-dd">
									<input class="form-control" type="text" name="txtEndOn" id="txtEndOn">
									<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
								</div>
							</div>
						</div>

						<!-- Estado -->
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label class="control-label">Estado</label>
								<select name="txtStatusID" id="txtStatusID" class="select2" style="width:100%">
									<option value="">-- Todos --</option>
									<?php
									if($objListWorkflowStage)
									foreach($objListWorkflowStage as $ws){
										if(!$ws) continue;
										echo "<option value='".$ws->workflowStageID."'>".$ws->name."</option>";
									}
									?>
								</select>
							</div>
						</div>

						<!-- Colaborador (examinador) -->
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label class="control-label">Colaborador</label>
								<div class="input-group">
									<input type="hidden" id="txtEmployerID" value="">
									<input class="form-control" readonly id="txtEmployerDescription" type="text" placeholder="Todos">
									<span class="input-group-btn">
										<button class="btn btn-danger" type="button" id="btnClearEmployer"><i class="i-undo-2"></i></button>
									</span>
									<span class="input-group-btn">
										<button class="btn btn-primary" type="button" id="btnSearchEmployer"><i class="i-search-5"></i></button>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- Proyecto / Activo Fijo (examinador) -->
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label class="control-label">Proyecto</label>
								<div class="input-group">
									<input type="hidden" id="txtFixedAssetID" value="">
									<input class="form-control" readonly id="txtFixedAssetDescription" type="text" placeholder="Todos">
									<span class="input-group-btn">
										<button class="btn btn-danger" type="button" id="btnClearFixedAsset"><i class="i-undo-2"></i></button>
									</span>
									<span class="input-group-btn">
										<button class="btn btn-primary" type="button" id="btnSearchFixedAsset"><i class="i-search-5"></i></button>
									</span>
								</div>
							</div>
						</div>

						<!-- Boton filtrar -->
						<div class="col-lg-3 col-md-6">
							<div class="form-group">
								<label class="control-label">&nbsp;</label>
								<div>
									<button class="btn btn-success btn-block" type="button" id="btnFiltrar"><i class="icon16 i-search-5"></i> Filtrar / Buscar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Dashboard -->
	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="dash-card bg-blue">
				<i class="icon20 i-stack-3 dash-icon"></i>
				<div class="dash-number" id="dashTotal">0</div>
				<div class="dash-label">Total ordenes</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="dash-card bg-green">
				<i class="icon20 i-users-4 dash-icon"></i>
				<div class="dash-number" id="dashColaboradores">0</div>
				<div class="dash-label">Colaboradores</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="dash-card bg-orange">
				<i class="icon20 i-flow-tree dash-icon"></i>
				<div class="dash-number" id="dashEstados">0</div>
				<div class="dash-label">Estados</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="dash-card bg-red">
				<i class="icon20 i-wrench dash-icon"></i>
				<div class="dash-number" id="dashEquipos">0</div>
				<div class="dash-label">Estados equipo</div>
			</div>
		</div>
	</div>

	<!-- Agrupaciones -->
	<div class="row">
		<div class="col-lg-6">
			<div class="panel panel-default grid-card">
				<div class="panel-heading"><div class="icon"><i class="icon20 i-users-4"></i></div><h4>Ordenes por colaborador</h4></div>
				<div class="panel-body"><ul class="mini-list" id="listByEmployer"><li><span>Sin datos</span></li></ul></div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="panel panel-default grid-card">
				<div class="panel-heading"><div class="icon"><i class="icon20 i-flow-tree"></i></div><h4>Ordenes por estado</h4></div>
				<div class="panel-body"><ul class="mini-list" id="listByStatus"><li><span>Sin datos</span></li></ul></div>
			</div>
		</div>
	</div>

	<!-- Grid de ordenes -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default grid-card">
				<div class="panel-heading">
					<div class="icon"><i class="icon20 i-table"></i></div>
					<h4>Ordenes de taller</h4>
					<div class="btn-group pull-right" style="margin-top:0px">
						<button class="btn btn-info" type="button" id="btnPrintAllInput"><i class="icon16 i-printer-4"></i> Imprimir todas las entradas</button>
						<button class="btn btn-warning" type="button" id="btnPrintAllOutput"><i class="icon16 i-printer-4"></i> Imprimir todas las salidas</button>
					</div>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="tableOrdenes" style="width:100%">
							<thead>
								<tr>
									<th style="width:150px">Activo fijo</th>
									<th>#</th>
									<th>Documento</th>
									<th>Fecha</th>
									<th>Cliente</th>
									<th>Colaborador</th>
									<th>Estado</th>
									<th>Estado equipo</th>
									<th>Articulo</th>
									<th>Nombre activo fijo</th>
									<th style="width:180px">Impresion</th>
								</tr>
							</thead>
							<tbody id="bodyOrdenes">
								<tr><td colspan="11" class="text-center text-muted">Aplique un filtro para mostrar las ordenes de taller.</td></tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
