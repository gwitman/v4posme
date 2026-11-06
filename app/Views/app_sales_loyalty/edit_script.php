<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    $(document).ready(function() {
        var valueTemp = 0;
        pv2InitDate("txtDate", false);
        afxBindCommon();

        //Regresar a la lista
        $(document).on("click", "#btnBack", function() {
            fnWaitOpen();
        });

        $(document).on("click", "#btnAcept", function() {
            $("#form-new-notecredit").attr("method", "POST");
            $("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_sales_loyalty/save/edit");

            if (validateForm()) {
                fnWaitOpen();
                afxLimpiarMontos("#form-new-notecredit");
                $("#form-new-notecredit").submit();
            }
        });

        $(document).on("click", "#btnDelete", function() {
            fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
                fnWaitOpen();
                $.ajax({
                    cache: false,
                    dataType: 'json',
                    type: 'POST',
                    url: "<?= base_url(); ?>/app_sales_loyalty/delete",
                    data: {
                        companyID: <?= $objTM->companyID; ?>,
                        transactionID: <?= $objTM->transactionID; ?>,
                        transactionMasterID: <?= $objTM->transactionMasterID; ?>
                    },
                    success: function(data) {
                        console.info("complete delete success");
                        fnWaitClose();
                        if (data.error) {
                            fnShowNotification(data.message, "error");
                        } else {
                            window.location = "<?= base_url(); ?>/app_sales_loyalty/index";
                        }
                    },
                    error: function(xhr, data) {
                        console.info("complete delete error");
                        fnWaitClose();
                        fnShowNotification("Error 505", "error");
                    }
                });
            });
        });

        $(document).on("click", "#btnPrinter", function() {
            fnWaitOpen();
            window.open("<?= base_url(); ?>" + "/" + "/app_sales_loyalty/viewPrinterFormatoA4" + "/companyID/<?php echo $objTM->companyID; ?>/transactionID/<?php echo $objTM->transactionID; ?>/transactionMasterID/<?php echo $objTM->transactionMasterID; ?>", '_blank');
            fnWaitClose();
        });

        $(document).on("click", "#btnClickArchivo", function() {
            window.open("<?= base_url()."/core_elfinder/index/componentID/".$objComponentSalesLoyalty->componentID."/componentItemID/".$objTM->transactionMasterID; ?>","blanck");
        });

        $(document).on("focus", "#txtTax1", function() {
            valueTemp = $(this).val();
        });

        //Los puntos no pueden superar la meta. Se compara como numero porque los campos llevan comas
        $(document).on("change", "#txtTax1", function() {
            var puntos = pv2Num($(this).val());
            var meta   = pv2Num($("#txtAmount").val());

            if (puntos > meta) {
                $(this).val(valueTemp);
            }
        });
    });

    function validateForm() {
        var result            = true;
        var timerNotification = 15000;

        var date = new Date($("#txtDate").val());

        if (isNaN(date.valueOf())) {
            fnShowNotification("Ingrese la Fecha", "error", timerNotification);
            result = false;
        }

        //Validar Monto
        if (pv2Num($("#txtAmount").val()) == 0) {
            fnShowNotification("La meta no puede estar vacia", "error", timerNotification);
            result = false;
        }

        if ($("#txtNumberPhone").val() === "") {
            fnShowNotification("Escribir numero de telefono", "error", timerNotification);
            result = false;
        }

        return result;
    }

    function refreschChecked() {
        $("[type='checkbox'], [type='radio']").not('.toggle').uniform();
    }
</script>
