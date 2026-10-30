<style>
	/* ==========================================================
	   app_box_share v2 - estilos compartidos de nuevo y edicion
	   Todo esta bajo .abv2 para no afectar otras pantallas.
	   ========================================================== */
	.abv2 { --abv2-accent:#2f6fb5; --abv2-accent-bg:#eaf2fb; --abv2-ok:#2e7d32; --abv2-ok-bg:#eaf5ea; --abv2-bad:#c62828; --abv2-bad-bg:#fdecec; --abv2-warn:#8a5a00; --abv2-warn-bg:#fdf3dc; --abv2-border:#e3e6ea; --abv2-muted:#6b7280; --abv2-soft:#f6f7f9; color:#1f2933; }
	.abv2 *, .abv2 *:before, .abv2 *:after { box-sizing:border-box; }

	/* Tarjetas */
	.abv2-card { background:#fff; border:1px solid var(--abv2-border); border-radius:10px; padding:14px 16px; margin-bottom:14px; }
	.abv2-card-title { font-size:14px; font-weight:700; margin:0 0 12px; display:flex; align-items:center; gap:6px; color:#1f2933; }
	.abv2-card-title i { color:var(--abv2-accent); }

	/* Encabezado */
	.abv2-header { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
	.abv2-header-info { display:flex; align-items:center; gap:12px; min-width:0; }
	.abv2-header-icon { width:42px; height:42px; border-radius:10px; background:var(--abv2-accent-bg); color:var(--abv2-accent); display:flex; align-items:center; justify-content:center; flex:0 0 42px; }
	.abv2-header-num { font-size:18px; font-weight:700; margin:0; line-height:1.2; }
	.abv2-header-sub { font-size:12px; color:var(--abv2-muted); margin:0; }
	.abv2-chip { display:inline-block; font-size:12px; padding:3px 10px; border-radius:20px; background:var(--abv2-warn-bg); color:var(--abv2-warn); font-weight:600; vertical-align:middle; }
	.abv2-chip.is-applied { background:var(--abv2-ok-bg); color:var(--abv2-ok); }
	.abv2-actions { display:flex; flex-wrap:wrap; gap:6px; }
	.abv2-actions .btn { border-radius:6px; }

	/* Formularios */
	.abv2 .form-group { margin-bottom:12px; }
	.abv2 .abv2-label { display:block; font-size:12px; font-weight:600; color:var(--abv2-muted); margin:0 0 4px; text-align:left; }
	.abv2 .form-control { height:38px; border-radius:6px; font-size:14px; }
	.abv2 textarea.form-control { height:auto; }
	.abv2 .abv2-num { text-align:right; font-variant-numeric:tabular-nums; }
	.abv2 .input-group .form-control { border-radius:6px 0 0 6px; }
	.abv2 .input-group-btn .btn { height:38px; }
	.abv2 .input-group-btn:last-child .btn { border-radius:0 6px 6px 0; }
	.abv2 .select2-container { width:100% !important; }
	/* uniform en selects (telefono): que ocupe todo el ancho */
	.abv2 div.selector { width:100% !important; }
	.abv2 div.selector span { width:100% !important; }
	.abv2 div.selector select { width:100% !important; }

	/* Indicadores de saldo */
	.abv2-stats { display:flex; gap:10px; margin-top:4px; }
	.abv2-stat { flex:1 1 0; min-width:0; background:var(--abv2-soft); border-radius:8px; padding:8px 10px; }
	.abv2-stat .abv2-label { margin-bottom:2px; }
	.abv2-stat input.abv2-stat-value { border:0; background:transparent; padding:0; height:auto; width:100%; font-size:17px; font-weight:700; color:#1f2933; box-shadow:none; }
	.abv2-stat.is-final input.abv2-stat-value { color:var(--abv2-ok); }
	.abv2-rates { display:flex; flex-wrap:wrap; gap:6px; margin-top:10px; }
	.abv2-rate { font-size:12px; background:var(--abv2-soft); border-radius:20px; padding:3px 10px; color:var(--abv2-muted); }
	.abv2-rate b { color:#1f2933; }

	/* Detalle de facturas */
	.abv2-detail-head { display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap; margin-bottom:10px; }
	.abv2-detail-head .abv2-card-title { margin:0; }
	.abv2-count { font-size:12px; background:var(--abv2-accent-bg); color:var(--abv2-accent); border-radius:20px; padding:1px 8px; font-weight:700; }
	.abv2-table { width:100%; margin:0; border-collapse:collapse; }
	.abv2-table th { font-size:12px; font-weight:600; color:var(--abv2-muted); text-transform:none; padding:8px 10px; border-bottom:1px solid var(--abv2-border); background:transparent; }
	.abv2-table td { padding:8px 10px; border-bottom:1px solid var(--abv2-border); vertical-align:middle; font-size:14px; }
	.abv2-table td.abv2-doc { font-weight:700; }
	.abv2-table .abv2-col-check { width:36px; }
	.abv2-table .abv2-col-share { width:180px; }
	.abv2-table .abv2-col-del { width:44px; text-align:center; }
	.abv2-btn-del { color:var(--abv2-bad); padding:4px 8px; }
	.abv2-btn-del:hover, .abv2-btn-del:focus { color:var(--abv2-bad); background:var(--abv2-bad-bg); }
	.abv2-empty { text-align:center; color:var(--abv2-muted); padding:22px 10px; border:1px dashed var(--abv2-border); border-radius:8px; margin-top:8px; }
	.abv2-empty i { display:block; margin:0 auto 6px; color:var(--abv2-accent); }

	/* Tabs */
	.abv2 .nav-tabs { margin-bottom:12px; }
	.abv2 .nav-tabs > li > a { font-size:13px; padding:8px 12px; }

	/* Pago y total */
	.abv2-pay .form-group { margin-bottom:10px; }
	.abv2-change { display:flex; justify-content:space-between; align-items:center; padding:8px 0 10px; border-top:1px solid var(--abv2-border); margin-top:4px; font-size:14px; }
	.abv2-change input { border:0; background:transparent; text-align:right; font-weight:700; font-size:15px; width:60%; padding:0; box-shadow:none; color:var(--abv2-ok); }
	.abv2-change.is-negative input { color:var(--abv2-bad); }
	.abv2-total { background:var(--abv2-accent-bg); border-radius:8px; padding:12px; }
	.abv2-total .abv2-label { color:var(--abv2-accent); margin-bottom:2px; }
	.abv2-total input { border:0; background:transparent; width:100%; padding:0; font-size:24px; font-weight:700; color:var(--abv2-accent); box-shadow:none; height:auto; }
	@media (min-width:992px) { .abv2-sticky { position:sticky; top:12px; } }

	/* Barra inferior (solo telefono) */
	.abv2-mbar { display:none; }

	/* Busqueda dentro de la pantalla (telefono) */
	#abv2SearchModal .modal-dialog { width:auto; margin:0; height:100%; }
	#abv2SearchModal .modal-content { border-radius:0; height:100%; border:0; display:flex; flex-direction:column; }
	#abv2SearchModal .modal-header { padding:10px 14px; display:flex; align-items:center; justify-content:space-between; }
	#abv2SearchModal .modal-title { font-size:16px; font-weight:700; }
	#abv2SearchModal .modal-body { padding:0; flex:1 1 auto; position:relative; }
	#abv2SearchModal iframe { border:0; width:100%; height:100%; position:absolute; top:0; left:0; }

	/* ===================== Telefono ===================== */
	@media (max-width:767px) {
		.abv2 { padding-bottom:84px; overflow-x:hidden; overflow-x:clip; }
		.abv2-card { padding:12px; border-radius:12px; }
		.abv2-header-num { font-size:16px; }
		.abv2-actions { width:100%; }
		.abv2-actions .btn { flex:1 1 auto; }
		.abv2-actions .abv2-hide-xs { display:none; }

		/* La tabla de facturas se convierte en tarjetas */
		.abv2-table thead { display:none; }
		.abv2-table, .abv2-table tbody { display:block; }
		.abv2-table tr.row_razon { display:block; position:relative; border:1px solid var(--abv2-border); border-radius:10px; padding:10px 12px; margin-bottom:10px; }
		.abv2-table tr.row_razon td { display:flex; justify-content:space-between; align-items:center; border:0; padding:3px 0; font-size:14px; }
		.abv2-table tr.row_razon td:before { content:attr(data-label); font-size:12px; color:var(--abv2-muted); font-weight:600; padding-right:10px; }
		.abv2-table tr.row_razon td.abv2-col-check { display:none; }
		.abv2-table tr.row_razon td.abv2-doc { font-size:15px; padding-right:40px; }
		.abv2-table tr.row_razon td.abv2-col-share { display:block; width:auto; padding-top:6px; }
		.abv2-table tr.row_razon td.abv2-col-share:before { display:block; margin-bottom:4px; }
		.abv2-table tr.row_razon td.abv2-col-share input { height:44px; font-size:18px; }
		.abv2-table tr.row_razon td.abv2-col-del { position:absolute; top:6px; right:6px; width:auto; padding:0; }
		.abv2-table tr.row_razon td.abv2-col-del:before { display:none; }
		.abv2 .form-control { height:44px; font-size:16px; }
		.abv2 .input-group-btn .btn { height:44px; }

		.abv2-mbar { display:flex; align-items:center; gap:10px; position:fixed; left:0; right:0; bottom:0; z-index:1030; background:#fff; border-top:1px solid var(--abv2-border); padding:10px 14px; box-shadow:0 -2px 10px rgba(0,0,0,.06); }
		.abv2-mbar-info { flex:1 1 auto; min-width:0; }
		.abv2-mbar-label { font-size:11px; color:var(--abv2-muted); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
		.abv2-mbar-total { font-size:20px; font-weight:700; margin:0; line-height:1.2; }
		.abv2-mbar .btn { height:46px; padding:0 20px; font-size:15px; border-radius:8px; display:inline-flex; align-items:center; gap:6px; }
		/* mientras se escribe (teclado abierto) la barra se oculta para no tapar el campo */
		body.abv2-typing .abv2-mbar { display:none; }
		.abv2 input, .abv2 select, .abv2 textarea { scroll-margin-bottom:110px; }
		.abv2-mbar-change.is-negative { color:var(--abv2-bad); font-weight:700; }
	}
</style>
