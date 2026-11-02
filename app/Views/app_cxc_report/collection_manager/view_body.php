<?php helper_getHtmlOfReportFilterOpen("Cartera de cobro", "👥", "Clientes"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<!-- Hora inicial y final: ocultas, el script las envia con su valor por defecto -->
	<input type="hidden" name="txtHourStartOn" id="txtHourStartOn" value="0">
	<input type="hidden" name="txtHourEndOn" id="txtHourEndOn" value="23">

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
