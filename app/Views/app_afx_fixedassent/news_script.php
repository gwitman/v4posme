<!-- ./ page heading -->
<script>
	var site_url 	  = "<?php echo base_url(); ?>";
	var varUseMobile  = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

	$(document).ready(function(){

		//La fecha de ingreso abre vacia: no se debe guardar la de hoy de forma silenciosa
		var fechaOriginal = $.trim($("#txtDate").val());
		pv2InitDate("txtDate", false);
		if(fechaOriginal == "") $("#txtDate").val("");
		afxBindCommon();
		$('.txt-numeric').mask('000,000.00', {
			reverse: true
		});
		
			//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(e){
				e.preventDefault();
				$( "#form-new-afx-fixedassent" ).attr("method","POST");
				$( "#form-new-afx-fixedassent" ).attr("action","<?php echo base_url(); ?>/app_afx_fixedassent/save/new");
				
				if(validateForm()){
					fnWaitOpen();
					//El servidor recibe los montos como numero puro, sin el formato visual
					afxLimpiarMontos("#form-new-afx-fixedassent");
					$( "#form-new-afx-fixedassent" ).submit();
				}
				
		});
		//Buscar el Asignado A
		$(document).on("click","#btnSearchEmployee",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentEmployeeID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty";
			pv2OpenSearch(url_request, "Buscar colaborador", "onCompleteEmployee", onCompleteEmployee, "width=900,height=450");
		});
		//Eliminar Asignado A
		$(document).on("click","#btnClearEmployee",function(){
					$("#txtAsignedEmployeeID").val("");
					$("#txtAsignedEmployeeDescripcion").val("");
		});

		//Buscar Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			var url_redirect	= "__app_cxc_customer__add__callback__onCompleteCustomer__comando__pantalla_abierta_desde_fixedassent";
			url_redirect 		= encodeURIComponent(url_redirect);
			var url_request 	= "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/33/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/false/"+url_redirect+"/1/1/10/";
			pv2OpenSearch(url_request, "Buscar cliente", "onCompleteCustomer", onCompleteCustomer, "width=900,height=450");
		});
		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
					$("#txtAsignedCustomerID").val("");
					$("#txtAsignedCustomerDescripcion").val("");
		});

		//Buscar area
		$(document).on("click","#btnSearchArea",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCatalog->componentID; ?>/onCompleteArea/SELECCIONAR_ACTIVOFIJO_AREA/true/empty/false/not_redirect_when_empty";
			pv2OpenSearch(url_request, "Buscar área", "onCompleteArea", onCompleteArea, "width=900,height=450");
		});
		//Eliminar area
		$(document).on("click","#btnClearArea",function(){
			$("#txtAreaID").val("");
			$("#txtAreaDescripcion").val("");
		});

		//Buscar proyecto
		$(document).on("click","#btnSearchProyect",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCatalog->componentID; ?>/onCompleteProyect/SELECCIONAR_ACTIVOFIJO_PROYECTO/true/empty/false/not_redirect_when_empty";
			pv2OpenSearch(url_request, "Buscar proyecto", "onCompleteProyect", onCompleteProyect, "width=900,height=450");
		});
		//Eliminar proyecto
		$(document).on("click","#btnClearProyect",function(){
			$("#txtProyectID").val("");
			$("#txtProyectDescripcion").val("");
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

		$("#txtStateID").change(function(){
			fnWaitOpen();
			$.ajax({									
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				data 		: { catalogItemID : $(this).val() },
				url  		: "<?php echo base_url(); ?>/app_catalog_api/getCatalogItemByCity",
				success:function(data){
					console.info("call app_catalog_api/getCatalogItemByCity");
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

		<?php echo getBahavioSession($company->type,'tb_fixed_assent','scriptJsReady','',$objListCompanyPageSetting) ?>
		
	});
	function onCompleteEmployee(objResponse){
		console.info("CALL onCompleteEmployee");
		
		$("#txtAsignedEmployeeID").val(objResponse[0][2]);
		$("#txtAsignedEmployeeDescripcion").val(objResponse[0][3] + " / " + objResponse[0][4]);
		
	}

	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
		if(objResponse != undefined)
		{
			$("#txtAsignedCustomerID").val(objResponse[0][1]);
			$("#txtAsignedCustomerDescripcion").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
		}
	}

	function onCompleteArea(objResponse)
	{
		console.info("CALL onCompleteArea");

		$("#txtAreaID").val(objResponse[0][0]);
		$("#txtAreaDescripcion").val(objResponse[0][1] + " | " + objResponse[0][2]);
	}

	function onCompleteProyect(objResponse)
	{
		console.info("CALL onCompleteProyect");

		$("#txtProyectID").val(objResponse[0][0]);
		$("#txtProyectDescripcion").val(objResponse[0][1] + " | " + objResponse[0][2]);
	}

	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		//Categoria
		if($("#txtCategoryID").val() == ""){
			fnShowNotification("Seleccionar la Categoria","error",timerNotification);
			result = false;
		}
		//Tipo
		if($("#txtTypeID").val() == ""){
			fnShowNotification("Seleccionar el Tipo","error",timerNotification);
			result = false;
		}
		//Nombre
		if($("#txtName").val() == ""){
			fnShowNotification("Escribir el Nombre","error",timerNotification);
			result = false;
		}

		<?php echo getBahavioSession($company->type,'tb_fixed_assent','scriptJsValidateForm','',$objListCompanyPageSetting) ?>

		return result;
		
	}
</script>