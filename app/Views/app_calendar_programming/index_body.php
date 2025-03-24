<div id="calendario" >
</div>


<!-- Modal para agregar eventos -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Programar Evento</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="eventId"> <!-- ID oculto para editar -->
                <div class="form-group">
                    <label for="eventTitle" class="form-label">Título del evento</label>
                    <input type="text" class="form-control" id="eventTitle" required>
                </div>
                <div class="form-group">
                    <label for="eventTitle" class="form-label">Descripcion del evento</label>
                    <input type="text" class="form-control" id="eventDescripcion" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="selectFilter">Tag</label>
                    <select name="txtTagID" id="txtTagID" class="">
                        <?php
                        if (isset($objListTag)) foreach ($objListTag as $ws) {
                            echo "<option value='" . $ws->tagID . "' selected >" . $ws->name . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="eventDate" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="eventDate" required>
                </div>
                <div class="form-group">
                    <label for="eventTime" class="form-label">Hora (opcional)</label>
                    <input type="time" class="form-control" id="eventTime">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="printEvent"><i class="fa-solid fa-print"></i> Imprimir</button>
                <button type="button" class="btn btn-primary" id="save">Guardar Evento</button>
                <button type="button" class="btn btn-danger" id="deleteEvent">Eliminar</button>
                <button type="button" class="btn btn-default" id="closeModal" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->