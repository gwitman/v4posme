<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-screwdriver-2"></i></div>
			<div>
				<p class="pv2-header-title">Nueva naturaleza de cuenta <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Indica si la cuenta es de naturaleza deudora (D) o acreedora (C)</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_type/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">

		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-screwdriver-2"></i></span> Datos de la naturaleza</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control" type="text" name="txtName" id="txtName" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtNaturaleza">Naturaleza (C|D)</label>
					<input class="form-control" type="text" name="txtNaturaleza" id="txtNaturaleza" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtDescription">Descripción</label>
					<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"></textarea>
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Nueva naturaleza de cuenta</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
