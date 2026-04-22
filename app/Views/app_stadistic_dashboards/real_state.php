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

<!--
<div class="panel" style="margin-bottom:20px; height: calc(100vh - 200px);">
   <iframe width="100%" height="100%" 
        src="https://datastudio.google.com/embed/reporting/bc67583a-fdb3-478a-9272-b7d4eb6a3697/page/page_12345" 
        frameborder="0" style="border:0" 
        allowfullscreen 
        sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
    </iframe>
</div>
-->
                

<div class="row"  >
    <div class="col-lg-12">
        <div class="panel" style="margin-bottom:20px; height: calc(100vh - 200px);">
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-health"></i></div>
                <h4>Dashboard con Google Analytics</h4>
                <a href="#" class="minimize"></a>
            </div>

            <div class="panel-body" style="height: calc(100% - 50px); padding: 0;">
                <iframe width="100%" height="100%" 
                    src="https://datastudio.google.com/embed/reporting/bc67583a-fdb3-478a-9272-b7d4eb6a3697/page/page_12345" 
                    frameborder="0" style="border:0" 
                    allowfullscreen 
                    sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
                </iframe>

            </div>
        </div>
    </div>
</div>




<script>
    
    //wg-$(document).ready(function(){
    //wg-        // Carga Google Charts
    //wg-        google.charts.load('current',{packages:['corechart']});


    // ============================================================
    //wg-// PROPIEDADES POR AGENTE
    // ============================================================

    //wg-var objDataSource8 = new Array();

    //wg-// PHP deshabilitado (no se ejecuta)
    //wg-var RealState_get_PropiedadesPorAgentes = JSON.parse(
    //wg-    '<?php /* echo json_encode($RealState_get_PropiedadesPorAgentes); */ ?>'
    //wg-);

    //wg-objDataSource8.push(new Array("Agente","Cantidad"));

    //wg-for(var i = 0 ; i < RealState_get_PropiedadesPorAgentes.length;i++)
    //wg-{
    //wg-    objDataSource8.push(
    //wg-        new Array(
    //wg-            RealState_get_PropiedadesPorAgentes[i].Indicador,
    //wg-            parseInt(RealState_get_PropiedadesPorAgentes[i].Cantidad)
    //wg-        )
    //wg-    );
    //wg-}

    //wg-google.charts.setOnLoadCallback(function () {
    //wg-    var data = google.visualization.arrayToDataTable(objDataSource8);

    //wg-    var options2 = {
    //wg-        title: 'Enlistamiento de propiedades',
    //wg-        colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],
    //wg-    };

    //wg-    var chart = new google.visualization.AreaChart(document.getElementById('grafico8'));
    //wg-    chart.draw(data, options2);
    //wg-});


    // ============================================================
    //wg-// PROPIEDADES POR AGENTE VS METAS
    // ============================================================

    //wg-var RealState_get_PropiedadesPorAgentesMetas = JSON.parse(
    //wg-    '<?php /* echo json_encode($RealState_get_PropiedadesPorAgentesMetas); */ ?>'
    //wg-);

    //wg-var objDataSource9 = new Array();
    //wg-objDataSource9.push(new Array("Agente","Cantidad"));

    //wg-for(var i = 0 ; i < RealState_get_PropiedadesPorAgentesMetas.length;i++)
    //wg-{
    //wg-    objDataSource9.push(
    //wg-        new Array(
    //wg-            RealState_get_PropiedadesPorAgentesMetas[i].Indicador,
    //wg-            parseInt(RealState_get_PropiedadesPorAgentesMetas[i].Cantidad)
    //wg-        )
    //wg-    );
    //wg-}

    //wg-google.charts.setOnLoadCallback(function () {

    //wg-    var data = google.visualization.arrayToDataTable(objDataSource9);

    //wg-    var options2 = {
    //wg-        title: 'Enlistamiento de propiedades metas',
    //wg-        /*isStacked: 'percent',*/
    //wg-        colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],
    //wg-    };

    //wg-    var chart = new google.visualization.AreaChart(document.getElementById('grafico9'));
    //wg-    chart.draw(data, options2);

    //wg-});


    // ============================================================
    //wg-// RENDIMIENTO ANUAL DE VENTAS
    // ============================================================

    //wg-var objDataSource10 = new Array();

    //wg-var RealState_get_PropiedadesRendimientoAnualVentas = JSON.parse(
    //wg-    '<?php /* echo json_encode($RealState_get_PropiedadesRendimientoAnualVentas); */ ?>'
    //wg-);

    //wg-objDataSource10.push(new Array("Clasificacion","Cantidad"));

    //wg-for(var i = 0 ; i < RealState_get_PropiedadesRendimientoAnualVentas.length;i++)
    //wg-{
    //wg-    objDataSource10.push(
    //wg-        new Array(
    //wg-            RealState_get_PropiedadesRendimientoAnualVentas[i].Indicador,
    //wg-            parseInt(RealState_get_PropiedadesRendimientoAnualVentas[i].Cantidad)
    //wg-        )
    //wg-    );
    //wg-}

    //wg-google.charts.setOnLoadCallback(function () {

    //wg-    var data = google.visualization.arrayToDataTable(objDataSource10);

    //wg-    var options = {
    //wg-        title: 'Rendimiento anual de ventas',
    //wg-        colors: ['#FF5733', '#FFC300', '#FF85A2', '#FF33FF', '#33FFBD'],
    //wg-        vAxis: {title: 'Clasificacion'},
    //wg-        hAxis: {title: 'Agente'},
    //wg-        seriesType: 'bars',
    //wg-        series: {5: {type: 'line'}}
    //wg-    };

    //wg-    var chart = new google.visualization.ComboChart(document.getElementById('grafico10'));
    //wg-    chart.draw(data, options);

    //wg-});


    // ============================================================
    //wg-// RENDIMIENTO ANUAL DE ENLISTAMIENTO
    // ============================================================

    //wg-var objDataSource11 = new Array();

    //wg-var RealState_get_PropiedadesRendimientoAnualEnlistamiento = JSON.parse(
    //wg-    '<?php /* echo json_encode($RealState_get_PropiedadesRendimientoAnualEnlistamiento); */ ?>'
    //wg-);

    //wg-objDataSource11.push(new Array("Agente","Cantidad"));

    //wg-for(var i = 0 ; i < RealState_get_PropiedadesRendimientoAnualEnlistamiento.length;i++)
    //wg-{
    //wg-    objDataSource11.push(
    //wg-        new Array(
    //wg-            RealState_get_PropiedadesRendimientoAnualEnlistamiento[i].Indicador,
    //wg-            parseInt(RealState_get_PropiedadesRendimientoAnualEnlistamiento[i].Cantidad)
    //wg-        )
    //wg-    );
    //wg-}

    //wg-google.charts.setOnLoadCallback(function () {

    //wg-    var data = google.visualization.arrayToDataTable(objDataSource11);

    //wg-    var options = {
    //wg-        title: 'Rendimiento anual de enlistamiento',
    //wg-        colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
    //wg-    };

    //wg-    var chart = new google.visualization.BarChart(document.getElementById('grafico11'));
    //wg-    chart.draw(data, options);

    //wg-});


    // ============================================================
    //wg-// FILTRO DE FECHAS
    // ============================================================

    //wg-$(document).on("click","#btnSalvarFiltro",function(){

    //wg-    var txtDateStart  = $("#txtDateStart").val();
    //wg-    var txtDateFinish = $("#txtDateFinish").val();

    //wg-    fnWaitOpen();

    //wg-    window.location = "<?php /* echo base_url(); */ ?>/app_stadistic_dashboards/real_state/txtDateStart/"+txtDateStart+"/txtDateFinish/"+txtDateFinish;

    //wg-});

    //wg-$('#txtDateStart').datepicker({format:"yyyy-mm-dd"});
    //wg-$('#txtDateFinish').datepicker({format:"yyyy-mm-dd"});


    //wg-});

</script>