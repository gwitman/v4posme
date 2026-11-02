<?php helper_getHtmlOfReportFilterOpen("Detalle de ventas formato 80mm", "📈", "Ventas"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtHoraInicial_hora">Hora inicial</label>
		<?php
		echo helper_GetHtmlControlHora("txtHoraInicial",helper_getDate());
		?>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtHoraFinal_hora">Hora final</label>
		<?php
		echo helper_GetHtmlControlHora("txtHoraFinal",helper_getDateTime());
		?>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
