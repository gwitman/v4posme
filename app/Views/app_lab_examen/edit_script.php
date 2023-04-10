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
					".$i->transactionMasterDetailID.",
					".$i->componentItemID.",
					'".$i->name."',								
					'".$i->reference3."'
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
						'',
						".$i->catalogItemID.",
						'".$i->name."',								
						''
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
										return '<input type="hidden" value="'+data+'" name="txtCatalogItemID[]" />';
									}
								},
								{
									"aTargets"	: [2], //Nombre
									"sWidth"	: "40%"
								},
								{
									"aTargets"	: [ 3 ],//Valor
									"mRender"	: function ( data, type, full ) {
										return '<input type="text" class="col-lg-12 " value="'+data+'" name="txtValue[]" />';
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
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentCustomer->componentID; ?>/onCompleteCustomer/SELECCIONAR_CLIENTES_BILLING/true/empty";
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
	
	
		var entityID = objResponse[1];
		$("#txtCustomerID").val(objResponse[1]);
		$("#txtCustomerDescription").val(objResponse[2] + " " + objResponse[3] + " / " + objResponse[4]);
			
				
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
		
		return result;
	}
	
</script>
<script>  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); </script>
<script>
	//window.userGuiding.identify(userId*, attributes)
	  
	// example with attributes
	window.userGuiding.identify('<?php echo get_cookie("email"); ?>', {
	  email: '<?php echo get_cookie("email"); ?>',
	  name: '<?php echo get_cookie("email"); ?>',
	  created_at: 1644403436643,
	});
	// or just send userId without attributes
	//window.userGuiding.identify('1Ax69i57j0j69i60l4')
</script>
