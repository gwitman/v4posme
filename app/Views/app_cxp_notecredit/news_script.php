<!-- ./ page heading -->
<script>
	var objListaCustomerCredit = {};
	var varUseMobile = '<?php echo $useMobile; ?>';

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

	});

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

		if ($("#txtAmount").val() == "") {
			fnShowNotification("Debe ingresar un monto", "error", timerNotification);
			result = false;
		}

		if ($("#txtCustomerID").val() === "") {
			fnShowNotification("Seleccione el proveedor", "error", timerNotification);
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

	function fnClearCustomer() {
		$("#txtCustomerEntityID").val("");
		$("#txtCustomerDescription").val("");

	}
</script>