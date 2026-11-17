<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? esc($useMobile, 'js') : "0"; ?>';

    //Configuracion de la pantalla (la usa ret_v2_common)
    var retConfig = {
        urlBase             : "<?php echo base_url(); ?>",
        componentItemID     : "<?php echo esc($objComponentItem->componentID, 'js'); ?>",
        cantidadItemPopup   : "<?php echo esc((string) $objParameterCantidadItemPoup, 'js'); ?>",
        maxDays             : <?php echo (int) $maxDays; ?>,
        transactionMasterID : 0,
        partial             : false,
        saveUrl             : "<?php echo base_url(); ?>/app_invoice_return/save/new"
    };

    $(document).ready(function(){
        //Controles: fecha de hoy, combos nativos en telefono, formato de cantidades y barra movil
        pv2InitDate("txtTransactionOn", true);
        afxBindCommon();
        if(!pv2IsMobile() && !$("#txtStatusID").data("select2")) afxInitSelect2("#txtStatusID", {placeholder: "seleccionar..."});

        //Factura, detalle y guardar
        retBindForm();

        //Ir a la lista
        $(document).on("click", "#btnBack", function(){
            fnWaitOpen();
        });
    });
</script>
