<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            tab: 						1,
			message: 					'',
            timer: 						null,
			entityID:					<?php echo $entityID; ?> ,
			guardando: 					false,			
			mostrarAlerta: 				false,
			mostrarWaite:				true,
			error:						false,
			modalImageSrc: 				'',
			audioUrl:					'',
			imageFile: 					null,
			sonidoReproducido:			false,
			parametroNoDebeVerFoto:			<?php echo $objParameterCONVERSATION_LIST_CONVERSATION_NOT_PHOTE; ?>,
			parametroNoDebeEscucharSonido:	<?php echo $objParameterCONVERSATION_LIST_CONVERSATION_NOT_BELL; ?>,
			unreadCount:				0,
			
			//Tab 1 - Nuevas variables para envío desde tab 1
			txtTab1Message:				'',
			txtTab1File:				null,
			txtTab1ImagePreview:		null,
			txtTab1PastedImage:			null,
			
			// Variables para grabación de audio
			isRecording:				false,
			mediaRecorder:				null,
			audioChunks:				[],
			txtTab1AudioBlob:			null,
			recordingTime:				0,
			recordingInterval:			null,
			
			//Tab 1 
			objListNotification: 		[],			
			
			//Tab 2 
			txtTab2ListEmployer:		[],
			txtTab2CustomerName:		'',
			txtTab2CustomerPhone:		'',
			txtTab2ListEmployerAsigned: [],
			txtTab2WorkflowStageID:		205,
			txtTab2ListWorkflowStage:	[{workflowStageID:205,name:'Mantener'},{workflowStageID:206,name:'Finalizar'}],
			txtTab2CategoryID:			'',
			txtTab2StatusID:			'',
			txtTab2SubCategoryID:		'',
			txtTab2ListCategoryID:		[],
			txtTab2ListSubCategoryID:	[],
			txtTab2ListWorkflowStageCustomer: [],
			txtTab2Budget:				'',
			txtTab2Location:			'',
			txtTab2Reference1:			'',
			txtTab2AllowWhatsappCollection: false,
			
			//Tab 3
			txtTab3CustomerPhone:		'',
			txtTab3CustomerMessage:		''	
			
			
        }
    },
    methods: {
        // TAB 1
		playChatNotification() {
			const audioCtx = new (window.AudioContext || window.webkitAudioContext)();

			const oscillator = audioCtx.createOscillator();
			const gainNode   = audioCtx.createGain();

			oscillator.type = "sine";
			oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);
			oscillator.frequency.exponentialRampToValueAtTime(
				1320,
				audioCtx.currentTime + 0.15
			);

			gainNode.gain.setValueAtTime(0.0001, audioCtx.currentTime);
			gainNode.gain.exponentialRampToValueAtTime(
				0.15,
				audioCtx.currentTime + 0.05
			);
			gainNode.gain.exponentialRampToValueAtTime(
				0.0001,
				audioCtx.currentTime + 0.6
			);

			oscillator.connect(gainNode);
			gainNode.connect(audioCtx.destination);

			oscillator.start();
			oscillator.stop(audioCtx.currentTime + 0.6);
		},
		verificarMensajesNoLeidos(objListNotification)
		{
			const hayNoLeidos = objListNotification.some(
				obj => Number(obj.isRead) == 0 
			);
	
			
			if (hayNoLeidos && this.sonidoReproducido == false) {
				this.playChatNotification();
				this.sonidoReproducido = true;
			}

			if (!hayNoLeidos) {
				this.sonidoReproducido = false;
			}
		},
        async cargarListado() {
			try {
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/getConversationNotification_ByCustomer', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							entityID: this.entityID
						})
				});				
				const json 			= await res.json();
				this.mostrarWaite	= false;
				// 🔴 CASO 1: success 	= false
				if (json.success === false) {
					this.objListNotification 	= [];
					this.mensaje 				= 'Ocurrió un error al cargar la información';
					return;
				}
				
				
				// 🟡 CASO 2: success = true pero sin datos
				if (json.success === true && (!json.data || json.data.length === 0)) {
					this.objListNotification 	= [];
					this.mensaje 				= 'No hay datos disponibles';
					return;
				}
				
				// 🟢 CASO 3: success = true con datos				
				this.mensaje 				= '';
				this.objListNotification 	= json.data; // 🔥 aquí Vue limpia y vuelve a renderizar
				
				// Contar mensajes no leídos
				this.unreadCount = json.data.filter(msg => msg.isRead == 0).length;
				
				this.verificarMensajesNoLeidos(json.data);
			
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexión con el servidor';
                this.objListNotification 	= [];
            }
			
        },
		openImageModal(imageUrl) 
		{
		  debugger;
		  this.modalImageSrc 	= imageUrl;
		  const modal 			= new bootstrap.Modal(
			document.getElementById('addNewCCModal')
		  );
		  modal.show();
		},
		openImageModalAudio(imageUrl) 
		{
		  
		  this.audioUrl 		= imageUrl;
		  const modal 			= new bootstrap.Modal(
			document.getElementById('addNewCCModalAudio')
		  );
		  modal.show();
		  
		  this.$nextTick(() => {
			  const audio = this.$refs.audioPlayer
			  if (audio) {
				audio.load()
				audio.play().catch(() => {})
			  }
		  });
			
		},
		
		// TAB 1 - Nuevos métodos para envío desde tab 1
		handlePaste(event) {
			const items = event.clipboardData.items;
			for (let i = 0; i < items.length; i++) {
				if (items[i].type.indexOf('image') !== -1) {
					event.preventDefault();
					const blob = items[i].getAsFile();
					this.txtTab1PastedImage = blob;
					
					// Crear preview
					const reader = new FileReader();
					reader.onload = (e) => {
						this.txtTab1ImagePreview = e.target.result;
					};
					reader.readAsDataURL(blob);
					break;
				}
			}
		},
		clearPastedImage() {
			this.txtTab1ImagePreview = null;
			this.txtTab1PastedImage = null;
		},
		onTab1FileChange(e) {
			this.txtTab1File = e.target.files[0];
			// Limpiar imagen pegada si se selecciona un archivo
			this.clearPastedImage();
		},
		clearTab1File() {
			this.txtTab1File = null;
		},
		clearTab1Message() {
			this.txtTab1Message = '';
			this.clearTab1File();
			this.clearPastedImage();
			this.clearRecordedAudio();
		},
		// Métodos para grabación de audio
		async startRecording() {
			try {
				const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
				this.mediaRecorder = new MediaRecorder(stream);
				this.audioChunks = [];
				this.recordingTime = 0;
				
				this.mediaRecorder.ondataavailable = (event) => {
					this.audioChunks.push(event.data);
				};
				
				this.mediaRecorder.onstop = () => {
					const audioBlob = new Blob(this.audioChunks, { type: 'audio/ogg; codecs=opus' });
					this.txtTab1AudioBlob = audioBlob;
					
					// Detener el stream
					stream.getTracks().forEach(track => track.stop());
					
					// Detener el contador
					if (this.recordingInterval) {
						clearInterval(this.recordingInterval);
						this.recordingInterval = null;
					}
				};
				
				this.mediaRecorder.start();
				this.isRecording = true;
				
				// Iniciar contador de tiempo
				this.recordingInterval = setInterval(() => {
					this.recordingTime++;
				}, 1000);
				
			} catch (error) {
				console.error('Error al acceder al micrófono:', error);
				this.message = 'No se pudo acceder al micrófono';
				this.mostrarAlerta = true;
				this.error = true;
			}
		},
		stopRecording() {
			if (this.mediaRecorder && this.isRecording) {
				this.mediaRecorder.stop();
				this.isRecording = false;
			}
		},
		clearRecordedAudio() {
			this.txtTab1AudioBlob = null;
			this.recordingTime = 0;
			this.audioChunks = [];
		},
		async fnEnviarMensajeTab1() {
			// Validación
			if (!this.txtTab1Message || this.txtTab1Message.trim() === '') {
				this.message = 'El mensaje no puede estar vacío';
				this.mostrarAlerta = true;
				this.error = true;
				return;
			}
			
			this.guardando = true;
			var json = true;
			
			// Determinar qué archivo enviar (audio grabado, imagen pegada o archivo seleccionado)
			const fileToSend = this.txtTab1AudioBlob || this.txtTab1PastedImage || this.txtTab1File;
			
			if (fileToSend) {
				const formData = new FormData();
				
				// Si es audio grabado, usar nombre específico
				if (this.txtTab1AudioBlob) {
					formData.append('image', fileToSend, 'audio_recording.ogg');
				} else {
					formData.append('image', fileToSend);
				}
				
				formData.append('entityID', this.entityID ?? '');
				formData.append('txtTab3CustomerPhone', this.txtTab2CustomerPhone ?? '');
				formData.append('txtTab3CustomerMessage', this.txtTab1Message ?? '');
				
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationNotification_ByCustomer', {
					method: 'POST',
					body: formData
				});
				json = await res.json();
			} else {
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationNotification_ByCustomer', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						entityID: this.entityID,
						txtTab3CustomerPhone: this.txtTab2CustomerPhone,
						txtTab3CustomerMessage: this.txtTab1Message
					})
				});
				json = await res.json();
			}
			
			// Mostrar errores
			if (json == undefined) {
				this.clearTab1Message();
				this.message = "Error al procesar información";
				this.mostrarAlerta = true;
				this.error = true;
				this.guardando = false;
				return;
			}
			
			if (json.success == false) {
				this.clearTab1Message();
				this.message = json.message;
				this.mostrarAlerta = true;
				this.error = true;
				this.guardando = false;
				return;
			}
			
			// Redirigir
			if (this.entityID != Number(json.entityID)) {
				window.location.href = '<?php echo base_url().'/app_cxc_conversation/edit/entityID/'; ?>' + json.entityID;
			}
			
			// Notificar
			this.clearTab1Message();
			this.message = 'Mensaje enviado';
			this.mostrarAlerta = true;
			this.error = false;
			this.guardando = false;
			
			// Recargar mensajes
			this.cargarListado();
		},
		
		// TAB 2
		async cargarDatosDePantalla(){
			try {
				const res = await fetch('<?php echo base_url(); ?>/app_cxc_api/getConversationCustomer_ByCustomer', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							entityID: this.entityID
						})
				});				
				const json 			= await res.json();				
				// 🔴 CASO 1: success 	= false
				if (json.success === false) {
					this.objListNotification 	= [];
					this.mensaje 				= 'Ocurrió un error al cargar la información';
					return;
				}
				// 🟢 CASO 3: success = true con datos
				this.mensaje 					= '';
				this.txtTab2CustomerName		= json.objNatural.firstName;
				this.txtTab2CustomerPhone		= json.objCustomer.phoneNumber;
				this.txtTab2CategoryID			= json.objCustomer.categoryID;
				this.txtTab2StatusID			= json.objCustomer.statusID;
				this.txtTab2SubCategoryID		= json.objCustomer.subCategoryID;
				this.txtTab2Budget				= json.objCustomer.budget;
				this.txtTab2Location			= json.objCustomer.location;
				this.txtTab2Reference1			= json.objCustomer.reference1;
				this.txtTab2AllowWhatsappCollection = json.objCustomer.allowWhatsappCollection == 1;
				
				this.txtTab2ListEmployerAsigned = json.objListEmployerAsigned.map(
					item => Number(item.entityID)
				);
				this.txtTab3CustomerPhone		= json.objCustomer.phoneNumber;
				
				// Cargar catálogos
				this.txtTab2ListCategoryID 			= json.objListCategoryID || [];
				this.txtTab2ListSubCategoryID 		= json.objListSubCategoryID || [];
				this.txtTab2ListWorkflowStageCustomer = json.objListWorkflowStage || [];
				
				//Parse los datos de 3 en  3
				//this.txtTab2ListEmployer		= json.objListEmployer;
				this.txtTab2ListEmployer 		= [];
				for (let i = 0; i < json.objListEmployer.length; i += 3) {
					this.txtTab2ListEmployer.push(json.objListEmployer.slice(i, i + 3));
				} 
				
				//// 🔄 REFRESH obligatorio
				//this.$nextTick(() => {
				//	$('#selectpickerMultiple').selectpicker('refresh')
				//})
				
			
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexión con el servidor';
                this.objListNotification 	= [];
            }
		},
        async fnGuardarCliente() {
            this.guardando = true;
			
			const res 	   = await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationCustomer', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						entityID: 					this.entityID,
						txtTab2CustomerName: 		this.txtTab2CustomerName,
						txtTab2CustomerPhone:		this.txtTab2CustomerPhone,
						txtTab2ListEmployerAsigned:	this.txtTab2ListEmployerAsigned,
						txtTab2WorkflowStageID:		this.txtTab2WorkflowStageID,
						txtTab2CategoryID:			this.txtTab2CategoryID,
						txtTab2StatusID:			this.txtTab2StatusID,
						txtTab2SubCategoryID:		this.txtTab2SubCategoryID,
						txtTab2Budget:				this.txtTab2Budget,
						txtTab2Location:			this.txtTab2Location,
						txtTab2Reference1:			this.txtTab2Reference1,
						txtTab2AllowWhatsappCollection: this.txtTab2AllowWhatsappCollection
					})
			});		
			
			
			json 				= await res.json();	
			if(this.entityID != Number(json.entityID) && json.redirect == true)
			{
				window.location.href = '<?php echo base_url().'/app_cxc_conversation/edit/entityID/'; ?>'+json.entityID;
				return;
			}
			else
			{
				this.message 		= 'Datos guardados con exito';
				this.mostrarAlerta 	= true;
				this.error 			= false;
				this.guardando 		= false;
			}
			
			
        },
		
		// TAB 3
		onFileChange(e) {
			this.imageFile = e.target.files[0];
		},
		clearFileInput() {
			// Limpiar la variable
			this.imageFile 	= null;
			// Limpiar visualmente el input
			const input 	= document.getElementById('formFile');
			if (input) input.value = '';
		},
        async fnGuardarNotification() {
			
			// ?? VALIDACI��N
		    if (!this.txtTab3CustomerMessage || this.txtTab3CustomerMessage.trim() === '') {
				 this.message       = 'El mensaje no puede estar vacio';
				 this.mostrarAlerta = true;
				 this.error         = true;
				 return; // ? Detiene el env��o
		    }
		  
			this.guardando 	= true;		
			var json		= true;
			//Enviar imagen en un mensaje aparte			
			if (this.imageFile) 
			{				
				const formData = new FormData();
				formData.append('image', this.imageFile);	
				formData.append('entityID', this.entityID ?? '');				
				formData.append('txtTab3CustomerPhone', this.txtTab3CustomerPhone ?? '');				
				formData.append('txtTab3CustomerMessage', this.txtTab3CustomerMessage ?? '');	
				const res 	= await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationNotification_ByCustomer', {
					method: 'POST',
					body: formData
				});
				json 		= await res.json();
			}
			else
			{	
				const res 		= await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationNotification_ByCustomer', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({
							entityID: 					this.entityID,
							txtTab3CustomerPhone:		this.txtTab3CustomerPhone,
							txtTab3CustomerMessage: 	this.txtTab3CustomerMessage
						})
				});		
				json 			= await res.json();		
			}
			
			//Mostrar errores
			if(json == undefined)
			{
				this.txtTab3CustomerMessage = '';
				this.imageFile 				= false;
				this.message 				= "Error al procesar informacion";
				this.mostrarAlerta 			= true;
				this.error 					= true;
				this.guardando 				= false;
				// ?? Aqu�� limpiamos el input
				this.clearFileInput();
				return;
			}
			
			
			
			//Mostrar errores
			if(json.success == false)
			{
				this.txtTab3CustomerMessage = '';
				this.imageFile 				= false;
				this.message 				= json.message;
				this.mostrarAlerta 			= true;
				this.error 					= true;
				this.guardando 				= false;
				// ?? Aqu�� limpiamos el input
				this.clearFileInput();
				return;
			}
			
			//Redirijir
			if(this.entityID != Number(json.entityID))
			{
				window.location.href = '<?php echo base_url().'/app_cxc_conversation/edit/entityID/'; ?>'+json.entityID;
			}
			
			//Notificar 
			this.txtTab3CustomerMessage = '';
			this.imageFile 				= false;
			this.message 				= 'Mensaje enviado';
			this.mostrarAlerta 			= true;
			this.error 					= false;
            this.guardando 				= false;
			// ?? Aqu�� limpiamos el input
			this.clearFileInput();
            
        },
		async fnClearNotification() {
			this.txtTab3CustomerMessage = '';
			this.imageFile 				= false;
			// ?? Aqu�� limpiamos el input
			this.clearFileInput();
		}
    },
    beforeUnmount() {
        clearInterval(this.timer);
    },
	mounted() {
		
		 // carga inicial
        this.cargarListado();
		this.cargarDatosDePantalla();

        // refresco cada 3 segundos
        this.timer = setInterval(() => {
            this.cargarListado();
        }, 3000);
		
        // una vez montado, hacemos visible la app
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
</script>