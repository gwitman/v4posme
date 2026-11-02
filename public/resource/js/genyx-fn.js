function selectMenu(current_url) {
  current_url =
    current_url.split("/")[0] +
    "/" +
    current_url.split("/")[1] +
    "/" +
    current_url.split("/")[2] +
    "/" +
    current_url.split("/")[3] +
    "/" +
    current_url.split("/")[4] +
    "/index.aspx";

  //Validar si es un menu superior
  if (
    $("a[href='" + current_url + "']")
      .parent()
      .parent()
      .parent()
      .parent()
      .hasClass("nav navbar-nav pull-right")
  )
    return;

  $("a[href='" + current_url + "']")
    .closest("li")
    .addClass("current");
  $("a[href='" + current_url + "']")
    .closest("li")
    .closest("ul")
    .addClass("expand");
  $("a[href='" + current_url + "']")
    .closest("li")
    .closest("ul")
    .addClass("show");
  $("a[href='" + current_url + "']")
    .closest("li")
    .closest("ul")
    .closest("li")
    .addClass("current");
  $("a[href='" + current_url + "']")
    .closest("li")
    .closest("ul")
    .closest("li");
  $("a[href='" + current_url + "']")
    .closest("li")
    .closest("ul")
    .closest("li")
    .find("a")
    .first()
    .removeClass("notExpand");
  $("a[href='" + current_url + "']")
    .closest("li")
    .closest("ul")
    .closest("li")
    .find("a")
    .first()
    .addClass("expand");
}

function fnTableSelectedRowMultiSelect(obj, event) {
  if ($(event.target.parentNode).hasClass("row-selected")) {
    $(event.target.parentNode).removeClass("row-selected");
  } else {
    $(event.target.parentNode).addClass("row-selected");
  }
}
function fnTableSelectedRow(obj, event) {
  $(event.target.parentElement.parentElement)
    .find("tr")
    .removeClass("row-selected");
  $(event.target.parentNode).addClass("row-selected");
}

function fnShowMessageSuccess(message) {
  var _success =
    "" +
    '<div class="alert alert-success" style="margin-top:15px">' +
    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
    '<strong><i class="icon24 i-checkmark-circle"></i> Success!</strong>' +
    message +
    "</div>";

  $("#main_content").prepend(_success);
}
function fnShowMessageError(message) {
  var _error =
    "" +
    '<div class="alert alert-error" style="margin-top:15px">' +
    '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
    '<strong><i class="icon24 i-close-4"></i> Error!</strong>' +
    message +
    "</div>";
  $("#main_content").prepend(_error);
}
/*
 * Notificaciones tipo "toast" de posMe (reemplaza a jGrowl en fnShowNotification).
 * - JavaScript puro: no depende de jQuery ni de jGrowl; el CSS se inserta una sola vez.
 * - Paleta de la pantalla de ABONOS: verde exito, rojo error, ambar atencion, azul informacion.
 * - Computadora: arriba a la derecha. Telefono: arriba a todo el ancho (no choca con las barras inferiores).
 * - Barra de tiempo, pausa al pasar el mouse / tocar, boton cerrar, maximo 4 visibles
 *   y los mensajes repetidos se agrupan con un contador (x2, x3...).
 */
