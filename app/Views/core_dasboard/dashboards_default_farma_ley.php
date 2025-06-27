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
				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/jquery.isloading.min.js"></script>	
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/chart-google/loader.js"></script>				
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jquery.number.min.js"></script>				
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/style.css" rel="stylesheet" /> 

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
								<img class="img-featured" style="width:400px;height:200px" src="<?php echo base_url();?>/resource/img/logos/logo-micro-finanza.jpg">
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
						
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4> Ventas diarias (Mes actual)</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							<div class="panel-body">								
								<div id="grafico1" style="height:150px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Ventas de contado por mes (Año actual)</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico3" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						

						
						
						<div class="panel  <?php echo getBehavio($company->type,"core_dashboards","divPanelInfoPago","hidden"); ?> " style="margin-bottom:20px;display:none">
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
					
						

					</div>
					<div class="col-lg-6">	
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Estadistica</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
						
							<div class="panel-body">								
								<div id="grafico4" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
						
					
						
							
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelFormaPago","hidden"); ?>" style="margin-bottom:20px;display:none">
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
						
						
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelFormaPago","hidden"); ?>" style="margin-bottom:20px;display:none">
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
						
						<div class="panel <?php echo getBehavio($company->type,"core_dashboards","divPanelSoporteTenico","hidden"); ?> " >
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
						
						
						
						

						
					</div>
				</div>

				<script>	
					// https://www.w3schools.com/js/js_graphics_google_chart.asp					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartBarraVentasContadoMesActual);	
						google.charts.setOnLoadCallback(drawChartPastelVentasCreditoMensuales);		
						google.charts.setOnLoadCallback(drawChartBarraHorizontalProductosMasVendidos);	
					});		
					
					function drawChartBarraVentasContadoMesActual() {

						var objVentasDeContadoMesActual		 			= JSON.parse('<?php echo json_encode($objListVentasContadoMesActual); ?>');	
						var objDataSourceVentasDeContadoMesActual	 	= new Array();					
						objDataSourceVentasDeContadoMesActual.push(new Array("Dia","Ventas"));
						
						for(var i = 0 ; i < objVentasDeContadoMesActual.length;i++)
						{
							objDataSourceVentasDeContadoMesActual.push(
								new Array(
									objVentasDeContadoMesActual[i].Dia,
									parseInt(objVentasDeContadoMesActual[i].Venta)
								)
							);	
						}
						
						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasDeContadoMesActual
						);

						var options = {
						  title: '',
						  colors: ['#ff8000', '#ff8000', '#ff8000', '#ff8000', '#ff8000'],
						  seriesType: 'bars',
						  hAxis: {
							title: 'Mes',
							slantedText: true,        // Inclina los textos
							slantedTextAngle: 45      // Ángulo de inclinación
						  },
						  vAxis: {
							title: 'Monto de Ventas'
						  }
						  
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico1'));
						chart.draw(data, options);

					}
					
					function drawChartPastelVentasCreditoMensuales() {
						
						var objVentasCreditoMensuales			 		= JSON.parse('<?php echo json_encode($objListVentasCreditoMensuales); ?>');	
						var objDataSourceVentasCreditoMensuales	 		= new Array();
						objDataSourceVentasCreditoMensuales.push(new Array("Mes","Ventas"));
						for(var i = 0 ; i < objVentasCreditoMensuales.length;i++)
						{
							
							objDataSourceVentasCreditoMensuales.push(
								new Array(
									objVentasCreditoMensuales[i].Dia,
									parseInt(objVentasCreditoMensuales[i].Venta)
								)
							);	
						}
						
						
						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasCreditoMensuales
						);

						var options = {
						  title: '',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						  seriesType: 'bars',
						  
						  hAxis: {
							title: 'Mes',
							slantedText: true,        // Inclina los textos
							slantedTextAngle: 45      // Ángulo de inclinación
						  },
						  vAxis: {
							title: 'Monto de Ventas'
						  }
						  
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico3'));
						chart.draw(data, options);

					}
					
					function drawChartBarraHorizontalProductosMasVendidos() {
						var objDataSourceProductosMasVendidos	 	= new Array();
						var objTransactionMaster 				 	= JSON.parse('<?php echo json_encode($objListVentas); ?>');	
						
						objDataSourceProductosMasVendidos.push(new Array("Colaborador","Venta"));
						for(var i = 0 ; i < objTransactionMaster.length;i++)
						{
							objDataSourceProductosMasVendidos.push(
								new Array(
									objTransactionMaster[i].firtsName,
									parseInt(objTransactionMaster[i].monto)
								)
							);	
						}
						
						var data = google.visualization.arrayToDataTable(
							objDataSourceProductosMasVendidos
						);

						var options = {
						  title: 'Colaboradores vs Venta',
						  colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};

						var chart = new google.visualization.BarChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}
					
					

											
				</script>
				