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
            $("#form-new-transferpiece").attr("action", "<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/save/edit");

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

        //Eliminar documento
        $(document).on("click", "#btnDelete", function() {
            fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
                fnWaitOpen();
                $.ajax({
                    cache: false,
                    dataType: 'json',
                    type: 'POST',
                    url: "<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/delete",
                    data: {
                        companyID: <?= $objTM->companyID; ?>,
                        transactionID: <?= $objTM->transactionID; ?>,
                        transactionMasterID: <?= $objTM->transactionMasterID; ?>
                    },
                    success: function(data) {
                        fnWaitClose();
                        if (data.error) {
                            fnShowNotification(data.message, "error");
                        } else {
                            window.location = "<?php echo base_url(); ?>/app_afx_fixedassent_transferpiece/index";
                        }
                    },
                    error: function(xhr, data) {
                        fnWaitClose();
                        fnShowNotification("Error 505", "error");
                    }
                });
            });
        });

        //Inicializar select2 en los selects de accion existentes
        $(".select2-action").select2({
            placeholder: "--Seleccione--",
            allowClear: true
        });

        //Inicializar select2 en los selects de nombre de pieza existentes
        $(".select2-piecename").select2({
            placeholder: "--Seleccione--",
            allowClear: true
        });

        //Agregar Pieza al detalle
        $(document).on("click", "#btnNewDetailTransaction", function() {
            var tmpl = $($("#tmpl_row_piece").html());
            $("#body_tb_transaction_master_detail").append(tmpl);
            //Inicializar select2 en la nueva fila
            tmpl.find(".select2-action").select2({
                placeholder: "--Seleccione--",
                allowClear: true
            });
            tmpl.find(".select2-piecename").select2({
                placeholder: "--Seleccione--",
                allowClear: true
            });
        });

        //Eliminar Piezas marcadas
        $(document).on("click", "#btnDeleteDetailTransaction", function() {
            $(".txtCheckedIsActive:checked").each(function(i, obj) {
                $(this).parents("tr").first().remove();
            });
        });

        //Buscar Colaborador
        $(document).on("click","#btnSearchEmployer",function(){
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/<?= $objParameterCantidadItemPoup; ?>/";
            window.open(url_request,"MsgWindow","width=900,height=450");
            window.onCompleteEmployee = onCompleteEmployee; 
        });
        //Eliminar Colaborador
        $(document).on("click","#btnClearEmployer",function(){
            $("#txtEmployerID").val("0");
            $("#txtEmployerDescription").val("");
        });
        
        <?php echo getBahavioSession($company->type,'tb_afx_transferpiece','scriptJsReady','',$objListCompanyPageSetting) ?>
    });

    function onCompleteEmployee(objResponse)
    {							
            
        $("#txtEmployerID").val(objResponse[0][2]);
        $("#txtEmployerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
            
    }

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
        $("select[name='txtPieceAction[]'], select[name='txtCurrentPieceAction[]'], select[name='txtNewPieceAction[]']").each(function() {
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
        $("select[name='txtPieceName[]'], select[name='txtCurrentPieceName[]'], select[name='txtNewPieceName[]']").each(function() {
            if ($(this).val() === "" || $(this).val() === null) {
                nameValid = false;
            }
        });
        if (!nameValid) {
            fnShowNotification("Seleccione el nombre de cada pieza", "error", timerNotification);
            result = false;
        }

        //Validar activo origen
        if ($("#txtSourceFixedAssetID").val() === "" && $("#txtTargetFixedAssetID").val() === "") {
            fnShowNotification("Seleccione al menos un Activo (Origen o Destino)", "error", timerNotification);
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
