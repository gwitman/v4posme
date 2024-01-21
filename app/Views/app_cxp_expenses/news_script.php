				<!-- ./ page heading -->
				<script>
					$(document).ready(function(){						 
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));	
						 $("#txtDate").datepicker("update");
						 $('.txt-numeric').mask('000,000.00', {reverse: true});
						 
						
						
						
						$(document).on("change","#txtCurrencyID",function(){
							updatePantalla();
							updateAmount();
						});
						
						$(document).on("change","#txtPriorityID",function(){
							var tipoGasto = $(this).val();
							
							fnWaitOpen(); 
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'GET',
								url  		: "<?php echo base_url(); ?>/app_public_catalog_api/getPublicCatalogDetail/companyID/<?php echo $companyID; ?>/publicCatalogDetailID/" + tipoGasto ,
								
								success		: fnCompletPublicCatalogDetail,
								error:function(xhr,data){	
									fnWaitClose(); 
									console.info("complete data error");													
									fnShowNotification("Error 505","error");
								}
							});
							
						});
						
						
						
						
		
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_cxp_expenses/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
					});
					
					
					
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}
						
						//Validar Monto
						if($("#txtDetailAmount").val() == "0"){
							fnShowNotification("El monto no puede ser 0","error",timerNotification);
							result = false;
						}
						
						if( $("#txtAreaID").val() === null )
						{
							fnShowNotification("Categoria de Gasto es obligatorio","error",timerNotification);
							result = false;
						}
						
						return result;
					}
					
					function fnCompletPublicCatalogDetail(data)
					{
						
						fnWaitClose();
						data = data.objGridView;
						
						
						$("#txtAreaID").html("");
						$("#txtAreaID").val("");
						
						for(var i = 0 ; i < data.length; i++)
						{
							if(i == 0)
								$("#txtAreaID").append("<option value='"+data[i].publicCatalogDetailID+"' selected>"+ data[i].name + "</option>");
							else 
								$("#txtAreaID").append("<option value='"+data[i].publicCatalogDetailID+"'>"+ data[i].name + "</option>");
							
						}
						
						$("#txtAreaID").select2();
						
					}
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
						$('.txt-numeric').mask('000,000.00', {reverse: true});
					}
					
					
					
				</script>