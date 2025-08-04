<!-- ./ page heading -->
<script>					
	
	$(document).ready(function(){
						
		var varParameterCantidadItemPoup	= '<?php echo $objParameterCantidadItemPoup; ?>';  

		//Regresar a la lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		//Evento Agregar el Usuario
		$(document).on("click","#btnAcept",function(){

				if (validateForm()) {
					fnWaitOpen();
					$( "#form-new-account-type" ).attr("method","POST");
					$( "#form-new-account-type" ).attr("action","<?php echo base_url(); ?>/app_collection_manager/save/edit");
					$( "#form-new-account-type" ).submit();
				}
		});

		//Comando Eliminar
		$(document).on("click","#btnDelete",function(){
		fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
		fnWaitOpen();
				$.ajax({
				cache       : false,
				dataType    : 'json',
				type        : 'POST',
				url  		: "<?php echo base_url(); ?>/app_collection_manager/delete",
				data 		: {relationshipID : <?php echo $objRelationship->relationshipID;?> },
				success:function(data){
					console.info("complete delete success");
					fnWaitClose();
					if(data.error){
						fnShowNotification(data.message,"error");
					}
					else{
						window.location = "<?php echo base_url(); ?>/app_collection_manager/index";
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

		
		//Buscar Colagorador
		$(document).on("click","#btnSearchEmployee",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteEmployee = onCompleteEmployee; 
		});

		//Eliminar Colaborador
		$(document).on("click","#btnClearEmployee",function(){
					$("#txtEmployeeID").val("");
					$("#txtEmployeeDescription").val("");
		});

		function onCompleteEmployee(objResponse){
			console.info("CALL onCompleteEmployee");
			
			$("#txtEmployeeID").val(objResponse[0][2]);
			$("#txtEmployeeDescription").val(objResponse[0][3] + " / " + objResponse[0][4]); 
			
		}
		
		//Buscar Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_ALL_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCustomer = onCompleteCustomer; 
		});
		
		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
					$("#txtCustomerID").val("");
					$("#txtCustomerDescription").val("");
		});

		function onCompleteCustomer(objResponse){
			console.info("CALL onCompleteCustomer");
			
			$("#txtCustomerID").val(objResponse[0][1]);
			$("#txtCustomerDescription").val(objResponse[0][2] + " / " + objResponse[0][3]); 
			
		}
		
		$(document).on("click","#btnSearchCustomerAfter",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomerAfter/SELECCIONAR_CLIENTES_ALL_PAGINATED/true/empty/false/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCustomerAfter = onCompleteCustomerAfter; 
		});
		$(document).on("click","#btnClearCustomerAfter",function(){
					$("#txtCustomerIDAfter").val("0");
					$("#txtCustomerDescriptionAfter").val("");
		});
		function onCompleteCustomerAfter(objResponse){
			console.info("CALL onCompleteCustomer");
			
			$("#txtCustomerIDAfter").val(objResponse[0][1]);
			$("#txtCustomerDescriptionAfter").val(objResponse[0][2] + " / " + objResponse[0][3]); 
			
		}
	
														
	});

	function validateForm() {
		let result = true;
		let timerNotification = 15000;

		//Validar Colaborador.
		if ($("#txtEmployeeDescription").val() == "") {
			fnShowNotification("El Colaborador es requerido", "error", timerNotification);
			result = false;
		}	
		
		//Validar Cliente.
		if ($("#txtCustomerDescription").val() == "") {
			fnShowNotification("El Cliente es requerido", "error", timerNotification);
			result = false;
		}

		if ($("#txtCustomerIDAfter").val() == $('#txtCustomerID').val()) {
			fnShowNotification("El Cliente es identico al siguiente orden indicado", "error", timerNotification);
			result = false;
		}

		if (
		    (
		        $("#txtCustomerIDAfter").val() == "" ||	 $("#txtCustomerIDAfter").val() == "0"
		    ) &&
		    (
		        $('#txtOrderNo').val() == "" || $('#txtOrderNo').val() == "0"
		    )
		) {
		    fnShowNotification("Debe indicar el orden a ingresar el Cliente o debe ser mayor que 0", "error", timerNotification);
		    result = false;
		}

        let orderText = $('#txtOrderNo').val().trim();

        // Permitir campo vacío → irá de último
        if (orderText !== '') {
            let orderVal = parseInt(orderText, 10);
            if (isNaN(orderVal) || orderVal < 0) {
                fnShowNotification("No puede ingresar valores negativos en el orden", "error", timerNotification);
                result = false;
            }
        }

		return result;

	}

</script>