<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">PEDIDO:#<span class="invoice-num">00000000</span> <span class="pv2-chip">Nuevo</span></p>
                <p class="pv2-header-sub">Registra el pedido, su cliente y su precio</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_purchase_pedidos/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-invoice" name="form-new-invoice" role="form" enctype="multipart/form-data" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li><a href="#profile" data-toggle="tab">Referencias</a></li>
                <li><a href="#dropdown" data-toggle="tab">Comentario</a></li>
                <li><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-calendar-4"></i></span> Documento</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate">Fecha pedido</label>
                                    <div>
                                        <div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">
                                            <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate" id="txtDate">
                                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDate2">Fecha bodega</label>
                                    <div>
                                        <div class="input-group date" data-date-format="yyyy-mm-dd">
                                            <input size="16" class="form-control" type="text" autocomplete="off" name="txtDate2" id="txtDate2">
                                            <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtBranchID">Sucursal</label>
                                    <div>
                                        <select name="txtBranchID" id="txtBranchID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListBranch)
                                            foreach($objListBranch as $ws){
                                                if($counter == 0 )
                                                    echo "<option value='".$ws->branchID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->branchID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group hidden">
                                    <label class="pv2-label" for="txtIsApplied">Aplicado</label>
                                    <div>
                                        <input type="checkbox" disabled name="txtIsApplied" id="txtIsApplied" value="1">
                                    </div>
                                </div>
                                <div class="form-group hidden">
                                    <label class="pv2-label" for="txtExchangeRate">Cambio</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" disabled="disabled" name="txtExchangeRate" id="txtExchangeRate" value="<?php echo $exchangeRate; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtZoneID">Status de pedido</label>
                                    <div>
                                        <select name="txtZoneID" id="txtZoneID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListPedidoStatus)
                                            foreach($objListPedidoStatus as $ws){
                                                if($counter == 0)
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Factura y personas</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtNoteDescription">Factura</label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtNote" name="txtNote" value="">
                                            <input class="form-control" readonly id="txtNoteDescription" type="text" autocomplete="off" value="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearNote">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchNote">
                                                    <i aria-hidden="true" class="i-search-5"></i>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCustomerDescription">Cliente <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtCustomerID" name="txtCustomerID" value="">
                                            <input class="form-control" readonly id="txtCustomerDescription" type="text" autocomplete="off" value="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearCustomer">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchCustomer">
                                                    <i aria-hidden="true" class="i-search-5"></i>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtEmployerDescription">Tecnico <span class="pv2-req">*</span></label>
                                    <div>
                                        <div class="input-group afx-search">
                                            <input type="hidden" id="txtEmployerID" name="txtEmployerID" value="">
                                            <input class="form-control" readonly id="txtEmployerDescription" type="text" autocomplete="off" value="">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="btnClearEmployer">
                                                    <i aria-hidden="true" class="i-undo-2"></i>
                                                    Limpiar
                                                </button>
                                            </span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="button" id="btnSearchEmployer">
                                                    <i aria-hidden="true" class="i-search-5"></i>
                                                    Buscar
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Producto</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference1">Informacion adicional</label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference1" id="txtDetailReference1"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference2">Marca</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtDetailReference2" id="txtDetailReference2" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference4">Modelo</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtDetailReference4" id="txtDetailReference4" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailReference3">Numero de parte</label>
                                    <div>
                                        <textarea class="form-control" name="txtDetailReference3" id="txtDetailReference3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Precio y envio</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtCurrencyID">Moneda</label>
                                    <div>
                                        <select name="txtCurrencyID" id="txtCurrencyID" class="select2">
                                            <?php
                                            $count = 0;
                                            if($objListCurrency)
                                            foreach($objListCurrency as $ws){
                                                if($count == 0)
                                                echo "<option value='".$ws->currencyID."' selected>".$ws->simb."</option>";
                                                else
                                                echo "<option value='".$ws->currencyID."' >".$ws->simb."</option>";
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtDetailAmount">Precio de venta <span class="pv2-req">*</span></label>
                                    <div>
                                        <input type="hidden" name="txtDetailTransactionDetailID" value="0">
                                        <input class="form-control afx-monto pv2-num" inputmode="decimal" type="text" autocomplete="off" name="txtDetailAmount" id="txtDetailAmount" value="0">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtAreaID">Pieza</label>
                                    <div>
                                        <select name="txtAreaID" id="txtAreaID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListPiezas)
                                            foreach($objListPiezas as $ws){
                                                if($counter == 0)
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtTMInfoDetailReferenceClientName">Otros</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtTMInfoDetailReferenceClientName" id="txtTMInfoDetailReferenceClientName" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtPriorityID">Envio</label>
                                    <div>
                                        <select name="txtPriorityID" id="txtPriorityID" class="select2">
                                            <?php
                                            $counter = 0;
                                            if($objListEnvios)
                                            foreach($objListEnvios as $ws){
                                                if($counter == 0)
                                                    echo "<option value='".$ws->catalogItemID."' selected >".$ws->name."</option>";
                                                else
                                                    echo "<option value='".$ws->catalogItemID."' >".$ws->name."</option>";

                                                $counter++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group <?php echo getBehavio($company->type,"app_purchase_pedidos","divDllEstado",""); ?> ">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <div>
                                        <select name="txtStatusID" id="txtStatusID" class="select2">
                                            <option></option>
                                            <?php
                                            if($objListWorkflowStage)
                                            foreach($objListWorkflowStage as $ws){
                                                echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtTMInfoDetailReference1">Tracking</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtTMInfoDetailReference1" id="txtTMInfoDetailReference1" value="">
                                    </div>
                                </div>

                                <!-- divScriptCustom oculta este campo con parent().parent(): el input debe seguir dentro de un div dentro de .form-group -->
                                <div class="form-group">
                                    <label class="pv2-label" for="txtTMInfoDetailReference2">Precio de compra</label>
                                    <div>
                                        <input class="form-control" type="text" autocomplete="off" name="txtTMInfoDetailReference2" id="txtTMInfoDetailReference2" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile">
                    <div class="row">
                        <div class="col-lg-6 currency-1"></div>
                        <div class="col-lg-6 currency-2"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown"></div>

                <div class="tab-pane fade" id="dropdown-file">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Archivos</p>
                        <div class="pv2-table-wrap">
                            <table class="pv2-table">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Archivo</th>
                                    </tr>
                                </thead>
                                <tbody id="body_detail_file">
                                    <?php
                                    if($objListArchivos)
                                    foreach($objListArchivos as $ws)
                                    {
                                        ?>
                                        <tr class="row_razon">
                                            <td data-label="Tipo">
                                                <input type="hidden" name="txtFileID[]" value="0">
                                                <input type="hidden" name="txtFileTypeID[]" value="<?php echo $ws->catalogItemID; ?>">
                                                <span class="badge badge-inverse"><?php echo $ws->name; ?></span>
                                            </td>
                                            <td class="afx-col-block" data-label="Archivo">
                                                <input type="file" name="txtFileDocument[]">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Nuevo pedido</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>

<?php echo getBehavio($company->type,"app_purchase_pedidos","divScriptCustom",""); ?>
