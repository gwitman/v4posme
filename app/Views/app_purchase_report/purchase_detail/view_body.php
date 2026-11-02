<?php helper_getHtmlOfReportFilterOpen("Detalle de compras", "🛒", "Compras"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtProviderID">Proveedor</label>
		<select name="txtProviderID" id="txtProviderID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListProvider)
				foreach($objListProvider as $i){
					echo "<option value='".$i->entityID."'>".$i->firstName."</option>";
				}
				?>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
