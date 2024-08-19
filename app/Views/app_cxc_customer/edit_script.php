<!-- ./ page heading -->
<script>	
	var objCallback		= '<?php echo $callback; ?>';				
	var objTableEmail 	= {};
	var objTablePhone 	= {};
	var objTableLine 	= {};
	var site_url 	  	= "<?php echo base_url(); ?>/";
	var userMobile		= '<?php echo $useMobile; ?>';
	
	//este evento es util cuando la pantalla se ejecuta desde la pantalla de facturacion
	if(objCallback != 'false'){
		$(window).unload(function() {
			//do something
			var dataCustomerNewRow 	= [];
			var dataCustomerItem 	= [];
			dataCustomerItem 		= ('0|'+$("#txtEntityID").val()+'|'+$("#txtEntityNumberID").val()+'|'+$("#txtLegalName").val()+'|'+$("#txtLegalName").val()).split("|");
			
			window.opener.<?php echo $callback; ?>(dataCustomerItem); 
		});
	}
	
	$(document).ready(function(){	
		//Inicializar DataPciker
		$('#txtVencimientoTarjeta').datepicker({format: "mm/yyyy"})
		$('#txtBirthDate').datepicker({format:"yyyy-mm-dd"});
		$('#txtDateContract').datepicker({format:"yyyy-mm-dd"});
		<?php echo getBehavio($company->type,"app_cxc_customer","divScriptReady",""); ?>		
		
		 //Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		//Buscar Colagorador
		$(document).on("click","#btnSearchEmployer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployer->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee; 
		});
		//Eliminar Colaborador
		$(document).on("click","#btnClearEmployer",function(){
					$("#txtEmployerID").val("");
					$("#txtEmployerDescription").val("");
		});
		
		//Traking
		$(document).on("click","#btnLeads",function(){
			$("#saveLeads").focus();
			 $("#mySidebar").css("width","250px");
			 
		});
		$(document).on("click","#cerrarLeads",function(){
			var sidebar = $("#mySidebar");		
			sidebar.css("width", "0");
			
			 
		});
		$(document).on("click","#saveLeads",function(){
			var sidebar = $("#mySidebar");		
			sidebar.css("width", "0");
			
			
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				url  		: "<?php echo base_url(); ?>/app_cxc_leads/save/new",
				data 		: {
						mode: "new",
						txtLeadTipo: $("#txtLeadTipo").val(),
						txtLeadSubTipo: $("#txtLeadSubTipo").val(),
						txtLeadCategory: $("#txtLeadCategory").val(),
						txtLeadComentario: $("#txtLeadComentario").val(),
						txtCustomerID : $("#txtEntityID").val()
				},
				success		: function(data){
					console.info(data);
					fnShowNotification("Leads agregado!","success");
				},
				error:function(xhr,data){						
					console.info("complete data error");													
					fnShowNotification("Error 505","error");
				}
			});
			
			
			 
		});
		
		
		
	
		
		//Eliminar el Documento
		$(document).on("click","#btnDelete",function(){
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_cxc_customer/delete",
					data 		: {companyID : <?php echo $objCustomer->companyID;?>, branchID : <?php echo $objCustomer->branchID;?> , entityID : <?php echo $objCustomer->entityID;?>  },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_cxc_customer/index";
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
				$( "#form-new-cxc-customer" ).attr("method","POST");
				$( "#form-new-cxc-customer" ).attr("action","<?php echo base_url(); ?>/app_cxc_customer/save/edit");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-cxc-customer" ).submit();
				}
				
		});
		$(document).on("click","#btnClickArchivo",function(){
			window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objCustomer->entityID; ?>","blanck");
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
		
		//Grid Lineas de credito
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
						$listrow[] 	= "[0,".$i->customerCreditLineID.",".$i->creditLineID.",".$i->currencyID.",".$i->statusID.",".$i->interestYear.",".$i->interestPay.",".$i->totalPay.",".$i->totalDefeated.",'".$i->dateOpen."',".$i->periodPay.",'".$i->dateLastPay."',".$i->term.",'".$i->note."','".$i->line."','".$i->accountNumber."','".number_format($i->limitCredit,2)."','".number_format($i->balance,2)."','".$i->statusName."','".$i->currencyName."',".$i->typeAmortization.",'".$i->typeAmortizationLabel."','".$i->periodPayLabel."','".number_format($i->interestYear,2)."','".number_format($i->term,2)."']";
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
								return '<input type="hidden" value="'+data+'" name="txtCustomerCreditLineID[]" />';
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
		
		
		$('a.toggle-vis').on( 'click', function (e) {
			e.preventDefault();
			var iCol	= parseInt($(this).attr("data-column"));
			var bVis 	= objTableLine.fnSettings().aoColumns[iCol].bVisible;
			objTableLine.fnSetColumnVis( iCol, bVis ? false : true );
			var bVis	= $(this).parent().hasClass('active');
			bVis 		= bVis ? ($(this).parent().removeClass("active")) : ($(this).parent().addClass("active")); 
			
		});
		$(document).on("click",".btnInfoAmortizationConstante",function(){
			$('#windowDialogConstante').dialog('open');
			return false;
		});
		$(document).on("click",".btnInfoAmortizationFrances",function(){
			$('#windowDialogFrancesa').dialog('open');
			return false;
		});
		$(document).on("click",".btnInfoAmortizationAleman",function(){
			$('#windowDialogAleman').dialog('open');
			return false;
		});
		$(document).on("click",".btnInfoAmortizationAmericano",function(){
			$('#windowDialogAmericano').dialog('open');
			return false;
		});
		
		refreschChecked();
		//Nueva cuenta
		$(document).on("click","#btnSearchAccount",function(){
			var url_request 				= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentAccount->componentID; ?>/onCompleteSetAccount/SELECCIONAR_CUENTA/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteSetAccount 	= onCompleteSetAccount; 
		});
		//Set cuenta
		function onCompleteSetAccount(objResponse){
			console.info("CALL onCompleteSetAccount");
			var objRow 						= {};
			objRow.accountID				= objResponse[1];
			objRow.accountName				= objResponse[2] + " " + objResponse[3];
			
			$("#txtAccountID").val(objRow.accountID);
			$("#txtAccountIDDescription").val(objRow.accountName);
		}
		//Limpiar Cuenta
		$(document).on("click","#btnClearAccount",function(){
			$("#txtAccountID").val("");
			$("#txtAccountIDDescription").val("");
		});
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
					
					if(userMobile != '1')
					$("#txtStateID").select2();
					$("#txtCityID").html("");
					
					if(userMobile != '1')
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
		
		$(document).on("keyup",'#txtIdentification', function(e) {	
			
			if( $("#txtSexoID").val() == 724 /*masculino*/ || $("#txtSexoID").val() == 725 /*femenino*/   )
			{
				var text 	 = $("#txtIdentification").val().replaceAll("-","").replaceAll(" ","");
				var longitud = text.length;
				var arrayc	 = text.split('');
				var fecha	 = $("#txtBirthDate").val();
				var rango	 = 0;

				if(longitud == 14)
				{
					
					rango	= parseInt(arrayc[7] + arrayc[8]) ;
					
					if(	rango > 50){
						rango = 1900 + rango ;
					}
					else {
						rango = 2000 + rango ;
					}
					
					fecha	= rango + "-" + arrayc[5] + arrayc[6] + "-" + arrayc[3] + arrayc[4];
					$("#txtBirthDate").val(fecha);
				}
			}
				
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
					
					if(userMobile != '1')
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
			
			window.open(site_url+"app_cxc_customer/edit_credit_line/customerCreditLineID/"+customerCreditLineID,"MsgWindow","width=650,height=500");
			window.parentEditLine = parentEditLine;
			
		});			
		//Nuevo Line
		$(document).on("click","#btnNewLine",function(){
			console.info("call click_btnNewLine");
			window.open(site_url+"app_cxc_customer/add_credit_line","MsgWindow","width=700,height=600");
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
		//Seleccionar Checke de Linea
		$(document).on("click",".classCheckedDetailLine",function(){
			var objrow_ = $(this).parent().parent().parent().parent()[0];
			var objind_ = objTableLine.fnGetPosition(objrow_);
			var objdat_ = objTableLine.fnGetData(objind_);								
			objTableLine.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
		//Nuevo Email
		$(document).on("click","#btnNewEmail",function(){
			console.info("call click_btnNewEmail");
			window.open(site_url+"app_cxc_customer/add_email","MsgWindow","width=650,height=500");
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
			window.open(site_url+"app_cxc_customer/add_phone","MsgWindow","width=650,height=500");
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
		
		$(document).on("click","#btnScanerHuella",function(){
			fnReadHuella();
		});

		$(document).on("click","#btnAddFrecuency",function(){
			fnAgregarFila();
		});
		$('#errorLabel').hide();
	});
	
	function fnEliminarFila(boton) {
		$(boton).closest('tr').remove();
	}

	function fnAgregarFila() {
		
		let texto = $('#txtNombreRecordatorio').val();

		// Si el campo de texto está vacío, mostrar un mensaje de error
        if (texto === "") {
            $('#errorLabel').show();
            return; 
        } else {
            $('#errorLabel').hide();
        }
		let combo1 = $('#txtSituationID').val();
		let combo2 = $('#txtFrecuencyContactID').val();
		let selected = '';
		let nuevaFila = ""+
        	"<tr> "+
                "<td>"+
					"<input class='form-control' type='hidden' name='customerFrecuencyActuations[]' value='0'> "+
                    "<input class='form-control' type='text' name='txtNombreRecordatorioArray[]' value='"+texto+"'> "+
                "</td>"+
                "<td>"+
                    "<select name='txtSituationIDArray[]' id='comboSituationId'>"+
					<?php
						if(isset($objListSituationID)){
							foreach($objListSituationID as $ws){
					?>
								"<option value='<?=$ws->catalogItemID?>' " + ((combo1===<?= $ws->catalogItemID?>) ? 'selected' : '') + "><?=$ws->name?></option>"+
					<?php
							}
						}
					?>
                "</td>"+
                "<td>"+
                    "<select name='txtFrecuencyContactIDArray[]' id='comboFrecuencyContactId'>"+
					<?php
						if(isset($objListFrecuencyContactID)){
							foreach($objListFrecuencyContactID as $ws){
					?>
				                "<option value='<?=$ws->catalogItemID?>' " + ((combo2==<?= $ws->catalogItemID?>) ? 'selected' : '') + "><?=$ws->name?></option>"+
					<?php
							}
						}
					?>
                    "</select>"+
                "</td>"+
                "<td>"+
                    "<button type='button' class='btn btn-flat btn-danger' onclick='fnEliminarFila(this)'>"+
                        "<i class='fas fa-trash'></i>"+
                    "</button>"+
                "</td>"+
            "</tr>";

		// Agregar la nueva fila después de la primera fila de entrada
		$('#filaEntrada').after(nuevaFila);

        $('#txtNombreRecordatorio').val('');
        $('#txtSituationID').val(null).trigger('change');
        $('#txtFrecuencyContactID').val(null).trigger('change');

		// Inicializar Select2 en los nuevos select creados
		$('#comboSituationId').select2();
		$('#comboFrecuencyContactId').select2();
	}
	
	function fnReadHuella(){
						
		$.ajax({									
			cache       : false,
			dataType    : 'json',
			type        : 'POST',
			url  		: "<?php echo base_url(); ?>/app_api_fingerprint/web_active_sensor_enroll",
			data 		: { customerID : $("#txtEntityID").val()  },
			success		: function()
			{
				
			},
			error:function(xhr,data)
			{	
				console.info("complete data error");													
				fnShowNotification("Error 505","error");
			}
		});			
		
	}
	
	function onCompleteEmployee(objResponse)
	{							
			
			$("#txtEmployerID").val(objResponse[2]);
			$("#txtEmployerDescription").val(objResponse[3] + " / " + objResponse[4]);
			
	}
	
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
		if($($("#body_detail_line").find("tr")[0]).find("td").length <= 1){
			fnShowNotification("Configurar una linea al cliente","error",timerNotification);
			result = false;
		}
		
		//Nombre
		if( $("#txtFirstName").val()   == ""){
			fnShowNotification("Escribir Primer Nombre","error",timerNotification);
			result = false;
		}

		if( $("#txtLastName").val()  == ""){
			fnShowNotification("Escribir Segundo Nombre","error",timerNotification);
			result = false;
		}

		if( $("#txtLegalName").val()  == ""){
			fnShowNotification("Escribir Nombre Legal","error",timerNotification);
			result = false;
		}

		if( $("#txtCommercialName").val()  == ""){
			fnShowNotification("Escribir Nombre Comercial","error",timerNotification);
			result = false;
		}
		
		<?php echo getBehavio($company->type,"app_cxc_customer","divScriptValideFunction",""); ?>		
		return result;
		
	}
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();						
		$('.txt-numeric').mask('000,000.00', {reverse: true});
	}
	function parentEditLine(data){
		console.info("call parentEditLine");
		console.info(data);
		
		for(var i = 0 ; i < objTableLine.fnGetData().length ; i++)
		{
			if(objTableLine.fnGetData()[i][1] == data.txtCustomerCreditLineID)
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
				
			}
		}
		refreschChecked();
	}
	function parentNewLine(data){
		console.info("call parentNewLine");
		console.info(data);
		
		//Berificar que la linea no este configurada
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
			data.txtTerm				/*termLabel*/
		]);
		refreschChecked();
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
</script>

