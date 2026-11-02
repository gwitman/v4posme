				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" /> 
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
				<?php echo view('core_template/posme_v2_style'); ?>
				<?php echo view('core_template/posme_v2_common'); ?>
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/posme-inventory.css?v=1" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/posme-inventory.js?v=2"></script>
				<style>
					/* Catalogo: celdas "Configuracion" con varios campos (etiqueta arriba del campo) */
					.pv2.pinv.pinv-form #tb_transaction_master_detail tbody td .form-group { margin-bottom:8px; }
					.pv2.pinv.pinv-form #tb_transaction_master_detail tbody td .form-group:last-child { margin-bottom:0; }
					.pv2.pinv.pinv-form #tb_transaction_master_detail tbody td .form-group > .control-label { white-space:nowrap; }
					.pv2.pinv.pinv-form #tb_transaction_master_detail tbody td input.form-control { width:100%; min-width:120px; }
					.pv2.pinv.pinv-form #tb_transaction_master_detail tbody td { vertical-align:top; }
					.pv2.pinv.pinv-form #tb_transaction_master_detail tbody td:first-child { vertical-align:middle; }
					@media (max-width:767px) {
						/* cada fila es una tarjeta: casilla arriba a la izquierda y los grupos de campos uno debajo del otro */
						.pv2.pinv.pinv-form #tb_transaction_master_detail tbody tr { padding:44px 12px 8px 12px; }
						.pv2.pinv.pinv-form #tb_transaction_master_detail tbody tr td { display:block !important; text-align:left; padding:6px 0; }
						.pv2.pinv.pinv-form #tb_transaction_master_detail tbody tr td:first-child { display:block !important; position:absolute; top:12px; left:12px; padding:0; }
						.pv2.pinv.pinv-form #tb_transaction_master_detail tbody tr td[data-label]:not(:first-child):before { display:block; max-width:none; margin:0 0 6px; padding-bottom:4px; border-bottom:1px solid var(--pv2-border); font-size:12px; text-transform:uppercase; letter-spacing:.3px; color:var(--pv2-accent); }
						.pv2.pinv.pinv-form #tb_transaction_master_detail tbody td input[type="text"] { display:block; flex:none; width:100%; max-width:100%; min-width:0; text-align:left; }
					}
				</style>

				<div id="heading" class="page-header">
							<h1><i class="icon20  i-storage-2"></i> Agregar Catalogo</h1>
				</div> 