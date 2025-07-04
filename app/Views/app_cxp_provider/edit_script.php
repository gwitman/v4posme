				<!-- ./ page heading -->
				<script>					
					var objTableEmail = {};
					var objTablePhone = {};
					var objTableLine 	= {};
					var site_url 	  = "<?php echo base_url(); ?>";
					
					$(document).ready(function(){	
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						//Eliminar el Documento
						$(document).on("click","#btnDelete",function(){
							fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
								fnWaitOpen();
								$.ajax({									
									cache       : false,
									dataType    : 'json',
									type        : 'POST',
									url  		: "<?php echo base_url(); ?>/app_cxp_provider/delete",
									data 		: {companyID : <?php echo $objProvider->companyID;?>, branchID : <?php echo $objProvider->branchID;?> , entityID : <?php echo $objProvider->entityID;?>  },
									success:function(data){
										console.info("complete delete success");
										fnWaitClose();
										if(data.error){
											fnShowNotification(data.message,"error");
										}
										else{
											window.location = "<?php echo base_url(); ?>/app_cxp_provider/index";
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
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-cxp-provider" ).attr("method","POST");
								$( "#form-new-cxp-provider" ).attr("action","<?php echo base_url(); ?>/app_cxp_provider/save/edit");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-cxp-provider" ).submit();
								}
								
						});
						$(document).on("click","#btnClickArchivo",function(){
							window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objProvider->entityID; ?>","blanck");
						});
						//Grid de Email
						objTableEmail = $("#tb_detail_email").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false,
							"aaData": [		
								<?php 									
									if($objEntityListEmail){
										$listrow 	= [];
										foreach($objEntityListEmail as $i)
										{
										$listrow[] 	= "[0,".$i->entityEmailID.",'".$i->email."',".$i->isPrimary."]";
										}
										echo implode(",",$listrow);
									}
								?>
							],
							"aoColumnDefs": [ 
										{
											"aTargets"	: [ 0 ],//checked
											"mRender"	: function ( data, type, full ) {
												if (data == false)
												return '<input type="checkbox"  class="classCheckedDetailEmail"  value="0" ></span>';
												else
												return '<input type="checkbox"  class="classCheckedDetailEmail" checked="checked" value="0" ></span>';
											}
										},
										{
											"aTargets"		: [ 1 ],//entityEmailID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtEntityEmailID[]" />';
											}
										},
										{
											"aTargets"		: [ 2 ],//entityEmail											
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtEntityEmail[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 3 ],//entityEmailIsPrimary
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+(data == true ? "1" : "0" )+'" name="txtEmailIsPrimary[]" />'+(data == true ? "SI" : "NO");
											}
										}
							]							
						});
						//Grid de Telefonos
						objTablePhone = $("#tb_detail_phone").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false,
							"aaData": [		
								<?php 									
									if($objEntityListPhone){
										$listrow 	= [];
										foreach($objEntityListPhone as $i)
										{
										$listrow[] 	= "[0,".$i->entityPhoneID.",".$i->typeID.",'".$i->typeIDDescription."','".$i->number."',".$i->isPrimary."]";
										}
										echo implode(",",$listrow);
									}
								?>
							],
							"aoColumnDefs": [ 
										{
											"aTargets"	: [ 0 ],//checked
											"mRender"	: function ( data, type, full ) {
												if (data == false)
												return '<input type="checkbox"  class="classCheckedDetailPhone"  value="0" ></span>';
												else
												return '<input type="checkbox"  class="classCheckedDetailPhone" checked="checked" value="0" ></span>';
											}
										},
										{
											"aTargets"		: [ 1 ],//entityPhoneID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtEntityPhoneID[]" />';
											}
										},
										{
											"aTargets"		: [ 2 ],//entityPhoneTypeID	
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtEntityPhoneTypeID[]" />';
											}
										},
										{
											"aTargets"		: [ 3 ],//entityPhoneTypeDescripcion											
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtEntityPhoneTypeDescription[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 4 ],//entityPhoneNumber											
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtEntityPhoneNumber[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 5 ],//entityPhoneIsPrimary											
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+(data == true ? "1" : "0" )+'" name="txtEntityPhoneIsPrimary[]" />'+ (data == true ? "SI" : "NO");
											}
										}
							]							
						});
						//Grid lineas de credito
						objTableLine = $("#tb_detail_credit_line").dataTable({
							"bPaginate"		: false,
							"bFilter"		: false,
							"bSort"			: false,
							"bInfo"			: false,
							"bAutoWidth"	: false,
							"aaData"		: [<?php 									
									if($objCustomerCreditLine){
										$listrow 	= [];
										foreach($objCustomerCreditLine as $i)
										{
										$listrow[] 	= "[0,".$i->customerCreditLineID.",".$i->creditLineID.",".$i->currencyID.",".$i->statusID.",".$i->interestYear.",".$i->interestPay.",".$i->totalPay.",".$i->totalDefeated.",'".$i->dateOpen."',".$i->periodPay.",'".$i->dateLastPay."',".$i->term.",'".$i->note."','".$i->line."','".$i->accountNumber."','".number_format($i->limitCredit,2)."','".number_format($i->balance,2)."','".$i->statusName."','".$i->currencyName."',".$i->typeAmortization.",'".$i->typeAmortizationLabel."','".$i->periodPayLabel."','".number_format($i->interestYear,2)."','".number_format($i->term,2)."','".$i->dayExcluded."' ]";
										}
										echo implode(",",$listrow);
									}
								?>],						
							"aoColumnDefs": [ 
										{
											"aTargets"	: [ 0 ],//checked
											"mRender"	: function ( data, type, full ) {
												if (data == false)
												return '<input type="checkbox"  class="classCheckedDetailLine"  value="0" ></span>';
												else
												return '<input type="checkbox"  class="classCheckedDetailLine" checked="checked" value="0" ></span>';
											}
										},
										{
											"aTargets"		: [ 1 ],//customerCreditLineID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtProviderCreditLineID[]" />';
											}
										},
										{
											"aTargets"		: [ 2 ],//creditLineID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditLineID[]" />';
											}
										},
										{
											"aTargets"		: [ 3 ],//currencyID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditCurrencyID[]" />';
											}
										},
										{
											"aTargets"		: [ 4 ],//statusID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditStatusID[]" />';
											}
										},
										{
											"aTargets"		: [ 5 ],//InteresYear
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditInterestYear[]" />';
											}
										},
										{
											"aTargets"		: [ 6 ],//InteresPay
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditInterestPay[]" />';
											}
										},
										{
											"aTargets"		: [ 7 ],//TotalPay
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditTotalPay[]" />';
											}
										},
										{
											"aTargets"		: [ 8 ],//TotalDefeated
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditTotalDefeated[]" />';
											}
										},
										{
											"aTargets"		: [ 9 ],//DateOpen
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditDateOpen[]" />';
											}
										},
										{
											"aTargets"		: [ 10 ],//PeriodPay
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditPeriodPay[]" />';
											}
										},
										{
											"aTargets"		: [ 11 ],//DateLastPay
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditDateLastPay[]" />';
											}
										},
										{
											"aTargets"		: [ 12 ],//Term
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditTerm[]" />';
											}
										},
										{
											"aTargets"		: [ 13 ],//Note
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCreditNote[]" />';
											}
										},
										{
											"aTargets"		: [ 14 ],//Linea
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtLine[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 15 ],//Numero
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtLineNumber[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 16 ],//Limite
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtLineLimit[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 17 ],//Balance
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtLineBalance[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 18 ],//Estado
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtLineStatus[]" />'+data;
											}
										},
										{
											"aTargets"		: [ 19 ],//CurrencyName
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtCurrencyName[]" />'+data;
											}
										},										
										{
											"aTargets"		: [ 20 ],//typeAmortization
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtTypeAmortization[]" />';
											}
										},{
											"aTargets"		: [ 21 ],//Plan
											"bVisible"		: true,
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												var html_	= '';
												
												if(data == "CONSTANTE")
													html_		= '<i class="icon20 i-info btnInfoAmortizationConstante"></i>';
												else if(data == "FRANCES")
													html_		= '<i class="icon20 i-info btnInfoAmortizationFrances"></i>';
												else if(data == "ALEMAN")
													html_		= '<i class="icon20 i-info btnInfoAmortizationAleman"></i>';
												else if(data == "AMERICANO")
													html_		= '<i class="icon20 i-info btnInfoAmortizationAmericano"></i>';
												
												html_		= html_ + '<input type="hidden" value="'+data+'" name="txtTypeAmortizationLabel[]" />' + data;
												return html_;
											}
										},{
											"aTargets"		: [ 22 ],//Frecuencia
											"bVisible"		: true,
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtPeriodPayLabel[]" />'+data;
											}
										},{
											"aTargets"		: [ 23 ],//InteresYear
											"bVisible"		: true,
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtInterestYearLael[]" />'+data;
											}
										},{
											"aTargets"		: [ 24 ],//Term
											"bVisible"		: true,
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtTermLabel[]" />'+data;
											}
										},{
											
											"aTargets"		: [ 25 ],//creditLineID
											"bVisible"		: true,
											"sClass" 		: "hidden",
											"bSearchable"	: false,
											"mRender"		: function ( data, type, full ) {
												return '<input type="hidden" value="'+data+'" name="txtDayExcluded[]" />'+data;
											}
										}
							]							
						});

						$("#windowDialogConstante").dialog({
							autoOpen	: false,
							modal		: true,
							dialogClass	: "dialog",
							buttons		:{
								"cerrar":function(){
									$(this).dialog("close");
								}
							}
						});
						$("#windowDialogAleman").dialog({
							autoOpen	: false,
							modal		: true,
							dialogClass	: "dialog",
							buttons		:{
								"cerrar":function(){
									$(this).dialog("close");
								}
							}
						});
						$("#windowDialogFrancesa").dialog({
							autoOpen	: false,
							modal		: true,
							dialogClass	: "dialog",
							buttons		:{
								"cerrar":function(){
									$(this).dialog("close");
								}
							}
						});
						$("#windowDialogAmericano").dialog({
							autoOpen	: false,
							modal		: true,
							dialogClass	: "dialog",
							buttons		:{
								"cerrar":function(){
									$(this).dialog("close");
								}
							}
						});

						//Seleccionar Checke de Linea
						$(document).on("click",".classCheckedDetailLine",function(){
							var objrow_ = $(this).parent().parent().parent().parent()[0];
							var objind_ = objTableLine.fnGetPosition(objrow_);
							var objdat_ = objTableLine.fnGetData(objind_);								
							objTableLine.fnUpdate( !objdat_[0], objind_, 0 );
							refreschChecked();
						});

						//Editar Linea
						$(document).on("click","#btnEditLine",function(){
							console.info("call click_btnEditLine");
							
							
							var listRow 				= objTableLine.fnGetData();							
							var customerCreditLineID 	= 0;
							var length 					= listRow.length;
							var i 						= 0;
							var j 						= 0;
							while (i< length ){
								if(listRow[i][0] == true){
									customerCreditLineID = listRow[i][1];
								}
								i++;
							}
							
							if(customerCreditLineID == 0)
							return;
							
							window.open(site_url+"/app_cxp_provider/edit_credit_line/customerCreditLineID/"+customerCreditLineID,"MsgWindow","width=650,height=500");
							window.parentEditLine = parentEditLine;
							
						});			
						//Nuevo Line
						$(document).on("click","#btnNewLine",function(){
							console.info("call click_btnNewLine");
							window.open(site_url+"/app_cxp_provider/add_credit_line","MsgWindow","width=700,height=600");
							window.parentNewLine = parentNewLine;
						});						
						//Eliminar Linea
						$(document).on("click","#btnDeleteLine",function(){
							console.info("call click_btnDeleteLine");
							var listRow = objTableLine.fnGetData();							
							var length 	= listRow.length;
							var i 		= 0;
							var j 		= 0;
							while (i< length ){
								if(listRow[i][0] == true){
								objTableLine.fnDeleteRow( j,null,true );
								j--;
								}
								i++;
								j++;
							}
							
						});

						//Mostrar o ocultar columnas del grid de lineas de credito
						$('a.toggle-vis').on( 'click', function (e) {
							e.preventDefault();
							var iCol	= parseInt($(this).attr("data-column"));
							var bVis 	= objTableLine.fnSettings().aoColumns[iCol].bVisible;
							objTableLine.fnSetColumnVis( iCol, bVis ? false : true );
							var bVis	= $(this).parent().hasClass('active');
							bVis 		= bVis ? ($(this).parent().removeClass("active")) : ($(this).parent().addClass("active")); 
							
						});
					

						refreschChecked();
						$("#txtCountryID").change(function(){
							fnWaitOpen();
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								data 		: { catalogItemID : $(this).val() },
								url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByState",
								success:function(data){
									console.info("call app_catalog_api/getCatalogItemByState")
									fnWaitClose();
									
									
									$("#txtStateID").html("");
									$("#txtStateID").append("<option value=''>N/D</option>");
									$("#txtStateID").select2();
									$("#txtCityID").html("");
									$("#txtCityID").select2();
									
									
									if(data.catalogItems == null)
									return;
									
									$.each(data.catalogItems,function(i,obj){
										$("#txtStateID").append("<option value='"+obj.catalogItemID+"'>"+obj.name+"</option>");
									});
								},
								error:function(xhr,data){									
									fnShowNotification(data.message,"error");
									fnWaitClose();
								}
							});
						});
						
						$("#txtStateID").change(function(){
							fnWaitOpen();
							$.ajax({									
								cache       : false,
								dataType    : 'json',
								type        : 'POST',
								data 		: { catalogItemID : $(this).val() },
								url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByCity",
								success:function(data){
									console.info("call app_catalog_api/getCatalogItemByCity")
									fnWaitClose();
									$("#txtCityID").html("");
									$("#txtCityID").append("<option value=''>N/D</option>");
									$("#txtCityID").select2();
									
									if(data.catalogItems == null)
									return;
									
									$.each(data.catalogItems,function(i,obj){
										$("#txtCityID").append("<option value='"+obj.catalogItemID+"'>"+obj.name+"</option>");
									});
								},
								error:function(xhr,data){									
									fnShowNotification(data.message,"error");
									fnWaitClose();
								}
							});
						});
						//Nuevo Email
						$(document).on("click","#btnNewEmail",function(){
							console.info("call click_btnNewEmail");
							window.open(site_url+"app_cxp_provider/add_email","MsgWindow","width=650,height=500");
							window.parentNewEmail = parentNewEmail;
						});
						//Eliminar Email
						$(document).on("click","#btnDeleteEmail",function(){
							console.info("call click_btnDeleteEmail");
							var listRow = objTableEmail.fnGetData();							
							var length 	= listRow.length;
							var i 		= 0;
							var j 		= 0;
							while (i< length ){
								if(listRow[i][0] == true){
								objTableEmail.fnDeleteRow( j,null,true );
								j--;
								}
								i++;
								j++;
							}
							
						});
						//Seleccionar Checke de Email
						$(document).on("click",".classCheckedDetailEmail",function(){
							var objrow_ = $(this).parent().parent().parent().parent()[0];
							var objind_ = objTableEmail.fnGetPosition(objrow_);
							var objdat_ = objTableEmail.fnGetData(objind_);								
							objTableEmail.fnUpdate( !objdat_[0], objind_, 0 );
							refreschChecked();
						});
						//Nuevo Telefono
						$(document).on("click","#btnNewPhones",function(){
							console.info("call click_btnNewPhones");
							window.open(site_url+"/app_cxp_provider/add_phone","MsgWindow","width=650,height=500");
							window.parentNewPhone = parentNewPhone;
						});
						//Eliminar Telefono
						$(document).on("click","#btnDeletePhones",function(){
							console.info("call click_btnDeletePhones");
							var listRow = objTablePhone.fnGetData();							
							var length 	= listRow.length;
							var i 		= 0;
							var j 		= 0;
							while (i< length ){
								if(listRow[i][0] == true){
								objTablePhone.fnDeleteRow( j,null,true );
								j--;
								}
								i++;
								j++;
							}
							
						});
						//Seleccionar Checke de Telefonos
						$(document).on("click",".classCheckedDetailPhone",function(){
							var objrow_ = $(this).parent().parent().parent().parent()[0];
							var objind_ = objTablePhone.fnGetPosition(objrow_);
							var objdat_ = objTablePhone.fnGetData(objind_);								
							objTablePhone.fnUpdate( !objdat_[0], objind_, 0 );
							refreschChecked();
						});
					});
					function validateForm(){
						var result 				= true;
						var timerNotification 	= 15000;
						
						//Pais
						if($("#txtCountryID").val() == ""){
							fnShowNotification("Seleccionar el Pais","error",timerNotification);
							result = false;
						}
						//Departamento
						if($("#txtStateID").val() == ""){
							fnShowNotification("Seleccionar el Departamento","error",timerNotification);
							result = false;
						}
						//Municipio
						if($("#txtCityID").val() == ""){
							fnShowNotification("Seleccionar el Municipio","error",timerNotification);
							result = false;
						}
						//Identificacion
						if($("#txtIdentification").val() == ""){
							fnShowNotification("Escribir la Identificacion","error",timerNotification);
							result = false;
						}
						//Nombre
						if( 
							(
							$("#txtFirstName").val()  + 
							$("#txtLastName").val()   + 
							$("#txtLegalName").val()  + 
							$("#txtCommercialName").val() 
							)  == ""){
							fnShowNotification("Escribir el Nombre","error",timerNotification);
							result = false;
						}

						// if($($("#body_detail_line").find("tr")[0]).find("td").length <= 1){
						// 	fnShowNotification("Configurar una linea al proveedor","error",timerNotification);
						// 	result = false;
						// }
						
						return result;
						
					}
					function refreschChecked(){
						$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
					}
					function parentNewEmail(data){
						console.info("call parentNewEmail");
						console.info(data);
						
						objTableEmail.fnAddData([false,0,data.txtEmail,data.txtIsPrimary]);
						refreschChecked();
					}
					function parentNewPhone(data){
						console.info("call parentNewPhone");
						console.info(data);
						
						objTablePhone.fnAddData([false,0,data.txtEntityPhoneTypeID,data.txtEntityPhoneTypeDescription,data.txtEntityPhoneNumber,data.txtIsPrimary]);
						refreschChecked();
					}
					
					function parentNewLine(data){
						console.info("call parentNewLine");
						console.info(data);
						
						//Verificar que la linea no este configurada
						if(jLinq.from(objTableLine.fnGetData()).where(function(obj){ return obj[2] == data.txtCreditLineID;}).select().length > 0 ){
							fnShowNotification("La linea ya esta configurada","error");
							return;
						}
						
						
						objTableLine.fnAddData([
							false,						/*check*/
							0,							/*customerCreditLineID*/
							data.txtCreditLineID, 		/*creditLineID*/
							data.txtCurrencyID, 		/*currencyID*/
							data.txtStatusID, 			/*statusID*/
							data.txtInteresYear, 		/*interestYear*/
							0,							/*interestPay*/
							0,							/*totalPay*/
							0,							/*totalDefeated*/
							'',							/*dateOpen*/
							data.txtPeriodPay, 			/*periodPay*/
							'', 						/*dateLastPay*/
							data.txtTerm, 				/*term*/
							data.txtNote, 				/*note*/
							data.txtCreditLineIDDesc,	/*linea*/
							'N/D',						/*numero*/
							data.txtLimitCredit, 		/*limite*/
							data.txtLimitCredit, 		/*balance*/
							data.txtStatusIDDesc, 		/*statusName*/
							data.txtCurrencyIDDesc,		/*currencyName*/
							data.txtTypeAmortization,	/*typeAmortization*/
							'N/D',						/*typeAmortizationLabel*/
							'N/D',						/*periodPayLabel*/
							data.txtInteresYear,		/*interestYearLabel*/
							data.txtTerm				/*termLabel*/,
							data.txtDayExcluded			/*dayExclude*/
						]);
						refreschChecked();
					}

					function parentEditLine(data){
						console.info("call parentEditLine");
						console.info(data);
						
						for(var i = 0 ; i < objTableLine.fnGetData().length ; i++)
						{
							console.info(objTableLine.fnGetData()[i][1]);
							if(objTableLine.fnGetData()[i][1] == data.txtProviderCreditLineID)
							{
								objTableLine.fnUpdate( data.txtCreditLineID, i, 2 );
								objTableLine.fnUpdate( data.txtCurrencyID, i, 3 );
								objTableLine.fnUpdate( data.txtStatusID, i, 4 );
								objTableLine.fnUpdate( data.txtLimitCredit, i, 16 );
								objTableLine.fnUpdate( data.txtInteresYear, i, 5 );
								objTableLine.fnUpdate( data.txtPeriodPay, i, 10 );
								objTableLine.fnUpdate( data.txtTerm, i, 12 );
								objTableLine.fnUpdate( data.txtNote, i, 13 );
								objTableLine.fnUpdate( data.txtCreditLineIDDesc, i, 14 );
								objTableLine.fnUpdate( data.txtStatusIDDesc, i, 18 );
								objTableLine.fnUpdate( data.txtCurrencyIDDesc, i, 19 );
								objTableLine.fnUpdate( data.txtTypeAmortization, i, 20 );
								objTableLine.fnUpdate( data.txtTypeAmortizationDesc, i, 21);
								objTableLine.fnUpdate( data.txtPeriodPayDesc, i, 22);
								objTableLine.fnUpdate( data.txtInteresYear, i, 23 );
								objTableLine.fnUpdate( data.txtTerm, i, 24 );
								objTableLine.fnUpdate( data.txtDayExcluded, i, 25 );
								
							}
						}
						refreschChecked();
					}

				</script>