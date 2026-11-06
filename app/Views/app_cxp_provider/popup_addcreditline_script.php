<script>
    $(document).ready(function() {
        //Combos nativos en telefono y formato 1,000.00 sin el tope de la mascara anterior
        afxBindCommon();

        $("#btnPopupCancelar").click(function(e) {
            e.preventDefault();
            window.close();
        });
        $("#btnPopupAceptar").click(function(e) {
            e.preventDefault();
            var data = {};
            data.txtProviderCreditLineID    = $("#providerCreditLineID").val();

            data.txtCreditLineID            = $("#txtCreditLineID").val();
            data.txtCreditLineIDDesc        = $("#txtCreditLineID option:selected").text();

            data.txtCurrencyID              = $("#txtCurrencyID").val();
            data.txtCurrencyIDDesc          = $("#txtCurrencyID option:selected").text();

            data.txtStatusID                = $("#txtStatusID").val();
            data.txtStatusIDDesc            = $("#txtStatusID option:selected").text();

            //Los montos viajan a la pantalla principal como numero puro, sin comas
            data.txtNumber                  = $("#txtNumber").val();
            data.txtLimitCredit             = afxMontoPuro($("#txtLimitCredit").val());
            data.txtInteresYear             = afxMontoPuro($("#txtInteresYear").val());

            data.txtPeriodPay               = $("#txtPeriodPay").val();
            data.txtPeriodPayDesc           = $("#txtPeriodPay option:selected").text();

            data.txtTypeAmortization        = $("#txtTypeAmorization").val();
            data.txtTypeAmortizationDesc    = $("#txtTypeAmorization option:selected").text();

            data.txtDayExcluded             = $("#txtDayExcluded").val();
            data.txtDayExcludedDesc         = $("#txtDayExcluded option:selected").text();

            data.txtTerm                    = afxMontoPuro($("#txtTerm").val());
            data.txtNote                    = $("#txtNote").val();

            var tmpFrecuenciaPago           = $("#txtPeriodPay option:selected").data("val") + 0;
            var tmpPlazo                    = $("#txtTerm").val() + 0;


            debugger;
            window.opener.parentNewLine(data);
            window.close();
        });
    });
</script>
