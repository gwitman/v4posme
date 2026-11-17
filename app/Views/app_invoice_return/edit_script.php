<?php
$isApplied  = isset($isApplied) && $isApplied;
$isPartial  = !$isApplied && (trim((string) $objTM->reference3) != "" || trim((string) $objTM->reference4) != "");
$isReadOnly = $isApplied || $isPartial;
$jsonFlags  = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
?>
<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? esc($useMobile, 'js') : "0"; ?>';

    //Configuracion de la pantalla (la usa ret_v2_common)
    var retConfig = {
        urlBase             : "<?php echo base_url(); ?>",
        componentItemID     : "<?php echo esc($objComponentItem->componentID, 'js'); ?>",
        cantidadItemPopup   : "<?php echo esc((string) $objParameterCantidadItemPoup, 'js'); ?>",
        maxDays             : <?php echo (int) $maxDays; ?>,
        transactionMasterID : <?php echo (int) $objTM->transactionMasterID; ?>,
        partial             : <?php echo $isPartial ? "true" : "false"; ?>,
        saveUrl             : "<?php echo base_url(); ?>/app_invoice_return/save/edit"
    };

    //Datos guardados del documento
    var retIsApplied     = <?php echo $isApplied ? "true" : "false"; ?>;
    var retIsReadOnly    = <?php echo $isReadOnly ? "true" : "false"; ?>;
    var retInitInvoice   = <?php echo json_encode($objInvoice ? $objInvoice : null, $jsonFlags); ?>;
    var retInitialDetail = <?php echo json_encode($isReadOnly ? array() : array_values((array) $objTMD), $jsonFlags); ?>;

    $(document).ready(function(){
        //Controles comunes: combos nativos en telefono, formato de cantidades y barra movil
        if(!retIsReadOnly) pv2InitDate("txtTransactionOn", false);
        afxBindCommon();
        if(!retIsApplied && !pv2IsMobile() && !$("#txtStatusID").data("select2")) afxInitSelect2("#txtStatusID", {placeholder: "seleccionar..."});

        //Ir a la lista
        $(document).on("click", "#btnBack", function(){
            fnWaitOpen();
        });

        //Imprimir (siempre disponible)
        $(document).on("click", "#btnPrinter", function(e){
            e.preventDefault();
            window.open("<?php echo base_url(); ?>/app_invoice_return/viewRegister/companyID/<?php echo (int) $objTM->companyID; ?>/transactionID/<?php echo (int) $objTM->transactionID; ?>/transactionMasterID/<?php echo (int) $objTM->transactionMasterID; ?>", "_blank");
        });

        //Documento aplicado: solo consulta
        if(retIsApplied) return;

        //Aplicacion interrumpida: solo se guarda el cambio de estado
        if(retIsReadOnly){
            retBindSave();
            return;
        }

        //Eliminar el documento (el servidor valida estado y movimientos generados)
        $(document).on("click", "#btnDelete", function(e){
            e.preventDefault();
            fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : "json",
                    type        : "POST",
                    url         : "<?php echo base_url(); ?>/app_invoice_return/delete",
                    data        : {
                        transactionID       : <?php echo (int) $objTM->transactionID; ?>,
                        transactionMasterID : <?php echo (int) $objTM->transactionMasterID; ?>
                    },
                    success     : function(response){
                        fnWaitClose();
                        if(!response || response.error === true){
                            fnShowNotification(response && response.message ? response.message : "No fue posible eliminar el registro", "error");
                            return;
                        }
                        window.location = "<?php echo base_url(); ?>/app_invoice_return/index";
                    },
                    error       : function(){
                        fnWaitClose();
                        fnShowNotification("Error 505", "error");
                    }
                });
            });
        });

        //Factura, detalle y guardar
        retBindForm();

        //Lineas de la factura con los datos guardados (por si la consulta falla)
        retInvoice = retInitInvoice;
        var lineas = [];
        var vistas = {};
        $.each(retInitialDetail, function(i, row){
            var id = String(row.invoiceTransactionMasterDetailID);
            if(vistas[id]) return;
            vistas[id] = true;
            lineas.push({
                transactionMasterDetailID : id,
                itemID                    : row.itemID,
                itemNumber                : row.itemNumber,
                itemName                  : row.itemName,
                unitMeasureName           : row.unitMeasureName,
                quantityInvoiced          : row.quantityInvoiced,
                quantityReplaced          : row.quantityReplaced,
                quantityAvailable         : row.quantityAvailable
            });
        });
        retSetLines(lineas);

        //Renglones guardados
        $.each(retInitialDetail, function(i, row){
            retAddRow({
                transactionMasterDetailID : row.transactionMasterDetailID,
                invoiceDetailID           : row.invoiceTransactionMasterDetailID,
                itemSustitutoID           : row.itemSustitutoID,
                sustitutoText             : retJoin(row.itemNumberSustituto, row.itemNameSustituto, " - "),
                quantity                  : row.quantity
            });
        });

        //Revalidar la factura y traer todas sus lineas (excluye este documento)
        if(retInvoice){
            retLoadInvoice($("#txtInvoiceNumber").val(), "refresh");
        }
        else if($.trim($("#txtInvoiceNumber").val()) !== ""){
            //La factura ya no existe: el documento no puede guardarse con ella
            retSetInvalid(true);
            fnShowNotification("La factura del documento no existe o fue eliminada; elija otra factura", "error", 15000);
        }
    });
</script>
