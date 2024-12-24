<script>
    $(document).ready(function() {
        $('.txt-numeric').mask('000,000.00', {
            reverse: true
        });
        $("#btnPopupCancelar").click(function() {
            window.close();
        });
        $("#btnPopupAceptar").click(function() {
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

            data.txtRatioDesembolso       = $("#txtRatioDesembolso").val();
            data.txtRatioBalance          = $("#txtRatioBalance").val();
            data.txtRatioBalanceExpired   = $("#txtRatioBalanceExpired").val();
            data.txtRatioShare            = $("#txtRatioShare").val();

            window.opener.parentNewLine(data);
            window.close();
        });
    });
</script>