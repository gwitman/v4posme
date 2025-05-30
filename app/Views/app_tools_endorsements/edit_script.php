<script>
    let catalogID         		= <?= $catalogID ?? 0 ?>;
    let listaValoresModificar   = JSON.parse('<?php echo json_encode($objCatalogItem); ?>');
    let transactionDocument     = "<?= $objTransactionMasterReference->transactionReferenceNumber?>";
    let catalogItemID   		= "<?= $objTransactionMasterReference->reference2 ?>";
    let valorNuevoEndosar       = "<?= $objTransactionMasterReference->reference10 ?>";
    let valorAnteriorEndosar    = "<?= $objTransactionMasterReference->referecne9 ?>";
    let objTransactionMaster    = [];
    var objParameterUrlPrinter	= '<?php echo $objParameterUrlPrinter; ?>';

    $( document ).ready(function() {

        let tagContenedorValorAnterior = $("#contenedorDinamicoValorAnterior");
        let tagContenedorValorNuevo    = $("#contenedorDinamicoValorNuevo");                
        $("#txtValorModificar").select2();
        $('#txtTransactionID').select2();
        $('#txtDate').datepicker({format: "yyyy-mm-dd"});        
        $("#txtDate").datepicker("update");

        let selectedValue       = $("#txtValorModificar").val();			
		let selectTipoEndoso    = listaValoresModificar.filter(function(item) {
			return item.catalogItemID === selectedValue;
		});
		fnMostrarCampoByTypeEndorsementsPreRegister(selectTipoEndoso[0]);
		
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
            let transactionId = $('#txtTransactionID').val()
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

		$(document).on("change", "#txtValorNuevo", function () {
			var textoSeleccionado = $(this).find("option:selected").text();
			$("#txtSelectedTextValorNuevo").val(textoSeleccionado);
		});

		$(document).on("change", "#txtValorAnterior", function () {
			var textoSeleccionado = $(this).find("option:selected").text();
			$("#txtSelectedTextValorAnterior").val(textoSeleccionado);
		});
		
        $('#btnPrint').click(function () {
            fnWaitOpen();
            window.open("<?php echo base_url(); ?>/"+objParameterUrlPrinter+"/<?= $objTransactionMaster->transactionID;?>/<?= $objTransactionMaster->transactionMasterID;?>","_blank");
            fnWaitClose();
        });

        $('#txtTransactionID').change(function () {
            fnClearValues(true);
        });

        // Evento para detectar el cambio en el select del tipo de endoso
        $("#txtValorModificar").change(function () {
            let selectedValue       = $("#txtValorModificar").val();			
            let selectTipoEndoso    = listaValoresModificar.filter(function(item) {
                return item.catalogItemID === selectedValue;
            });
            fnMostrarCampoByTypeEndorsements(selectTipoEndoso[0]);
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
					
					
					//buscamos el detalle del catalago de endoso conforme el tipo de documento
					$.ajax({
						url     : "<?= base_url(); ?>/app_catalog_api/getCatalogItemByEndosos/catalogID/"+catalogID+"/reference1/"+reference1,
						type    : 'GET',
						success : function (respuesta) {
							// Limpiar el select antes de agregar nuevas opciones
							$("#txtValorModificar").empty().append('<option value="0">Seleccione una opción...</option>');
							if (respuesta.data.length > 0) {
								listaValoresModificar   =  respuesta.data;
								$.each(listaValoresModificar, function (index, item) 
								{
									let selected = catalogItemID === item.catalogItemID;
									let newOption = new Option(item.name, item.catalogItemID, selected, selected);
									$("#txtValorModificar").append(newOption).trigger('change');
									if (selected){
										$("#txtValorModificar").trigger('change');
									}
								});
								$("#txtValorModificar").prop("disabled", false);
							} else {
								$("#txtValorModificar").html('<option value="">No hay datos disponibles</option>');
							}
							fnWaitClose();
						}
					});
					
                }
            })
        }

		// Función para mostrar el campo correspondiente
        function fnMostrarCampoByTypeEndorsementsPreRegister(endoso) {
			fnWaitOpen();
            if (endoso) {
				
                tagContenedorValorAnterior.empty();
                tagContenedorValorNuevo.empty();
                
                let campo       = endoso.display.split('.').pop();
                let tipo        = endoso.description;
                let referencia2 = endoso.reference2;
                let referencia3 = endoso.reference3;
				fnWaitOpen();
				
					
				var valorAnterior 			= '<?php echo $objTransactionMasterReference->referecne9; ?>';
				var valorNuevo 	  			= '<?php echo $objTransactionMasterReference->reference10; ?>';
				var valorAnteriorLabel 		= '<?php echo $objTransactionMasterReference->reference11; ?>';
				var valorNuevoLabel 	  	= '<?php echo $objTransactionMasterReference->reference12; ?>';		
				
				if (tipo === "datetime") 
				{
					tagContenedorValorAnterior.html('<input readonly type="datetime-local" name="txtValorAnterior" id="txtValorAnterior" class="form-control" value="' + valorAnterior + '">');
					tagContenedorValorNuevo.html('<input type="datetime-local" name="txtValorNuevo" id="txtValorNuevo" class="form-control" value="' + valorNuevo + '" >');
					fnWaitClose();
				} 
				else if (tipo === "combobox") 
				{
					tagContenedorValorAnterior.html('<select name="txtValorAnterior" id="txtValorAnterior" class="select2"><option value="">Cargando opciones...</option></select>');
					tagContenedorValorNuevo.html('<select name="txtValorNuevo" id="txtValorNuevo" class="select2"><option value="">Cargando opciones...</option></select>');
					$("#txtValorAnterior").prop("disabled", true);
					
					if (referencia2 === "tb_catalog") 
					{
						fnCargarOpcionesComboTbCatalog(referencia3,campo,valorAnterior,valorNuevo);
					}

				} 
				else if (tipo === "input") 
				{
					tagContenedorValorAnterior.html('<input readonly type="text" id="txtValorAnterior"  name="txtValorAnterior" class="form-control" placeholder="Ingrese un valor" value="' + valorAnterior + '">');
					tagContenedorValorNuevo.html('<input type="text" name="txtValorNuevo" id="txtValorNuevo" class="form-control" placeholder="Ingrese un valor" value="' + valorNuevo + '"  >');
					fnWaitClose();
				}
				
				
			}
	
        }
		
        // Función para mostrar el campo correspondiente
        function fnMostrarCampoByTypeEndorsements(endoso) {
			
            if (endoso) {
                tagContenedorValorAnterior.empty();
                tagContenedorValorNuevo.empty();
                let valorAnterior   = valorAnteriorEndosar;
                let campo           = endoso.display.split('.').pop();
                let tipo            = endoso.description;
                let referencia2     = endoso.reference2;
                let referencia3     = endoso.reference3;				
				fnWaitOpen();
				
				
				//obtener los valores anteriores de la tarnsaccion
				$.ajax({
					url     : "<?= base_url(); ?>/app_tools_endorsements/getTransactionMasterOld/"+$("#txtTransactionNumber").val()+"/"+endoso.display,
					type    : 'GET',
					success : function (respuesta) {
						
						if(respuesta.error == true)
						{
							fnWaitClose();
							fnShowNotification(respuesta.message,"error");
							return;
						}
						
						valorAnterior = respuesta.data[0].Value;
						$("#txtSelectedTextValorAnterior").val("");
						$("#txtSelectedTextValorNuevo").val("");
						if (tipo === "datetime") 
						{
							tagContenedorValorAnterior.html('<input readonly type="datetime-local" name="txtValorAnterior" id="txtValorAnterior" class="form-control" value="' + valorAnterior + '">');
							tagContenedorValorNuevo.html('<input type="datetime-local" name="txtValorNuevo" id="txtValorNuevo" class="form-control">');
							fnWaitClose();
						} 
						else if (tipo === "combobox") 
						{
							tagContenedorValorAnterior.html('<select name="txtValorAnterior" id="txtValorAnterior" class="select2"><option value="">Cargando opciones...</option></select>');
							tagContenedorValorNuevo.html('<select name="txtValorNuevo" id="txtValorNuevo" class="select2"><option value="">Cargando opciones...</option></select>');
							$("#txtValorAnterior").prop("disabled", true);
							
							if (referencia2 === "tb_catalog") {
								fnCargarOpcionesComboTbCatalog(referencia3,campo,valorAnterior,0);
							}
						} 
						else if (tipo === "input") 
						{
							tagContenedorValorAnterior.html('<input readonly type="text" id="txtValorAnterior" name="txtValorAnterior" class="form-control" placeholder="Ingrese un valor" value="' + valorAnterior + '">');
							tagContenedorValorNuevo.html('<input type="text" name="txtValorNuevo" id="txtValorNuevo" class="form-control" placeholder="Ingrese un valor">');
							fnWaitClose();
						}
						
						
					}
				});
				
            }
        }

        function fnCargarOpcionesComboTbCatalog(reference3, campo,valorAnterior,valorNuevo) {
			
            
            let comboValorAnterior  = $("#txtValorAnterior");
            let comboValorNuevo     = $("#txtValorNuevo");
            let valor               = valorAnteriorEndosar;
			valor 					= valorAnterior;
			
            $.ajax({
                url         : "<?= base_url()?>/app_catalog_api/getCatalogByName/"+reference3,
                type        : "GET",
                dataType    : "json",
                success     : function (respuesta) {
                    if (respuesta.data.length>0){
                        fnFillComboBox(comboValorAnterior,respuesta.data, valor);
                        fnFillComboBox(comboValorNuevo, respuesta.data, valorNuevo);
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
            tagContenedorValorAnterior.empty();
            tagContenedorValorNuevo.empty();
            $("#txtValorModificar").empty();
            $("#txtValorModificar").trigger('change');
            $("#txtValorModificar").prop("disabled", true);
            let newOption = new Option('Seleccione una opción...', '0', false, true);
            $("#txtValorModificar").append(newOption).trigger('change');
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
			
			$("#txtValorAnterior").prop("disabled", false);
            return true;
        }
    });
</script>