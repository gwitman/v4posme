<script>
	$(document).ready(function(){	
		$(document).on("click","#btnView",function(){
			window.open("<?php echo base_url(); ?>/core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback; 
		});		
		$(document).on("click","#btnEdit",function(){
		
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);			
				if (data == null)
				return;
				
				fnWaitOpen();				
				window.location	= "<?php echo base_url(); ?>/app_inventory_item/edit/companyID/"+data[0]+"/itemID/"+data[1];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		}); 
		$(document).on("click","#btnPrinter",function(){
		
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);			
				if (data == null)
				return;
				
				var url = "<?php echo base_url(); ?>/app_inventory_item/printerBarCode/companyID/"+data[0]+"/itemID/"+data[1];
				window.open(url, "_blank");								
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		}); 
		$(document).on("click","#btnSearchItem",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_inventory_item/searchItem",
						data 		: {itemNumber : $("#txtSearchItem").val() },
						success:function(data){
							console.info("complete delete success");
							fnWaitClose();
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{		
								window.location = "<?php echo base_url(); ?>/app_inventory_item/edit/companyID/"+data.companyID+"/itemID/"+data.itemID;
							}
						},
						error:function(xhr,data){	
							console.info("complete delete error");									
							fnWaitClose();
							fnShowNotification("Error 505","error");
						}
					});
		});		
		$(document).on("click","#btnEliminar",function(){
		
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);	
				if(data == null)
				return;
				
				fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_inventory_item/delete",
						data 		: {companyID : data[0], itemID :data[1]},
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
			window.location	= "<?php echo base_url(); ?>/app_inventory_item/add";
		});
	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_inventory_item/index/"+dataViewID;   
	}					
</script>
