<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-books"></i></div>
			<div>
				<p class="pv2-header-title">Cuenta contable <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities($objAccount->accountNumber." ".$objAccount->name, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_account/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account" name="form-new-rol" role="form" autocomplete="off">

		<input type="hidden" name="txtCompanyID" id="companyID" value="<?php echo $objAccount->companyID;?>" />
		<input type="hidden" name="txtAccountID" id="txtAccountID" value="<?php echo $objAccount->accountID;?>" />

		<!-- datos de la cuenta -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-books"></i></span> Datos de la cuenta</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountNumber">Código</label>
					<input class="form-control" type="text" name="txtAccountNumber" id="txtAccountNumber" value="<?php echo $objAccount->accountNumber;?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountNumberParent">Código padre</label>
					<input class="form-control" type="text" name="txtAccountNumberParent" id="txtAccountNumberParent" value="<?php if ($objParentAccount) { echo  $objParentAccount->accountNumber; } ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control" type="text" name="txtName" id="txtName" value="<?php echo $objAccount->name;?>" autocomplete="off">
				</div>
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtDescription">Descripción</label>
					<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objAccount->description;?></textarea>
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
							if($objAccount->accountLevelID == $i->accountLevelID)
								echo "<option value='".$i->accountLevelID."' selected >".$i->name."</option>";
							else
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
							if($objAccount->accountTypeID == $i->accountTypeID)
								echo "<option value='".$i->accountTypeID."' selected>".$i->name."</option>";
							else
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
							if($objAccount->classID == $i->classID)
								echo "<option value='".$i->classID."' selected>".$i->description."</option>";
							else
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
							if($objAccount->currencyID == $i->currencyID)
								echo "<option value='".$i->currencyID."' selected >".$i->name."</option>";
							else
								echo "<option value='".$i->currencyID."'>".$i->name."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pinv-check" for="txtIsOperative">
						<input type="checkbox" id="txtIsOperative" name="txtIsOperative" value="1" <?php if($objAccount->isOperative){ echo "checked='checked'"; } ?> > Es operativa
					</label>
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- historico de balances (grafica flot) -->
	<div class="pv2-card">
		<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-stats"></i></span> Histórico de balances <small>(últimos 12)</small></p>
		<div class="chart-line" style="width:100%; min-height:250px; margin-top:10px;"></div>
		<div class="pv2-empty" id="pv2EmptyBalance" style="display:none">
			<i class="icon20 i-stats"></i>
			<span id="pv2EmptyBalanceText">Sin balances</span>
		</div>
	</div>
	<!-- /historico de balances -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Cuenta contable</p>
			<p class="pv2-mbar-total"><?php echo htmlentities($objAccount->accountNumber, ENT_QUOTES); ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
