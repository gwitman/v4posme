	 <!-- Content wrapper -->
	  <div class="content-wrapper">
		<!-- Content -->

		<!-- Toast with Animation -->
		<div
	 		id="toast-ex"
			class="bs-toast toast animate__animated my-2 position-absolute top-0 end-0"
			role="alert"
			aria-live="assertive"
			aria-atomic="true"
			data-bs-delay="3000"
			>
			<div class="toast-header">
				<i class="bx bx-bell me-2"></i>
				<div class="me-auto fw-semibold">posMe</div>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body"></div>
			</div>
			<!--/ Toast with Animation -->
		<div class="container-xxl flex-grow-1 container-p-y">
		  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Detalle de servicio /</span> ...</h4>


		  <!-- Examples -->
		  <div class="row mb-12">
		
			<div class="col-md-12 col-lg-12 mb-6">
			  <?php echo $message; ?>
			
			  <div class="card h-100">
				<div class="card-body">
				  <h5 class="card-title">Formulario</h5>
				  <h6 class="card-subtitle text-muted">Nuevo</h6>
				</div>

				<div class="card-bodyi">
				
					<form id="frmPublic" role="form" action="<?= base_url(); ?>/app_form_public/save" method="POST" enctype="multipart/form-data">
							<div class="container-fluid">
								<div class="row">
									
									<div class="col-md-4 my-2 px-4" >
										<label for="txtTransactionMasterReference1" class="col-form-label">Tipo de servicio</label>
										<select id="txtTransactionMasterReference1" name="txtTransactionMasterReference1" 
												class="select2 form-select form-select-lg" data-live-search="true" data-allow-clear="true">
											<option value="" selected>Seleccione una opción...</option>
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference2" class="col-form-label">Servicio Existente</label>
										<select id="txtTransactionMasterReference2" name="txtTransactionMasterReference2" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference3" class="col-form-label">Servicio</label>
										<select id="txtTransactionMasterReference3" name="txtTransactionMasterReference3" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>
								
									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference4" class="col-form-label">Tecnologia</label>
										<select id="txtTransactionMasterReference4" name="txtTransactionMasterReference4" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>
								
									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference5" class="col-form-label">Velocidad Internet</label>
										<select id="txtTransactionMasterReference5" name="txtTransactionMasterReference5" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>
								
									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference6" class="col-form-label">Gestion Movil</label>
										<select id="txtTransactionMasterReference6" name="txtTransactionMasterReference6" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference7" class="col-form-label">Plan Movil</label>
										<select id="txtTransactionMasterReference7" name="txtTransactionMasterReference7" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>
									
									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference8" class="col-form-label">Tipo de Linea</label>
										<select id="txtTransactionMasterReference8" name="txtTransactionMasterReference8" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>
									
									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference9" class="col-form-label">Operador donante</label>
										<select id="txtTransactionMasterReference9" name="txtTransactionMasterReference9" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference10" class="col-form-label">Tipo de Migracion</label>
										<select id="txtTransactionMasterReference10" name="txtTransactionMasterReference10" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference11" class="col-form-label">Lineas Moviles adicionales</label>
										<select id="txtTransactionMasterReference11" name="txtTransactionMasterReference11" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference12" class="col-form-label">Gestion Movil adicional</label>
										<select id="txtTransactionMasterReference12" name="txtTransactionMasterReference12" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference13" class="col-form-label">Plan Movil Adicional</label>
										<select id="txtTransactionMasterReference13" name="txtTransactionMasterReference13" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference14" class="col-form-label">RGus Fijos</label>
										<select id="txtTransactionMasterReference14" name="txtTransactionMasterReference14" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference15" class="col-form-label">RGus Moviles</label>
										<select id="txtTransactionMasterReference15" name="txtTransactionMasterReference15" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>
								
									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference16" class="col-form-label">Renta Fijo</label>
										<select id="txtTransactionMasterReference16" name="txtTransactionMasterReference16" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

									<div class="col-md-4 my-2 px-4">
										<label for="txtTransactionMasterReference17" class="col-form-label">Renta Movil</label>
										<select id="txtTransactionMasterReference17" name="txtTransactionMasterReference17" class="select2 form-select form-select-lg" data-allow-clear="true">
										</select>
									</div>

								</div>
							</div>
							</br>
							<button type="submit" class="btn btn-success mx-3">Guardar</button>
							<button type="button" id="btnLimpiar" class="btn btn-warning mx-3">Limpiar</button>
							</br></br>
							  
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

	  