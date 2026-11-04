<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    $(document).ready(function() {

        var selectedEmployeeData = null;

        //Las fechas abren vacias; se llenan al elegir el colaborador
        plaInitDate("txtEmployeeLeaveStartDate");
        plaInitDate("txtEmployeeLeaveEndDate");
        plaInitDate("txtEmployeeLeaveIntegrationDate");
        afxBindCommon();

        //Regresar a la lista
        $("#btnBack").on("click", function() 
        {
            fnWaitOpen();
        });

        //Seleccionar colaborador
        $("#btnSearchEmployee").on("click", function() 
        {
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteNewEmployee/SELECCIONAR_EMPLOYEE_TO_PLANILLA/true/empty/false/not_redirect_when_empty";
            pv2OpenSearch(url_request, "Buscar colaborador", "onCompleteNewEmployee", onCompleteNewEmployee, "width=900,height=500");
        });

        //Limpiar datos del colaborador
        $("#btnClearEmployee").on("click", function() 
        {
            $("#form-new-employee-leave input").val(""); 
            $("#txtEmployeeLeaveNote").val("");
        });

        //Agregar Registro
        $("#btnAcept").on("click", function(e)
        {
            e.preventDefault();
            if(validateForm())
            {
                fnWaitOpen();
                $("#form-new-employee-leave")
                .attr("method","POST")
                .attr("action","<?php echo base_url(); ?>/app_planilla_leave/save/new")
                .submit();
            }
        });

        $("#form-new-employee-leave input").on("change",function(){
            onUpdatedEmployeeData(selectedEmployeeData);
        });

        //Validar que se haya ingresado un colaborador
        function validateForm() 
        {
            var isValidForm                 = true;
            var timerNotification           = 15000;
            var employeeID                  = $("#txtEmployeeEntityID").val();
            var totalVacationDays           = $("#txtEmployeeVacationDays").val();
            var leaveEndvsIntegrationDiff   = $("#txtEndvsIntegrationDiff").val();

            if (!employeeID || employeeID === "0") 
            {
                fnShowNotification("Seleccione el colaborador", "error", timerNotification);
                isValidForm = false;
                return isValidForm;
            }

            if(totalVacationDays < 1)
            {
                fnShowNotification("La Fecha Final del Permiso Está Mal Configurada", "error", timerNotification);
                isValidForm = false;
                return isValidForm;
            }

            if(leaveEndvsIntegrationDiff < 1)
            {
                fnShowNotification("La Fecha de Integración del Colaborador Está Mal Configurada", "error", timerNotification);
                isValidForm = false;
                return isValidForm;
            }

            $('#txtTransactionOn').val(moment().format("YYYY-MM-DD"));
            
            return isValidForm;
        }

        function onCompleteNewEmployee(objResponse) {
            console.info("CALL onCompleteNewEmployee");

            selectedEmployeeData = objResponse;

            EmployeeEntityID            = objResponse[0][2];
            EmployeeCode                = objResponse[0][3];
            EmployeeFullName            = objResponse[0][4];
            EmployeeArea                = objResponse[0][6];

            //Llenado de campos obtenidos desde la base de datos.
            $("#txtEmployeeEntityID").val(EmployeeEntityID);
            $("#txtEmployeeCode").val(EmployeeCode);
            $("#txtEmployeeFullName").val(EmployeeFullName);
            $("#txtEmployeeDescription").val(EmployeeCode + " | " + EmployeeFullName);
            $("#txtEmployeeArea").val(EmployeeArea);

            //Inicializar inputs en cero.
            $("#txtEmployeeVacationDays").val((0).toFixed(2));
            $("#txtEmployeeLeaveStartDate").val(moment().format("YYYY-MM-DD"));
            $("#txtEmployeeLeaveEndDate").val(moment().format("YYYY-MM-DD"));
            $("#txtEmployeeLeaveIntegrationDate").val(moment().format("YYYY-MM-DD"));

            onUpdatedEmployeeData(selectedEmployeeData);
            refreschChecked();
        }

        //Calcular campos de datos dinamicos.
        function onUpdatedEmployeeData(selectedEmployeeData)
        {
            console.info("CALL onUpdatedEmployeeData");            
            
            //Obtener las fechas
            let EmployeeLeaveStart          = $("#txtEmployeeLeaveStartDate").val();
            let EmployeeLeaveEnd            = $("#txtEmployeeLeaveEndDate").val();
            let EmployeeLeaveIntegration    = $("#txtEmployeeLeaveIntegrationDate").val();

            let totalVacationDays           = fnCountDiscreteDays(EmployeeLeaveStart, EmployeeLeaveEnd);
            
            let leaveEndvsIntegrationDiff   = fnCountDiscreteDays(EmployeeLeaveEnd, EmployeeLeaveIntegration);
            
            $("#txtEndvsIntegrationDiff").val(leaveEndvsIntegrationDiff);
            $("#txtEmployeeVacationDays").val(totalVacationDays.toFixed(2));
            refreschChecked();
        }

        function fnCountDiscreteDays(startDate, endDate) {
            const start     = new Date(startDate);
            const end       = new Date(endDate);
            let dayCount    = 0;
            let currentDate = new Date(start);

            if (start > end) {
                return dayCount;
            }

            while (currentDate <= end) {
                dayCount++;
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dayCount;
        }


        function refreschChecked()
        {
            plaRefreshChecked();
        }



    });
</script> 