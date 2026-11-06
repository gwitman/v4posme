<style>
	/* ==========================================================
	   Activos fijos - complementos del estandar pv2 (prefijo afx)
	   Usada por: app_afx_fixedassent, _input, _output, _depreciated,
	   _valorated y _transferpiece.
	   Requiere: core_template/posme_v2_style y posme_v2_common.
	   ========================================================== */

	/* Detalle en escritorio: encabezado fijo y alto limitado. En telefono la tabla pasa a tarjetas */
	@media (min-width:768px) {
		.pv2 #div_transaction_master_detail { max-height:500px; overflow:auto; }
		.pv2 #tb_transaction_master_detail { table-layout:fixed; }
		.pv2 #tb_transaction_master_detail th, .pv2 #tb_transaction_master_detail td { word-wrap:break-word; white-space:normal; }
		.pv2 #tb_transaction_master_detail thead th { position:sticky; top:0; z-index:2; }
	}

	/* Casilla para quitar filas: es el unico mecanismo de borrado, siempre visible */
	.pv2-table th.afx-col-quitar, .pv2-table td.td-checkbox { width:48px; text-align:center; }
	.pv2 .txtCheckedIsActive { width:20px; height:20px; margin:0; }

	/* Pestanas con estilo pv2; se acomodan en varias lineas para no generar scroll horizontal */
	.pv2 .nav-tabs { display:flex; flex-wrap:wrap; gap:4px; margin-bottom:12px; border-bottom:1px solid var(--pv2-border); }
	.pv2 .nav-tabs > li { float:none; margin-bottom:-1px; }
	.pv2 .nav-tabs > li > a { font-size:13px; padding:8px 12px; border-radius:8px 8px 0 0; color:var(--pv2-muted); }
	.pv2 .nav-tabs > li.active > a, .pv2 .nav-tabs > li.active > a:hover, .pv2 .nav-tabs > li.active > a:focus { color:var(--pv2-accent); font-weight:700; }

	@media (max-width:767px) {
		/* Filas en tarjeta */
		.pv2-table tr.row_razon td.td-checkbox { width:auto; text-align:left; padding:6px 0; }
		.pv2-table tr.row_razon td.td-checkbox .txtCheckedIsActive { width:26px; height:26px; }
		.pv2-table tr.row_razon td:before { flex:0 0 auto; }
		.pv2-table tr.row_razon td > .form-control { flex:0 1 60%; width:60%; min-width:0; }
		.pv2-table tr.row_razon td > span, .pv2-table tr.row_razon td > text { min-width:0; text-align:right; word-break:break-word; }
		.pv2-table tr.row_razon td.afx-col-block { display:block; padding-top:6px; }
		.pv2-table tr.row_razon td.afx-col-block:before { display:block; margin-bottom:4px; }
		.pv2-table tr.row_razon td.afx-col-block > .form-control { width:100%; }

		/* Buscadores: los botones bajan a su propia linea para que el campo conserve el ancho */
		.pv2 .afx-search { display:flex; flex-wrap:wrap; gap:0 6px; width:100%; }
		.pv2 .afx-search > .form-control { flex:1 1 100%; width:100%; border-radius:8px; }
		.pv2 .afx-search > .input-group-btn { display:block; flex:1 1 40%; width:auto; margin-top:6px; font-size:14px; }
		.pv2 .afx-search > .input-group-btn .btn { width:100%; border-radius:8px; margin:0; }
	}
