				<!-- ./ page heading -->
				<script>
				    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
				</script>
				<?php echo view('core_template/posme_v2_common'); ?>
				<script>
				    $(document).ready(function() {
				        //Fecha y combos nativos en telefono; barra inferior (una sola vez: enlazarla dos veces guardaria dos veces)
				        pv2InitDate("txtDate", true);
				        pv2MobileSelects("#txtStatusID,#txtCurrencyID");
				        pv2BindMobileBar();
				        $('.txt-numeric').mask('000,000.00', {
				            reverse: true
				        });

				        //Monto: al salir del campo se muestra como 1,000.00 (sin mascara: la inversa convertiria 500 en 5.00)
				        $(document).on("change", "#txtAmount", function() {
				            $(this).val(pv2Fmt($(this).val()));
				        });

				        //Regresar a la lista
				        $(document).on("click", "#btnBack", function() {
				            fnWaitOpen();
				        });

				        $(document).on("click", "#btnAcept", function() {
				            $("#form-new-emision").attr("method", "POST");
				            $("#form-new-emision").attr("action", "<?php echo base_url(); ?>/app_bank_cheque_make/save/new");

				            if (validateForm()) {
				                //El formato es solo visual: al servidor viaja el numero puro. Si la validacion falla no se toca el campo
				                $("#txtAmount").val(pv2Plain($("#txtAmount").val()));
				                fnWaitOpen();
				                $("#form-new-emision").submit();
				            }

				        });

				        //Buscar la chequera
				        $(document).on("click", "#btnSearcCheckbook", function() {
							var currencyID 	= $("#txtCurrencyID").val(); 
				            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentBankCheck->componentID; ?>/onCompleteCheckbook/SELECCIONAR_CHEQUERA/true/"+ encodeURI('{\"currencyID\"|\"' + currencyID + '\"}') +"/false/not_redirect_when_empty";
				            pv2OpenSearch(url_request, "Buscar chequera", "onCompleteCheckbook", onCompleteCheckbook, "width=900,height=450");
				        });

				        //Eliminar chequera
				        $(document).on("click", "#btnCleaCheckbook", function() {
				            fnClearCheckbook();
				        });

						$(document).on("change","#txtCurrencyID",function(){
				            fnClearCheckbook();
						});

				    });



				    function validateForm() {
				        var result = true;
				        var timerNotification = 15000;

						var date 			= new Date($("#txtDate").val());
						var valueFinal		= $("#txtCheckbookCounterLimit").val();
						var valueCurrent 	= $("#txtCheckbookCounter").val();

						if (isNaN(date.valueOf())) {
							fnShowNotification("Ingrese la Fecha", "error", timerNotification);
							result = false;
						}

				        if ($("#txtCheckbookID").val() === "") {
				            fnShowNotification("Seleccione la chequera", "error", timerNotification);
				            result = false;
				        }

						if($("#txtReceiver").val() === "")
						{
							fnShowNotification("Ingrese el beneficiario", "error", timerNotification);
				            result = false;
						}

						if(pv2Num($("#txtAmount").val()) == 0)
						{
							fnShowNotification("Ingrese el Monto", "error", timerNotification);
				            result = false;
						}

						if((valueCurrent == valueFinal) && $("#txtCheckbookID").val() != "")
						{
							fnShowNotification("Se ha alcanzdo el Limite de la Chequera", "error", timerNotification); //Mejorar este mensaje de error!!!!
				            result = false;
						}

				        return result;
				    }

				    function refreschChecked() {
				        $("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
				        $('.txt-numeric').mask('000,000.00', {
				            reverse: true
				        });
				    }

				    function fnClearCheckbook() {
				        $("#txtCheckbookID").val("");
				        $("#txtCheckbookDescription").val("");
						$("#txtCheckbookCounter").val("");
				    }

				    function onCompleteCheckbook(objResponse) {
				        console.info("CALL onCompleteBank");
				        fnClearCheckbook();
				        $("#txtCheckbookID").val(objResponse[0][0]);
				        $("#txtCheckbookDescription").val(objResponse[0][1] + " | " + objResponse[0][2]);
						
						var valueFinal		= objResponse[0][4] || 0;
						var valueCurrent	= objResponse[0][5] || 0;
						var checkboxSerie	= objResponse[0][6];

						$("#txtCheckbookCounterLimit").val(valueFinal);
						$("#txtCheckbookCounter").val(valueCurrent);
						// $("#txtCheckbookCounterDescription").val(checkboxSerie + " | " + valueCurrent);
					}

				</script>