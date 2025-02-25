<script>
    $(document).ready(function() {
       
        $(document).on("click", "#btnView", function() {
            window.open("<?php echo base_url(); ?>/core_view/chooseview/" + componentID, "MsgWindow", "width=900,height=450");
            window.fn_aceptCallback = fn_aceptCallback;
        });
        
        $(document).on("click", "#btnEdit", function() {

            if (objRowTableListView != undefined) {
                fnWaitOpen();
                var data = objTableListView.fnGetData(objRowTableListView);
                window.location = "<?php echo base_url(); ?>/app_bank_cheque/edit/chequeID/" + data[0];
            } else {
                fnShowNotification("Seleccionar el Registro...", "error");
            }
        });

        $(document).on("click", "#btnSearchCheque", function() {
            fnWaitOpen();
            $.ajax({
                cache: false,
                dataType: 'json',
                type: 'POST',
                url: "<?php echo base_url(); ?>/app_bank_cheque/searchBankCheque",
                data: {
                    chequeNumber: $("#txtSearchCheque").val()
                },
                success: function(data) {
                    console.info("complete delete success");
                    fnWaitClose();
                    if (data.error) {
                        fnShowNotification(data.message, "error");
                    } else {
                        window.location = "<?php echo base_url(); ?>/app_bank_cheque/edit/chequeID/" + data.chequeID;
                    }
                },
                error: function(xhr, data) {
                    console.info("complete delete error");
                    fnWaitClose();
                    fnShowNotification("Error 505", "error");
                }
            });
        });

        $(document).on("click", "#btnEliminar", function() {

            if (objRowTableListView != undefined) {
                var data = objTableListView.fnGetData(objRowTableListView);
                fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
                    fnWaitOpen();
                    $.ajax({
                        cache: false,
                        dataType: 'json',
                        type: 'POST',
                        url: "<?php echo base_url(); ?>/app_bank_cheque/delete",
                        data: {
                            chequeID: data[0]
                        },
                        success: function(data) {
                            console.info("complete delete success");
                            fnWaitClose();
                            if (data.error) {
                                fnShowNotification(data.message, "error");
                            } else {
                                fnShowNotification("success", "success");
                                objTableListView.fnDeleteRow(objRowTableListView);
                            }
                        },
                        error: function(xhr, data) {
                            console.info("complete delete error");
                            fnWaitClose();
                            fnShowNotification("Error 505", "error");
                        }
                    });
                });
            } else {
                fnShowNotification("Seleccionar el Registro...", "error");
            }
        });

        $(document).on("click", "#btnNuevo", function() {
            fnWaitOpen();
            window.location = "<?php echo base_url(); ?>/app_bank_cheque/add";
        });
    });

    function fn_aceptCallback(data) {
        var dataViewID = data[0];
        window.location = "../../app_bank_cheque/index/" + dataViewID;
    }
</script>