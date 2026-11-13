<?php echo view('app_box_share/share_v2_style'); ?>

<style>
    /* ==========================================================
       core_upgrade/byPop3 - actualización desde el servidor posMe
       Base visual: ABONOS (.abv2). Aquí solo lo propio (.upg),
       tomado de la consola de despliegue (core_merge/deploy, .dpl).
       Sin CDN: durante la actualización los archivos del servidor
       se reemplazan, por eso todo vive en esta página.
       ========================================================== */
    .upg-step-num { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; background:var(--abv2-accent); color:#fff; font-size:12px; font-weight:700; margin-right:4px; flex:0 0 22px; }
    .upg-hint { font-size:12px; color:var(--abv2-muted); margin:4px 0 0; }
    .upg-text { font-size:13px; margin:0 0 8px; }
    .upg-text:last-child { margin-bottom:0; }
    .upg-header-url { word-break:break-all; }
    .upg-chips { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
    .upg-chip { font-size:12px; border-radius:20px; padding:3px 10px; background:var(--abv2-soft); color:var(--abv2-muted); word-break:break-all; }
    .upg-chip b { color:#1f2933; }
    .upg-chip.is-ok { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
    .upg-chip.is-bad { background:var(--abv2-bad-bg); color:var(--abv2-bad); }
    .upg-chip.is-warn { background:var(--abv2-warn-bg); color:var(--abv2-warn); }
    .upg-btns { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
    .upg-btns .btn { border-radius:6px; }
    .upg-alert { border-radius:8px; padding:10px 12px; font-size:13px; margin:10px 0 0; word-break:break-word; }
    .upg-alert.is-ok { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
    .upg-alert.is-bad { background:var(--abv2-bad-bg); color:var(--abv2-bad); }
    .upg-alert.is-warn { background:var(--abv2-warn-bg); color:var(--abv2-warn); }
    .upg-alert.is-info { background:var(--abv2-accent-bg); color:var(--abv2-accent); }
    .upg-list { margin:0; padding-left:20px; font-size:13px; }
    .upg-list li { margin-bottom:4px; }
    .upg-checks { list-style:none; padding:0; margin:0; }
    .upg-checks li { display:flex; gap:8px; align-items:flex-start; padding:6px 0; border-bottom:1px solid var(--abv2-border); font-size:13px; word-break:break-word; }
    .upg-checks li:last-child { border-bottom:0; }
    .upg-checks .upg-ico { flex:0 0 18px; font-weight:700; }
    .upg-checks .is-ok .upg-ico { color:var(--abv2-ok); }
    .upg-checks .is-bad .upg-ico { color:var(--abv2-bad); }
    .upg-checks .is-warn .upg-ico { color:var(--abv2-warn); }
    .upg-checks .upg-group { font-size:11px; color:var(--abv2-muted); margin-left:auto; white-space:nowrap; }
    .upg-checks .upg-check-detail { display:block; font-size:12px; color:var(--abv2-muted); }
    .upg-option { display:flex; gap:10px; align-items:flex-start; border:1px solid var(--abv2-border); border-radius:10px; padding:10px 12px; margin-bottom:10px; cursor:pointer; }
    .upg-option.is-on { border-color:var(--abv2-accent); background:var(--abv2-accent-bg); }
    .upg-option input[type=checkbox] { width:20px; height:20px; margin:2px 0 0; flex:0 0 20px; }
    .upg-option-title { font-weight:700; font-size:14px; margin:0; }
    .upg-option-desc { font-size:12px; color:var(--abv2-muted); margin:2px 0 0; }
    .upg-option.is-danger .upg-option-title { color:#c0392b; }
    .upg-option.is-danger.is-on { border-color:#e74c3c; background:#fdecea; }
    .upg-confirm { font-size:16px !important; font-weight:700; letter-spacing:1px; }
    .upg-scroll { max-height:340px; overflow:auto; border:1px solid var(--abv2-border); border-radius:8px; }
    .upg-table { width:100%; border-collapse:collapse; }
    .upg-table th { position:sticky; top:0; background:#fff; font-size:12px; font-weight:600; color:var(--abv2-muted); padding:7px 8px; border-bottom:1px solid var(--abv2-border); text-align:left; z-index:1; }
    .upg-table td { padding:7px 8px; border-bottom:1px solid var(--abv2-border); font-size:13px; vertical-align:top; word-break:break-word; }
    .upg-table td.upg-n, .upg-table th.upg-n { text-align:right; font-variant-numeric:tabular-nums; white-space:nowrap; }
    .upg-table tr.is-missing td { background:var(--abv2-soft); color:var(--abv2-muted); }
    .upg-checks li.is-skip { color:var(--abv2-muted); }
    .upg-table input[type=checkbox] { width:20px; height:20px; margin:0; }
    .upg-badge { display:inline-block; font-size:11px; font-weight:700; border-radius:20px; padding:1px 8px; background:var(--abv2-soft); color:var(--abv2-muted); }
    .upg-badge.is-risk { background:var(--abv2-bad-bg); color:var(--abv2-bad); }
    .upg-badge.is-new { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
    .upg-badge.is-warn { background:var(--abv2-warn-bg); color:var(--abv2-warn); }
    .upg-sql { display:none; margin-top:6px; background:#0f172a; color:#e2e8f0; border-radius:6px; padding:8px; font:12px/1.4 Consolas, monospace; white-space:pre-wrap; word-break:break-all; max-height:220px; overflow:auto; }
    .upg-sql.is-open { display:block; }
    .upg-link { font-size:12px; cursor:pointer; color:var(--abv2-accent); }
    details > summary.upg-link { padding:6px 0; }
    .upg-stats { flex-wrap:wrap; margin-top:8px; }
    .upg-stats .abv2-stat { flex:1 1 120px; }
    .upg-stats .abv2-stat b { display:block; font-size:15px; word-break:break-word; }
    /* etapas de ejecución */
    .upg-stages { list-style:none; padding:0; margin:0; }
    .upg-stages > li { padding:9px 0; font-size:13px; border-bottom:1px solid var(--abv2-border); }
    .upg-stages > li:last-child { border-bottom:0; }
    .upg-stage-head { display:flex; align-items:flex-start; gap:8px; }
    .upg-stage-ico { width:24px; height:24px; border-radius:50%; flex:0 0 24px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; background:var(--abv2-soft); color:var(--abv2-muted); }
    .upg-stage-txt { flex:1 1 auto; min-width:0; }
    .upg-stage-title { display:block; font-weight:700; }
    .upg-stage-desc { display:block; font-size:12px; color:var(--abv2-muted); }
    .upg-stage-state { flex:0 0 auto; font-size:11px; font-weight:700; border-radius:20px; padding:1px 8px; background:var(--abv2-soft); color:var(--abv2-muted); white-space:nowrap; }
    .upg-stage-msg { font-size:12px; color:var(--abv2-muted); margin:4px 0 0 32px; word-break:break-word; }
    .upg-stage-msg:empty { display:none; }
    .upg-stage-det { margin:2px 0 0 32px; }
    .upg-stages > li.is-run .upg-stage-ico { background:var(--abv2-accent); color:#fff; animation:upgPulse 1s infinite alternate; }
    .upg-stages > li.is-run .upg-stage-state { background:var(--abv2-accent-bg); color:var(--abv2-accent); }
    .upg-stages > li.is-wait .upg-stage-ico { background:var(--abv2-warn); color:#fff; animation:upgPulse 1s infinite alternate; }
    .upg-stages > li.is-wait .upg-stage-state { background:var(--abv2-warn-bg); color:var(--abv2-warn); }
    .upg-stages > li.is-ok .upg-stage-ico { background:var(--abv2-ok); color:#fff; }
    .upg-stages > li.is-ok .upg-stage-state { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
    .upg-stages > li.is-bad .upg-stage-ico { background:var(--abv2-bad); color:#fff; }
    .upg-stages > li.is-bad .upg-stage-state { background:var(--abv2-bad-bg); color:var(--abv2-bad); }
    .upg-stages > li.is-skip .upg-stage-title { color:var(--abv2-muted); text-decoration:line-through; }
    @keyframes upgPulse { from { opacity:.55; } to { opacity:1; } }
    .upg-progress { height:10px; border-radius:10px; background:var(--abv2-soft); overflow:hidden; margin:10px 0 4px; }
    .upg-progress div { height:100%; width:0; background:var(--abv2-accent); transition:width .3s; }
    .upg-progress.is-ok div { background:var(--abv2-ok); }
    .upg-progress.is-bad div { background:var(--abv2-bad); }
    .upg-progress.is-mini { height:6px; margin:6px 0 0 32px; }
    .upg-progress-text { font-size:13px; font-weight:600; margin:0; word-break:break-word; }
    .upg-log { background:#0f172a; color:#cbd5e1; border-radius:8px; padding:10px; font:12px/1.45 Consolas, monospace; height:240px; overflow:auto; white-space:pre-wrap; word-break:break-word; margin:10px 0 0; }
    .upg-summary { list-style:none; padding:0; margin:0; }
    .upg-summary li { display:flex; gap:8px; padding:6px 0; font-size:13px; border-bottom:1px solid var(--abv2-border); word-break:break-word; }
    .upg-summary li:last-child { border-bottom:0; }
    .upg-summary .is-off { color:var(--abv2-warn); }
    .upg-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:2000; align-items:center; justify-content:center; padding:16px; }
    .upg-overlay.is-open { display:flex; }
    .upg-dialog { background:#fff; border-radius:12px; max-width:560px; width:100%; padding:18px; max-height:90vh; overflow:auto; }
    .upg-dialog h4 { margin:0 0 10px; font-weight:700; }
    .upg-btn-main { min-height:44px; }
    /* pestañas (Actualización | Respaldo de base de datos) */
    .upg-tabs { display:flex; gap:6px; flex-wrap:wrap; border-bottom:1px solid var(--abv2-border); margin:0 0 14px; }
    .upg-tab { border:1px solid transparent; border-bottom:0; background:transparent; color:var(--abv2-muted); font-weight:600; font-size:13px; padding:10px 14px; border-radius:8px 8px 0 0; min-height:44px; margin-bottom:-1px; cursor:pointer; }
    .upg-tab:hover { color:var(--abv2-accent); }
    .upg-tab.is-active { background:#fff; color:var(--abv2-accent); border-color:var(--abv2-border); }
    .upg-tab:disabled { opacity:.5; cursor:not-allowed; }
    .upg-table .bkp-actions { white-space:nowrap; text-align:right; }
    .upg-table .bkp-actions .btn { min-height:36px; margin:2px 0; }
    .upg-table .bkp-note { display:block; font-size:12px; color:var(--abv2-muted); }
    @media (max-width:767px) {
        .upg-tab { flex:1 1 0; padding:10px 6px; }
        .upg-table .bkp-actions { white-space:normal; flex-wrap:wrap; justify-content:flex-end; }
        .upg-table .bkp-actions .btn { min-height:44px; }
        .upg-option input[type=checkbox], .upg-table input[type=checkbox] { width:24px; height:24px; }
        .upg-btns .btn { flex:1 1 100%; min-height:44px; }
        .upg-checks .upg-group { display:none; }
        .upg-stage-msg, .upg-stage-det, .upg-progress.is-mini { margin-left:0; }
        .upg-scroll { max-height:none; }
        /* tablas apiladas: cada fila es un bloque y cada celda muestra su etiqueta */
        .upg-table.is-stack thead { display:none; }
        .upg-table.is-stack tr { display:block; border-bottom:1px solid var(--abv2-border); padding:4px 0; }
        .upg-table.is-stack td { display:flex; justify-content:space-between; gap:10px; border-bottom:0; padding:4px 8px; text-align:right; }
        .upg-table.is-stack td:before { content:attr(data-label); font-size:12px; font-weight:600; color:var(--abv2-muted); text-align:left; flex:0 0 auto; }
        .upg-table.is-stack td.upg-n { white-space:normal; }
        .upg-dialog { padding:14px; }
    }
</style>

<div id="heading" class="page-header">
    <h1><i class="icon20 i-download-2"></i> Actualización de posMe</h1>
</div>
