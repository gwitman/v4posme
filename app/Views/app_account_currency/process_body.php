<div class="pv2 pinv pinv-form">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-coins"></i></div>
			<div>
				<p class="pv2-header-title">Herramientas de monedas</p>
				<p class="pv2-header-sub">Procesar archivo, reporte y actualización del tipo de cambio</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="#" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
			<!-- botones Atras de cada tarjeta: se conservan ocultos por compatibilidad -->
			<a href="#" id="btnBackReport" class="btn btn-default" style="display:none"><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" id="btnBackExchangeRate" class="btn btn-default" style="display:none"><i class="icon16 i-rotate"></i> Atras</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<div class="row pacc-grid">

		<!-- procesar archivo -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file-upload"></i></span> Procesar archivo</p>
				<p class="pv2-help pacc-desc">Carga las tasas de cambio desde el archivo indicado.</p>
				<form id="form-new-account-level" name="form-new-rol" role="form" autocomplete="off" onsubmit="return false;">
					<div class="form-group">
						<label class="pv2-label" for="txtFile">Archivo</label>
						<input class="form-control" type="text" name="txtFile" id="txtFile" value="" autocomplete="off">
					</div>
					<div class="pacc-foot">
						<a href="#" id="btnAcept" class="btn btn-primary"><i class="icon16 i-checkmark-4"></i> Procesar archivo</a>
					</div>
				</form>
			</div>
		</div>
		<!-- /procesar archivo -->

		<!-- reporte de tipo de cambio -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Reporte de tipo de cambio</p>
				<p class="pv2-help pacc-desc">Muestra en una nueva pestaña las tasas registradas en el rango de fechas.</p>
				<form id="form-new-account-level-report" name="form-new-rol" role="form" autocomplete="off" onsubmit="return false;">
					<div class="row">
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
							<div class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
								<input size="16" class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
								<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtEndOn">Fecha final</label>
							<div class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
								<input size="16" class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
								<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
							</div>
						</div>
					</div>
					<div class="pacc-foot">
						<a href="#" id="btnAceptReport" class="btn btn-primary"><i class="icon16 i-checkmark-4"></i> Ver reporte</a>
					</div>
				</form>
			</div>
		</div>
		<!-- /reporte de tipo de cambio -->

		<!-- actualizar tipo de cambio -->
		<div class="col-xs-12 col-md-6">
			<div class="pv2-card">
				<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coin"></i></span> Actualizar tipo de cambio</p>
				<p class="pv2-help pacc-desc">Aplica el valor indicado a todos los días del rango de fechas.</p>
				<form id="form-new-account-level-exchange" name="form-new-rol" role="form" autocomplete="off" onsubmit="return false;">
					<div class="row">
						<div class="col-xs-12 form-group">
							<label class="pv2-label" for="txtValueExchangeRate">Valor</label>
							<input class="form-control pv2-num" type="text" inputmode="decimal" name="txtValueExchangeRate" id="txtValueExchangeRate" value="" autocomplete="off">
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtStartOnExchange">Fecha inicial</label>
							<div class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
								<input size="16" class="form-control" type="text" name="txtStartOnExchange" id="txtStartOnExchange" value="2014-01-30" autocomplete="off">
								<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 form-group">
							<label class="pv2-label" for="txtEndOnExchange">Fecha final</label>
							<div class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
								<input size="16" class="form-control" type="text" name="txtEndOnExchange" id="txtEndOnExchange" value="2014-01-30" autocomplete="off">
								<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
							</div>
						</div>
					</div>
					<div class="pacc-foot">
						<a href="#" id="btnAceptExchangeRate" class="btn btn-primary"><i class="icon16 i-checkmark-4"></i> Actualizar tipo de cambio</a>
					</div>
				</form>
			</div>
		</div>
		<!-- /actualizar tipo de cambio -->

	</div>
</div>
