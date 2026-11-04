				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
				</script>
				<?php echo view('core_template/posme_v2_common'); ?>
				<script>
					$(document).ready(function(){						 
						 //Fecha nativa y combos nativos en telefono; barra inferior (una sola vez: enlazarla dos veces guardaria dos veces)
						 pv2InitDate("txtDate", true);
						 pv2MobileSelects("#txtStatusID");	
						 pv2BindMobileBar();
						 fnRefreshDetailState();
						 pv2Mask($('.pv2 .txt-numeric'));
						 
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								if($(".txtCheckedIsActive").length  == 0 ){
									fnShowNotification("Seleccionar documento","error");
									return; 
								}
							
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_provisioned/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						//Nueva factura
						$(document).on("click","#btnNewShare",function(){
						
							if($(".txtCheckedIsActive").length  >= 1 ){
								fnShowNotification("El Documento no puede tener mas de un item","error");
								return; 
							}
							var url_request 			= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomerCreditDocument->componentID; ?>/onCompleteNewShare/LISTA_DE_CREDITOS_MOROSOS/true/empty/false/not_redirect_when_empty";
							//Ventana emergente en PC, ventana interna en telefono
							pv2OpenSearch(url_request,"Buscar documento","onCompleteNewShare",onCompleteNewShare,"width=1585,height=795");
						});
						//Eliminar factura
						$(document).on("click","#btnDeleteShare",function(){
							console.info("btnDeleteShare");
							$(".txtCheckedIsActive").each(function(i,obj){
									if($(obj).prop("checked")){
										$(obj).parents("tr").first().remove();
									}
							});
							//Al quitar documentos cambian el total y el cambio
							updateSummary();
							updateCalculateChange();
							
						});
						//Quitar una fila: en telefono no hay casilla para marcar, por eso cada fila tiene su boton
						$(document).on("click",".btnShareRemoveRow",function(e){
							e.preventDefault();
							$(this).closest("tr").remove();
							updateSummary();
							updateCalculateChange();
						});
						$(document).on("change","input.txtBalance",function(){
							updateSummary();
							updateCalculateChange();
						});
						$(document).on("change","#txtReceiptAmount",function(){
							updateCalculateChange();
						});
					});
					//Contador de documentos y mensaje de detalle vacio
					function fnRefreshDetailState(){
						var cantidad = $("#body_tb_transaction_master_detail tr.row_razon").length;
						$("#pv2DetailCount").text(cantidad);
						$("#pv2EmptyDetail").toggle(cantidad == 0);
						$("#pv2DetailWrap").toggle(cantidad > 0);
					}
					//Muestra el total y el cambio en la barra inferior del telefono
					function fnSyncMobileBar(){
						var cambio = pv2Num($("#txtChangeAmount").val());
						$("#pv2MbarTotal").text(pv2Fmt($("#txtTotal").val()));
						$("#pv2MbarChange").text(pv2Fmt(cambio)).toggleClass("is-negative", cambio < 0);
						$(".pv2-change").toggleClass("is-negative", cambio < 0);
					}
					function updateCalculateChange(){
						console.info("updateCalculateChange");
						var i = pv2Num($("#txtTotal").val());
						var x = pv2Num($("#txtReceiptAmount").val());
						$("#txtChangeAmount").val(pv2Fmt(x-i));
						fnSyncMobileBar();
					}
				
					function updateSummary(){
						console.info("updateSummary");
						var total = 0;
						$(".txtBalance").each(function(index,obj){
							total = total + pv2Num($(obj).val());
						});
						$("#txtTotal").val(pv2Fmt(total));
						fnRefreshDetailState();
						fnSyncMobileBar();
					}
					function onCompleteNewShare(objResponse){
						console.info("CALL onCompleteNewShare");	
						
						var objRow 						= {};
						objRow.checked 					= false;
						objRow.transactionMasterDetail 	= 0;						
						objRow.customerCreditDocumentID = objResponse[0][0];									/*customerCreditDocumentID*/
						objRow.documentNumber		 	= objResponse[0][1];									/*documentNumber*/
						objRow.nameMoneda			 	= objResponse[0][3];									/*moneda*/
						objRow.balance					= fnFormatFloat(objResponse[0][4],2);	/*saldo*/						
						
						
						
						//Validar si esta el item
						for(var i = 0 ; i < $(".classDetailItem").length; i++){
								var x  = $(($(".classDetailItem")[i])).val(); 
								var y  = objRow.customerCreditDocumentID;
								
								if(x == y){
									fnShowNotification("El Documento ya esta agregado","error");
									return;
								}
								
						}
						
						
						var tmpl = $($("#tmpl_row_document").html());
						tmpl.find("#txtDetailCustomerCreditDocumentID").attr("value",objRow.customerCreditDocumentID);						
						tmpl.find("#txtDetailTransactionMaster").attr("value",objRow.transactionMasterDetail);						
						tmpl.find("#txtDocument").text(objRow.documentNumber);
						tmpl.find("#txtNameMoneda").text(objRow.nameMoneda);
						//Con formato 1,000.00: la mascara lee de derecha a izquierda y un 1500 sin decimales quedaria como 15.00
						tmpl.find("#txtBalance").attr("value",pv2Fmt(objRow.balance));
						
						$("#body_tb_transaction_master_detail").append(tmpl);
						refreschChecked();
						
						updateSummary();
						updateCalculateChange();
					}
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}
					
						
						return result;
					}
					
					function refreschChecked(){
						$("[type='checkbox']").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
						pv2Mask($('.pv2 .txt-numeric'));
					}
					
				</script>