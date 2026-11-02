<?php helper_getHtmlOfReportFilterOpen("Detalle de gastos", "🚚", "Proveedores"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtTiposID">Tipos</label>
		<select name="txtTiposID" id="txtTiposID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListCatalogoTipoGastos)
				foreach($objListCatalogoTipoGastos as $i){
					echo "<option value='".$i->publicCatalogDetailID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtClassID">Clasificación</label>
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
		<label class="pv2-label" for="txtCategoriaID">Categoría</label>
		<select name="txtCategoriaID" id="txtCategoriaID" class="select2">
				<option value="0">TODAS</option>
				<?php
				if($objListCatalogoCategoriaGastos)
				foreach($objListCatalogoCategoriaGastos as $i){
					echo "<option value='".$i->publicCatalogDetailID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
