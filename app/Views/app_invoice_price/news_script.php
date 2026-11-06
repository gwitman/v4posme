<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
    var site_url     = "<?php echo base_url(); ?>";

    $(document).ready(function(){
        //Inicializar fechas
        pv2InitDate("txtStartOn", true);
        pv2InitDate("txtEndOn", true);
        afxBindCommon();

        //Regresar a la lista
        $(document).on("click","#btnBack",function(){
            fnWaitOpen();
        });

        //Guardar la lista de precio
        $(document).on("click","#btnAcept",function(){
            $( "#form-new-invoice-price" ).attr("method","POST");
            $( "#form-new-invoice-price" ).attr("action","<?php echo base_url(); ?>/app_invoice_price/save/new");

            if(validateForm()){
                fnWaitOpen();
                $( "#form-new-invoice-price" ).submit();
            }
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
