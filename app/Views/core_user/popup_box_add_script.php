<script >
	$(document).ready(function(){
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data								= {};
					data.txtDetailCashBoxID				= $("#txtCashBoxID").val();
					data.txtDetailCashBoxName			= $("#txtCashBoxID option:selected").text();
					window.opener.parentNewBox(data);  
					window.close(); 
			});
	});
</script>