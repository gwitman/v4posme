<!-- ./ page heading -->
<script>				
	$(document).ready(function(){
	
		$(document).on("click","#print-btn-report",function(){
			var txtCustomerNumber	=	$("#txtCustomerNumber").val();	
						
			if(!( txtCustomerNumber == ""  ) ){
				fnWaitOpen();
				window.location	= "<?php echo base_url(); ?>/app_cxc_report/history_purchase_by_customer/viewReport/true/customerNumber/"+txtCustomerNumber;
			}
			else{
				fnShowNotification("Completar los Parametros","error");
			}
		});
	});					
</script>