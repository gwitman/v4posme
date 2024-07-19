<link href="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css"
      rel="stylesheet"/>

<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.pie.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.resize.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.orderBars.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/jquery.flot.time.min.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/flot/date.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>

<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/gauge/justgage.1.0.1.min.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/gauge/raphael.2.1.0.min.js"></script>

<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/sparklines/jquery.sparkline.min.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>


<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/jquery.isloading.min.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/js/chart-google/loader.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jquery.number.min.js"></script>
<link href="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/js/is-loading-master/style.css" rel="stylesheet"/>
<script src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-dashboard"></i>
        Estadisticos
    </h1>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-health"></i></div>
                <h4>Parametros</h4>
                <a href="#" class="minimize"></a>
            </div><!-- End .panel-heading -->

            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label" for="datepicker">Inicio</label>
                    <div class="col-lg-8">
                        <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                            <input size="16" class="form-control" type="text" name="txtDateStart" id="txtDateStart"
                                   value="<?php echo $firstDate; ?>">
                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label" for="datepicker">Fin</label>
                    <div class="col-lg-8">
                        <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                            <input size="16" class="form-control" type="text" name="txtDateFinish" id="txtDateFinish"
                                   value="<?php echo $lastDate; ?>">
                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label" for="btnSalvarFiltro"></label>
                    <div class="col-lg-8">
                        <button type="button" id="btnSalvarFiltro" class="btn btn-success">Filtrar</button>
                    </div>
                </div>


            </div><!-- End .panel-body -->
        </div><!-- End .widget -->


    </div>

</div>

<div class="row"  >
    <div class="col-lg-6">
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-health"></i></div>
                <h4>Enlistamiento de propiedad metas</h4>
                <a href="#" class="minimize"></a>
            </div><!-- End .panel-heading -->

            <div class="panel-body">
                <div id="grafico9" style="height:300px" ></div>
            </div><!-- End .panel-body -->
        </div><!-- End .widget -->
    </div>
    <div class="col-lg-6">
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-health"></i></div>
                <h4>Rendimiento anual de venta</h4>
                <a href="#" class="minimize"></a>
            </div><!-- End .panel-heading -->

            <div class="panel-body">
                <div id="grafico10" style="height:300px" ></div>
            </div><!-- End .panel-body -->
        </div><!-- End .widget -->
    </div>
</div>


<div class="row"  >
    <div class="col-lg-6">
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-health"></i></div>
                <h4>Rendimiento anual de enlistamiento</h4>
                <a href="#" class="minimize"></a>
            </div><!-- End .panel-heading -->

            <div class="panel-body">
                <div id="grafico11" style="height:300px" ></div>
            </div><!-- End .panel-body -->
        </div><!-- End .widget -->
    </div>
    <div class="col-lg-6">
        <div class="panel" style="margin-bottom:20px;">
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-health"></i></div>
                <h4>Enlistamiento de propiedades</h4>
                <a href="#" class="minimize"></a>
            </div><!-- End .panel-heading -->

            <div class="panel-body">
                <div id="grafico8" style="height:300px" ></div>
            </div><!-- End .panel-body -->
        </div><!-- End .widget -->
    </div>

</div>



