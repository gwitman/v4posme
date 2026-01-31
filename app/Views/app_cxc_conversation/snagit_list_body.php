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
			<button class="btn btn-success w-100 mb-2" @click="fnAplicarCambiosConversaciones" >Aplicar</button>
			<button class="btn btn-danger w-100 mb-3" @click="fnLimpiarCambiosConversaciones" >Cancelar</button>

			<!-- Checkboxes -->
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" id="chk1" v-model="marcarLeido" >
			  <label class="form-check-label" for="chk1">
				Marcar todos como leido
			  </label>
			</div>

			<div class="form-check">
			  <input class="form-check-input" type="checkbox" id="chk2" v-model="marcarCerrados" >
			  <label class="form-check-label" for="chk2">
				Cerrar todas las conversaciones
			  </label>
			</div>

		  </div>
	</div>



	<nav class="navbar navbar-example navbar-expand-lg bg-danger">
	  <div class="container-fluid">
		<a class="navbar-brand" href="javascript:void(0)">Buscar</a>
		<button
		  class="navbar-toggler"
		  type="button"
		  data-bs-toggle="collapse"
		  data-bs-target="#navbar-ex-4"
		>
		  <span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbar-ex-4">
		 
		  <div class="navbar-nav me-auto">
			<a 
				class="nav-item nav-link" 
				href="javascript:void(0)"
				:class="{ active: activeTab === 'LISTA DE CLIENTES PARA CREAR CONVERSACIONES' }"     
				@click="handleClick('LISTA DE CLIENTES PARA CREAR CONVERSACIONES')"
			>Iniciar</a>
			<a 
				class="nav-item nav-link" 
				href="javascript:void(0)"
				:class="{ active: activeTab === 'LISTA DE CONVERSACIONES MIAS' }"
				@click="handleClick('LISTA DE CONVERSACIONES MIAS')"
			>Mias</a>
			<a 
				v-if="permisoParaVerTodasLasActivas"
				class="nav-item nav-link" 
				href="javascript:void(0)"
				:class="{ active: activeTab === 'activas' }"
				@click="handleClick('activas')"
			>Todas</a>
		  </div>

		  <form class="d-flex">
			<div class="input-group">
			  <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
			  <input type="text" class="form-control" placeholder="Search..."   v-model="txtCustomerFind" />
			</div>
		  </form>
		</div>
	  </div>
	</nav>

	<!-- Role cards -->
	<div class="row g-4">
			
		    <div class=""				
				:class="mostrarAlerta ? '' : 'd-none' " 
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
		  
			
			<div 
				class="col-xl-4 col-lg-6 col-md-6"
				v-for="objConversation in objListConversationFilter"   
			>
			  <div 
				class="card"
				:class="{'bg-warning': objConversation.messgeConterNotRead > 0, '': objConversation.messgeConterNotRead <= 0}"
			  >
				<div class="card-body">
				  <div class="d-flex justify-content-between mb-2">
					<h6 
						class="fw-bold"
						:class="{'text-white': objConversation.messgeConterNotRead > 0, '': objConversation.messgeConterNotRead <= 0}" 
					>{{ objConversation.phoneNumber }}</h6>
					<h6 
						class="fw-normal"
						:class="{'text-white': objConversation.messgeConterNotRead > 0, '': objConversation.messgeConterNotRead <= 0}" 
					>{{ objConversation.lastActivityOn }}</h6>
					<ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
					  <li
					    v-if="parametroNoDebeVerFoto === false" 
						data-bs-toggle="tooltip"
						data-popup="tooltip-custom"
						data-bs-placement="top"
						title="Vinnie Mostowy"
						class="avatar avatar-sm pull-up"
					  >
						<img class="rounded-circle" src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/img/avatars/5.png" alt="Avatar" />
					  </li>
					</ul>
				  </div>
				  <div class="d-flex justify-content-between align-items-end">
					<div class="role-heading">
					  <h4 
						class="mb-1" 
						:class="{'text-white': objConversation.messgeConterNotRead > 0, '': objConversation.messgeConterNotRead <= 0}"
					  >{{ objConversation.firstName }}</h4>
					  <p>{{ objConversation.lastMessage }}</p>
					  <a
						:href="'<?php echo  base_url() ?>' + '/app_cxc_conversation/edit/entityID/' + objConversation.entityID" 						
						class="role-edit-modal"
						><small
							:class="{'text-white': objConversation.messgeConterNotRead > 0, '': objConversation.messgeConterNotRead <= 0}" 
						>Ver {{ objConversation.customerNumber }} </small></a
					  >
					</div>
					<a  href="javascript:void(0);" 
						class="text-muted"
						
					><i 
						class="bx bx-copy"
						:class="{'text-white': objConversation.messgeConterNotRead > 0, '': objConversation.messgeConterNotRead <= 0}" 
					></i></a>
				  </div>
				</div>
			  </div>
			</div>
			
			
	</div>
	<!--/ Role cards -->

</div>

<script>
  var offcanvas;
  window.onload = () => {
    offcanvas = new bootstrap.Offcanvas('#sidebarOpciones');    
  };
</script>