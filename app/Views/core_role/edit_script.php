				
				<script>
					//Variables Globales
					var objRowListElement 				= {};
					var objTableListElement 			= {};
					var objTableListElementAutorization = {};
					var site_url 						= "<?php echo base_url(); ?>/";
					var companyID 						= "<?php echo $objRole->companyID;?>";
					var branchID 						= "<?php echo $objRole->branchID;?>";
					var roleID	 						= "<?php echo $objRole->roleID;?>";
					
					$(document).ready(function(){
					
						objTableListElement = $("#ListElement").dataTable({
							"bPaginate"		: false,
							"bLengthChange"	: false,
							"bFilter"		: false,
							"bSort"			: true,
							"bInfo"			: false,
							"bAutoWidth"	: true							
						});
						objTableListElementAutorization = $("#ListElementAutorization").dataTable({
							"bPaginate"		: false,
							"bLengthChange"	: false,
							"bFilter"		: false,
							"bSort"			: true,
							"bInfo"			: false,
							"bAutoWidth"	: true							
						});
						//Comando Guardar
						$(document).on("click","#btnAcept",function(){
								fnWaitOpen();
								$( "#form-new-rol" ).attr("method","POST");
								$( "#form-new-rol" ).attr("action",site_url+"core_role/save");
								$( "#form-new-rol" ).submit();
						});
						//Comando regresar
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Comando Eliminar
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Rol...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: site_url+"core_role/delete",
									data 		: {companyID : companyID, branchID : branchID, roleID : roleID },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = site_url+"core_role/index";
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
						
						
						//Comando  Detalle Seleccionar
						$(document).on("click","#tbody_detail tr",function(event){		
								objRowListElement = this;
								fnTableSelectedRow(this,event);
						});
						$(document).on("click","#tbody_detail_autorization tr",function(event){		
							objRowListElement = this;
							fnTableSelectedRow(this,event);
						});
						
						//Comando Detalle Eliminar
						$(document).on("click","#btnDeleteDetail",function(){	
							fnShowConfirm("Confirmar..","Desea eliminar el Elemento de Acceso ...",function(){								
								objTableListElement.fnDeleteRow(objRowListElement);
							});							
						});	
						$(document).on("click","#btnDeleteDetailAutorization",function(){
							fnShowConfirm("Confirmar..","Desea eliminar el Elemento de Autorizacion ...",function(){								
								objTableListElementAutorization.fnDeleteRow(objRowListElement);
							});	
						});	
						
						//Comando Detalle Agregar
						$(document).on("click","#btnNewDetail",function(){								
								window.open(site_url+"core_role/add_subelement","MsgWindow","width=650,height=500");
								window.parentNewElement = parentNewElement;
						}); 
						//Evento Agregar Detalle Autorizaciones
						$(document).on("click","#btnNewDetailAutorization",function(){								
								window.open(site_url+"core_role/add_subelement_autorization","MsgWindow","width=650,height=500");
								window.parentNewElementAutorization = parentNewElementAutorization;
						}); 
						
					});
					//Detalle CallbackComplete Agregar Detalle 
					function getOrden(){							
							return this.data.txtElementIDDescription.split(" >>> ")[0];
					}
					function getDescription(){							
							return this.data.txtElementIDDescription.split(" >>> ")[1];
					}			
					function parentNewElement(data){	 					
					
							if(data.txtElementID == "" || data.txtSelectedID == "" || data.txtInsertedID == "" || data.txtDeletedID == "" || data.txtEditedID == ""){
								fnShowNotification("No es posible agregar el elemento...","error");
								return; 
							}
							
							var tmpl0 =		$.tmpl('<a href="#" >${getOrden()}</a>',data).text();
							
							var tmpl1 = 	$.tmpl(
											'<span>'+
											'${getDescription()}'+	
											'<input type="hidden" id="txtElementID" name="txtElementID[]" value="${txtElementID}" />'+
											'<input type="hidden" id="txtSelectedID" name="txtSelectedID[${txtElementID}]" value="${txtSelectedID}" />'+
											'<input type="hidden" id="txtInsertedID" name="txtInsertedID[${txtElementID}]" value="${txtInsertedID}" />'+
											'<input type="hidden" id="txtDeletedID" name="txtDeletedID[${txtElementID}]" value="${txtDeletedID}" />'+
											'<input type="hidden" id="txtEditedID" name="txtEditedID[${txtElementID}]" value="${txtEditedID}" />'+
											'</span>',data).html();							
							 
							var tmpl2 =		$.tmpl('<a href="#" >${txtSelectedIDDescription}</a>',data).text();
							var tmpl3 =		$.tmpl('<a href="#" >${txtInsertedIDDescription}</a>',data).text();
							var tmpl4 =		$.tmpl('<a href="#" >${txtDeletedIDDescription}</a>',data).text();
							var tmpl5 =		$.tmpl('<a href="#" >${txtEditedIDDescription}</a>',data).text();
							
							objTableListElement.fnAddData([tmpl0,tmpl1,tmpl2,tmpl3,tmpl4,tmpl5]);
							
					} 
					function parentNewElementAutorization(data){
						if(data.txtComponentAutorizationID == ""){
							fnShowNotification("No es posible agregar el elemento...","error");
							return;
						}
						var tmp1 =		$.tmpl('<span><input type="hidden" name="txtComponentAutorizationID[]" value="${txtComponentAutorizationID}" /> ${txtComponentAutorizationName}</span>',data).html();
						objTableListElementAutorization.fnAddData([tmp1]);
						
					} 
				</script>