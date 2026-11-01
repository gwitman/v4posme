/* ==========================================================================
 * posme-role.js
 * Pantallas core_role/add y core_role/edit: matriz de permisos amigable.
 *
 * 1) Funciones puras (sin DOM) en window.PosmeRole: normalizarTypeApp, agrupar,
 *    estadoInicial, aplicar, fijar, camposPost, contar.
 * 2) Capa DOM: PosmeRole.init(config) (jQuery 1.9 + Bootstrap 3 del masterpage).
 *
 * Contrato del POST a core_role/save (NO cambiar):
 *   txtElementID[], txtSelectedID[id], txtInsertedID[id], txtDeletedID[id], txtEditedID[id],
 *   txtComponentAutorizationID[]
 * Los hidden se generan dentro de #pmRolePost justo antes del submit.
 * ES5: sin arrow functions, let/const ni template literals.
 * ========================================================================== */
(function (root) {
	'use strict';

	var CAMPOS = ['selected', 'inserted', 'edited', 'deleted'];

	var PosmeRole = {};

	PosmeRole.NIVELES = [-1, 0, 1, 2];
	PosmeRole.PRESETS = {
		total	: { selected: 0, inserted: 0, edited: 0, deleted: 0 },
		ver		: { selected: 0, inserted: -1, edited: -1, deleted: -1 }
	};
	PosmeRole.NOMBRES_NIVEL = { '-1': 'Ninguno', '0': 'Todos', '1': 'Sucursal', '2': 'Propios' };
	PosmeRole.TIPOS_MENU = { '4': 'Menú superior', '5': 'Menú', '6': 'Reporte', '8': 'Interno' };

	/* ---------------------------------------------------------------- utilidades */
	function esVacio(v) {
		return v === null || v === undefined || v === '';
	}
	function aEntero(v) {
		if (esVacio(v)) return NaN;
		var s = String(v).replace(/^\s+|\s+$/g, '');
		if (!/^-?\d+$/.test(s)) return NaN;
		return parseInt(s, 10);
	}
	function nivelValido(v) {
		var n = aEntero(v);
		if (isNaN(n)) return -1;
		for (var i = 0; i < PosmeRole.NIVELES.length; i++) {
			if (PosmeRole.NIVELES[i] === n) return n;
		}
		return -1;
	}
	function clave(id) {
		var n = aEntero(id);
		return isNaN(n) ? null : String(n);
	}
	function copiarEstado(estado) {
		var nuevo = {};
		if (!estado) return nuevo;
		for (var k in estado) {
			if (Object.prototype.hasOwnProperty.call(estado, k)) {
				var e = estado[k];
				nuevo[k] = { selected: e.selected, inserted: e.inserted, deleted: e.deleted, edited: e.edited };
			}
		}
		return nuevo;
	}
	function comoLista(x) {
		if (!x) return [];
		if (Object.prototype.toString.call(x) === '[object Array]') return x;
		var r = [];
		for (var k in x) {
			if (Object.prototype.hasOwnProperty.call(x, k)) r.push(x[k]);
		}
		return r;
	}
	PosmeRole.comoLista = comoLista;

	/* Texto sin acentos y en minusculas (para el buscador) */
	PosmeRole.sinAcentos = function (s) {
		return String(esVacio(s) ? '' : s).toLowerCase()
			.replace(/[áàäâã]/g, 'a')
			.replace(/[éèëê]/g, 'e')
			.replace(/[íìïî]/g, 'i')
			.replace(/[óòöôõ]/g, 'o')
			.replace(/[úùüû]/g, 'u')
			.replace(/ñ/g, 'n')
			.replace(/ç/g, 'c');
	};

	/* ---------------------------------------------------------------- API pura */
	PosmeRole.normalizarTypeApp = function (t) {
		if (esVacio(t)) return 'default';
		return t;
	};

	/*
	 * Agrupa las pantallas del typeApp indicado por modulo (ancestro raiz).
	 * La raiz se busca en TODA la lista (sin filtrar por typeApp).
	 */
	PosmeRole.agrupar = function (listMenuElement, typeApp) {
		var lista	= comoLista(listMenuElement);
		var tipo	= PosmeRole.normalizarTypeApp(typeApp);
		var porMenu	= {};
		var i, m;

		for (i = 0; i < lista.length; i++) {
			m = lista[i];
			if (!m) continue;
			var km = clave(m.menuElementID);
			if (km !== null && !porMenu.hasOwnProperty(km)) porMenu[km] = m;
		}

		function raiz(item) {
			var actual	= item;
			var vistos	= {};
			var kActual	= clave(actual.menuElementID);
			if (kActual !== null) vistos[kActual] = true;
			while (true) {
				var kp = clave(actual.parentMenuElementID);
				if (kp === null || kp === '0' || !porMenu.hasOwnProperty(kp) || vistos[kp]) break;
				vistos[kp]	= true;
				actual		= porMenu[kp];
			}
			return actual;
		}

		var grupos		= [];
		var porModulo	= {};
		var vistosElem	= {};

		for (i = 0; i < lista.length; i++) {
			m = lista[i];
			if (!m) continue;
			if (PosmeRole.normalizarTypeApp(m.typeApp) !== tipo) continue;
			var ke = clave(m.elementID);
			if (ke === null || vistosElem[ke]) continue;
			vistosElem[ke] = true;

			var r	= raiz(m);
			var kr	= clave(r.menuElementID);
			if (kr === null) kr = 'e' + ke;
			var g = porModulo[kr];
			if (!g) {
				g = {
					moduloID	: aEntero(r.menuElementID),
					nombre		: esVacio(r.display) ? '' : String(r.display),
					icon		: esVacio(r.icon) ? '' : String(r.icon),
					items		: [],
					_raizElem	: clave(r.elementID)
				};
				porModulo[kr] = g;
				grupos.push(g);
			}
			g.items.push({
				elementID			: parseInt(ke, 10),
				display				: esVacio(m.display) ? '' : String(m.display),
				typeMenuElementID	: isNaN(aEntero(m.typeMenuElementID)) ? null : aEntero(m.typeMenuElementID),
				icon				: esVacio(m.icon) ? '' : String(m.icon),
				orden				: esVacio(m.orden) ? '' : String(m.orden)
			});
		}

		/* el elemento raiz (si es pantalla del grupo) va primero */
		for (i = 0; i < grupos.length; i++) {
			var gr = grupos[i];
			if (gr._raizElem !== null) {
				for (var j = 1; j < gr.items.length; j++) {
					if (String(gr.items[j].elementID) === gr._raizElem) {
						var it = gr.items.splice(j, 1)[0];
						gr.items.unshift(it);
						break;
					}
				}
			}
			delete gr._raizElem;
		}
		return grupos;
	};

	/* { "<elementID>": {selected,inserted,deleted,edited} } (primera aparicion manda) */
	PosmeRole.estadoInicial = function (listUserPermission) {
		var lista	= comoLista(listUserPermission);
		var estado	= {};
		for (var i = 0; i < lista.length; i++) {
			var p = lista[i];
			if (!p) continue;
			var k = clave(p.elementID);
			if (k === null || estado.hasOwnProperty(k)) continue;
			estado[k] = {
				selected	: nivelValido(p.selected),
				inserted	: nivelValido(p.inserted),
				deleted		: nivelValido(p.deleted),
				edited		: nivelValido(p.edited)
			};
		}
		return estado;
	};

	/* Aplica un preset ('total' / 'ver') o quita el acceso (null). No muta el original. */
	PosmeRole.aplicar = function (estado, elementIDs, presetNombre) {
		var nuevo	= copiarEstado(estado);
		var ids		= comoLista(elementIDs);
		var preset	= null;
		if (presetNombre !== null && presetNombre !== undefined) {
			preset = PosmeRole.PRESETS[presetNombre];
			if (!preset) return nuevo;
		}
		for (var i = 0; i < ids.length; i++) {
			var k = clave(ids[i]);
			if (k === null) continue;
			if (preset === null) {
				delete nuevo[k];
			}
			else {
				nuevo[k] = { selected: preset.selected, inserted: preset.inserted, deleted: preset.deleted, edited: preset.edited };
			}
		}
		return nuevo;
	};

	/* Cambia un solo nivel (campo = selected/inserted/edited/deleted). No muta el original. */
	PosmeRole.fijar = function (estado, elementID, campo, valor) {
		var nuevo	= copiarEstado(estado);
		var k		= clave(elementID);
		if (k === null || CAMPOS.indexOf(campo) === -1) return nuevo;
		if (!nuevo.hasOwnProperty(k)) nuevo[k] = { selected: -1, inserted: -1, deleted: -1, edited: -1 };
		nuevo[k][campo] = nivelValido(valor);
		return nuevo;
	};

	/* Lista [{name,value}] para el POST de core_role/save */
	PosmeRole.camposPost = function (estado, autorizacionIDs) {
		var campos	= [];
		var ids		= [];
		var k;
		if (estado) {
			for (k in estado) {
				if (Object.prototype.hasOwnProperty.call(estado, k) && clave(k) !== null) ids.push(parseInt(k, 10));
			}
		}
		ids.sort(function (a, b) { return a - b; });
		for (var i = 0; i < ids.length; i++) {
			var id	= ids[i];
			var e	= estado[String(id)];
			campos.push({ name: 'txtElementID[]', value: String(id) });
			campos.push({ name: 'txtSelectedID[' + id + ']', value: String(nivelValido(e.selected)) });
			campos.push({ name: 'txtInsertedID[' + id + ']', value: String(nivelValido(e.inserted)) });
			campos.push({ name: 'txtDeletedID[' + id + ']', value: String(nivelValido(e.deleted)) });
			campos.push({ name: 'txtEditedID[' + id + ']', value: String(nivelValido(e.edited)) });
		}
		var auts	= comoLista(autorizacionIDs);
		var vistas	= {};
		for (var j = 0; j < auts.length; j++) {
			if (esVacio(auts[j])) continue;
			var a = String(auts[j]);
			if (vistas[a]) continue;
			vistas[a] = true;
			campos.push({ name: 'txtComponentAutorizationID[]', value: a });
		}
		return campos;
	};

	/* Cantidad de pantallas visibles con acceso */
	PosmeRole.contar = function (estado, elementIDsVisibles) {
		var ids		= comoLista(elementIDsVisibles);
		var vistos	= {};
		var n		= 0;
		if (!estado) return 0;
		for (var i = 0; i < ids.length; i++) {
			var k = clave(ids[i]);
			if (k === null || vistos[k]) continue;
			vistos[k] = true;
			if (Object.prototype.hasOwnProperty.call(estado, k)) n++;
		}
		return n;
	};

	/* ======================================================================
	 * Capa DOM
	 * ====================================================================== */
	function esc(s) {
		return String(esVacio(s) ? '' : s)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}
	function claseIcono(icon) {
		return String(esVacio(icon) ? '' : icon).replace(/[^\w\- ]/g, '');
	}
	function plural(n, uno, varios) {
		return n + ' ' + (n === 1 ? uno : varios);
	}
	function avisar(msg, tipo) {
		if (typeof root.fnShowNotification === 'function') root.fnShowNotification(msg, tipo);
		else if (typeof root.alert === 'function') root.alert(msg);
	}
	function confirmar(titulo, msg, fn) {
		if (typeof root.fnShowConfirm === 'function') root.fnShowConfirm(titulo, msg, fn);
		else if (typeof root.confirm === 'function' && root.confirm(msg)) fn();
	}
	function htmlSelect(campo, etiqueta) {
		var h = '<label class="pm-role-nivel"><span class="pm-role-nivel-txt">' + etiqueta + '</span>' +
			'<select class="form-control pm-role-sel" data-campo="' + campo + '">';
		for (var i = 0; i < PosmeRole.NIVELES.length; i++) {
			var n = PosmeRole.NIVELES[i];
			h += '<option value="' + n + '">' + PosmeRole.NOMBRES_NIVEL[String(n)] + '</option>';
		}
		return h + '</select></label>';
	}

	PosmeRole.init = function (config) {
		var $ = root.jQuery;
		if (!$) return;
		config = config || {};

		var listMenu		= comoLista(config.listMenuElement);
		var estado			= PosmeRole.estadoInicial(config.listUserPermission);
		var autorizaciones	= comoLista(config.autorizaciones);
		var autorizacionesRol = comoLista(config.autorizacionesRol);
		var grupos			= [];
		var idsActuales		= [];
		var enviando		= false;

		var $raiz		= $('#pmRole');
		var $form		= $('#form-new-rol');
		var $grupos		= $('#pmRoleGrupos');
		var $buscar		= $('#pmRoleBuscar');
		var $soloAcceso	= $('#pmRoleSoloAcceso');
		var $typeApp	= $('#txtTypeApp');

		/* ---------- Autorizaciones ---------- */
		function pintarAutorizaciones() {
			var marcadas	= {};
			var lista		= [];
			var vistos		= {};
			var i, a, k;
			for (i = 0; i < autorizacionesRol.length; i++) {
				a = autorizacionesRol[i];
				if (a && !esVacio(a.componentAutorizationID)) marcadas[String(a.componentAutorizationID)] = true;
			}
			for (i = 0; i < autorizaciones.length; i++) {
				a = autorizaciones[i];
				if (!a || esVacio(a.componentAutorizationID)) continue;
				k = String(a.componentAutorizationID);
				if (vistos[k]) continue;
				vistos[k] = true;
				lista.push({ id: k, name: a.name });
			}
			/* asignadas que no vienen en la lista completa: se conservan */
			for (i = 0; i < autorizacionesRol.length; i++) {
				a = autorizacionesRol[i];
				if (!a || esVacio(a.componentAutorizationID)) continue;
				k = String(a.componentAutorizationID);
				if (vistos[k]) continue;
				vistos[k] = true;
				lista.push({ id: k, name: a.name });
			}

			var $c = $('#pmRoleAutorizaciones');
			if (lista.length === 0) {
				$c.html('<div class="pm-role-vacio">No hay autorizaciones configuradas para esta empresa.</div>');
				$('#pmRoleAutTodas, #pmRoleAutNinguna').prop('disabled', true);
				return;
			}
			var h = '';
			for (i = 0; i < lista.length; i++) {
				h += '<label class="pm-role-aut">' +
					'<input type="checkbox" class="pm-role-aut-chk" value="' + esc(lista[i].id) + '"' + (marcadas[lista[i].id] ? ' checked="checked"' : '') + ' /> ' +
					'<span>' + esc(lista[i].name) + '</span></label>';
			}
			$c.html(h);
		}
		function autorizacionesMarcadas() {
			var r = [];
			$('#pmRoleAutorizaciones .pm-role-aut-chk:checked').each(function () { r.push($(this).val()); });
			return r;
		}

		/* ---------- Matriz de permisos ---------- */
		function pintarGrupos() {
			grupos		= PosmeRole.agrupar(listMenu, $typeApp.val());
			idsActuales	= [];
			var h		= '';
			var i, j;
			if (grupos.length === 0) {
				$grupos.html('<div class="pm-role-vacio">No hay pantallas para el tipo de app seleccionado.</div>');
			}
			else {
				for (i = 0; i < grupos.length; i++) {
					var g = grupos[i];
					h += '<div class="pm-role-grupo" data-grupo="' + i + '">' +
						'<div class="pm-role-grupo-head">' +
							'<button type="button" class="pm-role-grupo-toggle" title="Mostrar / ocultar">' +
								'<span class="pm-role-flecha"></span>' +
								(claseIcono(g.icon) ? '<i class="icon16 ' + claseIcono(g.icon) + '"></i>' : '') +
								'<span class="pm-role-grupo-nombre">' + esc(g.nombre) + '</span>' +
								'<span class="pm-role-grupo-cuenta"></span>' +
							'</button>' +
							'<div class="pm-role-grupo-acciones">' +
								'<button type="button" class="btn btn-default btn-sm pm-role-mod" data-preset="total">Total</button>' +
								'<button type="button" class="btn btn-default btn-sm pm-role-mod" data-preset="ver">Solo ver</button>' +
								'<button type="button" class="btn btn-default btn-sm pm-role-mod" data-preset="">Quitar</button>' +
							'</div>' +
						'</div>' +
						'<div class="pm-role-grupo-body">';
					for (j = 0; j < g.items.length; j++) {
						var it		= g.items[j];
						var tipoTxt	= PosmeRole.TIPOS_MENU[String(it.typeMenuElementID)] || 'Otro';
						idsActuales.push(it.elementID);
						h += '<div class="pm-role-fila" data-id="' + it.elementID + '" data-buscar="' + esc(PosmeRole.sinAcentos(it.display)) + '">' +
								'<div class="pm-role-fila-nombre">' +
									'<span class="pm-role-nombre">' + esc(it.display) + '</span>' +
									'<span class="pm-role-tipo pm-role-tipo-' + esc(it.typeMenuElementID) + '">' + tipoTxt + '</span>' +
								'</div>' +
								'<label class="pm-role-switch" title="Acceso a la pantalla">' +
									'<input type="checkbox" class="pm-role-acceso" />' +
									'<span class="pm-role-switch-ui"></span>' +
									'<span class="pm-role-switch-txt">Acceso</span>' +
								'</label>' +
								'<div class="pm-role-niveles">' +
									htmlSelect('selected', 'Ver') +
									htmlSelect('inserted', 'Crear') +
									htmlSelect('edited', 'Editar') +
									htmlSelect('deleted', 'Eliminar') +
								'</div>' +
							'</div>';
					}
					h += '</div></div>';
				}
				$grupos.html(h);
			}
			$grupos.find('.pm-role-fila').each(function () { pintarFila($(this)); });
			filtrar();
		}

		function pintarFila($fila) {
			var id		= String($fila.attr('data-id'));
			var e		= estado[id];
			var tiene	= !!e;
			$fila.toggleClass('pm-role-fila-sin', !tiene);
			$fila.find('.pm-role-acceso').prop('checked', tiene);
			$fila.find('.pm-role-sel').each(function () {
				var campo = $(this).attr('data-campo');
				$(this).val(String(tiene ? e[campo] : -1)).prop('disabled', !tiene);
			});
		}
		function pintarFilas(ids) {
			for (var i = 0; i < ids.length; i++) {
				pintarFila($grupos.find('.pm-role-fila[data-id="' + ids[i] + '"]'));
			}
		}

		function conservados() {
			var actuales = {};
			var n = 0;
			for (var i = 0; i < idsActuales.length; i++) actuales[String(idsActuales[i])] = true;
			for (var k in estado) {
				if (Object.prototype.hasOwnProperty.call(estado, k) && !actuales[k]) n++;
			}
			return n;
		}

		function actualizarContadores() {
			var total	= idsActuales.length;
			var conAcc	= PosmeRole.contar(estado, idsActuales);
			$('#pmRoleContador').text(conAcc + ' de ' + total + ' pantallas con acceso');
			$('#pmRoleContadorMovil').text(plural(conAcc, 'pantalla con acceso', 'pantallas con acceso'));

			var nc = conservados();
			if (nc > 0) {
				$('#pmRoleConservados').text(plural(nc, 'permiso de otras pantallas se conserva', 'permisos de otras pantallas se conservan') + ' (otro tipo de app o menú inactivo).').show();
			}
			else {
				$('#pmRoleConservados').hide();
			}

			$grupos.find('.pm-role-grupo').each(function () {
				var g = grupos[parseInt($(this).attr('data-grupo'), 10)];
				if (!g) return;
				var ids = [];
				for (var i = 0; i < g.items.length; i++) ids.push(g.items[i].elementID);
				var k = PosmeRole.contar(estado, ids);
				$(this).find('.pm-role-grupo-cuenta').text(k + '/' + ids.length);
				$(this).toggleClass('pm-role-grupo-con', k > 0);
			});
		}

		function filtrar() {
			var texto	= PosmeRole.sinAcentos($buscar.val()).replace(/^\s+|\s+$/g, '');
			var solo	= $soloAcceso.is(':checked');
			$raiz.toggleClass('pm-role-buscando', texto !== '');
			$grupos.find('.pm-role-grupo').each(function () {
				var $g		= $(this);
				var visibles = 0;
				$g.find('.pm-role-fila').each(function () {
					var $f	= $(this);
					var ok	= true;
					if (texto !== '' && String($f.attr('data-buscar')).indexOf(texto) === -1) ok = false;
					if (ok && solo && !estado.hasOwnProperty(String($f.attr('data-id')))) ok = false;
					$f.toggleClass('pm-role-oculta', !ok);
					if (ok) visibles++;
				});
				$g.toggleClass('pm-role-oculta', visibles === 0);
			});
			var hayGrupos = grupos.length > 0;
			var hayVisibles = $grupos.find('.pm-role-fila').not('.pm-role-oculta').length > 0;
			$('#pmRoleSinResultados').toggle(hayGrupos && !hayVisibles);
			actualizarContadores();
		}

		function idsVisibles($contenedor) {
			var r = [];
			$contenedor.find('.pm-role-fila').not('.pm-role-oculta').each(function () {
				r.push(parseInt($(this).attr('data-id'), 10));
			});
			return r;
		}

		function aplicarA(ids, preset) {
			if (ids.length === 0) {
				avisar('No hay pantallas visibles para aplicar el cambio.', 'error');
				return;
			}
			estado = PosmeRole.aplicar(estado, ids, preset);
			pintarFilas(ids);
			filtrar();
		}

		/* ---------- Eventos ---------- */
		$typeApp.on('change', function () { pintarGrupos(); });
		$buscar.on('keyup input', function () { filtrar(); });
		$soloAcceso.on('change', function () { filtrar(); });

		$raiz.on('click', '#pmRoleTotal', function () { aplicarA(idsVisibles($grupos), 'total'); });
		$raiz.on('click', '#pmRoleVer', function () { aplicarA(idsVisibles($grupos), 'ver'); });
		$raiz.on('click', '#pmRoleQuitar', function () { aplicarA(idsVisibles($grupos), null); });

		$grupos.on('click', '.pm-role-grupo-toggle', function () {
			$(this).closest('.pm-role-grupo').toggleClass('pm-role-grupo-cerrado');
		});
		$grupos.on('click', '.pm-role-mod', function () {
			var p = $(this).attr('data-preset');
			aplicarA(idsVisibles($(this).closest('.pm-role-grupo')), p === '' ? null : p);
		});
		$grupos.on('change', '.pm-role-acceso', function () {
			var id = parseInt($(this).closest('.pm-role-fila').attr('data-id'), 10);
			estado = PosmeRole.aplicar(estado, [id], $(this).is(':checked') ? 'total' : null);
			pintarFilas([id]);
			filtrar();
		});
		$grupos.on('change', '.pm-role-sel', function () {
			var $f = $(this).closest('.pm-role-fila');
			var id = parseInt($f.attr('data-id'), 10);
			estado = PosmeRole.fijar(estado, id, $(this).attr('data-campo'), $(this).val());
			pintarFila($f);
			actualizarContadores();
		});

		$raiz.on('click', '#pmRoleAutTodas', function () {
			$('#pmRoleAutorizaciones .pm-role-aut-chk').prop('checked', true);
		});
		$raiz.on('click', '#pmRoleAutNinguna', function () {
			$('#pmRoleAutorizaciones .pm-role-aut-chk').prop('checked', false);
		});

		/* Enter en un input no debe enviar el formulario */
		$form.on('keydown', 'input', function (e) {
			if ((e.keyCode || e.which) === 13) {
				e.preventDefault();
				return false;
			}
		});
		$form.on('submit', function (e) {
			if (!enviando) {
				e.preventDefault();
				guardar();
				return false;
			}
		});
		$raiz.on('click', '.pm-role-guardar', function () { guardar(); });
		$raiz.on('click', '#btnBack', function () {
			if (typeof root.fnWaitOpen === 'function') root.fnWaitOpen();
		});

		/* ---------- Guardar ---------- */
		function validar() {
			var obligatorios = [
				['#txtName', 'Escriba el nombre del rol.'],
				['#txtUrlDefault', 'Escriba la url por defecto.'],
				['#txtDescription', 'Escriba la descripción del rol.']
			];
			for (var i = 0; i < obligatorios.length; i++) {
				var $c = $(obligatorios[i][0]);
				if ($.trim($c.val()) === '') {
					avisar(obligatorios[i][1], 'error');
					$c.focus();
					return false;
				}
			}
			return true;
		}
		function enviar() {
			var $post	= $('#pmRolePost');
			var campos	= PosmeRole.camposPost(estado, autorizacionesMarcadas());
			$post.empty();
			for (var i = 0; i < campos.length; i++) {
				$('<input type="hidden" />').attr('name', campos[i].name).val(campos[i].value).appendTo($post);
			}
			enviando = true;
			$form.attr('method', 'POST');
			if (config.urlSave) $form.attr('action', config.urlSave);
			if (typeof root.fnWaitOpen === 'function') root.fnWaitOpen();
			$form[0].submit();
		}
		function guardar() {
			if (enviando) return;
			if (!validar()) return;
			if (PosmeRole.contar(estado, idsActuales) === 0) {
				confirmar('Confirmar..', 'El rol no tendrá acceso a ninguna pantalla. ¿Desea guardar de todos modos?', enviar);
				return;
			}
			enviar();
		}

		/* ---------- Arranque ---------- */
		pintarAutorizaciones();
		pintarGrupos();
	};

	root.PosmeRole = PosmeRole;
	if (typeof module !== 'undefined' && module.exports) module.exports = PosmeRole;
})(typeof window !== 'undefined' ? window : this);
