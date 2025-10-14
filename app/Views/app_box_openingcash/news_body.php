					<div class="row">
                        <div id="email" class="col-lg-12">

                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">
                                <div class="btn-group pull-right">
									<a href="<?php echo base_url(); ?>/app_box_openingcash/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
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
										<h4>INGRESO:#<span class="invoice-num">00000000</span></h4>
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
																	<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" >
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>

														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Aplicado</label>
																<div class="col-lg-5">
																	<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" >
																</div>
														</div>
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Cambio</label>
																<div class="col-lg-8">
																	<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
																</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Estado</label>
															<div class="col-lg-8">
																<select name="txtStatusID" id="txtStatusID" class="select2">
																		<option></option>
																		<?php
                                                                            if ($objListWorkflowStage) {
                                                                                foreach ($objListWorkflowStage as $ws) {
                                                                                    echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                                                                }
                                                                            }

                                                                        ?>
																</select>
															</div>
														</div>

														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Referencia 1</label>
																<div class="col-lg-8">
																	<input class="form-control"  type="text"  name="txtDetailReference1" id="txtDetailReference1" value="">
																</div>
														</div>


														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Referencia 2</label>
																<div class="col-lg-8">

																	<input class="form-control"  type="text"  name="txtDetailReference2" id="txtDetailReference2" value="">
																</div>
														</div>

														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Referencia 3</label>
																<div class="col-lg-8">
																	<input class="form-control"  type="text"  name="txtDetailReference3" id="txtDetailReference3" value="">
																</div>
														</div>


												</div>
												<div class="col-lg-6">

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Sucursal</label>
															<div class="col-lg-8">
																<select name="txtBranchID" id="txtBranchID"  class="select2">
																		<?php
                                                                            foreach ($objListBranch as $i) {
                                                                                echo "<option value='" . $i->branchID . "' selected >" . $i->name . "</option>";
                                                                            }
                                                                        ?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-8">
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">																		>
																		<?php
                                                                            if ($objListCurrency) {
                                                                                foreach ($objListCurrency as $ws) {
                                                                                    echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
                                                                                }
                                                                            }

                                                                        ?>
																</select>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="txtBoxID">Caja</label>
															<div class="col-lg-8">
																<select name="txtBoxID" id="txtBoxID" class="select2">																		>
																	<?php
                                                                        if (isset($objListCashBox)) {
                                                                            $i = 0;
                                                                            foreach ($objListCashBox as $cb) {
                                                                                if ($i == 0) {
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
															<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
															<div class="col-lg-8">
																<select name="txtAreaID" id="txtAreaID" class="select2">
																		<?php
                                                                            $counter = 0;
                                                                            if ($objTipoMovement) {
                                                                                foreach ($objTipoMovement as $ws) {
                                                                                    if ($counter == 0) {
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
															<label class="col-lg-4 control-label" for="selectFilter">Categoria</label>
															<div class="col-lg-8">
																<select name="txtPriorityID" id="txtPriorityID" class="select2">
																		<?php
                                                                            $counter = 0;
                                                                            if ($objSubTipoMovement) {
                                                                                foreach ($objSubTipoMovement as $ws) {
                                                                                    if ($counter == 0) {
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
																<label class="col-lg-4 control-label" for="normal">Monto</label>
																<div class="col-lg-8">
																	<input type="hidden" name="txtDetailTransactionDetailID" value="0">
																	<input class="form-control"  type="text"  name="txtDetailAmount" id="txtDetailAmount" value="0">
																</div>
														</div>



														<div class="vital-stats">
															<ul>

																<li>
																	<a href="#">
																		<div class="item">
																			<div class="icon green"><i class="i-download-2"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f", $exchangeRatePurchase); ?></span>
																			<span class="txt">C$ compra</span>
																		</div>
																	</a>
																</li>
																<li>
																	<a href="#">
																		<div class="item">
																			<div class="icon yellow"><i class="i-search-3"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f", $exchangeRateSale); ?></span>
																			<span class="txt">C$ vent</span>
																		</div>
																	</a>
																</li>
																<li>
																	<a href="#">
																		<div class="item">
																			<div class="icon orange"><i class="i-temperature"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f", $exchangeRate); ?></span>
																			<span class="txt">C$</span>
																		</div>
																	</a>
																</li>

															</ul>
														</div><!-- End .vital-stats -->
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile">
											<div class="row">
												<div class="col-lg-6 currency-1">

													<?php
                                                        if ($objListDenomination) {
                                                            foreach ($objListDenomination as $deno) {

                                                                if ($deno->ratio == 1) {
                                                                ?>
															<div class="form-group">
																<label class="col-lg-4 control-label" for="normal"><?php echo $deno->name; ?></label>
																<div class="col-lg-8">
																	<input type="hidden" name="txtTransactionMasterDenominationID[]" value="0">
																	<input type="hidden" name="txtTransactionMasterDenominationCatalogItemID[]" value="<?php echo $deno->catalogItemID; ?>">
																	<input type="hidden" name="txtTransactionMasterDenominationCurrencyID[]" value="<?php echo $deno->ratio; ?>">
																	<input type="hidden" name="txtTransactionMasterDenominationExchangeRate[]" value="<?php echo $exchangeRate; ?>">
																	<input type="hidden" name="txtTransactionMasterDenominationReference[]" value="<?php echo $deno->reference1; ?>">
																	<input class="form-control denomination-quantity"  type="text"  name="txtTransactionMasterDenominationQuantity[]" data-reference="<?php echo $deno->reference1; ?>"  value="0">
																</div>
															</div>
															<?php
                                                                }
                                                                    }

                                                                }
                                                            ?>


												</div>
												<div class="col-lg-6 currency-2">
													<?php
                                                        if ($objListDenomination) {
                                                            foreach ($objListDenomination as $deno) {

                                                                if ($deno->ratio == 2) {
                                                                ?>
															<div class="form-group">
																<label class="col-lg-4 control-label" for="normal"><?php echo $deno->name; ?></label>
																<div class="col-lg-8">
																	<input type="hidden" name="txtTransactionMasterDenominationID[]" value="0">
																	<input type="hidden" name="txtTransactionMasterDenominationCatalogItemID[]" value="<?php echo $deno->catalogItemID; ?>">
																	<input type="hidden" name="txtTransactionMasterDenominationCurrencyID[]" value="<?php echo $deno->ratio; ?>">
																	<input type="hidden" name="txtTransactionMasterDenominationExchangeRate[]" value="<?php echo $exchangeRate; ?>">
																	<input type="hidden" name="txtTransactionMasterDenominationReference[]" value="<?php echo $deno->reference1; ?>">
																	<input class="form-control denomination-quantity"  type="text"  name="txtTransactionMasterDenominationQuantity[]" data-reference="<?php echo $deno->reference1; ?>" value="0">
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
										<div class="tab-pane fade" id="dropdown">

												<div class="form-group">
		                                            <label class="col-lg-2 control-label" for="normal">Descripcion</label>
		                                            <div class="col-lg-6">
		                                                <textarea class="form-control"  id="txtNote" name="txtNote" rows="6"></textarea>
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


