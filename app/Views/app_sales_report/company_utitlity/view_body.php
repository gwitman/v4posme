<?php helper_getHtmlOfReportFilterOpen("Reporte de utilidad", "📈", "Ventas"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtClassID">Clasificación de gasto</label>
		<select name="txtClassID" id="txtClassID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListCatalogItemClasificacion)
					foreach($objListCatalogItemClasificacion as $ws){
						echo "<option value='".$ws->catalogItemID."' >".$ws->display."</option>";
					}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtBranchID">Sucursales</label>
		<select name="txtBranchID" id="txtBranchID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListBranch)
				foreach($objListBranch as $i){
					echo "<option value='".$i->branchID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtWarehouseID">Bodegas</label>
		<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
				<option value="0">TODAS</option>
				<?php
				if($objListWarehouse)
				foreach($objListWarehouse as $i){
					echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
