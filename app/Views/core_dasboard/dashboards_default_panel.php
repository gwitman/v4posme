<?php
/*
 * Panel de inventario y facturación (core_dashboards/index, compañías sin dashboard personalizado).
 * Datos: core_dashboards::getIndexPanel() -> Dashboard_Panel_Model (vistas ai_v_*).
 * Gráficos: Chart.js 4.4.1 local (public/resource/js/chartjs/chart.umd.min.js).
 * Las acciones rápidas y enlaces solo se muestran si el usuario tiene permiso en su menú.
 */
$p       = $panel;
$sim     = htmlspecialchars((string)$p["simbolo"], ENT_QUOTES, 'UTF-8');
$e       = function($texto) { return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8'); };
$money   = function($monto) use ($sim) { return $sim . " " . number_format((float)$monto, 2); };
$chipVar = function($valor) {
    if($valor === null) return '<span class="pmx-chip">Sin mes anterior</span>';
    $flecha = $valor >= 0 ? "▲" : "▼";
    return '<span class="pmx-chip">' . $flecha . ' ' . number_format(abs($valor), 1) . '%</span>';
};
$hora    = (int)date("G");
$saludo  = $hora < 12 ? "Buenos días" : ($hora < 19 ? "Buenas tardes" : "Buenas noches");
$mesesLargos = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
$objHoy  = \DateTime::createFromFormat("Y-m-d", $p["hoy"]);
$fechaTexto = $objHoy->format("j") . " de " . $mesesLargos[(int)$objHoy->format("n") - 1] . " de " . $objHoy->format("Y");
$urlBase = base_url();

//Íconos de las acciones rápidas (svg inline, sin dependencias)
$iconos = array(
    "facturar" => '<path d="M6 2h9l5 5v15H6z"/><path d="M12 11v6M9 14h6"/>',
    "producto" => '<path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="M12 9v6M9 12h6"/>',
    "compra"   => '<path d="M3 3h2l2.5 12h11L21 7H6"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/>',
    "abono"    => '<rect x="2" y="6" width="20" height="13" rx="2"/><path d="M2 10h20M6 15h4"/>',
    "cliente"  => '<circle cx="9" cy="8" r="4"/><path d="M2 21c0-4 3-6 7-6s7 2 7 6M19 8v6M16 11h6"/>',
    "reporte"  => '<path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 6-6"/>',
);
$colores = array("facturar" => "q1", "producto" => "q2", "compra" => "q3", "abono" => "q4", "cliente" => "q5", "reporte" => "q6");

//Acción "Facturar" en el encabezado si tiene permiso
$accionFacturar = null;
foreach($p["acciones"] as $a) { if($a["clave"] == "facturar") $accionFacturar = $a; }
?>
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-dashboard-panel.css?v=1" rel="stylesheet" />

<div class="pmx" id="pmxPanel">

    <header class="pmx-top">
        <div class="pmx-hello">
            <h1><?php echo $saludo; ?>, <?php echo $e($user->nickname); ?></h1>
            <p><?php echo $e($company->name); ?> · Resumen al <?php echo $fechaTexto; ?></p>
        </div>
        <div class="pmx-top-actions">
            <div class="pmx-rate" title="Tipo de cambio del día">
                <span>Tipo de cambio</span>
                <b class="pmx-num"><?php echo number_format($objExchangeRateDolarACordoba, 4); ?></b>
            </div>
            <?php if($accionFacturar != null): ?>
            <a class="pmx-btn pmx-btn-primary" href="<?php echo $e($accionFacturar["url"]); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>Nueva factura
            </a>
            <?php endif; ?>
        </div>
    </header>

    <section class="pmx-kpis" aria-label="Indicadores principales">
        <article class="pmx-kpi k1">
            <div class="pmx-kpi-head"><span>Ventas del mes</span><div class="pmx-kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
            <div class="pmx-kpi-val"><?php echo $money($p["ventasMes"]); ?></div>
            <div class="pmx-kpi-foot"><?php echo $chipVar($p["ventasVariacion"]); ?><canvas id="pmxSp1" width="96" height="32"></canvas></div>
        </article>
        <article class="pmx-kpi k2">
            <div class="pmx-kpi-head"><span>Facturas emitidas</span><div class="pmx-kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2h9l5 5v15H6z"/><path d="M14 2v6h6M9 13h7M9 17h5"/></svg></div></div>
            <div class="pmx-kpi-val"><?php echo number_format($p["facturasMes"]); ?></div>
            <div class="pmx-kpi-foot"><?php echo $chipVar($p["facturasVariacion"]); ?><canvas id="pmxSp2" width="96" height="32"></canvas></div>
        </article>
        <article class="pmx-kpi k3">
            <div class="pmx-kpi-head"><span>Valor del inventario</span><div class="pmx-kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg></div></div>
            <div class="pmx-kpi-val"><?php echo $money($p["inventarioValor"]); ?></div>
            <div class="pmx-kpi-foot"><span class="pmx-chip"><?php echo number_format($p["inventarioUnidades"], 2); ?> unidades</span><canvas id="pmxSp3" width="96" height="32"></canvas></div>
        </article>
        <article class="pmx-kpi k4">
            <div class="pmx-kpi-head"><span>Por cobrar</span><div class="pmx-kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div></div>
            <div class="pmx-kpi-val"><?php echo $money($p["porCobrar"]); ?></div>
            <div class="pmx-kpi-foot"><span class="pmx-chip"><?php echo number_format($p["documentosCxc"]); ?> créditos vigentes</span><canvas id="pmxSp4" width="96" height="32"></canvas></div>
        </article>
    </section>

    <section class="pmx-grid">
        <article class="pmx-card c8">
            <div class="pmx-card-head">
                <div><h2>Ventas vs. compras</h2><p>Comparativo mensual (facturas y compras aplicadas)</p></div>
                <div class="pmx-range" role="group" aria-label="Periodo">
                    <button type="button" data-r="6" aria-pressed="false">6 meses</button>
                    <button type="button" data-r="12" aria-pressed="true">12 meses</button>
                </div>
            </div>
            <div class="pmx-big-stats">
                <div><span>Total ventas</span><b class="pmx-num" id="pmxTotVentas">—</b></div>
                <div><span>Total compras</span><b class="pmx-num" id="pmxTotCompras">—</b></div>
                <div><span>Ventas − compras</span><b class="pmx-num" id="pmxTotDif">—</b></div>
            </div>
            <div class="pmx-legend"><span><i class="lg-brand"></i>Ventas</span><span><i class="lg-teal"></i>Compras</span></div>
            <div class="pmx-chart-box"><canvas id="pmxMain" aria-label="Gráfico de ventas y compras"></canvas></div>
        </article>

        <article class="pmx-card c4">
            <div class="pmx-card-head"><div><h2>Ventas por categoría</h2><p><?php echo $e($p["mesNombre"]); ?></p></div></div>
            <?php if(count($p["categorias"]) == 0): ?>
                <div class="pmx-empty">Sin ventas aplicadas este mes.</div>
            <?php else: ?>
                <div class="pmx-donut-wrap"><canvas id="pmxDonut" aria-label="Ventas por categoría"></canvas>
                    <div class="pmx-donut-center"><div><b class="pmx-num" id="pmxDonutTotal"></b><span>este mes</span></div></div></div>
                <ul class="pmx-cat-list" id="pmxCatList"></ul>
            <?php endif; ?>
        </article>

        <article class="pmx-card c7">
            <div class="pmx-card-head">
                <div><h2>Productos más vendidos</h2><p>Unidades vendidas en los últimos 30 días</p></div>
                <?php if($p["permisos"]["listaProductos"]): ?>
                    <a class="pmx-link" href="<?php echo $urlBase; ?>/app_inventory_item/index">Ver productos →</a>
                <?php endif; ?>
            </div>
            <?php if(count($p["topProductos"]) == 0): ?>
                <div class="pmx-empty">Sin ventas en los últimos 30 días.</div>
            <?php else: ?>
                <div class="pmx-chart-box sm"><canvas id="pmxTop" aria-label="Productos más vendidos"></canvas></div>
            <?php endif; ?>
        </article>

        <article class="pmx-card c5">
            <div class="pmx-card-head">
                <div><h2>Alertas de stock</h2><p>Productos en o cerca del mínimo</p></div>
                <?php if($p["stockCriticos"] > 0): ?>
                    <span class="pmx-pill p-bad"><?php echo number_format($p["stockCriticos"]); ?> críticos</span>
                <?php elseif($p["stockBajoMinimo"] > 0): ?>
                    <span class="pmx-pill p-warn"><?php echo number_format($p["stockBajoMinimo"]); ?> bajo el mínimo</span>
                <?php endif; ?>
            </div>
            <?php if(count($p["stock"]) == 0): ?>
                <div class="pmx-empty">Ningún producto está bajo el mínimo.</div>
            <?php else: ?>
            <ul class="pmx-stock">
                <?php foreach($p["stock"] as $i => $s):
                    $existencia = (float)$s->existencia;
                    $minimo     = (float)$s->minimo;
                    $pct        = $minimo > 0 ? max(0, min(100, round($existencia / $minimo * 100))) : 0;
                    //Regla: crítico <= 50% del mínimo, bajo <= mínimo, si no "reponer pronto"
                    if($existencia <= $minimo * 0.5)  { $cls = "bad";  $txt = "Crítico"; }
                    elseif($existencia <= $minimo)    { $cls = "warn"; $txt = "Bajo"; }
                    else                              { $cls = "ok";   $txt = "Reponer pronto"; }
                    $palabras = preg_split('/\s+/', trim((string)$s->nombre));
                    $ini      = mb_strtoupper(mb_substr($palabras[0] ?? "", 0, 1) . mb_substr($palabras[1] ?? "", 0, 1));
                ?>
                <li>
                    <div class="pmx-thumb t<?php echo $i % 5; ?>"><?php echo $e($ini); ?></div>
                    <div class="pmx-stock-info">
                        <div class="pmx-name"><?php echo $e($s->nombre); ?></div>
                        <div class="pmx-sku"><span class="pmx-num"><?php echo $e($s->codigo); ?></span> · <span class="pmx-num"><?php echo number_format($existencia, 2); ?></span> de <span class="pmx-num"><?php echo number_format($minimo, 2); ?></span> mín.</div>
                        <div class="pmx-bar"><span class="b-<?php echo $cls; ?>" style="width:<?php echo $pct; ?>%"></span></div>
                    </div>
                    <span class="pmx-pill p-<?php echo $cls; ?>"><?php echo $txt; ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </article>

        <article class="pmx-card <?php echo count($p["acciones"]) > 0 ? "c8" : "c12"; ?>">
            <div class="pmx-card-head">
                <div><h2>Facturas recientes</h2><p>Últimos movimientos de facturación</p></div>
                <?php if($p["permisos"]["historialFacturas"]): ?>
                    <a class="pmx-link" href="<?php echo $urlBase; ?>/app_invoice_billing/index">Ver historial →</a>
                <?php endif; ?>
            </div>
            <?php if(count($p["facturasRecientes"]) == 0): ?>
                <div class="pmx-empty">Aún no hay facturas registradas.</div>
            <?php else: ?>
            <div class="pmx-table-wrap">
                <table class="pmx-table">
                    <thead><tr><th>Factura</th><th>Cliente</th><th>Fecha</th><th>Tipo</th><th class="right">Total</th><th>Estado</th></tr></thead>
                    <tbody>
                    <?php foreach($p["facturasRecientes"] as $i => $f):
                        $cliente  = trim((string)$f->cliente_nombre) != "" ? $f->cliente_nombre : "Cliente de contado";
                        $palabras = preg_split('/\s+/', trim($cliente));
                        $ini      = mb_strtoupper(mb_substr($palabras[0] ?? "", 0, 1) . mb_substr($palabras[1] ?? "", 0, 1));
                        $estado   = (string)$f->estado;
                        //Aplicada = válida; anulada en rojo; cualquier otro estado (registrada, etc.) en amarillo
                        if((int)$f->es_valida == 1)                       $cls = "ok";
                        elseif(stripos($estado, "anul") !== false)        $cls = "bad";
                        else                                               $cls = "warn";
                        $fecha    = $f->fecha ? date("d/m/Y", strtotime($f->fecha)) : "";
                    ?>
                        <tr>
                            <td class="pmx-num fw"><?php echo $e($f->numero); ?></td>
                            <td><div class="pmx-client"><span class="pmx-ini t<?php echo $i % 5; ?>"><?php echo $e($ini); ?></span><span class="pmx-client-name"><?php echo $e($cliente); ?></span></div></td>
                            <td><?php echo $fecha; ?></td>
                            <td><?php echo $e($f->tipo_pago); ?></td>
                            <td class="right pmx-num fw"><?php echo $e($f->moneda); ?> <?php echo number_format((float)$f->total, 2); ?></td>
                            <td><span class="pmx-pill p-<?php echo $cls; ?>"><?php echo $e($estado); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </article>

        <?php if(count($p["acciones"]) > 0): ?>
        <article class="pmx-card c4">
            <div class="pmx-card-head"><div><h2>Acciones rápidas</h2><p>Según los permisos de tu usuario</p></div></div>
            <div class="pmx-quick">
                <?php foreach($p["acciones"] as $a): ?>
                <a class="pmx-qa" href="<?php echo $e($a["url"]); ?>">
                    <span class="pmx-qi <?php echo $colores[$a["clave"]]; ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?php echo $iconos[$a["clave"]]; ?></svg></span>
                    <b><?php echo $e($a["titulo"]); ?></b><span><?php echo $e($a["detalle"]); ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </article>
        <?php endif; ?>

        <article class="pmx-card c12 pmx-support <?php echo getBehavio($company->type,"core_dashboards","divPanelSoporteTenico","hidden"); ?>">
            <div class="pmx-card-head">
                <div><h2>Información de contacto</h2><p>Soporte técnico posMe: 8712-5827</p></div>
                <a aria-label="Chat on WhatsApp" target="_blank" rel="noopener" href="https://wa.me/50587125827?text=Buenos dias le saluda <?php echo $e($user->email); ?> : ">
                    <img alt="Chat on WhatsApp" src="<?php echo $urlBase; ?>/resource/img/logos/WhatsAppButtonGreenSmall.svg" />
                </a>
            </div>
        </article>
    </section>
</div>

<script>
    //Datos del panel generados en el servidor (core_dashboards::getIndexPanel)
    window.pmxPanelData = <?php echo json_encode(array(
        "simbolo"      => $p["simbolo"],
        "meses"        => $p["meses"],
        "ventas"       => $p["ventas"],
        "compras"      => $p["compras"],
        "facturas"     => $p["facturas"],
        "creditos"     => $p["creditos"],
        "categorias"   => $p["categorias"],
        "topProductos" => $p["topProductos"],
    ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/chartjs/chart.umd.min.js"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-dashboard-panel.js?v=1"></script>
