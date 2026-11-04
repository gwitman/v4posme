<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-clock-5"></i></div>
            <div>
                <p class="pv2-header-title">ADELANTO:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
                <p class="pv2-header-sub">Registra los adelantos de salario de los colaboradores</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_planilla_adelantos/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atrás</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

								<!-- formulario -->
								<form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">
								<div class="printArea">
											<div class="row" id="home">
												<div class="col-xs-12 col-md-6">
												  <div class="pv2-card">
													<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del adelanto</p>
														<div class="form-group">
															<label class="pv2-label">Ciclo</label>
															<div>
																<select name="txtCycleID" id="txtCycleID" class="select2">							
																		<?php
																		if($objListCycle)
																		foreach($objListCycle as $item){
																			echo "<option value='".$item->componentCycleID."' >".$item->startOn."</option>";
																		}
																		?>
																		
																</select>
															</div>
														</div>
													
														<div class="form-group">
															<label class="pv2-label">Estado</label>
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
														
													
												  </div>
												</div>
												<div class="col-xs-12 col-md-6">
												  <div class="pv2-card">
													<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Clasificación</p>
												
														<div class="form-group">
															<label class="pv2-label">Tipo</label>
															<div>
																<select name="txtTypeID" id="txtTypeID" class="select2">
																		<option></option>																
																		<?php
																		if($objListType)
																		foreach($objListType as $ws){
																			echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														<div class="form-group">
															<label class="pv2-label">Moneda</label>
															<div>
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																		<option></option>																
																		<?php
																		if($objListCurrency)
																		foreach($objListCurrency as $ws){
																			echo "<option value='".$ws->currencyID."' selected>".$ws->simbol."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														
												  </div>
												</div>
											</div>
										
											<div class="pv2-card" id="dropdown">
												<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Comentario</p>
											
												<div class="form-group">
		                                            <label class="pv2-label" for="txtNote">Descripción</label>
		                                            <div>
		                                                <textarea class="form-control"  id="txtNote" name="txtNote" rows="4"></textarea>
		                                            </div>
		                                        </div>
											
										</div>
											
							
									<!-- detalle -->
									<div class="pv2-card">
										<div class="pv2-detail-head">
											<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Colaboradores <span class="pv2-count" id="afxDetailCount">0</span></p>
											<div class="pv2-actions">
												<button type="button" class="btn btn-default btn-sm" id="btnDeleteShare"><i class="icon16 i-remove"></i> Quitar marcados</button>
												<button type="button" class="btn btn-primary btn-sm" id="btnNewShare"><i class="icon16 i-plus"></i> Agregar colaborador</button>
											</div>
										</div>
										<div id="div_transaction_master_detail" class="pv2-table-wrap">
											<table id="tb_transaction_master_detail" class="pv2-table">
												<thead>
													<tr>
														<th class="afx-col-quitar"></th>
														<th>Colaborador</th>
														<th>Monto</th>
													</tr>
												</thead>
												<tbody id="body_tb_transaction_master_detail">
												</tbody>
											</table>
										</div>
										<div class="pv2-empty" id="afxDetailEmpty">
											<i class="icon20 i-file-plus"></i>
											Este adelanto no tiene colaboradores. Toca <b>Agregar colaborador</b> para empezar.
										</div>
									</div>
									<!-- /detalle -->
                                            
									<!-- resumen -->
									<div class="pv2-card">
										<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-checkmark-4"></i></span> Resumen</p>
										<div class="pv2-total">
											<label class="pv2-label" for="txtTotal">Total</label>
											<input type="text" id="txtTotal" name="txtTotal" readonly class="afx-monto" autocomplete="off" value="">
										</div>
									</div>
									<!-- /resumen -->
                                       
                                </div>
								</form>
								<!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nuevo adelanto</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
					
					
					<script type="text/template"  id="tmpl_row_document">
							<tr class="row_razon">
								<td class="td-checkbox" data-label="Quitar">
									<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
									<input type="hidden"  name="txtTransactionMasterDetailID[]" id="txtTransactionMasterDetailID"  />
									<input type="hidden" class="classDetailItem" id="txtEmployeeID" name="txtEmployeeID[]" />
								</td>
								<td data-label="Colaborador"><span id="txtDocument"></span></td>
								<td data-label="Monto">
									<input class="form-control pv2-num txtDetailShare afx-monto" inputmode="decimal" autocomplete="off" type="text" id="txtNeto"  name="txtNeto[]"  value="0" />
								</td>
							</tr>
					</script>