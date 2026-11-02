<?php helper_getHtmlOfReportFilterOpen("Reporte de pagos", "👥", "Clientes"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtCustomerNumber">Cliente</label>
		<select name="txtCustomerNumber" id="txtCustomerNumber" class="select2">
				<?php
				if($objListCustomer)
				foreach($objListCustomer as $i){
					echo "<option value='".$i->customerNumber."'>".$i->customerNumber." ".$i->firstName." ".$i->lastName."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtReference">Referencia</label>
		<input type="text" name="txtReference" id="txtReference" class="text form-control" autocomplete="off" />
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
