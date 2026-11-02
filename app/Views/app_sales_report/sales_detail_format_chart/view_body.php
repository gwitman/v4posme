<?php helper_getHtmlOfReportFilterOpen("Gráfico de venta", "📈", "Ventas"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="<?php echo $objStartOn; ?>" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="<?php echo $objEndOn; ?>" autocomplete="off">
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>

<div class="pv2 prf">
	<div class="pv2-card">
		<div class="prf-charts">
			<div id="grafico1" class="prf-chart"></div>
			<div id="grafico2" class="prf-chart"></div>
			<div id="grafico3" class="prf-chart"></div>
			<div id="grafico4" class="prf-chart is-wide"></div>
		</div>
	</div>
</div>
