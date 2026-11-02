<!-- ./ page heading -->
<script>					
	
	$(document).ready(function(){
		if(window.pv2BindMobileBar){ pv2BindMobileBar(); }
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				fnWaitOpen();
				$( "#form-new-account-type" ).attr("method","POST");
				$( "#form-new-account-type" ).attr("action","<?php echo base_url(); ?>/app_inventory_category/save/new");
				$( "#form-new-account-type" ).submit();
		});
		
	});
	
	//Telefono: combos nativos (sin select2) y calendario nativo en las fechas. En PC no cambia nada
	$(document).ready(function(){
		PosmeInv.mobileSelects();
	});
</script>