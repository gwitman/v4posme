<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Notas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="dsemp"/
</head>
<body style="margin:0; font-family: serif; width: 100%">
	<?php
    header('Content-Type: image/png');
	$path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objLogo->value;
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $pathQr = PATH_FILE_OF_APP_ROOT . '/img/qr_code.png';
    $typeQr = pathinfo($pathQr, PATHINFO_EXTENSION);
    $dataQr = file_get_contents($pathQr);
    $base64Qr = 'data:image/' . $typeQr . ';base64,' . base64_encode($dataQr);
	?>
	
	<table style="width:100%; margin: 0; padding: 0">
        <tr>
            <td style="display: flex; align-items: center; justify-content: center;">
                <!-- Contenedor de imagen y texto -->
                <div style="display: flex; align-items: center; width: 100%;">
                    <!-- Imagen a la izquierda -->
                    <div style="flex: 0 0 auto; margin-right: 20px;">
                        <img src="<?= $base64 ?>" style="width: 150px; height: auto;" alt="Logo del Centro Educativo"/>
                    </div>
                    <!-- Texto centrado -->
                    <div style="text-align: center; flex: 1; margin-top: -50px">
                        <p style="margin: -2px; font-size: 24pt; font-weight: bold;"><?= $objCompany->namePublic ?></p>
                        <p style="margin: -2px; font-size: 12pt; font-weight: bold;"><?= $objCompany->name ?></p>
                        <p style="margin: -2px; font-size: 8pt; font-weight: bold;">INFORME DE CALIFICACIONES</p>
                        <p style="margin: -2px; font-size: 8pt; font-weight: bold;">
                            <u>AÑO LECTIVO <?= $objAlumno["Ano"] ?></u>
                        </p>
                    </div>
                </div>
            </td>
        </tr>

    </table>
	
	<p>&nbsp;</p>
    <table style="width: 100%; border-collapse: collapse; font-size: 9pt; margin: 0; padding: 0">
        <tr>
            <!-- Columna izquierda -->
            <td style="width: 60%; vertical-align: top; ">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 20%; font-weight: bold;">Código:</td>
                        <td style="width: 80%;"><?= $objAlumno["customerNumber"] ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%; font-weight: bold;">Alumno(a):</td>
                        <td style="width: 80%;"><?= $objAlumno["Nombre"] ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%; font-weight: bold;">Código Mined:</td>
                        <td style="width: 80%;"></td>
                    </tr>
                </table>
            </td>
            <!-- Columna derecha -->
            <td style="width: 40%; vertical-align: top; padding-left: 10px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 150px;font-weight: bold;">Grado:</td>
                        <td><?= $objAlumno["Grado"] ?></td>
                    </tr>
                    <tr>
                        <td style="width: 150px; font-weight: bold;">Turno:</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <p>&nbsp;</p>
	<p style="text-align: center; color: green; font-style: italic; font-weight: bold; font-size: 8pt">
        Francisco Avidán Medina Garmendia - <?= date('d/m/y h:i:s A')?>
    </p>
	<table style="table-layout: auto; width:100%; font-size: 8pt; margin: 0; padding: 0">
		<thead>
		<tr style="background-color:#0a68b4;color:white" >
			<th style="text-align:left;white-space: nowrap;width: 25%">Materia</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">I C</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">II C</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">III C</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">IV C</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">VAL</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">N</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">FVAL</th>
			<th style="text-align:left;white-space: nowrap;width: 6.82%">SC</th>
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
	<p style="text-align: center; font-weight: bold; font-size: 9pt">DADO EN LA CIUDAD DE LEÓN A LOS <?= date('d')?> DÍAS DEL MES DE <?= helper_DateToSpanish(date('F'),'F')?> DEL <?= date('Y') ?></p>
	<p style="text-align: center; font-weight: bold; font-size: 9pt">Observaciones</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <table style="width: 100%; margin: 0; padding: 0; font-size: 9pt">
        <tr>
            <!-- Columna 1: Firmas -->
            <td style="width: 37.5%; vertical-align: top; margin: 0;">
                <br>
                <div style="margin-bottom: 50px;">
                    <div style="border-bottom: 1px solid #000;"></div>
                    <p style="text-align: center; margin-top: -3px">Firma Profesor Guía</p>
                </div>
                <div>
                    <div style="border-bottom: 1px solid #000;"></div>
                    <p style="text-align: center; margin-top: -3px; font-size: 7pt">
                        Lic. FRANCISCO AVIDÁN MEDINA GARMENDIA
                        <br />
                        DIRECTOR
                    </p>
                </div>
            </td>

            <!-- Columna 2: Código QR -->
            <td style="width: 25%; vertical-align: top; text-align: center;">
                <img src="<?= $base64Qr ?>" alt="QR" style="margin-top:10px; width: 70%" />
            </td>

            <!-- Columna 3: Escala de Calificaciones -->
            <td style="width: 37.5%; vertical-align: top;">
                <p style="font-size: 10pt; font-weight: bold; text-align: right;margin-bottom: 5px; margin-top:-10px">
                    ESCALA DE CALIFICACIONES
                </p>
                <table style="width: 100%; font-size: 8pt; margin-top:0px">
                    <thead>
                    <tr style="background-color: #0a68b4; color: white;">
                        <th style="width: 33%; text-align: left; padding: 0;">Nombre</th>
                        <th style="width: 22.33%; text-align: center; padding: 0;">Simbolo</th>
                        <th style="width: 22.33%; text-align: center; padding: 0;">Mínimo</th>
                        <th style="width: 22.33%; text-align: center; padding: 0;">Máximo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($objLeyendas as $value): ?>
                        <tr>
                            <td style="text-align: left; padding: 0;"><?= $value["Nombre"] ?></td>
                            <td style="text-align: center; padding: 0;"><?= $value["reference3"] ?></td>
                            <td style="text-align: center; padding: 0;"><?= $value["reference1"] ?></td>
                            <td style="text-align: center; padding: 0;"><?= $value["reference2"] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

	<table style="width:100%; font-size: 10pt">
		<tr>
			<td style="text-align:center">
				<p style="color: #0a68b4; font-weight: bold; font-style: italic;font-weight: bold">"PARA GENTE IMPORTANTE, PARA GENTE COMO USTED"</p>
			</td>
		</tr>
	</table>
	
</body>
</html>
