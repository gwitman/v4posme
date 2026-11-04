<style>
    /* ==========================================================
       Colaboradores - complementos del estandar pv2 (prefijo emp)
       Usada por: app_rrhh_employee news y edit.
       Requiere: posme_v2_style, posme_v2_common, afx_v2_common y pla_v2_common.
       ========================================================== */

    /* Telefonos y correos siguen siendo tabla en telefono: el encabezado explica Tipo y Primario */
    @media (max-width:767px) {
        .pv2 #tb_detail_phone, .pv2 #tb_detail_email { display:table; }
        .pv2 #tb_detail_phone thead, .pv2 #tb_detail_email thead { display:table-header-group; }
        .pv2 #tb_detail_phone tbody, .pv2 #tb_detail_email tbody { display:table-row-group; }
        .pv2 #tb_detail_phone td, .pv2 #tb_detail_email td { word-break:break-word; }
    }
</style>
<script>
    /* ==========================================================
       Colaboradores - interfaz compartida entre nuevo y editar.
       No contiene logica de negocio.
       ========================================================== */
    var objTableEmail = {};
    var objTablePhone = {};
    var site_url      = "<?php echo base_url(); ?>";

    //Escapa un texto para mostrarlo o dejarlo en el value de un campo oculto
    function empEsc(value){
        return $("<div>").text(value === null || value === undefined ? "" : value).html().replace(/"/g,"&quot;");
    }

    //Solo casillas: los combos nativos del telefono no deben envolverse con uniform
    function refreschChecked(){
        $("[type='checkbox']").not(".toggle, .multiselect").uniform();
    }

    function empCheckRender(cssClass){
        return function(data, type, full){
            return '<input type="checkbox" class="' + cssClass + '"' + (data == false ? '' : ' checked="checked"') + ' value="0">';
        };
    }

    //Columna oculta con el valor para el servidor
    function empHiddenRender(nameField){
        return function(data, type, full){
            return '<input type="hidden" value="' + empEsc(data) + '" name="' + nameField + '" />';
        };
    }

    //Columna visible: conserva el campo oculto y muestra el texto
    function empTextRender(nameField){
        return function(data, type, full){
            return '<input type="hidden" value="' + empEsc(data) + '" name="' + nameField + '" />' + empEsc(data);
        };
    }

    function empPrimaryRender(nameField){
        return function(data, type, full){
            return '<input type="hidden" value="' + empEsc(data) + '" name="' + nameField + '" />' + (data == true ? "SI" : "NO");
        };
    }

    function empInitTables(emailRows, phoneRows){
        objTableEmail = $("#tb_detail_email").dataTable({
            "bPaginate"     : false,
            "bFilter"       : false,
            "bSort"         : false,
            "bInfo"         : false,
            "bAutoWidth"    : false,
            "aaData"        : emailRows,
            "aoColumnDefs"  : [
                { "aTargets" : [ 0 ], "mRender" : empCheckRender("classCheckedDetailEmail") },
                { "aTargets" : [ 1 ], "bVisible" : true, "sClass" : "hidden", "bSearchable" : false, "mRender" : empHiddenRender("txtEntityEmailID[]") },
                { "aTargets" : [ 2 ], "mRender" : empTextRender("txtEntityEmail[]") },
                { "aTargets" : [ 3 ], "mRender" : empPrimaryRender("txtEmailIsPrimary[]") }
            ]
        });

        objTablePhone = $("#tb_detail_phone").dataTable({
            "bPaginate"     : false,
            "bFilter"       : false,
            "bSort"         : false,
            "bInfo"         : false,
            "bAutoWidth"    : false,
            "aaData"        : phoneRows,
            "aoColumnDefs"  : [
                { "aTargets" : [ 0 ], "mRender" : empCheckRender("classCheckedDetailPhone") },
                { "aTargets" : [ 1 ], "bVisible" : true, "sClass" : "hidden", "bSearchable" : false, "mRender" : empHiddenRender("txtEntityPhoneID[]") },
                { "aTargets" : [ 2 ], "bVisible" : true, "sClass" : "hidden", "bSearchable" : false, "mRender" : empHiddenRender("txtEntityPhoneTypeID[]") },
                { "aTargets" : [ 3 ], "mRender" : empTextRender("txtEntityPhoneTypeDescription[]") },
                { "aTargets" : [ 4 ], "mRender" : empTextRender("txtEntityPhoneNumber[]") },
                { "aTargets" : [ 5 ], "mRender" : empPrimaryRender("txtEntityPhoneIsPrimary[]") }
            ]
        });
        refreschChecked();
    }

    //Quita las filas marcadas de una tabla
    function empDeleteChecked(objTable){
        var listRow = objTable.fnGetData();
        var length  = listRow.length;
        var i       = 0;
        var j       = 0;
        while (i < length){
            if(listRow[i][0] == true){
                objTable.fnDeleteRow(j, null, true);
                j--;
            }
            i++;
            j++;
        }
    }

    //Marca o desmarca la fila de la casilla
    function empToggleRow(objTable, checkbox){
        var objrow_ = $(checkbox).closest("tr")[0];
        var objind_ = objTable.fnGetPosition(objrow_);
        var objdat_ = objTable.fnGetData(objind_);
        objTable.fnUpdate(!objdat_[0], objind_, 0);
        refreschChecked();
    }

    //Recarga combos dependientes: select2 solo en PC, nativo en telefono
    function empLoadCatalog(url, catalogItemID, targetSelector, emptySelector){
        fnWaitOpen();
        $.ajax({
            cache       : false,
            dataType    : 'json',
            type        : 'POST',
            data        : { catalogItemID : catalogItemID },
            url         : url,
            success:function(data){
                fnWaitClose();

                $(targetSelector).html("");
                $(targetSelector).append("<option value=''>N/D</option>");
                afxInitSelect2(targetSelector);

                if(emptySelector){
                    $(emptySelector).html("");
                    afxInitSelect2(emptySelector);
                }

                if(data.catalogItems == null)
                return;

                $.each(data.catalogItems,function(i,obj){
                    $(targetSelector).append("<option value='"+obj.catalogItemID+"'>"+obj.name+"</option>");
                });
            },
            error:function(xhr,data){
                fnShowNotification(data.message,"error");
                fnWaitClose();
            }
        });
    }

    function fnJefeElegido(objResponse){
        console.info("CALL onCompleteEmployee");

        $("#txtParentEmployeeID").val(objResponse[0][2]);
        $("#txtParentDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
    }

    function fnNuevoEmail(data){
        console.info("call parentNewEmail");
        objTableEmail.fnAddData([false,0,data.txtEmail,data.txtIsPrimary]);
        refreschChecked();
    }

    function fnNuevoTelefono(data){
        console.info("call parentNewPhone");
        objTablePhone.fnAddData([false,0,data.txtEntityPhoneTypeID,data.txtEntityPhoneTypeDescription,data.txtEntityPhoneNumber,data.txtIsPrimary]);
        refreschChecked();
    }

    function validateForm(){
        var result              = true;
        var timerNotification   = 15000;
        var primerCampo         = null;

        function marcar(selector, mensaje){
            fnShowNotification(mensaje,"error",timerNotification);
            result      = false;
            primerCampo = primerCampo || selector;
        }

        //Pais
        if(!$("#txtCountryID").val()) marcar("#txtCountryID","Seleccionar el Pais");
        //Departamento
        if(!$("#txtStateID").val()) marcar("#txtStateID","Seleccionar el Departamento");
        //Municipio
        if(!$("#txtCityID").val()) marcar("#txtCityID","Seleccionar el Municipio");
        //Identificacion
        if($("#txtIdentification").val() == "") marcar("#txtIdentification","Escribir la Identificacion");
        //Nombre
        if(($("#txtFirstName").val() + $("#txtLastName").val()) == "") marcar("#txtFirstName","Escribir el Nombre");

        //Lleva al primer campo con error; en combos con select2 se lleva a su grupo
        if(primerCampo){
            pv2FocusField($(primerCampo).is("select") ? $(primerCampo).closest(".form-group") : primerCampo);
        }

        return result;
    }

    /* Enlaza lo comun de nuevo y editar. config: saveUrl, searchParentUrl, emailRows, phoneRows */
    function empInitForm(config){
        empInitTables(config.emailRows, config.phoneRows);
        afxBindCommon();

        $(document).on("click","#btnBack",function(){
            fnWaitOpen();
        });

        $(document).on("click","#btnAcept",function(e){
            e.preventDefault();
            if(validateForm()){
                fnWaitOpen();
                //El servidor recibe numero puro; un costo vacio sigue vacio
                afxLimpiarMontos("#form-new-rrhh-employee");
                $("#form-new-rrhh-employee")
                    .attr("method","POST")
                    .attr("action",config.saveUrl)
                    .submit();
            }
        });

        $("#txtCountryID").change(function(){
            empLoadCatalog(site_url + "/app_catalog_api/getCatalogItemByState", $(this).val(), "#txtStateID", "#txtCityID");
        });

        $("#txtStateID").change(function(){
            empLoadCatalog(site_url + "/app_catalog_api/getCatalogItemByCity", $(this).val(), "#txtCityID", null);
        });

        $(document).on("click","#btnNewEmail",function(e){
            e.preventDefault();
            pv2OpenSearch(site_url + "/app_rrhh_employee/add_email", "Agregar correo", "parentNewEmail", fnNuevoEmail, "width=650,height=500");
        });
        $(document).on("click","#btnDeleteEmail",function(e){
            e.preventDefault();
            empDeleteChecked(objTableEmail);
        });
        $(document).on("click",".classCheckedDetailEmail",function(){
            empToggleRow(objTableEmail, this);
        });

        $(document).on("click","#btnNewPhones",function(e){
            e.preventDefault();
            pv2OpenSearch(site_url + "/app_rrhh_employee/add_phone", "Agregar teléfono", "parentNewPhone", fnNuevoTelefono, "width=650,height=500");
        });
        $(document).on("click","#btnDeletePhones",function(e){
            e.preventDefault();
            empDeleteChecked(objTablePhone);
        });
        $(document).on("click",".classCheckedDetailPhone",function(){
            empToggleRow(objTablePhone, this);
        });

        $(document).on("click","#btnSearchEmployeeParent",function(){
            pv2OpenSearch(config.searchParentUrl, "Buscar jefe inmediato", "onCompleteEmployee", fnJefeElegido, "width=900,height=450");
        });
        $(document).on("click","#btnClearEmployeeParent",function(){
            $("#txtParentEmployeeID").val("");
            $("#txtParentDescription").val("");
        });
    }
</script>
