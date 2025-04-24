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
								<h4>Ventas por usuario (Mes actual)</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							
							<div class="panel-body">								
								<div id="grafico2" style="height:300px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->		
							
						
						
						<div class="panel" style="margin-bottom:20px;">
							<div class="panel-heading">
								<div class="icon"><i class="icon20 i-health"></i></div> 
								<h4>Pagos por dia (Mes actual)</h4>
								<a href="#" class="minimize"></a>
							</div><!-- End .panel-heading -->
							<div class="panel-body">								
								<div id="grafico4" style="height:150px" ></div>
							</div><!-- End .panel-body -->
						</div><!-- End .widget -->	
						
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
					
					var objPagosMensuales 				 			= JSON.parse('<?php echo json_encode($objPagosMensuales); ?>');	
					var objVentasCreditoMensuales			 		= JSON.parse('<?php echo json_encode($objListVentasCreditoMensuales); ?>');	
					var objVentasDeContadoMesActual		 			= JSON.parse('<?php echo json_encode($objListVentasContadoMesActual); ?>');	
					var objVentaContadoMensuales			 		= JSON.parse('<?php echo json_encode($objListVentaContadoMensuales); ?>');	
					var objDataPagosMensuales	 					= new Array();
					var objDataSourceVentasCreditoMensuales	 		= new Array();
					var objDataSourceVentasDeContadoMesActual	 	= new Array();
					var objDataSourceVentasContadoMensuales	 		= new Array();
					
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
					
					
					objDataSourceVentasContadoMensuales.push(new Array("Mes","Ventas"));
					for(var i = 0 ; i < objVentaContadoMensuales.length;i++)
					{
						objDataSourceVentasContadoMensuales.push(
							new Array(
								objVentaContadoMensuales[i].Mes,
								parseInt(objVentaContadoMensuales[i].Venta)
							)
						);	
					}
					
					
					
					objDataPagosMensuales.push(new Array("Mes","Pagos"));
					for(var i = 0 ; i < objPagosMensuales.length;i++)
					{
						objDataPagosMensuales.push(
							new Array(
								objPagosMensuales[i].Mes,
								parseInt(objPagosMensuales[i].Pagos)
							)
						);	
					}
					
					objDataSourceVentasCreditoMensuales.push(new Array("Mes","Ventas"));
					for(var i = 0 ; i < objVentasCreditoMensuales.length;i++)
					{
						objDataSourceVentasCreditoMensuales.push(
							new Array(
								objVentasCreditoMensuales[i].Mes,
								parseInt(objVentasCreditoMensuales[i].Venta)
							)
						);	
					}
					
					
					
					
					$(document).ready(function(){
						google.charts.load('current',{packages:['corechart']});							
						google.charts.setOnLoadCallback(drawChartBarraVentasContadoMesActual);		
						google.charts.setOnLoadCallback(drawChartPastelVentasContadoMensuales);		
						google.charts.setOnLoadCallback(drawChartPastelVentasCreditoMensuales);		
						google.charts.setOnLoadCallback(drawChartBarraCapitalMensual);		
					});		
					
					function drawChartBarraVentasContadoMesActual() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasDeContadoMesActual
						);

						var options = {
						  title: '',
						  colors: ['#ff8000', '#ff8000', '#ff8000', '#ff8000', '#ff8000'],
						  seriesType: 'bars',
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico1'));
						chart.draw(data, options);

					}
					
					function drawChartPastelVentasContadoMensuales() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasContadoMensuales
						);

						var options = {
						  title: '',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};

						var chart = new google.visualization.PieChart(document.getElementById('grafico2'));
						chart.draw(data, options);

					}

					function drawChartPastelVentasCreditoMensuales() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasCreditoMensuales
						);

						var options = {
						  title: '',
						  colors: ['#00C868', '#006E98', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						  seriesType: 'bars'
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico3'));
						chart.draw(data, options);

					}
							
					
					function drawChartBarraCapitalMensual() {

						var data = google.visualization.arrayToDataTable(
							objDataPagosMensuales
						);

						var options = {
						  title: '',
						  colors: ['#006E98', '#00C868', '#ec8f6e', '#f3b49f', '#f6c7b6'],
						};

						var chart = new google.visualization.ComboChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}
											
				</script>
				