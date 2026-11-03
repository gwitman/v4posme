				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				<?php echo view('core_template/posme_v2_style'); ?>
				<?php echo view('core_template/posme_v2_common'); ?>
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-inventory.css?v=1" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-inventory.js?v=2"></script>
				<style>
					/* Ciclos del periodo: tabla en PC / tableta */
					.pv2 #ListComponentCycle td .pv2-cycle-field { min-width:130px; }
					.pv2 #ListComponentCycle td .form-control { height:34px; border-radius:8px; }
					.pv2 #ListComponentCycle td .input-group .form-control { border-radius:8px 0 0 8px; }
					.pv2 #ListComponentCycle td input[readonly] { background:var(--pv2-soft); }
					.pv2 #ListComponentCycle td .select2-container { width:100% !important; }
					.pv2 #ListComponentCycle tbody tr:nth-child(even) td { background:var(--pv2-soft); }
					/* Ciclos del periodo: en telefono cada fila es una tarjeta (data-label lo pone PosmeInv) */
					@media (max-width:767px) {
						.pv2 #ListComponentCycle tbody tr { display:block; position:relative; border:1px solid var(--pv2-border); border-radius:12px; padding:10px 12px 10px 46px; margin-bottom:10px; background:#fff; box-shadow:0 1px 3px rgba(16,24,40,.05); }
						.pv2 #ListComponentCycle tbody tr td,
						.pv2 #ListComponentCycle tbody tr:nth-child(even) td { display:flex; justify-content:space-between; align-items:center; gap:4px 10px; border:0; padding:4px 0; background:transparent; }
						.pv2 #ListComponentCycle tbody tr td[data-label]:before { content:attr(data-label); flex:0 0 auto; max-width:40%; font-size:12px; color:var(--pv2-muted); font-weight:600; text-align:left; }
						.pv2 #ListComponentCycle tbody tr td[data-label=""]:before { display:none; }
						.pv2 #ListComponentCycle tbody tr td.pv2-col-check { position:absolute; top:12px; left:12px; padding:0; width:auto; }
						.pv2 #ListComponentCycle tbody tr td.pv2-col-check > input[type="checkbox"] { width:22px; height:22px; margin:0; }
						.pv2 #ListComponentCycle td .pv2-cycle-field { flex:1 1 60%; max-width:62%; min-width:0; }
						.pv2 #ListComponentCycle td .form-control { width:100%; height:44px; font-size:16px; border-radius:8px; }
					}
				</style>

				<div id="heading" class="page-header">
							<h1><i class="icon20  i-calendar-2"></i> Nuevo Periodo Contable</h1>
				</div>
