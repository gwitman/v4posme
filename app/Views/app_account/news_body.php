<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-books"></i></div>
			<div>
				<p class="pv2-header-title">Nueva cuenta contable <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Agrega una cuenta al catálogo de cuentas</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_account/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account" name="form-new-rol" role="form" autocomplete="off">

		<!-- datos de la cuenta -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-books"></i></span> Datos de la cuenta</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountNumber">Código</label>
					<input class="form-control" type="text" name="txtAccountNumber" id="txtAccountNumber" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountNumberParent">Código padre</label>
					<input class="form-control" type="text" name="txtAccountNumberParent" id="txtAccountNumberParent" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control" type="text" name="txtName" id="txtName" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtDescription">Descripción</label>
					<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"></textarea>
				</div>
			</div>
		</div>

		<!-- clasificacion -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-stairs"></i></span> Clasificación</p>
			<div class="row">
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
					<label class="pv2-label" for="txtAccountTypeID">Naturaleza / Tipo</label>
					<select name="txtAccountTypeID" id="txtAccountTypeID" class="select2">
						<option></option>
						<?php
						if($objListAccountType)
						foreach($objListAccountType as $i){
							echo "<option value='".$i->accountTypeID."'>".$i->name."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtClassID">Centro de costo</label>
					<select name="txtClassID" id="txtClassID" class="select2">
						<option></option>
						<?php
						if($objListCenterCost)
						foreach($objListCenterCost as $i){
							echo "<option value='".$i->classID."'>".$i->description."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCurrencyID">Moneda</label>
					<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
						<option></option>
						<?php
						if($objListCompanyCurrency)
						foreach($objListCompanyCurrency as $i){
							echo "<option value='".$i->currencyID."'>".$i->name."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pinv-check" for="txtIsOperative">
						<input type="checkbox" id="txtIsOperative" name="txtIsOperative" value="1" > Es operativa
					</label>
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Nueva cuenta contable</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
