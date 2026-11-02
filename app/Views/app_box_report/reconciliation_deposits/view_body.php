<?php helper_getHtmlOfReportFilterOpen("Recuperación de cartera", "💵", "Caja"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field hidden">
		<label class="pv2-label" for="txtHourStartOn">Hora inicial</label>
		<input class="form-control" type="text" inputmode="numeric" maxlength="2" name="txtHourStartOn" id="txtHourStartOn" value="0" autocomplete="off">
	</div>

	<div class="prf-field hidden">
		<label class="pv2-label" for="txtHourEndOn">Hora final</label>
		<input class="form-control" type="text" inputmode="numeric" maxlength="2" name="txtHourEndOn" id="txtHourEndOn" value="23" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEmployeeNumber">Colaborador</label>
		<select name="txtEmployeeNumber" id="txtEmployeeNumber" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListEmployer)
				foreach($objListEmployer as $i){
					echo "<option value='".$i->employeNumber."'>".$i->firstName."</option>";
				}
				?>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
