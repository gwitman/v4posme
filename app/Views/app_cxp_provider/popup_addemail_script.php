<script >
    $(document).ready(function(){
        $("#btnPopupCancelar").click(function(e){
            e.preventDefault();
            window.close();
        });
        $("#btnPopupAceptar").click(function(e){
            e.preventDefault();

            //No se acepta un correo vacio
            var email = $.trim($("#txtEmail").val());
            $("#msgEmailRequired").toggle(email == "");
            if(email == ""){
                $("#txtEmail").focus();
                return;
            }

            var data            = {};
            data.txtEmail       = email;
            data.txtIsPrimary   = $("#txtIsPrimary").is(':checked');
            window.opener.parentNewEmail(data);
            window.close();
        });
    });
</script>
