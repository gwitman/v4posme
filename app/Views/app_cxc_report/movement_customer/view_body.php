<?php helper_getHtmlOfReportFilterOpen("Movimiento por clientes", "👥", "Clientes"); ?>

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
		<!--
		<input size="16"  class="form-control" type="text" name="txtCustomerNumber" id="txtCustomerNumber" value="">
		-->
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
