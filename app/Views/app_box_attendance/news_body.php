<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

	<!-- encabezado y acciones -->
	<div class="pv2-card pv2-header">
		<div class="pv2-header-info">
			<div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
			<div>
				<p class="pv2-header-title">ASISTENCIA:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
				<p class="pv2-header-sub">Registra una nueva asistencia</p>
			</div>
		</div>
		<div class="pv2-actions">
			<a href="<?php echo base_url(); ?>/app_box_attendance/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
			<a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
		</div>
	</div>
	<!-- /encabezado y acciones -->

			<!-- body -->
			<form id="form-new-invoice" name="form-new-invoice" role="form" autocomplete="off">

					<div class="pv2-card" id="home">
						<p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Información</p>	
						<div class="row">										
							<div class="col-lg-12">
								
									<div class="form-group">
										<label class="pv2-label" for="txtDate">Fecha</label>
										<div>
											<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
												<input size="16"  class="form-control" autocomplete="off" type="text" name="txtDate" id="txtDate" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>							
									
									<div class="form-group">
										<label class="pv2-label" for="txtPriorityID">Prioridad</label>
										<div>
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
										<label class="pv2-label" for="txtStatusID">Estado</label>
										<div>
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
										<label class="pv2-label">Huella</label>
										<div>
						
											<div class="btn-group">
												<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Lectura <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="#" id="btnScanerHuella"   >Abrir Sensor</a></li>
												</ul>
											</div>

										</div>
									</div>
									
									
									<div class="form-group">
										<label class="pv2-label" for="txtCustomerDescription">Cliente</label>
										<div>
											<div class="input-group">
												<input type="hidden" id="txtCustomerID" name="txtCustomerID" value="<?php echo $objCustomerDefault->entityID;  ?>">
												<input class="form-control" readonly id="txtCustomerDescription" type="text" value="<?php echo $objNaturalDefault != null ? strtoupper($objCustomerDefault->customerNumber . " ". $objNaturalDefault->firstName . " ". $objNaturalDefault->lastName ) : strtoupper($objCustomerDefault->customerNumber." ".$objLegalDefault->comercialName); ?>">
												
												<span class="input-group-btn">
													<button class="btn btn-danger" type="button" id="btnClearCustomer">
														<i aria-hidden="true" class="i-undo-2"></i>
														Limpiar
													</button>
												</span>
												<span class="input-group-btn">
													<button class="btn btn-primary" type="button" id="btnSearchCustomer">
														<i aria-hidden="true" class="i-search-5"></i>
														Buscar
													</button>
												</span>										
											</div>
										</div>
									</div>
								
								
									
									<div class="form-group">
											<label class="pv2-label" for="txtDetailReference1">Solvencia</label>
											<div>																	
												<input class="form-control" autocomplete="off" type="text"  name="txtDetailReference1" id="txtDetailReference1" value="" readonly="true">												
											</div>
									</div>
									
								
									
									<div class="form-group">
										<label class="pv2-label" for="txtDetailReference2">Próximo Pago</label>
										<div>
											<div class="input-group">
												<input size="16"  class="form-control" autocomplete="off" type="text" name="txtDetailReference2" id="txtDetailReference2"  readonly="true" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>		
									
									<div class="form-group">
											<label class="pv2-label" for="txtDetailReference4">Días Próx. Pago</label>
											<div>																	
												<input class="form-control" autocomplete="off" type="text"  name="txtDetailReference4" id="txtDetailReference4" value="" readonly="true">												
											</div>
									</div>
									
									
									
									<div class="form-group">
										<label class="pv2-label" for="txtDetailReference3">Vencimiento</label>
										<div>
											<div class="input-group">
												<input size="16"  class="form-control" autocomplete="off" type="text" name="txtDetailReference3" id="txtDetailReference3" readonly="true" >
												<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
											</div>
										</div>
									</div>		
								
							</div>
						
						</div>
					</div>

			</form>
			<!-- /body -->

	<!-- barra inferior (telefono) -->
	<div class="pv2-mbar">
		<div class="pv2-mbar-info">
			<p class="pv2-mbar-label">Asistencia</p>
			<p class="pv2-mbar-total">Nuevo</p>
		</div>
		<a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
	</div>
</div>
