<?php helper_getHtmlOfReportFilterOpen("Movimientos por bodega", "📦", "Inventario"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtWarehouseID">Bodega</label>
		<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
				<option></option>
				<?php
				if($objListWarehouse)
				foreach($objListWarehouse as $i){
					echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field is-wide">
		<label class="pv2-label" for="txtItemID">Producto</label>
		<select name="txtItemID" id="txtItemID" class="select2">
				<option></option>
				<?php
				if($objListItem)
				foreach($objListItem as $i){
					echo "<option value='".$i->itemID."'>".$i->itemNumber." ".$i->name." ".$i->barCode."</option>";
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

<?php helper_getHtmlOfReportFilterClose(); ?>
