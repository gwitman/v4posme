<!-- ./ page heading -->
<script>
	var objListaCustomerCredit 			= {};
	var varUseMobile 					= '<?php echo $useMobile; ?>';
	var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';

	$(document).ready(function() {
		pv2InitDate("txtDate", true);
		afxBindCommon();

		//Regresar a la lista
		$(document).on("click", "#btnBack", function() {
			fnWaitOpen();
		});

		//Evento Agregar el Usuario
		$(document).on("click", "#btnAcept", function(e) {
			e.preventDefault();
			$("#form-new-notecredit").attr("method", "POST");
			$("#form-new-notecredit").attr("action", "<?php echo base_url(); ?>/app_cxp_notecredit/save/new");

			if (validateForm()) {
				fnWaitOpen();
				//El monto viaja como numero puro, sin comas
				afxLimpiarMontos("#form-new-notecredit");
				$("#form-new-notecredit").submit();
			}

		});

		//Buscar el Cliente
		$(document).on("click", "#btnSearchCustomer", function() {

			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteProvider/SELECCIONAR_PROVEEDOR_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
			pv2OpenSearch(url_request, "Buscar proveedor", "onCompleteProvider", onCompleteProvider, "width=900,height=450");
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

		//Validar Monto (pv2Num detecta 0 y 0.00)
		if ($("#txtAmount").val() != "" && pv2Num($("#txtAmount").val()) == 0) {
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
		//uniform solo sobre casillas, nunca sobre combos
		$("[type='checkbox'], [type='radio']").not('.toggle, .select2, .multiselect').uniform();
		afxInitMontos(".pv2");
	}

	function fnClearCustomer() {
		$("#txtCustomerEntityID").val("");
		$("#txtCustomerDescription").val("");

	}
</script>
