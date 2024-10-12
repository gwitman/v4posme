				<!-- ./ page heading -->
				<script>					
					var objRowListWarehouse		= {};
					var objTableListWarehouse	= {};
					var site_url 				= "<?php echo base_url(); ?>/";
					
					$(document).ready(function(){	
						//Inicializar Tabla
						objTableListWarehouse = $("#ListElementWarehouse").dataTable({
							"bPaginate"		: false,
							"bLengthChange"	: false,
							"bFilter"		: false,
							"bSort"			: true,
							"bInfo"			: false,
							"bAutoWidth"	: true							
						});						
						//Regresar
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-rol" ).attr("method","POST");
								$( "#form-new-rol" ).attr("action","<?php echo base_url(); ?>/core_user/save");
								$( "#form-new-rol" ).submit();
						});
						//Comando  Seleccionar Detalle de bodega
						$(document).on("click","#tbody_detail_warehouse tr",function(event){		
								objRowListWarehouse = this;
								fnTableSelectedRow(this,event);
						});
						//Comando Eliminar Detalle de bodegas
						$(document).on("click","#btnDeleteDetailWarehouse",function(){	
							fnShowConfirm("Confirmar..","Desea eliminar la bodega seleccionada?",function(){								
								objTableListWarehouse.fnDeleteRow(objRowListWarehouse);
							});							
						});
						//Comando Agregar Detalle de Bodega
						$(document).on("click","#btnNewDetailWarehouse",function(){								
								window.open(site_url+"core_user/add_warehouse","MsgWindow","width=650,height=500");
								window.parentNewWarehouse = parentNewWarehouse;
						});
						//Buscar el Empleado
						$(document).on("click","#btnSearchEmployeeParent",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objEntity->componentID; ?>/onCompleteEmployee/SELECCIONAR_ENTIDAD/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee; 
						});
						//Eliminar Empleado
						$(document).on("click","#btnClearEmployeeParent",function(){
									$("#txtEmployeeID").val("");
									$("#txtEmployeeDescription").val("");
						});
						
					});
					function onCompleteEmployee(objResponse){
						console.info("CALL onCompleteEmployee");
						
						$("#txtEmployeeID").val(objResponse[0][2]);
						$("#txtEmployeeDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
						
					}
					//Callback Complete: Agregar Bodega
					function parentNewWarehouse(data){
						if(data.txtDetailWarehouseID == ""){
							fnShowNotification("No es posible agregar la bodega","error");
							return;
						}
						if($("input[name='txtDetailWarehouseID[]'][value="+data.txtDetailWarehouseID+"]").length > 0 )
						return;
						
						var tmp1 =		$.tmpl('<span><input type="hidden" name="txtDetailWarehouseID[]" value="${txtDetailWarehouseID}" /> ${txtDetailWarehouseName}</span>',data).html();
						objTableListWarehouse.fnAddData([tmp1]);
					}
				</script>