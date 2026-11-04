				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
				
				<?php echo view('core_template/posme_v2_style'); ?>

				<div id="heading" class="page-header">
					<h1><i class="icon20  i-office"></i> LISTA DE BANCOS</h1>
				</div>

				<!-- Barra de busqueda y acciones de la lista (estandar pv2) -->
				<div class="pv2">
					<div class="pv2-card pv2-listbar">
						<form class="pv2-search" role="search" autocomplete="off" onsubmit="fnFiltrarLista(); return false;">
							<input type="text" class="form-control" id="txtSearchList" placeholder="Buscar en la lista…" autocomplete="off">
							<button type="button" class="btn btn-primary" id="btnSearchList" title="Buscar"><i class="icon16 i-search-2"></i></button>
						</form>
						<div class="pv2-actions">
							<a href="#" class="btn btn-default" id="btnView"><i class="icon16 i-grid-2"></i> Vistas</a>
							<a href="#" class="btn btn-default" id="btnEdit"><i class="icon16 i-pencil-4"></i> Editar</a>
							<a href="#" class="btn btn-danger" id="btnEliminar"><i class="icon16 i-remove"></i> Eliminar</a>
							<a href="#" class="btn btn-primary" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</a>
						</div>
					</div>
				</div>
			    <div class="row">  
					<div class="col-lg-12">					
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-table"></i></div> 
								<h4>...</h4>
								<a href="#" class="minimize"></a>
							</div>                            
							<div class="panel-body">
							
                                    
							