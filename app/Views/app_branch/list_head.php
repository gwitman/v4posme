				<div id="heading" class="page-header">
					<h1><i class="icon20  i-office"></i> LISTA DE SUCURSALES</h1>
				</div>
				<?php echo view('core_template/posme_v2_style'); ?>

				<div class="pv2">
					<div class="pv2-card pv2-listbar">
						<form class="pv2-search" role="search" autocomplete="off" onsubmit="$('#btnSearchList').trigger('click'); return false;">
							<input type="text" class="form-control" id="txtSearchList" placeholder="Buscar sucursal…" autocomplete="off">
							<button type="button" class="btn btn-primary" id="btnSearchList" title="Buscar"><i class="icon16 i-search-2"></i></button>
						</form>
						<div class="pv2-actions">
							<button type="button" class="btn btn-default" id="btnView"><i class="icon16 i-grid-2"></i> Vistas</button>
							<button type="button" class="btn btn-default" id="btnEdit"><i class="icon16 i-pencil-4"></i> Editar</button>
							<button type="button" class="btn btn-danger"  id="btnEliminar"><i class="icon16 i-remove"></i> Eliminar</button>
							<button type="button" class="btn btn-primary" id="btnNuevo"><i class="icon16 i-plus"></i> Nuevo</button>
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

