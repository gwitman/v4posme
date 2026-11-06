<script >
	$(document).ready(function(){
			pv2MobileSelects(".pv2 select.select2");
			$("#btnPopupCancelar").click(function(e){e.preventDefault(); window.close();});
			$("#btnPopupAceptar").click(function(e){
					e.preventDefault();
					var data								= {};
					data.txtBranchID						= $("#txtBranchID").val();
					data.txtBranchDescription				= $("#txtBranchID option:selected").text();
					data.txtName							= $("#txtName").val();					
					data.txtWarehouseSourceID				= $("#txtWarehouseSourceID").val();
					data.txtWarehouseSourceDescription		= $("#txtWarehouseSourceID option:selected").text();
					data.txtWarehouseTargetID				= $("#txtWarehouseTargetID").val();
					data.txtWarehouseTargetDescription		= $("#txtWarehouseTargetID option:selected").text();
					data.txtIsDefault						= $("#txtIsDefault").prop('checked');				
					
					if(data.txtBranchID == "" || data.txtName == "")
					return;
					
					window.opener.parentNewCausal(data);  
					window.close(); 
			});
	});
</script>