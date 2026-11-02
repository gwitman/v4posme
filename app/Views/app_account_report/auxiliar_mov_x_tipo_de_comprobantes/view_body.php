<?php helper_getHtmlOfReportFilterOpen("Auxiliar de movimientos por tipo de comprobantes", "📒", "Contabilidad"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtClassID">Centro de costo</label>
		<select name="txtClassID" id="txtClassID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListCentroCosto)
				foreach($objListCentroCosto as $i){
					echo "<option value='".$i->classID."'>".$i->description."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtJournalTypeID">Tipo de comprobantes</label>
		<select name="txtJournalTypeID" id="txtJournalTypeID" class="select2">
				<option value='-1'>TODOS</option>
				<?php
				if($objListJournalType)
				foreach($objListJournalType as $i){
					echo "<option value='".$i->catalogItemID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="stringContainer">Contiene</label>
		<input class="form-control" type="text" name="stringContainer" id="stringContainer" value="" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="" autocomplete="off">
	</div>

	<div class="prf-checks">
		<label class="prf-check"><input type="checkbox" name="excludeSystem" id="excludeSystem" value="1"> <span class="prf-check-text">Excluir comprobantes automáticos</span></label>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
