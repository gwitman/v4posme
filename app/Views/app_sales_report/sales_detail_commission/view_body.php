<?php helper_getHtmlOfReportFilterOpen("Detalle de comisiones", "📈", "Ventas"); ?>

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
		<label class="pv2-label" for="txtUserID">Usuario</label>
		<select name="txtUserID" id="txtUserID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListaUsuarios)
				foreach($objListaUsuarios as $i){
					echo "<option value='".$i->userID."'>".$i->nickname."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEmployerID">Colaborador</label>
		<select name="txtEmployerID" id="txtEmployerID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListEmployer)
				foreach($objListEmployer as $i){
					echo "<option value='".$i->entityID."'>".$i->firstName." ".$i->firstName."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtTexto">Filtro</label>
		<input class="form-control" type="text" name="txtTexto" id="txtTexto" value="" autocomplete="off">
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
