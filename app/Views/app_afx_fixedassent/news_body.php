					<div class="row"> 
                        <div id="email" class="col-lg-12">
                        
                        	<!-- botonera -->
                            <div class="email-bar" style="border-left:1px solid #c9c9c9">                                
                                <div class="btn-group pull-right">                                    
									<a href="<?php echo base_url(); ?>/app_afx_fixedassent/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
										<h4>CODIGO:#<span class="invoice-num">00000000</span></h4>
								</div>
								<!-- /titulo de comprobante-->
								
								<!-- body -->	
								<form id="form-new-afx-fixedassent" name="form-new-afx-fixedassent" class="form-horizontal" role="form">
								<div class="panel-body printArea"> 
								
									<ul id="myTab" class="nav nav-tabs">
										<li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
										<li><a href="#profile" data-toggle="tab">Referencias.</a></li>
									</ul>
									
									<div class="tab-content">
										<div class="tab-pane fade in active" id="home">	
											<div class="row">										
											<div class="col-lg-6">
												
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Nombre</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtName" id="txtName" value="">												
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Modelo</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtModelNumber" id="txtModelNumber" value="">												
															</div>
													</div>						
													<div class="form-group">
														<label class="col-lg-4 control-label" for="selectFilter">Categoria</label>
														<div class="col-lg-8">
															<select name="txtCategoryID" id="txtCategoryID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListCategory)
																	foreach($objListCategory as $ws){
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
															<label class="col-lg-4 control-label" for="normal">Marca</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtMarca" id="txtMarca" value="">												
															</div>
													</div>
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Año</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtYear" id="txtYear" value="">												
															</div>
													</div>	
													<div class="form-group">
														<label class="col-lg-4 control-label" for="buttons">Asignado A</label>
														<div class="col-lg-8">
															<div class="input-group">
																<input type="hidden" id="txtAsignedEmployeeID" name="txtAsignedEmployeeID">
																<input class="form-control" readonly id="txtAsignedEmployeeDescripcion" type="txtAsignedEmployeeDescripcion">
																
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
														<label class="col-lg-4 control-label" for="selectFilter">Tipo</label>
														<div class="col-lg-8">
															<select name="txtTypeID" id="txtTypeID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListType)
																	foreach($objListType as $ws){
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
														<label class="col-lg-4 control-label" for="selectFilter">Color</label>
														<div class="col-lg-8">
															<select name="txtColorID" id="txtColorID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListColor)
																	foreach($objListColor as $ws){
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
														<label class="col-lg-4 control-label" for="selectFilter">Depreciacion</label>
														<div class="col-lg-8">
															<select name="txtTypeDepresiationID" id="txtTypeDepresiationID" class="select2">
																	<option></option>																
																	<?php
																	$count = 0;
																	if($objListTypeDepresiation)
																	foreach($objListTypeDepresiation as $ws){
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
											</div>
											</div>
										</div>
										<div class="tab-pane fade" id="profile">
											<div class="row">		
											
												<div class="col-lg-6">
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Años de Utilidad</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtYearUtility" id="txtYearUtility" value="">												
															</div>
													</div>		
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Chasis No</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtChasisNumber" id="txtChasisNumber" value="">												
															</div>
													</div>		
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Es Foraneo</label>
															<div class="col-lg-8">
																<input type="hidden" name="txtIsForaneo" value="0"> <!-- En caso de que no se seleccione el checkbox -->
																<input type="checkbox"   name="txtIsForaneo" id="txtIsForaneo" value="1" >
															</div>
													</div>
												</div>
												<div class="col-lg-6">
													
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Referencia1</label>
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
													<div class="form-group">
															<label class="col-lg-4 control-label" for="normal">Precio Inicial</label>
															<div class="col-lg-8">
																<input class="form-control"  type="text"  name="txtPriceStart" id="txtPriceStart" value="">												
															</div>
													</div>				
													
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12">
														<div class="form-group">
															<label class="col-lg-2 control-label" for="normal">Descripcion</label>
															<div class="col-lg-10">
																<textarea class="form-control"  id="txtDescription" name="txtDescription" rows="6"></textarea>
															</div>
														</div>
												</div>
											</div>
										</div>
										
									</div>    
									
                                </div>
								</form>
								<!-- /body -->
							</div>
						</div>
					</div>
					
						