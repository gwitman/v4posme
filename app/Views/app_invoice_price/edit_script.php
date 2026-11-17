<!-- ./ page heading -->
<script>
    var varUseMobile          = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
    var site_url              = "<?php echo base_url(); ?>";
    var priceFechasOriginales = {};

    /* startOn y endOn son datetime y endOn admite NULL. pv2InitDate recorta a 10 caracteres y pone hoy
       si esta vacio: aqui se guarda el valor original para no perder la hora ni inventar una fecha. */
    function priceInitFecha(id){
        var original = $.trim($("#" + id).val());
        priceFechasOriginales[id] = original;
        pv2InitDate(id, false);
        if(original == "") $("#" + id).val("");
    }

    //Si el usuario no cambio el dia, se devuelve el valor original con su hora justo antes de enviar
    function priceRestaurarHora(id){
        var input    = document.getElementById(id);
        var original = priceFechasOriginales[id];
        if(original.length > 10 && input.value == original.substring(0,10)){
            input.type  = "text"; //el selector nativo del telefono no admite hora
            input.value = original;
        }
    }

    $(document).ready(function(){
        //Inicializar fechas
        priceInitFecha("txtStartOn");
        priceInitFecha("txtEndOn");
        afxBindCommon();

        //Regresar a la lista
        $(document).on("click","#btnBack",function(){
            fnWaitOpen();
        });

        //Eliminar el Documento
        $(document).on("click","#btnDelete",function(){
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_invoice_price/delete",
                    data        : {companyID : <?php echo $objListPrice->companyID;?>, listPriceID : <?php echo $objListPrice->listPriceID;?>   },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_invoice_price/index";
                        }
                    },
                    error:function(xhr,data){
                        console.info("complete delete error");
                        fnWaitClose();
                        fnShowNotification("Error 505","error");
                    }
                });
            });
        });

        //Imprimer Lista de Precio
        $(document).on("click","#btnPrinter",function(){
            console.info("call btnPrinter");
            fnWaitOpen();
            window.location = "<?php echo base_url(); ?>/app_invoice_price/viewRegister/listPriceID/<?php echo $objListPrice->listPriceID;?>";
        });

        //Evento Agregar la lista de Precio
        $(document).on("click","#btnAcept",function(){
            $( "#form-new-invoice-price" ).attr("method","POST");
            $( "#form-new-invoice-price" ).attr("action","<?php echo base_url(); ?>/app_invoice_price/save/edit");

            if(validateForm()){
                fnWaitOpen();
                priceRestaurarHora("txtStartOn");
                priceRestaurarHora("txtEndOn");
                $( "#form-new-invoice-price" ).submit();
            }
        });

        $(document).on("click","#btnClickArchivo",function(){
            priceAbrirArchivos();
        });

        //Subir archivo: abre el administrador de archivos de la lista
        $(document).on("click","#btnUploadFile",function(e){
            e.preventDefault();
            priceAbrirArchivos();
        });

        //Generar el archivo y descargarlo de una vez
        $(document).on("click","#btnGenerateFile",function(e){
            e.preventDefault();
            fnWaitOpen();
            $.ajax({
                cache       : false,
                dataType    : 'json',
                type        : 'POST',
                url         : site_url + "/app_invoice_price/generateFile",
                data        : { listPriceID : priceListPriceID },
                success:function(data){
                    fnWaitClose();
                    if(data.error){
                        fnShowNotification(data.message,"error");
                        return;
                    }
                    if(!data.urlDownload){
                        fnShowNotification("No se recibio el archivo generado","error");
                        return;
                    }
                    fnShowNotification("Archivo generado: " + data.fileName,"success");
                    priceDescargar(data.urlDownload);
                    priceCargarArchivos(data.fileName);
                },
                error:function(){
                    fnWaitClose();
                    fnShowNotification("Error al generar el archivo","error");
                }
            });
        });

        //Refrescar la lista de archivos de la carpeta
        $(document).on("click","#btnRefreshFile",function(e){
            e.preventDefault();
            priceCargarArchivos($("#cmbFilePrice").val());
        });

        //Descargar el archivo seleccionado
        $(document).on("click","#btnDownloadFile",function(e){
            e.preventDefault();
            var url = $("#cmbFilePrice option:selected").data("url");
            if(!url){
                fnShowNotification("Seleccione un archivo","error");
                return;
            }
            priceDescargar(url);
        });

        //Procesar el archivo seleccionado
        $(document).on("click","#btnProcessFile",function(e){
            e.preventDefault();
            var fileName = $("#cmbFilePrice").val();
            if(!fileName){
                fnShowNotification("Seleccione el archivo a procesar. Si lo acaba de subir presione refrescar.","error");
                return;
            }
            fnShowConfirm("Confirmar..","Desea actualizar los precios con el archivo " + fileName + "?",function(){
                priceProcesar(fileName);
            });
        });

        //Filtro y paginacion del resumen
        $(document).on("keyup","#txtPriceFiltro",function(){
            pricePagina = 1;
            priceRenderDetalle();
        });
        $(document).on("change","#cmbPricePageSize",function(){
            pricePagina = 1;
            priceRenderDetalle();
        });
        $(document).on("click","#btnPricePrev",function(e){
            e.preventDefault();
            if(pricePagina > 1){ pricePagina--; priceRenderDetalle(); }
        });
        $(document).on("click","#btnPriceNext",function(e){
            e.preventDefault();
            pricePagina++;
            priceRenderDetalle();
        });

        //Cargar los archivos al abrir la pantalla
        priceCargarArchivos("");
    });

    var priceListPriceID = <?php echo (int)$objListPrice->listPriceID; ?>;
    var priceDetalle     = [];
    var pricePagina      = 1;

    function priceAbrirArchivos(){
        window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objListPrice->listPriceID; ?>","blanck");
    }

    //Descarga sin salir de la pantalla (iframe oculto; el servidor responde como adjunto)
    function priceDescargar(url){
        var frame = document.getElementById("priceFrameDescarga");
        if(!frame){
            frame               = document.createElement("iframe");
            frame.id            = "priceFrameDescarga";
            frame.style.display = "none";
            document.body.appendChild(frame);
        }
        frame.src = url;
    }

    function priceEscapar(texto){
        return $("<div>").text(texto === null || texto === undefined ? "" : String(texto)).html();
    }

    //Porcentaje con 2 decimales y separador de miles
    function priceFmtPorc(valor){
        return pv2Fmt(valor) + " %";
    }

    //Llena el combo con los archivos .csv de la carpeta de la lista
    function priceCargarArchivos(seleccionar){
        var $cmb = $("#cmbFilePrice");
        $cmb.html("<option value=''>Cargando archivos...</option>");
        $.ajax({
            cache       : false,
            dataType    : 'json',
            type        : 'POST',
            url         : site_url + "/app_invoice_price/listFile",
            data        : { listPriceID : priceListPriceID },
            success:function(data){
                //error
                if(data.error){
                    $cmb.html("<option value=''>No se pudo leer la carpeta</option>");
                    fnShowNotification(data.message,"error");
                    return;
                }
                //sin datos
                if(!data.data || data.data.length == 0){
                    $cmb.html("<option value=''>No hay archivos .csv, genere o suba uno</option>");
                    return;
                }
                //con datos
                var html = "<option value=''>Seleccione un archivo...</option>";
                $.each(data.data,function(i,f){
                    html += "<option value='" + priceEscapar(f.name) + "' data-url='" + priceEscapar(f.urlDownload) + "'>" + priceEscapar(f.name) + "  (" + priceEscapar(f.date) + ")</option>";
                });
                $cmb.html(html);
                if(seleccionar) $cmb.val(seleccionar);
            },
            error:function(){
                $cmb.html("<option value=''>No se pudo leer la carpeta</option>");
                fnShowNotification("Error al cargar los archivos","error");
            }
        });
    }

    //Procesa el archivo y muestra el resumen
    function priceProcesar(fileName){
        fnWaitOpen();
        $.ajax({
            cache       : false,
            dataType    : 'json',
            type        : 'POST',
            url         : site_url + "/app_invoice_price/processFile",
            data        : { listPriceID : priceListPriceID, fileName : fileName },
            success:function(data){
                fnWaitClose();
                //error
                if(data.error){
                    fnShowNotification(data.message,"error",15000);
                    return;
                }
                //sin datos
                if(!data.data){
                    fnShowNotification("El servidor no devolvio el resumen","error");
                    return;
                }
                //con datos
                var r = data.data;
                $("#priceResumenArchivo").text(r.archivo);
                $("#kpiFilas").text(r.filasLeidas);
                $("#kpiActualizados").text(r.productosActualizados);
                $("#kpiPrecios").text(r.preciosCambiados);
                $("#kpiSinCambio").text(r.productosSinCambio);
                $("#kpiErrores").text(r.productosConError);
                $("#kpiErroresBox").toggleClass("is-negative", r.productosConError > 0);

                priceDetalle = r.detalle || [];
                pricePagina  = 1;
                $("#txtPriceFiltro").val("");
                priceRenderDetalle();
                priceRenderErrores(r.errores || []);

                $("#priceResumen").show();
                $("html, body").animate({ scrollTop: $("#priceResumen").offset().top - 70 }, 300);

                if(r.productosActualizados > 0)
                    fnShowNotification(r.productosActualizados + " producto(s) actualizados","success");
                else
                    fnShowNotification("No hubo cambios de precio en el archivo","warning");
            },
            error:function(){
                fnWaitClose();
                fnShowNotification("Error al procesar el archivo","error");
            }
        });
    }

    //Pinta la pagina actual del detalle de cambios (con filtro)
    function priceRenderDetalle(){
        var filtro    = $.trim($("#txtPriceFiltro").val()).toLowerCase();
        var tamano    = parseInt($("#cmbPricePageSize").val(),10) || 25;
        var filas     = $.grep(priceDetalle,function(d){
            if(filtro == "") return true;
            return (String(d.codigo) + " " + String(d.nombre) + " " + String(d.tipo)).toLowerCase().indexOf(filtro) >= 0;
        });
        var paginas   = Math.max(1, Math.ceil(filas.length / tamano));
        if(pricePagina > paginas) pricePagina = paginas;
        var inicio    = (pricePagina - 1) * tamano;
        var pagina    = filas.slice(inicio, inicio + tamano);
        var html      = "";

        if(pagina.length == 0){
            html = "<tr><td colspan='10'><div class='pv2-empty'>" + (priceDetalle.length == 0 ? "No hubo cambios de precio" : "Sin resultados para la busqueda") + "</div></td></tr>";
        }
        $.each(pagina,function(i,d){
            var clase = d.precioNuevo > d.precioAnterior ? "price-up" : (d.precioNuevo < d.precioAnterior ? "price-down" : "");
            html += "<tr class='row_razon'>"
                 +  "<td class='pv2-doc'>" + priceEscapar(d.codigo) + "</td>"
                 +  "<td>" + priceEscapar(d.nombre) + "</td>"
                 +  "<td>" + priceEscapar(d.tipo) + "</td>"
                 +  "<td class='pv2-num'>" + pv2Fmt(d.costo) + "</td>"
                 +  "<td class='pv2-num'>" + pv2Fmt(d.precioAnterior) + "</td>"
                 +  "<td class='pv2-num " + clase + "'>" + pv2Fmt(d.precioNuevo) + "</td>"
                 +  "<td class='pv2-num'>" + priceFmtPorc(d.porcAnterior) + "</td>"
                 +  "<td class='pv2-num'>" + priceFmtPorc(d.porcNuevo) + "</td>"
                 +  "<td class='pv2-num'>" + pv2Fmt(d.comAnterior) + " &rarr; " + pv2Fmt(d.comNueva) + "</td>"
                 +  "<td>" + priceEscapar(d.origen) + "</td>"
                 +  "</tr>";
        });
        $("#priceDetalleBody").html(html);
        $("#pricePagInfo").text("Pagina " + pricePagina + " de " + paginas + "  (" + filas.length + " cambios)");
        $("#btnPricePrev").toggleClass("disabled", pricePagina <= 1);
        $("#btnPriceNext").toggleClass("disabled", pricePagina >= paginas);
    }

    //Productos que no se procesaron y su motivo
    function priceRenderErrores(errores){
        if(errores.length == 0){
            $("#priceErroresBox").hide();
            return;
        }
        var html = "";
        $.each(errores,function(i,e){
            html += "<tr class='row_razon'>"
                 +  "<td>" + priceEscapar(e.linea) + "</td>"
                 +  "<td class='pv2-doc'>" + priceEscapar(e.codigo) + "</td>"
                 +  "<td>" + priceEscapar(e.nombre) + "</td>"
                 +  "<td>" + priceEscapar(e.mensaje) + "</td>"
                 +  "</tr>";
        });
        $("#priceErroresBody").html(html);
        $("#priceErroresBox").show();
    }

    function validateForm(){
        var result            = true;
        var timerNotification = 15000;

        if (  $('#txtStartOn').val() == "" ||  $('#txtEndOn').val() == "" ) {
            fnShowNotification("Las fechas son obligatorias","error",timerNotification);
            result = false;
        }
        return result;
    }
</script>
