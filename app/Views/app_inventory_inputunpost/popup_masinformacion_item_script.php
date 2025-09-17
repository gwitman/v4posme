<script >
	$(document).ready(function(){
			
			$('#txtVencimiento').datepicker({format:"yyyy-mm-dd"});
			
			$("#btnPopupCancelar").click(function(){window.close();});
			$("#btnPopupAceptar").click(function(){ 
					var data										= {};
					data.txtLote									= $("#txtLote").val();
					data.txtVencimiento								= $("#txtVencimiento").val();
					data.txtPrecio1									= $("#txtPrecio1").val();
					data.txtPrecio2									= $("#txtPrecio2").val();
					data.txtReference4TransactionMasterDetail		= $("#txtReference4TransactionMasterDetail").val();
					data.txtPosition								= <?php  echo $positionID; ?>;
					data.txtIva										= $("#txtIva").val();
					data.txtIsc										= $("#txtIsc").val();
					window.opener.onCompleteUpdateMasInformacion(data);  
					window.close(); 
			});
			
			$('#txtCheckIva').change(function() {
				debugger;
				if ($(this).is(':checked')) {
					// Checkbox marcado
					var resultIvaUnitary = ($("#txtCosto").val() * 0.15);
					$("#txtIva").val(resultIvaUnitary);
					// Aquí tu acción cuando se marca
				} else {
					// Checkbox desmarcado
					var resultIvaUnitary = 0.00;
					$("#txtIva").val(resultIvaUnitary);
					// Aquí tu acción cuando se desmarca
				}
			});
		
	});
</script>