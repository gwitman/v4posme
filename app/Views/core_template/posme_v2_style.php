<style>
	/* ==========================================================
	   posme v2 - capa visual generica (prefijo .pv2)
	   Usada por: app_box_sharecapital, app_box_canceldocument
	   Todo esta bajo .pv2 / #pv2SearchModal para no afectar otras pantallas.
	   ========================================================== */
	.pv2 { --pv2-accent:#3949ab; --pv2-accent-2:#00897b; --pv2-accent-bg:#e8eaf6; --pv2-ok:#2e7d32; --pv2-ok-bg:#eaf5ea; --pv2-bad:#c62828; --pv2-bad-bg:#fdecec; --pv2-warn:#8a5a00; --pv2-warn-bg:#fdf3dc; --pv2-info:#1565c0; --pv2-info-bg:#e8f1fc; --pv2-purple:#6a1b9a; --pv2-purple-bg:#f3e8fa; --pv2-border:#e3e6ea; --pv2-muted:#6b7280; --pv2-soft:#f6f7f9; color:#1f2933; }
	.pv2.pv2-theme-red { --pv2-accent:#c62828; --pv2-accent-2:#ad1457; --pv2-accent-bg:#fdecec; }
	.pv2 *, .pv2 *:before, .pv2 *:after { box-sizing:border-box; }

	/* Tarjetas */
	.pv2-card { background:#fff; border:1px solid var(--pv2-border); border-radius:12px; padding:14px 16px; margin-bottom:14px; box-shadow:0 1px 3px rgba(16,24,40,.05); }
	.pv2-card-title { font-size:14px; font-weight:700; margin:0 0 12px; display:flex; align-items:center; gap:8px; color:#1f2933; }
	.pv2-card-title .pv2-ico { width:28px; height:28px; border-radius:8px; background:var(--pv2-accent-bg); color:var(--pv2-accent); display:inline-flex; align-items:center; justify-content:center; font-size:13px; flex:0 0 28px; }
	.pv2-card.is-required { border-left:4px solid var(--pv2-warn); }
	.pv2-card.has-error { border-color:var(--pv2-bad); box-shadow:0 0 0 3px rgba(198,40,40,.12); }

	/* Encabezado con degradado */
	.pv2-header { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; background:linear-gradient(135deg, var(--pv2-accent) 0%, var(--pv2-accent-2) 100%); border:0; color:#fff; }
	.pv2-header-info { display:flex; align-items:center; gap:12px; min-width:0; }
	.pv2-header-icon { width:46px; height:46px; border-radius:12px; background:rgba(255,255,255,.18); color:#fff; display:flex; align-items:center; justify-content:center; flex:0 0 46px; font-size:20px; }
	.pv2-header-title { font-size:19px; font-weight:700; margin:0; line-height:1.25; color:#fff; }
	.pv2-header-sub { font-size:12px; color:rgba(255,255,255,.85); margin:0; }
	.pv2-chip { display:inline-block; font-size:11px; padding:2px 10px; border-radius:20px; background:rgba(255,255,255,.22); color:#fff; font-weight:700; vertical-align:middle; margin-left:6px; }
	.pv2-chip.is-applied { background:#fff; color:var(--pv2-ok); }
	.pv2-actions { display:flex; flex-wrap:wrap; gap:6px; }
	.pv2-actions .btn { border-radius:8px; }
	.pv2-header .pv2-actions .btn-default { background:rgba(255,255,255,.95); border-color:transparent; color:#1f2933; }
	.pv2-header .pv2-actions .btn-default:hover { background:#fff; }
	.pv2-header .pv2-actions .btn-primary { background:#fff; border-color:#fff; color:var(--pv2-accent); font-weight:700; }
	.pv2-header .pv2-actions .btn-danger { background:rgba(198,40,40,.92); border-color:transparent; }

	/* Formularios */
	.pv2 .form-group { margin-bottom:12px; }
	.pv2 .pv2-label { display:block; font-size:12px; font-weight:600; color:var(--pv2-muted); margin:0 0 4px; text-align:left; }
	.pv2 .pv2-label .pv2-req { color:var(--pv2-bad); }
	.pv2 .pv2-help { display:block; font-size:11px; color:var(--pv2-muted); margin-top:3px; }
	.pv2 .form-control { height:38px; border-radius:8px; font-size:14px; border:1px solid #d5dae0; box-shadow:none; }
	.pv2 .form-control:focus { border-color:var(--pv2-accent); box-shadow:0 0 0 3px rgba(57,73,171,.15); outline:0; }
	.pv2 .form-control[readonly], .pv2 .form-control[disabled] { background:var(--pv2-soft); }
	.pv2 textarea.form-control { height:auto; }
	.pv2 .pv2-num { text-align:right; font-variant-numeric:tabular-nums; }
	.pv2 .input-group .form-control { border-radius:8px 0 0 8px; }
	.pv2 .input-group-btn .btn { height:38px; }
	.pv2 .input-group-btn:last-child .btn { border-radius:0 8px 8px 0; }
	.pv2 .has-error .form-control, .pv2 td.has-error .form-control { border-color:var(--pv2-bad); background:var(--pv2-bad-bg); color:var(--pv2-bad); }

	/* Combos: nativos con flecha propia (telefono). En PC select2 los oculta */
	.pv2 select.form-control { -webkit-appearance:none; -moz-appearance:none; appearance:none; background:#fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%236b7280' stroke-width='2' d='M1 1l5 5 5-5'/%3E%3C/svg%3E") no-repeat right 12px center; background-size:12px 8px; padding-right:34px; cursor:pointer; }
	.pv2 select.form-control::-ms-expand { display:none; }
	.pv2 .select2-container { width:100% !important; }
	/* Telefono: select2 nunca se pinta; el combo nativo se ve desde el primer momento
	   (pv2MobileSelects luego destruye select2 y deja la clase form-control) */
	.pv2.pv2-mobile .select2-container { display:none !important; }
	.pv2.pv2-mobile select.select2, .pv2.pv2-mobile select.select2-offscreen { display:block !important; position:static !important; clip:auto !important; width:100% !important; height:auto !important; min-height:44px; margin:0 !important; overflow:visible !important; padding:8px 34px 8px 12px !important; border:1px solid var(--pv2-border) !important; border-radius:8px; font-size:16px; -webkit-appearance:none; -moz-appearance:none; appearance:none; background:#fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%236b7280' stroke-width='2' d='M1 1l5 5 5-5'/%3E%3C/svg%3E") no-repeat right 12px center; background-size:12px 8px; }
	@media (max-width:767px) {
		.pv2 .select2-container { display:none !important; }
		.pv2 select.select2, .pv2 select.select2-offscreen { display:block !important; position:static !important; clip:auto !important; width:100% !important; height:auto !important; min-height:44px; margin:0 !important; overflow:visible !important; padding:8px 34px 8px 12px !important; border:1px solid var(--pv2-border) !important; border-radius:8px; font-size:16px; -webkit-appearance:none; -moz-appearance:none; appearance:none; background:#fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%236b7280' stroke-width='2' d='M1 1l5 5 5-5'/%3E%3C/svg%3E") no-repeat right 12px center; background-size:12px 8px; }
	}
	/* por si uniform alcanzo a envolver algun combo */
	.pv2 div.selector, .pv2 div.selector span { width:100% !important; }
	.pv2 div.selector select { width:100% !important; }

	/* Indicadores (KPI) */
	.pv2-kpis { display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:12px; margin-bottom:14px; }
	.pv2-kpi { background:#fff; border:1px solid var(--pv2-border); border-left:4px solid var(--pv2-accent); border-radius:12px; padding:12px 14px; display:flex; align-items:center; gap:12px; box-shadow:0 1px 3px rgba(16,24,40,.05); min-width:0; }
	.pv2-kpi-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex:0 0 38px; font-size:16px; background:var(--pv2-accent-bg); color:var(--pv2-accent); }
	.pv2-kpi-body { min-width:0; flex:1 1 auto; }
	.pv2-kpi-label { font-size:11px; font-weight:600; color:var(--pv2-muted); text-transform:uppercase; letter-spacing:.3px; margin:0; display:block; }
	.pv2 .pv2-kpi-value { display:block; width:100%; border:0; background:transparent; padding:0; margin:0; height:auto; font-size:19px; font-weight:700; color:#1f2933; line-height:1.3; box-shadow:none; text-align:left; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
	.pv2 input.pv2-kpi-value[readonly] { background:transparent; }
	.pv2 .pv2-kpi-value:focus { outline:0; }
	.pv2-kpi.k-info   { border-left-color:var(--pv2-info); }	.pv2-kpi.k-info   .pv2-kpi-icon { background:var(--pv2-info-bg); color:var(--pv2-info); }
	.pv2-kpi.k-warn   { border-left-color:var(--pv2-warn); }	.pv2-kpi.k-warn   .pv2-kpi-icon { background:var(--pv2-warn-bg); color:var(--pv2-warn); }
	.pv2-kpi.k-ok     { border-left-color:var(--pv2-ok); }		.pv2-kpi.k-ok     .pv2-kpi-icon { background:var(--pv2-ok-bg); color:var(--pv2-ok); }
	.pv2-kpi.k-purple { border-left-color:var(--pv2-purple); }	.pv2-kpi.k-purple .pv2-kpi-icon { background:var(--pv2-purple-bg); color:var(--pv2-purple); }
	.pv2-kpi.k-ok .pv2-kpi-value { color:var(--pv2-ok); }
	.pv2-kpi.is-negative { border-left-color:var(--pv2-bad); }
	.pv2-kpi.is-negative .pv2-kpi-value { color:var(--pv2-bad); }
	.pv2-kpi.is-negative .pv2-kpi-icon { background:var(--pv2-bad-bg); color:var(--pv2-bad); }

	/* Detalle */
	.pv2-detail-head { display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap; margin-bottom:10px; }
	.pv2-detail-head .pv2-card-title { margin:0; }
	.pv2-count { font-size:12px; background:var(--pv2-accent-bg); color:var(--pv2-accent); border-radius:20px; padding:1px 8px; font-weight:700; }
	.pv2-table-wrap { overflow-x:auto; border-radius:10px; border:1px solid var(--pv2-border); }
	.pv2-table { width:100%; margin:0; border-collapse:collapse; }
	.pv2-table thead th { font-size:12px; font-weight:700; color:#fff; padding:10px; background:linear-gradient(135deg, var(--pv2-accent) 0%, var(--pv2-accent-2) 100%); border:0; white-space:nowrap; }
	.pv2-table td { padding:8px 10px; border-top:1px solid var(--pv2-border); vertical-align:middle; font-size:14px; }
	.pv2-table tbody tr.row_razon:nth-child(even) td { background:var(--pv2-soft); }
	.pv2-table td.pv2-doc { font-weight:700; }
	.pv2-table .pv2-col-check { width:40px; }
	.pv2-table .pv2-col-share { width:200px; }
	.pv2-table .pv2-col-del { width:48px; text-align:center; }
	.pv2-btn-del { color:var(--pv2-bad); padding:4px 8px; }
	.pv2-btn-del:hover, .pv2-btn-del:focus { color:var(--pv2-bad); background:var(--pv2-bad-bg); }
	.pv2-empty { text-align:center; color:var(--pv2-muted); padding:26px 10px; border:1px dashed var(--pv2-border); border-radius:10px; }
	.pv2-empty i { display:block; margin:0 auto 6px; color:var(--pv2-accent); font-size:22px; }

	/* Pago */
	.pv2-total { background:var(--pv2-accent-bg); border-radius:10px; padding:12px; margin-top:4px; }
	.pv2-total .pv2-label { color:var(--pv2-accent); margin-bottom:2px; }
	.pv2 .pv2-total input { border:0; background:transparent; width:100%; padding:0; font-size:24px; font-weight:700; color:var(--pv2-accent); box-shadow:none; height:auto; text-align:right; }
	.pv2-change { display:flex; justify-content:space-between; align-items:center; padding:8px 0 10px; border-top:1px solid var(--pv2-border); margin-top:4px; font-size:14px; }
	.pv2 .pv2-change input { border:0; background:transparent; text-align:right; font-weight:700; font-size:15px; width:60%; padding:0; box-shadow:none; color:var(--pv2-ok); height:auto; }
	.pv2-change.is-negative input { color:var(--pv2-bad) !important; }
	@media (min-width:992px) { .pv2-sticky { position:sticky; top:12px; } }

	/* Lista */
	.pv2-listbar { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
	.pv2-search { display:flex; gap:6px; flex:1 1 260px; max-width:420px; margin:0; }
	.pv2-search .form-control { flex:1 1 auto; }
	.pv2-search .btn { height:38px; border-radius:8px; }

	/* Barra inferior (solo telefono) */
	.pv2-mbar { display:none; }

	/* Busqueda dentro de la pantalla (telefono) */
	#pv2SearchModal .modal-dialog { width:auto; margin:0; height:100%; }
	#pv2SearchModal .modal-content { border-radius:0; height:100%; border:0; display:flex; flex-direction:column; }
	#pv2SearchModal .modal-header { padding:10px 14px; display:flex; align-items:center; justify-content:space-between; }
	#pv2SearchModal .modal-title { font-size:16px; font-weight:700; }
	#pv2SearchModal .modal-body { padding:0; flex:1 1 auto; position:relative; }
	#pv2SearchModal iframe { border:0; width:100%; height:100%; position:absolute; top:0; left:0; }

	/* ===================== Telefono ===================== */
	@media (max-width:767px) {
		.pv2 { overflow-x:hidden; overflow-x:clip; }
		.pv2.pv2-has-mbar { padding-bottom:84px; }
		.pv2-card { padding:12px; }
		.pv2-header-title { font-size:17px; }
		.pv2-actions { width:100%; }
		.pv2-actions .btn { flex:1 1 auto; }
		.pv2-actions .pv2-hide-xs { display:none; }
		.pv2 .form-control { height:44px; font-size:16px; }
		.pv2 .input-group-btn .btn { height:44px; }
		.pv2-search .btn { height:44px; }
		.pv2-search { max-width:none; }
		.pv2-kpis { grid-template-columns:1fr 1fr; gap:8px; }
		.pv2-kpi { padding:10px; gap:8px; }
		.pv2-kpi-icon { display:none; }
		.pv2 .pv2-kpi-value { font-size:16px; }
		/* a todo el ancho solo si queda solo en su fila (posicion impar) */
		.pv2-kpi.k-wide:nth-child(odd) { grid-column:1 / -1; }

		/* La tabla se convierte en tarjetas */
		.pv2-table-wrap { border:0; overflow:visible; }
		.pv2-table thead { display:none; }
		.pv2-table, .pv2-table tbody { display:block; }
		.pv2-table tr.row_razon { display:block; position:relative; border:1px solid var(--pv2-border); border-radius:10px; padding:10px 12px; margin-bottom:10px; background:#fff; }
		.pv2-table tr.row_razon.has-error { border-color:var(--pv2-bad); background:var(--pv2-bad-bg); }
		.pv2-table tbody tr.row_razon td, .pv2-table tbody tr.row_razon:nth-child(even) td { background:transparent; }
		.pv2-table tr.row_razon td { display:flex; justify-content:space-between; align-items:center; border:0; padding:3px 0; font-size:14px; }
		.pv2-table tr.row_razon td:before { content:attr(data-label); font-size:12px; color:var(--pv2-muted); font-weight:600; padding-right:10px; }
		.pv2-table tr.row_razon td.pv2-col-check { display:none; }
		.pv2-table tr.row_razon td.pv2-doc { font-size:15px; padding-right:44px; }
		.pv2-table tr.row_razon td.pv2-col-share { display:block; width:auto; padding-top:6px; }
		.pv2-table tr.row_razon td.pv2-col-share:before { display:block; margin-bottom:4px; }
		.pv2-table tr.row_razon td.pv2-col-share input { height:44px; font-size:18px; }
		.pv2-table tr.row_razon td.pv2-col-del { position:absolute; top:6px; right:6px; width:auto; padding:0; }
		.pv2-table tr.row_razon td.pv2-col-del:before { display:none; }

		.pv2-mbar { display:flex; align-items:center; gap:10px; position:fixed; left:0; right:0; bottom:0; z-index:1030; background:#fff; border-top:1px solid var(--pv2-border); padding:10px 14px; box-shadow:0 -2px 10px rgba(0,0,0,.06); }
		.pv2-mbar-info { flex:1 1 auto; min-width:0; }
		.pv2-mbar-label { font-size:11px; color:var(--pv2-muted); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
		.pv2-mbar-total { font-size:20px; font-weight:700; margin:0; line-height:1.2; }
		.pv2-mbar .btn { height:46px; padding:0 20px; font-size:15px; border-radius:8px; display:inline-flex; align-items:center; gap:6px; }
		.pv2-mbar-change.is-negative { color:var(--pv2-bad); font-weight:700; }
		/* mientras se escribe (teclado abierto) la barra se oculta para no tapar el campo */
		body.pv2-typing .pv2-mbar { display:none; }
		.pv2 input, .pv2 select, .pv2 textarea { scroll-margin-bottom:110px; }
	}
</style>
