					<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

						<!-- encabezado y acciones -->
						<div class="pv2-card pv2-header">
							<div class="pv2-header-info">
								<div class="pv2-header-icon"><i class="icon20 i-office"></i></div>
								<div>
									<p class="pv2-header-title">Formulario de Datos</p>
									<p class="pv2-header-sub">Editar banco</p>
								</div>
							</div>
							<div class="pv2-actions">
								<a href="<?php echo base_url(); ?>/app_bank/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
								<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
								<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
							</div>
						</div>
						<!-- /encabezado y acciones -->

						<!-- formulario -->
						<form id="form-new-account-type" name="form-new-rol" role="form" autocomplete="off">
							<input type="hidden" name="txtCompanyID" value="<?php echo $objBank->companyID; ?>">
							<input type="hidden" name="txtBankID" value="<?php echo $objBank->bankID; ?>">

							<div class="pv2-card">
								<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Información</p>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="pv2-label" for="txtName">Nombre</label>
											<input class="form-control" autocomplete="off" type="text" name="txtName" id="txtName" value="<?php echo $objBank->name; ?>">
										</div>

										<div class="form-group">
											<label class="pv2-label" for="txtCurrencyID">Moneda</label>
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

										<div class="form-group">
											<label class="pv2-label" for="txtBalance">Balance</label>
											<input class="form-control pv2-num" autocomplete="off" type="text" name="txtBalance" id="txtBalance" value="<?php echo number_format((float)$objBank->balance, 2); ?>" readonly>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="form-group">
											<label class="pv2-label" for="txtCustomerDescription">Administrador</label>
											<div class="input-group">
												<input type="hidden" id="txtCustomerEntityID" name="txtCustomerEntityID" value="<?php echo $objBank->managerID?>">
												<?php
													if ($objCustomer) {
														?>
														<input class="form-control" autocomplete="off" id="txtCustomerDescription" type="text" value="<?php echo $objCustomer->employeNumber . " | " . $objCustomer->firstName . " " . $objCustomer->lastName ?>">
														<?php
													}else {
														?>
														<input class="form-control" autocomplete="off" id="txtCustomerDescription" type="text" value="">
														<?php
													}
												?>

												<span class="input-group-btn">
													<button class="btn btn-danger" type="button" id="btnClearCustomer">
														<i aria-hidden="true" class="i-undo-2"></i>
														Limpiar
													</button>
												</span>
												<span class="input-group-btn">
													<button class="btn btn-primary" type="button" id="btnSearchCustomer">
														<i aria-hidden="true" class="i-search-5"></i>
														Buscar
													</button>
												</span>
											</div>
										</div>

										<div class="form-group">
											<label class="pv2-label" for="txtStatusID">Estado</label>
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
							</div>

							<div class="pv2-card">
								<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Tarjeta y comisiones</p>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="pv2-label" for="txtTarjeta">Numero de tarjeta asociada</label>
											<input class="form-control" autocomplete="off" type="text" name="txtTarjeta" id="txtTarjeta" value="<?php echo $objBank->cardNumber; ?>">
										</div>

										<div class="form-group">
											<label class="pv2-label" for="txtDate">Fecha de expiracion</label>
											<input class="form-control" autocomplete="off" type="text" name="txtDate" id="txtDate" value="<?php echo $objBank->dateExpired; ?>">
										</div>
									</div>

									<div class="col-sm-6">
										<div class="form-group">
											<label class="pv2-label" for="txtComisionPos">Comision POS</label>
											<input class="form-control" autocomplete="off" type="text" name="txtComisionPos" id="txtComisionPos" value="<?= $objBank->comisionPos ?>">
										</div>

										<div class="form-group">
											<label class="pv2-label" for="txtComisionSave">Comision Ahorro</label>
											<input class="form-control" autocomplete="off" type="text" name="txtComisionSave" id="txtComisionSave" value="<?= $objBank->comisionSave ?>">
										</div>
									</div>
								</div>
							</div>

							<div class="pv2-card">
								<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Referencias</p>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label class="pv2-label" for="txtReference1">Referencia 1</label>
											<input class="form-control" autocomplete="off" type="text" name="txtReference1" id="txtReference1" value="<?php echo $objBank->reference1; ?>">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label class="pv2-label" for="txtReference2">Referencia 2</label>
											<input class="form-control" autocomplete="off" type="text" name="txtReference2" id="txtReference2" value="<?php echo $objBank->reference2; ?>">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label class="pv2-label" for="txtComment">Comentario</label>
											<input class="form-control" autocomplete="off" type="text" name="txtComment" id="txtComment" value="<?php echo $objBank->note; ?>">
										</div>
									</div>
								</div>
							</div>

							<div class="pv2-card">
								<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-key"></i></span> Acceso al banco</p>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="pv2-label" for="txtURL">URL de banco</label>
											<input class="form-control" autocomplete="off" type="text" name="txtURL" id="txtURL" value="<?php echo $objBank->urlBank; ?>">
										</div>

										<div class="form-group">
											<label class="pv2-label" for="txtUsuario">Usuario</label>
											<input class="form-control" autocomplete="off" type="text" name="txtUsuario" id="txtUsuario" value="<?php echo $objBank->user; ?>">
										</div>
									</div>

									<div class="col-sm-6">
										<div class="form-group">
											<label class="pv2-label" for="txtPassword">Contraseña</label>
											<input class="form-control" autocomplete="new-password" type="password" name="txtPassword" id="txtPassword" value="<?php echo $objBank->password; ?>">
										</div>

										<div class="form-group">
											<label class="pv2-label" for="txtPin">Pin</label>
											<input class="form-control" autocomplete="new-password" type="password" name="txtPin" id="txtPin" value="<?php echo $objBank->pin; ?>">
										</div>
									</div>
								</div>
							</div>

						</form>
						<!-- /formulario -->

						<!-- barra inferior (telefono) -->
						<div class="pv2-mbar">
							<div class="pv2-mbar-info">
								<p class="pv2-mbar-label">Banco</p>
								<p class="pv2-mbar-total"><?php echo $objBank->name; ?></p>
							</div>
							<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
						</div>
					</div>