	<div class="row">  
		<div id="email" class="col-lg-12">
		<div class="email-bar" style="width:100%">				
				<div class="btn-group pull-right">
					<a href="#" class="btn btn-warning btnSearchServer"  id="btnSearchServer"><i class="icon16 i-rotate"></i>Refresh</a>
					<?php
						if($urlRedictWhenEmpty != "not_redirect_when_empty")
						{
							?>
							<a href="<?php echo base_url().$urlRedictWhenEmpty; ?>" class="btn btn-success"  id="btnPopupNuevo"><i class="icon16 i-checkmark-4"></i>Nuevo</a>
							<?php
						}
					?>
					<a href="#" class="btn btn-warning"  id="btnPopupCancelar"><i class="icon16 i-rotate"></i>Cancelar</a>
					<a href="#" class="btn btn-success" id="btnPopupAceptar"><i class="icon16 i-checkmark-4"></i>Aceptar</a>						
				</div>
			</div>
		</div>
	</div> 