<!-- ./ page heading -->
<script>		
	var objListaCustomerCredit  			= {};
	var varUseMobile						= '<?php echo $useMobile; ?>';
	var varUrlPrinter						= '<?php echo $urlPrinterDocument; ?>';
	var varUrlPrinterInvoiceCancel			= '<?php echo $urlPrinterDocumentInvoiceCancel; ?>';
	var varShareInvoiceByInvoice 			= '<?php echo $objParameterShareInvoiceByInvoice; ?>';
	var varShareMountDefaultOfAmortization 	= '<?php echo getBehavio($company->type,"app_box_share","javscriptVariable_varShareMountDefaultOfAmortization",""); ?>';
	var varPrinterOnlyFormat				= '<?php echo getBehavio($company->type,"app_box_share","javscriptVariable_varPrinterOnlyFormat",""); ?>';
	var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';
	
	$(document).ready(function(){					
		 $('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
		 $("#txtDate").datepicker("update");
		 $('.txt-numeric').mask('000,000.00', {reverse: true});
		 onCompletePantalla();
		 
		//Buscar los Creditos del Cliente
		fnWaitOpen();		
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
			data 		: {customerID : $("#txtCustomerID").val(), currencyID : $("#txtCurrencyID").val()   },
			success		: function(obj,index,event){
				console.info("complete data success getCustomerBalance");
				fnWaitClose();
				console.info(obj);
				objListaCustomerCredit 	= obj.array;
			},
			error:function(xhr,data){	
				console.info("complete data error getCustomerBalance");
				fnWaitClose();
				fnShowNotification("Error 505","error");
			}
		});
		
		
		//Buscar el Cliente
		$(document).on("click","#btnSearchCustomer",function(){
				
			if($("#txtCurrencyID").val() == ""){
				fnShowNotification("Seleccione la moneda","error");
				return;
			}
			
			
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCustomer = onCompleteCustomer; 
		});		
		//Buscar el Gestor de Cobro
		$(document).on("click","#btnSearchEmployee",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_COLLECTOR/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee; 
		});								
	
		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
					$("#txtCustomerID").val("");
					$("#txtCustomerDescription").val("");
					$("#txtBalanceStart").val("0.00");
		});
		$(document).on("click","#btnVerMovement",function(){
		
			if( $("#txtCustomerDescription").val() != "" )
			{
				var customerNumber	= $("#txtCustomerDescription").val().split(" ")[0];
				var url_request 	= "<?php echo base_url(); ?>/app_cxc_report/movement_customer/viewReport/true/customerNumber/"+customerNumber;
				window.open(url_request,"MsgWindow","width=1200,height=450");			
			}
			
		});	
	
		
		//Eliminar el Gestor de Cobro
		$(document).on("click","#btnClearEmployee",function(){
					$("#txtEmployeeID").val("");
					$("#txtEmployeeDescription").val("");
		});
		
		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		
		//Imprimir Documento
		$("#btnPrinterIndividual").click(function(){
			fnWaitOpen();
			window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Individuales", '_blank');
			fnWaitClose();		
			$('#modalDialogPrinterV2').modal('hide');
		});
		
		$("#btnPrinterGeneral").click(function(){
			fnWaitOpen();
			window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Generales", '_blank');
			fnWaitClose();	
			$('#modalDialogPrinterV2').modal('hide');
		});
		$("#btnPrinterBasico").click(function(){
			fnWaitOpen();
			window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Basico", '_blank');
			fnWaitClose();	
			$('#modalDialogPrinterV2').modal('hide');
		});
		
		
		$(document).on("click","#btnPrinter",function(){	

			if(varPrinterOnlyFormat == "true")
			{
				window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Individuales", '_blank');
			}
			else 
			{
				$("#modalDialogPrinterV2").modal("show");
			}
			return
		});
		
		$(document).on("click","#btnPrinterInvoiceCancel",function(){	

			fnWaitOpen();
			window.open("<?php echo base_url(); ?>/"+varUrlPrinterInvoiceCancel+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Individuales", '_blank');
			fnWaitClose();	
			
		});
		
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-invoice" ).attr("method","POST");
				$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_share/save/edit");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-invoice" ).submit();
				}
				
		});
		
		//Nueva factura
		$(document).on("click","#btnNewShare",function(){
		
			if($("#txtCustomerID").val() == ""){
				fnShowNotification("Seleccione el cliente","error");
				return;
			}
			
			if($("#txtCurrencyID").val() == ""){
				fnShowNotification("Seleccione la moneda","error");
				return;
			}
			
			
			var url_request 			= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomerCreditDocument->componentID; ?>/onCompleteNewShare/SELECCIONAR_DOCUMENTOS_DE_CREDITO/true/"+encodeURI("{\"entityID\"|\""+ $("#txtCustomerID").val() +"\",\"currencyID\"|\""+ $("#txtCurrencyID").val() +"\"}") + "/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=1585,height=795");
			window.onCompleteNewShare 	= onCompleteNewShare; 
		});
		//Eliminar factura
		$(document).on("click","#btnDeleteShare",function(){
			console.info("btnDeleteShare");
			$(".txtCheckedIsActive").each(function(i,obj){
					if($(obj).attr("checked") == "checked"){
						$(obj).parents("tr").first().remove();
					}
			});	
			
		});
		
		$(document).on("change","input.txtDetailShare",function(){
			updateSummary();
			updateCalculateChange();
		});
		//Monto
		$(document).on("change","#txtReceiptAmount",function(){
			updateCalculateChange();
		});
		//Monto Dol
		$(document).on("change","#txtReceiptAmountDol",function(){
			updateCalculateChange();
		});
		//Descuento
		$(document).on("change","#txtDiscountAmount",function(){
			updateCalculateChange();
		});
		
		$(document).on("change","#txtMobileEntityID",function(){			
			$("#txtCustomerID").val(	$(this).val()	);
			$("#txtCustomerDescription").val(	$('#txtMobileEntityID').find(":selected").data("name")	);
			
			fnWaitOpen();		
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				url  		: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
				data 		: {customerID : $("#txtCustomerID").val() , currencyID : $("#txtCurrencyID").val()   },
				success		: function(obj,index,event){
					console.info("complete data success getCustomerBalance");
					fnWaitClose();
					console.info(obj);
					objListaCustomerCredit 	= obj.array;
					var saldoTotal 			= 0;				
					objListaCustomerCredit.forEach(function(obj,inl){ saldoTotal = saldoTotal +  fnFormatFloat(obj.remaining,2);});
					
					saldoTotal = fnFormatNumber(saldoTotal,2);
					$("#txtBalanceStart").val(saldoTotal);
					
					
				},
				error:function(xhr,data){	
					console.info("complete data error getCustomerBalance");
					fnWaitClose();
					fnShowNotification("Error 505","error");
				}
			});
			
		});
		$(document).on("click","#btnDelete",function(){							
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_box_share/delete",
					data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_box_share/index";
						}
					},
					error:function(xhr,data){	
						console.info("complete delete error");									
						fnWaitClose();
						fnShowNotification("Error 505","error");
					}
				});
			});
		});
		
		$(document).on("click","#btnClickArchivo",function(){
			window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
		});
		
	});
	
	function updateCalculateChange()
	{
		var currencyID 		= parseFloat(fnFormatFloat($("#txtCurrencyID").val()));
		var exchangeRate 	= parseFloat(fnFormatFloat($("#txtExchangeRate").val()));
		var amountIng		= parseFloat(fnFormatFloat($("#txtReceiptAmount").val()));
		var amountIngDol	= parseFloat(fnFormatFloat($("#txtReceiptAmountDol").val()));
		var amountDiscount	= parseFloat(fnFormatFloat($("#txtDiscountAmount").val()));
		var total			= parseFloat(fnFormatFloat($("#txtTotal").val()));
		var change			= 0;
		debugger;
		
		if(currencyID == "1" /*cordoba*/)
		{
			change = (amountIng + (amountIngDol / exchangeRate) + amountDiscount) - total;
		}
		else 
		{
			change = (amountIng + (amountIngDol * exchangeRate) + amountDiscount) - total;
		}
		
		$("#txtChangeAmount").val( fnFormatNumber(change,2) );
	}
	function onCompleteEmployee(objResponse){
		console.info("CALL onCompleteEmployee");
	
		var entityID = objResponse[0][1];
		$("#txtEmployeeID").val(objResponse[0][1]);
		$("#txtEmployeeDescription").val(objResponse[0][2] + " / " + objResponse[0][3]);
		
	}
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
	
		var entityID = objResponse[0][1];
		$("#txtCustomerID").val(objResponse[0][1]);
		$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
		
		
			
		fnWaitOpen();		
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
			data 		: {customerID : $("#txtCustomerID").val() , currencyID : $("#txtCurrencyID").val()  },
			success		: function(obj,index,event){
				console.info("complete data success getCustomerBalance");
				fnWaitClose();
				console.info(obj);
				objListaCustomerCredit 	= obj.array;
				var saldoTotal 			= 0;				
				objListaCustomerCredit.forEach(function(obj,inl){ saldoTotal = saldoTotal + fnFormatFloat(obj.balance);});
				saldoTotal = fnFormatNumber(saldoTotal,2);
				$("#txtBalanceStart").val(saldoTotal);
				
			},
			error:function(xhr,data){	
				console.info("complete data error getCustomerBalance");
				fnWaitClose();
				fnShowNotification("Error 505","error");
			}
		});
							
	}
	function updateSummary(){
		console.info("updateSummary");
		var total = 0;
		$(".txtDetailShare").each(function(index,obj){
			total = total + fnFormatFloat($(obj).val());
		});
		total = fnFormatNumber(total,2);
		$("#txtTotal").val(total);
		var saldoFinal = fnFormatFloat($("#txtBalanceStart").val()) - total ;
		saldoFinal = fnFormatNumber(saldoFinal,2);
		$("#txtBalanceFinish").val(saldoFinal);
	}
	function onCompleteNewShare(objResponse){
		console.info("CALL onCompleteNewShare");	
		
		var objBalancesDocument = 
		jLinq.from(objListaCustomerCredit).where(function(obj){ return obj.documentNumber == objResponse[0][4]}).select()[0];
		objBalancesDocument.balance = fnFormatNumber(objBalancesDocument.balance,2);
		
		var objRow 						= {};
		objRow.checked 					= false;
		objRow.transactionMasterDetail 	= 0;
		objRow.companyID				= objResponse[0][0];	/*companyID*/
		objRow.entityID 				= objResponse[0][1];	/*entityID*/
		objRow.customerCreditDocumentID = objResponse[0][2];	/*customerCreditDocumentID*/
		objRow.creditAmortizationID 	= objResponse[0][3];	/*creditAmortizationID*/
		objRow.doc 						= objResponse[0][4];	/*Doc*/						
		objRow.docMonto 				= 0;
		objRow.abonoFecha 				= objResponse[0][5];	/*AbonoFecha*/
		objRow.abonoCuota 				= fnFormatNumber(fnFormatFloat(objResponse[0][6]),2);	/*AbonoCuota*/
		
		
		if(varShareMountDefaultOfAmortization == "true")
		{
			objRow.abonoFaltante 			= fnFormatNumber(fnFormatFloat($(objResponse[0][7]).text() ),2);			/*AbonoFaltante*/
		}
		else
		{
			objRow.abonoFaltante 			= "";
		}
		
		objRow.abonoAtraso 				= 0;
		objRow.abonoEstado 				= 0;
		
		//Validar si esta el item
		for(var i = 0 ; i < $(".classDetailItem").length; i++){
				var x  = $(($(".classDetailItem")[i])).val(); 
				var y  = objRow.customerCreditDocumentID;
				
				if(x == y){
					fnShowNotification("El Documento ya esta agregado","error");
					return;
				}
				
		}
		
		
		var tmpl = $($("#tmpl_row_document").html());
		
		tmpl.find("#txtDetailCustomerCreditDocumentID").attr("value",objRow.customerCreditDocumentID);		
		tmpl.find("#txtDetailTransactionDetailID").attr("value",0);		
		tmpl.find("#txtDetailTransactionDetailDocument").attr("value",objRow.doc);
		tmpl.find("#txtDetailTransactionDetailFecha").attr("value",'');
		tmpl.find("#txtDetailAmortizationID").attr("value",objRow.creditAmortizationID);				
		tmpl.find("#txtDocument").text(objRow.doc);
		tmpl.find("#txtFecha").text('');
		tmpl.find("#txtBalanceStartShare").text(objBalancesDocument.balance);
		tmpl.find("#txtDetailBalanceStart").attr("value",objBalancesDocument.balance);
		tmpl.find("#txtDetailShare").attr("value",objRow.abonoFaltante);
		tmpl.find("#txtBalanceFinishShare").text("0.00");
		
		$("#body_tb_transaction_master_detail").append(tmpl);
		refreschChecked();
		
		updateSummary();
		updateCalculateChange();
		onCompletePantalla();
	}
	function fnCompleteGetDocumentInfo(data){
		console.info("fnCompleteGetDocumentInfo");
		console.info(data);
		fnWaitClose();
		
		var row = $(".classDetailItem[value="+data.customerCreditDocumentID+"]").parent().parent();
		
		row.find("#txtDetailTransactionDetailCancel").attr("value",data.cancel);
		row.find("#txtDetailTransactionDetailDay").attr("value",data.diario);
		row.find("#txtDetailTransactionDetailShare").attr("value",data.cuota);
		row.find("#txtOfCancel").text(data.cancel);
		row.find("#txtOfDay").text(data.diario);
		row.find("#txtOfShare").text(data.cuota);
	}
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		//Validar Fecha
		if($("#txtDate").val() == ""){
			fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
			result = false;
		}
		
		//Validar Cliente
		if($("#txtCustomerID").val() == ""){
			fnShowNotification("Seleccionar el Cliente","error",timerNotification);
			result = false;
		}

		if($("#txtDiscountAmount").val() == ""){
			fnShowNotification("Descuento, no puede estar vacio dejar en 0 !!","error",timerNotification);
			result = false;
		}
		
		if($("#txtReceiptAmountDol").val() == ""){
			fnShowNotification("Mont EXT, no puede estar vacio dejar en 0 !!","error",timerNotification);
			result = false;
		}
		
		if($("#txtReceiptAmount").val() == ""){
			fnShowNotification("Monto NAC, no puede estar vacio dejar en 0 !!","error",timerNotification);
			result = false;
		}
		
		if(fnFormatFloat($("#txtChangeAmount").val()) < 0){
			fnShowNotification("Monto insuficiente para aplicar el abono","error",timerNotification);
			result = false;
		}
		
		//Si este valor esta en true, se tiene que seleccionar una factura  a la vez para ir abonoando una una
		if( varShareInvoiceByInvoice == 'true')
		{
			$(".row_razon").each(function(obj,i){  
				var saldoInicial =  $($(i).find(".txtDetailShareReference2")[0]).text() ;
				var amountShare = $($(i).find(".txtDetailShare")[0]).val();
				saldoInicial = fnFormatFloat(saldoInicial);
				amountShare = fnFormatFloat(amountShare);
				if(amountShare  > (saldoInicial + 2) )
				{
					fnShowNotification("El monto del abono en la factura es mayor quel saldo","error",timerNotification);
					result = false;
				}
			});
		}
		
		return result;
	}
	
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		//$('.txtDebit').mask('000,000.00', {reverse: true});
		//$('.txtCredit').mask('000,000.00', {reverse: true});
		$('.txt-numeric').mask('000,000.00', {reverse: true});
	}
	
	function onCompletePantalla(){
		if(varUseMobile == "1" ){		   
		   $("#tb_transaction_master_detail th").css("display","none");
		   $("#tb_transaction_master_detail td").css("display","block");		   
	    }
	}

</script>