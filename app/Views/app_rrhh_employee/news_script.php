				<!-- ./ page heading -->
				<script>					
					var objTableEmail = {};
					var objTablePhone = {};
					var site_url 	  = "<?php echo base_url(); ?>";
					
					$(document).ready(function(){	
						$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
						$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
						
						 //Regresar a la lista
						$(document).on("click","#btnBack",function(){
								fnWaitOpen();
						});
						
						//Evento Agregar el Usuario
						$(document).on("click","#btnAcept",function(){
								$( "#form-new-rrhh-employee" ).attr("method","POST");
								$( "#form-new-rrhh-employee" ).attr("action","<?php echo base_url(); ?>/app_rrhh_employee/save/new");
								
								if(validateForm()){
									fnWaitOpen();
									$( "#form-new-rrhh-employee" ).submit();
								}
								
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
												return '<input type="hidden" value="'+data+'" name="txtEmailIsPrimary[]" />'+(data == true ? "SI" : "NO");
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
												return '<input type="hidden" value="'+data+'" name="txtEntityPhoneIsPrimary[]" />'+ (data == true ? "SI" : "NO");
											}
										}
							]							
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
							window.open(site_url+"app_rrhh_employee/add_email","MsgWindow","width=650,height=500");
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
							window.open(site_url+"/app_rrhh_employee/add_phone","MsgWindow","width=650,height=500");
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
						//Buscar el Jefe
						$(document).on("click","#btnSearchEmployeeParent",function(){
							var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
							window.open(url_request,"MsgWindow","width=900,height=450");
							window.onCompleteEmployee = onCompleteEmployee; 
						});
						//Eliminar Jefe
						$(document).on("click","#btnClearEmployeeParent",function(){
									$("#txtParentEmployeeID").val("");
									$("#txtParentDescription").val("");
						});
						
					});
					function onCompleteEmployee(objResponse){
						console.info("CALL onCompleteEmployee");
						
						$("#txtParentEmployeeID").val(objResponse[0][2]);
						$("#txtParentDescription").val(objResponse[0][3] + " / " + objResponse[0][4]);
						
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
						//Nombre
						if( 
							(
							$("#txtFirstName").val()  + 
							$("#txtLastName").val()   
							)  == ""){
							fnShowNotification("Escribir el Nombre","error",timerNotification);
							result = false;
						}
						
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
				</script>