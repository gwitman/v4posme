<style>
	#tb_detail_amortization {
		table-layout: fixed;
		width: 100%;
	}

	#tb_detail_amortization th,
	#tb_detail_amortization td {
		text-align: center;
		word-wrap: break-word;
	}
</style>
<!-- ./ page heading -->
<script>
	var objTableLine 						= {};
	var objTableLineAmortization 			= {};
	var objTableLineEntity 					= {};
	var site_url 							= "<?php echo base_url(); ?>/";
	var userMobile 							= '<?php echo $useMobile; ?>';
	var objCustomerCreditAmortizations 		= JSON.parse('<?php echo json_encode($objCustomerCreditAmortization); ?>');
	var objListWorkflowStageAmortization 	= JSON.parse('<?php echo json_encode($objListWorkflowStageAmortization); ?>');
	var objCustomerEntityRelated 			= JSON.parse('<?php echo json_encode($objCustomerEntityRelated) ?>');
	var aCounter 							= 0;
	var backgroundCall 						= true;


	$(document).ready(function() {
		$('#txtDocumentDate').datepicker({format:"yyyy-mm-dd"});						 
		$("#txtDocumentDate").datepicker("update");
		
		$('.txtDateApply').datepicker({format:"yyyy-mm-dd"});						 
		$(".txtDateApply").datepicker("update");
		
		
		
		<?php echo getBehavio($company->type, "app_cxc_document", "divScriptReady", ""); ?>
			
		//Regresar a la lista
		$(document).on("click", "#btnBack", function() {
			fnWaitOpen();
		});

		//Evento Agregar el Documento
		$(document).on("click", "#btnAcept", function() {
			$("#form-new-cxc-document").attr("method", "POST");
			$("#form-new-cxc-document").attr("action", "<?php echo base_url(); ?>/app_cxc_document/save/edit");

			if (validateForm()) {
				fnWaitOpen();
				appendEntityHiddenTableDataToForm();
				$("#form-new-cxc-document").submit();
			}

		});

		//Grid Lineas de credito
		objTableLine = $("#tb_detail_credit_line").dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false,
			"aaData": [<?php
						if ($objCustomerCreditLine) {
							$listrow 	= [];
							foreach ($objCustomerCreditLine as $i) {
								$listrow[] 	= "[0," . $i->customerCreditLineID . "," . $i->creditLineID . "," . $i->currencyID . "," . $i->statusID . "," . $i->interestYear . "," . $i->interestPay . "," . $i->totalPay . "," . $i->totalDefeated . ",'" . $i->dateOpen . "'," . $i->periodPay . ",'" . $i->dateLastPay . "'," . $i->term . ",'" . $i->note . "','" . $i->line . "','" . $i->accountNumber . "','" . number_format($i->limitCredit, 2) . "','" . number_format($i->balance, 2) . "','" . $i->statusName . "','" . $i->currencyName . "'," . $i->typeAmortization . ",'" . $i->typeAmortizationLabel . "','" . $i->periodPayLabel . "','" . number_format($i->interestYear, 2) . "','" . number_format($i->term, 2) . "','" . $i->dayExcluded . "' ]";
							}
							echo implode(",", $listrow);
						}
						?>],
			"aoColumnDefs": [{
					"aTargets": [0], //checked,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						if (data == false)
							return '<input type="checkbox"  class="classCheckedDetailLine"  value="0" ></span>';
						else
							return '<input type="checkbox"  class="classCheckedDetailLine" checked="checked" value="0" ></span>';
					}
				},
				{
					"aTargets": [1], //customerCreditLineID
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCustomerCreditLineID[]" />';
					}
				},
				{
					"aTargets": [2], //creditLineID
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditLineID[]" />';
					}
				},
				{
					"aTargets": [3], //currencyID
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditCurrencyID[]" />';
					}
				},
				{
					"aTargets": [4], //statusID
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditStatusID[]" />';
					}
				},
				{
					"aTargets": [5], //InteresYear
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditInterestYear[]" />';
					}
				},
				{
					"aTargets": [6], //InteresPay
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditInterestPay[]" />';
					}
				},
				{
					"aTargets": [7], //TotalPay
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditTotalPay[]" />';
					}
				},
				{
					"aTargets": [8], //TotalDefeated
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditTotalDefeated[]" />';
					}
				},
				{
					"aTargets": [9], //DateOpen
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditDateOpen[]" />';
					}
				},
				{
					"aTargets": [10], //PeriodPay
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditPeriodPay[]" />';
					}
				},
				{
					"aTargets": [11], //DateLastPay
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditDateLastPay[]" />';
					}
				},
				{
					"aTargets": [12], //Term
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditTerm[]" />';
					}
				},
				{
					"aTargets": [13], //Note
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditNote[]" />';
					}
				},
				{
					"aTargets": [14], //Linea
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtLine[]" />' + data;
					}
				},
				{
					"aTargets": [15], //Numero
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtLineNumber[]" />' + data;
					}
				},
				{
					"aTargets": [16], //Limite,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtLineLimit[]" />' + data;
					}
				},
				{
					"aTargets": [17], //Balance
					"mRender": function(data, type, full) {
						return '<input class="form-control txt-numeric" type="text" value="' + data + '" name="txtLineBalance[]" />';
					}
				},
				{
					"aTargets": [18], //Estado,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtLineStatus[]" />' + data;
					}
				},
				{
					"aTargets": [19], //CurrencyName
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCurrencyName[]" />' + data;
					}
				},
				{
					"aTargets": [20], //typeAmortization
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtTypeAmortization[]" />';
					}
				}, {
					"aTargets": [21], //Plan
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						var html_ = '';

						if (data == "CONSTANTE")
							html_ = '<i class="icon20 i-info btnInfoAmortizationConstante"></i>';
						else if (data == "FRANCES")
							html_ = '<i class="icon20 i-info btnInfoAmortizationFrances"></i>';
						else if (data == "ALEMAN")
							html_ = '<i class="icon20 i-info btnInfoAmortizationAleman"></i>';
						else if (data == "AMERICANO")
							html_ = '<i class="icon20 i-info btnInfoAmortizationAmericano"></i>';

						html_ = html_ + '<input type="hidden" value="' + data + '" name="txtTypeAmortizationLabel[]" />' + data;
						return html_;
					}
				}, {
					"aTargets": [22], //Frecuencia
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtPeriodPayLabel[]" />' + data;
					}
				}, {
					"aTargets": [23], //InteresYear
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtInterestYearLael[]" />' + data;
					}
				}, {
					"aTargets": [24], //Term
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtTermLabel[]" />' + data;
					}
				}, {

					"aTargets": [25], //creditLineID
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtDayExcluded[]" />' + data;
					}
				}
			]
		});

		//Grid Amortizacion
		objTableLineAmortization = $("#tb_detail_amortization").dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false,
			"aaData": [<?php
						if ($objCustomerCreditAmortization) {
							$listrow 	= [];
							foreach ($objCustomerCreditAmortization as $i) {
								$listrow[] 	= "[0," . $i->creditAmortizationID . "," . $i->customerCreditDocumentID . "," . $i->balanceStart . ",'" . $i->dateApply . "'," . $i->interest . "," . $i->capital . "," . $i->share . "," . $i->balanceEnd . ",'" . number_format($i->remaining, 2) . "'," . $i->dayDelay . ",'" . $i->note . "'," . $i->statusID . ",'" . $i->isActive . "','" . number_format($i->shareCapital, 2) . "' ]";
							}
							echo implode(",", $listrow);
						}
						?>],
			"aoColumnDefs": [{
					"aTargets": [0], //checked,
					"bVisible": false,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						if (data == false) {
							return '<input type="checkbox"  class="classCheckedAmortization"  value="0" ></span>';
						} else
							return '<input type="checkbox"  class="classCheckedAmortization" checked="checked" value="0" ></span>';						
					}
				},
				{
					"aTargets": [1], //creditAmortizationID
					"bVisible": true,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCustomerCreditAmortizationID[]" />';
					}
				},
				{
					"aTargets": [2], //customerCreditDocumentID
					"bVisible": false,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreditDocumentID[]" />';
					}
				},
				{
					"aTargets": [3], //Balance Inicial
					"bVisible": true,
					"sWidth"  :"120px",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtBalanceStart[]" />' + data;
					}
				},
				{
					"aTargets": [4], //Date Apply
					"bVisible": true,
					"sWidth"  :"200px",
					"mRender" : function(data, type, full) {
						
						
						return '<div id="datepicker" class="input-group date" data-date-format="yyyy-mm-dd">'+
							'<input size="16"  class="form-control txtDateApply" type="text" name="txtDateApply[]"  value="'+data+'" >'+
							'<span class="input-group-addon"><i class="icon16 i-calendar-4"></i></span>'+
						'</div>';
											
					}
				},
				{
					"aTargets": [5], //Interes
					"bVisible": true,
					"sWidth"  :"100px",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtInterest[]" />' + data;
					}
				},
				{
					"aTargets": [6], //Capital
					"bVisible": true,
					"sWidth"  :"100px",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCapital[]" />' + data;
					}
				},
				{
					"aTargets": [7], //share
					"bVisible": true,
					"sWidth"  :"100px",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtShare[]" />' + data;
					}
				},
				{
					"aTargets": [8], //Balance Final
					"bVisible": true,
					"sWidth"  :"120px",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtBalanceEnd[]" />' + data;
					}
				},
				{
					"aTargets": [9], //Remanente
					"bVisible": true,
					"sWidth"  :"100px",
					"mRender": function(data, type, full) {
						return '<input class="form-control txt-numeric" type="text" value="' + data + '" name="txtRemaining[]" />';
					}
				},
				{
					"aTargets": [10], //Dias Atrazados
					"bVisible": true,
					"sWidth"  :"120px",
					"mRender": function(data, type, full) {
						return '<input class="form-control txt-numeric" type="number" value="' + data + '" name="txtDayDelay[]" />';
					}
				},
				{
					"aTargets": [11], //Nota
					"bVisible": true,
					"sWidth"  :"100px",
					"mRender": function(data, type, full) {
						return '<input class="form-control type="text" value="' + data + '" name="txtNote[]" />';
					}
				},
				{
					"aTargets": [12], // statusID
					"bVisible": true,
					"sWidth"  :"200px",
					"mRender": function(data, type, full) {
						return '<select name="txtAmortizationStatusID[]" id="txtAmortizationStatusID[]"  >' + '<option> </option>' + printCustomerAmortization() + '</select>';
						
					}
				},
				{
					"aTargets": [13], //isActive
					"bVisible": false,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtIsActive[]" />' + data;
					}
				},
				{
					"aTargets": [14], //Abono al Capital
					"bVisible": true,
					"sWidth"  :"150px",
					"mRender": function(data, type, full) {
						return '<input class="form-control txt-numeric" type="text" value="' + data + '" name="txtShareCapital[]" />';
					}
				}
			]
		});

		//Grid Entidades
		objTableLineEntity = $("#tb_detail_credit_entity").dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false,
			"aaData": [<?php
						if ($objCustomerEntityRelated) {
							$listrow 	= [];
							foreach ($objCustomerEntityRelated as $i) {
								$listrow[] 	= "[0," . $i->ccEntityRelatedID . "," . $i->customerCreditDocumentID . "," . $i->entityID . "," . $i->type . "," . $i->typeCredit . "," . $i->statusCredit . "," . $i->typeGarantia . "," . $i->typeRecuperation . ",'" . $i->ratioDesembolso . "'," . $i->ratioBalance . ",'" . $i->ratioBalanceExpired . "'," . $i->ratioShare . ",'" . $i->createdOn . "','" . $i->createdBy . "','" . $i->createdIn . "','" . $i->createdAt  . "','" . $i->isActive . "' ]";
							}
							echo implode(",", $listrow);
						}
						?>],
			"aoColumnDefs": 
			[
				{
					"aTargets": [0], //checked,
					"mRender": function(data, type, full) {
						if (data == false)
							return '<input type="checkbox"  class="classCheckedDetailEntity"  value="0" ></span>';
						else
							return '<input type="checkbox"  class="classCheckedDetailEntity" checked="checked" value="0" ></span>';
					}
				},
				{
					"aTargets": [1], //ccEntityRelatedID
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtEntityRelatedID[]" />' + data;
					}
				},
				{
					"aTargets": [2], //customerCreditDocumentID
					"bVisible": true,
					"sClass": "hidden",
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCustomerCreditEntityDocumentID[]" />' + data;
					}
				},
				{
					"aTargets": [3], //entityID
					"bVisible": true,
					"mRender": function(data, type, full) {
						return printEntityFieldStatus(full, 'legalName');
					}
				},
				{
					"aTargets": [4], //type
					"bVisible": true,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return printEntityFieldStatus(full, 'type');	
					}
				},
				{
					"aTargets": [5], //typeCredit
					"bVisible": true,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return printEntityFieldStatus(full, 'typeCredit');
					}
				},
				{
					"aTargets": [6], //statusCredit
					"bVisible": false,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return printEntityFieldStatus(full, 'statusCredit');
					}
				},
				{
					"aTargets": [7], //typeGarantia
					"bVisible": false,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return printEntityFieldStatus(full, 'typeGarantia');
					}
				},
				{
					"aTargets": [8], //typeRecuperation
					"bVisible": false,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return printEntityFieldStatus(full, 'typeRecuperation');
					}
				},
				{
					"aTargets": [9], //ratioDesembolso
					"bVisible": false,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtRatioDesembolso[]" />' + data;
					}
				},
				{
					"aTargets": [10], //ratioBalance
					"bVisible": false,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtRatioBalance[]" />' + data;
					}
				},
				{
					"aTargets": [11], //ratioBalanceExpired
					"bVisible": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtRatioBalanceExpired[]" />' + data;
					}
				},
				{
					"aTargets": [12], //ratioShare
					"bVisible": false,
					"bSearchable": false,
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtRatioShare[]" />' + data;
					}
				},
				{
					"aTargets": [13], //createdOn
					"bVisible": false,
					"bSearchable": false,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreatedOn[]" />' + data;
					}
				},
				{
					"aTargets": [14], //createdIn
					"bVisible": false,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreatedIn[]" />' + data;
					}
				},
				{
					"aTargets": [15], //createdAt
					"bVisible": false,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtCreatedAt[]" />' + data;
					}
				},
				{
					"aTargets": [16], //isActive,
					"bVisible": false,
					"sClass": "hidden",
					"mRender": function(data, type, full) {
						return '<input type="hidden" value="' + data + '" name="txtIsEntityActive[]" />' + data;
					}
				}
			]
		});


		//Evento para manejar las columnas visibles de la tabla de entidades
		$('a.toggle-vis').on('click', function(e) {
			e.preventDefault();
			var iCol = parseInt($(this).attr("data-column"));
			var bVis = objTableLineEntity.fnSettings().aoColumns[iCol].bVisible;
			objTableLineEntity.fnSetColumnVis(iCol, bVis ? false : true);
			var bVis = $(this).parent().hasClass('active');
			bVis = bVis ? ($(this).parent().removeClass("active")) : ($(this).parent().addClass("active"));
		});

		//Agregar nueva entidad en vista documento
		$(document).on("click", "#btnNewEntity", function() {
			console.info("call click_btnNewEntity");
			window.open(site_url + "app_cxc_document/add_document_entity", "MsgWindow", "width=700,height=600");
			window.parentNewLine = parentNewLine;
		});

		//Borrar linea de entidad en vista documento
		$(document).on("click","#btnDeleteLine",function(){
			console.info("call click_btnDeleteLine");
			var listRow = objTableLineEntity.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true)
				{
					objTableLineEntity.fnDeleteRow( j,null,true );
					j--;
				}
				i++;
				j++;
			}
			
		});

		//Seleccionar checkbox de linea en vista documento
		$(document).on("click",".classCheckedDetailEntity",function(){
			var objrow_ = $(this).parent().parent().parent().parent()[0];
			var objind_ = objTableLineEntity.fnGetPosition(objrow_);
			var objdat_ = objTableLineEntity.fnGetData(objind_);								
			objTableLineEntity.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});

		//Editar registro de entidad en documento
		$(document).on("click","#btnEditEntity",function(){
			console.info("call click_btnEditEntity");
			
			
			var listRow 				= objTableLineEntity.fnGetData();							
			var ccEntityRelatedID 	= 0;
			var length 					= listRow.length;
			var i 						= 0;
			var j 						= 0;
			while (i< length ){
				if(listRow[i][0] == true){
					ccEntityRelatedID = listRow[i][1];
				}
				i++;
			}
			
			if(ccEntityRelatedID == 0)
			return;
			
			window.open(site_url+"app_cxc_document/edit_document_entity/ccEntityRelatedID/"+ccEntityRelatedID,"MsgWindow","width=650,height=500");
			window.parentEditLineEntity = parentEditLineEntity;
			
		});	

		refreschChecked();
	});



	function validateForm() {
		var result = true;
		var timerNotification = 15000;

		if ($("#txtCreditDocumentAmount").val() == "") {
			fnShowNotification("Escribir Saldo", "error", timerNotification);
			result = false;
		}

		if ($("#txtBalanceDol").val() == "") {
			fnShowNotification("Ingresar Balance", "error", timerNotification);
			result = false;
		}

		<?php echo getBehavio($company->type, "app_cxc_document", "divScriptValideFunction", ""); ?>
		return result;

	}

	function refreschChecked() {
		//$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		//$('.txt-numeric').mask('000,000.00', {
		//	reverse: true
		//});
		
		$("[type='checkbox'], [type='radio'], [type='file']").not('.toggle').uniform();
		$('.txt-numeric').mask('000,000.00', {
			reverse: true
		});
		
		$('.txtDateApply').datepicker({format:"yyyy-mm-dd"});						 
		$(".txtDateApply").datepicker("update");
		
	}

	function parentEditLine(data) {
		console.info("call parentEditLine");
		console.info(data);

		for (var i = 0; i < objTableLine.fnGetData().length; i++) {
			if (objTableLine.fnGetData()[i][1] == data.txtCustomerCreditLineID) {
				objTableLine.fnUpdate(data.txtCreditLineID, i, 2);
				objTableLine.fnUpdate(data.txtCurrencyID, i, 3);
				objTableLine.fnUpdate(data.txtStatusID, i, 4);
				objTableLine.fnUpdate(data.txtInteresYear, i, 5);
				objTableLine.fnUpdate(data.txtPeriodPay, i, 10);
				objTableLine.fnUpdate(data.txtTerm, i, 12);
				objTableLine.fnUpdate(data.txtNote, i, 13);
				objTableLine.fnUpdate(data.txtCreditLineIDDesc, i, 14);
				objTableLine.fnUpdate(data.txtLimitCredit, i, 16);
				objTableLine.fnUpdate(data.txtStatusIDDesc, i, 18);
				objTableLine.fnUpdate(data.txtCurrencyIDDesc, i, 19);
				objTableLine.fnUpdate(data.txtTypeAmortization, i, 20);
				objTableLine.fnUpdate(data.txtDayExcluded, i, 25);
			}
		}
		refreschChecked();
	}

	function parentEditLineAmortization(data) {
		console.info("call parentEditLineAmortization");
		console.info(data);

		for (var i = 0; i < objTableLineAmortization.fnGetData().length; i++) {
			if (objTableLineAmortization.fnGetData()[i][1] == data.txtCustomerCreditAmortizationID) {
				objTableLineAmortization.fnUpdate(data.txtCustomerCreditDocumentID, i, 2);
				objTableLineAmortization.fnUpdate(data.txtBalanceStart, i, 3);
				objTableLineAmortization.fnUpdate(data.txtDateApply, i, 4);
				objTableLineAmortization.fnUpdate(data.txtInterest, i, 5);
				objTableLineAmortization.fnUpdate(data.txtCapital, i, 6);
				objTableLineAmortization.fnUpdate(data.txtShare, i, 7);
				objTableLineAmortization.fnUpdate(data.txtBalanceEnd, i, 8);
				objTableLineAmortization.fnUpdate(data.txtRemaining, i, 9);
				objTableLineAmortization.fnUpdate(data.txtDayDelay, i, 10);
				objTableLineAmortization.fnUpdate(data.txtNote, i, 11);
				objTableLineAmortization.fnUpdate(data.txtStatusID, i, 12);
				objTableLineAmortization.fnUpdate(data.txtShareCapital, i, 14);
			}
		}

		refreschChecked();
	}

	function parentEditLineEntity(data){
		console.info("call parentEditLineEntity");
		console.info(data);

		for(var i = 0 ; i < objTableLineEntity.fnGetData().length ; i++)
		{
			if(objTableLineEntity.fnGetData()[i][1] == data.txtEntityRelatedID)
			{
				objTableLineEntity.fnUpdate( data.txtDocumentEntityID, i, 3 );
				objTableLineEntity.fnUpdate( data.txtDocumentEntityType, i, 4 );
				objTableLineEntity.fnUpdate( data.txtDocumentEntityTypeCredit, i, 5 );
				objTableLineEntity.fnUpdate( data.txtDocumentEntityStatusCredit, i, 6 );
				objTableLineEntity.fnUpdate( data.txtDocumentEntityTypeGarantia, i, 7 );
				objTableLineEntity.fnUpdate( data.txtDocumentEntityTypeRecuperation, i, 8 );
				objTableLineEntity.fnUpdate( data.txtRatioDesembolso, i, 9 );
				objTableLineEntity.fnUpdate( data.txtRatioBalance, i, 10 );
				objTableLineEntity.fnUpdate( data.txtRatioBalanceExpired, i, 11 );
				objTableLineEntity.fnUpdate( data.txtRatioShare, i, 12 );
			}
		}
		refreschChecked();
	}

	function parentNewLine(data){
		console.info("call parentNewLine");
		console.info(data);
				
		objTableLineEntity.fnAddData([
			false,											/*classCheckedDetailEntity*/
			0,												/*EntityRelatedID*/
			0, 												/*CustomerCreditEntityDocumentID*/
			data.txtDocumentEntityID, 						/*DocumentEntityID*/
			data.txtDocumentEntityType, 					/*DocumentEntityType*/
			data.txtDocumentEntityTypeCredit, 				/*DocumentEntityTypeCredit*/
			data.txtDocumentEntityStatusCredit,				/*DocumentEntityStatusCredit*/
			data.txtDocumentEntityTypeGarantia,				/*DocumentEntityTypeGarantia*/
			data.txtDocumentEntityTypeRecuperation,			/*DocumentEntityTypeRecuperation*/
			data.txtRatioDesembolso, 						/*RatioDesembolso*/
			data.txtRatioBalance, 							/*RatioBalance*/
			data.txtRatioBalanceExpired, 					/*RatioBalanceExpired*/
			data.txtRatioShare,									/*RatioShare*/
			'', 								/*CreatedOn*/
			'', 								/*CreatedIn*/
			'', 								/*CreatedAt*/
			1,									/*IsEntityActive*/
		]);
		refreschChecked();
	}

	//Esta funcion imprime un select por cada registro de amortizacion
	function printCustomerAmortization() {

		if (backgroundCall) {
			backgroundCall = false;
			return "";
		} else {
			var arrayAmortizations = [];
			var arrayStatus = [];
			var options = '';

			for (var i = 0; i < objCustomerCreditAmortizations.length; i++) {
				arrayAmortizations.push(
					[
						objCustomerCreditAmortizations[i].creditAmortizationID,
						objCustomerCreditAmortizations[i].statusID
					]
				);
			}

			for (var i = 0; i < objListWorkflowStageAmortization.length; i++) {
				arrayStatus.push(
					[
						objListWorkflowStageAmortization[i].workflowStageID,
						objListWorkflowStageAmortization[i].name
					]
				);
			}

			arrayStatus.forEach(
				function(status) {
					if (aCounter < arrayAmortizations.length) {
						if (status[0] == arrayAmortizations[aCounter][1]) {
							options += "<option value=" + status[0] + " selected>" + status[1] + "</option>";
						} else {
							options += "<option value=" + status[0] + " >" + status[1] + "</option>";
						}
					}
				}
			);

			backgroundCall = true;
			aCounter++;
			return options;
		}
	}

	//Esta funcion imprime el nombre de los estados de los registros de entidad
	function printEntityFieldStatus(rowData, fieldName) 
	{
		var listCustomer				= '<?php echo json_encode($objCatalogLegalName) ?>';		
		objListFieldName				= JSON.parse(listCustomer.replace(/[\r\n]/g, "").replace(/[\n\r\t]/g, " "));
		objListFieldStatus 				= JSON.parse('<?php echo json_encode($objCatalogEntityType) ?>')
		objListFieldTypeCredit 			= JSON.parse('<?php echo json_encode($objCatalogEntityTypeCredit) ?>');
		objListFieldStatusCredit 		= JSON.parse('<?php echo json_encode($objCatalogEntityStatusCredit) ?>');
		objListFieldTypeGarantia		= JSON.parse('<?php echo json_encode($objCatalogEntityTypeGarantia)?>');
		objListFieldTypeRecuperation	= JSON.parse('<?php echo json_encode($objCatalogEntityTypeRecuperation)?>');

		var arrayListFieldNames				= objListFieldName.map(item => [item.entityID, item.legalName]);
		var arrayListFieldStatus 			= objListFieldStatus.map(item => [item.catalogItemID, item.name]);
		var arrayListFieldTypeCredit 		= objListFieldTypeCredit.map(item => [item.catalogItemID, item.name])
		var arrayListFieldStatusCredit 		= objListFieldStatusCredit.map(item => [item.catalogItemID, item.name])
		var arrayListFieldTypeGarantia		= objListFieldTypeGarantia.map(item => [item.catalogItemID, item.name])
		var arrayListFieldTypeRecuperation 	= objListFieldTypeRecuperation.map(item => [item.catalogItemID, item.name])

		var input = '';

		if(fieldName == 'legalName')
		{	
			const fieldIndex = arrayListFieldNames.findIndex(
				(field) => field[0] == rowData[3]
			);

			if(fieldIndex != -1)
			{
				input += '<input type="hidden" value=' + arrayListFieldNames[fieldIndex][0] + ' name="txtDocumentEntityID[]"/>' + arrayListFieldNames[fieldIndex][1];
			}
			
			return input;
		}


		if(fieldName == 'type')
		{	
			const fieldIndex = arrayListFieldStatus.findIndex(
				(field) => field[0] == rowData[4]
			);

			if(fieldIndex != -1)
			{
				input += '<input type="hidden" value=' + arrayListFieldStatus[fieldIndex][0] + ' name="txtDocumentEntityType[]"/>' + arrayListFieldStatus[fieldIndex][1];
			}
			
			return input;
		}
		
		if(fieldName == 'typeCredit')
		{
			const fieldIndex = arrayListFieldTypeCredit.findIndex(
				(field) => field[0] == rowData[5]
			);

			if(fieldIndex != -1)
			{
				input += '<input type="hidden" value=' + arrayListFieldTypeCredit[fieldIndex][0] + ' name="txtDocumentEntityTypeCredit[]"/>' + arrayListFieldTypeCredit[fieldIndex][1];
			}
			
			return input;
		}

		if(fieldName == 'statusCredit')
		{
			const fieldIndex = arrayListFieldStatusCredit.findIndex(
				(field) => field[0] == rowData[6]
			);

			if(fieldIndex != -1)
			{
				input += '<input type="hidden" value=' + arrayListFieldStatusCredit[fieldIndex][0] + ' name="txtDocumentEntityStatusCredit[]"/>' + arrayListFieldStatusCredit[fieldIndex][1];
			}
			
			return input;
		}
		
		if(fieldName == 'typeGarantia')
		{			
			const fieldIndex = arrayListFieldTypeGarantia.findIndex(
				(field) => field[0] == rowData[7]
			);

			if(fieldIndex != -1)
			{
				input += '<input type="hidden" value=' + arrayListFieldTypeGarantia[fieldIndex][0] + ' name="txtDocumentEntityTypeGarantia[]"/>' + arrayListFieldTypeGarantia[fieldIndex][1];
			}
			
			return input;
		}
		
		if(fieldName == 'typeRecuperation')
		{
			const fieldIndex = arrayListFieldTypeRecuperation.findIndex(
				(field) => field[0] == rowData[8]
			);

			if(fieldIndex != -1)
			{
				input += '<input type="hidden" value=' + arrayListFieldTypeRecuperation[fieldIndex][0] + ' name="txtDocumentEntityTypeRecuperation[]"/>' + arrayListFieldTypeRecuperation[fieldIndex][1];
			}
			
			return input;
		}

		return input;
	}

	//Esta funcion se encarga de agregar los datos de la tabla de entidades al formulario cuando la columna no se encuentra visible.
	function appendEntityHiddenTableDataToForm() 
	{
		var form = $('#form-new-cxc-document');
		var tableData = objTableLineEntity.fnGetData();
		var columnDefs = objTableLineEntity.fnSettings().aoColumns;

		//Mapeo Manual de los nombres de los inputs
		var columnInputMap = {
			3: 'txtDocumentEntityID[]',
			4: 'txtDocumentEntityType[]',
			5: 'txtDocumentEntityTypeCredit[]',
			6: 'txtDocumentEntityStatusCredit[]',
			7: 'txtDocumentEntityTypeGarantia[]',
			8: 'txtDocumentEntityTypeRecuperation[]',
			9: 'txtRatioDesembolso[]',
			10: 'txtRatioBalance[]',
			11: 'txtRatioBalanceExpired[]',
			12: 'txtRatioShare[]',
			13: 'txtCreatedOn[]',
			14: 'txtCreatedIn[]',
			15: 'txtCreatedAt[]',
			16: 'txtIsActive[]'
		};

		for (var i = 0; i < tableData.length; i++) {

			for (var colIndex in columnInputMap) {
				var inputName = columnInputMap[colIndex];
				colIndex = parseInt(colIndex, 10);

				if (!columnDefs[colIndex].bVisible) {
					form.append('<input type="hidden" name="' + inputName + '" value="' + tableData[i][colIndex] + '">');
				}
			}
		}
	}


</script>