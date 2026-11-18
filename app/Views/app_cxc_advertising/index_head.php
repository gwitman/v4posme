<?php echo view('core_template/posme_v2_style'); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-advertising.css?v=1" rel="stylesheet" />
<style>
    /* Navegación con pago: los botones se acomodan en varias líneas sin scroll horizontal */
    .pv2.adv .adv-nav { flex-wrap: wrap; }
    .pv2.adv .adv-nav-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 10px; margin-left: auto; min-width: 0; }
    .pv2.adv .adv-nav-actions .btn { max-width: 100%; }
    .pv2.adv .adv-btn-pay { display: inline-flex; align-items: center; gap: 6px; font-weight: 600; }
    .pv2.adv .pv2-mbar .btn { max-width: 100%; white-space: nowrap; }
    /* Tarjeta del cron job: título y estado en una línea; acciones táctiles que bajan de línea en móvil */
    .pv2.adv .adv-cron-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px; }
    .pv2.adv .adv-cron-head .pv2-card-title { margin-bottom: 0; }
    .pv2.adv .adv-cron-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
    .pv2.adv .adv-cron-actions .btn { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; max-width: 100%; }
    /* Pestañas Campañas / Conexión WhatsApp */
    .pv2.adv .adv-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; border-bottom: 1px solid var(--pv2-border); }
    .pv2.adv .adv-tab { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; padding: 8px 16px; border: 0; border-bottom: 3px solid transparent; background: transparent; color: var(--pv2-muted); font-weight: 600; font-size: 14px; cursor: pointer; }
    .pv2.adv .adv-tab.is-active { color: var(--pv2-accent); border-bottom-color: var(--pv2-accent-2); }
    .pv2.adv .adv-tab-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--pv2-border); }
    .pv2.adv .adv-tab-dot.is-ok { background: var(--pv2-ok); }
    .pv2.adv .adv-tab-dot.is-bad { background: var(--pv2-bad); }
    /* Conexión WhatsApp: datos de la instancia y código QR */
    .pv2.adv .adv-wa-info { display: flex; flex-wrap: wrap; gap: 8px 24px; margin: 0 0 12px; }
    .pv2.adv .adv-wa-info dt { font-size: 12px; color: var(--pv2-muted); font-weight: 600; }
    .pv2.adv .adv-wa-info dd { margin: 0; font-weight: 700; overflow-wrap: anywhere; }
    .pv2.adv .adv-wa-qr { display: flex; flex-wrap: wrap; align-items: flex-start; gap: 16px; margin-bottom: 14px; }
    .pv2.adv .adv-wa-qr img { width: 260px; max-width: 100%; height: auto; padding: 8px; background: #fff; border: 1px solid var(--pv2-border); border-radius: 10px; }
    .pv2.adv .adv-wa-qr .adv-wa-steps { flex: 1 1 220px; margin: 0; padding-left: 18px; font-size: 14px; }
    .pv2.adv .adv-wa-qr .pv2-help { flex-basis: 100%; margin: 0; }
</style>

<div id="heading" class="page-header">
    <h1><i class="bi bi-megaphone"></i> Campañas WhatsApp</h1>
</div>
