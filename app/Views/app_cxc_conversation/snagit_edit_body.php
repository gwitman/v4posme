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

/* Estilos modernos para tabs */
.nav-tabs .nav-link {
  border: none;
  border-radius: 10px 10px 0 0;
  margin-right: 5px;
  transition: all 0.3s ease;
  font-weight: 500;
  color: #6c757d;
}

.nav-tabs .nav-link:hover {
  background: #e3f2fd;
  color: #1976d2 !important;
}

.nav-tabs .nav-link.active {
  background: #1976d2;
  color: white !important;
  box-shadow: 0 2px 8px rgba(25, 118, 210, 0.3);
}

/* Estilos para mensajes */
.timeline-item-message {
  border-radius: 12px;
  padding: 10px 14px;
  margin-bottom: 10px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  transition: transform 0.2s ease;
  max-width: 80%;
}

.timeline-item-message:hover {
  transform: translateX(2px);
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.message-from-customer {
  background: #e3f2fd;
  color: #1565c0;
  margin-left: 0;
  margin-right: auto;
}

.message-from-employer {
  background: #1976d2;
  color: white;
  margin-left: auto;
  margin-right: 0;
}

/* Área de envío de mensajes */
.message-input-area {
  background: #f8f9fa;
  border-radius: 15px;
  padding: 15px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  margin-top: 15px;
  border: 1px solid #e0e0e0;
}

.message-input-area textarea {
  border-radius: 10px;
  border: 1px solid #d0d0d0;
  transition: all 0.3s ease;
  resize: none;
}

.message-input-area textarea:focus {
  border-color: #1976d2;
  box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.15);
}

