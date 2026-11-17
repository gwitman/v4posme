<?php
//posme:2026-11-16
namespace App\Libraries;

/**
 * Calculo y validacion del descuento de la factura (por linea o global).
 *
 * Solo se usa cuando el parametro INVOICE_BILLING_DISCOUNT_BY_ITEM = "true".
 * Funciones puras, sin acceso a base de datos, pensadas para pruebas PHPUnit.
 *
 * Reglas:
 * - Modos excluyentes: "line" (descuento por producto), "global" (descuento sobre el subtotal) o "none".
 * - bruto_i = quantity * price ; neto_i = bruto_i - descuento_i.
 * - Global: el monto se prorratea por linea con redondeo a 2 decimales y el residuo
 *   se ajusta en la ultima linea con bruto > 0, de modo que la suma sea igual al monto global.
 * - El IVA se calcula sobre el neto; el impuesto de servicio (tax2) sigue sobre el bruto.
 */
class core_web_invoice_discount
{
    const MODO_NINGUNO      = "none";
    const MODO_LINEA        = "line";
    const MODO_GLOBAL       = "global";
    const TOLERANCIA        = 0.01;
    const MENSAJE_COMBINADO = "No se puede combinar descuento por producto y descuento global";
    const MENSAJE_CLAVE     = "El descuento requiere una clave de autorizacion valida";

    /**
     * Calcula el detalle y los totales de la factura aplicando el descuento.
     *
     * @param array  $lineas      [['quantity','price','ivaPct','taxServicesPct','discountLine','discountPct'], ...] (se respetan las llaves)
     * @param string $modo        "none" | "line" | "global"
     * @param float  $montoGlobal Monto del descuento global (solo modo "global"; en "line" debe ser 0)
     * @param float  $pctGlobal   Porcentaje global informado por la pantalla (solo para validar que no se combine en modo "line")
     * @return array ['lineas' => [key => ['bruto','discount','discountLine','discountPct','neto','tax1Unit','tax2Unit','unitaryAmount','amount']],
     *                'totales' => ['subAmount','tax1','tax2','discount','pctGlobal','amount','total']]
     */
    function calcularDetalle(array $lineas, string $modo, float $montoGlobal, float $pctGlobal = 0)
    {
        $lineas = $this->normalizarLineas($lineas);
        $this->validar($lineas, $modo, $montoGlobal, $pctGlobal);

        $subtotal = 0;
        foreach ($lineas as $linea) {
            $subtotal = $subtotal + ($linea["quantity"] * $linea["price"]);
        }

        // Descuento por linea, segun el modo
        $descuentos = array();
        foreach ($lineas as $key => $linea) {
            $descuentos[$key] = 0;
        }

        if ($modo == self::MODO_LINEA) {
            foreach ($lineas as $key => $linea) {
                $bruto            = $linea["quantity"] * $linea["price"];
                $descuentos[$key] = min(round($linea["discountLine"], 2), $bruto);
            }
        } else if ($modo == self::MODO_GLOBAL) {
            $descuentos = $this->prorratearGlobal($lineas, $montoGlobal, $subtotal);
        }

        // Calculo por linea
        $resultLineas = array();
        $totales      = array(
            "subAmount" => 0,
            "tax1"      => 0,
            "tax2"      => 0,
            "discount"  => 0,
            "pctGlobal" => 0,
            "amount"    => 0,
            "total"     => 0
        );

        foreach ($lineas as $key => $linea) {
            $quantity      = $linea["quantity"];
            $price         = $linea["price"];
            $bruto         = $quantity * $price;
            $descuento     = $descuentos[$key];
            $neto          = $bruto - $descuento;

            // IVA sobre el neto (unitario); servicio sobre el bruto (unitario)
            $tax1Unit      = $quantity > 0 ? ($neto / $quantity) * $linea["ivaPct"] : 0;
            $tax2Unit      = $price * $linea["taxServicesPct"];
            $unitaryAmount = $price + $tax1Unit;
            $amount        = $quantity * ($unitaryAmount + $tax2Unit);

            $esLinea       = ($modo == self::MODO_LINEA && $descuento > 0);

            $resultLineas[$key] = array(
                "bruto"         => $bruto,
                "discount"      => $descuento,
                "discountLine"  => $modo == self::MODO_LINEA ? $descuento : 0,
                "discountPct"   => $esLinea ? trim((string) $linea["discountPct"]) : "",
                "neto"          => $neto,
                "tax1Unit"      => $tax1Unit,
                "tax2Unit"      => $tax2Unit,
                "unitaryAmount" => $unitaryAmount,
                "amount"        => $amount
            );

            $totales["subAmount"] = $totales["subAmount"] + $bruto;
            $totales["tax1"]      = $totales["tax1"] + ($tax1Unit * $quantity);
            $totales["tax2"]      = $totales["tax2"] + ($tax2Unit * $quantity);
            $totales["discount"]  = $totales["discount"] + $descuento;
            $totales["amount"]    = $totales["amount"] + $amount;
        }

        $totales["discount"]  = round($totales["discount"], 2);
        // Porcentaje global = monto global realmente aplicado / subtotal
        $totales["pctGlobal"] = ($modo == self::MODO_GLOBAL && $subtotal > 0) ? ($totales["discount"] / $subtotal * 100) : 0;
        $totales["total"]     = $totales["amount"] - $totales["discount"];

        return array(
            "lineas"  => $resultLineas,
            "totales" => $totales
        );
    }

