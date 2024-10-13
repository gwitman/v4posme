<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo base_url(); ?>/app_med_query/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
					<h4>ASISTENCIA:#<span class="invoice-num">00000000</span></h4>
			</div>
			<!-- /titulo de comprobante-->
			
			<!-- body -->	
			<form id="form-new-invoice" name="form-new-invoice" class="form-horizontal" role="form">
			<div class="panel-body printArea"> 
			<!--tab menu-->
				<ul id="myTab" class="nav nav-tabs">
					<li class="active">
						<a href="#home" data-toggle="tab">Informacion</a>
					</li>		
					<li>
						<a href="#details" data-toggle="tab">Detalles</a>
					</li>			
				</ul>
			<!--tab menu-->

				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
							<div class="col-lg-12">
								
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="date" name="txtDate" id="txtDate" >
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
														if($count == 0)
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
												<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomerDefault->entityID;  ?>">
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
											<textarea class="form-control" type="text"  name="txtNote" id="txtNote" ></textarea>
										</div>
									</div>
								
								<!-- New inputs -->
									
									<!-- Edad-->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Edad(años)</label>
										<div class="col-lg-8">
											<input class="form-control" type="number"  name="txtAge" id="txtAge" ></input>
										</div>
									</div>

									<!-- Altura-->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Altura(cm)</label>
										<div class="col-lg-8">
											<input class="form-control" type="number"  name="txtHeight" id="txtHeight"></input>
										</div>
									</div>

									<!-- Peso -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Peso(kg)</label>
										<div class="col-lg-8">
											<input class="form-control" type="number"  name="txtWeight" id="txtWeight" ></input>
										</div>
									</div>
								
									<!-- IMC -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">IMC</label>
										<div class="col-lg-8">
											<input class="form-control" readonly id="txtIMC"  type="number"></input>
											<span class="badge badge-info" >bajo de 18.5 , bajo de peso</span>
											<span class="badge badge-success" >18.5 a 24.9 peso noral</span>
											<span class="badge badge-warning" >25 a 29.9 sobre peso</span>
											<span class="badge badge-important" >30 a mas obesidad</span>
										</div>
									</div>
									
									<!-- Proxima visita -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Proxima Visita</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="date" name="txtNextVisit" id="txtNextVisit" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>

									<!-- Resultado -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Resultado</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtResult" id="txtResult" ></input>
										</div>
									</div>

									<!-- Evaluacion -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Evaluacion</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtEvaluation" id="txtEvaluation" ></input>
										</div>
									</div>

									<!-- Recomendacion -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Recomendacion</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtRecomendation" id="txtRecomendation" ></input>
										</div>
									</div>

									<!-- Diagnostico -->
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Diagnostico</label>
										<div class="col-lg-8">
											<input class="form-control" type="text"  name="txtDiagnostic" id="txtDiagnostic" ></input>
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
