<script>
    /* ==========================================================
       Tareas - interfaz compartida entre nuevo y editar.
       Requiere: posme_v2_common y afx_v2_common. No contiene logica de negocio.
       ========================================================== */

    //Valida los campos obligatorios de la tarea
    function validateForm() {
        var result              = true;
        var timerNotification   = 15000;
        var txtEmisorID         = $("#txtEmisorID").val().trim();
        var txtAsignadoID       = $("#txtAsignadoID").val().trim();

        //validar titulo
        if ($("#txtReference4").val().trim() === ""){
            fnShowNotification("Establecer un titulo para la tarea", "error", timerNotification);
            result = false;
        }
        //Validar Fecha
        if ($("#txtDate").val().trim() === "") {
            fnShowNotification("Establecer Fecha al Documento", "error", timerNotification);
            result = false;
        }
        //validar emisor
        if (txtEmisorID === "" || txtEmisorID === "0") {
            fnShowNotification("Seleccione el emisor de la tarea", "error", timerNotification);
            result = false;
        }
        //validar asignado
        if (txtAsignadoID === "" || txtAsignadoID === "0") {
            fnShowNotification("Seleccione a quién asignará la tarea", "error", timerNotification);
            result = false;
        }

        return result;
    }

    function taskEmployeeDescription(objResponse, codigo){
        return codigo + " " + objResponse[0][4] + " / " + objResponse[0][5];
    }

    /* Agrega una fila de comentario. El texto y las opciones se asignan por DOM:
       un apostrofo no corta el valor.
       config: textFieldName, detailIdFieldName (solo editar), commentOptions */
    function taskAgregarFila(config) {
        var comentarioTarea = $("#txtComentarioTarea");
        var selectComments  = $("#txtSelectComments");
        var texto           = comentarioTarea.val();

        //Si el campo de texto esta vacio, mostrar el mensaje de error
        if ($.trim(texto) === "") {
            $("#errorLabel").show();
            return;
        }
        $("#errorLabel").hide();

        var comboValue  = selectComments.val();
        var $textCell   = $('<td class="afx-col-block" data-label="Descripción"></td>');
        if (config.detailIdFieldName) {
            //Fila nueva en editar: el servidor inserta las filas con ID 0
            $textCell.append($('<input type="hidden">').attr("name", config.detailIdFieldName).val("0"));
        }
        $textCell.append($('<input class="form-control" type="text" autocomplete="off">').attr("name", config.textFieldName).val(texto));

        var $select = $('<select class="select2"></select>').attr("name", "txtCommentsIDArray[]");
        $.each(config.commentOptions, function(i, option){
            $select.append($("<option></option>").attr("value", option.id).text(option.name).prop("selected", String(option.id) === String(comboValue)));
        });

        var $row = $('<tr class="row_razon"></tr>')
            .append($textCell)
            .append($('<td class="afx-col-block" data-label="Situación"></td>').append($select))
            .append('<td class="pv2-col-del"><button type="button" class="btn btn-link pv2-btn-del" title="Quitar" onclick="fnEliminarFila(this)"><i class="fas fa-trash"></i></button></td>');

        //Agregar la nueva fila despues de la fila de entrada
        $("#filaEntrada").after($row);

        comentarioTarea.val("");
        selectComments.val(null).trigger("change");

        afxInitSelect2($select);
    }
</script>
