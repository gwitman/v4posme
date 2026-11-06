<script >
    $(document).ready(function(){
        pv2MobileSelects(".pv2 select.select2");
        //$('#txtEntityPhoneNumber').mask('+(000) 0000-0000');

        $("#btnPopupCancelar").click(function(e){
            e.preventDefault();
            window.close();
        });
        $("#btnPopupAceptar").click(function(e){
            e.preventDefault();

            var data                                = {};
            data.txtEntityPhoneTypeID               = $("#txtEntityPhoneTypeID").val();
            data.txtEntityPhoneTypeDescription      = $("#txtEntityPhoneTypeID option:selected").text();
            data.txtEntityPhoneNumber               = $("#txtEntityPhoneNumber").val();
            data.txtIsPrimary                       = $("#txtIsPrimary").is(':checked');
            window.opener.parentNewPhone(data);
            window.close();
        });
    });
</script>
