<?php helper_getHtmlOfReportFilterOpen("Lista de ítem información de productos", "📦", "Inventario"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtWarehouseID">Bodega</label>
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

	<div class="prf-field">
		<label class="pv2-label" for="txtCategoryID">Categoría</label>
		<select name="txtCategoryID" id="txtCategoryID" class="select2">
				<option value="0">TODAS</option>
				<?php
				if($objListCategory)
				foreach($objListCategory as $i){
					echo "<option value='".$i->inventoryCategoryID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtFormatID">Formato</label>
		<select name="txtFormatID" id="txtFormatID" class="select2">
				<option value="0">HTML</option>
				<option value="1">CSV</option>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
