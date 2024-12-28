<script>
    let timerNotification = 15000;
    var count = 1;
    var $txtCalificacionCuantitativa = $('#txtCalificacionCuantitativa');
    var companyID = <?= $companyID ?? "0" ?>;
    var userID = <?= $userID ?? "0" ?>;
    var urlPrinter = "app_cxc_notes/viewPrinterFormatoA4";
    $(document).ready(function () {

        $('#txtPriorityID').select2({
            allowClear: true,
            placeholder: "Selecciona un valor"
        });
        $('#txtGradoID').select2({
            allowClear: true,
            placeholder: "Selecciona un valor"
        });
        $('#txtAnio').select2({
            allowClear: true,
            placeholder: "Selecciona un valor"
        });
        $('#txtMes').select2({
            allowClear: true,
            placeholder: "Selecciona un valor"
        });

        $txtCalificacionCuantitativa.keypress(function (event) {
            // Permitir solo números (0-9) y un punto decimal
            var key     = event.which;
            var value   = $(this).val();
            // Permitir teclas de control (backspace, enter, etc.)
            if (key === 8 || key === 0) {
                return; // Permitir backspace y otras teclas de control
            }
            // Permitir números y un solo punto decimal
            if ((key < 48 || key > 57) && key !== 46) {
                event.preventDefault();
            }
            // Permitir solo un punto decimal
            if (key === 46 && value.indexOf('.') !== -1) {
                event.preventDefault();
            }

        });

        $('#txtGradoID').on('change', function () {
            let selectedGrado = $('#txtGradoID option:selected').text();
            $('#txtShowGrado').text(selectedGrado);
        });

        // Validar el año ingresado
        $('#txtAnio').on('change', function () {
            let selectedYear = parseInt($('#txtAnio').val());
            if (selectedYear > 0) {
                $('#error').hide();
                $('#txtMes').prop('disabled', false);
            } else {
                $('#error').show();
                $('#txtMes').prop('disabled', true);
            }
            $('#txtShowAnio').text($('#txtAnio option:selected').text());
        });

        // Validar el mes ingresado
        $('#txtMes').on('change', function () {
            let selectedMes = parseInt($('#txtMes').val());
            if (selectedMes > 0) {
                $('#error').hide();
                $('#txtDia').prop('disabled', false).trigger('change');
            } else {
                $('#error').show();
                $('#txtDia option:selected').text('');
                $('#txtDia').prop('disabled', true).trigger('change');
                $('#txtDia').empty();
            }
        });

        // Cambiar los días según el mes y el año seleccionados
        $('#txtMes, #txtAnio').on('change', function () {
            var selectedMonth   = parseInt($('#txtMes').val());
            var selectedYear    = parseInt($('#txtAnio option:selected').text().split('-')[0]);
            if (!isNaN(selectedMonth) && !isNaN(selectedYear)) {
                var daysInMonth = fnDaysInMonth(selectedMonth, selectedYear);
                $('#txtDia').empty(); // Limpiar el select de días
                $('#errorDay').hide(); // Ocultar mensaje de error

                // Llenar el select de días
                $('#txtTransactionOn').val(selectedYear + "-" + selectedMonth + "-" + 1);
                for (var day = 1; day <= daysInMonth; day++) {
                    $('#txtDia').append($('<option></option>').val(day).html(day));
                }

                $('#txtDia').prop('disabled', false); // Habilitar el select de días
                $('#txtDia').select2({
                    allowClear: true,
                    placeholder: "Selecciona un valor"
                });

            } else {
                $('#txtDia').prop('disabled', true); // Deshabilitar el select de días si no hay mes o año seleccionado
            }
        });

        $('#txtDia').on('change', function () {
            let selectedYear    = parseInt($('#txtAnio option:selected').text());
            let selectedMes     = parseInt($('#txtMes').val());
            let selectedDia     = parseInt($('#txtDia').val());
            if (selectedYear > 0) {
                $('#txtTransactionOn').val(selectedYear + "-" + selectedMes + "-" + selectedDia);
            }
        });
        $("#btnClearAlumno").click(function () {
            $("#txtAlumnoID").val("");
            $("#txtAlumnoDescription").val("");
        });

        $("#btnClearColaborador").click(function () {
            $("#txtColaboradorID").val("");
            $("#txtColaboradorDescription").val("");
        });

        $("#btnClearMateria").click(function () {
            $("#txtMateriaID").val("");
            $("#txtMateriaDescription").val("");
        });

        $("#btnSearchMateria").click(function () {
            var url_request = "<?= base_url(); ?>/core_view/showviewbyname/<?= $objComponentMateria->componentID; ?>/onCompleteMateria/SELECCIONAR_MATERIAS/true/empty/false/not_redirect_when_empty";
            window.open(url_request, "MsgWindow", "width=900,height=450");
            window.onCompleteMateria = onCompleteMateria;
        });

        $("#btnSearchColaborador").click(function () {
            var url_request = "<?= base_url(); ?>/core_view/showviewbyname/<?= $objComponentEmployer->componentID; ?>/onCompleteCustomer/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
            window.open(url_request, "MsgWindow", "width=900,height=450");
            window.onCompleteCustomer = onCompleteColaborador;
        });

        $("#btnSearchAlumno").click(function () {
            var url_request = "<?= base_url(); ?>/core_view/showviewbyname/<?= $objComponentCustomer->componentID; ?>/onCompleteAlumno/SELECCIONAR_CLIENTES_ALL/true/empty/false/not_redirect_when_empty";
            window.open(url_request, "MsgWindow", "width=900,height=450");
            window.onCompleteAlumno = onCompleteAlumno;
        });

        $('#btnMostrar').click(function () {
            fnWaitOpen();
            fnMostrar();
        });

        $('#txtPriorityID').change(function () {
            $('#txtCalificacionCuantitativa').val('');
        })

        // Add new row
        $('#btnGuardar').click(function () {
            count++;
            if (fnValidateForm(true)) {
                fnWaitOpen();
                var urlSave = "<?= base_url(); ?>/app_cxc_notes/save";
                $.ajax({
                    type:   "POST",
                    url:    urlSave,
                    data:   $('#form-note').serialize(),
                    success: function (response) {
                        if (response.error){
                            fnShowNotification("Se produjo un error al enviar los datos "+ response.message, "error", timerNotification)
                        }else{
                            fnShowNotification("Se han guardado los datos de forma correcta", "success", timerNotification);
                            fnMostrar();
                        }
                    },
                    error: function () {
                        fnWaitClose();
                        fnShowNotification("Se produjo un error al enviar los datos", "error", timerNotification);
                    }
                });
            }
        });

        // Validar al perder el foco
        $txtCalificacionCuantitativa.on('blur', function() {
            let selectedOption  = $('#txtPriorityID option:selected');
            let minValue        = parseFloat(selectedOption.data('minimo'));
            let maxValue        = parseFloat(selectedOption.data('maximo'));
            let calificacion    = parseFloat($(this).val());

            if (isNaN(calificacion)) {
                fnShowNotification("Por favor, ingrese un número válido", "error", timerNotification);
            } else if (calificacion < minValue) {
                fnShowNotification("La calificación no puede ser menor que " + minValue + ".", "error", timerNotification);
                $('#txtCalificacionCuantitativa').val('');
            } else if (calificacion > maxValue) {
                fnShowNotification("La calificación no puede ser mayor que " + maxValue + ".", "error", timerNotification);
                $('#txtCalificacionCuantitativa').val('');
            }
        });

        $("#btnDelete").click(function(){
            if (fnValidateForm(false)) {
                fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function () {
                    let txtAnio         = $('#txtAnio option:selected').text();
                    let txtMes          = parseInt($('#txtMes').val());
                    let transactionOn   = $('#txtTransactionOn').val();
                    let colaboradorId   = $('#txtColaboradorID').val();
                    let alumnoId        = $('#txtAlumnoID').val();
                    let materiaId       = $('#txtMateriaID').val();
                    let gradoId         = $('#txtGradoID').val();
                    fnWaitOpen();
                    $.ajax({
                        cache: false,
                        dataType: 'json',
                        type: 'POST',
                        url: "<?php echo base_url(); ?>/app_cxc_notes/delete",
                        data: {
                            'anio':             txtAnio,
                            'mes':              txtMes,
                            'transactionOn':    transactionOn,
                            'colaboradorId':    colaboradorId,
                            'alumnoId':         alumnoId,
                            'materiaId':        materiaId,
                            'gradoId':          gradoId
                        },
                        success: function (data) {
                            console.info("complete delete success");
                            if (data.error) {
                                fnWaitClose();
                                fnShowNotification(data.message, "error");
                            } else {
                                fnMostrar();
                                fnShowNotification("Se ha eliminado el registro de forma correcta", "success");
                            }
                        },
                        error: function (xhr, data) {
                            console.info("complete delete error");
                            fnWaitClose();
                            fnShowNotification("No fue posible realizar el eliminado del registro", "error");
                        }
                    });
                });
            }
        });

        $('#txtPriorityID').on('change', function() {
            var selectedColor = $(this).find('option:selected').data('color');
            $('#s2id_txtPriorityID .select2-choice').css('color', selectedColor);
        });

        $('#btnImprimir').click(function () {
            let txtAnio             = $('#txtAnio option:selected').text();
            let alumnoId            = $('#txtAlumnoID').val();
            let gradoId             = $('#txtGradoID').val();
            if (gradoId.trim() === ""){
                fnShowNotification("Debe seleccionar un grado", "error", timerNotification);
                return;
            }
            if (txtAnio.trim()===""){
                fnShowNotification("Debe seleccionar un año", "error", timerNotification);
                return;
            }
            if (alumnoId.trim() === ""){
                fnShowNotification("Debe seleccionar un alumno", "error", timerNotification);
                return;
            }
            fnWaitOpen();
            window.open("<?= base_url(); ?>/"+urlPrinter+"/companyID/"+companyID+"/userID/"+userID+"/gradoID/"+gradoId+"/anio/"+txtAnio+"/alumnoID/"+alumnoId, '_blank');
            fnWaitClose();
        });
    });

    // Función para obtener el número de días en un mes
    function fnDaysInMonth(month, year) {
        month -= 1;
        if (month === 1) { // Febrero
            return (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0)) ? 29 : 28;
        }
        return [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
    }

    function onCompleteMateria(objResponse) {
        console.info("CALL onCompleteCustomer");
        let entityID        = objResponse[0][0];
        let codigo          = objResponse[0][1];
        $("#txtMateriaID").val(entityID);
        $("#txtMateriaDescription").val(codigo + " " + objResponse[0][2]);
    }

    function onCompleteAlumno(objResponse) {
        console.info("CALL onCompleteCustomer");
        let entityID        = objResponse[0][1];
        let codigo          = objResponse[0][2];
        $("#txtAlumnoID").val(entityID);
        $("#txtAlumnoDescription").val(codigo + " " + objResponse[0][3] + " / " + objResponse[0][4]);
        $('#txtShowNombreAlumno').text(codigo + " / " +objResponse[0][3])
    }

    function onCompleteColaborador(objResponse) {
        console.info("CALL onCompleteCustomer");
        let entityID    = objResponse[0][2];
        let codigo      = objResponse[0][3];
        $("#txtColaboradorID").val(entityID);
        $("#txtColaboradorDescription").val(codigo + " " + objResponse[0][4] + " / " + objResponse[0][5]);
    }

    function fnMostrar(){
        let txtAnio             = $('#txtAnio option:selected').text();
        let txtMes              = $('#txtMes').val();
        let txtTransactionOn    = $('#txtTransactionOn').val();
        let colaboradorId       = $('#txtColaboradorID').val();
        let alumnoId            = $('#txtAlumnoID').val();
        let materiaId           = $('#txtMateriaID').val();
        let gradoId             = $('#txtGradoID').val();
        if (gradoId.trim() === ""){
            fnWaitClose();
            fnShowNotification("Debe seleccionar un grado", "error", timerNotification);
            return;
        }
        if (txtAnio.trim()===""){
            fnWaitClose();
            fnShowNotification("Debe seleccionar un año", "error", timerNotification);
            return;
        }
        if (txtMes.trim() === ""){
            fnWaitClose();
            fnShowNotification("Debe seleccionar un mes", "error", timerNotification);
            return;
        }
        if (txtTransactionOn.trim()===""){
            fnWaitClose();
            fnShowNotification("Debe seleccionar un dia", "error", timerNotification);
            return;
        }
        if (alumnoId.trim() === ""){
            fnWaitClose();
            fnShowNotification("Debe seleccionar un alumno", "error", timerNotification);
            return;
        }
        var urlSearch = "<?= base_url(); ?>/app_cxc_notes/searchTransactionMaster";
        $.ajax({
            type: "POST",
            url: urlSearch,
            data: {
                'anio':             txtAnio,
                'mes':              txtMes,
                'transactionOn':    txtTransactionOn,
                'colaboradorId':    colaboradorId,
                'alumnoId':         alumnoId,
                'materiaId':        materiaId,
                'gradoId':          gradoId
            },
            success: function (response) {
                if (response.error){
                    fnShowNotification("Se produjo un error al enviar los datos" + response.message, "error", timerNotification);
                }else{
                    if (response.datos.length <= 0){
                        $('#data_table tbody').empty();
                        fnShowNotification("No ha datos disponibles con los valores indicados", "success", timerNotification);
                    }else{
                        fnProcesarDatos(response.datos);
                        fnShowNotification("Datos encontrados de forma correcta", "success", timerNotification);
                    }
                }
                fnWaitClose();
            },
            error: function () {
                fnShowNotification("Se produjo un error al enviar los datos y guardar", "error", timerNotification);
                fnWaitClose();
            }
        });
    }

    function fnProcesarDatos(datos) {
        var primerValor = datos[0];
        if (typeof primerValor !== 'undefined') {
            $('#txtShowNombreAlumno').text(primerValor.codigoAlumno + " / " + primerValor.alumno);
            $('#txtShowGrado').text(primerValor.grado);
            var fecha = new Date(primerValor.transactionOn);
            if (!isNaN(fecha.getTime())) {
                $('#txtShowAnio').text(fecha.getFullYear());
            }
        }

        $('#data_table tbody').empty();
        // Recorre el array de datos
        $.each(datos, function(index, item) {
            var transactionOn   = new Date(item.transactionOn);
            let mes             = transactionOn.toLocaleString('default', { month: 'long' }).toUpperCase();
            let html_code       = "<tr id='row" + item.transactionMasterID + "'>";
            html_code           += "<td>" + item.colaborador + "</td>";
            html_code           += "<td>" + item.materia + "</td>";
            html_code           += "<td>" + mes + "</td>";
            html_code           += "<td ><p class='"+ item.color + "' >" + item.calificacionCualitativa + "</p></td>";
            html_code           += "<td ><p class='"+ item.color + "' >" + fnFormatNumber(item.calificacionCuantitativa, 2) + "</p></td>";
            html_code           += "</tr>";
            $('#data_table').append(html_code);
        });
    }

    function fnValidateForm(validarCalificacion) {
        var result  = true;

        let gradoId = $('#txtGradoID').val();
        if (gradoId.trim() === "") {
            fnShowNotification("Debe seleccionar un Grado", "error", timerNotification)
            result = false;
        }

        let anioId = $('#txtAnio option:selected').text();
        if (anioId.trim() === "") {
            fnShowNotification("Debe seleccionar un año", "error", timerNotification)
            result = false;
        }

        let mes = $('#txtMes option:selected').text();
        if (mes.trim() === "") {
            fnShowNotification("Debe seleccionar un mes", "error", timerNotification)
            result = false;
        }

        let colaborador = $('#txtColaboradorDescription').val();
        if (colaborador.trim() === "") {
            fnShowNotification("Debe seleccionar un Profesor", "error", timerNotification)
            result = false;
        }

        let materia = $('#txtMateriaDescription').val();
        if (materia.trim() === "") {
            fnShowNotification("Debe seleccionar una Materia", "error", timerNotification)
            result = false;
        }

        let dia             = $('#txtDia').val();
        let transactionOn   = $('#txtTransactionOn').val();
        if (transactionOn.trim() === "" || dia.trim() === ""){
            fnShowNotification("Debe seleccionar el dia para aplicar", "error", timerNotification)
            result = false;
        }

        if (validarCalificacion){
            let valCualitativa = $('#txtPriorityID option:selected').text();
            if (valCualitativa.trim() === "") {
                fnShowNotification("Debe seleccionar un valor cualitativo", "error", timerNotification)
                result = false;
            }
            let valCuantitativa = $txtCalificacionCuantitativa.val();
            if (valCuantitativa.trim() === "") {
                fnShowNotification("Debe especificar un valor cuantitativo", "error", timerNotification)
                result = false;
            }
        }

        return result;
    }
</script>