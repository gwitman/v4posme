<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};

    $(document).ready(function () {
        fnWaitOpen();
        var calendarEl 	= document.getElementById('calendario');
        var calendar 	= new FullCalendar.Calendar(calendarEl, {
            initialView: 		'dayGridMonth', // Vista mensual
			dayMaxEventRows: 	true,
			height: 			600, // Establece la altura en píxeles
            locale: 			'es', // Español
            themeSystem: 		'bootstrap',
            customButtons: {
                btnAdd: {
                    text: 'Agregar',
                    icon: 'fa fa-solid fa-plus',
                    click: function () {
                        $('#deleteEvent').hide();
                        let now = new Date();
                        let datePart = now.toISOString().split("T")[0];
                        $('#eventId').val("");
                        $('#eventTitle').val("");
                        $('#eventDescripcion').val("");
                        $('#eventDate').val(datePart);
                        $('#eventTime').val("");
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
							window.open('<?= base_url()?>/app_calendar_programming/imprimirEventos?date='+date, "_blank");
							fnWaitClose();
                        }
                    }
                }
            },
            headerToolbar: {
                left: 	'prevYear,nextYear,prev,next today btnAdd btnPrint',
                center: 'title',
                right: 	'dayGridMonth,timeGridWeek,timeGridDay,multiMonthYear'
            },
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
                    url: 		'<?= base_url()?>/app_calendar_programming/events',
                    method: 	'GET',
                    dataType: 	'json',
                    success: 	function(data) {
                        successCallback(data); // Pasa los eventos a FullCalendar
                        fnWaitClose();
                    },
                    error: function() {
                        failureCallback();
                        console.log("Error al cargar eventos.");
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
            navLinks: true,
            eventBackgroundColor: '#378006'
        });

        calendar.render();

        //$('.fc-icon').removeClass('fc-icon fc-icon-fa');
        // Evento submit del formulario
        $('#save').click(function (e) {
            e.preventDefault();
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

            let url = id ? 'save/edit' : 'save/new'; // Si hay ID, actualizar
            fnWaitOpen();
            $('#eventModal').modal('hide'); // Cerrar modal
            $.ajax({
                url: 			url,
                type: 			'POST',
                contentType: 	'application/json',
                dataType: 		'json', // Especificar que se espera JSON como respuesta
                processData: 	false, // No procesar datos automáticamente
                data: 			JSON.stringify(eventData),
                success: function (response) {
                    if (response.status === "success") {
                        calendar.refetchEvents();
                        fnWaitClose();
                    }else{
                        fnWaitClose();
                    }
                }
            });

        });

        // Eliminar evento
        $('#deleteEvent').click(function () {
            let id = $('#eventId').val();
            if (!id) return;
            let eventData = {
                idevent: id
            };
            fnWaitOpen();
            $('#eventModal').modal('hide');
            $.ajax({
                url: 		 	'delete',
                type: 		 	'POST',
                contentType: 	'application/json',
                dataType: 		'json',
                processData: 	false,
                data: 			JSON.stringify(eventData),
                success: function (response) {
                    if (response.status === "success") {
                        calendar.refetchEvents();
                    }
                    fnWaitClose();
                }
            });
        });

        $('#printEvent').click(function (){
            let id 		= $('#eventId').val();
            if (!id) return;
            let idevent	= id;
            $('#eventModal').modal('hide');
            fnWaitOpen();
			window.open('<?= base_url()?>/app_calendar_programming/imprimirEvento?idevent='+idevent, "_blank");
			fnWaitClose();
			
        });

        $('#printOpcionEvent').click(function (){
            let id 		= $('#eventPrintId').val();
            if (!id) return;
            let idevent	= id;
            $('#eventOpcionModal').modal('hide');
            fnWaitOpen();
			window.open('<?= base_url()?>/app_calendar_programming/imprimirEvento?idevent='+idevent, "_blank");
			fnWaitClose();
			
        });

		

        $('#redirectEvent').click(function(){
            let url = $('#eventUrl').val();
            $('#eventOpcionModal').modal('hide');
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
			
        });
		
		
        function handleEventClick(info, isDateClick = false) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            let event = isDateClick ? null : info.event;
            $('#deleteEvent').toggle(!isDateClick);
            $('#printEvent').toggle(!isDateClick);
            if (event){
                if (info.event.url) {
                    $('#eventPrintId').val(event.id);
                    $('#eventUrl').val(event.url);
                    $('#eventOpcionModal').modal('show');
                    return;
                }
                fnWaitOpen();
                $('#eventId').val(event.id);
                $.ajax({
                    dataType: 	'json',
                    url: 		'find/'+event.id,
                    success: function (response) {
                        $('#eventDescripcion').val(response.description);
                        $('#eventTitle').val(response.title);
                        let dateTime = response.createdOn.replace(" ", "T");
                        let datePart = dateTime.split("T")[0]; // Extraer fecha
                        let timePart = dateTime.includes("T") ? dateTime.split("T")[1].substring(0, 5) : "";
                        $('#eventDate').val(datePart);
                        $('#eventTime').val(timePart);
                        $('#eventModal').modal('show'); // Mostrar modal
                        $('#txtTagID').val(response.tagID).trigger('change');
                        fnWaitClose();
                    }
                });
            }else{
                let dateTime = isDateClick ? info.dateStr : event.startStr;
                let datePart = dateTime.split("T")[0]; // Extraer fecha
                let timePart = dateTime.includes("T") ? dateTime.split("T")[1].substring(0, 5) : "";
                $('#eventId').val("");
                $('#eventDescripcion').val("");
                $('#eventTitle').val("");
                $('#eventDate').val(datePart);
                $('#eventTime').val(timePart);
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
                showError("#eventDescripcion", "La descripcion del evento es obligatorio.");
            }

            if (!eventDate) {
                showError("#eventDate", "La fecha del evento es obligatoria.");
            }

            if (!tagID || tagID === "0") { // Suponiendo que "0" es la opción por defecto
                showError("#txtTagID", "Debe seleccionar una etiqueta.");
            }

            return isValid;
        }
		
		
		
		

    });
	
	
</script>