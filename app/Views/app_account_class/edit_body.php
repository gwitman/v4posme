<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-direction"></i></div>
			<div>
				<p class="pv2-header-title">Centro de costo <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities($objClass->number." ".$objClass->description, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_class/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account" name="form-new-rol" role="form" autocomplete="off">

		<input type="hidden" name="txtCompanyID" id="companyID" value="<?php echo $objClass->companyID;?>" />
		<input type="hidden" name="txtClassID" id="txtClassID" value="<?php echo $objClass->classID;?>" />

		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-direction"></i></span> Datos del centro de costo</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtNumber">Código</label>
					<input class="form-control" type="text" name="txtNumber" id="txtNumber" value="<?php echo $objClass->number;?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtParentClassNumber">Código padre (centro de costo superior)</label>
					<?php /* objParentClass solo existe si el centro de costo tiene padre */ ?>
					<input class="form-control" type="text" name="txtParentClassNumber" id="txtParentClassNumber" value="<?php if ($objClass->parentClassID != null) { echo  $objParentClass->number; } ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountLevelID">Nivel</label>
					<select name="txtAccountLevelID" id="txtAccountLevelID" class="select2">
						<option></option>
						<?php
						if($objListAccountLevel)
						foreach($objListAccountLevel as $i){

							if($i->accountLevelID == $objClass->accountLevelID)
								echo "<option value='".$i->accountLevelID."' selected >".$i->name."</option>";
							else
								echo "<option value='".$i->accountLevelID."'>".$i->name."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtDescription">Descripción</label>
					<input class="form-control" type="text" name="txtDescription" id="txtDescription" value="<?php echo $objClass->description;?>" autocomplete="off">
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Centro de costo</p>
			<p class="pv2-mbar-total"><?php echo htmlentities($objClass->number, ENT_QUOTES); ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
