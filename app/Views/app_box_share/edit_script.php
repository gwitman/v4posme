<!-- ./ page heading -->
<script>
	var objListaCustomerCredit  			= [];
	var varUseMobile						= '<?php echo $useMobile; ?>';
	var varUrlPrinter						= '<?php echo $urlPrinterDocument; ?>';
	var varUrlPrinterInvoiceCancel			= '<?php echo $urlPrinterDocumentInvoiceCancel; ?>';
	var varShareInvoiceByInvoice 			= '<?php echo $objParameterShareInvoiceByInvoice; ?>';
	var varShareMountDefaultOfAmortization 	= '<?php echo getBehavio($company->type,"app_box_share","javscriptVariable_varShareMountDefaultOfAmortization",""); ?>';
	var varPrinterOnlyFormat				= '<?php echo getBehavio($company->type,"app_box_share","javscriptVariable_varPrinterOnlyFormat",""); ?>';
	var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';
	var varUrlPrinterBase					= "<?php echo base_url(); ?>/"+varUrlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/";
</script>
<?php echo view('app_box_share/share_v2_common'); ?>
<script>
	$(document).ready(function(){
		 abv2InitDate(false);
		 abv2BindCommon();

		//Cargar los creditos del cliente (sin cambiar el saldo guardado)
		abv2LoadCustomerBalance(false);

		//Buscar el Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			if($("#txtCurrencyID").val() == ""){
				fnShowNotification("Selecciona la moneda","error");
				return;
			}
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
			abv2OpenSearch(url_request, "Buscar cliente", "onCompleteCustomer", onCompleteCustomer, "width=900,height=450");
		});

		//Buscar el Gestor de Cobro
		$(document).on("click","#btnSearchEmployee",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_COLLECTOR/true/empty/false/not_redirect_when_empty";
			abv2OpenSearch(url_request, "Buscar cobrador", "onCompleteEmployee", onCompleteEmployee, "width=900,height=450");
		});

		//Eliminar el Gestor de Cobro
		$(document).on("click","#btnClearEmployee",function(){
			$("#txtEmployeeID").val("");
			$("#txtEmployeeDescription").val("");
		});

		//Imprimir Documento
		$(document).on("click","#btnPrinterIndividual",function(e){
			e.preventDefault();
			window.open(varUrlPrinterBase + "Individuales", '_blank');
			$('#modalDialogPrinterV2').modal('hide');
		});
		$(document).on("click","#btnPrinterGeneral",function(e){
			e.preventDefault();
			window.open(varUrlPrinterBase + "Generales", '_blank');
			$('#modalDialogPrinterV2').modal('hide');
		});
		$(document).on("click","#btnPrinterBasico",function(e){
			e.preventDefault();
			window.open(varUrlPrinterBase + "Basico", '_blank');
			$('#modalDialogPrinterV2').modal('hide');
		});
		$(document).on("click","#btnPrinter",function(e){
			e.preventDefault();
			if(varPrinterOnlyFormat == "true")
				window.open(varUrlPrinterBase + "Individuales", '_blank');
			else
				$("#modalDialogPrinterV2").modal("show");
		});
		$(document).on("click","#btnPrinterInvoiceCancel",function(e){
			e.preventDefault();
			window.open("<?php echo base_url(); ?>/"+varUrlPrinterInvoiceCancel+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>/saldos/Individuales", '_blank');
		});

		//Guardar
		$(document).on("click","#btnAcept",function(e){
			e.preventDefault();
			$( "#form-new-invoice" ).attr("method","POST");
			$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_share/save/edit");

			if(validateForm()){
				fnWaitOpen();
				$( "#form-new-invoice" ).submit();
			}
		});

		//Agregar factura
		$(document).on("click","#btnNewShare",function(e){
			e.preventDefault();
			if($("#txtCustomerID").val() == ""){
				fnShowNotification("Selecciona el cliente","error");
				return;
			}
			if($("#txtCurrencyID").val() == ""){
				fnShowNotification("Selecciona la moneda","error");
				return;
			}
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentCustomerCreditDocument->componentID; ?>/onCompleteNewShare/SELECCIONAR_DOCUMENTOS_DE_CREDITO_PAGINATED/true/"+encodeURI("{\"entityID\"|\""+ $("#txtCustomerID").val() +"\",\"currencyID\"|\""+ $("#txtCurrencyID").val() +"\"}") + "/false/not_redirect_when_empty/1/1/500/";
			abv2OpenSearch(url_request, "Agregar factura", "onCompleteNewShare", onCompleteNewShare, "width=1585,height=795");
		});

		//Eliminar el abono
		$(document).on("click","#btnDelete",function(e){
			e.preventDefault();
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_box_share/delete",
					data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
					success:function(data){
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_box_share/index";
						}
					},
					error:function(){
						fnWaitClose();
						fnShowNotification("No se pudo eliminar. Revisa tu conexion e intenta de nuevo","error");
					}
				});
			});
		});

		$(document).on("click","#btnClickArchivo",function(){
			window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
		});

		//Ajustar el cambio y el resumen con los valores guardados
		updateCalculateChange();
	});

	function onCompleteEmployee(objResponse){
		console.info("CALL onCompleteEmployee");
		if(!objResponse || !objResponse.length || !objResponse[0]){
			fnShowNotification("Selecciona un cobrador","error");
			return;
		}
		$("#txtEmployeeID").val(objResponse[0][1]);
		$("#txtEmployeeDescription").val(objResponse[0][2] + " / " + objResponse[0][3]);
	}
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
		onCompleteCustomerCommon(objResponse);
	}

	function validateForm(){
		var result 				= abv2ValidateCommon();
		var timerNotification 	= 15000;

		//Si este valor esta en true, se tiene que seleccionar una factura a la vez para ir abonando una a una
		if( varShareInvoiceByInvoice == 'true')
		{
			$("#body_tb_transaction_master_detail .row_razon").each(function(index,row){
				var saldoInicial = abv2Num($(row).find(".txtDetailShareReference2").first().text());
				var amountShare  = abv2Num($(row).find(".txtDetailShare").first().val());
				if(amountShare > (saldoInicial + 2))
				{
					fnShowNotification("El monto del abono en la factura " + $(row).find("#txtDocument").text() + " es mayor que el saldo","error",timerNotification);
					result = false;
				}
			});
		}

		return result;
	}

	//Se conserva por compatibilidad con personalizaciones existentes
	function refreschChecked(){
		abv2Mask($('.abv2 .txt-numeric'));
	}
	function onCompletePantalla(){
	}
</script>
