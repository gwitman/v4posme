				<div id="heading" class="page-header">
					<h1><i class="icon20 i-address-book"></i> LISTA DE <?php echo getBehavio($company->type,"app_cxc_customer","labelTitlePageList","CLIENTES"); ?></h1>
				</div>
				
				<div class="row">  
					<div id="email" class="col-lg-12">
					<div class="email-bar" style="width:100%">
							
									<form class="navbar-form navbar-left" role="search">
										<div class="col-lg-12 srch">
											<div class="input-group-btn">
												<input type="text" class="form-control" id="txtSearchCustomer" placeholder="search ...">
												<button type="button" class="btn" id="btnSearchCustomer" ><i class="icon16 i-search-2 gap-left5"></i></button>
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
				
			    <div class="row">  
					<div class="col-lg-12">					
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-table"></i></div> 
								<h4>...</h4>
								<a href="#" class="minimize"></a>
							</div>                   

							<?php 
								if( $objParameterCORE_VIEW_CUSTOM_SCROLL_IN_LIST_CUSTOMER == "true" )
								{
									?>
									<div class="panel-body" style="overflow:scroll;height:600px">
									<?php 
								}
								else 
								{
									
									?>
									<div class="panel-body" >
									<?php 
								}
							?>
							