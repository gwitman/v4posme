<!-- ./ page heading -->
<script>
	$(document).ready(function() {
		//Regresar a la lista
		$(document).on("click", "#btnBack", function() {
			fnWaitOpen();
		});
		//Evento Agregar el Usuario
		$(document).on("click", "#btnAcept", function() {
			fnWaitOpen();
			$("#form-new-account-type").attr("method", "POST");
			$("#form-new-account-type").attr("action", "<?php echo base_url(); ?>/app_accounting_indicators/save/new");
			$("#form-new-account-type").submit();
		});

	});
</script>