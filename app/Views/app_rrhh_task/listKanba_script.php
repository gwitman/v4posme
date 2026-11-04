<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    mostrarModal('ModalCargandoDatos');

    document.addEventListener('DOMContentLoaded', () => {
        const kanbanBoard       = document.getElementById('kanban-board');
        const responsibleFilter = document.getElementById('responsible-filter');
        const addTaskBtn        = document.getElementById('add-task-btn');
        const listTaskBtn       = document.getElementById('list-task-btn');
        const refreshTaskBtn    = document.getElementById('refresh-task-btn');

        let tasks          = [];
        let draggedTask    = null;
        let draggedOrigin  = null;

        //Sin arrastre en pantallas tactiles: alli se ofrecen Subir y Bajar
        const usarBotonesMover = pv2IsMobile() || (navigator.maxTouchPoints || 0) > 0;

        // Funciones de la API
        const api = {
            async fetchTasks() {
                try {
                    const response = await fetch('<?php echo base_url(); ?>/app_transaction_master_api/getAll_TransactionMaster_Task/2');
                    return await response.json();
                } catch (error) {
                    console.error('Error fetching tasks:', error);
                    return { error: true, message: 'No se pudieron cargar las tareas.', data: [] };
                }
            },
            async reorderTasks(task) {
                try {
                    let transactionMasterIDs = task.map(item => item.txtTransactionMasterID);
                    let reference3           = task.map(item => item.txtReference3);

                    const formData = new URLSearchParams({
                        "txtTransactionMasterIDs": transactionMasterIDs,
                        "txtReference3s": reference3
                    });

                    const response = await fetch('<?php echo base_url(); ?>/app_transaction_master_api/updateOrden_TransactionMaster_Task/save/edit/transactionMasterID/0', {
                        method  : 'POST',
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body    : formData.toString(),
                    });
                    return await response.json();
                } catch (error) {
                    console.error('Error reordering tasks:', error);
                    return { error: true, message: 'No se pudo guardar el orden.' };
                }
            },
        };

        //Muestra un mensaje dentro del tablero: el texto no se interpreta como HTML
        function mostrarMensajeTablero(mensaje) {
            kanbanBoard.innerHTML = '';
            const aviso = document.createElement('div');
            aviso.className = 'pv2-empty';
            aviso.style.width = '100%';
            aviso.textContent = mensaje;
            kanbanBoard.appendChild(aviso);
        }

        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = (value === null || value === undefined) ? '' : value;
            return div.innerHTML.replace(/"/g, '&quot;');
        }

        // Renderizar el tablero
        async function renderBoard(filteredResponsible = null) {
            try {
                const allTasks = await api.fetchTasks();

                //Error del servidor o respuesta inesperada
                if (!allTasks || allTasks.error === true || !Array.isArray(allTasks.data)) {
                    const mensaje = (allTasks && allTasks.message) ? allTasks.message : 'No se pudieron cargar las tareas.';
                    mostrarMensajeTablero(mensaje);
                    fnShowNotification(mensaje, 'error');
                    return;
                }

                tasks = allTasks.data.filter(task => !filteredResponsible || task.responsable === filteredResponsible);

                const uniqueResponsibles = [...new Set(allTasks.data.map(task => task.responsable))];
                responsibleFilter.innerHTML = `<option value="">Filtrar por Responsable</option>`;
                uniqueResponsibles.forEach(resp => {
                    const option = document.createElement('option');
                    option.value = resp;
                    option.textContent = resp;
                    if (resp === filteredResponsible) {
                        option.selected = true;
                    }
                    responsibleFilter.appendChild(option);
                });

                //Sin datos
                if (tasks.length === 0) {
                    mostrarMensajeTablero(filteredResponsible ? `No hay tareas asignadas a ${filteredResponsible}.` : 'No hay tareas pendientes.');
                    return;
                }

                kanbanBoard.innerHTML = '';
                const responsiblesToRender = filteredResponsible ? [filteredResponsible] : uniqueResponsibles;

                responsiblesToRender.forEach(responsible => {
                    const column = document.createElement('div');
                    column.classList.add('column');
                    column.setAttribute('data-responsible', responsible);

                    const columnTitle = document.createElement('h2');
                    columnTitle.textContent = responsible;
                    column.appendChild(columnTitle);

                    const taskList = document.createElement('div');
                    taskList.classList.add('task-list');

                    const responsibleTasks = tasks
                        .filter(task => task.responsable === responsible)
                        .sort((a, b) => (parseInt(a.reference3, 10) || 0) - (parseInt(b.reference3, 10) || 0));

                    responsibleTasks.forEach(task => {
                        taskList.appendChild(createTaskElement(task));
                    });

                    column.appendChild(taskList);
                    kanbanBoard.appendChild(column);
                });
                addDragAndDropListeners();
            } catch (error) {
                console.error('Error rendering board:', error);
                mostrarMensajeTablero('No se pudo mostrar el tablero.');
                fnShowNotification('No se pudo mostrar el tablero.', 'error');
            } finally {
                cerrarModal('ModalCargandoDatos');
            }
        }

        function createTaskElement(task) {
            const taskElement = document.createElement('div');
            taskElement.classList.add('task');
            taskElement.setAttribute('data-id', task.transactionMasterID);
            if (!usarBotonesMover) {
                taskElement.setAttribute('draggable', true);
            }

            //La clase de color solo admite el nombre del catalogo limpio
            const priorityClass = 'priority-' + String(task.priorityName || '').replace(/[^A-Za-z0-9_-]/g, '');
            const priorityHtml  = task.priorityName ? `<span class="priority ${priorityClass}">Prioridad: ${escapeHtml(task.priorityName)}</span>` : '';
            const moveHtml      = usarBotonesMover ? `<button type="button" class="move-btn up-btn">Subir</button><button type="button" class="move-btn down-btn">Bajar</button>` : '';

            taskElement.innerHTML = `
                <h3>${escapeHtml(task.reference4)}</h3>
                <p>${escapeHtml(task.descriptionReference)}</p>
                <div class="meta">
                    ${priorityHtml}
                    <span>Entrega: ${escapeHtml(task.nextVisit)}</span>
                    <span>Estado: ${escapeHtml(task.statusName)}</span>
                    <span>Orden: ${escapeHtml(task.reference3)}</span>
                </div>
                <div class="task-actions">
                    <button type="button" class="edit-btn">Editar</button>
                    ${moveHtml}
                    <span class="task-doc">${escapeHtml(task.transactionNumber)}</span>
                </div>
            `;

            taskElement.querySelector('.edit-btn').addEventListener('click', (e,t) => {
                e.stopPropagation();
                window.open("<?php echo base_url(); ?>/app_rrhh_task/edit/companyID/"+task.companyID+"/transactionID/"+task.transactionID+"/transactionMasterID/"+task.transactionMasterID, "_blank");
            });

            if (usarBotonesMover) {
                taskElement.querySelector('.up-btn').addEventListener('click', () => moverTarea(taskElement, -1));
                taskElement.querySelector('.down-btn').addEventListener('click', () => moverTarea(taskElement, 1));
            }

            return taskElement;
        }

        //Guarda el orden de una columna y vuelve a pintar el tablero
        async function guardarOrden(list) {
            const reorderedTasks = Array.from(list.children).map((item, index) => ({
                txtTransactionMasterID  : item.getAttribute('data-id'),
                txtReference3           : index + 1
            }));

            mostrarModal('ModalCargandoDatos');
            const resultado = await api.reorderTasks(reorderedTasks);
            if (!resultado || resultado.error === true) {
                fnShowNotification((resultado && resultado.message) ? resultado.message : 'No se pudo guardar el orden.', 'error');
            }
            renderBoard(responsibleFilter.value || null);
        }

        //Botones Subir y Bajar: mueven la tarea dentro de su columna
        async function moverTarea(taskElement, direccion) {
            const list    = taskElement.closest('.task-list');
            const vecina  = direccion < 0 ? taskElement.previousElementSibling : taskElement.nextElementSibling;
            if (!vecina) {
                return;
            }

            if (direccion < 0) {
                list.insertBefore(taskElement, vecina);
            } else {
                list.insertBefore(vecina, taskElement);
            }
            await guardarOrden(list);
        }

        // Drag and Drop: solo reordena dentro de la columna de origen
        function addDragAndDropListeners() {
            const tasksElements = document.querySelectorAll('.task');
            const taskLists     = document.querySelectorAll('.task-list');

            tasksElements.forEach(task => {
                task.addEventListener('dragstart', () => {
                    draggedTask   = task;
                    draggedOrigin = task.closest('.task-list');
                    setTimeout(() => task.classList.add('dragging'), 0);
                });
                task.addEventListener('dragend', () => {
                    if (draggedTask) {
                        draggedTask.classList.remove('dragging');
                    }
                    draggedTask   = null;
                    draggedOrigin = null;
                });
            });

            taskLists.forEach(list => {
                list.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    if (list !== draggedOrigin) {
                        return;
                    }
                    const afterElement = getDragAfterElement(list, e.clientY);
                    const dragging     = document.querySelector('.dragging');
                    if (!dragging) {
                        return;
                    }
                    if (afterElement == null) {
                        list.appendChild(dragging);
                    } else {
                        list.insertBefore(dragging, afterElement);
                    }
                });
                list.addEventListener('drop', async () => {
                    if (!draggedTask) {
                        return;
                    }
                    if (list !== draggedOrigin) {
                        fnShowNotification('No se puede mover la tarea a otro responsable. Cambie el asignado desde Editar.', 'error');
                        return;
                    }
                    await guardarOrden(list);
                });
            });
        }

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.task:not(.dragging)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        addTaskBtn.addEventListener('click', () => {
            window.open("<?php echo base_url(); ?>/app_rrhh_task/add", "_blank");
        });
        listTaskBtn.addEventListener('click', () => {
            window.location = "<?php echo base_url(); ?>/app_rrhh_task/index";
        });
        refreshTaskBtn.addEventListener('click', () => {
            mostrarModal('ModalCargandoDatos');
            renderBoard(responsibleFilter.value || null);
        });

        responsibleFilter.addEventListener('change', (e) => {
            renderBoard(e.target.value || null);
        });

        // Cargar el tablero al inicio
        renderBoard();
    });
</script>
