<style>
#app { visibility: hidden; }
</style>


<div id='app' >
	
	<div v-if="mostrarAlerta"
         class="alert alert-danger alert-dismissible" 
         role="alert">
        {{ message }}
        <button type="button"
                class="btn-close"
                @click="mostrarAlerta = false"
                aria-label="Close"></button>
    </div>
	
   
	
	<div class="nav-align-top mb-4" >
		<ul class="nav nav-tabs nav-fill" role="tablist">
		  <li class="nav-item">
			<button
			  type="button"
			  class="nav-link active"
			  role="tab"
			  data-bs-toggle="tab"
			  data-bs-target="#navs-justified-home"
			  aria-controls="navs-justified-home"
			  aria-selected="true"
			>
			  <i class="tf-icons bx bx-home"></i> Chat
			  <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger">3</span>
			</button>
		  </li>
		  <li class="nav-item">
			<button
			  type="button"
			  class="nav-link"
			  role="tab"
			  data-bs-toggle="tab"
			  data-bs-target="#navs-justified-profile"
			  aria-controls="navs-justified-profile"
			  aria-selected="false"
			>
			  <i class="tf-icons bx bx-user"></i> Cliente
			</button>
		  </li>
		  
		  <li class="nav-item">
			<button
			  type="button"
			  class="nav-link"
			  role="tab"
			  data-bs-toggle="tab"
			  data-bs-target="#navs-justified-messages"
			  aria-controls="navs-justified-messages"
			  aria-selected="false"
			>
			  <i class="tf-icons bx bx-message-square"></i> Messages
			</button>
		  </li>
		  
		</ul>
		<div class="tab-content">
		  <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
				<!-- Activity Timeline -->
				  
					<div class="card-header align-items-center">
					  <h5 class="card-action-title mb-0"><i class="bx bx-list-ul me-2"></i>Ultimos mensajes</h5>
					  <div class="card-action-element">
						
					  </div>
					</div>
					<div class="card-body">
					  <ul class="timeline ms-2">
					  
						<li 
							v-for="objNotification in objListNotification"  
							class="timeline-item timeline-item-transparent"
						>
						  <span class="timeline-point timeline-point-warning"></span>
						  <div class="timeline-event">
							<div class="timeline-header mb-1">
							  <h6 class="mb-0">Client Meeting</h6>
							  <small class="text-muted">2026-01-04 04:00 P.M</small>
							</div>
							<p class="mb-2">Project meeting with john @10:15am</p>
							<div class="d-flex flex-wrap">
							  <div class="avatar me-3">
								<img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle" />
							  </div>
							  <div>
								<h6 class="mb-0">Lester McCarthy (Client)</h6>
								<span> {{ objNotification.message }}</span>
							  </div>
							</div>
						  </div>
						</li>
						
						
					  </ul>
					</div>
				  
				<!--/ Activity Timeline -->
		  </div>
		  <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
					
						
			  <div class="mb-3">
				<label for="exampleFormControlInput1" class="form-label">Nombre</label>
				<input
				  type="email"
				  class="form-control"
				  id="exampleFormControlInput1"
				  placeholder="name@example.com"
				/>
			  </div>
			  <div class="mb-3">
				<label for="exampleFormControlReadOnlyInput1" class="form-label">Telefono</label>
				<input
				  class="form-control"
				  type="text"
				  id="exampleFormControlReadOnlyInput1"
				  placeholder="Readonly input here..."
				  readonly
				/>
			  </div>
			  
			  <div class="mb-3">
				<label for="exampleFormControlSelect1" class="form-label">Interes</label>
				<select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
				  <option selected>Open this select menu</option>
				  <option value="1">One</option>
				  <option value="2">Two</option>
				  <option value="3">Three</option>
				</select>
			  </div>
			 
			  <div class="demo-inline-spacing">
				<button type="button" class="btn btn-primary"  @click="fnGuardarCliente"  >
				  <span class="tf-icons bx bx-pie-chart-alt"></span>&nbsp; Guardar
				</button>
				
				<button class="btn btn-primary" type="button" disabled>
				  <span class="spinner-border" role="status" aria-hidden="true"></span>
				  <span class="visually-hidden">Loading...</span>
				</button>
				
			  </div>
			  
		  </div>
		  <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
			  <label for="exampleFormControlTextarea1" class="form-label">Mensaje</label>
			  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"  v-model="message" ></textarea>
			  <div class="demo-inline-spacing">
				<button type="button" class="btn btn-primary" @click="fnGuardarNotification" >
				  <span class="tf-icons bx bx-pie-chart-alt"></span>&nbsp; Enviar
				</button>
				<button type="button" class="btn btn-secondary">
				  <span class="tf-icons bx bx-bell"></span>&nbsp; Limpiar
				</button>
			  </div>
		  </div>
		</div>
	</div>
</div>