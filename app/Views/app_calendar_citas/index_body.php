	  <!-- Content wrapper -->
	  <div class="content-wrapper">
		<!-- Content -->
		<div class="container-xxl flex-grow-1 container-p-y">
		  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Agenda /</span> lista de citas</h4>
		  <!-- Examples -->
		  <div class="row mb-5">
			<div class="col-md-12 col-lg-4 mb-6">
				
			  <div class="card h-100">
				<div class="card-body">
				  <h5 class="card-title">posMe</h5>
				  <h6 class="card-subtitle text-muted"><?php echo $company->name; ?></h6>
				</div>
				<div class="card-body">
					
					<div class="mb-12 row">
						<a href="<?php echo base_url()."/core_dashboards/index"; ?>" class="btn rounded-pill btn-outline-success">regresar</a>
					</div>
							  
					</br>
							  
							  
							  
					<ul class="timeline timeline-dashed mt-3">
					
						<?php 
							if($dataViewData["view_data"])
							foreach($dataViewData["view_data"] as $item)
							{
								
								?>
								
								<li class="timeline-item timeline-item-danger mb-4">
									  <span class="timeline-indicator timeline-indicator-danger">
										<i class="bx bx-bell"></i>
									  </span>
									  <div class="timeline-event">
											<div class="timeline-header">
											  <h6 class="mb-0">1 <?php echo $item["title"];?></h6>
											  <small class="text-muted"><?php echo $item["programDate"];?></small>
											</div>
											<ul class="list-group list-group-flush">
												  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0" >
														<div class="d-flex flex-wrap align-items-center">                                  
														  <span>tarea a realizar.</span>
														</div>
														<a href="<?php echo base_url()."/app_calendar_citas/delete/notificationID/".$item["notificationID"]; ?>" class="btn btn-primary btn-sm my-sm-0 my-3">rechazar</a>
												  </li>
												  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap pb-0 px-0">
														<div class="d-flex flex-sm-row flex-column align-items-center">
														  
														  <div class="user-info">
															<p class="my-0"><?php echo $item["message"];?></p>
															<span class="text-muted"><?php echo $item["programHour"];?></span>
														  </div>
														</div>
														<h5 class="mb-0">---</h5>
												  </li>
											</ul>
											
											<a href="javascript:void(0)"><i class="bx bx-link"></i>---.---</a>
										
									  </div>
								</li>
								
								<?php 								
							}
						?>                       
					</ul>
				</div>
			  </div>
			</div>
		
		  </div>
		  <!-- Examples -->
		</div>
		<!-- / Content -->
	  </div>
	  <!-- Content wrapper -->