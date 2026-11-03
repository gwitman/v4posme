				<!-- ./ page heading -->
				<script>
					var objTableDetailJournal = {};
					$(document).ready(function(){
						 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
						 $('#txtDate').val(moment().format("YYYY-MM-DD"));
						 $('#txtTotalCredit').mask('000,000,000.00', {reverse: true});
						 $('#txtTotalDebit').mask('000,000,000.00', {reverse: true});


						$(document).on("click","#btnOpenTemplated",function(e){
							e.preventDefault();
							var url_request 				= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentAccountID; ?>/onCompleteSelectTemplate/SELECCIONAR_TEMPLATED/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteSelectTemplate 	= onCompleteSelectTemplate;
						});

						function onCompleteSelectTemplate(objResponse){
							console.info("CALL onCompleteSelectTemplate");
							var journalEntryIDTemplated 	= objResponse[0][0];
							$( "#txtTemplatedNumber").val(journalEntryIDTemplated);
							$( "#form-new-account-journal" ).attr("method","POST");
							$( "#form-new-account-journal" ).attr("action","<?php echo base_url(); ?>/app_accounting_journal/save/new");
							$( "#form-new-account-journal" ).submit();
						}




						objTableDetailJournal = $("#tb_journal_entry_detail").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false,
							"aoColumnDefs": [
										{
											"aTargets"		: [ 0 ],//checked
											"mRender"		: function ( data, type, full ) {
												if (data == false)
												return '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';
												else
												return '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
											}
										},
										{
											"aTargets"		: [ 1 ],//journalEntryDetailID
											"bVisible"  	: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtJournalEntryDetailID[]" />';
											}
										},
										{
											"aTargets"		: [ 2 ],//accountID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtAccountID[]" />';
											}
										},
										{
											"aTargets"		: [ 3 ],//classID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtClassID[]" />';
											}
										},
										{
											"aTargets"		: [ 6 ],//Debito
											"mRender"		: function ( data, type, full ) {
												return '<input type="text" class="col-lg-12 txtDebit txt-numeric" value="'+data+'" name="txtDebit[]" autocomplete="off" inputmode="decimal" />';
											}
										},
										{
											"aTargets"		: [ 7 ],//Credito
											"mRender"		: function ( data, type, full ) {
												return '<input type="text" class="col-lg-12 txtCredit txt-numeric" value="'+data+'" name="txtCredit[]" autocomplete="off" inputmode="decimal" />';
											}
										}
							]
						});


						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(e){
								e.preventDefault();
								$( "#form-new-account-journal" ).attr("method","POST");
								$( "#form-new-account-journal" ).attr("action","<?php echo base_url(); ?>/app_accounting_journal/save/new");

								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-account-journal" ).submit();
								}

						});
						$(document).on("click","#btnNewDetailJournal",function(e){
							e.preventDefault();
							var url_request 				= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentAccountID; ?>/onCompleteNewAccount/SELECCIONAR_CUENTA/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteNewAccount 	= onCompleteNewAccount;
						});
						$(document).on("click","#btnDeleteDetailJournal",function(e){
									e.preventDefault();
									var listRow = objTableDetailJournal.fnGetData();
									var length 	= listRow.length;
									var i 		= 0;
									var j 		= 0;
									while (i< length ){
										if(listRow[i][0] == true){
										objTableDetailJournal.fnDeleteRow( j,null,true );
										j--;
										}
										i++;
										j++;
									}

								handler_changeDebit();
								handler_changeCredit();
						});
						//Recalcular totales al cambiar o escribir un monto
						$(document).on("change keyup","input.txtDebit",function(){
								handler_changeDebit();
						});
						$(document).on("change keyup","input.txtCredit",function(){
								handler_changeCredit();
						});

						//Rediseno: etiquetas de celdas (tarjetas en telefono), contador de lineas, estado vacio y barra del telefono.
						//initDetail llama pv2BindMobileBar() una sola vez
						if(window.PosmeInv){
							PosmeInv.initDetail({
								table				: "#tb_journal_entry_detail",
								totalSelector		: null,
								countSelector		: ".pv2-count",
								emptySelector		: ".pv2-empty",
								mbarTotalSelector	: "#lblJournalLines",
								unitSingular		: "línea",
								unitPlural			: "líneas"
							});
						}
						else if(typeof pv2BindMobileBar == "function"){
							pv2BindMobileBar();
						}
						handler_changeDebit();
						handler_changeCredit();
					});
					//Seleccionar Checke
					$(document).on("click",".classCheckedDetail",function(){
						//closest("tr") funciona con o sin el envoltorio de uniform (div.checker > span)
						var objrow_ = $(this).closest("tr")[0];
						var objind_ = objTableDetailJournal.fnGetPosition(objrow_);
						var objdat_ = objTableDetailJournal.fnGetData(objind_);
						objTableDetailJournal.fnUpdate( !objdat_[0], objind_, 0 );
						refreschChecked();
					});
					//Convierte un monto con formato (1,500.00) a numero puro redondeado a 2 decimales. Nunca devuelve NaN
					function fnJournalToNumber(value){
						var numero = (typeof pv2Num == "function") ? pv2Num(value) : parseFloat(String(value === null || value === undefined ? "" : value).replace(/,/g,""));
						if(isNaN(numero)) numero = 0;
						return Math.round(numero * 100) / 100;
					}
					//Formato visual 1,000.00
					function fnJournalFormat(value){
						return (typeof pv2Fmt == "function") ? pv2Fmt(value) : fnFormatNumber(value,2);
					}
					//Suma los debitos y creditos de las lineas del detalle (numeros puros, 2 decimales)
					function fnJournalTotals(){
						var totalDebit 	= 0;
						var totalCredit = 0;
						$("input.txtDebit").each(function(i,obj){
							totalDebit = totalDebit + fnJournalToNumber($(obj).val());
						});
						$("input.txtCredit").each(function(i,obj){
							totalCredit = totalCredit + fnJournalToNumber($(obj).val());
						});
						return {
							debit 	: Math.round(totalDebit * 100) / 100,
							credit 	: Math.round(totalCredit * 100) / 100
						};
					}
					//Indicador visual Cuadrado / Descuadrado y montos de la barra del telefono
					function fnJournalRefreshBalance(){
						var totals 		= fnJournalTotals();
						var lines 		= $("input.txtDebit").length;
						var diff 		= Math.round((totals.debit - totals.credit) * 100) / 100;
						var $kpi 		= $("#divJournalBalance");
						var $icon 		= $kpi.find(".pv2-kpi-icon i");
						var isBalanced 	= (diff == 0 && totals.debit > 0);

						$("#lblMbarDebit").text(fnJournalFormat(totals.debit));
						$("#lblMbarCredit").text(fnJournalFormat(totals.credit));
						$("#lblMbarDebit, #lblMbarCredit").toggleClass("is-bad", lines > 0 && !isBalanced).toggleClass("is-ok", isBalanced);

						$kpi.removeClass("k-ok is-negative");
						if(lines == 0){
							$("#lblJournalBalance").text("Sin movimientos");
							$("#lblJournalDiff").text("Agrega las cuentas del comprobante");
							$icon.attr("class","icon16 i-checkmark-4");
						}
						else if(totals.debit == 0 && totals.credit == 0){
							$("#lblJournalBalance").text("Sin montos");
							$("#lblJournalDiff").text("Escribe los débitos y créditos");
							$icon.attr("class","icon16 i-warning");
						}
						else if(isBalanced){
							$kpi.addClass("k-ok");
							$("#lblJournalBalance").text("Cuadrado");
							$("#lblJournalDiff").text("Débito y crédito son iguales");
							$icon.attr("class","icon16 i-checkmark-4");
						}
						else{
							$kpi.addClass("is-negative");
							$("#lblJournalBalance").text("Descuadrado");
							$("#lblJournalDiff").text("Diferencia: " + fnJournalFormat(Math.abs(diff)));
							$icon.attr("class","icon16 i-warning");
						}
					}
					function handler_changeDebit(){
							var totalDebit = fnJournalTotals().debit;
							$("#txtTotalDebit").val(fnJournalFormat(totalDebit));
							fnJournalRefreshBalance();
					}
					function handler_changeCredit(){
							var totalCredit = fnJournalTotals().credit;
							$("#txtTotalCredit").val(fnJournalFormat(totalCredit));
							fnJournalRefreshBalance();
					}
					function onCompleteNewAccount(objResponse){
						console.info("CALL onCompleteNewAccount");
						var objRow 						= {};
						objRow.checked 					= false;
						objRow.journalEntryDetailID 	= 0;
						objRow.accountID				= objResponse[0][1];
						objRow.classID					= 0;
						objRow.accountName				= objResponse[0][2] + " " + objResponse[0][3];
						objRow.className				= "";
						objRow.debit 					= fnFormatNumber(0,2);
						objRow.credit 					= fnFormatNumber(0,2);

						//Berificar que el Item ya esta agregado
						if(jLinq.from(objTableDetailJournal.fnGetData()).where(function(obj){ return obj[2] == objRow.accountID;}).select().length > 0 ){
							fnShowNotification("El Item ya esta agregado","error");
							return;
						}

						objTableDetailJournal.fnAddData([objRow.checked,objRow.journalEntryDetailID,objRow.accountID,objRow.classID,objRow.accountName,objRow.className,objRow.debit,objRow.credit]);
						refreschChecked();
						fnJournalRefreshBalance();

					}
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;

						//Validar Fecha
						if($("#txtDate").val() == ""){
							fnShowNotification("Establecer Fecha al Documento","error",timerNotification);
							result = false;
						}
						//Validar Beneficiario
						if($("#txtEntryName").val()==""){
							fnShowNotification("Establecer Beneficiario al Documento","error",timerNotification);
							result = false;
						}
						//Validar Estado
						if($("#txtStatusID").val() == ""){
							fnShowNotification("Establecer Estado al Documento","error",timerNotification);
							result = false;
						}
						//Validar Tipo
						if($("#txtJournalType").val() == ""){
							fnShowNotification("Establecer Tipo al Documento","error",timerNotification);
							result = false;
						}
						//Validar Moneda
						if($("#txtCurrencyID").val() == ""){
							fnShowNotification("Establecer Moneda al Documento","error",timerNotification);
							result = false;
						}

						//Validar Detalle
						//Los totales se comparan como NUMEROS (sin comas, 2 decimales) calculados desde las lineas;
						//el servidor no valida el cuadre, por eso esta validacion es obligatoria.
						///////////////////////////////////////////////
						var totals = fnJournalTotals();
						//Validar Creditos ("", "0" y "0.00" cuentan como cero)
						if(totals.credit <= 0){
							fnShowNotification("Establecer Los creditos al Documento","error",timerNotification);
							result = false;
						}
						//Validar Debitos
						if(totals.debit <= 0){
							fnShowNotification("Establecer Los debitos al Documento","error",timerNotification);
							result = false;
						}
						//Validar Cuentas del Comprobantes
						if(objTableDetailJournal.fnGetData().length == 0){
							fnShowNotification("Establecer Cuentas a los Movimientos del Comprobante","error",timerNotification);
							result = false;
						};

						//Validar Comprobante
						if(	totals.debit != totals.credit ){
							fnShowNotification("El documento no esta cuadrado","error",timerNotification);
							result = false;
						}

						return result;
					}
					function refreschChecked(){
						//Los combos ya convertidos a nativos en el telefono (select.form-control) no se envuelven con uniform
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect, select.form-control').uniform();
						$('.txtDebit').mask('000,000,000.00', {reverse: true});
						$('.txtCredit').mask('000,000,000.00', {reverse: true});

						$('#txtTotalCredit').mask('000,000,000.00', {reverse: true});
						$('#txtTotalDebit').mask('000,000,000.00', {reverse: true});
					}
					//Telefono: combos nativos (sin select2) y calendario nativo en la fecha (valor YYYY-MM-DD). En PC no cambia nada
					$(document).ready(function(){
						if(window.PosmeInv){
							PosmeInv.mobileSelects();
							PosmeInv.mobileDates("#txtDate");
						}
					});
				</script>
