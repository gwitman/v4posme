<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
			<div>
				<p class="pv2-header-title">CAJA: #<span class="box-num">0000</span> <span class="pv2-chip">Nueva</span></p>
				<p class="pv2-header-sub">Registra una nueva caja</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_box_admbox/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<form id="form-new-box" name="form-new-box" role="form" autocomplete="off">
		<div class="pv2-card" id="home">
			<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Información</p>
							<div class="row">
								<div class="col-lg-12">
								    <div class="form-group">
										<label class="pv2-label" for="txtNameBox">Nombre de Caja</label>
										<div>
											<input class="form-control" autocomplete="off" type="text" name="txtNameBox" id="txtNameBox" value="">
										</div>
									</div>
									<div class="form-group">
										<label class="pv2-label" for="txtStatusID">Estado</label>
										<div>
											<select name="txtStatusID" id="txtStatusID" class="select2">
													<option></option>
													<?php
                                                        if ($objListWorkflowStage) {
                                                            foreach ($objListWorkflowStage as $ws) {
                                                                echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                            }
                                                        }

                                                    ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="pv2-label" for="txtDescriptionBox">Descripción de Caja</label>
										<div>
											<input class="form-control" autocomplete="off" type="text" name="txtDescriptionBox" id="txtDescriptionBox" value="">
										</div>
									</div>
								</div>
							</div>
		</div>
	</form>

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Nueva caja</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>