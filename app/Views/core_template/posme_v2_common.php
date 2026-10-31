<script>
	/* ==========================================================
	   posme v2 - funciones genericas de interfaz (prefijo pv2)
	   Usada por: app_box_sharecapital, app_box_canceldocument
	   Requiere que antes este definida: varUseMobile
	   No contiene logica de negocio.
	   ========================================================== */

	//Convierte cualquier valor (texto con comas, vacio, numero) a numero. Nunca devuelve NaN
	function pv2Num(value){
		if(value === null || value === undefined) return 0;
		if(typeof value === "number") return isNaN(value) ? 0 : value;
		var texto  = String(value).replace(/,/g,"").replace(/[^0-9.\-]/g,"");
		var numero = parseFloat(texto);
		return isNaN(numero) ? 0 : numero;
	}
	//Formato sin separador de miles (atributos data-*, campos ocultos)
	function pv2Plain(value){
		return (Math.round(pv2Num(value) * 100) / 100).toFixed(2);
	}
	//Formato con separador de miles (para mostrar)
	function pv2Fmt(value){
		var numero = Math.round(pv2Num(value) * 100) / 100;
		var partes = Math.abs(numero).toFixed(2).split(".");
		partes[0]  = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		return (numero < 0 ? "-" : "") + partes[0] + "." + partes[1];
	}
	function pv2IsMobile(){
		var pantallaChica = window.matchMedia ? window.matchMedia("(max-width: 767px)").matches : (window.innerWidth < 768);
		return (typeof varUseMobile !== "undefined" && varUseMobile == "1") || pantallaChica;
	}
	function pv2Mask($elements){
		$elements.mask('000,000,000.00', {reverse: true});
	}

	/* ---------------- Controles segun el dispositivo ---------------- */
	//En telefono: combo normal (sin select2). En PC se mantiene select2
	function pv2MobileSelects(selector){
		if(!pv2IsMobile()) return;
		$(selector).each(function(i,obj){
			var $select = $(obj);
			try{
				if($select.data("select2")) $select.select2("destroy");
			}
			catch(ex){
				console.info("pv2MobileSelects", ex);
			}
			$select.removeClass("select2").addClass("form-control").css("display","");
		});
	}
	//Fecha: calendario del tema en PC, selector nativo del telefono en movil.
	//El valor siempre queda en formato YYYY-MM-DD
	function pv2InitDate(id, setToday){
		var input = document.getElementById(id);
		if(!input) return;

		var valor = $.trim(input.value);
		if(setToday || valor == "") valor = moment().format("YYYY-MM-DD");
		valor = valor.substring(0,10);

		if(pv2IsMobile()){
			try { input.type = "date"; } catch(ex) { console.info("pv2InitDate", ex); }
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
	function pv2EnsureSearchModal(){
		var $modal = $("#pv2SearchModal");
		if($modal.length > 0) return $modal;
		$modal = $(
			'<div class="modal fade" tabindex="-1" id="pv2SearchModal" role="dialog">' +
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
	function pv2CloseSearch(){
		$("#pv2SearchModal").modal("hide");
	}
	function pv2OpenSearch(url, title, callbackName, callbackFn, popupFeatures){
		if(!pv2IsMobile()){
			window[callbackName] = callbackFn;
			window.open(url, "MsgWindow", popupFeatures);
			return;
		}

		//En telefono: la pagina de busqueda se carga dentro de la misma pantalla.
		//La pagina de busqueda llama window.opener.<callback>(data) y window.close(),
		//por eso se los entregamos desde aqui sin tocar core_view.
		window[callbackName] = function(data){
			try { callbackFn(data); }
			finally { pv2CloseSearch(); }
		};

		var $modal  = pv2EnsureSearchModal();
		var $iframe = $('<iframe title="Buscar"></iframe>');
		$modal.find(".modal-title").text(title);
		$modal.find(".modal-body").empty().append($iframe);
		$iframe.on("load", function(){
			try{
				var ventana		= this.contentWindow;
				ventana.opener	= window;
				ventana.close	= function(){ pv2CloseSearch(); };
			}
			catch(ex){
				console.info("pv2OpenSearch: no se pudo enlazar la busqueda", ex);
			}
		});
		$iframe.attr("src", url);
		$modal.modal("show");
	}

	/* ---------------- Barra inferior del telefono ---------------- */
	function pv2BindMobileBar(){
		//Guardar desde la barra del telefono
		$(document).on("click","#btnAceptMobile",function(e){
			e.preventDefault();
			$("#btnAcept").first().trigger("click");
		});

		//Ocultar la barra mientras el teclado esta abierto
		var selectorTyping = ".pv2 input[type='text'], .pv2 input[type='date'], .pv2 textarea";
		$(document).on("focusin",selectorTyping,function(){
			if(pv2IsMobile()) $("body").addClass("pv2-typing");
		});
		$(document).on("focusout",selectorTyping,function(){
			setTimeout(function(){
				if(!$(document.activeElement).is(selectorTyping)) $("body").removeClass("pv2-typing");
			}, 50);
		});
	}

	//Lleva la vista a un campo y lo enfoca
	function pv2FocusField(selector){
		var $campo = $(selector).first();
		if($campo.length == 0) return;
		try { $campo[0].scrollIntoView({behavior:"smooth", block:"center"}); } catch(ex) { $campo[0].scrollIntoView(); }
		setTimeout(function(){ $campo.focus(); }, 250);
	}
</script>