    /**
     * Valida las reglas del descuento; lanza \Exception con un mensaje claro si algo no cumple.
     *
     * @param array  $lineas      Mismo formato de calcularDetalle
     * @param string $modo        "none" | "line" | "global"
     * @param float  $montoGlobal Monto del descuento global
     * @param float  $pctGlobal   Porcentaje global informado
     * @return bool true si todo es valido
     */
    function validar(array $lineas, string $modo, float $montoGlobal, float $pctGlobal = 0)
    {
        $lineas = $this->normalizarLineas($lineas);

        if (!in_array($modo, array(self::MODO_NINGUNO, self::MODO_LINEA, self::MODO_GLOBAL), true))
            throw new \Exception("El modo de descuento '" . $modo . "' no es valido");

        $subtotal          = 0;
        $hayDescuentoLinea = false;
        foreach ($lineas as $linea) {
            $subtotal = $subtotal + ($linea["quantity"] * $linea["price"]);
            if ($linea["discountLine"] > 0)
                $hayDescuentoLinea = true;
        }

        // Los modos son excluyentes
        if ($modo == self::MODO_LINEA && ($montoGlobal > 0 || $pctGlobal > 0))
            throw new \Exception(self::MENSAJE_COMBINADO);

        if ($modo == self::MODO_GLOBAL && $hayDescuentoLinea)
            throw new \Exception(self::MENSAJE_COMBINADO);

        if ($modo == self::MODO_LINEA) {
            foreach ($lineas as $linea) {
                $bruto = $linea["quantity"] * $linea["price"];

                if ($linea["discountLine"] < 0)
                    throw new \Exception("El descuento de un producto no puede ser negativo");

                if (round($linea["discountLine"], 2) > $bruto + self::TOLERANCIA)
                    throw new \Exception("El descuento de un producto no puede ser mayor que su subtotal");

                $pct = trim((string) $linea["discountPct"]);
                if ($pct !== "" && (!is_numeric($pct) || (float) $pct < 0 || (float) $pct > 100))
                    throw new \Exception("El porcentaje de descuento de un producto debe estar entre 0 y 100");
            }
        }

        if ($modo == self::MODO_GLOBAL) {
            if ($montoGlobal < 0)
                throw new \Exception("El descuento global no puede ser negativo");

            if ($montoGlobal > $subtotal + self::TOLERANCIA)
                throw new \Exception("El descuento global no puede ser mayor que el subtotal de la factura");
        }

        return true;
    }

    /**
     * Verifica la clave de autorizacion del descuento.
     * Si el parametro de clave esta vacio no se exige clave.
     *
     * @param string|null $claveParametro Valor del parametro INVOICE_BILLING_DISCOUNT_PASSWORD
     * @param string|null $claveIngresada Clave enviada por el usuario
     * @return bool true si la clave es valida o no se exige
     */
    function verificarClave($claveParametro, $claveIngresada)
    {
        $claveParametro = (string) $claveParametro;
        if (trim($claveParametro) === "")
            return true;

        return hash_equals($claveParametro, (string) $claveIngresada);
    }

