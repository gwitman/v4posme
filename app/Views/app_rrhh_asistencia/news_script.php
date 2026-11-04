<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    $(document).ready(function(){
        pv2InitDate("txtDate", true);
        afxBindCommon();

        //Lector de huella: solo en PC
        if(!pv2IsMobile()){
            $("#boxHuella").show();
        }

        //Regresar a la lista
        $(document).on("click","#btnBack",function(){
            fnWaitOpen();
        });

        //Evento Agregar el Usuario
        $(document).on("click","#btnAcept",function(e){
            e.preventDefault();
            fnEnviarFormulario();
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

    //En telefono no se activa el sensor ni se escucha la lectura
    if(!pv2IsMobile()){
        fnActiveSensorRead();
        fnHuellaLeida();
    }

    //Evento que verifica si ya se lello la huella
    function fnHuellaLeida()
    {
        if (typeof (EventSource) !== 'undefined')
        {
            var source          = new EventSource( '<?php echo base_url(); ?>' + '/app_api_fingerprint/web_ssejs');
            source.onmessage    = function (event)
            {
                const data = JSON.parse(event.data);

                if(data != null)
                {
                    if (data.image !== null) {

                        if (data.user_id != null)
                        {
                            //jQuery("#" + localStorage.getItem("srnPc")).attr("src", "data:image/png;base64," + data.image);
                            $("#txtCustomerID").val(data.user_id);
                            $("#txtCustomerDescription").val(data.name);

                            $.ajax({
                                cache       : false,
                                dataType    : 'json',
                                type        : 'POST',
                                url         : "<?php echo base_url(); ?>/app_invoice_api/getLineByCustomer",
                                data        : {entityID : $("#txtCustomerID").val()  },
                                success     : fnCompleteGetCustomerCreditLine,
                                error:function(xhr,data){
                                    console.info("complete data error");
                                    fnShowNotification("Error 505","error");
                                }
                            });
                        }
                        else
                        {
                            console.info("complete data error");
                            fnShowNotification("El usuairo no existe","error");
                        }
                    }
                }
            };
        }
        else
        {
            console.info("complete data error");
            fnShowNotification("Error 505","error");
        }
    }

    //Preparar el lector
    function fnActiveSensorRead()
    {
        $.ajax({
            cache       : false,
            dataType    : 'json',
            type        : 'POST',
            url         : "<?php echo base_url(); ?>/app_api_fingerprint/web_active_sensor_read",
            data        : { customerID : 0  },
            success     : function()
            {
            },
            error:function(xhr,data)
            {
                console.info("complete data error");
                fnShowNotification("Error 505","error");
            }
        });
    }

    //Elegir el colaborador guarda de inmediato (la pantalla lo avisa)
    function fnColaboradorElegido(objResponse){
        console.info("CALL onCompleteCustomer");

        $("#txtCustomerID").val(objResponse[0][2]);
        $("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][4] + " / " + objResponse[0][5]);

        fnEnviarFormulario();
    }

    function fnEnviarFormulario(){
        $("#form-new-invoice").attr("method","POST");
        $("#form-new-invoice").attr("action","<?php echo base_url(); ?>/app_rrhh_asistencia/save/new");

        if(validateForm())
        {
            fnWaitOpen();
            $("#form-new-invoice").submit();
        }
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
