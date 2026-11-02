<?php helper_getHtmlOfReportFilterOpen("Auxiliar de cuenta", "📒", "Contabilidad"); ?>

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
		<label class="pv2-label" for="txtMayorizateCicleStart">Ciclo inicial</label>
		<select name="txtMayorizateCicleStart" id="txtMayorizateCicleStart" class="select2">
				<option></option>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtMayorizateCicleEnd">Ciclo final</label>
		<select name="txtMayorizateCicleEnd" id="txtMayorizateCicleEnd" class="select2">
				<option></option>
		</select>
	</div>

	<div class="prf-field is-wide">
		<label class="pv2-label" for="txtAccount">Cuenta</label>
		<select name="txtAccount" id="txtAccount" class="select2">
				<option></option>
				<?php
				if($objListAccount)
				foreach($objListAccount as $i){
					echo "<option value='".$i->accountID."'>".$i->accountNumber." --- ".$i->name."</option>";
				}
				?>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