.btn-send-message {
  background: #1976d2;
  border: none;
  border-radius: 10px;
  padding: 8px 20px;
  color: white;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-send-message:hover {
  background: #1565c0;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
  color: white;
}

.btn-attach {
  background: #42a5f5;
  border: none;
  border-radius: 10px;
  color: white;
  transition: all 0.3s ease;
  padding: 8px 16px;
}

.btn-attach:hover {
  background: #1e88e5;
  transform: scale(1.02);
  box-shadow: 0 3px 10px rgba(66, 165, 245, 0.3);
  color: white;
}

.btn-recording {
  background: #ef5350;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

/* Tarjetas de información */
.info-card {
  border-radius: 12px;
  border: 1px solid #e0e0e0;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}

.info-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

/* Preview de imagen pegada */
.image-preview {
  max-width: 150px;
  max-height: 150px;
  border-radius: 8px;
  margin-top: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  border: 2px solid #e0e0e0;
}

.badge-file {
  background: #1976d2;
  border-radius: 8px;
  padding: 6px 12px;
  font-size: 0.85rem;
  color: white;
}

/* Botones de acción */
.btn-action {
  border-radius: 10px;
  padding: 8px 16px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-action:hover {
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}

/* Acordeón personalizado */
.accordion-button:not(.collapsed) {
  background: #1976d2;
  color: white;
}

.custom-option-icon:hover {
  transform: scale(1.01);
  transition: all 0.2s ease;
}

/* Scrollbar personalizado */
.chat-messages::-webkit-scrollbar {
  width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb {
  background: #1976d2;
  border-radius: 10px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
  background: #1565c0;
}

/* Contenedor de mensajes */
.chat-messages {
  max-height: 500px;
  overflow-y: auto;
  padding: 15px;
  background: white;
}

.message-time {
  font-size: 0.75rem;
  opacity: 0.7;
  margin-top: 4px;
}

.audio-recording-indicator {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  background: #ffebee;
  border-radius: 8px;
  color: #c62828;
  font-size: 0.9rem;
}

.recording-dot {
  width: 8px;
  height: 8px;
  background: #ef5350;
  border-radius: 50%;
  animation: pulse 1s infinite;
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
			  <i class="tf-icons bx bx-message-dots"></i> Chat
			  <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger ms-2">{{ unreadCount }}</span>
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
			  <i class="tf-icons bx bx-user-circle"></i> Cliente
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
			  <i class="tf-icons bx bx-send"></i> Enviar Mensaje
			</button>
		  </li>
		  
		</ul>
		<div class="tab-content">
		  <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
				<!-- Activity Timeline -->
				<div class="card info-card">
					<div class="card-body p-0">
					  <!-- Mensajes -->
					  <div class="chat-messages">
						<div 
							v-for="objNotification in objListNotification"  
							class="timeline-item-message"
							:class="objNotification.targetIDIsEmployeer > 0 ? 'message-from-employer' : 'message-from-customer'"
						>
							<div class="d-flex align-items-center gap-2 mb-1">
							  <div 
								class="avatar avatar-xs" 
								v-if="parametroNoDebeVerFoto === false"  
							  >
								<img src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle" />
							  </div>
							  <strong style="font-size: 0.9rem;">{{ objNotification.firstNameSource }}</strong>
							</div>

							<p style="white-space: pre-line; margin-bottom: 4px; font-size: 0.95rem;">
								{{ objNotification.message }}
								
								<template v-if="objNotification.title == 'image'">
								  <br/>
								  <span 
									class="badge bg-light text-primary cursor-pointer mt-1" 
									@click="openImageModal(objNotification.subject)"
								  ><i class="bx bx-image"></i> Ver imagen</span>
								</template> 								
								<template v-else-if="objNotification.title == 'audio'">
								    <br/>
								    <span 
										class="badge bg-light text-primary cursor-pointer mt-1" 
										@click="openImageModalAudio(objNotification.subject)"
									><i class="bx bx-volume-full"></i> Escuchar</span>
								</template>
							</p>
							  
							<div class="message-time">
								{{ objNotification.createdOnFormato12H }}
							</div>
						</div>
					  </div>
					  
					  <!-- Área de envío de mensajes -->
					  <div class="message-input-area">
						<!-- Indicador de grabación -->
						<div v-if="isRecording" class="audio-recording-indicator mb-2">
						  <span class="recording-dot"></span>
						  <span>Grabando audio... {{ recordingTime }}s</span>
						</div>
						
						<div class="mb-2">
						  <textarea 
							class="form-control" 
							id="txtTab1Message" 
							rows="2"  
							v-model="txtTab1Message"
							@paste="handlePaste"
							placeholder="Escribe tu mensaje..."
						  ></textarea>
						  
						  <!-- Preview de imagen pegada -->
						  <div v-if="txtTab1ImagePreview" class="mt-2">
							<img :src="txtTab1ImagePreview" class="image-preview" alt="Preview" />
							<button 
							  type="button" 
							  class="btn btn-sm btn-danger ms-2"
							  @click="clearPastedImage"
							>
							  <i class="bx bx-x"></i>
							</button>
						  </div>
						  
						  <!-- Preview de archivo seleccionado -->
						  <div v-if="txtTab1File" class="mt-2">
							<span class="badge badge-file">
							  <i class="bx bx-file"></i> {{ txtTab1File.name }}
							</span>
							<button 
							  type="button" 
							  class="btn btn-sm btn-danger ms-2"
							  @click="clearTab1File"
							>
							  <i class="bx bx-x"></i>
							</button>
						  </div>
						  
						  <!-- Preview de audio grabado -->
						  <div v-if="txtTab1AudioBlob" class="mt-2">
							<span class="badge badge-file">
							  <i class="bx bx-microphone"></i> Audio grabado ({{ recordingTime }}s)
							</span>
							<button 
							  type="button" 
							  class="btn btn-sm btn-danger ms-2"
							  @click="clearRecordedAudio"
							>
							  <i class="bx bx-x"></i>
							</button>
						  </div>
						</div>
						
						<div class="d-flex gap-2 flex-wrap">
						  <button 
							v-if="!guardando && !isRecording" 
							type="button" 
							class="btn btn-send-message btn-action"
							@click="fnEnviarMensajeTab1"
						  >
							<i class="bx bx-send"></i> Enviar
						  </button>
						  
						  <label v-if="!isRecording" class="btn btn-attach btn-action mb-0">
							<i class="bx bx-image"></i> Imagen
							<input 
							  type="file" 
							  class="d-none" 
							  @change="onTab1FileChange"
							  accept="image/*"
							/>
						  </label>
						  
						  <button 
							v-if="!isRecording && !txtTab1AudioBlob"
							type="button"
							class="btn btn-attach btn-action"
							@click="startRecording"
						  >
							<i class="bx bx-microphone"></i> Grabar Audio
						  </button>
						  
						  <button 
							v-if="isRecording"
							type="button"
							class="btn btn-recording btn-action"
							@click="stopRecording"
						  >
							<i class="bx bx-stop"></i> Detener
						  </button>
						  
						  <button 
							v-if="!guardando && !isRecording" 
							type="button" 
							class="btn btn-secondary btn-action"
							@click="clearTab1Message"
						  >
							<i class="bx bx-eraser"></i> Limpiar
						  </button>
						  
						  <button v-if="guardando" class="btn btn-danger btn-action" type="button" disabled>
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Enviando...
						  </button>
						</div>
					  </div>
					</div>
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
			  
			  <div class="mb-3">
				  <label for="txtTab2CategoryID" class="form-label"><?php echo getBehavio($company->type,"app_cxc_customer","Categoria",""); ?></label>
				  <select class="form-select" 
					id="txtTab2CategoryID" aria-label="Categoría"
					v-model="txtTab2CategoryID"
				  >
					<option value="">Seleccione una categoría</option>
					<option
						v-for="(objCategory, index) in txtTab2ListCategoryID"  
						:key="index"
						:value="objCategory.catalogItemID"  
						>{{ objCategory.name }}</option>
						
				  </select>
			  </div>
			  
			  <div class="mb-3">
				  <label for="txtTab2StatusID" class="form-label">Estado</label>
				  <select class="form-select" 
					id="txtTab2StatusID" aria-label="Estado"
					v-model="txtTab2StatusID"
				  >
					<option value="">Seleccione un estado</option>
					<option
						v-for="(objStatus, index) in txtTab2ListWorkflowStageCustomer"  
						:key="index"
						:value="objStatus.workflowStageID"  
						>{{ objStatus.name }}</option>
						
				  </select>
			  </div>
			  
			  <div class="mb-3">
				  <label for="txtTab2SubCategoryID" class="form-label">Sub Categoría</label>
				  <select class="form-select" 
					id="txtTab2SubCategoryID" aria-label="Sub Categoría"
					v-model="txtTab2SubCategoryID"
				  >
					<option value="">Seleccione una sub categoría</option>
					<option
						v-for="(objSubCategory, index) in txtTab2ListSubCategoryID"  
						:key="index"
						:value="objSubCategory.catalogItemID"  
						>{{ objSubCategory.name }}</option>
						
				  </select>
			  </div>
			  
			  <div class="mb-3">
				<label for="txtTab2Budget" class="form-label">Presupuesto U$</label>
				<input
				  type="text"
				  class="form-control"
				  id="txtTab2Budget"
				  v-model="txtTab2Budget" 
				  placeholder="Budget" 
				/>
			  </div>
			  
			  <div class="mb-3">
				<label for="txtTab2Location" class="form-label"><?php echo getBehavio($company->type,"app_cxc_customer","txtDomicilio","Domicilio"); ?></label>
				<input
				  type="text"
				  class="form-control"
				  id="txtTab2Location"
				  v-model="txtTab2Location"
				  placeholder="Location" 
				/>
			  </div>
			  
			  <div class="mb-3">
				<label for="txtTab2Reference1" class="form-label"><?php echo getBehavio($company->type,"app_cxc_customer","Referencia1",""); ?></label>
				<input
				  type="text"
				  class="form-control"
				  id="txtTab2Reference1"
				  v-model="txtTab2Reference1"
				  placeholder="Referencia 1" 
				/>
			  </div>
			  
			  <div class="mb-3">
				<div class="form-check">
				  <input
					class="form-check-input"
					type="checkbox"
					id="txtTab2AllowWhatsappCollection"
					v-model="txtTab2AllowWhatsappCollection"
				  />
				  <label class="form-check-label" for="txtTab2AllowWhatsappCollection">
					Permite cobro por WhatsApp
				  </label>
				</div>
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
				<button v-if="!guardando" type="button" class="btn btn-primary" @click="fnGuardarNotification" >
				  <span class="tf-icons bx  bx-save"></span>&nbsp; Enviar
				</button> 
				<button v-if="!guardando" type="button" class="btn btn-secondary" @click="fnClearNotification" >
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