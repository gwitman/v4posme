<style>
	/* ==========================================================
	   app_cxc_simulation v2 - simulador de credito
	   Todo esta bajo .simv2 para no afectar otras pantallas.
	   ========================================================== */
	.simv2 { --simv2-accent:#3949ab; --simv2-accent-2:#00897b; --simv2-accent-bg:#e8eaf6; --simv2-ok:#2e7d32; --simv2-ok-bg:#eaf5ea; --simv2-bad:#c62828; --simv2-bad-bg:#fdecec; --simv2-warn:#8a5a00; --simv2-warn-bg:#fdf3dc; --simv2-info:#1565c0; --simv2-info-bg:#e8f1fc; --simv2-purple:#6a1b9a; --simv2-purple-bg:#f3e8fa; --simv2-border:#e3e6ea; --simv2-muted:#6b7280; --simv2-soft:#f6f7f9; color:#1f2933; }
	.simv2 *, .simv2 *:before, .simv2 *:after { box-sizing:border-box; }

	/* Tarjetas */
	.simv2-card { background:#fff; border:1px solid var(--simv2-border); border-radius:12px; padding:14px 16px; margin-bottom:14px; box-shadow:0 1px 3px rgba(16,24,40,.05); }
	.simv2-card-title { font-size:14px; font-weight:700; margin:0 0 12px; display:flex; align-items:center; gap:8px; color:#1f2933; }
	.simv2-card-title .simv2-ico { width:28px; height:28px; border-radius:8px; background:var(--simv2-accent-bg); color:var(--simv2-accent); display:inline-flex; align-items:center; justify-content:center; font-size:13px; flex:0 0 28px; }

	/* Encabezado */
	.simv2-header { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; background:linear-gradient(135deg, var(--simv2-accent) 0%, var(--simv2-accent-2) 100%); border:0; color:#fff; }
	.simv2-header-info { display:flex; align-items:center; gap:12px; min-width:0; }
	.simv2-header-icon { width:46px; height:46px; border-radius:12px; background:rgba(255,255,255,.18); color:#fff; display:flex; align-items:center; justify-content:center; flex:0 0 46px; font-size:20px; }
	.simv2-header-title { font-size:19px; font-weight:700; margin:0; line-height:1.25; color:#fff; }
	.simv2-header-sub { font-size:12px; color:rgba(255,255,255,.85); margin:0; }
	.simv2-actions { display:flex; flex-wrap:wrap; gap:6px; }
	.simv2-actions .btn { border-radius:8px; }
	.simv2-header .simv2-actions .btn-default { background:rgba(255,255,255,.95); border-color:transparent; color:#1f2933; }
	.simv2-header .simv2-actions .btn-default:hover { background:#fff; }
	.simv2-header .simv2-actions .btn-primary { background:#fff; border-color:#fff; color:var(--simv2-accent); font-weight:700; }

	/* Formularios */
	.simv2 .form-group { margin-bottom:12px; }
	.simv2 .form-horizontal .form-group { margin-left:0; margin-right:0; }
	.simv2 .simv2-label { display:block; font-size:12px; font-weight:600; color:var(--simv2-muted); margin:0 0 4px; text-align:left; }
	.simv2 .simv2-help { display:block; font-size:11px; color:var(--simv2-muted); margin-top:3px; }
	.simv2 .form-control { height:38px; border-radius:8px; font-size:14px; border:1px solid #d5dae0; box-shadow:none; }
	.simv2 .form-control:focus { border-color:var(--simv2-accent); box-shadow:0 0 0 3px rgba(57,73,171,.15); outline:0; }
	.simv2 .form-control[readonly] { background:var(--simv2-soft); }
	.simv2 .input-group .form-control { border-radius:8px 0 0 8px; }
	.simv2 .input-group-btn .btn { height:38px; }
	.simv2 .input-group-btn:last-child .btn { border-radius:0 8px 8px 0; }
	.simv2 .simv2-unit { position:relative; }
	.simv2 .simv2-unit .form-control { padding-right:46px; }
	.simv2 .simv2-unit span { position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:12px; color:var(--simv2-muted); pointer-events:none; }
	.simv2 .simv2-calc { display:flex; justify-content:flex-end; }
	.simv2 .simv2-calc .btn { height:42px; padding:0 22px; border-radius:10px; font-weight:700; background:linear-gradient(135deg, var(--simv2-accent) 0%, var(--simv2-accent-2) 100%); border:0; color:#fff; }
	.simv2 .simv2-calc .btn:hover, .simv2 .simv2-calc .btn:focus { opacity:.92; color:#fff; }

	/* Combos nativos (sin select2) */
	.simv2 select.form-control { -webkit-appearance:none; -moz-appearance:none; appearance:none; background:#fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%236b7280' stroke-width='2' d='M1 1l5 5 5-5'/%3E%3C/svg%3E") no-repeat right 12px center; background-size:12px 8px; padding-right:34px; cursor:pointer; }
	.simv2 select.form-control::-ms-expand { display:none; }
	.simv2 .select2-container { width:100% !important; }
	/* por si uniform alcanzo a envolver algun combo */
	.simv2 div.selector, .simv2 div.selector span { width:100% !important; }
	.simv2 div.selector select { width:100% !important; }

	/* Indicadores (KPI) */
	.simv2-kpis { display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:12px; margin-bottom:14px; }
	.simv2-kpi { background:#fff; border:1px solid var(--simv2-border); border-left:4px solid var(--simv2-accent); border-radius:12px; padding:12px 14px; display:flex; align-items:center; gap:12px; box-shadow:0 1px 3px rgba(16,24,40,.05); min-width:0; }
	.simv2-kpi-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex:0 0 38px; font-size:16px; background:var(--simv2-accent-bg); color:var(--simv2-accent); }
	.simv2-kpi-body { min-width:0; flex:1 1 auto; }
	.simv2-kpi-label { font-size:11px; font-weight:600; color:var(--simv2-muted); text-transform:uppercase; letter-spacing:.3px; margin:0; }
	.simv2 .simv2-kpi-value { display:block; width:100%; border:0; background:transparent; padding:0; margin:0; height:auto; font-size:19px; font-weight:700; color:#1f2933; line-height:1.3; box-shadow:none; text-align:left; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
	.simv2 .simv2-kpi-value:focus { outline:0; }
	.simv2-kpi.k-capital  { border-left-color:var(--simv2-info); }		.simv2-kpi.k-capital  .simv2-kpi-icon { background:var(--simv2-info-bg); color:var(--simv2-info); }
	.simv2-kpi.k-interest { border-left-color:var(--simv2-warn); }		.simv2-kpi.k-interest .simv2-kpi-icon { background:var(--simv2-warn-bg); color:var(--simv2-warn); }
	.simv2-kpi.k-total    { border-left-color:var(--simv2-ok); }		.simv2-kpi.k-total    .simv2-kpi-icon { background:var(--simv2-ok-bg); color:var(--simv2-ok); }
	.simv2-kpi.k-count    { border-left-color:var(--simv2-purple); }	.simv2-kpi.k-count    .simv2-kpi-icon { background:var(--simv2-purple-bg); color:var(--simv2-purple); }
	.simv2-kpi.k-cuota    { border-left-color:var(--simv2-accent-2); }	.simv2-kpi.k-cuota    .simv2-kpi-icon { background:#e0f2f1; color:var(--simv2-accent-2); }

	/* Tabla de amortizacion */
	.simv2-detail-head { display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap; margin-bottom:10px; }
	.simv2-detail-head .simv2-card-title { margin:0; }
	.simv2-count { font-size:12px; background:var(--simv2-accent-bg); color:var(--simv2-accent); border-radius:20px; padding:1px 8px; font-weight:700; }
	.simv2-legend { font-size:12px; color:var(--simv2-muted); display:flex; align-items:center; gap:6px; }
	.simv2-table-wrap { overflow-x:auto; border-radius:10px; border:1px solid var(--simv2-border); }
	.simv2-table { width:100%; margin:0; border-collapse:collapse; }
	.simv2-table thead th { font-size:12px; font-weight:700; color:#fff; padding:10px; background:linear-gradient(135deg, var(--simv2-accent) 0%, var(--simv2-accent-2) 100%); border:0; white-space:nowrap; text-align:right; }
	.simv2-table thead th.simv2-col-num, .simv2-table thead th.simv2-col-date { text-align:left; }
	.simv2-table td { padding:8px 10px; border-top:1px solid var(--simv2-border); vertical-align:middle; font-size:14px; text-align:right; white-space:nowrap; }
	.simv2-table td.simv2-col-num { text-align:left; font-weight:700; }
	.simv2-table td.simv2-col-date { text-align:left; color:var(--simv2-muted); }
	.simv2-table td.simv2-col-cuota { font-weight:700; color:var(--simv2-accent); }
	.simv2-table tbody tr.simv2-row:nth-child(even) td { background:var(--simv2-soft); }
	.simv2-table tbody tr.simv2-row:hover td { background:var(--simv2-accent-bg); }
	.simv2-table tbody tr.simv2-row.is-prorrata td { background:var(--simv2-warn-bg); }
	.simv2-tag { display:inline-block; font-size:11px; font-weight:700; border-radius:20px; padding:1px 8px; background:var(--simv2-warn); color:#fff; margin-left:6px; vertical-align:middle; }
	.simv2-num-prefix { display:none; }
	.simv2-empty { text-align:center; color:var(--simv2-muted); padding:26px 10px; border:1px dashed var(--simv2-border); border-radius:10px; }
	.simv2-empty i { display:block; margin:0 auto 6px; color:var(--simv2-accent); font-size:22px; }

	/* Busqueda dentro de la pantalla (telefono) */
	#simv2SearchModal .modal-dialog { width:auto; margin:0; height:100%; }
	#simv2SearchModal .modal-content { border-radius:0; height:100%; border:0; display:flex; flex-direction:column; }
	#simv2SearchModal .modal-header { padding:10px 14px; display:flex; align-items:center; justify-content:space-between; }
	#simv2SearchModal .modal-title { font-size:16px; font-weight:700; }
	#simv2SearchModal .modal-body { padding:0; flex:1 1 auto; position:relative; }
	#simv2SearchModal iframe { border:0; width:100%; height:100%; position:absolute; top:0; left:0; }

	/* ===================== Telefono ===================== */
	@media (max-width:767px) {
		.simv2 { overflow-x:hidden; overflow-x:clip; }
		.simv2-card { padding:12px; }
		.simv2-header-title { font-size:17px; }
		.simv2-actions { width:100%; }
		.simv2-actions .btn { flex:1 1 auto; }
		.simv2 .form-control { height:44px; font-size:16px; }
		.simv2 .input-group-btn .btn { height:44px; }
		.simv2 .simv2-calc .btn { width:100%; height:46px; font-size:16px; }
		.simv2-kpis { grid-template-columns:1fr 1fr; gap:8px; }
		.simv2-kpi { padding:10px; gap:8px; }
		.simv2-kpi-icon { display:none; }
		.simv2 .simv2-kpi-value { font-size:16px; }
		.simv2-kpi.k-total { grid-column:1 / -1; }

		/* La tabla se convierte en tarjetas */
		.simv2-table-wrap { border:0; overflow:visible; }
		.simv2-table thead { display:none; }
		.simv2-table, .simv2-table tbody { display:block; }
		.simv2-table tr.simv2-row { display:block; border:1px solid var(--simv2-border); border-radius:10px; padding:10px 12px; margin-bottom:10px; background:#fff; }
		.simv2-table tbody tr.simv2-row td, .simv2-table tbody tr.simv2-row:nth-child(even) td, .simv2-table tbody tr.simv2-row:hover td { background:transparent; }
		.simv2-table tbody tr.simv2-row.is-prorrata { background:var(--simv2-warn-bg); border-color:#f0d9a6; }
		.simv2-table tbody tr.simv2-row.is-prorrata td { background:transparent; }
		.simv2-table tr.simv2-row td { display:flex; justify-content:space-between; align-items:center; border:0; padding:3px 0; font-size:14px; white-space:normal; }
		.simv2-table tr.simv2-row td:before { content:attr(data-label); font-size:12px; color:var(--simv2-muted); font-weight:600; padding-right:10px; text-align:left; }
		.simv2-table tr.simv2-row td.simv2-col-num { font-size:15px; border-bottom:1px solid var(--simv2-border); padding-bottom:6px; margin-bottom:4px; }
		.simv2-table tr.simv2-row td.simv2-col-num:before { display:none; }
		.simv2-table tr.simv2-row td.simv2-col-num { justify-content:flex-start; }
		.simv2-num-prefix { display:inline; margin-right:4px; }
		.simv2-table tr.simv2-row td.simv2-col-cuota { font-size:16px; }
	}
</style>
