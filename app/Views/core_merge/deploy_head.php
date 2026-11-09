				<?php echo view('app_box_share/share_v2_style'); ?>

				<style>
					/* ==========================================================
					   core_merge/deploy - consola de despliegue remoto
					   Base visual: ABONOS (.abv2). Aquí solo lo propio (.dpl).
					   ========================================================== */
					.dpl-step-num { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; background:var(--abv2-accent); color:#fff; font-size:12px; font-weight:700; margin-right:4px; }
					.dpl-hint { font-size:12px; color:var(--abv2-muted); margin:4px 0 0; }
					.dpl-chips { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
					.dpl-chip { font-size:12px; border-radius:20px; padding:3px 10px; background:var(--abv2-soft); color:var(--abv2-muted); }
					.dpl-chip b { color:#1f2933; }
					.dpl-chip.is-ok { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
					.dpl-chip.is-bad { background:var(--abv2-bad-bg); color:var(--abv2-bad); }
					.dpl-chip.is-warn { background:var(--abv2-warn-bg); color:var(--abv2-warn); }
					.dpl-btns { display:flex; flex-wrap:wrap; gap:6px; margin-top:6px; }
					.dpl-btns .btn { border-radius:6px; }
					.dpl-alert { border-radius:8px; padding:10px 12px; font-size:13px; margin:10px 0 0; }
					.dpl-alert.is-ok { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
					.dpl-alert.is-bad { background:var(--abv2-bad-bg); color:var(--abv2-bad); }
					.dpl-alert.is-warn { background:var(--abv2-warn-bg); color:var(--abv2-warn); }
					.dpl-alert.is-info { background:var(--abv2-accent-bg); color:var(--abv2-accent); }
					.dpl-checks { list-style:none; padding:0; margin:10px 0 0; }
					.dpl-checks li { display:flex; gap:8px; align-items:flex-start; padding:6px 0; border-bottom:1px solid var(--abv2-border); font-size:13px; }
					.dpl-checks li:last-child { border-bottom:0; }
					.dpl-checks .dpl-ico { flex:0 0 18px; font-weight:700; }
					.dpl-checks .is-ok .dpl-ico { color:var(--abv2-ok); }
					.dpl-checks .is-bad .dpl-ico { color:var(--abv2-bad); }
					.dpl-checks .dpl-group { font-size:11px; color:var(--abv2-muted); margin-left:auto; white-space:nowrap; }
					.dpl-option { display:flex; gap:10px; align-items:flex-start; border:1px solid var(--abv2-border); border-radius:10px; padding:10px 12px; margin-bottom:10px; cursor:pointer; }
					.dpl-option.is-on { border-color:var(--abv2-accent); background:var(--abv2-accent-bg); }
					.dpl-option input[type=checkbox] { width:20px; height:20px; margin:2px 0 0; flex:0 0 20px; }
					.dpl-option-title { font-weight:700; font-size:14px; margin:0; }
					.dpl-option-desc { font-size:12px; color:var(--abv2-muted); margin:2px 0 0; }
					.dpl-option.is-danger .dpl-option-title { color:#c0392b; }
					.dpl-option.is-danger.is-on { border-color:#e74c3c; background:#fdecea; }
					/* pestañas principales: Sincronización / Limpieza de servidor */
					.dpl-maintabs { display:flex; gap:8px; margin:0 0 14px; flex-wrap:wrap; }
					.dpl-maintab { flex:1 1 180px; min-height:44px; border:1px solid var(--abv2-border); border-radius:10px; background:#fff; font-weight:700; font-size:14px; color:var(--abv2-muted); cursor:pointer; }
					.dpl-maintab.is-active { border-color:var(--abv2-accent); background:var(--abv2-accent-bg); color:var(--abv2-accent); }
					.dpl-table tr.is-locked td { color:var(--abv2-muted); }
					.dpl-sub { border-left:3px solid var(--abv2-accent-bg); padding:4px 0 4px 12px; margin:-2px 0 12px; }
					.dpl-scroll { max-height:340px; overflow:auto; border:1px solid var(--abv2-border); border-radius:8px; }
					.dpl-table { width:100%; border-collapse:collapse; }
					.dpl-table th { position:sticky; top:0; background:#fff; font-size:12px; font-weight:600; color:var(--abv2-muted); padding:7px 8px; border-bottom:1px solid var(--abv2-border); text-align:left; z-index:1; }
					.dpl-table td { padding:7px 8px; border-bottom:1px solid var(--abv2-border); font-size:13px; vertical-align:top; word-break:break-word; }
					.dpl-table td.dpl-n { text-align:right; font-variant-numeric:tabular-nums; white-space:nowrap; }
					.dpl-table select.form-control { height:32px; font-size:13px; padding:2px 6px; min-width:150px; }
					.dpl-badge { display:inline-block; font-size:11px; font-weight:700; border-radius:20px; padding:1px 8px; background:var(--abv2-soft); color:var(--abv2-muted); }
					.dpl-badge.is-risk { background:var(--abv2-bad-bg); color:var(--abv2-bad); }
					.dpl-badge.is-new { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
					.dpl-sql { display:none; margin-top:6px; background:#0f172a; color:#e2e8f0; border-radius:6px; padding:8px; font:12px/1.4 Consolas, monospace; white-space:pre-wrap; max-height:220px; overflow:auto; }
					.dpl-sql.is-open { display:block; }
					.dpl-link { font-size:12px; cursor:pointer; color:var(--abv2-accent); }
					.dpl-stages { list-style:none; padding:0; margin:0; }
					.dpl-stages li { display:flex; align-items:center; gap:8px; padding:7px 0; font-size:13px; border-bottom:1px solid var(--abv2-border); }
					.dpl-stages li:last-child { border-bottom:0; }
					.dpl-stage-ico { width:22px; height:22px; border-radius:50%; flex:0 0 22px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; background:var(--abv2-soft); color:var(--abv2-muted); }
					.dpl-stages li.is-run .dpl-stage-ico { background:var(--abv2-accent); color:#fff; animation:dplPulse 1s infinite alternate; }
					.dpl-stages li.is-ok .dpl-stage-ico { background:var(--abv2-ok); color:#fff; }
					.dpl-stages li.is-bad .dpl-stage-ico { background:var(--abv2-bad); color:#fff; }
					.dpl-stages li.is-skip { color:var(--abv2-muted); text-decoration:line-through; }
					.dpl-stage-msg { margin-left:auto; font-size:11px; color:var(--abv2-muted); text-align:right; }
					@keyframes dplPulse { from { opacity:.55; } to { opacity:1; } }
					.dpl-progress { height:10px; border-radius:10px; background:var(--abv2-soft); overflow:hidden; margin:10px 0 4px; }
					.dpl-progress div { height:100%; width:0; background:var(--abv2-accent); transition:width .3s; }
					.dpl-log { background:#0f172a; color:#cbd5e1; border-radius:8px; padding:10px; font:12px/1.45 Consolas, monospace; height:220px; overflow:auto; white-space:pre-wrap; margin:10px 0 0; }
					.dpl-summary { list-style:none; padding:0; margin:0; }
					.dpl-summary li { display:flex; gap:8px; padding:6px 0; font-size:13px; border-bottom:1px solid var(--abv2-border); }
					.dpl-summary li:last-child { border-bottom:0; }
					.dpl-summary .is-off { color:var(--abv2-muted); }
					.dpl-overlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:2000; align-items:center; justify-content:center; padding:16px; }
					.dpl-overlay.is-open { display:flex; }
					.dpl-dialog { background:#fff; border-radius:12px; max-width:560px; width:100%; padding:18px; max-height:90vh; overflow:auto; }
					.dpl-dialog h4 { margin:0 0 10px; font-weight:700; }
					.dpl-secret { font:13px Consolas, monospace; background:var(--abv2-soft); border-radius:6px; padding:8px; word-break:break-all; user-select:all; }
					@media (max-width:767px) {
						.dpl-table select.form-control { min-width:120px; height:40px; font-size:15px; }
						.dpl-option input[type=checkbox] { width:24px; height:24px; }
						.dpl-btns .btn { flex:1 1 auto; min-height:44px; }
						.dpl-checks .dpl-group { display:none; }
					}
				</style>

				<div id="heading" class="page-header">
							<h1><i class="icon20 i-upload-3"></i> Despliegue remoto</h1>
				</div>
