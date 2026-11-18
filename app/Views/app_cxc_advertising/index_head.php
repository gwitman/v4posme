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
</style>

<div id="heading" class="page-header">
    <h1><i class="bi bi-megaphone"></i> Campañas WhatsApp</h1>
</div>
