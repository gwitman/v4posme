<?php helper_getHtmlOfReportFilterOpen("Detalle de ventas", "📈", "Ventas"); ?>

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
		<label class="pv2-label" for="txtUserID">Usuarios</label>
		<select name="txtUserID" id="txtUserID" class="select2">
				<?php
				if($objListaUsuarios["showTodos"] == true)
				{
					echo '<option value="0">TODOS</option>';
				}

				if($objListaUsuarios["user"])
				foreach($objListaUsuarios["user"] as $i){
					echo "<option value='".$i->userID."'>".$i->nickname."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtTransactionCausalID">Causa</label>
		<select name="txtTransactionCausalID" id="txtTransactionCausalID" class="select2">
				<option value="todas">TODAS</option>
				<option value="credito">Credito</option>
				<option value="contado">Contado</option>
		</select>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
