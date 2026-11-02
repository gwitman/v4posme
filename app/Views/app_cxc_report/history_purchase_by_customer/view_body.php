<?php helper_getHtmlOfReportFilterOpen("Reporte de histórico de compras", "👥", "Clientes"); ?>

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

	<div class="prf-field">
		<label class="pv2-label" for="txtTransactionCausalName">Tipo</label>
		<select name="txtTransactionCausalName" id="txtTransactionCausalName" class="select2">
				<option value="Todas">TODOS</option>
				<option value="Contado">Solo Contado</option>
				<option value="Credito">Solo Credito</option>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
