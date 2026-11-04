					<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

						<!-- encabezado y acciones -->
						<div class="pv2-card pv2-header">
							<div class="pv2-header-info">
								<div class="pv2-header-icon"><i class="icon20 i-car"></i></div>
								<div>
									<p class="pv2-header-title">Activo Fijo <span class="pv2-chip">#<span class="invoice-num"><?php echo $objFA->fixedAssentCode; ?></span></span></p>
									<p class="pv2-header-sub">Registra los datos, la ubicación y los valores del activo</p>
								</div>
							</div>
							<div class="pv2-actions">
								<a href="<?php echo base_url(); ?>/app_afx_fixedassent/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
								<button type="button" class="btn btn-default" id="btnClickArchivo"><i class="icon16 i-file"></i> Archivos</button>
								<!-- Sin formato de impresion disponible: se oculta sin retirarlo del DOM -->
								<button type="button" class="btn btn-default" id="btnPrinter" style="display:none;"><i class="icon16 i-print"></i> Imprimir</button>
								<button type="button" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</button>
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
											<li><a id="btnClickExpediente" href="#expediente" data-toggle="tab"><i class="icon16 i-wrench"></i> Expediente</a></li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane fade in active" id="home">
												<div class="row">
													<div class="col-xs-12 col-md-6">

														<div class="form-group">
															<label class="pv2-label">Nombre</label>
															<div>
																<input class="form-control" type="hidden" name="txtCompanyID" id="txtCompanyID" value="<?php echo $objFA->companyID; ?>">
																<input class="form-control" type="hidden" name="txtFixedAssentID" id="txtFixedAssentID" value="<?php echo $objFA->fixedAssentID; ?>">
																<input class="form-control" type="text" autocomplete="off" name="txtName" id="txtName" value="<?php echo $objFA->name; ?>">
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divEstado',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label">Estado</label>
															<div>
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
															<label class="pv2-label">Marca</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtMarca" id="txtMarca" value="<?php echo $objFA->marca; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Modelo</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtModelNumber" id="txtModelNumber" value="<?php echo $objFA->modelNumber; ?>">
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divCategoria',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblCategoria','Categoria',$objListCompanyPageSetting) ?></label>
															<div>
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
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divTipo',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblTipo','Tipo',$objListCompanyPageSetting) ?></label>
															<div>
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
													<div class="col-xs-12 col-md-6">
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divColor',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblColor','Color',$objListCompanyPageSetting) ?></label>
															<div>
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
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divChasis',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblChasis','Chasis No',$objListCompanyPageSetting) ?></label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtChasisNumber" id="txtChasisNumber" value="<?php echo $objFA->chasisNumber; ?>">
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divIsForaneo',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label">Es Foraneo</label>
															<div>
																<input type="checkbox" name="txtIsForaneo" id="txtIsForaneo" value="1" <?php echo ($objFA->isForaneo ? "checked" : ""); ?>>
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Descripcion</label>
															<div>
																<textarea class="form-control" id="txtDescription" name="txtDescription" rows="6"><?php echo $objFA->description; ?></textarea>
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divReferencia1',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblReferencia1','Referencia1',$objListCompanyPageSetting) ?></label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?php echo $objFA->reference1; ?>">
															</div>
														</div>
														<div class="form-group <?php echo getBahavioSession($company->type,'tb_fixed_assent','divReferencia2',' ',$objListCompanyPageSetting) ?>">
															<label class="pv2-label"><?php echo getBahavioSession($company->type,'tb_fixed_assent','lblReferencia2','Referencia2',$objListCompanyPageSetting) ?></label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?php echo $objFA->reference2; ?>">
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
															<label class="pv2-label">Departamento</label>
															<div>
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
															<label class="pv2-label">Municipio</label>
															<div>
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
															<label class="pv2-label">Direccion</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtAddress" id="txtAddress" value="<?= $objFA->address; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Area</label>
															<div>
																<div class="input-group afx-search">
																	<input type="hidden" id="txtAreaID" name="txtAreaID" value="<?= $objFA->areaID ?>">
																	<input class="form-control" readonly id="txtAreaDescripcion" type="text" autocomplete="off" value="<?= $objArea != null ? $objArea->Id . " | " . $objArea->Indicador : "" ?>">

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
																	<input type="hidden" id="txtProyectID" name="txtProyectID" value="<?= $objFA->projectID?>">
																<input class="form-control" readonly id="txtProyectDescripcion" type="text" autocomplete="off" value="<?= $objProject != null ? $objProject->Id  . " | " . $objProject->Indicador : "" ?>">

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
															<label class="pv2-label">Asignado A</label>
															<div>
																<div class="input-group afx-search">
																	<input type="hidden" id="txtAsignedEmployeeID" name="txtAsignedEmployeeID" value="<?php echo $objFA->asignedEmployeeID; ?>">
																	<input class="form-control" readonly id="txtAsignedEmployeeDescripcion" type="text" autocomplete="off" value="<?php echo $objAsignedEmployee == null  ?  ""  : ($objAsignedEmployee->employeNumber . " / " . $objAsignedNatural->firstName . " " . $objAsignedNatural->lastName); ?>">

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
																	<input type="hidden" id="txtAsignedCustomerID" name="txtAsignedCustomerID" value="<?php echo isset($objFA->asignedCustomerID) ? $objFA->asignedCustomerID : 0; ?>">
																	<input class="form-control" readonly id="txtAsignedCustomerDescripcion" type="text" value="<?php echo $objAsignedCustomer == null ? "" : ($objAsignedCustomerNatural != null ? strtoupper($objAsignedCustomer->customerNumber . " " . $objAsignedCustomerNatural->firstName . " " . $objAsignedCustomerNatural->lastName) : strtoupper($objAsignedCustomer->customerNumber . " " . $objAsignedCustomerLegal->comercialName)); ?>">

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
															<label class="pv2-label">Duracion</label>
															<div>
																<input class="form-control" type="number" autocomplete="off" name="txtDuration" id="txtDuration" value="<?= $objFA->duration ?>">
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
															<label class="pv2-label">Año</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtYear" id="txtYear" value="<?php echo $objFA->year; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label" for="txtDate">Fecha de Ingreso</label>
															<div>
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate" value="<?= explode(" ", $objFA->startOn)[0] ?>">
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Años de Utilidad</label>
															<div>
																<input class="form-control" type="text" autocomplete="off" name="txtYearUtility" id="txtYearUtility" value="<?php echo $objFA->yearOfUtility; ?>">
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
															<label class="pv2-label">Precio Inicial</label>
															<div>
																<input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtPriceStart" id="txtPriceStart" value="<?php echo $objFA->priceStart; ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Ratio</label>
															<div>
																<input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtRatio" id="txtRatio" value="<?= $objFA->ratio ?>">
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Tipo de Calculo</label>
															<div>
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
															<label class="pv2-label">Monto de liquidacion</label>
															<div>
																<input class="form-control pv2-num afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtSettlementAmount" id="txtSettlementAmount" value="<?= $objFA->settlementAmount ?>">
															</div>
														</div>

														<div class="form-group">
															<label class="pv2-label">Monto Corriente</label>
															<div>
																<input class="form-control pv2-num afx-monto" readonly type="text" inputmode="decimal" autocomplete="off" name="txtCurrentAmount" id="txtCurrentAmount" value="<?= $objFA->currentAmount?>">
															</div>
														</div>

													</div>
												</div>
											</div>

											<!-- Tab Propiedades -->
											<div class="tab-pane fade" id="propiedades">
												<div class="row" id="firstRowProperty"  >
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
																		<?php
																			$propValue = isset($objListPropertyValues[$prop->publicCatalogDetailID]) ? $objListPropertyValues[$prop->publicCatalogDetailID] : '';
																		?>
																		<?php if($prop->reference1 == 'checkbox'): ?>
																			<input type="checkbox" name="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" id="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" value="1" <?php echo ($propValue == '1') ? 'checked' : ''; ?>>
																		<?php else: ?>
																			<input class="form-control" type="text" autocomplete="off" name="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" id="txtProperty_<?php echo $prop->publicCatalogDetailID; ?>" value="<?php echo $propValue; ?>">
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

											<!-- Tab Expediente (Historial de Ordenes de Taller) -->
											<div class="tab-pane fade" id="expediente">
												<div id="appExpediente" style="visibility:hidden;">

													<!-- Resumen -->
													<div class="row" style="margin-bottom:15px;">
														<div class="col-lg-12">
															<div style="background:linear-gradient(135deg,#4e73df 0%,#224abe 100%);color:#fff;border-radius:10px;padding:16px 20px;box-shadow:0 4px 12px rgba(0,0,0,0.15);">
																<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;">
																	<div>
																		<h4 style="margin:0;color:#fff;font-weight:600;"><i class="fas fa-tools"></i> Expediente de Mantenimiento</h4>
																		<small style="opacity:.9;">Historial cronol&oacute;gico de &oacute;rdenes de taller del activo fijo</small>
																	</div>
																	<div style="text-align:right;">
																		<span style="font-size:34px;font-weight:700;line-height:1;">{{ objListOrders.length }}</span><br>
																		<small style="opacity:.9;">&oacute;rdenes registradas</small>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<!-- Mensaje / sin datos -->
													<div class="row" v-if="mostrarAlerta">
														<div class="col-lg-12">
															<div class="alert" :class="esError ? 'alert-danger' : 'alert-info'" style="border-radius:8px;">
																<i class="fas" :class="esError ? 'fa-exclamation-triangle' : 'fa-info-circle'"></i>
																{{ mensaje }}
															</div>
														</div>
													</div>

													<!-- Linea de tiempo -->
													<div class="row" v-if="!mostrarAlerta">
														<div class="col-lg-12">
															<div class="afx-timeline">
																<div class="afx-timeline-item" v-for="(orden, idx) in objListOrders" :key="orden.transactionMasterID">
																	<div class="afx-timeline-badge" :style="{background: colorEstado(orden.estadoOrden)}">
																		<i class="fas fa-wrench"></i>
																	</div>
																	<div class="afx-card">
																		<div class="afx-card-header" :style="{borderLeft: '5px solid ' + colorEstado(orden.estadoOrden)}">
																			<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;">
																				<div>
																					<span class="afx-order-num"><i class="fas fa-hashtag"></i> {{ orden.numero || orden.transactionMasterID }}</span>
																					<span class="afx-badge" :style="{background: colorEstado(orden.estadoOrden)}">{{ orden.estadoOrden || 'Sin estado' }}</span>
																				</div>
																				<div class="afx-fecha"><i class="fas fa-calendar-alt"></i> {{ formatFecha(orden.fecha) }}</div>
																			</div>
																		</div>
																		<div class="afx-card-body">
																			<div class="row">
																				<div class="col-lg-4 col-sm-6">
																					<div class="afx-meta"><span class="afx-meta-label"><i class="fas fa-cogs"></i> Estado del equipo</span><span class="afx-meta-value afx-tag-info">{{ orden.estadoEquipo || 'N/D' }}</span></div>
																				</div>
																				<div class="col-lg-4 col-sm-6">
																					<div class="afx-meta"><span class="afx-meta-label"><i class="fas fa-screwdriver"></i> Tipo mantenimiento</span><span class="afx-meta-value afx-tag-warning">{{ orden.tipoMantenimiento || 'N/D' }}</span></div>
																				</div>
																				<div class="col-lg-4 col-sm-6" v-if="orden.proximaVisita">
																					<div class="afx-meta"><span class="afx-meta-label"><i class="fas fa-calendar-check"></i> Pr&oacute;xima visita</span><span class="afx-meta-value afx-tag-success">{{ formatFecha(orden.proximaVisita) }}</span></div>
																				</div>
																			</div>

																			<!-- Problema -->
																			<div class="afx-bloque afx-bloque-problema" v-if="orden.problema">
																				<div class="afx-bloque-title"><i class="fas fa-exclamation-triangle"></i> Problema</div>
																				<div class="afx-bloque-text">{{ orden.problema }}</div>
																			</div>

																			<!-- Nota -->
																			<div class="afx-nota" v-if="orden.nota">
																				<i class="fas fa-sticky-note"></i> {{ orden.nota }}
																			</div>

																			<!-- Solucion -->
																			<div class="afx-bloque afx-bloque-solucion" v-if="orden.solucion">
																				<div class="afx-bloque-title"><i class="fas fa-check-circle"></i> Soluci&oacute;n</div>
																				<div class="afx-bloque-text">{{ orden.solucion }}</div>
																			</div>

																			<!-- Problemas y soluciones adicionales (comentarios) -->
																			<div v-if="orden.comentarios && orden.comentarios.length > 0" style="margin-top:12px;">
																				<div class="afx-sub-title"><i class="fas fa-clipboard-list"></i> Detalle adicional</div>
																				<div class="afx-problema" v-for="(c, ci) in orden.comentarios" :key="ci">
																					<div class="afx-problema-icon"><i class="fas fa-exclamation-circle"></i></div>
																					<div style="flex:1;">
																						<div class="afx-problema-text">{{ c.problema || 'Sin descripci\u00f3n' }}</div>
																						<span class="afx-solucion" v-if="c.situacion"><i class="fas fa-check-circle"></i> {{ c.situacion }}</span>
																					</div>
																				</div>
																			</div>

																			<!-- Sin informacion -->
																			<div v-if="!orden.problema && !orden.solucion && !orden.nota && (!orden.comentarios || orden.comentarios.length === 0)" class="afx-sin-problemas">
																				<i class="fas fa-check-circle"></i> Sin problemas registrados en esta orden
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

												</div>
											</div>
											<!-- /Tab Expediente -->

										</div>

									</div>
								</form>
								<!-- /body -->

						<!-- barra inferior (telefono) -->
						<div class="pv2-mbar">
							<div class="pv2-mbar-info">
								<p class="pv2-mbar-label">Editar activo</p>
							</div>
							<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
						</div>
					</div>