<div class="row"> 
	<div id="email" class="col-lg-12">
	
		<!-- botonera -->
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo base_url(); ?>/app_box_attendance/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
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
			
				<ul id="myTab" class="nav nav-tabs">
					<li class="active">
						<a href="#home" data-toggle="tab">Informacion</a>
					</li>					
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane fade in active" id="home">	
						<div class="row">										
							<div class="col-lg-12">
								
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
										<label class="col-lg-4 control-label" for="selectFilter">Huella</label>
										<div class="col-lg-8">
						
											<div class="btn-group">
												<button class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Lectura </font></font><span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="#" id="btnScanerHuella"   ><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Abrir Sensor</font></font></a></li>
												</ul>
											</div>

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
											<label class="col-lg-4 control-label" for="normal">Solvencia</label>
											<div class="col-lg-8">																	
												<input class="form-control"  type="text"  name="txtDetailReference1" id="txtDetailReference1" value="" readonly="true">												
											</div>
									</div>
									
								
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Proximo Pago</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDetailReference2" id="txtDetailReference2"  readonly="true" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>		
									
									<div class="form-group">
											<label class="col-lg-4 control-label" for="normal">Dias Prox. Pago</label>
											<div class="col-lg-8">																	
												<input class="form-control"  type="text"  name="txtDetailReference4" id="txtDetailReference4" value="" readonly="true">												
											</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Vencimiento</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDetailReference3" id="txtDetailReference3" readonly="true" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
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
