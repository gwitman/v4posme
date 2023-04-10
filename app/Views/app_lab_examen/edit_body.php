<!-- botonera -->
<div class="row"> 
	<div id="email" class="col-lg-12">
		<div class="email-bar" style="border-left:1px solid #c9c9c9">                                
			<div class="btn-group pull-right">                                    
				<a href="<?php echo base_url(); ?>/app_lab_examen/index" id="btnBack" class="btn btn-inverse" ><i class="icon16 i-rotate"></i> Atras</a>                                    
				<a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>									
				<a href="#" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
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
					<h4>NUMERO:#
						<span class="invoice-num">
						<?php 
							echo helper_RequestGetValueObjet($objTransactionMaster,"transactionNumber",0);
						?>
						</span>
					</h4>
			</div>
			<!-- /titulo del movimiento-->
			
			<form id="form-new-transaction" name="form-new-invoice" class="form-horizontal" role="form">
				<div class="panel-body"> 
					<ul id="myTab" class="nav nav-tabs">
						<li class="active">
							<a href="#home" data-toggle="tab">Informacion</a>
						</li>						
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="home">	
							<div class="row">										
								<div class="col-lg-6">
									<input type="hidden" id="txtCompanyID" name="txtCompanyID" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"companyID",0); ?>">
									<input type="hidden" id="txtTransactionID" name="txtTransactionID" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionID",0); ?>">
									<input type="hidden" id="txtTransactionMasterID" name="txtTransactionMasterID" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionMasterID",0); ?>">
									<input type="hidden" id="txtComando" name="txtComando" value="view" >
									<input type="hidden" id="txtStatusOld" name="txtStatusOld" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"statusID",0) ?>" >
									 
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="datepicker">Fecha</label>
										<div class="col-lg-8">
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" type="text" name="txtDate" id="txtDate" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionOn","2023-01-01"); ?>" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-lg-4 control-label" for="normal">Descripcion</label>
										<div class="col-lg-8">										
											<input class="form-control"   type="text" name="txtNote" id="txtNote" value="<?php echo helper_RequestGetValueObjet($objTransactionMaster,"note","..."); ?>">
										</div>
									</div>
									
								
								</div>
								
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-lg-4 control-label" for="buttons">Paciente</label>
										<div class="col-lg-8">
											<div class="input-group">
												<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php 
													echo 
													helper_RequestGetValueObjet(
														$objTransactionMaster,
														"entityID",
														helper_RequestGetValueObjet(
															$objCustomerDefault,
															"entityID",
															0
														)
													);  
												?>">
												
												<input class="form-control" readonly id="txtCustomerDescription" type="txtCustomerDescription" 
												value="<?php 
														echo 
															helper_RequestGetValueObjet($objCustomerDefault,"customerNumber","").
															" ".
															helper_RequestGetValueObjet($objNaturalDefault,"firstName","");
														?>">
														
												
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
										<label class="col-lg-4 control-label" for="selectFilter">Examen</label>
										<div class="col-lg-8">
											<select name="txtExamenID" id="txtExamenID" class="select2">
													<option></option>																
													<?php
													$count = 0;
													if($objListExamenes)
													foreach($objListExamenes as $z){
														if($z->catalogItemID  == helper_RequestGetValueObjet($objTransactionMaster,"reference3",$examenId) )
															echo "<option data-urlprinter='".$z->description."' value='".$z->catalogItemID."' selected >".$z->display."</option>";
														else
															echo "<option data-urlprinter='".$z->description."' value='".$z->catalogItemID."'  >".$z->display."</option>";
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
													$counter = 0;
													if($objListWorkflowStage)
													foreach($objListWorkflowStage as $ws){
														$counter++;
														if(
															$ws->workflowStageID == helper_RequestGetValueObjet($objTransactionMaster,"statusID",0) 
															||
															$counter == 1 && helper_RequestGetValueObjet($objTransactionMaster,"statusID",0) == 0 
														)
															echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
														else 
															echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
													}
													?>
											</select>
										</div>
									</div>
									
									
									
								</div>
								
							</div>
						</div>
					</div class="*/tab-content">
					
					
					<div class="row">
						<div class="col-lg-12">
							<h3>Detalle:</h3>
							
							<table id="tb_transaction_master_detail" class="table table-bordered">
								<thead>
								  <tr>
									<th>transactionMasterDetailID</th>
									<th>catalogItemID</th>
									<th>Nombre</th>
									<th>Valor</th>
								  </tr>
								</thead>
								<tbody id="body_detail_transaction">             
								</tbody>
							</table>
							
						</div>
					</div>
				</div>
			</form>
			
		</div>
	</div>
</div>