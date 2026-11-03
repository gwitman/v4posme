<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
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

<style>
    /* ==========================================================
       Estadisticas de inmuebles: tablero de Data Studio embebido
       El contenedor define el alto; el iframe lo ocupa completo.
       ========================================================== */
    .pv2 .pv2-dash-frame { position:relative; width:100%; height:calc(100vh - 230px); min-height:480px; border-radius:10px; overflow:hidden; background:var(--pv2-soft); }
    .pv2 .pv2-dash-frame iframe { position:absolute; top:0; right:0; bottom:0; left:0; width:100%; height:100%; border:0; }
    .pv2 .pv2-dash-frame .pv2-empty { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:80%; max-width:360px; background:#fff; }
    .pv2 .pv2-dash-frame.is-loaded .pv2-empty { display:none; }
    @media (max-width:767px) {
        .pv2 .pv2-dash-frame { height:75vh; min-height:0; }
    }
</style>

<div class="pv2<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-dashboard"></i></div>
            <div>
                <p class="pv2-header-title">Estadísticas de inmuebles</p>
                <p class="pv2-header-sub">Tablero de Google Data Studio con el comportamiento de las propiedades</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="https://datastudio.google.com/embed/reporting/bc67583a-fdb3-478a-9272-b7d4eb6a3697/page/page_12345" target="_blank" rel="noopener" class="btn btn-default"><i class="icon16 i-new-tab"></i> Abrir en pestaña nueva</a>
        </div>
    </div>
    <!-- /encabezado -->

    <!-- tablero -->
    <div class="pv2-card">
        <div class="pv2-dash-frame" id="pv2DashFrame">
            <div class="pv2-empty"><i class="icon20 i-dashboard"></i>Cargando tablero…</div>
            <iframe
                src="https://datastudio.google.com/embed/reporting/bc67583a-fdb3-478a-9272-b7d4eb6a3697/page/page_12345"
                frameborder="0"
                allowfullscreen
                onload="document.getElementById('pv2DashFrame').className += ' is-loaded';"
                sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-downloads">
            </iframe>
        </div>
    </div>
    <!-- /tablero -->

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