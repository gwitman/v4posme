<!-- ./ page heading -->
<script>
    var varUseMobile          = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
    var site_url              = "<?php echo base_url(); ?>";
    var priceFechasOriginales = {};

    /* startOn y endOn son datetime y endOn admite NULL. pv2InitDate recorta a 10 caracteres y pone hoy
       si esta vacio: aqui se guarda el valor original para no perder la hora ni inventar una fecha. */
    function priceInitFecha(id){
        var original = $.trim($("#" + id).val());
        priceFechasOriginales[id] = original;
        pv2InitDate(id, false);
        if(original == "") $("#" + id).val("");
    }

    //Si el usuario no cambio el dia, se devuelve el valor original con su hora justo antes de enviar
    function priceRestaurarHora(id){
        var input    = document.getElementById(id);
        var original = priceFechasOriginales[id];
        if(original.length > 10 && input.value == original.substring(0,10)){
            input.type  = "text"; //el selector nativo del telefono no admite hora
            input.value = original;
        }
    }

    $(document).ready(function(){
        //Inicializar fechas
        priceInitFecha("txtStartOn");
        priceInitFecha("txtEndOn");
        afxBindCommon();

        //Regresar a la lista
        $(document).on("click","#btnBack",function(){
            fnWaitOpen();
        });

        //Eliminar el Documento
        $(document).on("click","#btnDelete",function(){
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_invoice_price/delete",
                    data        : {companyID : <?php echo $objListPrice->companyID;?>, listPriceID : <?php echo $objListPrice->listPriceID;?>   },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_invoice_price/index";
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

        //Imprimer Lista de Precio
        $(document).on("click","#btnPrinter",function(){
            console.info("call btnPrinter");
            fnWaitOpen();
            window.location = "<?php echo base_url(); ?>/app_invoice_price/viewRegister/listPriceID/<?php echo $objListPrice->listPriceID;?>";
        });

        //Evento Agregar la lista de Precio
        $(document).on("click","#btnAcept",function(){
            $( "#form-new-invoice-price" ).attr("method","POST");
            $( "#form-new-invoice-price" ).attr("action","<?php echo base_url(); ?>/app_invoice_price/save/edit");

            if(validateForm()){
                fnWaitOpen();
                priceRestaurarHora("txtStartOn");
                priceRestaurarHora("txtEndOn");
                $( "#form-new-invoice-price" ).submit();
            }
        });

        $(document).on("click","#btnClickArchivo",function(){
            window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objListPrice->listPriceID; ?>","blanck");
        });
    });

    function validateForm(){
        var result            = true;
        var timerNotification = 15000;

        if (  $('#txtStartOn').val() == "" ||  $('#txtEndOn').val() == "" ) {
            fnShowNotification("Las fechas son obligatorias","error",timerNotification);
            result = false;
        }
        return result;
    }
</script>
