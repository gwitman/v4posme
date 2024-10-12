				<!-- ./ page heading -->
				<script>
					var site_url 	  = "<?php echo base_url(); ?>";
					
					$(document).ready(function(){	
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-afx-fixedassent" ).attr("method","POST");
								$( "#form-new-afx-fixedassent" ).attr("action","<?php echo base_url(); ?>/app_afx_fixedassent/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-afx-fixedassent" ).submit();
								}
								
						});
						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_afx_fixedassent/delete",
									data 		: {companyID : <?php echo $objFA->companyID;?>, branchID : <?php echo $objFA->branchID;?> , fixedAssentID : <?php echo $objFA->fixedAssentID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_afx_fixedassent/index";
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
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentFA->componentID."/componentItemID/".$objFA->fixedAssentID; ?>","blanck");
						});
						//Buscar el Asignado A
						$(document).on("click","#btnSearchEmployee",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee; 
						});
						//Eliminar Asignado A
						$(document).on("click","#btnClearEmployee",function(){
									$("#txtAsignedEmployeeID").val("");
									$("#txtAsignedEmployeeDescripcion").val("");
						});
						
					});
					function onCompleteEmployee(objResponse){
						console.info("CALL onCompleteEmployee");
						
						$("#txtAsignedEmployeeID").val(objResponse[0][2]);
						$("#txtAsignedEmployeeDescripcion").val(objResponse[0][3] + " / " + objResponse[0][4]);
						
					}
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Categoria
						if($("#txtCategoryID").val() == ""){
							fnShowNotification("Seleccionar la Categoria","error",timerNotification);
							result = false;
						}
						//Tipo
						if($("#txtTypeID").val() == ""){
							fnShowNotification("Seleccionar el Tipo","error",timerNotification);
							result = false;
						}
						//Nombre
						if($("#txtName").val() == ""){
							fnShowNotification("Escribir el Nombre","error",timerNotification);
							result = false;
						}
						
						return result;
						
					}
				</script>