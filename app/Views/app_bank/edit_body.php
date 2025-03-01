					<div class="row">
						<div id="email" class="col-lg-12">

							<!-- botonera -->
							<div class="email-bar" style="border-left:1px solid #c9c9c9">
								<div class="btn-group pull-right">
									<a href="<?php echo base_url(); ?>/app_bank/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
									<h4>Formulario de Datos</h4>
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
													<input type="hidden" name="txtCompanyID" value="<?php echo $objBank->companyID; ?>">
													<input type="hidden" name="txtBankID" value="<?php echo $objBank->bankID; ?>">

													<div class="col-md-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Nombre</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtName" id="txtName" value="<?php echo $objBank->name; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-8">
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																	<?php
																	if ($objListCurrency)
																		foreach ($objListCurrency as $ws) {
																			if ($ws->currencyID == $objBank->currencyID)
																				echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
																			else
																				echo "<option value='" . $ws->currencyID . "' >" . $ws->simb . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Balance</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtBalance" id="txtBalance" value="<?php echo $objBank->balance; ?>" readonly>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Administrador</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtCustomerEntityID" name="txtCustomerEntityID" value="<?php echo $objBank->managerID?>">
																	<?php
																		if ($objCustomer) {
																			?>
																			<input class="form-control" id="txtCustomer" type="txtCustomer" value="<?php echo $objCustomer->employeNumber . " | " . $objCustomer->firstName . " " . $objCustomer->lastName ?>">
																			<?php
																		}else {
																			?>
																			<input class="form-control" id="txtCustomer" type="txtCustomer" value="">
																			<?php
																		}
																	?>

																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearCustomer">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchCustomer">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Numero de tarjeta asociada</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtTarjeta" id="txtTarjeta" value="<?php echo $objBank->cardNumber; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Fecha de expiracion</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo $objBank->dateExpired; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Estado</label>
															<div class="col-lg-8">
																<select name="txtStatusID" id="txtStatusID" class="select2">
																	<option></option>
																	<?php
																	if ($objListWorkflowStage)
																		foreach ($objListWorkflowStage as $ws) {
																			if ($ws->workflowStageID == $objBank->statusID)
																				echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";

																			else
																			echo "<option  value='" . $ws->workflowStageID . "'>" . $ws->name . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia 1</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtReference1" id="txtReference1" value="<?php echo $objBank->reference1; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia 2</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtReference2" id="txtReference2" value="<?php echo $objBank->reference2; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Comentario</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtComment" id="txtComment" value="<?php echo $objBank->note; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">URL de banco</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtURL" id="txtURL" value="<?php echo $objBank->urlBank; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Usuario</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtUsuario" id="txtUsuario" value="<?php echo $objBank->user; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Contraseña</label>
															<div class="col-lg-8">
																<input class="form-control" type="password" name="txtPassword" id="txtPassword" value="<?php echo $objBank->password; ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Pin</label>
															<div class="col-lg-8">
																<input class="form-control" type="password" name="txtPin" id="txtPin" value="<?php echo $objBank->pin; ?>">
															</div>
														</div>
													</div>
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