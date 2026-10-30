<div class="abv2">
<form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">

	<input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
	<input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
	<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">

	<!-- encabezado y acciones -->
	<div class="abv2-card abv2-header">
		<div class="abv2-header-info">
			<div class="abv2-header-icon"><i class="icon20 i-credit"></i></div>
			<div>
				<p class="abv2-header-num">
					Abono #<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span>
					<span class="abv2-chip <?php echo ($objWorkflowStage[0]->aplicable == 1 ? "is-applied" : ""); ?>"><?php echo htmlspecialchars($objWorkflowStage[0]->name ?? ""); ?></span>
				</p>
				<p class="abv2-header-sub"><?php echo $objTransactionMaster->transactionOn; ?></p>
			</div>
		</div>
		<div class="abv2-actions">
			<a href="<?php echo base_url(); ?>/app_cxc_customer/index" class="btn btn-default <?php echo getBehavio($company->type,"app_box_share","showBtnIrCustomerOfShare","hidden"); ?>" id="btnBackCustomer"><i class="icon16 i-users"></i> Ir a clientes</a>
			<a href="<?php echo base_url(); ?>/app_box_share/index" class="btn btn-default" id="btnBack"><i class="icon16 i-arrow-left"></i> Atras</a>
			<a href="<?php echo base_url(); ?>/app_box_share/add" class="btn btn-default" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
			<?php
				if($objWorkflowStage[0]->aplicable == 1)
				{
					echo '<a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>';
					echo '<a href="#" class="btn btn-default '.getBehavio($company->type,"app_box_share","showBtnPrinterInvoiceCancel","hidden").'" id="btnPrinterInvoiceCancel"><i class="icon16 i-print"></i> Imp. fac. canceladas</a>';
				}
			?>
			<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
			<a href="#" class="btn btn-primary abv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

	<div class="row">
		<div class="col-md-8 col-lg-9">
			<div class="row">

				<!-- documento -->
				<div class="col-sm-6">
					<div class="abv2-card">
						<p class="abv2-card-title"><i class="icon16 i-file"></i> Documento</p>
						<div class="row">
							<div class="col-xs-12 col-sm-6 form-group <?php echo  $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divFecha",""); ?> ">
								<label class="abv2-label" for="txtDate">Fecha</label>
								<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
									<input size="16" class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>" >
									<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
								</div>
							</div>

							<div class="col-xs-12 col-sm-6 form-group">
								<label class="abv2-label" for="txtStatusID">Estado</label>
								<select name="txtStatusID" id="txtStatusID" class="form-control <?php echo ( $useMobile == "1" ? "" : "select2"); ?>">
										<option></option>
										<?php
										if($objListWorkflowStage)
										foreach($objListWorkflowStage as $ws){
											if($ws->workflowStageID == $objTransactionMaster->statusID)
												echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
											else
												echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
										}
										?>
								</select>
							</div>

							<div class="col-xs-12 col-sm-6 form-group <?php echo getBehavio($company->type,"app_box_share","divMoneda",""); ?> ">
								<label class="abv2-label" for="txtCurrencyID">Moneda</label>
								<select name="txtCurrencyID" id="txtCurrencyID" class="form-control <?php echo ( $useMobile == "1" ? "" : "select2"); ?>">
										<option></option>
										<?php
										if($objListCurrency)
										foreach($objListCurrency as $ws)
										{
											if( $ws->currencyID == $objTransactionMaster->currencyID )
												echo "<option value='".$ws->currencyID."' selected>".$ws->name."</option>";
											else
												echo "<option value='".$ws->currencyID."' >".$ws->name."</option>";
										}
										?>
								</select>
							</div>

							<div class="col-xs-12 col-sm-6 form-group si_ferreteria_mateo <?php echo $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divCambio",""); ?> ">
								<label class="abv2-label" for="txtExchangeRate">Tipo de cambio</label>
								<input class="form-control abv2-num" type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
							</div>

							<div class="col-xs-12 form-group si_ferreteria_mateo <?php echo $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divAplicado",""); ?> ">
								<label class="abv2-label" for="txtIsApplied">Aplicado</label>
								<input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1" <?php if($objTransactionMaster->isApplied) echo "checked"; ?> >
							</div>
						</div>
					</div>
				</div>
				<!-- /documento -->

				<!-- cliente -->
				<div class="col-sm-6">
					<div class="abv2-card">
						<p class="abv2-card-title"><i class="icon16 i-user"></i> <?php echo getBehavio($company->type,"app_box_share","lblCliente","Cliente"); ?></p>

						<div class="form-group <?php echo getBehavio($company->type,"app_box_share","divCustomerControlSelected",""); ?> ">
							<label class="abv2-label" for="txtMobileEntityID">Seleccionar de la lista</label>
							<select name="txtMobileEntityID" id="txtMobileEntityID" class="form-control">
									<option selected >Seleccionar</option>
									<?php
									if($objListCustomer)
									foreach($objListCustomer as $ws){
										if(  $objTransactionMaster->entityID == $ws->entityID   )
											echo "<option value='".$ws->entityID."' data-name='".$ws->firstName." ".$ws->lastName."' selected >".$ws->firstName." ".$ws->lastName."</option>";
										else
											echo "<option value='".$ws->entityID."' data-name='".$ws->firstName." ".$ws->lastName."' >".$ws->firstName." ".$ws->lastName."</option>";
									}
									?>
							</select>
						</div>

						<div class="form-group <?php echo getBehavio($company->type,"app_box_share","divCustomerControlBuscar",""); ?> ">
							<div class="input-group">
								<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objTransactionMaster->entityID;  ?>">
								<input class="form-control" readonly id="txtCustomerDescription" type="text" placeholder="Busca un cliente" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="btnSearchCustomer" title="Buscar cliente">
										<i aria-hidden="true" class="i-search-5"></i> Buscar
									</button>
								</span>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btnClearCustomer" title="Limpiar cliente">
										<i aria-hidden="true" class="i-undo-2"></i>
									</button>
								</span>
							</div>
						</div>

						<div class="form-group si_ferreteria_mateo <?php echo getBehavio($company->type,"app_box_share","divCobrador",""); ?> ">
							<label class="abv2-label" for="txtEmployeeDescription">Cobrador</label>
							<div class="input-group">
								<input type="hidden" id="txtEmployeeID" name="txtEmployeeID" value="<?php echo $objTransactionMaster->reference3;  ?>">
								<input class="form-control" readonly id="txtEmployeeDescription" type="text" placeholder="Busca un cobrador" value="<?php echo $objEmployeeDefault != null ? strtoupper($objEmployeeDefault->employeNumber . " / ". $objEmployeeNaturalDefault->firstName ) : ""; ?>">
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="btnSearchEmployee" title="Buscar cobrador">
										<i aria-hidden="true" class="i-search-5"></i> Buscar
									</button>
								</span>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btnClearEmployee" title="Limpiar cobrador">
										<i aria-hidden="true" class="i-undo-2"></i>
									</button>
								</span>
							</div>
						</div>

						<div class="abv2-stats">
							<div class="abv2-stat">
								<label class="abv2-label" for="txtBalanceStart">Saldo inicial</label>
								<input class="form-control abv2-stat-value" type="text" readonly name="txtBalanceStart" id="txtBalanceStart" value="<?php echo number_format($objTransactionMasterInfo->reference1,2); ?>">
							</div>
							<div class="abv2-stat is-final">
								<label class="abv2-label" for="txtBalanceFinish">Saldo final</label>
								<input class="form-control abv2-stat-value" type="text" readonly name="txtBalanceFinish" id="txtBalanceFinish" value="<?php echo number_format($objTransactionMasterInfo->reference2,2); ?>">
							</div>
						</div>

						<div class="abv2-rates <?php echo $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divStart",""); ?> ">
							<span class="abv2-rate si_ferreteria_mateo">C$ <b><?php echo sprintf("%01.2f",$exchangeRate); ?></b></span>
						</div>
					</div>
				</div>
				<!-- /cliente -->

			</div>

			<!-- facturas -->
			<div class="abv2-card">
				<div class="abv2-detail-head">
					<p class="abv2-card-title"><i class="icon16 i-list-2"></i> Facturas a abonar <span class="abv2-count" id="abv2DetailCount">0</span></p>
					<div class="abv2-actions" style="width:auto">
						<a href="#" class="btn btn-default btn-sm <?php echo getBehavio($company->type,"app_box_share","btnVerMovimientos","hidden"); ?> " id="btnVerMovement"><i class="icon16 i-print"></i> Ver movimientos</a>
						<a href="#" class="btn btn-default btn-sm hidden-xs" id="btnDeleteShare"><i class="icon16 i-remove"></i> Quitar marcadas</a>
						<a href="#" class="btn btn-primary btn-sm" id="btnNewShare"><i class="icon16 i-plus"></i> Agregar factura</a>
					</div>
				</div>

				<table id="tb_transaction_master_detail" class="abv2-table">
					<thead>
						<tr>
							<th class="abv2-col-check"></th>
							<th class="<?php echo getBehavio($company->type,"app_box_share","TableColumnDocumento",""); ?>">Documento</th>
							<th class="text-right">Saldo inicial</th>
							<th class="text-right abv2-col-share">Abonar</th>
							<th class="text-right">Saldo final</th>
							<th class="abv2-col-del"></th>
						</tr>
					</thead>
					<tbody id="body_tb_transaction_master_detail">
						<?php
							if($objTransactionMasterDetail)
							foreach($objTransactionMasterDetail as $key => $value)
							{
							?>
							<tr class="row_razon">
								<td class="abv2-col-check">
									<input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
									<input type="hidden" name="txtDetailCustomerCreditDocumentID[]" id="txtDetailCustomerCreditDocumentID" class="classDetailItem" value="<?php echo $value->componentItemID; ?>"/>
									<input type="hidden" name="txtDetailTransactionDetailID[]" id="txtDetailTransactionDetailID" value="<?php echo $value->transactionMasterDetailID; ?>"/>
									<input type="hidden" name="txtDetailTransactionDetailDocument[]" id="txtDetailTransactionDetailDocument" value="<?php echo $value->reference1; ?>" />
									<input type="hidden" name="txtDetailTransactionDetailFecha[]" id="txtDetailTransactionDetailFecha" />
									<input type="hidden" name="txtDetailAmortizationID[]" id="txtDetailAmortizationID" value="<?php echo $value->reference3; ?>"/>
									<input type="hidden" name="txtDetailBalanceStart[]" id="txtDetailBalanceStart" value="<?php echo $value->reference2; ?>"/>
									<input type="hidden" name="txtDetailBalanceFinish[]" id="txtDetailBalanceFinish" value="<?php echo $value->reference4; ?>"/>
								</td>
								<td class="abv2-doc <?php echo getBehavio($company->type,"app_box_share","TableColumnDocumento",""); ?>" data-label="Factura"><span id="txtDocument"><?php echo $value->reference1; ?></span></td>
								<td class="text-right" data-label="Saldo inicial"><span id="txtBalanceStartShare" class="txtDetailShareReference2"><?php echo number_format($value->reference2,2); ?></span></td>
								<td class="abv2-col-share" data-label="Abonar"><input class="form-control abv2-num txtDetailShare txt-numeric" type="text" inputmode="decimal" id="txtDetailShare" name="txtDetailShare[]" value="<?php echo number_format($value->amount,2); ?>" /></td>
								<td class="text-right" data-label="Saldo final"><span id="txtBalanceFinishShare"><?php echo number_format($value->reference4,2); ?></span></td>
								<td class="abv2-col-del"><a href="#" class="btn btn-link abv2-btn-del btnShareRemoveRow" title="Quitar factura"><i class="icon16 i-remove"></i></a></td>
							</tr>
							<?php
							}
						?>
					</tbody>
				</table>

				<div class="abv2-empty" id="abv2EmptyDetail" style="display:none">
					<i class="icon20 i-file-plus"></i>
					Este abono no tiene facturas. Toca <b>Agregar factura</b>.
				</div>
			</div>
			<!-- /facturas -->

		</div>

		<!-- pago -->
		<div class="col-md-4 col-lg-3">
			<div class="abv2-card abv2-pay abv2-sticky <?php echo getBehavio($company->type,"app_box_share","divResumenAbono",""); ?> ">
				<p class="abv2-card-title"><i class="icon16 i-coins"></i> Pago</p>
				<div class="form-group">
					<label class="abv2-label" for="txtReceiptAmount">Recibido NAC</label>
					<input type="text" id="txtReceiptAmount" name="txtReceiptAmount" class="form-control abv2-num txt-numeric" inputmode="decimal" value="<?php echo number_format($objTransactionMasterInfo->receiptAmount ,2); ?>"/>
				</div>
				<div class="form-group">
					<label class="abv2-label" for="txtReceiptAmountDol">Recibido EXT</label>
					<input type="text" id="txtReceiptAmountDol" name="txtReceiptAmountDol" class="form-control abv2-num txt-numeric" inputmode="decimal" value="<?php echo number_format($objTransactionMasterInfo->receiptAmountDol ,2); ?>"/>
				</div>
				<div class="form-group">
					<label class="abv2-label" for="txtDiscountAmount">Descuento</label>
					<input type="text" id="txtDiscountAmount" name="txtDiscountAmount" class="form-control abv2-num txt-numeric" inputmode="decimal" value="<?php echo number_format($objTransactionMaster->discount ?? 0,2); ?>"/>
				</div>
				<div class="abv2-change">
					<span>Cambio</span>
					<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly value="<?php echo number_format($objTransactionMasterInfo->changeAmount,2); ?>"/>
				</div>
				<div class="abv2-total">
					<label class="abv2-label" for="txtTotal">Total del abono</label>
					<input type="text" id="txtTotal" name="txtTotal" readonly value="<?php echo number_format($objTransactionMaster->amount,2); ?>"/>
				</div>
			</div>
		</div>
		<!-- /pago -->
	</div>

		<!-- informacion adicional -->
		<div class="abv2-card">
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#profile" data-toggle="tab">Referencias</a></li>
				<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
				<li id="btnClickArchivo"><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="profile">
					<div class="row">
						<div class="col-sm-6 col-lg-3 form-group">
							<label class="abv2-label" for="txtReferenceClientName">Cliente ref.</label>
							<input class="form-control" type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="<?php echo $objTransactionMasterInfo->referenceClientName; ?>">
						</div>
						<div class="col-sm-6 col-lg-3 form-group">
							<label class="abv2-label" for="txtReferenceClientIdentifier">ID cliente ref.</label>
							<input class="form-control" type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="<?php echo $objTransactionMasterInfo->referenceClientIdentifier; ?>">
						</div>
						<div class="col-sm-6 col-lg-3 form-group">
							<label class="abv2-label" for="txtReference1"><?php echo getBehavio($company->type,"app_box_share","labelReference1","Referencia 1"); ?></label>
							<input class="form-control" type="text" name="txtReference1" id="txtReference1" value="<?php echo $objTransactionMaster->reference1; ?>">
						</div>
						<div class="col-sm-6 col-lg-3 form-group">
							<label class="abv2-label" for="txtReference2">Referencia 2</label>
							<input class="form-control" type="text" name="txtReference2" id="txtReference2" value="<?php echo $objTransactionMaster->reference2; ?>">
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="dropdown">
					<div class="form-group">
						<label class="abv2-label" for="txtNote">Descripcion</label>
						<textarea class="form-control" id="txtNote" name="txtNote" rows="4"><?php echo $objTransactionMaster->note; ?></textarea>
					</div>
				</div>
				<div class="tab-pane fade" id="dropdown-file">
					<p class="text-muted" style="margin:0">Los archivos se abren en una ventana nueva.</p>
				</div>
			</div>
		</div>
		<!-- /informacion adicional -->

