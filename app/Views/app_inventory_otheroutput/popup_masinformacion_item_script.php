<script >
	$(document).ready(function(){
			
			$('#txtVencimiento').datepicker({format:"yyyy-mm-dd"});
			
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data					= {};
					data.txtLote				= $("#txtLote").val();
					data.txtVencimiento			= $("#txtVencimiento").val();
					data.txtPosition			= <?php  echo $positionID; ?>;
					window.opener.onCompleteUpdateMasInformacion(data);  
					window.close(); 
			});
	});
	//Telefono: combos nativos (sin select2) y calendario nativo en las fechas. En PC no cambia nada
	$(document).ready(function(){
		PosmeInv.mobileDates("#txtVencimiento");
	});
</script>