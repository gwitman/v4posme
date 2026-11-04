					<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-folder-3"></i></div>
			<div>
				<p class="pv2-header-title">PROVISION:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Registra una nueva provisión</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_box_provisioned/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
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
													
														<div class="form-group">
															<label class="pv2-label" for="txtDate">Fecha</label>
															<div>
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16"  class="form-control" autocomplete="off" type="text" name="txtDate" id="txtDate" >
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														
														<div class="form-group">
																<label class="pv2-label" for="txtIsApplied">Aplicado</label>
																<div>
																	<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" >
																</div>
														</div>
														
														<div class="form-group">
																<label class="pv2-label" for="txtExchangeRate">Cambio</label>
																<div>
																	<input class="form-control" autocomplete="off" type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
																</div>
														</div>
														
													
												</div>
												<div class="col-sm-6">
												
														
														<div class="form-group">
															<label class="pv2-label" for="txtStatusID">Estado</label>
															<div>
																<select name="txtStatusID" id="txtStatusID" class="select2">
																		<option></option>																
																		<?php
																		if($objListWorkflowStage)
																		foreach($objListWorkflowStage as $ws){
																			echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														
														<div class="pv2-kpis">
															<div class="pv2-kpi k-ok">
																<div class="pv2-kpi-icon"><i class="i-download-2"></i></div>
																<div class="pv2-kpi-body"><span class="pv2-kpi-label">C$ compra</span><span class="pv2-kpi-value"><?php echo number_format((float)$exchangeRatePurchase,2); ?></span></div>
															</div>
															<div class="pv2-kpi k-warn">
																<div class="pv2-kpi-icon"><i class="i-search-3"></i></div>
																<div class="pv2-kpi-body"><span class="pv2-kpi-label">C$ venta</span><span class="pv2-kpi-value"><?php echo number_format((float)$exchangeRateSale,2); ?></span></div>
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
											<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-link"></i></span> Referencias</p>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
															<label class="pv2-label" for="txtReferenceClientName">Persona Ref.</label>
															<div>
																<input class="form-control" autocomplete="off" type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="">
															</div>
													</div>
													
													<div class="form-group">
															<label class="pv2-label" for="txtReferenceClientIdentifier">ID Persona Ref.</label>
															<div>
																<input class="form-control" autocomplete="off" type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="">
															</div>
													</div>
														
																							
												</div>
												<div class="col-sm-6">
													
													<div class="form-group">
															<label class="pv2-label" for="txtReference">Referencia</label>
															<div>
																<input class="form-control" autocomplete="off" type="text"  name="txtReference" id="txtReference" value="">												
															</div>
													</div>			
												</div>
											</div>
										</div>
										<div class="pv2-card" id="dropdown">
											<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-bubble"></i></span> Comentario</p>

												<div class="form-group">
		                                            <label class="pv2-label" for="txtNote">Descripción</label>
		                                            <div>
		                                                <textarea class="form-control"  id="txtNote" name="txtNote" rows="6"></textarea>
		                                            </div>
		                                        </div>

										</div>

									<div class="row">
										<div class="col-md-8 col-lg-9">
											<!-- detalle -->
											<div class="pv2-card">
												<div class="pv2-detail-head">
													<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Detalle <span class="pv2-count" id="pv2DetailCount">0</span></p>
													<div class="pv2-actions" style="width:auto">
														<a href="#" class="btn btn-default btn-sm hidden-xs" id="btnDeleteShare"><i class="icon16 i-remove"></i> Quitar marcados</a>
														<a href="#" class="btn btn-primary btn-sm" id="btnNewShare"><i class="icon16 i-plus"></i> Agregar documento</a>
													</div>
												</div>

												<div class="pv2-table-wrap" id="pv2DetailWrap">
													<table id="tb_transaction_master_detail" class="pv2-table">
														<thead>
															<tr>
																<th class="pv2-col-check"></th>
																<th>Documento</th>
																<th>Moneda</th>
																<th class="text-right pv2-col-share">Provisión</th>
																<th class="pv2-col-del"></th>
															</tr>
														</thead>
														<tbody id="body_tb_transaction_master_detail">
														</tbody>
													</table>
												</div>

												<div class="pv2-empty" id="pv2EmptyDetail" style="display:none">
													<i class="icon20 i-file-plus"></i>
													Toca <b>Agregar documento</b> para seleccionar el documento a provisionar.
												</div>
											</div>
											<!-- /detalle -->
										</div>

										<div class="col-md-4 col-lg-3">
											<!-- pago -->
											<div class="pv2-card pv2-sticky">
												<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Pago</p>
												<div class="form-group">
													<label class="pv2-label" for="txtReceiptAmount">Ingreso</label>
													<input type="text" id="txtReceiptAmount" name="txtReceiptAmount" class="form-control pv2-num txt-numeric" autocomplete="off" inputmode="decimal" value="0.00"/>
												</div>
												<div class="pv2-change">
													<span>Cambio</span>
													<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly value=""/>
												</div>
												<div class="pv2-total">
													<label class="pv2-label" for="txtTotal">Total</label>
													<input type="text" id="txtTotal" name="txtTotal" readonly value=""/>
												</div>
											</div>
											<!-- /pago -->
										</div>
									</div>

								</form>
								<!-- /body -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Total &middot; cambio <span class="pv2-mbar-change" id="pv2MbarChange">0.00</span></p>
			<p class="pv2-mbar-total" id="pv2MbarTotal">0.00</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>

					<script type="text/template"  id="tmpl_row_document">
							<tr class="row_razon">
								<td class="pv2-col-check">
									<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
									<input type="hidden" name="txtDetailCustomerCreditDocumentID[]" id="txtDetailCustomerCreditDocumentID"  class="classDetailItem" />
									<input type="hidden" name="txtDetailTransactionMaster[]" />
								</td>
								<td class="pv2-doc" data-label="Documento"><span id="txtDocument"></span></td>
								<td data-label="Moneda"><span id="txtNameMoneda"></span></td>
								<td class="pv2-col-share" data-label="Provisión">
									<input class="form-control pv2-num txtBalance txt-numeric" autocomplete="off" inputmode="decimal" type="text" id="txtBalance"  name="txtBalance[]"  value="" />
								</td>
								<td class="pv2-col-del"><a href="#" class="btn btn-link pv2-btn-del btnShareRemoveRow" title="Quitar documento"><i class="icon16 i-remove"></i></a></td>
							</tr>
					</script>