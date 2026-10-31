<script>
	/* ==========================================================
	   app_box_sharecapital v2 - funciones compartidas de nuevo y edicion
	   Requiere: core_template/posme_v2_common y, antes, las variables
	   varUseMobile, varParameterCantidadItemPoup, varComponentItemID,
	   varComponentCustomerCreditDocumentID y varIsEditMode
	   ========================================================== */

	/* ---------------- Resumen ---------------- */
	function capRefreshDetailState(){
		var cantidad = $("#body_tb_transaction_master_detail tr.row_razon").length;
		$("#pv2DetailCount, #pv2KpiCount").text(cantidad);
		$("#pv2EmptyDetail").toggle(cantidad == 0);
		$("#pv2DetailWrap").toggle(cantidad > 0);

		//Saldo de los documentos que tienen saldo registrado
		var saldo = 0;
		$("#body_tb_transaction_master_detail input.txtDetailShare").each(function(i,obj){
			var balance = $(obj).attr("data-balance");
			if(balance !== undefined && balance !== null && String(balance) != "")
				saldo = saldo + pv2Num(balance);
		});
		$("#pv2KpiBalance").text(pv2Fmt(saldo));
	}
	function capSyncMobileBar(){
		var cambio = pv2Num($("#txtChangeAmount").val());
		$("#pv2KpiTotal, #pv2MbarTotal").text(pv2Fmt($("#txtTotal").val()));
		$("#pv2MbarChange").text(pv2Fmt(cambio)).toggleClass("is-negative", cambio < 0);
		$(".pv2-change").toggleClass("is-negative", cambio < 0);
	}
	function updateSummary(){
		console.info("updateSummary");
		var total = 0;
		$("#body_tb_transaction_master_detail input.txtDetailShare").each(function(index,obj){
			total = total + pv2Num($(obj).val());
		});
		$("#txtTotal").val(pv2Fmt(total));
		capRefreshDetailState();
		capSyncMobileBar();
	}
	function updateCalculateChange(){
		console.info("updateCalculateChange");
		var i = pv2Num($("#txtTotal").val());
		var x = pv2Num($("#txtReceiptAmount").val());
		$("#txtChangeAmount").val(pv2Fmt(x-i));
		capSyncMobileBar();
	}

	/* ---------------- Cliente ---------------- */
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
		if(!objResponse || !objResponse.length || !objResponse[0]){
			fnShowNotification("Seleccione el cliente","error");
			return;
		}
		$("#txtCustomerID").val(objResponse[0][1]);
		$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
	}

	/* ---------------- Detalle ---------------- */
	function onCompleteNewShare(objResponse){
		console.info("CALL onCompleteNewShare");
		if(!objResponse || !objResponse.length || !objResponse[0]){
			fnShowNotification("Seleccione el documento","error");
			return;
		}

		var objRow 						= {};
		objRow.checked 					= false;
		objRow.transactionMasterDetail 	= 0;
		objRow.customerCreditDocumentID = objResponse[0][0];	/*customerCreditDocumentID*/
		objRow.doc 						= objResponse[0][1];	/*Doc*/
		objRow.balance					= $("<div>" + objResponse[0][3] + "</div>").text();	/*Saldo*/

		//Validar si esta el item
		for(var i = 0 ; i < $(".classDetailItem").length; i++){
				var x  = $(($(".classDetailItem")[i])).val();
				var y  = objRow.customerCreditDocumentID;

				if(x == y){
					fnShowNotification("El Documento ya esta agregado","error");
					return;
				}
		}

		var tmpl = $($.trim($("#tmpl_row_document").html()));
		tmpl.find("#txtDetailCustomerCreditDocumentID").attr("value",objRow.customerCreditDocumentID);
		tmpl.find("#txtDetailTransactionDetailID").attr("value",objRow.transactionMasterDetail);
		tmpl.find("#txtDocument").text(objRow.doc);
		tmpl.find("#txtBalanceShare").text(pv2Fmt(objRow.balance));
		//Saldo normalizado antes de la mascara: valor formateado y data-balance sin comas
		tmpl.find("#txtDetailShare").attr("value",pv2Fmt(objRow.balance));
		tmpl.find("#txtDetailShare").attr("data-balance",pv2Plain(objRow.balance));

		$("#body_tb_transaction_master_detail").append(tmpl);
		refreschChecked(tmpl);
		updateSummary();
		updateCalculateChange();
	}

	//Devuelve {documento,monto,saldo} si el abono supera el saldo; null si no aplica o no hay saldo registrado
	function fnGetAbonoMayorSaldo(obj){
		var balance = $(obj).attr("data-balance");
		if(balance === undefined || balance === null || String(balance) == "")
			return null;

		var saldo 	= fnFormatFloat(String(balance));
		var monto 	= fnFormatFloat(String($(obj).val()));
		if(isNaN(saldo) || isNaN(monto))
			return null;

		if(monto > saldo + 0.005)
			return { documento : $(obj).closest("tr").find("#txtDocument").text(), monto : monto, saldo : saldo };

		return null;
	}

	//Marca visualmente (BS3 has-error) el monto que supera el saldo, sin bloquear la escritura
	function fnMarcarAbonoMayorSaldo(obj){
		if(fnGetAbonoMayorSaldo(obj) != null){
			$(obj).closest("td").addClass("has-error");
			$(obj).closest("tr").addClass("has-error");
		}
		else{
			$(obj).closest("td").removeClass("has-error");
			$(obj).closest("tr").removeClass("has-error");
		}
	}

	//Solo se aplica uniform al checkbox de la fila nueva (no a los combos) y la mascara a los montos
	function refreschChecked($row){
		if($row && $row.length){
			$row.find("input[type='checkbox']").uniform();
			pv2Mask($row.find(".txt-numeric"));
			return;
		}
		pv2Mask($('.pv2 .txt-numeric'));
	}

	/* ---------------- Validaciones comunes ---------------- */
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

		//Validar que el abono de cada documento no supere su saldo
		if(result){
			$("input.txtDetailShare").each(function(index,obj){
				var objValidacion = fnGetAbonoMayorSaldo(obj);
				if(objValidacion != null){
					fnShowNotification("El abono del documento " + objValidacion.documento + " (" + pv2Fmt(objValidacion.monto) + ") es mayor a su saldo (" + pv2Fmt(objValidacion.saldo) + ")","error",timerNotification);
					$(obj).closest("td").addClass("has-error");
					$(obj).closest("tr").addClass("has-error");
					pv2FocusField(obj);
					result = false;
					return false;
				}
			});
		}

		return result;
	}

	/* ---------------- Eventos comunes de las dos pantallas ---------------- */
	function capBindCommon(){
		pv2MobileSelects("#txtStatusID");
		pv2Mask($('.pv2 .txt-numeric'));
		pv2BindMobileBar();

		//Buscar el Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/"+varComponentItemID+"/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
			pv2OpenSearch(url_request, "Buscar cliente", "onCompleteCustomer", onCompleteCustomer, "width=900,height=450");
		});

		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
			$("#txtCustomerID").val("");
			$("#txtCustomerDescription").val("");
		});

		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
			fnWaitOpen();
		});

		//Agregar documento
		$(document).on("click","#btnNewShare",function(e){
			e.preventDefault();
			if($("#txtCustomerID").val() == ""){
				fnShowNotification("Seleccione el cliente","error");
				return;
			}

			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/"+varComponentCustomerCreditDocumentID+"/onCompleteNewShare/SELECCIONAR_DOCUMENTOS_DE_CREDITO_SUMMARY/true/"+encodeURI("{\"entityID\"|\""+$("#txtCustomerID").val()+"\"}") + "/false/not_redirect_when_empty";
			pv2OpenSearch(url_request, "Agregar documento", "onCompleteNewShare", onCompleteNewShare, "width=1585,height=795");
		});

		//Quitar los documentos marcados
		$(document).on("click","#btnDeleteShare",function(e){
			e.preventDefault();
			console.info("btnDeleteShare");
			var marcadas = $("#body_tb_transaction_master_detail .txtCheckedIsActive:checked");
			if(marcadas.length == 0){
				fnShowNotification("Marque los documentos que desea quitar","error");
				return;
			}
			marcadas.closest("tr").remove();
			updateSummary();
			updateCalculateChange();
		});

		//Quitar un documento
		$(document).on("click",".btnShareRemoveRow",function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
			updateSummary();
			updateCalculateChange();
		});

		//Recalcular mientras se escribe
		$(document).on("keyup change","input.txtDetailShare",function(){
			updateSummary();
			updateCalculateChange();
			fnMarcarAbonoMayorSaldo(this);
		});
		$(document).on("keyup change","#txtReceiptAmount",function(){
			updateCalculateChange();
		});

		//Enter no envia el formulario
		$(document).on("keydown","#form-new-invoice input",function(e){
			if(e.key === "Enter" || e.keyCode === 13) e.preventDefault();
		});

		//Estado inicial
		$("#body_tb_transaction_master_detail input.txtDetailShare").each(function(i,obj){
			fnMarcarAbonoMayorSaldo(obj);
		});
		capRefreshDetailState();
		updateCalculateChange();
	}
</script>
