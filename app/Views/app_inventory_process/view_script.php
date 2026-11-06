<!-- ./ page heading -->
<script>
	var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

	$(document).ready(function(){


		/*Generar salidas de inventario por formulas*/
		$(document).on("click","#btnAceptCreateOutputInventoryByFormulate",function(){
			fnShowConfirm("Confirmar..","Desea Realizar la salidas de inventarios de las facturas con productos formulados.",function(){
					fnWaitOpen();
					$.ajax({
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_inventory_api/generatedTransactionOutputByFormulate",
						data 		: { componentPeriodID :$("#txtClosedPeriod").val() , componentCycleID :$("#txtClosedCicle").val()},
						success:function(data){
							fnWaitClose();
							console.info("complete delete success");
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{
								console.info(data);
								if(data.result.code == 0)
									fnShowNotification("success","success");
								else
									fnShowNotification("problemas en el proceso de cierre","error");
							}
						},
						error:function(xhr,data){
							fnWaitClose();
							console.info("complete delete error");
							fnShowNotification("Error 505","error");
						}
					});
			});
		});


		$(document).on("change","#txtClosedPeriod",function(){
				fnWaitOpen();
				$.ajax({
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_accounting_api/getCycleNotClosed",
					data 		: { componentPeriodID :$("#txtClosedPeriod").val() },
					success:function(data){
						fnWaitClose();
						console.info("complete delete success");
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							$("#txtClosedCicle").html("");
							$.each(data.cycles,function(i,obj){
								$("#txtClosedCicle").append("<option value='"+obj.componentCycleID+"'>"+obj.startOnFormat+"</option>")
							});
							//select2 (PC) debe refrescar la seleccion al regenerar las opciones
							$("#txtClosedCicle").trigger("change");
						}
					},
					error:function(xhr,data){
						fnWaitClose();
						console.info("complete delete error");
						fnShowNotification("Error 505","error");
					}
				});
		});


		$(document).on("click","#btnAceptUploadFile",function(){

			var URL = "<?php echo base_url(); ?>/app_inventory_api/uploadDataEncuentra24";
			window.open(URL, '_blank');

		});

		//Telefono: combos nativos (sin select2). En PC no cambia nada
		PosmeInv.mobileSelects();

	});


</script>
