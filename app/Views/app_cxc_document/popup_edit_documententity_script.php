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

            data.txtEntityRelatedID                 = $("#txtEntityRelatedID").val();
            data.txtDocumentEntityID                = $("#txtDocumentEntityID").val();
            data.txtDocumentEntityType              = $("#txtDocumentEntityType").val();
            data.txtDocumentEntityTypeCredit        = $("#txtDocumentEntityTypeCredit").val();
            data.txtDocumentEntityStatusCredit      = $("#txtDocumentEntityStatusCredit").val();
            data.txtDocumentEntityTypeGarantia      = $("#txtDocumentEntityTypeGarantia").val();
            data.txtDocumentEntityTypeRecuperation  = $("#txtDocumentEntityTypeRecuperation").val();

            data.txtRatioDesembolso         = $("#txtRatioDesembolso").val();
            data.txtRatioBalance            = $("#txtRatioBalance").val();
            data.txtRatioBalanceExpired     = $("#txtRatioBalanceExpired").val();
            data.txtRatioShare              = $("#txtRatioShare").val();

            window.opener.parentEditLineEntity(data);
            window.close();
        });
    });
</script>