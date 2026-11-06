<style>
    /* ==========================================================
       Familia Taller - complementos del estandar pv2 (prefijo tal)
       Usada por: app_purchase_pedidos, _garantia, _taller y _request.
       Requiere: posme_v2_style, posme_v2_common y afx_v2_common.
       ========================================================== */

    /* Fecha y hora en la misma linea; en pantallas angostas bajan a dos lineas sin scroll horizontal */
    .pv2 .tal-datetime { display:flex; flex-wrap:wrap; gap:8px; }
    .pv2 .tal-datetime > .tal-datetime-item { flex:1 1 150px; min-width:0; }

    /* Archivos: uniform envuelve el input; se limita para que no desborde la tarjeta */
    .pv2 div.uploader { max-width:100%; }
    .pv2 .tal-file-link { display:inline-block; height:auto; max-width:100%; margin-top:6px; white-space:normal; word-break:break-all; text-align:left; }

    /* Fila de captura de comentarios */
    .pv2 #errorLabel { margin:4px 0 0; font-size:12px; }

    /* Menu de opciones dentro del encabezado */
    .pv2 .pv2-actions .btn-group { display:inline-flex; }

    @media (max-width:767px) {
        .pv2 .pv2-actions .btn-group { flex:1 1 auto; }
        .pv2 .pv2-actions .btn-group > .btn { width:100%; }
        .pv2-table tr.row_razon td #btnAddComments { width:100%; }
    }
</style>
<script>
    /* ==========================================================
       Familia Taller - funciones de interfaz compartidas (prefijo tal)
       Requiere: posme_v2_common y afx_v2_common. No contiene logica de negocio:
       las validaciones, las busquedas y los getBehavio viven en cada pantalla.
       ========================================================== */

    /* Fecha que puede quedar vacia. pv2InitDate pone la fecha de hoy cuando no hay valor;
       aqui se respeta el vacio, porque en estos campos vacio tiene significado (sin
       siguiente visita, sin fecha de bodega). */
    function talInitDateOpcional(id){
        var input = document.getElementById(id);
        if(!input) return;

        var valor = $.trim(input.value).substring(0,10);
        pv2InitDate(id, false);
        input.value = valor;
        if(!pv2IsMobile()) $(input).datepicker("update");
    }

    /* Enlaza lo comun de la pantalla. Llamar una sola vez: afxBindCommon ya enlaza la barra movil.
       La limpieza de montos va en el evento submit para cubrir todas las salidas del formulario
       (boton Guardar, barra movil y aceptar del modal de siguiente visita). */
    function talBindCommon(){
        afxBindCommon();
        $("#form-new-invoice").on("submit", function(){
            afxLimpiarMontos("#form-new-invoice");
        });
    }

    /* Los scripts de validacion por cliente comparan txtDetailAmount contra '0' como texto.
       Con el formato visual el campo vale '0.00', y esa comparacion dejaria de detectar el cero.
       Se deja '0' mientras corren esos scripts y despues se restaura el formato. */
    function talMontoAntesDeValidar(){
        var $monto = $("#txtDetailAmount");
        if($monto.length > 0 && pv2Num($monto.val()) == 0) $monto.val("0");
    }
    function talMontoDespuesDeValidar(){
        var $monto = $("#txtDetailAmount");
        if($monto.length > 0) $monto.val(afxFmtMonto($monto.val()));
    }

    /* Agrega una fila de comentario despues de la fila de captura. El texto y las opciones se
       asignan por DOM: un apostrofo en el comentario no corta el valor.
       config: comboValue (situacion a preseleccionar), conDetalleID (solo editar: el servidor
       inserta las filas con ID 0), commentOptions ([{id, name}]) */
    function talAgregarFila(config){
        var texto = $("#txtComentarioTaller").val();

        //Si el campo de texto esta vacio, mostrar el mensaje de error
        if(texto === ""){
            $("#errorLabel").show();
            return;
        }
        $("#errorLabel").hide();

        var $textCell = $('<td class="afx-col-block" data-label="Descripción"></td>');
        if(config.conDetalleID){
            $textCell.append($('<input type="hidden" name="commentsDetailID[]" value="0">'));
        }
        $textCell.append($('<input class="form-control" type="text" autocomplete="off" name="txtComentarioTallerArray[]">').val(texto));

        var $select = $('<select class="select2" name="txtCommentsIDArray[]" id="comboCommentsId"></select>');
        $.each(config.commentOptions, function(i, option){
            $select.append($("<option></option>").attr("value", option.id).text(option.name).prop("selected", String(option.id) === String(config.comboValue)));
        });

        var $row = $('<tr class="row_razon"></tr>')
            .append($textCell)
            .append($('<td class="afx-col-block" data-label="Situación"></td>').append($select))
            .append('<td class="pv2-col-del"><button type="button" class="btn btn-link pv2-btn-del" title="Quitar" onclick="fnEliminarFila(this)"><i class="fas fa-trash"></i></button></td>');

        //Agregar la nueva fila despues de la fila de captura
        $("#filaEntrada").after($row);

        $("#txtComentarioTaller").val("");
        $("#txtSelectComments").val(null).trigger("change");

        //Select2 en PC; combo nativo en telefono
        afxInitSelect2($select);
    }
</script>
