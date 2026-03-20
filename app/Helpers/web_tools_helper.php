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

function helper_SegmentsKeyValue($objListSegments)
{
    $result = [];

    if (!is_array($objListSegments) || count($objListSegments) === 0) {
        return $result;
    }

    for ($i = 0; $i < count($objListSegments); $i += 2) {
        if (isset($objListSegments[$i + 1])) {
            $result[$objListSegments[$i]] = $objListSegments[$i + 1];
        }
    }

    return $result;
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
					
					if (array_key_exists($i + 1, $objListSegments)) {
						$valueResult =  $objListSegments[$i + 1];
						break;
					}
					else
					{
						$valueResult = "";
						break;
					}
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

function helper_getStringClear($texto)
{
	// Definimos los caracteres a buscar y sus reemplazos
    $buscar 	= ['<', '|', '>', '"', "'"]; // puedes agregar más
    $reemplazar = ['',  '',  '',  '',  '']; // reemplazo correspondiente

    // Reemplazamos todos los caracteres
    $textoReemplazado = str_replace($buscar, $reemplazar, $texto);

    return $textoReemplazado;
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

function helper_getDateTime_Object()
{

    // Obtener la fecha actual
    $fechaActual = new DateTime();

    // Sumar o restar el intervalo de tiempo a la fecha actual
    $fechaActual->modify(APP_HOUR_DIFERENCE_PHP);

    // Devolver el objeto DateTime modificado
    return $fechaActual;
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

function helper_InsertarEntrePartes($original, $insertar, $n) {
    if ($n < 2) return $original; // No tiene sentido dividir en menos de 2 partes

    $longitud = strlen($original);
    $tamanoParte = intdiv($longitud, $n);
    $resto = $longitud % $n;

    $partes = [];
    $inicio = 0;

    for ($i = 0; $i < $n; $i++) {
        $extra = $i < $resto ? 1 : 0; // Reparte el sobrante
        $longitudParte = $tamanoParte + $extra;
        $partes[] = substr($original, $inicio, $longitudParte);
        $inicio += $longitudParte;
    }

    return implode($insertar, $partes);
}

function helper_QuitarAcentos($texto) {
	
	
    $acentos = [
        // Minúsculas
        'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ã' => 'a',
        'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
        'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'õ' => 'o',
        'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
        'ñ' => 'n', 'ç' => 'c',

        // Mayúsculas
        'Á' => 'A', 'À' => 'A', 'Ä' => 'A', 'Â' => 'A', 'Ã' => 'A',
        'É' => 'E', 'È' => 'E', 'Ë' => 'E', 'Ê' => 'E',
        'Í' => 'I', 'Ì' => 'I', 'Ï' => 'I', 'Î' => 'I',
        'Ó' => 'O', 'Ò' => 'O', 'Ö' => 'O', 'Ô' => 'O', 'Õ' => 'O',
        'Ú' => 'U', 'Ù' => 'U', 'Ü' => 'U', 'Û' => 'U',
        'Ñ' => 'N', 'Ç' => 'C'
    ];

    return strtr($texto, $acentos);
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

/***************
/***Si el numero tiene caracteres especiales quitarlos
/***
/**************/
function clearNumero($numero)
{
    // Quitar solo espacios, +, (, ), #, -
    $numeroLimpio = str_replace([' ', '+', '(', ')','-','#'], '', $numero);
    return $numeroLimpio;
}

/***************
/***Si el numero tiene solo 8 caracteres y todos son digitos ponerle el 
/***505 al inicio
/**************/
function getNumberPhone($numero)
{
	
    // Si tiene exactamente 8 caracteres y todos son dígitos
    if (strlen($numero) === 8 && ctype_digit($numero)) {
        return '505' . $numero;
    }

    // Si no cumple, devolver el número tal como viene
    return $numero;
}

/***************
/***Si el numero es un contacto registrado
/***Dejar solo los numeros y si empieza con 505
/***Quintar el 505 del inicio
/**************/
function getNumberPhoneIsContact($numero)
{
	// Validar que contenga .us
    if (strpos($numero, '.us') === false) {
        return $numero;
    }

    // Dejar solo números
    $numeroLimpio = preg_replace('/\D/', '', $numero);

    // Si tiene 11 dígitos y empieza con 505 quitar el 505
    if (strlen($numeroLimpio) == 11 && substr($numeroLimpio, 0, 3) == '505') {
        $numeroLimpio = substr($numeroLimpio, 3);
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

function helper_getHtmlOfModalDialog(
	$name,$idDivBody,$fncallBack,
	$fnShowBotonesCerrar = true,$fnShowBotonesAceptar = true,
	$cssModalContent = ""
)
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
				max-width: 400px;'.$cssModalContent.'
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
	
	 if($fnShowBotonesCerrar == true)
	 {
		 $string = $string.'
			<button class="btnCerrar'.$name.'"  onclick="event.preventDefault();cerrarModal(\''.$name.'\')">Cerrar</button>';
	 }
	 
	 if($fnShowBotonesAceptar == true)
	 {
		 $string = $string.'
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


function helper_getHtmlOfStylePageListReportCSS()
{
	?>
	<style>
		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity h2 {
		  text-align: center;
		  margin-bottom: 30px;
		  color: #333;
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity .reportes-grid {
		  display: grid;
		  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		  gap: 20px;
		  max-width: 1200px;
		  margin: 0 auto;
		  grid-auto-rows: 1fr;
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity .reporte-item {
		  background: #fff;
		  border-radius: 10px;
		  padding: 20px;
		  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		  cursor: pointer;
		  transition: transform 0.2s, box-shadow 0.2s;
		  display: flex;
		  flex-direction: column;
		  justify-content: space-between;
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity .reporte-item:hover {
		  transform: translateY(-5px);
		  box-shadow: 0 6px 12px rgba(0,0,0,0.15);
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity .reporte-titulo {
		  font-size: 12px;
		  font-weight: bold;
		  color: #333;
		  margin: 0 0 10px;
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity .reporte-descripcion {
		  font-size: 12px;
		  color: #555;
		  flex-grow: 1;
		  
		  position: relative;
		  max-width: 300px;       /* ancho contraído */
		  max-height: 60px;       /* alto contraído */
		  overflow: hidden;
		  transition: all 0.3s ease;
		  
		}
		
		.reporte-item.expandido
		{
			background: antiquewhite;
		}
		.reporte-descripcion.expandido {
		  max-width: 100%;        /* ancho expandido */
		  max-height: 600px;      /* alto expandido */
		}

		.reporte-ver-mas {
		  display: inline-block;
		  margin-top: 5px;
		  color: #007bff;
		  cursor: pointer;
		  font-weight: 600;
		  text-decoration: none;
		}

		.reporte-ver-mas:hover {
		  text-decoration: underline;
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity .reporte-rating {
		  margin-top: 15px;
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		.recent-activity .star {
		  color: #ffc107;
		  font-size: 20px;
		  margin-right: 2px;
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		@media (max-width: 900px) {
		  .recent-activity .reportes-grid {
			grid-template-columns: repeat(2, 1fr);
		  }
		}

		/*function helper_getHtmlOfStylePageListReportCSS()*/
		/*estilo para los elementos de reporteria*/
		@media (max-width: 600px) {
		  .recent-activity .reportes-grid {
			grid-template-columns: 1fr;
		  }
		}
		</style>
	<?php
}

function helper_getHtmlOfStylePageListReportJS()
{
	?>
	<!-- ./ page heading -->
	<script>
	document.querySelectorAll('.reporte-item').forEach(item => {
	  item.addEventListener('click', () => {
		const url = item.getAttribute('data-url');
		if (url) {
		  window.open(url, '_blank');
		}
	  });
	});
	</script>
	<?php
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
		"[simbol-computadora]" => "💻",
		"[simbol-engranaje]" => "⚙️",
		"[simbol-llave-inglesa]" => "🔧",
		"[simbol-check]" => "✅",
        "[simbol-pulgar-arriba]" => "👍",
        "[simbol-fuego]" => "🔥",
        "[simbol-risa]" => "😂",
		"[simbol-manos-levantadas]" => "🙌",
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
		"[simbol-estetoscopio]" => "🩺",
		"[simbol-pildora]" => "💊",
		"[simbol-link]" => "🔗",
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



function helper_toCsv(array $data, string $delimiter = ','): string
{
    if (empty($data)) 
	{
        return '';
    }
    $output 							= fopen('php://temp', 'r+');
    // Detectar columnas del primer registro
    $columns 							= array_keys($data[0][0]);
    // Escribir encabezado
    fputcsv($output, $columns, $delimiter);
    // Escribir filas
    foreach ($data[0] as $row) 
	{
        $line 							= [];
        foreach ($columns as $col) 
		{
            $val 						= $row[$col] ?? '';

            if (is_numeric($val)) 
			{
                $val 					= (float)$val; // asegurar n�mero
                if (floor($val) == $val) 
				{
                    // entero
                    $val 				= (string)(int)$val;
                } 
				else 
				{
                    // decimal con 2 decimales
                    $val 				= number_format($val, 2, '.', '');
                }
            }

            $line[]						= $val;
        }
        fputcsv($output, $line, $delimiter);
    }
    rewind($output);
    $csv 								= stream_get_contents($output);
    fclose($output);
    //$csv = "\xEF\xBB\xBF" . $csv;
	$csv = $csv;	
	return $csv;
}


/**
 * Genera un CSV en memoria y devuelve su contenido como string
 *
 * @param array $headers  Encabezados del CSV
 * @param array $mapping  Llaves del dataset que corresponden a cada columna
 * @param array $data     Conjunto de registros (array asociativo)
 * @param string $delimiter  Delimitador del CSV (por defecto coma)
 * @return string
 */
function helper_generarCSV(array $headers, array $mapping, array $data, string $delimiter = ",") {
    // Crear un flujo de memoria
    $handle = fopen("php://temp", "w+");
    
    if ($handle === false) {
        throw new Exception("No se pudo abrir el flujo en memoria.");
    }

    // Escribir encabezados
    fputcsv($handle, $headers, $delimiter);

    // Escribir filas
    foreach ($data as $row) {
        $line = [];
        foreach ($mapping as $key) {
            $line[] = isset($row[$key]) ? $row[$key] : "";
        }
        fputcsv($handle, $line, $delimiter);
    }

    // Rewind para leer desde el inicio
    rewind($handle);

    // Obtener el contenido
    $csv = stream_get_contents($handle);

    // Cerrar flujo
    fclose($handle);

    return $csv;
}



function helper_sendFtp($csvContent, $merchanId, $ftpIp, $ftpUser, $ftpPass, $ftpPort, $fileName, $remoteDir)
{
    $remoteFile 						= $fileName;
    $localFile  						= WRITEPATH . $fileName;	
    // Guardar archivo local
    file_put_contents($localFile, $csvContent);
	// Conectar con SFTP
	// 1. Conectar por SSH
    $connection 						= ssh2_connect($ftpIp, $ftpPort);
    if (!$connection) 
	{
        return "No se pudo conectar al servidor SFTP.";
    }
    // 2. Autenticar
    if (!ssh2_auth_password($connection, $ftpUser, $ftpPass)) 
	{
       return "Fall� la autenticaci�n SFTP.";
    }
    // 3. Inicializar subsistema SFTP
    $sftp 								= ssh2_sftp($connection);	
    if (!$sftp) 
	{
        return "No se pudo inicializar la sesi�n SFTP.";
    }	
	ssh2_sftp_mkdir($sftp, $remoteDir, 0777, true); // crea si no existe
	$remotePath 						= $remoteDir . $remoteFile;

    // 4. Subir archivo
    $stream 							= @fopen("ssh2.sftp://" . intval($sftp) . $remotePath, 'w');	
    if (!$stream) 
	{
        return "No se pudo abrir el archivo remoto: ".$remotePath;
    }	
	$localStream						=@fopen($localFile, 'r');	
	if (!$localStream) 
	{
        return "No se pudo abrir el archivo local:" . $localFile;
    }	
    $writtenBytes 						= stream_copy_to_stream($localStream, $stream);
	fclose($localStream);
    fclose($stream);	
	return "exitoso";
}


function helper_notificationPage($titlePage="Title",$script = "", $summaryPage="Summary", $labelButtonPage="Label", $linkButtonPage="link", $hiddenButtonPage=false, $backgroundHex="#006E98")
{
	$htmlPage 							= '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>' . htmlspecialchars($titlePage) . '</title>
        <style>
            body 
			{
                margin					: 0;
                padding					: 0;
                height					: 100vh;
                display					: flex;
                justify-content			: center;
                align-items				: center;
                background-color		: ' . $backgroundHex . ';
				font-family				: "Montserrat", sans-serif;
				
            }
            .container 
			{
                background-color		: white;
                padding					: 30px;
                border-radius			: 15px;
                box-shadow				: 0 8px 16px rgba(0,0,0,0.2);
                max-width				: 500px;
                text-align				: center;
            }
            h1 
			{
                margin-top				: 0;
                color					: #5CE65C;
				text-transform			: uppercase;
				font-weight				: bold;
            }
            p 
			{
                color					: #5CE65C;
                margin-bottom			: 30px;
				font-size				: 1.2rem;
				font-weight				: bold;
            }
            .btn 
			{
                display					: inline-block;
                background-color		: #0d6efd;
                color					: white;
                padding					: 10px 20px;
                text-decoration			: none;
                border-radius			: 5px;
                font-weight				: bold;
                transition				: background-color 0.3s ease;
				font-size				: 1.1rem;
            }
            .btn:hover {
                background-color		: #004f6d;
            }
			@media (max-width: 768px) 
			{
			  .error-code 
			  {
				font-size				: 2.5rem;
			  }
			  .error-message 
			  {
				font-size				: 1rem;
			  }
			}
        </style>
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link
		  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
		  rel="stylesheet"
		/>
    </head>
    <body>
        <div class="container">
            <h1>'.htmlspecialchars($titlePage).'</h1>
            <p>'.htmlspecialchars($summaryPage).'</p>';
    
	
    if (!$hiddenButtonPage) 
	{
        $htmlPage .= '<a href="' . htmlspecialchars($linkButtonPage) . '" class="btn">' . htmlspecialchars($labelButtonPage) . '</a>';
    }

    $htmlPage .= '</div>'.$script.'</body></html>';
    return $htmlPage;
}

function helper_reemplazarPuntosExceptoUltimo($precieValueAbs)
{
	$precieValueAbs = 
			substr_count($precieValueAbs, '.') > 1 ? 
				preg_replace('/\.(?=.*\.)/', ',', $precieValueAbs) : 
				$precieValueAbs;
				
	return $precieValueAbs;
}

function helper_obtenerPrimeras5Palabras( $texto,  $cantidad  )
{
    if (empty($texto)) {
        return '';
    }

    // Limpiar espacios extra
    $texto 		= trim($texto);

    // Separar por uno o más espacios
    $palabras 	= preg_split('/\s+/', $texto);

    // Tomar máximo 5 elementos
    $primeras 	= array_slice($palabras, 0, $cantidad);

    // Unir nuevamente
    return implode(' ', $primeras);
}


function helper_getAlertThemeSneatBoostrap5_1($title,$type,$mensaje)
{
	$mensajeTransformer = '';
	$mensajeTransformer = $mensajeTransformer.'<div class="alert alert-'.$type.' d-flex" role="alert">';
	$mensajeTransformer = $mensajeTransformer.	'<span class="badge badge-center rounded-pill bg-'.$type.' border-label-'.$type.' p-3 me-2">';
	$mensajeTransformer = $mensajeTransformer.		'<i class="bx bx-desktop fs-6"></i>';
	$mensajeTransformer = $mensajeTransformer.	'</span>';
	$mensajeTransformer = $mensajeTransformer.	'<div class="d-flex flex-column ps-1">';
	$mensajeTransformer = $mensajeTransformer.		'<h6 class="alert-heading d-flex align-items-center fw-bold mb-1" > '.$title.' </h6> ';
	$mensajeTransformer = $mensajeTransformer.		'<span><b>'.$mensaje.'</b></span>';
	$mensajeTransformer = $mensajeTransformer.	'</div>';
	$mensajeTransformer = $mensajeTransformer.'</div>';
	return $mensajeTransformer;
}


function helper_CompareDateTime($stringDateTime1,$stringDateTime2)
{
    $fechaOld     = new DateTime($stringDateTime1);
    $fechaActual  = new DateTime($stringDateTime2);

    // Timestamp
    $tsOld     = $fechaOld->getTimestamp();
    $tsActual  = $fechaActual->getTimestamp();

    // Comparador
    if ($tsOld == $tsActual) {
        $comparador = 0;
    } elseif ($tsOld < $tsActual) {
        $comparador = -1;
    } else {
        $comparador = 1;
    }

    // Diferencia absoluta en segundos
    $diffSegundos 	= abs($tsActual - $tsOld);

    // DateInterval para valores "humanos"
    $interval 		= $fechaOld->diff($fechaActual);

    return [
        'comparador' 	=> $comparador,

        'segundos' 		=> $diffSegundos,
        'minutos'  		=> floor($diffSegundos / 60),
        'horas'    		=> floor($diffSegundos / 3600),
        'dias'     		=> $interval->days,   // total de días
        'meses'    		=> ($interval->y * 12) + $interval->m,
        'anios'    		=> $interval->y,

        'detalle' => [
            'y' => $interval->y,
            'm' => $interval->m,
            'd' => $interval->d,
            'h' => $interval->h,
            'i' => $interval->i,
            's' => $interval->s,
        ]
    ];
}

function helper_convertirLinkAHtml($text, $label) {
    $patron = '/https?:\/\/[^\s]+/';

    return preg_replace_callback($patron, function($coincidencia) use ($label) {
        $url = $coincidencia[0];
        return '<a href="' . $url . '">' . $label . '</a>';
    }, $text);
}


/**
 * Convierte un archivo WebM a formato válido para WhatsApp (OGG/MP3)
 * Elimina el archivo original y deja solo el convertido
 * 
 * @param string $inputPath Ruta completa del archivo WebM a convertir
 * @param string $outputFormat Formato de salida: 'ogg', 'mp3', 'aac' (default: 'ogg')
 * @return array ['success' => bool, 'message' => string, 'file' => string|null]
 */
function helper_convertWebmToWhatsappAudio($inputPath, $outputFormat = 'ogg')
{
    // Validar que el archivo existe
    if (!file_exists($inputPath)) {
        return [
            'success' => false,
            'message' => 'El archivo de entrada no existe: ' . $inputPath,
            'file' => null
        ];
    }

    // Validar formato de salida
    $formatosValidos = ['ogg', 'mp3', 'aac', 'opus'];
    if (!in_array($outputFormat, $formatosValidos)) {
        return [
            'success' => false,
            'message' => 'Formato no válido. Use: ' . implode(', ', $formatosValidos),
            'file' => null
        ];
    }

    // Generar ruta de salida
    $pathInfo = pathinfo($inputPath);
    $outputPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.' . $outputFormat;

    // Buscar FFmpeg en rutas comunes de hosting compartido
    $possiblePaths = [
        '/usr/bin/ffmpeg',
        '/usr/local/bin/ffmpeg',
        'ffmpeg'
    ];

    $ffmpegPath = null;
    foreach ($possiblePaths as $path) {
        if (@file_exists($path) || $path === 'ffmpeg') {
            // Verificar si el comando funciona
            $testCommand = $path . ' -version 2>&1';
            @exec($testCommand, $testOutput, $testReturn);
            if ($testReturn === 0) {
                $ffmpegPath = $path;
                break;
            }
        }
    }

    // Si no se encuentra FFmpeg, intentar método alternativo
    if ($ffmpegPath === null) {
        return helper_convertWebmAlternativo($inputPath, $outputPath, $outputFormat);
    }

    // Configuraciones de conversión por formato
    // OGG con Opus es el más compatible (codec nativo de WebM)
    $configs = [
        'ogg' => '-vn -c:a libopus -b:a 96k -threads 1',
        'opus' => '-vn -c:a libopus -b:a 96k -threads 1',
        'mp3' => '-vn -c:a libmp3lame -ar 44100 -ac 2 -b:a 128k -threads 1',
        'aac' => '-vn -c:a aac -ar 44100 -ac 2 -b:a 128k -threads 1'
    ];

    // Construir comando FFmpeg
    // -y: sobrescribir sin preguntar
    // -threads 1: usar solo 1 thread para no sobrecargar el servidor
    $command = sprintf(
        '%s -y -i %s %s %s 2>&1',
        $ffmpegPath,
        escapeshellarg($inputPath),
        $configs[$outputFormat],
        escapeshellarg($outputPath)
    );

    // Ejecutar conversión
    exec($command, $output, $returnCode);

    // Verificar si la conversión fue exitosa
    if ($returnCode !== 0 || !file_exists($outputPath)) {
        // Si falla, intentar método alternativo (solo cambiar extensión)
        return helper_convertWebmAlternativo($inputPath, $outputPath, $outputFormat);
    }

    // Eliminar archivo original
    if (file_exists($inputPath)) {
        @unlink($inputPath);
    }

    return [
        'success' => true,
        'message' => 'Archivo convertido exitosamente a ' . strtoupper($outputFormat),
        'file' => $outputPath
    ];
}

/**
 * Método alternativo cuando FFmpeg no está disponible
 * Intenta renombrar el archivo o copiarlo con extensión compatible
 * 
 * @param string $inputPath
 * @param string $outputPath
 * @param string $format
 * @return array
 */
function helper_convertWebmAlternativo($inputPath, $outputPath, $format)
{
    // WhatsApp puede aceptar algunos formatos WebM directamente
    // Intentar cambiar extensión a OGG (más compatible)
    $pathInfo = pathinfo($inputPath);
    $oggPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.ogg';

    if (copy($inputPath, $oggPath)) {
        // Eliminar archivo original
        @unlink($inputPath);

        return [
            'success' => true,
            'message' => 'Archivo renombrado a OGG (FFmpeg no disponible)',
            'file' => $oggPath
        ];
    }

    return [
        'success' => false,
        'message' => 'FFmpeg no disponible y conversión alternativa falló',
        'file' => null
    ];
}

/**
 * Verifica si FFmpeg está disponible en el servidor
 * 
 * @return array ['available' => bool, 'path' => string|null, 'version' => string|null]
 */
function helper_checkFFmpegAvailability()
{
    $possiblePaths = [
        '/usr/bin/ffmpeg',
        '/usr/local/bin/ffmpeg',
        'ffmpeg'
    ];

    foreach ($possiblePaths as $path) {
        $command = $path . ' -version 2>&1';
        @exec($command, $output, $returnCode);

        if ($returnCode === 0 && !empty($output)) {
            return [
                'available' => true,
                'path' => $path,
                'version' => $output[0] ?? 'Unknown'
            ];
        }
    }

    return [
        'available' => false,
        'path' => null,
        'version' => null
    ];
}


/**
 * Renderiza un string HTML como vista temporal usando el sistema de vistas de CodeIgniter
 * 
 * @param string $htmlString String HTML con variables de CodeIgniter {variable}
 * @param array $data Array asociativo con los datos para reemplazar en la vista
 * @return string HTML resultante con las variables reemplazadas
 * 
 * @example
 * $html = '<h1>{titulo}</h1><p>{contenido}</p>';
 * $data = ['titulo' => 'Hola', 'contenido' => 'Mundo'];
 * $resultado = helper_RenderStringAsView($html, $data);
 */
function helper_RenderStringAsView($htmlString, $data = [])
{
    // Generar nombre único para el archivo temporal
    $tempFileName = 'core_view_temporal/temp_view_' . uniqid() . '.php';
    $tempFilePath = APPPATH . 'Views/' . $tempFileName;
    
    // Crear directorio si no existe
    if (!is_dir(APPPATH . 'Views/core_view_temporal/')) {
        mkdir(APPPATH . 'Views/core_view_temporal/', 0755, true);
    }
    
    // Guardar el string como archivo temporal
    file_put_contents($tempFilePath, $htmlString);
    
    // Renderizar usando el sistema de vistas de CodeIgniter
    $html = view($tempFileName, $data);
    
    // Eliminar el archivo temporal
    unlink($tempFilePath);
    
    return $html;
}

function helper_validarPremioLoto($notif, $objTMDR, $objItem, $item)
{
    $isPremio           = false;
    $isPremiadoGeneral  = false;
    $multiplicador      = 1;

    $notiPremio    = strtoupper(str_replace(' ', '', $notif->phoneFrom));
    $numeroJugado  = strtoupper(str_replace(' ', '', $objTMDR[0]->reference2));

    // =========================
    // LOTO DIARIA
    // =========================
    if ($objItem->reference2 == "lotoDiaria") {

        $respuesta = [
            "gano"             => false,
            "numero_ganador"   => null,
            "multiplicador"    => 1
        ];

        // Número ganador
        if (preg_match('/\d{2}/', $notiPremio, $match)) {
            $respuesta["numero_ganador"] = $match[0];
        }

        // Verificar si ganó
        if ($respuesta["numero_ganador"] != null) {
            if ($numeroJugado == $respuesta["numero_ganador"]) {
                $respuesta["gano"] = true;
            }
        }

        // Multiplicador
        if (preg_match('/=\s*(\d+)/', $notiPremio, $match)) {
            $respuesta["multiplicador"] = (int)$match[1];
        }

        if (
            str_contains((string)$notif->to, (string)$item->componentItemID) &&
            $respuesta["gano"] == true
        ) {
            $isPremio = true;
            $isPremiadoGeneral = true;
            $multiplicador = $respuesta["multiplicador"];
        }
    }

    // =========================
    // LOTO FECHAS
    // =========================
    if ($objItem->reference2 == "lotoFechas") {
        if (
            str_contains((string)$notif->to, (string)$item->componentItemID) &&
            $notiPremio == $numeroJugado
        ) {
            $isPremio = true;
            $isPremiadoGeneral = true;
        }
    }

    // =========================
    // LOTO JUEGA 3
    // =========================
    if ($objItem->reference2 == "lotoJuega3") {
        if (
            str_contains((string)$notif->to, (string)$item->componentItemID) &&
            $notiPremio == $numeroJugado
        ) {
            $isPremio = true;
            $isPremiadoGeneral = true;
        }
    }

    // =========================
    // LOTO PREMIA 2
    // =========================
    if ($objItem->reference2 == "lotoPremia2") {

        $partes = explode("-", $notiPremio);

        if (
            str_contains((string)$notif->to, (string)$item->componentItemID) &&
            in_array($numeroJugado, $partes)
        ) {
            $isPremio = true;
            $isPremiadoGeneral = true;
        }
    }

    return [
        "isPremio" => $isPremio,
        "isPremiadoGeneral" => $isPremiadoGeneral,
        "multiplicador" => $multiplicador
    ];
}


?>


