<?php echo view('core_template/posme_v2_style'); ?>
<?php echo view('core_template/posme_v2_common'); ?>
<style>
/* --- Contenedor Principal para evitar conflictos de estilos --- */
.body_custom {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 20px;
    box-sizing: border-box; /* Asegura que el padding no afecte el ancho total */
}

.body_custom .kanban-title { flex: 0 0 auto; }
.body_custom .kanban-filter { flex: 1 1 220px; max-width: 420px; }

/* --- Tablero Kanban (Columnas y Tareas) --- */
.body_custom #kanban-board {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    overflow-y: auto;
    padding: 0;
    height: 70vh;
    min-height: 360px;
}

.body_custom .column {
    flex: 0 0 300px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.body_custom .column h2 {
    margin: 0 0 10px 0;
    font-size: 1.2em;
    color: #555;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.body_custom .task-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 50px;
}

.body_custom .task {
    background-color: #fafafa;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    cursor: grab;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    position: relative;
}

.body_custom .task:hover {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.body_custom .task.dragging {
    opacity: 0.5;
    transform: scale(1.02);
}

.body_custom .task h3 {
    margin: 0 0 5px 0;
    font-size: 1em;
    color: #333;
    word-break: break-word;
}

.body_custom .task p {
    margin: 0 0 10px 0;
    font-size: 0.9em;
    color: #666;
    word-break: break-word;
}

.body_custom .task .meta {
    font-size: 0.8em;
    color: #555;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
}

.body_custom .task .meta span {
    background-color: #e9ecef;
    padding: 3px 8px;
    border-radius: 3px;
}

.body_custom .task .priority {
    font-weight: bold;
}

/* --- Colores para Prioridad --- */
.body_custom .task .meta .priority-Baja { background-color: #d4edda; color: #155724; }
.body_custom .task .meta .priority-Media { background-color: #fff3cd; color: #856404; }
.body_custom .task .meta .priority-Alta { background-color: #f8d7da; color: #721c24; }

/* --- Botones de Acción de Tarea --- */
.body_custom .task-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    margin-top: 10px;
}

.body_custom .task-actions button {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    font-size: 0.8em;
    cursor: pointer;
    color: white;
    transition: background-color 0.2s;
}

.body_custom .edit-btn { background-color: #17a2b8; }
.body_custom .move-btn { background-color: #6c757d; }

.body_custom .edit-btn:hover { background-color: #138496; }
.body_custom .move-btn:hover { background-color: #5a6268; }

/* Numero de documento: solo etiqueta, no es un boton */
.body_custom .task-doc {
    font-size: 0.8em;
    color: #555;
    background-color: #e9ecef;
    padding: 5px 10px;
    border-radius: 4px;
}

/* --- Modal (Caja de Diálogo) --- */
.body_custom .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.6);
    padding-top: 50px;
    backdrop-filter: blur(5px);
}

.body_custom .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 30px;
    border: none;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.25);
    position: relative;
    animation-name: animatetop;
    animation-duration: 0.4s;
}

@keyframes animatetop {
    from {top: -300px; opacity: 0}
    to {top: 0; opacity: 1}
}

.body_custom .close-btn {
    color: #888;
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 32px;
    font-weight: bold;
    transition: color 0.2s;
}

.body_custom .close-btn:hover,
.body_custom .close-btn:focus {
    color: #333;
    text-decoration: none;
    cursor: pointer;
}

/* --- Telefono: columnas apiladas, sin scroll horizontal --- */
@media (max-width: 767px) {
    .body_custom { padding: 8px; }
    .body_custom .kanban-filter { max-width: none; flex: 1 1 100%; }
    .body_custom #kanban-board { flex-direction: column; height: auto; min-height: 0; overflow: visible; }
    .body_custom .column { flex: 1 1 auto; width: 100%; }
    .body_custom .task { cursor: default; }
    .body_custom .task-actions button { min-height: 40px; padding: 8px 12px; font-size: 0.9em; }
}
</style>


<div class="pv2 body_custom<?php echo (isset($useMobile) && $useMobile == "1") ? " pv2-mobile" : ""; ?>">
    <div class="pv2-card pv2-listbar">
        <p class="pv2-card-title kanban-title" style="margin:0"><span class="pv2-ico"><i class="icon16 i-grid-2"></i></span> Tablero Kanban</p>
        <div class="kanban-filter">
            <select id="responsible-filter" class="form-control" autocomplete="off" aria-label="Filtrar por Responsable">
                <option value="">Filtrar por Responsable</option>
            </select>
        </div>
        <div class="pv2-actions">
            <button type="button" class="btn btn-primary" id="add-task-btn"><i class="icon16 i-plus"></i> Agregar Tarea</button>
            <button type="button" class="btn btn-default" id="list-task-btn"><i class="icon16 i-table"></i> Lista Tarea</button>
            <button type="button" class="btn btn-default" id="refresh-task-btn"><i class="icon16 i-rotate"></i> Actualizar</button>
        </div>
    </div>

    <div id="modalCargandoDatos" style="display:flex">
        <h3 id="title-modal">ESPERE UN MOMENTO</h3>
        <img class="img-fluid" style="height: 80px;" src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/img/loading.gif" />
    </div>

    <?php
        helper_getHtmlOfModalDialog("ModalCargandoDatos","modalCargandoDatos","fnAceptarModalDialogCargandoDatos",false);
    ?>


    <div id="kanban-board"></div>

</div>
