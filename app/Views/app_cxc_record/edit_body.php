<?php
/**
 * Imprime una operacion de credito (vigente o cancelada) del reporte de buro.
 *
 * @param object $ws       Operacion de credito.
 * @param int    $cantidad Consecutivo que se muestra en el titulo.
 * @param object $company  Empresa en sesion; sus facturas se muestran con el nombre de la empresa.
 */
$renderCredito = function ($ws, $cantidad, $company) {
    $fila = function ($label, $valor, $clase = "black") {
        echo '<div class="cxr-fila"><span class="cxr-fila-label">'.$label.'</span><span class="cxr-fila-valor '.$clase.'">'.$valor.'</span></div>';
    };
    ?>
    <div class="cxr-credito">
        <p class="cxr-credito-titulo">Credito Numero <?php echo $cantidad; ?> --<?php echo $ws->NumeroCredito; ?>: <?php echo ( strpos($ws->NumeroCredito, $company->abreviature.'FAC') !== false ? $company->name : $ws->Entidad ); ?></p>
        <p class="cxr-credito-fecha"><span class="green">Fecha desembolso: <?php echo helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"); ?></span></p>
        <div class="cxr-datos">
            <div>
                <?php
                $fila("Tipo de Obligación:", strtolower($ws->TipoObligacion));
                $fila("Tipo de Crédito:", strtolower($ws->TipoCredito));
                $fila("Tipo de Garantia:", strtolower(substr($ws->TipoGarantia,0,26)));
                $fila("Estado:", strtolower($ws->EstadoOP), helper_GetColorSinRiesgo($ws->EstadoOP));
                $fila("Forma de Recuperación:", strtolower($ws->FormaRecuperacion), helper_GetColorSinRiesgo($ws->FormaRecuperacion));
                ?>
            </div>
            <div>
                <?php
                $fila("Fecha de Reporte:", helper_DateToSpanish($ws->FechaReporte,"Y-M-d"));
                $fila("Fecha de Desembolso:", helper_DateToSpanish($ws->FechaDesembolso,"Y-M-d"));
                $fila("Departamento:", strtolower($ws->Departamento));
                $fila("Forma de Pago:", strtolower($ws->FormaDePago));
                $fila("Plazo:", $ws->PlazoMeses);
                ?>
            </div>
            <div>
                <?php
                $fila("Monto Autorizado (C$):", number_format(round($ws->MontoAutorizado,2),2));
                $fila("Saldo de Deuda (C$):", number_format(round($ws->SaldoDeuda,2),2));
                $fila("Monto Vencido (C$):", number_format(round($ws->MontoVencido,2),2));
                $fila("Antiguedad Mora:", $ws->AntiguedadMoraEnDias);
                $fila("Valor Cuota (C$):", number_format(round($ws->Cuota,2),2));
                ?>
            </div>
        </div>
    </div>
    <?php
};
?>
<div class="pv2<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-file"></i></div>
            <div>
                <p class="pv2-header-title">ULTIMA CONSULTA:# <span class="invoice-num"><?php echo (isset($ObjConsulta) ? $ObjConsulta->createdOn : ""); ?></span></p>
                <p class="pv2-header-sub" style="word-break:break-word">nombre del archivo: <?php echo (isset($ObjConsulta) ? $ObjConsulta->file:""); ?></p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="<?php if (!$objCustomer) { echo "#"; } else { echo  base_url()."/app_cxc_customer/edit/companyID/".$objCustomer->companyID."/branchID/".$objCustomer->branchID."/entityID/".$objCustomer->entityID ; } ?>" id="btnBackCustomer" class="btn btn-default <?php echo getBehavio($company->type,"app_cxc_record","showBtnIrCustomerOfRecord","hidden"); ?>" ><i class="icon16 i-rotate"></i> Regresar al cliente</a>
            <a href="<?php echo base_url(); ?>/app_cxc_customer/index" id="btnBack" class="btn btn-default" ><i class="icon16 i-rotate"></i> Atras</a>
            <a href="<?php echo base_url(); ?>/app_cxc_report/consulta_sin_riesgo/viewReport/true/fileName/<?php echo (isset($ObjConsulta) ? $ObjConsulta->file:""); ?>" target="_black" class="btn btn-primary" id="btnPrinter"><i class="icon16 i-print"></i> Imprimir</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <!-- busqueda -->
    <div class="pv2-card pv2-listbar">
        <form class="pv2-search" role="search" autocomplete="off" action="<?php echo base_url(); ?>/app_cxc_record/index" method="get">
            <input type="text" class="form-control" id="identificacion" name="identificacion" placeholder="000-000000-0000X" autocomplete="off">
            <button type="submit" class="btn btn-primary" id="btnSearchCustomer" title="Buscar"><i class="icon16 i-search-2"></i></button>
        </form>
    </div>
    <!-- /busqueda -->

    <!-- body -->
    <form id="form-new-cxc-customer" name="form-new-cxc-customer" role="form" autocomplete="off">
        <div class="printArea">

            <ul id="myTab" class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Informacion General.</a></li>
                <li><a href="#profile" data-toggle="tab">Historial de Creditos.</a></li>
                <li><a href="#profile-phones" data-toggle="tab">Consultas.</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Identificación del Cliente</p>
                                <dl class="dl-horizontal">
                                    <dt>Cedula</dt>
                                    <dd><span class="blue"><?php echo (isset($Persona) ? $Persona->NumeroDocumentoIdentidad:""); ?></span></dd>
                                    <dt>Nombre</dt>
                                    <dd><?php echo (isset($Persona) ? $Persona->NombreRazonSocial:""); ?></dd>
                                </dl>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="pv2-card">
                                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-phone"></i></span> Listado de Telefonos</p>
                                <?php if (isset($Telefonos) && $Telefonos) { ?>
                                    <dl class="dl-horizontal">
                                        <?php
                                        foreach($Telefonos as $ws){
                                            echo "<dt>".$ws->Referencia."</dt>";
                                            echo '<dd># <span class="blue">'.$ws->Telefono.'</span></dd>';
                                        }
                                        ?>
                                    </dl>
                                <?php } else { ?>
                                    <div class="pv2-empty">Sin telefonos</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-location"></i></span> Lista de Direcciones del cliente</p>
                        <?php if (isset($Direcciones) && $Direcciones) { ?>
                            <dl class="dl-horizontal">
                                <?php
                                foreach($Direcciones as $ws){
                                    echo "<dt>".$ws->Referencia."</dt>";
                                    echo '<dd>Departamento: <span class="blue">'.$ws->Departamento.'</span> &nbsp;&nbsp;&nbsp;Municipio:&nbsp;&nbsp;'.$ws->Municipio.'</dd>';
                                    echo '<dd>'.$ws->Direccion.'</dd>';
                                }
                                ?>
                            </dl>
                        <?php } else { ?>
                            <div class="pv2-empty">Sin direcciones</div>
                        <?php } ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Detalle de operaciones de credito</p>
                        <?php
                        $cantidad = 0;
                        if(isset($CreditosVigentes))
                        if($CreditosVigentes)
                        foreach($CreditosVigentes as $ws){
                            $cantidad++;
                            $renderCredito($ws, $cantidad, $company);
                        }
                        if($cantidad == 0)
                            echo '<div class="pv2-empty">Sin operaciones</div>';
                        ?>
                        <p class="cxr-seccion">Detalle de operaciones de creditos activos grupos solidarios</p>
                        <p class="cxr-seccion">Detalle de tarjetas de credito</p>
                        <p class="cxr-seccion">Detalle de operaciones de creditos saneados</p>
                        <p class="cxr-seccion">Detalle de operaciones de creditos saneados grupos solidarios</p>
                    </div>
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-file"></i></span> Detalle de operaciones de creditos cancelados</p>
                        <?php
                        $cantidad = 0;
                        if(isset($CreditosCancelados))
                        if($CreditosCancelados)
                        foreach($CreditosCancelados as $ws){
                            $cantidad++;
                            $renderCredito($ws, $cantidad, $company);
                        }
                        if($cantidad == 0)
                            echo '<div class="pv2-empty">Sin operaciones</div>';
                        ?>
                        <p class="cxr-seccion">Detalle de operaciones de creditos cancelados grupos solidarios</p>
                        <p class="cxr-seccion">Detalle de nota de prensas</p>
                        <p class="cxr-seccion">Detalle de operaciones de servicios publicos</p>
                    </div>
                </div>

                <div class="tab-pane fade" id="profile-phones">
                    <div class="pv2-card">
                        <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-search-2"></i></span> Listado de consultas, donde el cliente ha solicitado credito</p>
                        <?php if (isset($Consultas) && $Consultas) { ?>
                            <dl class="dl-horizontal">
                                <?php
                                foreach($Consultas as $ws){
                                    echo "<dt>Cantidad. ".$ws->Cantidad."</dt>";
                                    echo '<dd>Fecha de consulta: <span class="blue">'.$ws->FechaConsulta.'</span> &nbsp;&nbsp;&nbsp;Entidad:'.$ws->Entidad.' </dd>';
                                }
                                ?>
                            </dl>
                        <?php } else { ?>
                            <div class="pv2-empty">Sin consultas</div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <!-- /body -->
</div>
