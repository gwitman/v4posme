				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" /> 
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.pie.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.resize.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.time.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/date.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/sparklines/jquery.sparkline.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
				 
				<div id="heading" class="page-header">
							<h1><i class="icon20 i-dashboard"></i> 
								Dashboard								
							</h1>
				</div>
				
				<div class="row">
					 <div class="col-lg-6">		
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4><?php echo $company->name; ?></h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
								<img class="img-featured" style="width:300px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.jpg">
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
						
						<div class="panel  <?php echo getBehavio($company->type,"core_dashboards","divPanelCuadroMembresia",""); ?>  " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Plan activado</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							<table class="table table-sm">
								<thead>
									<tr>
										<th scope="col">Llave</th>
										<th scope="col">Valor</th>	  
									</tr>
								</thead>
								<tbody>									
									<tr>
										<th scope="row">CORE_CUST_PRICE_SLEEP</th>
										<td><?php echo $objParameterISleep; ?></td>											
									</tr>
									<tr>
										<th scope="row">CORE_CUST_PRICE_TIPO_PLAN</th>
										<td><?php echo $objParameterTipoPlan; ?></td>			
									</tr>
									
									<?php 
									if($objParameterTipoPlan != "PERPETUA")
									{
										?>
										<tr class="bg-primary">
											<th scope="row">CORE_CUST_PRICE_LICENCES_EXPIRED</th>
											<td><?php echo $objParameterExpiredLicense; ?></td>											
										</tr>
										<?php 
									}
									?>
									
									<tr>
										<th scope="row">CORE_CUST_PRICE_BALANCE</th>
										<td><?php echo $objParameterCreditos; ?></td>											
									</tr>
									<tr>
										<th scope="row">CORE_CUST_PRICE_BY_INVOICE</th>
										<td><?php echo $objParameterPriceByInvoice; ?></td>											
									</tr>
									<tr>
										<th scope="row">CORE_CUST_PRICE_MAX_USER</th>
										<td><?php echo $objParameterMAX_USER; ?></td>			
									</tr>
									<tr>
										<th scope="row">CORE_CUST_PRICE_VERSION</th>
										<td><?php echo $objParameterVersion; ?></td>			
									</tr>
									<tr>
										<th scope="row">CORE_CUST_PRICE</th>
										<td>$ <?php echo number_format($objParameterPrice,2,'.',','); ?></td>			
									</tr>
									
								</tbody>
								</table>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelBiblico",""); ?>  " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Consejo bíblico</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
										<blockquote>
											<p style="text-align: justify;">
													<?php 														
														foreach($objVersiculo as $objVersiculoItem)
														{
															echo $objVersiculoItem->versiculo." <br/><br/>"; 
														}
													?>
											</p>									
											<small>
												<?php echo $objVersiculo[0]->libro; ?> <?php echo $objVersiculo[0]->capitulo; ?>
											</small>
										</blockquote>
								
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
					</div>
					<div class="col-lg-6">	
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Tipo de cambio</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
								<blockquote>
									<p class="text-success">VALOR: <?php echo number_format($objExchangeRateCordobaDolar,2); ?></p>									
									<small>Dolar a Cordoba</small>
								</blockquote>
								
							   <blockquote>
									<p class="text-danger">VALOR: <?php echo number_format($objExchangeRateDolarACordoba,4); ?></p>									
									<small>Cordoba al Dolar</small>
								</blockquote>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						
						
						
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelSoporteTenico",""); ?> " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Informacion de contacto</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							   <blockquote>
									<p>Soporte Tenico: 8712-5827</p>									
									<small>posMe</small>
								</blockquote>
								<a aria-label="Chat on WhatsApp" target="_blank" href="https://wa.me/50587125827?text=Buenos dias le saluda <?php echo $user->email; ?> : "> 
									<img alt="Chat on WhatsApp" src="<?php echo base_url();?>/resource/img/logos/WhatsAppButtonGreenSmall.svg" /> 
								</a>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelFormaPago",""); ?>" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Pago con tarjeta</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
								<form method="POST" action="<?php echo base_url(); ?>core_acount/login"  autocomplete="off">
									<div class="form-group form-material floating" data-plugin="formMaterial">
										<input type="text" class="form-control" name="txtNickname" />
										<label class="floating-label">Usuario</label>
									</div>
									<div class="form-group form-material floating" data-plugin="formMaterial">
										<input type="password" class="form-control" name="txtPassword" />
										<label class="floating-label">Contraseña</label>
									</div>
									
									<div class="form-group form-material floating hidden-lg-up" id="divPagosMeses" data-plugin="formMaterial">
										<input type="text" class="form-control" id="txtPagarCantidadDe" name="txtPagarCantidadDe" placeholder="$ 0.00"></input>
									</div>    
									
									<button type="submit" class="btn btn-success btn-block btn-lg m-t-40 hidden-lg-up" id="divPagosMesesBoton" >Pagar</button>
								</form>
								<br/>
								<img class="img-featured" style="width:200px;height:50px" src="<?php echo base_url();?>/resource/img/logos/tarjeta.png">
								<img class="img-featured" style="width:200px;height:80px" src="<?php echo base_url();?>/resource/img/logos/posme.svg">
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						<div class="panel  <?php echo getBehavio($company->type,"core_dashboards","divPanelInfoPago",""); ?> " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Informacion de pago</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							   <blockquote>									
									<p>BAC $ 366-620-045</p>
									<small>posMe</small>
								</blockquote>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						<!--
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>posMe</h4>
								<a href="#" class="minimize"></a>
							</div>
							<div class="panel-body">
								<img class="img-featured" style="width:200px;height:80px" src="<?php echo base_url();?>/resource/img/logos/posme.svg">
							</div>
						</div>
						-->
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelUsuario",""); ?> " style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-quotes-left"></i></div> 
								<h4>Usuario</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">
							   <blockquote>
									<p><?php echo $user->nickname; ?><br/><?php echo $user->email; ?></p>
									<small>posMe</small>
								</blockquote>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
						
						
					</div>
				</div>
				