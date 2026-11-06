<!-- ./ page heading -->
<script>
	var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

	$(document).ready(function(){
						//Inicializar DataPciker
						$('#txtBirthDate').datepicker({format:"yyyy-mm-dd"});
						 //Regresar a la lista
						$(document).on("click","#btnSearchCustomer",function(){
								fnWaitOpen();
						});
    });
</script>