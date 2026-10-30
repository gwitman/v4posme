<!-- ./ page heading -->
<script>
	var objListaCustomerCredit 				= [];
	var varUseMobile						= '<?php echo $useMobile; ?>';
	var varShareMountDefaultOfAmortization 	= '<?php echo getBehavio($company->type,"app_box_share","javscriptVariable_varShareMountDefaultOfAmortization",""); ?>';
	var varParameterCantidadItemPoup		= '<?php echo $objParameterCantidadItemPoup; ?>';
</script>
<?php echo view('app_box_share/share_v2_common'); ?>
<script>
	$(document).ready(function(){
		 $('#txtDate').datepicker({format:"yyyy-mm-dd"});
		 $('#txtDate').val(moment().format("YYYY-MM-DD"));
		 $("#txtDate").datepicker("update");
		 abv2BindCommon();

		 <?php
		 if(isset($customerEntityID))
		 {
			?>
			var objCustomerInit = [[]];
			objCustomerInit[0][0] = '<?php echo $customerEntityID; ?>';
			objCustomerInit[0][1] = '<?php echo $customerEntityID; ?>';
			objCustomerInit[0][2] = '<?php echo $objCustomer->customerNumber; ?>';
			objCustomerInit[0][3] = '<?php echo $objNatural->firstName; ?>';
			objCustomerInit[0][4] = '<?php echo $objNatural->lastName; ?>';
			onCompleteCustomer(objCustomerInit);
			<?php
		 }
		 ?>

		//Buscar el Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			if($("#txtCurrencyID").val() == ""){
				fnShowNotification("Selecciona la moneda","error");
				return;
			}
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?= $objComponentItem->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING_PAGINATED/true/empty/true/not_redirect_when_empty/1/1/"+varParameterCantidadItemPoup+"/";
			abv2OpenSearch(url_request, "Buscar cliente", "onCompleteCustomer", onCompleteCustomer, "width=900,height=450");
		});

		//Guardar
		$(document).on("click","#btnAcept",function(e){
			e.preventDefault();
			$( "#form-new-invoice" ).attr("method","POST");
			$( "#form-new-invoice" ).attr("action","<?php echo base_url(); ?>/app_box_share/save/new");

			if(validateForm()){
				fnWaitOpen();
				$( "#form-new-invoice" ).submit();
			}
		});

		//Agregar factura
		$(document).on("click","#btnNewShare",function(e){
			e.preventDefault();
			if($("#txtCustomerID").val() == ""){
				fnShowNotification("Selecciona el cliente","error");
				return;
			}
			if($("#txtCurrencyID").val() == ""){
				fnShowNotification("Selecciona la moneda","error");
				return;
			}
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbynamepaginate/<?php echo $objComponentCustomerCreditDocument->componentID; ?>/onCompleteNewShare/SELECCIONAR_DOCUMENTOS_DE_CREDITO_PAGINATED/true/"+encodeURI("{\"entityID\"|\""+$("#txtCustomerID").val()+"\",\"currencyID\"|\""+ $("#txtCurrencyID").val() +"\"}") + "/false/not_redirect_when_empty/1/1/500/";
			abv2OpenSearch(url_request, "Agregar factura", "onCompleteNewShare", onCompleteNewShare, "width=1585,height=795");
		});
	});

	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
		onCompleteCustomerCommon(objResponse);
	}

	function validateForm(){
		var result = abv2ValidateCommon();

		<?php echo getBehavio($company->type,"app_box_share","new_script_validate_reference1",""); ?>

		return result;
	}

	//Se conserva por compatibilidad con personalizaciones existentes
	function refreschChecked(){
		abv2Mask($('.abv2 .txt-numeric'));
	}
	function onCompletePantalla(){
	}
</script>
