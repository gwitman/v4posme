<?php helper_getHtmlOfReportFilterOpen("Estado de cuenta", "👥", "Clientes"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtCustomerNumber">Cliente</label>
		<input type="text" name="txtCustomerNumber" id="txtCustomerNumber" class="form-control" autocomplete="off" />
		<!--
		<input size="16"  class="form-control" type="text" name="txtCustomerNumber" id="txtCustomerNumber" value="">
		-->
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
