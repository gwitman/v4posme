<style>
    /* ==========================================================
       Orden de produccion - complementos del estandar pv2 (prefijo prod)
       Usada por: news y edit de app_inventory_production.
       Requiere: posme_v2_style, posme_v2_common y afx_v2_common.
       ========================================================== */

    /* Barra lateral: el ancho lo controla la clase, nunca un valor fijo por JS.
       z-index por encima de .pv2-mbar (1030) y por debajo de .modal (1040), para que
       la busqueda interna del telefono y las confirmaciones queden encima de la barra. */
    .pv2 .sidebar { height:100%; width:0; position:fixed; top:0; right:0; background-color:#fff; overflow-x:hidden; overflow-y:auto; transition:width .1s; padding-top:60px; z-index:1035; }
    .pv2 .sidebar.prod-sidebar-open { width:500px; max-width:100%; box-shadow:0 4px 8px rgba(0,0,0,.2); }
    .pv2 .sidebar-content { padding:20px; }

    .pv2 .txtOutputCheckedIsActive { width:20px; height:20px; margin:0; }

    @media (min-width:768px) {
        .pv2-table td > .form-control { min-width:90px; }
    }

    @media (max-width:767px) {
        .pv2-table tr.row_razon td.td-checkbox .txtOutputCheckedIsActive { width:26px; height:26px; }
    }
</style>
<script>
    //Monto calculado listo para mostrar: sin ruido de punto flotante y con formato 1,000.00
    function prodCalcMonto(numero) {
        return afxFmtMonto(afxRedondear(numero));
    }

    //Contador y aviso de vacio de una tabla de detalle
    function prodRefrescarDetalle(bodySelector, countSelector, emptySelector, wrapSelector) {
        var total = $(bodySelector).find("tr").length;
        $(countSelector).text(total);
        $(emptySelector).toggle(total == 0);
        $(wrapSelector).toggle(total > 0);
    }

    //Observa los cambios de una tabla sin tocar los eventos que agregan o quitan filas
    function prodObservarDetalle(bodySelector, countSelector, emptySelector, wrapSelector) {
        var cuerpo = $(bodySelector)[0];
        var refrescar = function() {
            prodRefrescarDetalle(bodySelector, countSelector, emptySelector, wrapSelector);
        };

        refrescar();
        if (cuerpo && window.MutationObserver) {
            new MutationObserver(refrescar).observe(cuerpo, { childList: true });
        }
    }

    //Enlaza lo comun de news y edit. La barra movil ya la enlaza afxBindCommon
    function prodBindCommon() {
        //Los botones son enlaces con href="#": evita que la pagina salte al inicio
        $(document).on("click", ".pv2-actions a[href='#']", function(e) {
            e.preventDefault();
        });

        prodObservarDetalle("#body_tb_transaction_master_detail_item_input", "#prodInputCount", "#prodInputEmpty", "#div_transaction_master_detail_item_input");
        prodObservarDetalle("#body_tb_transaction_master_detail_item_output", "#prodOutputCount", "#prodOutputEmpty", "#div_transaction_master_detail_item_output");
    }
</script>
