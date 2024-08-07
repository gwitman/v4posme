<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $objFirmaEncription; ?></title>
		<meta name="viewport" 			content="width=device-width, initial-scale=1.0">
		<meta name="application-name" 	content="dsemp" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css">
		<link rel="stylesheet" type="text/css" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/style_table_report_printer.css" media="print">
		
	</head>
	<body> 
		<div class="data_grid_body">
			<table border=1>
				<thead>					
					<tr>
						<th colspan='22'>LISTA DE COMISIONES POR ACREDOR</th>
					</tr>
					<tr>
						<th colspan='22'><?php echo strtoupper($objCompany->name); ?> -- ACREDOR: <?php  if($objDetail){  echo ($objDetail[0]["NombreProveedor"]); }   ?> </th>
					</tr>
					<tr>
						<th colspan='22'>DEL <?php echo $startOn; ?> AL <?php echo $endOn; ?></th>
					</tr>
					
					
					<tr>
						<th nowrap class="cell_left" width="140">Fecha de Transaccion</th>
						<th nowrap class="cell_left" width="140">Codigo de Transaccion</th>
						
						<th nowrap class="cell_left" width="140">Tipo de Transaccion</th>						
						
						<th nowrap class="cell_right" width="70">A</th>
						<th nowrap class="cell_right" width="70">B</th>
						
						<th nowrap class="cell_right" width="70">C</th>
						
						<th nowrap class="cell_right" width="70">D</th>
						
						<th nowrap class="cell_right" width="70">E</th>
						
						<th nowrap class="cell_right" width="70">F</th>
						
						<th nowrap class="cell_right" width="70">G</th>
						
						<th nowrap class="cell_right" width="70">H</th>
						
						<th nowrap class="cell_right" width="70">I</th>
						
						<th nowrap class="cell_right" width="70">J</th>
						
						<th nowrap class="cell_right" width="70">K</th>
						
						<th nowrap class="cell_right" width="70">L</th>
						
						<th nowrap class="cell_right" width="70">M</th>
						
						<th nowrap class="cell_right" width="70">N</th>
						
						<th nowrap class="cell_left"  width="250">Nombre del Cliente</th>
						<th nowrap class="cell_left" width="120">Telefono del Ciente</th>
						<th nowrap class="cell_left" width="120">Desembolso</th>
						
						<th nowrap class="cell_left" width="120">Primer Fecha de Pago</th>
						
						<th nowrap class="cell_left" width="120">Frecuencia de Pago</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					$count 			= 0;
					$count1 		= 0;
					$count2 		= 0;
					$count3 		= 0;
					$count4 		= 0;
					$count5 		= 0;
					$count6 		= 0;
					$count7 		= 0;
					$count8 		= 0;
					$count9 		= 0;
					$count10 		= 0;
					$count11 		= 0;
					$count12		= 0;
					$count13		= 0;
					$count14		= 0;
					
					if($objDetail)
					foreach($objDetail as $i){
						$count++;
						echo "<tr>";							
							echo "<td nowrap  class='cell_left'>";
								echo ($i["FechaTransaccion"]);
							echo "</td>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["CodigoTransaccion"]);
							echo "</td>";
							echo "<td nowrap class='cell_left'>";
								echo ($i["TipoTransaccion"]);
							echo "</td>";
							
							
							
							echo "<td nowrap class='cell_right' >";
								echo sprintf("%01.2f",$i["SaldoInicial"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right' >";
								echo sprintf("%01.2f",$i["Abono"]);
							echo "</td>";
							
							echo "<td nowrap class='cell_right' >";
								echo sprintf("%01.2f",$i["SaldoFinal"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["InteresTotalDelAbono"]);
							echo "</td>";
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["CapitalTotalDelAbono"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["CapitalDesembolso"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["InterestTotalDelCredito"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["GastoFijoMonto"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["GastoFijoPorcentaje"] ) . " %";
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["RendimientoCompartido"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["RendimientoXComision"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right'>";
								echo sprintf("%01.2f",$i["RendimientoXProveedor"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right' style='background-color:bisque;' >";
								echo sprintf("%01.2f",$i["DepositoAProveedor"] );
							echo "</td>";
							
							echo "<td nowrap class='cell_right' >";
								echo sprintf("%01.2f",$i["Balance"] );
							echo "</td>";
						
							echo "<td nowrap  class='cell_left'>";
								echo ($i["NombreCliente"]);
							echo "</td>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["TelefonoCliente"]);
							echo "</td>";
							echo "<td nowrap  class='cell_left'>";
								echo ($i["CodigoDesembolso"]);
							echo "</td>";
							
							echo "<td nowrap  class='cell_left'>";
								echo ($i["PrimerFechaPagoMovimiento"]);
							echo "</td>";
							
							echo "<td nowrap  class='cell_left'>";
								echo ($i["FrecuenciaPagoMovimiento"]);
							echo "</td>";
							
						echo "</tr>";
						
						$count1 = $count1 + $i["SaldoInicial"];
						$count2 = $count2 + $i["Abono"];
						$count3 = $count3 + $i["SaldoFinal"];
						$count4 = $count4 + $i["InteresTotalDelAbono"];
						$count5 = $count5 + $i["CapitalTotalDelAbono"];
						$count6 = $count6 + $i["CapitalDesembolso"];
						$count7 = $count7 + $i["InterestTotalDelCredito"];
						$count8 = $count8 + $i["GastoFijoMonto"];
						$count9 = $count9 + $i["GastoFijoPorcentaje"];
						$count10 = $count10 + $i["RendimientoCompartido"];
						$count11 = $count11 + $i["RendimientoXComision"];
						$count12 = $count12 + $i["RendimientoXProveedor"];
						$count13 = $count13 + $i["DepositoAProveedor"];
						$count14 = $count14 + $i["Balance"];
					}
					?>
				</tbody>
				<footer>					
					<tr>
						<th nowrap class="cell_left" colspan="3" >TOTAL</th>
						<th nowrap class="cell_right">=SUMA(D5:D<?php echo $count+4;?>)</th>
						<th nowrap class="cell_right">=SUMA(E5:E<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(F5:F<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(G5:G<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(H5:H<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(I5:I<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(J5:J<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(K5:K<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(L5:L<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(M5:M<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(N5:N<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(O5:O<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(P5:P<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right">=SUMA(Q5:Q<?php echo $count+4;?>)</th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
					</tr>
					
					<tr>
						<th nowrap class="cell_left" colspan="3" >TOTAL</th>
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count1); ?></th>
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count2); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count3); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count4); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count5); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count6); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count7); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count8); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count9); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count10); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count11); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count12); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count13); ?></th>
						
						<th nowrap class="cell_right"><?php	echo sprintf("%01.2f",$count14); ?></th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
						
						<th nowrap class="cell_right"></th>
					</tr>
					
					<tr>
						<td colspan='22' style="text-align:center"><?php echo date("Y-m-d H:i:s");  ?> <?php echo $objFirmaEncription; ?></td>
					</tr>
				</footer>	
			</table>
			<br/>
			<br/>
			<table border=1 style="width:650px;">
				<thead>					
					<tr>
						<th colspan='2'>..::INFORMACION::..</th>
					</tr>
					
					<tr>
						<th nowrap class="cell_left" width="210">Atributo</th>		
						
						<th nowrap class="cell_left" >Descripcion de la Formula</th>						
					</tr>
				</thead>				
				<tbody>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">A) Saldo Inicial C$</th>
						<th nowrap class="cell_right">Saldo del Cliente Antes de Aplicar el Abono.</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">B) Abono C$</th>
						<th nowrap class="cell_right">Total del abono pagado por el cliente.</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">C) Saldo Final C$</th>
						<th nowrap class="cell_right">Saldo del Cliente Después de Aplicar el Abono.</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">D) Interés Total Del Abono</th>
						<th nowrap class="cell_right">Del abono aplicado por el cliente cuanto es de Interés</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">E) Capital Total Del Abono</th>
						<th nowrap class="cell_right">Del abono aplicado por el cliente cuanto es de Capital o Principal</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">F) Capital Desembolsado</th>
						<th nowrap class="cell_right">Total del desembolso inicial prestado al cliente.</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">G) Interés General del Crédito.</th>
						<th nowrap class="cell_right">Es la suma de la porción de interés de todas las cuotas correspondiente al desembolso.</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">H) Gasto Fijo.</th>
						<th nowrap class="cell_right">Es el gasto, generado por el desembolso</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">I) % Gasto Fijo sobre los intereses de cada cuota.</th>
						<th nowrap class="cell_right">valor igual a 0 ó mayor que uno.</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">J) Rendimiento Compartido.</th>
						<th nowrap class="cell_right">D *  (1 - (I/100))</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">K) Rendimiento x Comisión.</th>
						<th nowrap class="cell_right">J * 0.3</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">L) Rendimiento x Proveedor.</th>
						<th nowrap class="cell_right">J * 0.7</th>
					</tr>
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">M) Deposito a Proveedor.</th>
						<th nowrap class="cell_right">L  + E </th>
					</tr>
					
					<tr>
						<th nowrap class="cell_left" style="background:#CCC;">N) Capital Invertido.</th>
						<th nowrap class="cell_right"> Saldo del Capital.</th>
					</tr>
					
				</tbody>
			</table>
		</div>
		<br/>

	</body>	
</html>