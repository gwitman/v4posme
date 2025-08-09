<!-- ./ page heading -->
<script>
	
	$(document).ready(function(){						 
		 
		
		
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-invoice" ).attr("method","POST");
				$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/{nombre_controlador}/save/new");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-invoice" ).submit();
				}
				
		});
		
		

	});



	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;


		return result;
	}


	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
	}

	
</script>