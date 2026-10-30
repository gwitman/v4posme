<script>
	/* ==========================================================
	   app_box_share v2 - funciones compartidas de nuevo y edicion
	   Requiere que antes esten definidas: varUseMobile y objListaCustomerCredit
	   ========================================================== */

	//Convierte cualquier valor (texto con comas, vacio, numero) a numero. Nunca devuelve NaN
	function abv2Num(value){
		if(value === null || value === undefined) return 0;
		if(typeof value === "number") return isNaN(value) ? 0 : value;
		var texto  = String(value).replace(/,/g,"").replace(/[^0-9.\-]/g,"");
		var numero = parseFloat(texto);
		return isNaN(numero) ? 0 : numero;
	}
	//Formato sin separador de miles (para los campos que se envian al servidor)
	function abv2Plain(value){
		return (Math.round(abv2Num(value) * 100) / 100).toFixed(2);
	}
	//Formato con separador de miles (para mostrar)
	function abv2Fmt(value){
		var numero = Math.round(abv2Num(value) * 100) / 100;
		var partes = Math.abs(numero).toFixed(2).split(".");
		partes[0]  = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return (numero < 0 ? "-" : "") + partes[0] + "." + partes[1];
	}
	function abv2IsMobile(){
		var pantallaChica = window.matchMedia ? window.matchMedia("(max-width: 767px)").matches : (window.innerWidth < 768);
		return varUseMobile == "1" || pantallaChica;
	}
	function abv2Mask($elements){
		$elements.mask('000,000,000.00', {reverse: true});
	}

	/* ---------------- Controles segun el dispositivo ---------------- */
	//En telefono: Estado y Moneda como combo normal (sin select2)
	function abv2SetupMobileSelects(){
		if(!abv2IsMobile()) return;
		$("#txtStatusID, #txtCurrencyID").each(function(i,obj){
			var $select = $(obj);
			try{
				if($select.data("select2")) $select.select2("destroy");
			}
			catch(ex){
				console.info("abv2SetupMobileSelects", ex);
			}
			$select.removeClass("select2").show();
		});
	}
	//Fecha: calendario del tema en PC, selector nativo del telefono en movil
	function abv2InitDate(setToday){
		var input = document.getElementById("txtDate");
		if(!input) return;

		var valor = $.trim(input.value);
		if(setToday || valor == "") valor = moment().format("YYYY-MM-DD");
		valor = valor.substring(0,10);

		if(abv2IsMobile()){
			try { input.type = "date"; } catch(ex) { console.info("abv2InitDate", ex); }
			input.value = valor;
			$(input).closest(".input-group").find(".input-group-addon").hide();
			$(input).closest(".input-group").removeClass("input-group date");
			return;
		}

		$(input).datepicker({format:"yyyy-mm-dd"});
		$(input).val(valor);
		$(input).datepicker("update");
	}

	/* ---------------- Busqueda (ventana emergente en PC, ventana interna en telefono) ---------------- */
	function abv2EnsureSearchModal(){
		var $modal = $("#abv2SearchModal");
		if($modal.length > 0) return $modal;
		$modal = $(
			'<div class="modal fade" tabindex="-1" id="abv2SearchModal" role="dialog">' +
				'<div class="modal-dialog" role="document">' +
					'<div class="modal-content">' +
						'<div class="modal-header">' +
							'<h4 class="modal-title">Buscar</h4>' +
							'<button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon16 i-close-2"></i> Cerrar</button>' +
						'</div>' +
						'<div class="modal-body"></div>' +
					'</div>' +
				'</div>' +
			'</div>'
		);
		$("body").append($modal);
		$modal.on("hidden.bs.modal", function(){ $modal.find(".modal-body").empty(); });
		return $modal;
	}
	function abv2CloseSearch(){
		$("#abv2SearchModal").modal("hide");
	}
	function abv2OpenSearch(url, title, callbackName, callbackFn, popupFeatures){
		if(!abv2IsMobile()){
			window[callbackName] = callbackFn;
			window.open(url, "MsgWindow", popupFeatures);
			return;
		}

		//En telefono: la pagina de busqueda se carga dentro de la misma pantalla.
		//La pagina de busqueda llama window.opener.<callback>(data) y window.close(),
		//por eso se los entregamos desde aqui sin tocar core_view.
		window[callbackName] = function(data){
			try { callbackFn(data); }
			finally { abv2CloseSearch(); }
		};

		var $modal  = abv2EnsureSearchModal();
		var $iframe = $('<iframe title="Buscar"></iframe>');
		$modal.find(".modal-title").text(title);
		$modal.find(".modal-body").empty().append($iframe);
		$iframe.on("load", function(){
			try{
				var ventana		= this.contentWindow;
				ventana.opener	= window;
				ventana.close	= function(){ abv2CloseSearch(); };
			}
			catch(ex){
				console.info("abv2OpenSearch: no se pudo enlazar la busqueda", ex);
			}
		});
		$iframe.attr("src", url);
		$modal.modal("show");
	}

	/* ---------------- Saldos del cliente ---------------- */
	function abv2LoadCustomerBalance(updateBalanceStart){
		if($("#txtCustomerID").val() == "" || $("#txtCurrencyID").val() == "") return;

		fnWaitOpen();
		$.ajax({
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_cxc_api/getCustomerBalance",
			data 		: { customerID : $("#txtCustomerID").val() , currencyID : $("#txtCurrencyID").val() },
			success		: function(obj){
				fnWaitClose();
				if(!obj || obj.error === true){
					objListaCustomerCredit = [];
					fnShowNotification((obj && obj.message) ? obj.message : "No se pudo obtener el saldo del cliente","error");
					return;
				}

				objListaCustomerCredit = obj.array ? obj.array : [];
				if(updateBalanceStart){
					var saldoTotal = 0;
					objListaCustomerCredit.forEach(function(item){ saldoTotal = saldoTotal + abv2Num(item.remaining); });
					$("#txtBalanceStart").val(abv2Fmt(saldoTotal));
					updateSummary();
				}
			},
			error		: function(){
				fnWaitClose();
				fnShowNotification("No se pudo obtener el saldo del cliente. Revisa tu conexion e intenta de nuevo","error");
			}
		});
	}
	function abv2HasRows(){
		return $("#body_tb_transaction_master_detail tr.row_razon").length > 0;
	}
	function abv2ClearRows(message){
		if(!abv2HasRows()) return;
		$("#body_tb_transaction_master_detail tr.row_razon").remove();
		if(message) fnShowNotification(message,"error");
		updateSummary();
		updateCalculateChange();
	}

	/* ---------------- Detalle ---------------- */
	function abv2RecalcRow($row){
		var saldoInicial = abv2Num($row.find("#txtDetailBalanceStart").val());
		var abono		 = abv2Num($row.find(".txtDetailShare").val());
		var saldoFinal	 = saldoInicial - abono;
		$row.find("#txtBalanceFinishShare").text(abv2Fmt(saldoFinal));
		$row.find("#txtDetailBalanceFinish").val(abv2Plain(saldoFinal));
	}
	function abv2RefreshDetailState(){
		var cantidad = $("#body_tb_transaction_master_detail tr.row_razon").length;
		$("#abv2DetailCount").text(cantidad);
		$("#abv2EmptyDetail").toggle(cantidad == 0);
		$("#tb_transaction_master_detail").toggle(cantidad > 0);
	}
	function abv2SyncMobileBar(){
		var cambio = abv2Num($("#txtChangeAmount").val());
		$("#abv2MbarTotal").text(abv2Fmt($("#txtTotal").val()));
		$("#abv2MbarChange").text(abv2Fmt(cambio)).toggleClass("is-negative", cambio < 0);
		$(".abv2-change").toggleClass("is-negative", cambio < 0);
	}

	function updateSummary(){
		var total = 0;
		$("#body_tb_transaction_master_detail .txtDetailShare").each(function(index,obj){
			total = total + abv2Num($(obj).val());
		});
		$("#txtTotal").val(abv2Fmt(total));
		$("#txtBalanceFinish").val(abv2Fmt(abv2Num($("#txtBalanceStart").val()) - total));
		abv2RefreshDetailState();
		abv2SyncMobileBar();
	}
	function updateCalculateChange(){
		var currencyID 		= abv2Num($("#txtCurrencyID").val());
		var exchangeRate 	= abv2Num($("#txtExchangeRate").val());
		var amountIng		= abv2Num($("#txtReceiptAmount").val());
		var amountIngDol	= abv2Num($("#txtReceiptAmountDol").val());
		var amountDiscount	= abv2Num($("#txtDiscountAmount").val());
		var total			= abv2Num($("#txtTotal").val());
		var change			= 0;

		if(exchangeRate <= 0) exchangeRate = 1;

		if(currencyID == 1 /*cordoba*/)
		{
			change = (amountIng + (amountIngDol / exchangeRate) + amountDiscount) - total;
		}
		else
		{
			change = (amountIng + (amountIngDol * exchangeRate) + amountDiscount) - total;
		}

		$("#txtChangeAmount").val(abv2Fmt(change));
		abv2SyncMobileBar();
	}

	//Agrega una factura seleccionada en la busqueda
	function onCompleteNewShare(objResponse){
		console.info("CALL onCompleteNewShare");
		if(!objResponse || !objResponse.length || !objResponse[0]){
			fnShowNotification("Selecciona una factura","error");
			return;
		}

		var documentNumber		= objResponse[0][4];
		var objBalancesDocument = jLinq.from(objListaCustomerCredit || []).where(function(obj){ return obj.documentNumber == documentNumber }).select()[0];
		if(!objBalancesDocument){
			fnShowNotification("No se encontro el saldo de la factura " + documentNumber + ". Vuelve a seleccionar el cliente e intenta de nuevo","error");
			return;
		}

		var objRow 						= {};
		objRow.customerCreditDocumentID = objResponse[0][2];	/*customerCreditDocumentID*/
		objRow.creditAmortizationID 	= objResponse[0][3];	/*creditAmortizationID*/
		objRow.doc 						= documentNumber;		/*Doc*/
		objRow.balance					= abv2Num(objBalancesDocument.remaining);
		objRow.abonoFaltante			= "";

		if(varShareMountDefaultOfAmortization == "true")
		{
			objRow.abonoFaltante 		= abv2Fmt($("<div>" + objResponse[0][7] + "</div>").text());	/*AbonoFaltante*/
		}

		//Validar si esta el item
		var yaExiste = false;
		$(".classDetailItem").each(function(i,obj){
			if($(obj).val() == objRow.customerCreditDocumentID) yaExiste = true;
		});
		if(yaExiste){
			fnShowNotification("La factura " + objRow.doc + " ya esta agregada","error");
			return;
		}

		var tmpl = $($("#tmpl_row_document").html());
		tmpl.find("#txtDetailCustomerCreditDocumentID").attr("value",objRow.customerCreditDocumentID);
		tmpl.find("#txtDetailTransactionDetailID").attr("value",0);
		tmpl.find("#txtDetailTransactionDetailDocument").attr("value",objRow.doc);
		tmpl.find("#txtDetailTransactionDetailFecha").attr("value",'');
		tmpl.find("#txtDetailAmortizationID").attr("value",objRow.creditAmortizationID);
		tmpl.find("#txtDocument").text(objRow.doc);
		tmpl.find("#txtBalanceStartShare").text(abv2Fmt(objRow.balance));
		tmpl.find("#txtDetailBalanceStart").attr("value",abv2Plain(objRow.balance));
		tmpl.find("#txtDetailShare").attr("value",objRow.abonoFaltante);
		$("#body_tb_transaction_master_detail").append(tmpl);

		tmpl.find("input[type='checkbox']").uniform();
		abv2Mask(tmpl.find(".txt-numeric"));
		abv2RecalcRow(tmpl);
		updateSummary();
		updateCalculateChange();
	}
	function fnCompleteGetDocumentInfo(data){
		console.info("fnCompleteGetDocumentInfo");
		fnWaitClose();
	}

	//Validaciones comunes antes de guardar
	function abv2ValidateCommon(){
		var result 				= true;
		var timerNotification 	= 15000;

		//Los montos vacios se toman como 0
		$("#txtDiscountAmount, #txtReceiptAmountDol, #txtReceiptAmount").each(function(i,obj){
			if($.trim($(obj).val()) == "") $(obj).val("0.00");
		});
		updateSummary();
		updateCalculateChange();

		if($("#txtDate").val() == ""){
			fnShowNotification("Establece la fecha del documento","error",timerNotification);
			result = false;
		}
		if($("#txtCustomerID").val() == ""){
			fnShowNotification("Selecciona el cliente","error",timerNotification);
			result = false;
		}
		if($("#txtStatusID").val() == ""){
			fnShowNotification("Selecciona el estado","error",timerNotification);
			result = false;
		}
		if(abv2Num($("#txtChangeAmount").val()) < 0){
			fnShowNotification("Monto insuficiente para aplicar el abono","error",timerNotification);
			result = false;
		}
		return result;
	}

	//Eventos comunes de las dos pantallas
	function abv2BindCommon(){
		abv2SetupMobileSelects();
		abv2Mask($('.abv2 .txt-numeric'));

		//Recalcular mientras se escribe (no solo al salir del campo)
		$(document).on("keyup change blur","input.txtDetailShare",function(){
			abv2RecalcRow($(this).closest("tr"));
			updateSummary();
			updateCalculateChange();
		});
		$(document).on("keyup change blur","#txtReceiptAmount, #txtReceiptAmountDol, #txtDiscountAmount",function(){
			updateCalculateChange();
		});

		//Quitar una factura
		$(document).on("click",".btnShareRemoveRow",function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
			updateSummary();
			updateCalculateChange();
		});

		//Eliminar las facturas marcadas
		$(document).on("click","#btnDeleteShare",function(e){
			e.preventDefault();
			var marcadas = $("#body_tb_transaction_master_detail .txtCheckedIsActive:checked");
			if(marcadas.length == 0){
				fnShowNotification("Marca las facturas que quieres quitar","error");
				return;
			}
			marcadas.closest("tr").remove();
			updateSummary();
			updateCalculateChange();
		});

		//Si cambia la moneda, se recargan los saldos del cliente
		$(document).on("change","#txtCurrencyID",function(){
			abv2ClearRows("Cambiaste la moneda: se quitaron las facturas agregadas, vuelve a agregarlas");
			abv2LoadCustomerBalance(true);
		});

		//Seleccion de cliente desde la lista
		$(document).on("change","#txtMobileEntityID",function(){
			var entityID = $(this).val();
			if(!entityID || entityID == "Seleccionar") return;
			if($("#txtCustomerID").val() != entityID)
				abv2ClearRows("Cambiaste de cliente: se quitaron las facturas del cliente anterior");

			$("#txtCustomerID").val(entityID);
			$("#txtCustomerDescription").val($(this).find(":selected").data("name"));
			abv2LoadCustomerBalance(true);
		});

		//Limpiar cliente
		$(document).on("click","#btnClearCustomer",function(){
			abv2ClearRows("Se quitaron las facturas del cliente");
			objListaCustomerCredit = [];
			$("#txtCustomerID").val("");
			$("#txtCustomerDescription").val("");
			$("#txtBalanceStart").val("0.00");
			updateSummary();
		});

		//Ver movimientos
		$(document).on("click","#btnVerMovement",function(e){
			e.preventDefault();
			if($("#txtCustomerDescription").val() == ""){
				fnShowNotification("Selecciona el cliente","error");
				return;
			}
			var customerNumber	= $.trim($("#txtCustomerDescription").val()).split(" ")[0];
			window.open("<?php echo base_url(); ?>/app_cxc_report/movement_customer/viewReport/true/customerNumber/"+customerNumber,"MsgWindow","width=1200,height=450");
		});

		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
			fnWaitOpen();
		});

		//Guardar desde la barra del telefono
		$(document).on("click","#btnAceptMobile",function(e){
			e.preventDefault();
			$("#btnAcept").first().trigger("click");
		});

		//En telefono: ocultar la barra inferior mientras el teclado esta abierto
		$(document).on("focusin",".abv2 input[type='text'], .abv2 textarea",function(){
			if(abv2IsMobile()) $("body").addClass("abv2-typing");
		});
		$(document).on("focusout",".abv2 input[type='text'], .abv2 textarea",function(){
			setTimeout(function(){
				if(!$(document.activeElement).is(".abv2 input[type='text'], .abv2 textarea")) $("body").removeClass("abv2-typing");
			}, 50);
		});

		updateSummary();
		abv2SyncMobileBar();
	}

	function onCompleteCustomerCommon(objResponse){
		if(!objResponse || !objResponse.length || !objResponse[0]){
			fnShowNotification("Selecciona un cliente","error");
			return;
		}
		if($("#txtCustomerID").val() != "" && $("#txtCustomerID").val() != objResponse[0][1])
			abv2ClearRows("Cambiaste de cliente: se quitaron las facturas del cliente anterior");

		$("#txtCustomerID").val(objResponse[0][1]);
		$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
		abv2LoadCustomerBalance(true);
	}
</script>
