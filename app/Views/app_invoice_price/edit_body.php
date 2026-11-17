<div class="pv2 pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-coins"></i></div>
            <div>
                <p class="pv2-header-title">CODIGO:#<span class="invoice-num">00000000</span></p>
                <p class="pv2-header-sub">Revisa o actualiza la lista de precio</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php echo base_url(); ?>/app_invoice_price/index" id="btnBack" class="btn btn-default"><i class="icon16 i-rotate"></i> Atras</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-default" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnAcept"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- formulario -->
    <form id="form-new-invoice-price" name="form-new-invoice-price" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Mas <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#dropdown" data-toggle="tab">Nota</a></li>
                        <li id="btnClickArchivo"><a href="#dropdown-file" data-toggle="tab">Archivos</a></li>
                    </ul>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <input type="hidden" name="txtCompanyID" value="<?php echo $objListPrice->companyID; ?>">
                    <input type="hidden" name="txtListPriceID" value="<?php echo $objListPrice->listPriceID; ?>">

                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Datos de la lista</p>

                                <div class="form-group">
                                    <label class="pv2-label" for="txtStartOn">Fecha Inicial <span class="pv2-req">*</span></label>
                                    <div class="input-group date" data-date="<?php echo $objListPrice->startOn; ?>" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtStartOn" id="txtStartOn" value="<?php echo $objListPrice->startOn; ?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtEndOn">Fecha Final <span class="pv2-req">*</span></label>
                                    <div class="input-group date" data-date="<?php echo $objListPrice->endOn; ?>" data-date-format="yyyy-mm-dd">
                                        <input size="16" class="form-control" type="text" autocomplete="off" name="txtEndOn" id="txtEndOn" value="<?php echo $objListPrice->endOn; ?>">
                                        <span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtName">Nombre</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtName" id="txtName" value="<?php echo $objListPrice->name; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtStatusID">Estado</label>
                                    <select name="txtStatusID" id="txtStatusID" class="select2">
                                        <option></option>
                                        <?php
                                        if($objListWorkflowStage)
                                        foreach($objListWorkflowStage as $ws){
                                            if ($ws->workflowStageID == $objListPrice->statusID)
                                            echo "<option value='".$ws->workflowStageID."' selected>".$ws->name."</option>";
                                            else
                                            echo "<option value='".$ws->workflowStageID."' >".$ws->name."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Archivo de precios</p>

                                <p class="pv2-help">
                                    1. Genera el archivo (se descarga solo).<br>
                                    2. Por cada producto escribe el precio en <b>valor</b> o en <b>%</b> sobre el costo.<br>
                                    3. Subelo con <b>Subir</b>, seleccionalo y presiona <b>Procesar</b>.
                                </p>
                                <div class="form-group">
                                    <a href="#" class="btn btn-default btn-block" id="btnGenerateFile"><i class="icon16 i-download-2"></i> Generar y descargar archivo</a>
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="cmbFilePrice">Archivo para actualizar precios</label>
                                    <div class="price-file-row">
                                        <!-- sin name: el archivo se procesa por AJAX, no con Guardar -->
                                        <select class="form-control" id="cmbFilePrice" autocomplete="off">
                                            <option value="">Cargando archivos...</option>
                                        </select>
                                        <a href="#" class="btn btn-default" id="btnRefreshFile" title="Refrescar lista de archivos"><i class="icon16 i-loop-4"></i></a>
                                        <a href="#" class="btn btn-default" id="btnDownloadFile" title="Descargar archivo seleccionado"><i class="icon16 i-download-2"></i></a>
                                        <a href="#" class="btn btn-default" id="btnUploadFile" title="Subir archivo"><i class="icon16 i-upload"></i></a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="#" class="btn btn-primary btn-block" id="btnProcessFile"><i class="icon16 i-checkmark-4"></i> Procesar archivo</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- resumen del procesamiento -->
                    <div class="pv2-card" id="priceResumen" style="display:none;">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-coins"></i></span> Resumen del procesamiento <span class="pv2-count" id="priceResumenArchivo"></span></p>

                        <div class="pv2-kpis">
                            <div class="pv2-kpi k-info">
                                <div class="pv2-kpi-icon"><i class="icon16 i-file"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Productos leidos</span><span class="pv2-kpi-value" id="kpiFilas">0</span></div>
                            </div>
                            <div class="pv2-kpi k-ok">
                                <div class="pv2-kpi-icon"><i class="icon16 i-checkmark-4"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Productos actualizados</span><span class="pv2-kpi-value" id="kpiActualizados">0</span></div>
                            </div>
                            <div class="pv2-kpi k-purple">
                                <div class="pv2-kpi-icon"><i class="icon16 i-coins"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Precios cambiados</span><span class="pv2-kpi-value" id="kpiPrecios">0</span></div>
                            </div>
                            <div class="pv2-kpi">
                                <div class="pv2-kpi-icon"><i class="icon16 i-file"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Sin cambio</span><span class="pv2-kpi-value" id="kpiSinCambio">0</span></div>
                            </div>
                            <div class="pv2-kpi k-warn" id="kpiErroresBox">
                                <div class="pv2-kpi-icon"><i class="icon16 i-remove"></i></div>
                                <div class="pv2-kpi-body"><span class="pv2-kpi-label">Con error</span><span class="pv2-kpi-value" id="kpiErrores">0</span></div>
                            </div>
                        </div>

                        <div class="pv2-listbar" style="margin-bottom:10px;">
                            <div class="pv2-search">
                                <input type="text" class="form-control" id="txtPriceFiltro" autocomplete="off" placeholder="Buscar por codigo, producto o tipo de precio">
                            </div>
                            <select class="form-control price-page-size" id="cmbPricePageSize" autocomplete="off">
                                <option value="10">10 por pagina</option>
                                <option value="25" selected>25 por pagina</option>
                                <option value="50">50 por pagina</option>
                                <option value="100">100 por pagina</option>
                            </select>
                        </div>

                        <div class="pv2-table-wrap">
                            <table class="pv2-table">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Producto</th>
                                        <th>Tipo</th>
                                        <th class="pv2-num">Costo</th>
                                        <th class="pv2-num">Precio ant.</th>
                                        <th class="pv2-num">Precio nuevo</th>
                                        <th class="pv2-num">% ant.</th>
                                        <th class="pv2-num">% nuevo</th>
                                        <th class="pv2-num">Comision</th>
                                        <th>Cambio por</th>
                                    </tr>
                                </thead>
                                <tbody id="priceDetalleBody"></tbody>
                            </table>
                        </div>
                        <div class="price-pager">
                            <a href="#" class="btn btn-default" id="btnPricePrev"><i class="icon16 i-arrow-left"></i></a>
                            <span id="pricePagInfo">Pagina 1 de 1</span>
                            <a href="#" class="btn btn-default" id="btnPriceNext"><i class="icon16 i-arrow-right"></i></a>
                        </div>

                        <div id="priceErroresBox" style="display:none; margin-top:14px;">
                            <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-remove"></i></span> Productos no procesados</p>
                            <div class="pv2-table-wrap">
                                <table class="pv2-table">
                                    <thead>
                                        <tr>
                                            <th>Linea</th>
                                            <th>Codigo</th>
                                            <th>Producto</th>
                                            <th>Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="priceErroresBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /resumen del procesamiento -->
                </div>

                <div class="tab-pane fade" id="dropdown">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-pencil-4"></i></span> Nota</p>
                        <div class="form-group">
                            <label class="pv2-label" for="txtDescription">Descripcion</label>
                            <textarea class="form-control" id="txtDescription" name="txtDescription" rows="6"><?php echo $objListPrice->description; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="dropdown-file">
                    <div class="pv2-empty">Los archivos se abren en una ventana nueva.</div>
                </div>
            </div>
        </div>
    </form>
    <!-- /formulario -->

    <!-- barra inferior (telefono) -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label"><?php echo $objListPrice->name; ?></p>
        </div>
        <a href="#" class="btn btn-primary" id="btnAceptMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
