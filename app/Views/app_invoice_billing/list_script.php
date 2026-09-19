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
				$('#txtDeleteCommentList').val('').css({'border-color':'#dfe6e9','box-shadow':'none'});
				$('#errDeleteCommentList').hide();
				$('#modalDeleteCommentList').modal('show');
				$('#btnConfirmDeleteList').off('click').on('click', function(){
					var comments = $('#txtDeleteCommentList').val().trim();
					if(comments === ''){
						$('#txtDeleteCommentList').css({'border-color':'#d63031','box-shadow':'0 0 0 3px rgba(214,48,49,.12)'}).focus();
						$('#errDeleteCommentList').show();
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
		if($('#modalDeleteCommentList').length === 0){
			$('body').append(`
			<div class="modal fade" id="modalDeleteCommentList" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document" style="max-width:440px;margin-top:12vh;">
					<div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.25);">
						<div style="background:linear-gradient(135deg,#ff5f6d 0%,#d63031 100%);padding:22px 24px;color:#fff;position:relative;">
							<div style="display:flex;align-items:center;gap:12px;">
								<div style="width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;">&#9888;</div>
								<div>
									<div style="font-size:17px;font-weight:700;line-height:1.2;">Anular factura</div>
									<div style="font-size:12.5px;opacity:.9;">Esta acción requiere justificación</div>
								</div>
							</div>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:absolute;top:14px;right:16px;color:#fff;opacity:.85;font-size:26px;font-weight:400;text-shadow:none;background:none;border:none;cursor:pointer;">&times;</button>
						</div>
						<div style="padding:24px;background:#fff;">
							<label style="display:block;font-size:13.5px;font-weight:600;color:#2d3436;margin-bottom:8px;">Motivo de la anulación <span style="color:#d63031;">*</span></label>
							<textarea id="txtDeleteCommentList" rows="3" placeholder="Escriba el motivo por el cual anula esta factura..." style="width:100%;border:1.5px solid #dfe6e9;border-radius:10px;padding:12px 14px;font-size:14px;resize:none;outline:none;transition:border-color .2s,box-shadow .2s;box-sizing:border-box;"></textarea>
							<div id="errDeleteCommentList" style="display:none;color:#d63031;font-size:12.5px;margin-top:6px;">El comentario es obligatorio para continuar.</div>
						</div>
						<div style="padding:16px 24px;background:#f8f9fa;display:flex;justify-content:flex-end;gap:10px;">
							<button type="button" data-dismiss="modal" id="btnCancelDeleteList" style="border:none;background:#e9ecef;color:#495057;padding:10px 20px;border-radius:9px;font-size:13.5px;font-weight:600;cursor:pointer;transition:background .2s;">Cancelar</button>
							<button type="button" id="btnConfirmDeleteList" style="border:none;background:linear-gradient(135deg,#ff5f6d 0%,#d63031 100%);color:#fff;padding:10px 22px;border-radius:9px;font-size:13.5px;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(214,48,49,.3);">Confirmar anulación</button>
						</div>
					</div>
				</div>
			</div>`);
		}

		$('#modalDeleteCommentList').on('shown.bs.modal', function(){
			$('#txtDeleteCommentList').focus();
		});
		$(document).on('focus', '#txtDeleteCommentList', function(){
			$(this).css({'border-color':'#d63031','box-shadow':'0 0 0 3px rgba(214,48,49,.12)'});
		});
		$(document).on('input', '#txtDeleteCommentList', function(){
			if($(this).val().trim() !== ''){
				$(this).css('border-color','#dfe6e9');
				$('#errDeleteCommentList').hide();
			}
		});
		$(document).on('click', '#btnCancelDeleteList', function(){
			$('#modalDeleteCommentList').modal('hide');
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
