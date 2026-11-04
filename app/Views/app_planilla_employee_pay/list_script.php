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
				window.location	= "<?php echo base_url(); ?>/app_planilla_employee_pay/edit/calendarID/"+data[0];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		}); 
		//El controlador no tiene busqueda en servidor: se filtra la lista cargada en pantalla
		$(document).on("click","#btnSearchTransaction",function(e){
			e.preventDefault();
			fnFiltrarLista();
		});
		$(document).on("keyup","#txtSearchTransaction",function(){
			fnFiltrarLista();
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
						url  		: "<?php echo base_url(); ?>/app_planilla_employee_pay/delete",
						data 		: {calendarID : data[0] },
						success:function(data){
							console.info("complete delete success");
							fnWaitClose();
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{				
								fnShowNotification("success","success");
								objTableListView.fnDeleteRow(objRowTableListView);
							}
						},
						error:function(xhr,data){	
							console.info("complete delete error");									
							fnWaitClose();
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
			window.location	= "<?php echo base_url(); ?>/app_planilla_employee_pay/add";
		});
	});
	
	//Filtra con DataTables; si la tabla no esta disponible, oculta las filas que no coinciden
	function fnFiltrarLista(){
		var valor = $("#txtSearchTransaction").val();
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

	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_planilla_employee_pay/index/"+dataViewID;   
	}					
</script>