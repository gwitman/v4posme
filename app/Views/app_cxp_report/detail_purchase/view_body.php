<?php helper_getHtmlOfReportFilterOpen("Detalle de compra", "🚚", "Proveedores"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtInventoryCategoryID">Categorías</label>
		<select name="txtInventoryCategoryID" id="txtInventoryCategoryID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListCategoryItem)
				foreach($objListCategoryItem as $i){
					echo "<option value='".$i->inventoryCategoryID."'>".$i->name."</option>";
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

	<div class="prf-field is-wide">
		<label class="pv2-label" for="txtItemName">Producto</label>
		<input type="hidden" id="txtItemID" name="txtItemID" value="0">
		<div class="prf-input-group">
			<input class="form-control" readonly id="txtItemName" type="text" value="TODOS" autocomplete="off">
			<button class="btn btn-primary" type="button" id="btnSearchItem" title="Buscar producto">
				<i aria-hidden="true" class="i-search-5"></i>
				Buscar
			</button>
			<button class="btn btn-danger" type="button" id="btnClearItem" title="Limpiar producto">
				<i aria-hidden="true" class="i-undo-2"></i>
				Limpiar
			</button>
		</div>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
