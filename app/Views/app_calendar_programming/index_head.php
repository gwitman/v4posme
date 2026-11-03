<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
<!-- Font Awesome (CDN) -->
<link rel="stylesheet" href="<?= APP_URL_RESOURCE_CSS_JS?>/resource/css/font-awesome-6-5-1/all.min.css">


<script src='<?= APP_URL_RESOURCE_CSS_JS?>/resource/js/fullcallendar/index.global.js'></script>
<style>
    .fc-icon.fc-icon-fa {
        font-family: "FontAwesome" !important;
    }
    .fc .fc-button .fc-icon{
        font-size: unset;
    }

	.tooltip-inner {
		text-align: left !important;
		white-space: pre-line; /* permite respetar saltos */
	}

    /* ==========================================================
       Calendario > Programacion (capa visual pv2)
       ========================================================== */
    .pv2-cal-header { padding:14px 16px; }
    .pv2-cal-header .pv2-header-title { font-size:19px; }
    .pv2-cal-card { padding:12px; }
    .pv2-cal-card .fc .fc-toolbar { flex-wrap:wrap; gap:8px; }
    .pv2-cal-card .fc .fc-toolbar-title { font-size:18px; font-weight:700; color:#1f2933; }
    .pv2-cal-card .fc .fc-button { border-radius:8px; }

    /* Modales del calendario */
    .pv2-cal-modal .modal-content { border:0; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(16,24,40,.25); }
    .pv2-cal-modal .modal-header { padding:14px 16px; border-bottom:0; border-radius:0; flex-wrap:nowrap; }
    .pv2-cal-modal .modal-header:before, .pv2-cal-modal .modal-header:after,
    .pv2-cal-modal .modal-footer:before, .pv2-cal-modal .modal-footer:after { display:none; content:none; }
    .pv2-cal-modal .modal-header .pv2-header-title { font-size:17px; }
    .pv2-cal-modal .modal-header .pv2-header-icon { width:40px; height:40px; flex:0 0 40px; font-size:18px; }
    .pv2-cal-modal .modal-header .close { color:#fff; opacity:.85; text-shadow:none; font-size:28px; margin:0; float:none; padding:0 4px; }
    .pv2-cal-modal .modal-header .close:hover, .pv2-cal-modal .modal-header .close:focus { opacity:1; color:#fff; }
    .pv2-cal-modal .modal-body { background:var(--pv2-soft); padding:14px 16px 2px; }
    .pv2-cal-modal .modal-footer { display:flex; align-items:center; flex-wrap:wrap; gap:8px; background:#fff; border-top:1px solid var(--pv2-border); padding:12px 16px; }
    .pv2-cal-modal .modal-footer .btn { margin:0; border-radius:8px; }
    .pv2-cal-modal .modal-footer .pv2-cal-left { margin-right:auto; }
    .pv2-cal-modal textarea.form-control { min-height:80px; resize:vertical; }
    .pv2-cal-modal .pv2-cal-row { margin-left:-6px; margin-right:-6px; }
    .pv2-cal-modal .pv2-cal-row > [class*="col-"] { padding-left:6px; padding-right:6px; }

    /* Errores de validacion (showError) */
    .pv2-cal-modal .form-control.is-invalid { border-color:var(--pv2-bad); background-color:var(--pv2-bad-bg); }
    .pv2-cal-modal .form-control.is-invalid:focus { box-shadow:0 0 0 3px rgba(198,40,40,.15); }
    .pv2-cal-modal .error-message { font-size:12px; font-weight:600; color:var(--pv2-bad); margin-top:4px; }
    .pv2-cal-modal .error-message:before { content:"\f06a"; font-family:"Font Awesome 6 Free"; font-weight:900; margin-right:5px; }

    /* Informacion del evento con documento (etiqueta / valor) */
    .pv2-cal-event-title { font-size:15px; font-weight:700; color:#1f2933; margin:0 0 10px; word-wrap:break-word; }
    .pv2-cal-info-row { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; padding:7px 0; border-top:1px solid var(--pv2-border); font-size:14px; }
    .pv2-cal-info-row:first-child { border-top:0; }
    .pv2-cal-info-label { font-size:12px; font-weight:600; color:var(--pv2-muted); flex:0 0 auto; padding-top:1px; }
    .pv2-cal-info-value { text-align:right; color:#1f2933; word-wrap:break-word; overflow-wrap:anywhere; min-width:0; }
    .pv2-cal-info-row.is-block { display:block; }
    .pv2-cal-info-row.is-block .pv2-cal-info-value { display:block; text-align:left; margin-top:3px; }
    .pv2-cal-hint { font-size:12px; color:var(--pv2-muted); margin:0 0 12px; }

    /* Confirmacion del sistema encima del modal de evento */
    #myModalConfirm { z-index:1070; }

    /* ===================== Telefono ===================== */
    @media (max-width:767px) {
        .pv2-cal-header .pv2-header-title { font-size:17px; }
        .pv2-cal-card { padding:8px; }
        .pv2-cal-card .fc .fc-toolbar-title { font-size:16px; }
        .pv2-cal-card .fc .fc-button { padding:8px 10px; }

        /* Modal a pantalla completa (mismo patron que #pv2SearchModal) */
        .pv2-cal-modal .modal-dialog { width:auto; margin:0; height:100%; }
        .pv2-cal-modal .modal-content { border-radius:0; height:100%; border:0; display:flex; flex-direction:column; box-shadow:none; }
        .pv2-cal-modal .modal-header { flex:0 0 auto; padding:10px 14px; }
        .pv2-cal-modal .modal-body { flex:1 1 auto; overflow-y:auto; -webkit-overflow-scrolling:touch; padding:12px 12px 2px; }
        .pv2-cal-modal .modal-footer { flex:0 0 auto; position:sticky; bottom:0; z-index:2; padding:10px 12px; box-shadow:0 -2px 10px rgba(0,0,0,.06); }
        .pv2-cal-modal .modal-footer .btn { flex:1 1 40%; min-height:44px; font-size:15px; }
        .pv2-cal-modal .modal-footer .pv2-cal-left { margin-right:0; }
        .pv2-cal-modal .form-control,
        .pv2-cal-modal input,
        .pv2-cal-modal select,
        .pv2-cal-modal textarea { font-size:16px; }
        .pv2-cal-modal .form-control { min-height:44px; }
    }
</style>
