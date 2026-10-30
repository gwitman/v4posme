<script>
	/* ==========================================================
	   app_med_query v2 - funciones compartidas de nuevo y edicion
	   Requiere que antes este definida: varUseMobile
	   ========================================================== */

	function mqv2IsMobile(){
		var pantallaChica = window.matchMedia ? window.matchMedia("(max-width: 767px)").matches : (window.innerWidth < 768);
		return varUseMobile == "1" || pantallaChica;
	}

	/* ---------------- Controles segun el dispositivo ---------------- */
	//Combos nativos: se quita select2 y uniform de todos los combos de la pantalla
	function mqv2NativeSelects(){
		var $selects = $(".mqv2 select");
		$selects.each(function(i,obj){
			var $select = $(obj);
			try{
				if($select.data("select2")) $select.select2("destroy");
			}
			catch(ex){
				console.info("mqv2NativeSelects select2", ex);
			}
		});
		try{
			if($.uniform && $.uniform.restore) $.uniform.restore($selects);
		}
		catch(ex){
			console.info("mqv2NativeSelects uniform", ex);
		}
		$selects.removeClass("select2").addClass("form-control").css("display","");
	}
	//Fecha: calendario del tema en PC, selector nativo del telefono en movil.
	//El valor siempre queda en formato YYYY-MM-DD
	function mqv2InitDate(id, setToday){
		var input = document.getElementById(id);
		if(!input) return;

		var valor = $.trim(input.value);
		if(setToday && valor == "") valor = moment().format("YYYY-MM-DD");
		valor = valor.substring(0,10);

		if(mqv2IsMobile()){
			try { input.type = "date"; } catch(ex) { console.info("mqv2InitDate", ex); }
			input.value = valor;
			$(input).closest(".input-group").find(".input-group-addon").hide();
			$(input).closest(".input-group").removeClass("input-group date");
			return;
		}

		$(input).datepicker({format:"yyyy-mm-dd"});
		$(input).val(valor);
		if(valor != "") $(input).datepicker("update");
	}

	/* ---------------- Busqueda (ventana emergente en PC, ventana interna en telefono) ---------------- */
	function mqv2EnsureSearchModal(){
		var $modal = $("#mqv2SearchModal");
		if($modal.length > 0) return $modal;
		$modal = $(
			'<div class="modal fade" tabindex="-1" id="mqv2SearchModal" role="dialog">' +
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
	function mqv2CloseSearch(){
		$("#mqv2SearchModal").modal("hide");
	}
	function mqv2OpenSearch(url, title, callbackName, callbackFn, popupFeatures){
		if(!mqv2IsMobile()){
			window[callbackName] = callbackFn;
			window.open(url, "MsgWindow", popupFeatures);
			return;
		}

		//En telefono: la pagina de busqueda se carga dentro de la misma pantalla.
		//La pagina de busqueda llama window.opener.<callback>(data) y window.close(),
		//por eso se los entregamos desde aqui sin tocar core_view.
		window[callbackName] = function(data){
			try { callbackFn(data); }
			finally { mqv2CloseSearch(); }
		};

		var $modal  = mqv2EnsureSearchModal();
		var $iframe = $('<iframe title="Buscar"></iframe>');
		$modal.find(".modal-title").text(title);
		$modal.find(".modal-body").empty().append($iframe);
		$iframe.on("load", function(){
			try{
				var ventana		= this.contentWindow;
				ventana.opener	= window;
				ventana.close	= function(){ mqv2CloseSearch(); };
			}
			catch(ex){
				console.info("mqv2OpenSearch: no se pudo enlazar la busqueda", ex);
			}
		});
		$iframe.attr("src", url);
		$modal.modal("show");
	}

	/* ---------------- IMC ---------------- */
	function mqv2CalcImc(){
		var peso	= parseFloat(String($("#txtWeight").val()).replace(/,/g,""));
		var altura	= parseFloat(String($("#txtHeight").val()).replace(/,/g,"")) / 100;
		var $box	= $("#mqv2ImcBox");
		var $label	= $("#mqv2ImcLabel");

		$box.removeClass("is-low is-ok is-warn is-bad");
		if (isNaN(peso) || isNaN(altura) || peso <= 0 || altura <= 0) {
			$('#txtIMC').val("0");
			$label.text("Sin datos");
			return;
		}

		// Calcular el IMC (peso / (altura * altura))
		var imc = peso / (altura * altura);
		$('#txtIMC').val(imc.toFixed(2));

		if(imc < 18.5)		{ $box.addClass("is-low");  $label.text("Bajo peso"); }
		else if(imc < 25)	{ $box.addClass("is-ok");   $label.text("Peso normal"); }
		else if(imc < 30)	{ $box.addClass("is-warn"); $label.text("Sobrepeso"); }
		else				{ $box.addClass("is-bad");  $label.text("Obesidad"); }
	}
	//Se conserva por compatibilidad
	function fnCalcularIMC() {
		mqv2CalcImc();
	}

	/* ---------------- Estado visual ---------------- */
	//Oculta las tarjetas que no tienen campos visibles (personalizacion por empresa)
	function mqv2HideEmptyCards(){
		$(".mqv2 [data-mqv2-autohide]").each(function(i,obj){
			var visibles = $(obj).find(".form-group").not(".hidden").length;
			if(visibles == 0){
				$(obj).addClass("hidden");
				var expandir = $(obj).attr("data-mqv2-expand");
				if(expandir) $(expandir).removeClass("col-md-6 col-md-7").addClass("col-md-12");
			}
		});
	}
	function mqv2RefreshDetail(){
		var cantidad = $("#mqv2DetailBody tr.mqv2-row").length;
		$("#mqv2DetailCount").text(cantidad);
		$("#mqv2EmptyDetail").toggle(cantidad == 0);
		$("#tableFrecuency").toggleClass("hidden", cantidad == 0);
	}
	function mqv2SyncHeader(){
		var prioridad = $.trim($("#txtPriorityID option:selected").text());
		$("#mqv2HeaderPriority").text(prioridad).toggle(prioridad != "");

		var paciente = $.trim($("#txtCustomerDescription").val());
		$("#mqv2MbarName").text(paciente != "" ? paciente : "Sin paciente");
	}

	/* ---------------- Detalle (receta / indicaciones) ---------------- */
	function fnEliminarFila(boton) {
		$(boton).closest('tr').remove();
		mqv2RefreshDetail();
	}
	function fnAgregarFila() {
		var texto = $.trim($('#txtDetailName').val());
		var monto = $('#txtAmount').val();

		// Si el campo de texto esta vacio, mostrar un mensaje de error
		if (texto === "") {
			$('#errorLabel').show();
			$('#txtDetailName').focus();
			return;
		}
		$('#errorLabel').hide();

		//La fila sale de la plantilla; los valores se asignan con .val() (sin armar HTML con texto del usuario)
		var $fila = $($.trim($("#tmpl_row_detail").html()));
		$fila.find(".mqv2-in-name").val(texto);
		$fila.find(".mqv2-in-priority").val($('#txtPriority').val());
		$fila.find(".mqv2-in-frecuency").val($('#txtFrecuency').val());
		$fila.find(".mqv2-in-dose").val($('#txtDose').val());
		$fila.find(".mqv2-in-amount").val(monto);

		// La fila nueva queda arriba (igual que antes)
		$("#mqv2DetailBody").prepend($fila);

		$('#txtDetailName').val('');
		$('#txtAmount').val('');
		$('#txtPriority, #txtFrecuency, #txtDose').prop('selectedIndex', 0);
		mqv2RefreshDetail();
	}

	/* ---------------- Validaciones comunes ---------------- */
	function mqv2ValidateCommon(){
		var result 				= true;
		var timerNotification 	= 15000;

		//Validar Fecha
		if($("#txtDate").val() == ""){
			fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
			result = false;
		}

		var customerID = $.trim($("#txtCustomerID").val());
		if( customerID == "" || customerID == "0" || customerID == "13" ){
			fnShowNotification("Seleccione el cliente","error",timerNotification);
			result = false;
		}

		return result;
	}

	/* ---------------- Eventos comunes de las dos pantallas ---------------- */
	function mqv2BindCommon(){
		mqv2NativeSelects();
		mqv2HideEmptyCards();

		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
			fnWaitOpen();
		});

		//Guardar desde la barra del telefono
		$(document).on("click","#btnAceptMobile",function(e){
			e.preventDefault();
			$("#btnAcept").first().trigger("click");
		});

		//Limpiar cliente
		$(document).on("click","#btnClearCustomer",function(){
			$("#txtCustomerID").val("");
			$("#txtCustomerDescription").val("");
			mqv2SyncHeader();
		});

		//Agregar indicacion
		$(document).on("click","#btnAddDetail",function(e){
			e.preventDefault();
			fnAgregarFila();
		});

		//Enter no debe enviar el formulario; en la captura de indicaciones agrega la fila
		$(document).on("keydown","#form-new-invoice input",function(e){
			if(e.key !== "Enter" && e.keyCode !== 13) return;
			e.preventDefault();
			if(this.id == "txtDetailName" || this.id == "txtAmount") fnAgregarFila();
		});

		//IMC
		$(document).on("input change","#txtWeight, #txtHeight",function(){
			mqv2CalcImc();
		});

		//Encabezado
		$(document).on("change","#txtPriorityID",function(){
			mqv2SyncHeader();
		});

		//En telefono: ocultar la barra inferior mientras el teclado esta abierto
		var selectorTyping = ".mqv2 input[type='text'], .mqv2 input[type='number'], .mqv2 textarea";
		$(document).on("focusin",selectorTyping,function(){
			if(mqv2IsMobile()) $("body").addClass("mqv2-typing");
		});
		$(document).on("focusout",selectorTyping,function(){
			setTimeout(function(){
				if(!$(document.activeElement).is(selectorTyping)) $("body").removeClass("mqv2-typing");
			}, 50);
		});

		mqv2CalcImc();
		mqv2RefreshDetail();
		mqv2SyncHeader();
	}
</script>
