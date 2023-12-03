<script>
	var objIsMobile							= '<?php echo $useMobile; ?>';	
	var objParameterPantallaParaFacturar 	= '<?php echo $objParameterPantallaParaFacturar; ?>';	
	var urlPreview 							= "<?php echo base_url(); ?>/app_invoice_billing/viewRegisterFormatoPaginaNormal80mmOpcion1/companyID/2/transactionID/19/transactionMasterID/";
	
	$(document).ready(function(){	
	
		if(objIsMobile == "1")
		{
			
			var availWidth 	= window.screen.availWidth;
			availWidth		= availWidth - 65;
			$(".dataTables_paginate.paging_bootstrap.pagination").remove();
			$("#ListView_length").remove();
			$("#ListView_filter").remove();
			$("#ListView").css("width",availWidth+"px");
			
		}
		
		
		$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
		
		$(document).on("click","#btnView",function(){
			window.open("<?php echo base_url(); ?>/core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback; 
		});	

		$(document).on("click",".btnVerPrevio",function(){			
				
			//ver el iframe
			
			var data 				= objTableListView.fnGetData(objRowTableListView);		
			var transactionMasterID = $(this).data("transactionmasterid");
			$(".iframePreviewPdf").css("display","none");
			$("#iframeWork"+transactionMasterID).css("display","block");
			
			
			
			//--wgonzalez//solicitar la factura
			//--wgonzalez$.ajax({									
			//--wgonzalez	cache       : false,
			//--wgonzalez	dataType    : 'json',
			//--wgonzalez	type        : 'POST',
			//--wgonzalez	url  		: "<?php echo base_url(); ?>/app_invoice_billing/getTransactionMaster",
			//--wgonzalez	data 		: {companyID : 2 , transactionID : 19 , transactionMasterID : transactionMasterID  },
			//--wgonzalez	success:function(data){
			//--wgonzalez		console.info("complete delete success");
			//--wgonzalez		fnWaitClose();
			//--wgonzalez		if(data.error){
			//--wgonzalez			fnShowNotification(data.message,"error");
			//--wgonzalez		}
			//--wgonzalez		debugger;
			//--wgonzalez		
			//--wgonzalez		
			//--wgonzalez	},
			//--wgonzalez	error:function(xhr,data){	
			//--wgonzalez		console.info("complete delete error");									
			//--wgonzalez		fnWaitClose();
			//--wgonzalez		fnShowNotification("Error 505","error");
			//--wgonzalez	}
			//--wgonzalez});
			
			
		});	
		
		$(document).on("click","#btnEdit",function(){
		
			if(objRowTableListView != undefined){
				fnWaitOpen();
				var data 		= objTableListView.fnGetData(objRowTableListView);		

				if(objParameterPantallaParaFacturar == "-")	
					window.location	= "<?php echo base_url(); ?>/app_invoice_billing/edit/companyID/"+data[0]+"/transactionID/"+data[1]+"/transactionMasterID/"+data[2];
				else
					window.location	= "<?php echo base_url(); ?>/app_invoice_billing/"+objParameterPantallaParaFacturar+"/companyID/"+data[0]+"/transactionID/"+data[1]+"/transactionMasterID/"+data[2];					
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		}); 
		$(document).on("click","#btnSearchTransaction",function(){
					fnWaitOpen();
					var transactionNumber 	= $("#txtSearchTransaction").val() ;
					var fecha 				= $("#txtStartOn").val();
					
					
					if(transactionNumber != "") {
						$.ajax({									
							cache       : false,
							dataType    : 'json',
							type        : 'POST',
							url  		: "<?php echo base_url(); ?>/app_invoice_billing/searchTransactionMaster",
							data 		: {transactionNumber : transactionNumber },
							success:function(data){
								console.info("complete delete success");
								fnWaitClose();
								if(data.error){
									fnShowNotification(data.message,"error");
								}
								else{		
									window.location = "<?php echo base_url(); ?>/app_invoice_billing/edit/companyID/"+data.companyID+"/transactionID/"+data.transactionID+"/transactionMasterID/"+data.transactionMasterID;
								}
							},
							error:function(xhr,data){	
								console.info("complete delete error");									
								fnWaitClose();
								fnShowNotification("Error 505","error");
							}
						});
					}
					else{
						window.location = "<?php echo base_url(); ?>/app_invoice_billing/index/dataViewID/"+null+"/fecha/"+fecha;   
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
						url  		: "<?php echo base_url(); ?>/app_invoice_billing/delete",
						data 		: {companyID : data[0], transactionID :data[1],transactionMasterID : data[2] },
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
			
			if(objParameterPantallaParaFacturar == "-")
				window.location	= "<?php echo base_url(); ?>/app_invoice_billing/add";
			else 
				window.location	= "<?php echo base_url(); ?>/app_invoice_billing/"+objParameterPantallaParaFacturar+"/companyID/0/transactionID/19/transactionMasterID/0";
			
		});
		
	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_invoice_billing/index/"+dataViewID;   
	}					
</script>
