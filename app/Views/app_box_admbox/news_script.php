<script>
	$(document).ready(function () {
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});

		//Evento Agregar la Caja
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-box" ).attr("method","POST");
				$( "#form-new-box" ).attr("action","<?php echo base_url(); ?>/app_box_admbox/save/new");

				if(validateForm())
				{
					fnWaitOpen();
					$( "#form-new-box" ).submit();
				}

		});
	})
	const Toast = Swal.mixin({
		toast				: true,
		position			: "bottom-end",
		showConfirmButton	: false,
		timer				: 15000,
		customClass			: {
			title			: 'white swal2-title-custom',
		},
		background 			: 'red',
		timerProgressBar	: true,
		didOpen: (toast) => {
			toast.onmouseenter = Swal.stopTimer;
			toast.onmouseleave = Swal.resumeTimer;
		}
	});

	function validateForm() {
	  const errores = [];

	  if ($("#txtNameBox").val().trim() === "") {
	    errores.push("Nombre de la caja no puede estar vacío");
	  }

	  if ($("#txtDescriptionBox").val().trim() === "") {
	    errores.push("Descripción de la caja no puede estar vacía");
	  }

	  if (errores.length > 0) {
	    Toast.fire({
	      icon: "error",
	      title: errores.join("<br>")
	    });
	    return false;
	  }

	  return true;
	}


</script>