var PosmeToast = {
  MAX_VISIBLES: 4,
  DURACION_MAXIMA: 10000,
  TIPOS: {
    success: { titulo: "Listo", icono: "&#10003;", rol: "status" },
    error: { titulo: "Error", icono: "&#10005;", rol: "alert" },
    warning: { titulo: "Atención", icono: "!", rol: "status" },
    info: { titulo: "Información", icono: "i", rol: "status" },
  },

  /**
   * Normaliza el tipo recibido; cualquier valor desconocido se trata como "info".
   * @param {string} type Tipo enviado por la pantalla (success, error, warning, info...).
   * @return {string} Tipo valido.
   */
  tipo: function (type) {
    var t = (type || "").toString().toLowerCase().trim();
    if (t === "danger") t = "error";
    if (t === "warn" || t === "alert") t = "warning";
    return PosmeToast.TIPOS.hasOwnProperty(t) ? t : "info";
  },

  /**
   * Calcula cuanto tiempo se muestra la notificacion.
   * Sin tiempo: exito/info 4s, error/atencion 7s. Con tiempo: se respeta con un maximo de 10s.
   * @param {string} tipo Tipo ya normalizado.
   * @param {number} time Tiempo en milisegundos enviado por la pantalla (opcional).
   * @return {number} Duracion en milisegundos.
   */
  duracion: function (tipo, time) {
    var ms = parseInt(time, 10);
    if (isNaN(ms) || ms <= 0) {
      return tipo === "error" || tipo === "warning" ? 7000 : 4000;
    }
    return Math.min(Math.max(ms, 1500), PosmeToast.DURACION_MAXIMA);
  },

  /**
   * Clave para detectar mensajes repetidos (mismo tipo y mismo texto).
   * @return {string}
   */
  clave: function (tipo, message) {
    return tipo + "|" + (message === undefined || message === null ? "" : String(message)).replace(/\s+/g, " ").trim();
  },

  // Inserta el CSS de las notificaciones una sola vez
  insertarEstilo: function () {
    if (document.getElementById("pmToastStyle")) return;
    var css =
      "#pmToastStack{position:fixed;top:16px;right:16px;z-index:100000;display:flex;flex-direction:column;gap:10px;width:380px;max-width:calc(100vw - 32px);pointer-events:none;font-family:'Segoe UI',Roboto,'Open Sans',Arial,sans-serif;}" +
      ".pm-toast{pointer-events:auto;position:relative;display:flex;align-items:flex-start;gap:12px;padding:12px 40px 14px 14px;background:#FFFFFF;border:1px solid #E3E6EA;border-left:5px solid var(--pmt-color);border-radius:12px;box-shadow:0 10px 30px rgba(16,24,40,.16);overflow:hidden;opacity:0;transform:translateX(24px);transition:opacity .22s ease,transform .22s ease;}" +
      ".pm-toast.is-visible{opacity:1;transform:none;}" +
      ".pm-toast.is-saliendo{opacity:0;transform:translateX(24px);}" +
      ".pm-toast-success{--pmt-color:#2E7D32;--pmt-bg:#EAF5EA;}" +
      ".pm-toast-error{--pmt-color:#C62828;--pmt-bg:#FDECEC;}" +
      ".pm-toast-warning{--pmt-color:#B26A00;--pmt-bg:#FDF3DC;}" +
      ".pm-toast-info{--pmt-color:#1565C0;--pmt-bg:#E8F1FC;}" +
      ".pm-toast-icono{flex:0 0 28px;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--pmt-bg);color:var(--pmt-color);font-weight:800;font-size:14px;line-height:1;}" +
      ".pm-toast-cuerpo{flex:1 1 auto;min-width:0;}" +
      ".pm-toast-titulo{margin:0 0 2px;font-size:14px;font-weight:700;color:#1F2933;line-height:1.3;}" +
      ".pm-toast-contador{display:inline-block;margin-left:6px;padding:0 7px;border-radius:20px;background:var(--pmt-bg);color:var(--pmt-color);font-size:11px;font-weight:700;vertical-align:middle;}" +
      ".pm-toast-texto{margin:0;font-size:13px;color:#475569;line-height:1.45;word-break:break-word;max-height:40vh;overflow-y:auto;}" +
      ".pm-toast-cerrar{position:absolute;top:8px;right:8px;width:28px;height:28px;border:0;border-radius:8px;background:transparent;color:#6B7280;font-size:18px;line-height:1;cursor:pointer;}" +
      ".pm-toast-cerrar:hover,.pm-toast-cerrar:focus{background:#F1F5F9;color:#1F2933;outline:0;}" +
      ".pm-toast-barra{position:absolute;left:0;bottom:0;height:3px;width:100%;background:var(--pmt-color);opacity:.35;transform-origin:left center;}" +
      "@media (max-width:767px){#pmToastStack{top:8px;left:8px;right:8px;width:auto;max-width:none;}" +
      ".pm-toast{transform:translateY(-16px);}.pm-toast.is-saliendo{transform:translateY(-16px);}" +
      ".pm-toast-titulo{font-size:15px;}.pm-toast-texto{font-size:14px;}.pm-toast-cerrar{width:40px;height:40px;top:4px;right:4px;}}" +
      "@media print{#pmToastStack{display:none !important;}}";
    var style = document.createElement("style");
    style.id = "pmToastStyle";
    style.appendChild(document.createTextNode(css));
    (document.head || document.documentElement).appendChild(style);
  },

  // Contenedor de las notificaciones (se crea al primer uso)
  contenedor: function () {
    var stack = document.getElementById("pmToastStack");
    if (!stack) {
      stack = document.createElement("div");
      stack.id = "pmToastStack";
      document.body.appendChild(stack);
    }
    return stack;
  },

  // Cierra una notificacion con animacion
  cerrar: function (toast) {
    if (!toast || toast.getAttribute("data-cerrando") === "1") return;
    toast.setAttribute("data-cerrando", "1");
    clearTimeout(toast._pmTimer);
    toast.classList.add("is-saliendo");
    setTimeout(function () {
      if (toast.parentNode) toast.parentNode.removeChild(toast);
    }, 230);
  },

  // Programa el cierre y anima la barra con el tiempo restante
  iniciarTiempo: function (toast) {
    var barra = toast.querySelector(".pm-toast-barra");
    clearTimeout(toast._pmTimer);
    toast._pmInicio = Date.now();
    if (barra) {
      var fraccion = toast._pmRestante / toast._pmDuracion;
      barra.style.transition = "none";
      barra.style.transform = "scaleX(" + fraccion + ")";
      void barra.offsetWidth;
      barra.style.transition = "transform " + toast._pmRestante + "ms linear";
      barra.style.transform = "scaleX(0)";
    }
    toast._pmTimer = setTimeout(function () {
      PosmeToast.cerrar(toast);
    }, toast._pmRestante);
  },

  // Pausa el cierre (mouse encima o toque)
  pausar: function (toast) {
    if (toast.getAttribute("data-cerrando") === "1") return;
    clearTimeout(toast._pmTimer);
    toast._pmRestante = Math.max(toast._pmRestante - (Date.now() - toast._pmInicio), 800);
    var barra = toast.querySelector(".pm-toast-barra");
    if (barra) {
      barra.style.transition = "none";
      barra.style.transform = "scaleX(" + toast._pmRestante / toast._pmDuracion + ")";
    }
  },

  /**
   * Muestra una notificacion.
   * @param {string} message Texto o HTML del mensaje (el servidor envia </br> en listas).
   * @param {string} type    success | error | warning | info.
   * @param {number} time    Milisegundos (opcional).
   * @return {HTMLElement} La notificacion creada o la existente si el mensaje se repitio.
   */
  mostrar: function (message, type, time) {
    var tipo = PosmeToast.tipo(type);
    var config = PosmeToast.TIPOS[tipo];
    var duracion = PosmeToast.duracion(tipo, time);
    var clave = PosmeToast.clave(tipo, message);

    PosmeToast.insertarEstilo();
    var stack = PosmeToast.contenedor();

    // Mensaje repetido: se reutiliza la tarjeta, aumenta el contador y se reinicia el tiempo
    var hijos = stack.children;
    for (var i = 0; i < hijos.length; i++) {
      if (hijos[i].getAttribute("data-clave") === clave && hijos[i].getAttribute("data-cerrando") !== "1") {
        var repetido = hijos[i];
        var veces = parseInt(repetido.getAttribute("data-veces"), 10) + 1;
        repetido.setAttribute("data-veces", veces);
        repetido.querySelector(".pm-toast-contador").textContent = "×" + veces;
        repetido.querySelector(".pm-toast-contador").style.display = "inline-block";
        repetido._pmDuracion = duracion;
        repetido._pmRestante = duracion;
        PosmeToast.iniciarTiempo(repetido);
        return repetido;
      }
    }

    var toast = document.createElement("div");
    toast.className = "pm-toast pm-toast-" + tipo;
    toast.setAttribute("role", config.rol);
    toast.setAttribute("aria-live", config.rol === "alert" ? "assertive" : "polite");
    toast.setAttribute("data-clave", clave);
    toast.setAttribute("data-veces", "1");
    toast.innerHTML =
      '<span class="pm-toast-icono" aria-hidden="true">' + config.icono + "</span>" +
      '<div class="pm-toast-cuerpo">' +
      '<p class="pm-toast-titulo">' + config.titulo + '<span class="pm-toast-contador" style="display:none"></span></p>' +
      '<div class="pm-toast-texto"></div>' +
      "</div>" +
      '<button type="button" class="pm-toast-cerrar" aria-label="Cerrar notificación">&times;</button>' +
      '<span class="pm-toast-barra" aria-hidden="true"></span>';
    // El mensaje se inserta como HTML para conservar los saltos (</br>) que envia el servidor
    toast.querySelector(".pm-toast-texto").innerHTML = message === undefined || message === null ? "" : String(message);

    toast.querySelector(".pm-toast-cerrar").addEventListener("click", function () {
      PosmeToast.cerrar(toast);
    });
    toast.addEventListener("mouseenter", function () {
      PosmeToast.pausar(toast);
    });
    toast.addEventListener("mouseleave", function () {
      if (toast.getAttribute("data-cerrando") !== "1") PosmeToast.iniciarTiempo(toast);
    });
    toast.addEventListener("touchstart", function () {
      PosmeToast.pausar(toast);
    }, { passive: true });
    toast.addEventListener("touchend", function () {
      if (toast.getAttribute("data-cerrando") !== "1") PosmeToast.iniciarTiempo(toast);
    });

    // Maximo 4 visibles: se cierran las mas antiguas
    var activos = [];
    for (var j = 0; j < stack.children.length; j++) {
      if (stack.children[j].getAttribute("data-cerrando") !== "1") activos.push(stack.children[j]);
    }
    while (activos.length >= PosmeToast.MAX_VISIBLES) {
      PosmeToast.cerrar(activos.shift());
    }

    stack.appendChild(toast);
    toast._pmDuracion = duracion;
    toast._pmRestante = duracion;
    void toast.offsetWidth;
    toast.classList.add("is-visible");
    PosmeToast.iniciarTiempo(toast);
    return toast;
  },
};

