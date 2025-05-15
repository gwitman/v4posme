<!DOCTYPE html>
<?php
    $cantFacturasContado     = 0;
    $cantFacturasCredito     = 0;
    $monedaFacturaLocal      = '';
    $monedaFacturaExt        = '';
    $monedaAbonos            = '';
    $monedaAbonosExt         = '';
    $monedaIngCaja           = '';
    $monedaIngCajaExt        = '';
    $monedaEgrCaja           = '';
    $monedaEgrCajaExt        = '';
    $monedaGastos            = '';
    $monedaGastosExt         = '';
    $sumFacturasLocal        = 0;
    $sumFacturasExt          = 0;
    $sumFacturasContadoLocal = 0;
    $sumFacturasContadoExt   = 0;
    $sumFacturasCreditoLocal = 0;
    $sumFacturasCredtitoExt  = 0;
    $sumAbonos               = 0;
    $sumAbonosExt            = 0;
    $sumIngCaja              = 0;
    $sumIngCajaExt           = 0;
    $sumEgrCaja              = 0;
    $sumEgrCajaExt           = 0;
    $sumGastos               = 0;
    $sumGastosExt            = 0;
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" />

		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css" media="print">
        <style>
            @media print {
              td.no-print, th.no-print {
                display: none;
              }

              /* Opcional: Ajustar el ancho de las columnas restantes */
              table {
                width: 100% !important; /* Forzar ancho completo */
              }
            }
        </style>
	</head>
	<body style="background-image:url(<?php echo base_url(); ?>/resource/img/logos/<?php echo $objLogo->value; ?>);background-size:80px 50px;">
		<br />
		<div class="data_grid_encabezado">
			<table>
				<thead>
					<tr>
						<th colspan="9">ARQUEO CAJA</th>
					</tr>
					<tr>
						<th colspan="9"><?php echo strtoupper($objCompany->name); ?></th>
					</tr>
				</thead>
			</table>
		</div>
        <div class="data_grid_encabezado">
            <table>
                <thead>
                    <tr>
                        <th colspan="9">Parámetros Seleccionadas</th>
                    </tr>
                    <tr>
                        <th colspan="3"><?php echo 'Fecha Inicio: ' . $fechaInicio; ?></th>
                        <th colspan="3"><?php echo 'Fecha Final: ' . $fechaFin; ?></th>
                        <th colspan="3"><?php echo 'Usuario: ' . $user ?></th>

                    </tr>
                </thead>
            </table>
        </div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr style="background-color: #545775">
						<th colspan="10" style="color: white; font-weight: bold;">FACTURAS</th>
					</tr>
					<tr>
						<th nowrap class="cell_left">#</th>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Documento</th>
                        <th nowrap class="cell_left">Tipo de Doc.</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Cod. Usuario</th>
						<th nowrap class="cell_right no-print">Comentario</th>
					</tr>
				</thead>
				<tbody>
					<?php
                        $count        = 0;
                        $countCliente = 1;
                        $cliente      = "";
                        $style        = "";
                        $style2       = "";
                        $cliente2     = "";
                        $clienteNuevo = true;
                        if ($objFacturas) {
                            foreach ($objFacturas as $i) {
                                $monedaFacturaLocal = $i['MonedaLocal'];
                                $monedaFacturaExt   = $i['MonedaExt'];

                                if ($i['ESTADO'] == 'CONTADO') {
                                    if ($i['CurrencyID'] == 1/*Cordobas*/) {
                                        $sumFacturasContadoLocal += $i['Monto'];
                                    } else {
                                        $sumFacturasContadoExt += $i['Monto'];
                                    }

                                    $cantFacturasContado++;
                                } else {
                                    if ($i['CurrencyID'] == 1/*Cordobas*/) {
                                        $sumFacturasCreditoLocal += $i['Monto'];
                                    } else {
                                        $sumFacturasCredtitoExt += $i['Monto'];
                                    }

                                    $cantFacturasCredito++;
                                }
                                //Calcular Sebra
                                $count++;
                                if ($count % 2 == 0) {
                                    $style = "background:#ddd";
                                } else {
                                    $style = "";
                                }

                                //Separar Cliente al Final
                                if ($i["Entidad"] != $cliente && $cliente != "") {
                                    echo "<tr style='border-bottom-color:BLUE;border-bottom-style:solid;border-bottom-width:1px;'><td colspan='10'>&nbsp;</td></tr>";
                                }

                                $cliente = $i["Entidad"];
                                /*Estilo de cuota*/

                                //Repitar Cliente unicamente al Inicio
                                if ($cliente2 != "" && $cliente2 != $i["Entidad"]) {
                                    $clienteNuevo = true;
                                    $countCliente++;
                                }

                                //Grid
                                echo "<tr style='" . $style . "'>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo $countCliente;
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["Entidad"]);
                                }

                                echo "</td>";

                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["NombreCliente"]);
                                }
                                echo "</td>";

                                echo "<td nowrap class='cell_left'>";
                                echo($i["Documento"]);
                                echo "</td>";

                                echo "<td nowrap class='cell_left'>";
                                echo($i["ESTADO"]);
                                echo "</td>";

                                echo "<td nowrap class='cell_left' >";
                                echo(date_format(date_create($i["Fecha"]), "Y-m-d h:i:s A"));
                                echo "</td>";

                                echo "<td nowrap class='cell_right'>";
                                $moneda = $i['CurrencyID'] == 1/*Cordobas*/ ? $monedaFacturaLocal : $monedaFacturaExt;
                                echo($moneda . ' ' . number_format($i["Monto"], 2, '.', ','));
                                echo "</td>";

                                echo "<td nowrap class='cell_right'>";
                                echo($i["CodigoEmpleado"]);
                                echo "</td>";

                                echo "<td nowrap class='cell_right no-print'>";
                                echo($i["Concepto"]);
                                echo "</td>";
                                echo "</tr>";
                                //Repitar Cliente unicamente al Inicio
                                if ($clienteNuevo) {
                                    $clienteNuevo = false;
                                }

                                $cliente2 = $i["Entidad"];
                            }
                        }

                    ?>
				</tbody>
			<?php if (isset($objFacturas)) {?>
				<tfoot style="background-color: #E2C044; font-weight: bold">
                    <tr>
                        <td colspan="3" style="text-align: left;">Total Contado</td>
                        <td colspan="2" style="text-align: right"><?php echo 'Cant.' . $cantFacturasContado; ?></td>
                        <td colspan="2" style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumFacturasContadoLocal, 2, '.', ','); ?></td>
                        <td colspan="3" style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumFacturasContadoExt, 2, '.', ','); ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;">Total Credito</td>
                        <td colspan="2" style="text-align: right"><?php echo 'Cant.' . $cantFacturasCredito; ?></td>
                        <td colspan="2" style="text-align: right"><?php echo $monedaFacturaLocal . ' ' . number_format($sumFacturasCreditoLocal, 2, '.', ','); ?></td>
                        <td colspan="3" style="text-align: right"><?php echo $monedaFacturaExt . ' ' . number_format($sumFacturasCredtitoExt, 2, '.', ','); ?></td>
                    </tr>
		        </tfoot>
		    <?php }?>
			</table>
		</div>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr style="background-color: #545775">
						<th colspan="8" style="color: white; font-weight: bold;">ABONOS</th>
					</tr>
					<tr>
						<th nowrap class="cell_left">#</th>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_left">Cliente</th>
						<th nowrap class="cell_left">Documento</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Usuario</th>
						<th nowrap class="cell_right no-print">Comentario</th>
					</tr>
				</thead>
				<tbody>
					<?php
                        $count        = 0;
                        $countCliente = 1;
                        $cliente      = "";
                        $style        = "";
                        $style2       = "";
                        $cliente2     = "";
                        $clienteNuevo = true;
                        if ($objAbonos) {
                            foreach ($objAbonos as $i) {
                                $monedaAbonos    = $i['MonedaLocal'];
                                $monedaAbonosExt = $i['MonedaExt'];

                                if ($i['CurrencyID'] == 1/*Cordobas*/) {
                                    $sumAbonos += $i['Monto'];
                                } else {
                                    $sumAbonosExt += $i['Monto'];
                                }

                                //Calcular Sebra
                                $count++;
                                if ($count % 2 == 0) {
                                    $style = "background:#ddd";
                                } else {
                                    $style = "";
                                }

                                //Separar Cliente al Final
                                if ($i["Entidad"] != $cliente && $cliente != "") {
                                    echo "<tr style='border-bottom-color:BLUE;border-bottom-style:solid;border-bottom-width:1px;'><td colspan='8'>&nbsp;</td></tr>";
                                }

                                $cliente = $i["Entidad"];
                                /*Estilo de cuota*/

                                //Repitar Cliente unicamente al Inicio
                                if ($cliente2 != "" && $cliente2 != $i["Entidad"]) {
                                    $clienteNuevo = true;
                                    $countCliente++;
                                }

                                //Grid
                                echo "<tr style='" . $style . "'>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo $countCliente;
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["Entidad"]);
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["NombreCliente"]);
                                }

                                echo "</td>";

                                echo "<td nowrap class='cell_left'>";
                                echo($i["Documento"]);
                                echo "</td>";
                                echo "<td nowrap class='cell_left' >";
                                echo(date_format(date_create($i["Fecha"]), "Y-m-d h:m:s A"));
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                $moneda = $i['CurrencyID'] == 1/*Moneda Local*/ ? $monedaAbonos : $monedaAbonosExt;
                                echo($moneda . ' ' . number_format($i["Monto"], 2, '.', ','));
                                echo "</td>";

                                echo "<td nowrap class='cell_right'>";
                                echo($i["CodigoEmpleado"]);
                                echo "</td>";

                                echo "<td nowrap class='cell_righ no-print'>";
                                echo $i["Concepto"];
                                echo "</td>";
                                echo "</tr>";
                                //Repitar Cliente unicamente al Inicio
                                if ($clienteNuevo) {
                                    $clienteNuevo = false;
                                }

                                $cliente2 = $i["Entidad"];
                            }
                        }

                    ?>
				</tbody>
				<?php if (isset($objAbonos)) {?>
				<tfoot style="background-color: #E2C044; font-weight: bold">
		            <tr>
		                <td colspan="2" style="text-align:left">Total</td>
		                <td colspan="2" style="text-align:right;"><?php echo 'Cant. ' . count($objAbonos); ?></td>
		                <td colspan="2" style="text-align:right"><?php echo $monedaAbonos . ' ' . number_format($sumAbonos, 2, '.', ','); ?></td>
		                <td colspan="2" style="text-align:right"><?php echo $monedaAbonosExt . ' ' . number_format($sumAbonosExt, 2, '.', ','); ?></td>
		            </tr>
		        </tfoot>
		    <?php }?>
			</table>
		</div>
		<br>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr style="background-color: #545775">
						<th colspan="9" style="color: white; font-weight: bold;">INGRESO CAJA</th>
					</tr>
					<tr>
						<th nowrap class="cell_left">#</th>
						<th nowrap class="cell_left">No</th>
						<th nowrap class="cell_left">Caja</th>
						<th nowrap class="cell_left">Documento</th>
						<th nowrap class="cell_left">Fecha</th>
						<th nowrap class="cell_right">Monto</th>
						<th nowrap class="cell_right">Usuario</th>
                        <th nowrap class="cell_right">Tipo</th>
						<th nowrap class="cell_right no-print">Comentario</th>
					</tr>
				</thead>
				<tbody>
					<?php
                        $count        = 0;
                        $countCliente = 1;
                        $cliente      = "";
                        $style        = "";
                        $style2       = "";
                        $cliente2     = "";
                        $clienteNuevo = true;
                        if ($objIngCaja) {
                            foreach ($objIngCaja as $i) {
                                if ($i['CurrencyID'] == 1) {
                                    $sumIngCaja += $i["Monto"];
                                } else {
                                    $sumIngCajaExt += $i["Monto"];
                                }

                                //Calcular Sebra
                                $count++;
                                if ($count % 2 == 0) {
                                    $style = "background:#ddd";
                                } else {
                                    $style = "";
                                }

                                //Separar Cliente al Final
                                if ($i["Entidad"] != $cliente) {
                                    echo "<tr style='border-bottom-color:BLUE;border-bottom-style:solid;border-bottom-width:1px;'><td colspan='9'>&nbsp;</td></tr>";
                                }

                                $cliente = $i["Entidad"];
                                /*Estilo de cuota*/

                                //Repitar Cliente unicamente al Inicio
                                if ($cliente2 != $i["Entidad"]) {
                                    $clienteNuevo = true;
                                    $countCliente++;
                                }

                                //Grid
                                echo "<tr style='" . $style . "'>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo $countCliente;
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["Entidad"]);
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["NombreCliente"]);
                                }

                                echo "</td>";

                                echo "<td nowrap class='cell_left'>";
                                echo($i["Documento"]);
                                echo "</td>";
                                echo "<td nowrap class='cell_left' >";
                                echo(date_format(date_create($i["Fecha"]), "Y-m-d h:m:s A"));
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                $moneda = $i['CurrencyID'] == 1 ? $monedaFacturaLocal : $monedaFacturaExt;
                                echo($moneda . ' ' . number_format($i["Monto"], 2, '.', ','));
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                echo($i["CodigoEmpleado"]);
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                echo $i["Categoria"];
                                echo "</td>";
                                echo "<td nowrap class='cell_right no-print'>";
                                echo $i["Concepto"];
                                echo "</td>";
                                echo "</tr>";
                                //Repitar Cliente unicamente al Inicio
                                if ($clienteNuevo) {
                                    $clienteNuevo = false;
                                }

                                $cliente2 = $i["Entidad"];
                            }
                        }

                    ?>
				</tbody>
				<?php if (isset($objIngCaja)) {?>
				<tfoot style="background-color: #E2C044; font-weight: bold">
		            <tr>
		                <td colspan="3" style="text-align:left">Total</td>
		                <td colspan="2" style="text-align:right;"><?php echo 'Cant.' . count($objIngCaja); ?></td>
		                <td colspan="2" style="text-align:right"><?php echo $monedaFacturaLocal . ' ' . number_format($sumIngCaja, 2, '.', ','); ?></td>
                        <td colspan="2" style="text-align:right"><?php echo $monedaFacturaExt . ' ' . number_format($sumIngCajaExt, 2, '.', ','); ?></td>
		            </tr>
		        </tfoot>
		    	<?php }?>
			</table>
		</div>
		<br>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr style="background-color: #545775">
						<th colspan="9" style="color: white; font-weight: bold;">EGRESO CAJA</th>
					</tr>
					<tr>
                        <th nowrap class="cell_left">#</th>
                        <th nowrap class="cell_left">No</th>
                        <th nowrap class="cell_left">Caja</th>
                        <th nowrap class="cell_left">Documento</th>
                        <th nowrap class="cell_left">Fecha</th>
                        <th nowrap class="cell_right">Monto</th>
                        <th nowrap class="cell_right">Usuario</th>
                        <th nowrap class="cell_right">Tipo</th>
                        <th nowrap class="cell_right no-print">Comentario</th>
                    </tr>
				</thead>
				<tbody>
					<?php
                        $count        = 0;
                        $countCliente = 1;
                        $cliente      = "";
                        $style        = "";
                        $style2       = "";
                        $cliente2     = "";
                        $clienteNuevo = true;
                        if ($objEgrCaja) {
                            foreach ($objEgrCaja as $i) {
                                if ($i['CurrencyID'] == 1) {
                                    $sumEgrCaja += $i["Monto"];
                                } else {
                                    $sumEgrCajaExt += $i["Monto"];
                                }

                                //Calcular Sebra
                                $count++;
                                if ($count % 2 == 0) {
                                    $style = "background:#ddd";
                                } else {
                                    $style = "";
                                }

                                //Separar Cliente al Final
                                if ($i["Entidad"] != $cliente) {
                                    echo "<tr style='border-bottom-color:BLUE;border-bottom-style:solid;border-bottom-width:1px;'><td colspan='9'>&nbsp;</td></tr>";
                                }

                                $cliente = $i["Entidad"];
                                /*Estilo de cuota*/

                                //Repitar Cliente unicamente al Inicio
                                if ($cliente2 != $i["Entidad"]) {
                                    $clienteNuevo = true;
                                    $countCliente++;
                                }

                                //Grid
                                echo "<tr style='" . $style . "'>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo $countCliente;
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["Entidad"]);
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["NombreCliente"]);
                                }

                                echo "</td>";

                                echo "<td nowrap class='cell_left'>";
                                echo($i["Documento"]);
                                echo "</td>";
                                echo "<td nowrap class='cell_left' >";
                                echo(date_format(date_create($i["Fecha"]), "Y-m-d h:m:s A"));
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                $moneda = $i['CurrencyID'] == 1 ? $monedaFacturaLocal : $monedaFacturaExt;
                                echo($moneda . ' ' . number_format($i["Monto"], 2, '.', ','));
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                echo($i["CodigoEmpleado"]);
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                echo $i["Categoria"];
                                echo "</td>";
                                echo "<td nowrap class='cell_right no-print'>";
                                echo $i["Concepto"];
                                echo "</td>";
                                echo "</tr>";
                                //Repitar Cliente unicamente al Inicio
                                if ($clienteNuevo) {
                                    $clienteNuevo = false;
                                }

                                $cliente2 = $i["Entidad"];
                            }
                        }

                    ?>
				</tbody>
				<?php if (isset($objEgrCaja)) {?>
				<tfoot style="background-color: #E2C044; font-weight: bold">
		            <tr>
		                <td colspan="3" style="text-align:left">Total</td>
		                <td colspan="2" style="text-align:right;"><?php echo 'Cant.' . count($objEgrCaja); ?></td>
		                <td colspan="2" style="text-align:right"><?php echo $monedaFacturaLocal . ' ' . number_format($sumEgrCaja, 2, '.', ','); ?></td>
                        <td colspan="2" style="text-align:right"><?php echo $monedaFacturaExt . ' ' . number_format($sumEgrCajaExt, 2, '.', ','); ?></td>
		            </tr>
		        </tfoot>
		    	<?php }?>
			</table>
		</div>
		<br>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr style="background-color: #545775">
						<th colspan="9" style="color: white; font-weight: bold;">GASTOS</th>
					</tr>
					<tr>
                        <th nowrap class="cell_left">#</th>
                        <th nowrap class="cell_left">No</th>
                        <th nowrap class="cell_left">Proveedor</th>
                        <th nowrap class="cell_left">Documento</th>
                        <th nowrap class="cell_left">Fecha</th>
                        <th nowrap class="cell_right">Monto</th>
                        <th nowrap class="cell_right">Usuario</th>
                        <th nowrap class="cell_right">Tipo</th>
                        <th nowrap class="cell_right no-print">Comentario</th>
                    </tr>
				</thead>
				<tbody>
					<?php
                        $count        = 0;
                        $countCliente = 1;
                        $cliente      = "";
                        $style        = "";
                        $style2       = "";
                        $cliente2     = "";
                        $clienteNuevo = true;
                        if ($objGastos) {
                            foreach ($objGastos as $i) {
                                if ($i['CurrencyID'] == 1) {
                                    $sumGastos += $i["Monto"];
                                } else {
                                    $sumGastosExt += $i["Monto"];
                                }

                                //Calcular Sebra
                                $count++;
                                if ($count % 2 == 0) {
                                    $style = "background:#ddd";
                                } else {
                                    $style = "";
                                }

                                //Separar Cliente al Final
                                if ($i["Entidad"] != $cliente) {
                                    echo "<tr style='border-bottom-color:BLUE;border-bottom-style:solid;border-bottom-width:1px;'><td colspan='9'>&nbsp;</td></tr>";
                                }

                                $cliente = $i["Entidad"];
                                /*Estilo de cuota*/

                                //Repitar Cliente unicamente al Inicio
                                if ($cliente2 != $i["Entidad"]) {
                                    $clienteNuevo = true;
                                    $countCliente++;
                                }

                                //Grid
                                echo "<tr style='" . $style . "'>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo $countCliente;
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["Entidad"]);
                                }

                                echo "</td>";
                                echo "<td nowrap class='cell_left'>";
                                if ($clienteNuevo) {
                                    echo($i["NombreCliente"]);
                                }

                                echo "</td>";

                                echo "<td nowrap class='cell_left'>";
                                echo($i["Documento"]);
                                echo "</td>";
                                echo "<td nowrap class='cell_left' >";
                                echo(date_format(date_create($i["Fecha"]), "Y-m-d h:m:s A"));
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                $moneda = $i['CurrencyID'] == 1 ? $monedaFacturaLocal : $monedaFacturaExt;
                                echo($moneda . ' ' . number_format($i["Monto"], 2, '.', ','));
                                echo "</td>";
                                $monedaGastos = $i["MonedaLocal"];
                                echo "<td nowrap class='cell_right'>";
                                echo($i["CodigoEmpleado"]);
                                echo "</td>";
                                echo "<td nowrap class='cell_right'>";
                                echo $i["Categoria"];
                                echo "</td>";
                                echo "<td nowrap class='cell_right no-print'>";
                                echo $i["Concepto"];
                                echo "</td>";
                                echo "</tr>";
                                //Repitar Cliente unicamente al Inicio
                                if ($clienteNuevo) {
                                    $clienteNuevo = false;
                                }

                                $cliente2 = $i["Entidad"];
                            }
                        }

                    ?>
				</tbody>
				<?php if (isset($objGastos)) {?>
				<tfoot style="background-color: #E2C044; font-weight: bold">
		            <tr>
		                <td colspan="3" style="text-align: left;">Total</td>
		                <td colspan="2" style="text-align: right;"><?php echo 'Cant.' . count($objGastos); ?></td>
		                <td colspan="2" style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumGastos, 2, '.', ','); ?></td>
                        <td colspan="2" style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumGastosExt, 2, '.', ','); ?></td>
		            </tr>
		        </tfoot>
		    	<?php }?>
			</table>
		</div>
		<br>
		<br/>
		<br/>
		<div class="data_grid_body">
			<table>
				<thead>
					<tr style="background-color: #545775; font-weight: bold; color: white;">
						<th style="width: 25%; text-align: right;">Facturas</th>
						<th style="text-align: right;">Abonos</th>
						<th style="text-align: right;">Ingreso Caja</th>
						<th style="text-align: right;">Egreso Caja</th>
						<th style="text-align: right;">Gastos</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumFacturasContadoLocal + $sumFacturasCreditoLocal, 2, '.', ','); ?></td>
						<td style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumAbonos, 2, '.', ','); ?></td>
						<td style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumIngCaja, 2, '.', ','); ?></td>
						<td style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumEgrCaja, 2, '.', ','); ?></td>
						<td style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumGastos, 2, '.', ','); ?></td>
					</tr>
                    <tr>
                        <td style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumFacturasContadoExt + $sumFacturasCredtitoExt, 2, '.', ','); ?></td>
                        <td style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumAbonosExt, 2, '.', ','); ?></td>
                        <td style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumIngCajaExt, 2, '.', ','); ?></td>
                        <td style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumEgrCajaExt, 2, '.', ','); ?></td>
                        <td style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumGastosExt, 2, '.', ','); ?></td>
                    </tr>
				</tbody>
			</table>
		</div>
		<br>
		<div class="data_grid_body">
			<table>
				<tbody>
					<tr style="background-color: #00a676;; font-weight: bold; color: white;">
						<th style="text-align: left;">Total C&oacute;rdobas:</th>
						<th style="text-align: right;"><?php echo $monedaFacturaLocal . ' ' . number_format($sumFacturasContadoLocal + $sumFacturasCreditoLocal + $sumAbonos + $sumIngCaja - $sumEgrCaja - $sumGastos, 2, '.', ','); ?></th>
					</tr>
                    <tr style="background-color: #00a676;; font-weight: bold; color: white;">
                        <th style="text-align: left;">Total D&oacute;lares:</th>
                        <th style="text-align: right;"><?php echo $monedaFacturaExt . ' ' . number_format($sumFacturasContadoExt + $sumFacturasCredtitoExt + $sumAbonosExt + $sumIngCajaExt - $sumEgrCajaExt - $sumGastosExt, 2, '.', ','); ?></th>
                    </tr>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="data_grid_firm_system">
			<table>
				<tbody>
					<tr>
						<td colspan="9"><?php echo date("Y-m-d h:i:s A"); ?></td>
					</tr>
                    <tr>
                        <td colspan="9"><?php echo $objFirmaEncription; ?></td>
                    </tr>
				</tbody>
			</table>
		</div>
	</body>
</html>