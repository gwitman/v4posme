<style>
    /* ==========================================================
       posme v2 - ventana "Imprimir documento" (prefijo .pv2pd)
       Estilo de la pantalla de ABONOS para todos los dialogos de impresion.
       Usada por: app_box_share (modal Bootstrap), app_inventory_inputunpost (jQuery UI),
                  app_invoice_billing (helper_getHtmlOfModalDialog).
       Los dialogos se mueven fuera de la pantalla (jQuery UI los pasa al <body>),
       por eso se usan colores directos y no variables de .abv2 / .pv2.
       ========================================================== */
    .pv2pd { color:#1f2933; font-size:13px; text-align:left; }
    .pv2pd *, .pv2pd *:before, .pv2pd *:after { box-sizing:border-box; }

    /* Encabezado */
    .pv2pd-head      { display:flex; align-items:center; gap:12px; padding:16px 18px; border-bottom:1px solid #eef0f3; background:#fff; }
    .pv2pd-icon      { width:42px; height:42px; border-radius:10px; background:#eaf2fb; color:#2f6fb5; display:flex; align-items:center; justify-content:center; flex:0 0 42px; }
    .pv2pd-icon svg  { width:22px; height:22px; }
    .pv2pd-head-text { flex:1 1 auto; min-width:0; }
    .pv2pd .pv2pd-title { margin:0; font-size:16px; font-weight:700; line-height:1.3; color:#1f2933; }
    .pv2pd .pv2pd-sub   { margin:2px 0 0; font-size:12px; color:#6b7280; }
    .pv2pd-x         { border:0; background:transparent; color:#6b7280; font-size:22px; line-height:1; width:34px; height:34px; border-radius:8px; cursor:pointer; padding:0; flex:0 0 34px; }
    .pv2pd-x:hover   { background:#f6f7f9; color:#1f2933; }

    /* Opciones de formato */
    .pv2pd-body { padding:14px 18px 16px; background:#fff; }
    .pv2pd-body p { margin:0; color:#6b7280; }
    .pv2pd .pv2pd-opt {
        display         : flex;
        align-items     : center;
        gap             : 12px;
        width           : 100%;
        min-height      : 50px;
        margin          : 0 0 8px;
        padding         : 9px 12px;
        border          : 1px solid #e3e6ea;
        border-radius   : 10px;
        background      : #fff;
        color           : #1f2933;
        font-size       : 14px;
        font-weight     : 600;
        text-align      : left;
        text-decoration : none;
        box-shadow      : none;
        cursor          : pointer;
        transition      : border-color .15s ease, background-color .15s ease;
    }
    .pv2pd .pv2pd-opt:last-child { margin-bottom:0; }
    .pv2pd .pv2pd-opt:hover,
    .pv2pd .pv2pd-opt:focus { border-color:#2f6fb5; background:#eaf2fb; color:#1f2933; text-decoration:none; outline:none; }
    .pv2pd-opt-ico      { width:32px; height:32px; border-radius:8px; background:#f6f7f9; color:#2f6fb5; display:flex; align-items:center; justify-content:center; flex:0 0 32px; }
    .pv2pd-opt-ico svg  { width:18px; height:18px; }
    .pv2pd-opt:hover .pv2pd-opt-ico { background:#fff; }
    .pv2pd-opt-text       { flex:1 1 auto; min-width:0; }
    .pv2pd-opt-text small { display:block; font-size:12px; font-weight:400; color:#6b7280; margin-top:1px; }
    .pv2pd-opt-arrow      { color:#9ca3af; font-size:18px; line-height:1; }

    /* Pie con botones */
    .pv2pd-foot { display:flex; flex-wrap:wrap; justify-content:flex-end; gap:8px; padding:12px 18px; border-top:1px solid #eef0f3; background:#f9fafb; }
    .pv2pd .pv2pd-btn { min-height:38px; margin:0; padding:8px 16px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#374151; font-size:13px; font-weight:600; box-shadow:none; text-shadow:none; cursor:pointer; }
    .pv2pd .pv2pd-btn:hover { background:#f3f4f6; }

    /* ---------- Contenedor: modal Bootstrap ---------- */
    .modal.pv2pd-modal .modal-dialog  { width:auto; max-width:440px; margin:60px auto; padding:0 16px; }
    .modal.pv2pd-modal .modal-content { border:0; border-radius:14px; overflow:hidden; box-shadow:0 24px 60px rgba(17,24,39,.35); }

    /* ---------- Contenedor: dialogo jQuery UI ---------- */
    .ui-widget-overlay.pv2pd-overlay { background:rgba(17,24,39,.55) !important; opacity:1 !important; }
    .ui-dialog.pv2pd-dialog { padding:0 !important; border:0 !important; border-radius:14px !important; overflow:hidden; background:#fff !important; box-shadow:0 24px 60px rgba(17,24,39,.35) !important; }
    .ui-dialog.pv2pd-dialog .ui-dialog-titlebar  { display:none !important; }
    .ui-dialog.pv2pd-dialog .ui-dialog-content   { padding:0 !important; background:#fff !important; overflow:visible; }
    .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane { margin:0 !important; padding:12px 18px !important; border-top:1px solid #eef0f3 !important; background:#f9fafb !important; }
    .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane .ui-dialog-buttonset { float:none !important; display:flex; flex-wrap:wrap; justify-content:flex-end; gap:8px; }
    .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane button {
        margin           : 0 !important;
        min-height       : 38px;
        padding          : 8px 16px !important;
        border           : 1px solid #d1d5db !important;
        border-radius    : 8px !important;
        background       : #fff !important;
        background-image : none !important;
        color            : #374151 !important;
        font-size        : 13px !important;
        font-weight      : 600 !important;
        box-shadow       : none !important;
        text-shadow      : none !important;
        cursor           : pointer;
    }
    .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane button .ui-button-text { padding:0 !important; }
    .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane button:hover { background:#f3f4f6 !important; }
    .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane button.pv2pd-btn-primary { background:#2f6fb5 !important; border-color:#2f6fb5 !important; color:#fff !important; }
    .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane button.pv2pd-btn-primary:hover { background:#285f9b !important; }

    /* ---------- Contenedor: helper_getHtmlOfModalDialog (facturacion) ----------
       El helper pinta: #<nombre> > .modal-content-customer1-<nombre> > #divBody<nombre> + botones Cerrar/Aceptar.
       Se apunta por id para tener prioridad sobre el estilo que el helper escribe en linea. */
    #ModalOpcionesImpresion,
    #ModalPrinterDocumentDialogCustom { background-color:rgba(17,24,39,.55) !important; }
    #ModalOpcionesImpresion > div,
    #ModalPrinterDocumentDialogCustom > div {
        width         : calc(100% - 32px) !important;
        max-width     : 440px !important;
        padding       : 0 !important;
        border-radius : 14px !important;
        overflow      : hidden;
        text-align    : left !important;
        background    : #f9fafb !important;
        box-shadow    : 0 24px 60px rgba(17,24,39,.35) !important;
    }
    /* Opciones de impresion: el boton "Aceptar" del helper no aplica (cada formato es su propio boton) */
    #ModalOpcionesImpresion > div > button { display:none !important; }
    #ModalOpcionesImpresion .pv2pd .pv2pd-opt { background:#fff; color:#1f2933; padding:9px 12px; border:1px solid #e3e6ea; border-radius:10px; }
    #ModalOpcionesImpresion .pv2pd .pv2pd-opt:hover,
    #ModalOpcionesImpresion .pv2pd .pv2pd-opt:focus { background:#eaf2fb; color:#1f2933; }
    #ModalOpcionesImpresion .pv2pd .pv2pd-btn { background:#fff; color:#374151; border:1px solid #d1d5db; border-radius:8px; padding:8px 16px; }
    #ModalOpcionesImpresion .pv2pd .pv2pd-btn:hover { background:#f3f4f6; }
    /* Confirmar impresion: se usan los botones Cerrar / Aceptar del helper como pie */
    #ModalPrinterDocumentDialogCustom > div { display:flex; flex-wrap:wrap; justify-content:flex-end; gap:8px; padding:0 18px 12px !important; }
    #ModalPrinterDocumentDialogCustom > div > div { flex:0 0 calc(100% + 36px); margin:0 -18px 4px; border-bottom:1px solid #eef0f3; background:#fff; }
    #ModalPrinterDocumentDialogCustom .pv2pd-head { border-bottom:0; }
    #ModalPrinterDocumentDialogCustom > div > button { min-height:38px; margin:0; padding:8px 16px !important; border:1px solid #d1d5db !important; border-radius:8px !important; background:#fff !important; color:#374151 !important; font-size:13px; font-weight:600; }
    #ModalPrinterDocumentDialogCustom > div > button:hover { background:#f3f4f6 !important; }
    #ModalPrinterDocumentDialogCustom > div > button.btnAceptarModalPrinterDocumentDialogCustom { background:#2f6fb5 !important; border-color:#2f6fb5 !important; color:#fff !important; }
    #ModalPrinterDocumentDialogCustom > div > button.btnAceptarModalPrinterDocumentDialogCustom:hover { background:#285f9b !important; }

    /* Telefono: botones a todo el ancho y comodos al tacto */
    @media (max-width:600px) {
        .modal.pv2pd-modal .modal-dialog { margin:24px auto; }
        .pv2pd .pv2pd-opt { min-height:54px; }
        .pv2pd .pv2pd-btn,
        .ui-dialog.pv2pd-dialog .ui-dialog-buttonpane button,
        #ModalPrinterDocumentDialogCustom > div > button { flex:1 1 100%; min-height:44px; }
    }
</style>
