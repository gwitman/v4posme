<div class="row">
	<div id="email" class="col-lg-12">

		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">
			<div class="btn-group pull-right">
				<a href="<?php echo base_url(); ?>/app_box_admbox/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>
				<a href="<?php echo base_url(); ?>/app_box_admbox/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
				<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
			</div>
		</div>
		<!-- /botonera -->
	</div>
	<!-- End #email  -->
</div>
<!-- Fin del head -->

<!-- Inicio del Body -->
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">

			<!-- titulo de comprobante-->
			<div class="panel-heading">
				<div class="icon"><i class="icon20 i-file"></i></div>
				<h4>CAJA: #<span class="box-num"><?php echo $objCashBox->cashBoxCode ?></span></h4>
			</div>

			<form id="form-edit-box" name="form-edit-box" class="form-horizontal" role="form">
				<input type="hidden" name="txtBoxID" value="<?php echo $objCashBox->cashBoxID ?>">
				<input type="hidden" name="txtCompanyID" value="<?php echo $objCashBox->companyID ?>">
				<div class="panel-body printArea">

					<ul id="myTab" class="nav nav-tabs">
						<li class="active">
							<a href="#home" data-toggle="tab">Informaci&oacute;n</a>
						</li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane fade in active" id="home">
							<div class="row">
								<div class="col-lg-12">
								    <div class="form-group">
										<label class="col-lg-4 control-label" for="txtNameBox">Nombre de Caja</label>
										<div class="col-lg-8">
											<input class="form-control" type="text" name="txtNameBox" id="txtNameBox" value="<?php echo $objCashBox->name ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label" for="txtStatusID">Estado</label>
										<div class="col-lg-8">
											<select name="txtStatusID" id="txtStatusID" class="select2">
													<option></option>
													<?php
                                                        if ($objListWorkflowStage) {
                                                            foreach ($objListWorkflowStage as $ws) {
                                                                if ($ws->workflowStageID == $objCashBox->statusID) {
                                                                    echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                                } else {
                                                                    echo "<option value='" . $ws->workflowStageID . "'>" . $ws->name . "</option>";
                                                                }

                                                            }
                                                        }

                                                    ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-4 control-label" for="txtDescriptionBox">Descripción de Caja</label>
										<div class="col-lg-8">
											<input class="form-control" type="text" name="txtDescriptionBox" id="txtDescriptionBox" value="<?php echo $objCashBox->description ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>