</form>

	<!-- barra inferior (telefono) -->
	<div class="abv2-mbar">
		<div class="abv2-mbar-info">
			<p class="abv2-mbar-label">Total del abono &middot; cambio <span class="abv2-mbar-change" id="abv2MbarChange">0.00</span></p>
			<p class="abv2-mbar-total" id="abv2MbarTotal">0.00</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>

	<div class="modal fade" tabindex="-1" id="modalDialogPrinterV2" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Imprimir documento</h4>
		  </div>
		  <div class="modal-body">
			<p style="margin:0">Elige el formato de impresion.</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<a href="#" class="btn btn-primary hidden" id="btnPrinterIndividual">Individual</a>
			<a href="#" class="btn btn-primary" id="btnPrinterGeneral">General</a>
			<a href="#" class="btn btn-primary" id="btnPrinterBasico">Basico</a>
		  </div>
		</div>
	  </div>
	</div>
</div>


<script type="text/template" id="tmpl_row_document">
	<tr class="row_razon">
		<td class="abv2-col-check">
			<input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
			<input type="hidden" name="txtDetailCustomerCreditDocumentID[]" id="txtDetailCustomerCreditDocumentID" class="classDetailItem" />
			<input type="hidden" name="txtDetailTransactionDetailID[]" id="txtDetailTransactionDetailID" />
			<input type="hidden" name="txtDetailTransactionDetailDocument[]" id="txtDetailTransactionDetailDocument" />
			<input type="hidden" name="txtDetailTransactionDetailFecha[]" id="txtDetailTransactionDetailFecha" />
			<input type="hidden" name="txtDetailAmortizationID[]" id="txtDetailAmortizationID" />
			<input type="hidden" name="txtDetailBalanceStart[]" id="txtDetailBalanceStart" />
			<input type="hidden" name="txtDetailBalanceFinish[]" id="txtDetailBalanceFinish" />
		</td>
		<td class="abv2-doc <?php echo getBehavio($company->type,"app_box_share","TableColumnDocumento",""); ?>" data-label="Factura"><span id="txtDocument"></span></td>
		<td class="text-right" data-label="Saldo inicial"><span id="txtBalanceStartShare" class="txtDetailShareReference2"></span></td>
		<td class="abv2-col-share" data-label="Abonar"><input class="form-control abv2-num txtDetailShare txt-numeric" type="text" inputmode="decimal" id="txtDetailShare" name="txtDetailShare[]" value="" /></td>
		<td class="text-right" data-label="Saldo final"><span id="txtBalanceFinishShare">0.00</span></td>
		<td class="abv2-col-del"><a href="#" class="btn btn-link abv2-btn-del btnShareRemoveRow" title="Quitar factura"><i class="icon16 i-remove"></i></a></td>
	</tr>
</script>
