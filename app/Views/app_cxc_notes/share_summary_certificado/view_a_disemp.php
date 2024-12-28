<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Notas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="dsemp"/>
    <style>
        @page {
            size: 14in 8.5in; /* Tamaño carta horizontal */
            margin: 0; /* Márgenes */
        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Estilo del contenedor */
        .container {
            width: 100%; /* Ancho del contenedor */
            margin: 0 auto; /* Centra el contenedor */
        }

        /* Estilo de la fila */
        .row {
            display: flex; /* Usar flexbox para las filas */
            flex-wrap: wrap; /* Permite que las columnas se envuelvan */
            margin: -10px; /* Espacio negativo para compensar el padding de las columnas */
        }

        /* Estilo de las columnas */
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
            padding: 5px; /* Espaciado interno de las columnas */
            box-sizing: border-box; /* Incluye padding en el ancho total */
        }

        .col-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }

        .col-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }

        .col-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .col-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }

        .col-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }

        .col-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }

        .col-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }

        .col-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }

        .col-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }

        .col-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        .header {

        }

        .header img {
            height: 75px;
        }

        .title {
            margin-top: -50px;
        }

        .title h1, .title h3 {
            text-align: center;
        }

        .title h1 {
            font-size: 24px;
            margin: 0;
        }

        .title h3 {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .student-info {
            margin-bottom: 20px;
            font-size: small;
        }

        .student-info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: x-small;
            font-weight: bold;
            font-family: Consolas, monaco, monospace;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #044389;
            color: white;
        }

        .firmas-container {
            display: flex;
            justify-content: center; /* Distribuye el espacio entre las firmas */
            align-items: flex-end; /* Alinea las firmas en la parte inferior */
            margin-top: 150px; /* Espacio superior para separar del contenido anterior */
        }

        .firma {
            text-align: center; /* Centra el texto dentro de cada firma */
            width: 20%; /* Ancho de cada firma */
            margin: 0 250px;
        }

        .linea-firma {
            border-top: 1px solid #000; /* Línea para la firma */
            width: 100%; /* Ancho de la línea */
            margin: 20px 0; /* Espacio entre la línea y el texto */
        }

        .nombre {
            margin-top: 10px; /* Espacio entre la firma y el nombre */
            font-weight: bold; /* Resalta el nombre */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <?php
        $path = PATH_FILE_OF_APP_ROOT . '/img/logos/' . $objLogo->value;

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        ?>
        <img src="<?= $base64 ?>" alt="Logo del Centro Educativo"/>
        <div class="title">
            <h1>Certificado de Notas</h1>
            <h3><?= $objCompany->name ?></h3>
            <h3><?= $objCompany->address ?></h3>
            <h3><?= $objTelefono->value ?></h3>
        </div>
    </div>

    <div class="student-info col-6">
        <div class="container">
            <div class="row">
                <div class="col-3" style="text-align: left;"><strong>Nombre del Estudiante: </strong></div>
                <div class="col-9"><?= $objAlumno["customerNumber"] ?> <?= $objAlumno["Nombre"] ?></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-3" style="text-align: left"><strong>Grado:</strong></div>
                <div class="col-9"><?= $objAlumno["Grado"] ?></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-3" style="text-align: left"><strong>Año:</strong></div>
                <div class="col-9"><?= $objAlumno["Ano"] ?></div>
            </div>
        </div>
    </div>
    <table>
        <thead>
        <tr>
            <th style="text-align: left;min-width: 150px">Materia</th>
            <th>Ene</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>I Trimestre</th>
            <th>I Cualitativo</th>
            <th>Abr</th>
            <th>May</th>
            <th>Jun</th>
            <th>II Trimestre</th>
            <th>II Cualitativo</th>
            <th>Jul</th>
            <th>Ags</th>
            <th>Sept</th>
            <th>III Trimestre</th>
            <th>III Cualitativo</th>
            <th>Oct</th>
            <th>Nov</th>
            <th>Dic</th>
            <th>IV Trimestre</th>
            <th>IV Cualitativo</th>
            <th>Anualidad</th>
            <th>Cualitativo</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($objDetail as $value): ?>
            <tr>
                <td style="text-align: left; min-width: 150px"><?= $value["Materia"] ?></td>
                <td><?= intval($value["01-Enero"]) ?></td>
                <td><?= intval($value["02-Febrero"]) ?></td>
                <td><?= intval($value["03-Marzo"]) ?></td>
                <td><?= intval($value["Trimestral1"]) ?></td>
                <td><?= intval($value["Trimestre1Cualitativo"]) ?></td>
                <td><?= intval($value["04-Abril"]) ?></td>
                <td><?= intval($value["05-Mayo"]) ?></td>
                <td><?= intval($value["06-Junio"]) ?></td>
                <td><?= intval($value["Trimestral2"]) ?></td>
                <td><?= intval($value["Trimestre2Cualitativo"]) ?></td>
                <td><?= intval($value["07-Julio"]) ?></td>
                <td><?= intval($value["08-Agosto"]) ?></td>
                <td><?= intval($value["09-Septiembre"]) ?></td>
                <td><?= intval($value["Trimestral3"]) ?></td>
                <td><?= intval($value["Trimestre3Cualitativo"]) ?></td>
                <td><?= intval($value["10-Octubre"]) ?></td>
                <td><?= intval($value["11-Noviembre"]) ?></td>
                <td><?= intval($value["12-Diciembre"]) ?></td>
                <td><?= intval($value["Trimestral4"]) ?></td>
                <td><?= intval($value["Trimestre4Cualitativo"]) ?></td>
                <td><?= intval($value["Anualidad"]) ?></td>
                <td><?= $value["AnualidadCualitativo"] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <div style="width: 20%">
        <table>
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Simbolo</th>
                <th>Minimo</th>
                <th>Maximo</th>
            </tr>
            </thead>
            <?php
            foreach ($objLeyendas as $value) {
                ?>
                <tr>
                    <td style="text-align: left"><?= $value["Nombre"] ?></td>
                    <td><?= $value["reference3"] ?></td>
                    <td><?= $value["reference1"] ?></td>
                    <td><?= $value["reference2"] ?></td>
                </tr>
                <?php
            }
            ?>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="firmas-container">
        <div class="firma">
            <div class="linea-firma"></div>
            <div class="nombre">Firma del Director</div>
        </div>
        <div class="firma">
            <div class="linea-firma"></div>
            <div class="nombre">Firma del Tutor</div>
        </div>
    </div>
</div>
<!--Ingresar slogan-->
<p>&nbsp;</p>
<div class="container">
    <div class="row">
        <div class="col-12" style="text-align: center">
            <p style="color: #0a68b4; font-weight: bold; font-style: italic">"Educamos para redimir"</p>
        </div>
    </div>
</div>
</body>
</html>
