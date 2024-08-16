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
				
				
				
				
				<div class="card-body">
				
					<form role="form" action="<?php echo base_url(); ?>/core_user/savepublic" method="POST" enctype="multipart/form-data"  >
					
							  <div class="mb-12 row">
								<label for="html5-text-input" class="col-md-6 col-form-label">Tipo de servicio</label>
								<div class="col-md-6">
								  <select id="select2Basic" class="select2 form-select form-select-lg" data-allow-clear="true">
									<option value="AK">Alaska</option>
									<option value="HI">Hawaii</option>
									<option value="CA">California</option>
									<option value="NV">Nevada</option>
									<option value="OR">Oregon</option>
									<option value="WA">Washington</option>
									<option value="AZ">Arizona</option>
									<option value="CO">Colorado</option>
									<option value="ID">Idaho</option>
									<option value="MT">Montana</option>
									<option value="NE">Nebraska</option>
									<option value="NM">New Mexico</option>
									<option value="ND">North Dakota</option>
									<option value="UT">Utah</option>
									<option value="WY">Wyoming</option>
									<option value="AL">Alabama</option>
									<option value="AR">Arkansas</option>
									<option value="IL">Illinois</option>
									<option value="IA">Iowa</option>
									<option value="KS">Kansas</option>
									<option value="KY">Kentucky</option>
									<option value="LA">Louisiana</option>
									<option value="MN">Minnesota</option>
									<option value="MS">Mississippi</option>
									<option value="MO">Missouri</option>
									<option value="OK">Oklahoma</option>
									<option value="SD">South Dakota</option>
									<option value="TX">Texas</option>
									<option value="TN">Tennessee</option>
									<option value="WI">Wisconsin</option>
									<option value="CT">Connecticut</option>
									<option value="DE">Delaware</option>
									<option value="FL">Florida</option>
									<option value="GA">Georgia</option>
									<option value="IN">Indiana</option>
									<option value="ME">Maine</option>
									<option value="MD">Maryland</option>
									<option value="MA">Massachusetts</option>
									<option value="MI">Michigan</option>
									<option value="NH">New Hampshire</option>
									<option value="NJ">New Jersey</option>
									<option value="NY">New York</option>
									<option value="NC">North Carolina</option>
									<option value="OH">Ohio</option>
									<option value="PA">Pennsylvania</option>
									<option value="RI">Rhode Island</option>
									<option value="SC">South Carolina</option>
									<option value="VT">Vermont</option>
									<option value="VA">Virginia</option>
									<option value="WV">West Virginia</option>
								  </select>
								</div>
							  </div>
							  
							  <div class="mb-12 row">
								<label for="html5-text-input" class="col-md-6 col-form-label">Comercio</label>
								<div class="col-md-6">
								  <input class="form-control" type="text" value="" id="txtComercio" name="txtComercio"  />
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
								<label for="formFile" class="form-label">Portada</label>
								<input class="form-control" type="file" id="formFilePortada" name="formFilePortada" />
							  </div>
							  
							  </br>
							  
							  <div class="accordion mt-3" id="accordionWithIcon">
							  	
							  
							  	<div class="accordion-item card">
							  	  <h2 class="accordion-header d-flex align-items-center">
							  		<button
							  		  type="button"
							  		  class="accordion-button collapsed"
							  		  data-bs-toggle="collapse"
							  		  data-bs-target="#accordionWithIcon-2"
							  		  aria-expanded="false"
							  		>
							  		  <i class="bx bx-briefcase me-2"></i>
							  		  Políticas de privacidad
							  		</button>
							  	  </h2>
							  	  <div id="accordionWithIcon-2" class="accordion-collapse collapse">
							  		<div class="accordion-body">
							  		  
							  		  
										</br>
							  
											<p>
												Bienvenido a posMe. Estas son nuestras políticas de privacidad que rigen la recopilación y el uso de
												información en nuestra aplicación.
											</p>

											<h3>Información que Recopilamos</h3>
											<p>
												En posMe, recopilamos la información que nos proporcionas al utilizar nuestra aplicación, como tu nombre,
												dirección de correo electrónico y otra información relevante para mejorar nuestros servicios.
											</p>

											<h3>Uso de la Información</h3>
											<p>
												La información recopilada se utiliza para personalizar tu experiencia en posMe y mejorar nuestros
												servicios. No compartimos tu información personal con terceros sin tu consentimiento.
											</p>

											<!-- Agrega más secciones según sea necesario -->

											<h3>Seguridad</h3>
											<p>
												Implementamos medidas de seguridad para proteger tu información personal y asegurarnos de que se utilice
												de acuerdo con estas políticas.
											</p>
											
											<h3>Beneficio</h3>
											<p>
												En posMe, nuestras políticas de privacidad están diseñadas con el único propósito de mejorar tu 
												experiencia en la aplicación. Buscamos proporcionarte un servicio eficiente y personalizado. Además, 
												para ofrecerte la mejor atención, te permitimos recibir notificaciones en tiempo real en tu teléfono de 
												la manera más rápida posible. Tu comodidad y satisfacción son nuestras prioridades.
											</p>

											<h3>Contacto</h3>
											<p>
												Si tienes alguna pregunta o inquietud acerca de nuestras políticas de privacidad, por favor contáctanos
												en <a href="mailto:cliente@posme.com">cliente@posme.com</a>.
											</p>
										
										
							  		  
							  			<div class="form-check form-switch mb-2">
							  				<input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="flexSwitchCheckDefault" />
							  				<label class="form-check-label" for="flexSwitchCheckDefault"
							  				  >Acepto</label
							  				>
							  			</div>
							  		  
							  		</div>
							  	  </div>
							  	</div>
							  
							  	
							  </div>
							  
							  </br>
							  
																		
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