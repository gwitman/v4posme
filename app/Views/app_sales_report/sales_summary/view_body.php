					<div class="row"> 
						<div class="col-lg-12">
								<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="icon"><i class="icon20 i-tags-2"></i></div> 
                                        <h4>RESUMEN DE VENTAS</h4>
                                        <a href="#" class="minimize"></a>
										<div class="w-right" style="margin-right:20px">
											<button id="print-btn-report" class="btn btn-primary btn-full tip" title="Ver Reporte" rel="panel-body"><i class="icon20 i-file gap-right0"></i></button>
										</div>
                                    </div>
									<!-- End .panel-heading -->
                                
                                    <div class="panel-body">
                                        <form class="form-horizontal pad15 pad-bottom0" role="form">											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Fecha Inicial y Final</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtStartOn" id="txtStartOn" value="2014-01-30">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
													</div>
													<div class="col-lg-6">
															<div id="datepicker_v2" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtEndOn" id="txtEndOn" value="2014-01-30">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
													</div>													
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Ventas con IVA</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
													</div>
													<div class="col-lg-6">
														<select name="txtTax1" id="txtTax1" class="select2">
																<option value="0">TODAS</option>
																<option value="-1">NO</option>
																<option value="1">SIN</option>
														</select>
													</div>													
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Bodegas</label>
												<div class="col-lg-6"> 
													<div class="col-lg-6">
													</div>
													<div class="col-lg-6">
														<select name="txtWarehouseID" id="txtWarehouseID" class="select2">
																<option value="0">TODAS</option>
																<?php
																if($objListWarehouse)
																foreach($objListWarehouse as $i){
																	echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
																}
																?>
														</select>
													</div>													
												</div>
											</div>
											
											<div class="form-group">
												<label class="col-lg-6 control-label" for="selectFilter">Cliente</label>
												<div class="col-lg-6"> 													
													<div class="col-lg-12">
														<div class="input-group">
															<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="0">
															<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="TODOS">
															
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
											</div>
											
                                        </form>
                                    </div><!-- End .panel-body -->
                                </div><!-- End .widget -->	
						<div>
					<div>