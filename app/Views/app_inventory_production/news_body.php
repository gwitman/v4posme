<style>
    /* Estilo para ocultar la barra lateral */
    .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        top: 0;
        right: 0;
        background-color: #fff;
        overflow-x: hidden;
        transition: 0.1s;
        padding-top: 60px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    /* Estilo para el contenido de la barra lateral */
    .sidebar-content {
        padding: 20px;
    }
</style>

<div class="row">
    <div id="email" class="col-lg-12">
        <!-- botonera -->
        <div class="email-bar" style="border-left:1px solid #c9c9c9">
            <div class="btn-group pull-right">
                <a href="<?php echo base_url(); ?>/app_inventory_production/index" id="btnBack" class="btn btn-inverse"><i class="icon16 i-rotate"></i> Atras</a>
                <a href="#" class="btn btn-success" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            </div>
        </div>
        <!-- /botonera -->
    </div>
    <!-- End #email  -->
</div>
<!-- End .row-fluid  -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">

            <!-- titulo de comprobante-->
            <div class="panel-heading">
                <div class="icon"><i class="icon20 i-file"></i></div>
                <h4>Orden de Produccion:#<span class="invoice-num">00000000</span></h4>
            </div>
            <!-- /titulo de comprobante-->

            <!-- body -->
            <form id="form-new-production-order" name="form-new-production-order" class="form-horizontal" role="form">
                <div class="panel-body printArea">

                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">General</a></li>
                        <li><a href="#inputs" data-toggle="tab">Insumos</a></li>
                        <li><a href="#results" data-toggle="tab">Resultados</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="home">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="datepicker">Fecha</label>
                                        <div class="col-lg-8">
                                            <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                                <input size="16" class="form-control" type="text" name="txtDate" id="txtDate">
                                                <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Estado</label>
                                        <div class="col-lg-8">
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
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="selectFilter">Moneda</label>
                                        <div class="col-lg-8">
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
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="buttons">Solicitado Por</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtRequestEmployeeID" name="txtRequestEmployeeID" value="">
                                                <input class="form-control" readonly id="txtRequestEmployeeDescription" type="txtRequestEmployeeDescription" value="">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearRequestEmployee">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchRequestEmployee">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="buttons">Elaborado Por</label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="hidden" id="txtSenderEmployeeID" name="txtSenderEmployeeID" value="">
                                                <input class="form-control" readonly id="txtSenderEmployeeDescription" type="txtSenderEmployeeDescription" value="">

                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btnClearSenderEmployee">
                                                        <i aria-hidden="true" class="i-undo-2"></i>
                                                        clear
                                                    </button>
                                                </span>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btnSearchSenderEmployee">
                                                        <i aria-hidden="true" class="i-search-5"></i>
                                                        buscar
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Monto Total</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" readonly type="number" name="txtTransactionTotalAmount" id="txtTransactionTotalAmount" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 1</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference1" id="txtReference1" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 2</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference2" id="txtReference2" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Referencia 3</label>
                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="txtReference3" id="txtReference3" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-4 control-label" for="normal">Comentario</label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control" type="text" name="txtNote" id="txtNote"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="inputs">
                            <div class="row">
                                <div class="col-lg-12">
                                    <br>
                                    <a href="#" class="btn btn-flat btn-info" id="btnNewDetailTransactionItemInput">Agregar</a>
                                    <a href="#" class="btn btn-flat btn-success" id="btnEditDetailTransactionItemInput">Editar</a>
                                    <a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailTransactionItemInput">Eliminar</a>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h3>Detalle:</h3>
                                            <div id="div_transaction_master_detail_item_input">
                                                <table id="tb_transaction_master_detail_item_input" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 40px;"></th>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="results">
                            <div class="row">
                                <div class="col-lg-12">
                                    <br>
                                    <a href="#" class="btn btn-flat btn-info" id="btnNewDetailTransactionItemOutput">Agregar</a>
                                    <a href="#" class="btn btn-flat btn-success" id="btnEditDetailTransactionItemOutput">Editar</a>
                                    <a href="#" class="btn btn-flat btn-danger" id="btnDeleteDetailTransactionItemOutput">Eliminar</a>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h3>Detalle:</h3>
                                            <div id="div_transaction_master_detail_item_output">
                                                <table id="tb_transaction_master_detail_item_output" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 40px;"></th>
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
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /body -->
        </div>
    </div>
</div>

<!-- Tabla de manejo de Insumos -->
<script type="text/template" id="tmpl_row_detail_item_input">
    <tr class="row_razon">
        <td class="td-checkbox">
            <input type="checkbox" style="width: 20px; height:20px; display: block; margin: auto;"  class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" />
            <input type="hidden" name="txtItemInputID[]"                    id="txtItemInputID"  />
            <input type="hidden" name="txtItemInputWarehouseSourceID[]"     id="txtItemInputWarehouseSourceID"  />
            <input type="hidden" name="txtItemInputProductDestinationID[]"  id="txtItemInputProductDestinationID" value="">
        </td>
        <td>
            <span id="txtItemInputCode"></span>
        </td>
        <td>
            <span id="txtItemInputName"></span>
        </td>
        <td>
            <input class="form-control" readonly type="number" name="txtItemInputQuantity[]" id="txtItemInputQuantity" value="">
        </td>
        <td>
            <input class="form-control" readonly type="number" name="txtItemInputUnitaryCost[]" id="txtItemInputUnitaryCost" value="">
        </td>
        <td>
            <input class="form-control txtItemInputTotalCost" readonly type="number" name="txtItemInputTotalCost[]" id="txtItemInputTotalCost" value="">
        </td>
        <td>
            <input class="form-control" readonly type="text" name="txtItemInputWarehouseSource[]" id="txtItemInputWarehouseSource" value="">
        </td>
        <td>
            <input class="form-control" readonly type="text" name="txtItemInputProductDestination[]" id="txtItemInputProductDestination" value="">
        </td>
    </tr>
