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
    var taskCommentOptions = <?php echo json_encode($commentOptions, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    $(document).ready(function () {
        pv2InitDate("txtDate", true);
        pv2InitDate("txtNextVisit", true);
        afxBindCommon();

        //Regresar a la lista
        $(document).on("click", "#btnBack", function () {
            fnWaitOpen();
        });

        //Evento Agregar el Usuario
        $(document).on("click", "#btnAcept", function (e) {
            e.preventDefault();
            $("#form-new-task").attr("method", "POST");
            $("#form-new-task").attr("action", "<?php echo base_url(); ?>/app_rrhh_task/save/new");
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
    });

    function onCompleteEmisor(objResponse) {
        console.info("CALL onCompleteCustomer");
        $("#txtEmisorID").val(objResponse[0][2]);
        $("#txtEmisorDescription").val(taskEmployeeDescription(objResponse, objResponse[0][3]));
    }

    function onCompleteAsignado(objResponse) {
        console.info("CALL onCompleteCustomer");
        $("#txtAsignadoID").val(objResponse[0][2]);
        $("#txtAsignadoDescription").val(taskEmployeeDescription(objResponse, objResponse[0][3]));
    }

    function fnEliminarFila(boton) {
        $(boton).closest('tr').remove();
    }

    function fnAgregarFila() {
        taskAgregarFila({
            textFieldName     : "txtComentarioTareaArray[]",
            detailIdFieldName : null,
            commentOptions    : taskCommentOptions
        });
    }
</script>
