<style>
	/* ==========================================================
	   app_med_query v2 - estilos compartidos de lista, nuevo y edicion
	   Todo esta bajo .mqv2 para no afectar otras pantallas.
	   ========================================================== */
	.mqv2 { --mqv2-accent:#0e7c86; --mqv2-accent-2:#2f6fb5; --mqv2-accent-bg:#e6f4f5; --mqv2-ok:#2e7d32; --mqv2-ok-bg:#eaf5ea; --mqv2-bad:#c62828; --mqv2-bad-bg:#fdecec; --mqv2-warn:#8a5a00; --mqv2-warn-bg:#fdf3dc; --mqv2-info:#1565c0; --mqv2-info-bg:#e8f1fc; --mqv2-border:#e3e6ea; --mqv2-muted:#6b7280; --mqv2-soft:#f6f7f9; color:#1f2933; }
	.mqv2 *, .mqv2 *:before, .mqv2 *:after { box-sizing:border-box; }

	/* Tarjetas */
	.mqv2-card { background:#fff; border:1px solid var(--mqv2-border); border-radius:12px; padding:14px 16px; margin-bottom:14px; box-shadow:0 1px 3px rgba(16,24,40,.05); }
	.mqv2-card-title { font-size:14px; font-weight:700; margin:0 0 12px; display:flex; align-items:center; gap:8px; color:#1f2933; }
	.mqv2-card-title .mqv2-ico { width:28px; height:28px; border-radius:8px; background:var(--mqv2-accent-bg); color:var(--mqv2-accent); display:inline-flex; align-items:center; justify-content:center; font-size:13px; flex:0 0 28px; }

	/* Encabezado */
	.mqv2-header { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; background:linear-gradient(135deg, var(--mqv2-accent) 0%, var(--mqv2-accent-2) 100%); border:0; color:#fff; }
	.mqv2-header-info { display:flex; align-items:center; gap:12px; min-width:0; }
	.mqv2-header-icon { width:46px; height:46px; border-radius:12px; background:rgba(255,255,255,.18); color:#fff; display:flex; align-items:center; justify-content:center; flex:0 0 46px; font-size:20px; }
	.mqv2-header-num { font-size:19px; font-weight:700; margin:0; line-height:1.25; color:#fff; }
	.mqv2-header-sub { font-size:12px; color:rgba(255,255,255,.85); margin:0; }
	.mqv2-chip { display:inline-block; font-size:12px; padding:3px 10px; border-radius:20px; background:rgba(255,255,255,.22); color:#fff; font-weight:600; vertical-align:middle; margin-left:4px; }
	.mqv2-actions { display:flex; flex-wrap:wrap; gap:6px; }
	.mqv2-actions .btn { border-radius:8px; }
	.mqv2-header .mqv2-actions .btn-default { background:rgba(255,255,255,.95); border-color:transparent; color:#1f2933; }
	.mqv2-header .mqv2-actions .btn-default:hover { background:#fff; }
	.mqv2-header .mqv2-actions .btn-primary { background:#fff; border-color:#fff; color:var(--mqv2-accent); font-weight:700; }
	.mqv2-header .mqv2-actions .btn-danger { background:var(--mqv2-bad-bg); border-color:transparent; color:var(--mqv2-bad); }

	/* Formularios */
	.mqv2 .form-group { margin-bottom:12px; }
	.mqv2 .mqv2-label { display:block; font-size:12px; font-weight:600; color:var(--mqv2-muted); margin:0 0 4px; text-align:left; }
	.mqv2 .form-control { height:38px; border-radius:8px; font-size:14px; border:1px solid #d5dae0; box-shadow:none; }
	.mqv2 .form-control:focus { border-color:var(--mqv2-accent); box-shadow:0 0 0 3px rgba(14,124,134,.15); outline:0; }
	.mqv2 textarea.form-control { height:auto; min-height:76px; }
	.mqv2 .form-control[readonly] { background:var(--mqv2-soft); }
	.mqv2 .input-group .form-control { border-radius:8px 0 0 8px; }
	.mqv2 .input-group-btn .btn { height:38px; }
	.mqv2 .input-group-btn:last-child .btn { border-radius:0 8px 8px 0; }
	.mqv2 .input-group-addon { border-radius:0 8px 8px 0; }
	.mqv2 .mqv2-unit { position:relative; }
	.mqv2 .mqv2-unit .form-control { padding-right:46px; }
	.mqv2 .mqv2-unit span { position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:12px; color:var(--mqv2-muted); pointer-events:none; }

	/* Combos nativos (sin select2) */
	.mqv2 select.form-control { -webkit-appearance:none; -moz-appearance:none; appearance:none; background:#fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%236b7280' stroke-width='2' d='M1 1l5 5 5-5'/%3E%3C/svg%3E") no-repeat right 12px center; background-size:12px 8px; padding-right:34px; cursor:pointer; }
	.mqv2 select.form-control::-ms-expand { display:none; }
	.mqv2 .select2-container { width:100% !important; }
	/* por si uniform alcanzo a envolver algun combo */
	.mqv2 div.selector, .mqv2 div.selector span { width:100% !important; }
	.mqv2 div.selector select { width:100% !important; }

	/* IMC */
	.mqv2-imc { display:flex; align-items:center; gap:10px; background:var(--mqv2-soft); border-radius:10px; padding:8px 12px; }
	.mqv2-imc input.form-control { border:0; background:transparent; padding:0; height:auto; width:90px; font-size:22px; font-weight:700; box-shadow:none; color:#1f2933; }
	.mqv2-imc-label { font-size:12px; font-weight:700; border-radius:20px; padding:3px 10px; background:#fff; color:var(--mqv2-muted); }
	.mqv2-imc.is-low  { background:var(--mqv2-info-bg); }	.mqv2-imc.is-low  .mqv2-imc-label { color:var(--mqv2-info); }
	.mqv2-imc.is-ok   { background:var(--mqv2-ok-bg); }		.mqv2-imc.is-ok   .mqv2-imc-label { color:var(--mqv2-ok); }
	.mqv2-imc.is-warn { background:var(--mqv2-warn-bg); }	.mqv2-imc.is-warn .mqv2-imc-label { color:var(--mqv2-warn); }
	.mqv2-imc.is-bad  { background:var(--mqv2-bad-bg); }	.mqv2-imc.is-bad  .mqv2-imc-label { color:var(--mqv2-bad); }
	.mqv2-imc-legend { display:flex; flex-wrap:wrap; gap:4px; margin-top:6px; }
	.mqv2-imc-legend span { font-size:11px; border-radius:20px; padding:2px 8px; }
	.mqv2-imc-legend .l1 { background:var(--mqv2-info-bg); color:var(--mqv2-info); }
	.mqv2-imc-legend .l2 { background:var(--mqv2-ok-bg); color:var(--mqv2-ok); }
	.mqv2-imc-legend .l3 { background:var(--mqv2-warn-bg); color:var(--mqv2-warn); }
	.mqv2-imc-legend .l4 { background:var(--mqv2-bad-bg); color:var(--mqv2-bad); }

	/* Receta / detalle */
	.mqv2-detail-head { display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap; margin-bottom:10px; }
	.mqv2-detail-head .mqv2-card-title { margin:0; }
	.mqv2-count { font-size:12px; background:var(--mqv2-accent-bg); color:var(--mqv2-accent); border-radius:20px; padding:1px 8px; font-weight:700; }
	.mqv2-entry { background:var(--mqv2-soft); border:1px dashed #cfd6dd; border-radius:10px; padding:12px 12px 2px; margin-bottom:12px; }
	.mqv2-entry .mqv2-entry-btn { display:flex; align-items:flex-end; }
	.mqv2-entry .mqv2-entry-btn .btn { width:100%; height:38px; border-radius:8px; }
	.mqv2-error { display:block; color:var(--mqv2-bad); font-size:12px; margin-top:4px; }
	.mqv2-table { width:100%; margin:0; border-collapse:collapse; }
	.mqv2-table th { font-size:12px; font-weight:600; color:var(--mqv2-muted); padding:8px 6px; border-bottom:1px solid var(--mqv2-border); background:transparent; }
	.mqv2-table td { padding:6px; border-bottom:1px solid var(--mqv2-border); vertical-align:middle; font-size:14px; }
	.mqv2-table .mqv2-col-amount { width:120px; }
	.mqv2-table .mqv2-col-del { width:44px; text-align:center; }
	.mqv2-btn-del { color:var(--mqv2-bad); padding:4px 8px; background:transparent; border:0; }
	.mqv2-btn-del:hover, .mqv2-btn-del:focus { color:var(--mqv2-bad); background:var(--mqv2-bad-bg); }
	.mqv2-empty { text-align:center; color:var(--mqv2-muted); padding:22px 10px; border:1px dashed var(--mqv2-border); border-radius:10px; margin-top:8px; }
	.mqv2-empty i { display:block; margin:0 auto 6px; color:var(--mqv2-accent); font-size:20px; }

	/* Lista */
	.mqv2-listbar { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
	.mqv2-search { display:flex; flex:1 1 280px; max-width:420px; }
	.mqv2-search .form-control { border-radius:8px 0 0 8px; }
	.mqv2-search .btn { border-radius:0 8px 8px 0; height:38px; }

	/* Barra inferior (solo telefono) */
	.mqv2-mbar { display:none; }

	/* Busqueda dentro de la pantalla (telefono) */
	#mqv2SearchModal .modal-dialog { width:auto; margin:0; height:100%; }
	#mqv2SearchModal .modal-content { border-radius:0; height:100%; border:0; display:flex; flex-direction:column; }
	#mqv2SearchModal .modal-header { padding:10px 14px; display:flex; align-items:center; justify-content:space-between; }
	#mqv2SearchModal .modal-title { font-size:16px; font-weight:700; }
	#mqv2SearchModal .modal-body { padding:0; flex:1 1 auto; position:relative; }
	#mqv2SearchModal iframe { border:0; width:100%; height:100%; position:absolute; top:0; left:0; }

	/* ===================== Telefono ===================== */
	@media (max-width:767px) {
		.mqv2 { overflow-x:hidden; overflow-x:clip; }
		.mqv2.mqv2-form { padding-bottom:84px; }
		.mqv2-card { padding:12px; }
		.mqv2-header-num { font-size:17px; }
		.mqv2-actions { width:100%; }
		.mqv2-actions .btn { flex:1 1 auto; }
		.mqv2-actions .mqv2-hide-xs { display:none; }
		.mqv2 .form-control { height:44px; font-size:16px; }
		.mqv2 .input-group-btn .btn { height:44px; }
		.mqv2-entry .mqv2-entry-btn .btn { height:44px; }
		.mqv2-search { max-width:none; }
		.mqv2-search .btn { height:44px; }

		/* La receta se convierte en tarjetas */
		.mqv2-table thead { display:none; }
		.mqv2-table, .mqv2-table tbody { display:block; }
		.mqv2-table tr.mqv2-row { display:block; position:relative; border:1px solid var(--mqv2-border); border-radius:10px; padding:10px 12px; margin-bottom:10px; }
		.mqv2-table tr.mqv2-row td { display:block; border:0; padding:4px 0; width:auto; }
		.mqv2-table tr.mqv2-row td:before { content:attr(data-label); display:block; font-size:12px; color:var(--mqv2-muted); font-weight:600; margin-bottom:3px; }
		.mqv2-table tr.mqv2-row td.mqv2-col-name { padding-right:44px; }
		.mqv2-table tr.mqv2-row td.mqv2-col-del { position:absolute; top:6px; right:6px; padding:0; }
		.mqv2-table tr.mqv2-row td.mqv2-col-del:before { display:none; }

		.mqv2-mbar { display:flex; align-items:center; gap:10px; position:fixed; left:0; right:0; bottom:0; z-index:1030; background:#fff; border-top:1px solid var(--mqv2-border); padding:10px 14px; box-shadow:0 -2px 10px rgba(0,0,0,.06); }
		.mqv2-mbar-info { flex:1 1 auto; min-width:0; }
		.mqv2-mbar-label { font-size:11px; color:var(--mqv2-muted); margin:0; }
		.mqv2-mbar-name { font-size:15px; font-weight:700; margin:0; line-height:1.2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
		.mqv2-mbar .btn { height:46px; padding:0 20px; font-size:15px; border-radius:10px; display:inline-flex; align-items:center; gap:6px; background:var(--mqv2-accent); border-color:var(--mqv2-accent); }
		/* mientras se escribe (teclado abierto) la barra se oculta para no tapar el campo */
		body.mqv2-typing .mqv2-mbar { display:none; }
		.mqv2 input, .mqv2 select, .mqv2 textarea { scroll-margin-bottom:110px; }
	}
</style>
