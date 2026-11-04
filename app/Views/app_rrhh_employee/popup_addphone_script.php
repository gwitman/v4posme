<script >
    $(document).ready(function(){
        pv2MobileSelects(".pv2 select.select2");

        $("#btnPopupCancelar").click(function(e){
            e.preventDefault();
            window.close();
        });
        $("#btnPopupAceptar").click(function(e){
            e.preventDefault();

            //No se acepta un numero vacio
            var numero = $.trim($("#txtEntityPhoneNumber").val());
            $("#msgPhoneRequired").toggle(numero == "");
            if(numero == ""){
                $("#txtEntityPhoneNumber").focus();
                return;
            }

            var data                                = {};
            data.txtEntityPhoneTypeID               = $("#txtEntityPhoneTypeID").val();
            data.txtEntityPhoneTypeDescription      = $("#txtEntityPhoneTypeID option:selected").text();
            data.txtEntityPhoneNumber               = numero;
            data.txtIsPrimary                       = $("#txtIsPrimary").is(':checked') == true ? 1: 0;
            window.opener.parentNewPhone(data);
            window.close();
        });
    });
</script>
