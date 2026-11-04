<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    $(document).ready(function(){
        //Inicio: hoy al crear. Fin: si esta vacia sigue vacia
        pv2InitDate("txtStartOn", true);
        plaInitDate("txtEndOn");

        empInitForm({
            saveUrl         : "<?php echo base_url(); ?>/app_rrhh_employee/save/new",
            searchParentUrl : "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty",
            emailRows       : [],
            phoneRows       : []
        });
    });
</script>
