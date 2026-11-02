<?php helper_getHtmlOfReportFilterOpen("Reporte de asistencia", "💵", "Caja"); ?>

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
		<input class="form-control" type="text" inputmode="numeric" maxlength="2" name="txtHourStartOn" id="txtHourStartOn" value="00" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtHourEndOn">Hora final</label>
		<input class="form-control" type="text" inputmode="numeric" maxlength="2" name="txtHourEndOn" id="txtHourEndOn" value="23" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtUserID">Usuario</label>
		<select name="txtUserID" id="txtUserID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListaUsuarios)
				foreach($objListaUsuarios as $i){
					echo "<option value='".$i->userID."'>".$i->nickname."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtFormat">Formato</label>
		<select name="txtFormat" id="txtFormat" class="select2">
				<option value="PDF" selected >PDF</option>
				<option value="HTML">HTML</option>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtSize">Medida</label>
		<select name="txtSize" id="txtSize" class="select2">
				<option value="80MM" selected >80MM</option>
				<option value="A4">A4</option>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
