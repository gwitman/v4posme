<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-dice"></i></div>
			<div>
				<p class="pv2-header-title">Indicador #<span class="invoice-num"><?php echo $objIndicator->indicadorID; ?></span> <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities((string)$objIndicator->name, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_accounting_indicators/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">

		<input type="hidden" name="txtIndicatorID" id="txtIndicatorID" value="<?php echo $objIndicator->indicadorID; ?>">

		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-dice"></i></span> Indicador</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCode">Código</label>
					<input class="form-control" type="text" name="txtCode" id="txtCode" value="<?php echo $objIndicator->code; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control" type="text" name="txtName" id="txtName" value="<?php echo $objIndicator->name; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtLabel">Etiqueta</label>
					<input class="form-control" type="text" name="txtLabel" id="txtLabel" value="<?php echo $objIndicator->label; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtOrder">Orden</label>
					<input class="form-control" type="number" inputmode="numeric" name="txtOrder" id="txtOrder" value="<?php echo $objIndicator->order; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtPrefix">Prefijo</label>
					<input class="form-control" type="text" name="txtPrefix" id="txtPrefix" value="<?php echo $objIndicator->prefix; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtPosfix">Posfijo</label>
					<input class="form-control" type="text" name="txtPosfix" id="txtPosfix" value="<?php echo $objIndicator->posfix; ?>" autocomplete="off">
				</div>
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtDescription">Descripción</label>
					<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objIndicator->description; ?></textarea>
				</div>
			</div>
		</div>

		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-code"></i></span> Fórmula</p>
			<div class="row">
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtScript">Script</label>
					<?php /* Se escapa para que el navegador muestre y envie el texto original de la formula */ ?>
					<textarea class="form-control" name="txtScript" id="txtScript" rows="6" spellcheck="false" autocomplete="off" style="font-family:Consolas, 'Courier New', monospace; font-size:13px; white-space:pre;"><?php echo htmlspecialchars((string)$objIndicator->script, ENT_QUOTES); ?></textarea>
				</div>
			</div>
		</div>

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Indicador</p>
			<p class="pv2-mbar-total"><?php echo htmlentities((string)$objIndicator->code, ENT_QUOTES); ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
