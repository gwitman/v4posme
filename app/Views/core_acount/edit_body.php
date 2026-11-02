<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-marker-2"></i></div>
			<div>
				<p class="pv2-header-title">Mi perfil <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities($nickname, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-edit-acount" role="form" autocomplete="off">

		<input type="hidden" id="txtCompanyID" name="txtCompanyID" value="<?php echo $companyID; ?>">
		<input type="hidden" id="txtBranchID" name="txtBranchID" value="<?php echo $branchID; ?>">

		<!-- identificacion -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Identificacion</p>
			<div class="row">
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtUserID">Codigo</label>
					<input class="form-control" readonly="readonly" type="text" name="txtUserID" id="txtUserID" value="<?php echo helper_StrPadString($userID,8,"0"); ?>">
				</div>
				<div class="col-xs-12 col-sm-8 form-group">
					<label class="pv2-label" for="txtEmployeeDescription">Colaborador</label>
					<input class="form-control" type="text" readonly="readonly" name="txtEmployeeDescription" id="txtEmployeeDescription" value="<?php echo $objEmployee == null  ?  ""  : ($objEmployee->employeNumber." / ".$objEmployeeNatural->firstName." ".$objEmployeeNatural->lastName); ?>">
				</div>
			</div>
		</div>
		<!-- /identificacion -->

		<!-- acceso -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-key"></i></span> Datos de acceso</p>
			<div class="row">
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtNickname">Nickname</label>
					<input class="form-control" type="text" name="txtNickname" id="txtNickname" value="<?php echo $nickname; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtPassword">Password</label>
					<input class="form-control" type="password" name="txtPassword" id="txtPassword" value="<?php echo $password; ?>" autocomplete="new-password">
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtEmail">Email</label>
					<input class="form-control" type="text" name="txtEmail" id="txtEmail" value="<?php echo $email; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<!-- /acceso -->

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Mi perfil</p>
			<p class="pv2-mbar-total"><?php echo htmlentities($nickname, ENT_QUOTES); ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
