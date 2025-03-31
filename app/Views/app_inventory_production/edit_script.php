				<!-- ./ page heading -->
				<script>
					$(document).ready(function() {

						var isInputTableOnEditionMode 		= false;
						var isOutputTableOnEditionMode 		= false;
						var selectedItemRowInInputTable 	= null;
						var selectedItemRowInOutputTable 	= null;
						var $mySidebarItemInput 			= $("#mySidebarItemInput");
						var $mySidebarItemOutput 			= $("#mySidebarItemOutput");
						var $bodyInputTable 				= $("#body_tb_transaction_master_detail_item_input");
						var $bodyOutputTable 				= $("#body_tb_transaction_master_detail_item_output");
						var numberDecimal					= 2;
						var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';  

						$('#txtDate').datepicker({
							format: "yyyy-mm-dd"
						});
						$('#txtDate').val();
						$("#txtDate").datepicker("update");
						// $('.txt-numeric').mask('000,000.00', {reverse: true}).css('text-align', 'left');

						$('.txt-numeric').css('text-align', 'left');

						$('.txt-numeric').each(function() {
							let rawValue 		= $(this).val().replace(/,/g, ''); 
							if (!isNaN(rawValue) && rawValue !== '') {
								let formattedValue = fnFormatNumber(rawValue, numberDecimal);
								$(this).val(formattedValue);
							}
						});				

						$('.txt-numeric').on('input', function() {
							let rawValue = $(this).val().replace(/,/g, ''); 
							if (!isNaN(rawValue) && rawValue !== '') {
								let formattedValue = fnFormatNumber(rawValue,numberDecimal);
								$(this).val(formattedValue);
							}
						});

						//Regresar a la lista
						$(document).on("click", "#btnBack", fnWaitOpen)

						//Guardar datos
						$(document).on("click", "#btnAcept", function() {
							$("#form-new-production-order").attr("method", "POST");
							$("#form-new-production-order").attr("action", "<?php echo base_url(); ?>/app_inventory_production/save/edit");

							if (validateForm()) {
								fnWaitOpen();
								$("#form-new-production-order").submit();
							}

						});

						$(document).on("click", "#btnClickArchivo", function() {
							window.open("<?php echo base_url() . "/core_elfinder/index/componentID/" . $objComponentProductionOrder->componentID . "/componentItemID/" . $objTM->transactionMasterID; ?>", "blanck");
						});

						$(document).on("click", "#btnDelete", function() {
							fnShowConfirm("Confirmar..", "Desea eliminar este Registro...", function() {
								fnWaitOpen();
								$.ajax({
									cache: false,
									dataType: 'json',
									type: 'POST',
									url: "<?php echo base_url(); ?>/app_inventory_production/delete",
									data: {
										companyID: <?= $objTM->companyID; ?>,
										transactionID: <?= $objTM->transactionID; ?>,
										transactionMasterID: <?= $objTM->transactionMasterID; ?>
									},
									success: function(data) {
										console.info("complete delete success");
										fnWaitClose();
										if (data.error) {
											fnShowNotification(data.message, "error");
										} else {
											window.location = "<?php echo base_url(); ?>/app_inventory_production/index";
										}
									},
									error: function(xhr, data) {
										console.info("complete delete error");
										fnWaitClose();
										fnShowNotification("Error 505", "error");
									}
								});
							});
						});

						$(document).on("click", "#btnPrinter", function() {
							fnWaitOpen();
							window.open("<?php echo base_url(); ?>" + "/" + "/app_inventory_production/viewPrinterFormatoA4" + "/companyID/<?= $objTM->companyID; ?>/transactionID/<?= $objTM->transactionID; ?>/transactionMasterID/<?= $objTM->transactionMasterID; ?>", '_blank');
							fnWaitClose();
						});

						//Buscar colaborador solicitadodr
						$(document).on("click", "#btnSearchRequestEmployee", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteRequestEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteRequestEmployee = onCompleteRequestEmployee;
						});

						//Buscar colaborador enviador
						$(document).on("click", "#btnSearchSenderEmployee", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteSenderEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteSenderEmployee = onCompleteSenderEmployee;
						});

						//Limpiar colabodor solicitador
						$(document).on("click", "#btnClearRequestEmployee", function() {
							$("#txtRequestEmployeeID, #txtRequestEmployeeDescription").val("");
						});

						//Limpiar colabodor enviador
						$(document).on("click", "#btnClearSenderEmployee", function() {
							$("#txtSenderEmployeeID, #txtSenderEmployeeDescription").val("");
						});

						//Abrir barra lateral para insertar un producto insumo
						$(document).on("click", "#btnNewDetailTransactionItemInput", function() {
							$("#btnSaveItemInput").focus();
							$mySidebarItemInput.css("width", "500px");
							$mySidebarItemOutput.css("width", "0");
						});

						//Abrir barra lateral para insertar un producto resultado
						$(document).on("click", "#btnNewDetailTransactionItemOutput", function() {
							$("#btnSaveSidebarItemOutput").focus();
							$mySidebarItemOutput.css("width", "500px");
							$mySidebarItemInput.css("width", "0");
						});

						//Buscar producto de insumo
						$(document).on("click", "#btnSidebarSearchRequestItem", function() {
							var responseItemWarehouseID = $("#txtSidebarItemSourceWarehouse").val();

							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteSidebarRequestItem/SELECCIONAR_ITEM_PAGINATED/true/" + 
								encodeURI('{' + '\"warehouseID\"|\"' + responseItemWarehouseID + '\"' + ',\"currencyID\"|\"' + $("#txtCurrencyID").val() + '\"' + '}') + 
								"/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";				
							
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteSidebarRequestItem = onCompleteSidebarRequestItem;
						});

						//Eliminar producto de insumo
						$(document).on("click", "#btnSidebarClearRequestItem", function() {
							fnCalculateSidebarInputTotal();
							fnClearSidebarInputs();
						});

						//Buscar producto de destino desde barra lateral de insumos
						$(document).on("click", "#btnSidebarSearchDestinationItem", function() {
							var responseItemWarehouseID = $("#txtSidebarResponseItemTargetWarehouse").val();
							var currencyID 				= $("#txtCurrencyID").val();

							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteSidebarDestinationItem/SELECCIONAR_ITEM_PAGINATED/true/" + 
								encodeURI('{' + '\"warehouseID\"|\"' + responseItemWarehouseID + '\"' + ',\"currencyID\"|\"' + currencyID + '\"' + '}') + 
								"/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
							
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteSidebarDestinationItem = onCompleteSidebarDestinationItem;
						});

						//Buscar producto de destino desde barra lateral de productos resultantes
						$(document).on("click", "#btnSidebarSearchResponseItem", function() {
							var responseItemWarehouseID = $("#txtSidebarResponseItemTargetWarehouse").val();
							var currencyID = $("#txtCurrencyID").val();

							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteSidebarOutputResponseItem/SELECCIONAR_ITEM_PAGINATED/true/" + 
								encodeURI('{' + '\"warehouseID\"|\"' + responseItemWarehouseID + '\"' + ',\"currencyID\"|\"' + currencyID + '\"' + '}') + 
								"/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
							
							window.open(url_request, "MsgWindow", "width=900,height=450");
							window.onCompleteSidebarOutputResponseItem = onCompleteSidebarOutputResponseItem;
						});

						//Eliminar producto de destino en barra lateral de insumos
						$(document).on("click", "#btnSidebarClearDestinationItem", fnClearSidebarDestinationItemInput);

						//Eliminar producto de destino en barra lateral de resultados
						$(document).on("click", "#btnSidebarClearResponseItem", fnClearSidebarResponseItemOutput);

						$(document).on("click", "#btnCloseSidebarItemInput", function() {
							$mySidebarItemInput.css("width", "0");
						});

						$(document).on("click", "#btnCloseSidebarItemOutput", function() {
							$mySidebarItemOutput.css("width", "0");
						});

						//Guardar datos ingresados en barra lateral de insumos
						$(document).on("click", "#btnSaveSidebarItemInput", function() {
							var timerNotification = 15000;

							if (!fnValidateSidebarInputRecord()) return;

							if (isInputTableOnEditionMode) {
								$bodyInputTable.find("tr").each(function(index, obj) {
									if (index == selectedItemRowInInputTable) {
										if (!fnUpdateInputTableRow(obj)) return;
										isInputTableOnEditionMode = false;
										selectedItemRowInInputTable = null;
									}
								});
							} else {
								if (!fnInitializeInputItemsTable()) return;
							}

							fnClearSidebarInputs();
							$mySidebarItemInput.css("width", "0");
						});

						//Guardar datos ingresados en barra lateral de resultados
						$(document).on("click", "#btnSaveSidebarItemOutput", function() {

							if (!fnValidateSidebarOutputRecord()) return;

							if (isOutputTableOnEditionMode) {
								$bodyOutputTable.find("tr").each(function(index, obj) {
									if (index == selectedItemRowInOutputTable) {
										if(!fnUpdateOutputTableRow(obj, index)) return;
										isOutputTableOnEditionMode = false;
										selectedItemRowInOutputTable = null;
									}
								});
							} else {
								if (!fnInitializeOutputItemsTable()) return;
							}

							fnClearSidebarOutputs();
							$mySidebarItemOutput.css("width", "0");
						});

						// Eliminar un registro de la tabla de insumos
						$(document).on("click", "#btnDeleteDetailTransactionItemInput", function() {
							$(".txtCheckedIsActive:checked").parents("tr").remove();
							fnCalculateTransactionTotalAmount();
							fnCalculateOutputItemTotalAmountInTable();
						});

						// Eliminar un registro de la tabla de productos resultado
						$(document).on("click", "#btnDeleteDetailTransactionItemOutput", function() {
							$(".txtOutputCheckedIsActive:checked").parents("tr").remove();
						});

						// Seleccionar un registro de la tabla de insumos para Editar
						$(document).on("click", "#btnEditDetailTransactionItemInput", function() {

							var activeRowsCount = fnIsOnlyOneRowSelectedInTable($bodyInputTable, ".txtCheckedIsActive");
							if(!activeRowsCount) return;

							$bodyInputTable.find("tr").each(function(index, obj) {
								if ($(obj).find(".txtCheckedIsActive").prop("checked")) {
									fnPopulateSidebarInputFields(obj, index);
									isInputTableOnEditionMode = true;
									selectedItemRowInInputTable = index;
									$mySidebarItemInput.css("width", "500px");
									$mySidebarItemOutput.css("width", "0");
								}
							});
						});

						// Seleccionar un registro de la tabla de productos resultados para Editar
						$(document).on("click", "#btnEditDetailTransactionItemOutput", function() 
						{
							var activeRowsCount = fnIsOnlyOneRowSelectedInTable($bodyOutputTable, ".txtOutputCheckedIsActive");
							if(!activeRowsCount) return;

							$bodyOutputTable.find("tr").each(function(index, obj) {
								if ($(obj).find(".txtOutputCheckedIsActive").prop("checked")) {
									fnPopulateSidebarOutputFields(obj, index);
									isOutputTableOnEditionMode = true;
									selectedItemRowInOutputTable = index;
									$mySidebarItemOutput.css("width", "500px");
									$mySidebarItemInput.css("width", "0");
								}
							});
						});

						//Acutalizar el monto total de Barra lateral de Insumos cuando se actualize el input
						$(document).on("change", "#txtSidebarRequestItemQuantity", fnCalculateSidebarInputTotal);

						//Acutalizar el monto total de Barra lateral de Productos Resultados cuando se actualize el input
						$(document).on("change", "#txtSidebarResponseItemQuantity", fnCalculateSidebarOutputTotal);

						//Limpiar Insumo cuando se cambie la bodega de origen.
						$(document).on("change", "#txtSidebarItemSourceWarehouse", fnClearSidebarRequestItemInput);

						//Limpiar Producto Resultante cuando se cambie la bodega de destino.
						$(document).on("change", "#txtSidebarResponseItemTargetWarehouse", fnClearSidebarResponseItemOutput);
					});


					function validateForm() {
						var result = true;
						var timerNotification = 15000;
						var date = new Date($("#txtDate").val());

						if (isNaN(date.valueOf())) {
							fnShowNotification("Ingrese la Fecha", "error", timerNotification);
							result = false;
						}

						//Validar que haya al menos un registro en la tabla de insumos
						if ($("#body_tb_transaction_master_detail_item_input").find("tr").length == 0) {
							fnShowNotification("Debe ingresar al menos un insumo", "error", timerNotification);
							result = false;
						}

						//Validar que haya al menos un registro en la tabla de productos resultantes
						if ($("#body_tb_transaction_master_detail_item_output").find("tr").length == 0) {
							fnShowNotification("Debe ingresar al menos un producto resultante", "error", timerNotification);
							result = false;
						}

						//Validar que la cantidad de productos destino sea igual a la cantidad de productos resultantes
						if (!fnValidateDestinationItemsEqualOutputItems()) {
							fnShowNotification("Los Insumos no coinciden con los Resultados", "error", timerNotification);
							result = false;
						}

						return result;
					}

					function refreschChecked() {
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
						$('.txt-numeric').mask('000,000.00', {
							reverse: true
						});
					}

					function onCompleteRequestEmployee(objResponse) {
						console.info("CALL onCompleteRequestEmployee");
						$("#txtRequestEmployeeID").val(objResponse[0][2]);
						$("#txtRequestEmployeeDescription").val(objResponse[0][3] + " | " + objResponse[0][4]);
					}

					function onCompleteSenderEmployee(objResponse) {
						console.info("CALL onCompleteSenderEmployee");
						$("#txtSenderEmployeeID").val(objResponse[0][2]);
						$("#txtSenderEmployeeDescription").val(objResponse[0][3] + " | " + objResponse[0][4]);
					}

					function fnCalculateTransactionTotalAmount() {
						var totalAmount = 0;

						$(".txtItemInputTotalCost").each(function() {
							var value = parseFloat($(this).val()) || 0;
							totalAmount += value;
						});

						$("#txtTransactionTotalAmount").val(totalAmount.toFixed(2));
					}

					function fnClearSidebarDestinationItemInput() {
						$("#txtSidebarDestinationItemID").val("");
						$("#txtSidebarDestinationItemDescription").val("");
					}

					function fnClearSidebarResponseItemOutput() {
						$("#txtSidebarResponseItemID").val("");
						$("#txtSidebarResponseItemDescription").val("");
						$("#txtSidebarResponseItemQuantity").val("");
						$("#txtSidebarOutputTotalCost").val("");
					}

					function fnClearSidebarRequestItemInput()
					{
						$("#txtSidebarRequestItemID").val("");
						$("#txtSidebarRequestItemDescription").val("");
						$("#txtSidebarRequestItemQuantity").val("");
						$("#txtSidebarInputTotalCost").val("");
					}

					function fnIsOnlyOneRowSelectedInTable($bodyTable, selector) {
						var activeRowsCount = $bodyTable.find("tr").filter(function() {
							return $(this).find(selector).prop("checked");
						}).length;

						if(activeRowsCount > 1)
						{
							fnShowNotification("Solo puede editar un registro a la vez", "error", 15000);
							return false;
						}

						return true;
					}

					//Funciones relacionadas al manejo de los insumos
					function fnPopulateSidebarInputFields(obj, index) {
						var itemID 						= $(obj).find("#txtItemInputID").val();
						var itemCode 					= $(obj).find("#txtItemInputCode").text();
						var itemName 					= $(obj).find("#txtItemInputName").text();
						var itemQuantity 				= $(obj).find("#txtItemInputQuantity").val();
						var itemTotalCost 				= $(obj).find("#txtItemInputTotalCost").val();
						var itemUnitaryCost 			= $(obj).find("#txtItemInputUnitaryCost").val();
						var itemWarehouseSourceID 		= $(obj).find("#txtItemInputWarehouseSourceID").val();
						var itemProductDestinationID 	= $(obj).find("#txtItemInputProductDestinationID").val();
						var itemProductDestinationName 	= $(obj).find("#txtItemInputProductDestination").val();

						$("#txtSidebarRequestItemID").val(itemID);
						$("#txtSidebarRequestItemDescription").val(itemCode + " | " + itemName);
						$("#txtSidebarRequestItemUnitaryCost").val(itemUnitaryCost);
						$("#txtSidebarRequestItemQuantity").val(itemQuantity);
						$("#txtSidebarInputTotalCost").val(itemTotalCost);
						$("#txtSidebarItemSourceWarehouse").val(itemWarehouseSourceID);
						$("#txtSidebarDestinationItemID").val(itemProductDestinationID);
						$("#txtSidebarDestinationItemDescription").val(itemProductDestinationName);
					}

					function onCompleteSidebarRequestItem(objResponse) {
						console.info("CALL onCompleteSidebarRequestItem");

						var itemID 			= objResponse[0][1];
						var itemCode 		= objResponse[0][2];
						var itemName 		= objResponse[0][3];
						var itemUnitaryCost = objResponse[0][6];

						$("#txtSidebarRequestItemID").val(itemID);
						$("#txtSidebarRequestItemDescription").val(itemCode + " | " + itemName);
						$("#txtSidebarRequestItemUnitaryCost").val(itemUnitaryCost);

						fnCalculateSidebarInputTotal();
					}

					function fnCalculateSidebarInputTotal() {
						var unitaryCost = parseFloat($("#txtSidebarRequestItemUnitaryCost").val()) || 0;
						var Quantity 	= parseFloat($("#txtSidebarRequestItemQuantity").val()) || 0;
						var total 		= unitaryCost * Quantity;
						$("#txtSidebarInputTotalCost").val(total);
					}

					function fnValidateSidebarInputRecord() {
						console.info("CALL fnValidateSidebarInputRecord");
						var result 							= true;
						var timerNotification 				= 15000;
						var itemRequestID 					= $("#txtSidebarRequestItemID").val();
						var itemRequestDestinationProductID = $("#txtSidebarDestinationItemID").val();
						var itemRequestQuantity 			= parseFloat($("#txtSidebarRequestItemQuantity").val()) || 0;

						//validar que exista un producto origen y un producto destino
						if (itemRequestID == "" || itemRequestDestinationProductID == "") {
							fnShowNotification("Debe seleccionar un producto origen y un producto destino", "error", timerNotification);
							result = false;
						}

						//validar que el producto origen no sea igual al producto destino
						if (itemRequestID == itemRequestDestinationProductID && itemRequestID != "") {
							fnShowNotification("El producto origen no puede ser igual al producto destino", "error", timerNotification);
							result = false;
						}

						//Validar que la cantidad sea mayor que cero
						if (itemRequestQuantity <= 0) {
							fnShowNotification("La cantidad debe ser mayor a cero", "error", timerNotification);
							result = false;
						}

						return result;
					}

					function fnIsInputItemAlreadyInOutputList() {
						var timerNotification 	= 15000;
						var itemID 				= $("#txtSidebarRequestItemID").val();
						var isInputInOutputList = false;

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(index, obj) {
							var itemOutputID = $(obj).find("#txtItemOutputID").val();
							if (itemID == itemOutputID) {
								fnShowNotification("El producto origen ya se encuentra en la lista de productos resultantes", "error", timerNotification);
								isInputInOutputList = true;
								return false;
							}
						});

						return isInputInOutputList;
					}

					function fnAreThereMatchesInInputAndOutputLists() {
						var newItemInputID 		= $("#txtSidebarRequestItemID").val();
						var newItemOutputID 	= $("#txtSidebarDestinationItemID").val();
						var timerNotification 	= 15000;
						var areThereMatches 	= false;

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemProductDestinationID 	= $(obj).find("#txtItemInputProductDestinationID").val();
							var itemInputID 				= $(obj).find("#txtItemInputID").val();

							if (newItemInputID == itemProductDestinationID) {
								fnShowNotification("El producto origen ya se encuentra registrado como un producto destino", "error", timerNotification);
								areThereMatches = true;
								return true;
							}

							if (newItemOutputID == itemInputID) {
								fnShowNotification("El producto destino ya se encuentra registrado como un producto origen", "error", timerNotification);
								areThereMatches = true;
								return true;
							}
						});

						return areThereMatches;
					}

					function fnInitializeInputItemsTable() {
						console.info("CALL fnInitializeInputItemsTable");

						var itemRequestID 						= $("#txtSidebarRequestItemID").val();
						var itemRequestCode 					= $("#txtSidebarRequestItemDescription").val().split("|")[0].trim();
						var itemRequestName 					= $("#txtSidebarRequestItemDescription").val().split("|")[1].trim();
						var itemRequestQuantity 				= parseFloat($("#txtSidebarRequestItemQuantity").val()) || 0;
						var itemRequestTotalCost 				= $("#txtSidebarInputTotalCost").val();
						var itemRequestUnitaryCost 				= $("#txtSidebarRequestItemUnitaryCost").val();
						var itemRequestSourceWarehouseID 		= $("#txtSidebarItemSourceWarehouse").val();
						var itemRequestDestinationProductID 	= $("#txtSidebarDestinationItemID").val();
						var itemRequestDestinationProductName 	= $("#txtSidebarDestinationItemDescription").val();
						var itemRequestWarehouseSourceName 		= $("#txtSidebarItemSourceWarehouse option:selected").data("name");

						if (fnAreThereMatchesInInputAndOutputLists()) {
							return false;
						}

						if (fnIsInputItemAlreadyInOutputList()) {
							return false;
						}

						var tmpl = $($("#tmpl_row_detail_item_input").html());
						tmpl.find("#txtItemInputID").val(itemRequestID);
						tmpl.find("#txtItemInputCode").text(itemRequestCode);
						tmpl.find("#txtItemInputName").text(itemRequestName);
						tmpl.find("#txtItemInputQuantity").val(itemRequestQuantity);
						tmpl.find("#txtItemInputTotalCost").val(itemRequestTotalCost);
						tmpl.find("#txtItemInputUnitaryCost").val(itemRequestUnitaryCost);
						tmpl.find("#txtItemInputWarehouseSource").val(itemRequestWarehouseSourceName);
						tmpl.find("#txtItemInputWarehouseSourceID").val(itemRequestSourceWarehouseID);
						tmpl.find("#txtItemInputProductDestination").val(itemRequestDestinationProductName);
						tmpl.find("#txtItemInputProductDestinationID").val(itemRequestDestinationProductID);
						$("#body_tb_transaction_master_detail_item_input").append(tmpl);
						return true;
					}

					function onCompleteSidebarDestinationItem(objResponse) {
						console.info("CALL onCompleteSidebarDestinationItem");

						var itemID 		= objResponse[0][1];
						var itemCode 	= objResponse[0][2];
						var itemName 	= objResponse[0][3];

						$("#txtSidebarDestinationItemID").val(itemID);
						$("#txtSidebarDestinationItemDescription").val(itemCode + " | " + itemName);
					}

					function fnClearSidebarInputs() {
						$("#txtSidebarRequestItemID, #txtSidebarRequestItemDescription, #txtSidebarRequestItemUnitaryCost, #txtSidebarRequestItemQuantity, #txtSidebarInputTotalCost").val("");
						$("#txtSidebarDestinationItemID, #txtSidebarDestinationItemDescription").val("");
						fnCalculateTransactionTotalAmount();
						fnCalculateOutputItemTotalAmountInTable();
					}

					function fnUpdateInputTableRow(obj) {
						var itemRequestID 						= $("#txtSidebarRequestItemID").val();
						var itemRequestDescription 				= $("#txtSidebarRequestItemDescription").val();
						var itemRequestQuantity 				= parseFloat($("#txtSidebarRequestItemQuantity").val()) || 0;
						var itemRequestTotalCost 				= $("#txtSidebarInputTotalCost").val();
						var itemRequestUnitaryCost 				= $("#txtSidebarRequestItemUnitaryCost").val();
						var itemRequestSourceWarehouseID 		= $("#txtSidebarItemSourceWarehouse").val();
						var itemRequestDestinationProductID 	= $("#txtSidebarDestinationItemID").val();
						var itemRequestDestinationProductName 	= $("#txtSidebarDestinationItemDescription").val();
						var itemRequestWarehouseSourceName 		= $("#txtSidebarItemSourceWarehouse option:selected").data("name");

						if (fnAreThereMatchesInInputAndOutputLists()) {
							return false;
						}

						if (fnIsInputItemAlreadyInOutputList()) {
							return false;
						}

						$(obj).find("#txtItemInputID").val(itemRequestID);
						$(obj).find("#txtItemInputCode").text(itemRequestDescription.split("|")[0].trim());
						$(obj).find("#txtItemInputName").text(itemRequestDescription.split("|")[1].trim());
						$(obj).find("#txtItemInputQuantity").val(itemRequestQuantity);
						$(obj).find("#txtItemInputTotalCost").val(itemRequestTotalCost);
						$(obj).find("#txtItemInputUnitaryCost").val(itemRequestUnitaryCost);
						$(obj).find("#txtItemInputWarehouseSource").val(itemRequestWarehouseSourceName);
						$(obj).find("#txtItemInputWarehouseSourceID").val(itemRequestSourceWarehouseID);
						$(obj).find("#txtItemInputProductDestination").val(itemRequestDestinationProductName);
						$(obj).find("#txtItemInputProductDestinationID").val(itemRequestDestinationProductID);
						return true;
					}


					//Funciones relacionadas al manejo de los productos resultantes
					function fnPopulateSidebarOutputFields(obj, index) {
						var itemID 					= $(obj).find("#txtItemOutputID").val();
						var itemCode 				= $(obj).find("#txtItemOutputCode").text();
						var itemName 				= $(obj).find("#txtItemOutputName").text();
						var itemQuantity 			= $(obj).find("#txtItemOutputQuantity").val();
						var itemTotalCost 			= $(obj).find("#txtItemOutputTotalCost").val();
						var itemUnitaryCost 		= $(obj).find("#txtItemOutputUnitaryCost").val();
						var itemWarehouseTargetID 	= $(obj).find("#txtItemOutputWarehouseTargetID").val();

						$("#txtSidebarResponseItemID").val(itemID);
						$("#txtSidebarResponseItemDescription").val(itemCode + " | " + itemName);
						$("#txtSidebarResponseItemQuantity").val(itemQuantity);
						$("#txtSidebarOutputTotalCost").val(itemTotalCost);
						$("#txtSidebarResponseItemUnitaryCost").val(itemUnitaryCost);
						$("#txtSidebarResponseItemTargetWarehouse").val(itemWarehouseTargetID);
					}

					function onCompleteSidebarOutputResponseItem(objResponse) {
						console.info("CALL onCompleteSidebarOutputResponseItem");

						var itemID = objResponse[0][1];
						var itemCode = objResponse[0][2];
						var itemName = objResponse[0][3];

						$("#txtSidebarResponseItemID").val(itemID);
						$("#txtSidebarResponseItemDescription").val(itemCode + " | " + itemName);

						fnCalculateSidebarOutputTotal();
					}

					function fnCalculateSidebarOutputTotal() {
						fnCalculateResponseItemTotalCost();
						var unitaryCost = parseFloat($("#txtSidebarResponseItemUnitaryCost").val()) || 0;
						var Quantity = parseFloat($("#txtSidebarResponseItemQuantity").val()) || 0;
						var total = unitaryCost * Quantity;
						debugger;
						$("#txtSidebarOutputTotalCost").val(total);
					}

					function fnCalculateResponseItemTotalCost() {
						console.info("CALL fnCalculateResponseItemTotalCost");

						var responseItemID = $("#txtSidebarResponseItemID").val();
						var totalInputCost = 0;

						if (!responseItemID) return;

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var destinationItemID = $(obj).find("#txtItemInputProductDestinationID").val();
							var inputTotalCost = parseFloat($(obj).find("#txtItemInputTotalCost").val()) || 0;

							if (destinationItemID === responseItemID) {
								totalInputCost += inputTotalCost;
							}
						});

						$("#txtSidebarResponseItemUnitaryCost").val(totalInputCost.toFixed(2));
					}

					function fnValidateSidebarOutputRecord() {
						console.info("CALL fnValidateSidebarOutputRecord");
						var result = true;
						var timerNotification = 15000;

						var itemResponseID = $("#txtSidebarResponseItemID").val();
						var itemResponseCode = $("#txtSidebarResponseItemDescription").val().split("|")[0].trim();
						var itemResponseName = $("#txtSidebarResponseItemDescription").val().split("|")[1].trim();
						var itemResponseQuantity = parseFloat($("#txtSidebarResponseItemQuantity").val()) || 0;
						var itemResponseTotalCost = $("#txtSidebarOutputTotalCost").val();
						var itemResponseTargetWarehouse = $("#txtSidebarResponseItemTargetWarehouse").val();

						if (itemResponseID == "") {
							fnShowNotification("Debe seleccionar un producto destino", "error", timerNotification);
							result = false;
						}

						if (itemResponseQuantity <= 0) {
							fnShowNotification("La cantidad debe ser mayor a cero", "error", timerNotification);
							result = false;
						}

						return result;
					}

					function fnIsOutputItemAlreadyInInputList() {
						var timerNotification = 15000;
						var itemID = $("#txtSidebarResponseItemID").val();
						var isOutputInInputList = false;

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemInputID = $(obj).find("#txtItemInputID").val();
							if (itemID == itemInputID) {
								fnShowNotification("El producto destino ya se encuentra en la lista de insumos", "error", timerNotification);
								isOutputInInputList = true;
								return false;
							}
						});

						return isOutputInInputList;
					}

					function fnIsOutputItemAlreadyInOutputList(index = null) {
						var timerNotification = 15000;
						var itemID = $("#txtSidebarResponseItemID").val();
						var isOutputInOutputList = false;

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(idx, obj) {
							var itemOutputID = $(obj).find("#txtItemOutputID").val();
							if (itemID == itemOutputID && (index === null || index !== idx)) {
								fnShowNotification("El producto destino ya se encuentra registrado", "error", timerNotification);
								isOutputInOutputList = true;
								return false;
							}
						});

						return isOutputInOutputList;
					}

					function fnInitializeOutputItemsTable() {
						console.info("CALL fnInitializeOutputItemsTable");

						var itemResponseID 					= $("#txtSidebarResponseItemID").val();
						var itemResponseCode 				= $("#txtSidebarResponseItemDescription").val().split("|")[0].trim();
						var itemResponseName 				= $("#txtSidebarResponseItemDescription").val().split("|")[1].trim();
						var itemResponseQuantity 			= parseFloat($("#txtSidebarResponseItemQuantity").val()) || 0;
						var itemResponseTotalCost 			= $("#txtSidebarOutputTotalCost").val();
						var itemResponseUnitaryCost 		= $("#txtSidebarResponseItemUnitaryCost").val();
						var itemResponseTargetWarehouseID 	= $("#txtSidebarResponseItemTargetWarehouse").val();
						var itemResponseTargetWarehouseName = $("#txtSidebarResponseItemTargetWarehouse option:selected").data("name");

						if (fnIsOutputItemAlreadyInInputList()) {
							return false;
						}

						if (fnIsOutputItemAlreadyInOutputList()) {
							return false;
						}

						var tmpl = $($("#tmpl_row_detail_item_output").html());
						tmpl.find("#txtItemOutputID").val(itemResponseID);
						tmpl.find("#txtItemOutputCode").text(itemResponseCode);
						tmpl.find("#txtItemOutputName").text(itemResponseName);
						tmpl.find("#txtItemOutputQuantity").val(itemResponseQuantity);
						tmpl.find("#txtItemOutputTotalCost").val(itemResponseTotalCost);
						tmpl.find("#txtItemOutputUnitaryCost").val(itemResponseUnitaryCost);
						tmpl.find("#txtItemOutputWarehouseTarget").val(itemResponseTargetWarehouseName);
						tmpl.find("#txtItemOutputWarehouseTargetID").val(itemResponseTargetWarehouseID);
						$("#body_tb_transaction_master_detail_item_output").append(tmpl);
						return true;
					}

					function fnCalculateOutputItemTotalAmountInTable() {
						console.info("CALL fnCalculateOutputItemTotalAmountInTable");
						var totalAmount = 0;
						var mapItemInputDestinationID = new Map();

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemInputID 			= $(obj).find("#txtItemInputID").val();
							var itemInputDestinationID 	= $(obj).find("#txtItemInputProductDestinationID").val();
							var itemInputTotalCost 		= parseFloat($(obj).find("#txtItemInputTotalCost").val()) || 0;

							if (mapItemInputDestinationID.has(itemInputDestinationID)) {
								var totalCost = mapItemInputDestinationID.get(itemInputDestinationID);
								totalCost += itemInputTotalCost;
								mapItemInputDestinationID.set(itemInputDestinationID, totalCost);
							} else {
								mapItemInputDestinationID.set(itemInputDestinationID, itemInputTotalCost);
							}
						});

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(index, obj) {
							var itemOutputID 			= $(obj).find("#txtItemOutputID").val();
							var itemOutputQuantity 		= parseFloat($(obj).find("#txtItemOutputQuantity").val()) || 0;

							if (mapItemInputDestinationID.size == 0) {
								$(obj).find("#txtItemOutputUnitaryCost").val("");
								$(obj).find("#txtItemOutputTotalCost").val("");
								return;
							}

							if (mapItemInputDestinationID.has(itemOutputID)) {
								$(obj).find("#txtItemOutputUnitaryCost").val(mapItemInputDestinationID.get(itemOutputID) / itemOutputQuantity);
								var itemOutputUnitaryCost 				= parseFloat($(obj).find("#txtItemOutputUnitaryCost").val()) || 0;
								$(obj).find("#txtItemOutputTotalCost").val(itemOutputUnitaryCost * itemOutputQuantity);
							} else {
								$(obj).find("#txtItemOutputUnitaryCost").val("");
								$(obj).find("#txtItemOutputTotalCost").val("");
							}
						});
					}

					function fnClearSidebarOutputs() {
						$("#txtSidebarResponseItemID, #txtSidebarResponseItemDescription, #txtSidebarResponseItemQuantity, #txtSidebarOutputTotalCost").val("");
						fnCalculateOutputItemTotalAmountInTable();
					}

					function fnUpdateOutputTableRow(obj, index) {
						var itemResponseID 					= $("#txtSidebarResponseItemID").val();
						var itemResponseDescription 		= $("#txtSidebarResponseItemDescription").val();
						var itemResponseQuantity 			= parseFloat($("#txtSidebarResponseItemQuantity").val()) || 0;
						var itemResponseTotalCost 			= $("#txtSidebarOutputTotalCost").val();
						var itemResponseUnitaryCost 		= $("#txtSidebarResponseItemUnitaryCost").val();
						var itemResponseTargetWarehouseID 	= $("#txtSidebarResponseItemTargetWarehouse").val();
						var itemResponseTargetWarehouseName = $("#txtSidebarResponseItemTargetWarehouse option:selected").data("name");

						if (fnIsOutputItemAlreadyInInputList()) {
							return false;
						}
						
						if (fnIsOutputItemAlreadyInOutputList(index)) return false;


						$(obj).find("#txtItemOutputID").val(itemResponseID);
						$(obj).find("#txtItemOutputCode").text(itemResponseDescription.split("|")[0].trim());
						$(obj).find("#txtItemOutputName").text(itemResponseDescription.split("|")[1].trim());
						$(obj).find("#txtItemOutputQuantity").val(itemResponseQuantity);
						$(obj).find("#txtItemOutputTotalCost").val(itemResponseTotalCost);
						$(obj).find("#txtItemOutputUnitaryCost").val(itemResponseUnitaryCost);
						$(obj).find("#txtItemOutputWarehouseTarget").val(itemResponseTargetWarehouseName);
						$(obj).find("#txtItemOutputWarehouseTargetID").val(itemResponseTargetWarehouseID);
						return true;
					}

					function fnValidateDestinationItemsEqualOutputItems() {
						var arrayItemInputDestinationID = [];
						var arrayItemOutputID = [];

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemInputDestinationID = $(obj).find("#txtItemInputProductDestinationID").val();
							if (arrayItemInputDestinationID.indexOf(itemInputDestinationID) == -1) {
								arrayItemInputDestinationID.push(itemInputDestinationID);
							}
						});

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(index, obj) {
							var itemOutputID = $(obj).find("#txtItemOutputID").val();
							if (arrayItemOutputID.indexOf(itemOutputID) == -1) {
								arrayItemOutputID.push(itemOutputID);
							}
						});

						if (arrayItemInputDestinationID.length != arrayItemOutputID.length) {
							return false;
						}

						for (var i = 0; i < arrayItemInputDestinationID.length; i++) {
							if (arrayItemOutputID.indexOf(arrayItemInputDestinationID[i]) == -1) {
								return false;
							}
						}

						return true;
					}
				</script>