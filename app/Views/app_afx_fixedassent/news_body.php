					<div class="row">
						<div id="email" class="col-lg-12">

							<!-- botonera -->
							<div class="email-bar" style="border-left:1px solid #c9c9c9">
								<div class="btn-group pull-right">
									<a href="<?php echo base_url(); ?>/app_afx_fixedassent/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
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
									<h4>CODIGO:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->

								<!-- body -->
								<form id="form-new-afx-fixedassent" name="form-new-afx-fixedassent" class="form-horizontal" role="form">
									<div class="panel-body printArea">

										<ul id="myTab" class="nav nav-tabs">
											<li class="active"><a href="#home" data-toggle="tab">Informacion General</a></li>
											<li><a href="#ubicacion" data-toggle="tab">Ubicacion</a></li>
											<li><a href="#valores" data-toggle="tab">Valores</a></li>
										</ul>

										<div class="tab-content">

											<div class="tab-pane fade in active" id="home">
												<div class="row">
													<div class="col-lg-6">

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Nombre</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtName" id="txtName" value="">
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
															<label class="col-lg-4 control-label" for="normal">Marca</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtMarca" id="txtMarca" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Modelo</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtModelNumber" id="txtModelNumber" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Categoria</label>
															<div class="col-lg-8">
																<select name="txtCategoryID" id="txtCategoryID" class="select2">
																	<option></option>
																	<?php
																	$count = 0;
																	if ($objListCategory)
																		foreach ($objListCategory as $ws) {
																			if ($count == 0)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			$count++;
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
																	$count = 0;
																	if ($objListType)
																		foreach ($objListType as $ws) {
																			if ($count == 0)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			$count++;
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
																	$count = 0;
																	if ($objListColor)
																		foreach ($objListColor as $ws) {
																			if ($count == 0)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			$count++;
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Chasis No</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtChasisNumber" id="txtChasisNumber" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Es Foraneo</label>
															<div class="col-lg-8">
																<input type="checkbox" name="txtIsForaneo" id="txtIsForaneo" value="1">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Descripcion</label>
															<div class="col-lg-8">
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="6"></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia1</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtReference1" id="txtReference1" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia2</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtReference2" id="txtReference2" value="">
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
																	$count = 0;
																	if ($objListCountry)
																		foreach ($objListCountry as $ws) {
																			echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
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
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Municipio</label>
															<div class="col-lg-8">
																<select name="txtCityID" id="txtCityID" class="select2">
																	<option></option>
																</select>
															</div>
														</div>





														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Direccion</label>
															<div class="col-lg-8">
																<input class="form-control" type="text" name="txtAddress" id="txtAddress" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Area</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtAreaID" name="txtAreaID">
																	<input class="form-control" readonly id="txtAreaDescripcion" type="txtAreaDescripcion">

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
															<label class="col-lg-4 control-label" for="buttons">Asignado A.</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtAsignedEmployeeID" name="txtAsignedEmployeeID">
																	<input class="form-control" readonly id="txtAsignedEmployeeDescripcion" type="txtAsignedEmployeeDescripcion">

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
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Duracion</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtDuration" id="txtDuration" value="">
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
																	$count = 0;
																	if ($objListTypeFixedAssent)
																		foreach ($objListTypeFixedAssent as $tfa) {
																			if ($count == 0)
																				echo "<option value='" . $tfa->catalogItemID . "' selected >" . $tfa->name . "</option>";
																			else
																				echo "<option value='" . $tfa->catalogItemID . "'  >" . $tfa->name . "</option>";
																			$count++;
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Año</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtYear" id="txtYear" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="datepicker">Fecha de Ingreso</label>
															<div class="col-lg-8">
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16" class="form-control" type="text" name="txtDate" id="txtDate">
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Años de Utilidad</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtYearUtility" id="txtYearUtility" value="">
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
																	$count = 0;
																	if ($objListCurrency)
																		foreach ($objListCurrency as $lc) {
																			if ($count == 0)
																				echo "<option value='" . $lc->currencyID . "' selected >" . $lc->name . "</option>";
																			else
																				echo "<option value='" . $lc->currencyID . "'  >" . $lc->name . "</option>";
																			$count++;
																		}
																	?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Precio Inicial</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtPriceStart" id="txtPriceStart" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Ratio</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtRatio" id="txtRatio" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Tipo de Calculo</label>
															<div class="col-lg-8">
																<select name="txtTypeDepresiationID" id="txtTypeDepresiationID" class="select2">
																	<option></option>
																	<?php
																	$count = 0;
																	if ($objListTypeDepresiation)
																		foreach ($objListTypeDepresiation as $ws) {
																			if ($count == 0)
																				echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
																			else
																				echo "<option value='" . $ws->catalogItemID . "'  >" . $ws->name . "</option>";
																			$count++;
																		}
																	?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Monto de liquidacion</label>
															<div class="col-lg-8">
																<input class="form-control" type="number" name="txtSettlementAmount" id="txtSettlementAmount" value="">
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