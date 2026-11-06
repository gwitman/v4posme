<!-- ./ page heading -->
<script>				
	var objCallback							= '<?php  echo $callback; ?>';	
	var objTableEmail 						= {};
	var objTablePhone 						= {};
	var objTableLine 						= {};
	var site_url 	  						= "<?php echo base_url(); ?>/";
	var departamentoDefault 				= "<?php echo $objParameterDepartamento; ?>";
	var municipioDefault 					= "<?php echo $objParameterMunicipio; ?>";
	var varUseMobile						= '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';
	//En telefono pv2OpenSearch reemplaza window.<callback>: se guarda la funcion original para no anidar envolturas
	var cstCallbacks						= { email : parentNewEmail, phone : parentNewPhone, line : parentNewLine, employee : onCompleteEmployee };
	var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';
	
	
	
	//este evento es util cuando la pantalla se ejecuta desde la pantalla de facturacion
	if(objCallback != 'false'){
		$(window).unload(function() {
			//do something			
			window.opener.<?php echo $callback; ?>(); 
		});
	}
	
	$(document).ready(function(){	
		//Inicializar DataPciker
		cstInitVencimiento("txtVencimientoTarjeta");
		//El nacimiento conserva su valor inicial; la fecha de contacto inicia en hoy
		pv2InitDate("txtBirthDate", false);
		pv2InitDate("txtDateContract", true);
		
		
		
		refreschChecked();
		//Montos, combos nativos en telefono y barra inferior. Una sola llamada: una segunda guardaria dos veces
		prvBindCommon();
		<?php echo getBehavio($company->type,"app_cxc_customer","divScriptReady",""); ?>
		
		
		
		 //Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-cxc-customer" ).attr("method","POST");
				$( "#form-new-cxc-customer" ).attr("action","<?php echo base_url(); ?>/app_cxc_customer/save/new");
				
				if(validateForm()){
					fnWaitOpen();
					//El servidor recibe numero puro, sin comas
					afxLimpiarMontos("#form-new-cxc-customer");
					$( "#form-new-cxc-customer" ).submit();
				}
				
		});
		
		//Buscar Colagorador
		$(document).on("click","#btnSearchEmployer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployer->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
			//Ventana emergente en PC; dentro de la misma pantalla en telefono
			pv2OpenSearch(url_request,"Buscar agente","onCompleteEmployee",cstCallbacks.employee,"width=900,height=450");
		});
		//Eliminar Colaborador
		$(document).on("click","#btnClearEmployer",function(){
					$("#txtEmployerID").val("");
					$("#txtEmployerDescription").val("");
		});
		
		$(document).on("click","#btnSearchClienteReferente",function(){

			//Redireccion pantalla
			var url_redirect		= "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_la_factura";
			url_redirect 			= encodeURIComponent(url_redirect);


			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_ALL_PAGINATED/true/empty/true/"+url_redirect+"/1/1/"+varParameterCantidadItemPoup+"/";
			pv2OpenSearch(url_request,"Buscar cliente","onCompleteCustomer",onCompleteCustomer,"width=900,height=450");
		});
		
		$(document).on("click","#btnClearCustomerReferente",function(){
					$("#txtCustomerIDReference").val("");
					$("#txtCustomerIDReferenceDescription").val("");
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
						txtCustomerID : 0
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
		
		
		
		//Grid de Email
		objTableEmail = $("#tb_detail_email").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
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
		//Grid de Linea
		objTableLine = $("#tb_detail_credit_line").dataTable({
			"fnCreatedRow"	: prvLineCreatedRow,
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
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
								return prvMontoCell(data,"txtLineLimit[]");
							}
						},
						{
							"aTargets"		: [ 17 ],//Balance
							"mRender"		: function ( data, type, full ) {
								return prvMontoCell(data,"txtLineBalance[]");
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
						},
						{
							"aTargets"		: [ 21 ],//dayExclueded
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDayExcluded[]" />';
							}
						},
			]							
		});
		
		//Nueva cuenta
		$(document).on("click","#btnSearchAccount",function(){
			var url_request 				= "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentAccount->componentID; ?>/onCompleteSetAccount/SELECCIONAR_CUENTA/true/empty/false/not_redirect_when_empty";
			pv2OpenSearch(url_request,"Buscar cuenta","onCompleteSetAccount",onCompleteSetAccount,"width=900,height=450");
		});
		//Set cuenta
		function onCompleteSetAccount(objResponse){
			console.info("CALL onCompleteSetAccount");
			var objRow 						= {};
			objRow.accountID				= objResponse[0][1];
			objRow.accountName				= objResponse[0][2] + " " + objResponse[0][3];
			
			$("#txtAccountID").val(objRow.accountID);
			$("#txtAccountIDDescription").val(objRow.accountName);
		}
		
		function onCompleteCustomer(objResponse)
		{

			console.info("CALL onCompleteCustomer");
			if(objResponse !== undefined)
			{
				
				var entityID = objResponse[0][1];
				$("#txtCustomerIDReference").val(objResponse[0][1]);
				$("#txtCustomerIDReferenceDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);				
			}
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
					afxInitSelect2("#txtStateID");
					$("#txtCityID").html("");
					afxInitSelect2("#txtCityID");


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
					afxInitSelect2("#txtCityID");

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
		//Nuevo Line
		$(document).on("click","#btnNewLine",function(){
			console.info("call click_btnNewLine");
			//Ventana emergente en PC; dentro de la misma pantalla en telefono
			pv2OpenSearch(site_url+"app_cxc_customer/add_credit_line","Agregar línea de crédito","parentNewLine",cstCallbacks.line,"width=700,height=600");
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
			//Ventana emergente en PC; dentro de la misma pantalla en telefono
			pv2OpenSearch(site_url+"app_cxc_customer/add_email","Agregar correo","parentNewEmail",cstCallbacks.email,"width=650,height=500");
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
			//Ventana emergente en PC; dentro de la misma pantalla en telefono
			pv2OpenSearch(site_url+"app_cxc_customer/add_phone","Agregar teléfono","parentNewPhone",cstCallbacks.phone,"width=650,height=500");
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
		//Nuevo recordatorio
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
        	"<tr class='row_razon'> "+
                "<td class='afx-col-block' data-label='Descripcion'>"+
                    "<input class='form-control' type='text' name='txtNombreRecordatorioArray[]' value='"+texto+"'> "+
                "</td>"+
                "<td class='afx-col-block' data-label='Estado'>"+
                    "<select name='txtSituationIDArray[]' id='comboSituationId'>"+
					<?php
						if(isset($objListSituationID)){
							foreach($objListSituationID as $ws){
					?>
								"<option value='<?=$ws->catalogItemID?>' " + ((combo1==<?= $ws->catalogItemID?>) ? 'selected' : '') + "><?=$ws->name?></option>"+
					<?php
							}
						}
					?>
                "</td>"+
                "<td class='afx-col-block' data-label='Frecuencia'>"+
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
                "<td data-label='Acción'>"+
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

		// Inicializar los nuevos select creados: select2 en PC, combo nativo en telefono
		afxInitSelect2('#comboSituationId');
		afxInitSelect2('#comboFrecuencyContactId');
	}

	function onCompleteEmployee(objResponse)
	{							
			
			$("#txtEmployerID").val(objResponse[0][2]);
			$("#txtEmployerDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
			
	}
	
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		
		<?php echo getBehavio($company->type,"app_cxc_customer","divScriptValideFunctionPre",""); ?>		
		
		//Pais
		//if($("#txtCountryID").val() == ""){
		//	fnShowNotification("Seleccionar el Pais","error",timerNotification);
		//	result = false;
		//}
		////Departamento
		//if($("#txtStateID").val() == ""){
		//	fnShowNotification("Seleccionar el Departamento","error",timerNotification);
		//	result = false;
		//}
		////Municipio
		//if($("#txtCityID").val() == ""){
		//	fnShowNotification("Seleccionar el Municipio","error",timerNotification);
		//	result = false;
		//}
		
		//Identificacion
		if($("#txtIdentification").val() == ""){
			fnShowNotification("Escribir la Identificacion","error",timerNotification);
			result = false;
		}
		
		//Sexo
		if($("#txtSexoID").val() == ""){
			fnShowNotification("Escribir el sexo","error",timerNotification);
			result = false;
		}
		
		
		
		//if($($("#body_detail_line").find("tr")[0]).find("td").length <= 1){
		//	fnShowNotification("Configurar una linea al cliente","error",timerNotification);
		//	result = false;
		//}
		
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
		//Solo casillas: los combos nativos del telefono no deben envolverse con uniform
		$("[type='checkbox']").not(".toggle, .multiselect").uniform();
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
			false,
			0,
			data.txtCreditLineID,
			data.txtCurrencyID,
			data.txtStatusID,
			data.txtInteresYear,
			0,
			0,
			0,
			'',
			data.txtPeriodPay,
			'',
			data.txtTerm,
			data.txtNote,
			data.txtCreditLineIDDesc,
			'N/D',
			data.txtLimitCredit,
			data.txtLimitBalance,
			data.txtStatusIDDesc,
			data.txtCurrencyIDDesc,
			data.txtTypeAmortization,
			data.txtDayExcluded
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

