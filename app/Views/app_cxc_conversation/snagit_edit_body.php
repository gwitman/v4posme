<style>
#app { visibility: hidden; }
.light-style .bootstrap-select .filter-option-inner-inner{
	color: red;
}
.layout-navbar-fixed .layout-wrapper:not(.layout-horizontal):not(.layout-without-menu) .layout-page {
  padding-top: 0px !important;
}
.layout-navbar-fixed .layout-wrapper:not(.layout-without-menu) .layout-page {
  padding-top: 0px !important;
}
.cursor-pointer {
  cursor: pointer;
}
</style>


<div id='app' >
	
	<div class=""				
		:class="mostrarWaite ? '' : 'd-none' " 
	>		    	
		<div class="card-body">
		  <div class="demo-inline-spacing">
			
			<button class="btn btn-linkedin" type="button" disabled>
			  <span class="spinner-border" role="status" aria-hidden="true"></span>
			  Espere un momento...
			</button>
		  </div>
		</div>
	</div>
	
	<div v-if="mostrarAlerta"
         class="alert alert-dismissible" 
         role="alert"
		 :class="error ? 'alert-danger' : 'alert-success' "
	>
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
							class="mt-3"
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
								style="white-space: pre-line;" 
								class="mb-2"
								:class="{'': objNotification.targetIDIsEmployeer > 0, 'text-end': objNotification.targetIDIsEmployeer <= 0}"
							>
								{{ objNotification.message }}
								
								<!-- si source > 0 -->
								<template v-if="objNotification.title == 'image'">
								  <br/>
								  <span 
									class="badge bg-info cursor-pointer" 
									@click="openImageModal(objNotification.subject)"
								  > Ver imagen</span>
								</template> 								
								<!-- ELSE IF -->
								<template v-else-if="objNotification.title == 'audio'">
								    <br/>
								    <span 
										class="badge bg-info cursor-pointer" 
										@click="openImageModalAudio(objNotification.subject)"
									> Escuchar</span>
								</template>

								<!-- si source <= 0 -->
								<template v-else>
								</template> 
								
							</p>
							  
							
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
				<label for="txtTab2CustomerName" class="form-label">Nombre</label>
				<input
				  type="email"
				  class="form-control"
				  id="txtTab2CustomerName"
				  v-model="txtTab2CustomerName"
				  placeholder="nombre" 
				/>
			  </div>
			  <div class="mb-3">
				<label for="txtTab2CustomerPhone" class="form-label">Telefono</label>
				<input
				  class="form-control"
				  type="text"
				  id="txtTab2CustomerPhone"
				  v-model="txtTab2CustomerPhone"
				  placeholder="88888888" 
				/>
			  </div>
			  
			  <!-- Users List -->
			  <!--
			  <div class="mb-3">
				  <label for="selectpickerMultiple" class="form-label">Colaboradores</label>
				  <select
					id="selectpickerMultiple"
					class="selectpicker w-100"
					data-style="btn-default"
					multiple
					v-model="txtTab2ListEmployerAsigned"
					data-icon-base="bx"
					data-tick-icon="bx-check text-primary"
				  >
					<option
						v-for="(objEmployer, index) in txtTab2ListEmployer"  
						:key="index"
						:value="objEmployer.entityID"  
						>{{ objEmployer.firstName }}</option>
						
				  </select>
                        
			  </div>
			  -->
			  
			  <div class="mb-3">
				  <label for="txtAccionConversationID" class="form-label">Accion</label>
				  <select class="form-select" 
					id="txtAccionConversationID" aria-label="Default select example"
					v-model="txtTab2WorkflowStageID"
				  >
					<option
						v-for="(objWorkflowStage, index) in txtTab2ListWorkflowStage"  
						:key="index"
						:value="objWorkflowStage.workflowStageID"  
						>{{ objWorkflowStage.name }}</option>
						
				  </select>
			  </div>
			  
			   <!-- Advance Styling Options -->
              
              <div class="row">
                <!-- Accordion with Icon -->
                <div class="col-md mb-4 mb-md-2">
                  <div class="accordion mt-3" id="accordionWithIcon">
                    <div class="card accordion-item card">
                      <h2 class="accordion-header d-flex align-items-center">
                        <button
                          type="button"
                          class="accordion-button  bg-danger text-white "
                          data-bs-toggle="collapse"
                          data-bs-target="#accordionWithIcon-1"
                          aria-expanded="false"
                        >
                          <i class="bx bx-bar-chart-alt-2 me-2 text-white "></i>
                          Colaboradores
                        </button>
                      </h2>

                      <div id="accordionWithIcon-1" class="accordion-collapse collapse">
                        <div class="accordion-body">
							<!-- Custom SVG Icon Checkbox -->
							<div class="col-xl-12 mb-12">
								<div class="row" v-for="(row, rowIndex) in txtTab2ListEmployer" :key="rowIndex">
								  <div class="col-md mb-md-0 mb-2" v-for="collaborator in row" :key="collaborator.entityID">
									<div class="form-check custom-option custom-option-icon">
									  <label class="form-check-label custom-option-content">
										<span class="custom-option-body">
										  <!-- Icono o avatar -->
										  <img
											src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/icons/unicons/chart.png"
											class="w-px-40 mb-2"
											:alt="collaborator.firstName"
										  />
										  <span class="custom-option-title">{{ collaborator.firstName }}</span>
										</span>
										<input
										  type="checkbox"
										  class="form-check-input"
										  :value="collaborator.entityID"
										  v-model="txtTab2ListEmployerAsigned"
										/>
									  </label>
									</div>
								  </div>
								</div>
							</div>
							<!-- /Custom SVG Icon Checkbox -->
							  
                        </div>
                      </div>
                    </div>

					<!--
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
                          Header Option 2
                        </button>
                      </h2>
                      <div id="accordionWithIcon-2" class="accordion-collapse collapse">
                        <div class="accordion-body">
                          Dessert ice cream donut oat cake jelly-o pie sugar plum cheesecake. Bear claw dragée oat cake
                          dragée ice cream halvah tootsie roll. Danish cake oat cake pie macaroon tart donut gummies.
                          Jelly beans candy canes carrot cake. Fruitcake chocolate chupa chups.
                        </div>
                      </div>
                    </div>

                    <div class="accordion-item active">
                      <h2 class="accordion-header d-flex align-items-center">
                        <button
                          type="button"
                          class="accordion-button collapsed"
                          data-bs-toggle="collapse"
                          data-bs-target="#accordionWithIcon-3"
                          aria-expanded="false"
                        >
                          <i class="bx bx-gift me-2"></i>
                          Header Option 3
                        </button>
                      </h2>
                      <div id="accordionWithIcon-3" class="accordion-collapse collapse">
                        <div class="accordion-body">
                          Oat cake toffee chocolate bar jujubes. Marshmallow brownie lemon drops cheesecake. Bonbon
                          gingerbread marshmallow sweet jelly beans muffin. Sweet roll bear claw candy canes oat cake
                          dragée caramels. Ice cream wafer danish cookie caramels muffin.
                        </div>
                      </div>
                    </div>
                  
					-->
				  </div>
                </div>
			  </div>
              <!--/ Accordion with Icon -->
				
				
				
			  
			  
			  
						
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
		      <div class="mb-3">
				<label for="txtTab3CustomerPhone" class="form-label">Telefono</label>
				<input
				  class="form-control"
				  type="text"
				  id="txtTab3CustomerPhone"
				  v-model="txtTab3CustomerPhone"
				  placeholder="" 
				/>
			  </div>
			  
			  <label for="txtTab3CustomerMessage" class="form-label">Mensaje</label>
			  <textarea class="form-control" id="txtTab3CustomerMessage" rows="3"  v-model="txtTab3CustomerMessage" ></textarea>
			  
			  <div class="mb-3">
				<label for="formFile" class="form-label">Subir archivo</label>
				<input class="form-control" type="file" id="formFile" @change="onFileChange" />
			  </div>
			  
			  <div class="demo-inline-spacing">
				<button type="button" class="btn btn-primary" @click="fnGuardarNotification" >
				  <span class="tf-icons bx  bx-save"></span>&nbsp; Enviar
				</button> 
				<button type="button" class="btn btn-secondary" @click="fnClearNotification" >
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
	
		
	<!-- Add New Credit Card Modal -->
	<div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
		  <div class="modal-content p-3 p-md-5">
			<div class="modal-body text-center">
			   <img :src="modalImageSrc" class="img-fluid"  />
			</div>
		  </div>
		</div>
	</div>
	<!--/ Add New Credit Card Modal -->
	
	<!-- Add New Credit Card Modal -->
	<div class="modal fade" id="addNewCCModalAudio" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
		  <div class="modal-content p-3 p-md-5">
			<div class="modal-body text-center">
			   <audio 
					v-if="audioUrl"
					ref="audioPlayer"
		  
					controls 
					autoplay 
					style="width:100%"
				>
				  <source :src="audioUrl" type="audio/ogg">
				  Tu navegador no soporta audio.
			   </audio>
			</div>
		  </div>
		</div>
	</div>
	<!--/ Add New Credit Card Modal -->
	  
</div>