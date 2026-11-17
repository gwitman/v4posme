<style>
    /* ==========================================================
       Sustitucion de productos facturados (prefijo ret)
       Usada por: app_invoice_return news / edit.
       Requiere: core_template/posme_v2_style, posme_v2_common y
       app_afx_fixedassent/afx_v2_common.
       ========================================================== */

    /* Avisos informativos */
    .pv2 .ret-aviso { display:flex; gap:10px; align-items:flex-start; background:var(--pv2-info-bg); color:var(--pv2-info); border:1px solid rgba(21,101,192,.25); border-radius:10px; padding:10px 12px; margin-bottom:14px; font-size:13px; }
    .pv2 .ret-aviso.is-warn { background:var(--pv2-warn-bg); color:var(--pv2-warn); border-color:rgba(138,90,0,.25); }
    .pv2 .ret-aviso p { margin:0; }
    .pv2 .ret-aviso p + p { margin-top:4px; }
    .pv2 .ret-aviso i { flex:0 0 auto; margin-top:2px; }

    /* Texto de solo lectura con aspecto de campo */
    .pv2 .ret-readonly { display:block; min-height:38px; padding:9px 12px; background:var(--pv2-soft); border:1px solid #d5dae0; border-radius:8px; font-size:14px; word-break:break-word; }

    /* Documentos generados */
    .pv2 .ret-docs-item { display:flex; justify-content:space-between; align-items:center; gap:10px; padding:8px 0; border-top:1px solid var(--pv2-border); font-size:14px; }
    .pv2 .ret-docs-item:first-of-type { border-top:0; }
    .pv2 .ret-docs-item a { font-weight:700; }

    /* Detalle: anchos relativos (sin px fijos salvo la casilla de quitar) */
    @media (min-width:768px) {
        .pv2 #tb_transaction_master_detail th.ret-col-line { width:28%; }
        .pv2 #tb_transaction_master_detail th.ret-col-num  { width:10%; }
        .pv2 #tb_transaction_master_detail th.ret-col-qty  { width:12%; }
        .pv2 #tb_transaction_master_detail th.ret-col-um   { width:8%; }
        .pv2-table tr.row_razon.has-error td { background:var(--pv2-bad-bg) !important; }
        .pv2 .ret-sust-search .btn span { display:none; }
    }

    @media (max-width:767px) {
        /* El buscador del sustituto tiene un solo boton: a todo el ancho */
        .pv2 .ret-sust-search > .input-group-btn { flex:1 1 100%; }
        .pv2-table tr.row_razon td.afx-col-block > .ret-sust-search { width:100%; }
    }
</style>
<script>
    /* ==========================================================
       Sustitucion de productos facturados - funciones compartidas de news / edit.
       Cada pantalla define antes la variable retConfig:
         urlBase, componentItemID, cantidadItemPopup, maxDays,
         transactionMasterID (0 en nuevo), partial (bool), saveUrl.
       Las cantidades se muestran 1,000.00 y se envian como numero puro.
       ========================================================== */

    var retInvoice        = null;   //Factura elegida (objeto devuelto por getInvoiceDetailAvailable)
    var retLinesByID      = {};     //Lineas de la factura por transactionMasterDetailID
    var retLineOrder      = [];     //Ids de las lineas en el orden de la factura
    var retActiveRow      = null;   //Renglon que abrio la busqueda del producto sustituto
    var retInvoiceInvalid = false;  //La ultima consulta de la factura fallo: bloquea guardar
    var retLastQueryDate  = "";     //Fecha con la que se valido la factura por ultima vez
    var retSubmitting     = false;  //Evita doble envio del formulario
    var retDateTimer      = null;

    //Escapa texto para insertarlo como HTML
    function retEsc(value){
        return $("<div>").text(value === null || value === undefined ? "" : String(value)).html();
    }

    //Cantidad para mostrar: 1,000.00 (sin alterar el valor)
    function retFmtQty(value){
        var texto = afxFmtMonto(value);
        return texto === "" ? "0.00" : texto;
    }

    //"codigo / nombre" omitiendo las partes vacias
    function retJoin(a, b, separator){
        a = $.trim(a === null || a === undefined ? "" : String(a));
        b = $.trim(b === null || b === undefined ? "" : String(b));
        return (a !== "" && b !== "") ? a + separator + b : (a !== "" ? a : b);
    }

    function retLineText(line){
        return retJoin(line.itemNumber, line.itemName, " - ");
    }

    function retHasDetail(){
        return $("#body_tb_transaction_master_detail").find("tr").length > 0;
    }

    function retClearDetail(){
        $("#body_tb_transaction_master_detail").empty();
    }

    function retSetMaxDays(maxDays){
        $(".ret-max-days").text(parseInt(maxDays, 10) || 0);
    }

    //Marca la factura como no valida (fecha fuera de plazo, anulada, etc.)
    function retSetInvalid(flag){
        retInvoiceInvalid = flag;
        $("#retInvoiceCard").toggleClass("has-error", flag);
    }

    //Guarda las lineas de la factura en el orden recibido
    function retSetLines(detail){
        retLinesByID = {};
        retLineOrder = [];
        $.each(detail || [], function(i, line){
            var id = String(line.transactionMasterDetailID);
            retLinesByID[id] = line;
            retLineOrder.push(id);
        });
    }

    //Llena los campos informativos de la factura (cliente, fecha y bodega salen de la factura)
    function retFillInvoiceFields(invoice){
        retInvoice = invoice;
        $("#txtInvoiceNumber").val(invoice ? invoice.transactionNumber : "");
        $("#txtInvoiceTransactionMasterID").val(invoice ? invoice.transactionMasterID : "");
        $("#txtCustomerDescription").val(invoice ? retJoin(invoice.customerNumber, invoice.customerName, " / ") : "");
        $("#txtInvoiceDate").val(invoice ? invoice.transactionOn : "");
        $("#txtInvoiceWarehouse").val(invoice ? invoice.warehouseName : "");
    }

    function retClearInvoice(){
        retFillInvoiceFields(null);
        retSetLines([]);
        retSetInvalid(false);
        retLastQueryDate = "";
    }

    //Opciones del combo "Producto facturado"; las lineas sin disponible quedan deshabilitadas
    function retBuildOptions($select, selectedID){
        selectedID = (selectedID === null || selectedID === undefined) ? "" : String(selectedID);
        $select.empty();
        $select.append($("<option>").val("").text("Seleccione el producto..."));
        $.each(retLineOrder, function(i, id){
            var line       = retLinesByID[id];
            var sinDispon  = pv2Num(line.quantityAvailable) <= 0;
            var $option    = $("<option>").val(id).text(retLineText(line) + (sinDispon ? " (sin disponible)" : ""));
            if(sinDispon && id !== selectedID) $option.prop("disabled", true);
            $select.append($option);
        });
        $select.val(selectedID !== "" && retLinesByID[selectedID] ? selectedID : "");
    }

    //Copia al renglon los datos de la linea elegida (producto, facturado, disponible, U/M)
    function retApplyLineToRow($tr){
        var line = retLinesByID[String($tr.find(".ret-invoice-line").val())];
        $tr.find(".ret-item-id").val(line ? line.itemID : "");
        $tr.find(".ret-facturado").text(line ? retFmtQty(line.quantityInvoiced) : "");
        $tr.find(".ret-disponible").text(line ? retFmtQty(line.quantityAvailable) : "");
        $tr.find(".ret-um").text(line ? (line.unitMeasureName || "") : "");
    }

    //Reconstruye los combos de todos los renglones conservando la seleccion
    function retRefreshRows(){
        $("#body_tb_transaction_master_detail").find("tr").each(function(){
            var $tr = $(this);
            retBuildOptions($tr.find(".ret-invoice-line"), $tr.find(".ret-invoice-line").val());
            retApplyLineToRow($tr);
        });
    }

    /* Agrega un renglon. row: {transactionMasterDetailID, invoiceDetailID, itemSustitutoID, sustitutoText, quantity} */
    function retAddRow(row){
        row     = row || {};
        var $tr = $($("#tmpl_ret_row").html());
        $tr.find(".ret-tmd-id").val(row.transactionMasterDetailID || 0);
        retBuildOptions($tr.find(".ret-invoice-line"), row.invoiceDetailID);
        $tr.find(".ret-sustituto-id").val(row.itemSustitutoID || "");
        $tr.find(".ret-sustituto-desc").val(row.sustitutoText || "");
        $tr.find(".ret-quantity").val(row.quantity === undefined || row.quantity === null || row.quantity === "" ? "" : afxFmtMonto(row.quantity));
        $("#body_tb_transaction_master_detail").append($tr);
        retApplyLineToRow($tr);
        return $tr;
    }

    //Agrega un renglon vacio; si la factura tiene una sola linea disponible se preselecciona
    function retAddEmptyRow(){
        var disponibles = $.grep(retLineOrder, function(id){ return pv2Num(retLinesByID[id].quantityAvailable) > 0; });
        var $tr         = retAddRow({ invoiceDetailID: disponibles.length == 1 ? disponibles[0] : "" });
        if(disponibles.length == 1) retDefaultQuantity($tr);
        return $tr;
    }

    //Cantidad sugerida al elegir la linea: 1 o lo disponible si es menor
    function retDefaultQuantity($tr){
        var line = retLinesByID[String($tr.find(".ret-invoice-line").val())];
        var $qty = $tr.find(".ret-quantity");
        if(!line || $.trim($qty.val()) !== "") return;
        var disponible = pv2Num(line.quantityAvailable);
        if(disponible > 0) $qty.val(afxFmtMonto(disponible < 1 ? disponible : 1));
    }

    /* Consulta la factura y sus lineas disponibles.
       mode "select": factura recien elegida; si falla se limpia.
       mode "refresh": revalidar (cambio de fecha / carga de edit); si falla se marca como no valida. */
    function retLoadInvoice(invoiceNumber, mode){
        invoiceNumber = $.trim(invoiceNumber || "");
        if(invoiceNumber === "") return;

        var onFail = function(message){
            fnShowNotification(message, "error", 15000);
            if(mode == "select") retClearInvoice();
            else retSetInvalid(true);
        };

        fnWaitOpen();
        $.ajax({
            cache       : false,
            dataType    : "json",
            type        : "POST",
            url         : retConfig.urlBase + "/app_invoice_return/getInvoiceDetailAvailable",
            data        : {
                invoiceNumber              : invoiceNumber,
                excludeTransactionMasterID : retConfig.transactionMasterID,
                transactionOn              : $("#txtTransactionOn").val()
            },
            success     : function(data){
                fnWaitClose();
                if(data && data.maxDays !== undefined) retSetMaxDays(data.maxDays);

                //Error del servidor (no existe, anulada, fuera de plazo...)
                if(!data || data.error === true){
                    onFail((data && data.message) ? data.message : "No fue posible consultar la factura");
                    return;
                }

                //Sin datos: la factura no tiene productos
                if(!data.invoice || !data.detail || data.detail.length == 0){
                    onFail("La factura #" + invoiceNumber + " no tiene productos para sustituir");
                    return;
                }

                //Con datos
                retFillInvoiceFields(data.invoice);
                retSetLines(data.detail);
                retSetInvalid(false);
                retLastQueryDate = $("#txtTransactionOn").val();
                retRefreshRows();
                if(mode == "select" && !retHasDetail()) retAddEmptyRow();
            },
            error       : function(){
                fnWaitClose();
                onFail("Error al consultar la factura");
            }
        });
    }

    //Respuesta del buscador de facturas: objResponse[0][2] = numero de factura
    function onCompleteBilling(objResponse){
        if(!objResponse || !objResponse[0]) return;
        var number  = $.trim(objResponse[0][2]);
        var current = $.trim($("#txtInvoiceNumber").val());
        if(number === "") return;

        //Misma factura: solo se revalida
        if(number === current){
            retLoadInvoice(number, "refresh");
            return;
        }

        var proceed = function(){
            retClearDetail();
            retClearInvoice();
            $("#txtInvoiceNumber").val(number);
            retLoadInvoice(number, "select");
        };

        //Cambiar de factura vacia el detalle: confirmar
        if(retHasDetail()){
            fnShowConfirm("Confirmar..", "Al cambiar la factura se quitaran los productos del detalle. Desea continuar?", proceed);
            return;
        }
        proceed();
    }

    //Respuesta del buscador de productos: [1] itemID, [2] codigo, [3] nombre
    function onCompleteSustituto(objResponse){
        if(!objResponse || !objResponse[0] || !retActiveRow || retActiveRow.closest("body").length == 0) return;
        var itemID = String(objResponse[0][1]);

        if(itemID === String(retActiveRow.find(".ret-item-id").val())){
            fnShowNotification("El producto sustituto debe ser distinto al producto facturado", "error", 15000);
            return;
        }

        retActiveRow.find(".ret-sustituto-id").val(itemID);
        retActiveRow.find(".ret-sustituto-desc").val(retJoin(objResponse[0][2], objResponse[0][3], " - "));
        retActiveRow.removeClass("has-error");
    }

    /* Validaciones antes de guardar. Devuelve true si el formulario es valido */
    function retValidateForm(){
        var errors = [];
        var $rows  = $("#body_tb_transaction_master_detail").find("tr");
        $rows.removeClass("has-error");

        if($.trim($("#txtTransactionOn").val()) === "") errors.push("Escriba la fecha del documento");
        if($.trim($("#txtStatusID").val() || "") === "") errors.push("Seleccione el estado");

        //Aplicacion interrumpida: el servidor solo reintenta aplicar, el detalle no se envia
        if(retConfig.partial){
            retShowErrors(errors);
            return errors.length == 0;
        }

        if($.trim($("#txtInvoiceNumber").val()) === "" || $.trim($("#txtInvoiceTransactionMasterID").val()) === "")
            errors.push("Seleccione la factura");
        else if(retInvoiceInvalid || !retInvoice)
            errors.push("La factura no es valida para la fecha indicada; revise la fecha o elija otra factura");

        if($rows.length == 0) errors.push("Agregue al menos un producto a sustituir");

        //Por renglon: producto, cantidad y sustituto
        var sumByLine = {};
        var rowsByLine = {};
        $rows.each(function(index){
            var $tr      = $(this);
            var number   = index + 1;
            var lineID   = String($tr.find(".ret-invoice-line").val() || "");
            var line     = retLinesByID[lineID];
            var quantity = pv2Num($tr.find(".ret-quantity").val());
            var sustID   = $.trim($tr.find(".ret-sustituto-id").val());
            var rowOk    = true;

            if(!line){ errors.push("Linea " + number + ": seleccione el producto facturado"); rowOk = false; }
            if(quantity <= 0){ errors.push("Linea " + number + ": la cantidad debe ser mayor que 0"); rowOk = false; }
            if(sustID === ""){ errors.push("Linea " + number + ": seleccione el producto sustituto"); rowOk = false; }
            else if(line && sustID === String(line.itemID)){ errors.push("Linea " + number + ": el producto sustituto debe ser distinto al facturado"); rowOk = false; }
            if(!rowOk) $tr.addClass("has-error");

            if(line){
                sumByLine[lineID]  = afxRedondear((sumByLine[lineID] || 0) + quantity);
                rowsByLine[lineID] = (rowsByLine[lineID] || []).concat([$tr]);
            }
        });

        //La suma por linea de factura no puede superar lo disponible
        $.each(sumByLine, function(lineID, total){
            var line = retLinesByID[lineID];
            if(total > afxRedondear(line.quantityAvailable)){
                errors.push("El producto '" + retLineText(line) + "' supera la cantidad disponible (disponible " + retFmtQty(line.quantityAvailable) + ", solicitado " + retFmtQty(total) + ")");
                $.each(rowsByLine[lineID], function(i, $tr){ $tr.addClass("has-error"); });
            }
        });

        retShowErrors(errors);
        return errors.length == 0;
    }

    //Muestra hasta 4 errores para no saturar la pantalla
    function retShowErrors(errors){
        var max = Math.min(errors.length, 4);
        for(var i = 0; i < max; i++){
            fnShowNotification(errors[i], "error", 15000);
        }
        if(errors.length > max) fnShowNotification("Hay " + (errors.length - max) + " errores mas en el detalle", "error", 15000);
    }

    /* Enlaza los eventos del formulario editable (news / edit no aplicado). Llamar una sola vez */
    function retBindForm(){
        //Buscar factura
        $(document).on("click", "#btnSearchInvoice", function(){
            var url_request = retConfig.urlBase + "/core_view/showviewbynamepaginate/" + retConfig.componentItemID + "/onCompleteBilling/SELECCIONAR_FACTURA_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/" + retConfig.cantidadItemPopup + "/";
            pv2OpenSearch(url_request, "Buscar factura", "onCompleteBilling", onCompleteBilling, "width=900,height=450");
        });

        //Limpiar factura (y el detalle, que depende de ella)
        $(document).on("click", "#btnClearInvoice", function(){
            var limpiar = function(){
                retClearDetail();
                retClearInvoice();
            };
            if(retHasDetail()){
                fnShowConfirm("Confirmar..", "Al limpiar la factura se quitaran los productos del detalle. Desea continuar?", limpiar);
                return;
            }
            limpiar();
        });

        //Cambio de fecha con factura elegida: revalidar el plazo
        $(document).on("change changeDate", "#txtTransactionOn", function(){
            clearTimeout(retDateTimer);
            retDateTimer = setTimeout(function(){
                var fecha  = $("#txtTransactionOn").val();
                var number = $.trim($("#txtInvoiceNumber").val());
                if(number === "" || fecha === "" || fecha === retLastQueryDate) return;
                retLoadInvoice(number, "refresh");
            }, 300);
        });

        //Agregar renglon
        $(document).on("click", "#btnNewDetailTransaction", function(){
            if(!retInvoice){
                fnShowNotification("Seleccione primero la factura", "error", 15000);
                return;
            }
            if(retInvoiceInvalid){
                fnShowNotification("La factura no es valida para la fecha indicada", "error", 15000);
                return;
            }
            retAddEmptyRow();
        });

        //Quitar renglones marcados
        $(document).on("click", "#btnDeleteDetailTransaction", function(){
            var $marcados = $("#body_tb_transaction_master_detail").find(".txtCheckedIsActive:checked");
            if($marcados.length == 0){
                fnShowNotification("Marque los productos que desea quitar", "error");
                return;
            }
            $marcados.closest("tr").remove();
        });

        //Cambio de producto facturado en un renglon
        $(document).on("change", "#body_tb_transaction_master_detail .ret-invoice-line", function(){
            var $tr = $(this).closest("tr");
            retApplyLineToRow($tr);
            $tr.removeClass("has-error");

            //El sustituto no puede ser el mismo producto
            var itemID = String($tr.find(".ret-item-id").val());
            if(itemID !== "" && itemID === String($tr.find(".ret-sustituto-id").val())){
                $tr.find(".ret-sustituto-id").val("");
                $tr.find(".ret-sustituto-desc").val("");
                fnShowNotification("El producto sustituto debe ser distinto al producto facturado", "error", 15000);
            }
            retDefaultQuantity($tr);
        });

        //Al escribir la cantidad se quita la marca de error del renglon
        $(document).on("input", "#body_tb_transaction_master_detail .ret-quantity", function(){
            $(this).closest("tr").removeClass("has-error");
        });

        //Buscar producto sustituto (filtrado por la bodega de la factura)
        $(document).on("click", "#body_tb_transaction_master_detail .btnRetSearchSustituto", function(){
            if(!retInvoice){
                fnShowNotification("Seleccione primero la factura", "error", 15000);
                return;
            }
            if(!retInvoice.warehouseID){
                fnShowNotification("No se pudo determinar la bodega de la factura", "error", 15000);
                return;
            }
            retActiveRow    = $(this).closest("tr");
            var url_request = retConfig.urlBase + "/core_view/showviewbynamepaginate/" + retConfig.componentItemID + "/onCompleteSustituto/SELECCIONAR_ITEM_TO_OUTPUT_PAGINATED/true/" + encodeURI("{\"warehouseID\"|\"" + retInvoice.warehouseID + "\"}") + "/false/not_redirect_when_empty/1/1/" + retConfig.cantidadItemPopup + "/";
            pv2OpenSearch(url_request, "Buscar producto sustituto", "onCompleteSustituto", onCompleteSustituto, "width=900,height=450");
        });

        retBindSave();
    }

    //Guardar: valida, deja las cantidades como numero puro y envia
    function retBindSave(){
        $(document).on("click", "#btnAcept", function(e){
            e.preventDefault();
            if(retSubmitting) return;
            if(!retValidateForm()) return;

            retSubmitting = true;
            fnWaitOpen();
            afxLimpiarMontos("#form-new-transaction");
            $("#form-new-transaction").attr("method", "POST").attr("action", retConfig.saveUrl).submit();
        });
    }
</script>

<!-- renglon editable del detalle (sin ids: se repite) -->
<script type="text/template" id="tmpl_ret_row">
    <tr class="row_razon">
        <td class="td-checkbox" data-label="Quitar">
            <input type="checkbox" class="txtCheckedIsActive" value="1" aria-label="Quitar">
            <input type="hidden" class="ret-tmd-id" name="txtDetailTransactionDetailID[]" value="0">
            <input type="hidden" class="ret-item-id" name="txtDetailItemID[]" value="">
            <input type="hidden" class="ret-sustituto-id" name="txtDetailItemSustitutoID[]" value="">
        </td>
        <td class="afx-col-block" data-label="Producto facturado">
            <select class="form-control ret-invoice-line" name="txtDetailInvoiceDetailID[]" aria-label="Producto facturado"></select>
        </td>
        <td data-label="Facturado" class="pv2-num"><span class="ret-facturado"></span></td>
        <td data-label="Disponible" class="pv2-num"><span class="ret-disponible"></span></td>
        <td data-label="Cantidad">
            <input class="form-control pv2-num afx-monto afx-sin-negativo ret-quantity" type="text" inputmode="decimal" autocomplete="off" name="txtDetailQuantity[]" value="" placeholder="0.00" aria-label="Cantidad">
        </td>
        <td class="afx-col-block" data-label="Producto sustituto">
            <div class="input-group afx-search ret-sust-search">
                <input class="form-control ret-sustituto-desc" type="text" readonly autocomplete="off" value="" placeholder="Buscar producto..." aria-label="Producto sustituto">
                <span class="input-group-btn">
                    <button class="btn btn-primary btnRetSearchSustituto" type="button" title="Buscar producto sustituto">
                        <i aria-hidden="true" class="i-search-5"></i> <span>Buscar</span>
                    </button>
                </span>
            </div>
        </td>
        <td data-label="U/M"><span class="ret-um"></span></td>
    </tr>
</script>
