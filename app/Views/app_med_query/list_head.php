				<?php echo view('app_med_query/med_v2_style'); ?>

				<div id="heading" class="page-header">
					<h1><i class="icon20 i-storage-2"></i> LISTA DE <?php echo getBehavio($company->type,"app_med_query","labelTitlePageList","ASISTENCIA MEDICA"); ?></h1>
				</div>

				<div class="mqv2">
					<div class="mqv2-card mqv2-listbar">
						<form class="mqv2-search" role="search" onsubmit="$('#btnSearchTransaction').trigger('click'); return false;">
							<input type="text" class="form-control" id="txtSearchTransaction" placeholder="Buscar por número…">
							<button type="button" class="btn btn-primary" id="btnSearchTransaction" title="Buscar"><i class="icon16 i-search-2"></i></button>
						</form>
						<div class="mqv2-actions">
							<a href="#" class="btn btn-default" id="btnView"><i class="icon16 i-grid-2"></i> Vistas</a>
							<a href="#" class="btn btn-default" id="btnEdit"><i class="icon16 i-pencil-4"></i> Editar</a>
							<a href="#" class="btn btn-danger"  id="btnEliminar"><i class="icon16 i-remove"></i> Eliminar</a>
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


