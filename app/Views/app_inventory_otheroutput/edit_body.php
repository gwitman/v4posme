					<!-- botonera -->
					<div class="row"> 
                        <div id="email" class="col-lg-12">
							<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo base_url(); ?>/app_inventory_otheroutput/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
									<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
									<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
									<a href="#" class="btn btn-primary" id="btnPrinterSticker"><i class="icon16 i-print"></i> Sticker</a>
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /botonera -->
					
					
					
				    <div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
										
								<!-- titulo del movimiento-->
								<div class="panel-heading">
										<div class="icon"><i class="icon20 i-file"></i></div> 
										<h4>NUMERO:#<span class="invoice-num"><?php echo $objTM->transactionNumber;?></span></h4>
								</div>
								<!-- /titulo del movimiento-->
								
								<!-- formulario -->	
								<form id="form-new-transaction" name="form-new-transaction" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<!--tab menu-->
									<ul id="myTab" class="nav nav-tabs">
										<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>										
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas<b class="caret"></b></a>
											<ul class="dropdown-menu">
												<li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
												<li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
											 </ul>
										</li>
									</ul>
									<!--tab menu-->
									
									<!--tab content-->
									<div class="tab-content">
										<!--tab content general-->
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
											<div class="col-lg-6">
													<input type="hidden" name="txtCompanyID" value="<?php echo $objTM->companyID; ?>">
													<input type="hidden" name="txtTransactionID" value="<?php echo $objTM->transactionID; ?>">
													<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTM->transactionMasterID; ?>">
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="datepicker">Fecha</label>
														<div class="col-lg-8">
															<div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo $objTM->transactionOn;?>">
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
														</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Usuario</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtUser" id="txtUser" readonly value="<?php echo $objUser->nickname; ?>">												
															</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Aplicado</label>
															<div class="col-lg-5">
																<input type="checkbox"   name="txtIsApplied" id="txtIsApplied" disabled value="1" <?php if($objTM->isApplied == 1) echo "checked"; ?> >
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
																		if($ws->workflowStageID == $objTM->statusID)
																		echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
																		else 
																		echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
																	}
																	?>
															</select>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="selectFilter">Bodega</label>
														<div class="col-lg-8">
															<select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
																	<option></option>
																	<?php
																	if($objListWarehouse)
																	foreach($objListWarehouse as $i){
																		if($i->warehouseID == $objTM->sourceWarehouseID)
																		echo "<option value='".$i->warehouseID."' selected >".$i->name."</option>";
																		else
																		echo "<option value='".$i->warehouseID."'>".$i->name."</option>";
																	}
																	?>
															</select>
														</div>
													</div>
													
											</div>
											</div>
										</div>						
										<!--tab content general-->
										<!--tab content description-->
										<div class="tab-pane fade" id="dropdown">
											
												<div class="form-group">
		                                            <label class="col-lg-2 control-label" for="normal">Nota</label>
		                                            <div class="col-lg-6">
		                                                <textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"><?php echo $objTM->note; ?></textarea>
		                                            </div>
		                                        </div>
											
										</div>
										<!--tab content description-->
										<!--tab content file-->
										<div class="tab-pane fade" id="dropdown-file">
										</div>
										<!--tab content file-->
									</div>
									<!--tab content-->
									
									
									<br/>
									<a href="#" class="btn btn-flat btn-info" id="btnNewDetailTransaction" >Agregar</a>
									<a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailTransaction" >Eliminar</a>
									<!-- detalle del movimiento-->
									<div class="row">
                                        <div class="col-lg-12">
                                            <h3>Detalle:</h3>
                                            <table id="tb_transaction_master_detail" class="table table-bordered">
                                                <thead>
                                                  <tr>
                                                    <th></th>
													<th>itemID</th>
													<th>transactionDetailID</th>
                                                    <th>Codigo</th>
                                                    <th>Nombre</th>
                                                    <th>U/M</th>
                                                    <th>Cantidad</th>
													<th>Lote</th><!--7-->
													<th>Expiracion</th><!--8-->
													<th>Mas</th><!--9-->
                                                  </tr>
                                                </thead>
                                                <tbody id="body_detail_transaction">             
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
									<!-- detalle del movimiento-->
									
                                </div>
								</form>
								<!-- formulario -->	
							</div>
						</div>
					</div>