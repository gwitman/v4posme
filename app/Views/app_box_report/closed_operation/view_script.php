<!-- ./ page heading -->
<script>
	$(document).ready(function(){
		$('#txtStartOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtStartOn').val(moment().format("YYYY-MM-DD"));
		$("#txtStartOn").datepicker("update");
		$('#txtEndOn').datepicker({format:"yyyy-mm-dd"});
		$('#txtEndOn').val(moment().format("YYYY-MM-DD"));
		$("#txtEndOn").datepicker("update");
    	$("#txtHourStartOn").val("00:00:00");
    	$("#txtHourEndOn").val("23:59:59");
		$(document).on("click","#print-btn-report",function(){
			var user 			=	$("#txtUserID").val();
			var startOn			=	$("#txtStartOn").val();
			var endOn			=	$("#txtEndOn").val();
			var hourStart		=	$("#txtHourStartOn").val();
			var hourEnd			=	$("#txtHourEndOn").val();
			var verDetalle		=	$("#txtVerDetalle").val();
			var formato			=	$("#txtFormato").val();
			// Validar fechas
	        var datePattern = /^\d{4}-\d{2}-\d{2}$/;
	        var timePattern = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/;

	        if (startOn && !datePattern.test(startOn)) {
	            fnShowNotification("La fecha de inicio no tiene un formato válido (YYYY-MM-DD).");
	            e.preventDefault();
	            return;
	        }

	        if (endOn && !datePattern.test(endOn)) {
	            fnShowNotification("La fecha de fin no tiene un formato válido (YYYY-MM-DD).");
	            e.preventDefault();
	            return;
	        }

	        if (hourStart && !timePattern.test(hourStart)) {
	            fnShowNotification("La hora de inicio no tiene un formato válido (HH:mm:ss).");
	            e.preventDefault();
	            return;
	        }

	        if (hourEnd && !timePattern.test(hourEnd)) {
	            fnShowNotification("La hora de fin no tiene un formato válido (HH:mm:ss).");
	            e.preventDefault();
	            return;
	        }
			if(!(user == ""  )){
				window.open("<?php echo base_url(); ?>/app_box_report/closed_operation/viewReport/true/user/"+
				user+"/startOn/"+startOn+"/endOn/"+endOn+ "/hourStart/"+hourStart+"/hourEnd/"+hourEnd+
				"/detalle/"+verDetalle+"/formato/"+formato, "_blank");
			}
			else{
				fnShowNotification("Completar los Parametros","error");
			}
		});
	});
</script>