<script>
	$(document).ready(function(){	
		$(document).on("click","#btnView",function(){
			window.open("<?php echo base_url(); ?>/core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback; 
		});		
		$(document).on("click","#btnEdit",function(){
		
			if(objRowTableListView != undefined){
				fnWaitOpen();
				var data 		= objTableListView.fnGetData(objRowTableListView);			
				window.location	= "<?php echo base_url(); ?>/app_box_share/edit/companyID/"+data[0]+"/transactionID/"+data[1]+"/transactionMasterID/"+data[2];
			}
			else{
				fnShowNotification("Seleccionar el Registro...","error");
			}
			
		}); 
		$(document).on("click","#btnSearchTransaction",function(){
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_box_share/searchTransactionMaster",
						data 		: {transactionNumber : $("#txtSearchTransaction").val() },
						success:function(data){
							console.info("complete delete success");
							fnWaitClose();
							if(data.error){
								fnShowNotification(data.message,"error");
							}
							else{		
								window.location = "<?php echo base_url(); ?>/app_box_share/edit/companyID/"+data.companyID+"/transactionID/"+data.transactionID+"/transactionMasterID/"+data.transactionMasterID;
							}
						},
						error:function(xhr,data){	
							console.info("complete delete error");									
							fnWaitClose();
							fnShowNotification("Error 505","error");
						}
					});
		});		
		$(document).on("click","#btnEliminar",function(){
		
			if(objRowTableListView != undefined){
				var data 		= objTableListView.fnGetData(objRowTableListView);	
				
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
					fnWaitOpen();
					$.ajax({									
						cache       : false,
						dataType    : 'json',
						type        : 'POST',
						url  		: "<?php echo base_url(); ?>/app_box_share/delete",
						data 		: {companyID : data[0], transactionID :data[1],transactionMasterID : data[2], comments : comments },
						success:function(data){
							console.info("complete delete success");
							fnWaitClose();
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
							fnWaitClose();
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
			fnWaitOpen();
			window.location	= "<?php echo base_url(); ?>/app_box_share/add";
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


	});
	
	function fn_aceptCallback(data){
			var dataViewID 	= data[0];
			window.location = "../../app_box_share/index/"+dataViewID;   
	}					
</script>