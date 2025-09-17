<div class="main">
		<section id="content">
			<div class="wrapper">
				<div class="container-fluid" id="main_content"> 			
					<div class="row">
                        <div id="email" class="col-lg-12">
                            <div class="email-wrapper" style="padding:15px 15px 15px 15px;padding-left:15px">
                                <div class="row">
                                    <div class="email-content col-lg-12">
                                        <form id="form-new-rol" class="form-horizontal" >
													
													
													<div class="form-group">
														<label class="col-lg-4 control-label" for="datepicker">Vencimiento</label>
														<div class="col-lg-8">
															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtVencimiento" id="txtVencimiento" value="<?php echo $vencimiento;?>"  readonly="readonly"  > 
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
														</div>
													</div>
													
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="preapend">Lote</label>
														<div class="col-lg-10">
															<div class="input-group">
																<span class="input-group-addon">@</span>
																<input class="form-control" id="txtLote" type="text" placeholder="" value="<?php echo $lote;?>">
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="preapend">Precio por mayor</label>
														<div class="col-lg-10">
															<div class="input-group">
																<span class="input-group-addon">$</span>
																<input class="form-control" id="txtPrecio1" type="text" placeholder="" value="<?php echo $precio1;?>">
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="preapend">Precio especial</label>
														<div class="col-lg-10">
															<div class="input-group">
																<span class="input-group-addon">$</span>
																<input class="form-control" id="txtPrecio2" type="text" placeholder="" value="<?php echo $precio2;?>">
															</div>
														</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Expandir codigo</label>
															<div class="col-lg-10">
																<input class="form-control"  type="text"  name="txtReference4TransactionMasterDetail" id="txtReference4TransactionMasterDetail" value="<?php echo $txtReference4TransactionMasterDetail;?>">
															</div>
													</div>	
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="preapend">IVA</label>
														<div class="col-lg-10">
															<div class="input-group">
																<span class="input-group-addon">$</span>
																<input type="checkbox"   name="txtCheckIva" id="txtCheckIva" value="1" >
																<input type="hidden" id="txtCosto"  value="<?php echo $costo;?>" />
																<input class="form-control" id="txtIva" type="text" placeholder="" value="<?php echo $iva;?>" readonly="true" >
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="preapend">ISC</label>
														<div class="col-lg-10">
															<div class="input-group">
																<span class="input-group-addon">$</span>
																<input class="form-control" id="txtIsc" type="text" placeholder="" value="<?php echo $isc;?>">
															</div>
														</div>
													</div>
													
												
												
										</form>
                                    </div>
                                </div><!-- End .row-fluid  -->                            
                            </div>
                        </div><!-- End #email  -->
                    </div><!-- End .row-fluid  -->
				</div>
			</div>
		</section>
					
</div>