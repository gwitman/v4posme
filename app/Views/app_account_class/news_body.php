<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-direction"></i></div>
			<div>
				<p class="pv2-header-title">Nuevo centro de costo <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Registra un centro de costo y su ubicación en la jerarquía</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_class/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account" name="form-new-rol" role="form" autocomplete="off">

		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-direction"></i></span> Datos del centro de costo</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtNumber">Código</label>
					<input class="form-control" type="text" name="txtNumber" id="txtNumber" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtParentClassNumber">Código padre (centro de costo superior)</label>
					<input class="form-control" type="text" name="txtParentClassNumber" id="txtParentClassNumber" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountLevelID">Nivel</label>
					<select name="txtAccountLevelID" id="txtAccountLevelID" class="select2">
						<option></option>
						<?php
						if($objListAccountLevel)
						foreach($objListAccountLevel as $i){
							echo "<option value='".$i->accountLevelID."'>".$i->name."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtDescription">Descripción</label>
					<input class="form-control" type="text" name="txtDescription" id="txtDescription" value="" autocomplete="off">
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Nuevo centro de costo</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
