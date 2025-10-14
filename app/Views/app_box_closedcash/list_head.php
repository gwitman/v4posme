<div id="heading" class="page-header">
	<h1><i class="icon20 i-storage-2"></i> LISTA DE CIERRE DE CAJA</h1>
</div>

<div class="row">  
	<div id="email" class="col-lg-12">
	<div class="email-bar" style="width:100%">
			
					<form class="navbar-form navbar-left" role="search">
						<div class="col-lg-12 srch">
							<div class="input-group-btn">
								<input type="text" class="form-control" id="txtSearchTransaction" placeholder="search ...">
								<button type="button" class="btn" id="btnSearchTransaction" ><i class="icon16 i-search-2 gap-left5"></i></button>
							</div>
						</div>
					</form>
			
					<div class="btn-group pull-right" style="margin-top:0px">
						<a href="#" class="btn btn-inverse" id="btnView"><i class="icon16 i-grid-2"></i>Vistas</a>
						<a href="#" class="btn btn-primary" id="btnEdit"><i class="icon16 i-pencil-4"></i>Editar</a>
						<a href="#" class="btn btn-danger"  id="btnEliminar"><i class="icon16 i-remove"></i>Delete</a>
						<a href="#" class="btn btn-success" id="btnNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>						
					</div>
	</div>			
	</div>
</div>
				
			   
<div id="ModalMasInformacion" style="display:none">
	<h3>Información adicional</h3>
	<p id="txtInformationAdicional" class="parrafoModalCustomPosMe" style="text-align:left">
	</p>
	
</div>
<?php
	helper_getHtmlOfModalDialog("ModalMasInformacion","ModalMasInformacion","",true);
?>

<div class="row">  
	<div class="col-lg-12">	
				<div id="div-container-list">