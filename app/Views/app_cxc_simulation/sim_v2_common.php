<script>
	/* ==========================================================
	   app_cxc_simulation v2 - funciones del simulador de credito
	   Requiere que antes este definida: varUseMobile
	   ========================================================== */

	function simv2IsMobile(){
		var pantallaChica = window.matchMedia ? window.matchMedia("(max-width: 767px)").matches : (window.innerWidth < 768);
		return varUseMobile == "1" || pantallaChica;
	}

	/* ---------------- Combos nativos ---------------- */
	//Se quita select2 y uniform de todos los combos de la pantalla
	function simv2NativeSelects(){
		var $selects = $(".simv2 select");
		$selects.each(function(i,obj){
			var $select = $(obj);
			try{
				if($select.data("select2")) $select.select2("destroy");
			}
			catch(ex){
				console.info("simv2NativeSelects select2", ex);
			}
		});
		try{
			if($.uniform && $.uniform.restore) $.uniform.restore($selects);
		}
		catch(ex){
			console.info("simv2NativeSelects uniform", ex);
		}
		$selects.removeClass("select2").addClass("form-control").css("display","");
	}

	/* ---------------- Busqueda (ventana emergente en PC, ventana interna en telefono) ---------------- */
	function simv2EnsureSearchModal(){
		var $modal = $("#simv2SearchModal");
		if($modal.length > 0) return $modal;
		$modal = $(
			'<div class="modal fade" tabindex="-1" id="simv2SearchModal" role="dialog">' +
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
	function simv2CloseSearch(){
		$("#simv2SearchModal").modal("hide");
	}
	function simv2OpenSearch(url, title, callbackName, callbackFn, popupFeatures){
		if(!simv2IsMobile()){
			window[callbackName] = callbackFn;
			window.open(url, "MsgWindow", popupFeatures);
			return;
		}

		//En telefono: la pagina de busqueda se carga dentro de la misma pantalla.
		//La pagina de busqueda llama window.opener.<callback>(data) y window.close(),
		//por eso se los entregamos desde aqui sin tocar core_view.
		window[callbackName] = function(data){
			try { callbackFn(data); }
			finally { simv2CloseSearch(); }
		};

		var $modal  = simv2EnsureSearchModal();
		var $iframe = $('<iframe title="Buscar"></iframe>');
		$modal.find(".modal-title").text(title);
		$modal.find(".modal-body").empty().append($iframe);
		$iframe.on("load", function(){
			try{
				var ventana		= this.contentWindow;
				ventana.opener	= window;
				ventana.close	= function(){ simv2CloseSearch(); };
			}
			catch(ex){
				console.info("simv2OpenSearch: no se pudo enlazar la busqueda", ex);
			}
		});
		$iframe.attr("src", url);
		$modal.modal("show");
	}

	/* ---------------- Numeros ---------------- */
	//Plazo: si trae una sola coma y ningun punto, la coma es decimal (50,666 => 50.666).
	//En cualquier otro caso las comas se toman como separador de miles y se quitan.
	function fnNormalizarDecimal(valor){
		valor = $.trim(String(valor == null ? "" : valor)).replace(/\s/g,"");
		if(valor.indexOf(",") != -1 && valor.indexOf(".") == -1 && valor.split(",").length == 2)
			return valor.replace(",",".");
		return valor.replace(/,/g,"");
	}
	//Normaliza el campo # Pagos, reescribe el input y devuelve el plazo (NaN si no es valido)
	function simv2LeerPlazo(){
		var valor = fnNormalizarDecimal($("#txtNumberPay").val());
		$("#txtNumberPay").val(valor);
		if(!/^\d+(\.\d+)?$/.test(valor) && !/^\.\d+$/.test(valor))
			return NaN;
		return parseFloat(valor);
	}

	/* ---------------- Tabla y resumen ---------------- */
	function simv2SetEmpty(texto){
		$("#body_tb_transaction_master_detail").empty();
		$("#simv2TableWrap").addClass("hidden");
		$("#simv2Legend").addClass("hidden");
		$("#simv2DetailCount").text("0");
		$("#simv2EmptyText").html(texto);
		$("#simv2Empty").show();
	}
	function simv2ResetResumen(){
		$("#txtCapitalTotal").val(fnFormatNumber(0,2));
		$("#txtInterestTotal").val(fnFormatNumber(0,2));
		$("#txtTotal").val(fnFormatNumber(0,2));
		$("#simv2KpiCount").text("0");
		$("#simv2KpiCuota").text(fnFormatNumber(0,2));
	}
	//Dibuja las filas de data.array.detail (objeto con claves 1..n o arreglo).
	//Devuelve los totales calculados desde las filas.
	function simv2RenderTable(detail, plazo){
		var $body		= $("#body_tb_transaction_master_detail");
		var fraccion	= Math.round((plazo - Math.floor(plazo)) * 1000000) / 1000000;
		var claves		= Object.keys(detail);
		var ultimaClave	= claves[claves.length - 1];
		var resumen		= { capital:0, interes:0, cuotas:0, cuotaRegular:null, proporcional:false };

		$body.empty();
		$.each(detail,function(i,obj){
			var objRow		= obj;
			var numero		= parseInt(objRow.pnum,10);
			var esProrrata	= fraccion > 0.001 && String(i) == String(ultimaClave) && numero > Math.floor(plazo);

			var $tr		= $("<tr class='simv2-row'></tr>");
			var $num	= $("<td class='simv2-col-num' data-label='Número'></td>");
			$num.append($("<span class='simv2-num-prefix'></span>").text("Cuota"));
			$num.append(document.createTextNode(isNaN(numero) ? "" : String(numero)));
			if(esProrrata){
				$num.append($("<span class='simv2-tag'></span>").text("proporcional").attr("title","Cuota proporcional al " + fnFormatNumber(fraccion * 100,1) + "% del periodo"));
				$tr.addClass("is-prorrata");
				resumen.proporcional = true;
			}
			$tr.append($num);
			$tr.append($("<td class='simv2-col-date' data-label='Fecha'></td>").text(objRow.date));
			$tr.append($("<td data-label='Inicial'></td>").text(fnFormatNumber(objRow.saldoInicial,2)));
			$tr.append($("<td data-label='Interés'></td>").text(fnFormatNumber(objRow.interes,2)));
			$tr.append($("<td data-label='Capital'></td>").text(fnFormatNumber(objRow.principal,2)));
			$tr.append($("<td class='simv2-col-cuota' data-label='Cuota'></td>").text(fnFormatNumber(objRow.cuota,2)));
			$tr.append($("<td data-label='Final'></td>").text(fnFormatNumber(objRow.saldo,2)));
			$body.append($tr);

			resumen.capital	= resumen.capital + (parseFloat(objRow.principal) || 0);
			resumen.interes	= resumen.interes + (parseFloat(objRow.interes) || 0);
			if(numero >= 1){
				resumen.cuotas++;
				//Cuota regular: la primera cuota con numero >= 1 (en aleman puede venir una fila 0)
				if(resumen.cuotaRegular === null) resumen.cuotaRegular = parseFloat(objRow.cuota) || 0;
			}
		});

		$("#simv2DetailCount").text(claves.length);
		$("#simv2Legend").toggleClass("hidden", !resumen.proporcional);
		$("#simv2Empty").hide();
		$("#simv2TableWrap").removeClass("hidden");
		return resumen;
	}
	function simv2SetResumen(resumen){
		$("#txtCapitalTotal").val(fnFormatNumber(resumen.capital,2));
		$("#txtInterestTotal").val(fnFormatNumber(resumen.interes,2));
		$("#txtTotal").val(fnFormatNumber(resumen.interes + resumen.capital,2));
		$("#simv2KpiCount").text(resumen.cuotas);
		$("#simv2KpiCuota").text(fnFormatNumber(resumen.cuotaRegular === null ? 0 : resumen.cuotaRegular,2));
	}
</script>
