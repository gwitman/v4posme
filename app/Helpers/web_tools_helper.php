<?php
//posme:2023-02-27

/**
 * Returns a GUIDv4 string
 *
 * Uses the best cryptographically secure method
 * for all supported pltforms with fallback to an older,
 * less secure version.
 *
 * @param bool $trim
 * @return string
 */
function GUIDv4($trim = true)
{
    // Windows
    if (function_exists('com_create_guid') === true) {
        if ($trim === true)
            return trim(com_create_guid(), '{}');
        else
            return com_create_guid();
    }

    // OSX/Linux
    if (function_exists('openssl_random_pseudo_bytes') === true) {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Fallback (PHP 4.2+)
    mt_srand((float)microtime() * 10000);
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    $guidv4 = $lbrace .
        substr($charid,  0,  8) . $hyphen .
        substr($charid,  8,  4) . $hyphen .
        substr($charid, 12,  4) . $hyphen .
        substr($charid, 16,  4) . $hyphen .
        substr($charid, 20, 12) .
        $rbrace;
    return $guidv4;
}
function helper_ObtenerClavePrimaria($db, $table)
{
    $query = $db->query("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = ? 
        AND CONSTRAINT_NAME = 'PRIMARY'
    ", [$table]);

    $row = $query->getRow();
    return $row ? $row->COLUMN_NAME : null;
}

function helper_SegmentsValue($objListSegments, $key)
{
    $valueResult = "";


    if (!$objListSegments)
        $valueResult = "";
    else {
        if (count($objListSegments) <= 0)
            $valueResult = "";
        else {
            for ($i = 0; $i < count($objListSegments); $i++) {
                if ($objListSegments[$i] == $key) {
                    $valueResult =  $objListSegments[$i + 1];
                    break;
                }
            }
        }
    }


    return $valueResult;
}

function helper_SegmentsByIndex($objListSegments, $i, $variable)
{

    $result = "";
    $index  = $i + 1;
    $count  = count($objListSegments);


    //si variable es null
    if (is_null($variable) && $index < $count) {
        $result =  $objListSegments[$index];
    } else if (isset($variable) && $index < $count) {
        $result =  $objListSegments[$index];
    } else if ($variable == "" && $index < $count) {
        $result =  $objListSegments[$index];
    } else if (empty($variable) && $index < $count) {
        $result =  $objListSegments[$index];
    } else {
        $result = $variable;
    }


    return $result;
}

function helper_RequestGetValue($value, $default)
{
    if (is_null($value))
        return $default;

    if (empty($value))
        return $default;

    if (!isset($value))
        return $default;

    return $value;
}

function helper_RequestGetValueObjet($obj, $field, $default)
{
    if (is_null($obj))
        return $default;


    if (!isset($obj))
        return $default;

    if (empty($obj))
        return $default;


    if (is_null($obj->$field))
        return $default;

    if (!isset($obj->$field))
        return $default;

    if (empty($obj->$field))
        return $default;


    return $obj->$field;
}

function helper_DateToSpanish($date_, $format)
{

    $english     = NULL;
    $spanish       = NULL;

    if ($format == "Y-F" or $format == "Y-F-d" or $format == "F") {
        $english     = array("JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER");
        $spanish       = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
    } else {
        $english     = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $spanish       = array("ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGT", "SEP", "OCT", "NOV", "DIC");
    }
    $return_     = str_replace($english, $spanish, strtoupper(date_format(date_create($date_), $format)));
    return strtoupper($return_);
}

function helper_getDate()
{

    // Obtener la fecha actual
    $fechaActual = new DateTime();

    // Sumar o restar el intervalo de tiempo a la fecha actual
    $fechaActual->modify(APP_HOUR_DIFERENCE_PHP);

    // Devolver el objeto DateTime modificado
    return $fechaActual->format('Y-m-d 00:00:00');
}

function helper_getDateMoreOneMonth()
{
    $date     = date("Y-m-d");
    $date     = \DateTime::createFromFormat('Y-m-d', $date);
    $date   = date_add($date, date_interval_create_from_date_string('1 months'));
    $date    = date_format($date, "Y-m-d");
    return $date;
}

function helper_getDateTime()
{

    // Obtener la fecha actual
    $fechaActual = new DateTime();

    // Sumar o restar el intervalo de tiempo a la fecha actual
    $fechaActual->modify(APP_HOUR_DIFERENCE_PHP);

    // Devolver el objeto DateTime modificado
    return $fechaActual->format('Y-m-d H:i:s');
}

function helper_PrimerDiaDelMes()
{
    $date = date("Y-m") . "-01";
    return $date;
}
function helper_PrimerDiaDelYear()
{
    $date = date("Y") . "-01-01";
    return $date;
}
function helper_UltimoDiaDelMes()
{
    $date     = date("Y-m") . "-01";
    $date     = \DateTime::createFromFormat('Y-m-d', $date);
    $date   = date_add($date, date_interval_create_from_date_string('1 months'));
    $date   = date_add($date, date_interval_create_from_date_string('-1 days'));
    $date    = date_format($date, "Y-m-d 23:59:59");
    return $date;
}
function helper_UltimoDiaDelYear()
{
    $date     = date("Y") . "-01-01";
    $date     = \DateTime::createFromFormat('Y-m-d', $date);
    $date   = date_add($date, date_interval_create_from_date_string('1 years'));
    $date   = date_add($date, date_interval_create_from_date_string('-1 days'));
    $date    = date_format($date, "Y-m-d 23:59:59");
    return $date;
}

function helper_GetHtmlControlHora($id, $valueHora)
{
    $array = explode(" ", $valueHora);
    $array = explode(":", $array[1]);
    $horaSource        = intval($array[0]);
    $hora             = $array[0];
    $hora             = intval($hora) > 12 ? $hora - 12 : $hora;
    $minuto           = $array[1];
    $segundo          = $array[2];



    $html = "";
    $html = $html . "<div class='row'>";
    $html = $html . "<div class='col-lg-4'>";
    $html = $html . "<select name='" . $id . "_hora' id='" . $id . "_hora' class='select2'>";
    for ($i = 0; $i <= 12; $i++) {
        if ($i == $hora)
            $html = $html . "<option value='" . substr("000" . $i, -2)   . "' selected >" . substr("000" . $i, -2)  . "</option>";
        else
            $html = $html . "<option value='" . substr("000" . $i, -2)   . "'>" .  substr("000" . $i, -2)   . "</option>";
    }
    $html = $html . "</select>";
    $html = $html . "</div>";


    $html = $html . "<div class='col-lg-4'>";
    $html = $html . "<select name='" . $id . "_minuto' id='" . $id . "_minuto' class='select2'>";
    for ($i = 0; $i <= 59; $i++) {
        if ($i == $minuto)
            $html = $html . "<option value='" . substr("000" . $i, -2)  . "' selected >" . substr("000" . $i, -2)  . "</option>";
        else
            $html = $html . "<option value='" . substr("000" . $i, -2)  . "'>" . substr("000" . $i, -2)  . "</option>";
    }
    $html = $html . "</select>";
    $html = $html . "</div>";



    $html = $html . "<div class='col-lg-4'>";
    $html = $html . "<select name='" . $id . "_zona' id='" . $id . "_zona' class='select2'>";
    $html = $html . "<option value='AM' " . ($horaSource < 12 ? "selected" : "") . ">AM</option>";
    $html = $html . "<option value='MD' " . ($horaSource == 12 ? "selected" : "") . ">MD</option>";
    $html = $html . "<option value='PM' " . ($horaSource > 12 ? "selected" : "") . ">PM</option>";
    $html = $html . "</select>";
    $html = $html . "</div>";


    $html = $html . "</div>";

    return $html;
}
function helper_GetColorSinRiesgo($valor)
{
    $valor = strtolower($valor);
    if ($valor == "saneado")
        return "red";
    else if ($valor == "recuperacion normal" || $valor == "cancelado")
        return "green";
    else if ($valor == "")
        return "blue";
    else if ($valor == "")
        return "yellow";
    else
        return "black";
}

function helper_StrPadString($string, $quantity, $char)
{
    return str_pad($string, $quantity, $char, STR_PAD_LEFT);
}

function helper_StringToNumber($string)
{
    return str_replace(",", "", $string);
}

function helper_GetSr($Sexo)
{
    if ($Sexo == "FEMENINO")
        return "Sra.";
    else
        return "Sr.";
}

function helper_GetEl($Sexo)
{
    if ($Sexo == "FEMENINO")
        return "la";
    else
        return "el";
}

//------    Máxima cifra soportada: 999,999,999,999,999,999.99
function helper_GetLetras($xcifra, $moneda, $centavos)
{
    $xarray         = array(0 => "Cero", 1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA", 100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS");
    $xcifra         = trim($xcifra);
    $xlength         = strlen($xcifra);
    $xpos_punto     = strpos($xcifra, ".");
    $xaux_int         = $xcifra;
    $xdecimales     = "00";

    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra     = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int     = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX         = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena     = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux         = substr($XAUX, $xz * 6, 6);
        $xi         = 0;
        $xlimite     = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit         = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }

            $x3digitos     = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux         = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = GetLetrasSufix($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = GetLetrasSufix($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            } else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = GetLetrasSufix($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena .= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena .= " DE";

        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena .= "UN BILLON ";
                    else
                        $xcadena .= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena .= "UN MILLON ";
                    else
                        $xcadena .= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO $moneda $xdecimales/100 $centavos";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN $moneda $xdecimales/100 $centavos";
                    }
                    if ($xcifra >= 2) {
                        $xcadena .= " $moneda $xdecimales/100 $centavos"; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}

function GetLetrasSufix($xx)
{ // esta función regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}

function helper_GetFechaNacimiento($fechaString)
{
    // Convertir la cadena a un objeto de fecha
    $fechaNacimiento = DateTime::createFromFormat('Y-m-d', $fechaString);

    // Obtener la fecha actual
    $fechaActual = new DateTime();

    // Calcular la diferencia en años
    $diferencia = $fechaActual->diff($fechaNacimiento);

    // Devolver la fecha de nacimiento
    return $diferencia->y;
}

//END FUNCTION
function helper_GetNumberLetras($xcifra, $moneda, $centavos)
{
    $xarray         = array(0 => "Cero", 1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA", 100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS");
    $xcifra         = trim($xcifra);
    $xlength         = strlen($xcifra);
    $xpos_punto     = strpos($xcifra, ".");
    $xaux_int         = $xcifra;
    $xdecimales     = "00";

    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra     = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int     = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }

    $XAUX         = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena     = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux         = substr($XAUX, $xz * 6, 6);
        $xi         = 0;
        $xlimite     = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit         = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }

            $x3digitos     = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux         = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = GetLetrasSufix($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = GetLetrasSufix($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            } else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = GetLetrasSufix($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO

        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena .= " DE";

        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena .= " DE";

        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena .= "UN BILLON ";
                    else
                        $xcadena .= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena .= "UN MILLON ";
                    else
                        $xcadena .= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        //$xcadena = "CERO $moneda $xdecimales/100 $centavos"; original
                        $xcadena = "CERO $moneda  $centavos";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        //$xcadena = "UN $moneda $xdecimales/100 $centavos"; original
                        $xcadena = "UN $moneda  $centavos";
                    }
                    if ($xcifra >= 2) {
                        //$xcadena.= " $moneda $xdecimales/100 $centavos"; original
                        $xcadena .= " $moneda  $centavos";
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}



function emptyDir($dir)
{
    if (is_dir($dir)) {
        $scn = scandir($dir);
        foreach ($scn as $files) {
            if ($files !== '.') {
                if ($files !== '..') {
                    if (!is_dir($dir . '/' . $files)) {
                        unlink($dir . '/' . $files);
                    } else {
                        emptyDir($dir . '/' . $files);
                        rmdir($dir . '/' . $files);
                    }
                }
            }
        }
    }
}

function deleteDir($dir)
{

    foreach (glob($dir . '/' . '*') as $file) {
        if (is_dir($file)) {


            deleteDir($file);
        } else {

            unlink($file);
        }
    }
    emptyDir($dir);
    rmdir($dir);
}

function clearNumero($numero)
{
    // Eliminar caracteres no deseados
    $numeroLimpio = preg_replace("/[^0-9]/", "", $numero);

    // Verificar si el número resultante no comienza con "505"
    if (substr($numeroLimpio, 0, 3) !== "505") {
        // Si no comienza con "505", concatenar "505" al inicio
        $numeroLimpio = "505" . $numeroLimpio;
    }

    return $numeroLimpio;
}



function helper_getParameterFiltered($objListCompanyParameter, $parameterName)
{
    $result = current(array_filter($objListCompanyParameter, function ($obj) use ($parameterName) {
        return $obj->name === $parameterName;
    }));

    return $result;
}


function helper_convertToUTF8($texto)
{
	if (!mb_check_encoding($texto, 'UTF-8')) {
		$texto = utf8_encode($texto);
	}
	
	return $texto;
}

function helper_getHtmlOfPageLanding()
{
	echo '
	<div 
		class="isloading-overlay"
		id="divLoandingCustom"
		style="position:fixed; left:0; top:0; z-index: 10000; background: white; width: 100%; height: 1090px;"	
	>
		 <div style="width: 100%; height: 4px; background: #ddd; position: relative; overflow: hidden; border-radius: 10px;">
			<div style="width: 0; height: 100%; background: #007bff; position: absolute; animation: carga 2s infinite;"></div>
		</div>
	</div>
	
	<style>
		@keyframes carga {
			0% { width: 0%; }
			50% { width: 100%; }
			100% { width: 0%; }
		}
	</style>

	';
}

function helper_getHtmlOfModalDialog($name,$idDivBody,$fncallBack,$fnShowBotones)
{
	$string  =  
	'
	<style>
			.parrafoModalCustomPosMe {
			  background-color	: #ffeb3b; /* Amarillo vibrante */
			  color				: #333; /* Texto oscuro para contraste */
			  padding			: 15px 20px;
			  border-radius		: 8px;
			  box-shadow		: 0 2px 8px rgba(0,0,0,0.2);
			  font-size			: 16px;
			  font-weight		: bold;
			  text-align		: center;
			  margin-top		: 20px;
			  transition		: background-color 0.3s ease;
			}

			.parrafoModalCustomPosMe:hover 
			{
			  background-color: #ffc107; /* Un tono más oscuro al pasar el mouse */
			  cursor: pointer;
			}
			
			.modal-customer1-'.$name.' {
				display		: none;
				position	: fixed;
				top			: 0;
				left		: 0;
				width		: 100%;
				height		: 100%;
				background-color: rgba(0, 0, 0, 0.6); /* Fondo semitransparente */
				z-index: 1000;
				justify-content: center;
				align-items: center;
			}

			.modal-content-customer1-'.$name.' {
				background-color: #fff;
				padding: 20px;
				border-radius: 8px;
				box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
				text-align: center;
				width: 80%;
				max-width: 400px;
			}

			.modal-content-customer1-'.$name.' h3 {
				color: #f44336; /* Rojo */
				margin: 0;
			}

			.modal-content-customer1-'.$name.' p {
				margin: 10px 0;
			}

			.modal-content-customer1-'.$name.' .btnCerrar'.$name.' {
				background-color: #f44336;
				color: #fff;
				border: none;
				padding: 10px 20px;
				border-radius: 5px;
				cursor: pointer;
			}
			
			.modal-content-customer1-'.$name.' .btnAceptar'.$name.' {
				background-color: #28a745;
				color: #fff;
				border: none;
				padding: 10px 20px;
				border-radius: 5px;
				cursor: pointer;
			}

			.modal-content-customer1-'.$name.' .btnCerrar'.$name.':hover {
				background-color: #d32f2f;
			}
			.modal-content-customer1-'.$name.' .btnAceptar'.$name.':hover {
				background-color: #1e7e34;
			}
	  </style>	  
	  
	     
	  <div id="'.$name.'" class="modal-customer1-'.$name.'">
	  	<div class="modal-content-customer1-'.$name.'" >
			<div id="divBody'.$name.'">
				
			</div>
	';
	
	 if($fnShowBotones == true)
	 {
		 $string = $string.'
			<button class="btnCerrar'.$name.'"  onclick="event.preventDefault();cerrarModal(\''.$name.'\')">Cerrar</button>
			<button class="btnAceptar'.$name.'" onclick="event.preventDefault();'.$fncallBack.'(this)">Aceptar</button>';
	 }
	 
	 $string = $string.'
	  	</div>
	  </div>
	
	  <script>
		
		function mostrarModal(name) {
			document.getElementById(name).style.display = "flex";
		}

		function cerrarModal(name) {
			document.getElementById(name).style.display = "none";
		}		

		
		
		var div'.$name.' 			= document.getElementById("'.$idDivBody.'");
        var destino'.$name.' 		= document.getElementById("divBody'.$name.'"); 
		div'.$name.'.style.display 	= "block";
        destino'.$name.'.appendChild(div'.$name.');
		
	  </script>
	';
	
	echo $string;
}

function helper_getCssWidthInvoiceMobile()
{
	echo '
		<style>
			@media only screen and (max-width: 480px) 
			{
				#rowCssPrincipal{
					margin:0px 0px 0px 0px !important
				}
			}
			/*encabezado*/
			/*
			#heading
			{
				margin: 0 -25px 0px -25px !important
			}
			*/
			
			#heading
			{
				margin: 0 0 0 0 !important
			}
		</style>
	';
}
function replaceSimbol($string)
{
	$string = nl2br(htmlspecialchars(mb_convert_encoding($string, "UTF-8", "Windows-1252")));
	$string = str_replace("[simbol-enter]", 
"
", $string);


	$emojiMap = [
        "[simbol-carita-feliz]" => "😊",
        "[simbol-cono]" => "🎉",
        "[simbol-carita-estrellada]" => "🤩",
        "[simbol-pastel]" => "🎂",
        "[simbol-enter]" => "\n",
        "[simbol-corazon]" => "❤️",
        "[simbol-pulgar-arriba]" => "👍",
        "[simbol-fuego]" => "🔥",
        "[simbol-risa]" => "😂",
        "[simbol-aplauso]" => "👏",
        "[simbol-ok]" => "👌",
        "[simbol-llorando]" => "😭",
        "[simbol-gafas-sol]" => "😎",
        "[simbol-beso]" => "😘",
        "[simbol-cara-enamorada]" => "😍",
        "[simbol-mano-orando]" => "🙏",
        "[simbol-cohete]" => "🚀",
        "[simbol-globo]" => "🎈",
        "[simbol-trofeo]" => "🏆",
        "[simbol-estrellas]" => "✨",
        "[simbol-cara-sorprendida]" => "😲",
        "[simbol-cara-triste]" => "😢",
        "[simbol-cara-enojada]" => "😡",
        "[simbol-cara-riendo]" => "🤣",
        "[simbol-cara-dormida]" => "😴",
        "[simbol-cara-enferma]" => "🤒",
        "[simbol-cara-con-mascaras]" => "😷",
        "[simbol-ojos]" => "👀",
        "[simbol-mano-saludo]" => "👋",
        "[simbol-cara-diablo]" => "😈",
        "[simbol-cara-angel]" => "😇",
        "[simbol-mano-abajo]" => "👇",
        "[simbol-mano-arriba]" => "👆",
        "[simbol-mano-derecha]" => "👉",
        "[simbol-mano-izquierda]" => "👈",
        "[simbol-mano-puno]" => "✊",
        "[simbol-mano-victoria]" => "✌️",
        "[simbol-cara-payaso]" => "🤡",
        "[simbol-mono]" => "🐵",
        "[simbol-perro]" => "🐶",
        "[simbol-gato]" => "🐱",
        "[simbol-leon]" => "🦁",
        "[simbol-tigre]" => "🐯",
        "[simbol-zorro]" => "🦊",
        "[simbol-panda]" => "🐼",
        "[simbol-oso]" => "🐻",
        "[simbol-elefante]" => "🐘",
        "[simbol-coche]" => "🚗",
        "[simbol-avion]" => "✈️",
        "[simbol-barco]" => "⛵",
        "[simbol-bicicleta]" => "🚲",
        "[simbol-tren]" => "🚆",
        "[simbol-autobus]" => "🚌",
        "[simbol-camion]" => "🚛",
        "[simbol-globo-aerostatico]" => "🎈",
        "[simbol-futbol]" => "⚽",
        "[simbol-baloncesto]" => "🏀",
        "[simbol-beisbol]" => "⚾",
        "[simbol-tenis]" => "🎾",
        "[simbol-billar]" => "🎱",
        "[simbol-medalla]" => "🏅",
        "[simbol-boton-play]" => "▶️",
        "[simbol-boton-pausa]" => "⏸️",
        "[simbol-boton-stop]" => "⏹️",
        "[simbol-boton-record]" => "⏺️",
        "[simbol-montana]" => "⛰️",
        "[simbol-sol]" => "☀️",
        "[simbol-luna]" => "🌙",
        "[simbol-estrella]" => "⭐",
        "[simbol-arcoiris]" => "🌈",
        "[simbol-trueno]" => "⚡",
        "[simbol-nieve]" => "❄️",
        "[simbol-lluvia]" => "🌧️",
        "[simbol-arbol]" => "🌳",
        "[simbol-flor]" => "🌸",
        "[simbol-hoja]" => "🍃",
        "[simbol-cafe]" => "☕",
        "[simbol-copa]" => "🍷",
        "[simbol-cerveza]" => "🍺",
        "[simbol-pizza]" => "🍕",
        "[simbol-hamburguesa]" => "🍔",
        "[simbol-papas]" => "🍟",
        "[simbol-helado]" => "🍦",
        "[simbol-pastel]" => "🎂",
        "[simbol-donut]" => "🍩",
        "[simbol-banco]" => "🏦",
        "[simbol-hospital]" => "🏥",
        "[simbol-escuela]" => "🏫",
        "[simbol-hotel]" => "🏨",
        "[simbol-fabrica]" => "🏭",
        "[simbol-billete]" => "💵",
        "[simbol-moneda]" => "💰",
        "[simbol-grafico]" => "📈",
        "[simbol-bombilla]" => "💡",
        "[simbol-llave]" => "🔑",
        "[simbol-candado]" => "🔒",
        "[simbol-camara]" => "📷",
        "[simbol-computadora]" => "💻",
        "[simbol-telefono]" => "📱",
        "[simbol-reloj]" => "⌚",
        "[simbol-microfono]" => "🎤",
        "[simbol-mensajes]" => "💬",
        "[simbol-nota]" => "📝",
        "[simbol-estrella-brillante]" => "🌟",
        "[simbol-arena]" => "🏖️",
        "[simbol-guitarra]" => "🎸",
        "[simbol-casco]" => "⛑️",
        "[simbol-martillo]" => "🔨",
        "[simbol-clavo]" => "🔩",
        "[simbol-alarma]" => "🚨",
        "[simbol-lupa]" => "🔍",
        "[simbol-mochila]" => "🎒",
        "[simbol-carta]" => "💌",
        "[simbol-correo]" => "📧",
        "[simbol-estrella-filtrada]" => "🌠",
        "[simbol-sombrero]" => "🎩",
        "[simbol-limón]" => "🍋",
        "[simbol-cereza]" => "🍒",
        "[simbol-manzana]" => "🍎",
        "[simbol-uvas]" => "🍇",
        "[simbol-banano]" => "🍌",
        "[simbol-pina]" => "🍍",
        "[simbol-frambuesa]" => "🍇",
        "[simbol-papel]" => "📝",
        "[simbol-ratón]" => "🐭",
        "[simbol-palomitas]" => "🍿",
        "[simbol-ancla]" => "⚓",
        "[simbol-globo-terrestre]" => "🌍",
        "[simbol-circuito]" => "🛸",
        "[simbol-papelera]" => "🗑️",
        "[simbol-camino]" => "🛤️",
        "[simbol-flauta]" => "🎶",
        "[simbol-muestra]" => "🎭",
        "[simbol-seguridad]" => "🛡️",
        "[simbol-camara-vigilancia]" => "🎥",
        "[simbol-diamante]" => "💎",
        "[simbol-cartel]" => "📛",
        "[simbol-cereza]" => "🍒",
        "[simbol-lentes]" => "👓",
        "[simbol-carro]" => "🚙",
        "[simbol-pulpo]" => "🐙",
        "[simbol-puente]" => "🌉",
        "[simbol-piedra]" => "🪨",
        "[simbol-sierra]" => "🪚",
        "[simbol-pin]" => "📍",
        "[simbol-brazo]" => "💪",
        "[simbol-corona]" => "👑",
        "[simbol-oveja]" => "🐑",
        "[simbol-pavo]" => "🦃",
        "[simbol-canguro]" => "🦘",
        "[simbol-cachorro]" => "🐕",
        "[simbol-rana]" => "🐸",
        "[simbol-pez]" => "🐟",
        "[simbol-delfin]" => "🐬",
        "[simbol-ballena]" => "🐋",
        "[simbol-estrella-cadente]" => "🌠",
        "[simbol-huevo]" => "🥚",
        "[simbol-repollo]" => "🥬",
        "[simbol-repollo-rojo]" => "🥯",
        "[simbol-brocoli]" => "🥦",
        "[simbol-escarola]" => "🥒",
        "[simbol-pimiento]" => "🌶️",
        "[simbol-zanahoria]" => "🥕",
		"[simbol-cine]" => "🎬",
		"[simbol-television]" => "📺",
		"[simbol-telefono-movil]" => "📲",
		"[simbol-bicicleta-hombre]" => "🚴‍♂️",
		"[simbol-puerta]" => "🚪",
		"[simbol-viento]" => "💨",
		"[simbol-chocolate]" => "🍫",
		"[simbol-megafono]" => "📢",
		"[simbol-cien]" => "💯",
		"[simbol-sobre]" => "📩",
		"[simbol-marcador]" => "📍",
		"[simbol-exclamacion]" => "❗",
		"[simbol-a-acento]" => "á",
		"[simbol-e-acento]" => "é",
		"[simbol-i-acento]" => "í",
		"[simbol-o-acento]" => "ó",
		"[simbol-u-acento]" => "ú"

    ];
    
    $string = str_replace(array_keys($emojiMap), array_values($emojiMap), $string);
	
	// Reemplazar cualquier palabra con corchetes que no haya sido reemplazada
    $string = preg_replace('/\[[^\]]+\]/', "🌟", $string);
    return $string;
}

?>