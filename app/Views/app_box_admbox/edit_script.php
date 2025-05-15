<script>

	$(document).on("click","#btnAcept",function(){
			$( "#form-edit-box" ).attr("method","POST");
			$( "#form-edit-box" ).attr("action","<?php echo base_url(); ?>/app_box_admbox/save/edit");

			if(validateForm()){
				fnWaitOpen();
				$( "#form-edit-box" ).submit();
			}

	});


	$(document).on("click","#btnDelete",function(){
		fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
			fnWaitOpen();
			$.ajax({
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				url  		: "<?php echo base_url(); ?>/app_box_admbox/delete",
				data 		: {	companyID :<?php echo $objCashBox->companyID; ?>, txtBoxID :<?php echo $objCashBox->cashBoxID; ?> },
				success:function(data){
					console.info("complete delete success");
					fnWaitClose();
					if(data.error){
						Toast.fire({
							icon: "error",
					      	title: data.message
					    });
					}
					else{
						ToastSuccess.fire({
							icon: "success",
					      	title: "Registro eliminado correctamente..."
					    });
						window.location = "<?php echo base_url(); ?>/app_box_admbox/index";
					}
				},
				error:function(xhr,data){
					console.info("complete delete error");
					fnWaitClose();
					Toast.fire({
						icon: "error",
				      	title: "Error 505 al conectar al servidor"
				    });
				}
			});
		});
	});

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

	const ToastSuccess = Swal.mixin({
		toast				: true,
		position			: "bottom-end",
		showConfirmButton	: false,
		timer				: 15000,
		customClass			: {
			title			: 'white swal2-title-custom',
		},
		background 			: 'green',
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
