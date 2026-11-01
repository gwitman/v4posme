<div class="pv2 pinv pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-dice"></i></div>
			<div>
				<p class="pv2-header-title">Nueva categoría <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Agrupa tus productos por categoría</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_category/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-cube"></i></span> Datos de la categoría</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control"  type="text" name="txtName" id="txtName" value="">
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
			<p class="pv2-mbar-label">Nueva categoría</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
