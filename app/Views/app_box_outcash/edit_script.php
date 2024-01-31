				<!-- ./ page heading -->
				<script>		
					
					$(document).ready(function(){					
						$('#txtDate').datepicker({format:"yyyy-mm-dd"});						 
						$("#txtDate").datepicker("update");
						$('.txt-numeric').mask('000,000.00', {reverse: true});
						var urlPrinter = '<?php echo $objParameterUrlPrinter; ?>';
						updatePantalla();
						
						//Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						
						$(document).on("change",".denomination-quantity",function(){
							updateAmount();
						});
						
						$(document).on("change","#txtCurrencyID,#txtAreaID",function(){
							updatePantalla();
							updateAmount();
						});
						$(document).on("change","#txtAreaID",function(){
							updatePantalla();
							updateAmount();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_outcash/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}
								
						});
						
						$(document).on("click","#btnPrinter",function(){
									fnWaitOpen();
									window.open("<?php echo base_url(); ?>"+"/"+urlPrinter+"/companyID/<?php echo $objTransactionMaster->companyID;?>/transactionID/<?php echo $objTransactionMaster->transactionID;?>/transactionMasterID/<?php echo $objTransactionMaster->transactionMasterID;?>", '_blank');
									fnWaitClose();																	
						});
						
						
						$(document).on("click","#btnDelete",function(){							
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_box_outcash/delete",
									data 		: {companyID : <?php echo $objTransactionMaster->companyID;?>, transactionID : <?php echo $objTransactionMaster->transactionID;?>,transactionMasterID : <?php echo $objTransactionMaster->transactionMasterID; ?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_box_outcash/index";
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
							window.open("<?php echo base_url()."core_elfinder/index/componentID/".$objComponentShare->componentID."/componentItemID/".$objTransactionMaster->transactionMasterID; ?>","blanck");
						});
						
						$(document).on("change","#txtAreaID",function(){
							var TipoMovimiento = $(this).val();
							
							fnWaitOpen(); 
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByParentCatalogItemID" ,
								data		: {catalogItemID : TipoMovimiento, tableName : "tb_transaction_master_outputcash" , fieldName: "priorityID" },
								success		: fnCompletCatalogDetail,
								error:function(xhr,data){	
									fnWaitClose(); 
									console.info("complete data error");													
									fnShowNotification("Error 505","error");
								}
							});
							
						});
						
						
					});
					
					function fnCompletCatalogDetail(data)
					{
						debugger;
						fnWaitClose();
						data = data.catalogItems;
						
						
						$("#txtPriorityID").html("");
						$("#txtPriorityID").val("");
						
						for(var i = 0 ; i < data.length; i++)
						{
							if(i == 0)
								$("#txtPriorityID").append("<option value='"+data[i].catalogItemID+"' selected>"+ data[i].name + "</option>");
							else 
								$("#txtPriorityID").append("<option value='"+data[i].catalogItemID+"'>"+ data[i].name + "</option>");
							
						}
						
						$("#txtPriorityID").select2();
						
					}
					
					function updateAmount()
					{
						var total 		= $("#txtDetailAmount").val();
						var totalTmp 	= 0;
					
						if($("#txtCurrencyID").val()  == "1")
						{							
							$( ".currency-1  .denomination-quantity" ).each(function() {
							  var i = parseFloat($( this ).val());
							  var p = parseFloat($( this ).data("reference"));
							  totalTmp = totalTmp + (i * p );
							});							
						}
						else
						{							
							$( ".currency-2  .denomination-quantity" ).each(function() {
							  var i = parseFloat($( this ).val());
							  var p = parseFloat($( this ).data("reference"));
							  totalTmp = totalTmp + (i * p );
							});
							
						}
						
						
						$("#txtDetailAmount").val(totalTmp);
					}
					
					function updatePantalla()
					{
					
						
						if($("#txtCurrencyID").val()  == "1")
						{
							$(".currency-1").removeClass("hidden");
							$(".currency-2").addClass("hidden");
							
							$( ".currency-2  .denomination-quantity" ).each(function() {
							  $( this ).val("0")
							});
							
						}
						else
						{
							$(".currency-2").removeClass("hidden");
							$(".currency-1").addClass("hidden");
							$(".currency-1  .denomination-quantity" ).each(function() {
							  $( this ).val("0")
							});
							
						}
						
						
							
						if( $("#txtAreaID option:selected").text()  == "Cierre" )
						{							
							$("#txtDetailAmount").attr("readonly","true");
						}
						else
						{							
							$("#txtDetailAmount").removeAttr("readonly");
							$(".currency-1").addClass("hidden");
							$(".currency-2").addClass("hidden");
						}
						
						
					}
					
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
						
					
						return result;
					}
					
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						//$('.txtDebit').mask('000,000.00', {reverse: true});
						//$('.txtCredit').mask('000,000.00', {reverse: true});
						$('.txt-numeric').mask('000,000.00', {reverse: true});
					}
					
				</script>