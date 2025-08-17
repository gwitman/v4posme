<style>
/* --- Contenedor Principal para evitar conflictos de estilos --- */
.body_custom {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 20px;
    box-sizing: border-box; /* Asegura que el padding no afecte el ancho total */
}

/* --- Header y Controles --- */
.body_custom header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
    background-color: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.body_custom h1 {
    color: #333;
    margin: 0;
}

.body_custom .controls {
    display: flex;
    gap: 10px;
}

.body_custom #responsible-filter, .body_custom #add-task-btn {
    padding: 10px 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
}

.body_custom #responsible-filter, .body_custom #list-task-btn {
    padding: 10px 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
}

.body_custom #responsible-filter, .body_custom #refresh-task-btn {
    padding: 10px 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
}

.body_custom #add-task-btn {
    background-color: #007bff; /* Color primario moderno */
    color: white;
    border: none;
    font-weight: bold;
}

.body_custom #list-task-btn {
    background-color: #ff5722; /* Color secundario moderno */
    color: white;
    border: none;
    font-weight: bold;
}

.body_custom #refresh-task-btn {
    background-color: #00e676; /* Color tercero moderno */
    color: white;
    border: none;
    font-weight: bold;
}

.body_custom #add-task-btn:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

.body_custom #list-task-btn:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}
.body_custom #refresh-task-btn:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

/* --- Tablero Kanban (Columnas y Tareas) --- */
.body_custom #kanban-board {
    display: 	flex;
    gap: 		20px;
    overflow-x: auto;
    padding: 	0px;
	overflow-y:	auto;
	height:		500px;
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
}

.body_custom .task p {
    margin: 0 0 10px 0;
    font-size: 0.9em;
    color: #666;
}

.body_custom .task .meta {
    font-size: 0.8em;
    color: #999;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
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

/* --- Colores para Prioridad y Responsables --- */
.body_custom .priority-Baja { background-color: #d4edda; color: #155724; }
.body_custom .priority-Media { background-color: #fff3cd; color: #856404; }
.body_custom .priority-Alta { background-color: #f8d7da; color: #721c24; }

.body_custom .responsable-Juan { border-left: 5px solid #28a745; }
.body_custom .responsable-Maria { border-left: 5px solid #007bff; }
.body_custom .responsable-Pedro { border-left: 5px solid #ffc107; }

/* --- Botones de Acción de Tarea --- */
.body_custom .task-actions {
    display: flex;
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
.body_custom .delete-btn { background-color: #dc3545; }
.body_custom .status-btn { background-color: #6c757d; }

.body_custom .edit-btn:hover { background-color: #138496; }
.body_custom .delete-btn:hover { background-color: #c82333; }
.body_custom .status-btn:hover { background-color: #5a6268; }

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

.body_custom #modal-title {
    color: #333;
    margin-top: 0;
    margin-bottom: 25px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.body_custom #task-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.body_custom #task-form .full-width {
    grid-column: span 2;
}

.body_custom #task-form label {
    font-weight: 600;
    color: #555;
    display: block;
    margin-bottom: 5px;
}

.body_custom #task-form input,
.body_custom #task-form textarea,
.body_custom #task-form select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 16px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.body_custom #task-form input:focus,
.body_custom #task-form textarea:focus,
.body_custom #task-form select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    outline: none;
}

.body_custom #task-form textarea {
    height: 100px;
    resize: vertical;
}

/* --- Botón Guardar Tarea --- */
.body_custom #save-task-btn {
    grid-column: span 2;
    background-color: #007bff;
    color: white;
    padding: 15px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    margin-top: 15px;
    transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.body_custom #save-task-btn:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.body_custom #save-task-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>


<div class="body_custom">
	<header>
		<table style="width:100%">
			<tr>
				<td style="width: 20%;">
					<h3>Tablero Kanban</h3>
				</td>
				<td style="width: 50%;">
					<select id="responsible-filter">
						<option value="">Filtrar por Responsable</option>
					</select>
				</td>
				<td style="text-align:right;">
					<button id="add-task-btn">Agregar Tarea</button>
				</td>
				<td style="text-align:right;">
					<button id="list-task-btn">Lista Tarea</button>
				</td>
				<td style="text-align:right;">
					<button id="refresh-task-btn">Refresh</button>
				</td>
			</tr>
		</table>
		
	</header>
	
	<div id="modalCargandoDatos" style="display:flex">
		<h3 id="title-modal">ESPERE UN MOMENTO<h3/>
		<img class="img-fluid" style="height: 80px;" src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/img/loading.gif" />
	</div>
	
	<?php
		helper_getHtmlOfModalDialog("ModalCargandoDatos","modalCargandoDatos","fnAceptarModalDialogCargandoDatos",false);
	?>
	
	
	<div id="kanban-board"></div>

	

</div>
