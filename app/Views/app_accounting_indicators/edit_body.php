					<div class="row">
						<div id="email" class="col-lg-12">

							<!-- botonera -->
							<div class="email-bar" style="border-left:1px solid #c9c9c9">
								<div class="btn-group pull-right">
									<a href="<?php echo base_url(); ?>/app_accounting_indicators/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
									<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
									<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
								</div>
							</div>
							<!-- /botonera -->
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<!-- widget -->
							<div class="panel panel-default">
								<!-- panel widget -->
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-cube"></i></div>
									<h4>Formulario de Datos:#<span class="invoice-num"><?php echo $objIndicator->indicadorID; ?></span></h4>
									<a href="#" class="minimize"></a>
								</div>
								<!-- /panel widget -->
								<!-- body widget -->
								<div class="panel-body noPadding">
									<!-- body -->
									<div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
										<div class="row">
											<div class="email-content col-lg-12">
												<!-- formulario -->
												<form id="form-new-account-type" name="form-new-rol" class="form-horizontal" role="form">
													<fieldset>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Codigo</label>
															<div class="col-lg-4">
																<input type="hidden" name="txtIndicatorID" id="txtIndicatorID" value="<?php echo $objIndicator->indicadorID; ?>">

																<input class="form-control" type="text" name="txtCode" id="txtCode" value="<?php echo $objIndicator->code; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Nombre</label>
															<div class="col-lg-4">
																<input class="form-control" type="text" name="txtName" id="txtName" value="<?php echo $objIndicator->name; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Etiqueta</label>
															<div class="col-lg-4">
																<input class="form-control" type="text" name="txtLabel" id="txtLabel" value="<?php echo $objIndicator->label; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Descripcion</label>
															<div class="col-lg-10">
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"><?php echo $objIndicator->description; ?></textarea>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Orden</label>
															<div class="col-lg-4">
																<input class="form-control" type="number" name="txtOrder" id="txtOrder" value="<?php echo $objIndicator->order; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Script</label>
															<div class="col-lg-4">
																<input class="form-control" type="text" name="txtScript" id="txtScript" value="<?php echo $objIndicator->script; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Posfix</label>
															<div class="col-lg-4">
																<input class="form-control" type="text" name="txtPosfix" id="txtPosfix" value="<?php echo $objIndicator->posfix; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Prefix</label>
															<div class="col-lg-4">
																<input class="form-control" type="text" name="txtPrefix" id="txtPrefix" value="<?php echo $objIndicator->prefix; ?>">
															</div>
														</div>

													</fieldset>
												</form>
												<!-- /formulario -->
											</div>
										</div><!-- End .row-fluid  -->
									</div>
									<!-- /body -->
								</div>
							</div>

						</div>
						<!-- End #email  -->
					</div>
					<!-- End .row-fluid  -->