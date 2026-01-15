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
							class=""
						>
						  <!--<span class="timeline-point timeline-point-warning"></span>-->
						  <!--<div class="">-->
							<div 
								class="timeline-header mb-1"
								:class="{'d-flex justify-content-start align-items-center': objNotification.targetIDIsEmployeer > 0, 'justify-content-end align-items-center': objNotification.targetIDIsEmployeer <= 0}"
							>
							
							  <!-- si source > 0 -->
							  <template v-if="objNotification.targetIDIsEmployeer <= 0">
							    <h6 class="mb-0">{{ objNotification.firstNameSource }} </h6>
							    <h6 class="mb-0 me-0 text-success"  >{{ objNotification.createdOnFormato12H }}</h6>
							  </template> 
							  <!-- si source <= 0 -->
							  <template v-else>
							    <h6 class="mb-0 me-0 text-success"  >{{ objNotification.createdOnFormato12H }} </h6>
							    <h6 class="mb-0 ms-2"> {{ objNotification.firstNameSource }} </h6> 							    
							  </template> 


							  <!--<small class="text-muted">{{ objNotification.createdOn }}</small>-->
							</div>
							
							


							<p 
								class="mb-2"
								:class="{'': objNotification.targetIDIsEmployeer > 0, 'text-end': objNotification.targetIDIsEmployeer <= 0}"
							>{{ objNotification.message }}</p>
							<div 
								class="d-flex flex-wrap"
								:class="{'': objNotification.targetIDIsEmployeer > 0, 'justify-content-end align-items-center': objNotification.targetIDIsEmployeer <= 0}"
							>
							  <div class="avatar me-3">
								<img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle" />
							  </div>
							  <div
								:class="{'': objNotification.targetIDIsEmployeer > 0, 'text-end': objNotification.targetIDIsEmployeer <= 0}"
							  >
								<h6 class="mb-0">{{ objNotification.phoneFrom }}</h6>
								<span> {{ objNotification.firstNameTartet }}</span>
							  </div>
							</div>
						  <!--</div>-->
						</li>
						
						
					  </ul>
					</div>
				  
				<!--/ Activity Timeline -->
		  </div>
		  <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
					
						
			  <div class="mb-3">
				<label for="txtCustomerName" class="form-label">Nombre</label>
				<input
				  type="email"
				  class="form-control"
				  id="txtCustomerName"
				  v-model="txtCustomerName"
				  placeholder="nombre" 
				/>
			  </div>
			  <div class="mb-3">
				<label for="txtCustomerPhone" class="form-label">Telefono</label>
				<input
				  class="form-control"
				  type="text"
				  id="txtCustomerPhone"
				  v-model="txtCustomerPhone"
				  placeholder="88888888" 
				/>
			  </div>
			  
			  <div class="demo-inline-spacing">
				<button type="button" class="btn btn-primary"  @click="fnGuardarCliente"  >
				  <span class="tf-icons bx  bx-save"></span>&nbsp; Guardar
				</button>
				<button v-if="guardando" class="btn btn-danger" type="button" disabled>
				  <span class="spinner-border" role="status" aria-hidden="true"></span>
				  <span class="visually-hidden">Loading...</span>
				</button>
			  </div>
			  
		  </div>
		  <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
			  <label for="exampleFormControlTextarea1" class="form-label">Mensaje</label>
			  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"  v-model="txtCustomerMessage" ></textarea>
			  <div class="demo-inline-spacing">
				<button type="button" class="btn btn-primary" @click="fnGuardarNotification" >
				  <span class="tf-icons bx  bx-save"></span>&nbsp; Enviar
				</button>
				<button type="button" class="btn btn-secondary">
				  <span class="tf-icons bx bx-eraser"></span>&nbsp; Limpiar
				</button>
				<button v-if="guardando" class="btn btn-danger" type="button" disabled>
				  <span class="spinner-border" role="status" aria-hidden="true"></span>
				  <span class="visually-hidden">Loading...</span>
				</button>
			  </div>
		  </div>
		</div>
	</div>
</div>