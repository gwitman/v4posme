	  <!-- Content wrapper -->
	  <div class="content-wrapper">
		<!-- Content -->

		<div class="container-xxl flex-grow-1 container-p-y">
		  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Agenda /</span> servicio</h4>


		  <!-- Examples -->
		  <div class="row mb-5">
		
			<div class="col-md-12 col-lg-4 mb-6">
			  <?php echo $message; ?>
			
			  <div class="card h-100">
				<div class="card-body">
				  <h5 class="card-title">posMe</h5>
				  <h6 class="card-subtitle text-muted">Pago de usuario</h6>
				</div>
				
				
				<img class="img-fluid" src="<?php echo base_url(); ?>/resource/img/logo_posme.jpg" alt="Card image cap" />
				
				<div class="card-body">
				
					<form role="form" action="<?php echo base_url(); ?>/core_user/payment_user" method="POST" enctype="multipart/form-data"  >
						   
							  <div class="mb-12 row">
								<label for="html5-email-input" class="col-md-6 col-form-label">Email</label>
								<div class="col-md-6">
								  <input class="form-control" type="email" value="" id="txtEmail" name="txtEmail" />
								</div>
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
							  		  Terminos y condiciones
							  		</button>
							  	  </h2>
							  	  <div id="accordionWithIcon-2" class="accordion-collapse collapse">
							  		<div class="accordion-body">
							  		  
							  		  
							  			 </br>
							  
							  			<ol>
							  				<li><strong>Aceptación de Términos:</strong> Al acceder y utilizar este sitio web, usted acepta estar sujeto a estos términos y condiciones de uso. Si no está de acuerdo con alguno de estos términos, por favor, no utilice el sitio.</li>
							  
							  				<li><strong>Productos y Servicios:</strong> Describimos y presentamos nuestros productos de la manera más precisa posible. Sin embargo, no garantizamos que la información sea libre de errores.</li>
							  
							  				<li><strong>Precios y Pagos:</strong> Los precios están sujetos a cambios sin previo aviso. Nos reservamos el derecho de modificar o discontinuar productos sin previo aviso. Los pagos se procesarán de manera segura a través de métodos establecidos.</li>
							  
							  				<li><strong>Envíos y Entregas:</strong> Consulte nuestra política de envíos para obtener información detallada sobre costos y tiempos de entrega.</li>
							  
							  				<li><strong>Devoluciones y Reembolsos:</strong> Revise nuestra política de devoluciones para obtener información sobre cómo manejar devoluciones y solicitar reembolsos.</li>
							  
							  				<li><strong>Propiedad Intelectual:</strong> El contenido del sitio, incluyendo textos, imágenes y logotipos, está protegido por derechos de autor y otras leyes de propiedad intelectual.</li>
							  
							  				<li><strong>Privacidad:</strong> Su privacidad es importante para nosotros. Consulte nuestra política de privacidad para comprender cómo manejamos la información del usuario.</li>
							  
							  				<li><strong>Ley Aplicable:</strong> Estos términos y condiciones están sujetos a las leyes del lugar de operación de la empresa.</li>
							  
							  				<li><strong>Modificaciones:</strong> Nos reservamos el derecho de actualizar y modificar estos términos en cualquier momento. Se alienta a los usuarios a revisar periódicamente esta página.</li>
							  			</ol>
							  		  
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