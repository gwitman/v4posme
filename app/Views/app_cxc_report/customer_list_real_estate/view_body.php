<?php helper_getHtmlOfReportFilterOpen("Detalle de clientes", "👥", "Clientes"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtSubCategoryID">Categoría</label>
		<select name="txtSubCategoryID" id="txtSubCategoryID" class="select2">
				<?php
					if($objListSubCategory)
					{
						foreach($objListSubCategory as $category)
						{
							if($category->name == getBehavio($company->type,"app_cxc_report","customerListRealState_DefaultSubCategory","CLIENTE"))
							{
								echo "<option selected value='".$category->catalogItemID."'>".$category->name."</option>";
							}
							else
							{
								echo "<option value='".$category->catalogItemID."'>".$category->name."</option>";
							}
						}
					}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtShowActivos">Mostrar</label>
		<select name="txtShowActivos" id="txtShowActivos" class="select2">
				<option value="0" selected >Activos</option>
				<option value="1">Eliminados</option>
		</select>
	</div>

	<!-- Bodega: oculta, el script envia su valor -->
	<div class="prf-field hidden">
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

<?php helper_getHtmlOfReportFilterClose(); ?>
