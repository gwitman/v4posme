<!-- ./ page heading -->
<script>		
	var objListaCustomerCredit  = {};
	var varUrlPrinter			= '<?php echo $urlPrinterDocument; ?>';
	$(document).ready(function(){					
		 $('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
		 $("#txtDate").datepicker("update");
		 $('.txt-numeric').mask('000,000.00', {reverse: true});
		 
		 
		//Buscar los Creditos del Cliente
		fnWaitOpen();		
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
			data 		: {customerID : $("#txtCustomerID").val()  },
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
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/true/empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCustomer = onCompleteCustomer; 
		});		
		//Buscar el Gestor de Cobro
		$(document).on("click","#btnSearchEmployee",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_COLLECTOR/true/empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee; 
		});								
	
		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
					$("#txtCustomerID").val("");
					$("#txtCustomerDescription").val("");
					$("#txtBalanceStart").val("0.00");
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
		var objectParameterButtoms = {};
		objectParameterButtoms.Individual=function(){
				fnWaitOpen();
				window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Individuales", '_blank');
				fnWaitClose();
				$(this).dialog("close");
		};
		objectParameterButtoms.General=function(){
				fnWaitOpen();
				window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Generales", '_blank');
				fnWaitClose();
				$(this).dialog("close");
		};
		objectParameterButtoms.Basico=function(){
				fnWaitOpen();
				window.open("<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Basico", '_blank');
				fnWaitClose();
				$(this).dialog("close");
		};	
		$("#modalDialogOpenPrimter").dialog({
				autoOpen: false,
				modal: true,
				width:520,
				dialogClass: "dialog",
				buttons: objectParameterButtoms
		});
		$(document).on("click","#btnPrinter",function(){				
			$("#modalDialogOpenPrimter").dialog("open");
			return
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
			
			var url_request 			= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomerCreditDocument->componentID; ?>/onCompleteNewShare/SELECCIONAR_DOCUMENTOS_DE_CREDITO/true/"+encodeURI("{\"entityID\"|\""+$("#txtCustomerID").val()+"\"}");
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
		$(document).on("change","#txtReceiptAmount",function(){
			updateCalculateChange();
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
	
	function updateCalculateChange(){
		console.info("updateCalculateChange");
		var i = fnFormatFloat($("#txtTotal").val());
		var x = fnFormatFloat($("#txtReceiptAmount").val());
		$("#txtChangeAmount").val(fnFormatNumber((x-i),2));
	}
	function onCompleteEmployee(objResponse){
		console.info("CALL onCompleteEmployee");
	
		var entityID = objResponse[1];
		$("#txtEmployeeID").val(objResponse[1]);
		$("#txtEmployeeDescription").val(objResponse[2] + " / " + objResponse[3]);
		
	}
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
	
		var entityID = objResponse[1];
		$("#txtCustomerID").val(objResponse[1]);
		$("#txtCustomerDescription").val(objResponse[2] + " " + objResponse[3] + " / " + objResponse[4]);
		
		fnWaitOpen();		
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
			data 		: {customerID : $("#txtCustomerID").val()  },
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
		jLinq.from(objListaCustomerCredit).where(function(obj){ return obj.documentNumber == objResponse[4]}).select()[0];
		objBalancesDocument.balance = fnFormatNumber(objBalancesDocument.balance,2);
		
		var objRow 						= {};
		objRow.checked 					= false;
		objRow.transactionMasterDetail 	= 0;
		objRow.companyID				= objResponse[0];	/*companyID*/
		objRow.entityID 				= objResponse[1];	/*entityID*/
		objRow.customerCreditDocumentID = objResponse[2];	/*customerCreditDocumentID*/
		objRow.creditAmortizationID 	= objResponse[3];	/*creditAmortizationID*/
		objRow.doc 						= objResponse[4];	/*Doc*/						
		objRow.docMonto 				= 0;
		objRow.abonoFecha 				= objResponse[5];	/*AbonoFecha*/
		objRow.abonoCuota 				= fnFormatNumber(fnFormatFloat(objResponse[6]),2);	/*AbonoCuota*/
		objRow.abonoFaltante 			= fnFormatNumber(fnFormatFloat(objResponse[7]),2);	/*AbonoFaltante*/
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
		$(".row_razon").each(function(obj,i){  
			var saldoInicial =  $($(i).find(".txtDetailShareReference2")[0]).text() ;
			var amountShare = $($(i).find(".txtDetailShare")[0]).val();
			saldoInicial = fnFormatFloat(saldoInicial);
			amountShare = fnFormatFloat(amountShare);
			if(amountShare > saldoInicial){
				fnShowNotification("El monto del abono en la factura es mayor quel saldo","error",timerNotification);
				result = false;
			}
		});
		
		return result;
	}
	
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		//$('.txtDebit').mask('000,000.00', {reverse: true});
		//$('.txtCredit').mask('000,000.00', {reverse: true});
		$('.txt-numeric').mask('000,000.00', {reverse: true});
	}
	
</script>