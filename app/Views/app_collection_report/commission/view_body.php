<?php helper_getHtmlOfReportFilterOpen("Comisiones por gestor", "🛵", "Recolectores"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtMayorizatePeriod">Periodo</label>
		<select name="txtMayorizatePeriod" id="txtMayorizatePeriod" class="select2">
				<option></option>
				<?php
				if($objListAccountingPeriod)
				foreach($objListAccountingPeriod as $i){
					echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtMayorizateCicle">Ciclo</label>
		<select name="txtMayorizateCicle" id="txtMayorizateCicle" class="select2">
				<option></option>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
