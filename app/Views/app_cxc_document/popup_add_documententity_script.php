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


            data.txtDocumentEntityID        = $("#txtDocumentEntityID").val();
            data.txtDocumentEntityIDDesc    = $("#txtDocumentEntityID option:selected").text();

            data.txtDocumentEntityType      = $("#txtDocumentEntityType").val();
            data.txtDocumentEntityTypeDesc  = $("#txtDocumentEntityType option:selected").text();

            data.txtDocumentEntityTypeCredit        = $("#txtDocumentEntityTypeCredit").val();
            data.txtDocumentEntityTypeCreditDesc    = $("#txtDocumentEntityTypeCredit option:selected").text();

            data.txtDocumentEntityStatusCredit       = $("#txtDocumentEntityStatusCredit").val();
            data.txtDocumentEntityStatusCreditDesc   = $("#txtDocumentEntityStatusCredit option:selected").text();

            data.txtDocumentEntityTypeGarantia      = $("#txtDocumentEntityTypeGarantia").val();
            data.txtDocumentEntityTypeGarantiaDesc  = $("#txtDocumentEntityTypeGarantia option:selected").text();

            data.txtDocumentEntityTypeRecuperation     = $("#txtDocumentEntityTypeRecuperation").val();
            data.txtDocumentEntityTypeRecuperationDesc = $("#txtDocumentEntityTypeRecuperation option:selected").text();

            //A la pantalla padre se entrega el numero puro; ella decide como mostrarlo
            data.txtRatioDesembolso       = afxMontoPuro($("#txtRatioDesembolso").val());
            data.txtRatioBalance          = afxMontoPuro($("#txtRatioBalance").val());
            data.txtRatioBalanceExpired   = afxMontoPuro($("#txtRatioBalanceExpired").val());
            data.txtRatioShare            = afxMontoPuro($("#txtRatioShare").val());

            window.opener.parentNewLine(data);
            window.close();
        });
    });
</script>