<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-coins"></i></div>
			<div>
				<p class="pv2-header-title">Moneda <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub">Símbolo: <?php echo htmlentities((string)$objCompanyCurrency->simb, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_currency/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-level" name="form-new-rol" role="form" autocomplete="off">

		<?php /* Se conservan los dos campos txtCurrencyID (hidden y select) en el mismo orden que el original */ ?>
		<input type="hidden" name="txtCompanyID" value="<?php echo $objCompanyCurrency->companyID; ?>" />
		<input type="hidden" name="txtCurrencyID" value="<?php echo $objCompanyCurrency->currencyID; ?>" />

		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Moneda</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCurrencyID">Moneda</label>
					<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
						<option></option>
						<?php
						if($objListCurrency)
						foreach($objListCurrency as $i){
							if($i->currencyID == $objCompanyCurrency->currencyID)
								echo "<option value='".$i->currencyID."' selected >".$i->name."</option>";
							else
								echo "<option value='".$i->currencyID."'>".$i->name."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtSimb">Símbolo</label>
					<input class="form-control" type="text" name="txtSimb" id="txtSimb" value="<?php echo $objCompanyCurrency->simb; ?>" autocomplete="off">
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Moneda</p>
			<p class="pv2-mbar-total"><?php echo htmlentities((string)$objCompanyCurrency->simb, ENT_QUOTES); ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
