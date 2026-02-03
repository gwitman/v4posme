<style>
#app { 
	visibility: hidden; 
}
.layout-navbar-fixed .layout-wrapper:not(.layout-horizontal):not(.layout-without-menu) .layout-page {
  padding-top: 0px !important;
}
.layout-navbar-fixed .layout-wrapper:not(.layout-without-menu) .layout-page {
  padding-top: 0px !important;
}

.btn-sidebar 
{
	z-index: 1055; /* mayor que navbar, dropdowns, etc */
}

.btn-sidebar-tab 
{
  z-index: 1055;

  /* Tamaño */
  width: 48px;
  height: 56px;

  /* Pegado a la derecha */
  right: 0;
  padding: 0;

  /* Forma */
  border-radius: 12px 0 0 12px;

  /* Centrar icono */
  display: flex;
  align-items: center;
  justify-content: center;

  /* Quitar sombras raras si hay */
  box-shadow: 0 2px 8px rgba(0,0,0,.25);
}

.btn-sidebar-tab:hover {
  background-color: #0b5ed7; /* azul bootstrap más oscuro */
}

.btn-sidebar-tab {
  margin-right: -1px;
}


</style>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<div id='app' >
	
	<button class="btn btn-primary position-fixed top-50 end-0 translate-middle-y btn-sidebar-tab"
			data-bs-toggle="offcanvas"
			data-bs-target="#sidebarOpciones">
		<i class="bi bi-tools"></i>
	</button>


	<div class="offcanvas offcanvas-end" tabindex="-1" id="sidebarOpciones">
		  <div class="offcanvas-header">
			<h5 class="offcanvas-title">Opciones</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
		  </div>

		  <div class="offcanvas-body">
			
			<!-- Botones -->
			<button class="btn btn-success w-100 mb-2" @click="fnAplicarBusqueda" >Aplicar</button>
			<button class="btn btn-danger w-100 mb-3" @click="fnLimpiarBusqueda" >Cancelar</button>
	
			<div class="mb-3">
				<label for="html5-datetime-local-input"  class="form-label">Inicio</label> 				
				<input
				 class="form-control"
				 type="date" 		
				 v-model="txtStartOn"				 
				 id="html5-datetime-local-input"
				/>
			</div>
			
			<div class="mb-3">
				<label for="html5-datetime-local-input-finish"  class="form-label">Fin</label>
				
			    <input
			 	 class="form-control"
			 	 type="date" 			
				 v-model="txtFinishOn"				 
			 	 id="html5-datetime-local-input-finish"
			    />
				
			</div>
			  
			<div class="mb-3">
				  <label for="txtEmployeID" class="form-label">Colaborador</label>
				  <select class="form-select" 
					id="txtEmployeID" aria-label="Default select example"
					v-model="txtEmployeID"
				  >
					<option value="0" >TODOS</option>
					<?php 
					if($objListEmployer)
					{
						foreach($objListEmployer as $employer)
						{
							echo '<option value="'.$employer->entityID.'" >'.$employer->firstName.'</option>';
						}
					}
					?>	
				  </select>
			</div>
			
			<div class="mb-3">
				  <label for="txtWorkflowStatusID" class="form-label">Estado</label>
				  <select class="form-select" 
					id="txtWorkflowStatusID" aria-label="Default select example"
					v-model="txtWorkflowStatusID"
				  >
					<option :value="0" selected >TODOS</option>
					<option :value="205"   >Abiertas</option>
					<option :value="206"   >Cerradas</option>
				  </select>
			</div>

		  </div>
	</div>



	<!-- Bootstrap Dark Table -->
	<div class="card">
		<h5 class="card-header">Resultado</h5>
		<div class="table-responsive text-nowrap">
		  <table class="table table-dark">
			<thead>
			  <tr>
				<th>Telefono</th>
				<th>Nombre</th>
				<th>Recibido</th>
				<th>No Contestado</th>
				<th>Bandeja</th>
			  </tr>
			</thead>
			<tbody class="table-border-bottom-0">
			  <tr v-for="objConversation in objListConversation" >
				<td>
					<!--<div class="demo-inline-spacing">-->
						<button 
							type="button" 
							class="btn btn-icon "							
							:class="objConversation.messgeConterNotRead > 0 ? 'btn-success' : 'btn-instagram' "
							@click="verConversacion(objConversation.entityID)"
						>
						  <i class="tf-icons bx bx-show"></i>
						</button>
				    <!--</div>-->
					
					<!--<i class="fab fa-angular fa-lg text-danger me-3"></i> -->
					<strong>{{ objConversation.phoneNumber }}</strong>
				</td>
				<td>
					<span class="badge bg-label-primary me-1">
					{{ objConversation.firstName }}
					</span>
				</td>
				<td>
				   {{ objConversation.messageReceiptOnStr }}
				</td>
				<td>
				   {{ objConversation.dayNotContacted }}
				</td>
				<td>
				   {{ objConversation.firstNameEmployer }}
				</td>
			  </tr>			  
			</tbody>
		  </table>
		</div>
	</div>
	
	<div class="row">
		<!--/ Bootstrap Dark Table -->
		<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6 mb-4">
			<div class="card">
				<div class="card-body">
				  <div class="card-title d-flex align-items-start justify-content-between">
					<div class="avatar flex-shrink-0">
					  <img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/icons/unicons/computer.png" alt="computer" class="rounded" />
					</div>
				  </div>
				  <span class="fw-semibold d-block mb-1">Cantidad</span>
				  <h4 class="card-title mb-2" v-text="counterRegister" ></h4>
				  <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +--</small>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6 mb-4">
			<div class="card">
				<div class="card-body">
				  <div class="card-title d-flex align-items-start justify-content-between">
					<div class="avatar flex-shrink-0">
					  <img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/icons/unicons/paypal.png" alt="computer" class="rounded" />
					</div>
				  </div>
				  <span class="fw-semibold d-block mb-1">No leidas</span>
				  <h4 class="card-title mb-2" v-text="noLeidas" ></h4>
				  <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +{{noLeidasPorcentage}}%</small>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6 mb-4">
			<div class="card">
				<div class="card-body">
				  <div class="card-title d-flex align-items-start justify-content-between">
					<div class="avatar flex-shrink-0">
					  <img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/icons/unicons/cube-secondary.png" alt="computer" class="rounded" />
					</div>
				  </div>
				  <span class="fw-semibold d-block mb-1">Sin contestar</span>
				  <h4 class="card-title mb-2" v-text="sinContestar" ></h4>
				  <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +{{sinContestarPorcentage}}%</small>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6 mb-4">
			<div class="card">
				<div class="card-body">
				  <div class="card-title d-flex align-items-start justify-content-between">
					<div class="avatar flex-shrink-0">
					  <img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/icons/unicons/chart-success.png" alt="computer" class="rounded" />
					</div>
				  </div>
				  <span class="fw-semibold d-block mb-1">Contestadas</span>
				  <h4 class="card-title mb-2" v-text="conContestar" ></h4>
				  <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +{{conContestarPorcentage}}%</small>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
  var offcanvas;
  window.onload = () => {
    offcanvas = new bootstrap.Offcanvas('#sidebarOpciones');    
  };
</script>