<script>

    $(document).ready(function(){
            //https://www.w3schools.com/js/js_graphics_google_chart.asp
            google.charts.load('current',{packages:['corechart']});


            //Propiedades por Agente
            ///
            ////////////////////////////////////////////////
            var objDataSource8	 												= new Array();
            var RealState_get_PropiedadesPorAgentes			 					= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesPorAgentes); ?>');
            objDataSource8.push(new Array("Agente","Cantidad"));
            for(var i = 0 ; i < RealState_get_PropiedadesPorAgentes.length;i++)
            {
                objDataSource8.push(
                    new Array(
                        RealState_get_PropiedadesPorAgentes[i].Indicador,
                        parseInt(RealState_get_PropiedadesPorAgentes[i].Cantidad)
                    )
                );
            }


            google.charts.setOnLoadCallback(
                function () {

                    var data = google.visualization.arrayToDataTable(
                        objDataSource8
                    );

                    var options2 = {
                        title: 'Enlistamiento de propiedades',
                        colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],
                    };

                    var chart = new google.visualization.AreaChart(document.getElementById('grafico8'));
                    chart.draw(data, options2);

                }
            );


            //Propiedades por Agente vs Metas
            ///
            ////////////////////////////////////////////////
            var RealState_get_PropiedadesPorAgentesMetas 				 		= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesPorAgentesMetas); ?>');
            var objDataSource9	 												= new Array();
            objDataSource9.push(new Array("Agente","Cantidad"));
            for(var i = 0 ; i < RealState_get_PropiedadesPorAgentesMetas.length;i++)
            {
                objDataSource9.push(
                    new Array(
                        RealState_get_PropiedadesPorAgentesMetas[i].Indicador,
                        parseInt(RealState_get_PropiedadesPorAgentesMetas[i].Cantidad)
                    )
                );
            }


            google.charts.setOnLoadCallback(
                function () {

                    var data = google.visualization.arrayToDataTable(
                        objDataSource9
                    );

                    var options2 = {
                        title: 'Enlistamiento de propiedades metas',
						/*isStacked: 'percent',*/
                        colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],
						/*
						hAxis: {
							title: 'Year',
							format: '0'
						},
						vAxis: {
							title: 'Percentage',
							format: '#%'
						}
						*/ 
                    };

                    var chart = new google.visualization.AreaChart(document.getElementById('grafico9'));
                    chart.draw(data, options2);

                }
            );


            //Agente Rendimiento Anual de Ventas propiedades
            ///
            ////////////////////////////////////////////////
            var objDataSource10	 												= new Array();
            var RealState_get_PropiedadesRendimientoAnualVentas			 		= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesRendimientoAnualVentas); ?>');
            objDataSource10.push(new Array("Clasificacion","Cantidad"));
            for(var i = 0 ; i < RealState_get_PropiedadesRendimientoAnualVentas.length;i++)
            {
                objDataSource10.push(
                    new Array(
                        RealState_get_PropiedadesRendimientoAnualVentas[i].Indicador,
                        parseInt(RealState_get_PropiedadesRendimientoAnualVentas[i].Cantidad)
                    )
                );
            }

            google.charts.setOnLoadCallback(
                function () {

                    var data = google.visualization.arrayToDataTable(
                        objDataSource10
                    );

                    var options = {

                        title: 'Rendimiento anual de ventas',
                        colors: ['#FF5733', '#FFC300', '#FF85A2', '#FF33FF', '#33FFBD'],
                        vAxis: {title: 'Clasificacion'},
                        hAxis: {title: 'Agente'},
                        seriesType: 'bars',
                        series: {5: {type: 'line'}}



                    };

                    var chart = new google.visualization.ComboChart(document.getElementById('grafico10'));
                    chart.draw(data, options);

                }
            );



            //Propiedades Agente Rendimiento anual de enlistamiento
            ///
            ////////////////////////////////////////////////
            var objDataSource11	 												= new Array();
            var RealState_get_PropiedadesRendimientoAnualEnlistamiento		 	= JSON.parse('<?php echo json_encode($RealState_get_PropiedadesRendimientoAnualEnlistamiento); ?>');
            objDataSource11.push(new Array("Agente","Cantidad"));
            for(var i = 0 ; i < RealState_get_PropiedadesRendimientoAnualEnlistamiento.length;i++)
            {
                objDataSource11.push(
                    new Array(
                        RealState_get_PropiedadesRendimientoAnualEnlistamiento[i].Indicador,
                        parseInt(RealState_get_PropiedadesRendimientoAnualEnlistamiento[i].Cantidad)
                    )
                );
            }

            google.charts.setOnLoadCallback(
                function () {

                    var data = google.visualization.arrayToDataTable(
                        objDataSource11
                    );

                    var options = {
                        title: 'Rendimiento anual de enlistamiento',
                        colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('grafico11'));
                    chart.draw(data, options);

                }
            );






            $(document).on("click","#btnSalvarFiltro",function(){
                var txtDateStart		=	$("#txtDateStart").val();
                var txtDateFinish		=	$("#txtDateFinish").val();
                fnWaitOpen();
                window.location	= "<?php echo base_url(); ?>/app_stadistic_dashboards/real_state/txtDateStart/"+txtDateStart+"/txtDateFinish/"+txtDateFinish;

            });

            $('#txtDateStart').datepicker({format:"yyyy-mm-dd"});
            $('#txtDateFinish').datepicker({format:"yyyy-mm-dd"});



        }
    );








</script>