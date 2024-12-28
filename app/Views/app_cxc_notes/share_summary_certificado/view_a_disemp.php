<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Notas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="dsemp"/>
    
	<style>
		body
		{
			margin:0px;
		}
	</style>
</head>
<body style="margin:0px;  font-family: system-ui; ">
	
	<?php
	$path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objLogo->value;

	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
	?>
	
	<table style="width:100%">
		<tr>
			<td style="width:33%"><img src="<?= $base64 ?>" style="width:150px" alt="Logo del Centro Educativo"/></td>
			<td style="width:33%; text-align:center ">
				<h3 style="margin:0px" >Certificado de Notas</h3>
				<h3 style="margin:0px" ><?= $objCompany->name ?></h3>
				<h3 style="margin:0px" ><?= $objCompany->address ?></h3>
				<h3 style="margin:0px" ><?= $objTelefono->value ?></h3>
			</td>
			<td style="width:33%">
				<p>&nbsp;</p>
			</td>
		</tr>
	</table>
	
	<p>&nbsp;</p>
	
	<table style="width:100%">
		<tr>
			<td style="width:200px;font-weight: bold">Nombre del estudiante:</td>
			<td><?= $objAlumno["customerNumber"] ?> <?= $objAlumno["Nombre"] ?></td>
		</tr>
		<tr>
			<td style="font-weight: bold">Grado:</td>
			<td><?= $objAlumno["Grado"] ?></td>
		</tr>
		<tr>
			<td style="font-weight: bold">Año:</td>
			<td><?= $objAlumno["Ano"] ?></td>
		</tr>
	<table>
	
	<p>&nbsp;</p>
	
	<table style="table-layout: fixed;width:2340px">
		<thead>
		<tr style="background-color:#0a68b4;color:white" >
			<th style="text-align:left;white-space: nowrap;width: 300px">Materia</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Ene</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Feb</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Mar</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">I Trimestre</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">I Cualitativo</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Abr</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">May</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Jun</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">II Trimestre</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">II Cualitativo</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Jul</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Ags</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Sept</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">III Trimestre</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">III Cualitativo</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Oct</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Nov</th>
			<th style="text-align:left;white-space: nowrap;width: 60px">Dic</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">IV Trimestre</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">IV Cualitativo</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">Anualidad</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">Cualitativo</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($objDetail as $value): ?>
			<tr>
				<td style="text-align: left;"><?= $value["Materia"] ?></td>
				<td><?= intval($value["01-Enero"]) ?></td>
				<td><?= intval($value["02-Febrero"]) ?></td>
				<td><?= intval($value["03-Marzo"]) ?></td>
				<td><?= intval($value["Trimestral1"]) ?></td>
				<td><?= ($value["Trimestre1Cualitativo"]) ?></td>
				<td><?= intval($value["04-Abril"]) ?></td>
				<td><?= intval($value["05-Mayo"]) ?></td>
				<td><?= intval($value["06-Junio"]) ?></td>
				<td><?= intval($value["Trimestral2"]) ?></td>
				<td><?= ($value["Trimestre2Cualitativo"]) ?></td>
				<td><?= intval($value["07-Julio"]) ?></td>
				<td><?= intval($value["08-Agosto"]) ?></td>
				<td><?= intval($value["09-Septiembre"]) ?></td>
				<td><?= intval($value["Trimestral3"]) ?></td>
				<td><?= ($value["Trimestre3Cualitativo"]) ?></td>
				<td><?= intval($value["10-Octubre"]) ?></td>
				<td><?= intval($value["11-Noviembre"]) ?></td>
				<td><?= intval($value["12-Diciembre"]) ?></td>
				<td><?= intval($value["Trimestral4"]) ?></td>
				<td><?= ($value["Trimestre4Cualitativo"]) ?></td>
				<td><?= intval($value["Anualidad"]) ?></td>
				<td><?= $value["AnualidadCualitativo"] ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>	
	<p>&nbsp;</p>
	
	<table style="width:400px">
		<thead>
		<tr style="background-color:#0a68b4;color:white">
			<th style="width:200px;text-align:left">Nombre</th>
			<th style="width:80px">Simbolo</th>
			<th style="width:60px">Minimo</th>
			<th style="width:60px">Maximo</th>
		</tr>
		</thead>
		<?php
		foreach ($objLeyendas as $value) {
			?>
			<tr>
				<td style="text-align:left"><?= $value["Nombre"] ?></td>
				<td style="text-align:right"><?= $value["reference3"] ?></td>
				<td style="text-align:right"><?= $value["reference1"] ?></td>
				<td style="text-align:right"><?= $value["reference2"] ?></td>
			</tr>
			<?php
		}
		?>
		<tbody>
		</tbody>
	</table>
	
	<p>&nbsp;</p>
	
	<table style="width:100%">
		<tr>
			<td style="text-align:center">
				_____________________________
			</td>
			<td style="text-align:center">
				_____________________________
			</td>
		</tr>
		<tr>
			<td style="text-align:center">
				<p style="font-weight: bold">Firma Director:</p>
			</td>
			<td style="text-align:center">
				<p style="font-weight: bold">Firma Tutor:</p>
			</td>
		</tr>
	</table>
	
	
	<p>&nbsp;</p>
	
	<table style="width:100%">
		<tr>
			<td style="text-align:center">
				<p style="color: #0a68b4; font-weight: bold; font-style: italic;font-weight: bold">"Educamos para redimir"</p>
			</td>
		</tr>
	</table>
	
	
	
	
</body>
</html>
