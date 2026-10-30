				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
				</script>
				<?php echo view('app_med_query/med_v2_common'); ?>
				<script>

					$(document).ready(function(){
						mqv2BindCommon();
						mqv2InitDate("txtDate", true);
						mqv2InitDate("txtNextVisit", true);

						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(e){
								e.preventDefault();
								$( "#form-new-invoice" ).attr("method","POST");
								$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_med_query/save/new");

								if(validateForm())
								{
									fnWaitOpen();
									$( "#form-new-invoice" ).submit();
								}

						});

						//Buscar el paciente
						$(document).on("click","#btnSearchCustomer",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_ENTIDAD/true/empty/false/not_redirect_when_empty";
							mqv2OpenSearch(url_request, "Buscar paciente", "onCompleteCustomer", onCompleteCustomer, "width=900,height=450");
						});
					});

					function onCompleteCustomer(objResponse){
						console.info("CALL onCompleteCustomer");
						if(!objResponse || !objResponse.length || !objResponse[0]){
							return;
						}

						$("#txtCustomerID").val(objResponse[0][2]);
						$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][4] + " / " + objResponse[0][5]);
						mqv2SyncHeader();
					}

					function validateForm(){
						var result = mqv2ValidateCommon();
						return result;
					}

					//Se conserva por compatibilidad (los combos de la pantalla quedan nativos)
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect, .mqv2 select').uniform();
						$('.txt-numeric').mask('000,000.00', {reverse: true});
					}

				</script>