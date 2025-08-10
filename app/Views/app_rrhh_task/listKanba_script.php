<script>	

	//cerrarModal('ModalCargandoDatos');
	//mostrarModal('ModalCargandoDatos');
	mostrarModal('ModalCargandoDatos');

	document.addEventListener('DOMContentLoaded', () => {
		const kanbanBoard = document.getElementById('kanban-board');
		const responsibleFilter = document.getElementById('responsible-filter');
		const addTaskBtn = document.getElementById('add-task-btn');
		const listTaskBtn = document.getElementById('list-task-btn');
		const refreshTaskBtn = document.getElementById('refresh-task-btn');
		
		const taskTransactionMasterID	= document.getElementById('txtTransactionMasterID');
		const taskDescripcion 			= document.getElementById('txtDescripcion');
		const taskComentarioTareaArray 	= document.getElementById('txtComentarioTareaArray');
		const taskNote 					= document.getElementById('txtNote');		
		const taskPriorityID 		= document.getElementById('txtPriorityID');
		const taskAreaID 			= document.getElementById('txtAreaID');
		const taskDate  			= document.getElementById('txtDate');
		const taskNextVisit  		= document.getElementById('txtNextVisit');
		const taskStatusID  		= document.getElementById('txtStatusID');
		const taskAsignadoID 		= document.getElementById('txtAsignadoID');
		const taskEmisorID  		= document.getElementById('txtEmisorID');
		const taskReference1		= document.getElementById('txtReference1');
		const taskReference2		= document.getElementById('txtReference2');
		const taskReference3		= document.getElementById('txtReference3');
		const taskReference4		= document.getElementById('txtReference4');
		
		let tasks 		= [];
		let draggedTask = null;

		// Colores para responsables
		const responsableColors = {
			'Juan': '#28a745',
			'Maria': '#007bff',
			'Pedro': '#ffc107',
			// Añade más responsables y colores aquí
		};

		// Funciones de la API
		const api = {
			async fetchTasks() {
				try {
					const response = await fetch('<?php echo base_url(); ?>/app_transaction_master_api/getAll_TransactionMaster_Task/2');					
					return await response.json();
				} catch (error) {
					console.error('Error fetching tasks:', error);
					return [];
				}
			},
			async createTask(task) {
				try {
					
					const formData = new URLSearchParams();
					for (const key in task) {
					  if (task.hasOwnProperty(key)) {
						formData.append(key, task[key]);
					  }
					}

					const response = await fetch('<?php echo base_url(); ?>/app_transaction_master_api/create_TransactionMaster_Task/save/new', {
						method		: 'POST',
						//headers	: { 'Content-Type': 'application/json' },
						//body: JSON.stringify(task),
						
						headers	: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body	: formData.toString(),
					});
					return await response.json();
				} catch (error) {
					console.error('Error creating task:', error);
				}
			},
			async updateTask(id, task) {
				try {
					
					const formData = new URLSearchParams();
					for (const key in task) {
					  if (task.hasOwnProperty(key)) {
						formData.append(key, task[key]);
					  }
					}

					
					const response = await fetch('<?php echo base_url(); ?>/app_transaction_master_api/update_TransactionMaster_Task/save/edit/transactionMasterID/'+id, {
						method: 'POST',
						//headers: { 'Content-Type': 'application/json' },
						//body: JSON.stringify(task),
						
						headers	: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body	: formData.toString(),
						
					});
					return await response.json();
				} catch (error) {
					console.error('Error updating task:', error);
				}
			},
			async deleteTask(id) {
				try {
					await fetch('<?php echo base_url(); ?>/app_transaction_master_api/delete_TransactionMaster_Task/transactionNumber/'+id, 
					{
						method: 'GET',
					});
				} catch (error) {
					console.error('Error deleting task:', error);
				}
			},
			async updateTaskStatus(id, newStatus) {
				try {
					await fetch(`/api/tasks/${id}/status`, {
						method: 'PUT',
						headers: { 'Content-Type': 'application/json' },
						body: JSON.stringify({ transactionStatus: newStatus }),
					});
				} catch (error) {
					console.error('Error updating task status:', error);
				}
			},
			async reorderTasks(task) {
				try {
					
					let transactionMasterIDs = task.map(item => item.txtTransactionMasterID);
					let reference3 			 = task.map(item => item.txtReference3);
					
					const formData = new URLSearchParams({
						"txtTransactionMasterIDs": transactionMasterIDs,
						"txtReference3s": reference3
					});
	
					
					
					await fetch('<?php echo base_url(); ?>/app_transaction_master_api/updateOrden_TransactionMaster_Task/save/edit/transactionMasterID/0', {
						method: 'POST',
						//headers: { 'Content-Type': 'application/json' },
						//body: JSON.stringify(reorderData),
						
						headers	: { 'Content-Type': 'application/x-www-form-urlencoded' },
						body	: formData.toString(),
						
						
					});
				} catch (error) {
					console.error('Error reordering tasks:', error);
				}
			},
		};

		// Renderizar el tablero
		async function renderBoard(filteredResponsible = null) {
			
			
			const allTasks 	= await api.fetchTasks();						
			tasks 			= allTasks.data.filter(task => !filteredResponsible || task.responsable === filteredResponsible);
			
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

			kanbanBoard.innerHTML = '';
			if (tasks.length === 0 && filteredResponsible) {
				kanbanBoard.innerHTML = `<p>No hay tareas asignadas a ${filteredResponsible}.</p>`;
				return;
			}

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
					.sort((a, b) => a.Orden - b.Orden);

				responsibleTasks.forEach(task => {
					taskList.appendChild(createTaskElement(task));
				});

				column.appendChild(taskList);
				kanbanBoard.appendChild(column);
			});
			addDragAndDropListeners();
			cerrarModal('ModalCargandoDatos');
		}

		function createTaskElement(task) {
			
			const taskElement = document.createElement('div');
			taskElement.classList.add('task');
			taskElement.classList.add(`responsable-${task.responsable.replace(/\s/g, '')}`);
			taskElement.setAttribute('data-id', task.transactionMasterID);
			taskElement.setAttribute('draggable', true);

			const priorityClass = `priority-${task.priorityName}`;
			
			taskElement.innerHTML = `
				<h3>${task.reference4}</h3>
				<p>${task.descriptionReference}</p>
				<div class="meta">
					<span class="priority ${priorityClass} priority-Alta">Entrega: ${task.nextVisit}</span>
					<span>Estado: ${task.statusName}</span>
					<span>Categoría: ${task.categoryName}</span>
				</div>
				<div class="task-actions">					
					<button class="edit-btn">Editar</button>					
					<button class="delete-btn">${task.transactionNumber}</button>					
				</div>
			`;

			taskElement.querySelector('.edit-btn').addEventListener('click', (e,t) => {
				e.stopPropagation();				
				window.open("<?php echo base_url(); ?>/app_rrhh_task/edit/companyID/"+task.companyID+"/transactionID/"+task.transactionID+"/transactionMasterID/"+task.transactionMasterID, "_blank");				
			});

			
			
			
			return taskElement;
		}

		
		
		// Drag and Drop
		function addDragAndDropListeners() {
			
			const tasksElements = document.querySelectorAll('.task');
			const taskLists = document.querySelectorAll('.task-list');

			tasksElements.forEach(task => {
				task.addEventListener('dragstart', () => {
					draggedTask = task;
					setTimeout(() => task.classList.add('dragging'), 0);
				});
				task.addEventListener('dragend', () => {
					draggedTask.classList.remove('dragging');
					draggedTask = null;
				});
			});

			taskLists.forEach(list => {
				list.addEventListener('dragover', (e) => {
					e.preventDefault();
					const afterElement = getDragAfterElement(list, e.clientY);
					const dragging = document.querySelector('.dragging');
					if (afterElement == null) {
						list.appendChild(dragging);
					} else {
						list.insertBefore(dragging, afterElement);
					}
				});
				list.addEventListener('drop', async () => {
					
					if (draggedTask) {
						const responsible 		= draggedTask.closest('.column').getAttribute('data-responsible');
						const reorderedTasks 	= Array.from(list.children).map((item, index) => ({
							txtTransactionMasterID		: item.getAttribute('data-id'),
							txtReference3				: index + 1
						}));

						const changedTask = tasks.find(t => t.transactionMasterID == draggedTask.getAttribute('data-id'));						
						if (changedTask) 
						{
							
							 const updatedTask = {
								...changedTask,
								responsable		: responsible,
								txtReference3	: reorderedTasks.find(t => t.txtTransactionMasterID == changedTask.transactionMasterID).txtReference3,
							 };
							 
							 mostrarModal('ModalCargandoDatos');
							 //await api.updateTask(changedTask.transactionMasterID, updatedTask);
							 await api.reorderTasks(reorderedTasks);
							 renderBoard(responsibleFilter.value);
						}
					}
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
			}
		);
		listTaskBtn.addEventListener('click', () => {	
				window.location = "<?php echo base_url(); ?>/app_rrhh_task/index";
			}
		);
		refreshTaskBtn.addEventListener('click', () => {
				mostrarModal('ModalCargandoDatos');
				renderBoard(responsibleFilter.value);
			}
		);
		
		
		


		responsibleFilter.addEventListener('change', (e) => {
			renderBoard(e.target.value || null);
		});

		// Cargar el tablero al inicio
		renderBoard();
	});
</script>