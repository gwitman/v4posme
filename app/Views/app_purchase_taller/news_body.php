					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo base_url(); ?>/app_purchase_taller/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
										<h4>PEDIDO:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form" enctype="multipart/form-data" >
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
												<div class="col-lg-6">
													
										
														<div class="form-group">
															<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
															<div class="col-lg-8">
																<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
																	<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" >
																	<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Sucursal</label>
															<div class="col-lg-8">
																<select name="txtBranchID" id="txtBranchID" class="select2">																									
																		<?php
																		$counter = 0;
																		if($objListBranch)
																		foreach($objListBranch as $ws){
																			if($counter == 0 )
																				echo "<option value='".$ws->branchID."' selected >".$ws->name."</option>";
																			else 
																				echo "<option value='".$ws->branchID."' >".$ws->name."</option>";
																			
																			$counter++;
																				
																		}
																		?>
																</select>
															</div>
														</div>
														
														
														<div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtApplied","");  ?> ">
																<label class="col-lg-4 control-label" for="normal">Aplicado</label>
																<div class="col-lg-5">
																	<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" >
																</div>
														</div>
														<div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtChange","");  ?>">
																<label class="col-lg-4 control-label" for="normal">Cambio</label>
																<div class="col-lg-8">
																	<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
																</div>
														</div>
														
														<div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtStatus","");  ?>">
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
															<label class="col-lg-4 control-label" for="buttons">Cliente</label>
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
															<label class="col-lg-4 control-label" for="buttons">Tecnico</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtEmployerID" name="txtEmployerID" value="">
																	<input class="form-control" readonly id="txtEmployerDescription" type="txtEmployerDescription" value="">
																	
																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearEmployer">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchEmployer">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>											
																</div>
															</div>
														</div>
														
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="buttons">Factura</label>
															<div class="col-lg-8">
																<div class="input-group">
																	<input type="hidden" id="txtNote" name="txtNote" value="">
																	<input class="form-control" readonly id="txtNoteDescription" type="txtNoteDescription" value="">
																	
																	<span class="input-group-btn">
																		<button class="btn btn-danger" type="button" id="btnClearNote">
																			<i aria-hidden="true" class="i-undo-2"></i>
																			clear
																		</button>
																	</span>
																	<span class="input-group-btn">
																		<button class="btn btn-primary" type="button" id="btnSearchNote">
																			<i aria-hidden="true" class="i-search-5"></i>
																			buscar
																		</button>
																	</span>											
																</div>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Estado del equipo</label>
															<div class="col-lg-8">
																<select name="txtAreaID" id="txtAreaID" class="select2">																									
																		<?php
																		$counter = 0;
																		if($objListEstadosEquipo)
																		foreach($objListEstadosEquipo as $ws){
																			if($counter == 0)
																				echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																			else 
																				echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
																			
																			$counter++;
																				
																		}
																		?>
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Articulo</label>
															<div class="col-lg-8">
																<select name="txtRouteID" id="txtRouteID" class="select2">																									
																		<?php
																		$counter = 0;
																		if($objListArticulos)
																		foreach($objListArticulos as $ws){
																			if($counter == 0)
																				echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																			else 
																				echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
																			
																			$counter++;
																				
																		}
																		?>
																</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Desc. otros</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtReferenceClientName" id="txtReferenceClientName" value="">
															</div>
														</div>

														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Marca</label>
															<div class="col-lg-8">
																<select name="txtZoneID" id="txtZoneID" class="select2">																									
																		<?php
																		$counter = 0;
																		if($objListMarca)
																		foreach($objListMarca as $ws){
																			if($counter == 0)
																				echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																			else 
																				echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
																			
																			$counter++;
																				
																		}
																		?>
																</select>
															</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Modelo</label>
																<div class="col-lg-8">
																	<input class="form-control"  type="text"  name="txtInfoReference2" id="txtInfoReference2" value="">
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Serie</label>
																<div class="col-lg-8">
																	<input class="form-control"  type="text"  name="txtReference4" id="txtReference4" value="">
																</div>
														</div>

														
														<div class="form-group">
															<label class="col-lg-4 control-label" for="selectFilter">Accesorios</label>
															<div class="col-lg-8">
																<select name="txtPriorityID" id="txtPriorityID" class="select2">																									
																		<?php
																		$counter = 0;
																		if($objListAccesorios)
																		foreach($objListAccesorios as $ws){
																			if($counter == 0)
																				echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
																			else 
																				echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
																			
																			$counter++;
																				
																		}
																		?>
																</select>
															</div>
														</div>
													
													
												</div>
												<div class="col-lg-6">
														
														<div class="form-group <?php echo getBehavio($company->type,"app_purchase_taller","divTxtCurrency","");  ?>">
															<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
															<div class="col-lg-8">
																<select name="txtCurrencyID" id="txtCurrencyID" class="select2">																		>																
																		<?php
																		$counter = 0;
																		if($objListCurrency)
																		foreach($objListCurrency as $ws){
																			
																			if($counter == 0)
																				echo "<option value='".$ws->currencyID."' selected>".$ws->simb."</option>";
																			else
																				echo "<option value='".$ws->currencyID."' >".$ws->simb."</option>";
																			
																			$counter++;
																		}
																		?>
																</select>
															</div>
														</div>
														
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Problema</label>
																<div class="col-lg-8">
																	<textarea class="form-control" type="text"  name="txtDetailReference2" id="txtDetailReference2" ></textarea>																	
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Nota</label>
																<div class="col-lg-8">																																		
																	<textarea class="form-control" type="text"  name="txtDetailReference1" id="txtDetailReference1" ></textarea>
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Contraseña</label>
																<div class="col-lg-8">
																	<input class="form-control"  type="text"  name="txtInfoReference1" id="txtInfoReference1" value="">
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Solucion</label>
																<div class="col-lg-8">																	
																	<textarea class="form-control" type="text"  name="txtDetailReference3" id="txtDetailReference3" ></textarea>
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal">Monto</label>
																<div class="col-lg-8">
																	<input type="hidden" name="txtDetailTransactionDetailID" value="0">
																	<input class="form-control"  type="text"  name="txtDetailAmount" id="txtDetailAmount" value="0">												
																</div>
														</div>
														
														<div class="form-group">
																<label class="col-lg-4 control-label" for="normal"></label>
																<div class="col-lg-8">
																	<blockquote class="red" style="text-align: justify;" >
																		Agregar cualquier nota al cliente (rayones, quebraduras) Observaciones (Al no encender el equipo, no sabemos si algún componente este dañado tal como batería,pantalla,tecaldo etc.)
																	</blockquote>
																</div>
														</div>
														
														
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile">
											<div class="row">
												<div class="col-lg-6 currency-1">
													
												
													
												</div>
												<div class="col-lg-6 currency-2">
													
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="dropdown">
											
												
											
										</div>
										<div class="tab-pane fade" id="dropdown-file">
											
											
										
											
											<div class="row">
												<div class="col-lg-12">
													<table class="table table-bordered">
														<thead>
														  <tr>															
															<th>Tipo</th>
															<th>Archivo</th>
														  </tr>
														</thead>
														<tbody id="body_detail_file">
															<?php 
															if($objListArchivos)
															foreach($objListArchivos as $ws)
															{
																
																	?>
																	<tr>
																		<td>
																			<input type="hidden"  name="txtFileID[]" value="0">
																			<input type="hidden"  name="txtFileTypeID[]" value="<?php echo $ws->catalogItemID; ?>">
																			<span class="badge badge-inverse" >
																				<?php echo $ws->name; ?>
																			</span>																			
																		</td>
																		<td>
																			<input type="file" name="txtFileDocument[]" >
																		</td>
																	</tr>		
																	<?php 																
															}
															?>											
														</tbody>
													</table>
													
												</div><!-- End .col-lg-12  --> 
											</div><!-- End .row-fluid  -->
											
											
											
										</div>
									</div>    
							
                                       
                                </div>
								</form>
								<!-- /body -->
							</div>
						</div>
					</div>
					
					
					