    /**
     * Exige la clave cuando hay descuento; lanza \Exception si no es valida.
     *
     * @param float       $descuentoTotal Descuento total de la factura
     * @param string|null $claveParametro Valor del parametro INVOICE_BILLING_DISCOUNT_PASSWORD
     * @param string|null $claveIngresada Clave enviada por el usuario
     * @return bool true si no se requiere o es valida
     */
    function validarClave(float $descuentoTotal, $claveParametro, $claveIngresada)
    {
        if ($descuentoTotal <= 0)
            return true;

        if (!$this->verificarClave($claveParametro, $claveIngresada))
            throw new \Exception(self::MENSAJE_CLAVE);

        return true;
    }

    /**
     * Indica si dos montos de descuento son distintos (tolerancia de 0.01).
     *
     * @param float $anterior Valor guardado
     * @param float $nuevo    Valor recalculado
     * @return bool true si cambio
     */
    function descuentoCambio(float $anterior, float $nuevo)
    {
        return abs($anterior - $nuevo) > self::TOLERANCIA;
    }

    /**
     * Prorratea el descuento global por linea en proporcion al bruto.
     * El residuo de redondeo se ajusta en la ultima linea con bruto > 0.
     *
     * @param array $lineas      Lineas normalizadas
     * @param float $montoGlobal Monto global a repartir
     * @param float $subtotal    Suma de brutos
     * @return array [key => descuento]
     */
    private function prorratearGlobal(array $lineas, float $montoGlobal, float $subtotal)
    {
        $descuentos = array();
        $ultimaKey  = null;
        $suma       = 0;

        foreach ($lineas as $key => $linea) {
            $descuentos[$key] = 0;
        }

        if ($subtotal <= 0 || $montoGlobal <= 0)
            return $descuentos;

        $montoGlobal = min(round($montoGlobal, 2), round($subtotal, 2));

        foreach ($lineas as $key => $linea) {
            $bruto = $linea["quantity"] * $linea["price"];
            if ($bruto <= 0)
                continue;

            $descuentos[$key] = round($bruto * $montoGlobal / $subtotal, 2);
            $suma             = $suma + $descuentos[$key];
            $ultimaKey        = $key;
        }

        // Ajuste del residuo para que la suma sea exactamente el monto global:
        // se aplica desde la ultima linea con bruto > 0 hacia arriba, sin que ninguna
        // linea quede con descuento < 0 ni mayor a su bruto.
        if ($ultimaKey !== null) {
            $residuo = round($montoGlobal - $suma, 2);
            foreach (array_reverse(array_keys($lineas)) as $key) {
                if ($residuo == 0)
                    break;

                $tope = round($lineas[$key]["quantity"] * $lineas[$key]["price"], 2);
                if ($tope <= 0)
                    continue;

                $nuevo            = min(max(round($descuentos[$key] + $residuo, 2), 0), $tope);
                $aplicado         = round($nuevo - $descuentos[$key], 2);
                $descuentos[$key] = $nuevo;
                $residuo          = round($residuo - $aplicado, 2);
            }
        }

        return $descuentos;
    }

    /**
     * Convierte a numero los valores de cada linea (acepta strings con separador de miles ",").
     *
     * @param array $lineas Lineas recibidas
     * @return array Lineas con valores numericos, conservando las llaves
     */
    private function normalizarLineas(array $lineas)
    {
        $result = array();
        foreach ($lineas as $key => $linea) {
            $result[$key] = array(
                "quantity"       => $this->aNumero(isset($linea["quantity"]) ? $linea["quantity"] : 0),
                "price"          => $this->aNumero(isset($linea["price"]) ? $linea["price"] : 0),
                "ivaPct"         => $this->aNumero(isset($linea["ivaPct"]) ? $linea["ivaPct"] : 0),
                "taxServicesPct" => $this->aNumero(isset($linea["taxServicesPct"]) ? $linea["taxServicesPct"] : 0),
                "discountLine"   => $this->aNumero(isset($linea["discountLine"]) ? $linea["discountLine"] : 0),
                "discountPct"    => isset($linea["discountPct"]) && $linea["discountPct"] !== null ? (string) $linea["discountPct"] : ""
            );
        }
        return $result;
    }

    /**
     * Convierte un valor a float quitando separadores de miles.
     *
     * @param mixed $valor Valor a convertir
     * @return float
     */
    private function aNumero($valor)
    {
        if ($valor === null || $valor === "")
            return 0.0;

        return (float) str_replace(",", "", (string) $valor);
    }
}
