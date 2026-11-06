<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">Orden de Produccion:#<span class="invoice-num">00000000</span></p>
                <p class="pv2-header-sub">Registra los insumos y los resultados de la orden</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_inventory_production/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-production-order" name="form-new-production-order" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">General</a></li>
                <li><a href="#inputs" data-toggle="tab">Insumos</a></li>
                <li><a href="#results" data-toggle="tab">Resultados</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> La orden</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha <span class="pv2-req">*</span></label>
                                    <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        if ($objListWorkflowStage)
                                            foreach ($objListWorkflowStage as $ws) {
                                                echo "<option value='" . $ws->workflowStageID . "' selected>" . $ws->name . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                        <?php
                                        if ($objListCurrency)
                                            foreach ($objListCurrency as $ws) {
                                                if ($ws->currencyID == $objCurrency->currencyID)
                                                    echo "<option value='" . $ws->currencyID . "' selected>" . $ws->simb . "</option>";
                                                else
                                                    echo "<option value='" . $ws->currencyID . "' >" . $ws->simb . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtTransactionTotalAmount">Monto Total</label>
                                    <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtTransactionTotalAmount" id="txtTransactionTotalAmount" value="">
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Personas</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtRequestEmployeeDescription">Solicitado Por</label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtRequestEmployeeID" name="txtRequestEmployeeID" value="">
                                        <input class="form-control" readonly id="txtRequestEmployeeDescription" type="text" autocomplete="off" value="">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btnClearRequestEmployee">
                                                <i aria-hidden="true" class="i-undo-2"></i>
                                                Limpiar
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="btnSearchRequestEmployee">
                                                <i aria-hidden="true" class="i-search-5"></i>
                                                Buscar
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtSenderEmployeeDescription">Elaborado Por</label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtSenderEmployeeID" name="txtSenderEmployeeID" value="">
                                        <input class="form-control" readonly id="txtSenderEmployeeDescription" type="text" autocomplete="off" value="">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btnClearSenderEmployee">
                                                <i aria-hidden="true" class="i-undo-2"></i>
                                                Limpiar
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="btnSearchSenderEmployee">
                                                <i aria-hidden="true" class="i-search-5"></i>
                                                Buscar
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil"></i></span> Referencias</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference1">Referencia 1</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference2">Referencia 2</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtReference3">Referencia 3</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="">
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtNote">Comentario</label>
                                    <textarea class="form-control" autocomplete="off" name="txtNote" id="txtNote"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade pv2-card" id="inputs">
                    <div class="pv2-detail-head">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Insumos <span class="pv2-count" id="prodInputCount">0</span></p>
                        <div class="pv2-actions">
                            <a href="#" class="btn btn-primary" id="btnNewDetailTransactionItemInput"><i class="icon16 i-plus"></i> Agregar</a>
                            <a href="#" class="btn btn-default" id="btnEditDetailTransactionItemInput"><i class="icon16 i-pencil-4"></i> Editar</a>
                            <a href="#" class="btn btn-danger" id="btnDeleteDetailTransactionItemInput"><i class="icon16 i-remove"></i> Eliminar</a>
                        </div>
                    </div>
                    <div class="pv2-empty" id="prodInputEmpty"><i class="icon20 i-list-2"></i>Aun no hay insumos. Usa Agregar para registrar el primero.</div>
                    <div id="div_transaction_master_detail_item_input" class="pv2-table-wrap">
                        <table id="tb_transaction_master_detail_item_input" class="pv2-table">
                            <thead>
                                <tr>
                                    <th class="afx-col-quitar"></th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Costo Unitario</th>
                                    <th>Costo Total</th>
                                    <th>Bodega de Origen</th>
                                    <th>Producto Destino</th>
                                </tr>
                            </thead>
                            <tbody id="body_tb_transaction_master_detail_item_input">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade pv2-card" id="results">
                    <div class="pv2-detail-head">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Resultados <span class="pv2-count" id="prodOutputCount">0</span></p>
                        <div class="pv2-actions">
                            <a href="#" class="btn btn-primary" id="btnNewDetailTransactionItemOutput"><i class="icon16 i-plus"></i> Agregar</a>
                            <a href="#" class="btn btn-default" id="btnEditDetailTransactionItemOutput"><i class="icon16 i-pencil-4"></i> Editar</a>
                            <a href="#" class="btn btn-danger" id="btnDeleteDetailTransactionItemOutput"><i class="icon16 i-remove"></i> Eliminar</a>
                        </div>
                    </div>
                    <div class="pv2-empty" id="prodOutputEmpty"><i class="icon20 i-list-2"></i>Aun no hay resultados. Usa Agregar para registrar el primero.</div>
                    <div id="div_transaction_master_detail_item_output" class="pv2-table-wrap">
                        <table id="tb_transaction_master_detail_item_output" class="pv2-table">
                            <thead>
                                <tr>
                                    <th class="afx-col-quitar"></th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Costo Unitario</th>
                                    <th>Costo Total</th>
                                    <th>Bodega Destino</th>
                                </tr>
                            </thead>
                            <tbody id="body_tb_transaction_master_detail_item_output">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- Tabla de manejo de Insumos -->
    <script type="text/template" id="tmpl_row_detail_item_input">
        <tr class="row_razon">
            <td class="td-checkbox" data-label="Seleccionar">
                <input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
                <input type="hidden" name="txtItemInputID[]"                    id="txtItemInputID"  />
                <input type="hidden" name="txtItemInputWarehouseSourceID[]"     id="txtItemInputWarehouseSourceID"  />
                <input type="hidden" name="txtItemInputProductDestinationID[]"  id="txtItemInputProductDestinationID" value="">
            </td>
            <td data-label="Codigo">
                <span id="txtItemInputCode"></span>
            </td>
            <td data-label="Nombre">
                <span id="txtItemInputName"></span>
            </td>
            <td data-label="Cantidad">
                <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtItemInputQuantity[]" id="txtItemInputQuantity" value="">
            </td>
            <td data-label="Costo Unitario">
                <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtItemInputUnitaryCost[]" id="txtItemInputUnitaryCost" value="">
            </td>
            <td data-label="Costo Total">
                <input class="form-control txt-numeric afx-monto pv2-num txtItemInputTotalCost" readonly type="text" autocomplete="off" name="txtItemInputTotalCost[]" id="txtItemInputTotalCost" value="">
            </td>
            <td class="afx-col-block" data-label="Bodega de Origen">
                <input class="form-control" readonly type="text" autocomplete="off" name="txtItemInputWarehouseSource[]" id="txtItemInputWarehouseSource" value="">
            </td>
            <td class="afx-col-block" data-label="Producto Destino">
                <input class="form-control" readonly type="text" autocomplete="off" name="txtItemInputProductDestination[]" id="txtItemInputProductDestination" value="">
            </td>
        </tr>
    </script>

    <!-- Barra Lateral de manejo de Insumos: fuera del form para que sus campos no se envien -->
    <div id="mySidebarItemInput" class="sidebar">
        <div class="sidebar-content">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Insumo</p>

            <input type="hidden" name="txtSidebarRequestItemUnitaryCost" id="txtSidebarRequestItemUnitaryCost" value="">

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarRequestItemDescription">Producto Origen <span class="pv2-req">*</span></label>
                <div class="input-group afx-search">
                    <input type="hidden" id="txtSidebarRequestItemID" name="txtSidebarRequestItemID" value="">
                    <input class="form-control" readonly id="txtSidebarRequestItemDescription" type="text" autocomplete="off" value="">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="btnSidebarClearRequestItem">
                            <i aria-hidden="true" class="i-undo-2"></i>
                            Limpiar
                        </button>
                    </span>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="btnSidebarSearchRequestItem">
                            <i aria-hidden="true" class="i-search-5"></i>
                            Buscar
                        </button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarDestinationItemDescription">Producto Destino <span class="pv2-req">*</span></label>
                <div class="input-group afx-search">
                    <input type="hidden" id="txtSidebarDestinationItemID" name="txtSidebarDestinationItemID" value="">
                    <input class="form-control" readonly id="txtSidebarDestinationItemDescription" type="text" autocomplete="off" value="">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="btnSidebarClearDestinationItem">
                            <i aria-hidden="true" class="i-undo-2"></i>
                            Limpiar
                        </button>
                    </span>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="btnSidebarSearchDestinationItem">
                            <i aria-hidden="true" class="i-search-5"></i>
                            Buscar
                        </button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarItemSourceWarehouse">Bodega Origen</label>
                <select name="txtSidebarItemSourceWarehouse" id="txtSidebarItemSourceWarehouse" class="select2">
                    <option></option>
                    <?php
                    if ($objListWarehouse)
                        foreach ($objListWarehouse as $ws) {
                            echo "<option value='" . $ws->warehouseID . "' data-name='" . $ws->name . "' selected>" . $ws->name . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarRequestItemQuantity">Cantidad <span class="pv2-req">*</span></label>
                <input class="form-control txt-numeric afx-monto pv2-num" type="text" inputmode="decimal" autocomplete="off" name="txtSidebarRequestItemQuantity" id="txtSidebarRequestItemQuantity" value="">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarInputTotalCost">Monto Total</label>
                <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtSidebarInputTotalCost" id="txtSidebarInputTotalCost" value="">
            </div>

            <div class="pv2-actions">
                <button type="button" id="btnSaveSidebarItemInput" class="btn btn-primary">Salvar</button>
                <button type="button" id="btnCloseSidebarItemInput" class="btn btn-default">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Tabla de manejo de Productos Finales -->
    <script type="text/template" id="tmpl_row_detail_item_output">
        <tr class="row_razon">
            <td class="td-checkbox" data-label="Seleccionar">
                <input type="checkbox" class="txtOutputCheckedIsActive" name="txtOutputCheckedIsActive[]" value="1" />
                <input type="hidden" name="txtItemOutputID[]"                   id="txtItemOutputID"  />
                <input type="hidden" name="txtItemOutputWarehouseTargetID[]"    id="txtItemOutputWarehouseTargetID"  />
            </td>
            <td data-label="Codigo">
                <span id="txtItemOutputCode"></span>
            </td>
            <td data-label="Nombre">
                <span id="txtItemOutputName"></span>
            </td>
            <td data-label="Cantidad">
                <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtItemOutputQuantity[]" id="txtItemOutputQuantity" value="">
            </td>
            <td data-label="Costo Unitario">
                <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtItemOutputUnitaryCost[]" id="txtItemOutputUnitaryCost" value="">
            </td>
            <td data-label="Costo Total">
                <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtItemOutputTotalCost[]" id="txtItemOutputTotalCost" value="">
            </td>
            <td class="afx-col-block" data-label="Bodega Destino">
                <input class="form-control" readonly type="text" autocomplete="off" name="txtItemOutputWarehouseTarget[]" id="txtItemOutputWarehouseTarget" value="">
            </td>
        </tr>
    </script>

    <!-- Barra Lateral de manejo de Productos Finales: fuera del form para que sus campos no se envien -->
    <div id="mySidebarItemOutput" class="sidebar">
        <div class="sidebar-content">
            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Resultados</p>

            <input type="hidden" name="txtSidebarResponseItemUnitaryCost" id="txtSidebarResponseItemUnitaryCost" value="">

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarResponseItemDescription">Producto Destino <span class="pv2-req">*</span></label>
                <div class="input-group afx-search">
                    <input type="hidden" id="txtSidebarResponseItemID" name="txtSidebarResponseItemID" value="">
                    <input class="form-control" readonly id="txtSidebarResponseItemDescription" type="text" autocomplete="off" value="">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id="btnSidebarClearResponseItem">
                            <i aria-hidden="true" class="i-undo-2"></i>
                            Limpiar
                        </button>
                    </span>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="btnSidebarSearchResponseItem">
                            <i aria-hidden="true" class="i-search-5"></i>
                            Buscar
                        </button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarResponseItemTargetWarehouse">Bodega Destino</label>
                <select name="txtSidebarResponseItemTargetWarehouse" id="txtSidebarResponseItemTargetWarehouse" class="select2">
                    <option></option>
                    <?php
                    if ($objListWarehouse)
                        foreach ($objListWarehouse as $ws) {
                            echo "<option value='" . $ws->warehouseID . "' data-name='" . $ws->name . "' selected>" . $ws->name . "</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarResponseItemQuantity">Cantidad <span class="pv2-req">*</span></label>
                <input class="form-control txt-numeric afx-monto pv2-num" type="text" inputmode="decimal" autocomplete="off" name="txtSidebarResponseItemQuantity" id="txtSidebarResponseItemQuantity" value="">
            </div>

            <div class="form-group">
                <label class="pv2-label" for="txtSidebarOutputTotalCost">Monto Total</label>
                <input class="form-control txt-numeric afx-monto pv2-num" readonly type="text" autocomplete="off" name="txtSidebarOutputTotalCost" id="txtSidebarOutputTotalCost" value="">
            </div>

            <div class="pv2-actions">
                <button type="button" id="btnSaveSidebarItemOutput" class="btn btn-primary">Salvar</button>
                <button type="button" id="btnCloseSidebarItemOutput" class="btn btn-default">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- barra inferior del telefono -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Monto total</p>
            <p class="pv2-mbar-total" id="prodMbarTotal">0.00</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
