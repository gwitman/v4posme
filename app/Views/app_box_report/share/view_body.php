<?php helper_getHtmlOfReportFilterOpen("Reporte de caja", "💵", "Caja"); ?>

	<div class="prf-field">
		<label class="pv2-label" for="txtStartOn">Fecha inicial</label>
		<input class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtEndOn">Fecha final</label>
		<input class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtHourStartOn">Hora inicial</label>
		<input class="form-control" type="text" inputmode="numeric" maxlength="2" name="txtHourStartOn" id="txtHourStartOn" value="0" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtHourEndOn">Hora final</label>
		<input class="form-control" type="text" inputmode="numeric" maxlength="2" name="txtHourEndOn" id="txtHourEndOn" value="23" autocomplete="off">
	</div>

	<div class="prf-field">
		<label class="pv2-label" for="txtUserID">Usuario</label>
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

	<p class="prf-section">Conceptos de ingresos</p>

	<div class="prf-checks">
		<?php
		foreach($objTipoMovementInputCash as  $value)
		{
			?>
			<label class="prf-check"><input type="checkbox"  class="areas"   name="txtIsServices" id="txtIsServices" value="<?php echo $value->catalogItemID;   ?>"    > <span class="prf-check-text"><?php echo $value->name; ?></span></label>
			<?php
		}
		?>
	</div>

	<p class="prf-section">Conceptos de egreso</p>

	<div class="prf-checks">
		<?php
		foreach($objTipoMovementOutputCash as  $value)
		{
			?>
			<label class="prf-check"><input type="checkbox"  class="areas"  name="txtIsServices" id="txtIsServices" value="<?php echo $value->catalogItemID;   ?>"     > <span class="prf-check-text"><?php echo $value->name; ?></span></label>
			<?php
		}
		?>
	</div>

	<div class="prf-checks">
		<label class="prf-check"><input type="checkbox"  class="areas"   name="txtIsServices" id="txtIsServices" value="0"    > <span class="prf-check-text">Ningun concepto</span></label>
	</div>

	<p class="prf-section">Categoria de productos</p>

	<div class="prf-checks">
		<?php
		foreach($objListCategoryItem as  $value)
		{
			?>
			<label class="prf-check"><input type="checkbox"  class="categorias"   name="txtCategoryItem" id="txtCategoryItem" value="<?php echo $value->inventoryCategoryID;   ?>"     > <span class="prf-check-text"><?php echo $value->name; ?></span></label>
			<?php
		}
		?>
		<label class="prf-check"><input type="checkbox"  class="categorias"   name="txtCategoryItem" id="txtCategoryItem" value="0"    > <span class="prf-check-text">Ninguna categoria</span></label>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
