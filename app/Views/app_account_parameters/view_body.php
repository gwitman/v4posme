<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-tools"></i></div>
			<div>
				<p class="pv2-header-title">Parámetros contables</p>
				<p class="pv2-header-sub">Cuentas, monedas y razones financieras de la empresa</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-level" name="form-new-rol" role="form" autocomplete="off" onsubmit="return false;">

		<!-- cuentas principales -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tree-5"></i></span> Cuentas principales</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountActivo">Cuenta de Activo</label>
					<input class="form-control" type="text" name="txtAccountActivo" id="txtAccountActivo" value="<?php echo $accountActivo; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountPasivo">Cuenta de Pasivo</label>
					<input class="form-control" type="text" name="txtAccountPasivo" id="txtAccountPasivo" value="<?php echo $accountPasivo; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountCapital">Cuenta de Capital</label>
					<input class="form-control" type="text" name="txtAccountCapital" id="txtAccountCapital" value="<?php echo $accountCapital; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountIngreso">Cuenta de Ingresos</label>
					<input class="form-control" type="text" name="txtAccountIngreso" id="txtAccountIngreso" value="<?php echo $accountIngreso; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountCostos">Cuenta de Costos</label>
					<input class="form-control" type="text" name="txtAccountCostos" id="txtAccountCostos" value="<?php echo $accountCostos; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountGastos">Cuenta de Gastos</label>
					<input class="form-control" type="text" name="txtAccountGastos" id="txtAccountGastos" value="<?php echo $accountGastos; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtAccountResult">Cuentas de Resultado</label>
					<input class="form-control" type="text" name="txtAccountResult" id="txtAccountResult" value="<?php echo $accountResult; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<!-- /cuentas principales -->

		<!-- utilidades -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-stats-up"></i></span> Utilidades</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtUtility">Cuenta de Utilidad del periodo</label>
					<input class="form-control" type="text" name="txtUtility" id="txtUtility" value="<?php echo $accountUtilityPeriod; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtUtilityAcumulate">Cuenta de Utilidades acumuladas</label>
					<input class="form-control" type="text" name="txtUtilityAcumulate" id="txtUtilityAcumulate" value="<?php echo $accountUtilityAcumulate; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtFormulateUtility">Fórmula de utilidades</label>
					<input class="form-control" type="text" name="txtFormulateUtility" id="txtFormulateUtility" value="<?php echo $formulateUtility; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<!-- /utilidades -->

		<!-- moneda y tipo de cambio -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coin"></i></span> Moneda y tipo de cambio</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCurrency">Moneda de la empresa</label>
					<input class="form-control" type="text" name="txtCurrency" id="txtCurrency" value="<?php echo $currencyDefault; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCurrencyReport">Moneda de reporte</label>
					<input class="form-control" type="text" name="txtCurrencyReport" id="txtCurrencyReport" value="<?php echo $currencyReport; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtExchangePurchase">Dólares compra</label>
					<input class="form-control" type="text" name="txtExchangePurchase" id="txtExchangePurchase" value="<?php echo $exchangePurchase; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtExchangeSales">Dólares venta</label>
					<input class="form-control" type="text" name="txtExchangeSales" id="txtExchangeSales" value="<?php echo $exchangeSales; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<!-- /moneda y tipo de cambio -->

		<!-- razones financieras -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pie-5"></i></span> Razones financieras</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtRazon001">Razón del circulante</label>
					<input class="form-control" type="text" name="txtRazon001" id="txtRazon001" value="<?php echo $objRazon001; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtRazon002">Endeudamiento</label>
					<input class="form-control" type="text" name="txtRazon002" id="txtRazon002" value="<?php echo $objRazon002; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtRazon003">Utilidad mensual</label>
					<input class="form-control" type="text" name="txtRazon003" id="txtRazon003" value="<?php echo $objRazon003; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtRazon004">Utilidad anual</label>
					<input class="form-control" type="text" name="txtRazon004" id="txtRazon004" value="<?php echo $objRazon004; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtRazon005">Rentabilidad anual</label>
					<input class="form-control" type="text" name="txtRazon005" id="txtRazon005" value="<?php echo $objRazon005; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtRazon006">Rentabilidad mensual</label>
					<input class="form-control" type="text" name="txtRazon006" id="txtRazon006" value="<?php echo $objRazon006; ?>" autocomplete="off">
				</div>
			</div>
		</div>
		<!-- /razones financieras -->

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Contabilidad</p>
			<p class="pv2-mbar-total">Parámetros</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
