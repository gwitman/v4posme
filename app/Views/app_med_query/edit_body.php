<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">     
				<a href="<?php echo base_url(); ?>/app_med_query/add" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>						
				<a href="<?php echo base_url(); ?>/app_med_query/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
					<h4>ASISTENCIA:#<span class="invoice-num"><?php echo $objTransactionMaster->transactionNumber; ?></span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
			<div class="panel-body printArea"> 
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active">
						<a href="#home" data-toggle="tab">Informacion</a>
					</li>		
					<li>
						<a href="#details" data-toggle="tab">Detalles</a>
					</li>			
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
							<div class="col-lg-12">
								
									<input type="hidden" name="txtCompanyID" value="<?php echo $objTransactionMaster->companyID; ?>">
									<input type="hidden" name="txtTransactionID" value="<?php echo $objTransactionMaster->transactionID; ?>">
									<input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTransactionMaster->transactionMasterID; ?>">	
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="date" name="txtDate" id="txtDate" value="<?php echo $objTransactionMaster->transactionOn; ?>" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>
									
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="selectFilter">Prioridad</label>
										<div class="col-lg-8">
											<select name="txtPriorityID" id="txtPriorityID" class="select2">
													<option></option>																
													<?php
													$count = 0;
													if($objListPrioridad)
													foreach($objListPrioridad as $price){
														if( $price->catalogItemID == $objTransactionMaster->priorityID )
															echo "<option value='".$price->catalogItemID."' selected >".$price->display."</option>";
														else
															echo "<option value='".$price->catalogItemID."'  >".$price->display."</option>";
														
														$count++;
													}
													?>
											</select>
										</div>
									</div>
									
									
								
								
									<div class="form-group">
										<label class="col-lg-4 control-label" for="selectFilter">Estado</label>
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
										<label class="col-lg-4 control-label" for="buttons">Cliente</label>
										<div class="col-lg-8">
											<div class="input-group">
												<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objTransactionMaster->entityID;  ?>">
												<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
												
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
										<label class="col-lg-4 control-label" for="buttons">Observacion</label>
										<div class="col-lg-8">
											<textarea class="form-control" type="text"  name="txtNote" id="txtNote" ><?php echo $objTransactionMaster->note;  ?></textarea>
										</div>
									</div>
								
								<!-- New inputs -->
									
									<!-- Edad-->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Edad(Años)</label>
										<div class="col-lg-8">
											<input class="form-control" type="number"  name="txtAge" id="txtAge" value="<?php echo number_format($objTransactionMaster->tax1,0);  ?>"></input>
										</div>
									</div>

									<!-- Altura-->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Altura(cm)</label>
										<div class="col-lg-8">
											<input class="form-control" type="number"  name="txtHeight" id="txtHeight" value="<?php echo number_format($objTransactionMaster->tax2,2);  ?>"></input>
										</div>
									</div>

									<!-- Peso -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Peso(kg)</label>
										<div class="col-lg-8">
											<input class="form-control" type="number"  name="txtWeight" id="txtWeight" value="<?php echo number_format($objTransactionMaster->tax3,2);  ?>"></input>
										</div>
									</div>
								
									<!-- IMC -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">IMC</label>
										<div class="col-lg-8">
											<input class="form-control" readonly type="number" name="txtIMC" id="txtIMC" value="<?php echo number_format($objTransactionMaster->tax4,2) ?>"></input>
										</div>
									</div>
									
									<!-- Proxima visita -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Proxima Visita</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="date" name="txtNextVisit" id="txtNextVisit" value="<?php echo date('Y-m-d',strtotime($objTransactionMaster->nextVisit)); ?>">
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>

									<!-- Resultado -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Resultado</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtResult" id="txtResult" value="<?php echo $objTransactionMaster->reference1;  ?>"></input>
										</div>
									</div>

									<!-- Evaluacion -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Evaluacion</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtEvaluation" id="txtEvaluation" value="<?php echo $objTransactionMaster->reference2;  ?>"></input>
										</div>
									</div>

									<!-- Recomendacion -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Recomendacion</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtRecomendation" id="txtRecomendation" value="<?php echo $objTransactionMaster->reference3;  ?>"></input>
										</div>
									</div>

									<!-- Diagnostico -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Diagnostico</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtDiagnostic" id="txtDiagnostic" value="<?php echo $objTransactionMaster->reference4;  ?>"></input>
										</div>
									</div>

								
							</div>							
						</div>
					</div>
					
					<div class="tab-pane fade" id="details">
					
						<div class="row">
							<div class="col-md-12">
								<table class="table" id="tableFrecuency">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>Prioridad</th>
										<th>Frecuencia</th>
										<th>Dosis</th>
										<th>Monto</th>
										<th>Acción</th>
									</tr>
								</thead>
								<tbody>
									<tr class="" id="filaEntrada">
										<td>
											<input class="form-control"  type="text"  name="txtDetailName" id="txtDetailName" value="">
											<label id="errorLabel" class="text-danger">Este campo no puede estar vacío</label>
										</td>
										<td>
											<label class="sr-only" for="txtPriority">Prioridad:</label>
											<select name="txtPriority" id="txtPriority" class="select2">
													<?php
													$count = 0;
													if($objListPriority){
														foreach($objListPriority as $ws){
															if($count == 0 )
																echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
															else
																echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
															$count++;
														}
													}
													?>
											</select>
										</td>
										<td>
											<label class="sr-only" for="txtFrecuency">Frecuencia:</label>
											<select name="txtFrecuency" id="txtFrecuency" class="select2">
													<?php
													$count = 0;
													if($objListFrecuency){
														foreach($objListFrecuency as $ws){
															if($count == 0 )
																echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
															else
																echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
															$count++;
														}
													}
													?>
											</select>
										</td>
										<td>
											<label class="sr-only" for="txtDose">Dosis:</label>
											<select name="txtDose" id="txtDose" class="select2">
													<?php
													$count = 0;
													if($objListDose){
														foreach($objListDose as $ws){
															if($count == 0 )
																echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
															else
																echo "<option value='".$ws->catalogItemID."'  >".$ws->name."</option>";
															$count++;
														}
													}
													?>
											</select>
										</td>
										<td>
											<label class="sr-only" for="txtAmount">Monto:</label>
											<input class="form-control" type="number"  name="txtAmount" id="txtAmount" value="">
										</td>
										<td>
											<a href="#" class="btn btn-flat btn-info" id="btnAddDetail"><i class="fas fa-plus"></i></a>
										</td>
									</tr>
									<!-- Aquí se agregarán las filas dinámicamente -->
									 
									<!-- Filas dinámicas obtenidas de la base de datos -->
									<?php
									 if($objListDetails){
										foreach($objListDetails as $value):
										?>
											<tr>
												<td>
													<input type="hidden" name="customerMasterDetails[]" value="<?= $value->transactionMasterDetailID?>" />
													<input class="form-control" type="text" name="txtDetailNameArray[]" value="<?= $value->itemNameLog ?>">
												</td>
												<td>
													<select name="txtPriorityArray[]" class="select2">
														<?php
															if($objListPriority){
																foreach($objListPriority as $ws){
														?>
																<option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID == $value->skuQuantity ? 'selected' : '' ?> ><?=$ws->name?></option>
														<?php
																}
															}
														?>
													</select>
												</td>
												<td>
												<select name="txtFrecuencyArray[]" class="select2">
													<?php
														if($objListFrecuency){
															foreach($objListFrecuency as $ws){
													?>
															<option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID == $value->skuQuantityBySku ? 'selected' : '' ?> ><?=$ws->name?></option>
													<?php
															}
														}
													?>
												</select>
												</td>
												<td>
												<select name="txtDoseArray[]" class="select2">
													<?php
														if($objListDose){
															foreach($objListDose as $ws){
													?>
															<option value='<?=$ws->catalogItemID?>' <?= $ws->catalogItemID == $value->typePriceID ? 'selected' : '' ?> ><?=$ws->name?></option>
													<?php
															}
														}
													?>
												</select>
												</td>
												<td>
													<label class="sr-only" for="txtAmount">Monto:</label>
													<input class="form-control" type="number" name="txtAmountArray[]" value="<?= $value->amount ?>">
												</td>
												<td>
													<button type="button" class="btn btn-flat btn-danger" onclick="fnEliminarFila(this)">
														<i class="fas fa-trash"></i>
													</button>
												</td>
											</tr>
									<?php
										endforeach;
									 }
									 ?>
								</tbody>
								</table>
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
