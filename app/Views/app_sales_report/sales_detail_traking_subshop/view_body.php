<?php helper_getHtmlOfReportFilterOpen("Seguimiento de sub tienda", "📈", "Ventas"); ?>

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
		<label class="pv2-label" for="keyWarehouse">Clave</label>
		<input class="form-control" type="text" name="keyWarehouse" id="keyWarehouse" autocomplete="off" />
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
