<script>
    $(document).ready(function() {
        //Combos nativos en telefono y formato de montos (sin el tope de la mascara 000,000.00)
        afxBindCommon();

        $("#btnPopupCancelar").click(function(e) {
            e.preventDefault();
            window.close();
        });
        $("#btnPopupAceptar").click(function(e) {
            e.preventDefault();
            var data = {};

            data.txtEntityRelatedID                 = $("#txtEntityRelatedID").val();
            data.txtDocumentEntityID                = $("#txtDocumentEntityID").val();
            data.txtDocumentEntityType              = $("#txtDocumentEntityType").val();
            data.txtDocumentEntityTypeCredit        = $("#txtDocumentEntityTypeCredit").val();
            data.txtDocumentEntityStatusCredit      = $("#txtDocumentEntityStatusCredit").val();
            data.txtDocumentEntityTypeGarantia      = $("#txtDocumentEntityTypeGarantia").val();
            data.txtDocumentEntityTypeRecuperation  = $("#txtDocumentEntityTypeRecuperation").val();

            //A la pantalla padre se entrega el numero puro; ella decide como mostrarlo
            data.txtRatioDesembolso         = afxMontoPuro($("#txtRatioDesembolso").val());
            data.txtRatioBalance            = afxMontoPuro($("#txtRatioBalance").val());
            data.txtRatioBalanceExpired     = afxMontoPuro($("#txtRatioBalanceExpired").val());
            data.txtRatioShare              = afxMontoPuro($("#txtRatioShare").val());

            window.opener.parentEditLineEntity(data);
            window.close();
        });
    });
</script>