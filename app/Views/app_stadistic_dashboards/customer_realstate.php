<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
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
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>

<style>
    /* ==========================================================
       Estadisticas de clientes (bienes raices)
       Alturas de los graficos por clase; Google Charts toma el
       alto del contenedor, por eso no se usa style fijo.
       ========================================================== */
    .pv2 .pv2-stats-row { margin-left:-7px; margin-right:-7px; }
    .pv2 .pv2-stats-row > [class*="col-"] { padding-left:7px; padding-right:7px; }
    .pv2 .pv2-chart { width:100%; height:300px; }
    .pv2 #grafico5.pv2-chart { height:380px; }
    .pv2 .pv2-chart.is-empty { height:auto; }
    .pv2 .pv2-filter-btn { width:100%; height:38px; border-radius:8px; }
    .pv2 .pv2-filter-spacer { visibility:hidden; }
    @media (max-width:767px) {
        .pv2 .pv2-chart { height:260px; }
        .pv2 #grafico5.pv2-chart { height:320px; }
        .pv2 .pv2-chart.is-empty { height:auto; }
        .pv2 .pv2-filter-btn { height:44px; }
        .pv2 .pv2-filter-spacer { display:none; }
    }
</style>

<div class="pv2<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-dashboard"></i></div>
            <div>
                <p class="pv2-header-title">Estadísticas de clientes
                    <span class="pv2-chip"><?php echo htmlspecialchars((string)$firstDate, ENT_QUOTES, 'UTF-8'); ?> – <?php echo htmlspecialchars((string)$lastDate, ENT_QUOTES, 'UTF-8'); ?></span>
                </p>
                <p class="pv2-header-sub">Fuentes de contacto, intereses, agentes y cierres en el rango seleccionado</p>
            </div>
        </div>
    </div>
    <!-- /encabezado -->

    <!-- filtro -->
    <div class="pv2-card">
        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Rango de fechas</p>
        <form autocomplete="off" onsubmit="return false">
            <div class="row pv2-stats-row">
                <div class="col-xs-6 col-sm-4">
                    <div class="form-group">
                        <label class="pv2-label" for="txtDateStart">Inicio</label>
                        <input class="form-control" type="text" name="txtDateStart" id="txtDateStart" autocomplete="off" value="<?php echo htmlspecialchars((string)$firstDate, ENT_QUOTES, 'UTF-8'); ?>" >
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4">
                    <div class="form-group">
                        <label class="pv2-label" for="txtDateFinish">Fin</label>
                        <input class="form-control" type="text" name="txtDateFinish" id="txtDateFinish" autocomplete="off" value="<?php echo htmlspecialchars((string)$lastDate, ENT_QUOTES, 'UTF-8'); ?>" >
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label class="pv2-label pv2-filter-spacer" for="btnSalvarFiltro">Filtrar</label>
                        <button type="button" id="btnSalvarFiltro" class="btn btn-success pv2-filter-btn"><i class="icon16 i-filter"></i> Filtrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /filtro -->

    <!-- indicadores calculados en el cliente -->
    <div class="pv2-kpis">
        <div class="pv2-kpi k-info">
            <div class="pv2-kpi-icon"><i class="icon16 i-phone"></i></div>
            <div class="pv2-kpi-body">
                <span class="pv2-kpi-label">Total de contactos</span>
                <span class="pv2-kpi-value" id="kpiTotalContactos">0</span>
            </div>
        </div>
        <div class="pv2-kpi k-ok">
            <div class="pv2-kpi-icon"><i class="icon16 i-checkmark-4"></i></div>
            <div class="pv2-kpi-body">
                <span class="pv2-kpi-label">Clientes cerrados</span>
                <span class="pv2-kpi-value" id="kpiClientesCerrados">0</span>
            </div>
        </div>
        <div class="pv2-kpi k-purple">
            <div class="pv2-kpi-icon"><i class="icon16 i-users"></i></div>
            <div class="pv2-kpi-body">
                <span class="pv2-kpi-label">Agentes activos</span>
                <span class="pv2-kpi-value" id="kpiAgentesActivos">0</span>
            </div>
        </div>
        <div class="pv2-kpi k-warn">
            <div class="pv2-kpi-icon"><i class="icon16 i-star"></i></div>
            <div class="pv2-kpi-body">
                <span class="pv2-kpi-label">Interés principal</span>
                <span class="pv2-kpi-value" id="kpiInteresPrincipal" title="">—</span>
            </div>
        </div>
    </div>
    <!-- /indicadores -->

    <!-- graficos -->
    <div class="row pv2-stats-row">
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-phone"></i></span> Fuente de contacto</p>
                <div id="grafico1" class="pv2-chart"></div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-star"></i></span> Interés del cliente</p>
                <div id="grafico2" class="pv2-chart"></div>
            </div>
        </div>
    </div>

    <div class="row pv2-stats-row">
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-home"></i></span> Tipo de propiedad</p>
                <div id="grafico3" class="pv2-chart"></div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-users"></i></span> Clientes asignados</p>
                <div id="grafico4" class="pv2-chart"></div>
            </div>
        </div>
    </div>

    <div class="row pv2-stats-row">
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-stats"></i></span> Clasificación por agente</p>
                <div id="grafico5" class="pv2-chart"></div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-checkmark-4"></i></span> Cierre de clientes</p>
                <div id="grafico7" class="pv2-chart"></div>
            </div>
        </div>
    </div>
    <!-- /graficos -->

