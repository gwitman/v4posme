<script>
    $(document).ready(function() {

        var selectedEmployeeData = null;

        $('#txtEmployeeEndDate').datepicker({format:"yyyy-mm-dd"});

        //Regresar a la lista
        $("#btnBack").on("click", function() 
        {
            fnWaitOpen();
        });

        //Seleccionar colaborador
        $("#btnSearchEmployee").on("click", function() 
        {
            var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponentEmployee->componentID; ?>/onCompleteNewEmployee/SELECCIONAR_EMPLOYEE_TO_PLANILLA/true/empty/false/not_redirect_when_empty";
            window.open(url_request, "MsgWindow", "width=1585,height=795");
            window.onCompleteNewEmployee = onCompleteNewEmployee;
        });

        //Limpiar datos del colaborador
        $("#btnClearEmployee").on("click", function() 
        {
            $("#form-new-rrhh-employee input").val(""); 
        });

        //Agregar Registro
        $("#btnAcept").on("click", function()
        {
            if(validateForm())
            {
                fnWaitOpen();
                $("#form-new-rrhh-employee")
                .attr("method","POST")
                .attr("action","<?php echo base_url(); ?>/app_planilla_settlement/save/new")
                .submit();
            }
        });

        $("#form-new-rrhh-employee input").on("change",function(){
            onUpdatedEmployeeData(selectedEmployeeData);
        });

        //Validar que se haya ingresado un colaborador
        function validateForm() 
        {
            var isValidForm         = true;
            var timerNotification   = 15000;
            var employeeID          = $("#txtEmployeeEntityID").val();

            if (!employeeID || employeeID === "0") 
            {
                fnShowNotification("Seleccione el colaborador", "error", timerNotification);
                isValidForm = false;
                return isValidForm;
            }
            //Validar inputs
            const inputsNumericos = document.querySelectorAll('.txt-employee-numeric');
            inputsNumericos.forEach(function(input){
                var inputValue          = input.value.trim();
                var inputName           = input.name;
                var inputValueFormatted = inputValue.replace(/,/g,'');

                //NOTA!! el input txtEmployeeSettlement podria ser negativo si el empleado tiene mas deducciones que ingresos.
                if(isNaN(inputValueFormatted) || inputValueFormatted < 0 && inputName != "txtEmployeeSettlement")
                {
                    fnShowNotification("El dato '" + inputValue + "' no es una entrada Valida", "error", timerNotification);
                    isValidForm = false;
                }else if(isValidForm)
                {
                    input.value = inputValueFormatted;
                }
            });
            return isValidForm;
        }

        function onCompleteNewEmployee(objResponse) {
            console.info("CALL onCompleteNewEmployee");

            selectedEmployeeData = objResponse;

            EmployeeEntityID            = objResponse[0][2];
            EmployeeCode                = objResponse[0][3];
            EmployeeFullName            = objResponse[0][4];
            EmployeeDepartment          = objResponse[0][5];
            EmployeeArea                = objResponse[0][6];
            EmployeeSalary              = objResponse[0][7];
            EmployeeVacationBalance     = objResponse[0][8];
            EmployeeAmountSaving        = objResponse[0][9];
            EmployeeStartDate           = objResponse[0][10];
            EmployeeEndDate             = objResponse[0][11];

            //Llenado de campos obtenidos desde la base de datos.
            $("#txtEmployeeEntityID").val(EmployeeEntityID);
            $("#txtEmployeeCode").val(EmployeeCode);
            $("#txtEmployeeFullName").val(EmployeeFullName);
            $("#txtEmployeeSalary").val(EmployeeSalary);
            $("#txtEmployeeBeginDate").val(EmployeeStartDate);
            $("#txtEmployeeAccumulatedVacations").val(EmployeeVacationBalance);
            $("#txtEmployeeAmountSaving").val(EmployeeAmountSaving);
            $("#txtEmployeeEndDate").val(EmployeeEndDate);
            $("#txtEmployeeDescription").val(EmployeeEntityID + " " + EmployeeCode + " | " + EmployeeFullName);
            $("#txtEmployeeArea").val(EmployeeArea);

            $('#txtTransactionOn').val(moment().format("YYYY-MM-DD"));

            //Inicializar inputs en cero.
            $("#txtEmployeeComission").val((0).toFixed(2));
            $("#txtEmployeeBonus").val((0).toFixed(2));
            $("#txtEmployeeLoans").val((0).toFixed(2));
            $("#txtEmployeeLateArrival").val((0).toFixed(2));
            $("#txtEmployeeAdvancedPayment").val((0).toFixed(2));

            onUpdatedEmployeeData(selectedEmployeeData);
            fnFormatInputsToCurrency();
            refreschChecked();
        }

        //Calcular campos de datos dinamicos.
        function onUpdatedEmployeeData(selectedEmployeeData)
        {
            console.info("CALL onUpdatedEmployeeData");
            EmployeeSalary                      = selectedEmployeeData[0][7];
            EmployeeStartDate                   = selectedEmployeeData[0][10];
            EmployeeVacationBalance             = selectedEmployeeData[0][8];
            EmployeeAmountSaving                = selectedEmployeeData[0][9];
            
            EmployeeEndDate                     = $("#txtEmployeeEndDate").val();

            let totalTimeWorked                 = fnCalculateDatesDifference(EmployeeStartDate, EmployeeEndDate);
            totalTimeWorkedInYears              = (totalTimeWorked.years) + (totalTimeWorked.months / 12) + (totalTimeWorked.days / 30 / 12); 
            $("#txtEmployeeWorkYears").val(totalTimeWorkedInYears);

            //Calcular el pago por mes 13.
            let previousYearBegin               = fnCalculateLastYearBegin(EmployeeEndDate);
            let timePassed                      = fnCalculateDatesDifference(previousYearBegin, EmployeeEndDate);
            let totalPagoMesTrece               = fnCalculatePaymentMonthThirteen(EmployeeSalary, timePassed);
            $("#txtEmployeePaymentMonthThirteen").val(totalPagoMesTrece);

            //Calcular el pago por antiguedad.
            totalTimeWorked                     = fnCalculateDatesDifference(EmployeeStartDate, EmployeeEndDate);
            let totalPagoAntiguedad             = fnCalculateSeniorityPayment(EmployeeSalary, totalTimeWorked);
            $("#txtEmployeeSeniorityPayment").val(totalPagoAntiguedad);

            let totalSalarioProporcional        = fnCalculateProporcionalSalary(EmployeeSalary);
            $("#txtEmployeeProporcionalSalary").val(totalSalarioProporcional);
            
            let totalPagoVacacionesAcumuladas   = fnCalculateAccumulatedVacationPayment(EmployeeSalary, EmployeeVacationBalance);
            $("#txtEmployeeAccumulatedVacationsPayment").val(totalPagoVacacionesAcumuladas);

            let totalINSS                       = fnCalculateProporcionalSalary(EmployeeSalary) * 0.07;
            $("#txtEmployeeINSS").val(totalINSS);
            
            let totalIR                         = fnCalculateIR(EmployeeSalary - (EmployeeSalary * 0.07));
            $("#txtEmployeeIR").val(totalIR);

            let totalIRVacaciones               = fnCalculateIR(totalPagoVacacionesAcumuladas);
            $("#txtEmployeeIRVacation").val(totalIRVacaciones);
            
            //Llenado de datos para calculo de Ingresos
            let totalAhorroAcumulado            = EmployeeAmountSaving;
            let totalComision                   = parseFloat($("#txtEmployeeComission").val().replace(/,/g,'')) || 0;
            let totalBono                       = parseFloat($("#txtEmployeeBonus").val().replace(/,/g,'')) || 0;
           
            //Llenado de datos para calculo de Deducciones
            let totalPrestamos                  = parseFloat($("#txtEmployeeLoans").val().replace(/,/g,'')) || 0;
            let totalLLegadasTarde              = parseFloat($("#txtEmployeeLateArrival").val().replace(/,/g,'')) || 0;
            let totalAdelantos                  = parseFloat($("#txtEmployeeAdvancedPayment").val().replace(/,/g,'')) || 0;
            
            
            //Calculo total de Ingresos
            totalIngresoFinal   = parseFloat(totalSalarioProporcional) + parseFloat(totalAhorroAcumulado) + totalComision + totalBono + parseFloat(totalPagoVacacionesAcumuladas) + parseFloat(totalPagoMesTrece) + parseFloat(totalPagoAntiguedad);
            
            //Calculo total de Deducciones
            totalDeduccionFianl = parseFloat(totalINSS) + parseFloat(totalIR) + parseFloat(totalIRVacaciones) + totalPrestamos + totalLLegadasTarde + totalAdelantos;
            
            //Liquidacion Neta
            $("#txtEmployeeSettlement").val((totalIngresoFinal - totalDeduccionFianl));
            fnFormatInputsToCurrency();
            refreschChecked();
        }
        
        function fnCalculateDatesDifference(EmployeeStartDate, EmployeeEndDate) 
        { 
            const startDate     = new Date(EmployeeStartDate);
            const endDate       = isNaN(Date.parse(EmployeeEndDate)) ? new Date() : new Date(EmployeeEndDate);

            yearsDifference     = endDate.getFullYear() - startDate.getFullYear();
            monthsDifference    = endDate.getMonth() - startDate.getMonth();
            daysDifference      = endDate.getDate() - startDate.getDate();

            if (monthsDifference < 0) {
                yearsDifference--;
                monthsDifference += 12;
            }

            if (daysDifference < 0) {
                if (monthsDifference == 0) {
                    yearsDifference--;
                    monthsDifference = 11;
                } else {
                    monthsDifference--;
                }
                previousMonth   = new Date(endDate.getFullYear(), endDate.getMonth(), 0).getDate();
                daysDifference  += previousMonth;
            }

            let dateDifference = 
            {
                years:  yearsDifference,
                months: monthsDifference,
                days:   daysDifference
            };

            return dateDifference;
        }

        function fnCalculateProporcionalSalary(baseSalary) 
        {
            proporcionalSalary  = baseSalary / 30;
            currentDate         = new Date().getDate();
            proporcionalSalary  = proporcionalSalary * currentDate;
            return proporcionalSalary;
        }

        function fnCalculateAccumulatedVacationPayment(baseSalary, vacationBalance) 
        {
            proporcionalSalary      = baseSalary / 30;
            vacationBalancePayment  = proporcionalSalary * vacationBalance;
            return vacationBalancePayment;
        }

        function fnCalculatePaymentMonthThirteen(baseSalary, timeWorked) 
        {
            let yearsWorked     = timeWorked.years;
            let monthsWorked    = timeWorked.months;
            let daysWorked      = timeWorked.days;
            
            let salaryMonthly   = baseSalary / 12;
            let salaryDairy     = baseSalary / 12 / 30;
            let totalpayment    = 0;

            
            if(yearsWorked >= 1)
            {
                totalpayment = baseSalary;
            }
            else
            {
                //Formula: Salario Diaro * Dias Laborados * Factor 0.083
                totalpayment = (salaryDairy * daysWorked) + (monthsWorked * salaryMonthly);
            }
            debugger;
            return totalpayment;
        }

        function fnCalculateSeniorityPayment(baseSalary, timeWorked) {
            const salaryDaily = baseSalary / 30;
            let totalDaysWorked = 0;

            //Calculo de dias trabajados en los primeros tres años (maximo 3 * 30 dias)
            const daysFirstThreeYears = Math.min(timeWorked.years, 3) * 30;

            //Calculo de dias trabajados despues del tercer año (20 dias por año adicional)
            const daysAfterThreeYears = timeWorked.years > 3 ? (timeWorked.years - 3) * 20 : 0;

            //Calculo del factor de dias adicionales segun los meses trabajados
            const daysFactor = timeWorked.years >= 3 ? (20 / 12) * timeWorked.months : (30 / 12) * timeWorked.months;

            //Suma total de dias trabajados y limite de 150 dias (5 Meses)
            totalDaysWorked = Math.min(daysFirstThreeYears + daysAfterThreeYears + daysFactor, 150);

            //Pago total calculado
            const totalPayment = totalDaysWorked * salaryDaily;

            return totalPayment;
        }

        function fnCalculateIR(salaryAfterINSS) 
        {
            $anualSalary = (salaryAfterINSS * 12) || 0;

            $overExcess             = 0;
            $applicablePercentage   = 0;
            $baseTax                = 0;

            $withOutOverExcess          = 0;
            $withAplicablePercentage    = 0;
            $withBaseTax                = 0;
            $monthlyIR                  = 0;

            /* 
            											TARIFA PROGRESIVA
            	---------------------------------------------------------------------------------------------------
            	ESTRATOS DE RENTA			|	IMPUESTO BASE	|	PORCENTAJE APLICABLE	|	SOBRE EXCESO DE C$
            	DE C$	HASTA C$			|		C$			|			%				|			C$
            	---------------------------------------------------------------------------------------------------
            	0.01 HASTA 100,000.00		|		0.00		|			0.0%			|			0.00
            	---------------------------------------------------------------------------------------------------
            	100,000.1  HASTA 200,000.00	|		0.00		|			15.0%			|		100,000.00
            	---------------------------------------------------------------------------------------------------
            	200,000.1  HASTA 350,000.00	|		15,000.00	|			20.0%			|		200,000.00
            	---------------------------------------------------------------------------------------------------
            	350,000.1  HASTA 500,000.00	|		45,000.00	|			25.0%			|		350,000.00
            	---------------------------------------------------------------------------------------------------
            	500,000.1 A MAS				|		82,500.00	|			30.0%			|		500,000.00
            	---------------------------------------------------------------------------------------------------
            */

            if ($anualSalary >= 0 && $anualSalary <= 100000) 
            {
                $overExcess                 = 0;
                $applicablePercentage       = 0;
                $baseTax                    = 0;

                $withOutOverExcess          = ($anualSalary - $overExcess) || 0;
                $withAplicablePercentage    = ($withOutOverExcess * $applicablePercentage) || 0;
                $withBaseTax                = ($withAplicablePercentage + $baseTax) || 0;
                $monthlyIR                  = ($withBaseTax / 12) || 0;
            } 
            else if ($anualSalary > 100000 && $anualSalary <= 200000) 
            {
                $overExcess                 = 100000;
                $applicablePercentage       = 0.15;
                $baseTax                    = 0;

                $withOutOverExcess          = ($anualSalary - $overExcess) || 0;
                $withAplicablePercentage    = ($withOutOverExcess * $applicablePercentage) || 0;
                $withBaseTax                = ($withAplicablePercentage + $baseTax) || 0;
                $monthlyIR                  = ($withBaseTax / 12) || 0;
            } 
            else if ($anualSalary > 200000 && $anualSalary <= 350000) 
            {
                $overExcess                 = 200000;
                $applicablePercentage       = 0.20;
                $baseTax                    = 15000;

                $withOutOverExcess          = ($anualSalary - $overExcess) || 0;
                $withAplicablePercentage    = ($withOutOverExcess * $applicablePercentage) || 0;
                $withBaseTax                = ($withAplicablePercentage + $baseTax) || 0;
                $monthlyIR                  = ($withBaseTax / 12) || 0;
            } 
            else if ($anualSalary > 350000 && $anualSalary <= 500000) 
            {
                $overExcess                 = 350000;
                $applicablePercentage       = 0.25;
                $baseTax                    = 45000;

                $withOutOverExcess          = ($anualSalary - $overExcess) || 0;
                $withAplicablePercentage    = ($withOutOverExcess * $applicablePercentage) || 0;
                $withBaseTax                = ($withAplicablePercentage + $baseTax) || 0;
                $monthlyIR                  = ($withBaseTax / 12) || 0;
            } 
            else if ($anualSalary > 500000) 
            {
                $overExcess                 = 500000;
                $applicablePercentage       = 0.30;
                $baseTax                    = 82500;

                $withOutOverExcess          = ($anualSalary - $overExcess) || 0;
                $withAplicablePercentage    = ($withOutOverExcess * $applicablePercentage) || 0;
                $withBaseTax                = ($withAplicablePercentage + $baseTax) || 0;
                $monthlyIR                  = ($withBaseTax / 12) || 0;
            }

            return $monthlyIR;
        }

        function fnCalculateLastYearBegin(EmployeeEndDate)
        {
            const endDate       = isNaN(Date.parse(EmployeeEndDate)) ? new Date() : new Date(EmployeeEndDate);
            let endDateYear     = endDate.getFullYear();
            let endDateMonth    = endDate.getMonth() + 1;    

            if(endDateMonth < 12)
            {
                endDateYear--;
            }

            let previousYearBegin   = endDateYear + "-11-30"; //30 de noviembre del año anterior al actual
            return previousYearBegin;
        }   

        function fnFormatInputsToCurrency() 
        {
            console.info("CALL fnFormatInputsToCurrency");
            const inputsNumericos   = document.querySelectorAll('.txt-employee-numeric');

            inputsNumericos.forEach(input => {
                var inputValue              = input.value.trim();
                var inputValueFormatted     = inputValue.replace(/,/g,'');
                inputValue = Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(inputValueFormatted);

                if(!isNaN(inputValueFormatted))
                {
                    input.value = inputValue;
                }
            });
        }

        function refreschChecked()
        {
            $("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();	
        }



    });
</script> 