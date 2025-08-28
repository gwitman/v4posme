					<div class="row">
						<div id="email" class="col-lg-12">

							<!-- botonera -->
							<div class="email-bar" style="border-left:1px solid #c9c9c9">
								<div class="btn-group pull-right">
									<a href="<?php echo base_url(); ?>/app_cxp_expenses/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
									<a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
								</div>
							</div>
							<!-- /botonera -->
						</div>
						<!-- End #email  -->
					</div>
					<!-- End .row-fluid  -->

					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">

								<!-- titulo de comprobante-->
								<div class="panel-heading">
									<div class="icon"><i class="icon20 i-file"></i></div>
									<h4>Gasto:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->

								<!-- body -->
								<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
									<div class="panel-body printArea">

										<ul id="myTab" class="nav nav-tabs">
											<li class="active">
												<a href="#home" data-toggle="tab">Informacion</a>
											</li>
											<li>
												<a href="#profile" data-toggle="tab">Referencias.</a>
											</li>
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
												<ul class="dropdown-menu">
													<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
													<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
												</ul>
											</li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane fade in active" id="home">
												<div class="row">
													<div class="col-lg-6">


														<div class="form-group">
															<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
															<div class="col-lg-8">
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16" class="form-control" type="text" name="txtDate" id="txtDate">
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>

														<div class="form-group <?= getBehavio($company->type, "app_cxp_expenses", "lblAplicado", ""); ?>">
															<label class="col-lg-4 control-label" for="normal">Aplicado</label>
															<div class="col-lg-5">
																<input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1">
															</div>
														</div>

														<div class="form-group ">
															<label class="col-lg-4 control-label" for="selectFilter">Sucursal</label>
															<div class="col-lg-8">
																<select name="txtBranchID" id="txtBranchID" class="select2">
																	<?php
																	$counter = 0;
																	if ($objListBranch)
																		foreach ($objListBranch as $ws) {
																			if ($counter == 0)
																				echo "<option value='" . $ws->branchID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->branchID . "' >" . $ws->name . "</option>";

																			$counter++;
																		}
																	?>
																</select>
															</div>
														</div>


														<div class="form-group <?= getBehavio($company->type, "app_cxp_expenses", "lblCambio", ""); ?>">
															<label class="col-lg-4 control-label" for="normal">Cambio</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
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
																			echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal"><?php echo getBahavioDB($company->type, "app_cxp_expenses", "Referencia 1", "Referencia 1"); ?></label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtDetailReference1" id="txtDetailReference1" value="">
															</div>
														</div>


														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type, "app_cxp_expenses", "Referencia 2", ""); ?></label>
															<div class="col-lg-8">

																<input class="form-control" type="text" name="txtDetailReference2" id="txtDetailReference2" value="">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type, "app_cxp_expenses", "Referencia 3", ""); ?></label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtDetailReference3" id="txtDetailReference3" value="">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type, "app_cxp_expenses", "Referencia 4", "Reference 4"); ?></label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtDetailReference4" id="txtDetailReference4" value="">
															</div>
														</div>


													</div>
													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
															<div class="col-lg-8">
																<select name="txtPriorityID" id="txtPriorityID" class="select2">
																	<?php
																	$counter = 0;

																	if ($objListCatalogoTipoGastos)
																		foreach ($objListCatalogoTipoGastos as $ws) {
																			if ($counter == 0)
																				echo "<option value='" . $ws->publicCatalogDetailID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->publicCatalogDetailID . "' >" . $ws->name . "</option>";

																			$counter++;
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Categoria</label>
															<div class="col-lg-8">
																<select name="txtAreaID" id="txtAreaID" class="select2">
																	<?php
																	$counter = 0;
																	if ($objListCatalogoCategoriaGastos)
																		foreach ($objListCatalogoCategoriaGastos as $ws) {
																			if ($counter == 0)
																				echo "<option value='" . $ws->publicCatalogDetailID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->publicCatalogDetailID . "' >" . $ws->name . "</option>";

																			$counter++;
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="txtClassID">Clasificación</label>
															<div class="col-lg-8">
																<select name="txtClassID" id="txtClassID" class="select2">
																	<?php
																	$counter = 0;
																	$selected = getBahavioDB($company->type, "app_cxp_expenses", "txtClassID_select", "General"); 																																		
																	if ($objListCatalogItemClasificacion)
																			foreach ($objListCatalogItemClasificacion as $ws) {
																				if ($selected == $ws->display)
																					echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->display . "</option>";
																				else
																					echo "<option value='" . $ws->catalogItemID . "' >" . $ws->display . "</option>";

																				$counter++;
																			}
																		
																	?>
																</select>
															</div>
														</div>
														
														<div id="panelGeneralRigth">
															
														</div>
														
													</div>
												</div>
											</div>
											<div class="tab-pane fade" id="profile">
												<input type="hidden" name="txtDocumentNumber" id="txtDocumentNumber" value="">
												<input type="hidden" name="txtCustomerCreditDocumentID" id="txtCustomerCreditDocumentID" value="">
												<div class="row">
													<div class="col-lg-6 ">
														<div class="form-group" id="divPanelCurrency">
															<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-8">
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																	<?php
																	if ($objListCurrency)
																		foreach ($objListCurrency as $ws) {
																			if ($ws->currencyID == $objCurrency->currencyID)
																				echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
																			else
																				echo "<option value='" . $ws->currencyID . "' >" . $ws->simb . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group" id="divPanelMonto" >
															<label class="col-lg-4 control-label" for="normal">Monto</label>
															<div class="col-lg-8">
																<input type="hidden" name="txtDetailTransactionDetailID" value="0">
																<input class="form-control" type="text" name="txtDetailAmount" id="txtDetailAmount" value="0">
															</div>
														</div>

														<div class="form-group" id="divPanelIVA" >
															<label class="col-lg-4 control-label" for="txtTransactionMasterTax1">IVA</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtTransactionMasterTax1" id="txtTransactionMasterTax1" value="0">
															</div>
														</div>

														<div class="form-group" id="divPanelTotal" >
															<label class="col-lg-4 control-label" for="txtTransactionMasterTax2">Total</label>
															<div class="col-lg-8">
																<input class="form-control" readonly type="text" name="txtTransactionMasterTax2" id="txtTransactionMasterTax2" value="0">
															</div>
														</div>
													</div>

													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Proveedor</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtProviderID" name="txtProviderID" value="">
																	<input class="form-control" readonly id="txtProviderDescription" type="txtProviderDescription" value="">

																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearProvider">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchProvider">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Factura</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtAmortizationID" name="txtAmortizationID" value="">
																	<input class="form-control" readonly id="txtAmortizationDescription" type="txtAmortizationDescription" value="">

																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearAmortization">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchAmortization">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="dropdown">

											<div class="form-group">
												<label class="col-lg-2 control-label" for="normal">Descripcion</label>
												<div class="col-lg-6">
													<textarea class="form-control" id="txtNote" name="txtNote" rows="6"></textarea>
												</div>
											</div>

										</div>
										<div class="tab-pane fade" id="dropdown-file">

										</div>
									</div>


							</div>
							</form>
							<!-- /body -->
						</div>
					</div>
					</div>