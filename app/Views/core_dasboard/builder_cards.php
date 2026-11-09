<?php
/*
 * Parcial: tarjetas del Builder Report en un dashboard.
 * Uso desde el controlador: view('core_dasboard/builder_cards', ['dashboardCode' => 'core_dashboards/index'])
 * Si no hay tarjetas o hay error, el contenedor queda oculto y el dashboard se ve igual.
 */
$dashboardCode = isset($dashboardCode) ? (string) $dashboardCode : '';
?>
<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-dashboard-cards.css?v=2" rel="stylesheet" />
<div class="pdash" style="display:none"
     data-dashboard="<?php echo htmlspecialchars($dashboardCode, ENT_QUOTES, 'UTF-8'); ?>"
     data-url="<?php echo base_url(); ?>"
     data-resource="<?php echo APP_URL_RESOURCE_CSS_JS; ?>">
    <div class="pdash-grid"></div>
</div>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-report-builder.js?v=2"></script>
<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-dashboard-cards.js?v=5"></script>
