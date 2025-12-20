
<style>

/*************************************************
 FACTURACIÓN - THEME CUSTOM
 Ext JS 6.2.0 | theme-crisp-all
*************************************************/

/* ===== COLORES BASE ===== */
:root{
    --fact-primary:#1565C0;
    --fact-secondary:#00ACC1;
    --fact-success:#2E7D32;
    --fact-warning:#F9A825;
    --fact-danger:#C62828;
    --fact-dark:#263238;
    --fact-light:#F5F7FA;
    --fact-grid:#ECEFF1;
}

/* ===== WINDOW FACTURACIÓN ===== */
.x-window{
    border-radius:10px;
    box-shadow:0 10px 30px rgba(0,0,0,.25);
}

.x-window-header{
    background:linear-gradient(90deg,var(--fact-primary),var(--fact-secondary));
    color:#fff;
    font-weight:600;
    font-size:15px;
}

.x-window-header-text{
    color:#fff;
}

/* ===== PANEL ===== */
.x-panel-body{
    background:var(--fact-light);
}

/* ===== TOOLBAR ===== */
.x-toolbar{
    background:#fff;
    border-bottom:1px solid #ddd;
}

.x-toolbar-text{
    font-weight:600;
}

/* ===== BOTONES ===== */
.x-btn{
    border-radius:6px;
    font-weight:600;
    padding:6px 12px;
}

/* Botón principal (Guardar / Facturar) */
.x-btn-primary,
.x-btn.x-btn-default-toolbar-large{
    background:var(--fact-success);
    border-color:var(--fact-success);
    color:#fff;
}

.x-btn-primary:hover{
    background:#1B5E20;
}



/* ===== INPUTS ===== */
.x-form-field{
    border-radius:5px;
    border:1px solid #B0BEC5;
    padding:6px;
}

.x-form-field:focus{
    border-color:var(--fact-primary);
    box-shadow:0 0 5px rgba(21,101,192,.4);
}

.x-form-item-label{
    font-weight:600;
    color:#37474F;
}

/* ===== GRID FACTURACIÓN ===== */
.x-grid{
    border-radius:8px;
    overflow:hidden;
}

.x-grid-header-ct{
    background:var(--fact-dark);
}

.x-column-header{
    background:var(--fact-dark);
    color:#fff;
    font-weight:600;
    border-right:1px solid #455A64;
}

/* Filas */
.x-grid-item{
    background:#fff;
    border-bottom:1px solid #E0E0E0;
}

.x-grid-item-over{
    background:#E3F2FD !important;
}

/* Fila seleccionada */
.x-grid-item-selected{
    background:#BBDEFB !important;
}

/* ===== GRID TOTALES ===== */
.grid-totales .x-grid-item{
    background:#FFFDE7;
    font-weight:700;
}

/* ===== NUMEROS IMPORTANTES ===== */
.total-factura{
    font-size:22px;
    font-weight:700;
    color:var(--fact-success);
}

.subtotal-factura{
    font-size:16px;
    color:#37474F;
}

/* ===== BADGES ===== */
.badge-pagado{
    background:var(--fact-success);
    color:#fff;
    padding:4px 8px;
    border-radius:10px;
    font-size:12px;
}

.badge-pendiente{
    background:var(--fact-warning);
    color:#000;
    padding:4px 8px;
    border-radius:10px;
    font-size:12px;
}

/* ===== MENSAJES ===== */
.x-message-box .x-window-header{
    background:var(--fact-primary);
}

/* ===== SCROLL ===== */
::-webkit-scrollbar{
    width:8px;
}

::-webkit-scrollbar-thumb{
    background:var(--fact-primary);
    border-radius:4px;
}

/* ===== RESPONSIVE ===== */
@media (max-width:768px){
    .x-window{
        width:100% !important;
        height:100% !important;
    }
    .x-btn{
        font-size:14px;
    }
}

</style>


<style>

/* Texto blanco forzado */
.texto-blanco{
    color:#FFFFFF !important;
}

.texto-blanco,
.texto-blanco .x-btn-inner,
.texto-blanco .x-fa,
.texto-blanco .x-glyph,
.texto-blanco .x-btn-icon-el-default-toolbar-small{
    color:#FFFFFF !important;
}



