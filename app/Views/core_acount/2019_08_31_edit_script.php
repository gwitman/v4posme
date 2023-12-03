<script>
	$(document).ready(function(){
			$(document).on("click","#btnAcept",function(){
				fnWaitOpen();
				$( "#form-edit-acount" ).attr("method","POST");
				$( "#form-edit-acount" ).attr("action","<?php echo base_url(); ?>/core_acount/edit");
				$( "#form-edit-acount" ).submit();
			});	
	}); 
</script>