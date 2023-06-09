					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo base_url(); ?>/app_accounting_journal/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
									<a href="#" class="btn btn-info" id="btnOpenTemplated"><i class="icon16 i-quill-2"></i> Abrir Plantilla</a>
									
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
										<h4>COMPROBANTE:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-account-journal" name="form-new-account-journal" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
										<li><a href="#profile" data-toggle="tab">Referencias.</a></li>
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
														<label class="col-lg-2 control-label" for="datepicker">Fecha</label>
														<div class="col-lg-8">
															<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="hidden" name="txtTemplatedNumber" id="txtTemplatedNumber" >
																<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" >
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
														</div>
													</div>
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Aplicado</label>
															<div class="col-lg-5">
																<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" >
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Cambio</label>
															<div class="col-lg-8">
																<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Beneficiario</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtEntryName" id="txtEntryName" value="">												
															</div>
													</div>
												
											</div>
											<div class="col-lg-6">
											
													<div class="form-group">
														<label class="col-lg-2 control-label" for="selectFilter">Estado</label>
														<div class="col-lg-8">
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
													<div class="form-group">
														<label class="col-lg-2 control-label" for="selectFilter">Tipo</label>
														<div class="col-lg-8">
															<select name="txtJournalType" id="txtJournalType" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListJournalType)
																	foreach($objListJournalType as $ws){
																		if($count == 0 )
																		echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
																		$count++;
																	}
																	?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-lg-2 control-label" for="selectFilter">Moneda</label>
														<div class="col-lg-8">
															<select name="txtCurrencyID" id="txtCurrencyID" class="select2">
																	<option></option>
																	<?php
																	if($objListCurrency)
																	foreach($objListCurrency as $ws){
																		if($ws->currencyID == $objCurrency->currencyID )
																		echo "<option value='".$ws->currencyID."' selected >".$ws->name."</option>";
																		else
																		echo "<option value='".$ws->currencyID."'  >".$ws->name."</option>";
																	}
																	?>
															</select>
														</div>
													</div>
												
											</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile">
										
												<div class="form-group">
														<label class="col-lg-2 control-label" for="normal">Referencia1</label>
														<div class="col-lg-5">
															<input class="form-control"  type="text"  name="txtReference1" id="txtReference1" value="">												
														</div>
												</div>											
												<div class="form-group">
														<label class="col-lg-2 control-label" for="normal">Referencia2</label>
														<div class="col-lg-5">
															<input class="form-control"  type="text"  name="txtReference2" id="txtReference2" value="">												
														</div>
												</div>											
												<div class="form-group">
														<label class="col-lg-2 control-label" for="normal">Referencia3</label>
														<div class="col-lg-5">
															<input class="form-control"  type="text"  name="txtReference3" id="txtReference3" value="">												
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
									<a href="#" class="btn btn-flat btn-info" id="btnNewDetailJournal" >Agregar</a>
									<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailJournal" >Eliminar</a>									
									<div class="row">
                                        <div class="col-lg-12">
                                            <h3>Detalle:</h3>
                                            <table id="tb_journal_entry_detail" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th></th>
													<th></th>
													<th></th>
													<th></th>
                                                    <th>Cuenta</th>
                                                    <th>CC</th>
                                                    <th>Debito</th>
                                                    <th>Credito</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="body_jornal_entry_detail">
                                                </tbody>
                                            </table>
                                            
                                        </div><!-- End .col-lg-12  --> 
                                    </div><!-- End .row-fluid  -->
                                    <div class="row">
                                        <div class="col-lg-6">
											<div class="page-header">
                                                <h3>Ref.</h4>
                                            </div>
                                            <ul class="list-unstyled">
                                                <li><h3>CC: <span class="red-smooth">*</span></h3></li>
                                                <li><i class="icon16 i-arrow-right-3"></i>Centro de Costo</li>                                                
                                            </ul>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="page-header">
                                                <h3>Resumen</h3>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Debito</th>
                                                        <td >
															<input type="text" id="txtTotalDebit" disabled class="col-lg-12 txt-numeric" value=""/>
														</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Credito</th>
                                                        <td >
															<input type="text" id="txtTotalCredit" disabled class="col-lg-12 txt-numeric" value=""/>
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
					