/* AZUL INTENSO */
.btn-azul{
    background:#1565C0;
    border-color:#1565C0;
    color:#FFFFFF;
}

/* VERDE ÉXITO */
.btn-verde{
    background:#2E7D32;
    border-color:#2E7D32;
    color:#FFFFFF;
}

/* ROJO ALERTA */
.btn-rojo{
    background:#C62828;
    border-color:#C62828;
    color:#FFFFFF;
}

/* AMARILLO ATENCIÓN */
.btn-amarillo{
    background:#F9A825;
    border-color:#F9A825;
    color:#000000;
}

/* NARANJA ACCIÓN */
.btn-naranja{
    background:#EF6C00;
    border-color:#EF6C00;
    color:#FFFFFF;
}

/* MORADO PREMIUM */
.btn-morado{
    background:#6A1B9A;
    border-color:#6A1B9A;
    color:#FFFFFF;
}

/* CELESTE INFO */
.btn-celeste{
    background:#00ACC1;
    border-color:#00ACC1;
    color:#FFFFFF;
}

/* GRIS OSCURO */
.btn-gris{
    background:#455A64;
    border-color:#455A64;
    color:#FFFFFF;
}



/* Nuevo */
.btn-nuevo{
    background:var(--fact-primary);
    border-color:var(--fact-primary);
    color:#fff;
}

/* Imprimir */
.btn-imprimir{
    background:var(--fact-warning);
    border-color:var(--fact-warning);
    color:#000;
}

/* Eliminar */
.btn-eliminar{
    background:var(--fact-danger);
    border-color:var(--fact-danger);
    color:#fff;
}

/* Título blanco SOLO para windows con clase personalizada */
.win-titulo-blanco .x-title-text{
    color:#FFFFFF !important;
    font-weight:600;
}






/* TABPANEL LLAMATIVO */
.tabs-llamativos .x-tab-bar{
    background:#263238;
    padding:6px;
}

/* TABS */
.tabs-llamativos .x-tab{
    background:#455A64;
    border-radius:12px;
    margin-right:6px;
    border:none;
    transition:all .2s ease;
}

/* TEXTO DEL TAB */
.tabs-llamativos .x-tab-inner{
    color:#FFFFFF !important;
    font-weight:600;
}

/* ICONOS */
.tabs-llamativos .x-tab-icon-el{
    color:#FFFFFF;
}

/* TAB HOVER */
.tabs-llamativos .x-tab:hover{
    background:#546E7A;
}

/* TAB ACTIVO */
.tabs-llamativos .x-tab-active{
    background:linear-gradient(135deg,#1565C0,#00ACC1);
    box-shadow:0 3px 8px rgba(0,0,0,.35);
}

/* BORDE INFERIOR DEL CONTENIDO */
.tabs-llamativos .x-tabpanel-body{
    border-radius:10px;
    border:1px solid #CFD8DC;
}


/* PANEL HEADER LLAMATIVO */
.panel-header-llamativo 
.x-panel-header{
    background:linear-gradient(135deg,#1565C0,#00ACC1);
    border:none;
    border-radius:12px 12px 0 0;
    padding:10px 14px;
    box-shadow:0 4px 10px rgba(0,0,0,.35);
}

.panel-header-llamativo 
.x-panel-header .x-title-text{
    color:#FFFFFF !important;
    font-weight:700;
    font-size:15px;
    letter-spacing:.5px;
}

.panel-header-llamativo 
.x-panel-header .x-tool img,
.panel-header-llamativo 
.x-panel-header .x-tool-tool-el{
    filter:brightness(3);
}

.panel-header-llamativo 
.x-panel-header:hover{
    filter:brightness(1.05);
}



/* NÚMERO DE FACTURA - DESTACADO */
.lbl-numero-factura{
    font-size:26px;
    font-weight:800;
    color:#FFFFFF;
    background:linear-gradient(135deg,#2E7D32,#66BB6A);
    padding:6px 14px;
    border-radius:10px;
    letter-spacing:1px;
    box-shadow:0 3px 8px rgba(0,0,0,.35);
}



/* TOTAL DE LA FACTURA */
.lbl-total-factura {
    font-size: 24px;
    font-weight: bold;
    color: #ff6600;
    text-align: center;
    margin: 10px; /* opcional si quieres margen */
}

</style>
