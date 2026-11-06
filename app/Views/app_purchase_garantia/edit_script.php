<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    $(document).ready(function(){
        var varParameterCantidadItemPoup = '<?php echo $objParameterCantidadItemPoup; ?>';

        pv2InitDate("txtDate", false);
        talBindCommon();

        var urlPrinter       = '<?php echo $objParameterUrlPrinter; ?>';
        var urlPrinterOutput = '<?php echo $objParameterUrlPrinterOutput; ?>';
        var urlPrinterStiker = '<?php echo $objParameterUrlPrinterSticker; ?>';

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
            $( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_purchase_garantia/save/edit");

            if(validateForm()){
                fnWaitOpen();
                $( "#form-new-invoice" ).submit();
            }
        });

        $(document).on("click","#btnPrinter",function(){
            fnWaitOpen();
            window.open("<?php echo base_url(); ?>"+"/"+urlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
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

        $(document).on("click","#btnDelete",function(){
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_purchase_garantia/delete",
                    data        : {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_purchase_garantia/index";
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
            window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
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
            pv2OpenSearch(url_request,"Buscar tecnico","onCompleteEmployee",onCompleteEmployee,"width=900,height=450");
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

    function validateForm(){
        var result            = true;
        var timerNotification = 15000;

        //Validar Fecha
        if($("#txtDate").val() == ""){
            fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
            result = false;
        }

        if($("#txtNote").val() == ""){
            fnShowNotification("Seleccionar factura","error",timerNotification);
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

        return result;
    }

    function refreschChecked(){
        $("[type='checkbox'], [type='radio']").not('.toggle, .select2, .multiselect').uniform();
        pv2Mask($('.txt-numeric'));
    }
</script>
