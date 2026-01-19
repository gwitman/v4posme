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
			imageFile: 					null,
			
			//Tab 1 
			objListNotification: 		[],			
			
			//Tab 2 
			txtTab2ListEmployer:		[],
			txtTab2CustomerName:		'',
			txtTab2CustomerPhone:		'',
			txtTab2ListEmployerAsigned: [],
			txtTab2WorkflowStageID:		205,
			txtTab2ListWorkflowStage:	[{workflowStageID:205,name:'Mantener'},{workflowStageID:206,name:'Finalizar'}],
			
			//Tab 3
			txtTab3CustomerPhone:		'',
			txtTab3CustomerMessage:		''	
			
			
        }
    },
    methods: {
        // TAB 1
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
				// 馃敶 CASO 1: success 	= false
				if (json.success === false) {
					this.objListNotification 	= [];
					this.mensaje 				= 'Ocurri贸 un error al cargar la informaci贸n';
					return;
				}
				
				
				// 馃煛 CASO 2: success = true pero sin datos
				if (json.success === true && (!json.data || json.data.length === 0)) {
					this.objListNotification 	= [];
					this.mensaje 				= 'No hay datos disponibles';
					return;
				}
				
				// 馃煝 CASO 3: success = true con datos				
				this.mensaje 				= '';
				this.objListNotification 	= json.data; // 馃敟 aqu铆 Vue limpia y vuelve a renderizar
				
			
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexi贸n con el servidor';
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
				// 馃敶 CASO 1: success 	= false
				if (json.success === false) {
					this.objListNotification 	= [];
					this.mensaje 				= 'Ocurri贸 un error al cargar la informaci贸n';
					return;
				}
				// 馃煝 CASO 3: success = true con datos
				this.mensaje 					= '';
				this.txtTab2CustomerName		= json.objNatural.firstName;
				this.txtTab2CustomerPhone		= json.objCustomer.phoneNumber;				
				
				this.txtTab2ListEmployerAsigned = json.objListEmployerAsigned.map(
					item => Number(item.entityID)
				);
				this.txtTab3CustomerPhone		= json.objCustomer.phoneNumber;
				
				//Parse los datos de 3 en  3
				//this.txtTab2ListEmployer		= json.objListEmployer;
				this.txtTab2ListEmployer 		= [];
				for (let i = 0; i < json.objListEmployer.length; i += 3) {
					this.txtTab2ListEmployer.push(json.objListEmployer.slice(i, i + 3));
				} 
				
				//// 馃攧 REFRESH obligatorio
				//this.$nextTick(() => {
				//	$('#selectpickerMultiple').selectpicker('refresh')
				//})
				
			
			} 
			catch (error) 
			{
                console.error(error);
                this.mensaje 				= 'Error de conexi贸n con el servidor';
                this.objListNotification 	= [];
            }
		},
        async fnGuardarCliente() {
            this.guardando = true;
			
			await fetch('<?php echo base_url(); ?>/app_cxc_api/setConversationCustomer', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						entityID: 					this.entityID,
						txtTab2CustomerName: 		this.txtTab2CustomerName,
						txtTab2CustomerPhone:		this.txtTab2CustomerPhone,
						txtTab2ListEmployerAsigned:	this.txtTab2ListEmployerAsigned,
						txtTab2WorkflowStageID:		this.txtTab2WorkflowStageID
					})
			});		
			
            this.message 		= 'Datos guardados con exito';
			this.mostrarAlerta 	= true;
			this.error 			= false;
            this.guardando 		= false;
			
			
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
			
			// ?? VALIDACIóN
		    if (!this.txtTab3CustomerMessage || this.txtTab3CustomerMessage.trim() === '') {
				 this.message       = 'El mensaje no puede estar vacio';
				 this.mostrarAlerta = true;
				 this.error         = true;
				 return; // ? Detiene el envío
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
				// ?? Aquí limpiamos el input
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
				// ?? Aquí limpiamos el input
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
			// ?? Aquí limpiamos el input
			this.clearFileInput();
            
        },
		async fnClearNotification() {
			this.txtTab3CustomerMessage = '';
			this.imageFile 				= false;
			// ?? Aquí limpiamos el input
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

        //WG-// refresco cada 3 segundos
        //WG-this.timer = setInterval(() => {
        //WG-    this.cargarListado();
        //WG-}, 3000);
		
        // una vez montado, hacemos visible la app
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
</script>