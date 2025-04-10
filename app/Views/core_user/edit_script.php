<script>
	var objRowListWarehouse 			= {};
	var objRowListNotification 			= {};
	var objTableListWarehouse 			= {};
	var objTableListNotification 		= {};
	var site_url 						= "<?php echo base_url(); ?>/";
	var varParameterCantidadItemPoup 	= '<?php echo $objParameterCantidadItemPoup; ?>';

	$(document).ready(function() {
		//Inicializar Tabla
		objTableListWarehouse = $("#ListElementWarehouse").dataTable({
			"bPaginate"		: false,
			"bLengthChange"	: false,
			"bFilter"		: false,
			"bSort"			: true,
			"bInfo"			: false,
			"bAutoWidth"	: true
		});
		//Inicializar Tabla
		objTableListNotification = $("#ListElementNotification").dataTable({
			"bPaginate"			: false,
			"bLengthChange"		: false,
			"bFilter"			: false,
			"bSort"				: true,
			"bInfo"				: false,
			"bAutoWidth"		: true
		});
		//Comando Guardar
		$(document).on("click", "#btnAcept", function() {
			fnWaitOpen();
			$("#form-new-rol").attr("method", "POST");
			$("#form-new-rol").attr("action", "<?php echo base_url(); ?>/core_user/save");
			$("#form-new-rol").submit();
		});
		//Comando Regresar
		$(document).on("click", "#btnBack", function() {
			fnWaitOpen();
		});
		//Comando Eliminar
		$(document).on("click", "#btnDelete", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar este Rol...", function() {
				fnWaitOpen();
				$.ajax({
					cache: false,
					dataType: 'json',
					type: 'POST',
					url: "<?php echo base_url(); ?>/core_user/delete",
					data: {
						companyID: 	<?php echo $objUser->companyID; ?>,
						branchID: 	<?php echo $objUser->branchID; ?>,
						userID: 	<?php echo $objUser->userID; ?>
					},
					success: function(data) {
						fnWaitClose();
						console.info("complete delete success");
						if (data.error) {
							fnShowNotification(data.message, "error");
						} else {
							window.location = "<?php echo base_url(); ?>/core_user/index";
						}
					},
					error: function(xhr, data) {
						console.info("complete delete error");
						fnWaitClose();
						fnShowNotification("Error 505", "error");
					}
				});
			});
		});
		//Comando  Seleccionar Detalle de bodega
		$(document).on("click", "#tbody_detail_warehouse tr", function(event) {
			objRowListWarehouse = this;
			fnTableSelectedRow(this, event);
		});

		//Comando  Seleccionar Detalle de Notification
		$(document).on("click", "#tbody_detail_notification tr", function(event) {
			objRowListNotification = this;
			fnTableSelectedRow(this, event);
		});

		//Comando Eliminar Detalle de bodegas
		$(document).on("click", "#btnDeleteDetailWarehouse", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar la bodega seleccionada?", function() {
				objTableListWarehouse.fnDeleteRow(objRowListWarehouse);
			});
		});
		//Comando Eliminar Detalle de Notificacion
		$(document).on("click", "#btnDeleteDetailNotification", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar la notificacion seleccionada?", function() {
				objTableListNotification.fnDeleteRow(objRowListNotification);
			});
		});
		//Comando Agregar Detalle de Bodega
		$(document).on("click", "#btnNewDetailWarehouse", function() {
			window.open(site_url + "core_user/add_warehouse", "MsgWindow", "width=650,height=500");
			window.parentNewWarehouse = parentNewWarehouse;
		});
		//Comando Agregar Detalle de Notificacion
		$(document).on("click", "#btnNewDetailNotification", function() {
			window.open(site_url + "core_user/add_tag", "MsgWindow", "width=650,height=500");
			window.parentNewNotification = parentNewNotification;
		});
		//Buscar el Empleado
		$(document).on("click", "#btnSearchEmployeeParent", function() {
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteEmployee/SELECCIONAR_ENTIDAD_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
			window.open(url_request, "MsgWindow", "width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee;
		});
		//Eliminar Empleado
		$(document).on("click", "#btnClearEmployeeParent", function() {
			$("#txtEmployeeID").val("");
			$("#txtEmployeeDescription").val("");
		});

	});

	function onCompleteEmployee(objResponse) {
		console.info("CALL onCompleteEmployee");

		$("#txtEmployeeID").val(objResponse[0][2]);
		$("#txtEmployeeDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);

	}
	//Callback Complete: Agregar Notification Tag
	function parentNewNotification(data) {
		if (data.txtTagID == "") {
			fnShowNotification("No es posible agregar el Tag", "error");
			return;
		}
		if ($("input[name='txtDetailTagID[]'][value=" + data.txtTagID + "]").length > 0)
			return;

		var tmp1 = $.tmpl('<span><input type="hidden" name="txtDetailTagID[]" value="${txtTagID}" /> ${txtTagName}</span>', data).html();
		objTableListNotification.fnAddData([tmp1]);
	}

	//Callback Complete: Agregar Bodega
	function parentNewWarehouse(data) {
		if (data.txtDetailWarehouseID == "") {
			fnShowNotification("No es posible agregar la bodega", "error");
			return;
		}
		if ($("input[name='txtDetailWarehouseID[]'][value=" + data.txtDetailWarehouseID + "]").length > 0)
			return;

		var tmp1 = $.tmpl('<span><input type="hidden" name="txtDetailWarehouseID[]" value="${txtDetailWarehouseID}" /> ${txtDetailWarehouseName}</span>', data).html();
		objTableListWarehouse.fnAddData([tmp1]);
	}
</script>