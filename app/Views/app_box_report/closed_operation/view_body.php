<?php helper_getHtmlOfReportFilterOpen("Cierre de caja", "💵", "Caja"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtHourStartOn">Hora inicial</label>
		<input class="form-control" type="time" step="1" name="txtHourStartOn" id="txtHourStartOn" value="0" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtHourEndOn">Hora final</label>
		<input class="form-control" type="time" step="1" name="txtHourEndOn" id="txtHourEndOn" value="23" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtUserID">Usuario</label>
		<select name="txtUserID" id="txtUserID" class="select2">
			<option value="0">TODOS</option>
			<?php
			if (isset($objListaUsuarios)) {
				foreach ($objListaUsuarios as $i) {
					echo "<option value='" . $i->userID . "'>" . $i->nickname . "</option>";
				}
			}
			?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtVerDetalle">Ver detalle</label>
		<select name="txtVerDetalle" id="txtVerDetalle" class="select2">
			<option value="1" selected>Si</option>
			<option value="2">No</option>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtFormato">Formato</label>
		<select name="txtFormato" id="txtFormato" class="select2">
			<option value="1" selected>Default</option>
			<option value="2">A4</option>
			<option value="3">80mm</option>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
