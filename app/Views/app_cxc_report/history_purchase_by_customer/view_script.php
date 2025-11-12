<!-- ./ page heading -->
<script>				
	$(document).ready(function(){
	
		$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtStartOn').val(moment().format("YYYY-MM-DD"));
		$("#txtStartOn").datepicker("update");																		
		$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtEndOn').val(moment().format("YYYY-MM-DD"));		
		$("#txtEndOn").datepicker("update");						
		
		$(document).on("click","#print-btn-report",function(){
			var txtCustomerNumber		=	$("#txtCustomerNumber").val();	
			var startOn					=	$("#txtStartOn").val();	
			var endOn					=	$("#txtEndOn").val();
			var transactionCausalName	=	$("#txtTransactionCausalName").val();	
			
			if(!( txtCustomerNumber == "" || startOn == "" || endOn == ""  ) ){
				fnWaitOpen();
				window.location	= "<?php echo base_url(); ?>/app_cxc_report/history_purchase_by_customer/viewReport"+
					"/true/customerNumber/"+txtCustomerNumber+
					"/startOn/"+ startOn+"/endOn/"+endOn+
					"/transactionCausalName/"+transactionCausalName;
			}
			else{
				fnShowNotification("Completar los Parametros","error");
			}
		});
	});					
</script>