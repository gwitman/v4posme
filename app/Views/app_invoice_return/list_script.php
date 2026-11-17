<script>
    $(document).ready(function(){
        //Elegir otra vista de la lista
        $(document).on("click", "#btnView", function(e){
            e.preventDefault();
            window.open("<?php echo base_url(); ?>/core_view/chooseview/" + componentID, "MsgWindow", "width=900,height=450");
            window.fn_aceptCallback = fn_aceptCallback;
        });

        //Editar el registro seleccionado (columnas 0,1,2 = companyID, transactionID, transactionMasterID)
        $(document).on("click", "#btnEdit", function(e){
            e.preventDefault();
            if(objRowTableListView == undefined){
                fnShowNotification("Seleccionar el Registro...", "error");
                return;
            }
            var data = objTableListView.fnGetData(objRowTableListView);
            if(data == null) return;

            fnWaitOpen();
            window.location = "<?php echo base_url(); ?>/app_invoice_return/edit/companyID/" + data[0] + "/transactionID/" + data[1] + "/transactionMasterID/" + data[2];
        });

        //Eliminar (solo documentos no aplicados; el servidor valida)
        $(document).on("click", "#btnEliminar", function(e){
            e.preventDefault();
            if(objRowTableListView == undefined){
                fnShowNotification("Seleccionar el Registro...", "error");
                return;
            }
            var data = objTableListView.fnGetData(objRowTableListView);
            if(data == null) return;

            fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : "json",
                    type        : "POST",
                    url         : "<?php echo base_url(); ?>/app_invoice_return/delete",
                    data        : { transactionID : data[1], transactionMasterID : data[2] },
                    success     : function(response){
                        fnWaitClose();
                        if(!response || response.error === true){
                            fnShowNotification(response && response.message ? response.message : "No fue posible eliminar el registro", "error");
                            return;
                        }
                        fnShowNotification("Registro eliminado", "success");
                        objTableListView.fnDeleteRow(objRowTableListView);
                    },
                    error       : function(){
                        fnWaitClose();
                        fnShowNotification("Error 505", "error");
                    }
                });
            });
        });

        //Nuevo documento
        $(document).on("click", "#btnNuevo", function(e){
            e.preventDefault();
            fnWaitOpen();
            window.location = "<?php echo base_url(); ?>/app_invoice_return/add";
        });
    });

    //Vista elegida en core_view/chooseview
    function fn_aceptCallback(data){
        var dataViewID  = data[0];
        window.location = "<?php echo base_url(); ?>/app_invoice_return/index/" + dataViewID;
    }
</script>
