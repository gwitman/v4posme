<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">     
				<a href="<?php echo base_url(); ?>/app_purchase_pedidos/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>						
				<a href="<?php echo base_url(); ?>/app_purchase_pedidos/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
				<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
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
					<h4>INGRESO:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span></h4>
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
							<li id="btnClickArchivo"><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
						 </ul>
					</li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
							<div class="col-lg-6">
								
									<input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
									<input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
									<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">
									
									
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Fecha pedido</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Fecha bodega</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDate2" id="txtDate2" value="<?php echo explode(" ",$objTransactionMaster->transactionOn2)[0]; ?>" >
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
														if($ws->branchID == $objTransactionMaster->branchID )
															echo "<option value='".$ws->branchID."' selected >".$ws->name."</option>";
														else 
															echo "<option value='".$ws->branchID."' >".$ws->name."</option>";
														
														$counter++;
															
													}
													?>
											</select>
										</div>
									</div>
									
									<div class="form-group hidden">
											<label class="col-lg-4 control-label" for="normal">Aplicado</label>
											<div class="col-lg-5">
												<input type="checkbox" disabled   name="txtIsApplied" id="txtIsApplied" value="1" <?php if($objTransactionMaster->isApplied) echo "checked"; ?> >
											</div>
									</div>
									<div class="form-group hidden">
											<label class="col-lg-4 control-label" for="normal">Cambio</label>
											<div class="col-lg-8">
												<input class="form-control"   type="text" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
											</div>
									</div>
									
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="selectFilter">Status de pedido</label>
										<div class="col-lg-8">
											<select name="txtZoneID" id="txtZoneID" class="select2">																									
													<?php
													$counter = 0;
													if($objListPedidoStatus)
													foreach($objListPedidoStatus as $ws){
														if($ws->catalogItemID == $objTransactionMasterInfo->zoneID)
															echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
														else 
															echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
															
													}
													?>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Factura</label>
										<div class="col-lg-8">
											<div class="input-group">
												<input type="hidden" id="txtNote" name="txtNote" value="<?php echo $objBilling ? $objBilling->transactionNumber : "";  ?>">
												<input class="form-control" readonly id="txtNoteDescription" type="txtNoteDescription" value="<?php echo $objBilling ? $objBilling->transactionNumber : "" ;  ?>">
												
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
										<label class="col-lg-4 control-label" for="buttons">Cliente</label>
										<div class="col-lg-8">
											<div class="input-group">
												<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomer->entityID;  ?>">
												<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="<?php echo $objCustomerNatural != null ? strtoupper($objCustomer->customerNumber . " ". $objCustomerNatural->firstName . " ". $objCustomerNatural->lastName ) : strtoupper($objCustomer->customerNumber." ".$objCustomerLegal->comercialName); ?>">
												
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
												<input type="hidden" id="txtEmployerID" name="txtEmployerID" value="<?php echo $objEmployer->entityID;  ?>">
												<input class="form-control" readonly id="txtEmployerDescription" type="txtEmployerDescription" value="<?php echo $objEmployerNatural != null ? strtoupper($objEmployer->employeNumber . " ". $objEmployerNatural->firstName . " ". $objEmployerNatural->lastName ) : strtoupper($objEmployer->employeNumber." ".$objEmployerLegal->comercialName); ?>">
												
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
											<label class="col-lg-4 control-label" for="normal">Informacion adicional</label>
											<div class="col-lg-8">																														
												<textarea class="form-control" type="text"  name="txtDetailReference1" id="txtDetailReference1" ><?php echo $objTransactionMaster->reference1; ?></textarea>
											</div>
									</div>
									
										
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Marca</label>
											<div class="col-lg-8">
												
												<input class="form-control"  type="text"  name="txtDetailReference2" id="txtDetailReference2" value="<?php echo $objTransactionMaster->reference2; ?>">												
											</div>
									</div>
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Modelo</label>
											<div class="col-lg-8">
												
												<input class="form-control"  type="text"  name="txtDetailReference4" id="txtDetailReference4" value="<?php echo $objTransactionMaster->reference4; ?>">												
											</div>
									</div>
									
									
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Numero de parte</label>
											<div class="col-lg-8">																	
												<textarea class="form-control"  type="text"  name="txtDetailReference3" id="txtDetailReference3" ><?php echo $objTransactionMaster->reference3; ?></textarea>
											</div>
									</div>
									
								
							</div>
							<div class="col-lg-6">
						
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
									<div class="col-lg-8">
										<select name="txtCurrencyID" id="txtCurrencyID" class="select2">																									
												<?php
												if($objListCurrency)
												foreach($objListCurrency as $ws){
													if($ws->currencyID == $objTransactionMaster->currencyID)
														echo "<option value='".$ws->currencyID."' selected>".$ws->simb."</option>";
													else 
														echo "<option value='".$ws->currencyID."' >".$ws->simb."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Precio de venta</label>
										<div class="col-lg-8">
											<input class="form-control"  type="text"  name="txtDetailAmount" id="txtDetailAmount" value="<?php echo sprintf("%01.2f",$objTransactionMaster->amount); ?>">
										</div>
								</div>
								
								
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Pieza</label>
									<div class="col-lg-8">
										<select name="txtAreaID" id="txtAreaID" class="select2">																									
												<?php
												if($objListPiezas)
												foreach($objListPiezas as $ws){
													if($ws->catalogItemID == $objTransactionMaster->areaID)
														echo "<option value='".$ws->catalogItemID."' selected>".$ws->name."</option>";
													else 
														echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Otros</label>
										<div class="col-lg-8">												
											<input class="form-control"  type="text"  name="txtTMInfoDetailReferenceClientName" id="txtTMInfoDetailReferenceClientName" value="<?php echo $objTransactionMasterInfo->referenceClientName; ?>">
										</div>
								</div>
								
								
								<div class="form-group">
									<label class="col-lg-4 control-label" for="selectFilter">Envio</label>
									<div class="col-lg-8">
										<select name="txtPriorityID" id="txtPriorityID" class="select2">																									
												<?php
												$counter = 0;
												if($objListEnvios)
												foreach($objListEnvios as $ws){
													if($ws->catalogItemID == $objTransactionMaster->priorityID)
														echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
													else 
														echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";
													
													$counter++;
														
												}
												?>
										</select>
									</div>
								</div>
								
								<div class="form-group <?php echo getBehavio($company->type,"app_purchase_pedidos","divDllEstado",""); ?>  ">
									<label class="col-lg-4 control-label " for="selectFilter">Estado</label>
									<div class="col-lg-8">
										<select name="txtStatusID" id="txtStatusID" class="select2">
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
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Tracking</label>
										<div class="col-lg-8">												
											<input class="form-control"  type="text"  name="txtTMInfoDetailReference1" id="txtTMInfoDetailReference1" value="<?php echo $objTransactionMasterInfo->reference1; ?>">
										</div>
								</div>
								
								<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Precio de compra</label>
										<div class="col-lg-8">												
											<input class="form-control"  type="text"  name="txtTMInfoDetailReference2" id="txtTMInfoDetailReference2" value="<?php echo $objTransactionMasterInfo->reference2; ?>">
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
								<table class="table table-bordered">
									<thead>
									  <tr>															
										<th>Tipo</th>
										<th>Archivo</th>
									  </tr>
									</thead>
									<tbody id="body_detail_file">
										<?php 
										
										if($objTransactionMasterDetailDocument)
										foreach($objTransactionMasterDetailDocument as $ws)
										{
												?>
												<tr>
													<td>
														<input type="hidden"  name="txtFileID[]" value="<?php echo $ws->transactionMasterDetailID; ?>">
														<input type="hidden"  name="txtFileTypeID[]" value="<?php echo $ws->componentItemID; ?>">
														<span class="badge badge-inverse" >
															<?php echo $ws->tipoFile; ?>
														</span>																			
													</td>
													<td>
														<input type="file" name="txtFileDocument[]" value="<?php echo $ws->reference3; ?>" >
														
														<a class="btn btn-success"  href="<?php echo $objParameterUrlServerFile."/resource/file_company/company_2/component_97/component_item_".$objTransactionMaster->transactionMasterID."/".$ws->reference3; ?>" target="_blank" >
															<?php echo $ws->reference3; ?>
														</a>
													
													</td>
												</tr>		
												<?php 																
										}
										?>											
									</tbody>
								</table>
					</div>
				</div>    
				   
			</div>
			</form>
			<!-- /body -->
		</div>
	</div>
</div>

<?php echo getBehavio($company->type,"app_purchase_pedidos","divScriptCustom",""); ?>