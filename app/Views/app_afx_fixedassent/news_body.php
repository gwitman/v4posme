					<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

						<!-- encabezado y acciones -->
						<div class="pv2-card pv2-header">
							<div class="pv2-header-info">
								<div class="pv2-header-icon"><i class="icon20 i-car"></i></div>
								<div>
									<p class="pv2-header-title">Activo Fijo <span class="pv2-chip">Nuevo</span></p>
									<p class="pv2-header-sub">Registra los datos, la ubicación y los valores del activo</p>
								</div>
							</div>
							<div class="pv2-actions">
								<a href="<?php echo base_url(); ?>/app_afx_fixedassent/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
								<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
							</div>
						</div>
						<!-- /encabezado y acciones -->

								<!-- body -->
								<form id="form-new-afx-fixedassent" name="form-new-afx-fixedassent" role="form" autocomplete="off">
									<div class="pv2-card printArea">

										<ul id="myTab" class="nav nav-tabs">
											<li class="active"><a href="#home" data-toggle="tab">Informacion General</a></li>
											<li><a href="#ubicacion" data-toggle="tab">Ubicacion</a></li>
											<li><a href="#valores" data-toggle="tab">Valores</a></li>
											<li><a href="#propiedades" data-toggle="tab">Propiedades</a></li>
										</ul>

										<div class="tab-content">

											<div class="tab-pane fade in active" id="home">
												<div class="row">
													<div class="col-xs-12 col-md-6">

														<div class="form-group">
															<label class="pv2-label">Nombre</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtName" id="txtName" value="">
															</div>
														</div>

														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divEstado','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label">Estado</label>
															<div>
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
															<label class="pv2-label">Marca</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtMarca" id="txtMarca" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Modelo</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtModelNumber" id="txtModelNumber" value="">
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divCategoria','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblCategoria','Categoria',$objListCompanyPageSetting) ?></label>
															<div>
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
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divTipo','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblTipo','Tipo',$objListCompanyPageSetting) ?></label>
															<div>
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
													<div class="col-xs-12 col-md-6">
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divColor','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblColor','Color',$objListCompanyPageSetting) ?></label>
															<div>
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
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divChasis','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblChasis','Chasis No',$objListCompanyPageSetting) ?></label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtChasisNumber" id="txtChasisNumber" value="">
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divIsForaneo','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label">Es Foraneo</label>
															<div>
																<input type="checkbox" name="txtIsForaneo" id="txtIsForaneo" value="1">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Descripcion</label>
															<div>
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="6"></textarea>
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divReferencia1','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblReferencia1','Referencia1',$objListCompanyPageSetting) ?></label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="">
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divReferencia2','',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblReferencia2','Referencia2',$objListCompanyPageSetting) ?></label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="">
															</div>
														</div>
													</div>
												</div>

												<div class="row" id="secundaryRowHome">
													
												</div>
											</div>

											<div class="tab-pane fade" id="ubicacion">
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="pv2-label">Pais</label>
															<div>
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
															<label class="pv2-label">Departamento</label>
															<div>
																<select name="txtStateID" id="txtStateID" class="select2">
																	<option></option>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Municipio</label>
															<div>
																<select name="txtCityID" id="txtCityID" class="select2">
																	<option></option>
																</select>
															</div>
														</div>





														<div class="form-group">
															<label class="pv2-label">Direccion</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtAddress" id="txtAddress" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Area</label>
															<div>
																<div class="input-group afx-search">
																	<input type="hidden" id="txtAreaID" name="txtAreaID">
																	<input class="form-control" readonly id="txtAreaDescripcion" type="text" autocomplete="off">

																	<span class="input-group-btn">
																		<button class="btn btn-default" type="button" id="btnClearArea">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			Limpiar
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchArea">
																			<i aria-hidden="true" class="i-search-5"></i>
																			Buscar
																		</button>
																	</span>

																</div>
															</div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,"tb_fixed_assent","labelProyecto","Proyecto",$objListCompanyPageSetting); ?></label>
															<div>
																<div class="input-group afx-search">
																	<input type="hidden" id="txtProyectID" name="txtProyectID">
																	<input class="form-control" readonly id="txtProyectDescripcion" type="text" autocomplete="off">

																	<span class="input-group-btn">
																		<button class="btn btn-default" type="button" id="btnClearProyect">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			Limpiar
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchProyect">
																			<i aria-hidden="true" class="i-search-5"></i>
																			Buscar
																		</button>
																	</span>

																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Asignado A.</label>
															<div>
																<div class="input-group afx-search">
																	<input type="hidden" id="txtAsignedEmployeeID" name="txtAsignedEmployeeID">
																	<input class="form-control" readonly id="txtAsignedEmployeeDescripcion" type="text" autocomplete="off">

																	<span class="input-group-btn">
																		<button class="btn btn-default" type="button" id="btnClearEmployee">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			Limpiar
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchEmployee">
																			<i aria-hidden="true" class="i-search-5"></i>
																			Buscar
																		</button>
																	</span>

																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Cliente</label>
															<div>
																<div class="input-group afx-search">
																	<input type="hidden" id="txtAsignedCustomerID" name="txtAsignedCustomerID">
																	<input class="form-control" readonly id="txtAsignedCustomerDescripcion" type="text">

																	<span class="input-group-btn">
																		<button class="btn btn-default" type="button" id="btnClearCustomer">
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
														</div>
														<div class="form-group ">
															<label class="pv2-label">Sucursal</label>
															<div>
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
															<label class="pv2-label">Duracion</label>
															<div>
																<input class="form-control" type="number" autocomplete="off" name="txtDuration" id="txtDuration" value="">
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="tab-pane fade" id="valores">
												<div class="row">
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="pv2-label">Tipo de Activo</label>
															<div>
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
															<label class="pv2-label">Año</label>
															<div>
																<input class="form-control" type="number" autocomplete="off" name="txtYear" id="txtYear" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label" for="txtDate">Fecha de Ingreso</label>
															<div>
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate">
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Años de Utilidad</label>
															<div>
																<input class="form-control" type="number" autocomplete="off" name="txtYearUtility" id="txtYearUtility" value="">
															</div>
														</div>
													</div>
													<div class="col-xs-12 col-md-6">
														<div class="form-group">
															<label class="pv2-label">Moneda</label>
															<div>
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
															<label class="pv2-label">Precio Inicial</label>
															<div>
																<input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtPriceStart" id="txtPriceStart" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Ratio</label>
															<div>
																<input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtRatio" id="txtRatio" value="">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Tipo de Calculo</label>
															<div>
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
															<label class="pv2-label">Monto de liquidacion</label>
															<div>
																<input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtSettlementAmount" id="txtSettlementAmount" value="">
															</div>
														</div>

														<div class="form-group">
															<label class="pv2-label">Monto Corriente</label>
															<div>
																<input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtCurrentAmount" id="txtCurrentAmount" value="0">
															</div>
														</div>
													</div>
												</div>
											</div>

											<!-- Tab Propiedades -->
											<div class="tab-pane fade" id="propiedades">
												<div class="row" id="firstRowProperty" >
													<?php if(isset($objListProperties) && $objListProperties): ?>
														<?php
															$totalProps = count($objListProperties);
															$half = ceil($totalProps / 2);
															$propIndex = 0;
														?>
														<div class="col-xs-12 col-md-6">
															<?php foreach($objListProperties as $prop): ?>
																<?php if($propIndex == $half): ?>
																</div><div class="col-xs-12 col-md-6">
																<?php endif; ?>
																<div class="form-group">
																	<label class="pv2-label"><?php echo $prop->name; ?></label>
																	<div>
																		<?php if($prop->reference1 == 'checkbox'): ?>
																			<input type="checkbox" name="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" id="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" value="1">
																		<?php else: ?>
																			<input class="form-control" type="text" autocomplete="off" name="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" id="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" value="">
																		<?php endif; ?>
																	</div>
																</div>
																<?php $propIndex++; ?>
															<?php endforeach; ?>
														</div>
													<?php else: ?>
														<div class="col-lg-12">
															<p class="text-muted text-center" style="padding: 20px;">No hay propiedades configuradas.</p>
														</div>
													<?php endif; ?>
												</div>
											</div>
											<!-- /Tab Propiedades -->

										</div>
									</div>
								</form>
								<!-- /body -->

						<!-- barra inferior (telefono) -->
						<div class="pv2-mbar">
							<div class="pv2-mbar-info">
								<p class="pv2-mbar-label">Nuevo activo</p>
							</div>
							<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
						</div>
					</div>