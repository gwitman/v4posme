<!--
    Pantalla de compra (app_inventory_inputunpost news / edit).
    1. Colores propios de la pantalla (solo aplican bajo .pinv-inputunpost, no afectan ajustes ni otras pantallas).
    2. Resumen flotante: en escritorio/laptop (>= 992px) el panel de Resumen se fija arriba como franja
       horizontal cuando el usuario se desliza hacia abajo, para que Sub total, Descuento, IVA, ISC y Total
       siempre esten visibles. Se mueve la misma tarjeta (no se clona), asi los campos siguen funcionando.
-->
<style>
    /* ---------- Fondo general con color ---------- */
    .pv2.pinv.pinv-inputunpost {
        background    : linear-gradient(180deg, #e8ebfa 0%, #e6f4f2 100%);
        border-radius : 14px;
        padding-top   : 12px;
        padding-left  : 12px;
        padding-right : 12px;
    }
    .pv2.pinv.pinv-inputunpost .pv2-card { box-shadow:0 2px 10px rgba(57,73,171,.08); }

    /* ---------- Tarjetas con color de identidad ---------- */
    .pv2.pinv.pinv-inputunpost .pinv-card-doc     { background:#f7f8ff; border-top:4px solid var(--pv2-accent); }
    .pv2.pinv.pinv-inputunpost .pinv-card-prov    { background:#f2fbf9; border-top:4px solid var(--pv2-accent-2); }
    .pv2.pinv.pinv-inputunpost .pinv-summary      { background:#faf5fd; border-top:4px solid var(--pv2-purple); }
    .pv2.pinv.pinv-inputunpost .pinv-card-detalle { background:#f6faff; border-top:4px solid var(--pv2-info); }

    .pv2.pinv.pinv-inputunpost .pinv-card-doc     .pv2-card-title { color:var(--pv2-accent); }
    .pv2.pinv.pinv-inputunpost .pinv-card-prov    .pv2-card-title { color:var(--pv2-accent-2); }
    .pv2.pinv.pinv-inputunpost .pinv-summary      .pv2-card-title { color:var(--pv2-purple); }
    .pv2.pinv.pinv-inputunpost .pinv-card-detalle .pv2-card-title { color:var(--pv2-info); }

    .pv2.pinv.pinv-inputunpost .pinv-card-doc     .pv2-ico { background:var(--pv2-accent);   color:#fff; }
    .pv2.pinv.pinv-inputunpost .pinv-card-prov    .pv2-ico { background:var(--pv2-accent-2); color:#fff; }
    .pv2.pinv.pinv-inputunpost .pinv-summary      .pv2-ico { background:var(--pv2-purple);   color:#fff; }
    .pv2.pinv.pinv-inputunpost .pinv-card-detalle .pv2-ico { background:var(--pv2-info);     color:#fff; }

    /* Campos con borde suave del color de la pantalla */
    .pv2.pinv.pinv-inputunpost .form-control:not([readonly]) { border-color:#c5cae9; }
    .pv2.pinv.pinv-inputunpost .form-control:focus           { border-color:var(--pv2-accent); box-shadow:0 0 0 3px rgba(57,73,171,.15); }

    /* ---------- Combo de archivos .csv para importar ---------- */
    /* La regla compartida deja los campos de importacion en 80px x 30px: el combo necesita mas ancho y alto para leer el texto completo */
    .pv2.pinv.pinv-inputunpost .pinv-import select.form-control {
        flex        : 0 0 auto;
        width       : 320px;
        min-width   : 260px;
        max-width   : 100%;
        height      : 34px;
        padding     : 4px 30px 4px 8px; /* derecha reservada para la flecha: el texto no se monta sobre ella */
        line-height : 1.4;
        font-size   : 13px;
        white-space : nowrap;
        overflow    : hidden;
        text-overflow : ellipsis;
        /* Flecha propia del combo: el tema oculta/deforma la flecha nativa */
        -webkit-appearance  : none;
        -moz-appearance     : none;
        appearance          : none;
        background-color    : #fff;
        background-image    : url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' fill='none' stroke='%233949ab' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat   : no-repeat;
        background-position : right 10px center;
        background-size     : 12px 8px;
        cursor              : pointer;
    }
    .pv2.pinv.pinv-inputunpost .pinv-import select.form-control::-ms-expand { display:none; }
    @media (max-width:991px) {
        .pv2.pinv.pinv-inputunpost .pinv-import select.form-control { flex:1 1 auto; width:auto; min-width:0; height:44px; font-size:16px; }
    }

    /* ---------- Detalle: filas alternas y hover con color ---------- */
    .pv2.pinv.pinv-inputunpost #tb_transaction_master_detail tbody tr:nth-child(even) td { background:#eef1fb; }
    .pv2.pinv.pinv-inputunpost #tb_transaction_master_detail tbody tr:hover td          { background:#e0f2ef; }

    /* ---------- Resumen con color por concepto ---------- */
    .pv2.pinv.pinv-inputunpost .pinv-summary-row { border-bottom-color:#eadcf3; }
    .pv2.pinv.pinv-inputunpost .pinv-summary-row label[for="txtSubTotal"] { color:var(--pv2-accent); }
    .pv2.pinv.pinv-inputunpost .pinv-summary-row label[for="txtDiscount"] { color:var(--pv2-warn); }
    .pv2.pinv.pinv-inputunpost .pinv-summary-row label[for="txtIva"]      { color:var(--pv2-info); }
    .pv2.pinv.pinv-inputunpost .pinv-summary-row label[for="txtIsc"]      { color:var(--pv2-purple); }
    .pv2.pinv.pinv-inputunpost .pinv-summary .pv2-total {
        background : linear-gradient(135deg, var(--pv2-accent) 0%, var(--pv2-accent-2) 100%);
        box-shadow : 0 4px 12px rgba(0,137,123,.25);
    }
    .pv2.pinv.pinv-inputunpost .pinv-summary .pv2-total .pv2-label,
    .pv2.pinv.pinv-inputunpost .pinv-summary .pv2-total input { color:#fff; }

    /* Total con el simbolo de la moneda delante del monto */
    .pv2.pinv.pinv-inputunpost .pinv-total-valor { display:flex; align-items:baseline; gap:6px; }
    .pv2.pinv.pinv-inputunpost .pinv-total-valor .pinv-moneda-simbolo { color:#fff; font-size:18px; font-weight:700; opacity:.9; white-space:nowrap; }
    .pv2.pinv.pinv-inputunpost .pinv-total-valor input { flex:1 1 auto; min-width:0; font-variant-numeric:tabular-nums; }
    .pv2.pinv.pinv-inputunpost .pv2-total .pv2-label .pinv-moneda-simbolo { font-weight:700; opacity:.9; }
    .pv2.pinv.pinv-inputunpost .pinv-summary-row .form-control { font-variant-numeric:tabular-nums; }

    /* ---------- Ventanas de importacion CSV (progreso y codigos repetidos) ----------
       jQuery UI las mueve al <body>, fuera de .pv2: por eso usan colores directos y no las variables --pv2-* */
    .ui-widget-overlay.pimp-overlay { background:rgba(17,24,39,.55) !important; opacity:1 !important; backdrop-filter:blur(3px); }
    .ui-dialog.pimp-dialog {
        padding       : 0 !important;
        border        : 0 !important;
        border-radius : 16px !important;
        overflow      : hidden;
        background    : #fff !important;
        box-shadow    : 0 24px 60px rgba(17,24,39,.35) !important;
    }
    .ui-dialog.pimp-dialog .ui-dialog-titlebar { display:none !important; }
    .ui-dialog.pimp-dialog .ui-dialog-content  { padding:0 !important; background:#fff !important; overflow:visible; }
    .pimp { color:#1f2933; font-size:13px; }
    .pimp-head { display:flex; align-items:center; gap:14px; padding:18px 20px; background:linear-gradient(135deg, #3949ab 0%, #00897b 100%); color:#fff; }
    .pimp-head-warn { background:linear-gradient(135deg, #ef6c00 0%, #c62828 100%); }
    .pimp-icon { width:46px; height:46px; border-radius:12px; background:rgba(255,255,255,.18); display:flex; align-items:center; justify-content:center; flex:0 0 46px; }
    .pimp-icon svg { width:22px; height:22px; }
    .pimp-head-text { flex:1 1 auto; min-width:0; }
    .pimp-title { margin:0; font-size:16px; font-weight:700; color:#fff; line-height:1.3; }
    .pimp-sub   { margin:2px 0 0; font-size:12px; color:rgba(255,255,255,.88); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .pimp-pct   { font-size:28px; font-weight:800; line-height:1; font-variant-numeric:tabular-nums; }
    .pimp-body  { padding:18px 20px 20px; }

    /* Icono: subir mientras importa (con pulso), check al terminar */
    @keyframes pimpPulso { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-3px); } }
    .pimp .pimp-ico-subir { animation:pimpPulso 1.2s ease-in-out infinite; }
    .pimp .pimp-ico-ok { display:none; }
    .pimp.pimp-terminado .pimp-ico-subir { display:none; }
    .pimp.pimp-terminado .pimp-ico-ok    { display:block; }
    .pimp.pimp-terminado .pimp-head      { background:linear-gradient(135deg, #2e7d32 0%, #00897b 100%); }

    /* Barra de progreso con rayas animadas */
    @keyframes pimpRayas { from { background-position:0 0; } to { background-position:24px 0; } }
    .pimp-bar { height:10px; border-radius:999px; background:#e8eaf6; overflow:hidden; }
    .pimp-bar-fill {
        height           : 100%;
        border-radius    : 999px;
        background-color : #3949ab;
        background-image : linear-gradient(45deg, rgba(255,255,255,.25) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.25) 50%, rgba(255,255,255,.25) 75%, transparent 75%), linear-gradient(90deg, #3949ab, #00897b);
        background-size  : 24px 24px, 100% 100%;
        animation        : pimpRayas .8s linear infinite;
        transition       : width .35s ease;
    }
    .pimp.pimp-terminado .pimp-bar-fill { animation:none; background-image:none; background-color:#2e7d32; }
    .pimp-status { margin:10px 0 16px; font-size:13px; color:#6b7280; font-variant-numeric:tabular-nums; }

    /* Contadores */
    .pimp-stats { display:grid; grid-template-columns:repeat(3, 1fr); gap:10px; }
    .pimp-stat { border-radius:12px; padding:10px 8px; text-align:center; }
    .pimp-stat span  { display:block; font-size:20px; font-weight:700; line-height:1.2; font-variant-numeric:tabular-nums; }
    .pimp-stat small { display:block; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; opacity:.85; }
    .pimp-stat-ok   { background:#eaf5ea; color:#2e7d32; }
    .pimp-stat-new  { background:#e8eaf6; color:#3949ab; }
    .pimp-stat-skip { background:#fdf3dc; color:#8a5a00; }
    .pimp-note { margin-top:16px; padding:10px 12px; border-radius:10px; background:#e8f1fc; color:#1565c0; font-size:12px; display:flex; align-items:center; gap:8px; }
    .pimp-note svg { width:16px; height:16px; flex:0 0 16px; }

    /* Lista de codigos repetidos */
    .pimp-lista { max-height:240px; overflow:auto; border:1px solid #e3e6ea; border-radius:12px; }
    .pimp-tabla { width:100%; border-collapse:collapse; font-size:12px; }
    .pimp-tabla th { position:sticky; top:0; background:#f6f7f9; text-align:left; padding:8px 10px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#6b7280; }
    .pimp-tabla td { padding:8px 10px; border-top:1px solid #eef0f3; vertical-align:top; }
    .pimp-tabla tbody tr:hover td { background:#fafbff; }
    .pimp-codigo { font-family:Consolas, monospace; font-weight:700; color:#3949ab; white-space:nowrap; }
    .pimp-chip { display:inline-block; padding:1px 8px; margin:1px 2px; border-radius:999px; background:#fdf3dc; color:#8a5a00; font-weight:700; font-size:11px; }
    .pimp-mas { color:#6b7280; font-style:italic; }
    .pimp-opciones { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:14px; }
    .pimp-opcion { border:1px solid #e3e6ea; border-radius:12px; padding:10px 12px; font-size:12px; color:#6b7280; background:#fafbff; }
    .pimp-opcion b { display:block; color:#1f2933; font-size:13px; margin-bottom:2px; }

    /* Botones */
    .ui-dialog.pimp-dialog .ui-dialog-buttonpane { margin:0 !important; padding:12px 20px !important; border-top:1px solid #eef0f3 !important; background:#f9fafb !important; }
    .ui-dialog.pimp-dialog .ui-dialog-buttonpane .ui-dialog-buttonset { float:none !important; display:flex; flex-wrap:wrap; justify-content:flex-end; gap:8px; }
    .ui-dialog.pimp-dialog .ui-dialog-buttonpane button.pimp-btn {
        margin           : 0 !important;
        border-radius    : 10px !important;
        padding          : 9px 16px !important;
        font-size        : 13px !important;
        font-weight      : 600 !important;
        border           : 1px solid #d1d5db !important;
        background       : #fff !important;
        background-image : none !important;
        color            : #374151 !important;
        box-shadow       : none !important;
        text-shadow      : none !important;
        cursor           : pointer;
    }
    .ui-dialog.pimp-dialog .ui-dialog-buttonpane button.pimp-btn .ui-button-text { padding:0 !important; }
    .ui-dialog.pimp-dialog .ui-dialog-buttonpane button.pimp-btn:hover { background:#f3f4f6 !important; }
    .ui-dialog.pimp-dialog .ui-dialog-buttonpane button.pimp-btn-warn { background:#fff7ed !important; border-color:#fdba74 !important; color:#c2410c !important; }
    .ui-dialog.pimp-dialog .ui-dialog-buttonpane button.pimp-btn-primary { background:linear-gradient(135deg, #3949ab 0%, #00897b 100%) !important; border-color:transparent !important; color:#fff !important; }
    @media (max-width:600px) {
        .pimp-opciones { grid-template-columns:1fr; }
        .pimp-pct { font-size:22px; }
        .ui-dialog.pimp-dialog .ui-dialog-buttonpane button.pimp-btn { flex:1 1 100%; height:44px; }
    }

    /* ---------- Resumen flotante (solo escritorio / laptop) ---------- */
    @keyframes pinvResumenEntrar { from { transform:translateY(-100%); } to { transform:translateY(0); } }
    @media (min-width:992px) {
        .pv2.pinv.pinv-inputunpost .pinv-summary.pinv-summary-flotante {
            position      : fixed;
            top           : 0;
            z-index       : 1020;
            margin        : 0;
            display       : flex;
            align-items   : center;
            gap           : 14px;
            padding       : 8px 16px;
            border-radius : 0 0 12px 12px;
            border-top    : 0;
            border-bottom : 4px solid var(--pv2-purple);
            box-shadow    : 0 8px 20px rgba(57,73,171,.25);
            animation     : pinvResumenEntrar .18s ease-out;
        }
        .pv2.pinv.pinv-inputunpost .pinv-summary-flotante .pv2-card-title { margin:0; white-space:nowrap; }
        .pv2.pinv.pinv-inputunpost .pinv-summary-flotante .pinv-summary-row {
            flex           : 1 1 0;
            min-width      : 0;
            flex-direction : column;
            align-items    : stretch;
            gap            : 2px;
            padding        : 0;
            border-bottom  : 0;
        }
        .pv2.pinv.pinv-inputunpost .pinv-summary-flotante .pinv-summary-row label        { font-size:11px; }
        .pv2.pinv.pinv-inputunpost .pinv-summary-flotante .pinv-summary-row .form-control { max-width:none; height:30px; padding:4px 8px; }
        .pv2.pinv.pinv-inputunpost .pinv-summary-flotante .pv2-total       { flex:1.4 1 0; min-width:0; margin:0; padding:6px 12px; }
        .pv2.pinv.pinv-inputunpost .pinv-summary-flotante .pv2-total input { font-size:20px; }
    }
</style>
<script>
    /**
     * Formato visual del resumen: miles con coma y siempre dos decimales (15,500.25).
     * Solo cambia lo que se ve: el servidor quita las comas (helper_StringToNumber).
     * @param {number|string} valor
     * @return {string}
     */
    function fnResumenDosDecimales(valor)
    {
        var numero = parseFloat(String(valor == null ? "" : valor).replace(/,/g, ""));
        if(isNaN(numero))
            numero = 0;
        return numero.toLocaleString("en-US", { minimumFractionDigits:2, maximumFractionDigits:2 });
    }

    //Muestra el simbolo de la moneda seleccionada (data-simbol de cada opcion) en la etiqueta y la caja del Total
    function fnResumenSimboloMoneda()
    {
        var simbolo = $("#txtCurrencyID option:selected").data("simbol");
        $(".pinv-inputunpost .pinv-moneda-simbolo").text(simbolo == null ? "" : String(simbolo));
    }

    $(function(){
        fnResumenSimboloMoneda();
        $(document).on("change", "#txtCurrencyID", fnResumenSimboloMoneda);
    });

    $(function(){
        var $resumen = $(".pinv-inputunpost .pinv-summary");
        if($resumen.length == 0)
            return;

        var $columna    = $resumen.parent();
        var $referencia = $(".pinv-inputunpost .pinv-card-detalle"); //tarjeta de ancho completo: da el ancho y la posicion de la franja
        var $ventana    = $(window);

        //Fija o libera el resumen segun el desplazamiento.
        //La columna conserva la altura del resumen mientras flota, para que la pantalla no salte.
        function fnResumenFlotanteActualizar(){
            var flotando   = $resumen.hasClass("pinv-summary-flotante");
            var alto       = flotando ? $columna.data("altoResumen") : $resumen.outerHeight();
            var limite     = $columna.offset().top + alto;
            var escritorio = window.innerWidth >= 992;

            if(escritorio && $ventana.scrollTop() > limite){
                if(!flotando){
                    $columna.data("altoResumen", alto).css("min-height", alto);
                    $resumen.addClass("pinv-summary-flotante");
                }
                $resumen.css({
                    left  : $referencia.offset().left - $ventana.scrollLeft(),
                    width : $referencia.outerWidth()
                });
            }
            else if(flotando){
                $resumen.removeClass("pinv-summary-flotante").css({ left:"", width:"" });
                $columna.css("min-height", "");
            }
        }

        $ventana.on("scroll resize", fnResumenFlotanteActualizar);
        fnResumenFlotanteActualizar();
    });
</script>
