<style>
    /* porcentaje de utilidad junto al precio */
    .ppr-porc { display:flex; align-items:center; gap:4px; margin-top:4px; }
    .ppr-porc input { flex:1 1 auto; min-width:0; text-align:right; }
    .ppr-porc span { flex:0 0 auto; font-weight:700; color:#6b7280; }
    .ppr-porc input[readonly] { background:#f3f4f6; }
    /* telefono: campo comodo al tacto y sin desbordar la celda */
    @media (max-width:767px){
        .ppr-porc { width:100%; }
        .ppr-porc input { min-height:38px; font-size:16px; }
    }
</style>
<script>
    /* ==========================================================
       Precio <-> porcentaje de utilidad sobre el costo (prefijo ppr)
       Regla (igual que el servidor): % = (precio * 100 / costo) - 100
                                      precio = costo * (100 + %) / 100
       Sin costo no se calcula el %: el precio se escribe en valor.
       Usada por: app_inventory_item (nuevo/editar) y
                  app_inventory_inputunpost (nuevo/editar y popup mas informacion)
       ========================================================== */

    //Numero puro a partir de texto con comas. Nunca devuelve NaN
    function pprNum(valor){
        if(valor === null || valor === undefined) return 0;
        var numero = parseFloat(String(valor).replace(/,/g,"").replace(/[^0-9.\-]/g,""));
        return isNaN(numero) ? 0 : numero;
    }

    //Porcentaje a partir del precio. "" si no hay costo
    function pprPorcDesdePrecio(precio, costo){
        costo = pprNum(costo);
        if(costo <= 0) return "";
        var porc = ((pprNum(precio) * 100) / costo) - 100;
        return (Math.round(porc * 100) / 100).toFixed(2);
    }

    //Precio a partir del porcentaje. null si no hay costo
    function pprPrecioDesdePorc(porc, costo){
        costo = pprNum(costo);
        if(costo <= 0) return null;
        var precio = costo * (100 + pprNum(porc)) / 100;
        return (Math.round(precio * 100) / 100).toFixed(2);
    }

    //HTML del campo % (sin name: no se envia, el servidor recalcula el % con el precio)
    function pprHtmlPorc(precio, costo, clase){
        var porc = pprPorcDesdePrecio(precio, costo);
        var attr = porc === "" ? " readonly='readonly' placeholder='Sin costo'" : "";
        return "<div class='ppr-porc'><input type='text' autocomplete='off' class='" + clase + "' value='" + porc + "' title='% de utilidad sobre el costo'" + attr + "><span>%</span></div>";
    }

    //Recalcula el % de un par precio/% (se usa al cargar y cuando cambia el costo)
    function pprRefrescarPar($precio, $porc, costo){
        var porc = pprPorcDesdePrecio($precio.val(), costo);
        $porc.val(porc);
        if(porc === "") $porc.attr({ readonly : "readonly", placeholder : "Sin costo" });
        else            $porc.removeAttr("readonly").attr("placeholder","");
    }

    /**
     * Enlaza por delegacion los pares precio/% dentro de un contenedor.
     * fila      : selector del contenedor del par (ej. "#tblPrecios tr.row_price")
     * selPrecio : selector del input precio dentro del contenedor
     * selPorc   : selector del input % dentro del contenedor
     * fnCosto   : function($fila) que devuelve el costo a usar
     * fnPrecioCambiado (opcional): function($precio) al terminar de editar el % (para recalculos de la pantalla)
     */
    function pprEnlazar(fila, selPrecio, selPorc, fnCosto, fnPrecioCambiado){
        //Escribe el precio -> calcula el %
        $(document).on("input", fila + " " + selPrecio, function(){
            var $fila = $(this).closest(fila);
            pprRefrescarPar($(this), $fila.find(selPorc), fnCosto($fila));
        });
        //Escribe el % -> calcula el precio
        $(document).on("input", fila + " " + selPorc, function(){
            var $fila  = $(this).closest(fila);
            var precio = pprPrecioDesdePorc($(this).val(), fnCosto($fila));
            if(precio !== null && $.trim($(this).val()) !== "")
                $fila.find(selPrecio).val(precio);
        });
        //Al salir del % se avisa a la pantalla que el precio cambio
        $(document).on("change", fila + " " + selPorc, function(){
            if(typeof fnPrecioCambiado === "function")
                fnPrecioCambiado($(this).closest(fila).find(selPrecio));
        });
    }
</script>
