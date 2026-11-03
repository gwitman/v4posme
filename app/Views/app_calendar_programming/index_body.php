<div class="pv2 pv2-cal">
    <div class="pv2-card pv2-header pv2-cal-header">
        <div class="pv2-header-info">
            <div class="pv2-header-icon"><i class="fa-solid fa-calendar-days"></i></div>
            <div>
                <h3 class="pv2-header-title">Programación</h3>
                <p class="pv2-header-sub">Eventos, documentos y abonos programados</p>
            </div>
        </div>
    </div>

    <div class="pv2-card pv2-cal-card">
        <div id="calendario" >
        </div>
    </div>
</div>


<!-- Modal para agregar / editar eventos -->
<div class="modal fade pv2-cal-modal" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalTitle">
    <div class="modal-dialog pv2" role="document">
        <div class="modal-content">
            <div class="modal-header pv2-header">
                <div class="pv2-header-info">
                    <div class="pv2-header-icon"><i id="eventModalIcon" class="fa-solid fa-calendar-plus"></i></div>
                    <div>
                        <h4 class="modal-title pv2-header-title"><span id="eventModalTitle">Nueva programación</span> <span id="eventModalChip" class="pv2-chip">Nuevo</span></h4>
                        <p class="pv2-header-sub">Calendario</p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="eventForm" autocomplete="off" onsubmit="return false">
                    <input type="hidden" id="eventId"> <!-- ID oculto para editar -->

                    <div class="pv2-card">
                        <div class="pv2-card-title"><span class="pv2-ico"><i class="fa-solid fa-align-left"></i></span> Detalle</div>
                        <div class="form-group">
                            <label for="eventTitle" class="pv2-label">Título del evento <span class="pv2-req">*</span></label>
                            <input type="text" class="form-control" id="eventTitle" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="eventDescripcion" class="pv2-label">Descripción del evento <span class="pv2-req">*</span></label>
                            <textarea class="form-control" id="eventDescripcion" rows="3" autocomplete="off" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="txtTagID" class="pv2-label">Etiqueta <span class="pv2-req">*</span></label>
                            <select name="txtTagID" id="txtTagID" class="form-control">
                                <option value="0">Seleccione…</option>
                                <?php
                                if (isset($objListTag)) foreach ($objListTag as $ws) {
                                    echo "<option value='" . $ws->tagID . "'>" . $ws->name . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="pv2-card">
                        <div class="pv2-card-title"><span class="pv2-ico"><i class="fa-solid fa-clock"></i></span> Cuándo</div>
                        <div class="row pv2-cal-row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="eventDate" class="pv2-label">Fecha <span class="pv2-req">*</span></label>
                                    <input type="date" class="form-control" id="eventDate" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="eventTime" class="pv2-label">Hora (opcional)</label>
                                    <input type="time" class="form-control" id="eventTime" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pv2-cal-left" id="deleteEvent"><i class="fa-solid fa-trash"></i> Eliminar</button>
                <button type="button" class="btn btn-warning" id="printEvent"><i class="fa-solid fa-print"></i> Imprimir</button>
                <button type="button" class="btn btn-default" id="closeModal" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="save"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal para seleccionar si imprimir o abrir el documento del evento -->
<div class="modal fade pv2-cal-modal" id="eventOpcionModal" tabindex="-1" role="dialog" aria-labelledby="eventOpcionModalTitle">
    <div class="modal-dialog pv2" role="document">
        <div class="modal-content">
            <div class="modal-header pv2-header">
                <div class="pv2-header-info">
                    <div class="pv2-header-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <div>
                        <h4 class="modal-title pv2-header-title"><span id="eventOpcionModalTitle">Programación</span> <span class="pv2-chip">Documento</span></h4>
                        <p class="pv2-header-sub">Calendario</p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="eventPrintId"> <!-- ID oculto para imprimir -->
                <input type="hidden" id="eventUrl">     <!-- URL oculto para redirigir -->
                <div class="pv2-card">
                    <div id="eventOpcionTitle" class="pv2-cal-event-title"></div>
                    <div id="eventOpcionInfo"></div>
                </div>
                <p class="pv2-cal-hint">Seleccione una opción para el evento seleccionado.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pv2-cal-left" id="closeOpcionModal" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" id="printOpcionEvent"><i class="fa-solid fa-print"></i> Imprimir</button>
                <button type="button" class="btn btn-primary" id="redirectEvent"><i class="fa-solid fa-arrow-up-right-from-square"></i> Abrir documento</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
