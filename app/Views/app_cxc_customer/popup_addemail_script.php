<script >
    $(document).ready(function(){
        $("#btnPopupCancelar").click(function(e){
            e.preventDefault();
            window.close();
        });
        $("#btnPopupAceptar").click(function(e){
            e.preventDefault();

            var data            = {};
            data.txtEmail       = $("#txtEmail").val();
            data.txtIsPrimary   = $("#txtIsPrimary").is(':checked');
            window.opener.parentNewEmail(data);
            window.close();
        });
    });
</script>
