					<div class="row">
						<div id="email" class="col-lg-12">

							<!-- botonera -->
							<div class="email-bar" style="border-left:1px solid #c9c9c9">
								<div class="btn-group pull-right">
									<a href="<?php echo base_url(); ?>/app_afx_fixedassent/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
									<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
									<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
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
									<h4>CODIGO:#<span class="invoice-num"><?php echo $objFA->fixedAssentCode; ?></span></h4>
								</div>
								<!-- /titulo de comprobante-->

								<!-- body -->
								<form id="form-new-afx-fixedassent" name="form-new-afx-fixedassent" class="form-horizontal" role="form">
									<div class="panel-body printArea">

										<ul id="myTab" class="nav nav-tabs">
											<li class="active"><a href="#home" data-toggle="tab">Informacion General</a></li>
											<li><a href="#ubicacion" data-toggle="tab">Ubicacion</a></li>
											<li><a href="#valores" data-toggle="tab">Valores</a></li>
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
												<ul class="dropdown-menu">
													<li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
												</ul>
											</li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane fade in active" id="home">
												<div class="row">
													<div class="col-lg-6">

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Nombre</label>
															<div class="col-lg-8">
																<input class="form-control" type="hidden" name="txtCompanyID" id="txtCompanyID" value="<?php echo $objFA->companyID; ?>">
																<input class="form-control" type="hidden" name="txtFixedAssentID" id="txtFixedAssentID" value="<?php echo $objFA->fixedAssentID; ?>">
																<input class="form-control" type="text" name="txtName" id="txtName" value="<?php echo $objFA->name; ?>">
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
																			if ($ws->workflowStageID == $objFA->statusID)
																				echo "<option value='" . $ws->workflowStageID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Marca</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtMarca" id="txtMarca" value="<?php echo $objFA->marca; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Modelo</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtModelNumber" id="txtModelNumber" value="<?php echo $objFA->modelNumber; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Categoria</label>
															<div class="col-lg-8">
																<select name="txtCategoryID" id="txtCategoryID" class="select2">
																	<option></option>
																	<?php

																	if ($objListCategory)
																		foreach ($objListCategory as $ws) {
																			if ($ws->catalogItemID == $objFA->categoryID)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			// $count++;
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
															<div class="col-lg-8">
																<select name="txtTypeID" id="txtTypeID" class="select2">
																	<option></option>
																	<?php

																	if ($objListType)
																		foreach ($objListType as $ws) {
																			if ($ws->catalogItemID == $objFA->typeID)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			// $count++;
																		}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Color</label>
															<div class="col-lg-8">
																<select name="txtColorID" id="txtColorID" class="select2">
																	<option></option>
																	<?php

																	if ($objListColor)
																		foreach ($objListColor as $ws) {
																			if ($ws->catalogItemID == $objFA->colorID)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			// $count++;
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Chasis No</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtChasisNumber" id="txtChasisNumber" value="<?php echo $objFA->chasisNumber; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Es Foraneo</label>
															<div class="col-lg-8">
																<input type="checkbox" name="txtIsForaneo" id="txtIsForaneo" value="1" <?php echo ($objFA->isForaneo ? "checked" : ""); ?>>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Descripcion</label>
															<div class="col-lg-8">
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="6"><?php echo $objFA->description; ?></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia1</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtReference1" id="txtReference1" value="<?php echo $objFA->reference1; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia2</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtReference2" id="txtReference2" value="<?php echo $objFA->reference2; ?>">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane fade" id="ubicacion">
												<div class="row">

													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Pais</label>
															<div class="col-lg-8">
																<select name="txtCountryID" id="txtCountryID" class="select2">
																	<option></option>
																	<?php
																	if ($objListCountry)
																		foreach ($objListCountry as $lc) {
																			if ($lc->catalogItemID == $objFA->countryID) {
																				echo "<option value='" . $lc->catalogItemID . "' selected >" . $lc->name . "</option>";
																			} else {
																				echo "<option value='" . $lc->catalogItemID . "'  >" . $lc->name . "</option>";
																			}
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Departamento</label>
															<div class="col-lg-8">
																<select name="txtStateID" id="txtStateID" class="select2">
																	<option></option>
																	<?php
																	if ($objListState)
																		foreach ($objListState as $lc) {
																			if ($lc->catalogItemID == $objFA->cityID) {
																				echo "<option value='" . $lc->catalogItemID . "' selected >" . $lc->name . "</option>";
																			} else {
																				echo "<option value='" . $lc->catalogItemID . "'  >" . $lc->name . "</option>";
																			}
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Municipio</label>
															<div class="col-lg-8">
																<select name="txtCityID" id="txtCityID" class="select2">
																	<option></option>
																	<?php
																	if ($objListMunicipality)
																		foreach ($objListMunicipality as $lm) {
																			if ($lm->catalogItemID == $objFA->municipalityID) {
																				echo "<option value='" . $lm->catalogItemID . "' selected >" . $lm->name . "</option>";
																			} else {
																				echo "<option value='" . $lm->catalogItemID . "'  >" . $lm->name . "</option>";
																			}
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Direccion</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtAddress" id="txtAddress" value="<?= $objFA->address; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Area</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtAreaID" name="txtAreaID" value="<?= $objFA->areaID ?>">
																	<input class="form-control" readonly id="txtAreaDescripcion" type="txtAreaDescripcion" value="">

																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearArea">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchArea">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>

																</div>
															</div>
														</div>
													</div>
													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Proyecto</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtProyectID" name="txtProyectID">
																	<input class="form-control" readonly id="txtProyectDescripcion" type="txtProyectDescripcion">

																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearProyect">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchProyect">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>

																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Asignado A</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtAsignedEmployeeID" name="txtAsignedEmployeeID" value="<?php echo $objFA->asignedEmployeeID; ?>">
																	<input class="form-control" readonly id="txtAsignedEmployeeDescripcion" type="txtAsignedEmployeeDescripcion" value="<?php echo $objAsignedEmployee == null  ?  ""  : ($objAsignedEmployee->employeNumber . " / " . $objAsignedNatural->firstName . " " . $objAsignedNatural->lastName); ?>">

																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearEmployee">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchEmployee">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>

																</div>
															</div>
														</div>
														<div class="form-group ">
															<label class="col-lg-4 control-label" for="selectFilter">Sucursal</label>
															<div class="col-lg-8">
																<select name="txtBranchID" id="txtBranchID" class="select2">
																	<?php
																	if ($objListBranch)
																		foreach ($objListBranch as $lb) {
																			if ($lb->branchID == $objFA->branchID)
																				echo "<option value='" . $lb->branchID . "' selected >" . $lb->name . "</option>";
																			else
																				echo "<option value='" . $lb->branchID . "' >" . $lb->name . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Duracion</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtDuration" id="txtDuration" value="<?= $objFA->duration ?>">
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="tab-pane fade" id="valores">
												<div class="row">
													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Tipo de Activo</label>
															<div class="col-lg-8">
																<select name="txtTypeAssentID" id="txtTypeAssentID" class="select2">
																	<option></option>
																	<?php
																	if ($objListTypeFixedAssent)
																		foreach ($objListTypeFixedAssent as $tfa) {
																			if ($tfa->catalogItemID == $objFA->typeFixedAssentID)
																				echo "<option value='" . $tfa->catalogItemID . "' selected >" . $tfa->name . "</option>";
																			else
																				echo "<option value='" . $tfa->catalogItemID . "'  >" . $tfa->name . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Año</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtYear" id="txtYear" value="<?php echo $objFA->year; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="datepicker">Fecha de Ingreso</label>
															<div class="col-lg-8">
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?= explode(" ", $objFA->startOn)[0] ?>">
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Años de Utilidad</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtYearUtility" id="txtYearUtility" value="<?php echo $objFA->yearOfUtility; ?>">
															</div>
														</div>
													</div>

													<div class="col-lg-6">
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-8">
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																	<option></option>
																	<?php
																	if ($objListCurrency)
																		foreach ($objListCurrency as $lc) {
																			if ($lc->currencyID == $objFA->currencyID)
																				echo "<option value='" . $lc->currencyID . "' selected >" . $lc->name . "</option>";
																			else
																				echo "<option value='" . $lc->currencyID . "'  >" . $lc->name . "</option>";
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Precio Inicial</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtPriceStart" id="txtPriceStart" value="<?php echo $objFA->priceStart; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Ratio</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtRatio" id="txtRatio" value="<?= $objFA->ratio ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Tipo de Calculo</label>
															<div class="col-lg-8">
																<select name="txtTypeDepresiationID" id="txtTypeDepresiationID" class="select2">
																	<option></option>
																	<?php

																	if ($objListTypeDepresiation)
																		foreach ($objListTypeDepresiation as $ws) {
																			if ($ws->catalogItemID == $objFA->typeDepresiationID)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			// $count++;
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Monto de liquidacion</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtSettlementAmount" id="txtSettlementAmount" value="<?= $objFA->settlementAmount ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Monto Corriente</label>
															<div class="col-lg-8">
																<input class="form-control" readonly type="number" name="txtCurrentAmount" id="txtCurrentAmount" value="0">
															</div>
														</div>

													</div>
												</div>
											</div>

										</div>

									</div>
								</form>
								<!-- /body -->
							</div>
						</div>
					</div>