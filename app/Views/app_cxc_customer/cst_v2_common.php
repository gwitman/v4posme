<style>
    /* ==========================================================
       Clientes - complementos del estandar pv2 (prefijo cst)
       Usada por: app_cxc_customer news y edit.
       Requiere: posme_v2_style, posme_v2_common, afx_v2_common y
       app_cxp_provider/prv_v2_common (telefonos y correos como tabla en movil).
       ========================================================== */

    /* Vista previa de la foto del cliente */
    .preview-container { position:relative; margin-top:10px; }
    .preview-img { max-width:100%; max-height:200px; object-fit:cover; }

    /* Barra lateral de Leads: el ancho lo controlan btnLeads y cerrarLeads. Va encima de la barra del telefono */
    .sidebar { height:100%; width:0; position:fixed; top:0; right:0; z-index:1040; max-width:100%; background-color:#fff; overflow-x:hidden; padding-top:60px; }
    .sidebar-content { padding:20px; }
</style>
<script>
    /* ==========================================================
       Clientes - interfaz compartida entre nuevo y editar.
       No contiene logica de negocio.
       ========================================================== */

    /* Fecha de un registro existente: pv2InitDate pone la fecha de hoy en un campo vacio,
       pero las fechas del cliente admiten nulo y abrir y guardar no debe inventar un valor */
    function cstInitDateExistente(id){
        var input = document.getElementById(id);
        if(!input) return;

        var estabaVacia = $.trim(input.value) == "";
        pv2InitDate(id, false);
        if(estabaVacia) input.value = "";
    }

    /* El vencimiento se guarda como mm/aaaa, formato que el calendario nativo del telefono no produce:
       en telefono se escribe a mano y en PC se conserva el calendario del tema */
    function cstInitVencimiento(id){
        if(pv2IsMobile()){
            $("#" + id).attr({ placeholder:"mm/aaaa", inputmode:"numeric" });
            return;
        }
        $("#" + id).datepicker({format:"mm/yyyy"});
    }
</script>
