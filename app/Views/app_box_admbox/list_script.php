<script>
	$(document).ready(function(){
		$(document).on("click","#btnView",function(){
			window.open("<?php echo base_url(); ?>/core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback;
		});
		$(document).on("click","#btnEdit",function(){

			if(objRowTableListView != undefined){
				fnWaitOpen();
				var data 		= objTableListView.fnGetData(objRowTableListView);
				window.location	= "<?php echo base_url(); ?>/app_box_admbox/edit/"+data[0]+"/"+data[2];
			}
			else{
				Toast.fire({
					icon: "error",
					title: "Seleccionar registro a editar..."
				});
			}

		});
		$(document).on("click","#btnEliminar",function(){

			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);
				fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
					fnWaitOpen();
					$.ajax({
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_box_admbox/delete",
						data 		: {companyID : data[0], txtBoxID :data[2] },
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
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
		});
		$(document).on("click","#btnNuevo",function(){
			fnWaitOpen();
			window.location	= "<?php echo base_url(); ?>/app_box_admbox/add";
		});
	});

	//Buscar en la lista: filtra la tabla del lado del cliente (admbox no tiene busqueda en el servidor)
	function fnFiltrarLista(){
		var valor = $("#txtSearchList").val();
		if(typeof objTableListView != "undefined" && objTableListView && typeof objTableListView.fnFilter == "function"){
			objTableListView.fnFilter(valor);
		}
		else{
			var texto = $.trim(valor).toLowerCase();
			$("#ListView tbody tr").each(function(){
				$(this).toggle(texto == "" || $(this).text().toLowerCase().indexOf(texto) != -1);
			});
		}
	}
	$(document).on("click","#btnSearchList",function(e){
		e.preventDefault();
		fnFiltrarLista();
	});
	$(document).on("keyup","#txtSearchList",function(e){
		fnFiltrarLista();
	});
	$(document).on("keydown","#txtSearchList",function(e){
		if((e.keyCode || e.which) == 13){
			e.preventDefault();
			fnFiltrarLista();
		}
	});

	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_box_admbox/index/"+dataViewID;
	}
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
</script>