</div>



<script>

    /* ==========================================================
       Funciones puras de transformacion (sin DOM) para facilitar pruebas
       ========================================================== */

    //Devuelve siempre un arreglo (el controlador puede enviar null u objeto vacio)
    function fnStatsArreglo(valor)
    {
        return Array.isArray(valor) ? valor : [];
    }

    //Convierte a entero; nunca devuelve NaN
    function fnStatsEntero(valor)
    {
        var numero = parseInt(valor, 10);
        return isNaN(numero) ? 0 : numero;
    }

    //Entero con separador de miles (coma): 12345 -> "12,345"
    function fnStatsFormatoEntero(valor)
    {
        var numero = Math.round(Number(valor) || 0);
        var signo  = numero < 0 ? "-" : "";
        return signo + String(Math.abs(numero)).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    //Arma la tabla [encabezado, [Indicador, Cantidad], ...] que espera arrayToDataTable
    function fnStatsTabla(rows, encabezado)
    {
        var tabla = [encabezado];
        var datos = fnStatsArreglo(rows);
        for (var i = 0; i < datos.length; i++)
        {
            tabla.push([datos[i].Indicador, fnStatsEntero(datos[i].Cantidad)]);
        }
        return tabla;
    }

    //Valores distintos conservando el orden de aparicion
    function fnStatsDistinct(valores)
    {
        var resultado = [];
        for (var i = 0; i < valores.length; i++)
        {
            if (resultado.indexOf(valores[i]) === -1)
            {
                resultado.push(valores[i]);
            }
        }
        return resultado;
    }

    //Pivote Agente x Indicador (misma logica que la version jLinq anterior):
    //fila = agente, columna = indicador, celda = suma de Cantidad
    function fnStatsPivote(rows)
    {
        var datos       = fnStatsArreglo(rows);
        var indicadores = fnStatsDistinct(datos.map(function (a) { return a.Indicador; }));
        var agentes     = fnStatsDistinct(datos.map(function (a) { return a.Agente; }));
        var tabla       = [["Agente"].concat(indicadores)];

        for (var i = 0; i < agentes.length; i++)
        {
            var fila = [agentes[i]];
            for (var ix = 0; ix < indicadores.length; ix++)
            {
                var total = 0;
                for (var k = 0; k < datos.length; k++)
                {
                    if (datos[k].Agente == agentes[i] && datos[k].Indicador == indicadores[ix])
                    {
                        var cantidad = parseFloat(datos[k].Cantidad);
                        total += isNaN(cantidad) ? 0 : cantidad;
                    }
                }
                fila.push(total);
            }
            tabla.push(fila);
        }

        return { indicadores: indicadores, agentes: agentes, tabla: tabla };
    }

    //Indicadores de la fila superior
    //datos = { fuenteContacto, interes, porAgentes, clasificacionPorAgentes, cerrados }
    function fnStatsKpis(datos)
    {
        datos = datos || {};
        var fuente        = fnStatsArreglo(datos.fuenteContacto);
        var interes       = fnStatsArreglo(datos.interes);
        var porAgentes    = fnStatsArreglo(datos.porAgentes);
        var clasificacion = fnStatsArreglo(datos.clasificacionPorAgentes);
        var cerrados      = fnStatsArreglo(datos.cerrados);

        var totalContactos   = 0;
        var clientesCerrados = 0;
        var i;

        for (i = 0; i < fuente.length; i++)   { totalContactos   += fnStatsEntero(fuente[i].Cantidad); }
        for (i = 0; i < cerrados.length; i++) { clientesCerrados += fnStatsEntero(cerrados[i].Cantidad); }

        //Agentes activos: en "clientes por agente" el nombre viene en Indicador;
        //si no hay filas se toma el campo Agente de la clasificacion por agente
        var agentes = [];
        if (porAgentes.length > 0)
        {
            agentes = porAgentes.map(function (a) { return (a.Agente !== undefined && a.Agente !== null) ? a.Agente : a.Indicador; });
        }
        else
        {
            agentes = clasificacion.map(function (a) { return a.Agente; });
        }
        agentes = fnStatsDistinct(agentes.filter(function (a) { return a !== undefined && a !== null && String(a) !== ""; }));

        //Interes principal: Indicador con mayor Cantidad (el primero en caso de empate)
        var interesPrincipal = "—";
        var mayor            = null;
        for (i = 0; i < interes.length; i++)
        {
            var cantidad = fnStatsEntero(interes[i].Cantidad);
            if (mayor === null || cantidad > mayor)
            {
                mayor            = cantidad;
                interesPrincipal = (interes[i].Indicador === null || interes[i].Indicador === undefined || String(interes[i].Indicador) === "") ? "—" : String(interes[i].Indicador);
            }
        }

        return {
            totalContactos   : totalContactos,
            clientesCerrados : clientesCerrados,
            agentesActivos   : agentes.length,
            interesPrincipal : interesPrincipal
        };
    }

    //Valida el rango del filtro; devuelve "" si es valido o el mensaje de error
    function fnStatsValidarRango(inicio, fin)
    {
        var formato = /^\d{4}-\d{2}-\d{2}$/;
        if (!formato.test(inicio)) { return "La fecha de inicio debe tener el formato AAAA-MM-DD"; }
        if (!formato.test(fin))    { return "La fecha de fin debe tener el formato AAAA-MM-DD"; }
        if (inicio > fin)          { return "La fecha de inicio no puede ser mayor que la fecha de fin"; }
        return "";
    }

    /* ==========================================================
       Dibujo de graficos
       ========================================================== */
    var pv2StatsGraficos   = [];    //pares { chart, data, options } para redibujar
    var pv2StatsTimeResize = null;

    //Estado vacio dentro del contenedor del grafico
    function fnStatsMostrarVacio(id)
    {
        $("#" + id)
            .addClass("is-empty")
            .html('<div class="pv2-empty"><i class="icon20 i-stats"></i>Sin datos en el rango seleccionado</div>');
    }

    //Ajusta opciones comunes: area del grafico y leyenda abajo en telefono
    function fnStatsOpciones(base)
    {
        var opciones = $.extend(true, {}, base);
        if (!opciones.chartArea) { opciones.chartArea = { width: '85%', height: '70%' }; }

        var leyendaOculta = opciones.legend && opciones.legend.position === 'none';
        if (pv2IsMobile() && !leyendaOculta)
        {
            opciones.legend          = opciones.legend || {};
            opciones.legend.position = 'bottom';
        }
        return opciones;
    }

    //Crea el grafico, lo dibuja y lo registra para el redibujado
    function fnStatsDibujar(id, tipo, tabla, opcionesBase)
    {
        var data  = google.visualization.arrayToDataTable(tabla);
        var chart = new google.visualization[tipo](document.getElementById(id));
        var item  = { chart: chart, data: data, options: opcionesBase };
        chart.draw(data, fnStatsOpciones(opcionesBase));
        pv2StatsGraficos.push(item);
    }

    //Redibuja todos los graficos (ancho/alto nuevos y leyenda segun dispositivo)
    function fnStatsRedibujar()
    {
        for (var i = 0; i < pv2StatsGraficos.length; i++)
        {
            var item = pv2StatsGraficos[i];
            item.chart.draw(item.data, fnStatsOpciones(item.options));
        }
    }

    $(document).ready(function(){
            //https://www.w3schools.com/js/js_graphics_google_chart.asp
            google.charts.load('current',{packages:['corechart']});

            //Datos del controlador (json_encode directo, sin JSON.parse de cadena)
            var RealState_get_ClienteFuenteDeContacto          = fnStatsArreglo(<?php echo json_encode($RealState_get_ClienteFuenteDeContacto, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>);
            var RealState_get_ClientesInteres                  = fnStatsArreglo(<?php echo json_encode($RealState_get_ClientesInteres, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>);
            var RealState_get_ClientesTipoPropiedad            = fnStatsArreglo(<?php echo json_encode($RealState_get_ClientesTipoPropiedad, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>);
            var RealState_get_ClientesPorAgentes               = fnStatsArreglo(<?php echo json_encode($RealState_get_ClientesPorAgentes, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>);
            var RealState_get_ClientesClasificacionPorAgentes  = fnStatsArreglo(<?php echo json_encode($RealState_get_ClientesClasificacionPorAgentes, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>);
            var RealState_get_ClientesCerrados                 = fnStatsArreglo(<?php echo json_encode($RealState_get_ClientesCerrados, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>);


            //Indicadores (KPI)
            ///
            ////////////////////////////////////////////////
            var objKpis = fnStatsKpis({
                fuenteContacto          : RealState_get_ClienteFuenteDeContacto,
                interes                 : RealState_get_ClientesInteres,
                porAgentes              : RealState_get_ClientesPorAgentes,
                clasificacionPorAgentes : RealState_get_ClientesClasificacionPorAgentes,
                cerrados                : RealState_get_ClientesCerrados
            });
            $("#kpiTotalContactos").text(fnStatsFormatoEntero(objKpis.totalContactos));
            $("#kpiClientesCerrados").text(fnStatsFormatoEntero(objKpis.clientesCerrados));
            $("#kpiAgentesActivos").text(fnStatsFormatoEntero(objKpis.agentesActivos));
            $("#kpiInteresPrincipal").text(objKpis.interesPrincipal).attr("title", objKpis.interesPrincipal);


            //Clientes por Contactos
            ///
            ////////////////////////////////////////////////
            var objDataSource1 = fnStatsTabla(RealState_get_ClienteFuenteDeContacto, ["Forma","Cantidad"]);
            if (RealState_get_ClienteFuenteDeContacto.length == 0)
            {
                fnStatsMostrarVacio('grafico1');
            }
            else
            {
                google.charts.setOnLoadCallback(
                    function () {
                        fnStatsDibujar('grafico1', 'ColumnChart', objDataSource1, {
                            colors: ['#FF5733', '#FFC300', '#FF85A2', '#FF33FF', '#33FFBD'],
                            legend: { position: 'none' },
                            vAxis: { title: 'Cantidad', minValue: 0 },
                            hAxis: { title: 'Fuente' },
                            bar: { groupWidth: '60%' },
                            chartArea: { width: '85%', height: '70%' }
                        });
                    }
                );
            }


            //Clientes por Interes
            ///
            ////////////////////////////////////////////////
            var objDataSource2 = fnStatsTabla(RealState_get_ClientesInteres, ["Interes","Cantidad"]);
            if (RealState_get_ClientesInteres.length == 0)
            {
                fnStatsMostrarVacio('grafico2');
            }
            else
            {
                google.charts.setOnLoadCallback(
                    function () {
                        fnStatsDibujar('grafico2', 'PieChart', objDataSource2, {
                            isStacked: 'percent',
                            colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
                            chartArea: { width: '85%', height: '70%' }
                        });
                    }
                );
            }


            //Clientes por Stilo de propiedad
            ///
            ////////////////////////////////////////////////
            var objDataSource3 = fnStatsTabla(RealState_get_ClientesTipoPropiedad, ["Tipo","Cantidad"]);
            if (RealState_get_ClientesTipoPropiedad.length == 0)
            {
                fnStatsMostrarVacio('grafico3');
            }
            else
            {
                google.charts.setOnLoadCallback(
                    function () {
                        fnStatsDibujar('grafico3', 'PieChart', objDataSource3, {
                            colors: ['#3399FF', '#9966FF', '#FF33CC', '#FF6633', '#FFFF33'],
                            chartArea: { width: '85%', height: '70%' }
                        });
                    }
                );
            }


            //Clientes por Agente
            ///
            ////////////////////////////////////////////////
            var objDataSource4 = fnStatsTabla(RealState_get_ClientesPorAgentes, ["Agente","Cantidad"]);
            if (RealState_get_ClientesPorAgentes.length == 0)
            {
                fnStatsMostrarVacio('grafico4');
            }
            else
            {
                google.charts.setOnLoadCallback(
                    function () {
                        fnStatsDibujar('grafico4', 'LineChart', objDataSource4, {
                            colors: ['#FF33FF', '#66FF33', '#33FFFF', '#CC33FF', '#FFCC33'],
                            pieHole: 0.4,
                            chartArea: { width: '85%', height: '70%' }
                        });
                    }
                );
            }


            //Clasificacion de clientes por agente (pivote Agente x Indicador)
            ///
            ////////////////////////////////////////////////
            var objPivote5     = fnStatsPivote(RealState_get_ClientesClasificacionPorAgentes);
            var objDataSource5 = objPivote5.tabla;
            if (RealState_get_ClientesClasificacionPorAgentes.length == 0 || objPivote5.indicadores.length == 0)
            {
                fnStatsMostrarVacio('grafico5');
            }
            else
            {
                google.charts.setOnLoadCallback(
                    function () {
                        fnStatsDibujar('grafico5', 'ComboChart', objDataSource5, {
                            colors: ['#FF5733', '#FFC300', '#FF85A2', '#FF33FF', '#33FFBD'],
                            vAxis: { title: 'Cantidad', minValue: 0 },
                            hAxis: { title: 'Agente', slantedText: true, slantedTextAngle: 30 },
                            seriesType: 'bars',
                            series: { 5: { type: 'line' } },
                            legend: {
                                position: 'bottom',
                                alignment: 'center',
                                textStyle: { fontSize: 11 },
                                pagingTextStyle: { fontSize: 11 },
                                scrollArrows: { activeColor: '#333', inactiveColor: '#ccc' }
                            },
                            //Menos alto que el resto: deja espacio a etiquetas inclinadas y leyenda inferior
                            chartArea: { width: '85%', height: '60%', top: 20 },
                            fontSize: 11
                        });
                    }
                );
            }


            //Cierre de clientes
            ///
            ////////////////////////////////////////////////
            var objDataSource7 = fnStatsTabla(RealState_get_ClientesCerrados, ["Clasificacion","Cantidad"]);
            if (RealState_get_ClientesCerrados.length == 0)
            {
                fnStatsMostrarVacio('grafico7');
            }
            else
            {
                google.charts.setOnLoadCallback(
                    function () {
                        fnStatsDibujar('grafico7', 'PieChart', objDataSource7, {
                            colors: ['#33A1FF', '#FF3366', '#FF3333', '#33FF33', '#33FFA8'],
                            pieHole: 0.4,
                            chartArea: { width: '85%', height: '70%' }
                        });
                    }
                );
            }


            //Redibujar al cambiar el tamano de la ventana (debounce 250 ms)
            $(window).on('resize', function () {
                clearTimeout(pv2StatsTimeResize);
                pv2StatsTimeResize = setTimeout(fnStatsRedibujar, 250);
            });


            //Filtro: valida el rango y recarga la pantalla con las fechas
            $(document).on("click","#btnSalvarFiltro",function(){
                var txtDateStart		=	$.trim($("#txtDateStart").val());
                var txtDateFinish		=	$.trim($("#txtDateFinish").val());
                var mensaje             =   fnStatsValidarRango(txtDateStart, txtDateFinish);
                if (mensaje != "")
                {
                    fnShowNotification(mensaje, "error");
                    return;
                }
                fnWaitOpen();
                window.location	= "<?php echo base_url(); ?>/app_stadistic_dashboards/customer_realstate/txtDateStart/"+txtDateStart+"/txtDateFinish/"+txtDateFinish;

            });

            //Fechas: calendario del tema en PC y selector nativo en telefono (YYYY-MM-DD)
            pv2InitDate('txtDateStart');
            pv2InitDate('txtDateFinish');

        }
    );

</script>
