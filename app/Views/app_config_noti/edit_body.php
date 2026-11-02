<div class="pv2 pinv pinv-form pv2-has-mbar">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-certificate"></i></div>
			<div>
				<p class="pv2-header-title">Notificacion #<?php echo $objRemember->rememberID; ?> <span class="pv2-chip">Editando</span></p>
				<p class="pv2-header-sub"><?php echo htmlentities($objRemember->title, ENT_QUOTES); ?></p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_config_noti/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-warning" id="btnUploadFile"><i class="icon16 i-card"></i> Subir archivo</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<!-- formulario -->
	<form id="form-new-account-level" name="form-new-rol" role="form" autocomplete="off">

		<input type="hidden" name="txtRememberID" id="txtRememberID" value="<?php echo $objRemember->rememberID;?>" />

		<!-- datos generales -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-bell"></i></span> Datos de la notificacion</p>
			<div class="row">
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtTitulo">Titulo</label>
					<input class="form-control" type="text" name="txtTitulo" id="txtTitulo" value="<?php echo $objRemember->title;?>" autocomplete="off">
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pv2-label" for="txtTagID">Tag</label>
					<select name="txtTagID" id="txtTagID" class="select2">
							<option value="0">Seleccionar</option>
							<?php
							if($objListTag)
							foreach($objListTag as $ws){
								if($ws->tagID == $objRemember->tagID)
								echo "<option value='".$ws->tagID."' selected >".$ws->name."</option>";
								else
								echo "<option value='".$ws->tagID."' >".$ws->name."</option>";
							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtPeriodID">Periodo</label>
					<select name="txtPeriodID" id="txtPeriodID" class="select2">
							<option></option>
							<?php
							if($objListPeriod)
							foreach($objListPeriod as $i){
								if($i->catalogItemID == $objRemember->period )
								echo "<option value='".$i->catalogItemID."' selected>".$i->name."</option>";
								else
								echo "<option value='".$i->catalogItemID."'>".$i->name."</option>";
							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtDias">Dias</label>
					<input class="form-control" type="text" name="txtDias" id="txtDias" value="<?php echo $objRemember->day;?>" autocomplete="off" inputmode="numeric">
				</div>
				<div class="col-xs-12 col-sm-4 form-group">
					<label class="pv2-label" for="txtStatusID">Estado</label>
					<select name="txtStatusID" id="txtStatusID" class="select2">
							<option></option>
							<?php
							if($objListWorkflowStage)
							foreach($objListWorkflowStage as $ws){
								if ($ws->workflowStageID == $objRemember->statusID)
								echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
								else
								echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
							}
							?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pinv-check" for="txtIsTemporal">
						<input type="checkbox" id="txtIsTemporal" name="txtIsTemporal" value="1" <?php if($objRemember->isTemporal){ echo "checked='checked'"; } ?> > Temporal
					</label>
				</div>
				<div class="col-xs-12 col-sm-6 form-group">
					<label class="pinv-check" for="txtLeerFile">
						<input type="checkbox" id="txtLeerFile" name="txtLeerFile" value="1" <?php if($objRemember->leerFile){ echo "checked='checked'"; } ?> > Desde archivo
					</label>
				</div>
			</div>
		</div>
		<!-- /datos generales -->

		<!-- descripcion -->
		<div class="pv2-card">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file-4"></i></span> Descripcion</p>
			<div class="row">
				<div class="col-xs-12 form-group">
					<label class="pv2-label" for="txtDescripcion">Mensaje</label>
					<textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="6"><?php echo $objRemember->description;?></textarea>
				</div>
			</div>
		</div>
		<!-- /descripcion -->

	</form>
	<!-- /formulario -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Notificacion</p>
			<p class="pv2-mbar-total">#<?php echo $objRemember->rememberID; ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
