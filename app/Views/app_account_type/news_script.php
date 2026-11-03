				<!-- ./ page heading -->
				<script>					
					
					$(document).ready(function(){					
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-account-type" ).attr("method","POST");
								$( "#form-new-account-type" ).attr("action","<?php echo base_url(); ?>/app_accounting_type/save/new");
								$( "#form-new-account-type" ).submit();
						});
						
					});


	//Telefono: boton Guardar en la barra inferior y combos nativos (sin select2). En PC no cambia nada
	$(document).ready(function(){
		if(window.pv2BindMobileBar){ pv2BindMobileBar(); }
		PosmeInv.mobileSelects();
	});
</script>