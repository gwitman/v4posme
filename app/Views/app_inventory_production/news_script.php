				<!-- ./ page heading -->
				<script>
					var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

					$(document).ready(function() {

						var isInputTableOnEditionMode 		= false;
						var isOutputTableOnEditionMode 		= false;
						var selectedItemRowInInputTable		= null;
						var selectedItemRowInOutputTable	= null;
						var $mySidebarItemInput 			= $("#mySidebarItemInput");
						var $mySidebarItemOutput 			= $("#mySidebarItemOutput");
						var $bodyInputTable 				= $("#body_tb_transaction_master_detail_item_input");
						var $bodyOutputTable 				= $("#body_tb_transaction_master_detail_item_output");
						var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';  

						pv2InitDate("txtDate", true);
						afxBindCommon();
						prodBindCommon();

						//Regresar a la lista
						$(document).on("click", "#btnBack", fnWaitOpen)

						//Guardar datos
						$(document).on("click", "#btnAcept", function() {
							$("#form-new-production-order").attr("method", "POST");
							$("#form-new-production-order").attr("action", "<?php echo base_url(); ?>/app_inventory_production/save/new");

							if (validateForm()) {
								fnWaitOpen();
								//El valor enviado no debe llevar comas
								afxLimpiarMontos("#form-new-production-order");
								$("#form-new-production-order").submit();
							}

						});

						//Buscar colaborador solicitadodr
						$(document).on("click", "#btnSearchRequestEmployee", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteRequestEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							pv2OpenSearch(url_request, "Buscar colaborador", "onCompleteRequestEmployee", onCompleteRequestEmployee, "width=900,height=450");
						});

						//Buscar colaborador enviador
						$(document).on("click", "#btnSearchSenderEmployee", function() {
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteSenderEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							pv2OpenSearch(url_request, "Buscar colaborador", "onCompleteSenderEmployee", onCompleteSenderEmployee, "width=900,height=450");
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
							$mySidebarItemInput.addClass("prod-sidebar-open");
							$mySidebarItemOutput.removeClass("prod-sidebar-open");
						});

						//Abrir barra lateral para insertar un producto resultado
						$(document).on("click", "#btnNewDetailTransactionItemOutput", function() {
							$("#btnSaveSidebarItemOutput").focus();
							$mySidebarItemOutput.addClass("prod-sidebar-open");
							$mySidebarItemInput.removeClass("prod-sidebar-open");
						});

						//Buscar producto de insumo
						$(document).on("click", "#btnSidebarSearchRequestItem", function() {
							var responseItemWarehouseID = $("#txtSidebarItemSourceWarehouse").val();

							var url_request	= "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteSidebarRequestItem/SELECCIONAR_ITEM_PAGINATED/true/"+
								encodeURI('{' + '\"warehouseID\"|\"' + responseItemWarehouseID + '\"' + ',\"currencyID\"|\"' + $("#txtCurrencyID").val() + '\"' + '}') + 
								"/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";

							pv2OpenSearch(url_request, "Buscar producto", "onCompleteSidebarRequestItem", onCompleteSidebarRequestItem, "width=900,height=450");
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
							
							pv2OpenSearch(url_request, "Buscar producto", "onCompleteSidebarDestinationItem", onCompleteSidebarDestinationItem, "width=900,height=450");
						});

						//Buscar producto de destino desde barra lateral de productos resultantes
						$(document).on("click", "#btnSidebarSearchResponseItem", function() {
							var responseItemWarehouseID = $("#txtSidebarResponseItemTargetWarehouse").val();
							var currencyID = $("#txtCurrencyID").val();

							var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteSidebarOutputResponseItem/SELECCIONAR_ITEM_PAGINATED/true/" + 
								encodeURI('{' + '\"warehouseID\"|\"' + responseItemWarehouseID + '\"' + ',\"currencyID\"|\"' + currencyID + '\"' + '}') + 
								"/false/not_redirect_when_empty/1/1/" + varParameterCantidadItemPoup + "/";
							
							pv2OpenSearch(url_request, "Buscar producto", "onCompleteSidebarOutputResponseItem", onCompleteSidebarOutputResponseItem, "width=900,height=450");
						});


						//Eliminar producto de destino en barra lateral de insumos
						$(document).on("click", "#btnSidebarClearDestinationItem", fnClearSidebarDestinationItemInput);

						//Eliminar producto de destino en barra lateral de resultados
						$(document).on("click", "#btnSidebarClearResponseItem", fnClearSidebarResponseItemOutput);

						$(document).on("click", "#btnCloseSidebarItemInput", function() {
							$mySidebarItemInput.removeClass("prod-sidebar-open");
						});

						$(document).on("click", "#btnCloseSidebarItemOutput", function() {
							$mySidebarItemOutput.removeClass("prod-sidebar-open");
						});

						//Guardar datos ingresados en barra lateral de insumos
						$(document).on("click", "#btnSaveSidebarItemInput", function() {
							var timerNotification 	= 15000;
							var isUpdateValid		= true;

							if(!fnValidateSidebarInputRecord())	return;

							if(isInputTableOnEditionMode)
							{
								$bodyInputTable.find("tr").each(function(index, obj) 
								{
									if (index == selectedItemRowInInputTable) 
									{
										if(!fnUpdateInputTableRow(obj)) return false;
										isInputTableOnEditionMode	= false;
										selectedItemRowInInputTable	= null;
									}
								});
							}
							else
							{	
								if(!fnInitializeInputItemsTable())	return;
							}
							
							fnClearSidebarInputs();
							$mySidebarItemInput.removeClass("prod-sidebar-open");
						});

						//Guardar datos ingresados en barra lateral de resultados
						$(document).on("click", "#btnSaveSidebarItemOutput", function() {

							if(!fnValidateSidebarOutputRecord())	return;

							if(isOutputTableOnEditionMode)
							{
								$bodyOutputTable.find("tr").each(function(index, obj) 
								{	
									if (index == selectedItemRowInOutputTable) {
										if(!fnUpdateOutputTableRow(obj)) return false;
										isOutputTableOnEditionMode 		= false;
										selectedItemRowInOutputTable	= null;
									}
								});
							}
							else
							{
								if(!fnInitializeOutputItemsTable())	return;
							}

							fnClearSidebarOutputs();
        					$mySidebarItemOutput.removeClass("prod-sidebar-open");
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
						$(document).on("click", "#btnEditDetailTransactionItemInput", function() 
						{
							var activeRowsCount = fnIsOnlyOneRowSelectedInTable($bodyInputTable, ".txtCheckedIsActive");
							if(!activeRowsCount) return;

							$bodyInputTable.find("tr").each(function(index, obj) {
								if ($(obj).find(".txtCheckedIsActive").prop("checked")) {
									fnPopulateSidebarInputFields(obj, index);
									isInputTableOnEditionMode 	= true;
									selectedItemRowInInputTable = index;
									$mySidebarItemInput.addClass("prod-sidebar-open");
									$mySidebarItemOutput.removeClass("prod-sidebar-open");
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
									isOutputTableOnEditionMode 		= true;
									selectedItemRowInOutputTable 	= index;
									$mySidebarItemOutput.addClass("prod-sidebar-open");
									$mySidebarItemInput.removeClass("prod-sidebar-open");
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
						var result 				= true;
						var timerNotification 	= 15000;
						var date 				= new Date($("#txtDate").val());

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
						if(!fnValidateDestinationItemsEqualOutputItems())
						{
							fnShowNotification("Los Insumos no coinciden con los Resultados", "error", timerNotification);
							result = false;
						}

						return result;
					}

					function refreschChecked() {
						$("[type='checkbox'], [type='radio']").not('.toggle, .multiselect').uniform();
						afxInitMontos(".pv2");
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
							var value = pv2Num($(this).val());
							totalAmount += value;
						});

						$("#txtTransactionTotalAmount").val(pv2Fmt(totalAmount));
						$("#prodMbarTotal").text(pv2Fmt(totalAmount));
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
						var unitaryCost = pv2Num($("#txtSidebarRequestItemUnitaryCost").val());
						var Quantity	= pv2Num($("#txtSidebarRequestItemQuantity").val());
						var total 		= unitaryCost * Quantity;
						$("#txtSidebarInputTotalCost").val(prodCalcMonto(total));
					}

					function fnValidateSidebarInputRecord() {
						console.info("CALL fnValidateSidebarInputRecord");
						var result 							= true;
						var timerNotification 				= 15000;
						var itemRequestID					= $("#txtSidebarRequestItemID").val();
						var itemRequestDestinationProductID	= $("#txtSidebarDestinationItemID").val();
						var itemRequestQuantity				= pv2Num($("#txtSidebarRequestItemQuantity").val());

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

					function fnIsInputItemAlreadyInOutputList()
					{
						var timerNotification	= 15000;
						var itemID 				= $("#txtSidebarRequestItemID").val();
						var isInputInOutputList	= false;

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(index, obj) {
							var itemOutputID = $(obj).find("#txtItemOutputID").val();
							if(itemID == itemOutputID)
							{
								fnShowNotification("El producto origen ya se encuentra en la lista de productos resultantes", "error", timerNotification);
								isInputInOutputList = true;
								return false;
							}
						});

						return isInputInOutputList;
					}

					function fnAreThereMatchesInInputAndOutputLists()
					{
						var newItemInputID					= $("#txtSidebarRequestItemID").val();
						var newItemOutputID					= $("#txtSidebarDestinationItemID").val();
						var timerNotification 				= 15000;
						var areThereMatches					= false; 
						
						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemProductDestinationID 	= $(obj).find("#txtItemInputProductDestinationID").val();
							var itemInputID					= $(obj).find("#txtItemInputID").val();			
							
							if(newItemInputID == itemProductDestinationID)
							{
								fnShowNotification("El producto origen ya se encuentra registrado como un producto destino", "error", timerNotification);
								areThereMatches = true;
								return false;
							}

							if(newItemOutputID == itemInputID)
							{
								fnShowNotification("El producto destino ya se encuentra registrado como un producto origen", "error", timerNotification);
								areThereMatches = true;
								return false;
							}
						});

						return areThereMatches;
					}

					function fnInitializeInputItemsTable()
					{
						console.info("CALL fnInitializeInputItemsTable");

						var itemRequestID 						= $("#txtSidebarRequestItemID").val();
						var itemRequestCode 					= $("#txtSidebarRequestItemDescription").val().split("|")[0].trim();
						var itemRequestName 					= $("#txtSidebarRequestItemDescription").val().split("|")[1].trim();
						var itemRequestQuantity					= pv2Num($("#txtSidebarRequestItemQuantity").val());
						var itemRequestTotalCost 				= $("#txtSidebarInputTotalCost").val();
						var itemRequestUnitaryCost 				= $("#txtSidebarRequestItemUnitaryCost").val();
						var itemRequestSourceWarehouseID 		= $("#txtSidebarItemSourceWarehouse").val();
						var itemRequestDestinationProductID		= $("#txtSidebarDestinationItemID").val();
						var itemRequestDestinationProductName	= $("#txtSidebarDestinationItemDescription").val();
						var itemRequestWarehouseSourceName		= $("#txtSidebarItemSourceWarehouse option:selected").data("name");
						
						if(fnAreThereMatchesInInputAndOutputLists())
						{
							return false;
						}

						if(fnIsInputItemAlreadyInOutputList())
						{
							return false;
						}
						
						var tmpl = $($("#tmpl_row_detail_item_input").html());
						tmpl.find("#txtItemInputID").val(itemRequestID);
						tmpl.find("#txtItemInputCode").text(itemRequestCode);
						tmpl.find("#txtItemInputName").text(itemRequestName);
						tmpl.find("#txtItemInputQuantity").val(afxFmtMonto(itemRequestQuantity));
						tmpl.find("#txtItemInputTotalCost").val(afxFmtMonto(itemRequestTotalCost));
						tmpl.find("#txtItemInputUnitaryCost").val(afxFmtMonto(itemRequestUnitaryCost));
						tmpl.find("#txtItemInputWarehouseSource").val(itemRequestWarehouseSourceName);
						tmpl.find("#txtItemInputWarehouseSourceID").val(itemRequestSourceWarehouseID);
						tmpl.find("#txtItemInputProductDestination").val(itemRequestDestinationProductName);
						tmpl.find("#txtItemInputProductDestinationID").val(itemRequestDestinationProductID);
						$("#body_tb_transaction_master_detail_item_input").append(tmpl);	
						return true;				
					}

					function onCompleteSidebarDestinationItem(objResponse) {
						console.info("CALL onCompleteSidebarDestinationItem");

						var itemID 			= objResponse[0][1];
						var itemCode 		= objResponse[0][2];
						var itemName 		= objResponse[0][3];

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
						var itemRequestQuantity 				= pv2Num($("#txtSidebarRequestItemQuantity").val());
						var itemRequestTotalCost 				= $("#txtSidebarInputTotalCost").val();
						var itemRequestUnitaryCost 				= $("#txtSidebarRequestItemUnitaryCost").val();
						var itemRequestSourceWarehouseID 		= $("#txtSidebarItemSourceWarehouse").val();
						var itemRequestDestinationProductID 	= $("#txtSidebarDestinationItemID").val();
						var itemRequestDestinationProductName 	= $("#txtSidebarDestinationItemDescription").val();
						var itemRequestWarehouseSourceName 		= $("#txtSidebarItemSourceWarehouse option:selected").data("name");

						if(fnAreThereMatchesInInputAndOutputLists())
						{
							return false;
						}

						if(fnIsInputItemAlreadyInOutputList())
						{
							return false;
						}

						$(obj).find("#txtItemInputID").val(itemRequestID);
						$(obj).find("#txtItemInputCode").text(itemRequestDescription.split("|")[0].trim());
						$(obj).find("#txtItemInputName").text(itemRequestDescription.split("|")[1].trim());
						$(obj).find("#txtItemInputQuantity").val(afxFmtMonto(itemRequestQuantity));
						$(obj).find("#txtItemInputTotalCost").val(afxFmtMonto(itemRequestTotalCost));
						$(obj).find("#txtItemInputUnitaryCost").val(afxFmtMonto(itemRequestUnitaryCost));
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

						var itemID 			= objResponse[0][1];
						var itemCode 		= objResponse[0][2];
						var itemName 		= objResponse[0][3];

						$("#txtSidebarResponseItemID").val(itemID);
						$("#txtSidebarResponseItemDescription").val(itemCode + " | " + itemName);

						fnCalculateSidebarOutputTotal();
					}
					
					function fnCalculateSidebarOutputTotal() {
						fnCalculateResponseItemTotalCost();
						var unitaryCost = pv2Num($("#txtSidebarResponseItemUnitaryCost").val());
						var Quantity	= pv2Num($("#txtSidebarResponseItemQuantity").val());
						var total 		= unitaryCost * Quantity;
						debugger;
						$("#txtSidebarOutputTotalCost").val(prodCalcMonto(total));
					}

					function fnCalculateResponseItemTotalCost() {
						console.info("CALL fnCalculateResponseItemTotalCost");

						var responseItemID = $("#txtSidebarResponseItemID").val();
						var totalInputCost = 0;

						if (!responseItemID) return;

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var destinationItemID = $(obj).find("#txtItemInputProductDestinationID").val();
							var inputTotalCost = pv2Num($(obj).find("#txtItemInputTotalCost").val());

							if (destinationItemID === responseItemID) {
								totalInputCost += inputTotalCost;
							}
						});

						$("#txtSidebarResponseItemUnitaryCost").val(totalInputCost.toFixed(2));
					}

					function fnValidateSidebarOutputRecord() {
						console.info("CALL fnValidateSidebarOutputRecord");
						var result 						= true;
						var timerNotification 			= 15000;

						var itemResponseID 				= $("#txtSidebarResponseItemID").val();
						var itemResponseCode 			= $("#txtSidebarResponseItemDescription").val().split("|")[0].trim();
						var itemResponseName 			= $("#txtSidebarResponseItemDescription").val().split("|")[1].trim();
						var itemResponseQuantity 		= pv2Num($("#txtSidebarResponseItemQuantity").val());
						var itemResponseTotalCost 		= $("#txtSidebarOutputTotalCost").val();
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

					function fnIsOutputItemAlreadyInInputList()
					{
						var timerNotification	= 15000;
						var itemID 				= $("#txtSidebarResponseItemID").val();
						var isOutputInInputList	= false;

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemInputID = $(obj).find("#txtItemInputID").val();
							if(itemID == itemInputID)
							{
								fnShowNotification("El producto destino ya se encuentra en la lista de insumos", "error", timerNotification);
								isOutputInInputList = true;
								return false;
							}
						});

						return isOutputInInputList;
					}

					function fnIsOutputItemAlreadyInOutputList()
					{
						var timerNotification		= 15000;
						var itemID 					= $("#txtSidebarResponseItemID").val();
						var isOutputInOutputList	= false;

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(index, obj) {
							var itemOutputID = $(obj).find("#txtItemOutputID").val();
							if(itemID == itemOutputID)
							{
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
						var itemResponseQuantity 			= pv2Num($("#txtSidebarResponseItemQuantity").val());
						var itemResponseTotalCost 			= $("#txtSidebarOutputTotalCost").val();
						var itemResponseUnitaryCost 		= $("#txtSidebarResponseItemUnitaryCost").val();
						var itemResponseTargetWarehouseID 	= $("#txtSidebarResponseItemTargetWarehouse").val();
						var itemResponseTargetWarehouseName = $("#txtSidebarResponseItemTargetWarehouse option:selected").data("name");

						if(fnIsOutputItemAlreadyInInputList())
						{
							return false;
						}

						if(fnIsOutputItemAlreadyInOutputList())
						{
							return false;
						}

						var tmpl = $($("#tmpl_row_detail_item_output").html());
						tmpl.find("#txtItemOutputID").val(itemResponseID);
						tmpl.find("#txtItemOutputCode").text(itemResponseCode);
						tmpl.find("#txtItemOutputName").text(itemResponseName);
						tmpl.find("#txtItemOutputQuantity").val(afxFmtMonto(itemResponseQuantity));
						tmpl.find("#txtItemOutputTotalCost").val(afxFmtMonto(itemResponseTotalCost));
						tmpl.find("#txtItemOutputUnitaryCost").val(afxFmtMonto(itemResponseUnitaryCost));
						tmpl.find("#txtItemOutputWarehouseTarget").val(itemResponseTargetWarehouseName);
						tmpl.find("#txtItemOutputWarehouseTargetID").val(itemResponseTargetWarehouseID);
						$("#body_tb_transaction_master_detail_item_output").append(tmpl);
						return true;
					}

					function fnCalculateOutputItemTotalAmountInTable() {
						console.info("CALL fnCalculateOutputItemTotalAmountInTable");
						var totalAmount 				= 0;
						var mapItemInputDestinationID 	= new Map();

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemInputID 			= $(obj).find("#txtItemInputID").val();
							var itemInputDestinationID 	= $(obj).find("#txtItemInputProductDestinationID").val();
							var itemInputTotalCost 		= pv2Num($(obj).find("#txtItemInputTotalCost").val());

							if(mapItemInputDestinationID.has(itemInputDestinationID))
							{
								var totalCost 	= mapItemInputDestinationID.get(itemInputDestinationID);
								totalCost 		+= itemInputTotalCost;
								mapItemInputDestinationID.set(itemInputDestinationID, totalCost);
							}
							else
							{
								mapItemInputDestinationID.set(itemInputDestinationID, itemInputTotalCost);
							}
						});

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(index, obj) {
							var itemOutputID 			= $(obj).find("#txtItemOutputID").val();
							var itemOutputQuantity		= pv2Num($(obj).find("#txtItemOutputQuantity").val());

							if(mapItemInputDestinationID.size == 0)
							{
								$(obj).find("#txtItemOutputUnitaryCost").val("");
								$(obj).find("#txtItemOutputTotalCost").val("");
								return;
							}

							if(mapItemInputDestinationID.has(itemOutputID))
							{
								var itemOutputUnitaryCost = mapItemInputDestinationID.get(itemOutputID) / itemOutputQuantity;
								$(obj).find("#txtItemOutputUnitaryCost").val(prodCalcMonto(itemOutputUnitaryCost));
								//El total se calcula con el costo sin redondear para no perder centavos
								$(obj).find("#txtItemOutputTotalCost").val(prodCalcMonto(itemOutputUnitaryCost * itemOutputQuantity));
							}
							else
							{
								$(obj).find("#txtItemOutputUnitaryCost").val("");
								$(obj).find("#txtItemOutputTotalCost").val("");
							}
						});
					}

					function fnClearSidebarOutputs() {
						$("#txtSidebarResponseItemID, #txtSidebarResponseItemDescription, #txtSidebarResponseItemQuantity, #txtSidebarOutputTotalCost").val("");
						fnCalculateOutputItemTotalAmountInTable();
					}

					function fnUpdateOutputTableRow(obj) {
						var itemResponseID 					= $("#txtSidebarResponseItemID").val();
						var itemResponseDescription 		= $("#txtSidebarResponseItemDescription").val();
						var itemResponseQuantity 			= pv2Num($("#txtSidebarResponseItemQuantity").val());
						var itemResponseTotalCost 			= $("#txtSidebarOutputTotalCost").val();
						var itemResponseUnitaryCost 		= $("#txtSidebarResponseItemUnitaryCost").val();
						var itemResponseTargetWarehouseID 	= $("#txtSidebarResponseItemTargetWarehouse").val();
						var itemResponseTargetWarehouseName = $("#txtSidebarResponseItemTargetWarehouse option:selected").data("name");

						if(fnAreThereMatchesInInputAndOutputLists())
						{
							return false;
						}

						if(fnIsInputItemAlreadyInOutputList())
						{
							return false;
						}

						$(obj).find("#txtItemOutputID").val(itemResponseID);
						$(obj).find("#txtItemOutputCode").text(itemResponseDescription.split("|")[0].trim());
						$(obj).find("#txtItemOutputName").text(itemResponseDescription.split("|")[1].trim());
						$(obj).find("#txtItemOutputQuantity").val(afxFmtMonto(itemResponseQuantity));
						$(obj).find("#txtItemOutputTotalCost").val(afxFmtMonto(itemResponseTotalCost));
						$(obj).find("#txtItemOutputUnitaryCost").val(afxFmtMonto(itemResponseUnitaryCost));
						$(obj).find("#txtItemOutputWarehouseTarget").val(itemResponseTargetWarehouseName);
						$(obj).find("#txtItemOutputWarehouseTargetID").val(itemResponseTargetWarehouseID);
						return true;
					}

					function fnValidateDestinationItemsEqualOutputItems() {
						var arrayItemInputDestinationID	= [];
						var arrayItemOutputID 			= [];

						$("#body_tb_transaction_master_detail_item_input").find("tr").each(function(index, obj) {
							var itemInputDestinationID 	= $(obj).find("#txtItemInputProductDestinationID").val();
							if(arrayItemInputDestinationID.indexOf(itemInputDestinationID) == -1)
							{
								arrayItemInputDestinationID.push(itemInputDestinationID);
							}
						});

						$("#body_tb_transaction_master_detail_item_output").find("tr").each(function(index, obj) {
							var itemOutputID 			= $(obj).find("#txtItemOutputID").val();
							if(arrayItemOutputID.indexOf(itemOutputID) == -1)
							{
								arrayItemOutputID.push(itemOutputID);
							}
						});

						if(arrayItemInputDestinationID.length != arrayItemOutputID.length)
						{
							return false;
						}						

						for(var i = 0; i < arrayItemInputDestinationID.length; i++)
						{
							if(arrayItemOutputID.indexOf(arrayItemInputDestinationID[i]) == -1)
							{
								return false;
							}
						}

						return true;
					}
				
				</script>