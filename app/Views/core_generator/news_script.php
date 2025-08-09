<script>
    $(document).ready(function() {
        let $form = $("#form-generator"); // Corregido: $fom -> $form

        $form.on('submit', function(e) {
            e.preventDefault(); // Prevenir envío normal

            if (fnValidateForm()) {
                fnWaitOpen(); // Mostrar carga/espera

                // Configurar método y acción (mejor hacerlo en el HTML directamente)
                $form.attr({
                    "method": "POST",
                    "action": "<?= base_url('core_generator/save') ?>"
                });

                // Enviar formulario
                this.submit(); // Mejor que $form.submit() para evitar recursión
            }
        });
    });

    function fnValidateForm() {
        let result = true;
        let controllerName = $("#txtControllerName").val().trim();
        let title = $("#txtTitle").val().trim();

        // Limpiar notificaciones previas
        $(".error-notification").remove();

        // Validar nombre del controlador
        if (controllerName === "") {
            fnShowNotification("Debe establecer un nombre para el controlador", "error", 1500);
            $("#txtControllerName").after('<div class="error-notification text-danger">*Nombre del controlador requerido</div>');
            result = false;
        }
        // Validar formato (solo letras y guiones bajos)
        else if (!/^[a-zA-Z_]+$/.test(controllerName)) {
            fnShowNotification("El nombre solo puede contener letras y guiones bajos", "error", 1500);
            result = false;
        }
        // Validar longitud mínima
        else if (controllerName.length < 3) {
            fnShowNotification("El nombre debe tener al menos 3 caracteres", "error", 1500);
            result = false;
        }

        if (title === "") {
            fnShowNotification("Debe indicar un titulo del formulario", "error", 1500);
            $("#txtControllerName").after('<div class="error-notification text-danger">*Titulo es requerido</div>');
            result = false;
        }

        return result;
    }
</script>