<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-office"></i></div>
			<div>
				<p class="pv2-header-title">Sucursal <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities($objBranch->name, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_branch/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">

		<input type="hidden" name="txtCompanyID" value="<?php echo $objBranch->companyID; ?>">
		<input type="hidden" name="txtBranchID" value="<?php echo $objBranch->branchID; ?>">

		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-office"></i></span> Datos de la sucursal</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control" type="text" name="txtName" id="txtName" value="<?php echo $objBranch->name; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAddress">Dirección</label>
					<input class="form-control" type="text" name="txtAddress" id="txtAddress" value="<?php echo $objBranch->address; ?>" autocomplete="off">
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Sucursal</p>
			<p class="pv2-mbar-total"><?php echo htmlentities($objBranch->name, ENT_QUOTES); ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
