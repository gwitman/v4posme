	 <!-- Content wrapper -->
	  <div class="content-wrapper">
		<!-- Content -->

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
				
					<form role="form" action="<?php echo base_url(); ?>/core_user/savepublic" method="POST" enctype="multipart/form-data"  >
					
							<div class="row">
								
								<div class="col-md-4 my-2 px-4" >
									<label for="html5-email-input" class="form-label">Tipo de servicio</label>
									<select id="txtTransactionMasterReference1" class="select2 form-select form-select-lg" data-live-search="true" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-text-input" class="col-form-label">Servicio Existente</label>
									<select id=txtTransactionMasterReference2 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-form-label">Servicio</label>
									<select id=txtTransactionMasterReference3 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-tel-input" class="col-md-6 col-form-label">Tecnologia</label>
									<select id=txtTransactionMasterReference4 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>
							  
								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Velocidad Internet</label>
									<select id=txtTransactionMasterReference5 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>
							  
								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Numero Contrato Fijo</label>
									<input type="text" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp">
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Numero orden</label>
									<input type="text" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp">
								</div>
							  
								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Gestion Movil</label>
									<select id=txtTransactionMasterReference6 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Plan Movil</label>
									<select id=txtTransactionMasterReference7 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Numero Contrato Movil</label>
									<select id=txtTransactionMasterReference8 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>
	
								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Linea a Portar o Migrar</label>
									<select id=txtTransactionMasterReference9 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>
								
								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Tipo de Linea</label>
									<select id=txtTransactionMasterReference10 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>
								
								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Operador donante</label>
									<select id=txtTransactionMasterReference11 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Linea Nueva o Provincial</label>
									<select id=txtTransactionMasterReference12 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Tipo de Migracion</label>
									<select id=txtTransactionMasterReference13 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Lineas Moviles adicionales</label>
									<select id=txtTransactionMasterReference14 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Gestion Movil adicional</label>
									<select id=txtTransactionMasterReference15 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>
								

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Plan Movil Adicional</label>
									<select id=txtTransactionMasterReference16 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Linea a Portar o Migrar Adicional</label>
									<input type="text" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp">
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Linea Nueva o Provisional Adicional</label>
									<input type="text" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp">
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">RGus Fijos</label>
									<select id=txtTransactionMasterReference17 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">RGus Moviles</label>
									<select id=txtTransactionMasterReference18 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>
								
								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Total RGUs</label>
									<select id=txtTransactionMasterReference19 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Renta Fijo</label>
									<select id=txtTransactionMasterReference20 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Renta Movil</label>
									<select id=txtTransactionMasterReference21 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Renta Total</label>
									<select id=txtTransactionMasterReference22 class="select2 form-select form-select-lg" data-allow-clear="true">
										<option value="AK">Alaska</option>
										<option value="HI">Hawaii</option>
										<option value="CA">California</option>
									</select>
								</div>

								<div class="col-md-4 my-2 px-4">
									<label for="html5-email-input" class="col-md-6 col-form-label">Observaciones Servicio</label>
									<input type="text" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp">
								</div>

								

							</div>

							</br>

							<button type="button" class="btn btn-success mx-3">Guardar</button>
							<button type="button" class="btn btn-warning mx-3">Limpiar</button>

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

	  