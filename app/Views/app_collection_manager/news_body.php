<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-office"></i></div>
			<div>
				<p class="pv2-header-title"><?php echo getBehavio($company->type,"app_collection_manager","lblTitleAdd","Agregar Cliente a un Asesor"); ?> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Asigna un cliente a un gestor de cobro y define su orden de visita</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_collection_manager/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">

		<!-- asignacion -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Asignación</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtEmployeeDescription"><?php echo getBehavio($company->type,"app_collection_manager","lblColaborador","Colaborador"); ?></label>
					<div class="input-group">
						<input type="hidden" id="txtEmployeeID" name="txtEmployeeID">
						<input class="form-control" readonly id="txtEmployeeDescription" type="text" autocomplete="off">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="btnClearEmployee" title="Limpiar"><i aria-hidden="true" class="i-undo-2"></i> Limpiar</button>
							<button class="btn btn-primary" type="button" id="btnSearchEmployee" title="Buscar"><i aria-hidden="true" class="i-search-5"></i> Buscar</button>
						</span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCustomerDescription">Cliente</label>
					<div class="input-group">
						<input type="hidden" id="txtCustomerID" name="txtCustomerID">
						<input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="btnClearCustomer" title="Limpiar"><i aria-hidden="true" class="i-undo-2"></i> Limpiar</button>
							<button class="btn btn-primary" type="button" id="btnSearchCustomer" title="Buscar"><i aria-hidden="true" class="i-search-5"></i> Buscar</button>
						</span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtOrderNo">Orden No</label>
					<input class="form-control" type="number" inputmode="numeric" min="0" name="txtOrderNo" id="txtOrderNo" value="" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtCustomerDescriptionAfter">Aplicar orden después de</label>
					<div class="input-group">
						<input type="hidden" id="txtCustomerIDAfter" name="txtCustomerIDAfter" value="0">
						<input class="form-control" readonly id="txtCustomerDescriptionAfter" name="txtCustomerDescriptionAfter" type="text" value="" autocomplete="off">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="btnClearCustomerAfter" title="Limpiar"><i aria-hidden="true" class="i-undo-2"></i> Limpiar</button>
							<button class="btn btn-primary" type="button" id="btnSearchCustomerAfter" title="Buscar"><i aria-hidden="true" class="i-search-5"></i> Buscar</button>
						</span>
					</div>
				</div>
			</div>
		</div>
		<!-- /asignacion -->

		<!-- referencias -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Referencias</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtReference1">Referencia FAC</label>
					<input class="form-control" type="text" name="txtReference1" id="txtReference1" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtReference2">Referido</label>
					<input class="form-control" type="text" name="txtReference2" id="txtReference2" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtReference3">Tasa</label>
					<input class="form-control" type="text" name="txtReference3" id="txtReference3" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtReference4">Referencia 4</label>
					<input class="form-control" type="text" name="txtReference4" id="txtReference4" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtReference5">Referencia 5</label>
					<input class="form-control" type="text" name="txtReference5" id="txtReference5" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtDescripcion">Descripción</label>
					<input class="form-control" type="text" name="txtDescripcion" id="txtDescripcion" autocomplete="off">
				</div>
			</div>
		</div>
		<!-- /referencias -->

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Nuevo gestor</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
