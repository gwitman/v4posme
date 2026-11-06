<!-- ./ page heading -->
<script>
var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

<?php
    $commentOptions = [];
    if(isset($objListComments) && $objListComments){
        foreach($objListComments as $ws){
            $commentOptions[] = ["id" => $ws->catalogItemID, "name" => $ws->name];
        }
    }
?>
var talCommentOptions = <?php echo json_encode($commentOptions, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

$(document).ready(function(){
    var varParameterCantidadItemPoup = '<?php echo $objParameterCantidadItemPoup; ?>';

    pv2InitDate("txtDate", true);
    talBindCommon();

    $(document).on("change","#txtCurrencyID",function(){
        updatePantalla();
        updateAmount();
    });

    //Regresar a la lista
    $(document).on("click","#btnBack",function(){
        fnWaitOpen();
    });

    //Evento Agregar el Usuario
    $(document).on("click","#btnAcept",function(e){
        e.preventDefault();
        $( "#form-new-invoice" ).attr("method","POST");
        $( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_purchase_request/save/new");

        if(validateForm()){
            fnWaitOpen();
            $( "#form-new-invoice" ).submit();
        }
    });

    //Buscar el Cliente
    $(document).on("click","#btnSearchCustomer",function(){
        //Redireccion pantalla
        var url_redirect = "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_taller";
        url_redirect     = encodeURIComponent(url_redirect);

        var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/false/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup+"/";
        pv2OpenSearch(url_request,"Buscar cliente","onCompleteCustomer",onCompleteCustomer,"width=900,height=450");
    });

    //Eliminar Cliente
    $(document).on("click","#btnClearCustomer",function(){
        $("#txtCustomerID").val("");
        $("#txtCustomerDescription").val("");
    });

    //Buscar Colaborador
    $(document).on("click","#btnSearchEmployer",function(){
        var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
        pv2OpenSearch(url_request,"Buscar colaborador","onCompleteEmployee",onCompleteEmployee,"width=900,height=450");
    });
    //Eliminar Colaborador
    $(document).on("click","#btnClearEmployer",function(){
        $("#txtEmployerID").val("");
        $("#txtEmployerDescription").val("");
    });

    //Buscar Factura
    $(document).on("click","#btnSearchNote",function(){
        var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteBilling/SELECCIONAR_FACTURA_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
        pv2OpenSearch(url_request,"Buscar factura","onCompleteBilling",onCompleteBilling,"width=900,height=450");
    });
    //Eliminar Factura
    $(document).on("click","#btnClearNote",function(){
        $("#txtNote").val("");
        $("#txtNoteDescription").val("");
    });

    $('#errorLabel').hide();

    $(document).on("click","#btnAddComments",function(e){
        e.preventDefault();
        fnAgregarFila();
    });

    <?php
    echo $objReadyPostScript;
    ?>

});

function onCompleteBilling(objResponse)
{
    $("#txtNote").val(objResponse[0][2]);
    $("#txtNoteDescription").val(objResponse[0][2]);
}

function onCompleteEmployee(objResponse)
{
    $("#txtEmployerID").val(objResponse[0][2]);
    $("#txtEmployerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
}

function onCompleteCustomer(objResponse)
{
    if(objResponse != undefined)
    {
        var entityID = objResponse[0][1];
        $("#txtCustomerID").val(objResponse[0][1]);
        $("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
    }
}

function validateForm(){
    var result            = true;
    var timerNotification = 15000;

    //Validar Fecha
    if($("#txtDate").val() == ""){
        fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
        result = false;
    }

    if($("#txtCustomerID").val() == ""){
        fnShowNotification("Seleccionar cliente","error",timerNotification);
        result = false;
    }

    //Los scripts por cliente pueden comparar txtDetailAmount contra '0' como texto
    talMontoAntesDeValidar();
    <?php echo getBehavio($company->type,"app_purchase_request","scriptValidateForm","") ?>
    talMontoDespuesDeValidar();

    return result;
}

function refreschChecked(){
    $("[type='checkbox'], [type='radio']").not('.toggle, .select2, .multiselect').uniform();
    pv2Mask($('.txt-numeric'));
}

function fnEliminarFila(boton) {
    $(boton).closest('tr').remove();
}

function fnAgregarFila() {
    talAgregarFila({
        comboValue     : $('#txtCommentsID').val(),
        conDetalleID   : false,
        commentOptions : talCommentOptions
    });
}
</script>
