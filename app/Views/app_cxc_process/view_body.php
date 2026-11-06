<div class="pv2<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-hammer"></i></div>
            <div>
                <p class="pv2-header-title">Procesos CxC</p>
                <p class="pv2-header-sub">Carga de información a SIN Riesgo</p>
            </div>
        </div>
    </div>
    <!-- /encabezado -->

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-tags-2"></i></span> Subir carga SIN Riesgo</p>
                <p class="pv2-help">Ejecuta el proceso de carga a SIN Riesgo en una pestaña nueva.</p>
                <form role="form" autocomplete="off" onsubmit="return false;">
                    <div class="pv2-actions" style="justify-content:flex-end">
                        <button type="button" id="btnAceptUploadFile" class="btn btn-primary"><i class="icon16 i-checkmark-4"></i> Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
