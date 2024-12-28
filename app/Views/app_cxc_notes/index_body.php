<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <form id="form-note" method="post">
                <div class="panel panel-default full-height-panel">
                    <div class="panel-heading" style="padding: 15px">
                        <span class="text-primary"><strong>Filtrar por:</strong></span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="txtGradoID">Grado:</label>
                            <select name="txtGradoID" id="txtGradoID" class="select2">
                                <option></option>
                                <?php
                                if (isset($objListGrado)) {
                                    foreach ($objListGrado as $cat) {
                                        echo "<option value='".$cat->catalogItemID."'>".$cat->display."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtAnio">Año:</label>
                            <select name="txtAnio" id="txtAnio" class="select2">
                                <option></option>
                                <?php
                                if (isset($objListAnio)) {
                                    foreach ($objListAnio as $cat) {
                                        echo "<option value='".$cat->catalogItemID."'>".$cat->name."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtMes">Meses:</label>
                            <select name="txtMes" id="txtMes" class="select2" disabled>
                                <option></option>
                                <?php
                                if (isset($objListMeses)) {
                                    foreach ($objListMeses as $cat) {
                                        echo "<option value='".$cat->sequence."'>".$cat->name."</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div id="error" class="text-danger" style="display:none;">Por favor, selecciona un mes válido.</div>
                        </div>
                        <div class="form-group">
                            <label for="txtDia">Días:</label>
                            <input type="hidden" id="txtTransactionOn" name="txtTransactionOn" value="" />
                            <select name="txtDia" id="txtDia" class="select2" disabled>
                                <option></option>
                            </select>
                            <div id="errorDay" class="text-danger" style="display:none;">Por favor, selecciona un día válido.</div>
                        </div>
                        <div class="form-group">
                            <label for="txtAlumnoDescription">Alumno</label>
                            <div class="input-group">
                                <input type="hidden" id="txtAlumnoID" name="txtAlumnoID" value="" />
                                <input class="form-control" readonly id="txtAlumnoDescription" type="text" value="" />
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button" id="btnClearAlumno">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        clear
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchAlumno">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtColaboradorDescription">Colaborador</label>
                            <div class="input-group">
                                <input type="hidden" id="txtColaboradorID" name="txtColaboradorID" value="" />
                                <input class="form-control" readonly id="txtColaboradorDescription" type="text" value="" />
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button" id="btnClearColaborador">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        clear
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchColaborador">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtMateriaDescription">Materia</label>
                            <div class="input-group">
                                <input type="hidden" id="txtMateriaID" name="txtMateriaID" value="" />
                                <input class="form-control" readonly id="txtMateriaDescription" type="text" value="" />
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button" id="btnClearMateria">
                                        <i aria-hidden="true" class="i-undo-2"></i>
                                        clear
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="btnSearchMateria">
                                        <i aria-hidden="true" class="i-search-5"></i>
                                        buscar
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtPriorityID">Calificación Cualitativa:</label>
                            <select name="txtPriorityID" id="txtPriorityID" class="select2">
                                <option></option>
                                <?php
                                if (isset($objListCalificacionCualitativa)) {
                                    foreach ($objListCalificacionCualitativa as $cat) {
                                        ?>
                                <option value="<?= $cat->catalogItemID ?>" data-minimo="<?=$cat->reference1?>"
                                        data-maximo="<?=$cat->reference2?>" data-color="<?= $cat->display?>">
                                    <?=$cat->name?>
                                </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtCalificacionCuantitativa">Calificación Cuantitativa:</label>
                            <div class="input">
                                <input class="form-control" type="text" id="txtCalificacionCuantitativa" name="txtCalificacionCuantitativa" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtReferencia1">Referencia 1:</label>
                            <div class="input">
                                <input class="form-control" type="text" id="txtReferencia1" name="txtReferencia1" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtReferencia2">Referencia 2:</label>
                            <div class="input">
                                <input class="form-control" type="text" id="txtReferencia2" name="txtReferencia2" value="" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading" style="padding: 15px">
                    <span class="text-danger"><strong>Datos</strong></span>
                </div>
                <div class="panel-body">
                    <dv class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="container">
                                    <div class="col-md-3" style="text-align: right">Alumno:</div>
                                    <div class="col-md-9">
                                        <strong><span id="txtShowNombreAlumno">Nombre del Alumno</span></strong>
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="col-md-3" style="text-align: right">Grado:</div>
                                    <div class="col-md-9">
                                        <strong><span id="txtShowGrado">Grado del Alumno</span></strong>
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="col-md-3" style="text-align: right">Año:</div>
                                    <div class="col-md-9">
                                        <strong><span id="txtShowAnio">Seleccionar año</span></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a id="btnMostrar" href="#">Mostrar</a></li>
                                        <li><a id="btnGuardar" href="#">Guardar</a></li>
                                        <li><a id="btnDelete" href="#">Eliminar</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a id="btnImprimir" href="#">Certificado de notas</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </dv>
                </div>
            </div>
            <p>&nbsp;</p>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="data_table">
                            <thead>
                            <tr>
                                <th>Profesor</th>
                                <th>Materia</th>
                                <th>Mes</th>
                                <th>Valor Cualitativo</th>
                                <th>Valor Cuantitativo</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>