	  <!-- Content wrapper -->
	  <div class="content-wrapper">
		<!-- Content -->

		<div class="container-xxl flex-grow-1 container-p-y">
		  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Agenda /</span> nueva visita</h4>


		  <!-- Examples -->
		  <div class="row mb-5">
		
			<div class="col-md-12 col-lg-4 mb-6">
			  <?php echo $message; ?>
			
			  <div class="card h-100">
				<div class="card-body">
				  <h5 class="card-title">posMe</h5>
				  <h6 class="card-subtitle text-muted">Selecciona el dia disponible</h6>
				</div>
				
				<img class="img-fluid" src="<?php echo base_url(); ?>/resource/file_company/company_2/component_8/component_item_<?php echo $objItemUser->userID."/".$objItemUser->foto; ?>" alt="Card image cap" />
				
				
				<div class="card-body">
				
					<form role="form" action="<?php echo base_url(); ?>/app_config_noti/save/newpublic" method="POST" >
							  <div class="mb-12 row">
								<label for="html5-text-input" class="col-md-6 col-form-label">Nombre</label>
								<div class="col-md-6">
								  <input class="form-control" type="text" value="" id="txtNombre" name="txtNombre"  />
								</div>
							  </div>
						   
							  <div class="mb-12 row">
								<label for="html5-email-input" class="col-md-6 col-form-label">Email</label>
								<div class="col-md-6">
								  <input class="form-control" type="email" value="" id="txtEmail" name="txtEmail" />
								</div>
							  </div>
							 
							  <div class="mb-12 row">
								<label for="html5-tel-input" class="col-md-6 col-form-label">Telefono</label>
								<div class="col-md-6">
								  <input class="form-control" type="tel" value="" id="txtTelefono" name="txtTelefono" />
								</div>
							  </div>
							
						 
							  <div class="mb-12 row">
								<label for="html5-date-input" class="col-md-6 col-form-label">Fecha</label>
								<div class="col-md-6">
								  <input class="form-control" type="date" value="" id="txtDate" name="txtDate" />
								</div>
							  </div>
							
							  <div class="mb-12 row">
								<label for="html5-time-input" class="col-md-6 col-form-label">Hora</label>
								<div class="col-md-6">
								  <input class="form-control" type="time" value="" id="txtHora" name="txtHora"  />
								</div>
							  </div>
							  
							  </br>
							  
							  <div class="mb-12 row">
								<label for="html5-text-input" class="col-md-6 col-form-label">Descripción</label>
								<div class="col-md-6">
								  <input class="form-control" type="text" value="" id="txtDescription" name="txtDescription"   />
								</div>
							  </div>
							  
							  <div class="mb-12 row">
								<label for="html5-time-input" class="col-md-6 col-form-label">Tienda</label>
								<div class="col-md-6">
									<select id="txtUserID" name="txtUserID" class="form-select">
									  <?php
									  $count=0;
									  if($objListUser)
									  foreach($objListUser as $user)
								      {
										  if($user->userID != APP_USERADMIN)
										  {
											  if($count == 0)
												echo "<option value='".$user->userID."' selected >".$user->nickname."</option>";
											  else 
												echo "<option value='".$user->userID."'  >".$user->nickname."</option>";
											
											  $count++;
										  }
									  }
									  ?>
									  
									</select>
									
								</div>
							  </div>
							  
							  </br>
							  
							  
							  <input type="hidden" id="txtTitulo" name="txtTitulo"  value="Programacion de cita" />
							  <input type="hidden" id="txtPeriodID" name="txtPeriodID"  value="<?php echo $objListPeriod[0]->catalogItemID;  ?>" />
							  <input type="hidden" id="txtStatusID" name="txtStatusID"  value="<?php echo $objListWorkflowStage[0]->workflowStageID;  ?>" />
							  <input type="hidden" id="txtBusiness" name="txtBusiness"  value="<?php echo $business;  ?>" />
							  
																		
							  <div class="mb-12 row">
								<button type="submit" class="btn rounded-pill btn-outline-success">Enviar</button>
							  </div>
							  
					</form>
				</div>
			  </div>
			</div>
		
		  </div>
		  <!-- Examples -->
		</div>
		<!-- / Content -->
	  </div>
	  <!-- Content wrapper -->