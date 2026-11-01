				
<div 
	class="isloading-overlay"
	id="divLoandingCustom"
	style="position:fixed; left:0; top:0; z-index: 10000; background: rgba(0,0,0,1); width: 100%; height: 1090px;"	
>
	<span class="isloading-wrapper  isloading-show  isloading-overlay">espere un momento ...  
		<i class="icon-refresh icon-spin">
		</i>
	</span>
</div>

<div id="heading" class="page-header">
	<h1><i class="icon20   i-cube-3"></i> LISTA DE PRODUCTOS</h1>
</div>

<?php echo view('core_template/posme_v2_style'); ?>

<div class="pv2">
	<div class="pv2-card pv2-listbar">
		<form class="pv2-search" role="search" onsubmit="$('#btnSearchItem').trigger('click'); return false;">
			<input type="text" class="form-control" id="txtSearchItem" placeholder="Buscar producto…" autocomplete="off">
			<button type="button" class="btn btn-primary" id="btnSearchItem" title="Buscar"><i class="icon16 i-search-2"></i></button>
		</form>
		<div class="pv2-actions">
			<button type="button" class="btn btn-default" id="btnView"><i class="icon16 i-grid-2"></i> Vistas</button>
			<button type="button" class="btn btn-default" id="btnEdit"><i class="icon16 i-pencil-4"></i> Editar</button>
			<button type="button" class="btn btn-danger <?php echo getBehavio($company->type,"app_inventory_item","btnDelete",""); ?> "  id="btnEliminar"><i class="icon16 i-remove"></i> Eliminar</button>
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
			
					
			