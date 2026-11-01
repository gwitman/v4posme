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
				window.location	= "<?php echo base_url(); ?>/app_inventory_warehouse/edit/companyID/"+data[0]+"/warehouseID/"+data[1];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
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
						url  		: "<?php echo base_url(); ?>/app_inventory_warehouse/delete",
						data 		: {companyID : data[0], warehouseID :data[1]},
						success:function(data){
							fnWaitClose();
							console.info("complete delete success");
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{				
								fnShowNotification("success","success");
								objTableListView.fnDeleteRow(objRowTableListView);
							}
						},
						error:function(xhr,data){	
							fnWaitClose();
							console.info("complete delete error");									
							fnShowNotification("Error 505","error");
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
			window.location	= "<?php echo base_url(); ?>/app_inventory_warehouse/add";
		});

		//Buscar bodega: filtra la tabla del lado del cliente
		function fnFiltrarBodegas(){
			var valor = $("#txtSearchWarehouse").val();
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
		$(document).on("click","#btnSearchWarehouse",function(e){
			e.preventDefault();
			fnFiltrarBodegas();
		});
		$(document).on("keyup","#txtSearchWarehouse",function(e){
			fnFiltrarBodegas();
		});
		$(document).on("keydown","#txtSearchWarehouse",function(e){
			if((e.keyCode || e.which) == 13){
				e.preventDefault();
				fnFiltrarBodegas();
			}
		});
	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_inventory_warehouse/index/"+dataViewID;   
	}					
</script>