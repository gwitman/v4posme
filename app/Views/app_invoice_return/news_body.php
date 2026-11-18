<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-cart-remove"></i></div>
            <div>
                <p class="pv2-header-title">NUMERO:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nueva</span></p>
                <p class="pv2-header-sub">El cliente devuelve productos facturados y recibe una Nota de Crédito</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_invoice_return/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- aviso -->
    <div class="ret-aviso">
        <i class="icon16 i-info"></i>
        <div>
            <p>Al aplicar la devolución se generará una Nota de Crédito aplicada al cliente y una Otra Entrada de inventario. La factura no se modifica.</p>
            <p>Plazo máximo: <b class="ret-max-days"><?php echo (int) $maxDays; ?></b> días desde la fecha de la factura.</p>
        </div>
    </div>

    <!-- formulario -->
    <form id="form-new-transaction" name="form-new-transaction" role="form" autocomplete="off" onsubmit="return retSubmitting;">

        <!--tab menu-->
        <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
            <li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
        </ul>

        <!--tab content-->
        <div class="tab-content">
            <div class="tab-pane fade in active" id="home">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="pv2-card is-required" id="retInvoiceCard">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Factura</p>

                            <div class="form-group">
                                <label class="pv2-label" for="txtInvoiceNumber">Factura <span class="pv2-req">*</span></label>
                                <div class="input-group afx-search">
                                    <input type="hidden" id="txtInvoiceTransactionMasterID" name="txtInvoiceTransactionMasterID" value="">
                                    <input class="form-control" readonly id="txtInvoiceNumber" name="txtInvoiceNumber" type="text" autocomplete="off" value="" placeholder="Seleccione la factura">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" id="btnClearInvoice">
                                            <i aria-hidden="true" class="i-undo-2"></i>
                                            Limpiar
                                        </button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" id="btnSearchInvoice">
                                            <i aria-hidden="true" class="i-search-5"></i>
                                            Examinar
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="pv2-label" for="txtCustomerDescription">Cliente</label>
                                <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="">
                                <span class="pv2-help">Se toma de la factura.</span>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="pv2-label" for="txtInvoiceDate">Fecha de la factura</label>
                                        <input class="form-control" readonly id="txtInvoiceDate" type="text" autocomplete="off" value="">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="pv2-label" for="txtInvoiceWarehouse">Bodega</label>
                                        <input class="form-control" readonly id="txtInvoiceWarehouse" type="text" autocomplete="off" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="pv2-label" for="txtInvoiceCurrency">Moneda</label>
                                <input class="form-control" readonly id="txtInvoiceCurrency" type="text" autocomplete="off" value="">
                                <span class="pv2-help">La Nota de Crédito se genera en la moneda de la factura.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="pv2-card">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-checkmark-4"></i></span> Datos del documento</p>

                            <div class="form-group">
                                <label class="pv2-label" for="txtTransactionOn">Fecha <span class="pv2-req">*</span></label>
                                <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                    <input size="16" class="form-control" type="text" autocomplete="off" name="txtTransactionOn" id="txtTransactionOn" value="">
                                    <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="pv2-label" for="txtStatusID">Estado <span class="pv2-req">*</span></label>
                                <select name="txtStatusID" id="txtStatusID" class="select2">
                                    <?php
                                    $isFirstStage = true;
                                    if ($objListWorkflowStage)
                                        foreach ($objListWorkflowStage as $ws) {
                                            echo "<option value='" . esc($ws->workflowStageID, 'attr') . "'" . ($isFirstStage ? " selected" : "") . ">" . esc($ws->name) . "</option>";
                                            $isFirstStage = false;
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="dropdown">
                <div class="pv2-card">
                    <div class="form-group">
                        <label class="pv2-label" for="txtNote">Comentario</label>
                        <textarea class="form-control" id="txtNote" name="txtNote" rows="6" maxlength="3500"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!--tab content-->

        <!-- detalle -->
        <div class="pv2-card">
            <div class="pv2-detail-head">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Productos a devolver <span class="pv2-count" id="afxDetailCount">0</span></p>
                <div class="pv2-actions">
                    <button type="button" class="btn btn-default btn-sm" id="btnDeleteDetailTransaction"><i class="icon16 i-remove"></i> Quitar</button>
                    <button type="button" class="btn btn-primary btn-sm" id="btnNewDetailTransaction"><i class="icon16 i-plus"></i> Agregar</button>
                </div>
            </div>
            <div id="div_transaction_master_detail" class="pv2-table-wrap">
                <table id="tb_transaction_master_detail" class="pv2-table">
                    <thead>
                        <tr>
                            <th class="afx-col-quitar">Quitar</th>
                            <th class="ret-col-line">Producto facturado</th>
                            <th class="ret-col-num">Facturado</th>
                            <th class="ret-col-num">Ya devuelto</th>
                            <th class="ret-col-num">Disponible</th>
                            <th class="ret-col-qty">Cantidad</th>
                            <th class="ret-col-num">Precio</th>
                            <th class="ret-col-num">Subtotal</th>
                            <th class="ret-col-um">U/M</th>
                        </tr>
                    </thead>
                    <tbody id="body_tb_transaction_master_detail">
                    </tbody>
                </table>
            </div>
            <div class="pv2-empty" id="afxDetailEmpty">
                <i class="icon20 i-file-plus"></i>
                Seleccione la factura y toque <b>Agregar</b> para indicar los productos a devolver.
            </div>
            <div class="ret-total">
                <span class="ret-total-label">Total estimado de la Nota de Crédito</span>
                <span class="ret-total-value ret-total-nc" id="retTotalNC">0.00</span>
            </div>
        </div>
        <!-- /detalle -->

    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nota de Crédito estimada</p>
            <p class="pv2-mbar-total ret-total-nc" id="retTotalNCMobile">0.00</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
