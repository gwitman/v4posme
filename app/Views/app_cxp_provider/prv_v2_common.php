<style>
    /* ==========================================================
       Proveedores - complementos del estandar pv2 (prefijo prv)
       Usada por: app_cxp_provider news y edit.
       Requiere: posme_v2_style, posme_v2_common y afx_v2_common.
       ========================================================== */

    /* Telefonos y correos siguen siendo tabla en telefono: el encabezado explica Tipo y Primario.
       Las lineas de credito pasan a tarjetas (ver prvLineCreatedRow) porque tienen muchas columnas */
    @media (max-width:767px) {
        .pv2 #tb_detail_phone, .pv2 #tb_detail_email { display:table; }
        .pv2 #tb_detail_phone thead, .pv2 #tb_detail_email thead { display:table-header-group; }
        .pv2 #tb_detail_phone tbody, .pv2 #tb_detail_email tbody { display:table-row-group; }
        .pv2 #tb_detail_phone td, .pv2 #tb_detail_email td { word-break:break-word; }
    }
</style>
<script>
    /* ==========================================================
       Proveedores - interfaz compartida entre nuevo y editar.
       No contiene logica de negocio.
       ========================================================== */

    //Celda de monto: el campo oculto lleva el numero puro y el texto visible el formato 1,000.00
    function prvMontoCell(data, nameField){
        return '<input type="hidden" value="' + afxMontoPuro(data) + '" name="' + nameField + '" />' + pv2Fmt(data);
    }

    /* Prepara cada fila nueva de lineas de credito para verse como tarjeta en telefono:
       DataTables la genera sin row_razon ni data-label. No altera la seleccion de filas */
    function prvLineCreatedRow(nRow, aData, iDataIndex){
        var columnas = this.fnSettings().aoColumns;
        var celdas   = $(nRow).addClass("row_razon").children("td");
        var visibles = 0;
        for(var i = 0; i < columnas.length; i++){
            if(columnas[i].bVisible){
                celdas.eq(visibles).attr("data-label", columnas[i].sTitle);
                visibles++;
            }
        }
    }

    //Enlaza lo comun de nuevo y editar. Llamar una sola vez: afxBindCommon no debe repetirse
    function prvBindCommon(){
        afxBindCommon();

        //Los botones de la pantalla son enlaces con href="#": evita que la pagina salte al inicio
        $(document).on("click",".pv2 a.btn[href='#']",function(e){
            e.preventDefault();
        });
    }
</script>
