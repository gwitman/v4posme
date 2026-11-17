<?php
/*
 * Sustitucion de productos facturados - edicion / consulta.
 * Aplicado: todo solo lectura. Aplicacion interrumpida (ya genero Otra Entrada u
 * Otra Salida sin quedar aplicado): solo se puede cambiar el estado para terminar.
 */
$isApplied   = isset($isApplied) && $isApplied;
$isPartial   = !$isApplied && (trim((string) $objTM->reference3) != "" || trim((string) $objTM->reference4) != "");
$isReadOnly  = $isApplied || $isPartial;
$isMobileCls = (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : "";

//Datos de la factura (si ya no existe se muestran los guardados en el documento)
$invNumber    = $objInvoice ? $objInvoice["transactionNumber"] : $objTM->reference2;
$invTMID      = $objInvoice ? $objInvoice["transactionMasterID"] : $objTM->reference1;
$invDate      = $objInvoice ? $objInvoice["transactionOn"] : "";
$invWarehouse = $objInvoice ? $objInvoice["warehouseName"] : "";
$custNumber   = $objInvoice ? $objInvoice["customerNumber"] : ($objCustomer ? $objCustomer->customerNumber : "");
$custName     = $objInvoice ? $objInvoice["customerName"] : ($objCustomer ? $objCustomer->firstName : "");

/**
 * Une dos textos con un separador omitiendo los vacios ("codigo / nombre").
 * @param mixed  $a
 * @param mixed  $b
 * @param string $separator
 * @return string
 */
$joinText = function ($a, $b, $separator) {
    $a = trim((string) $a);
    $b = trim((string) $b);
    return ($a != "" && $b != "") ? $a . $separator . $b : $a . $b;
};
$custText     = $joinText($custNumber, $custName, " / ");

/**
 * Cantidad para mostrar: 1,000.00; conserva los decimales significativos si son mas de 2.
 * @param mixed $value
 * @return string
 */
$fmtQty = function ($value) {
    $number   = (float) $value;
    $text     = rtrim(rtrim(sprintf("%.6F", $number), "0"), ".");
    $pos      = strpos($text, ".");
    $decimals = $pos === false ? 2 : max(2, strlen($text) - $pos - 1);
    return number_format($number, $decimals, ".", ",");
};

/**
 * URL de edicion de una transaccion generada.
 * @param string $controller
 * @param object $obj
 * @return string
 */
$urlLinked = function ($controller, $obj) {
    return base_url() . "/" . $controller . "/edit/companyID/" . $obj->companyID . "/transactionID/" . $obj->transactionID . "/transactionMasterID/" . $obj->transactionMasterID;
};
?>
<div class="pv2<?php echo $isApplied ? "" : " pv2-has-mbar"; ?><?php echo $isMobileCls; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-cart-remove"></i></div>
            <div>
                <p class="pv2-header-title">NUMERO:#<span class="invoice-num"><?php echo esc($objTM->transactionNumber); ?></span>
                    <?php if ($isApplied) { ?>
                        <span class="pv2-chip is-applied">Aplicado</span>
                    <?php } else if ($isPartial) { ?>
                        <span class="pv2-chip">Aplicación pendiente</span>
                    <?php } else { ?>
                        <span class="pv2-chip">Editando</span>
                    <?php } ?>
                </p>
                <p class="pv2-header-sub">El cliente devuelve un producto facturado y se lleva otro</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_invoice_return/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <?php if (!$isReadOnly) { ?>
                <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <?php } ?>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <?php if (!$isApplied) { ?>
                <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
            <?php } ?>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- avisos -->
    <div class="ret-aviso">
        <i class="icon16 i-info"></i>
        <div>
            <p>La sustitución solo mueve inventario; no modifica la factura ni cobra diferencias de precio.</p>
            <p>Plazo máximo: <b class="ret-max-days"><?php echo (int) $maxDays; ?></b> días desde la fecha de la factura.</p>
        </div>
    </div>
    <?php if ($isPartial) { ?>
        <div class="ret-aviso is-warn">
            <i class="icon16 i-warning"></i>
            <div>
                <p>Este documento ya generó movimientos de inventario pero no terminó de aplicarse. Solo puede cambiar el estado a uno aplicado para terminar la sustitución.</p>
            </div>
        </div>
    <?php } ?>

    <!-- formulario -->
    <form id="form-new-transaction" name="form-new-transaction" role="form" autocomplete="off" onsubmit="return retSubmitting;">
        <input type="hidden" name="txtCompanyID" value="<?php echo esc($objTM->companyID, 'attr'); ?>">
        <input type="hidden" name="txtTransactionID" value="<?php echo esc($objTM->transactionID, 'attr'); ?>">
        <input type="hidden" name="txtTransactionMasterID" value="<?php echo esc($objTM->transactionMasterID, 'attr'); ?>">

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
                        <div class="pv2-card<?php echo $isReadOnly ? "" : " is-required"; ?>" id="retInvoiceCard">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Factura</p>

                            <div class="form-group">
                                <label class="pv2-label" for="txtInvoiceNumber">Factura <span class="pv2-req">*</span></label>
                                <input type="hidden" id="txtInvoiceTransactionMasterID" name="txtInvoiceTransactionMasterID" value="<?php echo esc($invTMID, 'attr'); ?>">
                                <?php if ($isReadOnly) { ?>
                                    <input class="form-control" readonly id="txtInvoiceNumber" name="txtInvoiceNumber" type="text" autocomplete="off" value="<?php echo esc($invNumber, 'attr'); ?>">
                                <?php } else { ?>
                                    <div class="input-group afx-search">
                                        <input class="form-control" readonly id="txtInvoiceNumber" name="txtInvoiceNumber" type="text" autocomplete="off" value="<?php echo esc($invNumber, 'attr'); ?>" placeholder="Seleccione la factura">
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
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label class="pv2-label" for="txtCustomerDescription">Cliente</label>
                                <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="<?php echo esc($custText, 'attr'); ?>">
                                <span class="pv2-help">Se toma de la factura.</span>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="pv2-label" for="txtInvoiceDate">Fecha de la factura</label>
                                        <input class="form-control" readonly id="txtInvoiceDate" type="text" autocomplete="off" value="<?php echo esc($invDate, 'attr'); ?>">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="pv2-label" for="txtInvoiceWarehouse">Bodega</label>
                                        <input class="form-control" readonly id="txtInvoiceWarehouse" type="text" autocomplete="off" value="<?php echo esc($invWarehouse, 'attr'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="pv2-card">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-checkmark-4"></i></span> Datos del documento</p>

                            <div class="form-group">
                                <label class="pv2-label" for="txtTransactionOn">Fecha <span class="pv2-req">*</span></label>
                                <?php if ($isReadOnly) { ?>
                                    <input class="form-control" readonly type="text" autocomplete="off" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo esc($objTM->transactionOn, 'attr'); ?>">
                                <?php } else { ?>
                                    <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo esc($objTM->transactionOn, 'attr'); ?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label class="pv2-label" for="txtStatusID">Estado <span class="pv2-req">*</span></label>
                                <?php if ($isApplied) { ?>
                                    <input class="form-control" readonly id="txtStatusID" type="text" autocomplete="off" value="<?php echo esc(isset($objTM->workflowStageName) ? $objTM->workflowStageName : "", 'attr'); ?>">
                                <?php } else { ?>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <?php
                                        if ($objListWorkflowStage)
                                            foreach ($objListWorkflowStage as $ws) {
                                                $isSelected = ((string) $ws->workflowStageID == (string) $objTM->statusID) ? " selected" : "";
                                                echo "<option value='" . esc($ws->workflowStageID, 'attr') . "'" . $isSelected . ">" . esc($ws->name) . "</option>";
                                            }
                                        ?>
                                    </select>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($isReadOnly) { ?>
                    <!-- documentos generados -->
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Documentos generados</p>
                        <div class="ret-docs-item">
                            <span>Otra entrada (productos devueltos)</span>
                            <?php if ($objOtherInput) { ?>
                                <a href="<?php echo $urlLinked("app_inventory_otherinput", $objOtherInput); ?>" target="_blank" rel="noopener"><?php echo esc($objOtherInput->transactionNumber); ?></a>
                            <?php } else { ?>
                                <span>—</span>
                            <?php } ?>
                        </div>
                        <div class="ret-docs-item">
                            <span>Otra salida (productos sustitutos)</span>
                            <?php if ($objOtherOutput) { ?>
                                <a href="<?php echo $urlLinked("app_inventory_otheroutput", $objOtherOutput); ?>" target="_blank" rel="noopener"><?php echo esc($objOtherOutput->transactionNumber); ?></a>
                            <?php } else { ?>
                                <span>—</span>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="tab-pane fade" id="dropdown">
                <div class="pv2-card">
                    <div class="form-group">
                        <label class="pv2-label" for="txtNote">Comentario</label>
                        <textarea class="form-control" id="txtNote" name="txtNote" rows="6" maxlength="3500"<?php echo $isReadOnly ? " readonly" : ""; ?>><?php echo esc($objTM->note); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!--tab content-->

        <!-- detalle -->
        <div class="pv2-card">
            <div class="pv2-detail-head">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Productos a sustituir <span class="pv2-count" id="afxDetailCount">0</span></p>
                <?php if (!$isReadOnly) { ?>
                    <div class="pv2-actions">
                        <button type="button" class="btn btn-default btn-sm" id="btnDeleteDetailTransaction"><i class="icon16 i-remove"></i> Quitar</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btnNewDetailTransaction"><i class="icon16 i-plus"></i> Agregar</button>
                    </div>
                <?php } ?>
            </div>
            <div id="div_transaction_master_detail" class="pv2-table-wrap">
                <table id="tb_transaction_master_detail" class="pv2-table">
                    <thead>
                        <tr>
                            <?php if (!$isReadOnly) { ?>
                                <th class="afx-col-quitar">Quitar</th>
                            <?php } ?>
                            <th class="ret-col-line">Producto facturado</th>
                            <th class="ret-col-num">Facturado</th>
                            <th class="ret-col-num">Disponible</th>
                            <th class="ret-col-qty">Cantidad</th>
                            <th>Producto sustituto</th>
                            <th class="ret-col-um">U/M</th>
                        </tr>
                    </thead>
                    <tbody id="body_tb_transaction_master_detail">
                        <?php
                        //Solo lectura: se pinta aqui. Editable: lo arma el script con retAddRow
                        if ($isReadOnly && $objTMD)
                            foreach ($objTMD as $row) {
                                $lineText = $joinText($row->itemNumber, $row->itemName, " - ");
                                $sustText = $joinText($row->itemNumberSustituto, $row->itemNameSustituto, " - ");
                                ?>
                                <tr class="row_razon">
                                    <td data-label="Producto facturado"><span><?php echo esc($lineText); ?></span></td>
                                    <td data-label="Facturado" class="pv2-num"><span><?php echo $fmtQty($row->quantityInvoiced); ?></span></td>
                                    <td data-label="Disponible" class="pv2-num"><span><?php echo $fmtQty($row->quantityAvailable); ?></span></td>
                                    <td data-label="Cantidad" class="pv2-num"><span><?php echo $fmtQty($row->quantity); ?></span></td>
                                    <td data-label="Producto sustituto"><span><?php echo esc($sustText); ?></span></td>
                                    <td data-label="U/M"><span><?php echo esc($row->unitMeasureName); ?></span></td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="pv2-empty" id="afxDetailEmpty">
                <i class="icon20 i-file-plus"></i>
                <?php echo $isReadOnly ? "Este documento no tiene productos." : "Seleccione la factura y toque <b>Agregar</b> para indicar los productos a sustituir."; ?>
            </div>
        </div>
        <!-- /detalle -->

    </form>
    <!-- /formulario -->

    <?php if (!$isApplied) { ?>
        <!-- barra inferior (telefono) -->
        <div class="pv2-mbar">
            <div class="pv2-mbar-info">
                <p class="pv2-mbar-label">Editar sustitución de productos</p>
            </div>
            <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    <?php } ?>
</div>
