<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    <?php
        $commentOptions = [];
        if(isset($objListComments) && $objListComments){
            foreach($objListComments as $ws){
                $commentOptions[] = ["id" => $ws->catalogItemID, "name" => $ws->name];
            }
        }
    ?>
    var talCommentOptions = <?php echo json_encode($commentOptions, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

    $(document).ready(function(){
        var varParameterCantidadItemPoup = '<?php echo $objParameterCantidadItemPoup; ?>';

        pv2InitDate("txtDateOnly", false);
        if(!$('#txtDateTime').val()){
            $('#txtDateTime').val(moment().format("HH:mm"));
        }
        //La siguiente visita es opcional: vacia significa que no hay visita programada
        talInitDateOpcional("txtNextVisitDate");
        talBindCommon();

        //Combinar fecha + hora del documento en el campo oculto txtDate
        function fnUpdateDate(){
            var fecha = $('#txtDateOnly').val().trim();
            var hora  = $('#txtDateTime').val().trim();
            if(fecha){
                $('#txtDate').val(hora ? (fecha + " " + hora + ":00") : (fecha + " 00:00:00"));
            }else{
                $('#txtDate').val("");
            }
        }
        $(document).on("change","#txtDateOnly, #txtDateTime", fnUpdateDate);
        $('#txtDateOnly').on("changeDate", fnUpdateDate);
        fnUpdateDate();

        //Combinar fecha + hora de la siguiente visita en el campo oculto txtNextVisit
        function fnUpdateNextVisit(){
            var fecha = $('#txtNextVisitDate').val().trim();
            var hora  = $('#txtNextVisitTime').val().trim();
            if(fecha){
                $('#txtNextVisit').val(hora ? (fecha + " " + hora + ":00") : (fecha + " 00:00:00"));
            }else{
                $('#txtNextVisit').val("");
            }
        }
        $(document).on("change","#txtNextVisitDate, #txtNextVisitTime", fnUpdateNextVisit);
        $('#txtNextVisitDate').on("changeDate", fnUpdateNextVisit);

        var urlPrinterInput  = '<?php echo $objParameterUrlPrinterInput; ?>';
        var urlPrinterOutput = '<?php echo $objParameterUrlPrinterOutput; ?>';
        var urlPrinterStiker = '<?php echo $objParameterUrlPrinterSticker; ?>';

        // Validaciones de solo lectura por rol y modo edición
        var roleID              = parseInt($('#txtRoleID').val()) || 0;
        var transactionMasterID = parseInt($('#txtTransactionMasterID').val()) || 0;

        //Regresar a la lista
        $(document).on("click","#btnBack",function(){
            fnWaitOpen();
        });

        $(document).on("change","#txtCurrencyID",function(){
            updatePantalla();
            updateAmount();
        });

        //Evento Agregar el Usuario
        $(document).on("click","#btnAcept",function(e){
            e.preventDefault();
            $( "#form-new-invoice" ).attr("method","POST");
            $( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_purchase_taller/save/edit");

            if(!validateForm()){
                return;
            }

            <?php echo getBehavio($company->type,"app_purchase_taller","scriptBtnAceptBeforeSubmit","") ?>

            fnWaitOpen();
            $( "#form-new-invoice" ).submit();
        });

        //Boton "Cerrar" del modal: solo cerrar, no guardar
        $(document).on("click",".btnCerrarModalSiguienteVisitaTaller",function(e){
            e.preventDefault();
            cerrarModal("ModalSiguienteVisitaTaller");
        });

        $(document).on("click","#btnPrinter",function(){
            fnWaitOpen();
            window.open("<?php echo base_url(); ?>"+"/"+urlPrinterInput+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
            fnWaitClose();
        });

        $(document).on("click","#btnPrinterOutput",function(){
            fnWaitOpen();
            window.open("<?php echo base_url(); ?>"+"/"+urlPrinterOutput+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
            fnWaitClose();
        });

        $(document).on("click","#btnPrinterStiker",function(){
            fnWaitOpen();
            window.open("<?php echo base_url(); ?>"+"/"+urlPrinterStiker+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
            fnWaitClose();
        });

        //Crear una nueva orden de trabajo a partir de la actual (usa la siguiente visita)
        $(document).on("click","#btnCrearOrden",function(e){
            e.preventDefault();

            //Validar que la siguiente visita este establecida (fecha + hora combinadas en el hidden)
            var nextVisit = $("#txtNextVisit").val();
            if(!nextVisit || nextVisit.trim() === ""){
                fnShowNotification("Debe establecer la fecha de la siguiente visita antes de crear la orden","error",15000);
                return;
            }

            //Precargar con los valores actuales y abrir el formulario rapido
            $("#txtCrearOrdenProblema").val($("#txtDetailReference2").val());
            $("#txtCrearOrdenNota").val($("#txtDetailReference1").val());
            mostrarModal("ModalCrearOrden");
        });

        //Boton "Cerrar" del modal de crear orden: solo cerrar
        $(document).on("click",".btnCerrarModalCrearOrden",function(e){
            e.preventDefault();
            cerrarModal("ModalCrearOrden");
        });

        $(document).on("click","#btnDelete",function(){
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_purchase_taller/delete",
                    data        : {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_purchase_taller/index";
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
            //window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
        });

        //Buscar el Cliente
        $(document).on("click","#btnSearchCustomer",function(){
            //Redireccion pantalla
            var url_redirect = "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_taller";
            url_redirect     = encodeURIComponent(url_redirect);

            var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/false/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup+"/";
            pv2OpenSearch(url_request,"Buscar cliente","onCompleteCustomer",onCompleteCustomer,"width=900,height=450");
        });

        //Eliminar Cliente
        $(document).on("click","#btnClearCustomer",function(){
            $("#txtCustomerID").val("");
            $("#txtCustomerDescription").val("");
        });

        //Buscar Colagorador
        $(document).on("click","#btnSearchEmployer",function(){
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
            pv2OpenSearch(url_request,"Buscar colaborador","onCompleteEmployee",onCompleteEmployee,"width=900,height=450");
        });
        //Eliminar Colaborador
        $(document).on("click","#btnClearEmployer",function(){
            $("#txtEmployerID").val("");
            $("#txtEmployerDescription").val("");
        });

        //Buscar Factura
        $(document).on("click","#btnSearchNote",function(){
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteBilling/SELECCIONAR_FACTURA_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
            pv2OpenSearch(url_request,"Buscar factura","onCompleteBilling",onCompleteBilling,"width=900,height=450");
        });
        //Eliminar Factura
        $(document).on("click","#btnClearNote",function(){
            $("#txtNote").val("");
            $("#txtNoteDescription").val("");
        });

        //Buscar Activo Fijo
        $(document).on("click","#btnSearchFixedAsset",function(){
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentFixedAsset->componentID; ?>/onCompleteFixedAsset/SELECCIONAR_FIXEDASSENT/true/empty/false/not_redirect_when_empty";
            pv2OpenSearch(url_request,"Buscar activo fijo","onCompleteFixedAsset",onCompleteFixedAsset,"width=900,height=450");
        });
        //Eliminar Activo Fijo
        $(document).on("click","#btnClearFixedAsset",function(){
            $("#txtFixedAssetID").val("");
            $("#txtFixedAssetDescription").val("");
        });

        $('#errorLabel').hide();
        $(document).on("click","#btnAddComments",function(e){
            e.preventDefault();
            fnAgregarFila();
        });

        <?php
        echo $objReadyPostScript;
        ?>
    });

    function onCompleteBilling(objResponse)
    {
        $("#txtNote").val(objResponse[0][2]);
        $("#txtNoteDescription").val(objResponse[0][2]);
    }

    function onCompleteEmployee(objResponse)
    {
        $("#txtEmployerID").val(objResponse[0][2]);
        $("#txtEmployerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
    }

    function onCompleteCustomer(objResponse)
    {
        if(objResponse != undefined)
        {
            var entityID = objResponse[0][1];
            $("#txtCustomerID").val(objResponse[0][1]);
            $("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
        }
    }

    function onCompleteFixedAsset(objResponse)
    {
        if(objResponse != undefined)
        {
            $("#txtFixedAssetID").val(objResponse[0][0]);
            $("#txtFixedAssetDescription").val(objResponse[0][1] + " " + objResponse[0][2]);
        }
    }

    function validateForm(){
        var result            = true;
        var timerNotification = 15000;

        //Validar Fecha
        if($("#txtDateOnly").val() == ""){
            fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
            result = false;
        }

        if($("#txtCustomerID").val() == ""){
            fnShowNotification("Seleccionar cliente","error",timerNotification);
            result = false;
        }

        if($("#txtEmployerID").val() == ""){
            fnShowNotification("Seleccionar tecnico","error",timerNotification);
            result = false;
        }

        //Los scripts por cliente comparan txtDetailAmount contra '0' como texto
        talMontoAntesDeValidar();
        <?php echo getBehavio($company->type,"app_purchase_taller","scriptValidateForm","") ?>
        talMontoDespuesDeValidar();

        return result;
    }

    //Boton "Aceptar" (Si) del modal: no dejar pasar si la siguiente visita esta vacia o nula
    function fnAceptarModalSiguienteVisitaTaller(){
        var nextVisit = $("#txtNextVisit").val();
        if(!nextVisit || nextVisit.trim() === ""){
            fnShowNotification("Debe establecer la siguiente visita","error",15000);
            return;
        }
        cerrarModal("ModalSiguienteVisitaTaller");
        fnWaitOpen();
        $( "#form-new-invoice" ).submit();
    }

    //Boton "Aceptar" del modal rapido: crear la nueva orden via API
    function fnAceptarModalCrearOrden(){
        var nextVisit = $("#txtNextVisit").val();
        if(!nextVisit || nextVisit.trim() === ""){
            fnShowNotification("Debe establecer la fecha de la siguiente visita antes de crear la orden","error",15000);
            return;
        }

        cerrarModal("ModalCrearOrden");
        fnWaitOpen();
        $.ajax({
            cache    : false,
            dataType : 'json',
            type     : 'POST',
            url      : "<?php echo base_url(); ?>/app_purchase_taller/insertElementApi",
            data     : {
                transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>,
                nextVisit           : $("#txtNextVisit").val(),
                problema            : $("#txtCrearOrdenProblema").val(),
                nota                : $("#txtCrearOrdenNota").val()
            },
            success:function(data){
                fnWaitClose();
                if(data.error){
                    fnShowNotification(data.message,"error",15000);
                }else{
                    var linkOrden = "<a href='"+data.url+"' target='_blank' style='color:#fff;text-decoration:underline'>Ir a la orden</a>";
                    fnShowNotification("La orden de trabajo fue elaborada. "+linkOrden,"success",15000);
                }
            },
            error:function(xhr,data){
                fnWaitClose();
                fnShowNotification("Error 505 al crear la orden","error",15000);
            }
        });
    }

    function refreschChecked(){
        $("[type='checkbox'], [type='radio']").not('.toggle, .select2, .multiselect').uniform();
        pv2Mask($('.txt-numeric'));
    }

    function fnEliminarFila(boton) {
        $(boton).closest('tr').remove();
    }

    function fnAgregarFila() {
        talAgregarFila({
            comboValue     : $('#txtSelectComments').val(),
            conDetalleID   : true,
            commentOptions : talCommentOptions
        });
    }
</script>
