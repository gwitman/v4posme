<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo base_url(); ?>/app_box_share/index" id="btnBack" class="btn btn-warning" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
										<h4>:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form" method="post">
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
												<div class="col-lg-6  ">
													
														<div class="form-group <?php echo $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divFecha",""); ?> ">
															<label class="col-lg-2 control-label" for="datepicker">Fecha</label>
															<div class="col-lg-8">
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" >
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														
														<div class="form-group si_ferreteria_mateo <?php echo $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divAplicado",""); ?> ">
																<label class="col-lg-2 control-label" for="normal">Aplicado</label>
																<div class="col-lg-5">
																	<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" >
																</div>
														</div>
														<div class="form-group si_ferreteria_mateo <?php echo $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divCambio",""); ?>  ">
																<label class="col-lg-2 control-label" for="normal">Cambio</label>
																<div class="col-lg-8">
																	<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
																</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-2 control-label" for="selectFilter">Estado</label>
															<div class="col-lg-8">
																<select name="txtStatusID" id="txtStatusID" class="<?php echo ( $useMobile == "1" ? "" : "select2"); ?>">
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
														
														<div class="form-group <?php echo getBehavio($company->type,"app_box_share","divMoneda",""); ?>  ">
															<label class="col-lg-2 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-8">
																<select name="txtCurrencyID" id="txtCurrencyID" class="<?php echo ( $useMobile == "1" ? "" : "select2"); ?>">
																		<option></option>																
																		<?php
																		if($objListCurrency)
																		foreach($objListCurrency as $ws){
																			
																			if( $ws->currencyID == $objListCurrencyDefault->currencyID )
																				echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
																			else 
																				echo "<option value='".$ws->currencyID."' >".$ws->name."</option>";
																			
																		}
																		?>
																</select>
															</div>
														</div>
														
													
												</div>
												<div class="col-lg-6">
												
														<div class="form-group <?php echo getBehavio($company->type,"app_box_share","divCustomerControlSelected",""); ?> ">
															<label class="col-lg-4 control-label" for="selectFilter"><?php echo getBehavio($company->type,"app_box_share","lblCliente","Cliente"); ?></label>
															<div class="col-lg-8">
																<select name="txtMobileEntityID" id="txtMobileEntityID">
																		<option selected >Seleccionar</option>
																		<?php
																		if($objListCustomer)
																		foreach($objListCustomer as $ws){
																			echo "<option value='".$ws->entityID."' data-name='".$ws->firstName." ".$ws->lastName."' >".$ws->firstName." ".$ws->lastName."</option>";
																		}
																		?>
																</select>
															</div>
														</div>
														
														
														
														<div class="form-group <?php echo getBehavio($company->type,"app_box_share","divCustomerControlBuscar",""); ?> ">
															<label class="col-lg-4 control-label" for="buttons"><?php echo getBehavio($company->type,"app_box_share","lblCliente","Cliente"); ?></label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="">
																	<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="">
																	
																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearCustomer">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchCustomer">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>
																	
																</div>
															</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Saldo inicial</label>
																<div class="col-lg-8">
																	<input class="form-control"   type="text"  readonly="tre" name="txtBalanceStart" id="txtBalanceStart" value="0">
																</div>
														</div>
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Saldo final</label>
																<div class="col-lg-8">
																	<input class="form-control"   type="text"  readonly="tre" name="txtBalanceFinish" id="txtBalanceFinish" value="0">
																</div>
														</div>

														<div class="vital-stats <?php echo $useMobile == "1" ? "hidden" : getBehavio($company->type,"app_box_share","divStart",""); ?> ">
															<ul>
																
																<li class="si_ferreteria_mateo">
																	<a href="#">
																		<div class="item">
																			<div class="icon green"><i class="i-download-2"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f",$exchangeRatePurchase); ?></span>
																			<span class="txt">C$ compra</span>
																		</div>
																	</a>
																</li>
																<li class="si_ferreteria_mateo">
																	<a href="#">
																		<div class="item">
																			<div class="icon yellow"><i class="i-search-3"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f",$exchangeRateSale); ?></span>
																			<span class="txt">C$ vent</span>
																		</div>
																	</a>
																</li>
																<li class="si_ferreteria_mateo">
																	<a href="#">
																		<div class="item">
																			<div class="icon orange"><i class="i-temperature"></i></div>
																			<span class="percent"><?php echo sprintf("%01.2f",$exchangeRate); ?></span>
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
												<div class="col-lg-6">
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Persona Ref.</label>
															<div class="col-lg-8">
																<input class="form-control"   type="text" name="txtReferenceClientName" id="txtReferenceClientName" value="">
															</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">ID Persona Ref.</label>
															<div class="col-lg-8">
																<input class="form-control"   type="text" name="txtReferenceClientIdentifier" id="txtReferenceClientIdentifier" value="">
															</div>
													</div>
														
																							
												</div>
												<div class="col-lg-6">
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_box_share","labelReference1",""); ?></label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
															</div>
													</div>	
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia2</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">												
															</div>
													</div>			
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
							
									<br/>
									<a href="#" class="btn btn-flat btn-info" id="btnNewShare" >Agregar</a>
									<a href="#" class="btn btn-flat btn-danger" id="btnDeleteShare" >Eliminar</a>	

									<a href="#" class="btn btn-flat btn-danger <?php echo getBehavio($company->type,"app_box_share","btnVerMovimientos","hidden"); ?> " id="btnVerMovement">
											<i class="i-print"></i>
											<span class="percent">Ver</span>
											<span class="txt">movimientos</span>
									</a>
												

							
									<div class="row">
                                        <div class="col-lg-12">
											</br>
                                            <h3>Detalle:</h3>
											</br>
                                            <table id="tb_transaction_master_detail" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th></th>
													<th class="<?php echo getBehavio($company->type,"app_box_share","TableColumnDocumento",""); ?> " >
														Documento
													</th>
													<th>Saldo inicial</th>
                                                    <th>Abonar</th>
													<th>Saldo final</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="body_tb_transaction_master_detail">
                                                </tbody>
                                            </table>
                                            
                                        </div><!-- End .col-lg-12  --> 
                                    </div><!-- End .row-fluid  -->
                                    <div class="row <?php echo getBehavio($company->type,"app_box_share","divResumenAbono",""); ?> ">
                                        <div class="col-lg-4 <?php echo $useMobile == "1" ? "hidden" : "" ?>">
											<div class="page-header">
                                                <h3>Ref.</h4>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li><h3>CC: <span class="red-smooth">*</span></h3></li>
                                                <li><i class="icon16 i-arrow-right-3"></i>Resumen del abono</li>                                                
                                            </ul>
                                        </div>
                                        <div class="col-lg-4">
											<div class="page-header">
                                                <h3>Pago</h3>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>INGRESO</th>
                                                        <td >
															<input type="text" id="txtReceiptAmount" name="txtReceiptAmount"  class="col-lg-12 txt-numeric" value="0" style="text-align:right"/>
														</td>
                                                    </tr>
													<tr>
                                                        <th>DESCUENTO</th>
                                                        <td >
															<input type="text" id="txtDiscountAmount" name="txtDiscountAmount"  class="col-lg-12 txt-numeric" value="0" style="text-align:right"/>
														</td>
                                                    </tr>
                                                    <tr>
                                                        <th>CAMBIO</th>
                                                        <td >
															<input type="text" id="txtChangeAmount" name="txtChangeAmount" readonly class="col-lg-12 txt-numeric" value="" style="text-align:right"/>
														</td>
                                                    </tr>
                                                </tbody>
                                            </table>
										</div>
                                        <div class="col-lg-4 <?php echo $useMobile == "1" ? "hidden" : "" ?>">
                                            <div class="page-header">
                                                <h3>Resumen</h3>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>                                                    
													<tr>
                                                        <th>TOTAL</th>
                                                        <td >
															<input type="text" id="txtTotal" name="txtTotal" readonly class="col-lg-12 txt-numeric" value="" style="text-align:right"/>
														</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!-- End .col-lg-6  --> 
                                    </div><!-- End .row-fluid  -->
                                       
                                </div>
								</form>
								<!-- /body -->
							</div>
						</div>
					</div>
					
					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo base_url(); ?>/app_box_share/index" id="btnBack" class="btn btn-warning" ><i class="icon16 i-rotate"></i> Atras</a>                                    
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
                                </div>
                            </div> 
                            <!-- /botonera -->
                        </div>
                        <!-- End #email  -->
                    </div>
                    <!-- End .row-fluid  -->
					
					
					<script type="text/template"  id="tmpl_row_document">
							<tr class="row_razon">
								<td>
									<input type="checkbox"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
									<input type="hidden" name="txtDetailCustomerCreditDocumentID[]" id="txtDetailCustomerCreditDocumentID"  class="classDetailItem" />
									<input type="hidden" name="txtDetailTransactionDetailID[]" id="txtDetailTransactionDetailID" />
									<input type="hidden" name="txtDetailTransactionDetailDocument[]" id="txtDetailTransactionDetailDocument" />
									<input type="hidden" name="txtDetailTransactionDetailFecha[]" id="txtDetailTransactionDetailFecha" />
									<input type="hidden" name="txtDetailAmortizationID[]" id="txtDetailAmortizationID" />
									<input type="hidden" name="txtDetailBalanceStart[]" id="txtDetailBalanceStart" />
									<input type="hidden" name="txtDetailBalanceFinish[]" id="txtDetailBalanceFinish" />
									
								</td>
								<td class="<?php echo getBehavio($company->type,"app_box_share","TableColumnDocumento",""); ?>" >									
									<?php 
										if($useMobile == "1")
										{
											echo '<span class="badge badge-inverse" >Factura</span></br>';
											echo '<div style="text-align:right">';
												echo '<text id="txtDocument"></text>';
											echo '</div>';
										}
										else 
										{
											echo '<text id="txtDocument"></text>';
										}
									?>
									
								</td>
								<td>
									<?php 
										if($useMobile == "1")
										{
											echo '<span class="badge badge-inverse" >Saldo inicial</span></br>';
											echo '<div style="text-align:right">';
												echo '<text id="txtBalanceStartShare"></text>';
											echo '</div>';
										}
										else 
										{
											echo '<text id="txtBalanceStartShare"></text>';
										}
									?>
									
								</td>
								<td>
									<?php 
										if($useMobile == "1")
										{
											echo '<span class="badge badge-inverse" >Abono</span></br>';
										}
									?>
									<input class="form-control txtDetailShare txt-numeric"  type="text" id="txtDetailShare"  name="txtDetailShare[]"  value="" />
								</td>
								<td>
									<?php 
										if($useMobile == "1")
										{
											echo '<span class="badge badge-inverse" >Saldo Final</span></br>';
											echo '<div style="text-align:right">';
												echo '<text id="txtBalanceFinishShare"></text>';
											echo '</div>';
										}
										else
										{
											echo '<text id="txtBalanceFinishShare"></text>';
										}
									?>
									
								</td>
							</tr>
					</script>