/**
 * Muestra una notificacion (usada por todas las pantallas y por los mensajes del servidor).
 * Firma original conservada: fnShowNotification(mensaje, tipo, tiempoEnMs).
 */
function fnShowNotification(message, type, time) {
  if (document.body) {
    PosmeToast.mostrar(message, type, time);
  } else {
    document.addEventListener("DOMContentLoaded", function () {
      PosmeToast.mostrar(message, type, time);
    });
  }
}
function fnShowConfirm(title_, body_, callback_) {
  var template =
    "<div>" +
    '<div class="modal fade" id="myModalConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
    '	<div class="modal-dialog">' +
    '	  <div class="modal-content">' +
    '		<div class="modal-header">' +
    '		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
    '		  <h4 class="modal-title">${title}</h4>' +
    "		</div>" +
    '		<div class="modal-body">' +
    "			${body}" +
    "		</div>" +
    '		<div class="modal-footer">' +
    '		  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>' +
    '		  <button type="button" id="btnAceptMyModalConfirm" class="btn btn-primary">Aceptar</button>' +
    "		</div>" +
    "	  </div>" +
    "	</div>" +
    "</div>" +
    "</div>";

  $("#myModalConfirm").remove();
  var result = $.tmpl(template, { title: title_, body: body_ }).html();
  $("#main_content").prepend(result);
  $("#myModalConfirm").modal();
  $("#btnAceptMyModalConfirm").click(function () {
    $("#myModalConfirm").modal("hide");
    callback_.call();
  });
}
function fnShowPrompt(title_, label_, callback_) {
  var template =
    "<div>" +
    '<div class="modal fade" id="myModalPrompt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
    '	<div class="modal-dialog">' +
    '	  <div class="modal-content">' +
    '		<div class="modal-header">' +
    '		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
    '		  <h4 class="modal-title">${title}</h4>' +
    "		</div>" +
    '		<div class="modal-body">' +
    '				<form id="myModalPromptForm" class="form-horizontal" role="form">' +
    "						<fieldset>			" +
    '							<div class="form-group">' +
    '									<label class="col-lg-2 control-label" for="normal">${label}</label>' +
    '									<div class="col-lg-10">' +
    '										<input class="form-control"  type="text" name="txtMyModalPromptForm" id="txtMyModalPromptForm" value="">' +
    "									</div>" +
    "							</div>" +
    "						</fieldset>" +
    "				</form>" +
    "		</div>" +
    '		<div class="modal-footer">' +
    '		  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>' +
    '		  <button type="button" id="btnAceptMyModalPrompt" class="btn btn-primary"  >Aceptar</button>' +
    "		</div>" +
    "	  </div>" +
    "	</div>" +
    "</div>" +
    "</div>";

  $("#myModalPrompt").remove();
  var result = $.tmpl(template, { title: title_, label: label_ }).html();
  $("#main_content").prepend(result);
  $("#myModalPrompt").modal();
  $("#btnAceptMyModalPrompt").click(function () {
    var result = $("#txtMyModalPromptForm").val();
    $("#myModalPrompt").modal("hide");
    callback_.call(this, result);
  });
}
function fnWaitOpen() {
  $("body").isLoading(isLoadingConfig);
}
function fnWaitClose() {
  $("body").isLoading("hide");
}
function fnWaitOpenV2() {
  $("#divLoandingCustom").removeClass("hidden");
}
function fnWaitCloseV2()
{
	$("#divLoandingCustom").addClass("hidden");
}
function fnFormatFloat(number) {
  var flo = parseFloat(
    number
      .replace(",", "")
      .replace(",", "")
      .replace(",", "")
      .replace(",", "")
      .replace(",", "")
  );
  return flo;
}
function fnFormatNumber(number, decimal=2) {
  //Validar Decimales
  if (parseFloat(decimal) == NaN) decimal = 0;

  var dec_ = "1";
  var posfix = "";
  var prefix = "";
  for (var i = 0; i < decimal; i++) dec_ = dec_ + "0";
  dec_ = parseInt(dec_);
  number = parseFloat(number);

  //Validar Numero
  if (number == NaN) return 0;

  number = parseInt(Math.round(number * dec_)) / dec_;
  number = number + "";

  if (number.indexOf(".") == -1) {
    posfix = "";
    prefix = number;
  } else {
    posfix = number.substr(number.indexOf(".") + 1, number.length);
    prefix = number.substr(0, number.indexOf("."));
  }
  if (posfix.length != decimal) {
    for (var i = posfix.length; i < decimal; i++) posfix = posfix + "0";
  }

  number = prefix + "." + posfix;
  return number;
}
function fnRecalcularTableSummary(
  table,
  targetInput,
  parameter1,
  parameter2,
  operation
) {
  var cantidad = table.fnGetData().length;
  var total = 0;
  for (var i = 0; i < cantidad; i++) {
    var objdat_ = table.fnGetData(i);
    var valueParameter1 = fnFormatFloat(objdat_[parameter1]);
    var valueParameter2 = 0;
    var result = 0;

    if (parameter2 != null)
      valueParameter2 = fnFormatFloat(objdat_[parameter2]);
    else valueParameter2 = 0;

    if (operation == "+") {
      result = valueParameter1 + valueParameter2;
    }
    if (operation == "*") {
      result = valueParameter1 * valueParameter2;
    }
    if (operation == "-") {
      result = valueParameter1 - valueParameter2;
    }
    if (operation == "/") {
      result = valueParameter1 / valueParameter2;
    }

    total = total + result;
  }
  total = fnFormatNumber(total, 2);
  $(targetInput).val(total);
}

