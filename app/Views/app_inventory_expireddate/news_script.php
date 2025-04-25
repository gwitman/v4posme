<!-- ./ page heading -->
<script>
	$(document).ready(function() 
	{
		//Descargar Documento
		$(document).on("click", "#btnDownload", function() {
			if($("#txtWarehouseID").val() == "") {
				fnShowNotification("Seleccione una bodega", "error", 15000);
				return false;
			}
			fnWaitOpen();
			$.ajax({
				cache		: false,
				dataType	: 'json',
				type		: 'POST',
				url			: "<?php echo base_url(); ?>/app_inventory_expireddate/download",
				data: {
					warehouseID:  $("#txtWarehouseID").val()
				},
				success: function(data) {
					fnWaitClose();
					if (data.error) {
						fnShowNotification(data.message, "error", 15000);
					} else{
						window.open(data.link, "_blank");
						fnShowNotification("ARCHIVO DESCARGADO CORRECTAMENTE", "success", 15000);
					}
				},
				error: function(xhr, data) {
					fnWaitClose();
					fnShowNotification("Error al descargar el archivo", "error", 15000);
				}
			});
		});

		//SUBIR UN ARCHIVO
		$(document).on("click", "#btnUpload", function() 
		{
			var warehouseID = $("#txtWarehouseID").val();

			if(warehouseID == "") {
				fnShowNotification("Seleccione una bodega", "error", 15000);
				return false;
			}

			var url_request	= "<?= base_url(); ?>/core_elfinder/index/componentID/<?= $objComponent->componentID ?>/componentItemID/" + warehouseID;
			window.open(url_request, "blanck");
		});

		$(document).on("click", "#btnProcess", function() {
			
			if($("#txtWarehouseID").val() == "") {
				fnShowNotification("Seleccione una bodega", "error", 15000);
				return false;
			}

			fnWaitOpen();
			$.ajax({
				cache		: false,
				dataType	: 'json',
				type		: 'POST',
				url			: "<?php echo base_url(); ?>/app_inventory_expireddate/process",
				data: {
					warehouseID:  $("#txtWarehouseID").val()
				},
				success: function(data) {
					fnWaitClose();
					if (data.error) {
						fnShowNotification(data.message, "error", 15000);
					} else{
						fnShowNotification("ARCHIVO PROCESADO CORRECTAMENTE", "success", 15000);
					}
				},
				error: function(xhr, data) {
					fnWaitClose();
					fnShowNotification("Error al procesar el archivo", "error", 15000);
				}
			});
		});


	});
</script>