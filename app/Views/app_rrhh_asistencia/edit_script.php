<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    $(document).ready(function(){
        pv2InitDate("txtDate", false);
        afxBindCommon();

        //Regresar a la lista
        $(document).on("click","#btnBack",function(){
            fnWaitOpen();
        });

        //Evento Agregar el Usuario
        $(document).on("click","#btnAcept",function(e){
            e.preventDefault();
            $("#form-new-invoice").attr("method","POST");
            $("#form-new-invoice").attr("action","<?php echo base_url(); ?>/app_rrhh_asistencia/save/edit");

            if(validateForm()){
                fnWaitOpen();
                $("#form-new-invoice").submit();
            }
        });

        $(document).on("click","#btnDelete",function(e){
            e.preventDefault();
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_rrhh_asistencia/delete",
                    data        : {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_rrhh_asistencia/index";
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

        $(document).on("click","#btnClearCustomer",function(){
            $("#txtCustomerID").val("");
            $("#txtCustomerDescription").val("");
        });

        $(document).on("click","#btnSearchCustomer",function(){
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_ENTIDAD/true/empty/false/not_redirect_when_empty";
            pv2OpenSearch(url_request, "Buscar colaborador", "onCompleteCustomer", fnColaboradorElegido, "width=900,height=450");
        });
    });

    function fnColaboradorElegido(objResponse){
        console.info("CALL onCompleteCustomer");

        $("#txtCustomerID").val(objResponse[0][2]);
        $("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][4] + " / " + objResponse[0][5]);
    }

    function validateForm(){
        var result              = true;
        var timerNotification   = 15000;
        var colaboradorID       = $("#txtCustomerID").val();

        //Validar Fecha
        if($("#txtDate").val() == ""){
            fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
            result = false;
        }

        if( !colaboradorID || colaboradorID == "0" || colaboradorID == "13" ){
            fnShowNotification("Seleccione el colaborador","error",timerNotification);
            result = false;
        }

        return result;
    }
</script>