</script>

<!-- Barra Lateral de manejo de Insumos -->
<div id="mySidebarItemInput" class="sidebar" style="background-color:white">
    <div class="sidebar-content">
        <h2>Insumo</h2>

        <input type="hidden" name="txtSidebarRequestItemUnitaryCost" id="txtSidebarRequestItemUnitaryCost" value="">

        <div class="form-group">
            <label for="buttons">Producto Origen</label>
            <div class="input-group">
                <input type="hidden" id="txtSidebarRequestItemID" name="txtSidebarRequestItemID" value="">
                <input class="form-control" readonly id="txtSidebarRequestItemDescription" type="txtSidebarRequestItemDescription" value="">

                <span class="input-group-btn">
                    <button class="btn btn-danger" type="button" id="btnSidebarClearRequestItem">
                        <i aria-hidden="true" class="i-undo-2"></i>
                        clear
                    </button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" id="btnSidebarSearchRequestItem">
                        <i aria-hidden="true" class="i-search-5"></i>
                        buscar
                    </button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="buttons">Producto Destino</label>
            <div class="input-group">
                <input type="hidden" id="txtSidebarDestinationItemID" name="txtSidebarDestinationItemID" value="">
                <input class="form-control" readonly id="txtSidebarDestinationItemDescription" type="txtSidebarDestinationItemDescription" value="">

                <span class="input-group-btn">
                    <button class="btn btn-danger" type="button" id="btnSidebarClearDestinationItem">
                        <i aria-hidden="true" class="i-undo-2"></i>
                        clear
                    </button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" id="btnSidebarSearchDestinationItem">
                        <i aria-hidden="true" class="i-search-5"></i>
                        buscar
                    </button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="selectFilter">Bodega Origen</label>
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
            <label for="normal">Cantidad</label>
            <input class="form-control" type="number" name="txtSidebarRequestItemQuantity" id="txtSidebarRequestItemQuantity" value="">
        </div>

        <div class="form-group">
            <label for="normal">Monto Total</label>
            <input class="form-control" readonly type="number" name="txtSidebarInputTotalCost" id="txtSidebarInputTotalCost" value="">
        </div>

        <button type="button" id="btnSaveSidebarItemInput" class="btn btn-success">Salvar</button>
        <button type="button" id="btnCloseSidebarItemInput" class="btn btn-danger">Cerrar</button>
    </div>
</div>



<!-- Tabla de manejo de Productos Finales -->
<script type="text/template" id="tmpl_row_detail_item_output">
    <tr class="row_razon">
        <td class="td-checkbox">
            <input type="checkbox" style="width: 20px; height:20px; display: block; margin: auto;"  class="txtOutputCheckedIsActive" name="txtOutputCheckedIsActive[]" value="1" />
            <input type="hidden" name="txtItemOutputID[]"                   id="txtItemOutputID"  />
            <input type="hidden" name="txtItemOutputWarehouseTargetID[]"    id="txtItemOutputWarehouseTargetID"  />
        </td>
        <td>
            <span id="txtItemOutputCode"></span>
        </td>
        <td>
            <span id="txtItemOutputName"></span>
        </td>
        <td>
            <input class="form-control" readonly type="number" name="txtItemOutputQuantity[]" id="txtItemOutputQuantity" value="">
        </td>
        <td>
            <input class="form-control" readonly type="text" name="txtItemOutputUnitaryCost[]" id="txtItemOutputUnitaryCost" value="">
        </td>
        <td>
            <input class="form-control" readonly type="text" name="txtItemOutputTotalCost[]" id="txtItemOutputTotalCost" value="">
        </td>
        <td>
            <input class="form-control" readonly type="text" name="txtItemOutputWarehouseTarget[]" id="txtItemOutputWarehouseTarget" value="">
        </td>
    </tr>
</script>

<!-- Barra Lateral de manejo de Productos Finales -->
<div id="mySidebarItemOutput" class="sidebar" style="background-color:white">
    <div class="sidebar-content">
        <h2>Resultados</h2>

        <input type="hidden" name="txtSidebarResponseItemUnitaryCost" id="txtSidebarResponseItemUnitaryCost" value="">

        <div class="form-group">
            <label for="buttons">Producto Destino</label>
            <div class="input-group">
                <input type="hidden" id="txtSidebarResponseItemID" name="txtSidebarResponseItemID" value="">
                <input class="form-control" readonly id="txtSidebarResponseItemDescription" type="txtSidebarResponseItemDescription" value="">

                <span class="input-group-btn">
                    <button class="btn btn-danger" type="button" id="btnSidebarClearResponseItem">
                        <i aria-hidden="true" class="i-undo-2"></i>
                        clear
                    </button>
                </span>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" id="btnSidebarSearchResponseItem">
                        <i aria-hidden="true" class="i-search-5"></i>
                        buscar
                    </button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <label for="selectFilter">Bodega Destino</label>
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
            <label for="normal">Cantidad</label>
            <input class="form-control" type="number" name="txtSidebarResponseItemQuantity" id="txtSidebarResponseItemQuantity" value="">
        </div>

        <div class="form-group">
            <label for="normal">Monto Total</label>
            <input class="form-control" readonly type="number" name="txtSidebarOutputTotalCost" id="txtSidebarOutputTotalCost" value="">
        </div>

        <button type="button" id="btnSaveSidebarItemOutput" class="btn btn-success">Salvar</button>
        <button type="button" id="btnCloseSidebarItemOutput" class="btn btn-danger">Cerrar</button>
    </div>
</div>