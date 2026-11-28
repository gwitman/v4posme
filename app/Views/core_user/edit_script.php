<script>
	var objRowListWarehouse 			= {};
	var objRowListNotification 			= {};
	var objTableListWarehouse 			= {};
	var objTableListNotification 		= {};
	var objTableListBox					= {};
	var objRowListBox					= {};
	var site_url 						= "<?php echo base_url(); ?>/";
	var varParameterCantidadItemPoup 	= '<?php echo $objParameterCantidadItemPoup; ?>';

	$(document).ready(function() {
		//Inicializar Tabla
		objTableListWarehouse = $("#ListElementWarehouse").dataTable({
			"bPaginate"		: false,
			"bLengthChange"	: false,
			"bFilter"		: false,
			"bSort"			: true,
			"bInfo"			: false,
			"bAutoWidth"	: true
		});
		objTableListBox = $("#ListElementBox").dataTable({
			"bPaginate"		: false,
			"bLengthChange"	: false,
			"bFilter"		: false,
			"bSort"			: true,
			"bInfo"			: false,
			"bAutoWidth"	: true
		});
		//Inicializar Tabla
		objTableListNotification = $("#ListElementNotification").dataTable({
			"bPaginate"			: false,
			"bLengthChange"		: false,
			"bFilter"			: false,
			"bSort"				: true,
			"bInfo"				: false,
			"bAutoWidth"		: true
		});
		//Comando Guardar
		$(document).on("click", "#btnAcept", function() {
			var fnEnviar = function() {
				fnWaitOpen();
				$("#form-new-rol").attr("method", "POST");
				$("#form-new-rol").attr("action", "<?php echo base_url(); ?>/core_user/save");
				$("#form-new-rol").submit();
			};
			//Si hay parametros modificados sin guardar, se pide confirmacion porque se perderian
			if (varCanEditParameter && fnParamDirtyCount() > 0) {
				fnShowConfirm("Confirmar..", "Hay parametros modificados sin guardar que se perderan. Desea continuar?", fnEnviar);
				return;
			}
			fnEnviar();
		});
		//Comando Regresar
		$(document).on("click", "#btnBack", function() {
			fnWaitOpen();
		});
		//Comando Eliminar
		$(document).on("click", "#btnDelete", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar este Rol...", function() {
				fnWaitOpen();
				$.ajax({
					cache: false,
					dataType: 'json',
					type: 'POST',
					url: "<?php echo base_url(); ?>/core_user/delete",
					data: {
						companyID: 	<?php echo $objUser->companyID; ?>,
						branchID: 	<?php echo $objUser->branchID; ?>,
						userID: 	<?php echo $objUser->userID; ?>
					},
					success: function(data) {
						fnWaitClose();
						console.info("complete delete success");
						if (data.error) {
							fnShowNotification(data.message, "error");
						} else {
							window.location = "<?php echo base_url(); ?>/core_user/index";
						}
					},
					error: function(xhr, data) {
						console.info("complete delete error");
						fnWaitClose();
						fnShowNotification("Error 505", "error");
					}
				});
			});
		});
		//Comando  Seleccionar Detalle de bodega
		$(document).on("click", "#tbody_detail_warehouse tr", function(event) {
			objRowListWarehouse = this;
			fnTableSelectedRow(this, event);
		});
		//Comando  Seleccionar Detalle de Caja
		$(document).on("click", "#tbody_detail_box tr", function(event) {
			objRowListBox = this;
			fnTableSelectedRow(this, event);
		});
		//Comando  Seleccionar Detalle de Notification
		$(document).on("click", "#tbody_detail_notification tr", function(event) {
			objRowListNotification = this;
			fnTableSelectedRow(this, event);
		});

		//Comando Eliminar Detalle de bodegas
		$(document).on("click", "#btnDeleteDetailWarehouse", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar la bodega seleccionada?", function() {
				objTableListWarehouse.fnDeleteRow(objRowListWarehouse);
			});
		});
		//Comando Eliminar Detalle de Caja
		$(document).on("click", "#btnDeleteDetailBox", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar la caja seleccionada?", function() {
				objTableListBox.fnDeleteRow(objRowListBox);
			});
		});
		//Comando Eliminar Detalle de Notificacion
		$(document).on("click", "#btnDeleteDetailNotification", function() {
			fnShowConfirm("Confirmar..", "Desea eliminar la notificacion seleccionada?", function() {
				objTableListNotification.fnDeleteRow(objRowListNotification);
			});
		});
		//Comando Agregar Detalle de Bodega
		$(document).on("click", "#btnNewDetailWarehouse", function() {
			window.open(site_url + "core_user/add_warehouse", "MsgWindow", "width=650,height=500");
			window.parentNewWarehouse = parentNewWarehouse;
		});
		//Comando Agregar Detalle de Caja
		$(document).on("click", "#btnNewDetailBox", function() {
			window.open(site_url + "core_user/add_box", "MsgWindow", "width=650,height=500");
			window.parentNewBox = parentNewBox;
		});
		//Comando Agregar Detalle de Notificacion
		$(document).on("click", "#btnNewDetailNotification", function() {
			window.open(site_url + "core_user/add_tag", "MsgWindow", "width=650,height=500");
			window.parentNewNotification = parentNewNotification;
		});
		//Buscar el Empleado
		$(document).on("click", "#btnSearchEmployeeParent", function() {
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteEmployee/SELECCIONAR_ENTIDAD_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
			window.open(url_request, "MsgWindow", "width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee;
		});
		//Eliminar Empleado
		$(document).on("click", "#btnClearEmployeeParent", function() {
			$("#txtEmployeeID").val("");
			$("#txtEmployeeDescription").val("");
		});

	});

	function onCompleteEmployee(objResponse) {
		console.info("CALL onCompleteEmployee");

		$("#txtEmployeeID").val(objResponse[0][2]);
		$("#txtEmployeeDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);

	}
	//Callback Complete: Agregar Notification Tag
	function parentNewNotification(data) {
		if (data.txtTagID == "") {
			fnShowNotification("No es posible agregar el Tag", "error");
			return;
		}
		if ($("input[name='txtDetailTagID[]'][value=" + data.txtTagID + "]").length > 0)
			return;

		var tmp1 = $.tmpl('<span><input type="hidden" name="txtDetailTagID[]" value="${txtTagID}" /> ${txtTagName}</span>', data).html();
		objTableListNotification.fnAddData([tmp1]);
	}

	//Callback Complete: Agregar Bodega
	function parentNewWarehouse(data) {
		if (data.txtDetailWarehouseID == "") {
			fnShowNotification("No es posible agregar la bodega", "error");
			return;
		}
		if ($("input[name='txtDetailWarehouseID[]'][value=" + data.txtDetailWarehouseID + "]").length > 0)
			return;

		var tmp1 = $.tmpl('<span><input type="hidden" name="txtDetailWarehouseID[]" value="${txtDetailWarehouseID}" /> ${txtDetailWarehouseName}</span>', data).html();
		objTableListWarehouse.fnAddData([tmp1]);
	}
	//Callback Complete: Agregar Caja
	function parentNewBox(data) {
		if (data.txtDetailCashBoxID == "") {
			fnShowNotification("No es posible agregar la caja", "error");
			return;
		}
		if ($("input[name='txtDetailCashBoxID[]'][value=" + data.txtDetailCashBoxID + "]").length > 0)
			return;

		var tmp1 = $.tmpl('<span><input type="hidden" name="txtDetailCashBoxID[]" value="${txtDetailCashBoxID}" /> ${txtDetailCashBoxName}</span>', data).html();
		objTableListBox.fnAddData([tmp1]);
	}
	//Telefono: boton Guardar en la barra inferior y combos nativos (sin select2). En PC no cambia nada
	$(document).ready(function(){
		if(window.pv2BindMobileBar){ pv2BindMobileBar(); }
		PosmeInv.mobileSelects();
	});

	/* ==================== Tab Parametros (AJAX JSON, sin name en los controles) ==================== */
	var varCanEditParameter = <?php echo !empty($canEditParameter) ? "true" : "false"; ?>;
	var objParameterList    = [];	//filas recibidas del servidor
	var objParameterIndex   = {};	//companyParameterID -> fila
	var objParameterRows    = [];	//{tr, key} para el filtro en cliente
	var varParameterLoaded  = false;
	var varParameterTimer   = null;
	var PARAM_REGEX         = {
		"int"  : /^-?\d+$/,
		"dec"  : /^-?\d+(\.\d+)?$/,
		"date" : /^\d{4}-\d{2}-\d{2}$/
	};

	//Escapa texto antes de insertarlo como HTML (display, description, value, customValue, source vienen de BD)
	function fnParamEsc(v){
		if(v === null || v === undefined) return "";
		return String(v).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;");
	}
	function fnParamStr(v){ return (v === null || v === undefined) ? "" : String(v); }

	//Tipo de editor: primero el campo type de la BD; si viene vacio o desconocido se detecta por value (o customValue)
	function fnParamType(p){
		var tipoBD = $.trim(fnParamStr(p.type)).toLowerCase();
		var mapa   = { "bool":"bool", "int":"int", "decimal":"dec", "text":"text", "datetime":"date", "combobox":"combobox" };
		if(mapa[tipoBD]) return mapa[tipoBD];

		var t = $.trim(fnParamStr(p.value));
		if(t === "") t = $.trim(fnParamStr(p.customValue));
		if(/^(true|false)$/i.test(t))     return "bool";
		if(/^-?\d+$/.test(t))             return "int";
		if(/^-?\d*\.\d+$/.test(t))        return "dec";
		if(/^\d{4}-\d{2}-\d{2}$/.test(t)) return "date";
		return "text";
	}

	//Devuelve "true"/"false" respetando el formato del valor base (TRUE, True o true)
	function fnParamBoolText(base, flag){
		var w = flag ? "true" : "false";
		base  = $.trim(fnParamStr(base));
		//solo se copia el formato si el valor base es texto true/false (ej. "1" o "0" se guarda en minuscula)
		if(!/^(true|false)$/i.test(base))                                       return w;
		if(base === base.toUpperCase())                                         return w.toUpperCase();
		if(base.length > 1 && base.charAt(0) === base.charAt(0).toUpperCase()) return w.charAt(0).toUpperCase() + w.substring(1);
		return w;
	}

	//Control de edicion segun el tipo
	function fnParamEditor(p, tipo){
		var id    = fnParamEsc(p.companyParameterID);
		var cv    = fnParamStr(p.customValue);
		var movil = pv2IsMobile();
		var attr  = ' data-id="' + id + '" data-type="' + tipo + '" autocomplete="off"';
		var i, b, opciones, existe, html;

		if(tipo === "bool"){
			b = $.trim(cv).toLowerCase();
			return '<select class="form-control jsParamEdit"' + attr + '>' +
				'<option value="">Seleccione</option>' +
				'<option value="1"' + (b === "true"  ? ' selected' : '') + '>Verdadero</option>' +
				'<option value="0"' + (b === "false" ? ' selected' : '') + '>Falso</option>' +
			'</select>';
		}
		if(tipo === "combobox"){
			//opciones de source separadas por coma, sin vacias
			opciones = $.map(fnParamStr(p.source).split(","), function(o){ o = $.trim(o); return o === "" ? null : o; });
			existe   = (cv === "");
			html     = '<select class="form-control jsParamEdit"' + attr + '><option value="">Seleccione</option>';
			for(i = 0; i < opciones.length; i++){
				if(opciones[i] === cv) existe = true;
				html += '<option value="' + fnParamEsc(opciones[i]) + '"' + (opciones[i] === cv ? ' selected' : '') + '>' + fnParamEsc(opciones[i]) + '</option>';
			}
			//si el valor actual no esta en las opciones se agrega para no perderlo
			if(!existe) html += '<option value="' + fnParamEsc(cv) + '" selected>' + fnParamEsc(cv) + '</option>';
			return html + '</select>';
		}
		if(tipo === "int" || tipo === "dec"){
			//el teclado numerico del telefono no tiene "-": si el valor es negativo se usa teclado de texto
			var im = fnParamStr(p.value).charAt(0) === "-" ? "text" : (tipo === "int" ? "numeric" : "decimal");
			return '<input type="text" class="form-control jsParamEdit jsParamNum" inputmode="' + im + '" maxlength="30"' + attr + ' value="' + fnParamEsc(cv) + '">';
		}
		if(tipo === "date"){
			if(movil) return '<input type="date" class="form-control jsParamEdit"' + attr + ' value="' + fnParamEsc(PosmeInv.normalizarFecha(cv)) + '">';
			return '<input type="text" class="form-control jsParamEdit jsParamDate" placeholder="yyyy-mm-dd" maxlength="10"' + attr + ' value="' + fnParamEsc(cv) + '">';
		}
		return '<input type="text" class="form-control jsParamEdit" maxlength="300"' + attr + ' value="' + fnParamEsc(cv) + '">';
	}

	//Campo de solo lectura para textos largos: textarea sin name, con salto de linea y alto segun el largo
	function fnParamReadOnly(v){
		var s     = fnParamStr(v);
		var lines = s.split("\n").length + Math.floor(s.length / 55);
		var rows  = Math.max(1, Math.min(5, lines));
		return '<textarea class="form-control cuser-param-ro" readonly tabindex="-1" rows="' + rows + '">' + fnParamEsc(s) + '</textarea>';
	}

	//Pinta todas las tarjetas de una sola vez (rapido con ~300 parametros)
	function fnParamRender(){
		var html = [], i, p, tipo, tipoBD;
		objParameterIndex = {};
		for(i = 0; i < objParameterList.length; i++){
			p        = objParameterList[i];
			tipo     = fnParamType(p);
			tipoBD   = $.trim(fnParamStr(p.type));
			p.__type = tipo;
			p.__orig = fnParamStr(p.customValue);
			objParameterIndex[String(p.companyParameterID)] = p;
			//tarjeta: encabezado (display, nombre, tipo, ID), descripcion y valor de solo lectura, y editor
			html.push(
				'<div class="cuser-param-card" data-id="' + fnParamEsc(p.companyParameterID) + '">' +
					'<div class="cuser-param-head">' +
						'<div class="cuser-param-title">' + fnParamEsc(p.display || p.name) +
							'<small class="cuser-param-name">' + fnParamEsc(p.name) +
							(tipoBD !== "" ? '<span class="cuser-param-type">' + fnParamEsc(tipoBD) + '</span>' : '') +
							'</small>' +
						'</div>' +
						'<span class="cuser-param-id">ID ' + fnParamEsc(p.companyParameterID) + '</span>' +
					'</div>' +
					'<div class="cuser-param-grid">' +
						'<div class="cuser-param-field is-full"><label>Descripcion</label>' + fnParamReadOnly(p.description) + '</div>' +
						'<div class="cuser-param-field"><label>Valor</label>' + fnParamReadOnly(p.value) + '</div>' +
						'<div class="cuser-param-field"><label>Valor personalizado</label>' + fnParamEditor(p, tipo) + '</div>' +
					'</div>' +
				'</div>'
			);
		}
		var tbody = document.getElementById("tbody_parameter");
		tbody.innerHTML = html.join("");
		//clave de busqueda calculada una sola vez por tarjeta (la propiedad tr guarda el elemento de la tarjeta)
		objParameterRows = [];
		$(tbody).children(".cuser-param-card").each(function(idx){
			var q = objParameterList[idx];
			objParameterRows.push({ tr: this, key: [q.display, q.description, q.name, q.value, q.customValue, q.companyParameterID].join(" ").toLowerCase() });
		});
		fnParamFilter();
		fnParamDirtyCount();
	}

	//Filtro en cliente: todas las palabras escritas deben aparecer en la fila
	function fnParamFilter(){
		var q        = $.trim($("#txtParameterSearch").val() || "").toLowerCase();
		var terms    = q === "" ? [] : q.split(/\s+/);
		var visibles = 0, i, j, ok;
		for(i = 0; i < objParameterRows.length; i++){
			ok = true;
			for(j = 0; j < terms.length; j++){ if(objParameterRows[i].key.indexOf(terms[j]) < 0){ ok = false; break; } }
			objParameterRows[i].tr.style.display = ok ? "" : "none";
			if(ok) visibles++;
		}
		$("#lblParameterCount").text(visibles);
		var msg = objParameterRows.length === 0 ? "No hay parametros editables." : (visibles === 0 ? "Ningun parametro coincide con la busqueda." : "");
		$("#lblParameterEmpty").text(msg);
		$("#divParameterEmpty").toggle(msg !== "");
	}

	//Lee el valor del control en el formato que se guarda
	function fnParamRead(el){
		var tipo = $(el).attr("data-type");
		var v    = $.trim($(el).val() || "");
		var p    = objParameterIndex[$(el).attr("data-id")];
		if(tipo === "bool"){
			if(v === "") return "";
			return fnParamBoolText($.trim(fnParamStr(p.value)) !== "" ? p.value : p.customValue, v === "1");
		}
		return v;
	}
	//Una fila esta modificada si su valor difiere del original (bool sin importar mayusculas)
	function fnParamIsDirty(el){
		var p = objParameterIndex[$(el).attr("data-id")];
		var v = fnParamRead(el);
		return p.__type === "bool" ? v.toLowerCase() !== $.trim(p.__orig).toLowerCase() : v !== p.__orig;
	}
	function fnParamDirtyCount(){
		var n = $("#tbody_parameter .cuser-param-card.is-dirty").length;
		$("#lblParameterDirty").text(n > 0 ? "(" + n + ")" : "");
		return n;
	}

	//Carga la lista (solo la primera vez que se abre el tab, o con Recargar)
	function fnParamLoad(){
		fnWaitOpen();
		$.ajax({
			cache    : false,
			dataType : "json",
			type     : "POST",
			url      : site_url + "core_user/getParameterCompany",
			data     : { companyID: $("#companyID").val() },
			success  : function(data){
				fnWaitClose();
				//caso error
				if(data.error){ fnShowNotification(fnParamEsc(data.message || "No fue posible cargar los parametros"), "error"); return; }
				varParameterLoaded = true;
				//sin datos / con datos: fnParamRender muestra el aviso de vacio
				objParameterList = (data.data && data.data.length) ? data.data : [];
				fnParamRender();
			},
			error    : function(){ fnWaitClose(); fnShowNotification("Error al cargar los parametros", "error"); }
		});
	}

	//Marca en rojo las filas que el servidor rechazo
	function fnParamMarkErrors(errors){
		var mensajes = [], i, $tr;
		for(i = 0; i < errors.length; i++){
			$tr = $("#tbody_parameter .cuser-param-card[data-id='" + String(errors[i].companyParameterID).replace(/[^0-9A-Za-z_\-]/g, "") + "']");
			$tr.addClass("has-error");
			if($tr.length) $tr[0].style.display = "";
			mensajes.push(fnParamEsc((errors[i].display || errors[i].companyParameterID) + ": " + (errors[i].message || "valor no valido")));
		}
		return mensajes;
	}

	//Valida y envia solo las filas modificadas
	function fnParamSave(){
		var cambios = [], errores = 0;
		$("#tbody_parameter .cuser-param-card.is-dirty .jsParamEdit").each(function(){
			var tipo = $(this).attr("data-type"), v = fnParamRead(this), ok = true;
			if(v !== "" && PARAM_REGEX[tipo] && !PARAM_REGEX[tipo].test(v)) ok = false;
			if(v.length > 300) ok = false;
			$(this).closest(".cuser-param-card").toggleClass("has-error", !ok);
			if(!ok){ errores++; return; }
			cambios.push({ companyParameterID: $(this).attr("data-id"), customValue: v });
		});
		if(errores > 0){
			fnShowNotification("Revise los " + errores + " valores marcados en rojo", "error");
			var $f = $("#tbody_parameter .cuser-param-card.has-error").first();
			$f[0].style.display = "";
			$f.find(".jsParamEdit").focus();
			return;
		}
		if(cambios.length === 0){ fnShowNotification("No hay parametros modificados", "warning"); return; }

		fnWaitOpen();
		$.ajax({
			cache       : false,
			dataType    : "json",
			type        : "POST",
			contentType : "application/json; charset=utf-8",
			url         : site_url + "core_user/saveParameterCompany",
			data        : JSON.stringify({ companyID: $("#companyID").val(), items: cambios }),
			success     : function(data){
				fnWaitClose();
				var listaErrores = (data.errors && data.errors.length) ? data.errors : [];
				var i, p;

				//filas rechazadas: el servidor guarda todo o nada, asi que no se confirma ninguna fila
				if(listaErrores.length > 0){
					var mensajes = fnParamMarkErrors(listaErrores);
					fnShowNotification((data.message ? fnParamEsc(data.message) + "<br>" : "") + mensajes.join("<br>"), "error");
					return;
				}
				//caso error general sin detalle por fila: no se confirma nada
				if(data.error){
					fnShowNotification(fnParamEsc(data.message || "No fue posible guardar los parametros"), "error");
					return;
				}
				//guardado correcto: los valores enviados pasan a ser los originales
				for(i = 0; i < cambios.length; i++){
					p = objParameterIndex[cambios[i].companyParameterID];
					if(p){ p.customValue = cambios[i].customValue; p.__orig = cambios[i].customValue; }
					$("#tbody_parameter .cuser-param-card[data-id='" + String(cambios[i].companyParameterID).replace(/[^0-9A-Za-z_\-]/g, "") + "']").removeClass("is-dirty has-error");
				}
				fnParamDirtyCount();
				fnShowNotification(fnParamEsc(data.message || "Parametros guardados"), "success");
			},
			error       : function(){ fnWaitClose(); fnShowNotification("Error al guardar los parametros", "error"); }
		});
	}

	$(document).ready(function(){
		if(!varCanEditParameter) return;

		//carga diferida: primera apertura del tab
		$(document).on("click", "#lnkTabParameter", function(){ if(!varParameterLoaded) fnParamLoad(); });
		//recargar (avisa si hay cambios sin guardar)
		$(document).on("click", "#btnParameterReload", function(e){
			e.preventDefault();
			if(fnParamDirtyCount() > 0) fnShowConfirm("Confirmar..", "Hay parametros modificados sin guardar. Desea recargar?", fnParamLoad);
			else fnParamLoad();
		});
		$(document).on("click", "#btnParameterSave", function(e){ e.preventDefault(); fnParamSave(); });
		//buscador con espera de 150 ms entre teclas
		$(document).on("input", "#txtParameterSearch", function(){
			clearTimeout(varParameterTimer);
			varParameterTimer = setTimeout(fnParamFilter, 150);
		});
		//Enter dentro del tab no debe enviar #form-new-rol
		$(document).on("keydown", "#tabParameter input", function(e){ if(e.which === 13){ e.preventDefault(); } });
		//entero/decimal: solo digitos, "-" al inicio y un solo punto
		$(document).on("input", "#tbody_parameter input.jsParamNum", function(){
			var dec = $(this).attr("data-type") === "dec";
			var v   = this.value.replace(dec ? /[^0-9.\-]/g : /[^0-9\-]/g, "");
			v = v.charAt(0) + v.substring(1).replace(/-/g, "");
			if(dec){ var k = v.indexOf("."); if(k >= 0) v = v.substring(0, k + 1) + v.substring(k + 1).replace(/\./g, ""); }
			if(v !== this.value) this.value = v;
		});
		//fecha en PC: datepicker del tema, creado al recibir el foco
		$(document).on("focus", "#tbody_parameter input.jsParamDate", function(){
			if(!$(this).data("datepicker")) $(this).datepicker({ format: "yyyy-mm-dd" }).datepicker("show");
		});
		//seguimiento de cambios por fila
		$(document).on("input change changeDate", "#tbody_parameter .jsParamEdit", function(){
			$(this).closest(".cuser-param-card").toggleClass("is-dirty", fnParamIsDirty(this)).removeClass("has-error");
			fnParamDirtyCount();
		});
	});
</script>