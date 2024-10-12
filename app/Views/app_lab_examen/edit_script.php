<!-- ./ page heading -->
<script>	
	var numberDecimal				= 2;
	var numberDecimalSummary		= 2;
	var numberDecimalSummaryRound	= false;
	var objTableDetailTransaction 	= {};
	var objDetailTransactionMaster  = [];
	
	
	<?php 
		
		
		// no hay detalle
		if($objTransactionMasterDetail){
			$listrow = [];									
			foreach($objTransactionMasterDetail as $i)
			{
			$listrow[] = 
				"[
					'".$i->transactionMasterDetailID."',
					'".$i->componentItemID."',
					'".$i->name."',								
					'".$i->reference3."',
					'".$i->reference4."',
					'".$i->display."'
				]";
			}
			echo "objDetailTransactionMaster = [".implode(",",$listrow)."]";
		}
		
		//no hay detalle pero hay catalogo
		if(!$objTransactionMasterDetail && $objListExamenesParametros){
			$listrow = [];									
			foreach($objListExamenesParametros as $i)
			{
				$listrow[] = 
					"[
						'0',
						'".$i->publicCatalogDetailID."',
						'".$i->name."',								
						'', /*valor por default*/
						'".$i->reference4."',
						'".$i->display."'
					]";
			}
			echo "objDetailTransactionMaster = [".implode(",",$listrow)."]";
		}
	?>
	
	$(document).ready(function(){	
		$('#txtDate').datepicker({format:"yyyy-mm-dd"});
		$("#txtDate").datepicker("update");
		
		objTableDetailTransaction = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			
			<?php
			if ($objTransactionMasterDetail)
				echo '"aaData": 		objDetailTransactionMaster,';
			if (!$objTransactionMasterDetail && $objListExamenesParametros)
				echo '"aaData": 		objDetailTransactionMaster,';
			?>
			
			"aoColumnDefs": [
								{
									"aTargets"		: [ 0 ],//transactionMasterDetailID
									"bVisible"		: true,
									"sClass" 		: "hidden",
									"bSearchable"	: false,
									"mRender"		: function ( data, type, full ) {
										return '<input type="hidden" value="'+data+'" name="txtTransactionMasterDetailID[]" />';
									}
								},
								{
									"aTargets"		: [1],//catalogItemID
									"bVisible"  	: true,
									"sClass" 		: "hidden",
									"bSearchable"	: false,
									"mRender"		: function ( data, type, full ) {
										return '<input type="hidden" value="'+data+'" name="txtPublicCatalogDetailID[]" />';
									}
								},
								{
									"aTargets"	: [2], //Nombre
									"sWidth"	: "40%"
								},
								{
									"aTargets"	: [ 3 ],//Valor
									"mRender"	: function ( data, type, full ) {
										
										var ele = '<input type="text" class="col-lg-12 " value="'+data+'" name="txtValue[]" />';
										
										if(full[5] != "")
										{
											ele 	= ele + "<span class='badge badge-danger' >valores normales : "+full[5]+"</span>";
										}
										if(full[4] != "")
										{
											ele 	= ele + "<span class='badge badge-success' >valores posible : "+full[4]+"</span>";
										}
										
										return ele;
									}
								}
							]							
		});
		
		//Ir a Lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		$(document).on("click","#btnPrinter",function(){
				fnWaitOpen();	
				var controller 	= $('#txtExamenID').find(":selected").data("urlprinter");				
				var url_ 		= "<?php echo base_url(); ?>/app_lab_examen/"+controller+"/companyID/<?php echo helper_RequestGetValueObjet($objTransactionMaster,"companyID",0);?>/transactionID/<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionID",0); ?>/transactionMasterID/<?php echo helper_RequestGetValueObjet($objTransactionMaster,"transactionMasterID",0); ?>";
				window.open(url_,"_blank");
				fnWaitClose();
		});
		
		//Buscar el Cliente
		$(document).on("click","#btnSearchCustomer",function(){
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/true/empty/false/not_redirect_when_empty";
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteCustomer = onCompleteCustomer; 
		});		
		
		//Eliminar Cliente
		$(document).on("click","#btnClearCustomer",function(){
					$("#txtCustomerID").val("");
					$("#txtCustomerDescription").val("");
		});
		
		//Guardar Documento
		$(document).on("click","#btnAcept",function(){
			
				var horaInicial				=   $("#txtHora_hora").val();
				var minutoInicial			=   $("#txtHora_minuto").val();
				var zonaInicial				=   $("#txtHora_zona").val();
				var horaInicial				=   zonaInicial == "PM" ? (parseInt( horaInicial) + 12) : horaInicial;
				var horaInicial				=   "0000-00-00 "+horaInicial+":"+minutoInicial+":00";
				$("#txtHoraHidden").val(horaInicial);
							
				$( "#txtComando" ).val("save");
				$( "#form-new-transaction" ).attr("method","POST");
				$( "#form-new-transaction" ).attr("action","<?php echo base_url(); ?>/app_lab_examen/edit/companyID/0/transactionID/31/transactionMasterID/0");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-transaction" ).submit();
				}
				
		});	
		
	});
	
	//Cargar Cliente
	function onCompleteCustomer(objResponse){
		console.info("CALL onCompleteCustomer");
	
	
		var entityID = objResponse[0][1];
		$("#txtCustomerID").val(objResponse[0][1]);
		$("#txtCustomerDescription").val(objResponse[0][2] + " " + objResponse[0][3] + " / " + objResponse[0][4]);
			
				
	}
	
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		//Bodega Proveedor
		if($("#txtDate").val()==""){
			fnShowNotification("Seleccionar la fecha","error",timerNotification);
			result = false;
		}
		
		//Validar Examen
		if($("#txtExamenID").val() == ""){
			fnShowNotification("Establecer Examen","error",timerNotification);
			result = false;
		}
		
		//Validar Estado
		if($("#txtStatusID").val() == ""){
			fnShowNotification("Establecer Estado","error",timerNotification);
			result = false;
		}
		//Fecha
		if($("#txtNote").val() == ""){
			fnShowNotification("Escribir nota","error",timerNotification);
			result = false;
		}
		
		//Sexo
		if($("#txtSexoID").val() == ""){
			fnShowNotification("Escribir sexo","error",timerNotification);
			result = false;
		}
		
		//Edad
		if($("#txtEdadID").val() == ""){
			fnShowNotification("Escribir edad","error",timerNotification);
			result = false;
		}
		
		
		
		return result;
	}
	
</script>
