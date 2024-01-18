				<!-- ./ page heading -->		
				
				<script>	
					//https://www.w3schools.com/js/js_graphics_google_chart.asp
					
					var objTransactionMaster 		= JSON.parse('<?php echo json_encode($objDetail); ?>');	
					var objListCategoria 			= jLinq.from(jLinq.from(objTransactionMaster).select(function(a){ return a.nameCategory })).distinct();
					var objListDay 					= jLinq.from(jLinq.from(objTransactionMaster).select(function(a){ return a.dayOfMonth })).distinct();
					var objListProductos 			= jLinq.from(jLinq.from(objTransactionMaster).select(function(a){ return a.itemName })).distinct();
					
					//Data source de Ventas por Dia
					//---------------------------------------
					//---------------------------------------
					var objDataSourceVentasPorDia = new Array();
					objDataSourceVentasPorDia.push(new Array("Día","Venta"));
					for(var i=0; i< objListDay.length; i++){
						var dia_ 	= objListDay[i];
						var venta_  = jLinq.from(jLinq.from(objTransactionMaster).where(function(a){ return a.dayOfMonth == dia_}).select(function(a){ return parseFloat(a.amount) })).sum().result;
						
						objDataSourceVentasPorDia.push(new Array(dia_,venta_));	
					}
					
					//Data Source de ventas por Categoria
					//---------------------------------------
					//---------------------------------------
					var objDataSourceVentasPorCategoria = new Array();
					objDataSourceVentasPorCategoria.push(new Array("Categoria","Venta"));
					for(var i=0; i< objListCategoria.length; i++){
						var category_ 	= objListCategoria[i];
						var venta_  = jLinq.from(jLinq.from(objTransactionMaster).where(function(a){ return a.nameCategory == category_}).select(function(a){ return parseFloat(a.amount) })).sum().result;
						
						objDataSourceVentasPorCategoria.push(new Array(category_,venta_));	
					}
					
					//Data Source Productos mas Vendidos
					//---------------------------------------
					//---------------------------------------
					var objDataSourceProductosMasVendidos = new Array();
					var objArrayTemporal = new Array();		
					
					//Sumarizar por producto
					for(var i=0; i< objListProductos.length; i++){
						var product_ 	= objListProductos[i];
						var venta_  = jLinq.from(jLinq.from(objTransactionMaster).where(function(a){ return a.itemName == product_}).select(function(a){ return parseFloat(a.quantity) })).sum().result;						
						objArrayTemporal.push({producto:product_, amount : venta_ });	
					}					
					//Ordenar los mas vendidos
					objArrayTemporal = objArrayTemporal.sort((firstItem, secondItem) => firstItem.amount + secondItem.amount);
					
					//Obtener los ultimos 10 elementos					
					objDataSourceProductosMasVendidos.push(new Array("Productos","Venta"));
					var numeroDeElementos 		 = objArrayTemporal.length;
					var numeroDeMaximosElementos = objArrayTemporal.length > 20 ? 20 : objArrayTemporal.length;
					
					for(var i = numeroDeElementos ; i > (numeroDeElementos - numeroDeMaximosElementos) ; i--)
					{
						
						objDataSourceProductosMasVendidos.push(
							new Array(
								objArrayTemporal[i-1].producto, objArrayTemporal[i-1].amount 
							)
						);	
					}
					
					
					
					$(document).ready(function(){
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});	
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						
						
						$(document).on("click","#print-btn-report",function(){
							var startOn					=	$("#txtStartOn").val();	
							var endOn					=	$("#txtEndOn").val();
							
							if(!( startOn == "" || endOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_sales_report/sales_detail_format_chart/viewReport/true/startOn/"+startOn+"/endOn/"+endOn;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
							
						});
						
						
						
					
						
						
						google.charts.load('current',{packages:['corechart']});
						google.charts.setOnLoadCallback(drawChartLine);						  
						google.charts.setOnLoadCallback(drawChartBarraHorizontal);		
						google.charts.setOnLoadCallback(drawChartBarraCirculo);		
						google.charts.setOnLoadCallback(drawChartBarraHorizontalProductosMasVendidos);		
						
						  
						  
					});		
					
					function drawChartLine() {
						// Set Data
						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasPorDia
						 );
						// Set Options
						var options = {
						  title: 'Ventas por Día',
						  hAxis: {title: 'Dias'},
						  vAxis: {title: 'Ventas'},
						  legend: 'none'
						};
						// Draw Chart
						var chart = new google.visualization.LineChart(document.getElementById('grafico1'));
						chart.draw(data, options);
					}
					
					function drawChartBarraHorizontal() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasPorCategoria
						);

						var options = {
						  title: 'Ventas por Categoria'
						};

						var chart = new google.visualization.BarChart(document.getElementById('grafico2'));
						chart.draw(data, options);

					}
					function drawChartBarraCirculo() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceVentasPorCategoria
						);

						var options = {
						  title: 'Ventas por Categoria'
						};

						var chart = new google.visualization.PieChart(document.getElementById('grafico3'));
						chart.draw(data, options);

					}
					
					function drawChartBarraHorizontalProductosMasVendidos() {

						var data = google.visualization.arrayToDataTable(
							objDataSourceProductosMasVendidos
						);

						var options = {
						  title: 'Productos mas Vendidos'
						};

						var chart = new google.visualization.BarChart(document.getElementById('grafico4'));
						chart.draw(data, options);

					}
											
				</script>