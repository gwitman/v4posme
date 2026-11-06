<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-cart-remove"></i></div>
            <div>
                <p class="pv2-header-title">NUMERO:#<span class="invoice-num"><?php echo $objTM->transactionNumber;?></span> <span class="pv2-chip">Editando</span></p>
                <p class="pv2-header-sub">Registra los productos que se devuelven al proveedor</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_purchase_returnsprovider/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-transaction" name="form-new-transaction" role="form" autocomplete="off">
        <div class="printArea">

            <!--tab menu-->
            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li><a href="#profile" data-toggle="tab">Referencias</a></li>
                <li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
                <li><a id="btnClickArchivo" href="#dropdown-file" data-toggle="tab">Archivos</a></li>
            </ul>
            <!--tab menu-->

            <!--tab content-->
            <div class="tab-content">
                <!--tab content general-->
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Datos del documento</p>
                                <input type="hidden" name="txtCompanyID" value="<?php echo $objTM->companyID; ?>">
                                <input type="hidden" name="txtTransactionID" value="<?php echo $objTM->transactionID; ?>">
                                <input type="hidden" name="txtTransactionMasterID" value="<?php echo $objTM->transactionMasterID; ?>">

                                <div class="form-group">
                                    <label class="pv2-label" for="txtTransactionOn">Fecha <span class="pv2-req">*</span></label>
                                    <div id="datepicker" class="input-group date" data-date="2014-01-30" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtTransactionOn" id="txtTransactionOn" value="<?php echo $objTM->transactionOn;?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtProviderDescription">Proveedor <span class="pv2-req">*</span></label>
                                    <div class="input-group afx-search">
                                        <input type="hidden" id="txtProviderID" name="txtProviderID" value="<?php echo $objProvider->entityID; ?>">
                                        <input class="form-control" readonly id="txtProviderDescription" type="text" autocomplete="off" value="<?php echo $objProvider->providerNumber."/".$objLegalDefault->comercialName ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btnClearProvider">
                                                <i aria-hidden="true" class="i-undo-2"></i>
                                                Limpiar
                                            </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="button" id="btnSearchProvider">
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
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-checkmark-4"></i></span> Estado y bodega</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtStatusID">Estado <span class="pv2-req">*</span></label>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        if($objListWorkflowStage)
                                        foreach($objListWorkflowStage as $ws){
                                            if($ws->workflowStageID == $objTM->statusID)
                                            echo "<option value='".$ws->workflowStageID."' selected >".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtWarehouseID">Bodega</label>
                                    <select name="txtWarehouseID" id="txtWarehouseID" class="select2">
                                        <option></option>
                                        <?php
                                        if($objListWarehouse)
                                        foreach($objListWarehouse as $ws){

                                            if($ws->warehouseID == $objTM->sourceWarehouseID)
                                                echo "<option value='".$ws->warehouseID."' selected>".$ws->name."</option>";
                                            else
                                                echo "<option value='".$ws->warehouseID."' >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--tab content general-->
                <div class="tab-pane fade" id="profile">
                    <div class="pv2-card">
                        <div class="form-group">
                            <label class="pv2-label" for="txtReference1">Referencia1</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference1" id="txtReference1" value="<?php echo $objTM->reference1; ?>">
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReference2">Referencia2</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference2" id="txtReference2" value="<?php echo $objTM->reference2; ?>">
                        </div>
                        <div class="form-group">
                            <label class="pv2-label" for="txtReference3">Referencia3</label>
                            <input class="form-control" type="text" autocomplete="off" name="txtReference3" id="txtReference3" value="<?php echo $objTM->reference3; ?>">
                        </div>
                    </div>
                </div>
                <!--tab content description-->
                <div class="tab-pane fade" id="dropdown">
                    <div class="pv2-card">
                        <div class="form-group">
                            <label class="pv2-label" for="txtDescription">Nota</label>
                            <textarea class="form-control" id="txtDescription" name="txtDescription" rows="6"><?php echo $objTM->note; ?></textarea>
                        </div>
                    </div>
                </div>
                <!--tab content description-->
                <!--tab content file-->
                <div class="tab-pane fade" id="dropdown-file">
                </div>
                <!--tab content file-->
            </div>
            <!--tab content-->

            <!-- detalle del movimiento-->
            <div class="pv2-card">
                <div class="pv2-detail-head">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Detalle <span class="pv2-count" id="pv2DetailCount">0</span></p>
                    <div class="pv2-actions">
                        <button type="button" class="btn btn-default btn-sm" id="btnDeleteDetailTransaction"><i class="icon16 i-remove"></i> Eliminar</button>
                        <button type="button" class="btn btn-primary btn-sm" id="btnNewDetailTransaction"><i class="icon16 i-plus"></i> Agregar</button>
                    </div>
                </div>
                <div id="div_transaction_master_detail" class="pv2-table-wrap">
                    <table id="tb_transaction_master_detail" class="pv2-table">
                        <thead>
                            <tr>
                                <th class="afx-col-quitar"></th>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>U/M</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="body_detail_transaction">
                            <?php
                            if($objTMD)
                            foreach($objTMD as $i){
                                ?>
                                <tr class="row_razon">
                                    <td class="td-checkbox" data-label="Quitar">
                                        <input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" >
                                        <input type="hidden" name="txtDetailItemID[]" id="txtDetailItemID" value="<?php echo $i->componentItemID; ?>" class="classDetailItem" />
                                        <input type="hidden" name="txtDetailTransactionDetailID[]" value="<?php echo $i->transactionMasterDetailID; ?>"/>
                                    </td>
                                    <td data-label="Codigo"><text id="txtCodigo"><?php echo $i->itemNumber; ?></text></td>
                                    <td data-label="Nombre"><text id="txtNombre"><?php echo $i->itemName; ?></text></td>
                                    <td data-label="U/M"><text id="txtUM"><?php echo $i->unitMeasureName; ?></text></td>
                                    <td data-label="Cantidad">
                                        <input class="form-control pv2-num txt-numeric afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtDetailQuantity[]" value="<?php echo sprintf("%01.4f", $i->quantity); ?>">
                                    </td>
                                    <!--
                                    <td>

                                                <input class="form-control"  type="text"  name="txtDetailLote[]"  value="<?php echo $i->reference1; ?>">

                                    </td>
                                    <td>

                                                <input class="form-control"  type="text"  name="txtDetailVencimiento[]"  value="<?php echo $i->reference2; ?>">

                                    </td>
                                    <td>

                                                <select name="txtDetailTipo[]" id="txtDetailTipoID"   class="select2 select2statusID">
                                                        <option></option>
                                                        <?php
                                                        if($objCatalogItemRazon)
                                                        foreach($objCatalogItemRazon as $cix){
                                                            if( $cix->catalogItemID == $i->catalogStatusID )
                                                                echo "<option value='".$cix->catalogItemID."' selected >".$cix->display."</option>";
                                                            else
                                                                echo "<option value='".$cix->catalogItemID."'>".$cix->display."</option>";
                                                        }
                                                        ?>
                                                </select>

                                    </td>
                                    -->
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="pv2-empty" id="pv2DetailEmpty">
                    <i class="icon20 i-file-plus"></i>
                    Este documento no tiene productos. Toca <b>Agregar</b> para empezar.
                </div>
            </div>
            <!-- detalle del movimiento-->

        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Editar devolución de compra</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>

<!-- razones del mal estado-->
<script type="text/template" id="tmpl_row_razon">
    <tr class="row_razon">
        <td class="td-checkbox" data-label="Quitar">
            <input type="checkbox" class="txtCheckedIsActive" name="txtCheckedIsActive[]" value="1" >
            <input type="hidden" name="txtDetailItemID[]" id="txtDetailItemID" class="classDetailItem" />
            <input type="hidden" name="txtDetailTransactionDetailID[]" />
        </td>
        <td data-label="Codigo"><text id="txtCodigo"></text></td>
        <td data-label="Nombre"><text id="txtNombre"></text></td>
        <td data-label="U/M"><text id="txtUM"></text></td>
        <td data-label="Cantidad">
            <input class="form-control pv2-num txt-numeric afx-monto" type="text" inputmode="decimal" autocomplete="off" name="txtDetailQuantity[]" value="">
        </td>
        <!--
        <td>

                        <input class="form-control"  type="text"  name="txtDetailLote[]"  value="">

        </td>
        <td>

                        <input class="form-control"  type="text"  name="txtDetailVencimiento[]"  value="">

        </td>
        <td>

                    <select name="txtDetailTipo[]" id="txtDetailTipoID"   class="select2 select2statusID">
                            <option></option>
                            <?php
                            if($objCatalogItemRazon)
                            foreach($objCatalogItemRazon as $i){
                                echo "<option value='".$i->catalogItemID."'>".$i->display."</option>";
                            }
                            ?>
                    </select>

        </td>
        -->
    </tr>
</script>
