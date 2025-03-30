<script>
    let publicCatalogID         = <?= $publicCatalogID ?? 0 ?>;
    let listaValoresModificar   = [];
    let transactionDocument     = "<?= $objTransactionMasterReference->transactionReferenceNumber?>";
    let publicCatalogDetailID   = "<?= $objTransactionMasterReference->reference2 ?>";
    let valorNuevoEndosar       = "<?= $objTransactionMasterReference->reference10 ?>";
    let valorAnteriorEndosar    = "<?= $objTransactionMasterReference->referecne9 ?>";
    let objTransactionMaster    = [];
    var objParameterUrlPrinter	= '<?php echo $objParameterUrlPrinter; ?>';

    $( document ).ready(function() {

        let selectValorModificar    = $("#txtValorModificar");
        let contenedorValorAnterior = $("#contenedorDinamicoValorAnterior");
        let contenedorValorNuevo    = $("#contenedorDinamicoValorNuevo");
        let txtTransactionID        = $('#txtTransactionID');
        selectValorModificar.prop("disabled", true).html('<option value="">Seleccione una opción...</option>');
        selectValorModificar.select2();
        txtTransactionID.select2();
        $('#txtDate').datepicker({format: "yyyy-mm-dd"});
        $('#txtDate').val(moment().format("YYYY-MM-DD"));
        $("#txtDate").datepicker("update");

        fnFillListCampoModificar(transactionDocument);

        $('#btnAcept').click(function () {
            let form = $( "#form-new-endoso" );
            form.attr("method","POST");
            form.attr("action","<?= base_url(); ?>/app_tools_endorsements/save/edit");
            if (fnValidateForm()){
                fnWaitOpen();
                form.submit();
            }

        });

        $(document).on("click","#btnDelete",function(){
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url  		: "<?php echo base_url(); ?>/app_tools_endorsements/delete",
                    data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_tools_endorsements/index";
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

        $("#btnSearchDocument").click(function () {
            let transactionId = txtTransactionID.val()
            if (transactionId){
                let url_request 		=
                    "<?php echo base_url(); ?>/core_view/showviewbynamepaginate"+
                    "/<?php echo $objComponent->componentID; ?>"+
                    "/onCompleteDocument/SELECCIONAR_TRANSACCION/true/"+
                    encodeURI(
                        '{'+
                        '\"transactionID\"|\"'+transactionId+'\"'+
                        '}'
                    ) +
                    "/false/not_redirect_when_empty/1/1/25/";
                window.open(url_request, "MsgWindow", "width=900,height=450");
                window.onCompleteDocument = onCompleteDocument;
            }
        });

        $('#btnClearDocument').click(function (){
            fnClearValues(true);
        });

        $('#btnPrint').click(function () {
            fnWaitOpen();
            window.open("<?php echo base_url(); ?>/"+objParameterUrlPrinter+"/<?= $objTransactionMaster->transactionID;?>/<?= $objTransactionMaster->transactionMasterID;?>","_blank");
            fnWaitClose();
        });

        txtTransactionID.change(function () {
            fnClearValues();
        });

        // Evento para detectar el cambio en el select del tipo de endoso
        selectValorModificar.change(function () {
            let selectedValue       = selectValorModificar.val();
            let selectTipoEndoso    = listaValoresModificar.filter(function(item) {
                return item.publicCatalogDetailID === selectedValue;
            });
            fnMostrarCampo(selectTipoEndoso[0]);
        });

        function onCompleteDocument(objResponse){
            console.info("CALL onCompleteDocument");
            $('#txtTransactionMasterIDEndoso').val(objResponse[0][1]);
            let documento = objResponse[0][2];
            $('#txtTransactionNumber').val(documento);
            $('#txtTransactionNumberDescription').val(documento);
            fnClearValues(false);
            fnFillListCampoModificar(documento);
        }
        
        function fnFillListCampoModificar(documento){
            transactionDocument = documento;
            let reference1      = documento.substring(0, 3);
            fnWaitOpen();
            //obtenemos el transaction master con el numero de documento
            $.ajax(
                '<?= base_url()?>/app_tools_endorsements/getTransactionMaster/'+transactionDocument
            ).done(function (data) {
                if (!data.error){
                    objTransactionMaster = data.data;
                }
            })

            //buscamos el detalle del catalago de endoso conforme el tipo de documento
            $.ajax({
                url     : "<?= base_url(); ?>/app_public_catalog_api/getPublicCatalogDetailEndoso/publicCatalogID/"+publicCatalogID+"/reference1/"+reference1,
                type    : 'GET',
                success : function (respuesta) {
                    // Limpiar el select antes de agregar nuevas opciones
                    selectValorModificar.empty().append('<option value="0">Seleccione una opción...</option>');
                    if (respuesta.data.length > 0) {
                        listaValoresModificar   =  respuesta.data;
                        $.each(listaValoresModificar, function (index, item) {
                            let selected = publicCatalogDetailID === item.publicCatalogDetailID;
                            let newOption = new Option(item.name, item.publicCatalogDetailID, selected, selected);
                            selectValorModificar.append(newOption).trigger('change');
                            if (selected){
                                selectValorModificar.trigger('change');
                            }
                        });
                        selectValorModificar.prop("disabled", false);
                    } else {
                        selectValorModificar.html('<option value="">No hay datos disponibles</option>');
                    }
                    fnWaitClose();
                }
            });
        }

        // Función para mostrar el campo correspondiente
        function fnMostrarCampo(endoso) {
            if (endoso) {
                contenedorValorAnterior.empty();
                contenedorValorNuevo.empty();
                let valorAnterior   = valorAnteriorEndosar;
                let campo           = endoso.display.split('.').pop();
                let tipo            = endoso.description;
                let referencia2     = endoso.reference2;
                let reference3      = endoso.reference3;
                if (objTransactionMaster.hasOwnProperty(campo)) {
                    valorAnterior = objTransactionMaster[campo];
                }
                if (tipo === "datetime") {
                    contenedorValorAnterior.html('<input readonly type="datetime-local" name="txtValorAnterior" id="txtValorAnterior" class="form-control" value="' + valorAnterior + '">');
                    contenedorValorNuevo.html('<input type="datetime-local" name="txtValorNuevo" id="txtValorNuevo" class="form-control" value="'+ valorNuevoEndosar +'">');
                } else if (tipo === "combobox") {
                    contenedorValorAnterior.html('<select name="txtValorAnterior" id="txtValorAnterior" class="select2"><option value="">Cargando opciones...</option></select>');
                    contenedorValorNuevo.html('<select name="txtValorNuevo" id="txtValorNuevo" class="select2"><option value="">Cargando opciones...</option></select>');
                    if (referencia2 === "tb_catalog") {
                        fnCargarOpcionesComboTbCatalog(reference3,campo);
                    }
                    $(document).on("change", "#txtValorNuevo", function () {
                        var textoSeleccionado = $(this).find("option:selected").text();
                        $("#txtSelectedTextValorNuevo").val(textoSeleccionado);
                    });

                    $(document).on("change", "#txtValorAnterior", function () {
                        var textoSeleccionado = $(this).find("option:selected").text();
                        $("#txtSelectedTextValorAnterior").val(textoSeleccionado);
                    });
                } else if (tipo === "input") {
                    contenedorValorAnterior.html('<input readonly type="text" id="txtValorAnterior" class="form-control" placeholder="Ingrese un valor" value="' + valorAnterior + '">');
                    contenedorValorNuevo.html('<input type="text" name="txtValorNuevo" id="txtValorNuevo" class="form-control" value="'+ valorNuevoEndosar +'">');
                }
            }
        }

        function fnCargarOpcionesComboTbCatalog(reference3, campo) {
            fnWaitOpen();
            let comboValorAnterior  = $("#txtValorAnterior");
            let comboValorNuevo     = $("#txtValorNuevo");
            let valor               = valorAnteriorEndosar;
            if (objTransactionMaster.hasOwnProperty(campo)) {
                valor = objTransactionMaster[campo];
            }
            $.ajax({
                url         : "<?= base_url()?>/app_catalog_api/getCatalogByReference3/"+reference3,
                type        : "GET",
                dataType    : "json",
                success     : function (respuesta) {
                    if (respuesta.data.length>0){
                        fnFillComboBox(comboValorAnterior,respuesta.data, valor);
                        fnFillComboBox(comboValorNuevo, respuesta.data, valorNuevoEndosar);
                    }
                    fnWaitClose();
                },
                error: function () {
                    comboValorAnterior.html('<option value="">Error al cargar opciones</option>');
                    comboValorNuevo.html('<option value="">Error al cargar opciones</option>');
                    fnWaitClose();
                }
            });
        }

        function fnClearValues(clearTransactionNumber){
            if (clearTransactionNumber){
                $('#txtTransactionNumberDescription').val('');
                $('#txtTransactionNumber').val('');
            }
            contenedorValorAnterior.empty();
            contenedorValorNuevo.empty();
            selectValorModificar.empty();
            selectValorModificar.trigger('change');
            selectValorModificar.prop("disabled", true);
            let newOption = new Option('Seleccione una opción...', '0', false, true);
            selectValorModificar.append(newOption).trigger('change');
        }

        function fnFillComboBox(comboBox, data, valor){
            comboBox.select2();
            comboBox.empty();
            comboBox.trigger('change');
            $.each(data, function (index, item) {
                let selected    = valor === item.catalogItemID;
                var newOption   = new Option(item.name, item.catalogItemID, false, selected);
                comboBox.append(newOption).trigger('change');
                if (selected){
                    comboBox.trigger('change');
                }
            });
        }

        function fnValidateForm() {
            let valorNuevo = $('#txtValorNuevo').val();
            if(typeof valorNuevo === "undefined" || valorNuevo === null || valorNuevo === ""){
                fnShowNotification("Seleccionar un valor nuevo para endoso","error",1500);
                fnWaitCloseV2();
                return false;
            }
            return true;
        }
    });
</script>