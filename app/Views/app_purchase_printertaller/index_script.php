<script>
	$(document).ready(function(){

		var companyID		= '<?php echo $companyID; ?>';
		var transactionID	= '<?php echo $transactionID; ?>';
		var urlPrinterInput	= $('#urlPrinterInput').val();
		var urlPrinterOutput= $('#urlPrinterOutput').val();
		var objOrdenes		= [];

		//Inicializar componentes
		$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtStatusID').select2();

		//=================== EXAMINADORES ===================
		//Buscar Colaborador (mismo examinador que app_afx_fixedassent/add -> Asignado A)
		$(document).on("click","#btnSearchEmployer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee;
		});
		$(document).on("click","#btnClearEmployer",function(){
			$("#txtEmployerID").val("");
			$("#txtEmployerDescription").val("");
		});

		//Buscar Proyecto (mismo examinador que app_afx_fixedassent/add -> Proyecto)
		$(document).on("click","#btnSearchFixedAsset",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCatalog->componentID; ?>/onCompleteProyect/SELECCIONAR_ACTIVOFIJO_PROYECTO/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteProyect = onCompleteProyect;
		});
		$(document).on("click","#btnClearFixedAsset",function(){
			$("#txtFixedAssetID").val("");
			$("#txtFixedAssetDescription").val("");
		});

		//=================== FILTRAR ===================
		$(document).on("click","#btnFiltrar",function(){
			fnBuscarOrdenes();
		});

		//=================== IMPRESION INDIVIDUAL ===================
		$(document).on("click",".btnPrintInput",function(){
			fnImprimir(urlPrinterInput,$(this).data("company"),$(this).data("transaction"),$(this).data("master"));
		});
		$(document).on("click",".btnPrintOutput",function(){
			fnImprimir(urlPrinterOutput,$(this).data("company"),$(this).data("transaction"),$(this).data("master"));
		});

		//=================== IMPRESION MASIVA (UN SOLO PDF) ===================
		$(document).on("click","#btnPrintAllInput",function(){
			fnImprimirTodas("app_purchase_printertaller/viewPrinterAllInput");
		});
		$(document).on("click","#btnPrintAllOutput",function(){
			fnImprimirTodas("app_purchase_printertaller/viewPrinterAllOutput");
		});

	});

	//Callbacks de los examinadores (mismos que app_afx_fixedassent/add)
	function onCompleteEmployee(objResponse){
		if(objResponse != undefined){
			$("#txtEmployerID").val(objResponse[0][2]);
			$("#txtEmployerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
		}
	}
	function onCompleteProyect(objResponse){
		if(objResponse != undefined){
			$("#txtFixedAssetID").val(objResponse[0][0]);
			$("#txtFixedAssetDescription").val(objResponse[0][1] + " | " + objResponse[0][2]);
		}
	}

	//Buscar ordenes via AJAX
	function fnBuscarOrdenes(){
		fnWaitOpen();
		$.ajax({
			cache    : false,
			dataType : 'json',
			type     : 'POST',
			url      : "<?php echo base_url(); ?>/app_purchase_printertaller/search",
			data     : {
				txtStartOn      : $("#txtStartOn").val(),
				txtEndOn        : $("#txtEndOn").val(),
				txtStatusID     : $("#txtStatusID").val(),
				txtEmployerID   : $("#txtEmployerID").val(),
				txtFixedAssetID : $("#txtFixedAssetID").val()
			},
			success:function(data){
				fnWaitClose();
				if(data.error){
					fnShowNotification(data.message,"error");
					return;
				}
				objOrdenes = data.data;
				fnRenderDashboard(data);
				fnRenderGrid(data.data);
			},
			error:function(){
				fnWaitClose();
				fnShowNotification("Error 505","error");
			}
		});
	}

	//Render del dashboard
	function fnRenderDashboard(data){
		$("#dashTotal").text(data.total);
		$("#dashColaboradores").text(Object.keys(data.dashboardByEmployer || {}).length);
		$("#dashEstados").text(Object.keys(data.dashboardByStatus || {}).length);
		$("#dashEquipos").text(Object.keys(data.dashboardByEstado || {}).length);

		$("#listByEmployer").html(fnRenderMiniList(data.dashboardByEmployer));
		$("#listByStatus").html(fnRenderMiniList(data.dashboardByStatus));
	}

	function fnRenderMiniList(obj){
		var html = "";
		if(obj && Object.keys(obj).length > 0){
			for(var key in obj){
				html += "<li><span>"+key+"</span><span class='badge'>"+obj[key]+"</span></li>";
			}
		}else{
			html = "<li><span>Sin datos</span></li>";
		}
		return html;
	}

	//Render del grid
	function fnRenderGrid(rows){
		var html = "";
		if(rows && rows.length > 0){
			$.each(rows,function(i,r){
				html += "<tr>";
				var urlOrden = "<?php echo base_url(); ?>/app_purchase_taller/edit/companyID/"+r.companyID+"/transactionID/"+r.transactionID+"/transactionMasterID/"+r.transactionMasterID;
				//Celda: Ir al activo fijo (abre en otra pestania)
				if(r.fixedAssentID && parseInt(r.fixedAssentID) > 0){
					var urlFixedAsset = "<?php echo base_url(); ?>/app_afx_fixedassent/edit/companyID/"+r.companyID+"/branchID/"+r.fixedAssentBranchID+"/fixedAssentID/"+r.fixedAssentID;
					html += "<td><a class='btn btn-xs btn-primary' href='"+urlFixedAsset+"' target='_blank' title='Ir al activo fijo'><i class='i-wrench'></i> Ir al activo fijo</a></td>";
				}else{
					html += "<td><span class='text-muted'>Sin activo fijo</span></td>";
				}
				html += "<td>"+(i+1)+"</td>";
				html += "<td><a href='"+urlOrden+"' target='_blank' title='Ir a la orden'><strong>"+(r.transactionNumber||"")+"</strong> <i class='i-link-2'></i></a></td>";
				html += "<td>"+(r.transactionOn||"")+"</td>";
				html += "<td>"+(r.customerName||"")+"</td>";
				html += "<td>"+(r.employerName||"<span class='text-muted'>Sin asignar</span>")+"</td>";
				html += "<td><span class='badge'>"+(r.workflowStageName||"")+"</span></td>";
				html += "<td><span class='badge badge-estado'>"+(r.estadoEquipoName||"")+"</span></td>";
				html += "<td>"+(r.articuloName||"")+"</td>";
				html += "<td>"+(r.fixedAssentName ? (r.fixedAssentName + (r.fixedAssentCode ? " <small class='text-muted'>("+r.fixedAssentCode+")</small>" : "")) : "")+"</td>";
				html += "<td>"+
							"<button type='button' class='btn btn-xs btn-info btnPrintInput' title='Orden de entrada' "+
								"data-company='"+r.companyID+"' data-transaction='"+r.transactionID+"' data-master='"+r.transactionMasterID+"'>"+
								"<i class='i-printer-4'></i> Entrada</button> "+
							"<button type='button' class='btn btn-xs btn-warning btnPrintOutput' title='Orden de salida' "+
								"data-company='"+r.companyID+"' data-transaction='"+r.transactionID+"' data-master='"+r.transactionMasterID+"'>"+
								"<i class='i-printer-4'></i> Salida</button>"+
						"</td>";
				html += "</tr>";
			});
		}else{
			html = "<tr><td colspan='11' class='text-center text-muted'>No se encontraron ordenes con los filtros aplicados.</td></tr>";
		}
		$("#bodyOrdenes").html(html);
	}

	//Imprimir una orden
	function fnImprimir(urlPrinter,companyID,transactionID,transactionMasterID){
		var url = "<?php echo base_url(); ?>/"+urlPrinter+"/companyID/"+companyID+"/transactionID/"+transactionID+"/transactionMasterID/"+transactionMasterID;
		window.open(url,'_blank');
	}

	//Imprimir todas las ordenes en un solo PDF (una orden por hoja)
	//Envia los mismos filtros de la pantalla al controlador por un form a una nueva pestania
	function fnImprimirTodas(url){
		//Validar intervalo de fecha obligatorio
		if($("#txtStartOn").val().trim() == "" || $("#txtEndOn").val().trim() == ""){
			fnShowNotification("Debe seleccionar un intervalo de fecha (fecha inicial y fecha final) antes de generar el PDF","error");
			return;
		}

		if(!objOrdenes || objOrdenes.length == 0){
			fnShowNotification("No hay ordenes para imprimir","error");
			return;
		}

		var form = $('<form>', {
			method : 'POST',
			action : "<?php echo base_url(); ?>/"+url,
			target : '_blank'
		});

		form.append($('<input>',{type:'hidden',name:'txtStartOn',      value:$("#txtStartOn").val()}));
		form.append($('<input>',{type:'hidden',name:'txtEndOn',        value:$("#txtEndOn").val()}));
		form.append($('<input>',{type:'hidden',name:'txtStatusID',     value:$("#txtStatusID").val()}));
		form.append($('<input>',{type:'hidden',name:'txtEmployerID',   value:$("#txtEmployerID").val()}));
		form.append($('<input>',{type:'hidden',name:'txtFixedAssetID', value:$("#txtFixedAssetID").val()}));

		$('body').append(form);
		form.submit();
		form.remove();
	}
</script>
