<!-- ./ page heading -->
<script>

    $(document).ready(function () {
        $('#txtDate').datepicker({format: "yyyy-mm-dd"});
        $("#txtDate").datepicker("update");

        $('#txtNextVisit').datepicker({format: "yyyy-mm-dd"});
        $("#txtNextVisit").datepicker("update");

        //Regresar a la lista
        $(document).on("click", "#btnBack", function () {
            fnWaitOpen();
        });

        //Evento Agregar el Usuario
        $(document).on("click", "#btnAcept", function () {
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
            window.open(url_request, "MsgWindow", "width=900,height=450");
            window.onCompleteCustomer = onCompleteEmisor;
        });

        $(document).on("click", "#btnSearchAsignado", function () {
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?= $objComponentEmployer->componentID; ?>/onCompleteCustomer/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
            window.open(url_request, "MsgWindow", "width=900,height=450");
            window.onCompleteCustomer = onCompleteAsignado;
        });

        $(document).on("click","#btnAddComments",function(){
            fnAgregarFila();
        });

        $(document).on("click","#btnDelete",function(){
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url  		: "<?php echo base_url(); ?>/app_rrhh_task/delete",
                    data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
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

        $(document).on("click","#btnClickArchivo",function(){
            window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponentTask->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
        });

    });

    function onCompleteEmisor(objResponse) {

        console.info("CALL onCompleteCustomer");
        var entityID = objResponse[0][2];
        $("#txtEmisorID").val(entityID);
        $("#txtEmisorDescription").val(entityID + " " + objResponse[0][4] + " / " + objResponse[0][5]);

    }

    function onCompleteAsignado(objResponse) {

        console.info("CALL onCompleteCustomer");
        var entityID = objResponse[0][2];
        $("#txtAsignadoID").val(entityID);
        $("#txtAsignadoDescription").val(entityID + " " + objResponse[0][4] + " / " + objResponse[0][5]);

    }

    function validateForm() {
        var result = true;
        var timerNotification = 15000;
        var txtEmisorID = $("#txtEmisorID").val().trim();
        var txtAsignadoID = $("#txtAsignadoID").val().trim();

        //validar titulo
        if ($("#txtReference4").val().trim() === ""){
            fnShowNotification("Establecer un titulo para la tarea", "error", timerNotification);
            result = false;
        }
        //Validar Fecha
        if ($("#txtDate").val().trim() === "") {
            fnShowNotification("Establecer Fecha al Documento", "error", timerNotification);
            result = false;
        }
        //validar emisor
        if (txtEmisorID === "" || txtEmisorID === "0") {
            fnShowNotification("Seleccione el emisor de la tarea", "error", timerNotification);
            result = false;
        }
        //validar asignado
        if (txtAsignadoID === "" || txtAsignadoID === "0") {
            fnShowNotification("Seleccione a quién asignará la tarea", "error", timerNotification);
            result = false;
        }

        return result;
    }

    function fnEliminarFila(boton) {
        $(boton).closest('tr').remove();
    }

    function fnAgregarFila() {
        var comentarioTarea = $('#txtComentarioTarea');
        var selectComments = $('#txtSelectComments');
        let texto = comentarioTarea.val();

        // Si el campo de texto está vacío, mostrar un mensaje de error
        if (texto === "") {
            $('#errorLabel').show();
            return;
        } else {
            $('#errorLabel').hide();
        }
        let combo1 = selectComments.val();
        console.log(combo1);
        let nuevaFila = "" +
            "<tr> " +
                "<td>" +
                    "<input class='form-control' type='text' name='txtComentarioTareaArray[]' value='" + texto + "'> " +
                "</td>" +
            "<td>" +
                "<select name='txtCommentsIDArray[]' id='comboCommentsId'>" +
                <?php
                if(isset($objListComments)){
                    foreach($objListComments as $ws){
                    ?>
                        "<option value='<?=$ws->catalogItemID?>' " + (<?=$ws->catalogItemID?> == combo1 ? 'selected' : '') + "><?=$ws->name?></option>" +
                    <?php
                    }
                }
                ?>
            "</td>" +
            "<td>" +
                "<button type='button' class='btn btn-flat btn-danger' onclick='fnEliminarFila(this)'>" +
                    "<i class='fas fa-trash'></i>" +
                "</button>" +
            "</td>" +
            "</tr>";

        // Agregar la nueva fila después de la primera fila de entrada
        $('#filaEntrada').after(nuevaFila);

        comentarioTarea.val('');
        selectComments.val(null).trigger('change');

        // Inicializar Select2 en los nuevos select creados
        $('#comboCommentsId').select2();
    }

</script>