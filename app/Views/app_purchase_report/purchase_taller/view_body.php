<?php helper_getHtmlOfReportFilterOpen("Reporte de taller", "🛒", "Compras"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="employerID">Colaboradores</label>
		<select name="employerID" id="employerID" class="select2" multiple >
				<option value="0">TODOS</option>
				<?php
				if($objListaEmployer)
				foreach($objListaEmployer as $i){
					echo "<option value='".$i->entityID."'>".$i->firstName."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtCustomerNumber">Código de cliente</label>
		<input type="text" name="txtCustomerNumber" id="txtCustomerNumber" class="form-control" autocomplete="off" />
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
