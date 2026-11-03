<style>
	/* ==========================================================
	   Comprobantes contables (app_account_journal news/edit)
	   Raiz: .pv2.pinv.pinv-journal  (no afecta otras pantallas)
	   Se incluye desde news_head.php y edit_head.php
	   ========================================================== */
	.pv2.pinv.pinv-journal .pv2-table-wrap { background:#fff; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail { width:100% !important; margin:0 !important; border:0; border-collapse:collapse; background:#fff; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail thead th { font-size:12px; font-weight:700; color:#fff; padding:10px; background:linear-gradient(135deg, var(--pv2-accent) 0%, var(--pv2-accent-2) 100%); border:0 !important; white-space:nowrap; vertical-align:middle; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail thead th.pinv-journal-num { text-align:right; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody td { padding:7px 10px; border:0; border-top:1px solid var(--pv2-border); vertical-align:middle; font-size:14px; background:#fff; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr:nth-child(even) td { background:var(--pv2-soft); }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr:hover td { background:var(--pv2-accent-bg); }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail td.dataTables_empty { text-align:center; color:var(--pv2-muted); padding:18px 10px; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail.pinv-has-emptybox tr.pinv-row-empty { display:none; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody td input[type="text"] { height:34px; border:1px solid #d5dae0; border-radius:8px; padding:4px 10px; font-size:14px; background:#fff; box-shadow:none; min-width:110px; text-align:right; font-variant-numeric:tabular-nums; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody td input.col-lg-12 { float:none; width:100%; padding-left:10px; padding-right:10px; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody td input[type="text"]:focus { border-color:var(--pv2-accent); box-shadow:0 0 0 3px rgba(57,73,171,.15); outline:0; }
	.pv2.pinv.pinv-journal #tb_journal_entry_detail th.hidden,
	.pv2.pinv.pinv-journal #tb_journal_entry_detail td.hidden,
	.pv2.pinv.pinv-journal #tb_journal_entry_detail .hidden { display:none !important; }
	.pv2.pinv.pinv-journal .pv2-empty { margin-top:10px; }
	/* menu "Mas" del encabezado: solo en telefono */
	.pv2.pinv.pinv-journal .pinv-journal-only-xs { display:none; }

	/* Casillas (Aplicado / Es plantilla) */
	.pv2.pinv.pinv-journal .pinv-journal-check { display:flex; align-items:center; gap:8px; min-height:38px; margin:0; font-weight:600; color:#1f2933; cursor:pointer; }
	.pv2.pinv.pinv-journal .pinv-journal-check div.checker { margin:0; }

	/* Estado del cuadre (debito vs credito) */
	.pv2.pinv.pinv-journal .pinv-journal-balance { font-size:17px; font-weight:700; margin:0; line-height:1.3; }
	.pv2.pinv.pinv-journal .pinv-journal-diff { display:block; font-size:12px; font-weight:600; color:var(--pv2-muted); }
	.pv2.pinv.pinv-journal .pv2-kpi.k-ok .pinv-journal-balance { color:var(--pv2-ok); }
	.pv2.pinv.pinv-journal .pv2-kpi.is-negative .pinv-journal-balance { color:var(--pv2-bad); }

	/* Barra inferior: debito / credito */
	.pv2.pinv.pinv-journal .pinv-journal-mbar { font-size:14px; font-weight:700; margin:0; line-height:1.35; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
	.pv2.pinv.pinv-journal .pinv-journal-mbar .is-bad { color:var(--pv2-bad); }
	.pv2.pinv.pinv-journal .pinv-journal-mbar .is-ok { color:var(--pv2-ok); }

	/* ---------- Telefono: cada linea es una tarjeta ---------- */
	@media (max-width:767px) {
		.pv2.pinv.pinv-journal .pv2-table-wrap { border:0; overflow:visible; border-radius:0; background:transparent; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail thead { display:none !important; }
		.pv2.pinv.pinv-journal .dataTables_wrapper { overflow:visible; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail,
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody { display:block; width:100% !important; background:transparent; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr { display:block; position:relative; border:1px solid var(--pv2-border); border-radius:12px; padding:10px 12px 10px 50px; margin-bottom:10px; background:#fff; box-shadow:0 1px 3px rgba(16,24,40,.05); }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td,
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr:nth-child(even) td,
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr:hover td { background:transparent; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td { display:flex !important; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:4px 10px; width:auto !important; border:0; padding:4px 0; font-size:14px; text-align:right; word-break:break-word; min-height:28px; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td[data-label]:before { content:attr(data-label); flex:0 0 auto; max-width:45%; font-size:12px; color:var(--pv2-muted); font-weight:600; text-align:left; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td[data-label=""]:before { display:none; }
		/* casilla de marcar arriba a la izquierda */
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td:first-child { position:absolute; top:10px; left:12px; padding:0; min-height:0; width:auto !important; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td:first-child:before { display:none; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td:first-child > input[type="checkbox"] { width:22px; height:22px; margin:0; }
		/* la cuenta es el titulo de la tarjeta */
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td:nth-child(5) { display:block !important; text-align:left; font-weight:700; font-size:15px; color:#1f2933; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td:nth-child(5):before { display:none; }
		/* centro de costo vacio: no ocupa espacio */
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td:nth-child(6):empty { display:none !important; }
		/* debito / credito grandes para el dedo (16px evita el zoom de iOS) */
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody td input[type="text"] { height:44px; font-size:16px; flex:1 1 55%; max-width:60%; width:auto; min-width:0; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody td input.col-lg-12 { width:auto; }
		/* fila vacia de DataTables */
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr.pinv-row-empty { padding:0; border-style:dashed; box-shadow:none; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td.dataTables_empty { position:static; display:block !important; text-align:center; padding:16px 10px; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td.dataTables_empty:before { display:none; }
		.pv2.pinv.pinv-journal #tb_journal_entry_detail.pinv-has-emptybox tbody tr.pinv-row-empty { display:none !important; }
		/* .hidden SIEMPRE gana, tambien sobre display:flex !important */
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr td.hidden,
		.pv2.pinv.pinv-journal #tb_journal_entry_detail tbody tr .hidden { display:none !important; }

		.pv2.pinv.pinv-journal .pinv-journal-check { min-height:44px; }
		.pv2.pinv.pinv-journal .pinv-journal-balance { font-size:15px; }
		/* el indicador de cuadre ocupa toda la fila */
		.pv2.pinv.pinv-journal .pv2-kpi.pinv-journal-kpi-balance { grid-column:1 / -1; }
		/* menu "Mas" del encabezado (acciones secundarias agrupadas) */
		.pv2.pinv.pinv-journal .pv2-header .pv2-actions .btn-group.pinv-journal-only-xs { display:flex; flex:1 1 auto; position:relative; }
		.pv2.pinv.pinv-journal .pv2-header .pv2-actions .btn-group > .btn { flex:1 1 auto; }
		.pv2.pinv.pinv-journal .pv2-header .dropdown-menu { left:auto; right:0; border-radius:10px; }
		.pv2.pinv.pinv-journal .pv2-header .dropdown-menu > li > a { padding:12px 16px; }
	}
</style>
