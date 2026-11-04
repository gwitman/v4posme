<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    <?php
        $commentOptions = [];
        if(isset($objListComments)){
            foreach($objListComments as $ws){
                $commentOptions[] = ["id" => $ws->catalogItemID, "name" => $ws->name];
            }
        }
    ?>
    var taskCommentOptions  = <?php echo json_encode($commentOptions, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var taskTeniaComentarios = false;

    $(document).ready(function () {
        pv2InitDate("txtDate", false);
        pv2InitDate("txtNextVisit", false);
        afxBindCommon();

        taskTeniaComentarios = $("input[name='commentsDetailID[]']").length > 0;

        //Regresar a la lista
        $(document).on("click", "#btnBack", function () {
            fnWaitOpen();
        });

        //Evento Agregar el Usuario
        $(document).on("click", "#btnAcept", function (e) {
            e.preventDefault();
            $("#form-new-task").attr("method", "POST");
            $("#form-new-task").attr("action", "<?php echo base_url(); ?>/app_rrhh_task/save/edit");

            if (validateForm()) {
                fnWaitOpen();
                $("#form-new-task").submit();
            }
        });

        $(document).on("click", "#btnClearEmisor", function () {
            $("#txtEmisorID").val("");
            $("#txtEmisorDescription").val("");
        });

        $(document).on("click", "#btnClearAsignado", function () {
            $("#txtAsignadoID").val("");
            $("#txtAsignadoDescription").val("");
        });

        $(document).on("click", "#btnSearchEmisor", function () {
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?= $objComponentEmployer->componentID; ?>/onCompleteCustomer/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
            pv2OpenSearch(url_request, "Buscar emisor", "onCompleteCustomer", onCompleteEmisor, "width=900,height=450");
        });

        $(document).on("click", "#btnSearchAsignado", function () {
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?= $objComponentEmployer->componentID; ?>/onCompleteCustomer/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
            pv2OpenSearch(url_request, "Buscar asignado", "onCompleteCustomer", onCompleteAsignado, "width=900,height=450");
        });

        $(document).on("click","#btnAddComments",function(e){
            e.preventDefault();
            fnAgregarFila();
        });

        $(document).on("click","#btnDelete",function(e){
            e.preventDefault();
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_rrhh_task/delete",
                    data        : {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_rrhh_task/index";
                        }
                    },
                    error:function(xhr,data){
                        console.info("complete delete error");
                        fnWaitClose();
                        fnShowNotification("Error 505","error");
                    }
                });
            });
        });

        $(document).on("click","#btnClickArchivo",function(e){
            e.preventDefault();
            window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponentTask->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
        });
    });

    function onCompleteEmisor(objResponse) {
        console.info("CALL onCompleteCustomer");
        var entityID = objResponse[0][2];
        $("#txtEmisorID").val(entityID);
        $("#txtEmisorDescription").val(taskEmployeeDescription(objResponse, entityID));
    }

    function onCompleteAsignado(objResponse) {
        console.info("CALL onCompleteCustomer");
        var entityID = objResponse[0][2];
        $("#txtAsignadoID").val(entityID);
        $("#txtAsignadoDescription").val(taskEmployeeDescription(objResponse, entityID));
    }

    /* El servidor solo aplica borrados si llega al menos una fila (commentsDetailID):
       si no queda ninguna, se avisa en pantalla en lugar de aparentar que se borraron. */
    function fnRefrescarAvisoComentarios() {
        var sinFilas = $("input[name='commentsDetailID[]']").length == 0;
        $("#avisoSinComentarios").toggle(taskTeniaComentarios && sinFilas);
    }

    function fnEliminarFila(boton) {
        $(boton).closest('tr').remove();
        fnRefrescarAvisoComentarios();
    }

    function fnAgregarFila() {
        taskAgregarFila({
            textFieldName     : "txtComentarioTallerArray[]",
            detailIdFieldName : "commentsDetailID[]",
            commentOptions    : taskCommentOptions
        });
        fnRefrescarAvisoComentarios();
    }
</script>
