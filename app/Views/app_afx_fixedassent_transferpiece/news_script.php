<script>
    $(document).ready(function() {

        $('#txtDate').datepicker({
            format: "yyyy-mm-dd"
        });
        $('#txtDate').val();
        $("#txtDate").datepicker("update");

        //Regresar a la lista
        $(document).on("click", "#btnBack", function() {
            fnWaitOpen();
        });

        //Evento Guardar
        $(document).on("click", "#btnAcept", function() {
            $("#form-new-transferpiece").attr("method", "POST");
            $("#form-new-transferpiece").attr("action", "<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/save/new");

            if (validateForm()) {
                fnWaitOpen();
                $("#form-new-transferpiece").submit();
            }
        });

        //Buscar Activo Origen
        $(document).on("click", "#btnSearchSourceAsset", function() {
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentFixedAsset->componentID; ?>/onCompleteSourceAsset/SELECCIONAR_FIXEDASSENT/true/empty/false/not_redirect_when_empty";
            window.open(url_request, "MsgWindow", "width=900,height=450");
            window.onCompleteSourceAsset = onCompleteSourceAsset;
        });

        //Limpiar Activo Origen
        $(document).on("click", "#btnClearSourceAsset", function() {
            $("#txtSourceFixedAssetID").val("");
            $("#txtSourceFixedAssetDescription").val("");
        });

        //Buscar Activo Destino
        $(document).on("click", "#btnSearchTargetAsset", function() {
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentFixedAsset->componentID; ?>/onCompleteTargetAsset/SELECCIONAR_FIXEDASSENT/true/empty/false/not_redirect_when_empty";
            window.open(url_request, "MsgWindow", "width=900,height=450");
            window.onCompleteTargetAsset = onCompleteTargetAsset;
        });

        //Limpiar Activo Destino
        $(document).on("click", "#btnClearTargetAsset", function() {
            $("#txtTargetFixedAssetID").val("");
            $("#txtTargetFixedAssetDescription").val("");
        });

        //Agregar Pieza al detalle
        $(document).on("click", "#btnNewDetailTransaction", function() {
            var tmpl = $($("#tmpl_row_piece").html());
            $("#body_tb_transaction_master_detail").append(tmpl);
        });

        //Eliminar Piezas marcadas
        $(document).on("click", "#btnDeleteDetailTransaction", function() {
            $(".txtCheckedIsActive:checked").each(function(i, obj) {
                $(this).parents("tr").first().remove();
            });
        });
    });

    function validateForm() {
        var result = true;
        var timerNotification = 15000;

        var date = new Date($("#txtDate").val());
        if (isNaN(date.valueOf())) {
            fnShowNotification("Ingrese la Fecha", "error", timerNotification);
            result = false;
        }

        if ($("#txtStatusID").val() === "" || $("#txtStatusID").val() === null) {
            fnShowNotification("Seleccione el Estado", "error", timerNotification);
            result = false;
        }

        //Validar que exista al menos una pieza
        var tableLength = $("#body_tb_transaction_master_detail").find("tr").length;
        if (tableLength < 1) {
            fnShowNotification("Agregue al menos una pieza", "error", timerNotification);
            result = false;
        }

        //Validar que cada pieza tenga una accion seleccionada
        var actionValid = true;
        $("select[name='txtPieceAction[]']").each(function() {
            if ($(this).val() === "") {
                actionValid = false;
            }
        });
        if (!actionValid) {
            fnShowNotification("Seleccione una accion para cada pieza", "error", timerNotification);
            result = false;
        }

        //Validar que cada pieza tenga nombre
        var nameValid = true;
        $("input[name='txtPieceName[]']").each(function() {
            if ($(this).val().trim() === "") {
                nameValid = false;
            }
        });
        if (!nameValid) {
            fnShowNotification("Ingrese el nombre de cada pieza", "error", timerNotification);
            result = false;
        }

        //Validar activo origen si hay piezas con accion baja o transferencia
        var needSource = false;
        $("select[name='txtPieceAction[]']").each(function() {
            if ($(this).val() === "baja" || $(this).val() === "transferencia") {
                needSource = true;
            }
        });
        if (needSource && $("#txtSourceFixedAssetID").val() === "") {
            fnShowNotification("Seleccione el Activo Origen para acciones de baja o transferencia", "error", timerNotification);
            result = false;
        }

        //Validar activo destino si hay piezas con accion nueva o transferencia
        var needTarget = false;
        $("select[name='txtPieceAction[]']").each(function() {
            if ($(this).val() === "nueva" || $(this).val() === "transferencia") {
                needTarget = true;
            }
        });
        if (needTarget && $("#txtTargetFixedAssetID").val() === "") {
            fnShowNotification("Seleccione el Activo Destino para acciones de nueva o transferencia", "error", timerNotification);
            result = false;
        }

        return result;
    }

    function onCompleteSourceAsset(objResponse) {
        console.info("CALL onCompleteSourceAsset");
        $("#txtSourceFixedAssetID").val(objResponse[0][0]);
        $("#txtSourceFixedAssetDescription").val(objResponse[0][1] + " | " + objResponse[0][2]);
    }

    function onCompleteTargetAsset(objResponse) {
        console.info("CALL onCompleteTargetAsset");
        $("#txtTargetFixedAssetID").val(objResponse[0][0]);
        $("#txtTargetFixedAssetDescription").val(objResponse[0][1] + " | " + objResponse[0][2]);
    }
</script>
