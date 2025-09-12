<?php

use App\Models\Core\Bd_Model;

?>
<html lang="es">
    <head>
        <title>Reportes posMe</title>
        <link rel="icon" type="image/x-icon" href="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/img/favicon.ico"/>
        <link rel="stylesheet" href="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/css/font-awesome-6-5-1/all.min.css">
        <link href="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/css/bootstrap5/bootstrap.min.css" rel="stylesheet">
        <!-- Tempus Dominus Styles -->
        <link rel="stylesheet" href="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/css/tempus-dominus/tempus-dominus.min.css">
    </head>
    <body class="p-3" style="background-color: #006E98">
        <p>&nbsp;</p>
        <div class="container">
            <div class="row">
                <form id="frm-reporting" method="post" class="col needs-validation" novalidate action="<?= base_url().$reporting->urlReportProcess?>" target="_blank">
                    <div class="mb-3 card">
                        <div class="card-header" style="background-color: #00B772">
                            <h3 class="text-center text-white">
                                <?= $reporting->namei ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" value="<?= $reporting->reportID?>" name="reportID" id="reportID" />
                            <div class="row">
                                <?php
                                if (isset($reportingParameter)) {
                                    $counter = 0; // Contador para controlar cuando abrir nuevas filas
                                    foreach ($reportingParameter as $reportingParameterKey => $reportingParameterValue) {
                                        $name = str_replace("@", "", $reportingParameterValue->name);

                                        // Si es un control oculto, no lo mostramos en las columnas
                                        if (in_array($reportingParameterValue->type, ['userID', 'tocketID', 'companyID','fixed'])) {
                                            echo '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . ($reportingParameterValue->type === 'userID' ? $userID : ($reportingParameterValue->type === 'companyID' ? $companyID : $reportingParameterValue->datasource)) . '">';
                                            continue;
                                        }

                                        // Cada dos controles visibles, abrimos nueva fila
                                        if ($counter % 2 === 0) {
                                            echo '</div><div class="row">'; // Cierra la fila anterior y abre nueva
                                        }

                                        switch ($reportingParameterValue->type) {
                                            case 'datetime':
                                                $id = 'datetimepicker_' . uniqid();
                                                $hora = $reportingParameterValue->datasource;
                                                $fecha = date('Y-m-d') . ' ' . $hora;
                                                ?>
                                                <div class="col-md-6 mb-3">
                                                    <label for="<?= $id ?>" class="form-label"><?= $reportingParameterValue->display ?></label>
                                                    <div class="input-group datetimepicker-container" id="<?= $id ?>" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                        <input type="text" class="form-control" data-td-target="#<?= $id ?>" name="<?= $name ?>" value="<?= $fecha ?>" required/>
                                                        <span class="input-group-text bg-warning" data-td-target="#<?= $id ?>" data-td-toggle="datetimepicker">
                                                            <i class="fa-solid fa-calendar text-white"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <?php
                                                $counter++;
                                                break;
                                            case 'examinated':
                                                ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label"><?= $reportingParameterValue->display ?></label>
                                                    <div class="input-group">
                                                        <input type="hidden" id="<?=$name?>" name="<?=$name?>" value="">
                                                        <input type="text" class="form-control" placeholder="<?= $reportingParameterValue->display ?>" readonly
                                                               aria-label="<?= $reportingParameterValue->display ?>" name="txtDescription" id="txtDescription" required>
                                                        <button class="btn btn-danger" type="button" id="btnClear" data-name="<?= $name?>">
                                                            <i class="fa-solid fa-rotate-left"></i> Clear
                                                        </button>
                                                        <button class="btn btn-primary" type="button" id="btnSearch" data-name="<?= $name?>" data-url="<?= $reportingParameterValue->datasource ?>">
                                                            <i class="fa-solid fa-magnifying-glass"></i> Buscar
                                                        </button>
                                                    </div>
                                                </div>
                                                <?php
                                                $counter++;
                                                break;
                                            case 'combobox':
                                                $data = explode(',',$reportingParameterValue->datasource);
                                                ?>
                                                <div class="col-md-6 mb-3">
                                                    <label for="<?= $name ?>" class="form-label"><?= $reportingParameterValue->display ?></label>
                                                    <select class="form-select" aria-label="Seleccionar" id="<?=$name?>" name="<?=$name?>">
                                                        <?php
                                                        foreach ($data as $k=>$option){
                                                            $selected = $k == 0 ? 'selected' : '';
                                                            echo '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                                $counter++;
                                                break;
                                            case 'comboboxfull':
                                                $bdModel = new Bd_Model();
                                                $result = $bdModel->executeRender($reportingParameterValue->datasource, '');
                                                ?>
                                                <div class="col-md-6 mb-3">
                                                    <label for="<?= $reportingParameterValue->type.$reportingParameterValue->reportParameterID ?>" class="form-label">
                                                        <?= $reportingParameterValue->display ?>
                                                    </label>
                                                    <select class="form-select" aria-label="Seleccionar" id="<?= $name ?>" name="<?= $name ?>">
                                                        <?php
                                                        foreach ($result as $k=>$option){
                                                            $selected = $k == 0 ? 'selected' : '';
                                                            echo '<option value="'.$option['key'].'" '.$selected.'>'.$option['value'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                                $counter++;
                                                break;
                                            case 'text': ?>
                                                <div class="col-md-6 mb-3">
                                                    <label for="<?= $name?>" class="form-label"><?= $reportingParameterValue->display ?></label>
                                                    <input type="text" class="form-control" name="<?= $name?>" id="<?= $name?>" placeholder="<?= $reportingParameterValue->display ?>">
                                                </div>
                                            <?php    break;
                                        }
                                    }
                                }
                                ?>
                                <div class="row-cols-1">
                                    <button type="submit" class="btn btn-outline-success">Generar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </form>
            </div>
        </div>
        <script src="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/js/jquery-3.7.1.min.js"></script>
        <script src="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/js/bootstrap5/bootstrap.bundle.min.js"></script>
        <!-- Popperjs -->
        <script src="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/js/popper.min.js" crossorigin="anonymous"></script>
        <!-- Tempus Dominus JavaScript -->
        <script src="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/js/tempus-dominus/tempus-dominus.min.js" crossorigin="anonymous"></script>
        <script>
            let buttonClick;
            $(function () {
                $('.datetimepicker-container').each(function () {
                    const el = this;
                    const picker = new tempusDominus.TempusDominus(el, {
                        display: {
                            sideBySide  : true,
                            components  : {
                                calendar: true,
                                date    : true,
                                month   : true,
                                year    : true,
                                decades : true,
                                clock   : true,
                                hours   : true,
                                minutes : true,
                                seconds : false
                            }
                        },
                        localization: {
                            locale: 'es',
                            format: 'yyyy-MM-dd HH:mm:ss' // <== Este es el formato MySQL
                        }
                    });
                });

                let form = $('#frm-reporting');
                $("#btnClear").click(function () {
                    let nameInput = $(this).data('name');
                    $("#"+nameInput).val("");
                    $('#txtDescription').val("");
                })

                $("#btnSearch").click(function () {
                    buttonClick     = $(this);
                    let url         = $(this).data('url');
                    let url_request = '<?= base_url()?>/' + url;
                    window.open(url_request, "MsgWindow", "width=900,height=450");
                    window.onCompleteEntity = onCompleteEntity;
                })

                form.on('submit', function (event) {
                    if (!form[0].checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    let valid = true;

                    form.find('.datetimepicker-container').each(function () {
                        let input = $(this).find('input[type="text"]');

                        // Limpiar clases previas
                        input.removeClass('is-valid is-invalid');

                        // Verificar si el campo está vacío
                        if (input.val().trim() === '') {
                            input.addClass('is-invalid');
                            valid = false;
                        } else {
                            input.addClass('is-valid');
                        }
                    });

                    form.find('input[type="hidden"]').each(function () {
                        let val = $(this).val();
                        let related = $(this).closest('.input-group').find('input[type="text"], input[readonly]');

                        // Limpieza de clases previas
                        related.removeClass('is-valid is-invalid');

                        if (val === '' || val === null || val === '0') {
                            valid = false;
                            related.addClass('is-invalid');
                        } else {
                            related.addClass('is-valid');
                        }
                    });

                    if (!valid) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.addClass('was-validated');
                });

            })

            function onCompleteEntity(param) {
                console.log('call onCompleteRequest');
                let nameInput   = $(buttonClick).data('name');
                let entityID    = param[0][1]
                let name        = param[0][3]
                $('#' + nameInput).val(entityID);
                $("#txtDescription").val(name);
            }
        </script>
    </body>
</html>
