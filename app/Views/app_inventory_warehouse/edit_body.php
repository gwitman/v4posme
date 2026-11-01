<div class="pv2 pinv pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-office"></i></div>
			<div>
				<p class="pv2-header-title">Bodega <span class="invoice-num"><?php echo $objWarehouse->number; ?></span> <span class="pv2-chip">Edición</span></p>
				<p class="pv2-header-sub">Revisa o modifica los datos de la bodega</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_inventory_warehouse/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">

		<!-- datos generales -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-cube"></i></span> Datos de la bodega</p>
			<div class="row">
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtNumber">Codigo</label>
					<input class="form-control"  type="text" name="txtNumber" id="txtNumber" maxlength="5" value="<?php echo $objWarehouse->number; ?>">
					<input type="hidden" name="txtCompanyID" value="<?php echo $objWarehouse->companyID; ?>">
					<input type="hidden" name="txtWarehouseID" value="<?php echo $objWarehouse->warehouseID; ?>">
					<input type="hidden" name="txtBranchID" value="<?php echo $objWarehouse->branchID; ?>">
					<p class="pv2-help">Exactamente 5 caracteres.</p>
				</div>
				<div class="col-xs-12 col-sm-8 form-group">
					<label class="pv2-label" for="txtName">Nombre</label>
					<input class="form-control"  type="text" name="txtName" id="txtName" value="<?php echo $objWarehouse->name; ?>">
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtBranchIDDescription">Sucursal</label>
					<select name="txtBranchIDDescription" id="txtBranchIDDescription"  class="select2">
							<option></option>
							<?php
							if($objListBranch)
							foreach($objListBranch as $i){
								if($i->branchID == $objWarehouse->branchID)
								echo "<option selected='selected' value='".$i->branchID."'>".$i->name."</option>";
								else
								echo "<option value='".$i->branchID."'>".$i->name."</option>";
							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtStatusID">Estado</label>
					<select name="txtStatusID" id="txtStatusID" class="select2">
							<option></option>
							<?php
							if($objListWorkflowStage)
							foreach($objListWorkflowStage as $i){
								if($i->workflowStageID == $objWarehouse->statusID)
								echo "<option selected='selected' value='".$i->workflowStageID."'>".$i->name."</option>";
								else
								echo "<option  value='".$i->workflowStageID."'>".$i->name."</option>";
							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtTypeWarehouse">Tipo</label>
					<select name="txtTypeWarehouse" id="txtTypeWarehouse" class="select2">
							<option></option>
							<?php
							if($objListTypeWarehouse)
							foreach($objListTypeWarehouse as $i){
								if($i->catalogItemID == $objWarehouse->typeWarehouse)
								echo "<option selected='selected' value='".$i->catalogItemID."'>".$i->name."</option>";
								else
								echo "<option  value='".$i->catalogItemID."'>".$i->name."</option>";

							}
							?>
					</select>
				</div>
			</div>
		</div>
		<!-- /datos generales -->

		<!-- contacto -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-location"></i></span> Ubicacion y responsable</p>
			<div class="row">
				<div class="col-xs-12 col-sm-8 form-group">
					<label class="pv2-label" for="txtAddress">Direccion</label>
					<textarea class="form-control" id="txtAddress" name="txtAddress" rows="3"><?php echo $objWarehouse->address; ?></textarea>
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtEmailResponsability">Email del responsable</label>
					<input class="form-control"  type="text" name="txtEmailResponsability" id="txtEmailResponsability" value="<?php echo $objWarehouse->emailResponsability; ?>">
				</div>
			</div>
		</div>
		<!-- /contacto -->

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Bodega <?php echo $objWarehouse->number; ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
