<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $(document).ready(function () {
        fnWaitOpen();

        // URL base del controlador (siempre absoluta para no depender de la URL actual)
        var urlCalendar = '<?php echo base_url(); ?>/app_calendar_programming';

        // Barra de herramientas en PC (la de siempre) y en telefono (compacta)
        var toolbarDesktop = {
            left: 	'prevYear,nextYear,prev,next today btnAdd btnPrint',
            center: 'title',
            right: 	'dayGridMonth,timeGridWeek,timeGridDay,multiMonthYear'
        };
        var toolbarMobile = {
            left: 	'prev,next today',
            center: 'title',
            right: 	'btnAdd'
        };
        var isMobileLayout = fnCalIsMobile();

        var calendarEl 	= document.getElementById('calendario');
        var calendar 	= new FullCalendar.Calendar(calendarEl, {
            initialView: 		isMobileLayout ? 'listWeek' : 'dayGridMonth', // Lista en telefono, mensual en PC
			dayMaxEventRows: 	true,
			height: 			'auto', // La altura se ajusta al contenido
            locale: 			'es', // Español
            themeSystem: 		'bootstrap',
            customButtons: {
                btnAdd: {
                    text: 'Agregar',
                    icon: 'fa fa-solid fa-plus',
                    click: function () {
                        let now = new Date();
                        let datePart = now.toISOString().split("T")[0];
                        fnCalResetForm(datePart, "");
                        $('#eventModal').modal('show');
                    }
                },
                btnPrint: {
                    text: 'Imprimir',
                    icon: 'fa fa-solid fa-print',
                    click: function (){
                        let view = calendar.view;
                        if (view.type === 'timeGridDay'){
                            let currentStart 	= view.currentStart;
                            let mes 			= currentStart.getMonth()+1;
                            let date 			= currentStart.getFullYear() + "-" + mes + "-"+ currentStart.getDate();
                            $('#eventModal').modal('hide');
                            fnWaitOpen();
							window.open(urlCalendar + '/imprimirEventos?date='+date, "_blank");
							fnWaitClose();
                        } else {
                            // Solo se imprime el detalle de un dia
                            fnShowNotification("Cambie a la vista Día para imprimir", "warning");
                        }
                    }
                }
            },
            headerToolbar: isMobileLayout ? toolbarMobile : toolbarDesktop,
            buttonText: {
                today: 	'Hoy',
                month: 	'Mes',
                week: 	'Semana',
                day: 	'Día',
                list: 	'Lista',
                year: 	'Año'
            },
            events: function(info, successCallback, failureCallback) {
                $.ajax({
                    url: 		urlCalendar + '/events',
                    method: 	'GET',
                    dataType: 	'json',
                    success: 	function(data) {
                        // El servidor responde un objeto {status:'error'} cuando falla
                        if (data && !Array.isArray(data) && data.status === "error") {
                            failureCallback();
                            fnShowNotification(data.message ? data.message : "No se pudieron cargar los eventos", "error");
                            fnWaitClose();
                            return;
                        }
                        successCallback(data ? data : []); // Pasa los eventos a FullCalendar
                        fnWaitClose();
                    },
                    error: function() {
                        failureCallback();
                        console.log("Error al cargar eventos.");
                        fnShowNotification("Error de comunicación con el servidor", "error");
                        fnWaitClose();
                    }
                });
            },
            dateClick: function (info) {
                handleEventClick(info, true);
            },
            eventClick: function (info) {
                handleEventClick(info, false);
            },
            // Cambio de barra y vista al pasar de PC a telefono y viceversa
            windowResize: function () {
                let mobileNow = fnCalIsMobile();
                if (mobileNow === isMobileLayout) return;
                isMobileLayout = mobileNow;
                calendar.setOption('headerToolbar', isMobileLayout ? toolbarMobile : toolbarDesktop);
                calendar.changeView(isMobileLayout ? 'listWeek' : 'dayGridMonth');
            },
            navLinks: true,
            eventBackgroundColor: '#378006',
			eventDidMount: function(info) {


				if (info.event.extendedProps.description) {
					//info.el.setAttribute("title", info.event.extendedProps.description);
					//Seguridad: la descripcion se escapa antes de convertir los saltos de linea (el tooltip usa html:true)
					$(info.el).tooltip({
						title		: fnCalEscapeHtml(info.event.extendedProps.description).replace(/\n/g, "<br>"),
						placement	: "top",   // puedes cambiar: top, bottom, left, right
						trigger		: "hover",
						container	: "body",
						html		: true
					});

				}



			}
        });

        calendar.render();

        // Al cerrar la confirmacion del sistema, si el modal del evento sigue abierto
        // se restaura la clase modal-open (Bootstrap 3 la quita al cerrar cualquier modal)
        $(document).on('shown.bs.modal', '#myModalConfirm', function () {
            $('.modal-backdrop').last().css('z-index', 1065);
        });
        $(document).on('hidden.bs.modal', '#myModalConfirm', function () {
            if ($('#eventModal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
        });

        //$('.fc-icon').removeClass('fc-icon fc-icon-fa');
        // Guardar (nuevo o edicion) evento
        $('#save').click(function (e) {
            e.preventDefault();
            // Evitar doble envio mientras el AJAX esta en curso
            if ($('#save').prop('disabled')) return;

            let eventTitle 	= $('#eventTitle').val().trim();
            let eventDate 	= $('#eventDate').val().trim();
            let eventTime 	= $('#eventTime').val().trim();
            let eventDescripcion = $('#eventDescripcion').val().trim();
            let tagID 		= $('#txtTagID').val();
            let id 			= $('#eventId').val();

            if (validateForm(eventTitle,eventDescripcion, eventDate, eventTime, tagID)){
                return;
            }

            let fullDateTime = eventTime ? `${eventDate}T${eventTime}:00` : eventDate; // Formato correcto

            let eventData = {
                id: id ? id : 	null, // Si hay ID, es una actualización
                title: 			eventTitle,
                start: 			fullDateTime,
                tagID:			tagID,
                descripcion: 	eventDescripcion
            };

            let url = urlCalendar + (id ? '/save/edit' : '/save/new'); // Si hay ID, actualizar
            $('#save').prop('disabled', true);
            fnWaitOpen();
            $.ajax({
                url: 			url,
                type: 			'POST',
                contentType: 	'application/json',
                dataType: 		'json', // Especificar que se espera JSON como respuesta
                processData: 	false, // No procesar datos automáticamente
                data: 			JSON.stringify(eventData),
                success: function (response) {
                    fnWaitClose();
                    if (response && response.status === "success") {
                        // Solo se cierra el modal cuando el servidor confirma
                        $('#eventModal').modal('hide');
                        calendar.refetchEvents();
                        fnShowNotification(response.message ? response.message : "Programación guardada", "success");
                    } else {
                        // El modal queda abierto con los datos para corregir
                        fnShowNotification(response && response.message ? response.message : "No se pudo guardar la programación", "error");
                    }
                },
                error: function () {
                    fnWaitClose();
                    fnShowNotification("Error de comunicación con el servidor", "error");
                },
                complete: function () {
                    $('#save').prop('disabled', false);
                }
            });

        });

        // Eliminar evento (con confirmacion previa)
        $('#deleteEvent').click(function () {
            let id = $('#eventId').val();
            if (!id) return;

            fnCalConfirm(
                "Eliminar programación",
                "¿Desea eliminar la programación seleccionada? Esta acción no se puede deshacer.",
                function () { fnCalDeleteEvent(id); }
            );
        });

        // Envia la eliminacion al servidor; el modal se cierra solo si hubo exito
        function fnCalDeleteEvent(id) {
            if ($('#deleteEvent').prop('disabled')) return;
            let eventData = {
                idevent: id
            };
            $('#deleteEvent').prop('disabled', true);
            fnWaitOpen();
            $.ajax({
                url: 		 	urlCalendar + '/delete',
                type: 		 	'POST',
                contentType: 	'application/json',
                dataType: 		'json',
                processData: 	false,
                data: 			JSON.stringify(eventData),
                success: function (response) {
                    fnWaitClose();
                    if (response && response.status === "success") {
                        $('#eventModal').modal('hide');
                        calendar.refetchEvents();
                        fnShowNotification(response.message ? response.message : "Programación eliminada", "success");
                    } else {
                        fnShowNotification(response && response.message ? response.message : "No se pudo eliminar la programación", "error");
                    }
                },
                error: function () {
                    fnWaitClose();
                    fnShowNotification("Error de comunicación con el servidor", "error");
                },
                complete: function () {
                    $('#deleteEvent').prop('disabled', false);
                }
            });
        }

        $('#printEvent').click(function (){
            let id 		= $('#eventId').val();
            if (!id) return;
            let idevent	= id;
            $('#eventModal').modal('hide');
            fnWaitOpen();
			window.open(urlCalendar + '/imprimirEvento?idevent='+idevent, "_blank");
			fnWaitClose();

        });

        $('#printOpcionEvent').click(function (){
            let id 		= $('#eventPrintId').val();
            if (!id) return;
            let idevent	= id;
            $('#eventOpcionModal').modal('hide');
            fnWaitOpen();
			window.open(urlCalendar + '/imprimirEvento?idevent='+idevent, "_blank");
			fnWaitClose();

        });



        $('#redirectEvent').click(function(){
            let url = $('#eventUrl').val();
            $('#eventOpcionModal').modal('hide');

			if (url.includes("app_invoice_billing"))
			{
				$.post(
					"<?= base_url(); ?>/app_invoice_billing/setSessionData",
					{
						companyID           : fnExtraerValoresUrl(url,"companyID").valor2,
						transactionID       : fnExtraerValoresUrl(url,"transactionID").valor2,
						transactionMasterID : fnExtraerValoresUrl(url,"transactionMasterID/").valor2,
						codigoMesero        : "none",
						edicion             : true
					},
					function ()
					{
						window.open("<?= base_url(); ?>/app_invoice_billing/add/codigoMesero/none", '_blank');
					}
				);
			}
			else
			{
				window.open(url, '_blank');
			}

        });


        function handleEventClick(info, isDateClick = false) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            let event = isDateClick ? null : info.event;
            if (event){
                if (info.event.url) {
                    $('#eventPrintId').val(event.id);
                    $('#eventUrl').val(event.url);
                    $('#eventOpcionTitle').text(event.title || "");
                    $('#eventOpcionInfo').html(fnCalBuildInfoHtml(event.extendedProps || {}));
                    $('#eventOpcionModal').modal('show');
                    return;
                }
                fnWaitOpen();
                $.ajax({
                    dataType: 	'json',
                    url: 		urlCalendar + '/find/' + encodeURIComponent(event.id),
                    success: function (response) {
                        fnWaitClose();
                        // Error del servidor (permisos, sesion, etc.)
                        if (response && response.status === "error") {
                            fnShowNotification(response.message ? response.message : "No se pudo cargar la programación", "error");
                            return;
                        }
                        // Sin datos: la programacion ya no existe
                        if (!response || !response.createdOn) {
                            fnShowNotification("No se encontró la programación seleccionada", "warning");
                            return;
                        }
                        // Con datos: cargar el formulario en modo edicion
                        fnCalClearErrors();
                        fnCalSetModalMode(true, event.id);
                        $('#eventId').val(event.id);
                        $('#eventDescripcion').val(response.description);
                        $('#eventTitle').val(response.title);
                        let dateTime = response.createdOn.replace(" ", "T");
                        let datePart = dateTime.split("T")[0]; // Extraer fecha
                        let timePart = dateTime.includes("T") ? dateTime.split("T")[1].substring(0, 5) : "";
                        $('#eventDate').val(datePart);
                        $('#eventTime').val(timePart);
                        $('#txtTagID').val(response.tagID ? String(response.tagID) : "0").trigger('change');
                        if ($('#txtTagID').val() === null) $('#txtTagID').val("0");
                        $('#eventModal').modal('show'); // Mostrar modal
                    },
                    error: function () {
                        fnWaitClose();
                        fnShowNotification("Error de comunicación con el servidor", "error");
                    }
                });
            }else{
                let dateTime = isDateClick ? info.dateStr : event.startStr;
                let datePart = dateTime.split("T")[0]; // Extraer fecha
                let timePart = dateTime.includes("T") ? dateTime.split("T")[1].substring(0, 5) : "";
                fnCalResetForm(datePart, timePart);
                $('#eventModal').modal('show'); // Mostrar modal
            }
        }

        function validateForm(eventTitle, eventDescripcion, eventDate, eventTime, tagID) {
            // Limpiar mensajes previos
            $(".error-message").remove();
            $(".is-invalid").removeClass("is-invalid");

            let isValid = false;

            // Función para mostrar el mensaje de error
            function showError(input, message) {
                $(input).addClass("is-invalid"); // Agregar borde rojo
                $(input).after(`<div class="error-message text-danger">${message}</div>`);
                isValid = true;
            }

            if (!eventTitle) {
                showError("#eventTitle", "El título del evento es obligatorio.");
            }

            if (!eventDescripcion) {
                showError("#eventDescripcion", "La descripción del evento es obligatoria.");
            }

            if (!eventDate) {
                showError("#eventDate", "La fecha del evento es obligatoria.");
            }

            if (!tagID || tagID === "0") { // Suponiendo que "0" es la opción por defecto
                showError("#txtTagID", "Debe seleccionar una etiqueta.");
            }

            return isValid;
        }

        /**
         * Limpia los mensajes y bordes de error del formulario del evento.
         */
        function fnCalClearErrors() {
            $('#eventForm .error-message').remove();
            $('#eventForm .is-invalid').removeClass('is-invalid');
        }

        /**
         * Ajusta cabecera y botones del modal segun sea nuevo o edicion.
         * @param {boolean} isEdit  true = editar programacion existente.
         * @param {string}  id      Codigo del evento (solo en edicion).
         */
        function fnCalSetModalMode(isEdit, id) {
            $('#eventModalTitle').text(isEdit ? 'Editar programación' : 'Nueva programación');
            $('#eventModalChip').text(isEdit ? ('Código ' + id) : 'Nuevo');
            $('#eventModalIcon').attr('class', isEdit ? 'fa-solid fa-pen-to-square' : 'fa-solid fa-calendar-plus');
            $('#deleteEvent').toggle(isEdit);
            $('#printEvent').toggle(isEdit);
        }

        /**
         * Deja el formulario listo para una programacion nueva.
         * @param {string} datePart Fecha inicial YYYY-MM-DD.
         * @param {string} timePart Hora inicial HH:MM (vacia si no aplica).
         */
        function fnCalResetForm(datePart, timePart) {
            fnCalClearErrors();
            fnCalSetModalMode(false, "");
            $('#eventId').val("");
            $('#eventTitle').val("");
            $('#eventDescripcion').val("");
            $('#txtTagID').val("0").trigger('change');
            $('#eventDate').val(datePart);
            $('#eventTime').val(timePart);
        }

        /**
         * Construye la lista etiqueta/valor del evento con documento.
         * Todos los valores se escapan antes de insertarlos en el HTML.
         * @param {object} props extendedProps del evento.
         * @return {string} HTML seguro.
         */
        function fnCalBuildInfoHtml(props) {
            let infoHtml = "";
            function row(label, value) {
                return '<div class="pv2-cal-info-row"><span class="pv2-cal-info-label">' + label + '</span>' +
                       '<span class="pv2-cal-info-value">' + fnCalEscapeHtml(value) + '</span></div>';
            }
            if (props.nombre || props.telefono || props.entregado || props.entidad) {
                if (props.entidad)   { infoHtml += row("Código", props.entidad); }
                if (props.nombre)    { infoHtml += row("Cliente", props.nombre); }
                if (props.telefono)  { infoHtml += row("Teléfono", props.telefono); }
                if (props.entregado) { infoHtml += row("Activo fijo", props.entregado); }
            } else if (props.description) {
                // Primero se escapa y despues se convierten los saltos de linea
                infoHtml = '<div class="pv2-cal-info-row is-block"><span class="pv2-cal-info-label">Descripción</span>' +
                           '<span class="pv2-cal-info-value">' + fnCalEscapeHtml(props.description).replace(/\n/g, "<br>") + '</span></div>';
            } else {
                infoHtml = '<p class="pv2-cal-hint" style="margin:0;">Sin información adicional.</p>';
            }
            return infoHtml;
        }

        /**
         * Escapa un texto para insertarlo de forma segura en HTML.
         * @param {*} value Valor a escapar.
         * @return {string}
         */
        function fnCalEscapeHtml(value) {
            if (value === null || value === undefined) return "";
            return String(value)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#39;");
        }

        /**
         * Confirmacion con el patron del sistema (fnShowConfirm); si no esta disponible usa confirm().
         */
        function fnCalConfirm(title, message, callback) {
            if (typeof fnShowConfirm === "function" && $.tmpl && $("#main_content").length > 0) {
                fnShowConfirm(title, message, callback);
                return;
            }
            if (confirm(message)) callback();
        }

        /**
         * Indica si se debe usar el diseño de telefono (pv2IsMobile de posme_v2_common).
         */
        function fnCalIsMobile() {
            if (typeof pv2IsMobile === "function") return pv2IsMobile();
            return window.innerWidth < 768;
        }

    });


</script>
