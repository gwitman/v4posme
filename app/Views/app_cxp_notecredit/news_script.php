<!-- ./ page heading -->
<script>
	var objListaCustomerCredit = {};
	var varUseMobile = '<?php echo $useMobile; ?>';
	var varShareMountDefaultOfAmortization = '<?php echo getBehavio($company->type, "app_box_share", "javscriptVariable_varShareMountDefaultOfAmortization", ""); ?>';

	$(document).ready(function() {
		$('#txtDate').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#txtDate').val(moment().format("YYYY-MM-DD"));
		$("#txtDate").datepicker("update");
		$('.txt-numeric').mask('000,000.00', {
			reverse: true
		});

		//Regresar a la lista
		$(document).on("click", "#btnBack", function() {
			fnWaitOpen();
		});

		//Evento Agregar el Usuario
		$(document).on("click", "#btnAcept", function() {
			$("#form-new-notecredit").attr("method", "POST");
			$("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_cxp_notecredit/save/new");

			if (validateForm()) {
				fnWaitOpen();
				$("#form-new-notecredit").submit();
			}

		});

		onCompletePantalla();

		//Buscar el Cliente
		$(document).on("click", "#btnSearchCustomer", function() {

			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentProvider->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR/true/empty/false/not_redirect_when_empty";
			window.open(url_request, "MsgWindow", "width=900,height=450");
			window.onCompleteProvider = onCompleteProvider;
		});

		//Eliminar Cliente
		$(document).on("click", "#btnClearCustomer", function() {
			$("#txtCustomerID").val("");
			$("#txtCustomerDescription").val("");
			$("#txtBalanceStart").val("0.00");
		});

		$(document).on("click", "#btnClearCustomer", function() {
			fnClearCustomer();
		});

		$(document).on("change", "#txtMobileEntityID", function() {

			$("#txtCustomerID").val($(this).val());
			$("#txtCustomerDescription").val($('#txtMobileEntityID').find(":selected").data("name"));

			fnWaitOpen();
			$.ajax({
				cache: false,
				dataType: 'json',
				type: 'POST',
				url: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
				data: {
					customerID: $("#txtCustomerID").val(),
					currencyID: $("#txtCurrencyID").val()
				},
				success: function(obj, index, event) {
					console.info("complete data success getCustomerBalance");
					fnWaitClose();
					console.info(obj);
					objListaCustomerCredit = obj.array;
					var saldoTotal = 0;
					objListaCustomerCredit.forEach(function(obj, inl) {
						saldoTotal = saldoTotal + fnFormatFloat(obj.remaining, 2);
					});

					saldoTotal = fnFormatNumber(saldoTotal, 2);
					$("#txtBalanceStart").val(saldoTotal);


				},
				error: function(xhr, data) {
					console.info("complete data error getCustomerBalance");
					fnWaitClose();
					fnShowNotification("Error 505", "error");
				}
			});


		});


	});

	function onCompleteCustomer(objResponse) {
		console.info("CALL onCompleteCustomer");

		var entityID = objResponse[0][1];
		$("#txtCustomerID").val(objResponse[0][1]);
		$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);

		fnWaitOpen();

		$.ajax({
			cache: false,
			dataType: 'json',
			type: 'POST',
			url: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
			data: {
				customerID: $("#txtCustomerID").val(),
				currencyID: $("#txtCurrencyID").val()
			},
			success: function(obj, index, event) {
				console.info("complete data success getCustomerBalance");
				fnWaitClose();
				console.info(obj);
				objListaCustomerCredit = obj.array;
				var saldoTotal = 0;
				objListaCustomerCredit.forEach(function(obj, inl) {
					saldoTotal = saldoTotal + fnFormatFloat(obj.remaining, 2);
				});

				saldoTotal = fnFormatNumber(saldoTotal, 2);
				$("#txtBalanceStart").val(saldoTotal);


			},
			error: function(xhr, data) {
				console.info("complete data error getCustomerBalance");
				fnWaitClose();
				fnShowNotification("Error 505", "error");
			}
		});


	}

	function onCompleteProvider(objResponse) {
		console.info("CALL onCompleteProvider");
		fnClearExpense();
		$("#txtCustomerID").val(objResponse[0][1]);
		$("#txtCustomerDescription").val(objResponse[0][2] + " | " + objResponse[0][3]);
	}

	function fnClearExpense() {
		$("#txtCustomerID").val("");
		$("#txtCustomerDescription").val("");
	}

	function validateForm() {
		var result = true;
		var timerNotification = 15000;

		//Validar Fecha
		if ($("#txtDate").val() == "") {
			fnShowNotification("Establecer Fecha al Documento", "error", timerNotification);
			result = false;
		}

		//Validar Monto
		if ($("#txtAmount").val() == "0") {
			fnShowNotification("El monto no puede ser 0", "error", timerNotification);
			result = false;
		}

		if ($("#txtCustomerID").val() === "") {
			fnShowNotification("Seleccione el cliente", "error", timerNotification);
			result = false;
		}

		return result;
	}

	function refreschChecked() {
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		//$('.txtDebit').mask('000,000.00', {reverse: true});
		//$('.txtCredit').mask('000,000.00', {reverse: true});
		$('.txt-numeric').mask('000,000.00', {
			reverse: true
		});
	}

	function onCompletePantalla() {
		if (varUseMobile == "1") {
			$("#tb_transaction_master_detail th").css("display", "none");
			$("#tb_transaction_master_detail td").css("display", "block");
		}
	}

	function fnClearCustomer() {
		$("#txtCustomerEntityID").val("");
		$("#txtCustomerDescription").val("");

	}
</script>