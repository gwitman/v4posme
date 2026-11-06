<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
<?php echo view('app_afx_fixedassent/afx_v2_common'); ?>
<style>
    /* La fila seleccionada debe verse tambien sobre el rayado y en las tarjetas del telefono */
    .ptc .pv2-table tbody tr.row-selected td { background:var(--pv2-accent-bg) !important; font-weight:600; }
    .ptc .pv2-table tbody tr { cursor:pointer; }
    /* fnTableSelectedRow marca event.target.parentNode: el clic debe caer en la celda, no en su span */
    .ptc .pv2-table tbody td > span { pointer-events:none; }
    .ptc .ptc-table-actions { margin-bottom:10px; }
    @media (max-width:767px) {
        .ptc .pv2-table tr.row_razon.row-selected { border-color:var(--pv2-accent); background:var(--pv2-accent-bg); }
    }
</style>

<div id="heading" class="page-header">
    <h1><i class="icon20  i-file"></i> Configurar Documento</h1>
</div>
