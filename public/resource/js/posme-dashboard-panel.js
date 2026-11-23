/*
 * Gráficos del panel de inventario y facturación (core_dashboards/index).
 * Lee window.pmxPanelData (generado en el servidor) y dibuja con Chart.js 4 (copia local).
 */
(function () {
    var datos = window.pmxPanelData;
    var raiz  = document.getElementById('pmxPanel');
    if (!datos || !raiz) return;

    var $ = function (id) { return document.getElementById(id); };

    // Formato visual: miles con coma y dos decimales (no altera los valores usados en los cálculos)
    var fmt = function (n) {
        return Number(n || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };
    // Formato corto para ejes y centro de la dona (12.5k, 1.2M)
    var corto = function (n) {
        n = Number(n || 0);
        var a = Math.abs(n);
        if (a >= 1e6) return (n / 1e6).toFixed(1) + 'M';
        if (a >= 1e3) return (n / 1e3).toFixed(1) + 'k';
        return n.toFixed(0);
    };
    var sim = datos.simbolo ? datos.simbolo + ' ' : '';
    var esc = function (t) {
        return String(t).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    };

    // Lista de categorías (se arma aunque Chart.js no cargue)
    var totalCat = datos.categorias.reduce(function (a, c) { return a + Number(c.total); }, 0);
    var catColores = ['#5b5cf0', '#10b8a6', '#f5a524', '#ff6b5b', '#2f9bff'];
    if ($('pmxCatList')) {
        $('pmxCatList').innerHTML = datos.categorias.map(function (c, i) {
            var pct = totalCat > 0 ? (Number(c.total) / totalCat * 100) : 0;
            return '<li><i style="background:' + catColores[i % catColores.length] + '"></i>' +
                '<span class="nm" title="' + esc(c.nombre) + '">' + esc(c.nombre) + '</span>' +
                '<b class="pmx-num">' + sim + fmt(c.total) + '</b>' +
                '<span class="pct pmx-num">' + pct.toFixed(1) + '%</span></li>';
        }).join('');
        $('pmxDonutTotal').textContent = sim + corto(totalCat);
    }

    // Totales del periodo seleccionado (6 o 12 meses)
    var rango = 12;
    function actualizarTotales() {
        var desde = datos.meses.length - rango;
        var tv = datos.ventas.slice(desde).reduce(function (a, x) { return a + Number(x); }, 0);
        var tc = datos.compras.slice(desde).reduce(function (a, x) { return a + Number(x); }, 0);
        $('pmxTotVentas').textContent  = sim + fmt(tv);
        $('pmxTotCompras').textContent = sim + fmt(tc);
        $('pmxTotDif').textContent     = sim + fmt(tv - tc);
        $('pmxTotDif').style.color     = (tv - tc) >= 0 ? 'var(--pmx-ok)' : 'var(--pmx-bad)';
    }
    actualizarTotales();

    if (!window.Chart) return;

    var css = getComputedStyle(raiz);
    var C = {
        brand: '#5b5cf0', teal: '#10b8a6', amber: '#f5a524', coral: '#ff6b5b', sky: '#2f9bff', pink: '#e5489c',
        muted: css.getPropertyValue('--pmx-muted').trim() || '#69708a',
        line:  css.getPropertyValue('--pmx-line').trim()  || '#e4e7f1',
        fg:    css.getPropertyValue('--pmx-fg').trim()    || '#141a2e',
        surface: '#ffffff'
    };
    Chart.defaults.font.family = '"Plus Jakarta Sans","Segoe UI",system-ui,sans-serif';
    Chart.defaults.color = C.muted;
    var tip = { backgroundColor: C.surface, titleColor: C.fg, bodyColor: C.fg, borderColor: C.line, borderWidth: 1, padding: 10, cornerRadius: 10, boxPadding: 4, usePointStyle: true };

    // Degradado vertical bajo la línea
    function degradado(ctx, area, color) {
        var g = ctx.createLinearGradient(0, area.top, 0, area.bottom);
        g.addColorStop(0, color + '55');
        g.addColorStop(1, color + '00');
        return g;
    }

    // Mini gráficos de las tarjetas KPI (últimos 8 meses)
    var series = [datos.ventas, datos.facturas, datos.compras, datos.creditos];
    series.forEach(function (d, i) {
        var canvas = $('pmxSp' + (i + 1));
        if (!canvas) return;
        var s = d.slice(-8);
        new Chart(canvas, {
            type: 'line',
            data: { labels: s.map(function (_, j) { return j; }), datasets: [{ data: s, borderColor: '#ffffff', borderWidth: 2, pointRadius: 0, tension: .4, fill: false }] },
            options: { responsive: false, animation: false, plugins: { legend: { display: false }, tooltip: { enabled: false } }, scales: { x: { display: false }, y: { display: false } } }
        });
    });

    // Ventas vs compras con selector de periodo
    var grafPrincipal = null;
    function dibujarPrincipal() {
        if (grafPrincipal) grafPrincipal.destroy();
        var desde = datos.meses.length - rango;
        var lb = datos.meses.slice(desde), vs = datos.ventas.slice(desde), cs = datos.compras.slice(desde);
        var ultimo = vs.length - 1;
        var serie = function (label, data, color) {
            return {
                label: label, data: data, borderColor: color, borderWidth: 3, tension: .4, fill: true,
                backgroundColor: function (c) { return c.chart.chartArea ? degradado(c.chart.ctx, c.chart.chartArea, color) : 'transparent'; },
                pointRadius: data.map(function (_, i) { return i === ultimo ? 6 : 0; }),
                pointBackgroundColor: color, pointBorderColor: C.surface, pointBorderWidth: 3, pointHoverRadius: 6
            };
        };
        grafPrincipal = new Chart($('pmxMain'), {
            type: 'line',
            data: { labels: lb, datasets: [serie('Ventas', vs, C.brand), serie('Compras', cs, C.teal)] },
            options: {
                responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false }, tooltip: Object.assign({}, tip, { callbacks: { label: function (c) { return ' ' + c.dataset.label + ': ' + sim + fmt(c.parsed.y); } } }) },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: { beginAtZero: true, ticks: { maxTicksLimit: 5, callback: function (x) { return sim + corto(x); } }, grid: { color: C.line }, border: { display: false } }
                }
            }
        });
    }
    raiz.querySelectorAll('.pmx-range button').forEach(function (b) {
        b.addEventListener('click', function () {
            raiz.querySelectorAll('.pmx-range button').forEach(function (x) { x.setAttribute('aria-pressed', 'false'); });
            b.setAttribute('aria-pressed', 'true');
            rango = Math.min(+b.dataset.r, datos.meses.length);
            actualizarTotales();
            dibujarPrincipal();
        });
    });
    dibujarPrincipal();

    // Dona de ventas por categoría
    if ($('pmxDonut') && datos.categorias.length > 0) {
        new Chart($('pmxDonut'), {
            type: 'doughnut',
            data: {
                labels: datos.categorias.map(function (c) { return c.nombre; }),
                datasets: [{ data: datos.categorias.map(function (c) { return c.total; }), backgroundColor: catColores, borderColor: C.surface, borderWidth: 4, borderRadius: 8, spacing: 2, hoverOffset: 6 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '72%',
                plugins: { legend: { display: false }, tooltip: Object.assign({}, tip, { callbacks: { label: function (c) { return ' ' + c.label + ': ' + sim + fmt(c.parsed); } } }) }
            }
        });
    }

    // Barras horizontales de productos más vendidos
    if ($('pmxTop') && datos.topProductos.length > 0) {
        var recortar = function (t) { t = String(t); return t.length > 28 ? t.substring(0, 27) + '…' : t; };
        new Chart($('pmxTop'), {
            type: 'bar',
            data: {
                labels: datos.topProductos.map(function (t) { return recortar(t.nombre); }),
                datasets: [{ data: datos.topProductos.map(function (t) { return t.cantidad; }), backgroundColor: [C.brand, C.sky, C.teal, C.amber, C.coral, C.pink], borderRadius: 8, borderSkipped: false, barThickness: 16 }]
            },
            options: {
                indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: Object.assign({}, tip, { callbacks: {
                    title: function (c) { return datos.topProductos[c[0].dataIndex].nombre; },
                    label: function (c) { return ' ' + fmt(c.parsed.x) + ' unidades'; }
                } }) },
                scales: {
                    x: { beginAtZero: true, ticks: { maxTicksLimit: 5 }, grid: { color: C.line }, border: { display: false } },
                    y: { grid: { display: false }, border: { display: false }, ticks: { color: C.fg, font: { weight: '600' } } }
                }
            }
        });
    }
})();
