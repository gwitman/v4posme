<?php
$title          = $reporting->name;
$encabezados    = '';
$contador       = 0;

foreach ($filtros as $key => $value) {
    $encabezados .= "$key: $value";
    $contador++;

    // Si no es múltiplo de 2 y no es el último
    if ($contador % 2 !== 0 && $contador !== count($filtros)) {
        $encabezados .= "  |  ";
    }

    // Cada dos pares, salto de línea
    if ($contador % 2 === 0) {
        $encabezados .= "<br>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title; ?></title>
    <link rel="icon" type="image/x-icon" href="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/img/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="application-name" content="distemper"/>

    <?php
    echo helper_reporteGeneralCreateStyle();
    ?>

</head>
<body style="font-family:monospace;font-size:smaller;margin:0">
<div style="margin: 15px">

<?php
echo helper_reporteGeneralCreateEncabezado(
        $title,
        $objCompany->name,
        2,
        $encabezados,
        "",
        "",
        '100%'
);
$resultado      = [];
$configColumn   = [];
foreach ($groupReportResult as $key => $value) {
    $tableTitle     = "";
    $tableWidth     = '0px';
    $reportStyle    = 'horizontal';
    foreach ($value as $k => $v) {
        if ($k == 0) {
            $configColumn["$k"]["IdTable"]  = "table".$k;
            $tableWidth                     = $v->tableWidth;
            $reportStyle                    = strlen($v->reportStyle) > 0 ? $v->reportStyle : "horizontal";
        }
        $configColumn["$k"]["Titulo"]                   = $v->title;
        $configColumn["$k"]["TituloFoot"]               = "";
        $configColumn["$k"]["FiledSouce"]               = $v->source;
        $configColumn["$k"]["Formato"]                  = $v->type;
        $configColumn["$k"]["Width"]                    = $v->width;
        $configColumn["$k"]["Total"]                    = $v->sumary == "true";
        $configColumn["$k"]["Colspan"] 					= "1" ;
        $configColumn["$k"]["Style"] 					= "" ;
        $configColumn["$k"]["Alineacion"] 				= "Left";
        $configColumn["$k"]["TotalValor"] 				= 0;
        $configColumn["$k"]["CantidadRegistro"]			= 0;
        $configColumn["$k"]["FiledSoucePrefix"] 		= trim($v->prefix);
        $configColumn["$k"]["AutoIncrement"] 			= False;
        $configColumn["$k"]["IsUrl"] 					= False;
        $configColumn["$k"]["FiledSouceUrl"] 			= "";
        $configColumn["$k"]["Url"] 						= "";
        $configColumn["$k"]["FiledSoucePrefixCustom"] 	= "";
        $configColumn["$k"]["Promedio"] 				= False ;
        $configColumn["$k"]["Alineacion"] 				= $v->type == "Number" ? "Right": "Left";

        $tableTitle = $v->tableTitle;
    }
    if ($reportStyle == 'horizontal'){
        $resultado[] = helper_reporteGeneralCreateTable($objDetail[$key - 1], $configColumn, $tableWidth, $tableTitle, NULL);
    }else{
        $resultado[] = helper_reporteGeneralCreateTableVertical($objDetail[$key - 1][0], $configColumn, null, $tableWidth);
    }

}
if (count($resultado) > 0) {
    foreach ($resultado as $result) {

        echo '<br/>';
        if (is_array($result)) {
            echo $result["table"];
            echo '<br/>';
            helper_reporteGeneralCreateFirma('', $result["columnas"], $result["width"]);
        }else{
            echo $result;
        }
        echo '<br/>';
    }
}
?>

</div>
</body>
</html>