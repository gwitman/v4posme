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
        .container {
            display: flex;
            justify-content: space-between; /* Espacio entre columnas */
            padding: 20px;
        }
        .column {
            flex: 1; /* Cada columna ocupa el mismo espacio */
            margin: 0 10px; /* Espacio entre columnas */
            padding: 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .signature {
            margin: 20px 0;
        }
        .signature .line {
            border-top: 1px solid black;
            margin: 0 auto;
            width: 50%;
        }
        .signature p {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }
	</style>
</head>
<body style="margin:0px;  font-family: system-ui; ">
	<?php
    header('Content-Type: image/png');
	$path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objLogo->value;

	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
	?>
	
	<table style="width:100%">
		<tr>
			<td style="width:33%"><img src="<?= $base64 ?>" style="width:150px" alt="Logo del Centro Educativo"/></td>
			<td style="width:33%; text-align:center ">
				<p style="margin:0px; font-size: 48pt" ><?= $objCompany->namePublic ?></p>
				<p style="margin:0px; font-size: 28pt" ><?= $objCompany->name ?></p>
				<p style="margin:0px" >INFORME DE CALIFICACIONES</p>
				<p style="margin:0px" ><u>AÑO LECTIVO <?= $objAlumno["Ano"] ?></u></p>
			</td>
			<td style="width:33%">
				<p>&nbsp;</p>
			</td>
		</tr>
	</table>
	
	<p>&nbsp;</p>
	<div style="width: 50%; float: left">
        <table style="width:100%">
            <tr>
                <td style="width:200px;font-weight: bold">Código:</td>
                <td><?= $objAlumno["customerNumber"] ?></td>
            </tr>
            <tr>
                <td style="width:200px;font-weight: bold">Alumno(a):</td>
                <td><?= $objAlumno["Nombre"] ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold">Código Mined:</td>
                <td></td>
            </tr>
        </table>
    </div>

    <div style="width: 50%; float: right">
        <table style="width:100%">
            <tr>
                <td style="font-weight: bold">Grado:</td>
                <td><?= $objAlumno["Grado"] ?></td>
            </tr>
            <tr>
                <td style="width:200px;font-weight: bold">Turno:</td>
                <td></td>
            </tr>
        </table>
    </div>

	<p>&nbsp;</p>
	<p style="text-align: center; color: green; font-style: italic; font-weight: bold">
        Francisco Avidán Medina Garmendia - <?= date('d/m/y h:i:s A')?>
    </p>
	<table style="table-layout: fixed;width:100%">
		<thead>
		<tr style="background-color:#0a68b4;color:white" >
			<th style="text-align:left;white-space: nowrap;width: 300px">Materia</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">I C</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">II C</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">III C</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">IV C</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">N</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">FVAL</th>
			<th style="text-align:left;white-space: nowrap;width: 120px">SC</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($objDetail as $value): ?>
			<tr>
				<td style="text-align: left;"><?= $value["Materia"] ?></td>
				<td><?= intval($value["Trimestral1"]) ?></td>
				<td><?= ($value["Trimestre1Cualitativo"]) ?></td>
				<td><?= intval($value["Trimestral2"]) ?></td>
				<td><?= ($value["Trimestre2Cualitativo"]) ?></td>
				<td><?= intval($value["Trimestral3"]) ?></td>
				<td><?= ($value["Trimestre3Cualitativo"]) ?></td>
				<td><?= intval($value["Trimestral4"]) ?></td>
				<td><?= ($value["Trimestre4Cualitativo"]) ?></td>
				<td><?= intval($value["Anualidad"]) ?></td>
				<td><?= $value["AnualidadCualitativo"] ?></td>
                <td></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>	
	<p>&nbsp;</p>
	<p style="text-align: center; font-weight: bold">DADO EN LA CIUDAD DE LEÓN A LOS <?= date('d')?> DÍAS DEL MES DE <?= helper_DateToSpanish(date('F'),'F')?> DEL <?= date('Y') ?></p>
	<p style="text-align: center; font-weight: bold">Observaciones</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div class="container">
        <div class="column">
            <div class="signature">
                <p>&nbsp;</p>
                <div class="line"></div>
                <p style="text-align: center">Firma Profesor Guía</p>
            </div>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <div class="signature">
                <div class="line"></div>
                <p style="text-align: center">Lic. FRANCISCO AVIDÁN MEDINA GARMENDIA</p>
                <p style="text-align: center">DIRECTOR</p>
            </div>
        </div>
        <div class="column">
            <div style="display: flex; align-items: center;justify-content: center">
                <img src="<?= APP_URL_RESOURCE_CSS_JS; ?>/resource/img/qr_code.jpg" alt="QR" />
            </div>
        </div>
        <div class="column">
            <p style="font-size: 18pt; font-weight: bold; text-align: right; margin-top: -15px">ESCALA DE CALIFICACIONES</p>
            <table style="width:100%">
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
        </div>
    </div>
	
	<p>&nbsp;</p>
	



	<p>&nbsp;</p>
	
	<table style="width:100%">
		<tr>
			<td style="text-align:center">
				<p style="color: #0a68b4; font-weight: bold; font-style: italic;font-weight: bold">"PARA GENTE IMPORTANTE, PARA GENTE COMO USTED"</p>
			</td>
		</tr>
	</table>
	
	
	
	
</body>
</html>
