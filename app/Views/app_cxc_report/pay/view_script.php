<!-- ./ page heading -->
<script>				
	$(document).ready(function(){
	
		$(document).on("click","#print-btn-report",function(){
			var txtCustomerNumber	=	$("#txtCustomerNumber").val();	
			var txtReference		=	$("#txtReference").val();

			if(txtReference == ''){
				txtReference = '0';
			}

			if(!( txtCustomerNumber == ""  ) ){
				fnWaitOpen();
				window.location	= "<?php echo base_url(); ?>/app_cxc_report/pay/viewReport/true/customerNumber/"+txtCustomerNumber+"/reference/"+txtReference;
			}
			else{
				fnShowNotification("Completar los Parametros","error");
			}
		});
	});					
</script>