function fnShowExpiredRegisters(baseUrl, userName, time) {
  setInterval(function () {
    $.ajax({
      url: baseUrl,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (!response.error) {
          response["data"].forEach(function (reminder) {
            fnShowNotification(reminder.name, "info", 6000);
          });
        }
      },
      error: function (xhr, status, error) {
        fnShowMessageError(error);
      },
    });
  }, time);
}

function fnGetUsersCurrentLocation(userName, userPassword, url, companyName ) {
  navigator.geolocation.getCurrentPosition(
    function (position) {
      var location = {
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
      };	  
      fnPostUsersCurrentLocation(userName, userPassword, url, location,companyName);
    },
    function (error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          console.error("Permiso denegado por el usuario.");
          break;
        case error.POSITION_UNAVAILABLE:
          console.error("La ubicación no está disponible.");
          break;
        case error.TIMEOUT:
          console.error("Tiempo de espera agotado.");
          break;
        default:
          console.error("Error desconocido.");
      }
    },
    { enableHighAccuracy: true, maximumAge: 0 }
  );
}

function fnPostUsersCurrentLocation(userName, userPassword, url, location, companyName) {
  $.ajax({
    data: {
      txtNickname: userName,
      txtPassword: userPassword,
      txtLatituded: location.latitude,
      txtLongituded: location.longitude,
      txtReference1: "",
	  txtCompanyName: companyName,
    },
    url: url,
    type: "POST",
    dataType: "json",
    success: function (data) {
      return data;
    },
    error: function (xhr, status, error, data) {
      console.log(error.message);
    },
  });
}

function fnExtraerValoresUrl(cadena, coincidencia) {
	// Buscar la primera coincidencia
	const index = cadena.indexOf(coincidencia);

	// Si no se encuentra la coincidencia, devolver null o lo que prefieras
	if (index === -1) {
		return null;
	}

	// Cortar la cadena desde donde empieza la coincidencia en adelante
	const cortada = cadena.substring(index);

	// Dividir por el carácter "/"
	const partes = cortada.split("/");

	// Tomar el primer y segundo valor del split
	const valor1 = partes[0] || null;
	const valor2 = partes[1] || null;

	return { valor1, valor2 };
}

