<div class="pv2 pnotes pv2-has-mbar<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">

    <!-- encabezado y acciones -->
    <div class="pv2-card pv2-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="icon20 i-storage-2"></i></div>
            <div>
                <p class="pv2-header-title">Notas</p>
                <p class="pv2-header-sub">Registra y consulta las calificaciones de los alumnos</p>
            </div>
        </div>
        <div class="pv2-actions">
            <a href="#" class="btn btn-default" id="btnImprimir"><i class="icon16 i-print"></i> Certificado</a>
            <a href="#" class="btn btn-default" id="btnMostrar"><i class="icon16 i-search-2"></i> Mostrar</a>
            <a href="#" class="btn btn-danger" id="btnDelete"><i class="icon16 i-remove"></i> Eliminar</a>
            <a href="#" class="btn btn-primary pv2-hide-xs" id="btnGuardar"><i class="icon16 i-checkmark-4"></i> Guardar</a>
        </div>
    </div>
    <!-- /encabezado y acciones -->

    <div class="row">
        <div class="col-xs-12 col-md-4">
            <form id="form-note" method="post" autocomplete="off">
                <div class="pv2-card">
                    <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-search-2"></i></span> Filtrar por</p>

                    <div class="form-group">
                        <label class="pv2-label" for="txtGradoID">Grado</label>
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
                        <label class="pv2-label" for="txtAnio">Año</label>
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
                        <label class="pv2-label" for="txtMes">Meses</label>
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
                        <label class="pv2-label" for="txtDia">Días</label>
                        <input type="hidden" id="txtTransactionOn" name="txtTransactionOn" value="" />
                        <select name="txtDia" id="txtDia" class="select2" disabled>
                            <option></option>
                        </select>
                        <div id="errorDay" class="text-danger" style="display:none;">Por favor, selecciona un día válido.</div>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtAlumnoDescription">Alumno</label>
                        <div class="input-group afx-search">
                            <input type="hidden" id="txtAlumnoID" name="txtAlumnoID" value="" />
                            <input class="form-control" readonly id="txtAlumnoDescription" type="text" autocomplete="off" value="" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="btnClearAlumno">
                                    <i aria-hidden="true" class="i-undo-2"></i>
                                    Limpiar
                                </button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="btnSearchAlumno">
                                    <i aria-hidden="true" class="i-search-5"></i>
                                    Buscar
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtColaboradorDescription">Colaborador</label>
                        <div class="input-group afx-search">
                            <input type="hidden" id="txtColaboradorID" name="txtColaboradorID" value="" />
                            <input class="form-control" readonly id="txtColaboradorDescription" type="text" autocomplete="off" value="" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="btnClearColaborador">
                                    <i aria-hidden="true" class="i-undo-2"></i>
                                    Limpiar
                                </button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="btnSearchColaborador">
                                    <i aria-hidden="true" class="i-search-5"></i>
                                    Buscar
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtMateriaDescription">Materia</label>
                        <div class="input-group afx-search">
                            <input type="hidden" id="txtMateriaID" name="txtMateriaID" value="" />
                            <input class="form-control" readonly id="txtMateriaDescription" type="text" autocomplete="off" value="" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="btnClearMateria">
                                    <i aria-hidden="true" class="i-undo-2"></i>
                                    Limpiar
                                </button>
                            </span>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button" id="btnSearchMateria">
                                    <i aria-hidden="true" class="i-search-5"></i>
                                    Buscar
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtPriorityID">Calificación Cualitativa</label>
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
                        <label class="pv2-label" for="txtCalificacionCuantitativa">Calificación Cuantitativa</label>
                        <input class="form-control pv2-num" type="text" inputmode="decimal" autocomplete="off" id="txtCalificacionCuantitativa" name="txtCalificacionCuantitativa" value="" />
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtReferencia1">Referencia 1</label>
                        <input class="form-control" type="text" autocomplete="off" id="txtReferencia1" name="txtReferencia1" value="" />
                    </div>
                    <div class="form-group">
                        <label class="pv2-label" for="txtReferencia2">Referencia 2</label>
                        <input class="form-control" type="text" autocomplete="off" id="txtReferencia2" name="txtReferencia2" value="" />
                    </div>
                </div>
            </form>
        </div>

        <div class="col-xs-12 col-md-8">
            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-user"></i></span> Datos</p>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 pnotes-dato">
                        <span class="pv2-label">Alumno</span>
                        <strong><span id="txtShowNombreAlumno">Nombre del Alumno</span></strong>
                    </div>
                    <div class="col-xs-12 col-sm-4 pnotes-dato">
                        <span class="pv2-label">Grado</span>
                        <strong><span id="txtShowGrado">Grado del Alumno</span></strong>
                    </div>
                    <div class="col-xs-12 col-sm-4 pnotes-dato">
                        <span class="pv2-label">Año</span>
                        <strong><span id="txtShowAnio">Seleccionar año</span></strong>
                    </div>
                </div>
            </div>

            <div class="pv2-card">
                <p class="pv2-card-title"><span class="pv2-ico"><i class="icon16 i-list-2"></i></span> Calificaciones</p>
                <div class="pv2-table-wrap">
                    <table class="pv2-table" id="data_table">
                        <thead>
                            <tr>
                                <th style="width:20%">Colaborador</th>
                                <th style="width:20%">Materia</th>
                                <th style="width:20%">Mes</th>
                                <th style="width:20%">Cualitativo</th>
                                <th style="width:20%">Cuantitativo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="pv2-empty" id="notesEmpty">Sin calificaciones para mostrar. Selecciona los filtros y pulsa Mostrar.</div>
            </div>
        </div>
    </div>

    <!-- barra inferior (telefono): esta pantalla guarda con btnGuardar -->
    <div class="pv2-mbar">
        <div class="pv2-mbar-info">
            <p class="pv2-mbar-label">Calificación del alumno</p>
        </div>
        <a href="#" class="btn btn-primary" id="btnGuardarMobile"><i class="icon16 i-checkmark-4"></i> Guardar</a>
    </div>
</div>
