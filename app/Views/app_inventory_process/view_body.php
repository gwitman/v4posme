<div class="pv2 pinv pinv-form<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-hammer"></i></div>
            <div>
                <p class="pv2-header-title">Procesos de inventario</p>
                <p class="pv2-header-sub">Salidas de inventario por fórmulas y XML para Encuentra24</p>
            </div>
        </div>
    </div>
    <!-- /encabezado -->

    <div class="row pacp-grid">

        <!-- salidas por formulas -->
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Salidas de inventario por fórmulas</p>
                <p class="pv2-help pacp-desc">Realiza las salidas de inventario de las facturas con productos formulados en el ciclo seleccionado.</p>
                <form role="form" autocomplete="off" onsubmit="return false;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 form-group">
                            <label class="pv2-label" for="txtClosedPeriod">Periodo</label>
                            <select name="txtClosedPeriod" id="txtClosedPeriod" class="select2">
                                    <option></option>
                                    <?php
                                    if($objListAccountingPeriod)
                                    foreach($objListAccountingPeriod as $i){
                                        echo "<option value='".$i->componentPeriodID."'>".helper_DateToSpanish($i->startOn,"Y")."</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group">
                            <label class="pv2-label" for="txtClosedCicle">Ciclo</label>
                            <select name="txtClosedCicle" id="txtClosedCicle" class="select2">
                                    <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="pacp-foot">
                        <button type="button" id="btnAceptCreateOutputInventoryByFormulate" class="btn btn-primary"><i class="icon16 i-checkmark-4"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /salidas por formulas -->

        <!-- xml encuentra24 -->
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> XML para Encuentra24</p>
                <p class="pv2-help pacp-desc">Ejecuta el proceso del XML para Encuentra24 en una pestaña nueva.</p>
                <form role="form" autocomplete="off" onsubmit="return false;">
                    <div class="pacp-foot">
                        <button type="button" id="btnAceptUploadFile" class="btn btn-primary"><i class="icon16 i-checkmark-4"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /xml encuentra24 -->

    </div>
</div>
