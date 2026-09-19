<script>
	var objIsMobile							= '<?php echo $useMobile; ?>';	
	var objEsMesero							= '<?php echo $esMesero; ?>';	
	var objEliminarProductos				= '<?php echo $eliminarProductos; ?>';
	var objParameterPantallaParaFacturar 	= '<?php echo $objParameterPantallaParaFacturar; ?>';	
	var objParameterMeseroScreenIndividual 	= '<?php echo $objParameterMeseroScreenIndividual; ?>';
	var objPasswordMesero 					= '<?php echo $objPasswordMesero; ?>';
	var objBoton							= "";
	var urlPreview 							= "<?php echo base_url(); ?>/app_invoice_billing/viewRegisterFormatoPaginaNormal80mmOpcion1/companyID/2/transactionID/19/transactionMasterID/";
	
	$(document).ready(function(){
		fnWaitCloseV2();
		
		$('#txtClaveMesero').css({
			'webkitTextSecurity'	: 'disc', // Para WebKit browsers
			'textSecurity'			: 'disc'        // Para otros browsers que lo soporten
		});

        if (objEliminarProductos != '0'){
            $('#btnEliminar').addClass('hidden');
        }

		if(objIsMobile == "1")
		{
			
			var availWidth 	= window.screen.availWidth;
			availWidth		= availWidth - 65;
			$(".dataTables_paginate.paging_bootstrap.pagination").remove();
			$("#ListView_length").remove();
			$("#ListView_filter").remove();
			$("#ListView").css("width","100%");
			
		}
		
		
		$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});		
		$('#modalDialogClaveMesero').on('shown.bs.modal', function () {
			$('#txtClaveMesero').focus();
		});
	
		
		
		$(document).on("click","#btnView",function(){
			window.open("<?php echo base_url(); ?>/core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback; 
		});	

		$(document).on("click",".btnVerPrevio",function(){			
				
			//ver el iframe
			
			var data 				= objTableListView.fnGetData(objRowTableListView);		
			var transactionMasterID = $(this).data("transactionmasterid");
			$(".iframePreviewPdf").css("display","none");
			$("#iframeWork"+transactionMasterID).css("display","block");
			
			
		});

        $(document).on("click", "#btnEdit", function () {
            objBoton = "edit";

            if (objEsMesero === "0") {
                if (objRowTableListView !== undefined) {
                    mostrarModal('ModalCargandoDatos');
                    let data = objTableListView.fnGetData(objRowTableListView);

                    $.post(
						"<?= base_url(); ?>/app_invoice_billing/setSessionData", 
							{
							companyID           : data[0],
							transactionID       : data[1],
							transactionMasterID : data[2],
							codigoMesero        : "none",
							edicion             : true
						}, function () {
							window.location.href = "<?= base_url(); ?>/app_invoice_billing/add/codigoMesero/none<?php echo getBahavioSession($company->type,'app_invoice_billing','versionPantallaFacturacion','',$objListCompanyPageSetting) ?>";
						}
					);
					
                } else {
                    fnShowNotification("Seleccionar el Registro...", "error");
                }
            } else {
                if (objParameterMeseroScreenIndividual === "true") {
                    $("#txtClaveMesero").val("<?= $objPasswordMesero ?>");
                    fnAceptarClaveMesero();
                } else {
                    $("#txtClaveMesero").val("");
                    $("#modalDialogClaveMesero").modal("show");
                }
            }
        });


        $(document).on("dblclick","#ListView tbody tr",function() {
			objBoton = "edit";
			if(objEsMesero == "0")
			{			
				if(objRowTableListView != undefined){
					mostrarModal('ModalCargandoDatos');
					var data 		= objTableListView.fnGetData(objRowTableListView);		

					 $.post(
						"<?= base_url(); ?>/app_invoice_billing/setSessionData", 
						{
							companyID           : data[0],
							transactionID       : data[1],
							transactionMasterID : data[2],
							codigoMesero        : "none",
							edicion             : true
						}, function () {
							window.location.href = "<?= base_url(); ?>/app_invoice_billing/add/codigoMesero/none<?php echo getBahavioSession($company->type,'app_invoice_billing','versionPantallaFacturacion','',$objListCompanyPageSetting) ?>";
						}
					);
				}
				else{
					fnShowNotification("Seleccionar el Registro...","error");
				}
			}
			else 
			{
				if (objParameterMeseroScreenIndividual === "true")
				{
                    $("#txtClaveMesero").val("<?= $objPasswordMesero ?>");					
                    fnAceptarClaveMesero();
                }
				else
				{
                    $("#txtClaveMesero").val("");
                    $("#modalDialogClaveMesero").modal("show");
                }
				
				
			}
		});
		$(document).on("click","#btnSearchTransaction",function(){
			mostrarModal('ModalCargandoDatos');
			var transactionNumber 	= $("#txtSearchTransaction").val() ;
			var fecha 				= $("#txtStartOn").val();
			
			
			if(transactionNumber != "") {
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_invoice_billing/searchTransactionMaster",
					data 		: {transactionNumber : transactionNumber },
					success:function(data){
						console.info("complete delete success");
						mostrarModal('ModalCargandoDatos');
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{	
							objBoton = "edit";
							if(objEsMesero == "0")
							{	
								
								mostrarModal('ModalCargandoDatos');
								$.post(
									"<?= base_url(); ?>/app_invoice_billing/setSessionData", 
										{
										companyID           : data.companyID,
										transactionID       : data.transactionID,
										transactionMasterID : data.transactionMasterID,
										codigoMesero        : "none",
										edicion             : true
									}, function () {
										window.location.href = "<?= base_url(); ?>/app_invoice_billing/add/codigoMesero/none<?php echo getBahavioSession($company->type,'app_invoice_billing','versionPantallaFacturacion','',$objListCompanyPageSetting) ?>";
									}
								);
								
				
							}
							else 
							{
								$("#txtClaveMesero").val("");
								$("#modalDialogClaveMesero").modal('show');
							}	
							
						}
					},
					error:function(xhr,data){	
						console.info("complete delete error");									
						mostrarModal('ModalCargandoDatos');
						fnShowNotification("Error 505","error");
					}
				});
			}
			else{
				window.location = "<?= base_url() ?>/app_invoice_billing/index/dataViewID/"+null+"/fecha/"+fecha;   
			}
					
		});		
		$(document).on("click","#btnEliminar",function(){
		
			if(objRowTableListView != undefined){
				var data = objTableListView.fnGetData(objRowTableListView);
				// Mostrar modal de comentario obligatorio
				$('#txtDeleteCommentList').val('');
				$('#modalDeleteCommentList').modal('show');
				$('#btnConfirmDeleteList').off('click').on('click', function(){
					var comments = $('#txtDeleteCommentList').val().trim();
					if(comments === ''){
						$('#txtDeleteCommentList').addClass('is-invalid').focus();
						return;
					}
					$('#modalDeleteCommentList').modal('hide');
					mostrarModal('ModalCargandoDatos');
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_invoice_billing/delete",
						data 		: {companyID : data[0], transactionID :data[1], transactionMasterID : data[2], comments : comments },
						success:function(data){
							console.info("complete delete success");
							cerrarModal('ModalCargandoDatos');
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{				
								fnShowNotification("success","success");
								objTableListView.fnDeleteRow(objRowTableListView);
							}
						},
						error:function(xhr,data){	
							console.info("complete delete error");									
							cerrarModal('ModalCargandoDatos');
							fnShowNotification("Error 505","error");
						}
					});
				});
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
		});

		<!-- Modal comentario eliminar (list) -->
		$('body').append(`
		<div class="modal fade" id="modalDeleteCommentList" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" style="max-width:420px">
				<div class="modal-content">
					<div class="modal-header bg-danger text-white py-2">
						<h6 class="modal-title mb-0"><i class="ti ti-alert-triangle me-1"></i>Motivo de anulación</h6>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<label class="form-label fw-semibold">Comentario <span class="text-danger">*</span></label>
						<textarea id="txtDeleteCommentList" class="form-control" rows="3" placeholder="Escriba el motivo de la anulación..."></textarea>
						<div class="invalid-feedback">El comentario es obligatorio.</div>
					</div>
					<div class="modal-footer py-2">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-danger btn-sm" id="btnConfirmDeleteList"><i class="ti ti-trash me-1"></i>Confirmar anulación</button>
					</div>
				</div>
			</div>
		</div>`);

		$('#modalDeleteCommentList').on('shown.bs.modal', function(){
			$('#txtDeleteCommentList').focus();
		});
		$('#txtDeleteCommentList').on('input', function(){
			if($(this).val().trim() !== '') $(this).removeClass('is-invalid');
		});
		
		$(document).on("click","#btnDuplicar",function(){
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);				
				fnShowConfirm("Confirmar..","Desea duplicar esta Factura...",function(){
					mostrarModal('ModalCargandoDatos');
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_invoice_billing/duplicate",
						data 		: {companyID : data[0], transactionID :data[1], transactionMasterID : data[2] },
						success:function(data){
							cerrarModal('ModalCargandoDatos');
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{				
								fnShowNotification(data.message,"success");
								setTimeout(function(){ location.reload(); }, 1500);
							}
						},
						error:function(xhr,data){	
							cerrarModal('ModalCargandoDatos');
							fnShowNotification("Error 505","error");
						}
					});
				});
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
		});
		
		$(document).on("click","#btnNuevo",function(){
			objBoton = "new";
			if(objEsMesero == "0")
			{
				mostrarModal('ModalCargandoDatos');
				if(objParameterPantallaParaFacturar == "-")
					window.location	= "<?php echo base_url(); ?>/app_invoice_billing/add/codigoMesero/none<?php echo getBahavioSession($company->type,'app_invoice_billing','versionPantallaFacturacion','',$objListCompanyPageSetting) ?>";
				else 
					window.location	= "<?php echo base_url(); ?>/app_invoice_billing/"+objParameterPantallaParaFacturar+"/companyID/0/transactionID/19/transactionMasterID/0/codigoMesero/none<?php echo getBahavioSession($company->type,'app_invoice_billing','versionPantallaFacturacion','',$objListCompanyPageSetting) ?>";
				
			}
			else 
			{
                if (objParameterMeseroScreenIndividual === "true"){
                    $("#txtClaveMesero").val("<?= $objPasswordMesero ?>");
                    fnAceptarClaveMesero();
                }else{
                    $("#txtClaveMesero").val("");
                    $("#modalDialogClaveMesero").modal("show");
                }
			}
			
			
		});
		
		$(document).on("click",'#btnAceptarClaveMesero', function(){
			fnAceptarClaveMesero();
		});
	
	
	
		
	});

    function fnAceptarClaveMesero(){
        // Validar si el input está vacío
        let codigoMesero = $('#txtClaveMesero').val().trim();
        if (codigoMesero === '') {
            $('#errorMessage').show();
        } else 
		{
            $('#errorMessage').hide();
            $('#modalDialogClaveMesero').modal('hide');
            mostrarModal('ModalCargandoDatos');

            if(objBoton == "new")
            {
                if(objParameterPantallaParaFacturar == "-")
                    window.location	= "<?= base_url() ?>/app_invoice_billing/add"+"/codigoMesero/"+codigoMesero;
                else
                    window.location	= "<?= base_url() ?>/app_invoice_billing/"+objParameterPantallaParaFacturar+"/companyID/0/transactionID/19/transactionMasterID/0"+"/codigoMesero/"+codigoMesero;
            }

            if(objBoton == "edit")
            {
                if(objRowTableListView != undefined)
                {
					mostrarModal('ModalCargandoDatos');
					var data 	= objTableListView.fnGetData(objRowTableListView);
					let url		="";
					$.post("<?= base_url(); ?>/app_invoice_billing/setSessionData", {
                        companyID           : data[0],
                        transactionID       : data[1],
                        transactionMasterID : data[2],
                        codigoMesero        : codigoMesero,
                        edicion             : true
                    }, function () {
                        window.location.href = "<?= base_url(); ?>/app_invoice_billing/add/codigoMesero/"+codigoMesero;
                    });
				}
                else{
                    fnShowNotification("Seleccionar el Registro...","error");
                }
            }

        }
    }
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_invoice_billing/index/dataViewID/"+dataViewID;   
	}					
</script>
