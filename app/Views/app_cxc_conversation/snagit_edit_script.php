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
				this.txtTab2ListEmployer		= json.objListEmployer;
				this.txtTab2ListEmployerAsigned = json.objListEmployerAsigned.map(
					item => Number(item.entityID)
				);
				this.txtTab3CustomerPhone		= json.objCustomer.phoneNumber;
				
				
				// 馃攧 REFRESH obligatorio
				this.$nextTick(() => {
					$('#selectpickerMultiple').selectpicker('refresh')
				})
				
			
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
        async fnGuardarNotification() {
			
			// ?? VALIDACIóN
		    if (!this.txtTab3CustomerMessage || this.txtTab3CustomerMessage.trim() === '') {
		 	 this.message       = 'El mensaje no puede estar vacio';
		 	 this.mostrarAlerta = true;
		 	 this.error         = true;
		 	 return; // ? Detiene el envío
		    }
		  
		  
            this.guardando 	= true;			
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
			const json 		= await res.json();		
			debugger;
			if(this.entityID != Number(json.entityID))
			{
				window.location.href = '<?php echo base_url().'/app_cxc_conversation/edit/entityID/'; ?>'+json.entityID;
			}			
			
			this.txtTab3CustomerMessage = '';
			this.message 				= 'Mensaje enviado';
			this.mostrarAlerta 			= true;
			this.error 					= false;
            this.guardando 				= false;
            
        },
		async fnClearNotification() {
			this.txtTab3CustomerMessage = '';
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
        //WG-this.timer = setInterval(() => {
        //WG-    this.cargarListado();
        //WG-}, 3000);
		
        // una vez montado, hacemos visible la app
        document.getElementById('app').style.visibility = 'visible';
    }
}).mount('#app');
</script>