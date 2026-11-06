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

                                <div class="form-group">
                                    <label class="pv2-label" for="txtFilePrice">Actualizar Precios</label>
                                    <input class="form-control" type="text" autocomplete="off" name="txtFilePrice" id="txtFilePrice" value="">
                                </div>
                                <div class="form-group">
                                    <label class="pv2-label" for="txtGenerateFile">Generar Archivo</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="txtGenerateFile" name="txtGenerateFile" value="1">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
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
