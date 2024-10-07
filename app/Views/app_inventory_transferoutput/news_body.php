					<!--Botonera-->
					<div class="row">
                        <div id="email" class="col-lg-12">
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">
                                <div class="btn-group pull-right">
									<a href="<?php echo base_url(); ?>/app_inventory_transferoutput/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>
                                    <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Botonera-->
					
					
				    <div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
										
								<!-- titulo del movimiento-->
								<div class="panel-heading">
										<div class="icon"><i class="icon20 i-file"></i></div> 
										<h4>NUMERO:#<span class="invoice-num">00000000</span></h4>
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
												<li><a href="#dropdown" data-toggle="tab">Comentarios</a></li>
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
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="datepicker">Fecha</label>
														<div class="col-lg-8">
															<div id="datepicker" class="input-group date"  data-date-format="yyyy-mm-dd">
																<input size="16"  class="form-control" type="text" name="txtTransactionOn" id="txtTransactionOn" >
																<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
															</div>
														</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Usuario</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtUser" id="txtUser" readonly value="">												
															</div>
													</div>
													
													<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Aplicado</label>
															<div class="col-lg-5">
																<input type="checkbox"   name="txtIsApplied" id="txtIsApplied" disabled value="1" >
															</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-2 control-label" for="selectFilter">Causa</label>
														<div class="col-lg-8">
															<select name="txtTransactionCausalID" id="txtTransactionCausalID" class="select2">
																	<option></option>
																	<?php
																	$ix = 0;
																	if($objListTransactionCausal)
																	foreach($objListTransactionCausal as $i){
																		if($ix == 0)
																		echo "<option value='".$i->transactionCausalID."' selected >".$i->name."</option>";
																		else
																		echo "<option value='".$i->transactionCausalID."'>".$i->name."</option>";
																	
																		$ix++;
																	}
																	?>
															</select>
														</div>
													</div>
													
													
													
													
											</div>
											<div class="col-lg-6">
											
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Estado</label>
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
														<label class="col-lg-4 control-label" for="selectFilter">B. Origen</label>
														<div class="col-lg-8">
															<select name="txtWarehouseSourceID" id="txtWarehouseSourceID" class="select2">
																	<option></option>
																	<?php
																	$count = 1;
																	if($objListWarehouse)
																	foreach($objListWarehouse as $i){
																		if($count == 1)
																		echo "<option value='".$i->warehouseID."' selected>".$i->name."</option>";
																		else
																		echo "<option value='".$i->warehouseID."' >".$i->name."</option>";
																		$count++;
																	}
																	?>
															</select>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">B. Destino</label>
														<div class="col-lg-8">
															<select name="txtWarehouseTargetID" id="txtWarehouseTargetID" class="select2">
																	<option></option>
																	<?php
																	$count = 1;
																	if($objListWarehouseAll)
																	foreach($objListWarehouseAll as $i){
																		if($count == 1)
																		echo "<option value='".$i->warehouseID."' selected>".$i->name."</option>";
																		else
																		echo "<option value='".$i->warehouseID."' >".$i->name."</option>";
																		$count++;
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
												<div class="row">										
													<div class="col-lg-6">	
															<div class="form-group">
																<label class="col-lg-2 control-label" for="buttons">Envia</label>
																<div class="col-lg-8">
																	<div class="input-group">
																		<input type="hidden" id="txtEmployeeID" name="txtEmployeeID">
																		<input class="form-control" readonly id="txtEmployeeDescription" type="txtEmployeeDescription">																
																		
																		<span class="input-group-btn">
																			<button class="btn btn-danger" type="button" id="btnClearEmployeeParent">
																				<i aria-hidden="true" class="i-undo-2"></i>
																				clear
																			</button>
																		</span>
																		<span class="input-group-btn">
																			<button class="btn btn-primary" type="button" id="btnSearchEmployeeParent">
																				<i aria-hidden="true" class="i-search-5"></i>
																				buscar
																			</button>
																		</span>
																		
																	</div>
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-lg-2 control-label" for="buttons">Recibe</label>
																<div class="col-lg-8">
																	<div class="input-group">
																		<input type="hidden" id="txtEmployeeIDTarget" name="txtEmployeeIDTarget">
																		<input class="form-control" readonly id="txtEmployeeDescriptionTarget" type="txtEmployeeDescriptionTarget">
																		
																		<span class="input-group-btn">
																			<button class="btn btn-danger" type="button" id="btnClearEmployeeParentTarget">
																				<i aria-hidden="true" class="i-undo-2"></i>
																				clear
																			</button>
																		</span>
																		<span class="input-group-btn">
																			<button class="btn btn-primary" type="button" id="btnSearchEmployeeParentTarget">
																				<i aria-hidden="true" class="i-search-5"></i>
																				buscar
																			</button>
																		</span>
																		
																	</div>
																</div>
															</div>
															
															
													</div>
													<div class="col-lg-6">	
													
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal"><?php echo getBehavio($company->type,"app_inventory_transferoutput","labelReference1",""); ?></label>
																<div class="col-lg-8">
																	<input class="form-control"  type="text" name="txtReference1" id="txtReference1" value="">
																</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Nota</label>
															<div class="col-lg-8">
																<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"></textarea>
															</div>
														</div>
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
                                                    <th></th><!--0-->
													<th>itemID</th><!--1-->
													<th>transactionDetailID</th><!--2-->
                                                    <th>Codigo</th><!--3-->
                                                    <th>Nombre</th><!--4-->
                                                    <th>U/M</th><!--5-->
                                                    <th>Cantidad</th><!--6-->
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