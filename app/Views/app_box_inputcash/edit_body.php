<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
			<div>
				<p class="pv2-header-title">INGRESO:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span>
					<?php if($objTransactionMaster->isApplied){ ?><span class="pv2-chip is-applied">Aplicado</span><?php } else { ?><span class="pv2-chip">Pendiente</span><?php } ?>
				</p>
				<p class="pv2-header-sub">Editar ingreso a caja</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_box_inputcash/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
			<a href="<?php echo base_url(); ?>/app_box_inputcash/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-default" id="btnClickArchivo"><i class="icon16 i-attachment"></i> Archivos</a>
			<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

			<!-- body -->
			<form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">

					<div class="pv2-card" id="home">
						<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Información</p>
						<div class="row">
							<div class="col-sm-6">

									<input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
									<input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
									<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">
									<input type="hidden" name="txtDetailTransactionDetailID" value="<?php echo $objTransactionMasterDetail[0]->transactionMasterDetailID; ?>">


									<div class="form-group">
										<label class="pv2-label" for="txtDate">Fecha</label>
										<div>
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" autocomplete="off" type="text" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>

									<div class="form-group">
											<label class="pv2-label" for="txtIsApplied">Aplicado</label>
											<div>
												<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1"												                                                                                 												                                                                                 												                                                                                  <?php if ($objTransactionMaster->isApplied) {
                                                                                                                                                                                                                                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                                                    ?> >
											</div>
									</div>
									<div class="form-group">
											<label class="pv2-label" for="txtExchangeRate">Cambio</label>
											<div>
												<input class="form-control" autocomplete="off" type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
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

                                                                if ($ws->workflowStageID == $objTransactionMaster->statusID) {
                                                                    echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                                } else {
                                                                    echo "<option value='" . $ws->workflowStageID . "' >" . $ws->name . "</option>";
                                                                }

                                                            }
                                                        }

                                                    ?>
											</select>
										</div>
									</div>

									<div class="form-group">
											<label class="pv2-label" for="txtDetailReference1">Referencia 1</label>
											<div>
												<input class="form-control" autocomplete="off" type="text"  name="txtDetailReference1" id="txtDetailReference1" value="<?php echo $objTransactionMaster->reference1; ?>">
											</div>
									</div>


									<div class="form-group">
											<label class="pv2-label" for="txtDetailReference2">Referencia 2</label>
											<div>

												<input class="form-control" autocomplete="off" type="text"  name="txtDetailReference2" id="txtDetailReference2" value="<?php echo $objTransactionMaster->reference2; ?>">
											</div>
									</div>

									<div class="form-group">
											<label class="pv2-label" for="txtDetailReference3">Referencia 3</label>
											<div>
												<input class="form-control" autocomplete="off" type="text"  name="txtDetailReference3" id="txtDetailReference3" value="<?php echo $objTransactionMaster->reference3; ?>">
											</div>
									</div>


							</div>
							<div class="col-sm-6">

								<div class="form-group">
									<label class="pv2-label" for="txtBranchID">Sucursal</label>
									<div>
										<select name="txtBranchID" id="txtBranchID"  class="select2">
												<option></option>
												<?php
                                                    foreach ($objListBranch as $i) {
                                                        if ($i->branchID == $objTransactionMaster->branchID) {
                                                            echo "<option value='" . $i->branchID . "' selected='selected'>" . $i->name . "</option>";
                                                        } else {
                                                            echo "<option value='" . $i->branchID . "'>" . $i->name . "</option>";
                                                        }

                                                    }
                                                ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="pv2-label" for="txtCurrencyID">Moneda</label>
									<div>
										<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
											<?php
                                                if ($objListCurrency) {
                                                    foreach ($objListCurrency as $ws) {
                                                        if ($ws->currencyID == $objTransactionMaster->currencyID) {
                                                            echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
                                                        } else {
                                                            echo "<option value='" . $ws->currencyID . "' >" . $ws->simb . "</option>";
                                                        }

                                                    }
                                                }

                                            ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="pv2-label" for="txtBoxID">Caja</label>
									<div>
										<select name="txtBoxID" id="txtBoxID" class="select2">																		>
											<?php
                                                if (isset($objListCashBox)) {
                                                    $i = 0;
                                                    foreach ($objListCashBox as $cb) {
                                                        if ($cb->cashBoxID == $objTransactionMaster->classID) {
                                                            echo "<option value='" . $cb->cashBoxID . "' selected>" . $cb->name . "</option>";
                                                        } else {
                                                            echo "<option value='" . $cb->cashBoxID . "'>" . $cb->name . "</option>";
                                                        }
                                                        $i++;
                                                    }
                                                }

                                            ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="pv2-label" for="txtAreaID">Tipo</label>
									<div>
										<select name="txtAreaID" id="txtAreaID" class="select2">
												<?php
                                                    if ($objTipoMovement) {
                                                        foreach ($objTipoMovement as $ws) {
                                                            if ($ws->catalogItemID == $objTransactionMaster->areaID) {
                                                                echo "<option value='" . $ws->catalogItemID . "' selected>" . $ws->name . "</option>";
                                                            } else {
                                                                echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                                                            }

                                                        }
                                                    }

                                                ?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="pv2-label" for="txtPriorityID">Categoría</label>
									<div>
										<select name="txtPriorityID" id="txtPriorityID" class="select2">
												<?php
                                                    $counter = 0;
                                                    if ($objSubTipoMovement) {
                                                        foreach ($objSubTipoMovement as $ws) {
                                                            if ($ws->catalogItemID == $objTransactionMaster->priorityID) {
                                                                echo "<option value='" . $ws->catalogItemID . "' selected >" . $ws->name . "</option>";
                                                            } else {
                                                                echo "<option value='" . $ws->catalogItemID . "' >" . $ws->name . "</option>";
                                                            }

                                                            $counter++;

                                                        }
                                                    }

                                                ?>
										</select>
									</div>
								</div>

								<div class="form-group">
										<label class="pv2-label" for="txtDetailAmount">Monto</label>
										<div>
											<input class="form-control pv2-num" autocomplete="off" inputmode="decimal" type="text"  name="txtDetailAmount" id="txtDetailAmount" value="<?php echo number_format((float)$objTransactionMasterDetail[0]->amount,2); ?>">
										</div>
								</div>




								<div class="pv2-kpis">
									<div class="pv2-kpi k-ok">
										<div class="pv2-kpi-icon"><i class="i-download-2"></i></div>
										<div class="pv2-kpi-body"><span class="pv2-kpi-label">C$ compra</span><span class="pv2-kpi-value"><?php echo number_format(0,2); ?></span></div>
									</div>
									<div class="pv2-kpi k-warn">
										<div class="pv2-kpi-icon"><i class="i-search-3"></i></div>
										<div class="pv2-kpi-body"><span class="pv2-kpi-label">C$ venta</span><span class="pv2-kpi-value"><?php echo number_format(0,2); ?></span></div>
									</div>
									<div class="pv2-kpi k-info">
										<div class="pv2-kpi-icon"><i class="i-temperature"></i></div>
										<div class="pv2-kpi-body"><span class="pv2-kpi-label">C$</span><span class="pv2-kpi-value"><?php echo number_format((float)$exchangeRate,2); ?></span></div>
									</div>
								</div>
						</div>
						</div>
					</div>
					<div class="pv2-card" id="profile">
						<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Denominaciones</p>
						<div class="row">
							<div class="col-sm-6 currency-1">

								<?php
                                    if ($objTransactionMasterDenomination) {
                                        foreach ($objTransactionMasterDenomination as $deno) {

                                            if ($deno->currencyID == 1) {
                                            ?>
										<div class="form-group">
											<label class="pv2-label"><?php echo $deno->denominationName; ?></label>
											<div>
												<input type="hidden" name="txtTransactionMasterDenominationID[]" value="0">
												<input type="hidden" name="txtTransactionMasterDenominationCatalogItemID[]" value="<?php echo $deno->catalogItemID; ?>">
												<input type="hidden" name="txtTransactionMasterDenominationCurrencyID[]" value="<?php echo $deno->currencyID; ?>">
												<input type="hidden" name="txtTransactionMasterDenominationExchangeRate[]" value="<?php echo $exchangeRate; ?>">
												<input type="hidden" name="txtTransactionMasterDenominationReference[]" value="<?php echo $deno->reference1; ?>">
												<input class="form-control pv2-num denomination-quantity" autocomplete="off" inputmode="decimal" type="text"  name="txtTransactionMasterDenominationQuantity[]"  data-reference="<?php echo $deno->reference1; ?>"   value="<?php echo $deno->quantity; ?>">
											</div>
										</div>
										<?php
                                            }
                                                }

                                            }
                                        ?>


							</div>

							<div class="col-sm-6 currency-2">
								<?php
                                    if ($objTransactionMasterDenomination) {
                                        foreach ($objTransactionMasterDenomination as $deno) {

                                            if ($deno->currencyID == 2) {
                                            ?>
										<div class="form-group">
											<label class="pv2-label"><?php echo $deno->denominationName; ?></label>
											<div>
												<input type="hidden" name="txtTransactionMasterDenominationID[]" value="0">
												<input type="hidden" name="txtTransactionMasterDenominationCatalogItemID[]" value="<?php echo $deno->catalogItemID; ?>">
												<input type="hidden" name="txtTransactionMasterDenominationCurrencyID[]" value="<?php echo $deno->currencyID; ?>">
												<input type="hidden" name="txtTransactionMasterDenominationExchangeRate[]" value="<?php echo $exchangeRate; ?>">
												<input type="hidden" name="txtTransactionMasterDenominationReference[]" value="<?php echo $deno->reference1; ?>">
												<input class="form-control pv2-num denomination-quantity" autocomplete="off" inputmode="decimal" type="text"  name="txtTransactionMasterDenominationQuantity[]" data-reference="<?php echo $deno->reference1; ?>"   value="<?php echo $deno->quantity; ?>">
											</div>
										</div>
										<?php
                                            }
                                                }

                                            }
                                        ?>


							</div>

						</div>
					</div>
					<div class="pv2-card" id="dropdown">
						<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-bubble"></i></span> Comentario</p>

							<div class="form-group">
								<label class="pv2-label" for="txtNote">Descripción</label>
								<div>
									<textarea class="form-control"  id="txtNote" name="txtNote" rows="6"><?php echo $objTransactionMaster->note; ?></textarea>
								</div>
							</div>

					</div>

			</form>
			<!-- /body -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Ingreso a caja</p>
			<p class="pv2-mbar-total"><?php echo $objTransactionMaster->transactionNumber; ?></p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
