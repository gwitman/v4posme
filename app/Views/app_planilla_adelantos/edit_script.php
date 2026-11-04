				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

					$(document).ready(function(){

						afxBindCommon();

						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});

						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(e){
								e.preventDefault();
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_planilla_adelantos/save/edit");

								if(validateForm()){
									fnWaitOpen();
									//El servidor debe recibir numeros puros, sin el formato visual
									plaLimpiarMontos("#form-new-invoice");
									$( "#form-new-invoice" ).submit();
								}

						});
						//Nueva factura
						$(document).on("click","#btnNewShare",function(){
							var url_request 			= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteNewShare/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							pv2OpenSearch(url_request,"Buscar colaborador","onCompleteNewShare",onCompleteNewShare,"width=1585,height=795");
						});
						//Eliminar factura
						$(document).on("click","#btnDeleteShare",function(){
							console.info("btnDeleteShare");
							$(".txtCheckedIsActive").each(function(i,obj){
									if($(obj).attr("checked") == "checked"){
										$(obj).parents("tr").first().remove();
									}
							});	
							
						});
						$(document).on("change","input.txtDetailShare",function(){
							updateSummary();
						});
					});
					
					function updateSummary(){
						console.info("updateSummary");
						var total = 0;
						
						$("#body_tb_transaction_master_detail tr").each(function(index,obj){
							//Un monto vacio cuenta como 0; el formato visual se aplica solo al escribir en el campo
							var neto 		= pv2Num($(obj).find("#txtNeto").val());

							$(obj).find("#txtNeto").val(afxFmtMonto(fnFormatNumber(neto,2)));
							total 			= total + neto;
						});

						$("#txtTotal").val(afxFmtMonto(fnFormatNumber(total,2)));
					}
					function onCompleteNewShare(objResponse){
						console.info("CALL onCompleteNewShare");	
						var objRow 									= {};
						objRow.checked 								= false;
						objRow.txtTransactionMasterDetailID 		= 0;
						objRow.txtEmployeeID						= objResponse[0][2];	/*companyID*/
						objRow.name 								= objResponse[0][3] + "/" + objResponse[0][4];	/*entityID*/
						
						//Validar si esta el item
						for(var i = 0 ; i < $(".classDetailItem").length; i++){
								var x  = $(($(".classDetailItem")[i])).val(); 
								var y  = objRow.txtEmployeeID;
								
								if(x == y){
									fnShowNotification("El Colaborador ya existe en el detalle","error");
									return;
								}
								
						}
						
						
						var tmpl = $($("#tmpl_row_document").html());
						tmpl.find("#txtTransactionMasterDetailID").attr("value",objRow.txtTransactionMasterDetailID);
						tmpl.find("#txtEmployeeID").attr("value",objRow.txtEmployeeID);
						tmpl.find("#txtDocument").text(objRow.name);
						
						$("#body_tb_transaction_master_detail").append(tmpl);
						refreschChecked();						
						updateSummary();
					}
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						return result;
					}
					
					function refreschChecked(){
						plaRefreshChecked();
					}
					
				</script>