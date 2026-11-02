<script>
	$(document).ready(function(){
			$(document).on("click","#btnAcept",function(){
				fnWaitOpen();
				$( "#form-edit-acount" ).attr("method","POST");
				$( "#form-edit-acount" ).attr("action","<?php echo base_url(); ?>/core_acount/edit");
				$( "#form-edit-acount" ).submit();
			});	
	}); 
	//Telefono: boton Guardar en la barra inferior y combos nativos (sin select2). En PC no cambia nada
	$(document).ready(function(){
		if(window.pv2BindMobileBar){ pv2BindMobileBar(); }
		PosmeInv.mobileSelects();
	});
</script>