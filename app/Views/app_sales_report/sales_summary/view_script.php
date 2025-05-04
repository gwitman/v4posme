				<!-- ./ page heading -->
				<script>				
					$(document).ready(function(){
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtStartOn').val(moment().format("YYYY-MM-DD"));						 						
						$("#txtStartOn").datepicker("update");												
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').val(moment().format("YYYY-MM-DD"));
						$("#txtEndOn").datepicker("update");												
						
						$(document).on("click","#print-btn-report",function(){
							var startOn					=	$("#txtStartOn").val();	
							var endOn					=	$("#txtEndOn").val();
							var tax1					=	$("#txtTax1").val();
							var warehouseID				=	$("#txtWarehouseID").val();								
							var customerID				=	$("#txtCustomerID").val();		
							
							if(!( startOn == "" || endOn == "" ) ){
								fnWaitOpen();
								window.location	= "<?php echo base_url(); ?>/app_sales_report/sales_summary/viewReport/true/startOn/"+
										startOn+"/endOn/"+endOn+
										"/tax1/"+tax1+
										"/warehouseID/"+warehouseID+
										"/customerID/"+customerID;
							}
							else{
								fnShowNotification("Completar los Parametros","error");
							}
						});
					});		

					$(document).on("click","#btnClearCustomer",function(){
								$("#txtCustomerID").val("0");
								$("#txtCustomerDescription").val("TODOS");
					});
					
					$(document).on("click","#btnSearchCustomer",function(){
						//Redireccion pantalla
						var url_redirect		= "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_la_factura";			
						url_redirect 			= encodeURIComponent(url_redirect);
						
						
						var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_ALL_PAGINATED/true/empty/true/"+url_redirect+"/1/1/<?php echo $objParameterCantidadItemPoup; ?>/";
						window.open(url_request,"MsgWindow","width=900,height=450");
						window.onCompleteCustomer = onCompleteCustomer; 
					});	
					
					function onCompleteCustomer(objResponse){
						console.info("CALL onCompleteCustomer");						
						if(objResponse !== undefined)
						{
							var entityID = objResponse[0][1];
							$("#txtCustomerID").val(objResponse[0][1]);
							$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);	
							
						}
					}
					
					
	
				</script>