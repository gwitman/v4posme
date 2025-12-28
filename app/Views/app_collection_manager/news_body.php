					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo base_url(); ?>/app_collection_manager/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>                                    
                                </div>
                            </div> 
                            <!-- /botonera -->
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<!-- widget -->
							<div class="panel panel-default">
								<!-- panel widget -->
                                <div class="panel-heading">
	                                  <div class="icon"><i class="icon20 i-cube"></i></div> 
	                                  <h4>Formulario de Datos</h4>
	                                  <a href="#" class="minimize"></a>
                                </div>
                                <!-- /panel widget -->
								<!-- body widget -->
                                <div class="panel-body noPadding">
									<!-- body -->
									<div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
										<div class="row">
											<div class="email-content col-lg-12">
												<!-- formulario -->
												<form id="form-new-account-type" name="form-new-rol" class="form-horizontal" role="form">
													<fieldset>		
													
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons"><?php echo getBehavio($company->type,"app_collection_manager","lblColaborador","Colaborador"); ?></label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtEmployeeID" name="txtEmployeeID">
																	<input class="form-control" readonly id="txtEmployeeDescription" type="txtEmployeeDescription">																
																	
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
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Cliente</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtCustomerID" name="txtCustomerID">
																	<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription">																
																	
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
															<label class="col-lg-4 control-label" for="buttons">Orden No</label>
															<div class="col-lg-8">
																<div class="input-group">	
																	<input class="form-control" type="number" name="txtOrderNo" id="txtOrderNo" value="">
																</div>
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Referencia FAC</label>
															<div class="col-lg-8">
																<div class="input-group">	
																	<input class="form-control" type="text" name="txtReference1" id="txtReference1">																																																																																																		
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Referido</label>
															<div class="col-lg-8">
																<div class="input-group">	
																	<input class="form-control" type="text" name="txtReference2" id="txtReference2">																																																																																																		
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Tasa</label>
															<div class="col-lg-8">
																<div class="input-group">	
																	<input class="form-control" type="text" name="txtReference3" id="txtReference3">																																																																																																		
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Aplicar orden depues de</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtCustomerIDAfter" name="txtCustomerIDAfter" value="0">
																	<input class="form-control" readonly id="txtCustomerDescriptionAfter" name="txtCustomerDescriptionAfter" value="" >
																	
																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearCustomerAfter">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchCustomerAfter">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>
																	
																</div>
															</div>
														</div>
														
																																									
													</fieldset> 
												</form>
												<!-- /formulario -->
											</div>
										</div><!-- End .row-fluid  -->                            
									</div>
									<!-- /body -->
                                </div>
							</div>
							
                        </div>
                        <!-- End #email  -->
                    </div>
                    <!-- End .row-fluid  -->