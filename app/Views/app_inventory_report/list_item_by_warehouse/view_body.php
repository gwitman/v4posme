<?php helper_getHtmlOfReportFilterOpen("Listado por bodega", "📦", "Inventario"); ?>

	<div class="prf-field is-wide">
		<label class="pv2-label" for="warehouseID">Bodega</label>
		<select name="warehouseID" id="warehouseID" class="select2" multiple >
				<option value="0">TODAS</option>
				<?php
				if($objListaWarehouse)
				foreach($objListaWarehouse as $i){
					echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