</style>
<script>
	/* ==========================================================
	   Activos fijos - funciones de interfaz compartidas (prefijo afx)
	   Requiere: posme_v2_common (pv2*). No contiene logica de negocio.
	   ========================================================== */

	/* Separa un monto en signo, entero y decimales trabajando sobre el texto, sin pasar por
	   punto flotante, para que negativos y montos con mas de 2 decimales no se alteren.
	   Devuelve null si el texto no es un numero. */
	function afxMontoPartes(value){
		var texto  = String(value === null || value === undefined ? "" : (typeof value === "number" && /e-/i.test(String(value)) ? value.toFixed(10) : value)).replace(/,/g,"").replace(/\s/g,"");
		var partes = /^(-?)(\d*)(?:\.(\d*))?$/.exec(texto);
		if(!partes || (partes[2] === "" && !partes[3])) return null;

		var entero    = partes[2].replace(/^0+(?=\d)/,"") || "0";
		var decimales = (partes[3] || "").replace(/0+$/,"");
		var signo     = (entero === "0" && decimales === "") ? "" : partes[1];
		return { signo:signo, entero:entero, decimales:decimales };
	}

	//Numero puro que se envia al servidor. Ejemplo: "-1,500.2500" -> "-1500.25"
	function afxMontoPuro(value){
		var monto = afxMontoPartes(value);
		if(!monto) return "";
		return monto.signo + monto.entero + (monto.decimales !== "" ? "." + monto.decimales : "");
	}

	//Formato visual 1,000.00. Minimo 2 decimales; si el valor trae mas, se conservan
	function afxFmtMonto(value){
		var monto = afxMontoPartes(value);
		if(!monto) return "";
		var decimales = monto.decimales;
		while(decimales.length < 2) decimales += "0";
		return monto.signo + monto.entero.replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "." + decimales;
	}

	//Quita el ruido de punto flotante de una suma (0.1 + 0.2)
	function afxRedondear(numero){
		return parseFloat(pv2Num(numero).toFixed(6));
	}

	//Da formato visual a los montos de un contenedor (pantalla completa o fila nueva)
	function afxInitMontos(scope){
		$(scope).find("input.afx-monto").each(function(){
			this.value = afxFmtMonto(this.value);
		});
	}

	//Deja los montos del formulario como numero puro. Se llama justo antes de .submit()
	function afxLimpiarMontos(formSelector){
		$(formSelector).find("input.afx-monto").each(function(){
			this.value = afxMontoPuro(this.value);
		});
	}

	//Select2 solo en PC; en telefono el combo queda nativo (tambien para filas y opciones recargadas)
	function afxInitSelect2(selector, options){
		if(!pv2IsMobile()){
			$(selector).select2(options || {});
			return;
		}
		pv2MobileSelects(selector);
	}

	//Actualiza el contador de filas y el aviso de detalle vacio
	function afxRefrescarDetalle(){
		var total = $("#body_tb_transaction_master_detail").find("tr").length;
		$("#afxDetailCount").text(total);
		$("#afxDetailEmpty").toggle(total == 0);
		$("#div_transaction_master_detail").toggle(total > 0);
	}

	/* Enlaza lo comun de los formularios de activos fijos. Llamar una sola vez por pantalla:
	   aqui esta la unica llamada a pv2BindMobileBar (una segunda guardaria dos veces). */
	function afxBindCommon(){
		pv2MobileSelects(".pv2 select.select2");
		afxInitMontos(".pv2");
		pv2BindMobileBar();

		//Mientras se escribe solo se aceptan digitos, comas, un punto y el signo inicial.
		//Los campos con la clase afx-sin-negativo no aceptan el signo
		$(document).on("input",".pv2 input.afx-monto",function(){
			var original = this.value;
			var texto    = original.replace(/[^0-9.,\-]/g,"");
			var negativo = texto.charAt(0) === "-" && !$(this).hasClass("afx-sin-negativo");
			texto        = texto.replace(/-/g,"");
			var punto    = texto.indexOf(".");
			if(punto !== -1){
				texto = texto.substring(0,punto + 1) + texto.substring(punto + 1).replace(/\./g,"");
			}
			texto = (negativo ? "-" : "") + texto;
			if(texto !== original) this.value = texto;
		});

		//Al salir del campo se aplica el formato visual
		$(document).on("focusout",".pv2 input.afx-monto",function(){
			if(!this.readOnly) this.value = afxFmtMonto(this.value);
		});

		//Contador y aviso de vacio: se observan los cambios del detalle sin tocar los eventos de cada pantalla
		var cuerpoDetalle = document.getElementById("body_tb_transaction_master_detail");
		if(cuerpoDetalle){
			afxRefrescarDetalle();
			if(window.MutationObserver){
				new MutationObserver(afxRefrescarDetalle).observe(cuerpoDetalle,{childList:true});
			}
		}
	}
</script>
