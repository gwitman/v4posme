<?php helper_getHtmlOfReportFilterOpen("Arqueo diario", "💵", "Caja"); ?>

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

	<div class="prf-field <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownUserDiv","") ?>">
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
		<label class="pv2-label" for="txtBranchID">Sucursales</label>
		<select name="txtBranchID" id="txtBranchID" class="select2">
				<option value="0">TODOS</option>
				<?php
				if($objListBranch)
				foreach($objListBranch as $i){
					echo "<option value='".$i->branchID."'>".$i->name."</option>";
				}
				?>
		</select>
	</div>

	<div class="prf-field <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownShowAmountDiv","") ?>">
		<label class="pv2-label" for="txtShowSumaryAmount">Mostrar montos únicamente</label>
		<select name="txtShowSumaryAmount" id="txtShowSumaryAmount" class="select2">
				<option value="0">No</option>
				<option value="1">Si</option>
		</select>
	</div>

	<p class="prf-section <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptIngresosDiv","") ?>">Conceptos de ingresos</p>

	<div class="prf-checks <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptIngresosDiv","") ?>">
		<?php
		foreach($objTipoMovementInputCash as  $value)
		{
			?>
			<label class="prf-check"><input type="checkbox"  class="areas"   name="txtIsServices" id="txtIsServices" value="<?php echo $value->catalogItemID;   ?>"    > <span class="prf-check-text"><?php echo $value->name; ?></span></label>
			<?php
		}
		?>
	</div>

	<p class="prf-section <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptEgresosDiv","") ?>">Conceptos de egreso</p>

	<div class="prf-checks <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownConceptEgresosDiv","") ?>">
		<?php
		foreach($objTipoMovementOutputCash as  $value)
		{
			?>
			<label class="prf-check"><input type="checkbox"  class="areas"  name="txtIsServices" id="txtIsServices" value="<?php echo $value->catalogItemID;   ?>"     > <span class="prf-check-text"><?php echo $value->name; ?></span></label>
			<?php
		}
		?>
	</div>

	<div class="prf-checks <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownNoneConceptDiv","") ?>">
		<label class="prf-check"><input type="checkbox"  class="areas"   name="txtIsServices" id="txtIsServices" value="0"    > <span class="prf-check-text">Ningun concepto</span></label>
	</div>

	<p class="prf-section <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownCategoryItemDiv","") ?>">Categoria de productos</p>

	<div class="prf-checks <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownCategoryItemDiv","") ?>">
		<?php
		foreach($objListCategoryItem as  $value)
		{
			?>
			<label class="prf-check"><input type="checkbox"  class="categorias"   name="txtCategoryItem" id="txtCategoryItem" value="<?php echo $value->inventoryCategoryID;   ?>"     > <span class="prf-check-text"><?php echo $value->name; ?></span></label>
			<?php
		}
		?>
	</div>

	<div class="prf-checks <?php echo getBehavio($objCompany->type,"app_box_report","dailyTownCategoryItemDiv","") ?>">
		<label class="prf-check"><input type="checkbox"  class="categorias"   name="txtCategoryItem" id="txtCategoryItem" value="0"    > <span class="prf-check-text">Ninguna categoria</span></label>
	</div>

<?php helper_getHtmlOfReportFilterClose(); ?>
